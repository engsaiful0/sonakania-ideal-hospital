<script>
    $(document).ready(function() {
        $('#canteen_goods_supplier_id').select2();
        $('#payment_type').select2();
        $('#bank_name_id').select2();
        $('#canteen_raw_goods_id_0').select2();
        $('#bank_account_id').select2();

    });
    document.addEventListener('keydown', function(event) {
        if (event.shiftKey && event.key === '+') {
            event.preventDefault();
            addMore();
        }
    });



    $(document).ready(function() {
        // Custom validation method to check at least one field is filled in array
        $.validator.addMethod("checkQuantityOrWeight", function(value, element) {
            let isFilled = false;
            $("input[name='quantity_or_weight[]']").each(function() {
                if ($(this).val() !== "") {
                    isFilled = true;
                    return false; // Break out of the loop if at least one input is filled
                }
            });
            return isFilled;
        }, "Please enter at least one quantity or weight.");

        $("#canteen_goods_usage_form").validate({
            rules: {
                "quantity_or_weight[]": {
                    checkQuantityOrWeight: true // Use the custom validation rule here
                },
                date: "required"
            },
            messages: {
                "quantity_or_weight[]": {
                    checkQuantityOrWeight: "Please enter at least one quantity or weight."
                },
                date: "Date is required"
            }
        });


        // On form submission
        $('#submit_button').click(function(e) {

            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#canteen_goods_usage_form').serialize();

            // Check if the form is valid
            if ($("#canteen_goods_usage_form").valid()) {
                $('#canteen_goods_usage_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('CanteenGoodsUsageController/save_canteen_goods_usage'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $.toast({
                                heading: 'Success',
                                text: 'Data has been saved successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });
                            $('#canteen_goods_usage_form')[0].reset();
                            $('#canteen_goods_usage_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            window.location.href = "<?php echo base_url('print-canteen-goods-usage') ?>";
                        } else {
                            alert('Error: ' + response.message);
                            $('#canteen_goods_usage_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#canteen_goods_usage_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).keypress(function(e) {
        if (e.which == 13) {
            //alert('enter key is pressed');
            var paid = $('#paid').val();
            // alert(paid);
            if (paid > 0) {
                $("#submit").click();
            } else if (paid == 0) {
                $("#add_more").click();
            }
        }
    });

    function stock_check(id) {
        var parts = id.split('_');
        var main_id = parts[3];
        console.log("main_id=", main_id);
        var quantity_or_weight = document.getElementById('quantity_or_weight_' + main_id).value;

        $.ajax({
            url: "<?php echo base_url('CanteenGoodsUsageController/stock_check'); ?>", // Update with your actual controller name
            type: "POST",
            data: {
                id: main_id,
                quantity_or_weight: quantity_or_weight,
            },
            success: function(response) {
                var data = JSON.parse(response);
                var messageElement = $("#stock-message-" + main_id); // Adjust selector to target your message area

                if (data.status === "false") {
                    // Display the "Stock not available" message
                    messageElement.text("Stock not available").css("color", "red");
                    document.getElementById('quantity_or_weight_' + main_id).value = '';
                    document.getElementById('quantity_or_weight_' + main_id).focus;
                } else {
                    // Clear any previous message if stock is available
                    messageElement.text("");
                }
            },
            error: function(xhr, status, error) {
                console.log("Error:", error);
            }
        });
    }
</script>
<div class="container-fluid">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add Goods Usage</h3>
        </div>
        <div class="panel-body">
            <form id="canteen_goods_usage_form" method="POST" class="form">
                <div class="col-sm-12 col-md-12 main">
                    <div class="table-responsive">
                        <table width="100%" class="table">
                            <tr>
                                <td width="100px;" align="right">
                                    <b>Invoice No:</b>
                                </td>
                                <?php
                                $canteen_goods_usage_invoice = $this->db->select('*')
                                    ->order_by('canteen_goods_usage_invoice_id', 'DESC')
                                    ->limit(1)
                                    ->get('canteen_goods_usage_invoice')
                                    ->row();

                                // Check if a record was found, and if not, set a default value
                                $invoice_serial = $canteen_goods_usage_invoice && isset($canteen_goods_usage_invoice->canteen_goods_usage_invoice_serial)
                                    ? intval($canteen_goods_usage_invoice->canteen_goods_usage_invoice_serial)
                                    : 0;

                                // Generate the invoice number
                                $canteen_goods_usage_invoice_no = 'GU' . time() . '0' . ($invoice_serial + 1);
                                ?>

                                <td align="left" style="padding:5px;width: 400px;">
                                    <input type="text" id="canteen_goods_usage_invoice_no" name="canteen_goods_usage_invoice_no" class="form-control" readonly="" value="<?php echo $canteen_goods_usage_invoice_no; ?>" />
                                    <input type="hidden" id="canteen_goods_usage_invoice_serial" name="canteen_goods_usage_invoice_serial" class="form-control" readonly="" value="<?php echo $invoice_serial + 1; ?>" />
                                </td>
                                <td width="100px;" align="right">
                                    <b>Date</b>
                                </td>
                                <td align="left" style="padding:5px;">
                                    <input type="text" value="<?php echo date('d-m-Y'); ?>" id="date" name="date" class="form-control" readonly="" />
                                </td>
                            </tr>
                            <tr>
                                <td width="100px;" colspan="" align="right"><b>Purpose</b></td>
                                <td align="left" style="padding:5px;">
                                    <textarea placeholder="Enter Purpose" type="text" id="purpose" name="purpose" class="form-control"></textarea>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" id="idControl" value="1">
                        <input type="hidden" id="current_id" value="1">
                        <table width="100%" border="0" id="cart_tab1">
                            <tr>
                                <th style="width:100px;text-align: center;">
                                    Item
                                </th>
                                <th style="text-align: center;">
                                    Quantity/Weight
                                </th>
                                <th style="text-align: center;">
                                    Unit Price
                                </th>
                                <th style="text-align: center;">
                                    Available Stock
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
                                        <?php echo $item->name . ' (' . $unit->name . ')' ?>
                                        <input type="hidden" value="<?php echo $item->canteen_raw_goods_id ?>" id="canteen_raw_goods_id<?php echo $id ?>" name="canteen_raw_goods_id[]" class="form-control" sequence=0>
                                    </td>
                                    <td>
                                        <input onblur="stock_check(this.id)" onkeydown="validateNumberInput(event)" placeholder="Enter Quantity/Weight" type="text" value="" id="quantity_or_weight_<?php echo $canteen_raw_goods_id ?>" name="quantity_or_weight[]" class="form-control">
                                        <span id="stock-message-<?php echo $canteen_raw_goods_id ?>"></span>
                                    </td>
                                    <td>
                                        <input onblur="stock_check(this.id)" onkeydown="validateNumberInput(event)" placeholder="Enter Quantity/Weight" type="text" value="" id="quantity_or_weight_<?php echo $canteen_raw_goods_id ?>" name="quantity_or_weight[]" class="form-control">
                                        <span id="stock-message-<?php echo $canteen_raw_goods_id ?>"></span>
                                    </td>
                                    <td>
                                        <input type="text" value="<?php echo  $total_stock ?>" readonly name="avilable_stcok[]" class="form-control">

                                    </td>
                                </tr>
                            <?php
                                $id = $id + 1;
                            }
                            ?>

                            <tr>
                                <td></td>
                                <td>
                                    <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Submit</button>
                                </td>
                            </tr>


                        </table>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>