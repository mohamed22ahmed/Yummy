<?php
require 'php-jwt/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CBooking
{

    public static function get($reservation_uuid='')
    {
        $model = AR_table_reservation::model()->find("reservation_uuid=:reservation_uuid",[
            ':reservation_uuid'=>$reservation_uuid
        ]);
        if($model){
            return $model;
        }
        throw new Exception( HELPER_RECORD_NOT_FOUND );
    }

    public static function getGuestList($merchant_id=0,$available=1)
    {        
        $stmt="
        SELECT min(min_covers) as min_covers,
        max(max_covers) as max_covers
        FROM {{table_tables}}
        WHERE 
        merchant_id=".q($merchant_id)."
        AND
        available=".q(intval($available))."        
        ";                
        if($res = Yii::app()->db->createCommand($stmt)->queryRow()){            
            $min_covers = intval($res['min_covers']);
            $max_covers = intval($res['max_covers']);
            $data = array();
            for ($x = $min_covers; $x <= $max_covers; $x++) {
                $data[$x] = $x;
            }
            return $data;
        }
        throw new Exception( Helper_not_found );
    }

    public static function getDateList($merchant_id='')
    {
        $today = date('Y-m-d'); $daylist = array();
        $yesterday = date('Y-m-d', strtotime($today. " -1 days"));	
		$tomorrow = date('Y-m-d', strtotime($today. " +1 days"));		

        for($i=1; $i<=7; $i++){			
			$days = date('w', strtotime($yesterday. " +$i days"));			
			$days = strtolower($days);				
			$daylist[$days]= date('Y-m-d', strtotime($yesterday. " +$i days"));	 
		}
        
        $stmt="
        SELECT a.day_of_week 
        FROM
        {{table_shift_days}} a
        WHERE merchant_id=".q($merchant_id)."
        GROUP BY a.day_of_week 
        ORDER BY a.day_of_week ASC
        ";                        
        if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
            $data = [];
            foreach ($res as $items) {
                $day_week = $items['day_of_week'];                                
                $date = isset($daylist[$day_week])?$daylist[$day_week]:'';				                
                $name = Date_Formatter::date($date,"eee dd MMM",true);
                if($today==$date){
                    $name = t("Today").", $name";
                } elseif ($tomorrow==$date){
                    $name = t("Tomorrow").", $name";
                }
                $data[$date] = $name;
            }
            ksort($data);
            return $data;
        }
        throw new Exception( Helper_not_found );
    }

    public static function getTimeSlot($day_week=0, $day_week_today="", $merchant_id=0, $status='publish' , $time_format=12)
    {        
        $interval_value = AttributesTools::timeInvertvalue(); 
        $current_time = date("Hi");
        $currenttime = date("H:i:s");
        $currenttime = CMerchantListingV1::blockMinutesRound($currenttime,60);        

        $stmt = "
        SELECT shift_id,first_seating,last_seating,shift_interval
        FROM {{table_shift}}
        WHERE merchant_id=".q($merchant_id)."
        AND shift_id IN (
            select shift_id from {{table_shift_days}}
            where merchant_id=".q($merchant_id)."
            and day_of_week =".q($day_week)."
        )            
        and status = ".q($status)."
        ";               
        if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
            $time_list = [];
            foreach ($res as $items) {                  
                $interval = isset($interval_value[$items['shift_interval']]) ? $interval_value[$items['shift_interval']] : '30 mins';                

                $firstseating = date("H:i",strtotime($items['first_seating']));
                $lastseating = date("H:i",strtotime($items['last_seating']));

                $first_seating = date("Hi",strtotime($items['first_seating']));
                $last_seating = date("Hi",strtotime($items['last_seating']));            

                $continue = true;                
                if($day_week_today==$day_week){
                    if($current_time>$first_seating && $current_time>$last_seating){                                              
                        $continue = false;                    
                    }                                 
                    if($continue){
                        if($current_time>$firstseating){                                                
                            $firstseating = $currenttime;
                        }                    
                        $time_list[] = AttributesTools::createTimeRange($firstseating,$lastseating,$interval ,$time_format);                            
                    }                
                } else {
                    $time_list[] = AttributesTools::createTimeRange($firstseating,$lastseating,$interval,$time_format);
                }
            }         
            return $time_list;
        }
        throw new Exception( Helper_not_found );
    }

    public static function getAvailableTable($guest_number=0,$reservation_date='',$reservation_time='',$merchant_id=0,$reservation_id=0)
    {
        $not_equal = $reservation_id>0? "AND reservation_id <> ".q($reservation_id) :'';

        $stmt = "
        SELECT count(*) as total
        FROM {{table_tables}}
        WHERE available = 1
        AND ".$guest_number." between min_covers and max_covers
        AND merchant_id  = ".q($merchant_id)."                
        ";                     
        if($res = Yii::app()->db->createCommand($stmt)->queryRow()){            
            if($res['total']<=0){
                throw new Exception( "We're sorry, the number of guest you had selected has no match with our available table." );
            }            
        }
        

        $stmt = "
        SELECT *
        FROM {{table_tables}}
        WHERE available = 1
        AND ".$guest_number." between min_covers and max_covers
        AND merchant_id  = ".q($merchant_id)."
        AND table_id NOT IN (
            select table_id from {{table_reservation}}
            where reservation_date=".q($reservation_date)."
            and reservation_time between ".q($reservation_time)." AND ".q($reservation_time)."
            and status IN ('confirmed','pending')
            $not_equal
        )
        ORDER by max_covers asc
        LIMIT 0,1
        ";                      
        if($res = Yii::app()->db->createCommand($stmt)->queryRow()){              
            return $res['table_id'];
        }
        throw new Exception( "We're sorry, the time you selected is no longer available." );
    }

    public static function getAvailableTableList($guest_number=0,$reservation_date='',$reservation_time='',$merchant_id=0, $reservation_id=0)
    {
        $not_equal = $reservation_id>0? "AND reservation_id <> ".q($reservation_id) :'';

        $stmt = "
        SELECT a.*, b.room_uuid
        FROM {{table_tables}} a
        LEFT JOIN {{table_room}} b 
        ON
        a.room_id = b.room_id
        WHERE a.available = 1
        AND ".$guest_number." between a.min_covers and a.max_covers
        AND a.merchant_id  = ".q($merchant_id)."        
        AND a.table_id NOT IN (
            select table_id from {{table_reservation}}
            where reservation_date=".q($reservation_date)."
            and reservation_time between ".q($reservation_time)." AND ".q($reservation_time)."
            and status IN ('confirmed','pending')
            $not_equal
        )
        ORDER by table_name asc        
        ";                   
        if($res = Yii::app()->db->createCommand($stmt)->queryAll()){              
            foreach ($res as $items) {
                $data[$items['room_uuid']][] = [
                    'value'=>$items['table_uuid'],
                    'label'=>$items['table_name']
                ];
            }
            return $data;
        }
        throw new Exception( "We're sorry, there is no available table with your selected timeslot." );
    }

    public static function getNotAvailableTime($merchant_id='',$reservation_date='',$guest_number=0, $reservation_id='')
    {
    
        $not_equal = $reservation_id>0? "AND reservation_id <> ".q($reservation_id) :'';
      
        $stmt = "            
        select a.reservation_time,
        (
        select count(*) 
        from {{table_tables}}
        where merchant_id = ".q($merchant_id)." 
        and ".$guest_number." between min_covers and max_covers  
        and available=1
        ) as total_table,

        (
          select count(*)
          from {{table_reservation}}
          where merchant_id = ".q($merchant_id)." 
          and reservation_date = ".q($reservation_date)." 
          and reservation_time = a.reservation_time 
          $not_equal
          and table_id IN (
            select table_id from 
            {{table_tables}}      
            where merchant_id = ".q($merchant_id)." 
            and ".$guest_number." between min_covers and max_covers  
            and available=1
         )       
        
        ) as total_use_table 

        from {{table_reservation}} a        
        where merchant_id = ".q($merchant_id)." 
        and reservation_date = ".q($reservation_date)." 
        and status IN ('confirmed','pending')    
        $not_equal    
        and table_id = (
        select table_id from {{table_tables}}
                    where 
                    table_id = a.table_id
                    and ".$guest_number." between min_covers and max_covers      
                    and available=1      
        )
        group by reservation_time
        ";        
        //dump($stmt);
        if($res = Yii::app()->db->createCommand($stmt)->queryAll()){                      
            $data = [];
            foreach ($res as $items) {             
                //dump($items);
                if($items['total_table']<=$items['total_use_table']){
                    $data[] = $items['reservation_time'];
                }        
                //dump($data);
            }
            return $data;
        }
        throw new Exception( HELPER_NO_RESULTS);
    }

    public static function getBookingDetails($reservation_uuid='')
    {
        
        $criteria=new CDbCriteria();
        $criteria->alias="a";
        $criteria->select="a.*,
        concat(b.first_name,' ',b.last_name) as full_name,
        b.first_name,b.last_name,
        b.email_address,concat(b.phone_prefix,'',b.contact_phone) as contact_phone,
        b.phone_prefix, b.contact_phone as contact_phone_without_prefix,
        b.avatar, b.path,
        c.room_id 
        ";
        $criteria->join='
        LEFT JOIN {{client}} b on a.client_id = b.client_id 
        LEFT JOIN {{table_tables}} c on a.table_id = c.table_id 
        ';
        $criteria->addCondition("reservation_uuid=:reservation_uuid");
        $criteria->params = [':reservation_uuid'=>$reservation_uuid];        

        // $dependency = CCacheData::dependency();        
        // $model = AR_table_reservation::model()->cache(Yii::app()->params->cache, $dependency)->find($criteria);        
        $model = AR_table_reservation::model()->find($criteria);
        if($model){
            $status_list = AttributesTools::bookingStatus();

            $full_time = "$model->reservation_date $model->reservation_time";	                
            $full_time_pretty = t("{date} at {time}",[
                '{date}'=>Date_Formatter::dateTime($full_time,"EEEE, MMMM d, y",true),
                '{time}'=>Date_Formatter::dateTime($full_time,"h:mm:ss a",true),
            ]);
            $guest_number = Yii::t('front', '{n} person|{n} persons', $model->guest_number );

            $status_pretty = isset($status_list[$model->status])?$status_list[$model->status]:$model->status;
            return [
                'reservation_id'=>$model->reservation_id,
                'reservation_uuid'=>$model->reservation_uuid,
                'client_id'=>$model->client_id,
                'full_name'=>$model->full_name,
                'first_name'=>$model->first_name,
                'last_name'=>$model->last_name,
                'contact_phone'=>$model->contact_phone,
                'phone_prefix'=>$model->phone_prefix,
                'contact_phone_without_prefix'=>$model->contact_phone_without_prefix,
                'email_address'=>$model->email_address,
                'photo_url'=>CMedia::getImage($model->avatar,$model->path,'@thumbnail',CommonUtility::getPlaceholderPhoto('customer')),
                'merchant_id'=>$model->merchant_id,
                'table_id'=>$model->table_id,
                'reservation_date_raw'=>$model->reservation_date,
                'reservation_date'=>Date_Formatter::date($model->reservation_date),
                'reservation_time'=>Date_Formatter::Time($model->reservation_time),
                'reservation_time_raw'=>$model->reservation_time,
                'reservation_datetime'=>Date_Formatter::date($model->reservation_date." ".$model->reservation_time,'EEEE, MMMM d, h:mm:ss a y',true),
                'guest_number_raw'=>$model->guest_number,
                'special_request'=>$model->special_request,
                'cancellation_reason'=>$model->cancellation_reason,
                'status'=>$model->status,
                'status_pretty'=>t("Reservation {status}",['{status}'=>$status_pretty]),
                'status_pretty1'=>$status_pretty,
                'date_created'=>Date_Formatter::dateTime($model->date_created,"EEEE, MMMM d, h:mm:ss a y",true),
                'guest_number'=>$guest_number,
                'full_time_pretty'=>$full_time_pretty,
                'room_id'=>$model->room_id
            ];
        }
        throw new Exception( HELPER_NO_RESULTS);
    }

    public static function customerSummary($client_id=0,$merchant_id=0,$reservation_date='')
    {
        $and_merchant = $merchant_id>0? "AND merchant_id=".q($merchant_id)."" :'';

        $stmt = "
        SELECT a.client_id,
        (
            select count(*)
            from {{table_reservation}}
            where client_id=".q($client_id)."
            $and_merchant
            and reservation_date>=".q($reservation_date)."
            and status not in ('denied','cancelled','no_show','waitlist','finished')
        ) as total_upcoming,

        (
            select count(*)
            from {{table_reservation}}
            where client_id=".q($client_id)."
            $and_merchant
        ) as total_reservation,

        (
            select count(*)
            from {{table_reservation}}
            where client_id=".q($client_id)."
            $and_merchant
            and status in ('denied')
        ) as total_denied,

        (
            select count(*)
            from {{table_reservation}}
            where client_id=".q($client_id)."
            $and_merchant
            and status in ('cancelled')
        ) as total_cancelled,

        (
            select count(*)
            from {{table_reservation}}
            where client_id=".q($client_id)."
            $and_merchant
            and status in ('no_show')
        ) as total_noshow,

        (
            select count(*)
            from {{table_reservation}}
            where client_id=".q($client_id)."
            $and_merchant
            and status in ('waitlist')
        ) as total_waitlist

        FROM {{table_reservation}} a
        WHERE
        a.client_id=".q($client_id)."
        ";        
        if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
            return $res;
        }        
        throw new Exception( HELPER_NO_RESULTS);
    }

    public static function reservationSummary($merchant_id=0,$reservation_date='')
    {        
        $and_merchant = $merchant_id>0? "AND merchant_id=".q($merchant_id)."" :'';
        $stmt = "
        SELECT a.client_id,
        (
            select count(*)
            from {{table_reservation}}
            where 1
            $and_merchant
            and reservation_date>=".q($reservation_date)."
            and status not in ('denied','cancelled','no_show','waitlist','finished')
        ) as total_upcoming,

        (
            select count(*)
            from {{table_reservation}}
            where 1
            $and_merchant
        ) as total_reservation,

        (
            select count(*)
            from {{table_reservation}}
            where 1
            $and_merchant
            and status in ('denied')
        ) as total_denied,

        (
            select count(*)
            from {{table_reservation}}
            where merchant_id=".q($merchant_id)."
            and status in ('cancelled')
        ) as total_cancelled,

        (
            select count(*)
            from {{table_reservation}}
            where 1
            $and_merchant
            and status in ('no_show')
        ) as total_noshow,

        (
            select count(*)
            from {{table_reservation}}
            where 1
            $and_merchant
            and status in ('waitlist')
        ) as total_waitlist

        FROM {{table_reservation}} a
        WHERE 1
        $and_merchant
        ";        
        if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
            return $res;
        }        
        throw new Exception( HELPER_NO_RESULTS);
    }

    public static function getTimeline($reservation_id=0)
    {        
        $status_list = AttributesTools::bookingStatus();
        $criteria=new CDbCriteria();
        $criteria->condition = "reservation_id=:reservation_id";		    
        $criteria->params  = array(
            ':reservation_id'=>$reservation_id
        );
        $criteria->order = "created_at DESC";
        $model = AR_table_reservation_history::model()->findAll($criteria); 
        if($model){
            $data = [];
            foreach ($model as $key => $items) {          
                $remarks = '';
                if(!empty($items->remarks)){
                    $args = json_decode($items->ramarks_trans,true);
                    $remarks = t($items->remarks,$args);
                }
                $data[] = [
                    'content'=>isset($status_list[$items->status])?$status_list[$items->status]:$items->status ,
                    'timestamp'=>Date_Formatter::dateTime($items->created_at),
                    'size'=>"large",
                    'type'=>$key<=0?"primary":"",
                    'remarks'=>$remarks
                ];
            }
            return $data;
        }        
        throw new Exception( HELPER_NO_RESULTS);
    }

    public static function getRoom($room_uuid='')
    {
        $model = AR_table_room::model()->find("room_uuid=:room_uuid",[
            ':room_uuid'=>$room_uuid
        ]);
        if($model){
            return $model;
        }
        throw new Exception( HELPER_RECORD_NOT_FOUND );
    }

    public static function getRoomByID($room_id='')
    {
        $model = AR_table_room::model()->find("room_id=:room_id",[
            ':room_id'=>$room_id
        ]);
        if($model){
            return $model;
        }
        throw new Exception( HELPER_RECORD_NOT_FOUND );
    }

    public static function getTable($table_uuid='')
    {
        $model = AR_table_tables::model()->find("table_uuid=:table_uuid",[
            ':table_uuid'=>$table_uuid
        ]);
        if($model){
            return $model;
        }
        throw new Exception( HELPER_RECORD_NOT_FOUND );
    }

    public static function getTableByID($table_id='')
    {
        $model = AR_table_tables::model()->find("table_id=:table_id",[
            ':table_id'=>$table_id
        ]);
        if($model){
            return $model;
        }
        throw new Exception( HELPER_RECORD_NOT_FOUND );
    }

    public static function getTableList($merchant_id=0)
    {
        $stmt = "
        SELECT a.*, b.room_uuid,b.room_name
        FROM {{table_tables}} a
        LEFT JOIN {{table_room}} b 
        ON
        a.room_id = b.room_id
        WHERE a.available = 1        
        AND a.merchant_id  = ".q($merchant_id)."                
        ORDER by sequence asc        
        ";
        if($res = CCacheData::queryAll($stmt)){ 
            foreach ($res as $items) {
                $data[$items['room_uuid']][] = [
                    'value'=>$items['table_uuid'],
                    'label'=>$items['table_name'],
                    'room_name'=>$items['room_name']
                ];
            }
            return $data;
        }
        throw new Exception( HELPER_NO_RESULTS);
    }

    public static function getTableListWithDevice($merchant_id=0)
    {
        $stmt = "
        SELECT 
        a.table_uuid,
        a.table_name,       
        b.room_name, 
        b.room_uuid,
        b.room_name,        
        IFNULL(c.device_id,'') as device_id

        FROM {{table_tables}} a

        LEFT JOIN {{table_room}} b 
        ON
        a.room_id = b.room_id

        left JOIN (
			SELECT device_id,table_uuid from {{table_device}}
            limit 0,1
		) c 
		on a.table_uuid = c.table_uuid

        WHERE a.available = 1        
        AND a.merchant_id  = ".q($merchant_id)."                
        ORDER by a.sequence asc        
        ";
        if($res = CCacheData::queryAll($stmt)){
            foreach ($res as $items) {
                $data[$items['room_uuid']][] = [
                    'value'=>$items['table_uuid'],
                    'label'=>$items['table_name'],
                    'room_name'=>$items['room_name'],
                    'device_id'=>$items['device_id']
                ];
            }
            return $data;
        }
        throw new Exception( HELPER_NO_RESULTS);
    }

    public static function TableList($merchant_id=0)
    {
        $stmt = "
        SELECT a.*, b.room_uuid,b.room_name
        FROM {{table_tables}} a
        LEFT JOIN {{table_room}} b 
        ON
        a.room_id = b.room_id
        WHERE a.available = 1        
        AND a.merchant_id  = ".q($merchant_id)."                
        ORDER by sequence asc        
        ";
        if($res = CCacheData::queryAll($stmt)){
            foreach ($res as $items) {
                $data[$items['table_uuid']] = [
                    'table_uuid'=>$items['table_uuid'],
                    'table_name'=>$items['table_name'],
                    'room_name'=>$items['room_name'],
                    'min_covers'=>$items['min_covers'],
                    'max_covers'=>$items['max_covers'],
                ];
            }
            return $data;
        }
        throw new Exception( HELPER_NO_RESULTS);
    }

    public static function setIdentityToken()
    {
        $identity_token = '';
		if(!Yii::app()->user->isGuest){
			$payload = [
				'iss'=>Yii::app()->request->getServerName(),
				'sub'=>0,				
				'iat'=>time(),	
				'token'=>Yii::app()->user->logintoken					
			];
			$identity_token = JWT::encode($payload, CRON_KEY, 'HS256'); 		
		}

		ScriptUtility::registerScript(array(			
			"var identity_token='".CJavaScript::quote($identity_token)."';",		
		),'identity_token');
    }

    public static function statusColor($status='')
    {
        $colors = [];
        $default_status = [
            'background'=>"#81c784",
            'color'=>'#fff'
        ];
        $colors['pending'] = [
            'background'=>"#2196f3",
            'color'=>'#fff'
        ];
        $colors['confirmed'] = [
            'background'=>"#81c784",
            'color'=>'#fff'
        ];
        $colors['cancelled'] = [
            'background'=>"#f44336",
            'color'=>'#fff'
        ];
        $colors['denied'] = [
            'background'=>"#f44336",
            'color'=>'#fff'
        ];
        $colors['finished'] = [
            'background'=>"#81c784",
            'color'=>'#fff'
        ];
        $colors['no_show'] = [
            'background'=>"#9c27b0",
            'color'=>'#fff'
        ];
        $colors['waitlist'] = [
            'background'=>"#9fa8da",
            'color'=>'#fff'
        ];
        return isset($colors[$status])?$colors[$status]:$default_status;
    }

    public static function tableListMaxMin($merchant_id=0)
    {
        $data = [];
        $model = AR_table_tables::model()->findAll("merchant_id=:merchant_id AND available=:available",[
            ':merchant_id'=>$merchant_id,
            ':available'=>1,
        ]);
        if($model){
            foreach ($model as $items) {
                $data[$items->table_uuid] = [
                  'min'=>$items->min_covers,
                  'max'=>$items->max_covers,
                ];
            }
        }
        return $data;
    }

    public static function getRoombytableid($table_uuid='')
    {
        $stmt = "
        SELECT a.room_uuid
        FROM {{table_room}} a
        LEFT JOIN {{table_tables}} b
        ON 
        a.room_id = b.room_id
        WHERE
        b.table_uuid=".q($table_uuid)."
        ";
        if($res = CCacheData::queryRow($stmt)){
            return $res;
        }
        throw new Exception( HELPER_NO_RESULTS);
    }

    public static function TableDetails($merchant_id=0)
    {
        $data = [];
        $stmt = "
        SELECT table_uuid,table_name,min_covers,max_covers
        FROM {{table_tables}}
        WHERE
        merchant_id = ".q($merchant_id)."
        AND available=1
        ";
        if($res = CCacheData::queryAll($stmt)){
            foreach ($res as $items) {
                $data[$items['table_uuid']] = [
                    'table_name'=>$items['table_name'],
                    'min_covers'=>$items['min_covers'],
                    'max_covers'=>$items['max_covers'],
                ];
            }
        }
        return $data;
    }

    public static function getTableStatus($merchant_id=0)
    {
        $data = [];
        $model = AR_table_status::model()->findAll("merchant_id=:merchant_id",[
            ':merchant_id'=>$merchant_id
        ]);
        if($model){
            foreach ($model as $items) {
                $data[$items->table_uuid] = $items->status;
            }
        }
        return $data;
    }

    public static function getTableStanding($table_uuid='')
    {
        $model = AR_table_status::model()->find("table_uuid=:table_uuid",[
            ':table_uuid'=>$table_uuid
        ]);
        if($model){
            return $model->status;
        }
        return false;
    }

    public static function isTableAvailable($table_uuid='')
    {
        $model = AR_table_status::model()->find("table_uuid=:table_uuid",[
            ':table_uuid'=>$table_uuid
        ]);
        if($model){
            if($model->status=="waiting for bill"){
                throw new Exception( t("Sorry, you cannot place a new order at this table at the moment. There are pending orders that have not been paid or completed yet. Please wait a moment or contact a staff member for assistance."));
            }
        }
        return true;
    }

    public static function getTableCartReference($merchant_id=0)
    {
        $data = [];
        $model = AR_cart::model()->findAll("merchant_id=:merchant_id AND COALESCE(table_uuid, '') != '' AND send_order=1",[
           ':merchant_id'=>$merchant_id
        ]);
        if($model){
            foreach ($model as $items) {
                $data[$items->table_uuid] = $items->cart_uuid;
            }
        }
        return $data;
    }

    public static function getTableWithStatus($merchant_id=0)
    {

        $stmt = "
        SELECT a.*, 
        b.room_uuid,
        IFNULL(c.status,'available') as table_status,
        IFNULL(d.cart_uuid,'') as cart_uuid,
        IFNULL(d.date_created,'') as time_seated,
        IFNULL(d.transaction_type,'') as transaction_type


        FROM {{table_tables}} a
        
        LEFT JOIN {{table_room}} b 
        ON
        a.room_id = b.room_id

        left JOIN (
			SELECT table_uuid,status from {{table_status}}
		) c 
		on a.table_uuid = c.table_uuid

        left JOIN (
			SELECT DISTINCT cart_uuid,table_uuid,date_created,transaction_type from {{cart}}   
            GROUP BY cart_uuid  
		) d
		on a.table_uuid = d.table_uuid

        WHERE a.available = 1        
        AND a.merchant_id  = ".q($merchant_id)."                
        ORDER by a.sequence asc        
        ";
        if($res = CCacheData::queryAll($stmt)){
            foreach ($res as $items) {
                $status = isset($items['table_status'])?$items['table_status']:'';
                $cart_uuid = isset($items['cart_uuid'])?$items['cart_uuid']:'';
                $atts = CCart::getAttributesAll($cart_uuid,[
					'timezone'
				]);

                $data[$items['room_uuid']][] = [
                    'room_uuid'=>$items['room_uuid'],
                    'table_id'=>$items['table_id'],
                    'table_uuid'=>$items['table_uuid'],
                    'table_name'=>$items['table_name'],
                    'min_covers'=>$items['min_covers'],
                    'max_covers'=>$items['max_covers'],
                    'status_class'=>str_replace(" ","-",$status),
                    'status'=>t($status),
                    'cart_uuid'=>$cart_uuid,
                    'timezone'=>isset($atts['timezone'])?$atts['timezone']:'',
                    'time_seated'=>!empty($items['time_seated'])?date("c",strtotime($items['time_seated'])):'',
                    'transaction_type_raw'=>$items['transaction_type'],
                    'transaction_type'=>t($items['transaction_type'])
                ];
            }
            return $data;
        }
        throw new Exception( HELPER_NO_RESULTS);
    }

    public static function updateTableStatus($merchant_id=0,$table_uuid='',$status='available')
    {
        if(empty($table_uuid)){
            return false;
        }
        $table_model = AR_table_status::model()->find("merchant_id=:merchant_id AND table_uuid=:table_uuid",[
            ':merchant_id'=>$merchant_id,
            ':table_uuid'=>$table_uuid
        ]);
        if(!$table_model){
            $table_model = new AR_table_status();
        }
        $table_model->merchant_id = intval($merchant_id);
        $table_model->table_uuid = $table_uuid;
        $table_model->status = $status;
        $table_model->save();
    }

    public static function getTableDetails($merchant_id=0,$table_uuid='')
    {
        $data = [];
        $stmt = "
        SELECT 
        a.table_uuid,
        a.merchant_id,
        a.room_id,
        b.room_uuid,
        b.room_name,
        a.table_name,
        a.min_covers,
        a.max_covers,
        a.available
        FROM {{table_tables}} a
        LEFT JOIN {{table_room}} b
        ON
        a.room_id = b.room_id
        WHERE a.table_uuid = ".q($table_uuid)."
        AND a.merchant_id=".q($merchant_id)."
        LIMIT 0,1
        ";
        if($res = CCacheData::queryRow($stmt)){
            return $res;
        }
        return false;
    }

    public static function setKitchenStatus($order_reference='',$status='')
    {
        AR_kitchen_order::model()->updateAll(array(
            'item_status' =>$status,
        ), "order_reference=:order_reference",[
            ":order_reference"=>$order_reference,
        ]);
    }

}
// end class