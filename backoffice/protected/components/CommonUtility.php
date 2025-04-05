<?php
require_once "php-qrcode/vendor/autoload.php";
use chillerlan\QRCode\{QRCode, QROptions};

class CommonUtility
{
	
	public static function dateNow()
	{
		return date("Y-m-d G:i:s");
	}

	public static function dateOnly()
	{
		return date("Y-m-d");
	}
	
	public static function userIp()
	{
		return Yii::app()->request->getUserHostAddress();
	}
	
	public static function t($text='',$args=array(),$language='backend')
	{
		return Yii::t($language,$text,(array)$args);
	}
	
	public static function q($data='')
	{
		return Yii::app()->db->quoteValue($data);
	}
	
	public static function dataTablesLocalization()
	{
		return array(
    	  'decimal'=>'',
    	  'emptyTable'=> t('No data available in table'),
    	  'info'=> t('Showing [start] to [end] of [total] entries',array(
    	    '[start]'=>"_START_",
    	    '[end]'=>"_END_",
    	    '[total]'=>"_TOTAL_",
    	  )),
    	  'infoEmpty'=> t("Showing 0 to 0 of 0 entries"),
    	  'infoFiltered'=>t("(filtered from [max] total entries)",array(
    	    '[max]'=>"_MAX_"
    	  )),
    	  'infoPostFix'=>'',
    	  'thousands'=>',',
    	  'lengthMenu'=> t("Show [menu] entries",array(
    	    '[menu]'=>"_MENU_"
    	  )),
    	  'loadingRecords'=>t('Loading...'),      	  
		  'processing'=>'',
    	  'search'=>t("Search:"),
    	  'zeroRecords'=>t("No matching records found"),
    	  'paginate' =>array(
    	    'first'=>t("First"),
    	    'last'=>t("Last"),
    	    'next'=>t("Next"),
    	    'previous'=>t("Previous")
    	  ),
    	  'aria'=>array(
    	    'sortAscending'=>t(": activate to sort column ascending"),
    	    'sortDescending'=>t(": activate to sort column descending")
		  ),
		  'buttons'=>array(
			'print'=>t("Print"),
			'csv'=>t("CSV"),
			'excel'=>t("Excel"),
			'pdf'=>t("PDF"),
		  ),
    	);    	
	}
	
