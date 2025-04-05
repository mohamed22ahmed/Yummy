<?php
Yii::import('zii.widgets.CMenu', true);

class WidgetDeliveryMenu extends CMenu
{
    public static $charge_type ;
    public $merchant;

	 public function init()
	 {		 		 	  
	 	  
	 	  $menu = array();
	 	  $menu[]=array(
	 	    'label'=>'<i class="zmdi zmdi-settings"></i>'.t("Settings"),
	 	    'url'=>array("/services/delivery_settings", 'id'=>$this->merchant->merchant_id)
	 	  );	 	  	 	 	 	
	 	  	 	  
		  $menu[]=array(
		   'label'=>'<i class="zmdi zmdi-time-countdown"></i>'.t("Fixed Charge"),
		   'url'=>array("/services/fixed_charge", 'id'=>$this->merchant->merchant_id),
		   'itemOptions'=>array(
			 'class'=>"fixed_charge"
		    )
		  );	 	  	 	 	 	
	 	  	 	  
		  $menu[]=array(
			'label'=>'<i class="zmdi zmdi-time-countdown"></i>'.t("Dynamic Rates"),
			'url'=>array("/services/charges_table", 'id'=>$this->merchant->merchant_id),
			'itemOptions'=>array(
				'class'=>"services_charges_table"
			)
		  );

         if(isset($this->merchant->isChain) && $this->merchant->isChain === true){

             foreach ($this->merchant->children as $child){

                 $menu[]=array(
                     'label'=>'<i class="zmdi zmdi-pin-account"></i>'.t("Settings - ". $child->restaurant_name),
                     'url'=>array("/services/delivery_settings", 'id'=>$child->merchant_id)
                 );
             }

         }elseif ($this->merchant->parent){

             $menu[]=array(
                 'label'=>'<i class="zmdi zmdi-pin-account"></i>'.t("Settings - ". $this->merchant->parent->restaurant_name),
                 'url'=>array("/services/delivery_settings", 'id'=>$this->merchant->parent->merchant_id)
             );

         }
	 	  	 	
	 	  $this->items = $menu;	 	  
	 	  	 	  
	 	  $this->encodeLabel = false;
	 	  $this->activeCssClass = "active";
	 	  $this->activateParents = true;
	 	  $this->htmlOptions = array(
	 	    'class'=>'services-menu'
	 	  ); 
	 	  $this->submenuHtmlOptions = array(
	 	    'class'=>'services-sub-menu'
	 	  ); 
	 	  
	 	  parent::init();
	 }
	 
}
/*end class*/