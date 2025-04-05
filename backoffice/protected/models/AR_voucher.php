<?php
class AR_voucher extends CActiveRecord
{	
	   				
	public $days_available,$apply_to_merchant,$apply_to_customer,$transaction_type, $merchant_ids;
	
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

    public function relations()
    {
        return array(
            'merchants' => array(self::MANY_MANY, 'AR_merchant', 'st_voucher_merchant(voucher_id, merchant_id)'),
        );
    }
		
	public function attributeLabels()
	{
		return array(		    
		  'voucher_name'=>t("Coupon name"),
		  'max_number_use'=>t("Define max number of use"),
		  'amount'=>t("Amount"),
		  'min_order'=>t("Min Order"),		
		  'up_to'=>t("Up To"),
		  'discount_delivery'=>t("Discount Delivery"),
		  'delivery_cost_payer'=>t("Delivery Cost Payer"),
		  'paying_way_merchant'=>t("Merchant Paying Way"),
		  'yummy_pay_percentage'=>t("Yummy Percentage"),
		  'merchant_pay_percentage'=>t("Merchant Percentage"),
		  'expiration'=>t("Expiration"),
		);
	}
	
	public function rules()
	{
		return array(		  
		   array('voucher_name,voucher_type,amount,days_available,status,expiration', 
		  'required','message'=> t( Helper_field_required ) ),
		  
		  array('voucher_name,voucher_type,apply_to_merchant', 
		  'filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),

		  array('apply_to_merchant,min_order,apply_to_customer,used_once,
		  max_number_use,transaction_type,visible,merchant_ids,
		  discount_delivery,delivery_cost_payer,paying_way_merchant,
		  yummy_pay_percentage,merchant_pay_percentage','safe'),
		  
		  array('expiration', 'date', 'format'=>'yyyy-M-d'),
		  
		  array('amount', 'numerical', 'integerOnly' => false,
		    'min'=>1,
		    'tooSmall'=>t(Helper_field_numeric_tooSmall),
		    'message'=>t(Helper_field_numeric)),
		    
		  array('amount,min_order,up_to', 'numerical', 'integerOnly' => false,
		    'message'=>t(Helper_field_numeric)),
		    
		  array('max_number_use,delivery_cost_payer,paying_way_merchant,yummy_pay_percentage,merchant_pay_percentage', 'numerical', 'integerOnly' => true,
		    'message'=>t(Helper_field_numeric)),  
		    
		  array('voucher_name','unique','message'=>t(Helper_field_unique))
		    
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
				
		$this->removeCoupon($this->voucher_id);
		if(is_array($this->transaction_type) && count($this->transaction_type)>=1){
			//dump($this->transaction_type);die();
			$params = [];			
			foreach ($this->transaction_type as $items) {
				$params[] = [
					'merchant_id'=>$this->merchant_id,
					'meta_name'=>"coupon",
					'meta_value'=>$this->voucher_id,
					'meta_value1'=>$items,
				];
			}
			$builder=Yii::app()->db->schema->commandBuilder;
			$command=$builder->createMultipleInsertCommand("{{merchant_meta}}",$params);
			$command->execute();
		}		
		
		/*ADD CACHE REFERENCE*/
		CCacheData::add();						
	}

	protected function afterDelete()
	{
		parent::afterDelete();		
		/*ADD CACHE REFERENCE*/
		CCacheData::add();
	}

	public function removeCoupon($voucher_id='')
    {
        $criteria = new CDbCriteria();
        $criteria->condition = "meta_name=:meta_name AND meta_value=:meta_value";
        $criteria->params = [
			':meta_name'=>'coupon',
			':meta_value'=>$voucher_id
		];    
        AR_merchant_meta::model()->deleteAll($criteria);        
    }
		
}
/*end class*/
