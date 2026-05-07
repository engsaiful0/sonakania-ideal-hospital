<script type="text/javascript">
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
                // Reinitialize autocomplete for the new input field
                initializeAutocomplete("#drug_id" + next_id);

                $("#drug_id" + next_id).focus();

            }
        }
        xhttp.open("POST", "<?php echo site_url('ExpiredMedicineController/addMoreRow'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //                                    xhttp.send("fname=Henry&lname=Ford");
        xhttp.send('next_id=' + next_id);

    }

    $(document).ready(function() {
    // Validate the form
    $("#expired_medicine_form").validate({
        rules: {
            date: "required",
            drug_id0: "required",
        },
        messages: {
            date: "Enter date",
            drug_id0: "Please enter paid amount",
        }
    });

    // On form submission
    $('#submit_button').click(function(e) {
        e.preventDefault(); // Prevent default submission
alert('submit button clicked');
        var submitBtn = $(this);
        var formData = $('#expired_medicine_form').serialize();

        // Check if the form is valid
        if ($("#expired_medicine_form").valid()) {
            $('#expired_medicine_form :input').prop('disabled', true);
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

            $.ajax({
                type: "POST",
                url: "<?php echo base_url('ExpiredMedicineController/expired_medicine_save'); ?>",
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

                        // Reset form
                        $('#expired_medicine_form')[0].reset();
                        $('#expired_medicine_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');

                        setTimeout(function() {
                            window.location.href = "<?php echo base_url('print-expired-medicine') ?>";
                        }, 1002);
                    } else {
                        alert('Error: ' + response.message);
                        $('#expired_medicine_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                },
                error: function(xhr, status, error) {
                    alert("An error occurred: " + error);
                    $('#expired_medicine_form :input').prop('disabled', false);
                    submitBtn.prop('disabled', false).html('Save');
                }
            });
        } else {
            alert("Please fill in all required fields.");
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
        var quantity = $("#quantity" + seq).val();
        var mrp_rate = $("#mrp_rate" + seq).val();
        var amount = quantity * mrp_rate;
        $("#amount" + seq).val(amount.toFixed(2));
        totalamount();

    }



    function getdrugdetails(element, e) {

        var seq = $(element).attr('sequence');
        var drug = $("#drug_id" + seq).val();
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
                $("#mrp_rate" + seq).val(sales_rate);
                $("#purchase_rate" + seq).val(pur_rate);
                $("#quantity" + seq).focus();
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

    function drug_info_set(drug_name, sequence) {
        console.log("sequence=" + sequence)
        $('#img').show();
        $.ajax({
            type: "post",
            url: "<?php echo site_url('DrugController/drug_info_by_drug_name') ?>",
            data: { drug_name: drug_name },
            dataType: "json",
            success: function(response) {
                var sales_rate = response.mrp;
                var drug_id = response.drug_id;
                $("#mrp_rate" + sequence).val(sales_rate);
                $("#drug_id_value" + sequence).val(drug_id);
                $("#quantity" + sequence).focus();
                $('#img').hide();
            }
        });
    }

    function initializeAutocomplete(selector) {
        $(selector).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('DrugController/drug_info_load'); ?>",
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
                $(this).val(ui.item.label); // Set the selected value
                let sequence = $(this).attr("sequence"); // Get sequence number
                //$("#sales_rate" + sequence).val(ui.item.sales_rate); // Set sales rate
                drug_info_set(ui.item.value, sequence);
                return false;
            }
        });
    }
    $(document).ready(function() {
        $("input[name='drug_id[]']").each(function() {
            initializeAutocomplete("#" + $(this).attr("id"));
        });
    });
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Expire Medicine</h3>
        </div>
        <div class="panel-body">
            <form id="expired_medicine_form" method="POST" class="form">

                <?php
                $uniqu_id = $this->db->select('*')->order_by('expired_medicine_invoice_id', 'DESC')->limit('1')->get('expired_medicine_invoices')->row();
                $expired_medicine_invoice_no = 'ME' . time() . '0' . (intval($uniqu_id->id_serial ?? 0) + 1);
                ?>
                <input type="hidden" readonly="" class="form-control"
                    value="<?php echo isset($uniqu_id) ? intval($uniqu_id->id_serial) + 1 : 1; ?>"
                    id="id_serial" name="id_serial">

                <div class="row">


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
                            <label class="control-label col-sm-4" for="name">Expire No</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" class="form-control" value="<?php echo $expired_medicine_invoice_no ?>" id="expired_medicine_invoice_no" name="expired_medicine_invoice_no">
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

                                        <th style="width: 200px;text-align: center;">
                                            Medicine

                                        </th>
                                        <th>
                                            Purchase Rate
                                        </th>
                                        <th>
                                            MRP
                                        </th>

                                        <th>
                                            QTY
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
                                            <input type="hidden" id="drug_id_value0" readonly name="drug_id_value[]" class="form-control" ">
                                                    <input type=" text" placeholder="Enter Medicine Name" id="drug_id0" sequence=0 name="drug_id[]" class="form-control" ">
                                                </td>
                                      
                                        <td>
                                            <input type=" text" readonly id="purchase_rate0>" name="purchase_rate[]" class="form-control" sequence=0>
                                        </td>
                                        <td>
                                            <input type="text" readonly id="mrp_rate0" name="mrp_rate[]" class="form-control" sequence=0>
                                        </td>


                                        <td>
                                            <input type="text" value="" id="quantity0" name="quantity[]" oninput="validateIntegerInput(this)" class="form-control" sequence=0 required="" onkeyup="getamount(this, event)">
                                        </td>


                                        <td>
                                            <input type="text" id="amount0" name="amount[]" class="form-control amount" readonly="" sequence=0>
                                        </td>
                                        <td>
                                            <button title="use Shit + shortcut key" onclick="addMore()" type="button" id="add_more" class="btn btn-sm btn-default">
                                                <i class="glyphicon glyphicon-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                                <table style="margin-top:5px;float:right">
                                    <tr>
                                        <td colspan="" valign="middle" style="padding:5px;">
                                            <b>Total:&nbsp;</b>
                                            <input type="text" readonly="" name="total" id="total" class="form-control" style="width:200px;float: right;" value="0">
                                        </td>
                                    </tr>

                                    <tr>

                                        <td style="padding:5px;">
                                            <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;" />
                                            <button type="button" name="submit_button" id="submit_button" class="btn btn-primary">Submit</button>
                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                    </table>
                </div>

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