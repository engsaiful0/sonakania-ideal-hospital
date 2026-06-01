-- Lab panel / section / parameter + reports (run once on MySQL/MariaDB)

CREATE TABLE IF NOT EXISTS `test_panels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `panel_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `test_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `panel_id` int(11) NOT NULL,
  `section_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `panel_id` (`panel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `test_parameters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL,
  `parameter_name` varchar(255) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `input_type` varchar(20) NOT NULL DEFAULT 'text',
  `min_value` decimal(12,4) DEFAULT NULL,
  `max_value` decimal(12,4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optional ordering column (only if you want manual sort; app uses id order by default):
-- ALTER TABLE `test_sections` ADD COLUMN `sort_order` int(11) NOT NULL DEFAULT 0;
-- ALTER TABLE `test_parameters` ADD COLUMN `sort_order` int(11) NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `lab_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_name` varchar(255) NOT NULL,
  `age` varchar(50) DEFAULT NULL,
  `sex` varchar(20) DEFAULT NULL,
  `patient_id` varchar(100) DEFAULT NULL,
  `panel_id` int(11) NOT NULL,
  `report_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `panel_id` (`panel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lab_report_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_id` int(11) NOT NULL,
  `parameter_id` int(11) NOT NULL,
  `result_value` varchar(500) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `report_id` (`report_id`),
  KEY `parameter_id` (`parameter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
