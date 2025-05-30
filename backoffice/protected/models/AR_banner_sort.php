<?php
class AR_banner_sort extends CActiveRecord
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
		  		  
		  array('title', 'filter','filter'=>array($obj=new CHtmlPurifier(),'purify')), 		  
		  
		  array('date_created,date_modified,ip_address,sequence,meta_value1,meta_value2,meta_value3','safe'),		  
		  
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