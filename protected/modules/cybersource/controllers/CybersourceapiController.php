<?php

class CybersourceapiController extends SiteCommon
{
    private $secretKey = '1ab534db708f4a56a35e650b0fe23796b90c8a4bc26d48869d2a9632343a67979b609d9327fd411188f2d8c1b9640366bb442cba994d4546b3fa47b95d29ce5cc624f372a4ce4eeb83c614f4149087b396daa5456041468d9da1e52b7fe87fc0e9235304cf4840eea7a3cfcd82aa367e34ad33ae025b4a78863f492c22389896';
    private $accessKey = '8d3e1ea6d00c3efebc18e22b1d9c91ae';
    private $profileId = '3BFF2F9C-5F6A-477A-A0EF-788EA432A200';
    private $pdo;
    public function beforeAction($action)
    {
        $db_host = DB_HOST;
        $db_name = DB_NAME;
        $this->pdo = new PDO("mysql:host=$db_host;dbname=$db_name", DB_USER, DB_PASSWORD);

        // Set cache-control headers
        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
        header('Pragma: no-cache'); // For HTTP/1.0
        header('Expires: 0');       // For proxies

        $this->data = Yii::app()->input->xssClean(json_decode(file_get_contents('php://input'), true));
        return true;
    }

    public function actionPaymentRender()
    {
        $transaction_payment_id = '';

        if(isset($this->data['default_payment_method'][0]['payment_method_id']) && $this->data['default_payment_method'][0]['payment_method_id']){
            $transaction_payment_id = $this->data['default_payment_method'][0]['payment_method_id'];
        }

        $transaction_uuid = $this->data['transaction_uuid'];

        $url = 'https://testsecureacceptance.cybersource.com/pay';
        $postdata = [
            'access_key' => $this->accessKey,
            'profile_id' => $this->profileId,
            'transaction_uuid' => "{$transaction_payment_id}::{$transaction_uuid}",
            "signed_field_names" => "access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency",
            "unsigned_field_names" => "",
            'signed_date_time' => gmdate('Y-m-d\TH:i:s\Z'),
            'locale' => 'en',
            'transaction_type' => 'sale,create_payment_token',
            'reference_number' => time(),
            'amount' => $this->data['amount'],
            'currency' => $this->data['currency']
        ];

        if(isset($this->data['default_payment_method'][0]['attr3']) && $this->data['default_payment_method'][0]['attr3']){
            $postdata['transaction_type'] = 'sale';
            $postdata['payment_token'] = $this->data['default_payment_method'][0]['attr3'];
            $postdata['signed_field_names'] .= ",payment_token";
            $url = 'https://testsecureacceptance.cybersource.com/oneclick/pay';
        }

        $postdata['signature'] = $this->generateSignature($postdata);

        $this->code = 1;
        $this->msg = "OK";
        $this->details = array(
            'data'           =>  $postdata,
            'transactionUrl' =>  $url
        );

        $this->responseJson();
    }

    private function generateSignature($data): string
    {
        $signedFieldNames = explode(",", $data["signed_field_names"]);
        $dataToSign = [];
        foreach ($signedFieldNames as $field) {
            if (isset($data[$field])) {
                $dataToSign[] = $field . "=" . $data[$field];
            }
        }
        $dataString = implode(",", $dataToSign);

        return base64_encode(hash_hmac('sha256', $dataString, $this->secretKey, true));
    }

    public function actionGetDefaultPayment()
    {
        $cached_client_id = Yii::app()->cache->get('client_id');
        $client_id = $this->getLoggedInClientId();
        if($cached_client_id !== $client_id && $client_id !== false){
            Yii::app()->cache->set('client_id', $client_id, CACHE_LONG_DURATION);
            $cached_client_id = Yii::app()->cache->get('client_id');
        }

        $payment = "SELECT * FROM st_client_payment_method where client_id = :client_id AND as_default = 1";
        $stmt = $this->pdo->prepare($payment);
        $params = [
            ':client_id' => $cached_client_id
        ];
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->code = 1;
        $this->msg = "OK";
        $this->details = $data;
        $this->responseJson();
    }

