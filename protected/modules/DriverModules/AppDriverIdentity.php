<?php
require 'php-jwt/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AppDriverIdentity extends CUserIdentity
{   
    public static function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } else if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["REDIRECT_HTTP_AUTHORIZATION"]);    
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));            
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    public static function getBearerToken() {
        $headers = self::getAuthorizationHeader();        
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    public static function getToken() {
        $headers = self::getAuthorizationHeader();        
        if (!empty($headers)) {
            if (preg_match('/token\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    public static function verifyToken()
    {
        $method = Yii::app()->getRequest()->getRequestType();    		                
        if($method=="OPTIONS"){
            return false;
        }                           
        if(self::getIdentity()){            
            return false;
        }  
        return true;
    }

    public static function getIdentity()
    {
        try {

            $token = self::getBearerToken();                                    
            if(empty($token)){
                return false;
            }            
                                            
            self::validateAPIKeys($token);
                        
            $jwt_key = new Key(CRON_KEY, 'HS256');
            $decoded = (array) JWT::decode($token, $jwt_key);            
            
            if(is_array($decoded) && count($decoded)){            
                $owner = isset($decoded['iss'])?CommonUtility::removeHttp($decoded['iss']):'';
                $merchant_id = isset($decoded['sub'])?$decoded['sub']:0;                
                $date_issued = isset($decoded['iat'])?$decoded['iat']:'';

                $server_domain = CommonUtility::removeHttp(Yii::app()->request->getServerName());                
                if($owner===$server_domain){                    
                    return true;
                }                
            }            
            return false;

        } catch (Exception $e) {            
            return false;
        }        
    }

    private static function validateAPIKeys($token='')
    {
        $jwt_token = AttributesTools::JwtDriverTokenID();            
        $dependency = CCacheData::dependency();        
        $model = AR_option::model()->cache(Yii::app()->params->cache, $dependency)->find("merchant_id=:merchant_id AND 
         option_name=:option_name AND option_value=:option_value",[
            ':merchant_id'=>0,
            ':option_name'=>$jwt_token,
            ':option_value'=>$token
        ]);                        
        if($model){            
            return true;
        }        
        throw new Exception( t("Invalid or API keys not found") ); 
    }

    public static function verifyCustomer()
    {                
        $method = Yii::app()->getRequest()->getRequestType();    		                
        if($method=="OPTIONS"){
            return false;
        }                           
        if(self::getCustomerIdentity()){            
            return false;
        }                           
        return true;
    }

    public static function getCustomerIdentity()
    {
        try {

            $jwt_token = self::getToken();                         
            if(empty($jwt_token)){
                return false;
            }         

            $jwt_key = new Key(CRON_KEY, 'HS256');
            $decoded = (array) JWT::decode($jwt_token, $jwt_key);                                     
            if(is_array($decoded) && count($decoded)){                  
                $owner = isset($decoded['iss'])?CommonUtility::removeHttp($decoded['iss']):'';
                $merchant_id = isset($decoded['sub'])?$decoded['sub']:0;                
                $date_issued = isset($decoded['iat'])?$decoded['iat']:'';
                $token = isset($decoded['token'])?$decoded['token']:'';
                
                $server_domain = CommonUtility::removeHttp(Yii::app()->request->getServerName());                                      
                if($owner!=$server_domain){                    
                    return false;
                }                 
                                
                $dependency = CCacheData::dependency();
                $user = AR_driver::model()->cache(Yii::app()->params->cache, $dependency)->find("token=:token",array(
                  ':token'=>$token
                ));                  
                if($user){                        
                    Yii::app()->driver->id = $user->driver_id;
                    Yii::app()->driver->setState('driver_uuid', $user->driver_uuid);
                    Yii::app()->driver->setState('first_name', $user->first_name);
                    Yii::app()->driver->setState('last_name', $user->last_name);
                    Yii::app()->driver->setState('email_address', $user->email);
                    Yii::app()->driver->setState('phone_prefix', $user->phone_prefix);                     
                    Yii::app()->driver->setState('phone', $user->phone); 
                    Yii::app()->driver->setState('notification', $user->notification); 
                    Yii::app()->driver->setState('employment_type', $user->employment_type);                     
                    Yii::app()->driver->setState('avatar', CMedia::getImage($user->photo,$user->path,Yii::app()->params->size_image_thumbnail,
                     CommonUtility::getPlaceholderPhoto('driver'))
                   );                                      
                   return true;
                }
            }
            return false;
        } catch (Exception $e) {            
            return false;
        }         
    }

}
// end class