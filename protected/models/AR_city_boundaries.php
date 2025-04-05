<?php
class AR_city_boundaries extends CActiveRecord
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
		return '{{city_boundaries}}';
	}
	
	public function primaryKey()
	{
	    return 'id';
	}
		
	public function attributeLabels()
	{
		return array(
            'id' => 'ID',
            'city_id' => 'City ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
		);
	}
	
	public function rules()
	{
		return array(

            array('city_id, latitude, longitude', 'required'),
            array('city_id', 'numerical', 'integerOnly' => true),
            array('latitude, longitude', 'numerical'),
		);
	}
		
}
/*end class*/
