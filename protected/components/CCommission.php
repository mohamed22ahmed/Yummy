<?php class CCommission
{
	
	public static function getCommissionValue($merchant_type='',$commision_type='',$merchant_commission=0,$sub_total=0,$total=0)
	{
		$data = array(); $commission = 0; $merchant_earning = 0;
		$merchant_commission_raw = $merchant_commission;
								
		$model = AR_merchant_type::model()->find('type_id=:type_id', 
		array(':type_id'=>$merchant_type)); 		
		if($model){
			
			if($model->based_on=="subtotal"){
				$total_based = $sub_total;	
			} else $total_based = $total;		
						
			if($commision_type=="fixed"){
				$commission = $merchant_commission;
				$merchant_earning = floatval($total_based) - floatval($commission);
			} else if ( $commision_type=='percentage' ) {
				$merchant_commission = floatval($merchant_commission)/100;
				$commission = floatval($total_based) * $merchant_commission;
				$merchant_earning = floatval($total_based) - floatval($commission);
			} else return false;
					
			return array(
				'commission_value'=>$merchant_commission_raw,
				'commission_based'=>$model->based_on,
				'commission'=>floatval($commission),
				'merchant_earning'=>floatval($merchant_earning)
			);
		}	
		return false;
	}

	public static function getCommissionValueNew($data=[])
	{		
						
		$commission = 0; $merchant_earning = 0; $merchant_commission_raw = 0;

		$points_enabled = isset(Yii::app()->params['settings']['points_enabled'])?Yii::app()->params['settings']['points_enabled']:false;
		$points_enabled = $points_enabled==1?true:false;
		$points_cover_cost = isset(Yii::app()->params['settings']['points_cover_cost'])?Yii::app()->params['settings']['points_cover_cost']:'website';
		
		$merchant_id = isset($data['merchant_id'])?intval($data['merchant_id']):'';
		$transaction_type = isset($data['transaction_type'])?$data['transaction_type']:'';
		$merchant_type = isset($data['merchant_type'])?$data['merchant_type']:2;
		$commission_type = isset($data['commision_type'])?$data['commision_type']:'';
		$merchant_commission = isset($data['merchant_commission'])?floatval($data['merchant_commission']):0;
		$sub_total = isset($data['sub_total'])?floatval($data['sub_total']):0;		
		$sub_total_without_cnd = isset($data['sub_total_without_cnd'])?floatval($data['sub_total_without_cnd']):0;
		$total = isset($data['total'])?floatval($data['total']):0;
		$service_fee = isset($data['service_fee'])?floatval($data['service_fee']):0;
		$delivery_fee = isset($data['delivery_fee'])?floatval($data['delivery_fee']):0;
		$tax_settings = isset($data['tax_settings'])?$data['tax_settings']:'';
		$tax_total = isset($data['tax_total'])?floatval($data['tax_total']):0;
		$self_delivery = isset($data['self_delivery'])?$data['self_delivery']:false;
		

		if($new_commission = CMerchants::getCommissionByTransaction($merchant_id,$transaction_type)){						
			$commission_type = $new_commission['commission_type'];
			$merchant_commission = $new_commission['commission'];			
		}

		// dump($data);
		// dump("sub_total=>$sub_total");
		// dump("tax_total=>$tax_total");
		// dump("commission_type=>$commission_type");
		// dump("merchant_commission=>$merchant_commission");
		//dump($tax_settings);		
		$merchant_commission_raw = $merchant_commission;

		if($points_enabled && $points_cover_cost=="website"){
			$sub_total = $sub_total_without_cnd>0?$sub_total_without_cnd:$sub_total;
		}
		// dump("sub_total_without_cnd=>$sub_total_without_cnd");
		// dump("points_cover_cost=>$points_cover_cost");
		// dump("sub_total=>$sub_total");

		$model = AR_merchant_type::model()->find('type_id=:type_id', 
		array(':type_id'=>$merchant_type)); 		
		if($model){						
			switch ($model->based_on) {
				case 'subtotal':							
					if($commission_type=="fixed"){
						$commission = $merchant_commission;
				        $merchant_earning = floatval($sub_total) - floatval($commission);
					} else if ( $commission_type=="percentage"){
						$merchant_commission = floatval($merchant_commission)/100;						
						$commission = floatval($sub_total) * $merchant_commission;
				        $merchant_earning = floatval($sub_total) - floatval($commission);
					}
					break;				
				case 'method2':							
					$tax_rate = 0;
					$tax_type = isset($tax_settings['tax_type'])?$tax_settings['tax_type']:'';
					$tax_list = isset($tax_settings['tax'])?$tax_settings['tax']:'';
					if($tax_type=="standard"){
						if(is_array($tax_list) && count($tax_list)>=1){
							foreach ($tax_list as $tax_items) {								
								$tax_rate = isset($tax_items['tax_rate'])?$tax_items['tax_rate']:0;
							}
						}
					}					

					if($commission_type=="percentage"){
						$commission = floatval($sub_total) * floatval($merchant_commission)/100;
					} else if ( $commission_type=="fixed"){
						$commission = $merchant_commission;
					}					
					$commission_on_tax = floatval($commission) * ( floatval($tax_rate) /100);					
					$commission_with_tax = $commission + $commission_on_tax;					
					$merchant_earning = floatval($sub_total) - floatval($commission_with_tax);
					if($self_delivery){
						$commission = $commission_with_tax + $service_fee;
					} else {						
						$commission = $commission_with_tax + $service_fee + $delivery_fee;
					}
					break;
				case 'method3':	
					$tax_rate = 0;
					$tax_type = isset($tax_settings['tax_type'])?$tax_settings['tax_type']:'';
					$tax_list = isset($tax_settings['tax'])?$tax_settings['tax']:'';
					if($tax_type=="standard"){
						if(is_array($tax_list) && count($tax_list)>=1){
							foreach ($tax_list as $tax_items) {								
								$tax_rate = isset($tax_items['tax_rate'])?$tax_items['tax_rate']:0;
							}
						}
					}					

					if($commission_type=="percentage"){
						$commission = floatval($sub_total) * floatval($merchant_commission)/100;
					} else if ( $commission_type=="fixed"){
						$commission = $merchant_commission;
					}				
					$commission_on_tax = floatval($commission) * ( floatval($tax_rate) /100);
					$tax_on_service_fee = floatval($service_fee) * ( floatval($tax_rate) /100);
					
					$merchant_earning = floatval($sub_total) + floatval($tax_total) - floatval($commission) - floatval($commission_on_tax);
					$commission = floatval($commission) + floatval($commission_on_tax) + floatval($service_fee)  + floatval($tax_on_service_fee);
					break;
			}			

			return array(
				'commission_value'=>$merchant_commission_raw,
				'commission_based'=>$model->based_on,				
				'merchant_earning'=>floatval($merchant_earning),
				'commission'=>floatval($commission),
				'commission_type'=>$commission_type
			);
		}
		return false;
	}
	
}
/*end class*/