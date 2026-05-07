<?php

$route['add-phygiotherapy'] = 'PhygiotherapyController/add_phygiotherapy';
$route['view-phygiotherapy'] = 'PhygiotherapyController/view_phygiotherapy';
$route['view-phygiotherapy/(:any)'] = 'PhygiotherapyController/view_phygiotherapy/$1';
$route['edit-phygiotherapy/(:any)'] = 'PhygiotherapyController/edit_phygiotherapy/$1';
$route['delete-this-phygiotherapy/(:any)'] = 'PhygiotherapyController/delete_this_phygiotherapy/$1';
$route['print-phygiotherapy'] = 'PhygiotherapyController/print_phygiotherapy';
$route['print-phygiotherapy/(:any)'] = 'PhygiotherapyController/print_phygiotherapy_with_id/$1';
$route['return-phygiotherapy/(:any)'] = 'PhygiotherapyController/return_phygiotherapy/$1';
$route['physiotherapy-due-payment/(:any)'] = 'PhygiotherapyController/physiotherapy_due_payment/$1';
