<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'frm-merchant',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
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

<div class="row">
 <div class="col-md-6">
 
 <div class="form-label-group">    
   <?php echo $form->textField($model,'restaurant_name',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'restaurant_name'),     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'restaurant_name'); ?>
   <?php echo $form->error($model,'restaurant_name'); ?>
</div>

 </div>

    <div class="col-md-6">
        <div class="form-label-group">
            <?php echo $form->textField($model,'ar_restaurant_name',array(
                'class'=>"form-control form-control-text",
                'placeholder'=>$form->label($model,'ar_restaurant_name'),
            )); ?>
            <?php
            echo $form->labelEx($model,'ar_restaurant_name'); ?>
            <?php echo $form->error($model,'ar_restaurant_name'); ?>
        </div>
    </div>
</div> <!--row-->


<div class="row">
    <div class="col-md-6">
        <div class="form-label-group">
            <?php echo $form->textField($model,'restaurant_slug',array(
                'class'=>"form-control form-control-text",
                'placeholder'=>$form->label($model,'restaurant_slug'),
            )); ?>
            <?php
            echo $form->labelEx($model,'restaurant_slug'); ?>
            <?php echo $form->error($model,'restaurant_slug'); ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-label-group">
            <?php echo $form->textField($model,'contact_name',array(
                'class'=>"form-control form-control-text",
                'placeholder'=>$form->label($model,'contact_name'),
            )); ?>
            <?php
            echo $form->labelEx($model,'contact_name'); ?>
            <?php echo $form->error($model,'contact_name'); ?>
        </div>
    </div>
</div> <!--row-->


<div class="row">
 <div class="col-md-6">
 
 <div class="form-label-group">    
   <?php echo $form->textField($model,'contact_phone',array(
     'class'=>"form-control form-control-text mask_mobile",
     'placeholder'=>$form->label($model,'contact_phone'),     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'contact_phone'); ?>
   <?php echo $form->error($model,'contact_phone'); ?>
</div>
 
 </div>
 <div class="col-md-6">
 
 <div class="form-label-group">    
   <?php echo $form->textField($model,'contact_email',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'contact_email'),     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'contact_email'); ?>
   <?php echo $form->error($model,'contact_email'); ?>
</div>
 
 </div>
</div> <!--row-->


<div id="vue-uploader">
<component-uploader
ref="uploader"
max_file="<?php echo Yii::app()->params->dropzone['max_file'];?>"
max_file_size = "<?php echo Yii::app()->params->dropzone['max_file_size']?>"
select_type="single"
field = "photo"
field_path = "path"
inline="false"
selected_file="<?php echo $model->logo;?>"
upload_path="<?php echo $upload_path?>"
save_path="<?php echo $model->path?>"

@set-afer-upload="afterUpload"
@set-afer-delete="afterDelete"
:label="{
    select_file:'<?php echo CJavaScript::quote(t("Select File"))?>',       
    upload_new:'<?php echo CJavaScript::quote(t("Upload New"))?>',     
    upload_button:'<?php echo CJavaScript::quote(t("Logo"))?>',     
    browse:'<?php echo CJavaScript::quote(t("Browse"))?>',    
    add_file:'<?php echo CJavaScript::quote(t("Add Files"))?>',
    previous:'<?php echo CJavaScript::quote(t("Previous"))?>',
    next:'<?php echo CJavaScript::quote(t("Next"))?>',
    search:'<?php echo CJavaScript::quote(t("Search"))?>',    
    delete_file:'<?php echo CJavaScript::quote(t("Delete File"))?>',   
    drop_files:'<?php echo CJavaScript::quote(t("Drop files anywhere to upload"))?>',   
    or:'<?php echo CJavaScript::quote(t("or"))?>',   
    select_files:'<?php echo CJavaScript::quote(t("Select Files"))?>',   
    add_more:'<?php echo CJavaScript::quote(t("Add more"))?>',   
}"
>
</component-uploader>


<div class="pt-3">
<component-uploader
ref="uploader"
max_file="<?php echo Yii::app()->params->dropzone['max_file'];?>"
max_file_size = "<?php echo Yii::app()->params->dropzone['max_file_size']?>"
select_type="single"
field = "header_image"
field_path = "path2"
inline="false"
selected_file="<?php echo $model->header_image;?>"
upload_path="<?php echo $upload_path?>"
save_path="<?php echo $model->path2?>"

