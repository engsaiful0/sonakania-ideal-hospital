<p id="logo">
    <?php
    $compnay=$this->db->where('company_id','1')->get('company')->row();
    ?>
    <img style="width: 200px;height: 120px;" src="<?php echo base_url()?>assets/images/<?php echo $compnay->logo?>" alt="Our logo" border="0" title="Visit Site" />
</p>