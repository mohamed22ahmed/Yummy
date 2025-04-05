<nav class="navbar navbar-light justify-content-between">
<?php
$this->widget('zii.widgets.CBreadcrumbs', 
array(
'links'=>$links,
'homeLink'=>false,
'separator'=>'<span class="separator">
<i class="zmdi zmdi-chevron-right"></i><i class="zmdi zmdi-chevron-right"></i></span>'
));
?>
</nav>


<div id="app-schedule" class="card">
  <div class="card-body">

  
  <div class="d-flex flex-row justify-content-end">
    <div class="p-2">  
    <a @click="newSchedule" type="button" class="btn btn-black btn-circle" 
    href="javascript:;">
      <i class="zmdi zmdi-plus"></i>
    </a>  
    </div>
    <div class="p-2"><h5><?php echo t("Add new")?></h5></div>

    <div class="p-2">  
    <a href="<?php echo isset($schedule_bulk_url) ? $schedule_bulk_url : Yii::app()->createUrl("/driver/schedule_bulk")?>" type="button" class="btn btn-green btn-circle" 
    href="javascript:;">
      <i class="zmdi zmdi-file-plus text-white"></i>
    </a>  
    </div>
    <div class="p-2"><h5><?php echo t("Upload Bulk")?></h5></div>
  </div> <!--flex-->    

    <component-calendar
    ref="appcalendar"
    ajax_url="<?php echo isset($ajax_url)?$ajax_url:Yii::app()->createUrl("/api")?>" 
    start_value="01:00"
    end_value="23:59"
    time_interval="00:15"
    :merchant_id="<?php echo isset($merchant_id)?$merchant_id:0;?>"
    employment_type="employee"
    :label="{
      cancel : '<?php echo CJavaScript::quote(t("Cancel"));?>',        
      close : '<?php echo CJavaScript::quote(t("Close"));?>',        
      title : '<?php echo CJavaScript::quote(t("Schedule"));?>',        
      searching : '<?php echo CJavaScript::quote(t("Searching..."));?>',     
      no_results : '<?php echo CJavaScript::quote(t("No results"));?>',     
      save : '<?php echo CJavaScript::quote(t("Save"));?>',  
      update : '<?php echo CJavaScript::quote(t("Update"));?>',  
      error : '<?php echo CJavaScript::quote(t("Error"));?>', 
      success : '<?php echo CJavaScript::quote(t("Success"));?>', 
      delete : '<?php echo CJavaScript::quote(t("Delete"));?>', 
      ok : '<?php echo CJavaScript::quote(t("Ok"));?>', 
      delete_confirm : '<?php echo CJavaScript::quote(t("Delete confirmation"));?>', 
      delete_message : '<?php echo CJavaScript::quote(t("This permanently delete this schedule, continue?"));?>', 
    }"  
    >
    </component-calendar>     
  </div>
  <!-- body -->
</div>
<!-- card -->