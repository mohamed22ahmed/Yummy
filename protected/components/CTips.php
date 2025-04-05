<?php
class CTips{
		
	public static function data($label='name',$tip_type='fixed',$exchange_rate=1)
	{
		$criteria=new CDbCriteria();
		$criteria->condition  = 'meta_name=:meta_name';		
		$criteria->params = array(':meta_name'=>'tips');
		$criteria->order="meta_value ASC";
		$dependency = CCacheData::dependency();		
		$model = AR_admin_meta::model()->cache(Yii::app()->params->cache, $dependency)->findAll($criteria);
		if($model){
			$data = array();
			$data[] = array(
				'value'=>0,
				$label=>t("Not now")
			); 
			foreach ($model as $item) {
				$tip = floatval($item->meta_value);			
				$data[] = array(
				 'value'=>$tip,
				 $label=>$tip_type=="fixed"?Price_Formatter::formatNumber( ($item->meta_value*$exchange_rate) ): Price_Formatter::convertToRaw($item->meta_value,0). "%"
				); 
			}
			$data[] = array(
			  'value'=>'fixed',
			  $label=>t("Other")
			); 
			return $data;
		}		
		throw new Exception( 'no results' );
	}
}
/*end class*/