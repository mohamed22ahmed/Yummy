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


<div class="card" id="vue-printhis">
 <div class="card-header" style="background-color:#fff;">
     <div class="row align-items-center">
        <div class="col">
            <h5 class="card-title p-0 m-0">Dining Tables</h5>
        </div>
        <div class="col text-right">           
           <a href="<?php echo $download_qrcode;?>" target="_blank" class="btn btn-secondary" style="margin-right: 10px;">
            <?php echo t("Download QR Code")?>
           </a>
           <button type="button" class="btn btn-primary"  @click="printDiv" :disabled="is_printing">              
             <i class="zmdi zmdi-print" style="font-size: 16px;"></i>
             <span class="pl-2">Print</span>
           </button>           
        </div>
     </div>
  </div>
  <div class="card-body text-center" >

     <h4 class="font-weight-bold">
       QR Code/NFC Tag Setup
     </h4>

     <p class="font14">
       Attach a QR code or NFC tag to each table.<br/>
       When setting up the iPad at the table,<br/>
       the staff member can scan the QR code or<br/>
       tap the NFC tag using the iPad's camera or<br/>
       NFC capabilities.
     </p>

     <div class="printhis">
        <?php CommonUtility::viewQrcode($qrcode)?>
     </div>     

  </div> <!--body-->
</div> <!--card-->
