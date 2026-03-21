<?php
// Create venue_details table (1:1 with venues)

return array(
    'up' => function($ci) {
        $ci->db->query("
            CREATE TABLE IF NOT EXISTS `venue_details` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `venue_id` INT UNSIGNED NOT NULL,
                `drive_time_mins` INT UNSIGNED DEFAULT NULL,
                `setup_time_mins` INT UNSIGNED DEFAULT NULL,
                `default_start_time` TIME DEFAULT NULL,
                `default_length_mins` INT UNSIGNED DEFAULT NULL,
                `special_requirements` TEXT,
                `address` VARCHAR(255) DEFAULT NULL,
                `city` VARCHAR(100) DEFAULT NULL,
                `state` VARCHAR(2) DEFAULT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY (`venue_id`),
                FOREIGN KEY (`venue_id`) REFERENCES `venues`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3
        ");
    },
    'down' => function($ci) {
        $ci->db->query("DROP TABLE IF EXISTS `venue_details`");
    },
);
