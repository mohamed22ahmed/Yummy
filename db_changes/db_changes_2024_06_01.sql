CREATE TABLE `st_city_discount`
(
    `city_discount_id`  INT(11) NOT NULL auto_increment,
    `city_id`       INT(11) NOT NULL DEFAULT 0,
    `discount_percentage` DECIMAL(5, 2) NOT NULL,
    `expiration`        DATE DEFAULT NULL,
    `status`            VARCHAR(100) NOT NULL DEFAULT '',
    `date_created`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `date_modified`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `ip_address`        VARCHAR(100) NOT NULL DEFAULT '',
    PRIMARY KEY (`city_discount_id`),
    KEY `status` (`status`),
    FOREIGN KEY (city_id) REFERENCES st_location_cities(city_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)engine=innodb
DEFAULT charset=utf8
COLLATE=utf8_general_ci;


CREATE TABLE `st_city_delivery_discount`
(
    `city_delivery_discount_id`  INT(11) NOT NULL auto_increment,
    `city_id`       INT(11) NOT NULL DEFAULT 0,
    `discount_level` ENUM('FIRST_ORDER', 'SECOND_ORDER', 'THIRD_ORDER') NOT NULL DEFAULT 'FIRST_ORDER',
    `discount_amount` DECIMAL(10, 2) NOT NULL,
    `expiration`        DATE DEFAULT NULL,
    `status`            VARCHAR(100) NOT NULL DEFAULT '',
    `is_forced`         TINYINT(1) NOT NULL DEFAULT 0,
    `date_created`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `date_modified`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `ip_address`        VARCHAR(100) NOT NULL DEFAULT '',
    PRIMARY KEY (`city_delivery_discount_id`),
    KEY `status` (`status`),
    FOREIGN KEY (city_id) REFERENCES st_location_cities(city_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)engine=innodb
DEFAULT charset=utf8
COLLATE=utf8_general_ci;
