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
                $('#doctor_id').select2();
                $('#reference_media_id').select2();
                $('#general_bed_id').select2();
                $('#cabin_id').select2();
            });
            $(document).ready(function() {
                $("#patient_unique_id").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('PatientController/patient_unique_id_load'); ?>",
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
                        return false;
                    }
                });
                $("#patient_name").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('PatientController/patient_name_load'); ?>",
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
                        return false;
                    }
                });
                $("#mobile_number").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('PatientController/mobile_number_load'); ?>",
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

            function deletePatient(patient_id) {
                if (confirm('Do you want to delete?')) {
                    $.ajax({
                        url: "<?php echo site_url('IpdPatientController/delete_ipd_patient_ajax'); ?>",
                        type: 'POST',
                        data: {
                            patient_id: patient_id
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

                                setTimeout(function() {
                                    window.location.href = "<?php echo base_url('view-ipd-patient'); ?>";
                                }, 2000);
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
        <form method="post" action="<?php echo base_url('view-ipd-patient') ?>">
            <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                <tr>
                    <td>Patient Name</td>
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
                        <input placeholder="Enter ID" type="text" id="patient_unique_id" name="patient_unique_id" class="form-control" />
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
                        <select id="doctor_id" name="doctor_id" class="form-control">
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
                    <td><input type="submit" value="Submit" class="btn btn-primary"></td>
                </tr>

            </table>
        </form>
        <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
            <tr>
                <td style="width: 2%;">Sl</td>
                <td style="width: 10%;">Patient Name</td>
                <td style="width: 10%;">Mobile</td>
                <td style="width: 10%;">Id</td>
                <td style="width: 10%;">Gender</td>
                <td style="width: 10%;">Ref. Doctor</td>
                <td style="width: 10%;">Ref. Media</td>
                <td style="width: 10%;">Ward/Bed</td>
                <td style="width: 10%;">Cabin</td>
                <td style="width: 10%;">Date & Time</td>
                <td style="width: 5%;">Edit</td>
                <td style="width: 5%;">Delete</td>
                <td style="width: 5%;">Print</td>
            </tr>
            <?php
            error_reporting(0);
            $sl = 1;
            for ($i = 0; $i < count($detailsList); ++$i) {
                $doctor = $this->db->where('doctor_id', $detailsList[$i]->doctor_id)->get('doctor')->row();
                $reference_media = $this->db->where('reference_media_id', $detailsList[$i]->reference_media_id)->get('reference_media')->row();
                $ward = $this->db->where('ward_id', $detailsList[$i]->ward_id)->get('ward')->row();
                $bed = $this->db->where('bed_id', $detailsList[$i]->bed_id)->get('bed')->row();
                $cabin = $this->db->where('cabin_id', $detailsList[$i]->cabin_id)->get('cabin')->row();
                $patient_id = $detailsList[$i]->patient_id;
            ?>
                <tr>
                    <td><?php
                        echo $sl++;
                        ?></td>
                    <td><?php echo $detailsList[$i]->patient_name ?></td>


                    <td> <?php echo $detailsList[$i]->mobile_number ?></td>
                    <td><?php echo $detailsList[$i]->patient_unique_id ?></td>
                    <td><?php echo $detailsList[$i]->gender ?></td>
                    <td><?php echo $doctor->doctor_name ?> </td>
                    <td> <?php echo $reference_media->reference_media_name ?></td>
                    <?php
                    if ($ward) {
                    ?>
                        <td>Ward:<b><?php echo $ward->name ?></b> Bed:<b><?php echo $bed->bed_number ?></b> </td>
                    <?php
                    } else {
                    ?>
                        <td></td>
                    <?php
                    }
                    ?>

                    <td> <?php echo $cabin->cabin_number ?></td>
                    <td> <?php echo date('d-m-Y', strtotime($detailsList[$i]->date)) . '-' . $detailsList[$i]->admission_time ?></td>

                    <td><a href="<?php echo base_url("ipd-patient-edit/$patient_id") ?>" class="btn btn-primary" style="color: white"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a></td>
                    <td><a onclick="deletePatient(<?php echo $patient_id; ?>)" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
                    <td><a class="btn btn-primary" id="" class="btn btn-success" href="<?php echo base_url("ipd-patient-print-again/$patient_id") ?>"><i class="glyphicon glyphicon-print" aria-hidden="true"></i></a></td>
                </tr>
            <?php
            }
            ?>

        </table>
    </div>
</div>


<div class="container" style="width: 100%">
    <div class="row" style="list-style: none ">
        <?php echo $this->pagination->create_links(); ?>
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