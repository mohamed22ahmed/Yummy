<?php
$form = $this->beginWidget(
	'CActiveForm',
	array(
		'id' => 'forms',
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
      <?php echo $form->checkBox($model,"merchant_opt_contact_delivery",array(
         'class'=>"custom-control-input checkbox_child",
         'value'=>1,
         'id'=>"merchant_opt_contact_delivery",
         'checked'=>$model->merchant_opt_contact_delivery==1?true:false
       )); ?>
      <label class="custom-control-label" for="merchant_opt_contact_delivery">
       <?php echo t("Enabled Opt in for no contact delivery")?>
      </label>
    </div>

    <div class="form-group">
        <div class="custom-control custom-switch custom-switch-md mb-3 d-inline-block">
            <?php echo $form->checkBox($model, "free_delivery_on_first_order", array(
                'class' => "custom-control-input checkbox_child",
                'value' => 1,
                'id' => "free_delivery_on_first_order",
                'checked' => $model->free_delivery_on_first_order == 1 ? true : false
            )); ?>
            <label class="custom-control-label" for="free_delivery_on_first_order">
                <?php echo t("Free Delivery On First Order") ?>
            </label>
        </div>

        <div class="d-inline-block ml-3">
            <?php echo $form->textField($model, "first_order_free_delivery_up_to_amount", array(
                'class' => "form-control",
                'placeholder' => t("Enter Amount between 1 - 100"),
                'id' => "first_order_free_delivery_up_to_amount",
                'style' => 'width: 250px;'
            )); ?>
        </div>

        <div class="d-inline-block ml-3">
            <?php echo $form->textField($model, "first_order_free_delivery_minimum_cart_amount", array(
                'class' => "form-control",
                'placeholder' => t("Minimum amount in cart"),
                'id' => "first_order_free_delivery_minimum_cart_amount",
                'style' => 'width: 200px;'
            )); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="custom-control custom-switch custom-switch-md mb-3 d-inline-block">
            <?php echo $form->checkBox($model, "free_delivery_on_second_order", array(
                'class' => "custom-control-input checkbox_child",
                'value' => 1,
                'id' => "free_delivery_on_second_order",
                'checked' => $model->free_delivery_on_second_order == 1 ? true : false
            )); ?>
            <label class="custom-control-label" for="free_delivery_on_second_order">
                <?php echo t("Free Delivery On Second Order") ?>
            </label>
        </div>

        <div class="d-inline-block ml-3">
            <?php echo $form->textField($model, "second_order_free_delivery_up_to_amount", array(
                'class' => "form-control",
                'placeholder' => t("Enter Amount between 1 - 100"),
                'id' => "second_order_free_delivery_up_to_amount",
                'style' => 'width: 250px;'
            )); ?>
        </div>

        <div class="d-inline-block ml-3">
            <?php echo $form->textField($model, "second_order_free_delivery_minimum_cart_amount", array(
                'class' => "form-control",
                'placeholder' => t("Minimum amount in cart"),
                'id' => "second_order_free_delivery_minimum_cart_amount",
                'style' => 'width: 200px;'
            )); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="custom-control custom-switch custom-switch-md mb-3 d-inline-block">
            <?php echo $form->checkBox($model, "free_delivery_on_third_order", array(
                'class' => "custom-control-input checkbox_child",
                'value' => 1,
                'id' => "free_delivery_on_third_order",
                'checked' => $model->free_delivery_on_third_order == 1 ? true : false
            )); ?>
            <label class="custom-control-label" for="free_delivery_on_third_order">
                <?php echo t("Free Delivery On Third Order") ?>
            </label>
        </div>

        <div class="d-inline-block ml-3">
            <?php echo $form->textField($model, "third_order_free_delivery_up_to_amount", array(
                'class' => "form-control",
                'placeholder' => t("Enter Amount between 1 - 100"),
                'id' => "third_order_free_delivery_up_to_amount",
                'style' => 'width: 250px;'
            )); ?>
        </div>

        <div class="d-inline-block ml-3">
            <?php echo $form->textField($model, "third_order_free_delivery_minimum_cart_amount", array(
                'class' => "form-control",
                'placeholder' => t("Minimum amount in cart"),
                'id' => "third_order_free_delivery_minimum_cart_amount",
                'style' => 'width: 200px;'
            )); ?>
        </div>
    </div>

    <div class="custom-control custom-switch custom-switch-md mb-3">
                    <?php echo $form->checkBox($model, "merchant_free_delivery_forced", array(
                        'class' => "custom-control-input checkbox_child",
                        'value' => 1,
                        'id' => "merchant_free_delivery_forced",
                        'checked' => $model->merchant_free_delivery_forced == 1 ? true : false
                    )); ?>
                    <label class="custom-control-label" for="merchant_free_delivery_forced">
                        <?php echo t("Ignore System Discounts") ?>
                    </label>
                </div>

    <h6 class="mb-3"><?php echo t("Delivery Charge Type")?></h6>
    <div class="form-label-group">
       <?php echo $form->dropDownList($model,'merchant_delivery_charges_type', (array) $charge_type,array(
         'class'=>"form-control custom-select form-control-select merchant_delivery_charges_type",
         'placeholder'=>$form->label($model,'merchant_delivery_charges_type'),
       )); ?>
       <?php echo $form->error($model,'merchant_delivery_charges_type'); ?>
    </div>

    <?php if($merchant_type==1 || $merchant_type==3):?>

      <h6><?php echo t("Service and small order fee")?></h6>

      <div class="row">
        <div class="col">

         <div class="form-label-group">
          <?php echo $form->dropDownList($model,'merchant_charge_type', (array) $commission_charge_list,array(
            'class'=>"form-control custom-select form-control-select",
            'placeholder'=>$form->label($model,'merchant_charge_type'),
          )); ?>
          <?php echo $form->error($model,'merchant_charge_type'); ?>
          </div>

        </div>
        <div class="col">

          <div class="form-label-group">
            <?php echo $form->textField($model,'merchant_service_fee',array(
              'class'=>"form-control form-control-text",
              'placeholder'=>$form->label($model,'merchant_service_fee'),
            )); ?>
            <?php
              echo $form->labelEx($model,'merchant_service_fee'); ?>
            <?php echo $form->error($model,'merchant_service_fee'); ?>
          </div>

        </div>
      </div>
    <!-- row -->

    <div class="row">
      <div class="col">

      <div class="form-label-group">
        <?php echo $form->textField($model,'merchant_small_order_fee',array(
          'class'=>"form-control form-control-text",
          'placeholder'=>$form->label($model,'merchant_small_order_fee'),
        )); ?>
        <?php
          echo $form->labelEx($model,'merchant_small_order_fee'); ?>
        <?php echo $form->error($model,'merchant_small_order_fee'); ?>
      </div>

      </div>
      <div class="col">

      <div class="form-label-group">
        <?php echo $form->textField($model,'merchant_small_less_order_based',array(
          'class'=>"form-control form-control-text",
          'placeholder'=>$form->label($model,'merchant_small_less_order_based'),
        )); ?>
        <?php
          echo $form->labelEx($model,'merchant_small_less_order_based'); ?>
        <?php echo $form->error($model,'merchant_small_less_order_based'); ?>
      </div>

      </div>
    </div>
    <!-- row -->

    <?php endif;?>

    <?php echo CHtml::submitButton('submit',array(
'class'=>"btn btn-green btn-full mt-3",
'value'=>t("Save")
)); ?>
  </div>
</div>

<?php $this->endWidget(); ?>