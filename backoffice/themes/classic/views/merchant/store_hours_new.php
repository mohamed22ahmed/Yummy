<nav class="navbar navbar-light justify-content-between">
  <a class="navbar-brand">
  <h5><?php echo CHtml::encode($this->pageTitle)?></h5>
  </a>

  
  <?php if(isset($link)):?>
  <?php if(!empty($link)):?> 
    <div class="d-flex flex-row justify-content-end align-items-center">
	  <div class="p-2">  
	  <a type="button" class="btn btn-black btn-circle" 
	  href="<?php echo $link?>">
	    <i class="zmdi zmdi-edit"></i>
	  </a>  
	  </div>
	  <div class="p-2"><h5 class="m-0"><?php echo t("Edit")?></h5></div>
      </div> <!--flex-->     
  <?php endif;?>
 <?php endif;?>	 

</nav>


<p><?php echo t("These are the hours your store is available")?></p>
<table class="table">
    <?php foreach ($days as $day_code => $day_name):?>
    <?php if(isset($data[$day_code])):?>
        <?php $x=0?>
        <?php foreach ($data[$day_code] as $items):?>
        <tr>
            <td width="15%" class="text-capitalize">                
                <?php echo $x<=0?t($day_name):''?>
            </td>
            <td width="20%"><?php echo Date_Formatter::Time($items['start_time'])?> - <?php echo Date_Formatter::Time($items['end_time'])?></td>
            <td width="10%">
                <span class="badge p-1 store_hours_<?php echo $items['status']?>"><?php echo t($items['status'])?></span>
            </td>
            <td width="15%">
                <?php echo !empty($items['custom_text'])?$items['custom_text']:'&nbsp;'?>                
            </td>
        </tr>
        <?php $x++?>
        <?php endforeach;?>
    <?php else :?>
        <tr>
           <td width="15%" class="text-capitalize">                
                <?php echo t($day_name);?>
           </td>
           <td width="20%" class="text-muted"><?php echo t("No opening hours")?></td>
           <td width="10%"></td>
           <td width="15%"></td>
        </tr>
    <?php endif;?>    
    <?php endforeach;?>
</table>