<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">View Doctor Serial </h3>
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
                $("#doctor_serial_unique_id").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('DoctorSerialController/doctor_serial_unique_id_load'); ?>",
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
                        $('#doctor_serial_unique_id').val(ui.item.label);
                        $('form').submit(); // Automatically submit the form
                        return false;
                    }
                });
                $("#patient_name").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('DoctorSerialController/patient_name_load'); ?>",
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
                            url: "<?php echo site_url('DoctorSerialController/mobile_number_load'); ?>",
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
                            url: "<?php echo site_url('DoctorSerialController/delete_doctor_serial_ajax'); ?>",
                            type: 'POST',
                            data: {
                                doctor_serial_id: doctor_serial_id
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

            // Export functionality functions
            function toggleAll(checkbox) {
                $('.record-checkbox').prop('checked', checkbox.checked);
            }

            function selectAll() {
                $('.record-checkbox').prop('checked', true);
                $('#selectAllCheckbox').prop('checked', true);
            }

            function selectNone() {
                $('.record-checkbox').prop('checked', false);
                $('#selectAllCheckbox').prop('checked', false);
            }

            function getSelectedIds() {
                var selectedIds = [];
                $('.record-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });
                return selectedIds;
            }

            function printSelected() {
                var selectedIds = getSelectedIds();
                if (selectedIds.length == 0) {
                    alert('Please select at least one record to print.');
                    return;
                }
                
                var url = "<?php echo site_url('DoctorSerialController/print_selected_doctor_serials'); ?>";
                var form = $('<form>', {
                    'method': 'POST',
                    'action': url,
                    'target': '_blank'
                });
                
                $.each(selectedIds, function(i, id) {
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': 'selected_ids[]',
                        'value': id
                    }));
                });
                
                $('body').append(form);
                form.submit();
                form.remove();
            }

            function exportToPDF() {
                var selectedIds = getSelectedIds();
                if (selectedIds.length == 0) {
                    alert('Please select at least one record to export to PDF.');
                    return;
                }
                
                var url = "<?php echo site_url('DoctorSerialController/export_selected_to_pdf'); ?>";
                var form = $('<form>', {
                    'method': 'POST',
                    'action': url
                });
                
                $.each(selectedIds, function(i, id) {
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': 'selected_ids[]',
                        'value': id
                    }));
                });
                
                $('body').append(form);
                form.submit();
                form.remove();
            }

            function exportToExcel() {
                var selectedIds = getSelectedIds();
                if (selectedIds.length == 0) {
                    alert('Please select at least one record to export to Excel.');
                    return;
                }
                
                var url = "<?php echo site_url('DoctorSerialController/export_selected_to_excel'); ?>";
                var form = $('<form>', {
                    'method': 'POST',
                    'action': url
                });
                
                $.each(selectedIds, function(i, id) {
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': 'selected_ids[]',
                        'value': id
                    }));
                });
                
                $('body').append(form);
                form.submit();
                form.remove();
            }
        </script>



        <form method="post" action="<?php echo base_url('view-doctor-serial') ?>">
            <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                <tr style="background-color: #337AB7;color:white">
                    <td>Patient Name</td>
                    <td>Mobile Number</td>
                    <td>ID</td>
                    <td>Gender</td>
                    
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

               
                </tr>
                <tr style="background-color: #337AB7;color:white">
                    <td>Department</td>
                    <td>Doctor</td>
                    <td>From Date</td>
                    <td>To Date</td>
                
                    <td></td>
                </tr>
                <tr>
                    <td>
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
                    </td>

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
                  
                    <td><input type="submit" value="Submit" class="btn btn-primary"></td>
                </tr>

            </table>
        </form>
        <?php if (isset($detailsList) && is_array($detailsList) && !empty($detailsList)) : ?>
            <!-- Export Buttons -->
            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-12">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-info" onclick="printSelected()">
                            <i class="glyphicon glyphicon-print"></i> Print Selected
                        </button>
                   
                        <button type="button" class="btn btn-success" onclick="exportToExcel()">
                            <i class="glyphicon glyphicon-download-alt"></i> Export to Excel
                        </button>
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-default btn-sm" onclick="selectAll()">Select All</button>
                        <button type="button" class="btn btn-default btn-sm" onclick="selectNone()">Select None</button>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;" id="serialTable">

                <tr>
                    <td style="width: 2%;">
                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleAll(this)">
                    </td>
                    <td style="width: 2%;">Sl</td>
                    <td style="width: 10%;">Patient Name</td>
                    <td style="width: 10%;">Mobile</td>
                    <td style="width: 10%;">Age</td>
                    <td style="width: 10%;">Gender</td>
                    <td style="width: 10%;">Doctor</td>
                    <td style="width: 10%;">Serial</td>
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
                    $doctor_serial_id = $detailsList[$i]->doctor_serial_id;
                    $user = getUserById($detailsList[$i]->user_id);
                ?>
                    <tr id="patient-row-<?php echo $doctor_serial_id; ?>">
                        <td>
                            <input type="checkbox" class="record-checkbox" value="<?php echo $doctor_serial_id; ?>">
                        </td>
                        <td><?php
                            echo $sl++;
                            ?></td>
                        <td><?php echo $detailsList[$i]->patient_name ?? "" ?></td>
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
                            <td><a class="btn btn-primary" id="" class="btn btn-success" href="<?php echo base_url("doctor-serial-print-again/$doctor_serial_id") ?>"><i class="glyphicon glyphicon-print" aria-hidden="true"></i></a></td>
                        <?php } ?>
                      
                        <?php if (in_array('opd_patient_edit', $permissions)) { ?>
                            <td><a href="<?php echo site_url("doctor-serial-edit/$doctor_serial_id") ?>" class="btn btn-primary" class="btn btn-primary" style="color: white"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a></td>
                        <?php } ?>
                        <?php if (in_array('opd_patient_delete', $permissions)) { ?>
                            <td><a onclick="deletePatient(<?php echo $doctor_serial_id; ?>, 'patient-row-<?php echo $doctor_serial_id; ?>')" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
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
