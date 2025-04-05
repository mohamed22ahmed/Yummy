-- Adding a new column to the st_cuisine table
ALTER TABLE st_cuisine
    ADD COLUMN type INT(11) DEFAULT 0 AFTER `ip_address`;

-- Adding multiple columns to the st_ordernew table
ALTER TABLE st_ordernew
    ADD COLUMN prep_time INT(11) DEFAULT 0,
    ADD COLUMN actual_prep_time INT(11) DEFAULT 0,
    ADD COLUMN prep_time_enabled_at DATETIME DEFAULT NULL AFTER `order_reference`;

-- Adding multiple columns to the st_tags table
ALTER TABLE st_tags
    ADD COLUMN type INT(11) DEFAULT 0 AFTER `ip_address`,
    ADD COLUMN featured_image VARCHAR(255) DEFAULT NULL AFTER `type`,
    ADD COLUMN path VARCHAR(255) DEFAULT NULL AFTER `featured_image`;

-- Adding additional columns to the st_tags table
ALTER TABLE st_tags
    ADD COLUMN up_to FLOAT(14, 4) DEFAULT NULL AFTER `path`,
    ADD COLUMN discount_delivery INT(11) DEFAULT 0 AFTER `up_to`,
    ADD COLUMN delivery_cost_payer INT(11) DEFAULT NULL AFTER `discount_delivery`,
    ADD COLUMN paying_way_merchant INT(11) DEFAULT NULL AFTER `delivery_cost_payer`,
    ADD COLUMN yummy_pay_percentage INT(11) DEFAULT NULL AFTER `paying_way_merchant`,
    ADD COLUMN merchant_pay_percentage INT(11) DEFAULT NULL AFTER `yummy_pay_percentage`;
