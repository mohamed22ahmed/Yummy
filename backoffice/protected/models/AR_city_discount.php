<?php
class AR_city_discount extends CActiveRecord
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
		return '{{city_discount}}';
	}
	
	public function primaryKey()
	{
	    return 'city_discount_id';
	}

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'city' => array(self::BELONGS_TO, 'st_location_cities', 'city_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'city_discount_id' => t('City Discount ID'),
            'city_id' => t('City ID'),
            'discount_percentage' => t('Discount Percentage'),
            'expiration' => t('Expiration'),
            'status' => t('Status')
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(

            array('city_id,discount_percentage,status,expiration',
                'required','message'=> t( Helper_field_required ) ),

            array('city_id', 'numerical', 'integerOnly'=>true),
            array('discount_percentage', 'numerical'),
            array('status, ip_address', 'length', 'max'=>100),
            array('expiration, date_created, date_modified, ip_address', 'safe'),
            array('expiration', 'date', 'format'=>'yyyy-M-d'),

            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('city_discount_id, city_id, discount_percentage, expiration, status, date_created, date_modified, ip_address', 'safe', 'on'=>'search'),
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
