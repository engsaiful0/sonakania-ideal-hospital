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
    $print_purchase_goods_id = $this->session->userdata('print_purchase_goods_id');
    $purchase_good = $this->db->where('purchase_goods_id', $print_purchase_goods_id)
        ->get('purchase_goods')
        ->row();
    $supplier = $this->db->where('supplier_id', $purchase_good->supplier_id)
        ->get('supplier')
        ->row();
    $purchase_goods_details = $this->db
        ->where('purchase_goods_id', $print_purchase_goods_id)
        ->get('purchase_goods_details')
        ->result();



    $bank = getBankName($purchase_good->bank_name_id);
    $bank_account = getBankAccountNumber($purchase_good->bank_account_id);
    $user = getUserById($purchase_good->user_id);
    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    ?>

    <div class="customer-copy" style="margin-top: 50px; ">
        <div class="" style="width: 100%;margin-bottom: 10px;">
            <div style="width: 15%;float: left;">
                <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
            </div>

            <div style="width: 70%;float: left;margin-bottom: 10px;text-align: center">

                <p style="text-align: center"><span style="text-align: center;font-size: 25px;text-align: center "> <?php echo $compnay->company_name ?><br><?php echo $compnay->address ?></span><br>
                    <span style="text-align: center">
                        Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                    </span>
                </p>
            </div>
            <div style="width: 15%;float: left;">
                <img src="<?php echo base_url('PurchaseGoodsController/set_barcode/' . $purchase_good->purchase_goods_invoice_no); ?>" alt="Barcode">
            </div>
        </div>
        <div class="name" style="width: 100%;margin-bottom: 10px;">
            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                <tr>
                    <td>Supplier</td>

                    <td>
                        <b><?php echo $supplier->name ?></b> </b>
                    </td>
                    <td>Date</td>
                    <td>
                        <b> <?php echo date('d-m-Y', strtotime($purchase_good->date)) ?></b>
                    </td>
                </tr>
                <tr>
                    <td>Purpose</td>

                    <td>

                        <b><?php echo $purchase_good->purpose ?></b>
                    </td>
                    <td>Invoice No</td>

                    <td>

                        <b> <?php echo $purchase_good->purchase_goods_invoice_no ?></b>
                    </td>
                </tr>

            </table>
        </div>
        <div class="product">
            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
                <tr>
                    <td>Sl</td>
                    <td>Item Name</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Amount</td>
                </tr>
                <?php
                $sl = 1;
                foreach ($purchase_goods_details as $purchase_goods_detail) {
                    $purchase_goods_id  = $purchase_goods_detail->purchase_goods_id;

                    $item = getGoodsItem($purchase_goods_detail->item_id);


                ?>
                    <tr>
                        <td><?php echo $sl++ ?></td>
                        <td><?php echo $item->item_name ?></td>
                        <td><?php echo $purchase_goods_detail->price ?></td>
                        <td><?php echo $purchase_goods_detail->quantity ?></td>
                        <td><?php echo $purchase_goods_detail->total_amount ?></td>

                    </tr>
                <?php
                }
                ?>
            </table>
            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;margin-top:10px">
                <tr>
                    <td>Payment Method</td>
                    <td><b><?php echo $purchase_good->payment_type ?></b></td>
                    <td>Total Amount</td>
                    <td><b><?php echo $purchase_good->total ?></b></td>
                    <td>Discount</td>
                    <td><b><?php echo $purchase_good->total_discount ?></b></td>
                    <td>Paid</td>
                    <td><b><?php echo $purchase_good->paid ?></b></td>
                    <td>Due</td>
                    <td><b><?php echo $purchase_good->due ?></b></td>
                </tr>
                <?php
                if ($purchase_good->payment_type == 'Bank') {
                ?>
                    <tr>
                        <td>Bank Name</td>
                        <td><b><?php echo $bank->name ?></b></td>
                        <td>Account Number</td>
                        <td><b><?php echo $bank_account->account_number ?></b></td>
                        <td>Check Number</td>
                        <td><b><?php echo $purchase_good->check_number ?></b></td>
                        <td>Bank Details</td>
                        <td><b><?php echo $purchase_good->bank_details ?></b></td>
                    </tr>
                <?php
                }
                ?>

            </table>

        </div>
        <div style="margin-top: 100px; ">
            <div style="width: 33%;float:left">
                <p style="text-align: center; ">___________<br>Store</p>
            </div>
            <div style="width: 33%;float:left">
                <p style="text-align: center; ">___________<br>Accounts</p>
            </div>
            <div style="width: 33%;float:left">
                <p style="text-align: center;">_____________<br>Approved By</p>
            </div>
        </div>
        <div style="margin-top: 100px; ">
            <div style="width: 30%;float:right">
                <p style="text-align: right;">Entry By: <?php echo  $user->name ?? "" ?></p>
            </div>
        </div>
    </div>


</div>