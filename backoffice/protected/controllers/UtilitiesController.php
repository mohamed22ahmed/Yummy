<?php
class UtilitiesController extends CommonController
{
		
	public function beforeAction($action)
	{							
		return true;
	}		

    public function actionclean_database()
    {
        $this->pageTitle = t("Clean database");	

        $model=new AR_option;
		$model->scenario=Yii::app()->controller->action->id;		

        $data = TableDataStatus::get();        

        if(isset($_POST['AR_option'])){

            if(DEMO_MODE){			
                $this->render('//tpl/error',array(  
                      'error'=>array(
                        'message'=>t("Modification not available in demo")
                      )
                    ));	
                return false;
            }

            $model->attributes=$_POST['AR_option'];
			if($model->validate()){												
				$user = AR_AdminUser::model()->find("admin_id=:admin_id AND password=:password",[
                    ':admin_id'=>Yii::app()->user->id,
                    ':password'=>md5($model->password)
                ]);                
                if($user){        
                    $data = [];
                    if(is_array($model->table_list) && count($model->table_list)>=1){
                        foreach ($model->table_list as $key => $items) {
                            if($items<>"0"){
                                $data[] = $items;
                            }
                        }
                    }                    
                    TableDataStatus::processDelete($data);
                    Yii::app()->user->setFlash('success',CommonUtility::t(Helper_success));
					$this->refresh();                
                } else Yii::app()->user->setFlash('error',t("Password is invalid"));
			} else Yii::app()->user->setFlash('error',t(HELPER_CORRECT_FORM));
        }
        
        $this->render('clean_database',[
            'model'=>$model,
            'data'=>$data,
            'links'=>array(
	            t("Utilities"),
                $this->pageTitle,
		    ),	    		    
        ]);
    }

    public function actionfixed_database()
    {
        $this->pageTitle = t("Fixed database");	

        require_once 'fixed_database.php';        

        $this->render('fixed_database',[
            'data'=>$data,
            'links'=>array(
	            t("Utilities"),
                $this->pageTitle,
		    ),	    		    
        ]);
    }

    public function actionmigration_tools()
    {
        $ajaxurl = Yii::app()->createUrl("/apitools");

        ScriptUtility::registerScript(array(
            "var ajaxurl_tools='$ajaxurl';",            
          ),'admin_migration');

        $this->pageTitle = t("Migration Tools");
        $this->render('migration-tools',[
            'tables'=>CMigrationTools::TableToMigrate(),
            'links'=>array(
	            t("Utilities"),
                $this->pageTitle,
		    ),	    		    
        ]);
    }

    public function actionfixedslug()
    {
        $model = AR_merchant::model()->findAll("restaurant_slug=:restaurant_slug",[':restaurant_slug'=>'']);
        if($model){
            foreach ($model as $items) {                
                if($items->save()){
                    dump("Succesful $items->merchant_id");
                }
            }
        } else dump(HELPER_NO_RESULTS);
    }

    public function actioncronjobs()
    {
        $this->pageTitle = t("Cron Jobs");

        $cron_key = CommonUtility::getCronKey();		
		$params = ['key'=>$cron_key];
		
		$cron_link[] = [
            'title'=>t("Change new order status"),
            'description'=>t("This cron job automates the process of updating order statuses within the system, ensuring timely and accurate changes in the order lifecycle based on predefined criteria or time triggers."),
            'link'=>[
                [
                   'link'=> "curl ".CommonUtility::getHomebaseUrl()."/task/runcron?".http_build_query($params)." >/dev/null 2>&1",
				   'label'=>t("run every (1)minute") 
                ],
                [
                    'link'=> "curl ".CommonUtility::getHomebaseUrl()."/task/orderautoaccept?".http_build_query($params)." >/dev/null 2>&1",
                    'label'=>t("run every (2)minute")
                ]
            ]		
		];

        $cron_link[] = [
            'title'=>t("Merchant APP"),
            'description'=>t("Automated alerts for new orders; ensures continuous notifications to the merchant app, enabling prompt action and order processing."),
            'link'=>[
                [
                   'link'=> "curl ".CommonUtility::getHomebaseUrl()."/task/getneworder?".http_build_query($params)." >/dev/null 2>&1",
				   'label'=>t("run every (2)minute or (5)minute") 
                ],                
            ]		
		];

        $cron_link[] = [
            'title'=>t("Driver APP"),
            'description'=>t("Automates order assignment in the driver app and calculates rider earnings, streamlining the allocation process and ensuring accurate compensation based on completed deliveries."),
            'link'=>[
                [
                   'link'=> "curl ".CommonUtility::getHomebaseUrl()."/task/assignorder?".http_build_query($params)." >/dev/null 2>&1",
				   'label'=>t("run every (2)minute")
                ],                
                [
                    'link'=> "curl ".CommonUtility::getHomebaseUrl()."/task/riderearningsrequery?".http_build_query($params)." >/dev/null 2>&1",
                    'label'=>t("run every (5)minute")
                 ],                
            ]		
		];

        $cron_link[] = [
            'title'=>t("Loyalty Points"),
            'description'=>t("This cron job automates the process of setting points to expire at the year-end, ensuring the timely management of expiring points within the system."),
            'link'=>[
                [
                   'link'=> "curl ".CommonUtility::getHomebaseUrl()."/task/setpointsexpiry?".http_build_query($params)." >/dev/null 2>&1",
				   'label'=>t("run at before end of the year") 
                ],                
            ]		
		];

        $kitchen_available = CommonUtility::checkModuleAddon("Karenderia Kitchen App");
        if($kitchen_available){
            $cron_link[] = [
                'title'=>t("Kitchen App"),
                'description'=>t(" Automated alerts for new orders; ensures continuous notifications to the kitchen app, enabling prompt action and order processing. "),
                'link'=>[
                    [
                       'link'=> "curl ".CommonUtility::getHomebaseUrl()."/task/kitchenpendingorder?".http_build_query($params)." >/dev/null 2>&1",
                       'label'=>t("run every (2)minute or (5)minute")
                    ],
                    [
                        'link'=> "curl ".CommonUtility::getHomebaseUrl()."/task/moveorderstocurrent?".http_build_query($params)." >/dev/null 2>&1",
                        'label'=>t("run every (1)minute or (2)minute")
                     ],
                ]
            ];
        }

		$message = t("below are the cron jobs that needed to run in your cpanel as http cron");

        $this->render('cron-jobs',[
            'cron_link'=>$cron_link,
			'message'=>$message,
            'links'=>array(
	            t("Utilities"),
                $this->pageTitle,
		    ),	  
        ]);
    }

    public function actionclear_cache()
    {
        $this->pageTitle = t("Clear Cache");
        Yii::app()->cache->flush();
        $this->render('clear_cache',[
            'links'=>array(
	            t("Utilities"),
                $this->pageTitle,
		    ),
        ]);
        // sleep(1);
        // $this->redirect(array('/admin/dashboard'));
    }

}
// end class