@set-afer-upload="afterUpload"
@set-afer-delete="afterDelete"
:label="{
    select_file:'<?php echo CJavaScript::quote(t("Select File"))?>',       
    upload_new:'<?php echo CJavaScript::quote(t("Upload New"))?>',     
    upload_button:'<?php echo CJavaScript::quote(t("Header"))?>',     
    browse:'<?php echo CJavaScript::quote(t("Browse"))?>',    
    add_file:'<?php echo CJavaScript::quote(t("Add Files"))?>',
    previous:'<?php echo CJavaScript::quote(t("Previous"))?>',
    next:'<?php echo CJavaScript::quote(t("Next"))?>',
    search:'<?php echo CJavaScript::quote(t("Search"))?>',    
    delete_file:'<?php echo CJavaScript::quote(t("Delete File"))?>',   
    drop_files:'<?php echo CJavaScript::quote(t("Drop files anywhere to upload"))?>',   
    or:'<?php echo CJavaScript::quote(t("or"))?>',   
    select_files:'<?php echo CJavaScript::quote(t("Select Files"))?>',   
    add_more:'<?php echo CJavaScript::quote(t("Add more"))?>',   
}"
>
</component-uploader>
</div>

</div> <!--vue-->

<h6 class="mb-4 mt-4"><?php echo t("Select Type") ?></h6>
<div class="form-label-group">
    <?php echo $form->dropDownList($model, 'type', array(
        'RESTAURANT' => 'Restaurant',
        'SUPERMARKET' => 'Super Market',
        'HOME_COOKING' => 'Home Cooking',
        'STORE' => 'Store',
        'CHAIN' => 'Restaurant Chain',
        'SUPERMARKET_CHAIN' => 'Super Market Chain',
        'STORE_CHAIN' => 'Store Chain',
        'CHILD' => 'Child',
    ), array(
        'class' => "form-control custom-select form-control-select",
        'placeholder' => $form->label($model, 'type'),
        'id' => 'merchant_type'
    )); ?>
    <?php echo $form->error($model, 'type'); ?>
</div>

<h6 class="mb-4 mt-4"><?php echo t("Select View Type") ?></h6>
<div class="form-label-group">
    <?php echo $form->dropDownList($model, 'view_type', array(
        'LIST' => 'List',
        'GRID' => 'Grid',
    ), array(
        'class' => "form-control custom-select form-control-select",
        'placeholder' => $form->label($model, 'view_type'),
    )); ?>
    <?php echo $form->error($model, 'view_type'); ?>
</div>

<div id="parent_chain_merchant">
<h6 disabled="true" class="mb-4 mt-4"><?php echo t("Select Parent Merchant")?></h6>
<div class="form-label-group">
    <?php echo $form->dropDownList($model,'parent_id', (array)$merchant_list,array(
        'class'=>"form-control custom-select form-control-select",
        'id' => 'choose_chain_merchant_id',
        'disabled' => 'disabled',
        'placeholder'=>$form->label($model,'parent_id'),
    )); ?>
    <?php echo $form->error($model,'parent_id'); ?>
</div>
</div>

<h6 class="mb-4 mt-4"><?php echo t("About")?></h6>
<div class="form-label-group mt-2">    
   <?php echo $form->textArea($model,'description',array(
     'class'=>"form-control form-control-text summernote",     
     'placeholder'=>t("Contact Content")
   )); ?>      
   <?php echo $form->error($model,'description'); ?>
</div>

<h6 class="mb-4 mt-4"><?php echo t("Short About")?></h6>
<div class="form-label-group mt-2">    
   <?php echo $form->textArea($model,'short_description',array(
     'class'=>"form-control form-control-text textarea_min",     
     'placeholder'=>t(""),     
   )); ?>      
   <?php echo $form->error($model,'short_description'); ?>
</div>

<!--TRANSLATION-->
<?php if(isset($language)):?>
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
<?php endif;?>
<!--END TRANSLATION-->	

<h6 class="mb-2 mt-4"><?php echo t("Cuisine")?></h6>
<div class="form-label-group">
   <?php echo $form->dropDownList($model,'cuisine2', (array)$cuisine,array(
     'class'=>"form-control custom-select form-control-select select_two",
     'multiple'=>true,
     'placeholder'=>$form->label($model,'cuisine2'),
   )); ?>         
   <?php echo $form->error($model,'cuisine2'); ?>
</div>

<h6 class="mb-2 mt-4"><?php echo t("Online Services")?></h6>
<div class="form-label-group">
   <?php echo $form->dropDownList($model,'service2', (array)$services,array(
     'class'=>"form-control custom-select form-control-select select_two",
     'placeholder'=>$form->label($model,'service2'),
     'multiple'=>true,
   )); ?>         
   <?php echo $form->error($model,'service2'); ?>
</div>

