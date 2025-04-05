<template v-if="hasSearch">

    <template v-if="!hasResults">
      <h4><?php echo t("No results for")?> “{{q}}”</h4>
      <p><?php echo t("Sorry, no product matched for your search. Please try again")?>.</p> 
    </template>
    <template v-else>
      <h4><?php echo t("Search for")?> "{{q}}"</h4>        
        <div class="row">
            <div v-for="items in getData" class="col-lg-6 col-sm-12 mb-3">        
                <el-card shadow="hover" :body-style="{ padding: '0px' }" >
                    <div class="d-flex align-items-start align-items-stretch">
                    <div class="borderx">            
                    <!-- <img
                        :src="items.url_image"
                        class="w-100 h-100"
                        style="max-height: 130px;"
                        fit="cover"
                        /> -->
                        <el-image
                            style="max-height: 130px;"
                            class="w-100 h-100"
                            :src="items.url_image"
                            fit="cover"
                            lazy
                        >
                        </el-image>
                    </div>
                    <div class="p-2 borderx w-100">
                    
                    
                    <div class="d-flex align-items-start flex-column" >
                        <h6>{{items.item_name}}</h6>
                        <p class="d-inline-block text-truncate" style="max-width: 200px;" v-html="items.item_description"></p>
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <template v-if="items.price[0]">                       
                            <template v-if="items.price[0].discount>0">
                                <h5 class="m-0 text-grey"><del>{{ items.price[0].pretty_price }}</del></h5>
                                <h5 class="m-0">{{ items.price[0].pretty_price_after_discount }}</h5>
                            </template>
                            <template v-else>
                                <h5 class="m-0">{{ items.price[0].pretty_price }}</h5>
                            </template>
                            </template>
                            <div>                                
                                <template v-if="items.not_for_sale || !items.available">
                                    <el-button disabled color="#b2b2b2" class="white-color">
                                    <?php echo t("Unavailable")?>
                                    </el-button>
                                </template>
                                <template v-else>
                                    <el-button @click="viewItem({cat_id:items.cat_id,item_uuid:items.item_uuid})" color="#b2b2b2" class="white-color">
                                    <?php echo t("Add")?>
                                    </el-button>
                                </template>
                            </div>
                        </div>
                    </div>

                    </div>
                    </div>
                </el-card>
            </div>
        </div>	
        <!-- row-->
</template>

</template>