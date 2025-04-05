(function(u){"use strict";jQuery(document).ready(function(){var t=function(t){console.debug(t)};var a=function(t){if(typeof t==="undefined"||t==null||t==""||t=="null"||t=="undefined"){return true}return false};const i=2e4;const s=function(){if(typeof identity_token==="undefined"||identity_token==null||identity_token==""||identity_token=="null"||identity_token=="undefined"){return""}return identity_token};const e={props:["ajax_url","label","mobile_number","mobile_prefix"],emits:["update:mobile_number","update:mobile_prefix"],data(){return{data:[],country_flag:""}},mounted(){this.getLocationCountries()},methods:{getLocationCountries(){axios({method:"POST",url:this.ajax_url+"/getLocationCountries",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:i}).then(t=>{if(t.data.code==1){this.data=t.data.details.data;this.country_flag=t.data.details.default_data.flag;this.$emit("update:mobile_prefix",t.data.details.default_data.phonecode)}else{this.data=[];this.country_flag="";this.mobile_prefix=""}})["catch"](t=>{}).then(t=>{})},setValue(t){this.country_flag=t.flag;this.$emit("update:mobile_prefix",t.phonecode);this.$refs.ref_mobile_number.focus()}},template:`				    
    <div class="inputs-with-dropdown d-flex align-items-center mb-3" >
	    <div class="dropdown">
		  <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		    <img v-if="country_flag" :src="country_flag">
		  </button>
		  <div class="dropdown-menu" >		    
		    <a v-for="item in data" @click="setValue(item)"
		    href="javascript:;"  class="dropdown-item d-flex align-items-center">
		      <div class="mr-2">
		        <img :src="item.flag">
		      </div>
		      <div>{{item.country_name}}</div>
		    </a>		    
		  </div>
		</div> <!--dropdown-->
		
		<div class="mr-0 ml-1" v-if="mobile_prefix">+{{mobile_prefix}}</div>
		<input type="text"    ref="ref_mobile_number"
		:value="mobile_number" @input="$emit('update:mobile_number', $event.target.value)" >
		
	</div> <!--inputs-->
	`};const l={props:["sitekey","size","theme","is_enabled"],data(){return{recaptcha:null}},mounted(){if(this.is_enabled==1||this.is_enabled=="true"||this.is_enabled==true){this.initCapcha()}},methods:{initCapcha(){if(window.grecaptcha==null){new Promise(t=>{window.recaptchaReady=function(){t()};const e=window.document;const a="recaptcha-script";const i=e.createElement("script");i.id=a;i.setAttribute("src","https://www.google.com/recaptcha/api.js?onload=recaptchaReady&render=explicit");e.head.appendChild(i)}).then(()=>{this.renderRecaptcha()})}else{this.renderRecaptcha()}},renderRecaptcha(){this.recaptcha=grecaptcha.render(this.$refs.recaptcha_target,{sitekey:this.sitekey,theme:this.theme,size:this.size,tabindex:this.tabindex,callback:t=>this.$emit("verify",t),"expired-callback":()=>this.$emit("expire"),"error-callback":()=>this.$emit("fail")})},reset(){grecaptcha.reset(this.recaptcha)}},template:`	
    <div class="mb-2 mt-2" ref="recaptcha_target"></div>
    `};const r={props:["label","ajax_url","api_url","merchant_uuid","booking_enabled_capcha","captcha_site_key","reservation_uuid"],components:{"component-phone":e,"vue-recaptcha":l},data(){return{steps:1,guest_list:[],guest:1,reservation_date:"",reservation_time:"",reservation_time_pretty:"",time_slot:[],date_list:[],all_time_slot:[],tc:"",first_name:"",last_name:"",email_address:"",special_request:"",mobile_prefix:"",mobile_number:"",loading:false,loading_time_slot:false,submit_loading:false,next_step_loading:false,reservation_info:[],success_data:[],recaptcha_response:"",not_available_time:[],track_reservation_link:"",data_booking:[],details_link:"",allowed_choose_table:false,room_list:[],room_uuid:"",table_uuid:"",user_data:""}},created(){this.getBookingAttributes()},computed:{hasTimeSlot(){if(Object.keys(this.all_time_slot).length>0){return true}return false},bookingValid(){let t=true;if(this.guest<=0){t=false}if(a(this.reservation_date)){t=false}if(a(this.reservation_time)){t=false}return t},reservationValid(){let t=true;if(a(this.first_name)){t=false}if(a(this.last_name)){t=false}if(!this.validEmail(this.email_address)){t=false}if(a(this.mobile_number)){t=false}return t},isEdit(){if(!a(this.reservation_uuid)){return true}return false}},methods:{validEmail(t){var e=/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;return e.test(t)},getBookingAttributes(){this.loading=true;let t=!a(this.reservation_uuid)?this.reservation_uuid:"";axios({method:"POST",url:this.ajax_url+"/Getbookingattributes",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&merchant_uuid="+this.merchant_uuid+"&id="+t,timeout:i}).then(t=>{if(t.data.code==1){this.guest_list=t.data.details.guest_list;this.date_list=t.data.details.date_list;this.time_slot=t.data.details.time_slot;this.all_time_slot=t.data.details.all_time_slot;this.reservation_date=t.data.details.default_date;this.tc=t.data.details.tc;this.allowed_choose_table=t.data.details.allowed_choose_table;this.room_list=t.data.details.room_list;this.not_available_time=t.data.details.not_available_time;this.guest=t.data.details.default_guest;this.data_booking=t.data.details.data_booking;this.details_link=t.data.details.details_link;if(!a(this.reservation_uuid)){this.guest=t.data.details.data_booking.guest_number_raw;this.reservation_time=t.data.details.data_booking.reservation_time_raw;this.first_name=t.data.details.data_booking.first_name;this.last_name=t.data.details.data_booking.last_name;this.email_address=t.data.details.data_booking.email_address;this.special_request=t.data.details.data_booking.special_request;this.mobile_prefix=t.data.details.data_booking.phone_prefix;this.mobile_number=t.data.details.data_booking.contact_phone_without_prefix}}else{this.guest_list=[];this.date_list=[];this.time_slot=[];this.all_time_slot=[];this.reservation_date="";this.tc="";this.not_available_time=[];this.data_booking=[];this.allowed_choose_table=[];this.room_list=[];this.room_uuid="";this.table_uuid=""}})["catch"](t=>{}).then(t=>{this.loading=false})},getTimeslot(){this.loading_time_slot=true;this.reservation_time="";let t="YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&merchant_uuid="+this.merchant_uuid;t+="&reservation_date="+this.reservation_date;t+="&guest="+this.guest;t+="&id="+this.reservation_uuid;axios({method:"POST",url:this.ajax_url+"/Gettimeslot",data:t,timeout:i}).then(t=>{if(t.data.code==1){this.time_slot=t.data.details.time_slot;this.all_time_slot=t.data.details.all_time_slot;this.not_available_time=t.data.details.not_available_time}else{this.time_slot=[];this.all_time_slot=[];this.not_available_time=[]}})["catch"](t=>{}).then(t=>{this.loading_time_slot=false})},nextStep(){this.next_step_loading=true;let t=!a(this.reservation_uuid)?this.reservation_uuid:"";let e="YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&merchant_uuid="+this.merchant_uuid;e+="&reservation_date="+this.reservation_date;e+="&reservation_time="+this.reservation_time;e+="&guest="+this.guest;e+="&id="+t;axios({method:"POST",url:this.ajax_url+"/SetBooking",data:e,timeout:i,headers:{Authorization:`token ${s()}`}}).then(t=>{if(t.data.code==1){this.steps=2;this.reservation_info=t.data.details;this.table_list=t.data.details.table_list;if(!a(this.reservation_uuid)){this.room_uuid=t.data.details.room_uuid;this.table_uuid=t.data.details.table_uuid}else{this.room_uuid="";this.table_uuid=""}this.user_data=t.data.details.user_data;if(Object.keys(this.user_data).length>0){this.first_name=this.user_data.first_name;this.last_name=this.user_data.last_name;this.email_address=this.user_data.email_address;this.mobile_prefix=this.user_data.phone_prefix;this.mobile_number=this.user_data.contact_number_without_prefix}}else{this.reservation_info=[];this.table_list=[];ElementPlus.ElNotification({title:"",message:t.data.msg,position:"bottom-right",type:"warning"})}})["catch"](t=>{}).then(t=>{this.next_step_loading=false})},submit(){this.submit_loading=true;let t="YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&merchant_uuid="+this.merchant_uuid;t+="&reservation_date="+this.reservation_date;t+="&reservation_time="+this.reservation_time;t+="&guest="+this.guest;t+="&first_name="+this.first_name;t+="&last_name="+this.last_name;t+="&email_address="+this.email_address;t+="&mobile_prefix="+this.mobile_prefix;t+="&mobile_number="+this.mobile_number;t+="&room_uuid="+this.room_uuid;t+="&table_uuid="+this.table_uuid;t+="&special_request="+this.special_request;t+="&recaptcha_response="+this.recaptcha_response;t+="&id="+this.reservation_uuid;axios({method:"POST",url:this.ajax_url+"/ReserveTable",data:t,timeout:i,headers:{Authorization:`token ${s()}`}}).then(t=>{if(t.data.code==1){this.steps=3;this.success_data=t.data.details;this.track_reservation_link=t.data.details.track_reservation_link;setTimeout(()=>{const t=document.getElementById("reservation_ty");t.scrollIntoView({behavior:"smooth"})},500);this.room_uuid="";this.table_uuid=""}else{this.success_data=[];this.track_reservation_link="";ElementPlus.ElNotification({title:"",message:t.data.msg,position:"bottom-right",type:"warning"})}})["catch"](t=>{}).then(t=>{this.submit_loading=false})},resetReservation(){this.steps=1;this.getBookingAttributes();this.guest=1;this.reservation_date="";this.reservation_time=""},recaptchaVerified(t){this.recaptcha_response=t},recaptchaExpired(){if(this.booking_enabled_capcha){this.$refs.vueRecaptcha.reset()}},recaptchaFailed(){},isNotavailable(t){if(Object.keys(this.not_available_time).length>0){if(this.not_available_time.includes(t)===true){return true}}return false},trackReservation(){window.location.href=this.track_reservation_link},clearTableList(){this.table_uuid=""}},template:`   
    
    <template v-if="steps==2">
                        
        <div class="mt-2 mb-2" v-loading="submit_loading">

         <div class="d-flex align-items-center mb-2">                        
           <b><i class="zmdi zmdi-arrow-left mr-2"></i></b>
           <a @click="this.steps=1" class="mr-2"><b>{{label.back}}</b></a>
         </div>        

          <h5>{{label.reservation_details}}</h5>          
          <p class="m-0">{{reservation_info.full_time}}</p>
          <p class="m-0">{{reservation_info.guest}}</p>

          <h5 class="mt-3">{{label.personal_details}}</h5>

        <div class="row mb-3">
            <div class="col">
               <p class="m-0 p-0 ">{{label.first_name}}</p>
               <el-input v-model="first_name"  size="large" />
            </div>
            <div class="col">
               <p class="m-0 p-0">{{label.last_name}}</p>
               <el-input v-model="last_name"  size="large" />
            </div>
          </div>
          <!-- row -->

          <div class="row mb-3">
            <div class="col">
              <p class="m-0 p-0">{{label.email_address}}</p>
              <el-input v-model="email_address"  size="large" />
            </div>
          </div>
          <!-- row -->

          <component-phone	    
           :ajax_url="ajax_url"
            v-model:mobile_number="mobile_number"
            v-model:mobile_prefix="mobile_prefix"
          >
	      </component-phone>   
                  
        <template v-if="allowed_choose_table">
        <div class="row mb-3">
          <div class="col"> 
            <p class="m-0 p-0">{{label.room_name}}</p>   
            <el-select v-model="room_uuid"  @change="clearTableList"  class="m-2" placeholder="Select" size="large">
              <el-option
              v-for="item in this.room_list"
              :key="item.value"
              :label="item.label"
              :value="item.value"
              />
             </el-select>
          </div>

          <div class="col">                          
             <p class="m-0 p-0">{{label.table_name}}</p>                
             <el-select v-model="table_uuid"  class="m-2" placeholder="Select" size="large">
             <el-option
             v-for="item in this.table_list[room_uuid]"
             :key="item.value"
             :label="item.label"
             :value="item.value"
             />
             </el-select>
          </div>

        </div>
        </template>
        <!-- row -->
          
          <div class="row mb-3">
            <div class="col ">
                <p class="m-0 p-0">{{label.special_request}}</p>                
                <el-input
                v-model="special_request"
                :rows="3"
                type="textarea"                
                >
                </el-input>
            </div>
          </div>
          <!-- row -->

          <p v-html="label.agree"></p>

          <vue-recaptcha  
          :sitekey="captcha_site_key"
          size="normal" 
          theme="light"
          :tabindex="0"
          :is_enabled="booking_enabled_capcha"
          @verify="recaptchaVerified"
          @expire="recaptchaExpired"
          @fail="recaptchaFailed"
          ref="vueRecaptcha">
          </vue-recaptcha>		

        </div>
        <!-- mt -->               

        <div class="mt-3 mb-1">                
          <el-button @click="submit"  size="large" type="success" style="width:100%;" 
          :disabled="!reservationValid" 
          :loading="submit_loading"
          >{{label.reserve}}</el-button>
       </div>

    </template>

    <template v-else-if="steps==3">
            
      <div id="reservation_ty" class="card border p-3">
        <div class="text-center">    
          <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"> <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/> <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
          </svg>    
                    
          <template v-if="isEdit">
             <h4>{{label.reservation_updated}}</h4>
             <p>{{label.reservation_succesful_notes}}</p>          
          </template>
          <template>
            <h4>{{label.reservation_succesful}}</h4>
            <p>{{label.reservation_succesful_notes}}</p>
          </template>

          <h6>{{success_data.full_time}}</h6>
          <p>{{success_data.guest}}</p>
          <p>{{label.reservation_id}}# <span class="text-success">{{success_data.reservation_id}}</span></p>

          <el-button          
          v-if="!isEdit"
          type="success"        
          @click="resetReservation()"  
          >
          {{label.reserved_table_again}}
          </el-button>
          
          <el-button                              
          @click="trackReservation"
          >
          {{label.track_your_reservation}}
          </el-button>          
          
        </div>        
      </div>
  
    </template>   
    <template v-else>
                    

    <div v-if="isEdit" class="d-flex align-items-center mb-2">                        
     <b><i class="zmdi zmdi-arrow-left mr-2"></i></b>
      <a :href="details_link"  class="mr-2"><b>{{label.back}}</b></a>
    </div>        


    <div class="row">
      <div class="col">        
       <p class="m-0 p-0 ml-2">{{label.guest}}</p>
       <el-select v-model="guest" @change="getTimeslot" class="m-2" placeholder="Select" size="large">
        <el-option
        v-for="item in this.guest_list"
        :key="item.value"
        :label="item.label"
        :value="item.value"
        />
       </el-select>
      </div>
      
      <div class="col ">         
         <p class="m-0 p-0 ml-2">{{label.date}}</p>
         <el-select v-model="reservation_date" @change="getTimeslot" class="m-2" placeholder="Select" size="large">
            <el-option
            v-for="item in this.date_list"
            :key="item.value"
            :label="item.label"
            :value="item.value"
            />
        </el-select>
      </div>

      <div class="col ">
        <p class="m-0 p-0 ml-2 mb-2">{{label.time}}</p>
         <el-input v-model="reservation_time"  size="large" disabled />
      </div>
    </div>
                    
    <div class="mt-2 mb-2" v-loading="loading_time_slot">       
       <template v-if="hasTimeSlot">
       <el-radio-group v-model="reservation_time" size="large">
            <template v-for="items in time_slot">                  
               <template v-for="(item,index) in items">
                 <el-radio-button :label="index" :disabled="isNotavailable(index)" >{{item}}</el-radio-button>            
              </template>
            </template>
        </el-radio-group>
       </template>
       <template v-else>        
        <el-alert 
        v-if="!loading"
        :title="label.no_results"
         type="error"
         :closable="false"
        >
        </el-alert>
       </template>    
    </div>
    
    <template v-if="this.tc">
    <div class="mt-2 mb-3">
        <h6>{{label.terms}}</h6>
        <div v-html="this.tc"></div>
    </div>
    </template>            
    
    <div class="mt-3 mb-1">
    <el-button @click="nextStep" size="large" type="success" style="width:100%;" 
    :disabled="!bookingValid"
    :loading="next_step_loading"
    >
      {{label.continue}}
    </el-button>
    </div>
    
    </template>
    `};const o={props:["id","ajax_url"],data(){return{steps:1,loading:false,data:[],merchant:[],cancel_link:"",update_link:"",cancel_reservation_stats:[],pending_reservation_stats:[],confirm_reservation_stats:[],completed_reservation_stats:[]}},created(){this.getBookingDetails()},computed:{hasData(){if(Object.keys(this.data).length>0){return true}return false},hasMerchant(){if(Object.keys(this.merchant).length>0){return true}return false},CanCancelReservation(){if(this.cancel_reservation_stats.includes(this.data.status)===true){return false}return true}},methods:{getBookingDetails(){this.loading=true;let t="YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&id="+this.id;axios({method:"POST",url:this.ajax_url+"/BookingDetails",data:t,timeout:i,headers:{Authorization:`token ${s()}`}}).then(t=>{if(t.data.code==1){this.data=t.data.details.data;this.merchant=t.data.details.merchant;this.cancel_link=t.data.details.cancel_link;this.update_link=t.data.details.update_link;this.cancel_reservation_stats=t.data.details.cancel_reservation_stats;this.pending_reservation_stats=t.data.details.pending_reservation_stats;this.confirm_reservation_stats=t.data.details.confirm_reservation_stats;this.completed_reservation_stats=t.data.details.completed_reservation_stats;this.setSteps()}else{this.data=[];this.merchant=[];this.cancel_link="";this.update_link="";this.cancel_reservation_stats=[];this.pending_reservation_stats=[];this.confirm_reservation_stats=[];this.completed_reservation_stats=[]}})["catch"](t=>{}).then(t=>{this.loading=false})},setSteps(){t("setSteps");if(this.confirm_reservation_stats.includes(this.data.status)===true){this.steps=2}else if(this.completed_reservation_stats.includes(this.data.status)===true){this.steps=3}else if(this.cancel_reservation_stats.includes(this.data.status)===true){this.steps=4}else{this.steps=1}},toCancelPage(){window.location.href=this.cancel_link},toUpdatePage(){window.location.href=this.update_link}},template:"#xtemplate_booking_details"};const n={props:["id","ajax_url","label"],data(){return{reason:"",loading:true,data:[],submit:false}},created(){this.getCancelreason()},computed:{hasData(){if(!a(this.reason)){return true}return false}},methods:{getCancelreason(){this.loading=true;let t="YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&id="+this.id;axios({method:"POST",url:this.ajax_url+"/getCancelreason",data:t,timeout:i}).then(t=>{if(t.data.code==1){this.data=t.data.details.data}else{this.data=[]}})["catch"](t=>{}).then(t=>{this.loading=false})},ConfirmcancelReservation(){ElementPlus.ElMessageBox.confirm(this.label.confirm,this.label.cancel_reservation,{confirmButtonText:this.label.yes,cancelButtonText:this.label.cancel,type:"warning"}).then(()=>{t("here");this.CancelReservation()})["catch"](()=>{})},CancelReservation(){this.submit=true;let t="YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&id="+this.id;t+="&reason="+this.reason;axios({method:"POST",url:this.ajax_url+"/CancelReservation",data:t,timeout:i,headers:{Authorization:`token ${s()}`}}).then(t=>{if(t.data.code==1){ElementPlus.ElNotification({title:"",message:t.data.msg,position:"bottom-right",type:"success"});setTimeout(()=>{window.location.href=t.data.details.redirect_url},500)}else{this.submit=false;ElementPlus.ElNotification({title:"",message:t.data.msg,position:"bottom-right",type:"warning"})}})["catch"](t=>{}).then(t=>{})}},template:"#xtemplate_booking_cancel"};const d=Vue.createApp({components:{"component-reservation":r,"booking-details":o,"booking-cancel":n},created(){}});d.use(ElementPlus);const c=d.mount("#vue-booking-reservation");const _={template:"#xtemplate_booking_list",props:["api_url","status_list","q"],data(){return{tab:"all",page:1,data:[],code:0,loading:false,load_more:false,merchant:[],table_list:[],show_next:false,awaitingSearch:false}},created(){this.BookingList()},computed:{hasData(){if(Object.keys(this.data).length>0){return true}return false},statusColor(){return"{background:'#f44336'}"}},watch:{q(t,e){if(!this.awaitingSearch){if(a(t)){return false}setTimeout(()=>{this.resetData();this.awaitingSearch=false},1e3);this.awaitingSearch=true}},awaitingSearch(t,e){this.$emit("setSearch",t)}},methods:{resetData(){this.page=1;this.tab="all";this.data=[];this.merchant=[];this.table_list=[];this.BookingList(false)},loadMore(t){this.page=t;this.BookingList(true)},tabChange(t){this.page=1;this.data=[];this.merchant=[];this.BookingList(false)},BookingList(t){if(t){this.load_more=true}else{this.loading=true}let e="YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&status="+this.tab+"&page="+this.page+"&q="+this.q;axios({method:"POST",url:this.api_url+"/BookingList",data:e,timeout:i,headers:{Authorization:`token ${s()}`}}).then(t=>{this.code=t.data.code;if(t.data.code==1){this.data.push(t.data.details.data);this.merchant=t.data.details.merchant;this.table_list=t.data.details.table_list;this.show_next=t.data.details.show_next;this.page=t.data.details.page_raw}})["catch"](t=>{}).then(t=>{this.load_more=false;this.loading=false})}}};const h=Vue.createApp({components:{"component-booking-list":_},data(){return{q:"",awaitingSearch:false}},methods:{setSearch(t){this.awaitingSearch=t},resetData(){this.q="";setTimeout(()=>{this.$refs.booking_list.resetData()},500)}}});h.use(ElementPlus);const m=h.mount("#vue-my-bookings")})})(jQuery);