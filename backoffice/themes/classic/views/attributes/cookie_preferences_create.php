<nav class="navbar navbar-light justify-content-between">
<?php
$this->widget('zii.widgets.CBreadcrumbs', 
array(
'links'=>$links,
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


<div class="form-label-group">    
   <?php echo $form->textField($model,'meta_value',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'meta_value')     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'meta_value',['label'=>t("Preferences")]); ?>
   <?php echo $form->error($model,'meta_value'); ?>
</div>

<div class="form-label-group mt-2">    
   <?php echo $form->textArea($model,'meta_value1',array(
     'class'=>"form-control form-control-text",     
     'placeholder'=>t("Description")
   )); ?>      
   <?php echo $form->error($model,'meta_value1'); ?>
</div>

  </div> <!--body-->
</div> <!--card-->

<!--TRANSLATION-->
<?php if(is_array($language) && count($language)>=1 ):?>
<?php 
$this->widget('application.components.WidgetTranslation',array(
  'form'=>$form,
  'model'=>$model,
  'language'=>$language,
  'field'=>$fields,
  'data'=>$data
));
?>   
<?php endif;?>
<!--END TRANSLATION-->


<?php echo CHtml::submitButton('submit',array(
'class'=>"btn btn-green btn-full mt-3",
'value'=>t("Save")
)); ?>

<?php $this->endWidget(); ?>