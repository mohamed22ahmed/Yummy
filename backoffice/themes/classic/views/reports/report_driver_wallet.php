<nav class="navbar navbar-light justify-content-between">
  <a class="navbar-brand">
  <h5><?php echo CHtml::encode($this->pageTitle)?></h5>
  </a>     
</nav>


<div id="vue-tables" class="card">
<div class="card-body">
  

 <div class="mt-3">

 <components-datatable
  ref="datatable"
  ajax_url="<?php echo Yii::app()->createUrl("/api")?>" 
  actions="driverreportwalletbalance"
  :table_col='<?php echo json_encode($table_col)?>'
  :columns='<?php echo json_encode($columns)?>'  
  :date_filter='<?php echo false;?>'  
  :transaction_type_list='<?php echo json_encode($transaction_type)?>'
  :settings="{
      auto_load : '<?php echo true;?>',
      filter : '<?php echo false;?>',   
      ordering :'<?php echo true;?>',  
      order_col :'<?php echo intval($order_col);?>',   
      load_filter :'<?php echo false;?>',  
      sortby :'<?php echo $sortby;?>',     
      placeholder : '<?php echo CJavaScript::quote(t("Start date -- End date"))?>',  
      separator : '<?php echo CJavaScript::quote(t("to"))?>',
      all_transaction : '<?php echo CJavaScript::quote(t("All Payment status"))?>'
    }"  
  page_limit = "<?php echo Yii::app()->params->list_limit?>"  
  >
  </components-datatable>
  
  
  </div>
  

</div> <!--card body-->
</div> <!--card-->
