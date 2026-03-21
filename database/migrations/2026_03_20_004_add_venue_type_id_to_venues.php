<?php
// Add venue_type_id column and FK to venues table

return array(
    'up' => function($ci) {
        $ci->db->query("ALTER TABLE `venues` ADD COLUMN `venue_type_id` INT UNSIGNED DEFAULT NULL AFTER `is_active`");
        $ci->db->query("ALTER TABLE `venues` ADD CONSTRAINT `venues_venue_type_fk` FOREIGN KEY (`venue_type_id`) REFERENCES `venue_types`(`id`) ON DELETE SET NULL");
    },
    'down' => function($ci) {
        $ci->db->query("ALTER TABLE `venues` DROP FOREIGN KEY `venues_venue_type_fk`");
        $ci->db->query("ALTER TABLE `venues` DROP COLUMN `venue_type_id`");
    },
);
