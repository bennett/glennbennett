<?php
// Add image adjustment columns to template_photos and templates tables

return array(
    'up' => function($ci) {
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
    },
    'down' => function($ci) {
        $ci->db->query("
            ALTER TABLE `template_photos`
                DROP COLUMN `brightness`,
                DROP COLUMN `contrast`,
                DROP COLUMN `saturation`,
                DROP COLUMN `sharpen`,
                DROP COLUMN `blur`,
                DROP COLUMN `opacity`,
                DROP COLUMN `sepia`,
                DROP COLUMN `grayscale`,
                DROP COLUMN `hue_rotate`,
                DROP COLUMN `tint_color`,
                DROP COLUMN `tint_amount`
        ");

        $ci->db->query("
            ALTER TABLE `templates`
                DROP COLUMN `brightness`,
                DROP COLUMN `contrast`,
                DROP COLUMN `saturation`,
                DROP COLUMN `sharpen`,
                DROP COLUMN `blur`,
                DROP COLUMN `opacity`,
                DROP COLUMN `sepia`,
                DROP COLUMN `grayscale`,
                DROP COLUMN `hue_rotate`,
                DROP COLUMN `tint_color`,
                DROP COLUMN `tint_amount`
        ");
    },
);
