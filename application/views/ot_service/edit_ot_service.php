<script>
    $(document).ready(function() {
        $('#patient_unique_id').focus();
        var doctor_id_control = document.getElementById('doctor_id_control').value * 1;
        for (var counter = 0; counter <= Number(doctor_id_control); counter++) {
            $('#surgon_doctor_id_' + counter).select2();
        }
        var nurse_id_control = document.getElementById('nurse_id_control').value * 1;
        for (var counter = 0; counter <= Number(nurse_id_control); counter++) {
            $('#employee_nurse_id_' + counter).select2();
        }
        $('#surgery_id').select2();
        $('#anestasia_doctor_id').select2();
        $('#years_or_days').select2();
        $('#reference_employee_id').select2();
        $('#reference_media_id').select2();
        $('#reference_director_id').select2();
        $('#reference_doctor_id').select2();
    });

    function payable_calculate() {
        var price = $('#price').val();
        if (price == '') {
            $.toast({
                heading: 'Error',
                text: 'Please select a Surgery.',
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 1000,
                icon: 'Error'
            });
            return false;
        }
        var discount = $('#discount').val();
        var grand_total = 0;

        var length = discount.length;

        if (discount[length - 1] == '%') {
            discount = discount.split("%");
            grand_total = Math.ceil(price - (price * (Number(discount[0]) / 100)));
            $('#total_discount').val(Number(price * (Number(discount[0]) / 100)));
        } else {
            grand_total = Math.ceil(price - discount);
            $('#total_discount').val(Number(discount));
        }
        $('#net_price').val(Math.ceil(grand_total));

    }

    function ipd_service_price_load(ipd_service_item_id) {
        var id = ipd_service_item_id.split('_');
        var idIndex = Number(id[4]);

        var ipd_service_item_id_value = $('#' + ipd_service_item_id).val();
        console.log('ipd_service_item_id_value=', ipd_service_item_id_value);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                $('#price_' + idIndex).val(xhttp.responseText);
                $('#amount_' + idIndex).val(Number($('#price_' + idIndex).val()));
                totalamount();
            }
        }
        xhttp.open("POST", "<?php echo site_url('IpdServiceController/ipd_service_price_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhttp.send("ipd_service_item_id=" + ipd_service_item_id_value);

    }

    function surgery_price_load(surgery_id) {


        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                $('#price').val(xhttp.responseText);
            }
        }
        xhttp.open("POST", "<?php echo site_url('OTServiceController/surgery_price_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhttp.send("surgery_id=" + surgery_id);

    }

    function removetr(element, e) {
        var seq = $(element).attr('sequence');
        if (seq != 0)
            $(element).parent().parent().remove();

        sub_total();
    }

    function sub_total() {
        var amount = $(".amount");
        total = 0;
        total = Number(total);
        $.each(amount, function(k, elm) {
            var camount = $(elm).val();
            camount = Number(camount);
            total += camount;
        });
        $("#net_total").val(total.toFixed(2));
    }

    function addMoreDoctorRow() {
        var id_control = document.getElementById('doctor_id_control').value * 1;

        if (id_control > 10) {
            $.toast({
                heading: 'Warning',
                text: 'You have added maximum number of surgons.',
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 1000,
                icon: 'warning'
            });
        } else {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                    // $('#cart_tab1').prepend(xhttp.responseText);
                    var newdiv = document.createElement('tr');
                    newdiv.innerHTML = xhttp.responseText;
                    document.getElementById('doctor_table').appendChild(newdiv);
                    $("#surgon_doctor_id_" + id_control).select2();
                    var next_id = id_control + 1;
                    document.getElementById('doctor_id_control').value = next_id;

                }
            }

            xhttp.open("POST", "<?php echo site_url('OTServiceController/add_more_doctor_row'); ?>", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            //                                    xhttp.send("fname=Henry&lname=Ford");
            xhttp.send('next_id=' + id_control);
        }
    }

    function addMoreNurseRow() {
        var id_control = document.getElementById('nurse_id_control').value * 1;
        if (id_control > 10) {
            $.toast({
                heading: 'Warning',
                text: 'You have added maximum number of surgons.',
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 1000,
                icon: 'warning'
            });
        } else {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                    // $('#cart_tab1').prepend(xhttp.responseText);
                    var newdiv = document.createElement('tr');
                    newdiv.innerHTML = xhttp.responseText;
                    document.getElementById('nurse_table').appendChild(newdiv);
                    $("#employee_nurse_id_" + id_control).select2();
                    var next_id = id_control + 1;
                    document.getElementById('nurse_id_control').value = next_id;

                }
            }

            xhttp.open("POST", "<?php echo site_url('OTServiceController/add_more_nurse_row'); ?>", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            //                                    xhttp.send("fname=Henry&lname=Ford");
            xhttp.send('next_id=' + id_control);
        }
    }

    function patient_data_set(patient_unique_id) {
        $('#img').show();
        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var patient = xhttp.responseText;
                var patient_array = patient.split('*');
                //alert(patient_array);
                document.getElementById("ipd_patient_id").value = patient_array[0];
                document.getElementById("patient_name").value = patient_array[1];
                document.getElementById("mobile_number").value = patient_array[2];
                document.getElementById("age").value = patient_array[3];

                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('IpdPatientController/patient_data_load_by_unique_id'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("patient_unique_id=" + patient_unique_id);
    }

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
        // Validate the form
        $("#ipd_service_entry_form").validate({
            rules: {
                patient_name: "required",
                surgery_id: "required",
                age: "required",
            },
            messages: {
                patient_name: "Please patient name",
                age: "Please enter age",
                surgery_id: "Please select a surgery name",
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {

            e.preventDefault();

            var submitBtn = $(this);
            var formData = new FormData($('#ot_service_entry_form')[0]); // Create FormData object with form data

            // Check if the form is valid
            if ($("#ot_service_entry_form").valid()) {
                $('#ot_service_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('OTServiceController/ot_service_update'); ?>",
                    data: formData,
                    dataType: "json",
                    processData: false, // Important: tell jQuery not to process the data
                    contentType: false, // Important: tell jQuery not to set contentType
                    success: function(response) {
                        if (response.success) {
                            $.toast({
                                heading: 'Success',
                                text: 'Data has been updated successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });
                            $('#ot_service_entry_form')[0].reset();
                            $('#ot_service_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('print-ot-service') ?>";
                            }, 1002);

                        } else {
                            alert('Error: ' + response.message);
                            $('#ot_service_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#ot_service_entry_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });

    function checkDuplicate(currentDropdown) {
        // Get the selected value of the current dropdown
        const selectedValue = currentDropdown.value;

        // Flag to track if a duplicate is found
        let isDuplicate = false;

        // Iterate through all dropdowns with the name "surgon_doctor_id[]"
        $('select[name="surgon_doctor_id[]"]').each(function() {
            if ($(this).val() === selectedValue && this !== currentDropdown) {
                isDuplicate = true;
                return false; // Break the loop
            }
        });

        if (isDuplicate) {
            // Show error message
            $.toast({
                heading: 'Error',
                text: "This doctor is already selected. Please choose a different one.",
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 2000,
                icon: 'error'
            });

            // Reset the current dropdown
            $(currentDropdown).val('').trigger('change');
        }
    }

    function checkNurseDuplicate(currentDropdown) {
        // Get the selected value of the current dropdown
        const selectedValue = currentDropdown.value;

        // Flag to track if a duplicate is found
        let isDuplicate = false;

        // Iterate through all dropdowns with the name "employee_nurse_id[]"
        $('select[name="employee_nurse_id[]"]').each(function() {
            // Compare the value of each dropdown, excluding the current one
            if ($(this).val() === selectedValue && this !== currentDropdown) {
                isDuplicate = true;
                return false; // Break the loop
            }
        });

        if (isDuplicate) {
            // Show error message
            $.toast({
                heading: 'Error',
                text: "This nurse is already selected. Please choose a different one.",
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 2000,
                icon: 'error'
            });

            // Reset the current dropdown to "Select Nurse"
            $(currentDropdown).val('').trigger('change');
        }
    }
</script>

<div class="container-fluid" style=" background-color: white;width: 98%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Edit OT Service</h3>
        </div>

        <?php
        $ot_service = $this->db->where('ot_service_id', $ot_service_id)->get('ot_services')->row();

        $surgons = json_decode($ot_service->surgon_doctor_id);
        $surgons = isset($surgons) && is_array($surgons) ? $surgons : [];

        $nurses = json_decode($ot_service->employee_nurse_id);
        $nurses = isset($nurses) && is_array($nurses) ? $nurses : [];

        $ipd_patient = $this->db->where('ipd_patient_id', $ot_service->ipd_patient_id)->get('ipd_patient')->row();
        ?>
        <div class="panel-body">
            <form id="ot_service_entry_form" class="form-horizontal" method="post" enctype='multipart/form-data'>
                <input type="hidden" name="ot_service_id" value="<?php echo $ot_service_id ?>">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Patient ID *</label>
                            <div class="col-sm-8">
                                <input type="hidden" value="<?php echo $ot_service->ipd_patient_id ?>" class="form-control" id="ipd_patient_id" name="ipd_patient_id">
                                <input onchange="patient_data_set(this.value)" placeholder="Scan or Enter IPD Patient ID.." type="text" value="<?php echo $ot_service->patient_unique_id ?>" id="patient_unique_id" name="patient_unique_id" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Invoice ID </label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" class="form-control" value="<?php echo $ot_service->ot_service_unique_id ?>" id="ot_service_unique_id" name="ot_service_unique_id">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Date *</label>
                            <div class="col-sm-4">
                                <input type="text" required="" value="<?php echo date('d-m-Y', strtotime($ot_service->date)); ?>" class="form-control" id="datepicker" name="date">
                            </div>
                            <div class="col-sm-4">
                                <?php date_default_timezone_set('Asia/Dhaka'); ?>
                                <input type="text" class="form-control" id="time"
                                    value="<?php echo !empty($ot_service->time) ? $ot_service->time : date('h:i:s A'); ?>"
                                    name="time">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Patient Name*</label>

                            <div class="col-sm-8">
                                <input type="text" placeholder="Enter Patient Name" class="form-control" value="<?php echo !empty($ot_service->patient_name) ? $ot_service->patient_name : ''; ?>" id="patient_name" name="patient_name">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Mobile Number</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Enter Mobile Number" class="form-control" value="<?php echo !empty($ot_service->mobile_number) ? $ot_service->mobile_number : ''; ?>" id="mobile_number" name="mobile_number">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="name">Age</label>
                            <div class="col-sm-3">
                                <input type="text" placeholder="Year" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $ot_service->age_year ?>" id="age_year" name="age_year">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" placeholder="Month" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $ot_service->age_month ?>" id="age_month" name="age_month">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" placeholder="Day" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $ot_service->age_day ?>" id="age_day" name="age_day">
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top:20px;">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Address</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Enter Address" class="form-control" <?php echo !empty($ot_service->address) ? $ot_service->address : ''; ?> id="address" name="address">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Surgery*</label>
                            <div class="col-sm-8">
                                <select onchange="surgery_price_load(this.value)" name="surgery_id" class="form-control" id="surgery_id" required="">
                                    <option selected="" value="" disabled="">Select Surgery Name</option>
                                    <?php
                                    $surgeries = $this->db->select('*')->get('surgeries')->result();

                                    foreach ($surgeries as $surgery) {
                                    ?>
                                        <option <?php echo $ot_service->surgery_id == $surgery->surgery_id ? 'selected' : '' ?> value="<?php echo $surgery->surgery_id; ?>"><?php echo $surgery->name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Price</label>
                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Enter Price" value="<?php echo $ot_service->price ?>" class="form-control" id="price" name="price">
                            </div>
                        </div>
                    </div>

                </div>
                <div style="margin-top:20px;">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Discount</label>
                            <div class="col-sm-8">
                                <input type="text" oninput="payable_calculate()" placeholder="Enter Discount" class="form-control" value="<?php echo $ot_service->discount ?>" id="discount" name="discount">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="control-label col-sm-4" for="discount_reference">Dis. Reference</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="discount_reference" placeholder="Enter Discount Ref.." value="<?php echo $ot_service->discount_reference ?>" name="discount_reference">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label" for="discount">Total Discount</label>
                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Total Discount" class="form-control" id="total_discount" value="<?php echo $ot_service->total_discount ?>" name="total_discount">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px;">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Net Price</label>
                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Enter Price" class="form-control" id="net_price" value="<?php echo $ot_service->net_price ?>" name="net_price">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Paid</label>
                            <div class="col-sm-8">
                                <input type="text" oninput="due_cal()" placeholder="Enter Paid" class="form-control" value="<?php echo $ot_service->paid ?>" id="paid" name="paid">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label" for="discount">Due</label>
                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Due" class="form-control" id="due" value="<?php echo $ot_service->due ?>" name="due">
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin-top:20px;">
                    <div style="width: 50%;float:left">
                        <fieldset>
                            <legend>Doctor</legend>
                            <input type="hidden" value="<?php echo count($surgons) ?>" name="doctor_id_control" id="doctor_id_control" class="form-control">
                            <table class="table table-bordered" id="doctor_table">
                                <?php
                                for ($index = 0; $index < count($surgons); $index++) {
                                    $next_id  = $index;
                                ?>
                                    <tr>
                                        <td style="width:30%">
                                            <?php
                                            $assistant_surgon = '';
                                            if ($next_id == 0) {
                                                echo 'Main Surgon';
                                                $assistant_surgon = 'Main Surgon';
                                            } elseif ($next_id == 1) {
                                                echo '1st Ass. Surgon';
                                                $assistant_surgon = '1st Ass. Surgon';
                                            } elseif ($next_id == 2) {
                                                echo '2nd Ass. Surgon';
                                                $assistant_surgon = '2nd Ass. Surgon';
                                            } elseif ($next_id == 3) {
                                                echo '3rd Ass. Surgon';
                                                $assistant_surgon = '3rd Ass. Surgon';
                                            } elseif ($next_id == 4) {
                                                echo '4th Ass. Surgon';
                                                $assistant_surgon = '4th Ass. Surgon';
                                            } elseif ($next_id == 5) {
                                                echo '5th Ass. Surgon';
                                                $assistant_surgon = '5th Ass. Surgon';
                                            } elseif ($next_id == 6) {
                                                echo '6th Ass. Surgon';
                                                $assistant_surgon = '6th Ass. Surgon';
                                            } elseif ($next_id == 7) {
                                                echo '7th Ass. Surgon';
                                                $assistant_surgon = '7th Ass. Surgon';
                                            } elseif ($next_id == 8) {
                                                echo '8th Ass. Surgon';
                                                $assistant_surgon = '8th Ass. Surgon';
                                            } elseif ($next_id == 9) {
                                                echo '9th Ass. Surgon';
                                                $assistant_surgon = '9th Ass. Surgon';
                                            } elseif ($next_id == 10) {
                                                echo '10th Ass. Surgon';
                                                $assistant_surgon = '10th Ass. Surgon';
                                            }
                                            ?>
                                        </td>
                                        <td style="width:68%">
                                            <select style="width: 100%;" type="text" class="form-control" id="surgon_doctor_id_<?php echo $index ?>" name="surgon_doctor_id[]" onchange="checkDuplicate(this)">
                                                <option selected="" disabled="" value="">Select Reference Doctor</option>
                                                <?php
                                                $doctor = $this->db->select('*')->order_by('doctor_name', 'ASC')->get('doctor')->result();
                                                foreach ($doctor as $doctor_value) {
                                                ?>
                                                    <option <?php echo $surgons[$index] == $doctor_value->doctor_id ? 'selected' : '' ?> value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->doctor_name.' - '.$doctor_value->doctor_unique_id ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <?php
                                        if ($index == 0) {
                                        ?>
                                            <td style="width: 2%;">
                                                <input type="button" onclick="addMoreDoctorRow()" style="width:30px" readonly id="add_more" title="Click To Add" value="+">
                                            </td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="width: 2%;">
                                                <input style="width:30px" type="button" onclick="removetr(this, event)" style="width:50px" readonly id="add_more" title="Click TO Remove" value="-">
                                            </td>
                                        <?php
                                        }
                                        ?>

                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>

                        </fieldset>

                    </div>
                    <div style="width: 50%;float:left;">
                        <fieldset>
                            <legend>Nurse</legend>
                            <input type="hidden" value="<?php echo count($nurses) ?>" name="nurse_id_control" id="nurse_id_control" class="form-control">
                            <table class="table table-bordered" id="nurse_table">
                                <?php
                                for ($index = 0; $index < count($nurses); $index++) {
                                    $next_id = $index;
                                ?>
                                    <tr>
                                        <td style="width:30%">
                                            <?php
                                            $assistant_nurse = '';
                                            if ($next_id == 0) {
                                                echo 'Main Nurse';
                                                $assistant_surgon = '1st Ass. Nurse';
                                            } elseif ($next_id == 1) {
                                                echo '1st Ass. Nurse';
                                                $assistant_surgon = '1st Ass. Nurse';
                                            } elseif ($next_id == 2) {
                                                echo '2nd Ass. Nurse';
                                                $assistant_surgon = '2nd Ass. Nurse';
                                            } elseif ($next_id == 3) {
                                                echo '3rd Ass. Nurse';
                                                $assistant_surgon = '3rd Ass. Nurse';
                                            } elseif ($next_id == 4) {
                                                echo '4th Ass. Nurse';
                                                $assistant_surgon = '4th Ass. Nurse';
                                            } elseif ($next_id == 5) {
                                                echo '5th Ass. Nurse';
                                                $assistant_surgon = '5th Ass. Nurse';
                                            } elseif ($next_id == 6) {
                                                echo '6th Ass. Nurse';
                                                $assistant_surgon = '6th Ass. Nurse';
                                            } elseif ($next_id == 7) {
                                                echo '7th Ass. Nurse';
                                                $assistant_surgon = '7th Ass. Nurse';
                                            } elseif ($next_id == 8) {
                                                echo '8th Ass. Nurse';
                                                $assistant_surgon = '8th Ass. Nurse';
                                            } elseif ($next_id == 9) {
                                                echo '9th Ass. Nurse';
                                                $assistant_surgon = '9th Ass. Nurse';
                                            } elseif ($next_id == 10) {
                                                echo '10th Ass. Nurse';
                                                $assistant_surgon = '10th Ass. Nurse';
                                            }
                                            ?>
                                        </td>
                                        <td style="width:68%">
                                            <select style="width: 100%;" type="text" class="form-control" id="employee_nurse_id_<?php echo $index ?>" name="employee_nurse_id[]" onchange="checkNurseDuplicate(this)">
                                                <option selected="" disabled="" value="">Select Nurse</option>
                                                <?php
                                                $this->db->select('employee.*,men_power_categories.name as category_name,men_power_categories.men_power_category_id');
                                                $this->db->from('employee');
                                                $this->db->join('men_power_categories', 'men_power_categories.men_power_category_id = employee.men_power_category_id', 'left');
                                                $this->db->where('men_power_categories.name', 'Nurse');
                                                $nurses_data = $this->db->get()->result();

                                                foreach ($nurses_data as $nurse) {
                                                ?>
                                                    <option <?php echo $nurses[$index] == $nurse->employee_id ? 'selected' : '' ?> value="<?php echo $nurse->employee_id ?>"><?php echo $nurse->employee_name . '-' . $nurse->employee_unique_id ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <?php
                                        if ($index == 0) {
                                        ?>
                                            <td style="width: 2%;"><input type="button" onclick="addMoreNurseRow()" style="width:30px" readonly id="add_more" title="Click To Add" value="+"></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="width: 2%;">
                                                <input style="width:30px" type="button" onclick="removetr(this, event)" style="width:30px" readonly id="add_more" title="Click TO Remove" value="-">
                                            </td>
                                        <?php
                                        }
                                        ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </fieldset>
                    </div>
                </div>
                <div class="row" style="clear:left;margin-top:20px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Anesthesiologist</label>
                            <div class="col-sm-8">
                                <select style="width: 100%;" type="text" class="form-control" id="anestasia_doctor_id" name="anestasia_doctor_id">
                                    <option selected="" disabled="" value="">Select Anesthesiologist</option>
                                    <?php
                                    $doctor = $this->db->where('anaestesiologist', 'Yes')->order_by('doctor_name', 'ASC')->get('doctor')->result();
                                    foreach ($doctor as $doctor_value) {
                                    ?>
                                        <option <?php echo $ot_service->anestasia_doctor_id == $doctor_value->doctor_id ? 'selected' : '' ?> value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->doctor_name . '-' . $doctor_value->doctor_unique_id ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Bond Paper</label>

                            <!-- Image Upload and Display -->
                            <div class="col-sm-4">
                                <input type="file" class="form-control" id="bond_paper" name="bond_paper" accept="image/*">
                            </div>
                            <div class="col-sm-4">
                                <div id="show_image" style="position:relative;">

                                    <img style="width: 100%;" src="<?php echo base_url() ?>assets/bond/<?php echo $ot_service->bond_paper ?>">

                                </div>
                            </div>

                            <!-- Modal for Zoomable Image -->
                            <div id="imageModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); justify-content:center; align-items:center;">
                                <div style="position:relative; width:100%; height:100%; display:flex; justify-content:center; align-items:center;">
                                    <!-- Close Icon -->
                                    <span id="closeModal" style="position:absolute; top:20px; right:20px; font-size:30px; color:white; cursor:pointer; z-index:10;">&times;</span>
                                    <!-- Zoomed Image -->
                                    <img id="zoomed_image" style="max-width:90%; max-height:90%; border:5px solid white;">
                                </div>
                            </div>

                            <script>
                                // Show selected image in the div
                                document.getElementById("bond_paper").addEventListener("change", function(event) {
                                    const file = event.target.files[0]; // Get the selected file
                                    const showImageDiv = document.getElementById("show_image"); // Get the div to display the image

                                    if (file) {
                                        const reader = new FileReader();

                                        reader.onload = function(e) {
                                            // Clear the div and append the new content
                                            showImageDiv.innerHTML = "";

                                            // Create an image element
                                            const img = document.createElement("img");
                                            img.src = e.target.result;
                                            img.style.maxWidth = "100%";
                                            img.style.height = "auto";

                                            // Create a zoom icon overlay
                                            const zoomIcon = document.createElement("div");
                                            zoomIcon.innerHTML = "🔍"; // Zoom icon
                                            zoomIcon.style.position = "absolute";
                                            zoomIcon.style.top = "10px";
                                            zoomIcon.style.right = "10px";
                                            zoomIcon.style.fontSize = "24px";
                                            zoomIcon.style.color = "white";
                                            zoomIcon.style.background = "rgba(0, 0, 0, 0.6)";
                                            zoomIcon.style.padding = "5px";
                                            zoomIcon.style.borderRadius = "50%";
                                            zoomIcon.style.cursor = "pointer";
                                            zoomIcon.onclick = function() {
                                                openModal(e.target.result); // Open modal on click
                                            };

                                            // Append the image and zoom icon to the container
                                            showImageDiv.appendChild(img);
                                            showImageDiv.appendChild(zoomIcon);
                                        };

                                        reader.readAsDataURL(file); // Read the file as a data URL
                                    } else {
                                        // Clear the div if no file is selected
                                        showImageDiv.innerHTML = "";
                                    }
                                });

                                // Function to open the modal and show the zoomed image
                                function openModal(imageSrc) {
                                    const modal = document.getElementById("imageModal");
                                    const zoomedImage = document.getElementById("zoomed_image");

                                    zoomedImage.src = imageSrc; // Set the source of the zoomed image
                                    modal.style.display = "flex"; // Show the modal
                                }

                                // Function to close the modal
                                document.getElementById("closeModal").addEventListener("click", function() {
                                    const modal = document.getElementById("imageModal");
                                    modal.style.display = "none"; // Hide the modal
                                });
                            </script>
                            <input type="hidden" value="<?php echo $ot_service->bond_paper ?>" class="form-control" id="bond_paper" name="old_bond_paper">


                        </div>
                    </div>
                </div>
                <fieldset style="background-color:whitesmoke;">
                    <legend>Reference</legend>
                    <!-- Reference Director -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="reference_doctor_id">Reference Doctor</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="reference_doctor_id" name="reference_doctor_id">
                                    <option value="">Select Doctor</option>
                                    <?php
                                    $doctors = $this->db->select('*')->get('doctor')->result();
                                    foreach ($doctors as $doctor_value) {
                                    ?>
                                        <option <?php echo $doctor_value->doctor_id == $ot_service->reference_doctor_id ? 'selected' : '' ?> value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->doctor_name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="reference_director_id">Reference Director</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="reference_director_id" name="reference_director_id">
                                        <option value="">Select Reference Director</option>
                                        <?php
                                        $directors = $this->db->select('*')->get('director')->result();
                                        foreach ($directors as $director) {
                                        ?>
                                            <option <?php echo $director->director_id == $ot_service->reference_director_id ? 'selected' : '' ?> value="<?php echo $director->director_id ?>"><?php echo $director->name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reference Media & Officer -->
                    <div class="row" style="margin-top:20px;">
                        <!-- Reference Media -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="reference_media_id">Reference Media</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="reference_media_id" name="reference_media_id">
                                        <option value="">Select Reference Media</option>
                                        <?php
                                        $reference_media = $this->db->select('*')->get('reference_media')->result();
                                        foreach ($reference_media as $reference_media_value) {
                                        ?>
                                            <option <?php echo $reference_media_value->reference_media_id == $ot_service->reference_media_id ? 'selected' : '' ?> value="<?php echo $reference_media_value->reference_media_id ?>"><?php echo $reference_media_value->reference_media_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Reference Officer -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="reference_employee_id">Reference Officer</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="reference_employee_id" name="reference_employee_id">
                                        <option selected="" value="" disabled="">Select Employee</option>
                                        <?php
                                        $employees = $this->db->select('*')->get('employee')->result();
                                        foreach ($employees as $employee) {
                                        ?>
                                            <option <?php echo $employee->employee_id == $ot_service->reference_employee_id ? 'selected' : '' ?> value="<?php echo $employee->employee_id; ?>"><?php echo $employee->employee_name . '-' . $employee->employee_unique_id ?></option>
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
                    <div class="col-md-5">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Update</button>
                        </div>

                    </div>
                    <div class="col-md-8" style="margin-top:5px;">

                        <div class="form-group">
                            <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>

</div>
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
