<ul class="main-left-sdiebar">
    <li class="dropdown">
        <a href="<?php echo base_url('doctor-dashboard') ?>">
            <i class="fas fa-tachometer-alt"></i> Dashbord</a>
    </li>
    <li class="dropdown">
    <i class="fas fa-user-md"></i> Doctor <span class="icon">+</span>

        <ul class="submenu">
            <li><a class="box_a" href="<?php echo base_url('add-doctor') ?>">Add</a></li>
            <li><a class="box_a" href="<?php echo base_url('view-doctor') ?>">View</a></li>
        </ul>
    </li>
    <li class="dropdown">
    <i class="fas fa-wallet"></i> Payment <span class="icon">+</span>
        <ul class="submenu">
            <li><a class="box_a" href="<?php echo base_url('doctors-payment') ?>">Add</a></li>
            <li><a class="box_a" href="<?php echo base_url('view-doctors-payment') ?>">Viwe</a></li>

        </ul>
    </li>
    <li class="dropdown ">
    <i class="fas fa-file-alt"></i>  Report <span class="icon">+</span>
        <ul class="submenu">
            <li><a class="box_a" href="<?php echo base_url('doctor-test-reference-report') ?>">Reference Report</a></li>
            <li><a class="box_a" href="<?php echo base_url('doctor-reference-payment-report') ?>">Commission & Paid Report</a></li>

        </ul>
    </li>
</ul>