   <div>
    <div class="row">
      <!-- Vue Commission Statement -->
      <div class="col-md-6"> <!-- Use col-*-* classes to control widths -->
        <div id="vue-commission-statement" v-cloak>
          <div class="bg-light p-3 mb-3 rounded">
            <div class="row align-items-center">
              <div class="col-lg-8 col-md-8 col-sm-6 mb-3 mb-xl-0"></div>
              <div class="col-lg-4 col-md-4 col-sm-6 text-md-right mb-3 mb-xl-0">
                <div class="dropdown">
                  <button class="btn btn-green" @click="createMerchantAdjustment" type="button">
                    <?php echo t("Create a Merchant Adjustment") ?>
                  </button>
                </div>
              </div>
            </div> <!-- row -->
          </div> <!-- rounded -->

          <components-datatable
            ref="datatable"
            ajax_url="<?php echo Yii::app()->createUrl('/api') ?>"
            actions="merchant_earninglist"
            :table_col=""
            :columns=""
            :date_filter='<?php echo false; ?>'
            :settings="{
              auto_load: '<?php echo true; ?>',
              filter: '<?php echo true; ?>',
              ordering: '<?php echo true; ?>',
              order_col: '',
              sortby: '',
              placeholder: '<?php echo t('Start date -- End date') ?>',
              separator: '<?php echo t('to') ?>',
              all_transaction: '<?php echo t('All transactions') ?>'
            }"
            page_limit="<?php echo Yii::app()->params->list_limit ?>"
            @view-transaction="viewMerchantTransaction">
          </components-datatable>

          <components-merchant-earning-adjustment
            ref="merchant_adjustment"
            ajax_url="<?php echo Yii::app()->createUrl('/api') ?>"
            :transaction_type_list='<?php echo json_encode($transaction_type2) ?>'
            :label="{
              title: '<?php echo t('Create adjustment') ?>',
              close: '<?php echo t('Close') ?>',
              submit: '<?php echo t('Submit') ?>',
              transaction_description: '<?php echo t('Transaction Description') ?>',
              transaction_amount: '<?php echo t('Amount') ?>',
              merchant: '<?php echo t('Merchant') ?>',
            }"
            @after-save="afterSave">
          </components-merchant-earning-adjustment>
        </div> <!-- vue-commission-statement -->
      </div> <!-- col-md-6 -->

      <!-- Vue Digital Wallet Transaction -->
      <div class="col-md-6"> <!-- Use col-*-* classes to control widths -->
        <div id="vue-digital-wallet-transaction" v-cloak>
          <div class="bg-light p-3 mb-3 rounded">
            <div class="row align-items-center">
              <div class="col-lg-8 col-md-8 col-sm-6 mb-3 mb-xl-0"></div>
              <div class="col-lg-4 col-md-4 col-sm-6 text-md-right mb-3 mb-xl-0">
                <div class="dropdown">
                  <button class="btn btn-green" @click="createTransaction" type="button">
                    <?php echo t("Create a Customer Adjustment") ?>
                  </button>
                </div>
              </div>
            </div> <!-- row -->
          </div> <!-- rounded -->

          <components-datatable
            ref="datatable"
            ajax_url="<?php echo Yii::app()->createUrl('/api') ?>"
            actions="digitalWalletTransactions"
            :table_col=""
            :columns=""
            :date_filter='<?php echo false; ?>'
            :settings="{
              auto_load: '<?php echo true; ?>',
              filter: '<?php echo false; ?>',
              ordering: '<?php echo true; ?>',
              order_col: '',
              sortby: '<?php echo $sortby; ?>',
              placeholder: '<?php echo CJavaScript::quote(t('Start date -- End date')) ?>',
              separator: '<?php echo CJavaScript::quote(t('to')) ?>',
              all_transaction: '<?php echo CJavaScript::quote(t('All transactions')) ?>'
            }"
            page_limit="<?php echo Yii::app()->params->list_limit ?>">
          </components-datatable>

          <components-create-adjustment-digitalwallet
            ref="create_adjustment"
            ajax_url="<?php echo Yii::app()->createUrl('/api') ?>"
            :transaction_type_list='<?php echo json_encode($transaction_type) ?>'
            :ref_id="<?php echo '' ?>"
            action_name='digitalwalletadjustment'
            :label="{
              title: '<?php echo CJavaScript::quote(t('Create adjustment')) ?>',
              close: '<?php echo CJavaScript::quote(t('Close')) ?>',
              submit: '<?php echo CJavaScript::quote(t('Submit')) ?>',
              transaction_description: '<?php echo CJavaScript::quote(t('Transaction Description')) ?>',
              transaction_amount: '<?php echo CJavaScript::quote(t('Amount')) ?>',
              customer_name: '<?php echo CJavaScript::quote(t('Customer')) ?>',
            }"
            @after-save="afterSave">
          </components-create-adjustment-digitalwallet>
        </div> <!-- vue-digital-wallet-transaction -->
      </div> <!-- col-md-6 -->
    </div> <!-- row -->
   </div>



