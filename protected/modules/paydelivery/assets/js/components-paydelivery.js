const componentsPaydelivery={props:["title","label","payment_code","merchant_id","ajax_url","cart_uuid"],data(){return{is_loading:false,error:[],payment_id:0,data:[]}},created(){},computed:{hasData(){if(Object.keys(this.data).length>0){return true}return false}},methods:{showPaymentForm(){this.getPaydelivery();$(this.$refs.form).modal("show")},close(){$(this.$refs.form).modal("hide")},empty(e){if(typeof e==="undefined"||e==null||e==""||e=="null"||e=="undefined"){return true}return false},submitForms(){var a=1;var e="";e="YII_CSRF_TOKEN="+$("meta[name=YII_CSRF_TOKEN]").attr("content");e+="&payment_code="+this.payment_code;e+="&cart_uuid="+this.cart_uuid;e+="&merchant_id="+this.merchant_id;e+="&payment_id="+this.payment_id;ajax_request_cmp[a]=$.ajax({url:this.ajax_url+"/savedPaydelivery",method:"POST",dataType:"json",data:e,timeout:$timeout_cmp,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];if(ajax_request_cmp[a]!=null){ajax_request_cmp[a].abort()}}});ajax_request_cmp[a].done(e=>{dump(e);if(e.code==1){this.close();this.$emit("setPaymentlist")}else{this.error=e.msg}});ajax_request_cmp[a].always(e=>{this.is_loading=false})},getPaydelivery(){var a=2;var e="";e="YII_CSRF_TOKEN="+$("meta[name=YII_CSRF_TOKEN]").attr("content");e+="&merchant_id="+this.merchant_id;ajax_request_cmp[a]=$.ajax({url:this.ajax_url+"/getPaydelivery",method:"POST",dataType:"json",data:e,timeout:$timeout_cmp,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];if(ajax_request_cmp[a]!=null){ajax_request_cmp[a].abort()}}});ajax_request_cmp[a].done(e=>{if(e.code==1){this.data=e.details.data}else{this.data=[];this.error=e.msg}});ajax_request_cmp[a].always(e=>{this.is_loading=false})}},template:`	
	<div class="modal" ref="form" tabindex="-1" role="dialog" aria-labelledby="cardForm" aria-hidden="true">
	   <div class="modal-dialog" role="document">
	     <div class="modal-content">  <!--opened-->    
	       <div class="modal-body">
	       
	         <a href="javascript:;" @click="close" 
	          class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a> 
	        
	         <h4 class="m-0 mb-4 mt-3">{{title}}</h4>  		
	         
	         
	         <form class="forms mt-4 mb-2" @submit.prevent="submitForms" >
			 
	         			 			 
			 <template v-if="hasData">			 
				<el-radio-group v-model="payment_id">
					<template v-for="items in data">
					<el-radio :label="items.id">					   
					   <el-image style="width: 50px; height: 50px" :src="items.url_image" :fit="fit" />					   
					</el-radio>				
					</template>
				</el-radio-group>
			</template>			
                          
	         </form> <!--forms-->
	         	         
	         <div v-cloak v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
			    <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
			 </div>    
	         
	       </div> <!--modal body-->
	       	       
	        <div class="modal-footer justify-content-start">	        
		       <button class="btn btn-green w-100" @click="submitForms" :class="{ loading: is_loading }" :disabled="hasFillForm"  >
		          <span class="label">{{label.submit}}</span>
		          <div class="m-auto circle-loader" data-loader="circle-side"></div>
		      </button>		      
		   </div> <!--footer-->
	       
	  </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->    
	`};