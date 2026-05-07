<ul class="main-left-sdiebar">
    <?php $permissions = $this->session->userdata('permissions'); ?>

    <?php if (in_array('hrm_dashboard', $permissions)) { ?>
        <li class="dropdown">
            <a href="<?php echo base_url('hrm') ?>">
                <i class="fas fa-tachometer-alt"></i> HRM Dashboard</a>
        </li>
    <?php } ?>
  
    <?php if (in_array('hrm_director_add', $permissions) || in_array('hrm_director_view', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-users"></i>
            Director <span class="icon">+</span>
            <ul class="submenu">
            <?php if (in_array('hrm_director_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-director') ?>">Add Director</a></li>
                <?php } ?>
                <?php if (in_array('hrm_director_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('view-director') ?>">View Director</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    
    <?php if (in_array('hrm_employee_add', $permissions) || in_array('hrm_employee_view', $permissions)) { ?>
        <li class="dropdown">
        <i class="fas fa-user"></i> Employee <span class="icon">+</span>

            <ul class="submenu">
                <?php if (in_array('hrm_employee_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-employee') ?>">Add Employee</a></li>
                <?php } ?>
                <?php if (in_array('hrm_employee_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('view-employee') ?>">View Employee</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('hrm_employee_salary_add', $permissions) || in_array('hrm_employee_salary_view', $permissions)) { ?>
        <li class="dropdown">
         <i class="fas fa-sack-dollar"></i> Salary <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('hrm_employee_salary_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-salary') ?>">Add Salary</a></li>
                <?php } ?>
                <?php if (in_array('hrm_employee_salary_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('view-salary') ?>">View Salary</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>

    <?php if (in_array('hrm_increment_add', $permissions) || in_array('hrm_increment_view', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-plus-circle"></i> Increment <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('hrm_increment_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-increment') ?>">Add Increment</a></li>
                <?php } ?>
                <?php if (in_array('hrm_increment_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('view-increment') ?>">View Increment</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('hrm_doctor_add', $permissions) || in_array('hrm_doctor_view', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-user-md"></i> Doctor <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('hrm_doctor_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-doctor') ?>">Add Doctor</a></li>
                <?php } ?>
                <?php if (in_array('hrm_doctor_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('view-doctor') ?>">View Doctor</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('hrm_single_attendance', $permissions) || in_array('hrm_all_attendance', $permissions) || in_array('hrm_attendance_view', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-user-check"></i> Attendance <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('hrm_single_attendance', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-attendance') ?>">Single Attendance</a></li>
                <?php } ?>
                <?php if (in_array('hrm_all_attendance', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('all-attendance') ?>">All Attendance</a></li>
                <?php } ?>
                <?php if (in_array('hrm_attendance_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('view-attendance') ?>">View Attendance</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>

</ul>