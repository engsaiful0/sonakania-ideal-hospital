<?php
$route['test-result-report-print'] = 'TestResultController/test_result_report_print';
$route['enter-test-result'] = 'TestResultController/enter_test_result';
$route['enter-test-result/(:num)'] = 'TestResultController/enter_test_result/$1';
$route['add-panel-test'] = 'TestPanelResultController/add_panel_test';
$route['view-panel-test'] = 'TestPanelResultController/view_panel_test';
$route['panel-test-edit/(:num)'] = 'TestPanelResultController/panel_test_edit/$1';
$route['print-panel-test-with-id/(:num)'] = 'TestPanelResultController/print_panel_test_with_id/$1';

