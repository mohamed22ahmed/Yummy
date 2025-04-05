<nav class="navbar navbar-light justify-content-between">
<?php
$this->widget('zii.widgets.CBreadcrumbs', $links);
?>
</nav>

<div class="card">

  <div class="card-body">
  
  <?php if($model->isNewRecord):?>
    <?php echo $this->renderPartial('merchant_info', array(
      'model'=>$model,
      'status'=>$status,
	  'cuisine'=>$cuisine,
	  'tags'=>$tags,
	  'services'=>$services,
	  'unit'=>$unit,
	  'featured'=>$featured,
	  'upload_path'=>$upload_path,
	  'show_status'=>true,
      'merchant_list' => $merchant_list,
    )); ?>
  <?php else :?>
  <div class="row">
    <div class="col-md-3">
         
    <div class="attributes-menu-wrap">
	<?php $this->widget('application.components.WidgetMerchantAttMenu',array(
	 'id'=>$model->merchant_id,
     'merchant' => $model
	));?>
	</div>
    
    </div> <!--col-->
    <div class="col-md-9">
    
     <?php echo $this->renderPartial('merchant_info', array(
      'model'=>$model,
      'status'=>$status,
	  'cuisine'=>$cuisine,
	  'tags'=>$tags,
	  'services'=>$services,
	  'unit'=>$unit,
	  'featured'=>$featured,
	  'show_status'=>true,
      'merchant_list' => $merchant_list,
    )); ?>  
    
    </div>
  </div> <!--row-->
  <?php endif;?>
  
  </div> <!--card-body-->

</div> <!--card-->