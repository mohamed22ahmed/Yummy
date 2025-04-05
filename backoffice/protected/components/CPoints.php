<?php

use CPoints as GlobalCPoints;

class CPoints
{

    public static function transactionType()
    {
        return ['points_earned','points_redeemed','points_firstorder','points_signup','points_review','points_booking'];
    }

    public static function getDescription($earn_type='')
    {
        $list = [];
        $list['points_signup'] = "Earn points by registering";
        $list['points_firstorder'] = "First order earn points";
        $list['points_review'] = "Points earned on Booking #{reservation_id}";
        $list['points_booking'] = "Points earned by adding review";
        return isset($list[$earn_type])?$list[$earn_type]:"Earn points";
    }

    public static function getAvailableBalance($client_id=0)
    {
        $balance = 0;
        try {								
            $card_id = CWallet::createCard( Yii::app()->params->account_type['customer_points'],$client_id);
            $balance = CWallet::getBalance($card_id);
        } catch (Exception $e) {             
        }
        return floatval($balance);
    }

    public static function getAvailableBalancePolicy($client_id=0,$redemption_policy='universal',$merchant_id=0)
    {
        $balance = 0;
        try {								
            $card_id = CWallet::createCard( Yii::app()->params->account_type['customer_points'],$client_id);
            if($redemption_policy=="merchant_specific"){
                $criteria = "                
                SELECT                
                SUM(CASE WHEN transaction_type = 'points_earned' THEN transaction_amount ELSE -transaction_amount END) AS points_balance
                FROM {{wallet_transactions}}
                WHERE card_id = ".q($card_id)."
                AND reference_id1 = ".q($merchant_id)."
                GROUP BY
                reference_id1;
                ";
                if($model = CCacheData::queryRow($criteria)){                    
                    $balance = isset($model['points_balance'])?floatval($model['points_balance']):0;
                }
            } else $balance = CWallet::getBalance($card_id);                 
        } catch (Exception $e) {             
        }
        return floatval($balance);
    }

    public static function creditPoints($order_uuid='')
    {
        $order = COrders::get($order_uuid);
        if($order){
            $atts = COrders::getAttributesAll($order->order_id,['points_to_earn']);			
            $points_to_earn = isset($atts['points_to_earn'])? ($atts['points_to_earn']>0?$atts['points_to_earn']:0) :0;
            if($points_to_earn<=0){
                throw new Exception( "points is less than zero");
            }
            
            $card_id = CWallet::createCard( Yii::app()->params->account_type['customer_points'], $order->client_id ); 

            $transaction_type = 'points_earned';

            $model = AR_wallet_transactions::model()->find("reference_id=:reference_id AND transaction_type=:transaction_type",[
                ':reference_id'=>$order->order_id,
                ':transaction_type'=>$transaction_type
            ]);
            if($model){
				throw new Exception( 'Transaction already exist' );
			}
            
            $params = array(					  		 
                'transaction_description'=>"Points earned on order #{order_id}",
                'transaction_description_parameters'=>array('{order_id}'=>$order->order_id),					  
                'transaction_type'=>$transaction_type,
                'transaction_amount'=>$points_to_earn,
                'status'=>'paid',                
                'reference_id'=>$order->order_id,
                'reference_id1'=>$order->merchant_id
            );
            $resp = CWallet::inserTransactions($card_id,$params);                
            return $resp;
        }
        throw new Exception( 'Order not found' );
    }   

