<ul class="box">
    <li>...<b>Home</b>.............</li><br /><br />
     <?php
    $user_type = $this->session->userdata('user_type');
    if ($user_type == 'cash_user' || $user_type == 'admin'):
        ?>
        <li><a class="box_a1" href="<?php echo site_url('HomeController/add_bank') ?>">Bank</a></li>

        <li><a class="box_a1" href="<?php echo site_url('HomeController/bank_deposit') ?>">Bank Deposit</a></li>
        <li><a class="box_a1" href="<?php echo site_url('HomeController/bank_withdraw') ?>">Bank Withdraw</a></li>

        <?php
    endif;
    ?>

 <?php
    $user_type = $this->session->userdata('user_type');

    if ($user_type == 'admin'):
        ?>

        <li><a class="box_a1" href="<?php echo site_url('HomeController/user') ?>">User</a></li>
          <li><a class="box_a1" href="<?php echo site_url('HomeController/company_profile') ?>">Company Profile</a></li>
        <?php
    endif;
    ?>

</ul>
