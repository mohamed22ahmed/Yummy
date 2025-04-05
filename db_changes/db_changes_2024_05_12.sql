-- Alter the table to add ENUM columns
ALTER TABLE st_merchant
    ADD COLUMN `type` ENUM('RESTAURANT', 'SUPERMARKET', 'HOME_COOKING', 'CHAIN', 'SUPERMARKET_CHAIN', 'CHILD') DEFAULT 'RESTAURANT' AFTER restaurant_phone,
    ADD COLUMN `view_type` ENUM('LIST', 'GRID') DEFAULT 'LIST' AFTER `type`,
    ADD COLUMN parent_id INT AFTER view_type,
    ADD CONSTRAINT fk_parent_id FOREIGN KEY (parent_id) REFERENCES st_merchant(merchant_id);


ALTER TABLE st_order_time_management ADD COLUMN locations VARCHAR(255);


ALTER TABLE st_pages ADD COLUMN locations VARCHAR(255);


ALTER TABLE st_size ADD COLUMN locations VARCHAR(255);


ALTER TABLE st_ingredients ADD COLUMN locations VARCHAR(255);


ALTER TABLE st_cooking_ref ADD COLUMN locations VARCHAR(255);

ALTER TABLE st_category ADD COLUMN locations VARCHAR(255);


ALTER TABLE st_subcategory ADD COLUMN locations VARCHAR(255);


ALTER TABLE st_subcategory_item ADD COLUMN locations VARCHAR(255);

ALTER TABLE st_item ADD COLUMN locations VARCHAR(255);