    public static function debitPoints($order_uuid='')
    {
        $order = COrders::get($order_uuid);
        if($order){            

            $atts = COrders::getAttributesAll($order->order_id,['point_discount']);		
            if(!$atts){
                throw new Exception( 'Record not found' );
            }
            	
            $point_discount = isset($atts['point_discount'])? json_decode($atts['point_discount'],true) :false;
            $points_used = isset($point_discount['points'])?$point_discount['points']:0;    
            
            if($points_used<=0){
                throw new Exception( "points is less than zero");
            }

            $card_id = CWallet::createCard( Yii::app()->params->account_type['customer_points'], $order->client_id );             

            $transaction_type = 'points_redeemed';

            $model = AR_wallet_transactions::model()->find("reference_id=:reference_id AND transaction_type=:transaction_type",[
                ':reference_id'=>$order->order_id,
                ':transaction_type'=>$transaction_type
            ]);
            if($model){
				throw new Exception( 'Transaction already exist' );
			}

            $params = array(					  		 
                'transaction_description'=>"Redeem {points} points to order #{order_id}",
                'transaction_description_parameters'=>array('{points}'=>$points_used ,'{order_id}'=>$order->order_id),
                'transaction_type'=>$transaction_type,
                'transaction_amount'=>$points_used,
                'status'=>'paid',                
                'reference_id'=>$order->order_id,
                'reference_id1'=>$order->merchant_id
            );            
            $resp = CWallet::inserTransactions($card_id,$params);
            return $resp;
        }
        throw new Exception( 'Order not found' );
    }

    public static function reversal($order_uuid='')
    {
        $order = COrders::get($order_uuid);
        if($order){
            if($order->points>0){
                $atts = COrders::getAttributesAll($order->order_id,['point_discount']);
                if(!$atts){
                    throw new Exception( 'Record not found' );
                }
                $point_discount = isset($atts['point_discount'])? json_decode($atts['point_discount'],true) :false;                
                $points_used = isset($point_discount['points'])?$point_discount['points']:0;                

                if($points_used<=0){
                    throw new Exception( "points is less than zero");
                }

                $card_id = CWallet::createCard( Yii::app()->params->account_type['customer_points'], $order->client_id );
                $transaction_type = 'points_earned';

                $model = AR_wallet_transactions::model()->find("reference_id=:reference_id AND transaction_type=:transaction_type",[
                    ':reference_id'=>$order->order_id,
                    ':transaction_type'=>$transaction_type
                ]);
                if($model){
                    throw new Exception( 'Transaction already exist' );
                }

                $params = array(					  		 
                    'transaction_description'=>"Reversal {points} points on order #{order_id}",
                    'transaction_description_parameters'=>array('{points}'=>$points_used ,'{order_id}'=>$order->order_id),
                    'transaction_type'=>$transaction_type,
                    'transaction_amount'=>$points_used,
                    'status'=>'paid',                
                    'reference_id'=>$order->order_id,
                    'reference_id1'=>$order->merchant_id
                );                
                $resp = CWallet::inserTransactions($card_id,$params);
                return $resp;
            }            
        }
        throw new Exception( 'Order not found' );
    }

    public static function FirstOrder($client_id='',$earn_type='',$description='',$points='')
    {

        if($points<=0){
            throw new Exception( "points is less than zero");
        }

        $dependency = CCacheData::dependency();     
        $model = AR_client::model()->cache(Yii::app()->params->cache, $dependency)->find("client_id=:client_id",[
            ':client_id'=>$client_id
        ]);
        if($model){
            $status_completed = AOrderSettings::getStatus(array('status_delivered','status_completed'));
            $in_query = CommonUtility::arrayToQueryParameters($status_completed);
            $criteria = "SELECT count(*) as total_sold FROM {{ordernew}} 
            WHERE client_id=".q($model->client_id)."
            AND status IN ($in_query)            
            ";                   
            $dependency = CCacheData::dependency();
            if($order = AR_ordernew::model()->cache(Yii::app()->params->cache, $dependency)->findBySql($criteria)){                                
                if($order->total_sold==1){
                    $card_id = CWallet::createCard( Yii::app()->params->account_type['customer_points'], $model->client_id );    
                    
                    $model = AR_wallet_transactions::model()->find("card_id=:card_id AND transaction_type=:transaction_type",[
                        ':card_id'=>$card_id,
                        ':transaction_type'=>$earn_type
                    ]);
                    if($model){
                        throw new Exception( 'Transaction already exist' );
                    }    
                    
                    $params = array(					  		 
                        'transaction_description'=>$description,
                        'transaction_description_parameters'=>'',
                        'transaction_type'=>$earn_type,
                        'transaction_amount'=>floatval($points),
                        'status'=>'paid',                                                        
                    );                     
                    $resp = CWallet::inserTransactions($card_id,$params);
                    return $resp;
                } else {
                    throw new Exception( $order->total_sold>0?"Already many orders completed":"No completed orders" );                
                }
            }
        }
        throw new Exception( HELPER_RECORD_NOT_FOUND );
    }

