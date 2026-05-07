<script type="text/javascript">
    function patient_data_set(patient_unique_id) {
        $('#img').show();
        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var patient_discharge_details = xhttp.responseText;
                var patient_discharge_details_array = patient_discharge_details.split('*');
                //alert(patient_discharge_details_array);
                document.getElementById("total_duration_day").value = patient_discharge_details_array[0];
                document.getElementById("total_duration_hours").value = patient_discharge_details_array[1];
                document.getElementById("total_bill").value = Number(patient_discharge_details_array[2]).toFixed(2);
                document.getElementById("cabin_or_bed_bill").value = Number(patient_discharge_details_array[3]).toFixed(2);
                document.getElementById("test_bill").value = Number(patient_discharge_details_array[4]).toFixed(2);
                document.getElementById("pharmachy_bill").value = Number(patient_discharge_details_array[5]).toFixed(2);
                document.getElementById("phygiotherapy_bill").value = Number(patient_discharge_details_array[6]).toFixed(2);
                document.getElementById("admission_and_other_bill").value = Number(patient_discharge_details_array[7]).toFixed(2);
                document.getElementById("emergency_bill").value = Number(patient_discharge_details_array[8]).toFixed(2);
                document.getElementById("patient_name").value = patient_discharge_details_array[9];
                document.getElementById("mobile_number").value = patient_discharge_details_array[10];
                document.getElementById("age").value = patient_discharge_details_array[11];
                document.getElementById("director_discount").value = Number(patient_discharge_details_array[12]).toFixed(2);
                document.getElementById("payable").value = Number(patient_discharge_details_array[13]).toFixed(2);
                document.getElementById("admission_date").value = patient_discharge_details_array[14];
                document.getElementById("admission_time").value = patient_discharge_details_array[15];
                document.getElementById("ipd_patient_id").value = patient_discharge_details_array[16];
                document.getElementById("reference_director_id").value = patient_discharge_details_array[17];
                document.getElementById("previous_paid").value = patient_discharge_details_array[18];
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('DischargeController/patient_discharge_data_load_by_unique_id'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("patient_unique_id=" + patient_unique_id);
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
        $('#special_discount').focus();

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
                return false;
            }
        });
    });


    $(document).ready(function() {
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
                    url: "<?php echo base_url('DischargeController/save_discharge_bill_data'); ?>",
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
        var special_discount = $('#special_discount').val();
        var payable = $('#payable').val();
        var netPayable = 0;

        // Handle percentage-based discounts
        if (special_discount.includes("%")) {
            var discount_percentage = special_discount.split("%")[0]; // Extract the number part
            var total_discount = (payable / 100) * Number(discount_percentage);
            netPayable = Math.ceil(Number(payable) - total_discount);
        } else {
            // Handle flat discount
            netPayable = Math.ceil(Number(payable) - Number(special_discount));
        }

        // Update net_payable and paid fields
        $('#net_payable').val(netPayable);
        $('#paid').val(netPayable);
        var paid = $('#paid').val() ? Number($('#paid').val()) : 0; // Default to 0 if empty

        // Calculate and update due field
        var due = Math.ceil(netPayable - paid);
        $('#due').val(due);
    }

    // Event listener for updating due when paid amount changes
    $(document).on('input', '#paid', function() {
        var netPayable = Number($('#net_payable').val());
        var paid = $(this).val() ? Number($(this).val()) : 0; // Default to 0 if empty
        var due = Math.ceil(netPayable - paid);
        $('#due').val(due);
    });


    function due_calculate() {
        var net_payable = $('#net_payable').val();
        var paid = $('#paid').val();
        var vat = $('#vat').val();
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
            <h3 style="text-align: center">Bill Payment</h3>
        </div>
        <?php
        $discharge = $this->db->where('discharge_id', $discharge_id)->get('discharge')->row();
        $patient = $this->db->where('ipd_patient_id', $discharge->ipd_patient_id)->get('ipd_patient')->row();
        ?>
        <div class="panel-body">

            <div class="row">
                <div class="col-md-12">
                    <form id="discharge_entry_form" class="form-horizontal" method="post" enctype='multipart/form-data'>

                        <input type="hidden" class="form-control" id="discount_reference_director_id" name="discount_reference_director_id" value="<?php echo $discharge->discount_reference_director_id ?>">
                        <input type="hidden" class="form-control" id="discount_reference_doctor_id" name="discount_reference_doctor_id" value="<?php echo $discharge->discount_reference_doctor_id ?>">
                        <input type="hidden" class="form-control" id="discount_reference_employee_id" name="discount_reference_employee_id" value="<?php echo $discharge->discount_reference_employee_id ?>">
                        <input type="hidden" class="form-control" id="discount_reference_media_id" name="discount_reference_media_id" value="<?php echo $discharge->discount_reference_media_id ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Patient ID *</label>
                                    <div class="col-sm-8">
                                        <input readonly value="<?php echo $discharge_id ?>" type="hidden" class="form-control" id="discharge_id" name="discharge_id">


                                        <input readonly value="<?php echo $discharge->patient_unique_id ?>" type="text" onchange="patient_data_set(this.value)" placeholder="Scan of Type IPD Patient Id.." class="form-control" id="patient_unique_id" name="patient_unique_id">
                                        <input value="<?php echo $discharge->ipd_patient_id ?>" type="hidden" class="form-control" id="ipd_patient_id" name="ipd_patient_id">
                                        <input type="hidden" readonly="" class="form-control" value="<?php echo $discharge->discharge_bill_id ?>" id="discharge_bill_id" name="discharge_bill_id">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Discharge Reason *</label>
                                    <div class="col-sm-8">
                                        <select type="text" class="form-control" id="discharge_reason_id" name="discharge_reason_id" disabled>
                                            <option disabled="" value="">Select Discharge Reason</option>
                                            <?php
                                            $discharge_reasons = $this->db->select('*')->get('discharge_reasons')->result();
                                            foreach ($discharge_reasons as $discharge_reason) {
                                            ?>
                                                <option <?php echo $discharge->discharge_reason_id == $discharge_reason->discharge_reason_id ? "selected" : "" ?> value="<?php echo $discharge_reason->discharge_reason_id ?>"><?php echo $discharge_reason->name ?></option>
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
                                    <label class="control-label col-sm-4" for="name">Patient Name *</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly value="<?php echo $patient->patient_name ?>" class="form-control" placeholder="Enter Name" id="patient_name" name="patient_name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Age *</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly value="<?php echo $patient->age ?>" class="form-control" placeholder="Enter Age" id="age" name="age">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Admission Date & Time </label>
                                    <div class="col-sm-4">
                                        <input type="text" readonly class="form-control" value="<?php echo $discharge->admission_date ?>" placeholder="Admission Date" id="admission_date" name="admission_date">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" readonly class="form-control" value="<?php echo $discharge->admission_time ?>" placeholder="Admission Time" id="admission_time" name="admission_time">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Mobile Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly required="" placeholder="Mobile Number" value="<?php echo $patient->mobile_number ?>" class="form-control" id="mobile_number" name="mobile_number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Discharge & and Time</label>
                                    <div class="col-sm-4">
                                        <input type="text" readonly class="form-control" value="<?php echo date('d-m-Y', strtotime($discharge->discharge_date)) ?>" placeholder="Discharge Date" id="discharge_date" name="discharge_date">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" readonly value="<?php echo  $discharge->discharge_time ?>" class="form-control" placeholder="Discharge Time" id="discharge_time" name="discharge_time">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Total Duration *</label>
                                    <div style="width: 20%;float:left;margin-left:15px">
                                        <input type="text" readonly required="" value="<?php echo  $discharge->total_duration_day ?>" class="form-control" id="total_duration_day" name="total_duration_day">
                                    </div>
                                    <div style="width: 10%;float:left"><span style="padding-left: 3px;margin-top:20px;">Days</span></div>
                                    <div style="width: 20%;float:left">
                                        <input type="text" readonly required="" class="form-control" value="<?php echo  $discharge->total_duration_hours ?>" id="total_duration_hours" name="total_duration_hours">

                                    </div>
                                    <div style="width: 10%;float:left"><span style="padding-left: 3px;margin-top:20px;">Hours</span></div>

                                </div>
                            </div>
                        </div>
                        <fieldset style="background-color:whitesmoke;margin-top:30px;">
                            <legend>Bill</legend>
                            <div class="row" style="margin-top:20px;">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" id="cabin_or_bed" for="pwd"><?php echo  $discharge->bed_or_cabin_charge ?></label>
                                        <div class="col-sm-8">
                                            <input readonly placeholder="Cabin/Bed Rent" type="text" class="form-control" value="<?php echo  $discharge->cabin_or_bed_bill ?>" id="cabin_or_bed_bill" name="cabin_or_bed_bill">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Pharmacy Bill*</label>
                                        <div class="col-sm-8">
                                            <input readonly placeholder="Pharmacy Bill Amount" type="text" class="form-control" value="<?php echo  $discharge->pharmachy_bill ?>" required="" id="pharmachy_bill" name="pharmachy_bill">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Test Bill*</label>
                                        <div class="col-sm-8">
                                            <input readonly placeholder="Test Bill Amount" type="text" class="form-control" value="<?php echo  $discharge->test_bill ?>" required="" id="test_bill" name="test_bill">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Physiotherapy Bill*</label>
                                        <div class="col-sm-8">
                                            <input readonly placeholder="Physiotherapy Bill Amount" type="text" class="form-control" value="<?php echo  $discharge->phygiotherapy_bill ?>" required="" id="phygiotherapy_bill" name="phygiotherapy_bill">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Emergency Bill*</label>
                                        <div class="col-sm-8">
                                            <input readonly placeholder="Emergency Bill Amount" type="text" class="form-control" value="<?php echo  $discharge->emergency_bill ?>" required="" id="emergency_bill" name="emergency_bill">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Admission and Other Services*</label>
                                        <div class="col-sm-8">
                                            <input readonly placeholder="Admission and Other Services" type="text" class="form-control" value="<?php echo  $discharge->admission_and_other_bill ?>" required="" id="admission_and_other_bill" name="admission_and_other_bill">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Total Bill*</label>
                                        <div class="col-sm-8">
                                            <input readonly placeholder="Total Bill Amount" type="text" class="form-control" value="<?php echo  $discharge->total_bill ?>" required="" id="total_bill" name="total_bill">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Advance Paid</label>
                                        <div class="col-sm-8">
                                            <input readonly placeholder="Advance Paid" type="text" class="form-control" value="<?php echo  $discharge->previous_paid ?>" id="previous_paid" name="previous_paid">
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Director Discount</label>
                                        <div class="col-sm-8">
                                            <input readonly placeholder="Director Discount Amount" type="text" class="form-control" value="<?php echo  $discharge->director_discount ?>" id="director_discount" name="director_discount">
                                            <input type="hidden" class="form-control" id="reference_director_id" name="reference_director_id">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Payable*</label>
                                        <div class="col-sm-8">
                                            <input readonly placeholder="Payable Amount" type="text" class="form-control" value="<?php echo  $discharge->payable ?>" required="" id="payable" name="payable">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Special Discount</label>
                                        <div class="col-sm-8">
                                            <input placeholder="Special Discount Amount" onkeydown="validateNumberInputWithPercentage(event)" oninput="net_payable_calculate()" type="text" class="form-control" value="<?php echo  $discharge->special_discount ?>" id="special_discount" name="special_discount">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Net Payable*</label>
                                        <div class="col-sm-8">
                                            <input readonly placeholder="Net Payable Amount" type="text" class="form-control" value="<?php echo  $discharge->net_payable ?>" id="net_payable" name="net_payable">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Paid</label>
                                        <div class="col-sm-8">
                                            <input placeholder="Enter Paid Amount" onkeydown="validateNumberInput(event)" oninput="due_calculate(this.value)" type="text" class="form-control" value="<?php echo  $discharge->paid ?>" id="paid" name="paid">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Payment Method*</label>
                                        <div class="col-sm-4">
                                            <select onchange="show_bank(this.value)" type="text" class="form-control" id="payment_method_id" name="payment_method_id">
                                                <option disabled="" value="">Select Payment Method</option>
                                                <?php
                                                $payment_methods = $this->db->select('*')->get('payment_methods')->result();
                                                foreach ($payment_methods as $payment_method) {
                                                ?>
                                                    <option <?php echo $discharge->payment_method_id == $payment_method->payment_method_id ? "selected" : "" ?> value="<?php echo $payment_method->payment_method_id ?>"><?php echo $payment_method->name ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <?php
                                        $bank_container = '';
                                        $mobile_banking_container = '';
                                        $check_details_container = '';

                                        if ($discharge->payment_method_id == 1) {
                                            $bank_container = 'none';
                                            $mobile_banking_container = 'none';
                                            $check_details_container = 'none';
                                        } elseif ($discharge->payment_method_id == 2) {
                                            $bank_container = 'none';
                                            $mobile_banking_container = 'block';
                                            $check_details_container = 'none';
                                        } elseif ($discharge->payment_method_id == 3) {
                                            $bank_container = 'block';
                                            $mobile_banking_container = 'none';
                                            $check_details_container = 'none';
                                        } elseif ($discharge->payment_method_id == 4) {
                                            $bank_container = 'none';
                                            $mobile_banking_container = 'none';
                                            $check_details_container = 'block';
                                        }

                                        ?>
                                        <div class="col-sm-4">
                                            <div class="form-group" id="bank_container" style="display:<?php echo $bank_container ?>">
                                                <select style="width: 170px;" type="text" class="form-control" id="bank_account_id" name="bank_account_id ">
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
                                            <div class="form-group" id="mobile_banking_container" style="display:<?php echo $mobile_banking_container ?>">


                                                <select style="width: 170px;" type="text" class="form-control" id="mobile_bank_id" name="mobile_bank_id ">
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
                                            <div class="form-group" id="check_details_container" style="display:<?php echo $check_details_container ?>">


                                                <textarea style="width: 170px;" placeholder="Enter Check Details" type="text" class="form-control" value="<?php echo  $discharge->director_discount ?>" id="check_details" name="check_details"></textarea>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Due</label>
                                        <div class="col-sm-8">
                                            <input placeholder="Enter Amount" readonly type="text" class="form-control" value="<?php echo  $discharge->due ?>" id="due" name="due">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea placeholder="Enter Remarks" type="text" class="form-control" value="" id="remarks" name="remarks"><?php echo  $discharge->remarks ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-sm-4" for="discount_reference">Discount Reference</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="discount_reference" placeholder="Enter Discount Ref.." name="discount_reference">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd"></label>
                                        <div class="col-sm-8">
                                            <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Update</button>
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
