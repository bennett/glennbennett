<?php
// Add is_ready flag to templates table

return array(
    'up' => function($ci) {
        $ci->db->query("ALTER TABLE `templates` ADD COLUMN `is_ready` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_active`");
    },
    'down' => function($ci) {
        $ci->db->query("ALTER TABLE `templates` DROP COLUMN `is_ready`");
    },
);
