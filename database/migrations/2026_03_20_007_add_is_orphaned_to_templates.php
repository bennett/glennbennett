<?php
// Add is_orphaned flag to templates table

return array(
    'up' => function($ci) {
        $ci->db->query("ALTER TABLE `templates` ADD COLUMN `is_orphaned` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_ready`");
    },
    'down' => function($ci) {
        $ci->db->query("ALTER TABLE `templates` DROP COLUMN `is_orphaned`");
    },
);
