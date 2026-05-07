<?php
$permissions = $this->session->userdata('permissions');
?>
<script>
    $(document).ready(function() {

        $('#employee_id').select2();
    });

    $(document).ready(function() {
        $("#issue_no").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('IssueController/issue_no_load'); ?>",
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
                $('#issue_no').val(ui.item.label);
                $('form').submit();
                return false;
            }
        });
    });

    function deleteIssue(issue_id, row_id) {
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
                    url: "<?php echo site_url('IssueController/issue_delete_ajax'); ?>",
                    type: 'POST',
                    data: {
                        issue_id: issue_id
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
            <h3 style="text-align: center">View Issue</h3>
        </div>
        <div class="panel-body">

            <form method="post" action="<?php echo base_url('view-issue') ?>">
                <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                    <tr>
                        <td>Concern By</td>
                        <td>Issue No</td>
                        <td>From Date</td>
                        <td>To Date</td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>
                            <select id="employee_id" name="employee_id" class="form-control">
                                <option value="" disabled="" selected="">Select Concerned</option>
                                <?php
                                $employees = $this->db->select('*')->order_by('employee_name')->get('employee')->result();
                                foreach ($employees as $employee_value) {
                                ?>
                                    <option value="<?php echo $employee_value->employee_id ?>"><?php echo $employee_value->employee_name ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>


                        <td>
                            <input placeholder="Enter any part of issue no" type="text" id="issue_no" name="issue_no" class="form-control" />

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
            <table class="table table-hover table-bordered table-condensed">
                <tr>
                    <td>#</td>
                    <td>Concern By</td>
                    <td>Purpose</td>
                    <td>Issue No</td>
                    <td>Total Quantity</td>
                    <td>Date</td>
                    <?php
                    error_reporting(0);
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td>User</td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('account_issue_print', $permissions)) { ?>
                        <td>Print</td>
                    <?php } ?>
                    <?php if (in_array('account_issue_edit', $permissions)) { ?>
                        <td>Edit</td>
                    <?php } ?>
                    <?php if (in_array('account_issue_delete', $permissions)) { ?>
                        <td>Delete</td>
                    <?php } ?>
                </tr>
                <?php

                $sl = 1;
                $grand_total = 0;
                foreach ($issue_data as $data) :

                    $employee = getEmployee($data->employee_id);

                    $issue_id = $data->issue_id;
                    $user = getUserById($data->user_id);
                ?>
                    <tr id="issue-row-<?php echo $issue_id; ?>">
                        <td><?php echo $sl++ ?></td>

                        <td><?php echo $employee->employee_name ?></td>
                        <td><?php echo $data->purpose ?></td>
                        <td><?php echo $data->issue_no ?></td>
                        <td><?php echo $data->total_quantity ?></td>

                        <td><?php echo date('d-m-Y', strtotime($data->date)) ?></td>
                        <td><?php echo $user->user_name ?? "" ?> </td>
                        <?php if (in_array('account_issue_print', $permissions)) { ?>
                            <td>
                                <a class="btn btn-primary" href="<?php echo base_url("print-issue/$issue_id") ?>"><i class="glyphicon glyphicon-print"></i></a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('account_issue_edit', $permissions)) { ?>
                            <td>
                                <a class="btn btn-primary" href="<?php echo base_url("edit-issue/$issue_id") ?>"><i class="glyphicon glyphicon-edit"></i></a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('account_issue_delete', $permissions)) { ?>
                            <td><a onclick="deleteIssue(<?php echo $issue_id; ?>, 'issue-row-<?php echo $issue_id; ?>')" href="#" class="btn btn-success"><i class="glyphicon glyphicon-trash"></i></a></td>
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