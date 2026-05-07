<ul class="box f-right">
    <li><a href="<?php echo base_url() ?>profile-update"><span><strong>My profile &raquo;</strong></span></a></li>
</ul>

<ul class="box">
    <li><a href="<?php echo site_url('HomeController/home') ?>"><span>Home</span></a></li>

    <li><a href="<?php echo site_url('PrescriptionController/add_prescription') ?>"><span>Prescription</span></a></li>
    <li><a href="<?php echo site_url('PatientController/add_ipd_patient') ?>"><span>Patient</span></a></li>
    <?php
    $user_type = $this->session->userdata('user_type');
  //  if ($user_type == 'cash_user' || $user_type == 'admin'):
        ?>
        <li><a href="<?php echo site_url('TestController/add_test_entry') ?>"><span>Test</span></a></li>
        <li><a href="<?php echo site_url('DoctorController/add_doctor') ?>"><span>Doctor</span></a></li>
        <?php
    //endif;
    ?>

    <?php
    $user_type = $this->session->userdata('user_type');
  //  if ($user_type == 'lab_user' || $user_type == 'admin'):
        ?>
        <li><a href="<?php echo site_url('TestResultController/add_test_result') ?>"><span>Test Result</span></a></li>
        <?php
   // endif;

    //if ($user_type == 'pharmacy_user' || $user_type == 'admin'):
        ?>
        <li><a href="<?php echo site_url('PharmacyController/pharmacy') ?>"><span>Pharmacy</span></a></li>
        <li><a href="<?php echo site_url('PurchaseController/add_purchase_drug') ?>"><span>Purchase Drug</span></a></li>
        <?php
   // endif;
    ?>


    <!--<li><a href="<?php echo site_url('RetailSaleController') ?>"><span>Retail Sell</span></a></li>  Active -->
    <!--<li><a href="<?php echo site_url('SupplierController/add_supplier') ?>"><span>Supplier</span></a></li>-->
    <?php
    $user_type = $this->session->userdata('user_type');
 //   if ($user_type == 'cash_user' || $user_type == 'admin'):
        ?>
        <li><a href="<?php echo site_url('EmployeeController/add_employee') ?>"><span>Employee</span></a></li>
        <li><a href="<?php echo site_url('ExpenseController/add_expense') ?>"><span>Expense</span></a></li>

        <?php
   // endif;
    ?>



    <?php
    $user_type = $this->session->userdata('user_type');
  //  if ($user_type == 'cash_user' || $user_type == 'admin'):
        ?>
        <li><a href="<?php echo site_url('ReportController/income_report') ?>"><span>Reports</span></a></li>
        <?php
   // endif;
    ?>

    <li><a href="<?php echo site_url('HomeController/backup') ?>"><span>Backup</span></a></li>





</ul>