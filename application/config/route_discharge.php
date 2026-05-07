<?php
$route['view-discharge'] = 'DischargeController/view_discharge';
$route['add-discharge'] = 'DischargeController/add_discharge';
$route['print-discharge-bill'] = 'DischargeController/print_discharge_bill';
$route['print-discharge-bill-again/(:num)'] = 'DischargeController/print_discharge_bill_again/$1';
$route['full-pay-bill/(:num)'] = 'DischargeController/full_pay_bill/$1';
$route['edit-discharge/(:num)'] = 'DischargeController/edit_discharge/$1';





