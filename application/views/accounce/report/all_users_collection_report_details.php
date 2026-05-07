<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            width: 100%;
            background: rgb(204, 204, 204);
            overflow-x: hidden;
            font-size: 12px;

        }

        page[size="A4"] {
            background: white;
            width: 23cm;
            height: 35cm;
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            box-shadow: 0 0 0.1cm rgba(0, 0, 0, 0.5);
            -o-box-shadow: 0 0 0.1cm rgba(0, 0, 0, 0.5);
            -webkit-box-shadow: 0 0 0.1cm rgba(0, 0, 0, 0.5);
            -moz-box-shadow: 0 0 0.1cm rgba(0, 0, 0, 0.5);

        }

        @media print {

            body,
            page[size="A4"] {
                margin: 0;
                box-shadow: 0;

            }
        }

        .first {
            width: 100%;
            height: 29cm;
            margin: auto;
            padding: 5px 0px 0px 0px;
        }

        .second {
            width: 728px;
            height: 600px;
            margin: auto;
            margin-top: 40px
        }

        .third {
            width: 607px;
            height: 820px;
            margin: auto;
        }

        h2 {
            font-size: 24px;
        }

        #footer {
            width: 400px;
            margin: auto;
            background-color: #FFF;
        }

        #footer a {
            text-decoration: none;
            text-align: center;
            font-size: 10px;
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
                font-size: 12px !important;
            }
        }

        @media print {
            #print {
                display: none;
            }

            .print {
                display: none;
            }
        }

        .upon {
            width: 70%;
            height: auto;
            margin: auto;

        }

        #site_header_logo {
            position: relative;
            width: 15%;
            height: 100px;
            float: left;
            margin-top: 10px;
            padding-left: 80px;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        a.tooltip {
            outline: none;
        }

        a.tooltip strong {
            line-height: 30px;
        }

        a.tooltip:hover {
            text-decoration: none;
        }

        a.tooltip span {
            z-index: 10;
            display: none;
            padding: 14px 20px;
            margin-top: 30px;
            margin-left: 2px;
            width: 300px;
            line-height: 16px;
        }

        a.tooltip:hover span {
            display: inline;
            position: absolute;
            color: #111;
            border: 1px solid #DCA;
            background: #fffAF0;
            font-size: 10px;
        }

        .callout {
            z-index: 20;
            position: absolute;
            top: 30px;
            border: 0;
            left: -12px;
        }

        /*CSS3 extras*/
        a.tooltip span {
            border-radius: 4px;
            box-shadow: 5px 5px 8px #CCC;
        }

        .boxright {
            width: 35%;
            height: 100px;
            float: left;
        }

        .fbox1 {
            width: 320px;
            float: left;
            margin-left: 39px;
            border-collapse: collapse;
            text-align: left;
            font-size: 10px;
        }

        .fbox1 td {
            height: 25px;
            line-height: 16px
        }

        @media print {
            .offs {
                display: none;
            }
        }

        tr:hover {
            background-color: #91b8f7
        }

        @font-face {
            font-family: 'NikoshBAN';
            src: url('../assets/fonts/NikoshBAN.ttf') format('truetype');

        }
    </style>

    <title>All Users Collection Report</title>
</head>

