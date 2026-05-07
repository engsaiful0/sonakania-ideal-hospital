<?php
$permissions = $this->session->userdata('permissions');
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Discharge Slip</h3>
    </div>

    <div class="panel-body" style="width: 100%;">
        <script>
            jQuery(document).ready(function() {
                jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                    $(this).slideUp('slow', function() {
                        $(this).remove();
                    });
                });
            });
            $(document).ready(function() {
                $("#discharge_slip_unique_id").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('DischargeSlipController/discharge_slip_id_load'); ?>",
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
                        $('#discharge_slip_unique_id').val(ui.item.label);
                        $('form').submit(); // Automatically submit the form
                        return false;
                    }
                });
                $("#patient_unique_id").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('DischargeSlipController/patient_unique_id_load'); ?>",
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

                $('#ipd_patient_id').focus();
            });


            function deleteDischargeSlip(discharge_slip_id, row_id) {
                if (confirm('Do you want to delete?')) {
                    $.ajax({
                        url: "<?php echo site_url('DischargeSlipController/delete_discharge_slip_ajax'); ?>",
                        type: 'POST',
                        data: {
                            discharge_slip_id: discharge_slip_id
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
                                setTimeout(function() {
                                    window.location.href = "<?php echo base_url('view-discharge-slip'); ?>";
                                }, 1005);
                                // Remove the specific row from the table
                                //$('#' + row_id).remove();
                            } else {
                                $.toast({
                                    heading: 'Error',
                                    text: res.message,
                                    showHideTransition: 'slide',
                                    position: 'top-right',
                                    hideAfter: 1000,
                                    icon: 'error'
                                });
                            }
                        }
                    });
                }
            }
        </script>

        <?php if (in_array('search_discharge_slip', $permissions)) { ?>
            <form method="post" action="<?php echo base_url() . "index.php/DischargeSlipController/view_discharge_slip"; ?>">
                <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                    <tr style="background-color: #337AB7;color:white">
                        <td>Discharge Slip ID</td>
                        <td>Patient ID</td>
                        <td>Date</td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>
                            <input placeholder="Scan or Enter Discharge ID" type="text" id="discharge_slip_unique_id" name="discharge_slip_unique_id" class="form-control" />
                        </td>

                        <td>
                            <input placeholder="Scan or Enter Patient ID" type="text" id="patient_unique_id" name="patient_unique_id" class="form-control" />
                        </td>

                        <td>
                            <input placeholder="Date" id="datepicker1" name="admission_date" class="form-control">

                        </td>

                        <td><input type="submit" value="Submit" class="btn btn-primary"></td>

                    </tr>


                </table>
            </form>
        <?php } ?>
        <?php if (isset($detailsList) && is_array($detailsList) && !empty($detailsList)) : ?>
            <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                <tr>
                    <td style="width: 2%;">Sl</td>
                    <td style="width: 10%;">Patient</td>
                    <td>Patient ID</td>
                    <td>Discharge Slip ID</td>
                    <td>BP</td>
                    <td>Follow Up</td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td>User</td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('print_discharge_slip', $permissions)) { ?>
                        <td style="width: 5%;">Print</td>
                    <?php } ?>
                    <?php if (in_array('edit_discharge_slip', $permissions)) { ?>
                        <td style="width: 5%;">Edit</td>
                    <?php } ?>
                    <?php if (in_array('delete_discharge_slip', $permissions)) { ?>
                        <td style="width: 5%;">Delete</td>
                    <?php } ?>
                </tr>
                <?php
                $sl = 1;
                for ($i = 0; $i < count($detailsList); ++$i) {
                    $ipd_patient = $this->db->where('ipd_patient_id', $detailsList[$i]->ipd_patient_id)->get('ipd_patient')->row();
                    $discharge_slip_id = $detailsList[$i]->discharge_slip_id;
                    $user = getUserById($detailsList[$i]->user_id);
                ?>
                    <tr id="patient-row-<?php echo $discharge_slip_id; ?>">
                        <td><?php echo $sl++; ?></td>
                        <td>Name:<b><?php echo $ipd_patient->patient_name ?></b><br>Mobile:<b><?php echo $ipd_patient->mobile_number ?></b><br>
                            </b>
                        </td>
                        <td><?php echo $detailsList[$i]->patient_unique_id ?></td>
                        <td><?php echo $detailsList[$i]->discharge_slip_unique_id ?></td>
                        <td><?php echo $detailsList[$i]->bp_systolic ?>/<?php echo $detailsList[$i]->bp_diastolic ?></td>
                        <td><?php echo $detailsList[$i]->follow_up ?></td>
                        <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                         <td><?php echo $user->user_name ?? "" ?> </td>
                    <?php
                    }
                    ?>
                        <?php if (in_array('print_discharge_slip', $permissions)) { ?>
                            <td><a href="<?php echo base_url("print-discharge-slip-data-again/$discharge_slip_id") ?>" title="Print" class="btn btn-primary" style="color: white"><i class="glyphicon glyphicon-print" aria-hidden="true"></i></a></td>
                        <?php } ?>
                        <?php if (in_array('edit_discharge_slip', $permissions)) { ?>
                            <td><a href="<?php echo base_url("edit-discharge-slip/$discharge_slip_id") ?>" class="btn btn-primary" style="color: white"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a></td>
                        <?php } ?>
                        <?php if (in_array('delete_discharge_slip', $permissions)) { ?>
                            <td><a onclick="deleteDischargeSlip(<?php echo $discharge_slip_id; ?>)" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </table>
            <div style="width:70%;margin:0 auto;text-align:center">
                <p><?php echo $pagination; ?></p>
            </div>
        <?php else : ?>
            <p style="text-align:center">No records found.</p>
        <?php endif; ?>
    </div>
</div>