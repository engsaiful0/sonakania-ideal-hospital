<script>
    function SomeDeleteRowFunction(btndel) {

        if (typeof(btndel) == "object") {
            $(btndel).closest("tr").remove();
        } else {
            return false;
        }
    }
    $(document).ready(function() {
        $('#gender').select2();
        $('#follow_up_day_month_year').select2();

        for (var i = 1; i <= 6; i++) {
            $('#type_name_id_' + i).select2();
            $('#medicin_id_' + i).select2();
            $('#medicin_times_id_' + i).select2();
            $('#advice_' + i).select2();
            $('#diagnosis_' + i).select2();
            $('#day_or_month_or_year_or_colbay_' + i).select2();
        }
    });

    function load_diagnosis_row() {
        $('#img').show();
        var id = document.getElementById("idControlDiagnosis").value * 1;


        document.getElementById("idControlDiagnosis").value = id + 1;
        id = Number(id) + 1;
        // alert(id);

        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {

                var newdiv = document.createElement('tr');
                newdiv.innerHTML = xhttp.responseText;
                document.getElementById('diagnosis_table').appendChild(newdiv);
                //  for (var i = 2; i <= id; i++)
                // {                  
                $('#diagnosis_' + id).select2();
                //  }

                $('#img').hide();
            }
        }

        xhttp.open("POST", "<?php echo site_url('DischargeSlipController/load_diagnosis_row'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("id=" + id);
    }

    function load_advice_row() {
        $('#img').show();
        var id = document.getElementById("idControlAdvice").value * 1;


        document.getElementById("idControlAdvice").value = id + 1;
        id = Number(id) + 1;
        // alert(id);

        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {

                var newdiv = document.createElement('tr');
                newdiv.innerHTML = xhttp.responseText;
                document.getElementById('advice_table').appendChild(newdiv);
                //  for (var i = 2; i <= id; i++)
                // {                  
                $('#advice_' + id).select2();
                //  }

                $('#img').hide();
            }
        }

        xhttp.open("POST", "<?php echo site_url('DischargeSlipController/load_advice_row'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("id=" + id);
    }

    function load_product_row() {
        $('#img').show();
        var id = document.getElementById("idControl").value * 1;


        document.getElementById("idControl").value = id + 1;
        id = Number(id) + 1;
        // alert(id);

        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {

                var newdiv = document.createElement('tr');
                newdiv.innerHTML = xhttp.responseText;
                document.getElementById('product_table').appendChild(newdiv);
                for (var i = 2; i <= id; i++) {

                    $('#type_name_id_' + i).select2();
                    $('#medicin_id_' + i).select2();
                    $('#medicin_times_id_' + i).select2();
                    $('#day_or_month_or_year_or_colbay_' + i).select2();
                }

                $('#img').hide();
            }
        }

        xhttp.open("POST", "<?php echo site_url('DischargeSlipController/load_product_row'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("id=" + id);
    }

    function drug_name_load(type_name) {
        $('#img').show();
        var xhttp = new XMLHttpRequest();

        var data = type_name.split("_");
        //alert(data);
        var type_name_val = document.getElementById(type_name).value;
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {

                document.getElementById("medicin_id_" + data[3]).innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }

        xhttp.open("POST", "<?php echo site_url('DischargeSlipController/add_drug_name'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhttp.send("type_name_val=" + type_name_val);
    }

    function set_field_value(fieldId, value) {
        var field = document.getElementById(fieldId);
        if (field) {
            field.value = value || '';
        }
    }

    function patient_data_set(patient_unique_id) {
        var patientUniqueId = $.trim(patient_unique_id || '');
        if (patientUniqueId === '') {
            return;
        }

        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState !== 4) {
                return;
            }

            $('#img').hide();
            if (xhttp.status !== 200) {
                return;
            }

            var patient = $.trim(xhttp.responseText || '');
            if (patient === '') {
                return;
            }

            var patient_array = patient.split('*');
            set_field_value("ipd_patient_id", patient_array[0]);
            set_field_value("patient_name", patient_array[1]);
            set_field_value("mobile_number", patient_array[2]);
            set_field_value("admission_date", patient_array[4]);
            set_field_value("admission_time", patient_array[5]);
            set_field_value("discharge_date", patient_array[6]);
            set_field_value("discharge_time", patient_array[7]);
            set_field_value("age_year", patient_array[8]);
            set_field_value("age_month", patient_array[9]);
            set_field_value("age_day", patient_array[10]);
        }
        xhttp.open("POST", "<?php echo site_url('DischargeSlipController/discharged_patient_data_load_by_unique_id'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("patient_unique_id=" + encodeURIComponent(patientUniqueId));
    }

    $(document).ready(function() {
        $("#patient_unique_id").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('DischargeSlipController/discharged_patient_unique_id_load'); ?>",
                    data: {
                        parameter: request.term
                    },
                    dataType: "json",
                    type: "POST",
                    success: function(data) {
                        var items = $.map(data, function(item) {
                            return {
                                label: item,
                                value: item
                            };
                        });
                        response(items);
                    }
                });
            },
            select: function(event, ui) {
                $('#patient_unique_id').val(ui.item.value);
                patient_data_set(ui.item.value);
                return false;
            }
        });
        // Validate the form
        $("#discharge_slip_entry_form").validate({
            rules: {
                ipd_patient_id: "required",
                patient_unique_id: "required",
            },
            messages: {
                ipd_patient_id: "Enter select a patient",
                patient_unique_id: "Please select a patient ID",
            }
        });

        // Submit the form using AJAX with loading spinner
        $('#discharge_slip_entry_form').on('submit', function(e) {
            e.preventDefault();
            var submitBtn = $('#submit_button');
            var formData = $(this).serialize();
            var originalButtonHtml = submitBtn.html();

            if ($(this).valid()) {
                $('#discharge_slip_entry_form :input').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('DischargeSlipController/discharge_slip_data_save'); ?>",
                    data: formData,
                    dataType: "json",
                    beforeSend: function() {
                        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
                    },
                    complete: function() {
                        $('#discharge_slip_entry_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html(originalButtonHtml);
                    },
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
                            $('#discharge_slip_entry_form')[0].reset();
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('print-discharge-slip') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                    }
                });
            }
        });
    });
