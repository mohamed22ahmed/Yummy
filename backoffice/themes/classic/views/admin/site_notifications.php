
  
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
  <?php echo $form->checkBox($model,"admin_enabled_alert",array(
     'class'=>"custom-control-input checkbox_child",     
     'value'=>1,
     'id'=>"admin_enabled_alert",
     'checked'=>$model->admin_enabled_alert==1?true:false
   )); ?>   
  <label class="custom-control-label" for="admin_enabled_alert">
   <?php echo t("Enabled Notification")?>
  </label>
</div>    

<small class="form-text text-muted mb-2">
  <?php echo t("Email and Mobile number who will receive notifications for new,cancel order and invoice.")?><br/>
  <?php echo t("Multiple email/mobile must be separated by comma.")?>
</small>   

<div class="form-label-group">    
   <?php echo $form->textField($model,'admin_email_alert',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'admin_email_alert')     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'admin_email_alert'); ?>
   <?php echo $form->error($model,'admin_email_alert'); ?>
</div>


<div class="form-label-group">    
   <?php echo $form->textField($model,'admin_mobile_alert',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'admin_mobile_alert')     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'admin_mobile_alert'); ?>
   <?php echo $form->error($model,'admin_mobile_alert'); ?>
</div>


<hr/>
<div class="custom-control custom-switch custom-switch-md">  
  <?php echo $form->checkBox($model,"admin_enabled_continues_alert",array(
     'class'=>"custom-control-input checkbox_child",     
     'value'=>1,
     'id'=>"admin_enabled_continues_alert",
     'checked'=>$model->admin_enabled_continues_alert==1?true:false
   )); ?>   
  <label class="custom-control-label" for="admin_enabled_continues_alert">
   <?php echo t("Enabled Continues alert for new order")?>
  </label>
</div>    

<small class="form-text text-muted mb-2">
  <?php echo t("We would like to bring to your attention that this service is not recommended for lower hosting plans")?>.<br/>
  <?php echo t("The reason being that it frequently sends requests to your server, which may lead to performance issues and strain on your hosting resources")?>.
  <br/><br/>
  <?php echo t("To ensure a smooth and reliable experience, we highly recommend opting for a hosting plan that can handle the increased server requests without compromising the overall performance of your website or application")?>.
</small>   


<div class="form-label-group mt-4">    
   <?php echo $form->textField($model,'admin_continues_alert_interval',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'admin_continues_alert_interval')     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'admin_continues_alert_interval'); ?>
   <?php echo $form->error($model,'admin_continues_alert_interval'); ?>
</div>
<small class="form-text text-muted mb-2">
  <?php echo t("minimum is 30 seconds")?>
</small>

  </div> <!--body-->
</div> <!--card-->



<?php echo CHtml::submitButton('submit',array(
'class'=>"btn btn-green btn-full mt-3",
'value'=>t("Save")
)); ?>

<?php $this->endWidget(); ?>