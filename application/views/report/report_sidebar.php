<ul class="main-left-sdiebar">
    <?php $permissions = $this->session->userdata('permissions'); ?>
    <?php if (in_array('report_dashboard', $permissions)) { ?>
        <li class="dropdown">
            <a href="<?php echo base_url('reports-dashboard') ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard</a>
        </li>
    <?php } ?>

    <?php if (in_array('ipd_report_daily', $permissions) || in_array('ipd_service_report', $permissions)) { ?>
        <li class="dropdown">
            <i class="fa fa-file-alt"></i> IPD Report <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('ipd_report_daily', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('ipd-patient-report') ?>">IPD Patient</a></li>
                <?php } ?>
                <?php if (in_array('ipd_service_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('ipd-service-report') ?>">IPD Service</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>

    <?php if (in_array('opd_report_daily', $permissions)) { ?>
        <li class="dropdown ">
            <i class="fa fa-file-alt"></i> OPD Report<span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('opd_report_daily', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('opd-patient-admission-report') ?>">OPD Patient</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    
    <?php if (in_array('doctor_serial_report_daily', $permissions)) { ?>
        <li class="dropdown ">
            <i class="fa fa-file-alt"></i> Doctor Serial Report<span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('doctor_serial_report_daily', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('doctor-serial-report') ?>">Doctor Serial</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>

    <?php if (in_array('test_report_daily', $permissions) || in_array('test_details_report', $permissions)) { ?>
        <li class="dropdown ">
            <i class="fa fa-file-alt"></i> Test Report<span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('test_report_daily', $permissions)) { ?>
                    <li>
                        <a class="box_a" href="<?php echo site_url('test-report') ?>">Test Report</a>
                    </li>
                <?php } ?>
                <?php if (in_array('test_details_report', $permissions)) { ?>
                    <li>
                        <a class="box_a" href="<?php echo site_url('test-details-report') ?>">Test Details Report</a>
                    </li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('test_result_report', $permissions)) { ?>
        <li class="dropdown ">
            <i class="fa fa-file-alt"></i> Test Result<span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('test_result_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo site_url('test-result-report') ?>">Test Result</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('emergency_report_daily', $permissions)) { ?>
        <li class="dropdown ">
            <i class="fa fa-file-alt"></i> Emergency Report<span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('emergency_report_daily', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('emergency-report') ?>">Emergency</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('phygiotherapy_report_daily', $permissions)) { ?>
        <li class="dropdown ">
            <i class="fa fa-file-alt"></i> Physiotherapy Report<span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('phygiotherapy_report_daily', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('physiotherapy-report') ?>">Physiotherapy</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <li style="display:none!important;" class="dropdown">
        <i class="fa fa-file-alt"></i> Doctor Report<span class="icon">+</span>
        <ul class="submenu">
            <li><a class="box_a" href="<?php echo site_url('ReportController/doctor_test_reference_report') ?>">Doctor Reference</a></li>

        </ul>
    </li>
    <?php if (in_array('pharmacy_medicine_sell_report_daily', $permissions) || in_array('pharmacy_medicine_sell_return_report_daily', $permissions) || in_array('pharmacy_medicine_purchase_report_daily', $permissions) || in_array('pharmacy_medicine_purchase_return_report_daily', $permissions) || in_array('pharmacy_expire_medicine_report_daily', $permissions) || in_array('pharmacy_drug_stock_report', $permissions)) { ?>
        <li class="dropdown ">
            <i class="fa fa-file-alt"></i> Pharmacy Report<span class="icon">+</span>
            <ul class="submenu">

                <?php if (in_array('pharmacy_medicine_sell_report_daily', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('medicine-sell-report') ?>">Sales</a></li>
                <?php } ?>
                <?php if (in_array('pharmacy_medicine_sell_return_report_daily', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('medicine-sell-return-report') ?>">Sales Return</a></li>
                <?php } ?>
                <?php if (in_array('pharmacy_medicine_purchase_report_daily', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('medicine-purchase-report') ?>"> Purchase </a></li>
                <?php } ?>
                 <?php if (in_array('pharmacy_medicine_purchase_details_report_daily', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('medicine-purchase-supplier-details-report') ?>"> Purchase Details Report </a></li>
                <?php } ?>
                <?php if (in_array('pharmacy_medicine_purchase_return_report_daily', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('medicine-purchase-return-report') ?>">Purchase Return </a></li>
                <?php } ?>
                <?php if (in_array('pharmacy_expire_medicine_report_daily', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('expired-medicine-report') ?>">Expired Medicine </a></li>
                <?php } ?>
                <?php if (in_array('pharmacy_drug_stock_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('medicine-stock-report') ?>">Medicine Stock </a></li>
                <?php } ?>
                <?php if (in_array('pharmacy_drug_low_stock_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('medicine-low-stock-report') ?>">Medicine Low Stock </a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <div style="display: none;">
        <?php if (in_array('canteen_ready_item_sell_report_daily', $permissions) || in_array('canteen_goods_purchase_report_daily', $permissions) || in_array('canteen_goods_usage_report_daily', $permissions) || in_array('canteen_ready_item_inventory_report', $permissions) || in_array('canteen_ready_item_stock_report', $permissions) || in_array('canteen_goods_stock_report', $permissions)) { ?>
            <li style="display: none;" class="dropdown ">
                <i class="fa fa-file-alt"></i> Canteen Report<span class="icon">+</span>
                <ul class="submenu">
                    <?php if (in_array('canteen_ready_item_sell_report_daily', $permissions)) { ?>
                        <li><a class="box_a" href="<?php echo base_url('ready-item-sell-report') ?>">Sell</a></li>
                    <?php } ?>
                    <?php if (in_array('canteen_goods_purchase_report_daily', $permissions)) { ?>
                        <li><a class="box_a" href="<?php echo base_url('canteen-purchase-report') ?>">Purchase</a></li>
                    <?php } ?>
                    <?php if (in_array('canteen_goods_usage_report_daily', $permissions)) { ?>
                        <li><a class="box_a" href="<?php echo base_url('goods-usage-report') ?>">Goods Usage</a></li>
                    <?php } ?>
                    <?php if (in_array('canteen_ready_item_inventory_report', $permissions)) { ?>
                        <li><a class="box_a" href="<?php echo base_url('ready-item-inventory-report') ?>">Ready Item Inventory</a></li>
                    <?php } ?>
                    <?php if (in_array('canteen_ready_item_stock_report', $permissions)) { ?>
                        <li><a class="box_a" href="<?php echo base_url('canteen-goods-stock-report') ?>">Goods Stock List</a></li>
                    <?php } ?>
                    <?php if (in_array('canteen_goods_stock_report', $permissions)) { ?>
                        <li><a class="box_a" href="<?php echo base_url('ready-item-stock-report') ?>">Rady Item Stock</a></li>
                    <?php } ?>

                </ul>
            </li>
        <?php } ?>
    </div>
    <?php if (in_array('hrm_report_director', $permissions) || in_array('hrm_report_employee', $permissions) || in_array('hrm_report_doctor', $permissions) || in_array('hrm_report_attendance', $permissions)) { ?>
        <li class="dropdown ">
            <i class="fa fa-file-alt"></i> HRM Report<span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('hrm_report_director', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('hrm-director-report') ?>">Director List</a></li>
                <?php } ?>
                <?php if (in_array('hrm_report_employee', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('hrm-employee-report') ?>">Employee List</a></li>
                <?php } ?>
                <?php if (in_array('hrm_report_doctor', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('hrm-doctor-report') ?>">Doctor List</a></li>
                <?php } ?>
                <li style="display: none!important;"><a class="box_a" href="<?php echo base_url('hrm-increment-report') ?>">Increment</a></li>
                <?php if (in_array('hrm_report_attendance', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('hrm-attendance-report') ?>">Attendance</a></li>
                <?php } ?>
                <li style="display:none!important;"><a class="box_a" href="<?php echo base_url('salary-sheet') ?>">Salary Sheet</a></li>
                <li style="display:none!important;"><a class="box_a" href="<?php echo base_url('director-ledger') ?>">Director Ledger</a></li>
                <li style="display:none!important;"><a class="box_a" href="<?php echo site_url('ReportController/employee_salary_report') ?>">Employee
                        Salary Report</a></li>
                <li style="display:none!important;"><a class="box_a" href="<?php echo site_url('ReportController/all_employee_salary_report') ?>">All
                        Employee Report</a></li>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('doctor_rf_report', $permissions) || in_array('media_rf_report', $permissions) || in_array('employee_rf_report', $permissions)) { ?>
        <li class="dropdown ">
            <i class="fa fa-file-alt"></i> RF (Referred Fees)<span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('doctor_rf_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('doctor-rf-report') ?>">Doctor RF</a></li>
                <?php } ?>
                <?php if (in_array('media_rf_report', $permissions)) { ?>
                    <li style="display: none;"><a class="box_a" href="<?php echo base_url('media-rf-report') ?>">Media RF</a></li>
                <?php } ?>
                <?php if (in_array('employee_rf_report', $permissions)) { ?>
                    <li style="display: none;"><a class="box_a" href="<?php echo base_url('employee-rf-report') ?>">Employee RF</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (
        in_array('monthly_software_bill', $permissions) ||
        in_array('account_journal_voucher_report', $permissions) ||
        in_array('account_credit_voucher_report', $permissions) ||
        in_array('account_debit_voucher_report', $permissions) ||
        in_array('account_purchase_report', $permissions) ||
        in_array('account_issue_report', $permissions) ||
        in_array('account_stock_report', $permissions)
    ) { ?>

        <li class="dropdown ">
            <i class="fa fa-file-alt"></i> Account Report<span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('monthly_software_bill', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('monthly-software-bill') ?>">Monthly Bill</a></li>
                <?php } ?>
                <?php if (in_array('account_credit_voucher_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('account-credit-voucher-report') ?>">Credit</a></li>
                <?php } ?>
                <?php if (in_array('account_credit_voucher_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('purpose-wise-account-credit-voucher-report') ?>">Purpose Wise Credit</a></li>
                <?php } ?>
                <?php if (in_array('account_debit_voucher_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('account-debit-voucher-report') ?>">Debit</a></li>
                <?php } ?>
                <?php if (in_array('account_debit_voucher_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('purpose-wise-account-debit-voucher-report') ?>">Purpose Wise Debit</a></li>
                <?php } ?>
                <?php if (in_array('account_journal_voucher_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('account-journal-voucher-report') ?>">Journal Voucher</a></li>
                <?php } ?>
                <?php if (in_array('account_purchase_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('account-purchase-report') ?>">Purchase</a></li>
                <?php } ?>
                <?php if (in_array('account_issue_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('account-issue-report') ?>">Issue</a></li>
                <?php } ?>
                <?php if (in_array('account_stock_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('account-stock-report') ?>">Stock</a></li>
                <?php } ?>
                <?php if (in_array('due_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('due-report') ?>">Due Report</a></li>
                <?php } ?>
                <?php if (in_array('daily_summary_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('daily-summary-report') ?>">Income Vs Expense</a></li>
                <?php } ?>
                <?php if (in_array('user_id_wise_summary_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('all-users-collection-report') ?>">All Users Collection</a></li>
                <?php } ?>

            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('marketting_sent_sms', $permissions)) { ?>
        <li class="dropdown ">
            <i class="fa fa-file-alt"></i> Marketting Report<span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('marketting_sent_sms', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('sent-sms-report') ?>">Sent SMS</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('canteen_ready_item_sell_report_daily', $permissions) || in_array('canteen_goods_purchase_report_daily', $permissions) || in_array('canteen_goods_usage_report_daily', $permissions) || in_array('canteen_ready_item_inventory_report', $permissions) || in_array('canteen_ready_item_stock_report', $permissions) || in_array('canteen_goods_stock_report', $permissions)) { ?>
        <li class="dropdown" style="display:none!important;">
            <i class="fa fa-file-alt"></i> Bank Report<span class="icon">+</span>
            <ul class="submenu">
                <li><a class="box_a" href="<?php echo site_url('ReportController/balance_report') ?>">Balance</a>
                </li>
                <li><a class="box_a" href="<?php echo site_url('ReportController/bank_deposit_report') ?>">Bank Deposit
                        Report</a></li>
                <li><a class="box_a" href="<?php echo site_url('ReportController/bank_withdraw_report') ?>">Bank Withdraw
                        Report</a></li>
                <li><a class="box_a" href="<?php echo site_url('ReportController/bank_balance_report') ?>">Bank Balnce
                        Report</a></li>
            </ul>
        </li>
    <?php } ?>
</ul>