<nav class="navbar navbar-light justify-content-between">
  <a class="navbar-brand">
  <h5><?php echo CHtml::encode($this->pageTitle)?></h5>
  </a>     
</nav>

<div id="vue-tables" class="card">
<div class="card-body">
  

<div class="text-right">
  <a href="<?php echo $clear_logs_url;?>" class="btn btn-danger">
    <?php echo t("Delete past 30 days records")?>
  </a>
</div>

<components-datatable
ref="datatable"
ajax_url="<?php echo Yii::app()->createUrl("/api")?>" 
actions="pushlogs"
:table_col='<?php echo json_encode($table_col)?>'
:columns='<?php echo json_encode($columns)?>'
:date_filter='<?php echo true;?>'
:settings="{
    auto_load : '<?php echo true;?>',
    filter : '<?php echo true;?>',   
    ordering :'<?php echo true;?>',  
    order_col :'<?php echo intval($order_col);?>',   
    sortby :'<?php echo $sortby;?>',     
    placeholder : '<?php echo CJavaScript::quote(t("Start date -- End date"))?>',  
    separator : '<?php echo CJavaScript::quote(t("to"))?>',
    all_transaction : '<?php echo CJavaScript::quote(t("All transactions"))?>',
    delete_confirmation : '<?php echo CJavaScript::quote(t("Delete Confirmation"));?>',    
    delete_warning : '<?php echo CJavaScript::quote(t("Are you sure you want to permanently delete the selected item?"));?>',        
    cancel : '<?php echo CJavaScript::quote(t("Cancel"));?>',        
    delete : '<?php echo CJavaScript::quote(t("Delete"));?>',     
  }"  
page_limit = "<?php echo Yii::app()->params->list_limit?>"  
@view="view"
>
</components-datatable>


<components-view-data
ref="view_data"
ajax_url="<?php echo Yii::app()->createUrl("/api")?>" 
method="getPush"
:label="{    
    title : '<?php echo CJavaScript::quote(t("View Push"))?>',      
    close : '<?php echo CJavaScript::quote(t("Close"))?>',  
  }"  
/>
</components-view-data>

</div> <!--card body-->
</div> <!--card-->