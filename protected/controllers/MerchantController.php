<?php
class MerchantController extends SiteCommon
{
	
	public function beforeAction($action)
	{				
				
		// SEO 
		CSeo::setPage();
		return true;
	}

	public function actionIndex()
	{
	    $this->redirect(array('/merchant/signup'));
	}
	
	public function actionsignup()
	{		
		$country_params = AttributesTools::getSetSpecificCountryArray();
		$terms = Yii::app()->params['settings']['registration_terms_condition'];
		$enabled = Yii::app()->params['settings']['merchant_enabled_registration'];
		$website_title = isset(Yii::app()->params['settings']['website_title'])?Yii::app()->params['settings']['website_title']:'';
		
		if($enabled<=0){
			$this->render("//store/404-page");
			Yii::app()->end();
		}
				
		$options = OptionsTools::find(array('captcha_site_key','merchant_enabled_registration_capcha','mobilephone_settings_country','mobilephone_settings_default_country','captcha_lang'));
		$capcha = isset($options['merchant_enabled_registration_capcha'])?$options['merchant_enabled_registration_capcha']:'';
        $capcha = $capcha==1?true:false;                
        $captcha_site_key = isset($options['captcha_site_key'])?$options['captcha_site_key']:'';
		$captcha_lang = isset($options['captcha_lang'])?$options['captcha_lang']:'en'; 
        
        $phone_default_country = isset($options['mobilephone_settings_default_country'])?$options['mobilephone_settings_default_country']:'us';
        $phone_country_list = isset($options['mobilephone_settings_country'])?$options['mobilephone_settings_country']:'';
        $phone_country_list = !empty($phone_country_list)?json_decode($phone_country_list,true):array();        

		$multicurrency_enabled = isset(Yii::app()->params['settings']['multicurrency_enabled'])?Yii::app()->params['settings']['multicurrency_enabled']:false;
        $multicurrency_enabled = $multicurrency_enabled==1?true:false;							
		
		$this->render('merchant-signup',array(
		  'country_params'=>$country_params,
		  'terms'=>Yii::app()->input->xssClean($terms),
		  'capcha'=>$capcha,
		  'captcha_site_key'=>$captcha_site_key,
		  'phone_country_list'=>$phone_country_list,
		  'phone_default_country'=>$phone_default_country,
		  'captcha_lang'=>$captcha_lang,
		  'website_title'=>$website_title,
		  'multicurrency_enabled'=>$multicurrency_enabled
		));
	}
	
	public function actionusersignup()
	{
		try {
			
		   $country_params = AttributesTools::getSetSpecificCountryArray();
		   $terms = Yii::app()->params['settings']['registration_terms_condition'];
			
		   $merchant_uuid = Yii::app()->input->get('uuid');
		   $merchant = CMerchants::getByUUID($merchant_uuid);		
		   
		   if($merchant->status=="pending"){		   
			   ScriptUtility::registerScript(array(
				  "var _merchant_uuid='".CJavaScript::quote($merchant_uuid)."';",			  
			   ),'merchant_uuid');
			   
			   $options = OptionsTools::find(array('mobilephone_settings_country','mobilephone_settings_default_country'));		
			   $phone_default_country = isset($options['mobilephone_settings_default_country'])?$options['mobilephone_settings_default_country']:'us';
		       $phone_country_list = isset($options['mobilephone_settings_country'])?$options['mobilephone_settings_country']:'';
		       $phone_country_list = !empty($phone_country_list)?json_decode($phone_country_list,true):array();        
						   
			   $this->render('merchant-user',array(		     
			     'country_params'=>$country_params,
			     'terms'=>Yii::app()->input->xssClean($terms),
			     'phone_country_list'=>$phone_country_list,
		         'phone_default_country'=>$phone_default_country
			   ));
		   } else $this->render("//store/404-page");
		
		} catch (Exception $e) {		    
		    $this->render("//store/404-page");
		}
	}
	
	public function actiongetbacktoyou()
	{
		$this->render('back-to-you');
	}
	
