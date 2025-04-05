let paypal_handle;const componentsPaypal={props:["title","label","payment_code","merchant_id","client_id","amount","currency_code","ajax_url","cart_uuid","on_error"],data(){return{is_loading:false,error:[],order_uuid:"",success:"",enabled_force:false,force_currency:"",force_amount:0,jwt_data:[]}},mounted(){},updated(){},methods:{showPaymentForm(){this.error=[];$("#PaypalForm").modal("show")},close(){$("#PaypalForm").modal("hide")},closeRender(){paypal_handle.close();$("#PaypalRender").modal("hide");this.$emit("afterCancelPayment")},submitForms(){var e={YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),payment_code:this.payment_code,merchant_id:this.merchant_id};var a=1;e=JSON.stringify(e);ajax_request_cmp[a]=$.ajax({url:ajaxurl+"/SavedPaymentProvider",method:"PUT",dataType:"json",data:e,contentType:$content_type.json,timeout:$timeout_cmp,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];if(ajax_request_cmp[a]!=null){ajax_request_cmp[a].abort()}}});ajax_request_cmp[a].done(e=>{if(e.code==1){this.error=[];this.close();this.$emit("setPaymentlist")}else{this.error=e.msg}});ajax_request_cmp[a].always(e=>{this.is_loading=false})},PaymentRender(e){this.order_uuid=e.order_uuid;this.error=[];if(e.force_payment_data){if(e.force_payment_data.enabled_force){this.enabled_force=true;this.force_currency=e.force_payment_data.use_currency_code;this.force_amount=e.force_payment_data.total_exchange}}$("#PaypalRender").modal("show");this.close();this.initPaypal()},initPaypal(){if(window.paypal==null){let i=this.currency_code;if(this.enabled_force){i=this.force_currency}new Promise(e=>{const a=window.document;const t="paypal-script";const r=a.createElement("script");r.id=t;r.setAttribute("src","https://www.paypal.com/sdk/js?client-id="+this.client_id+"&currency="+i+"&disable-funding=credit,card");a.head.appendChild(r);r.onload=()=>{e()}}).then(()=>{this.renderPaypal()})}else{this.renderPaypal()}},renderPaypal(){let t=this.amount;if(this.enabled_force){t=this.force_amount}paypal_handle=paypal.Buttons({createOrder:(e,a)=>{return a.order.create({purchase_units:[{amount:{value:parseFloat(t)}}]})},onCancel:e=>{},onError:e=>{this.error[0]=this.on_error.error},onApprove:(e,a)=>{return a.order.capture().then(e=>{var a=e.purchase_units[0].payments.captures[0];this.CompletePaymentRequest(a.status,a.id,e.id)})}});paypal_handle.render("#paypal-button-container")},CompletePaymentRequest(e,a,t){var r=this.ajax_url;var i={YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),transaction_id:a,order_id:t,order_uuid:this.order_uuid,cart_uuid:this.cart_uuid};var o=1;i=JSON.stringify(i);ajax_request_cmp[o]=$.ajax({url:r+"/verifyPayment",method:"PUT",dataType:"json",data:i,contentType:$content_type.json,timeout:$timeout_cmp,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];if(ajax_request_cmp[o]!=null){ajax_request_cmp[o].abort()}}});ajax_request_cmp[o].done(e=>{if(e.code==1){this.success=e.msg;this.error=[];this.close();window.location.href=e.details.redirect}else{this.error=e.msg}});ajax_request_cmp[o].always(e=>{this.is_loading=false})},Dopayment(e,a){this.jwt_data=e;this.force_amount=a.amount;this.force_currency=a.currency_code;$("#PaypalRender").modal("show");this.PaypalInit()},PaypalInit(){if(window.paypal==null){let i=this.currency_code;new Promise(e=>{const a=window.document;const t="paypal-script";const r=a.createElement("script");r.id=t;r.setAttribute("src","https://www.paypal.com/sdk/js?client-id="+this.client_id+"&currency="+i+"&disable-funding=credit,card");a.head.appendChild(r);r.onload=()=>{e()}}).then(()=>{this.renderPayment()})}else{this.renderPayment()}},renderPayment(){this.$emit("closeTopup");let t=this.force_amount;paypal_handle=paypal.Buttons({createOrder:(e,a)=>{return a.order.create({purchase_units:[{amount:{value:parseFloat(t)}}]})},onCancel:e=>{},onError:e=>{this.error[0]=this.on_error.error},onApprove:(e,a)=>{return a.order.capture().then(e=>{var a=e.purchase_units[0].payments.captures[0];this.processPayment(a.status,a.id,e.id)})}});paypal_handle.render("#paypal-button-container")},processPayment(e,a,t){$("#PaypalRender").modal("hide");this.$emit("showLoader");var r={YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),data:this.jwt_data,transaction_id:a,order_id:t};axios({method:"PUT",url:this.ajax_url+"/processpayment",data:r,timeout:$timeout_cmp}).then(e=>{if(e.data.code==1){paypal_handle.close();this.$emit("afterSuccessfulpayment",e.data.details)}else{paypal_handle.close();this.$emit("afterCancelPayment",e.data.msg)}})["catch"](e=>{}).then(e=>{this.$emit("closeLoader")})}},template:`		        
	 <div class="modal" id="PaypalForm" tabindex="-1" role="dialog" aria-labelledby="PaypalForm" aria-hidden="true">
	   <div class="modal-dialog" role="document">
	     <div class="modal-content">
	       <div class="modal-body">
	       
	         <a href="javascript:;" @click="close" 
	          class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a> 
	        
	         <h4 class="m-0 mb-3 mt-3">{{title}}</h4>  	
	         
	         <p>{{label.notes}}</p>	 	       
	         
			 <DIV v-if="is_loading">
			  <div class="loading mt-5">      
			    <div class="m-auto circle-loader" data-loader="circle-side"></div>
			  </div>
			 </DIV>  
			
	          <div v-cloak v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
			    <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
			  </div>    
	         
	       </div> <!--modal body-->	  
	       
	       <div class="modal-footer justify-content-start">	        
		       <button class="btn btn-green w-100" @click="submitForms" :class="{ loading: is_loading }"   >
		          <span class="label">{{label.submit}}</span>
		          <div class="m-auto circle-loader" data-loader="circle-side"></div>
		      </button>		      
		   </div> <!--footer-->
	            
	  </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->      

	 <div class="modal" id="PaypalRender" tabindex="-1" role="dialog" aria-labelledby="PaypalRender" aria-hidden="true"
	 data-backdrop="static" data-keyboard="false" 
	 >
	   <div class="modal-dialog" role="document">
	     <div class="modal-content">
	       <div class="modal-body">
	       
	         <a href="javascript:;" @click="closeRender" 
	          class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a> 
	        
	         <h4 class="m-0 mb-3 mt-3">{{label.payment_title}}</h4>  	
	         
	         <p>{{label.payment_notes}}</p>	    						
	         
			 <DIV v-if="is_loading">
			  <div class="loading mt-5">      
			    <div class="m-auto circle-loader" data-loader="circle-side"></div>
			  </div>
			 </DIV>  
			
	          <div v-cloak v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
			    <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
			  </div>    
			  
			  <div  v-cloak v-if="success" class="alert alert-success" role="alert">
			    <p class="m-0">{{success}}</p>	    
			   </div>
	         
	       </div> <!--modal body-->	  
	       
	       <div v-if="!is_loading" class="modal-footer justify-content-start">	        
		       <div ref="paypal_button" id="paypal-button-container" style="width:100%;" ></div>
		   </div> <!--footer-->
	            
	  </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->   
	`};