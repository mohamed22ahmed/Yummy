CREATE TABLE st_city_boundaries (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
   `city_id` INT(11) NOT NULL DEFAULT 0,
    latitude DOUBLE NOT NULL,
    longitude DOUBLE NOT NULL,
    FOREIGN KEY (city_id) REFERENCES st_location_cities(city_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) engine=innodb
DEFAULT charset=utf8
COLLATE=utf8_general_ci;




