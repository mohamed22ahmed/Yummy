import{initializeApp}from"https://www.gstatic.com/firebasejs/9.9.3/firebase-app.js";import{getFirestore,onSnapshot,collection}from"https://www.gstatic.com/firebasejs/9.9.3/firebase-firestore.js";var empty=function(e){if(typeof e==="undefined"||e==null||e==""||e=="null"||e=="undefined"){return true}return false};let $fc=JSON.parse(firebase_configuration);const firebaseConfig={apiKey:$fc.firebase_apikey,authDomain:$fc.firebase_domain,projectId:$fc.firebase_projectid,storageBucket:$fc.firebase_storagebucket,messagingSenderId:$fc.firebase_messagingid,appId:$fc.firebase_appid};const firebaseCollectionEnum={driver:"drivers",driver_logs:"driver_logs"};const firebaasApp=initializeApp(firebaseConfig);const firebaseDb=getFirestore(firebaasApp);const $timeout=2e4;const $content_type={form:"application/x-www-form-urlencoded; charset=UTF-8",json:"application/json"};const DateTime=luxon.DateTime;const LuxonSettings=luxon.Settings;const MapStyle=[{featureType:"administrative",elementType:"labels.text.fill",stylers:[{color:"#686868"}]},{featureType:"landscape",elementType:"all",stylers:[{color:"#f2f2f2"}]},{featureType:"poi",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"road",elementType:"all",stylers:[{saturation:-100},{lightness:45}]},{featureType:"road.highway",elementType:"all",stylers:[{visibility:"simplified"}]},{featureType:"road.highway",elementType:"geometry.fill",stylers:[{lightness:"-22"}]},{featureType:"road.highway",elementType:"geometry.stroke",stylers:[{saturation:"11"},{lightness:"-51"}]},{featureType:"road.highway",elementType:"labels.text",stylers:[{saturation:"3"},{lightness:"-56"},{weight:"2.20"}]},{featureType:"road.highway",elementType:"labels.text.fill",stylers:[{lightness:"-52"}]},{featureType:"road.highway",elementType:"labels.text.stroke",stylers:[{weight:"6.13"}]},{featureType:"road.highway",elementType:"labels.icon",stylers:[{lightness:"-10"},{gamma:"0.94"},{weight:"1.24"},{saturation:"-100"},{visibility:"off"}]},{featureType:"road.arterial",elementType:"geometry",stylers:[{lightness:"-16"}]},{featureType:"road.arterial",elementType:"labels.text.fill",stylers:[{saturation:"-41"},{lightness:"-41"}]},{featureType:"road.arterial",elementType:"labels.text.stroke",stylers:[{weight:"5.46"}]},{featureType:"road.arterial",elementType:"labels.icon",stylers:[{visibility:"off"}]},{featureType:"road.local",elementType:"geometry.fill",stylers:[{weight:"0.72"},{lightness:"-16"}]},{featureType:"road.local",elementType:"labels.text.fill",stylers:[{lightness:"-37"}]},{featureType:"transit",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"water",elementType:"all",stylers:[{color:"#b7e4f4"},{visibility:"on"}]}];const quasarComponents={empty(e){if(typeof e==="undefined"||e===null||e===""||e==="null"||e==="undefined"){return true}return false},setStorage(e,t){try{Quasar.LocalStorage.set(e,t)}catch(a){console.debug(a)}},getStorage(e){return Quasar.LocalStorage.getItem(e)},notify(e,t,a){const s=Quasar.Notify;s.create({message:t,color:e,icon:a,position:"bottom",html:true,timeout:3e3,multiLine:false,actions:[{noCaps:true,color:"white",handler:()=>{}}]})}};const componentsGroupList={props:["ajax_url","label"],data(){return{data:[]}},created(){this.GroupList()},methods:{GroupList(){axios({method:"put",url:this.ajax_url+"/getGroupList",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content")},timeout:$timeout}).then(e=>{if(e.data.code==1){this.data=e.data.details}})["catch"](e=>{}).then(e=>{})}},template:`    
    <q-btn-dropdown
    split
    label="All Groups"
    outline
    class="q-mr-md"
    no-caps
    > 
    <q-list>
        <q-item clickable v-close-popup>
            <q-item-section>
            <q-item-label>{{label.all_groups}}</q-item-label>
            </q-item-section>              
        </q-item>
        <template v-for="(items,index) in data" >
        <q-item clickable v-close-popup>
            <q-item-section>
            <q-item-label>{{items}}</q-item-label>
            </q-item-section>              
        </q-item>
        </template>
    </q-list>            
    </q-btn-dropdown>
    `};const componentsDriver={props:["ajax_url","task_url","label","status","date_now","driver_q","timezone"],data(){return{data:[],data_drivers:[],total_task:[],loading:false,location_data:{},snap_shot:undefined,watcher:false,zone_list:[],on_demand_availability:false}},created(){this.setLuxonTimezone();this.getDriverBySched();this.timer=setInterval(()=>{this.watchLocationData()},6e4)},computed:{hasData(){if(Object.keys(this.data).length>0){return true}return false}},watch:{location_data(e,t){this.watchLocationData();this.$emit("setLocationdriver",this.location_data)}},methods:{getFirebaseLocation(){if(!empty($fc)){this.snap_shot=onSnapshot(collection(firebaseDb,"drivers"),e=>{this.location_data={};e.forEach(e=>{let t={};t[e.data().driver_id]=e.data();this.location_data[e.data().driver_id]=e.data()})})}},getDriverBySched(){this.loading=true;axios({method:"put",url:this.task_url+"/getDriverBySched",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),status:this.status,q:this.driver_q},timeout:$timeout}).then(e=>{if(e.data.code==1){this.data=e.data.details.data.list;this.data_drivers=e.data.details.driver_data;this.total_task=e.data.details.total_task;this.zone_list=e.data.details.zone_list;this.on_demand_availability=e.data.details.on_demand_availability;this.$emit("setDatamarkerdriver",this.data_drivers)}else{this.data=[];this.date_now=""}})["catch"](e=>{}).then(e=>{this.loading=false;this.getFirebaseLocation()})},setLuxonTimezone(){console.debug("setLuxonTimezone=>"+this.timezone);LuxonSettings.defaultZone=this.timezone},TotalTask(e){if(typeof this.total_task[e]!=="undefined"&&this.total_task[e]!==null){return this.total_task[e]}return 0},refreshList(){this.getDriverBySched()},watchLocationData(){console.debug("watchLocationData xx");if(Object.keys(this.location_data).length>0){Object.entries(this.data_drivers).forEach(([r,o])=>{if(!quasarComponents.empty(this.location_data[r])){let e=this.location_data[r];let t=DateTime.fromISO(e.created_at);let a=DateTime.now();let s=DateTime.fromISO(a);let i=s.diff(t,["hours","minutes"]);const n=i.toObject();console.debug(n);if(n.hours>0){o.online_status=3}else if(n.hours<=-1){o.online_status=3}else{if(n.minutes<=5){o.online_status=1}else if(n.minutes>5&&n.minutes<=10){o.online_status=2}else if(n.minutes>10){o.online_status=3}}}})}},onlineStatus(e){if(this.on_demand_availability){if(e==1){return"orange-5"}else{return"blue-grey-5"}}else{if(e==1){return"orange-5"}else if(e==2){return"blue-grey-5"}else if(e==3){return"grey-4"}else{return"grey-4"}}},onlineStatusBg(e){if(this.on_demand_availability){if(e==1){return"bg-orange-5"}else{return"bg-blue-grey-5"}}else{if(e==1){return"bg-orange-5"}else if(e==2){return"bg-blue-grey-5"}else if(e==3){return"bg-blue-grey-13"}else{return"bg-blue-grey-13"}}},formatTime(e){let t=DateTime.fromISO(e);return t.toFormat("ccc hh:mm a")}},template:`         
    <template v-if="loading">            
        <div class="text-center q-pa-xl flex flex-center">
            <div>
            <q-spinner
            color="orange-5"
            size="3em"
            />
            </div>
        </div>
    </template>

    <template v-else>                        
      <q-list v-if="hasData">      
        <template v-for="items in data_drivers">
        <q-item clickable  @click.stop="$emit('showDriverinfo',items.driver_id)" >
        <q-item-section side>            
            <template v-if="items.photo">            
               <q-avatar>                                    
                    <img :src="items.photo_url"> 
                        <q-tooltip anchor="center left" self="center right" :offset="[10, 10]" :class="onlineStatusBg(items.online_status)" >
                        <template v-if="on_demand_availability">
                            <span class="font-12">
                            <template v-if="items.is_online==1">
                                Online
                            </template>
                            <template v-else>
                                Offline
                            </template>
                            </span>
                        </template>
                        <template v-else>                        
                            <span class="font-12">
                            <template v-if="items.online_status==1">
                                Online
                            </template>
                            <template v-else-if="items.online_status==2">
                                Idle
                            </template>
                            <template v-else-if="items.online_status==3">
                                Offline
                            </template>
                            </span>
                        </template>
                        </q-tooltip>
                    </img>                    
                    <q-badge floating :color="onlineStatus(items.online_status)" rounded />                         
                </q-avatar>
            </template>
            <template v-else>
            <q-avatar color="primary" text-color="white">
                {{items.initial}}               
                <q-badge floating :color="onlineStatus(items.online_status)"  rounded />

                <q-tooltip anchor="center left" self="center right" :offset="[10, 10]" :class="onlineStatusBg(items.online_status)" >
                <template v-if="on_demand_availability">
                    <span class="font-12">
                    <template v-if="items.is_online==1">
                        Online
                    </template>
                    <template v-else>
                        Offline
                    </template>
                    </span>
                </template>
                <template v-else>
                    <span class="font-12">
                        <template v-if="items.online_status==1">
                        Online
                        </template>
                        <template v-else-if="items.online_status==2">
                        Idle
                        </template>
                        <template v-else-if="items.online_status==3">
                        Offline
                        </template>
                    </span>
                </template>
                </q-tooltip>

            </q-avatar>
            </template>
        </q-item-section>
        <q-item-section class="cursor-pointer">                       
            <p class="no-margin text-weight-bold">
             {{items.first_name}} {{items.last_name}}
            </p>
            <div class="no-margin text-grey ellipsis" style="max-width: 230px;">

            +{{items.phone_prefix}} {{items.phone}}
            
            <div v-if="data[items.driver_id]" class="font-12" >Zone: <span class="q-mr-xs">            
            {{zone_list[data[items.driver_id].zone_id]}}            
            </span></div>

            <template v-if="!on_demand_availability">
            <p v-if="data[items.driver_id]" class="font-12 no-margin">Shift at {{data[items.driver_id].time_start}} to {{data[items.driver_id].time_end}}</p> 
            <p v-if="data[items.driver_id]" class="font-12 no-margin">
             <template v-if="data[items.driver_id].shift_time_started">
             Started shift at {{data[items.driver_id].shift_time_started}}
             </template>
            </p>
            </template>
            
            <template v-if="items.online_status!='1'">
            <p v-if="location_data[items.driver_id]" class="font-12 no-margin">last seem at {{formatTime(location_data[items.driver_id].created_at)}} </p>         
            </template>

            <q-badge color="lime">
              <q-icon name="description" color="white" class="q-mr-xs" />
               {{data[items.driver_id].instructions}}
            </q-badge>  

            </div>
        </q-item-section>                    
        <q-item-section side>
            <div class="text-center">            
            <q-btn round color="grey" unelevated outline >
             <span class="text-weight-bold text-blue">
                {{TotalTask(items.driver_id)}}
             </span>             
             
             <q-tooltip anchor="center left" self="center right" :offset="[10, 10]" class="bg-orange-5" >
                <span class="font-14">Task : {{TotalTask(items.driver_id)}}</span>
             </q-tooltip>

            </q-btn>
            <p class="no-margin">Task</p>
            </div>
            <q-btn  @click.stop="$emit('locationFocus',items.driver_id)"  round color="grey" flat icon="chevron_right" unelevated ></q-btn>                      
        </q-item-section> 
        </q-item>
        <q-separator spaced  />
        </template>
      </q-list>

      <template v-else>
       <div class="text-center q-pa-md flex flex-center">
            <div>
            <div class="text-h6 text-grey-4 q-mb-sm">
                No Data Available 
            </div>            
            </div>
        </div>
      </template>

    </template>
    `};const loader={methods:{show(){const e=Quasar;e.Loading.show({boxClass:"bg-grey-2 text-grey-9",spinnerColor:"primary",spinnerSize:"50",backgroundColor:"white"})},hide(){const e=Quasar;e.Loading.hide()}}};const componentsOrders={props:["ajax_url","task_url","label","status","tab","order_q"],data(){return{data:[],merchant:[],order_status:[],status_new:[],loading:false,reload:false,drivers_data:[],orders_location:[],merchant_zone:[],zone_list:[]}},mounted(){this.getOrders()},computed:{hasData(){if(Object.keys(this.data).length>0){return true}return false}},methods:{getOrders(t){if(t){this.reload=true}else{this.loading=true}axios({method:"put",url:this.task_url+"/getOrders",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),status:this.status,q:this.order_q},timeout:$timeout}).then(e=>{if(e.data.code==1){this.data=e.data.details.data;this.orders_location=e.data.details.orders_location;this.merchant=e.data.details.merchant_list;this.order_status=e.data.details.order_status;this.status_new=e.data.details.status_new;this.drivers_data=e.data.details.drivers_data;this.merchant_zone=e.data.details.merchant_zone;this.zone_list=e.data.details.zone_list}else{this.data=[];this.merchant_zone=[];this.zone_list=[]}})["catch"](e=>{}).then(e=>{this.$emit("afterGetorders");if(t){this.reload=false}else{this.loading=false}})},hasAccepted(e){if(this.status_new.includes(e)===false){return true}return false}},template:`          
    <template v-if="loading">            
        <div class="text-center q-pa-xl flex flex-center">
            <div>
            <q-spinner
            color="orange-5"
            size="3em"
            />
            </div>
        </div>
    </template>

    <template v-else>

    <template v-if="hasData">      
    <q-list>
       <template v-for="items in data">
       <q-item clickable @click.stop="$emit('viewDetails',items.order_uuid,items.order_id)">            
            <q-item-section side>                     
                <div class="text-center">
                
                <template v-if="drivers_data[items.driver_id]">
                    <q-avatar>
                       <img :src="drivers_data[items.driver_id].photo_url">
                    </q-avatar>
                    <p class="no-margin ellipsis">{{drivers_data[items.driver_id].first_name}}</p>
                </template>
                <template v-else>
                    <q-btn @click.stop="$emit('showAssign',items.order_uuid)" round color="blue" icon="add" unelevated size="md" ></q-btn>
                    <p class="no-margin ellipsis">Assign</p>                    
                </template>

                </div>
            </q-item-section>

            <q-item-section>
               <div class="row justify-center items-center">
                 <div class="col text-weight-bold">Order #{{items.order_id}}</div>
                 <div class="col text-right">
                 
                 <q-badge :style="'background:'+ this.order_status[items.delivery_status].bg_color+';'" text-color="white" rounded class="q-pa-sm" :class="'custom_'+items.delivery_status"  > 
                   <span class="ellipsis max-120">{{order_status[items.delivery_status].label}}</span>
                 </q-badge>                 

                 </div>
               </div>
               
               <q-timeline color="secondary" side="right" layout="dense" >                                
               <q-timeline-entry
                    :title="merchant[items.merchant_id].restaurant_name"
                    :subtitle="items.date_created"    
                    color="orange-3" 
                    v-if="merchant[items.merchant_id]"
                >
                    <div class="text-grey ellipsis font-12" style="max-width: 220px;"  >                      
                        {{ merchant[items.merchant_id].address }}                      
                    </div>
                    <div class="text-weight-medium text-grey ellipsis font-12">
                      Zone :                                                    
                      <template v-for="zoneid in merchant_zone[items.merchant_id]" :key="zoneid">
                         <span class="q-mr-xs">{{zone_list[zoneid]}},</span>                      
                      </template>               
                    </div>
                </q-timeline-entry>

                <q-timeline-entry
                    :title="items.customer_name"
                    :subtitle="items.delivery_time"     
                    color="orange-5"                                
                >
                    <div class="text-grey ellipsis font-12" style="max-width: 220px;" >
                    {{items.formatted_address}}
                    </div>
                </q-timeline-entry>

               </q-timeline>               
                                             
               <div class="q-mt-sm q-gutter-sm items-center">                 
                 
                 <template v-if="hasAccepted(items.status)">
                   <q-badge outline color="green-5" :label="order_status[items.status]?order_status[items.status].label:items.status" ></q-badge>
                 </template>
                 <template v-else>
                    <q-badge outline color="orange-5" :label="order_status[items.status]?order_status[items.status].label:items.status" ></q-badge>
                 </template>

                 <q-badge outline :color="items.payment_status_raw=='unpaid'?'red-5':'green-5'" :label="items.payment_status" ></q-badge>
                                       
                 <q-badge v-if="items.is_delayed" color="red-2" text-color="red-5"  >
                   <q-icon name="schedule" color="red-5" class="q-mr-xs" ></q-icon>
                   <span class="font-12">DELAYED</span>
                 </q-badge>

               </div>               

            </q-item-section>                    
            
            
            <q-item-section side>            
                <q-btn @click.stop="$emit('locationFocus',items.order_id)" round color="grey" flat icon="chevron_right" unelevated ></q-btn>
            </q-item-section>             
            
                        

        </q-item>
        <q-separator ></q-separator >
        </template>
    </q-list>
    </template>     
    <template v-else>
    
    <div class="text-center q-pa-md flex flex-center">
        <div>
        <div class="text-h6 text-grey-4 q-mb-sm">
            No Data Available 
        </div>            
        </div>
    </div>

    </template>

    </template> <!-- end if loading -->

    <q-inner-loading
        :showing="reload"        
        color="orange-5"
        label-style="font-size: 1.1em"
    />
    `};const componentsLocationOnMap={props:["markers","center","zoom","maps_config"],data(){return{modal:false,bounds:[],cmaps:undefined,cmapsMarker:[]}},methods:{renderMap(){if(this.maps_config.provider=="google.maps"){this.bounds=new window.google.maps.LatLngBounds;this.cmaps=new window.google.maps.Map(this.$refs.maploc,{center:{lat:parseFloat(this.center.lat),lng:parseFloat(this.center.lng)},zoom:parseInt(this.zoom),disableDefaultUI:false,styles:MapStyle})}else{this.bounds=new mapboxgl.LngLatBounds;mapboxgl.accessToken=this.maps_config.key;this.cmaps=new mapboxgl.Map({container:this.$refs.maploc,style:"mapbox://styles/mapbox/streets-v12",center:[parseFloat(this.center.lng),parseFloat(this.center.lat)],zoom:14});this.cmaps.on("error",e=>{alert(e.error.message)});this.mapBoxResize()}this.instantiateDriverMarker()},instantiateDriverMarker(){Object.entries(this.markers).forEach(([e,t])=>{let a="";if(this.maps_config.provider=="google.maps"){a={path:google.maps.SymbolPath.CIRCLE,scale:8,strokeColor:"#f44336"}}else{a="marker_icon_driver"}let s={lat:parseFloat(t.lat),lng:parseFloat(t.lng)};let i=t.info;this.addMarker({position:s,map:this.cmaps,icon:a,label:this.getIcon("driver"),info:i},t.index)});this.FitBounds()},getIcon(e){let t=[];t["driver"]={text:"",fontFamily:"Material Icons",color:"#ffffff",fontSize:"17px"};t["merchant"]={text:"",fontFamily:"Material Icons",color:"#ffffff",fontSize:"17px"};t["customer"]={text:"",fontFamily:"Material Icons",color:"#ffffff",fontSize:"17px"};return t[e]},addMarker(a,s){switch(this.maps_config.provider){case"google.maps":this.cmapsMarker[s]=new window.google.maps.Marker(a);this.cmaps.panTo(new window.google.maps.LatLng(a.position.lat,a.position.lng));this.bounds.extend(this.cmapsMarker[s].position);const i=new google.maps.InfoWindow({content:a.info});let e=this.cmaps;let t=this.cmapsMarker[s];t.addListener("click",()=>{i.open({anchor:t,cmaps:e,shouldFocus:false})});break;case"mapbox":const r=new mapboxgl.Popup({offset:25}).setHTML(a.info);const o=document.createElement("div");o.className=a.icon;this.cmapsMarker[s]=new mapboxgl.Marker(o).setLngLat([a.position.lng,a.position.lat]).setPopup(r).addTo(this.cmaps);this.bounds.extend(new mapboxgl.LngLat(a.position.lng,a.position.lat));break}},FitBounds(){if(this.maps_config.provider=="google.maps"){try{this.cmaps.fitBounds(this.bounds)}catch(e){console.error(e)}}else{this.cmaps.fitBounds(this.bounds)}},mapBoxResize(){if(this.maps_config.provider=="mapbox"){setTimeout(()=>{this.cmaps.resize()},500)}}},template:`
    <q-dialog v-model="modal" class="q-mb-md" persistent @show="renderMap" >
       <q-card style="width: 500px; max-width: 80vw;"  class="b_radius15" >

       <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Location On Map</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
       </q-card-section>

       <q-card-section class="q-gutter-md">          
         <div ref="maploc" class="bg-grey-1 map small"></div>
       </q-card-section>

       </q-card>        
    </q-dialog>
    `};const componentsCarousel={props:["data"],data(){return{modal:false,slide:0}},computed:{hasData(){if(this.data.length>0){return true}return false}},template:`
    <q-dialog v-model="modal" class="q-mb-md" persistent >
       <q-card style="width: 500px; max-width: 80vw;"  class="b_radius15" >

       <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Photos</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
       </q-card-section>

       <q-card-section class="q-gutter-md">          
    
        <template v-if="hasData">
        <q-carousel
            animated
            v-model="slide"
            arrows
            navigation                        
            >
            <template v-for="(item, index) in data">            
            <q-carousel-slide :name="index" :img-src="item" ></q-carousel-slide>
            </template>
        </q-carousel>
        </template>
        <template v-else>
           <div class="text-center q-pa-md flex flex-center fit">
                <div>
                <div class="text-h6 text-grey-4 q-mb-sm">
                    No Data Available 
                </div>            
                </div>
            </div>
        </template>

       </q-card-section>

       </q-card>        
    </q-dialog>
    `};const componentsDetails={props:["order_uuid","ajax_url","task_url","apibackend"],components:{"components-locationonmap":componentsLocationOnMap,"components-carousel":componentsCarousel},data(){return{loading_order:true,loading_assign:true,tab_details:"order_details",data_order:[],modal_assign:false,group_id:"",group_list:[],group_selected:0,driver_id:"",driver_list:[],driver_info:[],vehicle_info:[],customer_info:[],modal_change_status:false,delivery_status:"",delivery_status_list:[],modal_update_info:false,contact_number:"",customer_name:"",delivery_address:"",latitude:"",longitude:"",order_info:[],order_history:[],order_history_status:[],delivery_status_data:[],location_data:[],order_proof:[],delivery_status:""}},computed:{hasDriver(){if(Object.keys(this.driver_info).length>0){return true}return false},hasVehicle(){if(Object.keys(this.vehicle_info).length>0){return true}return false},hasCustomerInfo(){if(Object.keys(this.customer_info).length>0){return true}return false},hasTimeline(){if(Object.keys(this.order_history).length>0){return true}return false},hasProof(){if(Object.keys(this.order_proof).length>0){return true}return false}},updated(){},created(){this.DriverInformation(0);this.getAttributeStatus();this.getOrderHistory()},methods:{initDetails(){this.getOrderDetails()},getOrderDetails(){this.loading_order=true;axios({method:"put",url:this.ajax_url+"/orderDetails",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:this.order_uuid,payload:[]},timeout:$timeout}).then(e=>{if(e.data.code==1){this.data_order=e.data.details.data.order;this.customer_info=e.data.details.data.customer;this.order_info=e.data.details.data.order.order_info;this.customer_name=this.order_info.customer_name;this.contact_number=this.order_info.contact_number;this.delivery_address=this.order_info.delivery_address;this.latitude=this.order_info.latitude;this.longitude=this.order_info.longitude;this.delivery_status=this.order_info.delivery_status}})["catch"](e=>{}).then(e=>{this.loading_order=false})},getDriverGroups(){axios({method:"put",url:this.task_url+"/getDriverGroups",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content")},timeout:$timeout}).then(e=>{if(e.data.code==1){this.group_list=e.data.details.groups}})["catch"](e=>{}).then(e=>{})},DriverInformation(e){axios({method:"put",url:this.task_url+"/DriverInformation",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),driver_id:this.driver_id,order_uuid:this.order_uuid},timeout:$timeout}).then(e=>{if(e.data.code==1){this.driver_info=e.data.details.driver_info;this.vehicle_info=e.data.details.vehicle_info}else{this.driver_info=[];this.vehicle_info=[]}})["catch"](e=>{}).then(e=>{})},getAttributeStatus(){axios({method:"put",url:this.task_url+"/getAttributeStatus",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content")},timeout:$timeout}).then(e=>{if(e.data.code==1){this.delivery_status_list=e.data.details.delivery_status_list;this.delivery_status_data=e.data.details.delivery_status_data}else{this.delivery_status_list=[];this.delivery_status_data=[]}})["catch"](e=>{}).then(e=>{})},showAssign(){this.$refs.pop_menu.hide();this.$emit("showAssign",this.order_uuid)},showChangeStatus(){this.$refs.pop_menu.hide();this.$emit("showChangestatus",this.order_uuid)},showUpdateInfo(){this.$refs.pop_menu.hide();this.modal_update_info=!this.modal_update_info},UpdateDeliveryInformation(){axios({method:"put",url:this.apibackend+"/updateOrderDeliveryInformation",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:this.order_uuid,contact_number:this.contact_number,customer_name:this.customer_name,delivery_address:this.delivery_address,latitude:this.latitude,longitude:this.longitude},timeout:$timeout}).then(e=>{if(e.data.code==1){this.modal_update_info=false;quasarComponents.notify("green-5",e.data.msg,"done")}else{quasarComponents.notify("red-5",e.data.msg,"error_outline")}})["catch"](e=>{}).then(e=>{})},getOrderHistory(){axios({method:"put",url:this.ajax_url+"/getOrderHistory",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:this.order_uuid},timeout:$timeout}).then(e=>{if(e.data.code==1){this.order_history=e.data.details.data;this.order_history_status=e.data.details.order_status;this.order_proof=e.data.details.order_proof;this.delivery_status=e.data.details.delivery_status}})["catch"](e=>{this.order_history=[];this.order_history_status=[];this.order_proof=[];this.delivery_status=""}).then(e=>{})},locationOnMap(e){this.location_data=[];this.location_data.push({index:1,lat:e.latitude,lng:e.longitude,icon:"driver",info:e.status});this.$refs.location_on_map.modal=true},showLicensePhoto(){console.debug("showLicensePhoto");this.$refs.carousel.modal=true}},template:"#xtemplate_order_details"};const componentsAssign={props:["order_uuid","task_url"],template:"#xtemplate_assign",data(){return{group_id:"",driver_id:"",group_selected:0,driver_list:[],group_list:[],loading_assign:false,merchant_id:0,zone_id:"",zone_list:[]}},created(){this.getZone();this.getDriverList()},methods:{getZone(){axios({method:"post",url:this.task_url+"/getZoneList",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content")},timeout:$timeout}).then(e=>{if(e.data.code==1){this.zone_list=e.data.details}else{this.zone_list=[]}})["catch"](e=>{}).then(e=>{})},filterZone(e,t,a){axios({method:"put",url:this.task_url+"/getZoneList",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),q:e},timeout:$timeout}).then(e=>{if(e.data.code==1){t(()=>{this.zone_list=e.data.details})}else{t(()=>{this.zone_list=[]})}})["catch"](e=>{}).then(e=>{})},afterSelectZone(e){this.driver_id="";this.driver_list=[];this.getDriverList()},getDriverList(){axios({method:"put",url:this.task_url+"/getDriverList",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),group_selected:this.group_selected,merchant_id:this.merchant_id,order_uuid:this.order_uuid,zone_id:this.zone_id},timeout:$timeout}).then(e=>{if(e.data.code==1){this.driver_list=e.data.details}})["catch"](e=>{}).then(e=>{})},filterDriver(e,t,a){axios({method:"put",url:this.task_url+"/getDriverList",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),q:e,group_selected:this.group_id,merchant_id:this.merchant_id,order_uuid:this.order_uuid,zone_id:this.zone_id},timeout:$timeout}).then(e=>{if(e.data.code==1){t(()=>{this.driver_list=e.data.details})}else{t(()=>{this.driver_list=[]})}})["catch"](e=>{}).then(e=>{})},filterGroup(e,t,a){axios({method:"put",url:this.task_url+"/getDriverGroups",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),q:e,merchant_id:this.merchant_id},timeout:$timeout}).then(e=>{if(e.data.code==1){t(()=>{this.group_list=e.data.details.groups})}else{t(()=>{this.group_list=[]})}})["catch"](e=>{}).then(e=>{})},afterSelectGroup(e){this.group_selected=e;this.driver_id="";this.driver_list=[];this.getDriverList()},AssignDriver(){this.loading_assign=true;axios({method:"put",url:this.task_url+"/AssignDriver",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),driver_id:this.driver_id,order_uuid:this.order_uuid},timeout:$timeout}).then(e=>{if(e.data.code==1){this.$emit("afterAssign",this.driver_id)}else{quasarComponents.notify("red-5",e.data.msg,"error_outline")}})["catch"](e=>{}).then(e=>{this.loading_assign=false})}}};const componentsChangeStatus={props:["order_uuid","task_url"],template:"#xtemplate_change_status",data(){return{delivery_status:"",delivery_status_list:[],delivery_status_data:[],order_delivery_status:""}},created(){this.getAttributeStatus()},methods:{getAttributeStatus(){axios({method:"put",url:this.task_url+"/getAttributeStatus",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:this.order_uuid},timeout:$timeout}).then(e=>{if(e.data.code==1){this.delivery_status_list=e.data.details.delivery_status_list;this.delivery_status_data=e.data.details.delivery_status_data;this.order_delivery_status=e.data.details.delivery_status}else{this.delivery_status_list=[];this.delivery_status_data=[];this.order_delivery_status=""}})["catch"](e=>{}).then(e=>{})},changeStatus(){axios({method:"put",url:this.task_url+"/changeStatus",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),delivery_status:this.delivery_status,order_uuid:this.order_uuid},timeout:$timeout}).then(e=>{if(e.data.code==1){this.modal_change_status=false;this.$emit("afterChangestatus");quasarComponents.notify("green-5",e.data.msg,"done")}else{quasarComponents.notify("red-5",e.data.msg,"error_outline")}})["catch"](e=>{}).then(e=>{})}}};const componentsOrdersTotal={props:["task_url","order_q"],data(){return{data:[]}},created(){this.getOrderTotal()},methods:{getOrderTotal(){axios({method:"put",url:this.task_url+"/getOrderTotal",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),q:this.order_q},timeout:$timeout}).then(e=>{if(e.data.code==1){this.$emit("afterTotal",e.data.details)}})["catch"](e=>{}).then(e=>{})}}};const componentsDriverInformation={props:["driver_id","task_url","date_now"],template:"#xtemplate_driver_information",components:{"components-locationonmap":componentsLocationOnMap,"components-carousel":componentsCarousel,"components-carousel-activity":componentsCarousel},data(){return{tabs:"details",loading:true,loading_orders:true,loading_activities:true,driver_info:[],driver_info:[],orders_data:[],columns:[{name:"order_id",align:"left",label:"Order ID",field:"order_id",sortable:false},{name:"full_name",align:"left",label:"Customer",field:"full_name",sortable:false},{name:"address",align:"left",label:"Address",field:"address",sortable:false},{name:"delivery_status",align:"left",label:"Status",field:"delivery_status",sortable:false}],loadin_activity:true,data_activity:[],order_status:[],location_data:[],meta_activity:[],carousel_data:[],activity_photo:[]}},computed:{hasActivity(){if(Object.keys(this.data_activity).length>0){return true}return false}},methods:{InitData(){this.DriverInformation();this.getDriverOrdersList();this.getDriverActivity()},DriverInformation(){this.loading=true;axios({method:"put",url:this.task_url+"/GetDriverInfo",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),driver_id:this.driver_id},timeout:$timeout}).then(e=>{if(e.data.code==1){this.driver_info=e.data.details.driver_info}else{this.driver_info=[]}})["catch"](e=>{}).then(e=>{this.loading=false})},getDriverOrdersList(){this.loading_orders=true;axios({method:"put",url:this.task_url+"/getDriverOrdersList",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),driver_id:this.driver_id,date_now:this.date_now},timeout:$timeout}).then(e=>{if(e.data.code==1){this.orders_data=e.data.details.data;this.order_status=e.data.details.order_status}else{this.orders_data=[];this.order_status=[]}})["catch"](e=>{}).then(e=>{this.loading_orders=false})},getDriverActivity(){this.loadin_activity=true;axios({method:"put",url:this.task_url+"/getDriverActivity",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),driver_id:this.driver_id,date_now:this.date_now},timeout:$timeout}).then(e=>{if(e.data.code==1){this.data_activity=e.data.details.data;this.meta_activity=e.data.details.meta}else{this.data_activity=[];this.meta_activity=[]}})["catch"](e=>{}).then(e=>{this.loadin_activity=false})},locationOnMap(e){this.location_data=[];this.location_data.push({index:1,lat:e.latitude,lng:e.longitude,icon:"driver",info:e.status});this.$refs.location_on_map.modal=true},showCarousel(e){this.carousel_data=e;this.$refs.carousel.modal=true},showActivityPhoto(e){this.activity_photo=[];if(Object.keys(e).length>0){Object.entries(e).forEach(([e,t])=>{this.activity_photo.push(t.document)})}this.$refs.carousel_activity.modal=true}}};const ApiCalls={async getTotalOrders(e,t,a){let s="YII_CSRF_TOKEN="+$("meta[name=YII_CSRF_TOKEN]").attr("content");s+="&lenght="+t;s+="&q="+a;return axios.post(e+"/getTotalOrders",s).then(e=>{if(e.data.code===1){return e.data}else{throw e.data.msg}})["catch"](e=>{throw e})}};const componentsOrderList={props:["task_url","lenght","sortby","q"],data(){return{loading:false,columns:[{name:"order_id",align:"left",label:"Order ID",field:"order_id",sortable:true},{name:"client_id",align:"left",label:"Customer",field:"client_id",sortable:true},{name:"formatted_address",align:"left",label:"Address",field:"formatted_address",sortable:true},{name:"delivery_date",align:"left",label:"Delivery Date",field:"delivery_date",sortable:true},{name:"driver_id",align:"left",label:"Driver",field:"driver_id",sortable:true},{name:"rating",align:"left",label:"Rating",field:"rating",sortable:false},{name:"delivery_status",align:"left",label:"Status",field:"delivery_status",sortable:true}],data:[],driver_data:[],client_data:[],delivery_status_list:[],filter:"",pagination:{sortBy:this.sortby,descending:false,page:1,rowsNumber:0,rowsPerPage:this.lenght}}},created(){this.getOrderList({pagination:this.pagination,filter:undefined})},methods:{filterOrderList(e){console.debug(e);if(typeof this.q!=="undefined"&&this.q!==null){this.getOrderList({pagination:this.pagination,filter:undefined},e)}},getOrderList(e,s){const{page:i,rowsPerPage:r,rowsNumber:o,sortBy:n,descending:l}=e.pagination;const t=e.filter;this.loading=true;ApiCalls.getTotalOrders(this.task_url,this.lenght,s).then(e=>{this.pagination.rowsNumber=e.details;const t=r===0?this.pagination.rowsNumber:r;const a=(i-1)*r;axios({method:"put",url:this.task_url+"/getOrderList",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),descending:l,page:a,rowsNumber:o,rowsPerPage:r,sortBy:n,q:s},timeout:$timeout}).then(e=>{if(e.data.code==1){this.data=e.data.details.data;this.driver_data=e.data.details.driver_data;this.client_data=e.data.details.client_data;this.delivery_status_list=e.data.details.delivery_status_list}else{this.data=[];this.driver_data=[];this.client_data=[];this.delivery_status_list=[]}})["catch"](e=>{}).then(e=>{this.loading=false;this.pagination.page=i;this.pagination.rowsPerPage=r;this.pagination.sortBy=n;this.pagination.descending=l;this.$emit("afterSearch")})})["catch"](e=>{this.loading=false;this.$emit("afterSearch")}).then(e=>{})}},template:`    
    <div></div>
    <q-table            
    :rows="data"
    :columns="columns"
    row-key="name"
    :loading="loading"        
    v-model:pagination="pagination"
    @request="getOrderList"
    binary-state-sort
    >
    <template v-slot:body="props">
     <q-tr :props="props">
       <q-td key="order_id" :props="props">        
        <q-btn flat color="primary"  :label="props.row.order_id"></q-btn>
       </q-td>       
       <q-td key="client_id" :props="props">
       <q-avatar v-if="client_data[props.row.client_id]" size="30px" class="q-mr-xs">
            <img :src="client_data[props.row.client_id].photo_url">
        </q-avatar>
        {{ props.row.customer_name }}
       </q-td>
       <q-td key="formatted_address" :props="props">
          <div class="ellipsis" style="max-width:150px;">{{ props.row.formatted_address }}</div>
       </q-td>
       <q-td key="delivery_date" :props="props">
        {{ props.row.delivery_date }}
       </q-td>
       <q-td key="driver_id" :props="props">
        <template v-if="driver_data[props.row.driver_id]">        
        <q-avatar size="30px" class="q-mr-xs">
            <img :src="driver_data[props.row.driver_id].photo_url">
        </q-avatar>
        {{ driver_data[props.row.driver_id].first_name }} {{ driver_data[props.row.driver_id].last_name }}
        </template>
        <template v-else>
        -
        </template>
       </q-td>
       <q-td key="rating" :props="props">
            <q-rating
            v-model="ratingModel"
            size="18px"
            :max="1"
            color="orange"
            readonly
            />        
            {{ props.row.rating }}
       </q-td>
       <q-td key="delivery_status" :props="props">
          <q-badge :style="'background:'+ this.delivery_status_list[props.row.delivery_status].bg_color+';'"  
          text-color="white" rounded class="q-pa-sm" :class="'custom_'+props.row.delivery_status_raw"  > 
            {{ delivery_status_list[props.row.delivery_status].label }}            
          </q-badge>   
       </q-td>
     </q-tr>
    </template>

    <template v-slot:no-data="{ icon, message, filter }">
        <div class="text-center q-pa-md flex flex-center fit">
            <div>
            <div class="text-h6 text-grey-4 q-mb-sm">
                No Data Available 
            </div>            
            </div>
        </div>
    </template>

    </q-table>
    `};const componentsNotifications={props:["ajax_url","view_url","realtime"],data(){return{data:[],count:0,new_message:false,player:undefined,ably:undefined,channel:undefined,piesocket:undefined}},created(){this.getAllNotification();if(this.realtime.enabled){this.initRealTime()}},computed:{hasData(){if(this.data.length>0){return true}return false},ReceiveMessage(){if(this.new_message){return true}return false}},methods:{initRealTime(){if(this.realtime.provider=="pusher"){Pusher.logToConsole=false;var e=new Pusher(this.realtime.key,{cluster:this.realtime.cluster});var t=e.subscribe(this.realtime.channel);t.bind(this.realtime.event,e=>{console.debug("receive pusher");console.debug(e);console.debug(e.notification_type);if(e.notification_type=="silent"){this.$emit("afterReceivenotifications")}else{this.playAlert();this.addData(e)}})}else if(this.realtime.provider=="ably"){this.ably=new Ably.Realtime(this.realtime.ably_apikey);this.ably.connection.on("connected",()=>{this.channel=this.ably.channels.get(this.realtime.channel);this.channel.subscribe(this.realtime.event,e=>{console.debug("receive ably");console.debug(e.data);this.playAlert();this.addData(e.data)})})}else if(this.realtime.provider=="piesocket"){this.piesocket=new PieSocket({clusterId:this.realtime.piesocket_clusterid,apiKey:this.realtime.piesocket_api_key});this.channel=this.piesocket.subscribe(this.realtime.channel);this.channel.listen(this.realtime.event,e=>{console.debug("receive piesocket");console.debug(e);this.playAlert();this.addData(e)})}},playAlert(){this.player=new Howl({src:["../assets/sound/notify.mp3","../assets/sound/notify.ogg"],html5:true});this.player.play()},getAllNotification(){axios({method:"POST",url:this.ajax_url+"/getNotifications",data:"YII_CSRF_TOKEN="+$("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:$timeout}).then(e=>{if(e.data.code==1){this.data=e.data.details.data;this.count=e.data.details.count}else{this.data=[];this.count=0}})["catch"](e=>{}).then(e=>{})},addData(e){this.data.unshift(e);this.count++;this.new_message=true;setTimeout(()=>{this.new_message=false},1e3);this.$emit("afterReceivenotifications")},clearAll(){axios({method:"POST",url:this.ajax_url+"/clearNotifications",data:"YII_CSRF_TOKEN="+$("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:$timeout}).then(e=>{if(e.data.code==1){this.data=[];this.count=0}else{quasarComponents.notify("red-5",e.data.msg,"error_outline")}this.new_message=false})["catch"](e=>{}).then(e=>{})}},template:`
    <q-btn round icon="notifications" size="sm" class="q-mr-sm" >
    <q-popup-proxy>
      <q-banner>     
      <q-scroll-area style="height: 350px; width: 350px;">    

          <div class="row items-center justify-between q-pt-sm">
            <div class="col">
             <span class="text-weight-bold">Notifications</span> <q-badge rounded color="teal-4" :label="count" />
            </div>
            <div class="col text-right" >
               <q-btn @click="clearAll" flat color="dark" label="Clear all" no-caps ></q-btn>
            </div>
          </div>          

          <q-separator class="q-mb-sm q-mt-sm" ></q-separator>

          <template v-if="hasData"> 
          <q-list>
          
            <template v-for="item in data">
            <q-item clickable v-ripple>

                <q-item-section avatar>
                <template v-if="item.image!=''" >
                   <template v-if="item.image_type=='icon'">  
                       <q-avatar color="primary" text-color="white" icon="directions" ></q-avatar> 
                   </template>
                   <template v-else >
                       <q-avatar>
                         <img :src="item.image"  >
                       </q-avatar>
                   </template>
                </template>
                </q-item-section>

                <q-item-section >                
                <q-item-label lines="2">
                    <div class="text-heading font-13" v-html="item.message"></div>
                </q-item-label>
                </q-item-section>

                <q-item-section side top class="font-13">
                {{item.date}}
                </q-item-section>
            </q-item>
            <q-separator inset="item" ></q-separator>
            </template>

          </q-list>
          </template>
          <template v-else>
          
           <div class="text-center q-pa-md flex flex-center">
                <div>
                <div class="text-h6 text-grey-4 q-mb-sm">
                   <div><q-icon name="notifications" size="40px" ></q-icon></div>
                   <div>No Data Available </div>
                </div>            
                </div>
            </div>

          </template>
          </q-scroll-area>
          
          <div class="text-center q-pa-sm flex flex-center">             
              <q-btn :href="view_url" flat color="primary" label="View all" no-caps ></q-btn>
          </div>

      </q-banner>
     </q-popup-proxy>
       <q-badge v-if="count>0" floating color="red" rounded></q-badge>
    </q-btn> 
    `};const componentsAllOrder={props:["task_url","status"],data(){return{loading:false,data:[],orders_location:[],order_status:[],merchant_list:[]}},created(){this.getOrders()},methods:{getOrders(){axios({method:"put",url:this.task_url+"/getOrders",data:{YII_CSRF_TOKEN:$("meta[name=YII_CSRF_TOKEN]").attr("content"),status:this.status},timeout:$timeout}).then(e=>{if(e.data.code==1){this.data=e.data.details.data;this.orders_location=e.data.details.orders_location;this.order_status=e.data.details.order_status;this.merchant_list=e.data.details.merchant_list;const o=[];Object.entries(this.data).forEach(([e,t])=>{const a={lat:parseFloat(this.orders_location[t.order_id].latitude),lng:parseFloat(this.orders_location[t.order_id].longitude)};let s="";let i="000";if(!quasarComponents.empty(this.order_status[t.delivery_status])){s=this.order_status[t.delivery_status].label;i=this.order_status[t.delivery_status].bg_color_raw}const r='<div class="row inline">'+'<div class="column q-mr-sm inline text-weight-medium">#'+t.order_id+"</div>"+'<div class="column inline text-weight-bold">'+s+"</div>"+"</div>"+'<p class="q-mt-xs font-12">'+t.formatted_address+"</p>"+"";o.push({position:a,info:r,index:t.order_id,bg_color:i})});this.$emit("setDatamarker",o)}else{this.data=[];this.$emit("setDatamarker",[])}})["catch"](e=>{}).then(e=>{})}}};const cmapsMarker=[];let bounds=[];let markers=[];let markerCluster;let yandex_map;let yandex_zoom=16.6;let yandex_bounds=[];let yandex_markers=[];const componentsMap={props:["maps_config","marker","driver_markers","driver_locations","marker_focus","enabled_cluster","drawer_left","drawer_right"],data(){return{cmaps:undefined}},mounted(){this.renderMap()},watch:{marker(e,t){if(Object.keys(e).length>0){this.instantiateMarker()}},driver_markers(e,t){if(Object.keys(e).length>0){this.instantiateDriverMarker()}},driver_locations(e,t){if(Object.keys(e).length>0){this.moveDriverMarkers()}},marker_focus(a,e){if(!quasarComponents.empty(cmapsMarker[a])){let t=cmapsMarker[a];if(this.maps_config.provider=="google.maps"){this.cmaps.panTo(cmapsMarker[a].getPosition());this.cmaps.setZoom(18);let e=this.cmaps;cmapsMarker[a].infowindow.open({anchor:t,cmaps:e,shouldFocus:true})}else if(this.maps_config.provider=="mapbox"){let e=t.getLngLat();this.cmaps.flyTo({center:e,speed:.9})}else if(this.maps_config.provider=="yandex"){console.debug("marker_focus")}}if(!quasarComponents.empty(cmapsMarker[e])){if(this.maps_config.provider=="google.maps"){cmapsMarker[e].infowindow.close()}}},drawer_left(e,t){this.mapBoxResize()},drawer_right(e,t){this.mapBoxResize()}},methods:{getIcon(e){let t=[];t["driver"]={text:"",fontFamily:"Material Icons",color:"#ffffff",fontSize:"17px"};t["merchant"]={text:"",fontFamily:"Material Icons",color:"#ffffff",fontSize:"17px"};t["customer"]={text:"",fontFamily:"Material Icons",color:"#ffffff",fontSize:"17px"};return t[e]},renderMap(){if(this.maps_config.provider=="google.maps"){bounds=new window.google.maps.LatLngBounds;this.cmaps=new window.google.maps.Map(this.$refs.cmaps,{center:{lat:parseFloat(this.maps_config.default_lat),lng:parseFloat(this.maps_config.default_lng)},zoom:this.maps_config.zoom,disableDefaultUI:true,styles:MapStyle})}else if(this.maps_config.provider=="mapbox"){bounds=new mapboxgl.LngLatBounds;mapboxgl.accessToken=this.maps_config.key;this.cmaps=new mapboxgl.Map({container:this.$refs.cmaps,style:"mapbox://styles/mapbox/streets-v12",center:[parseFloat(this.maps_config.default_lng),parseFloat(this.maps_config.default_lat)],zoom:14});this.cmaps.on("error",e=>{alert(e.error.message)});this.mapBoxResize()}else if(this.maps_config.provider=="yandex"){this.initYandex()}},async initYandex(){if(this.maps_config.provider!="yandex"){return}await ymaps3.ready;const{YMap:e,YMapDefaultSchemeLayer:t,YMapMarker:r,YMapDefaultFeaturesLayer:a,YMapListener:s,YMapControls:i}=ymaps3;const{YMapDefaultMarker:o}=await ymaps3["import"]("@yandex/ymaps3-markers@0.0.1");const{YMapZoomControl:n,YMapGeolocationControl:l}=await ymaps3["import"]("@yandex/ymaps3-controls@0.0.1");const d={center:[parseFloat(this.maps_config.default_lat),parseFloat(this.maps_config.default_lng)],zoom:yandex_zoom};if(!yandex_map){yandex_map=new e(this.$refs.cmaps,{location:d,showScaleInCopyrights:false,behaviors:["drag","scrollZoom"]},[new t({}),new a({})]);yandex_map.addChild(new i({position:"right"}).addChild(new n({})))}else{if(Object.keys(this.driver_markers).length>0){Object.entries(this.driver_markers).forEach(([e,t])=>{let a=[parseFloat(t.lontitude),parseFloat(t.latitude)];yandex_bounds.push(a);const s=document.createElement("div");s.className="marker_icon_driver";let i="";i+='<div class="row items-start">';i+='<div class="col-2">';i+='<img src="'+t.photo_url+'" class="max-50 rounded-borders\t"></img>';i+="</div>";i+='<div class="col ">';i+='<div class="text-weight-bold">';i+=t.first_name+" "+t.last_name;i+="</div>";i+='<div class="text-weight-medium font-12">';i+="+"+t.phone_prefix+" "+t.phone;i+="<div>";i+="</div>";i+="</div>";i+="";s.onclick=()=>{this.$q.dialog({message:i,html:true})};cmapsMarker[t.driver_id]=yandex_map.addChild(new r({coordinates:a},s));yandex_markers[t.driver_id]=a})}if(Object.keys(this.marker).length>0){Object.entries(this.marker).forEach(([e,t])=>{let a=[parseFloat(t.position.lng),parseFloat(t.position.lat)];yandex_bounds.push(a);const s=document.createElement("div");s.className="marker_icon_merchant";s.onclick=()=>{this.$q.dialog({message:t.info,html:true})};cmapsMarker[t.index]=yandex_map.addChild(new r({coordinates:a},s));yandex_markers[t.index]=a})}if(Object.keys(yandex_bounds).length>1){const m={bounds:yandex_bounds,zoom:yandex_zoom};yandex_map.update({location:m})}else if(Object.keys(yandex_bounds).length>0){const c={center:[yandex_bounds[0][0],yandex_bounds[0][1]],zoom:yandex_zoom};yandex_map.update({location:c})}}},mapBoxResize(){if(this.maps_config.provider=="mapbox"){setTimeout(()=>{this.cmaps.resize()},500)}},instantiateMarker(){this.initYandex();Object.entries(this.marker).forEach(([e,t])=>{let a="";if(this.maps_config.provider=="google.maps"){a={path:google.maps.SymbolPath.CIRCLE,scale:8,strokeColor:"#"+t.bg_color}}else if(this.maps_config.provider=="mapbox"){a="marker_icon_merchant"}this.addMarker({position:t.position,map:this.cmaps,icon:a,label:this.getIcon("merchant"),info:t.info,bg_color:t.bg_color},t.index)});this.FitBounds()},instantiateDriverMarker(){this.initYandex();Object.entries(this.driver_markers).forEach(([e,t])=>{let a="";if(this.maps_config.provider=="google.maps"){a={path:google.maps.SymbolPath.CIRCLE,scale:8,strokeColor:"#f44336"}}else if(this.maps_config.provider=="mapbox"){a="marker_icon_driver"}let s={lat:parseFloat(t.latitude),lng:parseFloat(t.lontitude)};let i="";i+='<div class="row items-start">';i+='<div class="col-5 ">';i+='<img src="'+t.photo_url+'" class="max-50 rounded-borders\t"></img>';i+="</div>";i+='<div class="col ">';i+='<div class="text-weight-bold">';i+=t.first_name+" "+t.last_name;i+="</div>";i+='<div class="text-weight-medium font-12">';i+="+"+t.phone_prefix+" "+t.phone;i+="<div>";i+="</div>";i+="</div>";i+="";this.addMarker({position:s,map:this.cmaps,icon:a,label:this.getIcon("driver"),info:i},t.driver_id)});this.FitBounds()},moveDriverMarkers(){Object.entries(this.driver_locations).forEach(([t,a])=>{if(cmapsMarker[t]){if(this.maps_config.provider=="google.maps"){let e=new google.maps.LatLng(parseFloat(a.lat),parseFloat(a.lng));cmapsMarker[t].setPosition(e)}else{cmapsMarker[t].setLngLat([parseFloat(a.lng),parseFloat(a.lat)])}}})},addMarker(s,i){switch(this.maps_config.provider){case"google.maps":cmapsMarker[i]=new window.google.maps.Marker(s);this.cmaps.panTo(new window.google.maps.LatLng(s.position.lat,s.position.lng));bounds.extend(cmapsMarker[i].position);let e=this.cmaps;let t=cmapsMarker[i];markers.push(t);cmapsMarker[i].infowindow=new google.maps.InfoWindow({content:s.info});t.addListener("click",()=>{cmapsMarker[i].infowindow.open({anchor:t,cmaps:e,shouldFocus:false})});break;case"mapbox":const r=new mapboxgl.Popup({offset:25}).setHTML(s.info);const o=document.createElement("div");o.className=s.icon;o.style="background-color:#"+s.bg_color+";";cmapsMarker[i]=new mapboxgl.Marker(o).setLngLat([s.position.lng,s.position.lat]).setPopup(r).addTo(this.cmaps);bounds.extend(new mapboxgl.LngLat(s.position.lng,s.position.lat));let a=cmapsMarker[i];markers.push(a);break}},FitBounds(){if(this.maps_config.provider=="google.maps"){try{this.cmaps.fitBounds(bounds);if(this.enabled_cluster==1){this.clusterMarker()}}catch(e){console.error(e)}}else if(this.maps_config.provider=="mapbox"){this.cmaps.fitBounds(bounds)}},clusterMarker(){let e=this.cmaps;markerCluster=new MarkerClusterer(e,markers,{imagePath:"https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m"});markers=[]},FitBoundsCloseInfo(){this.FitBounds();if(this.maps_config.provider=="google.maps"){let e=this.cmaps;if(Object.keys(cmapsMarker).length>0){Object.entries(cmapsMarker).forEach(([e,t])=>{if(!quasarComponents.empty(cmapsMarker[e])){cmapsMarker[e].infowindow.close()}})}}else if(this.maps_config.provider=="yandex"){console.log("FitBoundsCloseInfo yandex");if(Object.keys(yandex_bounds).length>1){const e={bounds:yandex_bounds,zoom:yandex_zoom};yandex_map.update({location:e})}else if(Object.keys(yandex_bounds).length>0){const t={center:[yandex_bounds[0][0],yandex_bounds[0][1]],zoom:yandex_zoom};yandex_map.update({location:t})}}},locationFocus(a){if(this.maps_config.provider=="google.maps"){if(!quasarComponents.empty(cmapsMarker[a])){let e=cmapsMarker[a];this.cmaps.panTo(cmapsMarker[a].getPosition());this.cmaps.setZoom(18);let t=this.cmaps;cmapsMarker[a].infowindow.open({anchor:e,cmaps:t,shouldFocus:true})}}else if(this.maps_config.provider=="mapbox"){if(!quasarComponents.empty(cmapsMarker[a])){let e=cmapsMarker[a];let t=e.getLngLat();this.cmaps.flyTo({center:t,speed:.9})}}else if(this.maps_config.provider=="yandex"){if(!quasarComponents.empty(yandex_markers[a])){const e={center:yandex_markers[a],zoom:yandex_zoom};yandex_map.update({location:e})}}}},template:`         
    <div class="fit absolute">
      <div ref="cmaps"  class="fit"></div>
    </div>
    <q-page-sticky position="bottom-right" :offset="[18, 18]" style="z-index:9;">
      <q-btn @click="FitBoundsCloseInfo" fab color="accent"  padding="sm" >
        <q-icon name="my_location" size="xs"></q-icon>
      </q-btn>
     </q-page-sticky>
    `};const componentsDriversTotal={props:["task_url","driver_q"],mounted(){this.getTotalDrivers()},methods:{getTotalDrivers(){axios({method:"POST",url:this.task_url+"/getTotalDriversByTabs",data:"YII_CSRF_TOKEN="+$("meta[name=YII_CSRF_TOKEN]").attr("content")+"&q="+this.driver_q,timeout:$timeout}).then(e=>{if(e.data.code==1){this.$emit("afterTotaldriver",e.data.details)}})["catch"](e=>{}).then(e=>{})},refreshTotalDrivers(){this.getTotalDrivers()}}};const componentsAlldriver={props:["task_url","date_now"],data(){return{data:[]}}};const app_task=Vue.createApp({components:{"components-driver":componentsDriver,"components-orders":componentsOrders,"components-orders-total":componentsOrdersTotal,"components-details":componentsDetails,"components-assign":componentsAssign,"components-change-status":componentsChangeStatus,"components-driver-information":componentsDriverInformation,"components-orderlist":componentsOrderList,"components-notifications":componentsNotifications,"components-map":componentsMap,"components-allorder":componentsAllOrder,"components-drivers-total":componentsDriversTotal,"components-alldriver":componentsAlldriver},data(){return{drawerLeft:false,drawerRight:false,search_order:false,search_driver:false,tab:"unassigned",tab_agent:"duty",driver_onduty:0,driver_busy:0,tab_details:"order_details",show_details:false,order_uuid:"",modal_assign:false,modal_change_status:false,total_data:[],show_driverinfo:false,driver_id:"",search_date:"2019/02/01",q:"",filter_loading:false,filter_status:"",options:undefined,selected_status:[],data_marker:[],order_q:"",order_apply_filter:false,driver_markers:[],driver_locations:[],marker_focus:undefined,has_order_filter:false,driver_q:"",has_driver_filter:false}},mounted(){const e=quasarComponents.getStorage("driver_panel_tab");if(!quasarComponents.empty(e)){this.tab=e}},computed:{hasOrderFilter(){if(!quasarComponents.empty(this.order_q)){return false}return false}},methods:{setOnduty(e){this.driver_onduty=e},viewDetails(e,t){this.order_uuid=e;this.show_driverinfo=false;this.show_details=!this.show_details;this.marker_focus=t},showAssign(e){this.order_uuid=e;this.modal_assign=true},orderModalShow(){this.$refs.details.initDetails()},orderChangeTab(){quasarComponents.setStorage("driver_panel_tab",this.tab);this.show_details=false;this.show_driverinfo=false},afterAssign(e){console.debug("afterAssign =>"+e);this.modal_assign=false;if(typeof this.$refs.details!=="undefined"&&this.$refs.details!==null){this.show_details=false;this.$refs.details.DriverInformation(e)}this.refreshOrderTabs();this.refreshDriverTabs()},showChangestatus(e){this.order_uuid=this.order_uuid;this.modal_change_status=true},afterChangestatus(){console.debug("afterChangestatus");this.modal_change_status=false;this.refreshOrderTabs();this.refreshDriverTabs()},refreshOrderTabs(){console.debug("refreshOrderTabs =>"+this.tab);this.$refs.orders_total.getOrderTotal();if(this.tab=="unassigned"){this.$refs.tab_unassigned.getOrders(true)}else if(this.tab=="assigned"){this.$refs.tab_assigned.getOrders(true)}else if(this.tab=="completed"){this.$refs.tab_completed.getOrders(true)}this.$refs.all_order.getOrders()},afterTotal(e){this.total_data=e},showDriverinfo(e){this.show_details=false;this.show_driverinfo=!this.show_driverinfo;this.driver_id=e},onShowDriverInfo(){console.debug("onShowDriverInfo");this.$refs.driver_information.InitData()},filterOrder(){this.filter_loading=true;this.$refs.order_list.filterOrderList(this.q)},afterSearch(){this.filter_loading=false},onChooseStatus(){console.debug("onChooseStatus");console.debug(this.selected_status);this.filter_status=this.selected_status},refreshOrderList(){this.q="";this.$refs.order_list.filterOrderList("")},afterReceivenotifications(){this.refreshOrderTabs();this.refreshDriverTabs()},setDatamarker(e){this.data_marker=e},applyOrderFilter(){console.debug("applyOrderFilter");this.refreshOrderTabs();this.order_apply_filter=true;this.has_order_filter=true},afterGetorders(){this.order_apply_filter=false},resetOrderFilter(){console.log("resetOrderFilter");this.order_q="";this.order_apply_filter=true;this.has_order_filter=false;setTimeout(()=>{this.refreshOrderTabs()},500)},afterTotaldriver(e){this.driver_onduty=e.duty;this.driver_busy=e.busy},refreshDriverTabs(){this.$refs.drivers_total.refreshTotalDrivers();if(this.tab_agent=="duty"){this.$refs.driver.refreshList()}else{this.$refs.driver_busy.refreshList()}},applyDriverFilter(){this.refreshDriverTabs();this.has_driver_filter=true},resetDriverFilter(){this.has_driver_filter=false;this.driver_q="";setTimeout(()=>{this.refreshDriverTabs()},500)},setDatamarkerdriver(e){this.driver_markers=e},setLocationdriver(e){this.driver_locations=e},locationFocus(e){this.$refs.map_view.locationFocus(e)}}});app_task.use(Quasar,{config:{notify:{},loadingBar:{skipHijack:true},loading:{}}});app_task.mount("#app-task");