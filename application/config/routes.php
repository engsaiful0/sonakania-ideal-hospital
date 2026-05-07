<?php
defined('BASEPATH') or exit('No direct script access allowed');
include 'route_expired_medicine.php';
include 'route_canteen.php';
include 'route_canteen_stock_manager.php';
include 'route_discharge.php';
include 'route_ot_service.php';
include 'route_discharge_slip.php';
include 'route_prescription.php';
include 'route_test.php';
include 'route_qr_code.php';
include 'route_lab.php';
include 'route_drug.php';
include 'route_purchase_goods.php';
include 'route_marketting.php';
include 'route_canteen_goods_usage.php';
include 'route_report.php';
include 'route_test_result.php';
include 'route_customer.php';
include 'route_sample_collect.php';
include 'route_opd.php';
include 'route_emergency.php';
include 'route_phygiotherapy.php';
include 'route_journal_voucher.php';
include 'route_store.php';
include 'route_issue.php';
include 'route_director.php';
include 'route_api.php';
include 'route_sms.php';
include 'route_bill.php';
include 'route_salary.php';
include 'route_doctor_serial.php';
include 'route_director_api.php';



$route['reception-sell-report'] = 'ReportController/my_reception_sell_report';
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['home'] = 'HomeController/home';
$route['login'] = 'LoginController/index';
$route['database-backup'] = 'HomeController/backup';


$route['add-medicine-purchase'] = 'MedicinePurchaseController/add_medicine_purchase';
// $route['view-medicine-purchase'] = 'MedicinePurchaseController/view_medicine_purchase';
$route['medicine-purchase-edit/(:num)'] = 'MedicinePurchaseController/edit_medicine_purchase/$1';
$route['print-medicine-purchase-again/(:num)'] = 'MedicinePurchaseController/print_medicine_purchase_again/$1';
$route['print-medicine-purchase'] = 'MedicinePurchaseController/print_medicine_purchase';

$route['medicine-purchase-return/(:num)'] = 'MedicinePurchaseReturnController/medicine_purchase_return/$1';
$route['print-medicine-purchase-return'] = 'MedicinePurchaseReturnController/print_medicine_purchase_return';
$route['print-medicine-purchase-return-again/(:num)'] = 'MedicinePurchaseReturnController/print_medicine_purchase_return_again/$1';
$route['medicine-purchase-return-edit/(:num)'] = 'MedicinePurchaseReturnController/medicine_purchase_return_edit/$1';




$route['add-medicine-sale'] = 'MedicineSaleController/medicin_sell';
// $route['view-medicine-sale'] = 'MedicineSaleController/view_medicine_sell';
$route['view-medicine-sell/(:num)'] = 'MedicineSaleController/view_medicine_sell/$1';
$route['view-medicine-sell/(:num)'] = 'MedicineSaleController/view_medicine_sell/$1';
$route['medicine-sale-due-payment/(:num)'] = 'MedicineSaleController/medicine_sale_due_payment/$1';

$route['confirm-sale'] = 'MedicineSaleController/confirm_sale';
$route['medicine-sale-edit/(:any)'] = 'MedicineSaleController/medicine_sale_edit/$1';
$route['print-medicine-sale'] = 'MedicineSaleController/print_medicine_sale';
$route['print-medicine-sale-again/(:num)'] = 'MedicineSaleController/print_medicine_sale_again/$1';


