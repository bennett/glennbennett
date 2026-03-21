-- Venue Types, Details, and Template Assignment tables
-- Run on local + production

-- Venue Types
CREATE TABLE venue_types (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(100) NOT NULL,
  sort_order INT UNSIGNED DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

INSERT INTO venue_types (name, slug, sort_order) VALUES
('General', 'general', 0),
('Farmers Market', 'farmers-market', 1),
('Street Fair', 'street-fair', 2),
('Craft Fair', 'craft-fair', 3),
('Winery', 'winery', 4),
('Brewery', 'brewery', 5);

-- Venue Details (1:1 with venues)
CREATE TABLE venue_details (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  venue_id INT UNSIGNED NOT NULL,
  drive_time_mins INT UNSIGNED DEFAULT NULL,
  setup_time_mins INT UNSIGNED DEFAULT NULL,
  default_start_time TIME DEFAULT NULL,
  default_length_mins INT UNSIGNED DEFAULT NULL,
  special_requirements TEXT,
  address VARCHAR(255) DEFAULT NULL,
  city VARCHAR(100) DEFAULT NULL,
  state VARCHAR(2) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY (venue_id),
  FOREIGN KEY (venue_id) REFERENCES venues(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Venue Type → Template junction
CREATE TABLE venue_type_templates (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  venue_type_id INT UNSIGNED NOT NULL,
  template_id INT UNSIGNED NOT NULL,
  UNIQUE KEY (venue_type_id, template_id),
  FOREIGN KEY (venue_type_id) REFERENCES venue_types(id) ON DELETE CASCADE,
  FOREIGN KEY (template_id) REFERENCES templates(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Venue → Template junction
CREATE TABLE venue_templates (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  venue_id INT UNSIGNED NOT NULL,
  template_id INT UNSIGNED NOT NULL,
  UNIQUE KEY (venue_id, template_id),
  FOREIGN KEY (venue_id) REFERENCES venues(id) ON DELETE CASCADE,
  FOREIGN KEY (template_id) REFERENCES templates(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Add venue_type_id to venues
ALTER TABLE venues ADD COLUMN venue_type_id INT UNSIGNED DEFAULT NULL AFTER is_active;
ALTER TABLE venues ADD CONSTRAINT venues_venue_type_fk FOREIGN KEY (venue_type_id) REFERENCES venue_types(id) ON DELETE SET NULL;

-- Photo image adjustment columns
ALTER TABLE template_photos
  ADD COLUMN brightness INT DEFAULT 0 AFTER photo_glow_color,
  ADD COLUMN contrast INT DEFAULT 0 AFTER brightness,
  ADD COLUMN saturation INT DEFAULT 0 AFTER contrast,
  ADD COLUMN sharpen INT UNSIGNED DEFAULT 0 AFTER saturation,
  ADD COLUMN blur INT UNSIGNED DEFAULT 0 AFTER sharpen,
  ADD COLUMN opacity INT UNSIGNED DEFAULT 100 AFTER blur,
  ADD COLUMN sepia TINYINT(1) DEFAULT 0 AFTER opacity,
  ADD COLUMN grayscale TINYINT(1) DEFAULT 0 AFTER sepia,
  ADD COLUMN hue_rotate INT DEFAULT 0 AFTER grayscale,
  ADD COLUMN tint_color VARCHAR(7) DEFAULT NULL AFTER hue_rotate,
  ADD COLUMN tint_amount INT UNSIGNED DEFAULT 0 AFTER tint_color;

ALTER TABLE templates
  ADD COLUMN brightness INT DEFAULT 0 AFTER photo_glow_color,
  ADD COLUMN contrast INT DEFAULT 0 AFTER brightness,
  ADD COLUMN saturation INT DEFAULT 0 AFTER contrast,
  ADD COLUMN sharpen INT UNSIGNED DEFAULT 0 AFTER saturation,
  ADD COLUMN blur INT UNSIGNED DEFAULT 0 AFTER sharpen,
  ADD COLUMN opacity INT UNSIGNED DEFAULT 100 AFTER blur,
  ADD COLUMN sepia TINYINT(1) DEFAULT 0 AFTER opacity,
  ADD COLUMN grayscale TINYINT(1) DEFAULT 0 AFTER sepia,
  ADD COLUMN hue_rotate INT DEFAULT 0 AFTER grayscale,
  ADD COLUMN tint_color VARCHAR(7) DEFAULT NULL AFTER hue_rotate,
  ADD COLUMN tint_amount INT UNSIGNED DEFAULT 0 AFTER tint_color;
