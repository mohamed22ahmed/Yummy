<?php
require_once 'LeagueCSV/vendor/autoload.php';
use League\Csv\Writer;
use League\Csv\Reader;
use League\Csv\Statement;

class AttributesController extends CommonController
{
	public function beforeAction($action)
	{										
		InlineCSTools::registerStatusCSS();
		return true;
	}
		
	public function actioncuisine_list()
	{		
		$this->pageTitle=t("Cuisine list");
		$action_name='cuisine_list';
		$delete_link = Yii::app()->CreateUrl("attributes/cuisine_delete");
		
		ScriptUtility::registerScript(array(
		  "var action_name='$action_name';",
		  "var delete_link='$delete_link';",
		),'action_name');
		
		if(Yii::app()->params['isMobile']==TRUE){
			$tpl = 'cuisine_list_app';
		} else $tpl = 'cuisine_list';
				
		$this->render($tpl,array(
		  'link'=>Yii::app()->CreateUrl(Yii::app()->controller->id."/cuisine_create"),
		  'sort_link'=>Yii::app()->CreateUrl(Yii::app()->controller->id."/cuisine_sort")
		));	
	}

	public function actioncuisine_sort()
	{
		$this->pageTitle=t("Cuisine Sort");
		CommonUtility::setMenuActive('.attributes',".attributes_cuisine_list");

		$data = [];
		$model = new AR_cuisine_sort();

		if(isset($_POST['id'])){
			$data = $_POST['id'];
			if(is_array($data) && count($data)>=1){				
				foreach ($data as $index=> $cuisine_id) {					
					$model = AR_cuisine_sort::model()->find("cuisine_id=:cuisine_id",[						
						':cuisine_id'=>intval($cuisine_id)
					]);
					if($model){
						$model->sequence = $index;
						if(!$model->save()){							
						}
					}
				}
				CCacheData::add();
				Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
				$this->refresh();
			}						
		}
		
		try {
			$data = CCuisine::getList( Yii::app()->language ,'','sort');
		} catch (Exception $e) {			
		}				

		$this->render('//tpl/sort',[
			'data'=>$data,
			'model'=>$model,
			'links'=>array(
	            t("All Cuisine")=>array(Yii::app()->controller->id.'/cuisine_list'),        
                $this->pageTitle,
		    ),	    	
		]);
	}
	
	public function actioncuisine_create($update=false)
	{
		$this->pageTitle = $update==false? t("Add Cuisine") :  t("Update Cuisine");
		CommonUtility::setMenuActive('.attributes',".attributes_cuisine_list");
		
		$multi_language = CommonUtility::MultiLanguage();
		$upload_path = CMedia::adminFolder();
		
		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_cuisine::model()->findByPk( $id );				
			if(!$model){				
				$this->render("error");				
				Yii::app()->end();
			}	
		} else $model=new AR_cuisine;	
		
