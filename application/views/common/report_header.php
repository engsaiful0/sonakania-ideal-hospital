<?php
$compnay = $this->db->where('company_id', '1')->get('company')->row();
?>
<div style="width: 15%;float: left;margin-top:20px">
    <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
</div>
<div style="width: 70%;float: left;text-align: center">
    <p style="text-align: center"><span style="text-align: center;font-size: 20px;text-align: center "> <?php echo $compnay->company_name ?></span><br><?php echo $compnay->address ?></span><br>
        <span style="text-align: center">
            Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
        </span>
    </p>
</div>