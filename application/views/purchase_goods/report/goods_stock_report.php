<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #sumbit_button
        {
            display: none;
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


<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Goods Stock</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <button onclick="window.print()" id="sumbit_button" class="btn btn-primary">Print</button>
                </div>
            </div>
            <div class="panel-heading" style="font-weight: bold;text-align: center;">
                <?php
                $this->load->view('common/report_header');
                ?>
                <p style="clear:left;text-align: center">Goods Stock. Date:<b><?php echo date('d-m-Y') ?></b> <br>
                    <span style="text-align: center;font-weight:bold"></span>
                </p>
            </div>
            <table border="1" class="table table-bordered table-hover" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;margin-top:10px">
                <thead>
                    <th>#</th>
                    <th>Item</th>
                    <th>Purchase Quantity</th>
                    <th>Issue Quantity</th>
                    <th>Stock</th>
                </thead>
                <?php
                $items = $this->db->select('*')->order_by('item_name', 'ASC')->get('item')->result();
                $serial = 1;
                $total_purchase_quanity = 0;
                $total_issue_quanity = 0;
                $total_stock = 0;
                foreach ($items as $item) {

                    $item_purchase_quantity = $this->db->select_sum('quantity')
                        ->where('item_id', $item->item_id)
                        ->get('purchase_goods_details')
                        ->row()
                        ->quantity;
                    $total_purchase_quanity += $item_purchase_quantity;

                    $item_issue_quantity = $this->db->select_sum('issue_quantity')
                        ->where('item_id', $item->item_id)
                        ->get('issue_details')
                        ->row()
                        ->issue_quantity;
                    $total_issue_quanity += $item_issue_quantity;

                    $item = getGoodsItem($item->item_id);
                ?>
                    <tr>
                        <td><?php echo $serial++ ?></td>
                        <td>
                            <?php echo $item->item_name ?>
                        </td>
                        <td>
                            <?php echo $item_purchase_quantity ?>
                        </td>
                        <td>
                            <?php echo $item_issue_quantity ?>
                        </td>
                        <td>
                            <?php echo $item_purchase_quantity - $item_issue_quantity;
                            $total_stock = $total_stock + ($item_purchase_quantity - $item_issue_quantity);
                            ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="2" style="text-align:right">Total</td>
                    <td><?php echo $total_purchase_quanity ?></td>
                    <td><?php echo $total_issue_quanity ?></td>
                    <td><?php echo $total_stock ?></td>
                </tr>
            </table>

        </div>
    </div>
</div>