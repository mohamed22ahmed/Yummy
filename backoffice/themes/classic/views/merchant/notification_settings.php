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

<div class="custom-control custom-switch custom-switch-md">  
  <?php echo $form->checkBox($model,"merchant_enabled_alert",array(
     'class'=>"custom-control-input checkbox_child",     
     'value'=>1,
     'id'=>"merchant_enabled_alert",
     'checked'=>$model->merchant_enabled_alert==1?true:false
   )); ?>   
  <label class="custom-control-label" for="merchant_enabled_alert">
   <?php echo t("Enabled Notification")?>
  </label>
</div>    

<small class="form-text text-muted mb-2">
  <?php echo t("Email and Mobile number who will receive notifications like new order and cancel order.")?><br/>
  <?php echo t("Multiple email/mobile must be separated by comma.")?>
</small>   

<div class="form-label-group">    
   <?php echo $form->textField($model,'merchant_email_alert',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'merchant_email_alert')     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'merchant_email_alert'); ?>
   <?php echo $form->error($model,'merchant_email_alert'); ?>
</div>


<div class="form-label-group">    
   <?php echo $form->textField($model,'merchant_mobile_alert',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'merchant_mobile_alert')     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'merchant_mobile_alert'); ?>
   <?php echo $form->error($model,'merchant_mobile_alert'); ?>
</div>

<hr/>

<div class="custom-control custom-switch custom-switch-md">  
  <?php echo $form->checkBox($model,"merchant_enabled_continues_alert",array(
     'class'=>"custom-control-input checkbox_child",     
     'value'=>1,
     'id'=>"merchant_enabled_continues_alert",
     'checked'=>$model->merchant_enabled_continues_alert==1?true:false
   )); ?>   
  <label class="custom-control-label" for="merchant_enabled_continues_alert">
   <?php echo t("Enabled Continues alert for new order")?>
  </label>
</div>    

<div class="custom-control custom-switch custom-switch-md">
  <?php echo $form->checkBox($model,"merchant_enabled_tableside_alert",array(
     'class'=>"custom-control-input checkbox_child",
     'value'=>1,
     'id'=>"merchant_enabled_tableside_alert",
     'checked'=>$model->merchant_enabled_tableside_alert==1?true:false
   )); ?>
  <label class="custom-control-label" for="merchant_enabled_tableside_alert">
   <?php echo t("Enabled Continues alert for tableside ordering")?>
  </label>
</div>

  </div> <!--body-->
</div> <!--card-->

<?php echo CHtml::submitButton('submit',array(
'class'=>"btn btn-green btn-full mt-3",
'value'=>t("Save")
)); ?>

<?php $this->endWidget(); ?>  