    private function getLoggedInClientId(){
        $client_uuid = Yii::app()->user->client_uuid;
        $client = "SELECT client_id FROM st_client where client_uuid = :client_uuid";
        $stmt = $this->pdo->prepare($client);
        $params = [
            ':client_uuid' => $client_uuid
        ];
        $stmt->execute($params);
        $dataClient = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $dataClient[0]['client_id'];
    }

    public function actionsuccessCybersourceApi(): void
    {
        $response = $_POST;

        try {

            if (isset($response['decision']) && $response['decision'] == 'ACCEPT') {

                //get order id from response else get last order
                $order_uuid = null;
                $payment_id = null;

                if (isset($response['req_transaction_uuid']) && $response['req_transaction_uuid']) {
                    $txid = explode('::', $response['req_transaction_uuid']);
                    $payment_id_hook =  $txid[0];
                    $transaction_id =  $txid[1];

                    if (isset($transaction_id) && $transaction_id) {
                        $order_uuid = $transaction_id;
                    }

                    if (isset($payment_id_hook) && $payment_id_hook) {
                        $payment_id = $payment_id_hook;
                    }
                }
                
                if (!$order_uuid) {
                    throw new Error("Order not found");                    
                }

                $order = AR_ordernew::model()->find('order_uuid=:order_uuid', [':order_uuid'=> $order_uuid]);

                $client = AR_client::model()->find('client_id=:client_id', array(':client_id'=> $order->client_id));


                if(!$client){
                    throw new Error("Client not found");
                }

                $clientlogin = $this->loginOrderClient($client);

                if (!$clientlogin) {
                    $this->redirect(array("/account/login?error=login_failed"));
                }

                if (isset($response['payment_token']) && $response['payment_token']) {
                    $this->resetDefaultWithZero($client->client_id);
                    $this->updateClientCybersourcePaymentMethod(
                        $client->client_id,
                        $response['req_card_number'] ?? null,
                        $response['req_reference_number'] ?? null,
                        $response['payment_token'],
                        $payment_id
                    );                        
                }
                                    
                $this->updateOrderCybersourceStatus($order_uuid);
                $this->redirect(array("/orders/index?order_uuid=".$order_uuid));
                
            } else if (isset($response['decision']) && $response['decision'] == 'CANCEL') {
                $this->redirect(array("/account/checkout?error=The consumer cancelled the transaction"));
            } else {
                $this->redirect(array("/account/checkout?error=decision_declined"));
            }
            
        } catch (error $e) {
            $this->redirect(array("/account/checkout?error=" . $e->getMessage()));
        }        
    }

    public function actioncancelCybersourcePayment(): void
    {
        // $this->loginOrderClient();
        $order = $this->getLastOrder();
        $this->deleteOrder($order['order_uuid']);
        $this->redirect(array("/account/checkout"));
    }

    private function updateClientCybersourcePaymentMethod($client_id, $req_card_number, $req_reference_number, $token, $payment_id): void
    {
        if ($payment_id) {
            $client_payment_method = AR_client_payment_method::model()->find('payment_method_id=:payment_method_id', [':payment_method_id'=> $payment_id]);

            if (isset($client_payment_method->payment_uuid) && $client_payment_method->payment_uuid) {
                $client_payment_method->attr3 = $token;
                $client_payment_method->attr2 = $req_card_number;
                $client_payment_method->reference_id = $req_reference_number;
                $client_payment_method->as_default = 1;
                $client_payment_method->ip_address = $_SERVER['REMOTE_ADDR'];
                $client_payment_method->save();
                return;              
            }                      
        }
        $payment = new AR_client_payment_method();
        $payment->scenario = 'insert';
        $payment->client_id = $client_id;
        $payment->merchant_id = 0;
        $payment->payment_code = 'cybersource';
        $payment->as_default = 1;
        $payment->reference_id = $req_reference_number;
        $payment->attr1 = 'cybersource';
        $payment->attr2 = $req_card_number;
        $payment->attr3 = $token;
        $payment->ip_address = $_SERVER['REMOTE_ADDR'];
        $payment->save();
    }

