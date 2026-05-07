<ul class="main-left-sdiebar">
    <?php $permissions = $this->session->userdata('permissions'); ?>
    <?php if (in_array('marketting_dashboard', $permissions)) { ?>
        <li class="dropdown">
            <a href="<?php echo base_url('marketting') ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard</a>
        </li>
    <?php } ?>
    <?php if (in_array('marketting_sms_director', $permissions) || in_array('marketting_sms_employee', $permissions)|| in_array('marketting_sms_doctor', $permissions)|| in_array('marketting_sms_patient', $permissions)) { ?>
        <li class="dropdown">
        <i class="fas fa-sms"></i> SMS <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('marketting_sms_director', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url(uri: 'director-sms') ?>">Director</a></li>
                <?php } ?>
                <?php if (in_array('marketting_sms_employee', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('employee-sms') ?>">Employee</a></li>
                <?php } ?>
                <?php if (in_array('marketting_sms_doctor', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url(uri: 'doctor-sms') ?>">Doctor</a></li>
                <?php } ?>
                <?php if (in_array('marketting_sms_patient', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('patient-sms') ?>">Patient</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
  


</ul>