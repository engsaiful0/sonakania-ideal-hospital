<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">View OPD Patient </h3>
    </div>
    <?php
    $permissions = $this->session->userdata('permissions');
    ?>

    <div class="panel-body" style="width: 100%;">
        <script>
            $(document).ready(function() {
                $('#gender').select2();
                $('#doctor_id').select2();
                $('#department_id').select2();
                $('#reference_doctor_id').select2();
                $('#reference_employee_id').select2();
                $('#reference_media_id').select2();
                $('#reference_director_id').select2();
            });
        </script>
        <script>
            jQuery(document).ready(function() {
                jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                    $(this).slideUp('slow', function() {
                        $(this).remove();
                    });
                });
            });
            $(document).ready(function() {
                $("#opd_patient_unique_id").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('OpdPatientController/opd_patient_unique_id_load'); ?>",
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
                        $('#opd_patient_unique_id').val(ui.item.label);
                        $('form').submit(); // Automatically submit the form
                        return false;
                    }
                });
                $("#patient_name").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('OpdPatientController/patient_name_load'); ?>",
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
                        $('#patient_name').val(ui.item.label);
                        $('form').submit(); // Automatically submit the form
                        return false;
                    }
                });
                $("#mobile_number").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('OpdPatientController/mobile_number_load'); ?>",
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
                        $('#mobile_number').val(ui.item.label);
                        $('form').submit(); // Automatically submit the form
                        return false;
                    }
                });

            });

            function doctor_load(department_id) {
                $('#img').show();
                //alert(product_category_id);
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        document.getElementById("doctor_id").innerHTML = xhttp.responseText;
                        $('#img').hide();
                    }
                }
                //  alert(xhttp.responseText);
                xhttp.open("POST", "<?php echo site_url('PatientController/doctor_load'); ?>", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                //            xhttp.send("fname=Henry&lname=Ford");
                xhttp.send("department_id=" + department_id);
            }

            function deletePatient(opd_patient_id, row_id) {
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
                            url: "<?php echo site_url('OpdPatientController/delete_opd_patient_ajax'); ?>",
                            type: 'POST',
                            data: {
                                opd_patient_id: opd_patient_id
                            },
                            success: function(response) {
                                var res = JSON.parse(response);
                                if (res.status == 'success') {
                                    $.toast({
                                        heading: 'Success',
                                        text: res.message,
                                        showHideTransition: 'slide',
                                        position: 'top-right',
                                        hideAfter: 2000,
                                        icon: 'success'
                                    });
                                    // Remove the specific row from the table
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



        <form method="post" action="<?php echo base_url('view-opd-patient') ?>">
            <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                <tr style="background-color: #337AB7;color:white">
                    <td>Patient Name</td>
                    <td>Mobile Number</td>
                    <td>ID</td>
                    <td>Gender</td>
                    <td>Ref. Media</td>
                </tr>

                <tr>
                    <td>
                        <input placeholder="Enter  Name" type="text" id="patient_name" name="patient_name" class="form-control" />
                    </td>
                    <td>
                        <input placeholder="Enter mobile" type="text" id="mobile_number" name="mobile_number" class="form-control" />
                    </td>
                    <td>
                        <input placeholder="Enter ID" type="text" id="opd_patient_unique_id" name="opd_patient_unique_id" class="form-control" />
                    </td>
                    <td>
                        <select id="gender" name="gender" class="form-control">
                            <option value="">Select Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>

                        </select>
                    </td>

                    <td>
                        <select id="reference_media_id" name="reference_media_id" class="form-control">
                            <option value="">Select Reference Media</option>
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
                </tr>
                <tr style="background-color: #337AB7;color:white">
                    <!-- <td>Department</td> -->
                    <td>Doctor</td>
                    <td>From Date</td>
                    <td>To Date</td>
                    <td>Status</td>
                    <td></td>
                </tr>
                <tr>
                    <!-- <td>
                    <select id="department_id" onchange="doctor_load(this.value)" name="department_id" class="form-control">
                            <option value="" disabled="" selected=""> Select Department</option>
                            <?php
                            $department = $this->db->select('*')->get('department')->result();
                            foreach ($department as $department_value) {
                            ?>
                                <option value="<?php echo $department_value->department_id ?>"><?php echo $department_value->department_name ?></option>
                                <?php
                            }
                                ?>
                        </select>
                    </td> -->

                    <td>
                        <select id="doctor_id" name="doctor_id" class="form-control">
                            <option value="">Select Doctor</option>
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
        <?php if (isset($detailsList) && is_array($detailsList) && !empty($detailsList)) : ?>
            <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">

                <tr>
                    <td style="width: 2%;">Sl</td>
                    <td style="width: 10%;">Patient Name</td>
                    <td style="width: 10%;">Mobile</td>
                    <td style="width: 10%;">Age</td>
                    <td style="width: 10%;">Gender</td>
                    <td style="width: 10%;">Doctor</td>
                    <td style="width: 10%;">Serial</td>
                    <td style="width: 10%;">Visiting Fee</td>
                    <td style="width: 10%;">Visiting Date & Time</td>
                    <td style="width: 10%;">Date</td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td>User</td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('opd_patient_print', $permissions)) { ?>
                        <td style="width: 5%;">Print</td>
                    <?php } ?>
                    <?php if (in_array('opd_patient_return', $permissions)) { ?>
                        <td style="width: 5%;">Return</td>
                    <?php } ?>
                    <?php if (in_array('opd_patient_edit', $permissions)) { ?>
                        <td style="width: 5%;">Edit</td>
                    <?php } ?>
                    <?php if (in_array('opd_patient_delete', $permissions)) { ?>
                        <td style="width: 5%;">Delete</td>
                    <?php } ?>

                </tr>

                <?php

                //print_r( count($detailsList));
                error_reporting(0);
                $sl = 1;
                for ($i = 0; $i < count($detailsList); ++$i) {
                    $doctor = $this->db->where('doctor_id', $detailsList[$i]->doctor_id)->get('doctor')->row();
                    $department = $this->db->where('department_id', $detailsList[$i]->department_id)->get('department')->row();
                    $opd_patient_id = $detailsList[$i]->opd_patient_id;
                    $user = getUserById($detailsList[$i]->user_id);
                ?>
                    <tr id="patient-row-<?php echo $opd_patient_id; ?>">
                        <td><?php
                            echo $sl++;
                            ?></td>
                        <td><?php echo $detailsList[$i]->opd_patient_name ?? "" ?></td>
                        <td> <?php echo $detailsList[$i]->mobile_number ?? "" ?></td>
                        <td>
                            <?php
                            $age_parts = [];

                            if ($detailsList[$i]->age_year > 0) {
                                $age_parts[] = $detailsList[$i]->age_year . ' ' . ($detailsList[$i]->age_year == 1 ? 'Year' : 'Years');
                            }

                            if ($$detailsList[$i]->age_month > 0) {
                                $age_parts[] = $detailsList[$i]->age_month . ' ' . ($detailsList[$i]->age_month == 1 ? 'Month' : 'Months');
                            }

                            if ($detailsList[$i]->age_day>0) {
                                $age_parts[] = $detailsList[$i]->age_day . ' ' . ($detailsList[$i]->age_day == 1 ? 'Day' : 'Days');
                            }

                            echo implode(' ', $age_parts);
                            ?>
                        </td>
                        <td><?php echo $detailsList[$i]->gender ?? "" ?></td>
                        <td><?php echo $doctor->doctor_name ?? "" ?> </td>
                        <td><?php echo $detailsList[$i]->serial_numaber ?? "" ?><br><b><?php echo $detailsList[$i]->status ?? "" ?></b> </td>
                        <td> <?php echo $detailsList[$i]->visiting_fee ?? "" ?></td>
                        <td> <?php echo date('d-m-Y', strtotime($detailsList[$i]->visiting_date)) . '-' . $detailsList[$i]->visiting_time ?></td>
                        <td> <?php echo date('d-m-Y', strtotime($detailsList[$i]->entry_date)) ?></td>
                        <?php
                        $user_type_name = $this->session->userdata('user_type');
                        if ($user_type_name == 'Admin') {
                        ?>
                            <td><?php echo $user->user_name ?? "" ?> </td>
                        <?php
                        }
                        ?>
                        <?php if (in_array('opd_patient_print', $permissions)) { ?>
                            <td><a class="btn btn-primary" id="" class="btn btn-success" href="<?php echo base_url("opd-patient-print-again/$opd_patient_id") ?>"><i class="glyphicon glyphicon-print" aria-hidden="true"></i></a></td>
                        <?php } ?>
                        <?php if (in_array('opd_patient_return', $permissions)) { ?>
                            <td>
                                <a class="btn btn-warning" title="Sale Return" href="<?php echo base_url("opd-patient-return/$opd_patient_id") ?>"><i class="glyphicon glyphicon-repeat" aria-hidden="true"></i>
                                </a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('opd_patient_edit', $permissions)) { ?>
                            <td><a href="<?php echo site_url("opd-patient-edit/$opd_patient_id") ?>" class="btn btn-primary" class="btn btn-primary" style="color: white"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a></td>
                        <?php } ?>
                        <?php if (in_array('opd_patient_delete', $permissions)) { ?>
                            <td><a onclick="deletePatient(<?php echo $opd_patient_id; ?>, 'patient-row-<?php echo $opd_patient_id; ?>')" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
                        <?php } ?>


                    </tr>
                <?php
                }
                ?>
            </table>
            <div style="width:70%;margin:0 auto;text-align:center">
                <p><?php echo $pagination; ?></p>
            </div>
        <?php else : ?>
            <p style="text-align:center">No patients found.</p>
        <?php endif; ?>
    </div>
</div>
