<?php
class CMerchantListingV1
{
	public static function getMerchant($merchant_id='')
	{
		$dependency = new CDbCacheDependency('SELECT MAX(date_modified) FROM {{merchant}}');
		$model = AR_merchant::model()->cache(Yii::app()->params->cache, $dependency)->find('merchant_id=:merchant_id', 
		array(':merchant_id'=>$merchant_id)); 
		if($model){
			return $model;
		}
		throw new Exception( 'merchant not found' );
	}

	public static function getMerchantBySlug($restaurant_slug='')
	{
		$dependency = CCacheData::dependency();
		$model = AR_merchant::model()->cache(Yii::app()->params->cache, $dependency)->find('restaurant_slug=:restaurant_slug', 
		array(':restaurant_slug'=>$restaurant_slug)); 
		if($model){
			return $model;
		}
		throw new Exception( 'merchant not found' );
	}
	
	public static function getMerchantInfo($slug_name = '', $lang = '')
	{
		try {
			$stmt = "
			SELECT a.merchant_id, a.merchant_uuid,
				a.restaurant_name,
				a.description as original_description,
				c.meta_value1 as description,
				a.short_description as original_short_description,
				d.meta_value1 as short_description,
				a.logo, a.path,    
				a.header_image, a.path2,    
				a.address,
				a.restaurant_slug, 
				a.latitude, a.lontitude, a.distance_unit,
				a.contact_phone,
				b.review_count,
				b.ratings,
				a.city_id,
				a.ar_restaurant_name,
				a.type,
				a.ar_address,
				a.view_type,
				IFNULL((
					SELECT GROUP_CONCAT(DISTINCT cuisine_name SEPARATOR ';')
					FROM {{view_cuisine}}
					WHERE language=" . q($lang) . "
					AND cuisine_id IN (
						SELECT cuisine_id FROM {{cuisine_merchant}}
						WHERE merchant_id = a.merchant_id
					)
				),'') as cuisine_name
			FROM {{merchant}} a
			LEFT JOIN {{view_ratings}} b
				ON a.merchant_id = b.merchant_id
			LEFT JOIN (
				SELECT merchant_id, meta_value1 
				FROM {{merchant_meta}} 
				WHERE meta_value=" . q($lang) . " 
				AND meta_name='merchant_about_trans'
			) c
				ON a.merchant_id = c.merchant_id
			LEFT JOIN (
				SELECT merchant_id, meta_value1 
				FROM {{merchant_meta}} 
				WHERE meta_value=" . q($lang) . "
				AND meta_name='merchant_short_about_trans'
			) d
				ON a.merchant_id = d.merchant_id
			WHERE restaurant_slug=" . q($slug_name) . "
			  AND a.status='active' AND a.is_ready ='2' 
			LIMIT 0,1
			";
			
			if ($res = CCacheData::queryRow($stmt)) {
				$val2 = $res;
				error_log("vaaaaaaaaaal2");
				error_log(print_r($val2, true));
				
				// Process cuisine: build a formatted string and array.
				$cuisine = '';
				$cuisine_name = explode(";", $res['cuisine_name']);
				if (is_array($cuisine_name) && count($cuisine_name) >= 1) {
					foreach ($cuisine_name as $name) {
						$cuisine .= "&#8226; $name ";
					}
				}
				unset($val2['cuisine_name']);
				
				// Language-specific assignment.
				if ($lang === 'ar') {
					// Use Arabic values if language is Arabic.
					$val2['restaurant_name'] = Yii::app()->input->xssClean($res['ar_restaurant_name']);
					$val2['merchant_address'] = Yii::app()->input->xssClean($res['ar_address']);
					$val2['address'] = Yii::app()->input->xssClean($res['ar_address']);
				} else {
					// Use default values for other languages.
					$val2['restaurant_name'] = Yii::app()->input->xssClean($res['restaurant_name']);
					// (Optionally you could use $res['address'] for these fields if preferred)
					$val2['merchant_address'] = Yii::app()->input->xssClean($res['ar_address']);
					$val2['address'] = Yii::app()->input->xssClean($res['ar_address']);
				}
				
				// Common assignments.
				$val2['url'] = Yii::app()->createAbsoluteUrl($val2['restaurant_slug']);
				$val2['cuisine'] = (array)$cuisine_name;
				$val2['cuisine2'] = $cuisine;
				$val2['url_logo'] = CMedia::getImage($res['logo'], $res['path'], "@2x",
					CommonUtility::getPlaceholderPhoto('merchant_logo'));
				$val2['url_header'] = CMedia::getImage($res['header_image'], $res['path2'], "",
					CommonUtility::getPlaceholderPhoto('logo'));
				$val2['has_header'] = !empty($res['header_image']) ? true : false;
				$val2['latitude'] = $res['latitude'];
				$val2['lontitude'] = $res['lontitude'];
				$val2['delivery_estimation'] = '';
				$val2['description'] = empty($val2['description']) ? $val2['original_description'] : $val2['description'];
				$val2['short_description'] = empty($val2['short_description']) ? $val2['original_short_description'] : $val2['short_description'];
				// Preserve view_type from the query.
				$val2['view_type'] = $res['view_type'];
				
				// Clean up temporary fields.
				unset($val2['original_description']);
				unset($val2['original_short_description']);
				
				return $val2;
			} else {
				// No results were found.
				throw new Exception('no results');
			}
		} catch (Exception $e) {
			error_log("Error in getMerchantInfo: " . $e->getMessage());
			throw $e; // Optionally rethrow to let a higher-level handler process the error.
		}
	
	}


	public static function getGallery($merchant_id='')
	{		
		$criteria=new CDbCriteria;
		$criteria->condition = "merchant_id=:merchant_id AND meta_name=:meta_name";		    
		$criteria->params  = array(
		  ':merchant_id'=>intval($merchant_id),
		  ':meta_name'=>'merchant_gallery'
		);
		$criteria->order='meta_id ASC';
		$model = AR_merchant_meta::model()->findAll($criteria); 
		if($model){
			$data = array();
			foreach ($model as $val) {
				$data[] = array(
				  'thumbnail' =>CMedia::getImage($val['meta_value'],$val['meta_value1'],
				    Yii::app()->params->size_image_thumbnail,CommonUtility::getPlaceholderPhoto('gallery')),
				  'image_url' =>CMedia::getImage($val['meta_value'],$val['meta_value1'],
				    Yii::app()->params->size_image_medium,CommonUtility::getPlaceholderPhoto('gallery'))  
				);
			}
			return $data;
		}
		return false;
	}

	public static function openingHours($merchant_id='')
	{
		$stmt = "
		SELECT day,status,start_time,end_time,
		start_time_pm,end_time_pm,custom_text
		FROM {{opening_hours}}
		WHERE merchant_id=".q($merchant_id)."		
		AND status='open'	
		ORDER BY day_of_week ASC
		";				
		if($res = CCacheData::queryAll($stmt) ){	
			$data = []; $days = [];
			foreach ($res as $item) {										
				$item['value']=  in_array($item['day'],(array)$days)?'':t($item['day']);
				$days[] = $item['day'];				
				$item['start_time'] = Date_Formatter::Time($item['start_time']);
				$item['end_time'] = Date_Formatter::Time($item['end_time']);
				$item['start_time_pm'] = Date_Formatter::Time($item['start_time_pm']);
				$item['end_time_pm'] = Date_Formatter::Time($item['end_time_pm']);
				$data[]	= $item;
			}			
			return $data;
		}
		return false;
	}
	
	public static function staticMapLocation($maps_credentials=array(),
	   $lat='', $lng='',$size='500x300',$icon='',$zoom=13,$scale=2,$format='png8')
	{
		$link = '';		
		if($maps_credentials){
			$api_keys = $maps_credentials['api_keys'];
			if($maps_credentials['map_provider']=="google.maps"){
				$link = "https://maps.googleapis.com/maps/api/staticmap";
				$link.= "?".http_build_query(array(
				  'center'=>"$lat,$lng",
				  'size'=>$size,
				  'zoom'=>$zoom,
				  'scale'=>$scale,
				  'format'=>$format,
				  'markers'=>"icon:$icon|$lat,$lng",
				  'key'=>$api_keys,				  
				));
			} else if ( $maps_credentials['map_provider']=="mapbox"  ) {
				$link = "https://api.mapbox.com/styles/v1/mapbox/streets-v12/static";
				$link.="/pin-s-l+000";
				$link.="($lng,$lat)/$lng,$lat,14/$size";
				$link.= "?".http_build_query(array(				
					'access_token'=>$api_keys,				  
				));
			}			
			return $link;
		}
		return false;
	}
	
