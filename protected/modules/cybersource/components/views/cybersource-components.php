<components-cybersource
ref="<?php echo $payment_code?>"
title="<?php echo t("Add Cybersource")?>"
payment_code="<?php echo $payment_code?>"
merchant_id="<?php echo isset($credentials['attr3']) ? $credentials['attr3'] : ''; ?>"
merchant_type="<?php echo isset($credentials['merchant_type']) ? $credentials['merchant_type'] : 2;?>"
prefix="<?php echo $prefix?>"
reference="<?php echo $reference?>"

publish_key="<?php echo isset($credentials['attr2']) ? $credentials['attr2'] : ''; ?>"
access_key="<?php echo isset($credentials['attr1']) ? $credentials['attr1'] : ''; ?>"
:amount="amount_to_pay"
:cart_uuid="cart_uuid"
currency_code="<?php echo Price_Formatter::$number_format['currency_code'];?>"
ajax_url = "<?php echo Yii::app()->createAbsoluteUrl("$payment_code/cybersourceapi")?>"

@set-paymentlist="SavedPaymentList"	 	
@after-cancel-payment="AfterCancelPayment"	
@after-successfulpayment="afterSuccessfulpayment"	
@after-failedpayment="afterFailedpayment"	
@close-topup="closeTopup"
@alert="Alert"	
@show-loader="showLoadingBox"	
@close-loader="closeLoadingBox"

:label="{		    
submit: '<?php echo CJavaScript::quote(t("Add Cybersource"))?>',
notes : '<?php echo CJavaScript::quote(t("Add your Cybersource account"))?>',
payment_title : '<?php echo CJavaScript::quote(t("Pay using Cybersource"))?>',
payment_notes : '<?php echo CJavaScript::quote(t("You will re-direct to Cybersource account to login to your account."))?>',
}"  
:on_error="{		    
error: '<?php echo CJavaScript::quote(t("An error has occured"))?>',
}"  
>
</components-cybersource>