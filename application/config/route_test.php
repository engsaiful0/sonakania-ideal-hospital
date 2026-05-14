<?php
$route['group'] = 'SettingsTestController/add_test_group';

$route['test-category'] = 'SettingsTestController/add_test_category';
$route['test-name'] = 'SettingsTestController/test_name';

$route['panel'] = 'SettingsTestController/panel';
$route['section'] = 'SettingsTestController/section';
$route['parameter'] = 'SettingsTestController/parameter';
$route['report-result'] = 'SettingsTestController/report_result';
$route['edit-test-entry/(:num)'] = 'TestController/edit_test_entry/$1';
$route['return-test-entry/(:num)'] = 'TestController/return_test_entry/$1';
$route['print-discharge-slip-again/(:num)'] = 'SettingsTestController/print_discharge_slip_again/$1';
$route['print-test-entry'] = 'TestController/print_test_entry';
$route['print-test-entry-after-due-payment'] = 'TestController/print_test_entry_after_due_payment';
$route['print-test-entry-with-id/(:num)'] = 'TestController/print_test_entry_with_id/$1';
$route['print-test-entry-with-id/(:num)'] = 'TestController/print_test_entry_with_id/$1';
$route['test-entry-details-print/(:num)'] = 'TestController/TestEntryDetailsPrintWithId/$1';







