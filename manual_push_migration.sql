-- ========================================================
-- Postermaker Manual Push Notification & Delivery Analytics Migration
-- Database: u126066279_poster_db
-- Tables: push_manual_campaigns, push_delivery_events
-- ========================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- 1. Table structure for table `push_manual_campaigns`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `push_manual_campaigns` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `campaign_uid` VARCHAR(64) NOT NULL COMMENT 'Unique SHA-256 idempotency key',
  `title` VARCHAR(150) NOT NULL,
  `message` TEXT NOT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL COMMENT 'Relative path on server',
  `image_url` VARCHAR(500) DEFAULT NULL COMMENT 'Full HTTPS URL',
  `target_type` ENUM('TOPIC', 'ALL_DEVICES', 'TEST_DEVICES', 'CUSTOM_TOKEN') NOT NULL DEFAULT 'TOPIC',
  `target_value` VARCHAR(255) NOT NULL DEFAULT 'all_users',
  `target_screen` VARCHAR(50) DEFAULT 'category_detail',
  `cat_id` INT(11) DEFAULT NULL,
  `status` ENUM('DRAFT', 'PROCESSING', 'SENT', 'FAILED') NOT NULL DEFAULT 'DRAFT',
  `is_dry_run` TINYINT(1) NOT NULL DEFAULT 1,
  `fcm_message_id` VARCHAR(255) DEFAULT NULL,
  `targeted_count` INT(11) NOT NULL DEFAULT 0 COMMENT 'Targeted audience count (or -1 for global topic)',
  `accepted_count` INT(11) NOT NULL DEFAULT 0 COMMENT 'FCM HTTP accepted requests',
  `confirmed_delivery_count` INT(11) NOT NULL DEFAULT 0 COMMENT 'Verified device delivery receipts',
  `open_count` INT(11) NOT NULL DEFAULT 0 COMMENT 'Verified notification open events',
  `error_details` TEXT DEFAULT NULL,
  `created_by` VARCHAR(100) NOT NULL DEFAULT 'admin',
  `sent_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_campaign_uid` (`campaign_uid`),
  KEY `idx_status` (`status`),
  KEY `idx_sent_at` (`sent_at`),
  KEY `idx_created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. Table structure for table `push_delivery_events`
--    Device-level delivery receipts and open event tracking
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `push_delivery_events` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` INT(11) NOT NULL,
  `device_id` VARCHAR(100) NOT NULL,
  `event_type` ENUM('DISPLAYED', 'OPENED') NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_campaign_device_event` (`campaign_id`, `device_id`, `event_type`),
  KEY `idx_campaign_event` (`campaign_id`, `event_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
