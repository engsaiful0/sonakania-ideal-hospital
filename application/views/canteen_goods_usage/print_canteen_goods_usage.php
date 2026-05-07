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
    $print_canteen_goods_usage_id = $this->session->userdata('print_canteen_goods_usage_id');
    $canteen_goods_usage = $this->db->where('canteen_goods_usage_id', $print_canteen_goods_usage_id)
        ->get('canteen_goods_usage')
        ->row();

    $canteen_goods_usage_details = $this->db
        ->select('canteen_goods_usage_details.*, units.name as unit_name')
        ->from('canteen_goods_usage_details')
        ->join('canteen_raw_goods', 'canteen_goods_usage_details.canteen_raw_goods_id = canteen_raw_goods.canteen_raw_goods_id', 'left')
        ->join('units', 'canteen_raw_goods.unit_id = units.unit_id', 'left')
        ->where('canteen_goods_usage_details.canteen_goods_usage_id', $print_canteen_goods_usage_id)
        ->get()
        ->result();


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
                <img src="<?php echo base_url('CanteenGoodsUsageController/set_barcode/' . $canteen_goods_usage->canteen_goods_usage_invoice_no); ?>" alt="Barcode">
            </div>
        </div>
        <div class="name" style="width: 100%;margin-bottom: 10px;">
            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                <tr>

                    <td>Date</td>
                    <td>
                        <b> <?php echo date('d-m-Y', strtotime($canteen_goods_usage->date)) ?></b>
                    </td>
                </tr>
                <tr>
                    <td>Purpose</td>

                    <td>

                        <b><?php echo $canteen_goods_usage->purpose ?></b>
                    </td>
                    <td>Invoice No</td>

                    <td>

                        <b> <?php echo $canteen_goods_usage->canteen_goods_usage_invoice_no ?></b>
                    </td>
                </tr>

            </table>
        </div>
        <div class="product">
            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
                <tr>
                    <td>Sl</td>
                    <td>Goods Name</td>
                    <td>Quantity/Weight</td>
                    <td>Unit</td>
                </tr>
                <?php
                // echo '<pre>';
                // print_r($canteen_goods_usage_details);
                // die;
                $sl = 1;
                foreach ($canteen_goods_usage_details as $canteen_goods_usage_item) {
                    $canteen_raw_goods_id  = $canteen_goods_usage_item->canteen_raw_goods_id;
                    // print_r($canteen_raw_goods_id);
                    $canteen_goods = getCanteenGoods($canteen_raw_goods_id);
                    // print_r($canteen_goods);
                ?>

                    <tr>
                        <td><?php echo $sl++; ?></td>
                        <td><?php echo $canteen_goods->name; ?></td>
                        <td><?php echo $canteen_goods_usage_item->quantity_or_weight; ?></td>
                        <td><?php echo $canteen_goods_usage_item->unit_name; ?></td>
                    </tr>

                <?php
                }
                ?>

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