<body>

    <page size="A4">
        <div class="first">
            <div class="second">
                <?php
                
                $this->load->view('common/report_header');
                
                ?>
                <table border="1" class="table table-bordered table-hover" style="width: 100%;margin: 0 auto;color:black;border-collapse:collapse;">
                    <tr style="background-color: #1A7DFF;color: white">
                        <td colspan="13" style="text-align: center"><b>All Users Collection Report</b> From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                    </tr>
                    <tr>

                        <td>User</td>
                        <td>IPD</td>
                        <td>IPD <br>Advance</td>
                        <td>OPD</td>
                        <td>Emergency</td>
                        <td>Test</td>
                        <td>Physio</td>
                        <td>Due<br> Collection</td>
                        <td>Total</td>
                        <td>Return</td>
                        <td>Pharmacy</td>
                        <td>Balance</td>
                    </tr>
                    <?php
                    if ($to_date != '') {
                        $to_date = date('Y-m-d', strtotime($to_date));
                    }
                    if ($from_date  != '') {
                        $from_date = date('Y-m-d', strtotime($from_date));
                    }


                    $serial = 1;
                    $grand_ipd_discharge_amount = 0;
                    $grand_ipd_advacne_amount = 0;
                    $grand_opd_booking_amount = 0;
                    $grand_emergency_amount = 0;
                    $grand_test_amount = 0;
                    $grand_phygiotherapy_amount = 0;
                    $grand_medicine_sales_amount = 0;
                    $grand_test_collection_amount = 0;

                    //Return Amount
                    $grand_opd_return_amount = 0;
                    $grand_emergency_return_amount = 0;
                    $grand_test_return_amount = 0;
                    $grand_phygiotherapy_return_amount = 0;
                    $grand_medicine_sales_return_amount = 0;

                    $grand_total_collection_amount = 0;
                    $grand_total_return_amount = 0;
                    $grand_total_balance_amount = 0;

                    $grand_due_collection_amount = 0;
                    $grand_test_due_collection_amount = 0;
                    $grand_physio_due_collection_amount = 0;
                    $grand_emergency_due_collection_amount = 0;


                    $users = '';
                    if ($user_id == 'all') {
                        $users = $this->db->select('*')->get('user')->result();
                    } else {
                        $users = $this->db->where('user_id', $user_id)->get('user')->result();
                    }


                    foreach ($users as $user_value) {
                        $total_return_amount_of_each_user = 0;
                        $total_amount_of_each_user = 0;
                        $total_due_collection_of_each_user = 0;

                        //IPD discharge collection
                        $discharge_date_condition = [
                            'discharge_date >=' => $from_date,
                            'discharge_date <=' => $to_date
                        ];
                        $this->db->select_sum('paid');
                        $this->db->from('discharge');
                        $this->db->where('user_id', $user_value->user_id);
                        $this->db->where($discharge_date_condition); // Filter by today's date
                        $query = $this->db->get();
                        $ipd_discharge_collection = $query->row()->paid ?? 0; // Return 0 if no income found
                        $grand_ipd_discharge_amount += $ipd_discharge_collection;
                        $total_amount_of_each_user += $ipd_discharge_collection;
                        $grand_total_collection_amount += $ipd_discharge_collection;

                        //IPD Advance collection
                        $date_condition = [
                            'date >=' => $from_date,
                            'date <=' => $to_date
                        ];
                        $this->db->select_sum('paid_amount');
                        $this->db->from('ipd_patient');
                        $this->db->where('user_id', $user_value->user_id);
                        $this->db->where($date_condition); // Filter by today's date
                        $query = $this->db->get();
                        $ipd_advance_collection = $query->row()->paid_amount ?? 0; // Return 0 if no income found
                        $grand_ipd_advacne_amount += $ipd_advance_collection;
                        $total_amount_of_each_user += $ipd_advance_collection;
                        $grand_total_collection_amount += $ipd_advance_collection;

                        //OPD  collection
                        $date_condition = [
                            'entry_date >=' => $from_date,
                            'entry_date <=' => $to_date
                        ];
                        $this->db->select_sum('payable');
                        $this->db->from('opd_patient');
                        $this->db->where('user_id', $user_value->user_id);
                        $this->db->where($date_condition); // Filter by today's date
                        $query = $this->db->get();
                        $opd_booking_collection = $query->row()->payable ?? 0; // Return 0 if no income found
                        $grand_opd_booking_amount += $opd_booking_collection;
                        $total_amount_of_each_user += $opd_booking_collection;
                        $grand_total_collection_amount += $opd_booking_collection;

                        //OPD Returnable  Amount
                        $date_condition = [
                            'entry_date >=' => $from_date,
                            'entry_date <=' => $to_date
                        ];
                        $this->db->select_sum('returnable_amount');
                        $this->db->from('opd_patient');
                        $this->db->where('user_id', $user_value->user_id);
                        $this->db->where($date_condition); // Filter by today's date
                        $query = $this->db->get();
                        $opd_returnable_amount = $query->row()->returnable_amount ?? 0; // Return 0 if no income found
                        $grand_opd_return_amount += $opd_returnable_amount;
                        $total_return_amount_of_each_user += $opd_returnable_amount;
                        $grand_total_return_amount += $opd_returnable_amount;

                        //Emergency Collection
                        $date_condition = [
                            'date >=' => $from_date,
                            'date <=' => $to_date
                        ];
                        $this->db->select_sum('paid');
                        $this->db->from('emergency');
                        $this->db->where('user_id', $user_value->user_id);
                        $this->db->where($date_condition); // Filter by date range
                        $query = $this->db->get();
                        $emergency_collection = $query->row()->paid ?? 0; // Return 0 if no record found
                        $grand_emergency_amount += $emergency_collection;
                        $total_amount_of_each_user += $emergency_collection;
                        $grand_total_collection_amount += $emergency_collection;


                        //Emergency Retrun Amount
                        $date_condition = [
                            'date >=' => $from_date,
                            'date <=' => $to_date
                        ];
                        $this->db->select_sum('returnable_amount');
                        $this->db->from('emergency');
                        $this->db->where('user_id', $user_value->user_id);
                        $this->db->where($date_condition); // Filter by date range
                        $query = $this->db->get();
                        $emergency_return_amount = $query->row()->returnable_amount ?? 0; // Return 0 if no record found
                        $grand_emergency_return_amount += $emergency_return_amount;
                        $total_return_amount_of_each_user += $emergency_return_amount;
                        $grand_total_return_amount += $emergency_return_amount;

                        //Test Collection
                        $date_condition = [
                            'date >=' => $from_date,
                            'date <=' => $to_date
                        ];
                        $this->db->select_sum('paid');
                        $this->db->from('patient_test_entry');
                        $this->db->where('user_id', $user_value->user_id);
                        $this->db->where($date_condition); // Filter by date range

                        $query = $this->db->get();
                        $test_collection = $query->row()->paid ?? 0; // Return 0 if no record found
                        $grand_test_amount += $test_collection;
                        $total_amount_of_each_user += $test_collection;
                        $grand_total_collection_amount += $test_collection;

                      
                        //Test Return Amount
                        $date_condition = [
                            'date >=' => $from_date,
                            'date <=' => $to_date
                        ];
                        $this->db->select_sum('returnable_amount');
                        $this->db->from('patient_test_entry');
                        $this->db->where('user_id', $user_value->user_id);
                        $this->db->where($date_condition); // Filter by date range

                        $query = $this->db->get();
                        $test_returnable_amount = $query->row()->returnable_amount ?? 0; // Return 0 if no record found
                        $grand_test_return_amount += $test_returnable_amount;
                        $total_return_amount_of_each_user += $test_returnable_amount;
                        $grand_total_return_amount += $test_returnable_amount;

                        //Physiotherapy Collection
                        $date_condition = [
                            'date >=' => $from_date,
                            'date <=' => $to_date
                        ];
                        $this->db->select_sum('paid');
                        $this->db->from('phygiotherapy');
                        $this->db->where('user_id', $user_value->user_id);
                        $this->db->where($date_condition); // Filter by date range

                        $query = $this->db->get();
                        $phygiotherapy_collection = $query->row()->paid ?? 0; // Return 0 if no record found
                        $grand_phygiotherapy_amount += $phygiotherapy_collection;
                        $total_amount_of_each_user += $phygiotherapy_collection;
                        $grand_total_collection_amount += $phygiotherapy_collection;

                        //Physiotherapy Return Amount
                        $date_condition = [
                            'date >=' => $from_date,
                            'date <=' => $to_date
                        ];
                        $this->db->select_sum('returnable_amount');
                        $this->db->from('phygiotherapy');
                        $this->db->where('user_id', $user_value->user_id);
                        $this->db->where($date_condition); // Filter by date range

                        $query = $this->db->get();
                        $phygiotherapy_return_amount = $query->row()->returnable_amount ?? 0; // Return 0 if no record found
                        $grand_phygiotherapy_return_amount += $phygiotherapy_return_amount;
                        $total_return_amount_of_each_user += $phygiotherapy_return_amount;
                        $grand_total_return_amount += $phygiotherapy_return_amount;

                        //Pharmacy Collection
                        $date_condition = [
                            'bill_date >=' => $from_date,
                            'bill_date <=' => $to_date
                        ];
                        $this->db->select_sum('paid');
                        $this->db->from('medicine_sales');
                        $this->db->where('user_id', $user_value->user_id);
                        $this->db->where($date_condition); // Filter by date range

                        $query = $this->db->get();
                        $medicine_sales_collection = $query->row()->paid ?? 0; // Return 0 if no record found
                        $grand_medicine_sales_amount += $medicine_sales_collection;
                        $total_amount_of_each_user += $medicine_sales_collection;
                        $grand_total_collection_amount += $medicine_sales_collection;

                        //Pharmacy Return Amount
                        $date_condition = [
                            'date >=' => $from_date,
                            'date <=' => $to_date
                        ];
                        $this->db->select_sum('paid');
                        $this->db->from('medicine_sale_return');
                        $this->db->where('user_id', $user_value->user_id);
                        $this->db->where($date_condition); // Filter by date range

                        $query = $this->db->get();
                        $medicine_sales_return_amount = $query->row()->paid ?? 0; // Return 0 if no record found
                        $grand_medicine_sales_return_amount += $medicine_sales_return_amount;
                        $total_return_amount_of_each_user += $medicine_sales_return_amount;
                        $grand_total_return_amount += $medicine_sales_return_amount;


                        //Test Due Collection
                        $date_condition = [
                            'date >=' => $from_date,
                            'date <=' => $to_date
                        ];
                        $this->db->select_sum('paid');
                        $this->db->from('test_collection');
                        $this->db->where('user_id', $user_value->user_id);
                        $this->db->where('payment_type', 'from_due_collection');
                        $this->db->where($date_condition); // Filter by date range
                        $query = $this->db->get();
                        $test_due_collection = $query->row()->paid ?? 0; // Return 0 if no record found
                        $grand_test_collection_amount += $test_due_collection;
                        $grand_total_collection_amount += $test_due_collection;
                        $grand_test_due_collection_amount += $test_due_collection;
                        $grand_due_collection_amount += $test_due_collection;
                        $total_due_collection_of_each_user += $test_due_collection;

                        //Emergency Due Collection
                        $date_emergency_due_payment_condition = [
                            'due_payment_date >=' => $from_date,
                            'due_payment_date <=' => $to_date
                        ];
                        $this->db->select_sum('due_payment');
                        $this->db->from('emergency');
                        $this->db->where('due_payment_user_id', $user_value->user_id);
                        $this->db->where($date_emergency_due_payment_condition); // Filter by date range
                        $query = $this->db->get();
                        $emergency_due_collection = $query->row()->due_payment ?? 0; // Return 0 if no record found
                        $grand_emergency_due_collection_amount += $emergency_due_collection;
                        $grand_total_collection_amount += $emergency_due_collection;
                        $grand_due_collection_amount += $emergency_due_collection;
                        $total_due_collection_of_each_user += $emergency_due_collection;


                        //Physio Due Collection
                        $date_physio_due_payment_condition = [
                            'due_payment_date >=' => $from_date,
                            'due_payment_date <=' => $to_date
                        ];
                        $this->db->select_sum('due_payment');
                        $this->db->from('phygiotherapy');
                        $this->db->where('due_payment_user_id', $user_value->user_id);
                        $this->db->where($date_physio_due_payment_condition); // Filter by date range
                        $query = $this->db->get();
                        $physio_due_collection = $query->row()->due_payment ?? 0; // Return 0 if no record found
                        $grand_physio_due_collection_amount += $physio_due_collection;
                        $grand_total_collection_amount += $physio_due_collection;
                        $grand_due_collection_amount += $physio_due_collection;
                        $total_due_collection_of_each_user += $physio_due_collection;



                    ?>
                        <tr>
                            <!-- <td><?php echo $serial++; ?></td> -->
                            <td><?php echo $user_value->name; ?></td>
                            <td><?php echo  number_format($ipd_discharge_collection) ?></td>
                            <td><?php echo  number_format($ipd_advance_collection) ?></td>
                            <td><?php echo  number_format($opd_booking_collection) ?></td>
                            <td><?php echo  number_format($emergency_collection) ?></td>
                            <td><?php echo  number_format($test_collection) ?></td>
                            <td><?php echo  number_format($phygiotherapy_collection) ?></td>

                            <td><?php echo  number_format($total_due_collection_of_each_user) ?></td>
                            <td><?php echo  number_format($total_amount_of_each_user) ?></td>
                            <td><?php echo  number_format($total_return_amount_of_each_user) ?></td>
                            <td><?php echo  number_format($medicine_sales_collection) ?></td>
                            <td><?php echo  number_format($total_amount_of_each_user - $total_return_amount_of_each_user) ?></td>
                        </tr>
                    <?php
                    }
                    $grand_total_collection_amount -= $grand_total_return_amount;
                    ?>
                    <tr style="font-weight:bold">

                        <!-- <td></td> -->
                        <td>Total</td>
                        <td><?php echo  number_format($grand_ipd_discharge_amount) ?></td>
                        <td><?php echo  number_format($grand_ipd_advacne_amount) ?></td>
                        <td><?php echo  number_format($grand_opd_booking_amount) ?></td>
                        <td><?php echo  number_format($grand_emergency_amount) ?></td>
                        <td><?php echo  number_format($grand_test_amount) ?></td>
                        <td><?php echo  number_format($grand_phygiotherapy_amount) ?></td>

                        <td><?php echo  number_format($grand_due_collection_amount) ?></td>
                        <td><?php echo  number_format($grand_total_collection_amount) ?></td>
                        <td><?php echo  number_format($grand_total_return_amount) ?></td>
                        <td><?php echo  number_format($grand_medicine_sales_amount) ?></td>
                        <td><?php echo  number_format($grand_total_collection_amount - $grand_total_return_amount) ?></td>

                    </tr>
                </table>
                <!-- Details Report Starts Here ***************************************************************************************** -->
                <div style="width: 50%;float:left">
                    <h3 style="text-align: center;">Income</h3>
                    <hr>
                    <?php
                    $opd_date_condition = [
                        'entry_date>=' => $from_date,
                        'entry_date<=' => $to_date
                    ];

                    $this->db->select('
    SUM(visiting_fee) as total_visiting_fee,
    SUM(discount) as total_discount,
    SUM(payable) as total_payable,
    SUM(returnable_amount) as total_returnable_amount,
    COUNT(*) as total_rows
');
                    $this->db->from('opd_patient');
                    if ($user_id == 'all') {
                    } else {
                        $this->db->where('user_id', $user_value->user_id);
                    }

                    $this->db->where($opd_date_condition);

                    $query = $this->db->get();
                    $result = $query->row();

                    $total_opd_visiting_fee = $result->total_visiting_fee ?? 0;

                    $total_opd_discount = $result->total_discount ?? 0;
                    $total_opd_payable = $result->total_payable ?? 0;
                    $total_opd_returnable_amount = $result->total_returnable_amount ?? 0;
                    $total_opd_discharge_rows = $result->total_rows ?? 0;

                    ?>
                    <table border="1" class="table table-bordered table-hover" style="width: 100%;margin-top:10px;color:black;border-collapse:collapse;">
                        <tr style="background-color: #1A7DFF;color: white">
                            <td colspan="6" style="text-align: center">OPD</td>
                        </tr>
                        <tr>
                            <td>Total Entry</td>
                            <td>Total Amount</td>
                            <td>Total Discount</td>
                            <td>Total Paid</td>
                            <td>Total Return</td>
                            <td>Balance</td>
                        </tr>
                        <tr>
                            <td><?php echo number_format($total_opd_discharge_rows) ?></td>
                            <td><?php echo number_format($total_opd_visiting_fee) ?></td>
                            <td><?php echo number_format($total_opd_discount) ?></td>
                            <td><?php echo number_format($total_opd_payable) ?></td>
                            <td><?php echo number_format($total_opd_returnable_amount) ?></td>
                            <td><?php echo number_format($total_opd_payable - $total_opd_returnable_amount) ?></td>
                        </tr>
                    </table>

                    <?php
                    $discharge_date_condition = [
                        'discharge_date >=' => $from_date,
                        'discharge_date <=' => $to_date
                    ];

                    $this->db->select('
    SUM(paid) as total_paid,
    SUM(special_discount) as total_special_discount,
    SUM(payable) as total_payable,
    COUNT(*) as total_rows
');
                    $this->db->from('discharge');
                    if ($user_id == 'all') {
                    } else {
                        $this->db->where('user_id', $user_value->user_id);
                    }
                    $this->db->where($discharge_date_condition);
                    $query = $this->db->get();
                    $result = $query->row();

                    $total_ipd_discharge_paid = $result->total_paid ?? 0;

                    $total_ipd_discharge_special_discount = $result->total_special_discount ?? 0;
                    $total_ipd_discharge_payable = $result->total_payable ?? 0;
                    $total_ipd_discharge_rows = $result->total_rows ?? 0;


                    //IPD Advance collection
                    $date_condition = [
                        'date >=' => $from_date,
                        'date <=' => $to_date
                    ];
                    $this->db->select_sum('paid_amount');
                    $this->db->from('ipd_patient');
                    if ($user_id == 'all') {
                    } else {
                        $this->db->where('user_id', $user_value->user_id);
                    }
                    $this->db->where($date_condition); // Filter by today's date
                    $query = $this->db->get();
                    $ipd_advance_collection = $query->row()->paid_amount ?? 0; // Return 0 if no income found


                    ?>
                    <table border="1" class="table table-bordered table-hover" style="width: 100%;margin-top:10px;color:black;border-collapse:collapse;">
                        <tr style="background-color: #1A7DFF;color: white">
                            <td colspan="4" style="text-align: center">IPD</td>
                        </tr>
                        <tr>
                            <td>Total Entry</td>
                            <td>Total Amount</td>
                            <td>Total Discount</td>
                            <td>Total Paid</td>
                        </tr>
                        <tr>
                            <td><?php echo number_format($total_ipd_discharge_rows) ?></td>
                            <td><?php echo number_format($total_ipd_discharge_payable) ?></td>
                            <td><?php echo number_format($total_ipd_discharge_special_discount) ?></td>
                            <td><?php echo number_format($total_ipd_discharge_paid + $ipd_advance_collection) ?></td>
                        </tr>
                    </table>
                    <table border="1" class="table table-bordered table-hover" style="width: 100%;margin-top:10px;color:black;border-collapse:collapse;">
                        <tr style="background-color: #1A7DFF;color: white">
                            <td colspan="7" style="text-align: center">Test</td>
                        </tr>
                        <tr>
                            <td>Department</td>
                            <td>Total Entry</td>
                            <td>Total Amount</td>
                            <td>Total Discount</td>
                            <td>Return</td>
                            <td>Due</td>
                            <td>Net Amount</td>
                        </tr>

                        <?php

                        $this->db->select_sum('due', 'due_amount'); // Sum of the due_amount column
                        $this->db->from('patient_test_entry');
                        $this->db->where('paid_or_due_status', 'due');
                        $this->db->where('date >=', $from_date);
                        $this->db->where('date <=', $to_date);
                        $query = $this->db->get();
                        $result = $query->row();

                        $total_test_due = $result->due_amount;

                        $grand_test_total_entry = 0;
                        $grand_test_total_price = 0;
                        $grand_test_total_discount_each = 0;
                        $grand_test_total_paid_each = 0;
                        $grand_test_total_return_each_category = 0;

                        $test_categories = $this->db->get('test_categories')->result();
                        foreach ($test_categories as $test_category) {
                            $date_condition = [
                                'date >=' => $from_date,
                                'date <=' => $to_date
                            ];

                            $this->db->select('
        SUM(total_price) as total_price,
        SUM(total_return) as total_return,
        SUM(discount_each) as total_discount_each,
        SUM(paid_each) as total_paid_each,
        COUNT(*) as total_rows
    ');
                            $this->db->from('patient_test_entry_details');

                            $this->db->where('test_category_id', $test_category->test_category_id); // Filter by category
                            $this->db->where($date_condition);

                            $query = $this->db->get();
                            $row = $query->row();
//   echo $this->db->last_query();
// die;
                            $test_total_price = $row->total_price ?? 0;
                            $test_total_discount_each = $row->total_discount_each ?? 0;
                            $test_total_paid_each = $row->total_paid_each ?? 0;
                            $test_total_return_each_category = $row->total_return ?? 0;
                            $test_total_rows = $row->total_rows ?? 0;

                            $grand_test_total_entry += $test_total_rows;
                            $grand_test_total_price += $test_total_price;
                            $grand_test_total_discount_each += $test_total_discount_each;
                            $grand_test_total_paid_each += $test_total_paid_each;
                            $grand_test_total_return_each_category += $test_total_return_each_category;
                        ?>
                            <tr>
                                <td><?php echo $test_category->test_category_name ?></td>
                                <td><?php echo number_format($test_total_rows) ?></td>
                                <td><?php echo number_format($test_total_price) ?></td>
                                <td><?php echo number_format($test_total_discount_each) ?></td>
                                <td><?php echo number_format($test_total_return_each_category) ?></td>
                                <td></td>
                                <td><?php echo number_format($test_total_paid_each - $test_total_return_each_category) ?></td>
                            </tr>
                        <?php } ?>

                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong><?php echo number_format((float)$grand_test_total_entry) ?></strong></td>
                            <td><strong><?php echo number_format((float)$grand_test_total_price) ?></strong></td>
                            <td><strong><?php echo number_format((float)$grand_test_total_discount_each) ?></strong></td>
                            <td><strong><?php echo number_format((float)$grand_test_total_return_each_category) ?></strong></td>
                            <td><?php echo number_format((float)$total_test_due) ?></td>
                            <td><strong><?php echo number_format((float)$grand_test_total_paid_each - (float)$grand_test_total_return_each_category-(float)$total_test_due) ?></strong></td>
                        </tr>
                    </table>
                    <table border="1" class="table table-bordered table-hover" style="width: 100%;margin-top:10px;color:black;border-collapse:collapse;">
                        <tr style="background-color: #1A7DFF;color: white">
                            <td colspan="2" style="text-align: center">Test Due Collection</td>
                        </tr>
                        <tr>
                            <td>Total Entry</td>
                            <td>Total Paid</td>
                        </tr>

                        <?php
                        $grand_test_total_entry = 0;
                        $grand_test_total_price = 0;
                        $grand_test_total_discount_each = 0;
                        $grand_test_total_paid_each = 0;

                        $test_categories = $this->db->get('test_categories')->result();

                        $date_condition = [
                            'date >=' => $from_date,
                            'date <=' => $to_date
                        ];

                        $this->db->select('SUM(paid) as total_paid,COUNT(*) as total_rows');
                        $this->db->from('test_collection');
                        $this->db->where('payment_type', 'from_due_collection');
                        if ($user_id == 'all') {
                        } else {
                            $this->db->where('user_id', $user_value->user_id);
                        }
                        $this->db->where($date_condition);

                        $query = $this->db->get();
                        $row = $query->row();

                        $test_due_collection_total_paid = $row->total_paid ?? 0;
                        $test_due_collection_total_rows = $row->total_rows ?? 0;

                        ?>
                        <tr>
                            <td><?php echo number_format($test_due_collection_total_rows) ?></td>
                            <td><?php echo number_format($test_due_collection_total_paid) ?></td>
                        </tr>
                    </table>
                    <?php
                    //Emergency Collection
                    $emergency_date_condition = [
                        'date >=' => $from_date,
                        'date <=' => $to_date
                    ];

                    $this->db->select('
    SUM(total) as total_price,
    SUM(discount) as total_discount,
    SUM(nettotal) as total_nettotal,
    SUM(paid) as total_paid,
    SUM(due) as total_due,
    SUM(due_payment) as total_due_payment,
    SUM(returnable_amount) as total_returnable_amount,
    COUNT(*) as total_rows
');
                    $this->db->from('emergency');
                    if ($user_id == 'all') {
                    } else {
                        $this->db->where('user_id', $user_value->user_id);
                    }
                    $this->db->where($emergency_date_condition);


                    $query = $this->db->get();
                    $result = $query->row();

                    $emergency_total_price = $result->total_price ?? 0;

                    $emergency_total_discount = $result->total_discount ?? 0;
                    $emergency_total_nettotal = $result->total_nettotal ?? 0;
                    $emergency_total_discount = $result->total_discount ?? 0;
                    $emergency_total_paid = $result->total_paid ?? 0;
                    $emergency_total_due = $result->due ?? 0;
                    $emergency_total_due_payment = $result->total_due_payment ?? 0;
                    $emeregency_returnable_amount = $result->total_returnable_amount ?? 0;
                    $emergecy_total_rows = $result->total_rows ?? 0;

                    ?>
                    <table border="1" class="table table-bordered table-hover" style="width: 100%;margin-top:10px;color:black;border-collapse:collapse;">
                        <tr style="background-color: #1A7DFF;color: white">
                            <td colspan="8" style="text-align: center">Emergency</td>
                        </tr>
                        <tr>
                            <td>Total Entry</td>
                            <td>Total Amount</td>
                            <td>Total Discount</td>
                            <td>Total Paid</td>
                            <td>Total Due</td>
                            <td>Total Due<br>Payment</td>
                            <td>Total Return</td>
                            <td>Balance</td>
                        </tr>
                        <tr>
                            <td><?php echo number_format($emergecy_total_rows) ?></td>
                            <td><?php echo number_format($emergency_total_price) ?></td>
                            <td><?php echo number_format($emergency_total_discount) ?></td>
                            <td><?php echo number_format($emergency_total_paid) ?></td>
                            <td><?php echo number_format($emergency_total_due) ?></td>
                            <td><?php echo number_format($emergency_total_due_payment) ?></td>
                            <td><?php echo number_format($emeregency_returnable_amount) ?></td>
                            <td><?php echo number_format((float)$emergency_total_paid + (float)$emergency_total_due_payment - (float)$total_opd_returnable_amount) ?></td>
                        </tr>
                    </table>
                    <?php
                    //Physiotherapy Collection
                    $physiotherapy_date_condition = [
                        'date >=' => $from_date,
                        'date <=' => $to_date
                    ];
                    $this->db->select('
    SUM(total) as total_price,
    SUM(discount) as total_discount,
    SUM(nettotal) as total_nettotal,
    SUM(paid) as total_paid,
    SUM(due) as total_due,
    SUM(due_payment) as total_due_payment,
    SUM(returnable_amount) as total_returnable_amount,
    COUNT(*) as total_rows
');
                    $this->db->from('phygiotherapy');
                    if ($user_id == 'all') {
                    } else {
                        $this->db->where('user_id', $user_value->user_id);
                    }
                    $this->db->where($physiotherapy_date_condition);
                    $query = $this->db->get();
                    $result = $query->row();
                    $physiotherapy_total_price = $result->total_price ?? 0;
                    $physiotherapy_total_discount = $result->total_discount ?? 0;
                    $physiotherapy_total_nettotal = $result->total_nettotal ?? 0;
                    $physiotherapy_total_paid = $result->total_paid ?? 0;
                    $physiotherapy_total_due = $result->due ?? 0;
                    $physiotherapy_total_due_payment = $result->total_due_payment ?? 0;
                    $physiotherapy_returnable_amount = $result->total_returnable_amount ?? 0;
                    $physiotherapy_total_rows = $result->total_rows ?? 0;

                    ?>
                    <table border="1" class="table table-bordered table-hover" style="width: 100%;margin-top:10px;color:black;border-collapse:collapse;">
                        <tr style="background-color: #1A7DFF;color: white">
                            <td colspan="8" style="text-align: center">Physiotherapy</td>
                        </tr>
                        <tr>
                            <td>Total Entry</td>
                            <td>Total Amount</td>
                            <td>Total Discount</td>
                            <td>Total Paid</td>
                            <td>Total Due</td>
                            <td>Total Due<br>Payment</td>
                            <td>Total Return</td>
                            <td>Balance</td>
                        </tr>
                        <tr>
                            <td><?php echo number_format($physiotherapy_total_rows) ?></td>
                            <td><?php echo number_format($physiotherapy_total_price) ?></td>
                            <td><?php echo number_format($physiotherapy_total_discount) ?></td>
                            <td><?php echo number_format($physiotherapy_total_paid) ?></td>
                            <td><?php echo number_format($physiotherapy_total_due) ?></td>
                            <td><?php echo number_format($physiotherapy_total_due_payment) ?></td>
                            <td><?php echo number_format($physiotherapy_returnable_amount) ?></td>
                            <td><?php echo number_format((float)$physiotherapy_total_paid + (float)$physiotherapy_total_due_payment - (float)$physiotherapy_returnable_amount) ?></td>
                        </tr>
                    </table>
                    <?php
                    //OT Service Collection
                    $ot_date_condition = [
                        'date >=' => $from_date,
                        'date <=' => $to_date
                    ];

                    $this->db->select('
    SUM(price) as total_price,
    SUM(total_discount) as total_discount,
    SUM(net_price) as total_nettotal,
    SUM(paid) as total_paid,
    SUM(due) as total_due,
    SUM(due_payment) as total_due_payment,
    SUM(returnable_amount) as total_returnable_amount,
    COUNT(*) as total_rows
');
                    $this->db->from('ot_services');
                    if ($user_id == 'all') {
                    } else {
                        $this->db->where('user_id', $user_value->user_id);
                    }

                    $this->db->where($ot_date_condition);


                    $query = $this->db->get();
                    $result = $query->row();

                    $ot_service_total_price = $result->total_price ?? 0;
                    $ot_service_total_discount = $result->total_discount ?? 0;
                    $ot_service_total_nettotal = $result->net_price ?? 0;
                    $ot_service_total_paid = $result->total_paid ?? 0;
                    $ot_service_total_due = $result->due ?? 0;
                    $ot_service_total_due_payment = $result->total_due_payment ?? 0;
                    $ot_service_returnable_amount = $result->total_returnable_amount ?? 0;
                    $ot_service_total_rows = $result->total_rows ?? 0;

                    ?>
                    <table border="1" class="table table-bordered table-hover" style="width: 100%;margin-top:10px;color:black;border-collapse:collapse;">
                        <tr style="background-color: #1A7DFF;color: white">
                            <td colspan="8" style="text-align: center">OT Service</td>
                        </tr>
                        <tr>
                            <td>Total Entry</td>
                            <td>Total Amount</td>
                            <td>Total Discount</td>
                            <td>Total Paid</td>
                            <td>Total Due</td>
                            <td>Total Due<br>Payment</td>
                            <td>Total Return</td>
                            <td>Balance</td>
                        </tr>
                        <tr>
                            <td><?php echo number_format($ot_service_total_rows) ?></td>
                            <td><?php echo number_format($ot_service_total_price) ?></td>
                            <td><?php echo number_format($ot_service_total_discount) ?></td>
                            <td><?php echo number_format($ot_service_total_paid) ?></td>
                            <td><?php echo number_format($ot_service_total_due) ?></td>
                            <td><?php echo number_format($ot_service_total_due_payment) ?></td>
                            <td><?php echo number_format($ot_service_returnable_amount) ?></td>
                            <td><?php echo number_format((float)$ot_service_total_paid + (float)$ot_service_total_due_payment - (float)$physiotherapy_returnable_amount) ?></td>
                        </tr>
                    </table>

                    <?php
                    //Medicine Sale Collection
                    $medicine_sale_date_condition = [
                        'bill_date >=' => $from_date,
                        'bill_date <=' => $to_date
                    ];

                    $this->db->select('
    SUM(total) as total_price,
    SUM(total_discount) as total_discount,
    SUM(nettotal) as total_nettotal,
    SUM(paid) as total_paid,
    SUM(due) as total_due,
    SUM(due_payment) as total_due_payment,
    COUNT(*) as total_rows
');
                    $this->db->from('medicine_sales');
                    if ($user_id == 'all') {
                    } else {
                        $this->db->where('user_id', $user_value->user_id);
                    }
                    $this->db->where($medicine_sale_date_condition);


                    $query = $this->db->get();
                    $result = $query->row();

                    $medicine_sale_total_price = $result->total_price ?? 0;
                    $medicine_sale_total_discount = $result->total_discount ?? 0;
                    $medicine_sale_total_nettotal = $result->net_price ?? 0;
                    $medicine_sale_total_paid = $result->total_paid ?? 0;
                    $medicine_sale_total_due = $result->due ?? 0;
                    $medicine_sale_total_due_payment = $result->total_due_payment ?? 0;
                    $medicine_sale_total_rows = $result->total_rows ?? 0;


                    //Pharmacy Return Amount
                    $date_condition = [
                        'date >=' => $from_date,
                        'date <=' => $to_date
                    ];
                    $this->db->select_sum('paid');
                    $this->db->from('medicine_sale_return');
                    if ($user_id == 'all') {
                    } else {
                        $this->db->where('user_id', $user_value->user_id);
                    }
                    $this->db->where($date_condition); // Filter by date range

                    $query = $this->db->get();
                    $medicine_sales_return_amount = $query->row()->paid ?? 0; // Return 0 if no record found

                    ?>
                    <table border="1" class="table table-bordered table-hover" style="width: 100%;margin-top:10px;color:black;border-collapse:collapse;">
                        <tr style="background-color: #1A7DFF;color: white">
                            <td colspan="8" style="text-align: center">Medicine Sale</td>
                        </tr>
                        <tr>
                            <td>Total Entry</td>
                            <td>Total Amount</td>
                            <td>Total Discount</td>
                            <td>Total Paid</td>
                            <td>Total Due</td>
                            <td>Total Due<br>Payment</td>
                            <td>Total Return</td>
                            <td>Balance</td>
                        </tr>
                        <tr>
                            <td><?php echo number_format($medicine_sale_total_rows) ?></td>
                            <td><?php echo number_format($medicine_sale_total_price) ?></td>
                            <td><?php echo number_format($medicine_sale_total_discount) ?></td>
                            <td><?php echo number_format($medicine_sale_total_paid) ?></td>
                            <td><?php echo number_format($medicine_sale_total_due) ?></td>
                            <td><?php echo number_format($medicine_sale_total_due_payment) ?></td>
                            <td><?php echo number_format($medicine_sales_return_amount) ?></td>
                            <td><?php echo number_format((float)$medicine_sale_total_paid + (float)$medicine_sale_total_due_payment - (float)$medicine_sales_return_amount) ?></td>
                        </tr>
                    </table>
                </div>
                <div style="width: 45%;float:right" id="expense">
                    <table border="1" class="table table-bordered table-hover" style="width: 100%;margin-top:10px;color:black;border-collapse:collapse;">
                        <tr style="background-color: #1A7DFF;color: white">
                            <td colspan="8" style="text-align: center">Due Collection</td>
                        </tr>

                        <tr>
                            <td>1</td>
                            <td>Test</td>
                            <td><?php echo $grand_test_collection_amount ?></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Emergency</td>
                            <td><?php echo $grand_emergency_due_collection_amount ?></td>
                        </tr>
                        <tr>

                        <tr>
                            <td>3</td>
                            <td>Physio</td>
                            <td><?php echo $grand_physio_due_collection_amount ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:right">Total</td>
                            <td>
                                <?php echo $grand_due_collection_amount ?>
                            </td>
                        </tr>
                    </table>

                    <table border="1" class="table table-bordered table-hover" style="width: 100%;margin-top:10px;color:black;border-collapse:collapse;">
                        <tr style="background-color: #1A7DFF;color: white">
                            <td colspan="8" style="text-align: center">Credit Voucher</td>
                        </tr>
                        <tr>
                            <td>#</td>
                            <td>Account Name</td>
                            <td>Amount</td>
                        </tr>
                        <?php
                        //Credit Voucher Collection
                        $credit_voucher_date_condition = [
                            'cv.date >=' => $from_date,
                            'cv.date <=' => $to_date
                        ];

                        $this->db->select('cv.credit_account_id, coa.account_name, SUM(cv.total_amount) as total_sum');
                        $this->db->from('credit_voucher cv');
                        $this->db->join('credit_account coa', 'coa.credit_account_id = cv.credit_account_id');
                        $this->db->where($credit_voucher_date_condition);
                        if ($user_id == 'all') {
                        } else {
                            $this->db->where('cv.user_id', $user_value->user_id);
                        }

                        $this->db->group_by(['cv.credit_account_id', 'coa.account_name']);

                        $query = $this->db->get();
                        $credit_vouchers = $query->result();
                        $serial = 1;
                        $grand_credit_amount = 0;
                        foreach ($credit_vouchers as $row) {
                        ?>
                            <tr>
                                <td><?php echo  $serial++; ?></td>
                                <td><?php echo  $row->account_name; ?></td>
                                <td><?php echo  number_format($row->total_sum);
                                    $grand_credit_amount += $row->total_sum; ?></td>
                            </tr>
                            <?php
                            ?>
                        <?php
                        }
                        $grand_total_collection_amount += $grand_credit_amount;
                        ?>
                        <tr>
                            <td colspan="2" style="text-align: right;">Total</td>
                            <td><?php echo  number_format($grand_credit_amount) ?></td>
                        </tr>
                    </table>
                    <h3 style="text-align: center;">Expense</h3>
                    <hr>
                    <table border="1" class="table table-bordered table-hover" style="width: 100%;margin-top:10px;color:black;border-collapse:collapse;">
                        <tr style="background-color: #1A7DFF;color: white">
                            <td colspan="8" style="text-align: center">Debit Voucher</td>
                        </tr>
                        <tr>
                            <td>#</td>
                            <td>Account Name</td>
                            <td>Amount</td>
                        </tr>
                        <?php
                        //Debit Voucher Collection
                        $debit_voucher_date_condition = [
                            'dv.date >=' => $from_date,
                            'dv.date <=' => $to_date
                        ];

                        $this->db->select('dv.debit_account_id, coa.account_name, SUM(dv.total_amount) as total_sum');
                        $this->db->from('debit_voucher dv');
                        $this->db->join('debit_account coa', 'coa.debit_account_id = dv.debit_account_id');
                        $this->db->where($debit_voucher_date_condition);
                        if ($user_id == 'all') {
                        } else {
                            $this->db->where('dv.user_id', $user_value->user_id);
                        }

                        $this->db->group_by(['dv.debit_account_id', 'coa.account_name']);

                        $query = $this->db->get();
                        $debit_vouchers = $query->result();
                        $serial = 1;
                        $grand_debit_amount = 0;
                        foreach ($debit_vouchers as $row) {
                        ?>
                            <tr>
                                <td><?php echo  $serial++; ?></td>
                                <td><?php echo  $row->account_name; ?></td>
                                <td><?php echo  number_format($row->total_sum);
                                    $grand_debit_amount += $row->total_sum; ?></td>
                            </tr>
                            <?php
                            ?>
                        <?php
                        }
                        ?>
                        <tr>
                            <td colspan="2" style="text-align: right;">Total</td>
                            <td><?php echo  number_format($grand_debit_amount) ?></td>
                        </tr>
                    </table>
                </div>
                <div id="balance" style="clear:left;margin-top: 50px; ">
                    <table border="1" class="table table-bordered table-hover" style="width: 100%;margin-top:10px;color:black;border-collapse:collapse;">
                        <tr style="background-color: #1A7DFF;color: white">
                            <td colspan="8" style="text-align: center">Balance</td>
                        </tr>
                        <tr>
                            <td>Total Income</td>
                            <td>Total Expense</td>
                            <td>Net Balance</td>
                        </tr>
                        <tr>
                            <td><?php echo number_format($grand_total_collection_amount - $grand_total_return_amount) ?></td>
                            <td><?php echo number_format($grand_debit_amount) ?></td>
                            <td><?php echo number_format($grand_total_collection_amount - $grand_total_return_amount - $grand_debit_amount) ?></td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>
    </page>




</body>

</html>