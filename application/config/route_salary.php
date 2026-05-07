<?php
$route['add-salary'] = 'SalaryController/add_salary';
$route['view-salary'] = 'SalaryController/view_salary';
$route['salary-edit/(:any)'] = 'SalaryController/edit_salary/$1';
$route['delete-this-salary/(:any)'] = 'SalaryController/delete_this_salary/$1';
$route['print-salary'] = 'SalaryController/print_salary';
$route['print-salary-again/(:any)'] = 'SalaryController/print_salary_again/$1';


