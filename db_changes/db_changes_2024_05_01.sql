ALTER TABLE st_location_cities
ADD COLUMN latitude DECIMAL(10, 8) AFTER sequence,
ADD COLUMN longitude DECIMAL(11, 8) AFTER latitude;



ALTER TABLE st_zones
    ADD COLUMN `city_id` int(11) NOT NULL,
    ADD CONSTRAINT fk_zone_city FOREIGN KEY (city_id) REFERENCES st_location_cities(city_id),
    ADD COLUMN mode ENUM('delivery', 'driver'),
    ADD COLUMN `quardinates` text;


ALTER TABLE st_merchant
    ADD COLUMN `city_id` int(11) AFTER `package_id`,
    ADD CONSTRAINT fk_merchant_city FOREIGN KEY (city_id) REFERENCES st_location_cities(city_id),
    ADD COLUMN ar_address text AFTER `address`;



ALTER TABLE st_shipping_rate ADD COLUMN shipping_rate_zone_values VARCHAR(255);
