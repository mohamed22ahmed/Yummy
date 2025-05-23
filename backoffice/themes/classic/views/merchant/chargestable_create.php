<?php
$form = $this->beginWidget(
	'CActiveForm',
	array(
		'id' => 'upload-form',
		'enableAjaxValidation' => false,		
	)
);
?>

<div class="card">
  <div class="card-body">
 
<?php if(Yii::app()->user->hasFlash('success')): ?>
	<div class="alert alert-success">
		<?php echo Yii::app()->user->getFlash('success'); ?>
	</div>
<?php endif;?>

<?php if(Yii::app()->user->hasFlash('error')): ?>
	<div class="alert alert-danger">
		<?php echo Yii::app()->user->getFlash('error'); ?>
	</div>
<?php endif;?>


<h6 class="mb-4"><?php echo t("Shipping Type")?></h6>
<div class="form-label-group">    
   <?php echo $form->dropDownList($model,'shipping_type', (array) $shipping,array(
     'class'=>"form-control custom-select form-control-select",
     'placeholder'=>$form->label($model,'shipping_type'),
   )); ?>         
   <?php echo $form->error($model,'shipping_type'); ?>
</div>

<div class="row">
  <div class="col-md-6">
  
  <div class="form-label-group">    
   <?php echo $form->textField($model,'distance_from',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'distance_from')     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'distance_from'); ?>
   <?php echo $form->error($model,'distance_from'); ?>
</div>
  
  </div> <!--col-->
  <div class="col-md-6">
  
  <div class="form-label-group">    
   <?php echo $form->textField($model,'distance_to',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'distance_to')     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'distance_to'); ?>
   <?php echo $form->error($model,'distance_to'); ?>
</div>
  
  </div>
</div> <!--col-->


<h6 class="mb-4"><?php echo t("Units")?></h6>
<div class="form-label-group">    
   <?php echo $form->dropDownList($model,'shipping_units', (array) $units,array(
     'class'=>"form-control custom-select form-control-select",
     'placeholder'=>$form->label($model,'shipping_units'),
   )); ?>         
   <?php echo $form->error($model,'shipping_units'); ?>
</div>

 <div class="form-label-group">    
   <?php echo $form->textField($model,'distance_price',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'distance_price')     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'distance_price'); ?>
   <?php echo $form->error($model,'distance_price'); ?>
</div>

<div class="form-label-group">    
   <?php echo $form->textField($model,'estimation',array(
     'class'=>"form-control form-control-text estimation",
     'placeholder'=>$form->label($model,'estimation')     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'estimation'); ?>
   <?php echo $form->error($model,'estimation'); ?>
   <small><?php echo t("in minutes example 10-20mins")?></small>
</div>

<div class="row">
  <div class="col-md-6">
  
  <div class="form-label-group">    
   <?php echo $form->textField($model,'minimum_order',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'minimum_order')     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'minimum_order'); ?>
   <?php echo $form->error($model,'minimum_order'); ?>
</div>
  
  </div> <!--col-->
  <div class="col-md-6">
  
  <div class="form-label-group">    
   <?php echo $form->textField($model,'maximum_order',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'maximum_order')     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'maximum_order'); ?>
   <?php echo $form->error($model,'maximum_order'); ?>
</div>
  
  </div>
</div> <!--col-->



<h6 class="mb-4 mt-4"><?php echo t("Set Specific Zone")?></h6>
<div class="form-label-group">
  <?php echo $form->dropDownList($model,'shipping_rate_zone_values',(array)$zones_options,array(
      'class'=>"selectpicker mr-sm-10",
      'multiple'=>true,
      'title'=>t("Filter by zone"),
      'data-selected-text-format'=>"count > 5"
  )); ?>
  <?php echo $form->error($model,'shipping_rate_zone_values'); ?>
</div>

<?php echo CHtml::submitButton('submit',array(
'class'=>"btn btn-green btn-full mt-3",
'value'=>t("Save")
)); ?>


  </div> <!--body-->
</div> <!--card-->


<?php $this->endWidget(); ?>