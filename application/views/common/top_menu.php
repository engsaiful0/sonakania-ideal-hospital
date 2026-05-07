<ul class="box f-right">
    <!-- Notification Icon with Badge -->
    <li style="display: none;" class="dropdown">
        <a href="#" id="notificationDropdown" class="position-relative" data-bs-toggle="dropdown">
            <i style="padding-top: 20px;" class="fas fa-bell fa-lg"></i>
            <span id="notificationBadge" class="notification-badge">0</span>
        </a>

        <!-- Notification Dropdown -->
        <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="notificationDropdown">
            <li>
                <h6 class="dropdown-header">Notifications</h6>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <div id="notificationList"></div>
        </ul>
    </li>

    <!-- Profile Update Link -->
    <li>
        <a href="<?php echo base_url('profile-update'); ?>">
            <span><strong>My Profile &raquo;</strong></span>
        </a>
    </li>
</ul>

<!-- CSS for Badge -->
<style>
    .notification-badge {
        position: absolute;
        top: -20px;
        right: 0px;
        margin-right:3px;
        background: none!important;
        background-color: red!important;
        color: white;
        font-size: 10px;
        padding-left: 10px!important;
        border-radius: 50%;
        display: none;
        /* Hidden initially */
    }

    .dropdown-menu {
        width: 300px;
        max-height: 300px;
        overflow-y: auto;
    }
</style>
<script>
    $(document).ready(function() {
        let notifications = {
            test: ["Test notification 1", "Test notification 2"],
            opd: ["OPD notification 1", "OPD notification 2"]
        };

        function updateNotifications() {
            let totalCount = notifications.test.length + notifications.opd.length;
            $("#notificationBadge").text(totalCount).toggle(totalCount > 0);

            let listHtml = "";
            for (const [category, items] of Object.entries(notifications)) {
                items.forEach(notif => {
                    listHtml += `<li class="dropdown-item">${notif}</li>`;
                });
            }
            $("#notificationList").html(listHtml || "<li class='dropdown-item text-muted'>No new notifications</li>");
        }

        $("#notificationDropdown").on("click", function() {
            updateNotifications();
        });

        updateNotifications();
    });
</script>
<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Add some CSS for styling -->
<style>
    .fa-bell {
        font-size: 31px;
        margin-right: 5px;
    }

    .badge {
        background-color: red;
        color: white;
        padding: 3px 6px;
        font-size: 12px;
        border-radius: 50%;
        position: relative;
        top: -10px;
        left: -5px;
    }
</style>

