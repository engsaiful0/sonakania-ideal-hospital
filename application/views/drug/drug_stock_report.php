<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">View Medicine Stock </h3>
    </div>
    <div class="panel-body" style="width: 100%;">
        <script>
            $(document).ready(function() {
                $("#drug_name").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('DrugController/drug_name_load'); ?>",
                            data: {
                                parameter: request.term
                            },
                            dataType: "json",
                            type: "POST",
                            success: function(data) {
                                response(data);
                            }
                        });
                    },
                    select: function(event, ui) {
                        $('#drug_name').val(ui.item.label);
                        $('form').submit(); // Automatically submit the form
                        return false;
                    }
                });
                $("#manufacturer_id").select2();
                $("#drug_id").select2();
             
            });
        </script>
        <form method="post" action="<?php echo base_url() . "index.php/DrugController/drug_stock_report"; ?>">
            <table style="margin-top:20px; " class="table table-bordered">
                <tr>
                    <td style="width:20%">Search by Medicine Name</td>
                    <td style="width:20%;display:none">Supplier</td>
                    <td style="width:20%;display:none">Type</td>
                    <td  style="width:30%;display:none">Medicine</td>

                </tr>
                <tr>
                    <td>
                        <input placeholder="Type drug name.." autofocus type="text" class="form-control" name="drug_name" id="drug_name">
                    </td>
                    <td style="display: none;">
                        <select class="form-control" id="manufacturer_id" name="manufacturer_id">
                            <?php
                            $man = $this->db->select('*')->order_by('name', 'ASC')->get('manufacturer')->result();
                            ?>
                            <option value="">All</option>
                            <?php
                            foreach ($man as $value) {
                            ?>
                                <option value="<?php echo $value->manufacturer_id ?>"><?php echo $value->name ?></option>
                            <?php
                            }
                            ?>

                        </select>
                    </td>
                    <td style="display: none;">
                        <select name="drug_type_id" class="form-control" id="drug_type_id" sequence=0 onchange="drug_name_load(this.id)" style="width:200px;">
                            <option value="" selected="">Select Type</option>
                            <?php
                            $sql = $this->db->select('*')->order_by('type_name', 'ASC')->get('drug_type')->result();

                            foreach ($sql as $value) {
                            ?>
                                <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td style="display: none;">
                        <select class="form-control" id="drug_id" style="width: 300px;" name="drug_id">

                            <option></option>
                            <?php

                            ?>

                        </select>
                    </td>
                    <td>
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </td>
                </tr>
            </table>


        </form>

        <table border="1" class="table table-bordered table-striped ">

            <tr>
                <td>Sl</td>
                <td style="display: none;">Manufacturer</td>
                <td>Drug Name</td>
                <td style="display: none;">Type</td>
                <td>Purchase Rate</td>
                <td>MRP</td>
                <td>Stock</td>
                <td style="display: none;">Shelf No</td>
                <td>Status</td>
            </tr>

            <?php
            error_reporting(0);
            $k = 1;
            for ($i = 0; $i < count($detailsList); ++$i) {
                $manufacturer = getManufacturer($detailsList[$i]->manufacturer_id);
                $sql_type = getDrugType($detailsList[$i]->drug_type_id);
                $drug = getDrug($detailsList[$i]->drug_id);
                $shelf = getShelf($detailsList[$i]->shelf_id);
                $drug_id = $detailsList[$i]->drug_id;
            ?>
                <tr>
                    <td><?php echo $k++; ?></td>
                    <td style="display: none;"><?php echo $manufacturer->name ?></td>
                    <td><?php echo $drug->drug_name; ?></td>
                    <td style="display: none;"><?php echo $sql_type->type_name ?></td>
                    <td><?php echo $detailsList[$i]->purchase_rate; ?></td>
                    <td><?php echo $detailsList[$i]->mrp; ?></td>
                    <td><?php echo getStock($detailsList[$i]->drug_id); ?></td>
                    <td style="display: none;"><?php echo $shelf->shelf_number; ?></td>
                    <td><?php echo $detailsList[$i]->status; ?></td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>

<div style="width:70%;margin:0 auto;text-align:center">
    <p><?php echo $pagination; ?></p>
</div>