		$model->multi_language = $multi_language;
		if(isset($_POST['AR_cuisine'])){
			$model->attributes=$_POST['AR_cuisine'];
			if($model->validate()){		
										
				if(isset($_POST['photo'])){
					if(!empty($_POST['photo'])){
						$model->featured_image = $_POST['photo'];
						$model->path = isset($_POST['path'])?$_POST['path']:$upload_path;
					} else $model->featured_image = '';
				} else $model->featured_image = '';

				if(isset($_POST['icon'])){
					if(!empty($_POST['icon'])){
						$model->icon = $_POST['icon'];
						$model->icon_path = isset($_POST['icon_path'])?$_POST['icon_path']:$upload_path;
					} else $model->icon = '';
				} else $model->icon = '';

				if($model->save()){
					if(!$update){
					   $this->redirect(array('attributes/cuisine_list'));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			}
		}	
		
				
		$data  = array();
		if($update && !isset($_POST['AR_cuisine'])){
			$translation = AttributesTools::GetFromTranslation($id,'{{cuisine}}',
			'{{cuisine_translation}}',
			'cuisine_id',
			array('cuisine_id','cuisine_name'),
			array('cuisine_name'=>'cuisine_name_translation')
			);					
			$data['cuisine_name_translation'] = isset($translation['cuisine_name'])?$translation['cuisine_name']:'';
		}
						
		$fields[]=array(
		  'name'=>'cuisine_name_translation'
		);				
        $types = ['Restaurant', 'Market', 'Home Cooking'];
		$this->render("cuisine_create",array(
		    'model'=>$model,
		    'multi_language'=>$multi_language,
		    'language'=>AttributesTools::getLanguage(),
		    'fields'=>$fields,
		    'data'=>$data,
            'types' => $types,
		    'status'=>(array)AttributesTools::StatusManagement('post'),
		    'upload_path'=>$upload_path,
		));
	}
	
	public function actioncuisine_update()
	{		
		$this->actioncuisine_create(true);
	}
	
	public function actioncuisine_delete()
	{
		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_cuisine::model()->findByPk( $id );
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array('attributes/cuisine_list'));			
		} else $this->render("error");
	}
	
	public function actiondish_list()
	{
		$this->pageTitle=t("Dishes list");
		$action_name='dishes_list';
		$delete_link = Yii::app()->CreateUrl("attributes/dishes_delete");
		
		ScriptUtility::registerScript(array(
		  "var action_name='$action_name';",
		  "var delete_link='$delete_link';",
		),'action_name');
		
		if(Yii::app()->params['isMobile']==TRUE){
			$tpl = 'dish_list_app';
		} else $tpl = 'dish_list';
		
		$this->render( $tpl ,array(
			'link'=>Yii::app()->CreateUrl(Yii::app()->controller->id."/dish_create")
		));
	}
	
	public function actiondish_create($update=false)
	{
		$this->pageTitle = $update==false? t("Add Dishes") : t("Update Dishes");
		CommonUtility::setMenuActive('.attributes',".attributes_dish_list");
		
		$id='';
		$multi_language = CommonUtility::MultiLanguage();
		$upload_path = CMedia::adminFolder();
		
		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_dishes::model()->findByPk( $id );				
			if(!$model){				
				$this->render("error");				
				Yii::app()->end();
			}	
			
		} else {
			$model=new AR_dishes;
			$model->scenario='new';
		}

		$model->multi_language = $multi_language;	
		
		if(isset($_POST['AR_dishes'])){
			$model->attributes=$_POST['AR_dishes'];
			if($model->validate()){
																		
				if(isset($_POST['photo'])){
					if(!empty($_POST['photo'])){
						$model->photo = $_POST['photo'];
						$model->path = isset($_POST['path'])?$_POST['path']:$upload_path;
					} else $model->photo = '';
				} else $model->photo = '';
								
				if($model->save()){
					if(!$update){
					   $this->redirect(array('attributes/dish_list'));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error', CommonUtility::parseModelErrorToString($model->getErrors()) );	
			} else {				
				Yii::app()->user->setFlash('error', CommonUtility::parseModelErrorToString($model->getErrors()) );	
			}
		}	
		
		$data  = array();
		if($update && !isset($_POST['AR_dishes'])){
			$data['dish_name_trans'] = AttributesTools::getDishes($id);			
		} 
		
		$fields[]=array(
		  'name'=>'dish_name_trans'
		);
						
		$this->render("dish_create",array(
		    'model'=>$model,
		    'multi_language'=>$multi_language,
		    'language'=>AttributesTools::getLanguage(),
		    'status'=>(array)AttributesTools::StatusManagement('post'),
		    'fields'=>$fields,
		    'data'=>$data,
		    'upload_path'=>$upload_path,
		));
	}
	
	public function actiondish_update()
	{
		$this->actiondish_create(true);
	}
	
	public function actiondishes_delete()
	{
		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_dishes::model()->findByPk( $id );
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array('attributes/dish_list'));			
		} else $this->render("error");
	}
	
	public function actiontag_list()
	{
		$this->pageTitle=t("Tags list");
		$action_name='tags_list';
		$delete_link = Yii::app()->CreateUrl("attributes/tags_delete");
		
		ScriptUtility::registerScript(array(
		  "var action_name='$action_name';",
		  "var delete_link='$delete_link';",
		),'action_name');
		
		if(Yii::app()->params['isMobile']==TRUE){
			$tpl = 'tag_list_app';
		} else $tpl = 'tag_list';				
		
		$this->render( $tpl ,array(
			'link'=>Yii::app()->CreateUrl(Yii::app()->controller->id."/tags_create")
		));
	}
	
	public function actiontags_create($update=false)
	{
		$this->pageTitle = $update==false? t("Add Tags") : t("Update Tags");
		CommonUtility::setMenuActive('.attributes',".attributes_tag_list");
		
		$id='';
		$multi_language = CommonUtility::MultiLanguage();
        $upload_path = CMedia::adminFolder();

		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_tags::model()->findByPk( $id );				
			if(!$model){				
				$this->render("error");				
				Yii::app()->end();
			}	
			
		} else {
			$model=new AR_tags;				
		}
		
		$model->multi_language = $multi_language;	
		
		if(isset($_POST['AR_tags'])){			
			$model->attributes=$_POST['AR_tags'];
			if($model->validate()){
                if(isset($_POST['photo'])){
                    if(!empty($_POST['photo'])){
                        $model->featured_image = $_POST['photo'];
                        $model->path = isset($_POST['path'])?$_POST['path']:$upload_path;
                    } else $model->featured_image = '';
                } else $model->featured_image = '';
				if($model->save()){
					if(!$update){
					   $this->redirect(array('attributes/tag_list'));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			}
		}
		
		$data  = array();
		if($update && !isset($_POST['AR_tags'])){
			$translation = AttributesTools::GetFromTranslation($id,'{{tags}}',
			'{{tags_translation}}',
			'tag_id',
			array('tag_id','tag_name','description'),
			array('tag_name'=>'tag_name_trans','description'=>"description_trans")
			);					
			$data['tag_name_translation'] = isset($translation['tag_name'])?$translation['tag_name']:'';			
			$data['description_translation'] = isset($translation['description'])?$translation['description']:'';
		}		
		
		$fields[]=array(
		  'name'=>'tag_name_translation'
		);
		$fields[]=array(
		  'name'=>'description_translation',
		  'placeholder'=>"Enter [lang] description here",
		  'type'=>"textarea"
		);
        $types = ['Restaurant', 'Market', 'Home Cooking'];

		$this->render("tags_create",array(
		    'model'=>$model,
		    'multi_language'=>$multi_language,
		    'language'=>AttributesTools::getLanguage(),		    
		    'fields'=>$fields,
            'types' => $types,
            'data'=>$data,
            'upload_path'=>$upload_path,
		    'links'=>array(
			    t("All Tags")=>array('attributes/tag_list'),        
			    $this->pageTitle,
			)
		));
	}
	
	public function actiontags_update()
	{
	    $this->actiontags_create(true);
	}
	
	public function actiontags_delete()
	{
		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_tags::model()->findByPk( $id );
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array('attributes/tag_list'));			
		} else $this->render("error");
	}
	
	
	public function actionorder_status()
	{
		$this->pageTitle=t("Status list");
		$action_name='status_list';
		$delete_link = Yii::app()->CreateUrl("attributes/status_delete");
		
		ScriptUtility::registerScript(array(
		  "var action_name='$action_name';",
		  "var delete_link='$delete_link';",
		),'action_name');
							
		if(Yii::app()->params['isMobile']==TRUE){
			$tpl = 'status_list_app';
		} else $tpl = 'status_list';
		
		$this->render( $tpl ,array(
			'link'=>Yii::app()->CreateUrl(Yii::app()->controller->id."/status_create")
		));
	}
	
	public function actionstatus_create($update=false)
	{
		$this->pageTitle = $update==false? t("Add Status") : t("Update Status");
		CommonUtility::setMenuActive('.attributes',".attributes_order_status");
		
		$id='';
		$multi_language = CommonUtility::MultiLanguage();
		
		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_status::model()->findByPk( $id );				
			if(!$model){				
				$this->render("error");				
				Yii::app()->end();
			}	
			
		} else {
			$model=new AR_status;				
		}
		
		$model->multi_language = $multi_language;

		if(isset($_POST['AR_status'])){
			$model->attributes=$_POST['AR_status'];
			if($model->validate()){
				if($model->save()){
					if(!$update){
					   $this->redirect(array('attributes/order_status'));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			}
		}
		
		$data  = array();
		if($update && !isset($_POST['AR_status'])){
			$translation = AttributesTools::GetFromTranslation($id,'{{order_status}}',
			'{{order_status_translation}}',
			'stats_id',
			array('stats_id','description'),
			array('description'=>'description_translation')
			);			
			$data['description_translation'] = isset($translation['description'])?$translation['description']:'';			
		}		
		
		$fields[]=array(
		  'name'=>'description_translation',
		  'placeholder'=>"Enter [lang] status here"
		);
		
		if($update){			
			$this->render("//tpl/submenu_tpl",array(		    
				'template_name'=>"//attributes/status_create",
				'widget'=>'WidgetStatus',					
				'params'=>array(  			     				   
				   'model'=>$model,
			       'multi_language'=>$multi_language,
			       'language'=>AttributesTools::getLanguage(),
			       'fields'=>$fields,
			       'data'=>$data,
				   'group_list'=>AttributesTools::OrderStatusGroup(),
				   'links'=>array(		 
				      t("Status list")=>array('attributes/order_status'),        
	                  $this->pageTitle,                           
				   ),
				 )
			));		
		} else {
			$this->render("status_create",array(
			    'model'=>$model,
			    'multi_language'=>$multi_language,
			    'language'=>AttributesTools::getLanguage(),
			    'fields'=>$fields,
			    'data'=>$data ,
				'group_list'=>AttributesTools::OrderStatusGroup()
			));
		}
	}
	
	public function actionstatus_update()
	{
	    $this->actionstatus_create(true);
	}
	
	public function actionstatus_delete()
	{
		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_status::model()->findByPk( $id );
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array('attributes/order_status'));			
		} else $this->render("error");
	}
		
	public function actionstatus_actions()
	{
		$id = intval(Yii::app()->input->get("id"));
		
		$model =  AR_status::model()->findbyPK($id);	
		if(!$model){
			$this->render("error");				
			Yii::app()->end();
		}
		
		$this->pageTitle = t("Status actions");
		CommonUtility::setMenuActive('.attributes',".attributes_order_status");
				
		$action_name='status_actionlist';		
		$delete_link = Yii::app()->CreateUrl("attributes/status_action_delete/stats_id/".$id);
		
		ScriptUtility::registerScript(array(
		  "var action_name='$action_name';",
		  "var delete_link='$delete_link';",
		),'action_name');
		
		$this->render("//tpl/submenu_tpl",array(		    
			'template_name'=>"//attributes/status_action_list",
			'widget'=>'WidgetStatus',					
			'params'=>array(  		
			   'id'=>$id,
			   'link'=>Yii::app()->createUrl('attributes/create_action',array('id'=>$id)),
			   'links'=>array(		 
			      t("Status list")=>array('attributes/order_status'),        
                  $this->pageTitle,  
                  $model->description
			   ),
			 )
		));		
	}
	
	public function actioncreate_action($update=false)
	{
		$id = intval(Yii::app()->input->get("id"));
		
		$model_status =  AR_status::model()->findbyPK($id);	
		if(!$model_status){
			$this->render("error");				
			Yii::app()->end();
		}
		
		$this->pageTitle = $update==false? t("Add actions") : t("Update actions");
		CommonUtility::setMenuActive('.attributes',".attributes_order_status");
		CommonUtility::setSubMenuActive(".attributes-menu-wrap",'.status-actions');
				
		if($update){
			$action_id = (integer) Yii::app()->input->get('action_id');			
			$model = AR_order_status_actions::model()->findByPk( $action_id );				
			if(!$model){				
				$this->render("error");				
				Yii::app()->end();
			}				
		} else $model = new AR_order_status_actions;
		
		if(isset($_POST['AR_order_status_actions'])){
			$model->attributes=$_POST['AR_order_status_actions'];
			if($model->validate()){
				$model->stats_id = intval($id);
				if($model->save()){
					if(!$update){
					   $this->redirect(array('attributes/status_actions','id'=>$id));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			}
		}
		
		
		$template_list = CommonUtility::getDataToDropDown("{{templates}}",'template_id','template_name',"",
		"ORDER BY template_name ASC");
		
		$action_type_list = CommonUtility::getDataToDropDown("{{admin_meta}}",'meta_value1','meta_value',"
		WHERE meta_name='action_type' ","ORDER BY meta_value ASC");
				
		$this->render("//tpl/submenu_tpl",array(		    
			'template_name'=>"//attributes/status_create_action",
			'widget'=>'WidgetStatus',					
			'params'=>array(  					   
			   'link'=>Yii::app()->createUrl('attributes/create_action',array('id'=>$id)),
			   'links'=>array(		 
			      t("Status list")=>array('attributes/order_status'),        
                  $this->pageTitle,      	                  
                  $model_status->description
			   ),
			   'model'=>$model,
			   'action_type_list'=>$action_type_list,
			   'template_list'=>$template_list,
			 )
		));		
	}
	
	public function actionupdate_action()
	{
		$this->actioncreate_action(true);
	}
	
	public function actionstatus_action_delete()
	{		
		$id = (integer) Yii::app()->input->get('id');			
		$stats_id = (integer) Yii::app()->input->get('stats_id');			
		$model = AR_order_status_actions::model()->findByPk( $id );
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array('attributes/status_actions','id'=>$stats_id));			
		} else $this->render("error");
	}
		
	public function actioncurrency()
	{
		$this->pageTitle=t("Currency list");
		$action_name='currency_list';
		$delete_link = Yii::app()->CreateUrl("attributes/currency_delete");
		
		$ajaxurl = Yii::app()->createUrl("/backend");
		$is_mobile = Yii::app()->params['isMobile'];
		
		ScriptUtility::registerScript(array(
		  "var ajaxurl='$ajaxurl';",
		  "var is_mobile='$is_mobile';"
		),'admin_script');
		
		ScriptUtility::registerScript(array(
		  "var action_name='$action_name';",
		  "var delete_link='$delete_link';",
		),'action_name');
		
				
		$this->render("currency_list",array(
		  'link'=>Yii::app()->CreateUrl(Yii::app()->controller->id."/currency_create")
		));
	}
	
	public function actioncurrency_create($update=false)
	{
		$this->pageTitle = $update==false? t("Add Currency") : t("Update Currency");
		CommonUtility::setMenuActive('.attributes',".attributes_currency");
		
		$id='';
		$multi_language = CommonUtility::MultiLanguage();
		
		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_currency::model()->findByPk( $id );				
			if(!$model){				
				$this->render("error");				
				Yii::app()->end();
			}	
			
		} else {			
			$model=new AR_currency;							
		}

		if(isset($_POST['AR_currency'])){
			$model->attributes=$_POST['AR_currency'];
			if($model->validate()){						
				if($resp = AttributesTools::CurrencyDetails($model->currency_code)){											
					if(empty($model->currency_symbol)){												
						$model->currency_symbol=$resp['symbol'];
					}					
					$model->description=$resp['currency_name'];
				}			
				
				$model->exchange_rate = (float)$model->exchange_rate;
				$model->exchange_rate_fee = (float)$model->exchange_rate_fee;
				$model->number_decimal = (integer)$model->number_decimal;
				$model->as_default = (integer)$model->as_default;
				
				if($model->save()){
					if(!$update){
					   $this->redirect(array('attributes/currency'));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			} else {
				//dump($model);die();
			}
		}
		
		$model->exchange_rate = Price_Formatter::convertToRaw($model->exchange_rate,4,true);
		$model->exchange_rate_fee = Price_Formatter::convertToRaw($model->exchange_rate_fee,2,true);
		
		$this->render("currency_create",array(
		    'model'=>$model,
		    'currency_list'=>AttributesTools::currencyListSelection(),
		    'currency_position'=>AttributesTools::CurrencyPosition(), 
			'status'=>(array)AttributesTools::StatusManagement('post'),
		));
	}
	
	public function actioncurrency_update()
	{
	    $this->actioncurrency_create(true);
	}
	
	public function actioncurrency_delete()
	{
		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_currency::model()->findByPk( $id );
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array('attributes/currency'));			
		} else $this->render("error");
	}
	
	public function actionpages_list()
	{
		$this->pageTitle=t("Pages list");
		$action_name='pages_list';
		$delete_link = Yii::app()->CreateUrl("attributes/pages_delete");
		
		ScriptUtility::registerScript(array(
		  "var action_name='$action_name';",
		  "var delete_link='$delete_link';",
		),'action_name');
							
		if(Yii::app()->params['isMobile']==TRUE){
			$tpl = 'pages_list_app';
		} else $tpl = 'pages_list';
		
		$this->render( $tpl ,array(
			'link'=>Yii::app()->CreateUrl(Yii::app()->controller->id."/pages_create")
		));
	}
	
	public function actionpages_create($update=false)
	{
		$this->pageTitle = $update==false? t("Add Page") : t("Update Page");
		CommonUtility::setMenuActive('.attributes',".attributes_pages_list");
		
		$id='';
		$multi_language = CommonUtility::MultiLanguage();
		$upload_path = CMedia::adminFolder();
		
		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_pages::model()->findByPk( $id );	
					
			if(!$model){				
				$this->render("error");				
				Yii::app()->end();
			}	
			
		} else {			
			$model=new AR_pages;							
		}

		$model->multi_language = $multi_language;
		
		if(isset($_POST['AR_pages'])){
			$model->attributes=$_POST['AR_pages'];
			if($model->validate()){
				$model->slug = CommonUtility::SeoURL($model->slug);
								
				if(isset($_POST['photo'])){
					if(!empty($_POST['photo'])){
						$model->meta_image = $_POST['photo'];
						$model->path = isset($_POST['path'])?$_POST['path']:$upload_path;
					} else $model->meta_image = '';
				} else $model->meta_image = '';
												
				if($model->save()){
					if(!$update){
					   $this->redirect(array('attributes/pages_list'));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			} else {
				//dump($model);die();
			}
		}
		
		$data  = array();
		if($update){
			$translation = AttributesTools::pagesTranslation($id);
			$data['title_translation'] = isset($translation['title'])?$translation['title']:'';
			$data['long_content_translation'] = isset($translation['long_content'])?$translation['long_content']:'';
		}		
		
		$fields[]=array(
		  'name'=>'title_translation',
		  'placeholder'=>"Enter [lang] title here"
		);
		$fields[]=array(
		  'name'=>'long_content_translation',
		  'placeholder'=>"Enter [lang] content here",
		  'type'=>"textarea",
		  'class'=>"summernote"
		);
					
		$this->render("pages_create",array(
		    'model'=>$model,
		    'upload_path'=>$upload_path,
		    'links'=>array(
	            t("All Pages")=>array('attributes/pages_list'),        
                $this->pageTitle,
		    ),
		    'status_list'=>(array)AttributesTools::StatusManagement('post'),
		    'multi_language'=>$multi_language,
		    'language'=>AttributesTools::getLanguage(),
		    'fields'=>$fields,
		    'data'=>$data,		    
		));
	}	
	
	public function actionpage_update()
	{
	    $this->actionpages_create(true);
	}
	
	public function actionpages_delete()
	{
		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_pages::model()->findByPk( $id );
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array('attributes/pages_list'));			
		} else $this->render("error");
	}
	
	public function actionpages_image_delete()
	{
		$id = Yii::app()->input->get('id');			
		$page = Yii::app()->input->get('page');			
		$model = AR_pages::model()->findByPk( $id );				
		if($model){					
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array($page));
		} else $this->render("error");
	}
		
    public function actionlanguage_list()
	{
		$this->pageTitle=t("Languages list");
		$action_name='language_list';
		$delete_link = Yii::app()->CreateUrl("attributes/language_delete");
		
		ScriptUtility::registerScript(array(
		  "var action_name='$action_name';",
		  "var delete_link='$delete_link';",
		),'action_name');
								
		$tpl = 'language_list';		

		$this->render( $tpl ,array(
			'link'=>Yii::app()->CreateUrl(Yii::app()->controller->id."/language_create")
		));
	}	
	
	public function actionlanguage_settings()
	{
		$this->pageTitle=t("Languages settings");
		CommonUtility::setMenuActive('.attributes',".attributes_language_list");
		
		$model=new AR_option;
		$model->scenario=Yii::app()->controller->action->id;		

		$options = array('enabled_language_bar','default_language',
		  'enabled_language_bar_front','enabled_language_customer_app','enabled_language_rider_app',
		  'enabled_language_merchant_app','enabled_language_tableside_app','enabled_language_kicthen_app'
	    );
		
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
				if(OptionsTools::save($options, $model)){
					Yii::app()->user->setFlash('success',CommonUtility::t(Helper_settings_saved));
					$this->refresh();
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			}
		}
		
		if($data = OptionsTools::find($options)){
			foreach ($data as $name=>$val) {
				$model[$name]=$val;
			}		
		}

		$language_list = CommonUtility::getDataToDropDown("{{language}}",'code','title',"
		WHERE status='publish'
		",'Order by sequence, date_created ASC');

		$this->render('language_settings', [
			'model'=>$model,
			'language_list'=>$language_list,
			'links'=>array(
	            t("All Language")=>array('attributes/language_list'),        
                $this->pageTitle,
		    ),	    		    
		] );
	}
	
	public function actionlanguage_create($update=false)
	{
		$this->pageTitle = $update==false? t("Add Language") : t("Update Language");
		CommonUtility::setMenuActive('.attributes',".attributes_language_list");
		
		$id='';
		
		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_language::model()->findByPk( $id );				
			if(!$model){				
				$this->render("error");				
				Yii::app()->end();
			}	
			
		} else {			
			$model=new AR_language;							
		}
		
		$country_list = AttributesTools::CountryList();		
		
		if(isset($_POST['AR_language'])){
			$model->attributes=$_POST['AR_language'];
			if($model->validate()){		

				/*if($model->isNewRecord){
					CommonUtility::createLanguageFolder($model->code);					
				}*/
							
				if($model->save()){
					if(!$update){
					   $this->redirect(array('attributes/language_list'));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			} 
		}
				
		$this->render("language_create",array(
		    'model'=>$model,
		    'links'=>array(
	            t("All Language")=>array('attributes/language_list'),        
                $this->pageTitle,
		    ),	    		    
		    'status_list'=>(array)AttributesTools::StatusManagement('post'),
		    'country_list'=>$country_list,
		    'locale'=>AttributesTools::getLocaleLanguages()
		));
	}
	
	public function actionlanguage_update()
	{
		$this->actionlanguage_create(true);
	}
	
	public function actionlanguage_delete()
	{
		$id = Yii::app()->input->get('id');							
		$model = AR_language::model()->findByPk( $id );				
		if($model){			
			$model->delete(); 			
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array('attributes/language_list'));
		} else $this->render("error");
	}
	
	public function actionlanguage_translation()
	{
		$this->pageTitle = t("Translation");
		CommonUtility::setMenuActive('.attributes',".attributes_language_list");
		
		$category = Yii::app()->input->get('category');
		$id = Yii::app()->input->get('id');
		$key = Yii::app()->input->get('key');
		
		$model = AR_language::model()->find("id=:id",array(':id'=>intval($id)));		
		
		if($model){
			
			if(isset($_POST['yt0'])){

				if(DEMO_MODE){			
					$this->render('//tpl/error',array(  
						  'error'=>array(
							'message'=>t("Modification not available in demo")
						  )
						));	
					return false;
				}

				$params = array(); $ids = array();
				$translation_data = Yii::app()->input->post('translation');
				if(is_array($translation_data) && count($translation_data)>=1){					
					foreach ($translation_data as $id=>$translation) {		
						$ids[]=$id;
						if(!empty($translation)){
							$params[] = array(
							  'id'=>intval($id),
							  'language'=>$model->code,
							  'translation'=>$translation
							);
						}								
					}			
										
					Yii::app()->db->createCommand("DELETE FROM {{message}}
					WHERE language=".q($model->code)."
					AND id IN (".implode(",",$ids).")
					")->query();
					
					if(is_array($params) && count($params)>=1){						
						$builder=Yii::app()->db->schema->commandBuilder;
			            $command=$builder->createMultipleInsertCommand('{{message}}',$params);
			            $command->execute();			            
					}
					Yii::app()->cache->flush();
					Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
			        $this->refresh();
				}				
			}
			
			$page = intval(Yii::app()->input->get('page'));
			$length  = Yii::app()->params['media_list_limit'];
			
			$page = $page>0? intval($page)-1 :0;
		    $criteria=new CDbCriteria();		    
		    $criteria->alias ="a";
		    $criteria->select = "a.id,a.category, a.message,
		    (
		      select translation from {{message}}
		      where id = a.id and language=".q($model->code)."
		    )  as translation
		    ";		    
		    $criteria->condition="category=:category";
			$criteria->params = array(		 
			 ':category'=>$category,			 
			);

			if(!empty($key)){				
				$criteria->addSearchCondition('message',  $key );
			}
						
			$criteria->order = "id DESC";
			$count = AR_sourcemessage::model()->count($criteria); 
			$pages=new CPagination( intval($count) );
	        $pages->setCurrentPage( intval($page) );        
	        $pages->pageSize = intval($length);
	        $pages->applyLimit($criteria);        
	        				
	        $models = AR_sourcemessage::model()->findAll($criteria);	

			$this->render("//attributes/language_translation",array(
			  'models'=>$models,
			  'pages'=>$pages,
			  'id'=>$id,
			  'category'=>$category,
			  'key'=>$key,
			  'links'=>array(
		            t("All Language")=>array('attributes/language_list'),        
		            $model->title=>array('attributes/language_translation', 'id'=>$id,'category'=>$category  ),                  
	                $this->pageTitle,
					$category,
			    ),
			));
		} else {
			$this->render('//tpl/error',array(  
	          'error'=>array(
	            'message'=>t(Helper_not_found)
	          )
	        ));	
		}
	}
	
	public function actionlanguage_create_key()
	{
		$this->pageTitle = t("Translation Key");
		CommonUtility::setMenuActive('.attributes',".attributes_language_list");
		
		$category = Yii::app()->input->get('category');
		$id = Yii::app()->input->get('id');
		
		$model = AR_language::model()->find("id=:id",array(':id'=>intval($id)));		
		
		if($model){						
			if(isset($_POST['yt0'])){			
				$error = '';
				$message = Yii::app()->input->post('message');
				if(is_array($message) && count($message)>=1){
					$last_id = AR_sourcemessage::getlastID();
					foreach ($message as $key=> $item) {
						if(!empty($item)){
							$models = new AR_sourcemessage;
							$models->id=intval($last_id)+$key;							
							$models->category=$category;
							$models->message=$item;
							if(!$models->save()){
								$error.= CommonUtility::parseModelErrorToString($models->getErrors(),"<br/>");
							} 
						}
					}					
				}
				
				if(!empty($error)){
					Yii::app()->user->setFlash('error', $error);
				} else {
					Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
				    $this->refresh();
				}
			}
			
			$this->render("language_create_key",array(			 
			 'links'=>array(
	            t("All Language")=>array('attributes/language_list'),                  
	            $model->title=>array('attributes/language_translation', 'id'=>$id,'category'=>$category  ),                  
	            $this->pageTitle,
		      ),	    		    
			));		
		} else {
			$this->render('//tpl/error',array(  
	          'error'=>array(
	            'message'=>t(Helper_not_found)
	          )
	        ));	
		}
	}

	public function actionlanguage_export()
	{
		if(DEMO_MODE){			
		    $this->render('//tpl/error',array(  
		          'error'=>array(
		            'message'=>t("Modification not available in demo")
		          )
		        ));	
		    return false;
		}
		
		$id = (integer) Yii::app()->input->get('id');	
		$category = Yii::app()->input->get('category');	
		$model = AR_language::model()->findByPk($id);
		if($model){
			if (!ini_get("auto_detect_line_endings")) {
				ini_set("auto_detect_line_endings", '1');
			}
			$title = CommonUtility::removeSpace($model->title,"-");
			$stmt = "SELECT a.id, a.category, ".q($model->code).", a.message,
			b.translation
			FROM {{sourcemessage}} a

			LEFT JOIN {{message}} b
			ON 
			a.id = b.id

			WHERE a.category=".q($category)."			
			AND b.language = ".q($model->code)."
			ORDER BY id ASC
			";									
			if($res = Yii::app()->db->createCommand($stmt)->queryAll()){												
				$csv = Writer::createFromFileObject(new SplTempFileObject());
				$csv->insertOne(['id', 'category', 'language', 'message', 'translation']);
				$csv->insertAll($res);
				$csv->output( $title . "_" . $category  . '.csv');
			} else {
				$this->render('//tpl/error',array(  
					'error'=>array(
					  'message'=>t(Helper_not_found)
					)
				  ));		
			}			
		} else {
			$this->render('//tpl/error',array(  
				'error'=>array(
				  'message'=>t(Helper_not_found)
				)
			  ));	
		}
	}

	public function actionlanguage_download()
	{
		if(DEMO_MODE){			
		    $this->render('//tpl/error',array(  
		          'error'=>array(
		            'message'=>t("Modification not available in demo")
		          )
		        ));	
		    return false;
		}
		
		$id = (integer) Yii::app()->input->get('id');	
		$category = Yii::app()->input->get('category');	
		$model = AR_language::model()->findByPk($id);
		if($model){
			if (!ini_get("auto_detect_line_endings")) {
				ini_set("auto_detect_line_endings", '1');
			}
			$title = CommonUtility::removeSpace($model->title,"-");
			$stmt = "SELECT a.id, a.category, ".q($model->code).", a.message, ".q("")."
			FROM {{sourcemessage}} a			
			WHERE a.category=".q($category)."
			ORDER BY id ASC
			";			
			if($res = Yii::app()->db->createCommand($stmt)->queryAll()){				
				$csv = Writer::createFromFileObject(new SplTempFileObject());
				$csv->insertOne(['id', 'category', 'language', 'message', 'translation']);
				$csv->insertAll($res);
				$csv->output( $title . "_" . $category  . '.csv');
			} else {
				$this->render('//tpl/error',array(  
					'error'=>array(
					  'message'=>t(Helper_not_found)
					)
				  ));		
			}			
		} else {
			$this->render('//tpl/error',array(  
				'error'=>array(
				  'message'=>t(Helper_not_found)
				)
			  ));	
		}
	}

	public function actionlanguage_import()
	{
		$this->pageTitle = t("Import language file");
		CommonUtility::setMenuActive('.attributes',".attributes_language_list");

		$model = new AR_message();

		if(isset($_POST['AR_message'])){

			if(DEMO_MODE){			
				$this->render('//tpl/error',array(  
					  'error'=>array(
						'message'=>t("Modification not available in demo")
					  )
					));	
				return false;
			}

			if (!ini_get("auto_detect_line_endings")) {
				ini_set("auto_detect_line_endings", '1');
			}
			$model->csv=CUploadedFile::getInstance($model,'csv');			
			$csv = Reader::createFromPath($model->csv->tempName, 'r');
			$csv->setHeaderOffset(0);
			$header = $csv->getHeader(); 
			$records = $csv->getRecords();
			$data = array();  $ids = array(); $language_code = '';
			foreach ($records as $key => $item) {
				if(isset($item['translation']))	{
					if(!empty($item['translation'])){									
						$ids[] = intval($item['id']);
						$language_code = $item['language'];
						$data[] = [
                           'id'=>$item['id'],
						   'language'=>trim($item['language']),
						   'translation'=>trim($item['translation']),
						];
					}
			    }
			}
			if(is_array($data) && count($data)>=1){							
				$criteria=new CDbCriteria();
				$criteria->condition = "language=:language ";		    
				$criteria->params  = array(			  
				':language'=>trim($language_code)
				);
				$criteria->addInCondition('id', (array) $ids );					
				AR_message::model()->deleteAll($criteria);
				
				$builder=Yii::app()->db->schema->commandBuilder;
				$command=$builder->createMultipleInsertCommand("{{message}}",$data);
				$command->execute();
				Yii::app()->user->setFlash('success', t("Succesfully imported") );
				$this->refresh();
			} else Yii::app()->user->setFlash('error', t("Invalid csv data") );					
		}

		$this->render("language_import",[
			'model'=>$model,
			'links'=>array(
	            t("All Language")=>array('attributes/language_list'), 
	            $this->pageTitle,
		      ),	    		    
		]);
	}
	
	public function actionstatus_mgt()
	{		
		$this->pageTitle=t("Status Management list");
		$action_name='status_mgt_list';
		$delete_link = Yii::app()->CreateUrl("attributes/status_mgt_delete");
		
		ScriptUtility::registerScript(array(
		  "var action_name='$action_name';",
		  "var delete_link='$delete_link';",
		),'action_name');
		
		$tpl = 'status_mgt_list';
		
		$this->render( $tpl ,array(
			 'link'=>Yii::app()->CreateUrl(Yii::app()->controller->id."/status_mgt_create")
		));				
	}
	
	public function actionstatus_mgt_create($update=false)
	{
		$this->pageTitle = $update==false? t("Add Status") : t("Update Status");
		CommonUtility::setMenuActive('.attributes',".attributes_status_mgt");
		
		$id='';
		$multi_language = CommonUtility::MultiLanguage();
		
		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_status_management::model()->findByPk( $id );				
			if(!$model){				
				$this->render("/admin/error",array(
				 'error'=>array(
				   'message'=>t(HELPER_RECORD_NOT_FOUND)
				 )
				));		
				Yii::app()->end();
			}	
			
		} else {			
			$model=new AR_status_management;							
		}
		
		$model->multi_language = $multi_language;
				
		
		if(isset($_POST['AR_status_management'])){
			$model->attributes=$_POST['AR_status_management'];
			if($model->validate()){		
						
				if($model->save()){
					if(!$update){
					   $this->redirect(array(Yii::app()->controller->id.'/status_mgt'));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			} 
		}
		
		$data  = array();
		if($update && !isset($_POST['AR_status_management'])){
			$translation = AttributesTools::GetFromTranslation($id,'{{status_management}}',
			'{{status_management_translation}}',
			'status_id',
			array('status_id','title'),
			array('title'=>'title_trans')
			);			
			$data['title_translation'] = isset($translation['title'])?$translation['title']:'';			
		}		
		
		$fields[]=array(
		  'name'=>'title_translation',
		  'placeholder'=>"Enter [lang] title here"
		);
				
		$this->render("status_mgt_create",array(
		    'model'=>$model,
		    'group'=>AttributesTools::statusGroup(),
		    'links'=>array(
	            t("All Status")=>array(Yii::app()->controller->id.'/status_mgt'),        
                $this->pageTitle,
		    ),	  		    
		    'multi_language'=>$multi_language,
		    'language'=>AttributesTools::getLanguage(),
		    'fields'=>$fields,
		    'data'=>$data
		));
	}	
	
	public function actionstatus_mgt_update()
	{
		$this->actionstatus_mgt_create(true);
	}
	
	public function actionstatus_mgt_delete()
	{
		$id = Yii::app()->input->get('id');							
		$model = AR_status_management::model()->findByPk( $id );				
		if($model){			
			$model->delete(); 			
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array(Yii::app()->controller->id.'/status_mgt'));
		} else $this->render("/admin/error",array(
				 'error'=>array(
				   'message'=>t(HELPER_RECORD_NOT_FOUND)
				 )
				));		
	}
	
	public function actionservices_list()
	{		
		$this->pageTitle=t("Services list");
		$action_name='services_list';
		$delete_link = Yii::app()->CreateUrl("attributes/services_delete");
		
		ScriptUtility::registerScript(array(
		  "var action_name='$action_name';",
		  "var delete_link='$delete_link';",
		),'action_name');
				
		if(Yii::app()->params['isMobile']==TRUE){
			$tpl = 'services_list_app';
		} else $tpl = 'services_list';
		
		$this->render( $tpl ,array(
		   'link'=>Yii::app()->CreateUrl(Yii::app()->controller->id."/services_create"),
		   'sort_link'=>Yii::app()->CreateUrl(Yii::app()->controller->id."/sort_services"),
		));
	}
	
	public function actionservices_create($update=false)
	{
		$this->pageTitle = $update==false? t("Add Services") : t("Update Services");
		CommonUtility::setMenuActive('.attributes',".attributes_services_list");
		
		$id='';
		$multi_language = CommonUtility::MultiLanguage();
		
		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_services::model()->findByPk( $id );				
			if(!$model){				
				$this->render("/admin/error",array(
				 'error'=>array(
				   'message'=>t(HELPER_RECORD_NOT_FOUND)
				 )
				));		
				Yii::app()->end();
			}	
			
			$fee = AR_services_fee::model()->find('merchant_id=:merchant_id AND service_id=:service_id', 
		    array(':merchant_id'=>0, ':service_id'=>$model->service_id ));
		    if($fee){
			    $model->service_fee = floatval($fee->service_fee);
				$model->charge_type = $fee->charge_type;
				$model->small_order_fee = floatval($fee->small_order_fee);
				$model->small_less_order_based = floatval($fee->small_less_order_based);
		    }
		} else {			
			$model=new AR_services;							
		}
		
		$model->multi_language = $multi_language;
					
		
		if(isset($_POST['AR_services'])){
			$model->attributes=$_POST['AR_services'];
			if($model->validate()){		
						
				if($model->save()){
					if(!$update){
					   $this->redirect(array(Yii::app()->controller->id.'/services_list'));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			} 
		}
		
		$data  = array();
		if($update && !isset($_POST['AR_services'])){
			$translation = AttributesTools::GetFromTranslation($id,'{{services}}',
			'{{services_translation}}',
			'service_id',
			array('service_id','service_name'),
			array('service_name'=>'service_name_trans')
			);					
			$data['service_name_trans'] = isset($translation['service_name'])?$translation['service_name']:'';			
		}		
				
		$fields[]=array(
		  'name'=>'service_name_trans',
		  'placeholder'=>"Enter [lang] title here"
		);
			
		$model->service_fee = Price_Formatter::convertToRaw($model->service_fee,2,true);
				
		$this->render("services_create",array(
		    'model'=>$model,
		    'group'=>AttributesTools::statusGroup(),
		    'links'=>array(
	            t("All Services")=>array(Yii::app()->controller->id.'/services_list'),        
                $this->pageTitle,
		    ),	  		    
		    'multi_language'=>$multi_language,
		    'language'=>AttributesTools::getLanguage(),
		    'fields'=>$fields,
		    'data'=>$data,
		    'status'=>(array)AttributesTools::StatusManagement('post'),
			'charge_type'=>AttributesTools::TipType()
		));
	}	
	
	public function actionservices_update()
	{
		$this->actionservices_create(true);
	}
	
	public function actionservices_delete()
	{
		$id = Yii::app()->input->get('id');							
		$model = AR_services::model()->findByPk( $id );				
		if($model){			
			$model->delete(); 			
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array(Yii::app()->controller->id.'/services_list'));
		} else $this->render("/admin/error",array(
				 'error'=>array(
				   'message'=>t(HELPER_RECORD_NOT_FOUND)
				 )
				));		
	}
	
	public function actionmerchant_type_list()
	{		
		$this->pageTitle=t("Merchant Type");
		$action_name='merchant_type_list';
		$delete_link = Yii::app()->CreateUrl("attributes/merchant_type_delete");
		
		ScriptUtility::registerScript(array(
		  "var action_name='$action_name';",
		  "var delete_link='$delete_link';",
		),'action_name');
		
		if(Yii::app()->params['isMobile']==TRUE){
			$tpl = 'merchant_type_list_app';
		} else $tpl = 'merchant_type_list';
		
		$this->render( $tpl ,array(
			'link'=>Yii::app()->CreateUrl(Yii::app()->controller->id."/merchant_type_create")
		));
	}
	
	public function actionmerchant_type_create($update=false)
	{
		$this->pageTitle = $update==false? t("Add Merchant Type") : t("Update Merchant Type");
		CommonUtility::setMenuActive('.attributes',".attributes_merchant_type_list");
		
		$id='';
		$multi_language = CommonUtility::MultiLanguage();
		
		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_merchant_type::model()->findByPk( $id );				
			if(!$model){				
				$this->render("/admin/error",array(
				 'error'=>array(
				   'message'=>t(HELPER_RECORD_NOT_FOUND)
				 )
				));		
				Yii::app()->end();
			}	
			
		} else {			
			$model=new AR_merchant_type;							
		}
		
		$model->multi_language = $multi_language;				
		
		if(isset($_POST['AR_merchant_type'])){			
			$model->attributes=$_POST['AR_merchant_type'];				
			if($model->validate()){								
				if($model->save()){
					if(!$update){
					   $this->redirect(array(Yii::app()->controller->id.'/merchant_type_list'));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			} 
		} else {
			if($update){
				$commission_type = []; $commission_value = [];
				$commission_data = !empty($model->commission_data)?json_decode($model->commission_data,true):false;
				if(is_array($commission_data) && count($commission_data)>=1){					
					foreach ($commission_data as $key => $items) {
						$commission_type[$key] = $items['commission_type'];
						$commission_value[$key] = floatval($items['commission']);
					}
					$model->commission_type = $commission_type;
					$model->commission_value = $commission_value;
				}								
			}
		}
		
		$data  = array();
		if($update && !isset($_POST['AR_merchant_type'])){
			$translation = AttributesTools::GetFromTranslation($id,'{{merchant_type}}',
			'{{merchant_type_translation}}',
			'type_id',
			array('type_id','type_name'),
			array('type_name'=>'type_name_trans')
			);					
			$data['type_name_trans'] = isset($translation['type_name'])?$translation['type_name']:'';			
		}		
				
		$fields[]=array(
		  'name'=>'type_name_trans',
		  'placeholder'=>"Enter [lang] title here"
		);
						
		$this->render("merchant_type_create",array(
		    'model'=>$model,
		    'group'=>AttributesTools::statusGroup(),
		    'links'=>array(
	            t("All Type")=>array(Yii::app()->controller->id.'/merchant_type_list'),        
                $this->pageTitle,
		    ),	  		    
		    'commission_based'=>AttributesTools::commissionBased(),
		    'multi_language'=>$multi_language,
		    'language'=>AttributesTools::getLanguage(),
		    'fields'=>$fields,
		    'data'=>$data,
		    'status'=>(array)AttributesTools::StatusManagement('post'),
		    'commission_type_list'=>AttributesTools::CommissionType(),
			'service_list'=>CServices::Listing(Yii::app()->language)
		));
	}	
	
	public function actionmerchant_type_update()
	{
		$this->actionmerchant_type_create(true);
	}
	
	public function actionmerchant_type_delete()
	{
		$id = Yii::app()->input->get('id');							
		$model = AR_merchant_type::model()->findByPk( $id );				
		if($model){			
			$model->delete(); 			
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array(Yii::app()->controller->id.'/merchant_type_list'));
		} else $this->render("/admin/error",array(
				 'error'=>array(
				   'message'=>t(HELPER_RECORD_NOT_FOUND)
				 )
				));		
	}
	
	
	public function actionfeatured_loc()
	{		
		$this->pageTitle=t("Featured locations");
		$action_name='featured_loc_list';
		$delete_link = Yii::app()->CreateUrl("attributes/featured_loc_delete");
		
		ScriptUtility::registerScript(array(
		  "var action_name='$action_name';",
		  "var delete_link='$delete_link';",
		),'action_name');
		
		if(Yii::app()->params['isMobile']==TRUE){
			$tpl = 'featured_loc_list_app';
		} else $tpl = 'featured_loc_list';
		
		$this->render($tpl,array(
		  'link'=>Yii::app()->CreateUrl(Yii::app()->controller->id."/featured_loc_create")
		));	
	}
	
	public function actionfeatured_loc_create($update=false)
	{
		$this->pageTitle = $update==false? t("Add featured locations") : t("Update featured locations");
		CommonUtility::setMenuActive('.attributes',".attributes_featured_loc");
		
		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_featured_location::model()->findByPk( $id );				
			if(!$model){				
				$this->render("error");				
				Yii::app()->end();
			}	
			
		} else {
			$model=new AR_featured_location;				
		}
		
		if(isset($_POST['AR_featured_location'])){			
			$model->attributes=$_POST['AR_featured_location'];
			if($model->validate()){								
				if($model->save()){
					if(!$update){
					   $this->redirect(array('attributes/featured_loc'));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			}
		}
		
		$this->render("featured_loc_create",array(
		    'model'=>$model,	
		    'status'=>(array)AttributesTools::StatusManagement('post'),	    	
		    'featured_name'=>AttributesTools::MerchantFeatured(),
		    'links'=>array(
			    t("All featured locations")=>array('attributes/featured_loc'),        
			    $this->pageTitle,
			)
		));
	}
	
	public function actionfeatured_loc_update()
	{
		$this->actionfeatured_loc_create(true);
	}
	
	public function actionfeatured_loc_delete()
	{
		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_featured_location::model()->findByPk( $id );
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array('attributes/featured_loc'));			
		} else $this->render("error");
	}
			
	public function actionrejection_list()
	{
		$this->actionmeta_list(array(
		 'title'=>t("Rejection Reason List"),
		 'action_name'=>'rejection_list',
		 'delete_name'=>'rejection_reason_delete',
		 'list'=>'rejection_list',
		 'list_app'=>'rejection_list_app',
		 'create'=>'rejection_create'
		));
	}
	
	public function actionmeta_list($data = array())
	{
		$this->pageTitle = $data['title'];
		$action_name = $data['action_name'];
		$delete_link = Yii::app()->CreateUrl("attributes/".$data['delete_name']);
		
		ScriptUtility::registerScript(array(
		  "var action_name='$action_name';",
		  "var delete_link='$delete_link';",
		),'action_name');
		
		if(Yii::app()->params['isMobile']==TRUE){
			$tpl = $data['list_app'];
		} else $tpl = $data['list'];
		
		$this->render( $tpl ,array(
			'link'=>Yii::app()->CreateUrl(Yii::app()->controller->id."/".$data['create'])
		));
	}
	
	public function actionrejection_create($update=false)
	{
		CommonUtility::setMenuActive('.attributes',".attributes_rejection_list");
		$this->actionmeta_create($update,array(
		  'title'=>t("Add Rejection"),
		  'title_update'=>t("Update Rejection"),
		  'meta_name'=>'rejection_list',
		  'list'=>'rejection_list',
		  'create'=>'rejection_create'
		));
	}
	
	public function actionmeta_create($update=false,$parameters=array())
	{
		$this->pageTitle = $update==false? $parameters['title'] : $parameters['title_update'];		
		
		$multi_language = CommonUtility::MultiLanguage();
		$meta_name = $parameters['meta_name'];
		
		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
			  ':meta_name'=>$meta_name,
			  ':meta_id'=>intval($id)
			));
			if(!$model){				
				$this->render("/admin/error",array(
				 'error'=>array(
				   'message'=>t(HELPER_RECORD_NOT_FOUND)
				 )
				));		
				Yii::app()->end();
			}	
			
		} else {			
			$model=new AR_admin_meta;							
		}
		
		$model->scenario = "with_translation";
		
		if(isset($_POST['AR_admin_meta'])){
											
			if(DEMO_MODE){			
			    $this->render('//tpl/error',array(  
			          'error'=>array(
			            'message'=>t("Modification not available in demo")
			          )
			        ));	
			    return false;
			}
			
			$model->attributes=$_POST['AR_admin_meta'];
			$model->meta_name=$meta_name;
			if($model->validate()){						
				if($model->save()){
					if(!$update){
					   $this->redirect(array(Yii::app()->controller->id."/".$parameters['list']));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			} else Yii::app()->user->setFlash('error', CommonUtility::parseModelErrorToString( $model->getErrors() ));
		}
				
		$data  = array(); $fields = array();
		if($update && !isset($_POST['AR_admin_meta'])){
			$translation = AttributesTools::GetFromTranslation($id,'{{admin_meta}}',
			'{{admin_meta_translation}}',
			'meta_id',
			array('meta_id','meta_value'),
			array('meta_value'=>'meta_value_trans')
			);					
			$data['meta_value_trans'] = isset($translation['meta_value'])?$translation['meta_value']:'';			
		}		
				
		$fields[]=array(
		  'name'=>'meta_value_trans',
		  'placeholder'=>"Enter [lang] title here"
		);		
		
		$this->render($parameters['create'],array(
		    'model'=>$model,		    
		    'links'=>array(
	            isset($parameters['list_title'])?$parameters['list_title'] : t("All Type")=>array(Yii::app()->controller->id.'/'.$parameters['list']),        
                $this->pageTitle,
		    ),	  		    		
		    'multi_language'=>$multi_language,    
		    'language'=>AttributesTools::getLanguage(),
		    'fields'=>$fields,
		    'data'=>$data,
		    'status'=>(array)AttributesTools::StatusManagement('post'),
		    'update'=>$update
		));
	}
	
	public function actionrejection_update()
	{
		$this->actionrejection_create(true);
	}
	
	public function actionrejection_reason_delete()
	{
				
		if(DEMO_MODE){			
		    $this->render('//tpl/error',array(  
		          'error'=>array(
		            'message'=>t("Modification not available in demo")
		          )
		        ));	
		    return false;
		}

		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
		  ':meta_name'=>'rejection_list',
		  ':meta_id'=>intval($id)
		));
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array('attributes/rejection_list'));			
		} else $this->render("error");
	}
	
	public function actionpause_reason_list()
	{
		$this->actionmeta_list(array(
		 'title'=>t("Pause Reason List"),
		 'action_name'=>'pause_reason_list',
		 'delete_name'=>'pause_reason_delete',
		 'list'=>'pause_list',
		 'list_app'=>'pause_list_app',
		 'create'=>'pause_create'
		));
	}
	
	public function actionpause_create($update=false)
	{
		CommonUtility::setMenuActive('.attributes',".attributes_pause_reason_list");
		
		$this->actionmeta_create($update,array(
		  'title'=>t("Add Pause reason"),
		  'title_update'=>t("Update Pause reason"),
		  'meta_name'=>'pause_reason',
		  'list'=>'pause_reason_list',
		  'create'=>'rejection_create'
		));
	}
	
	public function actionpause_reason_update()
	{
		$this->actionpause_create(true);
	}
	
	public function actionpause_reason_delete()
	{
		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
		  ':meta_name'=>'pause_reason',
		  ':meta_id'=>intval($id)
		));
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array('attributes/pause_reason_list'));			
		} else $this->render("error");
	}
	
	public function actionactions_list()
	{
		$this->actionmeta_list(array(
		 'title'=>t("Status action List"),
		 'action_name'=>'action_list',
		 'delete_name'=>'action_delete',
		 'list'=>'action_list',
		 'list_app'=>'action_list_app',
		 'create'=>'action_create'
		));
	}
	
	public function actionaction_create($update=false)
	{
		CommonUtility::setMenuActive('.attributes',".attributes_actions_list");
		
		$this->actionmeta_create($update,array(
		  'title'=>t("Add action status"),
		  'title_update'=>t("Update action status"),
		  'meta_name'=>'action_type',
		  'list'=>'actions_list',
		  'create'=>'action_create'
		));
	}
	
	public function actionaction_update()
	{
		$this->actionaction_create(true);
	}
	
	public function actionaction_delete()
	{
				
		if(DEMO_MODE){			
		    $this->render('//tpl/error',array(  
		          'error'=>array(
		            'message'=>t("Modification not available in demo")
		          )
		        ));	
		    return false;
		}

		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
		  ':meta_name'=>'action_type',
		  ':meta_id'=>intval($id)
		));
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array('attributes/actions_list'));			
		} else $this->render("error");
	}
	
	public function actionzone_list()
	{
		$this->pageTitle=t("Zones list");
		
		$table_col = array(
		 'zone_id'=>array(
		    'label'=>t("ID"),
		    'width'=>'5%'
		  ),
		  'zone_name'=>array(
		    'label'=>t("Name"),
		    'width'=>'20%'
		  ),
		  'description'=>array(
		    'label'=>t("Description"),
		    'width'=>'20%'
		  ),
		  'zone_uuid'=>array(
		    'label'=>t("Actions"),
		    'width'=>'20%'
		  ),		  
		);
		$columns = array(
		  array('data'=>'zone_id'),
		  array('data'=>'zone_name'),
		  array('data'=>'description'),
		  array('data'=>null,'orderable'=>false,
		     'defaultContent'=>'
		     <div class="btn-group btn-group-actions" role="group">
			    <a class="ref_edit normal btn btn-light tool_tips"><i class="zmdi zmdi-border-color"></i></a>
			    <a class="ref_delete normal btn btn-light tool_tips"><i class="zmdi zmdi-delete"></i></a>
			 </div>
		     '
		  ),	  
		);				
				
		$this->render('//attributes/zone_list',array(
		  'table_col'=>$table_col,
		  'columns'=>$columns,
		  'order_col'=>0,
          'sortby'=>'desc',
		  'transaction_type'=>array(),		  
		  'link'=>Yii::app()->CreateUrl(Yii::app()->controller->id."/zone_create")
		));
	}
	
	public function actionzone_create($update=false)
	{
        $this->pageTitle = $update==false? t("Create Zones") : t("Update Zones");
		CommonUtility::setMenuActive('.attributes',".attributes_zone_list");
		
		$id = Yii::app()->input->get('id');			
		$model = new AR_zones;
		if($update){
			$model = AR_zones::model()->find("zone_uuid=:zone_uuid",array(
			 ':zone_uuid'=>$id
			));
			if(!$model){
				$this->render('//tpl/error',array(
				 'error'=>array(
				   'message'=>t(Helper_not_found)
				 )
				));
				return ;
			}
		}
		
		if(isset($_POST['AR_zones'])){

			if($update){
				if(DEMO_MODE){
					if($model->zone_id==1){
						$this->render('//tpl/error',array(  
							'error'=>array(
							  'message'=>t("Modification not available in demo")
							)
						  ));	
					    return false;
					}
				}
			}

			$model->attributes=$_POST['AR_zones'];			
			if($model->validate()){						
				if($model->save()){
					if(!$update){
					   $this->redirect(array(Yii::app()->controller->id."/zone_list"));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			} else Yii::app()->user->setFlash('error', CommonUtility::parseModelErrorToString( $model->getErrors() ));
		}
		
		$this->render('//attributes/zone_create',array(		  
		  'model'=>$model,
		  'links'=>array(
			    t("Zones list")=>array('attributes/zone_list'),        
			    $this->pageTitle,
			),
          'city_list'=>AttributesLocation::GetCityList()
		));
	}
	
	public function actionzone_update()
	{
		$this->actionzone_create(true);
	}
	
	public function actionzone_delete()
	{
		if(DEMO_MODE){			
			$this->render('//tpl/error',array(  
				  'error'=>array(
					'message'=>t("Modification not available in demo")
				  )
				));	
			return false;
		}
		
		$id =  Yii::app()->input->get('id');			
		$model = AR_zones::model()->find("zone_uuid=:zone_uuid",array(
		  ':zone_uuid'=>$id
		));
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array('attributes/zone_list'));			
		} else $this->render("error");
	}

	public function actionbooking_cancel_list()
	{
		$this->actionmeta_list(array(
		 'title'=>t("Booking Cancellation"),
		 'action_name'=>'booking_cancellation_list',
		 'delete_name'=>'booking_cancellation_delete',
		 'list'=>'pause_list',
		 'list_app'=>'pause_list_app',
		 'create'=>'booking_cancellation_create'
		));
	}

	public function actionbooking_cancellation_create($update=false)
	{
		$this->pageTitle = $update==false? t("Add booking cancellation") :  t("Update booking cancellation");
		CommonUtility::setMenuActive('.attributes',".attributes_booking_cancel_list");
		
		$this->actionmeta_create($update,array(
		  'title'=>t("Add booking cancellation reason"),
		  'title_update'=>t("Update booking cancellation reason"),
		  'meta_name'=>'reason_cancel_booking',
		  'list'=>'booking_cancel_list',
		  'create'=>'rejection_create',
		  'list_title'=>t("Booking Cancellation")
		));
	}		

	public function actionbooking_cancellation_update()
	{
		$this->actionbooking_cancellation_create(true);
	}
	
	public function actionbooking_cancellation_delete()
	{
		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
		  ':meta_name'=>'reason_cancel_booking',
		  ':meta_id'=>intval($id)
		));
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array('attributes/booking_cancel_list'));			
		} else $this->render("error");
	}

	public function actiontip_list()
	{
		$this->actionmeta_list(array(
		 'title'=>t("Tips List"),
		 'action_name'=>'tips_list',
		 'delete_name'=>'tips_delete',
		 'list'=>'tips_list',
		 'list_app'=>'pause_list_app',
		 'create'=>'tips_create'
		));
	}

	public function actiontips_create($update=false)
	{
		CommonUtility::setMenuActive('.attributes',".attributes_tip_list");
		
		$this->actionmeta_create($update,array(
		  'title'=>t("Add tips"),
		  'title_update'=>t("Update tips"),
		  'meta_name'=>'tips',
		  'list'=>'tip_list',
		  'create'=>'tips_create',
		  'list_title'=>t("Tips")
		));
	}

	public function actiontips_update()
	{
		$this->actiontips_create(true);
	}

	public function actiontips_delete()
	{
		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
		  ':meta_name'=>'tips',
		  ':meta_id'=>intval($id)
		));
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array('attributes/tip_list'));			
		} else $this->render("error");
	}

	public function actioncookie_preferences()
	{
		$this->actionmeta_list(array(
		 'title'=>t("Cookie Preferences"),
		 'action_name'=>'cookie_preferences_list',
		 'delete_name'=>'cookie_preferences_delete',
		 'list'=>'cookie_preferences_list',
		 'list_app'=>'cookie_preferences_list',
		 'create'=>'cookie_preferences_create'
		));
    }	

	public function actioncookie_preferences_update()
	{
		$this->actioncookie_preferences_create(true);
	}

	public function actioncookie_preferences_create($update=false)
	{
		$this->pageTitle = $update==false? t("Add Preferences") : t("Update Preferences");
		CommonUtility::setMenuActive('.attributes',".attributes_cookie_preferences");
		$meta_name = 'cookie_preferences';

		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
			  ':meta_name'=>$meta_name,
			  ':meta_id'=>intval($id)
			));
			if(!$model){				
				$this->render("/admin/error",array(
				 'error'=>array(
				   'message'=>t(HELPER_RECORD_NOT_FOUND)
				 )
				));		
				Yii::app()->end();
			}				
		} else $model=new AR_admin_meta;

		$model->scenario = "with_translation";
				
		if(isset($_POST['AR_admin_meta'])){
											
			if(DEMO_MODE){			
			    $this->render('//tpl/error',array(  
			          'error'=>array(
			            'message'=>t("Modification not available in demo")
			          )
			        ));	
			    return false;
			}
			
			$model->attributes=$_POST['AR_admin_meta'];
			$model->meta_name=$meta_name;
			if($model->validate()){						
				if($model->save()){
					if(!$update){
					   $this->redirect(array(Yii::app()->controller->id."/cookie_preferences"));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			} else Yii::app()->user->setFlash('error', CommonUtility::parseModelErrorToString( $model->getErrors() ));
		}
				
		$data  = array(); $fields = array();
		if($update && !isset($_POST['AR_admin_meta'])){
			$translation = AttributesTools::GetFromTranslation($id,'{{admin_meta}}',
			'{{admin_meta_translation}}',
			'meta_id',
			array('meta_id','meta_value','meta_value1'),
			array('meta_value'=>'meta_value_trans','meta_value1'=>'meta_value1_trans')
			);					
			$data['meta_value_trans'] = isset($translation['meta_value'])?$translation['meta_value']:'';			
			$data['meta_value1_trans'] = isset($translation['meta_value1'])?$translation['meta_value1']:'';			
		}		
				
		$fields[]=array(
		  'name'=>'meta_value_trans',
		  'placeholder'=>"Enter [lang] title here"
		);		
		$fields[]=array(
			'name'=>'meta_value1_trans',
			'placeholder'=>"Enter [lang] description here",
			'type'=>"textarea"
		);		
		
		$this->render('cookie_preferences_create',array(
		    'model'=>$model,		    
		    'links'=>array(
	            t("Cookie Preferences")=>array(Yii::app()->controller->id.'/cookie_preferences'),        
                $this->pageTitle,
		    ),	  		    		
		    'multi_language'=>true,    
		    'language'=>AttributesTools::getLanguage(),
		    'fields'=>$fields,
		    'data'=>$data,
		    'status'=>(array)AttributesTools::StatusManagement('post'),
		    'update'=>$update
		));
	}

	public function actioncookie_preferences_delete()
	{
		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
		  ':meta_name'=>'cookie_preferences',
		  ':meta_id'=>intval($id)
		));
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array(Yii::app()->controller->id.'/cookie_preferences'));			
		} else $this->render("error");
	}

	public function actionvehicle()
	{
		$this->actionmeta_list(array(
		 'title'=>t("Vehicle maker"),
		 'action_name'=>'vehicle_maker_list',
		 'delete_name'=>'vehicle_maker_delete',
		 'list'=>'vehicle_maker_list',
		 'list_app'=>'cookie_preferences_list',
		 'create'=>'vehicle_maker_create'
		));
    }	

	public function actionvehicle_maker_update()
	{
		$this->actionvehicle_maker_create(true);
	}

	public function actionvehicle_maker_create($update=false)
	{
		$this->pageTitle = $update==false? t("add vehicle") : t("update vehicle");
		CommonUtility::setMenuActive('.attributes',".attributes_vehicle");
		$meta_name = 'vehicle_maker';

		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
			  ':meta_name'=>$meta_name,
			  ':meta_id'=>intval($id)
			));			
			if(!$model){				
				$this->render("/admin/error",array(
				 'error'=>array(
				   'message'=>t(HELPER_RECORD_NOT_FOUND)
				 )
				));		
				Yii::app()->end();
			}				
		} else $model=new AR_admin_meta;

		$model->scenario = "with_translation";
				
		if(isset($_POST['AR_admin_meta'])){
											
			if(DEMO_MODE){			
			    $this->render('//tpl/error',array(  
			          'error'=>array(
			            'message'=>t("Modification not available in demo")
			          )
			        ));	
			    return false;
			}
			
			$model->attributes=$_POST['AR_admin_meta'];
			$model->meta_name=$meta_name;
			if($model->validate()){						
				if($model->save()){
					if(!$update){
					   $this->redirect(array(Yii::app()->controller->id."/vehicle"));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			} else Yii::app()->user->setFlash('error', CommonUtility::parseModelErrorToString( $model->getErrors() ));
		}
				
		$data  = array(); $fields = array();
		if($update && !isset($_POST['AR_admin_meta'])){
			$translation = AttributesTools::GetFromTranslation($id,'{{admin_meta}}',
			'{{admin_meta_translation}}',
			'meta_id',
			array('meta_id','meta_value','meta_value1'),
			array('meta_value'=>'meta_value_trans','meta_value1'=>'meta_value1_trans')
			);					
			$data['meta_value_trans'] = isset($translation['meta_value'])?$translation['meta_value']:'';						
		}		
				
		$fields[]=array(
		  'name'=>'meta_value_trans',
		  'placeholder'=>"Enter [lang] title here"
		);		
		
		$this->render('vehicle_maker_create',array(
		    'model'=>$model,		    
		    'links'=>array(
	            t("Vehicle list")=>array(Yii::app()->controller->id.'/vehicle'),        
                $this->pageTitle,
		    ),	  		    		
		    'multi_language'=>true,    
		    'language'=>AttributesTools::getLanguage(),
		    'fields'=>$fields,
		    'data'=>$data,
		    'status'=>(array)AttributesTools::StatusManagement('post'),
		    'update'=>$update
		));
	}	

	public function actionvehicle_maker_delete()
	{
		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
		  ':meta_name'=>'vehicle_maker',
		  ':meta_id'=>intval($id)
		));
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array(Yii::app()->controller->id.'/vehicle'));			
		} else $this->render("error");
	}

	public function actiondelivery_order_help()
	{
		$this->actionmeta_list(array(
		 'title'=>t("Delivery order help"),
		 'action_name'=>'delivery_order_help_list',
		 'delete_name'=>'delivery_order_help_delete',
		 'list'=>'delivery_order_help_list',
		 'list_app'=>'delivery_order_help_list',
		 'create'=>'delivery_order_help_create'
		));
    }	

	public function actiondelivery_order_help_update()
	{
		$this->actiondelivery_order_help_create(true);
	}

	public function actiondelivery_order_help_create($update=false)
	{
		$this->pageTitle = $update==false? t("add order help") : t("update order help");
		CommonUtility::setMenuActive('.attributes',".attributes_delivery_order_help");
		$meta_name = 'order_help';

		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
			  ':meta_name'=>$meta_name,
			  ':meta_id'=>intval($id)
			));			
			if(!$model){				
				$this->render("/admin/error",array(
				 'error'=>array(
				   'message'=>t(HELPER_RECORD_NOT_FOUND)
				 )
				));		
				Yii::app()->end();
			}				
		} else $model=new AR_admin_meta;

		$model->scenario = "with_translation";
				
		if(isset($_POST['AR_admin_meta'])){
											
			if(DEMO_MODE){			
			    $this->render('//tpl/error',array(  
			          'error'=>array(
			            'message'=>t("Modification not available in demo")
			          )
			        ));	
			    return false;
			}
			
			$model->attributes=$_POST['AR_admin_meta'];
			$model->meta_name=$meta_name;
			if($model->validate()){						
				if($model->save()){
					if(!$update){
					   $this->redirect(array(Yii::app()->controller->id."/delivery_order_help"));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			} else Yii::app()->user->setFlash('error', CommonUtility::parseModelErrorToString( $model->getErrors() ));
		}
				
		$data  = array(); $fields = array();
		if($update && !isset($_POST['AR_admin_meta'])){
			$translation = AttributesTools::GetFromTranslation($id,'{{admin_meta}}',
			'{{admin_meta_translation}}',
			'meta_id',
			array('meta_id','meta_value','meta_value1'),
			array('meta_value'=>'meta_value_trans','meta_value1'=>'meta_value1_trans')
			);					
			$data['meta_value_trans'] = isset($translation['meta_value'])?$translation['meta_value']:'';						
		}		
				
		$fields[]=array(
		  'name'=>'meta_value_trans',
		  'placeholder'=>"Enter [lang] title here"
		);		
		
		$this->render('vehicle_maker_create',array(
		    'model'=>$model,		    
		    'links'=>array(
	            t("Delivery order help")=>array(Yii::app()->controller->id.'/delivery_order_help'),        
                $this->pageTitle,
		    ),	  		    		
		    'multi_language'=>true,    
		    'language'=>AttributesTools::getLanguage(),
		    'fields'=>$fields,
		    'data'=>$data,
		    'status'=>(array)AttributesTools::StatusManagement('post'),
		    'update'=>$update
		));
	}		

	public function actiondelivery_order_help_delete()
	{
		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
		  ':meta_name'=>'order_help',
		  ':meta_id'=>intval($id)
		));
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array(Yii::app()->controller->id.'/delivery_order_help'));			
		} else $this->render("error");
	}

	public function actiondelivery_decline_reason()
	{
		$this->actionmeta_list(array(
		 'title'=>t("Delivery decline reason"),
		 'action_name'=>'delivery_decline_reason_list',
		 'delete_name'=>'delivery_decline_reason_delete',
		 'list'=>'vehicle_maker_list',
		 'list_app'=>'cookie_preferences_list',
		 'create'=>'delivery_decline_reason_create'
		));
    }	

	public function actiondelivery_decline_reason_update()
	{
		$this->actiondelivery_decline_reason_create(true);
	}

	public function actiondelivery_decline_reason_create($update=false)
	{
		$this->pageTitle = $update==false? t("add decline reason") : t("update decline reason");
		CommonUtility::setMenuActive('.attributes',".attributes_delivery_decline_reason");
		$meta_name = 'order_decline_reason';

		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
			  ':meta_name'=>$meta_name,
			  ':meta_id'=>intval($id)
			));			
			if(!$model){				
				$this->render("/admin/error",array(
				 'error'=>array(
				   'message'=>t(HELPER_RECORD_NOT_FOUND)
				 )
				));		
				Yii::app()->end();
			}				
		} else $model=new AR_admin_meta;

		$model->scenario = "with_translation";
				
		if(isset($_POST['AR_admin_meta'])){
											
			if(DEMO_MODE){			
			    $this->render('//tpl/error',array(  
			          'error'=>array(
			            'message'=>t("Modification not available in demo")
			          )
			        ));	
			    return false;
			}
			
			$model->attributes=$_POST['AR_admin_meta'];
			$model->meta_name=$meta_name;
			if($model->validate()){						
				if($model->save()){
					if(!$update){
					   $this->redirect(array(Yii::app()->controller->id."/delivery_decline_reason"));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			} else Yii::app()->user->setFlash('error', CommonUtility::parseModelErrorToString( $model->getErrors() ));
		}
				
		$data  = array(); $fields = array();
		if($update && !isset($_POST['AR_admin_meta'])){
			$translation = AttributesTools::GetFromTranslation($id,'{{admin_meta}}',
			'{{admin_meta_translation}}',
			'meta_id',
			array('meta_id','meta_value','meta_value1'),
			array('meta_value'=>'meta_value_trans','meta_value1'=>'meta_value1_trans')
			);					
			$data['meta_value_trans'] = isset($translation['meta_value'])?$translation['meta_value']:'';						
		}		
				
		$fields[]=array(
		  'name'=>'meta_value_trans',
		  'placeholder'=>"Enter [lang] title here"
		);		
		
		$this->render('vehicle_maker_create',array(
		    'model'=>$model,		    
		    'links'=>array(
	            t("Delivery decline reason")=>array(Yii::app()->controller->id.'/delivery_decline_reason'),        
                $this->pageTitle,
		    ),	  		    		
		    'multi_language'=>true,    
		    'language'=>AttributesTools::getLanguage(),
		    'fields'=>$fields,
		    'data'=>$data,
		    'status'=>(array)AttributesTools::StatusManagement('post'),
		    'update'=>$update
		));
	}	

	public function actiondelivery_decline_reason_delete()
	{
		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
		  ':meta_name'=>'order_decline_reason',
		  ':meta_id'=>intval($id)
		));
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array(Yii::app()->controller->id.'/delivery_decline_reason'));			
		} else $this->render("error");
	}

	public function actionlanguage_delete_words()
	{				
		$source_id = intval(Yii::app()->input->get('source_id'));
		$category = trim(Yii::app()->input->get('category'));
		$id = intval(Yii::app()->input->get('id'));		
		$model = AR_sourcemessage::model()->findByPk($source_id);
		if($model){			
			$model->delete(); 		
			$redirect = Yii::app()->createUrl("/attributes/language_translation",[
				'id'=>$id,
				'category'=>$category
			]);
			$this->redirect($redirect);
		} else $this->render("error");
	}

	public function actionallergens_list()
	{
		$this->actionmeta_list(array(
		 'title'=>t("Allergens List"),
		 'action_name'=>'allergens_list',
		 'delete_name'=>'allergens_delete',
		 'list'=>'vehicle_maker_list',
		 'list_app'=>'cookie_preferences_list',
		 'create'=>'allergens_create'
		));
    }		

	public function actionallergens_update()
	{
		$this->actionallergens_create(true);
	}

	public function actionallergens_create($update=false)
	{
		$this->pageTitle = $update==false? t("add Allergens") : t("update Allergens");
		CommonUtility::setMenuActive('.attributes',".attributes_allergens_list");
		$meta_name = 'allergens';

		if($update){
			$id = (integer) Yii::app()->input->get('id');	
			$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
			  ':meta_name'=>$meta_name,
			  ':meta_id'=>intval($id)
			));			
			if(!$model){				
				$this->render("/admin/error",array(
				 'error'=>array(
				   'message'=>t(HELPER_RECORD_NOT_FOUND)
				 )
				));		
				Yii::app()->end();
			}				
		} else $model=new AR_admin_meta;

		$model->scenario = "with_translation";
				
		if(isset($_POST['AR_admin_meta'])){
											
			if(DEMO_MODE){			
			    $this->render('//tpl/error',array(  
			          'error'=>array(
			            'message'=>t("Modification not available in demo")
			          )
			        ));	
			    return false;
			}
			
			$model->attributes=$_POST['AR_admin_meta'];
			$model->meta_name=$meta_name;
			if($model->validate()){						
				if($model->save()){
					if(!$update){
					   $this->redirect(array(Yii::app()->controller->id."/allergens_list"));		
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			} else Yii::app()->user->setFlash('error', CommonUtility::parseModelErrorToString( $model->getErrors() ));
		}
				
		$data  = array(); $fields = array();
		if($update && !isset($_POST['AR_admin_meta'])){
			$translation = AttributesTools::GetFromTranslation($id,'{{admin_meta}}',
			'{{admin_meta_translation}}',
			'meta_id',
			array('meta_id','meta_value','meta_value1'),
			array('meta_value'=>'meta_value_trans','meta_value1'=>'meta_value1_trans')
			);					
			$data['meta_value_trans'] = isset($translation['meta_value'])?$translation['meta_value']:'';						
		}		
				
		$fields[]=array(
		  'name'=>'meta_value_trans',
		  'placeholder'=>"Enter [lang] title here"
		);		
		
		$this->render('vehicle_maker_create',array(
		    'model'=>$model,		    
		    'links'=>array(
	            t("All Allergens")=>array(Yii::app()->controller->id.'/allergens_list'),        
                $this->pageTitle,
		    ),	  		    		
		    'multi_language'=>true,    
		    'language'=>AttributesTools::getLanguage(),
		    'fields'=>$fields,
		    'data'=>$data,
		    'status'=>(array)AttributesTools::StatusManagement('post'),
		    'update'=>$update
		));
	}		

	public function actionallergens_delete()
	{
		$id = (integer) Yii::app()->input->get('id');			
		$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
		  ':meta_name'=>'allergens',
		  ':meta_id'=>intval($id)
		));
		if($model){
			$model->delete(); 
			Yii::app()->user->setFlash('success', t("Succesful") );					
			$this->redirect(array(Yii::app()->controller->id.'/allergens_list'));			
		} else $this->render("error");
	}

	public function actionsort_services()
	{
		$this->pageTitle=t("Sort");
		CommonUtility::setMenuActive('.attributes',".attributes_services_list");				
		
		$model = new AR_services_sort();

		if(isset($_POST['id'])){
			$data = $_POST['id'];
			if(is_array($data) && count($data)>=1){				
				foreach ($data as $index=> $service_id) {						
					$model = AR_services_sort::model()->find("service_id=:service_id",[						
						':service_id'=>intval($service_id)
					]);
					if($model){						
						$model->sequence = $index;
						if(!$model->save()){							
						}
					}
				}				
				CCacheData::add();
				Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
				$this->refresh();
			}						
		}

		try {
			$category = CServices::List();
		} catch (Exception $e) {
			$category = [];
		}			

		$this->render('//tpl/sort',[
			'data'=>$category,
			'model'=>$model,
			'links'=>array(
	            t("All Services")=>array(Yii::app()->controller->id.'/services_list'),        
                $this->pageTitle,
		    ),	    	
		]);
	}		

	public function actionlanguage_updatewords()
	{
		$this->pageTitle = t("Update words");
		CommonUtility::setMenuActive('.attributes',".attributes_language_list");
		
		$this->render("language_updatewords",[
			'links'=>array(
	            t("All Language")=>array('attributes/language_list'), 
	            $this->pageTitle,
		    ),	    		    
		]);
	}

	public function actionupdatewords()
	{
		$allowed = ['front','backend','tableside','kitchen'];
		$category = Yii::app()->input->get('category');
		
		if(!in_array($category,$allowed)){
			die("Not allowed");
		}
		
		try {
			$params = [];
			$last_id = AR_sourcemessage::getlastID();
			require_once "language-$category.php";			
			if(is_array($words) && count($words)>=1){
				foreach ($words as $key => $items) {				
					$model = AR_sourcemessage::model()->find("category=:category AND BINARY message=:message",[
						':category'=>$category,
						':message'=>$items
					]);
					if(!$model){
						$params[] = [
							'id'=>intval($last_id)+$key,
							'category'=>trim($category),
							'message'=>trim($items)
						];
					}
				}
				if(is_array($params) && count($params)>=1){
					dump($params);
					$builder=Yii::app()->db->schema->commandBuilder;
					$command=$builder->createMultipleInsertCommand('{{sourcemessage}}',$params);
					$command->execute();			        
				} 
			}
		} catch (Exception $e) {
			dump($e->getMessage());
		}	

	}

	public function actioncall_staff_menu()
	{
		$this->actionmeta_list(array(
		 'title'=>t("Call Staff Menu"),
		 'action_name'=>'call_staff_menu_list',
		 'delete_name'=>'call_staff_menu_delete',
		 'list'=>'vehicle_maker_list',
		 'list_app'=>'cookie_preferences_list',
		 'create'=>'call_staff_menu_create'
		));
    }

	public function actioncall_staff_menu_update()
	{
		$this->actioncall_staff_menu_create(true);
	}

	public function actioncall_staff_menu_create($update=false)
	{
		$this->pageTitle = $update==false? t("add Call Staff Menu") : t("update Call Staff Menu");
		CommonUtility::setMenuActive('.attributes',".attributes_call_staff_menu");
		$meta_name = 'call_staff_menu';

		if($update){
			$id = (integer) Yii::app()->input->get('id');
			$model = AR_admin_meta::model()->find("meta_name=:meta_name AND meta_id=:meta_id",array(
			  ':meta_name'=>$meta_name,
			  ':meta_id'=>intval($id)
			));
			if(!$model){
				$this->render("/admin/error",array(
				 'error'=>array(
				   'message'=>t(HELPER_RECORD_NOT_FOUND)
				 )
				));
				Yii::app()->end();
			}
		} else $model=new AR_admin_meta;

		$model->scenario = "with_translation";

		if(isset($_POST['AR_admin_meta'])){

			if(DEMO_MODE){
			    $this->render('//tpl/error',array(
			          'error'=>array(
			            'message'=>t("Modification not available in demo")
			          )
			        ));
			    return false;
			}

			$model->attributes=$_POST['AR_admin_meta'];
			$model->meta_name=$meta_name;
			if($model->validate()){
				if($model->save()){
					if(!$update){
					   $this->redirect(array(Yii::app()->controller->id."/call_staff_menu"));
					} else {
						Yii::app()->user->setFlash('success',CommonUtility::t(Helper_update));
						$this->refresh();
					}
				} else Yii::app()->user->setFlash('error',t(Helper_failed_update));
			} else Yii::app()->user->setFlash('error', CommonUtility::parseModelErrorToString( $model->getErrors() ));
		}

		$data  = array(); $fields = array();
		if($update && !isset($_POST['AR_admin_meta'])){
			$translation = AttributesTools::GetFromTranslation($id,'{{admin_meta}}',
			'{{admin_meta_translation}}',
			'meta_id',
			array('meta_id','meta_value','meta_value1'),
			array('meta_value'=>'meta_value_trans','meta_value1'=>'meta_value1_trans')
			);
			$data['meta_value_trans'] = isset($translation['meta_value'])?$translation['meta_value']:'';
		}

		$fields[]=array(
		  'name'=>'meta_value_trans',
		  'placeholder'=>"Enter [lang] title here"
		);

		$this->render('vehicle_maker_create',array(
		    'model'=>$model,
		    'links'=>array(
	            t("All Call Staff Menu")=>array(Yii::app()->controller->id.'/call_staff_menu'),
                $this->pageTitle,
		    ),
		    'multi_language'=>true,
		    'language'=>AttributesTools::getLanguage(),
		    'fields'=>$fields,
		    'data'=>$data,
		    'status'=>(array)AttributesTools::StatusManagement('post'),
		    'update'=>$update
		));
	}

    public function actionsearch_city()
    {
        $res = array();
        try {
            $city_id = isset($_GET['city_id']) ? $_GET['city_id'] : 0;
            $res  = AttributesLocation::getLocationCity($city_id);
        } catch (Exception $e) {
            //echo $e->getMessage();
        }
        $result = array(
            'results'=>$res
        );

        header('Content-type: application/json');
        echo CJSON::encode($result);
        Yii::app()->end();
    }


}
/*end class*/