<?php
class CNotifications
{
	
	public static function getOrder($order_uuid='', $payload = array() )
	{	
			
		$merchant_info = array(); 
		$items = array(); 
		$summary = array(); 
		$total = 0;
		$summary = array(); 
		$order = array(); 
		$meta = array();
		$order_info = array(); 
		$customer = array();
		$site_data = array(); 
		$print_settings = array();		
		$logo = ''; 
		$total = '';
		
		$exchange_rate = 1;
		//$model_order = COrders::get($order_uuid);
		$model_order = COrders::getWithoutCache($order_uuid);
		
		if($model_order->base_currency_code!=$model_order->use_currency_code){
			$exchange_rate = $model_order->exchange_rate>0?$model_order->exchange_rate:1;
			Price_Formatter::init($model_order->use_currency_code);
		}
		Price_Formatter::init($model_order->use_currency_code);			 
		COrders::setExchangeRate($exchange_rate);

		COrders::getContent($order_uuid,Yii::app()->language);
        //$merchant_id = COrders::getMerchantId($order_uuid);
		$merchant_id = $model_order->merchant_id;
        if (in_array('merchant_info',$payload)){
           $merchant_info = COrders::getMerchant($merchant_id,Yii::app()->language);
        }
        
        if (in_array('items',$payload)){
           $items = COrders::getItems();		    
        } 
        
        if (in_array('total',$payload)){
            $total = COrders::getTotal();
        }
        
        if (in_array('summary',$payload)){
            $summary = COrders::getSummary();	
        }
        
        if (in_array('order_info',$payload)){
          $order = COrders::orderInfo(Yii::app()->language, date("Y-m-d"),true );	
          $order_info = isset($order['order_info'])?$order['order_info']:'';
          
          if (in_array('customer',$payload)){
	          $client_id = $order?$order['order_info']['client_id']:0;		    
	          $customer = COrders::getClientInfo($client_id);
          }
        }        
        		                
		if (in_array('logo',$payload)){	     
			$print_settings = AOrderSettings::getPrintSettings();   
		}
				
		if (in_array('meta',$payload)){	     
			$meta  = COrders::orderMeta();
		}
    
		$site_data = OptionsTools::find(
          array('website_title','website_address','website_contact_phone','website_contact_email')
        );
	    $site = array(
	      'title'=>isset($site_data['website_title'])?$site_data['website_title']:'',
	      'address'=>isset($site_data['website_address'])?$site_data['website_address']:'',
	      'contact'=>isset($site_data['website_contact_phone'])?$site_data['website_contact_phone']:'',
	      'email'=>isset($site_data['website_contact_email'])?$site_data['website_contact_email']:'',		      
	    );
	    
	    $label = array(
	      'date'=>t("Delivery Date/Time"),
	      'items_ordered'=>t("ITEMS ORDERED"),
	      'qty'=>t("QTY"),
	      'price'=>t("PRICE"),
	      'delivery_address'=>t("DELIVERY ADDRESS"),
	      'summary'=>t("SUMMARY")
	    );	    
	    if($order_info['service_code']=="pickup"){
	    	$label['date']=t("Pickup Date/Time");
	    } elseif ( $order_info['service_code']=="dinein" ){
	    	$label['date']=t("Dinein Date/Time");
	    }
	    	    	   
	    $order_type=''; 
	    $services = isset($order['services'])?$order['services']:'';
	    $service_code = $order['order_info']['service_code'];	    
	    if($services[$service_code]){	    	
	    	$order['order_info']['order_type'] = $services[$service_code]['service_name'];
	    }
	    
	    
	    	   	    		                		      
	    $data = array(		       
	       'site'=>$site,
	       'merchant'=>$merchant_info,
	       'order'=>$order,		 
	       'order_info'=>$order_info,
	       'meta'=>$meta,
	       'items'=>$items,
	       'total'=>Price_Formatter::formatNumber($total),
	       'summary'=>$summary,		
	       'label'=>$label,
	       'customer'=>$customer,
	       'logo'=>isset($print_settings['receipt_logo'])?$print_settings['receipt_logo']:'',			       
	       'facebook'=>websiteDomain()."/".Yii::app()->theme->baseUrl."/assets/images/facebook.png",
	       'twitter'=>websiteDomain()."/".Yii::app()->theme->baseUrl."/assets/images/twitter.png",
	       'instagram'=>websiteDomain()."/".Yii::app()->theme->baseUrl."/assets/images/instagram.png",
	       'whatsapp'=>websiteDomain()."/".Yii::app()->theme->baseUrl."/assets/images/whatsapp.png",
	       'youtube'=>websiteDomain()."/".Yii::app()->theme->baseUrl."/assets/images/youtube.png",
	    );			
	    return $data;    
	}
	