<?php $permissions = $this->session->userdata('permissions'); ?>
<ul class="box">
    <li><a href="<?php echo base_url('home') ?>"><span>Home</span></a></li>
    <li style="display: none;"><a href="<?php echo base_url('prescription-dashboard') ?>"><span>Prescription</span></a></li>

    <?php
    $requiredPermissions = [
        'patient_dashboard',
        'opd_patient_add',
        'opd_patient_edit',
        'opd_patient_view',
        'opd_patient_print',
        'opd_patient_delete',
        'opd_patient_search',
        'ipd_patient_add',
        'ipd_patient_edit',
        'ipd_patient_view',
        'ipd_patient_print',
        'ipd_patient_delete',
        'ipd_patient_search',
        'ipd_service_add',
        'ipd_service_edit',
        'ipd_service_view',
        'ipd_service_print',
        'ipd_service_delete',
        'ipd_service_search',
        'add_emergency',
        'edit_emergency',
        'view_emergency',
        'print_emergency',
        'delete_emergency',
        'search_emergency',
        'add_phygiotherapy',
        'edit_phygiotherapy',
        'view_phygiotherapy',
        'print_phygiotherapy',
        'delete_phygiotherapy',
        'search_phygiotherapy',
        'add_ot_service',
        'edit_ot_service',
        'view_ot_service',
        'print_ot_service',
        'delete_ot_service',
        'search_ot_service',
        'add_discharge',
        'edit_discharge',
        'view_discharge',
        'print_discharge',
        'delete_discharge',
        'search_discharge',
        'add_discharge_slip',
        'edit_discharge_slip',
        'view_discharge_slip',
        'print_discharge_slip',
        'delete_discharge_slip',
        'search_discharge_slip'
    ];

    if (!empty(array_intersect($requiredPermissions, $permissions))) {
    ?>
        <li><a href="<?php echo base_url('patient-dashbaord') ?>"><span>Patient</span></a></li>
    <?php
    }
    ?>


    <?php
    // Test Dashboard Permissions
    $testPermissions = [
        'test_dashboard',
        'test_add',
        'test_edit',
        'test_view',
        'test_print',
        'test_delete',
        'test_search',
        'print_due',
        'search_due',
        'view_due',
        'report_delivery_now',
        'view_report_delivery',
        'search_report_delivery'
    ];
    if (!empty(array_intersect($testPermissions, $permissions))) {
    ?>
        <li><a href="<?php echo base_url('test-dashboard') ?>"><span>Test</span></a></li>
    <?php
    }

    // Lab Dashboard Permissions
    $labPermissions = [
        'lab_dashboard',
        'add_test_result',
        'edit_test_result',
        'view_test_result',
        'print_test_result',
        'delete_test_result',
        'search_test_result',
        'add_test_configuration',
        'edit_test_configuration',
        'view_test_configuration',
        'search_test_configuration',
        'delete_test_configuration'
    ];
    if (!empty(array_intersect($labPermissions, $permissions))) {
    ?>
        <li><a href="<?php echo base_url('lab-dashboard') ?>"><span>Lab</span></a></li>
    <?php
    }

    // Pharmacy Dashboard Permissions
    $pharmacyPermissions = [
        'pharmacy_dashboard',
        'add_medicine_sell',
        'edit_medicine_sell',
        'view_medicine_sell',
        'print_medicine_sell',
        'return_medicine_sell',
        'search_medicine_sell',
        'edit_medicine_sell_return',
        'print_medicine_sell_return',
        'delete_medicine_sell_return',
        'search_medicine_sell_return',
        'add_medicine_purchase',
        'edit_medicine_purchase',
        'view_medicine_purchase',
        'print_medicine_purchase',
        'return_medicine_purchase',
        'search_medicine_purchase',
        'edit_medicine_purchase_return',
        'print_medicine_purchase_return',
        'delete_medicine_purchase_return',
        'search_medicine_purchase_return',
        'pharmacny_expired_medicine_add',
        'pharmacny_expired_medicine_add',
        'pharmacny_expired_medicine_edit',
        'pharmacny_expired_medicine_view',
        'pharmacny_expired_medicine_print',
        'pharmacny_expired_medicine_delete',
        'pharmacny_expired_medicine_search',
        'pharmacy_drug_add',
        'pharmacy_drug_edit',
        'pharmacy_drug_view',
        'pharmacy_drug_delete',
        'pharmacy_stock_report',
        'pharmacy_stock_report_search',
        'pharmacy_my_sale_report',
    ];
    if (!empty(array_intersect($pharmacyPermissions, $permissions))) {
    ?>
        <li><a href="<?php echo base_url('pharmacy') ?>"><span>Pharmacy</span></a></li>
    <?php
    }

    // Canteen Dashboard Permissions
    $canteenPermissions = [
        'canteen_dashboard',
        'canteen_sell_add',
        'canteen_sell_edit',
        'canteen_sell_view',
        'canteen_sell_print',
        'canteen_sell_delete',
        'canteen_sell_search',
        'canteen_purchase_add',
        'canteen_purchase_edit',
        'canteen_purchase_view',
        'canteen_purchase_print',
        'canteen_purchase_delete',
        'canteen_purchase_search',
        'canteen_goods_usage_add',
        'canteen_goods_usage_edit',
        'canteen_goods_usage_view',
        'canteen_goods_usage_print',
        'canteen_goods_usage_delete',
        'canteen_goods_usage_search',
        'canteen_inventory_ready_item_add',
        'canteen_inventory_ready_item_edit',
        'canteen_inventory_ready_item_view',
        'canteen_inventory_ready_item_print',
        'canteen_inventory_ready_item_delete',
        'canteen_inventory_ready_item_search',
        'canteen_ready_item_stock_list',
        'canteen_goods_list',
    ];
    if (!empty(array_intersect($canteenPermissions, $permissions))) {
    ?>
        <!-- <li><a href="<?php echo base_url('canteen-dashboard') ?>"><span>Canteen</span></a></li> -->
    <?php
    }

    // HRM Dashboard Permissions
    $hrmPermissions = [
        'employee_dashboard',
        'hrm_director_add',
        'hrm_director_edit',
        'hrm_director_view',
        'hrm_director_print',
        'hrm_director_delete',
        'hrm_director_search',
        'hrm_employee_add',
        'hrm_employee_edit',
        'hrm_employee_view',
        'hrm_employee_print',
        'hrm_employee_delete',
        'hrm_employee_search',
        'hrm_increment_add',
        'hrm_increment_edit',
        'hrm_increment_view',
        'hrm_increment_print',
        'hrm_increment_delete',
        'hrm_increment_search',
        'hrm_doctor_add',
        'hrm_doctor_edit',
        'hrm_doctor_view',
        'hrm_doctor_print',
        'hrm_doctor_delete',
        'hrm_doctor_search',
        'hrm_single_attendance',
        'hrm_all_attendance',
        'hrm_attendance_view',
        'hrm_attendance_delete',
        'hrm_attendance_search',
    ];
    if (!empty(array_intersect($hrmPermissions, $permissions))) {
    ?>
        <li><a href="<?php echo base_url('hrm') ?>"><span>HRM</span></a></li>
    <?php
    }
    // Account Dashboard Permissions
    $accountPermissions = [
        'account_dashboard',
        'account_purchase_add',
        'account_purchase_edit',
        'account_purchase_view',
        'account_purchase_print',
        'account_purchase_delete',
        'account_purchase_search',
        'account_issue_add',
        'account_issue_edit',
        'account_issue_view',
        'account_issue_print',
        'account_issue_delete',
        'account_issue_search',
        'account_goods_stock',
    ];
    if (!empty(array_intersect($accountPermissions, $permissions))) {
    ?>
        <li><a href="<?php echo base_url('general-store') ?>"><span>Store</span></a></li>
    <?php
    }
    // Account Dashboard Permissions
    $accountPermissions = [
        'account_dashboard',
        'account_debit_voucher_add',
        'account_debit_voucher_edit',
        'account_debit_voucher_view',
        'account_debit_voucher_print',
        'account_debit_voucher_delete',
        'account_debit_voucher_search',
        'account_credit_voucher_add',
        'account_credit_voucher_edit',
        'account_credit_voucher_view',
        'account_credit_voucher_print',
        'account_credit_voucher_delete',
        'account_credit_voucher_search',
        'account_journal_voucher_add',
        'account_journal_voucher_edit',
        'account_journal_voucher_view',
        'account_journal_voucher_print',
        'account_journal_voucher_delete',
        'account_journal_voucher_search',
    ];
    if (!empty(array_intersect($accountPermissions, $permissions))) {
    ?>
        <li><a href="<?php echo base_url('accounce') ?>"><span>Account</span></a></li>
    <?php
    }

    // Marrketting Dashboard Permissions
    $markettingPermissions = [
        'marketting_dashboard',
        'marketting_sms_director',
        'marketting_sms_employee',
        'marketting_sms_doctor',
        'marketting_sms_patient',

    ];
    if (!empty(array_intersect($markettingPermissions, $permissions))) {
    ?>
        <li><a href="<?php echo base_url('marketting') ?>"><span>Marketting</span></a></li>
    <?php
    }

    // Report Dashboard Permissions
    $reportPermissions = [
        'ipd_report_daily',
        'opd_report_daily',
        'emergency_report_daily',
        'phygiotherapy_report_daily',
        'ot_service_report_daily',
        'discharge_report_daily',
        'discharge_slip_report_daily',
        'test_report_daily',
        'test_due_daily',
        'test_report_delivery_daily',

        'lab_report_daily',
        'pharmacy_medicine_sell_report_daily',
        'pharmacy_medicine_purchase_report_daily',
        'pharmacy_expire_medicine_report_daily',
        'pharmacy_drug_stock_report',
        'canteen_ready_item_sell_report_daily',
        'canteen_goods_purchase_report_daily',
        'canteen_goods_usage_report_daily',
        'canteen_ready_item_inventory_report',
        'canteen_ready_item_stock_report',


        'canteen_goods_stock_report',
        'hrm_report_director',
        'hrm_report_increment',
        'hrm_report_employee',
        'hrm_report_doctor',
        'hrm_report_attendance',
        'account_credit_voucher_report',
        'account_debit_voucher_report',
        'account_purchase_report',
        'account_issue_report',

        'account_stock_report',
        'marketting_director_sms',
        'marketting_employee_sms',
        'marketting_doctor_sms',
        'marketting_patient_sms',

    ];
    if (!empty(array_intersect($reportPermissions, $permissions))) {
    ?>
        <li><a href="<?php echo base_url('reports-dashboard') ?>"><span>Reports</span></a></li>
    <?php
    }
    // Settings Dashboard Permissions
    $settingsPermissions = [
        'setting_dashboard',
        'account_setting_bank_name',
        'account_setting_bank_account',
        'account_setting_credit_account',
        'account_setting_debit_account',
        'account_setting_mobile_banking',
        'account_setting_payment_method',
        'basic_setting_blood_group',
        'basic_setting_company_profile',
        'basic_setting_department',

        'basic_setting_designation',
        'basic_setting_item',
        'basic_setting_marital_status',
        'basic_setting_nationality',
        'basic_setting_company_profession',
        'basic_setting_referrred_by',
        'basic_setting_relation',
        'basic_setting_report_footer',
        'basic_setting_sms_api',
        'basic_setting_nationality',


        'canteen_goods_stock_report',
        'basic_setting_sms_tempalte',
        'basic_setting_user_type',
        'basic_setting_men_power_category',
        'setting_canteen_raw_goods',
        'setting_canteen_ready_item',
        'setting_canteen_raw_goods_supplier',
        'setting_doctor_specialization',
        'patient_setting_bed',
        'patient_setting_cabin',

        'patient_setting_cabin_category',
        'patient_setting_discharge_reason',
        'patient_setting_emergency_service',
        'patient_setting_ipd_service',
        'patient_setting_phygiotherapy_service',


        'patient_setting_reference_media',
        'prescription_setting_diagnosis',
        'prescription_setting_medicine_times',
        'test_setting_group',
        'test_setting_test_name',

        'user_management_setting_user',
        'user_management_setting_user_permissions',
        'pharmacy_setting_medicine',
        'pharmacy_setting_medicine_type',
        'pharmacy_setting_manufacturer',
        'pharmacy_setting_shelf'


    ];
    if (!empty(array_intersect($settingsPermissions, $permissions))) {
    ?>
        <li><a href="<?php echo base_url('settings') ?>"><span>Settings</span></a></li>
    <?php
    }

    // database backup Permissions
    $database_backupPermissions = [
        'database_backup'
    ];
    if (!empty(array_intersect($database_backupPermissions, $permissions))) {
    ?>
        <li><a href="<?php echo base_url('database-backup') ?>"><span>Backup</span></a></li>
    <?php
    }

    ?>










</ul>
