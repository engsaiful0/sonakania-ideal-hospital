<ul class="main-left-sdiebar">
    <?php $permissions = $this->session->userdata('permissions'); ?>
    <?php if (in_array('pharmacy_dashboard', $permissions)) { ?>
        <li class="dropdown">
            <a href="<?php echo base_url('pharmacy') ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard</a>
        </li>
    <?php } ?>
    <?php if (in_array('pharmacy_add_medicine_sell', $permissions) || in_array('pharmacy_view_medicine_sell', $permissions) || in_array('pharmacy_return_medicine_sell', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-tag"></i>&nbsp;Medicine Sale <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('pharmacy_add_medicine_sell', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-medicine-sale') ?>">Add</a></li>
                <?php } ?>
                <?php if (in_array('pharmacy_view_medicine_sell', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/MedicineSaleController/view_medicine_sale"; ?>">View</a></li>
                <?php } ?>
                
                <?php if (in_array('pharmacy_return_medicine_sell', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/MedicineSaleReturnController/view_medicine_sale_return"; ?>">View Sale Return</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('pharmacy_add_medicine_sale_return_without_invoice', $permissions) || in_array('pharmacy_view_medicine_sale_return_without_invoice', $permissions) || in_array('pharmacy_edit_medicine_sale_return_without_invoice', $permissions) || in_array('pharmacy_delete_medicine_sale_return_without_invoice', $permissions) || in_array('pharmacy_print_medicine_sale_return_without_invoice', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-undo"></i>&nbsp;Medicine Sale Return <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('pharmacy_add_medicine_sale_return_without_invoice', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('medicine-sale-return/create') ?>">Add Return</a></li>
                <?php } ?>
                <?php if (in_array('pharmacy_view_medicine_sale_return_without_invoice', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('medicine-sale-return-without-invoice') ?>">View Returns</a></li>
                <?php } ?>
                <?php if (in_array('pharmacy_print_medicine_sale_return_without_invoice', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('medicine-sale-return-without-invoice') ?>">Print Returns</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>


    <?php if (in_array('pharmacy_add_medicine_purchase', $permissions) || in_array('pharmacy_view_medicine_purchase', $permissions) || in_array('pharmacy_return_medicine_purchase', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-shopping-cart"></i>
            </i>Medicine Purchase <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('pharmacy_add_medicine_purchase', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-medicine-purchase') ?>">Add</a></li>
                <?php } ?>
                <?php if (in_array('pharmacy_view_medicine_purchase', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/MedicinePurchaseController/view_medicine_purchase"; ?>">View</a></li>
                <?php } ?>
                <?php if (in_array('pharmacy_return_medicine_purchase', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/MedicinePurchaseReturnController/view_medicine_purchase_return"; ?>">View Purchase Return</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('pharmacny_expired_medicine_add', $permissions) || in_array('pharmacny_expired_medicine_view', $permissions)) { ?>
        <li class="dropdown">
            <i class="fas fa-calendar-times"></i>
            Expired Medicine <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('pharmacny_expired_medicine_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-expired-medicine') ?>">Add</a></li>
                <?php } ?>
                <?php if (in_array('pharmacny_expired_medicine_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/ExpiredMedicineController/view_expired_medicine"; ?>">View</a></li>
                <?php } ?>

            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('pharmacy_drug_add', $permissions) || in_array('pharmacy_drug_view', $permissions)) { ?>
        <li class="dropdown">
        <i class="fas fa-capsules"></i>
        Medicine <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('pharmacy_drug_add', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('add-drug') ?>">Add Medicine</a></li>
                <?php } ?>
                <?php if (in_array('pharmacy_drug_view', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/DrugController/view_drug"; ?>">View Medicine</a></li>
                <?php } ?>

            </ul>
        </li>
    <?php } ?>
    <?php if (in_array('pharmacy_stock_report', $permissions) || in_array('pharmacy_my_sale_report', $permissions)) { ?>
        <li class="dropdown">
        <i class="fas fa-file-alt"></i>&nbsp;Report <span class="icon">+</span>
            <ul class="submenu">
                <?php if (in_array('pharmacy_stock_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url() . "index.php/DrugController/drug_stock_report"; ?>">Medicine Stock</a></li>
                <?php } ?>
                <?php if (in_array('pharmacy_my_sale_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('my-sale-report') ?>">My Sale Report</a></li>
                <?php } ?>
                <?php if (in_array('pharmacy_all_users_medicine_sale_report', $permissions)) { ?>
                    <li><a class="box_a" href="<?php echo base_url('all-users-medicine-sale-report') ?>">All Users Sale Report</a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
   
    

    <!-- Add other list items as needed -->
</ul>