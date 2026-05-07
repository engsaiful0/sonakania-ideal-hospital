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

<div class="row hide-print">
    <div class="col-md-12">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
    </div>
</div>

<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;margin-top:40px;color:black;height: 20cm ">
    <?php
    error_reporting(0);
    $medicine_purchase = '';
    if ($this->session->userdata('print_medicine_purchase_id')) {
        $medicine_purchase_id = $this->session->userdata('print_medicine_purchase_id');
        $medicine_purchase = $this->db->where('medicine_purchase_id', $medicine_purchase_id)
            ->get('medicine_purchase')
            ->row();
    } else {
        $medicine_purchase = $this->db->where('medicine_purchase_id', $medicine_purchase_id)
            ->get('medicine_purchase')
            ->row();
    }

    $supplier = $this->db->where('supplier_id', $medicine_purchase->supplier_id)->get('supplier')->row();
    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    $user = getUserById($medicine_purchase->user_id);
    ?>

    <div style="width: 100%;margin-bottom: 10px;">
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
            <td style="font-weight: bold;">Remarks</td>
            <td><b><?= $medicine_purchase->remarks ?></b></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Invoice No</td>
            <td><?= $medicine_purchase->medicine_purchase_invoice_no ?></td>
            <td style="font-weight: bold;">Date</td>
            <td><?= date('d-m-Y', strtotime($medicine_purchase->date)) ?></td>
            <td style="font-weight: bold;">Purchase Type</td>
            <td><b><?= ucfirst($medicine_purchase->purchase_type) ?></b></td>
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
            ->where('medicine_purchase_id', $medicine_purchase_id)
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