	public static function getStatusActions($status='')
	{
		$criteria=new CDbCriteria();
		$criteria->alias="a";
		$criteria->select = "a.stats_id, a.action_type , a.action_value , b.description";
		$criteria->join='LEFT JOIN {{order_status}} b on  a.stats_id=b.stats_id ';
		$criteria->condition = "b.description = :description";
		$criteria->params = array(
		  ':description'=>$status,		 
		);
		$model=AR_order_status_actions::model()->findAll($criteria);
		if($model){
			$data = array(); $template_ids = array();
			foreach ($model as $item) {				
				$data[] = array(
				   'action_type'=>$item->action_type,
				   'action_value'=>$item->action_value,
				);
				$template_ids[]=$item->action_value;
			}
			return array(
			  'data'=>$data,
			  'template_ids'=>$template_ids
			);
		}
		throw new Exception( 'no results' );
	}

	public static function getStatusActionSingle($status='',$action_type='')
	{
		$criteria=new CDbCriteria();
		$criteria->alias="a";
		$criteria->select = "a.stats_id, a.action_type , a.action_value , b.description";
		$criteria->join='LEFT JOIN {{order_status}} b on  a.stats_id=b.stats_id ';
		$criteria->condition = "a.action_type=:action_type AND b.description = :description";
		$criteria->params = array(
		  ':action_type'=>$action_type,
		  ':description'=>$status,		 
		);
		$model=AR_order_status_actions::model()->find($criteria);
		if($model){			
			return $model->action_value;
		}
		throw new Exception( 'no results' );
	}
	
	public static function getSiteData()
	{
		$site_data = OptionsTools::find(
	      array('website_title','website_address','website_contact_phone','website_contact_email')
	    );
	    
	    $print_settings = AOrderSettings::getPrintSettings();   
	    
	    $site = array(
	      'title'=>isset($site_data['website_title'])?$site_data['website_title']:'',
	      'site_name'=>isset($site_data['website_title'])?$site_data['website_title']:'',
	      'address'=>isset($site_data['website_address'])?$site_data['website_address']:'',
	      'contact'=>isset($site_data['website_contact_phone'])?$site_data['website_contact_phone']:'',
	      'email'=>isset($site_data['website_contact_email'])?$site_data['website_contact_email']:'',		
	      'logo'=>isset($print_settings['receipt_logo'])?$print_settings['receipt_logo']:'',			       
	      'facebook'=>websiteDomain()."/".Yii::app()->theme->baseUrl."/assets/images/facebook.png",
	      'twitter'=>websiteDomain()."/".Yii::app()->theme->baseUrl."/assets/images/twitter.png",
	      'instagram'=>websiteDomain()."/".Yii::app()->theme->baseUrl."/assets/images/instagram.png",
	      'whatsapp'=>websiteDomain()."/".Yii::app()->theme->baseUrl."/assets/images/whatsapp.png",
	      'youtube'=>websiteDomain()."/".Yii::app()->theme->baseUrl."/assets/images/youtube.png",      
	    );		
	    return $site;	
	}

	public static function deleteNotifications($channel='',$ids='')
    {
        $criteria=new CDbCriteria;
        $criteria->addCondition("notication_channel=:notication_channel");
        $criteria->params = [':notication_channel'=>trim($channel)];
        $criteria->addInCondition("notification_uuid",$ids);
        $model = AR_notifications::model()->deleteAll($criteria);
        if($model){
            return true;
        }
        throw new Exception("Error deleting records."); 
    }

