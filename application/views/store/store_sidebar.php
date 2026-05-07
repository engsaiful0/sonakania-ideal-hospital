<ul class="main-left-sdiebar">
    <?php $permissions = $this->session->userdata('permissions'); ?>
    <?php if (in_array('account_dashboard', $permissions)) { ?>
        <li class="dropdown">
            <a href="<?php echo base_url('general-store') ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard</a>
        </li>
    <?php } ?>

    <?php if (in_array('account_purchase_add', $permissions) || in_array('account_purchase_view', $permissions)) { ?>
        <li class="dropdown">
        <i class="fas fa-shopping-cart"></i>&nbsp;Purchase <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('account_purchase_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-purchase-goods') ?>">Add Purchase</a></li>
                <?php } ?>
                <?php if (in_array('account_purchase_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/PurchaseGoodsController/view_purchase_goods"; ?>">View Purchase</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('account_issue_add', $permissions) || in_array('account_issue_view', $permissions)) { ?>
        <li class="dropdown">
        <i class="fas fa-info-circle"></i>&nbsp;Issue <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('account_issue_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-issue') ?>">Add Issue</a></li>
                <?php } ?>
                <?php if (in_array('account_issue_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/IssueController/view_issue"; ?>">View Issue</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('account_goods_stock', $permissions)) { ?>
        <li class="dropdown">
        <i class="fas fa-file-alt"></i>&nbsp;Report <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('account_goods_stock', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('goods-stock-report') ?>">Goods Stock Report</a></li>
                    <li><a class="box_a" href="<?php echo base_url('department-issue-report') ?>">Department Issue Report</a></li>
                <?php } ?>

            </ul>
        </li>
    <?php } ?>


</ul>
