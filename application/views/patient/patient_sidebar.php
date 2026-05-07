<ul class="main-left-sdiebar">
    <?php $permissions = $this->session->userdata('permissions'); ?>
    <?php if (in_array('patient_dashboard', $permissions)) { ?>
        <li class="dropdown">
            <a href="<?php echo base_url('patient-dashbaord') ?>">
                <i class="fas fa-tachometer-alt"></i>Dashboard</a>
        </li>
    <?php } ?>

    <?php if (in_array('opd_patient_add', $permissions) || in_array('opd_patient_view', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-user-injured"></i> OPD <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('opd_patient_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-opd-patient') ?>">Add OPD Patient</a></li>
                <?php } ?>

                <?php if (in_array('opd_patient_view', $permissions)) { ?>
                    <li>
                        <a class="box_a" href="<?php echo base_url() . "index.php/OpdPatientController/view_opd_patient"; ?>">View OPD Patient</a>
                    </li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('doctor_serial_add', $permissions) || in_array('doctor_serial_view', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-user-injured"></i> Doctor Serial <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('doctor_serial_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-doctor-serial') ?>">Add Doctor Serial</a></li>
                <?php } ?>

                <?php if (in_array('doctor_serial_view', $permissions)) { ?>
                    <li>
                        <a class="box_a" href="<?php echo base_url() . "index.php/DoctorSerialController/view_doctor_serial"; ?>">View Doctor Serial</a>
                    </li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('ipd_with_cabin', $permissions) ||in_array('ipd_with_bed', $permissions) || in_array('ipd_patient_add', $permissions) || in_array('ipd_patient_view', $permissions) || in_array('ipd_service_add', $permissions) || in_array('ipd_service_view', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-procedures"></i> <!-- Represents a hospital -->
            IPD <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('ipd_patient_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-ipd-patient') ?>">Add IPD Patient</a></li>
                <?php } ?>

                <?php if (in_array('ipd_patient_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/IpdPatientController/view_ipd_patient"; ?>">View IPD Patient</a></li>
                <?php } ?>
                <?php if (in_array('ipd_with_bed', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('ipd-with-bed') ?>">IPD with Bed</a></li>
                <?php } ?>
                  <?php if (in_array('ipd_with_cabin', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('ipd-with-cabin') ?>">IPD with Cabin</a></li>
                <?php } ?>
                <?php if (in_array('ipd_service_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-ipd-service') ?>">Add IPD Service</a></li>
                <?php } ?>

                <?php if (in_array('ipd_service_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/IpdServiceController/view_ipd_service"; ?>">View IPD Service</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('add_emergency', $permissions) || in_array('view_emergency', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-ambulance"></i> Emergency <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('add_emergency', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-emergency') ?>">Add Emergency</a></li>
                <?php } ?>

                <?php if (in_array('view_emergency', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/EmergencyController/view_emergency"; ?>">View Emergency</a>
                    </li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('add_phygiotherapy', $permissions) || in_array('view_phygiotherapy', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-wheelchair"></i> Physiotherapy <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('add_phygiotherapy', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-phygiotherapy') ?>">Add Physiotherapy</a></li>
                <?php } ?>

                <?php if (in_array('view_phygiotherapy', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/PhygiotherapyController/view_phygiotherapy"; ?>">View Physiotherapy</a>
                    </li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('add_ot_service', $permissions) || in_array('view_ot_service', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-syringe"></i> OT Service<span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('add_ot_service', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-ot-service') ?>">Add OT Service</a></li>
                <?php } ?>

                <?php if (in_array('view_ot_service', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/OTServiceController/view_ot_service"; ?>">View OT Service</a>
                    </li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('add_discharge', $permissions) || in_array('view_discharge', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-door-open"></i>
            Discharge <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('add_discharge', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-discharge') ?>">Add (Demo Bill)</a></li>
                <?php } ?>

                <?php if (in_array('view_discharge', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/DischargeController/view_discharge"; ?>">View (Demo Bill)</a>
                    </li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>

    <?php if (in_array('add_discharge_slip', $permissions) || in_array('view_discharge_slip', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-file-medical"></i> Discharge Slip <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('add_discharge_slip', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-discharge-slip') ?>">Add</a></li>
                <?php } ?>

                <?php if (in_array('view_discharge_slip', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/DischargeSlipController/view_discharge_slip"; ?>">View</a>
                    </li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <li class="dropdown">
        <i class="fas fa-file-medical"></i> Report <span class="icon">+</span>
        <ul class="submenu">
            <li><a class="box_a" href="<?php echo base_url('reception-sell-report') ?>">My Sell Report</a></li>


        </ul>
    </li>
</ul>