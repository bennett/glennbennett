-- Seed existing 25 calendar background images
-- These correspond to the Cal-Event-0.jpg through Cal-Event-24.jpg files in imgs/

INSERT INTO cal_images (filename, image_path, original_name, is_active, sort_order) VALUES
('Cal-Event-0.jpg', 'imgs/', 'Cal-Event-0.jpg', 1, 0),
('Cal-Event-1.jpg', 'imgs/', 'Cal-Event-1.jpg', 1, 1),
('Cal-Event-2.jpg', 'imgs/', 'Cal-Event-2.jpg', 1, 2),
('Cal-Event-3.jpg', 'imgs/', 'Cal-Event-3.jpg', 1, 3),
('Cal-Event-4.jpg', 'imgs/', 'Cal-Event-4.jpg', 1, 4),
('Cal-Event-5.jpg', 'imgs/', 'Cal-Event-5.jpg', 1, 5),
('Cal-Event-6.jpg', 'imgs/', 'Cal-Event-6.jpg', 1, 6),
('Cal-Event-7.jpg', 'imgs/', 'Cal-Event-7.jpg', 1, 7),
('Cal-Event-8.jpg', 'imgs/', 'Cal-Event-8.jpg', 1, 8),
('Cal-Event-9.jpg', 'imgs/', 'Cal-Event-9.jpg', 1, 9),
('Cal-Event-10.jpg', 'imgs/', 'Cal-Event-10.jpg', 1, 10),
('Cal-Event-11.jpg', 'imgs/', 'Cal-Event-11.jpg', 1, 11),
('Cal-Event-12.jpg', 'imgs/', 'Cal-Event-12.jpg', 1, 12),
('Cal-Event-13.jpg', 'imgs/', 'Cal-Event-13.jpg', 1, 13),
('Cal-Event-14.jpg', 'imgs/', 'Cal-Event-14.jpg', 1, 14),
('Cal-Event-15.jpg', 'imgs/', 'Cal-Event-15.jpg', 1, 15),
('Cal-Event-16.jpg', 'imgs/', 'Cal-Event-16.jpg', 1, 16),
('Cal-Event-17.jpg', 'imgs/', 'Cal-Event-17.jpg', 1, 17),
('Cal-Event-18.jpg', 'imgs/', 'Cal-Event-18.jpg', 1, 18),
('Cal-Event-19.jpg', 'imgs/', 'Cal-Event-19.jpg', 1, 19),
('Cal-Event-20.jpg', 'imgs/', 'Cal-Event-20.jpg', 1, 20),
('Cal-Event-21.jpg', 'imgs/', 'Cal-Event-21.jpg', 1, 21),
('Cal-Event-22.jpg', 'imgs/', 'Cal-Event-22.jpg', 1, 22),
('Cal-Event-23.jpg', 'imgs/', 'Cal-Event-23.jpg', 1, 23),
('Cal-Event-24.jpg', 'imgs/', 'Cal-Event-24.jpg', 1, 24);

-- Create default layouts for each image (using fb_image.php defaults)
INSERT INTO cal_image_layouts (cal_image_id, text_offset, summary_font_size, summary_margin_top, date_font_size, date_margin_top, time_font_size, time_margin_top, location_font_size, location_margin_top)
SELECT id, -200, 36, 260, 24, 25, 36, 25, 24, 25
FROM cal_images;
