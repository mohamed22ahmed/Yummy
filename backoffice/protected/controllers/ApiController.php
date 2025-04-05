<?php
class ApiController extends CommonApi
{
    private $pdo;

    public function beforeAction($action)
    {
        $db_host = DB_HOST;
        $db_name = DB_NAME;
        $this->pdo = new PDO("mysql:host=$db_host;dbname=$db_name", DB_USER, DB_PASSWORD);        $method = Yii::app()->getRequest()->getRequestType();
        if ($method == "PUT") {
            $this->data = Yii::app()->input->xssClean(json_decode(file_get_contents('php://input'), true));
        } else $this->data = Yii::app()->input->xssClean($_POST);
        return true;
    }

    public function actionorderList()
    {
        $merchant_id = Yii::app()->merchant->merchant_id ?? 0;
        $order_status = isset($this->data['order_status']) ? $this->data['order_status'] : '';
        $schedule = $this->data['schedule'] != '' ? true : false;
        $with_delivery = $this->data['with_delivery'] != '' ? true : false;
        $filter = isset($this->data['filter']) ? $this->data['filter'] : '';

        try {
            $data = AOrders::getOrderAll($merchant_id, $order_status, $schedule, $with_delivery, date("Y-m-d"), date("Y-m-d g:i:s a"), $filter);
            $meta = AOrders::getOrderMeta($data['all_order']);
            $status = COrders::statusList(Yii::app()->language);
            $services = COrders::servicesList(Yii::app()->language);

            $this->code = 1;
            $this->msg = "OK";
            $this->details = array(
                'data' => $data['data'],
                'total' => $data['total'],
                'meta' => $meta,
                'status' => $status,
                'services' => $services,
            );
        } catch (Exception $e) {
            $this->msg[] = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetDelayedMinutes()
    {
        $times = AttributesTools::delayedMinutes();
        $this->code = 1;
        $this->msg = "ok";
        $this->details = $times;
        $this->responseJson();
    }

    public function actionMerchantOrderingStatus()
    {
        try {
            $data = AR_merchant_meta::getMeta(Yii::app()->merchant?->merchant_id??0, array(
                'accepting_order',
                'pause_time',
                'pause_interval'
            ));
            $accepting_order = isset($data['accepting_order']) ? $data['accepting_order']['meta_value'] : true;
            $accepting_order = $accepting_order == 1 ? true : false;
            $pause_time = isset($data['pause_time']) ? trim($data['pause_time']['meta_value']) : '';

            if (!$accepting_order) {
                $pause_time = Date_Formatter::dateTime($pause_time, "yyyy-MM-ddTHH:mm", true);
            } else $pause_time = Date_Formatter::dateTime(date("c"), "yyyy-MM-ddTHH:mm", true);

            $this->code = 1;
            $this->msg = "ok";
            $this->details = array(
                'accepting_order' => $accepting_order,
                'pause_time' => $pause_time,
            );
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetPauseOptions()
    {
        try {
            $times = AttributesTools::delayedMinutes();
            $pause_reason = AOrders::rejectionList('pause_reason', Yii::app()->language);

            $array = array(
                'id' => "other",
                'value' => t("Other")
            );
            array_push($times, $array);

            $this->code = 1;
            $this->msg = "ok";
            $this->details = array(
                'times' => $times,
                'pause_reason' => $pause_reason
            );
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetOrderFilterSettings()
    {
        try {
            $data = array(
                'status_list' => AttributesTools::getOrderStatus(Yii::app()->language),
                'order_type_list' => AttributesTools::ListSelectServices(),
                'payment_status_list' => AttributesTools::statusManagementTranslationList('payment', Yii::app()->language),
                'sort_list' => AttributesTools::orderSortList()
            );
            $this->code = 1;
            $this->msg = "OK";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetCategory()
    {
        try {
            $merchant_id = isset($this->data['merchant_id']) ? $this->data['merchant_id'] : 0;
            $category = CMerchantMenu::getCategory(intval($merchant_id), Yii::app()->language);

            $this->code = 1;
            $this->msg = "OK";
            $this->details = array(
                'data' => $category
            );
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actioncategoryItem()
    {
        try {
            $merchant_id = isset($this->data['merchant_id']) ? $this->data['merchant_id'] : 0;
            if($merchant_id == 0 || $merchant_id == ""){
                $merchant_id = Yii::app()->cache->get('merchant_id');
            }

            $cat_id = isset($this->data['cat_id']) ? intval($this->data['cat_id']) : 0;
            $page  = isset($this->data['page']) ? (int)$this->data['page'] : 0;
            $search = isset($this->data['q']) ? trim($this->data['q']) : '';
            $items = array();
            $items  = CMerchantMenu::CategoryItem(intval($merchant_id), $cat_id, $search, $page, Yii::app()->language);
            $this->code = 1;
            $this->msg = "OK";
            $this->details = $items;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionUpdateOrderingStatus()
    {
        try {
            $accepting_order = isset($this->data['accepting_order']) ? $this->data['accepting_order'] : false;
            $accepting_order = $accepting_order == 1 ? true : false;
            AR_merchant_meta::saveMeta(Yii::app()->merchant->merchant_id, 'accepting_order', $accepting_order);
            AR_merchant_meta::saveMeta(Yii::app()->merchant->merchant_id, 'pause_time', '');
            AR_merchant_meta::saveMeta(Yii::app()->merchant->merchant_id, 'pause_reason', '');

            try {
                $merchant = CMerchants::get(Yii::app()->merchant?->merchant_id??0);
                $merchant->pause_ordering = false;
                $merchant->save();
            } catch (Exception $e) {
                //
            }

            $this->code = 1;
            $this->msg = "ok";
            $this->details = array(
                'pause_time' => '',
                'accepting_order' => $accepting_order
            );
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetOrderTab()
    {
        $group_name = isset($this->data['group_name']) ? $this->data['group_name'] : '';
        $criteria = new CDbCriteria;
        $criteria->select = "group_name,stats_id";
        $criteria->order = "id ASC";
        $criteria->addCondition('group_name =:group_name');
        $criteria->params = array(':group_name' => trim($group_name));
        $model = AR_order_settings_tabs::model()->findAll($criteria);

        if ($model) {
            $data = [];
            foreach ($model as $items) {
                array_push($data, $items->stats_id);
            }
            $this->code = 1;
            $this->msg = "ok";
            $this->details = $data;
        } else $this->msg = t("No results");
        $this->responseJson();
    }

    public function actionsaveOrderTab()
    {
        if (DEMO_MODE) {
            $this->msg[] = t("Modification not available in demo");
            $this->responseJson();
        }

        $group_name = isset($this->data['group_name']) ? $this->data['group_name'] : '';
        $status = isset($this->data['status']) ? $this->data['status'] : '';
        Yii::app()->db->createCommand("DELETE FROM {{order_settings_tabs}} 
		WHERE group_name=" . q($group_name) . " ")->query();
        if (is_array($status) && count($status) >= 1) {
            $params = array();
            foreach ($status as $val) {
                $params[] = array(
                    'group_name' => $group_name,
                    'stats_id' => intval($val),
                    'date_modified' => CommonUtility::dateNow(),
                    'ip_address' => CommonUtility::userIp()
                );
            }
            try {
                $builder = Yii::app()->db->schema->commandBuilder;
                $command = $builder->createMultipleInsertCommand("{{order_settings_tabs}}", $params);
                $command->execute();
            } catch (Exception $e) {
                $this->msg[] = $e->getMessage();
                $this->responseJson();
            }
        }
        $this->code = 1;
        $this->msg = t("Setting saved");
        $this->responseJson();
    }

    public function actionsaveOrderButtons()
    {
        if (DEMO_MODE) {
            $this->msg[] = t("Modification not available in demo");
            $this->responseJson();
        }
        $group_name = isset($this->data['group_name']) ? $this->data['group_name'] : '';
        $button_name = isset($this->data['button_name']) ? $this->data['button_name'] : '';
        $status = isset($this->data['status']) ? $this->data['status'] : '';
        $order_type = isset($this->data['order_type']) ? $this->data['order_type'] : '';
        $uuid = isset($this->data['uuid']) ? $this->data['uuid'] : '';
        $do_actions = isset($this->data['do_actions']) ? $this->data['do_actions'] : '';
        $class_name = isset($this->data['class_name']) ? $this->data['class_name'] : '';

        if (!empty($uuid)) {
            $model = AR_order_settings_buttons::model()->find("uuid=:uuid", array(
                ':uuid' => $uuid
            ));
            if (!$model) {
                $this->msg = t("Record not found");
                $this->responseJson();
            }
        } else $model = new AR_order_settings_buttons;

        $model->group_name = $group_name;
        $model->button_name = $button_name;
        $model->stats_id = intval($status);
        $model->order_type = trim($order_type);
        $model->do_actions = $do_actions;
        $model->class_name = $class_name;
        if ($model->save()) {
            $this->code = 1;
            $this->msg = "ok";
        } else $this->msg = CommonUtility::parseError($model->getErrors());
        $this->responseJson();
    }

    public function actiongetOrderButtonList()
    {
        $group_name = isset($this->data['group_name']) ? $this->data['group_name'] : '';
        $criteria = new CDbCriteria;
        $criteria->select = "uuid,button_name,order_type,
		(
		  select description 
		  from {{order_status_translation}}
		  where language=" . q(Yii::app()->language) . "
		  and stats_id = t.stats_id
		) as status		
		";
        $criteria->order = "id ASC";
        $criteria->addCondition('group_name =:group_name');
        $criteria->params = array(':group_name' => trim($group_name));
        $model = AR_order_settings_buttons::model()->findAll($criteria);
        if ($model) {
            $data = array();
            foreach ($model as $item) {
                $data[] = array(
                    'uuid' => $item->uuid,
                    'button_name' => $item->button_name,
                    'order_type' => $item->order_type,
                    'status' => $item->status
                );
            }
            $this->code = 1;
            $this->msg = "ok";
            $this->details = $data;
        } else $this->msg[] = t("No results");
        $this->responseJson();
    }

    public function actiondeleteButtons()
    {
        if (DEMO_MODE) {
            $this->msg[] = t("Modification not available in demo");
            $this->responseJson();
        }

        $uuid = isset($this->data['uuid']) ? $this->data['uuid'] : '';
        $model = AR_order_settings_buttons::model()->find("uuid=:uuid", array(
            ':uuid' => $uuid
        ));
        if ($model) {
            $model->delete();
            $this->code = 1;
            $this->msg = "OK";
        } else $this->msg = t("Record not found");
        $this->responseJson();
    }

    public function actiongetButtons()
    {
        $uuid = isset($this->data['uuid']) ? $this->data['uuid'] : '';
        $model = AR_order_settings_buttons::model()->find("uuid=:uuid", array(
            ':uuid' => $uuid
        ));
        if ($model) {
            $this->code = 1;
            $this->msg = "OK";
            $this->details = array(
                'uuid' => $model->uuid,
                'button_name' => $model->button_name,
                'stats_id' => $model->stats_id,
                'order_type' => $model->order_type,
                'do_actions' => $model->do_actions,
                'class_name' => $model->class_name
            );
        } else $this->msg = t("Record not found");
        $this->responseJson();
    }

    public function actioncommissionBalance()
    {
        try {
            $card_id = CWallet::createCard(Yii::app()->params->account_type['admin']);
            $balance = CWallet::getBalance($card_id);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
            $balance = 0;
        }

        $this->code = 1;
        $this->msg = "OK";
        $this->details = array(
            'balance' => Price_Formatter::formatNumberNoSymbol($balance),
            'price_format' => array(
                'symbol' => Price_Formatter::$number_format['currency_symbol'],
                'decimals' => Price_Formatter::$number_format['decimals'],
                'decimal_separator' => Price_Formatter::$number_format['decimal_separator'],
                'thousand_separator' => Price_Formatter::$number_format['thousand_separator'],
                'position' => Price_Formatter::$number_format['position'],
            )
        );
        $this->responseJson();
    }

    public function actiontransactionHistory()
    {
        $data = array();
        $card_id = 0;
        try {
            $card_id = CWallet::getCardID(Yii::app()->params->account_type['admin']);
        } catch (Exception $e) {
            // do nothing
        }

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';

        $sortby = "transaction_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        $criteria->addCondition('card_id=:card_id');
        $criteria->params = array(':card_id' => intval($card_id));

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(transaction_date,'%Y-%m-%d')", $date_start, $date_end);
        }
        if (is_array($transaction_type) && count($transaction_type) >= 1) {
            $criteria->addInCondition('transaction_type', (array) $transaction_type);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_wallet_transactions::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        if ($length > 0) {
            $pages->applyLimit($criteria);
        }
        $models = AR_wallet_transactions::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {
                $description = Yii::app()->input->xssClean($item->transaction_description);
                $parameters = json_decode($item->transaction_description_parameters, true);
                if (is_array($parameters) && count($parameters) >= 1) {
                    $description = t($description, $parameters);
                }

                $transaction_amount = Price_Formatter::formatNumber($item->transaction_amount);
                switch ($item->transaction_type) {
                    case "debit":
                    case "payout":
                        $transaction_amount = "(" . Price_Formatter::formatNumber($item->transaction_amount) . ")";
                        break;
                }

                $trans_html = <<<HTML
<p class="m-0 $item->transaction_type">$transaction_amount</p>
HTML;


                $data[] = array(
                    'transaction_date' => Date_Formatter::date($item->transaction_date),
                    'transaction_description' => $description,
                    'transaction_amount' => $trans_html,
                    'running_balance' => Price_Formatter::formatNumber($item->running_balance),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actioncommissionadjustment()
    {
        try {

            $transaction_description = isset($this->data['transaction_description']) ? $this->data['transaction_description'] : '';
            $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';
            $transaction_amount = isset($this->data['transaction_amount']) ? $this->data['transaction_amount'] : 0;

            $base_currency = Price_Formatter::$number_format['currency_code'];
            $params = array(
                'transaction_description' => $transaction_description,
                'transaction_type' => $transaction_type,
                'transaction_amount' => floatval($transaction_amount),
                'meta_name' => "adjustment",
                'meta_value' => CommonUtility::createUUID("{{admin_meta}}", 'meta_value'),
                'orig_transaction_amount' => floatval($transaction_amount),
                'merchant_base_currency' => $base_currency,
                'admin_base_currency' => $base_currency,
            );

            $card_id = CWallet::createCard(Yii::app()->params->account_type['admin']);
            CWallet::inserTransactions($card_id, $params);

            $this->code = 1;
            $this->msg = t("Successful");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionmerchant_earninglist()
    {
        $data = array();
        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';

        $sortby = "restaurant_name";
        $sort = 'ASC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.merchant_id, a.merchant_uuid, a.restaurant_name, a.logo, a.path,
		(
		 select concat(running_balance,',',merchant_base_currency,',',admin_base_currency,',',exchange_rate_merchant_to_admin,',',exchange_rate_admin_to_merchant) 
		 from {{wallet_transactions}}
		 where card_id = (
		    select card_id from {{wallet_cards}}
		    where account_type=" . q(Yii::app()->params->account_type['merchant']) . " and account_id=a.merchant_id		    
		    limit 0,1
		 )
		 order by transaction_id DESC
		 limit 0,1
		) as balance,
		
		(
			select option_value 
			from {{option}}
			where merchant_id=a.merchant_id
			and option_name='merchant_default_currency'
			limit 0,1
		) as merchant_base_currency
		";

        $criteria->condition = "status=:status";
        $criteria->params = array(
            ':status' => 'active'
        );

        if (!empty($search)) {
            $criteria->addSearchCondition('a.restaurant_name', $search);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_merchant::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        if ($length > 0) {
            $pages->applyLimit($criteria);
        }

        $models = AR_merchant::model()->findAll($criteria);
        if ($models) {

            $base_currency = Price_Formatter::$number_format['currency_code'];
            $multicurrency_enabled = isset(Yii::app()->params['settings']['multicurrency_enabled']) ? Yii::app()->params['settings']['multicurrency_enabled'] : false;
            $multicurrency_enabled = $multicurrency_enabled == 1 ? true : false;

            foreach ($models as $item) {

                $atts_balance = !empty($item->balance) ? explode(",", $item->balance) : '';
                $balance = isset($atts_balance[0]) ? floatval($atts_balance[0]) : 0;

                if ($multicurrency_enabled) {
                    $merchant_base_currency = isset($atts_balance[1]) ? $atts_balance[1] : $base_currency;
                } else $merchant_base_currency = $base_currency;

                $admin_base_currency = isset($atts_balance[2]) ? $atts_balance[2] : $base_currency;
                $exchange_rate_merchant_to_admin = isset($atts_balance[3]) ? floatval($atts_balance[3]) : 1;

                $exchange_rate = 1;
                if ($multicurrency_enabled && $merchant_base_currency != $admin_base_currency) {
                    $exchange_rate = $exchange_rate_merchant_to_admin;
                }

                $balance = Price_Formatter::formatNumber(($balance * $exchange_rate));
                $logo_url = CMedia::getImage($item->logo, $item->path, '@thumbnail', CommonUtility::getPlaceholderPhoto('merchant'));

                $view = Yii::app()->createUrl('earnings/transactions', array(
                    'merchant_uuid' => $item->merchant_uuid
                ));


                $logo_html = <<<HTML
<img src="$logo_url" class="img-60 rounded-circle" />
HTML;

                $balance_html = <<<HTML
<b>$balance</b>
HTML;


                $actions_html = <<<HTML
<div class="btn-group btn-group-actions" role="group">
 <a href="$view" target="_blank" class="btn btn-light tool_tips"><i class="zmdi zmdi-eye"></i></a>
 <a class="btn btn-light tool_tips"><i class="zmdi zmdi-money-off"></i></a>
</div>
HTML;

                $data[] = array(
                    'merchant_id' => $item->merchant_id,
                    'logo' => $logo_html,
                    'restaurant_name' => Yii::app()->input->xssClean($item->restaurant_name),
                    'balance' => $balance_html,
                    'merchant_uuid' => $item->merchant_uuid,
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actionmerchant_transactions()
    {
        $data = array();
        $card_id = 0;

        $merchant_uuid = isset($this->data['merchant_uuid']) ? $this->data['merchant_uuid'] : '';

        try {
            $merchant = CMerchants::getByUUID($merchant_uuid);
            $card_id = CWallet::getCardID(Yii::app()->params->account_type['merchant'], $merchant->merchant_id);
        } catch (Exception $e) {
            //
        }

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';

        $sortby = "transaction_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;

        $criteria = new CDbCriteria();
        $criteria->condition = "card_id=:card_id";
        $criteria->params  = array(
            ':card_id' => intval($card_id),
        );

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(transaction_date,'%Y-%m-%d')", $date_start, $date_end);
        }
        if (is_array($transaction_type) && count($transaction_type) >= 1) {
            $criteria->addInCondition('transaction_type', (array) $transaction_type);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_wallet_transactions::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);
        $models = AR_wallet_transactions::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {
                $description = Yii::app()->input->xssClean($item->transaction_description);
                $parameters = json_decode($item->transaction_description_parameters, true);
                if (is_array($parameters) && count($parameters) >= 1) {
                    $description = t($description, $parameters);
                }

                $exchange_rate = $item->exchange_rate_merchant_to_admin > 0 ? $item->exchange_rate_merchant_to_admin : 1;

                $transaction_amount = Price_Formatter::formatNumber($item->transaction_amount * $exchange_rate);
                switch ($item->transaction_type) {
                    case "debit":
                    case "payout":
                        $transaction_amount = "(" . Price_Formatter::formatNumber(($item->transaction_amount * $exchange_rate)) . ")";
                        break;
                }

                $data[] = array(
                    'transaction_date' => Date_Formatter::date($item->transaction_date),
                    'transaction_description' => $description,
                    'transaction_amount' => $transaction_amount,
                    'running_balance' => Price_Formatter::formatNumber(($item->running_balance * $exchange_rate)),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actiongetMenuItem()
    {
        try  {
            $item_uuid = isset($this->data['item_uuid'])?trim($this->data['item_uuid']):'';
            $item = AR_item::model()->find("item_token=:item_token",[
                ':item_token'=>$item_uuid
            ]);
            $merchant_id = $item->merchant_id;
            $cat_id = isset($this->data['cat_id'])?(integer)$this->data['cat_id']:0;

            // CHECK IF MERCHANT HAS DIFFERENT TIMEZONE
            $options_merchant = OptionsTools::find(['merchant_timezone'],$merchant_id);
            $merchant_timezone = isset($options_merchant['merchant_timezone'])?$options_merchant['merchant_timezone']:'';
            if(!empty($merchant_timezone)){
                Yii::app()->timezone = $merchant_timezone;
            }

            $items = CMerchantMenu::getMenuItem($merchant_id,$cat_id,$item_uuid,Yii::app()->language);
            $addons = CMerchantMenu::getItemAddonCategory($merchant_id,$item_uuid,Yii::app()->language);
            $addon_items = CMerchantMenu::getAddonItems($merchant_id,$item_uuid,Yii::app()->language);
            $meta = CMerchantMenu::getItemMeta($merchant_id,$item_uuid);
            $meta_details = CMerchantMenu::getMeta($merchant_id,$item_uuid,Yii::app()->language);

            $items_not_available = CMerchantMenu::getItemAvailability($merchant_id,date("w"),date("H:h:i"));
            $category_not_available = CMerchantMenu::getCategoryAvailability($merchant_id,date("w"),date("H:h:i"));

            if(in_array($items['item_id'],(array)$items_not_available)){
                $items['available']=false;
            } else {
                $items['available'] = in_array($items['cat_id'],(array)$category_not_available)? false : true;
            }

            $data = array(
                'items'=>$items,
                'addons'=>$addons,
                'addon_items'=>$addon_items,
                'meta'=>$meta,
                'meta_details'=>$meta_details,
            );

            $this->code = 1; $this->msg = "ok";
            $this->details = array(
                'sold_out_options'=>AttributesTools::soldOutOptions(),
                'data'=>$data
            );

        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
            $this->details = array(
                'data'=>array()
            );
        }
        $this->responseJson();
    }

    public function actionaddCartItems()
    {
        $cart_uuid = isset($this->data['order_uuid'])?$this->data['order_uuid']:'';
        $order_uuid = $cart_uuid;
        $cart_row = CommonUtility::createUUID("{{ordernew_item}}",'item_row');

        $transaction_type = isset($this->data['transaction_type'])?$this->data['transaction_type']:'';
        $merchant_id = isset($this->data['merchant_id'])?(integer)$this->data['merchant_id']:'';
        $cat_id = isset($this->data['cat_id'])?(integer)$this->data['cat_id']:'';
        $item_token = isset($this->data['item_token'])?$this->data['item_token']:'';
        $old_item_token = isset($this->data['old_item_token'])?$this->data['old_item_token']:'';
        $item_row = isset($this->data['item_row'])?$this->data['item_row']:'';
        $item_size_id = isset($this->data['item_size_id'])?(integer)$this->data['item_size_id']:0;
        $item_qty = isset($this->data['item_qty'])?(integer)$this->data['item_qty']:0;
        $special_instructions = isset($this->data['special_instructions'])?$this->data['special_instructions']:'';
        $if_sold_out = isset($this->data['if_sold_out'])?$this->data['if_sold_out']:'';
        $inline_qty = isset($this->data['inline_qty'])?(integer)$this->data['inline_qty']:0;

        if($old_item_token==$item_token){
            $this->msg = t("Cannot replace this item with the same item.");
            $this->responseJson();
        }

        $addons = array();
        $item_addons = isset($this->data['item_addons'])?$this->data['item_addons']:'';
        if(is_array($item_addons) && count($item_addons)>=1){
            foreach ($item_addons as $val) {
                $multi_option = isset($val['multi_option'])?$val['multi_option']:'';
                $subcat_id = isset($val['subcat_id'])?(integer)$val['subcat_id']:0;
                $sub_items = isset($val['sub_items'])?$val['sub_items']:'';
                $sub_items_checked = isset($val['sub_items_checked'])?(integer)$val['sub_items_checked']:0;

                if($multi_option=="one" && $sub_items_checked>0){

                    $addon_price = 0;
                    foreach ($sub_items as $sub_items_items) {
                        if($sub_items_items['sub_item_id']==$sub_items_checked){
                            $addon_price = $sub_items_items['price'];
                        }
                    }

                    $addons[] = array(
                        'cart_row'=>$cart_row,
                        'cart_uuid'=>$cart_uuid,
                        'subcat_id'=>$subcat_id,
                        'sub_item_id'=>$sub_items_checked,
                        'qty'=>1,
                        'price'=>$addon_price,
                        'multi_option'=>$multi_option,
                    );
                } else {
                    foreach ($sub_items as $sub_items_val) {
                        if($sub_items_val['checked']==1){
                            $addons[] = array(
                                'cart_row'=>$cart_row,
                                'cart_uuid'=>$cart_uuid,
                                'subcat_id'=>$subcat_id,
                                'sub_item_id'=>isset($sub_items_val['sub_item_id'])?(integer)$sub_items_val['sub_item_id']:0,
                                'qty'=>isset($sub_items_val['qty'])?(integer)$sub_items_val['qty']:0,
                                'price'=>isset($sub_items_val['price'])?floatval($sub_items_val['price']):0,
                                'multi_option'=>$multi_option,
                            );
                        }
                    }
                }
            }
        }


        $attributes = array();
        $meta = isset($this->data['meta'])?$this->data['meta']:'';
        if(is_array($meta) && count($meta)>=1){
            foreach ($meta as $meta_name=>$metaval) {
                if($meta_name!="dish"){
                    foreach ($metaval as $val) {
                        if($val['checked']>0){
                            $attributes[]=array(
                                'cart_row'=>$cart_row,
                                'cart_uuid'=>$cart_uuid,
                                'meta_name'=>$meta_name,
                                'meta_id'=>$val['meta_id']
                            );
                        }
                    }
                }
            }
        }


        try {

            $model = COrders::get($order_uuid);

            $criteria=new CDbCriteria();
            $criteria->alias = "a";
            $criteria->select = "a.item_id,a.item_token,
	        b.item_size_id, b.price as item_price, b.discount, b.discount_type, b.discount_start,
	        b.discount_end,
	        (
		     select count(*) from {{view_item_lang_size}}
		     where item_size_id = b.item_size_id 		  
		     and CURDATE() >= discount_start and CURDATE() <= discount_end
		    ) as discount_valid
	        
	        ";
            $criteria->condition = "a.merchant_id = :merchant_id AND a.item_token=:item_token
	        AND b.item_size_id=:item_size_id
	        ";
            $criteria->params = array (
                ':merchant_id'=>$merchant_id,
                ':item_token'=>$item_token,
                ':item_size_id'=>$item_size_id
            );
            $criteria->mergeWith(array(
                'join'=>'LEFT JOIN {{item_relationship_size}} b ON a.item_id = b.item_id',
            ));
            $item = AR_item::model()->find($criteria);

            if(!$item){
                $this->msg = t("Price is not valid");
                $this->responseJson();
            }

            $scenario = 'update_cart';

            $items = array(
                'order_uuid'=>$order_uuid,
                'order_id'=>$model->order_id,
                'merchant_id'=>$merchant_id,
                'cart_row'=>$cart_row,
                'cart_uuid'=>$cart_uuid,
                'cat_id'=>$cat_id,
                'item_id'=>$item->item_id,
                'item_token'=>$item_token,
                'item_size_id'=>$item_size_id,
                'qty'=>$item_qty,
                'special_instructions'=>$special_instructions,
                'if_sold_out'=>$if_sold_out,
                'addons'=>$addons,
                'attributes'=>$attributes,
                'inline_qty'=>$inline_qty,
                'price'=>floatval($item->item_price),
                'discount'=>$item->discount_valid>0?$item->discount:0,
                'discount_type'=>$item->discount_valid>0?$item->discount_type:'',
                'item_row'=>$item_row,
                'old_item_token'=>$old_item_token,
                'scenario'=>$scenario
            );


            /*GET TAX*/
            $tax_settings = array(); $tax_use = array();
            try {
                $tax_settings = CTax::getSettings($merchant_id);
                if($tax_settings['tax_type']=="multiple"){
                    $tax_use = CTax::getItemTaxUse($merchant_id,$item->item_id);
                } else $tax_use = isset($tax_settings['tax']) ? $tax_settings['tax'] : '';
            } catch (Exception $e) {
                //echo $e->getMessage();
            }
            $items['tax_use'] = $tax_use;

            COrders::add($items);
            COrders::updateServiceFee($order_uuid,$transaction_type);

            $this->code = 1 ; $this->msg = T("Item added to order");
            $this->details = array(
                'order_uuid'=>$order_uuid
            );

        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
            $this->details = array(
                'data'=>array()
            );
        }
        $this->responseJson();
    }

    public function actionitemChanges()
    {
        try {

            $order_uuid = isset($this->data['order_uuid'])?$this->data['order_uuid']:'';
            $item_row = isset($this->data['item_row'])?$this->data['item_row']:'';
            $out_stock_options = isset($this->data['out_stock_options'])?intval($this->data['out_stock_options']):0;
            $item_changes = isset($this->data['item_changes'])?$this->data['item_changes']:'';

            $model = COrders::get($order_uuid);

            $items = AR_ordernew_item::model()->find("item_row=:item_row",array(
                ':item_row'=>$item_row
            ));

            $refund_item_details = array();
            if($item_changes=="refund" || $item_changes=="out_stock"){
                $refund_item_details = COrders::getRefundItemTotal($item_changes,$model->tax,$items->order_id,$items->item_row);
            }

            if($items){
                $items->scenario = $item_changes;
                $items->item_changes = $item_changes;
                $items->order_uuid = $order_uuid;
                $items->refund_item_details = $refund_item_details;
                if($items->delete()){
                    $this->code = 1;
                    $this->msg = t("Succesful");
                } else $this->msg = CommonUtility::parseError( $model->getErrors());
            } else $this->msg = t("Item row not found");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionadditionalCharge()
    {
        try {
            $order_uuid = isset($this->data['order_uuid'])?$this->data['order_uuid']:'';
            $item_row = isset($this->data['item_row'])?$this->data['item_row']:'';
            $additional_charge = isset($this->data['additional_charge'])?$this->data['additional_charge']:'';
            $additional_charge_name = isset($this->data['additional_charge_name'])?$this->data['additional_charge_name']:'';
            $additional_charge = floatval($additional_charge);

            $additional_charge_name = !empty($additional_charge_name)?$additional_charge_name:'Additional charge applied';

            $model = COrders::get($order_uuid);

            if($additional_charge>0){
                $item = new AR_ordernew_additional_charge;
                $item->order_id = $model->order_id;
                $item->item_row = $item_row;
                $item->charge_name = $additional_charge_name;
                $item->additional_charge = $additional_charge;
                $item->order_uuid = $order_uuid;
                if($item->save()){
                    $this->code = 1;
                    $this->msg = t("Succesful");
                } else $this->msg = CommonUtility::parseError( $model->getErrors());
            } else $this->msg = t("Additional charge must be greater than zero");

        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetordersummary()
    {
        $merchant_uuid = isset($this->data['merchant_uuid']) ? $this->data['merchant_uuid'] : '';
        $merchant = AR_merchant::model()->find("merchant_uuid=:merchant_uuid", array(
            ':merchant_uuid' => $merchant_uuid
        ));

        $merchant_id = 0;
        if ($merchant) {

            try {

                $merchant_id = $merchant->merchant_id;
                $initial_status = AttributesTools::initialStatus();
                $refund_status = AttributesTools::refundStatus();
                $orders = 0;
                $order_cancel = 0;
                $total = 0;

                $not_in_status = AOrderSettings::getStatus(array('status_cancel_order', 'status_rejection'));
                array_push($not_in_status, $initial_status);
                $orders = AOrders::getOrdersTotal($merchant_id, array(), $not_in_status);

                $status_cancel = AOrderSettings::getStatus(array('status_cancel_order'));
                $order_cancel = AOrders::getOrdersTotal($merchant_id, $status_cancel);

                $status_delivered = AOrderSettings::getStatus(array('status_delivered', 'status_completed'));
                $total = AOrders::getOrderSummary($merchant_id, $status_delivered, 'exchange_rate_merchant_to_admin');
                $total_refund = AOrders::getTotalRefund($merchant_id, $refund_status, 'exchange_rate_merchant_to_admin');

                $logo_url = CMedia::getImage($merchant->logo, $merchant->path, '@thumbnail', CommonUtility::getPlaceholderPhoto('merchant'));

                $data = array(
                    'merchant' => array(
                        'name' => $merchant->restaurant_name,
                        'logo_url' => $logo_url,
                        'contact_phone' => $merchant->contact_phone,
                        'contact_email' => $merchant->contact_email,
                        'member_since' => Date_Formatter::date($merchant->date_created),
                        'merchant_active' => $merchant->status == 'active' ? true : false
                    ),
                    'orders' => $orders,
                    'order_cancel' => $order_cancel,
                    'total' => Price_Formatter::formatNumberNoSymbol($total),
                    'total_refund' => Price_Formatter::formatNumberNoSymbol($total_refund),
                    'price_format' => array(
                        'symbol' => Price_Formatter::$number_format['currency_symbol'],
                        'decimals' => Price_Formatter::$number_format['decimals'],
                        'decimal_separator' => Price_Formatter::$number_format['decimal_separator'],
                        'thousand_separator' => Price_Formatter::$number_format['thousand_separator'],
                        'position' => Price_Formatter::$number_format['position'],
                    )
                );

                $this->code = 1;
                $this->msg = "OK";
                $this->details = $data;
            } catch (Exception $e) {
                $this->msg = t($e->getMessage());
            }
        } else $this->msg = t("Merchant not found");
        $this->responseJson();
    }

    public function actionchangeMerchantStatus()
    {
        $merchant_uuid = isset($this->data['merchant_uuid']) ? $this->data['merchant_uuid'] : '';
        $status = isset($this->data['status']) ? $this->data['status'] : 0;
        $merchant = AR_merchant::model()->find("merchant_uuid=:merchant_uuid", array(
            ':merchant_uuid' => $merchant_uuid
        ));
        if ($merchant) {
            $status = $status == 1 ? 'active' : 'blocked';
            $merchant->status = $status;
            if ($merchant->save()) {
                $this->code = 1;
                $this->msg = "ok";
                $this->details = array(
                    'merchant_active' => $status == 'active' ? true : false
                );
            } else $this->msg = CommonUtility::parseError($merchant->getErrors());
        } else $this->msg = t("Merchant not found");
        $this->responseJson();
    }

    public function actionmerchantTotalBalance()
    {
        try {
            $balance = CEarnings::getTotalMerchantBalance();
            $this->msg = "ok";
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
            $balance = 0;
        }

        $this->code = 1;
        $this->details = array(
            'balance' => Price_Formatter::formatNumberNoSymbol($balance),
            'price_format' => array(
                'symbol' => Price_Formatter::$number_format['currency_symbol'],
                'decimals' => Price_Formatter::$number_format['decimals'],
                'decimal_separator' => Price_Formatter::$number_format['decimal_separator'],
                'thousand_separator' => Price_Formatter::$number_format['thousand_separator'],
                'position' => Price_Formatter::$number_format['position'],
            )
        );
        $this->responseJson();
    }

    public function actionwithdrawalList()
    {
        $data = array();
        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '') : '';
        $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';
        $filter = isset($this->data['filter']) ? $this->data['filter'] : '';
        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';

        $sortby = "a.transaction_date";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        $criteria->alias = "a";

        $criteria->select = "a.transaction_uuid,a.card_id,a.transaction_amount,a.transaction_date, a.status,
		a.exchange_rate_merchant_to_admin,
		b.merchant_id, b.restaurant_name , b.logo , b.path";

        $criteria->join = "LEFT JOIN {{merchant}} b on a.card_id = 
		(
		 select card_id from {{wallet_cards}}
		 where account_type=" . q(Yii::app()->params->account_type['merchant']) . " and account_id=b.merchant_id
		)
		";

        $criteria->condition = "transaction_type=:transaction_type";
        $criteria->params = array(
            ':transaction_type' => 'payout'
        );

        if (is_array($transaction_type) && count($transaction_type) >= 1) {
            $criteria->addInCondition('a.status', (array) $transaction_type);
        }

        if (!empty($search)) {
            $criteria->addSearchCondition('a.restaurant_name', $search);
        }

        if (is_array($filter) && count($filter) >= 1) {
            $filter_merchant_id = isset($filter['merchant_id']) ? $filter['merchant_id'] : '';
            $criteria->addSearchCondition('b.merchant_id', $filter_merchant_id);
        }

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(transaction_date,'%Y-%m-%d')", $date_start, $date_end);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_wallet_transactions::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);

        if ($length > 0) {
            $pages->applyLimit($criteria);
        }

        $models = AR_wallet_transactions::model()->findAll($criteria);

        if ($models) {
            foreach ($models as $item) {


                $logo_url = CMedia::getImage($item->logo, $item->path, '@thumbnail', CommonUtility::getPlaceholderPhoto('merchant'));
                $transaction_amount = Price_Formatter::formatNumber(($item->transaction_amount * $item->exchange_rate_merchant_to_admin));
                $status = $item->status;


                $logo_html = <<<HTML
<img src="$logo_url" class="img-60 rounded-circle" />
HTML;

                $amount_html = <<<HTML
<p class="m-0"><b>$transaction_amount</b></p>
<p class="m-0"><span class="badge payment $status">$status</span></p>
HTML;


                $data[] = array(
                    'merchant_id' => $item->merchant_id,
                    'logo' => $logo_html,
                    'transaction_date' => Date_Formatter::date($item->transaction_date),
                    'restaurant_name' => Yii::app()->input->xssClean($item->restaurant_name),
                    'transaction_amount' => $amount_html,
                    'transaction_uuid' => $item->transaction_uuid,
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actiongetPayoutDetails()
    {
        try {

            $merchant = array();
            $merchant_id = 0;
            $transaction_uuid = isset($this->data['transaction_uuid']) ? $this->data['transaction_uuid'] : '';
            $data = CPayouts::getPayoutDetails($transaction_uuid, true);
            $provider = AttributesTools::paymentProviderDetails(isset($data['provider']) ? $data['provider'] : '');
            $card_id = isset($data['card_id']) ? $data['card_id'] : '';
            try {
                $merchant_id = CWallet::getAccountID($card_id);
                $merchant_data = CMerchants::get($merchant_id);
                $merchant = array(
                    'restaurant_name' => Yii::app()->input->xssClean($merchant_data->restaurant_name)
                );
            } catch (Exception $e) {
                //
            }

            $this->code = 1;
            $this->msg = "ok";
            $this->details = array(
                'data' => $data,
                'merchant' => $merchant,
                'provider' => $provider
            );
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionpayoutPaid()
    {
        try {
            $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : 'payout';
            $transaction_uuid = isset($this->data['transaction_uuid']) ? $this->data['transaction_uuid'] : '';
            $model = AR_wallet_transactions::model()->find("transaction_uuid=:transaction_uuid", array(
                ':transaction_uuid' => $transaction_uuid
            ));
            if ($model) {
                //$model->scenario = "payout_paid";
                $model->scenario = $transaction_type . "_paid";
                $model->status = 'paid';
                if ($model->save()) {
                    $this->code = 1;
                    $this->msg = t("Payout status set to paid");
                } else $this->msg = CommonUtility::parseError($model->getErrors());
            } else $this->msg = t("Transaction not found");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actioncancelPayout()
    {
        try {

            $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : 'payout';
            $transaction_uuid = isset($this->data['transaction_uuid']) ? $this->data['transaction_uuid'] : '';

            $model = AR_wallet_transactions::model()->find("transaction_uuid=:transaction_uuid", array(
                ':transaction_uuid' => $transaction_uuid
            ));
            if ($model) {
                $params = array(
                    'transaction_description' => "Cancel payout reference #{{transaction_id}}",
                    'transaction_description_parameters' => array('{{transaction_id}}' => $model->transaction_id),
                    'transaction_type' => "credit",
                    'transaction_amount' => floatval($model->transaction_amount),
                );
                //$model->scenario = "payout_cancel";
                $model->scenario = $transaction_type . "_cancel";

                $model->status = "cancelled";

                if ($model->save()) {
                    CWallet::inserTransactions($model->card_id, $params);
                    $this->code = 1;
                    $this->msg = t("Payout cancelled");
                } else $this->msg = CommonUtility::parseError($model->getErrors());
            } else $this->msg = t("Transaction not found");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionapprovedPayout()
    {
        try {

            $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : 'payout';
            $transaction_uuid = isset($this->data['transaction_uuid']) ? $this->data['transaction_uuid'] : '';

            $model = AR_wallet_transactions::model()->find("transaction_uuid=:transaction_uuid", array(
                ':transaction_uuid' => $transaction_uuid
            ));
            if ($model) {
                //$model->scenario = "payout_paid";
                $model->scenario = $transaction_type . "_paid";
                $model->status = "paid";
                if ($model->save()) {
                    $this->code = 1;
                    $this->msg = t("Payout will process in a minute or two");
                } else $this->msg = CommonUtility::parseError($model->getErrors());
            } else $this->msg = t("Transaction not found");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionpayoutSummary()
    {
        try {

            $data = CPayouts::payoutSummary("payout", 0, true);
            $this->code = 1;
            $this->msg = "ok";
            $this->details = array(
                'summary' => $data,
                'price_format' => array(
                    'symbol' => Price_Formatter::$number_format['currency_symbol'],
                    'decimals' => Price_Formatter::$number_format['decimals'],
                    'decimal_separator' => Price_Formatter::$number_format['decimal_separator'],
                    'thousand_separator' => Price_Formatter::$number_format['thousand_separator'],
                    'position' => Price_Formatter::$number_format['position'],
                )
            );
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionsearchMerchant()
    {
        $search = isset($this->data['search']) ? $this->data['search'] : '';
        $data = array();

        $criteria = new CDbCriteria();
        $criteria->condition = "status=:status";
        $criteria->params = array(
            ':status' => 'active'
        );
        if (!empty($search)) {
            $criteria->addSearchCondition('restaurant_name', $search);
        }
        $criteria->limit = 10;
        if ($models = AR_merchant::model()->findAll($criteria)) {
            foreach ($models as $val) {
                $data[] = array(
                    'id' => $val->merchant_id,
                    'text' => Yii::app()->input->xssClean($val->restaurant_name)
                );
            }
        }

        $result = array(
            'results' => $data
        );
        $this->responseSelect2($result);
    }

    public function actionsetPauseOrder()
    {
        try {

            $now = time();
            $pause_time = 0;
            $time_delay = isset($this->data['time_delay']) ? $this->data['time_delay'] : 0;
            $pause_hours = isset($this->data['pause_hours']) ? intval($this->data['pause_hours']) : 0;
            $pause_minutes = isset($this->data['pause_minutes']) ? intval($this->data['pause_minutes']) : 0;
            $reason = isset($this->data['reason']) ? $this->data['reason'] : '';

            $sleep_in_seconds = 0;
            if ($time_delay == "other") {
                $pause_time = date('Y-m-d H:i:s', strtotime("+$pause_hours hour +$pause_minutes minutes", $now));
                $sleep_in_seconds = (intval($pause_hours) * 3600) +  (intval($pause_minutes) * 60);
            } else {
                $time_delay = intval($time_delay);
                $sleep_in_seconds = $time_delay * 60;
                $pause_time = date("Y-m-d H:i:s", strtotime("+$time_delay minutes", $now));
            }

            AR_merchant_meta::saveMeta(Yii::app()->merchant->merchant_id, 'pause_time', $pause_time);
            AR_merchant_meta::saveMeta(Yii::app()->merchant->merchant_id, 'pause_reason', $reason);
            AR_merchant_meta::saveMeta(Yii::app()->merchant->merchant_id, 'accepting_order', false);

            try {
                $merchant = CMerchants::get(Yii::app()->merchant->merchant_id);
                $merchant->pause_ordering = true;
                $merchant->save();
            } catch (Exception $e) {
                //
            }

            $pause_time = Date_Formatter::dateTime($pause_time, "yyyy-MM-ddTHH:mm", true);

            $this->code = 1;
            $this->msg = "ok";
            $this->details = array(
                'pause_time' => $pause_time,
                'accepting_order' => false,
            );

            if ($sleep_in_seconds > 0) {
                $this->details['sleep_in_seconds'] = $sleep_in_seconds;
            }
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionmerchantEarningAdjustment()
    {
        try {

            $multicurrency_enabled = isset(Yii::app()->params['settings']['multicurrency_enabled']) ? Yii::app()->params['settings']['multicurrency_enabled'] : false;
            $multicurrency_enabled = $multicurrency_enabled == 1 ? true : false;

            $merchant_id = isset($this->data['merchant_id']) ? $this->data['merchant_id'] : 0;
            $card_id = CWallet::getCardID(Yii::app()->params->account_type['merchant'], $merchant_id);

            $transaction_description = isset($this->data['transaction_description']) ? $this->data['transaction_description'] : '';
            $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';
            $transaction_amount = isset($this->data['transaction_amount']) ? $this->data['transaction_amount'] : 0;

            $base_currency = Price_Formatter::$number_format['currency_code'];
            $attrs = OptionsTools::find(array('merchant_default_currency'), $merchant_id);

            if ($multicurrency_enabled) {
                $merchant_default_currency = isset($attrs['merchant_default_currency']) ? $attrs['merchant_default_currency'] : $base_currency;
            } else $merchant_default_currency = $base_currency;

            $exchange_rate_merchant_to_admin = 1;
            $exchange_rate_admin_to_merchant = 1;
            if ($merchant_default_currency != $base_currency) {
                $exchange_rate_merchant_to_admin = CMulticurrency::getExchangeRate($merchant_default_currency, $base_currency);
                $exchange_rate_admin_to_merchant = CMulticurrency::getExchangeRate($base_currency, $merchant_default_currency);
            }

            $params = array(
                'card_id' => intval($card_id),
                'transaction_description' => $transaction_description,
                'transaction_type' => $transaction_type,
                'transaction_amount' => floatval($transaction_amount),
                'meta_name' => "adjustment",
                'meta_value' => CommonUtility::createUUID("{{admin_meta}}", 'meta_value'),
                'merchant_base_currency' => $merchant_default_currency,
                'admin_base_currency' => $base_currency,
                'exchange_rate_merchant_to_admin' => $exchange_rate_merchant_to_admin,
                'exchange_rate_admin_to_merchant' => $exchange_rate_admin_to_merchant,
            );
            CWallet::inserTransactions($card_id, $params);
            $this->code = 1;
            $this->msg = t("Succesful");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetFilterData()
    {
        try {

            $data = array(
                'status_list' => AttributesTools::getOrderStatus(Yii::app()->language),
                'order_type_list' => AttributesTools::ListSelectServices(),
            );
            $this->code = 1;
            $this->msg = "OK";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg[] = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionsearchCustomer()
    {
        $search = isset($this->data['search']) ? $this->data['search'] : '';
        $data = array();

        $criteria = new CDbCriteria();
        $criteria->select = "client_id,first_name,last_name";
        $criteria->condition = "status=:status";
        $criteria->params = array(
            ':status' => 'active'
        );
        if (!empty($search)) {
            $criteria->addSearchCondition('first_name', $search);
            $criteria->addSearchCondition('last_name', $search, true, 'OR');
        }
        $criteria->limit = 10;
        if ($models = AR_client::model()->findAll($criteria)) {
            foreach ($models as $val) {
                $data[] = array(
                    'id' => $val->client_id,
                    'text' => $val->first_name . " " . $val->last_name
                );
            }
        }

        $result = array(
            'results' => $data
        );
        $this->responseSelect2($result);
    }

    public function actionallOrders()
    {
        $data = array();
        $status = COrders::statusList(Yii::app()->language);
        $services = COrders::servicesList(Yii::app()->language);
        $payment_gateway = AttributesTools::PaymentProvider();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';
        $filter = isset($this->data['filter']) ? $this->data['filter'] : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';

        $sortby = "order_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;

        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.order_id, a.client_id, a.status, a.order_uuid , a.merchant_id,
		a.payment_code, a.service_code,a.total, a.date_created,
		a.admin_base_currency,a.exchange_rate_merchant_to_admin,
		a.prep_time, a.actual_prep_time,
		b.meta_value as customer_name, 
		c.restaurant_name, c.logo, c.path, l_c.name as city_name,
		(
		   select sum(qty)
		   from {{ordernew_item}}
		   where order_id = a.order_id
		) as total_items
		";
        $criteria->join = '
		LEFT JOIN {{ordernew_meta}} b on  a.order_id = b.order_id 
		LEFT JOIN {{merchant}} c on  a.merchant_id = c.merchant_id 
		LEFT JOIN {{location_cities}} l_c on l_c.city_id = c.city_id
		';

        $criteria->condition = "meta_name=:meta_name ";
        $criteria->params  = array(
            ':meta_name' => 'customer_name'
        );

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(a.date_created,'%Y-%m-%d')", $date_start, $date_end);
        }
        $initial_status = AttributesTools::initialStatus();
        $criteria->addNotInCondition('a.status', (array) array($initial_status));

        if (is_array($filter) && count($filter) >= 1) {
            $filter_merchant_id = isset($filter['merchant_id']) ? $filter['merchant_id'] : '';
            $filter_order_status = isset($filter['order_status']) ? $filter['order_status'] : '';
            $filter_order_type = isset($filter['order_type']) ? $filter['order_type'] : '';
            $filter_client_id = isset($filter['client_id']) ? intval($filter['client_id']) : '';
            $filter_order_id = isset($filter['order_id']) ? intval($filter['order_id']) : '';

            if ($filter_merchant_id > 0) {
                $criteria->addSearchCondition('a.merchant_id', $filter_merchant_id);
            }
            if (!empty($filter_order_status)) {
                $criteria->addSearchCondition('a.status', $filter_order_status);
            }
            if (!empty($filter_order_type)) {
                $criteria->addSearchCondition('a.service_code', $filter_order_type);
            }
            if ($filter_client_id > 0) {
                $criteria->addSearchCondition('a.client_id', intval($filter_client_id));
            }
            if ($filter_order_id > 0) {
                $criteria->addSearchCondition('a.order_id', intval($filter_order_id));
            }
        }

        $criteria->order = "$sortby $sort";

        $count = AR_ordernew::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        if ($length > 0) {
            $pages->applyLimit($criteria);
        }
        $models = AR_ordernew::model()->findAll($criteria);

        if ($models) {
            foreach ($models as $item) {
                $item->total_items = intval($item->total_items);
                $item->total_items = t("{{total_items}} items", array(
                    '{{total_items}}' => $item->total_items
                ));

                $trans_order_type = $item->service_code;
                if (array_key_exists($item->service_code, $services)) {
                    $trans_order_type = $services[$item->service_code]['service_name'];
                }

                $order_type = t("Order Type.");
                $order_type .= "<span class='ml-2 services badge $item->service_code'>$trans_order_type</span>";


                $exchange_rate  = $item->exchange_rate_merchant_to_admin > 0 ? $item->exchange_rate_merchant_to_admin : 1;

                $total = t("Total. {{total}}", array(
                    '{{total}}' => Price_Formatter::formatNumber(($item->total * $exchange_rate))
                ));
                $place_on = t("Place on {{date}}", array(
                    '{{date}}' => Date_Formatter::dateTime($item->date_created)
                ));

                $status_trans = $item->status;
                if (array_key_exists($item->status, (array) $status)) {
                    $status_trans = $status[$item->status]['status'];
                }

                $view_order = Yii::app()->createUrl('orders/view', array(
                    'order_uuid' => $item->order_uuid
                ));

                $print_pdf = Yii::app()->createUrl('print/pdf', array(
                    'order_uuid' => $item->order_uuid
                ));

                $logo_url = CMedia::getImage($item->logo, $item->path, '@thumbnail', CommonUtility::getPlaceholderPhoto('merchant'));

                $payment_name = isset($payment_gateway[$item->payment_code]) ? $payment_gateway[$item->payment_code] : $item->payment_code;

                $logo_html = <<<HTML
<img src="$logo_url" class="img-60 rounded-circle" />
HTML;

                $information = <<<HTML
$item->total_items<span class="ml-2 badge order_status $item->status">$status_trans</span>
<p class="dim m-0">$payment_name</p>
<p class="dim m-0">$order_type</p>
<p class="dim m-0">$total</p>
<p class="dim m-0">$place_on</p>
HTML;
                $ord = COrders::get($item->order_uuid);

                if($item->actual_prep_time == 0){
                    $prep_time = $this->getTimeDifference($ord->prep_time_enabled_at, $item->prep_time);
                }else{
                    $prep_time = $item->prep_time - $item->actual_prep_time;
                }

                if($prep_time <0)
                    $prep_time = 0;

                $data[] = array(
                    'merchant_id' => $logo_html,
                    'prep_time' => $prep_time . '/' . $item->prep_time,
                    'actual_prep_time' => $item->actual_prep_time,
                    'order_id' => $item->order_id,
                    'restaurant_name' => $item->restaurant_name,
                    'client_id' => $item->customer_name,
                    'status' => $information,
                    'order_uuid' => $item->order_uuid,
                    'view_order' => Yii::app()->createAbsoluteUrl('/order/view', array('order_uuid' => $item->order_uuid)),
                    'view_pdf' => Yii::app()->createAbsoluteUrl('/preprint/pdf', array('order_uuid' => $item->order_uuid)),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    private function getTimeDifference($prep_time_enabled_at, $prep_time)
    {
        $now = new DateTime();
        $updated_at = new DateTime($prep_time_enabled_at);

        $updated_at_time = DateTime::createFromFormat('H:i:s', $updated_at->format('H:i:s'));
        $now_time = DateTime::createFromFormat('H:i:s', $now->format('H:i:s'));
        $interval = $updated_at_time->diff($now_time);

        $minutes_difference = ($interval->h * 60) + $interval->i;

        return $minutes_difference - $prep_time;
    }

    public function actionallPrependingOrders()
    {
        $data = array();
        $status = COrders::statusList(Yii::app()->language);
        $services = COrders::servicesList(Yii::app()->language);
        $payment_gateway = AttributesTools::PaymentProvider();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';
        $filter = isset($this->data['filter']) ? $this->data['filter'] : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';

        $sortby = "order_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;

        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.order_id, a.client_id, a.status, a.order_uuid , a.merchant_id,
		a.payment_code, a.service_code,a.total, a.date_created,
		a.admin_base_currency,a.exchange_rate_merchant_to_admin,
		b.meta_value as customer_name, 
		c.restaurant_name, c.logo, c.path,
		(
		   select sum(qty)
		   from {{orderprepending_item}}
		   where order_id = a.order_id
		) as total_items
		";
        $criteria->join = '
		LEFT JOIN {{orderprepending_meta}} b on  a.order_id = b.order_id 
		LEFT JOIN {{merchant}} c on  a.merchant_id = c.merchant_id 
		';

        $criteria->condition = "meta_name=:meta_name ";
        $criteria->params  = array(
            ':meta_name' => 'customer_name'
        );

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(a.date_created,'%Y-%m-%d')", $date_start, $date_end);
        }
        $initial_status = AttributesTools::initialStatus();
        $criteria->addNotInCondition('a.status', (array) array($initial_status));

        if (is_array($filter) && count($filter) >= 1) {
            $filter_merchant_id = isset($filter['merchant_id']) ? $filter['merchant_id'] : '';
            $filter_order_status = isset($filter['order_status']) ? $filter['order_status'] : '';
            $filter_order_type = isset($filter['order_type']) ? $filter['order_type'] : '';
            $filter_client_id = isset($filter['client_id']) ? intval($filter['client_id']) : '';
            $filter_order_id = isset($filter['order_id']) ? intval($filter['order_id']) : '';

            if ($filter_merchant_id > 0) {
                $criteria->addSearchCondition('a.merchant_id', $filter_merchant_id);
            }
            if (!empty($filter_order_status)) {
                $criteria->addSearchCondition('a.status', $filter_order_status);
            }
            if (!empty($filter_order_type)) {
                $criteria->addSearchCondition('a.service_code', $filter_order_type);
            }
            if ($filter_client_id > 0) {
                $criteria->addSearchCondition('a.client_id', intval($filter_client_id));
            }
            if ($filter_order_id > 0) {
                $criteria->addSearchCondition('a.order_id', intval($filter_order_id));
            }
        }

        $criteria->order = "$sortby $sort";

        $count = AR_orderprepending::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        if ($length > 0) {
            $pages->applyLimit($criteria);
        }
        $models = AR_orderprepending::model()->findAll($criteria);

        if ($models) {

            foreach ($models as $item) {

                $item->total_items = intval($item->total_items);
                $item->total_items = t("{{total_items}} items", array(
                    '{{total_items}}' => $item->total_items
                ));

                $trans_order_type = $item->service_code;
                if (array_key_exists($item->service_code, $services)) {
                    $trans_order_type = $services[$item->service_code]['service_name'];
                }

                $order_type = t("Order Type.");
                $order_type .= "<span class='ml-2 services badge $item->service_code'>$trans_order_type</span>";


                $exchange_rate  = $item->exchange_rate_merchant_to_admin > 0 ? $item->exchange_rate_merchant_to_admin : 1;

                $total = t("Total. {{total}}", array(
                    '{{total}}' => Price_Formatter::formatNumber(($item->total * $exchange_rate))
                ));
                $place_on = t("Place on {{date}}", array(
                    '{{date}}' => Date_Formatter::dateTime($item->date_created)
                ));

                $status_trans = $item->status;
                if (array_key_exists($item->status, (array) $status)) {
                    $status_trans = $status[$item->status]['status'];
                }

                $view_order = Yii::app()->createUrl('orders/view', array(
                    'order_uuid' => $item->order_uuid
                ));

                $print_pdf = Yii::app()->createUrl('print/pdf', array(
                    'order_uuid' => $item->order_uuid
                ));

                $logo_url = CMedia::getImage($item->logo, $item->path, '@thumbnail', CommonUtility::getPlaceholderPhoto('merchant'));

                $payment_name = isset($payment_gateway[$item->payment_code]) ? $payment_gateway[$item->payment_code] : $item->payment_code;


                $logo_html = <<<HTML
<img src="$logo_url" class="img-60 rounded-circle" />
HTML;


                $information = <<<HTML
$item->total_items<span class="ml-2 badge order_status $item->status">$status_trans</span>
<p class="dim m-0">$payment_name</p>
<p class="dim m-0">$order_type</p>
<p class="dim m-0">$total</p>
<p class="dim m-0">$place_on</p>
HTML;


                $data[] = array(
                    'merchant_id' => $logo_html,
                    'order_id' => $item->order_id,
                    'restaurant_name' => $item->restaurant_name,
                    'client_id' => $item->customer_name,
                    'status' => $information,
                    'order_uuid' => $item->order_uuid,
                    'view_order' => Yii::app()->createAbsoluteUrl('/order/viewprependingorder', array('order_uuid' => $item->order_uuid)),
                    'view_pdf' => Yii::app()->createAbsoluteUrl('/preprint/pdf', array('order_uuid' => $item->order_uuid)),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actiongetNotifications()
    {
        try {
            $data = CNotificationData::getList(Yii::app()->params->realtime['admin_channel']);
            $this->code = 1;
            $this->msg = "ok";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionclearNotifications()
    {
        try {

            AR_notifications::model()->deleteAll('notication_channel=:notication_channel', array(
                ':notication_channel' => Yii::app()->params->realtime['admin_channel']
            ));
            $this->code = 1;
            $this->msg = "ok";
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionallNotifications()
    {
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  isset($this->data['order'][0]) ? $this->data['order'][0] : ''   : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';

        $sortby = "date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        $criteria->condition = "notication_channel=:notication_channel";
        $criteria->params = array(':notication_channel' => Yii::app()->params->realtime['admin_channel']);

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(date_created,'%Y-%m-%d')", $date_start, $date_end);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_notifications::model()->count($criteria);

        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);
        $models = AR_notifications::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {

                $params = !empty($item->message_parameters) ? json_decode($item->message_parameters, true) : '';
                $data[] = array(
                    'date_created' => Date_Formatter::dateTime($item->date_created),
                    'message' => t($item->message, (array)$params),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actiongetWebpushSettings()
    {
        try {

            $settings = AR_admin_meta::getMeta(array(
                'webpush_app_enabled',
                'webpush_provider',
                'pusher_instance_id',
                'onesignal_app_id'
            ));

            $enabled = isset($settings['webpush_app_enabled']) ? $settings['webpush_app_enabled']['meta_value'] : '';
            $provider = isset($settings['webpush_provider']) ? $settings['webpush_provider']['meta_value'] : '';
            $pusher_instance_id = isset($settings['pusher_instance_id']) ? $settings['pusher_instance_id']['meta_value'] : '';
            $onesignal_app_id = isset($settings['onesignal_app_id']) ? $settings['onesignal_app_id']['meta_value'] : '';

            $user_settings = array();

            try {
                $user_settings = CNotificationData::getUserSettings(Yii::app()->user->id, 'admin');
            } catch (Exception $e) {
                //
            }

            $data = array(
                'enabled' => $enabled,
                'provider' => $provider,
                'pusher_instance_id' => $pusher_instance_id,
                'onesignal_app_id' => $onesignal_app_id,
                'safari_web_id' => '',
                'channel' => Yii::app()->params->realtime['admin_channel'],
                'user_settings' => $user_settings,
            );
            $this->code = 1;
            $this->msg = "OK";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetwebnotifications()
    {
        try {

            $data = CNotificationData::getUserSettings(Yii::app()->user->id, 'admin');
            $this->code = 1;
            $this->msg = "OK";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionsavewebnotifications()
    {
        try {

            $webpush_enabled = isset($this->data['webpush_enabled']) ? intval($this->data['webpush_enabled']) : 0;
            $interest = isset($this->data['interest']) ? $this->data['interest'] : '';
            $device_id = isset($this->data['device_id']) ? $this->data['device_id'] : '';

            $model = AR_device::model()->find("user_id=:user_id AND user_type=:user_type", array(
                ':user_id' => intval(Yii::app()->user->id),
                ':user_type' => "admin"
            ));
            if (!$model) {
                $model = new AR_device;
            }
            $model->interest = $interest;
            $model->user_type = 'admin';
            $model->user_id = intval(Yii::app()->user->id);
            $model->platform = "web";
            $model->device_token = $device_id;
            $model->browser_agent = $_SERVER['HTTP_USER_AGENT'];
            $model->enabled = $webpush_enabled;
            if ($model->save()) {
                $this->code = 1;
                $this->msg = t("Setting saved");
            } else $this->msg = CommonUtility::parseError($model->getErrors());
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionupdatewebdevice()
    {
        try {

            $device_id = isset($this->data['device_id']) ? $this->data['device_id'] : '';

            $model = AR_device::model()->find("user_id=:user_id AND user_type=:user_type", array(
                ':user_id' => intval(Yii::app()->user->id),
                ':user_type' => "admin"
            ));
            if ($model) {
                $model->scenario = "update_device_token";
                $model->device_token = $device_id;
                if ($model->save()) {
                    $this->code = 1;
                    $this->msg = t("device updated");
                } else $this->msg = CommonUtility::parseError($model->getErrors());
            } else $this->msg = t("user device not found");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionpushlogs()
    {
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  isset($this->data['order'][0]) ? $this->data['order'][0] : ''   : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';

        $sortby = "date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        if (!empty($search)) {
            $criteria->addSearchCondition('platform', $search);
            $criteria->addSearchCondition('body', $search, true, 'OR');
            $criteria->addSearchCondition('channel_device_id', $search, true, 'OR');
        }

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(date_created,'%Y-%m-%d')", $date_start, $date_end);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_push::model()->count($criteria);

        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);
        $models = AR_push::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {

                $data[] = array(
                    'date_created' => Date_Formatter::dateTime($item->date_created),
                    'platform' => $item->platform,
                    'body' => '<div class="text-truncate" style="max-width:200px;">' . Yii::app()->input->purify($item->body) . '</div>',
                    'channel_device_id' => '<div class="text-truncate" style="max-width:200px;">' . Yii::app()->input->purify($item->channel_device_id) . '</div>',
                    'delete_url' => Yii::app()->createUrl("/notifications/delete_push/", array('id' => $item->push_uuid)),
                    'view_id' => $item->push_uuid,
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actiongetOrderStatusList()
    {
        if ($data = AttributesTools::getOrderStatusList(Yii::app()->language)) {
            $this->code = 1;
            $this->msg = "ok";
            $this->details = $data;
        } else $this->msg = t("No results");
        $this->responseJson();
    }

    public function actiongetGroupname()
    {
        try {

            $group_name = '';
            $modify_order = false;
            $filter_buttons = false;
            $order_uuid = isset($this->data['order_uuid']) ? $this->data['order_uuid'] : '';

            try {
                $model = COrders::get($order_uuid);
                $group_name = AOrderSettings::getGroup($model->status);
                if ($group_name == "new_order") {
                    $modify_order = true;
                }
                if ($group_name == "order_ready") {
                    $filter_buttons = true;
                }
            } catch (Exception $e) {
                //
            }

            $manual_status = isset(Yii::app()->params['settings']['enabled_manual_status']) ? Yii::app()->params['settings']['enabled_manual_status'] : false;

            $merchant_uuid = '';
            try {
                $merchant = CMerchants::get($model->merchant_id);
                $merchant_uuid = $merchant->merchant_uuid;
            } catch (Exception $e) {
            }

            $data = array(
                'client_id' => $model->client_id,
                'merchant_id' => $model->merchant_id,
                'merchant_uuid' => $merchant_uuid,
                'group_name' => $group_name,
                'manual_status' => $manual_status,
                'modify_order' => false,
                'filter_buttons' => $filter_buttons
            );

            $this->code = 1;
            $this->msg = "OK";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionorderDetails()
    {
        $this->data = Yii::app()->input->xssClean(json_decode(file_get_contents('php://input'), true));
        $order_uuid = isset($this->data['order_uuid']) ? $this->data['order_uuid'] : '';
        $group_name = isset($this->data['group_name']) ? $this->data['group_name'] : '';
        $filter_buttons = isset($this->data['filter_buttons']) ? $this->data['filter_buttons'] : '';
        $payload = isset($this->data['payload']) ? $this->data['payload'] : '';
        $cart_uuid = isset($this->data['cart_uuid']) ? $this->data['cart_uuid'] : '';
        $modify_order = isset($this->data['modify_order']) ? intval($this->data['modify_order']) : '';

        try {

            $exchange_rate = 1;
            $model_order = COrders::get($order_uuid);
            if ($model_order->base_currency_code != $model_order->admin_base_currency) {
                $exchange_rate = $model_order->exchange_rate_merchant_to_admin > 0 ? $model_order->exchange_rate_merchant_to_admin : 1;
                Price_Formatter::init($model_order->admin_base_currency);
            } else {
                Price_Formatter::init($model_order->admin_base_currency);
            }
            COrders::setExchangeRate($exchange_rate);

            COrders::getContent($order_uuid, Yii::app()->language);
            $merchant_id = COrders::getMerchantId($order_uuid);
            Yii::app()->cache->set('merchant_id', $merchant_id, CACHE_LONG_DURATION);
            $merchant_info = COrders::getMerchant($merchant_id, Yii::app()->language);
            $items = COrders::getItems();
            $summary = COrders::getSummary($cart_uuid);
            $summary_total = COrders::getSummaryTotal();

            $summary_changes = array();
            $summary_transaction = array();
            if ($modify_order == 1) {
                $summary_changes = COrders::getSummaryChanges();
            } else $summary_transaction = COrders::getSummaryTransaction();

            $total_order = CMerchants::getTotalOrders($merchant_id);
            $merchant_info['order_count'] = $total_order;

            $order = COrders::orderInfo(Yii::app()->language, date("Y-m-d"));
            $order_type = isset($order['order_info']) ? $order['order_info']['order_type'] : '';
            $client_id = $order ? $order['order_info']['client_id'] : 0;
            $order_id = $order ? $order['order_info']['order_id'] : '';
            $customer = COrders::getClientInfo($client_id);

            $origin_latitude = $order ? $order['order_info']['latitude'] : '';
            $origin_longitude = $order ? $order['order_info']['longitude'] : '';
            $delivery_direction = isset($merchant_info['restaurant_direction']) ? $merchant_info['restaurant_direction'] : '';
            if ($order_type == "delivery") {
                $delivery_direction = isset($merchant_info['restaurant_direction']) ? $merchant_info['restaurant_direction'] : '';
                $delivery_direction .= "&origin=" . "$origin_latitude,$origin_longitude";
            }
            $order['order_info']['delivery_direction'] = $delivery_direction;

            $draft = AttributesTools::initialStatus();
            $not_in_status = AOrderSettings::getStatus(array('status_cancel_order', 'status_rejection'));
            array_push($not_in_status, $draft);
            $orders = ACustomer::getOrdersTotal($client_id, 0, array(), (array)$not_in_status);
            $customer['order_count'] = $orders;


            $buttons = array();
            $link_pdf = '';
            $print_settings = array();
            $payment_history = array();

            if (in_array('buttons', (array)$payload)) {
                if ($filter_buttons) {
                    $buttons = AOrders::getOrderButtons($group_name, $order_type);
                } else $buttons = AOrders::getOrderButtons($group_name);
            }

            if (in_array('print_settings', (array)$payload)) {
                $link_pdf = array(
                    'pdf_a4' => Yii::app()->CreateUrl("preprint/pdf", array('order_uuid' => $order_uuid, 'size' => "a4")),
                    'pdf_receipt' => Yii::app()->CreateUrl("preprint/pdf", array('order_uuid' => $order_uuid, 'size' => "thermal")),
                );
                $print_settings = AOrderSettings::getPrintSettings();
            }

            if (in_array('payment_history', (array)$payload)) {
                $payment_history = COrders::paymentHistory($order_id);
            }

            $credit_card_details = '';
            $payment_code = $order['order_info']['payment_code'];
            if ($payment_code == "ocr") {
                try {
                    $credit_card_details = COrders::getCreditCard2($order_id);
                } catch (Exception $e) {
                    //
                }
            }

            $driver_data = [];
            $driver_id = $order['order_info']['driver_id'];
            if ($driver_id > 0) {
                $now = date("Y-m-d");
                try {
                    $driver = CDriver::getDriver($driver_id);
                    $driver_data = [
                        'uuid' => $driver->driver_uuid,
                        'driver_name' => "$driver->first_name $driver->last_name",
                        'phone_number' => $driver->phone_prefix . $driver->phone,
                        'email_address' => $driver->email,
                        'photo_url' => CMedia::getImage($driver->photo, $driver->path, '@thumbnail', CommonUtility::getPlaceholderPhoto('driver')),
                        'url' => Yii::app()->createAbsoluteUrl("/driver/overview", ['id' => $driver->driver_uuid]),
                        'active_task' => CDriver::getCountActiveTask($driver->driver_id, $now)
                    ];
                } catch (Exception $e) {
                    //
                }
            }

            $merchant_zone = CMerchants::getListMerchantZone([$merchant_id]);
            if (!$zone_list = CommonUtility::getDataToDropDown("{{zones}}", 'zone_id', 'zone_name')) {
                $zone_list = [];
            }

            $order_status = isset($order['order_info']) ? $order['order_info']['status'] : '';
            $order['order_info']['show_assign_driver'] = false;
            $order['order_info']['can_reassign_driver'] = true;

            $atts = OptionsTools::find(['self_delivery'], $merchant_id);
            $self_delivery = isset($atts['self_delivery']) ? $atts['self_delivery'] : false;
            $self_delivery = $self_delivery == 1 ? true : false;

            if ($order_type == "delivery" && !$self_delivery) {
                $status1 = COrders::getStatusTab2(['new_order', 'order_processing', 'order_ready']);
                $status2 = AOrderSettings::getStatus(array('status_delivered', 'status_completed', 'status_delivery_fail', 'status_failed'));
                $all_status = array_merge((array)$status1, (array)$status2);
                if (in_array($order_status, (array)$all_status)) {
                    $order['order_info']['show_assign_driver'] = true;
                }
                if (in_array($order_status, (array)$status2)) {
                    $order['order_info']['can_reassign_driver'] = false;
                }
            }

            $order_table_data = [];
            if ($order_type == "dinein") {
                $order_table_data = COrders::orderMeta(['table_id', 'room_id', 'guest_number']);
                $room_id = isset($order_table_data['room_id']) ? $order_table_data['room_id'] : 0;
                $table_id = isset($order_table_data['table_id']) ? $order_table_data['table_id'] : 0;
                try {
                    $table_info = CBooking::getTableByID($table_id);
                    $order_table_data['table_name'] = $table_info->table_name;
                } catch (Exception $e) {
                    //$order_table_data['table_name'] = t("Unavailable");
                }
                try {
                    $room_info = CBooking::getRoomByID($room_id);
                    $order_table_data['room_name'] = $room_info->room_name;
                } catch (Exception $e) {
                    //$order_table_data['room_name'] = t("Unavailable");
                }
            }

            $found_kitchen = Ckitchen::getByReference($order_id);
            $kitchen_addon = CommonUtility::checkModuleAddon("Karenderia Kitchen App");

            $data = array(
                'merchant' => $merchant_info,
                'order' => $order,
                'items' => $items,
                'summary' => $summary,
                'summary_total' => $summary_total,
                'summary_changes' => $summary_changes,
                'summary_transaction' => $summary_transaction,
                'customer' => $customer,
                'buttons' => $buttons,
                'sold_out_options' => AttributesTools::soldOutOptions(),
                'link_pdf' => $link_pdf,
                'print_settings' => $print_settings,
                'payment_history' => $payment_history,
                'credit_card_details' => $credit_card_details,
                'driver_data' => $driver_data,
                'zone_list' => $zone_list,
                'merchant_zone' => $merchant_zone,
                'order_table_data' => $order_table_data,
                'kitchen_addon' => $kitchen_addon,
                'found_in_kitchen' => $found_kitchen,
            );

            $this->code = 1;
            $this->msg = "OK";
            $this->details = array(
                'data' => $data,
            );

            $model_order->is_view = 1;
            $model_order->save();
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetCustomerDetails()
    {
        try {

            $this->data = Yii::app()->input->xssClean(json_decode(file_get_contents('php://input'), true));
            $client_id = isset($this->data['client_id']) ? intval($this->data['client_id']) : 0;
            $merchant_id = isset($this->data['merchant_id']) ? intval($this->data['merchant_id']) : 0;

            $addresses = array();

            if ($data = COrders::getClientInfo($client_id)) {
                try {
                    $addresses = ACustomer::getAddresses($client_id);
                } catch (Exception $e) {
                    //
                }

                $this->code = 1;
                $this->msg = "OK";
                $this->details = array(
                    'customer' => $data,
                    'block_from_ordering' => ACustomer::isBlockFromOrdering($client_id, $merchant_id),
                    'addresses' => $addresses,
                );
            } else $this->msg = t("Client information not found");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetCustomerSummary()
    {
        try {

            $client_id = isset($this->data['client_id']) ? $this->data['client_id'] : 0;
            //$merchant_id = isset($this->data['merchant_id'])?intval($this->data['merchant_id']):0;
            $merchant_id = 0;

            $draft = AttributesTools::initialStatus();

            $not_in_status = AOrderSettings::getStatus(array('status_cancel_order', 'status_rejection'));
            array_push($not_in_status, $draft);
            $orders = ACustomer::getOrdersTotal($client_id, $merchant_id, array(), (array)$not_in_status);

            $status_cancel = AOrderSettings::getStatus(array('status_cancel_order'));
            $order_cancel = ACustomer::getOrdersTotal($client_id, $merchant_id, $status_cancel);

            $status_delivered = AOrderSettings::getStatus(array('status_delivered', 'status_completed'));
            $total = ACustomer::getOrderSummary($client_id, $merchant_id, $status_delivered);
            $total_refund = ACustomer::getOrderRefundSummary($client_id, $merchant_id, AttributesTools::refundStatus());

            $data = array(
                'orders' => $orders,
                'order_cancel' => $order_cancel,
                'total' => Price_Formatter::formatNumberNoSymbol($total),
                'total_refund' => Price_Formatter::formatNumberNoSymbol($total_refund),
                'price_format' => AttributesTools::priceUpFormat()
            );

            $this->code = 1;
            $this->msg = "OK";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetCustomerOrders()
    {
        $this->data = Yii::app()->input->xssClean(json_decode(file_get_contents('php://input'), true));
        $data = array();
        //$merchant_id = isset($this->data['merchant_id'])?intval($this->data['merchant_id']):0;
        $client_id = isset($this->data['client_id']) ? $this->data['client_id'] : 0;
        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ? $this->data['order'][0] : '';

        $sortby = "order_id";
        $sort = 'DESC';
        if (array_key_exists($order['column'], (array)$columns)) {
            $sort = $order['dir'];
            $sortby = $columns[$order['column']]['data'];
        }


        $page = $page > 0 ? intval($page) / intval($length) : 0;

        $initial_status = AttributesTools::initialStatus();
        $status = COrders::statusList(Yii::app()->language);

        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.order_id,a.order_uuid,a.total,a.status, b.restaurant_name";
        $criteria->join = 'LEFT JOIN {{merchant}} b on  a.merchant_id=b.merchant_id ';
        /*$criteria->condition = "merchant_id=:merchant_id AND client_id=:client_id ";
		$criteria->params  = array(
		  ':merchant_id'=>intval($merchant_id),
		  ':client_id'=>intval($client_id)
		);*/
        $criteria->condition = "client_id=:client_id ";
        $criteria->params  = array(
            ':client_id' => intval($client_id)
        );
        $criteria->order = "$sortby $sort";

        if (is_string($search) && strlen($search) > 0) {
            $criteria->addSearchCondition('order_id', $search);
            $criteria->addSearchCondition('a.status', $search, true, 'OR');
        }
        $criteria->addNotInCondition('a.status', array($initial_status));

        $count = AR_ordernew::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);
        $models = AR_ordernew::model()->findAll($criteria);

        $buttons = <<<HTML
<div class="btn-group btn-group-actions small" role="group">
 <a href="{{view_order}}" target="_blank" class="btn btn-light tool_tips"><i class="zmdi zmdi-eye"></i></a>
 <a href="{{print_pdf}}" target="_blank"  class="btn btn-light tool_tips"><i class="zmdi zmdi-download"></i></a>
</div>
HTML;
        foreach ($models as $val) {
            $status_html = $val->status;
            if (array_key_exists($val->status, (array)$status)) {
                $new_status = $status[$val->status]['status'];
                $inline_style = "background:" . $status[$val->status]['background_color_hex'] . ";";
                $inline_style .= "color:" . $status[$val->status]['font_color_hex'] . ";";
                $status_html = <<<HTML
<span class="badge" style="$inline_style" >$new_status</span>
HTML;
            }

            $_buttons = str_replace(
                "{{view_order}}",
                Yii::app()->createUrl('/order/view', array('order_uuid' => $val->order_uuid)),
                $buttons
            );

            $_buttons = str_replace(
                "{{print_pdf}}",
                Yii::app()->createUrl('/preprint/pdf', array('order_uuid' => $val->order_uuid)),
                $_buttons
            );

            $data[] = array(
                'order_id' => $val->order_id,
                'restaurant_name' => Yii::app()->input->xssClean($val->restaurant_name),
                'total' => Price_Formatter::formatNumber($val->total),
                'status' => $status_html,
                'order_uuid' => $_buttons
            );
        }


        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actionblockCustomer()
    {
        try {

            $meta_name = 'block_customer';
            $this->data = Yii::app()->input->xssClean(json_decode(file_get_contents('php://input'), true));

            $merchant_id = isset($this->data['merchant_id']) ? intval($this->data['merchant_id']) : 0;
            $client_id = isset($this->data['client_id']) ? $this->data['client_id'] : 0;
            $block = isset($this->data['block']) ? $this->data['block'] : 0;

            $model = AR_merchant_meta::model()->find("merchant_id=:merchant_id AND 
			meta_name=:meta_name AND meta_value=:meta_value", array(
                ':merchant_id' => intval($merchant_id),
                ':meta_name' => $meta_name,
                ':meta_value' => $client_id
            ));

            if ($model) {
                if ($block != 1) {
                    $model->delete();
                }
            } else {
                if ($block == 1) {
                    $model = new AR_merchant_meta;
                    $model->merchant_id = $merchant_id;
                    $model->meta_name = $meta_name;
                    $model->meta_value = $client_id;
                    $model->save();
                }
            }

            $this->code = 1;
            $this->msg = t("Successful");
            $this->details = intval($block);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetOrderHistory()
    {
        try {
            $order_uuid = isset($this->data['order_uuid']) ? $this->data['order_uuid'] : '';
            $data = AOrders::getOrderHistory($order_uuid);
            $order_status = AttributesTools::getOrderStatus(Yii::app()->language, 'delivery_status');

            $order = COrders::get($order_uuid);
            $meta_proof = AR_driver_meta::getMeta2(0, $order->order_id, array('order_proof'));

            $meta = AR_admin_meta::getValue('status_delivery_delivered');
            $delivery_status = isset($meta['meta_value']) ? $meta['meta_value'] : '';

            $this->code = 1;
            $this->msg = "OK";
            $this->details = array(
                'data' => $data,
                'order_status' => $order_status,
                'order_proof' => $meta_proof,
                'delivery_status' => $delivery_status
            );
        } catch (Exception $e) {
            $this->msg[] = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionsetDelayToOrder()
    {
        try {

            $time_delay = isset($this->data['time_delay'])?intval($this->data['time_delay']):'';
            $order_uuid = isset($this->data['order_uuid'])?$this->data['order_uuid']:'';
            $model = COrders::get($order_uuid);
            $model->scenario = "delay_order";

            $model->remarks = "Order is delayed by [mins]min(s)";
            $model->ramarks_trans = json_encode(array('[mins]'=>$time_delay));

            COrders::savedMeta($model->order_id,'delayed_order', t($model->remarks,array('[mins]'=>$time_delay)) );
            COrders::savedMeta($model->order_id,'delayed_order_mins',$time_delay );

            if($model->save()){
                $this->code = 1;
                $this->msg = t("Customer is notified about the delayed.");
            } else $this->msg = CommonUtility::parseError( $model->getErrors());
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }


    public function actiongetAllOrderSummary()
    {
        try {

            $initial_status = AttributesTools::initialStatus();
            $refund_status = AttributesTools::refundStatus();
            $orders = 0;
            $order_cancel = 0;
            $total = 0;

            $not_in_status = AOrderSettings::getStatus(array('status_cancel_order', 'status_rejection'));
            array_push($not_in_status, $initial_status);
            $orders = AOrders::getOrdersTotal(0, array(), $not_in_status);

            $status_cancel = AOrderSettings::getStatus(array('status_cancel_order'));
            $order_cancel = AOrders::getOrdersTotal(0, $status_cancel);

            $status_delivered = AOrderSettings::getStatus(array('status_delivered', 'status_completed'));
            $total = AOrders::getOrderSummary(0, $status_delivered, 'exchange_rate_merchant_to_admin');
            $total_refund = AOrders::getTotalRefund(0, $refund_status, 'exchange_rate_merchant_to_admin');

            $data = array(
                'orders' => $orders,
                'order_cancel' => $order_cancel,
                'total' => Price_Formatter::formatNumberNoSymbol($total),
                'total_refund' => Price_Formatter::formatNumberNoSymbol($total_refund),
                'price_format' => AttributesTools::priceUpFormat()
            );

            $this->code = 1;
            $this->msg = "OK";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionplans_features()
    {

        $ref_id = isset($this->data['ref_id']) ? $this->data['ref_id'] : 0;
        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  isset($this->data['order'][0]) ? $this->data['order'][0] : ''   : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';

        $data = array();
        $sortby = "meta_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        $criteria->addCondition('meta_name=:meta_name AND meta_value1=:meta_value1');
        $criteria->params = array(':meta_name' => 'plan_features', ':meta_value1' => $ref_id);

        $criteria->order = "$sortby $sort";
        $count = AR_admin_meta::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);
        $models = AR_admin_meta::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {
                $data[] = array(
                    'meta_id' => $item->meta_id,
                    'meta_value' => $item->meta_value,
                    'update_url' => Yii::app()->createUrl("/plans/feature_update/", array('id' => $item->meta_value1, 'meta_id' => $item->meta_id)),
                    'delete_url' => Yii::app()->createUrl("/plans/feature_delete/", array('id' => $item->meta_value1, 'meta_id' => $item->meta_id)),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actioncustomerOrderList()
    {
        //test push
        $data = array();
        $status = COrders::statusList(Yii::app()->language);
        $services = COrders::servicesList(Yii::app()->language);
        $payment_gateway = AttributesTools::PaymentProvider();
        $initial_status = AttributesTools::initialStatus();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';
        $filter = isset($this->data['filter']) ? $this->data['filter'] : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';
        $client_id = isset($this->data['ref_id']) ? $this->data['ref_id'] : '';

        $sortby = "order_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.order_id, a.client_id, a.status, a.order_uuid , a.merchant_id,
		a.payment_code, a.service_code,a.total, a.date_created,
		b.meta_value as customer_name, 
		c.restaurant_name, c.logo, c.path,
		(
		   select sum(qty)
		   from {{ordernew_item}}
		   where order_id = a.order_id
		) as total_items
		";
        $criteria->join = '
		LEFT JOIN {{ordernew_meta}} b on  a.order_id = b.order_id 
		LEFT JOIN {{merchant}} c on  a.merchant_id = c.merchant_id 
		';

        $criteria->condition = "a.client_id=:client_id AND b.meta_name=:meta_name ";
        $criteria->params  = array(
            ':client_id' => intval($client_id),
            ':meta_name' => 'customer_name'
        );
        $criteria->addNotInCondition('a.status', (array) array($initial_status));

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(a.date_created,'%Y-%m-%d')", $date_start, $date_end);
        }

        if (is_array($filter) && count($filter) >= 1) {
            $filter_merchant_id = isset($filter['merchant_id']) ? $filter['merchant_id'] : '';
            $filter_order_status = isset($filter['order_status']) ? $filter['order_status'] : '';
            $filter_order_type = isset($filter['order_type']) ? $filter['order_type'] : '';
            $filter_client_id = isset($filter['client_id']) ? intval($filter['client_id']) : '';
            $filter_order_id = isset($filter['order_id']) ? intval($filter['order_id']) : '';

            if ($filter_merchant_id > 0) {
                $criteria->addSearchCondition('a.merchant_id', $filter_merchant_id);
            }
            if (!empty($filter_order_status)) {
                $criteria->addSearchCondition('a.status', $filter_order_status);
            }
            if (!empty($filter_order_type)) {
                $criteria->addSearchCondition('a.service_code', $filter_order_type);
            }
            if ($filter_client_id > 0) {
                $criteria->addSearchCondition('a.client_id', intval($filter_client_id));
            }
            if ($filter_order_id > 0) {
                $criteria->addSearchCondition('a.order_id', intval($filter_order_id));
            }
        }

        $criteria->order = "$sortby $sort";
        $count = AR_ordernew::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $models = AR_ordernew::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {

                $item->total_items = intval($item->total_items);
                $item->total_items = t("{{total_items}} items", array(
                    '{{total_items}}' => $item->total_items
                ));

                $trans_order_type = $item->service_code;
                if (array_key_exists($item->service_code, $services)) {
                    $trans_order_type = $services[$item->service_code]['service_name'];
                }

                $order_type = t("Order Type.");
                $order_type .= "<span class='ml-2 services badge $item->service_code'>$trans_order_type</span>";

                $total = t("Total. {{total}}", array(
                    '{{total}}' => Price_Formatter::formatNumber($item->total)
                ));
                $place_on = t("Place on {{date}}", array(
                    '{{date}}' => Date_Formatter::dateTime($item->date_created)
                ));

                $status_trans = $item->status;
                if (array_key_exists($item->status, (array) $status)) {
                    $status_trans = $status[$item->status]['status'];
                }

                $logo_url = CMedia::getImage($item->logo, $item->path, '@thumbnail', CommonUtility::getPlaceholderPhoto('merchant'));

                $payment_name = isset($payment_gateway[$item->payment_code]) ? $payment_gateway[$item->payment_code] : $item->payment_code;


                $logo_html = <<<HTML
<img src="$logo_url" class="img-60 rounded-circle" />
HTML;


                $information = <<<HTML
$item->total_items<span class="ml-2 badge order_status $item->status">$status_trans</span>
<p class="dim m-0">$payment_name</p>
<p class="dim m-0">$order_type</p>
<p class="dim m-0">$total</p>
<p class="dim m-0">$place_on</p>
HTML;

                $data[] = array(
                    'merchant_id' => $logo_html,
                    'client_id' => $information,
                    'order_id' => $item->order_id,
                    'restaurant_name' => $item->restaurant_name,
                    'order_uuid' => $item->order_uuid,
                    'view_order' => Yii::app()->createAbsoluteUrl('/order/view', array('order_uuid' => $item->order_uuid)),
                    'view_pdf' => Yii::app()->createAbsoluteUrl('/preprint/pdf', array('order_uuid' => $item->order_uuid)),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actionzoneList()
    {
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';
        $filter = isset($this->data['filter']) ? $this->data['filter'] : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';

        $sortby = "zone_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        $criteria->condition = "merchant_id=0";

        if (is_string($search) && strlen($search) > 0) {
            $criteria->addSearchCondition('zone_name', $search);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_zones::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);

        if ($length > 0) {
            $pages->applyLimit($criteria);
        }

        $models = AR_zones::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {
                $data[] = array(
                    'zone_id' => $item->zone_id,
                    'zone_name' => $item->zone_name,
                    'description' => $item->description,
                    'zone_id' => $item->zone_id,
                    'update_url' => Yii::app()->createUrl("/attributes/zone_update/", array('id' => $item->zone_uuid)),
                    'delete_url' => Yii::app()->createUrl("/attributes/zone_delete/", array('id' => $item->zone_uuid)),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actiondashboardSummary()
    {
        try {

            $balance = 0;
            $status_completed = AOrderSettings::getStatus(array('status_delivered', 'status_completed'));

            $total_sales = CReports::totalSales($status_completed);
            $total_merchant = CReports::totalMerchant(array('active'));
            $total_subscriptions = CReports::totalSubscriptions();

            try {
                $card_id = CWallet::createCard(Yii::app()->params->account_type['admin']);
                $balance = CWallet::getBalance($card_id);
            } catch (Exception $e) {
                //
            }


            $data = array(
                'total_sales' => intval($total_sales),
                'total_merchant' => intval($total_merchant),
                'total_commission' => floatval($balance),
                'total_subscriptions' => floatval($total_subscriptions),
                'price_format' => AttributesTools::priceUpFormat()
            );

            $this->code = 1;
            $this->msg = "ok";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actioncommissionSummary()
    {
        try {

            $card_id = 0;
            try {
                $card_id = CWallet::createCard(Yii::app()->params->account_type['admin']);
            } catch (Exception $e) {
                //
            }

            $commission_week = CReports::WalletEarnings($card_id);
            $commission_month = CReports::WalletEarnings($card_id, 30);
            $subscription_month = CReports::PlansEarning(30);

            $data = array(
                'commission_week' => floatval($commission_week),
                'commission_month' => floatval($commission_month),
                'subscription_month' => floatval($subscription_month),
                'price_format' => AttributesTools::priceUpFormat()
            );

            $this->code = 1;
            $this->msg = "ok";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetOrdersCount()
    {
        try {

            $merchant_id = Yii::app()->merchant->merchant_id ?? "null";
            $new_order = AOrders::getOrderTabsStatus('new_order');
            $order_prepending = AOrders::getOrderTabsStatus('order_prepending');
            $order_processing = AOrders::getOrderTabsStatus('order_processing');
            $order_ready = AOrders::getOrderTabsStatus('order_ready');
            $completed_today = AOrders::getOrderTabsStatus('completed_today');
            $with_delivery = AOrders::getOrderTabsStatus('with_delivery');
            $status_scheduled = (array) $new_order;

            if ($order_processing) {
                foreach ($order_processing as $order_processing_val) {
                    array_push($status_scheduled, $order_processing_val);
                }
            }

            $prepending = AOrders::getOrderCountPrepending($order_prepending, date("Y-m-d"));
            $new = AOrders::getOrderCountPerStatus($merchant_id, $new_order, date("Y-m-d"));
            $processing = AOrders::getOrderCountPerStatus($merchant_id, $order_processing, date("Y-m-d"));
            $ready = AOrders::getOrderCountPerStatus($merchant_id, $order_ready, date("Y-m-d"));
            $completed = AOrders::getOrderCountPerStatus($merchant_id, $completed_today, date("Y-m-d"));
            $withDelivery = AOrders::getOrderCountWithDelivery($with_delivery, date("Y-m-d"));
            $scheduled = AOrders::getOrderCountSchedule($merchant_id, $status_scheduled, date("Y-m-d"));
            $all_orders = AOrders::getAllOrderCount($merchant_id);

            $not_viewed = AOrders::OrderNotViewed($merchant_id, $new_order, date("Y-m-d"));

            $data = array(
                'order_prepending' => $prepending,
                'new_order' => $new,
                'order_processing' => $processing,
                'order_ready' => $ready,
                'completed_today' => $completed,
                'with_delivery' => $withDelivery,
                'scheduled' => $scheduled,
                'all_orders' => $all_orders,
                'not_viewed' => $not_viewed,
            );

            $this->code = 1;
            $this->msg = "OK";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetLastTenOrder()
    {
        try {

            $data = array();
            $order_status = array();
            $datetime = date("Y-m-d g:i:s a");
            $filter_by = Yii::app()->input->post('filter_by');
            $limit = Yii::app()->input->post('limit');

            if ($filter_by != "all") {
                $order_status = AOrders::getOrderTabsStatus($filter_by);
            }

            $status = COrders::statusList(Yii::app()->language);
            $services = COrders::servicesList(Yii::app()->language);
            $payment_status = COrders::paymentStatusList2(Yii::app()->language, 'payment');
            $status_not_in = AOrderSettings::getStatus(array(
                'status_delivered',
                'status_completed',
                'status_cancel_order',
                'status_rejection',
                'status_delivery_fail',
                'status_failed'
            ));
            $payment_list = AttributesTools::PaymentProvider();

            $criteria = new CDbCriteria();
            $criteria->alias = "a";
            $criteria->select = "a.order_id, a.order_uuid, a.client_id, a.status, a.order_uuid , a.merchant_id,
		    a.payment_code, a.service_code,a.total, a.delivery_date, a.delivery_time, a.date_created, a.payment_code, a.total,
		    a.payment_status, a.is_view, a.is_critical, a.whento_deliver,		
			a.use_currency_code,a.base_currency_code, a.admin_base_currency, a.exchange_rate, a.exchange_rate_merchant_to_admin,   		    		    
		    b.meta_value as customer_name, 
		    
		    IF(a.whento_deliver='now', 
		      TIMESTAMPDIFF(MINUTE, a.date_created, NOW())
		    , 
		     TIMESTAMPDIFF(MINUTE, concat(a.delivery_date,' ',a.delivery_time), NOW())
		    ) as min_diff
		    
		    ,
		    (
		       select sum(qty)
		       from {{ordernew_item}}
		       where order_id = a.order_id
		    ) as total_items,
		    
		    c.restaurant_name
		    
		    ";
            $criteria->join = '
		    LEFT JOIN {{ordernew_meta}} b on a.order_id = b.order_id 
		    LEFT JOIN {{merchant}} c on a.merchant_id = c.merchant_id 
		    ';
            $criteria->condition = "b.meta_name=:meta_name ";
            $criteria->params  = array(
                ':meta_name' => 'customer_name'
            );

            if (is_array($order_status) && count($order_status) >= 1) {
                $criteria->addInCondition('a.status', (array) $order_status);
            } else {
                $draft = AttributesTools::initialStatus();
                $criteria->addNotInCondition('a.status', array($draft));
            }

            $criteria->order = "a.date_created DESC";
            $criteria->limit = intval($limit);

            PrettyDateTime::$category = 'backend';

            $models = AR_ordernew::model()->findAll($criteria);
            if ($models) {

                $price_list_format = CMulticurrency::getAllCurrency();

                foreach ($models as $item) {

                    $status_trans = $item->status;
                    if (array_key_exists($item->status, (array) $status)) {
                        $status_trans = $status[$item->status]['status'];
                    }

                    $trans_order_type = $item->service_code;
                    if (array_key_exists($item->service_code, (array)$services)) {
                        $trans_order_type = $services[$item->service_code]['service_name'];
                    }

                    $payment_status_name = $item->payment_status;
                    if (array_key_exists($item->payment_status, (array)$payment_status)) {
                        $payment_status_name = $payment_status[$item->payment_status]['title'];
                    }

                    if (array_key_exists($item->payment_code, (array)$payment_list)) {
                        $item->payment_code = $payment_list[$item->payment_code];
                    }

                    $is_critical =  0;
                    if ($item->whento_deliver == "schedule") {
                        if ($item->min_diff > 0) {
                            $is_critical = true;
                        }
                    } else if ($item->min_diff > 10 && !in_array($item->status, (array)$status_not_in)) {
                        $is_critical = true;
                    }

                    $price_format = isset($price_list_format[$item->admin_base_currency]) ? $price_list_format[$item->admin_base_currency] : Price_Formatter::$number_format;

                    $data[] = array(
                        'order_id' => $item->order_id,
                        'order_id' => t("Order #{{order_id}}", array('{{order_id}}' => $item->order_id)),
                        'restaurant_name' => Yii::app()->input->xssClean($item->restaurant_name),
                        'order_uuid' => $item->order_uuid,
                        'client_id' => $item->client_id,
                        'customer_name' => Yii::app()->input->xssClean($item->customer_name),
                        'status' => $status_trans,
                        'status_raw' => str_replace(" ", "_", $item->status),
                        'order_type' => $trans_order_type,
                        'payment_code' => $item->payment_code == CDigitalWallet::transactionName() ? CDigitalWallet::paymentName() : t($item->payment_code),
                        'total' => Price_Formatter::formatNumber2(($item->total * $item->exchange_rate_merchant_to_admin), $price_format),
                        'payment_status' => $payment_status_name,
                        'payment_status_raw' => str_replace(" ", "_", $item->payment_status),
                        'is_view' => $item->is_view,
                        'is_critical' => $is_critical,
                        'min_diff' => $item->min_diff,
                        'whento_deliver' => $item->whento_deliver,
                        'delivery_date' => $item->delivery_date,
                        'delivery_time' => $item->delivery_time,
                        'view_order' => Yii::app()->createAbsoluteUrl('/order/view', array('order_uuid' => $item->order_uuid)),
                        'print_pdf' => Yii::app()->createAbsoluteUrl('/preprint/pdf', array('order_uuid' => $item->order_uuid)),
                        'date_created' => PrettyDateTime::parse(new DateTime($item->date_created)),
                    );
                }

                $this->code = 1;
                $this->msg = "ok";
                $this->details = $data;
            } else {
                $this->msg = t("You don't have current orders.");
                $this->details = array(
                    'image_url' => CMedia::themeAbsoluteUrl() . "/assets/images/order-best-food@2x.png"
                );
            }
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
            $this->details = array(
                'image_url' => CMedia::themeAbsoluteUrl() . "/assets/images/order-best-food@2x.png"
            );
        }
        $this->responseJson();
    }

    public function actionmostPopularItems()
    {
        try {

            $data = array();

            $limit = Yii::app()->input->post('limit');
            $status_completed = AOrderSettings::getStatus(array('status_delivered', 'status_completed'));

            $criteria = new CDbCriteria();
            $criteria->alias = "a";
            $criteria->select = "a.item_id, a.cat_id, sum(qty) as total_sold,
			b.photo , b.path,
			(
			  select item_name from {{item_translation}}
			  where item_id = a.item_id and language=" . q(Yii::app()->language) . "
			) as item_name,
			(
			  select category_name from {{category_translation}}
			  where cat_id=a.cat_id and language=" . q(Yii::app()->language) . "
			) as category_name,
			
			m.restaurant_name
			";
            $criteria->join = '
			LEFT JOIN {{item}} b on  a.item_id = b.item_id 
			LEFT JOIN {{ordernew}} c on a.order_id = c.order_id 
			LEFT JOIN {{merchant}} m on c.merchant_id = m.merchant_id 
			';

            if (is_array($status_completed) && count($status_completed) >= 1) {
                $criteria->addInCondition('c.status', (array) $status_completed);
            }

            $criteria->group = "a.item_id,a.cat_id,m.restaurant_name";
            $criteria->order = "sum(qty) DESC";
            $criteria->limit = intval($limit);

            $model = AR_ordernew_item::model()->findAll($criteria);

            if ($model) {
                foreach ($model as $item) {
                    $total_sold = number_format($item->total_sold, 0, '', ',');
                    $data[] = array(
                        'item_name' => Yii::app()->input->xssClean(htmlspecialchars_decode($item->item_name)),
                        'category_name' => Yii::app()->input->xssClean(htmlspecialchars_decode($item->category_name)),
                        'total_sold' => t("{{total_sold}} sold", array('{{total_sold}}' => $total_sold)),
                        'image_url' => CMedia::getImage($item->photo, $item->path, '@thumbnail', CommonUtility::getPlaceholderPhoto('item')),
                        'item_link' => Yii::app()->createAbsoluteUrl('/food/item_update', array('item_id' => $item->item_id)),
                        'restaurant_name' => Yii::app()->input->xssClean($item->restaurant_name),
                    );
                }

                $this->code = 1;
                $this->msg = "ok";
                $this->details = $data;
            } else {
                $this->msg = t("No item solds yet");
                $this->details = array(
                    'image_url' => CMedia::themeAbsoluteUrl() . "/assets/images/order-best-food@2x.png"
                );
            }
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
            $this->details = array(
                'image_url' => CMedia::themeAbsoluteUrl() . "/assets/images/order-best-food@2x.png"
            );
        }
        $this->responseJson();
    }

    public function actionitemSales()
    {
        try {

            $data = array();
            $items = array();
            $data = array();
            $period = Yii::app()->input->post('period');

            Yii::app()->db->createCommand("SET SESSION sql_mode = (SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))")->query();
            $data = CReports::ItemSales(0, $period);

            try {
                $items = CReports::popularItems(0, $period);
            } catch (Exception $e) {
                //
            }

            $this->code = 1;
            $this->msg = "ok";
            $this->details = array(
                'sales' => $data,
                'items' => $items,
            );
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
            $this->details = array(
                'image_url' => CMedia::themeAbsoluteUrl() . "/assets/images/no-results0.png"
            );
        }
        $this->responseJson();
    }

    public function actionsalesOverview()
    {
        try {

            $data = array();
            $months = intval(Yii::app()->input->post('months'));

            $status_completed = AOrderSettings::getStatus(array('status_delivered', 'status_completed'));
            $date_start = date("Y-m-d", strtotime(date("c") . " -$months months"));
            $date_end = date("Y-m-d");

            $table = new TableDataStatus();
            $field_exist = $table->fieldExist("{{ordernew}}", 'created_at');

            $criteria = new CDbCriteria();

            if ($field_exist) {
                Yii::app()->db->createCommand("SET SESSION sql_mode = (SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))")->query();

                $criteria->select = "
				DATE_FORMAT(`created_at`, '%b') AS month , SUM(total) as monthly_sales
				";
                $criteria->group = "MONTH(`created_at`)";
                $criteria->order = "created_at DESC";

                if (is_array($status_completed) && count($status_completed) >= 1) {
                    $criteria->addInCondition('status', (array) $status_completed);
                }
                if (!empty($date_start) && !empty($date_end)) {
                    $criteria->addBetweenCondition("DATE_FORMAT(created_at,'%Y-%m-%d')", $date_start, $date_end);
                }
            } else {
                $criteria->select = "DATE_FORMAT(date_created, '%b') AS month , SUM(total) as monthly_sales";

                $criteria->group = "DATE_FORMAT(date_created, '%b')";
                $criteria->order = "date_created DESC";

                if (is_array($status_completed) && count($status_completed) >= 1) {
                    $criteria->addInCondition('status', (array) $status_completed);
                }
                if (!empty($date_start) && !empty($date_end)) {
                    $criteria->addBetweenCondition("DATE_FORMAT(date_created,'%Y-%m-%d')", $date_start, $date_end);
                }
            }

            $model = AR_ordernew::model()->findAll($criteria);
            if ($model) {
                $category = array();
                $sales = array();
                foreach ($model as $item) {
                    $category[] = t($item->month);
                    $sales[] = floatval($item->monthly_sales);
                }

                $data = array(
                    'category' => $category,
                    'data' => $sales
                );

                $this->code = 1;
                $this->msg = "ok";
                $this->details = $data;
            } else {
                $this->msg = t("You don't have sales yet");
                $this->details = array(
                    'image_url' => CMedia::themeAbsoluteUrl() . "/assets/images/no-results2.png"
                );
            }
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
            $this->details = array(
                'image_url' => CMedia::themeAbsoluteUrl() . "/assets/images/order-best-food@2x.png"
            );
        }
        $this->responseJson();
    }

    public function actionmostPopularCustomer()
    {
        try {

            $data = array();
            $limit = Yii::app()->input->post('limit');
            $not_in_status = AOrderSettings::getStatus(array('status_cancel_order', 'status_rejection'));

            $criteria = new CDbCriteria();
            $criteria->alias = "a";
            $criteria->select = "a.client_id, count(*) as total_sold,
			b.first_name,b.last_name,b.date_created, b.avatar as logo, b.path
			";
            $criteria->join = 'LEFT JOIN {{client}} b on  a.client_id=b.client_id ';

            $criteria->condition = "b.client_id IS NOT NULL";

            if (is_array($not_in_status) && count($not_in_status) >= 1) {
                $criteria->addNotInCondition('a.status', (array) $not_in_status);
            }

            $criteria->group = "a.client_id";
            $criteria->order = "count(*) DESC";
            $criteria->limit = intval($limit);

            $model = AR_ordernew::model()->findAll($criteria);
            if ($model) {
                foreach ($model as $item) {
                    $total_sold = number_format($item->total_sold, 0, '', ',');
                    $data[] = array(
                        'client_id' => $item->client_id,
                        'first_name' => $item->first_name,
                        'last_name' => $item->last_name,
                        'total_sold' => t("{{total_sold}} orders", array('{{total_sold}}' => $total_sold)),
                        'member_since' => t("Member since {{date_created}}", array('{{date_created}}' => Date_Formatter::dateTime($item->date_created))),
                        'image_url' => CMedia::getImage($item->logo, $item->path, '@thumbnail', CommonUtility::getPlaceholderPhoto('customer')),
                    );
                }
                $this->code = 1;
                $this->msg = "ok";
                $this->details = $data;
            } else $this->msg = t("You don't have customer yet");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionOverviewReview()
    {
        try {

            $data = array();
            $total = 0;
            $merchant_id = 0;

            $total = CReviews::reviewsCount($merchant_id);
            $start = date('Y-m-01');
            $end = date("Y-m-d");
            $this_month = CReviews::totalCountByRange($merchant_id, $start, $end);
            $user = CReviews::userAddedReview($merchant_id, 4);
            $review_summary = CReviews::summaryCount($merchant_id, $total);

            $data = array(
                'total' => $total,
                'this_month' => $this_month,
                'this_month_words' => t("This month you got {{count}} New Reviews", array('{{count}}' => $this_month)),
                'user' => $user,
                'review_summary' => $review_summary,
                'link_to_review' => Yii::app()->createAbsoluteUrl('/buyer/review_list')
            );

            $this->code = 1;
            $this->msg = "ok";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionpopularMerchant()
    {
        try {

            $limit = Yii::app()->input->post('limit');
            $data = CReports::PopularMerchant($limit);
            $this->code = 1;
            $this->msg = "ok";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionPopularMerchantByReview()
    {
        try {

            $limit = Yii::app()->input->post('limit');
            $data = CReports::PopularMerchantByReview($limit);

            $cuisine_list = AttributesTools::cuisineGroup(Yii::app()->language, $data['merchant_ids']);

            $this->code = 1;
            $this->msg = "ok";
            $this->details = array(
                'data' => $data['data'],
                'cuisine_list' => $cuisine_list,
            );
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionDailyStatistic()
    {
        try {

            $status_new = AOrderSettings::getStatus(array('status_new_order'));
            $status_prepending = AOrderSettings::getStatus(array('status_prepending_order'));
            $status_with_delivery = AOrderSettings::getStatus(array('status_with_delivery_order'));
            $status_delivered = AOrderSettings::getStatus(array('status_delivered', 'status_completed'));

            $order_received = CReports::OrderTotalByStatus(0, $status_new);
            $today_delivered = CReports::OrderTotalByStatus(0, $status_delivered);
            $new_customer = CReports::CustomerTotalByStatus(1);
            $total_refund = CReports::TotalRefund();

            $data = array(
                'order_received' => $order_received,
                'today_delivered' => $today_delivered,
                'new_customer' => $new_customer,
                'total_refund' => $total_refund,
                'price_format' => AttributesTools::priceUpFormat()
            );
            $this->code = 1;
            $this->msg = "ok";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionRecentPayout()
    {
        try {

            $data = array();
            $limit = Yii::app()->input->post('limit');
            $criteria = new CDbCriteria();
            $criteria->alias = "a";
            $criteria->select = "a.transaction_date, a.transaction_amount, a.status, a.transaction_uuid,
    	    (
    	      select concat(restaurant_name,';',logo,';',path) 
    	      from {{merchant}}
    	      where merchant_id = b.account_id
    	    ) as meta_name
    	    ";
            $criteria->join = "LEFT JOIN {{wallet_cards}} b on  a.card_id = b.card_id ";

            $criteria->condition = "a.transaction_type=:transaction_type";
            $criteria->params = array(':transaction_type' => 'payout');

            $criteria->addNotInCondition('a.status', array('cancelled'));
            $criteria->limit = intval($limit);
            $criteria->order = "a.transaction_date DESC";


            if ($model = AR_wallet_transactions::model()->findAll($criteria)) {
                foreach ($model as $item) {
                    $meta_name = explode(";", $item->meta_name);
                    $restaurant_name = isset($meta_name[0]) ? $meta_name[0] : '';
                    $logo = isset($meta_name[1]) ? $meta_name[1] : '';
                    $path = isset($meta_name[2]) ? $meta_name[2] : '';

                    $image_url = CMedia::getImage(
                        $logo,
                        $path,
                        '@thumbnail',
                        CommonUtility::getPlaceholderPhoto('merchant')
                    );

                    $data[] = array(
                        'transaction_uuid' => $item->transaction_uuid,
                        'restaurant_name' => Yii::app()->input->xssClean($restaurant_name),
                        'transaction_date' => Date_Formatter::dateTime($item->transaction_date),
                        'transaction_amount' => $item->transaction_amount,
                        'transaction_amount_pretty' => Price_Formatter::formatNumber($item->transaction_amount),
                        'status' => $item->status,
                        'status_class' => str_replace(" ", "_", $item->status),
                        'image_url' => $image_url
                    );
                }

                $this->code = 1;
                $this->msg = "ok";
                $this->details = $data;
            } else $this->msg = t("No recent payout request");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionReportsMerchantReg()
    {
        $data = array();
        $status_list = AttributesTools::StatusManagement('customer', Yii::app()->language);
        $merchant_type_list = AttributesTools::ListMerchantType(Yii::app()->language);

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  isset($this->data['order'][0]) ? $this->data['order'][0] : ''   : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';
        $filter = isset($this->data['filter']) ? $this->data['filter'] : '';

        $sortby = "date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;

        $criteria = new CDbCriteria();

        if (is_array($transaction_type) && count($transaction_type) >= 1) {
            $criteria->addInCondition('status', (array) $transaction_type);
        }
        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(date_created,'%Y-%m-%d')", $date_start, $date_end);
        }
        if (is_array($filter) && count($filter) >= 1) {
            $filter_merchant_id = isset($filter['merchant_id']) ? $filter['merchant_id'] : '';
            $criteria->addSearchCondition('merchant_id', $filter_merchant_id);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_merchant::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $models = AR_merchant::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {
                $avatar = CMedia::getImage(
                    $item->logo,
                    $item->path,
                    '@thumbnail',
                    CommonUtility::getPlaceholderPhoto('merchant')
                );

                $restaurant_name = Yii::app()->input->xssClean($item->restaurant_name);
                $status = $item->status;
                if (array_key_exists($item->status, (array)$status_list)) {
                    $status = $status_list[$item->status];
                }

                $merchant_type = $item->merchant_type;
                if (array_key_exists($item->merchant_type, (array)$merchant_type_list)) {
                    $merchant_type = $merchant_type_list[$item->merchant_type];
                }

                $view_merchant =  Yii::app()->createUrl('/vendor/edit', array(
                    'id' => $item->merchant_id
                ));

                $html_resto = <<<HTML
<p class="m-0">$restaurant_name</p>
<div class="badge customer $item->status">$status</div>
HTML;


                $data[] = array(
                    'logo' => '<a href="' . $view_merchant . '"><img class="img-60 rounded-circle" src="' . $avatar . '"></a>',
                    'restaurant_name' => $html_resto,
                    'address' => Yii::app()->input->xssClean($item->address),
                    'merchant_type' => $merchant_type,
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actionReportsMerchantSummary()
    {
        try {

            $total_registered = CReports::MerchantTotal(0);
            $commission_total = CReports::MerchantTotal(2, array('active'));
            $membership_total = CReports::MerchantTotal(1, array('active'));
            $total_active = CReports::MerchantTotal(0, array('active'));
            $total_inactive = CReports::MerchantTotal(0, array('pending', 'draft', 'expired'));

            $data = array(
                'total_registered' => $total_registered,
                'commission_total' => $commission_total,
                'membership_total' => $membership_total,
                'total_active' => $total_active,
                'total_inactive' => $total_inactive,
                'price_format' => AttributesTools::priceFormat()
            );

            $this->code = 1;
            $this->msg = "ok";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionreportsmerchantplan()
    {

        $payment_gateway = AttributesTools::PaymentProvider();
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  isset($this->data['order'][0]) ? $this->data['order'][0] : ''   : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';
        $filter = isset($this->data['filter']) ? $this->data['filter'] : '';

        $sortby = "created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.merchant_id, a.invoice_number,a.invoice_ref_number,a.created,a.amount,a.status,a.payment_code,
		b.title , c.restaurant_name , c.logo, c.path
		";
        $criteria->join = '
		LEFT JOIN {{plans_translation}} b on  a.package_id=b.package_id 
		LEFT JOIN {{merchant}} c on  a.merchant_id = c.merchant_id 
		';


        $params = array();
        $criteria->addCondition("b.language=:language and c.restaurant_name IS NOT NULL AND TRIM(c.restaurant_name) <> ''");
        $params['language'] = Yii::app()->language;

        if (is_array($filter) && count($filter) >= 1) {
            $filter_merchant_id = isset($filter['merchant_id']) ? $filter['merchant_id'] : '';
            $criteria->addCondition('a.merchant_id=:merchant_id');
            $params['merchant_id']  = intval($filter_merchant_id);
        }

        $criteria->params = $params;

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(a.created,'%Y-%m-%d')", $date_start, $date_end);
        }
        if (is_array($transaction_type) && count($transaction_type) >= 1) {
            $criteria->addInCondition('a.status', (array) $transaction_type);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_plans_invoice::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);
        $models = AR_plans_invoice::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {
                $avatar = CMedia::getImage(
                    $item->logo,
                    $item->path,
                    '@thumbnail',
                    CommonUtility::getPlaceholderPhoto('merchant')
                );

                $status = $item->status;
                $created = t("Created {{date}}", array(
                    '{{date}}' => Date_Formatter::dateTime($item->created)
                ));

                $plan_title = Yii::app()->input->xssClean($item->title);
                $amount = Price_Formatter::formatNumber($item->amount);


                $view_merchant =  Yii::app()->createUrl('/vendor/edit', array(
                    'id' => $item->merchant_id
                ));

                $invoice = <<<HTML
<p class="m-0">$item->invoice_ref_number</p>
<div class="badge customer $item->status payment">$status</div>
HTML;

                $plan = <<<HTML
<p class="m-0">$plan_title</p>
<p class="m-0 text-muted font11">$amount</p>
HTML;


                $data[] = array(
                    'logo' => '<a href="' . $view_merchant . '"><img class="img-60 rounded-circle" src="' . $avatar . '"></a>',
                    'created' => Date_Formatter::dateTime($item->created),
                    'merchant_id' => $item->restaurant_name,
                    'payment_code' => isset($payment_gateway[$item->payment_code]) ? $payment_gateway[$item->payment_code] : $item->payment_code,
                    'invoice_ref_number' => $invoice,
                    'package_id' => $plan,
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actionreportsorderearnings()
    {
        $data = array();
        $status_completed = AOrderSettings::getStatus(array('status_delivered', 'status_completed'));

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  isset($this->data['order'][0]) ? $this->data['order'][0] : ''   : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';
        $filter = isset($this->data['filter']) ? $this->data['filter'] : '';

        $sortby = "order_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;

        $criteria = new CDbCriteria();

        if (is_array($status_completed) && count($status_completed) >= 1) {
            $criteria->addInCondition('status', (array) $status_completed);
        }

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(date_created,'%Y-%m-%d')", $date_start, $date_end);
        }

        if (!empty($search)) {
            $criteria->addSearchCondition('order_id', $search);
        }

        $criteria->order = "$sortby $sort";

        $count = AR_ordernew::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $models = AR_ordernew::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {

                $view_order = Yii::app()->createUrl('order/view', array(
                    'order_uuid' => $item->order_uuid
                ));


                $data[] = array(
                    'order_id' => '<a href="' . $view_order . '">' . $item->order_id . "</a>",
                    'sub_total' => Price_Formatter::formatNumber($item->sub_total),
                    'total' => Price_Formatter::formatNumber($item->total),
                    'merchant_earning' => Price_Formatter::formatNumber($item->merchant_earning),
                    'commission' => Price_Formatter::formatNumber($item->commission),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actionreportsorderearningsummary()
    {
        try {

            $date_start = Yii::app()->input->post('date_start');
            $date_end = Yii::app()->input->post('date_end');
            $status_completed = AOrderSettings::getStatus(array('status_delivered', 'status_completed'));

            $total_count = CReports::EarningTotalCount($status_completed, $date_start, $date_end);
            $admin_earning = CReports::EarningByOrder('admin', $status_completed, $date_start, $date_end);
            $merchant_earning = CReports::EarningByOrder('merchant', $status_completed, $date_start, $date_end);
            $total_sell = CReports::EarningByOrder('sales', $status_completed, $date_start, $date_end);

            $data = array(
                'total_count' => $total_count,
                'admin_earning' => floatval($admin_earning),
                'merchant_earning' => floatval($merchant_earning),
                'total_sell' => floatval($total_sell),
                'price_format' => AttributesTools::priceFormat()
            );

            $this->code = 1;
            $this->msg = "ok";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionEmailLogs()
    {
        $data = array();
        $status_completed = AOrderSettings::getStatus(array('status_delivered', 'status_completed'));

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  isset($this->data['order'][0]) ? $this->data['order'][0] : ''   : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';
        $filter = isset($this->data['filter']) ? $this->data['filter'] : '';

        $sortby = "id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;

        $criteria = new CDbCriteria();

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(date_created,'%Y-%m-%d')", $date_start, $date_end);
        }

        if (!empty($search)) {
            $criteria->addSearchCondition('email_address', $search);
            $criteria->addSearchCondition('subject', $search, true, 'OR');
            $criteria->addSearchCondition('content', $search, true, 'OR');
        }

        $criteria->order = "$sortby $sort";

        $count = AR_email_logs::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);

        if ($length > 0) {
            $pages->applyLimit($criteria);
        }

        $models = AR_email_logs::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {

                $data[] = array(
                    'date_created' => $item->date_created,
                    'email_address' => $item->email_address,
                    'subject' => '<div class="text-truncate" style="max-width:150px;">' . Yii::app()->input->purify($item->subject) . '</div>',
                    'sms_message' => '<div class="text-truncate" style="max-width:150px;">' . Yii::app()->input->purify($item->subject) . '</div>',
                    'status' => $item->status,
                    'delete_url' => Yii::app()->createUrl("/notifications/delete_email/", array('id' => $item->id)),
                    'view_id' => $item->id,
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actionMerchantPaymentPlans()
    {

        $data = array();
        $payment_gateway = AttributesTools::PaymentProvider();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  isset($this->data['order'][0]) ? $this->data['order'][0] : ''   : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';
        $filter = isset($this->data['filter']) ? $this->data['filter'] : '';
        $merchant_id = isset($this->data['ref_id']) ? $this->data['ref_id'] : 0;

        $sortby = "created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.merchant_id, a.invoice_number,a.invoice_ref_number,a.created,a.amount,a.status,
		a.payment_code,
		b.title , c.restaurant_name , c.logo, c.path
		";
        $criteria->join = '
		LEFT JOIN {{plans_translation}} b on  a.package_id=b.package_id 
		LEFT JOIN {{merchant}} c on  a.merchant_id = c.merchant_id 
		';

        $params = array();
        $criteria->addCondition("b.language=:language and c.restaurant_name IS NOT NULL AND TRIM(c.restaurant_name) <> ''");
        $params['language'] = Yii::app()->language;

        $criteria->addCondition('a.merchant_id=:merchant_id');
        $params['merchant_id']  = intval($merchant_id);

        $criteria->params = $params;

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(a.created,'%Y-%m-%d')", $date_start, $date_end);
        }
        if (is_array($transaction_type) && count($transaction_type) >= 1) {
            $criteria->addInCondition('a.status', (array) $transaction_type);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_plans_invoice::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);
        $models = AR_plans_invoice::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {
                $avatar = CMedia::getImage(
                    $item->logo,
                    $item->path,
                    '@thumbnail',
                    CommonUtility::getPlaceholderPhoto('merchant')
                );

                $status = $item->status;
                $created = t("Created {{date}}", array(
                    '{{date}}' => Date_Formatter::dateTime($item->created)
                ));

                $plan_title = Yii::app()->input->xssClean($item->title);
                $amount = Price_Formatter::formatNumber($item->amount);


                $view_merchant =  Yii::app()->createUrl('/vendor/edit', array(
                    'id' => $item->merchant_id
                ));

                $invoice = <<<HTML
<p class="m-0">$item->invoice_ref_number</p>
<div class="badge customer $item->status payment">$status</div>
HTML;

                $plan = <<<HTML
<p class="m-0">$plan_title</p>
<p class="m-0 text-muted font11">$amount</p>
HTML;


                $data[] = array(
                    'logo' => '<a href="' . $view_merchant . '"><img class="img-60 rounded-circle" src="' . $avatar . '"></a>',
                    'created' => Date_Formatter::dateTime($item->created),
                    'payment_code' => isset($payment_gateway[$item->payment_code]) ? $payment_gateway[$item->payment_code] : $item->payment_code,
                    'invoice_ref_number' => $invoice,
                    'package_id' => $plan,
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actionsmslogs()
    {
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  isset($this->data['order'][0]) ? $this->data['order'][0] : ''   : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';

        $sortby = "date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(date_created,'%Y-%m-%d')", $date_start, $date_end);
        }

        if (!empty($search)) {
            $criteria->addSearchCondition('contact_phone', $search);
            $criteria->addSearchCondition('sms_message', $search, true, 'OR');
            $criteria->addSearchCondition('status', $search, true, 'OR');
        }

        $criteria->order = "$sortby $sort";

        $count = AR_sms_broadcast_details::model()->count($criteria);

        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        if ($length > 0) {
            $pages->applyLimit($criteria);
        }
        $models = AR_sms_broadcast_details::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {
                $data[] = array(
                    'date_created' => Date_Formatter::dateTime($item->date_created),
                    'gateway' => $item->gateway,
                    'contact_phone' => $item->contact_phone,
                    'sms_message' => '<div class="text-truncate" style="max-width:150px;">' . Yii::app()->input->purify($item->sms_message) . '</div>',
                    'status' => $item->status,
                    'delete_url' => Yii::app()->createUrl("/sms/delete/", array('id' => $item->id)),
                    'view_id' => $item->id,
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actiongetSMS()
    {
        try {

            $view_id = Yii::app()->input->post('view_id');
            $model = AR_sms_broadcast_details::model()->find("id=:id", array(
                ':id' => intval($view_id)
            ));
            if ($model) {
                $data = array(
                    'content' => Yii::app()->input->purify($model->sms_message),
                    'type' => "sms"
                );

                $this->code = 1;
                $this->msg = "ok";
                $this->details = $data;
            } else $this->msg = t(HELPER_RECORD_NOT_FOUND);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetemail()
    {
        try {

            $data = array();
            $view_id = Yii::app()->input->post('view_id');
            $model = AR_email_logs::model()->find("id=:id", array(
                ':id' => intval($view_id)
            ));
            if ($model) {
                $data = array(
                    'content' => Yii::app()->input->purify($model->content),
                    'type' => "email"
                );
                $this->code = 1;
                $this->msg = "ok";
                $this->details = $data;
            } else $this->msg = t(HELPER_RECORD_NOT_FOUND);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetpush()
    {
        try {

            $data = array();
            $view_id = Yii::app()->input->post('view_id');
            $model = AR_push::model()->find("push_uuid=:push_uuid", array(
                ':push_uuid' => $view_id
            ));
            if ($model) {
                $data = array(
                    'content' => Yii::app()->input->purify($model->body),
                    'type' => "sms"
                );
                $this->code = 1;
                $this->msg = "ok";
                $this->details = $data;
            } else $this->msg = t(HELPER_RECORD_NOT_FOUND);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionrefundreports()
    {

        $status = COrders::statusList(Yii::app()->language);
        $payment_list = AttributesTools::PaymentProvider();
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  isset($this->data['order'][0]) ? $this->data['order'][0] : ''   : '';
        $filter = isset($this->data['filter']) ? $this->data['filter'] : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';

        $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';

        $sortby = "a.date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }


        if ($page > 0) {
            $page = intval($page) / intval($length);
        }
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.client_id,a.order_id,a.merchant_id,a.transaction_description,a.payment_code,
	    a.trans_amount, a.status, a.payment_reference, a.date_created,
	    b.logo as photo, b.path,
	    c.order_uuid
	    ";
        $criteria->join = '
	    LEFT JOIN {{merchant}} b on  a.merchant_id = b.merchant_id
	    LEFT JOIN {{ordernew}} c on  a.order_id = c.order_id
	    ';

        $criteria->addInCondition('a.transaction_name', array('refund', 'partial_refund'));
        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(a.date_created,'%Y-%m-%d')", $date_start, $date_end);
        }
        if (is_array($transaction_type) && count($transaction_type) >= 1) {
            $criteria->addInCondition('a.status', (array) $transaction_type);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_ordernew_transaction::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        if ($model = AR_ordernew_transaction::model()->findAll($criteria)) {
            foreach ($model as $item) {

                $avatar = CMedia::getImage(
                    $item->photo,
                    $item->path,
                    '@thumbnail',
                    CommonUtility::getPlaceholderPhoto('customer')
                );
                $date = t("Refund on {{date}}", array(
                    '{{date}}' => Date_Formatter::dateTime($item->date_created)
                ));
                $status_class = CommonUtility::removeSpace($item->status);
                $status_trans = $item->status;
                if (array_key_exists($item->status, (array) $status)) {
                    $status_trans = $status[$item->status]['status'];
                }
                $transaction_description = t(Yii::app()->input->xssClean($item->transaction_description));
                $reference = t("Payment reference# {{payment_reference}}", array(
                    '{{payment_reference}}' => $item->payment_reference
                ));

                $view_order = Yii::app()->createUrl('order/view', array(
                    'order_uuid' => $item->order_uuid
                ));

                $information = <<<HTML
$transaction_description<span class="ml-2 badge payment $status_class">$status_trans</span>
<p class="font12 dim m-0">$date</p>
<p class="font12 dim m-0">$reference</p>
HTML;

                $data[] = array(
                    'date_created' => $item->date_created,
                    'merchant_id' => '<img class="img-60 rounded-circle" src="' . $avatar . '">',
                    'order_id' => '<a href="' . $view_order . '">' . $item->order_id . '</a>',
                    'transaction_description' => $information,
                    'payment_code' => isset($payment_list[$item->payment_code]) ? $payment_list[$item->payment_code] : $item->payment_code,
                    'trans_amount' => Price_Formatter::formatNumber($item->trans_amount),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actionAllPages()
    {
        try {

            $data = PPages::all(Yii::app()->language);
            $this->code = 1;
            $this->msg = "ok";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actioncreateMenu()
    {
        try {

            $menu_name = isset($this->data['menu_name']) ? $this->data['menu_name'] : '';
            $menu_id = isset($this->data['menu_id']) ? intval($this->data['menu_id']) : 0;
            $child_menu = isset($this->data['child_menu']) ? $this->data['child_menu'] : '';

            if ($menu_id > 0) {
                $model = MMenu::get($menu_id, PPages::menuType());
            } else $model = new AR_menu();

            $model->scenario = "theme_menu";

            $model->menu_type = PPages::menuType();
            $model->menu_name = $menu_name;
            $model->child_menu = $child_menu;
            if ($model->save()) {
                $this->code = 1;
                $this->msg = t("Succesful");
            } else $this->msg = CommonUtility::parseModelErrorToString($model->getErrors(), "<br/>");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionsortMenu()
    {
        try {

            $menu = isset($this->data['menu']) ? $this->data['menu'] : '';
            if (is_array($menu) && count($menu) >= 1) {
                foreach ($menu as $index => $item) {
                    if ($model = MMenu::get($item['menu_id'], PPages::menuType())) {
                        $model->sequence = intval($index);
                        $model->save();
                    }
                }
                $this->code = 1;
                $this->msg = t("Sort menu saved");
            } else $this->msg = t("Invalid data");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionMenuList()
    {
        try {

            $data = array();
            try {
                $data = MMenu::getMenu(0, PPages::menuType());
            } catch (Exception $e) {
                //
            }

            $current_menu = AR_admin_meta::getValue(PPages::menuActiveKey());
            $current_menu = isset($current_menu['meta_value']) ? $current_menu['meta_value'] : 0;

            $this->code = 1;
            $this->msg = "ok";
            $this->details = array(
                'data' => $data,
                'current_menu' => intval($current_menu)
            );
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetMenuDetails()
    {
        try {

            $current_menu = Yii::app()->input->post('current_menu');
            $model = AR_menu::model()->findByPk(intval($current_menu));
            if ($model) {

                $data = array();
                try {
                    $data = MMenu::getMenu($current_menu, PPages::menuType());
                } catch (Exception $e) {
                    //
                }

                $this->code = 1;
                $this->msg = "ok";
                $this->details = array(
                    'menu_name' => $model->menu_name,
                    'sequence' => $model->sequence,
                    'data' => $data
                );
            } else $this->msg = t(Helper_not_found);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiondeletemenu()
    {
        try {

            $menu_id = intval(Yii::app()->input->post('menu_id'));

            $model = AR_menu::model()->find("menu_id=:menu_id AND menu_type=:menu_type", array(
                ':menu_id' => intval($menu_id),
                ':menu_type' => PPages::menuType()
            ));

            if ($model) {
                $model->scenario = "theme_menu";
                $model->delete();
                $this->code = 1;
                $this->msg = t(Helper_success);
            } else $this->msg = t(Helper_not_found);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionaddpagetomenu()
    {
        try {

            $menu_id = isset($this->data['menu_id']) ? intval($this->data['menu_id']) : 0;
            $pages = isset($this->data['pages']) ? $this->data['pages'] : array();
            if (is_array($pages) && count($pages) >= 1) {
                foreach ($pages as $page_id) {
                    $page = PPages::get($page_id);

                    $model = new AR_menu();
                    $model->menu_type = PPages::menuType();
                    $model->menu_name = $page->title;
                    $model->parent_id = $menu_id;
                    $model->link = '{{site_url}}/' . $page->slug;
                    $model->save();
                }
            }

            $this->code = 1;
            $this->msg = t(Helper_success);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionaddCustomPageToMenu()
    {
        try {

            $menu_id = isset($this->data['menu_id']) ? intval($this->data['menu_id']) : 0;
            $custom_link_text = isset($this->data['custom_link_text']) ? trim($this->data['custom_link_text']) : '';
            $custom_link = isset($this->data['custom_link']) ? trim($this->data['custom_link']) : '';

            $model = new AR_menu();
            $model->scenario = "custom_link";
            $model->menu_type = PPages::menuType();
            $model->menu_name = $custom_link_text;
            $model->parent_id = $menu_id;
            $model->link = $custom_link;

            if ($model->save()) {
                $this->code = 1;
                $this->msg = t(Helper_success);
            } else $this->msg = CommonUtility::parseModelErrorToString($model->getErrors(), "<br/>");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionremoveChildMenu()
    {
        try {

            $menu_id = intval(Yii::app()->input->post('menu_id'));
            $model = MMenu::get($menu_id, PPages::menuType());
            if ($model) {
                $model->delete();
                $this->code = 1;
                $this->msg = "ok";
            } else $this->msg = t(Helper_not_found);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetAddons()
    {
        try {

            $data = array();
            $model = AR_addons::model()->findAll();
            if ($model) {
                foreach ($model as $key => $items) {
                    $data[] = [
                        'id' => $items->id,
                        'uuid' => $items->uuid,
                        'addon_name' => CHtml::encode($items->addon_name),
                        'version' => t("Version {{version}}", ['{{version}}' => $items->version]),
                        'image' => CMedia::getImage($items->image, $items->path),
                        'activated' => $items->activated == 1 ? true : false
                    ];
                }
                $this->code = 1;
                $this->msg = "ok";
                $this->details = ['data' => $data];
            } else $this->msg = t("No results");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionenableddisabledaddon()
    {

        if (DEMO_MODE) {
            $this->msg = t("This action is not available in demo");
            $this->responseJson();
        }

        try {
            $uuid = isset($this->data['uuid']) ? $this->data['uuid'] : '';
            $activated = isset($this->data['activated']) ? $this->data['activated'] : 0;
            $model = AR_addons::model()->find("uuid=:uuid", [':uuid' => $uuid]);
            if ($model) {
                $model->activated = intval($activated);
                $model->save();
                $this->code = 1;
                $this->msg = $model->activated == 1 ? t("Addon activated") : t("Addon de-activated");
                $this->details = ['title' => t("Successful")];
            } else {
                $this->details = ['title' => t("Failed")];
                $this->msg = t("Record not found");
            }
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
            $this->details = ['title' => t("Failed")];
        }
        $this->responseJson();
    }

    public function actionbannerList()
    {
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  isset($this->data['order'][0]) ? $this->data['order'][0] : ''   : '';

        $sortby = "date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();
        $criteria->condition = "owner=:owner";
        $criteria->params = array(':owner' => 'admin');

        if (!empty($search)) {
            $criteria->addSearchCondition('title', $search);
            $criteria->addSearchCondition('banner_type', $search);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_banner::model()->count($criteria);

        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        if ($length > 0) {
            $pages->applyLimit($criteria);
        }

        $models = AR_banner::model()->findAll($criteria);
        if ($models) {

            $banner_type_list = AttributesTools::BannerType2();

            foreach ($models as $item) {

                $photo = CMedia::getImage(
                    $item->photo,
                    $item->path,
                    '@thumbnail',
                    CommonUtility::getPlaceholderPhoto('customer')
                );

                $checkbox = Yii::app()->controller->renderPartial('/attributes/html_checkbox', array(
                    'id' => "banner[$item->banner_uuid]",
                    'check' => $item->status == 1 ? true : false,
                    'value' => $item->banner_uuid,
                    'label' => '',
                    'class' => 'set_banner_status'
                ), true);

                $data[] = array(
                    'banner_id' => $item->banner_id,
                    'photo' => '<img class="img-60" src="' . $photo . '">',
                    'status' => $checkbox,
                    'title' => $item->title,
                    'banner_type' => isset($banner_type_list[$item->banner_type]) ? $banner_type_list[$item->banner_type] : $item->banner_type,
                    'date_created' => Date_Formatter::dateTime($item->date_created),
                    'update_url' => Yii::app()->createUrl("/marketing/banner_update/", array('id' => $item->banner_uuid)),
                    'delete_url' => Yii::app()->createUrl("/marketing/banner_delete/", array('id' => $item->banner_uuid)),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actionpushList()
    {
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  isset($this->data['order'][0]) ? $this->data['order'][0] : ''   : '';

        $sortby = "date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();
        $criteria->condition = "provider=:provider";
        $criteria->params = array(':provider' => 'firebase');

        if (!empty($search)) {
            $criteria->addSearchCondition('title', $search);
            $criteria->addSearchCondition('body', $search);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_push::model()->count($criteria);

        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);

        if ($length > 0) {
            $pages->applyLimit($criteria);
        }

        $models = AR_push::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {

                $photo = CMedia::getImage(
                    $item->image,
                    $item->path,
                    '@thumbnail',
                    CommonUtility::getPlaceholderPhoto('customer')
                );

                $platform =  $item->platform == 'android' ? '<div class="badge badge-info">' . t($item->platform) . '</div>' : '<div class="badge badge-warning">' . t($item->platform) . '</div>';

                $data[] = array(
                    'push_uuid' => $item->push_uuid,
                    'title' => $item->title,
                    'body' => $item->body,
                    'image' => !empty($item->image) ? '<img class="img-60" src="' . $photo . '">' : '<span class="badge badge-warning">' . t("No image") . '</span>',
                    'channel_device_id' => '<div class="d-inline-block text-truncate" style="max-width: 150px;">' . $item->channel_device_id . '</div>' . $platform,
                    'status' => $item->status == "process" ? '<span class="badge badge-success">' . $item->status . '</span>' : '<span class="badge badge-primary">' . $item->status . '</span>',
                    'date_created' => Date_Formatter::dateTime($item->date_created),
                    'update_url' => Yii::app()->createUrl("/marketing/notification_update/", array('id' => $item->push_uuid)),
                    'delete_url' => Yii::app()->createUrl("/marketing/notification_delete/", array('id' => $item->push_uuid)),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actiondriverList()
    {
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';

        $sortby = "date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();

        $criteria->condition = "merchant_id=:merchant_id";
        $criteria->params = [
            ':merchant_id' => 0
        ];

        if (!empty($search)) {
            $criteria->addSearchCondition('first_name', $search);
            // $criteria->addSearchCondition('last_name', $search , true, "OR" );
            // $criteria->addSearchCondition('email', $search , true, "OR"  );
        }

        $criteria->order = "$sortby $sort";
        $count = AR_driver::model()->count($criteria);

        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $models = AR_driver::model()->findAll($criteria);

        $employment_list = AttributesTools::DriverEmploymentType();

        if ($models) {
            foreach ($models as $item) {

                $photo = CMedia::getImage(
                    $item->photo,
                    $item->path,
                    '@thumbnail',
                    CommonUtility::getPlaceholderPhoto('customer')
                );

                $data[] = array(
                    'driver_uuid' => $item->driver_uuid,
                    'date_created' => $item->date_created,
                    'first_name' => '<div class="row"><div class="col-4"><img src="' . $photo . '" class="img-60 rounded-circle" /></div><div class="col">' .
                        $item->first_name . " " . $item->last_name . "<p>" . t("ID") . "# $item->driver_id</p>" . '</div></div>',
                    'email' => $item->email,
                    'phone' => $item->phone_prefix . $item->phone,
                    'employment_type' => isset($employment_list[$item->employment_type]) ? $employment_list[$item->employment_type] : $item->employment_type,
                    'status' => '<span class="badge ml-2 customer ' . $item->status . '">' . t($item->status) . '</span>',
                    'view_url' => Yii::app()->createUrl("/driver/overview/", array('id' => $item->driver_uuid)),
                    'update_url' => Yii::app()->createUrl("/driver/update/", array('id' => $item->driver_uuid)),
                    'delete_url' => Yii::app()->createUrl("/driver/delete/", array('id' => $item->driver_uuid)),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actioncarList()
    {
        $data = array();

        $vehicle_maker = CommonUtility::getDataToDropDown("{{admin_meta}}", "meta_id", 'meta_value', "WHERE meta_name='vehicle_maker'", "Order by meta_name");
        $vehicle_type = CommonUtility::getDataToDropDown("{{admin_meta}}", "meta_id", 'meta_value', "WHERE meta_name='vehicle_type'", "Order by meta_name");

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';

        $sortby = "date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();
        $criteria->addCondition("driver_id=0 AND merchant_id=0");

        if (!empty($search)) {
            $criteria->addSearchCondition('plate_number', $search);
            $criteria->addSearchCondition('color', $search, true, 'OR');
        }

        $criteria->order = "$sortby $sort";
        $count = AR_driver_vehicle::model()->count($criteria);

        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $models = AR_driver_vehicle::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {

                $checkbox = Yii::app()->controller->renderPartial('/attributes/html_checkbox', array(
                    'id' => "banner[$item->vehicle_uuid]",
                    'check' => $item->active == 1 ? true : false,
                    'value' => $item->vehicle_uuid,
                    'label' => '',
                    'class' => 'set_status'
                ), true);

                $photo = CMedia::getImage(
                    $item->photo,
                    $item->path,
                    '@thumbnail',
                    CommonUtility::getPlaceholderPhoto('car', 'car.png')
                );

                $data[] = array(
                    'vehicle_uuid' => $item->vehicle_uuid,
                    'vehicle_id' => $item->vehicle_id,
                    'active' => $checkbox,
                    'plate_number' => '<div class="row"><div class="col-4"><img src="' . $photo . '" class="img-50 rounded-circle" /></div><div class="col">' .
                        $item->plate_number . "<p>" . t("ID") . "# $item->vehicle_id</p>" . '</div></div>',
                    'vehicle_type_id' => isset($vehicle_type[$item->vehicle_type_id]) ? $vehicle_type[$item->vehicle_type_id] : '',
                    'maker' => isset($vehicle_maker[$item->maker]) ? $vehicle_maker[$item->maker] : '',
                    'update_url' => Yii::app()->createUrl("/driver/update_car/", array('id' => $item->vehicle_uuid)),
                    'delete_url' => Yii::app()->createUrl("/driver/delete_car/", array('id' => $item->vehicle_uuid)),
                    'id' => $item->vehicle_uuid,
                    'actions' => "set_car_status"
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actionset_car_status()
    {
        try {

            $id = Yii::app()->input->post('id');
            $status = Yii::app()->input->post('status');
            $model = AR_driver_vehicle::model()->find("vehicle_uuid=:vehicle_uuid", ['vehicle_uuid' => $id]);
            if ($model) {
                $model->active = $status == "active" ? 1 : 0;
                if ($model->save()) {
                    $this->code = 1;
                    $this->msg = "ok";
                } else $this->msg = t(Helper_failed_update);
            } else $this->msg = t(HELPER_RECORD_NOT_FOUND);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionsearchDriver()
    {
        try {

            $data = [];
            $search = isset($this->data['search']) ? $this->data['search'] : '';
            $merchant_id = isset($this->data['merchant_id']) ? $this->data['merchant_id'] : 0;
            $employment_type = isset($this->data['employment_type']) ? $this->data['employment_type'] : '';

            $query = 'status=:status AND merchant_id=:merchant_id';
            $criteria = new CDbCriteria();
            if (!empty($employment_type)) {
                $query .= " ";
                $query .= "AND employment_type=:employment_type";
            }
            $criteria->addCondition($query);
            if (!empty($employment_type)) {
                $criteria->params = array(
                    ':status' => 'active',
                    ':merchant_id' => intval($merchant_id),
                    ':employment_type' => trim($employment_type),
                );
            } else {
                $criteria->params = array(
                    ':status' => 'active',
                    ':merchant_id' => intval($merchant_id)
                );
            }

            if (!empty($search)) {
                $criteria->addSearchCondition('first_name', $search);
                $criteria->addSearchCondition('last_name', $search, true, 'OR');
            }

            $criteria->order = "first_name ASC";
            $criteria->limit = 10;

            if ($model = AR_driver::model()->findAll($criteria)) {
                foreach ($model as $item) {
                    $data[] = [
                        'id' => $item->driver_id,
                        'text' => "$item->first_name $item->last_name"
                    ];
                }
            }

            $result = array(
                'results' => $data
            );
            $this->responseSelect2($result);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
    }

    public function actionsearchCar()
    {
        try {

            $data = [];
            $search = isset($this->data['search']) ? $this->data['search'] : '';

            $criteria = new CDbCriteria();
            //$criteria->addCondition("driver_id=0");
            $criteria->addCondition("driver_id=0 AND merchant_id=0");
            if (!empty($search)) {
                $criteria->addSearchCondition('plate_number', $search);
                $criteria->addSearchCondition('maker', $search, true, 'OR');
            }

            $criteria->order = "plate_number ASC";
            $criteria->limit = 10;

            if ($model = AR_driver_vehicle::model()->findAll($criteria)) {
                foreach ($model as $item) {
                    $data[] = [
                        'id' => $item->vehicle_id,
                        'text' => "$item->plate_number"
                    ];
                }
            }

            $result = array(
                'results' => $data
            );
            $this->responseSelect2($result);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
    }

    public function actionaddSchedule()
    {
        try {

            $schedule_uuid = isset($this->data['schedule_uuid']) ? trim($this->data['schedule_uuid']) : null;
            $zone_id = isset($this->data['zone_id']) ? intval($this->data['zone_id']) : 0;
            $driver_id = isset($this->data['driver_id']) ? intval($this->data['driver_id']) : 0;
            $vehicle_id = isset($this->data['vehicle_id']) ? intval($this->data['vehicle_id']) : 0;
            $date_start = isset($this->data['date_start']) ? date("Y-m-d", strtotime($this->data['date_start'])) : null;
            $time_start = isset($this->data['time_start']) ? $this->data['time_start'] : null;
            $time_end = isset($this->data['time_end']) ? $this->data['time_end'] : null;
            $instructions = isset($this->data['instructions']) ? $this->data['instructions'] : '';

            $model = new AR_driver_schedule;
            if (!empty($schedule_uuid)) {
                $model = AR_driver_schedule::model()->find("schedule_uuid=:schedule_uuid", [
                    ':schedule_uuid' => $schedule_uuid
                ]);
                if (!$model) {
                    $this->msg = t(HELPER_RECORD_NOT_FOUND);
                    $this->responseJson();
                }
            }
            $model->zone_id = $zone_id;
            $model->driver_id  = $driver_id;
            $model->vehicle_id  = $vehicle_id;
            $model->time_start  = "$date_start $time_start";
            $model->time_end  = "$date_start $time_end";
            $model->instructions = $instructions;
            if ($model->save()) {
                $this->code = 1;
                $this->msg = !empty($schedule_uuid) ? t("Schedule updated") :   t("Schedule added");
            } else $this->msg = CommonUtility::parseModelErrorToString($model->getErrors());
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetDriverSched()
    {
        try {

            $data =  [];
            $start = isset($this->data['start']) ? date("Y-m-d", strtotime($this->data['start'])) : null;
            $end = isset($this->data['end']) ? date("Y-m-d", strtotime($this->data['end'])) : null;

            $zone_list = CommonUtility::getDataToDropDown("{{zones}}", 'zone_id', 'zone_name', "
		    WHERE merchant_id = 0", "ORDER BY zone_name ASC");

            $criteria = new CDbCriteria();
            $criteria->select = "a.*,
			(
				select concat(first_name,' ',last_name,'|',color_hex,'|',photo,'|',path)
				from {{driver}}
				where driver_id = a.driver_id
			) as fullname,
			(
				select plate_number
				from {{driver_vehicle}}
				where vehicle_id = a.vehicle_id
			) as plate_number			
			";
            $criteria->alias = "a";
            $criteria->addCondition("active=:active AND merchant_id=:merchant_id
			AND a.driver_id IN (
				select driver_id from {{driver}}
				where employment_type='employee'
			)
			");
            $criteria->params = array(
                ':active' => 1,
                ':merchant_id' => 0
            );
            $criteria->addBetweenCondition('DATE(time_start)', $start, $end);
            $criteria->order = "time_start ASC";
            $criteria->limit = 500;

            if ($model = AR_driver_schedule::model()->findAll($criteria)) {
                foreach ($model as $item) {
                    $fulldata = explode("|", $item->fullname);
                    $fullname = isset($fulldata[0]) ? $fulldata[0] : '';
                    $color_hex = isset($fulldata[1]) ? $fulldata[1] : '';
                    $photo = isset($fulldata[2]) ? $fulldata[2] : '';
                    $path = isset($fulldata[3]) ? $fulldata[3] : '';
                    $avatar = CMedia::getImage($photo, $path, '@thumbnail', CommonUtility::getPlaceholderPhoto('driver'));
                    $data[] = [
                        'id' => $item->schedule_uuid,
                        'title' => "$fullname ($item->plate_number)",
                        'start' => date("c", strtotime($item->time_start)),
                        'end' => date("c", strtotime($item->time_end)),
                        'color' => $color_hex,
                        'extendedProps' => [
                            'name' => $fullname,
                            'plate_number' => $item->plate_number,
                            'time' => Date_Formatter::Time($item->time_start) . " - " . Date_Formatter::Time($item->time_end),
                            'avatar' => $avatar,
                            'zone_name' => $zone_list[$item->zone_id] ?? ''
                        ]
                    ];
                }
            }

            $this->responseSelect2($data);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
            dump($this->msg);
        }
    }

    public function actiongetSchedule()
    {
        try {

            $schedule_uuid	= isset($this->data['schedule_uuid']) ? $this->data['schedule_uuid'] : null;
            $model = AR_driver_schedule::model()->find("schedule_uuid	=:schedule_uuid", array(
                ':schedule_uuid' => $schedule_uuid
            ));
            if ($model) {

                $driver = [];
                $car = [];
                try {
                    $drivers = CDriver::getDriver($model->driver_id);
                    $driver = [
                        'id' => $drivers->driver_id,
                        'text' => "$drivers->first_name $drivers->last_name"
                    ];
                } catch (Exception $e) {
                    //
                }

                try {
                    $cars = CDriver::getVehicle($model->vehicle_id);
                    $car = [
                        'id' => $cars->vehicle_id,
                        'text' => "$cars->plate_number"
                    ];
                } catch (Exception $e) {
                    //
                }


                $zone_list =  [];
                try {
                    $zone = CDriver::getZone($model->zone_id);
                    $zone_list = [
                        'label' => $zone->zone_name,
                        'value' => $zone->zone_id,
                    ];
                } catch (Exception $e) {
                }

                $this->code = 1;
                $this->msg = "ok";
                $data['sched'] = [
                    'schedule_uuid' => $model->schedule_uuid,
                    'driver_id' => $model->driver_id,
                    'vehicle_id' => $model->vehicle_id,
                    'zone_id' => $zone_list,
                    'date_start' => Date_Formatter::date($model->time_start, "yyyy-MM-dd", true),
                    'time_start' => Date_Formatter::Time($model->time_start, "HH:mm", true),
                    'time_end' => Date_Formatter::Time($model->time_end, "HH:mm", true),
                    'instructions' => $model->instructions
                ];
                $data['driver'] = $driver;
                $data['car'] = $car;
                $this->details = $data;
            } else $this->msg = t(HELPER_RECORD_NOT_FOUND);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiondeleteschedule()
    {
        try {

            $schedule_uuid	= isset($this->data['schedule_uuid']) ? $this->data['schedule_uuid'] : null;
            $model = AR_driver_schedule::model()->find("schedule_uuid	=:schedule_uuid", array(
                ':schedule_uuid' => $schedule_uuid
            ));
            if ($model) {
                if ($model->delete()) {
                    $this->code = 1;
                    $this->msg = t("Schedule deleted");
                } else $this->msg = CommonUtility::parseModelErrorToString($model->getErrors(), "<br/>");
            } else $this->msg = t(HELPER_RECORD_NOT_FOUND);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiondriverReviewList()
    {
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';
        $ref_id = isset($this->data['ref_id']) ? $this->data['ref_id'] : '';

        $sortby = "date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.*,
		(
			select concat(first_name,' ',last_name)
			from {{client}}
			where client_id = a.client_id
			limit 0,1
		) as customer_fullname,

		(
			select concat(first_name,' ',last_name,'|',driver_uuid)
			from {{driver}}
			where driver_id = a.driver_id
			limit 0,1
		) as driver_fullname
		";

        if (!empty($ref_id)) {
            try {
                $driver_data = CDriver::getDriverByUUID($ref_id);
                $criteria->addCondition('a.driver_id=:driver_id');
                $criteria->params = array(':driver_id' => $driver_data->driver_id);
            } catch (Exception $e) {
                //
            }
        } else $criteria->addCondition('a.driver_id>0');

        if (!empty($search)) {
            $criteria->addSearchCondition('a.review', $search);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_review::model()->count($criteria);

        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $models = AR_review::model()->findAll($criteria);

        if ($models) {
            foreach ($models as $item) {
                $driver_data = explode("|", $item->driver_fullname);
                $driver_name = isset($driver_data[0]) ? $driver_data[0] : '';
                $driver_uuid = isset($driver_data[1]) ? $driver_data[1] : '';
                $data[] = array(
                    'id' => $item->id,
                    'driver_id' => CHtml::link($driver_name, $this->createAbsoluteUrl('driver/update', array('id' => $driver_uuid))),
                    'client_id' => CHtml::link($item->customer_fullname, $this->createAbsoluteUrl('buyer/customer_update', array('id' => $item->client_id))),
                    'review' => t('<h6>[review] <span class="badge ml-2 post [status]">[status_title]</span></h6>', [
                        '[review]' => $item->review,
                        '[status]' => $item->status,
                        '[status_title]' => t($item->status),
                    ]),
                    'rating' => '<label class="badge btn-green">' . $item->rating . ' <i class="zmdi zmdi-star"></i> </label>',
                    'update_url' => Yii::app()->createUrl("/driver/review_update/", array('id' => $item->id)),
                    'delete_url' => Yii::app()->createUrl("/driver/review_delete/", array('id' => $item->id)),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actiondriverOrderTransaction()
    {

        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ? $this->data['order'][0]  : '';
        $ref_id = isset($this->data['ref_id']) ? $this->data['ref_id'] : '';

        $sortby = "date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.*,
		(
			select concat(first_name,' ',last_name)
			from {{client}}
			where client_id = a.client_id
			limit 0,1
		) as customer_name,

		(
			select restaurant_name
			from {{merchant}}
			where merchant_id = a.merchant_id
			limit 0,1
		) as restaurant_name	
		";

        try {
            $driver_data = CDriver::getDriverByUUID($ref_id);
            $criteria->addCondition('a.driver_id=:driver_id');
            $criteria->params = array(':driver_id' => $driver_data->driver_id);
        } catch (Exception $e) {
            //
        }

        if (!empty($search)) {
            $criteria->addSearchCondition('a.order_id', intval($search));
            $criteria->addSearchCondition('a.merchant_id', intval($search), true, 'OR');
        }

        $criteria->order = "$sortby $sort";
        $count = AR_ordernew::model()->count($criteria);

        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $models = AR_ordernew::model()->findAll($criteria);

        if ($models) {
            foreach ($models as $item) {
                $data[] = array(
                    'order_id' => CHtml::link($item->order_id, $this->createAbsoluteUrl('order/view', array('order_uuid' => $item->order_uuid))),
                    'merchant_id' => CHtml::link($item->restaurant_name, $this->createAbsoluteUrl('vendor/edit', array('id' => $item->merchant_id))),
                    'client_id' => CHtml::link($item->customer_name, $this->createAbsoluteUrl('buyer/customer_update', array('id' => $item->client_id))),
                    'total' => Price_Formatter::formatNumber($item->total),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actiondriverTipsTransaction()
    {
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ? $this->data['order'][0]  : '';
        $ref_id = isset($this->data['ref_id']) ? $this->data['ref_id'] : '';

        $sortby = "date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.*,
		(
			select concat(first_name,' ',last_name)
			from {{client}}
			where client_id = a.client_id
			limit 0,1
		) as customer_name,

		(
			select restaurant_name
			from {{merchant}}
			where merchant_id = a.merchant_id
			limit 0,1
		) as restaurant_name	
		";

        try {
            $driver_data = CDriver::getDriverByUUID($ref_id);
            $criteria->addCondition('a.driver_id=:driver_id AND courier_tip>0');
            $criteria->params = array(':driver_id' => $driver_data->driver_id);
        } catch (Exception $e) {
            //
        }

        if (!empty($search)) {
            $criteria->addSearchCondition('a.order_id', intval($search));
            $criteria->addSearchCondition('a.merchant_id', intval($search), true, 'OR');
        }

        $criteria->order = "$sortby $sort";
        $count = AR_ordernew::model()->count($criteria);

        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $models = AR_ordernew::model()->findAll($criteria);

        if ($models) {
            foreach ($models as $item) {
                $data[] = array(
                    'order_id' => CHtml::link($item->order_id, $this->createAbsoluteUrl('order/view', array('order_uuid' => $item->order_uuid))),
                    'merchant_id' => CHtml::link($item->restaurant_name, $this->createAbsoluteUrl('vendor/edit', array('id' => $item->merchant_id))),
                    'client_id' => CHtml::link($item->customer_name, $this->createAbsoluteUrl('buyer/customer_update', array('id' => $item->client_id))),
                    'courier_tip' => Price_Formatter::formatNumber($item->courier_tip),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actiongetDriverOverview()
    {
        try {

            $driver_uuid = isset($this->data['driver_uuid']) ? $this->data['driver_uuid'] : '';
            $driver_data = CDriver::getDriverByUUID($driver_uuid);
            $driver_id = $driver_data->driver_id;
            $total = CReviews::reviewsCountDriver($driver_id);
            $review_summary = CReviews::summaryDriver($driver_id, $total);

            $tracking_stats = AR_admin_meta::getMeta(array(
                'tracking_status_delivered',
                'tracking_status_completed'
            ));
            $tracking_status_delivered = isset($tracking_stats['tracking_status_delivered']) ? AttributesTools::cleanString($tracking_stats['tracking_status_delivered']['meta_value']) : '';
            $tracking_status_completed = isset($tracking_stats['tracking_status_completed']) ? AttributesTools::cleanString($tracking_stats['tracking_status_completed']['meta_value']) : '';

            $total_delivered_percent = 0;
            $total_delivered = CDriver::CountOrderStatus($driver_id, $tracking_status_delivered);
            $total_assigned =  CDriver::SummaryCountOrderTotal($driver_id);
            if ($total_assigned > 0) {
                $total_delivered_percent = round(($total_delivered / $total_assigned) * 100);
            }

            $successful_status = array();
            if (!empty($tracking_status_delivered)) {
                $successful_status[] = $tracking_status_delivered;
            }
            if (!empty($tracking_status_completed)) {
                $successful_status[] = $tracking_status_completed;
            }

            $total_tip_percent = 0;
            $total_tip = CDriver::TotaLTips($driver_id, $successful_status);
            $summary_tip = CDriver::SummaryTotaLTips($driver_id);
            if ($summary_tip > 0) {
                $total_tip_percent = round(($total_tip / $summary_tip) * 100);
            }

            try {
                $card_id = CWallet::createCard(Yii::app()->params->account_type['driver'], $driver_id);
                $wallet_balance = CWallet::getBalance($card_id);
            } catch (Exception $e) {
                $this->msg = t($e->getMessage());
                $wallet_balance = 0;
            }

            $data = array(
                'total' => $total,
                'review_summary' => $review_summary,
                'total_delivered' => $total_delivered,
                'total_delivered_percent' => $total_delivered_percent,
                'total_tip' => Price_Formatter::formatNumber($total_tip),
                'total_tip_percent' => intval($total_tip_percent),
                'wallet_balance' => Price_Formatter::formatNumber($wallet_balance),
            );

            $this->code = 1;
            $this->msg = "ok";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetDriverActivity()
    {
        try {

            $driver_uuid = isset($this->data['driver_uuid']) ? $this->data['driver_uuid'] : '';
            $date_end = isset($this->data['date_start']) ? $this->data['date_start'] : date("Y-m-d");

            $model = CDriver::getDriverByUUID($driver_uuid);
            $driver_id = $model->driver_id;

            $date_start = date('Y-m-d', strtotime('-7 days'));
            $model = CDriver::getActivity($driver_id, $date_start, $date_end);
            if ($model) {
                $data = [];

                foreach ($model as $items) {
                    $args = !empty($items->remarks_args) ?  json_decode($items->remarks_args, true) : array();
                    $data[] = [
                        'created_at' => PrettyDateTime::parse(new DateTime($items->created_at)),
                        'order_id' => $items->order_id,
                        'remarks' => t($items->remarks, (array)$args),
                    ];
                }

                $this->code = 1;
                $this->msg = "OK";
                $this->details = [
                    'data' => $data
                ];
            } else $this->msg = t(HELPER_NO_RESULTS);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongroupList()
    {
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';
        $ref_id = isset($this->data['ref_id']) ? $this->data['ref_id'] : '';

        $sortby = "date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.*,
		(
		   select count(*) from {{driver_group_relations}}
		   where group_id = a.group_id
		   and driver_id IN (
			   select driver_id from {{driver}} where status='active'
		   )
		) as drivers
		";

        $criteria->condition = "merchant_id=0";

        if (!empty($search)) {
            $criteria->addSearchCondition('a.group_name', $search);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_driver_group::model()->count($criteria);

        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $models = AR_driver_group::model()->findAll($criteria);

        if ($models) {
            foreach ($models as $item) {
                $data[] = array(
                    'group_uuid' => $item->group_uuid,
                    'group_name' => $item->group_name,
                    'drivers' => $item->drivers,
                    'update_url' => Yii::app()->createUrl("/driver/group_update/", array('id' => $item->group_uuid)),
                    'delete_url' => Yii::app()->createUrl("/driver/group_delete/", array('id' => $item->group_uuid)),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actiongetgrouplist()
    {
        try {

            $data = CommonUtility::getDataToDropDown(
                "{{driver_group}}",
                "group_id",
                "group_name",
                "WHERE merchant_id=0",
                "order by group_name asc"
            );
            $this->code = 1;
            $this->msg = "OK";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiontimeLogs()
    {
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';
        $ref_id = isset($this->data['ref_id']) ? $this->data['ref_id'] : '';

        $sortby = "date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.*, b.zone_name";
        $criteria->join = '
		LEFT JOIN {{zones}} b on  a.zone_id = b.zone_id 		
		';

        try {
            $driver_data = CDriver::getDriverByUUID($ref_id);
            $criteria->addCondition('driver_id=:driver_id AND on_demand=0');
            $criteria->params = array(':driver_id' => $driver_data->driver_id);
        } catch (Exception $e) {
            //
        }

        if (!empty($search)) {
            $criteria->addSearchCondition('a.zone_id', $search);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_driver_schedule::model()->count($criteria);

        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $models = AR_driver_schedule::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {

                $end_shift_url = Yii::app()->createAbsoluteUrl("/driver/endshift", [
                    'id' => $item->schedule_uuid
                ]);
                $delete_shift_url = Yii::app()->createAbsoluteUrl("/driver/deleteshift", [
                    'id' => $item->schedule_uuid
                ]);
                $label = t("End Shift");


                $buttons = <<<HTML
<a href="$end_shift_url" class="btn btn-primary">$label</a>
HTML;

                $buttons = <<<HTML
<div class="btn-group btn-group-actions" role="group">
 <a href="$end_shift_url"  class="btn btn-light tool_tips" data-toggle="tooltip" data-placement="top" title="$label" data-original-title="$label" >
	<i class="zmdi zmdi-filter-tilt-shift"></i>
</a>
 <a href="$delete_shift_url"  class="btn btn-light tool_tips"><i class="zmdi zmdi-delete"></i></a> 
</div>
HTML;


                $data[] = array(
                    'schedule_id' => $item->schedule_id,
                    'zone_id' => $item->zone_name,
                    'date_created' => Date_Formatter::date($item->time_start),
                    'time_start' => Date_Formatter::Time($item->time_start),
                    'time_end' => Date_Formatter::Time($item->time_end),
                    'shift_time_started' => !empty($item->shift_time_started) ? Date_Formatter::Time($item->shift_time_started) : '',
                    'shift_time_ended' => !empty($item->shift_time_ended) ? Date_Formatter::Time($item->shift_time_ended) : '',
                    'date_modified' => $item->shift_time_ended == null ? $buttons : ''
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actionbankdepositlist()
    {
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';

        $sortby = "deposit_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        if ($sortby == "deposit_uuid") {
            $sortby = "deposit_id";
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.*,
		(
			select order_uuid from {{ordernew}}
			where order_id=a.transaction_ref_id
		) as order_uuid
		";

        $criteria->condition = "deposit_type=:deposit_type";
        $criteria->params = ['deposit_type' => 'order'];

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(date_created,'%Y-%m-%d')", $date_start, $date_end);
        }

        if (!empty($search)) {
            $criteria->addSearchCondition('transaction_ref_id', $search);
            $criteria->addSearchCondition('account_name', $search, true, 'OR');
            $criteria->addSearchCondition('reference_number', $search, true, 'OR');
        }

        $criteria->order = "$sortby $sort";
        $count = AR_bank_deposit::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        if ($length > 0) {
            $pages->applyLimit($criteria);
        }

        if ($models = AR_bank_deposit::model()->findAll($criteria)) {
            foreach ($models as $item) {

                $exchange_rate = $item->exchange_rate_merchant_to_admin > 0 ? $item->exchange_rate_merchant_to_admin : 1;
                $amount = Price_Formatter::formatNumber(($item->amount * $exchange_rate));

                $link = CMedia::getImage($item->proof_image, $item->path);
                $order_link = Yii::app()->CreateUrl("/order/view/", [
                    'order_uuid' => $item->order_uuid
                ]);

                $status = t($item->status);
                $bg_badge = $item->status == "pending" ? 'badge-warning' : 'badge-success';

                $image = <<<HTML
<a href="$link" class="btn btn-light btn-sm" target="_blank">View</a>
HTML;

                $order_ref = <<<HTML
<a href="$order_link"  target="_blank">$item->transaction_ref_id</a>
<span class="badge ml-2 $bg_badge">$status</span>
HTML;

                $data[] = array(
                    'deposit_id' => $item->deposit_id,
                    'deposit_uuid' => $item->deposit_uuid,
                    'date_created' => Date_Formatter::dateTime($item->date_created),
                    'proof_image' => $image,
                    'deposit_type' => $item->deposit_type,
                    'transaction_ref_id' => $order_ref,
                    'account_name' => $item->account_name,
                    'amount' => $amount,
                    'reference_number' => $item->reference_number,
                    'view_url' => Yii::app()->createUrl("/payment_gateway/bank_deposit_view/", array('id' => $item->deposit_uuid)),
                    'delete_url' => Yii::app()->createUrl("/payment_gateway/bank_deposit_delete/", array('id' => $item->deposit_uuid)),
                );
            }
        }
        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actionFPprint()
    {
        try {

            $order_uuid = isset($this->data['order_uuid']) ? $this->data['order_uuid'] : '';
            $printer_id = isset($this->data['printer_id']) ? $this->data['printer_id'] : '';

            // $model = AR_printer::model()->find("merchant_id=:merchant_id AND printer_id=:printer_id",[
            //     ":merchant_id"=>0,
            //     ':printer_id'=>intval($printer_id)
            // ]);
            $model = AR_printer::model()->find("printer_id=:printer_id", [
                ':printer_id' => intval($printer_id)
            ]);
            if ($model) {

                $meta = AR_printer_meta::getMeta($printer_id, ['printer_user', 'printer_ukey', 'printer_sn', 'printer_key']);
                $printer_user = isset($meta['printer_user']) ? $meta['printer_user']['meta_value1'] : '';
                $printer_ukey = isset($meta['printer_ukey']) ? $meta['printer_ukey']['meta_value1'] : '';
                $printer_sn = isset($meta['printer_sn']) ? $meta['printer_sn']['meta_value1'] : '';
                $printer_key = isset($meta['printer_key']) ? $meta['printer_key']['meta_value1'] : '';

                $order_id = 0;
                $summary = array();
                $order_status = array();
                $order_delivery_status = array();
                $merchant_info = array();
                $order = array();
                $items = array();

                COrders::getContent($order_uuid, Yii::app()->language);
                $merchant_id = COrders::getMerchantId($order_uuid);

                $merchant_info = COrders::getMerchant($merchant_id, Yii::app()->language);
                $items = COrders::getItems();
                $summary = COrders::getSummary();
                $order = COrders::orderInfo();
                $order_id = $order['order_info']['order_id'];

                $credit_card_details = '';
                $payment_code = $order['order_info']['payment_code'];
                if ($payment_code == "ocr") {
                    try {
                        $credit_card_details = COrders::getCreditCard2($order_id);
                        $order['order_info']['credit_card_details'] = $credit_card_details;
                    } catch (Exception $e) {
                        //
                    }
                }

                $order_type = $order['order_info']['order_type'];
                $order_table_data = [];
                if ($order_type == "dinein") {
                    $order_table_data = COrders::orderMeta(['table_id', 'room_id', 'guest_number']);
                    $room_id = isset($order_table_data['room_id']) ? $order_table_data['room_id'] : 0;
                    $table_id = isset($order_table_data['table_id']) ? $order_table_data['table_id'] : 0;
                    try {
                        $table_info = CBooking::getTableByID($table_id);
                        $order_table_data['table_name'] = $table_info->table_name;
                    } catch (Exception $e) {
                        $order_table_data['table_name'] = t("Unavailable");
                    }
                    try {
                        $room_info = CBooking::getRoomByID($room_id);
                        $order_table_data['room_name'] = $room_info->room_name;
                    } catch (Exception $e) {
                        $order_table_data['room_name'] = t("Unavailable");
                    }
                }

                $order['order_info']['order_table_data'] = $order_table_data;

                $tpl = FPtemplate::ReceiptTemplate(
                    $model->paper_width,
                    $order['order_info'],
                    $merchant_info,
                    $items,
                    $summary
                );

                $stime = time();
                $sig = sha1($printer_user . $printer_ukey . $stime);
                $result = FPinterface::Print($printer_user, $stime, $sig, $printer_sn, $tpl);

                $model = new AR_printer_logs();
                $model->order_id = intval($order_id);
                $model->merchant_id = intval($merchant_id);
                $model->printer_number = $printer_sn;
                $model->print_content = $tpl;
                $model->job_id = $result;
                $model->status = 'process';
                $model->save();

                $this->code = 1;
                $this->msg = t("Request succesfully sent to printer");
                $this->details = $result;
            } else $this->msg = t(HELPER_RECORD_NOT_FOUND);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actioninvoicebankdepositlist()
    {
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')  : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';

        $sortby = "deposit_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        if ($sortby == "deposit_uuid") {
            $sortby = "deposit_id";
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.*,
		(
			select invoice_uuid from {{invoice}}
			where invoice_number=a.transaction_ref_id
		) as invoice_uuid
		";

        $criteria->condition = "deposit_type=:deposit_type";
        $criteria->params = ['deposit_type' => 'invoice'];

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(date_created,'%Y-%m-%d')", $date_start, $date_end);
        }

        if (!empty($search)) {
            $criteria->addSearchCondition('transaction_ref_id', $search);
            $criteria->addSearchCondition('account_name', $search, true, 'OR');
            $criteria->addSearchCondition('reference_number', $search, true, 'OR');
        }

        $criteria->order = "$sortby $sort";
        $count = AR_bank_deposit::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        if ($length > 0) {
            $pages->applyLimit($criteria);
        }

        if ($models = AR_bank_deposit::model()->findAll($criteria)) {

            //$price_format = CMulticurrency::getAllCurrency();

            foreach ($models as $item) {

                // if($price_format){
                // 	if(isset($price_format[$item->base_currency_code])){
                // 		Price_Formatter::$number_format = $price_format[$item->base_currency_code];
                // 	}
                // }

                $link = CMedia::getImage($item->proof_image, $item->path);
                $order_link = Yii::app()->CreateUrl("/invoice/view/", [
                    'invoice_uuid' => $item->invoice_uuid
                ]);

                $status = t($item->status);
                $bg_badge = $item->status == "pending" ? 'badge-warning' : 'badge-success';

                $image = <<<HTML
<a href="$link" class="btn btn-light btn-sm" target="_blank">View</a>
HTML;

                $order_ref = <<<HTML
<a href="$order_link"  target="_blank">$item->transaction_ref_id</a>
<span class="badge ml-2 $bg_badge">$status</span>
HTML;

                $data[] = array(
                    'deposit_id' => $item->deposit_id,
                    'deposit_uuid' => $item->deposit_uuid,
                    'date_created' => Date_Formatter::dateTime($item->date_created),
                    'proof_image' => $image,
                    'deposit_type' => $item->deposit_type,
                    'transaction_ref_id' => $order_ref,
                    'account_name' => $item->account_name,
                    'amount' => Price_Formatter::formatNumber(($item->amount * $item->exchange_rate_merchant_to_admin)),
                    'reference_number' => $item->reference_number,
                    'view_url' => Yii::app()->createUrl("/invoice/bank_deposit_view/", array('id' => $item->deposit_uuid)),
                    'delete_url' => Yii::app()->createUrl("/invoice/bank_deposit_delete/", array('id' => $item->deposit_uuid)),
                );
            }
        }
        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actionreservationList()
    {
        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')  : '';
        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $filter_id = isset($this->data['ref_id']) ? $this->data['ref_id'] : '';

        $sortby = "reservation_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.*,
		concat(b.first_name,' ',b.last_name) as full_name,
		c.table_name, d.restaurant_name
		";
        $criteria->join = '
		LEFT JOIN {{client}} b on  a.client_id = b.client_id 
		LEFT JOIN {{table_tables}} c on  a.table_id = c.table_id 
		LEFT JOIN {{merchant}} d on  a.merchant_id = d.merchant_id 
		';

        if ($filter_id > 0) {
            $criteria->addCondition("a.client_id=:client_id");
            $criteria->params = [
                ':client_id' => $filter_id
            ];
        }

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(reservation_date,'%Y-%m-%d')", $date_start, $date_end);
        }

        if (!empty($search)) {
            $criteria->addSearchCondition('a.reservation_id', $search);
        }

        $data = [];
        $status_list = AttributesTools::bookingStatus();

        $criteria->order = "$sortby $sort";
        $count = AR_table_reservation::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        if ($length > 0) {
            $pages->applyLimit($criteria);
        }

        $model = AR_table_reservation::model()->findAll($criteria);
        if ($model) {
            foreach ($model as $items) {

                $edit = Yii::app()->CreateUrl("/reservation/update_reservation", [
                    'id' => $items->reservation_uuid
                ]);
                $overview = Yii::app()->CreateUrl("/reservation/reservation_overview", [
                    'id' => $items->reservation_uuid
                ]);
                $booking_status = isset($status_list[$items->status]) ? $status_list[$items->status] : $items->status;

                $badge = 'badge-primary';
                $button_color = 'btn-info';
                if ($items->status == "confirmed") {
                    $badge = 'badge-success';
                    $button_color = 'btn-success';
                } else if ($items->status == "cancelled") {
                    $badge = 'badge-danger';
                    $button_color = 'btn-danger';
                } else if ($items->status == "denied") {
                    $badge = 'badge-danger';
                    $button_color = 'btn-danger';
                } else if ($items->status == "finished") {
                    $badge = 'badge-success';
                    $button_color = 'btn-success';
                }

                $status_action_list = '';
                foreach ($status_list as $key => $value) {
                    $status_action_list .= '<a class="dropdown-item" href="' . Yii::app()->CreateUrl(
                            "/reservation/update_status",
                            [
                                'id' => $items->reservation_uuid,
                                'status' => $key
                            ]
                        ) . '">' . $value . '</a>';
                }

                $special_request = $items->special_request;
                if (!empty($items->cancellation_reason)) {
                    $special_request .= "<p class=\"text-danger\">";
                    $special_request .= t("CANCELLATION NOTES = {cancellation_reason}", [
                        '{cancellation_reason}' => $items->cancellation_reason
                    ]);
                    $special_request .= "</p>";
                }

                $action = <<<HTML
<div class="btn-group btn-group-actions" role="group">
  <a href="$overview" class="btn btn-light tool_tips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Update">
   <i class="zmdi zmdi-eye"></i>
  </a>
  <a href="$edit" class="btn btn-light tool_tips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Update">
   <i class="zmdi zmdi-border-color"></i>
  </a>
  <a href="javascript:;" data-id="$items->reservation_uuid" class="btn btn-light datatables_delete tool_tips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete">
  <i class="zmdi zmdi-delete"></i>
  </a>
</div>

<div class="dropdown">
  <button class="btn $button_color dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  $booking_status
  </button>  
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">		
    $status_action_list
  </div>
</div>
HTML;


                $data[] = [
                    'reservation_id' => $items->reservation_id,
                    'merchant_id' => $items->restaurant_name,
                    'client_id' => '<b>' . $items->full_name . '</b></b><span class="badge ml-2 post ' . $badge . '">' . $booking_status . '</span>',
                    'guest_number' => $items->guest_number,
                    'table_id' => $items->table_name,
                    'reservation_date' => Date_Formatter::dateTime($items->reservation_date . " " . $items->reservation_time),
                    'special_request' => $special_request,
                    'date_created' => $action
                ];
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actionBookingTimeline()
    {
        try {

            $id = Yii::app()->input->post("id");
            $model = CBooking::get($id);
            $data = CBooking::getTimeline($model->reservation_id);
            $this->code = 1;
            $this->msg = "Ok";
            $this->details = [
                'data' => $data
            ];
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionprintLogs()
    {
        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')  : '';
        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $filter_id = isset($this->data['filter_id']) ? $this->data['filter_id'] : '';

        $sortby = "id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.*		
		";
        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(date_created,'%Y-%m-%d')", $date_start, $date_end);
        }

        if (!empty($search)) {
            $criteria->addSearchCondition('id', $search);
        }

        $data = [];

        $criteria->order = "$sortby $sort";
        $count = AR_printer_logs::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $model = AR_printer_logs::model()->findAll($criteria);
        if ($model) {
            foreach ($model as $items) {

                $view = Yii::app()->CreateUrl("/printer/print_view", ['id' => $items->id]);

                $badge = 'badge-primary';
                if ($items->status == "process") {
                    $badge = 'badge-success';
                } else {
                    $badge = 'badge-danger';
                }

                $action = <<<HTML
<div class="btn-group btn-group-actions" role="group">
  <a href="$view" target="_blank" class="btn btn-light tool_tips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Update">
   <i class="zmdi zmdi-eye"></i>
  </a>  
  <a href="javascript:;" data-id="$items->id" class="btn btn-light datatables_delete tool_tips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete">
  <i class="zmdi zmdi-delete"></i>
  </a>
</div>
HTML;

                $data[] = [
                    'id' => $items->id,
                    'order_id' => '<b>' . $items->order_id . '</b>',
                    'printer_number' => $items->printer_number,
                    'job_id' => '<span class="d-inline-block text-truncate" style="max-width: 150px;">' . $items->job_id . '</span>',
                    'status' => '<span class="badge ml-2 post ' . $badge . '">' . $items->status . '</span>',
                    'date_created' => Date_Formatter::dateTime($items->date_created),
                    'ip_address' => $action,
                ];
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actionshiftList()
    {
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')  : '';
        $ref_id = isset($this->data['ref_id']) ? $this->data['ref_id'] : '';

        $sortby = "date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();
        $criteria->alias = "a";

        $criteria->condition = "merchant_id=:merchant_id";
        $criteria->params = [
            'merchant_id' => 0
        ];

        if (!empty($search)) {
            $criteria->addCondition("a.zone_id IN (
				select zone_id from {{zones}}
				where zone_name LIKE " . q("$search%") . "
			)");
        }

        $criteria->order = "$sortby $sort";
        $count = AR_driver_shift_schedule::model()->count($criteria);

        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $models = AR_driver_shift_schedule::model()->findAll($criteria);

        $zone_list = CommonUtility::getDataToDropDown("{{zones}}", 'zone_id', 'zone_name', "
		WHERE merchant_id = 0", "ORDER BY zone_name ASC");

        if ($models) {
            foreach ($models as $item) {
                $data[] = array(
                    'shift_id' => $item->shift_id,
                    'shift_uuid' => $item->shift_uuid,
                    'zone_id' => isset($zone_list[$item->zone_id]) ? $zone_list[$item->zone_id] : $item->zone_id,
                    'time_start' => Date_Formatter::dateTime($item->time_start),
                    'time_end' => Date_Formatter::dateTime($item->time_end),
                    'max_allow_slot' => $item->max_allow_slot > 0 ? $item->max_allow_slot : t("unlimited"),
                    'update_url' => Yii::app()->createUrl("/driver/shift_update/", array('id' => $item->shift_uuid)),
                    'delete_url' => Yii::app()->createUrl("/driver/shift_delete/", array('id' => $item->shift_uuid)),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actiongetZoneList()
    {
        try {

            $zone_list = CommonUtility::getDataToDropDown("{{zones}}", 'zone_id', 'zone_name', "
		    WHERE merchant_id = 0", "ORDER BY zone_name ASC");
            if ($zone_list) {
                $zone_list = CommonUtility::ArrayToLabelValue($zone_list);
                $this->code = 1;
                $this->msg = "Ok";
                $this->details = $zone_list;
            } else $this->msg = t(HELPER_NO_RESULTS);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiondriverWalletTransactions()
    {
        $data = array();
        $card_id = 0;
        $driver_id = 0;
        $ref_id = isset($this->data['ref_id']) ? $this->data['ref_id'] : '';

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';

        try {
            $driver_data = CDriver::getDriverByUUID($ref_id);
            $driver_id = $driver_data->driver_id;
            $card_id = CWallet::getCardID(Yii::app()->params->account_type['driver'], $driver_id);
        } catch (Exception $e) {
            //
        }

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';

        $sortby = "transaction_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        $criteria->addCondition('card_id=:card_id');
        $criteria->params = array(':card_id' => intval($card_id));

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(transaction_date,'%Y-%m-%d')", $date_start, $date_end);
        }
        if (is_array($transaction_type) && count($transaction_type) >= 1) {
            $criteria->addInCondition('transaction_type', (array) $transaction_type);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_wallet_transactions::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);
        $models = AR_wallet_transactions::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {
                $description = Yii::app()->input->xssClean($item->transaction_description);
                $parameters = json_decode($item->transaction_description_parameters, true);
                if (is_array($parameters) && count($parameters) >= 1) {
                    $description = t($description, $parameters);
                }
                $transaction_amount = Price_Formatter::formatNumber(($item->transaction_amount * $item->exchange_rate_merchant_to_admin));
                switch ($item->transaction_type) {
                    case "debit":
                    case "payout":
                        $transaction_amount = "(" . Price_Formatter::formatNumber($item->transaction_amount) . ")";
                        break;
                }

                $trans_html = <<<HTML
<p class="m-0 $item->transaction_type">$transaction_amount</p>
HTML;


                $data[] = array(
                    'transaction_date' => Date_Formatter::date($item->transaction_date),
                    'transaction_description' => $description,
                    'transaction_amount' => $trans_html,
                    'running_balance' => Price_Formatter::formatNumber(($item->running_balance * $item->exchange_rate_merchant_to_admin)),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actiondriverWalletAdjustment()
    {
        try {

            $transaction_description = isset($this->data['transaction_description']) ? $this->data['transaction_description'] : '';
            $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';
            $transaction_amount = isset($this->data['transaction_amount']) ? $this->data['transaction_amount'] : 0;

            $base_currency = Price_Formatter::$number_format['currency_code'];
            $driver_currency = 	$base_currency;
            $exchange_rate_merchant_to_admin = 1;
            $exchange_rate_admin_to_merchant = 1;

            $multicurrency_enabled = isset(Yii::app()->params['settings']['multicurrency_enabled']) ? Yii::app()->params['settings']['multicurrency_enabled'] : false;
            $multicurrency_enabled = $multicurrency_enabled == 1 ? true : false;

            $ref_id = isset($this->data['ref_id']) ? $this->data['ref_id'] : '';
            $driver_data = CDriver::getDriverByUUID($ref_id);

            if ($multicurrency_enabled && !empty($driver_data->default_currency)) {
                if ($driver_data->default_currency != $base_currency) {
                    $driver_currency = $driver_data->default_currency;
                    $exchange_rate_merchant_to_admin = CMulticurrency::getExchangeRate($driver_currency, $base_currency);
                    $exchange_rate_admin_to_merchant = CMulticurrency::getExchangeRate($base_currency, $driver_currency);
                }
            }

            $params = array(
                'transaction_description' => $transaction_description,
                'transaction_type' => $transaction_type,
                'transaction_amount' => floatval($transaction_amount),
                'meta_name' => "adjustment",
                'meta_value' => CommonUtility::createUUID("{{admin_meta}}", 'meta_value'),
                'merchant_base_currency' => $driver_currency,
                'admin_base_currency' => $base_currency,
                'exchange_rate_merchant_to_admin' => $exchange_rate_merchant_to_admin,
                'exchange_rate_admin_to_merchant' => $exchange_rate_admin_to_merchant,
            );

            $driver_id = $driver_data->driver_id;
            $card_id = CWallet::createCard(Yii::app()->params->account_type['driver'], $driver_id);
            CWallet::inserTransactions($card_id, $params);

            $this->code = 1;
            $this->msg = t("Successful");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiondriverWalletBalance()
    {
        try {

            $ref_id = isset($this->data['ref_id']) ? $this->data['ref_id'] : '';
            $driver_data = CDriver::getDriverByUUID($ref_id);
            $driver_id = $driver_data->driver_id;
            $card_id = CWallet::createCard(Yii::app()->params->account_type['driver'], $driver_id);
            $balance = CWallet::getBalance($card_id);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
            $balance = 0;
        }

        $this->code = 1;
        $this->msg = "OK";
        $this->details = array(
            'balance' => Price_Formatter::formatNumberNoSymbol($balance),
            'price_format' => array(
                'symbol' => Price_Formatter::$number_format['currency_symbol'],
                'decimals' => Price_Formatter::$number_format['decimals'],
                'decimal_separator' => Price_Formatter::$number_format['decimal_separator'],
                'thousand_separator' => Price_Formatter::$number_format['thousand_separator'],
                'position' => Price_Formatter::$number_format['position'],
            )
        );
        $this->responseJson();
    }

    public function actiondriverCashoutTransactions()
    {
        $driver_id = 0;
        $card_id = 0;
        $data = [];
        try {
            $ref_id = isset($this->data['ref_id']) ? $this->data['ref_id'] : '';
            $driver_data = CDriver::getDriverByUUID($ref_id);
            $driver_id = $driver_data->driver_id;
            $card_id = CWallet::getCardID(Yii::app()->params->account_type['driver'], $driver_id);
        } catch (Exception $e) {
        }

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')  : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';

        $sortby = "transaction_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();
        $criteria->condition = "card_id=:card_id  AND transaction_type=:transaction_type";
        $criteria->params  = array(
            ':card_id' => intval($card_id),
            ':transaction_type' => "cashout"
        );

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(transaction_date,'%Y-%m-%d')", $date_start, $date_end);
        }

        $status_trans = AttributesTools::statusManagementTranslationList('payment', Yii::app()->language);

        $criteria->order = "$sortby $sort";
        $count = AR_wallet_transactions::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);
        $models = AR_wallet_transactions::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {
                $description = Yii::app()->input->xssClean($item->transaction_description);
                $parameters = json_decode($item->transaction_description_parameters, true);
                if (is_array($parameters) && count($parameters) >= 1) {
                    $description = t($description, $parameters);
                }

                $transaction_amount = Price_Formatter::formatNumber($item->transaction_amount);
                if ($item->transaction_type == "debit") {
                    $transaction_amount = "(" . Price_Formatter::formatNumber($item->transaction_amount) . ")";
                }

                $trans_status = $item->status;
                if (array_key_exists($item->status, (array)$status_trans)) {
                    $trans_status = $status_trans[$item->status];
                }
                $description = '<p class="m-0">' . $description . '</p>';
                $description .= '<div class="badge payment ' . $item->status . '">' . $trans_status . '</div>';

                $data[] = array(
                    'transaction_amount' => $transaction_amount,
                    'transaction_description' => $description,
                    'transaction_date' => Date_Formatter::date($item->transaction_date),
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actioncashoutSummary()
    {
        try {

            $data = CPayouts::payoutSummary('cashout', 0, true);
            $this->code = 1;
            $this->msg = "ok";
            $this->details = array(
                'summary' => $data,
                'price_format' => array(
                    'symbol' => Price_Formatter::$number_format['currency_symbol'],
                    'decimals' => Price_Formatter::$number_format['decimals'],
                    'decimal_separator' => Price_Formatter::$number_format['decimal_separator'],
                    'thousand_separator' => Price_Formatter::$number_format['thousand_separator'],
                    'position' => Price_Formatter::$number_format['position'],
                )
            );
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actioncashoutList()
    {
        $data = array();
        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '') : '';
        $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';
        $filter = isset($this->data['filter']) ? $this->data['filter'] : '';
        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';

        $sortby = "a.transaction_date";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        $criteria->alias = "a";

        $criteria->select = "a.transaction_uuid,a.card_id,a.transaction_amount,a.transaction_date, a.status,
		b.driver_id, b.merchant_id, concat(b.first_name,' ',b.last_name) as driver_name , b.photo as logo , b.path";

        $criteria->join = "LEFT JOIN {{driver}} b on a.card_id = 
		(
		 select card_id from {{wallet_cards}}
		 where account_type=" . q(Yii::app()->params->account_type['driver']) . " and account_id=b.driver_id
		)
		";

        $criteria->condition = "transaction_type=:transaction_type AND b.merchant_id=0";
        $criteria->params = array(
            ':transaction_type' => 'cashout'
        );

        if (is_array($transaction_type) && count($transaction_type) >= 1) {
            $criteria->addInCondition('a.status', (array) $transaction_type);
        }

        if (!empty($search)) {
            $criteria->addSearchCondition('a.first_name', $search);
        }

        if (is_array($filter) && count($filter) >= 1) {
            $filter_merchant_id = isset($filter['driver_id']) ? $filter['driver_id'] : '';
            $criteria->addSearchCondition('b.driver_id', $filter_merchant_id);
        }

        if (!empty($date_start) && !empty($date_end)) {
            $criteria->addBetweenCondition("DATE_FORMAT(transaction_date,'%Y-%m-%d')", $date_start, $date_end);
        }

        $criteria->order = "$sortby $sort";
        $count = AR_wallet_transactions::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);
        $models = AR_wallet_transactions::model()->findAll($criteria);

        if ($models) {
            foreach ($models as $item) {

                $logo_url = CMedia::getImage($item->logo, $item->path, '@thumbnail', CommonUtility::getPlaceholderPhoto('customer'));
                $transaction_amount = Price_Formatter::formatNumber($item->transaction_amount);
                $status = $item->status;


                $logo_html = <<<HTML
<img src="$logo_url" class="img-60 rounded-circle" />
HTML;

                $amount_html = <<<HTML
<p class="m-0"><b>$transaction_amount</b></p>
<p class="m-0"><span class="badge payment $status">$status</span></p>
HTML;



                $data[] = array(
                    'driver_id' => $item->driver_id,
                    'photo' => $logo_html,
                    'transaction_date' => Date_Formatter::date($item->transaction_date),
                    'driver_name' => Yii::app()->input->xssClean($item->driver_name),
                    'transaction_amount' => $amount_html,
                    'transaction_uuid' => $item->transaction_uuid,
                );
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actioncollectCashList()
    {
        $data = array();
        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';

        $sortby = "date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.*, concat(b.first_name,' ',b.last_name) as driver_name";

        $criteria->join = "LEFT JOIN {{driver}} b on a.driver_id = b.driver_id";

        $criteria->condition = "a.merchant_id=:merchant_id";
        $criteria->params = [
            ':merchant_id' => 0
        ];

        if (!empty($search)) {
            $criteria->addSearchCondition('a.reference_id', $search);
            $criteria->addSearchCondition('b.first_name', $search, true, 'OR');
            $criteria->addSearchCondition('b.last_name', $search, true, 'OR');
        }

        $criteria->order = "$sortby $sort";
        $count = AR_driver_collect_cash::model()->count($criteria);

        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $models = AR_driver_collect_cash::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {
                $data[] = [
                    'collect_id' => $item->collect_id,
                    'transaction_date' => Date_Formatter::dateTime($item->transaction_date),
                    'driver_id' => !empty($item->driver_name) ? $item->driver_name : t("Not found"),
                    'amount_collected' => Price_Formatter::formatNumber($item->amount_collected),
                    'reference_id' => $item->reference_id,
                    'collection_uuid' => $item->collection_uuid,
                    'view_url' => Yii::app()->createUrl("/driver/collect_transactions/", array('id' => $item->collection_uuid)),
                    'delete_url' => Yii::app()->createUrl("/driver/collect_cash_void/", array('id' => $item->collection_uuid)),
                ];
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actionrefundOrder()
    {
        $data = $this->getTransaction($this->data['transaction_id']);
        $host = "apitest.cybersource.com";
        $merchantId = "arabcp000198402";
        $keyId = "d1de262d-482e-49e7-9e8e-7e0edff12a46";
        $secretKey = "QiUBViJAO1wMgVTQH+t2cIpcegoXVLVqdAzv0TdGUyQ=";
        $date = gmdate("D, d M Y H:i:s T");
        $contentType = "application/json";
        $paymentId = "7349979323066867304503";
        $amount = $data['trans_amount'];
        $currency = $data['currency_code'];
        $payload = json_encode([
            "clientReferenceInformation" => [
                "code" => uniqid()
            ],
            "orderInformation" => [
                "amountDetails" => [
                    "totalAmount" => $amount,
                    "currency" => $currency
                ]
            ]
        ]);

        $digest = base64_encode(hash("sha256", $payload, true));

        $signatureString = "host: $host\n".
            "v-c-date: $date\n".
            "request-target: post /pts/v2/payments/$paymentId/refunds\n".
            "digest: SHA-256=$digest\n".
            "v-c-merchant-id: $merchantId";

        $signature = base64_encode(hash_hmac("sha256", $signatureString, base64_decode($secretKey), true));

        $headers = [
            "Host: $host",
            "v-c-date: $date",
            "Digest: SHA-256=$digest",
            "v-c-merchant-id: $merchantId",
            "Signature: keyid=\"$keyId\", algorithm=\"HmacSHA256\", headers=\"host v-c-date request-target digest v-c-merchant-id\", signature=\"$signature\"",
            "Content-Type: $contentType"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://$host/pts/v2/payments/$paymentId/refunds");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatus == 201) {
            $this->debitTransactionsWhenFullyRefund($data['order_id']);
            $this->updateTransactionDescriptionWhenRefunded($this->data['transaction_id']);
            $this->code = 1;
            $this->msg = 'ok';
            $this->details = json_decode($response);
        } else {
            $this->code = 0;
            $this->msg = "error";
        }
        $this->responseJson();
    }

    public function actionpartialRefund()
    {
        $host = "apitest.cybersource.com";
        $merchantId = "arabcp000198402";
        $keyId = "d1de262d-482e-49e7-9e8e-7e0edff12a46";
        $secretKey = "QiUBViJAO1wMgVTQH+t2cIpcegoXVLVqdAzv0TdGUyQ=";
        $date = gmdate("D, d M Y H:i:s T");
        $contentType = "application/json";
        $paymentId = "7349979323066867304503";
        $payload = json_encode([
            "clientReferenceInformation" => [
                "code" => uniqid()
            ],
            "orderInformation" => [
                "amountDetails" => [
                    "totalAmount" => $this->data['amount'],
                    "currency" => $this->data['currency']
                ]
            ]
        ]);

        $digest = base64_encode(hash("sha256", $payload, true));

        $signatureString = "host: $host\n".
            "v-c-date: $date\n".
            "request-target: post /pts/v2/payments/$paymentId/refunds\n".
            "digest: SHA-256=$digest\n".
            "v-c-merchant-id: $merchantId";

        $signature = base64_encode(hash_hmac("sha256", $signatureString, base64_decode($secretKey), true));

        $headers = [
            "Host: $host",
            "v-c-date: $date",
            "Digest: SHA-256=$digest",
            "v-c-merchant-id: $merchantId",
            "Signature: keyid=\"$keyId\", algorithm=\"HmacSHA256\", headers=\"host v-c-date request-target digest v-c-merchant-id\", signature=\"$signature\"",
            "Content-Type: $contentType"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://$host/pts/v2/payments/$paymentId/refunds");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatus == 201) {
            $card_id = CWallet::getCardID('merchant', $this->data['merchant_id']);

            $description = 'partial refund order #'.$this->data['order_id'];
            $amount = $this->data['amount'];
            $this->insertWalletTransactionWhenPartialRefund($card_id, 'debit', $description, $amount);

            $description = 'partial refund commission order #'.$this->data['order_id'];
            $amount = floatval(($this->data['commission']/$this->data['total']) * $this->data['amount']);
            $this->insertWalletTransactionWhenPartialRefund($card_id, 'credit', $description, $amount);

            $newCommission = $this->data['commission'] - $amount;
            $this->updateOrderAfterPartialRefund($this->data['order_id'], $this->data['summary'], $newCommission);
            $this->code = 1;
            $this->msg = 'ok';
            $this->details = json_decode($response);
        } else {
            $this->code = 0;
            $this->msg = "error";
        }
        $this->responseJson();
    }

    private function updateOrderAfterPartialRefund($order_id, $summary, $commission){
        $order = AR_ordernew::model()->findByPk($order_id);
        $total = 0;
        foreach($summary as $record){
            if($record->type == 'total'){
                $total = $record->raw;
                $order->total = $record->raw;
            }
            if($record->type == 'subtotal'){
                $order->sub_total = $record->raw;
                $order->sub_total_less_discount = $record->raw;
            }
            if($record->type == 'delivery_fee'){
                $order->delivery_fee = $record->raw;
            }
            if($record->type == 'voucher'){
                $order->total_discount = $record->raw;
                $order->promo_total = $record->raw;
            }
        }

        $order->commission = $commission;
        $order->commission_original = $commission;
        $order->total_original = $total;
        $order->merchant_earning = $total-$commission;
        $order->merchant_earning_original = $total-$commission;
        $order->save();
    }

    private function insertWalletTransactionWhenPartialRefund($card_id, $type, $description, $amount){
        CWallet::inserTransactions($card_id, [
            'transaction_type'        => $type,
            'transaction_description' => $description,
            'transaction_amount'      => $amount
        ]);
    }

    private function creditTransactionCommissionWhenPartialRefund($order_id){
        $criteria = new CDbCriteria();
        $criteria->condition = "transaction_description LIKE :transaction_description";
        $criteria->params = array(
            ':transaction_description' => '%' . $order_id . '%'
        );
        $criteria->addCondition("transaction_type = 'credit'");
        $transactions = AR_wallet_transactions::model()->findAll($criteria);

        if($transactions)
        {
            foreach ($transactions as $trans){
                CWallet::inserTransactions($trans->card_id, [
                    'transaction_type' => 'debit',
                    'transaction_description' => $trans->transaction_description,
                    'transaction_amount' => $trans->transaction_amount
                ]);
            }
        }
    }

    private function debitTransactionsWhenFullyRefund($order_id){
        $criteria = new CDbCriteria();
        $criteria->condition = "transaction_description LIKE :transaction_description";
        $criteria->params = array(
            ':transaction_description' => '%' . $order_id . '%'
        );
        $criteria->addCondition("transaction_type = 'credit'");
        $transactions = AR_wallet_transactions::model()->findAll($criteria);

        if($transactions)
        {
            foreach ($transactions as $trans){
                CWallet::inserTransactions($trans->card_id, [
                    'transaction_type' => 'debit',
                    'transaction_description' => $trans->transaction_description,
                    'transaction_amount' => $trans->transaction_amount
                ]);
            }
        }
    }

    private function getTransaction($transaction_id)
    {
        return AR_ordernew_transaction::model()->findByPk(intval($transaction_id));
    }

    private function updateTransactionDescriptionWhenRefunded($transaction_id)
    {
        return AR_ordernew_transaction::model()->updateAll(array(
            'transaction_description' => 'Refunded'
        ), "transaction_id=:transaction_id",[
            ":transaction_id"=>$transaction_id
        ]);
    }

    private function getCardIdForType($adjustment_type, $type_id): int|null
    {
        $adjustment_user = $adjustment_type == 0 ? 'merchant' : ($adjustment_type == 1 ? 'digital_wallet' : 'driver');
        return CWallet::getCardID($adjustment_user, $type_id);
    }

    public function actionadjustmentOrder()
    {
        try {
            $card_id = $this->getCardIdForType($this->data['adjustment_type'], $this->data['type_id']);
            $params = array(
                'card_id' => intval($card_id),
                'transaction_description' => $this->data['transaction_description'],
                'transaction_type' => $this->data['transaction_type'] == 0 ? 'credit' : 'debit',
                'transaction_amount' => floatval($this->data['transaction_amount']),
                'meta_name' => "adjustment",
                'meta_value' => CommonUtility::createUUID("{{admin_meta}}", 'meta_value'),
            );

            $resp = CWallet::inserTransactions($card_id, $params);
            $this->code = 1;
            $this->msg = t("Successful");
            $this->details = $resp;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetAllDriver()
    {
        $criteria = new CDbCriteria;
        $criteria->addCondition('status=:status');
        $criteria->addCondition('employment_type=:employment_type');
        $criteria->params = [
            ':status' => 'active',
            ':employment_type' => 'employee'
        ];

        $drivers_list = AR_driver::model()->findAll($criteria);
        $data = [];
        foreach ($drivers_list as $items) {
            $data[] = [
                'name' => $items->first_name . " " . $items->last_name,
                'driver_id' => $items->driver_id,
                'driver_uuid' => $items->driver_uuid
            ];
        }

        $this->details = $data;
        $this->code  = 1;
        $this->msg = "OK";
        $this->responseJson();
    }

    public function actiongetAvailableDriver()
    {
        try {

            $on_demand_availability = isset(Yii::app()->params['settings']) ? (isset(Yii::app()->params['settings']['driver_on_demand_availability']) ? Yii::app()->params['settings']['driver_on_demand_availability'] : false) : false;
            $on_demand_availability = $on_demand_availability == 1 ? true : false;

            $order_uuid = Yii::app()->input->post("order_uuid");

            $order = COrders::get($order_uuid);
            $merchant = CMerchants::get($order->merchant_id);
            $merchant_data = [
                'restaurant_name' => $merchant->restaurant_name,
                'contact_phone' => $merchant->contact_phone,
                'contact_email' => $merchant->contact_email,
                'address' => $merchant->address,
                'latitude' => $merchant->latitude,
                'longitude' => $merchant->lontitude,
            ];
            $merchant_zone = CMerchants::getListMerchantZone([$merchant->merchant_id]);
            $merchant_zone = isset($merchant_zone[$merchant->merchant_id]) ? $merchant_zone[$merchant->merchant_id] : '';

            $group_selected = intval(Yii::app()->input->post("group_selected"));
            $q = Yii::app()->input->post("q");
            $merchant_id = intval(Yii::app()->input->post("merchant_id"));
            $zone_id = intval(Yii::app()->input->post("zone_id"));

            $criteria = new CDbCriteria();
            $criteria->alias = "a";
            $criteria->select = "a.*";
            if ($group_selected > 0) {
                $criteria->join = "LEFT JOIN {{driver_group_relations}} b ON a.driver_id = b.driver_id";
                $criteria->addCondition("b.group_id=:group_id");
            }

            $now = date("Y-m-d");
            $and_zone = '';
            if ($zone_id > 0) {
                $and_zone = "AND zone_id = " . q($zone_id) . " ";
            }

            if (!$on_demand_availability) {
                $criteria->addCondition("a.merchant_id=:merchant_id AND a.latitude !='' AND a.status=:status AND a.driver_id IN (
					select driver_id from {{driver_schedule}}
					where DATE(time_start)=" . q($now) . "
					AND DATE(shift_time_started) IS NOT NULL  
					AND DATE(shift_time_ended) IS NULL  
					$and_zone                    
				)");
            }

            if ($group_selected > 0) {
                $criteria->params = [
                    ':merchant_id' => intval($merchant_id),
                    ':group_id' => $group_selected,
                    ':status' => "active"
                ];
            } else {
                $criteria->params = [
                    ':merchant_id' => intval($merchant_id),
                    ':status' => "active"
                ];
            }

            if (!empty($q)) {
                $criteria->addSearchCondition('a.first_name', $q);
                $criteria->addSearchCondition('a.last_name', $q, true, 'OR');
            }

            // ON DEMAND
            if ($on_demand_availability) {
                $and_merchant_zone = '';
                if (is_array($merchant_zone) && count($merchant_zone) >= 1 || $zone_id > 0) {
                    if ($zone_id > 0) {
                        $in_query = CommonUtility::arrayToQueryParameters([$zone_id]);
                    } else $in_query = CommonUtility::arrayToQueryParameters($merchant_zone);
                    $and_merchant_zone = "
					AND a.driver_id IN (
						select driver_id from {{driver_schedule}}
						where 
						merchant_id=0 and driver_id = a.driver_id 
						and on_demand=1 and zone_id IN ($in_query)
					)
					";
                }
                $criteria->addCondition("a.merchant_id=:merchant_id AND a.is_online=:is_online AND a.status=:status AND a.latitude !='' $and_merchant_zone ");
                $criteria->params = [
                    ':merchant_id' => intval($merchant_id),
                    ':is_online' => 1,
                    ':status' => "active"
                ];
                if ($group_selected > 0) {
                    $criteria->params[':group_id'] = $group_selected;
                }
            }

            $criteria->order = "a.first_name ASC";
            $criteria->limit = 20;

            if ($model = AR_driver::model()->findAll($criteria)) {
                $data = array();
                $driver_ids = [];
                foreach ($model as $items) {
                    $photo = CMedia::getImage($items->photo, $items->path, '@thumbnail', CommonUtility::getPlaceholderPhoto('customer'));
                    $driver_ids[] = $items->driver_id;
                    $data[] = [
                        'name' => $items->first_name . " " . $items->last_name,
                        'driver_id' => $items->driver_id,
                        'photo_url' => $photo,
                        'latitude' => $items->latitude,
                        'longitude' => $items->lontitude,
                    ];
                }

                $active_task = CDriver::getCountActiveTaskAll($driver_ids, date("Y-m-d"));

                $this->code  = 1;
                $this->msg = "OK";
                $this->details = [
                    'data' => $data,
                    'merchant_data' => $merchant_data,
                    'active_task' => $active_task
                ];
            } else {
                $this->msg = t(HELPER_NO_RESULTS);
                $this->details = [
                    'merchant_data' => $merchant_data
                ];
            }
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionAssignDriver()
    {
        try {

            $on_demand_availability = isset(Yii::app()->params['settings']) ? (isset(Yii::app()->params['settings']['driver_on_demand_availability']) ? Yii::app()->params['settings']['driver_on_demand_availability'] : false) : false;
            $on_demand_availability = $on_demand_availability == 1 ? true : false;

            $driver_id = intval(Yii::app()->input->post('driver_id'));
            $order_uuid = trim(Yii::app()->input->post('order_uuid'));

            $order = COrders::get($order_uuid);
            $driver = CDriver::getDriver($driver_id);

            $meta = AR_admin_meta::getValue('status_assigned');
            $status_assigned = isset($meta['meta_value']) ? $meta['meta_value'] : '';

            $options = OptionsTools::find(['driver_allowed_number_task']);
            $allowed_number_task = isset($options['driver_allowed_number_task']) ? $options['driver_allowed_number_task'] : 0;

            $order->scenario = "assign_order";
            $order->on_demand_availability = $on_demand_availability;
            $order->driver_id = intval($driver_id);
            $order->delivered_old_status = $order->delivery_status;
            $order->delivery_status = $status_assigned;
            $order->change_by = Yii::app()->user->first_name;
            $order->date_now = date("Y-m-d");
            $order->allowed_number_task = intval($allowed_number_task);

            if (!$on_demand_availability) {
                try {
                    $now = date("Y-m-d");
                    $vehicle = CDriver::getVehicleAssign($driver_id, $now);
                    $order->vehicle_id = $vehicle->vehicle_id;
                } catch (Exception $e) {
                    $this->msg = t($e->getMessage());
                    $this->responseJson();
                }
            }

            if ($order->save()) {
                $this->code  = 1;
                $this->msg = t("Order assign to {driver_name}", [
                    '{driver_name}' => "$driver->first_name $driver->first_name"
                ]);
            } else {
                $this->msg = CommonUtility::parseModelErrorToString($order->getErrors());
            }
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionorderRejectionList()
    {
        try {
            $data = AOrders::rejectionList('rejection_list', Yii::app()->language);
            $this->code = 1;
            $this->msg = "ok";
            $this->details = $data;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionupdateOrderStatus()
    {
        try {
            $this->data = Yii::app()->input->xssClean(json_decode(file_get_contents('php://input'), true));
            $uuid = isset($this->data['uuid']) ? $this->data['uuid'] : '';
            $order_uuid = isset($this->data['order_uuid']) ? $this->data['order_uuid'] : '';
            $prep_time = isset($this->data['prep_time']) ? $this->data['prep_time'] : '';
            $rejetion_reason = isset($this->data['reason']) ? $this->data['reason'] : '';
            $button_name = isset($this->data['name']) ? $this->data['name'] : '';
            $status = AOrders::getOrderButtonStatus($uuid);
            $do_actions = AOrders::getOrderButtonActions($uuid);
            $cancel2 = AR_admin_meta::getValue('status_delivery_cancelled');
            $cancel_status2 = isset($cancel2['meta_value']) ? $cancel2['meta_value'] : 'cancelled';

            $model = COrders::get($order_uuid);

            if ($do_actions == "reject_form") {
                $model->scenario = "reject_order";
            } else $model->scenario = "change_status";

            if($button_name == 'Agree'){
                $status = 'new';
            }

            if($button_name == 'Rejected'){
                $this->createCreditAdjustmentsOnDeliveryCost($model);
            }

            if($button_name == 'Accepted'){
                $model->prep_time = $prep_time;
                $status = 'accepted';
                $now = new DateTime();
                $formattedDate = $now->format('Y-m-d H:i:s');
                $model->prep_time_enabled_at = $formattedDate;

                $this->createAdjustmentsOnDeliveryCost($model);
            }

            $model->status = $status;
            $model->remarks = $rejetion_reason;
            $model->change_by = Yii::app()->user->first_name;

            if ($do_actions == "reject_form") {
                $model->delivery_status  = $cancel_status2;
            }

            if($button_name == 'Ready for pickup') {
                $now = new DateTime();
                $updated_at = new DateTime($model->prep_time_enabled_at);

                $updated_at_time = DateTime::createFromFormat('H:i:s', $updated_at->format('H:i:s'));
                $now_time = DateTime::createFromFormat('H:i:s', $now->format('H:i:s'));

                $interval = $updated_at_time->diff($now_time);
                $minutes_difference = ($interval->h * 60) + $interval->i;

                $model->actual_prep_time = $minutes_difference;
            }

            if ($model->save()) {
                $this->code = 1;
                $this->msg = t("Status Updated");

                if (!empty($rejetion_reason)) {
                    COrders::savedMeta($model->order_id, 'rejetion_reason', $rejetion_reason);
                }
            } else $this->msg = CommonUtility::parseError($model->getErrors());
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    private function createAdjustmentsOnDeliveryCost($model): void
    {
        $promo = CPromos::findVoucherByName($model->promo_code);

        if($promo) {
            $shipping_rate = AR_shipping_rate::model()->findAll('merchant_id=:merchant_id',
                array(':merchant_id'=>$model->merchant_id));
            $fee = $shipping_rate[0]['distance_price'];
            $params = [
                'transaction_type' => 'debit',
                'transaction_description' => 'order_id #' . $model->order_id,
                'transaction_amount' => $fee
            ];

            if($promo->discount_delivery == 1) {
                if($promo->delivery_cost_payer == 1) {
                    $this->store_adjustment_transaction('admin', 0, $params);
                }else if($promo->delivery_cost_payer == 2) {
                    if($promo->paying_way_merchant == 2) {
                        $this->store_adjustment_transaction('merchant', $model->merchant_id, $params);
                    }
                }else if($promo->delivery_cost_payer == 3) {
                    $params['transaction_amount'] = $fee * ($promo->yummy_pay_percentage/100);
                    $this->store_adjustment_transaction('admin', 0, $params);

                    if($promo->paying_way_merchant == 2) {
                        $params['transaction_amount'] = $fee * ($promo->merchant_pay_percentage/100);
                        $this->store_adjustment_transaction('merchant', $model->merchant_id, $params);
                    }
                }
            }
        }
    }

    private function createCreditAdjustmentsOnDeliveryCost($order): void
    {
        try {
            $admin_card_id = CWallet::getCardID('admin', 0);
            CWallet::inserTransactions($admin_card_id, $this->getParamsForCancelOrder($order, 'admin'));
        }  catch (Exception $e) {

        }

        try {
            $merchant_card_id = CWallet::getCardID('merchant', $order->merchant_id);
            CWallet::inserTransactions($merchant_card_id, $this->getParamsForCancelOrder($order, 'merchant'));
        }  catch (Exception $e) {

        }

        try {
            $driver_card_id = CWallet::getCardID('merchant', $order->driver_id);
            CWallet::inserTransactions($driver_card_id, $this->getParamsForCancelOrder($order, 'driver'));
        }  catch (Exception $e) {

        }
    }

    private function getParamsForCancelOrder($order, $type)
    {
        $amount = floatval($type == 'admin' ? $order->commission : ($type == 'merchant' ? $order->sub_total : $order->delivery_fee));
        return [
            'transaction_type' => 'credit',
            'transaction_description' => 'cancel order wallet credit #'.$order->order_id,
            'transaction_amount' => $amount
        ];
    }

    public function actioncancelOrder()
    {
        try {
            $model = AR_admin_meta::model()->find('meta_name=:meta_name', array(':meta_name'=>'status_cancel_order'));
            if($model){
                $status_cancelled = $model->meta_value ;
            } else $status_cancelled = 'cancelled';


            $reason = isset($this->data['reason'])?trim($this->data['reason']):'';
            $order_uuid = isset($this->data['order_uuid'])?$this->data['order_uuid']:'';
            $model = COrders::get($order_uuid);
            $model->scenario = "cancel_order";

            if($model->status==$status_cancelled){
                $this->msg = t("Order has the same status");
                $this->responseJson();
            }

            $model->status = $status_cancelled;
            $model->remarks = $reason;

            if($model->save()){
                $this->code = 1;
                $this->msg = t("Order is cancelled");
                if(!empty($reason)){
                    COrders::savedMeta($model->order_id,'rejetion_reason',$reason);
                }
                $this->createCreditAdjustmentsOnDeliveryCost($model);
            } else $this->msg = CommonUtility::parseError( $model->getErrors());

        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    private function store_adjustment_transaction($account_type, $account_id, $params): void
    {
        $card_id = CWallet::getCardID($account_type, $account_id);
        CWallet::inserTransactions($card_id, $params);
    }

    public function actionClearWalletTransactions()
    {
        try {

            if (DEMO_MODE) {
                $this->msg = t("This functions is not available in demo");
                $this->responseJson();
            }

            $card_id = 0;
            $ref_id = Yii::app()->input->post('ref_id');
            try {
                $driver_data = CDriver::getDriverByUUID($ref_id);
                $driver_id = $driver_data->driver_id;
                $card_id = CWallet::getCardID(Yii::app()->params->account_type['driver'], $driver_id);
            } catch (Exception $e) {
                //
            }

            AR_wallet_transactions::model()->deleteAll("card_id=:card_id", [
                ':card_id' => $card_id
            ]);

            $this->code = 1;
            $this->msg = "Ok";
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiondriverearnings()
    {
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')  : '';
        $filter = isset($this->data['filter']) ? $this->data['filter'] : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $date_start = !empty($date_start) ? $date_start : date("Y-m-d");
        $date_end = !empty($date_end) ? $date_end : date("Y-m-d");

        $sortby = "a.driver_id";
        $sort = 'ASC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        if ($page > 0) {
            $page = intval($page) / intval($length);
        }


        $delivery_status = ['payout_delivery_fee', 'payout_commission', 'payout_fixed', 'payout_fixed_and_commission'];
        $payout_tip = ['payout_tip'];
        $payout_incentives = ['payout_incentives'];
        $adjustment = ['adjustment'];

        $delivery_status = CommonUtility::arrayToQueryParameters($delivery_status);
        $payout_tip = CommonUtility::arrayToQueryParameters($payout_tip);
        $payout_incentives = CommonUtility::arrayToQueryParameters($payout_incentives);
        $adjustment = CommonUtility::arrayToQueryParameters($adjustment);

        $account_type = Yii::app()->params->account_type['driver'];
        $transaction_type = "credit";
        $transaction_type_debit = "debit";

        // $total_credit = CDriver::EarningAdjustment($card_id,$date_start,$date_end,['adjustment']);
        // $total_debit = CDriver::EarningAdjustment($card_id,$date_start,$date_end,['adjustment'],'debit');
        // $total_adjustment = $total_credit - $total_debit;

        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "
		a.driver_id, a.first_name, a.last_name,
		(
			 SELECT sum(transaction_amount) as total
			 FROM {{wallet_transactions}} b
			 WHERE b.card_id = (
				select card_id from {{wallet_cards}}
				where account_type=" . q($account_type) . "
				and account_id = a.driver_id
				AND DATE(transaction_date) BETWEEN  " . q($date_start) . " AND " . q($date_end) . "
				AND transaction_type= " . q($transaction_type) . "
				AND transaction_id IN (
					select transaction_id FROM {{wallet_transactions_meta}}
                    where transaction_id = b.transaction_id
                    and meta_name IN (" . $delivery_status . ")
				)
			 )		 
		) as delivery_pay,

		(
			SELECT sum(transaction_amount) as total
			FROM {{wallet_transactions}} b
			WHERE b.card_id = (
			   select card_id from {{wallet_cards}}
			   where account_type=" . q($account_type) . "
			   and account_id = a.driver_id
			   AND DATE(transaction_date) BETWEEN  " . q($date_start) . " AND " . q($date_end) . "
			   AND transaction_type= " . q($transaction_type) . "
			   AND transaction_id IN (
				   select transaction_id FROM {{wallet_transactions_meta}}
				   where transaction_id = b.transaction_id
				   and meta_name IN (" . $payout_tip . ")
			   )
			)		 
	   ) as total_tips,

	   (
		SELECT sum(transaction_amount) as total
		FROM {{wallet_transactions}} b
		WHERE b.card_id = (
		   select card_id from {{wallet_cards}}
		   where account_type=" . q($account_type) . "
		   and account_id = a.driver_id
		   AND DATE(transaction_date) BETWEEN  " . q($date_start) . " AND " . q($date_end) . "
		   AND transaction_type= " . q($transaction_type) . "
		   AND transaction_id IN (
			   select transaction_id FROM {{wallet_transactions_meta}}
			   where transaction_id = b.transaction_id
			   and meta_name IN (" . $payout_incentives . ")
		   )
		)		 
        ) as total_incentives,

		(
			SELECT sum(transaction_amount) as total
			FROM {{wallet_transactions}} b
			WHERE b.card_id = (
			   select card_id from {{wallet_cards}}
			   where account_type=" . q($account_type) . "
			   and account_id = a.driver_id
			   AND DATE(transaction_date) BETWEEN  " . q($date_start) . " AND " . q($date_end) . "
			   AND transaction_type= " . q($transaction_type) . "
			   AND transaction_id IN (
				   select transaction_id FROM {{wallet_transactions_meta}}
				   where transaction_id = b.transaction_id
				   and meta_name IN (" . $adjustment . ")
			   )
			)		 
		) as total_credit,

		(
			SELECT sum(transaction_amount) as total
			FROM {{wallet_transactions}} b
			WHERE b.card_id = (
			   select card_id from {{wallet_cards}}
			   where account_type=" . q($account_type) . "
			   and account_id = a.driver_id
			   AND DATE(transaction_date) BETWEEN  " . q($date_start) . " AND " . q($date_end) . "
			   AND transaction_type= " . q($transaction_type_debit) . "
			   AND transaction_id IN (
				   select transaction_id FROM {{wallet_transactions_meta}}
				   where transaction_id = b.transaction_id
				   and meta_name IN (" . $adjustment . ")
			   )
			)		 
		) as total_debit

		";
        $criteria->order = "$sortby $sort";
        $count = AR_driver::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        if ($model = AR_driver::model()->findAll($criteria)) {
            foreach ($model as $item) {
                $total_adjustment = floatval($item->total_credit) - floatval($item->total_debit);
                $balance = floatval($item->delivery_pay) + floatval($item->total_tips) + floatval($item->total_incentives)  + floatval($total_adjustment);
                $data[] = [
                    'first_name' => "$item->first_name $item->last_name",
                    'delivery_pay' => $item->delivery_pay > 0 ? Price_Formatter::formatNumber($item->delivery_pay) : '',
                    'tips' => $item->total_tips > 0 ? Price_Formatter::formatNumber($item->total_tips) : '',
                    'incentives' => $item->total_incentives > 0 ? Price_Formatter::formatNumber($item->total_incentives) : '',
                    'adjustment' => $total_adjustment > 0 ? Price_Formatter::formatNumber($total_adjustment) : '',
                    'total_earnings' => $balance > 0 ? Price_Formatter::formatNumber($balance) : '',
                ];
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actiondriverreportwalletbalance()
    {

        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')  : '';
        $filter = isset($this->data['filter']) ? $this->data['filter'] : '';

        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $date_start = !empty($date_start) ? $date_start : date("Y-m-d");
        $date_end = !empty($date_end) ? $date_end : date("Y-m-d");

        $sortby = "a.driver_id";
        $sort = 'ASC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        if ($page > 0) {
            $page = intval($page) / intval($length);
        }

        $account_type = Yii::app()->params->account_type['driver'];

        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "
		a.driver_id, a.first_name, a.last_name,
		(
			 SELECT running_balance
			 FROM {{wallet_transactions}} b
			 WHERE b.card_id = (
				select card_id from {{wallet_cards}}
				where account_type=" . q($account_type) . "
				and account_id = a.driver_id				
			 )		 
			 order by transaction_id desc
			 limit 0,1
		) as wallet_balance
		";

        $criteria->order = "$sortby $sort";
        $count = AR_driver::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);
        if ($model = AR_driver::model()->findAll($criteria)) {
            foreach ($model as $item) {
                $wallet_balance =  $item->wallet_balance > 0 ? Price_Formatter::formatNumber($item->wallet_balance) : '<span class="text-danger">' . Price_Formatter::formatNumber($item->wallet_balance) . '</span>';
                $data[] = [
                    'first_name' => "$item->first_name $item->last_name",
                    'wallet_balance' => $wallet_balance
                ];
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actiongetMenu()
    {
        try {

            $role_id = Yii::app()->user->role_id;

            $cacheKey  = 'cache_search_menu_data';
            $items = Yii::app()->cache->get($cacheKey);

            if ($items === false) {
                $items = AttributesTools::getSearchBarMenu("admin", $role_id);
                Yii::app()->cache->set($cacheKey, $items, CACHE_LONG_DURATION);
            }

            $this->code = 1;
            $this->msg = "OK";
            $this->details = $items;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionexchangerate()
    {
        $data = array();

        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')  : '';

        $sortby = "date_created";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();

        if (!empty($search)) {
            $criteria->addSearchCondition('currency_code', $search);
            $criteria->addSearchCondition('base_currency', $search, true, "OR");
            $criteria->addSearchCondition('provider', $search, true, "OR");
        }

        $criteria->order = "$sortby $sort";
        $count = AR_currency_exchangerate::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $models = AR_currency_exchangerate::model()->findAll($criteria);

        if ($models) {
            foreach ($models as $item) {
                $data[] = array(
                    'id' => $item->id,
                    'provider' => $item->provider,
                    'base_currency' => $item->base_currency,
                    'currency_code' => $item->currency_code,
                    'exchange_rate' => $item->exchange_rate,
                    'date_created' => Date_Formatter::dateTime($item->date_created),
                    'update_url' => Yii::app()->createUrl("/multicurrency/update/", array('id' => $item->id)),
                    'delete_url' => Yii::app()->createUrl("/multicurrency/delete/", array('id' => $item->id)),
                );
            }
        }

        $datatables = array(
            'page' => $page,
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actionuserRewardsPoints()
    {
        $data = array();
        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';

        $sortby = "transaction_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();

        $criteria->alias = "a";
        $criteria->select = "
		a.card_id,b.account_id,c.first_name,c.last_name,
          SUM(
			CASE WHEN a.transaction_type IN ('points_earned', 'points_signup','points_firstorder','points_review','points_booking') 
            THEN transaction_amount ELSE -transaction_amount END
		  ) AS total_earning
		";

        $criteria->join = '
		LEFT JOIN {{wallet_cards}} b on  a.card_id = b.card_id 	
		
		left JOIN (
			SELECT client_id,first_name,last_name FROM {{client}} 
		) c
		on b.account_id = c.client_id
		';

        //$criteria->addInCondition("a.transaction_type",CPoints::transactionType());
        $criteria->condition = "b.account_type='customer_points'";

        $criteria->group = 'card_id';

        $criteria->order = "$sortby $sort";
        $count = AR_wallet_transactions::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $models = AR_wallet_transactions::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {

                $view = Yii::app()->createUrl('points/transactions', array(
                    'card_id' => $item->card_id
                ));

                $actions_html = <<<HTML
<div class="btn-group btn-group-actions" role="group">
	<a href="$view" class="btn btn-light tool_tips"><i class="zmdi zmdi-eye"></i></a>	
</div>
HTML;

                $data[] = [
                    'card_id' => ucwords("$item->first_name $item->last_name"),
                    'transaction_amount' => '<b>' . Price_Formatter::convertToRaw($item->total_earning, 0) . '</b>',
                    'transaction_type' => $actions_html
                ];
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actionPointsTransactionLogs()
    {
        $data = array();
        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';

        $card_id = isset($this->data['ref_id']) ? intval($this->data['ref_id']) : '';

        $account_type = 'customer_points';
        try {
            $cart_data = CWallet::getCard($card_id);
            $account_type = $cart_data->account_type;
        } catch (Exception $e) {
        }

        $sortby = "transaction_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        $criteria->addCondition('card_id=:card_id');
        $criteria->params = array(':card_id' => intval($card_id));

        $criteria->order = "$sortby $sort";
        $count = AR_wallet_transactions::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);
        $models = AR_wallet_transactions::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {
                $description = Yii::app()->input->xssClean($item->transaction_description);
                $parameters = json_decode($item->transaction_description_parameters, true);
                if (is_array($parameters) && count($parameters) >= 1) {
                    $description = t($description, $parameters);
                }

                $transaction_amount = 0;
                switch ($item->transaction_type) {
                    case "points_redeemed":
                    case "debit":
                        if ($account_type == "digital_wallet") {
                            $transaction_amount = '<span class="text-danger">' . "-" . Price_Formatter::formatNumber($item->transaction_amount) . '</span>';
                        } else $transaction_amount = '<span class="text-danger">' . "-" . Price_Formatter::convertToRaw($item->transaction_amount, 0) . '</span>';
                        break;
                    default:
                        if ($account_type == "digital_wallet") {
                            $transaction_amount =  '<span class="text-success"><b>' . "+" . Price_Formatter::formatNumber($item->transaction_amount) . '</b></span>';
                        } else $transaction_amount =  '<span class="text-success"><b>' . "+" . Price_Formatter::convertToRaw($item->transaction_amount, 0) . '</b></span>';
                        break;
                }

                if ($account_type == "digital_wallet") {
                    $balance = Price_Formatter::formatNumber($item->running_balance);
                } else $balance = Price_Formatter::convertToRaw($item->running_balance, 0);

                $data[] = [
                    'transaction_id' => $item->transaction_id,
                    'transaction_date' => Date_Formatter::dateTime($item->transaction_date),
                    'transaction_description' => $description,
                    'transaction_amount' => $transaction_amount,
                    'running_balance' => '<b>' . $balance . '</b>',
                ];
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actionpointsadjustment()
    {
        try {

            $card_id = isset($this->data['ref_id']) ? intval($this->data['ref_id']) : 0;
            $transaction_amount = isset($this->data['transaction_amount']) ? floatval($this->data['transaction_amount']) : 0;
            $transaction_description = isset($this->data['transaction_description']) ? $this->data['transaction_description'] : '';
            $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';

            if ($transaction_amount <= 0) {
                $this->msg = t("Invalid amount");
                $this->responseJson();
            }

            $params = [
                'transaction_description' => $transaction_description,
                'transaction_type' => $transaction_type == "credit" ? 'points_earned' : 'points_redeemed',
                'transaction_amount' => floatval($transaction_amount),
                'status' => 'paid',
            ];
            $resp = CWallet::inserTransactions($card_id, $params);
            $this->code = 1;
            $this->msg = t("Successful");
            $this->details = $resp;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiongetpointsbalance()
    {
        try {

            $card_id = Yii::app()->input->post("card_id");
            $return_format = Yii::app()->input->post("return_format");
            $balance = CWallet::getBalance($card_id);
            $this->code = 1;
            $this->msg = "Ok";

            $this->details = [
                'balance' => $return_format == 'money_format' ? Price_Formatter::formatNumber($balance) : $balance
            ];
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionallpointstransaction()
    {

        $data = array();
        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';

        $sortby = "transaction_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.*,b.first_name,b.last_name";

        $criteria->join = "LEFT JOIN {{client}} b on a.card_id = 
		(
		  select card_id from {{wallet_cards}}
		  where account_type=" . q(Yii::app()->params->account_type['customer_points']) . " and account_id=b.client_id
		)
		";
        $criteria->addInCondition("transaction_type", CPoints::transactionType());

        $criteria->order = "$sortby $sort";
        $count = AR_wallet_transactions::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $models = AR_wallet_transactions::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {
                $description = Yii::app()->input->xssClean($item->transaction_description);
                $parameters = json_decode($item->transaction_description_parameters, true);
                if (is_array($parameters) && count($parameters) >= 1) {
                    $description = t($description, $parameters);
                }

                $transaction_amount = 0;
                switch ($item->transaction_type) {
                    case "points_redeemed":
                        $transaction_amount = '<span class="text-danger">' . "-" . Price_Formatter::convertToRaw($item->transaction_amount, 0) . '</span>';
                        break;
                    default:
                        $transaction_amount =  '<span class="text-success"><b>' . "+" . Price_Formatter::convertToRaw($item->transaction_amount, 0) . '</b></span>';
                        break;
                }

                $data[] = [
                    'transaction_id' => $item->transaction_id,
                    'transaction_date' => Date_Formatter::dateTime($item->transaction_date),
                    'card_id' => isset($item->first_name) ? (!empty($item->first_name) ? $item->first_name . " " . $item->last_name : t("Not found")) : t("Not found"),
                    'transaction_description' => $description,
                    'transaction_amount' => $transaction_amount,
                    'running_balance' => '<b>' . Price_Formatter::convertToRaw($item->running_balance, 0) . '</b>',
                ];
            }
        }
        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actionrequestNewOrder()
    {
        try {

            $criteria = new CDbCriteria;
            $criteria->alias = "a";
            $criteria->select = "a.order_id, concat(first_name,' ',last_name) as customer_name";
            $criteria->addCondition("is_view=0 AND a.status!=:status AND DATE_FORMAT(a.date_created,'%Y-%m-%d')=:date_created");
            $criteria->params = [
                ':status' => AttributesTools::initialStatus(),
                ':date_created' => CommonUtility::dateOnly()
            ];
            $criteria->join = '
			LEFT JOIN {{client}} b on a.client_id = b.client_id 			
			';
            $criteria->order = "a.order_id ASC";
            $model = AR_ordernew::model()->findAll($criteria);
            if ($model) {
                $data = [];
                foreach ($model as $item) {
                    $data[] = [
                        'title' => t("You have new order"),
                        'message' => t("Order#{order_id} from {customer_name}", [
                            '{order_id}' => $item->order_id,
                            '{customer_name}' => $item->customer_name
                        ])
                    ];
                }
                $this->code = 1;
                $this->msg = "Ok";
                $this->details = $data;
            } else $this->msg = t("no new order");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiondigitalWalletBonusList()
    {

        $data = array();
        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';

        $sortby = "id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();

        $criteria->alias = "a";
        $criteria->condition = "merchant_id=:merchant_id AND transaction_type=:transaction_type";
        $criteria->params = [
            ':merchant_id' => 0,
            ':transaction_type' => CDigitalWallet::transactionName()
        ];

        $criteria->order = "$sortby $sort";
        $count = AR_discount::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);
        $models = AR_discount::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {

                if ($item->discount_type == "percentage") {
                    $amount = '<b>' . t("{amount} %", [
                            '{amount}' => Price_Formatter::convertToRaw($item->amount, 0)
                        ]) . '</b>';
                } else $amount = '<b>' . Price_Formatter::formatNumber($item->amount) . '</b>';

                $checkbox = Yii::app()->controller->renderPartial('/attributes/html_checkbox', array(
                    'id' => "banner[$item->discount_uuid]",
                    'check' => $item->status == 1 ? true : false,
                    'value' => $item->discount_uuid,
                    'label' => '',
                    'class' => 'set_status'
                ), true);

                $data[] = [
                    'discount_id' => $item->discount_id,
                    'status' => $checkbox,
                    'title' => $item->title,
                    'amount' => $amount,
                    'minimum_amount' => '<b>' . Price_Formatter::formatNumber($item->minimum_amount) . '</b>',
                    'expiration_date' => Date_Formatter::date($item->start_date) . " - " . Date_Formatter::date($item->expiration_date),
                    'update_url' => Yii::app()->createUrl("/digitalwallet/bunos_update/", array('id' => $item->discount_uuid)),
                    'delete_url' => Yii::app()->createUrl("/digitalwallet/bunos_delete/", array('id' => $item->discount_uuid)),
                    'actions' => "setBonusStatus",
                    'id' => $item->discount_uuid,
                ];
            }
        }

        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );

        $this->responseTable($datatables);
    }

    public function actionsetBonusStatus()
    {
        try {

            $id = Yii::app()->input->post('id');
            $status = Yii::app()->input->post('status');
            $model = AR_discount::model()->find("discount_uuid=:discount_uuid", ['discount_uuid' => $id]);
            if ($model) {
                $model->status = $status == "active" ? 1 : 0;
                if ($model->save()) {
                    $this->code = 1;
                    $this->msg = "ok";
                } else $this->msg = t(Helper_failed_update);
            } else $this->msg = t(HELPER_RECORD_NOT_FOUND);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actiondigitalWalletTransactions()
    {

        $data = array();
        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';

        $sortby = "transaction_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.*,b.first_name,b.last_name";

        $criteria->join = "LEFT JOIN {{client}} b on a.card_id = 
		(
		  select card_id from {{wallet_cards}}
		  where account_type=" . q(Yii::app()->params->account_type['digital_wallet']) . " and account_id=b.client_id
		)
		";

        $criteria->addInCondition("a.reference_id1", CDigitalWallet::transactionType());

        $criteria->order = "$sortby $sort";
        $count = AR_wallet_transactions::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $models = AR_wallet_transactions::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {
                $description = Yii::app()->input->xssClean($item->transaction_description);
                $parameters = json_decode($item->transaction_description_parameters, true);
                if (is_array($parameters) && count($parameters) >= 1) {
                    $description = t($description, $parameters);
                }

                $transaction_amount = 0;
                switch ($item->transaction_type) {
                    case "debit":
                        $transaction_amount = '<span class="text-danger">' . "-" . Price_Formatter::formatNumber($item->transaction_amount) . '</span>';
                        break;
                    default:
                        $transaction_amount =  '<span class="text-success"><b>' . "+" . Price_Formatter::formatNumber($item->transaction_amount, 0) . '</b></span>';
                        break;
                }

                $data[] = [
                    'transaction_id' => $item->transaction_id,
                    'transaction_date' => Date_Formatter::dateTime($item->transaction_date),
                    'card_id' => isset($item->first_name) ? (!empty($item->first_name) ? $item->first_name . " " . $item->last_name : t("Not found")) : t("Not found"),
                    'transaction_description' => $description,
                    'transaction_amount' => $transaction_amount,
                    'running_balance' => '<b>' . Price_Formatter::convertToRaw($item->running_balance, 0) . '</b>',
                ];
            }
        }
        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actiondigitalwalletadjustment()
    {
        try {

            $card_id = 0;
            $client_id = isset($this->data['client_id']) ? intval($this->data['client_id']) : 0;
            $transaction_amount = isset($this->data['transaction_amount']) ? floatval($this->data['transaction_amount']) : 0;
            $transaction_description = isset($this->data['transaction_description']) ? $this->data['transaction_description'] : '';
            $transaction_type = isset($this->data['transaction_type']) ? $this->data['transaction_type'] : '';
            $base_currency = Price_Formatter::$number_format['currency_code'];
            $exchange_rate = 1;

            if ($transaction_amount <= 0) {
                $this->msg = t("Invalid amount");
                $this->responseJson();
            }

            $card_id = CWallet::createCard(Yii::app()->params->account_type['digital_wallet'], $client_id);

            $params = [
                'transaction_description' => $transaction_description,
                'transaction_type' => $transaction_type,
                'transaction_amount' => floatval($transaction_amount),
                'status' => 'paid',
                'reference_id1' => CDigitalWallet::transactionName(),
                'merchant_base_currency' => $base_currency,
                'admin_base_currency' => $base_currency,
                'meta_name' => "adjustment",
                'meta_value' => $card_id,
            ];
            $resp = CWallet::inserTransactions($card_id, $params);
            $this->code = 1;
            $this->msg = t("Successful");
            $this->details = $resp;
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actioncustomerbooking()
    {
        $client_id = isset($this->data['ref_id']) ? $this->data['ref_id'] : 0;
        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')  : '';
        $date_start = isset($this->data['date_start']) ? $this->data['date_start'] : '';
        $date_end = isset($this->data['date_end']) ? $this->data['date_end'] : '';
        $filter_id = isset($this->data['filter_id']) ? $this->data['filter_id'] : '';

        $sortby = "reservation_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = intval($page) / intval($length);
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->select = "a.*,
		concat(b.first_name,' ',b.last_name) as full_name,
		c.table_name
		";
        $criteria->join = '
		LEFT JOIN {{client}} b on  a.client_id = b.client_id 
		LEFT JOIN {{table_tables}} c on  a.table_id = c.table_id 
		';
        $criteria->addCondition("a.client_id=:client_id");
        $criteria->params = [
            ':client_id' => $client_id
        ];

        $data = [];
        $status_list = AttributesTools::bookingStatus();

        $criteria->order = "$sortby $sort";
        $count = AR_table_reservation::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);

        $model = AR_table_reservation::model()->findAll($criteria);
        if ($model) {
            foreach ($model as $items) {
                $booking_status = isset($status_list[$items->status]) ? $status_list[$items->status] : $items->status;

                $badge = 'badge-primary';
                $button_color = 'btn-info';
                if ($items->status == "confirmed") {
                    $badge = 'badge-success';
                    $button_color = 'btn-success';
                } else if ($items->status == "cancelled") {
                    $badge = 'badge-danger';
                    $button_color = 'btn-danger';
                } else if ($items->status == "denied") {
                    $badge = 'badge-danger';
                    $button_color = 'btn-danger';
                } else if ($items->status == "finished") {
                    $badge = 'badge-success';
                    $button_color = 'btn-success';
                }

                $special_request = $items->special_request;
                if (!empty($items->cancellation_reason)) {
                    $special_request .= "<p class=\"text-danger\">";
                    $special_request .= t("CANCELLATION NOTES = {cancellation_reason}", [
                        '{cancellation_reason}' => $items->cancellation_reason
                    ]);
                    $special_request .= "</p>";
                }

                $data[] = [
                    'reservation_id' => $items->reservation_id,
                    'client_id' => '<b>' . $items->full_name . '</b></b><span class="badge ml-2 post ' . $badge . '">' . $booking_status . '</span>',
                    'guest_number' => $items->guest_number,
                    'table_id' => $items->table_name,
                    'reservation_date' => Date_Formatter::dateTime($items->reservation_date . " " . $items->reservation_time),
                    'special_request' => $special_request,
                    'status' => '</b></b><span class="badge ml-2 post ' . $badge . '">' . $booking_status . '</span>',
                ];
            }
        }
        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actionpointsThresholds()
    {
        $data = array();
        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';

        $sortby = "transaction_id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        $criteria->condition = "meta_name=:meta_name";
        $criteria->params = [
            ':meta_name' => AttributesTools::pointsThresholds()
        ];

        $criteria->order = "$sortby $sort";
        $count = AR_admin_meta::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);
        $pages->applyLimit($criteria);
        $models = AR_admin_meta::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {


                $edit = Yii::app()->createAbsoluteUrl("/points/update_thresholds", [
                    'id' => $item->meta_id
                ]);
                $delete = Yii::app()->createAbsoluteUrl("/points/delete_thresholds", [
                    'id' => $item->meta_id
                ]);



                $buttons = <<<HTML
<div class="btn-group btn-group-actions" role="group">
 <a href="$edit"  class="btn btn-light tool_tips" data-toggle="tooltip" data-placement="top" >
	<i class="zmdi zmdi-border-color"></i>
</a>
 <a href="$delete"  class="btn btn-light tool_tips"><i class="zmdi zmdi-delete"></i></a> 
</div>
HTML;


                $data[] = [
                    'meta_id' => $item->meta_id,
                    'meta_value' => $item->meta_value,
                    'meta_value1' => $item->meta_value1,
                    'meta_name' => $buttons
                ];
            }
        }
        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actionemailSubscriber()
    {

        $data = array();
        $page = isset($this->data['start']) ? $this->data['start'] : 0;
        $length = isset($this->data['length']) ? $this->data['length'] : 0;
        $draw = isset($this->data['draw']) ? $this->data['draw'] : 0;
        $search = isset($this->data['search']) ? $this->data['search']['value'] : '';
        $columns = isset($this->data['columns']) ? $this->data['columns'] : '';
        $order = isset($this->data['order']) ?  (isset($this->data['order'][0]) ? $this->data['order'][0] : '')   : '';

        $sortby = "id";
        $sort = 'DESC';

        if (is_array($order) && count($order) >= 1) {
            if (array_key_exists($order['column'], (array)$columns)) {
                $sort = $order['dir'];
                $sortby = $columns[$order['column']]['data'];
            }
        }

        $page = $page > 0 ? intval($page) / intval($length) : 0;
        $criteria = new CDbCriteria();
        $criteria->alias = "a";
        $criteria->condition = "merchant_id=:merchant_id AND subcsribe_type=:subcsribe_type";
        $criteria->params = [
            ':merchant_id' => 0,
            ':subcsribe_type' => "website"
        ];
        $criteria->order = "$sortby $sort";
        $count = AR_subscriber::model()->count($criteria);
        $pages = new CPagination(intval($count));
        $pages->setCurrentPage(intval($page));
        $pages->pageSize = intval($length);

        if ($length > 0) {
            $pages->applyLimit($criteria);
        }

        $models = AR_subscriber::model()->findAll($criteria);
        if ($models) {
            foreach ($models as $item) {
                $data[] = [
                    'id' => $item->id,
                    'email_address' => $item->email_address,
                    'ip_address' => $item->ip_address,
                    'delete_url' => Yii::app()->createUrl("/buyer/subscriber_delete/", array('id' => $item->id)),
                ];
            }
        }
        $datatables = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'data' => $data
        );
        $this->responseTable($datatables);
    }

    public function actionSendToKitchen()
    {
        try {

            $this->data = Yii::app()->input->xssClean(json_decode(file_get_contents('php://input'), true));
            $order_uuid = isset($this->data['order_uuid']) ? $this->data['order_uuid'] : '';
            $data = isset($this->data['items']) ? $this->data['items'] : '';
            $order_info = isset($this->data['order_info']) ? $this->data['order_info'] : '';
            $order_table_data = isset($this->data['order_table_data']) ? $this->data['order_table_data'] : '';

            $kitchen_uuid = '';
            $order_reference = '';
            $whento_deliver = '';
            $merchant_uuid = '';
            $merchant_id = '';

            if (is_array($data) && count($data) >= 1) {
                foreach ($data as $items) {
                    $kitchen_uuid = isset($order_info['merchant_uuid']) ? $order_info['merchant_uuid'] : '';
                    $order_reference = isset($order_info['order_id']) ? $order_info['order_id'] : '';
                    $whento_deliver = isset($order_info['whento_deliver']) ? $order_info['whento_deliver'] : '';
                    $merchant_uuid = $kitchen_uuid;
                    $merchant_id = isset($order_info['merchant_id']) ? $order_info['merchant_id'] : '';

                    $modelKitchen = new AR_kitchen_order();
                    $modelKitchen->order_reference = isset($order_info['order_id']) ? $order_info['order_id'] : '';
                    $modelKitchen->merchant_uuid = $kitchen_uuid;
                    $modelKitchen->order_ref_id = $items['item_row'];
                    $modelKitchen->merchant_id = isset($order_info['merchant_id']) ? $order_info['merchant_id'] : '';
                    $modelKitchen->table_uuid = isset($order_table_data['table_uuid']) ? $order_table_data['table_uuid'] : '';
                    $modelKitchen->room_uuid = isset($order_table_data['room_uuid']) ? $order_table_data['room_uuid'] : '';
                    $modelKitchen->item_token = $items['item_token'];
                    $modelKitchen->qty = $items['qty'];
                    $modelKitchen->special_instructions = $items['special_instructions'];
                    $modelKitchen->customer_name = isset($order_info['customer_name']) ? $order_info['customer_name'] : '';
                    $modelKitchen->transaction_type = isset($order_info['order_type']) ? $order_info['order_type'] : '';
                    $modelKitchen->timezone =  isset($order_info['timezone']) ? $order_info['timezone'] : '';
                    $modelKitchen->whento_deliver = isset($order_info['whento_deliver']) ? $order_info['whento_deliver'] : '';
                    $modelKitchen->delivery_date = isset($order_info['delivery_date']) ? $order_info['delivery_date'] : '';
                    $modelKitchen->delivery_time = isset($order_info['delivery_time']) ? $order_info['delivery_time'] : '';

                    $addons = [];
                    $attributes = [];

                    if (is_array($items['addons']) && count($items['addons']) >= 1) {
                        foreach ($items['addons'] as $addons_key => $addons_items) {
                            $addonItems = isset($addons_items['addon_items']) ? $addons_items['addon_items'] : '';
                            if (is_array($addonItems) && count($addonItems) >= 1) {
                                foreach ($addonItems as $addons_items_val) {
                                    $addons[] = [
                                        'subcat_id' => $addons_items['subcat_id'],
                                        'sub_item_id' => $addons_items_val['sub_item_id'],
                                        'qty' => $addons_items_val['qty'],
                                        'multi_option' => $addons_items_val['multiple'],
                                    ];
                                }
                            }
                        }
                    }

                    $modelKitchen->addons = json_encode($addons);

                    if (is_array($items['attributes_raw']) && count($items['attributes_raw']) >= 1) {
                        foreach ($items['attributes_raw'] as $attributes_key => $attributes_items) {
                            if (is_array($attributes_items) && count($attributes_items) >= 1) {
                                foreach ($attributes_items as $meta_id => $attributesItems) {
                                    $attributes[] = [
                                        'meta_name' => $attributes_key,
                                        'meta_id' => $meta_id
                                    ];
                                }
                            }
                        }
                    }

                    $modelKitchen->attributes = json_encode($attributes);
                    $modelKitchen->sequence = CommonUtility::getNextAutoIncrementID('kitchen_order');
                    $modelKitchen->save();
                }
            }

            // SEND NOTIFICATIONS
            if (!empty($kitchen_uuid)) {
                AR_kitchen_order::SendNotifications([
                    'kitchen_uuid' => $kitchen_uuid,
                    'order_reference' => $order_reference,
                    'whento_deliver' => $whento_deliver,
                    'merchant_uuid' => $merchant_uuid,
                    'merchant_id' => $merchant_id
                ]);
            }
            // SEND NOTIFICATIONS

            $this->code = 1;
            $this->msg = t("Order was sent to kitchen succesfully");
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }

    public function actionwifiPrint()
    {
        try {

            $printer_id = Yii::app()->input->post('printerId');
            $order_uuid = Yii::app()->input->post('order_uuid');
            $model = AR_printer::model()->find("printer_id=:printer_id", [
                ':printer_id' => intval($printer_id)
            ]);
            if ($model) {

                COrders::getContent($order_uuid, Yii::app()->language);
                $items = COrders::getItems();
                $summary = COrders::getSummary();
                $order = COrders::orderInfo();
                $order_info = isset($order['order_info']) ? $order['order_info'] : [];
                $merchant_id = isset($order_info['merchant_id']) ? $order_info['merchant_id'] : 0;
                $merchant_info = CMerchants::getMerchantInfo($merchant_id);

                $order_type = $order['order_info']['order_type'];
                $order_table_data = [];
                if ($order_type == "dinein") {
                    $order_table_data = COrders::orderMeta(['table_id', 'room_id', 'guest_number']);
                    $room_id = isset($order_table_data['room_id']) ? $order_table_data['room_id'] : 0;
                    $table_id = isset($order_table_data['table_id']) ? $order_table_data['table_id'] : 0;
                    try {
                        $table_info = CBooking::getTableByID($table_id);
                        $order_table_data['table_name'] = $table_info->table_name;
                    } catch (Exception $e) {
                        $order_table_data['table_name'] = t("Unavailable");
                    }
                    try {
                        $room_info = CBooking::getRoomByID($room_id);
                        $order_table_data['room_name'] = $room_info->room_name;
                    } catch (Exception $e) {
                        $order_table_data['room_name'] = t("Unavailable");
                    }
                }
                $order_info['order_table_data'] = $order_table_data;

                ThermalPrinterFormatter::setPrinter([
                    'ip_address' => $model->service_id,
                    'port' => $model->characteristics,
                    'print_type' => $model->print_type,
                    'character_code' => $model->character_code,
                    'paper_width' => $model->paper_width,
                ]);
                ThermalPrinterFormatter::setItems($items);
                ThermalPrinterFormatter::setSummary($summary);
                ThermalPrinterFormatter::setOrderInfo($order_info);
                ThermalPrinterFormatter::setMerchant($merchant_info);
                $data = ThermalPrinterFormatter::RawReceipt();

                $this->code = 1;
                $this->msg = t("Request succesfully sent to printer");
                $this->details = [
                    'data' => $data
                ];
            } else $this->msg = t(HELPER_RECORD_NOT_FOUND);
        } catch (Exception $e) {
            $this->msg = t($e->getMessage());
        }
        $this->responseJson();
    }
}