<?php
class AR_distance_cache extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{distance_cache}}';
    }

    public function primaryKey()
    {
        return 'id';
    }

    public function rules()
    {
        return array(
            array('client_id, from_lat, from_lng, to_lat, to_lng, distance_covered, cached_date', 'required'),
            array('client_id, from_lat, from_lng, to_lat, to_lng, distance_covered', 'numerical'),
            array('place_id', 'length', 'max'=>255),
            array('unit, mode', 'length', 'max'=>50),
            array('client_id, from_lat, from_lng, to_lat, to_lng, place_id, unit, mode, distance_covered, cached_date', 'safe'),

        );
    }

    protected function beforeSave()
    {
        if(parent::beforeSave()){
            $this->cached_date = CommonUtility::dateNow();
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

