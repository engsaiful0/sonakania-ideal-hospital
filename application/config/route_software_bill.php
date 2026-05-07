<?php
$route['add-opd-patient'] = 'OpdPatientController/add_opd_patient';
$route['view-opd-patient'] = 'OpdPatientController/view_opd_patient';
$route['opd-payment'] = 'OpdPatientController/opd_payment';
$route['opd-patient-report'] = 'OpdPatientController/opd_patient_report';
$route['opd-patient-edit/(:num)'] = 'OpdPatientController/opd_patient_edit/$1';
$route['opd-patient-print-again/(:num)'] = 'OpdPatientController/opd_patient_print_again/$1';
$route['print-opd-patient'] = 'OpdPatientController/opd_patient_print';
$route['delete-this-opd-patient/(:num)'] = 'OpdPatientController/delete_this_opd_patient/$1';
$route['opd-patient-return/(:num)'] = 'OpdPatientController/opd_patient_return/$1';