    private function insertClientCybersourcePaymentMethod($client_id, $req_card_number, $req_reference_number, $token): void
    {
        $payment = new AR_client_payment_method();
        $payment->scenario = 'insert';
        $payment->client_id = $client_id;
        $payment->merchant_id = 0;
        $payment->payment_code = 'cybersource';
        $payment->as_default = 1;
        $payment->reference_id = $req_reference_number;
        $payment->attr1 = 'cybersource';
        $payment->attr2 = $req_card_number;
        $payment->attr3 = $token;
        $payment->ip_address = $_SERVER['REMOTE_ADDR'];
        $payment->save();
    }

    private function loginOrderClient($client): int
    {
        $login = new AR_customer_autologin;
        $login->username = $client->email_address;
        $login->password = $client->password;
        $login->rememberMe = 1;

        if($login->validate() && $login->login() ){
            return $client->client_id;
        }
        return 0;
    }

    private function getLastOrder()
    {
        $order = "SELECT * FROM st_ordernew where payment_code=:payment_code order by order_id DESC limit 1";
        $stmt = $this->pdo->prepare($order);
        $params = [
            ':payment_code' => 'cybersource'
        ];
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data[0];
    }

    private function deleteOrder($order_uuid): void
    {
        AR_ordernew::model()->deleteAllByAttributes(array('order_uuid' => $order_uuid));
    }

    private function resetDefaultWithZero($client_id): void
    {
        AR_client_payment_method::model()->updateAll(['as_default' => 0], 'client_id=:client_id', array(':client_id'=>$client_id));
    }

    private function updateOrderCybersourceStatus($order_uuid): void
    {
        AR_ordernew::model()->updateAll(
            array(
                'payment_status' => 'paid',
                'status' => $this->changeStatusForPrePendingOrder()
            ),
            'order_uuid = :order_uuid',
            array(':order_uuid' => $order_uuid)
        );

        $this->insertIntoOrdernewTransactionsTable();
        $this->clearCart();
    }

    private function changeStatusForPrePendingOrder(): string
    {
        $criteria = new CDbCriteria();
        $criteria->select = '*';

        $criteria->addCondition('client_id=:client_id');
        $criteria->params = [
            ':client_id' => Yii::app()->user->id,
        ];
        $params = ['delivered', 'complete'];
        $criteria->addInCondition('status', $params);

        if(!AR_ordernew::model()->count($criteria))
            return 'prepending';
        return 'new';
    }

    private function insertIntoOrdernewTransactionsTable()
    {
        $order = $this->getLastOrder();

        $model = new AR_ordernew_transaction;
        $model->payment_uuid = $this->getPaymentUUID($order['client_id']);
        $model->order_id = $order['order_id'];
        $model->merchant_id = $order['merchant_id'];
        $model->client_id = $order['client_id'];
        $model->payment_code = $order['payment_code'];
        $model->transaction_type = "credit";
        $model->status = 'paid';
        $model->transaction_name = "payment";
        $model->transaction_description = "payment";
        $model->trans_amount = floatval($order['total']);
        $model->currency_code = $order['use_currency_code'];
        $model->to_currency_code = $order['base_currency_code'];
        $model->exchange_rate = $order['exchange_rate'];
        $model->admin_base_currency = $order['admin_base_currency'];
        $model->exchange_rate_merchant_to_admin = $order['exchange_rate_merchant_to_admin'];
        $model->exchange_rate_admin_to_merchant = $order['exchange_rate_admin_to_merchant'];
        $model->save();
    }

    private function clearCart()
    {
        $cart_uuid = Yii::app()->cache->get('cart_uuid');
        CCart::clear($cart_uuid);
    }

    private function getPaymentUUID($client_id): string|null
    {
        $payment = AR_client_payment_method::model()->find(
            'client_id=:client_id AND payment_code=:payment_code',
            array(
                ':client_id'=>$client_id,
                ':payment_code'=>'cybersource'
            )
        );

        return $payment?$payment->payment_uuid:null;
    }

}