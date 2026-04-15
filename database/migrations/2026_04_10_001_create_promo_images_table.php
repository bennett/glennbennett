<?php
// Create promo_images table for AI Promo Builder image library

return array(
    'up' => function($ci) {
        $tables = array_column($ci->db->query("SHOW TABLES")->result_array(), 'Tables_in_' . $ci->db->database);
        if (!in_array('promo_images', $tables)) {
            $ci->db->query("
                CREATE TABLE promo_images (
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    filename VARCHAR(255) NOT NULL,
                    original_name VARCHAR(255) NOT NULL,
                    category ENUM('artist', 'venue', 'generic') NOT NULL DEFAULT 'generic',
                    label VARCHAR(255) DEFAULT NULL,
                    width INT UNSIGNED DEFAULT NULL,
                    height INT UNSIGNED DEFAULT NULL,
                    is_active TINYINT(1) NOT NULL DEFAULT 1,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            ");
        }
    },
    'down' => function($ci) {
        $ci->db->query("DROP TABLE IF EXISTS promo_images");
    },
);
