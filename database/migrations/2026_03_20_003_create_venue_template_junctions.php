<?php
// Create venue_type_templates and venue_templates junction tables

return array(
    'up' => function($ci) {
        $ci->db->query("
            CREATE TABLE IF NOT EXISTS `venue_type_templates` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `venue_type_id` INT UNSIGNED NOT NULL,
                `template_id` INT UNSIGNED NOT NULL,
                UNIQUE KEY (`venue_type_id`, `template_id`),
                FOREIGN KEY (`venue_type_id`) REFERENCES `venue_types`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`template_id`) REFERENCES `templates`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3
        ");

        $ci->db->query("
            CREATE TABLE IF NOT EXISTS `venue_templates` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `venue_id` INT UNSIGNED NOT NULL,
                `template_id` INT UNSIGNED NOT NULL,
                UNIQUE KEY (`venue_id`, `template_id`),
                FOREIGN KEY (`venue_id`) REFERENCES `venues`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`template_id`) REFERENCES `templates`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3
        ");
    },
    'down' => function($ci) {
        $ci->db->query("DROP TABLE IF EXISTS `venue_templates`");
        $ci->db->query("DROP TABLE IF EXISTS `venue_type_templates`");
    },
);