<DIV id="vue-order-view" v-cloak class="position-relative fixed-height">

  <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
    <div>
      <div class="m-auto circle-loader medium" data-loader="circle-side"></div>
    </div>
  </div>

  <!--CONTENT SECTION-->

  <components-orderinfo
    ref="orderinfo"
    :group_name="group_name"
    ajax_url="<?php echo $ajax_url ?>"
    @after-update="afterUpdateStatus"
    @show-menu="showMerchantMenu"
    @refresh-order="refreshOrderInformation"
    @view-customer="viewCustomer"
    @to-print="toPrint"
    @delay-orderform="delayOrder"
    @rejection-orderform="orderReject"
    @order-history="orderHistory"
    @view-merchant-transaction="viewMerchantTransaction"
    @show-assigndriver="showAssigndriver"

    :manual_status="manual_status"
    :modify_order="modify_order"
    :filter_buttons="filter_buttons"
    :enabled_delay_order="<?php echo false; ?>"

    :refund_label="{
    title:'<?php echo CJavaScript::quote(t("Refund this item")) ?>',
    content:'<?php echo CJavaScript::quote(t("This automatically remove this item from your active orders.")) ?>',
    go_back:'<?php echo CJavaScript::quote(t("Go back")) ?>',
    complete:'<?php echo CJavaScript::quote(t("Confirm")) ?>',
  }"
    :remove_item="{
    title:'<?php echo CJavaScript::quote(t("Remove this item")) ?>',
    content:'<?php echo CJavaScript::quote(t("This will remove this item from your active orders.")) ?>',
    go_back:'<?php echo CJavaScript::quote(t("Go back")) ?>',
    confirm:'<?php echo CJavaScript::quote(t("Confirm")) ?>',
  }"
    :out_stock_label="{
    title:'<?php echo CJavaScript::quote(t("Item is Out of Stock")) ?>',
  }"

    :update_order_label="{
    title:'<?php echo CJavaScript::quote(t("Order decrease")) ?>',
    title_increase:'<?php echo CJavaScript::quote(t("Order Increase")) ?>',
    content:'<?php echo CJavaScript::quote(t("By accepting this order, we will refund the amount of {{amount}} to customer.")) ?>',
    content_collect:'<?php echo CJavaScript::quote(t("Total amount for this order has increase, Send invoice to customer or less from your account with total amount of {{amount}}.")) ?>',
    cancel:'<?php echo CJavaScript::quote(t("Cancel")) ?>',
    confirm:'<?php echo CJavaScript::quote(t("Confirm")) ?>',
    send_invoice:'<?php echo CJavaScript::quote(t("Send invoice")) ?>',
    less_acccount :'<?php echo CJavaScript::quote(t("Less on my account")) ?>',
    close :'<?php echo CJavaScript::quote(t("Close")) ?>',
    content_payment:'<?php echo CJavaScript::quote(t("This order has unpaid invoice, until its paid you cannot change the order status.")) ?>',
  }">
  </components-orderinfo>
  <!--CONTENT SECTION-->



  <components-delay-order
    ref="delay"
    @after-confirm="afterConfirmDelay"
    @after-update="afterUpdateStatus"
    :order_uuid="order_uuid"
    ajax_url="<?php echo $ajax_url ?>"
    :label="{
    title:'<?php echo CJavaScript::quote(t("Delay Order")) ?>',
    sub1:'<?php echo CJavaScript::quote(t("How much additional time you need?")) ?>',
    sub2:'<?php echo CJavaScript::quote(t("We'll notify the customer about the delay.")) ?>',
    confirm:'<?php echo CJavaScript::quote(t("Confirm")) ?>',
  }">
  </components-delay-order>


  <components-rejection-forms
    ref="rejection"
    ajax_url="<?php echo $ajax_url; ?>"
    @after-submit="afterRejectionFormsSubmit"
    @after-update="afterUpdateStatus"
    :order_uuid="order_uuid"
    :label="{
    title:'<?php echo CJavaScript::quote(t("Enter why you cannot make this order.")) ?>',
    reject_order:'<?php echo CJavaScript::quote(t("Reject order")) ?>',
    reason:'<?php echo CJavaScript::quote(t("Reason")) ?>',
  }">
  </components-rejection-forms>

  <components-order-history
    ref="history"
    ajax_url="<?php echo $ajax_url ?>"
    :order_uuid="order_uuid"
    :label="{
    title:'<?php echo CJavaScript::quote(t("Timeline")) ?>',
    close:'<?php echo CJavaScript::quote(t("Close")) ?>',
  }">
  </components-order-history>

  <components-order-print
    ref="print"
    :order_uuid="order_uuid"
    mode="popup"
    :line="75"
    ajax_url="<?php echo $ajax_url ?>">
  </components-order-print>


  <components-menu
    ref="menu"
    ajax_url="<?php echo $ajax_url ?>"
    @show-item="showItemDetails"

    image_placeholder="<?php echo websiteDomain() . Yii::app()->theme->baseUrl . "/assets/images/placeholder.png" ?>"
    merchant_id="<?php echo $merchant_id ?>"
    :label="{
    previous:'<?php echo CJavaScript::quote(t("Previous")) ?>',
    next:'<?php echo CJavaScript::quote(t("Next")) ?>',
  }">
  </components-menu>

  <components-item-details
    ref="item"
    ajax_url="<?php echo $ajax_url ?>"
    @go-back="showMerchantMenu"
    @close-menu="hideMerchantMenu"
    @refresh-order="refreshOrderInformation"

    image_placeholder="<?php echo websiteDomain() . Yii::app()->theme->baseUrl . "/assets/images/placeholder.png" ?>"
    merchant_id="<?php echo $merchant_id ?>"
    :order_type="order_type"
    :order_uuid="order_uuid">
  </components-item-details>

  <components-customer-details
    ref="customer"
    :client_id="client_id"
    ajax_url="<?php echo $ajax_url ?>"
    merchant_id="<?php echo $merchant_id ?>"
    image_placeholder="<?php echo websiteDomain() . Yii::app()->theme->baseUrl . "/assets/images/placeholder.png" ?>"
    page_limit="<?php echo Yii::app()->params->list_limit ?>"
    :label="{
    block_customer:'<?php echo CJavaScript::quote(t("Block Customer")) ?>',
    block_content:'<?php echo CJavaScript::quote(t("You are about to block this customer from ordering to your restaurant, click confirm to continue?")) ?>',
    cancel:'<?php echo CJavaScript::quote(t("Cancel")) ?>',
    confirm:'<?php echo CJavaScript::quote(t("Confirm")) ?>',
  }">
  </components-customer-details>


  <components-merchant-transaction
    ref="merchant_transaction"
    ajax_url="<?php echo Yii::app()->createUrl("/api") ?>"
    image_placeholder="<?php echo websiteDomain() . Yii::app()->theme->baseUrl . "/assets/images/placeholder.png" ?>"
    :label="{
    block : '<?php echo t("Deactivate Merchant") ?>',
    block_content : '<?php echo t("You are about to deactivate this merchant, click confirm to continue?") ?>',
    cancel : '<?php echo t("Cancel") ?>',
    confirm : '<?php echo t("Confirm") ?>',
  }">
  </components-merchant-transaction>

  <components-assign-driver
    ref="assign_driver"
    order_uuid="<?php echo $order_uuid ?>"
    ajax_url="<?php echo Yii::app()->createUrl("/api") ?>"
    @refresh-order="refreshOrderInformation"
    :map_center='<?php echo json_encode([
                    'lat' => isset($maps_config['default_lat']) ? $maps_config['default_lat'] : '',
                    'lng' => isset($maps_config['default_lng']) ? $maps_config['default_lng'] : '',
                  ]) ?>'
    zoom="<?php echo isset($maps_config['zoom']) ? $maps_config['zoom'] : ''; ?>">
  </components-assign-driver>

