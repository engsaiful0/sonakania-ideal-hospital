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
    //error_reporting(0);
    $expired_medicine = '';

    if ($this->session->userdata('print_expired_medicine_id')) {
        $expired_medicine_id = $this->session->userdata('print_expired_medicine_id');
        $expired_medicine = $this->db->where('expired_medicine_id', $expired_medicine_id)
            ->get('expired_medicines')
            ->row();
        $this->session->unset_userdata('print_expired_medicine_id');
    } else {
        $expired_medicine = $this->db->where('expired_medicine_id', $expired_medicine_id)
            ->get('expired_medicines')
            ->row();
    }

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
            <img src="<?php echo base_url('ExpiredMedicineController/set_barcode/' . $expired_medicine->expired_medicine_invoice_no); ?>" alt="Barcode">
        </div>
    </div>
    <div class="name" style="width: 100%;margin-bottom: 5px;clear: left">
        <table border="0" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">

            <tr>
                <td>Expired No</td>
                <td>
                    <?php echo $expired_medicine->expired_medicine_invoice_no ?>
                </td>
                <td>Date</td>
                <td>
                    <?php echo date('d-m-Y', strtotime($expired_medicine->date)) ?>
                </td>
                <td>Remarks</td>
                <td>
                    <?php echo $expired_medicine->remarks ?>
                </td>
            </tr>

        </table>
    </div>

    <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
        <tr>
            <td>Sl</td>
            <td>Medicine</td>
            <td>MRP</td>
            <td>Purchase Rate</td>
            <td>Quantity</td>
            <td>Amount</td>
        </tr>
        <?php
        $sl = 1;
        $expired_medicine_details = $this->db
            ->where('expired_medicine_id', $expired_medicine_id)
            ->get('expired_medicine_details')->result();

        foreach ($expired_medicine_details as $expired_medicine_value) {
            
            $drug_name = $this->db
                ->where('drug_id', $expired_medicine_value->drug_id)
                ->get('drug')->row();
        ?>
            <tr>
                <td><?php echo $sl++ ?></td>
                
                <td><?php echo $drug_name->drug_name ?></td>
                <td><?php echo $expired_medicine_value->mrp_rate ?></td>
                <td><?php echo $expired_medicine_value->purchase_rate ?></td>
                <td><?php echo $expired_medicine_value->quantity ?></td>
                <td><?php echo $expired_medicine_value->amount ?></td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="5" style="text-align:right">Total</td>
            <td><?php echo number_format($expired_medicine->total, 3) ?></td>
        </tr>


    </table>

</div>