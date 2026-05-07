<ul class="main-left-sdiebar">
    <?php $permissions = $this->session->userdata('permissions'); ?>
    <li class="dropdown">
        <a href="<?php echo base_url('customer-panel') ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard</a>
    </li>
    <li class="dropdown">
        <a href="<?php echo base_url('customer-panel') ?>">
            <i class="fas fa-file"></i> My Report</a>
    </li>
    <!-- Add other list items as needed -->
</ul>