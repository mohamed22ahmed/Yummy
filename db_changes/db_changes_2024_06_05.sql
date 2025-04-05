CREATE TABLE st_distance_cache (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    from_lat DOUBLE NOT NULL,
    from_lng DOUBLE NOT NULL,
    to_lat DOUBLE NOT NULL,
    to_lng DOUBLE NOT NULL,
    place_id VARCHAR(255),
    unit VARCHAR(50),
    mode VARCHAR(50),
    distance_covered DOUBLE,
    cached_date DATETIME NOT NULL
) engine=innodb
DEFAULT charset=utf8
COLLATE=utf8_general_ci;