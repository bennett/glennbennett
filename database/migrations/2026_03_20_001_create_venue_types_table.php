<?php
// Create venue_types table with seed data

return array(
    'up' => function($ci) {
        $ci->db->query("
            CREATE TABLE IF NOT EXISTS `venue_types` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL,
                `slug` VARCHAR(100) NOT NULL,
                `sort_order` INT UNSIGNED DEFAULT 0,
                `is_active` TINYINT(1) DEFAULT 1,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3
        ");

        $ci->db->insert_batch('venue_types', array(
            array('name' => 'General', 'slug' => 'general', 'sort_order' => 0),
            array('name' => 'Farmers Market', 'slug' => 'farmers-market', 'sort_order' => 1),
            array('name' => 'Street Fair', 'slug' => 'street-fair', 'sort_order' => 2),
            array('name' => 'Craft Fair', 'slug' => 'craft-fair', 'sort_order' => 3),
            array('name' => 'Winery', 'slug' => 'winery', 'sort_order' => 4),
            array('name' => 'Brewery', 'slug' => 'brewery', 'sort_order' => 5),
        ));
    },
    'down' => function($ci) {
        $ci->db->query("DROP TABLE IF EXISTS `venue_types`");
    },
);
