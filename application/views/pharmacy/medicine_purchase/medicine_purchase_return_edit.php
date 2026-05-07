<script type="text/javascript">
    var sequence = 0;

    function change_Cal() {
        var paid = $("#paid").val() * 1;
        var given_amount = $("#given_amount").val() * 1;
        var change_amount = given_amount - paid;
        $("#change_amount").val(change_amount.toFixed(2));
    }

    function dueCal() {
        var paid = $("#paid").val() * 1;
        var nettotal = $("#nettotal").val() * 1;
        if (paid > nettotal) {
            alert('Paid amount can not be greater thn total amount');
            $('#paid').val(0);
            $('#due_sale').val(0);
        } else {
            var due = nettotal - paid;
            $("#due_sale").val(due.toFixed(2));
        }
    }

    function removetr(element, e) {
        var seq = $(element).attr('sequence');
        if (seq != 0)
            $(element).parent().parent().remove();
        totalamount();
    }


    function getamount(element, e) {
        var seq = $(element).attr('sequence');
        var qty = $("#return_quantity" + seq).val();
        var purchase_rate = $("#purchase_rate" + seq).val();
        var amount = qty * purchase_rate;
        $("#amount" + seq).val(amount.toFixed(2));
        totalamount();

    }

    function getdrugdetails(element, e) {
        var seq = $(element).attr('sequence');
        var drug = $("#drug_add" + seq).val();
        $.ajax({
            type: "post",
            url: "<?php echo site_url('drug/details') ?>",
            data: "drug=" + drug,
            dataType: "json",
            success: function(msg) {
                var sales_rate = msg.mrp;
                var stock = msg.stock;
                var pur_rate = msg.pur_rate;
                var shelf = msg.shelf;
                var group_name = msg.group_name;
                var wsr = msg.wsr;
                $("#sales_rate" + seq).val(sales_rate);
                $("#stock" + seq).val(stock);
                $("#pur_rate" + seq).val(pur_rate);
                $("#self_no" + seq).val(shelf);
                $("#group_name" + seq).val(group_name);
                $("#wsr" + seq).val(wsr);
                $("#qty" + seq).focus();
            }
        });
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

    }


    $(function() {
        totalamount();
        $(".date").datepicker({
            "format": "dd-mm-yyyy"
        });
    });

    $(document).keypress(function(e) {
        if (e.which == 13) {
            //alert('enter key is pressed');
            var paid = $('#paid').val();
            // alert(paid);
            if (paid == '') {
                $("#add_more").click();
            } else {
                $("#submit").click();
            }
        }
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var id_control = document.getElementById('id_control').value * 1;
        for (var index = 0; index < id_control; index++) {

        }
    });
    document.addEventListener('keydown', function(event) {
        if (event.shiftKey && event.key === '+') {
            event.preventDefault();
            addMore();
        }
    });

    $(document).ready(function() {

        // Validate the form
        $("#medicine_purchase_return_form").validate({
            rules: {
                date: "required",
                drug_type_id_0: "required",
            },
            messages: {
                date: "Enter date",
                drug_type_id_0: "Please select a type name",
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {
            e.preventDefault();
            var submitBtn = $(this);
            var formData = $('#medicine_purchase_return_form').serialize();

            // Check if the form is valid
            if ($("#medicine_purchase_return_form").valid()) {
                $('#medicine_purchase_return_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('MedicinePurchaseReturnController/medicine_purchase_return_edit_save'); ?>",
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
                            $('#medicine_purchase_return_form')[0].reset();
                            $('#medicine_purchase_return_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');

                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('print-medicine-purchase-return') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#medicine_purchase_return_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#medicine_purchase_return_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Medicine Purchase Return</h3>
        </div>
        <div class="panel-body">
            <form id="medicine_purchase_return_form" method="POST" class="form">
                <div class="container-fluid">
                    <div class="col-sm-12 col-md-12 main">
                        <?php
                        $uniqu_id = $this->db->select('*')->order_by('medicine_purchase_return_invoice_id', 'DESC')->limit('1')->get('medicine_purchase_return_invoices')->row();
                        $medicine_purchase_return_invoice_no = 'MPR' . time() . '0' . (intval($uniqu_id->id_serial ?? 0) + 1);
                        ?>
                        <input type="hidden" readonly="" class="form-control"
                            value="<?php echo isset($uniqu_id) ? intval($uniqu_id->id_serial) + 1 : 1; ?>"
                            id="id_serial" name="id_serial">
                        <?php
                        $medicine_purchase_return = $this->db->where('medicine_purchase_return_id', $medicine_purchase_return_id)
                            ->get('medicine_purchase_return')
                            ->row();
                        $medicine_purchase_id = $medicine_purchase_return->medicine_purchase_id;
                        $medicine_purchase = $this->db->where('medicine_purchase_id', $medicine_purchase_id)->get('medicine_purchase')->row();
                        $medicine_purchase_details = $this->db->where('medicine_purchase_id', $medicine_purchase_id)->get('medicine_purchase_details')->result();
                        $sql_supplier = $this->db->where('supplier_id', $medicine_purchase->supplier_id)->get('supplier')->row();

                        $medicine_purchase_return_details = $this->db->where('medicine_purchase_return_id', $medicine_purchase_return_id)->get('medicine_purchase_return_details')->result();
                        ?>
                        <fieldset>
                            <legend>Supplier Info</legend>
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <td><b>Supplier</b></td>
                                    <td><?php echo $sql_supplier->name; ?></td>
                                    <td><b>Invoice No</b></td>
                                    <td><?php echo $medicine_purchase->medicine_purchase_invoice_no; ?></td>
                                    <td><b>Status</b></td>
                                    <td><?php echo $medicine_purchase->status; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Remarks</b></td>
                                    <td><?php echo $medicine_purchase->remarks; ?></td>
                                    <td><b>Date</b></td>
                                    <td><?php echo date('d-m-Y', strtotime($medicine_purchase->date)); ?></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                            </table>
                        </fieldset>
                        <fieldset>
                            <legend>Return Info</legend>
                            <div class="row">
                                <input value="<?php echo $medicine_purchase_id ?>" type="hidden" id="medicine_purchase_id " name="medicine_purchase_id">
                                <input value="<?php echo $medicine_purchase_return_id ?>" type="hidden" id="medicine_purchase_return_id " name="medicine_purchase_return_id">
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Invoice No</label>
                                        <div class="col-sm-8">
                                            <input type="hidden" readonly="" class="form-control" value="<?php echo $medicine_purchase->supplier_id ?>" id="supplier_id" name="supplier_id">
                                            <input type="text" readonly="" class="form-control" value="<?php echo $medicine_purchase_return->medicine_purchase_return_invoice_no ?>" id="medicine_purchase_return_invoice_no" name="medicine_purchase_return_invoice_no">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Remarks</label>
                                        <div class="col-sm-8">
                                            <input type="text" placeholder="Enter Remarks" class="form-control" id="remarks" value="<?php echo $medicine_purchase_return->remarks ?>" name="remarks">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Date</label>
                                        <div class="col-sm-8">
                                            <input type="text" value="<?php echo date('d-m-Y') ?>" class="form-control" id="datepicker1" name="date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div style="clear: left;" class="table-responsive">
                            <table width="100%" border="1">
                                <tr>
                                    <input id="total_control" value="" type="hidden">
                                    <td colspan="4" style="padding:5px;">
                                        <table width="100%" cellpadding="4" border="0" id="cart_tab1">
                                            <tr>
                                                <th style="text-align: center;">
                                                    Type Name
                                                </th>
                                                <th style="text-align: center;">
                                                    Drug
                                                </th>
                                                <th id="sale_rate_title" style="text-align: center;">
                                                    Purchase Rate
                                                </th>
                                                <th style="text-align: center;">
                                                    Purchase Quantity
                                                </th>
                                                <th style="text-align: center;">
                                                    Return Quantity
                                                </th>
                                                <th style="text-align: center;">
                                                    Amount
                                                </th>
                                            </tr>
                                            <input type="hidden" value="<?php echo $medicine_purchase_id ?>" name="medicine_purchase_id" class="medicine_purchase_id-control">
                                            <input type="hidden" value="<?php echo count($medicine_purchase_details) ?>" name="id_control" id="id_control" class="form-control">
                                            <?php
                                            $seq = 0;
                                            foreach ($medicine_purchase_return_details as $value_de) {
                                                $type_name = $this->db
                                                    ->where('drug_type_id', $value_de->drug_type_id)
                                                    ->get('drug_type')->row();

                                                $drug_name = $this->db
                                                    ->where('drug_id', $value_de->drug_id)
                                                    ->get('drug')->row();
                                            ?>
                                                <tr>
                                                    <input type="hidden" value="0" name="id_control" id="id_control" class="form-control">
                                                    <td style="padding:5px;">
                                                        <?php
                                                        $drug_type = $this->db->where('drug_type_id', $value_de->drug_type_id)->get('drug_type')->row();
                                                        ?>
                                                        <?php echo $drug_type->type_name??"" ?>
                                                        <input type="hidden" value="<?php echo $value_de->drug_type_id ?>" id="drug_type_id_<?php echo $seq ?>" name="drug_type_id[]" class="form-control" required="" sequence=<?php echo $seq ?>>

                                                    </td>
                                                    <td style="padding:5px;">
                                                        <?php echo $drug_name->drug_name ?>
                                                        <input type="hidden" value="<?php echo $value_de->drug_id ?>" id="drug_id<?php echo $seq ?>" name="drug_id[]" class="form-control" required="" sequence=<?php echo $seq ?>>
                                                    </td>
                                                    <td style="padding:5px;">
                                                        <?php echo $value_de->purchase_rate ?>
                                                        <input type="hidden" readonly value="<?php echo $value_de->purchase_rate ?>" id="purchase_rate<?php echo $seq ?>" name="purchase_rate[]" class="form-control" required="" onkeyup="getamount(this, event)" sequence=<?php echo $seq ?>>
                                                    </td>

                                                    <td style="padding:5px;">
                                                        <?php echo $value_de->purchase_quantity ?>
                                                        <input type="hidden" value="<?php echo $value_de->purchase_quantity ?>" id="purchase_quantity<?php echo $seq ?>" name="purchase_quantity[]" class="form-control"" onkeyup=" getamount(this, event)" sequence=<?php echo $seq ?>>
                                                    </td>
                                                    <td style="padding:5px;">
                                                        <input type="text" value="<?php echo $value_de->return_quantity ?>" id="return_quantity<?php echo $seq ?>" oninput="validateIntegerInput(this)" name="return_quantity[]" class="form-control" required="" onkeyup="getamount(this, event)" sequence=<?php echo $seq ?>>
                                                    </td>

                                                    <td style="padding:5px;">
                                                        <input type="text" id="amount<?php echo $seq ?>" value="<?php echo $value_de->amount ?>" name="amount[]" class="form-control amount" readonly="" sequence=<?php echo $seq ?>>
                                                    </td>
                                                </tr>
                                            <?php
                                                $seq++;
                                            }
                                            ?>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table style="margin-top:5px;float:right">
                                <tr>
                                    <td align="right" valign="middle" style="padding:5px;">
                                        <b>Return Total:&nbsp;</b>
                                        <input type="text" name="total" id="total" class="form-control" style="width:200px;float: right;" value="<?php echo $medicine_purchase_return->total ?>" onkeyup="totalamount()">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" style="padding:5px;">
                                        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;" />
                                        <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Save</button>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>