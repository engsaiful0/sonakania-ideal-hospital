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

    <title>Credit Voucher Report</title>
</head>

<body>
    <?php
    $total_journal_voucher = '';
    if ($credit_account_id != '') {
        $total_journal_voucher = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
            ->where('date<=', date('Y-m-d', strtotime($to_date)))
            ->where('credit_account_id', $credit_account_id)
            ->get('journal_vouchers');
    } else if ($debit_account_id != '') {
        $total_journal_voucher = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
            ->where('date<=', date('Y-m-d', strtotime($to_date)))
            ->where('debit_account_id', $debit_account_id)
            ->get('journal_vouchers');
    } else {
        $total_journal_voucher = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
            ->where('date<=', date('Y-m-d', strtotime($to_date)))
            ->get('journal_vouchers');
    }
    $total_rows = $total_journal_voucher->num_rows();

    $page_limit = ceil($total_rows / 30);
    //echo $page_limit;
    $from = -30;
    $start = 0;

    $grand_total_amount = 0;
    if ($page_limit != 0) {
        for ($page_no = 1; $page_no <= $page_limit; $page_no++):
    ?>
            <page size="A4">
                <div class="first">
                    <div class="second">
                        <?php
                        $grand_total_amount = 0;
                        $from = $from + 30;

                        // Corrected SQL query with proper joins
                        $this->db->select('journal_vouchers.*, credit_account.account_name');
                        $this->db->from('journal_vouchers');
                        $this->db->join('credit_account', 'credit_account.credit_account_id = journal_vouchers.credit_account_id', 'left');
                        $this->db->join('debit_account', 'debit_account.debit_account_id = journal_vouchers.debit_account_id', 'left');

                        if (!empty($credit_account_id)) {
                            $this->db->where('journal_vouchers.credit_account_id', $credit_account_id);
                        }
                        if (!empty($debit_account_id)) {
                            $this->db->where('journal_vouchers.debit_account_id', $debit_account_id);
                        }

                        $this->db->where('journal_vouchers.date >=', date('Y-m-d', strtotime($from_date)));
                        $this->db->where('journal_vouchers.date <=', date('Y-m-d', strtotime($to_date)));
                        $this->db->order_by('journal_vouchers.journal_voucher_id', 'DESC');
                        $this->db->limit(30, $from);
                        $query = $this->db->get()->result();

                        ?>
                        <table border="1" class="table table-bordered table-hover" style="width: 90%; margin: 0 auto; color: black; border-collapse: collapse;">
                            <tr style="background-color: #0074B3; color: white;">
                                <td colspan="7" style="text-align: center;">
                                    <b>Journal Voucher Report</b>
                                    From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b>
                                    To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Sl</b></td>
                                <td><b>Debit Account</b></td>
                                <td><b>Credit Account</b></td>
                                <td><b>Voucher No</b></td>
                                <td><b>Amount</b></td>
                                <td><b>Date</b></td>
                            </tr>

                            <?php $sl = 1; ?>
                            <?php foreach ($query as $row):
                                $credit_account = getCreditAccount($row->credit_account_id);
                                $debit_account = getDebitAccount($row->debit_account_id);
                            ?>
                                <tr>
                                    <td><?= $sl++ ?></td>
                                    <td><?php echo $debit_account->account_name . '-' . $debit_account->account_number ?></td>
                                    <td><?php echo $credit_account->account_name . '-' . $credit_account->account_number ?></td>
                                    <td><?php echo $row->journal_voucher_no ?></td>
                                    <td>
                                        <?= number_format($row->total_amount) ?>
                                        <?php $grand_total_amount += $row->total_amount; ?>
                                    </td>

                                    <td><?= date('d-m-Y', strtotime($row->date)) ?></td>
                                </tr>
                            <?php endforeach; ?>

                            <tr>
                                <td colspan="3"></td>
                                <td><b>Total</b></td>
                                <td><b><?= number_format($grand_total_amount) ?></b></td>
                                <td colspan="3"></td>
                            </tr>
                        </table>

                    </div>
                </div>
            </page>
        <?php
        endfor;
    } else {
        ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                        <tr style="background-color: #0074B3;color: white  ">
                            <td colspan="7" style="text-align: center"><b>Journal Voucher Report</b> From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                        </tr>
                        <tr>
                            <td colspan="7" style="text-align: center;">Data Not Found</td>
                        </tr>
                    </table>
                </div>
            </div>
        </page>
    <?php
    }
    ?>

    ?>

</body>

</html>