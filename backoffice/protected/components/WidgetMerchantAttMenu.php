<?php
Yii::import('zii.widgets.CMenu', true);

class WidgetMerchantAttMenu extends CMenu
{		 	 
	 public $merchant_type,$main_account, $merchant;
	 
	 public function init()
	 {		 		 	 	 	
	 		 		 	 	 	
		$access = [];		  
		if($get_access = UserIdentityMerchant::getRoleAccess()){
			$access = $get_access;
		}

         if(isset($this->merchant->isChain) && $this->merchant->isChain === true){

             $items = array();
             foreach ($this->merchant->children as $child){

                 $items[]=array(
                     'label'=>'<i class="zmdi zmdi-pin-account"></i>'.t("Child - ". $child->restaurant_name),
                     'url'=>array("/merchant/edit", 'id'=>$child->merchant_id)
                 );
             }

             $menu[] = array(
                 'label' => '<i class="zmdi zmdi-file-text"></i>' . t("Children"),
                 'itemOptions' => array('class' => 'non-clickable', 'style' => 'margin-bottom: 20px;'),  // Add margin-bottom
                 'items' => $items,
             );

         }elseif ($this->merchant->parent){

             $items = array();
             $items[]=array(
                 'label'=>'<i class="zmdi zmdi-pin-account"></i>'.t("Parent - ". $this->merchant->parent->restaurant_name),
                 'url'=>array("/merchant/edit", 'id'=>$this->merchant->parent->merchant_id)
             );

             $menu[] = array(
                 'label' => '<i class="zmdi zmdi-file-text"></i>' . t("Parent Link"),
                 'itemOptions' => array('class' => 'non-clickable', 'style' => 'margin-bottom: 20px;'),  // Add margin-bottom
                 'items' => $items
             );

         }

	 	$menu[]=array(
	 	    'label'=>'<i class="zmdi zmdi-store"></i>'.t("Merchant information"),
	 	    'url'=>array("/merchant/edit", 'id'=>$this->merchant->merchant_id)
	 	  );

         if($this->merchant->isEntity === true){
             $menu[]=array(
                 'label'=>'<i class="zmdi zmdi-account-circle"></i>'.t("Login information"),
                 'url'=>array("/merchant/login"),
                 'visible'=>$this->main_account==1?true:false
             );

         }

         if (!$this->merchant->IsChain){
             $menu[]=array(
                 'label'=>'<i class="zmdi zmdi-pin"></i>'.t("Address"),
                 'url'=>array("/merchant/address", 'id'=>$this->merchant->merchant_id),
                 'visible'=>UserIdentityMerchant::CheckHasAccess($access,'merchant.address'),
             );
         }



         if($this->merchant_type==1):
             $menu[]=array(
                 'label'=>'<i class="zmdi zmdi-tv-list"></i>'.t("Payment history"),
                 'url'=>array("/merchant/payment_history", 'id'=>$this->merchant->merchant_id),
                 'visible'=>UserIdentityMerchant::CheckHasAccess($access,'merchant.payment_history'),
             );
         endif;


	 	  $this->items = $menu;
	 	  	 	  
	 	  $this->encodeLabel = false;
	 	  $this->activeCssClass = "active";
	 	  $this->activateParents = true;
	 	  $this->htmlOptions = array(
	 	    'class'=>'attributes-menu'
	 	  ); 
	 	  $this->submenuHtmlOptions = array(
	 	    'class'=>'attributes-sub-menu'
	 	  ); 
	 	  
	 	  parent::init();
	 }
	 
}
/*end class*/