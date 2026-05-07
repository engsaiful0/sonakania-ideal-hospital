
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

        var idControl = document.getElementById('idControl').value * 1;
        console.log('idControl', idControl);
        for (var counter = 1; counter <= Number(idControl); counter++) {
            $('#type_name_id_' + counter).select2();
            $('#medicin_id_' + counter).select2();
            $('#medicin_times_id_' + counter).select2();

            $('#day_or_month_or_year_or_colbay_' + counter).select2();
        }
        var idControlAdvice = document.getElementById('idControlAdvice').value * 1;
        for (var counter = 1; counter <= Number(idControlAdvice); counter++) {
            $('#advice_' + counter).select2();
        }

        var idControlDiagnosis = document.getElementById('idControlDiagnosis').value * 1;
        for (var counter = 1; counter <= Number(idControlDiagnosis); counter++) {
            $('#diagnosis_' + counter).select2();
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
        console.log(type_name);
        $('#img').show();
        var xhttp = new XMLHttpRequest();

        var data = type_name.split("_");
        //alert(data);
        console.log('data[3]=', data[3]);
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
                document.getElementById("admission_date").value = patient_array[4];
                document.getElementById("admission_time").value = patient_array[5];
                document.getElementById("discharge_date").value = patient_array[6];
                document.getElementById("discharge_time").value = patient_array[7];

                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('DischargeSlipController/discharged_patient_data_load_by_unique_id'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("patient_unique_id=" + patient_unique_id);
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
                ipd_patient_id: "required",
                patient_unique_id: "required",
            },
            messages: {
                ipd_patient_id: "Enter select a patient",
                patient_unique_id: "Please select a patient ID",
            }
        });
        // On form submission
        $('#submit_button').click(function(e) {
            e.preventDefault();
            var submitBtn = $(this);
            var formData = $('#discharge_slip_entry_form').serialize();
            // Check if the form is valid
            if ($("#discharge_slip_entry_form").valid()) {
                $('#discharge_slip_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('DischargeSlipController/discharge_slip_data_update'); ?>",
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
                            $('#discharge_slip_entry_form')[0].reset();
                            $('#discharge_slip_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('print-discharge-slip') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#discharge_slip_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#discharge_slip_entry_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
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
            <h3 style="text-align: center">Update Discharge Slip</h3>
        </div>
        <?php
        $discharge_slip = $this->db->where('discharge_slip_id', $discharge_slip_id)->get('discharge_slips')->row();
        $patient = $this->db->where('ipd_patient_id', $discharge_slip->ipd_patient_id)->get('ipd_patient')->row();
        // echo '<pre>';
        //print_r($discharge_slip);
        $discharge = $this->db->where('ipd_patient_id', $discharge_slip->ipd_patient_id)->get('discharge')->row();
        $discharge_slip_medicins = $this->db->where('discharge_slip_id', $discharge_slip->discharge_slip_id)->get('discharge_slip_medicins')->result();
        $discharge_slip_advices = $this->db->where('discharge_slip_id', $discharge_slip->discharge_slip_id)->get('discharge_slip_advices')->result();
        $discharge_slip_diagnosis = $this->db->where('discharge_slip_id', $discharge_slip->discharge_slip_id)->get('discharge_slip_diagnosis')->result();
        //var_dump($discharge);

        ?>
        <div class="panel-body" style="min-height: 1300px;">
            <form id="discharge_slip_entry_form" class="form-horizontal" method="post" enctype='multipart/form-data'>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Patient ID *</label>
                            <div class="col-sm-8">
                                <input type="hidden" value="<?php echo $discharge_slip_id ?>" id="discharge_slip_id" name="discharge_slip_id">
                                <input type="hidden" value="<?php echo $discharge_slip->ipd_patient_id ?>" id="ipd_patient_id" name="ipd_patient_id">
                                <input onchange="patient_data_set(this.value)" value="<?php echo $discharge_slip->patient_unique_id ?>" placeholder="Scan or Enter IPD Patient ID" type="text" id="patient_unique_id" name="patient_unique_id" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Name *</label>

                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Enter Patient Name" value="<?php echo $patient->patient_name ?>" class="form-control" id="patient_name" name="patient_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Mobile</label>
                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Enter Mobile Number" class="form-control" value="<?php echo $patient->mobile_number ?>" id="mobile_number" name="mobile_number">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top:20px">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Ad. Date</label>
                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Addmission Date" class="form-control" value="<?php echo $patient->date ?>" id="admission_date" name="admission_date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Ad. Time</label>
                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Addmission Time" class="form-control" id="admission_time" value="<?php echo $patient->admission_time ?>" name="admission_time">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Dis. Date</label>
                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Discharge Date" class="form-control" id="discharge_date" value="<?php echo $discharge->discharge_date ?>" name="discharge_date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Dis. Time</label>
                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Discharge Time" class="form-control" id="discharge_time" value="<?php echo $discharge->discharge_time ?>" name="discharge_time">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Age</label>
                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Enter Age" class="form-control" value="<?php echo $patient->age ?>" id="age" name="age">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Date</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Enter Date" value="<?php echo date('d-m-Y', strtotime($discharge_slip->date)) ?>" class="form-control" id="datepicker" name="date">
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
                                    <input type="number" placeholder="Systolic" value="<?php echo $discharge_slip->bp_systolic ?>" id="bp_systolic" name="bp_systolic" class="form-control" style="border: none; outline: none; width: 90px; text-align: left;" />
                                    <span style="margin: 0 5px;">/</span>
                                    <input type="number" placeholder="Diastolic" value="<?php echo $discharge_slip->bp_diastolic ?>" id="bp_diastolic" name="bp_diastolic" class="form-control" style="border: none; outline: none; width: 90px;" />
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Follow Up</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Enter Follow Up days" value="<?php echo $discharge_slip->follow_up ?>" class="form-control" id="follow_up" name="follow_up">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">দিন/মাস/বছর/</label>
                            <div class="col-sm-8">
                                <select type="text" required="" class="form-control" id="follow_up_day_month_year" name="follow_up_day_month_year">
                                    <option value="" disabled="">Select দিন/মাস/বছর/</option>
                                    <option <?php echo $discharge_slip->follow_up_day_month_year == 'দিন' ? 'selected' : '' ?>>দিন</option>
                                    <option <?php echo $discharge_slip->follow_up_day_month_year == 'মাস' ? 'selected' : '' ?>>মাস</option>
                                    <option <?php echo $discharge_slip->follow_up_day_month_year == 'বছর' ? 'selected' : '' ?>>বছর</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row" style="margin-top:20px">
                    <div class="col-md-4">
                        <div class="form-group">

                            <label class="control-label col-sm-4" for="pwd">Discharge Slip ID</label>
                            <div class="col-sm-8">

                                <input readonly type="text" placeholder="Discharge Slip ID" value="<?php echo $discharge_slip->discharge_slip_unique_id ?>" class="form-control" id="discharge_slip_unique_id" name="discharge_slip_unique_id">
                            </div>
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <table style="margin-bottom: 20px;" id="product_table" class="table table-bordered table-hover table-striped">
                                <input type="hidden" value="<?php echo count($discharge_slip_medicins) + 6 ?>" id="idControl">
                                <input type="hidden" id="current_id" value="<?php echo count($discharge_slip_medicins) + 6 ?>">

                                <input type="hidden" id="idControlAdvice" value="<?php echo count($discharge_slip_advices) + 6 ?>">
                                <input type="hidden" id="current_idAvice" value="<?php echo count($discharge_slip_advices) + 6 ?>">

                                <input type="hidden" id="idControlDiagnosis" value="<?php echo count($discharge_slip_diagnosis) + 6 ?>">
                                <input type="hidden" id="current_idDiagnosis" value="<?php echo count($discharge_slip_diagnosis) + 6 ?>">
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
                                    <?php
                                    $id = 1;
                                    foreach ($discharge_slip_medicins as $discharge_slip_medicin) {
                                        $drug = $this->db->where('drug_id', $discharge_slip_medicin->drug_id)->get('drug')->row();
                                    ?>
                                        <td style="width: 2%;"><?php echo $id ?></td>
                                        <td style="width: 20%;">
                                            <select name="drug_type_id[]" class="form-control" id="type_name_id_<?php echo $id ?>" onchange="drug_name_load(this.id)" style="width:100%;">
                                                <option value="" selected="">Select Type</option>
                                                <?php
                                                $sql = $this->db->select('*')->get('drug_type')->result();

                                                foreach ($sql as $value) {
                                                ?>
                                                    <option <?php echo $discharge_slip_medicin->drug_type_id == $value->drug_type_id ? 'selected' : '' ?> value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </td>


                                        <td style="width: 16%;">
                                            <select type="text" onchange="medicine_duplicate_check(this.id)" style="width: 100%" class="form-control" id="medicin_id_<?php echo $id ?>" name="drug_id[]">
                                                <option selected value="<?php echo $drug->drug_id ?>"><?php echo $drug->drug_name ?></option>
                                            </select>

                                        </td>
                                        <td style="width: 15%;">
                                            <select type="text" style="width: 100%" class="form-control" id="medicin_times_id_<?php echo $id ?>" name="medicin_times_id[]">
                                                <option selected="" value="" disabled=""></option>
                                                <?php
                                                $medicin_times = $this->db->select('*')->get('medicin_times')->result();
                                                foreach ($medicin_times as $value) {
                                                ?>
                                                    <option <?php echo $discharge_slip_medicin->medicin_times_id == $value->medicin_times_id ? 'selected' : '' ?> value="<?php echo $value->medicin_times_id; ?>"><?php echo $value->medicin_times_name; ?></option>
                                                <?php
                                                }
                                                ?>

                                            </select>
                                        </td>
                                        <td style="width: 15%;">

                                            <input type="text" value="<?php echo $discharge_slip_medicin->days ?>" oninput="validateIntegerInput(this)" style="width: 100%" class="form-control" id="days_<?php echo $id ?>" name="days[]">
                                        </td>
                                        <td style="width: 15%;">
                                            <select type="text" style="width: 100%" class="form-control" id="day_or_month_or_year_or_colbay_<?php echo $id ?>" placeholder="Days" name="day_or_month_or_year_or_colbay[]">
                                                <option <?php echo $discharge_slip_medicin->day_or_month_or_year_or_colbay == 'দিন' ? 'selected' : '' ?>>দিন</option>
                                                <option <?php echo $discharge_slip_medicin->day_or_month_or_year_or_colbay == 'মাস' ? 'selected' : '' ?>>মাস</option>
                                                <option <?php echo $discharge_slip_medicin->day_or_month_or_year_or_colbay == 'বছর' ? 'selected' : '' ?>>বছর</option>
                                                <option <?php echo $discharge_slip_medicin->day_or_month_or_year_or_colbay == 'চলবে' ? 'selected' : '' ?>>চলবে</option>
                                            </select>
                                        </td>
                                        <td style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </tr>
                            <?php
                                        $id++;
                                    }
                            ?>
                            <tr>
                                <td>

                                    <?php echo $id ?></td>
                                <td style="width: 20%;">
                                    <select name="drug_type_id[]" class="form-control" id="type_name_id_<?php echo $id ?>" onchange="drug_name_load(this.id)" style="width:100%;">
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
                                    <select type="text" style="width: 100%" class="form-control" id="medicin_id_<?php echo $id ?>" name="drug_id[]">

                                        <option selected="" value="" disabled=""></option>

                                    </select>

                                </td>
                                <td style="width: 15%;">
                                    <select type="text" style="width: 100%" class="form-control" id="medicin_times_id_<?php echo $id ?>" name="medicin_times_id[]">
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

                                    <input type="text" style="width: 100%" class="form-control" oninput="validateIntegerInput(this)" id="days_<?php echo $id ?>" name="days[]">
                                </td>
                                <td style="width: 15%;">

                                    <select type="text" style="width: 100%" class="form-control" id="day_or_month_or_year_or_colbay_<?php echo $id ?>" name="day_or_month_or_year_or_colbay[]">
                                        <option>দিন</option>
                                        <option>মাস</option>
                                        <option>বছর</option>
                                        <option>চলবে</option>
                                    </select>
                                </td>
                                <td style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                            </tr>
                            <tr>
                                <td>

                                    <?php echo $id + 1 ?></td>
                                <td style="width: 20%;">
                                    <select name="drug_type_id[]" class="form-control" id="type_name_id_<?php echo $id + 1 ?>" onchange="drug_name_load(this.id)" style="width:100%;">
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
                                    <select type="text" style="width: 100%" class="form-control" id="medicin_id_<?php echo $id + 1 ?>" name="drug_id[]">

                                        <option selected="" value="" disabled=""></option>

                                    </select>

                                </td>
                                <td style="width: 15%;">
                                    <select type="text" style="width: 100%" class="form-control" id="medicin_times_id_<?php echo $id + 1 ?>" name="medicin_times_id[]">
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
                                    <input type="text" style="width: 100%" class="form-control" oninput="validateIntegerInput(this)" id="days_<?php echo $id + 1 ?>" name="days[]">
                                </td>
                                <td style="width: 15%;">
                                    <select type="text" style="width: 100%" class="form-control" id="day_or_month_or_year_or_colbay_<?php echo $id + 1 ?>" name="day_or_month_or_year_or_colbay[]">
                                        <option>দিন</option>
                                        <option>মাস</option>
                                        <option>বছর</option>
                                        <option>চলবে</option>
                                    </select>
                                </td>
                                <td style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                            </tr>

                            <tr>
                                <td><?php echo $id + 2 ?></td>
                                <td style="width: 20%;">
                                    <select name="drug_type_id[]" class="form-control" id="type_name_id_<?php echo $id + 2 ?>" onchange="drug_name_load(this.id)" style="width:100%;">
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
                                    <select  type="text" style="width: 100%" class="form-control" id="medicin_id_<?php echo $id + 3 ?>" name="drug_id[]">
                                    <select  type="text" style="width: 100%" class="form-control" id="medicin_id_<?php echo $id + 2 ?>" name="drug_id[]">
                                        <option selected="" value="" disabled=""></option>

                                    </select>

                                </td>
                                <td style="width: 15%;">
                                    <select type="text" style="width: 100%" class="form-control" id="medicin_times_id_<?php echo $id + 2 ?>" name="medicin_times_id[]">
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

                                    <input type="text" style="width: 100%" class="form-control" oninput="validateIntegerInput(this)" id="days_<?php echo $id + 2 ?>" name="days[]">
                                </td>
                                <td style="width: 15%;">

                                    <select type="text" style="width: 100%" class="form-control" id="day_or_month_or_year_or_colbay_<?php echo $id + 2 ?>" name="day_or_month_or_year_or_colbay[]">

                                        <option>দিন</option>
                                        <option>মাস</option>
                                        <option>বছর</option>
                                        <option>চলবে</option>
                                    </select>
                                </td>
                                <td style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                            </tr>

                            <tr>
                                <td><?php echo $id + 3 ?></td>
                                <td style="width: 20%;">
                                    <select name="drug_type_id[]" class="form-control" id="type_name_id_<?php echo $id + 3 ?>" onchange="drug_name_load(this.id)" style="width:100%;">
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
                                    <select onchange="medicine_duplicate_check(this.id)" type="text" style="width: 100%" class="form-control" id="medicin_id_<?php echo $id + 3 ?>" name="drug_id[]">
                                        <option selected="" value="" disabled=""></option>

                                    </select>

                                </td>
                                <td style="width: 15%;">
                                    <select type="text" style="width: 100%" class="form-control" id="medicin_times_id_<?php echo $id + 3 ?>" name="medicin_times_id[]">
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

                                    <input type="text" style="width: 100%" class="form-control" oninput="validateIntegerInput(this)" id="days_<?php echo $id + 3 ?>" name="days[]">
                                </td>
                                <td style="width: 15%;">

                                    <select type="text" style="width: 100%" class="form-control" id="day_or_month_or_year_or_colbay_<?php echo $id + 3 ?>" name="day_or_month_or_year_or_colbay[]">
                                        <option>দিন</option>
                                        <option>মাস</option>
                                        <option>বছর</option>
                                        <option>চলবে</option>
                                    </select>
                                </td>
                                <td style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                            </tr>
                            <tr>
                                <td><?php echo $id + 4 ?></td>
                                <td style="width: 20%;">
                                    <select name="drug_type_id[]" class="form-control" id="type_name_id_<?php echo $id + 4 ?>" onchange="drug_name_load(this.id)" style="width:100%;">
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
                                    <select type="text" onchange="medicine_duplicate_check(this.id)" style="width: 100%" class="form-control" id="medicin_id_<?php echo $id + 4 ?>" name="drug_id[]">
                                        <option selected="" value="" disabled=""></option>

                                    </select>

                                </td>
                                <td style="width: 15%;">
                                    <select type="text" style="width: 100%" class="form-control" id="medicin_times_id_<?php echo $id + 4 ?>" name="medicin_times_id[]">
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

                                    <input type="text" style="width: 100%" class="form-control" oninput="validateIntegerInput(this)" id="days_<?php echo $id + 4 ?>" name="days[]">
                                </td>
                                <td style="width: 15%;">

                                    <select type="text" style="width: 100%" class="form-control" id="day_or_month_or_year_or_colbay_<?php echo $id + 4 ?>" name="day_or_month_or_year_or_colbay[]">
                                        <option>দিন</option>
                                        <option>মাস</option>
                                        <option>বছর</option>
                                        <option>চলবে</option>
                                    </select>
                                </td>
                                <td style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                            </tr>
                            <tr>
                                <td><?php echo $id + 5 ?></td>
                                <td style="width: 20%;">
                                    <select name="drug_type_id[]" class="form-control" id="type_name_id_<?php echo $id + 5 ?>" onchange="drug_name_load(this.id)" style="width:100%;">
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
                                    <select type="text" onchange="medicine_duplicate_check(this.id)" style="width: 100%" class="form-control" id="medicin_id_<?php echo $id + 5 ?>" name="drug_id[]">
                                        <option selected="" value="" disabled=""></option>

                                    </select>

                                </td>
                                <td style="width: 15%;">
                                    <select type="text" style="width: 100%" class="form-control" id="medicin_times_id_<?php echo $id + 5 ?>" name="medicin_times_id[]">
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

                                    <input type="text" style="width: 100%" class="form-control" oninput="validateIntegerInput(this)" id="days_<?php echo $id + 5 ?>" name="days[]">
                                </td>
                                <td style="width: 15%;">

                                    <select type="text" style="width: 100%" class="form-control" id="day_or_month_or_year_or_colbay_<?php echo $id + 5 ?>" name="day_or_month_or_year_or_colbay[]">
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
                            <?php
                            $id = 1;
                            foreach ($discharge_slip_diagnosis as $discharge_slip_diagnosis_value) {
                                $drug = $this->db->where('drug_id', $discharge_slip_medicin->drug_id)->get('drug')->row();
                            ?>

                                <tr>
                                    <td><?php echo $id ?></td>
                                    <td>
                                        <select name="diagnosis_id[]" onchange="diagnosis_duplicate_check(this.id)" class="form-control" id="diagnosis_<?php echo $id ?>" style="width:100%;">
                                            <option value="" disabled="" selected="">Diagnosis </option>
                                            <?php
                                            $diagnosis = $this->db->select('*')->get('diagnosis')->result();

                                            foreach ($diagnosis as $value) {
                                            ?>
                                                <option <?php echo $discharge_slip_diagnosis_value->diagnosis_id == $value->diagnosis_id ? 'selected' : '' ?> value="<?php echo $value->diagnosis_id ?>"><?php echo $value->diagnosis_name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>

                                    <td style="width: 2%;"><input type="button" onclick="load_diagnosis_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                    </td>
                                </tr>
                            <?php
                                $id++;
                            }
                            ?>
                            <tr>
                                <td>2</td>
                                <td>
                                    <select name="diagnosis_id[]" onchange="diagnosis_duplicate_check(this.id)" class="form-control" id="diagnosis_<?php echo $id + 1 ?>" style="width:100%;">
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
                                    <select name="diagnosis_id[]" onchange="diagnosis_duplicate_check(this.id)" class="form-control" id="diagnosis_<?php echo $id + 2 ?>" style="width:100%;">
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
                                    <select name="diagnosis_id[]" onchange="diagnosis_duplicate_check(this.id)" class="form-control" id="diagnosis_<?php echo $id + 3 ?>" style="width:100%;">
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
                                    <select name="diagnosis_id[]" onchange="diagnosis_duplicate_check(this.id)" class="form-control" id="diagnosis_<?php echo $id + 4 ?>" style="width:100%;">
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
                                    <select name="diagnosis_id[]" onchange="diagnosis_duplicate_check(this.id)" class="form-control" id="diagnosis_<?php echo $id + 5 ?>" style="width:100%;">
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
                        <input type="text" name="custom_diagnosis" value="<?php echo $discharge_slip->custom_diagnosis ?>" class="form-control" id="custom_diagnosis" placeholder="Enter Custom Diagnosis">
                        <div class="form-group" style="margin-top:10px;">
                            <div class="col-sm-8">
                                <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <table id="advice_table" class="table table-bordered table-hover table-striped">

                            <?php
                            $id = 1;
                            foreach ($discharge_slip_advices as $discharge_slip_advice) {
                            ?>
                                <tr>
                                    <td><?php echo $id ?></td>
                                    <td>
                                        <select name="advice_id[]" class="form-control" onchange="advice_duplicate_check(this.id)" id="advice_<?php echo $id ?>" style="width:100%;">
                                            <option value="" disabled="" selected="">Advice</option>
                                            <?php
                                            $advice = $this->db->select('*')->get('advice')->result();

                                            foreach ($advice as $value) {
                                            ?>
                                                <option <?php echo $discharge_slip_advice->advice_id == $value->advice_id ? 'selected' : '' ?> value="<?php echo $value->advice_id ?>"><?php echo $value->advice_name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>

                                    <td style="width: 2%;"><input type="button" onclick="load_advice_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                    </td>
                                </tr>
                            <?php
                                $id++;
                            }
                            ?>
                            <tr>
                                <td><?php echo $id + 1 ?></td>
                                <td>
                                    <select name="advice_id[]" class="form-control" onchange="advice_duplicate_check(this.id)" id="advice_<?php echo $id + 1 ?>" style="width:100%;">
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
                                <td><?php echo $id + 2 ?></td>
                                <td>
                                    <select name="advice_id[]" class="form-control" onchange="advice_duplicate_check(this.id)" id="advice_<?php echo $id + 2 ?>" style="width:100%;">
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
                                <td><?php echo $id + 3 ?></td>
                                <td>
                                    <select name="advice_id[]" class="form-control" onchange="advice_duplicate_check(this.id)" id="advice_<?php echo $id + 3 ?>" style="width:100%;">
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
                                <td><?php echo $id + 4 ?></td>
                                <td>
                                    <select name="advice_id[]" class="form-control" onchange="advice_duplicate_check(this.id)" id="advice_<?php echo $id + 4 ?>" style="width:100%;">
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
                                <td><?php echo $id + 5 ?></td>
                                <td>
                                    <select name="advice_id[]" class="form-control" onchange="advice_duplicate_check(this.id)" id="advice_<?php echo $id + 5 ?>" style="width:100%;">
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
                        <input type="text" name="custom_advice" class="form-control" value="<?php echo $discharge_slip->custom_advice ?>" id="custom_advice" placeholder="Enter Custom Advice">

                    </div>

                </div>



        </div>


    </div>

    </form>
</div>