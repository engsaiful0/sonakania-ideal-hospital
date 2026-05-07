<ul class="main-left-sdiebar">
    <li class="dropdown">
        <a href="<?php echo base_url('pharmacy') ?>">
            <i class="fas fa-tachometer-alt"></i> Dashbord</a>
    </li>
    <li class="dropdown">
        <i class="fas fa-tag"></i>&nbsp;Medicine Sell <span class="icon">+</span>
        <ul class="submenu">
            <li><a class="box_a" href="<?php echo base_url('add-medicine-sale') ?>">Add</a></li>
            <li><a class="box_a" href="<?php echo base_url() . "index.php/MedicineSaleController/view_medicine_sale"; ?>">View</a></li>
            <li><a class="box_a" href="<?php echo base_url() . "index.php/MedicineSaleReturnController/view_medicine_sale_return"; ?>">View Sell Return </a></li>
        </ul>
    </li>
    <li class="dropdown ">
        <i class="fas fa-shopping-cart"></i>
        </i>&nbsp;Medicine Purchase <span class="icon">+</span>
        <ul class="submenu">
            <li><a class="box_a" href="<?php echo base_url('add-medicine-purchase') ?>">Add</a></li>
            <li><a class="box_a" href="<?php echo base_url() . "index.php/MedicinePurchaseController/view_medicine_purchase"; ?>">View</a></li>
            <li><a class="box_a" href="<?php echo base_url() . "index.php/MedicinePurchaseReturnController/view_medicine_purchase_return"; ?>">View Purchase Return </a></li>
        </ul>
    </li>
    <li class="dropdown ">
        <i class="fas fa-calendar-times"></i>
        &nbsp; Expired Medicine <span class="icon">+</span>
        <ul class="submenu">
            <li><a class="box_a" href="<?php echo base_url('add-expired-medicine') ?>">Add</a></li>
            <li><a class="box_a" href="<?php echo base_url() . "index.php/ExpiredMedicineController/view_expired_medicine"; ?>">View</a></li>
        </ul>
    </li>
    <li class="dropdown ">
        <i class="fas fa-capsules"></i>
        &nbsp; Drug <span class="icon">+</span>
        <ul class="submenu">
            <li><a class="box_a" href="<?php echo base_url('add-drug') ?>">Add</a></li>
            <li><a class="box_a" href="<?php echo base_url() . "index.php/DrugController/view_drug"; ?>">View Drug</a></li>

        </ul>
    </li>
    <li class="dropdown ">
        <i class="fas fa-file-alt"></i>&nbsp;Report <span class="icon">+</span>
        <ul class="submenu">
            <li><a class="box_a" href="<?php echo base_url() . "index.php/DrugController/drug_stock_report"; ?>">Drug Stock</a></li>
            <li><a class="box_a" href="<?php echo base_url('my-sale-report') ?>">My Sale Report</a></li>
        </ul>
    </li>

    <!-- Add other list items as needed -->
</ul>