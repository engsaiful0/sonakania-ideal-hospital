<script>
    $(document).ready(function() {
        $('#supplier_id').select2();
        $('#payment_type').select2();
        $('#bank_name_id').select2();
        $('#canteen_ready_item_id_0').select2();
        $('#bank_account_id').select2();

    });
    document.addEventListener('keydown', function(event) {
        if (event.shiftKey && event.key === '+') {
            event.preventDefault();
            addMore();
        }
    });



    function getamount(element, e) {
        console.log("Get Amount During Unit Load=");
        var seq = $(element).attr('sequence');
        console.log('seq', seq);
        var price = $("#price" + seq).val();
        console.log('price', price);
        var quantity = $("#quantity" + seq).val();
        console.log('quantity', quantity);
        var amount = price * quantity;
        console.log('amount', amount);
        amount = amount.toFixed(2);
        $("#total_amount" + seq).val(amount);
        totalamount();
    }



    $(document).ready(function() {
        // Validate the form
        $("#canteen_ready_item_inventory_form").validate({
            rules: {
                supplier_id: "required",
                payment_type: "required",
                total_amount: "required",
                paid: "required",
            },
            messages: {
                supplier_id: "Please Select a supplier",
                payment_type: "Please select a payment type",
                total_amount: "Please Enter Total Amount",
                paid: "Paid amount is required",
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {

            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#canteen_ready_item_inventory_form').serialize();

            // Check if the form is valid
            if ($("#canteen_ready_item_inventory_form").valid()) {
                $('#canteen_ready_item_inventory_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('CanteenReadyItemInventoryController/save_canteen_ready_item_inventory'); ?>",
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
                            $('#canteen_ready_item_inventory_form')[0].reset();
                            $('#canteen_ready_item_inventory_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            window.location.href = "<?php echo base_url('print-canteen-ready-item-inventory') ?>";
                        } else {
                            alert('Error: ' + response.message);
                            $('#canteen_ready_item_inventory_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#canteen_ready_item_inventory_form :input').prop('disabled', false);
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

    function dueCal() {
        var due = $("#due").val() * 1;
        var total = $("#total").val() * 1;
        var paid = $("#paid").val() * 1;
        //$("#due").val(total - paid);

        if (paid > total) {
            alert('Paid amount can not be greater thn total amount');
            $('#paid').val(0);
            $('#due').val(0);
        } else {
            var due = total - paid;
            $("#due").val(due.toFixed(2));
        }
    }

    function removetr(element, e) {
        var seq = $(element).attr('sequence');
        if (seq > 0)
            $(element).parent().parent().remove();
        totalamount();
    }

    function totalamount() {
        var amount = $(".amount");
        var quantity = $(".quantity");
        var grandQuantity = 0;

        $.each(quantity, function(k, qty) {
            var qtyValue = $(qty).val();
            qtyValue = Number(qtyValue);
            grandQuantity += qtyValue;
        });

        total = 0;
        total = Number(total);
        $.each(amount, function(k, elm) {
            var camount = $(elm).val();
            camount = Number(camount);
            total += camount;
        });
        $("#grand_total_quantity").val(grandQuantity);
        $("#grand_total_amount").val(total.toFixed(2));

    }
</script>
<div class="container-fluid">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add Daily Inverntory</h3>
        </div>
        <div class="panel-body">
            <form id="canteen_ready_item_inventory_form" method="POST" class="form">
                <div class="col-sm-12 col-md-12 main">
                    <div class="table-responsive">
                        <?php
                        $canteen_ready_item_inventory_invoice = $this->db->select('*')->order_by('canteen_ready_item_inventory_invoice_id', 'DESC')->limit('1')->get('canteen_ready_item_inventory_invoice')->row();
                        $canteen_ready_item_inventory_invoice_no = 'CRI' . time() . '0' . intval($canteen_ready_item_inventory_invoice->canteen_ready_item_inventory_invoice_serial) + 1;
                        ?>
                        <table width="100%" class="table">
                            <tr>
                                <td width="100px;" align="right">
                                    <b>Date</b>
                                </td>
                                <td align="left" style="padding:5px;">
                                    <input type="text" value="<?php echo date('d-m-Y'); ?>" id="date" name="date" class="form-control" readonly="" />
                                </td>
                                <td width="100px;" align="right">
                                    <b>Invoice</b>
                                </td>
                                <td align="left" style="padding:5px;">
                                    <input type="text" id="canteen_ready_item_inventory_invoice_no" name="canteen_ready_item_inventory_invoice_no" class="form-control" readonly="" value="<?php echo $canteen_ready_item_inventory_invoice_no; ?>" />
                                    <input type="hidden" id="canteen_ready_item_inventory_invoice_serial" name="canteen_ready_item_inventory_invoice_serial" class="form-control" readonly="" value="<?php echo $canteen_ready_item_inventory_invoice->canteen_ready_item_inventory_invoice_serial + 1; ?>" />
                                </td>
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
                                <th>Serial</th>
                                <th style="text-align: center;">Item</th>
                                <th style="text-align: center;">Quantity</th>
                                <th style="text-align: center;">Unit Price</th>
                                <th style="text-align: center;">Total Amount</th>
                            </tr>
                            <?php
                            $serial = 0;
                            $items = $this->db->select('*')->order_by('name', 'ASC')->get('canteen_ready_items')->result();
                            foreach ($items as $item) {
                                $this->db->select('unit_id, name');
                                $this->db->from('units');
                                $this->db->where('unit_id', $item->unit_id);
                                $unit = $this->db->get()->row();
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
                                        <input placeholder="Enter Quantity" type="number" value="" id="quantity<?php echo  $serial ?>" name="quantity[]" class="form-control quantity" oninput="getamount(this, event)" sequence="<?php echo  $serial ?>">
                                    </td>

                                    <td>
                                        <input placeholder="Enter Unit Price" type="number" value="" id="price<?php echo  $serial ?>" name="price[]" class="form-control" oninput="getamount(this, event)" sequence="<?php echo  $serial ?>" required="">
                                    </td>
                                    <td>
                                        <input type="number" placeholder="Total Amount" value="" id="total_amount<?php echo  $serial ?>" name="total_amount[]" class="form-control amount" readonly="" sequence="<?php echo  $serial ?>" required="">
                                    </td>

                                </tr>

                            <?php
                                $serial = $serial + 1;
                            } ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>
                                    <input readonly class="form-control" name="grand_total_quantity" id="grand_total_quantity">
                                </td>
                                <td></td>
                                <td>
                                    <input readonly class="form-control" name="grand_total_amount" id="grand_total_amount">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" align="right" style="padding:5px;">
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