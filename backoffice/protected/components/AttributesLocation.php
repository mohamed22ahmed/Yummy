<?php
class AttributesLocation
{
	
	public static function StateList($country_id='')
	{
		$list = CommonUtility::getDataToDropDown("{{location_states}}",'state_id','name',
		"WHERE country_id=".q($country_id)." ","ORDER BY name ASC");
		return $list;
	}
	
	public static function CityList($country_id='')
	{
		$list = CommonUtility::getDataToDropDown("{{location_cities}} a",'city_id','name',
		"WHERE state_id IN (
		  select state_id from {{location_states}}
		  where country_id = ".q($country_id)."
		)
		","ORDER BY name ASC");
		return $list;
	}

    public static function GetCityList()
    {
        $list = CommonUtility::getDataToDropDown("{{location_cities}}",'city_id','name',
            "","ORDER BY name ASC");
        return $list;
    }

    public static function getLocationCity($city_id=0)
    {
        $model = AR_city::model()->find("city_id=:city_id",array(
            ':city_id'=>intval($city_id)
        ));
        if($model){
            return $model;
        }
        return NULL;
    }

    public static function getZoneByMerchantId($merchant_id)
    {
        $sql = "SELECT st_zones.*
                FROM st_zones
                JOIN st_merchant_meta ON st_merchant_meta.meta_value = st_zones.zone_id
                WHERE st_merchant_meta.meta_name = 'zone' AND st_merchant_meta.merchant_id = :param";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":param", $merchant_id, PDO::PARAM_STR); // Bind the parameter
        return $command->queryAll();
    }

}
/*END CLASS*/