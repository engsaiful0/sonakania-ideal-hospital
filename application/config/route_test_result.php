<?php
$route['test-result-report-print'] = 'TestResultController/test_result_report_print';
$route['add-panel-test'] = 'TestPanelResultController/add_panel_test';
$route['view-panel-test'] = 'TestPanelResultController/view_panel_test';
$route['panel-test-edit/(:num)'] = 'TestPanelResultController/panel_test_edit/$1';
$route['print-panel-test-with-id/(:num)'] = 'TestPanelResultController/print_panel_test_with_id/$1';