    public static function mapDirection($maps_credentials=array(),$lat='', $lng='')
	{
		$link = '';
		if($maps_credentials){
			// if($maps_credentials['map_provider']=="google.maps"){
			// 	$link = "https://www.google.com/maps/dir/?api=1&destination=$lat,$lng";
			// } else if ( $maps_credentials['map_provider']=="mapbox"  ) {
			// 	$link = "https://www.google.com/maps/dir/?api=1&destination=$lat,$lng";
			// }
			$link = "https://www.google.com/maps/dir/?api=1&destination=$lat,$lng";
			return $link;
		}
		return false;
	}

	public static function openHours($merchant_id='', $interval="20 mins")
	{
		if (!preg_match('/\d/', $interval)) {
			$interval="20 mins";
		}
		$today = date('Y-m-d'); $order_by_days = ''; $daylist = array();
		$yesterday = date('Y-m-d', strtotime($today. " -1 days"));	
		$tomorrow = date('Y-m-d', strtotime($today. " +1 days"));		
		$current_time = date("Hi");
		$time_now = date("H:i",strtotime("+".intval($interval)." minutes"));
		$day_of_week = date("N");		

		for($i=1; $i<=7; $i++){			
			$days = date('l', strtotime($yesterday. " +$i days"));			
			$days = strtolower($days);	
			$order_by_days.=q($days).",";	
			$daylist[$days]= date('Y-m-d', strtotime($yesterday. " +$i days"));	 
		}

		$order_by_days = substr($order_by_days,0,-1);
		$stmt="
		SELECT day,start_time,end_time
		FROM {{opening_hours}}
		WHERE merchant_id=".q($merchant_id)."
		AND status='open'			
		ORDER BY FIELD(day, $order_by_days),start_time ASC;	
		";
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){			
			$data = array(); $times = array();
			foreach ($res as $val) {	
								
				$start_time = date("Hi",strtotime($val['start_time']));		
				$item_start_time = $val['start_time'];
				
				$date = isset($daylist[$val['day']])?$daylist[$val['day']]:'';				
				//$name = Date_Formatter::date($date,"EEE, MMM dd");
				$name = Date_Formatter::date($date,"eee dd MMM",true);
							
				if($today==$date){
					$name = t("Today").", $name";
					if($current_time>$start_time){								
						$item_start_time = self::blockMinutesRound($time_now, intval($interval) ); 						
					}
				} elseif ($tomorrow==$date){
					$name = t("Tomorrow").", $name";
					//$item_start_time = date("H:i",strtotime($item_start_time." +".intval($interval)." minutes"));
				} else {
					//$item_start_time = date("H:i",strtotime($item_start_time." +".intval($interval)." minutes"));
				}

				$end_time = $val['end_time'];						
				$end_time = date("H:i",strtotime($end_time." -".intval($interval)." minutes"));

				$time = self::createTimeRange($item_start_time,$end_time,$interval);
				if(array_key_exists($date,(array)$times)){
					if(isset($times[$date][0])){
						$lastIndex = count($times[$date][0]);
						foreach ($time as $key => $value) {
							$times[$date][0][$lastIndex + $key] = $value;
						}
					}
				} else $times[$date][]=$time;
								
				if(is_array($time) && count($time)>=1){
				$data[$date] = array(
				  'name'=>$name,
				  'value'=>$date,				  
				  'data'=>$val,				  
				);
				}
			} //endfor	

			//die();

			$_times = array();
			if(is_array($times) && count($times)>=1){
				foreach ($times as $key=>$item) {				
					$merge = array();
					for ($x = 0; $x <= count($item)-1; $x++) {				
						$merge += $item[$x];
					}
					$_times[$key] = $merge;
				}			
			}
			
			return array(
			  'dates'=>$data,
			  'time_ranges'=>$_times
			);
		}
		return false;
	}
	
    public static function createTimeRange($start, $end, $interval = '30 mins', $format = '24') {
	    $startTime = strtotime($start); 
	    $startEnd = strtotime($start); 
	    $endTime   = strtotime($end);
	    $returnTimeFormat = ($format == '12')?'g:i:s A':'G:i:s';	    
	
	    $current   = time(); 
	    $addTime   = strtotime('+'.$interval, $current); 
	    $diff      = $addTime - $current;
	
	    $times = array(); 	    
	    while ($startTime < $endTime) { 	 
	    	$start_time =  date("H:i", $startTime);   		    		    		    	
	    	$startEnd  += $diff; 
	    	$start_end =  date("H:i", $startEnd);  
	        	        
	        $pretty_time = Date_Formatter::Time($startTime,"hh:mm a") . " - " . Date_Formatter::Time($startEnd,"hh:mm a"); 
	        
	        $times[] = array(
	          'start_time'=>date($returnTimeFormat, $startTime),
	          'end_time'=>date($returnTimeFormat, $startEnd),
	          'pretty_time'=>$pretty_time
	        );
	        $startTime += $diff; 
	    } 
	    
	    $start_time =  date("H:i", $startTime);  	       
	    return $times; 
	}    
	
	public static function blockMinutesRound($hour, $minutes = '5', $format = "H:i") {
	   $seconds = strtotime($hour);
	   $minutes=$minutes<=0?5:$minutes;
	   $rounded = round($seconds / ($minutes * 60)) * ($minutes * 60);
	   return date($format, $rounded);
	}

	public static function roundToNearestMinuteInterval(\DateTime $dateTime, $minuteInterval = 10)
	{
	    return $dateTime->setTime(
	        $dateTime->format('H'),
	        round($dateTime->format('i') / $minuteInterval) * $minuteInterval,
	        0
	    );
	}	
	
	public static function getDistanceExp($filter=array())
	{
	    $distance_exp=3959;
		if ($filter['unit']=="km"){
			$distance_exp=6371;
		}
		return $distance_exp;			
	}
	
	public static function preFilter($filter=array())
	{				
		$and = '';				

		if(isset($filter['filters'])){
			if(!is_array($filter['filters']) && count((array)$filter['filters'])<=1){
				return $and;
			}
		}

		if(isset($filter['filters'])){
			foreach ($filter['filters'] as $filter_by=>$val) {
				switch ($filter_by) {
					
					case "transaction_type":
						    $and.="\n\n";
							$and.= "
							AND a.merchant_id IN (
							 select merchant_id from {{merchant_meta}}
							 where merchant_id = a.merchant_id
							 and meta_name='services' 
							 and meta_value=".q($val)."
							)
							";
						break;
						
					case "sortby":
						if($val=="sort_most_popular"){
							$and.="\n\n";
							$and.= "
							AND a.merchant_id IN (
							 select merchant_id from {{merchant_meta}}
							 where merchant_id = a.merchant_id
							 and meta_name='featured' 
							 and meta_value='popular'
							)
							";
						} elseif ($val=="sort_rating"){
							$and.="\n\n";
							$and.= "
							AND a.merchant_id IN (
							  select merchant_id from {{review}}
							  where merchant_id = a.merchant_id
							  and status = 'publish'
							)
							";
						} elseif ( $val=="sort_promo"){	
							$date_now = isset($filter['date_now'])?$filter['date_now']:'';
							$and.="\n\n";
							$and.= "
							AND a.merchant_id IN (
							  select merchant_id from {{offers}}
							  where merchant_id = a.merchant_id
							  and status = 'publish'
							  and ".q($date_now)." >= valid_from and ".q($date_now)." <= valid_to
							)
							";
						} elseif ($val=="sort_free_delivery"){
							$and.="\n\n";
							$and.="
							AND a.merchant_id IN (
							  select merchant_id from {{option}}
							  where merchant_id = a.merchant_id
							  and option_name='free_delivery_on_first_order'
							  and option_value=1
							)
							";
						}
						break;
				
					case "price_range":				
					     if(!empty($val)){
							 $based_price = str_pad(9, intval($val) ,9);	
							 $and.="\n\n";
							 $and.=" AND a.merchant_id IN (
							  select merchant_id from {{item_relationship_size}}
							  where price <=".q($based_price)."
							  and available = 1
							 )
						    ";
					     }
						break;
					
					case "cuisine":		
					    if(is_array($val) && count($val)>=1){
					    	$in = '';
					    	foreach ($val as $cuisine_id) {
					    		$in.=q(intval($cuisine_id)).",";
					    	}
					    	$in = substr($in,0,-1);
					    	if(!empty($in)){
								$and.="\n\n";
								$and.=" AND a.merchant_id IN (
								 select merchant_id from {{cuisine_merchant}}
								 where merchant_id = a.merchant_id				 
								 and cuisine_id IN ($in)
							   )";		 
						   }
					    }
					    break;
							
					case "max_delivery_fee":    
					    $max_delivery_fee = floatval($val);
					    if($max_delivery_fee>0){
					    	$and.="\n\n";
					    	$and.="
					    	AND a.merchant_id IN (
					    	  select merchant_id
					    	  from {{shipping_rate}}
					    	  where distance_price between 1 and ".q($max_delivery_fee)."
					    	  and service_code='delivery'
					    	  and charge_type  = (
					    	    select option_value  
					    	    from {{option}}	
					    	    where merchant_id = a.merchant_id
					    	    and option_name='merchant_delivery_charges_type'					    	    
					    	  )
					    	)
					    	";
					    }
					    break;
					    
					case "rating":    
					    $rating = intval($val);					    
					    if($rating>0){
					    	$and.="\n\n";
							$and.= "
							AND a.merchant_id IN (
							  select merchant_id from {{view_ratings}}
							  where merchant_id = a.merchant_id
							  and ratings=".q($rating)."
							)
							";							
							//and ratings>=".q($rating)."
					    }					    
					    break;
					    
					default:
						break;
				}
			}
		}		
		return $and;
	}
	
	public static function getLocalID($local_id='')
	{		
		if(!empty($local_id)){
			$dependency = new CDbCacheDependency('SELECT MAX(date_modified) FROM {{map_places}}');
			$model = AR_map_places::model()->cache( Yii::app()->params->cache , $dependency)->find("reference_id=:reference_id",array(
			  ':reference_id'=>$local_id		  
			));	
			/*$model = AR_map_places::model()->find("reference_id=:reference_id",array(
			  ':reference_id'=>$local_id		  
			));	*/
			if($model){
				return $model;
			}
		} else throw new Exception( 'Place id is empty' );
		throw new Exception( 'Place id not found' );
	}
	
	public static function preSearch($filter=array(), $filter_location=true)
	{
		if(!is_array($filter) && count($filter)<=0){
			throw new Exception( 'Invalid filter' );
		}
		
		if($filter_location){
			if(empty($filter['lat']) || empty($filter['lng']) ){
				throw new Exception( 'Invalid coordinates' );
			}
			if(empty($filter['unit'])){
				throw new Exception( 'Invalid distance unit' );
			}
		}		
		
		$distance_exp = self::getDistanceExp($filter);
		$and = self::preFilter($filter);

		$and_distance_filter = '';
		if($filter_location){		
		   $and_distance_filter = "
		    AND 
			CASE 
			WHEN a.distance_unit = 'mi' THEN
			   (3959 * ACOS(SIN(RADIANS(a.latitude)) * SIN(RADIANS($filter[lat])) + COS(RADIANS(a.latitude)) * COS(RADIANS($filter[lat])) * COS(RADIANS(a.lontitude - $filter[lng]))))
			WHEN a.distance_unit = 'km' THEN
			   (6371 * ACOS(SIN(RADIANS(a.latitude)) * SIN(RADIANS($filter[lat])) + COS(RADIANS(a.latitude)) * COS(RADIANS($filter[lat])) * COS(RADIANS($filter[lng] - a.lontitude))))
			END < a.delivery_distance_covered
		   ";
		}


        $and_zone_filter = '';
        if (!empty($filter['zone_ids']) && is_array($filter['zone_ids'])) {

            $zone_ids_string = implode(',', $filter['zone_ids']);
            $and_zone_filter = "AND (mm.meta_name = 'zone' AND mm.meta_value IN ($zone_ids_string))";

        }
					
		$stmt="
		SELECT count(*) as total		
		FROM {{merchant}} a
		LEFT JOIN {{merchant_meta}} mm ON a.merchant_id = mm.merchant_id
		WHERE a.status='active' AND a.is_ready ='2' 		
		$and_distance_filter
		$and_zone_filter
		$and
		";				
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){			
			return $res['total'];
		}
		throw new Exception( 'no results' );

	}

	public static function Search($filter=array(), $lang = KMRS_DEFAULT_LANGUAGE , $filter_location = true )
	{
		
		if(!is_array($filter) && count($filter)<=0){
			throw new Exception( 'Invalid filter' );
		}
		
		if($filter_location){
			if(empty($filter['lat']) || empty($filter['lng']) ){
				throw new Exception( 'Invalid coordinates' );
			}
			if(empty($filter['unit'])){
				throw new Exception( 'Invalid distance unit' );
			}
		}		
		if(empty($filter['limit'])){
			throw new Exception( 'Invalid limit' );
		}
		
		$distance_exp = self::getDistanceExp($filter);
		$and = self::preFilter($filter);

		$unit = isset($filter['unit'])?$filter['unit']:'';		
		$unit = !empty($unit)? MapSdk::prettyUnit($unit) : $unit;		
		
		$query_distance = ''; $having_condition = ''; $sort_distance = '';

        $client_id_condition = ($filter['client_id'] == 0)
            ? "client_id = 0"
            : "client_id = " . q($filter['client_id']);

        if($filter_location){
			$query_distance = ",
			CASE 
			WHEN a.distance_unit = 'mi' THEN
			  (3959 * ACOS(SIN(RADIANS(a.latitude)) * SIN(RADIANS($filter[lat])) + COS(RADIANS(a.latitude)) * COS(RADIANS($filter[lat])) * COS(RADIANS(a.lontitude - $filter[lng]))))
			WHEN a.distance_unit = 'km' THEN
			  (6371 * ACOS(SIN(RADIANS(a.latitude)) * SIN(RADIANS($filter[lat])) + COS(RADIANS(a.latitude)) * COS(RADIANS($filter[lat])) * COS(RADIANS($filter[lng] - a.lontitude))))
		    END AS distance
			";
			$having_condition = "HAVING distance < a.delivery_distance_covered";
			$sort_distance = ', distance ASC';
		}


        $and_zone_filter = '';
        if (!empty($filter['zone_ids']) && is_array($filter['zone_ids'])) {

            $zone_ids_string = implode(',', $filter['zone_ids']);
            $and_zone_filter = "AND (mm.meta_name = 'zone' AND mm.meta_value IN ($zone_ids_string))";

        }
		
		$stmt="
		SELECT 
		a.merchant_id,
		a.restaurant_name,
		a.restaurant_slug,
		a.logo,
		a.delivery_distance_covered,
		a.distance_unit	,
		a.status,a.is_ready,
		a.close_store,
		a.disabled_ordering,
		a.pause_ordering,		
		a.path,
		a.header_image,a.path2,
		
		IFNULL((
		 select GROUP_CONCAT(cuisine_name,';',color_hex,';',font_color_hex)
		 from {{view_cuisine}}
		 where language=".q($lang)."
		 and cuisine_id in (
		    select cuisine_id from {{cuisine_merchant}}
		    where merchant_id  = a.merchant_id
		 )		 		 
		),'') as cuisine_name,
		
		(
		select concat(review_count,';',ratings) as ratings from {{view_ratings}}
		where merchant_id = a.merchant_id
		) as ratings,

		(
		select option_value
		from {{option}}
		where option_name='merchant_delivery_charges_type'
		and merchant_id = a.merchant_id
		) as charge_type,
		
		(
		select option_value
		from {{option}}
		where option_name='free_delivery_on_first_order'
		and merchant_id = a.merchant_id
		) as free_delivery,
        (
            SELECT 
                CASE
                    -- Merchant Discount Logic with Plus Delivery
                    WHEN merchant_discount_amount > 0 
                        AND merchant_free_delivery_forced = 1
                        AND city_discount_percentage > 0 
                    THEN CONCAT('merchant_discount_plus_delivery - ', 
                                CASE 
                                    WHEN total_order_count = 0 THEN 'First Discount'
                                    WHEN total_order_count = 1 THEN 'Second Discount'
                                    WHEN total_order_count = 2 THEN 'Third Discount'
                                    ELSE 'No Applicable Discount'
                                END
                    )
                    
                    -- Merchant Discount Logic without Plus Delivery
                    WHEN merchant_discount_amount > 0 
                        AND merchant_free_delivery_forced = 1
                    THEN CONCAT('merchant_discount - ', 
                                CASE 
                                    WHEN total_order_count = 0 THEN 'First Discount'
                                    WHEN total_order_count = 1 THEN 'Second Discount'
                                    WHEN total_order_count = 2 THEN 'Third Discount'
                                    ELSE 'No Applicable Discount'
                                END
                    )
                
                    -- System Discount Logic with Plus Delivery
                    WHEN system_discount_amount > 0 
                        AND system_discount_is_forced = 1
                        AND city_discount_percentage > 0 
                    THEN CONCAT('system_discount_plus_delivery - ', 
                                CASE 
                                    WHEN total_order_count = 0 THEN 'First Discount'
                                    WHEN total_order_count = 1 THEN 'Second Discount'
                                    WHEN total_order_count = 2 THEN 'Third Discount'
                                    ELSE 'No Applicable Discount'
                                END
                    )
                
                    -- System Discount Logic without Plus Delivery
                    WHEN system_discount_amount > 0 
                        AND system_discount_is_forced = 1
                    THEN CONCAT('system_discount - ', 
                                CASE 
                                    WHEN total_order_count = 0 THEN 'First Discount'
                                    WHEN total_order_count = 1 THEN 'Second Discount'
                                    WHEN total_order_count = 2 THEN 'Third Discount'
                                    ELSE 'No Applicable Discount'
                                END
                    )
                
                    -- Unforced Merchant Discount with Plus Delivery
                    WHEN merchant_discount_amount > 0 
                        AND city_discount_percentage > 0 
                    THEN CONCAT('merchant_discount_plus_delivery - ', 
                                CASE 
                                    WHEN total_order_count = 0 THEN 'First Discount'
                                    WHEN total_order_count = 1 THEN 'Second Discount'
                                    WHEN total_order_count = 2 THEN 'Third Discount'
                                    ELSE 'No Applicable Discount'
                                END
                    )
                
                    -- Unforced Merchant Discount
                    WHEN merchant_discount_amount > 0 
                    THEN CONCAT('merchant_discount - ', 
                                CASE 
                                    WHEN total_order_count = 0 THEN 'First Discount'
                                    WHEN total_order_count = 1 THEN 'Second Discount'
                                    WHEN total_order_count = 2 THEN 'Third Discount'
                                    ELSE 'No Applicable Discount'
                                END
                    )
                
                    -- Unforced System Discount with Plus Delivery
                    WHEN system_discount_amount > 0 
                        AND city_discount_percentage > 0 
                    THEN CONCAT('system_discount_plus_delivery - ', 
                                        CASE 
                                            WHEN total_order_count = 0 THEN 'First Discount'
                                            WHEN total_order_count = 1 THEN 'Second Discount'
                                            WHEN total_order_count = 2 THEN 'Third Discount'
                                            ELSE 'No Applicable Discount'
                                        END
                            )
                        
                    -- Unforced System Discount
                    WHEN system_discount_amount > 0 
                    THEN CONCAT('system_discount - ', 
                                CASE 
                                    WHEN total_order_count = 0 THEN 'First Discount'
                                    WHEN total_order_count = 1 THEN 'Second Discount'
                                    WHEN total_order_count = 2 THEN 'Third Discount'
                                    ELSE 'No Applicable Discount'
                                END
                    )
                
                    -- Delivery Discount Alone (No Merchant/System Discount)
                    WHEN city_discount_percentage > 0 
                    THEN 'delivery_discount'
                
                    ELSE 'no_discount'
                END AS applicable_discount_type
            FROM st_applicable_discounts_materialized
            WHERE ".$client_id_condition."
            AND merchant_id = a.merchant_id
        ) AS applicable_discount_type,

		(
		select COUNT(DISTINCT(merchant_id))
		from {{favorites}}
		where merchant_id = a.merchant_id
		and client_id=".q($filter['client_id'])."
		and fav_type='restaurant'
		) as saved_store,
		
		(
		select GROUP_CONCAT(day_of_week,';',start_time,';',end_time order by day_of_week asc)
		from {{opening_hours}}
		where merchant_id = a.merchant_id
		and day_of_week>=".q(intval($filter['day_of_week']))."
		and CAST(".q($filter['time_now'])." AS TIME) < CAST(end_time AS TIME)
		and status='open'		
		) as next_opening
		
		$query_distance	
		
		,(
			select count(*) from
			{{opening_hours}}
			where
			merchant_id = a.merchant_id
			and
			day=".q($filter['today_now'])."
			and
			status = 'open'
			and 
			
			(
			CAST(".q($filter['time_now'])." AS TIME)
			BETWEEN CAST(start_time AS TIME) and CAST(end_time AS TIME)					
			
			)
			
		) as merchant_open_status
		
		FROM {{merchant}} a		
		LEFT JOIN {{merchant_meta}} mm ON a.merchant_id = mm.merchant_id
		WHERE a.status='active'  AND a.is_ready ='2'		
		$and
		$and_zone_filter
		$having_condition
		ORDER BY close_store,disabled_ordering,pause_ordering ASC, merchant_open_status+0 DESC, is_sponsored DESC $sort_distance		
		LIMIT $filter[offset],$filter[limit]
		";

		if($res = CCacheData::queryAll($stmt)){									
			foreach ($res as $val) {
				$val2 = $val;	
				$cuisine_list = array();
				$cuisine_name = explode(",",$val['cuisine_name']);
				if(is_array($cuisine_name) && count($cuisine_name)>=1){
					foreach ($cuisine_name as $cuisine_val) {						
						$cuisine = explode(";",$cuisine_val);								
						$cuisine_list[]=array(
						  'cuisine_name'=>isset($cuisine[0])?Yii::app()->input->xssClean($cuisine[0]):'',
						  'bgcolor'=>isset($cuisine[1])?  (!empty($cuisine[1])?$cuisine[1]:'#ffd966')  :'#ffd966',
						  'fncolor'=>isset($cuisine[2])? (!empty($cuisine[2])?$cuisine[2]:'#ffd966') :'#000',
						);
					}
				}
				
				$ratings = array();
				if($rate = explode(";",$val['ratings'])){
				   $ratings = array(
				     'review_count'=>isset($rate[0])?intval($rate[0]):0,
				     'rating'=>isset($rate[1])?intval($rate[1]):0,
				   );
				}			
				
				/*next_opening*/	
				$next_opening = '';
				if(!empty($val['next_opening'])){
					$next_open = explode(",",$val['next_opening']);							
					if(is_array($next_open) && count($next_open)>=1){
						$next_open = isset($next_open[0])?$next_open[0]:'';						
						$next_open = explode(";",$next_open);	
																		
						$next_open_date = self::getDayWeek($filter['date_now'],$next_open[0]);
						$next_open_date ="$next_open_date $next_open[1]";
											
						$next_opening = t("Opens [day] at [time]",array(
						 '[day]'=>Date_Formatter::date($next_open_date,"E"),
						 '[time]'=>Date_Formatter::Time($next_open_date,"h:mm a")
						));
					}
				}
				
			    
				$val2['restaurant_name'] = Yii::app()->input->xssClean($val2['restaurant_name']);
				$val2['cuisine_name'] = (array)$cuisine_list;
				$val2['ratings'] = $ratings;
				$val2['merchant_url']= Yii::app()->createAbsoluteUrl($val2['restaurant_slug']);				
				$val2['url_logo']= CMedia::getImage($val2['logo'],$val2['path'],Yii::app()->params->size_image_medium,
				CommonUtility::getPlaceholderPhoto('merchant_logo'));

				$val2['url_header']= CMedia::getImage($val2['header_image'],$val2['path2'],Yii::app()->params->size_image_medium,
				CommonUtility::getPlaceholderPhoto('item'));

				$val2['next_opening'] = $next_opening;			

				if(isset($val2['distance'])){
					$distance = Price_Formatter::convertToRaw($val2['distance'],2);										
				    $val2['distance'] = $distance;				    
					$val2['distance_pretty'] = t("{{distance} {{unit}}",[
						'{{distance}'=>$distance,
						'{{unit}}'=>MapSdk::prettyUnit($val['distance_unit'])
					]);
				} else {
					$val2['distance'] = '';
					$val2['distance_pretty'] = '';
				}			
				$data[] = $val2;
			}
			return $data;
		} else throw new Exception( 'no results' );		
	}
	
	public static function getDayWeek($date='',$day=0)
	{
		$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday');
		if(isset($days[$day])){
		   return date('Y-m-d', strtotime($days[$day], strtotime($date)));
		}
	}
	
    public static function services($filter='' , $filter_location = true)
	{
		$distance_exp = self::getDistanceExp($filter);

		$distance_query = "";
		if($filter_location){
			$distance_query = "
			AND b.delivery_distance_covered > (
				( $distance_exp * acos( cos( radians($filter[lat]) ) * cos( radians( latitude ) ) 
				 * cos( radians( lontitude ) - radians($filter[lng]) ) 
				+ sin( radians($filter[lat]) ) * sin( radians( latitude ) ) ) ) 
			)
			";
		}

        $and_zone_filter = '';
        if (!empty($filter['zone_ids']) && is_array($filter['zone_ids'])) {

            $zone_ids_string = implode(',', $filter['zone_ids']);
            $and_zone_filter = "AND (mm.meta_name = 'zone' AND mm.meta_value IN ($zone_ids_string))";

        }
		
		$data = array();
		$stmt="
		SELECT a.meta_value as service_name,
		a.merchant_id
		
		FROM {{merchant_meta}} a
		WHERE 
		a.merchant_id IN (
		    SELECT b.merchant_id
			FROM {{merchant}} b
		    LEFT JOIN {{merchant_meta}} mm ON b.merchant_id = mm.merchant_id
			WHERE b.status='active' AND b.is_ready ='2' 		
			$distance_query
			$and_zone_filter		
		)
		AND a.meta_name ='services'
		";				
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){						
			foreach ($res as $val) {
				$data[$val['merchant_id']][] = $val['service_name'];
			}
			return $data;
		}
		return false;
	}
	
    public static function estimation($filter=array() , $filter_location = true)
	{
		$distance_exp = self::getDistanceExp($filter);		
		$distance_query = "";
		if($filter_location){
			$distance_query = "
			AND a.delivery_distance_covered > (
				( $distance_exp * acos( cos( radians($filter[lat]) ) * cos( radians( latitude ) ) 
				 * cos( radians( lontitude ) - radians($filter[lng]) ) 
				+ sin( radians($filter[lat]) ) * sin( radians( latitude ) ) ) ) 
			)
			";
		}
		
	    $data = array();
		$stmt="
		SELECT merchant_id,service_code,charge_type,distance_price,
		estimation,shipping_type
		FROM {{shipping_rate}} a
		WHERE
		shipping_type='standard'
		AND merchant_id  IN (
		    SELECT merchant_id
			FROM {{merchant}} a 					
			WHERE a.status='active' AND a.is_ready ='2'		
			$distance_query	
		)
		";						
		$dependency = CCacheData::dependency();	
		if($res = Yii::app()->db->cache(Yii::app()->params->cache,$dependency)->createCommand($stmt)->queryAll()){			
			foreach ($res as $val) {
				$data[$val['merchant_id']][$val['service_code']][$val['charge_type']] = array(
				  'service_code'=>$val['service_code'],
				  'charge_type'=>$val['charge_type'],
				  'estimation'=>$val['estimation'],
				  'shipping_type'=>$val['shipping_type'],
				  'fee'=>$val['distance_price']
				);
			}
			return $data;
		}
		return false;
	}		
	
	public static function estimationMerchant($filter=array())
	{
		$distance_exp = self::getDistanceExp($filter);
		
	    $data = array();
		$stmt="
		SELECT merchant_id,service_code,charge_type,
		estimation,shipping_type
		FROM {{shipping_rate}} a
		WHERE
		shipping_type=".q($filter['shipping_type'])."
		AND merchant_id  IN (
		    SELECT merchant_id
			FROM {{merchant}} a 					
			WHERE a.status='active' AND a.is_ready ='2' 	
			AND merchant_id = ".intval($filter['merchant_id'])."	
			AND a.delivery_distance_covered > (
			  ( $distance_exp * acos( cos( radians($filter[lat]) ) * cos( radians( latitude ) ) 
			   * cos( radians( lontitude ) - radians($filter[lng]) ) 
			  + sin( radians($filter[lat]) ) * sin( radians( latitude ) ) ) ) 
			)
		)
		";						
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){				
			foreach ($res as $val) {
				$data[$val['service_code']][$val['charge_type']] = array(
				  'service_code'=>$val['service_code'],
				  'charge_type'=>$val['charge_type'],
				  'estimation'=>$val['estimation'],
				  'shipping_type'=>$val['shipping_type']
				);
			}
			return $data;
		}
		return false;
	}		

	public static function estimationMerchant2($filter=array())
	{
		
	    $data = array();
		$stmt="
		SELECT merchant_id,service_code,charge_type,
		estimation,shipping_type
		FROM {{shipping_rate}} a
		WHERE
		shipping_type=".q($filter['shipping_type'])."
		AND merchant_id  IN (
		    SELECT merchant_id
			FROM {{merchant}} a 					
			WHERE a.status='active' AND a.is_ready ='2' 	
			AND merchant_id = ".intval($filter['merchant_id'])."			
		)
		";		
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){				
			foreach ($res as $val) {
				$data[$val['service_code']][$val['charge_type']] = array(
				  'service_code'=>$val['service_code'],
				  'charge_type'=>$val['charge_type'],
				  'estimation'=>$val['estimation'],
				  'shipping_type'=>$val['shipping_type']
				);
			}
			return $data;
		}
		throw new Exception( 'no results' );
	}		

	public static function searchSuggestion($filter=array(), $lang = KMRS_DEFAULT_LANGUAGE)
	{
		$query_distance='';	$where = ''; $and= '';	 	
		$distance_exp = self::getDistanceExp($filter);
		$query = isset($filter['search'])?$filter['search']:'';
		$page = isset($filter['page'])?intval($filter['page']):0;
		$limit = isset($filter['limit'])?intval($filter['limit']):10;
		
		$unit = isset($filter['unit'])?$filter['unit']:'mi';
		$lat = isset($filter['lat'])?$filter['lat']:'';
		$lng = isset($filter['lng'])?$filter['lng']:'';
		
		if(!empty($lat) && !empty($lng)){
			$query_distance = "
			( $distance_exp * acos( cos( radians($lat) ) * cos( radians( latitude ) ) 
			* cos( radians( lontitude ) - radians($lng) ) 
			+ sin( radians($lat) ) * sin( radians( latitude ) ) ) ) 
			AS distance,
			";
			$where='HAVING distance < a.delivery_distance_covered';
			//$and = "OR cuisine_name LIKE ".q("%$query%")." ";
		} else $where = "WHERE 1";
		
		if(empty($query)){
			throw new Exception( 'no results' );
		}
		
		$stmt = "
		SELECT a.restaurant_slug as slug,a.restaurant_name as title,
		a.logo,a.path,a.delivery_distance_covered,a.status,a.is_ready,
		
		$query_distance
		
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
		$where
		AND restaurant_name LIKE ".q("%$query%")."
		AND a.status='active'  AND a.is_ready ='2' 		
		$and
		ORDER BY a.restaurant_name ASC
		LIMIT $page,$limit
		";								
		if( $res = CCacheData::queryAll($stmt)){
			$data = array();
			foreach ($res as $val) {
				$val2 = $val;	
				$cuisine_list = array();
				$cuisine_name = explode(",",$val['cuisine_name']);				
				if(is_array($cuisine_name) && count($cuisine_name)>=1){
					foreach ($cuisine_name as $cuisine_val) {						
						$cuisine = explode(";",$cuisine_val);								
						$cuisine_list[]=array(
						  'cuisine_name'=>isset($cuisine[0])?Yii::app()->input->xssClean($cuisine[0]):'',
						  'bgcolor'=>isset($cuisine[1])?  (!empty($cuisine[1])?$cuisine[1]:'#ffd966')  :'#ffd966',
						  'fncolor'=>isset($cuisine[2])? (!empty($cuisine[2])?$cuisine[2]:'#ffd966') :'#000',
						);
					}
				}
				
				$val2['title'] = Yii::app()->input->xssClean($val2['title']);
				$val2['cuisine_name'] = (array)$cuisine_list;
				$val2['url']= Yii::app()->createAbsoluteUrl($val2['slug']);				
				$val2['url_logo'] = CMedia::getImage($val2['logo'],$val2['path'],"@2x",
				CommonUtility::getPlaceholderPhoto('merchant'));
				$data[] = $val2;
			}
			return $data;
		}
		throw new Exception( 'no results' );
	}

	public static function searchSuggestionFood($filter=array(), $lang = KMRS_DEFAULT_LANGUAGE)
	{
		$query_distance='';	$where = ''; $and= '';	 	
		$distance_exp = self::getDistanceExp($filter);
		$query = isset($filter['search'])?$filter['search']:'';
		$page = isset($filter['page'])?intval($filter['page']):0;
		$limit = isset($filter['limit'])?intval($filter['limit']):10;
		
		$unit = isset($filter['unit'])?$filter['unit']:'mi';
		$lat = isset($filter['lat'])?$filter['lat']:'';
		$lng = isset($filter['lng'])?$filter['lng']:'';
		
		if(!empty($lat) && !empty($lng)){
			$query_distance = "
			( $distance_exp * acos( cos( radians($lat) ) * cos( radians( latitude ) ) 
			* cos( radians( lontitude ) - radians($lng) ) 
			+ sin( radians($lat) ) * sin( radians( latitude ) ) ) ) 
			AS distance
			";
			$where='HAVING distance < a.delivery_distance_covered';			
		} else $where = "WHERE 1";
		
		if(empty($query)){
			throw new Exception( 'no results' );
		}

		$stmt = "
		SELECT a.item_name as title, b.slug, b.photo, b.path,
		b.merchant_id,

		IFNULL((
			select GROUP_CONCAT(cuisine_name,';',color_hex,';',font_color_hex)
			from {{view_cuisine}}
			where language=".q($lang)."
			and cuisine_id in (
			   select cuisine_id from {{cuisine_merchant}}
			   where merchant_id  = b.merchant_id
			)		 		 
		),'') as cuisine_name

		FROM {{item_translation}} a
		LEFT JOIN {{item}} b
		ON 
		a.item_id = b.item_id

		WHERE a.item_name LIKE ".q("%$query%")."
		AND  a.language=".q($lang)."
		AND b.merchant_id IN (
			select merchant_id
			from {{merchant}}
			where delivery_distance_covered > (
				select 
			    $query_distance from {{merchant}}
				where merchant_id = b.merchant_id
				AND status='active'  AND is_ready ='2' 		
			)
		)
		";						
		
		if( $res = CCacheData::queryAll($stmt)){
			$data = array();
			foreach ($res as $val) {
				$val2 = $val;	
				$cuisine_list = array();
				$cuisine_name = explode(",",$val['cuisine_name']);				
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
								
				$val2['title'] = Yii::app()->input->xssClean($val2['title']);
				$val2['cuisine_name'] = (array)$cuisine_list;
				$val2['url']= Yii::app()->createAbsoluteUrl($val2['slug']);				
				$val2['url_logo'] = CMedia::getImage($val2['photo'],$val2['path'],"@2x",
				CommonUtility::getPlaceholderPhoto('item'));
				$data[] = $val2;
			}
			return $data;
		}
		throw new Exception( 'no results' );
	}
	
	public static function checkStoreOpen($merchant_id=0, $date_now='', $time_now='')
	{
		$day_of_week = strtolower(date("N",strtotime($date_now)));
		$today_now = strtolower(date("l",strtotime($date_now)));
		
		$stmt="
		SELECT a.merchant_id,
		
		(
		select GROUP_CONCAT(day_of_week,';',start_time,';',end_time order by day_of_week asc)
		from {{opening_hours}}
		where merchant_id = a.merchant_id
		and day_of_week>=".q(intval($day_of_week))."
		and status='open'		
		) as next_opening,
		
		(
			select count(*) from
			{{opening_hours}}
			where
			merchant_id = a.merchant_id
			and
			day=".q($today_now)."
			and
			status = 'open'
			and 
			
			(
			CAST(".q($time_now)." AS TIME)
			BETWEEN CAST(start_time AS TIME) and CAST(end_time AS TIME)
			
			or
			
			CAST(".q($time_now)." AS TIME)
			BETWEEN CAST(start_time_pm AS TIME) and CAST(end_time_pm AS TIME)
			
			)
			
		) as merchant_open_status
		
		FROM {{merchant}} a
		WHERE merchant_id = ".q($merchant_id)."
		";									
		if($res=Yii::app()->db->createCommand($stmt)->queryRow()){
			/*next_opening*/	
			$next_opening = '';
			if(!empty($res['next_opening'])){
				$next_open = explode(",",$res['next_opening']);							
				if(is_array($next_open) && count($next_open)>=1){
					$next_open = isset($next_open[0])?$next_open[0]:'';						
					$next_open = explode(";",$next_open);	
																	
					$next_open_date = self::getDayWeek($date_now,$next_open[0]);
					$next_open_date ="$next_open_date $next_open[1]";
										
					$next_opening = t("Opens [day] at [time]",array(
					 '[day]'=>Date_Formatter::date($next_open_date,"E"),
					 '[time]'=>Date_Formatter::Time($next_open_date,"h:mm a")
					));
				}
			}

			$res['next_opening'] = $next_opening;
			return $res;
		}
		throw new Exception( 'no results' );
	}
	
	public static function checkCurrentTime($datetime_now='', $datetime_to='')
	{		
		$diff = CommonUtility::dateDifference($datetime_to,$datetime_now);
		if(is_array($diff) && count($diff)>=1){
			if($diff['days']>0){
			   throw new Exception( "Selected delivery time is already past" );	
			}			
			if($diff['hours']>0){
			   throw new Exception( "Selected delivery time is already past" );	
			}			
			if($diff['minutes']>1){
			   throw new Exception( "Selected delivery time is already past" );	
			}			
		}
		return true;
	}
	
	public static function storeAvailable($merchant_uuid='')
	{
		$merchant = CMerchants::getByUUID($merchant_uuid);
		$message = t("Currently unavailable");
		if($merchant->close_store>0){
             throw new Exception( $message );	
         } elseif ( $merchant->pause_ordering>0){
             $meta = AR_merchant_meta::getValue($merchant->merchant_id,'pause_reason');
             if($meta){			 		                  
                  throw new Exception( !empty($meta['meta_value'])?$meta['meta_value']:$message );	
             } else throw new Exception( $message );
         } else {
			$options = OptionsTools::find(['enabled_website_ordering']);
			$enabled_website_ordering = isset($options['enabled_website_ordering'])?$options['enabled_website_ordering']:false;
			$enabled_website_ordering = $enabled_website_ordering==1?true:false;			
			if(!$enabled_website_ordering){
				throw new Exception( $message );	
			}
		 } 
         return true;
	}
	
	public static function storeAvailableByID($merchant_id='')
	{
		$merchant = CMerchants::get($merchant_id);
		$message = t("Currently unavailable");
		if($merchant->close_store>0){
             throw new Exception( $message );	
        } elseif ( $merchant->pause_ordering>0){
             $meta = AR_merchant_meta::getValue($merchant->merchant_id,'pause_reason');
             if($meta){			 		                  
                  throw new Exception( !empty($meta['meta_value'])?$meta['meta_value']:$message );	
             } else throw new Exception( $message );
        } else {
			$options = OptionsTools::find(['enabled_website_ordering']);
			$enabled_website_ordering = isset($options['enabled_website_ordering'])?$options['enabled_website_ordering']:false;
			$enabled_website_ordering = $enabled_website_ordering==1?true:false;			
			if(!$enabled_website_ordering){
				throw new Exception( $message );	
			}
		}
        return true;
	}
	
	public static function getFeed($filter=array(),$sort_by='')
	{		
		
		$length = isset($filter['limit'])?$filter['limit']:10;
		$page = isset($filter['page'])?$filter['page']:0;						
		$continue = false;
		$distance_exp = self::getDistanceExp($filter);		
		$unit = isset($filter['unit'])?$filter['unit']:'mi';

        $client_id_condition = ($filter['client_id'] == 0)
            ? "client_id = 0"
            : "client_id = " . q($filter['client_id']);


        $criteria=new CDbCriteria();
		$criteria->alias="a";    		
    	$criteria->select="
		a.merchant_id,
		a.merchant_uuid,
		a.restaurant_name,
		a.restaurant_slug,
		a.delivery_distance_covered,
		a.logo,
		a.path,
		a.distance_unit,
		a.close_store,
		a.type,
        a.view_type,
		b.ratings,

		CASE 
			WHEN a.distance_unit = 'mi' THEN
			  (3959 * ACOS(SIN(RADIANS(a.latitude)) * SIN(RADIANS($filter[lat])) + COS(RADIANS(a.latitude)) * COS(RADIANS($filter[lat])) * COS(RADIANS(a.lontitude - $filter[lng]))))
			WHEN a.distance_unit = 'km' THEN
			  (6371 * ACOS(SIN(RADIANS(a.latitude)) * SIN(RADIANS($filter[lat])) + COS(RADIANS(a.latitude)) * COS(RADIANS($filter[lat])) * COS(RADIANS($filter[lng] - a.lontitude))))
		    END AS distance

		,(
			select count(*) from
			{{opening_hours}}
			where
			merchant_id = a.merchant_id
			and
			day=".q($filter['today_now'])."
			and
			status = 'open'
			and 
			
			(
			CAST(".q($filter['time_now'])." AS TIME)
			BETWEEN CAST(start_time AS TIME) and CAST(end_time AS TIME)
			
			or
			
			CAST(".q($filter['time_now'])." AS TIME)
			BETWEEN CAST(start_time_pm AS TIME) and CAST(end_time_pm AS TIME)
			
			)
			
		) as open_status,

		(
			select GROUP_CONCAT(cuisine_id)
			from {{cuisine_merchant}}
			where merchant_id = a.merchant_id
		) as cuisine_group,
		
		(
			select GROUP_CONCAT(tag_id)
			from {{tags}}
		) as tag_group,

		(
			select option_value
			from {{option}}
			where option_name='merchant_delivery_charges_type'
			and merchant_id = a.merchant_id
		) as charge_type,

		(
			select COUNT(DISTINCT(merchant_id))
			from {{favorites}}
			where merchant_id = a.merchant_id
			and client_id=".q($filter['client_id'])."
			and fav_type='restaurant'
		) as saved_store,

		(
		select option_value
		from {{option}}
		where option_name='free_delivery_on_first_order'
		and merchant_id = a.merchant_id
		) as free_delivery,
		(
            SELECT 
                CASE
                    -- Merchant Discount Logic with Plus Delivery
                    WHEN merchant_discount_amount > 0 
                        AND merchant_free_delivery_forced = 1
                        AND city_discount_percentage > 0 
                    THEN CONCAT('merchant_discount_plus_delivery - ', 
                                CASE 
                                    WHEN total_order_count = 0 THEN 'First Discount'
                                    WHEN total_order_count = 1 THEN 'Second Discount'
                                    WHEN total_order_count = 2 THEN 'Third Discount'
                                    ELSE 'No Applicable Discount'
                                END
                    )
                    
                    -- Merchant Discount Logic without Plus Delivery
                    WHEN merchant_discount_amount > 0 
                        AND merchant_free_delivery_forced = 1
                    THEN CONCAT('merchant_discount - ', 
                                CASE 
                                    WHEN total_order_count = 0 THEN 'First Discount'
                                    WHEN total_order_count = 1 THEN 'Second Discount'
                                    WHEN total_order_count = 2 THEN 'Third Discount'
                                    ELSE 'No Applicable Discount'
                                END
                    )
                
                    -- System Discount Logic with Plus Delivery
                    WHEN system_discount_amount > 0 
                        AND system_discount_is_forced = 1
                        AND city_discount_percentage > 0 
                    THEN CONCAT('system_discount_plus_delivery - ', 
                                CASE 
                                    WHEN total_order_count = 0 THEN 'First Discount'
                                    WHEN total_order_count = 1 THEN 'Second Discount'
                                    WHEN total_order_count = 2 THEN 'Third Discount'
                                    ELSE 'No Applicable Discount'
                                END
                    )
                
                    -- System Discount Logic without Plus Delivery
                    WHEN system_discount_amount > 0 
                        AND system_discount_is_forced = 1
                    THEN CONCAT('system_discount - ', 
                                CASE 
                                    WHEN total_order_count = 0 THEN 'First Discount'
                                    WHEN total_order_count = 1 THEN 'Second Discount'
                                    WHEN total_order_count = 2 THEN 'Third Discount'
                                    ELSE 'No Applicable Discount'
                                END
                    )
                
                    -- Unforced Merchant Discount with Plus Delivery
                    WHEN merchant_discount_amount > 0 
                        AND city_discount_percentage > 0 
                    THEN CONCAT('merchant_discount_plus_delivery - ', 
                                CASE 
                                    WHEN total_order_count = 0 THEN 'First Discount'
                                    WHEN total_order_count = 1 THEN 'Second Discount'
                                    WHEN total_order_count = 2 THEN 'Third Discount'
                                    ELSE 'No Applicable Discount'
                                END
                    )
                
                    -- Unforced Merchant Discount
                    WHEN merchant_discount_amount > 0 
                    THEN CONCAT('merchant_discount - ', 
                                CASE 
                                    WHEN total_order_count = 0 THEN 'First Discount'
                                    WHEN total_order_count = 1 THEN 'Second Discount'
                                    WHEN total_order_count = 2 THEN 'Third Discount'
                                    ELSE 'No Applicable Discount'
                                END
                    )
                
                    -- Unforced System Discount with Plus Delivery
                    WHEN system_discount_amount > 0 
                        AND city_discount_percentage > 0 
                    THEN CONCAT('system_discount_plus_delivery - ', 
                                        CASE 
                                            WHEN total_order_count = 0 THEN 'First Discount'
                                            WHEN total_order_count = 1 THEN 'Second Discount'
                                            WHEN total_order_count = 2 THEN 'Third Discount'
                                            ELSE 'No Applicable Discount'
                                        END
                            )
                        
                    -- Unforced System Discount
                    WHEN system_discount_amount > 0 
                    THEN CONCAT('system_discount - ', 
                                CASE 
                                    WHEN total_order_count = 0 THEN 'First Discount'
                                    WHEN total_order_count = 1 THEN 'Second Discount'
                                    WHEN total_order_count = 2 THEN 'Third Discount'
                                    ELSE 'No Applicable Discount'
                                END
                    )
                
                    -- Delivery Discount Alone (No Merchant/System Discount)
                    WHEN city_discount_percentage > 0 
                    THEN 'delivery_discount'
                
                    ELSE 'no_discount'
                END AS applicable_discount_type
            FROM st_applicable_discounts_materialized
            WHERE ".$client_id_condition."
            AND merchant_id = a.merchant_id
        ) AS applicable_discount_type

		";
				
		
		if(isset($filter['having'])){
			$criteria->having = $filter['having'];
		}
		if(isset($filter['condition'])){
			$criteria->condition = $filter['condition'];
		}
		if(isset($filter['params'])){
			$criteria->params = $filter['params'];
		}
		if(isset($filter['search'])){
			$criteria->addSearchCondition($filter['search'], $filter['search_params'] );
		}

		$criteria->join = "LEFT JOIN {{view_ratings}} b ON a.merchant_id = b.merchant_id";

        // LEFT JOIN to merchant_meta table
        $criteria->join .= " JOIN {{merchant_meta}} mm ON a.merchant_id = mm.merchant_id AND mm.meta_name = 'zone'";

        if (!empty($filter['zone_ids']) && is_array($filter['zone_ids'])) {
            $placeholders = [];
            foreach ($filter['zone_ids'] as $index => $zone_id) {
                $placeholders[] = ':zone_id_' . $index; // Use named placeholders
            }
            $placeholders_str = implode(',', $placeholders);
            $criteria->addCondition("mm.meta_name = 'zone' AND mm.meta_value IN ($placeholders_str)");

            // Bind each named placeholder
            foreach ($filter['zone_ids'] as $index => $zone_id) {
                $criteria->params[':zone_id_' . $index] = $zone_id;
            }
        }

        $criteria->order = "close_store,disabled_ordering,pause_ordering ASC, open_status+0 DESC, is_sponsored DESC, distance ASC";
		if(!empty($sort_by)){
			switch($sort_by){
				case "distance":					
					$criteria->order = "close_store,disabled_ordering,pause_ordering ASC, open_status+0 DESC,distance ASC";			
					break;
				case "recommended":	
					$criteria->order = "is_sponsored DESC,close_store,disabled_ordering,pause_ordering ASC, open_status+0 DESC";
					break;
				case "top_rated":	
					$criteria->order = "ratings DESC, close_store,disabled_ordering,pause_ordering ASC, open_status+0 DESC";
					break;
			}
		}
				
		$count = AR_merchant::model()->count($criteria); 
		$pages=new CPagination( intval($count) );
		$pages->setCurrentPage( intval($page) );        
		$pages->pageSize = intval($length);
		$pages->applyLimit($criteria);      
		$page_count = $pages->getPageCount();	        
		if($page_count > ($page+1) ){
			$continue = true;
		}   	        	
		
		
		
		$dependency = CCacheData::dependency();
		$model = AR_merchant::model()->cache(Yii::app()->params->cache, $dependency)->FindAll($criteria); 		
				
		if($model){
			$data = []; $merchant=[];
			foreach ($model as $items) {						
				$merchant[] = $items->merchant_id;		
				$distance = Price_Formatter::convertToRaw($items->distance,2);
				$data[] = [
					'merchant_id'=>$items->merchant_id,
					'merchant_view_type'=>$items->view_type,
					'merchant_type'=>$items->type,
					'merchant_uuid'=>$items->merchant_uuid,
					'restaurant_name'=>Yii::app()->input->xssClean($items->restaurant_name),
					'restaurant_slug'=>$items->restaurant_slug,
					'restaurant_url'=>Yii::app()->createAbsoluteUrl("/$items->restaurant_slug"),
					'delivery_distance_covered'=>$items->delivery_distance_covered,
					'distance'=>$items->distance,
					'distance_pretty'=>t("{{distance} {{unit}} away",[
						'{{distance}'=>$distance,
						'{{unit}}'=>MapSdk::prettyUnit($items->distance_unit)
					]),
					'charge_type'=>$items->charge_type,
					'cuisine_group'=>explode(",",$items->cuisine_group),
					'tag_group'=>explode(",",$items->tag_group),
					'url_logo'=>CMedia::getImage($items->logo,$items->path,"@2x",CommonUtility::getPlaceholderPhoto('item')),
					'open_status_raw'=>$items->open_status,
					'open_status'=>$items->close_store==1?0:$items->open_status,
					'saved_store'=>$items->saved_store,					
					'close_store'=>$items->close_store,
					'applicable_discount_type'=>$items->applicable_discount_type,
					'free_delivery'=>$items->free_delivery==1?true:false
				];
			}			
			return [
				'continue'=>$continue,
				'merchant'=>$merchant,
				'page_count'=>$page_count,
				'count'=>$count,
				'data'=>$data
			];
		}
		throw new Exception( "No results" );
	}	

	public static function getReviews($merchant=array())
	{
		$criteria=new CDbCriteria();
		$criteria->select = "merchant_id,review_count,ratings";
		$criteria->addInCondition('merchant_id',$merchant);		

		$dependency = CCacheData::dependency();
		$model = AR_view_ratings::model()->cache(Yii::app()->params->cache, $dependency)->FindAll($criteria); 				
		if($model){
			$data = [];
			foreach ($model as $items) {				
				$data[$items->merchant_id] = [
					'review_count'=>intval($items->review_count),
					'rating'=>$items->ratings,
					'ratings'=>Price_Formatter::convertToRaw($items->ratings,1),
				];
			}
			return $data;
		}
		throw new Exception( "No results" );
	}

	public static function getCuisine($merchant=array(),$language='')
	{
		$criteria=new CDbCriteria();
		$criteria->select = "cuisine_id,cuisine_name";
		$criteria->condition = "language=:language AND cuisine_id IN (
			select cuisine_id from {{cuisine_merchant}} 
			where merchant_id IN (". implode(',', $merchant) .")
		) ";
		$criteria->params = [
			':language'=>$language
		];		
		$dependency = CCacheData::dependency();
		$model = AR_cuisine_translation::model()->cache(Yii::app()->params->cache, $dependency)->FindAll($criteria); 						
		if($model){
			$data = [];
			foreach ($model as $items) {				
				$data[$items->cuisine_id] = [					
					'name'=>$items->cuisine_name
				];
			}
			return $data;
		}
		throw new Exception( "No results" );
	}

    public static function getTag($language='')
    {
        $criteria=new CDbCriteria();
        $criteria->select = "tag_id,tag_name";
        $criteria->condition = "language=:language AND tag_id IN (
			select tag_id from {{tags}}
		) ";
        $criteria->params = [
            ':language'=>$language
        ];
        $dependency = CCacheData::dependency();
        $model = AR_tag_translation::model()->cache(Yii::app()->params->cache, $dependency)->FindAll($criteria);
        if($model){
            $data = [];
            foreach ($model as $items) {
                $data[$items->tag_id] = [
                    'name'=>$items->tag_name
                ];
            }
            return $data;
        }
        throw new Exception( "No results" );
    }

	public static function getMaxMinItem($merchant=array())
	{
		$criteria=new CDbCriteria();
		$criteria->select = "
		merchant_id,MIN(price) as min_price , MAX(price) as max_price
		";
		$criteria->addInCondition('merchant_id',$merchant);
		$criteria->group = 'merchant_id';		
		$dependency = CCacheData::dependency();
		$model = AR_item_relationship_size::model()->cache(Yii::app()->params->cache, $dependency)->FindAll($criteria); 								
		if($model){
			$data = [];
			foreach ($model as $items) {				
				$data[$items->merchant_id] = [					
					'min'=>$items->min_price,
					'max'=>$items->max_price,
					'min_pretty'=>Price_Formatter::formatNumber($items->min_price),
					'max_pretty'=>Price_Formatter::formatNumber($items->max_price),
				];
			}			
			return $data;
		}
		throw new Exception( "No results" );
	}

	public static function getMerchantList($merchant_ids=array())
	{
		$data = [];
		$criteria=new CDbCriteria();
		$criteria->addInCondition('merchant_id',$merchant_ids);
		$dependency = CCacheData::dependency();
		$model = AR_merchant::model()->cache(Yii::app()->params->cache, $dependency)->FindAll($criteria);
		if($model){			
			foreach ($model as $items) {
				$data[$items->merchant_id] = [
					'merchant_uuid'=>$items->merchant_uuid,
					'restaurant_slug'=>$items->restaurant_slug,
					'restaurant_name'=>$items->restaurant_name,
				];
			}
		}
		return $data;
	}

	public static function searchSuggestionFoodRestaurants($search='',$language=KMRS_DEFAULT_LANGUAGE)
	{
		$stmt = "
		(SELECT 
		'items' as type,
		b.item_name as name
		FROM {{item}} a
		left JOIN (
			SELECT item_id,item_name FROM {{item_translation}} WHERE language = ".q($language)."
		) b 
		on a.item_id = b.item_id
		WHERE
		b.item_name LIKE ".q("%$search%")."
		and a.status='publish' 
		and a.available=1
		LIMIT 0,10
		)

		UNION ALL

		(SELECT 
		'merchant' as type,
		restaurant_name as name
		FROM {{merchant}}
		WHERE restaurant_name LIKE ".q("%$search%")."
		AND status='active'
		AND is_ready = 2
		LIMIT 0,10
		)
		";			
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){			
			return $res;
		}
		throw new Exception( "No results" );
	}

    public static function estimationNew($filter=array() , $filter_location = true)
	{
		$lat = isset($filter['lat'])?$filter['lat']:'';
		$lng = isset($filter['lng'])?$filter['lng']:'';
		$transaction_type = isset($filter['transaction_type'])?$filter['transaction_type']:'delivery';
			
		$and = '';	$and2='';	
		if($filter_location){
			$and = "
			,
			CASE 
				WHEN a.distance_unit = 'mi' THEN
					(3959 * ACOS(SIN(RADIANS(a.latitude)) * SIN(RADIANS($lat)) + COS(RADIANS(a.latitude)) * COS(RADIANS($lat)) * COS(RADIANS(a.lontitude - $lng))))
				WHEN a.distance_unit = 'km' THEN
					(6371 * ACOS(SIN(RADIANS(a.latitude)) * SIN(RADIANS($lat)) + COS(RADIANS(a.latitude)) * COS(RADIANS($lat)) * COS(RADIANS($lng - a.lontitude))))
			END AS distance
			";
			$and2 = "
			AND (
				b.charge_type = 'fixed' OR
				(
					CASE 
						WHEN a.distance_unit = 'mi' THEN
							(3959 * ACOS(SIN(RADIANS(a.latitude)) * SIN(RADIANS($lat)) + COS(RADIANS(a.latitude)) * COS(RADIANS($lat)) * COS(RADIANS(a.lontitude - $lng))))
						WHEN a.distance_unit = 'km' THEN
							(6371 * ACOS(SIN(RADIANS(a.latitude)) * SIN(RADIANS($lat)) + COS(RADIANS(a.latitude)) * COS(RADIANS($lat)) * COS(RADIANS($lng - a.lontitude))))
					END
				) BETWEEN b.distance_from AND b.distance_to
			)
			";
		}

        $and_zone_filter = '';
        if (!empty($filter['zone_ids']) && is_array($filter['zone_ids'])) {

            $zone_ids_string = implode(',', $filter['zone_ids']);
            $and_zone_filter = "AND (mm.meta_name = 'zone' AND mm.meta_value IN ($zone_ids_string))";

        }

		$stmt = "
		SELECT
			a.merchant_id,
			a.restaurant_name,
			b.service_code,
			b.charge_type,
			b.estimation,
			b.distance_from,
			b.distance_to
			$and
		FROM
			{{merchant}} a
		LEFT JOIN
			{{shipping_rate}} b ON a.merchant_id = b.merchant_id
		LEFT JOIN 
			    {{merchant_meta}} mm ON a.merchant_id = mm.merchant_id
		WHERE
			b.service_code = ".q($transaction_type)."
			and b.shipping_type ='standard'		
			$and2
			$and_zone_filter	
		";		
		$dependency = CCacheData::dependency();	
		if($res = Yii::app()->db->cache(Yii::app()->params->cache,$dependency)->createCommand($stmt)->queryAll()){
			foreach ($res as $items) {
				$data[$items['merchant_id']][$items['service_code']][$items['charge_type']] = [
					'merchant_id'=>$items['merchant_id'],
					'estimation'=>$items['estimation'],
				];
			}
			return $data;
		}
		return false;
	}			

	public static function estimationMerchantNew($filter=array())
	{
		$merchant_id = isset($filter['merchant_id'])?$filter['merchant_id']:0;  		
		$distance = isset($filter['distance'])?$filter['distance']:0;
		$shipping_type = isset($filter['shipping_type'])?$filter['shipping_type']:'';		
		$charges_type = isset($filter['charges_type'])?$filter['charges_type']:'';		
		
		$stmt = "
		SELECT a.* 
		FROM {{shipping_rate}} a
		WHERE
		(
				(a.service_code ='delivery' AND a.charge_type = 'dynamic' AND ".q(floatval($distance))." BETWEEN a.distance_from AND a.distance_to ) OR
				(a.service_code ='delivery' AND a.charge_type = 'fixed'  ) OR
				(a.service_code ='pickup' AND a.charge_type = 'fixed') OR 
				(a.service_code ='dinein' AND a.charge_type = 'fixed')
			)
		AND a.merchant_id = ".q($merchant_id)."
		AND a.shipping_type = ".q($shipping_type)."
		";
        $data = array();
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
			foreach ($res as $val) {			
				if($val['service_code']=='delivery'){
					if($val['charge_type']==$charges_type){
						$data[$val['service_code']][$val['charge_type']] = array(
							'service_code'=>$val['service_code'],
							'charge_type'=>$val['charge_type'],
							'estimation'=>$val['estimation'],
							'shipping_type'=>$val['shipping_type']
						);
					}
				} else {
					$data[$val['service_code']][$val['charge_type']] = array(
						'service_code'=>$val['service_code'],
						'charge_type'=>$val['charge_type'],
						'estimation'=>$val['estimation'],
						'shipping_type'=>$val['shipping_type']
					);
				}				
			}			
			return $data;
		}
		return false;
	}
}