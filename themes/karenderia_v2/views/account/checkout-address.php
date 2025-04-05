<!--promoModal-->
<div class="modal" ref="address_modal" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModal" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">      
      <div class="modal-header border-bottom-0" style="padding-bottom:0px !important;">
        <h5 class="modal-title"><?php echo t("Delivery details")?></h5>        
        <a href="javascript:;" @click="closeModal" class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a>
      </div>
      <div class="modal-body">       
      

      <div  v-cloak v-if="error.length>0" class="alert alert-warning mb-2" role="alert">
	      <p v-cloak v-for="err in error" class="m-0">{{err}}</p>	    
	    </div>   
         	               
     <template v-if="cmaps_config.provider=='mapbox'">      
      <div  :class="{ 'map-large': cmaps_full, 'map-small': !cmaps_full }"  >
        <div ref="ref_map" id="cmaps" style="height:100%; width:100%;"></div>
      </div>	   
     </template>
     <template v-else>
	   <div ref="ref_map" id="cmaps" :class="{ 'map-large': cmaps_full, 'map-small': !cmaps_full }"  ></div>	   
     </template>
	   	   	        
	   <template v-if="!cmaps_full">
	   <div class="row mt-3" v-if="hasLocationData" >
	     <div class="col">
	       <h5 class="m-0">{{address1}}</h5>	       	       	       
		     <p class="m-0">{{formatted_address}}</p>		       
	     </div>
	     <div class="col text-right">
	       <button class="btn small btn-black" @click="adjustPin" :disabled="!hasLocationData" ><?php echo t("Adjust pin")?></button>
	     </div>
	   </div>
	   <!--row-->
	            
	  	    
	   <div class="forms mt-2 mb-2">

     <template v-if="cmaps_config.address_format_use==2">
        <div class="row">
				  <div class="col">
              <div class="form-label-group">
                <input class="form-control form-control-text" v-model="formatted_address"
                  id="formatted_address" type="text" >
                <label for="formatted_address"><?php echo t("Street name")?> (<?php echo t("Mandatory")?>)</label>
              </div>
          </div>
				  <div class="col">
              <div class="form-label-group">
                <input class="form-control form-control-text" v-model="address1"
                  id="address1" type="text" >
                <label for="address1"><?php echo t("Street number")?> (<?php echo t("Mandatory")?>)</label>
              </div>
          </div>
				</div>


        <div class="row">
				  <div class="col">
              <div class="form-label-group">
                <input class="form-control form-control-text" v-model="location_name"
                  id="location_name" type="text" >
                <label for="location_name"><?php echo t("Entrance")?></label>
              </div>
          </div>
				  <div class="col">
             <div class="form-label-group">
                <input class="form-control form-control-text" v-model="address2"
                  id="address2" type="text" >
                <label for="address2"><?php echo t("Floor")?></label>
              </div>
          </div>
			  </div>


        <div class="row">
				  <div class="col">
              <div class="form-label-group">
                <input class="form-control form-control-text" v-model="postal_code"
                  id="postal_code" type="text" >
                <label for="postal_code"><?php echo t("Door")?> (<?php echo t("Mandatory")?>)</label>
              </div>
          </div>
				  <div class="col">
             <div class="form-label-group">
                <input class="form-control form-control-text" v-model="company"
                  id="company" type="text" >
                <label for="company"><?php echo t("Company")?></label>
              </div>
          </div>
				</div>
     </template>
     <template v-else>
         <div class="form-label-group">
            <input class="form-control form-control-text" v-model="formatted_address"
              id="formatted_address" type="text" >
            <label for="formatted_address"><?php echo t("Street name")?></label>
          </div>

          <div class="form-label-group">
            <input class="form-control form-control-text" v-model="address1"
              id="address1" type="text" >
            <label for="address1"><?php echo t("Street number")?></label>
          </div>

          <div class="form-label-group">
            <input class="form-control form-control-text" v-model="location_name"
              id="location_name" type="text" >
            <label for="location_name"><?php echo t("Aparment, suite or floor")?></label>
          </div>
      </template>
       
      <h5 class="m-0 mt-2 mb-2"><?php echo t("Delivery options")?></h5>       
      <select class="form-control custom-select" v-model="delivery_options">		 
        <option v-for="(items, key) in delivery_options_data" :value="key" >{{items}}</option>      
	  </select>  
       
      <h5 class="m-0 mt-3 mb-2"><?php echo t("Add delivery instructions")?></h5>      
      <div class="form-label-group">    
        <textarea id="delivery_instructions" style="max-height:150px;" v-model="delivery_instructions"  class="form-control form-control-text font13" 
              placeholder="<?php echo t("eg. ring the bell after dropoff, leave next to the porch, call upon arrival, etc")?>">
        </textarea>       
      </div>  
                  
        <div class="btn-group btn-group-toggle input-group-small mb-4" >
           <label class="btn" v-for="(items, key) in address_label_data" 
               v-model="address_label" :class="{ active: address_label==key }" >
             <input v-model="address_label" type="radio" :value="key" > 
             {{ items }}
           </label>           
        </div>
	  <!--btn-group-->
	   
	   </div> <!--forms-->
     
	   </template>
      </div> <!--modal body-->
      
      <div class="modal-footer justify-content-start">
      
       <template v-if="!cmaps_full">
       <div class="border flex-fill">
           <button class="btn btn-black w-100" @click="closeModal" >
           <?php echo t("Cancel")?>
	       </button>
       </div>
       <div class="border flex-fill">
           <button class="btn btn-green w-100" @click="save" :class="{ loading: is_loading }" :disabled="!hasLocationData" >
	          <span class="label"><?php echo t("Save")?></span>
	          <div class="m-auto circle-loader" data-loader="circle-side"></div>
	       </button>
       </div>
       </template>
       
       <template v-else-if="cmaps_full">
        <div class="border flex-fill">
           <button class="btn btn-black w-100" @click="cancelPin" >
           <?php echo t("Cancel")?>
	       </button>
       </div>       
       <div class="border flex-fill">
           <button class="btn btn-green w-100" @click="setNewCoordinates" :class="{ loading: is_loading }" :disabled="!hasNewCoordinates"   >
	          <span class="label"><?php echo t("Set Pin")?></span>
	          <div class="m-auto circle-loader" data-loader="circle-side"></div>
	       </button>
       </div>
       </template>
      
      </div> <!--footer-->
    </div> <!--content-->
  </div> <!--dialog-->
</div> <!--modal-->              