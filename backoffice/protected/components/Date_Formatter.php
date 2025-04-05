<?php
class Date_Formatter
{
	public static $dateFormat,$timeFormat;

	private static function Init()
	{		

		if(isset(Yii::app()->params['settings']['website_date_format_new'])){
			if(!empty(Yii::app()->params['settings']['website_date_format_new'])){
				self::$dateFormat = Yii::app()->params['settings']['website_date_format_new'];
			}			
		}
		if(isset(Yii::app()->params['settings']['website_time_format_new'])){
			if(!empty(Yii::app()->params['settings']['website_time_format_new'])){
				self::$timeFormat = Yii::app()->params['settings']['website_time_format_new'];
			}			
		}

		try {
			return Yii::app()->dateFormatter->format("dd MMM yyyy", date("c"));
		} catch (Exception $e) {
			Yii::app()->language=KMRS_DEFAULT_LANGUAGE;
		}
	}
	
	public static function date($date='',$pattern='dd MMM yyyy',$force_format=false)
	{	
		Date_Formatter::Init();	    
		if(!empty(self::$dateFormat) && !$force_format){
			$pattern = self::$dateFormat;
		}		
		if(!empty($date)){
		   return Yii::app()->dateFormatter->format($pattern, $date);
		}
	}
	
	public static function dateTime($date='',$pattern='dd MMM yyyy h:mm a',$force_format=false)
	{								
		Date_Formatter::Init();				
		if(!empty(self::$dateFormat) && !$force_format){
			$pattern = self::$dateFormat;
		}		
		if(!empty(self::$timeFormat && !$force_format )){
			$pattern.= " ".self::$timeFormat;
		}		
		if(!empty($date)){
		   return Yii::app()->dateFormatter->format($pattern, $date);
		}
	}
	
	public static function Time($time='',$pattern='h:mm a',$force_format=false)
	{	
						
		Date_Formatter::Init();
		if(!empty(self::$timeFormat) && !$force_format ){
			$pattern = self::$timeFormat;
		}				
		if(!empty($time)){
			return Yii::app()->dateFormatter->format($pattern, $time);
		}		
	}
	
	public static function TimeTo24($time='',$pattern='H:mm')
	{					
		Date_Formatter::Init();
		if(!empty($time)){
		   return Yii::app()->dateFormatter->format($pattern, $time);
		}
	}
	
	public static function getRangeDateString($date='') {				
		if ($date) {			
			$dateTime = new DateTime($date); 
            $timestamp = $dateTime->format('U'); 
			$currentTime=strtotime('today');			
			$timestamp=strtotime(date('Y-m-d 00:00:00',$timestamp));
			$days=round(($timestamp-$currentTime)/86400);
			switch($days) {
				case '0';
					return 'Today';
					break;
				case '-1';
					return 'Yesterday';
					break;
				case '-2';
					return 'Day before yesterday';
					break;
				case '1';
					return 'Tomorrow';
					break;
				case '2';
					return 'Day after tomorrow';
					break;
				default:
					if ($days > 0) {
						return 'In '.$days.' days';
					} else {
						return ($days*-1).' days ago';
					}
					break;
			}
		}
	}
}
/*end class*/