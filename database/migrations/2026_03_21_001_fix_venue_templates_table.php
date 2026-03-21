<?php
// Fix: create venue_templates table that failed due to MyISAM FK constraint

return array(
    'up' => function($ci) {
        // venue_type_templates may or may not exist from migration 003
        $ci->db->query("
            CREATE TABLE IF NOT EXISTS `venue_type_templates` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `venue_type_id` INT UNSIGNED NOT NULL,
                `template_id` INT UNSIGNED NOT NULL,
                UNIQUE KEY (`venue_type_id`, `template_id`),
                KEY (`venue_type_id`),
                KEY (`template_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3
        ");

        // This one failed in migration 003 due to venues being MyISAM
        $ci->db->query("
            CREATE TABLE IF NOT EXISTS `venue_templates` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `venue_id` INT UNSIGNED NOT NULL,
                `template_id` INT UNSIGNED NOT NULL,
                UNIQUE KEY (`venue_id`, `template_id`),
                KEY (`venue_id`),
                KEY (`template_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3
        ");
    },
    'down' => function($ci) {
        // Don't drop — migration 003's down handles these
    },
);
