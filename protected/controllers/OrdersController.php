<?php
class OrdersController extends SiteCommon
{
	public function beforeAction($action)
	{
		if(Yii::app()->user->isGuest){
			$this->redirect(Yii::app()->getBaseUrl(true));	
			return false;	
		}

		if(!Yii::app()->user->isGuest){
			if(!ACustomer::validateUserStatus()){						
				Yii::app()->user->logout(false);		
				$this->redirect(Yii::app()->createUrl("store/index"));
			}
		}

		// SEO 
		CSeo::setPage();
		
		return true;			
	}
	
	public function actionIndex()
	{

		AssetsFrontBundle::includeMaps();
		
		$order_uuid = isset($_GET['order_uuid'])?$_GET['order_uuid']:'';	

		$chat_enabled = isset(Yii::app()->params['settings'])? (isset(Yii::app()->params['settings']['chat_enabled'])?Yii::app()->params['settings']['chat_enabled']:false) :false;		
		$chat_enabled = $chat_enabled==1?true:false;
		
		ScriptUtility::registerScript(array(
		  "var php_order_uuid='".CJavaScript::quote($order_uuid)."';"		  
		),'order_uuid');
			
		$this->render('order_place',array(
		  'order_uuid'=>$order_uuid,
		  'chat_enabled'=>$chat_enabled,
		  'chat_link'=>Yii::app()->createUrl("/account/livechat",[
			'order_uuid'=>$order_uuid
		  ])
		));
	}

	public function actionupload_deposit()
	{		
		try {

			$order_uuid = Yii::app()->input->get("order_uuid");
		    $order = COrders::get($order_uuid);
			
			Price_Formatter::init($order->use_currency_code);

			$model = AR_bank_deposit::model()->find("deposit_type=:deposit_type AND transaction_ref_id=:transaction_ref_id",[
				':deposit_type'=>"order",
				':transaction_ref_id'=>$order->order_id
			]);
			if(!$model){
				$model = new AR_bank_deposit;
			}

			if(isset($_POST['AR_bank_deposit'])){
				$model->attributes=$_POST['AR_bank_deposit'];
				$model->proof_image=CUploadedFile::getInstance($model,'proof_image');
				if($model->validate()){	
					$file_uuid = CommonUtility::createUUID("{{bank_deposit}}",'deposit_uuid');					
					
					$extension = pathinfo($model->proof_image->name, PATHINFO_EXTENSION);
			        $extension = strtolower($extension);									
					$new_filename = $file_uuid.".".$extension;

					$model->transaction_ref_id = $order->order_id;
					$model->path = "upload/deposit";
					$model->deposit_uuid = $file_uuid;			
					$model->merchant_id = $order->merchant_id;		

					$path = CommonUtility::uploadDestination('upload/deposit/').$new_filename;
                    $model->proof_image->saveAs( $path );

					$model->proof_image = $new_filename;	

					$model->use_currency_code = $order->use_currency_code;
					$model->base_currency_code = $order->base_currency_code;
					$model->admin_base_currency = $order->admin_base_currency;
					$model->exchange_rate = $order->exchange_rate;
					$model->exchange_rate_merchant_to_admin = $order->exchange_rate_merchant_to_admin;
					$model->exchange_rate_admin_to_merchant = $order->exchange_rate_admin_to_merchant;
					
					if($model->save()){
						Yii::app()->user->setFlash('success',t("You succesfully upload bank deposit. Please wait while we validate your payment."));
						$this->refresh();
					} else {						
						Yii::app()->user->setFlash('error',t(Helper_failed_save));
					}
				}
			}

			$this->render("upload_deposit",[
				'order'=>$order,
				'model'=>$model,
			]);
		} catch (Exception $e) {            
			$this->render("//store/404-page");
        }		
	}

}
/*end class*/