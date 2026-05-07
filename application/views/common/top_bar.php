<p class="f-left box">

    <!-- Switcher -->
    <span class="f-left" id="switcher">
        <a href="#" rel="1col" class="styleswitch ico-col1" title="Display one column"><img src="<?php echo base_url() ?>design/switcher-1col.gif" alt="1 Column" /></a>
        <a href="#" rel="2col" class="styleswitch ico-col2" title="Display two columns"><img src="<?php echo base_url() ?>design/switcher-2col.gif" alt="2 Columns" /></a>
    </span>

    Project: <strong>Hospital Management</strong>

</p>

<p class="f-right">User: <strong><a href="">
            <?php
            $user_type = $this->session->userdata('user_type');
            echo $this->session->userdata('user_name') . '(' . $user_type . ')';
            ?>
        </a></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong><a href="<?php echo site_url('LoginController/FunctionLogout') ?>" id="logout">Log out</a></strong></p>