DROP TABLE IF EXISTS st_applicable_discounts_materialized;


CREATE TABLE `st_applicable_discounts_materialized` (
                                                        `client_id` INT(11) NOT NULL,
                                                        `merchant_id` INT(11) NOT NULL,
                                                        `total_order_count` INT(11) DEFAULT 0,
                                                        `merchant_discount_amount` DECIMAL(10, 2) DEFAULT 0.00,
                                                        `system_discount_amount` DECIMAL(10, 2) DEFAULT 0.00,
                                                        `city_discount_percentage` DECIMAL(10, 2) DEFAULT 0.00,
                                                        `merchant_free_delivery_forced` TINYINT(1) DEFAULT 0,
                                                        `system_discount_is_forced` TINYINT(1) DEFAULT 0,
                                                        PRIMARY KEY (`client_id`, `merchant_id`),  -- Composite primary key
                                                        INDEX (`merchant_id`),
                                                        INDEX (`client_id`)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;


REPLACE INTO st_applicable_discounts_materialized (
    client_id,
    merchant_id,
    total_order_count,
    merchant_discount_amount,
    system_discount_amount,
    city_discount_percentage,
    merchant_free_delivery_forced,
    system_discount_is_forced
)
SELECT
    c.client_id,
    m.merchant_id,
    COALESCE(total_orders_per_merchant.total_order_count, 0) AS total_order_count,
    COALESCE(
            CASE
                WHEN COALESCE(total_orders_per_merchant.total_order_count, 0) = 0
                    AND COALESCE(md.merchant_discount_amount_first_order, 0) > 0 THEN md.merchant_discount_amount_first_order
                WHEN COALESCE(total_orders_per_merchant.total_order_count, 0) = 1
                    AND COALESCE(md.merchant_discount_amount_second_order, 0) > 0 THEN md.merchant_discount_amount_second_order
                WHEN COALESCE(total_orders_per_merchant.total_order_count, 0) = 2
                    AND COALESCE(md.merchant_discount_amount_third_order, 0) > 0 THEN md.merchant_discount_amount_third_order
                ELSE 0
                END,
            0
    ) AS merchant_discount_amount,
    COALESCE(
            CASE
                WHEN COALESCE(total_orders_system.total_order_count_per_client, 0) = 0
                    AND COALESCE(sd.system_discount_amount_first_order, 0) > 0 THEN sd.system_discount_amount_first_order
                WHEN COALESCE(total_orders_system.total_order_count_per_client, 0) = 1
                    AND COALESCE(sd.system_discount_amount_second_order, 0) > 0 THEN sd.system_discount_amount_second_order
                WHEN COALESCE(total_orders_system.total_order_count_per_client, 0) = 2
                    AND COALESCE(sd.system_discount_amount_third_order, 0) > 0 THEN sd.system_discount_amount_third_order
                ELSE 0
                END,
            0
    ) AS system_discount_amount,
    COALESCE(cd.city_discount_percentage, 0) AS city_discount_percentage,
    COALESCE(md.merchant_free_delivery_forced, 0) AS merchant_free_delivery_forced,
    COALESCE(sd.is_forced, 0) AS system_discount_is_forced
FROM
    (
        SELECT client_id FROM st_client
        UNION ALL
        SELECT 0 AS client_id -- Add the non-logged-in user with client_id = NULL
    ) c
        JOIN
    st_merchant m ON 1=1 -- Join every client (including non-logged-in) with every merchant
        LEFT JOIN
    (
        SELECT
            o.merchant_id,
            o.client_id,
            COUNT(o.order_id) AS total_order_count
        FROM
            st_ordernew o
        WHERE
            o.status NOT IN ('cancelled', 'delivery failed', 'draft', 'rejected')
        GROUP BY
            o.merchant_id, o.client_id
    ) AS total_orders_per_merchant
    ON m.merchant_id = total_orders_per_merchant.merchant_id
        AND c.client_id = total_orders_per_merchant.client_id
        LEFT JOIN
    (
        SELECT
            opt.merchant_id,
            MAX(CASE WHEN opt.option_name = 'free_delivery_on_first_order' AND opt.option_value = '1' THEN
                         CAST((SELECT MAX(CASE WHEN o.option_name = 'first_order_free_delivery_up_to_amount' THEN o.option_value ELSE NULL END) FROM st_option o WHERE o.merchant_id = opt.merchant_id) AS UNSIGNED)
                     ELSE 0 END) AS merchant_discount_amount_first_order,
            MAX(CASE WHEN opt.option_name = 'free_delivery_on_second_order' AND opt.option_value = '1' THEN
                         CAST((SELECT MAX(CASE WHEN o.option_name = 'second_order_free_delivery_up_to_amount' THEN o.option_value ELSE NULL END) FROM st_option o WHERE o.merchant_id = opt.merchant_id) AS UNSIGNED)
                     ELSE 0 END) AS merchant_discount_amount_second_order,
            MAX(CASE WHEN opt.option_name = 'free_delivery_on_third_order' AND opt.option_value = '1' THEN
                         CAST((SELECT MAX(CASE WHEN o.option_name = 'third_order_free_delivery_up_to_amount' THEN o.option_value ELSE NULL END) FROM st_option o WHERE o.merchant_id = opt.merchant_id) AS UNSIGNED)
                     ELSE 0 END) AS merchant_discount_amount_third_order,
            MAX(IF(opt.option_name = 'merchant_free_delivery_forced' AND opt.option_value = '1', 1, 0)) AS merchant_free_delivery_forced
        FROM
            st_option opt
        GROUP BY
            opt.merchant_id
    ) AS md
    ON m.merchant_id = md.merchant_id
        LEFT JOIN
    (
        SELECT
            o.client_id,
            COUNT(o.order_id) AS total_order_count_per_client
        FROM
            st_ordernew o
        WHERE
            o.status NOT IN ('cancelled', 'delivery failed', 'draft', 'rejected')
        GROUP BY o.client_id
    ) AS total_orders_system
    ON c.client_id = total_orders_system.client_id
        LEFT JOIN
    (
        SELECT
            d.city_id,
            SUM(CASE WHEN d.discount_level = 'FIRST_ORDER' THEN d.discount_amount ELSE 0 END) AS system_discount_amount_first_order,
            SUM(CASE WHEN d.discount_level = 'SECOND_ORDER' THEN d.discount_amount ELSE 0 END) AS system_discount_amount_second_order,
            SUM(CASE WHEN d.discount_level = 'THIRD_ORDER' THEN d.discount_amount ELSE 0 END) AS system_discount_amount_third_order,
            MAX(d.is_forced) AS is_forced
        FROM
            st_city_delivery_discount d
        WHERE
            d.status = 'publish'
          AND d.expiration > NOW()
        GROUP BY d.city_id
    ) AS sd
    ON m.city_id = sd.city_id
        LEFT JOIN
    (
        SELECT
            d.city_id,
            d.discount_percentage AS city_discount_percentage
        FROM
            st_city_discount d
        WHERE
            d.status = 'publish'
          AND d.expiration > NOW()
    ) AS cd
    ON m.city_id = cd.city_id;





SET GLOBAL event_scheduler = ON;
DROP EVENT IF EXISTS refresh_st_applicable_discounts;


DELIMITER $$

CREATE EVENT IF NOT EXISTS refresh_st_applicable_discounts
ON SCHEDULE EVERY 1 MINUTE
DO
BEGIN
REPLACE INTO st_applicable_discounts_materialized (
    client_id,
    merchant_id,
    total_order_count,
    merchant_discount_amount,
    system_discount_amount,
    city_discount_percentage,
    merchant_free_delivery_forced,
    system_discount_is_forced
)
SELECT
    c.client_id,
    m.merchant_id,
    COALESCE(total_orders_per_merchant.total_order_count, 0) AS total_order_count,
    COALESCE(
            CASE
                WHEN COALESCE(total_orders_per_merchant.total_order_count, 0) = 0
                    AND COALESCE(md.merchant_discount_amount_first_order, 0) > 0 THEN md.merchant_discount_amount_first_order
                WHEN COALESCE(total_orders_per_merchant.total_order_count, 0) = 1
                    AND COALESCE(md.merchant_discount_amount_second_order, 0) > 0 THEN md.merchant_discount_amount_second_order
                WHEN COALESCE(total_orders_per_merchant.total_order_count, 0) = 2
                    AND COALESCE(md.merchant_discount_amount_third_order, 0) > 0 THEN md.merchant_discount_amount_third_order
                ELSE 0
                END,
            0
    ) AS merchant_discount_amount,
    COALESCE(
            CASE
                WHEN COALESCE(total_orders_system.total_order_count_per_client, 0) = 0
                    AND COALESCE(sd.system_discount_amount_first_order, 0) > 0 THEN sd.system_discount_amount_first_order
                WHEN COALESCE(total_orders_system.total_order_count_per_client, 0) = 1
                    AND COALESCE(sd.system_discount_amount_second_order, 0) > 0 THEN sd.system_discount_amount_second_order
                WHEN COALESCE(total_orders_system.total_order_count_per_client, 0) = 2
                    AND COALESCE(sd.system_discount_amount_third_order, 0) > 0 THEN sd.system_discount_amount_third_order
                ELSE 0
                END,
            0
    ) AS system_discount_amount,
    COALESCE(cd.city_discount_percentage, 0) AS city_discount_percentage,
    COALESCE(md.merchant_free_delivery_forced, 0) AS merchant_free_delivery_forced,
    COALESCE(sd.is_forced, 0) AS system_discount_is_forced
FROM
    (
        SELECT client_id FROM st_client
        UNION ALL
        SELECT 0 AS client_id -- Add the non-logged-in user with client_id = NULL
    ) c
        JOIN
    st_merchant m ON 1=1 -- Join every client (including non-logged-in) with every merchant
        LEFT JOIN
    (
        SELECT
            o.merchant_id,
            o.client_id,
            COUNT(o.order_id) AS total_order_count
        FROM
            st_ordernew o
        WHERE
            o.status NOT IN ('cancelled', 'delivery failed', 'draft', 'rejected')
        GROUP BY
            o.merchant_id, o.client_id
    ) AS total_orders_per_merchant
    ON m.merchant_id = total_orders_per_merchant.merchant_id
        AND c.client_id = total_orders_per_merchant.client_id
        LEFT JOIN
    (
        SELECT
            opt.merchant_id,
            MAX(CASE WHEN opt.option_name = 'free_delivery_on_first_order' AND opt.option_value = '1' THEN
                         CAST((SELECT MAX(CASE WHEN o.option_name = 'first_order_free_delivery_up_to_amount' THEN o.option_value ELSE NULL END) FROM st_option o WHERE o.merchant_id = opt.merchant_id) AS UNSIGNED)
                     ELSE 0 END) AS merchant_discount_amount_first_order,
            MAX(CASE WHEN opt.option_name = 'free_delivery_on_second_order' AND opt.option_value = '1' THEN
                         CAST((SELECT MAX(CASE WHEN o.option_name = 'second_order_free_delivery_up_to_amount' THEN o.option_value ELSE NULL END) FROM st_option o WHERE o.merchant_id = opt.merchant_id) AS UNSIGNED)
                     ELSE 0 END) AS merchant_discount_amount_second_order,
            MAX(CASE WHEN opt.option_name = 'free_delivery_on_third_order' AND opt.option_value = '1' THEN
                         CAST((SELECT MAX(CASE WHEN o.option_name = 'third_order_free_delivery_up_to_amount' THEN o.option_value ELSE NULL END) FROM st_option o WHERE o.merchant_id = opt.merchant_id) AS UNSIGNED)
                     ELSE 0 END) AS merchant_discount_amount_third_order,
            MAX(IF(opt.option_name = 'merchant_free_delivery_forced' AND opt.option_value = '1', 1, 0)) AS merchant_free_delivery_forced
        FROM
            st_option opt
        GROUP BY
            opt.merchant_id
    ) AS md
    ON m.merchant_id = md.merchant_id
        LEFT JOIN
    (
        SELECT
            o.client_id,
            COUNT(o.order_id) AS total_order_count_per_client
        FROM
            st_ordernew o
        WHERE
            o.status NOT IN ('cancelled', 'delivery failed', 'draft', 'rejected')
        GROUP BY o.client_id
    ) AS total_orders_system
    ON c.client_id = total_orders_system.client_id
        LEFT JOIN
    (
        SELECT
            d.city_id,
            SUM(CASE WHEN d.discount_level = 'FIRST_ORDER' THEN d.discount_amount ELSE 0 END) AS system_discount_amount_first_order,
            SUM(CASE WHEN d.discount_level = 'SECOND_ORDER' THEN d.discount_amount ELSE 0 END) AS system_discount_amount_second_order,
            SUM(CASE WHEN d.discount_level = 'THIRD_ORDER' THEN d.discount_amount ELSE 0 END) AS system_discount_amount_third_order,
            MAX(d.is_forced) AS is_forced
        FROM
            st_city_delivery_discount d
        WHERE
            d.status = 'publish'
          AND d.expiration > NOW()
        GROUP BY d.city_id
    ) AS sd
    ON m.city_id = sd.city_id
        LEFT JOIN
    (
        SELECT
            d.city_id,
            d.discount_percentage AS city_discount_percentage
        FROM
            st_city_discount d
        WHERE
            d.status = 'publish'
          AND d.expiration > NOW()
    ) AS cd
    ON m.city_id = cd.city_id;
    END$$

DELIMITER ;



