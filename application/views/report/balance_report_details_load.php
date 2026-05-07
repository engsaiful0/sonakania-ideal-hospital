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
                margin-top: 40px
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

        <title>Balance Report</title>
    </head>
    <body>
        <?php
        $total_pofit = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                ->where('date<=', date('Y-m-d', strtotime($to_date)))
                ->get('patient_test_entry');
        $total_profit_rows = $total_pofit->num_rows();

        $page_limit = ceil($total_profit_rows / 38);
        //echo $page_limit;
        $from = -38;
        $start = 0;
        $grand_total_net_total = 0;
        $grand_total_paid = 0;
        $grand_total_due = 0;
        $grand_total_paid_test=0;
        for ($page_no = 1; $page_no <= $page_limit; $page_no++):
            ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                        <tr style="background-color: #0074B3;color: white  ">
                            <td colspan="7" style="text-align: center"><b>Test Sells Report</b> From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                        </tr>


                        <tr>
                            <td>Sl</td>
                            <td>Patient</td>
                            <td>Invoice No</td>
                            <td>Amount</td>
                            <td>Paid</td>
                            <td>Due</td>
                            <td>Date</td>
                        </tr>
                        <?php
//                        $query = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
//                                ->where('date<=', date('Y-m-d', strtotime($to_date)))
//                                ->get('whole_customer_sells')
//                                ->result();

                        $from = $from + 38;
                        $this->db->select('*');
                        $this->db->order_by('patient_test_entry_id', 'asc');
                        $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                                ->where('date<=', date('Y-m-d', strtotime($to_date)));
                        $this->db->from('patient_test_entry');
                        $this->db->limit(38, $from);
                        $query_profit = $this->db->get();
                        $query = $query_profit->result();
                        $sl = 1;

                        foreach ($query as $query_value) {
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
                                    <?php
                                    echo $query_value->net_total;
                                    $grand_total_net_total += $query_value->net_total;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $query_value->paid;
                                    $grand_total_paid_test += $query_value->paid;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $query_value->due;
                                    $grand_total_due += $query_value->due;
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
                            <td colspan="" style="text-align: right">Total</td>
                            <td><?php echo number_format($grand_total_net_total, 0) ?></td>
                            <td><?php echo number_format($grand_total_paid_test, 0) ?></td>
                            <td><?php echo number_format($grand_total_due, 0) ?></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </page>   
        <?php
    endfor;
    ?>
    <?php
    $total_pofit = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
            ->where('date<=', date('Y-m-d', strtotime($to_date)))
            ->get('bank_withdraw');
    $total_profit_rows = $total_pofit->num_rows();

    $page_limit = ceil($total_profit_rows / 45);
    //echo $page_limit;
    $from = -45;
    $start = 0;
    $grand_bank_withdraw = 0;
    for ($page_no = 1; $page_no <= $page_limit; $page_no++):
        ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                        <tr style="background-color: #0074B3;color: white  ">
                            <td colspan="7" style="text-align: center"><b>Bank Withdraw Report</b> From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                        </tr>


                        <tr>
                            <td>Sl</td>
                            <td>Bank Name</td>
                            <td>Account Number</td>
                            <td>Purpose</td>
                            <td>Amount</td>
                            <td>Date</td>
                        </tr>
                        <?php
                        $from = $from + 45;
                        $this->db->select('*');
                        $this->db->order_by('bank_id', 'asc');
                        $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                                ->where('date<=', date('Y-m-d', strtotime($to_date)));
                        $this->db->from('bank_withdraw');
                        $this->db->limit(45, $from);
                        $query_profit = $this->db->get();
                        $query = $query_profit->result();
                        $sl = 1;

                        foreach ($query as $query_value) {
                            $bank = $this->db->where('bank_id', $query_value->bank_id)->get('bank')->row();
                            ?>
                            <tr>
                                <td>
                                    <?php echo $sl++ ?>
                                </td>
                                <td>
                                    <?php echo $bank->bank_name ?>
                                </td>
                                <td>
                                    <?php echo $bank->account_number ?>
                                </td>
                                <td>
                                    <?php echo $query_value->purpose ?>
                                </td>
                                <td>
                                    <?php
                                    echo $query_value->amount;
                                    $grand_bank_withdraw += $query_value->amount;
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

                            <td colspan="3" style="text-align: right">Total</td>
                            <td><?php echo number_format($grand_bank_withdraw, 0) ?></td>                         
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </page>   
        <?php
    endfor;
    ?>
    <?php
    $total_pofit = $this->db->where('entry_date>=', date('Y-m-d', strtotime($from_date)))
            ->where('entry_date<=', date('Y-m-d', strtotime($to_date)))
            ->get('opd_patient');
    $total_profit_rows = $total_pofit->num_rows();

    $page_limit = ceil($total_profit_rows / 45);
    //echo $page_limit;
    $from = -45;
    $start = 0;

    $grand_opd_paid = 0;

    for ($page_no = 1; $page_no <= $page_limit; $page_no++):
        ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                        <tr style="background-color: #0074B3;color: white  ">
                            <td colspan="7" style="text-align: center"><b>OPD Patient Report</b> From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                        </tr>


                        <tr>
                            <td>Sl</td>
                            <td>Patient</td>
                            <td>Invoice No</td>
                            <td>Visiting Fee</td>
                            <td>Discount</td>
                            <td>Paid</td>
                            <td>Date</td>
                        </tr>
                        <?php
                        $from = $from + 45;
                        $this->db->select('*');
                        $this->db->order_by('opd_patient_id', 'asc');
                        $this->db->where('entry_date>=', date('Y-m-d', strtotime($from_date)))
                                ->where('entry_date<=', date('Y-m-d', strtotime($to_date)));
                        $this->db->from('opd_patient');
                        $this->db->limit(45, $from);
                        $query_profit = $this->db->get();
                        $query = $query_profit->result();
                        $sl = 1;

                        foreach ($query as $query_value) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $sl++ ?>
                                </td>
                                <td>
                                    <?php echo $query_value->opd_patient_name ?>
                                </td>
                                <td>
                                    <?php echo $query_value->opd_patient_unique_id ?>
                                </td>
                                <td>
                                    <?php
                                    echo $query_value->visiting_fee;
                                    $grand_total_net_total += $query_value->visiting_fee;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $query_value->discount;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $query_value->payable;
                                    $grand_opd_paid += $query_value->payable;
                                    ?>
                                </td>

                                <td>
                                    <?php echo date('d-m-Y', strtotime($query_value->entry_date)) ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td colspan="" style="text-align: right">Total</td>
                            <td><?php //echo number_format($grand_total_net_total, 0) ?></td>
                            <td><?php echo number_format($grand_opd_paid, 0) ?></td>

                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </page>   
        <?php
    endfor;
    ?>
    <?php
    $total_pofit = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
            ->where('date<=', date('Y-m-d', strtotime($to_date)))
            ->get('expense');
    $total_profit_rows = $total_pofit->num_rows();

    $page_limit = ceil($total_profit_rows / 45);
    //echo $page_limit;
    $from = -45;
    $start = 0;

    $grand_expense = 0;
    for ($page_no = 1; $page_no <= $page_limit; $page_no++):
        ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                        <tr style="background-color: #0074B3;color: white  ">
                            <td colspan="7" style="text-align: center"><b>Expense Report</b> From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                        </tr>


                        <tr>
                            <td>Sl</td>
                            <td>Voucher No</td>
                            <td>Purpose</td>
                            <td>Amount</td>
                            <td>Date</td>
                        </tr>
                        <?php
                        $from = $from + 45;
                        $this->db->select('*');
                        $this->db->order_by('expense_head_id', 'asc');
                        $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                                ->where('date<=', date('Y-m-d', strtotime($to_date)));
                        $this->db->from('expense');
                        $this->db->limit(45, $from);
                        $query_profit = $this->db->get();
                        $query = $query_profit->result();
                        $sl = 1;

                        foreach ($query as $query_value) {
                            $expense_head = $this->db->where('expense_head_id', $query_value->expense_head_id)->get('expense_head')->row();
                            ?>
                            <tr>
                                <td>
                                    <?php echo $sl++ ?>
                                </td>
                                <td>
                                    <?php echo $query_value->voucher_no ?>
                                </td>
                                <td>
                                    <?php echo $expense_head->expense_head_name ?>
                                </td>
                                <td>
                                    <?php
                                    echo $query_value->amount;
                                    $grand_expense += $query_value->amount;
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

                            <td colspan="3" style="text-align: right">Total</td>
                            <td><?php echo number_format($grand_expense, 0) ?></td>                         
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </page>   
        <?php
    endfor;
    ?>
    <?php
    $total_pofit = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
            ->where('date<=', date('Y-m-d', strtotime($to_date)))
            ->get('employee_salary');
    $total_profit_rows = $total_pofit->num_rows();

    $page_limit = ceil($total_profit_rows / 45);
    //echo $page_limit;
    $from = -45;
    $start = 0;
    $grand_total_net_total = 0;
    $grand_total_paid = 0;
    $grand_employee_salary = 0;
    for ($page_no = 1; $page_no <= $page_limit; $page_no++):
        ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                        <tr style="background-color: #0074B3;color: white  ">
                            <td colspan="7" style="text-align: center"><b>Employee Salary Report</b> From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                        </tr>


                        <tr>
                            <td>Sl</td>
                            <td>Employee Name</td>
                            <td>Purpose</td>
                            <td>Amount</td>
                            <td>Date</td>
                        </tr>
                        <?php
                        $from = $from + 45;
                        $this->db->select('*');
                        $this->db->order_by('employee_id', 'asc');
                        $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                                ->where('date<=', date('Y-m-d', strtotime($to_date)));
                        $this->db->from('employee_salary');
                        $this->db->limit(45, $from);
                        $query_profit = $this->db->get();
                        $query = $query_profit->result();
                        $sl = 1;

                        foreach ($query as $query_value) {
                            $employee = $this->db->where('employee_id', $query_value->employee_id)->get('employee')->row();
                            ?>
                            <tr>
                                <td>
                                    <?php echo $sl++ ?>
                                </td>
                                <td>
                                    <?php echo $employee->employee_name ?>
                                </td>
                                <td>
                                    <?php echo $query_value->purpose ?>
                                </td>
                                <td>
                                    <?php
                                    echo $query_value->amount;
                                    $grand_employee_salary += $query_value->amount;
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

                            <td colspan="3" style="text-align: right">Total</td>
                            <td><?php echo number_format($grand_employee_salary, 0) ?></td>                         
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </page>   
        <?php
    endfor;
    ?>
    <?php
    $total_pofit = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
            ->where('date<=', date('Y-m-d', strtotime($to_date)))
            ->get('bank_deposit');
    $total_profit_rows = $total_pofit->num_rows();

    $page_limit = ceil($total_profit_rows / 45);
    //echo $page_limit;
    $from = -45;
    $start = 0;

    $grand_bank_deposit = 0;
    for ($page_no = 1; $page_no <= $page_limit; $page_no++):
        ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                        <tr style="background-color: #0074B3;color: white  ">
                            <td colspan="7" style="text-align: center"><b>Bank Deposit Report</b> From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                        </tr>


                        <tr>
                            <td>Sl</td>
                            <td>Bank Name</td>
                            <td>Account Number</td>
                            <td>Purpose</td>
                            <td>Amount</td>
                            <td>Date</td>
                        </tr>
                        <?php
                        $from = $from + 45;
                        $this->db->select('*');
                        $this->db->order_by('bank_id', 'asc');
                        $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                                ->where('date<=', date('Y-m-d', strtotime($to_date)));
                        $this->db->from('bank_deposit');
                        $this->db->limit(45, $from);
                        $query_profit = $this->db->get();
                        $query = $query_profit->result();
                        $sl = 1;

                        foreach ($query as $query_value) {
                            $bank = $this->db->where('bank_id', $query_value->bank_id)->get('bank')->row();
                            ?>
                            <tr>
                                <td>
                                    <?php echo $sl++ ?>
                                </td>
                                <td>
                                    <?php echo $bank->bank_name ?>
                                </td>
                                <td>
                                    <?php echo $bank->account_number ?>
                                </td>
                                <td>
                                    <?php echo $query_value->purpose ?>
                                </td>
                                <td>
                                    <?php
                                    echo $query_value->amount;
                                    $grand_bank_deposit += $query_value->amount;
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

                            <td colspan="3" style="text-align: right">Total</td>
                            <td><?php echo number_format($grand_bank_deposit, 0) ?></td>                         
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </page>   
        <?php
    endfor;
    ?>
    <?php
    $total_pofit = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
            ->where('date<=', date('Y-m-d', strtotime($to_date)))
            ->get('doctor_commission_payment');
    $total_profit_rows = $total_pofit->num_rows();

    $page_limit = ceil($total_profit_rows / 45);
    //echo $page_limit;
    $from = -45;
    $start = 0;

    $grand_doctor_payment = 0;
    for ($page_no = 1; $page_no <= $page_limit; $page_no++):
        ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                        <tr style="background-color: #0074B3;color: white  ">
                            <td colspan="7" style="text-align: center"><b>Doctor Payment Report</b> From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                        </tr>


                        <tr>
                            <td>Sl</td>
                            <td>Doctor Name</td>

                            <td>Amount</td>
                            <td>Date</td>
                        </tr>
                        <?php
                        $from = $from + 45;
                        $this->db->select('*');
                        $this->db->order_by('doctor_id', 'asc');
                        $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                                ->where('date<=', date('Y-m-d', strtotime($to_date)));
                        $this->db->from('doctor_commission_payment');
                        $this->db->limit(45, $from);
                        $query_profit = $this->db->get();
                        $query = $query_profit->result();
                        $sl = 1;

                        foreach ($query as $query_value) {
                            $doctor = $this->db->where('doctor_id', $query_value->doctor_id)->get('doctor')->row();
                            ?>
                            <tr>
                                <td>
                                    <?php echo $sl++ ?>
                                </td>
                                <td>
                                    <?php echo $doctor->doctor_name ?>
                                </td>

                                <td>
                                    <?php
                                    echo $query_value->paid_amount;
                                    $grand_doctor_payment += $query_value->paid_amount;
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

                            <td colspan="3" style="text-align: right">Total</td>
                            <td><?php echo number_format($grand_doctor_payment, 0) ?></td>                         
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </page>   
        <?php
    endfor;
    ?>
    <?php
    $total_pofit = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
            ->where('date<=', date('Y-m-d', strtotime($to_date)))
            ->get('owner_payment');
    $total_profit_rows = $total_pofit->num_rows();

    $page_limit = ceil($total_profit_rows / 45);
    //echo $page_limit;
    $from = -45;
    $start = 0;

    $grand_owner_payment = 0;
    for ($page_no = 1; $page_no <= $page_limit; $page_no++):
        ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                        <tr style="background-color: #0074B3;color: white  ">
                            <td colspan="7" style="text-align: center"><b>Owner Payment Report</b> From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                        </tr>


                        <tr>
                            <td>Sl</td>
                            <td>Owner Name</td>

                            <td>Amount</td>
                            <td>Date</td>
                        </tr>
                        <?php
                        $from = $from + 45;
                        $this->db->select('*');
                        $this->db->order_by('owner_id', 'asc');
                        $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                                ->where('date<=', date('Y-m-d', strtotime($to_date)));
                        $this->db->from('owner_payment');
                        $this->db->limit(45, $from);
                        $query_profit = $this->db->get();
                        $query = $query_profit->result();
                        $sl = 1;

                        foreach ($query as $query_value) {
                            $owner = $this->db->where('owner_id', $query_value->owner_id)->get('owner')->row();
                            ?>
                            <tr>
                                <td>
                                    <?php echo $sl++ ?>
                                </td>
                                <td>
                                    <?php echo $owner->owner_name ?>
                                </td>

                                <td>
                                    <?php
                                    echo $query_value->amount;
                                    $grand_owner_payment += $query_value->amount;
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

                            <td colspan="3" style="text-align: right">Total</td>
                            <td><?php echo number_format($grand_owner_payment, 0) ?></td>                         
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </page>   
        <?php
    endfor;
    ?>
    <page size="A4">
        <div class="first">
            <div class="second">
                <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                    <tr style="background-color: #0074B3;color: white  ">
                        <td colspan="4" style="text-align: center"><b>Total Income Expense</b> From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                    </tr>
                    <tr>
                        <td colspan="2">Income</td>

                        <td  colspan="2">Expense</td>

                    </tr>
                    <tr>
                        <td>OPD Patient</td>
                        <td><?php echo number_format($grand_opd_paid, 3) ?></td>
                        <td>Expense</td>
                        <td><?php echo number_format($grand_expense, 3) ?></td>
                    </tr>
                    <tr>
                        <td>Test Sell</td>
                        <td><?php echo number_format($grand_total_paid_test, 3) ?></td>
                        <td>Employee Salary</td>
                        <td><?php echo number_format($grand_employee_salary, 3) ?></td>
                    </tr>
                    <tr>
                        <td>Bank Withdraw</td>
                        <td><?php echo number_format($grand_bank_withdraw, 3) ?></td>
                        <td>Bank Deposit</td>
                        <td><?php echo number_format($grand_bank_deposit, 3) ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Doctor Payment</td>
                        <td><?php echo number_format($grand_doctor_payment, 3) ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Owner Payment</td>
                        <td><?php echo number_format($grand_owner_payment, 3) ?></td>
                    </tr>
                    <tr>
                        <td>Total Income</td>
                        <td><?php echo number_format($grand_opd_paid+$grand_total_paid_test+$grand_bank_withdraw, 3) ?></td>
                        <td>Total Expense</td>
                        <td><?php echo number_format($grand_expense+$grand_employee_salary+$grand_bank_deposit+$grand_owner_payment+$grand_doctor_payment, 3) ?></td>
                    </tr>
                     <tr>
                        <td>Total Balance</td>
                       
                        <td colspan="3"><?php echo number_format(($grand_opd_paid+$grand_total_paid_test+$grand_bank_withdraw)-($grand_expense+$grand_employee_salary+$grand_bank_deposit+$grand_owner_payment+$grand_doctor_payment), 3) ?></td>
                    </tr>

                </table>
            </div>
        </div>
    </page>
</body>
</html>