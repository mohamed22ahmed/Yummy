

<div class="container">
 
<div class="login-container m-auto pt-5 pb-5">

  <div class="text-center">
     <h5 class="m-1"><?php echo t("Thank you for signing up!")?></h5>
     <p class="m-0"><?php echo t("Your registration is now complete.");?></p>  
     
     <div class="mt-4"></div>
     <h6><?php echo t("Download our rider app")?>!</h6>
     <p>
        <?php echo t("you can easily download the app by visiting the Google Play Store or the App Store. Simply search for '{rider_app_name}' and click 'download' to get started. With our app.",[
            '{rider_app_name}'=>$rider_app_name
        ])?>
     </p>

     <a class="btn btn-link" href="<?php echo Yii::app()->createUrl("/deliveryboy/signup")?>">
        <?php echo t("Register again")?>
     </a>     
  </div>

  </div> <!--login container-->

</div> <!--containter-->  