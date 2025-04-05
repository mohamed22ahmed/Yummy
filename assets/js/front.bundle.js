(function(p){"use strict";var M=[];var n={};var A={};var $;var K;const c=8e4;const d={form:"application/x-www-form-urlencoded; charset=UTF-8",json:"application/json"};var z;const L=[{featureType:"administrative.country",elementType:"geometry",stylers:[{visibility:"off"}]}];var u=function(e){console.debug(e)};var q=function(e){alert(JSON.stringify(e))};var V=function(e,t){localStorage.setItem(e,t)};var U=function(e){return localStorage.getItem(e)};var J=function(e){localStorage.removeItem(e)};jQuery.fn.exists=function(){return this.length>0};var f=function(e){if(typeof e==="undefined"||e==null||e==""||e=="null"||e=="undefined"){return true}return false};jQuery(document).ready(function(){if(p(".sidebar-panel").exists()){p(".sidebar-panel").slideAndSwipe()}if(p(".headroom").exists()){var e=document.querySelector(".headroom");u(e);var t=new Headroom(e);t.init()}p(document).ready(function(){p(".dropdown").on("show.bs.dropdown",function(){p(this).find(".dropdown-menu").first().stop(true,true).slideDown(150)});p(".dropdown").on("hide.bs.dropdown",function(){p(this).find(".dropdown-menu").first().stop(true,true).slideUp(150)})});if(p(".star-rating").exists()){le()}p(document).on("change","#min_range_slider",function(){p(".min-selected-range").html(p(this).val())});p(".collapse").on("show.bs.collapse",function(){p(this).parent().find("a").removeClass("closed").addClass("opened")}).on("hidden.bs.collapse",function(){p(this).parent().find("a").removeClass("opened").addClass("closed")});if(p(".sticky-sidebar").exists()){$=new StickySidebar(".sticky-sidebar",{topSpacing:20})}if(p(".sticky-cart").exists()){K=new StickySidebar(".sticky-cart",{topSpacing:20})}p(".category-nav").sticky({topSpacing:0});p(".gallery_magnific").magnificPopup({delegate:"a",type:"image",gallery:{enabled:true},removalDelay:300,mainClass:"mfp-fade"});p(document).on("click",".toogle-radio",function(){var e=p(this).find("input");if(e.prop("checked")==false){e.prop("checked",true)}else{e.prop("checked",false)}});p(document).on("click",".desired_delivery",function(){Q(p(this).find("input").val(),{now:"hide",schedule:"show"},".schedule-section")});p(document).on("click",".a-account",function(){p(".login-wrap,.signup-wrap,.account-section").hide();p("."+p(this).data("id")).show()});p(document).on("click",".close-modal",function(){H(p(this).data("id"))});if(p(".auto_typehead").exists()){var r={};p(".auto_typehead").each(function(e,t){var a=p(this);var s=a.data("api");var i=a.data("display");var o=Z(a.data("settings"));p.each(o,function(e,t){if(t=="true"){r[e]=true}else if(t=="false"){r[e]=false}else{r[e]=t}});r["input"]=a;r["source"]={user:{display:i.split(","),ajax:{method:"POST",url:ajaxurl+"/"+s,path:"details.data",data:{q:"{{query}}",YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content")},beforeSend:function(e,t){u("before send");if(s=="getlocation_autocomplete"){if(p(".change-address-modal").exists()){p(".change-address-modal").find(".modal-content").addClass("opened")}}},callback:{done:function(e){return e},fail:function(e,t,a){},always:function(e,t,a){u("always")},then:function(e,t){}}}}};r["template"]=function(e,t){return a.data("template")};r["emptyTemplate"]=a.data("emptytemplate");r["callback"]={onClickAfter:function(e,t,a,s){var i=e.data("api");switch(i){case"getlocation_autocomplete":var o=W();var r="id="+a.id+"&addressLine1="+a.addressLine1+"&addressLine2="+a.addressLine2;if(a.provider=="google.maps"){if(typeof a.place_type!=="undefined"&&a.place_type!==null){r+="&place_type="+a.place_type}}else if(a.provider=="mapbox"){if(typeof a.latitude!=="undefined"&&a.latitude!==null){r+="&latitude="+a.latitude;r+="&longitude="+a.longitude;r+="&place_type="+a.place_type}}r+="&services="+o;ie("getLocationDetails",r,"typeahead_loader",1);break;case"getSearchSuggestion":window.location.href=a.url;break}},onCancel:function(e,t,a){if(s=="getlocation_autocomplete"){if(p(".change-address-modal").exists()){p(".change-address-modal").find(".modal-content").removeClass("opened")}}}};r["selector"]={list:"typeahead__list list-unstyled"};p.typeahead(r)})}p(document).on("click",".get_current_location",function(){G("get_place_details")});if(p(".load-ajax").exists()){p(".load-ajax").each(function(e,t){var a=p(t).data("action");var s=p(t).data("params");var i=p(t).data("loader_type");var o=p(t).data("single_call");var r=p(t).data("silent");var d=p(t).data("method");var l=p(t).data("content_type");var n=Z(s);if(l=="json"){if(a=="getFeed"){var c=n.services;s={local_id:n.local_id,target:n.target,YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),filters:{services:c.split(",")}}}s=JSON.stringify(s)}ie(a,s,i,o,r,d,l)})}p(document).on("click",".show-more",function(e){var t=p(this).data("action");var a=p(this).data("params");var s=p(this).data("loader_type");var i=p(this).data("single_call");var o=Z(a);var r=o.filters;r=r.replace(/\\/g,"");r=JSON.parse(r);a={local_id:o.local_id,page:o.page,target:o.target,YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),filters:r};a=JSON.stringify(a);ie(t,a,s,i,null,"PUT","json")});p(document).on("click",".restaurants-filter",function(e){var t=p("#local_id").val();var a=p("#target").val();var s="";var i=p(".sort:checked").val();var o=p(".price_range:checked").val();var r=p("#min_range_slider").val();var d=p(".ratings_selected").val();var l=[];var n=[];p(".services:checked").each(function(){l.push(p(this).val())});p(".cuisine:checked").each(function(){n.push(p(this).val())});if(typeof i!=="undefined"&&i!==null){}else i="";s={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),local_id:t,target:a,filters:{services:l,cuisine:n,price_range:o,max_fee:parseInt(r),ratings:parseInt(d),sort:i}};s=JSON.stringify(s);ie("getFeed",s,"restaurants_filter",1,1,"PUT","json")});p(function(){X()});p("#changeAddress").on("shown.bs.modal",function(e){p(".auto_typehead").val("");p(".auto_typehead").focus()});p("#changeAddress").on("hidden.bs.modal",function(e){p(".change-address-modal").find(".modal-content").removeClass("opened")});p(document).on("click",".get-item-details",function(e){var t=p(this).data("item_uuid");var a=p(this).closest("section").data("cat_id");ie("getMenuItem","&merchant_id="+merchant_id+"&item_uuid="+t+"&cat_id="+a)});p(document).on("click",".choose-size",function(){var e=p(this).find("input:checked").val();if(typeof e!=="undefined"&&e!==null){_e(e)}});p(document).on("click",".addon-items",function(e){})});var B=function(e){var t="";var a=parseInt(list_limit);u("$itemlimit=>"+a);for(let e=0;e<a;e++){t+='<div class="col-lg-3 col-md-3 mb-4 mb-lg-3">';t+='<div class="position-relative skeleton-height">';t+='<div class="skeleton-placeholder"></div>';t+="</div>";t+="</div>"}e.html(t)};var G=function(a){var e={enableHighAccuracy:true,timeout:5e3,maximumAge:0};if(navigator.geolocation){de("typeahead_loader",1);navigator.geolocation.getCurrentPosition(function(e){var t=W();ie("reverseGeocoding","lat="+e.coords.latitude+"&lng="+e.coords.longitude+"&services="+t+"&next_steps="+a,"typeahead_loader",1)},function(e){de("typeahead_loader",false);h(e.message)},e)}else{h(m("Error: Your browser doesn't support geolocation."));de("typeahead_loader",false)}};var W=function(){var e="";if(p("#services").exists()){p("#services option:selected").each(function(){e+=p(this).val()+","})}return e};function Z(e){if(typeof e!="string"||e.length==0)return{};var t=e.split("&");var a=t.length;var s,i={},o,r;for(var d=0;d<a;d++){s=t[d].split("=");o=decodeURIComponent(s[0]);if(o.length==0)continue;r=decodeURIComponent(s[1]);if(typeof i[o]=="undefined")i[o]=r;else if(i[o]instanceof Array)i[o].push(r);else i[o]=[i[o],r]}return i}var Q=function(a,e,s){p.each(e,function(e,t){u(e+"=>"+t+"=>"+a);if(a==e){if(t=="show"){p(s).show()}else{p(s).hide()}}})};var H=function(e){p("#"+e).modal("hide")};var X=function(){z=p(".lazy").Lazy({chainable:false,beforeLoad:function(e){},afterLoad:function(e){e.parent().parent().addClass("image-loaded")},onError:function(e){},onFinishedAll:function(){}})};var ee=function(){setTimeout(()=>{X()},500)};var m=function(e){return e};const te=new Notyf({duration:1e3*4});var h=function(e,t){switch(t){case"error":te.error(e);break;default:te.success(e);break}};const ae=new Notyf({duration:1e4*6,dismissible:true,icon:""});var se=function(e){ae.success(e)};var _=function(){var e=Date.now()+(Math.random()*1e5).toFixed();return e};var g=function(){var e="";var t=p("meta[name=YII_CSRF_TOKEN]").attr("content");e+="&YII_CSRF_TOKEN="+t;return e};var ie=function(e,t,a,s,i,o,r){var d=_();if(!f(s)){d=s}if(f(i)){i=null}var l="";if(f(o)){o="POST"}if(f(r)){l="application/x-www-form-urlencoded; charset=UTF-8";t+=g()}else{l="application/json"}n[d]=p.ajax({url:ajaxurl+"/"+e,method:o,data:t,dataType:"json",contentType:l,timeout:c,crossDomain:true,beforeSend:function(e){if(i==null){}else{de(a,true)}if(n[d]!=null){n[d].abort();clearTimeout(A[d])}else{A[d]=setTimeout(function(){n[d].abort()},c)}}});n[d].done(function(e){var t="";if(typeof e.details!=="undefined"&&e.details!==null){if(typeof e.details.next_action!=="undefined"&&e.details.next_action!==null){t=e.details.next_action}}if(e.code==1){switch(t){case"redirect":window.location.href=e.details.redirect_url;break;case"get_place_details":var a=e.details.data;p(".auto_typehead").val(a.address.address1);var s="id="+a.place_id+"&addressLine1="+a.address.address1+"&addressLine2="+a.address.address2;if(e.details.provider=="google.maps"){if(typeof a.place_type!=="undefined"&&a.place_type!==null){s+="&place_type="+a.place_type}}else if(e.details.provider=="mapbox"){if(typeof a.latitude!=="undefined"&&a.latitude!==null){s+="&latitude="+a.latitude;s+="&longitude="+a.longitude;s+="&place_type="+a.place_type}}s+="&services="+e.details.services;ie("getLocationDetails",s,"typeahead_loader",1);break;case"sidebar_filter":p(".sidenav-filter").html(e.details.data);p(".min-selected-range").html(p("#min_range_slider").val());le();p("#moreCuisine").on("shown.bs.collapse",function(){p(".more-cuisine u").html(m("View less"))});p("#moreCuisine").on("hidden.bs.collapse",function(){p(".more-cuisine u").html(m("Show more +"))});break;case"fill_feed":oe({action:e.details.action,title:e.details.total_message,data:e.details.data,target:e.details.target,append:e.details.append,show_next_page:e.details.show_next_page,next_page_data:e.details.next_page_data});break;case"merchant_menu":MerchantMenu({data:e.details.data});break;case"show_item_details":p("#itemModal").modal("show");ue(e.details.data);break;case"fill_price_format":p(e.details.data.target).html(e.details.data.pretty_price);break}}else{switch(t){case"location_details_no_results":h(e.msg);p(".typeahead__container").removeClass("loading");break;case"clear_restaurant_results":var i=e.details.target;var o="";o+='<div class="list-items mt-4 row hover01 list-loader load-ajax"></div>';o+=e.details.data;p(i).html(o);break;default:h(e.msg);break}}});n[d].always(function(){de(a,false);n[d]=null;clearTimeout(A[d])});n[d].fail(function(e,t){clearTimeout(A[d])})};var oe=function(e){var t="";var a="";var s;var i;var o="";var r=parseInt(e.append);var d=e.target;var l=e.show_next_page;t+='<h4 class="total_message title">';t+="<span>"+e.title+"</span>";t+='<div class="skeleton-placeholder"></div>';t+="</h4>";t+='<div class="list-items mt-4 row hover01 list-loader load-ajax">';p.each(e.data,function(e,t){a+='<div class="col-lg-3 col-md-3 mb-4 mb-lg-3">';a+='<a href="'+t.url+'">';a+='<div class="position-relative">';a+='<div class="skeleton-placeholder"></div>';a+='<figure><img class="rounded lazy" data-src="'+t.url_logo+'"/></figure>';a+='<h6 class="mt-2">'+t.restaurant_name+"</h6>";a+='<div class="row">';a+='<div class="col d-flex justify-content-start align-items-center"> ';a+='<p class="m-0"><i class="zmdi zmdi-time"></i> '+t.delivery_estimation+"</p>";a+="</div>";a+='<div class="col d-flex justify-content-end align-items-center">';if(t.delivery_fee_raw>0){a+='<p class="m-0"><i class="zmdi zmdi-car"></i> '+t.delivery_fee+"</p>"}a+="</div>";a+="</div>";a+='<div class="position-top">';if(typeof t.cuisine!=="undefined"&&t.cuisine!==null){if(t.cuisine.length>0){s=t.cuisine[0].bgcolor;i=t.cuisine[0].fncolor;a+='<span class="badge" style="background:'+s+";font-color:"+i+';">'+t.cuisine[0].cuisine_name+"</span>"}}a+='<span class="badge rounded-pill float-right" style="background:'+s+";font-color:"+i+';" >'+t.ratings+"</span>";a+="</div>";a+="</div>";a+="</a>";a+="</div>"});if(r<=0){t+=a}t+="</div>";if(l==true){u("SHOW NEXT");p.each(e.next_page_data,function(e,t){if(e=="filters"){o+="&filters="+re(JSON.stringify(t))}else o+=e+"="+t+"&"});u("ATTRIBUTES");u(o);t+='<div class="text-center show_more_section mt-5">';t+='<a href="javascript:;" class="btn btn-black  show-more" ';t+="data-action="+e.action+" data-params="+o;t+="\n";t+="data-loader_type="+"show_more"+' data-single_call="1"';t+='data-silent=""';t+=">"+m("Show more")+"</a>";t+='<div class="m-auto circle-loader medium" data-loader="circle-side"></div>';t+="</div>"}else{p(".show_more_section").hide()}if(r>0){p(".list-items").append(a)}else{p(d).html(t)}ee()};var re=function(e){return(e+"").replace(/[\\"']/g,"\\$&").replace(/\u0000/g,"\\0")};var de=function(e,t){switch(e){case"getLocationDetails":case"typeahead_loader":if(t){p(".typeahead__container").addClass("loading")}else{setTimeout(function(){p(".typeahead__container").removeClass("loading")},1e3)}break;case"show_more":if(t){p(".show_more_section").addClass("loading")}else{p(".show_more_section").removeClass("loading")}break;case"results_loader":if(t){B(p(".list-loader"))}else{}break;case"restaurants_filter":if(t){B(p(".list-loader"));p(".restaurants-filter").addClass("loading");p(".total_message").addClass("loading");p(".show_more_section").hide()}else{p(".restaurants-filter").removeClass("loading");p(".total_message").removeClass("loading")}break;case"add_to_cart":if(t){p(".add_to_cart").addClass("loading")}else{p(".add_to_cart").removeClass("loading")}break;default:break}};var le=function(){var _={};p(".star-rating").each(function(e,t){var a=p(this);var s=p(this).data("totalstars");var i=p(this).data("initialrating");var o=p(this).data("strokecolor");var r=p(this).data("ratedcolor");var d=parseInt(p(this).data("strokewidth"));var l=parseInt(p(this).data("starsize"));var n=p(this).data("readonly");var c=p(this).data("minrating");var m=p(this).data("disableafterrate");var u=p(this).data("usefullstars");var h=p(this).data("hovercolor");_["totalStars"]=!f(s)?s:"";_["initialRating"]=!f(i)?i:"";_["minRating"]=!f(c)?c:"";_["strokeColor"]=!f(o)?o:"";_["ratedColor"]=!f(r)?r:"";_["hoverColor"]=!f(h)?h:"";_["strokeWidth"]=!f(d)?d:"";_["starSize"]=!f(l)?l:"";_["readOnly"]=!f(n)?n:"";_["disableAfterRate"]=m;_["useFullStars"]=!f(u)?u:"";_["callback"]=function(e,t){t.parent().find(".ratings_selected").val(e);Pa.$refs.ReviewRef.rating_value=e};p(this).starRating(_)})};var ne=function(){setTimeout(function(){$.updateSticky()},1)};var ce=function(){setTimeout(function(){if(typeof K!=="undefined"&&K!==null){K.updateSticky()}},1)};var me=function(e){p(".menu-left").removeClass("loading");p("#merchant-menu").removeClass("loading");var t=e.data.category;var a=e.data.items;Vue.createApp({data(){return e.data}}).mount("#menu-category");var s=[];var i=[];if(t.length>0){p.each(t,function(e,t){p.each(t.items,function(e,t){i.push(a[t])});s.push({cat_id:t.cat_id,category_name:t.category_name,category_uiid:t.category_uiid,items:i});i=[]})}var s={category:s};Vue.createApp({data(){return s}}).mount("#merchant-menu");ne()};var r;var l;var v;var ue=function(e){r=e.items;l=e.addons;v=e.addon_items;var t={items:r};if(typeof F!=="undefined"&&F!==null){F.$data.items=r;p("#item-details .lazy").attr("src",r.url_image)}else{F=Vue.createApp({data(){return t},created(){}}).mount("#item-details")}};var he=function(e){var t=p("#itemModal .modal-body");r=e.items;l=e.addons;v=e.addon_items;var i="";i+='<div class="item_s" data-item_token="'+r.item_token+'" >';i+='<div class="list-items">';i+='<div class="position-relative">';i+='<div class="skeleton-placeholder"></div>';i+='<img class="rounded lazy" data-src="'+r.url_image+'"/>';i+="</div>";i+="</div>";i+="</div>";i+='<h4 class="m-0 mt-2 mb-2">'+r.item_name+"</h4>";if(!f(r.item_description)){i+="<p>"+r.item_description+"</p>"}var a=0;if(typeof r.price!=="undefined"&&r.price!==null){a=Object.keys(r.price).length}if(a>0){i+='<div class="mb-4 btn-group btn-group-toggle input-group-small choose-size flex-wrap" data-toggle="buttons">';var o=1;p.each(r.price,function(e,t){var a=o==1?"active":"";var s=o==1?"checked='checked'":"";i+='<label class="btn '+a+' ">';i+='<input value="'+t.item_size_id+'" name="size_uuid" id="size_uuid" class="size_uuid" type="radio" '+s+"> ";if(t.discount<=0){i+=t.size_name+" "+t.pretty_price}else{i+=t.size_name+" <del>"+t.pretty_price+"</del> "+t.pretty_price_after_discount}i+="</label>";o++});i+="</div>"}i+='<div class="addon-list"></div>';t.html(i);i="";var s=p('input[name="size_uuid"]:checked').val();_e(s)};var _e=function(e){var i="";var t=p("#itemModal .addon-list");if(typeof r.item_addons!=="undefined"&&r.item_addons!==null){if(typeof r.item_addons[e]!=="undefined"&&r.item_addons[e]!==null){p.each(r.item_addons[e],function(e,a){if(typeof l[a]!=="undefined"&&l[a]!==null){var s={multi_option:l[a].multi_option,multi_option_value:l[a].multi_option_value,require_addon:l[a].require_addon,pre_selected:l[a].pre_selected,sub_items:l[a].sub_items};i+='<DIV class="addon-rows">';i+='<div class="d-flex align-items-center heads">';i+='<div class="flexcol flex-grow-1">';i+='<h5 class="m-0">'+l[a].subcategory_name+"</h5>";i+='<p class="text-grey m-0 mb-1">'+fe(s)+"</p>";i+="</div>";i+='<div class="flexcol">';i+='<h6 class="m-0">'+ge(l[a].require_addon)+"</h6>";i+="</div>";i+="</div>";var t=l[a].sub_items;if(t.length>0){i+='<ul class="list-unstyled list-selection list-addon no-hover m-0 p-0"';i+='data-subcat_id="'+a+'" ';i+='data-multi_option="'+s.multi_option+'" data-multi_option_value="'+s.multi_option_value+'" ';i+='data-require_addon="'+s.require_addon+'" data-pre_selected="'+s.pre_selected+'" ';i+=">";p.each(t,function(e,t){if(typeof v[t]!=="undefined"&&v[t]!==null){i+=pe(v[t],a,s)}});i+="</ul>"}i+="</DIV>"}})}}t.html(i);ItemSummary()};var pe=function(e,t,a){u("AddonItemsRows");u(a);var s=!f(a.multi_option)?a.multi_option:"";var i=!f(a.multi_option_value)?a.multi_option_value:"";u("multi_option=>"+s);u("multi_option_value=>"+i);var o="";o+='<li class="d-flex align-items-center" >';switch(s){case"custom":o+='<div class="custom-control custom-checkbox flex-grow-1">';o+='<input type="checkbox" id="sub_item_id'+e.sub_item_id+'" name="sub_item_id_'+t+'" value="'+e.sub_item_id+'" class="addon-items custom-control-input">';o+='<label class="custom-control-label font14 bold" for="sub_item_id'+e.sub_item_id+'">';o+='<h6 class="m-0">'+e.sub_item_name+"</h6>";o+="</label>";o+="</div>";o+='<p class="m-0">+'+e.pretty_price+"</p>";break;case"multiple":o+='<div class="position-relative quantity-wrapper ">';o+='<div class="quantity-parent">';o+='<div class="quantity d-flex justify-content-between m-auto">';o+='<div><a href="javascript:;" class="rounded-pill qty-btn multiple_qty" data-id="less"><i class="zmdi zmdi-minus"></i></a></div>';o+='<div class="qty" data-id="'+e.sub_item_id+'">0</div>';o+='<div><a href="javascript:;" class="rounded-pill qty-btn multiple_qty" data-id="plus"><i class="zmdi zmdi-plus"></i></a></div>';o+="</div>";o+="</div> ";o+='<a href="javascript:;" class="btn quantity-add-cart multiple_qty">';o+='<i class="zmdi zmdi-plus"></i>';o+="</a>";o+="</div>";o+='<div class="flexcol ml-3">';o+='<h6 class="m-0">'+e.sub_item_name+"</h6>";o+='<p class="m-0 text-grey">+'+e.pretty_price+"</p>";o+="</div>";break;default:o+='<div class="custom-control custom-radio flex-grow-1">';o+='<input type="radio" id="sub_item_id'+e.sub_item_id+'" name="sub_item_id_'+t+'" value="'+e.sub_item_id+'" class="addon-items custom-control-input">';o+='<label class="custom-control-label font14 bold" for="sub_item_id'+e.sub_item_id+'">';o+='<h6 class="m-0">'+e.sub_item_name+"</h6>";o+="</label>";o+="</div>";o+='<p class="m-0">+'+e.pretty_price+"</p>";break}o+="</li>";return o};var fe=function(e){var t="";var a=!f(e.multi_option)?e.multi_option:"";var s=!f(e.sub_items)?e.sub_items:"";var i=!f(e.multi_option_value)?e.multi_option_value:"";switch(a){case"one":t=m("Select 1");break;case"multiple":t=m("Choose up to ")+i;break;case"two_flavor":t=m("Select flavor "+i);break;case"custom":t=m("Choose up to ")+i;break;default:break}return t};var ge=function(e){if(e>0){return m("Required")}else return m("Optional")};var b=0;var ve=0;var be=0;var ye=0;var we="";var y=0;var xe=0;var w;var x;var Se=0;var Te=[];var i=[];var Ie=0;var ke=0;var Ce=function(){u("ItemSummary");y=0;b=0;be=0;ve=0;ye=F.size_id;xe=0;r=j;v=P;if(!f(r.price[ye])){if(r.price[ye].discount>0){xe=!f(r.price[ye])?parseFloat(r.price[ye].price_after_discount):0}else xe=!f(r.price[ye])?parseFloat(r.price[ye].price):0}y+=xe;var i=[];var o=[];p(".list-addon").each(function(e,t){w=p(t).attr("data-multi_option");Ie=p(t).attr("data-require_addon");Se=p(t).attr("data-subcat_id");ke=p(t).attr("data-multi_option_value");if(w=="custom"||w=="one"){x=p(t).find(".addon-items:checked")}else if(w=="multiple"){x=p(t).find(".qty")}if(Ie==1){o.push(Se)}if(ke>0){var a=p(t).find("input:checked");var s=a.length;if(s>=ke){p(t).find("input:checkbox:not(:checked)").attr("disabled",true)}else{p(t).find("input:disabled").attr("disabled",false)}}if(typeof x!=="undefined"&&x!==null){x.each(function(e,t){if(w=="multiple"){b=p(t).data("id");ve=parseInt(p(t).html());be=!f(v[b])?parseFloat(v[b].price):0;y+=ve*be;if(ve>0){i.push(Se)}}else{b=p(t).val();be=!f(v[b])?parseFloat(v[b].price):0;y+=be;i.push(Se)}})}});u("REQUIRED");u(o);u(i);u("END REQUIRED");var a=true;if(o.length>0){p.each(o,function(e,t){if(i.includes(t)===false){a=false;return false}})}u("$required_meet=>"+a);if(a==false){p(".add_to_cart").attr("disabled",true)}else p(".add_to_cart").attr("disabled",false);ie("priceFormat","price="+y+"&target=.item-summary","")};var Oe=function(){ye=p('input[name="size_uuid"]:checked').val();we=p(".item_s").attr("data-item_token");b=0;ve=0;be=0;Te=[];i="";var s="";p(".list-addon").each(function(e,t){w=p(t).attr("data-multi_option");Se=p(t).attr("data-subcat_id");if(w=="custom"||w=="one"){x=p(t).find(".addon-items:checked")}else if(w=="multiple"){x=p(t).find(".qty")}var a=0;if(typeof x!=="undefined"&&x!==null){x.each(function(e,t){if(w=="multiple"){b=p(t).attr("data-id");ve=parseInt(p(t).html());be=!f(P[b])?parseFloat(P[b].price):0}else{b=p(t).val();be=!f(P[b])?parseFloat(P[b].price):0;ve=1}if(ve>0){s+='{"sub_item_id":'+b+', "sub_item_qty":'+ve+"},"}})}if(!f(s)){s=s.slice(0,-1);i+='"'+Se+'":['+s+"],";s="";Te.push({subcat_id:Se,multi_option:w,multi_option_value:p(t).attr("data-multi_option_value")})}});i=i.slice(0,-1);i="{"+i+"}";i=JSON.parse(i);u(i);var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),item_token:we,item_size_id:ye,addons_category:Te,addons_items:i};e=JSON.stringify(e);ie("addCartItems",e,"add_to_cart",1,1,"PUT","json")};var S=function(e,t,a){const s=new Date;s.setTime(s.getTime()+a*24*60*60*1e3);let i="expires="+s.toUTCString();document.cookie=e+"="+t+";"+i+";path=/"};var T=function(e,t,a){let s=e+"=";let i=document.cookie.split(";");for(let t=0;t<i.length;t++){let e=i[t];while(e.charAt(0)==" "){e=e.substring(1)}if(e.indexOf(s)==0){return e.substring(s.length,e.length)}}return""};const je={props:["message","donnot_close"],data(){return{new_message:""}},methods:{show(){p("#loadingBox").modal("show")},close(){p("#loadingBox").modal("hide")},setMessage(e){this.new_message=e}},template:`
	<div class="modal" id="loadingBox" tabindex="-1" role="dialog" aria-labelledby="loadingBox" aria-hidden="true"
	data-backdrop="static" data-keyboard="false" 	 
	 >
	   <div class="modal-dialog modal-dialog-centered modal-loadingbox" role="document">
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
	`};const Ee={props:["title","label","next_url","auto_generate_uuid","enabled_auto_detect_address"],components:{"loading-box":je},data(){return{q:"",awaitingSearch:false,show_list:false,data:[],error:[],autosaved_addres:true}},created(){if(this.enabled_auto_detect_address){this.detectLocation()}},watch:{q(e,t){if(!this.awaitingSearch){if(f(e)){return false}this.show_list=true;setTimeout(()=>{var e;var t=2;e="q="+this.q;e+=g();n[t]=p.ajax({url:ajaxurl+"/getlocation_autocomplete",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.data=e.details.data}else{this.data=[]}});n[t].always(e=>{this.awaitingSearch=false})},1e3)}this.data=[];this.awaitingSearch=true}},methods:{detectLocation(){if(navigator.geolocation){navigator.geolocation.getCurrentPosition(e=>{this.reverseGeocoding(e.coords.latitude,e.coords.longitude)},e=>{ElementPlus.ElNotification({title:"",message:e.message,position:"bottom-right",type:"warning"})})}else{}},reverseGeocoding(e,t){axios({method:"POST",url:ajaxurl+"/reverseGeocoding",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&lat="+e+"&lng="+t,timeout:c}).then(s=>{if(s.data.code==1){let e={id:s.data.details.data.place_id,country:s.data.details.data.address.country,country_code:s.data.details.data.address.country_code};let t=JSON.parse(some_words);let a=t.we_detected;a=a.replace("{address}",s.data.details.data.address.formatted_address);ElementPlus.ElMessageBox.confirm(a,t.address_detected,{confirmButtonText:t.yes,cancelButtonText:t.no,type:"info"}).then(()=>{this.getLocationDetails(e)})["catch"](()=>{})}})["catch"](e=>{}).then(e=>{})},showList(){this.show_list=true},clearData(){this.data=[];this.q=""},getLocationDetails(e){this.show_list=false;var t;var a=3;t="id="+e.id;t+="&country="+e.country;t+="&country_code="+e.country_code;t+="&description="+e.description;t+="&autosaved_addres="+this.autosaved_addres;t+="&auto_generate_uuid="+this.auto_generate_uuid;t+="&cart_uuid="+T("cart_uuid");t+=g();n[a]=p.ajax({url:ajaxurl+"/getLocationDetails",method:"POST",dataType:"json",data:t,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{this.awaitingSearch=true;if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.q=e.details.data.address.formatted_address;if(this.auto_generate_uuid==true||this.auto_generate_uuid=="true"){S("cart_uuid",e.details.cart_uuid,30)}window.location.href=this.next_url}else{this.error[0]=e.msg}});n[a].always(e=>{this.awaitingSearch=false})}},computed:{hasData(){if(this.data.length>0){return true}return false}},template:`	
	<loading-box ref="loading_box" message="Getting address" donnot_close="do not close this window" ></loading-box>
	<div class="position-relative search-geocomplete"> 	
	   <div v-if="!awaitingSearch" class="img-20 m-auto pin_placeholder icon"></div>
	   <div v-if="awaitingSearch" class="icon" data-loader="circle"></div>    
	   	   
	   <input @click="showList" v-model="q" class="form-control form-control-text" 
	   :placeholder="label.enter_address" >
	   
	   <div v-if="hasData" @click="clearData" class="icon-remove"><i class="zmdi zmdi-close"></i></div>
	   <div v-if="!hasData" class="search_placeholder pos-right img-20"></div>
	  </div>
	
	   <div class="search-geocomplete-results" v-if="show_list">		      
	    <ul class="list-unstyled m-0 border">
	     <li v-for="items in data">			      
	      <a href="javascript:;"  @click="getLocationDetails(items)" >
	       <h6 class="m-0">{{items.addressLine1}}</h6>
	       <p class="m-0 text-grey">{{items.addressLine2}}</p>
	      </a>
	     </li>	     
	    </ul>
   </div>
	`};const Pe={props:["label","addresses","location_data","saveplace"],data(){return{q:"",awaitingSearch:false,show_list:false,data:[],error:[],autosaved_addres:true,is_fullwidth:false,visible:false}},mounted(){if(typeof is_mobile!=="undefined"&&is_mobile!==null){if(is_mobile){this.is_fullwidth=true}}},computed:{hasAddress(){if(!f(this.addresses)){if(Object.keys(this.addresses).length>0){return true}}return false},hasData(){if(this.data.length>0){return true}return false}},methods:{showList(){this.show_list=true},clearData(){this.data=[];this.q=""},showModal(){this.visible=true},closeModal(){this.visible=false;this.$emit("afterClose")},close(){this.visible=false},getLocationDetails(e){this.show_list=false;var t;var a=3;t="id="+e.id;t+="&country="+e.country;t+="&country_code="+e.country_code;t+="&autosaved_addres="+this.autosaved_addres;t+="&saveplace="+this.saveplace;t+="&description="+e.description;t+=g();n[a]=p.ajax({url:ajaxurl+"/getLocationDetails",method:"POST",dataType:"json",data:t,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{this.awaitingSearch=true;if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.q="";this.closeModal();this.$emit("setLocation",e.details.data)}else{this.error[0]=e.msg}});n[a].always(e=>{this.awaitingSearch=false})},setPlaceID(e){var t;var a=4;t="place_id="+e;t+=g();n[a]=p.ajax({url:ajaxurl+"/setPlaceID",method:"POST",dataType:"json",data:t,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.$emit("setPlaceid")}else{this.error[0]=e.msg}});n[a].always(e=>{})},editAddress(e){this.$emit("setEdit",e)},deleteAddress(e){var t;var a=5;t="address_uuid="+e;t+=g();n[a]=p.ajax({url:ajaxurl+"/deleteAddress",method:"POST",dataType:"json",data:t,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.$emit("loadData");this.$emit("afterDelete",e)}else{this.error[0]=e.msg}});n[a].always(e=>{})},locateLocation(){this.awaitingSearch=true;if(navigator.geolocation){navigator.geolocation.getCurrentPosition(e=>{this.reverseGeocoding(e.coords.latitude,e.coords.longitude)},e=>{this.awaitingSearch=false;ElementPlus.ElNotification({title:"",message:e.message,position:"bottom-right",type:"warning"})})}else{this.awaitingSearch=false;ElementPlus.ElNotification({title:"",message:"Your browser doesn't support geolocation.",position:"bottom-right",type:"warning"})}},reverseGeocoding(e,t){axios({method:"POST",url:ajaxurl+"/reverseGeocoding",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&lat="+e+"&lng="+t,timeout:c}).then(t=>{if(t.data.code==1){let e=[];e.id=t.data.details.data.place_id;e.country=t.data.details.data.address.country;e.country_code=t.data.details.data.address.country_code;this.getLocationDetails(e)}else{ElementPlus.ElNotification({title:"",message:t.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{}).then(e=>{this.awaitingSearch=false})},searchLocation(e,t){let a=[];axios({method:"POST",url:ajaxurl+"/getlocationAutocomplete",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&q="+e,timeout:c}).then(e=>{if(e.data.code==1){a=e.data.details.data}else{a=[]}t(a)})["catch"](e=>{}).then(e=>{})}},template:`	  	 
	<el-dialog v-model="visible" :close-on-click-modal="false" :show-close="true" :title="label.title"  >
	
	   <div v-cloak v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
	     <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
       </div> 

	   <div class="position-relative search-geocomplete" v-loading="awaitingSearch" > 
			<el-autocomplete
				v-model="q"				
				:placeholder="label.enter_address"				
				size="large"
				class="w-100"
				:trigger-on-focus="false"
				value-key="description"
				:fit-input-width="is_fullwidth"
				clearable
				:fetch-suggestions="searchLocation"				
				@select="getLocationDetails"
			>
			<template #suffix>
				<button @click="locateLocation" type="button" class="btn btn-link">
				<i class="zmdi zmdi-my-location font20" style="color:#000;"></i>
				</button>
			</template>			
			</el-autocomplete>
	   </div>

	   <template v-if="hasAddress">
	    <div class="p-2"></div>
		<el-scrollbar max-height="200px" class="w-100">
			<ul class="m-0 list-unstyled w-100 list-with-icon-button" >
				<li v-for="valadd in addresses" class="row align-items-center no-gutters" >	 
													
				<div class="col-1" @click="setPlaceID(valadd.place_id)" ><i class="zmdi zmdi-pin icon-25"></i></div>
				<div class="col-9" @click="setPlaceID(valadd.place_id)" >
					<h5 class="m-0">{{valadd.address.complete_delivery_address}}</h5>					
				</div>
							
				<div class="col-2 ">	           
					<div class="float-right">
					<a href="javascript:;" @click="editAddress(valadd)" class="rounded-pill rounded-button-icon d-inline-block mr-2">
					<i class="zmdi zmdi-edit"></i>
					</a>
					
					<a href="javascript:;" v-if="location_data.address_uuid != valadd.address_uuid " @click="deleteAddress(valadd.address_uuid)" 
					class="rounded-pill rounded-button-icon d-inline-block">
					<i class="zmdi zmdi-close"></i>
					</a>
					
					</div>	          
				</div>
				</li>	        	      	       
			</ul> 
		</el-scrollbar>
		</template>

	</el-dialog>
	`};const Fe={props:["label","set_place_id","cmaps_config"],data(){return{data:[],is_loading:false,error:[],cmaps:undefined,cmaps_full:false,location_name:"",delivery_options:"",address_label:"",delivery_instructions:"",delivery_options_data:[],address_label_data:[],address1:"",formatted_address:"",new_coordinates:[],inline_edit:false,address2:"",postal_code:"",company:""}},mounted(){autosize(document.getElementById("delivery_instructions"));this.getAddressAttributes()},computed:{DataValid(){if(Object.keys(this.data).length>0){return true}return false},DataValidNew(){if(Object.keys(this.new_coordinates).length>0){return true}return false}},methods:{show(e){this.cmaps_full=false;this.getAdddress(e);p(this.$refs.modal_address).modal("show")},clearForms(){this.data=[];this.location_name="";this.delivery_options="";this.address_label="";this.delivery_instructions="";this.address1="";this.formatted_address="";this.address2="";this.postal_code="";this.company=""},showWithData(e){this.clearForms();p(this.$refs.modal_address).modal("show");this.data=e;this.address1=e.address.address1;this.formatted_address=e.address.formatted_address;this.loadMap()},close(){p(this.$refs.modal_address).modal("hide")},closeModal(){p(this.$refs.modal_address).modal("hide");this.$emit("afterClose")},getAddressAttributes(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content")};var t=_();e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/getAddressAttributes",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.delivery_options_data=e.details.delivery_option;this.address_label_data=e.details.address_label;this.delivery_options=e.details.default_atts.delivery_options;this.address_label=e.details.default_atts.address_label}else{this.delivery_options_data=[];this.address_label_data=[];this.delivery_options="";this.address_label=""}});n[t].always(e=>{this.is_loading=false})},getAdddress(e){var t={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),address_uuid:e};var a=_();t=JSON.stringify(t);n[a]=p.ajax({url:ajaxurl+"/getAdddress",method:"PUT",dataType:"json",data:t,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.data=e.details.data;this.location_name=e.details.data.attributes.location_name;this.delivery_options=e.details.data.attributes.delivery_options;this.address_label=e.details.data.attributes.address_label;this.delivery_instructions=e.details.data.attributes.delivery_instructions;this.address1=e.details.data.address.address1;this.formatted_address=e.details.data.address.formatted_address;this.address2=e.details.data.address.address2;this.postal_code=e.details.data.address.postal_code;this.company=e.details.data.address.company;this.loadMap()}else{this.clearForms()}});n[a].always(e=>{this.is_loading=false})},loadMap(){this.renderMap()},renderMap(){switch(this.cmaps_config.provider){case"google.maps":if(typeof this.cmaps!=="undefined"&&this.cmaps!==null){this.removeMarker()}else{u("render map here");this.cmaps=new google.maps.Map(this.$refs.cmaps,{center:{lat:parseFloat(this.data.latitude),lng:parseFloat(this.data.longitude)},zoom:parseInt(this.cmaps_config.zoom),disableDefaultUI:true})}this.addMarker({position:{lat:parseFloat(this.data.latitude),lng:parseFloat(this.data.longitude)},map:this.cmaps,icon:this.cmaps_config.icon,animation:google.maps.Animation.DROP});break;case"mapbox":mapboxgl.accessToken=this.cmaps_config.key;this.cmaps=new mapboxgl.Map({container:this.$refs.cmaps,style:"mapbox://styles/mapbox/streets-v12",center:[parseFloat(this.data.longitude),parseFloat(this.data.latitude)],zoom:14});this.addMarker({position:{lat:parseFloat(this.data.latitude),lng:parseFloat(this.data.longitude)},map:this.cmaps,icon:this.cmaps_config.icon,animation:null});break;case"yandex":this.initYandexAddress();break}},async initYandexAddress(){console.debug("initYandexAddress");await ymaps3.ready;const{YMap:e,YMapDefaultSchemeLayer:t,YMapMarker:a,YMapDefaultFeaturesLayer:s,YMapListener:i,YMapControls:o}=ymaps3;const{YMapDefaultMarker:r}=await ymaps3["import"]("@yandex/ymaps3-markers@0.0.1");const{YMapZoomControl:d,YMapGeolocationControl:l}=await ymaps3["import"]("@yandex/ymaps3-controls@0.0.1");const n={center:[parseFloat(this.data.longitude),parseFloat(this.data.latitude)],zoom:k};if(I){I.destroy();I=null}if(!I){I=new e(this.$refs.cmaps,{location:n,showScaleInCopyrights:false,behaviors:["drag","scrollZoom"]},[new t({}),new s({})]);R=new r({coordinates:n.center});I.addChild(R)}},addMarker(t){switch(this.cmaps_config.provider){case"google.maps":R=new google.maps.Marker(t);this.cmaps.panTo(new google.maps.LatLng(t.position.lat,t.position.lng));break;case"mapbox":let e={};if(t.draggable){e={draggable:true}}R=new mapboxgl.Marker(e).setLngLat([t.position.lng,t.position.lat]).addTo(this.cmaps);break}},removeMarker(){switch(this.cmaps_config.provider){case"google.maps":R.setMap(null);break;case"mapbox":u("remove marker");R.remove();break}},adjustPin(){console.debug("adjustPin");this.new_coordinates=[];this.cmaps_full=true;try{this.removeMarker()}catch(e){u(e)}this.addMarker({position:{lat:parseFloat(this.data.latitude),lng:parseFloat(this.data.longitude)},map:this.cmaps,icon:this.cmaps_config.icon,animation:this.cmaps_config.provider=="google.maps"?google.maps.Animation.DROP:null,draggable:true});if(this.cmaps_config.provider=="google.maps"){R.addListener("dragend",e=>{this.new_coordinates={lat:e.latLng.lat(),lng:e.latLng.lng()}})}else if(this.cmaps_config.provider=="mapbox"){R.on("dragend",e=>{const t=R.getLngLat();this.new_coordinates={lat:t.lat,lng:t.lng}});this.mapBoxResize()}else if(this.cmaps_config.provider=="yandex"){const t={center:[parseFloat(this.data.longitude),parseFloat(this.data.latitude)],zoom:k};R.update({coordinates:t.center,draggable:true,onDragEnd:e=>{this.new_coordinates={lat:e[1],lng:e[0]}}})}},mapBoxResize(){if(this.cmaps_config.provider=="mapbox"){setTimeout(()=>{this.cmaps.resize()},500)}},cancelPin(){this.error=[];this.cmaps_full=false;if(this.cmaps_config.provider=="yandex"){const e={center:[parseFloat(this.data.longitude),parseFloat(this.data.latitude)],zoom:k};R.update({coordinates:e.center,draggable:false})}else{this.removeMarker();this.addMarker({position:{lat:parseFloat(this.data.latitude),lng:parseFloat(this.data.longitude)},map:this.cmaps,icon:this.cmaps_config.icon,animation:this.cmaps_config.provider=="google.maps"?google.maps.Animation.DROP:null});this.mapBoxResize()}},setNewCoordinates(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),lat:this.data.latitude,lng:this.data.longitude,new_lat:this.new_coordinates.lat,new_lng:this.new_coordinates.lng};var t=1;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/checkoutValidateCoordinates",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.data.latitude=this.new_coordinates.lat;this.data.longitude=this.new_coordinates.lng;this.cmaps_full=false;if(this.cmaps_config.provider=="yandex"){}else{this.removeMarker();this.addMarker({position:{lat:parseFloat(this.data.latitude),lng:parseFloat(this.data.longitude)},map:this.cmaps,icon:this.cmaps_config.icon,animation:google.maps.Animation.DROP})}}else{this.error=e.msg;setTimeout(function(){location.href="#error_message"},1)}});n[t].always(e=>{this.is_loading=false})},save(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),address_uuid:this.data.address_uuid,location_name:this.location_name,delivery_instructions:this.delivery_instructions,delivery_options:this.delivery_options,address_label:this.address_label,latitude:this.data.latitude,longitude:this.data.longitude,address1:this.address1,formatted_address:this.formatted_address,set_place_id:this.set_place_id,address2:this.address2,postal_code:this.postal_code,company:this.company,data:this.data,address_format_use:this.cmaps_config.address_format_use};var t=1;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/SaveAddress",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.close();this.$emit("afterSave",true)}else{this.error=e.msg;setTimeout(function(){location.href="#error_message"},1)}});n[t].always(e=>{this.is_loading=false})},ConfirmDelete(t){o.confirm().then(e=>{if(e){this.deleteAddress(t)}})},deleteAddress(e){var t;var a=5;t="address_uuid="+e;t+=g();n[a]=p.ajax({url:ajaxurl+"/deleteAddress",method:"POST",dataType:"json",data:t,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.close();this.$emit("afterDelete",true)}else{this.error[0]=e.msg}});n[a].always(e=>{})}},template:`
	   <div class="modal" ref="modal_address" id="ModalAddress" tabindex="-1" role="dialog" 
    aria-labelledby="ModalAddress" aria-hidden="true" data-backdrop="static" data-keyboard="false"  >
	   <div class="modal-dialog modal-lg" role="document">
	     <div class="modal-content"> 

		  <div class="modal-header border-bottom-0" style="padding-bottom:0px !important;">
			<h5 class="modal-title">{{label.title}}</h5>        
		 	<a href="javascript:;" @click="closeModal" class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a>
		  </div>

	       <div class="modal-body" >
	       			   	       
		   <template v-if="cmaps_config.provider=='mapbox'">      
				<div  :class="{ 'map-large': cmaps_full, 'map-small': !cmaps_full }"  >
					<div ref="cmaps" id="cmaps" style="height:100%; width:100%;"></div>
				</div>	   
			</template>
			<template v-else>			
			    <div ref="cmaps" id="cmaps" :class="{ 'map-large': cmaps_full, 'map-small': !cmaps_full }"></div>	   
			</template>
	       
	       <template v-if="!cmaps_full">
	       	       
	        <div class="row mt-3" >
		     <div class="col">
		       <h5 class="m-0">{{address1}}</h5>
			   <p class="m-0">{{formatted_address}}</p>			       
		     </div>
		     <div class="col text-right">
		       <button class="btn small btn-black" @click="adjustPin" :disabled="!DataValid" >{{label.adjust_pin}}</button>
		     </div>
		   </div>
		    <!--row-->				   
		    
		   <div class="forms mt-3 mb-2">		  
				   		   	   

		   ComponentsAddress
		   
		   <template v-if="cmaps_config.address_format_use==2">
		        
		        <div class="row">
				  <div class="col">
						<div class="form-label-group">
							<input v-model="formatted_address" class="form-control form-control-text" placeholder="" id="formatted_address" type="text">
							<label for="formatted_address">
							{{label.street_name}} ({{label.mandatory}})
							</label>
						</div>
				  </div>
				  <div class="col">
				        <div class="form-label-group">
							<input v-model="address1" class="form-control form-control-text" placeholder="" id="address1" type="text">
							<label for="address1">
							{{label.street_number}} ({{label.mandatory}})
							</label>
						</div>						
				  </div>
			   </div>
							
			   <div class="row">
				  <div class="col">
				    <div class="form-label-group">
						<input v-model="location_name" class="form-control form-control-text" placeholder="" id="location_name" type="text">
						<label for="location_name">
						{{label.entrance}}
						</label>
					</div>
				  </div>
				  <div class="col">
				    <div class="form-label-group">
						<input v-model="address2" class="form-control form-control-text" placeholder="" id="address2" type="text">
						<label for="address2">
						{{label.floor}}
						</label>
					</div>				  
				  </div>
			   </div>

							
			  <div class="row">
				 <div class="col">

				   <div class="form-label-group">
						<input v-model="postal_code" class="form-control form-control-text" placeholder="" id="postal_code" type="text">
						<label for="postal_code">
						{{label.door}} ({{label.mandatory}})
						</label>
					</div>			
				 
				 </div>
				 <div class="col">

					<div class="form-label-group">
						<input v-model="company" class="form-control form-control-text" placeholder="" id="company" type="text">
						<label for="company">
						{{label.company}}
						</label>
					</div>		
				 
				 </div>
			  </div>
											
		   
		   </template>
		   <template v-else>

			<div class="form-label-group">
				<input v-model="formatted_address" class="form-control form-control-text" placeholder="" id="formatted_address" type="text">
					<label for="formatted_address">
					{{label.street_name}}
					</label>
				</div>

				<div class="form-label-group">
				<input v-model="address1" class="form-control form-control-text" placeholder="" id="address1" type="text">
				<label for="address1">
				{{label.street_number}}
				</label>
				</div>
			
				<div class="form-label-group">    
				<input class="form-control form-control-text" v-model="location_name"
				id="location_name" type="text" >   
				<label for="location_name">{{label.location_name}}</label> 
				</div>   

			</template>
	        	        
	        <h5 class="m-0 mt-2 mb-2">{{label.delivery_options}}</h5>       
	        <select class="form-control custom-select" v-model="delivery_options">		 
	         <option v-for="(items, key) in delivery_options_data" :value="key" >{{items}}</option>      
		    </select>  
		    
			     
		      <h5 class="m-0 mt-3 mb-2">{{label.delivery_instructions}}</h5>      
		      <div class="form-label-group">    
		        <textarea id="delivery_instructions" 
		        style="max-height:150px;" v-model="delivery_instructions"  class="form-control form-control-text font13" 
		              :placeholder="label.notes">
		        </textarea>       
		      </div>  
		      
		    <p>{{label.address_label}}</p>  
		    <div class="btn-group btn-group-toggle input-group-small mb-4" >
	           <label class="btn" v-for="(items, key) in address_label_data" :class="{ active: address_label==key }" >
	             <input v-model="address_label" type="radio" :value="key" > 
	             {{ items }}
	           </label>           
	        </div>
		    <!--btn-group-->   
		   
		   </div> <!--forms--> 
	   
		   </template>
		   
		   <div id="error_message"  v-cloak v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
	         <p v-for="err in error" class="m-0">{{err}}</p>	    
	       </div>   
	           	   
	        
	       </div> <!--modal-body-->
	       
	       
	       <div class="modal-footer justify-content-start">
      
       <template v-if="!cmaps_full">
	       <div class="border flex-fill">
	           <button class="btn btn-black w-100" @click="closeModal" >
		          {{label.cancel}}
		       </button>
	       </div>
	       <div class="border flex-fill">
	           <button class="btn btn-green w-100" @click="save" :class="{ loading: is_loading }" :disabled="!DataValid"  >
		          <span class="label">{{label.save}}</span>
		          <div class="m-auto circle-loader" data-loader="circle-side"></div>
		       </button>
	       </div>
	       </template>
	       
	       <template v-else-if="cmaps_full">
	        <div class="border flex-fill">
	           <button class="btn btn-black w-100" @click="cancelPin" >
		          {{label.cancel}}
		       </button>
	       </div>       
	       <div class="border flex-fill">		   
	           <button class="btn btn-green w-100" @click="setNewCoordinates" 
	           :class="{ loading: is_loading }" :disabled="!DataValidNew" >
		          <span class="label">{{label.save}}</span>
		          <div class="m-auto circle-loader" data-loader="circle-side"></div>
		       </button>
	       </div>
       </template>
       
	   </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->
	`};const Ne={beforeMount:(t,a)=>{t.clickOutsideEvent=e=>{if(!(t==e.target||t.contains(e.target))){a.value()}};document.addEventListener("click",t.clickOutsideEvent)},unmounted:e=>{document.removeEventListener("click",e.clickOutsideEvent)}};const Re={props:["title","label"],data(){return{transaction_list:[],transaction:"",loading:false,charge_type:"",estimation:[]}},mounted(){this.getMerchantServices()},methods:{getMerchantServices(){var e;var t=_();e="merchant_id="+merchant_id;e+="&cart_uuid="+T("cart_uuid");e+=g();n[t]=p.ajax({url:ajaxurl+"/servicesList",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:function(e){this.loading=false;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.transaction_list=e.details.data;this.transaction=e.details.transaction;this.charge_type=e.details.charge_type;this.estimation=e.details.estimation}else{this.transaction_list=[];this.transaction="";this.charge_type="";this.estimation=[]}});n[t].always(e=>{this.loading=false})},doUpdateService(e){this.transaction=e;this.updateService()},updateService(){axios({method:"POST",url:ajaxurl+"/updateService",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&transaction_type="+this.transaction+"&cart_uuid="+T("cart_uuid"),timeout:c}).then(e=>{if(e.data.code==1){S("cart_transaction_type",this.transaction,30);S("cart_uuid",e.data.details.cart_uuid,30)}if(typeof Y!=="undefined"&&Y!==null){Y.addressNeeded()}})["catch"](e=>{}).then(e=>{this.$emit("afterUpdate",this.transaction)})}},template:` 				
	<div class="btn-group-rounded btn-group btn-group-toggle" >
	  <label class="btn d-flex"
	   v-for="trans in transaction_list" :class="{ active: transaction==trans.service_code }" >
	     <input type="radio" :value="trans.service_code" v-model="transaction" @click="doUpdateService(trans.service_code)" > 
	       <div class="align-self-center">
	         <div class="bold">{{ trans.service_name }}</div>
	         
	         <template v-if="estimation[trans.service_code]">
	           <template v-for="(item,index) in estimation[trans.service_code] ">
	           
	             <template v-if="trans.service_code=='delivery'">
		             <template v-if="item.charge_type==charge_type">	              
		              <p class="m-0">{{item.estimation}} {{label.min}}</p>
		             </template>
	             </template>
	             <template v-else> 
	                 <template v-if="item.charge_type=='fixed'">	              
		              <p class="m-0">{{item.estimation}} {{label.min}}</p>
		             </template>
	             </template>
	             
	           </template>
	         </template>
	         
	       </div>
	   </label>           
	</div> 
	`};const De={props:["sitekey","size","theme","is_enabled","captcha_lang"],data(){return{recaptcha:null}},mounted(){if(this.is_enabled==1||this.is_enabled=="true"||this.is_enabled==true){this.initCapcha()}},methods:{initCapcha(){if(window.grecaptcha==null){new Promise(e=>{window.recaptchaReady=function(){e()};const t=window.document;const a="recaptcha-script";const s=t.createElement("script");s.id=a;s.setAttribute("src","https://www.google.com/recaptcha/api.js?onload=recaptchaReady&render=explicit&hl="+this.captcha_lang);t.head.appendChild(s)}).then(()=>{this.renderRecaptcha()})}else{this.renderRecaptcha()}},renderRecaptcha(){this.recaptcha=grecaptcha.render(this.$refs.recaptcha_target,{sitekey:this.sitekey,theme:this.theme,size:this.size,tabindex:this.tabindex,callback:e=>this.$emit("verify",e),"expired-callback":()=>this.$emit("expire"),"error-callback":()=>this.$emit("fail")})},reset(){grecaptcha.reset(this.recaptcha)}},template:`	
    <div class="mb-2 mt-2" ref="recaptcha_target"></div>
    `};const e={props:["label","mobile_number","mobile_prefix","default_country","only_countries"],emits:["update:mobile_number","update:mobile_prefix"],data(){return{data:[],country_flag:""}},updated(){},mounted(){this.getLocationCountries()},methods:{getLocationCountries(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),default_country:this.default_country,only_countries:this.only_countries};var t=_();e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/getLocationCountries",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.data=e.details.data;this.country_flag=e.details.default_data.flag;this.$emit("update:mobile_prefix",e.details.default_data.phonecode)}else{this.data=[];this.country_flag="";this.mobile_prefix=""}});n[t].always(e=>{})},setValue(e){this.country_flag=e.flag;this.$emit("update:mobile_prefix",e.phonecode);this.$refs.ref_mobile_number.focus()}},template:`					
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
		<input type="text"  v-maska="'###################'"  ref="ref_mobile_number"
		:value="mobile_number" @input="$emit('update:mobile_number', $event.target.value)" >
		
	</div> <!--inputs-->
	`};const Ye={props:["label","size"],methods:{confirm(){bootbox.confirm({size:this.size,title:this.label.confirm,message:this.label.are_you_sure,centerVertical:true,animate:false,buttons:{cancel:{label:this.label.cancel,className:"btn btn-black small pl-4 pr-4"},confirm:{label:this.label.yes,className:"btn btn-green small pl-4 pr-4"}},callback:e=>{this.$emit("callback",e)}})},alert(e,t){bootbox.alert({size:!f(t.size)?t.size:"",closeButton:false,message:e,animate:false,centerVertical:true,buttons:{ok:{label:this.label.ok,className:"btn btn-green small pl-4 pr-4"}}})}}};var Me,Ae,$e;const Ke={components:{"star-rating":VueStarRating["default"]},props:["label","accepted_files","max_file"],data(){return{upload_images:[],required_message:[],rating_value:"",review_content:"",as_anonymous:false,order_uuid:"",is_loading:false,error:[],is_reset:false}},mounted(){Me=new Tagify(document.querySelector(".tags_like"));Ae=new Tagify(document.querySelector(".tags_not_like"));autosize(document.getElementById("review_content"));this.initDropZone(this.label,this)},methods:{show(){p("#reviewModal").modal("show")},close(){p(this.$refs.review_modal).modal("hide");Dropzone.forElement("#dropzone_multiple").removeAllFiles(true)},reset(){Dropzone.forElement("#dropzone_multiple").files=[];p(".dz-preview").remove();p(".dropzone").removeClass("dz-started");this.upload_images=[];this.rating_value="";this.review_content="";this.as_anonymous=false;this.order_uuid="";this.error=[];Me.removeAllTags();Ae.removeAllTags()},initDropZone(t,i){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content")};Dropzone.autoDiscover=false;$e=p("#dropzone_multiple").dropzone({dictDefaultMessage:t.dictDefaultMessage,dictFallbackMessage:t.dictFallbackMessage,dictFallbackText:t.dictFallbackText,dictFileTooBig:t.dictFileTooBig,dictInvalidFileType:t.dictInvalidFileType,dictResponseError:t.dictResponseError,dictCancelUpload:t.dictCancelUpload,dictCancelUploadConfirmation:t.dictCancelUploadConfirmation,dictRemoveFile:t.dictRemoveFile,dictMaxFilesExceeded:t.dictMaxFilesExceeded,paramName:"file",url:ajaxurl+"/uploadReview",params:e,addRemoveLinks:true,maxFiles:parseInt(this.max_file),acceptedFiles:this.accepted_files,autoProcessQueue:true,init:function(){var s=this;s.on("maxfilesexceeded",function(e){h(t.max_file_exceeded,"error");s.removeFile(e)});s.on("removedfile",function(e){u("removedfile");i.removeUpload(e.upload_uuid)});s.on("success",(e,t)=>{u(t);var a=t;if(a.code==1){e.upload_uuid=a.details.id;i.upload_images.push(a.details)}else{var a=JSON.parse(t);h(a.msg,"error");s.removeFile(e)}});s.on("addedfile",function(){u("addedfile")})}})},removeUpload(i){return new Promise((t,e)=>{var a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),id:i};var s=_();n[s]=p.ajax({url:ajaxurl+"/removeReviewImage",method:"PUT",dataType:"json",data:JSON.stringify(a),contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;if(n[s]!=null){n[s].abort();this.is_loading=false}}});n[s].done(e=>{this.is_loading=false;t(true);if(e.code==1){}});n[s].always(e=>{this.is_loading=false})})},remove(a){if(this.upload_images){this.upload_images.forEach((e,t)=>{if(e.id==a){u("index remove=>"+t);this.upload_images.splice(t,1)}})}},getTag(e){var a=[];if(e){e.forEach((e,t)=>{a.push(e.value)})}return a},addReview(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:this.order_uuid,review_content:this.review_content,rating_value:this.rating_value,as_anonymous:this.as_anonymous,tags_like:this.getTag(Me.value),tags_not_like:this.getTag(Ae.value),upload_images:this.upload_images};var t=1;n[t]=p.ajax({url:ajaxurl+"/addReview",method:"PUT",dataType:"json",data:JSON.stringify(e),contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];if(n[t]!=null){n[t].abort();this.is_loading=false}}});n[t].done(e=>{u(e);if(e.code==1){h(e.msg);this.reset();p(this.$refs.review_modal).modal("hide")}else{this.error=e.msg;setTimeout(function(){location.href="#error_message"},1)}});n[t].always(e=>{this.is_loading=false})},deleteAllImages(){if(this.upload_images.length<=0){return false}var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),upload_images:this.upload_images};var t=_();n[t]=p.ajax({url:ajaxurl+"/removeAllReviewImage",method:"PUT",dataType:"json",data:JSON.stringify(e),contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.upload_images=[];Dropzone.forElement("#dropzone_multiple").removeAllFiles(true)}})}},template:`
  <div class="modal" ref="review_modal" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModal" aria-hidden="true"  data-backdrop="static" data-keyboard="false" >
       <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content">     
                
        	      
          <div class="modal-body" >    
          
          <a href="javascript:;" @click="close" 
	        class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a> 
          
          <h4 class="bold m-0 mb-3 mt-3">{{label.write_review}}</h4> 	 
                              	     	    
	     <star-rating  
         v-model:rating="rating_value"
		 :star-size="30"
		 :show-rating="false" 
		 @update:rating="rating_value = $event"
		 >
		 </star-rating>	      	    		
	       
	     <h5 class="m-0 mt-3 mb-2">{{label.what_did_you_like}}</h5>     
	     <input type="text" name="tags_like" class="tags_like" id="tags_like" :placeholder="label.search_tag"> 
	     
	     <h5 class="m-0 mt-2 mb-2">{{label.what_did_you_not_like}}</h5>     
	     <input type="text" name="tags_not_like" class="tags_not_like" id="tags_not_like" :placeholder="label.search_tag"> 
	      	     
	     <h5 class="m-0 mt-3 mb-2">{{label.add_photo}}</h5>      
	     <div class="mb-3">
	         <div class="dropzone dropzone_container" id="dropzone_multiple" data-action="gallery_create">
			   <div class="dz-default dz-message">
			    <i class="fas fa-cloud-upload-alt"></i>
			     <p>{{label.drop_files_here}}</p>
			    </div>
			</div> 
	     </div>
	     	     	    
	    <!-- <div v-if="upload_images.length>0" class="row gallery m-0">
	      <div v-for="images in upload_images" class="col-lg-4 col-md-4 col-sm-12 mb-0 mb-lg-0  p-1">
	        <div class="position-relative">
	           <img :src="images.url_image">
	           <a  href="javascript:;"  
	           @click="removeUpload(images.id)" data-dz-remove 
	           class="remove btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-plus"></i></a>
	        </div>
	      </div>	      
	     </div>-->
	     
	     <h5 class="m-0 mt-2 mb-2">{{label.write_your_review}}</h5>      
         <div class="form-label-group">    
           <textarea id="review_content" class="form-control form-control-text font13" 
           :placeholder="label.review_helps"
           v-model="review_content"
	       style="max-height:150px;"
           >
           </textarea>       
        </div>
                
		<div  class="custom-control custom-checkbox flex-grow-1">            
		  <input type="checkbox" class="custom-control-input"
		  name="as_anonymous" value="1" 
		  id="as_anonymous"	    
		  v-model="as_anonymous"  
		  >	  
		  <label class="custom-control-label font14 bold" for="as_anonymous" >
		   {{label.post_review_anonymous}}
		  </label>
		</div>    
		
				
		<div id="error_message" v-cloak v-if="error.length>0" class="alert alert-warning mb-2 mt-2" role="alert">
          <p v-cloak v-for="message in error" class="m-0">{{message}}</p>	    
        </div>  
            
          </div> <!--modal body-->
          
          <div class="modal-footer justify-content-start">
            <button class="btn btn-black w-100" @click="addReview" :class="{ loading: is_loading }" >               
               <span class="label">{{label.add_review}}</span>
               <div class="m-auto circle-loader" data-loader="circle-side"></div>
            </button>
          </div> <!--footer-->
       
	   </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->  
  `};const ze={props:["ajax_url","amount","price_format"],data(){return{data:0,config:JSON.parse(money_config)}},mounted(){this.data=window["v-money3"].format(this.amount,this.config)},watch:{price_format(e,t){this.config=e}},updated(){this.data=window["v-money3"].format(this.amount,this.config)},methods:{formatNumber(e){return window["v-money3"].format(e,this.config)}},template:`		
    {{data}}
    `};var Le=function(e){return window["v-money3"].format(e,JSON.parse(money_config))};let I;let k=16.6;const qe={props:["keys","provider","zoom","center","markers","size"],template:`     		
    <div ref="cmaps" class="map border" :class="size"></div>    
		
	<div class="position-absolute map-buttons-wrap" v-if="size=='map-lg-large'" >
		<div class="btn-group-vertical" v-loading="loading" v-if="provider!='yandex'" >
		    <div class="mb-2">
			<button @click="locateLocation" type="button" class="btn btn-white rounded-circle">
				<i class="zmdi zmdi-gps-dot font25"></i>
			</button>
			</div>
			<button @click="setPlusZoom()" type="button" class="btn btn-white">
				<i class="zmdi zmdi-plus font25"></i>
			</button>
			<div class="divider py-1 bg-dark"></div>
			<button @click="setLessZoom()" type="button" class="btn btn-white">
				<i class="zmdi zmdi-minus font25"></i>
			</button>
		</div>
	</div>
    `,data(){return{cmapsMarker:[],latLng:[],cmaps:undefined,bounds:undefined,loading:false}},mounted(){this.renderMap()},watch:{markers(e,t){this.renderMap()}},methods:{locateLocation(){this.$emit("dragMarker",true);this.loading=true;if(navigator.geolocation){navigator.geolocation.getCurrentPosition(e=>{this.$emit("dragMarker",false);this.loading=false;this.$emit("afterSelectmap",e.coords.latitude,e.coords.longitude);u(e)},e=>{this.$emit("dragMarker",false);this.loading=false;ElementPlus.ElNotification({title:"",message:e.message,position:"bottom-right",type:"warning"})})}else{this.$emit("dragMarker",false);this.loading=false;ElementPlus.ElNotification({title:"",message:"Your browser doesn't support geolocation.",position:"bottom-right",type:"warning"})}},renderMap(){try{switch(this.provider){case"google.maps":this.bounds=new window.google.maps.LatLngBounds;if(typeof this.cmaps!=="undefined"&&this.cmaps!==null&&Object.keys(this.cmapsMarker).length>0){this.moveAllMarker()}else{this.cmaps=new window.google.maps.Map(this.$refs.cmaps,{center:{lat:parseFloat(this.center.lat),lng:parseFloat(this.center.lng)},zoom:parseInt(this.zoom),disableDefaultUI:true,styles:L});Object.entries(this.markers).forEach(([e,t])=>{this.addMarker({position:{lat:parseFloat(t.lat),lng:parseFloat(t.lng)},map:this.cmaps,draggable:t.draggable==1?true:false,label:t.label},t.id,t.draggable)})}break;case"mapbox":if(typeof this.cmaps!=="undefined"&&this.cmaps!==null&&Object.keys(this.cmapsMarker).length>0){this.moveAllMarker()}else{mapboxgl.accessToken=this.keys;this.bounds=new mapboxgl.LngLatBounds;this.cmaps=new mapboxgl.Map({container:this.$refs.cmaps,style:"mapbox://styles/mapbox/streets-v12",center:[parseFloat(this.center.lng),parseFloat(this.center.lat)],zoom:14});this.cmaps.on("error",e=>{u(e.error.message)});Object.entries(this.markers).forEach(([e,t])=>{this.addMarker({position:{lat:parseFloat(t.lat),lng:parseFloat(t.lng)},map:this.cmaps,draggable:t.draggable==1?true:false},t.id,t.draggable)});if(Object.keys(this.markers).length>1){this.FitBounds()}else{if(this.markers[0]){this.setCenter(this.markers[0].lat,this.markers[0].lng)}}}break;case"yandex":this.initYandex();break}}catch(e){console.error(e)}},async initYandex(){console.debug("initYandex");await ymaps3.ready;const{YMap:t,YMapDefaultSchemeLayer:a,YMapMarker:e,YMapDefaultFeaturesLayer:s,YMapListener:i,YMapControls:o}=ymaps3;const{YMapDefaultMarker:r}=await ymaps3["import"]("@yandex/ymaps3-markers@0.0.1");const{YMapZoomControl:d,YMapGeolocationControl:l}=await ymaps3["import"]("@yandex/ymaps3-controls@0.0.1");const n={center:[parseFloat(this.center.lng),parseFloat(this.center.lat)],zoom:k};if(typeof this.$refs.cmaps!=="undefined"&&this.$refs.cmaps!==null){}else{return}console.debug("yandex_map",I);console.debug("this.markers",this.markers);if(!I){console.debug("ADD Yandex map");I=new t(this.$refs.cmaps,{location:n,showScaleInCopyrights:false,behaviors:["drag","scrollZoom"]},[new a({}),new s({})]);let e=true;if(Object.keys(this.markers).length>0){e=this.markers[0].draggable}console.debug("is_dragable",e);if(e){I.addChild(new i({onUpdate:e=>{if(e.mapInAction){this.cmapsMarker[0].update({coordinates:e.location.center});this.$emit("dragMarker",true)}else{this.$emit("dragMarker",false)}},onActionEnd:e=>{console.log("onActionEnd",e.type);if(e.type=="drag"){this.cmapsMarker[0].update({coordinates:e.location.center});this.$emit("afterSelectmap",e.location.center[1],e.location.center[0])}else if(e.type=="scrollZoom"){k=e.location.zoom}}}))}if(this.size=="map-lg-large"){I.addChild(new o({position:"right"}).addChild(new d({})));I.addChild(new o({position:"top right"}).addChild(new l({onGeolocatePosition:e=>{console.log("onGeolocatePosition",e);this.$emit("afterSelectmap",e[1],e[0])}})))}Object.entries(this.markers).forEach(([e,t])=>{let a=t.id;this.cmapsMarker[a]=new r({coordinates:[parseFloat(t.lng),parseFloat(t.lat)],draggable:t.draggable,onDragEnd:e=>{console.log("onDragEnd",e);this.$emit("afterSelectmap",e[1],e[0])}});I.addChild(this.cmapsMarker[a])});if(Object.keys(this.markers).length>1){}else{if(this.markers[0]){const c={center:[this.markers[0].lng,this.markers[0].lat],zoom:k};I.update({location:c})}}}else{console.debug("move all marker");Object.entries(this.markers).forEach(([e,t])=>{let a=t.id;if(this.cmapsMarker[a]){this.cmapsMarker[a].update({coordinates:[t.lng,t.lat]})}});if(Object.keys(this.markers).length>1){}else{if(this.markers[0]){const c={center:[this.markers[0].lng,this.markers[0].lat],zoom:k};I.update({location:c})}}}},async addMarker(e,a,t){try{switch(this.provider){case"google.maps":this.cmapsMarker[a]=new window.google.maps.Marker(e);this.cmaps.panTo(new window.google.maps.LatLng(e.position.lat,e.position.lng));this.bounds.extend(this.cmapsMarker[a].position);if(t===true){google.maps.event.addListener(this.cmaps,"drag",e=>{this.$emit("dragMarker",true);let t=new google.maps.LatLng(this.cmaps.getCenter().lat(),this.cmaps.getCenter().lng());this.cmapsMarker[a].setPosition(t)});google.maps.event.addListener(this.cmaps,"dragend",e=>{let t=new google.maps.LatLng(this.cmaps.getCenter().lat(),this.cmaps.getCenter().lng());this.$emit("dragMarker",false);this.$emit("afterSelectmap",t.lat(),t.lng())});window.google.maps.event.addListener(this.cmapsMarker[a],"drag",e=>{this.$emit("dragMarker",true)});window.google.maps.event.addListener(this.cmapsMarker[a],"dragend",e=>{const t=e.latLng;this.latLng={lat:t.lat(),lng:t.lng()};this.$emit("dragMarker",false);this.$emit("afterSelectmap",t.lat(),t.lng())})}break;case"mapbox":this.cmapsMarker[a]=new mapboxgl.Marker(e).setLngLat([e.position.lng,e.position.lat]).addTo(this.cmaps);this.bounds.extend(new mapboxgl.LngLat(e.position.lng,e.position.lat));if(t===true){this.cmapsMarker[a].on("dragend",e=>{const t=this.cmapsMarker[a].getLngLat();u(t.lat+"=>"+t.lng);this.$emit("afterSelectmap",t.lat,t.lng)});this.cmaps.on("drag",()=>{this.cmapsMarker[a].setLngLat([this.cmaps.getCenter().lng.toFixed(4),this.cmaps.getCenter().lat.toFixed(4)])});this.cmaps.on("dragend",()=>{this.cmapsMarker[a].setLngLat([this.cmaps.getCenter().lng.toFixed(4),this.cmaps.getCenter().lat.toFixed(4)]);this.$emit("afterSelectmap",this.cmaps.getCenter().lat.toFixed(4),this.cmaps.getCenter().lng.toFixed(4))})}this.mapBoxResize();break}}catch(s){console.error(s)}},mapBoxResize(){if(this.provider=="mapbox"){setTimeout(()=>{this.cmaps.resize()},500)}},moveAllMarker(){console.log("moveAllMarker");console.log(this.markers);if(this.provider=="google.maps"){if(Object.keys(this.markers).length>0){Object.entries(this.markers).forEach(([e,t])=>{let a=new google.maps.LatLng(parseFloat(t.lat),parseFloat(t.lng));if(!f(this.cmapsMarker[t.id])){this.cmapsMarker[t.id].setPosition(a)}});if(Object.keys(this.markers).length>1){this.FitBounds()}else{this.cmaps.panTo(new window.google.maps.LatLng(this.markers[0].lat,this.markers[0].lng))}}}else{if(Object.keys(this.markers).length>0){Object.entries(this.markers).forEach(([e,t])=>{if(!f(this.cmapsMarker[t.id])){this.cmapsMarker[t.id].setLngLat([t.lng,t.lat]).addTo(this.cmaps)}});if(Object.keys(this.markers).length>1){this.FitBounds()}else{this.cmaps.flyTo({center:[this.markers[0].lng,this.markers[0].lat],essential:true,zoom:14})}}}},centerMap(){this.FitBounds()},FitBounds(){try{switch(this.provider){case"google.maps":if(!f(this.bounds)){this.cmaps.fitBounds(this.bounds)}break;case"mapbox":if(!f(this.bounds)){this.cmaps.fitBounds(this.bounds,{duration:0,padding:50})}break}}catch(e){}},setCenter(e,t){try{switch(this.provider){case"google.maps":this.cmaps.setCenter(new window.google.maps.LatLng(e,t));break;case"mapbox":this.cmaps.flyTo({center:[t,e],essential:true});break}}catch(a){console.error(a)}},setPlusZoom(){if(this.provider=="yandex"){}else{this.cmaps.setZoom(this.cmaps.getZoom()+2)}},setLessZoom(){if(this.provider=="yandex"){}else{this.cmaps.setZoom(this.cmaps.getZoom()-2)}}}};const Ve={props:["label","modelValue","formatted_address","enabled_locate"],emits:["update:modelValue"],data(){return{q:"",awaitingSearch:false,show_list:false,data:[],error:[],hasSelected:false,loading:false,addressData:[],auto_locate:false,is_fullwidth:false,selectedData:[]}},created(){this.q=this.formatted_address;if(typeof is_mobile!=="undefined"&&is_mobile!==null){if(is_mobile){this.is_fullwidth=true}}},watch:{formatted_address(e,t){this.q=e}},computed:{hasData(){if(this.data.length>0){return true}return false}},methods:{showList(){this.show_list=true},clearData(){this.data=[];this.q=""},setData(e){this.clearData();this.q=e.description;this.$emit("update:modelValue",e.description);this.$emit("afterChoose",e)},locateLocation(){this.loading=true;if(navigator.geolocation){navigator.geolocation.getCurrentPosition(e=>{this.reverseGeocoding(e.coords.latitude,e.coords.longitude)},e=>{this.loading=false;ElementPlus.ElNotification({title:"",message:e.message,position:"bottom-right",type:"warning"})})}else{this.loading=false;ElementPlus.ElNotification({title:"",message:"Your browser doesn't support geolocation.",position:"bottom-right",type:"warning"})}},reverseGeocoding(e,t){axios({method:"POST",url:ajaxurl+"/reverseGeocoding",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&lat="+e+"&lng="+t,timeout:c}).then(e=>{if(e.data.code==1){this.q=e.data.details.data.address.formatted_address;this.addressData=e.data.details.data;this.$emit("afterGetcurrentlocation",this.addressData)}else{this.addressData=[];ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{}).then(e=>{this.loading=false;this.auto_locate=false})},setAddressData(e){this.addressData=e},setLoading(e){this.loading=e},onSubmit(){if(Object.keys(this.addressData).length>0){console.log("selectedData",this.selectedData);if(this.selectedData.from_addressbook){this.$emit("afterPointaddress",this.selectedData)}else{this.$emit("afterGetcurrentlocation",this.addressData)}}else{this.auto_locate=true;this.locateLocation()}},searchLocation(e,t){let a=[];let s=1;if(typeof isGuest!=="undefined"&&isGuest!==null){s=isGuest}let i=f(e)?"getRecentAddress":"getlocationAutocomplete";i=s==1?"getlocationAutocomplete":i;if(s==1&&f(e)){t(a);return}axios({method:"POST",url:ajaxurl+"/"+i,data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&q="+e,timeout:c}).then(e=>{if(e.data.code==1){a=e.data.details.data}else{a=[]}t(a)})["catch"](e=>{}).then(e=>{})},handleSelect(e){this.selectedData=e;this.$emit("update:modelValue",e.description);this.$emit("afterChoose",e)}},template:`	
	<div class="d-flex flex-sm-row flex-column align-items-center justify-content-between">
		<div class="position-relative search-geocomplete" v-loading="loading" :class="{width_87 : enabled_locate , 'w-100' : !enabled_locate}"  > 	
			<div class="p-0">			
				<el-autocomplete
					v-model="q"				
					:placeholder="label.enter_address"				
					size="large"
					class="w-100"
					:trigger-on-focus="true"
					value-key="description"
					:fit-input-width="is_fullwidth"
					clearable
					:fetch-suggestions="searchLocation"				
					@select="handleSelect"
				>
				<template #suffix>
				<button v-if="enabled_locate" @click="locateLocation" type="button" class="btn btn-link">
					<i class="zmdi zmdi-my-location font20" style="color:#000;"></i>
				</button>
				</template>			
				</el-autocomplete>
			</div>
		</div>    
		<div v-if="enabled_locate" class="flex-enabled-locate">
		<button @click="onSubmit" type="button" class="btn btn-green pl-3 pr-3" :disabled="loading">
		   <i class="zmdi zmdi-arrow-right font25"></i>
		</button>
		</div>	
	</div>      			
	`};const Ue={props:["data","keys","provider","zoom","center","label"],components:{"components-maps":qe,"component-auto-complete":Ve},data(){return{markers:[],address_data:[],loading:false,drag:false,visible:false}},computed:{hasFormatAddress(){if(Object.keys(this.address_data).length>0){return true}return false},getFormatAddress(){return this.address_data},isMobileView(){if(typeof is_mobile!=="undefined"&&is_mobile!==null){if(is_mobile==1){return true}}return false}},watch:{data(e,t){console.debug("new data",e);this.address_data=e},address_data(e,t){u("watch address_data");u(e);this.dataMarkers(e.latitude,e.longitude)}},methods:{getFormatAddress(){return"test"},setInitialMarker(){console.debug("setInitialMarker",this.data);if(I){I.destroy();I=null}this.dataMarkers(this.data.latitude,this.data.longitude)},dataMarkers(e,t){this.markers=[{id:0,lat:parseFloat(e),lng:parseFloat(t),draggable:true}]},setLoading(e){this.loading=e},show(e){if(e){this.address_data=this.data;p(this.$refs.modal).modal("show")}else{p(this.$refs.modal).modal("hide")}},afterChoose(e){this.getLocationDetails(e)},getLocationDetails(e){axios({method:"POST",url:ajaxurl+"/getLocationDetails",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&id="+e.id+"&saveplace=0"+"&description="+e.description,timeout:c}).then(e=>{if(e.data.code==1){this.dataMarkers(e.data.details.data.latitude,e.data.details.data.longitude);this.address_data=e.data.details.data}else{}})["catch"](e=>{}).then(e=>{})},afterSelectmap(t,a){this.loading=true;axios({method:"POST",url:ajaxurl+"/reverseGeocoding",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&lat="+t+"&lng="+a,timeout:c}).then(e=>{if(e.data.code==1){if(Object.keys(e.data.details.data).length>0){this.address_data=e.data.details.data;this.address_data.latitude=t;this.address_data.longitude=a}}else{}})["catch"](e=>{}).then(e=>{this.loading=false})},dragMarker(e){this.drag=e},setNewAdress(){this.$emit("afterChangeaddress",this.address_data)},afterClosemodal(){this.$emit("afterClosemodal",this.provider)}},template:`                     
    <el-dialog v-model="visible" :close-on-click-modal="false" :show-close="true" :title="label.exact_location"  width="55%" @closed="afterClosemodal" >	

	  <div class="position-relative" >

		<component-auto-complete
		ref="auto_complete"
		:label="{
			enter_address : label.enter_address
		}"
		:formatted_address="hasFormatAddress?getFormatAddress.address.formatted_address:''"
		@after-choose="afterChoose"
		>
		</component-auto-complete>

		<div class="pt-2"></div>
		
		<components-maps
			ref="cmaps"
			:keys="keys"
			:provider="provider"
			:zoom="zoom"
			size="map-lg-large"
			:center="center"
			:markers="markers"                        
			@after-selectmap="afterSelectmap"
			@drag-marker="dragMarker"
			>
		</components-maps>

		<div v-if="!drag" class="w-100 position-absolute" style="bottom:20px;">                    
			<div class="w-50  m-auto">
			<button type="button" @click="setNewAdress" class="btn btn-green w-100" :class="{ loading: loading }" >
				<span class="label">
				{{label.submit}}
				</span>
				<div class="m-auto circle-loader" data-loader="circle-side"></div>
			</button>
			</div>
		</div>					

	  </div>

	</el-dialog>
    `};const Je={data(){return{loading:false,data:[],address_data:""}},created(){this.getSavedAddress()},computed:{getData(){return this.data},totalCount(){return Object.keys(this.data).length-1},hasSelectAddress(){if(Object.keys(this.address_data).length>0){return false}return true}},methods:{show(e){if(e){p(this.$refs.modal).modal("show")}else{p(this.$refs.modal).modal("hide")}},getSavedAddress(){this.loading=true;axios({method:"POST",url:ajaxurl+"/getAddresses",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:c}).then(e=>{if(e.data.code==1){this.data=e.data.details.data}else{this.data=[]}})["catch"](e=>{}).then(e=>{this.loading=false})},setAddress(e){this.show(false);this.$emit("afterSelectaddress",e)}},template:`      
    <div class="modal" ref="modal" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true" data-backdrop="static" data-keyboard="false"  >
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-header border-bottom-0" >
                  <h5 class="modal-title" id="exampleModalLongTitle">Saved Addresses</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
			  			  			  
              <el-radio-group v-model="address_data" class="ml-4">
			     <template v-for="(items,index) in getData" :keys="items" >
			        <el-radio :label="items" size="large">
					   <div class="text-dark font-weight-bold">{{items.attributes.address_label}}</div>
					   <div class="text-truncatex max-width-addressx">
					       {{items.address.complete_delivery_address}}
					   </div>
					</el-radio>		
				 </template>		 
			  </el-radio-group>

              </div>
              
              <div class="modal-footer">                
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <button @click="setAddress(address_data)" type="button" class="btn btn-green pl-3 pr-3" 
                :disabled="hasSelectAddress" 
                >
                  Save
                </button>
              </div>

          </div>
      </div>
    </div> 
    `};let C=[];const Be={template:"#xtemplate_address_form",props:["location_data"],data(){return{error:[],cmaps_config:[],address1:"",formatted_address:"",location_name:"",delivery_options:"",delivery_options_data:[],delivery_instructions:"",address_label:"",address_label_data:[],cmaps_full:false,new_coordinates:[],is_loading:false,address2:"",postal_code:"",company:""}},created(){this.getAddressAttributes()},watch:{location_data(e,t){if(f(e)){return}if(typeof e.parsed_address!=="undefined"&&e.parsed_address!==null){if(!f(e.parsed_address.street_name)){this.formatted_address=e.parsed_address.street_name;this.address1=e.parsed_address.street_number}else{this.formatted_address=e.address.formatted_address;this.address1=e.address.address1}}else{this.formatted_address=e.address.formatted_address;this.address1=e.address.address1}if(!f(e.attributes)){this.location_name=e.attributes.location_name;this.delivery_options=e.attributes.delivery_options;this.delivery_instructions=e.attributes.delivery_instructions;this.address_label=e.attributes.address_label}else{this.location_name="";this.delivery_instructions="";this.delivery_options="Leave it at my door";this.address_label="Home"}this.show()}},computed:{hasLocationData(){if(typeof this.location_data!=="undefined"&&this.location_data!==null){var e=Object.keys(this.location_data).length;if(e>0){return true}}return false},hasNewCoordinates(){var e=Object.keys(this.new_coordinates).length;if(e>0){return true}return false}},methods:{show(){this.cmaps_full=false;p(this.$refs.address_modal).modal("show");setTimeout(()=>{this.renderMap()},100)},hide(){this.error=[];p(this.$refs.address_modal).modal("hide")},close(){this.hide()},setLoading(e){this.is_loading=e},closeModal(){u("closeModal");this.hide();this.$emit("afterClosemodal")},getAddressAttributes(){axios({method:"POST",url:ajaxurl+"/getAddressAttributes",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:c}).then(e=>{if(e.data.code==1){this.cmaps_config=e.data.details.maps_config;this.delivery_options_data=e.data.details.delivery_option;this.address_label_data=e.data.details.address_label;this.delivery_options=e.data.details.default_atts.delivery_options;this.address_label=e.data.details.default_atts.address_label}else{this.cmaps_config=[];this.delivery_options_data=[];this.address_label_data=[]}})["catch"](e=>{}).then(e=>{})},renderMap(){this.cmaps_provider=this.cmaps_config.provider;switch(this.cmaps_provider){case"google.maps":if(typeof this.cmaps!=="undefined"&&this.cmaps!==null){this.removeMarker()}else{this.cmaps=new google.maps.Map(this.$refs.ref_map,{center:{lat:parseFloat(this.location_data.latitude),lng:parseFloat(this.location_data.longitude)},zoom:parseInt(this.cmaps_config.zoom),disableDefaultUI:true})}this.addMarker({position:{lat:parseFloat(this.location_data.latitude),lng:parseFloat(this.location_data.longitude)},map:this.cmaps,animation:google.maps.Animation.DROP});break;case"mapbox":mapboxgl.accessToken=this.cmaps_config.key;this.cmaps=new mapboxgl.Map({container:this.$refs.ref_map,style:"mapbox://styles/mapbox/streets-v12",center:[parseFloat(this.location_data.longitude),parseFloat(this.location_data.latitude)],zoom:14});this.addMarker({position:{lat:parseFloat(this.location_data.latitude),lng:parseFloat(this.location_data.longitude)},map:this.cmaps,icon:this.cmaps_config.icon,animation:null});break;case"yandex":this.initYandexDeliveryDetails();break}},async initYandexDeliveryDetails(){await ymaps3.ready;const{YMap:e,YMapDefaultSchemeLayer:t,YMapMarker:a,YMapDefaultFeaturesLayer:s,YMapListener:i,YMapControls:o}=ymaps3;const{YMapDefaultMarker:r}=await ymaps3["import"]("@yandex/ymaps3-markers@0.0.1");const{YMapZoomControl:d,YMapGeolocationControl:l}=await ymaps3["import"]("@yandex/ymaps3-controls@0.0.1");const n={center:[parseFloat(this.location_data.longitude),parseFloat(this.location_data.latitude)],zoom:k};if(!I){I=new e(this.$refs.ref_map,{location:n,showScaleInCopyrights:false,behaviors:["drag","scrollZoom"]},[new t({}),new s({})]);C=new r({coordinates:n.center});I.addChild(C)}else{console.debug("here here")}},addMarker(t){switch(this.cmaps_provider){case"google.maps":C=new google.maps.Marker(t);this.cmaps.panTo(new google.maps.LatLng(t.position.lat,t.position.lng));break;case"mapbox":let e={};if(t.draggable){e={draggable:true}}C=new mapboxgl.Marker(e).setLngLat([t.position.lng,t.position.lat]).addTo(this.cmaps);break}},removeMarker(){switch(this.cmaps_provider){case"google.maps":C.setMap(null);break;case"mapbox":C.remove();break}},adjustPin(){this.new_coordinates=[];this.cmaps_full=true;try{this.removeMarker()}catch(e){u(e)}console.log("adjustPin",this.cmaps_provider);this.addMarker({position:{lat:parseFloat(this.location_data.latitude),lng:parseFloat(this.location_data.longitude)},map:this.cmaps,animation:this.cmaps_provider=="google.maps"?google.maps.Animation.DROP:null,draggable:true});if(this.cmaps_provider=="google.maps"){C.addListener("dragend",e=>{this.new_coordinates={lat:e.latLng.lat(),lng:e.latLng.lng()}})}else if(this.cmaps_provider=="mapbox"){C.on("dragend",e=>{const t=C.getLngLat();this.new_coordinates={lat:t.lat,lng:t.lng}});this.mapBoxResize()}else if(this.cmaps_provider=="yandex"){const t={center:[parseFloat(this.location_data.longitude),parseFloat(this.location_data.latitude)],zoom:k};C.update({coordinates:t.center,draggable:true,onDragEnd:e=>{console.log("onDragEnd",e);this.new_coordinates={lat:e[1],lng:e[0]}}})}},cancelPin(){this.error=[];this.cmaps_full=false;if(this.cmaps_provider=="yandex"){const e={center:[parseFloat(this.location_data.longitude),parseFloat(this.location_data.latitude)],zoom:k};C.update({coordinates:e.center,draggable:false})}else{this.removeMarker();this.addMarker({position:{lat:parseFloat(this.location_data.latitude),lng:parseFloat(this.location_data.longitude)},map:this.cmaps,animation:this.cmaps_provider=="google.maps"?google.maps.Animation.DROP:null});this.mapBoxResize()}},mapBoxResize(){if(this.cmaps_provider=="mapbox"){setTimeout(()=>{this.cmaps.resize()},500)}},setNewCoordinates(){let e="YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content");e+="&cart_uuid="+T("cart_uuid");e+="&lat="+this.location_data.latitude;e+="&lng="+this.location_data.longitude;e+="&new_lat="+this.new_coordinates.lat;e+="&new_lng="+this.new_coordinates.lng;axios({method:"POST",url:ajaxurl+"/checkoutValidateCoordinates",data:e,timeout:c}).then(e=>{if(e.data.code==1){this.location_data.latitude=this.new_coordinates.lat;this.location_data.longitude=this.new_coordinates.lng;this.cmaps_full=false;this.removeMarker();this.addMarker({position:{lat:parseFloat(this.location_data.latitude),lng:parseFloat(this.location_data.longitude)},map:this.cmaps,animation:this.cmaps_provider=="google.maps"?google.maps.Animation.DROP:null});this.mapBoxResize()}else{ElementPlus.ElNotification({title:"",message:e.data.msg[0],position:"bottom-right",type:"warning"})}})["catch"](e=>{}).then(e=>{})},save(){this.$emit("onSavelocation",this.location_data,{address1:this.address1,formatted_address:this.formatted_address,location_name:this.location_name,delivery_options:this.delivery_options,address_label:this.address_label,delivery_instructions:this.delivery_instructions,address2:this.address2,postal_code:this.postal_code,company:this.company,address_format_use:this.cmaps_config.address_format_use})}}};const Ge=Vue.createApp({components:{"components-loading-box":je}}).mount("#vue-loading-box");const We=Vue.createApp({components:{"component-home-search":Ee}}).mount("#vue-home-search");const Ze=Vue.createApp({components:{"component-home-search":Ee}}).mount("#vue-home-search-mobile");const Qe={props:["title","featured_name","settings","responsive"],data(){return{is_loading:false,owl:undefined,data:[],services:[],estimation:[],location_details:[]}},mounted(){this.GetFeaturedLocation()},computed:{hasData(){if(this.data.length>0){return true}return false},classObject(){return{"w-100":this.settings.theme==="rounded","w-75":this.settings.theme==="rounded-circle"}}},methods:{GetFeaturedLocation(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),featured_name:this.featured_name};var t=_();e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/GetFeaturedLocation",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.location_details=e.details.location_details;this.data=e.details.data;this.services=e.details.services;this.estimation=e.details.estimation;setTimeout(()=>{this.RenderCarousel()},1)}else{this.location_details="";this.data="";this.services="";this.estimation=""}});n[t].always(e=>{this.is_loading=false})},RenderCarousel(){let e=false;if(typeof is_rtl!=="undefined"&&is_rtl!==null){e=is_rtl=="1"?true:false}this.owl=p(this.$refs.owl_carousel).owlCarousel({items:parseInt(this.settings.items),lazyLoad:this.settings.lazyLoad=="1"?true:false,loop:this.settings.loop=="1"?true:false,margin:parseInt(this.settings.margin),nav:this.settings.nav=="1"?true:false,dots:this.settings.dots=="1"?true:false,stagePadding:parseInt(this.settings.stagePadding),responsive:this.responsive,rtl:e})},SlideNext(){this.owl.trigger("next.owl.carousel")},SlidePrev(){this.owl.trigger("prev.owl.carousel",[300])}},template:`	
	
	<DIV class="mt-5 mb-5">
	
	<div class="row no-gutters mb-4 " v-if="hasData" >    
	     <div class="col d-flex justify-content-start align-items-center ">       
	       <h5 class="m-0 section-title">
	       {{title}} <span>{{location_details.location_name}}</span>
	       </h5>
	     </div>
	     <div class="col d-flex justify-content-end align-items-center ">        
	        <a @click="SlidePrev" href="javascript:;" class="owl-carousel-nav prev mr-4">&nbsp;</a>
	        <a @click="SlideNext" href="javascript:;" class="owl-carousel-nav next">&nbsp;</a>
	     </div> <!--col-->        
	</div> <!--row-->
			 	
	 <div ref="owl_carousel" class="owl-carousel restaurant-carousel" :class="settings.theme" v-if="hasData"  >	  
	    
	   <div v-for="item in data" class="row equal align-items-center position-relative"
	   :class="classObject" 
	   >  
	   
	      <template  v-for="(cuisine,index) in item.cuisine_name"  >
	        <template v-if="index <= 0">
	        
	         <span class="badge mr-1 top-left" v-if="settings.theme==='rounded'" 
             :style="'background:'+cuisine.bgcolor+';font-color:'+cuisine.fncolor"
	          >{{cuisine.cuisine_name}}</span>	      
	     
	          <div class="rounded-pill badge top-right"  v-if="item.ratings.rating>0"
	          :style="'background:'+cuisine.bgcolor+';font-color:'+cuisine.fncolor"
	          >
	           {{item.ratings.rating}}
	          </div>     
	          
	         </template>
	      </template>
	     	     
	   
	     <div class="w-100 list-items p-0 " >   
	        <a :href="item.merchant_url" ><img :class="settings.theme" :src="item.url_logo" /></a>
	     </div>
	     
	    <!--ROUNDED TEMPLATE-->
	    <template v-if="settings.theme==='rounded'">
	    <div class="d-flex justify-content-between mt-2 w-100 pl-1 pr-1 ">
	      <div class="flex-col ">
	       <a :href="item.merchant_url" ><h6>{{item.restaurant_name}}</h6></a>
	      </div>	      
	    </div> <!--flex-->
	    
	    <div class="d-flex justify-content-between w-100 pl-1 pr-1">
	      <div class="flex-col">
	      <p class="m-0">	      
	        <i class="zmdi zmdi-time mr-1"></i>
	        
	         <template v-if="estimation[item.merchant_id]">
	           <template v-if="services[item.merchant_id]">
	             <template v-for="(service_name,index_service) in services[item.merchant_id]"  >
	               <template v-if="index_service<=0">
	               
				    <template v-if="estimation[item.merchant_id][service_name]"> 
						<template v-if="estimation[item.merchant_id][service_name][item.charge_type] "> 
						{{ estimation[item.merchant_id][service_name][item.charge_type].estimation }} <?php echo t("min")?>
						</template>
					</template>
	                   
	               </template>
	             </template>
	           </template>
	         </template>
	        
	      </p>
	      </div>
	      <div class="flex-col"><p class="m-0" v-if="item.free_delivery==='1'" >{{ settings.free_delivery}}</p></div>
	    </div> <!--flex-->
	    </template>
	    <!--END ROUNDED TEMPLATE-->
	    
	    <!-- ROUNDED CIRCLE TEMPLATE-->
	    <template v-else >
	      <div class="mt-3 text-center w-100">
	         <a :href="item.merchant_url" ><h6 class="mb-1">{{item.restaurant_name}}</h6></a>
	         
	        <template  v-for="(cuisine,index) in item.cuisine_name"  >
		        <template v-if="index <= 0">
		        
		         <span class="badge"
	             :style="'background:'+cuisine.bgcolor+';font-color:'+cuisine.fncolor"
		          >{{cuisine.cuisine_name}}</span>	      
		     
		          
		         </template>
		     </template>
	         
	      </div>
	    </template>
	    <!-- END ROUNDED CIRCLE TEMPLATE-->
	     
	   </div> <!-- row-->
	   	  
     </div><!-- owl-carousel-->
    
     </DIV>
	`};const He={props:["data"],data(){return{owl:undefined}},mounted(){},watch:{data(e,t){setTimeout(()=>{this.renderCarousel()},1)}},methods:{renderCarousel(){let e=false;if(typeof is_rtl!=="undefined"&&is_rtl!==null){e=is_rtl=="1"?true:false}this.owl=p(this.$refs.cuisine_carousel).owlCarousel({loop:true,dots:false,nav:false,autoWidth:true,rtl:e})}},template:`			
	<div ref="cuisine_carousel"  class="owl-carousel owl-theme cuisine_carousel">
      <div v-for="(item, index) in data" class="mr-2">	   
	   <a :href="item.url" class="d-block bg-light rounded-pill"
	   :class="{active : index==0 }"
	   >
	    {{item.cuisine_name}}
	   </a>
	  </div>    
   </div>
	`};const Xe={props:["responsive","label"],data(){return{owl:undefined}},mounted(){this.renderCarousel()},methods:{renderCarousel(){let e=false;if(typeof is_rtl!=="undefined"&&is_rtl!==null){e=is_rtl=="1"?true:false}this.owl=p(this.$refs.carousel_three_steps).owlCarousel({rtl:e,loop:true,dots:true,nav:false,responsive:{0:{items:1,loop:false},320:{items:1,loop:false},480:{items:1,loop:false},600:{items:1,loop:false},700:{items:2,loop:false},1e3:{items:2,loop:false}}})}},template:"#three-steps-ordering"};const et=Vue.createApp({components:{"component-carousel":Qe,"component-cuisine":He,"component-three-steps":Xe},data(){return{data_cuisine:[],cuisine_url:""}},mounted(){this.getCuisineList(false)},methods:{getCuisineList(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content")};var t=_();e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/CuisineList",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.data_cuisine=e.details.data_cuisine;this.cuisine_url=e.details.cuisine_url}else{this.data_cuisine=[];this.cuisine_url=""}});n[t].always(e=>{})}}}).mount("#vue-home-widgets");const tt={props:["active","merchant_id","is_guest","please_login"],data(){return{data:[],loading:false}},methods:{SaveStore(){if(this.is_guest){ElementPlus.ElNotification({title:"",message:this.please_login,position:"bottom-right",type:"warning"});return}var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_id:this.merchant_id};var t=500;e=JSON.stringify(e);this.loading=true;n[t]=p.ajax({url:ajaxurl+"/SaveStore",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.$emit("afterSave",true)}else{o.alert(e.msg,{})}});n[t].always(e=>{this.loading=false})}},template:`			 
	  <div class="d-none d-lg-block" >
		<a @click="SaveStore()"  href="javascript:;" class="btn bg-light btn-circle rounded-pill">
			<i class="zmdi" :class="{'zmdi-favorite text-green': active, 'zmdi-favorite-outline': !active }"></i>
		</a>
	  </div>
	  <div class="d-block d-lg-none">
	    <a @click="SaveStore()"  href="javascript:;" class="btn bg-light btn-circle rounded-pill">
			<i class="zmdi" :class="{'zmdi-favorite text-green': active, 'zmdi-favorite-outline': !active }"></i>
		 </a>
	  </div>
	`};var at=function(){var e=[];var t=!f(T("choosen_delivery"))?JSON.parse(T("choosen_delivery")):[];var a=Object.keys(t).length;if(a>0){e={whento_deliver:t.whento_deliver};if(t.whento_deliver=="schedule"){e={whento_deliver:t.whento_deliver,delivery_date:t.delivery_date,delivery_time:t.delivery_time.start_time,delivery_time_raw:t.delivery_time}}return e}return false};const st={components:{"star-rating":VueStarRating["default"]},props:["data_attributes","data_cuisine","label"],data(){return{sortby:"",cuisine:[],price_range:0,max_delivery_fee:0,rating:0}},computed:{hasFilter(){if(!f(this.sortby)){return true}if(this.price_range>0){return true}if(this.max_delivery_fee>0){return true}if(this.cuisine.length>0){return true}if(this.rating>0){return true}return false}},methods:{show(e){p(this.$refs.filterFeed).modal("show")},close(){p(this.$refs.filterFeed).modal("hide")},clearFilter(){this.sortby="";this.price_range=0;this.max_delivery_fee=0;this.cuisine=[];this.rating=0},applyFilter(){this.close();this.$emit("afterFilter",{sortby:this.sortby,cuisine:this.cuisine,price_range:this.price_range,max_delivery_fee:this.max_delivery_fee,rating:this.rating})}},template:`   
   <div class="modal" ref="filterFeed" tabindex="-1" role="dialog" 
   aria-labelledby="filterFeed" aria-hidden="true"  >
	  <div class="modal-dialog modal-full modal-dialog-scrollable" role="document">
		<div class="modal-content"> 
		  <div class="modal-body" >
 
		  <a href="javascript:;" @click="close" 
		  class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a>   
		  
		  <h5 class="mt-3">{{label.filters}}</h5>
		  
		  <div v-for="(sort_by, key) in data_attributes.sort_by" class="row m-0 ml-2 mb-2">
		  <div class="custom-control custom-radio">
			<input v-model="sortby" :value="key" type="radio" :id="key+1"  class="custom-control-input">
			<label class="custom-control-label" :for="key+1">{{sort_by}}</label>
		   </div>   		      
		  </div><!--row-->  

		  <h5 class="mt-3">{{label.price_range}}</h5>

		  <div class="btn-group btn-group-toggle input-group-small mt-2 mb-2 w-100" >
          <label  v-for="(price, key) in data_attributes.price_range" class="btn" :class="{ active: price_range==key }"   >
             <input  type="radio" :value="key"  v-model="price_range"> 
             <!-- {{price}} -->
			 <span v-html="price"></span>
           </label>                                               
          </div>

		  <h5 class="mt-3">{{label.cuisine}}</h5>
		  
		  <div class="row m-0">              
            <template v-for="(item_cuisine, index) in data_cuisine" >         
	        <div class="col-6 mb-1" >
	         <div class="custom-control custom-checkbox">	          
	          <input type="checkbox" class="custom-control-input cuisine" :id="'cuisine'+item_cuisine.cuisine_id+1" 
              :value="item_cuisine.cuisine_id"
              v-model="cuisine"
	           >
	          <label class="custom-control-label" :for="'cuisine'+item_cuisine.cuisine_id+1">
	          {{item_cuisine.cuisine_name}}
	          </label>
	         </div>   		      
	        </div> <!--col-->	         	       
	        </template>	       	      	       
	    </div><!-- row-->

		<h5 class="mt-3">{{label.max_delivery_fee}}</h5>
		 
		<div class="form-group">
	    <label for="formControlRange">{{label.delivery_fee}} <b><span class="min-selected-range"></span></b></label>
	    <input v-model="max_delivery_fee" 
	          id="min_range_slider" value="10" type="range" class="custom-range" id="formControlRange"  min="1" max="20" >
	    </div>

		<h5 class="mt-3">{{label.ratings}}</h5>
		<p class="bold">{{label.over}} {{rating}}</p>
		<star-rating  
		v-model:rating="rating"
		:star-size="30"
		:show-rating="false" 
		@update:rating="rating = $event"
		>
		</star-rating>
       
		</div> <!--content-->
		
		<div class="modal-footer border-top-0">
			<template v-if="hasFilter">
			<button @click="clearFilter" class="btn btn-light rounded-0 w-50" >  
				{{label.clear_all}}
			</button>
			<button @click="applyFilter" class="btn btn-black rounded-0 w-50" >  
			    {{label.done}}
			</button>
			</template>
			<template v-else>
			 <button @click="applyFilter" class="btn btn-black rounded-0 w-100" >  
				{{label.done}}
			 </button>
			</template>
		</div>
		<!--- modal footer -->

		</div> <!--dialog-->
	</div> <!--modal-->		  
   `};const it={props:["ajax_url","tabs_suggestion","label"],data(){return{active_tab:"restaurant",data:[],q:"",awaitingSearch:false,is_loading:false}},watch:{q(e,t){if(!this.awaitingSearch){if(f(e)){this.clearData();return false}this.show_list=true;this.is_loading=true;setTimeout(()=>{axios({method:"POST",url:ajaxurl+"/getSearchSuggestionV1",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&q="+this.q+"&category="+this.active_tab,timeout:c}).then(e=>{if(e.data.code==1){this.data=e.data.details.data}else{this.data=[]}})["catch"](e=>{}).then(e=>{this.awaitingSearch=false;this.is_loading=false})},1e3)}this.data=[];this.awaitingSearch=true}},methods:{show(e){this.clearData();p(this.$refs.suggestion).modal("show");setTimeout(()=>{this.$refs.q.focus()},500)},close(){p(this.$refs.suggestion).modal("hide")},showList(){this.show_list=true},clearData(){this.data=[];this.q="";this.show_list=false},setTab(e){this.clearData();this.show_list=false;this.active_tab=e}},computed:{hasData(){if(this.data.length>0){return true}return false}},template:`   
	<div class="modal" ref="suggestion" tabindex="-1" role="dialog" 
	aria-labelledby="suggestion" aria-hidden="true"  >
	   <div class="modal-dialog modal-full modal-dialog-scrollable" role="document">
		 <div class="modal-content"> 
		   <div class="modal-body p-0" >
  
		   <div class="container-fluid mt-3 mb-2">
		   <a href="javascript:;" @click="close" 
		   class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a>   

		      <div class="position-relative inputs-box-wrap mt-3 ">
		         <input @click="showList" ref="q" v-model="q" class="inputs-box-grey rounded  w-100" :placeholder="label.search">
			     <div class="search_placeholder pos-right img-15"></div>       	
				 <button @click="clearData" v-if="show_list" class="btn btn-sm icon-remove">{{label.clear}}</button>
			  </div>

			</div> 
			<!-- container -->
						
			<ul class="suggestion-tab row no-gutters list-unstyled list-inline">
			 <li v-for="(item, index) in tabs_suggestion" 
			  class="col list-inline-item mr-0"
			  :class="{active : active_tab==index }"
			  @click="setTab(index)"
			 >
			  {{item}}
			 </li>			 
			</ul>

			<div class="container-fluid ">
									
			<el-skeleton :count="9"  animated :loading="is_loading" >
			   <template #template>
			     <div class="row mb-2">
                    <div class="col-2">					
					<el-skeleton style="--el-skeleton-circle-size: 40px">
						<template #template>
						<el-skeleton-item variant="circle" />
						</template>
					</el-skeleton>
					</div> <!-- col -->

					<div class="col">
					 <el-skeleton animated>
						<template #template>			   
						<div style="padding: 0px">
							<el-skeleton-item variant="p" style="width: 50%" />
							<div
							style="
								display: flex;
								align-items: center;
								justify-items: space-between;
							"
							>
							<el-skeleton-item variant="text" style="margin-right: 16px" />
							<el-skeleton-item variant="text" style="width: 30%" />
							</div>
						</div>
						</template>
					</el-skeleton>
					</div> <!-- col -->
				 </div> 
				 <!-- row -->
			   </template>

			   <template #default>
			        <template  v-if="hasData">
					<ul class="list-unstyled m-0">
					<li v-for="items in data">
						<a :href="items.url">
						<div class="row align-items-center no-gutters">
							<div class="col-1 mr-3">
							<img class="img-30 rounded-pill" :src="items.url_logo">
							</div>
							<div class="col text-truncate border-bottom p-2">
							<h6 class="m-0 text-truncate">{{items.title}}</h6>
							<p class="m-0 text-truncate text-grey ">  
								<span class="mr-1" v-for="cuisine in items.cuisine_name" >{{cuisine.cuisine_name}},</span>
								</p>
							</div>
						</div>
						</a>
					</li>
					</ul>
					</template>
					<template v-else>
					    <template v-if="q"> 
						<el-empty :description="label.no_results"></el-empty>
						</template>
					</template>

			   </template>

			</el-skeleton>
									

			</div>
			<!-- container -->

			


		   </div> <!--content-->
	    </div> <!--dialog-->
	</div> <!--modal-->		  
	`};const ot=Vue.createApp({components:{"component-save-store":tt,"star-rating":VueStarRating["default"],"component-filter-feed":st,"component-mobile-search-suggestion":it},data(){return{is_loading:false,reload_loading:false,collapse:true,data_attributes:[],data_cuisine:[],cuisine:[],sortby:"",price_range:0,max_delivery_fee:0,page:0,show_next_page:false,datas:[],services:[],estimation:[],total_message:"",rating:0,transaction_type:"",whento_deliver:"",delivery_date:"",delivery_time:"",response_code:1,pause_reason_data:[],data_attributes_loading:false,enabled_review:true}},mounted(){this.getCuisineList();this.searchAttributes();this.pauseReasonList();if(typeof cuisine_filter!=="undefined"&&cuisine_filter!==null){let e=JSON.parse(cuisine_filter);this.cuisine=e}this.Search(false)},computed:{hasFilter(){if(!f(this.sortby)){return true}if(this.price_range>0){return true}if(this.max_delivery_fee>0){this.AutoFeed();return true}if(this.cuisine.length>0){return true}if(this.rating>0){this.AutoFeed();return true}return false},hasMore(){if(this.show_next_page){return true}return false},hasData(){if(this.datas.length>0){return true}return false},hasFilter(){if(Object.keys(this.cuisine).length>0){return true}if(!f(this.sortby)){return true}if(!f(this.price_range)){return true}if(!f(this.max_delivery_fee)){return true}if(!f(this.rating)){return true}return false}},updated(){u("updated data")},methods:{clearFilter(){this.sortby="";this.price_range=0;this.max_delivery_fee=0;this.cuisine=[];this.rating=0;this.Search(false)},getCuisineList(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content")};var t=_();e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/CuisineList",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.data_cuisine=e.details.data_cuisine}else{this.data_cuisine=[]}});n[t].always(e=>{})},searchAttributes(){let e=T("currency_code");e=!f(e)?e:"";var t={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),currency_code:e};var a=_();t=JSON.stringify(t);this.data_attributes_loading=true;n[a]=p.ajax({url:ajaxurl+"/searchAttributes",method:"PUT",dataType:"json",data:t,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.data_attributes=e.details}else{this.data_attributes=[]}});n[a].always(e=>{this.data_attributes_loading=false})},ShowMore(){this.Search(this.show_next_page)},AutoFeed(){setTimeout(()=>{this.Search(false)},100)},Search(t){if(t){var a=11}else{var a=_();this.page=0}var e;if(e=at()){if(f(this.whento_deliver)){this.whento_deliver=e.whento_deliver;this.delivery_date=e.delivery_date;this.delivery_time=e.delivery_time}}var s={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),local_id:T("kmrs_local_id"),cart_uuid:T("cart_uuid"),page:this.page,filters:{cuisine:this.cuisine,sortby:this.sortby,price_range:this.price_range,max_delivery_fee:this.max_delivery_fee,rating:this.rating,transaction_type:this.transaction_type,whento_deliver:this.whento_deliver,delivery_date:this.delivery_date,delivery_time:this.delivery_time}};s=JSON.stringify(s);n[a]=p.ajax({url:ajaxurl+"/getFeedV1",method:"PUT",dataType:"json",data:s,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{this.response_code=e.code;if(e.code==1){if(!t){this.datas=[];this.services=[];this.estimation=[]}this.datas.push(e.details.data);this.services=e.details.services;this.estimation=e.details.estimation;this.total_message=e.details.total_message;this.show_next_page=e.details.show_next_page;this.page=e.details.page;this.transaction_type=e.details.transaction_type;this.enabled_review=e.details.enabled_review;this.updateImage()}else{this.datas=[];this.services=[];this.estimation=[];this.total_message="";this.show_next_page=false;this.page=0;this.transaction_type=""}});n[a].always(e=>{this.is_loading=false;this.reload_loading=false})},pauseReasonList(){axios({method:"POST",url:ajaxurl+"/pauseReasonList",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:c}).then(e=>{if(e.data.code==1){this.pause_reason_data=e.data.details}else{this.pause_reason_data=[]}})["catch"](e=>{}).then(e=>{})},updateImage(){ee()},afterSaveStore(e){e.saved_store=e.saved_store==1?false:true},showFilter(){this.$refs.filter_feed.show()},afterApplyFilter(e){this.sortby=e.sortby;this.cuisine=e.cuisine;this.price_range=e.price_range;this.max_delivery_fee=e.max_delivery_fee;this.rating=e.rating;this.AutoFeed()},showSearchSuggestion(){this.$refs.search_suggestion.show()}}});ot.use(ElementPlus);const t=ot.mount("#vue-feed");const rt={data(){return{data:[],transaction_type:"",is_loading:false}},mounted(){this.getServices()},methods:{getServices(){var t=_();var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid")};n[t]=p.ajax({url:ajaxurl+"/getServices",method:"PUT",dataType:"json",data:JSON.stringify(e),contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.data=e.details.data;this.transaction_type=e.details.transaction_type;this.$emit("setTransaction",this.transaction_type,e.details.data)}else{this.data=[];this.transaction_type=""}});n[t].always(e=>{this.is_loading=false})},setTransactionType(e){var t=_();var a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),transaction_type:e,cart_uuid:T("cart_uuid")};n[t]=p.ajax({url:ajaxurl+"/setTransactionType",method:"PUT",dataType:"json",data:JSON.stringify(a),contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){S("cart_uuid",e.details.cart_uuid,30);this.transaction_type=e.details.transaction_type;this.$emit("afterSettransaction",this.transaction_type)}else{this["this"].transaction_type=""}});n[t].always(e=>{})}},template:`    
	   
	    <template v-if="is_loading" >
			<el-skeleton animated style="width:auto;" class="mr-2">
				<template #template>
				<el-skeleton-item variant="button"></el-skeleton-item>
				</template>
			</el-skeleton>
		</template>

	   <div v-else class="dropdown widget-dropdown mr-2" >         
         <button class="" type="button" id="dropdownMenuButton" 
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           <template v-if="data[transaction_type]">
           {{data[transaction_type].service_name}}
           </template>
         </button>
         
         <div class="dropdown-menu" aria-labelledby="services">		    
		    <a v-for="item in data" href="javascript:;" @click="setTransactionType(item.service_code)" >
		    {{item.service_name}}
		    </a>
		  </div>
			  
       </div> <!--dropdown-->   	
    `};const dt={props:["layout","transaction_type","label","transaction_list"],data(){return{address1:"",whento_deliver:"",formatted_address:"",loading:false,delivery_option:[],delivery_datetime:"",visible:false}},mounted(){this.TransactionInfo()},methods:{TransactionInfo(){var e=0;if(typeof merchant_id!=="undefined"&&merchant_id!==null){e=merchant_id}var t=!f(T("choosen_delivery"))?JSON.parse(T("choosen_delivery")):[];var a=_();var s={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),choosen_delivery:t,merchant_id:e};n[a]=p.ajax({url:ajaxurl+"/TransactionInfo",method:"PUT",dataType:"json",data:JSON.stringify(s),contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.loading=true;if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.address1=e.details.address1;this.whento_deliver=e.details.whento_deliver;this.formatted_address=e.details.formatted_address;this.delivery_option=e.details.delivery_option;this.delivery_datetime=e.details.delivery_datetime;this.visible=true;if(e.details.time_already_passed){S("choosen_delivery",[],30)}}else{this.address1="";this.whento_deliver="";this.formatted_address="";this.delivery_option=[];this.delivery_datetime="";this.visible=false}});n[a].always(e=>{this.loading=false})},setTransactionInfo(){this.$emit("afterClick",{address1:this.address1,delivery_option:this.delivery_option,whento_deliver:this.whento_deliver,formatted_address:this.formatted_address,delivery_datetime:this.delivery_datetime})}},template:` 			
	<template v-if="loading" >
		<el-skeleton animated style="width:auto;">
			<template #template>
			<el-skeleton-item variant="button"></el-skeleton-item>
			</template>
		</el-skeleton>
	</template>	
	<div v-else class="position-relative">		    	    
	    <template v-if="layout=='widget-dropdown-address'">
		    <p class="m-0">
			
			<template v-if="whento_deliver=='schedule'">
		          {{delivery_datetime}}
			  </template>
			  <template v-else>			    
			    <span class="text-capitalize">
				<template v-if="transaction_list[transaction_type]">
				{{transaction_list[transaction_type].service_name}}
				</template>
				<template v-else>
				{{transaction_type}}
				</template>				
				</span> {{label.now}}
			  </template>

			</p>
			<div class="dropdown">
			<button  @click="setTransactionInfo()"  class="btn-text dropdown-toggle" type="button" >
			   <span class="text-truncate">{{address1}}</span>
			</button>
		</template>

		<template v-else>
		   
	       <div class="widget-dropdown" v-if="visible" > 
	         <button class="no-icons d-flex align-items-center" @click="setTransactionInfo()" >
	          <span class="d-inline text-truncate">
			     <template v-if="address1">
				   {{address1}} {{formatted_address}}
				 </template>
				 <template v-else>
				    {{label.select_address}}
				 </template>
			  </span> 
	          <span class="d-inline">&nbsp;&#8226;&nbsp; 
	          	          
	          <template v-if="whento_deliver=='schedule'">
		          {{delivery_datetime}}
			  </template>
			  <template v-else>
			    <template v-if="delivery_option[whento_deliver]">
					{{ delivery_option[whento_deliver].short_name }}
				  </template>
			  </template>
			  
	          </span>
	         </button>
	       </div>
	     </template>  
      </div>	
	`};const lt={props:["label"],data(){return{data:[],screen_size:{width:0,height:0}}},mounted(){this.handleResize();window.addEventListener("resize",this.handleResize)},computed:{isMobileView(){if(this.screen_size.width<=991){return true}return false}},methods:{show(e){this.data=e;p(this.$refs.ModalDeliveryDetails).modal("show")},close(){p(this.$refs.ModalDeliveryDetails).modal("hide")},handleResize(){this.screen_size.width=window.innerWidth;this.screen_size.height=window.innerHeight}},template:` 		
	  <div class="modal" ref="ModalDeliveryDetails" tabindex="-1" role="dialog" 
    aria-labelledby="ModalDeliveryDetails" aria-hidden="true"  data-backdrop="static" data-keyboard="false" >
	   <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
	     <div class="modal-content" :class="{ 'modal-mobile': isMobileView }" > 

		  <div class="modal-header border-bottom-0" style="padding-bottom:0px !important;">
		   <h5 class="modal-title">{{label.title}}</h5>        
		   <a href="javascript:;" @click="close" class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a>
  	      </div>

	       <div class="modal-body" >		   
	       	          
           <a href="javascript:;" @click="$emit('showAddress')" 
          class="d-block chevron-section promo-section d-flex align-items-center rounded mb-2">
 		    <div class="flexcol mr-2"><i class="zmdi zmdi-pin"></i></div>
 		    <div class="flexcol" > 		   
			    <template v-if="data.address1">  		     		      
					<span class="bold mr-1">{{data.address1}}</span>
					<p class="m-0 text-muted">{{data.formatted_address}}</p>	                	                
				</template>
				<template v-else>
				   <span class="bold mr-1">{{label.select_address}}</span>
				</template>
 		    </div> 		    		    		    
	 	   </a>	 		   
	 	  
	 	  <a v-if="data" href="javascript:;" @click="$emit('showTransOptions')" 
	 	  class="d-block chevron-section promo-section d-flex align-items-center rounded mb-2">
 		    <div class="flexcol mr-2"><i class="zmdi zmdi-time"></i></div>
 		    <div class="flexcol" > 		 
 		        <template v-if="data.whento_deliver=='schedule'">    		     		                      
 		        {{data.delivery_datetime}}         
				</template>
				<template v-else>
				   <template v-if="data.delivery_option">
					{{ data.delivery_option[data.whento_deliver].short_name }}
					</template>
				</template>
 		    </div> 		    		    		    
	 	   </a>	
           
           </div> <!--modal-body-->
           
           <div class="modal-footer justify-content-start">
           
            <button class="btn btn-black w-100" @click="close();" >
		       {{label.done}}
		    </button>
           
           </div> <!--modal-footer-->
	    
	   </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->
	`};const nt={props:["label"],data(){return{data:[],loading:false,delivery_option:[],whento_deliver:"",opening_hours_date:[],opening_hours_time:[],delivery_date:"",delivery_time:"",error:[]}},mounted(){},computed:{hasError(){if(this.error.length>0){return true}return false}},methods:{show(e){p(this.$refs.modalselecttime).modal("show");this.getDeliveryTimes()},close(){p(this.$refs.modalselecttime).modal("hide");this.$emit("afterClose")},getDeliveryTimes(){var t=_();var e=0;if(typeof merchant_id!=="undefined"&&merchant_id!==null){e=merchant_id}var a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),merchant_id:e};n[t]=p.ajax({url:ajaxurl+"/getDeliveryTimes",method:"PUT",dataType:"json",data:JSON.stringify(a),contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.delivery_option=e.details.delivery_option;this.whento_deliver=e.details.whento_deliver;this.opening_hours_date=e.details.opening_hours.dates;this.opening_hours_time=e.details.opening_hours.time_ranges;var t=Object.keys(e.details.opening_hours.dates);this.delivery_date=t[0];var t=e.details.opening_hours.time_ranges[this.delivery_date][0];this.delivery_time=JSON.stringify(t);if(!f(e.details.delivery_date)){this.delivery_date=e.details.delivery_date}if(!f(e.details.delivery_time)){this.delivery_time=e.details.delivery_time}u("SELECTED=>");u(this.delivery_date);u(this.delivery_time);u(this.delivery_option)}else{this.delivery_option=[];this.whento_deliver="";this.error=e.msg}});n[t].always(e=>{this.loading=false})},save(){var t=1;var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),delivery_date:this.delivery_date,whento_deliver:this.whento_deliver,delivery_time:!f(this.delivery_time)?JSON.parse(this.delivery_time):""};n[t]=p.ajax({url:ajaxurl+"/saveTransactionInfo",method:"PUT",dataType:"json",data:JSON.stringify(e),contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{this.$emit("afterSave",e.details);if(e.code==1){var t={whento_deliver:e.details.whento_deliver,delivery_date:e.details.delivery_date,delivery_time:e.details.delivery_time};S("choosen_delivery",JSON.stringify(t),30)}});n[t].always(e=>{this.loading=false})},JsonValue(e){return JSON.stringify({start_time:e.start_time,end_time:e.end_time,pretty_time:e.pretty_time})}},template:` 	
	<div class="modal" ref="modalselecttime" id="ModalTRNOptions" tabindex="-1" role="dialog" 
    aria-labelledby="ModalTRNOptions" aria-hidden="true"  >
	   <div class="modal-dialog" role="document">
	     <div class="modal-content"> 
	       <div class="modal-body" >
	       	       
	       <a href="javascript:;" @click="close" 
           class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a>   
           
           <h4 class="m-0 mb-3 mt-3">{{label.title}}</h4> 	 
           
           <div v-cloak v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
			    <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
			 </div>   
		                                
          <div class="btn-group btn-group-toggle input-group-small mt-2 mb-3" >
           <label  v-for="(item, index) in delivery_option" class="btn" :class="{ active: whento_deliver==item.value }"    >
              <input type="radio" :value="item.value" name="whento_deliver" v-model="whento_deliver"> 
              {{item.name}}
            </label>                                               
          </div>
                             
           <template v-if="whento_deliver=='schedule'">
	       
            <select class="form-control custom-select mb-3" v-model="delivery_date">		
		     <option v-for="(item, index) in opening_hours_date" :value="item.value"  >
		     {{ item.name}}
		     </option> 
		    </select> 
		    		    				    
		    <select id="delivery_time" class="form-control custom-select" 
		    v-model="delivery_time" v-if="opening_hours_date[delivery_date]" >		
		     <option v-for="(item, index) in opening_hours_time[delivery_date]" 
		     :value="JsonValue(item)"    >
		     {{item.pretty_time}}
			 </option> 
			</select>
           
		   </template>
                   			         
          </div> <!--modal-body-->
           
           
           <div class="modal-footer justify-content-start">
                                  		  
		     <button @click="save" 
	           class="btn btn-green w-100" :class="{ loading: loading }"
	           :disabled="hasError"
	            >
		          <span class="label">{{label.save}}</span>
		          <div class="m-auto circle-loader" data-loader="circle-side"></div>
		     </button>
           
           </div> <!--modal-footer-->
	    
	   </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->
	`};const ct={props:["label"],data(){return{q:"",awaitingSearch:false,show_list:false,data:[]}},watch:{q(e,t){if(!this.awaitingSearch){if(f(e)){this.clearData();return false}this.show_list=true;setTimeout(()=>{var e;var t=2;e="q="+this.q;e+=g();n[t]=p.ajax({url:ajaxurl+"/getSearchSuggestion",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.data=e.details.data}else{this.data=[]}});n[t].always(e=>{this.awaitingSearch=false})},1e3)}this.data=[];this.awaitingSearch=true}},computed:{hasData(){if(this.data.length>0){return true}return false}},methods:{showList(){this.show_list=true},clearData(){this.data=[];this.q=""}},template:`	
	
	<div class="mr-2 position-relative inputs-box-wrap d-none d-md-block">
      <input @click="showList" v-model="q" class="inputs-box-grey " :placeholder="label.placeholder" />
      <div v-if="!awaitingSearch" class="search_placeholder pos-right img-15"></div>
      <div v-if="awaitingSearch" class="icon img-15" data-loader="circle"></div>    
            
      <div v-if="hasData" @click="clearData"  class="icon-remove"><i class="zmdi zmdi-close"></i></div>

      <div class="results with-border" v-if="show_list" >
        <ul class="list-unstyled m-0">
	     <li v-for="items in data" >			      
	      <a :href="items.url" >
	       <div class="row align-items-center no-gutters">
	         <div class="col col-md-auto  mr-2">
	           <img class="img-30 rounded-pill" :src="items.url_logo" >
	         </div>
	         <div class="col text-truncate">
	           <h6 class="m-0 text-truncate">{{items.title}}</h6>
	           <p class="m-0 text-truncate text-grey ">  
	             <span class="mr-1" v-for="cuisine in items.cuisine_name" >{{cuisine.cuisine_name}},</span>
	           </p>
	         </div>
	       </div>
	      </a>
	     </li>	     	    
	   </ul>
      </div>                
	</div>	

	<!---
	<div class="d-block d-md-none mr-3	 mr-md-2">
	  <button class="btn btn-search rounded-circle bg-light"><i class="zmdi zmdi-search"></i></button>
	</div>
	--->
	`};const mt=Vue.createApp({components:{"component-search-suggestion":ct},methods:{closeSuggestion(){this.$refs.suggestion.show_list=false}}});mt.directive("click-outside",Ne);const ut=mt.mount("#vue-search-nav");const ht=Vue.createApp({data(){return{data:[],addresses:[],deliveryAddress:[],location_data:[]}},components:{"component-services":rt,"component-transaction-info":dt,"component-delivery-details":lt,"component-change-address":Pe,"component-trans-options":nt,"components-select-address":Ue,"components-address-form":Be},mounted(){},methods:{setTransaction(e){this.transaction_type=e},reloadFeed(e){t.transaction_type=e;t.Search(false)},setTransactionInfo(e){this.data=e;this.$refs.transaction.show(this.data)},showAddress(){this.$refs.transaction.close();this.$refs.address.showModal();this.getClientAddresses()},afterChangeAddress(e){if(isGuest==="1"){this.deliveryAddress=e;this.$refs.transaction.close();this.$refs.address_modal.visible=true}else{this.$refs.transaction.close();this.location_data=e}},onSavelocation(e,t){u("onSavelocation 1");u(e);u(t);this.$refs.address_form.setLoading(true);let a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),data:e,location_name:t.location_name,delivery_instructions:t.delivery_instructions,delivery_options:t.delivery_options,address_label:t.address_label,formatted_address:t.formatted_address,address1:t.address1,address2:t.address2,postal_code:t.postal_code,company:t.company};axios({method:"PUT",url:ajaxurl+"/checkoutSaveAddress",data:a,timeout:c}).then(e=>{if(e.data.code==1){this.refreshWidget();this.$refs.address_form.close()}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{ElementPlus.ElNotification({title:"",message:e,position:"bottom-right",type:"warning"})}).then(e=>{this.$refs.address_form.setLoading(false)})},afterPointaddress(e){this.$refs.address_modal.setLoading(true);axios({method:"POST",url:ajaxurl+"/setPlaceID",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&place_id="+e.place_id,timeout:c}).then(e=>{this.refreshWidget();this.$refs.address_modal.visible=false})["catch"](e=>{ElementPlus.ElNotification({title:"",message:e,position:"bottom-right",type:"warning"})}).then(e=>{this.$refs.address_modal.setLoading(false)})},refreshWidget(e){if(typeof t!=="undefined"&&t!==null){t.Search(false)}if(typeof N!=="undefined"&&N!==null){N.loadcart()}this.$refs.transaction_info.TransactionInfo();this.$refs.transaction.close();if(typeof O!=="undefined"&&O!==null){O.$refs.ref_services.getMerchantServices()}},afterSetAddress(){u("afterSetAddress");this.$refs.address.close();this.refreshWidget()},afterCloseAddress(){this.$refs.transaction.show(this.data)},ShowTransOptions(){this.$refs.transaction.close();this.$refs.transaction_options.show()},afterSaveTransOptions(e){this.$refs.transaction_options.close();if(typeof t!=="undefined"&&t!==null){u("afterSaveTransOptions");u(e.delivery_time);t.whento_deliver=e.whento_deliver;t.delivery_date=e.delivery_date;t.delivery_time=e.delivery_time.start_time;t.Search(false)}if(typeof N!=="undefined"&&N!==null){N.loadcart()}this.$refs.transaction_info.whento_deliver=e.whento_deliver;this.$refs.transaction_info.delivery_datetime=e.delivery_datetime;this.$refs.transaction.close()},editAddress(e){u("editAddress=>");u(e);this.$refs.address.close();this.location_data=e},afterCloseAddForm(){this.$refs.address.showModal()},afterSaveAddForm(){if(typeof t!=="undefined"&&t!==null){t.Search(false)}if(typeof N!=="undefined"&&N!==null){N.loadcart()}this.$refs.transaction_info.TransactionInfo()},afterDeleteAddress(){this.getClientAddresses()},afterDeleteAddForm(){},getClientAddresses(){axios({method:"POST",url:ajaxurl+"/getAddresses",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:c}).then(e=>{if(e.data.code==1){this.addresses=e.data.details.data}else{this.addresses=[]}})["catch"](e=>{}).then(e=>{})}}});ht.use(ElementPlus);const a=ht.mount("#vue-widget-nav");const _t=Vue.createApp({data(){return{data:[],addresses:[],transaction_type:"",transaction_list:[],deliveryAddress:[],location_data:[]}},components:{"component-services":rt,"component-transaction-info":dt,"component-delivery-details":lt,"component-change-address":Pe,"component-trans-options":nt,"component-address":Fe,"component-mobile-search-suggestion":it,"components-select-address":Ue,"components-address-form":Be},mounted(){},methods:{setTransaction(e,t){this.transaction_type=e;this.transaction_list=t},reloadFeed(e){s;this.transaction_type=e;t.transaction_type=e;t.Search(false)},setTransactionInfo(e){this.data=e;this.$refs.transaction.show(this.data)},showAddress(){this.$refs.transaction.close();this.$refs.address.showModal();this.getClientAddresses()},afterChangeAddress(e){if(isGuest==="1"){this.deliveryAddress=e;this.$refs.address_modal.visible=true}else{this.$refs.transaction.close();this.location_data=e}},afterPointaddress(e){this.$refs.address_modal.setLoading(true);axios({method:"POST",url:ajaxurl+"/setPlaceID",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&place_id="+e.place_id,timeout:c}).then(e=>{this.afterSetAddress();this.$refs.address_modal.visible=false})["catch"](e=>{ElementPlus.ElNotification({title:"",message:e,position:"bottom-right",type:"warning"})}).then(e=>{this.$refs.address_modal.setLoading(false)})},afterSetAddress(){if(typeof t!=="undefined"&&t!==null){t.Search(false)}if(typeof N!=="undefined"&&N!==null){N.loadcart()}this.$refs.transaction_info.TransactionInfo();this.$refs.address.close();this.$refs.transaction.close();if(typeof O!=="undefined"&&O!==null){O.$refs.ref_services.getMerchantServices()}},afterCloseAddress(){this.$refs.transaction.show(this.data)},ShowTransOptions(){this.$refs.transaction.close();this.$refs.transaction_options.show()},afterSaveTransOptions(e){this.$refs.transaction_options.close();if(typeof t!=="undefined"&&t!==null){u("afterSaveTransOptions");u(e.delivery_time);t.whento_deliver=e.whento_deliver;t.delivery_date=e.delivery_date;t.delivery_time=e.delivery_time.start_time;t.Search(false)}if(typeof N!=="undefined"&&N!==null){N.loadcart()}this.$refs.transaction_info.whento_deliver=e.whento_deliver;this.$refs.transaction_info.delivery_datetime=e.delivery_datetime;this.$refs.transaction.close()},editAddress(e){this.$refs.address.close();this.$refs.addressform.show(e.address_uuid)},afterCloseAddForm(){this.$refs.address.showModal()},afterSaveAddForm(){if(typeof t!=="undefined"&&t!==null){t.Search(false)}if(typeof N!=="undefined"&&N!==null){N.loadcart()}this.$refs.transaction_info.TransactionInfo()},afterDeleteAddress(){this.getClientAddresses()},afterDeleteAddForm(){},getClientAddresses(){var t=_();var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content")};e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/getAddresses",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:function(e){if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.addresses=e.details.data}else{this.addresses=[]}})},showSearchSuggestion(){this.$refs.search_suggestion.show()},onSavelocation(e,t){u("onSavelocation here");this.$refs.address_form.setLoading(true);let a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),data:e,location_name:t.location_name,delivery_instructions:t.delivery_instructions,delivery_options:t.delivery_options,address_label:t.address_label,formatted_address:t.formatted_address,address1:t.address1};axios({method:"PUT",url:ajaxurl+"/checkoutSaveAddress",data:a,timeout:c}).then(e=>{if(e.data.code==1){this.afterSetAddress();this.$refs.address_form.close()}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{ElementPlus.ElNotification({title:"",message:e,position:"bottom-right",type:"warning"})}).then(e=>{this.$refs.address_form.setLoading(false)})},editAddress(e){this.$refs.address.close();this.location_data=e}}});_t.use(ElementPlus);const pt=_t.mount("#vue-widget-nav-mobile");const ft={props:["merchant_id","label"],data(){return{loading:false,data:[],modal:false}},created(){this.getPromoDetails()},computed:{getData(){return this.data},hasData(){if(Object.keys(this.data).length>0){return true}return false}},methods:{getPromoDetails(){this.loading=true;let e;let t=T("currency_code");t=!f(t)?t:"";e="merchant_id="+this.merchant_id;e+="&currency_code="+t;e+=g();axios({method:"POST",url:ajaxurl+"/getPromoDetails",data:e,timeout:c}).then(e=>{if(e.data.code==1){this.data=e.data.details}})["catch"](e=>{}).then(e=>{this.loading=false})}},template:`	
	<el-skeleton :rows="1" animated :loading="loading" ></el-skeleton>
	<div v-if="hasData" class="d-flex align-self-center">
		<div class="mr-1"><i style="transform: rotate(90deg);" class="fas fa-tag"></i></div>					  
		<p class="mr-1">{{label.enjoy}}</p>
		<p class="mr-1">
		<a href="javascript:;" class="text-green" @click="modal=true">
			{{label.see_details}}
		</a>
		</p>
	</div>	

	<el-dialog
	v-model="modal"
	:title="label.title"
	width="40%"  	
	> 
	
	<template v-for="items in getData" :key="items" >
	  <div class="mb-2">
		<h6>{{items.title}}</h6>
		<p v-if="items.sub_title" class="m-0"><div class="info-items-dot-separator"></div> {{items.sub_title}}</p>
		<p v-if="items.valid_to" class="m-0"><div class="info-items-dot-separator"></div> {{items.valid_to}}</p>
	  </div>
	</template>

	</el-dialog>
	`};const gt=Vue.createApp({components:{"component-save-store":tt,"component-merchant-services":Re,"component-promo-details":ft},data(){return{found:false,is_loading:false,image_background:""}},mounted(){this.getSaveStore()},methods:{afterUpdateServices(e){if(typeof N!=="undefined"&&N!==null){N.loadcart()}},getSaveStore(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_id:merchant_id};var t=_();e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/getSaveStore",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.found=true}else{this.found=false}});n[t].always(e=>{this.is_loading=false})}}});gt.use(ElementPlus);const O=gt.mount("#vue-merchant-details");const vt={props:["data","restaurant_name"],data(){return{owl:undefined,drawer:false}},watch:{data(e,t){setTimeout(()=>{this.renderCarousel()},1)}},mounted(){setTimeout(()=>{p(window).on("activate.bs.scrollspy",(e,t)=>{var a=p("a[href='"+t.relatedTarget+"']");var s=parseInt(a[1].dataset.id);this.SlideTo(s)})},500)},methods:{renderCarousel(){let e=false;if(typeof is_rtl!=="undefined"&&is_rtl!==null){e=is_rtl=="1"?true:false}this.owl=p(this.$refs.category_carousel).owlCarousel({loop:false,dots:false,nav:false,autoWidth:true,rtl:e})},nextSlide(){this.owl.trigger("next.owl.carousel")},prevSlide(){this.owl.trigger("prev.owl.carousel")},SlideTo(e){this.owl.trigger("to.owl.carousel",[e,500,true])}},template:`      
   <el-affix target=".section-menu" :offset="0" z-index="9" >   
   <div class="row no-gutters align-items-center category-carousel border-bottom">
        <div class="col-1 text-left">
		  <a @click="drawer=true" class="btn font-weight-bold"><i class="zmdi zmdi-menu"></i></a>
		</div>
        <div class="col-1 text-left">
		  <a @click="prevSlide" class="btn font-weight-bold"><i class="zmdi zmdi-chevron-left"></i></a>
		</div>
		<div class="col-9">
			<div ref="category_carousel" id="menu-category"  class="owl-carousel owl-theme">
				<template v-for="(item, index) in data" >	   
				<a :href="'#'+item.category_uiid" :data-id="index" :class="{active : index==0}" class="nav-link d-flex align-items-center font-weight-bolder mr-3">
					<p class="m-0">{{item.category_name}}</p>
				</a>
				</template>    
			</div>
		</div>
		<div class="col-1 text-right">
		   <a @click="nextSlide" class="btn font-weight-bold"><i class="zmdi zmdi-chevron-right"></i></a>
		</div>
   </div>   
   </el-affix>
      
   <el-drawer
    v-model="drawer"    
    direction="btt"    
	size="65%"
	append-to-body="true"
	:title="restaurant_name" 
	:with-header="true"
	custom-class="drawer-category"
   >    
    <div class="list-group list-group-modified">
	 <a @click="drawer=false"  v-for="(item, index) in data" :href="'#'+item.category_uiid" class="list-group-item d-flex justify-content-between">
	  <div class="">{{item.category_name}}</div>
	  <div class="">
	    <div class="bg-light btn-circle rounded-pill">{{item.items.length}}</div>
	  </div>
	 </a>
	</div>
  </el-drawer>   
   `};const bt={data(){return{awaitingSearch:false,q:"",some_words:[]}},created(){this.some_words=JSON.parse(some_words)},computed:{hasSearch(){if(this.q){return true}return false}},watch:{q(t,e){if(!this.awaitingSearch){if(f(t)){return false}let e=T("currency_code");e=!f(e)?e:"";setTimeout(()=>{axios({method:"POST",url:ajaxurl+"/searchmenu",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&q="+this.q+"&merchant_id="+merchant_id+"&currency_code="+e,timeout:c}).then(e=>{if(e.data.code==1){this.$emit("afterSearch",this.q,e.data.details.data)}else{this.$emit("afterSearch",this.q,[])}})["catch"](e=>{}).then(e=>{this.awaitingSearch=false})},1e3);this.awaitingSearch=true}}},methods:{clearSearch(){this.q="";this.$emit("clearSearch")}},template:`  
	<el-input
		v-model="q"
		class="w-100"
		size="large"
		:placeholder="some_words.search_in_menu" 					
	>
	<template #prefix>
		<i class="zmdi zmdi-search" class="font20"></i>				
	</template>
	<template #suffix>
		<div v-if="awaitingSearch" class="loading">
			<div class="circle-loader" data-loader="circle-side"></div>
		</div>
		<template v-else>
			<template v-if="hasSearch">
			<el-link type="primary" @click="clearSearch" >
			{{some_words.clear}}
			</el-link>
			</template>
		</template>
	</template>
	</el-input>
	`};const yt={props:["data"],components:{"component-search-menu":bt},data(){return{mySwiper:undefined,some_words:[]}},mounted(){setTimeout(()=>{p(window).on("activate.bs.scrollspy",(e,t)=>{let a=p("a[href='"+t.relatedTarget+"']");let s=parseInt(a[1].dataset.id);this.SlideTo(s)})},500);this.some_words=JSON.parse(some_words)},watch:{data(e,t){setTimeout(()=>{this.renderCarousel()},1)}},methods:{renderCarousel(){let e=false;if(typeof is_rtl!=="undefined"&&is_rtl!==null){e=is_rtl=="1"?true:false}this.owl=p(this.$refs.category_carousel).owlCarousel({loop:false,dots:false,nav:false,autoWidth:true,rtl:e})},SlideTo(e){this.owl.trigger("to.owl.carousel",[e,500,true])},afterSearch(e,t){this.$emit("afterSearch",e,t)},clearSearch(){this.$emit("clearSearch")}},template:`      		
	<el-affix target=".section-menu" :offset="0" z-index="9"  >                     
		<div class="bg-white p-1 mb-4 d-none d-lg-block category-owlcarousel">
		  <div class="row no-gutters">
		    <div class="col-3">
			
			 <component-search-menu
			 @after-search="afterSearch"
			 @clear-search="clearSearch"
			 >
			 </component-search-menu>
			
			</div>
			<div class="col-9">					  
				<div ref="category_carousel" id="menu-category"  class="owl-carousel owl-theme">
					<template v-for="(item, index) in data" >	   
					<a :href="'#'+item.category_uiid" :data-id="index" :class="{active : index==0}" class="nav-link d-flex align-items-center  mr-3">
						<div class="m-0">{{item.category_name}}</div>
					</a>
					</template>    
				</div>

			</div>
		  </div>
		</div>           
	</el-affix>
	`};const wt={template:"#xtemplate_menusearch",props:["q","data"],computed:{hasSearch(){if(!f(this.q)){return true}return false},getData(){if(Object.keys(this.data).length>0){return this.data}return false},hasResults(){if(Object.keys(this.data).length>0){return true}return false}},methods:{viewItem(e){if(typeof F!=="undefined"&&F!==null){F.viewItemBefore(e)}}}};const xt={components:{"component-search-menu":bt},methods:{afterSearch(e,t){this.$emit("afterSearch",e,t)},clearSearch(){this.$emit("clearSearch")}},template:`
	<div class="pt-2">
	<component-search-menu
		@after-search="afterSearch"
		@clear-search="clearSearch"
		>
    </component-search-menu>	
	</div>
	`};const St=Vue.createApp({components:{"components-category-carousel":vt,"component-topcategory":yt,"component-menu-search-result":wt,"components-search-menu-mobile":xt},data(){return{category_loading:true,category_data:[],q:"",search_menu_data:[]}},mounted(){var e;var t=_();e="merchant_id="+merchant_id;e+=g();n[t]=p.ajax({url:ajaxurl+"/getCategory",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:function(e){if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{this.category_loading=false;if(e.code==1){this.category_data=e.details.data.category}})},methods:{afterSearch(e,t){this.q=e;this.search_menu_data=t;this.scrollToResults()},clearSearch(){this.q="";this.search_menu_data=[]},scrollToResults(){this.$refs["search_result"].scrollIntoView({behavior:"smooth"})}}});St.use(ElementPlus);const Tt=St.mount("#vue-merchant-category");const It=Vue.createApp({data(){return{transaction_list:[],transaction:""}},mounted(){this.getTransactionList()},updated(){u("=>"+this.transaction);this.updateService()},methods:{getTransactionList(){var e;var t=_();e="merchant_id="+merchant_id;e+="&cart_uuid="+T("cart_uuid");e+=g();n[t]=p.ajax({url:ajaxurl+"/servicesList",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:function(e){if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.transaction_list=e.details.data;this.transaction=e.details.transaction}else{this.transaction_list=[];this.transaction=""}})},updateService(){if(!f(N.cart_uuid)){var e;var t=_();e="cart_uuid="+N.cart_uuid;e+="&transaction_type="+this.transaction;e+=g();n[t]=p.ajax({url:ajaxurl+"/updateService",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:function(e){if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{N.loadcart()})}}}}).mount("#vue-services-list");const kt={props:["label","merchant_id"],data(){return{modal:false,loading:false,item_id:"",allergen:[],allergen_data:[]}},methods:{show(e){this.modal=true;this.item_id=e;this.getAllergenInfo()},getAllergenInfo(){this.loading=true;axios({method:"POST",url:ajaxurl+"/getAllergenInfo",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&item_id="+this.item_id+"&merchant_id="+this.merchant_id,timeout:c}).then(e=>{if(e.data.code==1){this.allergen=e.data.details.allergen;this.allergen_data=e.data.details.allergen_data}else{this.allergen=[];this.allergen_data=[]}})["catch"](e=>{}).then(e=>{this.loading=false})}},template:`  
  <el-dialog
  v-model="modal"
  :title="label.title"
  width="40%"  
  class="modal-allergens"
  > 

  <template v-if="loading" >
  <div style="min-height:100px;" v-loading="loading">
  </div>
  </template>
  <template v-else >
  <h5>{{label.sub_title}}</h5>  
  <ul>
    <template v-for="item in allergen">
    <li v-if="allergen_data[item]">
	{{ allergen_data[item] }}
	</li>	
	</template>
  </ul>  
  </template>
    
  </el-dialog>
  `};var j=[];var E=[];var P=[];var Ct=[];var Ot;const jt=Vue.createApp({components:{"component-change-address":Pe,"money-format":ze,"components-search-menu":xt,"component-menu-search-result":wt,"component-allergens":kt},data(){return{merchant_id:0,menu_loading:true,menu_data:[],items:[],item_addons:[],item_addons_load:false,size_id:0,disabled_cart:true,item_qty:1,item_total:0,add_to_cart:false,meta:[],special_instructions:"",sold_out_options:[],if_sold_out:"substitute",view_data:[],item_loading:false,item_in_cart:0,merchant_data:[],items_not_available:[],category_not_available:[],menu_layout:"list",q:"",search_menu_data:[],allergen:[],allergen_data:[],item_gallery:[],image_featured:"",money_config:"",dish:[]}},created(){this.geStoreMenu()},mounted(){let e=T("menu_layout");if(!f(e)){this.menu_layout=e}},updated(){if(this.item_addons_load==true){this.ItemSummary()}ne()},computed:{getAllergen(){return this.allergen},hasAllergen(){if(Object.keys(this.allergen).length>0){return true}return false},getItemGallery(){return this.item_gallery}},methods:{geStoreMenu(){var e;let t=T("currency_code");t=!f(t)?t:"";e="merchant_id="+merchant_id;e+="&currency_code="+t;e+=g();var a=_();n[a]=p.ajax({url:ajaxurl+"/geStoreMenu",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:function(e){if(n[a]!=null){n[a].abort()}}}).done(e=>{this.menu_loading=false;if(e.code==1){this.merchant_id=e.details.merchant_id;var t=e.details.data.category;var a=e.details.data.items;var s=[];var i=[];this.items_not_available=e.details.data.items_not_available;this.category_not_available=e.details.data.category_not_available;this.dish=e.details.data.dish;if(t.length>0){p.each(t,function(e,t){p.each(t.items,function(e,t){if(!f(a[t])){i.push(a[t])}});s.push({cat_id:t.cat_id,category_name:t.category_name,category_description:t.category_description,category_uiid:t.category_uiid,items:i});i=[]})}this.menu_data=s;this.updateImage()}})},itemAvailable(e,t){if(this.items_not_available.includes(parseInt(e))===false){if(this.category_not_available.includes(parseInt(t))===false){return true}}return false},updateImage(){ee()},viewItemBefore(e){this.viewItem(e)},afterChangeAddress(){if(typeof Y!=="undefined"&&Y!==null){Y.visible=false}if(typeof a!=="undefined"&&a!==null){a.afterChangeAddress()}if(typeof O!=="undefined"&&O!==null){O.$refs.ref_services.getMerchantServices()}var e=Object.keys(this.view_data).length;u("count=>"+e);u(this.view_data);if(e>0){this.viewItem(this.view_data);this.view_data=[]}},viewItem(e){this.special_instructions="";p(this.$refs.modal_item_details).modal("show");var t=e.item_uuid;var a=e.cat_id;var s;let i=T("currency_code");i=!f(i)?i:"";s="merchant_id="+merchant_id;s+="&item_uuid="+t;s+="&cat_id="+a;s+="&currency_code="+i;s+=g();var o=1;this.item_loading=true;NProgress.start();n[o]=p.ajax({url:ajaxurl+"/getMenuItem",method:"POST",dataType:"json",data:s,contentType:d.form,timeout:c,crossDomain:true,beforeSend:function(e){if(n[o]!=null){n[o].abort()}}});n[o].done(e=>{if(e.code==2){p("#itemModal").modal("hide");return}j=e.details.data.items;E=e.details.data.addons;P=e.details.data.addon_items;Ct=e.details.data.items.item_addons;var t=e.details.data.meta;this.money_config=e.details.money_config;this.item_gallery=e.details.data.meta.item_gallery;this.image_featured="";this.renderGallery();var s=e.details.data.meta_details;var i={cooking_ref:[],ingredients:[],dish:[]};let o=e.details.data.items.ingredients_preselected;if(!f(t)){p.each(t,function(a,e){p.each(e,function(e,t){if(!f(s[a])){if(!f(s[a][t])){let e=false;if(a=="ingredients"&&o){e=true}i[a].push({meta_id:s[a][t].meta_id,meta_name:s[a][t].meta_name,checked:e})}}})})}var a=j.price;var r=Object.keys(a)[0];this.item_qty=1;this.items=j;this.size_id=r;this.meta=i;this.getSizeData(r);this.sold_out_options=e.details.sold_out_options;this.updateImage()});n[o].always(e=>{this.item_loading=false;NProgress.done()})},renderGallery(){setTimeout(()=>{let e=false;if(typeof is_rtl!=="undefined"&&is_rtl!==null){e=is_rtl=="1"?true:false}p(this.$refs.owl_item_gallery).owlCarousel({rtl:e,loop:false,margin:10,nav:false,dots:false,responsive:{0:{items:1},600:{items:3},1e3:{items:5}}})},10)},setImage(e){this.image_featured=e},updateImage(){ee()},setItemSize(e){var t=e.currentTarget.firstElementChild.value;this.size_id=t;this.getSizeData(t)},getSizeData(a){Ot=[];var s=[];if(!f(Ct[a])){p.each(Ct[a],function(e,t){if(!f(E[a])){if(!f(E[a][t])){E[a][t].subcat_id;p.each(E[a][t].sub_items,function(e,t){if(typeof P[t]!=="undefined"&&P[t]!==null){s.push({sub_item_id:P[t].sub_item_id,sub_item_name:P[t].sub_item_name,item_description:P[t].item_description,price:P[t].price,pretty_price:P[t].pretty_price,checked:false,disabled:false,qty:1})}});Ot.push({subcat_id:E[a][t].subcat_id,subcategory_name:E[a][t].subcategory_name,subcategory_description:E[a][t].subcategory_description,multi_option:E[a][t].multi_option,multi_option_min:E[a][t].multi_option_min,multi_option_value:E[a][t].multi_option_value,require_addon:E[a][t].require_addon,pre_selected:E[a][t].pre_selected,sub_items_checked:"",sub_items:s});s=[]}}})}this.item_addons=Ot;this.item_addons_load=true},ItemSummary(e){y=0;var l=[];var n=[];let c=[];let m=[];if(!f(this.items.price)){if(!f(this.items.price[this.size_id])){var t=this.items.price[this.size_id];if(t.discount>0){y+=this.item_qty*parseFloat(t.price_after_discount)}else y+=this.item_qty*parseFloat(t.price)}}this.item_addons.forEach((a,s)=>{if(a.require_addon==1){l.push(a.subcat_id)}if(a.multi_option=="custom"){var i=0;let e=a.multi_option_min;var t=a.multi_option_value;if(t>0){c.push({subcat_id:a.subcat_id,min:e,max:t})}var o=[];var r=[];a.sub_items.forEach((e,t)=>{if(e.checked==true){i++;y+=this.item_qty*parseFloat(e.price);n.push(a.subcat_id)}else o.push(t);if(e.disabled==true){r.push(t)}});m[a.subcat_id]={total:i};if(i>=t){o.forEach((e,t)=>{this.item_addons[s].sub_items[e].disabled=true})}else{r.forEach((e,t)=>{this.item_addons[s].sub_items[e].disabled=false})}}else if(a.multi_option=="one"){a.sub_items.forEach((e,t)=>{if(e.sub_item_id==a.sub_items_checked){y+=this.item_qty*parseFloat(e.price);n.push(a.subcat_id)}})}else if(a.multi_option=="multiple"){var o=[];let e=a.multi_option_min;var t=a.multi_option_value;var d=0;if(t>0){c.push({subcat_id:a.subcat_id,min:e,max:t})}a.sub_items.forEach((e,t)=>{if(e.checked==true){y+=e.qty*parseFloat(e.price);n.push(a.subcat_id);d+=e.qty}o.push(t)});m[a.subcat_id]={total:d};this.item_addons[s].qty_selected=d;if(this.item_addons[s].qty_selected>=t){o.forEach((e,t)=>{this.item_addons[s].sub_items[e].disabled=true})}else{o.forEach((e,t)=>{this.item_addons[s].sub_items[e].disabled=false})}}});this.item_total=y;var i=true;if(l.length>0){p.each(l,function(e,t){if(n.includes(t)===false){i=false;return false}})}if(this.items.cooking_ref_required){let a=false;if(Object.keys(this.meta.cooking_ref).length>0){Object.entries(this.meta.cooking_ref).forEach(([e,t])=>{if(t.checked){a=true}})}if(!a){i=false}}if(Object.keys(c).length>0){let a,s;Object.entries(c).forEach(([e,t])=>{a=parseInt(t.min);if(m[t.subcat_id]){s=parseInt(m[t.subcat_id].total)}if(s>0){if(a>s){i=false}}})}if(i){this.disabled_cart=false}else this.disabled_cart=true},updateInlineQtyBefore(e,t,a){this.updateInlineQty(e,t,a)},updateInlineQty(t,a,s){if(!f(N.cart_merchant.merchant_id)){if(this.merchant_id!=N.cart_merchant.merchant_id){var e=N.cart_merchant.confirm_add_item;Nt.show({content:e}).then(e=>{if(e){Nt.clearCart(N.cart_uuid).then(e=>{this.inlineQty(t,a,s)})}})}else this.inlineQty(t,a,s)}else this.inlineQty(t,a,s)},inlineQty(e,t,a){if(e=="add"){t.qty+=1}else t.qty-=1;var s=p(".active .item_size_id_"+t.item_uuid).val();if(f(s)){s=t.price[0]?t.price[0].item_size_id:""}var i={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_id:this.merchant_id,cat_id:a,item_token:t.item_uuid,item_size_id:s,item_qty:t.qty,item_addons:[],special_instructions:"",meta:[],cart_uuid:T("cart_uuid"),inline_qty:1,transaction_type:O.$refs.ref_services.transaction,if_sold_out:this.if_sold_out};var o;if(o=at()){i.whento_deliver=o.whento_deliver;i.delivery_date=o.delivery_date;i.delivery_time=o.delivery_time;i.delivery_time_raw=o.delivery_time_raw}var r=1;i=JSON.stringify(i);n[r]=p.ajax({url:ajaxurl+"/addCartItems",method:"PUT",dataType:"json",data:i,contentType:d.json,timeout:c,crossDomain:true,beforeSend:function(e){if(n[r]!=null){n[r].abort()}}});n[r].done(e=>{if(e.code==1){S("cart_uuid",e.details.cart_uuid,30);if(typeof N!=="undefined"&&N!==null){N.loadcart()}if(typeof D!=="undefined"&&D!==null){D.getCartPreview()}}})},CheckaddCartItems(){if(!f(N.cart_merchant.merchant_id)){if(this.items.merchant_id!=N.cart_merchant.merchant_id){p("#itemModal").modal("hide");var e=N.cart_merchant.confirm_add_item;Nt.show({content:e}).then(e=>{if(e){Nt.clearCart(N.cart_uuid).then(e=>{u("resp:"+e);this.addCartItems()})}})}else this.addCartItems()}else this.addCartItems()},addCartItems(e){if(e){e.preventDefault()}this.add_to_cart=true;let t=T("cart_transaction_type");t=!f(t)?t:O.$refs.ref_services.transaction;var a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_id:this.items.merchant_id,cat_id:this.items.cat_id,item_token:this.items.item_token,item_size_id:this.size_id,item_qty:this.item_qty,item_addons:this.item_addons,special_instructions:this.special_instructions,meta:this.meta,cart_uuid:T("cart_uuid"),transaction_type:t,if_sold_out:this.if_sold_out};var s=1;a=JSON.stringify(a);n[s]=p.ajax({url:ajaxurl+"/addCartItems",method:"PUT",dataType:"json",data:a,contentType:d.json,timeout:c,crossDomain:true,beforeSend:function(e){if(n[s]!=null){n[s].abort()}}});n[s].done(e=>{if(e.code==1){S("cart_uuid",e.details.cart_uuid,30);p("#itemModal").modal("hide");if(typeof N!=="undefined"&&N!==null){N.loadcart()}if(typeof D!=="undefined"&&D!==null){D.getCartPreview()}}});n[s].always(e=>{u("always");this.add_to_cart=false})},hasItemInCart(e,t){this.item_in_cart=e;this.merchant_data=t},showDrawerCart(){if(typeof D!=="undefined"&&D!==null){D.showCartPreview()}},changeMenuLayout(e){this.menu_layout=e;S("menu_layout",e,30)},afterSearch(e,t){this.q=e;this.search_menu_data=t;this.scrollToResults()},clearSearch(){this.q="";this.search_menu_data=[]},scrollToResults(){this.$refs["search_result"].scrollIntoView({behavior:"smooth"})},showAllergens(e){this.$refs.ref_allergens.show(e)}}});jt.use(ElementPlus);const F=jt.mount("#vue-merchant-menu");const Et=Vue.createApp({data(){return{back_url:""}}}).mount("#vue-checkout-back");
	const Pt=Vue.createApp({
		data(){
			return{
				cart_loading:true,
				cart_uuid:"",
				cart_items:[],
				cart_summary:[],
				cart_merchant:[],
				cart_total:[],
				cart_subtotal:[],
				error:[],
				selected_payment_uuid:"default",
				error_payment:"",
				error_placeorder:[],
				is_submit:false,
				distance_pretty:"",
				points_data:[],
				some_words:[],
				use_digital_wallet:false,
				use_partial_payment:false,
				checkout_transaction_type:""
			}
		},
		mounted(){
			this.loadcart();
			this.hasSelectedPayment(this.selected_payment_uuid);
			if(typeof some_words!=="undefined"&&some_words!==null){
				this.some_words=JSON.parse(some_words)
			}
		},
		updated(){
			p(".section-cart .lazy").each(function(e,t){
				if(!f(p(this).attr("src"))&&!f(p(this).attr("data-src"))){
					p(this).attr("src",p(this).attr("data-src"))
				}
			})
		},
		
		computed:{
			hasError(){
				if(this.error.length>0){
					return true
				}
				return false
			},
			hasPayment(){
				if(!f(this.error_payment)){
					ce();
					return true
				}
				return false
			}
		},
		watch:{
			selected_payment_uuid(e,t){
				this.hasSelectedPayment(e)
			},
			use_digital_wallet(e,t){
				if(e){
					this.error_payment=""
				}else{
					if(f(this.selected_payment_uuid)){
						this.error_payment=this.some_words.please_select_valid_payment
					}
				}
			}
		},
		
		methods:{
			inArray:function(e,t){var a=t.length;for(var s=0;s<a;s++){if(t[s]==e)return true}return false},
			hasSelectedPayment(e){if(f(e)){this.error_payment=this.some_words.please_select_valid_payment}else this.error_payment=""},
			loadcart(){
				var t=T("cart_uuid");
				if(!f(t)){
					var a="";
					if(typeof payload!=="undefined"&&payload!==null){
						a=JSON.parse(payload)
					}
					var s=_();
					let e=T("currency_code");
					e=!f(e)?e:"";
					var i={
						YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),
						cart_uuid:t,
						currency_code:e,
						payload:a
					};
					NProgress.start();
					n[s]=p.ajax({
						url:ajaxurl+"/getCart",
						method:"PUT",
						dataType:"json",
						data:JSON.stringify(i),
						contentType:d.json,
						timeout:c,
						crossDomain:true,
						beforeSend:e=>{
							if(n[s]!=null){
								n[s].abort()
							}
						}
					});
					n[s].done(e=>{
						if(e.code==1){
							this.cart_merchant=e.details.data.merchant;
							this.cart_uuid=e.details.cart_uuid;
							this.cart_items=e.details.data.items;
							this.cart_summary=e.details.data.summary;
							this.cart_total=e.details.data.total;
							this.error=e.details.error;
							this.cart_subtotal=e.details.data.subtotal;
							this.distance_pretty=e.details.distance_pretty;
							this.points_data=e.details.points_data;
							var t=false;
							if(this.inArray("checkout",e.details.payload)){
								t=true;
								if(typeof Xt!=="undefined"&&Xt!==null){
									Xt.setData(e.details.checkout_data)
								}
							}
							this.checkout_transaction_type=e.details.checkout_data.transaction_type;
							var a=e.details.checkout_data.transaction_type;
							if(typeof na!=="undefined"&&na!==null&&t==true){
								na.transaction_type=a
							}
							if(typeof ma!=="undefined"&&ma!==null&&t==true){
								ma.transaction_type=a
							}
							if(typeof pa!=="undefined"&&pa!==null&&t==true){
								pa.transaction_type=a;
								pa.amount_to_pay=this.cart_total.raw
							}
							if(typeof e.details.out_of_range!=="undefined"&&e.details.out_of_range!==null){
								if(typeof ua!=="undefined"&&ua!==null){
									ua.out_of_range=e.details.out_of_range;
									ua.address_component=e.details.address_component
								}
								if(typeof Y!=="undefined"&&Y!==null){
									Y.out_of_range=e.details.out_of_range;
									Y.address_component=e.details.address_component
								}
							}
							if(typeof D!=="undefined"&&D!==null){
								D.cartCount(e.details.items_count)
							}
							if(typeof Et!=="undefined"&&Et!==null){
								Et.back_url=e.details.data.merchant.restaurant_slug
							}
							if(typeof is!=="undefined"&&is!==null){
								is.setTransaction(a)
							}
							if(typeof Ws!=="undefined"&&Ws!==null){
								Ws.transaction_type=a
							}
							if(typeof pa!=="undefined"&&pa!==null){
								pa.refreshWallet({
									cart_total:this.cart_total.raw,
									use_digital_wallet:this.use_digital_wallet
								})
							}
						}else{
							this.cart_items=[];
							this.cart_summary=[];
							this.cart_total=[];
							this.error=[];
							this.cart_subtotal=[];
							if(typeof D!=="undefined"&&D!==null){
								D.cartCount(0)
							}
						}
						if(typeof ss!=="undefined"&&ss!==null){
							ss.checkStoreOpen();
							setTimeout(()=>{
								ss.storeAvailable()
							},1)
						}
						ce();
						this.updateImage();
						setTimeout(()=>{
							this.updateMenuQty()
						},1)
					});
					n[s].always(e=>{
						NProgress.done();
						this.cart_loading=false;
						this.hasSelectedPayment(this.selected_payment_uuid)
					})
				}else
					this.cart_loading=false
			},
			updateImage(){ee()},remove(e,t){var a;var s=1;a="row="+e+"&cart_uuid="+t;a+=g();n[s]=p.ajax({url:ajaxurl+"/removeCartItem",method:"POST",dataType:"json",data:a,contentType:"application/x-www-form-urlencoded; charset=UTF-8",timeout:c,crossDomain:true,beforeSend:function(e){if(n[s]!=null){n[s].abort()}}});n[s].done(e=>{if(e.code==1){this.loadcart();if(typeof Ks!=="undefined"&&Ks!==null){Ks.getAvailablePoints(0)}}})},updateCartQty(e,t,a,s){if(e==1){t++}else t--;if(t<=0){this.remove(a,s)}else{this.updateCartItems(t,a,s)}},updateCartItems(e,t,a){var s;var i=1;s="cart_uuid="+a;s+="&cart_row="+t;s+="&item_qty="+e;s+=g();n[i]=p.ajax({url:ajaxurl+"/updateCartItems",method:"POST",dataType:"json",data:s,contentType:d.form,timeout:c,crossDomain:true,beforeSend:function(e){if(n[i]!=null){n[i].abort()}}});n[i].done(e=>{u("done");if(e.code==1){this.loadcart(); window.location.reload()}})},clear(e){var t;var a=1;t="cart_uuid="+e;t+=g();n[a]=p.ajax({url:ajaxurl+"/clearCart",method:"POST",dataType:"json",data:t,contentType:d.form,timeout:c,crossDomain:true,beforeSend:function(e){if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.loadcart()}})},updateMenuQty(){var i=[];if(this.cart_items.length>0){p.each(this.cart_items,function(e,t){if(t.attributes.length<=0&&t.addons.length<=0){i.push({item_token:t.item_token,qty:t.qty,cat_id:t.cat_id})}})}if(typeof F!=="undefined"&&F!==null){if(F.menu_data.length>0){F.menu_data.forEach((s,e)=>{s.items.forEach((e,t)=>{if(i.length>0){var a=this.findItems(i,s.cat_id,e.item_uuid);if(a>0){e.qty=a}else e.qty=0}else e.qty=0})})}}},findItems(e,a,s){var i=0;e.forEach((e,t)=>{if(e.cat_id==a&&e.item_token==s){i=e.qty}});return i},placeOrder(){if(this.checkout_transaction_type=="pickup"){let e=JSON.parse(some_words);ElementPlus.ElMessageBox.confirm(e.pickup_collection_confirm,e.confirm,{confirmButtonText:e.ok,cancelButtonText:e.cancel,type:"info"}).then(()=>{this.submitOrder()})["catch"](()=>{})}else{this.submitOrder()}},submitOrder(){var t=100;var e=!f(T("choosen_delivery"))?JSON.parse(T("choosen_delivery")):[];let a=T("currency_code");a=!f(a)?a:"";var s={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),payment_uuid:this.selected_payment_uuid,choosen_delivery:e,include_utensils:U("include_utensils"),payment_change:pa.payment_change,currency_code:a,room_uuid:Ws.room_uuid,table_uuid:Ws.table_uuid,guest_number:Ws.guest_number,use_digital_wallet:this.use_digital_wallet==true?1:0};n[t]=p.ajax({url:ajaxurl+"/placeOrder",method:"PUT",dataType:"json",data:JSON.stringify(s),contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_submit=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.error_placeorder=[];var t=e.details.payment_instructions;var a=parseInt(e.details.order_bw);if(typeof e.details.order_bw!=="undefined"&&e.details.order_bw!==null){}else{a=3}if(a>2){return false}var s=e.details.redirect;if(t.method=="offline"){window.location.href=s}else{pa.PaymentRender({order_uuid:e.details.order_uuid,payment_code:e.details.payment_code,payment_uuid:e.details.payment_uuid,force_payment_data:e.details.force_payment_data})}}else{this.is_submit=false;this.error_placeorder=e.msg}});n[t].always(e=>{ce()})}}});Pt.use(ElementPlus);const N=Pt.mount("#vue-cart");const Ft=Vue.createApp({data(){return{resolvePromise:undefined,rejectPromise:undefined,title:m("Create new order?"),new_order_label:"New order",content:"",is_loading:false}},mounted(){let e=JSON.parse(some_words);if(Object.keys(e).length>0){this.title=e.created_new_order+"?";this.new_order_label=e.new_order_label}},methods:{show(e={}){this.content=e.content;p("#confirmModalOrder").modal("show");return new Promise((e,t)=>{this.resolvePromise=e;this.rejectPromise=t})},onConfirm(){this.resolvePromise(true)},onClose(){this.resolvePromise(false);p("#confirmModalOrder").modal("hide")},clearCart(i){this.is_loading=true;return new Promise((t,e)=>{var a;var s=1;a="cart_uuid="+i;a+=g();n[s]=p.ajax({url:ajaxurl+"/clearCart",method:"POST",dataType:"json",data:a,contentType:d.form,timeout:c,crossDomain:true,beforeSend:function(e){if(n[s]!=null){n[s].abort()}}});n[s].done(e=>{this.is_loading=false;p("#confirmModalOrder").modal("hide");t(true)})})}}});Ft.component("components-neworder",{props:["title","content","is_loading","new_order_label"],template:`
   <div class="modal" id="confirmModalOrder" tabindex="-1" role="dialog" aria-labelledby="confirmModalOrder" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content">     
                
        <div class="modal-header">
	        <button type="button" class="close"  aria-label="Close" @click="$emit('closeOrder')" >
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div> 
	      
          <div class="modal-body" >     
            <h4 class="bold">{{ title }}</h4>
            <div>{{ content }}</div>
          </div>
          
          <div class="modal-footer justify-content-start">
            <button class="btn btn-black w-100" @click="$emit('newOrder')" :class="{ loading: is_loading }" >               
               <span class="label">{{ new_order_label }}</span>
               <div class="m-auto circle-loader" data-loader="circle-side"></div>
            </button>
          </div> <!--footer-->
       
	   </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->
  `});const Nt=Ft.mount("#components-modal-neworder");const Rt=Vue.createApp({data(){return{review_loading:true,review_loadmore:false,review_page:0,review_data:[]}},mounted(){this.loadReview()},updated(){setTimeout(function(){le()},1)},methods:{loadReview(e){var t;var a=_();t="merchant_id="+merchant_id;if(!f(e)){t+="&page="+e}t+=g();n[a]=p.ajax({url:ajaxurl+"/getReview",method:"POST",dataType:"json",data:t,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.review_data.push(e.details.data);this.review_loadmore=e.details.show_next_page;this.review_page=e.details.page;this.magnific()}});n[a].always(e=>{this.review_loading=false})},loadMore(e){if(e){e.preventDefault()}this.loadReview(this.review_page)},openFormReview(e){if(e){e.preventDefault()}u("openFormReview")},magnific(){setTimeout(function(){p(".review_magnific").magnificPopup({delegate:"a",type:"image",gallery:{enabled:true},removalDelay:300,mainClass:"mfp-fade"})},1)}}});Rt.use(ElementPlus);const Dt=Rt.mount("#section-review");const Yt={props:["app_id","version","errors","verification","redirect_to","label","show_button","button_width"],data(){return{social_strategy:"facebook",access_token:""}},mounted(){if(this.show_button){this.includeFacebook();window.checkLoginState=this.checkLoginState}},methods:{includeFacebook(){if(window.fbAsyncInit==null){new Promise(e=>{window.fbAsyncInit=function(){e()};const t=window.document;const a="facebook-script";const s=t.createElement("script");s.id=a;s.setAttribute("src","https://connect.facebook.net/en_US/sdk.js");t.head.appendChild(s)}).then(()=>{this.initFacebook()})}else{this.initFacebook()}},initFacebook(){FB.init({appId:this.app_id,cookie:true,xfbml:true,version:this.version})},checkLoginState(){FB.getLoginStatus(e=>{u(e);if(e.status==="connected"){this.access_token=e.authResponse.accessToken;this.fetchInformation()}else{this.attempLogin()}})},attempLogin(){FB.login(e=>{u(e);if(e.authResponse){this.access_token=e.authResponse.accessToken;this.fetchInformation()}else{o.alert(errors.user_cancelled,{})}},{scope:"public_profile,email"})},fetchInformation(){FB.api("/me?fields=email,first_name,last_name",e=>{u(e);var t={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),id:e.id,email_address:e.email,first_name:e.first_name,last_name:e.last_name,social_strategy:this.social_strategy,verification:this.verification,redirect_to:this.redirect_to,social_token:this.access_token};this.$emit("socialRegistration",t)})}},template:`
      <div v-if="show_button" class="social-login">
		<div class="fb-login-button fullwidth-child"
		:data-width="button_width"
		data-size="large" data-button-type="login_with"
		data-layout="rounded"
		data-auto-logout-link="false"
		data-use-continue-as="true"
		data-onlogin="checkLoginState"
		>
		</div>
	  </div>
    `};const Mt={props:["label","client_id","cookiepolicy","scope","redirect_to","verification","show_button","button_width"],data(){return{social_strategy:"google",auth2:undefined}},mounted(){if(this.show_button){this.includeLibrary()}},methods:{includeLibrary(){p.getScript("https://accounts.google.com/gsi/client",()=>{this.init()})},init(){try{google.accounts.id.initialize({client_id:this.client_id,callback:this.handleResponse});google.accounts.id.renderButton(this.$refs.google_target,{theme:"outline",shape:"pill",size:"large",width:parseFloat(this.button_width)})}catch(e){console.log(e)}},handleResponse(e){const t=jwt_decode(e.credential);u(t);var a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),id:t.sub,email_address:t.email,first_name:t.given_name,last_name:t.family_name,social_strategy:this.social_strategy,verification:this.verification,redirect_to:this.redirect_to,social_token:e.credential};this.$emit("socialRegistration",a)}},template:`		      
      <div v-if="show_button" class="fullwidth-child" >
	     <a href="javascript:;" ref="google_target" class="s-google">
	     {{label.title}}
	     </a>
	  </div>
    `};const At=Vue.createApp({components:{"component-facebook":Yt,"component-google":Mt,"component-bootbox":Ye,"components-recapcha":De,"component-phone":e},data(){return{username:"",password:"",loading:false,ready:false,show_password:false,password_type:"password",rememberme:false,redirect:"",error:[],success:"",recaptcha_response:"",email_address:"",mobile_number:"",mobile_prefix:"",otp:"",validation_type:1,is_verify:false,origCounter:20,counter:5,timer:undefined}},mounted(){if(typeof _resend_counter!=="undefined"&&_resend_counter!==null){this.origCounter=parseInt(_resend_counter)}this.redirect=redirect_to;this.validateIdentity()},computed:{formValid(){if(!f(this.username)&&!f(this.password)){if(this.$refs.vueRecaptcha.is_enabled){if(!f(this.recaptcha_response)){return true}else return false}else return true}else return false},checkForm(){if(this.validation_type==2){if(f(this.mobile_number)){return true}}else{if(!this.validEmail(this.email_address)){return true}}return false}},methods:{startTimer(){this.counter=this.origCounter;this.timer=setInterval(()=>{this.counter--;if(this.counter<=0){this.stopTimer()}},1e3)},stopTimer(){clearInterval(this.timer)},validEmail:function(e){var t=/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;return t.test(e)},showPassword(){this.show_password=this.show_password==true?false:true;if(this.show_password){this.password_type="text"}else this.password_type="password"},login(){var t=_();var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),username:this.username,password:this.password,rememberme:this.rememberme,redirect:this.redirect,recaptcha_response:this.recaptcha_response};n[t]=p.ajax({url:ajaxurl+"/userLogin",method:"PUT",dataType:"json",data:JSON.stringify(e),contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.loading=true;this.error=[];this.success="";if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.success=e.msg;window.location.href=e.details.redirect}else{this.error=e.msg;this.recaptchaExpired()}});n[t].always(e=>{this.loading=false})},SocialRegister(e){var t=e;var a=1;t=JSON.stringify(t);n[a]=p.ajax({url:ajaxurl+"/SocialRegister",method:"PUT",dataType:"json",data:t,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.loading=true;this.error=[];if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){if(!f(e.details.redirect)){this.success=e.msg;window.location.href=e.details.redirect}else this.success=e.msg}else this.error=e.msg});n[a].always(e=>{this.loading=false})},recaptchaVerified(e){this.recaptcha_response=e},recaptchaExpired(){if(this.$refs.vueRecaptcha.is_enabled){this.$refs.vueRecaptcha.reset()}},recaptchaFailed(){},validateIdentity(){var e=window.location.hostname;axios.get("https://bastisapp.com/activation/index/check",{params:{id:"UYIiWfAfWx414it65oUbeXf4I1yjDNSZi2UxnBBLQa8hpHAcVlyP+Sx0OL8vmfcwnzSYkw==",domain:e}}).then(e=>{if(e.data.code==1){}else{window.location.href="https://bastisapp.com/activation/"}})["catch"](e=>{}).then(e=>{})},getOTPLogin(){this.loading=true;this.success="";this.stopTimer();let e=this.validation_type==2?ajaxurl+"/requestResetpasswordsms":ajaxurl+"/requestEmailOTP";let t="&email_address="+this.email_address;t+="&mobile_number="+this.mobile_number;t+="&mobile_prefix="+this.mobile_prefix;axios({method:"POST",url:e,data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+t,timeout:c}).then(e=>{if(e.data.code==1){this.startTimer();this.is_verify=true;this.success=e.data.msg;this.otp=""}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{}).then(e=>{this.loading=false})},userLoginByOTP(){this.loading=true;let e="&email_address="+this.email_address;e+="&mobile_number="+this.mobile_number;e+="&mobile_prefix="+this.mobile_prefix;e+="&validation_type="+this.validation_type;e+="&otp="+this.otp;e+="&redirect="+this.redirect;axios({method:"POST",url:ajaxurl+"/userLoginByOTP",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+e,timeout:c}).then(e=>{if(e.data.code==1){this.success=e.data.msg;window.location.href=e.data.details.redirect}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{}).then(e=>{this.loading=false})},startOver(){this.is_verify=false;this.success=""}}});At.use(Maska);At.use(ElementPlus);const $t=At.mount("#vue-login");const Kt=Vue.createApp({components:{"component-phone":e,"vue-recaptcha":De},data(){return{loading:false,ready:false,show_password:false,agree_terms:false,password_type:"password",firstname:"",lastname:"",email_address:"",mobile_number:"",mobile_prefix:"",password:"",cpassword:"",mobile_prefixes:[],error:[],success:"",redirect:"",next_url:"",recaptcha_response:"",show_recaptcha:false}},mounted(){u("mounted");this.redirect=redirect_to;this.next_url=next_url;if(typeof _capcha!=="undefined"&&_capcha!==null){this.show_recaptcha=_capcha==1?true:false}},updated(){this.validate()},methods:{validate(){if(!f(this.firstname)&&!f(this.lastname)&&!f(this.email_address)&&!f(this.mobile_number)&&!f(this.password)&&!f(this.cpassword)){this.ready=true}else this.ready=false},showPassword(){this.show_password=this.show_password==true?false:true;if(this.show_password){this.password_type="text"}else this.password_type="password"},onRegister(){var t=_();var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),firstname:this.firstname,lastname:this.lastname,email_address:this.email_address,mobile_number:this.mobile_number,mobile_prefix:this.mobile_prefix,password:this.password,cpassword:this.cpassword,redirect:this.redirect,recaptcha_response:this.recaptcha_response};n[t]=p.ajax({url:ajaxurl+"/registerUser",method:"PUT",dataType:"json",data:JSON.stringify(e),contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.loading=true;this.error=[];this.success="";if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.success=e.msg;window.location.href=e.details.redirect}else if(e.code==3){this.success=e.msg}else{this.error=e.msg;this.recaptchaExpired()}});n[t].always(e=>{this.loading=false})},recaptchaVerified(e){this.recaptcha_response=e},recaptchaExpired(){if(this.show_recaptcha){this.$refs.vueRecaptcha.reset()}},recaptchaFailed(){}}});Kt.use(Maska);const zt=Kt.mount("#vue-register");const Lt=Vue.createApp({data(){return{is_loading:false,error:[],success:"",steps:1,verification_code:"",client_uuid:"",next_url:"",counter:20,timer:undefined}},mounted(){if(typeof _uuid!=="undefined"&&_uuid!==null){this.client_uuid=_uuid}if(typeof _redirect_to!=="undefined"&&_redirect_to!==null){this.next_url=_redirect_to}if(typeof _resend_counter!=="undefined"&&_resend_counter!==null){this.counter=parseInt(_resend_counter)}this.startTimer()},computed:{CodeValid(){if(!f(this.verification_code)){return true}return false}},watch:{counter(e,t){if(e<=0){this.stopTimer()}}},methods:{startTimer(){this.timer=setInterval(()=>{this.counter--},1e3)},stopTimer(){clearInterval(this.timer);this.success=""},resendCode(){var e;var t=1;var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),client_uuid:this.client_uuid};e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/requestCode",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];this.success="";if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){if(typeof _resend_counter!=="undefined"&&_resend_counter!==null){this.counter=parseInt(_resend_counter)}else this.counter=20;this.startTimer();this.success=e.msg;if(!f(e.details)){if(!f(e.details.verification_code)){se(e.details.verification_code)}}}else{this.error=e.msg}});n[t].always(e=>{this.is_loading=false})},verifyCode(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),client_uuid:this.client_uuid,verification_code:this.verification_code,auto_login:true};var t=1;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/verifyCode",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.error=[];this.is_loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){window.location.href=e.details.redirect}else{this.error=e.msg}});n[t].always(e=>{this.is_loading=false})}}});Lt.use(Maska);const qt=Lt.mount("#vue-account-verification");const Vt=Vue.createApp({components:{"component-phone":e,"vue-recaptcha":De},data(){return{is_loading:false,mobile_number:"",mobile_prefix:"",error:[],steps:1,verification_code:"",client_uuid:"",firstname:"",lastname:"",email_address:"",password:"",cpassword:"",show_password:false,password_type:"password",next_url:"",show_recaptcha:false,recaptcha_response:"",counter:40,timer:undefined,success:""}},mounted(){this.next_url=this.$refs.redirect.value;if(typeof _capcha!=="undefined"&&_capcha!==null){this.show_recaptcha=_capcha==1?true:false}if(typeof _resend_counter!=="undefined"&&_resend_counter!==null){this.counter=parseInt(_resend_counter)}},watch:{counter(e,t){if(e<=0){this.stopTimer()}}},computed:{DataValid(){if(!f(this.mobile_number)&&f(!this.mobile_prefix)){if(this.show_recaptcha){if(!f(this.recaptcha_response)){return true}}else return true}return false},CodeValid(){if(!f(this.verification_code)){return true}return false},CompleteFormValid(){if(!f(this.firstname)&&f(!this.lastname)&&!f(this.email_address)){return true}return false}},methods:{startTimer(){this.timer=setInterval(()=>{this.counter--},1e3)},stopTimer(){clearInterval(this.timer);this.success=""},showPassword(){this.show_password=this.show_password==true?false:true;if(this.show_password){this.password_type="text"}else this.password_type="password"},recaptchaVerified(e){this.recaptcha_response=e},recaptchaExpired(){if(this.show_recaptcha){this.$refs.vueRecaptcha.reset()}},recaptchaFailed(){},registerPhone(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),mobile_prefix:this.mobile_prefix,mobile_number:this.mobile_number,recaptcha_response:this.recaptcha_response};var t=1;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/RegistrationPhone",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.steps=2;this.client_uuid=e.details.client_uuid;setTimeout(()=>{this.$refs.code.focus()},500);if(!f(e.details.verification_code)){se(e.details.verification_code)}this.startTimer()}else{this.recaptchaExpired();this.error=e.msg;this.client_uuid=""}});n[t].always(e=>{this.is_loading=false})},verifyPhoneCode(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),client_uuid:this.client_uuid,verification_code:this.verification_code};var t=1;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/verifyCode",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.steps=3}else{this.error=e.msg}});n[t].always(e=>{this.is_loading=false})},resendCode(){var e;var t=1;var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),client_uuid:this.client_uuid};e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/requestCodePhone",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];this.success="";if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){if(typeof _resend_counter!=="undefined"&&_resend_counter!==null){this.counter=parseInt(_resend_counter)}else this.counter=40;this.startTimer();this.success=e.msg}else{this.error=e.msg}});n[t].always(e=>{this.is_loading=false})},completeSignup(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),client_uuid:this.client_uuid,firstname:this.firstname,lastname:this.lastname,email_address:this.email_address,password:this.password,cpassword:this.cpassword,next_url:this.next_url};var t=1;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/completeSignup",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){window.location.href=e.details.redirect_url}else{this.error=e.msg}});n[t].always(e=>{this.is_loading=false})}}});Vt.use(Maska);const Ut=Vt.mount("#vue-register-less");const Jt=Vue.createApp({components:{"component-phone":e},data(){return{is_loading:false,error:[],success:"",steps:1,verification_code:"",client_uuid:"",next_url:"",firstname:"",lastname:"",email_address:"",mobile_number:"",mobile_prefix:"",counter:20,timer:undefined}},mounted(){if(typeof _uuid!=="undefined"&&_uuid!==null){this.client_uuid=_uuid}if(typeof _redirect_to!=="undefined"&&_redirect_to!==null){this.next_url=_redirect_to}if(typeof _steps!=="undefined"&&_steps!==null){this.steps=_steps}if(this.steps==2){this.getCustomerInfo()}if(typeof _resend_counter!=="undefined"&&_resend_counter!==null){this.counter=parseInt(_resend_counter)}this.startTimer()},computed:{CodeValid(){if(!f(this.verification_code)){return true}return false},CompleteFormValid(){if(!f(this.firstname)&&f(!this.lastname)&&!f(this.mobile_number)){return true}return false}},watch:{counter(e,t){if(e<=0){this.stopTimer()}}},methods:{startTimer(){this.timer=setInterval(()=>{this.counter--},1e3)},stopTimer(){clearInterval(this.timer);this.success=""},verifyCode(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),client_uuid:this.client_uuid,verification_code:this.verification_code};var t=1;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/verifyCode",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.error=[];this.is_loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.steps=2;this.getCustomerInfo()}else{this.error=e.msg}});n[t].always(e=>{this.is_loading=false})},resendCode(){var e;var t=1;var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),client_uuid:this.client_uuid};e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/requestCode",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];this.success="";if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){if(typeof _resend_counter!=="undefined"&&_resend_counter!==null){this.counter=parseInt(_resend_counter)}else this.counter=20;this.startTimer();this.success=e.msg}else{this.error=e.msg}});n[t].always(e=>{this.is_loading=false})},getCustomerInfo(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),client_uuid:this.client_uuid};var t=_();e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/getCustomerInfo",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.error=[];this.is_loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.firstname=e.details.firstname;this.lastname=e.details.lastname;this.email_address=e.details.email_address}else{this.error=e.msg}});n[t].always(e=>{this.is_loading=false})},completeSignup(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),client_uuid:this.client_uuid,firstname:this.firstname,lastname:this.lastname,mobile_prefix:this.mobile_prefix,mobile_number:this.mobile_number,next_url:this.next_url};var t=1;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/completeSocialSignup",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){window.location.href=e.details.redirect_url}else{this.error=e.msg}});n[t].always(e=>{this.is_loading=false})}}});Jt.use(Maska);const Bt=Jt.mount("#vue-account-information");const Gt=5;const Wt=Vue.createApp({components:{"component-phone":e},data(){return{loading:false,error:[],email_address:"",steps:1,uuid:"",success:"",counter:Gt,timer:undefined,mobile_number:"",mobile_prefix:"",password_reset_options:"",otp:"",validation_type:1}},watch:{counter(e,t){if(e<=0){this.stopTimer()}}},mounted(){if(typeof password_reset_options!=="undefined"&&password_reset_options!==null){this.password_reset_options=password_reset_options}if(this.password_reset_options=="sms"){this.validation_type=2}},computed:{checkForm(){if(this.validation_type==2){if(f(this.mobile_number)){return true}}else{if(!this.validEmail(this.email_address)){return true}}return false}},methods:{validEmail:function(e){var t=/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;return t.test(e)},startTimer(){this.counter=Gt;this.timer=setInterval(()=>{this.counter--},1e3)},stopTimer(){clearInterval(this.timer)},startOver(){this.steps=1;this.stopTimer()},requestResetPassword(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),email_address:this.email_address,password_reset_options:this.password_reset_options,mobile_number:this.mobile_number,mobile_prefix:this.mobile_prefix};var t=1;e=JSON.stringify(e);let a=this.validation_type==2?ajaxurl+"/requestResetpasswordsms":ajaxurl+"/requestResetPassword";n[t]=p.ajax({url:a,method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.loading=true;this.error=[];this.success="";if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.steps=!f(e.details.steps)?e.details.steps:2;this.uuid=e.details.uuid;this.success=e.msg;this.startTimer()}else{this.error=e.msg;this.success=""}});n[t].always(e=>{this.loading=false})},resendResetEmail(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),client_uuid:this.uuid,password_reset_options:this.password_reset_options,mobile_number:this.mobile_number,mobile_prefix:this.mobile_prefix};var t=1;e=JSON.stringify(e);n[t]=p.ajax({url:this.validation_type==2?ajaxurl+"/resendResetpasswordsms":ajaxurl+"/resendResetEmail",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.loading=true;this.error=[];this.success="";if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.success=e.msg;this.counter=Gt;this.startTimer()}else{this.error=e.msg}});n[t].always(e=>{this.loading=false})},verifyOTP(){this.loading=true;axios({method:"POST",url:ajaxurl+"/verifyOTP",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&client_uuid="+this.uuid+"&otp="+this.otp,timeout:c}).then(e=>{if(e.data.code==1){window.location.href=e.data.details.redirect}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{}).then(e=>{this.loading=false})}}});Wt.use(Maska);Wt.use(ElementPlus);const Zt=Wt.mount("#vue-forgot-password");const Qt=Vue.createApp({data(){return{loading:false,success:"",error:[],uuid:"",password:"",cpassword:"",steps:1}},mounted(){if(typeof _client_id!=="undefined"&&_client_id!==null){this.uuid=_client_id}},computed:{checkForm(){if(!f(this.password)&&!f(this.cpassword)){return true}return false}},methods:{resetPassword(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),client_uuid:this.uuid,password:this.password,cpassword:this.cpassword};var t=1;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/resetPassword",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.loading=true;this.error=[];this.success="";if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.success=e.msg;this.steps=2}else{this.error=e.msg}});n[t].always(e=>{this.loading=false})}}}).mount("#vue-reset-password");const Ht=Vue.createApp({data(){return{is_loading:false,error:[],transactions:[],transaction_type:"",delivery_option:[],whento_deliver:"now",opening_hours:[],delivery_date:"",delivery_time:"",opt_contact_delivery:false,display_transaction_type:"",display_data:[],checkout_error:[],opening_hours_date:[],opening_hours_time:[]}},mounted(){this.getTransaction()},updated(){},methods:{show(){this.error=[];p("#orderTypeTime").modal("show")},close(){p("#orderTypeTime").modal("hide")},JsonValue(e){return JSON.stringify({start_time:e.start_time,end_time:e.end_time,pretty_time:e.pretty_time})},getTransaction(){var e="";var t=_();var a=T("cart_uuid");e="cart_uuid="+a;e+=g();this.is_loading=true;n[t]=p.ajax({url:ajaxurl+"/checkoutTransaction",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:function(e){if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.transactions=e.details.transactions;this.delivery_option=e.details.delivery_option;this.opening_hours_date=e.details.opening_hours.dates;this.opening_hours_time=e.details.opening_hours.time_ranges}else{this.transactions=[];this.delivery_option=[];this.opening_hours=[];this.display_data=[];this.checkout_error=[];this.opening_hours_date=[]}});n[t].always(e=>{this.is_loading=false})},setData(e){this.transaction_type=e.transaction_type;this.display_transaction_type=e.transaction_type;this.display_data=e.data;if(typeof e.data.whento_deliver!=="undefined"&&e.data.whento_deliver!==null){this.whento_deliver=e.data.whento_deliver}if(typeof e.data.delivery_date!=="undefined"&&e.data.delivery_date!==null){this.delivery_date=e.data.delivery_date}if(typeof e.data.delivery_time!=="undefined"&&e.data.delivery_time!==null){this.delivery_time=e.data.delivery_time}if(typeof e.data.error!=="undefined"&&e.data.error!==null){this.checkout_error=e.data.error}else this.checkout_error=[]},validate(){this.error=[];if(this.whento_deliver=="now"){this.save()}else{if(f(this.delivery_date)){this.error.push(m("Delivery Date is required"))}else if(f(this.delivery_time)){this.error.push(m("Time is required"))}else{this.save()}}},save(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),transaction_type:this.transaction_type,whento_deliver:this.whento_deliver,delivery_date:this.delivery_date,delivery_time:!f(this.delivery_time)?JSON.parse(this.delivery_time):""};var t=1;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/checkoutSave",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){var t={whento_deliver:e.details.whento_deliver,delivery_date:e.details.delivery_date,delivery_time:e.details.delivery_time};S("choosen_delivery",JSON.stringify(t),30);S("cart_transaction_type",this.transaction_type,30);p("#orderTypeTime").modal("hide");N.loadcart();na.loadTips();if(typeof ks!=="undefined"&&ks!==null){ks.addressNeeded()}if(typeof Ws!=="undefined"&&Ws!==null){Ws.getTableAttributes()}}});n[t].always(e=>{this.is_loading=false})}}});Ht.use(ElementPlus);const Xt=Ht.mount("#vue-transaction");const ea={components:{"component-phone":e},props:["label","is_mobile","default_country","only_countries"],data(){return{error:[],is_loading:false,mobile_prefix:"",mobile_prefixes:[],mobile_number:"",mobile_number_old:""}},computed:{PhoneValid(){if(!f(this.mobile_number)&&this.mobile_number!=this.mobile_number_old){return true}return false}},methods:{show(){p("#changephoneModal").modal("show");this.error=[]},close(){p("#changephoneModal").modal("hide")},SendCode(){var e;var t=1;var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content")};e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/RequestEmailCode",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.close();this.$emit("setPhone",{mobile_prefix:this.mobile_prefix,mobile_number:this.mobile_number,message:e.msg})}else{this.error=e.msg}});n[t].always(e=>{this.is_loading=false})}},template:`
    <div class="modal" id="changephoneModal" tabindex="-1" role="dialog" 
    aria-labelledby="changephoneModal" aria-hidden="true"  >
	   <div class="modal-dialog" role="document">
	     <div class="modal-content" :class="{ 'modal-mobile' : is_mobile==1 }"> 

		  <div class="modal-header border-bottom-0" style="padding-bottom:0px !important;"> 
			<h5 class="modal-title" id="exampleModalLongTitle">
			    {{label.edit_phone}}
			</h5>
			<div class="close">
			  <a href="javascript:;" @click="close" class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a>    
			</div>
		   </div>

	       <div class="modal-body" style="overflow-y:inherit !important;">
	       	       	        
			<form class="forms mt-2 mb-2" @submit.prevent="SendCode" >
	        
	         <!--COMPONENTS-->
	        <component-phone
		    :default_country="default_country"    
		     :only_countries="only_countries"	
		    v-model:mobile_number="mobile_number"
		    v-model:mobile_prefix="mobile_prefix"
		    >
		    </component-phone>
		    <!--END COMPONENTS-->	   
	       
	         
	        </form>
	        
	        <div v-cloak v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
			    <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
			 </div>   
	        
	       </div> <!--modal body-->  
	       
	       <div class="modal-footer justify-content-start">	        
		   
	          <div class="border flex-fill">
		           <button class="btn btn-black w-100" @click="close" >
			          {{label.cancel}}
			       </button>
		       </div>       
		       <div class="border flex-fill">
		           <button @click="SendCode" 
		           class="btn btn-green w-100" :class="{ loading: is_loading }" :disabled="!PhoneValid"  >
			          <span class="label">{{label.continue}}</span>
			          <div class="m-auto circle-loader" data-loader="circle-side"></div>
			       </button>
		       </div>    
	       
		   </div> <!--footer-->
	       
	    </div> <!--content-->
	   </div> <!--dialog-->
	 </div> <!--modal-->           
    `};const ta={props:["label"],data(){return{error:[],is_loading:false,code:"",data:[],counter:20,timer:undefined}},computed:{CodeisValid(){if(!f(this.code)){return true}return false}},watch:{counter(e,t){if(e<=0){this.stopTimer()}}},methods:{show(e){this.data=e;this.code="";this.error=[];this.startTimer();p("#verifyCodeModal").modal("show");setInterval(()=>{this.$refs.refcode.focus()},500)},startTimer(){this.stopTimer();this.counter=20;this.timer=setInterval(()=>{this.counter--},1e3)},stopTimer(){clearInterval(this.timer)},close(){p("#verifyCodeModal").modal("hide")},submit(){this.$emit("afterSubmit",this.code)},resendCode(){var e;var t=1;var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content")};u("resendCode");e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/RequestEmailCode",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.counter=20;this.startTimer();if(!f(e.details)){if(!f(e.details.verification_code)){se(e.details.verification_code)}}}else{this.error=e.msg}});n[t].always(e=>{this.is_loading=false})}},template:`
    <div class="modal" id="verifyCodeModal" tabindex="-1" role="dialog" 
    aria-labelledby="verifyCodeModal" aria-hidden="true" data-backdrop="static" data-keyboard="false"   >
	   <div class="modal-dialog" role="document">
	     <div class="modal-content"> 
	     
	     <form class="forms" @submit.prevent="submit" >
	     
	       <div class="modal-body">
	       		       
	              
	        <a href="javascript:;" @click="close" 
           class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a>   

           <div class="text-center">
             <h4 class="m-0 mb-3 mt-3">{{label.steps}}</h4>
             <h6>{{label.for_security}}</h6>
           </div>
           
           
           
           <p class="bold">{{label.enter_digit}}</p>
            <div class="form-label-group">    
              <input v-model="code" ref="refcode"  v-maska="'######'"
              class="form-control form-control-text" 
              placeholder="" type="text" maxlength="6" id="code" > 
              <label for="code" class="required">{{label.code}}</label> 
            </div>   
                        
           
           
          <p class="bold">{{data.message}}</p>      
          
          <template v-if="counter===0">          
          <div class="mt-1 mb-3">           
           <a href="javascript:;" @click="resendCode" :disabled="is_loading" >
             <p class="m-0"><u>{{label.resend_code}}</u></p>
           </a>
          </div>
          </template>
          <template v-else>          
            <p><u>{{label.resend_code_in}} {{counter}}</u></p>
          </template>
          
          
          <div v-cloak v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
			<p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
		  </div>   
         
         </div> <!--modal body-->  
                
         <div class="modal-footer justify-content-start">	        
		       <button class="btn btn-green w-100" 
		       :class="{ loading: is_loading }" :disabled="!CodeisValid" >
		          <span class="label">{{label.submit}}</span>
		          <div class="m-auto circle-loader" data-loader="circle-side"></div>
		      </button>		      
		   </div> <!--footer--> 
	       
		</form>   
	    </div> <!--content-->
	   </div> <!--dialog-->
	 </div> <!--modal-->           
    `};const aa=Vue.createApp({components:{"component-change-phone":ea,"component-change-phoneverify":ta},mounted(){this.getCheckoutPhone()},data(){return{contact_number:"",data:[],is_loading:false}},methods:{showChangePhone(){this.$refs.cphone.show()},loadVerification(e){this.data=e;this.$refs.cphoneverify.show(e)},getCheckoutPhone(){var e;var t=_();var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid")};e=JSON.stringify(e);this.is_loading=true;n[t]=p.ajax({url:ajaxurl+"/getCheckoutPhone",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.contact_number=e.details.contact_number_w_prefix;this.$refs.cphone.mobile_prefixes=e.details.prefixes;this.$refs.cphone.mobile_prefix=e.details.default_prefix;this.$refs.cphone.mobile_number=e.details.contact_number;this.$refs.cphone.mobile_number_old=e.details.contact_number}else{this.contact_number="";this.$refs.cphone.mobile_prefixes=[]}});n[t].always(e=>{this.is_loading=false})},ChangePhone(e){u("change phone");var t;var a=1;var t={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),data:this.data,code:e};t=JSON.stringify(t);n[a]=p.ajax({url:ajaxurl+"/ChangePhone",method:"PUT",dataType:"json",data:t,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.$refs.cphoneverify.is_loading=true;this.$refs.cphoneverify.error=[];if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.$refs.cphoneverify.close();sa.getCheckoutPhone()}else{this.$refs.cphoneverify.error=e.msg}});n[a].always(e=>{this.$refs.cphoneverify.is_loading=false})}}});aa.use(Maska);aa.use(ElementPlus);const sa=aa.mount("#vue-contactphone");const ia={props:["title","add_promo_code","apply_text","is_mobile"],data(){return{is_loading:false,promo_code:"",error:[]}},methods:{showModal(){p("#promocodeModal").modal("show")},closeModal(){p("#promocodeModal").modal("hide");this.error=[];this.promo_code=""},applyCode(){this.error=[];var e;var t=1;e="promo_code="+this.promo_code;e+="&cart_uuid="+T("cart_uuid");e+=g();n[t]=p.ajax({url:ajaxurl+"/applyPromoCode",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.closeModal();if(da.enabled){da.getAppliedPromo()}else{this.$emit("setLoadpromo")}N.loadcart()}else{this.error[0]=e.msg}});n[t].always(e=>{this.is_loading=false})},back(){this.closeModal();this.$emit("back")}},computed:{hasData(){if(this.promo_code==""){this.error=[];return true}return false}},template:`
    <div class="modal" id="promocodeModal" tabindex="-1" role="dialog" 
    aria-labelledby="promocodeModal" aria-hidden="true" data-backdrop="static" >
	   <div class="modal-dialog" role="document">
	     <div class="modal-content" :class="{ 'modal-mobile' : is_mobile==1 }" > 

		  <div class="modal-header border-bottom-0" style="padding-bottom:0px !important;">
			<h5 class="modal-title" id="exampleModalLongTitle">
			     {{title}}
			</h5>
			<div class="close">
			<a href="javascript:;" data-dismiss="modal" class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a>   
			</div>
		   </div>

	       <div class="modal-body">
	       	       	        
	         <form class="forms mt-2 mb-2" @submit.prevent="applyCode" >
	         	   	   
		     <div class="form-label-group">    
              <input v-model="promo_code" class="form-control form-control-text" placeholder=""
               id="promo_code" type="text" maxlength="20" >   
              <label for="promo_code" class="required">{{add_promo_code}}</label> 
             </div>                
             
	         </form> <!--forms-->
	         	         
	         <div v-cloak v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
			    <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
			 </div>   
	         
	       </div> <!--modal body-->

	       <div class="modal-footer">
		        <button type="button" class="btn btn-black pl-4 pr-4" @click="back" >Cancel</button>
				<button type="button" class="btn btn btn-green pl-4 pr-4"
				  @click="applyCode" :class="{ loading: is_loading }" :disabled="hasData"
				>
				  <span class="label">{{apply_text}}</span><div class="m-auto circle-loader" data-loader="circle-side"></div>
				</button>				
		   </div>	        
	       
	    </div> <!--content-->
	   </div> <!--dialog-->
	 </div> <!--modal-->           
    `};const oa=Vue.createApp({components:{"component-promocode":ia},data(){return{is_loading:false,loading:false,remove_loading:false,saved_disabled:true,promo_id:[],data:[],error:"",promo_selected:[]}},created(){this.loadPromo()},updated(){if(this.promo_id.length>0){this.saved_disabled=true}else{this.saved_disabled=false}},computed:{hasSelectedPromo(){if(Object.keys(this.promo_id).length>0){return true}return false}},methods:{loadPromo(){this.is_loading=true;var e;var t=_();let a=T("currency_code");a=!f(a)?a:"";e="cart_uuid="+T("cart_uuid");e+="&currency_code="+a;e+=g();n[t]=p.ajax({url:ajaxurl+"/loadPromo",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code===1){this.data=e.details.data;this.promo_selected=e.details.promo_selected;da.enabled=false}else{this.data=[];this.promo_selected=[];da.enabled=true}});n[t].always(e=>{this.is_loading=false})},show(){this.error="";p(this.$refs.promo_modal).modal("show")},hide(){p(this.$refs.promo_modal).modal("hide")},close(){p(this.$refs.promo_modal).modal("hide")},save(){this.loading=true;axios({method:"PUT",url:ajaxurl+"/applyPromo",data:{YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),promo_type:this.promo_id[0],promo_id:this.promo_id[1],currency_code:T("currency_code")},timeout:c}).then(e=>{console.debug(e.data.code);if(e.data.code==1){this.error="";this.close();this.loadPromo();N.loadcart()}else{this.error=e.data.msg}})["catch"](e=>{}).then(e=>{this.loading=false})},removePromo(e,t){var a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),promo_id:t,promo_type:e};var s=1;a=JSON.stringify(a);n[s]=p.ajax({url:ajaxurl+"/removePromo",method:"PUT",dataType:"json",data:a,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.remove_loading=true;if(n[s]!=null){n[s].abort()}}});n[s].done(e=>{if(e.code==1){this.close();this.loadPromo();this.promo_id=[];N.loadcart()}else this.error=e.msg});n[s].always(e=>{this.remove_loading=false})},showPromoCode(){this.$refs.childref.showModal();this.hide()}}});oa.use(ElementPlus);const ra=oa.mount("#vue-promo");const da=Vue.createApp({components:{"component-apply-promocode":ia},data(){return{enabled:false,has_promocode:false,saving:"",data:[]}},mounted(){},watch:{enabled(e,t){if(e){this.getAppliedPromo()}}},methods:{show(){if(this.has_promocode){this.removePromoCode()}else{this.$refs.childref.showModal()}},getAppliedPromo(){var e;var t=1;e="cart_uuid="+T("cart_uuid");e+=g();n[t]=p.ajax({url:ajaxurl+"/getAppliedPromo",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.data=e.details.data;this.has_promocode=true;this.saving=e.details.saving}else{this.has_promocode=false;this.saving="";this.data=[]}});n[t].always(e=>{})},removePromoCode(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),promo_id:this.data.id,promo_type:this.data.type};var t=1;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/removePromo",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.has_promocode=false;this.data=[];this.getAppliedPromo();N.loadcart()}});n[t].always(e=>{})}}}).mount("#vue-add-promocode");const la=Vue.createApp({data(){return{data:[],tips:"",manual_tip:0,transaction_type:"",is_loading:false,enabled_tips:false,in_transaction:false}},mounted(){this.loadTips()},computed:{ifOthers(){if(this.tips=="fixed"){return true}return false},ifDelivery(){return this.in_transaction}},updated(){},methods:{loadTips(){this.is_loading=true;var e;var t=_();let a=T("currency_code");a=!f(a)?a:"";e="cart_uuid="+T("cart_uuid");e+="&currency_code="+a;e+=g();n[t]=p.ajax({url:ajaxurl+"/loadTips",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.data=e.details.data;this.tips=e.details.tips;this.transaction_type=e.details.transaction_type;this.enabled_tips=e.details.enabled_tips;this.in_transaction=e.details.in_transaction;this.setOther()}else{this.data=[]}});n[t].always(e=>{this.is_loading=false})},checkoutAddTips(e,t){if(e=="fixed"){return}this.tips=e;var a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),value:e,is_manual:t};var s=1;a=JSON.stringify(a);this.is_loading=true;n[s]=p.ajax({url:ajaxurl+"/checkoutAddTips",method:"PUT",dataType:"json",data:a,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(n[s]!=null){n[s].abort()}}});n[s].done(e=>{if(e.code==1){N.loadcart()}else{}});n[s].always(e=>{this.is_loading=false})},setOther(){if(this.tips>0){var a=false;this.data.forEach((e,t)=>{if(this.tips==e.value){a=true}});if(!a){this.manual_tip=this.tips;this.tips="fixed"}}}}});la.use(ElementPlus);const na=la.mount("#vue-tips");let R;let ca=[];const ma=Vue.createApp({components:{"component-address":Pe},data(){return{q:"",awaitingSearch:false,is_loading:false,transaction_type:"",data:[],error:[],show_list:true,location_data:[],new_coordinates:[],location_name:"",delivery_instructions:"",delivery_options:"",address_label:"",delivery_options_data:[],address_label_data:[],auto_load_data:false,cmaps:null,cmaps_config:[],cmaps_provider:"",cmaps_marker:undefined,cmaps_full:false,current_page:"checkout",out_of_range:false,address_component:[],addresses:[],inline_edit:false,formatted_address:"",address1:""}},beforeMount(){},mounted(){const e=document.getElementById("autoload");if(typeof e!=="undefined"&&e!==null){this.auto_load_data=e.value}if(this.auto_load_data==1){autosize(document.getElementById("delivery_instructions"));this.loadData()}},updated(){},computed:{ifDelivery(){if(this.transaction_type=="delivery"){return true}return false},hasData(){if(this.data.length>0){return true}return false},hasLocationData(){if(typeof this.location_data!=="undefined"&&this.location_data!==null){var e=Object.keys(this.location_data).length;if(e>0){return true}}return false},hasNewCoordinates(){var e=Object.keys(this.new_coordinates).length;if(e>0){return true}return false}},methods:{show(){this.cmaps_full=false;p("#addressModal").modal("show");this.loadMap()},hide(){this.error=[];p("#addressModal").modal("hide")},close(){this.error=[];p("#addressModal").modal("hide")},showNewAddress(){this.$refs.childref.showModal()},hideChange(){p("#changeAddressModal").modal("hide")},save(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),data:this.location_data,location_name:this.location_name,delivery_instructions:this.delivery_instructions,delivery_options:this.delivery_options,address_label:this.address_label,formatted_address:this.formatted_address,address1:this.address1};var t=1;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/checkoutSaveAddress",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.hide();this.loadData();N.loadcart()}else{this.error[0]=e.msg}});n[t].always(e=>{this.is_loading=false})},clearData(){this.data=[];this.q=""},showList(){this.show_list=true},setLocationDetails(e){this.location_data=e;this.formatted_address=e.address.formatted_address;this.address1=e.address.address1;if(!f(e.attributes)){this.location_name=e.attributes.location_name;this.delivery_instructions=e.attributes.delivery_instructions;this.delivery_options=e.attributes.delivery_options;this.address_label=e.attributes.address_label}else{this.location_name="";this.delivery_instructions="";this.delivery_options="Leave it at my door";this.address_label="Home"}this.hideChange();this.show()},setPlaceData(){this.hideChange();this.loadData();N.loadcart()},loadData(){var e="";var t=_();e="cart_uuid="+T("cart_uuid");e+=g();n[t]=p.ajax({url:ajaxurl+"/checkoutAddress",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.transaction_type=e.details.transaction_type;this.location_data=e.details.data;if(typeof e.details.data.address!=="undefined"&&e.details.data.address!==null){this.formatted_address=e.details.data.address.formatted_address;this.address1=e.details.data.address.address1}this.cmaps_config=e.details.maps_config;this.delivery_options_data=e.details.delivery_option;this.address_label_data=e.details.address_label;this.addresses=e.details.addresses;var t=Object.keys(e.details.delivery_option);t=t[0];this.delivery_options=t;var t=Object.keys(e.details.address_label);t=t[0];this.address_label=t;if(!f(e.details.data.attributes)){this.location_name=e.details.data.attributes.location_name;this.delivery_instructions=e.details.data.attributes.delivery_instructions;this.delivery_options=e.details.data.attributes.delivery_options;this.address_label=e.details.data.attributes.address_label}}else{this.transaction_type="";this.error[0]=e.msg;this.location_data=[];this.cmaps_config=[];this.delivery_options_data=[];this.address_label_data=[];this.addresses=[];this.formatted_address="";this.address1=""}});n[t].always(e=>{})},loadMap(){this.cmaps_provider=this.cmaps_config.provider;switch(this.cmaps_provider){case"google.maps":if(typeof this.cmaps!=="undefined"&&this.cmaps!==null){this.removeMarker()}else{this.cmaps=new google.maps.Map(document.getElementById("cmaps"),{center:{lat:parseFloat(this.location_data.latitude),lng:parseFloat(this.location_data.longitude)},zoom:parseInt(this.cmaps_config.zoom),disableDefaultUI:true})}this.addMarker({position:{lat:parseFloat(this.location_data.latitude),lng:parseFloat(this.location_data.longitude)},map:this.cmaps,icon:this.cmaps_config.icon,animation:google.maps.Animation.DROP});break;case"mapbox":mapboxgl.accessToken=this.cmaps_config.key;this.cmaps=new mapboxgl.Map({container:document.getElementById("cmaps"),style:"mapbox://styles/mapbox/streets-v12",center:[parseFloat(this.location_data.longitude),parseFloat(this.location_data.latitude)],zoom:14});this.addMarker({position:{lat:parseFloat(this.location_data.latitude),lng:parseFloat(this.location_data.longitude)},map:this.cmaps,icon:this.cmaps_config.icon,animation:null});break}},addMarker(t){u("add markerx");u(this.cmaps_provider);u(t);switch(this.cmaps_provider){case"google.maps":R=new google.maps.Marker(t);this.cmaps.panTo(new google.maps.LatLng(t.position.lat,t.position.lng));break;case"mapbox":let e={};if(t.draggable){e={draggable:true}}R=new mapboxgl.Marker(e).setLngLat([t.position.lng,t.position.lat]).addTo(this.cmaps);break}},removeMarker(){u("remove marker");switch(this.cmaps_provider){case"google.maps":R.setMap(null);break;case"mapbox":R.remove();break}},cancelPin(){this.error=[];this.cmaps_full=false;this.removeMarker();this.addMarker({position:{lat:parseFloat(this.location_data.latitude),lng:parseFloat(this.location_data.longitude)},map:this.cmaps,icon:this.cmaps_config.icon,animation:this.cmaps_provider=="google.maps"?google.maps.Animation.DROP:null});this.mapBoxResize()},adjustPin(){u("adjustPin");this.new_coordinates=[];this.cmaps_full=true;try{this.removeMarker()}catch(e){u(e)}this.addMarker({position:{lat:parseFloat(this.location_data.latitude),lng:parseFloat(this.location_data.longitude)},map:this.cmaps,icon:this.cmaps_config.icon,animation:this.cmaps_provider=="google.maps"?google.maps.Animation.DROP:null,draggable:true});if(this.cmaps_provider=="google.maps"){R.addListener("dragend",e=>{this.new_coordinates={lat:e.latLng.lat(),lng:e.latLng.lng()}})}else if(this.cmaps_provider=="mapbox"){R.on("dragend",e=>{const t=R.getLngLat();this.new_coordinates={lat:t.lat,lng:t.lng}});this.mapBoxResize()}},mapBoxResize(){if(this.cmaps_provider=="mapbox"){setTimeout(()=>{this.cmaps.resize()},500)}},setNewCoordinates(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),lat:this.location_data.latitude,lng:this.location_data.longitude,new_lat:this.new_coordinates.lat,new_lng:this.new_coordinates.lng};var t=1;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/checkoutValidateCoordinates",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error=[];if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.location_data.latitude=this.new_coordinates.lat;this.location_data.longitude=this.new_coordinates.lng;this.cmaps_full=false;this.removeMarker();this.addMarker({position:{lat:parseFloat(this.location_data.latitude),lng:parseFloat(this.location_data.longitude)},map:this.cmaps,icon:this.cmaps_config.icon,animation:this.cmaps_provider=="google.maps"?google.maps.Animation.DROP:null});this.mapBoxResize()}else{this.error=e.msg}});n[t].always(e=>{this.is_loading=false})}}}).mount("#vue-manage-address");const ua=Vue.createApp({components:{"component-change-address":Pe,"component-address":Fe},data(){return{out_of_range:false,address_component:[],addresses:[]}},mounted(){},methods:{show(){this.$refs.address.showModal();this.getAddresses()},afterChangeAddress(e){N.loadcart();if(typeof a!=="undefined"&&a!==null){a.afterChangeAddress()}if(typeof O!=="undefined"&&O!==null){O.$refs.ref_services.getMerchantServices()}},afterSetAddress(){this.$refs.address.closeModal();N.loadcart()},afterDeleteAddress(){this.getAddresses()},editAddress(e){this.$refs.address.close();this.$refs.addressform.show(e.address_uuid)},afterSaveAddForm(){N.loadcart()},getAddresses(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content")};var t=_();e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/getAddresses",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.addresses=e.details.data}else{this.addresses=[]}})}}}).mount("#vue-change-address");const ha={props:["ajax_url","amount_to_pay","enabled_digital_wallet"],data(){return{use_wallet:false,data:[],loading:false,currency_code:"",message:""}},created(){let e=T("currency_code");this.currency_code=!f(e)?e:"";this.getCartWallet()},computed:{canUseWallet(){if(Object.keys(this.data).length>0){if(this.data.balance_raw>0){return true}}return false}},watch:{use_wallet(e,t){this.applyDigitalWallet(e==true?1:0,null)}},methods:{getCartWallet(){this.loading=true;axios({method:"POST",url:this.ajax_url+"/getCartWallet",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&cart_uuid="+T("cart_uuid")+"&currency_code="+this.currency_code,timeout:c}).then(e=>{if(e.data.code==1){this.data=e.data.details;this.use_wallet=e.data.details.use_wallet}else{this.data=[];this.use_wallet=false}})["catch"](e=>{}).then(e=>{this.loading=false})},applyDigitalWallet(t,e){this.loading=true;let a=this.amount_to_pay;if(typeof e!=="undefined"&&e!==null){a=e}axios({method:"POST",url:this.ajax_url+"/applyDigitalWallet",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&cart_uuid="+T("cart_uuid")+"&currency_code="+this.currency_code+"&use_wallet="+t+"&amount_to_pay="+a,timeout:c}).then(e=>{if(e.data.code==1){if(t){this.message=e.data.msg}else{this.message=""}this.$emit("afterApplywallet",e.data.details)}else{this.use_wallet=false;this.message="";ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{}).then(e=>{this.loading=false})}},template:"#xtemplate_digital_wallet"};const _a=Vue.createApp({data(){return{error:[],data:[],data_saved_payment:[],saved_payment_error:[],default_payment_uuid:"",transaction_type:"",amount_to_pay:0,cart_uuid:T("cart_uuid"),saved_payment_loading:false,payment_list_loading:false,payment_change:0,wallet_data:[]}},mounted(){this.PaymentList();this.SavedPaymentList()},computed:{hasData(){if(this.data.length>0){return true}return false},hasSavedPayment(){if(this.data_saved_payment.length>0){return true}return false},validatePaymentChange(){let e=parseFloat(this.payment_change);if(e>0){return true}return false},validatePaymentChangeValue(){if(N.cart_total.raw<=this.payment_change){return true}return false},isWalletFullPayment(){if(Object.keys(this.wallet_data).length>0){if(this.wallet_data.use_wallet&&this.wallet_data.amount_due_raw<=0){return true}}return false},usePartialPayment(){if(Object.keys(this.wallet_data).length>0){if(this.wallet_data.use_wallet&&this.wallet_data.amount_due_raw>0){N.use_partial_payment=true;return true}}N.use_partial_payment=false;return false},getPayRemaining(){if(Object.keys(this.wallet_data).length>0){if(this.wallet_data.use_wallet){return this.wallet_data.pay_remaining}}return false}},methods:{NumbersOnly(e){e=e?e:window.event;var t=e.which?e.which:e.keyCode;if(t>31&&(t<48||t>57)&&t!==46){e.preventDefault()}else{return true}},showPayment(e){try{this.$refs[e].showPaymentForm()}catch(t){o.alert(t,{})}},PaymentRender(e){this.$refs[e.payment_code].PaymentRender(e)},AfterCancelPayment(){N.is_submit=false},Alert(e){o.alert(e,{})},showLoadingBox(e){if(!f(e)){Ge.$refs.box.setMessage(e)}Ge.$refs.box.show()},closeLoadingBox(){Ge.$refs.box.close()},PaymentList(){this.payment_list_loading=true;var e;var t=_();let a=T("currency_code");a=!f(a)?a:"";e="cart_uuid="+T("cart_uuid");e+="&currency_code="+a;e+=g();n[t]=p.ajax({url:ajaxurl+"/PaymentList",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.data=e.details.data}else{this.error[0]=e.msg}});n[t].always(e=>{this.payment_list_loading=false})},SavedPaymentList(){this.saved_payment_loading=true;var e;var t=_();let a=T("currency_code");a=!f(a)?a:"";e="cart_uuid="+T("cart_uuid");e+="&currency_code="+a;e+=g();n[t]=p.ajax({url:ajaxurl+"/SavedPaymentList",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.data_saved_payment=e.details.data;this.default_payment_uuid=e.details.default_payment_uuid;N.selected_payment_uuid=this.default_payment_uuid;if(typeof N!=="undefined"&&N!==null){}}else{this.error[0]=e.msg;this.data_saved_payment=[];this.default_payment_uuid="";N.selected_payment_uuid=""}});n[t].always(e=>{this.saved_payment_loading=false;ce()})},deleteSavedPaymentMethod(e,t){var a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),payment_uuid:e,payment_code:t};var s=1;a=JSON.stringify(a);n[s]=p.ajax({url:ajaxurl+"/deleteSavedPaymentMethod",method:"PUT",dataType:"json",data:a,contentType:d.json,timeout:c,crossDomain:true,beforeSend:function(e){if(n[s]!=null){n[s].abort()}}});n[s].done(e=>{if(e.code==1){this.SavedPaymentList()}else{this.saved_payment_error[0]=e.msg}})},setDefaultPayment(e){var t={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),payment_uuid:e};var a=1;t=JSON.stringify(t);n[a]=p.ajax({url:ajaxurl+"/setDefaultPayment",method:"PUT",dataType:"json",data:t,contentType:d.json,timeout:c,crossDomain:true,beforeSend:function(e){if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.SavedPaymentList();if(typeof N!=="undefined"&&N!==null){}}else{this.saved_payment_error[0]=e.msg}})},afterApplywallet(e){this.wallet_data=e;if(Object.keys(this.wallet_data).length>0){N.use_digital_wallet=e.use_wallet}},refreshWallet(t){if(typeof this.$refs.digital_wallet!=="undefined"&&this.$refs.digital_wallet!==null){if(t.use_digital_wallet){let e=t.use_digital_wallet?1:0;this.$refs.digital_wallet.applyDigitalWallet(e,t.cart_total)}}}}});if(typeof components_bundle!=="undefined"&&components_bundle!==null){p.each(components_bundle,function(e,t){_a.component(e,t)})}_a.component("components-digital-wallet",ha);_a.use(Maska);_a.use(ElementPlus);const pa=_a.mount("#vue-payment-list");const fa={props:["ajax_url","realtime"],data(){return{ably:undefined,channel:undefined,piesocket:undefined,pusher:undefined}},mounted(){if(this.realtime.enabled){this.initRealTime()}},methods:{initRealTime(){if(this.realtime.provider=="pusher"){Pusher.logToConsole=false;this.pusher=new Pusher(this.realtime.key,{cluster:this.realtime.cluster});this.channel=this.pusher.subscribe(this.realtime.channel);this.channel.bind(this.realtime.event,e=>{u("receive tracking");u(e);this.$emit("afterReceive",e)})}else if(this.realtime.provider=="ably"){this.ably=new Ably.Realtime(this.realtime.ably_apikey);this.ably.connection.on("connected",()=>{this.channel=this.ably.channels.get(this.realtime.channel);this.channel.subscribe(this.realtime.event,e=>{u("receive ably");u(e);this.$emit("afterReceive",e)})})}else if(this.realtime.provider=="piesocket"){this.piesocket=new PieSocket({clusterId:this.realtime.piesocket_clusterid,apiKey:this.realtime.piesocket_api_key});this.channel=this.piesocket.subscribe(this.realtime.channel);this.channel.listen(this.realtime.event,e=>{u("receive piesocket");u(e);this.$emit("afterReceive",e)})}}}};let ga=[];let va;const ba=Vue.createApp({components:{"component-order-tracking":fa,"components-review":Ke},data(){return{order_uuid:php_order_uuid,merchant_info:[],items:[],meta:[],error:[],maps_config:[],maps_provider:"",cmaps:null,items_count:0,order_progress:-1,order_status:"",order_status_details:"",order_info:[],instructions:[],points_label:"",enabled_review:true}},mounted(){this.getOrder()},computed:{isActive(){return{active:true}},hasInstructions(){if(this.instructions){if(!f(this.instructions.text)){if(this.order_progress>=3){return true}}}return false}},methods:{updateImage(){ee()},getOrder(){var e;var t=_();e="order_uuid="+this.order_uuid;e+=g();n[t]=p.ajax({url:ajaxurl+"/getOrder",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:function(e){if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.merchant_info=e.details.merchant_info;this.order_info=e.details.order_info;this.items=e.details.items;this.meta=e.details.meta;this.updateImage();this.maps_config=e.details.maps_config;this.items_count=e.details.items_count;this.order_progress=e.details.progress.order_progress;this.order_status=e.details.progress.order_status;this.order_status_details=e.details.progress.order_status_details;this.instructions=e.details.instructions;this.points_label=e.details.points_label;this.enabled_review=e.details.enabled_review}else{this.error=e.msg;this.items_count=0;this.order_info=[];this.points_label=""}if(typeof this.maps_config.provider!=="undefined"&&this.maps_config.provider!==null){this.loadTrackMap()}});n[t].always(e=>{})},afterProgress(e){if(e.order_id!=this.order_info.order_id){return}if(e.order_progress==0){this.order_progress=e.order_progress;this.order_status=e.order_status;this.order_status_details=e.order_status_details}else if(e.order_progress==-1){}else{this.order_progress=e.order_progress;this.order_status=e.order_status;this.order_status_details=e.order_status_details}},writeReview(e){u("writeReview=>"+e);this.$refs.ReviewRef.order_uuid=e;this.$refs.ReviewRef.show()},loadTrackMap(){this.maps_provider=this.maps_config.provider;switch(this.maps_provider){case"google.maps":va=new google.maps.LatLngBounds;this.cmaps=new google.maps.Map(document.getElementById("cmaps"),{center:{lat:parseFloat(this.merchant_info.latitude),lng:parseFloat(this.merchant_info.longitude)},zoom:this.maps_config.zoom,disableDefaultUI:true});if(!f(this.merchant_info.latitude)){this.addMarker({position:{lat:parseFloat(this.merchant_info.latitude),lng:parseFloat(this.merchant_info.longitude)},map:this.cmaps,icon:this.maps_config.icon_merchant,animation:google.maps.Animation.DROP},1)}if(!f(this.meta.latitude)){this.addMarker({position:{lat:parseFloat(this.meta.latitude),lng:parseFloat(this.meta.longitude)},map:this.cmaps,icon:this.maps_config.icon_destination,animation:google.maps.Animation.DROP},2)}this.FitBounds();break;case"mapbox":va=new mapboxgl.LngLatBounds;mapboxgl.accessToken=this.maps_config.key;this.cmaps=new mapboxgl.Map({container:this.$refs.cmaps,style:"mapbox://styles/mapbox/streets-v12",center:[parseFloat(this.maps_config.default_lng),parseFloat(this.maps_config.default_lat)],zoom:14});this.cmaps.on("error",e=>{});this.mapBoxResize();if(!f(this.merchant_info.latitude)){this.addMarker({position:{lat:parseFloat(this.merchant_info.latitude),lng:parseFloat(this.merchant_info.longitude)},map:this.cmaps,icon:"marker_icon_merchant",animation:null},1)}if(!f(this.meta.latitude)){this.addMarker({position:{lat:parseFloat(this.meta.latitude),lng:parseFloat(this.meta.longitude)},map:this.cmaps,icon:"marker_icon_destination",animation:null},2)}this.FitBounds();break;case"yandex":this.initYandexTrackMap();break}},async initYandexTrackMap(){console.debug("initYandexTrackMap");await ymaps3.ready;const{YMap:e,YMapDefaultSchemeLayer:a,YMapMarker:s,YMapDefaultFeaturesLayer:i}=ymaps3;const o={center:[parseFloat(this.maps_config.default_lng),parseFloat(this.maps_config.default_lat)],zoom:k};if(I){I.destroy();I=null}if(!I){I=new e(this.$refs.cmaps,{location:o,showScaleInCopyrights:false,behaviors:["drag","scrollZoom"]},[new a({}),new i({})]);let t=[];if(!f(this.merchant_info.latitude)){let e=[parseFloat(this.merchant_info.longitude),parseFloat(this.merchant_info.latitude)];t.push(e);const d=document.createElement("img");d.className="icon-marker";d.src=this.maps_config.icon_merchant;I.addChild(new s({coordinates:e},d))}if(!f(this.meta.latitude)){let e=[parseFloat(this.meta.longitude),parseFloat(this.meta.latitude)];t.push(e);const d=document.createElement("img");d.className="icon-marker";d.src=this.maps_config.icon_destination;I.addChild(new s({coordinates:e},d))}const r={center:t[0],zoom:k};I.update({location:r})}},mapBoxResize(){if(this.maps_config.provider=="mapbox"){setTimeout(()=>{this.cmaps.resize()},500)}},addMarker(e,t){switch(this.maps_provider){case"google.maps":ga[t]=new google.maps.Marker(e);va.extend(ga[t].position);break;case"mapbox":const a=document.createElement("div");a.className=e.icon;ga[t]=new mapboxgl.Marker(a).setLngLat([e.position.lng,e.position.lat]).addTo(this.cmaps);va.extend(new mapboxgl.LngLat(e.position.lng,e.position.lat));break}},FitBounds(){switch(this.maps_provider){case"google.maps":this.cmaps.fitBounds(va);break;case"mapbox":this.cmaps.fitBounds(va);break}}}}).mount("#vue-orders-track");const ya={props:["payload","label","cart_preview","drawer"],data(){return{cart_loading:true,cart_uuid:"",cart_items:[],cart_summary:[],cart_merchant:[],cart_total:[],error:[],go_checkout:[],subtotal:[],items_count:0,update_cart_loading:false}},computed:{hasError(){if(this.error.length>0){return true}return false}},methods:{getCartPreview(){u("cart_preview=>"+this.cart_preview);if(!this.cart_preview){this.getCartCount();return}var t=_();let e=T("currency_code");e=!f(e)?e:"";var a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),currency_code:e,payload:this.payload};n[t]=p.ajax({url:ajaxurl+"/getCart",method:"PUT",dataType:"json",data:JSON.stringify(a),contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{this.cart_loading=false;if(e.code==1){
		this.cart_merchant=e.details.data.merchant;this.cart_uuid=e.details.cart_uuid;this.cart_items=e.details.data.items;
		this.cart_summary=e.details.data.summary;
		this.cart_total=e.details.data.total;this.error=e.details.error;this.go_checkout=e.details.go_checkout;this.subtotal=e.details.data.subtotal;this.items_count=e.details.items_count}else{this.cart_items=[];this.cart_summary=[];this.cart_total=[];this.error=[];this.subtotal=0;this.items_count=0}this.updateImage();this.$emit("setCartcount",this.items_count);if(typeof F!=="undefined"&&F!==null){F.hasItemInCart(this.items_count,this.cart_merchant)}})},
		getCartCount(){
			var t=_();
			let e=T("currency_code");
			e=!f(e)?e:"";
			var a={
				YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),
				cart_uuid:T("cart_uuid"),currency_code:e,
				payload:["items_count"]
			};
			n[t]=p.ajax({
				url:ajaxurl+"/getCart",
				method:"PUT",
				dataType:"json",
				data:JSON.stringify(a),
				contentType:d.json,
				timeout:c,
				crossDomain:true,
				beforeSend:e=>{
					if(n[t]!=null){
						n[t].abort()
					}
				}
			});
			n[t].done(e=>{
				if(e.code==1){
					this.items_count=e.details.items_count
				}else this.items_count=0;this.$emit("setCartcount",this.items_count)})},
		updateCartQty(e,t,a,s){if(e==1){t++}else t--;if(t<=0){this.remove(a,s)}else{this.updateCartItems(t,a,s)}},updateCartItems(e,t,a){var s;var i=1;s="cart_uuid="+a;s+="&cart_row="+t;s+="&item_qty="+e;this.update_cart_loading=true;s+=g();n[i]=p.ajax({url:ajaxurl+"/updateCartItems",method:"POST",dataType:"json",data:s,contentType:d.form,timeout:c,crossDomain:true,beforeSend:function(e){if(n[i]!=null){n[i].abort()}}});n[i].done(e=>{u("done");if(e.code==1){this.getCartPreview()}});n[i].always(e=>{this.update_cart_loading=false})},remove(e,t){var a;var s=1;a="row="+e+"&cart_uuid="+t;a+=g();n[s]=p.ajax({url:ajaxurl+"/removeCartItem",method:"POST",dataType:"json",data:a,contentType:"application/x-www-form-urlencoded; charset=UTF-8",timeout:c,crossDomain:true,beforeSend:function(e){if(n[s]!=null){n[s].abort()}}});n[s].done(e=>{u(e);if(e.code==1){this.getCartPreview()}})},clear(e){var t;var a=1;t="cart_uuid="+e;t+=g();n[a]=p.ajax({url:ajaxurl+"/clearCart",method:"POST",dataType:"json",data:t,contentType:d.form,timeout:c,crossDomain:true,beforeSend:function(e){if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.getCartPreview()}})},updateImage(){ee()},beforeClose(e){this.$emit("afterDrawerclose",false);e()}},template:`
	
	<el-drawer
    v-model="drawer"    
    direction="rtl"    
	size="320px"
	append-to-body="true"	
	:with-header="true"	
	:before-close="beforeClose"
	custom-class="drawer-preview-cart"
    >    
	  	   	  	  	  
	  <template v-if="cart_loading==false">
	    <template v-if="cart_items.length>0">    	        
	    
	     <div class="mt-3 mb-0">
		    <p class="m-0 bold">{{label.your_cart}}</p>
		    <a :href="cart_merchant.restaurant_slug" class="m-0 p-0"><h5 class="m-0 chevron d-inline position-relative">{{cart_merchant.restaurant_name}}</h5></a>
		    <p class="m-0 text-muted">{{cart_merchant.merchant_address}}</p>
		  </div>	  	  	
	    
			  <div class="d-flex justify-content-between align-items-center mb-1">
			   <div><h6 class="m-0">{{label.summary}}</h6></div>
			   <div>
			     <a href="javascript:;" @click="clear(cart_uuid)" class="javascript:;">
			       <p class="m-0"><u>{{label.clear}}</u></p>
			     </a>
			   </div>
			</div>
	    </template>
	    
	    <template v-else>
	    <h5 class="mt-3 mb-0">{{label.cart}}</h5> 
	    <div class="cart-empty text-center" >
	      <div class="mt-5">
	        <div class="no-results m-auto"></div>
	        <h6 class="m-0 mt-3">{{label.no_order}}</h6>
	        <p>{{label.lets_change_that}}</p>
	      </div>
	    </div>
	    </template>
	    
	  </template>  
	  
	    <!--section-cart-->  
  <DIV class="section-cart">
  
  <div v-cloak class="items" v-for="(items, index) in cart_items" >
  
    <div class="line-items row mb-1">
      <div class="col-3">		               
		 <el-image
			style="width: 50px; height: 50px"
			:src="items.url_image"
			:fit="contain"
			lazy
		  ></el-image>
      </div> <!--col-->
      
      <div class="col-6 p-0 d-flex justify-content-start flex-column">
      
        <p class="mb-1">
          {{ items.item_name }}
          <template v-if=" items.price.size_name!='' "> 
          ({{items.price.size_name}})
          </template>
        </p>	     

         <template v-if="items.price.discount>0">         
           <p class="m-0 font11"><del>{{items.price.pretty_price}}</del> {{items.price.pretty_price_after_discount}}</p>
         </template>
         <template v-else>
           <p class="m-0 font11">{{items.price.pretty_price}}</p>
         </template>
               
         <!--quantity-->
		 <div class="quantity d-flex justify-content-between">
		 
			<div>
			 <a href="javascript:;" @click="updateCartQty(0,items.qty,items.cart_row,cart_uuid)"  class="rounded-pill qty-btn" data-id="less">
			  <i class="zmdi zmdi-minus"></i>
			  </a>
			</div>
			
			<div class="qty">{{  items.qty }}</div>
			
			<div>
			<a href="javascript:;" @click="updateCartQty(1,items.qty,items.cart_row,cart_uuid)" class="rounded-pill qty-btn" data-id="plus">
			 <i class="zmdi zmdi-plus"></i>
			 </a>
		    </div>
		    
		 </div>
		 <!--quantity-->		 
        
		<p class="mb-0" v-if=" items.special_instructions!='' ">{{ items.special_instructions }}</p>	               
		 
        <template v-if=" items.attributes!='' "> 
          <template v-for="(attributes, attributes_key) in items.attributes">                    
            <p class="mb-0">            
            <template v-for="(attributes_data, attributes_index) in attributes">            
              {{attributes_data}}<template v-if=" attributes_index<(attributes.length-1) ">, </template>
            </template>
            </p>
          </template>
        </template>
        
              
      </div> <!--col-->
      
      <div class="col-3  quantity d-flex justify-content-start flex-column  text-right">
        <a href="javascript:;" @click="remove(items.cart_row,cart_uuid)" class="rounded-pill ml-auto mb-1"><i class="zmdi zmdi-close"></i></a>
        <template v-if="items.price.discount<=0 ">
          <p class="mb-0">{{ items.price.pretty_total }}</p>
        </template>
        <template v-else>
           <p class="mb-0">{{ items.price.pretty_total_after_discount }}</p>
        </template>
      </div> <!--col-->
      
    </div><!-- line-items-->
    
    <!--addon-items-->
    <div class="addon-items row mb-1"  v-for="(addons, index_addon) in items.addons" >
      <div class="col-3"><!--empty--></div> <!--col-->		     
      <div class="col-9 pl-0 d-flex justify-content-start flex-column">
         <p class="m-0 bold">{{ addons.subcategory_name }}</p>		  
                
         <template v-cloak v-for="addon_items in addons.addon_items">
         <div class="d-flex justify-content-between mb-1">
           <div class="flexrow"><p class="m-0">{{addon_items.qty}} x {{addon_items.pretty_price}} {{addon_items.sub_item_name}}</p></div>
           <div class="flexrow"><p class="m-0">{{addon_items.pretty_addons_total}}</p></div>
         </div>	<!--flex-->                  
        </template>
         
      </div> <!--col-->		      		      
    </div>
    <!-- addon-items-->
    
  </div> <!--items-->

  </DIV>
  
   <template v-if="cart_items.length>0">     
     <div class="divider"></div>   
	  <a class="btn btn-green w-100 pointer position-relative" :disabled="hasError" 
	  :href="hasError?'javascript:;':go_checkout.link"
	  :class="{ loading: update_cart_loading }"
	  >
	   <div class="d-flex justify-content-between">
	     <div>{{label.go_checkout}}</div>
	     <div v-if="!update_cart_loading">{{subtotal.value}}</div>
		 <div class="m-auto circle-loader" data-loader="circle-side"></div>
	   </div>	   
	  </a>
   </template> 
   
    <div v-cloak class="alert alert-warning m-0 mt-2" v-if="error.length>0">
      <p class="m-0" v-for="error_msg in error">
      {{ error_msg }}
      </p>
    </div>    
	  
	</el-drawer>		
	`};const wa={props:["ajax_url","message"],data(){return{settings:[],beams:undefined}},mounted(){this.getWebpushSettings()},methods:{getWebpushSettings(){axios({method:"POST",url:this.ajax_url+"/getWebpushSettings",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:c}).then(e=>{if(e.data.code==1){this.settings=e.data.details;if(this.settings.enabled==1){this.webPushInit()}}else{this.settings=[]}})["catch"](e=>{}).then(e=>{})},webPushInit(){if(this.settings.provider=="pusher"&&this.settings.user_settings.enabled==1){this.beams=new PusherPushNotifications.Client({instanceId:this.settings.pusher_instance_id});this.beams.start().then(()=>{this.beams.setDeviceInterests(this.settings.user_settings.interest).then(()=>{console.log("Device interests have been set")}).then(()=>this.beams.getDeviceInterests()).then(e=>console.log("Current interests:",e))["catch"](e=>{var t={notification_type:"push",message:"Beams "+e,date:"",image_type:"icon",image:"zmdi zmdi-info-outline"};D.$refs.notification.addData(t)})})["catch"](e=>{var t={notification_type:"push",message:"Beams "+e,date:"",image_type:"icon",image:"zmdi zmdi-info-outline"};D.$refs.notification.addData(t)})}else if(this.settings.provider=="onesignal"){}}},template:`	   
	`};const xa={components:{"components-webpush":wa},props:["ajax_url","label","realtime","view_url","avatar","image_background"],data(){return{data:[],count:0,new_message:false,player:undefined,ably:undefined,channel:undefined,piesocket:undefined,observer:undefined,pusher:undefined}},mounted(){this.getAllNotification();if(this.realtime.enabled){this.initRealTime()}this.observer=lozad(".lozad",{loaded:function(e){e.classList.add("loaded")}})},updated(){this.observer.observe()},computed:{hasData(){if(this.data.length>0){return true}return false},ReceiveMessage(){if(this.new_message){return true}return false}},methods:{initRealTime(){u(this.realtime.event_tracking_order);if(this.realtime.provider=="pusher"){Pusher.logToConsole=false;this.pusher=new Pusher(this.realtime.key,{cluster:this.realtime.cluster});this.channel=this.pusher.subscribe(this.realtime.channel);this.channel.bind(this.realtime.event,e=>{u("receive pusher");u(e);this.playAlert();this.addData(e)})}else if(this.realtime.provider=="ably"){this.ably=new Ably.Realtime(this.realtime.ably_apikey);this.ably.connection.on("connected",()=>{this.channel=this.ably.channels.get(this.realtime.channel);this.channel.subscribe(this.realtime.event,e=>{u("receive ably");u(e.data);this.playAlert();this.addData(e.data)})})}else if(this.realtime.provider=="piesocket"){this.piesocket=new PieSocket({clusterId:this.realtime.piesocket_clusterid,apiKey:this.realtime.piesocket_api_key});this.channel=this.piesocket.subscribe(this.realtime.channel);this.channel.listen(this.realtime.event,e=>{u("receive piesocket");u(e);this.playAlert();this.addData(e)})}},playAlert(){this.player=new Howl({src:[site_url+"/assets/sound/notify.mp3",site_url+"/assets/sound/notify.ogg"],html5:true});this.player.play()},getAllNotification(){axios({method:"POST",url:this.ajax_url+"/getNotifications",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:c}).then(e=>{if(e.data.code==1){this.data=e.data.details.data;this.count=e.data.details.count}else{this.data=[];this.count=0}})["catch"](e=>{}).then(e=>{})},addData(e){this.playAlert();this.data.unshift(e);this.count++;this.new_message=true;setTimeout(()=>{this.new_message=false;this.player.stop()},1e3)},clearAll(){axios({method:"POST",url:this.ajax_url+"/clearNotifications",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:c}).then(e=>{if(e.data.code==1){this.data=[];this.count=0}else{h(e.data.msg,"error")}this.new_message=false})["catch"](e=>{}).then(e=>{})}},template:`
	
	<components-webpush
	 :ajax_url="ajax_url"
	 :message='label'
	/>
	</components-webpush>
		
	<div class="btn-group pull-right notification-dropdown">
	      <button type="button" class="btn p-0 btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	        <img class="img-30 rounded-pill lozad" :src="avatar" :data-background-image="image_background" />
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
	`};const Sa={props:["ajax_url"],data(){return{data:[],is_loading:false}},mounted(){this.getLanguage()},methods:{getLanguage(){this.is_loading=true;axios({method:"POST",url:this.ajax_url+"/getLanguage",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:c}).then(e=>{if(e.data.code==1){this.data=e.data.details}else{this.data=[]}})["catch"](e=>{}).then(e=>{this.is_loading=false})},setLang(e){window.location.href=e}},template:`  
  <el-skeleton style="width: 150px" :loading="is_loading" animated>
  <template #template>
       <el-skeleton-item variant="image" style="width: 150px; height: 20px" />
  </template>	 
  <template #default>  
  
  <el-dropdown trigger="click" v-if="data.enabled">
    <span class="el-dropdown-link">

	   <div class="d-flex align-items-center">
	     <div class="mr-2">
		 <el-image
			style="width: 30px; height: 25px"
			:src="data.default.flag"
			:fit="contain"
			lazy
		  ></el-image>
		 </div>
		 <div class="mr-2">{{data.default.title}}</div>
		 <div><i class="zmdi zmdi-chevron-down"></i></div>
	   </div>	         
    </span>
    <template #dropdown>
      <el-dropdown-menu>
        <el-dropdown-item v-for="item in data.data" >
		
		<el-dropdown-item @click="setLang(item.link)">
		<div class="row no-gutters align-items-center">
		  <div class="col mr-3">
			<el-image
			style="width: 30px; height: 25px"
			:src="item.flag"
			:fit="contain"			
			></el-image>		
		  </div>
		  <div class="col">{{item.title}}</div>
		</div>
		</el-dropdown-item> 

		</el-dropdown-item>        
      </el-dropdown-menu>
    </template>
  </el-dropdown>

  </template>	 
  </el-skeleton>
  `};const Ta={props:["ajax_url","realtime"],data(){return{merchant_uuid:""}},created(){this.getMerchantByCart()},methods:{getMerchantByCart(){axios({method:"POST",url:this.ajax_url+"/getMerchantByCart",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&cart_uuid="+T("cart_uuid"),timeout:c}).then(e=>{if(e.data.code==1){this.merchant_uuid=e.data.details.merchant_uuid;this.initRealTime()}else{this.merchant_uuid=""}})["catch"](e=>{}).then(e=>{})},initRealTime(){if(this.realtime.provider=="pusher"&&this.realtime.enabled==1){Pusher.logToConsole=false;this.pusher=new Pusher(this.realtime.key,{cluster:this.realtime.cluster});this.channel=this.pusher.subscribe(this.merchant_uuid);this.channel.bind(this.realtime.event,e=>{let t=JSON.parse(e.message);this.$emit("afterReceiveitemupdate",t)})}}}};const Ia={data(){return{data:[],loading:false,based_currency:""}},created(){this.getCurrencyList()},methods:{getCurrencyList(){this.loading=true;let e=T("currency_code");e=!f(e)?e:"";axios({method:"POST",url:ajaxurl+"/getCurrencyList",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&currency_code="+e,timeout:c}).then(e=>{if(e.data.code==1){this.data=e.data.details.data;this.based_currency=e.data.details.based_currency}else{this.data=[];this.based_currency=""}})["catch"](e=>{}).then(e=>{this.loading=false})},setCurrency(e){this.based_currency=e;this.$emit("afterSelectcurrency",e)}},template:`		
	<div class="dropdown language-bar d-inline" v-if="!loading"  >	      
		<a class="dropdown-toggle text-truncate" href="javascript:;" 
		role="button" id="currencySelection" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		{{based_currency}}
		</a>		    
		<div class="dropdown-menu" aria-labelledby="currencySelection">			
		    <template v-for="(items,code) in data">
			<a @click="setCurrency(code)" class="dropdown-item" :class="{active : based_currency==code}" >			
			<div class="d-flex align-items-center">            
				<div class="text-truncate">{{items}}</div>
			</div>
			</a>	
			</template>	    		
		</div>		   
	</div> 
	`};const ka=Vue.createApp({components:{"component-cart-preview":ya,"components-notification":xa,"components-language":Sa,"component-notification-cart":Ta,"component-currency-selection":Ia},mounted(){this.getCartPreview()},data(){return{items_count:0,drawer:false,drawer_preview_cart:false}},methods:{getCartPreview(){this.$refs.childref.getCartPreview()},cartCount(e){this.items_count=e},showCartPreview(){this.drawer_preview_cart=true},afterDrawerclose(){this.drawer_preview_cart=false},afterReceiveitemupdate(e){axios({method:"POST",url:ajaxurl+"/validateCartItems",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&cart_uuid="+T("cart_uuid")+"&item_id="+e.item_id,timeout:c}).then(t=>{if(t.data.code==1){let e=JSON.parse(some_words);ElementPlus.ElMessageBox.alert(t.data.msg,"",{confirmButtonText:e.ok,callback:e=>{if(typeof D!=="undefined"&&D!==null){D.getCartPreview()}if(typeof N!=="undefined"&&N!==null){N.loadcart()}if(typeof F!=="undefined"&&F!==null){F.geStoreMenu()}}})}})["catch"](e=>{}).then(e=>{})},afterSelectcurrency(e){S("currency_code",e,30);if(typeof D!=="undefined"&&D!==null){D.getCartPreview()}if(typeof N!=="undefined"&&N!==null){N.loadcart()}if(typeof F!=="undefined"&&F!==null){F.geStoreMenu()}if(typeof Bs!=="undefined"&&Bs!==null){if(typeof Bs.$refs.food_list_recommended!=="undefined"&&Bs.$refs.food_list_recommended!==null){Bs.$refs.food_list_recommended.getFoodList();Bs.$refs.food_list.getFoodList()}}}}});ka.use(ElementPlus);const D=ka.mount("#vue-cart-preview");const Ca={props:["title","button"],data(){return{data:[]}},methods:{show(){p("#ModalAlert").modal("show")},close(){p("#ModalAlert").modal("hide")},alert(e){this.data=e;u(this.data);this.show()}},template:`
	  <div class="modal" id="ModalAlert" tabindex="-1" role="dialog" aria-labelledby="ModalAlert" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content">     
                
        <div class="modal-header">
             <h5 class="modal-title" id="exampleModalLabel">{{ title }}</h5>
	        <button type="button" class="close"  aria-label="Close" @click="close" >
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div> 
	      
          <div class="modal-body" >                      
            <div v-for="item in data.text" >
            {{ item }}
            </div>
          </div>
          
          <div class="modal-footer justify-content-center">
            <button class="btn btn-black w-25" @click="close" >               
               {{button}}
            </button>
          </div> <!--footer-->
       
	   </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->
	 `};const Oa=Vue.createApp({components:{"component-alert":Ca},methods:{alert(e){this.$refs.Alertref.alert({text:e})}}}).mount("#component_alert");const ja={props:["label"],data(){return{data:[],loading:false,merchant_name:"",cancel_status:false,cancel_msg:"",order_uuid:"",error:[],is_loading:false,cancel_response:[]}},methods:{show(){p("#ModalCancelOrder").modal("show")},close(){p("#ModalCancelOrder").modal("hide");this.order_uuid=""},cancel(e){this.merchant_name=e.merchant_name;this.show();this.order_uuid=e.order_uuid;var t={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:this.order_uuid};var a=1;t=JSON.stringify(t);n[a]=p.ajax({url:ajaxurl+"/cancelOrderStatus",method:"PUT",dataType:"json",data:t,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.loading=true;this.cancel_status=false;this.cancel_msg="";if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.cancel_status=e.details.cancel_status;this.cancel_msg=e.details.cancel_msg;this.cancel_response=e.details}else{this.cancel_status=false;this.cancel_response=[]}});n[a].always(e=>{this.loading=false})},applyCancelOrder(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:this.order_uuid};var t=1;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/applyCancelOrder",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.error=[];this.is_loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){h(e.msg,"success");this.close();Pa.page=0;Pa.data=[];Pa.orderHistory()}else{this.error=e.msg}});n[t].always(e=>{this.is_loading=false})}},template:`
	  <div class="modal" id="ModalCancelOrder" tabindex="-1" role="dialog" aria-labelledby="ModalCancelOrder" aria-hidden="true"  data-backdrop="static" data-keyboard="false" >
       <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content">     
                
        <div class="modal-header">
             <h5 class="modal-title" id="exampleModalLabel">{{merchant_name}}</h5>
	        <button type="button" class="close"  aria-label="Close" @click="close" :disabled="is_loading" >
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div> 
	      
	      <DIV v-if="loading">
		      <div class="loading mt-5 mb-5">      
		        <div class="m-auto circle-loader" data-loader="circle-side"></div>
		      </div>
		  </DIV>  
		  
		   <DIV v-else>
	      
          <div class="modal-body" >                      
            
            <h5 v-if="cancel_response.refund_status==='full_refund'">{{label.are_you_sure}}</h5>
            <h5 v-else>{{label.how}}</h5>
            
            <div class="mt-3">
            <p>{{cancel_msg}}</p>
            </div>
            
            <div class="mt-3 mb-2" v-if="cancel_response.refund_msg">
             <p>{{cancel_response.refund_msg}}</p>
            </div>
            
            <div v-cloak v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
			    <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
			 </div>   
            
          </div> <!--body-->
                    
          <div class="modal-footer justify-content-center">                      
            <button class="btn btn-black w-100" @click="close" :disabled="is_loading" >               
               {{label.dont}}
            </button>
            <button class="btn btn-green w-100" :disabled="!cancel_status" @click="applyCancelOrder()" 
            :class="{ loading: is_loading }" >               
               <span class="label">{{label.cancel}}</span>
               <div class="m-auto circle-loader" data-loader="circle-side"></div>
            </button>
          </div> <!--footer-->
          
          </DIV> <!--end if-->
       
	   </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->
	 `};const Ea=Vue.createApp({components:{"component-cancel-order":ja,"components-review":Ke},mounted(){this.orderHistory();this.orderSummary()},data(){return{show_next_page:false,loading:false,load_more:false,page:0,data:[],merchants:[],items:[],size:[],status:[],services:[],show_details:false,order_loading:false,order_merchant:[],order_items:[],order_summary:[],order_info:[],order_status:[],order_services:[],order_label:[],status_allowed_cancelled:[],status_allowed_review:[],q:"",awaitingSearch:false,search_found:false,summary_data:[],total_qty:0,animated_total_qty:0,total_order_raw:0,animated_total_order:0,refund_transaction:[],order_table_data:[]}},watch:{q(e,t){if(!this.awaitingSearch){if(f(e)){return false}setTimeout(()=>{var e;var t=_();e="page=0";e+="&q="+this.q;e+=g();n[t]=p.ajax({url:ajaxurl+"/orderHistory",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:function(e){if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.data=[];this.show_next_page=e.details.show_next_page;this.page=e.details.page;this.data.push(e.details.data.data);this.merchants=e.details.data.merchants;this.items=e.details.data.items;this.size=e.details.data.size;this.status=e.details.data.status;this.services=e.details.data.services;this.status_allowed_cancelled=e.details.data.status_allowed_cancelled;this.status_allowed_review=e.details.data.status_allowed_review;this.search_found=true}else{this.data=[];this.search_found=true}});n[t].always(e=>{this.awaitingSearch=false})},1e3);this.awaitingSearch=true}},total_qty(e){gsap.to(this.$data,{duration:.6,animated_total_qty:e})},total_order_raw(e){gsap.to(this.$data,{duration:.6,animated_total_order:e})}},computed:{hasData(){if(this.search_found){return true}return false},hasResults(){if(this.data.length>0){return true}return false},hasRefund(){if(this.refund_transaction.length>0){return true}return false},animatedNumber(){return this.animated_total_qty.toFixed(0)},animatedTotal(){return this.animated_total_order.toFixed(2)},hasBooking(){if(Object.keys(this.order_table_data).length>0){return true}return false}},methods:{clearData(){this.search_found=false;this.q="";this.page=0;this.data=[];this.orderHistory()},orderHistory(){if(!this.load_more){this.loading=true}var e;var t=_();e="page="+this.page;e+=g();n[t]=p.ajax({url:ajaxurl+"/orderHistory",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.show_next_page=e.details.show_next_page;this.page=e.details.page;this.data.push(e.details.data.data);this.merchants=this.addObjectToArray(this.merchants,e.details.data.merchants);this.items=this.addObjectToArray(this.items,e.details.data.items);this.status=e.details.data.status;this.services=e.details.data.services;this.status_allowed_cancelled=e.details.data.status_allowed_cancelled;this.status_allowed_review=e.details.data.status_allowed_review}else{}});n[t].always(e=>{this.loading=false;this.load_more=false})},addObjectToArray(e,t){var a={};Object.entries(e).forEach(([e,t])=>{a[e]=t});Object.entries(t).forEach(([e,t])=>{a[e]=t});return a},loadMoreOrders(e){this.page=e;this.load_more=true;this.orderHistory()},orderDetails(e){this.show_details=true;var t={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:e};var a=1;t=JSON.stringify(t);n[a]=p.ajax({url:ajaxurl+"/orderDetails",method:"PUT",dataType:"json",data:t,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.order_loading=true;this.order_merchant=[];this.order_items=[];this.order_summary=[];this.order_info=[];this.order_status=[];this.order_services=[];this.order_label=[];if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.order_merchant=e.details.data.merchant;this.order_items=e.details.data.items;this.order_summary=e.details.data.summary;this.order_info=e.details.data.order.order_info;this.order_label=e.details.data.label;this.refund_transaction=e.details.data.refund_transaction;this.order_status=e.details.data.order.status;this.order_services=e.details.data.order.services;this.order_table_data=e.details.data.order_table_data;if(!f(this.order_services[this.order_info.service_code])){this.order_services=this.order_services[this.order_info.service_code]}if(!f(this.order_status[this.order_info.status])){this.order_status=this.order_status[this.order_info.status]}}});n[a].always(e=>{this.order_loading=false;this.updateImage()})},closeOrderDetails(){this.show_details=false},updateImage(){ee()},buyAgain(e,t){var a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),order_uuid:e,cart_uuid:T("cart_uuid")};var s=1;a=JSON.stringify(a);n[s]=p.ajax({url:ajaxurl+"/orderBuyAgain",method:"PUT",dataType:"json",data:a,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(n[s]!=null){n[s].abort()}}});n[s].done(e=>{if(e.code==1){S("cart_uuid",e.details.cart_uuid,30);if(t==1){this.show_details=false}D.getCartPreview();if(typeof D!=="undefined"&&D!==null){D.showCartPreview()}}else{Oa.alert(e.msg)}})},callCancel(e,t){this.$refs.orderCancelRef.cancel({merchant_name:t,order_uuid:e})},writeReview(e){u("writeReview"+e);this.$refs.ReviewRef.order_uuid=e;this.$refs.ReviewRef.show()},orderSummary(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content")};var t=_();e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/orderSummary",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{this.summary_data=e.details.summary;this.total_qty=e.details.summary.total_qty;this.total_order_raw=e.details.summary.total_order_raw>0?e.details.summary.total_order_raw:0})}}});Ea.use(ElementPlus);const Pa=Ea.mount("#vue-my-order");const o=Vue.createApp({components:{"component-bootbox":Ye},data(){return{resolvePromise:undefined,rejectPromise:undefined}},methods:{confirm(){return new Promise((e,t)=>{this.resolvePromise=e;this.rejectPromise=t;this.$refs.bootbox.confirm()})},Callback(e){this.resolvePromise(e)},alert(e,t){this.$refs.bootbox.alert(e,t)}}}).mount("#vue-bootbox");const Fa=Vue.createApp({components:{"component-new-address":Pe,"component-address":Fe},mounted(){this.getAddresses(false)},data(){return{data:[],error:[],is_loading:false,reload_loading:false,total_address:0,total_address_animated:0}},watch:{total_address(e){gsap.to(this.$data,{duration:.5,total_address_animated:e})}},computed:{animatedTotal(){return this.total_address_animated.toFixed(0)}},methods:{getAddresses(t){this.reload_loading=t;var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content")};var a=_();e=JSON.stringify(e);n[a]=p.ajax({url:ajaxurl+"/getAddresses",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(!t){this.is_loading=true}else{this.reload_loading=true}if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.data=e.details.data;this.total_address=this.data.length}else{this.data=[];this.total_address=0}});n[a].always(e=>{this.is_loading=false;this.reload_loading=false})},showNewAddress(){this.$refs.refnewaddress.autosaved_addres=false;this.$refs.refnewaddress.showModal()},ShowAddress(e){this.$refs.address.show(e)},setLocationDetails(e){this.$refs.address.showWithData(e)},ConfirmDelete(e){this.$refs.address.ConfirmDelete(e)}}});Fa.use(ElementPlus);const Na=Fa.mount("#vue-my-address");const Ra={props:["label","payment_type","show_payment"],data(){return{data:[],display:false}},mounted(){this.getPaymentMethod();this.display=this.show_payment},methods:{getPaymentMethod(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),payment_type:this.payment_type};var t=_();e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/PaymentMethod",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:function(e){if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.data=e.details.data}else{this.data=[]}})},showPayment(e){this.$emit("setPayment",e)}},template:`
	<!--PAYMENT METHODS-->
	<transition name="slide-fade">
	<div class="card mb-3" v-if="display" >
		<div class="card-body">
		
		<h5 class="mb-3">{{label.add_new_payment}}</h5>
		
		<a  v-for="payment in data" @click="showPayment(payment.payment_code)"
		href="javascript:;" class="d-block chevron-section medium d-flex align-items-center rounded mb-2">
		    <div class="flexcol mr-2 payment-logo-wrap">
		      <i v-if="payment.logo_type=='icon'" :class="payment.logo_class"></i>
	 		  <img v-else class="img-35" :src="payment.logo_image" />  
		    </div>
		    
		    <div class="flexcol" > 		     		     		      
		       <span class="mr-1">{{payment.payment_name}}</span>          
		    </div> 		    		    		    
		 </a> 
		
		</div> <!--card body-->
	</div> <!--card-->
	</transition>
	<!--END PAYMENT METHODS-->
	`};const Da=Vue.createApp({data(){return{data:[],default_payment_uuid:"",error:[],is_loading:false,reload_loading:false,payment_method:false}},mounted(){this.getMyPayments(false)},watch:{payment_method(e,t){this.$refs.payment_method.display=e}},methods:{getMyPayments(t){this.reload_loading=t;var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content")};var a=_();e=JSON.stringify(e);n[a]=p.ajax({url:ajaxurl+"/MyPayments",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(!t){this.is_loading=true}else{this.reload_loading=true}if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.data=e.details.data;this.default_payment_uuid=e.details.default_payment_uuid}else{this.data=[];this.default_payment_uuid=""}});n[a].always(e=>{this.is_loading=false;this.reload_loading=false})},ConfirmDelete(t){o.confirm().then(e=>{if(e){this.deletePayment(t)}})},deletePayment(e){var t={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),payment_uuid:e};var a=_();t=JSON.stringify(t);n[a]=p.ajax({url:ajaxurl+"/deletePayment",method:"PUT",dataType:"json",data:t,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.getMyPayments(true)}else{o.alert(e.msg,{})}});n[a].always(e=>{})},editPayment(e){this.$refs[e.payment_code].Get(e.reference_id)},SavedPaymentList(){this.getMyPayments(true)},showPayment(e){try{this.$refs[e].showPaymentForm()}catch(t){o.alert(t,{})}},setDefaultPayment(e){var t={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),payment_uuid:e};var a=1;t=JSON.stringify(t);n[a]=p.ajax({url:ajaxurl+"/setDefaultPayment",method:"PUT",dataType:"json",data:t,contentType:d.json,timeout:c,crossDomain:true,beforeSend:function(e){if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.getMyPayments(true)}else{o.alert(e.msg,{})}})}}});if(typeof components_bundle!=="undefined"&&components_bundle!==null){p.each(components_bundle,function(e,t){Da.component(e,t)})}Da.component("components-payment-method",Ra);Da.use(Maska);Da.use(ElementPlus);const Ya=Da.mount("#vue-my-payments");const Ma=Vue.createApp({components:{"component-save-store":tt},data(){return{data:[],is_loading:false,reload_loading:false,services:[],estimation:[]}},mounted(){this.saveStoreList(false)},updated(){u("updated");p(".list-items .lazy").each(function(e,t){if(!f(p(this).attr("src"))&&!f(p(this).attr("data-src"))){p(this).attr("src",p(this).attr("data-src"))}})},methods:{saveStoreList(t){this.reload_loading=t;var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content")};var a=_();e=JSON.stringify(e);n[a]=p.ajax({url:ajaxurl+"/saveStoreList",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(!t){this.is_loading=true}else{this.reload_loading=true}if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.data=e.details.data;this.services=e.details.services;this.estimation=e.details.estimation}else{this.data=[];this.services=[];this.estimation=[]}});n[a].always(e=>{this.is_loading=false;this.reload_loading=false})},afterSaveStore(e){e.saved_store=e.saved_store==1?false:true}}});Ma.use(ElementPlus);const Aa=Ma.mount("#vue-saved-store");const $a={props:["label","modelValue"],emits:["update:modelValue"],data(){return{q:"",awaitingSearch:false,show_list:false,data:[],error:[],hasSelected:false}},watch:{q(e,t){this.$emit("update:modelValue",e);if(!this.awaitingSearch){if(f(e)){return false}if(this.q.length>20){return false}this.show_list=true;setTimeout(()=>{var e;var t=2;e="q="+this.q;e+=g();n[t]=p.ajax({url:ajaxurl+"/getlocation_autocomplete",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.data=e.details.data}else{this.data=[]}});n[t].always(e=>{this.awaitingSearch=false})},1e3)}this.data=[];this.awaitingSearch=true}},methods:{showList(){this.show_list=true},clearData(){this.data=[];this.q=""},setData(e){this.clearData();this.q=e.description;this.$emit("update:modelValue",e.description);this.$emit("afterChoose",e)}},computed:{hasData(){if(this.data.length>0){return true}return false}},template:`		
	<div class="position-relative search-geocomplete"> 	
	   <div v-if="!awaitingSearch" class="img-20 m-auto pin_placeholder icon"></div>
	   <div v-if="awaitingSearch" class="icon" data-loader="circle"></div>    
	   	   
	   <input @click="showList" v-model="q" class="form-control form-control-text" 
	   :placeholder="label.enter_address" >
	   
	   <div v-if="hasData" @click="clearData" class="icon-remove"><i class="zmdi zmdi-close"></i></div>
	   
	  </div>
	
	   <div class="search-geocomplete-results" v-if="show_list">		      
	    <ul class="list-unstyled m-0 border">
	     <li v-for="items in data">			      
	      <a href="javascript:;" @click="setData(items)" >
	       <h6 class="m-0">{{items.description}}</h6>	       
	      </a>
	     </li>	     
	    </ul>
   </div>
	`};const Ka=Vue.createApp({components:{"component-auto-complete":$a,"component-phone":e,"vue-recaptcha":De},data(){return{data:[],loading:false,mobile_prefixes:[],restaurant_name:"",address:"",contact_email:"",mobile_prefix:"",mobile_number:"",membership_type:"",membership_list:[],response:[],recaptcha_response:"",show_recaptcha:true,membership_commission:[],services_list:[],services:[],currency_list:[],currency:""}},mounted(){this.getSignupAttributes()},computed:{checkForm(){if(f(this.restaurant_name)){return true}if(f(this.address)){return true}if(f(this.contact_email)){return true}if(!this.validEmail(this.contact_email)){return true}if(f(this.mobile_number)){return true}if(f(this.mobile_prefix)){return true}if(f(this.membership_type)){return true}return false}},methods:{getSignupAttributes(){var e;var t=_();e+=g();n[t]=p.ajax({url:ajaxurl+"/getSignupAttributes",method:"POST",dataType:"json",data:e,contentType:d.form,timeout:c,crossDomain:true,beforeSend:function(e){if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.mobile_prefixes=e.details.mobile_prefixes;this.membership_list=e.details.membership_list;this.show_recaptcha=e.details.capcha;this.membership_commission=e.details.membership_commission;this.services_list=e.details.services_list;this.currency_list=e.details.currency_list}else{this.mobile_prefixes=[];this.membership_list="";this.show_recaptcha=false;this.membership_commission=[];this.services_list=[];this.currency_list=[]}})},verifyForms(){if(this.show_recaptcha){this.verifyRecaptcha()}else{this.submit()}},verifyRecaptcha(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),recaptcha_response:this.recaptcha_response};var t=11;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/verifyRecaptcha",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.submit()}else if(e.code==3){this.recaptchaExpired();this.response.code=2;this.response.msg=e.msg}else{this.response=e}});n[t].always(e=>{this.loading=false})},submit(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),restaurant_name:this.restaurant_name,address:this.address,contact_email:this.contact_email,mobile_prefix:this.mobile_prefix,mobile_number:this.mobile_number,membership_type:this.membership_type,services:this.services,currency:this.currency};var t=22;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/CreateAccountMerchant",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{this.response=e;if(e.code==1){window.location.href=e.details.redirect}});n[t].always(e=>{this.loading=false})},validEmail:function(e){var t=/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;return t.test(e)},recaptchaVerified(e){this.recaptcha_response=e},recaptchaExpired(){this.$refs.vueRecaptcha.reset()},recaptchaFailed(){},replaceText(e,t,a){let s=e.replace("{transaction_type}",t);s=s.replace("{site_name}",a);return s}}});Ka.use(Maska);Ka.use(ElementPlus);const za=Ka.mount("#vue-merchant-signup");const La=Vue.createApp({components:{"component-phone":e,"vue-recaptcha":De},data(){return{is_loading:false,show_password:false,agree_terms:false,password_type:"password",first_name:"",last_name:"",contact_email:"",mobile_number:"",mobile_prefix:"",password:"",cpassword:"",mobile_prefixes:[],error:[],success:"",redirect:"",next_url:"",recaptcha_response:"",show_recaptcha:false,merchant_uuid:"",username:""}},mounted(){this.merchant_uuid=_merchant_uuid},computed:{DataValid(){if(!f(this.first_name)&&f(!this.last_name)&&f(!this.contact_email)&&f(!this.mobile_number)&&f(!this.password)&&f(!this.cpassword)&&f(!this.username)){return true}return false}},methods:{showPassword(){this.show_password=this.show_password==true?false:true;if(this.show_password){this.password_type="text"}else this.password_type="password"},onRegister(){this.is_loading=true;this.error=[];axios({method:"PUT",url:ajaxurl+"/createMerchantUser",data:{YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),username:this.username,password:this.password,cpassword:this.cpassword,first_name:this.first_name,last_name:this.last_name,contact_email:this.contact_email,mobile_number:this.mobile_number,mobile_prefix:this.mobile_prefix,merchant_uuid:this.merchant_uuid},timeout:c}).then(e=>{if(e.data.code==1){window.location.href=e.data.details.redirect}else{this.data=[];this.error=e.data.msg}})["catch"](e=>{}).then(e=>{this.is_loading=false})}}});La.use(Maska);const qa=La.mount("#vue-merchant-user");const Va={props:["label","payment_type","ajax_url","actions"],data(){return{is_loading:false,data:[],payment_code:""}},mounted(){this.getPaymentMethod()},methods:{getPaymentMethod(){this.is_loading=true;axios({method:"POST",url:this.ajax_url+"/"+this.actions,data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:c}).then(e=>{if(e.data.code==1){this.data=e.data.details}else{this.data=[]}})["catch"](e=>{}).then(e=>{this.is_loading=false})},isActive(e){return{"border-vertical-green":e.payment_code==this.payment_code}},setPayment(e){this.payment_code=e;this.$emit("setPayment",this.payment_code)}},template:`
	<!--PAYMENT METHODS-->	
	<div class="card mb-3" >
		<div class="card-body">
		
		<h4 class="mb-3">{{label.select_payment}}</h4>
				
		<a  v-for="payment in data" 
		@click="setPayment(payment.payment_code)"
		:class="isActive(payment)"
		class="d-block chevron-section medium d-flex align-items-center rounded mb-2">
		    <div class="flexcol mr-2 payment-logo-wrap">
		      <i v-if="payment.logo_type=='icon'" :class="payment.logo_class"></i>
	 		  <img v-else class="img-35" :src="payment.logo_image" />  
		    </div>
		    
		    <div class="flexcol" > 		     		     		      
		       <span class="mr-1">{{payment.payment_name}}</span>          
		    </div> 		    		    		    
		 </a> 
		
		</div> <!--card body-->
	</div> <!--card-->	
	<!--END PAYMENT METHODS-->
	`};const Ua=Vue.createApp({data(){return{data:[],plan_details:[],is_loading:true}},mounted(){this.getPlan()},methods:{getPlan(){this.is_loading=true;axios({method:"POST",url:ajaxurl+"/getPlan",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")}).then(e=>{if(e.data.code==1){this.data=e.data.details.data;this.plan_details=e.data.details.plan_details}else{this.data=[];this.plan_details=[]}})["catch"](e=>{}).then(e=>{this.is_loading=false})}}});Ua.use(ElementPlus);const Ja=Ua.mount("#vue-subscription");const Ba=Vue.createApp({data(){return{data:[],loading:false,merchant_uuid:null,package_uuid:null,plan_details:[],payment_list:[],payment_code:""}},mounted(){if(typeof plan_merchant_uuid!=="undefined"&&plan_merchant_uuid!==null){this.merchant_uuid=plan_merchant_uuid}if(typeof plan_uuid!=="undefined"&&plan_uuid!==null){this.package_uuid=plan_uuid}this.getSelectPaymentPlan()},computed:{hasPaymentSelected(){return f(this.payment_code)?false:true}},methods:{isActive(e){return this.payment_code==e?true:false},getSelectPaymentPlan(){this.loading=true;axios({method:"POST",url:ajaxurl+"/getSelectPaymentPlan",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&plan_uuid="+this.package_uuid}).then(e=>{if(e.data.code==1){this.plan_details=e.data.details.plan_details;this.payment_list=e.data.details.payment_list}else{this.plan_details=[];this.payment_list=[]}})["catch"](e=>{}).then(e=>{this.loading=false})},showPayment(){console.debug("this.payment_code",this.payment_code);try{this.$refs[this.payment_code].showPaymentForm()}catch(e){console.debug("error",e);ElementPlus.ElNotification({title:"",message:"This payment is not available for plan",position:"bottom-right",type:"warning"})}}}});if(typeof components_bundle!=="undefined"&&components_bundle!==null){console.debug("components_bundle",components_bundle);p.each(components_bundle,function(e,t){Ba.component(e,t)})}Ba.use(ElementPlus);const Ga=Ba.mount("#payment-plan");const Wa=Vue.createApp({components:{"component-phone":e,"component-change-phoneverify":ta},data(){return{is_loading:false,data:[],first_name:"",last_name:"",email_address:"",mobile_prefix:"",mobile_number:"",original_email_address:"",original_mobile_number:"",error:[],success:""}},computed:{DataValid(){if(!f(this.first_name)&&f(!this.last_name)&&f(!this.email_address)&&f(!this.mobile_number)){return true}return false}},mounted(){this.getProfile()},methods:{getProfile(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content")};var t=11;e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/getProfile",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.first_name=e.details.first_name;this.last_name=e.details.last_name;this.email_address=e.details.email_address;this.mobile_prefix=e.details.mobile_prefix;this.mobile_number=e.details.mobile_number;this.original_email_address=e.details.email_address;this.original_mobile_number=e.details.mobile_number}else{this.msg=e.msg}});n[t].always(e=>{this.is_loading=false})},checkForm(){var e=false;if(this.email_address!=this.original_email_address){e=true}if(this.mobile_number!=this.original_mobile_number){e=true}this.success="";this.error=[];if(e){this.$refs.cphoneverify.show({mobile_prefix:this.mobile_prefix,mobile_number:this.mobile_number,message:_message});this.$refs.cphoneverify.resendCode()}else{this.saveProfile("")}},saveProfile(t){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),code:t,first_name:this.first_name,last_name:this.last_name,email_address:this.email_address,mobile_prefix:this.mobile_prefix,mobile_number:this.mobile_number};var a=_();e=JSON.stringify(e);n[a]=p.ajax({url:ajaxurl+"/saveProfile",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(!f(t)){this.$refs.cphoneverify.is_loading=true}else{this.is_loading=true}if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.$refs.cphoneverify.close();this.success=e.msg;this.original_email_address=this.email_address;this.original_mobile_number=this.mobile_number}else{if(!f(t)){this.$refs.cphoneverify.error=e.msg}else this.error=e.msg}});n[a].always(e=>{if(!f(t)){this.$refs.cphoneverify.is_loading=false}else{this.is_loading=false}})}}});Wa.use(Maska);const Za=Wa.mount("#vue-update-profile");const Qa=Vue.createApp({components:{"component-change-phoneverify":ta},data(){return{is_loading:false,data:[],error:[],success:"",old_password:"",new_password:"",confirm_password:""}},computed:{DataValid(){if(!f(this.old_password)&&f(!this.new_password)&&f(!this.confirm_password)){return true}return false}},methods:{updatePassword(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),old_password:this.old_password,new_password:this.new_password,confirm_password:this.confirm_password};var t=_();e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/updatePassword",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error="";this.success="";if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.success=e.msg;this.old_password="";this.new_password="";this.confirm_password=""}else this.error=e.msg});n[t].always(e=>{this.is_loading=false})}}}).mount("#vue-update-password");const Ha=Vue.createApp({components:{"component-change-phoneverify":ta},data(){return{is_loading:false,error:[],success:"",steps:1,steps_request_data:1}},methods:{requestArchive(){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content")};var t=_();e=JSON.stringify(e);n[t]=p.ajax({url:ajaxurl+"/requestData",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.steps_request_data=1;this.error="";if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.steps_request_data=2}else{this.error=e.msg}});n[t].always(e=>{this.is_loading=false})},confirm(){this.$refs.cphoneverify.show({mobile_prefix:_phone_prefix,mobile_number:_contact_phone,message:_message});this.$refs.cphoneverify.resendCode()},verifyAccountDelete(t){var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),code:t};var a=_();e=JSON.stringify(e);n[a]=p.ajax({url:ajaxurl+"/verifyAccountDelete",method:"PUT",dataType:"json",data:e,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.$refs.cphoneverify.is_loading=true;this.$refs.cphoneverify.error=[];if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.$refs.cphoneverify.close();o.confirm().then(e=>{if(e){this.deleteAccount(t)}})}else this.$refs.cphoneverify.error=e.msg});n[a].always(e=>{this.$refs.cphoneverify.is_loading=false})},deleteAccount(e){var t={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),code:e};var a=_();t=JSON.stringify(t);n[a]=p.ajax({url:ajaxurl+"/deleteAccount",method:"PUT",dataType:"json",data:t,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;this.error="";this.success="";if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){this.steps=2;setTimeout(()=>{window.location.href=e.details.redirect},4e3)}else o.alert(e.msg,{})});n[a].always(e=>{this.is_loading=false})}}});Ha.use(Maska);const Xa=Ha.mount("#vue-manage-account");const es={components:{Cropper:VueAdvancedCropper.Cropper},props:["label"],data(){return{upload_images:"",filename:"",required_message:[],is_loading:false,error:[],steps:1,photo:undefined}},mounted(){this.initDropZone(this.label,this)},methods:{show(){this.steps=1;p("#photoModal").modal("show")},close(){p("#photoModal").modal("hide")},closeReset(){this.reset();p("#photoModal").modal("hide")},reset(){Dropzone.forElement("#dropzone_multiple").removeAllFiles(true);this.removeUpload(this.filename);this.upload_images=[];this.filename="";this.error=[]},initDropZone(t,e){var a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content")};Dropzone.autoDiscover=false;$e=p(this.$refs.profile_dropzone).dropzone({dictDefaultMessage:t.dictDefaultMessage,dictFallbackMessage:t.dictFallbackMessage,dictFallbackText:t.dictFallbackText,dictFileTooBig:t.dictFileTooBig,dictInvalidFileType:t.dictInvalidFileType,dictResponseError:t.dictResponseError,dictCancelUpload:t.dictCancelUpload,dictCancelUploadConfirmation:t.dictCancelUploadConfirmation,dictRemoveFile:t.dictRemoveFile,dictMaxFilesExceeded:t.dictMaxFilesExceeded,paramName:"file",url:ajaxurl+"/uploadProfilePhoto",maxFiles:20,params:a,addRemoveLinks:true,maxFiles:2,init:function(){this.on("maxfilesexceeded",function(e){o.alert(t.max_file_exceeded,{})});this.on("removedfile",function(e){})},success:(e,t)=>{var a=JSON.parse(t);if(a.code==1){this.upload_images=a.details.url_image;this.filename=a.details.filename;this.steps=2}else{o.alert(a.msg,{});this.reset()}}})},removeUpload(i){return new Promise((t,e)=>{var a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),id:i};var s=_();n[s]=p.ajax({url:ajaxurl+"/removeProfilePhoto",method:"PUT",dataType:"json",data:JSON.stringify(a),contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;if(n[s]!=null){n[s].abort();this.is_loading=false}}});n[s].done(e=>{this.is_loading=false;t(true);if(e.code==1){}});n[s].always(e=>{this.is_loading=false})})},change({coordinates:e,canvas:t}){this.photo=t.toDataURL("image/png")},save(){this.$emit("onSave",this.photo,this.filename)}},template:`	
    <div class="modal" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModal" aria-hidden="true" 
     data-backdrop="static" data-keyboard="false"  >
       <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content">  
       
       <div class="modal-body" style="overflow-y:inherit !important;" > 
       
          <a href="javascript:;" @click="closeReset" 
          class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a>        
       
         <template :class="{ 'd-block': steps==1 }">  
         <h5 class="m-0 mt-3 mb-2">{{label.add_photo}}</h5>      
	     <div class="mb-3">
	         <div class="dropzone dropzone_container" id="dropzone_multiple" ref="profile_dropzone" >
			   <div class="dz-default dz-message">
			    <i class="fas fa-cloud-upload-alt"></i>
			     <p>{{label.drop_files_here}}</p>
			    </div>
			</div> 
	     </div>	    
	    </template>
	    
	    <template v-if="steps==2">
	      <h5 class="m-0 mt-3 mb-2">{{label.crop_photo}}</h5>  
	      
	      <div class="crop-images-wrap">
	        <cropper 
		    :src="upload_images"		    
		    @change="change"
		    :stencil-props="{
				handlers: {},
				movable: false,
				scalable: false,
			}"
			:stencil-size="{
				width: 120,
				height: 120
			}"
			image-restriction="stencil"
		  ></cropper>
		  </div>
	      
	    </template>
              
       
       </div> <!--modal body-->
       
       <template v-if="steps==2">
       
       <div v-cloak v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
		    <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
	   </div> 
       
        <div class="modal-footer justify-content-start">
            <button class="btn btn-green w-100" @click="save" :class="{ loading: is_loading }" >               
               <span class="label">{{label.save_photo}}</span>
               <div class="m-auto circle-loader" data-loader="circle-side"></div>
            </button>
        </div> <!--footer-->
        </template>
       
       </div> <!--content-->
	  </div> <!--dialog-->
	</div> <!--modal-->     
  `};const ts=Vue.createApp({components:{"components-profile-photo":es},data(){return{avatar:""}},mounted(){u("mounted")},methods:{showUpload(){this.$refs.profile_photo.show()},SavePhoto(e,t){var a;var s=1;a="photo="+e;a+="&filename="+t;a+=g();n[s]=p.ajax({url:ajaxurl+"/saveProfilePhoto",method:"POST",dataType:"json",data:a,contentType:d.form,timeout:c,crossDomain:true,beforeSend:e=>{this.$refs.profile_photo.is_loading=true;if(n[s]!=null){n[s].abort()}}});n[s].done(e=>{if(e.code==1){this.$refs.profile_photo.close();this.avatar=e.details.url_image;this.updateImage()}else{this.$refs.profile_photo.error=e.msg}});n[s].always(e=>{this.$refs.profile_photo.is_loading=false})},updateImage(){var e=this.$refs.refavatar.getElementsByTagName("img");e[0].src=this.avatar}}});ts.use(ElementPlus);const as=ts.mount("#vue-profile-photo");const ss=Vue.createApp({components:{"component-select-time":nt},data(){return{store_close:false,time_required:false,time_required_message:"",time_selection:1}},mounted(){},methods:{checkStoreOpen(){var e=0;if(typeof merchant_id!=="undefined"&&merchant_id!==null){e=merchant_id}var t=!f(T("choosen_delivery"))?JSON.parse(T("choosen_delivery")):[];var a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_id:e,cart_uuid:T("cart_uuid"),choosen_delivery:t};var s=_();a=JSON.stringify(a);n[s]=p.ajax({url:ajaxurl+"/checkStoreOpen",method:"PUT",dataType:"json",data:a,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(n[s]!=null){n[s].abort()}}}).done(e=>{if(e.code==1){if(e.details.merchant_open_status<=0){this.store_close=true;N.error.push(e.msg)}else{this.store_close=false}this.time_required=e.details.time_required;this.time_selection=e.details.time_selection;if(this.time_required){N.error.push(e.details.time_required_message)}if(e.details.time_already_passed){S("choosen_delivery",[],30)}}else{this.store_close=true;N.error.push(e.msg)}}).always(e=>{})},show(){this.$refs.select_time.show()},afterSaveTransOptions(e){this.$refs.select_time.close();if(typeof t!=="undefined"&&t!==null){t.whento_deliver=e.whento_deliver;t.delivery_date=e.delivery_date;t.delivery_time=e.delivery_time;t.Search(false)}if(typeof N!=="undefined"&&N!==null){N.loadcart()}if(typeof a!=="undefined"&&a!==null){a.$refs.transaction_info.whento_deliver=e.whento_deliver;a.$refs.transaction_info.delivery_datetime=e.delivery_datetime}},storeAvailable(){var e="";if(typeof merchant_uuid!=="undefined"&&merchant_uuid!==null){e=merchant_uuid}axios({method:"POST",url:ajaxurl+"/storeAvailable",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&merchant_uuid="+e,timeout:c}).then(e=>{if(e.data.code==1){}else{N.error.push(e.data.msg)}})["catch"](e=>{}).then(e=>{})}}}).mount("#vue-schedule-order");const is=Vue.createApp({data(){return{visible:false,include_utensils:false,is_loading:false}},watch:{include_utensils(e,t){V("include_utensils",e);this.setUtensils(e)}},mounted(){this.setUtensils(U("include_utensils"))},methods:{setTransaction(e){if(e=="delivery"){this.visible=true}else this.visible=false},setUtensils(e){e=f(e)?false:e;this.include_utensils=e}}}).mount("#vue-utensils");const os={props:["title","merchant_id","settings","image_use","responsive"],components:{"money-format":ze},data(){return{owl:undefined,menu_data:[],items:[],item_addons:[],item_addons_load:false,size_id:0,disabled_cart:true,item_qty:1,item_total:0,add_to_cart:false,meta:[],special_instructions:"",sold_out_options:[],if_sold_out:"substitute",transaction_type:"",item_loading:false,item_gallery:[],image_featured:"",money_config:""}},mounted(){this.itemSuggestion()},updated(){if(this.item_addons_load==true){this.ItemSummary();p(".item_s .lazy").each(function(e,t){if(!f(p(this).attr("src"))&&!f(p(this).attr("data-src"))){p(this).attr("src",p(this).attr("data-src"))}})}if(typeof Xt!=="undefined"&&Xt!==null){this.transaction_type=Xt.transaction_type}},computed:{getItemGallery(){return this.item_gallery}},methods:{itemSuggestion(){let e=T("currency_code");e=!f(e)?e:"";var t={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_id:this.merchant_id,image_use:this.image_use,currency_code:e};var a=_();t=JSON.stringify(t);n[a]=p.ajax({url:ajaxurl+"/geStoreMenu",method:"PUT",dataType:"json",data:t,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.is_loading=true;if(n[a]!=null){n[a].abort()}}}).done(e=>{if(e.code==1){var t=e.details.data.category;var a=e.details.data.items;var s=[];var i=[];if(t.length>0){p.each(t,function(e,t){p.each(t.items,function(e,t){i.push(a[t])});s.push({cat_id:t.cat_id,category_name:t.category_name,category_uiid:t.category_uiid,items:i});i=[]})}this.menu_data=s;setTimeout(()=>{this.RenderCarousel()},10)}else this.menu_data=[]}).always(e=>{this.is_loading=false})},RenderCarousel(){let e=false;if(typeof is_rtl!=="undefined"&&is_rtl!==null){e=is_rtl=="1"?true:false}this.owl=p("#item-suggestion-list").owlCarousel({items:parseInt(this.settings.items),lazyLoad:this.settings.lazyLoad=="1"?true:false,loop:this.settings.loop=="1"?true:false,margin:parseInt(this.settings.margin),nav:this.settings.nav=="1"?true:false,dots:this.settings.dots=="1"?true:false,stagePadding:parseInt(this.settings.stagePadding),autoWidth:true,rtl:e})},SlideNext(){this.owl.trigger("next.owl.carousel")},SlidePrev(){this.owl.trigger("prev.owl.carousel",[300])},viewItem(e){this.item_loading=true;NProgress.start();let t=T("currency_code");t=!f(t)?t:"";var a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_id:this.merchant_id,item_uuid:e.item_uuid,cat_id:e.cat_id,currency_code:t};var s=1;a=JSON.stringify(a);n[s]=p.ajax({url:ajaxurl+"/getMenuItem",method:"PUT",dataType:"json",data:a,contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{if(n[s]!=null){n[s].abort()}}}).done(e=>{if(e.code==1){j=e.details.data.items;E=e.details.data.addons;P=e.details.data.addon_items;Ct=e.details.data.items.item_addons;this.money_config=e.details.money_config;this.item_gallery=e.details.data.meta.item_gallery;this.image_featured="";this.renderGallery();var t=e.details.data.meta;var i=e.details.data.meta_details;var o={cooking_ref:[],ingredients:[],dish:[]};let s=e.details.data.items.ingredients_preselected;if(!f(t)){p.each(t,function(a,e){p.each(e,function(e,t){if(!f(i[a])){if(!f(i[a][t])){let e=false;if(a=="ingredients"&&s){e=true}o[a].push({meta_id:i[a][t].meta_id,meta_name:i[a][t].meta_name,checked:e})}}})})}var a=j.price;var r=Object.keys(a)[0];this.item_qty=1;this.items=j;this.size_id=r;this.meta=o;this.getSizeData(r);this.sold_out_options=e.details.sold_out_options;p(this.$refs.modal_item_details).modal("show");this.updateImage()}}).always(e=>{this.item_loading=false;NProgress.done()})},renderGallery(){setTimeout(()=>{let e=false;if(typeof is_rtl!=="undefined"&&is_rtl!==null){e=is_rtl=="1"?true:false}p(this.$refs.owl_item_gallery).owlCarousel({loop:false,margin:10,nav:false,dots:false,rtl:e,responsive:{0:{items:1},600:{items:3},1e3:{items:5}}})},10)},setImage(e){this.image_featured=e},updateImage(){ee()},setItemSize(e){var t=e.currentTarget.firstElementChild.value;this.size_id=t;this.getSizeData(t)},getSizeData(a){Ot=[];var s=[];if(!f(Ct[a])){p.each(Ct[a],function(e,t){if(!f(E[a])){if(!f(E[a][t])){E[a][t].subcat_id;p.each(E[a][t].sub_items,function(e,t){s.push({sub_item_id:P[t].sub_item_id,sub_item_name:P[t].sub_item_name,item_description:P[t].item_description,price:P[t].price,pretty_price:P[t].pretty_price,checked:false,disabled:false,qty:1})});Ot.push({subcat_id:E[a][t].subcat_id,subcategory_name:E[a][t].subcategory_name,subcategory_description:E[a][t].subcategory_description,multi_option:E[a][t].multi_option,multi_option_min:E[a][t].multi_option_min,multi_option_value:E[a][t].multi_option_value,require_addon:E[a][t].require_addon,pre_selected:E[a][t].pre_selected,sub_items_checked:"",sub_items:s});s=[]}}})}this.item_addons=Ot;this.item_addons_load=true},ItemSummary(e){y=0;var l=[];var n=[];let c=[];let m=[];if(!f(this.items.price)){if(!f(this.items.price[this.size_id])){var t=this.items.price[this.size_id];if(t.discount>0){y+=this.item_qty*parseFloat(t.price_after_discount)}else y+=this.item_qty*parseFloat(t.price)}}u("item_total=>"+y);this.item_addons.forEach((a,s)=>{if(a.require_addon==1){l.push(a.subcat_id)}if(a.multi_option=="custom"){var i=0;let e=a.multi_option_min;var t=a.multi_option_value;var o=[];var r=[];if(t>0){c.push({subcat_id:a.subcat_id,min:e,max:t})}a.sub_items.forEach((e,t)=>{if(e.checked==true){i++;y+=this.item_qty*parseFloat(e.price);n.push(a.subcat_id)}else o.push(t);if(e.disabled==true){r.push(t)}});m[a.subcat_id]={total:i};if(i>=t){o.forEach((e,t)=>{this.item_addons[s].sub_items[e].disabled=true})}else{r.forEach((e,t)=>{this.item_addons[s].sub_items[e].disabled=false})}}else if(a.multi_option=="one"){a.sub_items.forEach((e,t)=>{if(e.sub_item_id==a.sub_items_checked){y+=this.item_qty*parseFloat(e.price);n.push(a.subcat_id)}})}else if(a.multi_option=="multiple"){var o=[];let e=a.multi_option_min;var t=a.multi_option_value;var d=0;if(t>0){c.push({subcat_id:a.subcat_id,min:e,max:t})}a.sub_items.forEach((e,t)=>{if(e.checked==true){y+=e.qty*parseFloat(e.price);n.push(a.subcat_id);d+=e.qty}o.push(t)});m[a.subcat_id]={total:d};this.item_addons[s].qty_selected=d;if(this.item_addons[s].qty_selected>=t){o.forEach((e,t)=>{this.item_addons[s].sub_items[e].disabled=true})}else{o.forEach((e,t)=>{this.item_addons[s].sub_items[e].disabled=false})}}});this.item_total=y;var i=true;if(l.length>0){p.each(l,function(e,t){if(n.includes(t)===false){i=false;return false}})}if(this.items.cooking_ref_required){let a=false;if(Object.keys(this.meta.cooking_ref).length>0){Object.entries(this.meta.cooking_ref).forEach(([e,t])=>{if(t.checked){a=true}})}if(!a){i=false}}if(Object.keys(c).length>0){let a,s;Object.entries(c).forEach(([e,t])=>{a=parseInt(t.min);if(m[t.subcat_id]){s=parseInt(m[t.subcat_id].total)}if(s>0){if(a>s){i=false}}})}if(i){this.disabled_cart=false}else this.disabled_cart=true},CheckaddCartItems(){this.addCartItems()},addCartItems(e){if(e){e.preventDefault()}this.add_to_cart=true;var t={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_id:this.items.merchant_id,cat_id:this.items.cat_id,item_token:this.items.item_token,item_size_id:this.size_id,item_qty:this.item_qty,item_addons:this.item_addons,special_instructions:this.special_instructions,meta:this.meta,cart_uuid:T("cart_uuid"),transaction_type:this.transaction_type,if_sold_out:this.if_sold_out};var a=1;t=JSON.stringify(t);n[a]=p.ajax({url:ajaxurl+"/addCartItems",method:"PUT",dataType:"json",data:t,contentType:d.json,timeout:c,crossDomain:true,beforeSend:function(e){if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){p(this.$refs.modal_item_details).modal("hide");N.loadcart()}});n[a].always(e=>{this.add_to_cart=false})}},template:"#item_suggestion"};const rs=Vue.createApp({components:{"components-item-suggestion":os},data(){return{data:[]}}});rs.use(ElementPlus);const ds=rs.mount("#vue-item-suggestion");const ls=Vue.createApp({components:{"component-change-address":Pe,"components-address-form":Be,"components-select-address":Ue},data(){return{addresses:[],address_needed:false,out_of_range:false,location_data:[],deliveryAddress:[],address_component:[]}},created(){this.addressNeeded()},methods:{addressNeeded(){axios({method:"POST",url:ajaxurl+"/addressNeeded",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&merchant_id="+merchant_id+"&cart_uuid="+T("cart_uuid"),timeout:c}).then(e=>{if(e.data.code==1){this.address_needed=e.data.details.address_needed}else{this.address_needed=false}})["catch"](e=>{}).then(e=>{})},show(){this.$refs.address.showModal()},afterChangeAddress(e){u("afterChangeAddress");u(e);u("isGuest=>"+isGuest);if(isGuest==="1"){this.deliveryAddress=e;this.$refs.address_modal.visible=true}else{u("not guest");this.location_data=e}},onSavelocation(e,t){u("onSavelocation here1");u(t);this.$refs.address_form.setLoading(true);let a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),data:e,location_name:t.location_name,delivery_instructions:t.delivery_instructions,delivery_options:t.delivery_options,address_label:t.address_label,formatted_address:t.formatted_address,address1:t.address1,address2:t.address2,postal_code:t.postal_code,company:t.company};axios({method:"PUT",url:ajaxurl+"/checkoutSaveAddress",data:a,timeout:c}).then(e=>{if(e.data.code==1){this.address_needed=false;this.$refs.address_form.close();this.refreshWidget()}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{ElementPlus.ElNotification({title:"",message:e,position:"bottom-right",type:"warning"})}).then(e=>{this.$refs.address_form.setLoading(false)})},afterPointaddress(e){u("afterPointaddress");u(e.place_id);this.$refs.address_modal.setLoading(true);axios({method:"POST",url:ajaxurl+"/setPlaceID",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&place_id="+e.place_id,timeout:c}).then(e=>{this.address_needed=false;this.refreshWidget();this.$refs.address_modal.visible=false})["catch"](e=>{ElementPlus.ElNotification({title:"",message:e,position:"bottom-right",type:"warning"})}).then(e=>{this.$refs.address_modal.setLoading(false)})},refreshWidget(){if(typeof a!=="undefined"&&a!==null){a.afterSetAddress()}else{if(typeof O!=="undefined"&&O!==null){O.$refs.ref_services.getMerchantServices()}}},afterChangeAddressOLD(e){this.location_data=e;this.visible=false;this.$refs.address.closeModal();if(typeof a!=="undefined"&&a!==null){a.afterChangeAddress()}if(typeof O!=="undefined"&&O!==null){O.$refs.ref_services.getMerchantServices()}}}});ls.use(ElementPlus);const Y=ls.mount("#vue-address-needed");const ns={template:"#xtemplate_webpushsettings",props:["ajax_url","settings","iterest_list","message"],data(){return{beams:undefined,is_loading:false,is_submitted:false,webpush_enabled:"",interest:[],device_id:""}},mounted(){this.initBeam()},methods:{initBeam(){this.beams=new PusherPushNotifications.Client({instanceId:this.settings.instance_id});this.is_loading=true;axios({method:"POST",url:this.ajax_url+"/getwebnotifications",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:c}).then(e=>{if(e.data.code==1){this.webpush_enabled=e.data.details.enabled==1?true:false;this.device_id=e.data.details.device_token;this.interest=e.data.details.interest}else{this.webpush_enabled=false;this.device_id="";this.interest=[]}})["catch"](e=>{}).then(e=>{this.is_loading=false})},enabledWebPush(){this.is_loading=true;if(this.webpush_enabled){this.beams.start().then(()=>{u("start");this.beams.getDeviceId().then(e=>{this.device_id=e})})["catch"](e=>{h(this.message.notification_start,"error")}).then(e=>{this.is_loading=false})}else{this.beams.stop().then(()=>{u("stop");this.device_id=""})["catch"](e=>{h(this.message.notification_stop,"error")}).then(e=>{this.is_loading=false})}},saveWebNotifications(){this.is_submitted=true;axios({method:"PUT",url:this.ajax_url+"/savewebnotifications",data:{YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),webpush_enabled:this.webpush_enabled,interest:this.interest,device_id:this.device_id},timeout:c}).then(e=>{if(e.data.code==1){h(e.data.msg)}else{h(e.data.msg,"error")}})["catch"](e=>{}).then(e=>{this.is_submitted=false})}}};const cs=Vue.createApp({components:{"components-web-pusher":ns}});const ms=cs.mount("#vue-webpush-settings");const us=Vue.createApp({data(){return{is_loading:false,data:[],page:0,show_next_page:false}},mounted(){this.getData(this.page)},computed:{hasData(){if(this.data.length>0){return true}return false},isFirstLoad(){if(this.is_loading&&this.page<=0){return true}return false}},methods:{getData(e){this.is_loading=true;axios({method:"POST",url:ajaxurl+"/notificationList",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&page="+e,timeout:c}).then(e=>{if(e.data.code==1){this.data.push(e.data.details.data);this.show_next_page=e.data.details.show_next_page;this.page=e.data.details.page}else{this.data=[]}})["catch"](e=>{}).then(e=>{this.is_loading=false})}}});us.use(ElementPlus);const hs=us.mount("#vue-notifications-list");const _s={props:["ajax_url","label","payment_type"],data(){return{data:[],is_loading:true,error_message:""}},mounted(){this.getPaymentList()},computed:{hasData(){if(this.data.length>0){return true}return false}},methods:{getPaymentList(){this.is_loading=true;axios({method:"POST",url:this.ajax_url+"/getPaymentList",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&payment_type="+this.payment_type,timeout:c}).then(e=>{if(e.data.code==1){this.data=e.data.details.data;this.error_message=""}else{this.data=[];this.error_message=e.data.msg}})["catch"](e=>{}).then(e=>{this.is_loading=false})}},template:`	    
    <DIV class="position-relative">
     <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
	    <div>
	      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	    </div>
	 </div>   
    
    <h5 class="mb-3 mt-4">Add New Payment Method</h5>
	
	<template v-if="!is_loading && hasData">
	    here
		<a v-for="items in data" @click="$emit('afterClickpayment',items.payment_code)" 
		class="d-block chevron-section medium d-flex align-items-center rounded mb-2">
		<div class="flexcol mr-2 payment-logo-wrap">
			<i v-if="items.logo_type=='icon'" :class="items.logo_class"></i>
			<img v-else class="img-35" :src="items.logo_image" /> 		      
		</div>
		<div class="flexcol">
			<span class="mr-1">{{items.payment_name}}</span>
		</div>
		</a>
	</template>	

	<template v-if="!is_loading && !hasData">
	   <div class="alert alert-warning mb-2" role="alert">
		<p class="m-0">{{error_message}}</p>
	   </div>
	</template>	
	
	
	</DIV>
    `};const ps={props:["ajax_url","merchant_uuid"],data(){return{data:[],default_payment_uuid:"",is_loading:false,message:""}},computed:{hasData(){if(this.data.length>0){return true}return false}},mounted(){this.MerchantSavedPayment()},methods:{MerchantSavedPayment(){this.is_loading=true;axios({method:"POST",url:this.ajax_url+"/MerchantSavedPayment",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&merchant_uuid="+this.merchant_uuid,timeout:c}).then(e=>{if(e.data.code==1){this.data=e.data.details.data;this.default_payment_uuid=e.data.details.default_payment_uuid;this.$emit("setDefaultpayment",e.data.details.default_payment)}else{this.data=[];this.message=e.data.msg}})["catch"](e=>{}).then(e=>{this.is_loading=false})},setDefaultPayment(e){axios({method:"POST",url:this.ajax_url+"/MerchantsetDefaultPayment",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&payment_uuid="+e.payment_uuid,timeout:c}).then(e=>{if(e.data.code==1){this.MerchantSavedPayment()}else{h(e.data.msg,"error")}})["catch"](e=>{}).then(e=>{})},deleteSavedPaymentMethod(e){axios({method:"POST",url:this.ajax_url+"/MerchantDeleteSavedPaymentMethod",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&payment_uuid="+e.payment_uuid,timeout:c}).then(e=>{if(e.data.code==1){this.MerchantSavedPayment()}else{h(e.data.msg,"error")}})["catch"](e=>{}).then(e=>{})}},template:`	    
    <DIV class="position-relative">
    
     <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
	    <div>
	      <div class="m-auto circle-loader medium" data-loader="circle-side"></div> 
	    </div>
	 </div>   
    
	     <template v-if="hasData">
	     <h5 class="mb-3">Saved Payment Methods</h5>
	          
	     <div v-for="saved_payment in data" class="row no-gutters align-items-center chevron-section medium rounded mb-2"  :class="{ selected: saved_payment.as_default==1 }" >
	             
		  <div class="col-md-8 d-flex align-items-center">
		    <div class="flexcol mr-2 payment-logo-wrap">
			      <i v-if="saved_payment.logo_type=='icon'" :class="saved_payment.logo_class"></i>
			      <img v-else class="img-35" :src="saved_payment.logo_image" /> 		      
			    </div> <!--flex-col-->
			    <div class="flexcol" > 		     		     		      
			       <span class=" mr-1">{{saved_payment.attr1}}</span>       
			       <p class="m-0 text-muted">{{saved_payment.attr2}}</p>   
			    </div> 		    		    		    
		  </div> <!--col-->
		  <div class="col-md-4 d-flex align-items-center justify-content-end">
		             
		     <template v-if="saved_payment.as_default==1">
		     <div class="mr-1"><i class="zmdi zmdi-check text-success"></i></div>
		     <div class="mr-3"><p class="m-0">Default</p></div>
		     </template>
		     
		     <div class="dropdown">
		     <a href="javascript:;" class="rounded-pill rounded-button-icon d-inline-block" 
		     id="dropdownMenuLink" data-toggle="dropdown" >
		       <i class="zmdi zmdi-more" style="font-size: inherit;"></i>
		     </a>
		         <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
				    <a  v-if="saved_payment.as_default!=1" 
		     @click="setDefaultPayment(saved_payment)"
		     class="dropdown-item a-12" href="javascript:;">Set Default</a>			    
				    <a @click="deleteSavedPaymentMethod(saved_payment)" class="dropdown-item a-12" href="javascript:;">
				    Delete
				    </a>				    
				  </div>
		     </div> <!--dropdown-->
		      
		  </div> <!--col-->
		 </div> <!--row-->	 	 
		 </template>	 
	 </DIV>
    `};const fs={props:["ajax_url","amount","label","default_payment","merchant_uuid"],data(){return{data:[],is_loading:false}},computed:{ValidAmount(){if(this.amount<=0){return true}if(f(this.default_payment.payment_uuid)){return true}return false}},mounted(){u(this.amount)},methods:{DoCashin(){bootbox.confirm({size:"small",title:this.label.confirm,message:this.label.are_you_sure,centerVertical:true,animate:false,buttons:{cancel:{label:this.label.cancel,className:"btn btn-black small pl-4 pr-4"},confirm:{label:this.label.yes,className:"btn btn-green small pl-4 pr-4"}},callback:e=>{if(e){var t={payment_uuid:this.default_payment.payment_uuid,payment_code:this.default_payment.payment_code,merchant_uuid:this.merchant_uuid,amount:this.amount};try{vs.$refs[this.default_payment.payment_code].DoPaymentRender(t,"PaymentIntentCashin")}catch(a){h(a.message,"error")}}}})}},template:`	        
     <button class="btn btn-green w-100 pointer mt-3"    
     :disabled="ValidAmount"
     @click="DoCashin"
	 >
	   <span class="label">{{label.continue}}</span>
	   <div class="m-auto circle-loader" data-loader="circle-side"></div>
	</button>                     
    `};const gs=Vue.createApp({data(){return{data:[],default_payment:""}},mounted(){u(this.amount_cashin)},methods:{afterClickpayment(e){try{this.$refs[e].showPaymentForm()}catch(t){h(t.message,"error")}},SavedPaymentList(){this.$refs.merchant_saved_payment.MerchantSavedPayment()},setDefaultpayment(e){this.default_payment=e},showLoadingBox(e){if(!f(e)){Ge.$refs.box.setMessage(e)}Ge.$refs.box.show()},closeLoadingBox(){Ge.$refs.box.close()},Alert(e){h(e,"error")}}});gs.component("components-payment-list",_s);gs.component("components-merchant-saved-payment",ps);gs.component("components-cashin-payment",fs);if(typeof components_bundle!=="undefined"&&components_bundle!==null){p.each(components_bundle,function(e,t){gs.component(e,t)})}const vs=gs.mount("#vue-cashin");const bs=Vue.createApp({components:{"component-carousel":Qe}}).mount("#vue-carousel");const ys={props:["data","index_selected"],data(){return{owl:undefined}},mounted(){setTimeout(()=>{this.renderCarousel()},1)},methods:{renderCarousel(){let e=false;if(typeof is_rtl!=="undefined"&&is_rtl!==null){e=is_rtl=="1"?true:false}this.owl=p(this.$refs.menu).owlCarousel({loop:false,dots:false,nav:false,autoWidth:true,rtl:e})}},template:`
   <div ref="menu"  class="owl-carousel owl-theme menu-carousel">
      <div v-for="(item, index) in data" class="mr-2">	   
	   <a :href="item.url" class="d-block"
	   :class="{active : index==index_selected }"
	   >
	    {{item.label}}
	   </a>
	  </div>    
   </div>
   `};const ws=Vue.createApp({components:{"component-profile-menu":ys}}).mount("#vue-profile-menu");const xs=Vue.createApp({data(){return{recaptcha_response:""}},components:{"components-recapcha":De},methods:{recaptchaVerified(e){this.recaptcha_response=e},recaptchaExpired(){if(this.$refs.vueRecaptcha.is_enabled){this.$refs.vueRecaptcha.reset()}},recaptchaFailed(){}}}).mount("#vue-captcha");const Ss=Vue.createApp({components:{"component-phone":e,"vue-recaptcha":De},data(){return{loading:false,ready:false,show_password:false,agree_terms:false,password_type:"password",firstname:"",lastname:"",email_address:"",mobile_number:"",mobile_prefix:"",password:"",cpassword:"",mobile_prefixes:[],error:[],success:"",redirect:"",next_url:"",recaptcha_response:"",show_recaptcha:false}},mounted(){u("mounted");this.redirect=redirect_to;this.next_url=next_url;if(typeof _capcha!=="undefined"&&_capcha!==null){this.show_recaptcha=_capcha==1?true:false}},updated(){this.validate()},methods:{validate(){if(!f(this.firstname)&&!f(this.lastname)&&!f(this.mobile_number)){this.ready=true}else this.ready=false},showPassword(){this.show_password=this.show_password==true?false:true;if(this.show_password){this.password_type="text"}else this.password_type="password"},onRegister(){var t=_();var e={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),firstname:this.firstname,lastname:this.lastname,email_address:this.email_address,mobile_number:this.mobile_number,mobile_prefix:this.mobile_prefix,password:this.password,cpassword:this.cpassword,redirect:this.redirect,recaptcha_response:this.recaptcha_response};n[t]=p.ajax({url:ajaxurl+"/registerGuestUser",method:"PUT",dataType:"json",data:JSON.stringify(e),contentType:d.json,timeout:c,crossDomain:true,beforeSend:e=>{this.loading=true;this.error=[];this.success="";if(n[t]!=null){n[t].abort()}}});n[t].done(e=>{if(e.code==1){this.success=e.msg;window.location.href=e.details.redirect}else{this.error=e.msg;this.recaptchaExpired()}});n[t].always(e=>{this.loading=false})},recaptchaVerified(e){this.recaptcha_response=e},recaptchaExpired(){if(this.show_recaptcha){this.$refs.vueRecaptcha.reset()}},recaptchaFailed(){}}});Ss.use(Maska);const Ts=Ss.mount("#vue-guest-register");const Is=Vue.createApp({components:{"components-maps":qe,"components-select-address":Ue,"components-saveaddress":Je},data(){return{loading:false,loading_save:false,data:[],address1:"",location_name:"",delivery_options:"Leave it at my door",delivery_instructions:"",address_label:"Home",markers:[],address_data:[],address_found:false,address_uuid:"",isEdit:false,isAdd:false,formatted_address:"",save_address_data:[],default_address:[],count_address:0,address_needed:true,address_label_list:[],address2:"",postal_code:"",company:""}},created(){this.addressNeeded()},computed:{hasDeliveryAddress(){if(Object.keys(this.address_data).length>0){return true}return false},deliveryAddress(){return this.address_data},hasSavedAddress(){return this.address_found},hasAddressList(){return this.count_address>0?true:false},isAddressNeeded(){return this.address_needed},getAddressLabel(){return this.address_label_list}},watch:{address_data(e,t){this.setMarker(e.latitude,e.longitude)}},methods:{addressNeeded(){this.loading=true;axios({method:"POST",url:ajaxurl+"/checkoutAddressNeeded",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&merchant_id="+merchant_id+"&cart_uuid="+T("cart_uuid"),timeout:c}).then(e=>{if(e.data.code==1){this.address_needed=e.data.details.address_needed;this.getAddress()}else{this.address_needed=false}})["catch"](e=>{}).then(e=>{this.loading=false})},setMarker(e,t){this.markers=[{id:0,lat:parseFloat(e),lng:parseFloat(t),draggable:false}]},afterClosemodal(e){console.debug("afterClosemodal xx",e);if(e=="yandex"){if(I){I.destroy();I=null}this.$refs.maps.renderMap()}},getAddress(){this.loading=true;axios({method:"POST",url:ajaxurl+"/getAddressDetails",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:c}).then(e=>{if(e.data.code==1){this.data=e.data.details;this.address_found=e.data.details.address_found;this.address_data=e.data.details.data;this.default_address=this.address_data;this.save_address_data=this.address_data;this.address_uuid=e.data.details.data.address_uuid;this.count_address=e.data.details.count_address;this.address_label_list=e.data.details.address_label}else{this.data=e.data.details;this.address_data=e.data.details.data;this.default_address=this.address_data;this.save_address_data="";this.formatted_address=this.address_data.address.formatted_address;this.address1=this.address_data.address.address1;this.address_found=e.data.details.address_found;this.count_address=e.data.details.count_address;this.address_label_list=e.data.details.address_label}})["catch"](e=>{}).then(e=>{this.loading=false})},saveAddress(){this.loading_save=true;axios({method:"PUT",url:ajaxurl+"/SaveAddress",data:{YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),address_uuid:this.address_uuid,location_name:this.location_name,delivery_instructions:this.delivery_instructions,delivery_options:this.delivery_options,address_label:this.address_label,latitude:this.address_data.latitude,longitude:this.address_data.longitude,address1:this.address1,formatted_address:this.formatted_address,set_place_id:this.address_data.place_id,data:this.address_data,address2:this.address2,postal_code:this.postal_code,company:this.company},timeout:c}).then(e=>{if(e.data.code==1){if(I){I.destroy();I=null}ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"success"});if(typeof redirect_to!=="undefined"&&redirect_to!==null){window.location.href=redirect_to}else{this.isEdit=false;this.isAdd=false;this.getAddress();this.$refs.save_address.getSavedAddress();if(typeof N!=="undefined"&&N!==null){N.loadcart()}}}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{}).then(e=>{this.loading_save=false})},showSelectAddress(){u("showSelectAddress");this.$refs.address_modal.setInitialMarker();this.$refs.address_modal.visible=true},afterChangeaddress(e){console.debug("afterChangeaddress xx1",e);if(I){I.destroy();I=null}this.address_data=e;if(typeof e.parsed_address!=="undefined"&&e.parsed_address!==null){if(!f(e.parsed_address.street_name)){this.formatted_address=e.parsed_address.street_name;this.address1=e.parsed_address.street_number}else{this.formatted_address=this.address_data.address.formatted_address;this.address1=this.address_data.address.address1}}else{this.formatted_address=this.address_data.address.formatted_address;this.address1=this.address_data.address.address1}this.$refs.address_modal.visible=false},editSaveAddress(){console.debug("editSaveAddress");this.isEdit=true;this.address_data=this.save_address_data;this.address_uuid=this.save_address_data.address_uuid;this.formatted_address=this.address_data.address.formatted_address;this.address1=this.address_data.address.address1;this.location_name=this.address_data.attributes.location_name;this.delivery_options=this.address_data.attributes.delivery_options;this.delivery_instructions=this.address_data.attributes.delivery_instructions;this.address_label=this.address_data.attributes.address_label;this.address2=this.address_data.address.address2;this.postal_code=this.address_data.address.postal_code;this.company=this.address_data.address.company},CancelEdit(){console.debug("CancelEdit");if(I){I.destroy();I=null}this.isEdit=false;this.address_data=this.default_address;this.formatted_address="";this.address1="";this.location_name="";this.delivery_options="";this.delivery_instructions="";this.address_label="";this.address2="";this.postal_code="";this.company=""},AddNewAddress(){this.isAdd=true;this.address_data=this.default_address;this.formatted_address=this.address_data.address.formatted_address;this.address1=this.address_data.address.address1;this.address_uuid="";this.location_name="";this.delivery_instructions="";this.address2="";this.postal_code="";this.company=""},CanceAdd(){console.log("CanceAdd");this.isAdd=false;this.address_data=this.default_address},afterSelectaddress(e){this.setPlaceID(e.place_id);this.address_found=true;this.save_address_data=e;this.address_data=this.save_address_data;this.address_uuid=this.save_address_data.address_uuid;this.formatted_address=this.address_data.address.formatted_address;this.address1=this.address_data.address.address1;this.location_name=this.address_data.attributes.location_name;this.delivery_options=this.address_data.attributes.delivery_options;this.delivery_instructions=this.address_data.attributes.delivery_instructions;this.address_label=this.address_data.attributes.address_label},setPlaceID(e){axios({method:"POST",url:ajaxurl+"/setPlaceID",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&place_id="+e,timeout:c}).then(e=>{if(typeof N!=="undefined"&&N!==null){N.loadcart()}})["catch"](e=>{}).then(e=>{})},confirmDelete(e){let t=JSON.parse(some_words);ElementPlus.ElMessageBox.confirm(t.confirm_delete_address,t["delete"],{confirmButtonText:t.delete_adress,cancelButtonText:t.cancel,type:"info"}).then(()=>{this.deleteAddress(e)})["catch"](()=>{})},deleteAddress(e){axios({method:"POST",url:ajaxurl+"/deleteAddress",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&address_uuid="+e,timeout:c}).then(e=>{this.getAddress();this.$refs.save_address.getSavedAddress()})["catch"](e=>{}).then(e=>{})}}});Is.use(ElementPlus);const ks=Is.mount("#vm_delivery_details");const Cs=Vue.createApp({components:{"component-auto-complete":Ve,"components-select-address":Ue,"components-address-form":Be},data(){return{deliveryAddress:[],location_data:[]}},methods:{afterChoose(e){console.log("afterChoosexx",e);this.$refs.auto_complete.setLoading(true);axios({method:"POST",url:ajaxurl+"/getLocationDetails",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&id="+e.id+"&saveplace=0"+"&description="+e.description,timeout:c}).then(e=>{if(e.data.code==1){this.$refs.auto_complete.setAddressData(e.data.details.data)}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{}).then(e=>{this.$refs.auto_complete.setLoading(false)})},afterFormclose(){u("afterFormclose");this.location_data=[]},afterGetcurrentlocation(e){u("afterGetcurrentlocation");console.log("isGuest=>"+isGuest);if(isGuest==="1"){this.deliveryAddress=e;this.$refs.address_modal.visible=true}else{this.location_data=e}},afterPointaddress(e){console.log("afterPointaddress",e);this.$refs.address_modal.setLoading(true);axios({method:"POST",url:ajaxurl+"/setPlaceID",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&place_id="+e.place_id,timeout:c}).then(e=>{window.location.href=next_url})["catch"](e=>{ElementPlus.ElNotification({title:"",message:e,position:"bottom-right",type:"warning"})}).then(e=>{this.$refs.address_modal.setLoading(false)})},onSavelocation(e,t){this.$refs.address_form.setLoading(true);let a={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),cart_uuid:T("cart_uuid"),data:e,location_name:t.location_name,delivery_instructions:t.delivery_instructions,delivery_options:t.delivery_options,address_label:t.address_label,latitude:e.latitude,longitude:e.longitude,formatted_address:t.formatted_address,address1:t.address1,address2:t.address2,postal_code:t.postal_code,company:t.company,address_format_use:t.address_format_use};axios({method:"PUT",url:ajaxurl+"/SaveAddress",data:a,timeout:c}).then(e=>{if(e.data.code==1){ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"success"});window.location.href=next_url}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{ElementPlus.ElNotification({title:"",message:e,position:"bottom-right",type:"warning"})}).then(e=>{this.$refs.address_form.setLoading(false)})}}});Cs.use(ElementPlus);const Os=Cs.mount("#vm_home_search");const js=Vue.createApp({components:{"component-notification-cart":Ta},methods:{afterReceiveitemupdate(e){axios({method:"POST",url:ajaxurl+"/validateCartItems",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&cart_uuid="+T("cart_uuid")+"&item_id="+e.item_id,timeout:c}).then(t=>{if(t.data.code==1){let e=JSON.parse(some_words);ElementPlus.ElMessageBox.alert(t.data.msg,"",{confirmButtonText:e.ok,callback:e=>{if(typeof D!=="undefined"&&D!==null){D.getCartPreview()}if(typeof N!=="undefined"&&N!==null){N.loadcart()}if(typeof ds!=="undefined"&&ds!==null){ds.$refs.ref_item_suggestion.itemSuggestion()}}})}})["catch"](e=>{}).then(e=>{})}}});js.use(ElementPlus);const Es=js.mount("#vue-notification-cart");const Ps={props:["resend_counter","uuid","label"],data(){return{loading:false,counter:0,timer:undefined}},created(){this.startTimer()},watch:{counter(e,t){if(e<=0){this.stopTimer()}}},methods:{startTimer(){this.counter=this.resend_counter;this.stopTimer();this.timer=setInterval(()=>{this.counter--},1e3)},stopTimer(){clearInterval(this.timer)},resendCode(){this.loading=true;axios({method:"POST",url:ajaxurl+"/ResendRiderverificationcode",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&uuid="+this.uuid,timeout:c}).then(e=>{if(e.data.code==1){this.startTimer();ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"success"});window.location.href=e.data.details.redirect}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{}).then(e=>{this.loading=false})}},template:`     			
	<div class="d-flex justify-content-between align-items-center">
		<div><b>{{label.resend_code}} {{ counter }}</b></div>
		<div>
		   <el-button type="text" 
		   :loading="loading"
		   :disabled="counter != 0"
		   @click="resendCode"
		   >
		   {{label.resend}}
		   </el-button>		
		</div>
    </div>
    `};const Fs=Vue.createApp({components:{"component-phone":e,"component-rider-verifycode":Ps},data(){return{loading:false,mobile_number:"",mobile_prefix:"",first_name:"",last_name:"",email:"",address:"",password:"",cpassword:"",show_password:false,password_type:"password"}},computed:{validForm(){if(f(this.first_name)){return false}if(f(this.last_name)){return false}if(f(this.email)){return false}if(f(this.password)){return false}if(f(this.cpassword)){return false}if(f(this.address)){return false}return true}},methods:{onRegister(){this.loading=true;let e="&mobile_prefix="+this.mobile_prefix;e+="&mobile_number="+this.mobile_number;e+="&first_name="+this.first_name;e+="&last_name="+this.last_name;e+="&email="+this.email;e+="&address="+this.address;e+="&password="+this.password;e+="&cpassword="+this.cpassword;axios({method:"POST",url:ajaxurl+"/riderRegistration",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+e,timeout:c}).then(e=>{if(e.data.code==1){this.loading=true;ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"success"});window.location.href=e.data.details.redirect}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{}).then(e=>{this.loading=false})},showPassword(){this.show_password=this.show_password==true?false:true;if(this.show_password){this.password_type="text"}else this.password_type="password"}}});Fs.use(Maska);Fs.use(ElementPlus);const Ns=Fs.mount("#vue-rider-registration");const Rs={data(){return{total:0,total_animated:0,loading:true}},watch:{total(e){gsap.to(this.$data,{duration:.6,total_animated:e})}},computed:{animatedTotal(){return this.total_animated.toFixed(0)}},mounted(){this.getAvailablePoints()},methods:{getAvailablePoints(){this.loading=true;axios({method:"POST",url:ajaxurl+"/getAvailablePoints",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:c}).then(e=>{this.total=e.data.details.total})["catch"](e=>{}).then(e=>{this.loading=false})}},template:`{{animatedTotal}}`};const Ds=Vue.createApp({components:{"component-available-points":Rs},data(){return{loading:false,data:[],has_data:false,show_next:false,tab:"transaction",loading1:false,data1:[],show_next1:false,page:1,page2:1,loading_next:false,loading_next1:false}},created(){this.getPointsTransaction(false)},mounted(){this.getPointsTransactionMerchant(false)},computed:{hasData(){return this.has_data},getData(){return this.data},showNext(){return this.show_next},getData1(){return this.data1},showNext1(){return this.show_next1}},methods:{getPointsTransaction(e){this.has_data=false;if(e){this.loading_next=true}else this.loading=true;axios({method:"POST",url:ajaxurl+"/getPointsTransaction",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&page="+this.page,timeout:c}).then(e=>{this.data.push(e.data.details.data);this.has_data=true;this.show_next=e.data.details.show_next_page;this.page=e.data.details.page})["catch"](e=>{}).then(e=>{this.loading=false;this.loading_next=false})},getPointsTransactionMerchant(e){if(e){this.loading_next1=true}else this.loading1=true;axios({method:"POST",url:ajaxurl+"/getPointsTransactionMerchant",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&page="+this.page2,timeout:c}).then(e=>{this.data1.push(e.data.details.data);this.show_next1=e.data.details.show_next_page;this.page2=e.data.details.page})["catch"](e=>{}).then(e=>{this.loading1=false;this.loading_next1=false})},nextPage(e){this.getPointsTransaction(true)},nextPage2(e){this.getPointsTransactionMerchant(true)}}});Ds.use(ElementPlus);const Ys=Ds.mount("#vue-my-points");const Ms=e=>e===100?"Full":`${e}%`;const As={props:["title","is_mobile","apply_text","enter_points","data","use_thresholds"],data(){return{points:0,is_submit:false,points_tab:undefined,data_points:[],balance:0,points_id:0,loading:false}},computed:{hasData(){if(this.points>0){return false}return true}},watch:{points_tab(e,t){u(this.balance+"=>"+e.points);if(this.balance>e.points){this.points=e.points;this.points_id=e.id}else{this.points=0;this.points_id=0}}},created(){},methods:{showModal(e){if(e){p(this.$refs.modal).modal("show")}else{p(this.$refs.modal).modal("hide")}if(this.use_thresholds&&e){this.getPointsthresholds()}},applyPoints(){this.is_submit=true;let e=T("currency_code");e=!f(e)?e:"";axios({method:"POST",url:ajaxurl+"/applyPoints",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&points="+this.points+"&cart_uuid="+T("cart_uuid")+"&currency_code="+e+"&points_id="+this.points_id,timeout:c}).then(e=>{if(e.data.code==1){this.showModal(false);this.$emit("afterApplypoints")}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{}).then(e=>{this.is_submit=false})},getPointsthresholds(){this.loading=true;let e=T("currency_code");e=!f(e)?e:"";axios({method:"POST",url:ajaxurl+"/getPointsthresholds",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&cart_uuid="+T("cart_uuid")+"&currency_code="+e,timeout:c}).then(e=>{if(e.data.code==1){this.data_points=e.data.details.data;this.balance=e.data.details.balance}})["catch"](e=>{}).then(e=>{this.loading=false})}},template:`
	<div class="modal" ref="modal" tabindex="-1" role="dialog" 
    aria-labelledby="promocodeModal" aria-hidden="true" data-backdrop="static" >
	   <div class="modal-dialog" role="document">
	     <div class="modal-content" :class="{ 'modal-mobile' : is_mobile==1 }" > 

		  <div class="modal-header border-bottom-0" style="padding-bottom:0px !important;">
			<h5 class="modal-title" id="exampleModalLongTitle">
			     {{title}}
			</h5>
			<div class="close">
			<a href="javascript:;" data-dismiss="modal" class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a>   
			</div>
		   </div>

	       <div class="modal-body">
		   	       	
		   <form class="forms mt-2 apply-points-form" @submit.prevent="applyCode" >
		   
		   
		   <template v-if="use_thresholds">
		   
		   <div v-loading="loading">		   
		   		   
			    <el-tabs
					v-model="points_tab"					
					stretch							
				>
				<template v-for="items in data_points" :keys="items">
					<el-tab-pane label="User" :name="items"  >
						<template #label>
						<Div v-if="balance < items.points" class="disabled-class"></Div>
						<span class="custom-tabs-label">					    
							<div class="font13 font-weight-light">{{ items.label }}</div>
							<div class="font-weight-bold font20">{{ items.amount }}</div>				
							<div>						
								<el-progress
								:text-inside="true"
								:stroke-width="20"
								:percentage=" ((balance / items.points)*100)>100?100: (balance / items.points)*100 "
								:status="balance >= items.points ? 'success' : 'primary'"
								>
								<span>
								<template v-if="balance >= items.points">
									REDEEM
								</template>
								</span>
								</el-progress>					
							</div>		
						</span>
						</template>
					</el-tab-pane>				
					</template>
				</el-tabs>

			</div>

		   </template>
	       <template v-else>
			<div class="form-label-group">    
				<input v-model="points" class="form-control form-control-text" placeholder=""
				id="points" type="text" maxlength="20" >   
				<label for="points" class="required">{{enter_points}}</label> 
			</div>           
		   </template>     
		   
		   </form> <!--forms-->
	         
	       </div> <!--modal body-->

	       <div class="modal-footer">
		        <button type="button" class="btn btn-black pl-4 pr-4" @click="showModal(false)" >Cancel</button>				
				<button type="button" class="btn btn btn-green pl-4 pr-4"
				  @click="applyPoints" :class="{ loading: is_submit }" :disabled="hasData"
				>
				  <span class="label">{{apply_text}}</span><div class="m-auto circle-loader" data-loader="circle-side"></div>
				</button>	
		   </div>	        
	       
	    </div> <!--content-->
	   </div> <!--dialog-->
	 </div> <!--modal-->           
	`};const $s=Vue.createApp({components:{"components-apply-points":As},data(){return{loading:true,loading_remove:false,data:[]}},created(){this.getAvailablePoints()},methods:{getAvailablePoints(){this.loading=true;let e=T("currency_code");e=!f(e)?e:"";axios({method:"POST",url:ajaxurl+"/getCartpoints",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&cart_uuid="+T("cart_uuid")+"&currency_code="+e,timeout:c}).then(e=>{this.data=e.data.details})["catch"](e=>{}).then(e=>{this.loading=false})},showApplyPoints(){this.$refs.apply_points.showModal(true)},afterApplypoints(){if(typeof N!=="undefined"&&N!==null){N.loadcart()}this.getAvailablePoints()},removePoints(){this.loading_remove=true;axios({method:"POST",url:ajaxurl+"/removePoints",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&cart_uuid="+T("cart_uuid"),timeout:c}).then(e=>{if(typeof N!=="undefined"&&N!==null){N.loadcart()}this.getAvailablePoints()})["catch"](e=>{}).then(e=>{this.loading_remove=false})}}});$s.use(ElementPlus);const Ks=$s.mount("#vue-checkout-points");const zs={props:["label"],data(){return{q:"",data:[]}},computed:{hasSearch(){if(this.q==""){return false}return true}},methods:{async querySearch(e,t){axios({method:"POST",url:ajaxurl+"/SearchSuggestion",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&q="+e,timeout:c}).then(e=>{if(e.data.code==1){t(e.data.details)}else{t([])}})["catch"](e=>{t([])}).then(e=>{})},handleSelect(e){this.$emit("afterSuggestion",e.name)}},template:`			
	<el-autocomplete
    v-model="q"
    :fetch-suggestions="querySearch"
	@select="handleSelect"
    :placeholder="label.search"    
	:trigger-on-focus="false"
	:fit-input-width="false"
	class="w-100"
	size="large"
	value-key="name"
	:clearable="true"
	:select-when-unmatched="true"
	:highlight-first-item="true"
    >			
	<template #default="{ item }">	  
      <div class="value" v-html="item.name"></div>      
    </template>	
	</el-autocomplete>
	`};const Ls={props:["data","items_not_available","category_not_available"],template:"#xtemplate_item_list",methods:{viewItem(e){this.$emit("handleView",{cat_id:e.cat_id,item_uuid:e.item_uuid,merchant_id:e.merchant_id})},itemAvailable(e,t){if(this.items_not_available.includes(parseInt(e))===false){if(this.category_not_available.includes(parseInt(t))===false){return true}}return false}}};const qs={props:["q"],components:{"components-item-list":Ls},data(){return{tabs:"searchFood",loading:false,food_list:[],merchant_list:[],total_food:0,merchant_data:[],total_merchant:0,cuisine:[],items_not_available:[],category_not_available:[]}},watch:{q(e,t){this.doSearch(e,this.tabs)}},computed:{hasDataFood(){if(Object.keys(this.food_list).length>0){return true}return false},getFood(){return this.food_list},getFoodCount(){return this.total_food},hasQ(){return!f(this.q)?true:false},hasMerchant(){if(Object.keys(this.merchant_data).length>0){return true}return false},getMerchant(){return this.merchant_data},getMerchantCount(){return this.total_merchant}},methods:{tabClick(e,t){this.doSearch(this.q,e.paneName)},doSearch(e,t){let a=T("currency_code");a=!f(a)?a:"";this.loading=true;axios({method:"POST",url:ajaxurl+"/"+t,data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&q="+e+"&currency_code="+a,timeout:c}).then(e=>{if(e.data.code==1){if(t=="searchFood"){this.food_list=e.data.details.food_list;this.merchant_list=e.data.details.merchant_list;this.total_food=e.data.details.total;this.items_not_available=e.data.details.items_not_available;this.category_not_available=e.data.details.category_not_available}else{this.merchant_data=e.data.details.merchant_data;this.cuisine=e.data.details.cuisine;this.total_merchant=e.data.details.total}this.$emit("setSearch",true)}else{this.clearData()}})["catch"](e=>{this.clearData()}).then(e=>{this.loading=false})},clearData(){this.food_list=[];this.merchant_list=[];this.total_food=0},handleView(e){this.$emit("handleView",{cat_id:e.cat_id,item_uuid:e.item_uuid,merchant_id:e.merchant_id})}},template:"#xtemplate_search_food_result"};const Vs={template:"#xtemplate_item_details",components:{"money-format":ze},data(){return{merchant_id:0,menu_loading:true,menu_data:[],items:[],item_addons:[],item_addons_load:false,size_id:0,disabled_cart:true,item_qty:1,item_total:0,add_to_cart:false,meta:[],special_instructions:"",sold_out_options:[],if_sold_out:"substitute",view_data:[],item_loading:false,item_in_cart:0,merchant_data:[],items_not_available:[],category_not_available:[],menu_layout:"list",q:"",search_menu_data:[],allergen:[],allergen_data:[],item_gallery:[],image_featured:"",title:"",new_order_label:"",money_config:[]}},mounted(){let e=JSON.parse(some_words);if(Object.keys(e).length>0){this.title=e.created_new_order+"?";this.new_order_label=e.new_order_label}},updated(){if(this.item_addons_load==true){this.ItemSummary()}},computed:{getAllergen(){return this.allergen},hasAllergen(){if(Object.keys(this.allergen).length>0){return true}return false},getItemGallery(){return this.item_gallery}},methods:{getMenuItem(e){let t=T("currency_code");t=!f(t)?t:"";let a="YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&merchant_id="+e.merchant_id;a+="&cat_id="+e.cat_id;a+="&item_uuid="+e.item_uuid;a+="&currency_code="+t;this.item_loading=true;NProgress.start();axios({method:"POST",url:ajaxurl+"/getMenuItem",data:a,timeout:c}).then(e=>{if(e.data.code==1){p("#itemModal").modal("show");j=e.data.details.data.items;E=e.data.details.data.addons;P=e.data.details.data.addon_items;Ct=e.data.details.data.items.item_addons;var t=e.data.details.data.meta;this.money_config=e.data.details.money_config;this.item_gallery=e.data.details.data.meta.item_gallery;this.image_featured="";this.renderGallery();var i=e.data.details.data.meta_details;var o={cooking_ref:[],ingredients:[],dish:[]};let s=e.data.details.data.items.ingredients_preselected;if(!f(t)){p.each(t,function(a,e){p.each(e,function(e,t){if(!f(i[a])){if(!f(i[a][t])){let e=false;if(a=="ingredients"&&s){e=true}o[a].push({meta_id:i[a][t].meta_id,meta_name:i[a][t].meta_name,checked:e})}}})})}var a=j.price;var r=Object.keys(a)[0];this.item_qty=1;this.items=j;this.size_id=r;this.meta=o;this.getSizeData(r);this.sold_out_options=e.data.details.sold_out_options}else{p("#itemModal").modal("hide")}})["catch"](e=>{}).then(e=>{this.item_loading=false;NProgress.done()})},setItemSize(e){var t=e.currentTarget.firstElementChild.value;this.size_id=t;this.getSizeData(t)},getSizeData(a){Ot=[];var s=[];if(!f(Ct[a])){p.each(Ct[a],function(e,t){if(!f(E[a])){if(!f(E[a][t])){E[a][t].subcat_id;p.each(E[a][t].sub_items,function(e,t){if(typeof P[t]!=="undefined"&&P[t]!==null){s.push({sub_item_id:P[t].sub_item_id,sub_item_name:P[t].sub_item_name,item_description:P[t].item_description,price:P[t].price,pretty_price:P[t].pretty_price,checked:false,disabled:false,qty:1})}});Ot.push({subcat_id:E[a][t].subcat_id,subcategory_name:E[a][t].subcategory_name,subcategory_description:E[a][t].subcategory_description,multi_option:E[a][t].multi_option,multi_option_min:E[a][t].multi_option_min,multi_option_value:E[a][t].multi_option_value,require_addon:E[a][t].require_addon,pre_selected:E[a][t].pre_selected,sub_items_checked:"",sub_items:s});s=[]}}})}this.item_addons=Ot;this.item_addons_load=true},renderGallery(){setTimeout(()=>{let e=false;if(typeof is_rtl!=="undefined"&&is_rtl!==null){e=is_rtl=="1"?true:false}p(this.$refs.owl_item_gallery).owlCarousel({loop:false,margin:10,nav:false,dots:false,rtl:e,responsive:{0:{items:1},600:{items:3},1e3:{items:5}}})},10)},setImage(e){this.image_featured=e},ItemSummary(e){y=0;var l=[];var n=[];let c=[];let m=[];if(!f(this.items.price)){if(!f(this.items.price[this.size_id])){var t=this.items.price[this.size_id];if(t.discount>0){y+=this.item_qty*parseFloat(t.price_after_discount)}else y+=this.item_qty*parseFloat(t.price)}}this.item_addons.forEach((a,s)=>{if(a.require_addon==1){l.push(a.subcat_id)}if(a.multi_option=="custom"){var i=0;let e=a.multi_option_min;var t=a.multi_option_value;if(t>0){c.push({subcat_id:a.subcat_id,min:e,max:t})}var o=[];var r=[];a.sub_items.forEach((e,t)=>{if(e.checked==true){i++;y+=this.item_qty*parseFloat(e.price);n.push(a.subcat_id)}else o.push(t);if(e.disabled==true){r.push(t)}});m[a.subcat_id]={total:i};if(i>=t){o.forEach((e,t)=>{this.item_addons[s].sub_items[e].disabled=true})}else{r.forEach((e,t)=>{this.item_addons[s].sub_items[e].disabled=false})}}else if(a.multi_option=="one"){a.sub_items.forEach((e,t)=>{if(e.sub_item_id==a.sub_items_checked){y+=this.item_qty*parseFloat(e.price);n.push(a.subcat_id)}})}else if(a.multi_option=="multiple"){var o=[];let e=a.multi_option_min;var t=a.multi_option_value;var d=0;if(t>0){c.push({subcat_id:a.subcat_id,min:e,max:t})}a.sub_items.forEach((e,t)=>{if(e.checked==true){y+=e.qty*parseFloat(e.price);n.push(a.subcat_id);d+=e.qty}o.push(t)});m[a.subcat_id]={total:d};this.item_addons[s].qty_selected=d;if(this.item_addons[s].qty_selected>=t){o.forEach((e,t)=>{this.item_addons[s].sub_items[e].disabled=true})}else{o.forEach((e,t)=>{this.item_addons[s].sub_items[e].disabled=false})}}});this.item_total=y;var i=true;if(l.length>0){p.each(l,function(e,t){if(n.includes(t)===false){i=false;return false}})}if(this.items.cooking_ref_required){let a=false;if(Object.keys(this.meta.cooking_ref).length>0){Object.entries(this.meta.cooking_ref).forEach(([e,t])=>{if(t.checked){a=true}})}if(!a){i=false}}if(Object.keys(c).length>0){let a,s;Object.entries(c).forEach(([e,t])=>{a=parseInt(t.min);if(m[t.subcat_id]){s=parseInt(m[t.subcat_id].total)}if(s>0){if(a>s){i=false}}})}if(i){this.disabled_cart=false}else this.disabled_cart=true},CheckaddCartItems(){if(typeof D!=="undefined"&&D!==null){let e=D.$refs.childref.cart_merchant.merchant_id;if(typeof e!=="undefined"&&e!==null){if(this.items.merchant_id!=D.$refs.childref.cart_merchant.merchant_id){p("#itemModal").modal("hide");ElementPlus.ElMessageBox.confirm(D.$refs.childref.cart_merchant.confirm_add_item,this.title,{confirmButtonText:this.new_order_label,showCancelButton:false,type:"info",buttonSize:"large"}).then(()=>{this.clearCart(D.$refs.childref.cart_uuid)})["catch"](()=>{})}else this.addCartItems()}else this.addCartItems()}else this.addCartItems()},clearCart(e){NProgress.start();axios({method:"POST",url:ajaxurl+"/clearCart",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&cart_uuid="+e,timeout:c}).then(e=>{if(e.data.code==1){this.addCartItems()}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{}).then(e=>{NProgress.done()})},addCartItems(e){if(e){e.preventDefault()}this.add_to_cart=true;var t={YII_CSRF_TOKEN:p("meta[name=YII_CSRF_TOKEN]").attr("content"),merchant_id:this.items.merchant_id,cat_id:this.items.cat_id,item_token:this.items.item_token,item_size_id:this.size_id,item_qty:this.item_qty,item_addons:this.item_addons,special_instructions:this.special_instructions,meta:this.meta,cart_uuid:T("cart_uuid"),transaction_type:"delivery",if_sold_out:this.if_sold_out};var a=1;t=JSON.stringify(t);n[a]=p.ajax({url:ajaxurl+"/addCartItems",method:"PUT",dataType:"json",data:t,contentType:d.json,timeout:c,crossDomain:true,beforeSend:function(e){if(n[a]!=null){n[a].abort()}}});n[a].done(e=>{if(e.code==1){S("cart_uuid",e.details.cart_uuid,30);p("#itemModal").modal("hide");if(typeof D!=="undefined"&&D!==null){D.getCartPreview()}}});n[a].always(e=>{u("always");this.add_to_cart=false})}}};const Us={props:["meta_name","sub_title"],components:{"components-item-list":Ls},data(){return{loading:false,food_list:[],merchant_list:[],items_not_available:[],category_not_available:[],title:""}},created(){this.getFoodList()},computed:{hasDataFood(){if(Object.keys(this.food_list).length>0){return true}return false},getFood(){return this.food_list}},methods:{getFoodList(){let e=T("currency_code");e=!f(e)?e:"";this.loading=true;axios({method:"POST",url:ajaxurl+"/getFoodList",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&meta_name="+this.meta_name+"&currency_code="+e,timeout:c}).then(e=>{if(e.data.code==1){this.title=e.data.details.title;this.food_list=e.data.details.food_list;this.merchant_list=e.data.details.merchant_list;this.total_food=e.data.details.total;this.items_not_available=e.data.details.items_not_available;this.category_not_available=e.data.details.category_not_available;this.renderSlide()}})["catch"](e=>{}).then(e=>{this.loading=false})},renderSlide(){setTimeout(()=>{let e=false;if(typeof is_rtl!=="undefined"&&is_rtl!==null){e=is_rtl=="1"?true:false}p(this.$refs.owl_item_slide).owlCarousel({loop:false,margin:10,nav:true,dots:false,navText:[p(this.$refs.owl_next),p(this.$refs.owl_previous)],rtl:e,responsive:{0:{items:1},600:{items:3},1e3:{items:5}}})},10)},viewItem(e){this.$emit("handleView",{cat_id:e.cat_id,item_uuid:e.item_uuid,merchant_id:e.merchant_id})},itemAvailable(e,t){if(this.items_not_available.includes(parseInt(e))===false){if(this.category_not_available.includes(parseInt(t))===false){return true}}return false}},template:"#xtemplate_food_carousel"};const Js=Vue.createApp({data(){return{searchString:"",isSearch:false}},components:{"components-search-food":zs,"components-search-food-result":qs,"components-item-details":Vs,"components-food-list":Us},computed:{getisSearch(){return this.isSearch}},methods:{afterSuggestion(e){this.searchString=e},handleView(e){this.$refs.item_details.getMenuItem(e)},setSearch(e){this.isSearch=e}}});Js.use(ElementPlus);const Bs=Js.mount("#vue-search");const Gs=Vue.createApp({data(){return{loading:false,room_list:[],table_list:[],room_uuid:"",table_uuid:"",transaction_type:"",guest_number:1,booking_enabled:false}},created(){this.getTableAttributes()},methods:{getTableAttributes(){this.loading=true;axios({method:"POST",url:ajaxurl+"/getTableAttributes",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&cart_uuid="+T("cart_uuid"),timeout:c}).then(e=>{if(e.data.code==1){this.room_list=e.data.details.room_list;this.table_list=e.data.details.table_list;this.booking_enabled=e.data.details.booking_enabled}})["catch"](e=>{}).then(e=>{this.loading=false})},clearTableList(){this.table_uuid=""}}});Gs.use(ElementPlus);const Ws=Gs.mount("#vue-checkout-booking");const Zs={data(){return{total:0,total_animated:0,loading:true}},watch:{total(e){gsap.to(this.$data,{duration:.6,total_animated:e})}},computed:{animatedTotal(){return this.total_animated}},mounted(){this.getBalance()},methods:{getBalance(){this.loading=true;axios({method:"POST",url:ajaxurl+"/getWalletBalance",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:c}).then(e=>{this.total=e.data.details.total})["catch"](e=>{}).then(e=>{this.loading=false})}},template:`{{animatedTotal}}`};const Qs={props:["data","has_data","loading","show_next"],template:"#xtemplate_wallet_transaction"};const Hs={props:["label","topup_minimum","topup_maximum"],data(){return{modal:false,amount:1,loading:false,default_payment:[],loading_submit:false,loading_payment:false}},created(){this.getCustomerDefaultPayment()},computed:{getPayment(){return this.default_payment}},methods:{show(){this.modal=true},close(){this.modal=false},getCustomerDefaultPayment(){this.loading=true;axios({method:"POST",url:ajaxurl+"/getCustomerDefaultPayment",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content"),timeout:c}).then(e=>{if(e.data.code==1){this.default_payment=e.data.details.data}})["catch"](e=>{}).then(e=>{this.loading=false})},onAddfunds(){this.loading_submit=true;let e=T("currency_code");e=!f(e)?e:"";axios({method:"POST",url:ajaxurl+"/prepareAddFunds",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&amount="+this.amount+"&payment_code="+this.default_payment.payment_code+"&payment_uuid="+this.default_payment.payment_uuid+"&currency_code="+e,timeout:c}).then(e=>{if(e.data.code==1){this.$emit("afterPreparepayment",e.data.details)}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{}).then(e=>{this.loading_submit=false})}},template:"#xtemplate_topupform"};const Xs={props:["data"],data(){return{modal:false}},template:"#xtemplate_succesful_modal"};const ei={props:["transaction_type"],data(){return{owl:undefined,data:[],loading:false}},mounted(){this.bonusFunds()},computed:{hasData(){if(Object.keys(this.data).length>0){return true}return false}},watch:{data(e,t){setTimeout(()=>{this.renderCarousel()},1)}},methods:{bonusFunds(){this.loading=true;axios({method:"POST",url:ajaxurl+"/getDiscount",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&transaction_type="+this.transaction_type,timeout:c}).then(e=>{if(e.data.code==1){this.data=e.data.details}else{this.data=[]}})["catch"](e=>{}).then(e=>{this.loading=false})},renderCarousel(){let e=false;if(typeof is_rtl!=="undefined"&&is_rtl!==null){e=is_rtl=="1"?true:false}this.owl=p(this.$refs.carousel_bonusfunds).owlCarousel({loop:true,dots:false,nav:false,autoWidth:true,rtl:e})}},template:`				
	<div ref="carousel_bonusfunds"  class="owl-carousel owl-theme carousel_bonusfunds">
		<div v-for="(items, index) in data" class="mr-2">	   
			<el-card shadow="hover" class="gradient-yellow-bg" style="max-width:350px;">			    
				<h6>{{items.title}}</h6>				
				<div class="mb-1">{{items.discount_details}}</div>
				<div class="font-weight-bold">{{items.valid_discount}}</div>
			</el-card>
		</div>
	</div>	
	`};const ti=Vue.createApp({components:{"component-wallet-balance":Zs,"components-wallet-transaction":Qs,"components-topup-form":Hs,"components-succesful-modal":Xs,"component-bonusfunds":ei},created(){this.getWalletTransaction()},data(){return{loading:false,tab:"all",data:[],page:1,show_next:false,loading:false,loading_next:false,payment_data:[]}},computed:{hasData(){if(Object.keys(this.data).length>0){return true}return false},getData(){return this.data},showNext(){return this.show_next}},methods:{showFundsForm(){this.$refs.topup.show()},tabChange(){this.data=[];this.page=1;this.getWalletTransaction(false)},nextPage(e){this.getWalletTransaction(true)},showLoadingBox(e){this.$refs.topup.loading_payment=true},closeLoadingBox(){this.$refs.topup.loading_payment=false},closeTopup(){this.$refs.topup.close()},getWalletTransaction(e){if(e){this.loading_next=true}else this.loading=true;axios({method:"POST",url:ajaxurl+"/getWalletTransaction",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&page="+this.page+"&transaction_type="+this.tab,timeout:c}).then(e=>{if(e.data.code==1){this.data.push(e.data.details.data);this.show_next=e.data.details.show_next_page;this.page=e.data.details.page}else{this.show_next=false}})["catch"](e=>{}).then(e=>{this.loading=false;this.loading_next=false})},afterPreparepayment(e){try{this.$refs[e.payment_code].Dopayment(e.data,e)}catch(t){ElementPlus.ElNotification({title:"",message:t,position:"bottom-right",type:"warning"})}},AfterCancelPayment(e){if(!f(e)){ElementPlus.ElNotification({title:"",message:e,position:"bottom-right",type:"warning"})}},afterSuccessfulpayment(e){this.payment_data=e;this.$refs.topup.close();if(typeof this.$refs.wallet_balance!=="undefined"&&this.$refs.wallet_balance!==null){this.$refs.wallet_balance.getBalance()}if(typeof this.$refs.wallet_balance_mobile!=="undefined"&&this.$refs.wallet_balance_mobile!==null){this.$refs.wallet_balance_mobile.getBalance()}this.$refs.succesful_modal.modal=true;if(this.tab!="all"){this.tab="all"}else{this.tabChange()}}}});if(typeof components_bundle!=="undefined"&&components_bundle!==null){p.each(components_bundle,function(e,t){ti.component(e,t)})}ti.use(ElementPlus);ti.use(Maska);const ai=ti.mount("#vue-my-wallet");const si=Vue.createApp({data(){return{email_address:"",loading:false}},methods:{submit(){this.loading=true;axios({method:"POST",url:ajaxurl+"/subscribetoemail",data:"YII_CSRF_TOKEN="+p("meta[name=YII_CSRF_TOKEN]").attr("content")+"&email_address="+this.email_address,timeout:c}).then(e=>{if(e.data.code==1){this.email_address="";ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"success"})}else{ElementPlus.ElNotification({title:"",message:e.data.msg,position:"bottom-right",type:"warning"})}})["catch"](e=>{}).then(e=>{this.loading=false})}}});si.use(ElementPlus);const ii=si.mount("#vue-esubscription")})(jQuery);