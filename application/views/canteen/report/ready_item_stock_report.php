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
    <table width="100%" border="1" style="border-collapse:collapse " id="cart_tab1">
        <tr style="background-color: #0074B3;color: white  ">
            <td colspan="5" style="text-align: center"><b>Canteen Ready Item Stock Report</b></td>
        </tr>
        <tr>
            <th>Serial</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total Amount</th>
        </tr>
        <?php
        $serial = 0;
        $total_stock_quantity = 0;
        $total_stock_price = 0;
        $total_amount = 0;
        $total_stock_value = 0;
        $items = $this->db->select('*')->order_by('name', 'ASC')->get('canteen_ready_items')->result();
        foreach ($items as $item) {
            $this->db->select('unit_id, name');
            $this->db->from('units');
            $this->db->where('unit_id', $item->unit_id);
            $unit = $this->db->get()->row();

            $this->db->select_sum('quantity');
            $this->db->where('canteen_ready_item_id', $item->canteen_ready_item_id);
            $query = $this->db->get('canteen_ready_item_inventory_details'); // replace 'your_table_name' with the actual table name
            $result = $query->row();
            $total_inventory_quantity = $result->quantity;

            $this->db->select_sum('quantity');
            $this->db->where('canteen_ready_item_id', $item->canteen_ready_item_id);
            $query = $this->db->get('canteen_ready_item_sell_details'); // replace 'your_table_name' with the actual table name
            $result = $query->row();
            $total_sell_quantity = $result->quantity;
            $total_stock_quantity += ($total_inventory_quantity - $total_sell_quantity);
            $total_stock_price += ($total_inventory_quantity - $total_sell_quantity) * $item->price;
            $total_amount = ($total_inventory_quantity - $total_sell_quantity) * $item->price;
        ?>
            <tr>
                <td>
                    <?php
                    echo $serial + 1;
                    ?>
                </td>
                <td>
                    <input type="hidden" value="<?php echo $item->canteen_ready_item_id ?>" id="canteen_ready_item_id<?php echo  $serial ?>" name="canteen_ready_item_id[]" class="form-control" sequence="<?php echo  $serial ?>">
                    <?php echo $item->name . ' (' . $unit->name . ')' ?>

                </td>

                <td>
                    <?php echo $total_inventory_quantity - $total_sell_quantity ?>
                </td>

                <td>
                    <?php echo $item->price ?>
                </td>
                <td>
                    <?php echo $total_amount ?>
                </td>

            </tr>

        <?php
            $serial = $serial + 1;
        } ?>
        <tr>
            <td></td>
            <td style="text-align:right">Total</td>
            <td>
                <?php echo $total_stock_quantity ?>
            </td>
            <td></td>
            <td>
                <?php echo $total_stock_price ?>
            </td>
        </tr>


    </table>


</div>