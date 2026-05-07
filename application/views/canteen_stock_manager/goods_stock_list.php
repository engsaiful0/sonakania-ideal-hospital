
<div class="container-fluid">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Goods Stock List</h3>
        </div>
        <div class="panel-body">
            <form id="canteen_ready_item_inventory_form" method="POST" class="form">
                <div class="col-sm-12 col-md-12 main">
                    <div class="table-responsive">

                        <table width="100%" border="0" id="cart_tab1">
                            <tr>
                                <th style="width:30%;text-align: center;">
                                    Item
                                </th>
                                <th style="width:30%;text-align: center;">
                                    Quantity/Weight Stock
                                </th>
                                <th style="width:30%;text-align: center;">
                                    Unit
                                </th>
                            </tr>
                            <?php
                            $items = $this->db->select('*')->order_by('name', 'ASC')->get('canteen_raw_goods')->result();
                            $id = 0;

                            foreach ($items as $item) {
                                $this->db->select('unit_id, name');
                                $this->db->from('units');
                                $this->db->where('unit_id', $item->unit_id);
                                $unit = $this->db->get()->row();
                                $canteen_raw_goods_id=$item->canteen_raw_goods_id;

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

                                $total_stock=$total_purchase_quantity-$total_quantity_or_weight;
                            ?>
                                <tr>
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
                                </tr>
                            <?php
                            }
                            ?>
                          
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