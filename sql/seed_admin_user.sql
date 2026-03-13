-- Seed admin user
-- Password: admin123 (change after first login)
-- Generated with: SELECT PASSWORD_HASH = password_hash('admin123', PASSWORD_BCRYPT)
-- Run this after admin_schema.sql

-- Clear existing and insert
TRUNCATE TABLE admin_users;
INSERT INTO admin_users (username, password_hash) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Note: The hash above is for 'password' — you should generate a proper hash.
-- Use PHP to generate: echo password_hash('your_password', PASSWORD_BCRYPT);
