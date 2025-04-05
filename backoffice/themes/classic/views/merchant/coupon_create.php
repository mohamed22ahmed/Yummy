<nav class="navbar navbar-light justify-content-between">
<?php
$this->widget('zii.widgets.CBreadcrumbs', 
array(
'links'=>isset($links)?$links:array(),
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
   <?php echo $form->textField($model,'voucher_name',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'voucher_name')     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'voucher_name'); ?>
   <?php echo $form->error($model,'voucher_name'); ?>
</div>

<h6 class="mb-4 mt-4"><?php echo t("Coupon Type")?></h6>
<div class="form-label-group">    
   <?php echo $form->dropDownList($model,'voucher_type', (array)$voucher_type,array(
     'class'=>"form-control custom-select form-control-select voucher_type",
     'placeholder'=>$form->label($model,'voucher_type'),
   )); ?>         
   <?php echo $form->error($model,'voucher_type'); ?>
</div>

<div class="row mt-4" id="switch_type_merchant">
  <div class="col-md-4 switch_type_amount">
      <div class="form-label-group">
          <?php echo $form->textField($model,'amount',array(
              'class'=>"form-control form-control-text",
              'placeholder'=>$form->label($model,'amount')
          )); ?>
          <?php
          echo $form->labelEx($model,'amount'); ?>
          <?php echo $form->error($model,'amount'); ?>
      </div>
  </div>

  <div class="col-md-4 switch_type_min_order">
      <div class="form-label-group">
          <?php echo $form->textField($model,'min_order',array(
              'class'=>"form-control form-control-text",
              'placeholder'=>$form->label($model,'min_order')
          )); ?>
          <?php echo $form->labelEx($model,'min_order'); ?>
          <?php echo $form->error($model,'min_order'); ?>
      </div>
  </div>

  <div class="col-md-4 switch_type_up_to">
      <div class="form-label-group">
          <?php echo $form->textField($model,'up_to',array(
              'class'=>"form-control form-control-text",
              'placeholder'=>$form->label($model,'up_to')
          )); ?>
          <?php
          echo $form->labelEx($model,'up_to'); ?>
          <?php echo $form->error($model,'up_to'); ?>
      </div>
  </div>
</div>

<h6 class="mb-4"><?php echo t("Days Available")?></h6>
<div class="form-label-group">    
   <?php echo $form->dropDownList($model,'days_available',$days,array(
     'class'=>"form-control custom-select form-control-select select_two",
     'placeholder'=>$form->label($model,'days_available'),
     'multiple'=>true,
   )); ?>         
   <?php echo $form->error($model,'days_available'); ?>
</div>

<div class="form-label-group mb-4">    
   <?php echo $form->textField($model,'expiration',array(
     'class'=>"form-control form-control-text datepick",
     'readonly'=>true,
     'placeholder'=>$form->label($model,'expiration'),     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'expiration'); ?>
   <?php echo $form->error($model,'expiration'); ?>
</div>

<h6 class="mb-3"><?php echo t("Transaction Type")?></h6>
<div class="form-label-group">    
   <?php echo $form->dropDownList($model,'transaction_type',$transaction_list,array(
     'class'=>"form-control custom-select form-control-select select_two",
     'placeholder'=>$form->label($model,'transaction_type'),
     'multiple'=>true,
   )); ?>         
   <?php echo $form->error($model,'transaction_type'); ?>
</div>

<h6 class="mb-3 mt-3"><?php echo t("Coupon Options")?></h6>
<div class="form-label-group">
   <?php echo $form->dropDownList($model,'used_once', (array)$coupon_options,array(
     'class'=>"form-control custom-select form-control-select coupon_options",
     'placeholder'=>$form->label($model,'used_once'),
   )); ?>
   <?php echo $form->error($model,'used_once'); ?>
</div>

<div class="mt-4" id="discount_delivery_selectbox">
    <div class="row">
        <div class="col-md-6 form-label-group">
            <?php echo $form->dropDownList($model,'discount_delivery', [
                1 => t("Unlimited for all users"),
                2 => t("Use Only Once"),
                3 => t("Once Per User")
            ],array(
                'class'=>"form-control custom-select form-control-select",
                'placeholder'=>$form->label($model,'discount_delivery'),
            )); ?>
            <?php echo $form->error($model,'discount_delivery'); ?>
        </div>

        <div class="col-md-6 form-label-group">
            <?php echo $form->dropDownList($model,'delivery_cost_payer', [
                1 => t("Yummy"),
                2 => t("Merchant"),
                3 => t("Shared"),
            ],array(
                'class'=>"form-control custom-select form-control-select delivery_cost_payer",
                'placeholder'=>$form->label($model,'delivery_cost_payer'),
            )); ?>
            <?php echo $form->error($model,'delivery_cost_payer'); ?>
        </div>
    </div>

    <div class="row" id="switchWhenSelectDeliveryCostPayer">
        <div class="col-md-6 form-label-group">
            <?php echo $form->dropDownList($model,'paying_way_merchant', [
                1 => t("Cash"),
                2 => t("Wallet"),
            ],array(
                'class'=>"form-control custom-select form-control-select",
                'placeholder'=>$form->label($model,'paying_way_merchant'),
            )); ?>
            <?php echo $form->error($model,'paying_way_merchant'); ?>
        </div>

        <div class="col-md-6" id="switchWhenSelectDeliveryCostPayerIsShared">
            <div class="row">
                <div class="col-md-6 form-label-group">
                    <?php echo $form->textField($model,'yummy_pay_percentage',array(
                        'class'=>"form-control form-control-text",
                        'placeholder'=>$form->label($model,'yummy_pay_percentage')
                    )); ?>
                    <?php echo $form->labelEx($model,'yummy_pay_percentage'); ?>
                    <?php echo $form->error($model,'yummy_pay_percentage'); ?>
                </div>

                <div class="col-md-6 form-label-group">
                    <?php echo $form->textField($model,'merchant_pay_percentage',array(
                        'class'=>"form-control form-control-text",
                        'placeholder'=>$form->label($model,'merchant_pay_percentage')
                    )); ?>
                    <?php echo $form->labelEx($model,'merchant_pay_percentage'); ?>
                    <?php echo $form->error($model,'merchant_pay_percentage'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<DIV class="coupon_max_number_use">
<div class="form-label-group">
   <?php echo $form->textField($model,'max_number_use',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'max_number_use')
   )); ?>
   <?php
    echo $form->labelEx($model,'max_number_use'); ?>
   <?php echo $form->error($model,'max_number_use'); ?>
</div>
</DIV>

<DIV class="coupon_customer">
<h6 class="mb-4"><?php echo t("Select Customer")?></h6>
<div class="form-label-group">    
   <?php echo $form->dropDownList($model,'apply_to_customer',(array)$selected_customer,array(
     'class'=>"form-control custom-select form-control-select select_two_ajax2",
     'placeholder'=>$form->label($model,'apply_to_customer'),
     'multiple'=>true,
     'action'=>'search_customer'
   )); ?>         
   <?php echo $form->error($model,'apply_to_customer'); ?>
</div>
</DIV>

<div class="custom-control custom-switch custom-switch-md">  
  <?php echo $form->checkBox($model,"visible",array(
     'class'=>"custom-control-input checkbox_child",     
     'value'=>1,
     'id'=>"visible",
     'checked'=>$model->visible==1?true:false
   )); ?>   
  <label class="custom-control-label" for="visible">
   <?php echo t("Visible")?>
  </label>
</div>


<?php
if (is_array($children)) {?>

  <h6 class="mb-4"><?php echo t("Locations")?></h6>
  <div class="form-label-group">
      <?php echo $form->dropDownList($model,'merchant_ids', (array)$children,array(
          'class'=>"form-control custom-select form-control-select select_two",
          'multiple'=>true,
          'placeholder'=>$form->label($model,'merchant_ids'),
      )); ?>
      <?php echo $form->error($model,'merchant_ids'); ?>
  </div>
  <?php
}
?>

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

<script>
  document.addEventListener('DOMContentLoaded', function () {
      // coupon options
      const dropdown = document.querySelector('.coupon_options');
      const discount_delivery_selectbox = document.getElementById('discount_delivery_selectbox');

      changeClassesWhenChangingCouponOptions(dropdown.value)
      dropdown.addEventListener('change', function () {
          changeClassesWhenChangingCouponOptions(dropdown.value)
      });


      // voucher or coupon type
      const voucher_type_merchant = document.querySelector('.voucher_type');
      const switch_type_merchant = document.getElementById('switch_type_merchant');
      const amount_merchant = switch_type_merchant.querySelector('.switch_type_amount');
      const min_order_merchant = switch_type_merchant.querySelector('.switch_type_min_order');
      const up_to_merchant = switch_type_merchant.querySelector('.switch_type_up_to');

      changeClassesWhenChangingCouponType(voucher_type_merchant.value)
      voucher_type_merchant.addEventListener('change', function () {
          changeClassesWhenChangingCouponType(voucher_type_merchant.value)
      });


      // select Delivery Cost Payer
      const delivery_cost_payer_merchant = document.querySelector('.delivery_cost_payer');
      const switchWhenSelectDeliveryCostPayer = document.getElementById('switchWhenSelectDeliveryCostPayer');
      const switchWhenSelectDeliveryCostPayerIsShared = document.getElementById('switchWhenSelectDeliveryCostPayerIsShared');

      changeClassesWhenChangingDeliveryCostPayer(delivery_cost_payer_merchant.value)
      delivery_cost_payer_merchant.addEventListener('change', function () {
          changeClassesWhenChangingDeliveryCostPayer(delivery_cost_payer_merchant.value)
      });


      // functions
      function changeClassesWhenChangingCouponOptions(option) {
          if (option == 4) {
              discount_delivery_selectbox.style.display = 'block';
          } else {
              discount_delivery_selectbox.style.display = 'none';
          }
      }

      function changeClassesWhenChangingCouponType(type) {
          if(type === "fixed amount") {
              amount_merchant.classList.remove('col-md-4');
              min_order_merchant.classList.remove('col-md-4');
              up_to_merchant.classList.remove('col-md-4');

              amount_merchant.classList.add('col-md-6');
              min_order_merchant.classList.add('col-md-6');
              up_to_merchant.style.display = 'none';
          } else {
              amount_merchant.classList.remove('col-md-6');
              min_order_merchant.classList.remove('col-md-6');

              amount_merchant.classList.add('col-md-4');
              min_order_merchant.classList.add('col-md-4');
              up_to_merchant.classList.add('col-md-4');
              up_to_merchant.style.display = 'block';
          }
      }

      function changeClassesWhenChangingDeliveryCostPayer(type) {
          if (type != 1) {
              switchWhenSelectDeliveryCostPayer.style.display = 'block';
              if(type == 3) {
                  switchWhenSelectDeliveryCostPayerIsShared.style.display = 'block';
              }else{
                  switchWhenSelectDeliveryCostPayerIsShared.style.display = 'none';
              }
          }else {
              switchWhenSelectDeliveryCostPayer.style.display = 'none';
          }
      }
  });
</script>
