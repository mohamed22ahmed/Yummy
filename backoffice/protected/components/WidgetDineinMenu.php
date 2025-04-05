<?php
Yii::import('zii.widgets.CMenu', true);

class WidgetDineinMenu extends CMenu
{
    public $merchant;
	 public function init()
	 {		 		 	  
	 	  	 	  	 	  
	 	  $menu = array();
	 	  $menu[]=array(
	 	    'label'=>'<i class="zmdi zmdi-settings"></i>'.t("Settings"),
	 	    'url'=>array("/services/settings_dinein", 'id'=>$this->merchant->merchant_id)
	 	  );	 	  	 	 	 	
	 	  
	 	  $menu[]=array(
	 	    'label'=>'<i class="zmdi zmdi-comments"></i>'.t("Instructions"),
	 	    'url'=>array("/services/dinein_instructions", 'id'=>$this->merchant->merchant_id),
	 	    'itemOptions'=>array(
	 	      'class'=>"instructions"
	 	    )
	 	  );

         if(isset($this->merchant->isChain) && $this->merchant->isChain === true){

             foreach ($this->merchant->children as $child){

                 $menu[]=array(
                     'label'=>'<i class="zmdi zmdi-pin-account"></i>'.t("Settings - ". $child->restaurant_name),
                     'url'=>array("/services/settings_dinein", 'id'=>$child->merchant_id)
                 );
             }

         }elseif ($this->merchant->parent){

             $menu[]=array(
                 'label'=>'<i class="zmdi zmdi-pin-account"></i>'.t("Settings - ". $this->merchant->parent->restaurant_name),
                 'url'=>array("/services/settings_dinein", 'id'=>$this->merchant->parent->merchant_id)
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