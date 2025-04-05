<?php

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Foreach_;

class CWhatsApp extends CComponent
{    

    private static $business_id;
    private static $phone_number;
    private static $token;

    public static function setBusiness($id='')
    {
        self::$business_id = $id;
    }

    public static function setPhone($phone='')
    {
        self::$phone_number = $phone;
    }

    public static function setToken($token='')
    {
        self::$token = $token;
    }

    public static function getTemplates($params=[])
    {
        $ch = curl_init();        
        curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v19.0/".self::$business_id."/message_templates?".http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        
        $headers = array();
        $headers[] = 'Authorization: Bearer '.self::$token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {            
            throw new Exception( curl_error($ch) );
        }
        curl_close($ch);
        
        $json_data = !empty($result)?json_decode($result,true):false;        
        if(is_array($json_data) && count($json_data)>=1){
            $error = isset($json_data['error'])?$json_data['error']:'';
            $data = isset($json_data['data'])?$json_data['data']:'';
            if(is_array($data) && count($data)>=1){
                return $data;
            } else {
                $error_message = isset($error['message'])?$error['message']:t("Undefined error");
                throw new Exception(t($error_message));  
            }
        } else throw new Exception( t("Invalid response"));
    }

    public static function listTemplates()
    {
        $data[] = t("Please select...");
        $model = AR_admin_meta::model()->findAll("meta_name=:meta_name",[
            ':meta_name'=>'whatsapp_templates'
        ]);
        if($model){
            foreach ($model as $items) {
                $data[$items->meta_value] = $items->meta_value;
            }
        }
        return $data;
    }

    public static function sendMessage($params=[])
    {        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v19.0/".self::$phone_number."/messages");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($params));
        
        $headers = array();
        $headers[] = 'Authorization: Bearer '.self::$token;
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception( curl_error($ch) );
        }
        curl_close($ch);
        
        $json_data = !empty($result)?json_decode($result,true):false;          
        if(is_array($json_data) && count($json_data)>=1){
            $error = isset($json_data['error'])?$json_data['error']:'';
            $data = isset($json_data['messages'])?$json_data['messages']:'';
            if(is_array($data) && count($data)>=1){
                return $data;
            } else {
                $error_message = isset($error['message'])?$error['message']:t("Undefined error");
                throw new Exception(t($error_message));  
            }
        } else throw new Exception( t("Invalid response"));        
    }

}
// end class