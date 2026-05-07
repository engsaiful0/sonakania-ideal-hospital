
<div class="container-fluid">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Ready Item Stock List</h3>
        </div>
        <div class="panel-body">
            <form id="canteen_ready_item_inventory_form" method="POST" class="form">
                <div class="col-sm-12 col-md-12 main">
                    <div class="table-responsive">
                        <?php
                        $canteen_ready_item_inventory_invoice = $this->db->select('*')->order_by('canteen_ready_item_inventory_invoice_id', 'DESC')->limit('1')->get('canteen_ready_item_inventory_invoice')->row();
                        $canteen_ready_item_inventory_invoice_no = 'CRI' . time() . '0' . intval($canteen_ready_item_inventory_invoice->canteen_ready_item_inventory_invoice_serial) + 1;
                        ?>
                        <input type="hidden" id="idControl" value="1">
                        <input type="hidden" id="current_id" value="1">
                        <table width="100%" border="0" id="cart_tab1">
                            <tr>
                                <th>Serial</th>
                                <th style="text-align: center;">Item</th>
                                <th style="text-align: center;">Quantity</th>
                                <th style="text-align: center;">Unit Price</th>
                                <th style="text-align: center;">Total Amount</th>
                            </tr>
                            <?php
                            $serial = 0;
                            $total_stock_quantity=0;
                            $total_stock_price=0;
                            $total_amount=0;
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
                                $total_stock_quantity+=($total_inventory_quantity-$total_sell_quantity);
                                $total_stock_price+=($total_inventory_quantity-$total_sell_quantity)*$item->price;
                                $total_amount=($total_inventory_quantity-$total_sell_quantity)*$item->price;
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
                                        <input type="hidden" value="<?php echo $item->unit_id ?>" id="unit_id<?php echo  $serial ?>" name="unit_id[]" class="form-control" sequence="<?php echo  $serial ?>">
                                    </td>

                                    <td>
                                        <input readonly  type="number" value="<?php echo $total_inventory_quantity-$total_sell_quantity?>" id="quantity<?php echo  $serial ?>" name="quantity[]" class="form-control quantity" oninput="getamount(this, event)" sequence="<?php echo  $serial ?>">
                                    </td>

                                    <td>
                                        <input  type="number" value="<?php echo $item->price ?>" readonly id="price<?php echo  $serial ?>" name="price[]" class="form-control" oninput="getamount(this, event)" sequence="<?php echo  $serial ?>" required="">
                                    </td>
                                    <td>
                                        <input type="number"  value="<?php echo $total_amount?>" id="total_amount<?php echo  $serial ?>" name="total_amount[]" class="form-control amount" readonly="" sequence="<?php echo  $serial ?>" required="">
                                    </td>

                                </tr>

                            <?php
                                $serial = $serial + 1;
                            } ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>
                                    <input readonly value="<?php echo $total_stock_quantity?>" class="form-control" name="grand_total_quantity" id="grand_total_quantity">
                                </td>
                                <td></td>
                                <td>
                                    <input readonly value="<?php echo $total_stock_price?>" class="form-control" name="grand_total_amount" id="grand_total_amount">
                                </td>
                            </tr>

                        </table>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function validateNumberInput(e) {

        if (isNaN(e.key) && e.key !== '.') {
            e.preventDefault();
        } else if (e.key === '.') {
            if (e.target.value.indexOf('.') >= 0) {
                e.preventDefault();
            }
        }
    }
</script>