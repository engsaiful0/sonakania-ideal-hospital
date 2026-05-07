<?php
$permissions = $this->session->userdata('permissions');
?>
<script>
    $(document).ready(function() {

        $('#patient_unique_id').focus();
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
                $('form').submit(); // Automatically submit the form
                return false;
            }
        });
    });
    $(document).ready(function() {
        $("#patient_unique_id").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('IpdPatientController/patient_unique_id_load'); ?>",
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
                $('#patient_unique_id').val(ui.item.label);
                $('form').submit(); // Automatically submit the form
                return false;
            }
        });
    });
</script>


<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">View IPD Service</h3>
        </div>
        <div class="panel-body">
            <div class="col-md-12">


            </div>
        </div>
        <?php if (in_array('ipd_service_search', $permissions)) { ?>
            <form method="post" action="<?php echo base_url('view-ipd-service') ?>">
                <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                    <tr>
                        <td>Patient ID</td>
                        <td>From Date</td>
                        <td>To Date</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <input placeholder="Scan or Enter any part of patient ID .." type="text" id="patient_unique_id" name="patient_unique_id" class="form-control" />
                        </td>
                        <td>
                            <input id="datepicker1" placeholder="Select from date" name="from_date" class="form-control">
                        </td>
                        <td>
                            <input id="datepicker2" placeholder="Select to date" name="to_date" class="form-control">
                        </td>
                        <td><input type="submit" value="Submit" class="btn btn-primary"></td>
                    </tr>

                </table>
            </form>
        <?php } ?>
        <?php if (isset($ipd_service_data) && is_array($ipd_service_data) && !empty($ipd_service_data)) : ?>
            <table class="table table-hover table-bordered table-condensed">
                <tr>
                    <td>#</td>
                    <td>Patient</td>
                    <td>Patient ID</td>
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
                    <?php if (in_array('ipd_service_print', $permissions)) { ?>
                        <td>Print</td>
                    <?php } ?>
                    <?php if (in_array('ipd_service_edit', $permissions)) { ?>
                        <td>Edit</td>
                    <?php } ?>
                    <?php if (in_array('ipd_service_delete', $permissions)) { ?>
                        <td>Delete</td>
                    <?php } ?>
                </tr>
                <?php

                $sl = 1;
                $grand_total = 0;
                error_reporting(0);
                foreach ($ipd_service_data as $data) :

                    $patient = $this->db->where('ipd_patient_id', $data->ipd_patient_id)->get('ipd_patient')->row();
                    $ipd_service_id = $data->ipd_service_id;
                    $user = getUserById($data->user_id);
                ?>
                    <tr>
                        <td><?php echo $sl++ ?></td>
                        <td><?php echo $patient->patient_name ?></td>
                        <td><?php echo $patient->patient_unique_id ?></td>
                        <td><?php echo $data->net_total ?></td>
                        <td><?php echo date('d-m-Y', strtotime($data->date)) ?></td>
                        <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                         <td><?php echo $user->user_name ?? "" ?> </td>
                    <?php
                    }
                    ?>
                        <?php if (in_array('ipd_service_print', $permissions)) { ?>
                            <td>
                                <a class="btn btn-primary" href="<?php echo base_url("print-ipd-service-again/$ipd_service_id") ?>">Print</a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('ipd_service_edit', $permissions)) { ?>
                            <td>
                                <a class="btn btn-primary" href="<?php echo base_url("edit-ipd-service/$ipd_service_id") ?>"><i class="glyphicon glyphicon-edit"></i></a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('ipd_service_delete', $permissions)) { ?>
                            <td><a onclick="return confirm('Do you want to delete?')" href="<?php echo base_url("delete-this-ipd-service/$ipd_service_id") ?>" class="btn btn-success"><i class="glyphicon glyphicon-trash"></i></a></td>
                        <?php } ?>
                    </tr>
                <?php endforeach; ?>


            </table>
            <div style="width:70%;margin:0 auto;text-align:center">
                <p><?php echo $pagination; ?></p>
            </div>
        <?php else : ?>
            <p style="text-align:center">No records found.</p>
        <?php endif; ?>
    </div>
</div>
