<nav class="navbar navbar-light justify-content-between">
<?php
$this->widget('zii.widgets.CBreadcrumbs', 
array(
'links'=>array(
    t("All City Discounts")=>array('promo/city_discounts'),
    $this->pageTitle,
),
'homeLink'=>false,
'separator'=>'<span class="separator">
<i class="zmdi zmdi-chevron-right"></i><i class="zmdi zmdi-chevron-right"></i></span>'
));
?>
</nav>

  
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



<h6 class="mb-4 mt-4"><?php echo t("Select City")?></h6>
<div class="row">
  <div class="col-md-6">
      <div class="form-label-group">
          <?php echo $form->dropDownList($model,'city_id', (array)$city_list,array(
              'class'=>"form-control custom-select form-control-select",
              'placeholder'=>$form->label($model,'city_id'),
          )); ?>
          <?php echo $form->error($model,'city_id'); ?>
      </div>
  </div>
</div> <!--row-->


<div class="row mt-4">
<div class="col-md-6">

<div class="form-label-group">    
   <?php echo $form->textField($model,'discount_percentage',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'discount_percentage')
   )); ?>   
   <?php    
    echo $form->labelEx($model,'discount_percentage'); ?>
   <?php echo $form->error($model,'discount_percentage'); ?>
</div>

</div>

</div>

<div class="form-label-group">    
   <?php echo $form->textField($model,'expiration',array(
     'class'=>"form-control form-control-text datepick",
     'readonly'=>true,
     'placeholder'=>$form->label($model,'expiration'),     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'expiration'); ?>
   <?php echo $form->error($model,'expiration'); ?>
</div>

<h6 class="mb-4 mt-4"><?php echo t("Status")?></h6>
<div class="form-label-group">    
   <?php echo $form->dropDownList($model,'status', (array)$status,array(
     'class'=>"form-control custom-select form-control-select",     
     'placeholder'=>$form->label($model,'status'),
   )); ?>         
   <?php echo $form->error($model,'status'); ?>
</div>		  

<?php echo CHtml::submitButton('submit',array(
'class'=>"btn btn-green btn-full mt-3",
'value'=>t("Save")
)); ?>

<?php $this->endWidget(); ?>