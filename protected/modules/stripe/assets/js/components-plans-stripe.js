const componentsplansStripe={props:["title","label","ajax_url","payment_code","publish_key","merchant_uuid","package_uuid","return_url","trial_url"],data(){return{is_loading:false,error:[],stripe:undefined,elements:undefined,cardElement:undefined,cardholder_name:"",button_enabled:false,steps:1,customer_id:"",client_secret:""}},mounted(){},updated(){},methods:{showPaymentForm(){this.error=[];$(this.$refs.stripe_modal).modal("show");this.steps=1;this.createAccount()},close(){if(typeof this.cardElement!=="undefined"&&this.cardElement!==null){this.cardElement.unmount()}$(this.$refs.stripe_modal).modal("hide")},createAccount(){this.is_loading=true;axios({method:"PUT",url:this.ajax_url+"/createMerchantAccount",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_uuid:this.merchant_uuid,payment_code:this.payment_code},timeout:$timeout_cmp}).then(e=>{if(e.data.code==1){this.customer_id=e.data.details.customer_id;this.subscribeAccount()}else{this.customer_id="";this.error=e.data.msg}})["catch"](e=>{}).then(e=>{this.is_loading=false})},subscribeAccount(){dump("subscribeAccount");this.steps=2;this.is_loading=true;axios({method:"PUT",url:this.ajax_url+"/subscribeAccount",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_uuid:this.merchant_uuid,payment_code:this.payment_code,package_uuid:this.package_uuid,customer_id:this.customer_id},timeout:$timeout_cmp}).then(e=>{if(e.data.code==1){this.client_secret=e.data.details.client_secret;this.steps=3;this.initStripe()}else if(e.data.code==3){var t=e.data.details.subscriber_id;window.location.href=this.trial_url+"?merchant_uuid="+this.merchant_uuid+"&package_uuid="+this.package_uuid+"&payment_code="+this.payment_code+"&subscriber_id="+t}else{this.client_secret="";this.error=e.data.msg}})["catch"](e=>{}).then(e=>{this.is_loading=false})},initStripe(){if(window.Stripe==null){new Promise(e=>{const t=window.document;const i="stripe-script";const a=t.createElement("script");a.id=i;a.setAttribute("src","https://js.stripe.com/v3/");t.head.appendChild(a);a.onload=()=>{dump("added stripe");e()}}).then(()=>{this.renderCard()})}else{this.renderCard()}},renderCard(){this.is_loading=true;this.stripe=Stripe(this.publish_key);const e={clientSecret:this.client_secret};this.elements=this.stripe.elements(e);this.cardElement=this.elements.create("payment");setTimeout(()=>{this.cardElement.mount(this.$refs.card_element);this.button_enabled=true;this.is_loading=false},100)},submitForms(){dump("submitForms");this.is_loading=true;var e=this.elements;this.stripe.confirmPayment({elements:e,confirmParams:{return_url:this.return_url+"?merchant_uuid="+this.merchant_uuid+"&package_uuid="+this.package_uuid+"&payment_code="+this.payment_code}}).then(e=>{dump("ERROR");dump(e);if(e.error){this.is_loading=false;this.error[0]=e.error.message;setTimeout(function(){location.href="#error_message"},1)}})}},template:`	
	 <div class="modal" ref="stripe_modal" tabindex="-1" role="dialog" aria-labelledby="StripeForm" aria-hidden="true"
	 data-backdrop="static" data-keyboard="false" 
	 >
	   <div class="modal-dialog" role="document">
	     <div class="modal-content">
	     
	       <div class="modal-body">
	       
	       <a href="javascript:;" @click="close" 
	          class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a> 
	      	       
	       <DIV class="position-relative">
	       
	       <h4 class="m-0 mb-3 mt-3">{{title}}</h4>  		         
		   <p>{{label.notes}}</p>	 	  
		   	       
	       <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
		    <div>
		      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
		    </div>
		   </div>
      		
		   
		   <template v-if="steps===1"> 
		    <p>{{label.creating_account}}...</p>
		   </template>
		   
		   <template v-else-if="steps===2"> 
		    <p>{{label.subscribing}}...</p>		    
		   </template>
		   
		   <template v-else-if="steps===3">
		         
		       <!--
		       <div class="form-label-group">    
	              <input v-model="cardholder_name"  class="form-control form-control-text" placeholder=""
	               id="cardholder_name" type="text"  >   
	              <label for="cardholder_name" class="required">{{label.cardholder_name}}</label> 
	           </div>         
	           -->
		         
		       <div class="mb-4" ref="card_element" id="card-element"></div>   
		      		       
		       
	       </template>
	       
	       <div v-cloak v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
			    <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
			 </div>   
	       
	      </DIV>   
	      </div> <!--modal body-->	  
	       
	       <div class="modal-footer justify-content-start">	        
		       <button @click="submitForms" class="btn btn-green w-100" :disabled="!button_enabled" :class="{ loading: is_loading }"   >
		          <span class="label">{{label.submit}}</span>
		          <div class="m-auto circle-loader" data-loader="circle-side"></div>
		      </button>		      
		   </div> <!--footer-->
	     
	  </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->      	
	`};