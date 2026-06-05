-- Section descriptions saved per test result (panel / unique tests).
CREATE TABLE IF NOT EXISTS `test_result_description` (
  `test_result_description_id` int(11) NOT NULL AUTO_INCREMENT,
  `test_result_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`test_result_description_id`),
  UNIQUE KEY `uq_result_test_section` (`test_result_id`, `test_id`, `section_id`),
  KEY `idx_trd_result` (`test_result_id`),
  KEY `idx_trd_test` (`test_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
