<?php
class AR_services_fee extends CActiveRecord
{	
	   				
	/**
	 * Returns the static model of the specified AR class.
	 * @return static the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{services_fee}}';
	}
	
	public function primaryKey()
	{
	    return 'id';	 
	}
		
	public function attributeLabels()
	{
		return array(
		    'service_id'=>t("service_id"),
		    'merchant_id'=>t("merchant_id"),
		    'service_fee'=>t("service_fee"),
			'charge_type'=>t("Charge type"),
			'small_order_fee'=>t("Small order fee"),
			'small_less_order_based'=>t("Less than"),
		);
	}
	
	public function rules()
	{
		return array(
		  array('service_id,merchant_id,service_fee', 
		  'required','message'=> t( Helper_field_required ) ),
		  		  
		  array('service_fee,small_order_fee,small_less_order_based', 'numerical', 'integerOnly' => false,		  
		  'message'=>t(Helper_field_numeric)),
		  
		  array('date_modified,charge_type','safe')
		);
	}

    protected function beforeSave()
	{
		if(parent::beforeSave()){
			$this->date_modified = CommonUtility::dateNow();	
			return true;
		} else return true;
	}
	
	protected function afterSave()
	{
		parent::afterSave();		
		CCacheData::add();
	}

	protected function afterDelete()
	{
		parent::afterDelete();			
		CCacheData::add();	
	}
		
}
/*end class*/
