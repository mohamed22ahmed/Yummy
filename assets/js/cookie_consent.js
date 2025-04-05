(function(e){"use strict";jQuery(document).ready(function(){var e=function(e){console.debug(e)};var t=function(e){if(typeof e==="undefined"||e==null||e==""||e=="null"||e=="undefined"){return true}return false};var o=function(e,t,i){const o=new Date;o.setTime(o.getTime()+i*24*60*60*1e3);let s="expires="+o.toUTCString();document.cookie=e+"="+t+";"+s+";path=/"};var i=function(e,t,i){let o=e+"=";let s=document.cookie.split(";");for(let t=0;t<s.length;t++){let e=s[t];while(e.charAt(0)==" "){e=e.substring(1)}if(e.indexOf(o)==0){return e.substring(o.length,e.length)}}return""};const s=2e4;const c={props:["label","preferences_data","cookie_expiration","themes","show_preferences"],data(){return{consent_visible:false,cookie_preferences:[],is_customize:false}},created(){this.consent_visible=true},computed:{classObject(){let e="left";if(this.themes.cookie_position=="top_right"){e="right"}else if(this.themes.cookie_position=="bottom_right"){e="right"}else if(this.themes.cookie_position=="bottom_left"){e="left"}else if(this.themes.cookie_position=="top_left"){e="left"}return e},styleObject(){if(this.themes.cookie_position=="top_right"||this.themes.cookie_position=="top_left"){return{top:"16px","z-index":"2003"}}else{return{bottom:"16px","z-index":"2003"}}},isOkToAccept(){if(this.is_customize){if(Object.keys(this.cookie_preferences).length>0){}else{return true}}return false}},methods:{hasData(){if(Object.keys(this.cookie_preferences).length>0){return true}return false},showConsent(){let e="";e+='<div class="cookie-content mb-2">';e+=this.label.cookie_message;e+="</div>";e+='<div class="d-flex align-items-center">';e+='<button @click="test" type="button" class="btn btn-primary">'+this.label.accept_button+"</button>";e+="<div>";ElementPlus.ElNotification({title:this.label.cookie_title,dangerouslyUseHTMLString:true,message:e,position:"bottom-left",duration:4500*1e3,customClass:"cookie-consent"})},close(){this.consent_visible=false},accept(){if(this.hasData()){if(Object.keys(this.cookie_preferences).length>0){let i=[];Object.entries(this.cookie_preferences).forEach(([e,t])=>{i.push(t)})}o("cookieConsentPrefs",this.cookie_preferences,this.cookie_expiration)}else{if(Object.keys(this.preferences_data).length>0){let i=[];Object.entries(this.preferences_data).forEach(([e,t])=>{i.push(t.preferences)});o("cookieConsentPrefs",i,this.cookie_expiration)}}o("cookieConsent",true,this.cookie_expiration);this.consent_visible=false},decline(){e("decline");o("cookieConsent",false,this.cookie_expiration);this.consent_visible=false}},template:`
        <div v-if="consent_visible" id="notification_1" class="el-notification cookie-consent" :class="classObject" :style="styleObject" role="alert">          
          <div class="el-notification__group">
             <h2 class="el-notification__title">{{label.cookie_title}}</h2>
             <div class="el-notification__content cookie-content" >            
                 <span class="el-content-message" v-html="label.cookie_message"></span>
                 
                 <template v-if="show_preferences">
                 <div class="mt-2 d-flex align-items-center">
                    <i class="zmdi zmdi-settings mr-2"></i> <el-button type="text" size="small" link @click="is_customize=!is_customize"  >{{label.customize}}</el-button>                    
                 </div>                           
                 <div v-if="is_customize" class="mt-2 mb-2">
                   <div class="font13">{{label.select_cookies}}</div>                   
                   <div class="d-flexx">
                        <el-checkbox-group v-model="cookie_preferences">
                          <template v-for="pref in preferences_data">
                          <el-checkbox :label="pref.preferences">{{pref.title}}</el-checkbox>
                          </template>
                        </el-checkbox-group>                    
                   </div>
                 </div>
                 </template>

                 <div class="mt-3 mb-2 row">                               
                   <div class="col"><el-button type="primary" class="w-100" round @click="accept" :color="themes.cookie_theme_primary_color"                   
                   :disabled="isOkToAccept"
                    >
                     {{label.accept_button}}</el-button>
                   </div>
                   <div class="col">
                     <el-button round class="w-100" @click="decline" :type="themes.cookie_theme_mode=='dark'?'info':''" >
                      {{label.reject_button}}
                      </el-button>
                   </div>
                 </div>                                 
             </div>
             <i class="el-icon el-notification__closeBtn" @click="close" >
              <svg class="icon" width="200" height="200" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                <path fill="currentColor" d="M764.288 214.592L512 466.88 259.712 214.592a31.936 31.936 0 00-45.12 45.12L466.752 512 214.528 764.224a31.936 31.936 0 1045.12 45.184L512 557.184l252.288 252.288a31.936 31.936 0 0045.12-45.12L557.12 512.064l252.288-252.352a31.936 31.936 0 10-45.12-45.184z"></path>
              </svg>
             </i>
          </div>
        </div>
        `};const n=Vue.createApp({components:{"component-cookie-consent":c},created(){}});n.use(ElementPlus);const l=n.mount("#vue-cookie-consent")})})(jQuery);