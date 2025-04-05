<?php
class AR_voucher_new extends CActiveRecord
{	

	public $total_used, $merchant_ids;
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
		return '{{voucher_new}}';
	}
	
	public function primaryKey()
	{
	    return 'voucher_id';	 
	}
		
	public function attributeLabels()
	{
		return array(
		    'voucher_id'=>t("voucher_id"),		    
		);
	}

    public function relations()
    {
        return array(
            'merchants' => array(self::MANY_MANY, 'AR_merchant', 'st_voucher_merchant(voucher_id, merchant_id)'),
        );
    }
	
	public function rules()
	{
		return array(
		  array('voucher_owner,merchant_id,voucher_name,voucher_type,amount', 
		  'required','message'=> t( Helper_field_required ) ),
		  		  
		  array('voucher_name,
		  status', 'filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),

        array('merchant_ids','safe'),
        );
	}

    protected function beforeSave()
	{
		if(parent::beforeSave()){
			if($this->isNewRecord){
				$this->date_created = CommonUtility::dateNow();					
			} else {
				$this->date_modified = CommonUtility::dateNow();											
			}
			$this->ip_address = CommonUtility::userIp();	
			
			return true;
		} else return true;
	}
	
	protected function afterSave()
	{
		parent::afterSave();
		/*ADD CACHE REFERENCE*/
		CCacheData::add();
	}

	protected function afterDelete()
	{
		parent::afterDelete();		
		/*ADD CACHE REFERENCE*/
		CCacheData::add();
	}
		
}
/*end class*/
