<?php
$permissions = $this->session->userdata('permissions');
?>
<script>
    $(document).ready(function() {
        $('#gender').select2();
        $('#reference_media_id').select2();
        $('#reference_doctor_id').select2();
        $('#patient_id').select2();

        $('#referred_by_id').select2();
        $('#emergency_invoice_no').focus();

    });
    $(document).ready(function() {
        $("#name").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('EmergencyController/name_load'); ?>",
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
                $('#name').val(ui.item.label);
                $('form').submit(); // Automatically submit the form
                return false;
            }
        });

        $("#phone").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('EmergencyController/phone_load'); ?>",
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
                $('#phone').val(ui.item.label);
                $('form').submit(); // Automatically submit the form
                return false;
            }
        });

        $("#emergency_invoice_no").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('EmergencyController/emergency_invoice_no_load'); ?>",
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
                $('#emergency_invoice_no').val(ui.item.label);
                $('form').submit(); // Automatically submit the form
                return false;
            }
        });

    });

    function deleteEmergency(emergency_id) {
        if (confirm('Do you want to delete?')) {
            $.ajax({
                url: "<?php echo site_url('EmergencyController/delete_ipd_emergency_ajax'); ?>",
                type: 'POST',
                data: {
                    emergency_id: emergency_id
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
                            window.location.href = "<?php echo base_url('view-emergency'); ?>";
                        }, 1005);
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
    }
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">View Emergency</h3>
        </div>
        <div class="panel-body">
            <?php if (in_array('search_emergency', $permissions)) { ?>
                <form method="post" action="<?php echo base_url('view-emergency') ?>">
                    <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                        <tr>
                            <td>Patient</td>
                            <td>Phone</td>
                            <td>Invoice No</td>
                            <td>Gender</td>
                            <td>Ref. Doctor</td>
                            <td>Ref. Media</td>
                            <td>From Date</td>
                            <td>To Date</td>
                            <td>Status</td>
                            <td></td>
                        </tr>
                        <tr>

                            <td>
                                <input placeholder="Enter Name" id="name" name="name" class="form-control">
                            </td>
                            <td>
                                <input placeholder="Enter Phone" id="phone" name="phone" class="form-control">
                            </td>
                            <td>
                                <input placeholder="Scan or Insert Invoice ID.." id="emergency_invoice_no" name="emergency_invoice_no" class="form-control">

                            </td>
                            <td>
                                <select id="gender" name="gender" class="form-control">
                                    <option selected="" value="">Select Gender</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Other</option>

                                </select>
                            </td>
                            <td>
                                <select type="text" class="form-control" id="reference_doctor_id" name="reference_doctor_id">
                                    <option selected="" value="">Select Reference Doctor</option>
                                    <?php
                                    $doctor = $this->db->select('*')->get('doctor')->result();
                                    foreach ($doctor as $doctor_value) {
                                    ?>
                                        <option value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->doctor_name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select id="reference_media_id" name="reference_media_id" class="form-control">
                                    <option selected="" value="">Select Reference Media</option>
                                    <?php
                                    $reference_media = $this->db->select('*')->get('reference_media')->result();
                                    foreach ($reference_media as $reference_media_value) {

                                    ?>
                                        <option value="<?php echo $reference_media_value->reference_media_id ?>"><?php echo $reference_media_value->reference_media_name ?></option>
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
                            <td>
                                <select id="status" name="status" class="form-control">
                                    <option value="">Select Status</option>
                                    <option>Returned</option>
                                </select>

                            </td>
                            <td><input type="submit" value="Submit" class="btn btn-primary"></td>
                        </tr>

                    </table>
                </form>
            <?php } ?>
            <?php if (isset($emergencies) && is_array($emergencies) && !empty($emergencies)) : ?>
                <table class="table table-hover table-bordered table-condensed">
                    <tr>
                        <td>#</td>
                        <td>Name</td>
                        <td>Age</td>
                        <td>Phone</td>
                        <td style="display: none;">Address</td>
                        <td style="display: none;">Attendant</td>
                        <td>Invoice No</td>
                        <td>Total</td>
                        <td>Paid</td>
                        <td>Due</td>
                        <td>Date</td>
                        <td>Time</td>
                        <?php
                        $user_type_name = $this->session->userdata('user_type');
                        if ($user_type_name == 'Admin') {
                        ?>
                            <td>User</td>
                        <?php
                        }
                        ?>
                        <?php if (in_array('due_payment_status_emergency', $permissions)) { ?>
                            <td>Payment<br> Status</td>
                        <?php } ?>
                        <?php if (in_array('print_emergency', $permissions)) { ?>
                            <td>Print</td>
                        <?php } ?>
                        <?php if (in_array('return_emergency', $permissions)) { ?>
                            <td>Return</td>
                        <?php } ?>
                        <?php if (in_array('edit_emergency', $permissions)) { ?>
                            <td>Edit</td>
                        <?php } ?>
                        <?php if (in_array('delete_emergency', $permissions)) { ?>
                            <td>Delete</td>
                        <?php } ?>
                    </tr>
                    <?php

                    $sl = 1;
                    $grand_total = 0;
                    foreach ($emergencies as $emergency) :

                        $doctor = $this->db->where('doctor_id', $emergency->doctor_id)->get('doctor')->row();
                        $reference_media = $this->db->where('reference_media_id', $emergency->reference_media_id)->get('reference_media')->row();
                        $emergency_id = $emergency->emergency_id;
                        $user = getUserById($emergency->user_id);
                    ?>
                        <tr>
                            <td><?php echo $sl++ ?></td>

                            <td><?php echo $emergency->name ?></td>
                            <td>
                                <b><?php
                                    $age_parts = [];

                                    if ($emergency->age_year > 0) {
                                        $age_parts[] = $emergency->age_year . ' ' . ($emergency->age_year == 1 ? 'Year' : 'Years');
                                    }

                                    if ($emergency->age_month > 0) {
                                        $age_parts[] = $emergency->age_month . ' ' . ($emergency->age_month == 1 ? 'Month' : 'Months');
                                    }

                                    if ($emergency->age_day > 0) {
                                        $age_parts[] = $emergency->age_day . ' ' . ($emergency->age_day == 1 ? 'Day' : 'Days');
                                    }

                                    echo implode(' ', $age_parts);
                                    ?></b>
                            </td>
                            <td><?php echo $emergency->phone ?></td>
                            <td style="display: none;"><?php echo $emergency->address ?></td>
                            <td style="display: none;"><?php echo $emergency->attendant ?></td>
                            <td><?php echo $emergency->emergency_invoice_no ?></td>
                            <td><?php echo $emergency->total ?></td>
                            <td><?php echo (float)$emergency->paid + (float)$emergency->due_payment ?><br><b><?php echo $emergency->status; ?></b></td>
                            <td><?php echo $emergency->due ?></td>
                            <td><?php echo date('d-m-Y', strtotime($emergency->date)) ?></td>
                            <td><?php echo $emergency->emergency_time; ?></td>

                            <?php
                            $user_type_name = $this->session->userdata('user_type');
                            if ($user_type_name == 'Admin') {
                            ?>
                                <td><?php echo $user->user_name ?? "" ?> </td>
                            <?php
                            }
                            ?>
                            <?php if (in_array('due_payment_status_emergency', $permissions)) : ?>
                                <?php if ($emergency->due != 0) : ?>
                                    <td>
                                        <a class="btn btn-primary" href="<?php echo base_url("emergency-due-payment/$emergency_id") ?>">Pay Now</a>
                                    </td>
                                <?php else : ?>
                                    <td>
                                        Paid
                                    </td>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if (in_array('print_emergency', $permissions)) { ?>
                                <td>
                                    <a class="btn btn-primary" href="<?php echo base_url("print-emergency/$emergency_id") ?>"><i class="glyphicon glyphicon-print"></i></a>
                                </td>
                            <?php } ?>
                            <?php if (in_array('return_emergency', $permissions)) { ?>
                                <td>
                                    <a class="btn btn-warning" title="Sale Return" href="<?php echo base_url("return-emergency/$emergency_id") ?>"><i class="glyphicon glyphicon-repeat" aria-hidden="true"></i>
                                    </a>
                                </td>
                            <?php } ?>
                            <?php if (in_array('edit_emergency', $permissions)) { ?>
                                <td>
                                    <a class="btn btn-primary" href="<?php echo base_url("edit-emergency/$emergency_id") ?>"><i class="glyphicon glyphicon-edit"></i></a>
                                </td>
                            <?php } ?>
                            <?php if (in_array('delete_emergency', $permissions)) { ?>
                                <td><a onclick="deleteEmergency(<?php echo $emergency_id; ?>)" class="btn btn-success"><i class="glyphicon glyphicon-trash"></i></a></td>
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
</div>