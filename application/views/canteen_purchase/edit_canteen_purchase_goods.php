<script>
    document.addEventListener('keydown', function(event) {
        if (event.shiftKey && event.key === '+') {
            event.preventDefault();
            addMore();
        }
    });

    function load_account_number(bank_name_id) {
        $('#img').show();

        // alert(id_no[2]);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("bank_account_id").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('DebitVoucherController/load_account_number'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("bank_name_id=" + bank_name_id);
    }

    function getamount(element, e) {
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

    function show_bank(type) {
        if (type == 'Bank') {
            document.getElementById('bank_container').style.display = "block";
        } else {
            document.getElementById('bank_container').style.display = "none";
        }
    }

    $(document).ready(function() {
        // Validate the form
        $("#purchase_goods_entry_form").validate({
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
            var formData = $('#purchase_goods_entry_form').serialize();
            // Check if the form is valid
            if ($("#purchase_goods_entry_form").valid()) {
                $('#purchase_goods_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('CanteenPurchaseController/update_purchase_goods_data'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $.toast({
                                heading: 'Success',
                                text: 'Data has been updated successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });
                            $('#purchase_goods_entry_form')[0].reset();
                            $('#purchase_goods_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            window.location.href = "<?php echo base_url('print-canteen-purchase-goods') ?>";
                        } else {
                            alert('Error: ' + response.message);
                            $('#purchase_goods_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#purchase_goods_entry_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
</script>
<script type="text/javascript">
    function addMore() {
        $('#img').show();
        var id_control = document.getElementById('idControl').value * 1;
        var next_id = id_control + 1;
        document.getElementById('idControl').value = next_id;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var newRow = document.createElement('tr');
                newRow.innerHTML = xhttp.responseText;
                document.getElementById('cart_tab1').appendChild(newRow);

                // Initialize select2 for the new dropdown
                $("#canteen_raw_goods_id_" + next_id).select2({
                    placeholder: "Select Item",
                    allowClear: true
                });
                $("#canteen_raw_goods_id_" + next_id).focus();
                $('#img').hide();
            }
        }
        xhttp.open("POST", "<?php echo site_url('CanteenPurchaseController/more_purchase_canteen_goods_row'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send('next_id=' + next_id);
    }
</script>
<script type="text/javascript">
    function resetFn() {
        window.location.href = "";
    }
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
        total = 0;
        total = Number(total);
        $.each(amount, function(k, elm) {
            var camount = $(elm).val();
            camount = Number(camount);
            total += camount;
        });
        $("#total").val(total.toFixed(2));
        $("#paid").val(total.toFixed(2));
    }

    function price_select(id) {
        var data = id.split("_");
        console.log('data[2]', data[2]); // Debugging line to show data split
        setTimeout(() => {
            $('#price' + data[2]).focus();
        }, 50);
    }
</script>
<div class="container-fluid">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Eidt Goods Purchase</h3>
        </div>
        <div class="panel-body">
            <form id="purchase_goods_entry_form" method="POST" class="form">
                <div class="col-sm-12 col-md-12 main">
                    <div class="table-responsive">
                        <input name="canteen_purchase_good_id" type="hidden" value="<?php echo $canteen_purchase_good_id ?>">
                        <?php
                        $canteen_purchase_goods = $this->db->where('canteen_purchase_good_id', $canteen_purchase_good_id)->get('canteen_purchase_goods')->row();
                        ?>

                        <table width="100%" class="table">
                            <tr>
                                <td width="100px;" align="right">
                                    <b>Invoice No:</b>
                                </td>

                                <td align="left" style="padding:5px;width: 400px;">
                                    <input type="text" id="purchase_canteen_goods_invoice_no" name="purchase_canteen_goods_invoice_no" class="form-control" readonly="" value="<?php echo $canteen_purchase_goods->purchase_canteen_goods_invoice_no ?>" />

                                </td>
                                <td width="100px;" align="right">
                                    <b>Date</b>
                                </td>
                                <td align="left" style="padding:5px;">
                                    <input type="text" value="<?php echo date('d-m-Y', strtotime($canteen_purchase_goods->date)); ?>" id="date" name="date" class="form-control" readonly="" />
                                </td>
                            </tr>
                            <tr>
                                <td width="100px;" align="right">
                                    <b>Supplier:</b>
                                </td>
                                <td align="left" style="padding:5px;">
                                    <select name="canteen_goods_supplier_id" id="canteen_goods_supplier_id" class="form-control" required="">
                                        <option value="">Supplier</option>
                                        <?php
                                        $sql = $this->db->select('*')->get('supplier')->result();

                                        foreach ($sql as $value) {
                                        ?>
                                            <option <?php echo $canteen_purchase_goods->canteen_goods_supplier_id == $value->supplier_id ? "selected" : "" ?> value="<?php echo $value->supplier_id ?>"><?php echo $value->name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td width="100px;" colspan="" align="right"><b>Purpose</b></td>
                                <td align="left" style="padding:5px;">
                                    <textarea placeholder="Enter Purpose" type="text" id="purpose" name="purpose" class="form-control"><?php echo $canteen_purchase_goods->purpose ?></textarea>
                                </td>
                            </tr>
                        </table>

                        <input type="hidden" id="current_id" value="1">
                        <table width="100%" border="0" id="cart_tab1">
                            <tr>
                                <th style="width:100px;text-align: center;">
                                    Item
                                </th>
                                <th style="text-align: center;">
                                    Quantity
                                </th>
                                <th style="width:185px;text-align: center;">
                                    Unit
                                </th>
                                <th style="width:185px;text-align: center;">
                                    Unit Price
                                </th>

                                <th style="text-align: center;">
                                    Total Amount
                                </th>
                                <th style="text-align: center;">

                                </th>
                            </tr>
                            <?php
                            $canteen_purchase_goods_details = $this->db->where('canteen_purchase_good_id', $canteen_purchase_good_id)->get('canteen_purchase_goods_details')->result();
                            $id = 0;
                            ?>
                            <input type="hidden" value="<?php echo count($canteen_purchase_goods_details) ?>" name="idControl" id="idControl" class="form-control">
                            <?php

                            foreach ($canteen_purchase_goods_details as $purchase_goods_value) {
                                $unit = $this->db->where('unit_id', $purchase_goods_value->unit_id)->get('units')->row();
                            ?>
                                <tr>
                                    <td>
                                        <select name="canteen_raw_goods_id[]" onchange="unit_load(this.id)" class="form-control" id="canteen_raw_goods_id_<?php echo $id ?>" sequence=0 required style="width:200px;">
                                            <option value="" selected>Select Item</option>
                                            <?php
                                            $items = $this->db->select('*')->order_by('name', 'ASC')->get('canteen_raw_goods')->result();

                                            foreach ($items as $item) {
                                            ?>
                                                <option <?php echo $purchase_goods_value->canteen_raw_goods_id == $item->canteen_raw_goods_id ? "selected" : "" ?> value="<?php echo $item->canteen_raw_goods_id ?>"><?php echo $item->name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>

                                    <td>
                                        <input onkeydown="validateNumberInput(event)" placeholder="Enter Unit Price" type="text" value="<?php echo $purchase_goods_value->price ?>" id="price0" name="price[]" class="form-control" oninput="getamount(this, event)" sequence=0 required="">
                                    </td>
                                    <td>
                                        <input readonly type="text" id="unit_name0" name="unit_name[]" value="<?php echo $unit->name ?>" class="form-control" sequence=0 required="">
                                        <input type="hidden" id="unit_id0" name="unit_id[]" class="form-control" value="<?php echo $unit->unit_id ?>" sequence=0 required="">
                                    </td>

                                    <td>
                                        <input onkeydown="validateNumberInput(event)" placeholder="Enter Quantity" type="text" value="<?php echo $purchase_goods_value->quantity ?>" id="quantity0" name="quantity[]" class="form-control" oninput="getamount(this, event)" sequence=0>
                                    </td>

                                    <td>
                                        <input type="text" placeholder="Total Amount" value="<?php echo $purchase_goods_value->total_amount ?>" id="total_amount0" name="total_amount[]" class="form-control amount" readonly="" sequence=0 required="">
                                    </td>
                                    <?php
                                    if ($id == 0) {
                                    ?>
                                        <td>
                                            <button type="button" onclick="addMore()" id="add_more" class="btn btn-sm btn-default">
                                                <i class="glyphicon glyphicon-plus"></i>
                                            </button>
                                        </td>
                                    <?php
                                    } else {
                                    ?>
                                        <td>
                                            <button class="btn btn-danger  btn-xs remove" type="button" onclick="removetr(this, event)"><i class="glyphicon glyphicon-remove"></i></button>
                                        </td>
                                    <?php
                                    }
                                    ?>
                                </tr>
                            <?php
                                $id++;
                            }
                            ?>

                        </table>
                        <table width="100%" class="table">
                            <tr>
                                <td colspan="7" align="right" valign="middle" style="padding:5px;">
                                    <b>Total:&nbsp;</b>
                                    <input type="text" name="total" value="<?php echo $canteen_purchase_goods->total ?>" id="total" readonly="" class="form-control" style="width:200px;float: right;">
                                </td>
                            </tr>

                            <tr>
                                <td colspan="7" align="right" valign="middle" style="padding:5px;">
                                    <b>Payment Type:&nbsp;</b>
                                    <select onchange="show_bank(this.value)" type="text" name="payment_type" required="" id="payment_type" class="form-control" style="width:200px;float: right;">
                                        <option selected value="" disabled>Select Payment Type</option>
                                        <option <?php echo $canteen_purchase_goods->payment_type == 'Cash' ? "selected" : "" ?>>Cash</option>
                                        <option <?php echo $canteen_purchase_goods->payment_type == 'Bank' ? "selected" : "" ?>>Bank</option>
                                    </select>
                                </td>
                            </tr>
                            <?php
                            $display = 'none';
                            if ($canteen_purchase_goods->payment_type == 'Bank') {
                                $display = 'block';
                            }
                            ?>
                            <tr id="bank_container" style="display:<?php echo $display ?>">
                                <td style="width:25%">
                                    <select id="bank_name_id" name="bank_name_id" onchange="load_account_number(this.value)" style="width:100% ;" class="form-control">
                                        <option value="">Select Bank</option>
                                        <?php
                                        $banks = $this->db->where('status', 'active')->order_by('name', 'ASC')->get('bank_name')->result();
                                        foreach ($banks as $bank) {
                                        ?>
                                            <option <?php echo $canteen_purchase_goods->bank_name_id == $bank->bank_name_id ? "selected" : "" ?> value="<?php echo $bank->bank_name_id ?>"><?php echo $bank->name ?></option>

                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td style="width:25%">
                                    <select id="bank_account_id" name="bank_account_id" style="width:100% ;" class="form-control">
                                        <?php
                                        $bank_account = $this->db->where('bank_account_id', $canteen_purchase_goods->bank_account_id)->get('bank_accounts')->row();

                                        ?>
                                        <option <?php echo $canteen_purchase_goods->bank_account_id == $bank_account->bank_account_id ? "selected" : "" ?> value="<?php echo $bank_account->bank_account_id ?>"><?php echo $bank_account->account_number ?></option>


                                    </select>
                                </td>
                                <td style="width:25%">
                                    <input type="text" value="<?php echo $canteen_purchase_goods->check_number ?>" class="form-control" placeholder="Enter Check Number" id="check_number" name="check_number">
                                </td>

                                <td style="width:25%">
                                    <input type="text" style="width:300px" class="form-control" value="<?php echo $canteen_purchase_goods->bank_details ?>" placeholder="Enter Bank Details" id="bank_details" name="bank_details">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" align="right" valign="middle" style="padding:5px;">
                                    <b>Paid:&nbsp;</b>
                                    <input type="text" name="paid" value="<?php echo $canteen_purchase_goods->paid ?>" required="" oninput="dueCal()" id="paid" class="form-control" style="width:200px;float: right;">
                                </td>
                            </tr>

                            <tr>
                                <td colspan="7" align="right" valign="middle" style="padding:5px;">
                                    <b>Due:&nbsp;</b>
                                    <input type="text" name="due" id="due" value="<?php echo $canteen_purchase_goods->due ?>" readonly="" class="form-control" style="width:200px;float: right;">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" align="right">

                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" align="right" style="padding:5px;">
                                    <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Update</button>
                                    <button onclick="resetFn()" type="submit" name="submit_button" id="submit_button" class="btn btn-success">Reset</button>

                                </td>
                            </tr>
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
                                </div>
                            </div>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#canteen_goods_supplier_id').select2();
        $('#payment_type').select2();
        $('#bank_name_id').select2();
        $('#canteen_raw_goods_id_0').select2();
        $('#bank_account_id').select2();
        var id_control = document.getElementById('idControl').value * 1;
        for (var counter = 0; counter < Number(id_control); counter++) {
            $('#canteen_raw_goods_id_' + counter).select2();
        }
    });

    function unit_load(canteen_raw_goods_id) {
        $('#img').show();

        // Extract the index from the ID
        var ids = canteen_raw_goods_id.split("_");
        var sequenceIndex = ids[3];
        var selectedItem = $("#canteen_raw_goods_id_" + sequenceIndex).val();

        // Gather the currently selected items in all dropdowns except the one that triggered the event
        var existingItems = $("select[name='canteen_raw_goods_id[]']").map(function() {
            // Skip the current dropdown (the one triggering the event)
            if ($(this).attr('id') !== 'canteen_raw_goods_id_' + sequenceIndex) {
                return $(this).val();
            }
        }).get().filter(item => item); // Filter out empty selections

        console.log('selectedItem=', selectedItem);
        console.log('existingItems=', existingItems);

        // Check if the selected item is already in use in other dropdowns
        if (existingItems.includes(selectedItem)) {
            $.toast({
                heading: 'Error',
                text: "This item is already added. Please select a new item.",
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 2000,
                icon: 'error'
            });

            // Clear the last selected item and reset the dropdown
            $("#canteen_raw_goods_id_" + sequenceIndex).val(null).trigger('change'); // Reset using select2 if applicable
            $('#img').hide();
            return; // Exit the function as no further action is needed
        }

        // AJAX request to load unit details for the selected item
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var response = JSON.parse(xhttp.responseText);
                console.log("response=", response);
                if (!response.error) {
                    // Update the form fields with the fetched unit details
                    $("#unit_id" + sequenceIndex).val(response.unit_id);
                    $("#unit_name" + sequenceIndex).val(response.name);
                    $("#price" + sequenceIndex).val(response.price);
                    $("#quantity" + sequenceIndex).focus();
                } else {
                    console.log(response.error);
                }
                $('#img').hide();
            }
        };

        xhttp.open("POST", "<?php echo site_url('CanteenPurchaseController/unit_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send('canteen_raw_goods_id=' + selectedItem);
    }
</script>