    public static function globalPoints($client_uuid='',$earn_type='',$description='',$points='',$reference_id='',$description_parameters=array())
    {
        $dependency = CCacheData::dependency();     
        $model = AR_client::model()->cache(Yii::app()->params->cache, $dependency)->find("client_uuid=:client_uuid",[
            ':client_uuid'=>$client_uuid
        ]);
        if($model){            
            $card_id = CWallet::createCard( Yii::app()->params->account_type['customer_points'], $model->client_id );            

            switch ($earn_type) {
                case 'points_signup':                
                    $transact = AR_wallet_transactions::model()->find("reference_id=:reference_id AND transaction_type=:transaction_type",[                
                        ':reference_id'=>$model->client_id,
                        ':transaction_type'=>$earn_type
                    ]);
                    if($transact){
                        throw new Exception( 'Transaction already exist' );
                    }      
                    $reference_id = $model->client_id;
                    break;        
                    
               case 'points_booking':
               case 'points_review':        
                    $transact = AR_wallet_transactions::model()->find("reference_id=:reference_id AND transaction_type=:transaction_type",[                
                        ':reference_id'=>$reference_id,
                        ':transaction_type'=>$earn_type
                    ]);
                    if($transact){
                        throw new Exception( 'Transaction already exist' );
                    }        
                    break;        
            }            

            $params = array(					  		 
                'transaction_description'=>$description,
                'transaction_description_parameters'=>$description_parameters,
                'transaction_type'=>$earn_type,
                'transaction_amount'=>floatval($points),
                'status'=>'paid',                                
                'reference_id'=>$reference_id,
            );             
            $resp = CWallet::inserTransactions($card_id,$params);
            return $resp;
        }
        throw new Exception( HELPER_RECORD_NOT_FOUND );
    }

    public static function getThresholds($exchange_rate=1)
    {
        $criteria=new CDbCriteria();
        $criteria->condition = "meta_name=:meta_name";
        $criteria->params  = array(
        ':meta_name'=>AttributesTools::pointsThresholds()
        );
        //$criteria->order = "cast(meta_value as int) asc";
        $criteria->order = "cast(meta_value as unsigned)";
        if($model = AR_admin_meta::model()->findAll($criteria)){            
            $data = [];
            foreach ($model as $items) {
                $amount = $items->meta_value1 * $exchange_rate;
                $data[] = [
                    'id'=>$items->meta_id,
                    'label'=>t("{{points}} Points",['{{points}}'=>$items->meta_value]),
                    'points'=>$items->meta_value,
                    'amount_raw'=>$items->meta_value1,
                    'exchange_rate'=>$exchange_rate,
                    'amount'=>Price_Formatter::formatNumber($amount),
                ];
            }
            return $data;
        }
        throw new Exception( HELPER_RECORD_NOT_FOUND );
    }

    public static function getThresholdData($id=0)
    {
        $model = AR_admin_meta::model()->find("meta_id=:meta_id",[
            ':meta_id'=>intval($id)
        ]);
        if($model){
            return [
                'points'=>$model->meta_value,
                'value'=>$model->meta_value1,
            ];
        }
        return false;
    }

}
// end class