// New Medicine Sale Return Management Routes
$route['medicine-sale-return'] = 'MedicineSaleReturnWithoutInvoiceController/index';
$route['medicine-sale-return/create'] = 'MedicineSaleReturnWithoutInvoiceController/create';
$route['medicine-sale-return/store'] = 'MedicineSaleReturnWithoutInvoiceController/store';
$route['medicine-sale-return-without-invoice'] = 'MedicineSaleReturnWithoutInvoiceController/view_medicine_sale_return';
$route['medicine-sale-return/show/(:num)'] = 'MedicineSaleReturnWithoutInvoiceController/show/$1';
$route['medicine-sale-return/edit/(:num)'] = 'MedicineSaleReturnWithoutInvoiceController/edit/$1';
$route['medicine-sale-return/update/(:num)'] = 'MedicineSaleReturnWithoutInvoiceController/update/$1';
$route['medicine-sale-return/delete/(:num)'] = 'MedicineSaleReturnWithoutInvoiceController/delete/$1';
$route['print-medicine-sale-return-without-invoice'] = 'MedicineSaleReturnWithoutInvoiceController/print_medicine_sale_return_without_invoice';
$route['print-medicine-sale-return-without-invoice/(:num)'] = 'MedicineSaleReturnWithoutInvoiceController/print_medicine_sale_return_without_invoice_again/$1';
$route['print-medicine-sale-return-without-invoice-again/(:num)'] = 'MedicineSaleReturnWithoutInvoiceController/print_medicine_sale_return_without_invoice_again/$1';
$route['medicine-sale-return/search-sales'] = 'MedicineSaleReturnWithoutInvoiceController/search_sales';
$route['medicine-sale-return/get-sale-details'] = 'MedicineSaleReturnWithoutInvoiceController/get_sale_details';
$route['medicine-sale-return/get-summary'] = 'MedicineSaleReturnWithoutInvoiceController/get_summary';

// Legacy routes (keeping for backward compatibility)
$route['medicine-sale-return-edit/(:num)'] = 'MedicineSaleReturnController/medicine_sale_return_edit/$1';
$route['medicine-sale-return/(:num)'] = 'MedicineSaleReturnController/medicine_sale_return/$1';
$route['print-medicine-sale-return'] = 'MedicineSaleReturnController/print_medicine_sale_return';
$route['print-medicine-sale-return-again/(:num)'] = 'MedicineSaleReturnController/print_medicine_sale_return_again/$1';




$route['add-prescription'] = 'PrescriptionController/add_prescription';
$route['prescription-dashboard'] = 'PrescriptionController/prescription_dashboard';
$route['view-prescription'] = 'PrescriptionController/view_prescription';
$route['add-diagnosis'] = 'PrescriptionController/add_diagnosis';
$route['add-medicin-times'] = 'PrescriptionController/add_medicin_times';
$route['add-advice'] = 'PrescriptionController/add_advice';
$route['add-prescription-header'] = 'PrescriptionController/add_prescription_header';

$route['add-bill-generic'] = 'BillGenericController/add_bill_generic';
$route['bill-dashboard'] = 'BillGenericController/bill_dashboard';

$route['add-doctor'] = 'DoctorController/add_doctor';
$route['doctor-dashboard'] = 'DoctorController/doctor_dashboard';
$route['view-doctor'] = 'DoctorController/view_doctor';
$route['doctors-payment'] = 'DoctorController/doctors_payment';
$route['view-doctors-payment'] = 'DoctorController/view_doctors_payment';




$route['test-dashboard'] = 'TestController/dashboard';
$route['add-test-entry'] = 'TestController/add_test_entry';
$route['view-test-entry'] = 'TestController/view_test_entry';
$route['test-due-collection'] = 'TestController/test_due_collection';
$route['sold-test-report'] = 'TestController/sold_test_report';
$route['test-report-delivery'] = 'ReportDeliveryController/test_report_delivery';


$route['pharmacy'] = 'PharmacyController/pharmacy';
$route['hrm'] = 'HrmController';


$route['add-employee'] = 'EmployeeController/add_employee';
$route['view-employee'] = 'EmployeeController/view_employee';
$route['add-employee-payroll'] = 'EmployeeController/add_employee_payroll';
$route['add-employee-salary'] = 'EmployeeController/add_employee_salary';
$route['salary-sheet'] = 'EmployeeController/all_employee_salary_report';
$route['delete-this-employee/(:any)'] = 'EmployeeController/delete_this_employee/$1';
$route['edit-employee/(:any)'] = 'EmployeeController/edit_employee/$1';
$route['print-employee/(:any)'] = 'EmployeeController/print_employee/$1';


