<?php
require 'php-jwt/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class COrders
{
	private static $order_id;
	private static $content;
	private static $condition;
	private static $summary=array();	
	private static $packaging_fee=0;
	private static $order;
	public static $buy_again = false;
	public static $summary_total = 0;
	public static $tax_condition;
	public static $tax_type;
	private static $tax_group=array();
	private static $tax_settings=array();
	private static $tax_for_delivery=array();
	private static $tax_total;	
	private static $exchange_rate=1;
	
	public static function newOrderStatus()
	{
		// Check for previous orders with statuses accepted, complete, or delivered
		$previousOrder = AR_ordernew::model()->find(
			"client_id = :client_id AND status IN ('accepted', 'complete', 'delivered')",
			array(":client_id" => Yii::app()->user->id)
		);

		// If no previous orders are found, set the status to 'prepending'
		if (empty($previousOrder)) {
			$status = 'prepending';
		} else {
			// If a previous order exists with the relevant status, set status to 'new'
			$status = 'new';
		}

		// Optionally, log the result for debugging
		error_log("=== Order Status: " . $status . " ===");

		return $status;
	}

    public static function prependingOrderStatus()
	{
    	$status = AR_admin_meta::getValue('status_prepending_order');
		$status = isset($status['meta_value'])?$status['meta_value']:'prepending';
		return $status;
	}
    public static function withDeliveryOrderStatus()
	{
    	$status = AR_admin_meta::getValue('status_with_delivery_order');
		$status = isset($status['meta_value'])?$status['meta_value']:'with_delivery';
		return $status;
	}
	
	public static function getStatusTab($group_name = array() )
	{
		$data = array();
		$criteria = new CDbCriteria;		
		$criteria->alias="a";
		$criteria->select = "a.group_name,a.stats_id , b.description as status";		
		$criteria->join='LEFT JOIN {{order_status}} b on  a.stats_id=b.stats_id ';
		//$criteria->addInCondition('b.group_name', array('new_order','order_processing') );
		$criteria->addInCondition('a.group_name', array('new_order','order_processing') );		
		$model=AR_order_settings_tabs::model()->findAll($criteria);		
    	if($model){			
    		foreach ($model as $items) {
    			$data[]=$items->status;
    		}
    	}
    	return $data;
	}

	public static function getStatusTab2($group_name = array() )
	{
		$data = array();
		$criteria = new CDbCriteria;		
		$criteria->alias="a";
		$criteria->select = "a.group_name,a.stats_id , b.description as status";		
		$criteria->join='LEFT JOIN {{order_status}} b on  a.stats_id=b.stats_id ';
		$criteria->addInCondition('a.group_name', (array)$group_name );
		$model=AR_order_settings_tabs::model()->findAll($criteria);		
    	if($model){
    		foreach ($model as $items) {
    			$data[]=$items->status;
    		}
    	}
    	return $data;
	}

	public static function getStatusAllowedToCancel()
	{
		$new = self::newOrderStatus();
		$data = self::getStatusTab(array('new_order','order_processing'));		
		if($new){
		   array_push($data,$new);
		}
		return (array)$data;
	}
	
	public static function get($order_uuid='')
	{				
		$dependency = CCacheData::dependency();
		$model = AR_ordernew::model()->cache(Yii::app()->params->cache, $dependency)->find('order_uuid=:order_uuid',array(':order_uuid'=>$order_uuid)); 
		if($model){
			return $model;
		}
		throw new Exception( 'no results' );
	}

	public static function getWithoutCache($order_uuid='')
	{						
		$model = AR_ordernew::model()->find('order_uuid=:order_uuid',array(':order_uuid'=>$order_uuid)); 
		if($model){
			return $model;
		}
		throw new Exception( 'no results' );
	}
	
	public static function getByID($order_id='')
	{				
		$model = AR_ordernew::model()->find('order_id=:order_id',array(':order_id'=>$order_id)); 
		if($model){
			return $model;
		}
		throw new Exception( 'no results' );
	}
	
	public static function getMerchantId($order_uuid='')
	{				
		$model = AR_ordernew::model()->find('order_uuid=:order_uuid',array(':order_uuid'=>$order_uuid)); 
		if($model){
			return intval($model->merchant_id);
		}
		throw new Exception( 'no results' );
	}
	
	public static function getContent($order_uiid='', $lang = KMRS_DEFAULT_LANGUAGE)
	{
		COrders::$condition = array();
		self::$content = array();
		self::$packaging_fee = 0;
		
		$dependency = CCacheData::dependency();
		$model = AR_ordernew::model()->cache(Yii::app()->params->cache, $dependency)->find('order_uuid=:order_uuid', 
		array(':order_uuid'=>$order_uiid)); 				
		if($model){		   
		   COrders::$order_id = $model->order_id;	
		   COrders::$order = $model; 	   
		   $content = COrders::getOrder($model->order_id,$lang);	
		   $subcategory = COrders::getSubcategory($model->order_id,$lang);
		   $size = COrders::getSize($model->order_id,$lang);		
		   $addon_items = COrders::getAddonItems($model->order_id,$lang);
		   
		   $meta_cooking = COrders::getMetaCooking($model->order_id,$lang);
		   $meta_ingredients = COrders::getMetaIngredients($model->order_id,$lang);		
		   		   
		   $model->packaging_fee = self::$packaging_fee;
		   
		   COrders::getUseTax();
		   
		   COrders::setCondition($model);
		   
		   //if($content){
		   	  self::$content = array(			  
				  'content'=>$content,
				  'subcategory'=>$subcategory?$subcategory:'',
			      'size'=>$size?$size:'', 
			      'addon_items'=>$addon_items?$addon_items:'',
			      'attributes'=>array(
			        'cooking_ref'=>$meta_cooking,
			        'ingredients'=>$meta_ingredients
			      )
				);
			  return self::$content;
		   //} 
		   //throw new Exception( 'order is empty' );
		   
		} else {
			if(self::$buy_again){
				throw new Exception( 'this order has no items available' );
			} else throw new Exception( 'order not found' );			
		}
	}	
			
	public static function getOrderID()
	{
		return COrders::$order_id;
	}
	
	public static function getUseTax()
	{
		$order_id = self::getOrderID();
		
		$criteria=new CDbCriteria();
		$criteria->condition = "order_id=:order_id ";		    
		$criteria->params  = array(			  
		  ':order_id'=>intval($order_id)
		);
		$criteria->addInCondition('meta_name', array('tax_use','tax_for_delivery') );
		$model = AR_ordernew_meta::model()->findAll($criteria); 		
		if($model){
			foreach ($model as $item) {
				if($item->meta_name=="tax_use"){
					$tax_settings = !empty($item->meta_value) ? json_decode($item->meta_value,true): false;
					if(is_array($tax_settings) && count($tax_settings)>=1){			   
					   self::setTaxSettings( $tax_settings ) ;
					   self::setTaxType( isset($tax_settings['tax_type'])?$tax_settings['tax_type']:'' ) ;
					   self::addTaxCondition( isset($tax_settings['tax'])?$tax_settings['tax']:''  );
					}
				} elseif ( $item->meta_name=="tax_for_delivery" ){
					$tax_delivery = !empty($item->meta_value) ? json_decode($item->meta_value,true): false;
					if(is_array($tax_delivery) && count($tax_delivery)>=1){			   
						self::setTaxForDelivery($tax_delivery);
					}
				}
			}
		}				
	}
	
	public static function setTaxSettings($data = array() )
	{
		if(is_array($data) && count($data)>=1){
		   self::$tax_settings = $data;
		}
	}
	
	public static function getTaxSettings()
	{
		if(is_array(self::$tax_settings) && count(self::$tax_settings)>=1){
		   return self::$tax_settings;
		}
		return false;
	}
	
	public static function addTaxCondition($data = array() )
	{
		if(is_array($data) && count($data)>=1){
		   self::$tax_condition = $data;
		}
	}
	
	public static function getTaxCondition()
	{
		if(is_array(self::$tax_condition) && count(self::$tax_condition)>=1){
		   return self::$tax_condition;
		}
		return false;
	}
	
	public static function setTaxForDelivery($data = array() )
	{
		if(is_array($data) && count($data)>=1){
		   self::$tax_for_delivery = $data;
		}
	}
	
	public static function getTaxForDelivery()
	{
		if(is_array(self::$tax_for_delivery) && count(self::$tax_for_delivery)>=1){
		   return self::$tax_for_delivery;
		}
		return false;
	}
	
	public static function setTaxType($tax_type='')
	{
		if(!empty($tax_type)){
			self::$tax_type = $tax_type;
		}
	}
	
	public static function getTaxType()
	{
		if(!empty(self::$tax_type)){
			return self::$tax_type;
		}
		return false;
	}
	
	public static function addTaxGroup($key=0,$data = array() )
	{		
		$current_data=0;
		if(isset(self::$tax_group[$key])){
			$current_data = self::$tax_group[$key]['total'];
		}
		self::$tax_group[$key] = array(
		  'tax_in_price'=>isset($data['tax_in_price'])?$data['tax_in_price']:false,
		  'total'=>floatval($current_data) + floatval($data['tax_total'])
		);
	}
	
	public static function getTaxGroup()
	{
		if(is_array(self::$tax_group) && count(self::$tax_group)>=1){
		   return self::$tax_group;
		}
		return false;
	}
	
	public static function addTax($tax=array(), $total=0){
		if(is_array($tax) && count($tax)>=1 && $total>0){
			foreach ($tax as $tax_item) {
				$tax_rate = isset($tax_item['tax_rate']) ? floatval($tax_item['tax_rate']) :0;
				$tax_rate = $tax_rate/100;				
				$tax_total = $tax_rate*floatval($total);								
				self::addTaxGroup($tax_item['tax_id'], array(
				 'tax_total'=>$tax_total,
				 'tax_in_price'=>$tax_item['tax_in_price']
				));				
			}
		}
	}
	
	public static function getOrder($order_id='', $lang = KMRS_DEFAULT_LANGUAGE)
	{
				
		$and='';
		if(self::$buy_again){
			$and = "
			AND b.status ='publish'	
			AND b.available = 1
			";
		}
		
		$data = array();
		$stmt="
		SELECT 
		a.item_row,
		a.order_id,
		a.cat_id,
		d.category_name,
		a.item_token,
		a.qty,a.special_instructions,a.price,a.discount,a.discount_type,
		a.item_size_id,
		a.if_sold_out,		
		a.item_changes,
		a.item_changes_meta1,
		a.tax_use,				
		IF(COALESCE(NULLIF(c.item_name, ''), '') = '', b.item_name, c.item_name) as item_name,
		b.photo, b.path,
		b.non_taxable as taxable,	
		b.packaging_fee,	
		b.packaging_incremental,
		e.item_status,
					
		(
		 select GROUP_CONCAT(item_row,';',subcat_id,';',sub_item_id,';',qty,';',multi_option,';',price,';',addons_total)
		 from {{ordernew_addons}}
		 where order_id = a.order_id
		 and item_row = a.item_row
		) as addon_items,
		
		(
		 select GROUP_CONCAT(item_row,';',charge_name,';',additional_charge)
		 from {{ordernew_additional_charge}}
		 where order_id = a.order_id
		 and item_row = a.item_row
		) as additional_charge,
		
		(
		 select GROUP_CONCAT(meta_name,';',meta_value)
		 from {{ordernew_attributes}}
		 where order_id = a.order_id
		 and item_row = a.item_row
		) as attributes,
		
		(
		 select item_name from {{item_translation}}
		 where item_id IN (
		   select item_id from {{item}}
		   where item_token = a.item_changes_meta1
		   and language = ".q($lang)."
		 )		 
		 limit 0,1
		) as item_name_replace
				
		FROM {{ordernew_item}} a
		LEFT JOIN {{item}} b
		ON
		a.item_token = b.item_token

		left JOIN (
			SELECT item_id,item_name FROM {{item_translation}} where language = ".q($lang)."
		) c		
		on a.item_id = c.item_id

		left JOIN (
			SELECT cat_id,category_name FROM {{category_translation}} where language = ".q($lang)."
		) d		
		on a.cat_id = d.cat_id

		left JOIN (
			SELECT item_status,order_ref_id FROM {{kitchen_order}} 
		) e		
		on a.item_row = e.order_ref_id
		
		WHERE order_id = ".q($order_id)."			
		$and
		ORDER BY id ASC
		";							
		$dependency = CCacheData::dependency();
		if($res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency )->createCommand($stmt)->queryAll()){												
			foreach ($res as $val) {				
				$addon_items = array();
				$_addon_items = isset($val['addon_items'])? explode(",",$val['addon_items']) :'';
				if(is_array($_addon_items) && count($_addon_items)>=1){
					foreach ($_addon_items as $val3) {						
						$addonitems = explode(";",$val3);					
						$row = isset($addonitems[0])?$addonitems[0]:'';
						$subcat_id = isset($addonitems[1])?$addonitems[1]:'';
						$sub_item_id = isset($addonitems[2])?$addonitems[2]:'';
						$qty = isset($addonitems[3])?$addonitems[3]:'';
						$multi_option = isset($addonitems[4])?$addonitems[4]:'';
						$price = isset($addonitems[5])?$addonitems[5]:'';
						$addons_total = isset($addonitems[6])?$addonitems[6]:'';
						$addon_items[$row][$subcat_id][$sub_item_id] = array(
						 'qty'=>$qty,
						 'price'=>$price,
						 'addons_total'=>$addons_total,
						 'multi_option'=>$multi_option
						);
					}
				}
				
				$attributes = array();
				$attributes_raw = isset($val['attributes'])? explode(",",$val['attributes']) :'';
				if(is_array($attributes_raw) && count($attributes_raw)>=1){
					foreach ($attributes_raw as $val4) {
						$attributes_item = explode(";",$val4);
						$meta_name = isset($attributes_item[0])?$attributes_item[0]:'';
						$meta_value = isset($attributes_item[1])?$attributes_item[1]:'';						
						$attributes[$meta_name][$meta_value]=$meta_value;
					}
				}
				
				$additional_charge = array();
				$additional_charge_raw = isset($val['additional_charge'])? explode(",",$val['additional_charge']) :'';
				if(is_array($additional_charge_raw) && count($additional_charge_raw)>=1){
					foreach ($additional_charge_raw as $items_charge) {
						$items_charge_row = explode(";",$items_charge);
						$row = isset($items_charge_row[0])?$items_charge_row[0]:'';
						$charge_name = isset($items_charge_row[1])?$items_charge_row[1]:'';
						$charge_total = isset($items_charge_row[2])?$items_charge_row[2]:'';
						$additional_charge[]=array(
						  'charge_name'=>t($charge_name),
						  'charge_total'=>$charge_total,
						  'pretty_price'=>Price_Formatter::formatNumber($charge_total),
						);
					}
				}
								
				if($val['packaging_fee']>0 && $val['packaging_incremental']<=0){
					self::$packaging_fee+= $val['packaging_fee'];					
				} elseif ( $val['packaging_fee']>0 && $val['packaging_incremental']>0){
					self::$packaging_fee+= floatval($val['packaging_fee']) * intval($val['qty']);
				}
								
				$data[]=array(
				   'item_row'=>$val['item_row'],
				   'item_status'=>$val['item_status'],
				   'cat_id'=>$val['cat_id'],
				   'category_name'=>$val['category_name'],
				   'item_token'=>$val['item_token'],				   
				   'item_name'=> $val['item_name'],
				   'item_changes'=>$val['item_changes'],
				   'item_name_replace'=>Yii::app()->input->xssClean($val['item_name_replace']),		
				   'url_image'=>CMedia::getImage($val['photo'],$val['path'],'@thumbnail',CommonUtility::getPlaceholderPhoto('item')),
				   'item_size_id'=>$val['item_size_id'],
				   'qty'=>intval($val['qty']),
				   'if_sold_out'=>$val['if_sold_out'],
				   'price'=>floatval($val['price']),
				   'discount'=>floatval($val['discount']),
				   'discount_type'=>trim($val['discount_type']),
				   'special_instructions'=>Yii::app()->input->xssClean($val['special_instructions']),	
				   'addon_items'=>$addon_items,
				   'additional_charge'=>$additional_charge,
				   'attributes'=>$attributes,
				   'tax'=> !empty($val['tax_use']) ?  json_decode($val['tax_use'],true) : array(),
				);
			}						
			return $data;
		}
		//throw new Exception( 'this order has no items' );
	}
	
	public static function itemCount($order_id='')
	{
		$stmt="
		SELECT SUM(qty) as item_count
		FROM {{ordernew_item}}
		WHERE order_id=".q($order_id)."		
		";		
		if( $res = Yii::app()->db->createCommand($stmt)->queryRow() ){
			return $res['item_count'];
		}
		return 0;
	}
	
	public static function getSubcategory($order_id='', $lang = KMRS_DEFAULT_LANGUAGE)
	{
		$stmt="
		SELECT a.subcat_id , 
		a.subcategory_name as original_subcategory_name,
		b.subcategory_name
		FROM {{subcategory}} a		
		left JOIN (
			SELECT subcat_id, subcategory_name FROM {{subcategory_translation}} where language = ".q($lang)."
		) b 		
		ON
		a.subcat_id = b.subcat_id			
		WHERE 
		a.subcat_id IN (
		  select subcat_id from {{ordernew_addons}}
		  where order_id =".q($order_id)."
		)
		";				
		$dependency = CCacheData::dependency();		
		if($res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency )->createCommand($stmt)->queryAll()){			
			$data = array();
			foreach ($res as $val) {				
				$data[$val['subcat_id']] = array(
				  'subcat_id'=>$val['subcat_id'],
				  'subcategory_name'=> empty($val['subcategory_name'])? $val['original_subcategory_name'] : $val['subcategory_name']
				);
			}
			return $data;
		}
		return false;
	}
	
	public static function getSize($order_id='', $lang = KMRS_DEFAULT_LANGUAGE)
	{
		$stmt="
		SELECT a.item_size_id,a.size_name,a.original_size_name,
		a.price , a.discount,
		a.discount_type,		
		 (
		  select count(*) from {{view_item_lang_size}}
		  where item_size_id = a.item_size_id 		  
		  and CURDATE() >= discount_start and CURDATE() <= discount_end
		 ) as discount_valid
		
		FROM {{view_item_lang_size}}	a		
				
		WHERE a.language IN ('',".q($lang).")
		AND a.item_size_id IN (
		 select item_size_id from {{ordernew_item}}
		 where order_id =".q($order_id)."
		)
		";		
		$dependency = CCacheData::dependency();					
        if($res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency)->createCommand($stmt)->queryAll()){		
			$data = array();
			foreach ($res as $val) {				
				$data[$val['item_size_id']] = array(
				  'item_size_id'=>$val['item_size_id'],
				  'size_name'=> empty($val['size_name'])?$val['original_size_name']:$val['size_name'],
				  'price'=>$val['price'],
				  'discount'=>$val['discount'],
				  'discount_type'=>$val['discount_type'],
				  'discount_valid'=>$val['discount_valid'],				  
				);
			}
			return $data;
		}
		return false;
	}
	
	public static function getAddonItems($order_id='', $lang = KMRS_DEFAULT_LANGUAGE)
	{
		$stmt="
		SELECT 
		a.sub_item_id,
		a.sub_item_name as original_sub_item_name, 
		a.item_description as original_item_description,
		b.sub_item_name,
		b.item_description,
		a.price, a.photo,a.path
		
		FROM {{subcategory_item}} a	

		left JOIN (
			SELECT sub_item_id, sub_item_name, item_description 
			FROM {{subcategory_item_translation}} where language  = ".q($lang)."
		) b 

		on a.sub_item_id = b.sub_item_id
		
		WHERE a.status = 'publish'		
		AND a.sub_item_id IN (		  
		  select sub_item_id from {{ordernew_addons}}
		  where order_id =".q($order_id)."
		)		
		";					
		$dependency = CCacheData::dependency();					
		if($res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency)->createCommand($stmt)->queryAll()){		   
			$data = array();
			foreach ($res as $val) {	
				$sub_item_id = (integer) $val['sub_item_id'];
				$data[$sub_item_id] = array(
				  'sub_item_id'=>$sub_item_id,
				  'sub_item_name'=> empty($val['sub_item_name']) ?Yii::app()->input->xssClean($val['original_sub_item_name']) : Yii::app()->input->xssClean($val['sub_item_name']),
				  'item_description'=>empty($val['item_description']) ? Yii::app()->input->xssClean($val['original_item_description']) : Yii::app()->input->xssClean($val['item_description']),
				  'price'=>(float)$val['price'],
				  'pretty_price'=>Price_Formatter::formatNumber($val['price']),
				  'url_image'=>CMedia::getImage($val['photo'],$val['path'],CommonUtility::getPlaceholderPhoto('item')),
				);
			}			
			return $data;
		}
		return false;
	}
	
	public static function getAdditionalCharge($order_id='')
	{
		$model = AR_ordernew_additional_charge::model()->findAll('order_id=:order_id',array(
		 ':order_id'=>intval($order_id)
		));
		if($model){			
			$data = array();
			foreach ($model as $items) {
				$data[$items->item_row] = array(
				  'charge_name'=>$items->charge_name,
				  'additional_charge'=>floatval($items->additional_charge),
				  'pretty_price'=>Price_Formatter::formatNumber( floatval($items->additional_charge) ),
				);
			}
			return $data;
		}
		return false;
	}

	public static function getMetaCooking($order_id='', $lang = KMRS_DEFAULT_LANGUAGE)
	{
		$stmt="
		SELECT 
		a.cook_id,
		a.cooking_name as original_cooking_name,
		b.cooking_name
		FROM {{cooking_ref}} a
		left JOIN (
			SELECT cook_id, cooking_name FROM {{cooking_ref_translation}} where language = ".q($lang)."
		) b 
		on a.cook_id = b.cook_id

		WHERE
		a.cook_id IN (
		  select meta_value from {{ordernew_attributes}}
		  where order_id =".q($order_id)."
		  and meta_name = 'cooking_ref'
		)
		";						
		$dependency = CCacheData::dependency();					
        if($res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency)->createCommand($stmt)->queryAll()){		   						
			$data = array();
			foreach ($res as $val) {
				$id = (integer) $val['cook_id'];
				$data[$id] = empty($val['cooking_name']) ? Yii::app()->input->xssClean($val['original_cooking_name']) : Yii::app()->input->xssClean($val['cooking_name']);
			}
			return $data;
		}
		return false;
	}
	
	public static function getMetaIngredients($order_id='', $lang = KMRS_DEFAULT_LANGUAGE)
	{
		$stmt="
		SELECT 
		a.ingredients_id,
		a.ingredients_name as original_ingredients_name, 
		b.ingredients_name
		FROM {{ingredients}} a
		left JOIN (
			SELECT ingredients_id, ingredients_name FROM {{ingredients_translation}} where language = ".q($lang)."
		) b 
		on a.ingredients_id = b.ingredients_id

		WHERE 
		a.ingredients_id IN (
		  select meta_value from {{ordernew_attributes}}
		  where order_id =".q($order_id)."
		  and meta_name = 'ingredients'
		)
		";								
		$dependency = CCacheData::dependency();		
		if($res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency)->createCommand($stmt)->queryAll()){		   	
			$data = array();
			foreach ($res as $val) {
				$id = (integer) $val['ingredients_id'];
				$data[$id] = empty($val['ingredients_name']) ? Yii::app()->input->xssClean($val['original_ingredients_name']) : Yii::app()->input->xssClean($val['ingredients_name']);
			}
			return $data;
		}
		return false;
	}
	
	public static function isEmpty()
	{
		if(is_array(self::$content) && count(self::$content)>=1){
			return false;
		}
		return true;
	}
	
	protected static function valueIsPercentage($value='')
    {
        return (preg_match('/%/', $value) == 1);
    }
        
    protected static function valueIsToBeSubtracted($value)
    {
        return (preg_match('/\-/', $value) == 1);
    }
       
    protected static function valueIsToBeAdded($value)
    {
        return (preg_match('/\+/', $value) == 1);
    }
    
    protected static function normalizePrice($price)
    {
        return (is_string($price)) ? floatval(COrders::cleanValue($price)) : $price;
    }
    
    protected static function cleanValue($value)
    {
        return str_replace(array('%','-','+'),'',$value);
    }
    
    public static function cleanNumber($value)
    {
    	return self::cleanValue($value);
    }
	
	public static function parseItemPrice($value='')
    {
    	$price = 0;    	
    	if(is_array($value) && count($value)>=1){    		
    		if ($value['discount']>0){
    			$raw_price = isset($value['price'])?floatval($value['price']):0;
    			$raw_discount = isset($value['discount'])?floatval($value['discount']):0;
    			if ( $value['discount_type']=="percentage"){    				
    				$price = floatval($raw_price) - ((floatval($raw_discount)/100)*floatval($raw_price));
    			} else $price = floatval($raw_price) - floatval($raw_discount);
    		} else $price = floatval($value['price']);
    	}
    	return $price;
    }
    
	public static function getItems()
	{
		$results = array(); $exchange_rate = self::getExchangeRate();
		if(!COrders::isEmpty()){
			$items = isset(COrders::$content['content'])?COrders::$content['content']:'';
    		$size = isset(COrders::$content['size'])?COrders::$content['size']:'';    		
    		$subcategory = isset(COrders::$content['subcategory'])?COrders::$content['subcategory']:'';
    		$addon_items = isset(COrders::$content['addon_items'])?COrders::$content['addon_items']:'';
    		$attributes = isset(COrders::$content['attributes'])?COrders::$content['attributes']:'';    		    		
    		
    		if(is_array($items) && count($items)>=1){
    		foreach ($items as $val) {
    			
    			$qty = intval($val['qty']);
    			$item_size_id = isset($val['item_size_id'])?(integer)$val['item_size_id']:0;
    			$item_price_data = isset($size[$item_size_id])?$size[$item_size_id]:'';      			
    			$size_name = isset($item_price_data['size_name'])?$item_price_data['size_name']:'';    			
    			$item_price = floatval($val['price']) * $exchange_rate;    			
    			$item_price_less_discount = COrders::parseItemPrice($val) * $exchange_rate;  				
    			    			
    			/*ADDON*/    			
    			$results_addon = array(); $results_addon_item = array();
    			if(is_array($val['addon_items']) && count($val['addon_items'])>=1){
    				foreach ($val['addon_items'] as $addon_category) {
    					foreach ($addon_category as $addon_cat_id => $addons_item) {
    						$results_addon_item = array();
    						if(is_array($addons_item) && count($addons_item)>=1){
    							foreach ($addons_item as $sub_item_id=>$sub_item_data) {    								
    								if(isset($addon_items[$sub_item_id])){   
    									$addons_qty = isset($sub_item_data['qty'])?intval($sub_item_data['qty']):1;    	
    									$addons_price = isset($sub_item_data['price'])?floatval($sub_item_data['price']):0;
    									$addons_total = isset($sub_item_data['addons_total'])?floatval($sub_item_data['addons_total']):0;
    									$multi_option = isset($sub_item_data['qty'])?trim($sub_item_data['multi_option']):'';    	    									    									
    									$addon_items[$sub_item_id]['price']=$addons_price;
    									$addon_items[$sub_item_id]['pretty_price']=Price_Formatter::formatNumber($addons_price);
    									
    									$addon_items[$sub_item_id]['qty']=$multi_option=="multiple"?$addons_qty:$qty;
    									$addon_items[$sub_item_id]['addons_total']=$addons_total;
    									$addon_items[$sub_item_id]['pretty_addons_total']=Price_Formatter::formatNumber($addons_total);
    									$addon_items[$sub_item_id]['multiple'] = $multi_option;
    									
    									$results_addon_item[]=$addon_items[$sub_item_id];
    								}
    							}    							
    						}
    						
    						$results_addon[] = array(
    						  'subcat_id'=>$addon_cat_id,
    						  'subcategory_name'=>isset($subcategory[$addon_cat_id]['subcategory_name'])?trim($subcategory[$addon_cat_id]['subcategory_name']):'',
    						  'addon_items'=>$results_addon_item
    						);    
    					}
    				}
    			}

    			/*ATTRIBUTES*/
    			$attributes_list=array(); $attributes_list_raw = array();
    			if(is_array($val['attributes']) && count($val['attributes'])>=1 ){
    				foreach ($val['attributes'] as $meta_key=>$data_attributes) {    					
    					$attributes_items = array(); $attributes_items_raw = array();
    					if(is_array($data_attributes) && count($data_attributes)>=1){
    						foreach ($data_attributes as $meta_value) {    	    							
    							if(isset($attributes[$meta_key])){
    							   $attributes_items[] = isset($attributes[$meta_key][$meta_value])?$attributes[$meta_key][$meta_value]:'';
    							   $attributes_items_raw[$meta_value] = isset($attributes[$meta_key][$meta_value])?$attributes[$meta_key][$meta_value]:'';
    							}
    						}
    						$attributes_list[$meta_key] = $attributes_items;
    						$attributes_list_raw[$meta_key] = $attributes_items_raw;
    					}
    				}
    			}    
    			
    			/*ADDITIONAL CHARGE*/
    			$additional_charge_list = array();
    			if(is_array($val['additional_charge']) && count($val['additional_charge'])>=1 ){
    				$additional_charge_list = $val['additional_charge'];
    			}
    			
    			
    			$total = intval($val['qty']) * floatval($item_price);
    			$total_after_discount = intval($val['qty']) * floatval($item_price_less_discount);
    			$results[] = array(
    			   'item_row'=>$val['item_row'],
				   'item_status_raw'=>$val['item_status']?$val['item_status']:'',
				   'item_status_class'=>CommonUtility::toSeoURL($val['item_status']),
				   'item_status'=> $val['item_status']?  t($val['item_status']) :'',
    			   'cat_id'=>$val['cat_id'],    			  
				   'category_name'=>$val['category_name'], 
    			   'item_token'=>$val['item_token'],
    			   'item_name'=>CHtml::decode(Yii::app()->input->xssClean($val['item_name'])),
    			   'item_changes'=>$val['item_changes'],
    			   'item_name_replace'=>CHtml::decode(Yii::app()->input->xssClean($val['item_name_replace'])),
    			   'url_image'=>$val['url_image'],
    			   'special_instructions'=>chtml::decode(Yii::app()->input->xssClean($val['special_instructions'])),
    			   'if_sold_out'=>$val['if_sold_out'],
    			   'qty'=>intval($val['qty']),
    			   'price'=>array(
    			     'item_size_id'=>$val['item_size_id'],
    			     'price'=>$item_price,
    			     'size_name'=>isset($item_price_data['size_name'])?Yii::app()->input->xssClean($item_price_data['size_name']):'',
    			     'discount'=>isset($val['discount'])?(float)$val['discount']:'',
    			     'discount_type'=>isset($val['discount_type'])?$val['discount_type']:'',
    			     'price_after_discount'=>(float)$item_price,
    			     'pretty_price'=>Price_Formatter::formatNumber($item_price),
    			     'pretty_price_after_discount'=>Price_Formatter::formatNumber($item_price_less_discount),
    			     'total'=>$total, 
    			     'pretty_total'=>Price_Formatter::formatNumber($total),
    			     'total_after_discount'=>$total_after_discount,
    			     'pretty_total_after_discount'=>Price_Formatter::formatNumber($total_after_discount),
    			   ),
    			   'attributes'=>$attributes_list,    			   
    			   'attributes_raw'=>$attributes_list_raw,
    			   'addons'=>$results_addon,
    			   'additional_charge_list'=>$additional_charge_list,
    			   'tax'=>isset($val['tax'])?$val['tax']:'',
    			);
    			   			
    		} //end foreach 	
    		}		
		}
		return $results;
	}
	
	public static function getItemsOnly()
	{
		$results = array();
		if(!COrders::isEmpty()){
			$items = isset(COrders::$content['content'])?COrders::$content['content']:'';
			$size = isset(COrders::$content['size'])?COrders::$content['size']:'';    	
			foreach ($items as $val) {				
				$item_size_id = isset($val['item_size_id'])?(integer)$val['item_size_id']:0;
				$item_price_data = isset($size[$item_size_id])?$size[$item_size_id]:'';      			
    			$size_name = isset($item_price_data['size_name'])?$item_price_data['size_name']:'';    			
    			
				$results[] = array(
				   'item_name'=>Yii::app()->input->xssClean($val['item_name']),
    			   'url_image'=>$val['url_image'],
    			   'qty'=>intval($val['qty']),
    			   'size_name'=>$size_name
				);
			}
		}
		return $results;
	}
	
	public static function getMerchant($merchant_id='',$lang = KMRS_DEFAULT_LANGUAGE)
	{		
		$stmt="
		SELECT merchant_id,merchant_uuid,restaurant_name,restaurant_slug,
		address,		
		distance_unit,delivery_distance_covered,latitude,lontitude as longitude,
		merchant_type,
		commision_type,
		percent_commision as commission,
		allowed_offline_payment,invoice_terms,
		logo,path,
		contact_phone, contact_email,
		
		
		IFNULL((
		 select GROUP_CONCAT(cuisine_name,';',color_hex,';',font_color_hex)
		 from {{view_cuisine}}
		 where language=".q($lang)."
		 and cuisine_id in (
		    select cuisine_id from {{cuisine_merchant}}
		    where merchant_id  = a.merchant_id
		 )		 
		),'') as cuisine_name
			
		FROM {{merchant}} a
		WHERE merchant_id =".q($merchant_id)."
		";
						
		if( $res = Yii::app()->db->createCommand($stmt)->queryRow() ){
			$cuisine_list = array();
			$cuisine_name = explode(",",$res['cuisine_name']);
			if(is_array($cuisine_name) && count($cuisine_name)>=1){
				foreach ($cuisine_name as $cuisine_val) {						
					$cuisine = explode(";",$cuisine_val);								
					$cuisine_list[]=array(
					  'cuisine_name'=>isset($cuisine[0])?Yii::app()->input->xssClean($cuisine[0]):'',
					  'bgcolor'=>isset($cuisine[1])?  !empty($cuisine[1])?$cuisine[1]:'#ffd966'  :'#ffd966',
					  'fncolor'=>isset($cuisine[2])? !empty($cuisine[2])?$cuisine[2]:'#ffd966' :'#000',
					);
				}
			}
						
			$res['url_logo']= CMedia::getImage($res['logo'],$res['path'],'@thumbnail',
			CommonUtility::getPlaceholderPhoto('merchant'));
			
			$res['restaurant_url']=Yii::app()->createAbsoluteUrl($res['restaurant_slug']);
			$res['restaurant_direction'] = "https://www.google.com/maps/dir/?api=1&destination=$res[latitude],$res[longitude]";
			$res['cuisine'] = (array)$cuisine_list;
			$res['restaurant_name'] = Yii::app()->input->xssClean($res['restaurant_name']);
			$res['merchant_address'] = Yii::app()->input->xssClean($res['address']);
			return $res;
		}
		return false;
	}	
	
    public static function getAttributesAll($order_id='',$meta = array() ) 
    {    	
    	$meta_name = '';    
    	foreach ($meta as $val) {
    		$meta_name.=q($val).",";
    	}
    	$meta_name = substr($meta_name,0,-1);
    	
    	$stmt="
    	SELECT meta_name,meta_value
    	FROM {{ordernew_meta}}
    	WHERE order_id=".q($order_id)."
    	AND meta_name IN ($meta_name)
    	";    	    	
    	if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
    		$data = array();    		
    		foreach ($res as $val) {
    			$data[$val['meta_name']]=$val['meta_value'];    			
    		}
    		return $data;
    	}
    	return false;
    }	

	public static function getAttributesAll2($order_id='',$meta = array() ) 
    {    	
		$data = [];
    	$criteria=new CDbCriteria();
		$criteria->addInCondition("order_id",$order_id);
		$criteria->addInCondition("meta_name",$meta);		
		$model = AR_ordernew_meta::model()->findAll($criteria); 
		if($model){
			foreach ($model as $items) {
				$data[$items->order_id][$items->meta_name] = $items->meta_value;
			}
			return $data;
		}
		return false;
    }	
    
    public static function getMeta($order_id=0, $meta_name='')
    {
    	$model = AR_ordernew_meta::model()->find('order_id=:order_id AND meta_name=:meta_name', 
		   array(
		     ':order_id'=>$order_id, 
		     ':meta_name'=> $meta_name )
		   ); 
		if($model){
			return $model;
		}
		return false;
    }
    
    public static function savedAttributes($order_id='',$meta_name='', $meta_value='')
    {    	
		$model = AR_ordernew_meta::model()->find('order_id=:order_id AND meta_name=:meta_name', 
		   array(
		     ':order_id'=>$order_id, 
		     ':meta_name'=> $meta_name )
		   ); 
		   		   
		if($model){			
			if($model->meta_value!=$meta_value){
				$model->meta_value = $meta_value;
				$model->update();
			}
		} else {
			$insert = new AR_ordernew_meta;			
			$insert->order_id=$order_id;
			$insert->meta_name=$meta_name;
			$insert->meta_value = $meta_value;
			$insert->save();
		}
    }
    
    public static function orderMeta($meta_name=array('latitude','longitude','address1', 'address2','formatted_address','rejetion_reason','delayed_order_mins','points_to_earn' )){
    	$atts = COrders::getAttributesAll(COrders::$order_id,$meta_name);			
    	if($atts){
    		return $atts;
    	}
    	return false;
    }
    
    public static function savedMeta($order_id='',$meta_name='', $meta_value='')
    {    	
		$model = AR_ordernew_meta::model()->find('order_id=:order_id AND meta_name=:meta_name', 
		   array(
		      ':order_id'=>$order_id, 
		     ':meta_name'=> $meta_name )
		   ); 
		   		   
		if($model){			
			if($model->meta_value!=$meta_value){
				$model->meta_value = $meta_value;
				$model->update();
			}
		} else {
			$insert = new AR_ordernew_meta;			
			$insert->order_id=$order_id;
			$insert->meta_name=$meta_name;
			$insert->meta_value = $meta_value;
			$insert->save();
		}
    }
    	
    public static function orderHistory($client_id=0,$q='',$page=0,$limit=10,$merchant_id=0,$status=array())
    {
    	$all_items = array(); $all_item_size = array(); $all_merchant = array(); $total_rows=0;
		$all_orderid = [];

		$and_status = "AND status NOT IN ('pending','draft')";		
		if(is_array($status) && count($status)>=1){
			$and_status = " AND status IN (".CommonUtility::arrayToQueryParameters($status).")";
		}
		
    	$and = '';
    	if(!empty($q)){
    		$and = " 
    		AND  ( 
    		 a.order_id=".q($q)."  OR
    		 a.status LIKE ".q("%$q%")." OR
    		 a.service_code LIKE ".q("%$q%")." OR
    		 a.payment_code LIKE ".q("%$q%")." 
    		)
    		";
    	}

		$and_merchant = '';		
		if($merchant_id>0){
			$and_merchant = "AND a.merchant_id=".q( intval($merchant_id) )." ";
		}
    	
    	$stmt="
    	SELECT a.order_id, a.order_id as order_id_raw, a.order_uuid,a.merchant_id,a.status,
    	a.payment_status,a.service_code,a.payment_code,a.total,a.total as total_raw,
    	a.date_created, a.date_created as date_created_raw,
    	a.date_cancelled, a.date_cancelled as date_cancelled_raw,
		a.use_currency_code,
		a.base_currency_code,
		a.exchange_rate,
		a.admin_base_currency,
		a.exchange_rate_merchant_to_admin,
    	(
    	 select GROUP_CONCAT(cat_id,';',item_id,';',item_size_id,';',price,';',discount,';',qty,';',discount_type)
    	 from {{ordernew_item}}
    	 where order_id = a.order_id
    	) as items_row,
    	
    	(
    	select sum(qty) as total_items
    	from {{ordernew_item}}
    	where order_id = a.order_id
    	) as total_items		
    	    	
    	FROM {{ordernew}} a    			

		WHERE a.client_id=".q( intval($client_id) )."
		$and_merchant
    	$and_status
    	$and
    	ORDER BY a.order_id DESC
    	LIMIT $page,$limit
    	";    	    		    	
		if($res = CCacheData::queryAll($stmt)){
    		$data = array(); $price_list_format = [];
			$price_list = CMulticurrency::getAllCurrency();
    		foreach ($res as $val) {    			  		
    			$items_row = explode(",",$val['items_row']);				
    			$all_merchant[] = $val['merchant_id'];
				$all_orderid[] = $val['order_id'];

				$base_currency_code = $val['base_currency_code'];
				$use_currency_code = $val['use_currency_code'];				
				$exchange_rate = 1;
				
				if($base_currency_code!=$use_currency_code){
					$exchange_rate = floatval($val['exchange_rate']);					
				}
				
				if(isset($price_list[$use_currency_code])){
					$price_list_format = $price_list[$use_currency_code];
				}				

    			if(is_array($items_row) && count($items_row)>=1){
    				$items = array();
    				foreach ($items_row as $item_val) {
    					$item = explode(";",$item_val);								
    					if(count($item)>1){
							$price_after_discount=0;
							$discount_type = isset($item['6'])?$item['6']:'';
							$discount = isset($item['4'])?$item['4']:0;
							$price = isset($item['3'])?$item['3']:0;							

							if($discount>0){
								if($discount_type=="percentage"){
									$price_after_discount = $price - (floatval($discount/100) * floatval($price));
								} else {
									$price_after_discount = floatval($price)-floatval($discount);
								}
							}
						
							$items[] = array(
								'cat_id'=>$item['0'],
								'item_id'=>$item['1'],
								'item_size_id'=>$item['2'],
								'price'=>isset($item['3'])?$item['3']:0,
								'discount'=>isset($item['4'])?$item['4']:0,
								'qty'=>isset($item['5'])?$item['5']:0,
								'discount_type'=>isset($item['6'])?$item['6']:'',
								'price_after_discount'=>$price_after_discount
							);
							$all_items[]=$item['1'];
							$all_item_size[]=$item['2'];
    					}
    				}
    				$val['items']=$items;
    			}
    			
    			unset($val['items_row']); 				

				$price_format = [];
				if(is_array($price_list_format) && count($price_list_format)>=1){
					$val['total'] = Price_Formatter::formatNumber2( ($val['total']*$exchange_rate) , $price_list_format);
					$price_format = [				
						'precision' => $price_list_format['decimals'],
						'minimumFractionDigits'=>$price_list_format['decimals'],
						'decimal' => !empty($price_list_format['decimal_separator'])?$price_list_format['decimal_separator']:'.',
						'thousands' => $price_list_format['thousand_separator'],
						'separator' => $price_list_format['thousand_separator'],
						'prefix'=> $price_list_format['position']=='left'?$price_list_format['currency_symbol']:'',
						'suffix'=> $price_list_format['position']=='right'?$price_list_format['currency_symbol']:'',
						'prefill'=>true
					];
				} else {
					$val['total'] = Price_Formatter::formatNumber( ($val['total']*$exchange_rate) );					
					$price_format = [				
						'precision' => Price_Formatter::$number_format['decimals'],
						'minimumFractionDigits'=>!empty(Price_Formatter::$number_format['decimals'])?Price_Formatter::$number_format['decimals']:'.',
						'decimal' => Price_Formatter::$number_format['decimal_separator'],
						'thousands' => Price_Formatter::$number_format['thousand_separator'],
						'separator' => Price_Formatter::$number_format['thousand_separator'],
						'prefix'=> Price_Formatter::$number_format['position']=='left'?Price_Formatter::$number_format['currency_symbol']:'',
						'suffix'=> Price_Formatter::$number_format['position']=='right'?Price_Formatter::$number_format['currency_symbol']:'',
						'prefill'=>true
					];	
				}    			
    			$val['order_id'] = t("Order #{{order_id}}",array(
    			  '{{order_id}}'=>$val['order_id_raw']
    			));
    			$val['date_created'] = Date_Formatter::dateTime($val['date_created']);
    			$val['date_cancelled'] = Date_Formatter::dateTime($val['date_cancelled']);
    			$val['view'] = Yii::app()->createUrl("/orders/details",array('order_uuid'=>$val['order_uuid']));
    			$val['track'] = Yii::app()->createUrl("/orders/index",array('order_uuid'=>$val['order_uuid']));    			
				$val['pdf'] = Yii::app()->createAbsoluteUrl("/print/pdf",array('order_uuid'=>$val['order_uuid']));
    			$val['total_items_raw'] = $val['total_items'];				
    			if($val['total_items_raw']<=1){
    				$val['total_items'] = t("{{total}} item",array('{{total}}'=>$val['total_items']));
    			} else $val['total_items'] = t("{{total}} items",array('{{total}}'=>$val['total_items']));    			
				$val['is_loading']=false;
				$val['price_format'] = $price_format;
    			$data[] = $val;  
    		}
    		return array(
    		  'all_items'=>$all_items,
    		  'all_item_size'=>$all_item_size,
    		  'all_merchant'=>$all_merchant,
			  'all_orderid'=>$all_orderid,
    		  'data'=>$data,			  
    		);
    	}
    	throw new Exception( 'no results' );
    }
    
    public static function orderItems($items=array(),$lang=KMRS_DEFAULT_LANGUAGE)
    {    	
		$items = CommonUtility::arrayToQueryParameters($items);
    	$stmt = "
		SELECT a.item_id, a.item_name as original_item_name,b.item_name
		FROM {{item}} a
		
		left JOIN (
		   SELECT item_id,item_name FROM {{item_translation}} 
		   where language =".q($lang)."
		) b 
		on a.item_id = b.item_id
	
		where a.item_id IN (".$items.")
		";
    	$dependency = CCacheData::dependency();		
		if($res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency)->createCommand($stmt)->queryAll()){    				
    		$data = array();
    		foreach ($res as $val) {
				$item_name = empty($val['item_name'])? Yii::app()->input->xssClean($val['original_item_name']) : Yii::app()->input->xssClean($val['item_name']);
    			$data[$val['item_id']] = CHtml::decode($item_name);
    		}
    		return $data;
    	}
    	return false;
    }

	public static function orderItems2($items=array(),$lang=KMRS_DEFAULT_LANGUAGE)
    {    	
    	$stmt="
    	 SELECT item_id,item_name,photo,path,item_short_description,
		 (
			select item_name from {{item_translation}}
			where item_id = a.item_id
			AND language =".q($lang)."
		 ) as item_translation
		FROM
		 {{item}} a
		 where item_id IN (". CommonUtility::arrayToQueryParameters($items) .")
    	";    			
		$dependency = CCacheData::dependency();		
		if($res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency)->createCommand($stmt)->queryAll()){    			
    		$data = array();			
    		foreach ($res as $val) {
				$item_name = Yii::app()->input->xssClean($val['item_translation']);
				if(empty($item_name)){
					$item_name = Yii::app()->input->xssClean($val['item_name']);
				}
    			$data[$val['item_id']] = [
					'item_name'=> $item_name ,
					'item_short_description'=>Yii::app()->input->xssClean($val['item_short_description']),
					'photo'=>CMedia::getImage($val['photo'],$val['path'],Yii::app()->params->size_image_thumbnail,CommonUtility::getPlaceholderPhoto('item'))
				];
    		}
    		return $data;
    	}
    	return false;
    }
    
    public static function orderSize($sizes = array(), $lang=KMRS_DEFAULT_LANGUAGE)
    {    	
    	$stmt="
    	 SELECT a.item_size_id,a.size_name,a.original_size_name
    	 FROM {{view_item_lang_size}} a
		 where a.item_size_id IN (".implode(",",$sizes).")
		 AND LANGUAGE =".q($lang)."
    	";       			
    	if($res = Yii::app()->db->createCommand($stmt)->queryAll()){			
    		$data = array();
    		foreach ($res as $val) {
    			$data[$val['item_size_id']] = empty($val['size_name'])? Yii::app()->input->xssClean($val['original_size_name']) : Yii::app()->input->xssClean($val['size_name']);
    		}			
    		return $data;
    	}
    	return false;
    }
    
    public static function orderMerchantInfo($merchant_ids=array())
    {
    	$stmt="
    	 SELECT merchant_id,restaurant_name,
    	 address, address as merchant_address,
    	 logo,path
    	 FROM {{merchant}}
    	 WHERE merchant_id IN (".implode(",",$merchant_ids).")
    	";       	
    	if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
    		$data = array();
    		foreach ($res as $val) {    			
    			$data[$val['merchant_id']] = array(
    			  'restaurant_name'=>Yii::app()->input->xssClean($val['restaurant_name']),
    			  'merchant_address'=>Yii::app()->input->xssClean($val['merchant_address']),
    			  'url_logo'=>CMedia::getImage($val['logo'],$val['path'],
    			  Yii::app()->params->size_image_thumbnail
    			  ,CommonUtility::getPlaceholderPhoto('merchant'))
    			);
    		}
    		return $data;
    	}
    	return false;
    }
    
    public static function orderHistoryTotal($client_id=0,$merchant_id=0 , $status = array())
    {
		$and_merchant = '';		
		if($merchant_id>0){
			$and_merchant = "AND merchant_id=".q(intval($merchant_id))." ";
		}

		$and_status = "AND status NOT IN ('pending','draft')";
		if(is_array($status) && count($status)>=1){
			$and_status = " AND status IN (".CommonUtility::arrayToQueryParameters($status).")";
		}		

    	$stmt="
		SELECT count(*) as total
		FROM {{ordernew}}
		WHERE client_id=".q( intval($client_id)  )."		
		$and_merchant
		$and_status
		";			
    	if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
    		return $res['total'];
    	}
    	throw new Exception( 'no results' );
    }
    
    public static function statusList($lang=KMRS_DEFAULT_LANGUAGE, $description='')
    {
    
    	$where = '';	
    	if(!empty($description)){
    		$where="WHERE a.description=".q($description);
    	}
    	    	
		$stmt = "
		SELECT 
		a.stats_id,
		a.description,
		b.description as status,
		a.font_color_hex,a.background_color_hex
		FROM {{order_status}} a

		left JOIN (
			SELECT stats_id, description FROM {{order_status_translation}} where language = ".q($lang)."
		) b 
		on a.stats_id = b.stats_id

		$where
		"; 		    	
		$dependency = CCacheData::dependency();					
		if($res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency)->createCommand($stmt)->queryAll()){		   
    		$data = array();
    		foreach ($res as $val) {
    			$data[$val['description']] = array(
    			  'status'=> empty($val['status'])? $val['description'] :$val['status'] ,
    			  'font_color_hex'=>$val['font_color_hex'],
    			  'background_color_hex'=>$val['background_color_hex'],
    			);
    		}			
    		return $data;
    	}
    	return false;
    }
        
    public static function getOrderHistory($client_id=0,$q='',$page=0,$limit=10,$lang=KMRS_DEFAULT_LANGUAGE,$merchant_id=0 , $status=array())
    {    	    	    					
		
    	$data = COrders::orderHistory($client_id,$q,$page,$limit,$merchant_id,$status);        			
		$reviews = COrders::getAllReview($data['all_orderid']);		
    	$merchant = COrders::orderMerchantInfo($data['all_merchant']);
    	$items = COrders::orderItems($data['all_items'],$lang);
		$items2 = COrders::orderItems2($data['all_items'],$lang);
    	$size = COrders::orderSize($data['all_item_size'],$lang);  
    	$order_status = COrders::statusList($lang);    	
    	$services = COrders::servicesList($lang);    	
    	    	
    	$status_allowed_cancelled = COrders::getStatusAllowedToCancel();
		$status_allowed_review = AOrderSettings::getStatus(array('status_delivered','status_completed'));
    	    	
    	return array(    	  
    	  'data'=>$data['data'],
    	  'merchants'=>$merchant,
    	  'items'=>$items,
		  'items2'=>$items2,
    	  'size'=>$size,
    	  'status'=>$order_status,
    	  'services'=>$services,
    	  'status_allowed_cancelled'=>(array)$status_allowed_cancelled,
    	  'status_allowed_review'=>(array)$status_allowed_review,
		  'reviews'=>$reviews
    	);
    }
        
    public static function addCondition($data = array() )
	{
		if(is_array($data) && count($data)>=1){
		   COrders::$condition[] = $data;
		}
	}
	
	public static function getCondition()
	{
		if(is_array(COrders::$condition) && count(COrders::$condition)>=1){
		   return COrders::$condition;
		}
		return false;
	}
	
	public static function setCondition($model)
	{
		$exchange_rate = self::getExchangeRate();		
		if($model){
			
			$tax_delivery = array();
			$tax_condition = self::getTaxCondition();		
			$tax_settings = self::getTaxSettings();			
			
			if(isset($tax_settings['tax_type'])){
				if($tax_settings['tax_type']=="multiple"){			
					$tax_delivery = self::getTaxForDelivery();
				} else $tax_delivery = $tax_settings['tax'];
		    }
						
			
			/*PROMO CODE*/
			if($model->promo_total>0){
				COrders::addCondition(array(
				  'name'=>t("less voucher"),
				  'type'=>"voucher",
				  'target'=>"subtotal",
				  'value'=>floatval(-($model->promo_total*$exchange_rate))
				));
			}
			
			/*OFFER*/
			if($model->offer_discount>0){
				COrders::addCondition(array(
				  'name'=>t("Discount {{discount}}%", array('{{discount}}'=>$model->offer_discount) ),
				  'type'=>"offers",
				  'target'=>"subtotal",
				  'value'=>"-%$model->offer_discount"
				));
			}

			/*POINTS*/
			if($model->points>0){				
				COrders::addCondition(array(
					'name'=>t("Less Points"),
					'type'=>"points_discount",
					'target'=>"subtotal",
					'value'=>floatval(-($model->points*$exchange_rate))
				));
			}
			
			/*SERVICE FEE*/
			if($model->service_fee>0){
				COrders::addCondition(array(
				  'name'=>t("Service fee"),
				  'type'=>"service_fee",
				  'target'=>"total",
				  'value'=>$model->service_fee * $exchange_rate,			
				  'taxable'=>isset($tax_settings['tax_service_fee'])?$tax_settings['tax_service_fee']:false,
				  'tax'=>$tax_delivery,		          
				));
			}

			/*SMALL ORDER FEE*/
			if($model->small_order_fee>0){
				COrders::addCondition(array(
				  'name'=>t("Small order fee"),
				  'type'=>"small_order_fee",
				  'target'=>"total",
				  'value'=>$model->small_order_fee * $exchange_rate,			
				  'taxable'=>false,
				  'tax'=>$tax_delivery,		          
				));
			}
			
			/*DELIVERY FEE*/
			if($model->delivery_fee>0 && $model->service_code=="delivery"){
				COrders::addCondition(array(
				   'name'=>t("Delivery Fee"),
		           'type'=>"delivery_fee",
		           'target'=>"total",
		           'value'=>$model->delivery_fee * $exchange_rate,
		           'taxable'=>isset($tax_settings['tax_delivery_fee'])?$tax_settings['tax_delivery_fee']:false,
		           'tax'=>$tax_delivery,		           
				));
			}
			
			/*PACKAGING*/			
			if($model->packaging_fee>0){
				COrders::addCondition(array(
				   'name'=>t("Packaging fee"),
		           'type'=>"packaging_fee",
		           'target'=>"total",
		           'value'=>$model->packaging_fee * $exchange_rate,
		           'taxable'=>isset($tax_settings['tax_packaging'])?$tax_settings['tax_packaging']:false,
		           'tax'=>$tax_delivery,		           
				));
			}
			
			/*TAX*/			
			if(is_array($tax_condition) && count($tax_condition)>=1){
				foreach ($tax_condition as $tax_item) {					
					$tax_rate = floatval($tax_item['tax_rate']);
		            $tax_name = $tax_item['tax_name'];
		            $tax_label = $tax_item['tax_in_price']==false?'{{tax_name}} {{tax}}%' : '{{tax_name}} ({{tax}}% included)';
		            self::addCondition(array(
					  'name'=>t($tax_label,array(
						 '{{tax_name}}'=>t($tax_name),
						 '{{tax}}'=>$tax_rate
					  )),
					  'type'=>"tax",
					  'target'=>"total",
					  'taxable'=>false,
					  'value'=>"$tax_rate%",
					  'tax_id'=>$tax_item['tax_id']
					));
				}
			}

			/*TIP*/
			if($model->courier_tip>0){
				COrders::addCondition(array(
				  'name'=>t("Courier tip"),
				  'type'=>"tip",
				  'target'=>"total",
				  'value'=>floatval($model->courier_tip) * $exchange_rate
				));
			}

			if($model->card_fee>0){
				COrders::addCondition(array(
				  'name'=>t("Card fee"),
				  'type'=>"card_fee",
				  'target'=>"total",
				  'value'=>floatval($model->card_fee) * $exchange_rate
				));
			}
		} /*end model*/
	}
	
    public static function getSubTotal()
    {    	
		$exchange_rate = self::getExchangeRate();
    	$sub_total = 0; $sub_total_without_cnd = 0; $taxable_subtotal = 0;   	
    	if(!COrders::isEmpty()){
    		$items = isset(COrders::$content['content'])?COrders::$content['content']:'';
    		$size = isset(COrders::$content['size'])?COrders::$content['size']:'';
    		$addon_items = isset(COrders::$content['addon_items'])?COrders::$content['addon_items']:'';
    		    		
    		if(is_array($items) && count($items)>=1){
    		foreach ($items as $val) {    	
    					    			       		
    			$qty = intval($val['qty']);
    			$taxable = isset($val['taxable'])?$val['taxable']:0;
    			$item_size_id = isset($val['item_size_id'])?(integer)$val['item_size_id']:0;
    			$item_price_data = isset($size[$item_size_id])?$size[$item_size_id]:'';   
    			
    			/*OVER WRITE PRICE*/
    			$item_price_data = array(
    			  'price'=>$val['price'],
    			  'discount'=>$val['discount'],
    			  'discount_type'=>$val['discount_type'],
    			);
    			
    			$item_price = COrders::parseItemPrice($item_price_data);
				$item_price = $item_price>0? ($item_price*$exchange_rate) : $item_price;
    			$total_price = $qty*$item_price;
    			$sub_total+=$total_price;
    			if($taxable===1){
    				$taxable_subtotal+=$total_price;
    			}
    			    			
    			$addon_total = 0; $addon_total_price = 0;
    			if(is_array($val['addon_items']) && count($val['addon_items'])>=1){
    				foreach ($val['addon_items'] as $addon_category) {
    					foreach ($addon_category as $addons_item) {    						
    						if(is_array($addons_item) && count($addons_item)>=1){    							
    							foreach ($addons_item as $addon_items_id=>$addon) {    								
    								$addon_price = isset($addon_items[$addon_items_id]['price'])?$addon_items[$addon_items_id]['price']:0;
									$addon_price = $addon_price>0? ($addon_price*$exchange_rate) : $addon_price;
    								$addon_qty = isset($addon['qty'])?(integer)$addon['qty']:0;
    								$multi_option = isset($addon['multi_option'])?$addon['multi_option']:'';    								
    								if($multi_option=="multiple"){
    									$addon_total_price = floatval($addon_price)*intval($addon_qty); 
    								} else $addon_total_price = floatval($addon_price)*intval($qty); 
    								$sub_total+=$addon_total_price;
    								$addon_total+= $addon_total_price;
    								if($taxable===1){
					    				$taxable_subtotal+=$addon_total_price;
					    			}
    							}
    						}
    					}
    				}
    			} // addons item
    			
    			if(isset($val['additional_charge'])){
    			if(is_array($val['additional_charge']) && count($val['additional_charge'])>=1){
    				foreach ($val['additional_charge'] as $item_charge) {    					
    					$sub_total+=floatval($item_charge['charge_total']);
    					$addon_total+= $addon_total_price;
    					$taxable_subtotal+=floatval($item_charge['charge_total']);
    				}
    			}
    			}
    			    			
    			/*ADD TAX*/
    			if(isset($val['tax'])){
    				$total_to_tax = floatval($total_price)+floatval($addon_total);       				
    				self::addTax($val['tax'], $total_to_tax);
    			}
    			
    		} // items
    		}
    		  	       		
    			
    		$sub_total_without_cnd = $sub_total;
    		/*CONDITION*/
    		if ( $condition = COrders::getCondition()){
    			foreach ($condition as $val) {       				
    				if($val['target']=="subtotal"){         					
    					$raw_sub_total = COrders::apply($sub_total,$val['value']);  
    					$sub_total = $raw_sub_total;
						if(isset($taxable)){
							if($taxable===1){
								$taxable_subtotal = $raw_sub_total;
							}
						}    					
    				}
    			}
    		}    		
    		    		    		
    	}    	
    	
    	return array(
    	  'sub_total'=>floatval($sub_total),
    	  'taxable_subtotal'=>floatval($taxable_subtotal),
    	  'sub_total_without_cnd'=>$sub_total_without_cnd
    	);
    }    
        
    public static function apply($total=0, $condition_val=0)
    {    	
    	$results = 0;    	
    	if ( COrders::valueIsPercentage($condition_val)){
    		
    		$value = (float) COrders::cleanValue($condition_val);    		
    		$raw_value = (float)$total * ($value/100);    		    		
    		
    		if ( COrders::valueIsToBeSubtracted($condition_val)){ 
    			$results = floatval($total) - floatval($raw_value);
    		} else $results = floatval($total) + floatval($raw_value);
    	} else {
    		$raw_value = (float) COrders::cleanValue($condition_val); 
    		if ( COrders::valueIsToBeSubtracted($condition_val)){
    			$results = floatval($total) - floatval($raw_value);
    		} else $results = floatval($total) + floatval($raw_value);
    	}
    	return $results;
    }
        
    public static function summary($condition_val=0,$total=0)
    {    	
    	$results = '';  
    	$raw_value = (float) COrders::cleanValue($condition_val);     	
    	if ( COrders::valueIsPercentage($condition_val)){    		    		
    		$value = (float) COrders::cleanValue($condition_val);        		
    		$raw_value = (float)$total * ($value/100);        		
    		if ( COrders::valueIsToBeSubtracted($condition_val)){ 
    			$total  = t("([total])",array(
    			 '[total]'=>Price_Formatter::formatNumber($raw_value)
    			));
    			$results = array(
    			  'value'=>$total,
    			  'raw'=>$raw_value
    			);
    		} else $results = array(
    		  'value'=>Price_Formatter::formatNumber($raw_value),
    		  'raw'=>$raw_value,
    		);
    	} else {    		
    		if ( COrders::valueIsToBeSubtracted($condition_val)){    			
    			$total  = t("([total])",array(
    			 '[total]'=>Price_Formatter::formatNumber($raw_value)
    			));
    			$results = array(
    			  'value'=>$total,
    			  'raw'=>$condition_val
    			);
    		} else $results = array(
    		  'value'=>Price_Formatter::formatNumber($raw_value),
    		  'raw'=>$raw_value
    		);
    	}    	
    	return $results;
    }

    public static function getPackagingFee()
    {
    	if(self::$packaging_fee>0){
    		return self::$packaging_fee;
    	}
    	return false;
    }
    
    public static function getSummary($cart_uuid = '')
    {
    	$results = array();
    	if(!COrders::isEmpty()){
    		$resp = COrders::getSubTotal();  			
    		$item_count = self::itemCount( self::$order_id );
    		    		
    		$sub_total = $resp['sub_total'];
    	    $sub_total_without_cnd = $resp['sub_total_without_cnd'];

    	    if ( $condition = COrders::getCondition()){
    			//dump($condition);
    			/*SUB TOTAL*/
    			foreach ($condition as $val) {    				    				    				
    				if($val['target']=="subtotal"){           					
    					$value = COrders::summary($val['value'],$sub_total_without_cnd);    					
    					$results[] = array(
    					 'name'=>$val['name'],
    					 'value'=>isset($value['value'])?$value['value']:0,
    					 'raw'=>isset($value['raw'])?$value['raw']:0,
    					 'type'=>$val['type'],
    					);
    				}
    			}

    			$value = COrders::summary( $sub_total );
    			$results[]=array(
    			  'name'=>t("Sub total ({{count}} items)",array('{{count}}'=>intval($item_count) )),
    			  'value'=>isset($value['value'])?$value['value']:0,
    			  'raw'=>isset($value['raw'])?$value['raw']:0,
    			  'type'=>'subtotal',
    			);
    			
    			foreach ($condition as $val) { 
    				if($val['target']=="total"){    				    
    				    $value = COrders::summary($val['value'],$sub_total);
    				    
    				    /*ADD TAX*/       				    
    					if(isset($val['tax']) && isset($val['taxable'])){        						    						
    						if($val['taxable']){    							
			    				$total_to_tax = isset($value['raw'])? floatval($value['raw']) : 0;			    				
			    				self::addTax($val['tax'], $total_to_tax);
    						}
		    			}    
		    			
		    			if($val['type']=="tax"){
		    				$tax_group_data = self::getTaxGroup();		    				
		    				$tax_value = isset($tax_group_data[$val['tax_id']]) ? $tax_group_data[$val['tax_id']]['total'] : 0;
		    				$tax_in_price = isset($tax_group_data[$val['tax_id']]) ? $tax_group_data[$val['tax_id']]['tax_in_price'] : false;
							if($tax_value>0){
								self::setTotalTax($tax_value);
								$results[] = array(
								'name'=>$val['name'],
								'value'=>Price_Formatter::formatNumber($tax_value),
								'raw'=>$tax_value,
								'type'=>$val['type'],
								);	    
						    }
	    					if($tax_in_price==false){
	    						$sub_total = self::apply($sub_total,$tax_value);  
	    					} 
		    			} else {
                            $atts = CCart::getAttributesAll($cart_uuid,array('promo','promo_type','promo_id'));
                            $discount_value = 0;

                            if($atts){
                                CCart::getContent($cart_uuid,Yii::app()->language);
                                $merchant_id = CCart::getMerchantId($cart_uuid);
                                $subtotal = CCart::getSubTotal();
                                $sub_total = floatval($subtotal['sub_total']);
                                if(isset($atts['promo'])){
                                    if ($promo = json_decode($atts['promo'],true)){
                                        $promo_id = isset($promo['id'])?$promo['id']:null;
                                        if($promo_details = CPromos::findVoucherByID($promo_id)){
                                            if($promo_details->used_once == 4){
                                                if($promo_details->voucher_type=="percentage"){
                                                    $discount_value = $sub_total*($promo_details->amount/100);
                                                    if($promo_details->up_to){
                                                        $discount_value = min($discount_value, $promo_details->up_to);
                                                    }
                                                } else {
                                                    $discount_value = $promo_details->amount;
                                                }

                                                $delivery_fee = CCart::shippingRate($merchant_id,'dynamic','standard',0,'km','delivery');
                                                $delivery_fee = $delivery_fee['distance_price'];
                                                $discount_value = min($discount_value, $delivery_fee);
                                            }
                                        }
                                    }
                                }
                            }

                            $results[] = array(
                                'name'=>$val['name'],
                                'value'=>isset($value['value'])?$value['value']:0,
                                'raw'=>isset($value['raw'])?$value['raw']:0,
                                'type'=>$val['type'],
                                'pay' => Price_Formatter::formatNumber($value['raw'] - $discount_value),
                            );
	    					$sub_total = self::apply($sub_total,$val['value']);    				
		    			}    		
    				    
    				}
    			}
    			
    			$value = COrders::summary( $sub_total );
    			$results[]=array(
    			  'name'=>t("Total"),
    			  'value'=> isset($value['value'])?$value['value']:0,
    			  'raw'=>isset($value['raw'])?$value['raw']:0,
    			  'type'=>'total',
    			);
    			COrders::$summary_total = isset($value['raw'])?$value['raw']:0;
    		} else {
    			
    			$value = COrders::summary( $sub_total );
    			$results[]=array(
    			  //'name'=>t("Sub total"),
    			  'name'=>t("Sub total ({{count}} items)",array('{{count}}'=>$item_count)),
    			  'value'=> isset($value['value'])?$value['value']:0,
    			  'raw'=>isset($value['raw'])?$value['raw']:0,
    			  'type'=>'subtotal',
    			);
    			$results[]=array(
    			  'name'=>t("Total"),
    			  'value'=> isset($value['value'])?$value['value']:0,
    			  'raw'=>isset($value['raw'])?$value['raw']:0,
    			  'type'=>'total',
    			);
    			COrders::$summary_total = isset($value['raw'])?$value['raw']:0;
    		}    		
    		
			if(COrders::$order->wallet_amount>0 && COrders::$order->amount_due>0 && COrders::$order->payment_status=="unpaid"){
				$results[] = [
					'name'=>t("Paid by Digital Wallet"),
					'value'=>"-".Price_Formatter::formatNumber( (COrders::$order->wallet_amount*self::$exchange_rate) ),
					'raw'=>COrders::$order->wallet_amount*self::$exchange_rate,
					'type'=>'paid_wallet',
				];									
				$results[] = [
					'name'=>t("Remaining Amount Due"),
					'value'=>Price_Formatter::formatNumber( (COrders::$order->amount_due*self::$exchange_rate) ),
					'raw'=>COrders::$order->amount_due*self::$exchange_rate,
					'type'=>'amount_due',
				];			
		    }
    		return $results;
    	}
    	return false;
    }
    
    public static function getTotal()
    {    	
    	if(COrders::$order->total>0){
    		return COrders::$order->total;
    	}    	
    	return 0;
    }
    
    public static function getSummaryTotal()
    {    	
    	if(COrders::$summary_total>0){
    		return COrders::$summary_total;
    	}    	
    	return 0;
    }
        
    public static function getSummaryChangesOLD()
    {
    	$total_paid = 0; $total_refund = 0; $net_payment = 0;
    	$data = array(); $refund_list = array(); $refund_due = 0; $method = '';
    	$order = COrders::$order;    	
    	
    	$all_online = CPayments::getPaymentTypeOnline();
    	if(!array_key_exists($order->payment_code,(array)$all_online)){
    		return false;
    	}
    	
    	$criteria=new CDbCriteria();    	
	    $criteria->condition = "order_id=:order_id";		    
		$criteria->params  = array(
		  ':order_id'=>intval($order->order_id),		  
		);
		$model = AR_ordernew_summary_transaction::model()->findAll($criteria); 
		if($model){			
			foreach ($model as $item) {
				$total_refund+=floatval($item->transaction_amount);
				$refund_list[]= array(
				  'transaction_uuid'=>$item->refund_id,
				  'transaction_description'=>t($item->reason),
				  'transaction_amount'=>Price_Formatter::formatNumber($item->amount),
				  'status'=>$item->status,
				);
			}			
		}
						
		$summary_total =  COrders::getSummaryTotal();		
		if(array_key_exists($order->payment_code,(array)$all_online)){
			$total_paid = COrders::getTotalPayment($order->order_id,'payment',array('paid'));  
			if($total_paid != $summary_total ){
	    		$refund_due =  floatval($total_paid) - floatval($summary_total) ;
	    	}	    	
		}
		
		$datas = array(
		  'total_refund'=>$total_refund,
		  'refund_due'=>$refund_due
		);
		
		$total_refund = Price_Formatter::convertToRaw($total_refund);
		
		$refund_due = floatval($refund_due) - floatval($total_refund);		
		$net_payment = floatval($total_paid)-floatval($total_refund);
		
		if($refund_due>0){
			$method='total_decrease';
		} elseif ( $refund_due<=-0.0001){
			$method='total_increase';
			$refund_due = $refund_due*-1;
		}
		
		$data = array(
		 'method'=>$method,
		 'datas'=>$datas,
		 'total_paid'=>Price_Formatter::formatNumber($total_paid),
		 'summary_total'=>$summary_total,
		 'refund_list'=>$refund_list,
		 'total_refund'=>$total_refund,
		 'refund_due'=>Price_Formatter::convertToRaw($refund_due),
		 'refund_due_pretty'=>Price_Formatter::formatNumber($refund_due),
		 'net_payment'=>Price_Formatter::formatNumber($net_payment)
		);
		
		return $data;
		
    	return false;
    }
    
    public static function SummaryTransaction($order_id=0)
    {
    	$total_refund = 0; $total_credit = 0; $list = array();
    	$criteria=new CDbCriteria();    	
	    $criteria->condition = "order_id=:order_id ";		    
		$criteria->params  = array(
		  ':order_id'=>intval($order_id),		  
		);
		$criteria->order = "transaction_id ASC";
		$model = AR_ordernew_summary_transaction::model()->findAll($criteria); 
		if($model){			
			foreach ($model as $item) {
				if($item->transaction_type=="debit"){
				   $total_refund+=floatval($item->transaction_amount);
				} else $total_credit+=floatval($item->transaction_amount);
				
				$list[]= array(
				  'transaction_uuid'=>$item->transaction_uuid,
				  'transaction_type'=>$item->transaction_type,
				  'transaction_description'=>t($item->transaction_description),
				  'transaction_amount'=>Price_Formatter::formatNumber($item->transaction_amount),
				  'status'=>$item->status,
				);
			}			
			return array(
			  'total_refund'=>$total_refund,
			  'total_credit'=>$total_credit,
			  'list'=>$list
			);
		}
		return false;
    }
    
    public static function getSummaryChanges()
    {
		$digitalwallet_enabled = isset(Yii::app()->params['settings']['digitalwallet_enabled'])?Yii::app()->params['settings']['digitalwallet_enabled']:false;
		$digitalwallet_enabled = $digitalwallet_enabled==1?true:false;

    	$order = COrders::$order;    	
    	$all_online = CPayments::getPaymentTypeOnline();

		// DIGITAL WALLET
		if($digitalwallet_enabled){
			$all_online[CDigitalWallet::transactionName()] =[
				'payment_code'=>CDigitalWallet::transactionName(),
				'payment_name'=>CDigitalWallet::paymentName(),
			];
		}

    	if(!array_key_exists($order->payment_code,(array)$all_online)){
    		return false;
    	}

		$all_capture = CPayments::getPaymentTypeCapture();		
		if(array_key_exists($order->payment_code,(array)$all_capture)){			
    		return false;
    	}
    	
    	$total_refund = 0; $refund_list = array();
    	$method = ''; $refund_due = 0; $net_payment = 0; $unpaid_invoice = false;
    	
    	if( $refund_data = COrders::SummaryTransaction($order->order_id) ){    		
    		$total_refund = $refund_data['total_refund'];
    		$refund_list = $refund_data['list'];
    	}

		//dump($order->payment_code);
    	    	
		$summary_total =  COrders::getSummaryTotal();
		$total_paid = COrders::getTotalPayment($order->order_id,'payment',array('paid'));
		$summary_total = Price_Formatter::convertToRaw(floatval($summary_total));
		$total_paid = Price_Formatter::convertToRaw(floatval($total_paid));
		
		if($total_paid>$summary_total){
			$method = 'total_decrease';
			$refund_due =  $total_paid - $summary_total;
			$refund_due = $refund_due - $total_refund;
			$net_payment = floatval($total_paid)-floatval($total_refund);
		} else if ($total_paid<$summary_total) {					
			$method = 'total_increase';
			$refund_due =  $summary_total - $total_paid ;
			$net_payment = floatval($total_paid)-floatval($total_refund);

			/*CHECK IF HAS UNPAID INVOICE*/
			try  {			  
			   $invoice_payment = self::getInvoicePayment($order->order_id,'unpaid','invoice');
			   $unpaid_invoice = true;
			} catch (Exception $e) {
		       //
		    }			
		    
		    try  {			  
			   $invoice_payment = self::getInvoicePayment($order->order_id,'paid','invoice');
			   $paid_invoice = true;
			} catch (Exception $e) {
		       //
		    }			
			
		}  else if ($total_refund>0) {
			$method = 'total_increase';
			$refund_due = $total_refund;
		}
		
		
		$data = array(
		  'method'=>$method,
		  'unpaid_invoice'=>isset($unpaid_invoice)?$unpaid_invoice:false,
		  'paid_invoice'=>isset($paid_invoice)?$paid_invoice:false,
		  'summary_total'=>$summary_total,
		  'total_paid'=>$total_paid,
		  'refund_due'=>Price_Formatter::convertToRaw($refund_due),
		  'refund_due_pretty'=>Price_Formatter::formatNumber($refund_due),
		  'net_payment'=>$net_payment,
		  'refund_list'=>$refund_list
		);				
		
		return $data;    	    
    }
    
    public static function getSummaryTransaction()
    {
    	$order = COrders::$order;
    	if( $refund_data = COrders::SummaryTransaction($order->order_id) ){    		
    		
    		$net_payment = 0;
    		$total_paid = COrders::getTotalPayment($order->order_id,'payment',array('paid'));
    		$total_paid = Price_Formatter::convertToRaw(floatval($total_paid));
    		
    		$total_refund = Price_Formatter::convertToRaw($refund_data['total_refund']);
    		$total_credit = Price_Formatter::convertToRaw($refund_data['total_credit']);
    		$list = $refund_data['list'];
    		
    		$net_payment = ($total_paid+$total_credit)-$total_refund;
    		
    		return array(
    		  'total_paid'=>Price_Formatter::formatNumber($total_paid),
    		  'total_refund'=>$total_refund,
    		  'total_credit'=>$total_credit,
    		  'total_credit_pretty'=>Price_Formatter::formatNumber($total_credit),
    		  'net_payment'=>Price_Formatter::formatNumber($net_payment),
    		  'summary_list'=>$list
    		);
    	}
    	return false;
    }
    
    public static function servicesList($lang=KMRS_DEFAULT_LANGUAGE,$service_code='',$status='')
    {        	
    	
    	$where = '';	
    	if(!empty($service_code)){
    		$where="WHERE a.service_code=".q($service_code);
    	} else {
			if(!empty($status)){
				$where="WHERE a.status=".q($status);
			}
		}
    	
		$stmt = "
		SELECT a.service_id,a.service_name as original_service_name,
		b.service_name,
		a.service_code,a.color_hex,a.font_color_hex
		FROM {{services}} a

		left JOIN (
			SELECT service_id, service_name FROM {{services_translation}} where language = ".q($lang)."
		) b 
		on a.service_id = b.service_id

		$where
		";		
    	if($res = Yii::app()->db->createCommand($stmt)->queryAll()){    					
    		$data = array();
    		foreach ($res as $val) {
    			$data[$val['service_code']] = array(
    			  'service_name'=> empty($val['service_name']) ? $val['original_service_name'] : $val['service_name'],
    			  'font_color_hex'=>$val['font_color_hex'],
    			  'background_color_hex'=>$val['color_hex'],
    			);
    		}			
    		return $data;
    	}
    	return false;
    }
    
    public static function paymentStatusList($lang=KMRS_DEFAULT_LANGUAGE,$payment_status='')
    {		
    	$stmt="
    	 SELECT 
		 a.status_id,
		 a.status,
		 a.title as original_title,
		 b.title,
		 a.color_hex,
		 a.font_color_hex
    	 FROM {{status_management}} a       
		 left JOIN (
			SELECT status_id, title FROM {{status_management_translation}} where language = ".q($lang)."
		 ) b 
		 on a.status_id = b.status_id
    	 WHERE a.status=".q($payment_status)."
    	";    				
    	if($res = Yii::app()->db->createCommand($stmt)->queryAll()){    		  			
    		$data = array();
    		foreach ($res as $val) {
    			$data[$val['status']] = array(
    			  'title'=> empty($val['title'])? $val['original_title'] : $val['title'],
    			  'color_hex'=>$val['color_hex'],
    			  'font_color_hex'=>$val['font_color_hex'],
    			);
    		}				
	    	return $data;
    	}
    	return false;
    }
    
    public static function paymentStatusList2($lang=KMRS_DEFAULT_LANGUAGE,$group_name='')
    {
    	$stmt="
    	 SELECT 
		 a.status_id,
		 a.status,
		 a.title as original_title,
		 b.title,
		 a.color_hex,a.font_color_hex

    	 FROM {{status_management}} a     
		 left JOIN (
			SELECT status_id, title FROM {{status_management_translation}} where language = ".q($lang)."
		 ) b 
		 on a.status_id = b.status_id

    	 WHERE group_name=".q($group_name)."
    	";    
    	if($res = Yii::app()->db->createCommand($stmt)->queryAll()){    		  			
    		$data = array();
    		foreach ($res as $val) {
    			$data[$val['status']] = array(
    			  'title'=> empty($val['title']) ? $val['original_title'] : $val['title'],
    			  'color_hex'=>$val['color_hex'],
    			  'font_color_hex'=>$val['font_color_hex'],
    			);
    		}	  			
	    	return $data;
    	}
    	return false;
    }
    
    public static function orderInfo($lang=KMRS_DEFAULT_LANGUAGE,$datenow='',$is_notifications=false)
    {
    	if(COrders::$order){
    		$order = COrders::$order;			
    		
    		$attr = self::orderMeta(array(
    		  'customer_name','contact_number','contact_email',
    		  'include_utensils','longitude','latitude','rejetion_reason','delayed_order','delayed_order_mins',
    		  'order_change','order_notes','order_change','receive_amount',
			  'address_label','delivery_options','location_name','payment_change','order_otp',
			  'address1','address2','delivery_instructions','points_to_earn','postal_code','company','address_format_use',
			  'timezone'
    		));			
			    		
    		$status = COrders::statusList($lang,$order->status);   
			$delivery_status = COrders::statusList($lang,$order->delivery_status);   			
    		$services = COrders::servicesList($lang,$order->service_code);
    		$payment_status = COrders::paymentStatusList($lang,$order->payment_status);   
			$status_completed = AOrderSettings::getStatus(array('status_delivered','status_completed'));			
    		
    		$payment = AR_payment_gateway::model()->find('payment_code=:payment_code', 
		    array(':payment_code'=>$order->payment_code)); 			
			
			$upload_deposit_link = $order->payment_code=="bank"?Yii::app()->createAbsoluteUrl("/orders/upload_deposit",array('order_uuid'=>$order->order_uuid)) :'';  
		    
			$tracking_link = Yii::app()->createAbsoluteUrl("/orders/index",array('order_uuid'=>$order->order_uuid));    			
			if($is_notifications && $order->request_from=="singleapp"){				
				try {
					$jwt_token = AR_merchant_meta::getValue($order->merchant_id,AttributesTools::JwtTokenID());
					$jwt_token = isset($jwt_token['meta_value'])?$jwt_token['meta_value']:'';					
					$jwt_key = new Key(CRON_KEY, 'HS256');
					$decoded = (array) JWT::decode($jwt_token, $jwt_key);    					
					$single_site_url = isset($decoded['aud'])?$decoded['aud']:'';
					$tracking_link = $single_site_url."/#/account/trackorder?order_uuid=".$order->order_uuid;
				} catch (Exception $e) {
					//
				}
			} 

			$view_order_link = Yii::app()->createAbsoluteUrl(BACKOFFICE_FOLDER."/orders/view",array('order_uuid'=>$order->order_uuid));    			

		    $payment_name = $payment?$payment->payment_name:$order->payment_code;			
		    $place_on = Date_Formatter::dateTime($order->date_created);

			if($order->payment_code=="paydelivery" || $order->payment_code=="myfatoorah"){
				try {
					$ordermeta =  CPayments::ordernewTransMeta($order->order_id,'payment_name');
					$payment_name = $ordermeta->meta_value;
				} catch (Exception $e) {}				
			} else if ( $order->payment_code==CDigitalWallet::transactionName() ){
				$payment_name = CDigitalWallet::paymentName();
			}

		    
		    $transaction = AR_ordernew_transaction::model()->find('order_id=:order_id', 
		    array(':order_id'=>$order->order_id)); 	
		    
		    $payment_name_stats = ''; $paid_on='';
		    if($transaction){		    	
		    	if($transaction->status=="paid"){
		    		$payment_name_stats = t("Paid by {payment_name}",array('{payment_name}'=>$payment_name));
		    		$paid_on = t("Paid on {date}",array('{date}'=>Date_Formatter::dateTime($transaction->date_created)));
		    	} else {
		    		$payment_name_stats = t("Payment by {payment_name}",array('{payment_name}'=>$payment_name));
		    	}		    	
		    } else $payment_name_stats = $payment_name;
		    
		    $delivery_date = ''; $due_at='';
		    if($order->whento_deliver=="now"){
		    	$delivery_date = t("Asap");
		    } else {
		    	$due_at = Date_Formatter::dateTime( $order->delivery_date." ".$order->delivery_time );
				//$due_at = Date_Formatter::dateTime( $order->delivery_date." ".$order->delivery_time ,"yyyy-MM-dd HH:mm",true);
		    	$delivery_date = t("Scheduled at [delivery_date]",array(
				    	 '[delivery_date]'=>$due_at
				    	));
		    	if($order->delivery_date==$datenow){
		    		$delivery_date = t("Due at [delivery_date], Today",array(
				    	 '[delivery_date]'=>$due_at
				    	));
		    	}
		    }		    
		        		
		    $total = COrders::getTotal();		  
			$total_exchange_rate_use_currency_to_admin = ($total*$order->exchange_rate_use_currency_to_admin);  
			$total_from_used_currency_to_based_currency = ($total*$order->exchange_rate);  
			$total_from_merchant_to_admin_currency = ($total*$order->exchange_rate_merchant_to_admin); 			

			$price_list_format = []; $price_format_used_currency_to_based_currency = [];
			$price_format_total_exchange_rate_use_currency_to_admin = [];
						
			$price_list = CMulticurrency::getAllCurrency();
						
			$price_list_format = isset($price_list[$order->admin_base_currency])?$price_list[$order->admin_base_currency]:Price_Formatter::$number_format;
			$price_format_used_currency_to_based_currency = isset($price_list[$order->use_currency_code])?$price_list[$order->use_currency_code]:Price_Formatter::$number_format;				
			$price_format_total_exchange_rate_use_currency_to_admin = isset($price_list[$order->admin_base_currency])?$price_list[$order->admin_base_currency]:Price_Formatter::$number_format;				
			
			$points_to_earn = isset($attr['points_to_earn'])?floatval($attr['points_to_earn']):'';
			
			$exchange_rate = self::getExchangeRate();			
			$attr_payment_change = isset($attr['payment_change'])?trim($attr['payment_change']):0;						
			$payment_change = $attr_payment_change*$exchange_rate;		
			
			$item_count = self::itemCount( self::$order_id );

			$address_format_use = isset($attr['address_format_use'])?$attr['address_format_use']:1;
			$formatted_address = Yii::app()->input->xssClean($order->formatted_address);
			$address1 = isset($attr['address1'])?Yii::app()->input->xssClean($attr['address1']):'';
			$address2 = isset($attr['address2'])?Yii::app()->input->xssClean($attr['address2']):'';
			$postal_code = isset($attr['postal_code'])?Yii::app()->input->xssClean($attr['postal_code']):'';
			$location_name = isset($attr['location_name'])?trim($attr['location_name']):'';			
			$company = isset($attr['company'])?Yii::app()->input->xssClean($attr['company']):'';

			$complete_delivery_address = '';
			if($address_format_use==2){				
				$complete_delivery_address = "$address1 $formatted_address";
				if(!empty($location_name)){
					$complete_delivery_address.=", $location_name";
				}
				if(!empty($address2)){
					$complete_delivery_address.=", $address2";
				}
				if(!empty($postal_code)){
					$complete_delivery_address.=", $postal_code";
				}
				if(!empty($company)){
					$complete_delivery_address.=", $company";
				}
			} else {
				$complete_delivery_address = "$address1 $formatted_address";
			}

            // get promo code data for order
            $promo = AR_voucher::model()->find('voucher_name=:voucher_name',
                array(':voucher_name'=>$order->promo_code));

            $promoData = [
                'voucher_name' => 'no promo',
                'voucher_id' => 0,
                'yummy_pay' => 0,
                'merchant_pay' => 0
            ];

            if($promo){
                if($order->delivery_fee==0){
                    $shipping_rate = AR_shipping_rate::model()->findAll('merchant_id=:merchant_id',
                        array(':merchant_id'=>$order->merchant_id));
                    $fee = $shipping_rate[0]['distance_price'];

                    [$yummy_pay, $merchant_pay] = COrders::getYummyAndMerchantDeliveryPay($promo, $fee);
                    $promoData = [
                       'voucher_name' => $promo->voucher_name,
                       'voucher_id' => $promo->voucher_id,
                       'yummy_pay' => $yummy_pay,
                       'merchant_pay' => $merchant_pay
                    ];
                }
            }

    		$order_info = array(
    		  'client_id'=>$order->client_id,
    		  'merchant_id'=>$order->merchant_id,
    		  'order_id'=>$order->order_id,    		      		  
			  'order_id1'=>t("Order #{order_id}",[
				'{order_id}'=>$order->order_id
			  ]),
              'prep_time' => $order->prep_time,
              'actual_prep_time' => $order->actual_prep_time,
              'prep_time_enabled_at' => Date_Formatter::dateTime($order->prep_time_enabled_at,"HH:mm",true),
    		  'promo_code' => $order->promo_code,
              'order_uuid'=>$order->order_uuid,
			  'order_reference'=>$order->order_reference,
			  'request_from'=>$order->request_from,
    		  'total'=>$total,
    		  'total_original'=>$order->total_original,
    		  'pretty_total'=>Price_Formatter::formatNumber($total),			  
    		  'pretty_sub_total'=>Price_Formatter::formatNumber($order->sub_total),
    		  'pretty_total_discount'=>Price_Formatter::formatNumber($order->total_discount),
    		  'total_discount'=> $order->total_discount,
			  'amount_due_raw'=>(float)$order->amount_due,
			  'amount_due'=>Price_Formatter::formatNumber($order->amount_due),
			  'wallet_amount_raw'=>(float)$order->wallet_amount,
			  'wallet_amount'=>Price_Formatter::formatNumber($order->wallet_amount),
    		  'commission'=>$order->commission,
			  'delivery_fee'=>$order->delivery_fee,
			  'pretty_delivery_fee'=>Price_Formatter::formatNumber($order->delivery_fee),
			  'use_currency_code'=>$order->use_currency_code,
			  'base_currency_code'=>$order->base_currency_code,
			  'exchange_rate'=>floatval($order->exchange_rate),
			  'admin_base_currency'=>$order->admin_base_currency,
			  'exchange_rate_use_currency_to_admin'=>$order->exchange_rate_use_currency_to_admin,
			  'exchange_rate_merchant_to_admin'=>floatval($order->exchange_rate_merchant_to_admin),
			  'exchange_rate_admin_to_merchant'=>floatval($order->exchange_rate_admin_to_merchant),

			  'total_exchange_rate_use_currency_to_admin'=>$total_exchange_rate_use_currency_to_admin,
			  'total_exchange_rate_use_currency_to_admin_pretty'=>Price_Formatter::formatNumber2($total_exchange_rate_use_currency_to_admin,$price_format_total_exchange_rate_use_currency_to_admin),

			  'total_from_used_currency_to_based_currency'=>$total_from_used_currency_to_based_currency,
			  'total_from_used_currency_to_based_currency_pretty'=>Price_Formatter::formatNumber2($total_from_used_currency_to_based_currency,$price_format_used_currency_to_based_currency),

			  'total_from_merchant_to_admin_currency'=>$total_from_merchant_to_admin_currency,
			  'total_from_merchant_to_admin_currency_pretty'=>Price_Formatter::formatNumber2($total_from_merchant_to_admin_currency,$price_list_format),

    		  'status'=>$order->status,		
			  'is_completed'=>in_array($order->status,(array)$status_completed)?true:false,
			  'delivery_status'=>$order->delivery_status,
    		  'payment_status'=>$order->payment_status,   
			  'payment_status1'=>isset($payment_status[$order->payment_status])?$payment_status[$order->payment_status]['title']:$order->payment_status,   
    		  'payment_code'=>$order->payment_code,
    		  'payment_name'=>$payment_name_stats,
			  'payment_by_wallet'=>t("Paid by Digital Wallet"),
    		  'service_code'=>$order->service_code,    		
    		  'order_type'=>$order->service_code,    		
			  'order_type1'=>isset($services[$order->service_code])?$services[$order->service_code]['service_name']:$order->service_code,    		
    		  'whento_deliver'=>$order->whento_deliver,
    		  'schedule_at'=>$delivery_date,    		  
    		  'delivery_date'=>$order->delivery_date, 		  
			  'delivery_date1'=>Date_Formatter::dateTime($order->date_created,"dd.MM.yyyy",true),
    		  'delivery_time'=>$order->delivery_time, 		  	 
			  'delivery_time1'=>Date_Formatter::dateTime($order->delivery_time,"hh:mm",true),
    		  'place_on'=>t("Place on {{date}}",array('{{date}}'=>$place_on)),
    		  'place_on_raw'=>$place_on,
    		  'paid_on'=>$paid_on,
			  'place_datetime'=>Date_Formatter::dateTime($order->date_created,"dd.MM.yyyy hh:mm",true),
			  'complete_delivery_address'=>$complete_delivery_address,
    		  'delivery_address'=>Yii::app()->input->xssClean($order->formatted_address),
			  'address1'=>isset($attr['address1'])?Yii::app()->input->xssClean($attr['address1']):'',
			  'address2'=>isset($attr['address2'])?Yii::app()->input->xssClean($attr['address2']):'',
			  'address_format_use'=>$address_format_use,
			  'postal_code'=>isset($attr['postal_code'])?Yii::app()->input->xssClean($attr['postal_code']):'',
			  'company'=>isset($attr['company'])?Yii::app()->input->xssClean($attr['company']):'',
    		  'customer_name'=>isset($attr['customer_name'])?Yii::app()->input->xssClean($attr['customer_name']):'',
    		  'contact_number'=>isset($attr['contact_number'])?CommonUtility::prettyMobile(Yii::app()->input->xssClean($attr['contact_number'])):'',
    		  'contact_email'=>isset($attr['contact_email'])?Yii::app()->input->xssClean($attr['contact_email']):'',
    		  'include_utensils'=>isset($attr['include_utensils'])?$attr['include_utensils']:'',
    		  'longitude'=>isset($attr['longitude'])?$attr['longitude']:'',
    		  'latitude'=>isset($attr['latitude'])?$attr['latitude']:'',    		  
    		  'rejetion_reason'=>isset($attr['rejetion_reason'])?$attr['rejetion_reason']:'',
    		  'delayed_order'=>isset($attr['delayed_order'])?$attr['delayed_order']:'',
    		  'delayed_order_mins'=>isset($attr['delayed_order_mins'])?$attr['delayed_order_mins']:'',
    		  'order_notes'=>isset($attr['order_notes'])?$attr['order_notes']:'',
    		  'order_change'=>isset($attr['order_change'])?floatval($attr['order_change']):0,
    		  'receive_amount'=>isset($attr['receive_amount'])?floatval($attr['receive_amount']):0,
    		  'tracking_link'=>$tracking_link,
			  'address_label'=>isset($attr['address_label'])?t($attr['address_label']):'',
			  'delivery_options'=>isset($attr['delivery_options'])?t($attr['delivery_options']):'',
			  'location_name'=>isset($attr['location_name'])?trim($attr['location_name']):'',
			  'payment_change'=>$payment_change,			  
			  'payment_change_pretty'=>Price_Formatter::formatNumber($payment_change),
			  'driver_id'=>$order->driver_id,
			  'order_otp'=>isset($attr['order_otp'])?$attr['order_otp']:'',	
			  'timezone'=>isset($attr['timezone'])?$attr['timezone']:'',	
			  'delivery_instructions'=>isset($attr['delivery_instructions'])?trim($attr['delivery_instructions']):'',
			  'upload_deposit_link'=>$upload_deposit_link,
			  'points_to_earn'=>$points_to_earn,
			  'points_label'=>$points_to_earn>0? t("This order will earns you {points} points!",['{points}'=>$points_to_earn]) :'',
			  'points_label2'=>$points_to_earn>0? t("This order will earns {points} points!",['{points}'=>$points_to_earn]) :'',
			  'total_items'=>$item_count,
			  'total_items1'=>Yii::t('front', '{n} item|{n} item(s)', intval($item_count)),
			  'view_order_link'=>$view_order_link
    		);

    		return array(
    		  'order_info'=>$order_info,    		   
    		  'status'=>$status, 
    		  'services'=>$services,
              'promo' => $promoData,
    		  'payment_status'=>$payment_status,
    		  'payment_list'=>AttributesTools::PaymentProvider(),
			  'delivery_status'=>$delivery_status
    		);
    	}
    	return false;
    }

    public static function getYummyAndMerchantDeliveryPay($promo, $delivery_fee): array
    {
        $yummy_pay = $merchant_pay = 0;
        if($promo->discount_delivery == 1) {
            if($promo->delivery_cost_payer == 1) {
                $yummy_pay = $delivery_fee;
            }else if($promo->delivery_cost_payer == 2) {
                if($promo->paying_way_merchant == 2) {
                    $merchant_pay = $delivery_fee;
                }
            }else if($promo->delivery_cost_payer == 3) {
                $yummy_pay = $delivery_fee * ($promo->yummy_pay_percentage / 100);
                $merchant_pay = $delivery_fee * ($promo->merchant_pay_percentage / 100);
            }
        }

        return [$yummy_pay, $merchant_pay];
    }
    
    public static function orderTransaction($order_uuid='',$merchant_id='',$lang=KMRS_DEFAULT_LANGUAGE)
    {    	
    	$transaction='';
    	$stmt="
    	SELECT a.service_code
    	FROM {{services}} a
    	WHERE a.service_code IN (
		  select meta_value from {{merchant_meta}}
		  where meta_name='services'
		  and merchant_id = ".q($merchant_id)."
		  and meta_value IN (
		    select service_code from {{ordernew}}
		    where order_uuid = ".q($order_uuid)."		    
		  )
		)
    	"; 		
    	if( $res = Yii::app()->db->createCommand($stmt)->queryRow() ){     		
    		$transaction = $res['service_code'];
    	} else $transaction = CCheckout::getFirstTransactionType($merchant_id,$lang);
    	return $transaction;
    }
    
    public static function getCancelStatus($order_uuid='')
    {
    	$all_online = CPayments::getPaymentTypeOnline();		
    	$new_status = self::newOrderStatus();
    	$processing_status = self::getStatusTab(array('order_processing'));		
    	$model = AR_ordernew::model()->find('order_uuid=:order_uuid', 
		    array(':order_uuid'=>$order_uuid)); 
		if($model){

			$exchange_rate = $model->exchange_rate>0?$model->exchange_rate:1;
			Price_Formatter::init($model->use_currency_code);			
			
			$condition = 0;
	    	$refund_status = 'no_refund'; 
	    	$refund_amount = 0;
	    	$refund_msg = '';
	    		    	
	    	$cancel_status=false;
	    	$cancel_msg = '';
	    	$payment_type = '';
	    	
	    	/*dump($model->order_id);
	    	dump($new_status);dump($processing_status);
	    	dump($model->payment_status);
	    	dump($model->payment_code);	*/
	    	
	    	if($model->payment_status=="paid" && array_key_exists($model->payment_code,(array)$all_online) ){	
	    		$payment_type = 'online';
	    		if($model->status==$new_status && $model->driver_id<=0){
	    			//Restaurant has not confirmed order and a driver has not been assigned
	    			$condition = 1;
	    			$cancel_status = true;		
	    			$cancel_msg = t("Your order has not been accepted so there is no charge to cancel. Your payment will be refunded to your account.",array(
		    		  '[order_id]'=>$model->order_id
		    		));	    				    		
		    		
	    			$refund_status = 'full_refund';
	    			$refund_amount = floatval($model->total);	    			
	    			$refund_msg = t("Your total refund will be {{amount}}",array(
	    			  '{{amount}}'=>Price_Formatter::formatNumber( ($refund_amount*$exchange_rate) )
	    			));
	    		} elseif ( $model->status==$new_status && $model->driver_id>0 ){
	    			//Restaurant has not confirmed order but a driver has been assigned
	    			$condition = 2;
		    		$cancel_status = true;
		    		$cancel_msg = t("Your driver is already on their way to pick up your order, so we can only refund the subtotal and tax");
		    		
		    		$refund_status = 'partial_refund';		    		
		    		$refund_amount = $model->sub_total + $model->tax_total;
	    			$refund_msg = t("Your total refund will be {{amount}}",array(
	    			  '{{amount}}'=>Price_Formatter::formatNumber( ($refund_amount*$exchange_rate) )
	    			));
		    		
	    		} elseif ( in_array($model->status,(array)$processing_status) && $model->driver_id<=0 ){
	    			//Restaurant has confirmed order but a driver has not been assigned
	    			$condition = 3;
		    		$cancel_status = true;		    		
		    		$cancel_msg = t("The store has started preparing this order so we can only refund the delivery charges and driver tip");
		    		
		    		$refund_status = 'partial_refund';		    		    	
		    		$refund_amount = $model->delivery_fee + $model->courier_tip;
	    			$refund_msg = t("Your total refund will be {{amount}}",array(
	    			  '{{amount}}'=>Price_Formatter::formatNumber( ($refund_amount*$exchange_rate) )
	    			));
	    		} elseif ( in_array($model->status,(array)$processing_status) && $model->driver_id>0 ){
	    			//Restaurant has confirmed order and a driver has been assigned
	    			$condition = 4;
		    		$cancel_status = false;
		    		$cancel_msg = t("Store has confirmed order and a driver has been assigned, so we cannot cancel this order");
	    		} else {
	    			$condition = 5;
		    		$cancel_status = false;
		    		$cancel_msg = t("Refund is not available for this order");
	    		}
	    	} else {	    	
	    		$payment_type = 'offline';	
	    		if($model->status==$new_status && $model->driver_id<=0){
	    			//Restaurant has not confirmed order and a driver has not been assigned
	    			$condition = 1;
	    			$cancel_status = true;	
	    			$refund_status = 'full_refund';	
	    			$cancel_msg = t("Your order has not been accepted so there is no charge to cancel, click cancel order to proceed",array(
		    		  '[order_id]'=>$model->order_id
		    		));	    		
	    		} elseif ( $model->status==$new_status && $model->driver_id>0 ){
	    			//Restaurant has not confirmed order but a driver has been assigned
	    			$condition = 2;
		    		$cancel_status = false;
		    		$cancel_msg = t("The driver has already on the way to pickup your order so we cannot cannot cancel this order");
	    		} elseif ( in_array($model->status,(array)$processing_status) && $model->driver_id<=0 ){
	    			//Restaurant has confirmed order but a driver has not been assigned
	    			$condition = 3;
		    		$cancel_status = false;
		    		$cancel_msg = t("The restaurant has started preparing this order so we cannot cancel this order");
	    		} elseif ( in_array($model->status,(array)$processing_status) && $model->driver_id>0 ){
	    			//Restaurant has confirmed order and a driver has been assigned
	    			$condition = 4;
		    		$cancel_status = false;
		    		$cancel_msg = t("Store has confirmed order and a driver has been assigned, so we cannot cancel this order");
	    		} else {
	    			$condition = 5;
		    		$cancel_status = false;
		    		$cancel_msg = t("Refund is not available for this order");
	    		}
	    	}
	    	
	    	
	    	/*dump("RESULTS");
	    	dump($condition);
	    	dump("cancel status=>$cancel_status");
	    	dump($cancel_msg);*/
	    	
	    	return  array(
	    	  'status'=>$model->status,
	    	  'cancel_status'=>$cancel_status,
	    	  'cancel_msg'=>$cancel_msg,
	    	  'refund_status'=>$refund_status,
	    	  'refund_amount'=>($refund_amount*$exchange_rate),
	    	  'refund_msg'=>$refund_msg,
	    	  'condition'=>$condition,
	    	  'payment_type'=>$payment_type,
	    	);		
	    	
		} else throw new Exception( 'order not found' );
    }
    
    public static function getOrderSummary($client_id=0)
    {    
    	$total_qty =0; $total_order=0;	
    	$stmt="
    	SELECT count(*) as total_qty,
    	sum(total) as total_order
    	FROM {{ordernew}}
    	WHERE client_id=".q($client_id)."
    	";    	    	
		$dependency = CCacheData::dependency(); 		
		if($res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency)->createCommand($stmt)->queryRow()){
			$total_qty = $res['total_qty'];
			$total_order = $res['total_order'];
		}
		return array(
		   'total_qty'=>$total_qty,
		   'total_order_raw'=>$total_order,
		   'total_order'=>Price_Formatter::formatNumber($total_order),
		);
    }
    
    public static function getClientInfo($client_id='')
    {    	
    	$model = AR_client::model()->find('client_id=:client_id',
    	array(
    	  ':client_id'=>intval($client_id)
    	)); 
		if($model){
			
			$avatar = CMedia::getImage($model->avatar,$model->path,'@thumbnail',
		    CommonUtility::getPlaceholderPhoto('customer'));
		    		
			return array(
			  'client_id'=>$model->client_id,
			  'client_uuid'=>$model->client_uuid,
			  'first_name'=>Yii::app()->input->xssClean($model->first_name),
			  'last_name'=>Yii::app()->input->xssClean($model->last_name),
			  'contact_phone'=>CommonUtility::prettyMobile(Yii::app()->input->xssClean($model->contact_phone)),
			  'email_address'=>Yii::app()->input->xssClean($model->email_address),
			  'avatar'=>$avatar,
			  'member_since'=>Date_Formatter::dateTime($model->date_created)
			);
		}
		return false;
    }
    
    public static function getCustomerOrderCount($client_id='',$merchant_id=0)
    {
    	$draft = AttributesTools::initialStatus();  
    	$criteria = new CDbCriteria;    
    	
    	if($merchant_id>0){
	    	$criteria->addCondition('client_id=:client_id AND merchant_id =:merchant_id');
	    	$criteria->params = array( 
			    ':client_id' => intval($client_id),
			    ':merchant_id' => intval($merchant_id),
			  );	  
    	} else {    		    		
    		$criteria->addCondition('client_id=:client_id');
	    	$criteria->params = array( 
			    ':client_id' => intval($client_id),			    
			  );	  
    	}
		$criteria->addNotInCondition('status', array($draft) );    	  
    	$count = AR_ordernew::model()->count($criteria);    	
    	return $count;	
    }
    
    public static function getSubTotal_lessDiscount()
    {
    	$subtotal = COrders::getSubTotal();
    	$sub_total = floatval($subtotal['sub_total']);
    	return $sub_total;
    }
    
    public static function updateSummary($order_uuid='')
    {
    	
    	$model = COrders::get($order_uuid);	
    	$total_old = $model->total_original;
    	$commission_old = $model->commission_original;
    	
    	$model->scenario = 'adjustment';
    	COrders::getContent($order_uuid,Yii::app()->language);
    	$summary = COrders::getSummary();  
    	
    	$sub_total = 0; $total = 0;
    	$sub_total_less_discount  = 0;
    	$total_discount = 0; $offer_total=0; $service_fee=0;
    	$packagin_fee=0; $packagin_fee =0; $tip = 0; $total_tax = 0;
		$delivery_fee = 0;
    	    	    	
    	if($model && $summary){    		
    		if(is_array($summary) && count($summary)>=1){	
				foreach ($summary as $summary_item) {						
					switch ($summary_item['type']) {
						case "subtotal":
							$sub_total = self::cleanNumber($summary_item['raw']);
							break;
							
						case "voucher":
							$total_discount = self::cleanNumber($summary_item['raw']);
							$total_discount = floatval($total_discount)+ floatval($total_discount);
							break;
					
						case "offers":	
						    $total_discount = self::cleanNumber($summary_item['raw']);
						    $offer_total = $total_discount;
						    $total_discount = floatval($total_discount)+ floatval($total_discount);
							break;
							
						case "service_fee":
							$service_fee = self::cleanNumber($summary_item['raw']);
							break;
							
						case "delivery_fee":
							$delivery_fee = self::cleanNumber($summary_item['raw']);
							break;	
						
						case "packaging_fee":
							//$packaging_fee = self::cleanNumber($summary_item['raw']);
							break;			
							
						case "tip":
							$tip = self::cleanNumber($summary_item['raw']);
							break;				
							
						case "tax":
							$total_tax = self::cleanNumber($summary_item['raw']);
							break;			
							
						case "total":			
						    $total = self::cleanNumber($summary_item['raw']);
						    break;	
								
						default:
							break;
					}
				}				
			}
			
    		
			$packaging_fee = self::getPackagingFee();
			
			$commission_based = ''; $commission=0; $merchant_earning=0; $merchant_commission = 0;
			$merchant_type = ''; $commision_type = '';
			
			if($merchant_info = self::getMerchant($model->merchant_id,Yii::app()->language)){			   
			   $merchant_type = $merchant_info['merchant_type'];
			   $commision_type = $merchant_info['commision_type'];				
			   $merchant_commission = $merchant_info['commission'];		
			   $tax_settings = self::getTaxSettings();

			   $options_data = OptionsTools::find(['self_delivery','merchant_disabled_pos_earnings'],$model->merchant_id);
			   $self_delivery = isset($options_data['self_delivery'])? ($options_data['self_delivery']==1?true:false) :false;
			   $disabled_pos_earnings = isset($options_data['merchant_disabled_pos_earnings'])? ($options_data['merchant_disabled_pos_earnings']==1?true:false) :false;
			   			   
			   if(!$disabled_pos_earnings):
				$resp_comm = CCommission::getCommissionValueNew([
					'merchant_id'=>$model->merchant_id,
					'transaction_type'=>$model->service_code,
					'merchant_type'=>$merchant_type,
					'commision_type'=>$commision_type,
					'merchant_commission'=>$merchant_commission,
					'sub_total'=>$sub_total,
					'sub_total_without_cnd'=>$sub_total,
					'total'=>$total,
					'service_fee'=>$service_fee,
					'delivery_fee'=>$delivery_fee,
					'tax_settings'=>$tax_settings,
					'tax_total'=>$total_tax,
					'self_delivery'=>$self_delivery
				]);			  			   
				if($resp_comm){					
					$commission_based = $resp_comm['commission_based'];
					$commission = $resp_comm['commission'];
					$merchant_earning = $resp_comm['merchant_earning'];
					$merchant_commission = $resp_comm['commission_value'];
				}			
			  endif;

			}			
			
			
			$adjustment_commission = floatval($commission) - floatval($commission_old);			
			$adjustment_total = floatval($total) - floatval($total_old);		
										
			$sub_total_less_discount = 	floatval($sub_total)-floatval($total_discount);
			$model->sub_total = floatval($sub_total);
			$model->sub_total_less_discount = floatval($sub_total_less_discount);
			$model->packaging_fee = floatval($packaging_fee);
			$model->tax_total = floatval($total_tax);
			$model->total = floatval($total);	
			$model->commission_type = $commision_type;
			$model->commission_value = $merchant_commission;
			$model->commission_based = $commission_based;
			$model->commission = floatval($commission);
			$model->merchant_earning = floatval($merchant_earning);
			$model->adjustment_commission = floatval($adjustment_commission);
			$model->adjustment_total = floatval($adjustment_total);
			$model->save();			
						
			return true;
    	} 
    	throw new Exception( 'order not found' );
    }
    
    public static function add($data = array() )
    {    	
    	    	    	    	
    	$items = new AR_ordernew_item;
    	$scenario = isset($data['scenario'])?$data['scenario']:'';
    	$order_uuid = isset($data['order_uuid'])?$data['order_uuid']:'';
    	if(!empty($scenario)){
    	   $items->scenario = $scenario;
    	}
    	if(!empty($order_uuid)){
    		$items->order_uuid = $order_uuid;
    	}
		$items->item_row = isset($data['cart_row'])?$data['cart_row']:'';
		$items->order_id = isset($data['order_id'])?$data['order_id']:'';		
		$items->cat_id = isset($data['cat_id'])?(integer)$data['cat_id']:'';
		$items->item_id = isset($data['item_id'])?(integer)$data['item_id']:'';
		$items->item_token = isset($data['item_token'])?$data['item_token']:'';
		$items->item_size_id = isset($data['item_size_id'])?(integer)$data['item_size_id']:'';
		$items->qty = isset($data['qty'])?(integer)$data['qty']:'';
		$items->special_instructions = isset($data['special_instructions'])?$data['special_instructions']:'';
		$items->if_sold_out = isset($data['if_sold_out'])?$data['if_sold_out']:'';
		$items->price = isset($data['price'])?floatval($data['price']):0;
		$items->discount = isset($data['discount'])?floatval($data['discount']):0;
		$items->discount_type = isset($data['discount_type'])?trim($data['discount_type']):'';
		$items->tax_use = isset($data['tax_use'])? json_encode($data['tax_use']) :'';
		
		/*CHECK IF ITEM IS FOR REPLACEMENT*/
		if(!empty($data['item_row']) && !empty($data['old_item_token'])  ){			
			$items->item_changes = "replacement";
			$items->item_changes_meta1 = $data['old_item_token'];			
			$models = AR_ordernew_item::model()->find("item_row=:item_row",array(
			 ':item_row'=>$data['item_row']
			));						
			if($models){
				$models->delete();
			}
		}		
		if($items->save()){
			
			$builder=Yii::app()->db->schema->commandBuilder;
			
			// addon
			$item_addons = array();
			$addons = isset($data['addons'])?$data['addons']:'';
			if(is_array($addons) && count($addons)>=1){
				foreach ($addons as $item) {		
					$addon_qty = intval($item['qty']);
					if($item['multi_option']!="multiple"){
						$addon_qty = $items->qty;
					}					
					$addon_price = isset($item['price'])?floatval($item['price']):0;		
					$addons_total = $addon_qty*$addon_price;
					$item_addons[] = array(
					 'order_id'=>isset($data['order_id'])?$data['order_id']:'',
					 'item_row'=>isset($item['cart_row'])?$item['cart_row']:'',					 
					 'subcat_id'=>isset($item['subcat_id'])?(integer)$item['subcat_id']:0,
					 'sub_item_id'=>isset($item['sub_item_id'])?(integer)$item['sub_item_id']:0,
					 'qty'=>$addon_qty,
					 'price'=>floatval($addon_price),
					 'addons_total'=>floatval($addons_total),
					 'multi_option'=>isset($item['multi_option'])?$item['multi_option']:'',
					);
				}				
				$command=$builder->createMultipleInsertCommand('{{ordernew_addons}}',$item_addons);
				$command->execute();
			}
			
			// attributes
			$item_attributes = array();
			$attributes = isset($data['attributes'])?$data['attributes']:'';			
			if(is_array($attributes) && count($attributes)>=1){
				foreach ($attributes as $item) {					
					$item_attributes[] = array(
					 'order_id'=>isset($data['order_id'])?$data['order_id']:'',
					 'item_row'=>isset($item['cart_row'])?$item['cart_row']:'',					 
					 'meta_name'=>isset($item['meta_name'])?$item['meta_name']:'',
					 'meta_value'=>isset($item['meta_id'])?(integer)$item['meta_id']:'',
					);					
				}						
				$command=$builder->createMultipleInsertCommand('{{ordernew_attributes}}',$item_attributes);
				$command->execute();
			}
						
			if($scenario=="update_cart"){				
				Yii::import('ext.runactions.components.ERunActions');	
				$cron_key = CommonUtility::getCronKey();		
				$get_params = array( 
				   'order_uuid'=> $order_uuid,
				   'key'=>$cron_key,
				   'language'=>Yii::app()->language
				);			
				CommonUtility::runActions( CommonUtility::getHomebaseUrl()."/task/updatesummary?".http_build_query($get_params) );
			}
			
			return true;
		} 
		throw new Exception( 'Error inserting records' );
    }
    
    public static function paymentHistory($order_id=0)
    {
    	$all_online = CPayments::getPaymentTypeOnline();
    	$model = AR_ordernew_transaction::model()->findAll("order_id=:order_id ORDER BY transaction_id DESC",array(
    	 ':order_id'=>$order_id
    	));    	
    	if($model){
    		$data = array();
    		foreach ($model as $item) {    		
    			$is_online = array_key_exists($item->payment_code,(array)$all_online)?true:false;
    				
    			$data[] = array( 
    			   'is_online'=>$is_online,    			   
    			   'transaction_id'=>$item->transaction_id,
    			   'payment_code'=>$item->payment_code,
    			   'transaction_type'=>$item->transaction_type,
    			   'transaction_description'=>t($item->transaction_description),
    			   'trans_amount'=>Price_Formatter::formatNumber( ($item->trans_amount*self::$exchange_rate) ),
    			   'currency_code'=>$item->currency_code,
    			   'payment_reference'=>$item->payment_reference,
    			   'status'=>$item->status,
    			   'reason'=>$item->reason,
    			   'date_created'=>Date_Formatter::dateTime($item->date_created),    			       			   
    			);
    		}

            $walletTransactions = CWallet::getTransactionsByOrder($order_id);
            if($walletTransactions){
                foreach ($walletTransactions as $transaction) {
                    $data[] = array(
                        'is_online'=>false,
                        'transaction_id'=>$transaction->transaction_id,
                        'payment_code'=>'wallet transaction',
                        'transaction_type'=>$transaction->transaction_type,
                        'transaction_description'=>t($transaction->transaction_description). ' - ' .COrders::getAccountTypeOfCard($transaction->card_id).' adjustment',
                        'trans_amount'=>Price_Formatter::formatNumber( ($transaction->transaction_amount*self::$exchange_rate) ),
                        'currency_code'=>$transaction->admin_base_currency ?? $transaction->merchant_base_currency,
                        'payment_reference'=>$transaction->reference_id,
                        'status'=>$transaction->status,
                        'reason'=>'adjustment',
                        'date_created'=>'',
                    );
                }
            }
            return $data;
    	}
    	return false;
    }

    public static function getAccountTypeOfCard($card_id)
    {
        $model = AR_wallet_cards::model()->find("card_id=:card_id",array(
            ':card_id'=>$card_id,
        ));
        if($model){
            return $model->account_type;
        }
        return false;
    }
    public static function getRefundItemTotal($item_changes='',$tax=0,$order_id='',$item_row='')
    {    	    	
    	$total_amount = 0; 
    	$description = 'Item refund for {{item_name}}';
    	if($item_changes=="out_stock"){
    		$description = 'Item out of stock for {{item_name}}';
    	}
    	if($order_id>0 && !empty($item_row)){
    		$items = AR_ordernew_item::model()->find("item_row=:item_row",array(
			 ':item_row'=>$item_row
			));		
			if($items){								
				$price = $items->price;
				$qty = $items->qty; 
				$discount = $items->discount;
				$addons_total = 0;
				if($discount>0){
					$price = floatval($price)-floatval($discount);
				}
				$price = floatval($price)*intval($qty);
				
				$item = AR_item::model()->find("item_id=:item_id",array(
				 ':item_id'=>$items->item_id
				));
				if($item){
					$description = t($description,array(
					  '{{item_name}}'=>Yii::app()->input->xssClean($item->item_name)
					));
				}
				
				$addons = AR_ordernew_addons::model()->findAll("order_id=:order_id AND item_row=:item_row",array(
				 ':order_id'=>$order_id,
				 ':item_row'=>$item_row
				));
				if($addons){
					foreach ($addons as $addon) {
						$addons_total+= floatval($addon->addons_total);
					}
				}			
				$total_amount = floatval($price)+floatval($addons_total);	
				$tax = floatval($tax)/100;
				$tax_amount = floatval($total_amount) * floatval($tax);
				$total_amount = $total_amount + $tax_amount;
			}
    	}
    	return array(
    	  'total_amount'=>floatval($total_amount),
    	  'description'=>$description
    	);
    }
    
    public static function getExistingRefund($order_id='')
    {
    	$transaction = AR_ordernew_transaction::model()->find("order_id=:order_id 
    	AND transaction_type=:transaction_type    	
    	AND status=:status    	
    	 ",array(
		  ':order_id'=>intval($order_id),
		  ':transaction_type'=>"refund",		  
		  ':status'=>"paid",
		));
		if($transaction){
			throw new Exception( 'Cannot cancel this order, this order has existing refund.' );
		}
		return true;		
    }
    
    public static function getTotalPayment($order_id=0, $transaction_type="", $status=array())
    {
    	$criteria=new CDbCriteria();
		$criteria->select="sum(trans_amount) as trans_amount";
		$criteria->condition = "order_id=:order_id AND transaction_name=:transaction_name";		    
		$criteria->params  = array(
		  ':order_id'=>intval($order_id),		  
		  ':transaction_name'=>$transaction_type,
		);		
		$criteria->addInCondition('status', (array) $status );
		$model = AR_ordernew_transaction::model()->find($criteria); 
		if($model){
			return $model->trans_amount;
		}
		return 0;
    }
        
    public static function getTransactionPayment($transaction_id='')
    {
    	$model = AR_ordernew_transaction::model()->findbyPK( intval($transaction_id) );
    	if($model){
    		return $model;
    	}    	
    	throw new Exception( 'transaction not found' );
    }
    
    public static function getInvoicePayment($order_id='', $status='paid' , $transaction_name='')
    {
    	$model = AR_ordernew_transaction::model()->findAll("order_id=:order_id AND status=:status AND transaction_name=:transaction_name",array(
    	  ':order_id'=>intval($order_id),
    	  ':status'=>$status,
    	  ':transaction_name'=>$transaction_name
    	));
    	if($model){
    		return $model;
    	}
    	throw new Exception( 'No invoice payment found' );
    }
    
    public static function getPaymentTransactionList($client_id=0, $order_id=0, $status=array() , $transaction_name=array())
    {
    	$criteria=new CDbCriteria();
    	$criteria->alias="a";
    	$criteria->select = "a.client_id, a.order_id, a.status, a.transaction_name,
    	a.date_created, a.date_modified, a.payment_code, a.transaction_description, a.trans_amount,
    	a.status, a.exchange_rate,
    	b.attr2 as used_card
    	";    	
    	$criteria->join='LEFT JOIN {{client_payment_method}} b on  a.payment_uuid=b.payment_uuid ';
    	
    	$criteria->condition = "a.client_id=:client_id AND a.order_id=:order_id";    	
    	$criteria->params = array(
    	 ':client_id'=>intval($client_id),
    	 ':order_id'=>intval($order_id),
    	);
    	$criteria->addInCondition('a.status', (array) $status );
    	$criteria->addInCondition('a.transaction_name', (array) $transaction_name );    	
    	$model = AR_ordernew_transaction::model()->findAll($criteria);        
    	if($model){
    		$data = array();
			$wallet_payment_code = CDigitalWallet::transactionName();
    		foreach ($model as $item) {    							
				$exchange_rate = $item->exchange_rate>0?$item->exchange_rate:1;
    			$data[] = array(
    			  'date'=>Date_Formatter::dateTime($item->date_modified),
    			  'payment_code'=>$item->payment_code,
    			  'used_card'=> $item->payment_code==$wallet_payment_code?"":$item->used_card,
    			  'description'=>t($item->transaction_description),
    			  'trans_amount'=>Price_Formatter::formatNumber( ($item->trans_amount*$exchange_rate) ),
    			  'status'=>$item->status,
    			);
    		}    		
    		return $data;
    	}
    	throw new Exception( 'no payment has found' );
    }
    
	public static function getAllReview($order_ids=array())
	{
		$criteria=new CDbCriteria();
		$criteria->select = "order_id,rating";
		$criteria->addInCondition('order_id', (array) $order_ids );		
		
		$dependency = CCacheData::dependency();	
		$model = AR_review::model()->cache(Yii::app()->params->cache, $dependency)->findAll($criteria);
		if($model){
			$data = array();
			foreach ($model as $items) {
				$data[$items->order_id]=$items->rating;
			}
			return $data;
		}
		return false;
	}

	public static function OrderSettingTabs()
	{
		$stmt = "		
		select a.group_name,a.stats_id,
		b.description
		from {{order_settings_tabs}} a
		left join {{order_status}} b
		on a.stats_id = b.stats_id
		where
		b.group_name='order_status'
		";
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
			$data = [];
			foreach ($res as $items) {
				$data[$items['description']] = [
					'group_name'=>$items['group_name'],
					'description'=>$items['description'],
				];
			}
			return $data;
		}
		return false;
	}

	public static function OrderButtons()
	{
		$model = AR_order_settings_buttons::model()->findAll();
		if($model){
			$data = [];
			foreach ($model as $items) {
				$data[$items->id] = [
					'group_name'=>$items->group_name,
					'button_name'=>t($items->button_name),
					'do_actions'=>$items->do_actions,
					'class_name'=>$items->class_name,
					'uuid'=>$items->uuid
				];
			}
			return $data;
		}
		return false;
	}

	public static function OrderGroupButtons()
	{
		$stmt="
		select a.group_name,a.order_type,
		(
		select GROUP_CONCAT(id) from {{order_settings_buttons}}
		where group_name=a.group_name
		and order_type=a.order_type
		) as group_id
		from {{order_settings_buttons}} a
		group by a.group_name,a.order_type
		";
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
			$data = [];
			foreach ($res as $items) {
				$order_type = !empty($items['order_type'])?$items['order_type']:'none';
				$data[$items['group_name']][$order_type] = !empty($items['group_id'])? explode(",",$items['group_id']) :array();
			}
			return $data;
		}
		return false;
	}

	public static function getCreditCard($order_id=0)
	{

		$card_label = [
			'card_number'=>t("Card number"),
			'card_name'=>t("Card name"),
			'expiration_month'=>t("Expiration Month"),
			'expiration_yr'=>t("Expiration year"),
			'cvv'=>t("CVV"),
		];

		$model = AR_ordernew_trans_meta::model()->findAll("order_id=:order_id",[
			':order_id'=>intval($order_id)
		]);
		if($model){
			$data = array();
			foreach ($model as $items) {	
				if($items->meta_value=="card_number"){
					try {
						$items->meta_value = CreditCardWrapper::decryptCard($items->meta_binary);
					} catch (Exception $e) {
						$items->meta_value = t("Description Failed");
					}		
				}

				$label = isset($card_label[$items->meta_name])?$card_label[$items->meta_name]:$items->meta_name;
				$data[$label] = $items->meta_value;
			}
			return $data;			
		}
		throw new Exception( HELPER_NO_RESULTS );
	}

	public static function getCreditCard2($order_id=0)
	{
		
		$model = AR_ordernew_trans_meta::model()->findAll("order_id=:order_id",[
			':order_id'=>intval($order_id)
		]);
		if($model){
			$data = array();
			foreach ($model as $items) {	
				if($items->meta_value=="card_number"){
					try {
						$data[$items->meta_name] = CreditCardWrapper::decryptCard($items->meta_binary);
						$data[$items->meta_name] = CommonUtility::prettyCC($data[$items->meta_name]);
					} catch (Exception $e) {
						$data[$items->meta_name] = t("Description Failed");
					}		
				} else {
					$data[$items->meta_name] = $items->meta_value;
				}							
			}
			return $data;			
		}
		throw new Exception( HELPER_NO_RESULTS );
	}

	public static function getFoodOrders($merchant_id=0,$order_status=array())
	{
		$in = CommonUtility::arrayToQueryParameters($order_status);		
		$stmt="SELECT sum(qty) as total
		FROM {{ordernew_item}}
		WHERE order_id IN (
			select order_id from {{ordernew}}
			where status IN (".$in.")
			and merchant_id = ".q($merchant_id)."
		)
		";						
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
			return $res['total'];
		} else throw new Exception( HELPER_NO_RESULTS );		
	}

	public static function getStatusByGroup($group='')
	{		
		if($group=="active"){
			$status = AOrderSettings::getStatus(array('status_new_order', 'status_prepending_order', 'status_with_delivery_order'));
			$tracking_stats = AR_admin_meta::getMeta([
				'tracking_status_process','tracking_status_ready','tracking_status_in_transit'
			]);						
			$tracking_status_process = isset($tracking_stats['tracking_status_process'])?$tracking_stats['tracking_status_process']['meta_value']:'';
			$tracking_status_ready = isset($tracking_stats['tracking_status_ready'])?$tracking_stats['tracking_status_ready']['meta_value']:'';
			$tracking_status_in_transit = isset($tracking_stats['tracking_status_in_transit'])?$tracking_stats['tracking_status_in_transit']['meta_value']:'';
			array_push($status,$tracking_status_process);
			array_push($status,$tracking_status_ready);
			array_push($status,$tracking_status_in_transit);
			return $status;
		} else if ($group=="history"){
			$status = AOrderSettings::getStatus(array('status_delivered','status_completed','status_delivery_fail'));    	
			return $status;
		} else if ($group=="cancel"){
			$status = AOrderSettings::getStatus(array('status_cancel_order','status_rejection'));    	
			return $status;
		} 
		return false;
	}

	public static function setTotalTax($tax_amount=0)
	{
		if($tax_amount>0){
			self::$tax_total = $tax_amount;
		}
	}

	public static function getTotalTax()
	{
		return floatval(self::$tax_total);
	}

	public static function updateServiceFee($order_uuid='',$transaction_type='')
	{		
			
		$order = COrders::get($order_uuid);		
		$transaction_type = !empty($transaction_type)?$transaction_type:$order->service_code;		
		COrders::getContent($order_uuid,Yii::app()->language);			
		$get_subtotal = COrders::getSubTotal();			
		$sub_total = floatval($get_subtotal['sub_total']);		

		$merchant_id = $order->merchant_id;		
		$merchant = CMerchants::get($merchant_id);
		$merchant_type = $merchant->merchant_type;			

		$service_fee = 0; $small_order_fee = 0;			
		$service_charge = CCheckout::getServiceFeeCharge($merchant_id,$merchant_type,$transaction_type);			
		if($service_charge){
			$charge_type = isset($service_charge['charge_type'])?$service_charge['charge_type']:'';		
			$servicefee = isset($service_charge['service_fee'])?floatval($service_charge['service_fee']):0;
			if($servicefee>0){
				$service_fee = $charge_type=="percentage"? (($servicefee/100) * $sub_total) : $servicefee;			
			}

			$smallorder_fee = isset($service_charge['small_order_fee'])?floatval($service_charge['small_order_fee']):0;
			$small_less_order_based = isset($service_charge['small_less_order_based'])?floatval($service_charge['small_less_order_based']):0;
			if($sub_total<=$small_less_order_based){
				$small_order_fee = $smallorder_fee;
			}
		}		
		
		$order->service_fee = floatval($service_fee);
		$order->small_order_fee = floatval($small_order_fee);
		$order->service_code = $transaction_type;
		if($order->save()){
			return true;
		} 
		return false;
	}

	public static function getOrderModel()
	{
		return COrders::$order;
	}

	public static function setExchangeRate($exchange_rate=0)
	{
		if($exchange_rate>0){
			self::$exchange_rate = $exchange_rate;
		} else {
			self::$exchange_rate = 1;
		}
	}

	public static function getExchangeRate()
	{		
		return self::$exchange_rate>0? floatval(self::$exchange_rate) : 1;
	}

	public static function getTotalItems()
	{
		$item_count = self::itemCount( self::$order_id );
		return $item_count;
	}

	public static function getPaymentTransaction($order_id='', $status='paid' , $transaction_name='payment')
    {
    	$model = AR_ordernew_transaction::model()->find("order_id=:order_id AND status=:status AND transaction_name=:transaction_name",array(
    	  ':order_id'=>intval($order_id),
    	  ':status'=>$status,
    	  ':transaction_name'=>$transaction_name
    	));
    	if($model){
    		return $model;
    	}
    	throw new Exception( 'No invoice payment found' );
    }
}
/*end class*/