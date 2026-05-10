-- Sample panels: CBC, Urine R/M/E, Lipid Profile
-- Prerequisites: tables from lab_panel_report_schema.sql exist.
-- Safe to run on empty hierarchy; for re-run DELETE parameters/sections/panels first (see footer).

SET NAMES utf8mb4;

/* ========== CBC ========== */
INSERT INTO `test_panels` (`panel_name`, `description`) VALUES ('CBC', 'Complete blood count — sample reference ranges (adjust locally)');
SET @cbc_panel := LAST_INSERT_ID();
INSERT INTO `test_sections` (`panel_id`, `section_name`) VALUES (@cbc_panel, 'Complete Blood Count');
SET @cbc_sec := LAST_INSERT_ID();
INSERT INTO `test_parameters` (`section_id`, `parameter_name`, `unit`, `input_type`, `min_value`, `max_value`) VALUES
(@cbc_sec, 'Hb', 'g/dL', 'numeric', 12.0000, 16.0000),
(@cbc_sec, 'RBC', '×10^6/µL', 'numeric', 4.3000, 5.9000),
(@cbc_sec, 'WBC', '×10^3/µL', 'numeric', 4.0000, 11.0000),
(@cbc_sec, 'Platelets', '×10^3/µL', 'numeric', 150.0000, 400.0000);

/* ========== Urine R/M/E ========== */
INSERT INTO `test_panels` (`panel_name`, `description`) VALUES ('Urine R/M/E', 'Urine routine / microscopy examination — qualitative fields as text except protein');
SET @urn_panel := LAST_INSERT_ID();
INSERT INTO `test_sections` (`panel_id`, `section_name`) VALUES (@urn_panel, 'Routine & microscopy');
SET @urn_sec := LAST_INSERT_ID();
INSERT INTO `test_parameters` (`section_id`, `parameter_name`, `unit`, `input_type`, `min_value`, `max_value`) VALUES
(@urn_sec, 'Colour', '', 'text', NULL, NULL),
(@urn_sec, 'Protein', '', 'boolean', NULL, NULL),
(@urn_sec, 'RBC', '/HPF', 'text', NULL, NULL),
(@urn_sec, 'Pus Cells', '/HPF', 'text', NULL, NULL);

/* ========== Lipid Profile ========== */
INSERT INTO `test_panels` (`panel_name`, `description`) VALUES ('Lipid Profile', 'HDL/LDL/triglycerides — sample lipid cutoffs');
SET @lip_panel := LAST_INSERT_ID();
INSERT INTO `test_sections` (`panel_id`, `section_name`) VALUES (@lip_panel, 'Lipid panel');
SET @lip_sec := LAST_INSERT_ID();
INSERT INTO `test_parameters` (`section_id`, `parameter_name`, `unit`, `input_type`, `min_value`, `max_value`) VALUES
(@lip_sec, 'HDL', 'mg/dL', 'numeric', 40.0000, NULL),
(@lip_sec, 'LDL', 'mg/dL', 'numeric', NULL, 100.0000),
(@lip_sec, 'Triglycerides', 'mg/dL', 'numeric', NULL, 150.0000);

/*
-- To remove this sample pack only (if no lab_reports reference these rows):

DELETE pr FROM lab_report_results pr
JOIN test_parameters tp ON tp.id = pr.parameter_id
JOIN test_sections ts ON ts.id = tp.section_id
JOIN test_panels pp ON pp.id = ts.panel_id
WHERE pp.panel_name IN ('CBC', 'Urine R/M/E', 'Lipid Profile');

DELETE tp FROM test_parameters tp
JOIN test_sections ts ON ts.id = tp.section_id
JOIN test_panels pp ON pp.id = ts.panel_id
WHERE pp.panel_name IN ('CBC', 'Urine R/M/E', 'Lipid Profile');

DELETE ts FROM test_sections ts
JOIN test_panels pp ON pp.id = ts.panel_id
WHERE pp.panel_name IN ('CBC', 'Urine R/M/E', 'Lipid Profile');

DELETE FROM test_panels WHERE panel_name IN ('CBC', 'Urine R/M/E', 'Lipid Profile');
*/
