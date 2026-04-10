<?php
// Add text background box columns for semi-transparent glow behind text

return array(
    'up' => function($ci) {
        $has_col = function($table, $col) use ($ci) {
            $cols = array_column($ci->db->query("SHOW COLUMNS FROM `{$table}`")->result_array(), 'Field');
            return in_array($col, $cols);
        };
        $tables = array('templates', 'template_photos', 'template_backgrounds');
        foreach ($tables as $table) {
            if (!$has_col($table, 'text_bg_opacity')) {
                $ci->db->query("ALTER TABLE `{$table}` ADD COLUMN text_bg_opacity TINYINT UNSIGNED DEFAULT 0");
                $ci->db->query("ALTER TABLE `{$table}` ADD COLUMN text_bg_color VARCHAR(7) DEFAULT '#ffffff'");
            }
        }
    },
    'down' => function($ci) {
        $ci->db->query("ALTER TABLE templates DROP COLUMN text_bg_opacity");
        $ci->db->query("ALTER TABLE templates DROP COLUMN text_bg_color");
        $ci->db->query("ALTER TABLE template_photos DROP COLUMN text_bg_opacity");
        $ci->db->query("ALTER TABLE template_photos DROP COLUMN text_bg_color");
        $ci->db->query("ALTER TABLE template_backgrounds DROP COLUMN text_bg_opacity");
        $ci->db->query("ALTER TABLE template_backgrounds DROP COLUMN text_bg_color");
    },
);
