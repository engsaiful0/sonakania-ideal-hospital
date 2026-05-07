<?php
$permissions = $this->session->userdata('permissions');
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">IPD Patient View</h3>
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
        </script>
        <script>
            $(document).ready(function() {
                $('#gender').select2();
                $('#ward_id').select2();
                $('#bed_id').select2();
                $('#reference_doctor_id').select2();
                $('#reference_media_id').select2();
                $('#general_bed_id').select2();
                $('#cabin_id').select2();
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

                $("#patient_name").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('IpdPatientController/patient_name_load'); ?>",
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
                            url: "<?php echo site_url('IpdPatientController/mobile_number_load'); ?>",
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

            function bed_number_load(ward_id) {

                $('#img').show();
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        document.getElementById("bed_id").innerHTML = xhttp.responseText;
                        $('#img').hide();
                    }
                }
                //  alert(xhttp.responseText);
                xhttp.open("POST", "<?php echo site_url('PatientController/bed_number_load'); ?>", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                //            xhttp.send("fname=Henry&lname=Ford");
                xhttp.send("ward_id=" + ward_id);

            }

            function deletePatient(ipd_patient_id, row_id) {
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
                            url: "<?php echo site_url('IpdPatientController/delete_ipd_patient_ajax'); ?>",
                            type: 'POST',
                            data: {
                                ipd_patient_id: ipd_patient_id
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
        <?php if (in_array('ipd_patient_search', $permissions)) { ?>
            <form method="post" action="<?php echo base_url('view-ipd-patient') ?>">
                <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                    <tr>
                        <td>Patient</td>
                        <td>Mobile Number</td>
                        <td>ID</td>
                        <td>Gender</td>
                        <td>Ref. Doctor</td>
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
                            <input placeholder="Scan or Enter ID" type="text" id="patient_unique_id" name="patient_unique_id" class="form-control" />
                        </td>
                        <td>
                            <select id="gender" name="gender" class="form-control">
                                <option></option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>

                            </select>
                        </td>
                        <td>
                            <select id="reference_doctor_id" name="reference_doctor_id" class="form-control">
                                <option></option>
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
                                <option></option>
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
                    <tr>
                        <td>Ward</td>
                        <td>Bed Number</td>
                        <td>Cabin Number</td>
                        <td>From Date</td>
                        <td>To Date</td>
                        <td>Status</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <select style="width: 100%;" type="text" onchange="bed_number_load(this.value)" class="form-control" id="ward_id" name="ward_id">
                                <option selected="" disabled="" value="">Select Ward</option>
                                <?php
                                $wards = $this->db->select('*')->get('ward')->result();
                                foreach ($wards as $value) {
                                ?>
                                    <option value="<?php echo $value->ward_id ?>"><?php echo $value->name ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select id="bed_id" name="bed_id" class="form-control">
                                <option></option>
                                <?php
                                $beds = $this->db->select('*')->get('bed')->result();
                                foreach ($beds as $bed) {

                                ?>
                                    <option value="<?php echo $bed->bed_id ?>"><?php echo $bed->bed_number ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select id="cabin_id" name="cabin_id" class="form-control">
                                <option></option>
                                <?php
                                $cabin = $this->db->select('*')->get('cabin')->result();
                                foreach ($cabin as $cabin_value) {

                                ?>
                                    <option value="<?php echo $cabin_value->cabin_id ?>"><?php echo $cabin_value->cabin_number ?></option>
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
                                <option>Admitted</option>
                                <option>Discharged</option>
                            </select>

                        </td>

                    </tr>
                    <tr>
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
                    <td style="width: 10%;">Id</td>
                    <td style="width: 10%;">Reference</td>
                    <td style="width: 10%;">Location</td>
                    <td style="width: 10%;">Status</td>
                    <td style="width: 10%;">Admission Date & Time</td>
                      <td style="width: 10%;">Duration</td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td>User</td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('ipd_patient_print', $permissions)) { ?>
                        <td style="width: 5%;">Print</td>
                    <?php } ?>
                    <?php if (in_array('ipd_patient_edit', $permissions)) { ?>
                        <td style="width: 5%;">Edit</td>
                    <?php } ?>
                    <?php if (in_array('ipd_patient_delete', $permissions)) { ?>
                        <td style="width: 5%;">Delete</td>
                    <?php } ?>

                </tr>
                <?php
                error_reporting(0);
                $sl = 1;
                for ($i = 0; $i < count($detailsList); ++$i) {
                    $reference_doctor = $this->db->where('doctor_id', $detailsList[$i]->reference_doctor_id)->get('doctor')->row();
                    $reference_media = $this->db->where('reference_media_id', $detailsList[$i]->reference_media_id)->get('reference_media')->row();
                    $reference_director = $this->db->where('director_id', $detailsList[$i]->reference_director_id)->get('director')->row();
                    $reference_employee = $this->db->where('employee_id', $detailsList[$i]->reference_employee_id)->get('employee')->row();

                    $ward = $this->db->where('ward_id', $detailsList[$i]->ward_id)->get('ward')->row();
                    $bed = $this->db->where('bed_id', $detailsList[$i]->bed_id)->get('bed')->row();
                    $cabin = $this->db->where('cabin_id', $detailsList[$i]->cabin_id)->get('cabin')->row();

                    $ipd_patient_id = $detailsList[$i]->ipd_patient_id;
                    $user = getUserById($detailsList[$i]->user_id);
                ?>
                    <tr id="patient-row-<?php echo $ipd_patient_id; ?>">
                        <td><?php
                            echo $sl++;
                            ?></td>
                        <td>Name:<b><?php echo $detailsList[$i]->patient_name ?></b><br>Mobile:<b><?php echo $detailsList[$i]->mobile_number ?></b><br>Gender:<b><?php echo $detailsList[$i]->gender ?></b></td>

                        <td><?php echo $detailsList[$i]->patient_unique_id ?></td>

                        <td>

                            <?php
                            if ($reference_doctor != '') {
                            ?>
                                Doctor:<b> <?php echo $reference_doctor->doctor_name ?></b><br>
                            <?php
                            }
                            ?>
                            <?php
                            if ($reference_media != '') {
                            ?>
                                Media:<b> <?php echo $reference_media->reference_media_name ?></b><br>
                            <?php
                            }
                            ?>
                            <?php
                            if ($reference_director != '') {
                            ?>
                                Director:<b> <?php echo $reference_director->name ?></b><br>
                            <?php
                            }
                            if ($reference_employee != '') {
                            ?>
                                Employee:<b> <?php echo $reference_employee->employee_name ?></b><br>
                            <?php
                            }
                            ?>
                        </td>
                        <td>
                            <?php if ($ward) { ?>
                                Ward:<b><?php echo $ward->name ?></b> Bed:<b><?php echo $bed->bed_number ?></b>
                            <?php
                            }
                            if ($cabin) { ?>
                                Cabin:<b><?php echo $cabin->cabin_number ?></b>
                            <?php
                            }
                            ?>
                        </td>
                        <td> <?php echo $detailsList[$i]->status ?></td>
                        <td> <?php echo date('d-m-Y', strtotime($detailsList[$i]->date)) . ' & ' . $detailsList[$i]->admission_time ?></td>
                        <td>
                            <?php
                            if ($detailsList[$i]->status == 'Admitted') {
                                // Remove extra spaces from time
                                $clean_time = str_replace(' ', '', $detailsList[$i]->admission_time); // Remove all spaces
                                $clean_time = preg_replace('/\s*:\s*/', ':', $clean_time); // Clean colon spacing just in case

                                // Combine cleaned date and time
                                $admission_datetime_str = $detailsList[$i]->date . ' ' . $clean_time;

                                try {
                                    $admission_datetime = new DateTime($admission_datetime_str);
                                    $current_datetime = new DateTime();

                                    // Calculate the interval
                                    $interval = $admission_datetime->diff($current_datetime);

                                    // Format the duration
                                    echo $interval->format('%d days %h hrs %i mins');
                                } catch (Exception $e) {
                                    echo "Invalid time format";
                                }
                            }
                            ?>

                        </td>
                        <?php
                        $user_type_name = $this->session->userdata('user_type');
                        if ($user_type_name == 'Admin') {
                        ?>
                            <td><?php echo $user->user_name ?? "" ?> </td>
                        <?php
                        }
                        ?>
                        <?php if (in_array('ipd_patient_print', $permissions)) { ?>
                            <td><a class="btn btn-primary" id="" class="btn btn-success" href="<?php echo base_url("ipd-patient-print-again/$ipd_patient_id") ?>"><i class="glyphicon glyphicon-print" aria-hidden="true"></i></a></td>
                        <?php } ?>

                        <?php if (in_array('ipd_patient_edit', $permissions)) { ?>
                            <td><a href="<?php echo base_url("ipd-patient-edit/$ipd_patient_id") ?>" class="btn btn-primary" style="color: white"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a></td>
                        <?php } ?>

                        <?php if (in_array('ipd_patient_delete', $permissions)) { ?>
                            <td><a onclick="deletePatient(<?php echo $ipd_patient_id; ?>, 'patient-row-<?php echo $ipd_patient_id; ?>')" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
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




<div class="modal" id="globalModalPermission" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="">
        <div class="modal-content">

            <div class="modal-body">
                <div class="loader">
                    <div class="es-spinner">
                        <i class="glyphicon glyphicon-spinner fa-pulse fa-5x fa-fw"></i>
                    </div>
                </div>
            </div>


        </div>

    </div>
</div>

<div class="modal" id="globalModalEdit" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="">

        <div class="modal-content">

            <div class="modal-body">
                <div class="loader">
                    <div class="es-spinner">
                        <i class="glyphicon glyphicon-spinner fa-pulse fa-5x fa-fw"></i>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