$route['accounce'] = 'AccounceController';
$route['reports'] = 'ReportController/income_report';
$route['reports-dashboard'] = 'ReportController/reports_dashboard';


$route['print-ipd-patient'] = 'IpdPatientController/ipd_patient_print';
$route['print_patient_info'] = 'IpdPatientController/print_patient_info';
$route['generate-barcode/(:any)'] = 'IpdPatientController/set_barcode/$1';
$route['add-ipd-patient'] = 'IpdPatientController/add_ipd_patient';
$route['add-ipd-patient'] = 'IpdPatientController/add_ipd_patient';
$route['view-ipd-patient'] = 'IpdPatientController/view_ipd_patient';
$route['view-ipd-patient/(:num)'] = 'IpdPatientController/view_ipd_patient/$1';
$route['ipd-patient-edit/(:num)'] = 'IpdPatientController/ipd_patient_edit/$1';
$route['delete-this-ipd-patient/(:num)'] = 'IpdPatientController/delete_this_ipd_patient/$1';
$route['ipd-patient-print-again/(:num)'] = 'IpdPatientController/ipd_patient_print_again/$1';
$route['ipd-with-bed'] = 'IpdPatientController/ipd_with_bed';
$route['ipd-with-cabin'] = 'IpdPatientController/ipd_with_cabin';


$route['patient-dashbaord'] = 'PatientController/patient_dashbaord';
$route['my-reception-sell-report'] = 'PatientController/my_reception_sell_report';

$route['increment'] = 'IncrementController/view_increment';
$route['add-increment'] = 'IncrementController/add_increment';

$route['view-increment'] = 'IncrementController/view_increment';
$route['edit-increment/(:any)'] = 'IncrementController/edit_increment/$1';
$route['delete-this-increment/(:any)'] = 'IncrementController/delete_this_increment/$1';


$route['add-ipd-service'] = 'IpdServiceController/add_ipd_service';
$route['view-ipd-service'] = 'IpdServiceController/view_ipd_service';
$route['delete-this-ipd-service/(:any)'] = 'IpdServiceController/delete_this_ipd_service/$1';
$route['edit-ipd-service/(:any)'] = 'IpdServiceController/edit_ipd_service/$1';
$route['print-ipd-service-again/(:any)'] = 'IpdServiceController/print_ipd_service_again/$1';
$route['print-ipd-service'] = 'IpdServiceController/print_ipd_service';
$route['get-ipd-service-details'] = 'IpdServiceController/get_ipd_service_details';





$route['drug-list'] = 'DrugController/drug_list';
$route['drug-stock-report'] = 'DrugController/drug_stock_report';

$route['settings'] = 'SettingsController';


$route['monthly-bill'] = 'SettingsController/monthly_bill';
$route['company-profile'] = 'SettingsController/company_profile';
$route['referred-by'] = 'SettingsController/referred_by';
$route['department'] = 'SettingsController/department';
$route['designation'] = 'SettingsController/designation';
$route['phygiotherapy-service'] = 'SettingsController/phygiotherapy_service';
$route['emergency-service'] = 'SettingsController/emergency_service';
$route['item'] = 'SettingsController/item';
$route['user'] = 'SettingsController/user';
$route['module'] = 'SettingsController/module';
$route['sub-module'] = 'SettingsController/sub_module';
$route['user-type'] = 'SettingsController/user_type';
$route['debit-account'] = 'SettingsController/debit_account';
$route['credit-account'] = 'SettingsController/credit_account';
$route['settings'] = 'SettingsController';
$route['blood-group'] = 'SettingsController/blood_group';
$route['sms-api'] = 'SettingsController/sms_api';
$route['sms-template'] = 'SettingsController/sms_template';
$route['medicine-type'] = 'SettingsPharmachyController/medicine_type';
$route['manufacturer'] = 'SettingsPharmachyController/manufacturer';


