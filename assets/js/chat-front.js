import{initializeApp}from"https://www.gstatic.com/firebasejs/9.9.3/firebase-app.js";import{getFirestore,onSnapshot,collection,doc,getDocs,getDoc,query,orderBy,limit,where,Timestamp,addDoc,setDoc,updateDoc,serverTimestamp,deleteDoc}from"https://www.gstatic.com/firebasejs/9.9.3/firebase-firestore.js";import"https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js";import insertText from"https://cdn.jsdelivr.net/npm/insert-text-at-cursor@0.3.0/index.js";var empty=function(e){if(typeof e==="undefined"||e==null||e==""||e=="null"||e=="undefined"){return true}return false};let $fc=JSON.parse(firebase_configuration);const firebaseConfig={apiKey:$fc.firebase_apikey,authDomain:$fc.firebase_domain,projectId:$fc.firebase_projectid,storageBucket:$fc.firebase_storagebucket,messagingSenderId:$fc.firebase_messagingid,appId:$fc.firebase_appid};const firebaseCollectionEnum={chats:"chats",limit:500};const firebaasApp=initializeApp(firebaseConfig);const firebaseDb=getFirestore(firebaasApp);const quasarComponents={empty(e){if(typeof e==="undefined"||e===null||e===""||e==="null"||e==="undefined"){return true}return false},setStorage(e,t){try{Quasar.LocalStorage.set(e,t)}catch(s){console.debug(s)}},getStorage(e){return Quasar.LocalStorage.getItem(e)},notify(e,t,s){const a=Quasar.Notify;a.create({message:t,color:e,icon:s,position:"bottom",html:true,timeout:3e3,multiLine:false,actions:[{noCaps:true,color:"white",handler:()=>{}}]})}};const DateTime=luxon.DateTime;const LuxonSettings=luxon.Settings;const componentsTest={props:["api"],created(){},computed:{},methods:{},template:`
    test 
    `};const componentsUserSkeleton={props:["rows"],template:`
    <q-list >
      <q-item v-for="items in rows" :key="items">
        <q-item-section avatar>
          <q-skeleton type="QAvatar" />
        </q-item-section>
        <q-item-section>
          <q-item-label>
            <q-skeleton type="text" />
          </q-item-label>
          <q-item-label caption>
            <q-skeleton type="text" />
          </q-item-label>
         </q-item-section>
      </q-item>
    </q-list>    
    `};const componentsParticipants={props:["api","user_uuid","language","label","main_user_type"],components:{"components-loader":componentsUserSkeleton},data(){return{data:[],users:[],all_users:[],users_data:[],loading:false,loading_user:false,last_message_data:{},whoistyping_data:{},document_id:""}},created(){this.getParticipants()},computed:{getData(){return this.data},getLastMessageData(){return this.last_message_data},hasData(){if(Object.keys(this.data).length>0){return true}return false},hasUserData(){if(Object.keys(this.users_data).length>0){return true}return false},getShowistyping(){return this.whoistyping_data}},watch:{},methods:{getParticipants(){this.loading=true;const e=collection(firebaseDb,firebaseCollectionEnum.chats);const t=query(e,where("participants","array-contains",this.user_uuid),orderBy("lastUpdated","desc"),limit(firebaseCollectionEnum.limit));const s=onSnapshot(t,e=>{this.data=[];this.users=[];this.all_users=[];this.loading=false;e.forEach(s=>{let a=s.data();let i=a.isTyping||null;let r=a.participants||null;if(r){if(Object.keys(r).length>0){Object.entries(r).forEach(([e,t])=>{this.all_users.push(t)})}let e=r.filter(e=>!e.includes(this.user_uuid));let t=e[0]?e[0]:null;this.users.push(t);this.data.push({doc_id:s.id,user_uuid:t,is_typing:i[e[0]]?i[e[0]]:false,orderID:a.orderID||null,orderUuid:a.orderUuid||null})}});if(Object.keys(this.users).length>0){this.getUser();this.getLastMessage();this.getWhoIsTyping()}},e=>{this.loading=false;console.log("Error fetching chat documents:",e)})},getUser(){this.loading_user=true;axios({method:"post",url:this.api+"/getUsers?language="+this.language,data:{main_user_type:this.main_user_type,users:this.users}}).then(e=>{if(e.data.code==1){this.users_data=e.data.details}else{this.users_data=[]}this.$emit("setUserdata",this.users_data)})["catch"](e=>{}).then(e=>{this.loading_user=false})},async getLastMessage(){try{if(Object.keys(this.users).length>0){const i=this.users.splice(0,10);const t=collection(firebaseDb,firebaseCollectionEnum.chats);const s=await getDocs(query(t,where("participants","array-contains-any",i)));s.forEach(async e=>{const a=e.id;const t=collection(firebaseDb,firebaseCollectionEnum.chats,a,"messages");const s=await getDocs(query(t,where("senderID","in",i),orderBy("timestamp","desc"),limit(1)));s.forEach(e=>{let t=e.data();let s=t.timestamp.toDate().toISOString();this.last_message_data[a]={message:t.message,timestamp:s,time:DateTime.fromISO(s).toFormat("hh:mm a")}})})}}catch(e){console.error("Error fetching last message:",e)}},async getWhoIsTyping(){if(Object.keys(this.users).length>0){const e=this.users.splice(0,10);const t=query(collection(firebaseDb,firebaseCollectionEnum.chats),where("participants","array-contains-any",e),limit(firebaseCollectionEnum.limit));const s=onSnapshot(t,e=>{e.forEach(e=>{let t=e.data();let s=t.isTyping||[];if(Object.keys(s).length>0){Object.entries(s).forEach(([e,t])=>{this.whoistyping_data[e]=t})}})})}},isTyping(t){if(Object.keys(this.whoistyping_data).length>0){let e=this.whoistyping_data[t]||false;return e}return false},onClickChat(e){this.document_id=e;this.$emit("afterClickconversation",e)}},template:`            
    <template v-if="loading">
       <components-loader :rows="10"></components-loader>
    </template> 
      
                
    <template v-if="hasData && !loading && hasUserData">        
        <q-list class="list-custom">
          <template v-for="items in getData" :key="items">
          <template v-if="users_data[items.user_uuid]">
            <q-item  clickable v-ripple @click="onClickChat(items.doc_id)" :active="items.doc_id==document_id" active-class="bg-mygrey text-grey-8 q-mb-sm" >        
                <q-item-section avatar>
                    <q-avatar>                                       
                      <img :src="users_data[items.user_uuid].photo_url"  />
                    </q-avatar>
                </q-item-section>
                <q-item-section>
                    <q-item-label class="text-weight-bold">
                      {{ users_data[items.user_uuid].first_name }} {{ users_data[items.user_uuid].last_name }}
                    </q-item-label>
                    <q-item-label caption>
                       <template v-if="items.orderID">
                          {{label.order_number}} {{items.orderID}}
                       </template>
                       <template v-else>
                          {{ users_data[items.user_uuid].user_type }}
                       </template>                       
                    </q-item-label>

                    <q-item-label caption lines="2" v-if="getLastMessageData[items.doc_id]">
                      <template v-if="isTyping(items.user_uuid)">
                        <span class="text-primary">{{ users_data[items.user_uuid].first_name }} {{ label.is_typing }} ...</span>
                      </template>
                      <template v-else>
                         {{ getLastMessageData[items.doc_id].message }}
                      </template>
                    </q-item-label>

                    <q-item-label caption lines="1" v-else>
                      <template v-if="isTyping(items.user_uuid)">
                        <span class="text-primary">{{ users_data[items.user_uuid].first_name }} {{ label.is_typing }} ...</span>
                      </template>
                    </q-item-label>

                    
                </q-item-section>                            
                <q-item-section side >                                    
                    <q-item-label caption lines="2"  class="text-center"  >                     
                      <div class="time" v-if="getLastMessageData[items.doc_id]" >{{ getLastMessageData[items.doc_id].time }}</div>                                 
                    </q-item-label>
                </q-item-section>
            </q-item>       
          </template>
          </template>
        </q-list>
    </template>
    `};const componentsMessages={props:["api","user_uuid","conversation_id","user_data","label","no_chat_image_url"],watch:{conversation_id(e,t){if(!empty(e)){this.getMessages(e);this.getWhoIsTyping(e);this.getParticipant(e)}else{this.data=[];this.user_typing_data=[];this.chating_with_user_uuid=""}}},data(){return{data:[],loading:false,user_typing_data:[],chating_with_user_uuid:""}},computed:{getChatmessage(){return this.data},hasMessage(){if(Object.keys(this.data).length>0){return true}return false},hasChatDocID(){if(!empty(this.chating_with_user_uuid)){return true}return false},hasUserData(){if(Object.keys(this.user_data).length>0){return true}return false},getUserData(){return this.user_data},getUserTyping(){return this.user_typing_data}},methods:{getMessages(e){this.loading=true;const t=doc(firebaseDb,firebaseCollectionEnum.chats,e);const s=query(collection(t,"messages"),orderBy("timestamp","asc"),limit(firebaseCollectionEnum.limit));const a=onSnapshot(s,e=>{this.data=[];this.loading=false;e.forEach(t=>{if(t.exists()){const s=t.data();let e=s.timestamp.toDate().toISOString();this.data.push({fileType:s.fileType,fileUrl:s.fileUrl,message:s.message,senderID:s.senderID,time:DateTime.fromISO(e).toFormat("ccc hh:mm a")})}else{console.log("Conversation document does not exist")}});this.$emit("scrollTobottom")},e=>{this.loading=false;console.error("Error fetching messages:",e)})},getWhoIsTyping(e){const t=doc(firebaseDb,firebaseCollectionEnum.chats,e);const s=onSnapshot(t,t=>{if(t.exists()){let e=t.data();this.user_typing_data=e.isTyping||[]}else{this.user_typing_data=[]}this.$emit("scrollTobottom")},e=>{console.error("Error fetching chat document:",e)})},async getParticipant(e){try{const s=doc(firebaseDb,firebaseCollectionEnum.chats,e);const a=await getDoc(s);if(a.exists()){const i=a.data().participants;let e=i.filter(e=>!e.includes(this.user_uuid));this.chating_with_user_uuid=e[0]?e[0]:null;this.$emit("setChattingwith",this.chating_with_user_uuid)}else{console.log("Conversation document does not exist");this.$emit("setChattingwith",null)}}catch(t){console.error("Error getting participants:",t)}}},template:`                     
     <q-inner-loading
        :showing="loading"
        color="primary"
        :label="label.please_wait"
        label-class="text-dark"
        label-style="font-size: 1em"
    >
    </q-inner-loading>    


    <template v-for="items in getChatmessage" :key="items">       
      <q-chat-message
        :text-color="items.senderID==user_uuid?'white':'dark'"
        :bg-color="items.senderID==user_uuid?'primary':'mygrey'"
        :sent="items.senderID==user_uuid?true:false"  
        :stamp="items.time"
      >
        <template v-slot:name>
          <template v-if="getUserData[items.senderID]">
             {{ getUserData[items.senderID].first_name }} {{ getUserData[items.senderID].last_name }}               
          </template>
          <template v-else>
             <template v-if="items.senderID!=user_uuid">
                {{label.uknown}}
             </template>             
          </template>

          <template v-if="items.senderID!=user_uuid">
            <q-badge rounded color="yellow" class="q-ml-sm q-mr-sm"></q-badge> 
            <span class="text-grey text-caption">{{ getUserData[items.senderID].user_type }}  </span>
          </template>

        </template>
        <!--- slot-name -->
        
        <template v-slot:avatar>
            <template v-if="items.senderID!=user_uuid">
              <img
                class="q-message-avatar q-message-avatar--received"
                :src="getUserData[items.senderID].photo_url"
              >
              </img>
            </template>                
        </template>
        
        <div>
          <template v-if="items.message && items.fileUrl">
              <div>{{items.message}}</div>
              <q-img
              :src="items.fileUrl"
              spinner-size="sm"
              spinner-color="primary"
              style="height: 80px; min-width:80px;max-width:80px"
              >
              </q-img>  
          </template>
          <template v-else-if="items.fileUrl">              
              <q-img
              :src="items.fileUrl"
              spinner-size="sm"
              spinner-color="primary"
              style="height: 180px; min-width:300px;max-width:300px"
              >
              </q-img>           
          </template>
          <template v-else>
             {{items.message}}          
          </template>          
        </div>                
      </q-chat-message>         

    </template>
    <!--- END MESSAGES -->

    
    <template v-if="!hasChatDocID && !loading">
       <div class="text-center q-mt-sm">       
          <q-img
          :src="no_chat_image_url"
          spinner-color="white"
          fit="fill"
          style="height: 120px; max-width: 130px"
         >
         </q-img>
         <h6 class="q-ma-none q-pt-md">{{label.no_chat_selected}}</h6>
       </div>
    </template>

    <!--- TYPING -->
    <template v-if="getUserTyping">
      <template v-for="(items, userUUID) in getUserTyping" :key="items">
         <template v-if="items">            
            <q-chat-message                    
            :text-color="userUUID==user_uuid?'white':'dark'"
            :bg-color="userUUID==user_uuid?'primary':'mygrey'"
            :sent="userUUID==user_uuid?true:false"  
            >       
            
              <template v-slot:name>

                <template v-if="userUUID!=user_uuid">
                  <template v-if="getUserData[userUUID]">
                    {{ getUserData[userUUID].first_name }} {{ getUserData[userUUID].last_name }}               
                  </template>
                  <template v-else>
                    {{label.uknown}}
                  </template>
                  
                    <q-badge rounded color="yellow" class="q-ml-sm q-mr-sm"></q-badge> 
                    <span class="text-grey text-caption">{{ getUserData[userUUID].user_type }}  </span>
                </template>

              </template>
              <!--- slot-name -->

              <template v-slot:avatar>
                 <template v-if="items.senderID!=user_uuid">
                    <template v-if="getUserData[userUUID]">
                    <img
                      class="q-message-avatar q-message-avatar--received"
                      :src="getUserData[userUUID].photo_url"
                    >
                    </img>
                    </template>
                 </template>
              </template>

              <div v-if="userUUID!=user_uuid">
                 <q-spinner-dots size="2rem" ></q-spinner-dots>
              </div>
            </q-chat-message>
         </template>
      </template>
    </template>
  `};const componentsChat={props:["api","api_upload","user_uuid","conversation_id","user_data","label","max_file_size"],data(){return{message:"",files:{},file_url:"",file_type:"",upload_loading:false,loading:false,is_typing:false}},computed:{hasConversation(){if(!empty(this.conversation_id)){return true}return false},hasMessage(){if(!empty(this.message)){return true}if(Object.keys(this.files).length>0){return true}return false}},watch:{conversation_id(e,t){this.message=""},is_typing(e,t){if(e){this.UpdateWhoistyping(true)}else{this.UpdateWhoistyping(false)}},message(e,t){if(!this.is_typing){setTimeout(()=>{this.is_typing=false},1e3)}this.is_typing=true}},methods:{onSubmit(){if(Object.keys(this.files).length>0){this.$refs.uploader.upload()}else{this.saveChatMessage()}},async saveChatMessage(){this.loading=true;const e=collection(firebaseDb,firebaseCollectionEnum.chats,this.conversation_id,"messages");try{await addDoc(e,{message:this.message,senderID:this.user_uuid,timestamp:Timestamp.now(),serverTimestamp:serverTimestamp(),fileUrl:this.file_url,fileType:this.file_type});this.loading=false;this.documentLastUpdate(this.conversation_id);this.resetChat();this.$emit("afterAddmessage")}catch(t){console.error("Error adding message to the conversation:",t);quasarComponents.notify("red-5",t,"error_outline")}},async documentLastUpdate(e){try{const s=doc(firebaseDb,firebaseCollectionEnum.chats,e);await updateDoc(s,{lastUpdated:serverTimestamp()})}catch(t){quasarComponents.notify("red-5",t,"error_outline")}},resetChat(){this.message="";this.file_url="";this.file_type="";this.files={};this.$refs.uploader.reset()},pickFiles(){this.$refs.uploader.pickFiles()},onRejectedFiles(e){quasarComponents.notify("red-5","Invalid file type","error_outline")},afterAddedFiles(e){Object.entries(e).forEach(([e,t])=>{this.files[t.name]={name:t.name}})},afterRemoveFiles(e){Object.entries(e).forEach(([e,t])=>{delete this.files[t.name]})},onUploadingFiles(e){this.upload_loading=true},afterUploaded(i){if(i.xhr.status==200){let e=JSON.parse(i.xhr.response);let t=e.code||false;let s=e.details||[];let a=e.msg||"";if(t==1){this.file_url=s.file_url;this.file_type=s.file_type;this.saveChatMessage()}else{quasarComponents.notify("red-5",a,"error_outline");this.$refs.uploader.reset()}}else{quasarComponents.notify("red-5","Error uploading files","error_outline");this.$refs.uploader.reset()}},afterFinishUpload(){this.upload_loading=false},showEmoji(){document.querySelector("emoji-picker").addEventListener("emoji-click",e=>{insertText(document.querySelector("textarea"),e.detail.unicode)})},async UpdateWhoistyping(e){try{const s=doc(firebaseDb,firebaseCollectionEnum.chats,this.conversation_id);await updateDoc(s,{[`isTyping.${this.user_uuid}`]:e})}catch(t){console.error("Error updating typing status:",t)}}},template:`   
  

  <div v-if="hasConversation" class="full-width border-grey q-pa-sm radius10">
    
    <q-inner-loading
      :showing="upload_loading"
      color="primary"
      :label="label.please_wait"
      label-class="text-dark"
      label-style="font-size: 1em"
    >
    </q-inner-loading>

    <q-uploader            
      :url="api_upload"            
      multiple
      ref="uploader"
      flat                  
      accept=".jpg, image/*"
      :max-total-size="max_file_size"
      field-name="file"            
      @added="afterAddedFiles"        
      @removed="afterRemoveFiles"   
      @rejected="onRejectedFiles"                        
      @uploading="onUploadingFiles"   
      @uploaded="afterUploaded" 
      @finish="afterFinishUpload"
      >
      <template v-slot:header="scope">         
        <q-uploader-add-trigger ></q-uploader-add-trigger>
      </template>
      <template v-slot:list="scope">
          <div class="flex justify-start q-col-gutter-x-md">
            <template v-for="file in scope.files" :key="file.__key">
              <div class="relative-position">
                  <img :src="file.__img.src" style="max-width: 60px; height:60px;" class="radius10"></img>
                  <div class="absolute-right" style="margin-right: -10px;margin-top: -5px;">
                    <q-btn 
                    unelevated 
                    round color="primary" 
                    icon="close" 
                    size="xs"
                    @click="scope.removeFile(file)"
                    ></q-btn>
                  </div>
              </div>            
            </template>
          </div>
      </template>
  </q-uploader>

    <q-input color="primary" 
    v-model="message"
    :label="label.your_message"      
    ref="message"      
    autogrow
    borderless             
    >
      <template v-slot:append>
        <div class="q-gutter-sm">

          <q-btn unelevated round color="mygrey" text-color="grey"  @click="pickFiles"  >
            <q-icon name="attach_file" class="rotate-45"></q-icon>
          </q-btn>

          <q-btn unelevated round color="mygrey" text-color="grey"  >
            <q-icon name="emoji_emotions" ></q-icon>
            <q-popup-proxy @show="showEmoji">
               <q-card>
               <emoji-picker ref="emoji"></emoji-picker>
               </q-card>
            </q-popup-proxy>
          </q-btn>               
       
          
          <q-btn    
          @click="onSubmit"              
          :disabled="!hasMessage"
          :loading="loading"
          flat color="primary" label="Send" no-caps size="lg" >
          </q-btn>                  

        </div>
      </template>
    </q-input>        

  </div>           
  `};const componentsSearchChat={props:["api","label","language","search_type"],data(){return{search:"",is_search:false,awaitingSearch:false}},computed:{hasSearch(){if(!empty(this.search)){return true}return false}},watch:{awaitingSearch(e,t){this.$emit("onSearchloading",e)},is_search(e,t){this.$emit("onSearchchat",e)},search(e,t){this.$emit("setSearchtext",e);if(!this.awaitingSearch){if(empty(e)){return false}setTimeout(()=>{axios({method:"post",url:this.api+"/searchChats?language="+this.language,data:{search:this.search,search_type:this.search_type}}).then(e=>{if(e.data.code==1){this.$emit("onSearchresults",e.data.details)}else{this.$emit("onSearchresults",[])}})["catch"](e=>{}).then(e=>{this.awaitingSearch=false})},1e3);this.awaitingSearch=true}}},methods:{closeSearch(){this.is_search=false;this.search=""}},template:`       
    <q-input color="primary" outlined v-model="search" :label="label.search_chat" class="q-mb-md"
    @click="is_search=true"    
    >            
      <template v-slot:prepend>              
        <q-btn v-if="is_search" @click="closeSearch" flat round color="primary" icon="keyboard_backspace" ></q-btn>
      </template>
      <template v-slot:append>
        <q-icon v-if="!is_search" name="search" size="md" ></q-icon>
        <q-btn v-if="hasSearch" @click="search=''" flat round color="primary" icon="highlight_off" ></q-btn>
      </template>
    </q-input>
  `};const deleteMessagesInConversation=async e=>{try{const s=collection(firebaseDb,firebaseCollectionEnum.chats,e,"messages");const a=await getDocs(s);a.forEach(async e=>{await deleteDoc(e.ref)});console.log("All messages in the conversation deleted successfully.")}catch(t){console.error("Error deleting messages in conversation:",t)}};const app_chat=Vue.createApp({components:{"components-participants":componentsParticipants,"components-messages":componentsMessages,"components-chat":componentsChat,"components-search-chat":componentsSearchChat},data(){return{snap_shot:undefined,data:[],drawer:false,message:"",conversation_id:"",user_data:[],user_typing_data:[],chatting_with_uuid:"",file:null,image:null,show_uploader:false,search_chat:false,search_loading:false,search_data:[],search_text:""}},created(){if(typeof order_uuid!=="undefined"&&order_uuid!==null){if(!empty(order_uuid)){this.createChatOrder()}}},computed:{hasConversation(){if(!empty(this.conversation_id)){return true}return false},hasMessage(){if(!empty(this.message)){return true}return false},hasSearch(){if(!empty(this.search_text)){return true}return false},hasSearchData(){if(Object.keys(this.search_data).length>0){return true}return false},getSearchData(){return this.search_data}},methods:{setUserdata(e){this.user_data=e},setWhoistyping(e){this.user_typing_data=e},afterClickconversation(e){this.conversation_id=e},setChattingwith(e){this.chatting_with_uuid=e;this.scrollTobottom()},afterAddmessage(){this.scrollTobottom();this.notifyUser()},scrollTobottom(){setTimeout(()=>{if(typeof this.$refs.scroll_ref!=="undefined"&&this.$refs.scroll_ref!==null){let e=parseInt(this.$refs.scroll_ref.getScroll().verticalSize)+100;this.$refs.scroll_ref.setScrollPosition("vertical",e)}},500)},onSearchchat(e){this.search_chat=e},onSearchresults(e){this.search_data=e},onSearchloading(e){this.search_loading=e},setSearchtext(e){this.search_text=e},async chatToUser(e){try{const s=collection(firebaseDb,firebaseCollectionEnum.chats);const i=query(s,where("participants","array-contains",e),orderBy("lastUpdated","desc"),limit(1));let a="";const r=await getDocs(i);r.forEach(e=>{let t=e.data();let s=t.participants||null;if(s.includes(main_user_uuid)===true){a=e.id}});console.log("main_user_uuid=>"+main_user_uuid);console.log("chatToUser=>"+e);console.log("current_doc_id=>"+a);if(!empty(a)){this.loadConversation(a,e)}else{this.createConversation(e)}}catch(t){quasarComponents.notify("red-5",t,"error_outline")}},async createConversation(t){try{const s=await addDoc(collection(firebaseDb,firebaseCollectionEnum.chats),{lastUpdated:serverTimestamp()});const a=s.id;const i=doc(firebaseDb,firebaseCollectionEnum.chats,a);let e={lastUpdated:serverTimestamp(),dateCreated:serverTimestamp(),participants:[t,main_user_uuid],isTyping:{[`${t}`]:false,[`${main_user_uuid}`]:false}};setDoc(i,e).then(()=>{this.loadConversation(a)})["catch"](e=>{quasarComponents.notify("red-5",e,"error_outline")})}catch(e){quasarComponents.notify("red-5",e,"error_outline")}},loadConversation(e){this.$refs.search_chat.closeSearch();this.conversation_id=e;setTimeout(()=>{if(typeof this.$refs.participants!=="undefined"&&this.$refs.participants!==null){this.$refs.participants.document_id=e}},600)},async deleteChat(){try{const t=doc(firebaseDb,firebaseCollectionEnum.chats,this.conversation_id);await deleteDoc(t);let e=this.conversation_id;this.conversation_id="";deleteMessagesInConversation(e)}catch(e){quasarComponents.notify("red-5",e,"error_outline")}},async createChatOrder(){try{console.log("createChatOrder=>"+order_uuid);console.log("main_user_uuid=>"+main_user_uuid);console.log("merchant_uuid=>"+merchant_uuid);let e={lastUpdated:serverTimestamp(),dateCreated:serverTimestamp(),orderID:order_id,orderUuid:order_uuid,participants:[merchant_uuid,main_user_uuid],isTyping:{[`${merchant_uuid}`]:false,[`${main_user_uuid}`]:false}};console.log(e);await setDoc(doc(firebaseDb,firebaseCollectionEnum.chats,order_uuid),e);console.log("Successful creating docs");this.loadConversation(order_uuid)}catch(e){quasarComponents.notify("red-5",e,"error_outline")}},notifyUser(){console.log("notifyUser");axios({method:"post",url:chat_api+"/notifyUser?language="+chat_language,data:{user_uuid:this.chatting_with_uuid,from_user_uuid:main_user_uuid}}).then(e=>{})["catch"](e=>{}).then(e=>{})}}});app_chat.use(Quasar,{config:{notify:{},loadingBar:{skipHijack:true},loading:{}}});app_chat.mount("#app-chat");