<ul class="main-left-sdiebar">
<?php $permissions = $this->session->userdata('permissions'); ?>
<?php if (in_array('lab_dashboard', $permissions)) { ?>
        <li class="dropdown">
            <a href="<?php echo base_url('lab-dashboard') ?>">
                <i class="fas fa-tachometer-alt"></i>Dashboard</a>
        </li>
    <?php } ?>
    <?php if (in_array('add_test_result', $permissions) || in_array('view_test_result', $permissions)) { ?>
        <li class="dropdown">
        <i class="fas fa-clipboard-check"></i> Test Result <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('add_test_result', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() ?>add-test-result">Add</a></li>
                <?php } ?>
                <?php if (in_array('view_test_result', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/TestResultController/view_test_result"; ?>">View</a></li>
                <?php } ?>
               
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('add_test_configuration', $permissions) || in_array('view_test_configuration', $permissions)) { ?>
        <li class="dropdown">
        <i class="fas fa-file-medical"></i> Test Configuration <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('add_test_configuration', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() ?>add-test-configuration">Add</a></li>
                <?php } ?>
                <?php if (in_array('view_test_configuration', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/TestResultController/view_test_configuration"; ?>">View</a></li>
                <?php } ?>
               
            </ul>
        </li>
    <?php } ?>
    
</ul>