</DIV>
<!--vue-order-view-->

<?php $this->renderPartial("/order/order-details", array(
  'ajax_url' => $ajax_url,
  'view_admin' => $view_admin,
  'printer_list' => isset($printer_list) ? $printer_list : ''
)); ?>
<?php $this->renderPartial("/orders/template_print"); ?>
<?php $this->renderPartial("/orders/template_menu"); ?>
<?php $this->renderPartial("/orders/template_item"); ?>
<?php $this->renderPartial("/orders/template_customer_all"); ?>
<?php $this->renderPartial("/orders/template_assigned_driver", [
  'maps_config' => $maps_config
]); ?>

<DIV id="vue-bootbox">
  <component-bootbox
    ref="bootbox"
    @callback="Callback"
    size='small'
    :label="{
  confirm: '<?php echo CJavaScript::quote(t("Delete Confirmation")) ?>',
  are_you_sure: '<?php echo CJavaScript::quote(t("Are you sure you want to continue?")) ?>',
  yes: '<?php echo CJavaScript::quote(t("Yes")) ?>',
  cancel: '<?php echo CJavaScript::quote(t("Cancel")) ?>',
  ok: '<?php echo CJavaScript::quote(t("Okay")) ?>',
}">
  </component-bootbox>
</DIV>


<?php $this->renderPartial("//finance/template_merchant_transaction", array(
  'table_col_trans' => $table_col_trans,
  'columns_trans' => $columns_trans,
  'transaction_type' => $transaction_type,
  'sortby' => $sortby,
)) ?>