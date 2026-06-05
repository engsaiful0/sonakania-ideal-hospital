<?php $permissions = $this->session->userdata('permissions'); ?>
 <ul class="main-left-sdiebar">
     <?php
        $basic_settings_account = [
            'monthly_bill',
            'account_setting_bank_name',
            'account_setting_bank_account',
            'account_setting_credit_account',
            'account_setting_debit_account',
            'account_setting_mobile_banking',
            'account_setting_payment_method'
        ];
        if (array_intersect($basic_settings_account, $permissions)) { ?>
         <li class="dropdown">
             <i class="fa fa-cog"></i> Account <span class="icon">+</span>
             <ul class="submenu">
                 <?php if (in_array('monthly_bill', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('monthly-bill') ?>">Monthly Bill</a></li>
                 <?php } ?>
                  <?php if (in_array('account_setting_bank_name', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('bank-name') ?>">Bank Name</a></li>
                 <?php } ?>
                 <?php if (in_array('account_setting_bank_account', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('bank-account') ?>">Bank Account</a></li>
                 <?php } ?>
                 <?php if (in_array('account_setting_credit_account', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('credit-account') ?>">Credit Account</a></li>
                 <?php } ?>
                 <?php if (in_array('account_setting_debit_account', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('debit-account') ?>">Debit Account</a></li>
                 <?php } ?>
                 <?php if (in_array('account_setting_mobile_banking', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('mobile-banking') ?>">Mobile Banking</a></li>
                 <?php } ?>
                 <?php if (in_array('account_setting_payment_method', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('payment-method') ?>">Payment Method</a></li>
                 <?php } ?>
             </ul>
         </li>
     <?php } 
        $basic_settings = [
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
            'basic_setting_sms_tempalte',
            'basic_setting_supplier',
            'basic_setting_user_type',
            'basic_setting_men_power_category'
        ];

        if (array_intersect($basic_settings, $permissions)) { ?>
         <li class="dropdown ">
             <i class="fa fa-cog"></i> Basic Settings <span class="icon">+</span>
             <ul class="submenu">
                 <?php if (in_array('basic_setting_blood_group', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('blood-group') ?>">Blood Group</a></li>
                 <?php } ?>
                 <?php if (in_array('basic_setting_company_profile', $permissions)) { ?>
                     <li><a class="box_a1" href="<?php echo site_url('company-profile') ?>">Company Profile</a></li>
                 <?php } ?>
                 <?php if (in_array('basic_setting_department', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('department') ?>">Department</a></li>
                 <?php } ?>
                 <?php if (in_array('basic_setting_designation', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('designation') ?>">Designation</a></li>
                 <?php } ?>
                 <?php if (in_array('basic_setting_item', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('item') ?>">Item</a></li>
                 <?php } ?>
                 <?php if (in_array('basic_setting_marital_status', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('marital-status') ?>">Marital Status</a></li>
                 <?php } ?>
                 <?php if (in_array('basic_setting_nationality', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('nationality') ?>">Nationality</a></li>
                 <?php } ?>
                 <?php if (in_array('basic_setting_company_profession', $permissions)) { ?>

                     <li><a class="box_a" href="<?php echo base_url('profession') ?>">Profession</a></li>
                 <?php } ?>
                 <?php if (in_array('basic_setting_referrred_by', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('referred-by') ?>">Reffered By</a></li>
                 <?php } ?>
                 <?php if (in_array('basic_setting_relation', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('relation') ?>">Relation</a></li>
                 <?php } ?>
                 <?php if (in_array('account_setting_mobile_banking', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('religion') ?>">Religion</a></li>
                 <?php } ?>
                 <?php if (in_array('basic_setting_report_footer', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('report-footer') ?>">Report Footer</a></li>
                 <?php } ?>
                 <?php if (in_array('basic_setting_sms_api', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('sms-api') ?>">SMS API</a></li>
                 <?php } ?>
                 <?php if (in_array('basic_setting_sms_tempalte', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('sms-template') ?>">SMS Template</a></li>
                 <?php } ?>
                 <?php if (in_array('basic_setting_supplier', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('supplier') ?>">Supplier</a></li>
                 <?php } ?>
                 <?php if (in_array('basic_setting_user_type', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('user-type') ?>">User Type</a></li>
                 <?php } ?>
                 <?php if (in_array('basic_setting_men_power_category', $permissions)) { ?>
                     <li><a class="box_a" href="<?php echo base_url('men-power-category') ?>">Men Power Category</a></li>
                 <?php } ?>
             </ul>
         </li>
     <?php } 
        $basic_settings_canteen = [
            'setting_canteen_raw_goods',
            'setting_canteen_ready_item',
            'setting_canteen_raw_goods_supplier'
        ];
        if (array_intersect($basic_settings_canteen, $permissions)) { ?>
     <li class="dropdown">
         <i class="fa fa-cog"></i> Canteen <span class="icon">+</span>
         <ul class="submenu">
             <?php if (in_array('setting_canteen_raw_goods', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('raw-goods') ?>">Raw Goods</a></li>
             <?php } ?>
             <?php if (in_array('setting_canteen_ready_item', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('ready-item') ?>">Ready Item</a></li>
             <?php } ?>
             <?php if (in_array('setting_canteen_raw_goods_supplier', $permissions)) { ?>
                 <li style="display: none;"><a class="box_a" href="<?php echo base_url('raw-goods-supplier') ?>">Raw Goods Supplier</a></li>
             <?php } ?>
         </ul>
     </li>
 <?php } 
    $basic_settings_doctor_specialization = [
        'setting_doctor_specialization'
    ];
    if (array_intersect($basic_settings_doctor_specialization, $permissions)) { ?>
     <li class="dropdown">
         <i class="fa fa-cog"></i> Doctor <span class="icon">+</span>
         <ul class="submenu">
             <?php if (in_array('setting_doctor_specialization', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('expertise') ?>">Doctor Specialization</a></li>
             <?php } ?>
         </ul>
     </li>
 <?php } 
    $basic_settings_patient = [
        'patient_setting_bed',
        'patient_setting_cabin',
        'patient_setting_cabin_category',
        'patient_setting_discharge_reason',
        'patient_setting_emergency_service',
        'patient_setting_reference_media',
        'patient_setting_phygiotherapy_service',
        'patient_setting_cabin_surgery',
        'patient_setting_ward',
        'patient_setting_ipd_service'
    ];
    if (array_intersect($basic_settings_patient, $permissions)) { ?>
     <li class="dropdown">
         <i class="fa fa-cog"></i> Patient <span class="icon">+</span>
         <ul class="submenu">
             <?php if (in_array('patient_setting_bed', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('bed') ?>">Bed</a></li>
             <?php } ?>
             <?php if (in_array('patient_setting_cabin', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('cabin') ?>">Cabin</a></li>
             <?php } ?>
             <?php if (in_array('patient_setting_cabin_category', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('cabin-category') ?>">Cabin Category</a></li>
             <?php } ?>
             <?php if (in_array('patient_setting_discharge_reason', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('discharge-reason') ?>">Discharge Reason</a></li>
             <?php } ?>
             <?php if (in_array('patient_setting_emergency_service', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('emergency-service') ?>">Emergency Service</a></li>
             <?php } ?>
             <?php if (in_array('patient_setting_ipd_service', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('ipd-service') ?>">IPD Service</a></li>
             <?php } ?>
             <?php if (in_array('patient_setting_phygiotherapy_service', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('phygiotherapy-service') ?>">Physiotherapy Service</a></li>
             <?php } ?>
             <?php if (in_array('patient_setting_reference_media', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('reference-media') ?>">Reference Media</a></li>
             <?php } ?>
             <?php if (in_array('patient_setting_cabin_surgery', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('surgery') ?>">Surgery</a></li>
             <?php } ?>
             <?php if (in_array('patient_setting_ward', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('ward') ?>">ward</a></li>
             <?php } ?>
         </ul>
     </li>
 <?php } 
    $basic_settings_pharmacy = [
        'pharmacy_setting_medicine',
        'pharmacy_setting_medicine_type',
        'pharmacy_setting_manufacturer',
        'pharmacy_setting_shelf'
    ];
    if (array_intersect($basic_settings_pharmacy, $permissions)) { ?>
     <li class="dropdown">
         <i class="fa fa-cog"></i> Pharmacy <span class="icon">+</span>
         <ul class="submenu">

             <?php if (in_array('pharmacy_setting_medicine', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('drug-list') ?>">Medicine</a></li>
             <?php } ?>
                <?php if (in_array('pharmacy_setting_medicine_type', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('medicine-type') ?>">Medicine Type</a></li>
             <?php } ?>
             <?php if (in_array('pharmacy_setting_manufacturer', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('manufacturer') ?>">Manufacturer</a></li>
             <?php } ?>
             <?php if (in_array('pharmacy_setting_shelf', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('shelf') ?>">Shelf</a></li>
             <?php } ?>
         </ul>
     </li>
 <?php } 
    $basic_settings_prescription = [
        'prescription_setting_advice',
        'prescription_setting_diagnosis',
        'prescription_setting_medicine_times'
    ];
    if (array_intersect($basic_settings_prescription, $permissions)) { ?>
     <li class="dropdown">
         <i class="fa fa-cog"></i> Prescription <span class="icon">+</span>
         <ul class="submenu">

             <?php if (in_array('prescription_setting_advice', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('advice') ?>">Advice</a></li>
             <?php } ?>
             <?php if (in_array('prescription_setting_diagnosis', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('diagnosis') ?>">Diagnosis</a></li>
             <?php } ?>
             <?php if (in_array('prescription_setting_medicine_times', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('medicin-times') ?>">Medicine Times</a></li>
             <?php } ?>

         </ul>
     </li>
 <?php } 

    $basic_settings_test = [
        'test_setting_group',
        'test_setting_sub_group',
        'test_setting_test_name'
    ];
    if (array_intersect($basic_settings_test, $permissions)) { ?>
     <li class="dropdown">
         <i class="fa fa-cog"></i> Test <span class="icon">+</span>
         <ul class="submenu">
             <?php if (in_array('test_setting_group', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('test-category') ?>">Test Category</a></li>
             <?php } ?>
             <?php if (in_array('test_setting_group', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('group') ?>">Test Group</a></li>
             <?php } ?>
             <?php if (in_array('test_setting_sub_group', $permissions)) { ?>
                 <!-- <li><a class="box_a" href="<?php echo base_url('sub-group') ?>">Sub-Group</a></li> -->
             <?php } ?>
             <?php if (in_array('test_setting_test_name', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('test-name') ?>">Test Name</a></li>
             <?php } ?>
             
             <li><a class="box_a" href="<?php echo base_url('panel') ?>">Panel</a></li>
             <li><a class="box_a" href="<?php echo base_url('section') ?>">Section</a></li>
             <li><a class="box_a" href="<?php echo base_url('parameter') ?>">Parameter</a></li>
             <?php if (in_array('add_test_configuration', $permissions) || in_array('view_test_configuration', $permissions)) { ?>
      
                <?php if (in_array('add_test_configuration', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() ?>add-test-configuration">Add</a></li>
                <?php } ?>
                <?php if (in_array('view_test_configuration', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/TestResultController/view_test_configuration"; ?>">View</a></li>
                <?php } ?>
            
    <?php } ?>
             
         </ul>
     </li>
 <?php } 
    $basic_settings_user_management = [
        'user_management_setting_user',
        'user_management_setting_user_permissions'
    ];
    if (array_intersect($basic_settings_user_management, $permissions)) { ?>
     <li class="dropdown">
         <i class="fa fa-cog"></i> User Management <span class="icon">+</span>
         <ul class="submenu">

             <?php if (in_array('user_management_setting_user', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('user') ?>">User</a></li>
             <?php } ?>

             <?php if (in_array('user_management_setting_user_permissions', $permissions)) { ?>
                 <li><a class="box_a" href="<?php echo base_url('add-permission') ?>">User Permissions</a></li>
             <?php } ?>
         </ul>
     </li>
 <?php } ?>
</ul>