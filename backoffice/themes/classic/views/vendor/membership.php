<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'frm-merchant',
	'enableAjaxValidation'=>false,
)); ?>

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
	
<h6 class="mb-4"><?php echo t("Type")?></h6>

<div class="form-label-group">    
   <?php echo $form->dropDownList($model,'merchant_type', (array)$merchant_type,array(
     'class'=>"form-control custom-select form-control-select merchant_type_selection",     
     'placeholder'=>$form->label($model,'merchant_type'),
   )); ?>         
   <?php echo $form->error($model,'merchant_type'); ?>
</div>

<DIV class="membership_type_1">
<h6 class="mb-4"><?php echo t("Plan")?></h6>

<div class="form-label-group">    
   <?php echo $form->dropDownList($model,'package_id', (array)$package,array(
     'class'=>"form-control custom-select form-control-select",     
     'placeholder'=>$form->label($model,'package_id'),
   )); ?>         
   <?php echo $form->error($model,'package_id'); ?>
</div>


<!--<div class="form-label-group">    
   <?php echo $form->textField($model,'membership_expired',array(
     'class'=>"form-control form-control-text datepick",
     'readonly'=>true,
     'placeholder'=>$form->label($model,'membership_expired'),          
   )); ?>   
   <?php    
    echo $form->labelEx($model,'membership_expired'); ?>
   <?php echo $form->error($model,'membership_expired'); ?>
</div>-->

</DIV> <!--membership_type_1-->

<DIV class="membership_type_2">

<h6 class="mb-0"><?php echo t("commission on orders")?></h6>
<div class="mb-4"><small><?php echo t("For membership type you can also set commission per order") ?>.</small></div>

<?php if(is_array($service_list) && count($service_list)>=1):?>  
  <?php foreach ($service_list as $key => $items):?>  
    <div class="row align-items-center">
      <div class="col-2">        
        <h6 class="m-0"><?php  echo $items['service_name']?></h6>
      </div>   
      <div class="col">
      
      <div class="form-label-group">    
        <?php echo $form->dropDownList($model,"commission_type[".$items['service_code']."]", (array) $commission_type_list,array(
          'class'=>"form-control custom-select form-control-select",
          'placeholder'=>$form->label($model,"commission_type[".$items['service_code']."]"),
        )); ?>         
        <?php echo $form->error($model,"commission_type[".$items['service_code']."]"); ?>
      </div>

      </div> 
      <div class="col">

      <div class="form-label-group">    
        <?php echo $form->textField($model,"commission_value[".$items['service_code']."]",array(
          'class'=>"form-control form-control-text",
          'placeholder'=>$form->label($model,"commission_value[".$items['service_code']."]"),     
        )); ?>           
        <?php echo $form->labelEx($model,"commission_value[".$items['service_code']."]",[
          'label'=>t("Commission")
        ]); ?>
        <?php echo $form->error($model,"commission_value[".$items['service_code']."]"); ?>
      </div> 

      </div>
    </div>
    <!-- row -->
  <?php endforeach;?>    
<?php endif;?>

<Div class="invoice_section_membership">
<div class="row">
  <div class="col-md-6">
  <h6 class="mb-2 mt-2"><?php echo t("Invoice terms")?></h6>
	<div class="form-label-group">    
	   <?php echo $form->dropDownList($model,'invoice_terms2', (array)$invoice_terms_type,array(
	     'class'=>"form-control custom-select form-control-select",     
	     'placeholder'=>$form->label($model,'invoice_terms2'),
	   )); ?>         
	   <?php echo $form->error($model,'invoice_terms2'); ?>
	</div>
  </div> <!--col-->
</div>
</DIV>

<Div class="invoice_section">
<div class="row">
  <div class="col-md-6">
    <div class="custom-control custom-switch custom-switch-md">  
      <?php echo $form->checkBox($model,"allowed_offline_payment",array(
        'class'=>"custom-control-input checkbox_child",     
        'value'=>1,
        'id'=>"allowed_offline_payment",
        'checked'=>$model->allowed_offline_payment==1?true:false
      )); ?>   
      <label class="custom-control-label" for="allowed_offline_payment">
      <?php echo t("Allow to accept offline payment")?>
      </label>
    </div>    
  </div>
</div>
<!-- row -->
<div class="row">
  <div class="col-md-6">
  <h6 class="mb-2 mt-2"><?php echo t("Invoice terms for offline payment")?></h6>
	<div class="form-label-group">    
	   <?php echo $form->dropDownList($model,'invoice_terms', (array)$invoice_terms_type,array(
	     'class'=>"form-control custom-select form-control-select",     
	     'placeholder'=>$form->label($model,'invoice_terms'),
	   )); ?>         
	   <?php echo $form->error($model,'invoice_terms'); ?>
	</div>
  </div> <!--col-->
</div>
<!-- row -->
</Div>

</DIV> <!--membership_type_2-->

<DIV class="membership_type_3">

</DIV> <!--membership_type_3-->

<div class="row text-left mt-4">
<div class="col-md-12 m-0">
<?php echo CHtml::submitButton('Login',array(
'class'=>"btn btn-green btn-full",
'value'=>CommonUtility::t("Save")
)); ?>
</div>
</div>

<?php $this->endWidget(); ?>
 