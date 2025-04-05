<?php 
class MapSdk
{
	public static $api_key;
	public static $map_provider;
	public static $map_parameters;
	public static $http_code;
	
	public static $map_api = array(
	  'google'=>array(
	    'place'=>'https://maps.googleapis.com/maps/api/place/autocomplete/json',
	    'place_detail'=>'https://maps.googleapis.com/maps/api/place/details/json',
	    'reverse_geocoding'=>'https://maps.googleapis.com/maps/api/geocode/json',
	    'distance'=>"https://maps.googleapis.com/maps/api/distancematrix/json"
	  ),
	  'mapbox'=>array(
	    'place'=>'https://api.mapbox.com/geocoding/v5/mapbox.places',
	    'place_detail'=>'https://api.mapbox.com/geocoding/v5/mapbox.places',
	    'reverse_geocoding'=>'https://api.mapbox.com/geocoding/v5/mapbox.places',
		'distance'=>"https://api.mapbox.com/directions-matrix/v1/mapbox"
	  ),
	  'yandex'=>array(
		'place'=>"https://suggest-maps.yandex.ru/v1/suggest",
		'place_detail'=>"https://geocode-maps.yandex.ru/1.x",
		'reverse_geocoding'=>"https://geocode-maps.yandex.ru/1.x",
		'distance'=>"https://api.routing.yandex.net/v2/distancematrix"
	  ),
	);
	
	public static function setKeys($keys=array())
	{		
		if(array_key_exists(self::$map_provider,(array)$keys)){
			self::$api_key=$keys[self::$map_provider];
		} 
		//} else  throw new Exception('Invalid google api keys');
	}
	
	public static function setMapParameters($parameters=array())
	{
		self::$map_parameters = $parameters;
	}
	
	private static function getMapParameters($separator='')
	{
		$components='';
		if(is_array(self::$map_parameters) && count(self::$map_parameters)>=1){
			foreach (self::$map_parameters as $key=>$val) {
				if($separator=="="){
					$components.= "&".$key.$separator.$val;
				} else $components.= $key.$separator.$val;				
			}			
		}
		return $components;
	}
	
