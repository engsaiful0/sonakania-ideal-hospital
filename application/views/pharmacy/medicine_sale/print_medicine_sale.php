<style>
    @media print {
        @page {
            size: auto;
            margin: 0;
        }

        body {
            margin: 0;
            font-size: 14px;
            font-family: 'Courier New', Courier, monospace;
        }

        body * {
            visibility: hidden;
        }

        #report,
        #report * {
            visibility: visible;
            overflow: visible;
        }

        #report {
            position: absolute;
            left: 0;
            top: 0;
            width: 80mm;
            margin: 0 auto;
        }

        table {
            width: 100%;
            font-size: 14px;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 3px;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    }
</style>

<div class="row no-print">
    <div class="col-md-12">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
    </div>
</div>

<div id="report" style="color:black;">
    <?php
    error_reporting(0);
    $medicine_sale = '';

    if ($this->session->userdata('print_medicine_sale_id')) {
        $medicine_sale_id = $this->session->userdata('print_medicine_sale_id');
        $medicine_sale = $this->db->where('medicine_sale_id', $medicine_sale_id)->get('medicine_sales')->row();
        $this->session->unset_userdata('print_medicine_sale_id');
    } else {
        $medicine_sale = $this->db->where('medicine_sale_id', $medicine_sale_id)->get('medicine_sales')->row();
    }
    $user = getUserById($medicine_sale->user_id);
    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    ?>

    <div style="width: 100%; margin-bottom: 10px; text-align: center;">
        <div style="width: 100%;">
            <img style="width:50px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
            <span style="margin: 5px 0;"><strong><?php echo $compnay->company_name ?></strong><br><strong><?php echo $compnay->address ?></strong><span>
                    <span style="margin: 5px 0;font-weight: bold;">
                        Email: <?php echo $compnay->email ?>, Web: <?php echo $compnay->web ?>
                    </span>
        </div>
        <div>
            <img src="<?php echo base_url('MedicineSaleController/set_barcode/' . $medicine_sale->medicine_sale_invoice_no); ?>" alt="Barcode">
        </div>
    </div>

    <table border="1">
        <tr>
            <td><strong>Name</strong></td>
            <td style="font-weight: bold;"><?php echo $medicine_sale->name . ' ' . $medicine_sale->ipd_patient_id ?></td>
        </tr>
        <tr>
            <td><strong>Phone</strong></td>
            <td style="font-weight: bold;"><?php echo $medicine_sale->mobile_number ?></td>
        </tr>
        <tr>
            <td><strong>Address</strong></td>
            <td style="font-weight: bold;"><?php echo $medicine_sale->address ?></td>
        </tr>
        <tr>
            <td><strong>Age</strong></td>
            <td>
                <b><?php
                    $age_parts = [];

                    if ($medicine_sale->age_year > 0) {
                        $age_parts[] = $medicine_sale->age_year . ' ' . ($medicine_sale->age_year == 1 ? 'Year' : 'Years');
                    }

                    if ($medicine_sale->age_month > 0) {
                        $age_parts[] = $medicine_sale->age_month . ' ' . ($medicine_sale->age_month == 1 ? 'Month' : 'Months');
                    }

                    if ($medicine_sale->age_day > 0) {
                        $age_parts[] = $medicine_sale->age_day . ' ' . ($medicine_sale->age_day == 1 ? 'Day' : 'Days');
                    }

                    echo implode(' ', $age_parts);
                    ?></b>
            </td>
        </tr>
        <tr>
            <td><strong>Invoice No</strong></td>
            <td style="font-weight: bold;"><?php echo $medicine_sale->medicine_sale_invoice_no ?></td>
        </tr>
        <tr>
            <td><strong>Date</strong></td>
            <td style="font-weight: bold;"><?php echo date('d-m-Y', strtotime($medicine_sale->bill_date)) ?></td>
        </tr>
        <tr>
            <td><strong>Entry Date & Time</strong></td>
            <td style="font-weight: bold;">
                <?php
                date_default_timezone_set('Asia/Dhaka');
                echo date('d-m-Y h:i:s A', strtotime($medicine_sale->data_insert_time));
                ?>
            </td>
        </tr>
    </table>

    <hr>

    <table border="1">
        <tr>
            <th style="font-weight: bold;">Sl</th>
            <th style="font-weight: bold;">Medicine Name</th>
            <th style="font-weight: bold;">Qty</th>
            <th style="font-weight: bold;">Unit Price</th>
            <th style="font-weight: bold;">Total</th>
        </tr>
        <?php
        $sl = 1;
        $sales_details = $this->db->where('medicine_sale_id', $medicine_sale_id)->get('medicine_sales_details')->result();

        foreach ($sales_details as $sales_details_value) {
            $drug = $this->db->where('drug_id', $sales_details_value->drug_id)->get('drug')->row();
        ?>
            <tr>
                <td style="font-weight: bold;"><?php echo $sl++ ?></td>
                <td style="font-weight: bold;"><?php echo $drug->drug_name ?></td>
                <td style="font-weight: bold;"><?php echo $sales_details_value->quantity ?></td>
                <td style="font-weight: bold;"><?php echo $sales_details_value->sales_rate ?></td>
                <td style="font-weight: bold;"><?php echo $sales_details_value->amount ?></td>
            </tr>
        <?php } ?>
    </table>

    <hr>

    <table border="1">
        <tr>
            <td><strong>Sub Total</strong></td>
            <td style="font-weight: bold;"><?php echo number_format($medicine_sale->total, 3) ?></td>
        </tr>
        <tr>
            <td><strong>Discount</strong></td>
            <td style="font-weight: bold;">
                <?php
                if (str_ends_with($medicine_sale->discount, '%')) {
                    if ($medicine_sale->total_discount == '') {
                        echo $medicine_sale->discount;
                    } else { {
                            echo $medicine_sale->total_discount;
                        }
                    }
                } else {

                    echo $medicine_sale->discount;
                }
                ?>
            </td>

        </tr>
        <tr>
            <td><strong>Net Total</strong></td>
            <td style="font-weight: bold;"><?php echo number_format($medicine_sale->nettotal, 3) ?></td>
        </tr>
        <tr>
            <td><strong>Paid</strong></td>
            <td style="font-weight: bold;"><?php echo number_format($medicine_sale->paid, 3) ?></td>
        </tr>
        <tr>
            <td><strong>Due</strong></td>
            <td style="font-weight: bold;"><?php echo $medicine_sale->due != '' ? number_format($medicine_sale->due, 3) : '' ?></td>
        </tr>
        <tr>
            <td>In Word:</td>
            <td style="font-weight: bold;">

                <b><?php echo convertNumberToWord($medicine_sale->paid) ?> Taka only</b>
            </td>
        </tr>
    </table>

    <div class="container" style="clear:left;margin-top: 20px;">
        <div style="width: 50%;float:left;">
            <p style="text-align: left;">Software By:<span style="font-weight:bold"> Bijoylab, www.bijoylab.com</span></p>
        </div>
        <div style="width: 50%;float:left;">
            <p style="text-align: right;font-weight: bold;">Entry By: <?php echo  $user->name ?? "" ?></p>
        </div>
    </div>
</div>