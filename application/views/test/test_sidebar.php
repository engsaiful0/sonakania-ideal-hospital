<ul class="main-left-sdiebar">
    <?php $permissions = $this->session->userdata('permissions'); ?>
    <?php if (in_array('test_dashboard', $permissions)) { ?>
        <li class="dropdown">
            <a href="<?php echo base_url('test-dashboard') ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard</a>
        </li>
    <?php } ?>
    <?php if (in_array('test_add', $permissions) || in_array('test_view', $permissions)) { ?>
        <li class="dropdown">
        <i class="fas fa-microscope"></i>
        Test <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('test_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-test-entry') ?>">Add</a></li>
                <?php } ?>
                <?php if (in_array('test_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/TestController/view_test_entry"; ?>">View</a></li>
                <?php } ?>

            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('test_view_due', $permissions)) { ?>
        <li class="dropdown">
        <i class="fas fa-wallet"></i>
        Due Management <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('test_view_due', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/TestController/test_due_collection"; ?>">Due Collection</a></li>
                <?php } ?>
                 <?php if (in_array('test_due_collection_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/TestController/test_due_collection_view"; ?>">View</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('view_report_delivery', $permissions)) { ?>
        <li class="dropdown">
        <i class="fas fa-file-alt"></i> Report Delivery<span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('view_report_delivery', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/ReportDeliveryController/test_report_delivery"; ?>">Report Delivery</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
   
</ul>