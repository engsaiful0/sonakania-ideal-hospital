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
            width: 21cm;
            height: 29cm;
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

    <title>Income Vs Expense Report</title>
</head>

<body>
    <?php
    $total_issue_details = $this->db
        ->where('date>=', date('Y-m-d', strtotime($from_date)))
        ->where('date<=', date('Y-m-d', strtotime($to_date)))
        ->get('issue_details');
    $total_rows = $total_issue_details->num_rows();

    $this->db->select_sum('nettotal');
    $this->db->from('medicine_sales');
    $this->db
        ->where('date>=', date('Y-m-d', strtotime($from_date)))
        ->where('date<=', date('Y-m-d', strtotime($to_date))); // Filter by today's date
    $query = $this->db->get();
    $total_medicine_sells_today = $query->row()->nettotal ?? 0; // Return 0 if no income found

    $this->db->select_sum('payable');
    $this->db->from('opd_patient');
    $this->db
        ->where('entry_date>=', date('Y-m-d', strtotime($from_date)))
        ->where('entry_date<=', date('Y-m-d', strtotime($to_date)));
    $query = $this->db->get();
    $total_opd_payable_today = $query->row()->payable ?? 0; // Return 0 if no income found

    $this->db->select_sum('paid_amount');
    $this->db->from('ipd_patient');
    $this->db
        ->where('date>=', date('Y-m-d', strtotime($from_date)))
        ->where('date<=', date('Y-m-d', strtotime($to_date))); // Filter by today's date
    $query = $this->db->get();
    $total_ipd_paid_amount_today = $query->row()->paid_amount ?? 0; // Return 0 if no income found

    $this->db->select_sum('paid');
    $this->db->from('emergency');
    $this->db
        ->where('date>=', date('Y-m-d', strtotime($from_date)))
        ->where('date<=', date('Y-m-d', strtotime($to_date))); // Filter by today's date
    $query = $this->db->get();
    $total_emergency_today = $query->row()->paid ?? 0; // Return 0 if no income found

    $this->db->select_sum('paid');
    $this->db->from('phygiotherapy');
    $this->db
        ->where('date>=', date('Y-m-d', strtotime($from_date)))
        ->where('date<=', date('Y-m-d', strtotime($to_date))); // Filter by today's date
    $query = $this->db->get();
    $total_phygiotherapy_today = $query->row()->paid ?? 0; // Return 0 if no income found

    //ot_services table to paid amount
    $this->db->select_sum('paid');
    $this->db->from('ot_services');
    $this->db
        ->where('date>=', date('Y-m-d', strtotime($from_date)))
        ->where('date<=', date('Y-m-d', strtotime($to_date))); // Filter by today's date
    $query = $this->db->get();
    $total_ot_service_paid_today = $query->row()->paid ?? 0; // Return 0 if no income found
    

    $this->db->select_sum('total_amount');
    $this->db->from('debit_voucher');
    $this->db
        ->where('date>=', date('Y-m-d', strtotime($from_date)))
        ->where('date<=', date('Y-m-d', strtotime($to_date))); // Filter by today's date
    $query = $this->db->get();
    $total_debit_voucher_today = $query->row()->total_amount ?? 0; // Return 0 if no income found

    $this->db->select_sum('total_amount');
    $this->db->from('credit_voucher');
    $this->db
        ->where('date>=', date('Y-m-d', strtotime($from_date)))
        ->where('date<=', date('Y-m-d', strtotime($to_date))); // Filter by today's date
    $query = $this->db->get();
    $total_credit_voucher_today = $query->row()->total_amount ?? 0; // Return 0 if no income found


    $this->db->select_sum('paid');
    $this->db->from('test_collection');
    $this->db->where('payment_type', 'from_direct_sales');
    $this->db
        ->where('date>=', date('Y-m-d', strtotime($from_date)))
        ->where('date<=', date('Y-m-d', strtotime($to_date))); // Filter by today's date
    $query = $this->db->get();
    $total_test_entry_today = $query->row()->paid ?? 0; // Return 0 if no income found


    $this->db->select_sum('paid');
    $this->db->from('test_collection');
    $this->db->where('payment_type', 'from_due_collection');
    $this->db
        ->where('date>=', date('Y-m-d', strtotime($from_date)))
        ->where('date<=', date('Y-m-d', strtotime($to_date))); // Filter by today's date
    $query = $this->db->get();
    $total_test_due_collection_today = $query->row()->paid ?? 0; // Return 0 if no income found
    $this->db->select_sum('paid');
    $this->db->from('discharge');
    $this->db
        ->where('discharge_date>=', date('Y-m-d', strtotime($from_date)))
        ->where('discharge_date<=', date('Y-m-d', strtotime($to_date))); // Filter by today's date
    $query = $this->db->get();
    $total_discharge_value_today = $query->row()->paid ?? 0; // Return 0 if no income found

    ?>
    <page size="A4">
        <div class="first">
            <div class="second">
                <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                    <tr style="background-color: #0074B3;color: white  ">
                        <td colspan="5" style="text-align: center"><b>Income Vs Expense Report</b> From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                    </tr>

                    <tr style="text-align: left">
                        <th>#</th>
                        <th>Title</th>
                        <th>Income</th>
                        <th>Expense</th>
                        <th>Balance</th>
                    </tr>

                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>OPD</td>
                            <td><?php echo  number_format($total_opd_payable_today) ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>IPD</td>
                            <td><?php echo  number_format($total_ipd_paid_amount_today) ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>IPD</td>
                            <td><?php echo  number_format($total_discharge_value_today) ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Emergency</td>
                            <td><?php echo  number_format($total_emergency_today) ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Phygiotherapy</td>
                            <td><?php echo  number_format($total_phygiotherapy_today) ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Test Entry</td>
                            <td><?php echo  number_format($total_test_entry_today) ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Test Due Collection</td>
                            <td><?php echo  number_format($total_test_due_collection_today) ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>Pharmacy</td>
                            <td><?php echo  number_format($total_medicine_sells_today) ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>Credit Voucher</td>
                            <td><?php echo  number_format($total_credit_voucher_today) ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>OT</td>
                            <td><?php echo  number_format($total_ot_service_paid_today) ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>Debit Voucher</td>
                            <td></td>
                            <td><?php echo  number_format($total_debit_voucher_today) ?></td>
                            <td></td>
                        </tr>
                        <tr style="background-color: #0074B3;color: white  ">
                            <td></td>
                            <td style="text-align:right">Total</td>
                            <td><?php echo  number_format($total_ot_service_paid_today+$total_discharge_value_today+$total_test_entry_today + $total_test_due_collection_today + $total_opd_payable_today + $total_ipd_paid_amount_today + $total_emergency_today + $total_phygiotherapy_today + $total_medicine_sells_today + $total_credit_voucher_today) ?></td>
                            <td><?php echo  number_format($total_debit_voucher_today) ?></td>
                            <td><?php echo  number_format(($total_ot_service_paid_today+$total_discharge_value_today+$total_test_entry_today + $total_test_due_collection_today + $total_opd_payable_today + $total_ipd_paid_amount_today + $total_emergency_today + $total_phygiotherapy_today + $total_medicine_sells_today + $total_credit_voucher_today) - ($total_debit_voucher_today)) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </page>


</body>

</html>