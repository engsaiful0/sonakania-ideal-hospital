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

        .p1 {
            line-height: 80% !important;
        }
    }

    .p1 {
        line-height: 80% !important;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <button onclick="window.print()" id="sumbit_button" class="btn btn-primary">Print</button>
    </div>

</div>
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:50px;">

    <?php
    error_reporting(0);
    $print_canteen_ready_item_inventory_id = $this->session->userdata('print_canteen_ready_item_inventory_id');
    $canteen_ready_item_inventory = $this->db->where('canteen_ready_item_inventory_id', $print_canteen_ready_item_inventory_id)
        ->get('canteen_ready_item_inventory')
        ->row();
   
    $canteen_ready_item_inventory_details = $this->db
        ->where('canteen_ready_item_inventory_id', $print_canteen_ready_item_inventory_id)
        ->get('canteen_ready_item_inventory_details')
        ->result();

    $bank = getBankName($canteen_purchase_good->bank_name_id);
    $bank_account = getBankAccountNumber($canteen_purchase_good->bank_account_id);
    ?>
    <?php
    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    ?>

    <div class="customer-copy" style="margin-top: 50px; ">
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
                <img src="<?php echo base_url('CanteenReadyItemInventoryController/set_barcode/' . $canteen_ready_item_inventory->canteen_ready_item_inventory_invoice_no); ?>" alt="Barcode">
            </div>
        </div>
        <div class="name" style="width: 100%;margin-bottom: 10px;">
            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                <tr>
                  
                    <td>Date</td>
                    <td>
                        <b> <?php echo date('d-m-Y', strtotime($canteen_ready_item_inventory->date)) ?></b>
                    </td>
                </tr>
                <tr>
                    <td>Purpose</td>

                    <td>

                        <b><?php echo $canteen_ready_item_inventory->purpose ?></b>
                    </td>
                    <td>Invoice No</td>

                    <td>

                        <b> <?php echo $canteen_ready_item_inventory->canteen_ready_item_inventory_invoice_no ?></b>
                    </td>
                </tr>

            </table>
        </div>
        <div class="product">
            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
                <tr>
                    <td>Sl</td>
                    <td>Item Name</td>
                    <td>Quantity</td>
                    <td>Unit</td>
                    <td>Price</td>
                    <td>Amount</td>
                </tr>
                <?php
                $sl = 1;
                foreach ($canteen_ready_item_inventory_details as $canteen_ready_item_inventory_detail) {
                    $canteen_ready_item_id  = $canteen_ready_item_inventory_detail->canteen_ready_item_id;

                    $canteen_ready_item =getCanteenReadyItem($canteen_ready_item_inventory_detail->canteen_ready_item_id);
                    $unit =getUnit($canteen_ready_item_inventory_detail->unit_id);

                ?>
                    <tr>
                        <td><?php echo $sl++ ?></td>
                        <td><?php echo $canteen_ready_item->name ?></td>
                        <td><?php echo $canteen_ready_item_inventory_detail->quantity ?></td>
                        <td><?php echo $unit->name ?></td>
                        <td><?php echo $canteen_ready_item_inventory_detail->price ?></td>
                      
                        <td><?php echo $canteen_ready_item_inventory_detail->total_amount ?></td>

                    </tr>
                <?php
                }
                ?>
            </table>
            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;margin-top:10px">
                <tr>
                  
                    <td>Total Amount</td>
                    <td><b><?php echo $canteen_ready_item_inventory->grand_total_amount ?></b></td>


                    <td>Total Quantity</td>
                    <td><b><?php echo $canteen_ready_item_inventory->grand_total_quantity ?></b></td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 100px; ">
            <div style="width: 50%;float:left">
                <p style="text-align: left; ">___________<br>Received By</p>
            </div>
            <div style="width: 50%;float:left">
                <p style="text-align: right;">___________<br>Manager</p>
            </div>
        </div>
    </div>


</div>