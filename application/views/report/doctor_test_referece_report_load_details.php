<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <style>
            body
            {
                width:100%;
                background: rgb(204,204,204); 
                overflow-x: hidden;
             

            }
            page[size="A4"] {
                background: white;
                width: 21cm;
                height: 29cm;
                display: block;
                margin: 0 auto;
                margin-bottom: 0.5cm;
                box-shadow: 0 0 0.1cm rgba(0,0,0,0.5);
                -o-box-shadow: 0 0 0.1cm rgba(0,0,0,0.5);
                -webkit-box-shadow: 0 0 0.1cm rgba(0,0,0,0.5);
                -moz-box-shadow: 0 0 0.1cm rgba(0,0,0,0.5);

            }

            @media print {
                body, page[size="A4"] {
                    margin: 0;
                    box-shadow: 0;

                }
            }

            .first
            {
                width:100%;
                height:29cm;
                margin:auto; padding:5px 0px 0px 0px;
            }
            .second
            {
                width:728px;
                height:600px;
                margin:auto; 
            }
            .third
            {
                width:607px;
                height:820px;
                margin:auto;
            }
            h2{font-size:24px;}
            #footer
            {
                width:400px;  
                margin:auto;
                background-color:#FFF;
            }
            #footer a
            {
                text-decoration:none;
                text-align:center;
              
            }

            @page {
                size: A4;
                margin: 0;
            }
            @media print {
                .first {

                    margin: 0;
                    border: initial;
                    border-radius: initial;
                    width: initial;
                    min-height: initial;
                    box-shadow: initial;
                    background: initial;
                    page-break-after: always;
                    -webkit-print-color-adjust: exact;
               
                }
            }
            @media print{
                #print{
                    display:none;
                }
                .print{
                    display:none;
                }
            }

            .upon{
                width:70%;
                height:auto;
                margin:auto;

            }
            #site_header_logo{
                position: relative;
                width:15%;
                height:100px;
                float:left;
                margin-top:10px;
                padding-left:80px;
            }
            a{
                text-decoration:none;
                color:inherit;
            }
            a.tooltip {outline:none;}
            a.tooltip strong {line-height:30px;}
            a.tooltip:hover {text-decoration:none;} 
            a.tooltip span {
                z-index:10;display:none; padding:14px 20px;
                margin-top:30px; margin-left:2px;
                width:300px; line-height:16px;
            }
            a.tooltip:hover span{
                display:inline; position:absolute; color:#111;
                border:1px solid #DCA; background:#fffAF0;
               
            }
            .callout {z-index:20;position:absolute;top:30px;border:0;left:-12px;}

            /*CSS3 extras*/
            a.tooltip span
            {
                border-radius:4px;
                box-shadow: 5px 5px 8px #CCC;
            }
            .boxright{width:35%; height:100px; float:left;  }
            .fbox1{width:320px; float:left; margin-left:39px; border-collapse: collapse; text-align:left;}
            .fbox1 td{height:25px;  line-height:16px}

            @media print {
                .offs{display:none;}
            }
            tr:hover{background-color:#91b8f7}
            @font-face {
                font-family: 'NikoshBAN';
                src: url('../assets/fonts/NikoshBAN.ttf') format('truetype');

            }

        </style>
        <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() ?>css/loader_extras.css" />
        <title>Doctor Test Reference Report</title>
    </head>
    <body>
        <?php
        $total_test_reference = '';
        if ($doctor_id != '') {
            $total_test_reference = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                    ->where('doctor_id', $doctor_id)
                    ->where('doctor_id!=', '')
                    ->where('date<=', date('Y-m-d', strtotime($to_date)))->where('is_deleted', '0')
                    ->get('patient_test_entry');
        } else {
            $total_test_reference = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                    ->where('doctor_id!=', '')
                    ->where('date<=', date('Y-m-d', strtotime($to_date)))->where('is_deleted', '0')
                    ->get('patient_test_entry');
        }

        $total_test_reference_rows = $total_test_reference->num_rows();

        $page_limit = ceil($total_test_reference_rows / 45);
        //echo $page_limit;
        $from = -45;
        $start = 0;
        $sl = 1;
        $grand_quantity = 0;
        $grand_commissioin = 0;
        for ($page_no = 1; $page_no <= $page_limit; $page_no++):
            ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" border="1" style="width: 98%;border-collapse:collapse;margin:0 auto;color:black;margin-top: 30px;">
                        <tr style="background-color:#337AB7;color: white  ">
                            <td colspan="9" style="text-align: center">Doctor Test Reference Report</td>
                        </tr>
                        <tr style="background-color: #337AB7;color: white  ">
                            <td colspan="9" style="text-align: center">From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                        </tr>

                        <tr>
                            <td>Sl</td>
                            <td>Patient Name</td>
                            <td>Invoice No</td>   
                            <td>Doctor</td>

                            <td>Sub Total</td>  
                            <td>Discount</td>  
                            <td>Net Total</td>  
                            <td>Date</td>
                            <td><b>Commission</b></td>
                        </tr>
                        <?php

                        $query = '';
                        if ($doctor_id != '') {
                            $from = $from + 45;
                            $this->db->select('*');
                            $this->db->order_by('doctor_id', 'asc');
                            $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                                    ->where('doctor_id', $doctor_id)
                                    ->where('doctor_id!=', '')
                                    ->where('date<=', date('Y-m-d', strtotime($to_date)))->where('is_deleted', '0');
                            $this->db->from('patient_test_entry');
                            $this->db->limit(45, $from);
                            $query_profit = $this->db->get();
                            $query = $query_profit->result();
                        } else {
                            $from = $from + 45;
                            $this->db->select('*');
                            $this->db->order_by('doctor_id', 'asc');
                            $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                                    ->where('doctor_id!=', '')
                                    ->where('date<=', date('Y-m-d', strtotime($to_date)))->where('is_deleted', '0');
                            $this->db->from('patient_test_entry');
                            $this->db->limit(45, $from);
                            $query_profit = $this->db->get();
                            $query = $query_profit->result();
                        }
                        $grand_net_total = 0;
                        $grand_sub_total = 0;
                        foreach ($query as $query_value) {
//                            $patient_test_entry = $this->db->where('patient_test_entry_id', $query_value->patient_test_entry_id)
//                                            ->get('patient_test_entry')->row();
                            //  print_r($patient_test_entry);
                            //  die;
                            $doctor = $this->db->where('doctor_id', $query_value->doctor_id)
                                            ->get('doctor')->row();
                            ?>
                            <tr>
                                <td>
                                    <?php echo $sl++ ?>
                                </td>
                                <td>
                                    <?php echo $query_value->patient_name ?>
                                </td>
                                <td>
                                    <?php echo $query_value->invoice_no ?>
                                </td>
                                <td>
                                    <?php echo $doctor->doctor_name . '-' . $doctor->doctor_unique_id . '(' . $doctor->lab_percentage.'%' . ')' ?>
                                </td>

                                <td>
                                    <?php
                                    echo $query_value->sub_total;
                                    $grand_sub_total += $query_value->sub_total;
                                    ?>
                                </td>
                                <td>
                                    <?php echo $query_value->discount ?>
                                </td>
                                <td>
                                    <?php
                                    echo $query_value->net_total;
                                    $grand_net_total += $query_value->net_total;
                                    ?>
                                </td>
                                <td>
                                    <?php echo date('d-m-Y', strtotime($query_value->date)) ?>
                                </td>
                                <td>
                                    <b><?php
                                        $commission = explode('%', $doctor->lab_percentage);
                                        echo $query_value->net_total * ($commission[0] / 100);
                                        $grand_commissioin += $query_value->net_total * ($commission[0] / 100);
                                        ?></b>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                            <td colspan="" style="text-align: right">Total</td>
                            <td><?php echo $grand_sub_total ?></td>
                            <td></td>
                            <td><?php echo $grand_net_total ?></td>
                            <td></td>
                            <td>
                                <b><?php
                                    echo $grand_commissioin;
                                    ?></b></td>
                        </tr>
                    </table>
                </div>
            </div>
        </page>
        <!--<div style="height: 100px; "></div>-->

        <?php
    endfor;
    ?>
    <div id="preloader">
        <div class="loader" id="loader-1"></div>
    </div>
    <script src="<?php echo base_url(); ?>js/loader_jquery-min.js"></script>
    <script src="<?php echo base_url(); ?>js/loader_main.js"></script>

</body>
</html>