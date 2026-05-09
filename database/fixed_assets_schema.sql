-- Fixed Asset Management module (CodeIgniter 3 + MySQL)
-- Run this script once against your hospital ERP database.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `fa_asset_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_fa_cat_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `fa_asset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_name` varchar(200) NOT NULL,
  `asset_code` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `purchase_date` date NOT NULL,
  `purchase_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `salvage_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `useful_life_years` int(11) NOT NULL DEFAULT 1,
  `annual_depreciation` decimal(15,2) NOT NULL DEFAULT 0.00,
  `current_book_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `accumulated_depreciation` decimal(15,2) NOT NULL DEFAULT 0.00,
  `department_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `warranty_expiry` date DEFAULT NULL,
  `condition_status` varchar(50) NOT NULL DEFAULT 'Good',
  `notes` text,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_fa_asset_code` (`asset_code`),
  KEY `idx_fa_category` (`category_id`),
  KEY `idx_fa_department` (`department_id`),
  KEY `idx_fa_employee` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `fa_asset_maintenance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `maintenance_date` date NOT NULL,
  `description` text NOT NULL,
  `cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `performed_by` varchar(200) DEFAULT NULL,
  `next_due_date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_fa_maint_asset` (`asset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `fa_asset_assignment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `assigned_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_fa_assign_asset` (`asset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;
