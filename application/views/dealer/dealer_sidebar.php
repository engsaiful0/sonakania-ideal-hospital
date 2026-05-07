<ul class="box">
    <li>...<b>Dealer Panel</b>.............</li><br /><br />
    <li><a  class="box_a" href="<?php echo site_url('DealerController/add_dealer') ?>">Dealer</a></li>
    <li><a  class="box_a" href="<?php echo site_url('DealerController/add_check') ?>">Dealer Check</a></li>
    
   <?php
    $user_type = $this->session->userdata('user_type');
    if ($user_type == 'admin'):
        ?>
        <li><a  class="box_a" href="<?php echo site_url('DealerController/dealer_report') ?>">Dealer Report</a></li>
        <?php
    endif;
    ?>
</ul>
