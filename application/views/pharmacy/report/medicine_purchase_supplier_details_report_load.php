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

    <title>Medicine Purcase Report</title>
</head>

<body>
    <?php
    error_reporting();
    $company = getCompany();
    $this->db->from('medicine_purchase');

    // Optional Date Filter
    if (!empty($from_date) && !empty($to_date)) {
        $this->db->where('date >=', date('Y-m-d', strtotime($from_date)));
        $this->db->where('date <=', date('Y-m-d', strtotime($to_date)));
    }

    // Optional Supplier ID Filter
    if (!empty($supplier_id)) {
        $this->db->where('supplier_id', $supplier_id);
    }
    $total_medicine_purchase = $this->db->get();

    if ($total_medicine_purchase->num_rows() > 0) {
        $total_medicine_purchases = $total_medicine_purchase->result();

        foreach ($total_medicine_purchases as $medicine_purchase):
            $user = getUserById($medicine_purchase->user_id);
            $supplier = $this->db->where('supplier_id', $medicine_purchase->supplier_id)->get('supplier')->row();
    ?>
            <page size="A4">
                <div class="first">
                    <div class="second">
                        <div style="width: 100%;margin-bottom: 10px;">
                            <div style="width: 15%;float: left;margin-top:20px">
                                <img src="<?= base_url('assets/images/' . $company->logo) ?>" style="width:100%;">
                            </div>
                            <div style="width: 70%;float: left;text-align: center">
                                <span style="font-size: 16px;">
                                    <strong><?= $company->company_name ?><br><?= $company->address ?>
                                        Email: <?= $company->email ?>, Web: <?= $company->web ?></strong>
                                </span>
                            </div>
                            <div style="width: 15%;float: left;margin-top:20px">
                                <img src="<?= base_url('MedicinePurchaseController/set_barcode/' . $medicine_purchase->medicine_purchase_invoice_no); ?>">
                            </div>
                        </div>

                        <div style="clear: both;"></div>

                        <table border="0" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                            <tr>
                                <td style="font-weight: bold;">Supplier</td>
                                <td><b><?= $supplier->name ?></b></td>
                                <td style="font-weight: bold;">Status</td>
                                <td><b><?= $medicine_purchase->status ?></b></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Invoice No</td>
                                <td><?= $medicine_purchase->medicine_purchase_invoice_no ?></td>
                                <td style="font-weight: bold;">Date</td>
                                <td><?= date('d-m-Y', strtotime($medicine_purchase->date)) ?></td>
                            </tr>
                        </table>

                        <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
                            <tr>
                                <td style="font-weight: bold;">Sl</td>
                                <td style="font-weight: bold;">Medicine Name</td>
                                <td style="font-weight: bold;">MRP</td>
                                <td style="font-weight: bold;">P.Rate</td>
                                <td style="font-weight: bold;">Qty</td>
                                <td style="font-weight: bold;">B.Qty</td>
                                <td style="font-weight: bold;">Amount</td>
                            </tr>
                            <?php
                            $sl = 1;
                            $medicine_purchase_details = $this->db
                                ->where('medicine_purchase_id', $medicine_purchase->medicine_purchase_id)
                                ->get('medicine_purchase_details')->result();

                            foreach ($medicine_purchase_details as $detail):
                                $drug = $this->db->where('drug_id', $detail->drug_id)->get('drug')->row();
                            ?>
                                <tr>
                                    <td style="font-weight: bold;"><?= $sl++ ?></td>
                                    <td style="font-weight: bold;"><?= $drug->drug_name ?></td>
                                    <td style="font-weight: bold;"><?= $detail->mrp_rate ?></td>
                                    <td style="font-weight: bold;"><?= $detail->purchase_rate ?></td>
                                    <td style="font-weight: bold;"><?= $detail->quantity ?></td>
                                    <td style="font-weight: bold;"><?= $detail->bonus_quantity ?></td>
                                    <td style="font-weight: bold;"><?= $detail->amount ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="6" style="text-align:right;font-weight: bold;">Sub Total</td>
                                <td style="font-weight: bold;"><?= number_format($medicine_purchase->total, 3) ?></td>
                            </tr>
                            <tr>
                                <td colspan="6" style="text-align:right;font-weight: bold;">Vat</td>
                                <td style="font-weight: bold;"><?= $medicine_purchase->vat ?></td>
                            </tr>
                            <tr>
                                <td colspan="6" style="text-align:right;font-weight: bold;">Discount</td>
                                <td style="font-weight: bold;"><?= $medicine_purchase->discount ?></td>
                            </tr>
                            <tr>
                                <td colspan="6" style="text-align:right;font-weight: bold;">Net Payable</td>
                                <td style="font-weight: bold;"><?= number_format($medicine_purchase->nettotal) ?></td>
                            </tr>
                            <tr>
                                <td>In Word:</td>
                                <td colspan="6">

                                    <b><?php echo convertNumberToWord($medicine_purchase->nettotal) ?> Taka only</b>
                                </td>
                            </tr>
                        </table>

                        <div style="margin-top: 5px; ">
                            <div style="width: 50%;float:left">
                                <p style="text-align: left;">Software By:<span style="font-weight:bold"> Bijoylab, www.bijoylab.com</span></p>
                            </div>
                            <div style="width: 30%;float:right">
                                <p style="text-align: right;font-weight: bold;">Entry By: <?php echo  $user->name ?? "" ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </page>
        <?php
        endforeach;
    } else {
        ?>
        <div>
            <p style="text-align: center;">Data Not Found</p>
        </div>
    <?php

    }
    ?>
</body>

</html>