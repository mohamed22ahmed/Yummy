let cybersource_handle;
const componentsCybersource = {
	props: [
		"title",
		"label",
		"payment_code",
		"merchant_id",
		"merchant_type",
		"publish_key",
		"access_key",
		"amount",
		"currency_code",
		"ajax_url",
		"cart_uuid",
		"on_error",
		"prefix",
		"reference"
	],
	
	data() {
		return {
			is_loading: false,
			error: [],
			order_uuid: "",
			default_payment_method:{},
			success: "",
			enabled_force: false,
			jwt_data: [],
			is_ready: false,
			payment_data: {},
			default_payment: {},
			transactionUrl: ''
		}
	},
	updated() {
		this.getDefaultPaymentMethod();
	},
	methods: {
		showPaymentForm() {
			this.error = [];
			$("#CybersourceForm").modal("show");
		},
		
		close() {
			dump("close");
			$("#CybersourceForm").modal("hide");
			this.is_ready = false
			this.is_loading = false;
		},
		
		closeRender() {
			$("#CybersourceRender").modal("hide");
			cybersource_handle.close();
			this.$emit("afterCancelPayment");
			this.is_loading = false;
		},

		submitForms() {
			var e = {
				YII_CSRF_TOKEN: $("meta[name=YII_CSRF_TOKEN]").attr("content"),
				payment_code: this.payment_code,
			};
			var a = 1;
			e = JSON.stringify(e);
			ajax_request_cmp[a] = $.ajax({
				url: ajaxurl + "/SavedPaymentProvider",
				method: "PUT",
				dataType: "json",
				data: e,
				contentType: $content_type.json,
				timeout: $timeout_cmp,
				crossDomain: true,
				beforeSend: (e) => {
					this.is_loading = true;
					this.error = [];
					if (ajax_request_cmp[a] != null) {
						ajax_request_cmp[a].abort();
					}
				},
			});
			ajax_request_cmp[a].done((e) => {
				if (e.code === 1) {
					this.error = [];
					this.close();
					this.$emit("setPaymentlist");
				} else {
					this.error = e.msg;
				}
			});
			ajax_request_cmp[a].always((e) => {
				this.is_loading = false;
			});
		},
		
		getDefaultPaymentMethod() {
			var e_csrf = {
				YII_CSRF_TOKEN: $("meta[name=YII_CSRF_TOKEN]").attr("content"),
			}
			
			var url = this.ajax_url + "/GetDefaultPayment" + this.prefix;
			e_csrf = JSON.stringify(e_csrf);
			
			ajax_request_cmp[1] = $.ajax({
				url: url,
				method: "PUT",
				dataType: "json",
				data: e_csrf,
				contentType: $content_type.json,
				timeout: $timeout_cmp,
				crossDomain: true
			}).done((e) => {
				if (e.code === 1) {
					this.default_payment_method = e.details;
				}
			});
		},
		
		PaymentRender(para) {
			this.order_uuid = para.order_uuid;
			this.error = [];
			if (para.force_payment_data) {
				this.enabled_force = true;
				this.currency_code = para.force_payment_data.use_currency_code;
				this.amount = para.force_payment_data.total_exchange;
			}
			
			var e_csrf = {
				YII_CSRF_TOKEN: $("meta[name=YII_CSRF_TOKEN]").attr("content"),
				reference_number: this.reference,
				payment_code: this.payment_code,
				amount: this.amount,
				currency: this.currency_code,
				access_key: this.access_key,
				transaction_uuid: this.order_uuid,
				profile_id: this.merchant_id,
				publish_key: this.publish_key,
				merchant_id: this.merchant_id,
				default_payment_method: this.default_payment_method
			}
			
			var url = this.ajax_url + "/PaymentRender" + this.prefix;
			e_csrf = JSON.stringify(e_csrf);
			
			ajax_request_cmp[1] = $.ajax({
				url: url,
				method: "PUT",
				dataType: "json",
				data: e_csrf,
				contentType: $content_type.json,
				timeout: $timeout_cmp,
				crossDomain: true,
				beforeSend: (e) => {
					this.is_loading = true;
					this.error = [];
					if (ajax_request_cmp[1] != null) {
						ajax_request_cmp[1].abort();
					}
				},
			});
			ajax_request_cmp[1].done((e) => {
				if (e.code === 1) {
					this.error = [];
					this.payment_data = e.details.data;
					this.transactionUrl = e.details.transactionUrl
					this.buildFieldsAndSubmit();
				} else {
					this.error = e.msg;
				}
			});
			ajax_request_cmp[1].always((e) => {
				this.is_loading = false;
			});
		},
		
		buildFieldsAndSubmit() {
			const form = document.getElementById("cybersource_form");
			form.action = this.transactionUrl;
			const firstFields = document.querySelector(".firstFields");
			Object.entries(this.payment_data).forEach(([name, value]) => {
				const input = document.createElement("input");
				input.type = "hidden";
				input.name = name;
				input.value = value;
				
				firstFields.appendChild(input);
			});
			
			form.submit();
		},
	},

	template:`
	   <div className="modal" id="CybersourceForm" tabIndex="-1" role="dialog" aria-labelledby="CybersourceForm" aria-hidden="true">
		 <div className="modal-dialog" role="document">
		   <div className="modal-content">
			 <div className="modal-body">
			     <a href="javascript:;" @click="close" class="btn btn-black btn-circle rounded-pill"><i class="zmdi zmdi-close font20"></i></a>
			     <h4 class="m-0 mb-3 mt-3">{{title}}</h4>
			     <p>{{label.notes}}</p>
			     <div v-if="is_loading">
				  <div className="loading mt-5">
					 <div className="m-auto circle-loader" data-loader="circle-side"></div>
				  </div>
			   </div>
				 <div v-cloak v-if="error.length>0" className="alert alert-warning mb-2" role="alert">
					 <p v-cloak v-for="err in error" className="m-0">{{err}}</p>
				 </div>
			 </div> <!--modal body-->
			 
			 <div className="modal-footer justify-content-start">
			   <button class="btn btn-green w-100" @click="submitForms" :class="{ loading: is_loading }"   >
				  <span class="label">{{label.submit}}</span>
				  <div className="m-auto circle-loader" data-loader="circle-side"></div>
			   </button>
			 </div> <!--footer-->
           </div> <!--content-->
         </div> <!--dialog-->
       </div> <!--modal-->
       
	   <div className="modal" id="payModal" tabIndex="-1" role="dialog" aria-labelledby="payModal" aria-hidden="true">
		 <div className="modal-dialog" role="document">
		   <div className="modal-content">
			 <div className="modal-body">
			 	<form id="cybersource_form" method="POST">
					<div className="firstFields"></div>
					<div>
						<button type="submit">submit</button>
					</div>
				</form>
			   <div v-if="is_loading">
				  <div className="loading mt-5">
					 <div className="m-auto circle-loader" data-loader="circle-side"></div>
				  </div>
			   </div>
			   <div v-cloak v-if="error.length>0" className="alert alert-warning mb-2" role="alert">
				   <p v-cloak v-for="err in error" className="m-0">{{err}}</p>
			   </div>
			 </div> <!--modal body-->
           </div> <!--content-->
         </div> <!--dialog-->
       </div> <!--modal-->
    `
};