</script>
<script>
    function medicine_duplicate_check(drug_id) {
        console.log("Function triggered: ", drug_id);
        // Split ID to extract the index
        var id = drug_id.split('_');
        var idIndex = id[2];
        var medicin_id_value = $('#medicin_id_' + idIndex).val();

        console.log("Selected Value: ", medicin_id_value);

        // Check for duplicates
        var isDuplicate = false;

        $('select[name="drug_id[]"]').each(function() {
            console.log("Comparing: ", $(this).val(), " with ", medicin_id_value);
            if ($(this).val() === medicin_id_value && $(this).attr('id') !== drug_id) {
                isDuplicate = true;
                console.log("Duplicate found!");
                return false; // Exit loop
            }
        });

        // Handle duplicate case
        if (isDuplicate) {
            console.log("Duplicate detected, resetting the dropdown.");
            $.toast({
                heading: 'Error',
                text: "This medicine is already selected. Please choose a different one.",
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 2000,
                icon: 'error'
            });

            // Reset the dropdown
            $('#medicin_id_' + idIndex).val('').trigger('change');
            return; // Stop further execution
        }
    }

    function diagnosis_duplicate_check(diagnosis_id) {

        // Extract the index from the ID
        var id = diagnosis_id.split("_");
        var sequenceIndex = id[1];
        var selectedItem = $("#diagnosis_" + sequenceIndex).val();

        // Gather the currently selected items in all dropdowns except the one that triggered the event
        var existingItems = $("select[name='diagnosis_id[]']").map(function() {
            if ($(this).attr('id') !== 'diagnosis_' + sequenceIndex) {
                return $(this).val();
            }
        }).get().filter(item => item); // Filter out empty selections

        console.log('selectedItem=', selectedItem);
        console.log('existingItems=', existingItems);

        // Check if the selected item is already in use in other dropdowns
        if (existingItems.includes(selectedItem)) {
            $.toast({
                heading: 'Error',
                text: "This diagnosis is already added. Please select a new item.",
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 2000,
                icon: 'error'
            });

            // Clear the last selected item and reset the dropdown
            $("#diagnosis_" + sequenceIndex).val(null).trigger('change');
            $('#img').hide();
            return; // Exit the function as no further action is needed
        }
    }


    function advice_duplicate_check(advice_id) {
        // Extract the index from the ID
        var id = advice_id.split("_");
        var sequenceIndex = id[1];
        var selectedItem = $("#advice_" + sequenceIndex).val();

        // Gather the currently selected items in all dropdowns except the one that triggered the event
        var existingItems = $("select[name='advice_id[]']").map(function() {
            if ($(this).attr('id') !== 'advice_' + sequenceIndex) {
                return $(this).val();
            }
        }).get().filter(item => item); // Filter out empty selections

        console.log('selectedItem=', selectedItem);
        console.log('existingItems=', existingItems);

        // Check if the selected item is already in use in other dropdowns
        if (existingItems.includes(selectedItem)) {
            $.toast({
                heading: 'Error',
                text: "This advice is already added. Please select a new item.",
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 2000,
                icon: 'error'
            });

            // Clear the last selected item and reset the dropdown
            $("#advice_" + sequenceIndex).val(null).trigger('change');
            return; // Exit the function as no further action is needed
        }
    }
