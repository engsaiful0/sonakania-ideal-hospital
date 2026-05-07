<script type="text/javascript">
    function patient_data_set(patient_unique_id) {
        $('#img').show();
        var discharge_date = $('#discharge_date').val();
        var discharge_time_hour = $('#discharge_time_hour').val();
        var discharge_time_minute = $('#discharge_time_minute').val();
        var discharge_time_second = $('#discharge_time_second').val();
        var discharge_time_meridian = $('#discharge_time_meridian').val();
        var discharge_time = discharge_time_hour + ":" + discharge_time_minute + ":" + discharge_time_second + " " + discharge_time_meridian;
        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var patient_discharge_details = xhttp.responseText;
                var patient_discharge_details_array = patient_discharge_details.split('*');
                //alert(patient_discharge_details_array);
                document.getElementById("total_duration_day").value = patient_discharge_details_array[0];
                document.getElementById("total_duration_hours").value = patient_discharge_details_array[1];
                document.getElementById("total_bill").value = patient_discharge_details_array[2] != 0 ? Number(patient_discharge_details_array[2]).toFixed(2) : '0';
                document.getElementById("cabin_or_bed_bill").value = patient_discharge_details_array[3] != 0 ? Number(patient_discharge_details_array[3]).toFixed(2) : '0';
                document.getElementById("test_bill").value = patient_discharge_details_array[4] != 0 ? Number(patient_discharge_details_array[4]).toFixed(2) : '0';
                document.getElementById("pharmachy_bill").value = patient_discharge_details_array[5] != 0 ? Number(patient_discharge_details_array[5]).toFixed(2) : '0';
                document.getElementById("phygiotherapy_bill").value = patient_discharge_details_array[6] != 0 ? Number(patient_discharge_details_array[6]).toFixed(2) : '0';
                document.getElementById("admission_and_other_bill").value = patient_discharge_details_array[7] != 0 ? Number(patient_discharge_details_array[7]).toFixed(2) : '0';
                document.getElementById("emergency_bill").value = patient_discharge_details_array[8] != 0 ? Number(patient_discharge_details_array[8]).toFixed(2) : '0';
                document.getElementById("patient_name").value = patient_discharge_details_array[9];
                document.getElementById("mobile_number").value = patient_discharge_details_array[10];
                // document.getElementById("age").value = patient_discharge_details_array[11];
                document.getElementById("director_discount").value = Number(patient_discharge_details_array[12]).toFixed(2);

                document.getElementById("payable").value = Number(patient_discharge_details_array[13]).toFixed(2);
                document.getElementById("net_payable").value = Number(patient_discharge_details_array[13]).toFixed(2);
                document.getElementById("due").value = Number(patient_discharge_details_array[13]).toFixed(2);
                // document.getElementById("paid").value = Number(patient_discharge_details_array[13]).toFixed(2);

                document.getElementById("admission_date").value = patient_discharge_details_array[14];
                document.getElementById("admission_time").value = patient_discharge_details_array[15];
                document.getElementById("ipd_patient_id").value = patient_discharge_details_array[16];
                document.getElementById("reference_director_id").value = patient_discharge_details_array[17];
                document.getElementById("previous_paid").value = patient_discharge_details_array[18];
                document.getElementById("ot_service_bill").value = patient_discharge_details_array[20];

                document.getElementById("service_charge").value = patient_discharge_details_array[21];
                document.getElementById("consultant_fee").value = patient_discharge_details_array[22];
                document.getElementById("assistatnt_fee").value = patient_discharge_details_array[23];
                document.getElementById("admission_reg_fee").value = patient_discharge_details_array[24];


                document.getElementById("age_year").value = patient_discharge_details_array[25];
                document.getElementById("age_month").value = patient_discharge_details_array[26];
                document.getElementById("age_day").value = patient_discharge_details_array[27];
                document.getElementById("cabin_or_bed_bill_show").innerHTML = patient_discharge_details_array[28];
                document.getElementById("bed_or_cabin_charge").value = patient_discharge_details_array[28];
                document.getElementById("extra_hours_bill").value = patient_discharge_details_array[29];

                $('#special_discount').focus();
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('DischargeController/patient_discharge_data_load_by_unique_id'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("patient_unique_id=" + patient_unique_id + "&discharge_time=" + discharge_time + "&discharge_date=" + discharge_date);
    }

    function show_bank(payment_method_id) {
        if (payment_method_id == 3) {
            document.getElementById('bank_container').style.display = "block";
            document.getElementById('mobile_banking_container').style.display = "none";
            document.getElementById('check_details_container').style.display = "none";
        } else if (payment_method_id == 2) {
            document.getElementById('bank_container').style.display = "none";
            document.getElementById('mobile_banking_container').style.display = "block";
            document.getElementById('check_details_container').style.display = "none";
        } else if (payment_method_id == 1) {
            document.getElementById('bank_container').style.display = "none";
            document.getElementById('mobile_banking_container').style.display = "none";
            document.getElementById('check_details_container').style.display = "none";
        } else if (payment_method_id == 4) {
            document.getElementById('bank_container').style.display = "none";
            document.getElementById('mobile_banking_container').style.display = "none";
            document.getElementById('check_details_container').style.display = "block";
        }
    }
</script>
<script>
    $(document).ready(function() {
        $('#discharge_reason_id').select2();
        $('#payment_method_id').select2();
        $('#mobile_bank_id').select2();
        $('#bank_account_id').select2();
        $('#patient_unique_id').focus();
        $('#discharge_time_hour').select2();
        $('#discharge_time_minute').select2();
        $('#discharge_time_second').select2();
        $('#discharge_time_meridian').select2();

        $("#patient_unique_id").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('DischargeController/undischarged_patient_unique_id_load'); ?>",
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
                patient_data_set(ui.item.value);
                return false;
            }
        });
    });


    $(document).ready(function() {

        $("#discount_reference").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('OpdPatientController/discount_reference_load'); ?>",
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
                $('#discount_reference').val(ui.item.label);

                // Clear all reference IDs first
                $('#discount_reference_doctor_id').val('');
                $('#discount_reference_employee_id').val('');
                $('#discount_reference_media_id').val('');
                $('#discount_reference_director_id').val('');

                // Set the appropriate ID based on type
                if (ui.item.type === 'doctor') {
                    $('#discount_reference_doctor_id').val(ui.item.value);
                } else if (ui.item.type === 'director') {
                    $('#discount_reference_director_id').val(ui.item.value);
                } else if (ui.item.type === 'employee') {
                    $('#discount_reference_employee_id').val(ui.item.value);
                } else if (ui.item.type === 'reference_media') {
                    $('#discount_reference_media_id').val(ui.item.value);
                }

                return false;
            }
        });

        // Validate the form
        $("#discharge_entry_form").validate({
            rules: {
                patient_unique_id: "required",
                discharge_reason: "required",
                total_amount: "required",
            },
            messages: {
                patient_unique_id: "Please select patient unique ID",
                discharge_reason: "Please select discharge reason",
                total_amount: "Please Enter Total Amount",
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {

            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#discharge_entry_form').serialize();

            // Check if the form is valid
            if ($("#discharge_entry_form").valid()) {
                $('#discharge_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('DischargeController/save_discharge_data'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $.toast({
                                heading: 'Success',
                                text: 'Data has been saved successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });

                            $('#discharge_entry_form')[0].reset();
                            $('#discharge_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('print-discharge-bill') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#discharge_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#discharge_entry_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });

    function net_payable_calculate() {
        //
        var special_discount = $('#special_discount').val();
        if (special_discount.includes("%")) {
            var discount_percentage = special_discount.split("%");
            console.log('discount_percentage', discount_percentage);
            var total_discount = discount_percentage[0];
            var payable = $('#payable').val();
            total_discount = (payable / 100) * Number(total_discount);
            $('#net_payable').val(Math.ceil(Number(payable) - Number(total_discount)));
            $('#paid').val(Math.ceil(Number(payable) - Number(total_discount)));

        } else {
            var payable = $('#payable').val();
            $('#net_payable').val(Math.ceil(Number(payable) - Number(special_discount)));
            $('#paid').val(Math.ceil(Number(payable) - Number(special_discount)));
        }
        due_calculate();

    }

    function due_calculate() {
        var net_payable = $('#net_payable').val();
        var paid = $('#paid').val();
        if (Number(net_payable) < Number(paid)) {
            alert("Paid amount can not be greater than net total");
            $('#paid').val();
        } else {
            $('#due').val(Number(net_payable) - Number(paid));
        }
    }
</script>

<div class="container-fluid" style=" background-color: white;width: 98%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add Discharge</h3>
        </div>

        <div class="panel-body">

            <div class="row">
                <div class="col-md-12">
                    <form id="discharge_entry_form" class="form-horizontal" method="post" enctype='multipart/form-data'>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Discharge Date *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo date('d-m-Y') ?>" placeholder="Discharge Date" id="discharge_date" name="discharge_date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Patient Name *</label>
                                    <div class="col-sm-8">
                                        <input readonly type="text" class="form-control" placeholder="Enter Name" id="patient_name" name="patient_name">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Discharge Time *</label>
                                    <?php
                                    date_default_timezone_set('Asia/Dhaka');
                                    $currentHour = date('h'); // 12-hour format with leading zeros
                                    $currentMinute = date('i');
                                    $currentSecond = date('s');
                                    $currentMeridian = date('A'); // AM or PM
                                    ?>


                                    <div class="col-sm-2">
                                        <select class="form-control" title="Hour" id="discharge_time_hour" name="discharge_time_hour">
                                            <?php
                                            for ($i = 1; $i <= 12; $i++) {
                                                $val = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                echo "<option value='$val'" . ($val == $currentHour ? " selected" : "") . ">$val</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-sm-2">
                                        <select class="form-control" title="Minute" id="discharge_time_minute" name="discharge_time_minute">
                                            <?php
                                            for ($i = 0; $i < 60; $i++) {
                                                $val = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                echo "<option value='$val'" . ($val == $currentMinute ? " selected" : "") . ">$val</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-sm-2">
                                        <select class="form-control" title="Minute" id="discharge_time_second" name="discharge_time_second">
                                            <?php
                                            for ($i = 0; $i < 60; $i++) {
                                                $val = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                echo "<option value='$val'" . ($val == $currentSecond ? " selected" : "") . ">$val</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-sm-2">
                                        <select class="form-control" title="AM or PM" id="discharge_time_meridian" name="discharge_time_meridian">
                                            <option value="AM" <?php if ($currentMeridian == 'AM') echo "selected"; ?>>AM</option>
                                            <option value="PM" <?php if ($currentMeridian == 'PM') echo "selected"; ?>>PM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Admission Date</label>
                                    <div class="col-sm-8">
                                        <input readonly type="text" class="form-control" placeholder="Admission Date" id="admission_date" name="admission_date">
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Patient ID *</label>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="Scan of Type IPD Patient Id.." class="form-control" id="patient_unique_id" name="patient_unique_id">
                                        <input type="hidden" class="form-control" id="ipd_patient_id" name="ipd_patient_id">
                                        <input type="hidden" class="form-control" id="discount_reference_director_id" name="discount_reference_director_id">
                                        <input type="hidden" class="form-control" id="discount_reference_doctor_id" name="discount_reference_doctor_id">
                                        <input type="hidden" class="form-control" id="discount_reference_employee_id" name="discount_reference_employee_id">
                                        <input type="hidden" class="form-control" id="discount_reference_media_id" name="discount_reference_media_id">
                                        <?php

                                        $uniqu_id = $this->db->select('*')->order_by('id', 'DESC')->limit('1')->get('discharge_bill_id_table')->row();
                                        if (!$uniqu_id) {
                                            $uniqu_id = new stdClass();
                                            $uniqu_id->id_serial = 0; // Default value
                                        }
                                        $discharge_bill_id = 'D' . time() . '0' . $uniqu_id->id_serial + 1;
                                        ?>
                                        <input type="hidden" readonly="" class="form-control" value="<?php echo $uniqu_id->id_serial + 1 ?>" id="id_serial" name="id_serial">
                                        <input type="hidden" readonly="" class="form-control" value="<?php echo $discharge_bill_id ?>" id="discharge_bill_id" name="discharge_bill_id">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Admission Time </label>
                                    <div class="col-sm-8">
                                        <input readonly type="text" class="form-control" placeholder="Admission Time" id="admission_time" name="admission_time">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Discharge Reason *</label>
                                    <div class="col-sm-8">
                                        <select required type="text" class="form-control" id="discharge_reason_id" name="discharge_reason_id">
                                            <option value="">Select Discharge Reason</option>
                                            <?php
                                            $discharge_reasons = $this->db->select('*')->get('discharge_reasons')->result();
                                            foreach ($discharge_reasons as $discharge_reason) {
                                            ?>
                                                <option value="<?php echo $discharge_reason->discharge_reason_id ?>"><?php echo $discharge_reason->name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label class="control-label col-sm-3" for="name">Age</label>
                                    <div class="col-sm-3">
                                        <input readonly type="text" placeholder="Year" oninput="validateIntegerInput(this)" class="form-control" id="age_year" name="age_year">
                                    </div>
                                    <div class="col-sm-3">
                                        <input readonly type="text" placeholder="Month" oninput="validateIntegerInput(this)" class="form-control" id="age_month" name="age_month">
                                    </div>
                                    <div class="col-sm-3">
                                        <input readonly type="text" placeholder="Day" oninput="validateIntegerInput(this)" class="form-control" id="age_day" name="age_day">
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px;">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Total Duration *</label>
                                    <div style="width: 20%;float:left;margin-left:15px">
                                        <input readonly type="text" readonly required="" class="form-control" id="total_duration_day" name="total_duration_day">
                                    </div>
                                    <div style="width: 10%;float:left"><span style="padding-left: 3px;margin-top:20px;">Days</span></div>
                                    <div style="width: 20%;float:left">
                                        <input readonly type="text" readonly required="" class="form-control" id="total_duration_hours" name="total_duration_hours">

                                    </div>
                                    <div style="width: 10%;float:left"><span style="padding-left: 3px;margin-top:20px;">Hours</span></div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Mobile Number</label>
                                    <div class="col-sm-8">
                                        <input readonly type="text" required="" placeholder="Mobile Number" class="form-control" id="mobile_number" name="mobile_number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <fieldset style="background-color:whitesmoke;margin-top:30px;">
                            <legend>Bill</legend>
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" id="cabin_or_bed" for="pwd"><span id="cabin_or_bed_bill_show">Cabin/Bed Rent</span></label>
                                        <div class="col-sm-6">

                                            <input placeholder="Cabin/Bed Rent" type="hidden" class="form-control" id="bed_or_cabin_charge" name="bed_or_cabin_charge">
                                            <input readonly placeholder="Cabin/Bed Rent" type="text" class="form-control" id="cabin_or_bed_bill" name="cabin_or_bed_bill">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Pharmacy Bill*</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="Pharmacy Bill Amount" type="text" class="form-control" id="pharmachy_bill" name="pharmachy_bill">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Test Bill*</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="Test Bill Amount" type="text" class="form-control" id="test_bill" name="test_bill">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top:20px;">


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Physiotherapy Bill*</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="Physiotherapy Bill" type="text" class="form-control" id="phygiotherapy_bill" name="phygiotherapy_bill">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Emergency Bill*</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="Emergency Bill " type="text" class="form-control" id="emergency_bill" name="emergency_bill">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Services Fee*</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="Other Services" type="text" class="form-control" id="admission_and_other_bill" name="admission_and_other_bill">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="clear:left;margin-top:20px;">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">OT Bill*</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="OT Amount" type="text" class="form-control" id="ot_service_bill" name="ot_service_bill">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Admission Reg Fee</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="Admission Reg Fee" type="text" class="form-control" id="admission_reg_fee" name="admission_reg_fee">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Advance Paid</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="Advance Paid" type="text" class="form-control" id="previous_paid" name="previous_paid">
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="row" style="clear:left;margin-top:20px;">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Assistant Fee*</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="Assistant Fee" type="text" class="form-control" id="assistatnt_fee" name="assistatnt_fee">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Consultant Fee*</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="Consultant Fee" type="text" class="form-control" id="consultant_fee" name="consultant_fee">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Service Charge</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="Service Charge" type="text" class="form-control" id="service_charge" name="service_charge">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Extra Hours Bill</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="Extra Hours Bill" type="text" class="form-control" id="extra_hours_bill" name="extra_hours_bill">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Total Bill*</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="Total Bill" type="text" class="form-control" id="total_bill" name="total_bill">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Director Discount</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="Director Discount Amount" type="text" class="form-control" id="director_discount" name="director_discount">
                                            <input type="hidden" class="form-control" id="reference_director_id" name="reference_director_id">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Payable*</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="Payable Amount" type="text" class="form-control" id="payable" name="payable">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Net Payable*</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="Net Payable Amount" type="text" class="form-control" id="net_payable" name="net_payable">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row" style="margin-top:20px;">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Special Discount</label>
                                        <div class="col-sm-6">
                                            <input onkeydown="validateNumberInputWithPercentage(event)" placeholder="Special Discount Amount" oninput="net_payable_calculate()" type="text" class="form-control" value="" id="special_discount" name="special_discount">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Paid</label>
                                        <div class="col-sm-6">
                                            <input onkeydown="validateNumberInput(event)" placeholder="Enter Paid Amount" oninput="due_calculate(this.value)" type="text" class="form-control" value="" id="paid" name="paid">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Payment Method*</label>
                                        <div class="col-sm-6">
                                            <select onchange="show_bank(this.value)" type="text" class="form-control" id="payment_method_id" name="payment_method_id">
                                                <option disabled="" value="">Select Payment Method</option>
                                                <?php
                                                $payment_methods = $this->db->select('*')->get('payment_methods')->result();
                                                foreach ($payment_methods as $payment_method) {
                                                ?>
                                                    <option value="<?php echo $payment_method->payment_method_id ?>"><?php echo $payment_method->name ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group" id="bank_container" style="display:none">
                                                <select style="width: 170px;" type="text" class="form-control" id="bank_account_id" name="bank_account_id">
                                                    <option selected="" disabled="" value="">Select Bank Account</option>
                                                    <?php
                                                    $bank_accounts = $this->db->select('*')->get('bank_accounts')->result();
                                                    foreach ($bank_accounts as $bank_account) {
                                                        $bank_name = $this->db->where('bank_name_id', $bank_account->bank_name_id)->get('bank_name')->row();
                                                    ?>
                                                        <option value="<?php echo $bank_account->bank_name_id ?>"><?php echo $bank_name->name . '-' . $bank_account->account_number . '-' . $bank_account->account_name ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group" id="mobile_banking_container" style="display:none">


                                                <select style="width: 170px;" type="text" class="form-control" id="mobile_bank_id" name="mobile_bank_id">
                                                    <option selected="" disabled="" value="">Select Mobile Banking</option>
                                                    <?php
                                                    $mobile_banks = $this->db->select('*')->get('mobile_banks')->result();
                                                    foreach ($mobile_banks as $mobile_bank) {

                                                    ?>
                                                        <option value="<?php echo $mobile_bank->mobile_bank_id  ?>"><?php echo $mobile_bank->account_name . '-' . $mobile_bank->account_number . '-' . $mobile_bank->type ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                            <div class="form-group" id="check_details_container" style="display:none">


                                                <textarea style="width: 170px;" placeholder="Enter Check Details" type="text" class="form-control" value="" id="check_details" name="check_details"></textarea>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="control-label col-sm-6" for="discount_reference">Discount Reference</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="discount_reference" placeholder="Enter Discount Ref.." name="discount_reference">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd"></label>
                                        <div class="col-sm-6">
                                            <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Due</label>
                                        <div class="col-sm-6">
                                            <input readonly placeholder="Enter Amount" type="text" class="form-control" value="" id="due" name="due">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="pwd">Remarks</label>
                                        <div class="col-sm-6">
                                            <textarea placeholder="Enter Remarks" type="text" class="form-control" value="" id="remarks" name="remarks"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-2">
                                    <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>

            </div>

        </div>

    </div>

</div><?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
