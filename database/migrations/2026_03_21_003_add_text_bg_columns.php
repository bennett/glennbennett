<?php
// Add text background box columns for semi-transparent glow behind text

return array(
    'up' => function($ci) {
        $ci->db->query("ALTER TABLE templates ADD COLUMN text_bg_opacity TINYINT UNSIGNED DEFAULT 0");
        $ci->db->query("ALTER TABLE templates ADD COLUMN text_bg_color VARCHAR(7) DEFAULT '#ffffff'");
        $ci->db->query("ALTER TABLE template_photos ADD COLUMN text_bg_opacity TINYINT UNSIGNED DEFAULT 0");
        $ci->db->query("ALTER TABLE template_photos ADD COLUMN text_bg_color VARCHAR(7) DEFAULT '#ffffff'");
        $ci->db->query("ALTER TABLE template_backgrounds ADD COLUMN text_bg_opacity TINYINT UNSIGNED DEFAULT 0");
        $ci->db->query("ALTER TABLE template_backgrounds ADD COLUMN text_bg_color VARCHAR(7) DEFAULT '#ffffff'");
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
