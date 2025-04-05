<div id="app-task" v-cloak>

<q-layout view="hHh Lpr lff" >
      <q-header class="bg-white text-dark" style="border-bottom:1px solid #dee2e6;">
        <q-toolbar>
           <q-btn href="<?php echo Yii::app()->createUrl("/driver/schedule")?>"  flat round dense icon="menu" class="q-mr-sm"></q-btn>
           
           <q-toolbar-title>
              <?php echo CHtml::encode($this->pageTitle)?>
           </q-toolbar-title>              

          <component-grouplist 
          ref="grouplist"
          ajax_url="<?php echo $ajax_url;?>" 
          :label="{
            all_groups : '<?php echo CJavaScript::quote(t("All Groups"));?>',                   
          }"  
          >
          </component-grouplist>

          <q-btn-group outline class="q-mr-md">
             <q-btn href="<?php echo Yii::app()->createUrl("/driver/mapview")?>" color="dark" label="Map" icon="map" ></q-btn>
             <q-btn href="<?php echo Yii::app()->createUrl("/driver/orders")?>" outline color="dark" label="List" icon="format_list_bulleted" ></q-btn>
          </q-btn-group>

          <q-spacer></q-spacer>

          <!-- <q-btn round icon="notifications" size="sm" class="q-mr-sm" >
            <q-badge floating color="red" rounded />
          </q-btn> -->

          <components-notifications
          ref="notifications"
          ajax_url="<?php echo $ajax_url;?>"
          view_url="<?php echo Yii::app()->createUrl("/notifications/all_notification")?>" 
          :realtime="{
            enabled : '<?php echo Yii::app()->params['realtime_settings']['enabled']==1?true:false ;?>',  
            provider : '<?php echo CJavaScript::quote( Yii::app()->params['realtime_settings']['provider'] )?>',  			   
            key : '<?php echo CJavaScript::quote( Yii::app()->params['realtime_settings']['key'] )?>',  			   
            cluster : '<?php echo CJavaScript::quote( Yii::app()->params['realtime_settings']['cluster'] )?>', 
            ably_apikey : '<?php echo CJavaScript::quote( Yii::app()->params['realtime_settings']['ably_apikey'] )?>', 
            piesocket_api_key : '<?php echo CJavaScript::quote( Yii::app()->params['realtime_settings']['piesocket_api_key'] )?>', 
            piesocket_websocket_api : '<?php echo CJavaScript::quote( Yii::app()->params['realtime_settings']['piesocket_websocket_api'] )?>', 
            piesocket_clusterid : '<?php echo CJavaScript::quote( Yii::app()->params['realtime_settings']['piesocket_clusterid'] )?>', 
            channel : '<?php echo CJavaScript::quote( Yii::app()->params->realtime['admin_channel'] )?>',  			   
            event : '<?php echo CJavaScript::quote( Yii::app()->params->realtime['notification_event'] )?>',  
          }"  			
          @after-receivenotifications="afterReceivenotifications"
          >
          </components-notifications>

          <q-spacer></q-spacer>
          

        </q-toolbar>
      </q-header>

      <q-page-container>
        <q-page class="bg-grey-2">          
       
        <q-card class="q-card-light">
            <q-card-section class="row items-center justify-between q-pa-sm q-pl-lg q-pr-lg">
            
               <div class="col">
               
                <div class="row items-center q-gutter-md" >
                    <div class="col">
                    <q-input color="white"                
                        dense                
                        label-color="dark" v-model="q" label="Search Orders" class="full-width text-color">
                        <template v-slot:prepend>
                            <q-icon name="search" color="dark" ></q-icon>
                        </template>
                        <template v-slot:append>                    
                            <q-btn @click="filterOrder" :loading="filter_loading" color="brandgreen" unelevated text-color="white" label="Search" no-caps ></q-btn>
                        </template>
                        </q-input>         
                    </div>
                    
                    <div class="col">                     
                    <!-- <q-select 
                    v-model="filter_status" 
                    :options="<?php echo CHtml::encode(json_encode($delivery_status))?>" 
                    label="All Filters" 
                    dense               
                    clearable      
                    multiple
                    emit-value
                    map-options
                    >
                    </q-select> -->                    
                    <q-select                      
                      v-model="filter_status"
                      :options="<?php echo CHtml::encode(json_encode($delivery_status))?>" 
                      label="All Filters" 
                      dense     
                      multiple
                      emit-value
                      map-options
                      clearable
                    >
                    <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
                      <q-item tag="label" clickable >
                        <q-item-section>
                          <q-item-label v-html="opt.label" ></q-item-label>
                        </q-item-section>
                        <q-item-section side>                          
                          <q-checkbox 
                          dense 
                          v-model="selected_status" 
                          :val="opt.value" 
                          color="brandgreen" 
                          @update:model-value="onChooseStatus"
                          >
                          </q-checkbox>
                        </q-item-section>
                      </q-item>
                    </template>
                  </q-select>

                    </div>
                </div>
                <!-- row -->

               </div>

               <div class="col text-right">
                  <q-btn flat round color="grey-5" icon="refresh" @click="refreshOrderList" ></q-btn>
                  <q-btn flat round color="grey" icon="filter_alt" ></q-btn>
               </div>

            </q-card-section>
        </q-card>
          

        <div class="q-pa-md">          
           <components-orderlist
           ref="order_list"
           task_url="<?php echo $task_url;?>" 
           lenght="10"
           sortby="order_id"
           :loading="loading"
           :q="q"
           @after-search="afterSearch"
           >
           </components-orderlist>
        </div>
        
        </q-page>
      </q-page-container>

</q-layout>      

</div>
<!-- app-task -->