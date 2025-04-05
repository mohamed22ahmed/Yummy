<?php
class CClientAddress
{
	
	public static function delete($client_id='', $address_uuid='')
	{
		$model = AR_client_address::model()->find('address_uuid=:address_uuid AND client_id=:client_id', 
		array(':address_uuid'=>$address_uuid,'client_id'=>$client_id)); 
		if($model){
			$model->delete();
			return true;
		}
		throw new Exception( 'Address not found' );
	}
	
	public static function find($client_id='', $address_uuid='')
	{
		$model = AR_client_address::model()->find('address_uuid=:address_uuid AND client_id=:client_id', 
		array(':address_uuid'=>$address_uuid,'client_id'=>$client_id)); 
		if($model){			
			return array(
			   'address_uuid'=>$model->address_uuid,
			   'address' => array(
			     'address1'=>$model->address1,
			     'address2'=>$model->address2,
			     'country'=>$model->country,
			     'country_code'=>$model->country_code,
			     'postal_code'=>$model->postal_code,
			     'formatted_address'=>$model->formatted_address,
				 'company'=>$model->company,
			   ),
			   'latitude'=>$model->latitude,
			   'longitude'=>$model->longitude,
			   'place_id'=>$model->place_id,
			   'reference'=>$model->place_id,
			   'attributes'=>array(
			     'location_name'=>$model->location_name,
			     'delivery_options'=>$model->delivery_options,
			     'delivery_instructions'=>$model->delivery_instructions,
			     'address_label'=>$model->address_label,
			   )
			);
		}
		throw new Exception( 'Address not found' );
	}
	
	public static function getAddress($place_id='',$client_id='')
	{
		$model = AR_client_address::model()->find('place_id=:place_id AND client_id=:client_id', 
		array(':place_id'=>$place_id,'client_id'=>$client_id)); 
		if($model){
			$complete_delivery_address = '';
			if($model->address_format_use==2){
				$complete_delivery_address = "$model->address1 $model->formatted_address";
				if(!empty($model->location_name)){
					$complete_delivery_address.=", $model->location_name";
				}
				if(!empty($model->address2)){
					$complete_delivery_address.=", $model->address2";
				}
				if(!empty($model->postal_code)){
					$complete_delivery_address.=", $model->postal_code";
				}
				if(!empty($model->company)){
					$complete_delivery_address.=", $model->company";
				}
			} else {
				$complete_delivery_address = "$model->address1 $model->formatted_address";
			}
			return array(
			   'address_uuid'=>$model->address_uuid,
			   'address' => array(
			     'address1'=>$model->address1,
			     'address2'=>$model->address2,
			     'country'=>$model->country,
			     'country_code'=>$model->country_code,
			     'postal_code'=>$model->postal_code,
			     'formatted_address'=>$model->formatted_address,
				 'company'=>$model->company,
				 'address_format_use'=>$model->address_format_use,
				 'complete_delivery_address'=>$complete_delivery_address
			   ),
			   'latitude'=>$model->latitude,
			   'longitude'=>$model->longitude,
			   'place_id'=>$model->place_id,
			   'reference'=>$model->place_id,
			   'attributes'=>array(
			     'location_name'=>$model->location_name,
			     'delivery_options'=>$model->delivery_options,
			     'delivery_instructions'=>$model->delivery_instructions,
			     'address_label'=>$model->address_label,
			   )
			);
		} else throw new Exception( 'Address not found' );
	}
		
	public static function getAddresses($client_id='')
	{
		$data = array();
		$model = AR_client_address::model()->findAll('client_id=:client_id order by address_id DESC', 
		array('client_id'=>$client_id)); 
		if($model){
			foreach ($model as $val) {
				$complete_delivery_address = '';
				if($val->address_format_use==2){
					$complete_delivery_address = "$val->address1 $val->formatted_address";
					if(!empty($val->location_name)){
						$complete_delivery_address.=", $val->location_name";
					}
					if(!empty($val->address2)){
						$complete_delivery_address.=", $val->address2";
					}
					if(!empty($val->postal_code)){
						$complete_delivery_address.=", $val->postal_code";
					}
					if(!empty($val->company)){
						$complete_delivery_address.=", $val->company";
					}
				} else {
					$complete_delivery_address = "$val->address1 $val->formatted_address";
				}
				$data[]=array(
				  'address_uuid'=>$val->address_uuid,
				  'address'=>array(
				    'address1'=>$val->address1,
				    'address2'=>$val->address2,
				    'country'=>$val->country,
				    'country_code'=>$val->country_code,
				    'postal_code'=>$val->postal_code,
				    'formatted_address'=>$val->formatted_address,
					'company'=>$val->company,
					'complete_delivery_address'=>$complete_delivery_address
				  ),
				  'latitude'=>$val->latitude,
				  'longitude'=>$val->longitude,
				  'place_id'=>$val->place_id,
				  'reference'=>$val->place_id,
				  'attributes'=>array(
				    'location_name'=>$val->location_name,
				    'delivery_options'=>$val->delivery_options,
				    'delivery_instructions'=>$val->delivery_instructions,
				    'address_label'=>t($val->address_label),
				  )
				);
			}
			return $data;
		}
		return false;
	}

	public static function countAddress($client_id='')
	{
		$criteria=new CDbCriteria();	    
	    $criteria->condition = "client_id=:client_id";
		$criteria->params  = array(
		  ':client_id'=>intval($client_id),		  		  
		);		
		$count = AR_client_address::model()->count($criteria); 		
		return intval($count);
	}
	
	public static function getFirstAddress($client_id='')
	{		
		$criteria=new CDbCriteria();	    
		$criteria->condition = "client_id=:client_id";
		$criteria->params  = array(
		  ':client_id'=>intval($client_id),		  		  
		);		
		$criteria->order = "date_created DESC";
		$model = AR_client_address::model()->find($criteria); 		
		if($model){
			return array(
			   'address_uuid'=>$model->address_uuid,
			   'address' => array(
			     'address1'=>$model->address1,
			     'address2'=>$model->address2,
			     'country'=>$model->country,
			     'country_code'=>$model->country_code,
			     'postal_code'=>$model->postal_code,
			     'formatted_address'=>$model->formatted_address,
			   ),
			   'latitude'=>$model->latitude,
			   'longitude'=>$model->longitude,
			   'place_id'=>$model->place_id,
			   'reference'=>$model->place_id,
			   'attributes'=>array(
			     'location_name'=>$model->location_name,
			     'delivery_options'=>$model->delivery_options,
			     'delivery_instructions'=>$model->delivery_instructions,
			     'address_label'=>$model->address_label,
			   )
			);
		} else throw new Exception( 'Address not found' );
	}
}
/*end class*/