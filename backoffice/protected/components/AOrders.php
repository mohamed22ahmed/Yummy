<?php
class AOrders
{
	public static function getOrderAll($merchant_id=0, $status=array(), $schedule=false, $with_delivery=false, $date='',$datetime='',$filter=array(), $limit=100)
	{
		$criteria = new CDbCriteria;
        $criteria->alias = "a";
		$criteria->select = "order_id,order_uuid,a.merchant_id, c.restaurant_name,
		client_id,a.status,payment_status,service_code,formatted_address,
		whento_deliver,delivery_date,delivery_time,delivery_time_end,
		is_view,is_critical,a.date_created,
		(
		 select sum(qty) 
		 from {{ordernew_item}}
		 where order_id = a.order_id
		) as total_items,
		
	    IF(whento_deliver='now', 
	      TIMESTAMPDIFF(MINUTE, a.date_created, NOW())
	    , 
	     TIMESTAMPDIFF(MINUTE, concat(delivery_date,' ',delivery_time), NOW())
	    ) as min_diff
		";		
		$criteria->order = "a.order_id ASC";
        $criteria->join='LEFT JOIN {{merchant}} c on  a.merchant_id = c.merchant_id';

        if($merchant_id != 0){
            $criteria->addCondition('a.merchant_id = "'.$merchant_id.'" ');
        }

        if($with_delivery == ''){
            $criteria->addInCondition('a.status', (array) $status);
        }

        $today = date('Y-m-d');

        if($with_delivery != ''){
            $criteria->addInCondition('a.delivery_status',(array) $status);
            $criteria->addCondition('delivery_date = "'.$date.'" ');
        }else if($schedule != ''){
            $criteria->addCondition('a.whento_deliver = "schedule" ');
            $criteria->addCondition('a.delivery_date >= "'.$today.'" ');
        } else{
            if (!in_array('prepending', $status)) {
                $criteria->addCondition('delivery_date = "'.$date.'" ');
				$criteria->addCondition('whento_deliver = "now" ');
            }
        }

        $criteria->limit = $limit;
		$model=AR_ordernew::model()->findAll($criteria);

		if($model){
			$data = array(); $all_merchant = array(); $all_order = array();
			foreach ($model as $item) {
				$delivery_date = '';
				$all_merchant[] = $item->merchant_id;
				$all_order[] = $item->order_id;

			    $items = t("[item_count] items",array('[item_count]'=>$item->total_items));
			    if($item->total_items<=1){
			    	$items = t("[item_count] item",array('[item_count]'=>$item->total_items));
			    }

				$data[]=array(
				  'order_name'=>t("Order #[order_id]",array('[order_id]'=>$item->order_id)),
				  'order_id'=>$item->order_id,
				  'order_uuid'=>$item->order_uuid,
				  'client_id'=>$item->order_id,
                  'merchant_name' => $item->restaurant_name,
				  'status'=>$item->status,
				  'payment_status'=>$item->payment_status,
				  'service_code'=>$item->service_code,
				  'formatted_address'=>$item->formatted_address,
				  'delivery_date'=>$delivery_date,
				  'total_items'=>$items,
				  'is_view'=>$item->is_view,
				  'is_critical'=>0
				);
			}

			return array(
			 'data'=>$data,
			 'all_merchant'=>$all_merchant,
			 'all_order'=>$all_order,
			 'total'=>count($model)
			);
		}
		throw new Exception( 'No results' );
	}		
	
	public static function getOrderMeta($order_id=array())
	{
		$criteria = new CDbCriteria;		
		$criteria->order = "order_id ASC";				
		$criteria->addColumnCondition(array('meta_name' => 'customer_name'));
		$criteria->addInCondition('order_id', (array)$order_id );		
		$model=AR_ordernew_meta::model()->findAll($criteria);		
		if($model){
			$data = array();
			foreach ($model as $item) {
				$data[$item->order_id][$item->meta_name] = $item->meta_value;
			}
			return $data;
		}
		return false;
	}
	
