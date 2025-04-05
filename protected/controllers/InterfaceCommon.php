<?php
class InterfaceCommon extends CController
{	
	public $code=2,$msg,$details,$data;
	    
	public function __construct($id,$module=null){
		parent::__construct($id,$module);				
		// Set the application language if provided by GET, session or cookie
		if(isset($_GET['language'])) {
			Yii::app()->language = $_GET['language'];
			Yii::app()->user->setState('language', $_GET['language']); 
			$cookie = new CHttpCookie('language', $_GET['language']);
			$cookie->expire = time() + (60*60*24*365); // (1 year)
			Yii::app()->request->cookies['language'] = $cookie; 
		} else if (Yii::app()->user->hasState('language')){
			Yii::app()->language = Yii::app()->user->getState('language');			
		} else if(isset(Yii::app()->request->cookies['language'])){
			Yii::app()->language = Yii::app()->request->cookies['language']->value;			
			if(!empty(Yii::app()->language) && strlen(Yii::app()->language)>=10){
				Yii::app()->language = KMRS_DEFAULT_LANGUAGE;
			}
		} else {
			$options = OptionsTools::find(['default_language']);
			$default_language = isset($options['default_language'])?$options['default_language']:'';			
			if(!empty($default_language)){
				Yii::app()->language = $default_language;
			} else Yii::app()->language = KMRS_DEFAULT_LANGUAGE;
		}
	}
	
	public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
	{						
		return array(
			array('deny',			
                 'actions'=>array(
                     'registerUser','userLogin','getAccountStatus','verifyCodeSignup','getlocationAutocomplete','getLocationDetails',
					 'reverseGeocoding','addressAtttibues','validateCoordinates','requestResetPassword','resendResetEmail','getMenuItem',
					 'searchAttributes','getMerchantFeed','CuisineList','TransactionInfo','getDeliveryDetails','geStoreMenu','servicesList',
					 'getCart','menuSearch','getMapconfig','getMoneyConfig','getBanner','Search','socialRegistration','requestCode','getRegSettings',
					 'completeSocialSignup','registerDevice','authenticate','checkStoreOpen','checkStoreOpen2','searchItems','getMerchantInfo','SimilarItems',
					 "getAttributes","userLoginPhone",'getDeliveryTimes', 'cityBoundaries',
                     'getLocationStates', 'getLocationCities', 'geStoreCategory', 'geStoreItems', 'getMerchantTypes'
                 ),
				 'expression' => array('AppIdentity','verifyToken')
			 ), 
             array('deny',				
                  'actions'=>array(
                    'saveClientAddress','clientAddresses','deleteAddress','checkoutAddress','getPhone',
                    'RequestEmailCode','verifyCode','ChangePhone','applyPromo','removePromo','applyPromoCode',
                    'checkoutAddTips','PaymentList','SavedPaymentProvider','SavedPaymentList',
                    'SetDefaultPayment','deleteSavedPaymentMethod','savedCards','','PlaceOrder',
                    'getOrder','orderHistory','orderDetails','uploadReview','addReview','getProfile','saveProfile',
                    'updatePassword','getAddresses','MyPayments','deletePayment','PaymentMethod','addTofav',
                    'getsaveitems','getCartCheckout','getRealtime','SavePlaceByID','orderBuyAgain',
					'StripePaymentIntent','paypalverifypayment','razorpaycreatecustomer','razorpaycreateorder','razorpayverifypayment',
					'mercadopagocustomer','mercadopagoaddcard','mercadopagogetcard','mercadopagocapturepayment','getMenuItem2','saveStoreList',
					'SaveStore','requestData','verifyAccountDelete','deleteAccount','saveSettings','getSettings','getNotification','deleteNotification',
					'updateDevice','StripeCreateCustomer','CybersourcePaymentRender','StripeSavePayment','getMerchantInfo2','getItemFavorites','updateAvatar','Updateaccountnotification',
					'Updateaccountpromonotification','orderDeliveryDetails','deleteAllNotification','getMerchantFeed2','deleteNotifications','getPointsTransaction',
					'getAvailablePoints','getPointsTransactionMerchant','getCartpoints','applyPoints','removePoints','savedPaydelivery','getBankDeposit',
					'uploadBankDeposit','SaveAddress','getwalletbalance','getWalletTransaction','getPointsthresholds','getCustomerDefaultPayment',
					'prepareaddfunds','getPaymentCredentials','getCartWallet','applyDigitalWallet'
                 ), 
				 'expression' => array('AppIdentity','verifyCustomer')
			 ), 
		 );
	}	   

	public function responseJson()
    {
		header("Access-Control-Allow-Origin: *");          
        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    	header('Content-type: application/json'); 
		$resp=array('code'=>$this->code,'msg'=>$this->msg,'details'=>$this->details);
		echo CJSON::encode($resp);
		Yii::app()->end();
    } 
	
	public function initSettings()
	{	
		$settings = OptionsTools::find(array(
			'website_date_format_new','website_time_format_new','home_search_unit_type','website_timezone_new',
			'captcha_customer_signup','image_resizing','merchant_specific_country','map_provider','google_geo_api_key','mapbox_access_token',
			'signup_enabled_verification','mobilephone_settings_default_country','mobilephone_settings_country','website_title','fb_flag','google_login_enabled',
			'enabled_language_customer_app','multicurrency_enabled','multicurrency_enabled_hide_payment','multicurrency_enabled_checkout_currency',
			'points_enabled','points_earning_rule','points_earning_points','points_minimum_purchase','points_maximum_purchase','captcha_site_key','captcha_secret',
			'captcha_lang','admin_addons_use_checkbox','admin_category_use_slide','site_user_avatar','site_merchant_avatar','site_food_avatar','default_location_lat',
			'default_location_lng','digitalwallet_enabled','digitalwallet_enabled_topup',
			'digitalwallet_topup_minimum','digitalwallet_topup_maximum','digitalwallet_transaction_limit','digitalwallet_refund_to_wallet',
			'chat_enabled','chat_enabled_merchant_delete_chat','points_use_thresholds','points_expiry','enabled_include_utensils',
			'android_download_url','ios_download_url','mobile_app_version_android','mobile_app_version_ios'
	    ));		
	    
		Yii::app()->params['settings'] = $settings;

		/*SET TIMEZONE*/
		$timezone = Yii::app()->params['settings']['website_timezone_new'];		
		if (is_string($timezone) && strlen($timezone) > 0){
		   Yii::app()->timeZone=$timezone;		   
		}
		Price_Formatter::init();			
	}

}
// end class