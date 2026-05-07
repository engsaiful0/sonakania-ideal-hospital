<?php
$permissions = $this->session->userdata('permissions');
?>
<script>
    $(document).ready(function() {
        $('#employee_id').select2();
    });

    function deleteAttendance(ipd_patient_id, row_id) {
        Swal.fire({
            title: 'Do you want to deliver this report?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, deliver it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?php echo site_url('IpdPatientController/delete_ipd_patient_ajax'); ?>",
                    type: 'POST',
                    data: {
                        attendance_id: attendance_id
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
            <h3 style="text-align: center">View Attendance</h3>
        </div>
        <div class="panel-body">
            <div class="col-md-12">
                <script>
                    jQuery(document).ready(function() {
                        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                            $(this).slideUp('slow', function() {
                                $(this).remove();
                            });
                        });
                    });
                </script>

            </div>
            <form method="post" action="<?php echo base_url('view-attendance') ?>">
                <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                    <tr>
                        <td>Employee</td>
                        <td>From Date</td>
                        <td>To Date</td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>
                            <select id="employee_id" name="employee_id" class="form-control">
                                <option value="" disabled="" selected="">Select Employee</option>
                                <?php
                                $employees = $this->db->select('*')->get('employee')->result();
                                foreach ($employees as $employee_value) {
                                ?>
                                    <option value="<?php echo $employee_value->employee_id ?>"><?php echo $employee_value->employee_name ?></option>
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
            <table class="table table-hover table-bordered table-condensed">
                <tr>
                    <td>#</td>
                    <td>Employee</td>
                    <td>Date</td>
                    <td>In Time</td>
                    <td>Out Time</td>
                    <td>Working Time</td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td>User</td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('hrm_attendance_edit', $permissions)) { ?>
                        <td>Edit</td>
                    <?php } ?>
                    <?php if (in_array('hrm_attendance_delete', $permissions)) { ?>
                        <td>Delete</td>
                    <?php } ?>
                </tr>
                <?php

                $sl = 1;
                $grand_total = 0;
                foreach ($attendance_data as $data) :

                    $employee = $this->db->where('employee_id', $data->employee_id)->get('employee')->row();

                    $attendance_id = $data->attendance_id;
                    $user = getUserById($data->user_id);

                ?>
                    <tr id="attendance-row-<?php echo $attendance_id; ?>">
                        <td><?php echo $sl++ ?></td>

                        <td><?php echo $employee->employee_name ?? "" ?></td>

                        <td><?php echo date('d-m-Y', strtotime($data->date)) ?></td>
                        <td><?php echo $data->in_time_hour . ':' . $data->in_time_minute . ' ' . $data->in_time_am_or_pm ?></td>
                        <td><?php echo $data->out_time_hour . ':' . $data->out_time_minute . ' ' . $data->out_time_am_or_pm ?></td>
                        <td> <?php
                                echo $data->working_hours ?? "" . ' Hours';
                                if (!empty($data->working_minutes)) {
                                    echo ' ' . number_format($data->working_minutes, 2) . ' Minutes';
                                }
                                ?></td>
                        <?php
                        $user_type_name = $this->session->userdata('user_type');
                        if ($user_type_name == 'Admin') {
                        ?>
                            <td><?php echo $user->user_name ?? "" ?> </td>
                        <?php
                        }
                        ?>
                        <?php if (in_array('hrm_attendance_edit', $permissions)) { ?>
                            <td>
                                <a class="btn btn-primary" href="<?php echo base_url("edit-single-attendance/$attendance_id") ?>"><i class="glyphicon glyphicon-edit"></i></a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('hrm_attendance_delete', $permissions)) { ?>
                            <td><a onclick="deleteAttendance(<?php echo $attendance_id; ?>, 'patient-row-<?php echo $attendance_id; ?>')" class="btn btn-success"><i class="glyphicon glyphicon-trash"></i></a></td>
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