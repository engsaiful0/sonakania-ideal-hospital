<?php
$route['add-emergency'] = 'EmergencyController/add_emergency';
$route['view-emergency'] = 'EmergencyController/view_emergency';
$route['view-emergency/(:any)'] = 'EmergencyController/view_emergency/$1';
$route['edit-emergency/(:any)'] = 'EmergencyController/edit_emergency/$1';
$route['return-emergency/(:any)'] = 'EmergencyController/return_emergency/$1';
$route['delete-this-emergency/(:any)'] = 'EmergencyController/delete_this_emergency/$1';
$route['print-emergency'] = 'EmergencyController/print_emergency';
$route['print-emergency/(:any)'] = 'EmergencyController/print_emergency_with_id/$1';
$route['emergency-due-payment/(:any)'] = 'EmergencyController/emergency_due_payment/$1';
