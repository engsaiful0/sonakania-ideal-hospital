<ul class="main-left-sdiebar">
    <?php $permissions = $this->session->userdata('permissions'); ?>
    <?php if (in_array('canteen_dashboard', $permissions)) { ?>
        <li class="dropdown">
            <a href="<?php echo base_url('canteen-dashboard') ?>">
                <i class="fas fa-tachometer-alt"></i> Canteen Dashboard</a>
        </li>
    <?php } ?>
    <?php if (in_array('canteen_sell_add', $permissions) || in_array('canteen_sell_view', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-dollar-sign"></i> Sell <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('canteen_sell_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-ready-item-sell') ?>">Add Sell</a></li>
                <?php } ?>
                <?php if (in_array('canteen_sell_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('view-ready-item-sell') ?>">View Sell</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('canteen_purchase_add', $permissions) || in_array('canteen_purchase_view', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-shopping-cart"></i> Purchase <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('canteen_purchase_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-purchase-canteen-goods') ?>">Add Purchase</a></li>
                <?php } ?>
                <?php if (in_array('canteen_purchase_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('view-purchase-canteen-goods') ?>">View Purchase</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('canteen_goods_usage_add', $permissions) || in_array('canteen_goods_usage_view', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-shopping-cart"></i> <i class="fas fa-check" style="color: green;"></i> Goods Usage <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('canteen_goods_usage_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-canteen-goods-usage') ?>">Add Goods Usage</a></li>
                <?php } ?>
                <?php if (in_array('canteen_goods_usage_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('view-canteen-goods-usage') ?>">View Goods Usage</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('canteen_inventory_ready_item_add', $permissions) || in_array('canteen_inventory_ready_item_view', $permissions)) { ?>
        <li class="dropdown">
        <i class="fas fa-boxes"></i> Inventory Ready Item <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('canteen_inventory_ready_item_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-inventory') ?>">Add Inventory</a></li>
                <?php } ?>
                <?php if (in_array('canteen_inventory_ready_item_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('view-inventory') ?>">View Inventory</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>

    <?php if (in_array('canteen_ready_item_stock_list', $permissions) || in_array('canteen_goods_list', $permissions)) { ?>
        <li class="dropdown">
        <i class="fas fa-warehouse"></i>Stock Manager <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('canteen_ready_item_stock_list', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('ready-item-stock-list') ?>">Ready Item Stock List</a></li>
                <?php } ?>
                <?php if (in_array('canteen_goods_list', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('goods-stock-list') ?>">Goods Stock List</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
  
</ul>