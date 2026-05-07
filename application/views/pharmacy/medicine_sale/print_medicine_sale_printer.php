<style>
    @media print {
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
        }

        #ad_fotter1 {
            margin-top: -25px !important;
        }

        #ad_fotter2 {
            margin-top: 100px !important;
        }
    }
</style>

<div class="row no-print">
    <div class="col-md-12">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
    </div>
</div>

<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;margin-top:40px;color:black;height: 20cm ">
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

    <div class="container" style="width: 100%;margin-bottom: 10px;">
        <div style="width: 15%;float: left;margin-top:20px">
            <img src="<?= base_url('assets/images/' . $compnay->logo) ?>" style="width:100%;">
        </div>
        <div style="width: 70%;float: left;text-align: center">
            <span style="font-size: 16px;">
                <strong><?= $compnay->company_name ?><br><?= $compnay->address ?>
                    Email: <?= $compnay->email ?>, Web: <?= $compnay->web ?></strong>
            </span>
        </div>
        <div style="width: 15%;float: left;margin-top:20px">
            <img src="<?php echo base_url('MedicineSaleController/set_barcode/' . $medicine_sale->medicine_sale_invoice_no); ?>" alt="Barcode">
        </div>
    </div>
    <div class="container">


        <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
            <tr>
                <td><strong>Name</strong></td>
                <td><?php echo $medicine_sale->name . ' ' . $medicine_sale->ipd_patient_id ?></td>

                <td><strong>Phone</strong></td>
                <td><?php echo $medicine_sale->mobile_number ?></td>
                <td>
                    <strong>Entry Date & Time</strong> :
                    <?php
                    // Set Bangladesh timezone
                    date_default_timezone_set('Asia/Dhaka');

                    // Format datetime in 12-hour format with AM/PM
                    echo date('d-m-Y h:i:s A', strtotime($medicine_sale->data_insert_time));
                    ?>
                </td>


            </tr>
            <tr>
                <td><strong>Address</strong></td>
                <td><?php echo $medicine_sale->address ?></td>

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
                <td></td>

            </tr>
            <tr>
                <td><strong>Invoice No</strong></td>
                <td><?php echo $medicine_sale->medicine_sale_invoice_no ?></td>

                <td><strong>Date</strong></td>
                <td><?php echo date('d-m-Y', strtotime($medicine_sale->bill_date)) ?></td>
                <td></td>

            </tr>
        </table>



        <table border="1" style="width: 100%;border-collapse:collapse;margin-top:5px;color:black">
            <tr>
                <th style="text-align: left;">Sl</th>
                <th style="text-align: left;">Medicine Name</th>
                <th style="text-align: left;">Qty</th>
                <th style="text-align: left;">Unit Price</th>
                <th style="text-align: left;">Total</th>
            </tr>
            <?php
            $sl = 1;
            $sales_details = $this->db->where('medicine_sale_id', $medicine_sale_id)->get('medicine_sales_details')->result();

            foreach ($sales_details as $sales_details_value) {
                $drug = $this->db->where('drug_id', $sales_details_value->drug_id)->get('drug')->row();
            ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td><?php echo $drug->drug_name ?></td>
                    <td><?php echo $sales_details_value->quantity ?></td>
                    <td><?php echo $sales_details_value->sales_rate ?></td>
                    <td><?php echo $sales_details_value->amount ?></td>
                </tr>
            <?php } ?>

            <tr>
                <td colspan="4" style="text-align: right;"><strong>Sub Total</strong></td>
                <td><?php echo number_format($medicine_sale->total, 3) ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Discount</strong></td>
                <td>
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
                <td colspan="4" style="text-align: right;"><strong>Net Total</strong></td>
                <td><?php echo number_format($medicine_sale->nettotal, 3) ?></td>
            </tr>
            <tr>
                <td>In Word:</td>
                <td colspan="2"><b><?php echo convertNumberToWord($medicine_sale->paid + $medicine_sale->due_payment) ?> Taka only</b></td>
                <td style="text-align: right;"><strong>Paid</strong></td>
                <td><?php echo number_format($medicine_sale->paid + $medicine_sale->due_payment, 3) ?></td>
            </tr>
            <tr>
                <td colspan="3">
                    <?php
                    if ($medicine_sale->due_reference != '') {
                        echo "Due Reference: <b>" . $medicine_sale->due_reference . "</b>";
                    }
                    ?>
                </td>
                <td style="text-align: right;"><strong>Due</strong></td>
                <td><?php echo $medicine_sale->due != '' ? number_format($medicine_sale->due, 3) : '' ?></td>
            </tr>
        </table>
    </div>

    <div class="container" style="clear:left;margin-top: 20px;">
        <div style="width: 50%;float:left;">
            <p style="text-align: left;">Software By:<span style="font-weight:bold"> Bijoylab, www.bijoylab.com</span></p>
        </div>
        <div style="width: 50%;float:left;">
            <p style="text-align: right;">Entry By: <?php echo  $user->name ?? "" ?></p>
        </div>
    </div>
</div>