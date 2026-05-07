<p class="f-left box">

    <!-- Switcher -->
    <span class="f-left" id="switcher">
        <a href="#" rel="1col" class="styleswitch ico-col1" title="Display one column"><img src="<?php echo base_url() ?>design/switcher-1col.gif" alt="1 Column" /></a>
        <a href="#" rel="2col" class="styleswitch ico-col2" title="Display two columns"><img src="<?php echo base_url() ?>design/switcher-2col.gif" alt="2 Columns" /></a>
    </span>

    Project: <strong>Mirzakhil General Hospital and Diagnostic Center Ltd</strong>

</p>

<p class="f-right">Patient: <strong><a href="">
            <?php
            $patient_test_entry_id = $this->session->userdata('patient_test_entry_id');
            $patient_test_entry = $this->db->select('*')->where('patient_test_entry_id', $patient_test_entry_id)->get('patient_test_entry')->row();
          
            echo $patient_test_entry->patient_name;
            ?>
        </a></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong><a href="<?php echo site_url('AuthController/FunctionLogout') ?>" id="logout">Log out</a></strong></p>