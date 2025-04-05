<?php
class AR_applicable_discount extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{applicable_discounts_materialized}}';
    }

    public static function getApplicableDiscount($client_id, $merchant_id) {
        $sql = "
            SELECT 
                CASE
                    WHEN merchant_discount_amount > 0 AND merchant_free_delivery_forced = 1 AND city_discount_percentage > 0 THEN CONCAT('merchant_discount_plus_delivery - ', CASE WHEN total_order_count = 0 THEN 'First Discount' WHEN total_order_count = 1 THEN 'Second Discount' WHEN total_order_count = 2 THEN 'Third Discount' ELSE 'No Applicable Discount' END)
                    WHEN merchant_discount_amount > 0 AND merchant_free_delivery_forced = 1 THEN CONCAT('merchant_discount - ', CASE WHEN total_order_count = 0 THEN 'First Discount' WHEN total_order_count = 1 THEN 'Second Discount' WHEN total_order_count = 2 THEN 'Third Discount' ELSE 'No Applicable Discount' END)
                    WHEN system_discount_amount > 0 AND system_discount_is_forced = 1 AND city_discount_percentage > 0 THEN CONCAT('system_discount_plus_delivery - ', CASE WHEN total_order_count = 0 THEN 'First Discount' WHEN total_order_count = 1 THEN 'Second Discount' WHEN total_order_count = 2 THEN 'Third Discount' ELSE 'No Applicable Discount' END)
                    WHEN system_discount_amount > 0 AND system_discount_is_forced = 1 THEN CONCAT('system_discount - ', CASE WHEN total_order_count = 0 THEN 'First Discount' WHEN total_order_count = 1 THEN 'Second Discount' WHEN total_order_count = 2 THEN 'Third Discount' ELSE 'No Applicable Discount' END)
                    WHEN merchant_discount_amount > 0 AND city_discount_percentage > 0 THEN CONCAT('merchant_discount_plus_delivery - ', CASE WHEN total_order_count = 0 THEN 'First Discount' WHEN total_order_count = 1 THEN 'Second Discount' WHEN total_order_count = 2 THEN 'Third Discount' ELSE 'No Applicable Discount' END)
                    WHEN merchant_discount_amount > 0 THEN CONCAT('merchant_discount - ', CASE WHEN total_order_count = 0 THEN 'First Discount' WHEN total_order_count = 1 THEN 'Second Discount' WHEN total_order_count = 2 THEN 'Third Discount' ELSE 'No Applicable Discount' END)
                    WHEN system_discount_amount > 0 AND city_discount_percentage > 0 THEN CONCAT('system_discount_plus_delivery - ', CASE WHEN total_order_count = 0 THEN 'First Discount' WHEN total_order_count = 1 THEN 'Second Discount' WHEN total_order_count = 2 THEN 'Third Discount' ELSE 'No Applicable Discount' END)
                    WHEN system_discount_amount > 0 THEN CONCAT('system_discount - ', CASE WHEN total_order_count = 0 THEN 'First Discount' WHEN total_order_count = 1 THEN 'Second Discount' WHEN total_order_count = 2 THEN 'Third Discount' ELSE 'No Applicable Discount' END)
                    WHEN city_discount_percentage > 0 THEN 'delivery_discount'
                    ELSE 'no_discount'
                END AS applicable_discount_type,
                merchant_discount_amount,
                system_discount_amount,
                city_discount_percentage
            FROM st_applicable_discounts_materialized
            WHERE client_id = :client_id AND merchant_id = :merchant_id
        ";

        return Yii::app()->db->createCommand($sql)
            ->bindParam(':client_id', $client_id, PDO::PARAM_INT)
            ->bindParam(':merchant_id', $merchant_id, PDO::PARAM_INT)
            ->queryRow();
    }
}
