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

    function load_account_number(bank_name_id) {
        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("bank_account_id").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        xhttp.open("POST", "<?php echo site_url('DebitVoucherController/load_account_number'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("bank_name_id=" + bank_name_id);
    }

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
            var formData = $('#canteen_sell_entry_form').serialize();

            // Check if the form is valid
            if ($("#canteen_sell_entry_form").valid()) {
                $('#canteen_sell_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('CanteenSellController/save_canteen_sell_item'); ?>",
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
                            $('#canteen_sell_entry_form')[0].reset();
                            $('#canteen_sell_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            window.location.href = "<?php echo base_url('print-canteen-ready-item-sell') ?>";
                        } else {
                            alert('Error: ' + response.message);
                            $('#canteen_sell_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#canteen_sell_entry_form :input').prop('disabled', false);
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
                var newdiv = document.createElement('tr');
                newdiv.innerHTML = xhttp.responseText;
                document.getElementById('cart_tab1').appendChild(newdiv);
                $("#canteen_ready_item_id_" + next_id).select2();
                $("#item_id_" + next_id).focus();

                $('#img').hide();
            }
        }

        xhttp.open("POST", "<?php echo site_url('CanteenSellController/more_sell_ready_item_row'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //                                    xhttp.send("fname=Henry&lname=Ford");
        xhttp.send('next_id=' + next_id);
    }
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

    function unit_load(canteen_ready_item_id) {
        $('#img').show();

        // Extract the index from the ID
        var ids = canteen_ready_item_id.split("_");
        var sequenceIndex = ids[4];
        var selectedItem = $("#canteen_ready_item_id_" + sequenceIndex).val();

        // Gather the currently selected items in all dropdowns except the one that triggered the event
        var existingItems = $("select[name='canteen_ready_item_id[]']").map(function() {
            if ($(this).attr('id') !== 'canteen_ready_item_id_' + sequenceIndex) {
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
            $("#canteen_ready_item_id_" + sequenceIndex).val(null).trigger('change');
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
                    console.log("Unit Load Function:: Updating fields for sequenceIndex =", sequenceIndex);

                    // Update the form fields with the fetched unit details
                    $("#unit_id" + sequenceIndex).val(response.unit_id);
                    $("#unit_name" + sequenceIndex).val(response.name);
                    $("#price" + sequenceIndex).val(response.price);
                    // $("#total_amount" + sequenceIndex).val(Number(response.price) * 1);

                    $("#quantity" + sequenceIndex).focus();
                    totalamount();
                } else {
                    console.log(response.error);
                }
                $('#img').hide();
            }
        };

        xhttp.open("POST", "<?php echo site_url('CanteenSellController/unit_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send('canteen_ready_item_id=' + selectedItem);
    }
</script>
<div class="container-fluid">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add Sell Ready Item</h3>
        </div>
        <div class="panel-body">
            <form id="canteen_sell_entry_form" method="POST" class="form">
                <div class="col-sm-12 col-md-12 main">
                    <div class="table-responsive">

                        <table width="100%" class="table">
                            <tr>
                                <td width="100px;" align="right">
                                    <b>Invoice No:</b>
                                </td>
                                <?php

                                $canteen_ready_item_sell_invoice = $this->db->select('*')->order_by('canteen_ready_item_sell_invoice_id', 'DESC')->limit('1')->get('canteen_ready_item_sell_invoice')->row();

                                $canteen_ready_item_sell_invoice_no = 'CRS' . time() . '0' . intval($canteen_ready_item_sell_invoice->canteen_ready_item_sell_invoice_serial) + 1;
                                ?>
                                <td align="left" style="padding:5px;width: 400px;">
                                    <input type="text" id="canteen_ready_item_sell_invoice_no" name="canteen_ready_item_sell_invoice_no" class="form-control" readonly="" value="<?php echo $canteen_ready_item_sell_invoice_no; ?>" />
                                    <input type="hidden" id="canteen_ready_item_sell_invoice_serial" name="canteen_ready_item_sell_invoice_serial" class="form-control" readonly="" value="<?php echo $canteen_ready_item_sell_invoice->canteen_ready_item_sell_invoice_serial + 1; ?>" />
                                </td>
                                <td width="100px;" align="right">
                                    <b>Date</b>
                                </td>
                                <td align="left" style="padding:5px;">
                                    <input type="text" value="<?php echo date('d-m-Y'); ?>" id="date" name="date" class="form-control" readonly="" />
                                </td>
                            </tr>
                            <tr>
                                <td width="100px;" align="right">
                                    <b>Customer:</b>
                                </td>
                                <td align="left" style="padding:5px;">
                                    <input type="text" value="Walking Customer" id="customer_name" name="customer_name" class="form-control" />
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
                                <th style="width:100px;text-align: center;">Item</th>
                                <th style="text-align: center;">Quantity</th>
                                <th style="width:185px;text-align: center;">Unit</th>
                                <th style="width:185px;text-align: center;">Unit Price</th>
                                <th style="text-align: center;">Total Amount</th>
                                <th style="text-align: center;"></th>
                            </tr>
                            <tr>
                                <td>
                                    <select name="canteen_ready_item_id[]" class="form-control" id="canteen_ready_item_id_0" onchange="unit_load(this.id)" sequence="0" required="" style="width:200px;">
                                        <option value="" selected="">Select Item</option>
                                        <?php
                                        $items = $this->db->select('*')->order_by('name', 'ASC')->get('canteen_ready_items')->result();
                                        foreach ($items as $item) {
                                        ?>
                                            <option value="<?php echo $item->canteen_ready_item_id ?>"><?php echo $item->name ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <input  placeholder="Enter Quantity" type="number" value="" id="quantity0" name="quantity[]" class="form-control" oninput="getamount(this, event)" sequence="0">
                                </td>
                                <td>
                                    <input readonly type="text" id="unit_name0" name="unit_name[]" class="form-control" sequence="0" required="">
                                    <input type="hidden" id="unit_id0" name="unit_id[]" class="form-control" sequence="0" required="">
                                </td>
                                <td>
                                    <input  placeholder="Enter Unit Price" type="number" value="" id="price0" name="price[]" class="form-control" oninput="getamount(this, event)" sequence="0" required="">
                                </td>
                                <td>
                                    <input type="number" placeholder="Total Amount" value="" id="total_amount0" name="total_amount[]" class="form-control amount" readonly="" sequence="0" required="">
                                </td>
                                <td>
                                    <button title="Ctr+Shift+Press(+)" type="button" onclick="addMore()" id="add_more" class="btn btn-sm btn-default">
                                        <i class="glyphicon glyphicon-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" class="table">
                            <tr>
                                <td colspan="7" align="right" valign="middle" style="padding:5px;">
                                    <b>Total:&nbsp;</b>
                                    <input type="text" name="total" id="total" readonly="" class="form-control" style="width:200px;float: right;">
                                </td>
                            </tr>

                            <tr>
                                <td colspan="7" align="right" valign="middle" style="padding:5px;">
                                    <b>Payment Type:&nbsp;</b>
                                    <select onchange="show_bank(this.value)" type="text" name="payment_type" required="" id="payment_type" class="form-control" style="width:200px;float: right;">
                                        <option>Cash</option>
                                        <option>Bank</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="bank_container" style="display:none">


                                <td style="width:25%">
                                    <select id="bank_name_id" onchange="load_account_number(this.value)" name="bank_name_id" style="width:100% ;" class="form-control">
                                        <option value="">Select Bank</option>
                                        <?php
                                        $banks = $this->db->where('status', 'active')->order_by('name', 'ASC')->get('bank_name')->result();
                                        foreach ($banks as $bank) {
                                        ?>
                                            <option value="<?php echo $bank->bank_name_id ?>"><?php echo $bank->name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td style="width:25%">
                                    <select id="bank_account_id" name="bank_account_id" style="width:100% ;" class="form-control">
                                        <option value="">Select Account Number</option>

                                    </select>
                                </td>

                                <td style="width:25%">
                                    <input type="text" class="form-control" placeholder="Enter Check Number" id="check_number" name="check_number">
                                </td>

                                <td align="right" style="width:25%">
                                    <input type="text" style="width:300px" class="form-control" placeholder="Enter Bank Details" id="bank_details" name="bank_details">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" align="right" valign="middle" style="padding:5px;">
                                    <b>Paid:&nbsp;</b>
                                    <input type="text" name="paid" required="" oninput="dueCal()" id="paid" class="form-control" style="width:200px;float: right;">
                                </td>
                            </tr>

                            <tr>
                                <td colspan="7" align="right" valign="middle" style="padding:5px;">
                                    <b>Due:&nbsp;</b>
                                    <input type="text" name="due" id="due" readonly="" class="form-control" style="width:200px;float: right;">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" align="right">

                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" align="right" style="padding:5px;">
                                    <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Submit</button>
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