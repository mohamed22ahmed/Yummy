const componentsStripe={props:["title","label","payment_code","merchant_id","merchant_type","publish_key","amount","currency_code","ajax_url","cart_uuid","on_error","cardholder_name","prefix","reference"],data(){return{is_loading:false,is_ready:false,error:[],order_uuid:"",success:"",client_secret:"",stripe:undefined,cardElement:undefined,customer_id:"",button_enabled:true}},mounted(){},updated(){},methods:{showPaymentForm(){this.error=[];$(this.$refs.stripe_modal).modal("show");this.createCustomer()},close(){dump("close");this.cardElement.unmount();$(this.$refs.stripe_modal).modal("hide");this.is_ready=false},createCustomer(){var e=this.ajax_url;var t={YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_id:this.merchant_id,payment_code:this.payment_code,merchant_type:this.merchant_type,reference:this.reference};var a=1;t=JSON.stringify(t);ajax_request_cmp[a]=$.ajax({url:e+"/CreateCustomer"+this.prefix,method:"PUT",dataType:"json",data:t,contentType:$content_type.json,timeout:$timeout_cmp,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];if(ajax_request_cmp[a]!=null){ajax_request_cmp[a].abort()}}});ajax_request_cmp[a].done(e=>{if(e.code==1){this.is_ready=true;this.error=[];this.client_secret=e.details.client_secret;this.customer_id=e.details.customer_id;this.initStripe()}else{this.error=e.msg;this.button_enabled=false}});ajax_request_cmp[a].always(e=>{this.is_loading=false})},initStripe(){if(window.Stripe==null){new Promise(e=>{const t=window.document;const a="stripe-script";const r=t.createElement("script");r.id=a;r.setAttribute("src","https://js.stripe.com/v3/");t.head.appendChild(r);r.onload=()=>{dump("added stripe");e()}}).then(()=>{this.renderCard()})}else{this.renderCard()}},renderCard(){dump("renderCard");var e={base:{color:"#32325d",fontFamily:'"Helvetica Neue", Helvetica, sans-serif',fontSmoothing:"antialiased",fontSize:"16px","::placeholder":{color:"#aab7c4"}},invalid:{color:"#fa755a",iconColor:"#fa755a"}};this.stripe=Stripe(this.publish_key);var t=this.stripe.elements();this.cardElement=t.create("card",{style:e});setTimeout(()=>{this.cardElement.mount(this.$refs.card_element)},500)},submitForms(){this.is_loading=true;this.error=[];this.stripe.confirmCardSetup(this.client_secret,{payment_method:{card:this.cardElement,billing_details:{name:this.cardholder_name}}}).then(e=>{console.debug(e);if(e.error){this.is_loading=false;this.error[0]=e.error.message;dump(e.error.code);if(e.error.code=="setup_intent_unexpected_state"){this.createIntent()}}else{this.savePayment(e.setupIntent.payment_method)}})},savePayment(e){var t=this.ajax_url;var a={YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),payment_method_id:e,merchant_id:this.merchant_id,payment_code:this.payment_code,merchant_type:this.merchant_type,reference:this.reference};var r=1;a=JSON.stringify(a);ajax_request_cmp[r]=$.ajax({url:t+"/savePayment"+this.prefix,method:"PUT",dataType:"json",data:a,contentType:$content_type.json,timeout:$timeout_cmp,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];if(ajax_request_cmp[r]!=null){ajax_request_cmp[r].abort()}}});ajax_request_cmp[r].done(e=>{if(e.code==1){this.error=[];this.close();this.$emit("setPaymentlist")}else{this.error=e.msg}});ajax_request_cmp[r].always(e=>{this.is_loading=false})},createIntent(){var e=this.ajax_url;var t={YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),customer_id:this.customer_id,merchant_id:this.merchant_id,payment_code:this.payment_code,merchant_type:this.merchant_type};var a=2;t=JSON.stringify(t);ajax_request_cmp[a]=$.ajax({url:e+"/createIntent",method:"PUT",dataType:"json",data:t,contentType:$content_type.json,timeout:$timeout_cmp,crossDomain:true,beforeSend:e=>{if(ajax_request_cmp[a]!=null){ajax_request_cmp[a].abort()}}});ajax_request_cmp[a].done(e=>{if(e.code==1){this.client_secret=e.details.client_secret}})},PaymentRender(e){var t=this.ajax_url;var a={YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:this.cart_uuid,order_uuid:e.order_uuid,payment_uuid:e.payment_uuid,merchant_id:this.merchant_id,payment_code:this.payment_code,merchant_type:this.merchant_type};var r=2;a=JSON.stringify(a);ajax_request_cmp[r]=$.ajax({url:t+"/PaymentIntent",method:"PUT",dataType:"json",data:a,contentType:$content_type.json,timeout:$timeout_cmp,crossDomain:true,beforeSend:e=>{this.$emit("showLoader");if(ajax_request_cmp[r]!=null){ajax_request_cmp[r].abort()}}});ajax_request_cmp[r].done(e=>{if(e.code==1){window.location.href=e.details.redirect}else{this.$emit("afterCancelPayment");this.$emit("alert",e.msg)}});ajax_request_cmp[r].always(e=>{this.$emit("closeLoader")})},DoPaymentRender(e,t){this.$emit("showLoader");var a=e;a.YII_CSRF_TOKEN=$("meta[name=YII_CSRF_TOKEN]").attr("content");axios({method:"PUT",url:this.ajax_url+"/"+t,data:a,timeout:$timeout_cmp}).then(e=>{if(e.data.code==1){window.location.href=e.data.details.redirect}else{this.$emit("afterCancelPayment");this.$emit("alert",e.data.msg)}})["catch"](e=>{}).then(e=>{this.$emit("closeLoader")})},Dopayment(e){this.$emit("showLoader");var t={YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),data:e};axios({method:"PUT",url:this.ajax_url+"/processpayment",data:t,timeout:$timeout_cmp}).then(e=>{if(e.data.code==1){this.$emit("afterSuccessfulpayment",e.data.details)}else{this.$emit("afterCancelPayment",e.data.msg)}})["catch"](e=>{}).then(e=>{this.$emit("closeLoader")})}},template:`		        
	 <div class="modal" ref="stripe_modal" tabindex="-1" role="dialog" aria-labelledby="StripeForm" aria-hidden="true"
	 data-backdrop="static" data-keyboard="false" 
	 >
	   <div class="modal-dialog" role="document">
	     <div class="modal-content">
	     <form class="forms mt-2 mb-2" @submit.prevent="submitForms" >
	       <div class="modal-body">
	       
	         <a href="javascript:;" @click="close" 
	          class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a> 
	        
	         <h4 class="m-0 mb-3 mt-3">{{title}}</h4>  	
	         
	         <p>{{label.notes}}</p>	 	  
	         	         
	         <DIV  v-if="is_ready" class="position-relative"> 
	         
	         <DIV v-if="is_loading">
			  <div class="loading mt-5">      
			    <div class="m-auto circle-loader" data-loader="circle-side"></div>
			  </div>
			 </DIV>  
	         
	         <div class="form-label-group">    
              <input v-model="cardholder_name"  class="form-control form-control-text" placeholder=""
               id="cardholder_name" type="text"  >   
              <label for="cardholder_name" class="required">{{label.cardholder_name}}</label> 
             </div>        
	         	                    
             <div class="mb-4" ref="card_element" id="card-element"></div>
             
             <p>{{label.agreement}}</p>
             
             </DIV> <!-- is_ready -->
	        
			
	          <div v-cloak v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
			    <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
			  </div>    
	         
	       </div> <!--modal body-->	  
	       
	       <div class="modal-footer justify-content-start">	        
		       <button class="btn btn-green w-100" :disabled="!button_enabled" :class="{ loading: is_loading }"   >
		          <span class="label">{{label.submit}}</span>
		          <div class="m-auto circle-loader" data-loader="circle-side"></div>
		      </button>		      
		   </div> <!--footer-->
	     </form>               
	  </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->      	
	`};