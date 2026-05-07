<script type="text/javascript">
    $(document).ready(function() {
        $('#drug_type_id_0').select2();
        $("#drug_id0").select2();
        $("#supplier_id").select2();
        $('#payment_method_id').select2();
        $('#mobile_bank_id').select2();
        $('#bank_account_id').select2();
    });
    function drug_name_load(type_name) {
        var xhttp = new XMLHttpRequest();
        $('#img').show();
        var data = type_name.split("_");
        var type_name_val = document.getElementById(type_name).value;
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("drug_id" + data[3]).innerHTML = xhttp.responseText;
                //alert(xhttp.responseText);
                $("#drug_type_id" + data[3]).select2();
                $('#img').hide();
            }
        }
        xhttp.open("POST", "<?php echo site_url('ProductController/add_drug_name'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("type_name_val=" + type_name_val);
    }

    function patient_data_set(patient_unique_id) {
        $('#img').show();
        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var patient = xhttp.responseText;
                var patient_array = patient.split('*');
                //alert(patient_array);
                if (patient_array[0] == 'ipd_patient') {
                    document.getElementById("ipd_patient_id").value = patient_array[1];
                    document.getElementById("name").value = patient_array[2];
                    document.getElementById("mobile_number").value = patient_array[3];
                    document.getElementById("age").value = patient_array[4];
                    document.getElementById("address").value = patient_array[4];
                } else {
                    document.getElementById("opd_patient_id").value = patient_array[1];
                    document.getElementById("name").value = patient_array[2];
                    document.getElementById("mobile_number").value = patient_array[3];
                    document.getElementById("address").value = patient_array[4];
                }
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('MedicineSaleController/patient_data_load_by_unique_id'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("patient_unique_id=" + patient_unique_id);
    }


    document.addEventListener('keydown', function(event) {
        if (event.shiftKey && event.key === '+') {
            event.preventDefault();
            addMore();
        }
    });

    function addMore() {
        var id_control = document.getElementById('id_control').value * 1;
        var next_id = id_control + 1;
        document.getElementById('id_control').value = next_id;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {


                // $('#cart_tab1').prepend(xhttp.responseText);
                var newdiv = document.createElement('tr');
                newdiv.innerHTML = xhttp.responseText;
                document.getElementById('cart_tab1').appendChild(newdiv);

                $("#drug_id" + next_id).select2();
                $("#drug_type_id_" + next_id).select2();
                $("#drug_type_id_" + next_id).focus();
                $('#mfg_datepicker' + next_id).datepicker({
                    "changeMonth": true,
                    "changeYear": true,
                    "dateFormat": "dd-mm-yy",
                    "yearRange": '1995:2030'
                });
                $('#exp_date' + next_id).datepicker({
                    "changeMonth": true,
                    "changeYear": true,
                    "dateFormat": "dd-mm-yy",
                    "yearRange": '1995:2030'
                });
            }
        }
        xhttp.open("POST", "<?php echo site_url('MedicinePurchaseController/addMorePurchase'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //                                    xhttp.send("fname=Henry&lname=Ford");
        xhttp.send('next_id=' + next_id);

    }

    $(document).ready(function() {
        // Validate the form
        $("#medicine_purchase_form").validate({
            rules: {
                date: "required",
                type_name_0: "required",
                paid: "required",
            },
            messages: {
                date: "Enter date",
                type_name_0: "Please select a type name",
                paid: "Please enter paid amount",
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {

            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#medicine_purchase_form').serialize();

            // Check if the form is valid
            if ($("#medicine_purchase_form").valid()) {
                $('#medicine_purchase_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('MedicinePurchaseController/medicine_purchase_save'); ?>",
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
                            $('#medicine_purchase_form')[0].reset();
                            $('#medicine_purchase_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');

                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('print-medicine-purchase') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#medicine_purchase_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#medicine_purchase_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
</script>
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
        var discount = $("#discounteach" + seq).val();
        var len = discount.length;
        var discountAmount = 0;
        if (discount[len - 1] == '%') {
            var quantity = $("#quantity" + seq).val();
            var purchase_rate = $("#purchase_rate" + seq).val();
            var disArray = discount.split('%');
            discountAmount = disArray[0];
            var amount = $("#amount" + seq).val();
            var discount = (quantity * purchase_rate) * (discountAmount / 100);
            var amount = (quantity * purchase_rate) - discount;
            $("#amount" + seq).val(amount.toFixed(2));
            totalamount();
        } else {
            var quantity = $("#quantity" + seq).val();
            var purchase_rate = $("#purchase_rate" + seq).val();
            var amount = quantity * purchase_rate - discount;
            $("#amount" + seq).val(amount.toFixed(2));
            totalamount();
        }
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
                $("#mfg_date" + seq).focus();
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
        nettotalamount();
    }

    function nettotalamount() {
        var total = $("#total").val();
        var discount = $("#discount").val();
        var len = discount.length;
        var discountAmount = 0;
        if (discount[len - 1] == '%') {
            var disArray = discount.split('%');
            discountAmount = disArray[0];
            var discount = (total * discountAmount) / 100;
            var nettotal = total - discount;
            $("#nettotal").val(nettotal.toFixed(2));
            $("#paid").val(nettotal.toFixed(2));
        } else {
            var nettotal = total - discount;
            $("#nettotal").val(nettotal.toFixed(2));
            $("#paid").val(nettotal.toFixed(2));
        }
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

    function show_bank(payment_method_id) {
        if (payment_method_id == 3) {
            document.getElementById('bank_container').style.display = "block";
            document.getElementById('mobile_banking_container').style.display = "none";
            document.getElementById('check_details_container').style.display = "none";
        } else if (payment_method_id == 2) {
            document.getElementById('bank_container').style.display = "none";
            document.getElementById('mobile_banking_container').style.display = "block";
            document.getElementById('check_details_container').style.display = "none";
        } else if (payment_method_id == 1) {
            document.getElementById('bank_container').style.display = "none";
            document.getElementById('mobile_banking_container').style.display = "none";
            document.getElementById('check_details_container').style.display = "none";
        } else if (payment_method_id == 4) {
            document.getElementById('bank_container').style.display = "none";
            document.getElementById('mobile_banking_container').style.display = "none";
            document.getElementById('check_details_container').style.display = "block";
        }
    }
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Medinice Purchase</h3>
        </div>
        <div class="panel-body">

            <form id="medicine_purchase_form" method="POST" class="form">

                <?php
                $uniqu_id = $this->db->select('*')->order_by('medicine_purchase_invoice_id', 'DESC')->limit('1')->get('medicine_purchase_invoices')->row();
                $medicine_purchase_invoice_no = 'MP' . time() . '0' . (intval($uniqu_id->id_serial ?? 0) + 1);
                ?>
                <input type="hidden" readonly="" class="form-control"
                    value="<?php echo isset($uniqu_id) ? intval($uniqu_id->id_serial) + 1 : 1; ?>"
                    id="id_serial" name="id_serial">

                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Supplier *</label>
                            <div class="col-sm-8">
                                <select name="supplier_id" id="supplier_id" class="form-control" required="">
                                    <option value="">Supplier</option>
                                    <?php
                                    $sql = $this->db->select('*')->order_by('name','ASC')->get('supplier')->result();

                                    foreach ($sql as $value) {
                                    ?>
                                        <option value="<?php echo $value->supplier_id ?>"><?php echo $value->name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Date *</label>
                            <div class="col-sm-8">
                                <input type="text" required="" value="<?php echo date('d-m-Y'); ?>" class="form-control" id="datepicker" name="date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Invoice No</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" class="form-control" value="<?php echo $medicine_purchase_invoice_no ?>" id="medicine_purchase_invoice_no" name="medicine_purchase_invoice_no">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Status</label>
                            <div class="col-sm-8">
                                <select type="text" readonly="" class="form-control" id="status" name="status">
                                    <option>Received</option>
                                    <option>Ordered</option>
                                    <option>Quotation</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Remarks</label>
                            <div class="col-sm-8">
                                <textarea type="text" placeholder="Enter Remarks" class="form-control" id="remarks" name="remarks"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="clear: left;margin-top:5px" class="table-responsive">
                    <table width="100%" border="1" class="table table-bordered table-striped">
                        <tr>
                            <input id="total_control" value="" type="hidden">
                            <td colspan="4" style="padding:5px;">

                                <table width="100%" class="table-responsive table table-bordered table-striped " id="cart_tab1">
                                    <tr>
                                        <th style="width:200px;text-align: center;">
                                            Type Name
                                        </th>
                                        <th style="width: 200px;text-align: center;">
                                            Medicine

                                        </th>
                                        <th id="sale_rate_title" style="width: 90px;text-align: center;">
                                            MFG Date
                                        </th>
                                        <th id="sale_rate_title" style="width: 90px;text-align: center;">
                                            Exp Date
                                        </th>
                                        <th>
                                            MRP
                                        </th>
                                        <th>
                                            Purchase Rate
                                        </th>
                                        <th>
                                            QTY
                                        </th>
                                        <th>
                                            Discount(%)
                                        </th>

                                        <th>
                                            Amount
                                        </th>
                                        <th>
                                            &nbsp;
                                        </th>
                                    </tr>
                                    <tr>
                                        <input type="hidden" value="0" name="id_control" id="id_control" class="form-control">

                                        <td>
                                            <select name="drug_type_id[]" class="form-control" id="drug_type_id_0" sequence=0 onchange="drug_name_load(this.id)" required="" style="width:180px;">
                                                <option value="" selected="">Select Type</option>
                                                <?php
                                                $sql = $this->db->select('*')->order_by('type_name','ASC')->get('drug_type')->result();

                                                foreach ($sql as $value) {
                                                ?>
                                                    <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="drug_id[]" class="form-control" id="drug_id0" sequence=0 onchange="getdrugdetails(this, event)" required="" style="width:150px;">
                                                <option selected="">Select Drug</option>

                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" id="mfg_datepicker0" name="mfg_date[]" class="form-control" sequence=0 required="" style="width:90px;">
                                        </td>
                                        <td>
                                            <input type="text" id="exp_date0" name="exp_date[]" class="form-control" sequence=0 required="" style="width:90px;">
                                        </td>
                                        <td>
                                            <input type="text"  id="mrp_rate0" name="mrp_rate[]" class="form-control" sequence=0 required="" style="width:60px;" >
                                        </td>
                                        <td>
                                            <input type="text" value="0" id="purchase_rate0" name="purchase_rate[]" class="form-control" sequence=0 required="" style="width:60px;" onkeyup="getamount(this, event)">
                                        </td>

                                        <td>
                                            <input type="text" value="" id="quantity0" name="quantity[]" class="form-control" sequence=0 onkeyup="getamount(this, event)" style="width:60px;" required="" onkeyup="getamount(this, event)">
                                        </td>
                                        <td>
                                            <input type="text" value="" id="discounteach0" name="discounteach[]" class="form-control" sequence=0 style="width:60px;" onkeyup="getamount(this, event)">
                                        </td>


                                        <td>
                                            <input type="text" id="amount0" name="amount[]" class="form-control amount" readonly="" style="width:60px;" sequence=0>
                                        </td>
                                        <td>
                                            <button title="use Shit + shortcut key" onclick="addMore()" type="button" id="add_more" class="btn btn-sm btn-default">
                                                <i class="glyphicon glyphicon-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                    </table>
                </div>
                <fieldset>
                    <legend>Payment Info</legend>


                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name" style="padding-left: 0;">Total</label>
                                <div class="col-sm-8" style="padding-right: 0;">
                                    <input type="text" readonly="" name="total" id="total" class="form-control" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Discount(%)</label>
                                <div class="col-sm-8">
                                    <input type="text" name="discount" id="discount" class="form-control" value="0" onkeyup="totalamount()">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Net Total</label>
                                <div class="col-sm-8">
                                    <input type="text" readonly="" name="nettotal" id="nettotal" class="form-control" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Paid</label>
                                <div class="col-sm-8">
                                    <input type="text" name="paid" id="paid" oninput="dueCal()" required="" class="form-control" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:20px">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Due</label>
                                <div class="col-sm-8">
                                    <input type="text" name="due" id="due_sale" readonly="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Payment Note</label>
                                <div class="col-sm-8">
                                    <textarea type="text" name="payment_note" id="payment_note" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Payment Method</label>
                                <div class="col-sm-8">
                                    <select onchange="show_bank(this.value)" type="text" class="form-control" id="payment_method_id" name="payment_method_id">
                                        <option disabled="" value="">Select Payment Method</option>
                                        <?php
                                        $payment_methods = $this->db->select('*')->get('payment_methods')->result();
                                        foreach ($payment_methods as $payment_method) {
                                        ?>
                                            <option value="<?php echo $payment_method->payment_method_id ?>"><?php echo $payment_method->name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" id="bank_container" style="display:none">
                                <textarea  placeholder="Enter Bank Details" type="text" class="form-control" value="" id="bank_details" name="bank_details"></textarea>
                            </div>
                            <div class="form-group" id="mobile_banking_container" style="display:none">
                                <textarea  placeholder="Enter Mobile Banking Details" type="text" class="form-control" value="" id="mobile_banking_details" name="mobile_banking_details"></textarea>
                            </div>
                            <div class="form-group" id="check_details_container" style="display:none">
                                <textarea  placeholder="Enter Check Details" type="text" class="form-control" value="" id="check_details" name="check_details"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="row" style="clear: left;margin-top:40px">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name"></label>
                                <div class="col-sm-8">
                                    <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;" />
                                    <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<style>
    /* Adjust the padding/margin for specific elements */
    .control-label {
        padding-left: 0;
        padding-right: 0;
    }

    .form-control {
        margin-bottom: 0;
    }

    .col-md-3>.form-group>.col-sm-8 {
        padding-left: 0;
        padding-right: 0;
    }
</style>