	public static function deleteByChannel($channel='',$ids='')
    {
        $criteria=new CDbCriteria;
        $criteria->addCondition("notication_channel=:notication_channel");
        $criteria->params = [':notication_channel'=>trim($channel)];        
        $model = AR_notifications::model()->deleteAll($criteria);
        if($model){
            return true;
        }
        throw new Exception("Error deleting records."); 
    }

	public static function sendReceiptByEmail($order_uuid='',$to='')
	{
		$template_id = 5;
		$templates = CTemplates::get($template_id, array('email'), Yii::app()->language );

		$data = CNotifications::getOrder($order_uuid , array(
			'merchant_info','items','summary','order_info','customer','logo','total'
		));

		$path = Yii::getPathOfAlias('backend_webroot')."/twig";
	    $loader = new \Twig\Loader\FilesystemLoader($path);
	    $twig = new \Twig\Environment($loader, [
		    'cache' => $path."/compilation_cache",
		    'debug'=>true
		]);

		$order_info = isset($data['order_info'])?$data['order_info']:'';
		$merchant_id = isset($order_info['merchant_id'])?$order_info['merchant_id']:'';
		$request_from = isset($order_info['request_from'])?$order_info['request_from']:'';
		$customer_name = $order_info['customer_name']?$order_info['customer_name']:'';
		$email_address = $order_info['contact_email']?$order_info['contact_email']:'';
		$contact_phone = $order_info['contact_number']?$order_info['contact_number']:'';
		$client_id = $order_info['client_id']?$order_info['client_id']:'';
		$merchant = isset($data['merchant'])?$data['merchant']:'';
		$merchant_name = isset($merchant['restaurant_name'])?$merchant['restaurant_name']:'';

		$message_parameters = array(); $sms_vars = [];
		if(is_array($data['order_info']) && count($data['order_info'])>=1){
			foreach ($data['order_info'] as $data_key=>$data_value) {
				if($data_key=="service_code"){
					$data_key='order_type';
				}
				$message_parameters["{{{$data_key}}}"]=$data_value;
			}
		}
		if(is_array($data['merchant']) && count($data['merchant'])>=1){
			foreach ($data['merchant'] as $data_key=>$data_value) {
				$message_parameters["{{{$data_key}}}"]=$data_value;
			}
		}

		$items = isset($templates[0])?$templates[0]:'';
		if($items){
			$email_subject = isset($items['title'])?$items['title']:'';
			$template = isset($items['content'])?$items['content']:'';
			$twig_template = $twig->createTemplate($template);
			$template = $twig_template->render($data);
			$twig_subject = $twig->createTemplate($email_subject);
            $email_subject = $twig_subject->render($data);
			if(empty($email_subject)){
				throw new Exception("Email subject is empty");
			}
			if(empty($template)){
				throw new Exception("Email template is empty");
			}
			if(empty($to)){
				throw new Exception("Email address is empty");
			}
			if(CommonUtility::sendEmail($to,$customer_name,$email_subject,$template)){
				return true;
			} else throw new Exception("Failed to send email.");
		} else {
			throw new Exception("Template not found");
		}
		throw new Exception("Undefined error");
	}

