<?php
$permissions = $this->session->userdata('permissions');
?>
<script>
    $(document).ready(function() {

        $('#credit_account_id').select2();
        $('#type').select2();
        $('#bank_name_id').select2();
    });
    $(document).ready(function() {
        $("#credit_voucher_no").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('CreditVoucherController/credit_voucher_no_load'); ?>",
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
                $('#credit_voucher_no').val(ui.item.label);
                $('form').submit();
                return false;
            }
        });
    });

    function deleteCreditVoucher(credit_voucher_id, row_id) {
        Swal.fire({
            title: 'Do you want to delete this?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?php echo site_url('CreditVoucherController/credit_vocher_delete_ajax'); ?>",
                    type: 'POST',
                    data: {
                        credit_voucher_id: credit_voucher_id
                    },
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status == 'success') {
                            $.toast({
                                heading: 'Success',
                                text: res.message,
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });

                            $('#' + row_id).remove();
                        } else {
                            $.toast({
                                heading: 'Error',
                                text: res.message,
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 2000,
                                icon: 'error'
                            });
                        }
                    }
                });
            }
        });
    }
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">View Credit Voucher</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-8">

                </div>
                <div class="col-md-4">
                    <a class="btn btn-success pull-right" href="<?php echo base_url('add-credit-voucher') ?>"><i class="glyphicon glyphicon-plus"></i> Add credit Voucher</a>
                </div>

            </div>
            <?php if (in_array('account_credit_voucher_search', $permissions)) { ?>
                <form method="post" action="<?php echo base_url('view-credit-voucher') ?>">
                    <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                        <tr>
                            <td>Purpose</td>
                            <td>Voucher No</td>
                            <td>Type</td>
                            <td>Bank</td>
                            <td>From Date</td>
                            <td>To Date</td>
                            <td></td>
                        </tr>
                        <tr>

                            <td>
                                <select id="credit_account_id" name="credit_account_id" class="form-control">
                                    <option value="" disabled="" selected="">Select Account</option>
                                    <?php
                                    $credit_accounts = $this->db->select('*')->get('credit_account')->result();
                                    foreach ($credit_accounts as $credit_account) {
                                    ?>
                                        <option value="<?php echo $credit_account->credit_account_id ?>"><?php echo $credit_account->account_name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>


                            <td>
                                <input placeholder="Enter any part of credit voucher no" type="text" id="credit_voucher_no" name="credit_voucher_no" class="form-control" />

                            </td>
                            <td>
                                <select id="type" name="type" class="form-control">
                                    <option value="" disabled="" selected="">Select Type</option>
                                    <option>Cash</option>
                                    <option>Bank</option>
                                </select>
                            </td>
                            <td>
                                <select id="bank_name_id" name="bank_name_id" class="form-control">
                                    <option value="" disabled="" selected="">Select Bank</option>
                                    <?php
                                    $banks = $this->db->select('*')->order_by('name', 'ASC')->get('bank_name')->result();
                                    foreach ($banks as $bank) {
                                    ?>
                                        <option value="<?php echo $bank->bank_name_id ?>"><?php echo $bank->name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>


                            <td>
                                <input id="datepicker1" name="from_date" class="form-control">
                            </td>
                            <td>
                                <input id="datepicker2" name="to_date" class="form-control">
                            </td>
                            <td><input type="submit" value="Submit" class="btn btn-primary"></td>
                        </tr>

                    </table>
                </form>
            <?php } ?>
            <table class="table table-hover table-bordered table-condensed">
                <tr>
                    <td>#</td>
                    <td>Crdit Account</td>
                    <td>Voucher No</td>
                    <td>Type</td>
                    <td>Bank</td>
                    <td>Received From</td>
                    <td>Purpose</td>
                    <td>Amount</td>
                    <td>Date</td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td>User</td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('account_credit_voucher_print', $permissions)) { ?>
                        <td>Print</td>
                    <?php } ?>
                    <?php if (in_array('account_credit_voucher_edit', $permissions)) { ?>
                        <td>Edit</td>
                    <?php } ?>
                    <?php if (in_array('account_credit_voucher_delete', $permissions)) { ?>
                        <td>Delete</td>
                    <?php } ?>

                </tr>
                <?php
                error_reporting(0);
                $sl = 1;
                $grand_total = 0;
                foreach ($credit_voucher_data as $data) :


                    $credit_account = getCreditAccount($data->credit_account_id);
                    $bank = getBankName($data->bank_name_id);
                    $credit_voucher_id  = $data->credit_voucher_id;
                    $user = getUserById($data->user_id);

                ?>
                    <tr id="credit-row-<?php echo $credit_voucher_id; ?>">
                        <td><?php echo $sl++ ?></td>

                        <td><?php echo $credit_account->account_name ?></td>
                        <td><?php echo $data->credit_voucher_no ?></td>
                        <td><?php echo $data->type ?></td>
                        <td>Bank: <?php echo $bank->name ?> Check Number: <?php echo $data->check_number ?> Check Date: <?php echo $data->check_date ?></td>
<td><?php echo $data->received_from ?></td>
                        <td><?php echo $data->purpose ?></td>
                        <td><?php echo $data->total_amount ?></td>
                        <td><?php echo date('d-m-Y', strtotime($data->date)) ?></td>
                        <?php
                        $user_type_name = $this->session->userdata('user_type');
                        if ($user_type_name == 'Admin') {
                        ?>
                            <td><?php echo $user->user_name ?? "" ?> </td>
                        <?php
                        }
                        ?>
                        <?php if (in_array('account_credit_voucher_print', $permissions)) { ?>
                            <td>
                                <a class="btn btn-primary" href="<?php echo base_url("print-credit-voucher/$credit_voucher_id") ?>"><i class="glyphicon glyphicon-print"></i></a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('account_credit_voucher_edit', $permissions)) { ?>
                            <td>
                                <a class="btn btn-primary" href="<?php echo base_url("edit-credit-voucher/$credit_voucher_id") ?>"><i class="glyphicon glyphicon-edit"></i></a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('account_credit_voucher_delete', $permissions)) { ?>
                            <td><a onclick="deleteCreditVoucher(<?php echo $credit_voucher_id; ?>, 'credit-row-<?php echo $credit_voucher_id; ?>')" href="#" class="btn btn-success"><i class="glyphicon glyphicon-trash"></i></a></td>

                        <?php } ?>
                    </tr>
                <?php endforeach; ?>


            </table>


            <div style="width:70%;margin:0 auto;text-align:center">
                <p><?php echo $pagination; ?></p>
            </div>

        </div>
    </div>
</div>
