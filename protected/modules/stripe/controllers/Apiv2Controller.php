<?php
require 'php-jwt/vendor/autoload.php';
require 'stripe/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\Translation\Dumper\DumperInterface;

class Apiv2Controller extends CController
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

    public function beforeAction($action)
	{							
		$method = Yii::app()->getRequest()->getRequestType();    		
		if($method=="POST"){
			$this->data = Yii::app()->input->xssClean(json_decode(file_get_contents('php://input'), true));
		} else if($method=="GET"){
		   $this->data = Yii::app()->input->xssClean($_GET);				
		} elseif ($method=="OPTIONS" ){
			$this->responseJson();
		} else $this->data = Yii::app()->input->xssClean($_POST);		
		
		return true;
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

    public function actioncreateIntent()
    {
        try {
            
			$key = isset($this->data['key'])?trim($this->data['key']):'';
			$payment_code = isset($this->data['payment_code'])?trim($this->data['payment_code']):'';
            $total = isset($this->data['total'])?$this->data['total']:0;
			$amount = $total;
            $payment_description = isset($this->data['payment_description'])?$this->data['payment_description']:'';
            $currency_code = isset($this->data['currency_code'])?$this->data['currency_code']:'';
			$payment_reference = isset($this->data['payment_reference'])?$this->data['payment_reference']:'';						
			$payment_type = isset($this->data['payment_type'])?$this->data['payment_type']:'';						

			$options = OptionsTools::find([
				'multicurrency_enabled','multicurrency_enabled_checkout_currency'
			]);
			$multicurrency_enabled = isset($options['multicurrency_enabled']) ? ($options['multicurrency_enabled']?$options['multicurrency_enabled']:false) :false;
			$multicurrency_enabled = $multicurrency_enabled==1?true:false;		   	
			$enabled_checkout_currency = isset($options['multicurrency_enabled_checkout_currency'])? ($options['multicurrency_enabled_checkout_currency']?$options['multicurrency_enabled_checkout_currency']:false) :false;
			$enabled_force = $multicurrency_enabled==true? ($enabled_checkout_currency==1?true:false) :false;
			
			if($enabled_force){
				if($force_result = CMulticurrency::getForceCheckoutCurrency($payment_code,$currency_code)){					 					 				   
				   $currency_code = $force_result['to_currency'];
				   $amount = Price_Formatter::convertToRaw($total*$force_result['exchange_rate'],2);
				}
		    } 

            $stripe = new \Stripe\StripeClient($key);
			$paymentIntent = $stripe->paymentIntents->create([
				'amount'=>($amount*100),
				'currency' => $currency_code,				
				'description'=>$payment_description,
				'automatic_payment_methods' => [
					'enabled' => true,
				],
				'metadata'=>[
					'payment_reference'=>$payment_reference,					
					'payment_type'=>$payment_type
				 ]
			]);
			$this->code = 1;
			$this->msg = "Ok";		
			$this->details =[
				'client_secret'=>$paymentIntent->client_secret
			];
        } catch (Exception $e) {
			$this->msg = t($e->getMessage());		
		}		
		$this->responseJson();
    }

} 
// end class