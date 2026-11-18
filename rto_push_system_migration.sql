-- ========================================================
-- RTO Vehicle App Push Notification System Migration
-- Database: u126066279_poster_db
-- Tables created: rto_push_notification_bank, rto_push_logs,
--                 rto_push_device_tokens, rto_push_config,
--                 rto_push_slot_executions, rto_push_campaigns,
--                 rto_push_delivery_events
-- ========================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- 1. Table structure for table `rto_push_notification_bank`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rto_push_notification_bank` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `content_uid` VARCHAR(64) NOT NULL COMMENT 'Unique SHA-256 fingerprint',
  `title` VARCHAR(150) NOT NULL,
  `body` VARCHAR(300) NOT NULL,
  `category_name` VARCHAR(100) NOT NULL,
  `language` VARCHAR(10) NOT NULL DEFAULT 'en',
  `target_screen` VARCHAR(50) DEFAULT 'EXAM',
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
  UNIQUE KEY `uq_rto_content_uid` (`content_uid`),
  KEY `idx_rto_status_scheduled` (`status`, `scheduled_at`),
  KEY `idx_rto_category` (`category_name`),
  KEY `idx_rto_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. Table structure for table `rto_push_logs`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rto_push_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `bank_id` INT(11) DEFAULT NULL,
  `target_type` ENUM('TOPIC', 'TOKEN') NOT NULL DEFAULT 'TOPIC',
  `target_destination` VARCHAR(255) NOT NULL,
  `http_status` INT(11) NOT NULL,
  `fcm_message_id` VARCHAR(255) DEFAULT NULL,
  `response_body` TEXT DEFAULT NULL,
  `is_dry_run` TINYINT(1) NOT NULL DEFAULT 0,
  `execution_time_ms` INT(11) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_rto_bank_id` (`bank_id`),
  KEY `idx_rto_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. Table structure for table `rto_push_device_tokens`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rto_push_device_tokens` (
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
  UNIQUE KEY `uq_rto_device_id` (`device_id`),
  KEY `idx_rto_is_test` (`is_test_device`),
  KEY `idx_rto_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. Table structure for table `rto_push_config`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rto_push_config` (
  `config_key` VARCHAR(50) NOT NULL,
  `config_value` TEXT NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. Table structure for table `rto_push_slot_executions`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rto_push_slot_executions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `slot_date` DATE NOT NULL COMMENT 'Date in Asia/Kolkata YYYY-MM-DD',
  `slot_name` VARCHAR(10) NOT NULL COMMENT '09:00, 13:00, 17:00, 20:00',
  `bank_id` INT(11) DEFAULT NULL,
  `status` ENUM('CLAIMED', 'COMPLETED', 'FAILED', 'SKIPPED') NOT NULL DEFAULT 'CLAIMED',
  `is_dry_run` TINYINT(1) NOT NULL DEFAULT 1,
  `executed_at` DATETIME NOT NULL,
  `notes` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_rto_date_slot` (`slot_date`, `slot_name`),
  KEY `idx_rto_slot_date` (`slot_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 6. Table structure for table `rto_push_campaigns`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rto_push_campaigns` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `campaign_uid` VARCHAR(64) NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `message` TEXT NOT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `image_url` VARCHAR(500) DEFAULT NULL,
  `target_type` ENUM('TOPIC', 'ALL_DEVICES', 'TEST_DEVICES', 'CUSTOM_TOKEN') NOT NULL DEFAULT 'TOPIC',
  `target_value` VARCHAR(255) NOT NULL DEFAULT 'all_rto_users',
  `target_screen` VARCHAR(50) DEFAULT 'EXAM',
  `status` ENUM('DRAFT', 'PROCESSING', 'SENT', 'FAILED') NOT NULL DEFAULT 'DRAFT',
  `is_dry_run` TINYINT(1) NOT NULL DEFAULT 1,
  `fcm_message_id` VARCHAR(255) DEFAULT NULL,
  `targeted_count` INT(11) NOT NULL DEFAULT 0,
  `accepted_count` INT(11) NOT NULL DEFAULT 0,
  `confirmed_delivery_count` INT(11) NOT NULL DEFAULT 0,
  `open_count` INT(11) NOT NULL DEFAULT 0,
  `error_details` TEXT DEFAULT NULL,
  `created_by` VARCHAR(100) NOT NULL DEFAULT 'admin',
  `sent_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_rto_campaign_uid` (`campaign_uid`),
  KEY `idx_rto_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 7. Table structure for table `rto_push_delivery_events`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rto_push_delivery_events` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` INT(11) NOT NULL,
  `device_id` VARCHAR(100) NOT NULL,
  `event_type` ENUM('DISPLAYED', 'OPENED') NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_rto_campaign_device_event` (`campaign_id`, `device_id`, `event_type`),
  KEY `idx_rto_campaign_event` (`campaign_id`, `event_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
