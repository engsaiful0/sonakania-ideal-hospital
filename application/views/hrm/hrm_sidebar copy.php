<ul class="main-left-sdiebar">
    <li class="dropdown">
        <a href="<?php echo base_url('hrm') ?>">
            <i class="fas fa-tachometer-alt"></i> HRM Dashbord</a>
    </li>
    <li class="dropdown">
        <i class="fas fa-film"></i>
        Director <span class="icon">+</span>
        <ul class="submenu">
            <li><a class="box_a" href="<?php echo base_url('add-director') ?>">Add Director</a></li>
            <li><a class="box_a" href="<?php echo base_url('view-director') ?>">Viwe Director</a></li>
        </ul>
    </li>
    <li class="dropdown">
        <i class="fas fa-user-tie"></i> Employee <span class="icon">+</span>
        <ul class="submenu">
            <li><a class="box_a" href="<?php echo base_url('add-employee') ?>">Add Employee</a></li>
            <li><a class="box_a" href="<?php echo base_url('view-employee') ?>">View Employee</a></li>
            <li><a class="box_a" href="<?php echo base_url('view-increment') ?>">Increment</a></li>

        </ul>
    </li>
    <li class="dropdown ">
        <i class="fas fa-user-md"></i> Doctor <span class="icon">+</span>
        <ul class="submenu">
            <li><a class="box_a" href="<?php echo base_url('add-doctor') ?>">Add</a></li>
            <li><a class="box_a" href="<?php echo base_url('view-doctor') ?>">View</a></li>
        </ul>
    </li>
    <li class="dropdown ">
        <i class="fas fa-user-check"></i> Attendance <span class="icon">+</span>
        <ul class="submenu">
            <li><a class="box_a" href="<?php echo base_url('add-attendance') ?>">Single Attendance</a></li>
            <li><a class="box_a" href="<?php echo base_url('all-attendance') ?>">all Attendance</a></li>
            <!-- <li><a class="box_a" href="<?php echo base_url('bulk-attendance') ?>">Bulk Attendance</a></li> -->
            <li><a class="box_a" href="<?php echo base_url('view-attendance') ?>">View Attendance</a></li>
        </ul>
    </li>
</ul>