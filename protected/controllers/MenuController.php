<?php
class MenuController extends SiteCommon
{
	public function beforeAction($action)
	{						
		// SEO 
		//CSeo::setPage();
		return true;
	}
	public function convertOpeningHours($sessions) {
		// Mapping of full day names to two-letter abbreviations.
		$dayMap = [
			"monday"    => "Mo",
			"tuesday"   => "Tu",
			"wednesday" => "We",
			"thursday"  => "Th",
			"friday"    => "Fr",
			"saturday"  => "Sa",
			"sunday"    => "Su"
		];
	
		// Group sessions by time range.
		$grouped = [];
		foreach ($sessions as $session) {
			// Only process if status is 'open'
			if (isset($session['status']) && strtolower($session['status']) == 'open') {
				$dayFull = strtolower($session['day']);
				// Ensure the day exists in our mapping.
				if (!isset($dayMap[$dayFull])) {
					continue;
				}
				$dayAbbrev = $dayMap[$dayFull];
				// Convert start and end times to 24-hour format.
				$start = date("H:i", strtotime($session['start_time']));
				$end = date("H:i", strtotime($session['end_time']));
				$timeRange = $start . "-" . $end;
				// Group by time range.
				$grouped[$timeRange][] = $dayAbbrev;
			}
		}
	
		$formatted = [];
		// Define the week order so days are sorted properly.
		$weekOrder = ["Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"];
	
		foreach ($grouped as $timeRange => $days) {
			// Remove duplicates and sort days based on week order.
			$days = array_unique($days);
			usort($days, function($a, $b) use ($weekOrder) {
				return array_search($a, $weekOrder) - array_search($b, $weekOrder);
			});
			$dayStr = implode(",", $days);
			// Append the time range
			$formatted[] = $dayStr . " " . $timeRange;
		}
	
		// Join all segments with a comma and a space.
		return implode(", ", $formatted);
	}		
	public function actionMenu()
	{					
				
		try {
			
			AssetsFrontBundle::includeMaps();
			$pathInfo = Yii::app()->request->getPathInfo();		
			$matches = explode('/', $pathInfo);			
			if(is_array($matches) && count($matches)>=1){
				
				$slug_name = isset($matches[0])?$matches[0]:''; 
				

				$data = CMerchantListingV1::getMerchantInfo($slug_name,Yii::app()->language);	

				

				Yii::app()->params['seo_data'] = [
					'restaurant_name'=>$data['restaurant_name']
				];		
													
				$merchant_id = intval($data['merchant_id']);
				$merchant_uuid = trim($data['merchant_uuid']);
				$gallery = CMerchantListingV1::getGallery($merchant_id);						
				$opening_hours = CMerchantListingV1::openingHours($merchant_id);	
				
				$city_name = '';
				// Assuming $data contains a 'city_id' from AR_merchant:
				if (isset($data['city_id']) && !empty($data['city_id'])) {
					
					$cityRecord = AR_city::model()->findByPk($data['city_id']);
					
					if ($cityRecord !== null) {
						if (Yii::app()->language == 'ar'){
							$city_name = $cityRecord->ar_name;
						}
						else{
							$city_name = $cityRecord->name;
						}
					}
					
				}
				// Add city name to $data if needed by the view
				$data['city'] = $city_name;
				
				function replacePlaceholders($metaString, $data)
				{
					
					// Find all placeholders of the form {placeholder}
					preg_match_all('/\{([^}]+)\}/', $metaString, $matches);
					if (!empty($matches[1])) {
						foreach ($matches[1] as $placeholder) {
							// Clean up the placeholder text (e.g. trim spaces)
							$key = strtolower(trim($placeholder));
							// Optionally, convert spaces to underscores if your keys are formatted that way:
							$key = str_replace(' ', '_', $key);
							// Check if the key exists in $data (case-insensitive comparison)
							// You might also check the original placeholder if needed
							if (isset($data[$key])) {
								$value = $data[$key];
							} elseif (isset($data[$placeholder])) {
								$value = $data[$placeholder];
							} else {
								// If not found, replace with an empty string (or leave the placeholder as is)
								$value = '';
							}
							if (is_array($value)) {
								$value = reset($value); // equivalent to $value = $value[0];
							}
							// Replace the placeholder in the meta string
							$metaString = str_replace("{" . $placeholder . "}", $value, $metaString);
						}
					}
					
					return $metaString;
				}

				// SET SEO
				try {
                    $dependency = CCacheData::dependency();
                    $model = AR_pages_seo::model()->cache(Yii::app()->params->cache, $dependency)->find("page_id=:page_id AND merchant_id=:merchant_id", [
                        ':page_id' => "27",
                        ':merchant_id' => 0
                    ]);

                    if ($model) {
                        $models = PPages::pageDetailsSlug($model->page_id, Yii::app()->language, "a.page_id");
                    }

                    //$models = isset(Yii::app()->params['seo_data']) ? Yii::app()->params['seo_data'] : false;
					
                    if ($models) {
                        $models = CommonUtility::toLanguageParameters($models, "{{", "}}");

						$merchant_name = isset($data['restaurant_name']) ? $data['restaurant_name'] : 'Merchant Name';
						$restaurant_name = isset($data['restaurant_name']) ? $data['restaurant_name'] : 'Restaurant Name';
						
						// Replace placeholders in the meta title
						$meta_title = $models->meta_title;
						$meta_title = replacePlaceholders($meta_title, $data);
                        $models->meta_title = t($meta_title, $models);
						
						$meta_description = $models->meta_description;
						$models->meta_description  = replacePlaceholders($meta_description, $data);
                        $models->meta_description = t($models->meta_description, $models);
                        $models->meta_keywords = t($models->meta_keywords, $models);
                        CommonUtility::setSEO($models->meta_title, $models->meta_title, $models->meta_description, $models->meta_keywords, $models->image);
                    } else {
                        // Handle case where SEO data is not available
                        error_log("SEO data not available.");
                    }
                } catch (Exception $e) {
                    // Log the exception message
                    error_log("Exception: " . $e->getMessage());
                    CSeo::setPage();
                }
				
				
				$open_start=''; $open_end='';
				$today = strtolower(date("l")); 
				if(is_array($opening_hours) && count($opening_hours)>=1){
					foreach ($opening_hours as $items) {
					   if($items['day']==$today){
						  $open_start = Date_Formatter::Time($items['start_time']);
						  $open_end = Date_Formatter::Time($items['end_time']);
					   }
					}
				}        						
								
				// GET DISTANCE
				$place_id = CommonUtility::getCookie(Yii::app()->params->local_id);				
				$distance = 0;
				$unit = isset($data['distance_unit'])?$data['distance_unit']:'mi';				
				try {			
					$place_data = CMaps::locationDetails($place_id,'');					
					$distance = CMaps::getLocalDistance($data['distance_unit'],$place_data['latitude'],$place_data['longitude'],
					$data['latitude'],$data['lontitude']);			
				} catch (Exception $e) {
					//			
				}				
										
				$static_maps=''; $map_direction='';
				if($data){			  					
				   $static_maps = CMerchantListingV1::staticMapLocation(
				     Yii::app()->params['map_credentials'],
				     $data['latitude'],$data['lontitude'],
				     '500x200',websiteDomain().Yii::app()->theme->baseUrl."/assets/images/marker2@2x.png"
				   );		
				   $map_direction = CMerchantListingV1::mapDirection(Yii::app()->params['map_credentials'],
				     $data['latitude'],$data['lontitude']
				   );	   					  
				}
							
							
				$payload = array(
				  'items','subtotal','distance_local','merchant_info','items_count'
				);			
				
				ScriptUtility::registerScript(array(
				  "var merchant_id='".CJavaScript::quote($merchant_id)."';",		
				  "var merchant_uuid='".CJavaScript::quote($merchant_uuid)."';",		
				  "var payload='".CJavaScript::quote(json_encode($payload))."';",
				  "var isGuest='".CJavaScript::quote(Yii::app()->user->isGuest)."';",						  
				),'merchant_id');

				CBooking::setIdentityToken();
				
				$checkout_link = Yii::app()->createUrl("account/login?redirect=". Yii::app()->createAbsoluteUrl("/account/checkout") );
				if(!Yii::app()->user->isGuest){
					$checkout_link = Yii::app()->createUrl("/account/checkout");
				}

				if($data['has_header']){
					Yii::app()->clientScript->registerCss('headerCSS', '
						.top-merchant-details,
						.merchant-top-header .right-info
						{
							background: url("'.$data['url_header'].'") no-repeat center center #fedc79;
							background-size:cover;
						}
					');
				}

				// BOOKING
				$options = OptionsTools::find([
					'booking_enabled','booking_enabled_capcha','menu_layout','merchant_tax_number'
				],$merchant_id);
				$booking_enabled = isset($options['booking_enabled'])?$options['booking_enabled']:false;
				$booking_enabled = $booking_enabled==1?true:false;
				$booking_enabled_capcha = isset($options['booking_enabled_capcha'])?$options['booking_enabled_capcha']:false;
				$booking_enabled_capcha = $booking_enabled_capcha==1?true:false;
				$menu_layout = isset(Yii::app()->params['settings']['menu_layout'])?Yii::app()->params['settings']['menu_layout']:'left_image';
				$menu_layout = !empty($menu_layout)?$menu_layout:'left_image';
				$category_position = isset(Yii::app()->params['settings']['category_position'])?Yii::app()->params['settings']['category_position']:'left';
				$category_position = !empty($category_position)?$category_position:'left';				
				
		        $tax_number = isset($options['merchant_tax_number'])?$options['merchant_tax_number']:'';				
				
				$options = OptionsTools::find(['captcha_site_key','menu_disabled_inline_addtocart','enabled_review']);
				$captcha_site_key = isset($options['captcha_site_key'])?$options['captcha_site_key']:'';
				$disabled_inline_addtocart = isset($options['menu_disabled_inline_addtocart'])?$options['menu_disabled_inline_addtocart']:false;
				$disabled_inline_addtocart = $disabled_inline_addtocart==1?true:false;
				
				$enabled_review = isset($options['enabled_review'])?$options['enabled_review']:'';
				$enabled_review = $enabled_review==1?true:false;

				$maps_config = CMaps::config();

				$tpl = $category_position=='top'? "//store/menu_categorytop" : "//store/menu";		
				$address = [
					'@type'           => 'PostalAddress',
					'addressCountry'  => isset($data['address']) && $data['address'] 
										 ? $data['address'] 
										 : 'Palestine', // fallback country if not provided
					'addressLocality' => isset($data['address']) && $data['address']
										 ? $data['address']
										 : '', // fallback locality if not provided
					// You can add more properties such as 'streetAddress', 'postalCode', etc., if available.
				];
		
				$schema = [
					'@context'    => 'https://schema.org',
					'@type'       => 'Restaurant',
					'name'        => $data['restaurant_name'],
					'legalName'   => 'yummy',
					'address'     => $address,
					'description' => $data['description'],
					'areaServed'  => $data['address'],
					'url'         => Yii::app()->createAbsoluteUrl('/restaurants'),
					'image'       => $data['logo'],
					'brand'       => [
						'@type' => 'Brand',
						'name'  => 'Yummy',  // Replace with your brand name or dynamic value if available
						"logo"  =>  'https://devyum.yummy-app.com/upload/all/8561d109-ad49-11ee-b244-6045bdf13a52.png'			
					],
					'logo'        => 'https://devyum.yummy-app.com/upload/all/8561d109-ad49-11ee-b244-6045bdf13a52.png',
					"paymentAccepted" => 'Cash, Credit Card,',
					"openingHours" => $this->convertOpeningHours($opening_hours),
				];
				// Convert the schema array to a JSON string
				
				// Register the JSON-LD script in the head section of the HTML output
				$category = CMerchantMenu::getCategory($merchant_id, Yii::app()->language);
				$menuItems = CMerchantMenu::getMenu($merchant_id, Yii::app()->language);
				$hasMenuItems = array();
				foreach ($menuItems as $item) {
					// Use the first price option if available
					$priceOption = isset($item['price'][0]) ? $item['price'][0] : null;
					$offer = array();
					if ($priceOption) {
						$offer = array(
							"@type"         => "Offer",
							"price"         => $priceOption['price'],
							"priceCurrency" => "ILS"  // or set dynamically if available
						);
					}
					
					// Build a MenuItem structure
					$menuItemSchema = array(
						"@type"       => "MenuItem",
						"name"        => $item['item_name'],
						"description" => $item['item_description'],
						"offers"      => $offer,
						// Optionally, add more properties such as "nutrition" or "suitableForDiet"
					);
					
					$hasMenuItems[] = $menuItemSchema;
				}

				// Build the MenuSection. In a more advanced scenario you might group items by category.
				$menuSection = array(
					"@type"       => "MenuSection",
					"name"        => "Main Menu", // You can change this or generate it dynamically
					"description" => "Our available menu items",
					"hasMenuItem" => $hasMenuItems
				);

				// Build the overall hasMenu property
				$hasMenu = array(
					"@type"       => "Menu",
					"hasMenuSection" => $menuSection,
					"inLanguage"  => "English" // Or use a dynamic value if available
				);
				$schema['hasMenu'] = $hasMenu;
				$schema_json = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
				if($data){		    												
			    	$this->render($tpl,array(
			    	  'data'=>$data,
			    	  'gallery'=>$gallery,
			    	  'opening_hours'=>$opening_hours,
			    	  'static_maps'=>$static_maps,
			    	  'map_direction'=>$map_direction,
			    	  'checkout_link'=>$checkout_link,
					  'booking_enabled'=>$booking_enabled,
					  'merchant_uuid'=>$merchant_uuid,
					  'booking_enabled_capcha'=>$booking_enabled_capcha,
					  'captcha_site_key'=>$captcha_site_key,
					  'menu_layout'=>$menu_layout,
					  'tax_number'=>$tax_number,
					  'maps_config'=>$maps_config,
					  'disabled_inline_addtocart'=>$disabled_inline_addtocart,
					  'open_start'=>$open_start,
					  'open_end'=>$open_end,
					  'distance'=>[
						'value'=>$distance,
						'label'=>t("{{distance} {{unit}} away",[
							'{{distance}'=>$distance,
							'{{unit}}'=>MapSdk::prettyUnit($unit)
						])
					  ],		
					  'enabled_review'=>$enabled_review,
					  'schema_json' => $schema_json,
			    	));
			    }
			} else $this->render("//store/404-page");		
			
		} catch (Exception $e) {
			error_log("Exception: " . $e->getMessage());
			$this->render("//store/404-page");		
		}
	}
	
}
/*end class*/