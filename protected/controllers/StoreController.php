<?php
class StoreController extends SiteCommon
{
	public function beforeAction($action)
	{						
		$settings = Yii::app()->params['settings'];		
		$enabled_google_analytics = isset($settings['enabled_google_analytics'])?$settings['enabled_google_analytics']:false;
		$google_analytics_tracking_id = isset($settings['google_analytics_tracking_id'])?$settings['google_analytics_tracking_id']:'';		

		$enabled_fb_pixel = isset($settings['enabled_fb_pixel'])?$settings['enabled_fb_pixel']:false;		
		$fb_pixel_id = isset($settings['fb_pixel_id'])?$settings['fb_pixel_id']:'';		

		$cs = Yii::app()->getClientScript();			

		if($enabled_google_analytics==1 && !empty($google_analytics_tracking_id)){			
			Yii::app()->clientScript->registerScriptFile('https://www.googletagmanager.com/gtag/js?id='.$google_analytics_tracking_id,CClientScript::POS_HEAD,[
				'async'=>true,				
			]);	
			ScriptUtility::registerScript(array(
				"window.dataLayer = window.dataLayer || [];",
				"function gtag(){dataLayer.push(arguments);}",
				"gtag('js', new Date());",
				"gtag('config', '".$google_analytics_tracking_id."');"
			),'googleAnalytics');								  
		}

		if($enabled_fb_pixel==1 && !empty($fb_pixel_id)){			
			$cs->registerScript(
				'facebookPixels',
				"
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '".$fb_pixel_id."');
fbq('track', 'PageView');
				",
				CClientScript::POS_HEAD
			  );		
		}


		// CHECK IF PWA IS ENABLED AND REDIRECT
		$enabled_pwa = isset(Yii::app()->params['settings']['enabled_auto_pwa_redirect'])?Yii::app()->params['settings']['enabled_auto_pwa_redirect']:'';		
		$pwa_url = isset(Yii::app()->params['settings']['pwa_url'])?Yii::app()->params['settings']['pwa_url']:'';				
		if($enabled_pwa==1 && !empty($pwa_url) && Yii::app()->params['isMobile']==1 ){						
			$this->redirect($pwa_url);
			Yii::app()->end();
		}		
		
		// SEO 
		
		//CSeo::setPage();

