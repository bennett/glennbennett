<?php
// Add uid and description columns to share_images for event fallback lookups

return array(
    'up' => function($ci) {
        $cols = array_column($ci->db->query("SHOW COLUMNS FROM `share_images`")->result_array(), 'Field');
        if (!in_array('uid', $cols)) {
            $ci->db->query("ALTER TABLE share_images ADD COLUMN uid VARCHAR(255) NULL AFTER hash");
        }
        if (!in_array('description', $cols)) {
            $ci->db->query("ALTER TABLE share_images ADD COLUMN description TEXT NULL AFTER location");
        }
    },
    'down' => function($ci) {
        $ci->db->query("ALTER TABLE share_images DROP COLUMN uid");
        $ci->db->query("ALTER TABLE share_images DROP COLUMN description");
    },
);
