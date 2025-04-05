<nav class="navbar navbar-light justify-content-between">
<?php
$this->widget('zii.widgets.CBreadcrumbs', 
array(
'links'=>$params['links'],
'homeLink'=>false,
'separator'=>'<span class="separator">
<i class="zmdi zmdi-chevron-right"></i><i class="zmdi zmdi-chevron-right"></i></span>'
));
?>

<?php 
if(isset($custom_link)){
  echo $custom_link;
}
?>
</nav>

<div class="card">

  <div class="card-body">
    
  <div class="row">
    <div class="col-md-3">
        
      <div class="d-none d-md-block">
		  
	   <?php if(!empty($avatar)):?>
	    <div class="preview-image mb-2">
	     <div class="col-lg-7">
	      <img src="<?php echo $avatar?>" class="img-fluid mb-2 rounded-circle img-120">                
	     </div>     
	    </div>
		<?php endif;?>	    

     <?php if(isset($title)):?>
      <div class="text-left mb-3">
      <h6><?php echo $title;?></h6>
      </div>
     <?php endif;?>

	    <div class="attributes-menu-wrap">
		  <?php $this->widget('application.components.'.$widget, isset($params_widget)?(array)$params_widget:array() );?>
		</div>
      </div>

	  <div class="d-block d-md-none text-right">
	
	    <div class="dropdown btn-group dropleft">
		      <button class="btn btn-sm dropdown-togglex dropleft" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		       <i class="zmdi zmdi-more-vert"></i>
		     </button>
         <div class="dropdown-menu dropdown-menu-mobile" aria-labelledby="dropdownMenuButton">
           <?php $this->widget('application.components.'.$widget, isset($params_widget)?(array)$params_widget:array() );?>
         </div>         
       </div> 

      </div>
	  <!-- mobile menu -->
    	
    </div> <!--col-->
    <div class="col-md-9 p-md-0">
    
     <?php echo $this->renderPartial($template_name, $params); ?>  
    
    </div>
  </div> <!--row-->  
  
  </div> <!--card-body-->

</div> <!--card-->