<DIV id="vue-order-view" v-cloak class="position-relative fixed-height">
    <div v-if="is_loading" class="loading cover-loader d-flex align-items-center justify-content-center">
        <div>
            <div class="m-auto circle-loader medium" data-loader="circle-side"></div>
        </div>
    </div>

    <components-orderinfo
        ref="orderinfo"
        :group_name="group_name"
        ajax_url="<?php echo $ajax_url?>"
        @after-update="afterUpdateStatus"
        @show-menu="showMerchantMenu"
        @refresh-order="refreshOrderInformation"
        @view-customer="viewCustomer"
        @to-print="toPrint"
        @delay-orderform="delayOrder"
        @rejection-orderform = "orderReject"
        @order-history = "orderHistory"
        @show-assigndriver="showAssigndriver"

        :manual_status="manual_status"
        :modify_order="modify_order"
        :filter_buttons="filter_buttons"
        :enabled_delay_order="<?php echo true;?>"

        :refund_label="{
            title:'<?php echo CJavaScript::quote("Refund this item")?>',
            content:'<?php echo CJavaScript::quote("This automatically remove this item from your active orders.")?>',
            go_back:'<?php echo CJavaScript::quote("Go back")?>',
            complete:'<?php echo CJavaScript::quote("Confirm")?>',
        }"
        :remove_item="{
            title:'<?php echo CJavaScript::quote("Remove this item")?>',
            content:'<?php echo CJavaScript::quote("This will remove this item from your active orders.")?>',
            go_back:'<?php echo CJavaScript::quote("Go back")?>',
            confirm:'<?php echo CJavaScript::quote("Confirm")?>',
        }"
        :out_stock_label="{
            title:'<?php echo CJavaScript::quote("Item is Out of Stock")?>',
        }"

        :update_order_label="{
            title:'<?php echo CJavaScript::quote(t("Order decrease"))?>',
            title_increase:'<?php echo CJavaScript::quote(t("Order Increase"))?>',
            content:'<?php echo CJavaScript::quote(t("By accepting this order, we will refund the amount of {{amount}} to customer."))?>',
            content_collect:'<?php echo CJavaScript::quote(t("Total amount for this order has increase, Send invoice to customer or less from your account with total amount of {{amount}}."))?>',
            cancel:'<?php echo CJavaScript::quote(t("Cancel"))?>',
            confirm:'<?php echo CJavaScript::quote(t("Confirm"))?>',
            send_invoice:'<?php echo CJavaScript::quote(t("Send invoice"))?>',
            less_acccount :'<?php echo CJavaScript::quote(t("Less on my account"))?>',
            close :'<?php echo CJavaScript::quote(t("Close"))?>',
            content_payment:'<?php echo CJavaScript::quote(t("This order has unpaid invoice, until its paid you cannot change the order status."))?>',
            content_payment:'<?php echo CJavaScript::quote(t("This order has unpaid invoice, until its paid you cannot change the order status."))?>',
            collect_cash:'<?php echo CJavaScript::quote(t("By accepting this order we will less the commission total {{amount}} to your account."))?>',
        }"
    >
    </components-orderinfo>

    <components-delay-order
            ref="delay"
            @after-confirm="afterConfirmDelay"
            @after-update="afterUpdateStatus"
            :order_uuid="order_uuid"
            ajax_url="<?php echo $ajax_url?>"
            :label="{
                title:'<?php echo CJavaScript::quote("Delay Order")?>',
                sub1:'<?php echo CJavaScript::quote("How much additional time you need?")?>',
                sub2:'<?php echo CJavaScript::quote("We'll notify the customer about the delay.")?>',
                confirm:'<?php echo CJavaScript::quote("Confirm")?>',
            }"
    >
    </components-delay-order>

    <components-rejection-forms
            ref="rejection"
            ajax_url="<?php echo $ajax_url;?>"
            @after-submit="afterRejectionFormsSubmit"
            @after-update="afterUpdateStatus"
            :order_uuid="order_uuid"
            :label="{
                title:'<?php echo CJavaScript::quote("Enter why you cannot make this order.")?>',
                reject_order:'<?php echo CJavaScript::quote("Reject order")?>',
                reason:'<?php echo CJavaScript::quote("Reason")?>',
            }"
    >
    </components-rejection-forms>

    <components-order-history
            ref="history"
            ajax_url="<?php echo $ajax_url?>"
            :order_uuid="order_uuid"
            :label="{
                title:'<?php echo CJavaScript::quote("History")?>',
                close:'<?php echo CJavaScript::quote("Close")?>',
            }"
    >
    </components-order-history>

    <components-order-print
        ref="print"
        :order_uuid="order_uuid"
        mode="popup"
        :line="75"
        ajax_url="<?php echo $ajax_url?>"
    >
    </components-order-print>

    <components-menu
        ref="menu"
        ajax_url="<?php echo $ajax_url?>"
        @show-item="showItemDetails"

        image_placeholder="<?php echo websiteDomain().Yii::app()->theme->baseUrl."/assets/images/placeholder.png"?>"
        merchant_id="<?php echo $merchant_id?>"
        :label="{
            previous:'<?php echo CJavaScript::quote("Previous")?>',
            next:'<?php echo CJavaScript::quote("Next")?>',
        }"
        :responsive='<?php echo json_encode($responsive);?>'
    >
    </components-menu>

    <components-item-details
        ref="item"
        ajax_url="<?php echo $ajax_url?>"
        @go-back="showMerchantMenu"
        @close-menu="hideMerchantMenu"
        @refresh-order="refreshOrderInformation"

        image_placeholder="<?php echo websiteDomain().Yii::app()->theme->baseUrl."/assets/images/placeholder.png"?>"
        merchant_id="<?php echo $merchant_id?>"
        :order_type="order_type"
        :order_uuid="order_uuid"
    >
    </components-item-details>

    <components-customer-details
        ref="customer"
        :client_id="client_id"
        ajax_url="<?php echo $ajax_url?>"
        merchant_id="<?php echo $merchant_id?>"
        image_placeholder="<?php echo websiteDomain().Yii::app()->theme->baseUrl."/assets/images/placeholder.png"?>"
        page_limit = "<?php echo Yii::app()->params->list_limit?>"
        :label="{
            block_customer:'<?php echo CJavaScript::quote("Block Customer")?>',
            block_content:'<?php echo CJavaScript::quote("You are about to block this customer from ordering to your restaurant, click confirm to continue?")?>',
            cancel:'<?php echo CJavaScript::quote("Cancel")?>',
            confirm:'<?php echo CJavaScript::quote("Confirm")?>',
        }"
    >
    </components-customer-details>

    <components-assign-driver
        ref="assign_driver"
        order_uuid="<?php echo $order_uuid?>"
        ajax_url="<?php echo $ajax_url?>"
        @refresh-order="refreshOrderInformation"
        :map_center='<?php echo json_encode([
            'lat'=>isset($maps_config['default_lat'])?$maps_config['default_lat']:'',
            'lng'=>isset($maps_config['default_lng'])?$maps_config['default_lng']:'',
        ])?>'
        zoom="<?php echo isset($maps_config['zoom'])?$maps_config['zoom']:'';?>"
    >
    </components-assign-driver>
</DIV>

<?php $this->renderPartial("/order/order-details",array(
    'ajax_url'=>$ajax_url,
    'view_admin'=>$view_admin,
    'printer_list'=>isset($printer_list)?$printer_list:''
)); ?>
<?php $this->renderPartial("/order/template_print");?>
<?php $this->renderPartial("/order/template_menu");?>
<?php $this->renderPartial("/order/template_item");?>
<?php $this->renderPartial("/order/template_customer");?>
<?php $this->renderPartial("/order/template_assigned_driver",[
    'maps_config'=>$maps_config
]);?>

<DIV id="vue-bootbox">
    <component-bootbox
        ref="bootbox"
        @callback="Callback"
        size='small'
        :label="{
          confirm: '<?php echo CJavaScript::quote(t("Delete Confirmation"))?>',
          are_you_sure: '<?php echo CJavaScript::quote(t("Are you sure you want to continue?"))?>',
          yes: '<?php echo CJavaScript::quote(t("Yes"))?>',
          cancel: '<?php echo CJavaScript::quote(t("Cancel"))?>',
          ok: '<?php echo CJavaScript::quote(t("Okay"))?>',
        }"
    >
    </component-bootbox>
</DIV>