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
            <td colspan="6" style="text-align: center"><b>Canteen Goods Stock Report</b></td>
        </tr>
        <tr>
            <th>Sl</th>
            <th>
                Item
            </th>
            <th>
                Quantity/Weight Stock
            </th>
            <th>
                Unit
            </th>
            <th>
                Price
            </th>
            <th>
                Stcok Value
            </th>
        </tr>
        <?php
        $items = $this->db->select('*')->order_by('name', 'ASC')->get('canteen_raw_goods')->result();
        $total_stock_value = 0;
        $id = 0;
        $serial = 1;

        foreach ($items as $item) {
            $this->db->select('unit_id, name');
            $this->db->from('units');
            $this->db->where('unit_id', $item->unit_id);
            $unit = $this->db->get()->row();
            $canteen_raw_goods_id = $item->canteen_raw_goods_id;

            $this->db->select_sum('quantity');
            $this->db->where('canteen_raw_goods_id', $canteen_raw_goods_id);
            $query = $this->db->get('canteen_purchase_goods_details'); // replace 'your_table_name' with the actual table name
            $result = $query->row();
            $total_purchase_quantity = $result->quantity;

            $this->db->select_sum('quantity_or_weight');
            $this->db->where('canteen_raw_goods_id', $canteen_raw_goods_id);
            $query = $this->db->get('canteen_goods_usage_details'); // replace 'your_table_name' with the actual table name
            $result = $query->row();
            $total_quantity_or_weight = $result->quantity_or_weight;

            $total_stock = $total_purchase_quantity - $total_quantity_or_weight;
        ?>
            <tr>
                <td>
                    <?php echo  $serial++; ?>
                </td>
                <td>
                    <?php echo $item->name ?>
                    <input type="hidden" value="<?php echo $item->canteen_raw_goods_id ?>" id="canteen_raw_goods_id0" name="canteen_raw_goods_id[]" class="form-control" sequence=0>
                </td>
                <td>
                    <?php echo $total_stock ?>
                </td>
                <td>
                    <?php echo $unit->name ?>
                </td>
                <td>
                    <?php echo $item->price ?>
                </td>
                <td>
                    <?php echo $item->price * $total_stock;
                    $total_stock_value += $item->price * $total_stock;
                    ?>
                </td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Total</td>
            <td><?php echo $total_stock_value; ?></td>
        </tr>

    </table>


</div>