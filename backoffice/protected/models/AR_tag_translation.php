<?php
class AR_tag_translation extends CActiveRecord
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
		return '{{tags_translation}}';
	}
	
	public function primaryKey()
	{
	    return 'id';	 
	}
		
	public function attributeLabels()
	{
		return array(
		    'tag_id'=>t("tag_id"),
		);
	}
	
	public function rules()
	{
		return array(
		  array('tag_id,tag_name',
		  'required','message'=> t( Helper_field_required ) ),
		  		  
		  array('tag_name,language', 'filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
		  
		);
	}

    protected function beforeSave()
	{
		if(parent::beforeSave()){			
			return true;
		} else return true;
	}
	
	protected function afterSave()
	{
		parent::afterSave();
	}

	protected function afterDelete()
	{
		parent::afterDelete();		
	}
		
}
/*end class*/