	public function actionchoose_plan()
	{
		
		$merchant_uuid = Yii::app()->input->get('uuid');		
		$model = AR_merchant::model()->find('merchant_uuid=:merchant_uuid', 
		array(':merchant_uuid'=>$merchant_uuid)); 	
		
		if($model){					
			CommonUtility::WriteCookie("merchant_uuid_plan",$merchant_uuid);
			$this->render('choose-plan');
		} else $this->render("//store/404-page");
	}

	public function actionpayment_plan()
	{
		$merchant_uuid = CommonUtility::getCookie('merchant_uuid_plan');
		$model = AR_merchant::model()->find('merchant_uuid=:merchant_uuid',
		array(':merchant_uuid'=>$merchant_uuid));
		if($model){

			$plan_uuid = Yii::app()->input->get('id');
			ScriptUtility::registerScript(array(
				"var plan_merchant_uuid='".CJavaScript::quote($merchant_uuid)."';",
				"var plan_uuid='".CJavaScript::quote($plan_uuid)."';",
			),'plan_registration');

			try {
				$payments = AttributesTools::PaymentPlansProvider();
				$payments_credentials = CPayments::getPaymentCredentials(0,'',0);
				CComponentsManager::RegisterBundle($payments ,'plans-');
				$this->render('payment-plan',[
					'payments'=>$payments,
					'payments_credentials'=>$payments_credentials
				]);
			} catch (Exception $e) {
				$this->render("//store/404-page");
			}
	    } else $this->render("//store/404-page");
	}
	
	public function actionthankyou()
	{
		$this->render('thank-you');
	}
	
	public function actionpaymentprocessing()
	{
		$this->render('payment-processing');
	}
	
	public function actionsignupfailed()
	{
		$this->render('signup-failed');
	}
	
	public function actioncashin()
	{
		try {
			
			$payments = array(); $payments_credentials = array();
			
			$merchant_uuid = Yii::app()->input->get('uuid'); 
			$amount = floatval(Yii::app()->input->get('amount')); 
			$merchant = CMerchants::getByUUID($merchant_uuid);
			$merchant_id = $merchant->merchant_id;			
			
			$base_currency = Price_Formatter::$number_format['currency_code'];
			$attr = OptionsTools::find(['merchant_default_currency'],$merchant_id);
			$merchant_base_currency = isset($attr['merchant_default_currency'])? (!empty($attr['merchant_default_currency'])?$attr['merchant_default_currency']:$base_currency) :$base_currency;
			
			Price_Formatter::init($merchant_base_currency);
						
			try {								
				if($payments = CPayments::getPaymentList(1,'Merchant',$merchant_uuid)){						
					$payments_credentials = CPayments::getPaymentCredentials($merchant_id,'',2);					
					CComponentsManager::RegisterBundle($payments);
				}
			} catch (Exception $e) {
			    //
			}	
			
			$this->render("cashin",array(
			  'merchant_uuid'=>$merchant_uuid,
			  'payments'=>$payments,
			  'payments_credentials'=>$payments_credentials,
			  'amount'=>$amount,
			  'back_link'=>CMedia::homeUrl()."/".BACKOFFICE_FOLDER."/commission/statement",
			));
			
		} catch (Exception $e) {				 
		    $this->render("//store/404-page");
		}
	}
	
	public function actioncashin_successful()
	{
		$this->render('cashin-thankyou',array(
		  'back_link'=>CMedia::homeUrl()."/".BACKOFFICE_FOLDER."/commission/statement",
		));
	}

	public function actionconfirm_account()
	{
		$this->pageTitle = t("Confirm your account");		
		$uuid = Yii::app()->input->get("uuid");
		try {	
			$merchant = CMerchants::getByUUID($uuid);			
			if($merchant->status!="active"){
				$merchant->scenario = "update_status";
				$merchant->status = "active";
				$merchant->save();
			}
			$this->render('thank-you');
		} catch (Exception $e) {            
			$this->render("//store/404-page");
        }		
	}
	
}
/*end class*/