	public static function sendReceiptByWhatsapp($order_uuid='',$mobile_number='')
	{
		$data = CNotifications::getOrder($order_uuid , array(
			'merchant_info','items','summary','order_info','customer','logo','total'
		));
		$merchant = isset($data['merchant'])?$data['merchant']:'';
		$order_info = isset($data['order'])? ($data['order']?$data['order']['order_info']:'') :'';
		$order_items = isset($data['items'])?$data['items']:'';
		$total = isset($data['total'])?$data['total']:'';

		$customer_name = isset($order_info['customer_name'])?$order_info['customer_name']:'';
		$order_id = isset($order_info['order_id'])?$order_info['order_id']:'';
		$restaurant_name = isset($merchant['restaurant_name'])?$merchant['restaurant_name']:'';
		$restaurant_name = isset($merchant['restaurant_name'])?$merchant['restaurant_name']:'';
		$merchant_address = isset($merchant['address'])?$merchant['address']:'';
		$merchant_contact = isset($merchant['contact_phone'])?$merchant['contact_phone']:'';

		$service_code = isset($order_info['service_code'])?$order_info['service_code']:'';
		$order_type = isset($order_info['order_type'])?$order_info['order_type']:'';

		if($service_code=="delivery"){
			$delivery_address = t("Delivery Address:");
			$delivery_address.="\\n";
			$delivery_address.= isset($order_info['complete_delivery_address'])?$order_info['complete_delivery_address']:'';
		} else {
		   $delivery_address = t("{transaction_type} Address:",[
			'{transaction_type}'=>$order_type
		   ]);
		   $delivery_address.="\\n";
		   $delivery_address.= $merchant_address;
		}

		$line_items = '';
		//$line_break = '<br/>';
		$line_break = "\\n";
		if(is_array($order_items) && count($order_items)>=1){
			foreach ($order_items as $items) {
				$price = isset($items['price'])?$items['price']:'';
				$size_name = isset($price['size_name'])?$price['size_name']:'';
				$line_items.= $items['qty']."x ".$items['item_name'];
				if(!empty($size_name)){
					$line_items.=" ($size_name)";
				}
				$line_items.=$line_break;
				$attributes = isset($items['attributes'])?$items['attributes']:'';
				if(is_array($attributes) && count($attributes)>=1){
					foreach ($attributes as $attributes_val) {
						if(is_array($attributes_val) && count($attributes_val)>=1){
							foreach ($attributes_val as $indexKey=> $attributesVal) {
								$line_items.=$attributesVal;
								if($indexKey!==count($attributes_val)-1){
									$line_items.=",";
								}
							}
							$line_items.=$line_break;
					    }
					}
				}

				$addons = isset($items['addons'])?$items['addons']:'';
				if(is_array($addons) && count($addons)>=1){
					foreach ($addons as $addonsVal) {
						$line_items.= $addonsVal['subcategory_name'];
						$addon_items = isset($addonsVal['addon_items'])?$addonsVal['addon_items']:'';
						$line_items.=$line_break;
						if(is_array($addon_items) && count($addon_items)>=1){
							foreach ($addon_items as $addon_itemsVal) {
								$line_items.= "- ".$addon_itemsVal['qty']."x ".$addon_itemsVal['sub_item_name'];
								$line_items.=$line_break;
							}
						}
					}
				}
				$line_items.=$line_break;
			}
			// end each items
		}

		$parameters = [
			'1' => $customer_name,
			'2' => $restaurant_name,
			'3' => $order_id,
			'4' => $line_items,
			'5' => $total,
			'6' => $delivery_address,
			'7' => $merchant_contact
		];

		$options = OptionsTools::find(['whatsapp_phone_number','whatsapp_token','whatsapp_receipt_templatename']);
		$whatsapp_phone_number = isset($options['whatsapp_phone_number'])?$options['whatsapp_phone_number']:'';
		$whatsapp_token = isset($options['whatsapp_token'])?$options['whatsapp_token']:'';
		$template_name = isset($options['whatsapp_receipt_templatename'])?$options['whatsapp_receipt_templatename']:'';

		$params = [
			'messaging_product'=>'whatsapp',
			'to'=>$mobile_number,
			'type'=>'template',
			'template'=>[
				'name'=>$template_name,
				'language'=>[
					'code'=>'en_US'
				],
				'components'=>[
					[
						'type' => 'body',
						'parameters' => array_map(function($value) {
							return ['type' => 'text', 'text' => $value];
						}, array_values($parameters))
					]
				]
			],
		];

		CWhatsApp::setPhone($whatsapp_phone_number);
		CWhatsApp::setToken($whatsapp_token);
		CWhatsApp::sendMessage($params);

	}

}
/*end class*/