	public static function getAllTabsStatus()
	{
		$new_order = AOrders::getOrderTabsStatus("new_order");
		$order_processing = AOrders::getOrderTabsStatus("order_processing");
		$order_ready = AOrders::getOrderTabsStatus("order_ready");
		$completed_today = AOrders::getOrderTabsStatus("completed_today");
		return array(
		  'new_order'=>$new_order,
		  'order_processing'=>$order_processing,
		  'order_ready'=>$order_ready,
		  'completed_today'=>$completed_today,
		);
	}
	
	public static function getOrderTabsStatus($group_name='')
	{
		$stmt="
		SELECT description as status
		FROM {{order_status}}
		WHERE 
		stats_id IN (
		 select stats_id from {{order_settings_tabs}}
		 where group_name =".q($group_name)."
		)
		";	
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
			$data = array();
			foreach ($res as $val) {				
				array_push($data,$val['status']);
			}
			return $data;
		}
		return false;
	}
	
	public static function getOrderButtons($group_name='', $order_type='')
	{		
		$criteria = new CDbCriteria;
		$criteria->order = "id ASC";						
		if($order_type){
			$criteria->addCondition("group_name=:group_name AND order_type=:order_type");
			$criteria->params = array(
			  ':group_name'=>$group_name,
			  ':order_type'=>trim($order_type)
			);
		} else $criteria->addColumnCondition(array('group_name' => $group_name ));
		
		$model = AR_order_settings_buttons::model()->findAll($criteria);	
		if($model){
			$data = array();
			foreach ($model as $items) {
				$data[]=array(
				  'button_name'=>t($items->button_name),
				  'uuid'=>$items->uuid,
				  'class_name'=>$items->class_name,
				  'do_actions'=>$items->do_actions,					  			 
				);
			}
			return $data;
		}
		return false;
	}
	
	public static function getOrderButtonStatus($uuid='')
	{
		$stmt="
		SELECT a.description as status
		FROM {{order_status}} a
		LEFT JOIN {{order_settings_buttons}} b
		ON
		a.stats_id = b.stats_id	
		WHERE b.uuid = ".q($uuid)."
		";		
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){			
			return $res['status'];
		}
		throw new Exception( 'no results' );
	}
	
	public static function getOrderButtonActions($uuid='')
	{
		$model = AR_order_settings_buttons::model()->find("uuid=:uuid",array(
		 ':uuid'=>$uuid
		));
		if($model){
		   return $model->do_actions;	
		}
		throw new Exception( 'no results' );
	}
	
	public static function rejectionList($meta_name='rejection_list',$language=KMRS_DEFAULT_LANGUAGE)
	{		
		$stmt = "
		SELECT a.meta_value as meta_value_original,
		b.meta_value
		FROM {{admin_meta}}	a
		left JOIN (
			SELECT meta_id,meta_value FROM {{admin_meta_translation}} where language = ".q($language)."
		) b 
		on a.meta_id = b.meta_id

		WHERE
		meta_name=".q($meta_name)."
		";		
		$dependency = CCacheData::dependency();					
        if($res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency)->createCommand($stmt)->queryAll()){
			foreach ($res as $items) {
				$data[] = !empty($items['meta_value'])? trim($items['meta_value']) : trim($items['meta_value_original']);
			}
			return $data;
		}
		throw new Exception( 'no results' );
	}
	
	public static function getOrderHistory($order_uuid='' , $lang=KMRS_DEFAULT_LANGUAGE)
	{
		$stmt="
		SELECT created_at,order_id,status,change_by,
		remarks,ramarks_trans,latitude,longitude
		FROM {{ordernew_history}}
		WHERE order_id = (
		 select order_id from {{ordernew}}
		 where order_uuid=".q($order_uuid)."
		)
		ORDER BY id DESC
		";		
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
			$data = array();
			foreach ($res as $item) {		
				$remarks = $item['remarks'];
				if(!empty($item['ramarks_trans'])){
					$ramarks_trans = json_decode($item['ramarks_trans'],true);
					$remarks = t($item['remarks'],(array)$ramarks_trans);
				}
				
				$change_by = '';
				if(!empty($item['change_by'])){
					$change_by = t("change status by {{user}}",array(
					  '{{user}}'=>Yii::app()->input->xssClean($item['change_by'])
					));
				}
				
				$data[] = array(
				  'created_at'=>Date_Formatter::dateTime($item['created_at'],"dd MMM yyyy h:mm:ss a"),
				  'status'=>$item['status'],
				  'remarks'=>$remarks,
				  'change_by'=>$change_by,
				  'latitude'=>$item['latitude'],
				  'longitude'=>$item['longitude'],
				);
			}
			return $data;
		}
		throw new Exception( 'no results' );
	}
	
	public static function getOrderCountPerStatus($merchant_id=0, $status=array(), $date = '',$filter_date=true)
	{
		$criteria=new CDbCriteria();
		if($merchant_id !== "null") {	   
            $criteria->condition = "merchant_id=:merchant_id";

            $criteria->params  = array(
              ':merchant_id'=>intval($merchant_id)
            );
        }

		$criteria->addInCondition('status', (array) $status );
        if(in_array('new', (array) $status )){
            $criteria->addSearchCondition('t.delivery_date', $date);
            $criteria->addCondition('whento_deliver != "schedule"' );
        }
		else if($filter_date){
			$criteria->addSearchCondition('t.delivery_date', $date );
		}

		$count = AR_ordernew::model()->count($criteria);

		return intval($count);
	}

	public static function getOrderCountWithDelivery($status=array(), $date = '')
	{

		$criteria=new CDbCriteria();
        $criteria->addInCondition('delivery_status', (array) $status );
        $criteria->addSearchCondition('delivery_date', $date );
		$count = AR_ordernew::model()->count($criteria);
		return intval($count);
	}

	public static function getOrderCountPrepending($status=array(), $date = '')
	{
		$criteria=new CDbCriteria();
        $criteria->addInCondition('status', (array) $status);
        $criteria->addSearchCondition('t.delivery_date', $date );
        $count = AR_ordernew::model()->count($criteria);
		return intval($count);
	}

	public static function getOrderCountSchedule($merchant_id=0, $status=array() , $date = '')
	{		
		$criteria=new CDbCriteria();
		if($merchant_id !== "null") {	   
			$criteria->condition = "merchant_id=:merchant_id AND whento_deliver=:whento_deliver";		    
			$criteria->params  = array(
			  ':merchant_id'=>intval($merchant_id),		  
			  ':whento_deliver'=>"schedule"
			);
		} else {
			$criteria->condition = "whento_deliver=:whento_deliver";		    
			$criteria->params  = array(
			  ':whento_deliver'=>"schedule"
			);
		}	    
	   
		$criteria->addInCondition('status', (array) $status );					
		$criteria->addCondition('delivery_date >= "'.$date.'" ');
		
		$count = AR_ordernew::model()->count($criteria); 		
		return intval($count);
	}
	
	public static function getAllOrderCount($merchant_id=0)
	{
		$criteria=new CDbCriteria();	    
		if($merchant_id !== "null") {
			$criteria->condition = "merchant_id=:merchant_id";
			  
			$criteria->params  = array(
			  ':merchant_id'=>intval($merchant_id),
			);
		}
		$not_in_status = AttributesTools::initialStatus();		
		$criteria->addNotInCondition('t.status', (array) array($not_in_status) );
		
		$count = AR_ordernew::model()->count($criteria);
		return intval($count);
	}
	
	public static function OrderNotViewed($merchant_id=0, $status=array() , $date = '')
	{
		$criteria=new CDbCriteria();
        $criteria->join='
		LEFT JOIN {{merchant}} c on  t.merchant_id = c.merchant_id 
		';
        $criteria->condition = "(c.merchant_id = :merchant_id OR c.parent_id = :parent_id) AND t.is_view=:is_view";
        $criteria->params  = array(
            ':merchant_id'=>intval($merchant_id),
            ':parent_id'=>intval($merchant_id),
            ':is_view'=>0,
        );

		$criteria->addInCondition('t.status', (array) $status );
		$criteria->addSearchCondition('t.delivery_date', $date );
		
		$count = AR_ordernew::model()->count($criteria); 
		return intval($count);
	}
	
	public static function getOrdersTotal($merchant_id=0, $status=array(), $not_in_status=array() )
	{				
		$criteria=new CDbCriteria();
	    $criteria->select="order_id,order_uuid,total,status";
	    
	    if($merchant_id>0){
		    $criteria->condition = "merchant_id=:merchant_id";		    
			$criteria->params  = array(
			  ':merchant_id'=>intval($merchant_id),		  
			);
	    }
		if(is_array($status) && count($status)>=1){
			$criteria->addInCondition('status', (array) $status );
		}
		if(is_array($not_in_status) && count($not_in_status)>=1){
			$criteria->addNotInCondition('status', (array) $not_in_status );
		}		
		$count = AR_ordernew::model()->count($criteria); 
		if($count){
         //   return 4110;
			return $count;
		}
		return 0;
	}
	
	public static function getOrderSummary($merchant_id=0, $status=array() , $exchange_rate_options='')
	{		
		$criteria=new CDbCriteria();		

		switch ($exchange_rate_options) {
			case 'exchange_rate_merchant_to_admin':				
				$criteria->select="sum((total*exchange_rate_merchant_to_admin)) as total";
				break;
			
			default:
			    $criteria->select="sum(total) as total";
				break;
		}
		
		if($merchant_id>0){
			$criteria->condition = "merchant_id=:merchant_id";		    
			$criteria->params  = array(
			  ':merchant_id'=>intval($merchant_id)		  
			);
		}		
		$criteria->addInCondition('status', (array) $status );
		$model = AR_ordernew::model()->find($criteria); 
		if($model){
			return $model->total;
		}
		return 0;
	}
	
	public static function getTotalRefund($merchant_id=0, $status= array() , $exchange_rate_options='')
	{
		$criteria=new CDbCriteria();		
		
		switch ($exchange_rate_options) {
			case 'exchange_rate_merchant_to_admin':
				$criteria->select="sum((trans_amount*exchange_rate_merchant_to_admin)) as total";
				break;
			
			default:
			    $criteria->select="sum(trans_amount) as total";
				break;
		}
		
		if($merchant_id>0){
			$criteria->condition = "merchant_id=:merchant_id AND status=:status";		    
			$criteria->params  = array(
			  ':merchant_id'=>intval($merchant_id),
			  ':status'=>"paid"
			);		
		} else {
			$criteria->condition = "status=:status";		    
			$criteria->params  = array(			  
			  ':status'=>"paid"
			);		
		}
		
		$criteria->addInCondition('transaction_name', (array) $status );
		$model = AR_ordernew_transaction::model()->find($criteria); 		
		if($model){
			return $model->total;
		}
		return 0;
	}

	public static function findUseDiscount($order_id=0,$owner='merchant',$meta_name='promo_id')
	{
		$stmt = "
		SELECT a.order_id,b.voucher_id
		from st_ordernew_meta a
		left join {{voucher_new}} b
		ON
		a.meta_value = b.voucher_id

		WHERE
		a.order_id=".q(intval($order_id))."
		AND
		a.meta_name=".q($meta_name)."
		AND b.voucher_owner=".q($owner)."
		";				
		if($res = CCacheData::queryRow($stmt)){				
			return $res;
		}
		return false;
	}
}