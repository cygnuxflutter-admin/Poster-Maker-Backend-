-- ========================================================
-- Home Categories (Browse by Category) Migration & Seed
-- Database: u126066279_poster_db
-- Table: home_categories
-- ========================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `home_categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `app_id` INT(11) NOT NULL DEFAULT 2,
  `name` VARCHAR(100) NOT NULL,
  `icon_url` VARCHAR(500) DEFAULT NULL,
  `bg_color` VARCHAR(20) DEFAULT NULL,
  `border_color` VARCHAR(20) DEFAULT NULL,
  `action_type` VARCHAR(50) NOT NULL DEFAULT 'template_category',
  `cat_id` VARCHAR(50) DEFAULT NULL,
  `cat_name` VARCHAR(100) DEFAULT NULL,
  `action_url` VARCHAR(500) DEFAULT NULL,
  `sort_order` INT(11) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_home_cat_app_active_sort` (`app_id`, `is_active`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed initial default rows for app_id = 2 only if table is empty
INSERT INTO `home_categories` (`app_id`, `name`, `icon_url`, `bg_color`, `border_color`, `action_type`, `cat_id`, `cat_name`, `action_url`, `sort_order`, `is_active`)
SELECT * FROM (
  SELECT 2 AS app_id, 'Festival' AS name, NULL AS icon_url, '#FFFBEB' AS bg_color, '#FDE68A' AS border_color, 'template_category' AS action_type, '55' AS cat_id, 'Diwali' AS cat_name, '' AS action_url, 1 AS sort_order, 1 AS is_active
  UNION ALL
  SELECT 2, 'Wedding', NULL, '#FFF1F2', '#FECDD3', 'template_category', '18', 'Wedding Anniversary', '', 2, 1
  UNION ALL
  SELECT 2, 'Sale', NULL, '#FEF2F2', '#FECACA', 'template_category', '10', 'Sales', '', 3, 1
  UNION ALL
  SELECT 2, 'Business', NULL, '#EFF6FF', '#BFDBFE', 'template_category', '11', 'Business & marketing', '', 4, 1
  UNION ALL
  SELECT 2, 'Birthday', NULL, '#F5F3FF', '#DDD6FE', 'template_category', '47', 'Birthday', '', 5, 1
  UNION ALL
  SELECT 2, 'Food', NULL, '#ECFDF5', '#A7F3D0', 'template_category', '19', 'Food', '', 6, 1
  UNION ALL
  SELECT 2, 'Education', NULL, '#ECFEFF', '#A5F3FC', 'template_category', '27', 'Educationss', '', 7, 1
  UNION ALL
  SELECT 2, 'Party', NULL, '#FDF2F8', '#FBCFE8', 'template_category', '20', 'Party', '', 8, 1
) AS seed_rows
WHERE NOT EXISTS (SELECT 1 FROM `home_categories` LIMIT 1);

COMMIT;
