<script type="text/javascript">
    $(document).ready(function() {

        $("#paid_to").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('DebitVoucherController/paid_to_load'); ?>",
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
                $('#paid_to').val(ui.item.label);

                return false;
            }
        });
        $("#purpose").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('DebitVoucherController/purpose_load'); ?>",
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

    function show_the_relevalent_block(debit_account_id) {
        // alert(debit_account_id);
        if (debit_account_id == 16) {
            //Director
            document.getElementById('director_container').style.display = 'block';
            document.getElementById('employee_container').style.display = 'none';
            document.getElementById('supplier_container').style.display = 'none';

        } else if (debit_account_id == 1) {
            //Employee
            document.getElementById('director_container').style.display = 'none';
            document.getElementById('employee_container').style.display = 'block';
            document.getElementById('supplier_container').style.display = 'none';

        } else if (debit_account_id == 8) {
            //
            document.getElementById('director_container').style.display = 'none';
            document.getElementById('employee_container').style.display = 'none';
            document.getElementById('supplier_container').style.display = 'block';

        } else if (debit_account_id == 14) {
            //Employee
            document.getElementById('director_container').style.display = 'none';
            document.getElementById('employee_container').style.display = 'block';
            document.getElementById('supplier_container').style.display = 'none';

        } else {
            document.getElementById('director_container').style.display = 'none';
            document.getElementById('employee_container').style.display = 'none';
            document.getElementById('supplier_container').style.display = 'none';
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

    function show_bank(type) {
        if (type == 'Bank') {
            document.getElementById('bank_container').style.display = "block";
        } else {
            document.getElementById('bank_container').style.display = "none";
        }
    }
</script>
<script>
    $(document).ready(function() {
        $('#debit_account_id').select2();
        $('#type').select2();
        $('#bank_account_id').select2();
        $('#bank_name_id').select2();
        $('#director_id').select2();
        $('#supplier_id').select2();
        $('#employee_id').select2();
        $('#year_id').select2();
        $('#month_id').select2();
        $('#doctor_id').select2();
    });


    $(document).ready(function() {
        // Validate the form
        $("#debit_voucher_entry_form").validate({
            rules: {
                debit_account_id: "required",
                type: "required",
                total_amount: "required",
            },
            messages: {
                debit_account_id: "Please Select a debit account",
                type: "Please select a type",
                total_amount: "Please Enter Total Amount",
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {

            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#debit_voucher_entry_form').serialize();

            // Check if the form is valid
            if ($("#debit_voucher_entry_form").valid()) {
                $('#debit_voucher_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('DebitVoucherController/save_debit_voucher_edit_data'); ?>",
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
                            $('#debit_voucher_entry_form')[0].reset();
                            $('#debit_voucher_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            window.location.href = "<?php echo base_url('print-debit-voucher') ?>";
                        } else {
                            alert('Error: ' + response.message);
                            $('#debit_voucher_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#debit_voucher_entry_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });

        // Live limit check for total_amount while typing (same as Add form)
        $('#total_amount').on('input', function() {
            var debitAccountId = $('#debit_account_id').val();
            var amountStr = $('#total_amount').val().trim();
            var dateStr = $('#datepicker').val();
            var excludeId = $('#debit_voucher_id').val();
            if (!debitAccountId || amountStr === '') {
                return; // wait until both present
            }
            var amount = parseFloat(amountStr);
            if (isNaN(amount)) { return; }

            $.ajax({
                url: "<?php echo site_url('DebitVoucherController/check_debit_limit'); ?>",
                method: 'POST',
                dataType: 'json',
                data: { debit_account_id: debitAccountId, amount: amount, date: dateStr, exclude_debit_voucher_id: excludeId },
                success: function(resp) {
                    if (!resp || resp.success !== true) { return; }
                    if (resp.anyExceeded) {
                        var messages = [];
                        if (resp.daily && resp.daily.exceeded) {
                            $('#total_amount').val('');
                            messages.push('Daily limit exceeded (' + Number(resp.daily.after).toLocaleString() + ' > ' + Number(resp.daily.limit).toLocaleString() + ')');
                        }
                        if (resp.monthly && resp.monthly.exceeded) {
                            $('#total_amount').val('');
                            messages.push('Monthly limit exceeded (' + Number(resp.monthly.after).toLocaleString() + ' > ' + Number(resp.monthly.limit).toLocaleString() + ')');
                        }
                        if (resp.yearly && resp.yearly.exceeded) {
                            $('#total_amount').val('');
                            messages.push('Yearly limit exceeded (' + Number(resp.yearly.after).toLocaleString() + ' > ' + Number(resp.yearly.limit).toLocaleString() + ')');
                        }
                        if (messages.length) {
                            $.toast({
                                heading: 'Limit Warning',
                                text: messages.join('<br>'),
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 3500,
                                icon: 'warning'
                            });
                        }
                    }
                }
            });
        });
    });
</script>

<div class="container-fluid" style=" background-color: white;width: 98%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Edit Debit Voucher</h3>
        </div>
        <div class="panel-body">
            <?php
            $debit_voucher = $this->db->where('debit_voucher_id ', $debit_voucher_id)->get('debit_voucher')->row();
            ?>
            <div class="row">

                <div class="col-md-12">
                    <form id="debit_voucher_entry_form" class="form-horizontal" method="post" enctype='multipart/form-data'>
                        <input type="hidden" readonly="" class="form-control" value="<?php echo $debit_voucher_id ?>" id="debit_voucher_id" name="debit_voucher_id">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Debit Account *</label>
                                    <div class="col-sm-8">
                                        <select onchange="show_the_relevalent_block(this.value)" style="width:100% ;" type="text" class="form-control" id="debit_account_id" name="debit_account_id">
                                            <option selected="" value="" disabled="">Select Debit Account</option>
                                            <?php
                                            $debit_accounts = $this->db->select('*')->get('debit_account')->result();
                                            foreach ($debit_accounts as $debit_account) {
                                            ?>
                                                <option <?php echo $debit_voucher->debit_account_id == $debit_account->debit_account_id ? "selected" : "" ?> value="<?php echo $debit_account->debit_account_id; ?>"><?php echo $debit_account->account_name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $director_display = 'none';
                        if ($debit_voucher->debit_account_id == '13') {
                            $director_display = 'block';
                        }
                        ?>
                        <div id="director_container" style="display:<?php echo $director_display ?>">
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
                        <?php
                        $employee_display = 'none';
                        if ($debit_voucher->debit_account_id == '1') {
                            $employee_display = 'block';
                        }
                        ?>
                        <div id="employee_container" style="display:<?php echo $employee_display ?>">
                            <fieldset>
                                <legend>Employee Information</legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="name">Employee</label>
                                            <div class="col-sm-8">
                                                <select id="employee_id" name="employee_id" style="width:100% ;" class="form-control">
                                                    <option value="">Select Employee</option>
                                                    <?php
                                                    $employees = $this->db->select('*')->order_by('employee_name', 'ASC')->get('employee')->result();
                                                    foreach ($employees as $employee) {
                                                    ?>
                                                        <option value="<?php echo $employee->employee_id ?>"><?php echo $employee->employee_name ?>&nbsp;<?php echo $employee->employee_unique_id ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="name">Month</label>
                                            <div class="col-sm-8">
                                                <select id="month_id" name="month_id" style="width:100% ;" class="form-control">
                                                    <option value="">Select Month</option>
                                                    <?php
                                                    $months = $this->db->select('*')->order_by('name', 'ASC')->get('month')->result();
                                                    foreach ($months as $month) {
                                                    ?>
                                                        <option value="<?php echo $month->month_id ?>"><?php echo $month->name ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="name">Year</label>
                                            <div class="col-sm-8">
                                                <select id="year_id" name="year_id" style="width:100% ;" class="form-control">
                                                    <option value="">Select Year</option>
                                                    <?php
                                                    $years = $this->db->select('*')->order_by('name', 'ASC')->get('year')->result();
                                                    foreach ($years as $year) {
                                                    ?>
                                                        <option value="<?php echo $year->year_id ?>"><?php echo $year->name ?></option>
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
                        <?php
                        $supplier_display = 'none';
                        if ($debit_voucher->debit_account_id == '19') {
                            $supplier_display = 'block';
                        }
                        ?>
                        <div id="doctor_container" style="display:<?php echo $supplier_display ?>">
                            <fieldset>
                                <legend>Doctor Information</legend>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="name">Doctor</label>
                                            <div class="col-sm-8">
                                                <select type="text" style="width:100% ;" class="form-control" id="doctor_id" name="doctor_id">
                                                    <option selected="" value="" disabled="">Doctor</option>
                                                    <?php
                                                    $doctor = $this->db->select('*')->order_by('doctor_name')->get('doctor')->result();
                                                    foreach ($doctor as $value) {
                                                    ?>
                                                        <option <?php echo $debit_voucher->doctor_id == $value->doctor_id ? "selected" : "" ?> value="<?php echo $value->doctor_id; ?>"><?php echo $value->doctor_name . '-' . $value->doctor_unique_id; ?></option>
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
                        <?php
                        $supplier_display = 'none';
                        if ($debit_voucher->debit_account_id == '1') {
                            $supplier_display = 'block';
                        }
                        ?>

                        <div id="supplier_container" style="display:<?php echo $supplier_display ?>">
                            <fieldset>
                                <legend>Supplier Information</legend>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="name">Supplier</label>
                                            <div class="col-sm-8">
                                                <select id="supplier_id" name="supplier_id" style="width:100% ;" class="form-control">
                                                    <option value="">Select Supplier</option>
                                                    <?php
                                                    $suppliers = $this->db->select('*')->order_by('name', 'ASC')->get('supplier')->result();
                                                    foreach ($suppliers as $supplier) {
                                                    ?>
                                                        <option value="<?php echo $supplier->supplier_id ?>"><?php echo $supplier->name ?></option>
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
                                            <option <?php echo $debit_voucher->type == 'Cash' ? "selected" : "" ?>>Cash</option>
                                            <option <?php echo $debit_voucher->type == 'Bank' ? "selected" : "" ?>>Bank</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $display = 'none';
                        if ($debit_voucher->type == 'Bank') {
                            $display = 'block';
                        }
                        ?>
                        <div id="bank_container" style="display:<?php echo $display ?>">
                            <fieldset>
                                <legend>Bank Information</legend>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Bank</label>
                                        <div class="col-sm-8">
                                            <select id="bank_name_id" onchange="load_account_number(this.value)" name="bank_name_id" style="width:100% ;" class="form-control">
                                                <option value="">Select Bank</option>
                                                <?php
                                                $banks = $this->db->where('status', 'active')->order_by('name', 'ASC')->get('bank_name')->result();
                                                foreach ($banks as $bank) {
                                                ?>
                                                    <option <?php echo $debit_voucher->bank_name_id == $bank->bank_name_id ? "selected" : "" ?> value="<?php echo $bank->bank_name_id ?>"><?php echo $bank->name ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Account Number</label>
                                        <div class="col-sm-8">

                                            <select id="bank_account_id" name="bank_account_id" style="width:100% ;" class="form-control">
                                                <?php
                                                $bank_account = $this->db->where('bank_account_id', $debit_voucher->bank_account_id)->get('bank_accounts')->row();

                                                ?>
                                                <option <?php echo $debit_voucher->bank_account_id == $bank_account->bank_account_id ? "selected" : "" ?> value="<?php echo $bank_account->bank_account_id ?>"><?php echo $bank_account->account_number ?></option>


                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Check Number</label>
                                        <div class="col-sm-8">
                                            <input type="text" value="<?php echo $debit_voucher->check_number ?>" class="form-control" placeholder="Enter Check Number" id="check_number" name="check_number">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="paid_to">Paid To</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $debit_voucher->paid_to ?>" placeholder="Enter Paid To" id="paid_to" name="paid_to">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Purpose</label>
                                    <div class="col-sm-8">
                                        <textarea type="text" class="form-control" placeholder="Enter Description" id="purpose" name="purpose"><?php echo $debit_voucher->purpose ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Voucher No</label>
                                    <div class="col-sm-8">
                                        <input type="text" value="<?php echo $debit_voucher->debit_voucher_no ?>" readonly="" class="form-control" id="debit_voucher_no" name="debit_voucher_no">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Date *</label>
                                    <div class="col-sm-8">
                                        <input type="text" required="" value="<?php echo $debit_voucher->date; ?>" class="form-control" id="datepicker" name="date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Total Amount*</label>
                                    <div class="col-sm-8">
                                        <input oninput="validateInputFloatingPoint(this)" value="<?php echo $debit_voucher->total_amount ?>" placeholder="Enter Amount" type="text" class="form-control" value="" required="" id="total_amount" name="total_amount">
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
