<?php
class cybersourceComponents extends CWidget
{
	public $data;
	public $credentials;

	public function run() {
		$this->render('cybersource-components',array(
		  'payment_code'=>$this->data['payment_code'],
		  'credentials'=>$this->credentials,
		  'prefix'=>isset($this->data['prefix'])?$this->data['prefix']:'',
		  'reference'=>isset($this->data['reference'])?$this->data['reference']:''
		));
	}
}