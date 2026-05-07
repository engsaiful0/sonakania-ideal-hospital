<script>
    $(document).ready(function() {

        $('#debit_account_id').select2();
        $('#journal_account_id').select2();

    });



    $(document).ready(function() {

        $("#description").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('JournalVoucherController/description_load'); ?>",
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
                $('#description').val(ui.item.label);
                return false;
            }
        });
        // Validate the form
        $("#journal_voucher_entry_form").validate({
            rules: {
                journal_account_id: "required",

                total_amount: "required",
            },
            messages: {
                journal_account_id: "Please Select a credit account",

                total_amount: "Please Enter Total Amount",
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {

            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#journal_voucher_entry_form').serialize();

            // Check if the form is valid
            if ($("#journal_voucher_entry_form").valid()) {
                $('#journal_voucher_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('JournalVoucherController/save_journal_voucher_edit_data'); ?>",
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
                            $('#journal_voucher_entry_form')[0].reset();
                            $('#journal_voucher_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            window.location.href = "<?php echo base_url('print-journal-voucher') ?>";
                        } else {
                            alert('Error: ' + response.message);
                            $('#journal_voucher_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#journal_voucher_entry_form :input').prop('disabled', false);
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
            $journal_voucher = $this->db->where('journal_voucher_id ', $journal_voucher_id)->get('journal_vouchers')->row();
            ?>
            <div class="row">
                <div class="col-md-12">
                    <form id="journal_voucher_entry_form" class="form-horizontal" method="post" enctype='multipart/form-data'>
                        <input type="hidden" readonly="" class="form-control" value="<?php echo $journal_voucher_id ?>" id="journal_voucher_id" name="journal_voucher_id">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Debit Account</label>
                                    <div class="col-sm-8">
                                        <select style="width:100% ;" type="text" class="form-control" id="debit_account_id" name="debit_account_id">
                                            <option selected="" value="" disabled="">Select Debit Account</option>
                                            <?php
                                            $debit_accounts = $this->db->select('*')->order_by('account_name')->get('debit_account')->result();
                                            foreach ($debit_accounts as $debit_account) {
                                            ?>
                                                <option <?php echo $journal_voucher->debit_account_id == $debit_account->debit_account_id ? "selected" : "" ?> value="<?php echo $debit_account->debit_account_id; ?>"><?php echo $debit_account->account_name . '-' . $debit_account->account_number ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Credit Account *</label>
                                    <div class="col-sm-8">
                                        <select style="width:100% ;" type="text" class="form-control" id="credit_account_id" name="credit_account_id">
                                            <option selected="" value="" disabled="">Select Credit Account</option>
                                            <?php
                                            $credit_accounts = $this->db->select('*')->get('credit_account')->result();
                                            foreach ($credit_accounts as $credit_account) {
                                            ?>
                                                <option <?php echo $journal_voucher->credit_account_id == $journal_voucher->credit_account_id ? "selected" : "" ?> value="<?php echo $credit_account->credit_account_id; ?>"><?php echo $credit_account->account_name . '-' . $credit_account->account_number ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Description </label>
                                    <div class="col-sm-8">
                                        <textarea type="text" class="form-control" placeholder="Enter Description" id="description" name="description"><?php echo $journal_voucher->description ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Voucher No</label>
                                    <div class="col-sm-8">
                                        <input type="text" value="<?php echo $journal_voucher->journal_voucher_no ?>" readonly="" class="form-control" id="journal_voucher_no" name="journal_voucher_no">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Date *</label>
                                    <div class="col-sm-8">
                                        <input type="text" required="" value="<?php echo $journal_voucher->date; ?>" class="form-control" id="datepicker" name="date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Total Amount*</label>
                                    <div class="col-sm-8">
                                        <input oninput="validateInputFloatingPoint(this)" value="<?php echo $journal_voucher->total_amount ?>" placeholder="Enter Amount" type="text" class="form-control" value="" required="" id="total_amount" name="total_amount">
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
