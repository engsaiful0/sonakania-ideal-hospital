<?php
$route['test-result-report-print'] = 'TestResultController/test_result_report_print';
$route['add-panel-test'] = 'TestPanelResultController/add_panel_test';
$route['view-panel-test'] = 'TestPanelResultController/view_panel_test';
$route['print-panel-test-with-id/(:num)'] = 'TestPanelResultController/print_panel_test_with_id/$1';

