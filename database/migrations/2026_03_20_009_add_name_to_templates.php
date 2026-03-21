<?php
// Add name column to templates

return array(
    'up' => function($ci) {
        $ci->db->query("
            ALTER TABLE `templates`
                ADD COLUMN `name` VARCHAR(255) DEFAULT NULL AFTER `photo_id`
        ");

        // Set default names from bg + photo names
        $ci->db->query("
            UPDATE templates t
            JOIN template_backgrounds bg ON t.background_id = bg.id
            JOIN template_photos p ON t.photo_id = p.id
            SET t.name = CONCAT(bg.original_name, '_', p.original_name)
            WHERE t.name IS NULL
        ");
    },
    'down' => function($ci) {
        $ci->db->query("
            ALTER TABLE `templates`
                DROP COLUMN `name`
        ");
    },
);
