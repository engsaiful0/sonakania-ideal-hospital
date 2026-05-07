<ul class="main-left-sdiebar">
    <?php $permissions = $this->session->userdata('permissions'); ?>
    <?php if (in_array('account_dashboard', $permissions)) { ?>
        <li class="dropdown">
            <a href="<?php echo base_url('accounce') ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard</a>
        </li>
    <?php } ?>
    <?php if (in_array('account_journal_voucher_view', $permissions) ||in_array('account_debit_voucher_view', $permissions) || in_array('account_credit_voucher_view', $permissions)) { ?>
        <li class="dropdown">
        <i class="fas fa-receipt"></i>&nbsp;Voucher <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('account_debit_voucher_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/DebitVoucherController/view_debit_voucher"; ?>">Debit Voucher</a></li>
                <?php } ?>
                <?php if (in_array('account_credit_voucher_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/CreditVoucherController/view_credit_voucher"; ?>">Credit Voucher</a></li>
                <?php } ?>
                <?php if (in_array('account_journal_voucher_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/JournalVoucherController/view_journal_voucher"; ?>">Journal Voucher</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
  


</ul>
