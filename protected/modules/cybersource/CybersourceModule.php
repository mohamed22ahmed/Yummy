<?php
class CybersourceModule extends CWebModule
{	
	public static function paymentCode()
	{
		return 'cybersource';
	}
	
	public function init()
	{
		$this->setImport(array(
            'cybersource.models.*',
			'cybersource.components.*'
		));
	}
		
	public function beforeControllerAction($controller, $action)
	{									
		if(parent::beforeControllerAction($controller, $action))
			return true;
        return false;
	}
	
	public function paymentInstructions()
	{
		return array(
		  'method'=>"online",
		  'redirect'=>''
		);
	}
	
	public function delete($data)
	{		
		AR_payment_method_meta::model()->deleteAll("payment_method_id=:payment_method_id",array(
		  ':payment_method_id'=>$data->payment_method_id
		));		
	}
	
	public function deletePaymentMerchant($data)
	{
		AR_merchant_payment_method::model()->deleteAll("payment_method_id=:payment_method_id",array(
		  ':payment_method_id'=>$data->payment_method_id
		));		
	}
}