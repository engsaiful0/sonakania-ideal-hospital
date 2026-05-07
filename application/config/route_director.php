<?php
// director Holder Routes - Modern Ajax-based CRUD
$route['add-director'] = 'DirectorController/add_director';
$route['edit-director/(:num)'] = 'DirectorController/edit_director/$1';
$route['delete-this-director/(:num)'] = 'DirectorController/delete_this_director/$1';
$route['print-director'] = 'DirectorController/print_director';
$route['print-director-again/(:num)'] = 'DirectorController/print_director_again/$1';
$route['view-director'] = 'DirectorController/view_director';

// director Holder Export and Print Routes
$route['export-directors-excel'] = 'DirectorController/export_directors_excel';
$route['export-directors-comprehensive'] = 'DirectorController/export_directors_comprehensive_excel';
$route['export-directors-summary'] = 'DirectorController/export_directors_summary_excel';
$route['export-directors-financial'] = 'DirectorController/export_directors_financial_excel';
$route['export-directors-nominee-bank'] = 'DirectorController/export_directors_nominee_bank_excel';
$route['export-directors-pdf'] = 'DirectorController/export_directors_pdf';
$route['print-directors'] = 'DirectorController/print_directors';
$route['directors-export-dashboard'] = 'DirectorController/export_dashboard';

// director Holder Legacy Routes (for backward compatibility)
$route['director'] = 'DirectorController/director';
$route['get-director-unique-id'] = 'DirectorController/get_director_unique_id';
$route['update-director-unique-ids'] = 'DirectorController/update_director_unique_ids';
$route['test-director-unique-id'] = 'DirectorController/test_director_unique_id';