		return true;
	}			  
	
	public function actionIndex()
	{							
		CSeo::setPage();
		AssetsFrontBundle::includeMaps();
		ScriptUtility::registerScript(array(			
			"var isGuest='".CJavaScript::quote(Yii::app()->user->isGuest)."';",		
			"var next_url='".CJavaScript::quote(Yii::app()->createAbsoluteUrl('store/restaurants'))."';",
		  ),'is_guest');

	    $local_id = CommonUtility::getCookie(Yii::app()->params->local_id);		
		$setttings = Yii::app()->params['settings'];
		$allow_return_home = isset($setttings['allow_return_home'])?$setttings['allow_return_home']:false;
		$allow_return_home = $allow_return_home==1?true:false;
		$android_download_url = isset($setttings['android_download_url'])?$setttings['android_download_url']:'#';
		$ios_download_url = isset($setttings['ios_download_url'])?$setttings['ios_download_url']:'#';

		$enabled_auto_detect_address = isset($setttings['enabled_auto_detect_address'])?$setttings['enabled_auto_detect_address']:false;		
		$enabled_auto_detect_address = $enabled_auto_detect_address==1?true:false;		
		  
		$criteria = new CDbCriteria();
		$criteria->distinct = true;
		$criteria->select = 'city';
		$criteria->addCondition("city IS NOT NULL AND city <> ''");
		$merchants = AR_merchant::model()->findAll($criteria);
		
		// Convert results to an array of city names.
		$cities = array();
		foreach ($merchants as $merchant) {
			$cities[] = $merchant->city;
		}

	    if(!empty($local_id) && $allow_return_home==false){			 
	    	 $this->redirect(array('/store/restaurants'));
	    	 Yii::app()->end();
	    }		
		$place_details = [];
		try {
			$place_details = MapSdk::placeDetails($local_id);		
		} catch (Exception $e) {
		}

		if (empty($place_details)) {
			$address = [];
		} else {
			$address = [
				'@type'           => 'PostalAddress',
				'addressCountry'  => isset($place_details['address']['country']) && $place_details['address']['country'] 
									? $place_details['address']['country'] 
									: 'Palestine', // fallback country if not provided
				'addressLocality' => isset($place_details['address']['address1']) && $place_details['address']['address1']
									? $place_details['address']['address1']
									: '', // fallback locality if not provided
				// You can add more properties such as 'streetAddress', 'postalCode', etc.
			];
		}
		$model = PPages::pageDetailsSlug(23 , Yii::app()->language , "a.page_id" );    
		$schema = [
			'@context'    => 'https://schema.org',
			'@type'       => 'OnlineStore',
			'name'        => $model->meta_title,
			'legalName'   => 'yummy',
			'address'     => $address,
			'description' => $model->meta_description,
			'areaServed'  => $address,
			'url'         => Yii::app()->createAbsoluteUrl('/restaurants'),
			'image'       => $model->image,
			'brand'       => [
				'@type' => 'Brand',
				'name'  => 'Yummy',  // Replace with your brand name or dynamic value if available
				"logo"  =>  'https://devyum.yummy-app.com/upload/all/8561d109-ad49-11ee-b244-6045bdf13a52.png'			
			],
			'logo'        => 'https://devyum.yummy-app.com/upload/all/8561d109-ad49-11ee-b244-6045bdf13a52.png'
		];
		// Convert the schema array to a JSON string
		$schema_json = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		$this->render("index",[
			'responsive'=>AttributesTools::FrontCarouselResponsiveSettings('full'),
			'android_download_url'=>$android_download_url,
			'ios_download_url'=>$ios_download_url,
			'enabled_auto_detect_address'=>$enabled_auto_detect_address,
			'cities' => $cities,
			'schema_json' => $schema_json,
		]);
	}	
	
	public function actionRestaurants()
	{
		$local_id = CommonUtility::getCookie(Yii::app()->params->local_id);
		if(empty($local_id)){
			 $this->redirect(array('/'));
			 Yii::app()->end();
		}		

		MapSdk::$map_provider = Yii::app()->params['settings']['map_provider'];		   
		MapSdk::setKeys(array(
			'google.maps'=>Yii::app()->params['settings']['google_geo_api_key'],
			'mapbox'=>Yii::app()->params['settings']['mapbox_access_token'],
		));

		ScriptUtility::registerScript(array(			
			"var isGuest='".CJavaScript::quote(Yii::app()->user->isGuest)."';",		
		  ),'is_guest');

		$place_details = [];
		try {
			$place_details = MapSdk::placeDetails($local_id);		
		} catch (Exception $e) {
		}

		AssetsFrontBundle::includeMaps();
		
	
		$model = PPages::pageDetailsSlug(24 , Yii::app()->language , "a.page_id" ); 
		if (isset($model->meta_title) && strpos($model->meta_title, '{city}') !== false) {
			// Access the address value from place_details array properly
			$address_value = isset($place_details['address']['address1']) ? $place_details['address']['address1'] : '';
			// Replace "{address}" with the actual address
			$model->meta_title = str_replace('{city}', $address_value, $model->meta_title);
			$model->meta_description  = str_replace('{city}', $address_value, $model->meta_description);
			// Set SEO with the updated meta_title
			CommonUtility::setSEO(
				$model->meta_title,
				$model->meta_title,
				$model->meta_description,
				$model->meta_keywords,
				$model->image
			);
		} else {
			CSeo::setPage();
		}
		$address = [
			'@type'           => 'PostalAddress',
			'addressCountry'  => isset($place_details['address']['country']) && $place_details['address']['country'] 
								 ? $place_details['address']['country'] 
								 : 'Palestine', // fallback country if not provided
			'addressLocality' => isset($place_details['address']['address1']) && $place_details['address']['address1']
								 ? $place_details['address']['address1']
								 : '', // fallback locality if not provided
			// You can add more properties such as 'streetAddress', 'postalCode', etc., if available.
		];

		$schema = [
			'@context'    => 'https://schema.org',
			'@type'       => 'OnlineStore',
			'name'        => $model->meta_title,
			'legalName'   => 'yummy',
			'address'     => $address,
			'description' => $model->meta_description,
			'areaServed'  => $place_details['address']['address1'],
			'url'         => Yii::app()->createAbsoluteUrl('/restaurants'),
			'image'       => $model->image,
			'brand'       => [
				'@type' => 'Brand',
				'name'  => 'Yummy',  // Replace with your brand name or dynamic value if available
				"logo"  =>  'https://devyum.yummy-app.com/upload/all/8561d109-ad49-11ee-b244-6045bdf13a52.png'			
			],
			'logo'        => 'https://devyum.yummy-app.com/upload/all/8561d109-ad49-11ee-b244-6045bdf13a52.png'
		];
		// Convert the schema array to a JSON string
		$schema_json = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		// Register the JSON-LD script in the head section of the HTML output
		Yii::app()->clientScript->registerScript(
			'schema',
			$schema_json,  // Note: no <script> tags here.
			CClientScript::POS_HEAD
		);
		$this->render('feed',array(
			'responsive'=>AttributesTools::FrontCarouselResponsiveSettings('full'), 
			'tabs_suggestion'=>AttributesTools::suggestionTabs(),
			'place_details'=>$place_details,
			'schema_json' => $schema_json
		));
	}
	
	public function actionFeed()
	{
		$this->render('feed');
	}
	
	public function actionmenu()
	{
		$this->render('menu');
		//$this->render('menu-1');
	}
	
	public function actioncheckout()
	{
		$this->render('checkout');
		//$this->render('checkout-1');
	}	
	
	public function actionreceipt()
	{
		$this->render('receipt');
	}	
		
	public function actionoffers()
	{
		$this->render('offers');
	}	
	
	public function actionpagenotfound()
	{
		$this->render('404-page');
	}	
	
	public function actionregister()
	{
		$this->render('register');
	}

	public function actioncuisine()
	{
		$pathInfo = Yii::app()->request->getPathInfo();		
		$matches = explode('/', $pathInfo);					
		if(is_array($matches) && count($matches)>=1){			
			$slug = isset($matches[1])?$matches[1]:'';             			
			$dependency = CCacheData::dependency();
			$model = AR_cuisine::model()->cache(Yii::app()->params->cache, $dependency)->find('slug=:slug AND status=:status',[
				':slug'=>trim($slug), ':status'=>'publish'
			]); 
			
			$page = PPages::pageDetailsSlug(26 , Yii::app()->language , "a.page_id" ); 
			if (isset($page->meta_title) && strpos($page->meta_title, '{Cuisine}') !== false) {
				// Access the address value from place_details array properly
				// Replace "{address}" with the actual address
				$page->meta_title = str_replace('{Cuisine}', $slug, $page->meta_title);
				$page->meta_description  = str_replace('{Cuisine}', $slug, $page->meta_description);
				// Set SEO with the updated meta_title
				CommonUtility::setSEO(
					$page->meta_title,
					$page->meta_title,
					$page->meta_description,
					$page->meta_keywords,
					$page->image
				);
			} else {
				CSeo::setPage();
			}
			if($model){				
				$cuisine_filter = json_encode(array($model->cuisine_id));
				ScriptUtility::registerScript(array(
					"var cuisine_filter='".CJavaScript::quote($cuisine_filter)."';",					  
				),'cuisine_filter');	
				$this->render('//store/feed_cuisine',array(
					'model'=>$model,					
				));
			} else $this->render("//store/404-page");
		} else $this->render("//store/404-page");		
	}
	
	public function actioncontactus()
	{
		
		$options = array('enabled_contact_form','contact_email_receiver','contact_field','contact_content','contact_enabled_captcha','captcha_site_key','captcha_lang');		

		$data = OptionsTools::find($options);
		$enabled_contact_form = isset($data['enabled_contact_form'])?$data['enabled_contact_form']:false;
		$enabled_contact_form = $enabled_contact_form==1?true:false;
		$contact_content = isset($data['contact_content'])?$data['contact_content']:'';
		$contact_email_receiver = isset($data['contact_email_receiver'])?$data['contact_email_receiver']:'';
		$contact_field = isset($data['contact_field'])?$data['contact_field']:false;
		$contact_field = !empty($contact_field)?json_decode($contact_field,true):false;				
		$contact_enabled_captcha = isset($data['contact_enabled_captcha'])?$data['contact_enabled_captcha']:false;
		$contact_enabled_captcha = $contact_enabled_captcha==1?true:false;
		$captcha_site_key = isset($data['captcha_site_key'])?$data['captcha_site_key']:'';		
		$captcha_lang = isset($data['captcha_lang'])?$data['captcha_lang']:'en'; 

		if(!$enabled_contact_form){
			$this->render("404-page");
			Yii::app()->end();
		}
		
		$model = new AR_contact();

		if(isset($_POST['AR_contact'])){			
			$model->attributes=$_POST['AR_contact'];			
			$model->capcha = $contact_enabled_captcha;
			$model->recaptcha_response = isset($_POST['recaptcha_response'])?$_POST['recaptcha_response']:'';			
			if($model->validate()){						
				$model->receiver_email_address = $contact_email_receiver;
				if($model->save()){
					Yii::app()->user->setFlash('success',t("Your request has been sent."));
					$this->refresh();
				} else Yii::app()->user->setFlash('error', CommonUtility::parseModelErrorToString( $model->getErrors(),"<br/>" ));
			} else Yii::app()->user->setFlash('error', CommonUtility::parseModelErrorToString( $model->getErrors(),"<br/>" ));
		}

		$this->render('contact_us',[
			'model'=>$model,
			'contact_field'=>$contact_field,
			'contact_content'=>$contact_content,
			'responsive'=>AttributesTools::FrontCarouselResponsiveSettings('full'), 
			'enabled_captcha'=>$contact_enabled_captcha,
			'captcha_site_key'=>$captcha_site_key,
			'captcha_lang'=>$captcha_lang
		]);
	}

	public function actionsearch()
	{
		$this->render("search");
	}
		
}
/*end class*/