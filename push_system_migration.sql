-- ========================================================
-- Postermaker Push Notification System Migration
-- Database: u126066279_poster_db
-- Tables created: push_notification_bank, push_notification_logs,
--                 push_device_tokens, push_config, push_slot_executions
-- ========================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- 1. Table structure for table `push_notification_bank`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `push_notification_bank` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `content_uid` VARCHAR(64) NOT NULL COMMENT 'Unique SHA-256 fingerprint of title+body',
  `title` VARCHAR(150) NOT NULL,
  `body` VARCHAR(300) NOT NULL,
  `category_id` INT(11) DEFAULT NULL COMMENT 'References category.cat_id',
  `category_name` VARCHAR(100) NOT NULL,
  `language` VARCHAR(10) NOT NULL DEFAULT 'en',
  `target_screen` VARCHAR(50) DEFAULT 'category_detail',
  `image_url` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('DRAFT', 'APPROVED', 'SCHEDULED', 'PROCESSING', 'SENT', 'FAILED') NOT NULL DEFAULT 'DRAFT',
  `scheduled_slot` ENUM('09:00', '13:00', '17:00', '20:00', 'ANY') NOT NULL DEFAULT 'ANY',
  `scheduled_at` DATETIME DEFAULT NULL,
  `sent_at` DATETIME DEFAULT NULL,
  `fcm_message_id` VARCHAR(255) DEFAULT NULL,
  `attempt_count` INT(11) NOT NULL DEFAULT 0,
  `error_details` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_content_uid` (`content_uid`),
  KEY `idx_status_scheduled` (`status`, `scheduled_at`),
  KEY `idx_category` (`category_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. Table structure for table `push_notification_logs`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `push_notification_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `bank_id` INT(11) NOT NULL,
  `target_type` ENUM('TOPIC', 'TOKEN') NOT NULL DEFAULT 'TOPIC',
  `target_destination` VARCHAR(255) NOT NULL,
  `http_status` INT(11) NOT NULL,
  `fcm_message_id` VARCHAR(255) DEFAULT NULL,
  `response_body` TEXT DEFAULT NULL,
  `is_dry_run` TINYINT(1) NOT NULL DEFAULT 0,
  `execution_time_ms` INT(11) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_bank_id` (`bank_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. Table structure for table `push_device_tokens`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `push_device_tokens` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `device_id` VARCHAR(100) NOT NULL,
  `fcm_token` TEXT NOT NULL,
  `app_version` VARCHAR(20) DEFAULT NULL,
  `language` VARCHAR(10) DEFAULT 'en',
  `is_test_device` TINYINT(1) DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `last_active_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_device_id` (`device_id`),
  KEY `idx_is_test` (`is_test_device`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. Table structure for table `push_config`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `push_config` (
  `config_key` VARCHAR(50) NOT NULL,
  `config_value` TEXT NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. Table structure for table `push_slot_executions`
--    Ensures slot-level idempotency (never double-send a slot)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `push_slot_executions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `slot_date` DATE NOT NULL COMMENT 'Date in Asia/Kolkata YYYY-MM-DD',
  `slot_name` VARCHAR(10) NOT NULL COMMENT '09:00, 13:00, 17:00, 20:00',
  `bank_id` INT(11) DEFAULT NULL,
  `status` ENUM('CLAIMED', 'COMPLETED', 'FAILED', 'SKIPPED') NOT NULL DEFAULT 'CLAIMED',
  `is_dry_run` TINYINT(1) NOT NULL DEFAULT 1,
  `executed_at` DATETIME NOT NULL,
  `notes` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_date_slot` (`slot_date`, `slot_name`),
  KEY `idx_slot_date` (`slot_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Seed default configuration
-- --------------------------------------------------------
INSERT INTO `push_config` (`config_key`, `config_value`, `description`) VALUES
('is_campaign_paused', '0', 'Emergency Pause: 1 = Paused, 0 = Active'),
('dry_run_mode', '1', 'Safety dry-run: 1 = Simulate only (no FCM network dispatch), 0 = Live'),
('fcm_topic', 'all_users', 'Default FCM broadcast topic'),
('daily_slots', '09:00,13:00,17:00,20:00', 'Four configurable daily slots (Asia/Kolkata HH:MM)'),
('max_retries', '3', 'Max retry attempts for transient FCM errors'),
('fcm_project_id', 'poster---text-art', 'Firebase Project ID for FCM HTTP v1'),
('timezone', 'Asia/Kolkata', 'Operating timezone for push scheduler')
ON DUPLICATE KEY UPDATE `config_key`=`config_key`;

COMMIT;
