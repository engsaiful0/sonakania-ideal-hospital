<?php
$route['add-doctor-serial'] = 'DoctorSerialController/add_doctor_serial';
$route['view-doctor-serial'] = 'DoctorSerialController/view_doctor_serial';
$route['doctor-serial-payment'] = 'DoctorSerialController/doctor_serial_payment';
$route['doctor-serial-report'] = 'DoctorSerialController/doctor_serial_report';
$route['doctor-serial-edit/(:num)'] = 'DoctorSerialController/doctor_serial_edit/$1';
$route['doctor-serial-print-again/(:num)'] = 'DoctorSerialController/doctor_serial_print_again/$1';
$route['print-doctor-serial'] = 'DoctorSerialController/doctor_serial_print';
$route['delete-this-doctor-serial/(:num)'] = 'DoctorSerialController/delete_this_doctor_serial/$1';
$route['doctor-serial-return/(:num)'] = 'DoctorSerialController/doctor_serial_return/$1';