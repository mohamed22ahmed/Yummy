<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'frm-merchant',
	'enableAjaxValidation'=>false,
)); ?>

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
   <?php echo $form->textField($model,'mt_android_download_url',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'mt_android_download_url'),
   )); ?>   
   <?php    
    echo $form->labelEx($model,'mt_android_download_url'); ?>
   <?php echo $form->error($model,'mt_android_download_url'); ?>
</div>

<div class="form-label-group">    
   <?php echo $form->textField($model,'mt_ios_download_url',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'mt_ios_download_url'),
   )); ?>   
   <?php    
    echo $form->labelEx($model,'mt_ios_download_url'); ?>
   <?php echo $form->error($model,'mt_ios_download_url'); ?>
</div>

<h6 class="mb-4"><?php echo t("Mobile Version")?></h6>

<div class="form-label-group">    
   <?php echo $form->textField($model,'mt_app_version_android',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'mt_app_version_android'),
   )); ?>   
   <?php    
    echo $form->labelEx($model,'mt_app_version_android'); ?>
   <?php echo $form->error($model,'mt_app_version_android'); ?>
   <div class="text-muted"><?php echo t("example 1.0")?></div>
</div>

<div class="form-label-group">    
   <?php echo $form->textField($model,'mt_app_version_ios',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'mt_app_version_ios'),
   )); ?>   
   <?php    
    echo $form->labelEx($model,'mt_app_version_ios'); ?>
   <?php echo $form->error($model,'mt_app_version_ios'); ?>
   <div class="text-muted"><?php echo t("example 1.0")?></div>
</div>

<div class="row text-left mt-4">
<div class="col-md-12 m-0">
<?php echo CHtml::submitButton('save',array(
'class'=>"btn btn-green btn-full",
'value'=>CommonUtility::t("Save")
)); ?>
</div>
</div>


</div>
</div>

<?php $this->endWidget(); ?>