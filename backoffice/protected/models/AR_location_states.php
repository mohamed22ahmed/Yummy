<?php
class AR_location_states extends CActiveRecord
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
		return '{{location_states}}';
	}
	
	public function primaryKey()
	{
	    return 'state_id';	 
	}
		
	public function attributeLabels()
	{
		return array(
		    'state_id'=>t("state_id"),		    			
		);
	}
	
	public function rules()
	{
		return array(
		  array('name,country_id', 
		  'required','message'=> t( Helper_field_required ) ),
		  
		);
	}

    protected function beforeSave()
	{
		if(!parent::beforeSave()){
			return false;
		} 
		
		return true;
	}
	
	protected function afterSave()
	{
		if(!parent::afterSave()){
			return false;
		}
		
		/*ADD CACHE REFERENCE*/
		CCacheData::add();
	}

	protected function afterDelete()
	{
		if(!parent::afterDelete()){
			return false;
		}
		
		/*ADD CACHE REFERENCE*/
		CCacheData::add();
	}
		
}
/*end class*/
