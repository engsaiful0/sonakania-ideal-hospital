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
    }
</style>

<div class="row">
    <div class="col-md-12">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
    </div>
</div>

<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;margin-top:40px;color:black;height: 20cm ">
    <?php

    error_reporting(0);
    $medicine_sale_return = '';
    if ($this->session->userdata('print_medicine_sale_return_id')) {
        $medicine_sale_return_id = $this->session->userdata('print_medicine_sale_return_id');
        $medicine_sale_return = $this->db->where('medicine_sale_return_id', $medicine_sale_return_id)
            ->get('medicine_sale_return')
            ->row();
        $this->session->unset_userdata('print_medicine_sale_return_id');
    } else {
        $medicine_sale_return = $this->db->where('medicine_sale_return_id', $medicine_sale_return_id)
            ->get('medicine_sale_return')
            ->row();
    }
    $medicine_sale = $this->db->where('medicine_sale_id', $medicine_sale_return->medicine_sale_id)
        ->get('medicine_sales')
        ->row();
    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    ?>

    <div class="" style="width: 100%;margin-bottom: 10px;">
        <div style="width: 15%;float: left;">
            <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
        </div>
        <div style="width: 70%;float: left;margin-bottom: 10px;text-align: center">

            <p style="text-align: center"><span style="text-align: center;font-size: 25px;text-align: center "> <?php echo $compnay->company_name ?></span><br>
                <span style="text-align: center"> Mobile: <?php echo $compnay->mobile ?><br>
                    Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                </span>
            </p>
        </div>
        <div style="width: 15%;float: left;">
            <img src="<?php echo base_url('MedicineSaleController/set_barcode/' . $medicine_sale_return->medicine_sale_return_invoice_no); ?>" alt="Barcode">
        </div>
    </div>
    <div class="name" style="width: 100%;margin-bottom: 5px;clear: left">

        <table border="0" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
            <tr>
                <td>Name</td>
                <td>
                    <b><?php echo $medicine_sale->name ?></b>
                </td>
                <td>Phone</td>
                <td>
                    <b><?php echo $medicine_sale->phone ?></b>
                </td>
            </tr>
            <tr>
                <td>Invoice No</td>
                <td>
                    <?php echo $medicine_sale->medicine_sale_invoice_no ?>
                </td>
                <td>Date</td>
                <td>

                    <?php echo date('d-m-Y', strtotime($medicine_sale->bill_date)) ?>
                </td>
            </tr>
            <tr>
                <td>Return Invoice No</td>
                <td>
                    <?php echo $medicine_sale_return->medicine_sale_return_invoice_no ?>
                </td>
                <td>Return Date</td>
                <td>

                    <?php echo date('d-m-Y', strtotime($medicine_sale_return->date)) ?>
                </td>
            </tr>
        </table>
    </div>

    <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
        <tr>
            <td>Sl</td>
            <td>Medicin</td>
            <td>Return Quantiy</td>
            <td>Unit Price</td>
            <td>Discount</td>
            <td>Total Price</td>
        </tr>
        <?php
        $sl = 1;

        $medicine_sale_return_details = $this->db
            ->where('medicine_sale_return_id', $medicine_sale_return_id)
            ->get('medicine_sale_return_details')->result();
        // echo '<pre>';
        // print_r($medicine_sale_return_details);
        foreach ($medicine_sale_return_details as $sales_detail_return_value) {


            $drug = $this->db
                ->where('drug_id', $sales_detail_return_value->drug_id)
                ->get('drug')
                ->row();
        ?>
            <tr>
                <td><?php echo $sl++ ?></td>
                <td><?php echo $drug->drug_name ?></td>
                <td><?php echo $sales_detail_return_value->return_quantity ?></td>
                <td><?php echo $sales_detail_return_value->sales_rate ?></td>
                <td><?php echo $sales_detail_return_value->discounteach ?></td>
                <td><?php echo $sales_detail_return_value->amount ?></td>
            </tr>
        <?php
        }
        ?>
        <tr>

            <td colspan="5" style="text-align:right">Sub Total</td>
            <td><?php echo number_format($medicine_sale_return->total, 3) ?></td>
        </tr>
        <tr>

            <td colspan="5" style="text-align:right">Discount</td>
            <td><?php echo $medicine_sale_return->discount ?></td>
        </tr>
        <tr>
            <td colspan="5" style="text-align:right">Net Total</td>
            <td><?php echo number_format($medicine_sale_return->nettotal, 3) ?></td>
        </tr>
        <tr>
            <td colspan="5" style="text-align:right">Paid</td>
            <td><?php echo number_format($medicine_sale_return->paid, 3) ?></td>
        </tr>
    </table>
    <!-- <p style="text-align: center;margin-top: 100px;">Software developed by Bijoy LAB Web & IT Solution Ltd:01818-650864,www.bijoylab.com</p> -->
</div>