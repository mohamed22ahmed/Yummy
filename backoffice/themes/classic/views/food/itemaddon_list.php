<?php $this->renderPartial("/tpl/search-form",array(
 'link'=>isset($link)?$link:'',
 'sort_link'=>isset($sort_link)?$sort_link:'',
))?>

<?php echo CHtml::beginForm('','post',array(
  'id'=>"frm_datatables",
  'class'=>"frm_datatables",
  'onsubmit'=>"return false;"
));
echo CHtml::hiddenField('item_id',$model->item_id); 
?> 

<div class="table-responsive-md">
<table class="ktables_list table_datatables">
<thead>
<tr>
<th width="30%"><?php echo t("Addon Category")?></th>
<th width="30%"><?php echo t("Select Type")?></th>
<th width="30%"><?php echo t("Select Value")?></th>
<th width="30%"><?php echo t("Required")?></th>
<th width="15%"><?php echo t("Actions")?></th>
</tr>
</thead>
<tbody></tbody>
</table>
</div>

<?php echo CHtml::endForm(); ?>

<?php $this->renderPartial("/admin/modal_delete");?>