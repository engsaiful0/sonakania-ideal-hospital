<script type="text/javascript">
    $(document).ready(function() {

        $("#received_from").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('CreditVoucherController/received_from_load'); ?>",
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
                $('#received_from').val(ui.item.label);

                return false;
            }
        });
        $("#purpose").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('CreditVoucherController/purpose_load'); ?>",
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
                $('#purpose').val(ui.item.label);

                return false;
            }
        });
    });

    function available_quantity_load(item_id) {
        $('#img').show();
        var id_no = item_id.split("_");
        var item_id = $('#item_id_' + id_no[2]).val();
        // alert(id_no[2]);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("available_quantity_" + id_no[2]).value = xhttp.responseText;
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('IssueController/available_quantity_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("item_id=" + item_id);

    }

    function show_bank(type) {
        if (type == 'Bank') {
            document.getElementById('bank_container').style.display = "block";
        } else {
            document.getElementById('bank_container').style.display = "none";
        }
    }

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
</script>
<script>
    function show_the_relevalent_block(credit_account_id) {
        if (credit_account_id == 9) {
            //Director
            document.getElementById('director_container').style.display = 'block';
        } else {
            document.getElementById('director_container').style.display = 'none';
        }
    }
    $(document).ready(function() {
        $('#credit_account_id').select2();
        $('#type').select2();
        $('#bank_account_id').select2();
        $('#bank_name_id').select2();
        $('#director_id').select2();
    });


    $(document).ready(function() {
        // Validate the form
        $("#credit_voucher_entry_form").validate({
            rules: {
                credit_account_id: "required",
                type: "required",
                total_amount: "required",
            },
            messages: {
                credit_account_id: "Please Select a credit account",
                type: "Please select a type",
                total_amount: "Please Enter Total Amount",
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {

            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#credit_voucher_entry_form').serialize();

            // Check if the form is valid
            if ($("#credit_voucher_entry_form").valid()) {
                $('#credit_voucher_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('creditVoucherController/save_credit_voucher_edit_data'); ?>",
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
                            $('#credit_voucher_entry_form')[0].reset();
                            $('#credit_voucher_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            window.location.href = "<?php echo base_url('print-credit-voucher') ?>";
                        } else {
                            alert('Error: ' + response.message);
                            $('#credit_voucher_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#credit_voucher_entry_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
</script>

<div class="container-fluid" style=" background-color: white;width: 98%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Edit credit Voucher</h3>
        </div>
        <div class="panel-body">
            <?php
            $credit_voucher = $this->db->where('credit_voucher_id ', $credit_voucher_id)->get('credit_voucher')->row();
            ?>
            <div class="row">
                <div class="col-md-12">
                    <form id="credit_voucher_entry_form" class="form-horizontal" method="post" enctype='multipart/form-data'>
                        <input type="hidden" readonly="" class="form-control" value="<?php echo $credit_voucher_id ?>" id="credit_voucher_id" name="credit_voucher_id">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Credit Account *</label>
                                    <div class="col-sm-8">
                                        <select onchange="show_the_relevalent_block(this.value)" style="width:100% ;" type="text" class="form-control" id="credit_account_id" name="credit_account_id">
                                            <option selected="" value="" disabled="">Select Credit Account</option>
                                            <?php
                                            $credit_accounts = $this->db->select('*')->get('credit_account')->result();
                                            foreach ($credit_accounts as $credit_account) {
                                            ?>
                                                <option <?php echo $credit_voucher->credit_account_id == $credit_account->credit_account_id ? "selected" : "" ?> value="<?php echo $credit_account->credit_account_id; ?>"><?php echo $credit_account->account_name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="director_container" style="display:none">
                            <fieldset>
                                <legend>Director Information</legend>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="name">Director</label>
                                            <div class="col-sm-8">
                                                <select id="director_id" name="director_id" style="width:100% ;" class="form-control">
                                                    <option value="">Select Director</option>
                                                    <?php
                                                    $directors = $this->db->select('*')->order_by('name', 'ASC')->get('director')->result();
                                                    foreach ($directors as $director) {
                                                    ?>
                                                        <option value="<?php echo $director->director_id ?>"><?php echo $director->name ?>&nbsp; <?php echo $director->name ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </fieldset>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Type *</label>
                                    <div class="col-sm-8">
                                        <select onchange="show_bank(this.value)" style="width:100% ;" id="type" name="type" class="form-control">
                                            <option value="">Select Type</option>
                                            <option <?php echo $credit_voucher->type == 'Cash' ? "selected" : "" ?>>Cash</option>
                                            <option <?php echo $credit_voucher->type == 'Bank' ? "selected" : "" ?>>Bank</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $display = 'none';
                        if ($credit_voucher->type == 'Bank') {
                            $display = 'block';
                        }
                        ?>
                        <div id="bank_container" style="display:<?php echo $display ?>">
                            <fieldset>
                                <legend>Bank Information</legend>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="name">Director</label>
                                            <div class="col-sm-8">
                                                <select id="bank_name_id" name="bank_name_id" onchange="load_account_number(this.value)" style="width:100% ;" class="form-control">
                                                    <option value="">Select Bank</option>
                                                    <?php
                                                    $banks = $this->db->where('status', 'active')->order_by('name', 'ASC')->get('bank_name')->result();
                                                    foreach ($banks as $bank) {
                                                    ?>
                                                        <option <?php echo $credit_voucher->bank_name_id == $bank->bank_name_id ? "selected" : "" ?> value="<?php echo $bank->bank_name_id ?>"><?php echo $bank->name ?></option>

                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="name">Account Number</label>
                                            <div class="col-sm-8">
                                                <select id="bank_account_id" name="bank_account_id" style="width:100% ;" class="form-control">
                                                    <?php
                                                    $bank_account = $this->db->where('bank_account_id', $credit_voucher->bank_account_id)->get('bank_accounts')->row();

                                                    ?>
                                                    <option <?php echo $credit_voucher->bank_account_id == $bank_account->bank_account_id ? "selected" : "" ?> value="<?php echo $bank_account->bank_account_id ?>"><?php echo $bank_account->account_number ?></option>


                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="name">Check Number</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="<?php echo $credit_voucher->check_number ?>" class="form-control" placeholder="Enter Check Number" id="check_number" name="check_number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="name">Bank Details</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" placeholder="Enter Bank Details" id="bank_details" value="<?php echo $credit_voucher->bank_details ?>" name="bank_details">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Received From </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $credit_voucher->received_from ?>" placeholder="Enter Received From" id="received_from" name="received_from">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Purpose </label>
                                    <div class="col-sm-8">
                                        <textarea type="text" class="form-control" placeholder="Enter Purpose" id="purpose" name="purpose"><?php echo $credit_voucher->purpose ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Voucher No</label>
                                    <div class="col-sm-8">
                                        <input type="text" value="<?php echo $credit_voucher->credit_voucher_no ?>" readonly="" class="form-control" id="credit_voucher_no" name="credit_voucher_no">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Date *</label>
                                    <div class="col-sm-8">
                                        <input type="text" required="" value="<?php echo $credit_voucher->date; ?>" class="form-control" id="datepicker" name="date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Total Amount*</label>
                                    <div class="col-sm-8">
                                        <input oninput="validateInputFloatingPoint(this)" value="<?php echo $credit_voucher->total_amount ?>" placeholder="Enter Amount" type="text" class="form-control" value="" required="" id="total_amount" name="total_amount">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd"></label>
                                    <div class="col-sm-8">
                                        <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>





                    </form>
                </div>

            </div>

        </div>

    </div>

</div><?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
