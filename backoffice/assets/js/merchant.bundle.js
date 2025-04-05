(function(u){"use strict";var d;var P;var n;var c;const s=2e4;const i={form:"application/x-www-form-urlencoded; charset=UTF-8",json:"application/json"};var o=function(e){console.debug(e)};var K=function(e){alert(JSON.stringify(e))};jQuery.fn.exists=function(){return this.length>0};var h=function(e){if(typeof e==="undefined"||e==null||e==""||e=="null"||e=="undefined"){return!0}
return!1};jQuery(document).ready(function(){if(u(".sidebar-nav").exists()){u("ul.sidebar-nav ul > li.active").parent().addClass("open");u("ul.sidebar-nav ul > li.orders_new").parent().addClass("open");u(document).on("click","ul li a",function(){u(this).parent().find(".sidebar-nav-sub-menu").slideToggle("fast");if(u(".nice-scroll").exists()){setTimeout(function(){u(".nice-scroll").getNiceScroll().resize()},100)}})}
u(document).ready(function(){u(".dropdown").on("show.bs.dropdown",function(){u(this).find(".dropdown-menu").first().stop(!0,!0).slideDown(150)});u(".dropdown").on("hide.bs.dropdown",function(){u(this).find(".dropdown-menu").first().stop(!0,!0).slideUp(150)})});if(u(".top-container").exists()){}
if(u(".headroom").exists()){var e=document.querySelector(".headroom");var t=new Headroom(e);t.init()}
if(u(".headroom2").exists()){var e=document.querySelector(".headroom2");var t=new Headroom(e);t.init()}
if(u(".select_two").exists()){u(".select_two").select2({allowClear:!1,templateResult:A,theme:"classic"})}
if(u(".select_two_ajax").exists()){var a=u(".select_two_ajax").attr("action");u(".select_two_ajax").select2({theme:"classic",language:{searching:function(){return"Searching..."},noResults:function(e){return"No results"},},ajax:{delay:250,url:ajaxurl+"/"+a,type:"POST",data:function(e){var t={search:e.term,YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content")};return t},},})}
if(u(".select_two_ajax2").exists()){var a=u(".select_two_ajax2").attr("action");u(".select_two_ajax2").select2({language:{searching:function(){return"Searching..."},noResults:function(e){return"No results"},},ajax:{delay:250,url:ajaxurl+"/"+a,type:"POST",data:function(e){var t={search:e.term,YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content")};return t},},})}
let i=["Su","Mo","Tu","We","Th","Fr","Sa"];let l=["January","February","March","April","May","June","July","August","September","October","November","December"];if(typeof daysofweek!=="undefined"&&daysofweek!==null){i=JSON.parse(daysofweek)}
if(typeof monthsname!=="undefined"&&monthsname!==null){l=JSON.parse(monthsname)}
if(u(".datepick").exists()){u(".datepick").each(function(e,s){u(s).daterangepicker({singleDatePicker:!0,autoUpdateInput:!1,locale:{format:"YYYY-MM-DD",daysOfWeek:i,monthNames:l},autoApply:!0},function(e,t,a){u(s).val(e.format("YYYY-MM-DD"))})})}
if(u(".datepick2").exists()){var s=u(".datepick2");u(".datepick2").daterangepicker({singleDatePicker:!0,autoUpdateInput:!1,locale:{format:"YYYY-MM-DD",daysOfWeek:i,monthNames:l},autoApply:!0},function(e,t,a){s.val(e.format("YYYY-MM-DD"))})}
if(u(".date_range_picker").exists()){var r=u(".date_range_picker");var o=r.data("separator");u(".date_range_picker").daterangepicker({autoUpdateInput:!1,showWeekNumbers:!0,alwaysShowCalendars:!0,autoApply:!0,locale:{format:"YYYY-MM-DD",daysOfWeek:i,monthNames:l},ranges:{Today:[moment(),moment()],Yesterday:[moment().subtract(1,"days"),moment().subtract(1,"days")],"Last 7 Days":[moment().subtract(6,"days"),moment()],"Last 30 Days":[moment().subtract(29,"days"),moment()],"This Month":[moment().startOf("month"),moment().endOf("month")],"Last Month":[moment().subtract(1,"month").startOf("month"),moment().subtract(1,"month").endOf("month")],},},function(e,t,a){r.val(e.format("YYYY-MM-DD")+" "+o+" "+t.format("YYYY-MM-DD"))})}
if(u(".timepick").exists()){let e=!h(website_twentyfour_format)?website_twentyfour_format:!1;let t=e==1?"HH:mm":"hh:mm A";u(".timepick").datetimepicker({format:t})}
if(u(".tool_tips").exists()){u(".tool_tips").tooltip()}
if(u(".colorpicker").exists()){u(".colorpicker").spectrum({type:"component",showAlpha:!1})}
u("#frm_search").submit(function(e){d.search(u(".search").val()).draw();u(".search_close").show()});u(document).on("click",".search_close",function(){u("#frm_search").find(".search").val("");d.search("").draw();u(".search_close").hide()});u("#frm_search_app").submit(function(e){Z();g();u(".search_close_app").show()});u(document).on("click",".search_close_app",function(){u("#frm_search_app").find(".search").val("");u(".search_close_app").hide();Z(!0);g()});u(".frm_search_filter").submit(function(e){u(".search_close_filter").show();d.destroy();d.clear();m(u(".table_datatables"),u(".frm_datatables"))});u(document).on("click",".search_close_filter",function(){u(".frm_search_filter").find(".search,.date_range_picker").val("");u(".search_close_filter").hide();d.destroy();d.clear();m(u(".table_datatables"),u(".frm_datatables"))});u(".image_file").on("change",function(){var e=u(this).val().split("\\").pop();u(this).siblings(".image_label").addClass("selected").html(e)});u(".image2_file").on("change",function(){var e=u(this).val().split("\\").pop();u(this).siblings(".image2_label").addClass("selected").html(e)});if(u(".mask_time").exists()){u(".mask_time").mask("00:00")}
if(u(".mask_minutes").exists()){u(".mask_minutes").mask("00")}
if(u(".mask_phone").exists()){u(".mask_phone").mask("(00) 0000-0000")}
if(u(".mask_mobile").exists()){let e="+000000000000";if(typeof backend_phone_mask!=="undefined"&&backend_phone_mask!==null){e=backend_phone_mask}
u(".mask_mobile").mask(e)}
if(u(".card_number").exists()){u(".card_number").mask("0000 0000 0000 0000")}
if(u(".card_expiration").exists()){u(".card_expiration").mask("00/00")}
if(u(".card_cvv").exists()){u(".card_cvv").mask("000")}
if(u(".estimation").exists()){u(".estimation").mask("00-00")}
if(u(".mask_date").exists()){u(".mask_date").mask("0000/00/00")}
if(u(".summernote").exists()){u(".summernote").summernote({height:200,toolbar:[["font",["bold","underline","italic","clear"]],["para",["ul","ol","paragraph"]],["style",["style"]],["color",["color"]],["table",["table"]],["insert",["link","picture","video"]],["view",["fullscreen","undo","redo"]],],})}
if(u(".copy_text_to").exists()){u(".copy_text_to").keyup(function(e){var t=u(this).val();var a=u(this).data("id");t=z(t);u(a).val(t)})}
if(typeof is_mobile!=="undefined"&&is_mobile!==null){P=is_mobile}
if(!P){if(u(".nice-scroll").exists()){u(".nice-scroll").niceScroll({autohidemode:!0,horizrailenabled:!1})}}
u(".sidebar-panel").slideAndSwipe();u(document).on("click",".hamburger",function(){u(this).toggleClass("is-active")});u(document).on("click",function(e){if(u(e.target).closest(".hamburger").length===0){if(u(".hamburger").hasClass("is-active")){u(".hamburger").removeClass("is-active")}}});u(document).on("click",".checkbox_select_all",function(){u(this).toggleClass("checked");if(!u(this).hasClass("checked")){u(".checkbox_child").prop("checked",!1)}else{u(".checkbox_child").prop("checked",!0)}});u(".item_multi_options").on("change",function(){D(u(this).val())});if(u(".item_multi_options").exists()){D(u(".item_multi_options").val())}
if(u("#lazy-start").exists()){g()}
u(".broadcast_send_to").on("change",function(){M(u(this).val())});if(u(".broadcast_send_to").exists()){M(u(".broadcast_send_to").val())}
u(".table_review tbody").on("click","td",function(){c=u(this).closest("tr");n=d.row(c);var e=u(this).find("a.review_viewcomments").data("id");if(e>0){v("customer_reply","parent_id="+e)}});u(function(){u('[data-toggle="tooltip"]').tooltip()})});function D(e){switch(e){case "custom":u(".multi_option_value_text").show();u(".multi_option_value_selection").hide();u(".multi_option_min").show();break;case "multiple":u(".multi_option_value_text").show();u(".multi_option_value_selection").hide();u(".multi_option_min").show();break;case "two_flavor":u(".multi_option_value_text").hide();u(".multi_option_value_selection").show();u(".multi_option_min").hide();break;default:u(".multi_option_value_text").hide();u(".multi_option_value_selection").hide();u(".multi_option_min").hide();break}}
function M(e){switch(e){case 3:case "3":u(".broadcast_list_mobile").show();break;default:u(".broadcast_list_mobile").hide();break}}
function z(e){return e.toString().normalize("NFD").replace(/[\u0300-\u036f]/g,"").toLowerCase().replace(/\s+/g,"-").replace(/&/g,"-and-").replace(/[^\w\-]+/g,"").replace(/\-\-+/g,"-").replace(/^-+/,"").replace(/-+$/,"")}
function A(e){if(e&&!e.selected){return u("<span>"+e.text+"</span>")}}
var L=function(e){return e};const U=new Notyf({duration:1e3*4});var r=function(e,t){switch(t){case "error":U.error(e);break;default:U.success(e);break}};var m=function(e,t){u.fn.dataTable.ext.errMode="none";var a=t.serializeArray();var s={};u.each(a,function(){if(s[this.name]){if(!s[this.name].push){s[this.name]=[s[this.name]]}
s[this.name].push(this.value||"")}else{s[this.name]=this.value||""}});var i="";if(typeof action_name!=="undefined"&&action_name!==null){i=action_name}
let l=!1;if(typeof datatable_export!=="undefined"&&datatable_export!==null){l=datatable_export}
let r={aaSorting:[[0,"DESC"]],processing:!0,serverSide:!0,bFilter:!0,dom:'<"top">rt<"row"<"col-md-6"i><"col-md-6"p>><"clear">',pageLength:10,ajax:{url:ajaxurl+"/"+i,type:"POST",data:s},language:{url:ajaxurl+"/DatableLocalize"},buttons:["excelHtml5","csvHtml5","pdfHtml5"],};if(l==1){r.dom="Brtip"}
d=e.on("preXhr.dt",function(e,t,a){o("loading")}).on("xhr.dt",function(e,t,a,s){o("done");setTimeout(function(){u(".tool_tips").tooltip()},100)}).on("error.dt",function(e,t,a,s){o("error")}).DataTable(r)};var e="";var _={};var p={};var l;var q;jQuery(document).ready(function(){if(u(".table_datatables").exists()){m(u(".table_datatables"),u(".frm_datatables"))}
u(document).on("click",".datatables_delete",function(){e=u(this).data("id");u(".delete_confirm_modal").modal("show")});u(".delete_confirm_modal").on("shown.bs.modal",function(){if(typeof delete_custom_link!=="undefined"&&delete_custom_link!==null){u(".item_delete").attr("href",delete_custom_link+"&id="+e)}else{u(".item_delete").attr("href",delete_link+"?id="+e)}});u(document).on("click",".delete_image",function(){e=u(this).data("id");u(".delete_image_confirm_modal").modal("show")});u(".delete_image_confirm_modal").on("shown.bs.modal",function(){u(".item_delete").attr("href",e)});u(document).on("click",".order_history",function(){e=u(this).data("id");u(".order_history_modal").modal("show")});u(".order_history_modal").on("show.bs.modal",function(){v("order_history","id="+e)});if(u("#dropzone_multiple").exists()){H()}
u(document).on("change",".set_item_available",function(e){var t=u(e.target).val();var a=u(this).is(":checked");a=a==!0?1:0;setTimeout(function(){v("update_item_available","id="+t+"&checked="+a)},100)});u(document).on("change",".set_payment_provider",function(){var e=u(this).val();var t=u(this).prop("checked");t=t==!0?"active":"inactive";setTimeout(function(){v("set_payment_provider","id="+e+"&status="+t)},100)});u(document).on("change",".set_banner_status",function(e){var t=u(e.target).val();var a=u(this).is(":checked");a=a==!0?1:0;setTimeout(function(){v("set_banner_status","id="+t+"&checked="+a)},100)});u(".coupon_options").change(function(){B(u(this).val())});if(u(".coupon_options").exists()){B(u(".coupon_options").val())}});var B=function(e){u(".coupon_customer").hide();if (e == 9) { u(".coupon_customer").show() } else if (e == 5 || e == 6 || e == 7 || e == 8){u(".coupon_max_number_use").show()}else{}};var f=function(){var e=Date.now()+(Math.random()*1e5).toFixed();return e};var V=function(){var e="";var t=u("meta[name=YII_CSRF_TOKEN]").attr("content");e+="&YII_CSRF_TOKEN="+t;return e};var v=function(e,t,a,s,i){l=f();if(!h(a)){var l=a}
if(h(i)){i="POST";t+=V()}
_[l]=u.ajax({url:ajaxurl+"/"+e,method:i,data:t,dataType:"json",timeout:2e4,crossDomain:!0,beforeSend:function(e){if(typeof s!=="undefined"&&s!==null){}else{}
if(_[l]!=null){o("request aborted");_[l].abort();clearTimeout(p[l])}else{p[l]=setTimeout(function(){_[l].abort();r(L("Request taking lot of time. Please try again"))},2e4)}},});_[l].done(function(e){o("done");var t="";if(typeof e.details.next_action!=="undefined"&&e.details.next_action!==null){t=e.details.next_action}
if(e.code==1){switch(t){case "csv_continue":u(".csv_progress_"+e.details.id).html(e.msg);setTimeout(function(){processCSV(e.details.id)},1*1e3);break;case "csv_done":u(".csv_progress_"+e.details.id).html(e.msg);u('a.view_delete_process[data-id="'+e.details.id+'"]').html('<i class="zmdi zmdi-mail-send"></i>');break;case "order_history":J(e.details.data,".order_history_modal table tbody");break;case "review_reply":X(e.details.data);break;case "silent":break;default:r(e.msg,"success");break}}else{switch(t){case "clear_order_history":u(".order_history_modal table tbody").html("");break;case "silent":break;default:r(e.msg,"danger");break}}});_[l].always(function(){o("ajax always");_[l]=null;clearTimeout(p[l])});_[l].fail(function(e,t){clearTimeout(p[l]);r(L("Failed")+": "+t,"danger")})};var J=function(e,t){var a="";u.each(e,function(e,t){a+="<tr>";a+="<td>"+t.date_created+"</td>";a+="<td>"+t.status+"</td>";a+="<td>"+t.remarks+"</td>";a+="</tr>"});u(t).html(a)};var H=function(){if(typeof upload_params!=="undefined"&&upload_params!==null){var e=JSON.parse(upload_params)}else var e={};var t=u("#dropzone_multiple").data("action");q=u("#dropzone_multiple").dropzone({paramName:"file",url:upload_ajaxurl+"/"+t,maxFiles:20,params:e,addRemoveLinks:!1,success:function(e,t){e.previewElement.innerHTML="";var a=JSON.parse(t);o(a);var s="";if(typeof a.details!=="undefined"&&a.details!==null){if(typeof a.details.next_action!=="undefined"&&a.details.next_action!==null){s=a.details.next_action}}
if(a.code==1){switch(s){case "display_image":var i=a.details.file_url;var l=a.details.remove_url;G(".item_gallery_preview .row",i,l);break;default:r(a.msg,"success");break}}else{switch(s){default:r(a.msg,"danger");break}}},})};var G=function(e,t,a){var s="";s+='<div class="col-lg-4 mb-4 mb-lg-0 preview-image">';s+='<a type="button" class="btn btn-black btn-circle delete_image" href="javascript:;" data-id="'+a+'"><i class="zmdi zmdi-plus"></i></a>';s+='<img src="'+t+'" class="img-fluid mb-2">';s+="</div>";u(e).append(s)};var g=function(){l=u("#lazy-start").infiniteScroll({path:function(){var e=u(".frm_search").serializeArray();var t={};var a="";u.each(e,function(){if(t[this.name]){if(!t[this.name].push){t[this.name]=[t[this.name]]}
t[this.name].push(this.value||"")}else{t[this.name]=this.value||""}});u.each(t,function(e,t){a+="&"+e+"="+t});a+="&page="+this.pageIndex;return ajaxurl+"/"+action_name+"/?"+a},responseBody:"json",history:!1,status:".lazy-load-status",});l.on("load.infiniteScroll",function(e,t){if(t.code==1){u(".page-no-results").hide();if(t.details.is_search){o("search==");l.html("")}
var a="";if(typeof t.details.next_action!=="undefined"&&t.details.next_action!==null){a=t.details.next_action}
o(t);switch(a){case "display_gallery":u("#lazy-start").addClass("row");Q(t.details.data);break;default:W(t.details.data);break}}else{var s=parseInt(t.details.page);if(s<=0){l.html("");u(".page-no-results").show()}else{l.infiniteScroll("option",{loadOnScroll:!1})}}});l.infiniteScroll("loadNextPage")};var W=function(e){var a="";u.each(e,function(e,t){a+='<div class="kmrs-row">';a+='<div class="d-flex bd-highlight">';a+='<div class="p-2 bd-highlight">';a+=t[0];a+="</div>";a+='<div class="p-2 bd-highlight flex-grow-1">';a+=t[1];a+="</div>";a+="</div>";a+='<div class="d-flex justify-content-end">';if(u.isArray(t[2])){u.each(t[2],function(e){a+='<div class="p-2" >';a+=t[2][e];a+="</div>"})}
a+="</div>";a+="</div>"});l.append(a)};var Z=function(e){try{if(e){l.html("")}
l.infiniteScroll("destroy");l.removeData("infiniteScroll");l.off("load.infiniteScroll")}catch(t){o(t.message)}};var Q=function(e){var a="";u.each(e,function(e,t){a+='<div class="col-lg-3 col-md-12 mb-4 mb-lg-3">';a+='<div class="card" >';a+=t[0];a+='<div class="card-body">';a+=t[1];a+='<div class="d-flex justify-content-end">';a+='<div class="btn-group btn-group-actions" role="group">';a+=t[2][1];a+="</div>";a+="</div>";a+="</div>";a+="</div>";a+="</div>"});l.append(a)};var X=function(e){var a="";u.each(e,function(e,t){a+='<div class="d-flex">';a+='<div class="w-100 ml-5"><h6>'+t.reply_from+"</h6> <p>"+t.review+"</p>";a+='<div class="btn-group btn-group-actions mr-4" role="group">';a+='<a href="'+t.edit_link+'" class="btn btn-light tool_tips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Update">';a+='<i class="zmdi zmdi-border-color"></i>';a+="</a>";a+='<a href="javascript:;" data-id="'+t.id+'" class="btn btn-light datatables_delete tool_tips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete">';a+='<i class="zmdi zmdi-delete"></i>';a+="</a>";a+="</div>";a+="</div>";a+="</div>"});if(n.child.isShown()){n.child.hide();c.removeClass("shown")}else{n.child(a).show();c.addClass("shown")}};const ee={props:["ajax_url","message"],data(){return{settings:[],beams:undefined}},mounted(){this.getWebpushSettings()},methods:{getWebpushSettings(){axios({method:"POST",url:this.ajax_url+"/getWebpushSettings",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.settings=e.data.details;if(this.settings.enabled==1){this.webPushInit()}}else{this.settings=[]}})["catch"]((e)=>{}).then((e)=>{})},webPushInit(){if(this.settings.provider=="pusher"&&this.settings.user_settings.enabled==1){this.beams=new PusherPushNotifications.Client({instanceId:this.settings.pusher_instance_id});this.beams.start().then(()=>{this.beams.setDeviceInterests(this.settings.user_settings.interest).then(()=>{console.log("Device interests have been set")}).then(()=>this.beams.getDeviceInterests()).then((e)=>console.log("Current interests:",e))["catch"]((e)=>{var t={notification_type:"push",message:"Beams "+e,date:"",image_type:"icon",image:"zmdi zmdi-info-outline"};if(typeof vm_notifications!=="undefined"&&vm_notifications!==null){vm_notifications.$refs.notification.addData(t)}})})["catch"]((e)=>{var t={notification_type:"push",message:"Beams "+e,date:"",image_type:"icon",image:"zmdi zmdi-info-outline"};if(typeof vm_notifications!=="undefined"&&vm_notifications!==null){vm_notifications.$refs.notification.addData(t)}})}else if(this.settings.provider=="onesignal"){}},},template:`	   
	`,};
	const te={components:{"components-webpush":ee},
	props:["ajax_url","label","realtime","view_url"],
	data(){return{data:[],count:0,new_message:!1,player:undefined,ably:undefined,channel:undefined,piesocket:undefined}},
	mounted(){this.getAllNotification();if(this.realtime.enabled){this.initRealTime()}},
	computed:{hasData(){if(this.data.length>0){return!0}
return!1},ReceiveMessage(){if(this.new_message){return!0}
return!1},},methods:{initRealTime(){if(this.realtime.provider=="pusher"){Pusher.logToConsole=!1;var e=new Pusher(this.realtime.key,{cluster:this.realtime.cluster});var t=e.subscribe(this.realtime.channel);t.bind(this.realtime.event,(t)=>{o("receive pusher");o(t);o(t.notification_type);if(t.notification_type=="silent"){}else if(t.notification_type=="order_update"){this.playAlert();this.addData(t);if(typeof we!=="undefined"&&we!==null){let e=t.meta_data;we.refreshOrderInformation(e.order_uuid)}}else{this.playAlert();this.addData(t)}})}else if(this.realtime.provider=="ably"){this.ably=new Ably.Realtime(this.realtime.ably_apikey);this.ably.connection.on("connected",()=>{this.channel=this.ably.channels.get(this.realtime.channel);this.channel.subscribe(this.realtime.event,(e)=>{o("receive ably");o(e.data);this.playAlert();this.addData(e.data)})})}else if(this.realtime.provider=="piesocket"){this.piesocket=new PieSocket({clusterId:this.realtime.piesocket_clusterid,apiKey:this.realtime.piesocket_api_key});this.channel=this.piesocket.subscribe(this.realtime.channel);this.channel.listen(this.realtime.event,(e)=>{o("receive piesocket");o(e);this.playAlert();this.addData(e)})}},playAlert(){this.player=new Howl({src:["../assets/sound/notify.mp3","../assets/sound/notify.ogg"],html5:!0});this.player.play()},getAllNotification(){axios({method:"POST",url:this.ajax_url+"/getNotifications",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details.data;this.count=e.data.details.count}else{this.data=[];this.count=0}})["catch"]((e)=>{}).then((e)=>{})},addData(e){this.data.unshift(e);this.count++;this.new_message=!0;setTimeout(()=>{this.new_message=!1},1e3);if(typeof E!=="undefined"&&E!==null){E.getOrdersCount()}
if(typeof N!=="undefined"&&N!==null){N.$refs.orderlist.getList()}
if(typeof at!=="undefined"&&at!==null){at.refreshLastOrder()}},clearAll(){axios({method:"POST",url:this.ajax_url+"/clearNotifications",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.data=[];this.count=0}else{r(e.data.msg,"error")}
this.new_message=!1})["catch"]((e)=>{}).then((e)=>{})},},template:`
	
	<components-webpush
	 :ajax_url="ajax_url"
	 :message='label'
	/>
	</components-webpush>
	
	<div class="btn-group pull-right notification-dropdown">
	      <button type="button" class="btn p-0 btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	        <i class="zmdi zmdi-notifications-none"></i>
	        <span v-if="count>0" :class="{'shake-constant shake-chunk' : ReceiveMessage }" class="badge rounded-circle badge-danger count">{{count}}</span>
	      </button>
          <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header d-flex justify-content-between">
               <div class="flex-col">
                 <div class="d-flex align-items-center">
                  <h5 class="m-0 mr-2">{{label.title}}</h5>
                  <span class="badge rounded-circle badge-green badge-25">{{count}}</span>
                 </div>
               </div>
               <div class="flex-col" v-if="hasData">
                <a @click="clearAll">{{label.clear}}</a>
               </div>
            </div>
            <!--header-->
            
            <!--content-->            
            <ul v-if="hasData"  class="list-unstyled m-0">
             <li v-for="(item,index) in data">
              <a :class="{ active: index<=0 }" >
                <div class="d-flex">
                   <div v-if="item.image!=''" class="flex-col mr-3">  
                      <template v-if="item.image_type=='icon'">
                         <div class="notify-icon rounded-circle bg-soft-primary">
	                        <i :class="item.image"></i>
	                      </div>
                      </template>
                      <template v-else>
                       <div class="notify-icon">
                          <img class="img-40 rounded-circle" :src="item.image" />
                       </div>
                      </template>
                   </div>
                   <div class="flex-col">
                      <div class="text-heading" v-html="item.message"></div>
	                  <div class="dropdown-text-light">{{item.date}}</div>
                   </div>
                </div>
              </a>
             </li>		            		             
            </ul>
            <!--content-->
            
            <div v-if="!hasData" class="none-notification text-center">
              <div class="image-notification m-auto"></div>
              <h5 class="m-0 mb-1 mt-2">{{label.no_notification}}</h5>
              <p class="m-0 font11 text-muted">{{label.no_notification_content}}</p>
            </div>
            
            <div v-if="hasData" class="footer-dropdown text-center">
            <a :href="view_url" targe="_blank" class="text-primary">{{label.view}}</a>
            </div>
            
          </div> <!--dropdown-menu-->
      </div>
      <!--btn-group-->
	`,};
	const ae={props:["label","ajax_url","tpl"],data(){return{is_loading:!1,data:[]}},mounted(){this.merchantPlanStatus()},methods:{merchantPlanStatus(){this.is_loading=!0;axios({method:"POST",url:this.ajax_url+"/merchantPlanStatus",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details}else{this.data=[]}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},template:`		
	  <div v-if="tpl==='1'" class="card m-auto">
	  
		<div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
		    <div>
		      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
		    </div>
		</div>
	  
	     <div class="card-body">
	        <h5 class="mb-1">{{data.restaurant_name}}</h5>
	        
	         <div class="d-flex justify-content-between">
			   <div class="flex-col">{{label.current_status}}</div>
			   <div class="flex-col"><span class="badge customer" :class="data.status_raw">{{data.status}}</span></div>
			 </div>
	        
	     </div>  <!-- body -->
	  </div> <!-- card -->
	  
	 <template v-else-if="tpl==='2'" >
	 <div v-if="data.status_raw=='expired'" class="p-2 align-self-center">
      <i class="zmdi zmdi-alarm text-danger"></i><span class="ml-2"><b>{{label.trial_ended}}</b></span>
     </div>
     </template>
	`,};const t={props:["ajax_url","amount"],data(){return{data:0,config:JSON.parse(money_config)}},mounted(){this.data=window["v-money3"].format(this.amount,this.config)},updated(){this.data=window["v-money3"].format(this.amount,this.config)},methods:{formatNumber(e){return window["v-money3"].format(e,this.config)},},template:`	
    {{data}}
    `,};var se=function(e){return window["v-money3"].format(e,JSON.parse(money_config))};const ie=[{featureType:"administrative",elementType:"labels.text.fill",stylers:[{color:"#686868"}]},{featureType:"landscape",elementType:"all",stylers:[{color:"#f2f2f2"}]},{featureType:"poi",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"road",elementType:"all",stylers:[{saturation:-100},{lightness:45}]},{featureType:"road.highway",elementType:"all",stylers:[{visibility:"simplified"}]},{featureType:"road.highway",elementType:"geometry.fill",stylers:[{lightness:"-22"}]},{featureType:"road.highway",elementType:"geometry.stroke",stylers:[{saturation:"11"},{lightness:"-51"}]},{featureType:"road.highway",elementType:"labels.text",stylers:[{saturation:"3"},{lightness:"-56"},{weight:"2.20"}]},{featureType:"road.highway",elementType:"labels.text.fill",stylers:[{lightness:"-52"}]},{featureType:"road.highway",elementType:"labels.text.stroke",stylers:[{weight:"6.13"}]},{featureType:"road.highway",elementType:"labels.icon",stylers:[{lightness:"-10"},{gamma:"0.94"},{weight:"1.24"},{saturation:"-100"},{visibility:"off"}]},{featureType:"road.arterial",elementType:"geometry",stylers:[{lightness:"-16"}]},{featureType:"road.arterial",elementType:"labels.text.fill",stylers:[{saturation:"-41"},{lightness:"-41"}]},{featureType:"road.arterial",elementType:"labels.text.stroke",stylers:[{weight:"5.46"}]},{featureType:"road.arterial",elementType:"labels.icon",stylers:[{visibility:"off"}]},{featureType:"road.local",elementType:"geometry.fill",stylers:[{weight:"0.72"},{lightness:"-16"}]},{featureType:"road.local",elementType:"labels.text.fill",stylers:[{lightness:"-37"}]},{featureType:"transit",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"water",elementType:"all",stylers:[{color:"#b7e4f4"},{visibility:"on"}]},];const le={props:["markers","center","zoom","maps_config"],data(){return{bounds:[],cmaps:undefined,cmapsMarker:[],allMarkers:[]}},mounted(){},watch:{markers(e,t){this.clearMarkers()},},methods:{renderMap(){if(this.maps_config.provider=="google.maps"){this.bounds=new window.google.maps.LatLngBounds();this.cmaps=new window.google.maps.Map(this.$refs.maploc,{center:{lat:parseFloat(this.center.lat),lng:parseFloat(this.center.lng)},zoom:parseInt(this.zoom),disableDefaultUI:!1,styles:ie})}else{this.bounds=new mapboxgl.LngLatBounds();mapboxgl.accessToken=this.maps_config.key;this.cmaps=new mapboxgl.Map({container:this.$refs.maploc,style:"mapbox://styles/mapbox/streets-v12",center:[parseFloat(this.center.lng),parseFloat(this.center.lat)],zoom:14});this.cmaps.on("error",(e)=>{alert(e.error.message)});this.mapBoxResize()}
this.instantiateDriverMarker()},clearMarkers(){if(this.maps_config.provider=="google.maps"){for(var e=0;e<this.allMarkers.length;e++){this.allMarkers[e].setVisible(!1);this.allMarkers[e].setMap(null)}}
this.allMarkers=[];this.instantiateDriverMarker()},instantiateDriverMarker(){Object.entries(this.markers).forEach(([e,t])=>{let a="";if(this.maps_config.provider=="google.maps"){a={path:google.maps.SymbolPath.CIRCLE,scale:8,strokeColor:"#f44336"}}else{a=this.getIconMapbox(t.type)}
let s={lat:parseFloat(t.lat),lng:parseFloat(t.lng)};let i=t.name;this.addMarker({position:s,map:this.cmaps,icon:a,label:this.getIcon(t.type),info:i},t.index)});this.FitBounds()},getIconMapbox(e){let t=[];t.driver="marker_icon_driver";t.merchant="marker_icon_merchant";t.customer="marker_icon_destination";return t[e]},getIcon(e){let t=[];t.driver={text:"",fontFamily:"Material Icons",color:"#ffffff",fontSize:"17px"};t.merchant={text:"",fontFamily:"Material Icons",color:"#ffffff",fontSize:"17px"};t.customer={text:"",fontFamily:"Material Icons",color:"#ffffff",fontSize:"17px"};return t[e]},addMarker(a,s){if(h(this.cmaps)){return}
if(this.maps_config.provider=="google.maps"){this.cmapsMarker[s]=new window.google.maps.Marker(a);this.cmaps.panTo(new window.google.maps.LatLng(a.position.lat,a.position.lng));this.bounds.extend(this.cmapsMarker[s].position);this.allMarkers.push(this.cmapsMarker[s]);const i=new google.maps.InfoWindow({content:a.info});let e=this.cmaps;let t=this.cmapsMarker[s];t.addListener("click",()=>{i.open({anchor:t,cmaps:e,shouldFocus:!1})})}else{const t=new mapboxgl.Popup({offset:25}).setHTML(a.info);const e=document.createElement("div");e.className=a.icon;this.cmapsMarker[s]=new mapboxgl.Marker(e).setLngLat([a.position.lng,a.position.lat]).setPopup(t).addTo(this.cmaps);this.bounds.extend(new mapboxgl.LngLat(a.position.lng,a.position.lat))}},FitBounds(){if(h(this.cmaps)){return}
if(this.maps_config.provider=="google.maps"){try{this.cmaps.fitBounds(this.bounds)}catch(e){console.error(e)}}else{this.cmaps.fitBounds(this.bounds,{padding:30})}},mapBoxResize(){if(this.maps_config.provider=="mapbox"){setTimeout(()=>{this.cmaps.resize()},500)}},},template:`			
	<div ref="maploc" class="map-small"></div>
	`,};const re={props:["order_uuid","ajax_url","map_center","zoom"],components:{"components-map":le},data(){return{loading:!1,data:[],zone_id:0,group_selected:0,group_data:[],zone_data:[],markers:[],active_task:[]}},created(){if(!h(this.order_uuid)){this.getAvailableDriver()}
this.getGroupList();this.getZoneList()},watch:{order_uuid(e,t){this.getAvailableDriver()},zone_id(e,t){this.getAvailableDriver()},group_selected(e,t){this.getAvailableDriver()},},computed:{hasData(){if(Object.keys(this.data).length>0){return!0}
return!1},hasMarkers(){if(Object.keys(this.markers).length>0){return!0}
return!1},hasFilter(){let e=!1;if(!h(this.zone_id)){e=!0}
if(this.group_selected>0){e=!0}
return e},},methods:{show(){u(this.$refs.modal).modal("show");if(Object.keys(this.markers).length>0){this.$refs.map_components.renderMap()}},hide(){u(this.$refs.modal).modal("hide")},getGroupList(){axios({method:"post",url:this.ajax_url+"/getGroupList",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{this.group_data=e.data.details})["catch"]((e)=>{this.group_data=[]}).then((e)=>{})},getZoneList(){axios({method:"post",url:this.ajax_url+"/getZoneList",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{this.zone_data=e.data.details})["catch"]((e)=>{this.zone_data=[]}).then((e)=>{})},clearFilter(){this.zone_id=0;this.group_selected=0;this.getAvailableDriver()},getAvailableDriver(){this.loading=!0;axios({method:"post",url:this.ajax_url+"/getAvailableDriver",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&order_uuid="+this.order_uuid+"&zone_id="+this.zone_id+"&group_selected="+this.group_selected,timeout:s,}).then((e)=>{if(e.data.code==1){this.data=e.data.details.data;this.merchant_data=e.data.details.merchant_data;this.active_task=e.data.details.active_task}else{this.data=[];this.merchant_data=e.data.details.merchant_data;this.active_task=[]}
this.SetMarker()})["catch"]((e)=>{this.data=[];this.merchant_data=[];this.active_task=[]}).then((e)=>{this.loading=!1})},SetMarker(){this.markers=[];if(Object.keys(this.data).length>0){Object.entries(this.data).forEach(([e,t])=>{this.markers.push({type:"driver",name:t.name,lat:t.latitude,lng:t.longitude})})}
if(Object.keys(this.merchant_data).length>0){this.markers.push({type:"merchant",name:this.merchant_data.restaurant_name,lat:this.merchant_data.latitude,lng:this.merchant_data.longitude})}},AssignDriver(e){this.loading=!0;let t="YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content");t+="&driver_id="+e;t+="&order_uuid="+this.order_uuid;axios({method:"post",url:this.ajax_url+"/AssignDriver",data:t,timeout:s}).then((e)=>{if(e.data.code==1){this.$emit("refreshOrder",this.order_uuid);ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"success"});this.hide()}else{ElementPlus.ElNotification({title:"",message:e.data.msg,type:"warning"})}})["catch"]((e)=>{ElementPlus.ElNotification({title:"",message:e,type:"warning"})}).then((e)=>{this.loading=!1})},},template:"#xtemplate_assign_driver",};const oe={props:["label","size"],methods:{confirm(){bootbox.confirm({size:this.size,title:this.label.confirm,message:this.label.are_you_sure,centerVertical:!0,animate:!1,buttons:{cancel:{label:this.label.cancel,className:"btn btn-black small pl-4 pr-4"},confirm:{label:this.label.yes,className:"btn btn-green small pl-4 pr-4"}},callback:(e)=>{this.$emit("callback",e)},})},alert(e,t){bootbox.alert({size:!h(t.size)?t.size:"",closeButton:!1,message:e,animate:!1,centerVertical:!0,buttons:{ok:{label:this.label.ok,className:"btn btn-green small pl-4 pr-4"}}})},},};const a=Vue.createApp({components:{"component-bootbox":oe},data(){return{resolvePromise:undefined,rejectPromise:undefined}},methods:{confirm(){return new Promise((e,t)=>{this.resolvePromise=e;this.rejectPromise=t;this.$refs.bootbox.confirm()})},Callback(e){this.resolvePromise(e)},alert(e,t){this.$refs.bootbox.alert(e,t)},},}).mount("#vue-bootbox");const de={props:["label","max_file","select_type","field","field_path","selected_file","selected_multiple_file","max_file_size","inline","upload_path","save_path"],components:{"component-bootbox":oe},data(){return{data:[],q:"",page_count:0,current_page:0,preview:!1,dropzone:undefined,tab:1,is_loading:!1,page:1,item_selected:[],added_files:[],awaitingSearch:!1,data_message:""}},mounted(){this.getMedia();this.getMediaSeleted();this.getMediaMultipleSeleted();this.initDropzone()},updated(){o("inline=>"+this.inline)},watch:{q(e,t){if(!this.awaitingSearch){if(h(e)){this.getMedia();return!1}
setTimeout(()=>{var e={YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),page:this.page,q:this.q};var t=f();e=JSON.stringify(e);_[t]=u.ajax({url:upload_ajaxurl+"/getMedia",method:"PUT",dataType:"json",data:e,contentType:i.json,timeout:s,crossDomain:!0,beforeSend:(e)=>{if(_[t]!=null){_[t].abort()}},}).done((e)=>{this.data_message=e.msg;if(e.code==1){this.data=e.details.data;this.page_count=e.details.page_count;this.current_page=e.details.current_page}else{this.data=[];this.page_count=0;this.current_page=0}}).always((e)=>{this.awaitingSearch=!1})},1e3);this.data=[];this.awaitingSearch=!0}},},computed:{hasData(){if(this.data.length>0){return!0}
return!1},hasSelected(){if(this.item_selected.length>0){return!0}
return!1},totalSelected(){return this.item_selected.length},hasAddedFiles(){if(this.added_files.length>0){return!0}
return!1},noFiles(){if(this.data.length>0){return!1}
if(this.awaitingSearch){return!1}
return!0},hasSearch(){if(!h(this.q)){return!0}
return!1},},methods:{show(){u(this.$refs.modal_uplader).modal("show")},close(){u(this.$refs.modal_uplader).modal("hide")},previewTemplate(){var e=`
  	  	  <div class="col-lg-3 col-md-12 mb-4 mb-lg-3">
  	  	     <div class="card">
	  	  	     <div class="image"><img data-dz-thumbnail /></div>
	  	  	     
	  	  	     <div class="p-2 pt-0">
	  	  	     <p class="m-0 name" data-dz-name></p>
	  	  	     <p class="m-0 size" data-dz-size></p>  	
	  	  	     
	  	  	     <div class="progress">
					  <div class="progress-bar" role="progressbar" aria-valuenow="0" 
					  style="width:0%;" data-dz-uploadprogress
					  aria-valuemin="0" aria-valuemax="100"></div>
				 </div>  	     
				 </div>
  	  	     </div> 
  	  	  </div> <!-- col -->
  	  	 `;return e},initDropzone(){var e={YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),upload_path:this.upload_path};this.dropzone=new Dropzone(this.$refs.dropzone,{paramName:"file",maxFilesize:parseInt(this.max_file_size),url:upload_ajaxurl+"/file",maxFiles:this.max_file,params:e,clickable:this.$refs.fileinput,previewsContainer:this.$refs.ref_preview,previewTemplate:this.previewTemplate(),acceptedFiles:"image/*",});this.dropzone.on("addedfile",(e)=>{this.preview=!0;o("added file=>"+e.type);switch(e.type){case "image/jpeg":case "image/png":case "image/svg+xml":case "image/webp":case "image/apng":break;default:this.dropzone.removeFile(e);break}});this.dropzone.on("queuecomplete",(e)=>{o("All files have uploaded ");this.getMedia()});this.dropzone.on("success",(e,t)=>{o("success");t=JSON.parse(t);o(t);if(t.code==2){r(t.msg);this.dropzone.removeFile(e)}})},getMedia(){var e={YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),page:this.page,selected_file:this.selected_multiple_file,item_selected:this.item_selected};var t=f();e=JSON.stringify(e);_[t]=u.ajax({url:upload_ajaxurl+"/getMedia",method:"PUT",dataType:"json",data:e,contentType:i.json,timeout:s,crossDomain:!0,beforeSend:(e)=>{this.is_loading=!0;if(_[t]!=null){_[t].abort()}},}).done((e)=>{this.data_message=e.msg;if(e.code==1){this.data=e.details.data;this.page_count=e.details.page_count;this.current_page=e.details.current_page}else{this.data=[];this.page_count=0;this.current_page=0}}).always((e)=>{this.is_loading=!1})},addMore(){this.preview=!1},pageNum(e){this.page=e;this.getMedia()},pageNext(){this.page=parseInt(this.page)+1;if(this.page>=this.page_count){this.page=this.page_count}
this.getMedia()},pagePrev(){this.page=parseInt(this.page)-1;o(this.page+"=>"+this.page_count);if(this.page<=1){this.page=1}
this.getMedia()},itemSelect(a,e){a.is_selected=!a.is_selected;if(this.select_type=="single"){this.removeAllSelected(a.id);if(a.is_selected){this.item_selected[0]={filename:a.filename,image_url:a.image_url,path:a.path}}else{this.item_selected.splice(0,1)}}else{if(a.is_selected){this.item_selected.push({filename:a.filename,image_url:a.image_url,path:a.path})}else{this.item_selected.forEach((e,t)=>{if(e.filename==a.filename){this.item_selected.splice(t,1)}})}}},removeAllSelected(a){this.data.forEach((e,t)=>{if(e.id!=a){e.is_selected=!1}})},addFiles(){var a=[];this.item_selected.forEach((e,t)=>{a[t]={id:e.id,filename:e.filename,image_url:e.image_url,path:e.path}});this.added_files=a;this.close()},removeAddedFiles(e){this.added_files.splice(e,1)},getMediaSeleted(){if(h(this.selected_file)){return}
var e={YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),selected_file:this.selected_file,save_path:this.save_path};var t=f();e=JSON.stringify(e);_[t]=u.ajax({url:upload_ajaxurl+"/getMediaSeleted",method:"PUT",dataType:"json",data:e,contentType:i.json,timeout:s,crossDomain:!0,beforeSend:(e)=>{if(_[t]!=null){_[t].abort()}},}).done((e)=>{if(e.code==1){this.added_files=e.details}else{}})},getMediaMultipleSeleted(){if(h(this.selected_multiple_file)){return}
var e={YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),selected_file:this.selected_multiple_file,save_path:this.save_path};var t=f();e=JSON.stringify(e);_[t]=u.ajax({url:upload_ajaxurl+"/getMediaMultipleSeleted",method:"PUT",dataType:"json",data:e,contentType:i.json,timeout:s,crossDomain:!0,beforeSend:(e)=>{if(_[t]!=null){_[t].abort()}},}).done((e)=>{if(e.code==1){this.added_files=e.details;var a=[];this.added_files.forEach((e,t)=>{a[t]={filename:e.filename,image_url:e.image_url}});this.item_selected=a}else{this.added_files=[];this.item_selected=[]}})},clearData(){this.q="";this.getMedia()},beforeDeleteFiles(){a.confirm().then((e)=>{if(e){this.deleteFiles()}})},deleteFiles(){var e={YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),item_selected:this.item_selected};var t=f();e=JSON.stringify(e);_[t]=u.ajax({url:upload_ajaxurl+"/deleteFiles",method:"PUT",dataType:"json",data:e,contentType:i.json,timeout:s,crossDomain:!0,beforeSend:(e)=>{this.is_loading=!0;if(_[t]!=null){_[t].abort()}},}).done((e)=>{if(e.code==1){this.item_selected=[];this.getMedia()}else{a.alert(e.msg,{})}}).always((e)=>{this.is_loading=!1})},clearSelected(e){if(this.item_selected.length>0){this.item_selected=[];e.forEach((e,t)=>{e.is_selected=!1})}else{if(this.select_type=="multiple"){var a=[];e.forEach((e,t)=>{e.is_selected=!0;a[t]={filename:e.filename,image_url:e.image_url}});this.item_selected=a}}},},template:`   

   
   <!---
   <div v-if="inline=='false'" class="input-group">  
	  <div class="custom-file">         
	     <label @click="show" class="custom-file-label image_label" for="upload_image">
	     {{label.upload_button}}
	     </label>  
	  </div>      	  
   </div>
   -->
   

   <div v-if="inline=='false'" class="mb-2">
     <div class="border bg-white  rounded">	 
	    <div class="row justify-content-between align-items-center">
		  <div class="col-4 ml-3">{{label.upload_button}}</div>
		  <div class="col-4 text-right">
		     <button @click="show"  type="button" class="btn btn-info" style="padding:.375rem .75rem;">
			   <template v-if="label.browse">{{label.browse}}</template>
			   <template v-else>Browse</template>
			 </button>
		  </div>
		</div>
	 </div>
   </div>
         
   <template v-for="item in added_files" >
      <template v-if="select_type=='single'">
        <input :name="field" type="hidden" :value="item.filename" />
        <input :name="field_path" type="hidden" :value="item.path" />
      </template>
      <template v-else >
        <input :name="field+'[]'" type="hidden" :value="item.filename" />
        <input :name="field_path+'[]'" type="hidden" :value="item.path" />
      </template>
   </template>
            
   <div v-if="hasAddedFiles" class="file_added_container pr-2">   
	   <div class="row pt-3">	   
	   <div v-for="(item, index) in added_files" class="col-md-2 mb-3 position-relative">
	     <a @click="removeAddedFiles(index)" class="btn-remove btn btn-black btn-circle" href="javascript:;" >
		  <i class="zmdi zmdi-close"></i>
		 </a>  	  		 
          <img class="rounded" :src="item.image_url" />
	   </div>
	   </div>
   </div>
   <!--  file_added_container  -->
          
    <div ref="modal_uplader" :class="{'modal fade':this.inline=='false'}" 
    id="modalUploader" data-backdrop="static" 
    tabindex="-1" role="dialog" aria-labelledby="modalUploader" aria-hidden="true">
    
	   <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered" role="document">
	     <div class="modal-content"> 
	     
	       <div class="modal-header pb-1 bg-light">	        
	        <ul class="nav nav-pills">
	          <li class="nav-item">
			    <a @click="tab=1" href="javascript:;" class="nav-link" :class="{ 'active': tab==1 }" >
			    {{label.select_file}}
			    </a>
			  </li>
			  <li class="nav-item">
			    <a @click="tab=2" href="javascript:;" class="nav-link" :class="{ 'active': tab==2 }"  >
			    {{label.upload_new}}
			    </a>
			  </li>
	        </ul>
	       
	        <button v-if="inline=='false'" type="button" class="close"  aria-label="Close" @click="close" >
	          <span aria-hidden="true" style="font-size:1.5rem;">&times;</span>
	        </button>
	      </div> 
	     
	       <div class="modal-body">	 

	       <!-- file list wrapper  -->
	       <template v-if="tab=='1'" >
	       
	       <div class="row">
			  <div class="col">
				 <button type="button" class="send btn-upload-count" 
				 @click="clearSelected(data)"
				 :class="{selected : item_selected.length>0}"
				 :data-counter="totalSelected">&#10004;</button>
			  </div>
			  <div class="col">
				<div class="form-group has-search">
				  <span v-if="!awaitingSearch" class="fa fa-search form-control-feedback"></span>
				  <span v-if="awaitingSearch" class="img-15 form-control-feedback" data-loader="circle"></span>
				  <div  v-if="hasSearch"  @click="clearData" class="img-15 clear_data">
				    <i class="zmdi zmdi-close"></i>
				  </div>
				  <input v-model="q" type="text" class="form-control" :placeholder="label.search" >
				</div>
			  </div>
			</div>
						
			
			<DIV class="file_wrapper">	
			
             <div v-if="is_loading" class="cover-loader d-flex align-items-center justify-content-center">
	            <div>
	              <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	            </div>
	         </div>
	         	         
	         <div v-if="noFiles" class="d-flex justify-content-center align-items-center file_upload_inner">
	           <div class="text-center">
	             <h5>{{data_message}}</h5>	             
	           </div>
	         </div> 	         
             	 	
			 <ul class="list-unstyled">			  
			  <li v-for="item in data"
			   :class="{ selected: item.is_selected }"	     
			   @click="itemSelect(item,index)"
			   >
			    <img :src="item.image_url" />
			    <p class="m-0"><strong>{{item.title}}</strong></p>
			    <p class="m-0"><small>{{item.size}}</small></p>
			  </li>			  
			</ul>
			
			</DIV>
			<!-- file_wrapper -->
	       
	       </template>	       
	       <!-- end file list wrapper  -->
	       
	       <!-- file_preview_container -->
	       
	       <div :class="{'d-block': tab=='2' }" class="file_upload_container rounded position-relative">
	         <div ref="dropzone" class="d-flex justify-content-center align-items-center file_upload_inner">
	           <div class="text-center">
			      <h5>{{label.drop_files}} <br/> {{label.or}}</h5>
	             <a ref="fileinput" class="btn btn-green fileinput-button" href="javascript:;">
				 {{label.select_files}}
				 </a>
	           </div>
	         </div> 	         
	         
	         <!-- file_preview_container -->
	         <div :class="{ 'd-block': preview }" class="file_preview_container">	 
		          <nav class="navbar bg-light d-flex justify-content-end">
		             <button @click="addMore" type="button" class="btn">
					 + 
					 <template v-if="label.add_more">
					    {{label.add_more}}
					 </template>
					 <template v-else>
					    Add more
					 </template>
					 </button>
		          </nav>
		          
		          <div ref="ref_preview" class="row p-2">		          		            
		          </div> <!-- row -->
		          
	         </div>
	         <!-- file_preview_container -->
	         
	       </div>
	       
	       <!-- end file_upload_container -->
	       
	       </div> <!--modal body-->
           
	      <div class="modal-footer justify-content-start">
	        <div class="row no-gutters w-100">
	          <div class="col">
	          	          
	           <!-- current page {{current_page}} page {{page}}  page_count {{page_count}} -->
	           <nav aria-label="Page navigation" v-if="hasData" >
				  <ul class="pagination">
				  
				    <li class="page-item" :class="{disabled: current_page=='1'}" >
				      <a @click="pagePrev()" class="page-link" href="javascript:;">{{label.previous}}</a>
				    </li>				    
				    
				    <!--
				    <li v-for="n in page_count" class="page-item" :class="{ active: current_page==n }" >
				      <a @click="pageNum(n)" class="page-link" href="javascript:;">{{n}}</a>
				    </li>
				    -->
				    
				    <li class="page-item" :class="{disabled: page_count==current_page}">
				       <a @click="pageNext()" class="page-link" href="javascript:;">{{label.next}}</a>
				    </li>
				  </ul>
				</nav>
	          
	          </div> <!-- col -->
	          <div class="col text-right">
	           
	           <template v-if="inline=='false'" >
		           <button @click="addFiles" type="button" class="btn btn-green" :disabled="!hasSelected" >  
	                <span class="label">{{label.add_file}}</span>                 
	               </button>
               </template>
               <template v-else>
                  <button @click="beforeDeleteFiles" type="button" class="btn btn-green" :disabled="!hasSelected" >  
	                <span class="label">{{label.delete_file}}</span>                 
	               </button>
               </template>
	          
	          </div>
	        </div> <!-- row -->
          </div> <!--footer-->
	       
	    </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->     	       
   `,};const ne=Vue.createApp({components:{"component-uploader":de},data(){return{data:[]}},mounted(){},methods:{},}).mount("#vue-uploader");const ce={props:["order_status","ajax_url","label","show_critical","show_status","schedule"],data(){return{error:[],is_loading:!1,data:[],meta:[],status:[],services:[],total:0,order_uuid:"",order_type:"",response_code:0,count_up:undefined}},mounted(){this.getList()},methods:{getList(e){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/orderList",data:{order_status:this.order_status,schedule:this.schedule,YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),filter:e},timeout:s}).then((e)=>{this.response_code=e.data.code;if(e.data.code==1){this.data=e.data.details.data;this.total=e.data.details.total;this.meta=e.data.details.meta;this.status=e.data.details.status;this.services=e.data.details.services;this.order_uuid=e.data.details.data[0].order_uuid;this.order_type=e.data.details.data[0].service_code;this.$emit("afterSelect",this.order_uuid,this.order_type)}else{this.error=e.data.msg;this.data=[];this.meta=[];this.status=[];this.services=[];this.total=0;this.$emit("afterSelect","")}
var t=new countUp.CountUp(this.$refs.total,this.total,{decimalPlaces:0,separator:",",decimal:"."});t.start()})["catch"]((e)=>{}).then((e)=>{this.updateScroll();this.is_loading=!1})},updateScroll(){setTimeout(function(){u(".nice-scroll").getNiceScroll().resize()},100)},select(e){this.order_uuid=e.order_uuid;this.order_type=e.service_code;e.is_view=1;this.$emit("afterSelect",e.order_uuid,this.order_type)},},template:`			
	
	<div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
	    <div>
	      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	    </div>
	</div>
	
	<div class="make-sticky d-flex align-items-center justify-content-between bg-white">
	    <div><h5 class="head m-0">{{label.title}}</h5></div>
	    <div>
	      <div ref="total" class="ronded-green">0</div>
	    </div>
    </div> 
    
    <template v-if="response_code==1">
	<ul class="list-unstyled m-0 grey-list-chevron">
    <li v-for="(item, index) in data" class="chevron" 
    :class="{selected:item.order_uuid == order_uuid}" @click="select(item)" >
    
    <div class="row align-items-start">      
      <div class="col">
        <div class="d-flex justify-content-between align-items-center">
         <div><p class="m-0" v-if="meta[item.order_id]"><b>{{meta[item.order_id].customer_name}}</b></p></div>
         <div>
         <span v-if="status[item.status]" class="ml-2 badge" 
          :style="{background:status[item.status].background_color_hex,color:status[item.status].font_color_hex}"
          >          
          {{status[item.status].status}} 
         </span>  
         <span v-else class="ml-2 badge badge-info"  >
           {{item.status}}
         </span>
         </div>
        </div>
        <p class="m-0">{{item.total_items}}
        
         <span v-if="services[item.service_code]" class="ml-2 badge services" 
         :style="{background:services[item.service_code].background_color_hex,color:services[item.service_code].font_color_hex}"
          >          
          {{services[item.service_code].service_name}} 
         </span>      
         <span v-else class="ml-2 badge badge-info"  >
           {{item.service_code}}
         </span>
        
        </p>
        
        <div class="d-flex align-items-center">
          <div v-if="item.is_view==0" class="mr-1"><div class="blob green"></div></div>
          <div><p class="m-0">{{item.order_name}}</p></div>
        </div>
        
        <div class="d-flex align-items-center">
          <template v-if="show_critical">
          <div v-if="item.is_critical==1" class="mr-1"><div class="blob red"></div></div>
          </template>
          <div><p class="m-0"><u>{{item.delivery_date}}</u></p></div>
        </div>        
      </div> <!--col-->
    </div>
    <hr class="m-0">
   </li>    
   </ul>
   </template>
   <template v-else>
    <div class="fixed-height40 text-center justify-content-center d-flex align-items-center">
    
    <div v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
	    <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
	 </div>      
    
    </div>
   </template>
	`,};const me={props:["ajax_url","label","order_uuid"],data(){return{data:[],reason:"",resolvePromise:undefined,rejectPromise:undefined}},computed:{hasData(){if(!h(this.reason)){return!0}
return!1},},mounted(){this.orderRejectionList();autosize(this.$refs.reason)},methods:{confirm(){u(this.$refs.rejection_modal).modal("show");return new Promise((e,t)=>{this.resolvePromise=e;this.rejectPromise=t})},close(){u(this.$refs.rejection_modal).modal("hide")},orderRejectionList(){axios({method:"put",url:this.ajax_url+"/orderRejectionList",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content")},timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details}else{this.data=[]}})["catch"]((e)=>{}).then((e)=>{})},submit(){this.close();this.resolvePromise(this.reason)},},template:`	
	<div ref="rejection_modal" class="modal" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{label.title}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            
      <form @submit.prevent="submit" >
        <div class="form-label-group mt-2"> 
        <textarea ref="reason" v-model="reason" id="reason" class="form-control form-control-text" :placeholder="label.reason">
        </textarea>
        </div>
                        
        <div class="list-group list-group-flush">
         <a v-for="item in data" @click="reason=item" 
         :class="{active:reason==item}"
         class="text-center list-group-item list-group-item-action">
         {{item}}
         </a>
        </div>
        
      </form>
      
      </div>      
      <div class="modal-footer">            
        <button type="button" @click="submit" class="btn btn-green pl-4 pr-4" :class="{ loading: is_loading }"         
         :disabled="!hasData"
         >
          <span>{{label.reject_order}}</span>
          <div class="m-auto circle-loader" data-loader="circle-side"></div> 
        </button>
      </div>
      
    </div>
  </div>
</div>            
	`,};const ue={props:["ajax_url","label","order_uuid"],data(){return{amount:0,refund_type:"full",resolvePromise:undefined,rejectPromise:undefined,is_loading:!1,data:[]}},methods:{confirm(e){this.data=e;this.refund_type=e.refund_type;u(this.$refs.refund_modal).modal("show");return new Promise((e,t)=>{this.resolvePromise=e;this.rejectPromise=t})},close(){u(this.$refs.refund_modal).modal("hide")},submit(){this.close();this.resolvePromise(!0)},},template:`	
	
	<div ref="refund_modal" class="modal" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{label.title}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">                
        <p>{{label.refund_full}} {{data.pretty_total}}</p>        
      </div> <!-- body -->
      
       <div class="modal-footer">
          <button type="buttton" class="btn btn-black" data-dismiss="modal" aria-label="Close" >
          <span class="pl-2 pr-2" >{{label.cancel}}</span>
          </button>
          <button type="button" @click="submit" class="btn btn-green pl-4 pr-4" :class="{ loading: is_loading }"           
          >
          <span>{{label.refund}}</span>
          <div class="m-auto circle-loader" data-loader="circle-side"></div> 
        </button>
      </div>
      
      </div>
     </div>
     </div>      
	`,};const he={props:["message","donnot_close"],data(){return{new_message:""}},methods:{show(){u(this.$refs.modal).modal("show")},close(){u(this.$refs.modal).modal("hide")},setMessage(e){this.new_message=e},},template:`
	<div class="modal" ref="modal"  tabindex="-1" role="dialog"  aria-hidden="true"
	data-backdrop="static" data-keyboard="false" 	 
	 >
	   <div class="modal-dialog modal-dialog-centered modal-sm modal-loadingbox" role="document">
	     <div class="modal-content">
	         <div class="modal-body">
	            <div class="loading mt-2">
	              <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	            </div>
	            <p class="text-center mt-2">
	              <div v-if="!new_message">{{message}}</div>
	              <div v-if="new_message">{{new_message}}</div>
	              <div>{{donnot_close}}</div>
	            </p>	            
	         </div>
	       </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->      
	`,};const _e={props:["ajax_url","group_name","refund_label","remove_item","out_stock_label","manual_status","modify_order","update_order_label","filter_buttons","enabled_delay_order"],components:{"components-rejection-forms":me,"components-refund-forms":ue,"components-loading-box":he},data(){return{is_loading:!1,loading:!0,order_uuid:"",merchant:[],order_info:[],promo:[],items:[],order_summary:[],summary_changes:[],summary_transaction:[],summary_total:0,merchant_direction:"",delivery_direction:"",order_status:[],services:[],payment_status:[],response_code:0,customer:[],buttons:[],status_data:[],stats_id:"",sold_out_options:[],out_stock_options:"",item_row:[],additional_charge:0,additional_charge_name:"",customer_name:"",contact_number:"",delivery_address:"",latitude:"",longitude:"",error:[],link_pdf:[],payment_history:[],screen_size:{width:0,height:0},show_as_popup:!1,load_count:0,credit_card_details:[],driver_data:[],zone_list:[],merchant_zone:[],delivery_status:[],order_table_data:[],}},computed:{hasData(){if(this.stats_id>0){return!0}
return!1},outStockOptions(){if(this.out_stock_options>0){return!0}
return!1},hasValidCharge(){if(this.additional_charge>0){return!0}
return!1},refundAvailable(){if(this.order_info.payment_status=="paid"){return!0}
return!1},hasRefund(){if(this.summary_changes){if(this.summary_changes.method==="total_decrease"){if(this.summary_changes.refund_due>0){return!0}}}
return!1},hasAmountToCollect(){if(this.summary_changes){if(this.summary_changes.method==="total_increase"){if(this.summary_changes.refund_due>0){return!0}}}
return!1},hasTotalDecrease(){if(this.summary_changes){if(this.summary_changes.method==="total_decrease"){return!0}}
return!1},hasTotalIncrease(){if(this.summary_changes){if(this.summary_changes.method==="total_increase"){return!0}}
return!1},summaryTransaction(){if(this.summary_transaction){if(typeof this.summary_transaction.summary_list!=="undefined"&&this.summary_transaction.summary_list!==null){if(this.summary_transaction.summary_list.length>0){return!0}}}
return!1},hasInvoiceUnpaid(){if(this.summary_changes.unpaid_invoice){return!0}
if(this.summary_changes.paid_invoice){return!0}
return!1},hasBooking(){if(Object.keys(this.order_table_data).length>0){return!0}
return!1},},watch:{load_count(e,t){if(typeof N!=="undefined"&&N!==null){if(e>=2){if(this.screen_size.width<=576){N.show_as_popup=!0}else{N.show_as_popup=!1}}else{N.show_as_popup=!1}}},},mounted(){this.getOrderStatusList();this.handleResize();window.addEventListener("resize",this.handleResize)},methods:{handleResize(){this.screen_size.width=window.innerWidth;this.screen_size.height=window.innerHeight},orderDetails(e,t){this.order_uuid=e;this.is_loading=!0;this.loading=!0;var a=["payment_history","print_settings","buttons"];axios({method:"put",url:this.ajax_url+"/orderDetails",data:{order_uuid:this.order_uuid,group_name:this.group_name,YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),payload:a,modify_order:this.modify_order,filter_buttons:this.filter_buttons},timeout:s,}).then((e)=>{this.load_count++;this.response_code=e.data.code;if(e.data.code==1){this.merchant=e.data.details.data.merchant;this.order_info=e.data.details.data.order.order_info;this.promo = e.data.details.data.order.promo;this.driver_data=e.data.details.data.driver_data;this.zone_list=e.data.details.data.zone_list;this.merchant_zone=e.data.details.data.merchant_zone;this.order_table_data=e.data.details.data.order_table_data;this.customer_name=this.order_info.customer_name;this.contact_number=this.order_info.contact_number;this.delivery_address=this.order_info.delivery_address;this.latitude=this.order_info.latitude;this.longitude=this.order_info.longitude;this.customer=e.data.details.data.customer;if(typeof N!=="undefined"&&N!==null){N.client_id=this.customer.client_id}
this.order_status=e.data.details.data.order.status;this.services=e.data.details.data.order.services;this.payment_status=e.data.details.data.order.payment_status;this.delivery_status=e.data.details.data.order.delivery_status;this.items=e.data.details.data.items;this.order_summary=e.data.details.data.summary;this.summary_total=e.data.details.data.summary_total;this.summary_changes=e.data.details.data.summary_changes;this.summary_transaction=e.data.details.data.summary_transaction;this.merchant_direction="https://www.google.com/maps/dir/?api=1&destination=";this.merchant_direction+=this.merchant.latitude+",";this.merchant_direction+=this.merchant.longitude;this.delivery_direction=this.order_info.delivery_direction;this.buttons=e.data.details.data.buttons;this.sold_out_options=e.data.details.data.sold_out_options;this.link_pdf=e.data.details.data.link_pdf;this.payment_history=e.data.details.data.payment_history;this.credit_card_details=e.data.details.data.credit_card_details}else{this.merchant_direction="";this.delivery_direction="";this.merchant=[];this.order_info=[];this.promo=[];this.items=[];this.order_summary=[];this.buttons=[];this.sold_out_options=[];this.link_pdf=[];this.payment_history=[];this.credit_card_details=[];this.driver_data=[]}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1;this.loading=!1})},doUpdateOrderStatus(t,a,e){o("do_actions=>"+e);console.log("summary_changes=>"+this.summary_changes.method);if(e=="reject_form"){this.$refs.rejection.confirm().then((e)=>{if(e){o("rejection reason =>"+e);this.updateOrderStatus(t,a,e)}})}else{var s="";if(this.summary_changes.method=="total_decrease"){var s=this.update_order_label.content;s=s.replace("{{amount}}",this.summary_changes.refund_due_pretty);bootbox.confirm({size:"small",title:"",message:"<h5>"+this.update_order_label.title+"</h5>"+"<p>"+s+"</p>",centerVertical:!0,animate:!1,buttons:{cancel:{label:this.update_order_label.cancel,className:"btn btn-black small pl-4 pr-4"},confirm:{label:this.update_order_label.confirm,className:"btn btn-green small pl-4 pr-4"}},callback:(e)=>{if(e){this.createRefund(t,a)}},})}else if(this.summary_changes.method=="total_increase"){var s=this.update_order_label.content_collect;s=s.replace("{{amount}}",this.summary_changes.refund_due_pretty);if(this.summary_changes.unpaid_invoice){var i=bootbox.dialog({title:"",message:"<h5>"+this.update_order_label.title_increase+"</h5>"+"<p>"+this.update_order_label.content_payment+"</p>",size:"medium",centerVertical:!0,buttons:{cancel:{label:this.update_order_label.close,className:"btn btn-black small pl-4 pr-4",callback:()=>{}}},})}else if(this.summary_changes.paid_invoice){this.updateOrderStatus(t,a)}else{var i=bootbox.dialog({title:"",message:"<h5>"+this.update_order_label.title_increase+"</h5>"+"<p>"+s+"</p>",size:"medium",centerVertical:!0,buttons:{cancel:{label:this.update_order_label.cancel,className:"btn btn-black small pl-4 pr-4",callback:()=>{}},account:{label:this.update_order_label.less_acccount,className:"btn btn-yellow small",callback:()=>{this.AcceptOrder(t,a,"lessOnAccount")},},ok:{label:this.update_order_label.send_invoice,className:"btn btn-green small pl-4 pr-4",callback:()=>{this.AcceptOrder(t,a,"createInvoice")},},},})}}else if(this.summary_changes.method=="less_on_account"){var s=this.update_order_label.collect_cash;s=s.replace("{{amount}}",se(parseFloat(this.order_info.commission)));var i=bootbox.dialog({title:"",message:"<h5>Accept Order</h5>"+"<p>"+s+"</p>",size:"medium",centerVertical:!0,buttons:{cancel:{label:this.update_order_label.cancel,className:"btn btn-black small pl-4 pr-4",callback:()=>{}},ok:{label:this.update_order_label.less_acccount,className:"btn btn-green small pl-4 pr-4",callback:()=>{this.AcceptOrder(t,a,"lessCashOnAccount")},},},})}else{this.updateOrderStatus(t,a)}}},updateOrderStatus(e,t,a){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/updateOrderStatus",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),uuid:e,order_uuid:t,reason:a},timeout:s}).then((e)=>{if(e.data.code==1){this.$emit("afterUpdate")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},createRefund(e,t){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/createRefund",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),uuid:e,order_uuid:t},timeout:s}).then((e)=>{if(e.data.code==1){this.$emit("afterUpdate")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},AcceptOrder(e,t,a){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/"+a,data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),uuid:e,order_uuid:t},timeout:s}).then((e)=>{if(e.data.code==1){this.$emit("afterUpdate")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},getOrderStatusList(){axios({method:"put",url:this.ajax_url+"/getOrderStatusList",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content")},timeout:s}).then((e)=>{if(e.data.code==1){this.status_data=e.data.details}else{this.status_data=!1}})["catch"]((e)=>{}).then((e)=>{})},manualStatusList(e){this.stats_id="";u(this.$refs.manual_status_modal).modal("show")},confirm(){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/updateOrderStatusManual",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:this.order_uuid,stats_id:this.stats_id},timeout:s}).then((e)=>{if(e.data.code==1){u(this.$refs.manual_status_modal).modal("hide");this.$emit("afterUpdate")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},cancelOrder(){this.$refs.rejection.confirm().then((e)=>{if(e){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/cancelOrder",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:this.order_uuid,reason:e},timeout:s}).then((e)=>{if(e.data.code==1){this.$emit("afterUpdate");r(e.data.msg)}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})}})},delayOrder(){this.$emit("delayOrderform",this.order_uuid)},contactCustomer(){a.alert(this.order_info.contact_number,{size:"small"})},orderHistory(){this.$emit("order-history",this.order_uuid)},markItemOutStock(e){this.item_row=e;u(this.$refs.out_stock_modal).modal("show")},setOutOfStocks(){u(this.$refs.out_stock_modal).modal("hide");bootbox.confirm({size:"medium",title:"",message:"<h5>"+this.out_stock_label.title+"</h5>"+"<p>"+this.refund_label.content+"</p>",centerVertical:!0,animate:!1,buttons:{cancel:{label:this.refund_label.go_back,className:"btn btn-black small pl-4 pr-4"},confirm:{label:this.refund_label.complete,className:"btn btn-green small pl-4 pr-4"}},callback:(e)=>{if(e){this.itemChanges("out_stock")}else{u(this.$refs.out_stock_modal).modal("show")}},})},itemChanges(t){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/itemChanges",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:this.order_uuid,item_row:this.item_row.item_row,item_changes:t,out_stock_options:this.out_stock_options},timeout:s,}).then((e)=>{if(e.data.code==1){switch(t){default:u(this.$refs.out_stock_modal).modal("hide");this.orderDetails(this.order_uuid,!0);break}}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},adjustOrder(e){this.item_row=e;u(this.$refs.adjust_order_modal).modal("show")},refundItem(){u(this.$refs.adjust_order_modal).modal("hide");bootbox.confirm({size:"medium",title:"",message:"<h5>"+this.refund_label.title+"</h5>"+"<p>"+this.refund_label.content+"</p>",centerVertical:!0,animate:!1,buttons:{cancel:{label:this.refund_label.go_back,className:"btn btn-black small pl-4 pr-4"},confirm:{label:this.refund_label.complete,className:"btn btn-green small pl-4 pr-4"}},callback:(e)=>{if(e){this.doItemRefund()}else{u(this.$refs.adjust_order_modal).modal("show")}},})},doItemRefund(){this.itemChanges("refund")},cancelEntireOrder(){u(this.$refs.adjust_order_modal).modal("hide");this.cancelOrder()},removeItem(){u(this.$refs.adjust_order_modal).modal("hide");bootbox.confirm({size:"medium",title:"",message:"<h5>"+this.remove_item.title+"</h5>"+"<p>"+this.remove_item.content+"</p>",centerVertical:!0,animate:!1,buttons:{cancel:{label:this.remove_item.go_back,className:"btn btn-black small pl-4 pr-4"},confirm:{label:this.remove_item.confirm,className:"btn btn-green small pl-4 pr-4"}},callback:(e)=>{if(e){this.doRemoveItem()}else{u(this.$refs.adjust_order_modal).modal("show")}},})},doRemoveItem(){this.itemChanges("remove")},additionalCharge(e){this.item_row=e;u(this.$refs.additional_charge_modal).modal("show")},doAdditionalCharge(){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/additionalCharge",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:this.order_uuid,item_row:this.item_row.item_row,additional_charge:this.additional_charge,additional_charge_name:this.additional_charge_name,},timeout:s,}).then((e)=>{if(e.data.code==1){u(this.$refs.additional_charge_modal).modal("hide");this.orderDetails(this.order_uuid,!0)}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},updateOrderSummary(){axios({method:"put",url:this.ajax_url+"/updateOrderSummary",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:this.order_uuid},timeout:s}).then((e)=>{if(e.data.code==1){}else{}})["catch"]((e)=>{}).then((e)=>{})},replaceItem(){u(this.$refs.adjust_order_modal).modal("hide");this.$emit("showMenu",this.item_row)},editOrderInformation(){u(this.$refs.update_info_modal).modal("show")},updateOrderDeliveryInformation(){this.is_loading=!0;this.error=[];axios({method:"put",url:this.ajax_url+"/updateOrderDeliveryInformation",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:this.order_uuid,customer_name:this.customer_name,contact_number:this.contact_number,delivery_address:this.delivery_address,latitude:this.latitude,longitude:this.longitude,},timeout:s,}).then((e)=>{if(e.data.code==1){r(e.data.msg);u(this.$refs.update_info_modal).modal("hide");this.$emit("refreshOrder",this.order_uuid)}else{this.error=e.data.details}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},showCustomer(){this.$emit("viewCustomer")},printOrder(){this.$emit("to-print",this.order_uuid)},refundFull(){var e={refund_type:"full",order_uuid:this.order_info.order_uuid,total:this.order_info.total,pretty_total:this.order_info.pretty_total};this.$refs.refund.confirm(e).then((e)=>{o(e)})},refundPartial(){o("refundPartial")},updateOrder(){o(this.summary_changes.method);if(this.summary_changes.method=="total_decrease"){var e=this.update_order_label.content;e=e.replace("{{amount}}",this.summary_changes.refund_due_pretty);bootbox.confirm({size:"small",title:"",message:"<h5>"+this.update_order_label.title+"</h5>"+"<p>"+e+"</p>",centerVertical:!0,animate:!1,buttons:{cancel:{label:this.update_order_label.cancel,className:"btn btn-black small pl-4 pr-4"},confirm:{label:this.update_order_label.confirm,className:"btn btn-green small pl-4 pr-4"}},callback:(e)=>{if(e){o(e)}},})}},FPprint(e){this.$refs.loading_box.show();axios({method:"put",url:this.ajax_url+"/FPprint",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:this.order_uuid,printer_id:e},timeout:s}).then((e)=>{if(e.data.code==1){ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"success"})}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"]((e)=>{}).then((e)=>{this.$refs.loading_box.close()})},},template:"#xtemplate_order_details",};const pe={props:["ajax_url","label","order_uuid"],data(){return{data:[],is_loading:!1,time_delay:""}},mounted(){this.getDelayedMinutes()},computed:{hasData(){if(!h(this.time_delay)){return!0}
return!1},},methods:{show(){this.time_delay="";u(this.$refs.delay_modal).modal("show")},close(){u(this.$refs.delay_modal).modal("hide")},getDelayedMinutes(){axios({method:"put",url:this.ajax_url+"/getDelayedMinutes",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content")},timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details}else{this.data=[]}})["catch"]((e)=>{}).then((e)=>{})},confirm(){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/setDelayToOrder",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),time_delay:this.time_delay,order_uuid:this.order_uuid},timeout:s}).then((e)=>{if(e.data.code==1){this.close();r(e.data.msg)}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},template:`	
	<div ref="delay_modal" class="modal" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{label.title}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
       <p class="m-0">{{label.sub1}}</p>
       <p class="m-0">{{label.sub2}}</p>
                     
       <div class="w-75 m-auto">
       <div class="row mt-4">
         <div v-for="item in data" class="col-lg-4 col-md-4 col-sm-6 col-4  mb-2">
           <button 
           :class="{active:time_delay==item.id}" 
           @click="time_delay=item.id"
           class="btn btn-light delay-btn">
           {{item.value}}
           </button>
         </div>
       </div>
       </div>
      
       </div>      
      <div class="modal-footer">            
        <button type="button" @click="confirm" class="btn btn-green pl-4 pr-4" :class="{ loading: is_loading }"         
         :disabled="!hasData"
         >
          <span>{{label.confirm}}</span>
          <div class="m-auto circle-loader" data-loader="circle-side"></div> 
        </button>
      </div>
      
    </div>
  </div>
</div>        
	`,};const fe={props:["ajax_url","label","order_uuid"],data(){return{is_loading:!1,data:[],order_status:[],error:[]}},methods:{show(){this.data=[];this.order_status=[];u(this.$refs.history_modal).modal("show");this.getHistory()},close(){u(this.$refs.history_modal).modal("hide")},getHistory(){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/getOrderHistory",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:this.order_uuid},timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details.data;this.order_status=e.data.details.order_status;this.error=[]}else{this.error=e.data.msg;this.data=[];this.order_status=[]}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},template:`	
	<div ref="history_modal" class="modal" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{label.title}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body position-relative">
      
      <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
	    <div>
	      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	    </div>
	  </div>
      
	  <ul class="timeline m-0 p-0 pl-5">
        <li  v-for="item in data" >
          <div class="time">{{item.created_at}}</div>
           <p v-if="order_status[item.status]" class="m-0">{{order_status[item.status]}}</p>
	       <p v-else class="m-0">{{item.status}}</p>
	       <p class="m-0 text-muted">{{item.remarks}}</p>
	       <p v-if="item.change_by" class="m-0 text-muted">{{item.change_by}}</p>
        </li>        
      </ul>
	  
	  <div id="error_message" v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
        <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
      </div>  
      
      </div>      
      <div class="modal-footer">            
        <button type="button" class="btn btn-green pl-4 pr-4"  data-dismiss="modal">
          <span>{{label.close}}</span>          
        </button>
      </div>
      
    </div>
  </div>
</div>        
	`,};const b={props:["ajax_url","label","image_placeholder","merchant_id","responsive"],data(){return{is_loading:!1,category_list:[],active_category:"all",item_list:[],observer:undefined,total_results:"",current_page:0,page_count:0,page:0,awaitingSearch:!1,q:"",owl:undefined,replace_item:[],}},mounted(){this.getCategory();setTimeout(()=>{this.categoryItem(0)},500);this.observer=lozad(".lozad",{loaded:function(e){e.classList.add("loaded")},})},updated(){this.observer.observe()},watch:{q(e,t){if(!this.awaitingSearch){if(h(e)){return!1}
setTimeout(()=>{axios({method:"put",url:this.ajax_url+"/categoryItem",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_id:this.merchant_id,cat_id:0,page:0,q:this.q},timeout:s}).then((e)=>{if(e.data.code==1){this.item_list=e.data.details.data;this.total_results=e.data.details.total_records;this.current_page=e.data.details.current_page;this.page_count=e.data.details.page_count}else{this.item_list=[];this.current_page=0;this.page_count=0;this.total_results=e.data.msg}})["catch"]((e)=>{}).then((e)=>{this.awaitingSearch=!1})},1e3)}
this.item_list=[];this.awaitingSearch=!0},},methods:{show(){this.q="";u(this.$refs.menu_modal).modal("show")},close(){u(this.$refs.menu_modal).modal("hide")},getCategory(){axios({method:"put",url:this.ajax_url+"/getCategory",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_id:this.merchant_id},timeout:s}).then((e)=>{if(e.data.code==1){this.category_list=e.data.details.data.category}else{this.category_list=[]}})["catch"]((e)=>{}).then((e)=>{})},pageNext(){this.page=parseInt(this.page)+1;if(this.page>=this.page_count){this.page=this.page_count}
this.categoryItem(this.active_category)},pagePrev(){this.page=parseInt(this.page)-1;if(this.page<=0){this.page=0}
this.categoryItem(this.active_category)},pageWithID(e){this.page=e;this.categoryItem(this.active_category)},categoryItem(e){this.active_category=e;this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/categoryItem",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_id:this.merchant_id,cat_id:e,page:this.page},timeout:s}).then((e)=>{if(e.data.code==1){this.item_list=e.data.details.data;this.total_results=e.data.details.total_records;this.current_page=e.data.details.current_page;this.page_count=e.data.details.page_count}else{this.item_list=[];this.current_page=0;this.page_count=0;this.total_results=e.data.msg}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1;this.RenderCarousel()})},
			itemShow(e){var t={merchant_id:this.merchant_id,item_uuid:e.item_uuid,category_id:e.category_id[0],replace_item:this.replace_item};this.close();this.$emit("showItem",t)},
			RenderCarousel(){this.owl=u(this.$refs.carousel).owlCarousel({nav:!0,dots:!1,responsive:this.responsive})},},template:"#xtemplate_menu",};var y=[];var x=[];var w=[];var k=[];var I;var S=0;const T={components:{"money-format":t},props:["ajax_url","label","image_placeholder","merchant_id","order_type","order_uuid"],data(){return{is_loading:!1,items:[],item_addons:[],item_addons_load:!1,size_id:0,disabled_cart:!0,item_qty:1,item_total:0,add_to_cart:!1,meta:[],special_instructions:"",sold_out_options:[],if_sold_out:"substitute",transaction_type:"",observer:undefined,old_item:[],}},mounted(){u(this.$refs.modal_item_details).on("hide.bs.modal",(e)=>{this.$emit("goBack",this.old_item)});this.observer=lozad(".lozad",{loaded:function(e){o("image loaded");e.classList.add("loaded")},})},updated(){if(this.item_addons_load==!0){this.ItemSummary()}},methods:{show(e){u(this.$refs.modal_item_details).modal("show");this.old_item=e.replace_item;this.viewItem(e)},close(){this.items=[];this.item_addons=[];this.meta=[];u(this.$refs.modal_item_details).modal("hide");this.$emit("goBack",this.old_item)},viewItem(e){var t={YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_id:this.merchant_id,item_uuid:e.item_uuid,cat_id:e.category_id};var a=1;t=JSON.stringify(t);_[a]=u.ajax({url:this.ajax_url+"/getMenuItem",method:"PUT",dataType:"json",data:t,contentType:i.json,timeout:s,crossDomain:!0,beforeSend:(e)=>{this.is_loading=!0;if(_[a]!=null){_[a].abort()}},}).done((e)=>{if(e.code==1){y=e.details.data.items;x=e.details.data.addons;w=e.details.data.addon_items;k=e.details.data.items.item_addons;var t=e.details.data.meta;var i=e.details.data.meta_details;var l={cooking_ref:[],ingredients:[],dish:[]};let s=e.details.data.items.ingredients_preselected;o("ingredients_preselected");o(s);if(!h(t)){u.each(t,function(a,e){u.each(e,function(e,t){if(!h(i[a])){if(!h(i[a][t])){let e=!1;if(a=="ingredients"&&s){e=!0}
l[a].push({meta_id:i[a][t].meta_id,meta_name:i[a][t].meta_name,checked:e})}}})})}
var a=y.price;var r=Object.keys(a)[0];this.item_qty=1;this.items=y;this.size_id=r;this.meta=l;this.getSizeData(r);this.sold_out_options=e.details.sold_out_options}}).always((e)=>{this.is_loading=!1;this.observer.observe()})},setItemSize(e){var t=e.currentTarget.firstElementChild.value;this.size_id=t;this.getSizeData(t)},getSizeData(a){I=[];var s=[];if(!h(k[a])){u.each(k[a],function(e,t){if(!h(x[a])){if(!h(x[a][t])){x[a][t].subcat_id;u.each(x[a][t].sub_items,function(e,t){if(!h(w[t])){s.push({sub_item_id:w[t].sub_item_id,sub_item_name:w[t].sub_item_name,item_description:w[t].item_description,price:w[t].price,pretty_price:w[t].pretty_price,checked:!1,disabled:!1,qty:1,})}});I.push({subcat_id:x[a][t].subcat_id,subcategory_name:x[a][t].subcategory_name,subcategory_description:x[a][t].subcategory_description,multi_option:x[a][t].multi_option,multi_option_min:x[a][t].multi_option_min,multi_option_value:x[a][t].multi_option_value,require_addon:x[a][t].require_addon,pre_selected:x[a][t].pre_selected,sub_items_checked:"",sub_items:s,});s=[]}}})}
this.item_addons=I;this.item_addons_load=!0},ItemSummary(e){S=0;var d=[];var n=[];let c=[];let m=[];if(!h(this.items.price)){if(!h(this.items.price[this.size_id])){var t=this.items.price[this.size_id];if(t.discount>0){S+=this.item_qty*parseFloat(t.price_after_discount)}else S+=this.item_qty*parseFloat(t.price)}}
this.item_addons.forEach((a,s)=>{if(a.require_addon==1){d.push(a.subcat_id)}
if(a.multi_option=="custom"){var i=0;let e=a.multi_option_min;var t=a.multi_option_value;var l=[];var r=[];if(t>0){c.push({subcat_id:a.subcat_id,min:e,max:t})}
a.sub_items.forEach((e,t)=>{if(e.checked==!0){i++;S+=this.item_qty*parseFloat(e.price);n.push(a.subcat_id)}else l.push(t);if(e.disabled==!0){r.push(t)}});m[a.subcat_id]={total:i};if(i>=t){l.forEach((e,t)=>{this.item_addons[s].sub_items[e].disabled=!0})}else{r.forEach((e,t)=>{this.item_addons[s].sub_items[e].disabled=!1})}}else if(a.multi_option=="one"){a.sub_items.forEach((e,t)=>{if(e.sub_item_id==a.sub_items_checked){S+=this.item_qty*parseFloat(e.price);n.push(a.subcat_id)}})}else if(a.multi_option=="multiple"){var l=[];let e=a.multi_option_min;var t=a.multi_option_value;var o=0;if(t>0){c.push({subcat_id:a.subcat_id,min:e,max:t})}
a.sub_items.forEach((e,t)=>{if(e.checked==!0){S+=e.qty*parseFloat(e.price);n.push(a.subcat_id);o+=e.qty}
l.push(t)});m[a.subcat_id]={total:o};this.item_addons[s].qty_selected=o;if(this.item_addons[s].qty_selected>=t){l.forEach((e,t)=>{this.item_addons[s].sub_items[e].disabled=!0})}else{l.forEach((e,t)=>{this.item_addons[s].sub_items[e].disabled=!1})}}});this.item_total=S;var i=!0;if(d.length>0){u.each(d,function(e,t){if(n.includes(t)===!1){i=!1;return!1}})}
if(this.items.cooking_ref_required){let a=!1;if(Object.keys(this.meta.cooking_ref).length>0){Object.entries(this.meta.cooking_ref).forEach(([e,t])=>{if(t.checked){a=!0}})}
if(!a){i=!1}}
if(Object.keys(c).length>0){let a,s;Object.entries(c).forEach(([e,t])=>{a=parseInt(t.min);if(m[t.subcat_id]){s=parseInt(m[t.subcat_id].total)}
if(s>0){if(a>s){i=!1}}})}
if(i){this.disabled_cart=!1}else this.disabled_cart=!0},CheckaddCartItems(){this.addCartItems()},addCartItems(e){if(e){e.preventDefault()}
this.add_to_cart=!0;var t={YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_id:this.items.merchant_id,cat_id:this.items.cat_id,item_token:this.items.item_token,item_size_id:this.size_id,item_qty:this.item_qty,item_addons:this.item_addons,special_instructions:this.special_instructions,meta:this.meta,order_uuid:this.order_uuid,transaction_type:this.order_type,if_sold_out:this.if_sold_out,};if(!h(this.old_item)){t.old_item_token=this.old_item.item_token;t.item_row=this.old_item.item_row}
var a=1;t=JSON.stringify(t);_[a]=u.ajax({url:this.ajax_url+"/addCartItems",method:"PUT",dataType:"json",data:t,contentType:i.json,timeout:s,crossDomain:!0,beforeSend:function(e){if(_[a]!=null){_[a].abort()}},});_[a].done((e)=>{if(e.code==1){u(this.$refs.modal_item_details).modal("hide");this.$emit("refreshOrder",this.order_uuid);r(e.msg,"success");if(!h(this.old_item)){this.$emit("close-menu")}}else{r(e.msg,"error")}});_[a].always((e)=>{this.add_to_cart=!1})},},template:"#xtemplate_item",};const O={props:["label","ajax_url","client_id","image_placeholder","page_limit","merchant_id"],data(){return{is_loading:!1,customer:[],addresses:[],block_from_ordering:!1,datatables:undefined,count_up:undefined}},mounted(){this.observer=lozad(".lozad",{loaded:function(e){e.classList.add("loaded");o("image loaded")},})},computed:{hasData(){if(Object.keys(this.customer).length>0){return!0}
return!1},},updated(){this.observer.observe()},methods:{show(){this.getCustomerDetails();this.getCustomerOrders();this.getCustomerSummary();u(this.$refs.customer_modal).modal("show")},close(){u(this.$refs.customer_modal).modal("hide")},getCustomerDetails(){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/getCustomerDetails",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),client_id:this.client_id,merchant_id:this.merchant_id},timeout:s}).then((e)=>{if(e.data.code==1){this.customer=e.data.details.customer;this.addresses=e.data.details.addresses;this.block_from_ordering=e.data.details.block_from_ordering}else{this.customer=[];this.addresses=[]}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},getCustomerOrders(){var e={YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),client_id:this.client_id};this.datatables=u(this.$refs.order_table).DataTable({ajax:{url:this.ajax_url+"/getCustomerOrders",contentType:"application/json",type:"PUT",data:(e)=>{e.YII_CSRF_TOKEN=u("meta[name=YII_CSRF_TOKEN]").attr("content");e.client_id=this.client_id;e.merchant_id=this.merchant_id;return JSON.stringify(e)},},language:{url:ajaxurl+"/DatableLocalize"},serverSide:!0,processing:!0,pageLength:parseInt(this.page_limit),destroy:!0,lengthChange:!1,order:[[0,"desc"]],columns:[{data:"order_id"},{data:"total"},{data:"status"},{data:"order_uuid"}],})},blockCustomerConfirmation(){if(!this.block_from_ordering){bootbox.confirm({size:"small",title:"",message:"<h5>"+this.label.block_customer+"</h5>"+"<p>"+this.label.block_content+"</p>",centerVertical:!0,animate:!1,buttons:{cancel:{label:this.label.cancel,className:"btn btn-black small pl-4 pr-4"},confirm:{label:this.label.confirm,className:"btn btn-green small pl-4 pr-4"}},callback:(e)=>{if(e){this.blockOrUnlockCustomer(1)}},})}else{this.blockOrUnlockCustomer(0)}},blockOrUnlockCustomer(e){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/blockCustomer",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),client_id:this.client_id,merchant_id:this.merchant_id,block:e},timeout:s}).then((e)=>{if(e.data.code==1){if(e.data.details==1){this.block_from_ordering=!0}else this.block_from_ordering=!1}else{this.block_from_ordering=!1}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},getCustomerSummary(){axios({method:"POST",url:this.ajax_url+"/getCustomerSummary",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&client_id="+this.client_id+"&merchant_id="+this.merchant_id,timeout:s,}).then((e)=>{if(e.data.code==1){var t={decimalPlaces:0,separator:",",decimal:"."};var a=new countUp.CountUp(this.$refs.summary_orders,e.data.details.orders,t);a.start();var s=new countUp.CountUp(this.$refs.summary_cancel,e.data.details.order_cancel,t);s.start();var t=e.data.details.price_format;var i=(this.count_up=new countUp.CountUp(this.$refs.summary_total,e.data.details.total,t));i.start();var l=(this.count_up=new countUp.CountUp(this.$refs.summary_refund,e.data.details.total_refund,t));l.start()}else{}})["catch"]((e)=>{}).then((e)=>{})},},template:"#xtemplate_customer",};const C={components:{"money-format":t},props:["label","ajax_url","order_uuid","mode","line"],template:"#xtemplate_print_order",data(){return{is_loading:!1,merchant:[],order_info:[],promo:[],order_status:[],services:[],payment_status:[],items:[],order_summary:[],print_settings:[],payment_list:[],order_table_data:[]}},computed:{hasData(){if(this.order_summary.length>0){return!0}
return!1},hasBooking(){if(Object.keys(this.order_table_data).length>0){return!0}
return!1},},methods:{show(){this.orderDetails();u(this.$refs.print_modal).modal("show")},close(){u(this.$refs.print_modal).modal("hide")},orderDetails(){this.order_summary=[];this.is_loading=!0;var e=["print_settings"];axios({method:"put",url:this.ajax_url+"/orderDetails",data:{order_uuid:this.order_uuid,YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),payload:e},timeout:s}).then((e)=>{this.response_code=e.data.code;if(e.data.code==1){this.merchant=e.data.details.data.merchant;this.order_info=e.data.details.data.order.order_info;this.promo = e.data.details.data.order.promo;this.payment_list=e.data.details.data.order.payment_list;this.order_status=e.data.details.data.order.status;this.services=e.data.details.data.order.services;this.payment_status=e.data.details.data.order.payment_status;this.items=e.data.details.data.items;this.order_summary=e.data.details.data.summary;this.print_settings=e.data.details.data.print_settings;this.order_table_data=e.data.details.data.order_table_data}else{r(e.data.msg,"error");this.merchant_direction="";this.delivery_direction="";this.merchant=[];this.order_info=[];this.promo=[];this.items=[];this.order_summary=[];this.print_settings=[];this.order_table_data=[]}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1;this.loading=!1})},print(){u(".printhis").printThis();this.$refs.print_button.disabled=!0;setTimeout(()=>{this.$refs.print_button.disabled=!1},1e3)},},};const ve={props:["label","ajax_url","filter_status"],data(){return{is_loading:!1,status_list:[],order_type_list:[],payment_status_list:[],sort_list:[],status:[],order_type:[],payment_status:[],sort:"",search_filter:"",awaitingSearch:!1,search_toggle:!1,}},mounted(){this.getOrderFilterSettings();this.selectPicker()},watch:{search_filter(e,t){if(!this.awaitingSearch){if(h(e)){return!1}
setTimeout(()=>{this.search_toggle=!0;this.$emit("afterFilter",{search_filter:this.search_filter,order_type:this.order_type,payment_status:this.payment_status,sort:this.sort});this.awaitingSearch=!1},1e3)}
this.awaitingSearch=!0},},methods:{getOrderFilterSettings(){axios({method:"POST",url:this.ajax_url+"/getOrderFilterSettings",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.status_list=e.data.details.status_list;this.order_type_list=e.data.details.order_type_list;this.payment_status_list=e.data.details.payment_status_list;this.sort_list=e.data.details.sort_list;setTimeout(()=>{u(".selectpicker").selectpicker("refresh")},1)}else{}})["catch"]((e)=>{}).then((e)=>{})},selectPicker(){u(this.$refs.order_type_list).on("changed.bs.select",(e,t,a,s)=>{this.order_type=u(this.$refs.order_type_list).selectpicker("val");this.$emit("afterFilter",{order_type:this.order_type,payment_status:this.payment_status,sort:this.sort})});u(this.$refs.payment_status_list).on("changed.bs.select",(e,t,a,s)=>{this.payment_status=u(this.$refs.payment_status_list).selectpicker("val");this.$emit("afterFilter",{order_type:this.order_type,payment_status:this.payment_status,sort:this.sort})});u(this.$refs.sort_list).on("changed.bs.select",(e,t,a,s)=>{this.sort=u(this.$refs.sort_list).selectpicker("val");this.$emit("afterFilter",{order_type:this.order_type,payment_status:this.payment_status,sort:this.sort})})},clearFiler(){this.search_toggle=!1;this.search_filter="";this.$emit("afterFilter",{search_filter:"",order_type:this.order_type,payment_status:this.payment_status,sort:this.sort})},},template:`
	<div class="order-search-nav p-2">
	
	  <div class="row">
	    <div class="col-md-6 pl-2 mb-2 mb-lg-0">
	    
	     <div class="input-group mr-2">
		    <input v-model="search_filter" class="form-control py-2 border-right-0 border" type="search" 
		    :placeholder="label.placeholder"
		    >
		    <span class="input-group-append">
		        <div v-if="!awaitingSearch" class="input-group-text bg-transparent"><i class="zmdi zmdi-search"></i></div>
		        <div v-if="awaitingSearch" class="input-group-text bg-transparent"><i class="fas fa-circle-notch fa-spin"></i></div>
		        <div v-if="search_toggle" @click="clearFiler" class="input-group-text bg-transparent"><a class="m-0 link font12">Clear</a></div>
		    </span>
		  </div>  
	    
	    </div> <!--col-->
	    
	    <div class="col-md-6">	   	  
	    
	       <div class="d-flex selectpicker-group rounded">
	         <div v-if="filter_status" class="flex-col">
	         <select ref="status_list" data-width="fit" class="selectpicker" multiple="multiple"  :title="label.status" data-selected-text-format="static" >		    		    
		       <option v-for="(item, key) in status_list" :value="key">{{item}}</option>		    
		     </select>	
	         </div>
	         <div class="flex-col">
	          <select ref="order_type_list"  data-width="fit" class="selectpicker" multiple="multiple" :title="label.order_type_list" data-selected-text-format="static" >		    		    
		       <option v-for="(item, key) in order_type_list" :value="key">{{item}}</option>		    
		      </select>		
	         </div>
	         <div class="flex-col">
	          <select ref="payment_status_list"  data-width="fit" class="selectpicker" multiple="multiple" :title="label.payment_status_list" data-selected-text-format="static" >		    		    
		       <option v-for="(item, key) in payment_status_list" :value="key">{{item}}</option>		    
		      </select>		
	         </div>
	         <div class="flex-col">	          
	          <select ref="sort_list"  data-width="fit" class="selectpicker" :title="label.sort" data-selected-text-format="static" >		    		    
		       <option v-for="(item, key) in sort_list" :value="key" :data-icon="item.icon" >{{item.text}}</option>		    
		      </select>		
	         </div>
	       </div>		  	    
	    </div> <!--col-->
	    
	  </div>
	  <!--flex-->
	
	</div>
	<!--order searcb-nav-->		
	`,};const ge={props:{label:Array,ajax_url:String,pause_interval:{type:Number,default:10}},data(){return{is_loading:!1,data:[],time_delay:"",steps:1,pause_reason:[],reason:"",pause_hours:0,pause_minutes:0}},mounted(){this.getDelayedMinutes()},computed:{hasData(){if(this.time_delay=="other"){if(this.pause_hours>0){return!0}
if(this.pause_minutes>0){return!0}}else{if(!h(this.time_delay)){return!0}}
return!1},hasReason(){if(!h(this.reason)){return!0}
return!1},},methods:{show(){u(this.$refs.modal_pause_order).modal("show")},close(){u(this.$refs.modal_pause_order).modal("hide")},getDelayedMinutes(){axios({method:"post",url:this.ajax_url+"/getPauseOptions",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details}else{this.data=[]}})["catch"]((e)=>{}).then((e)=>{})},submit(){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/setPauseOrder",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),time_delay:this.time_delay,reason:this.reason,pause_hours:this.pause_hours,pause_minutes:this.pause_minutes},timeout:s,}).then((e)=>{if(e.data.code==1){this.steps=1;this.time_delay="";this.close();this.$emit("afterPause",e.data.details)}else{r(e.data.msg)}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},addMins(){if(this.pause_minutes>=60){this.pause_minutes=0;this.pause_hours+=1}else{this.pause_minutes+=this.pause_interval}},lessMins(){if(this.pause_minutes<=0){if(this.pause_hours>0){this.pause_minutes=60;this.pause_hours-=1}else{this.pause_minutes=0}}else{this.pause_minutes-=this.pause_interval}},cancel(){if(this.time_delay=="other"){this.steps=1;this.time_delay=""}else{this.close()}},},template:`	
	   <div ref="modal_pause_order" class="modal"
	    id="modal_pause_order"  data-backdrop="static" 
	    tabindex="-1" role="dialog" aria-labelledby="modal_pause_order" aria-hidden="true">
	    	   
	       <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
		     <div class="modal-content pt-2">
		     		      		   		     
		     <template v-if="steps==1">
		      <div class="modal-body">	
		        			    
			   <div class="w-75 m-auto">
				    <h5>{{label.pause_new_order}}</h5>
				    <p>{{label.how_long}}</p>
				    				    
				   <template v-if="time_delay=='other'">				   
				   <div class="d-flex justify-content-center align-items-center text-center">
				     <div class="flex-col mr-3"><button @click="lessMins" class="btn rounded-button-icon rounded-circle"><i class="zmdi zmdi-minus"></i></button></div>
				     <div class="flex-col mr-1"><h1>{{pause_hours}}</h2> <p class="m-0 font9 font-weight-bold">{{label.hours}}</p></div>
				     <div class="flex-col mr-1"><h1>:</h2><p  class="m-0 font11">&nbsp;</p></div>
				     <div class="flex-col m-0"><h1>{{pause_minutes}}</h2> <p class="m-0 font9 bold font-weight-bold">{{label.minute}}</p></div>
				     <div class="flex-col ml-3"><button @click="addMins" class="btn rounded-button-icon rounded-circle"><i class="zmdi zmdi-plus"></i></button></div>
				   </div>				   				   				  
				   </template>
				   
				   <template v-else>				   
			       <div class="row mt-4">
			         <div v-for="item in data.times" class="col-lg-4 col-md-4 col-sm-6 col-4 mb-2 mb-2 ">
			           <button 
			           :class="{active:time_delay==item.id}" 
			           @click="time_delay=item.id"
			           class="btn btn-light">
			           {{item.value}}
			           </button>
			         </div>
			       </div>
			       </template>
			        
		       </div> <!-- w-75 -->
			    
			  </div> <!-- body -->
			  
	          <div class="modal-footer">         
			   <button type="button" class="btn btn-black" @click="cancel">
	            <span class="pl-3 pr-3">{{label.cancel}}</span>
	           </button>	           
		        <button type="submit"  class="btn btn-green pl-4 pr-4" :class="{ loading: is_loading }"   
		        :disabled="!hasData"
		        @click="steps=2"
		         >
		          <span>{{label.next}}</span>
		          <div class="m-auto circle-loader" data-loader="circle-side"></div> 
		        </button>
		     </div>
		     </template>
		     
		     <template v-else-if="steps==2">
		     <div class="modal-body">		 
			 
		      <div class="w-75 m-auto">
			    <h5>{{label.reason}}</h5>
			  </div>
			    
		       <div class="list-group list-group-flush mt-4">
		         <a v-for="item in data.pause_reason" @click="reason=item" 
		         :class="{active:reason==item}"
		         class="text-center list-group-item list-group-item-action">
		         {{item}}
		         </a>
		       </div>
		     
			  </div> <!-- body -->
			  			  
	          <div class="modal-footer">               
			   <button type="button" class="btn btn-black"
			   @click="steps=1"
			    >
	            <span class="pl-3 pr-3">{{label.cancel}}</span>
	           </button>
			       
		        <button type="submit"  class="btn btn-green pl-4 pr-4" :class="{ loading: is_loading }"   
		        :disabled="!hasReason"
		        @click="submit"
		         >
		          <span>{{label.confirm}}</span>
		          <div class="m-auto circle-loader" data-loader="circle-side"></div> 
		        </button>
		     </div>
		     </template>
			     		     
		     </div> <!--content-->		     
		  </div> <!--dialog-->		  
		</div> <!--modal-->     	
	`,};const be={props:{endDate:{type:Date,default(){return new Date()},},negative:{type:Boolean,default:!1},},data(){return{now:new Date(),timer:null}},computed:{hour(){let e=Math.trunc((this.endDate-this.now)/1e3/3600);return e>9?e:"0"+e},min(){let e=Math.trunc((this.endDate-this.now)/1e3/60)%60;return e>9?e:"0"+e},sec(){let e=Math.trunc((this.endDate-this.now)/1e3)%60;return e>9?e:"0"+e},},watch:{endDate:{immediate:!0,handler(e){if(this.timer){clearInterval(this.timer)}
this.timer=setInterval(()=>{this.now=new Date();if(this.negative)return;if(this.now>e){this.now=e;this.$emit("endTime");clearInterval(this.timer)}},1e3)},},},beforeUnmount(){clearInterval(this.timer)},template:`	
	  <p class="m-0 mt-1 text-center font-weight-bold"><slot></slot> {{hour}}:{{min}}:{{sec}} </p>
	`,};const ye={props:["label","ajax_url"],components:{"components-timer-countdown":be},data(){return{is_load:!1,accepting_order:!1,data:[],pause_time:undefined,luxon:undefined}},mounted(){this.getMerchantOrderingStatus();this.luxon=luxon.DateTime},methods:{getMerchantOrderingStatus(){this.is_load=!0;axios({method:"post",url:this.ajax_url+"/MerchantOrderingStatus",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details;this.accepting_order=e.data.details.accepting_order;this.pause_time=this.luxon.fromISO(e.data.details.pause_time);o(this.pause_time)}else{this.data=[];this.pause_order=!1}})["catch"]((e)=>{}).then((e)=>{this.is_load=!1})},PauseOrdering(){this.$emit("afterClickpause",this.accepting_order)},pauseOrderEnds(){axios({method:"put",url:this.ajax_url+"/UpdateOrderingStatus",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),accepting_order:!0},timeout:s}).then((e)=>{if(e.data.code==1){this.accepting_order=e.data.details.accepting_order}else{this.accepting_order=!1}})["catch"]((e)=>{}).then((e)=>{this.is_load=!1})},updateStatus(e){o(e);this.accepting_order=e.accepting_order;this.pause_time=this.luxon.fromISO(e.pause_time)},},template:`
	 <div class="position-relative">
      <div v-if="is_load" class="skeleton-placeholder" style="height:50px;width:100%;"></div>
      
       <button @click="PauseOrdering" class="btn" :class="{'btn-green' :accepting_order, 'btn-yellow': accepting_order==false}">   
	     <div class="d-flex justify-content-between align-items-center">
	       <template v-if="accepting_order" >
		       <div class="mr-0 mr-lg-2"><i style="font-size:20px;" class="zmdi zmdi-check-circle"></i></div>
		       <div class="xd-none xd-lg-block" >{{label.accepting_orders}}</div>       
	       </template>
	       <template v-else>  
	           <div class="mr-2"><i style="font-size:20px;" class="zmdi zmdi-pause"></i></div>
	           <div>{{label.not_accepting_orders}}</div>
	       </template>
	     </div>
	   </button>
	   	   
	   <template v-if="!is_load">
	   <template v-if="!accepting_order">
	   <components-timer-countdown
	    :endDate='pause_time'
	    @end-time="pauseOrderEnds"
	     >
	     {{label.store_pause}}
	   </components-timer-countdown>
	   </template>
	   </template>
	   
      
      </div>          
	`,};const xe={props:["label","ajax_url"],data(){return{is_loading:!1}},methods:{show(){u(this.$refs.modal).modal("show")},close(){u(this.$refs.modal).modal("hide")},submit(){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/UpdateOrderingStatus",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),accepting_order:!0},timeout:s}).then((e)=>{if(e.data.code==1){this.$emit("afterPause",e.data.details);this.close()}else{r(e.data.msg)}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},template:`	
		<div ref="modal" class="modal" tabindex="-1" role="dialog" data-backdrop="static"  >
	    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
	    <div class="modal-content">
	      <div class="modal-body">	            	     
	        
	        <div class="w-75 m-auto">
			    <h5>{{label.store_pause}}</h5>
			    <p>{{label.resume_orders}}</p>
			</div>  
	      
	      </div>      
	      <div class="modal-footer">            
	      
	            <button type="button" class="btn btn-black" data-dismiss="modal">
	            <span class="pl-3 pr-3">{{label.cancel}}</span>
	           </button>
			       
		        <button type="submit"  class="btn btn-green pl-4 pr-4" :class="{ loading: is_loading }"   		        		        
		        @click="submit"
		         >
		          <span>{{label.confirm}}</span>
		          <div class="m-auto circle-loader" data-loader="circle-side"></div> 
		        </button>  
	      
	      </div>	      
	    </div>
	  </div>
	</div>            
	`,};
	const F=Vue.createApp({components:{"components-orderlist":ce,"components-orderinfo":_e,"components-delay-order":pe,"components-order-history":fe,"components-menu":b,"components-item-details":T,"components-customer-details":O,"components-order-print":C,"components-order-search-nav":ve,"components-pause-order":ye,"components-pause-modal":ge,"components-resume-order-modal":xe,"components-notification":te,"components-assign-driver":re,},data(){return{order_uuid:"",order_type:"",client_id:"",resolvePromise:undefined,rejectPromise:undefined,show_as_popup:!1}},mounted(){},methods:{showAssigndriver(){this.$refs.assign_driver.show()},afterSelectOrder(e,t){this.order_uuid=e;this.order_type=t;this.$refs.orderinfo.orderDetails(e)},refreshOrderInformation(e){this.$refs.orderinfo.orderDetails(e)},afterUpdateStatus(){this.$refs.orderlist.getList();if(typeof E!=="undefined"&&E!==null){E.getOrdersCount()}},orderReject(e){this.$refs.rejection.confirm()},delayOrder(e){this.$refs.delay.show()},orderHistory(e){this.$refs.history.show()},showMerchantMenu(e){this.$refs.menu.replace_item=e;this.$refs.menu.show()},hideMerchantMenu(){this.$refs.menu.close()},showItemDetails(e){this.$refs.item.show(e)},viewCustomer(){this.$refs.customer.show()},toPrint(){this.$refs.print.show()},afterFilter(e){this.$refs.orderlist.getList(e)},afterClickpause(e){o(e);if(e){this.$refs.pause_modal.show()}else{this.$refs.resume_order.show()}},afterPause(e){this.$refs.pause_order.updateStatus(e)},closeOrderModal(){this.show_as_popup=!1},},});F.use(Maska);F.use(ElementPlus);const N=F.mount("#vue-order-management");const j=Vue.createApp({components:{"components-orderinfo":_e,"components-delay-order":pe,"components-rejection-forms":me,"components-order-history":fe,"components-order-print":C,"components-menu":b,"components-item-details":T,"components-customer-details":O,"components-assign-driver":re,},data(){return{order_uuid:"",client_id:"",merchant_id:"",is_loading:!1,group_name:"",manual_status:!1,modify_order:!1,filter_buttons:!1}},mounted(){this.order_uuid=_order_uuid;this.getGroupname()},methods:{showAssigndriver(){this.$refs.assign_driver.show()},afterUpdateStatus(){this.getGroupname();if(typeof E!=="undefined"&&E!==null){E.getOrdersCount()}},refreshOrderInformation(e){this.$refs.orderinfo.orderDetails(this.order_uuid)},loadOrderDetails(){this.$refs.orderinfo.orderDetails(this.order_uuid)},delayOrder(e){this.$refs.delay.show()},orderReject(e){this.$refs.rejection.confirm()},orderHistory(e){this.$refs.history.show()},toPrint(){this.$refs.print.show()},showMerchantMenu(e){this.$refs.menu.replace_item=e;this.$refs.menu.show()},showItemDetails(e){this.$refs.item.show(e)},viewCustomer(){this.$refs.customer.show()},getGroupname(){this.is_loading=!0;axios({method:"POST",url:_ajax_url+"/getGroupname",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&order_uuid="+this.order_uuid,timeout:s}).then((e)=>{if(e.data.code==1){this.group_name=e.data.details.group_name;this.manual_status=e.data.details.manual_status;this.modify_order=e.data.details.modify_order;this.client_id=e.data.details.client_id;this.merchant_id=e.data.details.merchant_id;this.filter_buttons=e.data.details.filter_buttons}else{this.client_id="";this.group_name="";this.manual_status=!1;this.modify_order=!1;this.filter_buttons=!1}
setTimeout(()=>{this.loadOrderDetails()},1)})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},});j.use(Maska);j.use(ElementPlus);
const we=j.mount("#vue-order-view");
const E=Vue.createApp({
mounted(){
	this.getOrdersCount()
},data(){
	return{
		data:[],is_load:!1
	}},
methods:{
	getOrdersCount(){
		axios({
			method:"POST",url:apibackend+"/getOrdersCount",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s})
			.then((e)=>{if(e.data.code==1){if(this.is_load){u(this.$refs.orders_new).find(".badge-notification").remove()}
this.data=e.data.details;

if(this.data.not_viewed>0){
	u(this.$refs.orders_new).append('<div class="blob green badge-pill pull-right badge-notification bg-new">'+this.data.new_order+"</div>")
}else{
	u(this.$refs.orders_new).append('<div class="badge-pill pull-right badge-notification bg-new">'+this.data.new_order+"</div>")
}
u(this.$refs.orders_processing).append('<div class="badge-pill pull-right badge-notification bg-processing">'+this.data.order_processing+"</div>");
u(this.$refs.orders_ready).append('<div class="badge-pill pull-right badge-notification bg-ready">'+this.data.order_ready+"</div>");
u(this.$refs.orders_completed).append('<div class="badge-pill pull-right badge-notification bg-completed">'+this.data.completed_today+"</div>");
u(this.$refs.orders_scheduled).append('<div class="badge-pill pull-right badge-notification bg-scheduled">'+this.data.scheduled+"</div>");
u(this.$refs.orders_history).append('<div class="badge-pill pull-right badge-notification bg-history">'+this.data.all_orders+"</div>")}})["catch"]((e)=>{}).then((e)=>{this.is_load=!0})},},}).mount("#vue-siderbar-menu");
const ke={props:["ajax_url","settings"],template:"#xtemplate_order_filter",mounted(){this.initeSelect2();this.getFilterData()},data(){return{status_list:[],order_type_list:[],order_status:"",order_type:"",client_id:""}},methods:{initeSelect2(){u(".select2-single").select2({width:"resolve"});u(".select2-customer").select2({width:"resolve",language:{searching:()=>{return this.settings.searching},noResults:()=>{return this.settings.no_results},},ajax:{delay:250,url:this.ajax_url+"/searchCustomer",type:"PUT",contentType:"application/json",data:function(e){var t={search:e.term,YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content")};return JSON.stringify(t)},},});if(!h(this.$refs.driver_id)){u(".select2-driver").select2({width:"resolve",language:{searching:()=>{return this.settings.searching},noResults:()=>{return this.settings.no_results},},ajax:{delay:250,url:this.ajax_url+"/searchDriver",type:"PUT",contentType:"application/json",data:function(e){var t={search:e.term,YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content")};return JSON.stringify(t)},},})}},getFilterData(){axios({method:"put",url:this.ajax_url+"/getFilterData",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content")},timeout:s}).then((e)=>{if(e.data.code==1){this.status_list=e.data.details.status_list;this.order_type_list=e.data.details.order_type_list}else{this.status_list=[];this.order_type_list=[]}})["catch"]((e)=>{}).then((e)=>{})},clearFilter(){u(this.$refs.order_status).val(null).trigger("change");u(this.$refs.order_type).val(null).trigger("change");u(this.$refs.client_id).val(null).trigger("change")},submitFilter(){this.order_status=u(this.$refs.order_status).find(":selected").val();this.order_type=u(this.$refs.order_type).find(":selected").val();this.client_id=u(this.$refs.client_id).find(":selected").val();this.$emit("afterFilter",{order_status:this.order_status,order_type:this.order_type,client_id:this.client_id})},closePanel(){this.$emit("closePanel")},},};
const $={components:{"components-order-filter":ke},props:["settings","actions","ajax_url","table_col","columns","page_limit","transaction_type_list","filter","filter_id","ref_id"],mounted(){this.getTableData();this.initDateRange();this.selectPicker();this.initSiderbar()},data(){return{datatables:undefined,date_range:"",date_start:"",date_end:"",transaction_type:[],sidebarjs:undefined,filter_by:[]}},methods:{initDateRange(){let e=["Su","Mo","Tu","We","Th","Fr","Sa"];let t=["January","February","March","April","May","June","July","August","September","October","November","December"];if(typeof daysofweek!=="undefined"&&daysofweek!==null){e=JSON.parse(daysofweek)}
if(typeof monthsname!=="undefined"&&monthsname!==null){t=JSON.parse(monthsname)}
var a=JSON.parse(translation_vendor);var s={};s[a.today]=[moment(),moment()];s[a.Yesterday]=[moment().subtract(1,"days"),moment().subtract(1,"days")];s[a.last_7_days]=[moment().subtract(6,"days"),moment()];s[a.last_30_days]=[moment().subtract(29,"days"),moment()];s[a.this_month]=[moment().startOf("month"),moment().endOf("month")];s[a.last_month]=[moment().subtract(1,"month").startOf("month"),moment().subtract(1,"month").endOf("month")];u(this.$refs.date_range).daterangepicker({autoUpdateInput:!1,showWeekNumbers:!0,alwaysShowCalendars:!0,autoApply:!0,locale:{format:"YYYY-MM-DD",daysOfWeek:e,monthNames:t,customRangeLabel:a.custom_range},ranges:s},(e,t,a)=>{this.date_range=e.format("YYYY-MM-DD")+" "+this.settings.separator+" "+t.format("YYYY-MM-DD");this.date_start=e.format("YYYY-MM-DD");this.date_end=t.format("YYYY-MM-DD");this.getTableData()})},selectPicker(){u(this.$refs.transaction_type).on("changed.bs.select",(e,t,a,s)=>{this.transaction_type=u(this.$refs.transaction_type).selectpicker("val");this.getTableData()})},initSiderbar(){if(this.filter==1){this.sidebarjs=new SidebarJS.SidebarElement({position:"right"})}},getTableData(){var a;var s=this;this.datatables=u(this.$refs.vue_table).DataTable({ajax:{url:this.ajax_url+"/"+this.actions,contentType:"application/json",type:"PUT",data:(e)=>{e.YII_CSRF_TOKEN=u("meta[name=YII_CSRF_TOKEN]").attr("content");e.date_start=this.date_start;e.date_end=this.date_end;e.transaction_type=this.transaction_type;e.filter=this.filter_by;e.filter_id=this.filter_id;e.ref_id=this.ref_id;return JSON.stringify(e)},},language:{url:ajaxurl+"/DatableLocalize"},serverSide:!0,processing:!0,pageLength:parseInt(this.page_limit),destroy:!0,lengthChange:!1,bFilter:this.settings.filter,ordering:this.settings.ordering,order:[[this.settings.order_col,this.settings.sortby]],columns:this.columns,dom:"Bfrtip",buttons:["excelHtml5","csvHtml5","pdfHtml5","print"],});a=this.datatables;let i=this.date_start;let l=this.date_end;a.on("preXhr.dt",function(e,t,a){if(!h(i)&&!h(l)){s.$emit("afterSelectdate",i,l)}});u(".vue_table tbody").on("click",".ref_invoice",function(){var e=a.row(u(this).parents("tr")).data();if(!h(e)){s.$emit("viewInvoice",e.invoice_ref_number,e.payment_code)}});u(".vue_table tbody").on("click",".ref_tax_edit",function(){var e=a.row(u(this).parents("tr")).data();if(!h(e)){s.$emit("editTax",e.tax_uuid)}});u(".vue_table tbody").on("click",".ref_tax_delete",function(){var e=a.row(u(this).parents("tr")).data();if(!h(e)){s.$emit("deleteTax",e.tax_uuid)}});u(".vue_table tbody").on("click",".ref_edit",function(){var e=a.row(u(this).parents("tr")).data();if(!h(e)){window.location.href=e.update_url}});u(".vue_table tbody").on("click",".ref_view_url",function(){var e=a.row(u(this).parents("tr")).data();if(!h(e)){window.location.href=e.view_url}});u(".vue_table tbody").on("click",".ref_delete",function(){var t=a.row(u(this).parents("tr")).data();if(!h(t)){bootbox.confirm({size:"small",title:"",message:"<h5>Delete Confirmation</h5>"+"<p>Are you sure you want to permanently delete the selected item?</p>",centerVertical:!0,animate:!1,buttons:{cancel:{label:"Cancel",className:"btn"},confirm:{label:"Delete",className:"btn btn-green small pl-4 pr-4"}},callback:(e)=>{if(e){window.location.href=t.delete_url}},})}});u(".vue_table tbody").on("click",".ref_payout",function(){var e=a.row(u(this).parents("tr")).data();if(!h(e)){s.viewTransaction(e.transaction_uuid)}})},openFilter(){this.sidebarjs.toggle()},afterFilter(e){this.sidebarjs.toggle();this.filter_by=e;this.getTableData()},closePanel(){this.sidebarjs.toggle()},viewTransaction(e){this.$emit("viewTransaction",e)},},template:`			
	
	 <div class="row mb-3">
	  <div class="col">
	      
	      <div class="d-flex">
	      	      
		  <div v-if="!settings.filter_date_disabled" class="input-group fixed-width-field mr-2">
		    <input ref="date_range" v-model="date_range" class="form-control py-2 border-right-0 border" type="search"         
	        :placeholder="settings.placeholder" :data-separator="settings.separator"
	        >
		    <span class="input-group-append">
		        <div class="input-group-text bg-transparent"><i class="zmdi zmdi-calendar-alt"></i></div>
		    </span>
		  </div>
		  
		  
		  <select v-if="transaction_type_list" ref="transaction_type" data-style="selectpick" class="selectpicker" multiple="multiple" :title="settings.all_transaction" >		    
		    <option v-for="(item, key) in transaction_type_list" :value="key">{{item}}</option>		    
		  </select>
		  
		  <button v-if="filter==1" class="btn btn-yellow normal" @click="openFilter" >		   		   
		   <div class="d-flex">
		     <div class="mr-2"><i class="zmdi zmdi-filter-list"></i></div>
		     <div>{{settings.filters}}</div>
		   </div>
		  </button>
		  
		  </div> <!-- flex -->
		  
	  </div>	  
	  <div class="col"></div>
	</div> <!--row-->
	
	<div class="table-responsive">
	<table ref="vue_table" class="table vue_table"  style="width:100%" >
	<thead>
	<tr>
	 <th v-for="(col, key) in table_col" :width="col.width">{{col.label}}</th>
	</tr>
	</thead>
	<tbody>
	</tbody>
	</table>
	</div>
	
	<components-order-filter
	ref="filter"
	:ajax_url="ajax_url"
	:settings="settings"
	@after-filter="afterFilter"
	@close-panel="closePanel"
	>
	</components-order-filter>
    `,};const Ie={props:["ajax_url"],data(){return{is_loading:!1}},mounted(){this.getGetMerchantBalance()},methods:{getGetMerchantBalance(){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/getGetMerchantBalance",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content")},timeout:s}).then((e)=>{var t;if(e.data.code==1){t=this.count_up=new countUp.CountUp(this.$refs.balance,e.data.details.balance,e.data.details.price_format);t.start();this.$emit("afterBalance",e.data.details.balance)}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},template:`
	<span ref="balance"></span>
	`,};const Se={props:["ajax_url","label","balance"],data(){return{is_loading:!1,error:[],amount:0}},computed:{hasData(){var e=!0;if(this.amount<=0){e=!1}
if(parseFloat(this.amount)>=parseFloat(this.balance)){e=!1}
return e},},methods:{show(){u(this.$refs.modal_payout).modal("show");this.$refs.amount.focus()},close(){u(this.$refs.modal_payout).modal("hide")},submit(){this.error=[];this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/requestPayout",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),amount:this.amount},timeout:s}).then((e)=>{var t;if(e.data.code==1){r(e.data.msg);this.close();this.$emit("afterRequestpayout")}else{this.error=e.data.msg;location.href="#error_message"}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},template:`
	<div ref="modal_payout" class="modal"
    id="modal_payout" data-backdrop="static" 
    tabindex="-1" role="dialog" aria-labelledby="modal_payout" aria-hidden="true">
    
	   <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
	     <div class="modal-content"> 
		 
  		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">{{label.title}}</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  
		 <div class="modal-body">
		 
		 <div class="form-label-group">    
	       <input type="text" v-model="amount" id="amount"
	       v-maska="'#*.##'" 
	       ref="amount"
	       placeholder=""
	       class="form-control form-control-text" >
	       <label for="amount">{{label.amount}}</labe>
	     </div>
		     
		 
	     <div id="error_message" v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
	        <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
	     </div>
	     
         </div> <!-- body -->
         
          <div class="modal-footer">               
		   <button type="button" class="btn btn-black" data-dismiss="modal">
            <span class="pl-3 pr-3">{{label.close}}</span>
           </button>
		       
	        <button type="submit" @click="submit" class="btn btn-green pl-4 pr-4" :class="{ loading: is_loading }"         
	         :disabled="!hasData"
	         >
	          <span>{{label.submit}}</span>
	          <div class="m-auto circle-loader" data-loader="circle-side"></div> 
	        </button>
	     </div>

	  </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->     	
	`,};const Te={props:["cash_link","label","amount_selection","minimum_cashin"],data(){return{data:[],cashin_amount:0,is_loading:!1}},computed:{hasData(){if(this.cashin_amount<this.minimum_cashin){return!1}
return!0},},methods:{show(){u(this.$refs.modal_cashin).modal("show")},close(){u(this.$refs.modal_cashin).modal("hide")},ContinueToPayment(){window.location.href=this.cash_link+"&amount="+parseFloat(this.cashin_amount)},},template:`	
   <div ref="modal_cashin" class="modal" tabindex="-1" role="dialog" data-backdrop="static"  >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">      
    
      <div class="modal-body">      
        <a @click="close" href="javascript:;" class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a>      
        
        <div class="d-flex justify-content-between align-items-center">
          <div><h4 class="m-0 mb-3 mt-3">{{label.add_to_balance}}</h4></div>
          <div class="text-muted font11">{{label.minimum_amount}}</div>
        </div>
        
        <p>{{label.how_much}}</p>
        
        <div  class="menu-categories medium mb-3 d-flex">
		  <a v-for="(item, key) in amount_selection" 
		  @click="cashin_amount=key"
		  :class="{active:key==cashin_amount}"  
		  class="text-center rounded align-self-center text-center">		    
		    <h5 class="m-0">{{item}}</h5>
		    <p class="m-0 mt-1 text-truncate">{{label.cashin}}</p>
		  </a>		  
		</div>
		
		<div class="mt-1 mb-2">
		    <div class="form-label-group">    
		       <input type="text" v-model="cashin_amount" id="cashin_amount"
		       placeholder=""
		       v-maska="'#*.##'" 
		       class="form-control form-control-text" >
		       <label for="cashin_amount">{{label.enter_amount}}</labe>
		     </div>
		</div>
        
                 		                                
      </div>  <!-- modal body -->      
      
      <div class="modal-footer border-0">                            
      
        <button type="button" class="btn btn-black" data-dismiss="modal">
            <span class="pl-3 pr-3">{{label.cancel}}</span>
           </button>
		       
        <button type="button"  class="btn btn-green pl-4 pr-4" 
         :disabled="!hasData"
         @click="ContinueToPayment"
         >
          <span>{{label.continue}}</span>          
        </button>  
      
      </div> <!-- modal footer -->
      
    </div> <!--content-->
   </div> <!--dialog-->
   </div> <!--modal-->     	              

    `,};const Oe=Vue.createApp({components:{"components-datatable":$,"components-merchant-balance":Ie,"components-request-payout":Se,"components-cash-in":Te},data(){return{is_loading:!1,balance:0}},methods:{requestPayout(){this.$refs.payout.show()},afterRequestpayout(){this.$refs.datatable.getTableData();this.$refs.balance.getGetMerchantBalance()},afterBalance(e){this.balance=e},showCashin(){this.$refs.cashin.show()},},});Oe.use(Maska);const Ce=Oe.mount("#vue-commission-statement");const Fe={props:["ajax_url","label"],data(){return{is_loading:!1,payment_provider_list:[],account_type:[],payment_provider:"",country_list:[],email_address:"",account_number:"",account_holder_name:"",account_holder_type:"individual",currency:"",routing_number:"",country:"",error:[],currency_list:[],}},computed:{hasData(){var e=!0;if(h(this.payment_provider)){e=!1}
return e},},mounted(){this.getPayoutSettings()},methods:{show(){u(this.$refs.modal_payout_account).modal("show")},close(){u(this.$refs.modal_payout_account).modal("hide")},getPayoutSettings(){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/getPayoutSettings",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content")},timeout:s}).then((e)=>{var t;if(e.data.code==1){this.payment_provider_list=e.data.details.provider;this.country_list=e.data.details.country_list;this.account_type=e.data.details.account_type;this.currency_list=e.data.details.currency_list;this.currency=e.data.details.default_currency;this.country=e.data.details.default_country}else{this.payment_provider_list=[];this.country_list=[];this.currency_list=[]}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},submit(){var e={YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),payment_provider:this.payment_provider};switch(this.payment_provider){case "paypal":e.email_address=this.email_address;break;case "cybersource":e.email_address=this.email_address;break;case "stripe":e.account_number=this.account_number;e.account_holder_name=this.account_holder_name;e.account_holder_type=this.account_holder_type;e.currency=this.currency;e.routing_number=this.routing_number;e.country=this.country;break;case "bank":e.full_name=this.full_name;e.billing_address1=this.billing_address1;e.billing_address2=this.billing_address2;e.city=this.city;e.state=this.state;e.post_code=this.post_code;e.country=this.country;e.account_name=this.account_name;e.account_number_iban=this.account_number_iban;e.swift_code=this.swift_code;e.bank_name=this.bank_name;e.bank_branch=this.bank_branch;break}
this.error=[];this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/SetPayoutAccount",data:e,timeout:s}).then((e)=>{var t;if(e.data.code==1){r(e.data.msg);this.close();this.$emit("afterSave")}else{this.error=e.data.msg;location.href="#error_message"}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},template:`	
	<div ref="modal_payout_account" class="modal"
    id="modal_payout_account" data-backdrop="static" 
    tabindex="-1" role="dialog" aria-labelledby="modal_payout_account" aria-hidden="true">
    
	   <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
	     <div class="modal-content"> 
		 
  		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">{{label.title}}</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  
		 <div class="modal-body">
		 		 
		 <form @submit.prevent="submit" >
		 		 
		 <div class="menu-categories medium mb-3 d-flex">
		  		   
		   <a v-for="(item, key) in payment_provider_list"  class="text-center"
	       @click="payment_provider=item.payment_code"	      
	       :class="{active:payment_provider==item.payment_code}"  
	        class="rounded align-self-center text-center"
	       >
	       
	       <template v-if="item.logo_type=='icon'">
	         <i :class="item.logo_class"></i>
	       </template>
	       <template v-else>
	         <img class="rounded lozad" 		        
		        :src="item.logo_image" 
		        class="rounded-pill lozad"
		     >    
	       </template>
	       
	       <p class="m-0 mt-1 text-truncate">{{item.payment_name}}</p>
	       </a>   
	       
		 </div>
		<!-- menu -->
		  
		  
		  <!-- PAYPAL -->
		  <div v-if="payment_provider==='paypal'">
		     <div class="form-label-group">    
		       <input type="text" v-model="email_address" id="email_address"
		       placeholder=""
		       class="form-control form-control-text" >
		       <label for="email_address">{{label.email_address}}</labe>
		     </div>
		  </div>
		  <!-- PAYPAL --> 

		    <!-- CYBERSOURCE -->
		  <div v-if="payment_provider==='cybersource'">
		     <div class="form-label-group">    
		       <input type="text" v-model="email_address" id="email_address"
		       placeholder=""
		       class="form-control form-control-text" >
		       <label for="email_address">{{label.email_address}}</labe>
		     </div>
		  </div>
		  <!-- CYBERSOURCE --> 
		  
		  <!-- STRIPE -->		  
		  <div v-if="payment_provider==='stripe'">
		     <div class="form-label-group">    
		       <input type="text" v-model="account_number" id="account_number" 
		       v-maska="'#################'" 
		       placeholder=""
		       class="form-control form-control-text" >
		       <label for="account_number">{{label.account_number}}</labe>
		     </div>
		     
		     <div class="form-label-group">    
		       <input type="text" v-model="account_holder_name" id="account_holder_name"
		       placeholder=""
		       class="form-control form-control-text" >
		       <label for="account_holder_name">{{label.account_holder_name}}</labe>
		     </div>
		     		     
		     <select ref="account_holder_type" v-model="account_holder_type"
		     class="form-control custom-select form-control-select mb-3"  >		    
		     <option v-for="(account_type_name, account_type_key) in account_type" :value="account_type_key">{{account_type_name}}</option>		    
		     </select>
		     		     
		     <select ref="currency" v-model="currency"
		     class="form-control custom-select form-control-select mb-3"  >		    
		     <option v-for="(currency_name, currency_key) in currency_list" :value="currency_key">{{currency_name}}</option>		    
		     </select>
		     		     
		     <div class="form-label-group">    
		       <input type="text" v-model="routing_number" id="routing_number"
		       v-maska="'#################'" 
		       placeholder=""
		       class="form-control form-control-text" >
		       <label for="routing_number">{{label.routing_number}}</labe>
		     </div>
		     
		     <select ref="country" v-model="country"
		     class="form-control custom-select form-control-select mb-3"  >		    
		     <option v-for="(country_name, country_key) in country_list" :value="country_key">{{country_name}}</option>		    
		     </select>
		     
		  </div>
		  <!-- STRIPE -->
		  
		  <!-- BANK -->
		  <div v-if="payment_provider==='bank'">
		  
		     <h6>{{label.account_information}}</h6>      
		     
		     <div class="form-label-group">    
		       <input type="text" v-model="account_name" id="account_name"
		       placeholder=""
		       class="form-control form-control-text" >
		       <label for="account_name">{{label.account_name}}</labe>
		     </div>
		     
		     <div class="form-label-group">    
		       <input type="text" v-model="account_number_iban" id="account_number_iban"
		       v-maska="'#################'" 
		       placeholder=""
		       class="form-control form-control-text" >
		       <label for="account_number_iban">{{label.account_number_iban}}</labe>
		     </div>
		     
		     <div class="form-label-group">    
		       <input type="text" v-model="swift_code" id="swift_code"		       
		       placeholder=""
		       class="form-control form-control-text" >
		       <label for="swift_code">{{label.swift_code}}</labe>
		     </div>
		     
		     <div class="form-label-group">    
		       <input type="text" v-model="bank_name" id="bank_name"
		       placeholder=""
		       class="form-control form-control-text" >
		       <label for="bank_name">{{label.bank_name}}</labe>
		     </div>
		     
		      <div class="form-label-group">    
		       <input type="text" v-model="bank_branch" id="bank_branch"
		       placeholder=""
		       class="form-control form-control-text" >
		       <label for="bank_branch">{{label.bank_branch}}</labe>
		     </div>
		     
		  </div>
		  <!-- BANK --> 
		  
		  <div id="error_message" v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
	        <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
	       </div>      
		  
		  </div> <!-- body -->
		  
		   <div class="modal-footer">     

		   <button type="button" class="btn btn-black" data-dismiss="modal">
            <span class="pl-3 pr-3">{{label.close}}</span>
           </button>
		       
	        <button type="submit" @click="submit" class="btn btn-green pl-4 pr-4" :class="{ loading: is_loading }"         
	         :disabled="!hasData"
	         >
	          <span>{{label.submit}}</span>
	          <div class="m-auto circle-loader" data-loader="circle-side"></div> 
	        </button>
	      </div>
		 
	      </form>
		  </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->     	       
	
	`,};const Ne=Vue.createApp({components:{"components-datatable":$,"components-merchant-balance":Ie,"components-set-account":Fe,"components-request-payout":Se},data(){return{is_loading:!1,payout_account:"",provider:"",balance:0}},mounted(){this.getPayoutAccount()},methods:{showAccountForm(){this.$refs.account.show()},afterSave(){this.getPayoutAccount()},afterBalance(e){this.balance=e},getPayoutAccount(){this.is_loading=!0;axios({method:"put",url:apibackend+"/getPayoutAccount",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content")},timeout:s}).then((e)=>{var t;if(e.data.code==1){this.payout_account=e.data.details.account;this.provider=e.data.details.provider}else{this.payout_account="";this.provider=""}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},requestPayout(){this.$refs.payout.show()},afterRequestpayout(){this.$refs.datatable.getTableData();this.$refs.balance.getGetMerchantBalance()},},});Ne.use(Maska);const je=Ne.mount("#vue-withdrawals");const Ee=Vue.createApp({components:{"components-datatable":$},data(){return{is_loading:!1}},mounted(){this.getOrderSummary()},methods:{getOrderSummary(){this.is_loading=!0;axios({method:"POST",url:apibackend+"/getOrderSummary",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){var t={decimalPlaces:0,separator:",",decimal:"."};var a=new countUp.CountUp(this.$refs.summary_orders,e.data.details.orders,t);a.start();var s=new countUp.CountUp(this.$refs.summary_cancel,e.data.details.order_cancel,t);s.start();var t=e.data.details.price_format;var i=(this.count_up=new countUp.CountUp(this.$refs.summary_total,e.data.details.total,t));i.start();var l=(this.count_up=new countUp.CountUp(this.$refs.total_refund,e.data.details.total_refund,t));l.start()}else{}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},});const $e=Ee.mount("#vue-order-history");const Re={template:"#xtemplate_webpushsettings",props:["ajax_url","settings","iterest_list","message"],data(){return{beams:undefined,is_loading:!1,is_submitted:!1,webpush_enabled:"",interest:[],device_id:""}},mounted(){this.initBeam()},methods:{initBeam(){this.beams=new PusherPushNotifications.Client({instanceId:this.settings.instance_id});this.is_loading=!0;axios({method:"POST",url:this.ajax_url+"/getwebnotifications",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.webpush_enabled=e.data.details.enabled==1?!0:!1;this.device_id=e.data.details.device_token;this.interest=e.data.details.interest}else{this.webpush_enabled=!1;this.device_id="";this.interest=[]}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},enabledWebPush(){this.is_loading=!0;if(this.webpush_enabled){this.beams.start().then(()=>{o("start");this.beams.getDeviceId().then((e)=>{this.device_id=e})})["catch"]((e)=>{r(this.message.notification_start,"error")}).then((e)=>{this.is_loading=!1})}else{this.beams.stop().then(()=>{o("stop");this.device_id=""})["catch"]((e)=>{r(this.message.notification_stop,"error")}).then((e)=>{this.is_loading=!1})}},saveWebNotifications(){this.is_submitted=!0;axios({method:"PUT",url:this.ajax_url+"/savewebnotifications",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),webpush_enabled:this.webpush_enabled,interest:this.interest,device_id:this.device_id},timeout:s,}).then((e)=>{if(e.data.code==1){r(e.data.msg)}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.is_submitted=!1})},},};const Ye=Vue.createApp({components:{"components-web-pusher":Re}});const Pe=Ye.mount("#vue-webpush-settings");const Ke={props:["message","donnot_close"],data(){return{new_message:""}},methods:{show(){u("#loadingBox").modal("show")},close(){u("#loadingBox").modal("hide")},setMessage(e){this.new_message=e},},template:`
	<div class="modal" id="loadingBox" tabindex="-1" role="dialog" aria-labelledby="loadingBox" aria-hidden="true"
	data-backdrop="static" data-keyboard="false" 	 
	 >
	   <div class="modal-dialog modal-dialog-centered modal-sm modal-loadingbox" role="document">
	     <div class="modal-content">
	         <div class="modal-body">
	            <div class="loading mt-2">
	              <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	            </div>
	            <p class="text-center mt-2">
	              <div v-if="!new_message">{{message}}</div>
	              <div v-if="new_message">{{new_message}}</div>
	              <div>{{donnot_close}}</div>
	            </p>	            
	         </div>
	       </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->      
	`,};const De={props:["label","ajax_url"],data(){return{is_loading:!1,error:[],data:[],package_uuid:"",current_package_uuid:"",payment_code:"",methods:"",agree:!1,payment_list:[],payment_code:""}},mounted(){this.getPlanList()},computed:{hasPlan(){if(h(this.package_uuid)){return!1}
if(this.current_package_uuid==this.package_uuid){return!1}
return!0},},methods:{show(){u(this.$refs.modal_plan).modal("show")},close(){u(this.$refs.modal_plan).modal("hide")},getPlanList(){this.is_loading=!0;axios({method:"POST",url:this.ajax_url+"/getPlanList",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details.data;this.current_package_uuid=e.data.details.package_uuid;this.package_uuid=e.data.details.package_uuid;this.payment_code=e.data.details.payment_code}else{this.data=[];this.package_uuid="";this.payment_code=""}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},confirmPlan(){this.$emit("changePlan",this.package_uuid,this.payment_code)},},template:`	
    <div ref="modal_plan" class="modal" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{label.subscription_plan}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
      
       <div class="menu-categories medium mb-3 d-flex">
         <a v-for="(item, key) in data"  class="rounded align-self-center text-center"
         @click="package_uuid=item.package_uuid"	      
         :class="{active:package_uuid==item.package_uuid}"  
         >
         <h5 class="text-truncate">{{item.title}}</h5>
         <p class="m-0 mt-1 text-truncate">
         
         <template v-if="item.promo_price_raw>0" >
           {{item.promo_price}}
         </template>
         <template v-else >
           {{item.price}}
         </template>
         
         /{{item.package_period}}</p>         
         </a>
       </div>
                                          		                                
      </div>  <!-- modal body -->      
      
      <div class="modal-footer">        
              
        <button type="button" class="btn btn-black" data-dismiss="modal">
          <span class="pl-3 pr-3">Cancel</span>
        </button>
	                
        <button type="button" @click="confirmPlan" class="btn btn-green pl-4 pr-4" :class="{ loading: is_loading }"         
         :disabled="!hasPlan"
         >
          <span>Continue</span>
          <div class="m-auto circle-loader" data-loader="circle-side"></div> 
        </button>
        
      </div> <!-- modal footer -->
      
    </div> <!--content-->
   </div> <!--dialog-->
   </div> <!--modal-->     	              
	`,};const R=Vue.createApp({data(){return{error:[],payment_code:""}},created(){this.defaultPaymentGateway()},methods:{defaultPaymentGateway(){axios({method:"POST",url:apibackend+"/defaultPaymentGateway",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.payment_code=e.data.details.payment_code}else{this.payment_code=""}})["catch"]((e)=>{}).then((e)=>{})},showPlan(e){this.$refs.planlist.show(e)},changePlan(e,t){try{this.$refs.planlist.close();this.$refs[t].changePlan(e)}catch(a){r(a.message,"danger")}},notify(e,t){r(e,t)},showLoading(){this.$refs.box.show()},closeLoading(){this.$refs.box.close()},afterChangeplan(){this.$refs.merchant_status.merchantPlanStatus()},afterCancelplan(){this.$refs.merchant_status.merchantPlanStatus()},viewInvoice(e,t){try{this.$refs[this.payment_code].invoiceDetails(e)}catch(a){r(a.message,"danger")}},refreshDatatables(){this.$refs.datatable.getTableData()},},});R.component("components-datatable",$);R.component("components-planlist",De);R.component("components-loading-box",Ke);R.component("components-merchant-status",ae);if(typeof components_bundle!=="undefined"&&components_bundle!==null){u.each(components_bundle,function(e,t){R.component(e,t)})}
const Me=R.mount("#vue-manage-plan");const ze=Vue.createApp({data(){return{available_at_specific:null}},mounted(){this.available_at_specific=this.$refs.available_at_specific.value==1?!0:!1},methods:{},}).mount("#vue-availability");
const Ae={props:["ajax_url","label","tax_in_price_list","tax_type"],data(){return{tax_uuid:0,tax_name:"",tax_in_price:0,tax_rate:0,active:!0,default_tax:!0,error:[],is_loading:!1}},methods:{show(){this.clearData();u(this.$refs.modal_tax).modal("show")},close(){u(this.$refs.modal_tax).modal("hide")},submit(){this.is_loading=!0;this.error=[];axios({method:"PUT",url:this.ajax_url+"/saveTax",data:{tax_uuid:this.tax_uuid,tax_name:this.tax_name,tax_rate:this.tax_rate,default_tax:this.default_tax,active:this.active,tax_in_price:this.tax_in_price,tax_type:this.tax_type,YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),},timeout:s,}).then((e)=>{if(e.data.code==1){this.close();r(e.data.msg,"success");this.$emit("afterSave")}else{this.error=e.data.msg}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},getTax(e){this.show();this.is_loading=!0;axios({method:"POST",url:this.ajax_url+"/getTax",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&tax_uuid="+e,timeout:s}).then((e)=>{if(e.data.code==1){this.tax_uuid=e.data.details.tax_uuid;this.tax_name=e.data.details.tax_name;this.tax_in_price=e.data.details.tax_in_price;this.tax_rate=e.data.details.tax_rate;this.default_tax=e.data.details.default_tax;this.active=e.data.details.active;this.default_tax=this.default_tax==1?!0:!1;this.active=this.active==1?!0:!1}else{r(data.msg,"danger")}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},clearData(){this.tax_uuid="";this.tax_name="";this.tax_rate=0;this.default_tax=!0;this.active=!0},deleteTax(t){bootbox.confirm({size:"small",title:"",message:"<h5>"+this.label.confirmation+"</h5>"+"<p>"+this.label.content+"</p>",centerVertical:!0,animate:!1,buttons:{cancel:{label:this.label.cancel,className:"btn btn-black small pl-4 pr-4"},confirm:{label:this.label.confirm,className:"btn btn-green small pl-4 pr-4"}},callback:(e)=>{if(e){this.taxDelete(t)}},})},taxDelete(e){this.is_loading=!0;axios({method:"POST",url:this.ajax_url+"/taxDelete",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&tax_uuid="+e,timeout:s}).then((e)=>{if(e.data.code==1){r(e.data.msg,"success");this.$emit("afterSave")}else{r(data.msg,"danger")}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},template:`	
	
	<div ref="modal_tax" class="modal" tabindex="-1" role="dialog" data-backdrop="static"  > 
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{label.title}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
            
      <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
		    <div>
		      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
		    </div>
		</div>
      
      <div class="modal-body">          
      
      <form @submit.prevent="submit" >
          <div class="form-group">
		    <label for="tax_name">{{label.tax_name}}</label>
		    <input v-model="tax_name" type="text" class="form-control form-control-text" id="tax_name" >		    
		  </div>  
		  
		  
		  <template v-if="tax_type=='standard'">
		  <div v-if="default_tax" class="input-group mb-3">  
		   <select  v-model="tax_in_price" class="custom-select form-control-select" id="tax_in_price">	
		    <option v-for="(item, index) in tax_in_price_list" :value="index" >{{item}}</option>	    
		   </select>
		  </div>
		  </template>
		  
		  <div class="form-group">
		    <label for="tax_rate">{{label.rate}}</label>
		    <input v-model="tax_rate" type="text" class="form-control form-control-text" id="tax_rate" >		    
		  </div>  
		  		
		  
		 <template v-if="tax_type=='standard' || tax_type=='euro'">
		 <div class="row">
		   <div class="col">
		   
		     <div class="custom-control custom-switch">
			  <input v-model="default_tax" type="checkbox" class="custom-control-input" id="default_tax">
			  <label class="custom-control-label" for="default_tax">{{label.default_tax}}</label>
			</div>
		   
		   </div>
		   <div class="col">
		   
		     <div class="custom-control custom-switch">
			  <input v-model="active" type="checkbox" class="custom-control-input" id="active">
			  <label class="custom-control-label" for="active">{{label.active}}</label>
			</div> 
		   
		   </div>
		 </div>
		 </template>
		 <template v-else>
		   <div class="custom-control custom-switch">
			  <input v-model="active" type="checkbox" class="custom-control-input" id="active">
			  <label class="custom-control-label" for="active">{{label.active}}</label>
			</div> 
		 </template>
		 
      </form>
      
        <div v-if="error.length>0" class="alert alert-warning mb-2 mt-2" role="alert">
	    <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
	    </div>  
      
      </div> <!-- body -->
      
       <div class="modal-footer">
          <button type="buttton" class="btn btn-black" data-dismiss="modal" aria-label="Close" >
          <span class="pl-2 pr-2" >{{label.cancel}}</span>
          </button>
          
          <button type="button" @click="submit" class="btn btn-green pl-4 pr-4" :class="{ loading: is_loading }"           
          >
          <span>{{label.save}}</span>
          <div class="m-auto circle-loader" data-loader="circle-side"></div> 
        </button>
      </div>
      
      </div>
     </div>
     </div>      
	`,};
	const Le=Vue.createApp({components:{"components-datatable":$,"components-tax":Ae},data(){return{data:[]}},mounted(){this.$refs.datatable.transaction_type=this.$refs.tax.tax_type},methods:{newTax(){this.$refs.tax.show()},afterSave(){this.$refs.datatable.getTableData()},editTax(e){this.$refs.tax.getTax(e)},deleteTax(e){this.$refs.tax.deleteTax(e)},},});const Ue=Le.mount("#vue-tax");const qe={props:["ajax_url","enabled_interval","interval_seconds"],data(){return{handle:undefined}},created(){if(this.enabled_interval){this.requestNewOrder()}},methods:{startRequest(){if(this.handle){clearInterval(this.handle)}
let e=3e4;if(typeof continues_alert_interval!=="undefined"&&continues_alert_interval!==null){e=parseFloat(continues_alert_interval)*1e3}
this.handle=setInterval(()=>{this.requestNewOrder()},e)},requestNewOrder(){axios({method:"POST",url:this.ajax_url+"/requestNewOrder",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){if(Object.keys(e.data.details).length>0){Object.entries(e.data.details).forEach(([e,t])=>{if(e==0){this.playAlert();ElementPlus.ElNotification({title:t.title,message:t.message,duration:4500})}else{setTimeout(()=>{this.playAlert();ElementPlus.ElNotification({title:t.title,message:t.message,duration:4500})},500)}})}}})["catch"]((e)=>{}).then((e)=>{this.startRequest()})},playAlert(){this.player=new Howl({src:["../assets/sound/notify.mp3","../assets/sound/notify.ogg"],html5:!0});this.player.play()},},};const Be=Vue.createApp({components:{"components-notification":te,"components-merchant-status":ae,"components-pause-order":ye,"components-pause-modal":ge,"components-resume-order-modal":xe,"components-continuesalert":qe},mounted(){},data(){return{data:[],is_load:!1}},methods:{getOrdersCount(){axios({method:"POST",url:apibackend+"/getOrdersCount",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){if(this.is_load){u(this.$refs.orders_new).find(".badge-notification").remove()}
this.data=e.data.details;if(this.data.not_viewed>0){u(this.$refs.orders_new).append('<div class="blob green badge-pill pull-right badge-notification bg-new">'+this.data.new_order+"</div>")}else{u(this.$refs.orders_new).append('<div class="badge-pill pull-right badge-notification bg-new">'+this.data.new_order+"</div>")}
u(this.$refs.orders_processing).append('<div class="badge-pill pull-right badge-notification bg-processing">'+this.data.order_processing+"</div>");
u(this.$refs.orders_ready).append('<div class="badge-pill pull-right badge-notification bg-ready">'+this.data.order_ready+"</div>");
u(this.$refs.orders_completed).append('<div class="badge-pill pull-right badge-notification bg-completed">'+this.data.completed_today+"</div>");
u(this.$refs.orders_scheduled).append('<div class="badge-pill pull-right badge-notification bg-scheduled">'+this.data.scheduled+"</div>");
u(this.$refs.orders_history).append('<div class="badge-pill pull-right badge-notification bg-history">'+this.data.all_orders+"</div>")}})["catch"]((e)=>{}).then((e)=>{this.is_load=!0})},afterClickpause(e){o(e);if(e){this.$refs.pause_modal.show()}else{this.$refs.resume_order.show()}},afterPause(e){this.$refs.pause_order.updateStatus(e)},},});const Ve=Be.mount("#vue-top-nav");const Je={props:["ajax_url","label","orders_tab","limit"],data(){return{data:[],active_tab:"all",is_loading:!1,data_failed:[]}},mounted(){this.getLastOrder(!1);setInterval(()=>this.getLastOrder(!0),6e4)},computed:{hasData(){if(this.data.length>0){return!0}
return!1},},methods:{setTab(e){this.active_tab=e;this.getLastOrder()},getLastOrder(t){if(!t){this.is_loading=!0}
axios({method:"POST",url:this.ajax_url+"/getLastTenOrder",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&filter_by="+this.active_tab+"&limit="+this.limit,timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details}else{this.data=[];this.data_failed=e.data}})["catch"]((e)=>{}).then((e)=>{if(!t){this.is_loading=!1}})},viewCustomer(e){this.$emit("viewCustomer",e)},},template:`	
	
	 <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
	    <div>
	      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	    </div>
	 </div>   
	
    <div class="card ">
    <div class="card-body">
	 
	<div class="row align-items-center">
        <div class="col col-lg-6 col-md-6 col-9">        
          <h5 class="m-0">{{label.title}}</h5>   
          <p class="m-0 text-muted">{{label.sub_title}}</p>        
        </div>
        <div class="col col-lg-6 col-md-6 col-3 ">

		  <div class="d-none d-sm-block">
          <ul class="nav nav-pills justify-content-md-end justify-content-sm-start">			  
			  <li v-for="(item, key) in orders_tab" class="nav-item">
			    <a @click="setTab(key)" :class="{active : active_tab==key}" class="nav-link py-1 px-3">{{item}}</a>
			  </li>			  
		  </ul>
		  </div>

		  <div class="d-block d-sm-none text-right">
		  
		  <div class="dropdown btn-group dropleft">
		  <button class="btn btn-sm dropdown-togglex dropleft" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		  <i class="zmdi zmdi-more-vert"></i>
		  </button>
		   <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
		     <template v-for="(item, key) in orders_tab" >
			 <a class="dropdown-item"  @click="setTab(key)" :class="{active : active_tab==key}"  >{{item}}</a>			
			 </template>
		    </div>
		  </div>

		  </div> <!-- small -->

        </div>
      </div>  
      <!--row-->	
                 
    
     <div class="mt-3 table-orders table-responsive">
         <table class="table">
          <thead>
           <tr>
             <th class="p-0 mw-200"></th>
             <th class="p-0 mw-200"></th>
             <th class="p-0 mw-200"></th>
             <th class="p-0 mw-200"></th>
             <th class="p-0 mw-200"></th>
           </tr>
          </thead>
          <tbody>
          <tr v-for="item in data">
             <td class="pl-0 align-middle">             
             
                 <div class="d-flex align-items-center">
                    <div class="mr-2">
                      <div  v-if="item.is_view==0" class="blob green mb-1"></div>
                      <div  v-if="item.is_critical==1" class="blob red"></div>
                    </div>
                    <div>
                       <div><a :href="item.view_order" class="font-weight-bold hover-text-primary mb-1">{{item.order_id}}</a></div>
                       <div><a @click="viewCustomer(item.client_id)" class="text-muted font-weight-bold hover-text-primary" href="javascript:;">{{item.customer_name}}</a></div>
                       <div class="text-muted font11">{{item.date_created}}</div>
                    </div>
                 </div>
                  
                <!--- <div>                    
                    <a @click="viewCustomer(item.client_id)" class="text-muted font-weight-bold hover-text-primary" href="javascript:;">{{item.customer_name}}</a>
                </div> -->
            </td>
           <td class="text-right align-middle">
                <span class="font-weight-bold d-block">{{item.total}}</span>
                <span class="badge payment"  :class="item.payment_status_raw" >{{item.payment_status}}</span>
            </td> 
            <td class="text-right align-middle">
                <span class="text-muted font-weight-500">{{item.payment_code}}</span>
            </td>
            <td class="text-right align-middle">
                <span class="badge order_status " :class="item.status_raw">{{item.status}}</span>
            </td>
            <td class="text-right align-middle pr-0">
                <a :href="item.view_order" class="btn btn-sm text-muted btn-light hover-bg-primary hover-text-secondary py-1 px-3 mr-2">
                    <i class="zmdi zmdi-eye"></i>
                </a>
                <a :href="item.print_pdf" target="_blank" class="btn btn-sm text-muted btn-light hover-bg-primary hover-text-secondary py-1 px-3">
                    <i class="zmdi zmdi-download"></i>
                </a>
            </td>
          </tr>
          </tbody>
         </table>
     </div>          
     
     <div v-if="!hasData" class="fixed-height40 text-center justify-content-center d-flex align-items-center">
	     <div class="flex-col">
	      <img v-if="data_failed.details" class="img-300" :src="data_failed.details.image_url" />
	      <h6 class="mt-3 text-muted font-weight-normal">{{data_failed.msg}}</h6>
	     </div>     
	  </div>  
     
     </div><!-- card body -->
    </div> <!--card-->
	`,};const He={props:["ajax_url","label","period"],data(){return{data:[],data_items:[],data_failed:[],is_loading:!1,code:null}},mounted(){this.itemSales()},methods:{itemSales(){this.is_loading=!0;axios({method:"POST",url:this.ajax_url+"/itemSales",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&period="+this.period,timeout:s}).then((e)=>{this.code=e.data.code;if(e.data.code==1){this.data=e.data.details.sales;this.data_items=e.data.details.items;this.initChart()}else{this.data=[];this.data_items=[];this.data_failed=e.data;this.is_loading=!1}})["catch"]((e)=>{this.is_loading=!1}).then((e)=>{})},initChart(){if(window.Highcharts==null){new Promise((e)=>{const t=window.document;const a="chart-script";const s=t.createElement("script");s.id=a;s.setAttribute("src","https://code.highcharts.com/highcharts.js");t.head.appendChild(s);s.onload=()=>{e()}}).then(()=>{this.renderChart()})}else{this.renderChart()}},renderChart(){Highcharts.chart(this.$refs.chart,{chart:{type:"column",height:"60%",events:{load:()=>{setTimeout(()=>{this.is_loading=!1},500)},},},title:{text:""},xAxis:{categories:this.data.category,crosshair:!0},yAxis:{min:0,title:{text:""}},tooltip:{headerFormat:'<span style="font-size:10px">{point.key}</span><table>',pointFormat:'<tr><td style="color:{series.color};padding:0"></td>'+'<td style="padding:0"><b>{point.y:.1f} '+this.label.sold+"</b></td></tr>",footerFormat:"</table>",shared:!0,useHTML:!0,},plotOptions:{column:{pointPadding:0.2,borderWidth:0}},series:[{name:"Sales",showInLegend:!1,color:"#3ecf8e",data:this.data.data}],})},},template:`	
    
    <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
	    <div>
	      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	    </div>
	 </div>   
    	 
    <div class="card ">
    <div class="card-body">
           
	    <div ref="chart"></div>
	    	    
	    <div class="row ml-2 mt-4">
	      <div v-for="item in data_items" class="col-md-4 mb-2">
	        <div class="d-flex">
	          <div class="mr-1 w-25"><b>{{item.total_sold}}</b></div>
	          <div>{{item.item_name}}</div>
	        </div>
	      </div> <!-- col -->
	    </div> <!-- row -->
	    
	    <div v-if="code==2" class="fixed-height40 text-center justify-content-center d-flex align-items-center">
		    <div class="flex-col">
		     <img v-if="data_failed.details" class="img-300" :src="data_failed.details.image_url" />
	         <h6 class="mt-3 text-muted font-weight-normal">{{data_failed.msg}}</h6>
		    </div>     
		 </div> 
  
	</div> <!--card-body-->
    </div> <!--card--> 
    
    `,};const Ge={components:{"components-item-sales":He},props:["ajax_url","label","limit","item_tab"],data(){return{data:[],is_loading:!1,data_failed:[],currentTab:"item_overview"}},mounted(){this.mostPopularItems()},computed:{hasData(){if(this.data.length>0){return!0}
return!1},},methods:{mostPopularItems(){this.is_loading=!0;axios({method:"POST",url:this.ajax_url+"/mostPopularItems",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&limit="+this.limit,timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details}else{this.data=[];this.data_failed=e.data}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},template:`	    
    
     <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
	    <div>
	      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	    </div>
	 </div>   
	 
    <div class="card">
    <div class="card-body">
    
	<div class="row">
		<div class="col col-lg-6 col-md-6 col-9">        
		<h5 v-if="item_tab[currentTab]" class="m-0">{{item_tab[currentTab].title}}</h5>   
		<p  v-if="item_tab[currentTab]" class="m-0 text-muted">{{item_tab[currentTab].sub_title}}</p>        
		</div>

		<div class="col col-lg-6 col-md-6 col-3">        
		<div class="d-none d-sm-block">
		<ul class="nav nav-pills justify-content-end">			  
			<li v-for="(item, key) in item_tab" class="nav-item">
				<a @click="currentTab=key"  :class="{active : currentTab==key}" class="nav-link py-1 px-3">{{item.title}}</a>
			</li>			  
		</ul>
		</div>

		<div class="d-block d-sm-none text-right">
		
		<div class="dropdown btn-group dropleft">
		<button class="btn btn-sm dropdown-togglex dropleft" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<i class="zmdi zmdi-more-vert"></i>
		</button>
		<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
			<template v-for="(item, key) in item_tab"  >
			<a class="dropdown-item" @click="currentTab=key"  :class="{active : currentTab==key}"  >{{item.title}}</a>			
			</template>
			</div>
		</div>

		</div>
		<!-- dropdown -->

		</div>
	</div>  
	<!--row-->	
                      
      
      <template v-if="currentTab=='item_overview'">
      <div class="mt-3 table-item table-responsive">
        <table class="table">
          <thead>
           <tr>
             <th class="p-0 mw-200"></th>
             <th class="p-0 mw-200"></th>             
           </tr>
          </thead>
          <tbody>
            <tr v-for="item in data">
             <td class="text-left align-middle">
               <div class="d-flex  align-items-center"> 
                 <div class="mr-3">
                    <a :href="item.item_link"><img :src="item.image_url" class="img-60 rounded-circle"></a>
                 </div>
                 <div class="flex-col">
                    <a :href="item.item_link" class="font-weight-bold hover-text-primary mb-1">{{item.item_name}}</a>
                    <p class="m-0 text-muted">{{item.category_name}}</p>
                 </div>
               </div> <!--flex-->
             </td>
             <td class="text-right align-middle">
               <p class="m-0 text-muted">{{item.total_sold}}</p>
             </td>
             </tr>            
          </tbody>
        </table>
       </div> 
       
        <div v-if="!hasData" class="fixed-height40 text-center justify-content-center d-flex align-items-center">
		    <div class="flex-col">
		     <img v-if="data_failed.details" class="img-300" :src="data_failed.details.image_url" />
		     <h6 class="mt-3 text-muted font-weight-normal">{{data_failed.msg}}</h6>
		    </div>     
		 </div>         
       
       </template>
       
       <template v-else>
       <components-item-sales
         :ajax_url="ajax_url"
         period="30"
         :label="label"
       >
       </components-item-sales>
       </template>            
                
    </div> <!--card body-->
   </div> 
   <!--card-->
    
    `,};const We={props:["ajax_url","label","limit"],data(){return{data:[],data2:[],is_loading:!1,data_failed:[]}},mounted(){this.mostPopularCustomer()},computed:{hasData(){if(this.data.length>0){return!0}
return!1},},methods:{mostPopularCustomer(){this.is_loading=!0;axios({method:"POST",url:this.ajax_url+"/mostPopularCustomer",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&limit="+this.limit,timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details.data;this.data2=e.data.details.data2}else{this.data=[];this.data_failed=e.data;this.data2=[]}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},viewCustomer(e){this.$emit("viewCustomer",e)},},template:`	
     
     <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
	    <div>
	      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	    </div>
	 </div>   
	 
      <div class="card">
	    <div class="card-body">
	       <h5 class="m-0 mb-3">{{label.title}}</h5>    
	       
	        <table class="table">
	          <thead>
	           <tr>
	             <th class="p-0 mw-200"></th>
	             <th class="p-0 mw-200"></th>             
	           </tr>
	          </thead>
	          <tbody>
	            <tr v-for="item in data">
	             <td class="text-left align-middle">
	               <div class="d-flex  align-items-center"> 
	                 <div class="mr-3">
	                   <a @click="viewCustomer(item.client_id)">
	                   <img :src="item.image_url" class="img-60 rounded-circle">
	                   </a>
	                 </div>
	                 <div class="flex-col">
	                    <a @click="viewCustomer(item.client_id)" href="javascript:;" class="font-weight-bold hover-text-primary mb-1">{{item.first_name}} {{item.last_name}}</a>
	                    <div><small class="text-muted">{{item.member_since}}</small></div>
	                 </div>
	               </div> <!--flex-->
	             </td>
	             <td class="text-right align-middle">
	               <p class="m-0 text-muted">{{item.total_sold}}</p>
	             </td>
	             </tr>             
	          </tbody>
	        </table>
	        
	        <div v-if="!hasData" class="fixed-height15 text-center justify-content-center d-flex align-items-center">
			    <div class="flex-col">			     
			     <h6 class="mt-3 text-muted font-weight-normal">{{data_failed.msg}}</h6>
			    </div>     
			 </div>  
			 			 
			<a :href="data2.link" class="w-100 btn btn-lg btn-info waves-effect waves-light mb-3">
			  {{data2.label}}
			</a> 	      	 			 
	       
	    </div> <!--card body-->
	   </div> 
	   <!--card-->
    `,};const Ze={props:["ajax_url","label","limit","months"],data(){return{data:[],is_loading:!1,data_failed:[],code:null}},mounted(){this.salesOverview()},computed:{hasData(){if(this.data.length>0){return!0}
return!1},},methods:{salesOverview(){this.is_loading=!0;axios({method:"POST",url:this.ajax_url+"/salesOverview",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&months="+this.months,timeout:s}).then((e)=>{this.code=e.data.code;if(e.data.code==1){this.data=e.data.details;this.initChart()}else{this.data=[];this.is_loading=!1;this.data_failed=e.data}})["catch"]((e)=>{this.is_loading=!1}).then((e)=>{})},initChart(){if(window.Highcharts==null){new Promise((e)=>{const t=window.document;const a="chart-script";const s=t.createElement("script");s.id=a;s.setAttribute("src","https://code.highcharts.com/highcharts.js");t.head.appendChild(s);s.onload=()=>{e()}}).then(()=>{this.renderChart()})}else{this.renderChart()}},renderChart(){Highcharts.chart(this.$refs.chart_sales,{chart:{type:"column",height:(18/16)*100+"%",events:{load:()=>{setTimeout(()=>{this.is_loading=!1},500)},},},title:{text:""},xAxis:{categories:this.data.category,crosshair:!0},yAxis:{min:0,title:{text:""}},tooltip:{headerFormat:'<span style="font-size:10px">{point.key}</span><table>',pointFormat:'<tr><td style="color:{series.color};padding:0">{series.name}: </td>'+'<td style="padding:0"><b>{point.y:.1f} '+this.label.sales+"</b></td></tr>",footerFormat:"</table>",shared:!0,useHTML:!0,},plotOptions:{column:{pointPadding:0.2,borderWidth:0}},series:[{name:"Sales",showInLegend:!1,data:this.data.data}],})},},template:`	
     <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
	    <div>
	      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	    </div>
	 </div>       	
	 
    <div class="card mb-3">
    <div class="card-body">
    
       <h5 class="m-0 mb-3">{{label.sales_overview}}</h5>    
        
	    <div ref="chart_sales"></div>
	            
	    <div v-if="code==2" class="fixed-height15 text-center justify-content-center d-flex align-items-center">
		    <div class="flex-col">
		     <h6 class="mt-3 text-muted font-weight-normal">{{data_failed.msg}}</h6>
		    </div>     
		 </div> 
  
	</div> <!--card-body-->
    </div> <!--card--> 
    `,};const Qe={props:["ajax_url","label","limit"],data(){return{data:[],is_loading:!1}},mounted(){this.latestReview()},methods:{latestReview(){this.is_loading=!0;axios({method:"POST",url:this.ajax_url+"/OverviewReview",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&limit="+this.limit,timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details}else{this.data=[]}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},viewCustomer(e){this.$emit("viewCustomer",e)},},template:`	
      <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
	    <div>
	      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	    </div>
	 </div>   
	 	  
      <div class="card">
	    <div class="card-body">
	    
	       <h5 class="m-0 mb-3">{{label.title}}</h5>   
	       	        
	       <h2 class="font-medium mt-2 mb-0">{{data.total}}</h2>
	       <span class="text-muted">{{data.this_month_words}}</span>
	       
	       <div class="image-box mt-3 mb-3"> 
	         <a v-for="user in data.user"  @click="viewCustomer(user.client_id)" class="mr-2" data-toggle="tooltip" data-placement="top" :title="user.first_name" :data-original-title="user.first_name">
	           <img :src="user.image_url" class="rounded-circle img-40" alt="user">
	         </a> 	         
	       </div>
	       
	       <div class="graph-rating-body mb-3">	        
	       
	        <div v-for="item in data.review_summary" class="rating-list mb-1">  
	          <div class="d-flex justify-content-between align-items-center">
	            <div class="flex-col font11">{{item.count}} {{label.star}}</div>
	            <div class="flex-col font11">{{item.in_percent}}</div>
	          </div>
	          <div class="progress">
			    <div class="progress-bar" role="progressbar" 
			    :style="{ width: item.review + '%' }"
			    :aria-valuenow="item.review" aria-valuemin="0" aria-valuemax="100"></div>
			  </div>
			</div> <!-- rating-list -->
						
	       </div> <!-- graph-rating-body -->
	       	       
	      
	      <a :href="data.link_to_review" class="w-100 btn btn-lg btn-info waves-effect waves-light mb-3">
	      {{label.all_review}}
	      </a> 	      
	       
	   </div> <!--card body-->
	  </div> <!--card-->      
    `,};const Xe={props:["ajax_url","label","merchant_type"],data(){return{data:[],is_loading:!1}},mounted(){this.salesSummary()},methods:{salesSummary(){axios({method:"POST",url:this.ajax_url+"/salesSummary",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{var t,a,s;if(e.data.code==1){t=this.count_up=new countUp.CountUp(this.$refs.your_balance,e.data.details.balance,e.data.details.price_format);t.start();a=this.count_up=new countUp.CountUp(this.$refs.sales_this_week,e.data.details.sales_week,e.data.details.price_format);a.start();s=this.count_up=new countUp.CountUp(this.$refs.earning_this_week,e.data.details.earning_week,e.data.details.price_format);s.start()}else{}})["catch"]((e)=>{}).then((e)=>{})},},template:`	    
    <div class="row">
	    <div class="col mb-3 mb-xl-0">
	      <div class="card">
	        <div class="card-body" style="padding:10px;">
	        
	          <div id="boxes" class="d-flex align-items-center">
	            <div class="mr-2"><div class="rounded box box-1 d-flex align-items-center  justify-content-center"><i class="zmdi zmdi-balance-wallet"></i></div></div>
	            <div>
	               <h6 class="m-0 text-muted font-weight-normal">{{label.sales_this_week}}</h6>
	               <h6 class="m-0 position-relative" ref="sales_this_week">0
	                  <div class="skeleton-placeholder" style="height:17px;width:100%;"></div>
	               </h6>
	            </div>
	          </div><!--flex-->       
	          
	        </div> <!--card body-->       
	      </div> <!--card-->
	    </div> <!-- col -->
	    
	    <div class="col mb-3 mb-xl-0">
	      <div class="card">
	        <div class="card-body" style="padding:10px;">
	        
	          <div id="boxes" class="d-flex align-items-center">
	            <div class="mr-2"><div class="rounded box box-2 d-flex align-items-center  justify-content-center"><i class="zmdi zmdi-balance-wallet"></i></div></div>
	            <div>
	               <h6 class="m-0 text-muted font-weight-normal">{{label.earning_this_week}}</h6>
	               <h6 class="m-0 position-relative" ref="earning_this_week">0
	                 <div class="skeleton-placeholder" style="height:17px;width:100%;"></div>
	               </h6>
	            </div>
	          </div><!--flex-->       
	          
	        </div> <!--card body-->       
	      </div> <!--card-->
	    </div> <!-- col -->
	    
	     <div class="col mb-3 mb-xl-0">
	      <div class="card">
	        <div class="card-body" style="padding:10px;">
	        
	          <div id="boxes" class="d-flex align-items-center">
	            <div class="mr-2"><div class="rounded box box-3 d-flex align-items-center  justify-content-center"><i class="zmdi zmdi-balance-wallet"></i></div></div>
	            <div>
	               <h6 class="m-0 text-muted font-weight-normal">{{label.your_balance}}</h6>
	               <h6 class="m-0 position-relative" ref="your_balance">0
	                  <div class="skeleton-placeholder" style="height:17px;width:100%;"></div>
	               </h6>
	            </div>
	          </div><!--flex-->       
	          
	        </div> <!--card body-->       
	      </div> <!--card-->
	    </div> <!-- col -->
	    
	  </div> <!--row--> 
    `,};const et={props:["ajax_url","label","limit"],data(){return{data:[],is_loading:!1}},mounted(){this.DailyStatistic()},methods:{DailyStatistic(){this.is_loading=!0;axios({method:"POST",url:this.ajax_url+"/DailyStatistic",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details;var t={decimalPlaces:0,separator:",",decimal:"."};var a=new countUp.CountUp(this.$refs.stats_order_received,e.data.details.order_received,t);a.start();a=this.count_up=new countUp.CountUp(this.$refs.stats_today_delivered,e.data.details.today_delivered,t);a.start();var t=e.data.details.price_format;a=this.count_up=new countUp.CountUp(this.$refs.stats_today_sales,e.data.details.today_sales,t);a.start();a=this.count_up=new countUp.CountUp(this.$refs.stats_total_refund,e.data.details.total_refund,t);a.start()}else{this.data=[]}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},template:`	
    
      <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
	    <div>
	      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	    </div>
	 </div>   
    
    <div class="row mb-3">
          <div class="col">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                     <div class="flex-col mr-3"><h1 class="m-0"><i class="zmdi zmdi-store"></i></h1></div>
                     <div class="flex-col">
                       <h3 class="mb-1 text-danger" ref="stats_order_received">0</h3>
                       <h5 class="m-0 text-secondary">{{label.order_received}}</h5>
                     </div>
                  </div>
                </div>
              </div>
          </div> <!--col-->

           <div class="col">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                     <div class="flex-col mr-3"><h1 class="m-0"><i class="zmdi zmdi-truck"></i></h1></div>
                     <div class="flex-col">
                       <h3 class="mb-1 text-green" ref="stats_today_delivered">0</h3>
                       <h5 class="m-0 text-secondary">{{label.today_delivered}}</h5>
                     </div>
                  </div>
                </div>
              </div>
          </div> <!--col-->
          
        </div> <!--row-->
        
      </div> <!--relative-->
      
       <div class="dashboard-statistic position-relative mb-3">
        
        <div class="row">
          <div class="col  mb-3 mb-xl-0">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                     <div class="flex-col mr-3"><h1 class="m-0"><i class="zmdi zmdi-balance-wallet"></i></h1></div>
                     <div class="flex-col">
                       <h3 class="mb-1 text-violet" ref="stats_today_sales">0</h3>
                       <h5 class="m-0 text-secondary">{{label.today_sales}}</h5>
                     </div>
                  </div>
                </div>
              </div>
          </div> <!--col-->

           <div class="col  mb-3 mb-xl-0">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                     <div class="flex-col mr-3"><h1 class="m-0"><i class="zmdi zmdi-balance-wallet"></i></h1></div>
                     <div class="flex-col">
                       <h3 class="mb-1 text-orange" ref="stats_total_refund">0</h3>
                       <h5 class="m-0 text-secondary">{{label.total_refund}}</h5>
                     </div>
                  </div>
                </div>
              </div>
          </div> <!--col-->
          
        </div> <!--row-->
    
    `,};const tt=Vue.createApp({components:{"components-last-orders":Je,"components-customer-details":O,"components-popular-items":Ge,"components-popular-customer":We,"components-chart-sales":Ze,"components-latest-review":Qe,"components-sales-summary":Xe,"components-daily-statistic":et,},data(){return{data:[],is_load:!1,client_id:null,dashboard_access:[]}},mounted(){this.getMerchantSummary();if(typeof dashboard_access!=="undefined"&&dashboard_access!==null){this.dashboard_access=JSON.parse(dashboard_access)}},methods:{hasPermission(e){if(this.dashboard_access.includes(e)===!1){return!1}
return!0},getMerchantSummary(){this.is_loading=!0;axios({method:"POST",url:apibackend+"/getOrderSummary",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){var t={decimalPlaces:0,separator:",",decimal:"."};var a=new countUp.CountUp(this.$refs.summary_orders,e.data.details.orders,t);a.start();var s=new countUp.CountUp(this.$refs.summary_cancel,e.data.details.order_cancel,t);s.start();var t=e.data.details.price_format;var i=(this.count_up=new countUp.CountUp(this.$refs.summary_total,e.data.details.total,t));i.start();var l=(this.count_up=new countUp.CountUp(this.$refs.total_refund,e.data.details.total_refund,t));l.start()}else{}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},viewCustomer(e){this.client_id=e;setTimeout(()=>{this.$refs.customer.show()},1)},refreshLastOrder(){this.$refs.last_order.getLastOrder();this.$refs.daily_statistic.DailyStatistic()},refreshDailyStatistic(){this.$refs.daily_statistic.DailyStatistic()},},});const at=tt.mount("#vue-dashboard");const st=Vue.createApp({components:{"components-datatable":$},data(){return{is_loading:!1}},methods:{},});const it=st.mount("#vue-tables");const lt={props:["ajax_url","label"],data(){return{data:[],data_items:[],data_failed:[],is_loading:!1,code:null,period:null}},mounted(){this.itemSales()},methods:{itemSales(){this.is_loading=!0;axios({method:"POST",url:apibackend+"/itemSalesSummary",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&period="+this.period,timeout:s}).then((e)=>{this.code=e.data.code;if(e.data.code==1){this.data=e.data.details.sales;this.data_items=e.data.details.items;this.initChart()}else{this.data=[];this.data_items=[];this.data_failed=e.data;this.is_loading=!1}})["catch"]((e)=>{this.is_loading=!1}).then((e)=>{})},initChart(){if(window.Highcharts==null){new Promise((e)=>{const t=window.document;const a="chart-script";const s=t.createElement("script");s.id=a;s.setAttribute("src","https://code.highcharts.com/highcharts.js");t.head.appendChild(s);s.onload=()=>{e()}}).then(()=>{this.renderChart()})}else{this.renderChart()}},renderChart(){Highcharts.chart(this.$refs.chart,{chart:{type:"column",height:"60%",events:{load:()=>{setTimeout(()=>{this.is_loading=!1},500)},},},title:{text:""},xAxis:{categories:this.data.category,crosshair:!0},yAxis:{min:0,title:{text:""}},tooltip:{headerFormat:'<span style="font-size:10px">{point.key}</span><table>',pointFormat:'<tr><td style="color:{series.color};padding:0"></td>'+'<td style="padding:0"><b>{point.y:.1f} '+this.label.sold+"</b></td></tr>",footerFormat:"</table>",shared:!0,useHTML:!0,},colors:this.data.colors,plotOptions:{column:{pointPadding:0.2,borderWidth:0,colorByPoint:!0}},series:[{name:"Sales",showInLegend:!1,color:"#3ecf8e",data:this.data.data}],})},},template:`	
    
    <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
	    <div>
	      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	    </div>
	 </div>   
    
    <div class="card ">
    <div class="card-body">
       
	    <div ref="chart"></div>
	    	    
	    <div class="row ml-2 mt-4">
	      <div v-for="item in data_items" class="col-md-4 mb-2">
	        <div class="d-flex">
	          <div class="mr-1 w-25"><b>{{item.total_sold}}</b></div>
	          <div>{{item.item_name}}</div>
	        </div>
	      </div> <!-- col -->
	    </div> <!-- row -->
	    
	    <div v-if="code==2" class="fixed-height40 text-center justify-content-center d-flex align-items-center">
		    <div class="flex-col">
		     <img v-if="data_failed.details" class="img-300" :src="data_failed.details.image_url" />
	         <h6 class="mt-3 text-muted font-weight-normal">{{data_failed.msg}}</h6>
		    </div>     
		 </div> 
  
	</div> <!--card-body-->
    </div> <!--card--> 
    
    `,};const rt=Vue.createApp({components:{"components-datatable":$,"components-report-sales-summary-chart":lt},data(){return{is_loading:!1}},methods:{},});const ot=rt.mount("#vue-report-sales-summary");const dt={props:["ajax_url","label"],data(){return{data:[],is_loading:!1,first_name:"",last_name:"",email_address:"",contact_phone:""}},mounted(){},computed:{hasData(){if(h(this.first_name)||h(this.last_name)||h(this.email_address)||h(this.contact_phone)){return!1}
return!0},},methods:{show(){u(this.$refs.customer_entry_modal).modal("show")},close(){u(this.$refs.customer_entry_modal).modal("hide")},submit(){this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/createCustomer",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),first_name:this.first_name,last_name:this.last_name,email_address:this.email_address,contact_phone:this.contact_phone},timeout:s,}).then((e)=>{if(e.data.code==1){r(e.data.msg);this.$emit("afterSavecustomer",e.data.details)}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},template:`		    
	<div ref="customer_entry_modal" class="modal" tabindex="-1" role="dialog" data-backdrop="static"  >
	    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
	    <div class="modal-content">

		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLongTitle">{{label.customer}}</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
	      
	      <div class="modal-body">
	      	      
	      <form @submit.prevent="submit"  class="forms mt-2">
		  
	      <div class="row">
	        <div class="col">
	           <div class="form-label-group">
			    <input v-model="first_name" class="form-control form-control-text" placeholder="" id="first_name" type="text" maxlength="255">
			    <label for="first_name" class="required">{{label.first_name}}</label>
			  </div>
	        </div> <!-- col -->
	        
	        <div class="col">
	           <div class="form-label-group">
			    <input v-model="last_name" class="form-control form-control-text" placeholder="" id="last_name" type="text" maxlength="255">
			    <label for="last_name" class="required">{{label.last_name}}</label>
			  </div>
	        </div> <!-- col -->
	        
	      </div><!-- row -->	      
	      
	       <div class="row">
	        <div class="col">
	           <div class="form-label-group">
			    <input v-model="email_address" class="form-control form-control-text" placeholder="" id="email_address" type="text" maxlength="200">
			    <label for="email_address" class="required">{{label.emaiL_address}}</label>
			  </div>
	        </div> <!-- col -->
	        
	        <div class="col">
	           <div class="form-label-group">
			    <input v-model="contact_phone" class="form-control form-control-text" placeholder="" id="contact_phone" type="text" maxlength="20">
			    <label for="contact_phone" class="required">{{label.contact_phone}}</label>
			  </div>
	        </div> <!-- col -->
	        
	      </div><!-- row -->	      
	      
		  </form>
	      
	      </div>      
	      <div class="modal-footer">
	        <button type="button" @click="submit" class="btn btn-green w-100" :class="{ loading: is_loading }"         
	         :disabled="!hasData"
	         >
	          <span>{{label.submit}}</span>
	          <div class="m-auto circle-loader" data-loader="circle-side"></div> 
	        </button>
	      </div>
	      
	    </div>
	  </div>
	</div>  
    `,};const nt={props:["label","modelValue","formatted_address","enabled_locate"],emits:["update:modelValue"],data(){return{q:"",awaitingSearch:!1,show_list:!1,data:[],error:[],hasSelected:!1,loading:!1,addressData:[],auto_locate:!1}},created(){this.q=this.formatted_address},watch:{formatted_address(e,t){this.q=e},q(e,t){this.$emit("update:modelValue",e);if(!this.awaitingSearch){if(h(e)){return!1}
if(this.q.length>20){return!1}
this.show_list=!0;setTimeout(()=>{axios({method:"POST",url:apibackend+"/getlocationAutocomplete",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&q="+this.q,timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details.data}else{this.data=[]}})["catch"]((e)=>{}).then((e)=>{this.awaitingSearch=!1})},1e3)}
this.data=[];this.awaitingSearch=!0},},computed:{hasData(){if(this.data.length>0){return!0}
return!1},},methods:{showList(){this.show_list=!0},clearData(){this.data=[];this.q=""},setData(e){this.clearData();this.q=e.description;this.$emit("update:modelValue",e.description);this.$emit("afterChoose",e)},locateLocation(){this.loading=!0;if(navigator.geolocation){navigator.geolocation.getCurrentPosition((e)=>{this.reverseGeocoding(e.coords.latitude,e.coords.longitude)},(e)=>{this.loading=!1;ElementPlus.ElNotification({title:"",message:e.message,position:"bottom-right",type:"warning"})})}else{this.loading=!1;ElementPlus.ElNotification({title:"",message:"Your browser doesn't support geolocation.",position:"bottom-right",type:"warning"})}},reverseGeocoding(e,t){axios({method:"POST",url:apibackend+"/reverseGeocoding",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&lat="+e+"&lng="+t,timeout:s}).then((e)=>{if(e.data.code==1){this.q=e.data.details.data.address.formatted_address;this.addressData=e.data.details.data;if(this.auto_locate){this.$emit("afterGetcurrentlocation",this.addressData)}}else{this.addressData=[];ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"]((e)=>{}).then((e)=>{this.loading=!1;this.auto_locate=!1})},setAddressData(e){this.addressData=e},setLoading(e){this.loading=e},onSubmit(){if(Object.keys(this.addressData).length>0){this.$emit("afterGetcurrentlocation",this.addressData)}else{this.auto_locate=!0;this.locateLocation()}},},template:`	
	<div class="d-flex flex-sm-row flex-column align-items-center justify-content-between">
		<div class="position-relative search-geocomplete" v-loading="loading" :class="{width_87 : enabled_locate , 'w-100' : !enabled_locate}"  > 	
			<div v-if="!awaitingSearch" class="img-20 m-auto pin_placeholder icon"></div>
			<div v-if="awaitingSearch" class="icon" data-loader="circle"></div>    

			<input v-model="q" class="form-control form-control-text" 
			:placeholder="label.enter_address" >
			
			<template v-if="hasData">
			<div @click="clearData" class="icon-remove"><i class="zmdi zmdi-close"></i></div>
			</template>
			<template v-else>
			<div v-if="enabled_locate" @click="locateLocation" class="icon-remove"><i class="zmdi zmdi-my-location font20"></i></div>
			</template>

			<div class="dropdown-menu" :class="{show :hasData}">
			<template v-for="items in data">
				<button @click="setData(items)" type="button" class="dropdown-item" data-value="1">
					{{items.description}}
				</button>
			</template>
			</div>
		</div>    
		<div v-if="enabled_locate" class="flex-enabled-locate">
		<button @click="onSubmit" type="button" class="btn btn-green pl-3 pr-3" :disabled="loading">
		   <i class="zmdi zmdi-arrow-right font25"></i>
		</button>
		</div>	
	</div>      			
	`,};const ct={props:["keys","provider","zoom","center","markers","size"],template:`     	
    <div ref="cmaps" class="map border" :class="size"></div>  	
    `,data(){return{cmapsMarker:[],latLng:[],cmaps:undefined,bounds:undefined}},mounted(){this.renderMap()},watch:{markers(e,t){this.renderMap()},},methods:{renderMap(){try{switch(this.provider){case "google.maps":this.bounds=new window.google.maps.LatLngBounds();if(typeof this.cmaps!=="undefined"&&this.cmaps!==null&&Object.keys(this.cmapsMarker).length>0){this.moveAllMarker()}else{this.cmaps=new window.google.maps.Map(this.$refs.cmaps,{center:{lat:parseFloat(this.center.lat),lng:parseFloat(this.center.lng)},zoom:parseInt(this.zoom),disableDefaultUI:!0});Object.entries(this.markers).forEach(([e,t])=>{this.addMarker({position:{lat:parseFloat(t.lat),lng:parseFloat(t.lng)},map:this.cmaps,draggable:t.draggable==1?!0:!1,label:t.label},t.id,t.draggable)})}
break;case "mapbox":if(typeof this.cmaps!=="undefined"&&this.cmaps!==null&&Object.keys(this.cmapsMarker).length>0){this.moveAllMarker()}else{mapboxgl.accessToken=this.keys;this.bounds=new mapboxgl.LngLatBounds();this.cmaps=new mapboxgl.Map({container:this.$refs.cmaps,style:"mapbox://styles/mapbox/streets-v12",center:[parseFloat(this.center.lng),parseFloat(this.center.lat)],zoom:14});this.cmaps.on("error",(e)=>{o(e.error.message)});Object.entries(this.markers).forEach(([e,t])=>{this.addMarker({position:{lat:parseFloat(t.lat),lng:parseFloat(t.lng)},map:this.cmaps,draggable:t.draggable==1?!0:!1},t.id,t.draggable)});this.FitBounds()}
break}}catch(e){console.error(e)}},addMarker(e,a,t){try{switch(this.provider){case "google.maps":this.cmapsMarker[a]=new window.google.maps.Marker(e);this.cmaps.panTo(new window.google.maps.LatLng(e.position.lat,e.position.lng));this.bounds.extend(this.cmapsMarker[a].position);if(t===!0){window.google.maps.event.addListener(this.cmapsMarker[a],"drag",(e)=>{this.$emit("dragMarker",!0)});window.google.maps.event.addListener(this.cmapsMarker[a],"dragend",(e)=>{const t=e.latLng;this.latLng={lat:t.lat(),lng:t.lng()};this.$emit("dragMarker",!1);this.$emit("afterSelectmap",t.lat(),t.lng())})}
break;case "mapbox":this.cmapsMarker[a]=new mapboxgl.Marker(e).setLngLat([e.position.lng,e.position.lat]).addTo(this.cmaps);this.bounds.extend(new mapboxgl.LngLat(e.position.lng,e.position.lat));if(t===!0){this.cmapsMarker[a].on("dragend",(e)=>{const t=this.cmapsMarker[a].getLngLat();o(t.lat+"=>"+t.lng);this.$emit("afterSelectmap",t.lat,t.lng)});this.mapBoxResize()}
break}}catch(s){console.error(s)}},mapBoxResize(){if(this.provider=="mapbox"){setTimeout(()=>{this.cmaps.resize()},500)}},moveAllMarker(){console.log("moveAllMarker");console.log(this.markers);if(this.provider=="google.maps"){if(Object.keys(this.markers).length>0){Object.entries(this.markers).forEach(([e,t])=>{let a=new google.maps.LatLng(parseFloat(t.lat),parseFloat(t.lng));if(!h(this.cmapsMarker[t.id])){this.cmapsMarker[t.id].setPosition(a)}});if(Object.keys(this.markers).length>1){this.FitBounds()}else{this.cmaps.panTo(new window.google.maps.LatLng(this.markers[0].lat,this.markers[0].lng))}}}else{if(Object.keys(this.markers).length>0){Object.entries(this.markers).forEach(([e,t])=>{if(!h(this.cmapsMarker[t.id])){this.cmapsMarker[t.id].setLngLat([t.lng,t.lat]).addTo(this.cmaps)}});if(Object.keys(this.markers).length>1){this.FitBounds()}else{this.cmaps.flyTo({center:[this.markers[0].lng,this.markers[0].lat],essential:!0,zoom:14})}}}},centerMap(){this.FitBounds()},FitBounds(){try{switch(this.provider){case "google.maps":if(!h(this.bounds)){this.cmaps.fitBounds(this.bounds)}
break;case "mapbox":if(!h(this.bounds)){this.cmaps.fitBounds(this.bounds,{duration:0,padding:50})}
break}}catch(e){}},setCenter(e,t){try{switch(this.provider){case "google.maps":this.cmaps.setCenter(new window.google.maps.LatLng(e,t));break;case "mapbox":this.cmaps.flyTo({center:[t,e],essential:!0});break}}catch(a){console.error(a)}},setPlusZoom(){o("setPlusZoom");this.cmaps.setZoom(this.cmaps.getZoom()+2)},setLessZoom(){o("setLessZoom");this.cmaps.setZoom(this.cmaps.getZoom()-2)},},};const mt={props:["keys","provider","zoom","center","label","order_uuid","transaction_type"],components:{"component-auto-complete":nt,"components-maps":ct},data(){return{data:[],dialog_address:!1,markers:[],address_data:[],loading:!1,drag:!1,address1:"",formatted_address:"",address_label:"Home",delivery_options:"Leave it at my door",location_name:"",delivery_instructions:"",}},created(){this.markers=[{id:0,lat:parseFloat(this.center.lat),lng:parseFloat(this.center.lng),draggable:!0}]},computed:{hasFormatAddress(){if(Object.keys(this.address_data).length>0){return!0}
return!1},getFormatAddress(){return this.address_data},hasDeliveryAddress(){if(Object.keys(this.address_data).length>0){return!0}
return!1},},watch:{address_data(e,t){if(Object.keys(e).length>0){this.address1=e.address.address1;this.formatted_address=e.address.formatted_address;this.dataMarkers(e.latitude,e.longitude)}},order_uuid(e,t){this.getAddressdetails()},},methods:{show(){this.dialog_address=!0},dataMarkers(e,t){this.markers=[{id:0,lat:parseFloat(e),lng:parseFloat(t),draggable:!0}]},setLoading(e){this.loading=e},afterChoose(e){o(e);this.getLocationDetails(e.id)},getAddressdetails(){axios({method:"POST",url:apibackend+"/getAddressdetails",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&order_uuid="+this.order_uuid,timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details;this.address_data=e.data.details.data;if(Object.keys(e.data.details.address_data).length>0){this.address_label=e.data.details.address_data.address_label;this.delivery_options=e.data.details.address_data.delivery_options;this.delivery_instructions=e.data.details.address_data.delivery_instructions;this.location_name=e.data.details.address_data.location_name}}else{this.data=[]}})["catch"]((e)=>{}).then((e)=>{})},getLocationDetails(e){axios({method:"POST",url:apibackend+"/getLocationDetails",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&id="+e+"&saveplace=0",timeout:s}).then((e)=>{if(e.data.code==1){this.dataMarkers(e.data.details.data.latitude,e.data.details.data.longitude);this.address_data=e.data.details.data}else{}})["catch"]((e)=>{}).then((e)=>{})},afterSelectmap(t,a){this.loading=!0;axios({method:"POST",url:apibackend+"/reverseGeocoding",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&lat="+t+"&lng="+a,timeout:s}).then((e)=>{if(e.data.code==1){if(Object.keys(e.data.details.data).length>0){this.address_data=e.data.details.data;this.address_data.latitude=t;this.address_data.longitude=a}}else{}})["catch"]((e)=>{}).then((e)=>{this.loading=!1})},dragMarker(e){this.drag=e},setNewAdress(){this.$emit("afterChangeaddress",this.address_data)},saveAddress(){this.loading=!0;axios({method:"PUT",url:apibackend+"/saveAddress",data:{order_uuid:this.order_uuid,YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),address_label:this.address_label,delivery_options:this.delivery_options,address_data:this.address_data,transaction_type:this.transaction_type,address1:this.address1,formatted_address:this.formatted_address,location_name:this.location_name,delivery_instructions:this.delivery_instructions,},timeout:s,}).then((e)=>{if(e.data.code==1){this.dialog_address=!1;this.$emit("refreshOrder")}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"]((e)=>{}).then((e)=>{this.loading=!1})},},template:"#xtemplate_delivery_address",};const ut={components:{"components-customer-entry":dt,"money-format":t,"components-customer-address":mt},props:["ajax_url","label","limit","order_uuid"],data(){return{is_loading:!1,items:[],order_summary:[],summary_total:0,items_row:[],item_qty:0,promo_code:"",promo_loading:!1,pay_left:0,change:0,receive_amount:0,payment_code:"",payment_list:[],create_payment_loading:!1,client_id:0,order_notes:"",transaction_type:"",transaction_list:[],order_status:"",order_status_list:[],guest_number:1,room_list:[],table_list:[],room_id:"",table_id:"",payment_reference:"",discount:0,loading_discount:!1,inner_loading:!1,additional_fee:0,additional_fee_type:"",additional_list:[],whento_deliver:"now",delivery_option:[],opening_hours:[],delivery_date:"",delivery_time:"",delivery_address:"",}},computed:{hasData(){if(this.items.length>0){return!0}
return!1},hasCoopon(){if(!h(this.promo_code)){return!0}
return!1},hasValidPayment(){if(h(this.transaction_type)){return!1}
if(this.receive_amount<=0){return!1}
if(h(this.payment_code)){return!1}
if(this.pay_left.toFixed(2)>0){return!1}
return!0},getTimelist(){if(Object.keys(this.opening_hours).length>0){if(this.opening_hours.time_ranges[this.delivery_date]){return this.opening_hours.time_ranges[this.delivery_date]}}
return[]},},mounted(){this.initeSelect2();this.posAttributes()},watch:{item_qty(e,t){this.inner_loading=!0;axios({method:"POST",url:this.ajax_url+"/updatePosQty",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&qty="+e+"&item_row="+this.items_row.item_row,timeout:s}).then((e)=>{if(e.data.code==1){this.$emit("refreshOrder")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.inner_loading=!1})},receive_amount(e,t){this.pay_left=parseFloat(this.summary_total)-parseFloat(e);if(this.pay_left<=0){this.pay_left=0}
this.change=parseFloat(e)-parseFloat(this.summary_total);if(this.change<=0){this.change=0}},},methods:{SetAddress(e){o("SetAddress");o(e)},showAddress(){this.$refs.customer_address.show()},POSdetails(){var e=[];axios({method:"put",url:this.ajax_url+"/orderDetails",data:{order_uuid:this.order_uuid,YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),payload:e},timeout:s}).then((e)=>{if(e.data.code==1){this.items=e.data.details.data.items;this.order_summary=e.data.details.data.summary;this.summary_total=e.data.details.data.summary_total;this.transaction_type=e.data.details.data.order.order_info.order_type;this.delivery_address=e.data.details.data.order.order_info.delivery_address}else{this.items=[];this.order_summary=[];this.summary_total=0;this.delivery_address=""}})["catch"]((e)=>{}).then((e)=>{})},resetPos(){bootbox.confirm({size:"small",title:"",message:"<h5>"+this.label.clear_items+"</h5>"+"<p>"+this.label.are_you_sure+"</p>",centerVertical:!0,animate:!1,buttons:{cancel:{label:this.label.cancel,className:"btn btn-black small pl-4 pr-4"},confirm:{label:this.label.confirm,className:"btn btn-green small pl-4 pr-4"}},callback:(e)=>{if(e){this.clearItemPos()}},})},clearItemPos(){axios({method:"POST",url:this.ajax_url+"/resetPos",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&order_uuid="+this.order_uuid,timeout:s}).then((e)=>{o(e.data.code);if(e.data.code==1){this.$emit("afterReset")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{})},removeItem(e){axios({method:"POST",url:this.ajax_url+"/removeItem",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&item_row="+e.item_row,timeout:s}).then((e)=>{o(e.data.code);if(e.data.code==1){this.$emit("refreshOrder")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{})},changeQty(e,t){this.items_row=e;this.item_qty=this.items_row.qty;if(t=="add"){this.items_row.qty++;this.item_qty++}else{this.items_row.qty>1?this.items_row.qty--:1;this.item_qty>1?this.item_qty--:1}},initeSelect2(){u(this.$refs.customer).select2({placeholder:this.label.walkin_customer,width:"resolve",language:{searching:()=>{return this.label.searching},noResults:()=>{return this.label.no_results},},ajax:{delay:250,url:this.ajax_url+"/searchCustomer",type:"PUT",contentType:"application/json",data:function(e){var t={search:e.term,YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),POS:!0};return JSON.stringify(t)},},})},showPromo(){this.$refs.promo_code.focus();u(this.$refs.promo_modal).modal("show")},closePromo(){u(this.$refs.promo_modal).modal("hide")},applyPromoCode(){this.promo_loading=!0;axios({method:"PUT",url:this.ajax_url+"/applyPromoCode",data:{order_uuid:this.order_uuid,YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),promo_code:this.promo_code},timeout:s}).then((e)=>{if(e.data.code==1){this.promo_code="";this.closePromo();this.$emit("refreshOrder")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.promo_loading=!1})},removeVoucher(){this.inner_loading=!0;axios({method:"PUT",url:this.ajax_url+"/removeVoucher",data:{order_uuid:this.order_uuid,YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content")},timeout:s}).then((e)=>{if(e.data.code==1){this.$emit("refreshOrder")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.inner_loading=!1})},showCustomer(){this.$refs.customer_entry.show()},closeCustomer(){},afterSavecustomer(e){o(e);this.$refs.customer_entry.close();u(this.$refs.customer).select2("trigger","select",{data:{id:e.client_id,text:e.client_name}})},showPayment(){this.receive_amount=this.summary_total;u(this.$refs.submit_order_modal).modal("show")},clearFields(){this.room_id="";this.table_id="";this.guest_number=1;this.payment_reference="";this.order_notes=""},closePayment(){u(this.$refs.submit_order_modal).modal("hide")},posAttributes(){axios({method:"POST",url:this.ajax_url+"/posAttributes",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.payment_list=e.data.details.data;this.payment_code=e.data.details.default_payment;this.transaction_list=e.data.details.transaction_list;this.transaction_type=e.data.details.transaction_type;this.order_status_list=e.data.details.order_status_list;this.order_status=e.data.details.order_status;this.room_list=e.data.details.room_list;this.table_list=e.data.details.table_list;this.additional_list=e.data.details.additional_list;this.delivery_option=e.data.details.delivery_option;this.opening_hours=e.data.details.opening_hours}else{this.payment_list=[];this.payment_code="";this.transaction_list=[];this.transaction_type="";this.order_status_list=[];this.delivery_option=[];this.opening_hours=[]}})["catch"]((e)=>{}).then((e)=>{})},submitOrder(){this.create_payment_loading=!0;this.client_id=u(this.$refs.customer).find(":selected").val();axios({method:"PUT",url:this.ajax_url+"/submitPOSOrder",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:this.order_uuid,receive_amount:this.receive_amount,payment_code:this.payment_code,order_notes:this.order_notes,client_id:this.client_id,order_change:this.change,transaction_type:this.transaction_type,order_status:this.order_status,guest_number:this.guest_number,room_id:this.room_id,table_id:this.table_id,payment_reference:this.payment_reference,whento_deliver:this.whento_deliver,delivery_date:this.delivery_date,delivery_time:this.delivery_time,},timeout:s,}).then((e)=>{if(e.data.code==1){this.clearFields();this.closePayment();this.$emit("afterCreateorder",this.order_uuid)}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.create_payment_loading=!1})},roomChange(){this.table_id=""},showDiscount(){u(this.$refs.promo_discount).modal("show")},applyDiscount(){this.loading_discount=!0;axios({method:"POST",url:this.ajax_url+"/applyDiscount",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&discount="+this.discount+"&order_uuid="+this.order_uuid,timeout:s}).then((e)=>{if(e.data.code==1){r(e.data.msg);u(this.$refs.promo_discount).modal("hide");this.$emit("refreshOrder")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.loading_discount=!1})},removeDiscount(){this.inner_loading=!0;axios({method:"POST",url:this.ajax_url+"/removeDiscount",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&order_uuid="+this.order_uuid,timeout:s}).then((e)=>{if(e.data.code==1){this.$emit("refreshOrder")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.inner_loading=!1})},addAdditionalFee(){this.inner_loading=!0;axios({method:"POST",url:this.ajax_url+"/addAdditionalFee",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&order_uuid="+this.order_uuid+"&additional_fee="+this.additional_fee+"&additional_fee_type="+this.additional_fee_type,timeout:s,}).then((e)=>{if(e.data.code==1){this.$emit("refreshOrder")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.inner_loading=!1})},removeAdditionalFee(e){this.inner_loading=!0;axios({method:"POST",url:this.ajax_url+"/removeAdditionalFee",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&order_uuid="+this.order_uuid+"&additional_fee_type="+e,timeout:s}).then((e)=>{if(e.data.code==1){this.$emit("refreshOrder")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.inner_loading=!1})},udatePosTransaction(){this.inner_loading=!0;axios({method:"POST",url:this.ajax_url+"/udatePosTransaction",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&order_uuid="+this.order_uuid+"&transaction_type="+this.transaction_type,timeout:s,}).then((e)=>{if(e.data.code==1){this.$emit("refreshOrder")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.inner_loading=!1})},},template:"#xtemplate_order_details_pos",};const Y=Vue.createApp({components:{"components-menu":b,"components-item-details":T,"components-order-pos":ut,"components-order-print":C},data(){return{is_loading:!1,order_uuid:"",order_type:"",order_uuid_print:""}},mounted(){this.createOrder()},methods:{createOrder(){this.is_loading=!0;axios({method:"POST",url:apibackend+"/createOrder",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.order_uuid=e.data.details.order_uuid;this.order_type=e.data.details.order_type;setTimeout(()=>{this.$refs.pos_details.POSdetails()},100)}else{this.order_uuid="";this.order_type=""}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},showItemDetails(e){this.$refs.item.show(e)},refreshOrderInformation(){this.$refs.pos_details.POSdetails()},afterReset(){this.createOrder()},afterCreateorder(e){this.order_uuid_print=e;this.createOrder();setTimeout(()=>{this.$refs.print.show()},100)},},});Y.use(Maska);Y.use(window["v-money3"]["default"]);Y.use(ElementPlus);const ht=Y.mount("#vue-pos");const _t={props:["ajax_url","label","menu_id"],data(){return{data:[],pages:[],is_loading:!1,add_loading:!1,custom_loading:!1,custom_link:"",custom_link_text:""}},mounted(){this.getAllPages()},computed:{hasData(){if(this.pages.length>0&&this.menu_id>0){return!0}
return!1},hasLink(){if(!h(this.custom_link)&&!h(this.custom_link_text)){return!0}
return!1},},methods:{getAllPages(){this.is_loading=!0;axios({method:"POST",url:this.ajax_url+"/AllPages",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details}else{this.data=[]}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},addPageToMenu(){this.add_loading=!0;axios({method:"PUT",url:this.ajax_url+"/addPageToMenu",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),menu_id:this.menu_id,pages:this.pages},timeout:s}).then((e)=>{if(e.data.code==1){r(e.data.msg);this.pages=[];this.$emit("afterAddpage")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.add_loading=!1})},addCustomPageToMenu(){this.custom_loading=!0;axios({method:"PUT",url:this.ajax_url+"/addCustomPageToMenu",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),menu_id:this.menu_id,custom_link_text:this.custom_link_text,custom_link:this.custom_link},timeout:s,}).then((e)=>{if(e.data.code==1){r(e.data.msg);this.$emit("afterAddpage");this.custom_link_text="";this.custom_link=""}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.custom_loading=!1})},},template:`	
        
      <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
	    <div>
	      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	    </div>
	  </div>
    
      <h6 class="mb-2">{{label.title}}</h6>
      
      <div class="accordion mb-3" id="accordionPages">      
       <div class="card">
         <div class="card-header p-0" id="headingOne">
             <button class="btn w-100 text-left" type="button" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
               {{label.pages}}
             </button>
         </div>			
         <div id="collapsePages" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionPages">
           <div class="card-body border-left border-right border-bottom">		                           
             <ul class="list-group list-group-flush">  
               <li v-for="(item, index) in data" class="list-group-item p-1 pl-0">      
			      <div class="custom-control custom-checkbox">
			        <input v-model="pages" type="checkbox" class="custom-control-input" :value="index" :id="index" >
			        <label class="custom-control-label" :for="index">{{item}}</label>
			      </div>
			    </li>			    
             </ul>                
           </div> <!-- body -->
           
           <div class="card-footer text-right">		     
		     <button @click="addPageToMenu" :disabled="!hasData" type="button" class="btn btn-green normal rounded-0" :class="{ loading: add_loading }">
			  <span>Add to menu</span>
			  <div class="m-auto circle-loader" data-loader="circle-side"></div> 
			</button> 	            		     
		  </div> <!--card-footer-->
           
         </div> <!-- card -->
       </div> <!-- collapse -->
    </div> <!--accordion-->
    
    
     <div class="accordion" id="accordionCustomlink">      
       <div class="card">
         <div class="card-header p-0" id="headingOne">
             <button class="btn w-100 text-left" type="button" data-toggle="collapse" data-target="#collapseCustomepage" aria-expanded="true" aria-controls="collapseCustomepage">
               {{label.custom_links}}
             </button>
         </div>			
         <div id="collapseCustomepage" class="collapse" aria-labelledby="headingOne" data-parent="#accordionCustomlink">
           <div class="card-body border-left border-right border-bottom">		                           
           
             <div class="form-group">
			    <label for="custom_link">URL</label>
			    <input v-model="custom_link"
			    placeholder="https://"			    
			     type="text" class="form-control" id="custom_link" >  
			  </div>
			  
			  <div class="form-group">
			    <label for="custom_link_text">Link Text</label>
			    <input v-model="custom_link_text"			    
			     type="text" class="form-control" id="custom_link_text" >  
			  </div>
            
           </div> <!-- body -->
           
           <div class="card-footer text-right">		     
		     <button @click="addCustomPageToMenu" :disabled="!hasLink" type="button" class="btn btn-green normal rounded-0" :class="{ loading: custom_loading }">
			  <span>Add to menu</span>
			  <div class="m-auto circle-loader" data-loader="circle-side"></div> 
			</button> 	            		     
		  </div> <!--card-footer-->
           
         </div> <!-- card -->
       </div> <!-- collapse -->
    </div> <!--accordion-->
    `,};const pt={components:{draggable:vuedraggable},props:["ajax_url","label"],data(){return{data:[],current_menu:0,is_loading:!1,enabled:!0,dragging:!1,list:[{name:"John",id:0},{name:"Joao",id:1},{name:"Jean",id:2},],}},mounted(){this.MenuList()},methods:{MenuList(){this.is_loading=!0;axios({method:"POST",url:this.ajax_url+"/MenuList",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details.data;this.current_menu=e.data.details.current_menu;this.$emit("setCurrentmenu",this.current_menu)}else{this.data=[]}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},createNewMenu(){this.$emit("createNewmenu")},setMenuID(e){this.current_menu=e;this.$emit("setCurrentmenu",this.current_menu)},checkMove(e,t){this.is_loading=!0;axios({method:"PUT",url:this.ajax_url+"/sortMenu",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),menu:this.data},timeout:s}).then((e)=>{if(e.data.code==1){r(e.data.msg)}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},template:`	        
	 <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
	    <div>
	      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	    </div>
	 </div>
    
    <div class="mb-2">
      <p class="m-0">Select a menu to edit or <a @click="createNewMenu" class="text-green">create new menu</a></p>
    </div>        
            
    <div class="menu-categories medium mb-3 d-flex">
	<draggable
	:list="data"
	:disabled="!enabled"
	item-key="name"
	class="list-group"
	tag="transition-group"
	ghost-class="ghost"
	@update="checkMove"
	@start="dragging = true"
	@end="dragging = false"
	v-bind="dragOptions"
	>
	<template #item="{ element, index }">
	<a
	@click="setMenuID(element.menu_id)"
	:class="{active : current_menu==element.menu_id}"
	class="text-center rounded align-self-center text-center">	
	<p class="m-0 mt-1 text-truncate">{{element.menu_name}}</p>
	</a>
    </template>
    </div>
    `,};const ft={components:{draggable:vuedraggable},props:["ajax_url","label","current_menu"],data(){return{data:[],is_loading:!1,menu_name:"",child_menu:[],create_loading:!1,delete_loading:!1,remove_loading:!1,enabled:!0,dragging:!1}},mounted(){},computed:{dragOptions(){return{animation:200,group:"description",disabled:!1,ghostClass:"ghost"}},},watch:{current_menu(e,t){if(e>0){this.getMenuDetails()}else{this.menu_name="";this.child_menu=[]}},},methods:{createMenu(){this.create_loading=!0;axios({method:"PUT",url:this.ajax_url+"/createMenu",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),menu_name:this.menu_name,menu_id:this.current_menu,child_menu:this.child_menu},timeout:s,}).then((e)=>{if(e.data.code==1){r(e.data.msg);this.$emit("afterSavemenu")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.create_loading=!1})},deleteMenu(){bootbox.confirm({size:"small",title:"",message:"<h5>"+this.label.delete_confirmation+"</h5>"+"<p>"+this.label.are_you_sure+"</p>",centerVertical:!0,animate:!1,buttons:{cancel:{label:this.label.cancel,className:"btn btn-black small pl-4 pr-4"},confirm:{label:this.label["delete"],className:"btn btn-green small pl-4 pr-4"}},callback:(e)=>{if(e){this.delete_loading=!0;axios({method:"POST",url:this.ajax_url+"/deleteMenu",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&menu_id="+this.current_menu,timeout:s}).then((e)=>{if(e.data.code==1){r(e.data.msg);this.$emit("afterSavemenu")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.delete_loading=!1})}},})},getMenuDetails(){this.is_loading=!0;axios({method:"POST",url:this.ajax_url+"/getMenuDetails",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&current_menu="+this.current_menu,timeout:s}).then((e)=>{if(e.data.code==1){this.menu_name=e.data.details.menu_name;this.child_menu=e.data.details.data}else{this.menu_name="";this.child_menu=[]}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},removeChildMenu(e){this.remove_loading=!0;axios({method:"POST",url:this.ajax_url+"/removeChildMenu",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&menu_id="+e,timeout:s}).then((e)=>{if(e.data.code==1){this.getMenuDetails()}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.remove_loading=!1})},},template:`	    
   <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
      <div>
       <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
      </div>
    </div>
	  
    <h6 class="mb-2">{{label.title}}</h6>
    
    <div class="card">
	    <div class="card-header border-top border-right border-left">
	    
	      <div class="row align-items-center">
	       <div class="col">		        
	        <div class="d-flex">
		      <div class="mr-2">Menu name</div>
		      <div><input v-model="menu_name" type="text"></div>		      
		    </div>   		       
	       </div> <!--col-->
	       <div class="col text-right">
	          <template v-if="current_menu>0">	  
	                    
	            <button @click="createMenu" type="button" class="btn btn-green normal rounded-0 mr-2" :class="{ loading: create_loading }">
				  <span>Save menu</span>
				  <div class="m-auto circle-loader" data-loader="circle-side"></div> 
				</button> 	            
					            
	            <button @click="deleteMenu" type="button" class="btn btn-link normal rounded-0 text-green" :class="{ loading: delete_loading }">
				  <span>Delete</span>
				  <div class="m-auto circle-loader" data-loader="circle-side"></div> 
				</button> 	            
	            
	          </template>
	          <template v-else>	    	                 
	            <button @click="createMenu" type="button" class="btn btn-green normal rounded-0" :class="{ loading: create_loading }">
				  <span>Create menu</span>
				  <div class="m-auto circle-loader" data-loader="circle-side"></div> 
				</button> 	            
				
				<button @click="$emit('afterCancelmenu')" type="button" class="btn btn-link normal rounded-0 text-green" >
				  <span>Cancel</span>				  
				</button> 	            
				
	          </template>
	          
	       </div>
	      </div> <!-- row-->
	    
	    </div> <!--card-header-->
	    
	    <div class="card-body border-left border-bottom border-right">	      

	       <p class="font11 text-muted">Drag the items into the order you prefer.</p>
	       
	       <draggable
	        :list="child_menu"
	        :disabled="!enabled"
	        item-key="name"
	        class="list-group"
	        tag="transition-group"
	        ghost-class="ghost"
	        :move="checkMove"
	        @start="dragging = true"
	        @end="dragging = false"
	        v-bind="dragOptions"
	        >
	        <template #item="{ element, index }">
		        <div class="accordion mb-3"  :class="{ 'not-draggable': !enabled }" :id="'child_accordion_'+element.menu_id">		     
			      <div class="card">		      
				    <div class="card-header p-0" id="headingOne">
				        <button class="btn w-100 text-left" type="button" data-toggle="collapse" :data-target="'#page' + element.menu_id" aria-expanded="true" aria-controls="page1">
				          <b>{{element.menu_name}}</b>
				        </button>
				    </div>				
				    <div :id="'page' + element.menu_id" class="collapse" aria-labelledby="headingOne" :data-parent="'#child_accordion_'+element.menu_id">
				      <div class="card-body border-left border-bottomx border-right">
				      
				          <div class="form-group">
						    <label :for="element.menu_id">Navigation Label</label>
						    <input v-model="child_menu[index].menu_name"  type="text" class="form-control" :id="element.menu_id" >  
						  </div>				      
						  
				      </div> <!--body-->
				      
				      <div class="card-footer p-0">
					    <button @click="removeChildMenu(element.menu_id)" 
					    class="btn btn-link normal text-green"
					    :class="{ loading: remove_loading }"
					    >
					       <span>Remove	</span>
					       <div class="m-auto circle-loader" data-loader="circle-side"></div> 
					    </button>				    
					  </div> <!--card-footer-->
					  
				    </div> <!--page1-->
				  </div> <!--card-->			     
			     </div>  
			    <!--accordion-->
	        </template>
	       </draggable>    
		    
	    </div> <!--card-body-->
	    
	</div> <!--card-->    
    `,};const vt=Vue.createApp({components:{"components-menu-allpages":_t,"components-menu-structure":ft,"components-menu-list":pt},data(){return{current_menu:0}},methods:{setCurrentmenu(e){this.current_menu=e},createNewmenu(){this.current_menu=0},afterCancelmenu(){o("afterCancelmenu");this.$refs.menu_list.MenuList()},afterSavemenu(){o("afterSavemenu");this.$refs.menu_list.MenuList()},afterAddpage(){this.$refs.menu_structure.getMenuDetails()},},});vt.use(Maska);const gt=vt.mount("#vue-theme-menu");if(u("#sort-items").exists()){var bt=document.getElementById("sort-items");var yt=Sortable.create(bt,{swapThreshold:1,animation:150,direction:"horizontal"})}
if(u("#sort-menu-items").exists()){let e=JSON.parse(category_group);u.each(e,function(e,t){console.log(t);Sortable.create(document.getElementById(t),{swapThreshold:1,group:t,animation:100})})}
const xt={props:["ajax_url","id","label"],data(){return{data:[],loading:!0}},created(){this.BookingTimeline()},computed:{hasData(){if(this.data.length>0){return!0}
return!1},},methods:{BookingTimeline(){this.loading=!0;axios({method:"POST",url:this.ajax_url+"/BookingTimeline",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&id="+this.id,timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details.data}else{this.data=[]}})["catch"]((e)=>{}).then((e)=>{this.loading=!1})},},template:`	
	<template v-if="loading">
	  <div v-loading="loading" style="min-height:100px;"></div>
	</template>
	<template v-else>

	 <template v-if="!hasData">
	   <div class="d-flex" style="min-height:100px;">
	      <div class="align-self-center text-center text-muted col">
		  {{label.no_data}}
		  </div>
	   </div>
	 </template>

	 <div  class="pre-scrollable" style="max-height: 65vh" v-else>
	    <el-timeline>
		   <template v-for="(activity, index) in data">
		   <el-timeline-item :timestamp="activity.content" placement="top">
		      <p v-if="activity.remarks" class="font12 m-0 p-0">{{activity.remarks}}</p>
		      <p class="font11 m-0 p-0">{{activity.timestamp}}</p>
		   </el-timeline-item>
		   </template>
		</el-timeline>
	 </div>
	
	</template>   
	`,};const wt=Vue.createApp({components:{"reservation-timeline":xt}});wt.use(ElementPlus);const kt=wt.mount("#vue-booking");const It=Vue.createApp({data(){return{drawer:!1,filterText:"",defaultProps:{children:"children",label:"label"},data:[],loading:!1,translation_vendors:[]}},created(){this.getMenu();this.translation_vendors=JSON.parse(translation_vendor)},watch:{filterText(e,t){this.$refs.treeRef.filter(e)},},methods:{getMenu(){this.loading=!0;axios({method:"put",url:apibackend+"/getMenu",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content")},timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details}else{this.data=[]}})["catch"]((e)=>{}).then((e)=>{this.loading=!1})},filterNode(e,t){if(!e)return!0;return t.label.toLowerCase().includes(e)},goLink(e){if(e.link){o(e.link);window.location.href=e.link}},},template:`	
	<el-drawer
	 v-model="drawer"
	 title="I am the title"
	 :with-header="false"
	 direction="rtl"    
	 size="25%"
	 :append-to-body="true"		 
	>	   	   	   
	   <template v-if="loading">
	     <div v-loading="loading" style="min-height:200px;"></div>
	   </template>
	   <template v-else>
			<el-input v-model="filterText" :placeholder="translation_vendors.search_sidebar"></el-input>	   
			<el-tree 
			ref="treeRef"
			:data="data" 
			:props="defaultProps"
			default-expand-all	
			:filter-node-method="filterNode"  	   
			empty-text="Menu not found"
			>
			<template #default="{ node, data }">
				<span class="custom-tree-node">
					<span @click="goLink(data)">{{ node.label }}</span>
				</span>
			</template>
			</el-tree>
	   </template>	   
	</el-drawer>
	`,});It.use(ElementPlus);const St=It.mount("#vue-search-menu");const Tt=Vue.createApp({methods:{showDrawer(){St.drawer=!0},},template:`	
	<a href="javascript:;" @click="showDrawer">
	  <i class="zmdi zmdi-search"></i>
    </a>
	`,});const Ot=Tt.mount("#vue-search-toogle");const Ct=Vue.createApp({components:{"components-datatable":$},data(){return{loading:!1,date_start:"",date_end:"",count_up:undefined}},created(){this.getDailySummary()},methods:{afterSelectdate(e,t){this.date_start=e;this.date_end=t;this.getDailySummary()},getDailySummary(){this.loading=!0;axios({method:"POST",url:apibackend+"/getDailySummary",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&date_start="+this.date_start+"&date_end="+this.date_end,timeout:s}).then((e)=>{let t={decimalPlaces:e.data.details.price_format.decimals,separator:e.data.details.price_format.thousand_separator,decimal:e.data.details.price_format.decimal_separator,prefix:e.data.details.price_format.symbol,};let a=0;let s=0;let i=0;let l=0;let r=0;if(e.data.code==1){a=e.data.details.data.total_sales;s=e.data.details.data.total_delivery_fee;i=e.data.details.data.tax_total;l=e.data.details.data.total_tips;r=e.data.details.data.total}else{a=0;s=0;i=0;l=0;r=0}
this.count_up=new countUp.CountUp(this.$refs.total_sales,a,t);this.count_up.start();this.count_up=new countUp.CountUp(this.$refs.total_delivery_fee,s,t);this.count_up.start();this.count_up=new countUp.CountUp(this.$refs.total_tax,i,t);this.count_up.start();this.count_up=new countUp.CountUp(this.$refs.total_tips,l,t);this.count_up.start();this.count_up=new countUp.CountUp(this.$refs.total,r,t);this.count_up.start()})["catch"]((e)=>{}).then((e)=>{this.loading=!1})},FPprint(e){this.loading=!0;axios({method:"POST",url:apibackend+"/FPprint_dailysalesreport",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&printerId="+e+"&date_start="+this.date_start+"&date_end="+this.date_end,timeout:s,}).then((e)=>{if(e.data.code==1){ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"success"})}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"]((e)=>{}).then((e)=>{this.loading=!1})},},});Ct.use(ElementPlus);const Ft=Ct.mount("#vue-daily-sales-report");const Nt={props:["merchant_id","days","time_range","data","data_loading"],data(){return{loading:!1,start_time:[],datas:[],errors:[]}},created(){this.datas=this.data},watch:{data(e,t){this.datas=e},},computed:{hasError(){if(Object.keys(this.errors).length>0){return!0}
return!1},},methods:{addRow(e){this.datas[e].push({id:0,status:"open",start_time:"00:00",end_time:"00:00",custom_text:""})},removeRow(e,t){this.datas[e].splice(t,1)},onSubmit(){this.loading=!0;this.errors=[];axios({method:"PUT",url:apibackend+"/updatestorehours?merchant_id="+this.merchant_id,data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),data:this.datas},timeout:s}).then((e)=>{if(e.data.code==1){if(e.data.details.error_count<=0){ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"success"});this.$emit("afterUpdate")}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}
this.errors=e.data.details.error}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"});this.errors=e.data.details.error}})["catch"]((e)=>{}).then((e)=>{this.loading=!1})},},template:"#xtemplate_opening_hours",};const jt=Vue.createApp({components:{"components-opening-hours":Nt},data(){return{merchantId:null,data:[],days:[],time_range:[],loading:!1}},created(){this.merchantId=document.getElementById('vue-opening-hours').getAttribute('data-merchant-id');if(this.merchantId){this.getOpeningHoursAttribute()}else{console.error("merchant_id is not defined.")}},methods:{afterUpdate(){o("afterUpdate");this.getOpeningHoursAttribute()},getOpeningHoursAttribute(){this.loading=!0;axios({method:"POST",url:apibackend+"/getOpeningHoursAttribute?merchant_id="+this.merchantId,data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.days=e.data.details.days;this.data=e.data.details.data;this.time_range=e.data.details.time_range}else{}})["catch"]((e)=>{}).then((e)=>{this.loading=!1})},},});jt.use(ElementPlus);const Et=jt.mount("#vue-opening-hours");const $t={props:["label","default_country","only_countries","phone","field_phone","field_phone_prefix"],data(){return{data:[],country_flag:"",mobile_prefix:"",mobile_number:""}},updated(){},mounted(){this.mobile_number=this.phone;this.getLocationCountries()},methods:{getLocationCountries(){var e={YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),default_country:this.default_country,only_countries:this.only_countries};var t=f();e=JSON.stringify(e);_[t]=u.ajax({url:ajaxurl+"/getLocationCountries",method:"PUT",dataType:"json",data:e,contentType:i.json,timeout:s,crossDomain:!0,beforeSend:(e)=>{this.is_loading=!0;if(_[t]!=null){_[t].abort()}},});_[t].done((e)=>{if(e.code==1){this.data=e.details.data;this.country_flag=e.details.default_data.flag;this.mobile_prefix=e.details.default_data.phonecode}else{this.data=[];this.country_flag="";this.mobile_prefix=""}});_[t].always((e)=>{})},setValue(e){this.country_flag=e.flag;this.mobile_prefix=e.phonecode;this.$refs.ref_mobile_number.focus()},},template:`					
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
		<input type="hidden" :name="field_phone_prefix" :value="mobile_prefix" />
		<input :name="field_phone" type="text"  v-maska="'###################'"  ref="ref_mobile_number"
		:value="mobile_number"  >
		
	</div> <!--inputs-->
	`,};const Rt=Vue.createApp({components:{"component-phone":$t},data(){return{data:[]}},});Rt.use(Maska);const Yt=Rt.mount("#app-phone");const Pt={props:["label","ajax_url","driver_uuid"],data(){return{loading:!1,loading_activity:!1,overview_data:[],tableData:[]}},mounted(){this.getDriverOverview();this.getDriverActivity()},methods:{getDriverOverview(){this.loading=!0;axios({method:"put",url:this.ajax_url+"/getDriverOverview",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),driver_uuid:this.driver_uuid},timeout:s}).then((e)=>{if(e.data.code==1){this.overview_data=e.data.details}else{this.overview_data=[]}})["catch"]((e)=>{}).then((e)=>{this.loading=!1})},getDriverActivity(){this.loading_activity=!0;axios({method:"put",url:this.ajax_url+"/getDriverActivity",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),driver_uuid:this.driver_uuid},timeout:s}).then((e)=>{if(e.data.code==1){this.tableData=e.data.details.data}else{this.tableData=[]}})["catch"]((e)=>{}).then((e)=>{this.loading_activity=!1})},},template:"#xtemplate_overview",};const Kt=Vue.createApp({components:{"component-driveroverview":Pt},data(){return{data:[]}},});Kt.use(ElementPlus);const Dt=Kt.mount("#app-driveroverview");const Mt={props:["ajax_url","label","transaction_type_list","action_name","ref_id"],data(){return{is_loading:!1,transaction_description:"",transaction_type:"credit",transaction_amount:0}},computed:{hasData(){if(!h(this.transaction_description)&&!h(this.transaction_type)&&!h(this.transaction_amount)){return!0}
return!1},},mounted(){},methods:{show(){u(this.$refs.modal_adjustment).modal("show")},close(){u(this.$refs.modal_adjustment).modal("hide")},clear(){this.transaction_description="";this.transaction_type="credit";this.transaction_amount=0},submit(){var e={YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),payment_provider:this.payment_provider,transaction_description:this.transaction_description,transaction_type:this.transaction_type,transaction_amount:this.transaction_amount,ref_id:this.ref_id,};this.error=[];this.is_loading=!0;axios({method:"put",url:this.ajax_url+"/"+this.action_name,data:e,timeout:s}).then((e)=>{if(e.data.code==1){r(e.data.msg);this.clear();this.close();this.$emit("afterSave")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},template:`
	<div ref="modal_adjustment" class="modal"
    id="modal_adjustment" data-backdrop="static" 
    tabindex="-1" role="dialog" aria-labelledby="modal_adjustment" aria-hidden="true">
    
	   <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
	     <div class="modal-content"> 
	     
	     <form @submit.prevent="submit" >
	     
	      <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">{{label.title}}</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  
		  <div class="modal-body">
		  
		   <div class="form-label-group">    
		       <input type="text" v-model="transaction_description" id="transaction_description"
		       placeholder=""
		       class="form-control form-control-text" >
		       <label for="transaction_description">{{label.transaction_description}}</labe>
		   </div>

		   <select ref="transaction_type" v-model="transaction_type"
		     class="form-control custom-select form-control-select mb-3"  >		    
		     <option v-for="(name, key) in transaction_type_list" :value="key">{{name}}</option>		    
		   </select>	   
		   
		   <div class="form-label-group">    
		       <input type="number" v-model="transaction_amount" id="transaction_amount"		       
		       placeholder=""
			   step="0.01"
		       class="form-control form-control-text" >
		       <label for="transaction_amount">{{label.transaction_amount}}</labe>
		     </div>
		  
		   </div> <!-- body -->
		  
		   <div class="modal-footer">     

		   <button type="button" class="btn btn-black" data-dismiss="modal">
            <span class="pl-3 pr-3">{{label.close}}</span>
           </button>
		       
	        <button type="submit" class="btn btn-green pl-4 pr-4" :class="{ loading: is_loading }"
	        :disabled="!hasData"
	        >
	          <span>{{label.submit}}</span>
	          <div class="m-auto circle-loader" data-loader="circle-side"></div> 
	        </button>
	      </div>
		 
	      </form>
		  </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->     	   
	`,};const zt={props:["ajax_url","action_name","ref_id"],data(){return{is_loading:!1}},mounted(){this.getBalance()},methods:{getBalance(){this.is_loading=!0;axios({method:"post",url:this.ajax_url+"/"+this.action_name,data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&ref_id="+this.ref_id,timeout:s}).then((e)=>{var t;if(e.data.code==1){var a={decimalPlaces:e.data.details.price_format.decimals,separator:e.data.details.price_format.thousand_separator,decimal:e.data.details.price_format.decimal_separator};if(e.data.details.price_format.position=="right"){a.suffix=e.data.details.price_format.symbol}else a.prefix=e.data.details.price_format.symbol;t=new countUp.CountUp(this.$refs.balance,e.data.details.balance,a);t.start();this.$emit("afterBalance",e.data.details.balance)}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},template:`
	<span ref="balance"></span>
	`,};const At={props:["ajax_url","label","ref_id"],components:{"components-loading-box":he},methods:{confirm(){ElementPlus.ElMessageBox.confirm(this.label.message,this.label.confirm,{confirmButtonText:this.label.ok,cancelButtonText:this.label.cancel,type:"warning"}).then(()=>{this.$refs.loading_box.show();axios({method:"POST",url:this.ajax_url+"/ClearWalletTransactions",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&ref_id="+this.ref_id,timeout:s}).then((e)=>{if(e.data.code==1){this.$emit("afterSave")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.$refs.loading_box.close()})})["catch"](()=>{})},},template:`
	<components-loading-box
	ref="loading_box"
	message="Processing"
	donnot_close="don't close this window"
	>
	</components-loading-box>
	`,};const Lt=Vue.createApp({components:{"components-datatable":$,"components-create-adjustment":Mt,"components-commission-balance":zt,"components-clearwallet":At},methods:{createTransaction(){this.$refs.create_adjustment.show()},afterSave(){this.$refs.datatable.getTableData();this.$refs.balance.getBalance()},clearWallet(){this.$refs.clear_wallet.confirm()},},});Lt.use(Maska);const Ut=Lt.mount("#vue-driver-wallet");const qt={props:["label","size","ajax_url","start_value","end_value","time_interval","merchant_id","employment_type"],data(){return{loading:!0,is_loading:!1,sched_calendar:null,schedule_uuid:"",driver_id:"",car_id:"",date_start:"",time_start:"",time_end:"",instructions:"",zone_id:"",zone_value:"",zone_loading:!1,zone_list:[{value:"Option1",label:"Option1"}],}},created(){this.initCalendar()},computed:{hasData(){let e=this.schedule_uuid.length;if(e>0){return!0}
return!1},},mounted(){this.select2Driver();this.select2Car();this.getZoneList()},methods:{newSchedule(){this.clearForm();u(this.$refs.modal_sched).modal("show")},initCalendar(){let e=document.getElementsByTagName("html")[0].getAttribute("lang");e=e.replace("_","-");document.addEventListener("DOMContentLoaded",()=>{this.sched_calendar=new FullCalendar.Calendar(this.$refs.calendar,{locale:e,initialView:"dayGridMonth",headerToolbar:{right:"today prev,next",center:"title",left:"dayGridMonth,timeGridWeek,timeGridDay,listWeek"},selectable:!0,unselectAuto:!0,loading:(e)=>{this.loading=e},eventSources:[{url:this.ajax_url+"/getDriverSched",extraParams:{merchant_id:this.merchant_id},method:"POST",success:()=>{},failure:()=>{ElementPlus.ElNotification({title:"Error",message:"there was an error while fetching data",type:"warning",position:"bottom-right"})},},],dateClick:(e)=>{this.clearForm();this.date_start=e.dateStr;u(this.$refs.modal_sched).modal("show")},eventClick:(e)=>{this.getSchedule(e.event.id)},eventContent:(e,t)=>{let a=e.event.extendedProps;console.debug(a);let s="";s+='<div class="fc-content"><span class="fc-time"></span><span class="fc-title">';s+="<div>"+a.time+"</div>";s+="<div>"+a.plate_number+"</div>";s+='<div class="mytable">';s+='<div class="mycol">';s+='<img class="circle_image" src="'+a.avatar+'">';s+="</div>";s+='<div class="mycol">';s+='<span class="d-inline-block text-truncate font-weight-bold" style="max-width: 90px;">'+a.name+"</span>";s+="<div>"+a.zone_name+"</div>";s+="</div>";s+="</div>";s+="</span></div>";return{html:s}},});this.sched_calendar.render()})},getZoneList(){this.zone_loading=!0;axios({method:"put",url:this.ajax_url+"/getZoneList2",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content")},timeout:s}).then((e)=>{if(e.data.code==1){this.zone_list=e.data.details}else{this.zone_list=[]}})["catch"]((e)=>{}).then((e)=>{this.zone_loading=!1})},select2Driver(){let a=this.merchant_id;let s=this.employment_type;u(this.$refs.driver_list).select2({width:"resolve",language:{searching:()=>{return this.label.searching},noResults:()=>{return this.label.no_results},},ajax:{delay:250,url:this.ajax_url+"/searchDriver",type:"PUT",contentType:"application/json",data:function(e){var t={search:e.term,YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_id:a,employment_type:s};return JSON.stringify(t)},},})},select2Car(){u(this.$refs.car_list).select2({width:"resolve",language:{searching:()=>{return this.label.searching},noResults:()=>{return this.label.no_results},},ajax:{delay:250,url:this.ajax_url+"/searchCar",type:"PUT",contentType:"application/json",data:function(e){var t={search:e.term,YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content")};return JSON.stringify(t)},},})},submit(){this.is_loading=!0;this.driver_id=u(this.$refs.driver_list).find(":selected").val();this.car_id=u(this.$refs.car_list).find(":selected").val();let e=this.zone_id;if(Object.keys(this.zone_id).length>0){e=this.zone_id.value}
axios({method:"put",url:this.ajax_url+"/addSchedule",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),schedule_uuid:this.schedule_uuid,zone_id:e,driver_id:this.driver_id,vehicle_id:this.car_id,date_start:this.date_start,time_start:this.time_start,time_end:this.time_end,instructions:this.instructions,},timeout:s,}).then((e)=>{if(e.data.code==1){ElementPlus.ElNotification({title:this.label.success,message:e.data.msg,position:"bottom-right",type:"success"});this.afterInsert()}else{ElementPlus.ElNotification({title:this.label.error,message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},getSchedule(e){this.zone_id="";this.loading=!0;axios({method:"put",url:this.ajax_url+"/getSchedule",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),schedule_uuid:e},timeout:s}).then((e)=>{if(e.data.code==1){const t=e.data.details;this.clearForm();u(this.$refs.modal_sched).modal("show");this.schedule_uuid=t.sched.schedule_uuid;this.driver_id=t.sched.driver_id;this.car_id=t.sched.car_id;this.date_start=t.sched.date_start;this.time_start=t.sched.time_start;this.time_end=t.sched.time_end;this.instructions=t.sched.instructions;this.zone_id=t.sched.zone_id;setTimeout(()=>{var e=new Option(t.driver.text,t.driver.id,!1,!1);u(this.$refs.driver_list).append(e).trigger("change");var e=new Option(t.car.text,t.car.id,!1,!1);u(this.$refs.car_list).append(e).trigger("change");u(this.$refs.driver_list).val(t.driver.id).trigger("change");u(this.$refs.car_list).val(t.car.id).trigger("change")},1)}else{ElementPlus.ElNotification({title:this.label.error,message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"]((e)=>{}).then((e)=>{this.loading=!1})},ConfirmDeleteSchedule(){ElementPlus.ElMessageBox.confirm(this.label.delete_message,this.label.delete_confirm,{confirmButtonText:this.label.ok,cancelButtonText:this.label.cancel,type:"warning"}).then(()=>{this.deleteSchedule()})["catch"](()=>{})},deleteSchedule(){this.delete_loading=!1;axios({method:"put",url:this.ajax_url+"/deleteSchedule",data:{YII_CSRF_TOKEN:u("meta[name=YII_CSRF_TOKEN]").attr("content"),schedule_uuid:this.schedule_uuid},timeout:s}).then((e)=>{if(e.data.code==1){ElementPlus.ElNotification({title:this.label.success,message:e.data.msg,position:"bottom-right",type:"success"});this.afterDelete()}else{ElementPlus.ElNotification({title:this.label.error,message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"]((e)=>{}).then((e)=>{this.delete_loading=!1})},afterInsert(){u(this.$refs.modal_sched).modal("hide");this.clearForm();this.sched_calendar.refetchEvents()},afterDelete(){u(this.$refs.modal_sched).modal("hide");this.clearForm();this.sched_calendar.refetchEvents()},clearForm(){this.schedule_uuid="";this.driver_id="";this.car_id="";this.date_start="";this.time_start="";this.time_end="";this.instructions="";this.zone_id="";u(this.$refs.driver_list).val(null).trigger("change");u(this.$refs.car_list).val(null).trigger("change")},},template:`	
	<div v-loading="loading" >
	   <div id="fs-calendar" ref="calendar"></div>
	</div>

	<div ref="modal_sched" class="modal" role="dialog" data-backdrop="static" >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		
	<form @submit.prevent="submit" >	  

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{label.title}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body position-relative">
	  	  	  		  	 
	  
	 <div class="row">
	    <div class="col">		
		<div class="form-group">  
				<label class="mr-4" >Zone</label>	
				<div>				
				<el-select v-model="zone_id"   placeholder="Select zone">
					<el-option
					v-for="item in zone_list"
					:key="item.value"
					:label="item.label"
					:value="item.value"
					/>
				</el-select>
				</div>
			</div>

		</div>
		<div class="col">
		   <div class="form-group">  
			<label class="mr-4" >Date</label>
			<div> 
			<el-date-picker
				v-model="date_start"
				type="date"
				placeholder="Pick a date"	   	   
				size="default"				
				/>          
			</div>	
		   </div>				 
		</div>
	 </div>
	 
	 <div class="row">
	   <div class="col"> 
			<div class="form-group">  
				<label class="mr-4" >Time start</label>	
				<div>
				<el-time-select
				v-model="time_start"
				:start="start_value"				
				:end="end_value"	
				:step="time_interval"		
				placeholder="Select time"
				/>	 
				</div>
			</div>
	   </div>
	   <div class="col"> 
			<div class="form-group">  
				<label class="mr-4" >Time ends</label>	
				<div>
				<el-time-select
				v-model="time_end"
				:start="start_value"				
				:end="end_value"	
				:step="time_interval"		
				placeholder="Select time"
				/>	 
				</div>
			</div>
	   </div>
	 </div>
	 <!-- row -->
	 	 

	 <div class="form-group">  
		<label class="mr-4" >Select Driver</label> 
		<select ref="driver_list" class="form-control select2-driver"  style="width:100%">	  	  
		</select>
	</div>	

	<div class="form-group">  
	<label class="mr-4" >Select Car</label> 
	<select ref="car_list" class="form-control select2-car"  style="width:100%">	  	  
	</select>
    </div>	

	<div class="form-group">  
	<label class="mr-4" >Instructions</label> 
	<el-input
		v-model="instructions"
		:rows="2"
		type="textarea"
		placeholder="Add instructions to driver"
	/>
    </div>	
  
	  </div>      
      <div class="modal-footer">     	

        <button type="button" class="btn btn-black pl-4 pr-4"  data-dismiss="modal">
          <span>{{label.close}}</span>          
        </button>

		<button v-if="hasData" type="button" class="btn btn-danger pl-4 pr-4" @click="ConfirmDeleteSchedule">
          <span>{{label.delete}}</span>          
        </button>

		<button type="submit"  class="btn btn-green pl-4 pr-4" :class="{ loading: is_loading }" >
		<span v-if="!hasData">{{label.save}}</span>
		<span v-else>{{label.update}}</span>
		<div class="m-auto circle-loader" data-loader="circle-side"></div> 
	  </button>
      </div>
      	  

    </div>
  </div>
  </form>
</div>        
	`,};const Bt=Vue.createApp({components:{"component-calendar":qt},data(){return{data:[]}},methods:{newSchedule(){console.debug("newSchedule");this.$refs.appcalendar.newSchedule()},},});let Vt={};if(typeof ElementPlusLocaleLanguage!=="undefined"&&ElementPlusLocaleLanguage!==null){Vt={locale:ElementPlusLocaleLanguage}}
Bt.use(ElementPlus,Vt);const Jt=Bt.mount("#app-schedule");const Ht={props:["ajax_url","label","transaction_type"],data(){return{is_loading:!1,transaction_uuid:"",data:[],merchant:[],provider:[]}},computed:{isUnpaid(){if(this.data.status=="unpaid"){return!1}
return!0},},methods:{show(){u(this.$refs.modal_payout).modal("show");this.getPayoutDetails()},close(){u(this.$refs.modal_payout).modal("hide")},getPayoutDetails(){this.is_loading=!0;axios({method:"POST",url:this.ajax_url+"/getPayoutDetails",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&transaction_uuid="+this.transaction_uuid,timeout:s}).then((e)=>{if(e.data.code==1){this.data=e.data.details.data;this.merchant=e.data.details.merchant;this.provider=e.data.details.provider}else{r(e.data.msg,"error");this.data=[];this.merchant=[];this.provider=[]}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},changePayoutStatus(e){this.is_loading=!0;axios({method:"POST",url:this.ajax_url+"/"+e,data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content")+"&transaction_uuid="+this.transaction_uuid+"&transaction_type="+this.transaction_type,timeout:s,}).then((e)=>{if(e.data.code==1){r(e.data.msg,"success");this.close();this.$emit("afterSave")}else{r(e.data.msg,"error")}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},},template:`
	<div ref="modal_payout" class="modal"
    id="modal_payout" data-backdrop="static" 
    tabindex="-1" role="dialog" aria-labelledby="modal_payout" aria-hidden="true">
    
	   <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
	     <div class="modal-content"> 
	     
	     <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
		    <div>
		      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
		    </div>
		 </div>
		 
	     
	      <div class="modal-header">		  
			<h5 class="modal-title" id="exampleModalLabel">{{label.title}}</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  		  
		  <div class="modal-body grey-bg">
		  
		  <div class="card p-3">

		  <div class="row">
		    <div class="col">		    
		     <div class="form-group">
			     <label class="m-0 mb-1">{{label.amount}}</label>
			     <h6 class="m-0">{{data.transaction_amount}}</h6>
			  </div> 		    
			  <div class="form-group">
			     <label class="m-0 mb-1">{{label.payment_method}}</label>
			     <h6 class="m-0">{{provider.payment_name}}</h6>
			     <p v-if="provider.is_online==='1'" class="m-0 text-muted font11">({{label.online_payment}})</p>
			     <p v-else class="m-0 text-muted font11">({{label.offline_payment}})</p>
			  </div> 		    
			  
			  <div class="form-group">
			     <label class="m-0 mb-1">{{label.status}}</label>
			     <h6 class="m-0">{{data.status}}</h6>
			  </div> 		    
			  
		    </div>
		    <!-- col -->
		    
		    <div class="col">		    
		     <div class="form-group">
			     <label class="m-0 mb-1">{{label.merchant}}</label>
			     <h6 class="m-0">{{merchant.restaurant_name}}</h6>
			  </div> 		    
			  <div class="form-group">
			     <label class="m-0 mb-1">{{label.date_requested}}</label>
			     <h6 class="m-0">{{data.transaction_date}}</h6>
			  </div> 		    

			  <div v-if="data.merchant_base_currency!=data.admin_base_currency" class="form-group">
			   <label class="m-0 mb-1"><b>Exchange rate info</b></label>
			   <p class="m-0">Total amount : {{data.transaction_amount_original}}</p>
			   <p class="m-0">Exchange rate : {{data.exchange_rate_merchant_to_admin}}</p>			   
			  </div>

		    </div>
		    <!-- col -->
		    
		  </div> 
		  <!-- row -->
		  		  
		  <h5>{{label.payment_to_account}}</h5>	
		  
		  
		  <template v-if="data.provider==='paypal'">	  
		  <p>{{data.email_address}}</p>
		  </template>
		  <template v-else-if="data.provider==='cybersource'">	  
		  <p>{{data.email_address}}</p>
		  </template>
		  
		  <template v-else-if="data.provider==='stripe'">	  		  
		  <table class="table table-bordered">
		   <tr>
		    <td width="40%">{{label.account_number}}</td>
		    <td>{{data.account_number}}</td>
		   </tr>
		   <tr>
		    <td >{{label.account_name}}</td>
		    <td>{{data.account_holder_name}}</td>
		   </tr>
		   <tr>
		    <td >{{label.account_type}}</td>
		    <td>{{data.account_holder_type}}</td>
		   </tr>
		   <tr>
		    <td >{{label.account_currency}}</td>
		    <td>{{data.currency}}</td>
		   </tr>
		   <tr>
		    <td>{{label.routing_number}}</td>
		    <td>{{data.routing_number}}</td>
		   </tr>
		   <tr>
		    <td>{{label.country}}</td>
		    <td>{{data.country}}</td>
		   </tr>
		  </table>		  
		  </template>
		  
		  <template v-else-if="data.provider==='bank'">	  		  
		   <table class="table table-bordered">
		   <tr>
		    <td width="40%">{{label.account_holders_name}}</td>
		    <td>{{data.account_name}}</td>
		   </tr>
		   <tr>
		    <td width="40%">{{label.iban}}</td>
		    <td>{{data.account_number_iban}}</td>
		   </tr>
		   <tr>
		    <td width="40%">{{label.switf_code}}</td>
		    <td>{{data.swift_code}}</td>
		   </tr>
		   <tr>
		    <td width="40%">{{label.bank_name}}</td>
		    <td>{{data.bank_name}}</td>
		   </tr>
		   <tr>
		    <td width="40%">{{label.bank_branch}}</td>
		    <td>{{data.bank_branch}}</td>
		   </tr>
		  </table>
		  </template>
		  		 
		  </div>
		  </div> <!-- body -->
		  
		   <div class="modal-footer">     
          
		   <button type="button" class="btn btn-black" data-dismiss="modal">
            <span class="pl-3 pr-3">{{label.close}}</span>
           </button>
		       	        
	       <button type="button" class="btn btn-yellow" :class="{ loading: is_loading }" 
	       :disabled="isUnpaid"       
	       @click="changePayoutStatus('cancelPayout')"  
	       >
            <span class="pl-3 pr-3">{{label.cancel_payout}}</span>
           </button>
           
           <template v-if="provider.is_online==='1'">
	           <button type="button" class="btn btn-green pl-4 pr-4" :class="{ loading: is_loading }"	
	           :disabled="isUnpaid"        	           
	           @click="changePayoutStatus('approvedPayout')"  
		        >
		          <span>{{label.approved}}</span>
		          <div class="m-auto circle-loader" data-loader="circle-side"></div> 
		        </button>
	       </template>
	       
	       <template v-else>
	       
	         <button type="button" class="btn btn-green pl-4 pr-4" :class="{ loading: is_loading }"	
	           :disabled="isUnpaid"      	           
	           @click="changePayoutStatus('payoutPaid')"  
		        >
		          <span>{{label.set_paid}}</span>
		          <div class="m-auto circle-loader" data-loader="circle-side"></div> 
		      </button>  
	       
	       </template>
	        
            
	      </div>
		 
	      
		  </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->   
	`,};const Gt=Vue.createApp({components:{"components-datatable":$,"components-payout-details":Ht},data(){return{is_loading:!1,summary:[],count_up:undefined}},mounted(){this.payoutSummary()},methods:{payoutSummary(){this.is_loading=!0;axios({method:"POST",url:apibackend+"/cashoutSummary",data:"YII_CSRF_TOKEN="+u("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:s}).then((e)=>{if(e.data.code==1){this.summary=e.data.details.summary;var t={decimalPlaces:0,separator:",",decimal:"."};var a={decimalPlaces:e.data.details.price_format.decimals,separator:e.data.details.price_format.thousand_separator,decimal:e.data.details.price_format.decimal_separator};if(e.data.details.price_format.position=="right"){a.suffix=e.data.details.price_format.symbol}else a.prefix=e.data.details.price_format.symbol;this.count_up=new countUp.CountUp(this.$refs.ref_unpaid,this.summary.unpaid,t);this.count_up.start();this.count_up=new countUp.CountUp(this.$refs.ref_paid,this.summary.paid,t);this.count_up.start();this.count_up=new countUp.CountUp(this.$refs.total_unpaid,this.summary.total_unpaid,a);this.count_up.start();this.count_up=new countUp.CountUp(this.$refs.ref_total_paid,this.summary.total_paid,a);this.count_up.start()}})["catch"]((e)=>{}).then((e)=>{this.is_loading=!1})},viewPayoutDetails(e){this.$refs.payout.transaction_uuid=e;this.$refs.payout.show()},afterSave(){o("afterSave");this.$refs.datatable.getTableData();this.payoutSummary()},},});const Wt=Gt.mount("#vue-cashout")})(jQuery)