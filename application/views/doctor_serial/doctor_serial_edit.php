<script>
  

   

    function serial_load(doctor_id) {
        $('#img').show();
        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("serial_numaber").value = xhttp.responseText;
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('DoctorSerialController/serial_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("doctor_id=" + doctor_id);
    }

    function doctor_load(department_id) {
        $('#img').show();
        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("doctor_id1").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('PatientController/doctor_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("department_id=" + department_id);
    }

    $(document).ready(function() {
        $('#gender').select2();

        $('#doctor_id').select2();
        $('#department_id').select2();
        $('#reference_doctor_id').select2();
        $('#reference_employee_id').select2();
        $('#reference_media_id').select2();
        $('#reference_director_id').select2();
        $('#years_or_days').select2();
      

    });
    jQuery(document).ready(function() {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });
    $(function() {
        $('[name="visiting_time"]').timeselector()
    });


    $().ready(function() {
        // validate the comment form when it is submitted       
        // validate signup form on keyup and submit
        $("#patient_form").validate({
            rules: {
                patient_name: "required",
                doctor_id: "required",
                mobile_number: {
                    required: true,
                    minlength: 11
                },
                gender: "required",

            },
            messages: {
                patient_name: "Please Enter Patient Name",
                doctor_id: "Please select a doctor",

                mobile_number: {
                    required: "Please enter a valid mobile number",
                    minlength: "Your mobile number must consist of at least 13 characters"
                },
                gender: "Select gender",
            }
        });
    });
    $(document).ready(function() {
        // On form submission
        $('#submit_button').click(function(e) {
            e.preventDefault();
            var submitBtn = $(this);
            var formData = $('#patient_form').serialize();

            // Check if the form is valid
            if ($("#patient_form").valid()) {
                $('#patient_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('DoctorSerialController/edit_doctor_serial_save'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {

                            $('#patient_form')[0].reset();
                            $('#patient_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            $.toast({
                                heading: 'Success',
                                text: 'Data has been updated successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('print-doctor-serial') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#patient_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#patient_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
</script>
<div class="container-fluid" style=" background-color: white;width: 98%;">

    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Edit OPD Patient</h3>
        </div>
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">
                    <?php
                    $doctor_serial = $this->db->where('doctor_serial_id', $doctor_serial_id)->get('doctor_serial')->row();
                    $doctor_id = $this->db->select('*')->get('doctor');
                    
                    ?>
                    <form class="form-horizontal" id="patient_form" method="post" enctype="multipart/form-data">
                        <input name="doctor_serial_id" type="hidden" value="<?php echo $doctor_serial_id ?>">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Name *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="patient_name" value="<?php echo $doctor_serial->patient_name ?>" name="patient_name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" oninput="validatePhoneNumberInput(this)" class="form-control" id="mobile_number" value="<?php echo $doctor_serial->mobile_number ?>" name="mobile_number">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Patient ID </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly="" value="<?php echo $doctor_serial->doctor_serial_unique_id ?>" id="doctor_serial_unique_id" name="doctor_serial_unique_id">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="name">Age</label>


                                    <div class="col-sm-3">
                                        <input type="text" placeholder="Year" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $doctor_serial->age_year ?>" id="age_year" name="age_year">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" placeholder="Month" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $doctor_serial->age_month ?>" id="age_month" name="age_month">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" placeholder="Day" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $doctor_serial->age_day ?>" id="age_day" name="age_day">
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Gender *</label>
                                    <div class="col-sm-8">
                                        <select type="text" required="" class="form-control" id="gender" name="gender">
                                            <option><?php echo $doctor_serial->gender ?></option>
                                            <option>Male</option>
                                            <option>Female</option>
                                            <option>Other</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Address </label>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="Enter Address" class="form-control" value="<?php echo $doctor_serial->address ?>" id="address" name="address">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Department</label>
                                    <div class="col-sm-8">
                                        <select type="text" class="form-control" onchange="doctor_load(this.value)" id="department_id" name="department_id">

                                            <?php
                                            $department = $this->db->select('*')->get('department')->result();
                                            foreach ($department as $department_value) {
                                            ?>
                                                <option <?php echo $doctor_serial->department_id == $department_value->department_id ? "selected" : "" ?> value="<?php echo $department_value->department_id ?>"><?php echo $department_value->department_name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Visiting Date </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="datepicker2" value="<?php echo date('d-m-Y', strtotime($doctor_serial->visiting_date)) ?>" name="visiting_date">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Visiting Time </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="visiting_time" value="<?php echo $doctor_serial->visiting_time ?>" name="visiting_time">

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row" style="clear:left">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Doctor *</label>
                                    <div class="col-sm-8">
                                        <select type="text" required="" class="form-control" id="doctor_id" onchange="visiting_fee_load(this.value), serial_load(this.value)" name="doctor_id">

                                            <?php
                                            $doctor = $this->db->select('*')->get('doctor')->result();
                                            foreach ($doctor as $doctor_value) {
                                            ?>
                                                <option <?php echo $doctor_serial->doctor_id == $doctor_value->doctor_id ? "selected" : "" ?> value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->doctor_name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>


                                    </div>
                                </div>
                            </div>
                       
                          
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Serial Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control" id="serial_numaber" value="<?php echo $doctor_serial->serial_numaber ?>" name="serial_numaber">

                                    </div>
                                </div>
                            </div>
                           
                   
                           
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Entry Date </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="datepicker" value="<?php echo date('d-m-Y', strtotime($doctor_serial->entry_date)) ?>" name="entry_date">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <fieldset style="clear:left;background-color:whitesmoke">
                            <legend>Reference</legend>
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Reference Director</label>
                                        <div class="col-sm-8">
                                            <select type="text" class="form-control" id="reference_director_id" name="reference_director_id">
                                                <option value="">Select Reference Director</option>
                                                <?php
                                                $share_holders = $this->db->select('*')->get('share_holder')->result();
                                                foreach ($share_holders as $share_holder) {
                                                ?>
                                                    <option <?php echo $share_holder->share_holder_id == $doctor_serial->reference_director_id ? 'selected' : '' ?> value="<?php echo $share_holder->share_holder_id ?>"><?php echo $share_holder->name . '-' . $share_holder->unique_id ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Reference Doctor</label>
                                        <div class="col-sm-8">
                                            <select type="text" class="form-control" id="reference_doctor_id" name="reference_doctor_id">


                                                <option value="">Select Doctor</option>
                                                <?php
                                                $doctors = $this->db->select('*')->get('doctor')->result();
                                                foreach ($doctors as $doctor_value) {
                                                ?>
                                                    <option <?php echo $doctor_value->doctor_id == $doctor_serial->reference_doctor_id ? 'selected' : '' ?> value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->doctor_name ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Reference Media</label>
                                        <div class="col-sm-8">
                                            <select type="text" class="form-control" id="reference_media_id" name="reference_media_id">
                                                <option value="">Select Reference Media</option>
                                                <?php
                                                $reference_media = $this->db->select('*')->get('reference_media')->result();
                                                foreach ($reference_media as $reference_media_value) {
                                                ?>
                                                    <option <?php echo $reference_media_value->reference_media_id == $doctor_serial->reference_media_id ? 'selected' : '' ?> value="<?php echo $reference_media_value->reference_media_id ?>"><?php echo $reference_media_value->reference_media_name ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Reference Officer</label>
                                        <div class="col-sm-8">
                                            <select style="width:100% ;" type="text" class="form-control" id="reference_employee_id" name="reference_employee_id">
                                                <option selected="" value="" disabled="">Select Employee</option>
                                                <?php
                                                $employees = $this->db->select('*')->get('employee')->result();
                                                foreach ($employees as $employee) {
                                                ?>
                                                    <option <?php echo $employee->employee_id == $doctor_serial->reference_employee_id ? 'selected' : '' ?> value="<?php echo $employee->employee_id; ?>"><?php echo $employee->employee_name . '-' . $employee->employee_unique_id ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-offset-4 col-sm-4">
                                        <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Update</button>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <img src="<?php echo base_url() ?>images/ajax-loader.gif" id="img" style="display:none" />
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>

</div>