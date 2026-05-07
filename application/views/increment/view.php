<?php
$permissions = $this->session->userdata('permissions');
?>
<script>
    $(document).ready(function() {
        $('#employee_id').select2();


    });
    $(document).ready(function() {
        $("#debit_voucher_no").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('DebitVoucherController/debit_voucher_no_load'); ?>",
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
                $('#debit_voucher_no').val(ui.item.label);
                return false;
            }
        });
    });
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Increment</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-8">

                </div>
                <div class="col-md-4">
                    <?php if (in_array('hrm_increment_add', $permissions)) { ?>
                        <a class="btn btn-success pull-right" href="<?php echo base_url('add-increment') ?>"><i class="glyphicon glyphicon-plus"></i> Add Increment</a>
                    <?php } ?>
                </div>

            </div>
            <?php if (in_array('hrm_increment_search', $permissions)) { ?>
                <form method="post" action="<?php echo base_url('view-increment') ?>">
                    <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                        <tr>
                            <td>Employee</td>
                            <td>From Date</td>
                            <td>To Date</td>
                        </tr>
                        <tr>

                            <td>
                                <select id="employee_id" name="employee_id" class="form-control">
                                    <option value="" disabled="" selected="">Select Employee</option>
                                    <?php
                                    $employes = $this->db->select('*')->get('employee')->result();
                                    foreach ($employes as $employe) {
                                    ?>
                                        <option value="<?php echo $employe->employee_id  ?>"><?php echo $employe->employee_name . '-' . $employe->employee_unique_id ?></option>
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
                    <td>Employee</td>
                    <td>Amount</td>
                    <td>Date</td>
                    <td>Remark</td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td>User</td>
                    <?php
                    }
                    ?>
                    <!-- <td>Print</td> -->
                    <?php if (in_array('hrm_increment_edit', $permissions)) { ?>
                        <td>Edit</td>
                    <?php } ?>
                    <?php if (in_array('hrm_increment_delete', $permissions)) { ?>
                        <td>Delete</td>
                    <?php } ?>
                </tr>
                <?php
                error_reporting(0);
                $sl = 1;
                $grand_total = 0;
                foreach ($increment_data as $data) :
                    $employee = $this->db->where('employee_id', $data->employee_id)->get('employee')->row();
                    $increment_id  = $data->increment_id;
                    $user = getUserById($data->user_id);

                ?>
                    <tr>
                        <td><?php echo $sl++ ?></td>
                        <td><?php echo $employee->employee_name ?></td>
                        <td><?php echo $data->amount ?></td>
                        <td><?php echo date('d-m-Y', strtotime($data->date)) ?></td>
                        <td><?php echo $data->remark ?></td>
                        <?php
                        $user_type_name = $this->session->userdata('user_type');
                        if ($user_type_name == 'Admin') {
                        ?>
                            <td><?php echo $user->user_name ?? "" ?> </td>
                        <?php
                        }
                        ?>
                        <!-- <td>
                            <a class="btn btn-primary" href="<?php echo base_url("print-increment/$increment_id") ?>"><i class="glyphicon glyphicon-print"></i></a>
                        </td> -->
                        <?php if (in_array('hrm_increment_edit', $permissions)) { ?>
                            <td>
                                <a class="btn btn-primary" href="<?php echo base_url("edit-increment/$increment_id") ?>"><i class="glyphicon glyphicon-edit"></i></a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('hrm_increment_delete', $permissions)) { ?>
                            <td><a onclick="return confirm('Do you want to delete?')" href="<?php echo base_url("delete-this-increment/$increment_id") ?>" class="btn btn-success"><i class="glyphicon glyphicon-trash"></i></a></td>
                        <?php } ?>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php echo $this->pagination->create_links(); ?>

        </div>
    </div>
</div>