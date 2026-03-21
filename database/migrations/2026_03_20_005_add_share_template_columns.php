<?php
// Add all share template columns to templates, template_photos, and template_backgrounds

return array(
    'up' => function($ci) {

        // --- templates table ---

        // Status flags
        $ci->db->query("ALTER TABLE `templates` ADD COLUMN `is_ready` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_active`");
        $ci->db->query("ALTER TABLE `templates` ADD COLUMN `is_orphaned` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_ready`");

        // Name
        $ci->db->query("ALTER TABLE `templates` ADD COLUMN `name` VARCHAR(255) DEFAULT NULL AFTER `photo_id`");

        // Image adjustments
        $ci->db->query("
            ALTER TABLE `templates`
                ADD COLUMN `brightness` INT DEFAULT 0 AFTER `photo_glow_color`,
                ADD COLUMN `contrast` INT DEFAULT 0 AFTER `brightness`,
                ADD COLUMN `saturation` INT DEFAULT 0 AFTER `contrast`,
                ADD COLUMN `sharpen` INT UNSIGNED DEFAULT 0 AFTER `saturation`,
                ADD COLUMN `blur` INT UNSIGNED DEFAULT 0 AFTER `sharpen`,
                ADD COLUMN `opacity` INT UNSIGNED DEFAULT 100 AFTER `blur`,
                ADD COLUMN `sepia` TINYINT(1) DEFAULT 0 AFTER `opacity`,
                ADD COLUMN `grayscale` TINYINT(1) DEFAULT 0 AFTER `sepia`,
                ADD COLUMN `hue_rotate` INT DEFAULT 0 AFTER `grayscale`,
                ADD COLUMN `tint_color` VARCHAR(7) DEFAULT NULL AFTER `hue_rotate`,
                ADD COLUMN `tint_amount` INT UNSIGNED DEFAULT 0 AFTER `tint_color`
        ");

        // Set default names from bg + photo names
        $ci->db->query("
            UPDATE templates t
            JOIN template_backgrounds bg ON t.background_id = bg.id
            JOIN template_photos p ON t.photo_id = p.id
            SET t.name = CONCAT(bg.original_name, '_', p.original_name)
            WHERE t.name IS NULL
        ");

        // --- template_photos table ---

        // has_defaults flag
        $ci->db->query("ALTER TABLE `template_photos` ADD COLUMN `has_defaults` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_active`");

        // Image adjustments
        $ci->db->query("
            ALTER TABLE `template_photos`
                ADD COLUMN `brightness` INT DEFAULT 0 AFTER `photo_glow_color`,
                ADD COLUMN `contrast` INT DEFAULT 0 AFTER `brightness`,
                ADD COLUMN `saturation` INT DEFAULT 0 AFTER `contrast`,
                ADD COLUMN `sharpen` INT UNSIGNED DEFAULT 0 AFTER `saturation`,
                ADD COLUMN `blur` INT UNSIGNED DEFAULT 0 AFTER `sharpen`,
                ADD COLUMN `opacity` INT UNSIGNED DEFAULT 100 AFTER `blur`,
                ADD COLUMN `sepia` TINYINT(1) DEFAULT 0 AFTER `opacity`,
                ADD COLUMN `grayscale` TINYINT(1) DEFAULT 0 AFTER `sepia`,
                ADD COLUMN `hue_rotate` INT DEFAULT 0 AFTER `grayscale`,
                ADD COLUMN `tint_color` VARCHAR(7) DEFAULT NULL AFTER `hue_rotate`,
                ADD COLUMN `tint_amount` INT UNSIGNED DEFAULT 0 AFTER `tint_color`
        ");

        // Text positioning defaults
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

        // Mark existing photos as having defaults
        $ci->db->query("UPDATE `template_photos` SET `has_defaults` = 1");

        // --- template_backgrounds table ---

        // has_defaults flag
        $ci->db->query("ALTER TABLE `template_backgrounds` ADD COLUMN `has_defaults` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_active`");

        // Mark existing backgrounds as having defaults
        $ci->db->query("UPDATE `template_backgrounds` SET `has_defaults` = 1");
    },

    'down' => function($ci) {

        // --- templates ---
        $ci->db->query("ALTER TABLE `templates` DROP COLUMN `is_ready`");
        $ci->db->query("ALTER TABLE `templates` DROP COLUMN `is_orphaned`");
        $ci->db->query("ALTER TABLE `templates` DROP COLUMN `name`");
        $ci->db->query("
            ALTER TABLE `templates`
                DROP COLUMN `brightness`, DROP COLUMN `contrast`, DROP COLUMN `saturation`,
                DROP COLUMN `sharpen`, DROP COLUMN `blur`, DROP COLUMN `opacity`,
                DROP COLUMN `sepia`, DROP COLUMN `grayscale`, DROP COLUMN `hue_rotate`,
                DROP COLUMN `tint_color`, DROP COLUMN `tint_amount`
        ");

        // --- template_photos ---
        $ci->db->query("ALTER TABLE `template_photos` DROP COLUMN `has_defaults`");
        $ci->db->query("
            ALTER TABLE `template_photos`
                DROP COLUMN `brightness`, DROP COLUMN `contrast`, DROP COLUMN `saturation`,
                DROP COLUMN `sharpen`, DROP COLUMN `blur`, DROP COLUMN `opacity`,
                DROP COLUMN `sepia`, DROP COLUMN `grayscale`, DROP COLUMN `hue_rotate`,
                DROP COLUMN `tint_color`, DROP COLUMN `tint_amount`
        ");
        $ci->db->query("
            ALTER TABLE `template_photos`
                DROP COLUMN `text_offset`, DROP COLUMN `summary_margin_top`, DROP COLUMN `summary_font_size`,
                DROP COLUMN `date_font_size`, DROP COLUMN `date_margin_top`,
                DROP COLUMN `time_font_size`, DROP COLUMN `time_margin_top`,
                DROP COLUMN `location_font_size`, DROP COLUMN `location_margin_top`,
                DROP COLUMN `font_color`, DROP COLUMN `glow_radius`, DROP COLUMN `glow_color`,
                DROP COLUMN `shadow_offset`, DROP COLUMN `stroke_width`, DROP COLUMN `stroke_color`
        ");

        // --- template_backgrounds ---
        $ci->db->query("ALTER TABLE `template_backgrounds` DROP COLUMN `has_defaults`");
    },
);
