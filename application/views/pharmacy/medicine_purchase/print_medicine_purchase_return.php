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
    $medicine_purchase_return = '';
    if ($this->session->userdata('print_medicine_purchase_return_id')) {
        $medicine_purchase_return_id = $this->session->userdata('print_medicine_purchase_return_id');
        $medicine_purchase_return = $this->db->where('medicine_purchase_return_id', $medicine_purchase_return_id)
            ->get('medicine_purchase_return')
            ->row();
        // $this->session->unset_userdata('print_medicine_purchase_return_id');
    } else {
        $medicine_purchase_return = $this->db->where('medicine_purchase_return_id', $medicine_purchase_return_id)
            ->get('medicine_purchase_return')
            ->row();
    }
    $medicine_purchase_return = $this->db->where('medicine_purchase_return_id', $medicine_purchase_return->medicine_purchase_return_id)
        ->get('medicine_purchase_return')
        ->row();
    $medicine_purchase = $this->db->where('medicine_purchase_id', $medicine_purchase_return->medicine_purchase_id)
        ->get('medicine_purchase')
        ->row();
    // print_r($medicine_purchase_return_id);
    // print_r($medicine_purchase_return);
    // die;
    $supplier = $this->db->where('supplier_id', $medicine_purchase->supplier_id)->get('supplier')->row();
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
            <img src="<?php echo base_url('MedicinePurchaseReturnController/set_barcode/' . $medicine_purchase_return->medicine_purchase_return_invoice_no); ?>" alt="Barcode">
        </div>
    </div>
    <div class="name" style="width: 100%;margin-bottom: 5px;clear: left">

        <table border="0" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
            <tr>
                <td>Name</td>
                <td>
                    <b><?php echo $supplier->name ?></b>
                </td>
                <td>Invoice No</td>
                <td>
                    <?php echo $medicine_purchase->medicine_purchase_invoice_no ?>
                </td>
                <td>Date</td>
                <td>
                    <?php echo date('d-m-Y', strtotime($medicine_purchase->date)) ?>
                </td>
            </tr>
            <tr>
                <td>Return Invoice No</td>
                <td>
                    <?php echo $medicine_purchase_return->medicine_purchase_return_invoice_no ?>
                </td>
                <td>Return Date</td>
                <td>
                    <?php echo date('d-m-Y', strtotime($medicine_purchase_return->date)) ?>
                </td>
            </tr>
        </table>
    </div>

    <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
        <tr>
            <td>Sl</td>
            <td>Medicine Type</td>
            <td>Medicin</td>
            <td>Purchase Quantity</td>
            <td>Return Quantity</td>
            <td>Purchase Rate</td>
            <td>Total Price</td>
        </tr>
        <?php
        $sl = 1;
        $medicine_purchase_return_details = $this->db
            ->where('medicine_purchase_return_id', $medicine_purchase_return_id)
            ->get('medicine_purchase_return_details')->result();
        $purchase_quantity = 0;
        $return_quantity = 0;
        foreach ($medicine_purchase_return_details as $purchase_detail_return_value) {
            $drug = $this->db
                ->where('drug_id', $purchase_detail_return_value->drug_id)
                ->get('drug')
                ->row();
            $drug_type = $this->db
                ->where('drug_type_id', $purchase_detail_return_value->drug_type_id)
                ->get('drug_type')
                ->row();
            $purchase_quantity += $purchase_detail_return_value->purchase_quantity;
            $return_quantity += $purchase_detail_return_value->return_quantity;
        ?>
            <tr>
                <td><?php echo $sl++ ?></td>
                <td><?php echo $drug_type->type_name ?></td>
                <td><?php echo $drug->drug_name ?></td>
                <td><?php echo $purchase_detail_return_value->purchase_quantity ?></td>
                <td><?php echo $purchase_detail_return_value->return_quantity ?></td>
                <td><?php echo $purchase_detail_return_value->purchase_rate ?></td>
                <td><?php echo $purchase_detail_return_value->amount ?></td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="3" style="text-align:right">Total</td>
            <td><?php echo $purchase_quantity ?></td>
            <td><?php echo $return_quantity ?></td>
            <td></td>
            <td><?php echo number_format($medicine_purchase_return->total, 3) ?></td>
        </tr>

    </table>
    <!-- <p style="text-align: center;margin-top: 100px;">Software developed by Bijoy LAB Web & IT Solution Ltd:01818-650864,www.bijoylab.com</p> -->
</div>