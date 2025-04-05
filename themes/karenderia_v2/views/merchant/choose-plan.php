<div id="vue-subscription" class="container mt-4 mb-4" v-cloak>

<div class="text-center" style="max-width: 400px;margin:auto;">
  <h2 class="font-weight-bolder"><?php echo t("Flexible")?> <span class="text-green"><?php echo t("Pricing")?></span> <?php echo t("for Every")?> 
  <span class="text-green"><?php echo t("Restaurant")?></span></h2>
  <br/>
  <h6><?php echo t("Transparent pricing. No hidden costs. Advanced features to elevate your business")?>.</h6>
  <br/>
</div>

<!-- <div class="text-center">
   <div class="wrap-radio-selection">
    <el-radio-group v-model="value1" size="large">
      <el-radio-button label="New York" value="1" ></el-radio-button>
      <el-radio-button label="Washington" value="Washington" ></el-radio-button>
      <el-radio-button label="Los Angeles" value="Los Angeles" ></el-radio-button>
      <el-radio-button label="Chicago" value="Chicago" ></el-radio-button>
    </el-radio-group>
    </div>
</div> -->

<br/>

<div class="row justify-content-center pricing-plans q-gutter-md" v-loading="is_loading" >  
  <template v-for="items in data">
  <div class="plans position-relative mb-3">
     <div class="icon"><i class="zmdi zmdi-fire"></i></div>
     <div class="mt-3 mb-3">
      <h4 class="mb-0">{{ items.title }}</h4>
      <div class="ellipsis-2-lines" v-html="items.description"></div>
     </div>

     <h4 class=" font-weight-bolder">
      <template v-if="items.promo_price_raw>0">
        <span class="text-muted opacity-60"><del>{{items.price}}</del></span> <span>{{ items.promo_price }}</span>
      </template>
      <template v-else>
        {{items.price}}
      </template>
     </h4>

    <template v-if="plan_details[items.package_id]">
    <ul>
      <template v-for="features in plan_details[items.package_id]">
         <li>{{ features }}</li>
      </template>      
    </ul>
    </template>
    
    <div class="mt-3">      
      <!-- <button 
      @click="setPlan(items.package_id,items.package_uuid)"
      type="button" 
      class="btn btn-outline-successx btn-block"
      :class="isActive(items)"      
      >
      Choose Plan
      </button> -->
      <a :href="items.plan_url" class="btn btn-outline-success btn-block">
      <?php echo t("Choose Plan")?>
      </a>      
    </div>
  </div>  
  </template>
</div>

<br/><br/>

</div> <!--container-->