</script>
<div class="container-fluid" style=" background-color: white;width: 98%">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add Discharge Slip</h3>
        </div>

        <div class="panel-body" style="min-height: 1300px;">

            <form id="discharge_slip_entry_form" class="form-horizontal" method="post" enctype='multipart/form-data'>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Patient ID *</label>
                            <div class="col-sm-8">

                                <input type="hidden" id="ipd_patient_id" name="ipd_patient_id">
                                <input placeholder="Scan or Enter IPD Patient ID" type="text" id="patient_unique_id" name="patient_unique_id" class="form-control" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Name *</label>

                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Enter Patient Name" class="form-control" id="patient_name" name="patient_name">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Mobile</label>
                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Enter Mobile Number" class="form-control" id="mobile_number" name="mobile_number">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row" style="margin-top:20px">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Ad. Date</label>
                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Addmission Date" class="form-control" id="admission_date" name="admission_date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Ad. Time</label>
                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Addmission Time" class="form-control" id="admission_time" name="admission_time">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Dis. Date</label>
                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Discharge Date" class="form-control" id="discharge_date" name="discharge_date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Dis. Time</label>
                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Discharge Time" class="form-control" id="discharge_time" name="discharge_time">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Age</label>
                            <div class="col-sm-8">
                                <div class="col-sm-3">
                                    <input type="text" readonly placeholder="Year" class="form-control" id="age_year" name="age_year">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" readonly placeholder="Month" class="form-control" id="age_month" name="age_month">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" readonly placeholder="Day" class="form-control" id="age_day" name="age_day">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Date</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Enter Date" value="<?php echo date('d-m-Y') ?>" class="form-control" id="datepicker" name="date">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row" style="margin-top:20px">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">BP</label>
                            <div class="col-sm-8">
                                <div style="display: flex; align-items: center; border: 1px solid #ced4da;">
                                    <input type="number" placeholder="Systolic" id="bp_systolic" name="bp_systolic" class="form-control" style="border: none; outline: none; width: 90px; text-align: left;" />
                                    <span style="margin: 0 5px;">/</span>
                                    <input type="number" placeholder="Diastolic" id="bp_diastolic" name="bp_diastolic" class="form-control" style="border: none; outline: none; width: 90px;" />
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Follow Up</label>
                            <div class="col-sm-8">
                                <input type="text" oninput="validateIntegerInput(this)" placeholder="Enter Follow Up days" class="form-control" id="follow_up" name="follow_up">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">দিন/মাস/বছর/</label>
                            <div class="col-sm-8">
                                <select type="text" required="" class="form-control" id="follow_up_day_month_year" name="follow_up_day_month_year">
                                    <option selected="" value="" disabled="">Select দিন/মাস/বছর/</option>
                                    <option>দিন</option>
                                    <option>মাস</option>
                                    <option>বছর</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row" style="margin-top:20px">
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php
                            error_reporting(0);
                            $uniqu_id = $this->db->select('*')->order_by('id', 'DESC')->limit('1')->get('discharge_slip_ids')->row();
                            $discharge_slip_id = 'DS' . time() . '0' . $uniqu_id->id_serial + 1;
                            ?>
                            <label class="control-label col-sm-4" for="pwd">Discharge Slip ID</label>
                            <div class="col-sm-8">
                                <input type="hidden" readonly="" class="form-control" value="<?php echo $uniqu_id->id_serial + 1 ?>" id="id_serial" name="id_serial">
                                <input readonly type="text" placeholder="Discharge Slip ID" value="<?php echo $discharge_slip_id ?>" class="form-control" id="discharge_slip_unique_id" name="discharge_slip_unique_id">
                            </div>
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <table id="product_table" class="table table-bordered table-hover table-striped">
                                <input type="hidden" id="idControl" value="6">
                                <input type="hidden" id="current_id" value="6">

                                <input type="hidden" id="idControlAdvice" value="6">
                                <input type="hidden" id="current_idAvice" value="6">

                                <input type="hidden" id="idControlDiagnosis" value="6">
                                <input type="hidden" id="current_idDiagnosis" value="6">
                                <tr id="1">
                                    <td>Sl</td>
                                    <td>Drug Type</td>
                                    <td>Drug</td>
                                    <td>Times</td>
                                    <td>How Many</td>
                                    <td>How Many</td>
                                    <td>দিন/মাস/বছর/চলবে</td>
                                </tr>
                                <tr>
                                    <td style="width: 2%;">1</td>
                                    <td style="width: 20%;">
                                        <select name="drug_type_id[]" class="form-control" id="type_name_id_1" onchange="drug_name_load(this.id)" style="width:100%;">
                                            <option value="" selected="">Select Type</option>
                                            <?php
                                            $sql = $this->db->select('*')->get('drug_type')->result();

                                            foreach ($sql as $value) {
                                            ?>
                                                <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td style="width: 16%;">
                                        <select type="text" onchange="medicine_duplicate_check(this.id)" style="width: 100%" class="form-control drug-select" id="medicin_id_1" name="drug_id[]">
                                            <option selected="" value="" disabled=""></option>

                                        </select>

                                    </td>
                                    <td style="width: 15%;">
                                        <select type="text" style="width: 100%" class="form-control" id="medicin_times_id_1" name="medicin_times_id[]">
                                            <option selected="" value="" disabled=""></option>
                                            <?php
                                            $medicin_times = $this->db->select('*')->get('medicin_times')->result();
                                            foreach ($medicin_times as $value) {
                                            ?>
                                                <option value="<?php echo $value->medicin_times_id; ?>"><?php echo $value->medicin_times_name; ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </td>
                                    <td style="width: 15%;">

                                        <input type="text" style="width: 100%" oninput="validateIntegerInput(this)" class="form-control" id="days_1" name="days[]">
                                    </td>
                                    <td style="width: 15%;">
                                        <select type="text" style="width: 100%" class="form-control" id="day_or_month_or_year_or_colbay_1" placeholder="Days" name="day_or_month_or_year_or_colbay[]">
                                            <option>দিন</option>
                                            <option>মাস</option>
                                            <option>বছর</option>
                                            <option>চলবে</option>
                                        </select>
                                    </td>
                                    <td style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td style="width: 20%;">
                                        <select name="drug_type_id[]" class="form-control" id="type_name_id_2" onchange="drug_name_load(this.id)" style="width:100%;">
                                            <option value="" selected="">Select Type</option>
                                            <?php
                                            $sql = $this->db->select('*')->get('drug_type')->result();

                                            foreach ($sql as $value) {
                                            ?>
                                                <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>


                                    <td style="width: 16%;">
                                        <select type="text" style="width: 100%" onchange="medicine_duplicate_check(this.id)" class="form-control drug-select" id="medicin_id_2" name="drug_id[]">
                                            <option selected="" value="" disabled=""></option>

                                        </select>

                                    </td>
                                    <td style="width: 15%;">
                                        <select type="text" style="width: 100%" class="form-control" id="medicin_times_id_2" name="medicin_times_id[]">
                                            <option selected="" value="" disabled=""></option>
                                            <?php
                                            $medicin_times = $this->db->select('*')->get('medicin_times')->result();
                                            foreach ($medicin_times as $value) {
                                            ?>
                                                <option value="<?php echo $value->medicin_times_id; ?>"><?php echo $value->medicin_times_name; ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </td>
                                    <td style="width: 15%;">

                                        <input type="text" style="width: 100%" class="form-control" oninput="validateIntegerInput(this)" id="days_2" name="days[]">
                                    </td>
                                    <td style="width: 15%;">

                                        <select type="text" style="width: 100%" class="form-control" id="day_or_month_or_year_or_colbay_2" name="day_or_month_or_year_or_colbay[]">
                                            <option>দিন</option>
                                            <option>মাস</option>
                                            <option>বছর</option>
                                            <option>চলবে</option>
                                        </select>
                                    </td>
                                    <td style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td style="width: 20%;">
                                        <select name="drug_type_id[]" class="form-control drug-select" id="type_name_id_3" onchange="drug_name_load(this.id)" style="width:100%;">
                                            <option value="" selected="">Select Type</option>
                                            <?php
                                            $sql = $this->db->select('*')->get('drug_type')->result();

                                            foreach ($sql as $value) {
                                            ?>
                                                <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>


                                    <td style="width: 16%;">
                                        <select type="text" style="width: 100%" onchange="medicine_duplicate_check(this.id)" class="form-control" id="medicin_id_3" name="drug_id[]">
                                            <option selected="" value="" disabled=""></option>

                                        </select>

                                    </td>
                                    <td style="width: 15%;">
                                        <select type="text" style="width: 100%" class="form-control" id="medicin_times_id_3" name="medicin_times_id[]">
                                            <option selected="" value="" disabled=""></option>
                                            <?php
                                            $medicin_times = $this->db->select('*')->get('medicin_times')->result();
                                            foreach ($medicin_times as $value) {
                                            ?>
                                                <option value="<?php echo $value->medicin_times_id; ?>"><?php echo $value->medicin_times_name; ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </td>
                                    <td style="width: 15%;">

                                        <input type="text" style="width: 100%" class="form-control" oninput="validateIntegerInput(this)" id="days_3" name="days[]">
                                    </td>
                                    <td style="width: 15%;">

                                        <select type="text" style="width: 100%" class="form-control" id="day_or_month_or_year_or_colbay_3" name="day_or_month_or_year_or_colbay[]">

                                            <option>দিন</option>
                                            <option>মাস</option>
                                            <option>বছর</option>
                                            <option>চলবে</option>
                                        </select>
                                    </td>
                                    <td style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </tr>

                                <tr>
                                    <td>4</td>
                                    <td style="width: 20%;">
                                        <select name="drug_type_id[]" class="form-control" id="type_name_id_4" onchange="drug_name_load(this.id)" style="width:100%;">
                                            <option value="" selected="">Select Type</option>
                                            <?php
                                            $sql = $this->db->select('*')->get('drug_type')->result();

                                            foreach ($sql as $value) {
                                            ?>
                                                <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>


                                    <td style="width: 16%;">
                                        <select type="text" style="width: 100%" onchange="medicine_duplicate_check(this.id)" class="form-control drug-select" id="medicin_id_4" name="drug_id[]">
                                            <option selected="" value="" disabled=""></option>

                                        </select>

                                    </td>
                                    <td style="width: 15%;">
                                        <select type="text" style="width: 100%" class="form-control" id="medicin_times_id_4" name="medicin_times_id[]">
                                            <option selected="" value="" disabled=""></option>
                                            <?php
                                            $medicin_times = $this->db->select('*')->get('medicin_times')->result();
                                            foreach ($medicin_times as $value) {
                                            ?>
                                                <option value="<?php echo $value->medicin_times_id; ?>"><?php echo $value->medicin_times_name; ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </td>
                                    <td style="width: 15%;">

                                        <input type="text" style="width: 100%" class="form-control" oninput="validateIntegerInput(this)" id="days_4" name="days[]">
                                    </td>
                                    <td style="width: 15%;">

                                        <select type="text" style="width: 100%" class="form-control" id="day_or_month_or_year_or_colbay_4" name="day_or_month_or_year_or_colbay[]">
                                            <option>দিন</option>
                                            <option>মাস</option>
                                            <option>বছর</option>
                                            <option>চলবে</option>
                                        </select>
                                    </td>
                                    <td style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td style="width: 20%;">
                                        <select name="drug_type_id[]" class="form-control" id="type_name_id_5" onchange="drug_name_load(this.id)" style="width:100%;">
                                            <option value="" selected="">Select Type</option>
                                            <?php
                                            $sql = $this->db->select('*')->get('drug_type')->result();

                                            foreach ($sql as $value) {
                                            ?>
                                                <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>


                                    <td style="width: 16%;">
                                        <select type="text" style="width: 100%" onchange="medicine_duplicate_check(this.id)" class="form-control drug-select" id="medicin_id_5" name="drug_id[]">
                                            <option selected="" value="" disabled=""></option>

                                        </select>

                                    </td>
                                    <td style="width: 15%;">
                                        <select type="text" style="width: 100%" class="form-control" id="medicin_times_id_5" name="medicin_times_id[]">
                                            <option selected="" value="" disabled=""></option>
                                            <?php
                                            $medicin_times = $this->db->select('*')->get('medicin_times')->result();
                                            foreach ($medicin_times as $value) {
                                            ?>
                                                <option value="<?php echo $value->medicin_times_id; ?>"><?php echo $value->medicin_times_name; ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </td>
                                    <td style="width: 15%;">

                                        <input type="text" style="width: 100%" class="form-control" oninput="validateIntegerInput(this)" id="days_5" name="days[]">
                                    </td>
                                    <td style="width: 15%;">

                                        <select type="text" style="width: 100%" class="form-control" id="day_or_month_or_year_or_colbay_5" name="day_or_month_or_year_or_colbay[]">
                                            <option>দিন</option>
                                            <option>মাস</option>
                                            <option>বছর</option>
                                            <option>চলবে</option>
                                        </select>
                                    </td>
                                    <td style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td style="width: 20%;">
                                        <select name="drug_type_id[]" class="form-control" id="type_name_id_6" onchange="drug_name_load(this.id)" style="width:100%;">
                                            <option value="" selected="">Select Type</option>
                                            <?php
                                            $sql = $this->db->select('*')->get('drug_type')->result();

                                            foreach ($sql as $value) {
                                            ?>
                                                <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>


                                    <td style="width: 16%;">
                                        <select type="text" style="width: 100%" onchange="medicine_duplicate_check(this.id)" class="form-control drug-select" id="medicin_id_6" name="drug_id[]">
                                            <option selected="" value="" disabled=""></option>

                                        </select>

                                    </td>
                                    <td style="width: 15%;">
                                        <select type="text" style="width: 100%" class="form-control" id="medicin_times_id_6" name="medicin_times_id[]">
                                            <option selected="" value="" disabled=""></option>
                                            <?php
                                            $medicin_times = $this->db->select('*')->get('medicin_times')->result();
                                            foreach ($medicin_times as $value) {
                                            ?>
                                                <option value="<?php echo $value->medicin_times_id; ?>"><?php echo $value->medicin_times_name; ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </td>
                                    <td style="width: 15%;">

                                        <input type="text" style="width: 100%" class="form-control" id="days_6" name="days[]">
                                    </td>
                                    <td style="width: 15%;">

                                        <select type="text" style="width: 100%" class="form-control" id="day_or_month_or_year_or_colbay_6" name="day_or_month_or_year_or_colbay[]">
                                            <option>দিন</option>
                                            <option>মাস</option>
                                            <option>বছর</option>
                                            <option>চলবে</option>
                                        </select>
                                    </td>
                                    <td style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </tr>

                            </table>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <table id="diagnosis_table" class="table table-bordered table-hover table-striped">


                            <tr>
                                <td>1</td>
                                <td>
                                    <select name="diagnosis_id[]" onchange="diagnosis_duplicate_check(this.id)" class="form-control" id="diagnosis_1" style="width:100%;">
                                        <option value="" disabled="" selected="">Diagnosis </option>
                                        <?php
                                        $diagnosis = $this->db->select('*')->get('diagnosis')->result();

                                        foreach ($diagnosis as $value) {
                                        ?>
                                            <option value="<?php echo $value->diagnosis_id ?>"><?php echo $value->diagnosis_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td style="width: 2%;"><input type="button" onclick="load_diagnosis_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <select name="diagnosis_id[]" onchange="diagnosis_duplicate_check(this.id)" class="form-control" id="diagnosis_2" style="width:100%;">
                                        <option value="" disabled="" selected="">Diagnosis</option>
                                        <?php
                                        $diagnosis = $this->db->select('*')->get('diagnosis')->result();

                                        foreach ($diagnosis as $value) {
                                        ?>
                                            <option value="<?php echo $value->diagnosis_id ?>"><?php echo $value->diagnosis_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td style="width: 2%;"><input type="button" onclick="load_diagnosis_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    <select name="diagnosis_id[]" onchange="diagnosis_duplicate_check(this.id)" class="form-control" id="diagnosis_3" style="width:100%;">
                                        <option value="" disabled="" selected="">Diagnosis</option>
                                        <?php
                                        $diagnosis = $this->db->select('*')->get('diagnosis')->result();

                                        foreach ($diagnosis as $value) {
                                        ?>
                                            <option value="<?php echo $value->diagnosis_id ?>"><?php echo $value->diagnosis_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td style="width: 2%;"><input type="button" onclick="load_diagnosis_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>
                                    <select name="diagnosis_id[]" onchange="diagnosis_duplicate_check(this.id)" class="form-control" id="diagnosis_4" style="width:100%;">
                                        <option value="" disabled="" selected="">Diagnosis</option>
                                        <?php
                                        $diagnosis = $this->db->select('*')->get('diagnosis')->result();

                                        foreach ($diagnosis as $value) {
                                        ?>
                                            <option value="<?php echo $value->diagnosis_id ?>"><?php echo $value->diagnosis_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td style="width: 2%;"><input type="button" onclick="load_diagnosis_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>
                                    <select name="diagnosis_id[]" onchange="diagnosis_duplicate_check(this.id)" class="form-control" id="diagnosis_5" style="width:100%;">
                                        <option value="" disabled="" selected="">Diagnosis</option>
                                        <?php
                                        $diagnosis = $this->db->select('*')->get('diagnosis')->result();

                                        foreach ($diagnosis as $value) {
                                        ?>
                                            <option value="<?php echo $value->diagnosis_id ?>"><?php echo $value->diagnosis_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td style="width: 2%;"><input type="button" onclick="load_diagnosis_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>
                                    <select name="diagnosis_id[]" onchange="diagnosis_duplicate_check(this.id)" class="form-control" id="diagnosis_6" style="width:100%;">
                                        <option value="" disabled="" selected="">Diagnosis</option>
                                        <?php
                                        $diagnosis = $this->db->select('*')->get('diagnosis')->result();

                                        foreach ($diagnosis as $value) {
                                        ?>
                                            <option value="<?php echo $value->diagnosis_id ?>"><?php echo $value->diagnosis_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td style="width: 2%;">
                                    <input type="button" onclick="load_diagnosis_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+">
                                </td>
                                </td>
                            </tr>
                        </table>
                        <input type="text" name="custom_diagnosis" class="form-control" id="custom_diagnosis" placeholder="Enter Custom Diagnosis">
                        <div class="form-group" style="margin-top:10px;">
                            <div class="col-sm-8">
                                <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <table id="advice_table" class="table table-bordered table-hover table-striped">
                            <tr>
                                <td>1</td>
                                <td>
                                    <select name="advice_id[]" onchange="advice_duplicate_check(this.id)" class="form-control" id="advice_1" style="width:100%;">
                                        <option value="" disabled="" selected="">Advice</option>
                                        <?php
                                        $advice = $this->db->select('*')->get('advice')->result();

                                        foreach ($advice as $value) {
                                        ?>
                                            <option value="<?php echo $value->advice_id ?>"><?php echo $value->advice_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td style="width: 2%;"><input type="button" onclick="load_advice_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <select name="advice_id[]" onchange="advice_duplicate_check(this.id)" class="form-control" id="advice_2" style="width:100%;">
                                        <option value="" disabled="" selected="">Advice</option>
                                        <?php
                                        $advice = $this->db->select('*')->get('advice')->result();

                                        foreach ($advice as $value) {
                                        ?>
                                            <option value="<?php echo $value->advice_id ?>"><?php echo $value->advice_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td style="width: 2%;"><input type="button" onclick="load_advice_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    <select name="advice_id[]" onchange="advice_duplicate_check(this.id)" class="form-control" id="advice_3" style="width:100%;">
                                        <option value="" disabled="" selected="">Advice</option>
                                        <?php
                                        $advice = $this->db->select('*')->get('advice')->result();

                                        foreach ($advice as $value) {
                                        ?>
                                            <option value="<?php echo $value->advice_id ?>"><?php echo $value->advice_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td style="width: 2%;"><input type="button" onclick="load_advice_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>
                                    <select name="advice_id[]" onchange="advice_duplicate_check(this.id)" class="form-control" id="advice_4" style="width:100%;">
                                        <option value="" disabled="" selected="">Advice</option>
                                        <?php
                                        $advice = $this->db->select('*')->get('advice')->result();

                                        foreach ($advice as $value) {
                                        ?>
                                            <option value="<?php echo $value->advice_id ?>"><?php echo $value->advice_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td style="width: 2%;"><input type="button" onclick="load_advice_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>
                                    <select name="advice_id[]" onchange="advice_duplicate_check(this.id)" class="form-control" id="advice_5" style="width:100%;">
                                        <option value="" disabled="" selected="">Advice</option>
                                        <?php
                                        $advice = $this->db->select('*')->get('advice')->result();

                                        foreach ($advice as $value) {
                                        ?>
                                            <option value="<?php echo $value->advice_id ?>"><?php echo $value->advice_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td style="width: 2%;"><input type="button" onclick="load_advice_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>
                                    <select name="advice_id[]" onchange="advice_duplicate_check(this.id)" class="form-control" id="advice_6" style="width:100%;">
                                        <option value="" disabled="" selected="">Advice</option>
                                        <?php
                                        $advice = $this->db->select('*')->get('advice')->result();

                                        foreach ($advice as $value) {
                                        ?>
                                            <option value="<?php echo $value->advice_id ?>"><?php echo $value->advice_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td style="width: 2%;"><input type="button" onclick="load_advice_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </td>
                            </tr>

                        </table>
                        <input type="text" name="custom_advice" class="form-control" id="custom_advice" placeholder="Enter Custom Advice">

                    </div>

                </div>



        </div>


    </div>

    </form>
</div>