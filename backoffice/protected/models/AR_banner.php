<?php
class AR_banner extends CActiveRecord
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
		return '{{banner}}';
	}
	
	public function primaryKey()
	{
	    return 'banner_id';	 
	}
		
	public function attributeLabels()
	{
		return array(
		    'banner_id'=>t("ID"),
            'title'=>t("Title"),
            'banner_type'=>t("Type"),
            'status	'=>t("Status"),
			'sequence'=>t("Sequence")
		);
	}
	
	public function rules()
	{
		return array(
		  array('title,banner_type', 
		  'required','message'=> t( Helper_field_required ) ),		 

		  array('photo', 
		  'required','message'=> t("Image is required") ),		 

		  array('status', 'numerical', 'integerOnly' => true,		  
		  'message'=>t(Helper_field_numeric)),

		  array('meta_value2', 'numerical', 'integerOnly' => true,
			'min'=>1,		  
			'tooSmall'=>t("Item is required"),		  
			'message'=>t("Item is required"),
			'on'=>"merchant_banner,food_banner"
		 ),

		  array('meta_value1', 'numerical', 'integerOnly' => true,		   
		   'min'=>1,		  
		   'tooSmall'=>t("Merchant is required"),		  
		   'message'=>t("Merchant is required"),
		   'on'=>"admin_banner"	   
		  ),

		  array('meta_value3', 'required',
			 'on'=>"admin_featured"
		  ),

		  array('meta_value4', 'required',
			 'on'=>"admin_cuisine"
		  ),
		  		  
		  array('title', 'filter','filter'=>array($obj=new CHtmlPurifier(),'purify')), 		  
		  
		  array('date_created,date_modified,ip_address,sequence,meta_value1,meta_value2,meta_value3,meta_value4','safe'),		  
		  
		);
	}

    protected function beforeSave()
	{
		if(parent::beforeSave()){

			if(DEMO_MODE && in_array($this->meta_value1,DEMO_MERCHANT)){				
				return false;
			}

			if($this->isNewRecord){
				$this->date_created = CommonUtility::dateNow();					
			} else {
				$this->date_modified = CommonUtility::dateNow();											
			}

			if(empty($this->banner_uuid)){
				$this->banner_uuid = CommonUtility::createUUID("{{banner}}",'banner_uuid');
			}

			if($this->scenario=="food_banner"){				
				$model_item = AR_item::model()->find("item_id=:item_id",[
					':item_id'=>$this->meta_value2
				]);
				if($model_item){
					$this->meta_value1 = $model_item->merchant_id;
				}				
			}
			
			$this->ip_address = CommonUtility::userIp();				
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

	protected function beforeDelete()
	{				
	    if(DEMO_MODE && in_array($this->meta_value1,DEMO_MERCHANT)){
		    return false;
		}
	    return true;
	}
		
}
/*end class*/