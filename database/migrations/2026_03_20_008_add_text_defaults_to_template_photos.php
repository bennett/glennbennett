<?php
// Add text positioning default columns to template_photos

return array(
    'up' => function($ci) {
        $ci->db->query("
            ALTER TABLE `template_photos`
                ADD COLUMN `text_offset` INT NOT NULL DEFAULT -200 AFTER `tint_amount`,
                ADD COLUMN `summary_margin_top` INT NOT NULL DEFAULT 0 AFTER `text_offset`,
                ADD COLUMN `summary_font_size` INT UNSIGNED NOT NULL DEFAULT 36 AFTER `summary_margin_top`,
                ADD COLUMN `date_font_size` INT UNSIGNED NOT NULL DEFAULT 24 AFTER `summary_font_size`,
                ADD COLUMN `date_margin_top` INT NOT NULL DEFAULT 25 AFTER `date_font_size`,
                ADD COLUMN `time_font_size` INT UNSIGNED NOT NULL DEFAULT 36 AFTER `date_margin_top`,
                ADD COLUMN `time_margin_top` INT NOT NULL DEFAULT 25 AFTER `time_font_size`,
                ADD COLUMN `location_font_size` INT UNSIGNED NOT NULL DEFAULT 24 AFTER `time_margin_top`,
                ADD COLUMN `location_margin_top` INT NOT NULL DEFAULT 25 AFTER `location_font_size`,
                ADD COLUMN `font_color` VARCHAR(7) NOT NULL DEFAULT '#ffffff' AFTER `location_margin_top`,
                ADD COLUMN `glow_radius` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `font_color`,
                ADD COLUMN `glow_color` VARCHAR(7) NOT NULL DEFAULT '#ffffff' AFTER `glow_radius`,
                ADD COLUMN `shadow_offset` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `glow_color`,
                ADD COLUMN `stroke_width` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `shadow_offset`,
                ADD COLUMN `stroke_color` VARCHAR(7) NOT NULL DEFAULT '#000000' AFTER `stroke_width`
        ");
    },
    'down' => function($ci) {
        $ci->db->query("
            ALTER TABLE `template_photos`
                DROP COLUMN `text_offset`,
                DROP COLUMN `summary_margin_top`,
                DROP COLUMN `summary_font_size`,
                DROP COLUMN `date_font_size`,
                DROP COLUMN `date_margin_top`,
                DROP COLUMN `time_font_size`,
                DROP COLUMN `time_margin_top`,
                DROP COLUMN `location_font_size`,
                DROP COLUMN `location_margin_top`,
                DROP COLUMN `font_color`,
                DROP COLUMN `glow_radius`,
                DROP COLUMN `glow_color`,
                DROP COLUMN `shadow_offset`,
                DROP COLUMN `stroke_width`,
                DROP COLUMN `stroke_color`
        ");
    },
);
