<ul class="box">
    <li>...<b>Supplier</b>.............</li><br /><br />
    <li><a  class="box_a" href="<?php echo site_url('SupplierController/add_supplier') ?>">Add Supplier</a></li>
    <li><a  class="box_a" href="<?php echo site_url('SupplierController/supplier_payment') ?>">Supplier Payment</a></li>
   
     <?php
      $user_type = $this->session->userdata('user_type');
    if ($user_type == 'admin'):
        ?>
        <li><a  class="box_a" href="<?php echo site_url('SupplierController/supplier_report') ?>">Supplier Payment Report</a></li>
        <?php
    endif;
    ?>
</ul>