	public static function getData($api_url='')
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        $result = curl_exec($ch);        
        if (curl_errno($ch)) {		    
		    throw new Exception( curl_error($ch) );
		}
		self::$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);		
		curl_close($ch);
		
		return $result;
	}
		
	/**
	 * find place api
	 *
	 * @param $query  = address or location name
	 * @return array
	 * //'types'=>'geocode', //possible values geocode,address,establishment
	 */
	public static function findPlace($query='')
	{		
				
		$resp = array(); $data=array(); $components = '';		
				
		switch (self::$map_provider) {
			case "google.maps":			
			    $components = self::getMapParameters(":");
			    			    
			    $api_url = self::$map_api['google']['place']."?".http_build_query(array(
				  'key'=>self::$api_key,
				  'input'=>trim($query),			  
				  'components'=>$components 
				));
								
				$result = self::getData($api_url);						
				if (is_string($result) && strlen($result) > 0){
					if ($resp = json_decode($result,true)){				
						$data = MapSdk::parseGoogleResponse($resp,'place');
					}
				}				
			    
				break;
		
			case "mapbox":	
			    $api_url = self::$map_api['mapbox']['place']."/".urlencode($query).".json?".http_build_query(array(			  
				  'access_token'=>self::$api_key			  
				));
				$api_url.= self::getMapParameters("=");				
				
				$result = self::getData($api_url);												
				if (is_string($result) && strlen($result) > 0){
					if ($resp = json_decode($result,true)){				
						$data = MapSdk::parseMapboxResponse($resp,'place');
					}
				}				
			    break;

			case "yandex":
				$params = [
					'apikey'=>self::$api_key,
					'text'=>$query,
					'print_address'=>1
				];
				$api_url = "https://suggest-maps.yandex.ru/v1/suggest?".http_build_query($params);				
				$api_url.= self::getMapParameters("=");					
				$result = self::getData($api_url);		
				if(self::$http_code==200){
					if (is_string($result) && strlen($result) > 0){
						if ($resp = json_decode($result,true)){				
							$data = MapSdk::parseYandexResponse($resp,'place');
						}
					}				
				} else if ( self::$http_code==400 )	{
					throw new Exception ( 'The request is missing a required parameter or an invalid parameter value is specified. The message contains additional information about the error.' ); 
				} else if ( self::$http_code==403 )	{
					throw new Exception ( "The request doesn't contain the apikey parameter or an invalid key was specified." ); 
				} else if ( self::$http_code==429 )	{
					throw new Exception ( "There are too many requests in a short time." ); 
				}
				break;
			    
			default:
				throw new Exception ( 'undefined map provider' ); 
				break;
		}
				
		return $data;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $place_id
	 * @return unknown
	 */
	public static function placeDetails($place_id='',$place_description='')
	{
		if(empty($place_id)){
			throw new Exception("invalid place id parameters");
		}
		
		$resp = array(); $data=array();		
		
		switch (self::$map_provider) {
			case "google.maps":
												
				$api_url = self::$map_api['google']['place_detail']."?".http_build_query(array(
				  'key'=>self::$api_key,
				  'placeid'=>trim($place_id)
				));
								
				$result = self::getData($api_url);		
				
				if (is_string($result) && strlen($result) > 0){
					if ($resp = json_decode($result,true)){				
						$data = MapSdk::parseGoogleResponse($resp,'place_detail');
					}
				}								
				break;
		
			case "mapbox":	
			    	
				$place_id = !empty($place_description)? urlencode($place_description) :$place_id;
			    $api_url = self::$map_api['mapbox']['place_detail']."/$place_id.json?".http_build_query(array(
				  'access_token'=>self::$api_key				  
				));									
				
				$api_url.= self::getMapParameters("=");				
												
				$result = self::getData($api_url);
				
				if (is_string($result) && strlen($result) > 0){
					if ($resp = json_decode($result,true)){							
						$data = self::parseMapboxResponse($resp,'place_detail');
					}
				}					
			    break;

			case "yandex":	
				$language = isset(Yii::app()->params['settings']['yandex_language'])?Yii::app()->params['settings']['yandex_language']:'';		
				$place_id = !empty($place_description)? $place_description :$place_id;				
				$api_url = self::$map_api['yandex']['place_detail']."?".http_build_query(array(
					'apikey'=>self::$api_key,
					'geocode'=>$place_id,
					'format'=>'json',
					'results'=>1,
					'lang'=>$language
				));						
				$result = self::getData($api_url);			
				if(self::$http_code==200){
					if (is_string($result) && strlen($result) > 0){
						if ($resp = json_decode($result,true)){							
							$data = self::parseYandexResponse($resp,'place_detail');
						}
					}							
				} else if ( self::$http_code==400 )	{
					throw new Exception ( 'The request is missing a required parameter or an invalid parameter value is specified. The message contains additional information about the error.' ); 
				} else if ( self::$http_code==403 )	{
					throw new Exception ( "The request doesn't contain the apikey parameter or an invalid key was specified." ); 
				} else if ( self::$http_code==429 )	{
					throw new Exception ( "There are too many requests in a short time." ); 
				}
				break;
			    
			default:
				throw new Exception ( 'undefined map provider' );
				break;
		}			
		
		if(is_array($data) && count($data)>=1){
		   return $data;
		} else throw new Exception ( 'no results' );
	}
	
	public static function parse_address_components($address_components) {
		$street_number = '';
		$street_name = '';
		$city = '';
		$state = '';
		$postal_code = '';
		$country = '';
		
		foreach ($address_components as $component) {
			foreach ($component['types'] as $component_type) {
				if (strpos($component_type, 'street_number') !== false) {
					$street_number = $component['long_name'];
				} elseif ($component_type == 'route') {
					$street_name = $component['long_name'];
				} elseif ($component_type == 'locality') {
					$city = $component['long_name'];
				} elseif ($component_type == 'administrative_area_level_1') {
					$state = $component['short_name'];
				} elseif ($component_type == 'postal_code') {
					$postal_code = $component['long_name'];
				} elseif ($component_type == 'country') {
					$country = $component['long_name'];
				}
			}
		}
		
		return array(
			'street_number' => $street_number,
			'street_name' => $street_name,
			'city' => $city,
			'state' => $state,
			'postal_code' => $postal_code,
			'country' => $country
		);
	}

	public static function parseGoogleResponse($resp=array(),$parse_method='place')
	{
		$data = array();
		$status = isset($resp['status']) ? trim($resp['status']) :'';
		switch ($status) {
			case "OK":
				if($parse_method==="place"){
					if(isset($resp['predictions'])){
						foreach ($resp['predictions'] as $val) {	
							$place_type = '';
							foreach ($val['types'] as $types){
								$place_type.="$types,";
							}							
							$place_type = !empty($place_type)?substr($place_type,0,-1):'';							
							$data[]=array(
							  'id'=>isset($val['place_id'])?$val['place_id']:'',
							  'provider'=>self::$map_provider,
							  'addressLine1'=>$val['structured_formatting']['main_text'],							  
							  'addressLine2'=>isset($val['structured_formatting']['secondary_text'])?$val['structured_formatting']['secondary_text']:'',
							  'place_type'=>$place_type,
							  'description'=>isset($val['description'])?$val['description']:''
							);
						}		
					}				
				} elseif ( $parse_method==="place_detail"){		
																		
					$address_components = $resp['result']['address_components'];										
					$parsed_address = self::parse_address_components($address_components);
					$parsed_address['formatted_address'] = $resp['result']['formatted_address'];					
										
					$address1 = array('street_address','neighborhood','premise','street_number');
					$address2 = array('locality','route','administrative_area_level_1','administrative_area_level_2');
					$country  =  array('country');
					$postal_code = array('postal_code');
					
					$address_out['address1']='';
					$address_out['address2']='';
					$address_out['country']='';
					$address_out['country_code']='';
					$address_out['postal_code']='';
					$address_out['formatted_address'] = $resp['result']['formatted_address'];
					
					$latitude = $resp['result']['geometry']['location']['lat'];
					$longitude = $resp['result']['geometry']['location']['lng'];
					$place_id = $resp['result']['place_id'];
					$reference = isset($resp['result']['reference'])?$resp['result']['reference']:'';
					
					$name = isset($resp['result']['name'])?$resp['result']['name']:'';
					
					if(is_array($address_components) && count($address_components)>=1){
						foreach ($address_components as $val) {									
							foreach ($val['types'] as $types) {														
								if(in_array($types,$address1)){		
									if(!empty($address_out['address1'])){
										$address_out['address1'].= ", ".$val['long_name'];
									} else $address_out['address1'].= $val['long_name'];									
								}
								if(in_array($types,$address2)){
									if(!empty($address_out['address2'])){
										$address_out['address2'].= ", ".$val['long_name'];
									} else $address_out['address2'].= $val['long_name'];									
								}
								if(in_array($types,$country)){
									$address_out['country'].= $val['long_name'];
									$address_out['country_code'].= $val['short_name'];
								}
								if(in_array($types,$postal_code)){
									$address_out['postal_code'].= $val['long_name'];
								}
							}															
						}
												
						if(!empty($name)){
						   $address_out['address1'] = $name;
						}
					}
					
					$place_type = '';
					if(isset($resp['result']['types'])){
						foreach ($resp['result']['types'] as $types){
							$place_type.="$types,";
						}
						$place_type = !empty($place_type)?substr($place_type,0,-1):'';
					}					
										
					$data = array(
					  'address'=>$address_out,
					  'latitude'=>$latitude,
					  'longitude'=>$longitude,
					  'place_id'=>$place_id,
					  'reference'=>$reference,
					  'place_type'=>$place_type,
					  'parsed_address'=>$parsed_address
					);								
				} elseif ( $parse_method==="distance"){						
					$elements = $resp['rows'][0]['elements'][0];
					$elements_status = $resp['rows'][0]['elements'][0]['status'];									
					if($elements_status=="ZERO_RESULTS"){
					   throw new Exception($elements_status);
					}
					if($elements_status=="NOT_FOUND"){
					   throw new Exception($elements_status);
					}
					$distance_raw = isset($elements['distance'])? $elements['distance']['text'] : 0;
					$duration_raw = isset($elements['distance'])? $elements['duration']['text'] : 0;
					
					$distance = (float)$distance_raw;
					$unit = trim(str_replace($distance,"",$distance_raw));					
					
					$duration = (float)$duration_raw;
					$duration_unit = trim(str_replace($duration,"",$duration_raw));					
					
					if($unit=="ft"){
						$distance = 0;
					}
																			
					$data = array(
					  'distance'=>$distance,
					  'unit'=>$unit,
					  'pretty_unit'=>MapSdk::prettyUnit($unit),
					  'duration'=>$duration,
					  'duration_unit'=>$duration_unit
					);
				}
				break;
			
			case "ZERO_RESULTS":	
			    //throw new Exception("zero search found");
			    break;							
		
			case "OVER_QUERY_LIMIT":	
			    throw new Exception("over query limit");
			    break;
			        
			case "REQUEST_DENIED":  				
			    throw new Exception( isset($resp['error_message'])?$resp['error_message']:"request denied" );  
			    break;
			        
			case "INVALID_REQUEST":						     				
				throw new Exception( isset($resp['error_message'])?$resp['error_message']:"input parameter is missing" );  
			    break;					    					   			
			    
			case "UNKNOWN_ERROR":
				throw new Exception("unknow error");
				break;
				
			case "NOT_FOUND":
				throw new Exception("place id not found");
				break;	
				
			default:
				throw new Exception("undefined error");
				break;
		}
		return $data;
	}
	
	public static function parseAddressComponents($data) {		
		$place = isset($data['features']) ? ( isset($data['features'][0])?$data['features'][0]:'' )  : '';
		if(empty($place)){
			return [];
		}
		$addressComponents = explode(',', $place['place_name']);
		
		$streetAddress = trim($addressComponents[0]);
		$streetParts = explode(' ', $streetAddress, 2);
		$streetNumber = $streetParts[0];
		$streetName = isset($streetParts[1]) ? $streetParts[1] : '';

		$city = '';
		$state = '';
		$postalCode = '';
		$country = '';

		 // Loop through remaining address components
		 for ($i = 1; $i < count($addressComponents); $i++) {
			$component = trim($addressComponents[$i]);
			if (is_numeric($component)) {
				// Assuming it's the postal code if it's numeric
				$postalCode = $component;
			} elseif (strlen($component) == 2) {
				// Assuming it's the country if it's a two-letter code
				$country = $component;
			} elseif ($i == count($addressComponents) - 1 && empty($country)) {
				// If it's the last component and country is not set, consider it the country
				$country = $component;
			} elseif (empty($city)) {
				// If city is not set, consider it the city
				$city = $component;
			} elseif (empty($state)) {
				// If state is not set, consider it the state
				$state = $component;
			}
		}

		$formattedAddress = $streetAddress . ', ' . $city . ', ' . $state . ' ' . $postalCode . ', ' . $country;
		
		return array(
			'street_number' => $streetNumber,
			'street_name' => $streetName,
			'city' => $city,
			'state' => $state,
			'postal_code' => $postalCode,
			'country' => $country,
			'formatted_address'=>$formattedAddress
		);

	}

	public static function parseMapboxResponse($resp=array(),$parse_method='place')
	{		
		$data = array();
		
		if($parse_method=="place"){			
			if(is_array($resp) && count($resp)>=1){								
				if(isset($resp['features'])){
					foreach ($resp['features'] as $val) {						

						$country_name = ''; $country_code = '';
						if(isset($val['context'])){
							foreach ($val['context'] as $items_country) {
								if(isset($items_country['short_code'])){									
									$country_name = $items_country['text'];
									$country_code = $items_country['short_code'];
								}
							}
						}
						$data[]=array(
						  'id'=>$val['id'],
						  'provider'=>self::$map_provider,
						  'addressLine1'=>$val['text'],
						  'addressLine2'=>$val['place_name'],
						  'latitude'=>$val['center'][1],
						  'longitude'=>$val['center'][0],
						  'place_type'=>$val['place_type'][0],
						  'description'=>$val['place_name'],
						  'country'=>$country_name,
						  'country_code'=>$country_code
						);
					}
				} else {
					$error = self::$http_code." ";
					$error.= isset($resp['message'])?$resp['message']:'';
					throw new Exception( $error );
				}
			}
		} elseif ($parse_method=="place_detail"){
			
			
			$address1 = array('poi','address','neighborhood');
			$address2 = array('locality','place','district','region');
			$country  =  array('country');
			$postal_code = array('postcode');
			
			$address_out['address1']='';
			$address_out['address2']='';
			$address_out['country']='';
			$address_out['postal_code']='';
			$address_out['formatted_address'] = '';
			$address_out['country_code']='';
			
			$parsed_address = [];
			
			if(is_array($resp) && count($resp)>=1){
				if(isset($resp['features'])){								
					$parsed_address = self::parseAddressComponents($resp);					

					foreach ($resp['features'] as $val) {		
																				
						$address_out['formatted_address'] =isset($val['place_name'])?$val['place_name']:'';
						$address_out['address1'] =  isset($val['text'])?$val['text']:'';
						
						if(isset($val['context'])){
							if(is_array($val['context']) && count($val['context'])>=1){
								foreach ($val['context'] as $context) {									
									$id = isset($context['id'])? substr($context['id'],0, strpos($context['id'],".") ) :'';
									$text=isset($context['text'])?$context['text']:'';									
									
									if (is_string($address_out['address1']) && strlen($address_out['address1']) <= 0){
									if(in_array($id,$address1)){		
										if(!empty($address_out['address1'])){
											$address_out['address1'].= ", ".$text;
										} else $address_out['address1'].= $text;
									}
									}
									
									if(in_array($id,$address2)){
										if(!empty($address_out['address2'])){
											$address_out['address2'].= ", ".$text;
										} else $address_out['address2'].= $text;
									}
									if(in_array($id,$country)){
										$address_out['country'].= $text;
									}
									if(in_array($id,$postal_code)){
										$address_out['postal_code'].= $text;
									}
									
								}
							}
														
						}
						
						$place_type = '';
						if(isset($val['place_type'])){
							foreach ($val['place_type'] as $types){
								$place_type.="$types,";
							}
							$place_type = !empty($place_type)?substr($place_type,0,-1):'';
						}					
						
						$data=array(
						  'address'=>$address_out,
						  'place_id'=>$val['id'],
						  'reference'=>$val['id'],						  						  
						  'latitude'=>$val['center'][1],
						  'longitude'=>$val['center'][0],
						  'place_type'=>$place_type,
						  'country'=>'',
						  'country_code'=>'',
						  'parsed_address'=>$parsed_address
						);
					}
				} else {
					$error = self::$http_code." ";
				    $error.= isset($resp['message'])?$resp['message']:'';
				    throw new Exception( $error );
				}
			} 
		} elseif ($parse_method=="distance"){			
			$code = isset($resp['code'])?$resp['code']:'';						
			if($code=="Ok"){				
				$distances = isset($resp['distances'])?$resp['distances']:'';
				$durations = isset($resp['durations'])?$resp['durations']:'';
				if(is_array($distances) && count($distances)>=1){
					$distance = isset($distances[0][1])?$distances[0][1]:0;					
					$unit = self::$map_parameters['unit'];
					$convertion = $unit=="mi"?0.000621371:0.001;
					$distance = $distance*$convertion;					

					$duration_unit = 'min'; $duration = '';
					$duration_resp = isset($durations[0][1])?$durations[0][1]:0;						
					$duration_resp = CommonUtility::seconds2human($duration_resp);					
					if($duration_resp['hour']>0){
						$duration = $duration_resp['hour'].":".$duration_resp['minute'];
					} else $duration = $duration_resp['minute'];

					$data = [
						'distance'=>round( floatval($distance),2),
						'unit'=>$unit,
						'pretty_unit'=>MapSdk::prettyUnit($unit),
						'duration'=>$duration,
						'duration_unit'=>$duration_unit
					];						
				} else throw new Exception( "Route not available" );				
			} else if ($code=="NoRoute"){
				throw new Exception( "Route not available" );
			} else {
				throw new Exception( "Route returns null" );
			}
		}		
		return $data;
	}
	
	public static function prettyUnit($unit='')
	{
		switch ($unit) {
			case "M":		
			case "mi":	
			    return t("miles");
				break;
				
			case "K":			
			case "km":	
			    return t("km");
				break;	
				
			case "m":			
			    return t("m");
				break;		
				
			case "ft":			
			    return t("ft");
				break;			
		
			default:
				return $unit;
				break;
		}
	}
	
	public static function reverseGeocoding($lat='', $lng='')
	{
		if(empty($lat)){
			throw new Exception("invalid latitude parameters");
		}
		if(empty($lng)){
			throw new Exception("invalid longitude parameters");
		}

		$data = array();
		
		switch (self::$map_provider) {
			case "google.maps":	
			   $api_url = self::$map_api['google']['reverse_geocoding']."?".http_build_query(array(
			      'latlng'=>"$lat,$lng",
			      'location_type'=>"ROOFTOP",
				  'key'=>self::$api_key,			  
			   ));					   			   
			   $result = self::getData($api_url);
			   if (is_string($result) && strlen($result) > 0){
				   if ($resp = json_decode($result,true)){					   	   
					   $new_resp = array(
					    'error_message'=>isset($resp['error_message'])?$resp['error_message']:'',
					    'result'=>isset($resp['results'][0])?$resp['results'][0]:array(),
					    'status'=>isset($resp['status'])?$resp['status']:''
					   );					   
					   $data = MapSdk::parseGoogleResponse($new_resp,'place_detail');					   
				   }
			   }	
			break;
			
			case "mapbox":
			   $api_url = self::$map_api['mapbox']['reverse_geocoding']."/$lng,$lat".".json?".http_build_query(array(
			      'access_token'=>self::$api_key	
			   ));
			   $api_url.= self::getMapParameters("=");
			   $result = self::getData($api_url);
			   if (is_string($result) && strlen($result) > 0){
				   if ($resp = json_decode($result,true)){				   	   
					   $data = MapSdk::parseMapboxResponse($resp,'place_detail');					   
				   }
			   }	
			break;

			case "yandex":
				$language = isset(Yii::app()->params['settings']['yandex_language'])?Yii::app()->params['settings']['yandex_language']:'';
				$api_url = self::$map_api['yandex']['reverse_geocoding']."/?".http_build_query([
					'apikey'=>self::$api_key,
					'geocode'=>"$lng,$lat",
					'results'=>1,
					'lang'=>$language,
					'format'=>"json",
				]);				
				$result = self::getData($api_url);
				if(self::$http_code==200){
					if (is_string($result) && strlen($result) > 0){
						if ($resp = json_decode($result,true)){									
							$data = MapSdk::parseYandexResponse($resp,'place_detail');
						}
					}							
				} else if ( self::$http_code==400 )	{
					throw new Exception ( 'The request is missing a required parameter or an invalid parameter value is specified. The message contains additional information about the error.' ); 
				} else if ( self::$http_code==403 )	{
					throw new Exception ( "The request doesn't contain the apikey parameter or an invalid key was specified." ); 
				} else if ( self::$http_code==429 )	{
					throw new Exception ( "There are too many requests in a short time." ); 
				}								
				break;
		}
				
		return $data;
	}

	
	/*
	how to use
	
	MapSdk::$map_provider = Yii::app()->params['settings']['map_provider'];		   
    MapSdk::setKeys(array(
     'google.maps'=>Yii::app()->params['settings']['google_geo_api_key'],
     'mapbox'=>Yii::app()->params['settings']['mapbox_access_token'],
    ));
    		    		    		    		    		  
    MapSdk::setMapParameters(array(
      'from_lat'=>$merchant_lat,
      'from_lng'=>$merchant_lng,
      'to_lat'=>$customer_lat,
      'to_lng'=>$customer_lng,
      'place_id'=>$place_id,
      'unit'=>$unit,
      'mode'=>'driving'
    ));*/
		    
	public static function distance()
	{
		$data=array(); $parameters = self::$map_parameters;		
		
		if(is_array($parameters) && count($parameters)>=1){
						
			switch (MapSdk::$map_provider) {
				case "google.maps":					
					$params = array(
					  'origins'=>$parameters['from_lat'].",".$parameters['from_lng'],
					  'units'=>$parameters['unit']=="mi"?"imperial":"metric",
					  'key'=>MapSdk::$api_key,
					);
					if(isset($parameters['place_id'])){						
						$params['destinations']="place_id:".$parameters['place_id'];
					} else $params['destinations']=$parameters['to_lat'].",".$parameters['to_lng'];
					
					$api_url = MapSdk::$map_api['google']['distance']."?".http_build_query($params);					
										
					$result = self::getData($api_url);											
					if (is_string($result) && strlen($result) > 0){
						if ($resp = json_decode($result,true)){								
							$data = MapSdk::parseGoogleResponse($resp,'distance');
						}
					}				
				
					break;
			
				case "mapbox":					
					$params = [						
						'annotations'=>"distance,duration",
						'access_token'=>MapSdk::$api_key
					];					
					$coordinates = $parameters['from_lng'].",".$parameters['from_lat'];
					$coordinates.=";";
					$coordinates.= $parameters['to_lng'].",".$parameters['to_lat'];
					$api_url = MapSdk::$map_api['mapbox']['distance']."/".$parameters['mode']."/".$coordinates."?".http_build_query($params);					
					$result = self::getData($api_url);
					if (is_string($result) && strlen($result) > 0){
						if ($resp = json_decode($result,true)){								
							$data = MapSdk::parseMapboxResponse($resp,'distance');
						}
					}									
					break;
						
				default:
					break;
			}
		} 
		
		if(is_array($data) && count($data)>=1){
		   return $data;
		} else throw new Exception ( 'no results' );
	}

	public static function parseYandexResponse($resp=array(),$parse_method='place')
	{
		$data = array();
		if($parse_method=="place"){	
			$results = isset($resp['results'])?$resp['results']:'';						
			if(is_array($results) && count($results)>=1){	
				foreach ($results as $items) {
					$title = isset($items['title'])? (isset($items['title']['text'])?$items['title']['text']:'') :'';
					$subtitle = isset($items['subtitle'])?$items['subtitle']['text']:'';
					$address = isset($items['address'])? (isset($items['address']['formatted_address'])?$items['address']['formatted_address']:'') :'';					
					$component = isset($items['address'])? (isset($items['address']['component'])?$items['address']['component']:'') :'';					

					$country = ''; $country_code = '';

					if(is_array($component) && count($component)>=1){
						foreach ($component as $component_items) {							
							if(in_array("COUNTRY",$component_items['kind'])){
								$country = $component_items['name'];
							}
						}
					}
					$data[] = [
						'id'=>$address,
						'provider'=>self::$map_provider,
						'addressLine1'=>$title,
						'addressLine2'=>$subtitle,
						'latitude'=>'',
						'longitude'=>'',
						'description'=>$address,
						'country'=>$country,
						'country_code'=>$country_code
					];				
				}				
			} else {

			}
		} else if ( $parse_method=="place_detail"){
			$response = isset($resp['response'])? (isset($resp['response']['GeoObjectCollection'])?$resp['response']['GeoObjectCollection']:'') :'';									
			if(is_array($response) && count($response)>=1){
				$metaDataProperty = isset($response['metaDataProperty'])? (isset($response['metaDataProperty']['GeocoderResponseMetaData'])?$response['metaDataProperty']['GeocoderResponseMetaData']:'') :'';												
				$found = isset($metaDataProperty['found'])? ($metaDataProperty['found']==1?true:false) :false;
				if($found){
					$featureMember = isset($response['featureMember'])? (isset($response['featureMember'][0])?$response['featureMember'][0]['GeoObject']:'') :'';									
					$GeocoderMetaData = isset($featureMember['metaDataProperty'])?$featureMember['metaDataProperty']['GeocoderMetaData']:'';
					$Address = isset($GeocoderMetaData['Address'])?$GeocoderMetaData['Address']:'';
					$place_type = isset($GeocoderMetaData['kind'])?$GeocoderMetaData['kind']:'';
					
					$address_line1 = isset($featureMember['name'])?$featureMember['name']:'';
					$address_line2 = isset($featureMember['description'])?$featureMember['description']:'';
					$Point = isset($featureMember['Point'])?$featureMember['Point']['pos']:'';
					$coordinates = !empty($Point)?explode(" ",$Point):'';
					$longitude = isset($coordinates[0])?$coordinates[0]:'';
					$latitude = isset($coordinates[1])?$coordinates[1]:'';

					$formatted_address = isset($Address['formatted'])?$Address['formatted']:'';					

					$Components = isset($Address['Components'])?$Address['Components']:'';					
					$country_code = isset($Address['country_code'])?$Address['country_code']:'';
					$streetNumber = ''; $streetName = ''; $city = ''; $state = ''; $country = ''; $postal_code = '';
					if(is_array($Components) && count($Components)>=1){
						foreach ($Components as $component) {
							switch ($component['kind']) {
								case 'street':									
									$streetName = $component['name'];
									break;
								case 'locality':									
									$city = $component['name'];
									break;
								case 'district':									
									$state = $component['name'];
									break;
								case 'country':									
									$country = $component['name'];
									break;
								case 'region':									
									$postal_code = $component['name'];
									break;
								case 'district':									
								case 'house':									
								case 'entrance':
								case 'level':
								case 'apartment':
									$postal_code = $component['name'];
									break;
							}
						}
					}	
					
					$address_out = [
						'address1'=>$address_line1,
						'address2'=>$address_line2,
						'country'=>$country,
						'country_code'=>$country_code,
						'postal_code'=>$postal_code,
						'formatted_address'=>$formatted_address
					];
					$parsed_address = [
						'street_number'=>$streetNumber,
						'street_name'=>$streetName,
						'city'=>$city,
						'state'=>$state,
						'postal_code'=>$postal_code,
						'country'=>$country,
						'formatted_address'=>$formatted_address
					];					
					$data = [
						'address'=>$address_out,
						'latitude'=>$latitude,
						'longitude'=>$longitude,
						'place_id'=>$address_line1,
						'reference'=>$address_line1,
						'place_type'=>$place_type,
						'parsed_address'=>$parsed_address
					];					
				} else {

				}
			} else {

			}
		}
		return $data;
	}

    public static function getOrSaveCachedDistance()
    {

        $data=array(); $parameters = self::$map_parameters;



        if(is_array($parameters) && count($parameters)>=1){

            $criteria = new CDbCriteria();
            $criteria->condition = 'client_id=:client_id AND from_lat=:from_lat AND from_lng=:from_lng AND to_lat=:to_lat AND to_lng=:to_lng';
            $criteria->params = array(
                ':client_id' => Yii::app()->user->id,
                ':from_lat' => $parameters['from_lat'],
                ':from_lng' => $parameters['from_lng'],
                ':to_lat' => $parameters['to_lat'],
                ':to_lng' => $parameters['to_lng'],
            );

            $cache = AR_distance_cache::model()->find($criteria);

            if ($cache) {
                return array(
                    'found' => true,
                    'distance' => $cache->distance_covered,
                    'cached_date' => $cache->cached_date
                );
            } else {

                if ($resp = MapSdk::distance()) {
                    $distance_covered = $resp['distance'];

                    if (isset($parameters['place_id']) && !empty($parameters['place_id'])) {
                        $place_id = $parameters['place_id'];
                    } else {
                        $place_id = null;
                    }

                    if (isset($parameters['unit']) && !empty($parameters['unit'])) {
                        $unit = $parameters['unit'];
                    } else {
                        $unit = null;
                    }

                    if (isset($parameters['mode']) && !empty($parameters['mode'])) {
                        $mode = $parameters['mode'];
                    } else {
                        $mode = null;
                    }

                    // Save the new cache entry
                    $cache = new AR_distance_cache();
                    $cache->client_id = Yii::app()->user->id;
                    $cache->from_lat = $parameters['from_lat'];
                    $cache->from_lng = $parameters['from_lng'];
                    $cache->to_lat = $parameters['to_lat'];
                    $cache->to_lng = $parameters['to_lng'];
                    $cache->place_id = $place_id;
                    $cache->unit = $unit;
                    $cache->mode = $mode;
                    $cache->distance_covered = $distance_covered;
                    $cache->cached_date = new CDbExpression('NOW()');
                    $cache->save();

                    return array(
                        'found' => false,
                        'distance' => $distance_covered,
                        'merchant_lat' => $parameters['from_lat'],
                        'merchant_lng' => $parameters['from_lng']
                    );
                }
            }

        }
    }
	
}
/*end class*/