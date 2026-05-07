<?php
$permissions = $this->session->userdata('permissions');
?>
<script>
    $(document).ready(function() {
        $('#department_id').select2();
    });
    $(document).ready(function() {
        $("#employee_unique_id").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('EmployeeController/employee_unique_id_load'); ?>",
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
                $('#employee_unique_id').val(ui.item.label);
                $('form').submit(); // Automatically submit the form
                return false;
            }
        });
    });
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">View Employee</h3>
        </div>
        <div class="panel-body">

            <?php if (in_array('hrm_employee_search', $permissions)) { ?>
                <form method="post" action="<?php echo base_url('view-employee') ?>">
                    <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                        <tr>
                            <td>Department</td>
                            <td>Employee ID</td>
                            <td>From Date</td>
                            <td>To Date</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <select type="text" class="form-control" id="department_id" name="department_id">
                                    <option selected disabled value="">Select Department</option>
                                    <?php
                                    $department = $this->db->select('*')->get('department')->result();
                                    foreach ($department as $department_value) {
                                    ?>
                                        <option value="<?php echo $department_value->department_id ?>"><?php echo $department_value->department_name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input placeholder="Enter any part of Unique ID" type="text" id="employee_unique_id" name="employee_unique_id" class="form-control" />
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
                    <td>Employee Name</td>
                    <td>ID</td>
                    <td>Mobile</td>
                    <td>Department</td>
                    <td>Designation</td>
                    <td>Gross Salary</td>
                    <td>Picture</td>
                    <td>Date of Join</td>
                    <td>Status</td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td>User</td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('hrm_employee_print', $permissions)) { ?>
                        <td>Print</td>
                    <?php } ?>
                    <?php if (in_array('hrm_employee_edit', $permissions)) { ?>
                        <td>Edit</td>
                    <?php } ?>
                    <?php if (in_array('hrm_employee_delete', $permissions)) { ?>
                        <td>Delete</td>
                    <?php } ?>
                </tr>
                <?php

                $sl = 1;
                $grand_total = 0;
                foreach ($employee_data as $data) :
                    $employee_id  = $data->employee_id;
                    $department = $this->db->where('department_id', $data->department_id)->get('department')->row();
                    $designation = $this->db->where('designation_id', $data->designation_id)->get('designation')->row();
                    $user = getUserById($data->user_id);
                ?>
                    <tr>
                        <td><?php echo $sl++ ?></td>

                        <td><?php echo $data->employee_name ?></td>
                        <td><?php echo $data->employee_unique_id ?></td>
                        <td><?php echo $data->mobile??"" ?></td>
                        <td><?php echo $department->department_name??"" ?></td>
                        <td><?php echo $designation->designation_name??"" ?></td>
                        <td><?php echo $data->gross_salary ?></td>
                        <td>
                            <?php
                            if ($data->picture == '') {
                            ?>
                                <img style="height: 100px;width: 100px;" src="<?php echo base_url() ?>assets/image_icon.jpg">
                        </td>
                    <?php
                            } else {
                    ?>
                        <img style="height: 100px;width: 100px;" src="<?php echo base_url() ?>assets/employee/<?php echo $data->picture ?>"></td>
                    <?php
                            }
                    ?>
                    <td><?php echo !empty($data->date_of_join) ? date('d-m-Y', strtotime($data->date_of_join)) : 'N/A' ?></td>
                        <td><?php echo $data->status ?></td>
                        <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                         <td><?php echo $user->user_name ?? "" ?> </td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('hrm_employee_print', $permissions)) { ?>
                        <td>
                            <a class="btn btn-primary" href="<?php echo base_url("print-employee/$employee_id") ?>"><i class="glyphicon glyphicon-print"></i></a>
                        </td>
                    <?php } ?>
                    <?php if (in_array('hrm_employee_edit', $permissions)) { ?>
                        <td>
                            <a class="btn btn-primary" href="<?php echo base_url("edit-employee/$employee_id") ?>"><i class="glyphicon glyphicon-edit"></i></a>
                        </td>
                    <?php } ?>
                    <?php if (in_array('hrm_employee_delete', $permissions)) { ?>
                        <td><a onclick="return confirm('Do you want to delete?')" href="<?php echo base_url("delete-this-employee/$employee_id") ?>" class="btn btn-success"><i class="glyphicon glyphicon-trash"></i></a></td>
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