<h6 class="mb-2 mt-4"><?php echo t("POS Services")?> <span class="font11 text-grey">(<?php echo t("If empty will use online services instead")?>)</span></h6>
<div class="form-label-group">
   <?php echo $form->dropDownList($model,'service3', (array)$services,array(
     'class'=>"form-control custom-select form-control-select select_two",
     'placeholder'=>$form->label($model,'service3'),
     'multiple'=>true,
   )); ?>
   <?php echo $form->error($model,'service3'); ?>
</div>

<h6 class="mb-2 mt-4"><?php echo t("Tableside Services")?> <span class="font11 text-grey">(<?php echo t("If empty will use online services instead")?>)</span></h6>
<div class="form-label-group">
   <?php echo $form->dropDownList($model,'tableside_services', (array)$services,array(
     'class'=>"form-control custom-select form-control-select select_two",
     'placeholder'=>$form->label($model,'tableside_services'),
     'multiple'=>true,
   )); ?>
   <?php echo $form->error($model,'tableside_services'); ?>
</div>

<h6 class="mb-2 mt-4"><?php echo t("Tags")?></h6>
<div class="form-label-group">
   <?php echo $form->dropDownList($model,'tags',(array)$tags,array(
     'class'=>"form-control custom-select form-control-select select_two",
     'placeholder'=>$form->label($model,'tags'),
     'multiple'=>true,
   )); ?>         
   <?php echo $form->error($model,'tags'); ?>
</div>


<?php if(isset($is_admin)):?>
<?php if($is_admin):?>
<h6 class="mb-4"><?php echo t("Featured")?></h6>
<div class="form-label-group">    
   <?php echo $form->dropDownList($model,'featured',(array)$featured,array(
     'class'=>"form-control custom-select form-control-select select_two",
     'placeholder'=>$form->label($model,'featured'),
     'multiple'=>true,
   )); ?>         
   <?php echo $form->error($model,'featured'); ?>
</div>
<?php endif?>
<?php endif?>

<div class="row">
 <div class="col-md-6">
 
 <div class="form-label-group">    
   <?php echo $form->textField($model,'delivery_distance_covered',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'delivery_distance_covered'),     
   )); ?>   
   <?php    
    echo $form->labelEx($model,'delivery_distance_covered'); ?>
   <?php echo $form->error($model,'delivery_distance_covered'); ?>
</div>
 
 </div>
 <div class="col-md-6">
 
 <div class="form-label-group">    
   <?php echo $form->dropDownList($model,'distance_unit',(array)$unit,array(
     'class'=>"form-control custom-select form-control-select",
     'placeholder'=>$form->label($model,'distance_unit'),
   )); ?>         
   <?php echo $form->error($model,'distance_unit'); ?>
</div>
 
 </div>
</div> <!--row-->


<DIV class="row mt-2 mb-2">
  <div class="col-md-6">
  
  <div class="custom-control custom-switch custom-switch-md">  
  <?php echo $form->checkBox($model,"is_ready",array(
     'class'=>"custom-control-input checkbox_child",     
     'value'=>2,
     'id'=>"is_ready",
     'checked'=>$model->is_ready==2?true:false
   )); ?>   
  <label class="custom-control-label" for="is_ready">
   <?php echo t("Published Merchant")?>
  </label>
</div>    
  
  
  </div><!-- col-->
  
</DIV>
<!--row-->

<?php if($show_status):?>
<h6 class="mb-4"><?php echo t("Status")?></h6>

<div class="form-label-group">    
   <?php echo $form->dropDownList($model,'status', (array) $status,array(
     'class'=>"form-control custom-select form-control-select",
     'placeholder'=>$form->label($model,'status'),
   )); ?>         
   <?php echo $form->error($model,'status'); ?>
</div>
<?php endif;?>


<div class="row text-left mt-4">
<div class="col-md-12 m-0">
<?php echo CHtml::submitButton('Login',array(
'class'=>"btn btn-green btn-full",
'value'=>CommonUtility::t("Save")
)); ?>
</div>
</div>

<?php $this->endWidget(); ?>
 <?php $this->renderPartial("/admin/modal_delete_image");?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>

    $(document).ready(function () {

        var typeDropdown = $('#merchant_type');
        var merchant_parent_section = $('#parent_chain_merchant');

        function toggleSubTypeDropdown() {
            if (typeDropdown.val() === 'CHILD') {
                merchant_parent_section.show();
                $('#choose_chain_merchant_id').prop('disabled', false);

            } else {
                merchant_parent_section.hide();
                $('#choose_chain_merchant_id').prop('disabled', true);

            }
        }

        // Initial check
        toggleSubTypeDropdown();

        // Add event listener for change event
        typeDropdown.change(toggleSubTypeDropdown);

    });


</script>
