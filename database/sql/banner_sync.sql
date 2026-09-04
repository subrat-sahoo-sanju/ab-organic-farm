-- Banner + Homepage Section sync for production.
-- Run after `php artisan db:seed` or standalone: mysql -u root organic_store < database/sql/banner_sync.sql

-- Upsert current banners (id 22, 23)
INSERT INTO banners (id, placement, title, subtitle, desktop_image, mobile_image, button_text, button_url, width, height, sort_order, is_active, show_text, created_at, updated_at)
VALUES
  (22, 'hero', 'sdad', NULL, 'banners/6a9ad0476d0997.24077906.png', NULL, NULL, NULL, 1600, 500, 1, 1, 1, NOW(), NOW()),
  (23, 'hero', 'DSDA', NULL, 'banners/6a9ad01784b5d9.74697946.png', NULL, NULL, NULL, 1600, 500, 0, 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
  desktop_image = VALUES(desktop_image),
  is_active = VALUES(is_active),
  sort_order = VALUES(sort_order),
  updated_at = NOW();

-- Ensure hero + promotional_banners sections are visible
UPDATE homepage_sections SET is_visible = 1, updated_at = NOW() WHERE `key` IN ('hero', 'promotional_banners');
