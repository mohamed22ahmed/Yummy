<nav class="navbar navbar-light justify-content-between">
  <a class="navbar-brand">
  <h5><?php echo CHtml::encode($this->pageTitle)?></h5>
  </a>     
</nav>

<div id="vue-tables" class="card">
<div class="card-body">

<div class="d-flex flex-row justify-content-end">
  <div class="p-2">  
  <a type="button" class="btn btn-black btn-circle" 
  href="<?php echo $link?>">
    <i class="zmdi zmdi-plus"></i>
  </a>  
  </div>
  <div class="p-2"><h5><?php echo t("Add new")?></h5></div>

  <?php if(!empty($sort_link)):?>
    <div class="p-2">  
	  <a type="button" class="btn btn-primary btn-circle" 
	  href="<?php echo $sort_link?>">
	    <i class="zmdi zmdi-sort-asc"></i>
	  </a>  
	  </div>
	  <div class="p-2"><h5 class="m-0"><?php echo t("Sort")?></h5></div>
    <?php endif;?>

    <?php if(isset($bulk_link)):?>
    <?php if(!empty($bulk_link)):?>
    <div class="p-2">
	  <a type="button" class="btn btn-green btn-circle"
	  href="<?php echo $bulk_link;?>">
	    <i class="zmdi zmdi-file-plus text-white"></i>
	  </a>
	  </div>
	  <div class="p-2"><h5 class="m-0"><?php echo t("Upload Bulk")?></h5></div>
    <?php endif?>
    <?php endif;?>

</div> <!--flex-->     

<components-datatable
ref="datatable"
ajax_url="<?php echo Yii::app()->createUrl("/apibackend")?>" 
actions="itemList"
:table_col='<?php echo json_encode($table_col)?>'
:columns='<?php echo json_encode($columns)?>'
:date_filter='<?php echo false;?>'
:filter="<?php echo false; ?>"
:settings="{
    auto_load : '<?php echo true;?>',
    filter_date_disabled : '<?php echo true;?>',   
    filter : '<?php echo true;?>',   
    ordering :'<?php echo true;?>',  
    order_col :'<?php echo intval($order_col);?>',   
    sortby :'<?php echo $sortby;?>',     
    placeholder : '<?php echo CJavaScript::quote(t("Start date -- End date"))?>',  
    separator : '<?php echo CJavaScript::quote(t("to"))?>',
    all_transaction : '<?php echo CJavaScript::quote(t("All transactions"))?>',
    load_filter : '<?php echo false;?>',
    delete_confirmation : '<?php echo CJavaScript::quote(t("Delete Confirmation"));?>',    
    delete_warning : '<?php echo CJavaScript::quote(t("Are you sure you want to permanently delete the selected item?"));?>',        
    cancel : '<?php echo CJavaScript::quote(t("Cancel"));?>',        
    delete : '<?php echo CJavaScript::quote(t("Delete"));?>',        
  }"  
page_limit = "<?php echo Yii::app()->params->list_limit?>"  
>
</components-datatable>
</div>
</div>
