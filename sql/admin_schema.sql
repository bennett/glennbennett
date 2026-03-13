-- Admin Panel Schema for glennbennett.com
-- Run against tsgimh_glb1 database

CREATE TABLE IF NOT EXISTS admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS cal_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    image_path VARCHAR(255) NOT NULL DEFAULT 'imgs/cal-backgrounds/',
    original_name VARCHAR(255) NOT NULL,
    width INT UNSIGNED DEFAULT 0,
    height INT UNSIGNED DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT UNSIGNED DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS cal_image_layouts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cal_image_id INT UNSIGNED NOT NULL,
    text_offset INT DEFAULT 0,
    summary_font_size INT UNSIGNED DEFAULT 36,
    summary_margin_top INT UNSIGNED DEFAULT 180,
    date_font_size INT UNSIGNED DEFAULT 24,
    date_margin_top INT UNSIGNED DEFAULT 25,
    time_font_size INT UNSIGNED DEFAULT 36,
    time_margin_top INT UNSIGNED DEFAULT 25,
    location_font_size INT UNSIGNED DEFAULT 24,
    location_margin_top INT UNSIGNED DEFAULT 25,
    FOREIGN KEY (cal_image_id) REFERENCES cal_images(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS venues (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    match_pattern VARCHAR(255) NOT NULL,
    match_type ENUM('exact', 'contains', 'alpha_only') DEFAULT 'exact',
    venue_logo VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS venue_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    venue_id INT UNSIGNED NOT NULL,
    cal_image_id INT UNSIGNED NOT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    FOREIGN KEY (venue_id) REFERENCES venues(id) ON DELETE CASCADE,
    FOREIGN KEY (cal_image_id) REFERENCES cal_images(id) ON DELETE CASCADE,
    UNIQUE KEY unique_venue_image (venue_id, cal_image_id)
);

-- Seed admin user (password: admin123 — change immediately after first login)
INSERT INTO admin_users (username, password_hash) VALUES
('admin', '$2y$12$3vMJtyEjSuFnCbcae0WQTucFIrFDbVm7lQwTA6MeLFPhFhTWvbniu');
