<?php
$route['view-ot-service'] = 'OTServiceController/view_ot_service';
$route['add-ot-service'] = 'OTServiceController/add_ot_service';
$route['edit-ot-service/(:num)'] = 'OTServiceController/edit_ot_service/$1';
$route['get-ot-service-details'] = 'OTServiceController/get_ot_service_details';
$route['print-ot-service-by-id/(:num)'] = 'OTServiceController/print_ot_service_by_id/$1';
$route['ot-service-due-payment/(:num)'] = 'OTServiceController/ot_service_due_payment/$1';

$route['print-ot-service'] = 'OTServiceController/print_ot_service';





