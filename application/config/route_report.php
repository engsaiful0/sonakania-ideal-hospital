<?php
//Patient Report
$route['ipd-patient-report'] = 'ReportIPDController/ipd_patient_report';
$route['ipd-service-report'] = 'ReportIPDController/ipd_service_report';
$route['opd-patient-admission-report'] = 'ReportOPDController/opd_patient_report';
$route['daily-test-report'] = 'ReportTestController/daily_test_report';
$route['emergency-report'] = 'ReportEmergencyController/emergency_report';
$route['physiotherapy-report'] = 'ReportPhysiotherapyController/physiotherapy_report';

//Pharmacy Report
$route['medicine-sell-report'] = 'ReportPharmacyController/medicine_sell_report';
$route['medicine-sell-return-report'] = 'ReportPharmacyController/medicine_sell_return_report';
$route['medicine-purchase-report'] = 'ReportPharmacyController/medicine_purchase_report';
$route['medicine-purchase-supplier-details-report'] = 'ReportPharmacyController/medicine_purchase_supplier_details_report';

$route['medicine-purchase-return-report'] = 'ReportPharmacyController/medicine_purchase_return_report';
$route['expired-medicine-report'] = 'ReportPharmacyController/expired_medicine_report';
$route['medicine-stock-report'] = 'ReportPharmacyController/medicine_stock_report';
$route['medicine-low-stock-report'] = 'ReportPharmacyController/medicine_low_stock_report';

//Canteen Report
$route['ready-item-sell-report'] = 'ReportCanteenController/ready_item_sell_report';
$route['canteen-purchase-report'] = 'ReportCanteenController/canteen_purchase_report';
$route['goods-usage-report'] = 'ReportCanteenController/goods_usage_report';
$route['canteen-goods-stock-report'] = 'ReportCanteenController/canteen_goods_stock_report';
$route['ready-item-stock-report'] = 'ReportCanteenController/ready_item_stock_report';

//HRM Report
$route['hrm-director-report'] = 'ReportHRMController/hrm_director_report';
$route['hrm-employee-report'] = 'ReportHRMController/hrm_employee_report';
$route['hrm-doctor-report'] = 'ReportHRMController/hrm_doctor_list_report';
$route['hrm-increment-report'] = 'ReportHRMController/hrm_increment_report';
$route['hrm-attendance-report'] = 'ReportHRMController/hrm_attendance_report';

//Account Report
$route['monthly-software-bill'] = 'ReportAccountController/monthly_software_bill';
$route['account-credit-voucher-report'] = 'ReportAccountController/account_credit_voucher_report';
$route['account-debit-voucher-report'] = 'ReportAccountController/account_debit_voucher_report';
$route['account-purchase-report'] = 'ReportAccountController/account_purchase_report';
$route['account-issue-report'] = 'ReportAccountController/account_issue_report';
$route['daily-summary-report'] = 'ReportAccountController/daily_summary_report';
$route['purpose-wise-account-credit-voucher-report'] = 'ReportAccountController/purpose_wise_account_credit_voucher_report';
$route['purpose-wise-account-debit-voucher-report'] = 'ReportAccountController/purpose_wise_account_debit_voucher_report';
$route['account-journal-voucher-report'] = 'ReportAccountController/account_journal_voucher_report';
$route['all-users-collection-report'] = 'ReportAccountController/all_users_collection_report';
$route['due-report'] = 'ReportAccountController/due_report';


//Marketting Report
$route['sent-sms-report'] = 'ReportMarkettingController/sent_sms_report';

//Reception Sell Report
$route['my-reception-sell-report'] = 'ReportAccountController/my_reception_sell_report';

//Test Report
$route['test-report'] = 'ReportTestController/daily_test_report';
$route['test-details-report'] = 'ReportTestController/test_details_report';



//Test Report
$route['test-result-report'] = 'ReportTestResultController/test_result_report';



$route['doctor-test-reference-report'] = 'DoctorController/doctor_test_reference_report';
$route['doctor-reference-payment-report'] = 'DoctorController/doctor_reference_payment_report';

//RF Report
$route['doctor-rf-report'] = 'ReportRfController/doctor_rf_report';