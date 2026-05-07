<?php
$route['generate_monthly_bill'] = 'CronForBillController/generate_monthly_bill';
$route['software-bill-print/(:num)'] = 'ReportAccountController/software_bill_print/$1';
