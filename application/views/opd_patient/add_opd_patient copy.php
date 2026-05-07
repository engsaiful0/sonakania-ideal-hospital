<script>
    function payable_calculate() {
        var discount = $('#discount').val();
        var grand_total = 0;
        var visiting_fee = $('#visiting_fee').val();
        var length = discount.length;

        if (discount[length - 1] == '%') {
            discount = discount.split("%");
            grand_total = Math.ceil(visiting_fee - (visiting_fee * (Number(discount[0]) / 100)));
        } else {
            grand_total = Math.ceil(visiting_fee - discount);
        }
        $('#payable').val(Math.ceil(grand_total));
    }

    function visiting_fee_load(doctor_id) {
        $('#img').show();
        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("visiting_fee").value = xhttp.responseText;
                document.getElementById("payable").value = xhttp.responseText;

                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('PatientController/visiting_fee_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("doctor_id=" + doctor_id);
    }

    function select_visit_interval_fee(visiting_interval) {
        var doctor_id = Number($('#doctor_id').val());
        if (doctor_id == '') {
            $.toast({
                heading: 'Error',
                text: 'Please select a doctor.',
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 1000,
                icon: 'Error'
            });
            return false;
        }

        $('#img').show();
        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("visiting_fee").value = xhttp.responseText;
                document.getElementById("payable").value = xhttp.responseText;

                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('PatientController/select_visit_interval_fee'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("doctor_id=" + doctor_id + "&visiting_interval=" + visiting_interval);
    }

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
        xhttp.open("POST", "<?php echo site_url('PatientController/serial_load'); ?>", true);
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
    $().ready(function() {
        // validate the comment form when it is submitted       
        // validate signup form on keyup and submit
        $("#patient_form").validate({
            rules: {
                opd_patient_name: "required",
                doctor_id: "required",
                mobile_number: {
                    required: true,
                    minlength: 11
                },
                gender: "required",
                age: "required",
            },
            messages: {
                patient_name: "Please Enter Patient Name",
                doctor_id: "Please select a doctor",
                age: "Please enter age",
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
                    url: "<?php echo base_url('OpdPatientController/add_opd_patient_save'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {

                            $('#patient_form')[0].reset();
                            $('#patient_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            $.toast({
                                heading: 'Success',
                                text: 'Data has been saved successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('print-opd-patient') ?>";
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
    $(document).ready(function() {
        $('#gender').select2();

        $('#department_id').select2();
        $('#doctor_id').select2();
        $('#years_or_days').select2();
        $('#reference_doctor_id').select2();
        $('#reference_employee_id').select2();
        $('#reference_media_id').select2();
        $('#reference_director_id').select2();
        $('#visiting_interval').select2();

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

    function total_price_cal() {
        var quantity = $('#quantity').val();
        var unit_price = $('#unit_price').val();
        $('#total_price').val(Number(quantity) * Number(unit_price));
    }
</script>
<div class="container-fluid" style=" background-color: white;width: 98%;">

    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add OPD Patient</h3>
        </div>
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">

                    <form class="form-horizontal" id="patient_form" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Name *</label>
                                    <div class="col-sm-8">
                                        <input placeholder="Enter Patient Name" type="text" class="form-control" required="" id="opd_patient_name" name="opd_patient_name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Mobile </label>
                                    <div class="col-sm-8">
                                        <input type="text" oninput="validatePhoneNumberInput(this)" placeholder="Enter Mobile Number" class="form-control" id="mobile_number" name="mobile_number">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Patient ID </label>
                                    <div class="col-sm-8">

                                        <?php
                                        $uniqu_id = $this->db->select('*')->order_by('opd_patientunique_id', 'DESC')->limit('1')->get('opd_patient_unique_table')->row();
                                        if (!$uniqu_id) {
                                            $uniqu_id = new stdClass();
                                            $uniqu_id->id_serial = 0; // Default value
                                        }
                                        $patient_unique_id = 'OPD' . time() . '0'  . $uniqu_id->id_serial + 1;
                                        ?>
                                        <input type="hidden" readonly="" class="form-control" value="<?php echo $uniqu_id->id_serial + 1 ?>" id="id_serial" name="id_serial">
                                        <input type="text" readonly="" class="form-control" value="<?php echo $patient_unique_id ?>" id="opd_patient_unique_id" name="opd_patient_unique_id">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Age *</label>
                                    <div class="col-sm-4">
                                        <input type="text" placeholder="Enter Age" oninput="validateIntegerInput(this)" class="form-control" id="age" name="age">

                                    </div>
                                    <div class="col-sm-4">
                                        <select type="text" class="form-control" id="years_or_days" name="years_or_days">
                                            <option>Years</option>
                                            <option>Months</option>
                                            <option>Days</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Gender *</label>
                                    <div class="col-sm-8">
                                        <select type="text" required="" class="form-control" id="gender" name="gender">
                                            <option selected="" disabled="" value="">Select Gender</option>
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
                                        <input type="text" placeholder="Enter Address" class="form-control" id="address" name="address">
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
                                            <option selected="" disabled="" value="">Select Department</option>
                                            <?php
                                            $department = $this->db->where('type', 'Clinical')->order_by('department_name', 'ASC')->get('department')->result();
                                            foreach ($department as $department_value) {
                                            ?>
                                                <option value="<?php echo $department_value->department_id ?>"><?php echo $department_value->department_name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Doctor *</label>
                                    <div class="col-sm-8">
                                        <select type="text" required="" class="form-control" id="doctor_id" onchange="visiting_fee_load(this.value), serial_load(this.value)" name="doctor_id">
                                            <option selected="" disabled="" value="">Select Doctor</option>
                                            <?php
                                            $doctor = $this->db->select('*')->get('doctor')->result();
                                            foreach ($doctor as $doctor_value) {
                                            ?>
                                                <option value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->doctor_name ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>


                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Visiting Fee </label>
                                    <div class="col-sm-4">
                                        <input type="text" oninput="validateInputFloatingPoint(this)" placeholder="Visiting Fee" class="form-control" id="visiting_fee" name="visiting_fee">
                                    </div>
                                    <div class="col-sm-4">
                                        <select type="text" onchange="select_visit_interval_fee(this.value)" class="form-control" id="visiting_interval" name="visiting_interval">
                                            <option value="first_time">First Time</option>
                                            <option value="within_seven_days">Within 7 Days</option>
                                            <option value="within_fifty_days">Within 15 Days</option>
                                            <option value="within_thirty_days">Within 30 Days</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Visiting Date </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="datepicker2" value="<?php echo date('d-m-Y') ?>" name="visiting_date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Visiting Time </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="visiting_time" value="<?php echo date("h:i:s") ?>" name="visiting_time">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Serial Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control" id="serial_numaber" value="" name="serial_numaber">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin-top:20px;">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Discount</label>
                                    <div class="col-sm-8">
                                        <input type="text" oninput="validateInputFloatingPoint(this),payable_calculate()" placeholder="Enter Discount" class="form-control" id="discount" name="discount">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Payable </label>
                                    <div class="col-sm-8">
                                        <input type="text" oninput="validateInputFloatingPoint(this)" placeholder="Payable" class="form-control" id="payable" name="payable">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Entry Date </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="datepicker" value="<?php echo date('d-m-Y') ?>" name="entry_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Discount Reference</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="discount_reference" placeholder="Enter Discount Ref.." name="discount_reference">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4"></div>
                        </div>
                        <div style="clear: left;" class="container">
                            <fieldset style="background-color:whitesmoke;">
                                <legend>Reference</legend>
                                <div class="row" style="margin-top:20px;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="name">Reference Director</label>
                                            <div class="col-sm-8">
                                                <select type="text" class="form-control" id="reference_director_id" name="reference_director_id">
                                                    <option selected="" disabled="" value="">Select Reference Director</option>
                                                    <?php
                                                    $directors = $this->db->select('*')->get('director')->result();
                                                    foreach ($directors as $director) {
                                                    ?>
                                                        <option value="<?php echo $director->director_id ?>"><?php echo $director->director_name ?></option>
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top:20px;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="name">Reference Media</label>
                                            <div class="col-sm-8">
                                                <select type="text" class="form-control" id="reference_media_id" name="reference_media_id">
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
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="name">Reference Officer</label>
                                            <div class="col-sm-8">
                                                <select style="width:100% ;" type="text" class="form-control" id="reference_employee_id" name="reference_employee_id">
                                                    <option selected="" value="">Select Employee</option>
                                                    <?php
                                                    $employees = $this->db->select('*')->get('employee')->result();
                                                    foreach ($employees as $employee) {
                                                    ?>
                                                        <option value="<?php echo $employee->employee_id; ?>"><?php echo $employee->employee_name . '-' . $employee->employee_unique_id ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-offset-4 col-sm-4">
                                        <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Submit</button>
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