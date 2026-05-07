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
                font-size: 12px;

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
                font-size:10px;
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
                    font-size: 12px!important;
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
                font-size:10px;
            }
            .callout {z-index:20;position:absolute;top:30px;border:0;left:-12px;}

            /*CSS3 extras*/
            a.tooltip span
            {
                border-radius:4px;
                box-shadow: 5px 5px 8px #CCC;
            }
            .boxright{width:35%; height:100px; float:left;  }
            .fbox1{width:320px; float:left; margin-left:39px; border-collapse: collapse; text-align:left; font-size:10px;}
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

        <title>Bank Withdraw Report</title>
    </head>
    <body>
        <?php
        error_reporting(0);
        $bank_deposit = '';
        if ($bank_id != '') {
            $bank_deposit = $this->db
                    ->where('date>=', date('Y-m-d', strtotime($from_date)))
                    ->where('bank_id', $bank_id)
                    ->where('date<=', date('Y-m-d', strtotime($to_date)))
                    ->get('bank_withdraw');
        } else {
            $bank_deposit = $this->db
                    ->where('date>=', date('Y-m-d', strtotime($from_date)))
                    ->where('date<=', date('Y-m-d', strtotime($to_date)))
                    ->get('bank_withdraw');
        }

        $bank_deposit_rows = $bank_deposit->num_rows();

        $page_limit = ceil($bank_deposit_rows / 45);
        //echo $page_limit;
        $from = -45;
        $start = 0;
        $sl = 1;
        $grand_total = 0;
        for ($page_no = 1; $page_no <= $page_limit; $page_no++):
            ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" style="width: 90%;border-collapse:collapse;margin:0 auto;color:black;margin-top: 40px;">
                        <tr>
                            <td colspan="6" style="text-align: center">Bank Withdraw Report From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                        </tr>
                        <tr>
                            <td>Sl</td>
                            <td>Purpose</td>
                            <td>Bank Name</td>
                            <td>Account Number</td>
                            <td>Amount</td>
                            <td>Date</td>
                        </tr>
                        <?php
                        $query = '';
                        if ($bank_id != '') {
                            $from = $from + 45;
                            $this->db->select('*');
                            $this->db->order_by('bank_id', 'asc');
                            $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                                    ->where('bank_id', $bank_id)
                                    ->where('date<=', date('Y-m-d', strtotime($to_date)));
                            $this->db->from('bank_withdraw');
                            $this->db->limit(45, $from);
                            $bank_deposit = $this->db->get();
                            $query = $bank_deposit->result();
                        } else {
                            $from = $from + 45;
                            $this->db->select('*');
                            $this->db->order_by('bank_id', 'asc');
                            $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                                    ->where('date<=', date('Y-m-d', strtotime($to_date)));
                            $this->db->from('bank_withdraw');
                            $this->db->limit(45, $from);
                            $bank_deposit = $this->db->get();
                            $query = $bank_deposit->result();
                        }
                        $sl = 1;

                        foreach ($query as $query_value) {
                            $sql_bank = $this->db->where('bank_id', $query_value->bank_id)
                                    ->get('bank')
                                    ->row();
                            ?>
                            <tr>
                                <td>
                                    <?php echo $sl++ ?>
                                </td>
                                <td>
                                    <?php echo $query_value->purpose ?>
                                </td>
                                <td>
                                    <?php echo $sql_bank->bank_name ?>
                                </td>
                                <td>
                                    <?php echo $sql_bank->account_number ?>
                                </td>
                                <td>
                                    <?php
                                    echo $query_value->amount;
                                    $grand_total += $query_value->amount;
                                    ?>
                                </td>
                                <td>
                                    <?php echo date('d-m-Y', strtotime($query_value->date)) ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td colspan="" style="text-align: right ">Total</td>
                            <td><?php echo number_format($grand_total, 0) ?></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </page>
        <?php
    endfor;
    ?>

</body>
</html>