$route['supplier'] = 'SettingsController/supplier';
$route['nationality'] = 'SettingsController/nationality';
$route['profession'] = 'SettingsController/profession';
$route['religion'] = 'SettingsController/religion';
$route['relation'] = 'SettingsController/relation';
$route['expertise'] = 'SettingsController/expertise';
$route['bank-name'] = 'SettingsController/bank_name';
$route['bank-account'] = 'SettingsController/bank_account';
$route['mobile-banking'] = 'SettingsController/mobile_banking';
$route['marital-status'] = 'SettingsController/marital_status';
$route['report-footer'] = 'SettingsController/add_report_footer';
$route['ipd-service'] = 'SettingsController/ipd_service';
$route['surgery'] = 'SettingsController/surgery';
$route['payment-method'] = 'SettingsController/payment_method';
$route['men-power-category'] = 'SettingsController/men_power_category';

$route['shelf'] = 'SettingsPharmachyController/shelf';







$route['cabin'] = 'SettingsPatientController/cabin';
$route['cabin-category'] = 'SettingsPatientController/cabin_category';
$route['ward'] = 'SettingsPatientController/ward';
$route['bed'] = 'SettingsPatientController/bed';
$route['reference-media'] = 'SettingsPatientController/reference_media';
$route['discharge-reason'] = 'SettingsPatientController/discharge_reason';



$route['user-permission'] = 'PermissionController/view_permission';
$route['add-permission'] = 'PermissionController/add_permission';

$route['marketting'] = 'MarkettingController';
$route['profile-update'] = 'ProfileController';



$route['test-result-dashboard'] = 'TestResultController/test_result_dashboard';
$route['add-test-result'] = 'TestResultController/add_test_result';
$route['view-test-result'] = 'TestResultController/view_test_result';
$route['view-test-configuration'] = 'TestResultController/view_test_configuration';
$route['add-test-configuration'] = 'TestResultController/add_test_configuration';
$route['add-test-result'] = 'TestResultController/add_test_result';



$route['add-attendance'] = 'AttendanceController/add_attendance';
$route['view-attendance'] = 'AttendanceController/view_attendance';
$route['delete-this-attendance/(:any)'] = 'AttendanceController/delete_this_attendance/$1';
$route['edit-single-attendance/(:any)'] = 'AttendanceController/edit_single_attendance/$1';
$route['all-attendance'] = 'AttendanceController/all_attendance';
$route['bulk-attendance'] = 'AttendanceController/bulk_attendance';



$route['add-debit-voucher'] = 'DebitVoucherController/add_debit_voucher';
$route['view-debit-voucher'] = 'DebitVoucherController/view_debit_voucher';
$route['print-debit-voucher/(:any)'] = 'DebitVoucherController/print_debit_voucher_with_id/$1';
$route['edit-debit-voucher/(:any)'] = 'DebitVoucherController/edit_debit_voucher/$1';
$route['print-debit-voucher'] = 'DebitVoucherController/print_debit_voucher';
$route['delete-this-debit-voucher/(:any)'] = 'DebitVoucherController/delete_this_debit_voucher/$1';

$route['add-credit-voucher'] = 'CreditVoucherController/add_credit_voucher';
$route['view-credit-voucher'] = 'CreditVoucherController/view_credit_voucher';
$route['print-credit-voucher/(:any)'] = 'CreditVoucherController/print_credit_voucher_with_id/$1';
$route['edit-credit-voucher/(:any)'] = 'CreditVoucherController/edit_credit_voucher/$1';
$route['print-credit-voucher'] = 'CreditVoucherController/print_credit_voucher';
$route['delete-this-credit-voucher/(:any)'] = 'CreditVoucherController/delete_this_credit_voucher/$1';