	public static function getDataToDropDown($table_name='', $primary_fields='', $fields_value='',$where='',$orderby='',$limit='')
	{
		$data = array();
		$stmt="
		SELECT $primary_fields,$fields_value
		FROM $table_name
		$where
		$orderby
		$limit
		";						
		$dependency = CCacheData::dependency();
		$res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency)->createCommand($stmt)->queryAll();
		if($res){
			foreach ($res as $val) {				
				$data[ $val[$primary_fields] ] = Yii::app()->input->stripClean($val[$fields_value]);
			}
		}
		return $data;
	}
	
	public static function generateToken($table="", $field_name='' ,$token='')
	{
		$token = empty($token)? sha1(uniqid(mt_rand(), true)) : $token;
		
		$stmt="SELECT * FROM $table
		WHERE $field_name=".q($token)."
		";			
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
			return self::generateToken($table,$field_name,$token);
		}
		return $token;
	}
	
	public static function toSeoURLOLD($string){
	    $string = str_replace(array('[\', \']'), '', $string);
	    $string = preg_replace('/\[.*\]/U', '', $string);
	    $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
	    $string = htmlentities($string, ENT_COMPAT, 'utf-8');
	    $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
	    $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
	    return strtolower(trim($string, '-'));
	}   

	public static function toSeoURL($string)
	{
		try {
			require_once 'WSanitize.php';
			return sanitize_title_with_dashes($string);
		} catch (Exception $e) {
			return self::toSeoURLOLD($string);
		}		
	}
	
	public static function uploadPath($full_path=true,$upload_folder='upload')
	{
		if($full_path){
		    //return Yii::getPathOfAlias('webroot')."/../$upload_folder";		
		    return Yii::getPathOfAlias('upload_dir');		
		} else {
			return "/$upload_folder";
		}
	}
	
	public static function homePath()
	{
		return Yii::getPathOfAlias('home_dir');		
	}
		
	public static function uploadDestination($folder='')
	{
		$path = self::homePath()."/$folder";
		if(!file_exists($path)){
			@mkdir($path,0777);
		} 			
		return $path;
	}
	
	public static function uploadURL()
	{
		if(IS_FRONTEND){
			return Yii::app()->createAbsoluteUrl("/upload");		
		} else return websiteDomain().Yii::app()->request->baseUrl."/../upload";
	}
	
	public static function formatShortText($value,$limit=90) {
		$CHtmlPurifier = new CHtmlPurifier();
		$CHtmlPurifier->options = array('HTML.Allowed'=>'');		
		$value = stripslashes($CHtmlPurifier->purify($value));
		
        if(strlen($value)>$limit) {
            $retval=CHtml::tag('span',array('title'=>$value),CHtml::decode(mb_substr($value,0,$limit-3,Yii::app()->charset).'...'));
        } else {
            $retval=CHtml::decode($value);
        }
        return $retval;
    }
	
    public static function setMenuActive($parent='.membership',$class_name='.plans_create',$scriptname='menu_active')
	{
		ScriptUtility::registerScript(array(
		  '$(".siderbar-menu li'.$parent.'").addClass("active")',		 
		  '$(".siderbar-menu li'.$parent.' ul li'.$class_name.'").addClass("active")',		 
		),$scriptname,CClientScript::POS_END);
				
	}
	
	public static function setSubMenuActive($parent='.siderbar-menu',$child='.membership')
	{
		ScriptUtility::registerScript(array(
		  '$("'.$parent.' li'.$child.'").addClass("active")',		  
		),'sub_menu_active',CClientScript::POS_END);
				
	}
	
	public static function getSiteLogo()
	{
		$opts = OptionsTools::find(array('website_logo'));		
		return CMedia::getImage(isset($opts['website_logo'])?$opts['website_logo']:'',"/upload/all",
		Yii::app()->params->size_image
		,'logo@2x.png');
	}
	
	public static function getPhotox($filename='',$default='sample-merchant-logo@2x.png',$folder='')
	{					
		$upload_path = CommonUtility::uploadPath();		
		$url = websiteDomain().Yii::app()->theme->baseUrl."/assets/images/$default";		
		if(empty($folder)){
			if ( file_exists($upload_path."/$filename") &&  !empty($filename)){	
				$url = CommonUtility::uploadURL()."/$filename";
			}		
		} else {			
			$folder = str_replace("/upload",'',$folder);			
			if ( file_exists($upload_path.$folder."/$filename") &&  !empty($filename)){					
				$url = CommonUtility::uploadURL().$folder."/$filename";
			}
		}
		return $url;
	}
	
	public static function getPlaceholderPhoto($type='customer',$default='sample-merchant-logo@2x.png')
	{
		switch ($type) {
			case "customer":
			case "driver":
				$site_user_avatar = 'user@2x.png';
				$settings = Yii::app()->params['settings'];
				if(is_array($settings) && count($settings)>=1){
					$site_user_avatar = isset($settings['site_user_avatar'])? (!empty($settings['site_user_avatar'])?$settings['site_user_avatar']:$site_user_avatar) :$site_user_avatar;
				}					
				return $site_user_avatar;
				break;
				
			case "merchant_logo":		
				$avatar = 'placeholder.png';
				$settings = Yii::app()->params['settings'];
				if(is_array($settings) && count($settings)>=1){
					$avatar = isset($settings['site_merchant_avatar'])? (!empty($settings['site_merchant_avatar'])?$settings['site_merchant_avatar']:$avatar) :$avatar;
				}					
				return $avatar;
				break;
				
			case "item_photo":	 								   	
			case "item":
				$avatar = 'placeholder.png';
				$settings = Yii::app()->params['settings'];
				if(is_array($settings) && count($settings)>=1){
					$avatar = isset($settings['site_food_avatar'])? (!empty($settings['site_food_avatar'])?$settings['site_food_avatar']:$avatar) :$avatar;
				}					
				return $avatar;
			   break;	
			   
			case "logo":
				return 'logo@2x.png';
				break;   

			case "icon";
			return 'default-icons.png';
			    break;  
							
			default:
				return $default;
				break;
		}
	}
	
	public static function validatePhoto($filename='')
	{
		$upload_path = CommonUtility::uploadPath();		
		if ( file_exists($upload_path."/$filename") &&  !empty($filename)){
			return true;
		}
		return false;
	}
	
	/*public static function deletePhoto($filename='',$folder='')
	{
		dump($filename);dump($folder);
		$upload_path = CommonUtility::uploadPath();				
		if(!empty($folder)){
			$folder = str_replace("/upload",'',$folder);			
			$upload_path.=$folder;
		}		
		dump($upload_path);dump($filename);die();
		if ( file_exists($upload_path."/$filename") &&  !empty($filename)){
			@unlink($upload_path."/$filename");
		}
	}*/
	public static function deletePhoto($filename='',$folder='')
	{		
		$home_path = CMedia::homeDir();
		$upload_path = $home_path.DIRECTORY_SEPARATOR.$folder;
		
		if(empty($folder)){
			$upload_path = CommonUtility::uploadPath();
		}
		
		if ( file_exists($upload_path."/$filename") &&  !empty($filename)){
			@unlink($upload_path."/$filename");
		}
	}
		
	public static function MultiLanguage()
	{
		/*if($res = OptionsTools::find(array('enabled_multiple_translation_new'))){
			$enabled = isset($res['enabled_multiple_translation_new'])?$res['enabled_multiple_translation_new']:'';
			if($enabled==1){
				return true;
			}
		}
		return false;*/
		return true;
	}
	
	public static function getMessages($aslist=true)
	{		
    	$path=Yii::getPathOfAlias('webroot')."/protected/messages";    	    	
    	$res=scandir($path);
    	if(is_array($res) && count($res)>=1){
    		foreach ($res as $val) {       			
    			if($val=="."){    				
    			} elseif ($val==".."){  
    			} elseif ($val=="default"){  
    			} elseif ( strpos($val,".") ){      			
    			} else {
    				$list[$val]=$val;
    			}
    		}    		
    		return $list;
    	}
    	return false;		
	}
	
	public static function pagePath()
	{
		return Yii::app()->controller->id."/".Yii::app()->controller->action->id;
	}
	
	public static function SeoURL($string){
	    $string = str_replace(array('[\', \']'), '', $string);
	    $string = preg_replace('/\[.*\]/U', '', $string);
	    $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
	    $string = htmlentities($string, ENT_COMPAT, 'utf-8');
	    $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
	    $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
	    return strtolower(trim($string, '-'));
	}   
	
	public static function beautifyFilename($filename) {
	    // reduce consecutive characters
	    $filename = preg_replace(array(
	        // "file   name.zip" becomes "file-name.zip"
	        '/ +/',
	        // "file___name.zip" becomes "file-name.zip"
	        '/_+/',
	        // "file---name.zip" becomes "file-name.zip"
	        '/-+/'
	    ), '-', $filename);
	    $filename = preg_replace(array(
	        // "file--.--.-.--name.zip" becomes "file.name.zip"
	        '/-*\.-*/',
	        // "file...name..zip" becomes "file.name.zip"
	        '/\.{2,}/'
	    ), '.', $filename);
	    // lowercase for windows/unix interoperability http://support.microsoft.com/kb/100625
	    $filename = mb_strtolower($filename, mb_detect_encoding($filename));
	    // ".file-name.-" becomes "file-name"
	    $filename = trim($filename, '.-');
	    return $filename;
	}

	public static function createLanguageFolder($folder_name='')
	{
		$path = Yii::getPathOfAlias('webroot')."/protected/messages/$folder_name";		
		if(!file_exists($path)){			
			@mkdir($path);
		}
	}
	
	public static function generateAplhaCode($length = 8)
	{
	   $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789abcdefghijklmnopqrstuvwxyz';
	   $ret = '';
	   for($i = 0; $i < $length; ++$i) {
	     $random = str_shuffle($chars);
	     $ret .= $random[0];
	   }
	   return $ret;
	}
	
	public static function generateNumber($range=10,$exclude_zero=false) 
    {
	    $chars = "0123456789";	
		if($exclude_zero){
			$chars = "123456789";	
		}
	    srand((double)microtime()*1000000);	
	    $i = 0;	
	    $pass = '' ;	
	    while ($i <= $range) {
	        $num = rand() % $range;	
	        $tmp = substr($chars, $num, 1);	
	        $pass = $pass . $tmp;	
	        $i++;	
	    }
	    return $pass;
    }
    
    public static function uuid($prefix = '')
	{
		$chars = md5(uniqid(mt_rand(), true));
		$uuid  = substr($chars,0,8) . '-';
		$uuid .= substr($chars,8,4) . '-';
		$uuid .= substr($chars,12,4) . '-';
		$uuid .= substr($chars,16,4) . '-';
		$uuid .= substr($chars,20,12);
		return $prefix . $uuid;
	}
    
    public static function uploadNewFilename($filename='',$ext='')
    {
    	$extension='';
    	if(!empty($ext)){    	
    		 $extension = strtolower($ext);
    	} else {
    		if($explode = explode(".",$filename)){    			
    			$count = count($explode)-1;
    			$extension = isset($explode[$count])?$explode[$count]:'png';    			
    		} else $extension = strtolower(substr($filename,-3,3));    		
    	}
    	
    	$new_filename = self::generateAplhaCode(50).".$extension";
    	return self::generateToken("{{media_files}}",'filename',$new_filename);
    }

    public static function MobileDetect()
    {
    	require_once 'Mobile_Detect.php';
		$detect = new Mobile_Detect;
		return $detect;
    }
    
    public static function deleteMediaFile($filename='')
    {
    	$media = AR_media::model()->find("filename=:filename",array(
		  ':filename'=>$filename,		  
		));		
		if($media){
			$media->delete(); 			
		}
    }
    
    public static function maskCardnumber($cardnumber='')
    {
    	if ( !empty($cardnumber)){
    		$cardnumber = str_replace(" ",'',$cardnumber);
    		return substr($cardnumber,0,4)."XXXXXXXX".substr($cardnumber,-4,4);
    	}
    	return '';
    }
    
    public static function mask($string='', $mask='*')
    {    	
    	if(strlen($string)>1){    		
    	   //return str_repeat($mask,strlen($string)-4) . substr($string, -4);    	
		   if (!$string) {
				return NULL;
			}
			$length = strlen($string);
			$visibleCount = (int) round($length / 4);
			$hiddenCount = $length - ($visibleCount * 2);
			return substr($string, 0, $visibleCount) . str_repeat($mask, $hiddenCount) . substr($string, ($visibleCount * -1), $visibleCount);
    	}
    	return '';
    }
        
    public static function maskEmail($email='', $mask='*')
    {    	
    	if(strlen($email)>1){    		
    	    /*$prefix = substr($email, 0, strrpos($email, '@'));
		    $suffix = substr($email, strripos($email, '@'));
		    $len  = floor(strlen($prefix)/2);		
		    return substr($prefix, 0, $len) . str_repeat('*', $len) . $suffix;*/
    	    preg_match('/^.?(.*)?.@.+$/', $email, $matches);
            return str_replace($matches[1], str_repeat('*', strlen($matches[1])), $email);
    	}
    	return '';
    }
    
    public static function HumanFilesize($size, $precision = 2) {
	    $units = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
	    $step = 1024;
	    $i = 0;
	    while (($size / $step) > 0.9) {
	        $size = $size / $step;
	        $i++;
	    }
	    return round($size, $precision).t($units[$i]);
	}
	
	public static function WriteCookie($cookie_name='', $value='',$is_expired_long=true)
	{
		$cookie = new CHttpCookie($cookie_name, $value);
		if($is_expired_long){
           $cookie->expire = time()+60*60*24*180; 
		}
        Yii::app()->request->cookies[$cookie_name] = $cookie;  		
	}
	
	public static function getCookie($cookie_name='')
	{
		$value = (string)Yii::app()->request->cookies[$cookie_name];
		if (is_string($value) && strlen($value) > 0){
			return $value;
		}
		return false;
	}
	
	public static function deleteCookie($cookie_name='')
	{
		unset(Yii::app()->request->cookies[$cookie_name]);
	}
	
	public static function clearALlCookie()
	{
		Yii::app()->request->cookies->clear();
	}
	
	public static function highlightWord( $content, $word ) {
	    $replace = '<span class="highlight">' . $word . '</span>'; // create replacement
	    $content = str_ireplace( $word, $replace, $content ); // replace content	
	    return $content; 
    }
    
    public static function generateUIID()
    {
    	if($res = Yii::app()->db->createCommand("select UUID() as UUID")->queryRow()){
    		return $res['UUID'];
    	}
    	return false;
    }
    
    public static function createUUID($table="", $field_name='' )
	{
		$token = self::generateUIID();
		
		$stmt="SELECT * FROM $table
		WHERE $field_name=".q($token)."
		";			
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
			return self::createUUID($table,$field_name,$token);
		}
		return $token;
	}
    
    public static function MapCredentials($keys='maps')
	{
		$api_keys = ''; $map_provider='';
		$options=OptionsTools::find(array(
		  'map_provider','google_geo_api_key','google_maps_api_key','mapbox_access_token'
		));		
		if($options){
			$map_provider = isset($options['map_provider'])?$options['map_provider']:'';
			switch ($map_provider) {
				case "google.maps":
					if($keys=="maps"){
						$api_keys= isset($options['google_maps_api_key'])?$options['google_maps_api_key']:'';
					} else $api_keys= isset($options['google_geo_api_key'])?$options['google_geo_api_key']:'';
					break;
			
				default:
					$api_keys= isset($options['mapbox_access_token'])?$options['mapbox_access_token']:'';
					break;
			}
			return array(
			  'map_provider'=>$map_provider,
			  'api_keys'=>$api_keys
			);
		}
		return false;
	}	    
	
	public static function checkEmail($email) {
    	$version = phpversion();
        if($version>=7){
            if(!filter_var($email,FILTER_VALIDATE_EMAIL) === false){
                return true;
            } else
                return false;
        } else {
            if (@eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
                return true;
            } else
                return false;            
        }        
    }
    
    public static function parseError($data)
    {    	
    	$error = array();
    	if(is_array($data) && count($data)>=1){
    		foreach ($data as $key=>$val) {    			    			
    			foreach ($val as $value) {
    				$key = str_replace("_"," ",$key);    				
    				$error[] = $value;
    			}
    		}
    		return $error;
    	}
    	return false;
    }
    
    public static function parseModelError($model)
    {
    	$error = array();
    	foreach ($model->errors as $err) {
			foreach ($err as $error) {
				$error[] = $error;
			}				
		}			
		return $error;
    }
    
    public static function parseModelErrorToString($model_error=array(),$line_break="\n")
    {
    	$error = '';
    	if(is_array($model_error) && count($model_error)>=1){
    		foreach ($model_error as $item) {
    			foreach ($item as $val) {
    				$error.="$val".$line_break;
    			}
    		}
    	}
    	return $error;
    }
    
    public static function arrayToQueryParameters($data=array())
    {
    	$query_params = '';
    	if(is_array($data) && count($data)>=1){
    		foreach ($data as $value) {
    			$query_params.=q($value).",";
    		}
    		$query_params = substr($query_params,0,-1);
    		return $query_params;
    	}
    	return false;
    }
    
    public static function arrayToString($data=array(),$separator=',')
    {
    	$string ='';
    	if(is_array($data) && count($data)>=1){
    		foreach ($data as $value) {    			
    			$string.=t($value)."$separator ";
    		}
    		$string = substr($string,0,-2);
    	}
    	return $string;
    }
    
    public static function cutString($string='', $limit=255)
	{
		if(!empty($string)){
			if(strlen($string)>$limit){
				return substr($string,0,$limit);
			} 
		} 
		return $string;
	}
	
	public static function dateDifference($start, $end )
    {
        $uts['start']=strtotime( $start );
		$uts['end']=strtotime( $end );
		if( $uts['start']!==-1 && $uts['end']!==-1 )
		{
		if( $uts['end'] >= $uts['start'] )
		{
		$diff    =    $uts['end'] - $uts['start'];
		if( $days=intval((floor($diff/86400))) )
		    $diff = $diff % 86400;
		if( $hours=intval((floor($diff/3600))) )
		    $diff = $diff % 3600;
		if( $minutes=intval((floor($diff/60))) )
		    $diff = $diff % 60;
		$diff    =    intval( $diff );            
		return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
		}
		else
		{			
		return false;
		}
		}
		else
		{			
		return false;
		}
		return( false );
     }    
     
     public static function prettyMobile($mobile='')
     {     	
     	if(!empty($mobile)){
     		if (!preg_match("/\+\b/i",$mobile)) {
     			return "+$mobile";
     		}
     	}
     	return $mobile;
     }
     
     public static function getCronKey()
     {     	
     	return CRON_KEY;
     }
     
     public static function getHomebaseUrl()
     {
     	 if(IS_FRONTEND){
			if(self::isSSL()){
				return Yii::app()->createAbsoluteUrl("/",array(),'https');
			} else return Yii::app()->createAbsoluteUrl("/");     	 	
     	 } else {
	     	 //$url = websiteDomain();
	     	 /*if(!empty(HOME_FOLDER) && strlen(HOME_FOLDER)>1){
	     	 	return $url."/".HOME_FOLDER;
	     	 }*/	     	 
	     	 $url = Yii::app()->getBaseUrl(true);	     	 
	     	 $url = str_replace(BACKOFFICE_FOLDER,"",$url);
	     	 if(!empty($url)){
	     	 	if(substr($url,-1,1)=="/"){	     	 		
	     	 		$url = substr($url,0,-1);
	     	 	}
	     	 }
	     	 return $url;
     	 }
     }
     
     public static function sendEmail($email='', $toname='', $subject='', $body='')
     {
     	  try {

			  $email_explode = explode("@",$email);
			  $uuid = isset($email_explode[0])?$email_explode[0]:'';
			  if(self::isValidUuid($uuid)){
				return false;
			  }

		      CEmailer::init();
			  CEmailer::setTo( $email );
			  CEmailer::setName( $toname );
			  CEmailer::setSubject( $subject );
			  CEmailer::setBody( $body );
			  $resp = CEmailer::send();
			  return $resp;
		  } catch (Exception $e) {
		  	  return false;
		  }
     }

	 public static function isValidUuid( $uuid ) 
	 {    
		return preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $uuid) !== 1 ? false : true;
	 }
     
     public static function sendSMS($to='', $body='', $client_id='', $merchant_id='', $name='',$sms_template_id='', $sms_vars=[] )
     {
		  // THIS IS FIXED FOR NEXMO 
		  $model = AR_sms_provider::model()->find('as_default=:as_default', array(':as_default'=>1)); 		
		  if($model){
				if($model->provider_id=="nexmo"){
					Yii::import('ext.runactions.components.ERunActions');	
		            $cron_key = CommonUtility::getCronKey();		
					$get_params = array( 
						'to'=> $to,
						'key'=>$cron_key,
						'body'=>$body,
						'client_id'=>$client_id,
						'merchant_id'=>$merchant_id,
						'name'=>$name,
						'language'=>Yii::app()->language
					 );							 
					 CommonUtility::runActions( CommonUtility::getHomebaseUrl()."/tasksms/send?".http_build_query($get_params) );	
					 return true;				 
				}
		  }

     	  try {
	          CSMSsender::init();
		      CSMSsender::setTo($to);
		      CSMSsender::setBody($body);
		      CSMSsender::setClientID( $client_id );
		      CSMSsender::setMerchantID( $merchant_id );
		      CSMSsender::setName( $name );
			  CSMSsender::setTemplateID( $sms_template_id );
			  CSMSsender::setVars( $sms_vars );
		      $resp = CSMSsender::send();
		      return $resp;
	      } catch (Exception $e) {			  
		  	  return false;
		  }
     }
     
    public static function cleanString($text='', $lower=true)
	{
		if(!empty($text)){
			if($lower){
				return trim( strtolower($text) );
			} else return trim($text);			
		}
		return $text;
	}
	
	public static function removeSpace($text='', $replace_with='')
	{
		if(!empty($text)){
			return str_replace(" ",$replace_with,$text);
		}
		return $text;
	}
	
	public static function arrayToMustache($data=array())
	{
		$return_data = array();
		if(is_array($data) && count($data)>=1){
			foreach ($data as $key=> $items) {
				$return_data["{{{$key}}}"]=$items;
			}
		}
		return $return_data;
	}
	
	public static function taxPriceList()
	{
		return array(
		  1=>t("Tax in prices (prices include taxes)"),
		  0=>t("Tax not in prices (prices does not include tax)"),
		);
	}
	
	public static function taxType()
	{
		return array(
		  'standard'=>t("Standard"),
		  'multiple'=>t("Multiple tax"),
		  //'euro'=>t("Euro tax"),
		);
	}
	
	public static function generateRandomColor()
	{
		return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
	}

	public static function escapedJson($data=array())
	{
        $escaped_data = json_encode($data, JSON_HEX_QUOT | JSON_HEX_APOS);
        $escaped_data = str_replace(['\u0022', '\u0027'], ["\\\"", "\\'"], $escaped_data);
		return $escaped_data;
	}

	public static function dataToRow($data=array(), $column=4)
	{		
		$total = count($data);
		$new_data = array(); $x=1; $i=1; $datas = [];
		foreach ($data as $item) {				
			$datas[] = $item;
			if($x>=$column){
				$new_data[] = $datas;
				$x=0;
				$datas = [];				
			} else {			    
				if($i>=$total){
					$new_data[] = $datas;
				}
			}
			$x++; $i++;
		}				
		return $new_data;	
	}

	public static function removeHttp($url) {
		$disallowed = array('http://', 'https://');
		foreach($disallowed as $d) {
		   if(strpos($url, $d) === 0) {
			  return str_replace($d, '', $url);
		   }
		}	   
		if(!empty($url)){
			$url = str_replace("www.",'',$url);			
		}		
		return $url;
	}

	public static function validateDomain($domain_registered='', $domain_from='')
	{
		if (preg_match("/localhost/i", $domain_registered) && preg_match("/localhost/i", $domain_from) ) {
			return true;
		}
		if($domain_registered==$domain_from){			
			return true;
		}
		return false;
	}

	public static function createSlug($slug='',$table='',$field='slug')
	{
		$stmt="SELECT count(*) as total FROM $table
		WHERE $field=".q($slug)."
		";					
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){	
			if($res['total']>0){
				$new_slug = $slug.$res['total'];					
				return self::createSlug($new_slug);
			}
		}
		return $slug;
	}

	public static function runActions($url='')
	{
		Yii::import('ext.runactions.components.ERunActions');
		$options = OptionsTools::find(['runactions_method']);
		$method = isset($options['runactions_method'])?$options['runactions_method']:'';		
		if($method==="touchUrlExt"){
			ERunActions::touchUrlExt($url);
		} elseif($method==="fastRequest"){			
			self::fastRequest($url);
		} else {
			ERunActions::touchUrl($url);
		}
	}

	public static function getAddonStatus($uuid='')
	{
		$enabled = false;
		$model_addon = AR_addons::model()->find("uuid=:uuid",[':uuid'=>trim($uuid) ]);
		if($model_addon){
		    $enabled = $model_addon->activated==1?true:false;
		}
		return $enabled;
	}
	
	public static function fastRequest($url)
	{		
        if (preg_match("/https/i", $url)) {        	
        	self::consumeUrl($url);
        } else {        	
		    $parts=parse_url($url);	    
		    $fp = fsockopen($parts['host'],isset($parts['port'])?$parts['port']:80,$errno, $errstr, 30);
		    $out = "GET ".$parts['path']." HTTP/1.1\r\n";
		    $out.= "Host: ".$parts['host']."\r\n";
		    $out.= "Content-Length: 0"."\r\n";
		    $out.= "Connection: Close\r\n\r\n";	
		    fwrite($fp, $out);
		    fclose($fp);
        }	    
	}

	public static function consumeUrl($url='')
	{		
		$is_curl_working = true;
		$ch = curl_init();
	 	curl_setopt($ch, CURLOPT_URL, $url);
	 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	 	$result = curl_exec($ch);
	 	if (curl_errno($ch)) {		    
		    $is_curl_working = false;
		}
	 	curl_close($ch);
	 	
	 	if(!$is_curl_working){
	 		 $response = @file_get_contents($url);		 	 
		 	 if (isset($http_response_header)) {
		 	 	if (!in_array('HTTP/1.1 200 OK',(array)$http_response_header) && !in_array('HTTP/1.0 200 OK',(array)$http_response_header)) {
		 	 		//
		 	 	}
		 	 }
	 	}
	}

	public static function ArrayToLabelValue($data='')
	{
		if(is_array($data) && count($data)>=1){
			$new_data = [];
			foreach ($data as $key => $item) {
				$new_data[] = [
					'value'=>$key,
					'label'=>$item
				];
			}
			return $new_data;
		}
		return false;
	}

	public static function ArrayToSingleValue($data='',$type_cast='string')
	{
		if(is_array($data) && count($data)>=1){
			$new_data = [];
			foreach ($data as $key => $item) {
				if($type_cast=="integer"){
					$new_data[]=intval($item);
				} else $new_data[]=trim($item);
			}
			return $new_data;
		}
		return false;
	}

	public static function ArrayToValue($data='',$type_cast='string')
	{
		if(is_array($data) && count($data)>=1){
			$new_data = [];
			foreach ($data as $item) {
				if($type_cast=="integer"){
					$new_data[$item]=intval($item);
				} else $new_data[$item]=trim($item);
			}
			return $new_data;
		}
		return false;
	}

	public static function shortNumber($num) 
	{
		$units = ['', 'K', 'M', 'B', 'T'];
		for ($i = 0; $num >= 1000; $i++) {
			$num /= 1000;
		}
		return round($num, 1) . $units[$i];
	}

	public static function PrinterList()
	{
		return [
			[
				'label'=>t("Bluetooth printer"),
				'value'=>"bluetooth"
			],
			[
				'label'=>t("Sunmi V2"),
				'value'=>"sunmi"
			],
			[
				'label'=>t("FP-80WC 80mm"),
				'value'=>"feieyun"
			]
		];
	}

	public static function printerPaperType()
	{
		return [
			'58'=>t("58mm"),
			'80'=>t("80mm"),
		];
	}

	public static function seconds2human($ss) {
		$s = $ss%60;
		$m = floor(($ss%3600)/60);
		$h = floor(($ss%86400)/3600);
		$d = floor(($ss%2592000)/86400);
		$M = floor($ss/2592000);		
		//return "$M months, $d days, $h hours, $m minutes, $s seconds";
		return [
			'hour'=>$h,
			'minute'=>$m,			
		];
	}

	public static function isSSL() {
		if ( isset($_SERVER['HTTPS']) ) {
			if ( 'on' == strtolower($_SERVER['HTTPS']) )
			return true;
			if ( '1' == $_SERVER['HTTPS'] )
			return true;
		} elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
			return true;
		}
		return false;
	}
	
	public static function getCurrentURL()
	{
		$current_url = sprintf(
			'%s://%s/%s',
			isset($_SERVER['HTTPS']) ? 'https' : 'http',
			$_SERVER['HTTP_HOST'],
			trim($_SERVER['REQUEST_URI'],'/\\')
		);
		return $current_url;
	}

	public static function saveCronURL($url='')
	{
		$cron = isset($_GET['cron'])?$_GET['cron']:'';
		if(empty($cron)){
			$model = new AR_cron();
		    $model->url = $url;
		    $model->save();
		}		
	}

	public static function updateCronURL($url='')
	{
		$model  = AR_cron::model()->find("url=:url AND status=:status ",[
			':url'=>$url,
			':status'=>0
		]);
		if($model){
			$model->status = 1;
			$model->save();
		}
	}

	public static function mysqlSetTimezone()
	{
		try {
			$timezone = Yii::app()->timeZone;
            $tz = (new DateTime('now', new DateTimeZone($timezone)))->format('P');        
            Yii::app()->db->createCommand("SET time_zone=".q($tz)."")->query();
		} catch (Exception $e) {
			
		}		
	}

	public static function prettyCC($card='')
	{
		if(!empty($card)){
			if(strlen($card)>=16){
			$format = substr($card,0,4)." ";
			$format.= substr($card,4,4)." ";
			$format.= substr($card,8,4)." ";
			$format.= substr($card,12,4);
			return $format;
			} else return $card;
		}
		return $card;
	}

	public static function getMobileCountryList($filter=array())
	{
		$list = [];
		try {
			$data = ClocationCountry::listing($filter);			
			foreach ($data as $key => $items) {
				$list[$items['phonecode']] = $items['country_name']."(+".$items['phonecode'].")";
			}
	    } catch (Exception $e) {
			$list = [];
		}
		return $list;
	}

	public static function setSEO($title='',$meta_title='',$description='',$keywords='', $image='')
	{
		 if (!empty($title)){
			Yii::app()->controller->setPageTitle($title);			
		 }    	

		 if (!empty($meta_title)){
		    Yii::app()->clientScript->registerMetaTag($meta_title, 'title');		
		 }

		 if ($description){
			Yii::app()->clientScript->registerMetaTag($description, 'description'); 
			Yii::app()->clientScript->registerMetaTag($description, 'og:description'); 
		 }
		 
		 if ($keywords){
			Yii::app()->clientScript->registerMetaTag($keywords, 'keywords'); 
		 }

		 if ($image){
			Yii::app()->clientScript->registerMetaTag($image, 'og:image'); 
		 }
	}

	public static function toLanguageParameters($data=array(), $openBraket="{",$closeBraket='}')
	{
		if(is_array($data) && count($data)>=1){
			$new_data = [];
			foreach ($data as $key => $item) {
				$new_data[$openBraket.$key.$closeBraket] = $item;
			}
			return $new_data;
		} else return $data;
	}

	public static function getNextAutoIncrementID($table_name='')
	{
		$stmt = "
		SELECT auto_increment  AS next_id
		FROM  `information_schema`.`tables`
		WHERE table_name = ".q(DB_PREFIX.$table_name)."
		AND table_schema = ".q(DB_NAME)."
		";			
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
			return $res['next_id'];
		}
		return 0;
	}

	public static function getLastSequence($table_name='', $where='')
	{
		$stmt = "
		SELECT max(sequence) as total
		FROM {{{$table_name}}}
		$where
		";
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
			return $res['total']+1;
		}
		return 1;
	}

	public static function checkModuleAddon($addon_name='')
	{
		$model = AR_addons::model()->find("addon_name=:addon_name",[
			':addon_name'=>trim($addon_name)
		]);
		if($model){
			return $model->activated==1?true:false;
		}
		return false;
	}

	public static function POSviewlist()
	{
		$view_list[] = [
			'value'=>'new_view',
			'label'=>t("New")
		 ];
		$view_list[] = [
			'value'=>'order_view',
			'label'=>t("Orders")
		 ];
		 $view_list[] = [
			'value'=>'hold_view',
			'label'=>t("Hold")
		 ];
		 $view_list[] = [
			'value'=>'table_view',
			'label'=>t("Table")
		 ];
		 $view_list[] = [
			'value'=>'table_request',
			'label'=>t("Request")
		 ];
		 return $view_list;
	}

	public static function createUniqueTransaction($table="", $field_name='',$prefix='',$lenght=10)
	{
		$token = $prefix."-".CommonUtility::generateAplhaCode($lenght);

		$stmt="SELECT * FROM $table
		WHERE $field_name=".q($token)."
		";
		if(Yii::app()->db->createCommand($stmt)->queryRow()){
			return self::createUniqueTransaction($table,$field_name);
		}
		return $token;
	}

	public static function createQrcode($data='',$path='')
	{
		try {
			$options = new QROptions([
				'imageTransparent'  => false
			]);
			$options->cachefile = $path;
			$qrcode = new QRCode($options);
		$qrcode->render($data);
		} catch (Exception $e) {
			throw new Exception( $e->getMessage() );
		}
	}

	public static function viewQrcode($data='')
	{
		try {
			echo '<img src="'.(new QRCode)->render($data).'" alt="QR Code" />';
		} catch (Exception $e) {
			throw new Exception( $e->getMessage() );
		}
	}

	public static function getQrcodeFile($data=''){
		$qrcode_path = CommonUtility::uploadDestination(CMedia::qrcodeFolder());
		$file_path = "$qrcode_path/$data.png";
		if(file_exists($file_path)){
			return $file_path;
		}
		return false;
	}

	public static function tableStatus()
	{
		return [
			'available'=>t("Available"),
			'ordered'=>t("Ordered"),
			'occupied'=>t("Occupied"),
			'waiting for bill'=>t("Waiting for bill"),
		];
	}

	public static function printerType()
	{
		return [
			''=>t("Please select"),
			'feieyun'=>t("Feieyun"),
			'wifi'=>t("Wifi Printer"),
		];
	}

	public static function printerPaperList()
	{
		return [
			80=>t("80mm"),
			58=>t("58mm"),
		];
	}

	public static function printingTypeList()
	{
		return [
			'raw'=>t("Esc/Pos"),
			'image'=>t("Image"),
		];
	}

	public static function printingCharacterCodeList()
	{
		return [
			'en'=>t("English"),
			'ar'=>t("Arabic"),
			'jp'=>t("Japanese"),
			'kr'=>t("Korean"),
		];
	}

	public static function getPrinterDetails($merchant_id='',$printer_id='')
	{
		$model = AR_printer::model()->find("merchant_id=:merchant_id AND printer_id=:printer_id",[
			":merchant_id"=>$merchant_id,
			':printer_id'=>intval($printer_id)
		]);
		if($model){

			$data = [];
			$printer_user='';
			$printer_ukey='';
			$printer_sn='';
			$printer_key='';

			switch ($model->printer_model) {
				case 'bluetooth':
                case 'bleutooth':
					$data = [
						'printer_id'=>$model->printer_id,
						'printer_name'=>$model->printer_name,
						'printer_model'=>$model->printer_model,
						'paper_width'=>$model->paper_width,
						'auto_print'=>$model->auto_print,
						'auto_close'=>$model->auto_close,
						'printer_bt_name'=>$model->printer_bt_name,
						'device_id'=>$model->device_id,
						'service_id'=>$model->service_id,
						'characteristic'=>$model->characteristics,
						'print_type'=>$model->print_type,
					];
					break;

					case "feieyun":
                        $meta = AR_printer_meta::getMeta($printer_id,['printer_user','printer_ukey','printer_sn','printer_key']);
                        $printer_user = isset($meta['printer_user'])?$meta['printer_user']['meta_value1']:'';
                        $printer_ukey = isset($meta['printer_ukey'])?$meta['printer_ukey']['meta_value1']:'';
                        $printer_sn = isset($meta['printer_sn'])?$meta['printer_sn']['meta_value1']:'';
                        $printer_key = isset($meta['printer_key'])?$meta['printer_key']['meta_value1']:'';
                        $data = [
                            'printer_id'=>$model->printer_id,
                            'printer_name'=>$model->printer_name,
                            'printer_model'=>$model->printer_model,
                            'paper_width'=>$model->paper_width,
                            'auto_print'=>$model->auto_print,
                            'printer_user'=>$printer_user,
                            'printer_ukey'=>$printer_ukey,
                            'printer_sn'=>$printer_sn,
                            'printer_key'=>$printer_key,
                        ];
                        break;
			}
			return $data;
		} else throw new Exception( t("Printer not found") );
	}

	public static function getPrinterAutoPrint($merchant_id=0,$platform='web')
	{
		$model = AR_printer::model()->find("merchant_id=:merchant_id AND platform=:platform AND auto_print=:auto_print",[
			":merchant_id"=>$merchant_id,
			':platform'	=>$platform,
			':auto_print'=>1
		]);
		if($model){
			$data = [];
			$printer_user='';
			$printer_ukey='';
			$printer_sn='';
			$printer_key='';

			switch ($model->printer_model) {
				case 'bluetooth':
                case 'bleutooth':
					$data = [
						'printer_id'=>$model->printer_id,
						'printer_name'=>$model->printer_name,
						'printer_model'=>$model->printer_model,
						'paper_width'=>$model->paper_width,
						'auto_print'=>$model->auto_print,
						'auto_close'=>$model->auto_close,
						'printer_bt_name'=>$model->printer_bt_name,
						'device_id'=>$model->device_id,
						'service_id'=>$model->service_id,
						'characteristic'=>$model->characteristics,
						'print_type'=>$model->print_type,
					];
					break;

					case "feieyun":
                        $meta = AR_printer_meta::getMeta($model->printer_id,['printer_user','printer_ukey','printer_sn','printer_key']);
                        $printer_user = isset($meta['printer_user'])?$meta['printer_user']['meta_value1']:'';
                        $printer_ukey = isset($meta['printer_ukey'])?$meta['printer_ukey']['meta_value1']:'';
                        $printer_sn = isset($meta['printer_sn'])?$meta['printer_sn']['meta_value1']:'';
                        $printer_key = isset($meta['printer_key'])?$meta['printer_key']['meta_value1']:'';
                        $data = [
                            'printer_id'=>$model->printer_id,
                            'printer_name'=>$model->printer_name,
                            'printer_model'=>$model->printer_model,
                            'paper_width'=>$model->paper_width,
                            'auto_print'=>$model->auto_print,
                            'printer_user'=>$printer_user,
                            'printer_ukey'=>$printer_ukey,
                            'printer_sn'=>$printer_sn,
                            'printer_key'=>$printer_key,
                        ];
                        break;
			}
			return $data;
		} else throw new Exception( t("Printer not found") );
	}

	public static function validateDate($date, $format = 'Y-m-d') {
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	}

	public static function bulkImportItems($merchant_id=0,$data=[])
	{

		$insert_count = 0;
		if(is_array($data) && count($data)>=1){
			array_shift($data);
			foreach ($data as $items) {
				$item_id = isset($items[0])?$items[0]:null;
				$item_name = isset($items[1])?$items[1]:'';
				$short_desc = isset($items[2])?$items[2]:'';
				$long_desc = isset($items[3])?$items[3]:'';
				$size_id = isset($items[4])? intval($items[4]) :0;
				$size_name = isset($items[5])?$items[5]:'';
				$price = isset($items[6])? floatval($items[6]) :0;
				$cost_price = isset($items[7])? floatval($items[7]) :0;
				$discount_type = isset($items[8])?$items[8]:'';
				$discount = isset($items[9])?$items[9]:'';
				$discount_start = isset($items[10])?$items[10]:'';
				$discount_end = isset($items[11])?$items[11]:'';
				$cat_id = isset($items[12])? intval($items[12]) :0;
				$cat_name = isset($items[13])?$items[13]:'';
				$featured_image = isset($items[14])?$items[14]:'';
				$featured = isset($items[15])?$items[15]:'';
				$status = isset($items[16])?$items[16]:'';


				// ITEMS
				$item_exist = AR_item::model()->find("merchant_id=:merchant_id AND item_id=:item_id",[
					':merchant_id'=>$merchant_id,
					':item_id'=>$item_id
				]);

				$item_found = AR_item::model()->find("item_id=:item_id",[
					':item_id'=>$item_id
				]);

				if(!$item_exist){
					$model_item = new AR_item();
					$model_item->scenario = 'create';
					$model_item->is_bulk = 1;
					$model_item->item_id = $item_id;
					$model_item->merchant_id = $merchant_id;
					$model_item->item_name = $item_name;
					$model_item->item_description = $long_desc;
					$model_item->item_short_description = $short_desc;
					$model_item->status = $status;
					$model_item->photo = $featured_image;
					$model_item->path = "upload/$merchant_id";
					$model_item->category_selected[] = $cat_id;
					$featured_data = !empty($featured) ? explode(",",$featured) : null;
					if(is_array($featured_data) && count($featured_data)>=1){
						$model_item->item_featured = $featured_data;
					}
					$model_item->status = $status;

					if(!$item_found){
						if($model_item->save()){
							$insert_count++;
						}
					} else {
						throw new Exception( t("Item ID {item_id} already exist",[
							'{item_id}'=>$item_id
						]) );
					}
				}

				// ITEM SIZE RELATIONSHIP
				$model_itemsize = AR_item_size::model()->find("merchant_id=:merchant_id AND item_id=:item_id AND size_id=:size_id AND price=:price",[
					':merchant_id'=>$merchant_id,
					':item_id'=>$item_id,
					':size_id'=>$size_id,
					':price'=>$price,
				]);
				if(!$model_itemsize){
					$model_itemsize = new AR_item_size;
				}
				$model_itemsize->merchant_id = $merchant_id;
				$model_itemsize->item_id = $item_id;
				$model_itemsize->size_id = $size_id;
				$model_itemsize->price = $price;
				$model_itemsize->cost_price = $cost_price;
				$model_itemsize->discount = floatval($discount);
				$model_itemsize->discount_type = $discount_type;
				$model_itemsize->discount_start = self::validateDate($discount_start) ? $discount_start : null;
				$model_itemsize->discount_end = self::validateDate($discount_end) ? $discount_end : null;
				$model_itemsize->save();


				// SIZE
				$size_exist = AR_size::model()->find("size_id=:size_id",[
					':size_id'=>$size_id
				]);
				if(!$size_exist && $size_id>0){
					$model_size = new AR_size();
					$model_size->size_id = $size_id;
					$model_size->merchant_id = $merchant_id;
					$model_size->size_name = $size_name;
					$model_size->status = 'publish';
					$model_size->save();
				}

				// CATEGORY
				$category_exist = AR_category::model()->find("cat_id=:cat_id",[
					':cat_id'=>$cat_id
				]);
				if(!$category_exist){
					$model_cat = new AR_category();
					$model_cat->cat_id = $cat_id;
					$model_cat->merchant_id = $merchant_id;
					$model_cat->category_name = $cat_name;
					$model_cat->status = 'publish';
					$model_cat->save();
				}

			}
			// end for
			return $insert_count;
		} else throw new Exception( t("Invalid data") );
	}

}
/*end class*/