<script>
    $(document).ready(function() {
        $('#test_group_id').select2();
    });

    function clearPatientAndTests() {
        $('#patient_test_entry_id').val('');
        $('#patient_name').val('');
        $('#mobile').val('');
        $('#age').val('');
        $('#gender').val('');
        $('#invoice_no').val('');
        $('#test_configuration').empty();
    }

    function setInvoiceEnabled(enabled) {
        var $inv = $('#invoice_no');
        if (enabled) {
            $inv.prop('disabled', false).attr('placeholder', 'Enter or Scan invoice no ...');
        } else {
            $inv.prop('disabled', true).attr('placeholder', 'Select test group first...');
        }
    }

    function test_configuration_load() {
        var test_group_id = $('#test_group_id').val();
        var patient_test_entry_id = $('#patient_test_entry_id').val();

        if (!test_group_id) {
            $('#test_configuration').html('<p class="text-muted">Select a test group, then choose an invoice.</p>');
            return;
        }
        if (!patient_test_entry_id) {
            $('#test_configuration').empty();
            return;
        }

        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("test_configuration").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        xhttp.open("POST", "<?php echo site_url('TestResultController/test_configuration_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("test_group_id=" + encodeURIComponent(test_group_id) + "&patient_test_entry_id=" + encodeURIComponent(patient_test_entry_id));
    }

    function patient_data_set(invoice_no) {
        if (!$('#test_group_id').val()) {
            return;
        }
        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var patient = xhttp.responseText;
                var patient_array = patient.split('*');
                document.getElementById("patient_test_entry_id").value = patient_array[0];
                document.getElementById("patient_name").value = patient_array[1];
                var mob = document.getElementById("mobile");
                if (mob) {
                    mob.value = patient_array[2];
                }
                document.getElementById("age").value = patient_array[3];
                document.getElementById("gender").value = patient_array[4];
                test_configuration_load();
                $('#img').hide();
            }
        }
        xhttp.open("POST", "<?php echo site_url('TestResultController/patient_data_load_by_test_invoice_no'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("invoice_no=" + encodeURIComponent(invoice_no));
    }

    function test_name_load(test_group_id) {

        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var el = document.getElementById("test_id");
                if (el) {
                    el.innerHTML = xhttp.responseText;
                }
                $('#img').hide();
            }
        }
        xhttp.open("POST", "<?php echo site_url('TestResultController/test_name_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("test_group_id=" + encodeURIComponent(test_group_id));
    }

    function patient_info_load(patient_test_entry_id) {
        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                //  var data_array = xhttp.responseText.split("_");
                var data_array = xhttp.responseText.split("_");
                document.getElementById("age").value = data_array[0]; /*age*/
                document.getElementById("invoice_no").value = data_array[1]; /*invoice no*/
                // document.getElementById("datepicker").value = data_array[2];/*date*/
                document.getElementById("mobile").value = data_array[3]; /*mobile*/
                document.getElementById("gender").value = data_array[4]; /*gender*/
                document.getElementById("doctor_name").value = data_array[5]; /*doctor*/
                $('#img').hide();
                // document.getElementById("stock_" + id_no[2]).value = data_array[1];/*stock*/
            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('TestResultController/patient_info_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("patient_test_entry_id=" + patient_test_entry_id);
    }

    $(document).ready(function() {
        $("#invoice_no").autocomplete({
            source: function(request, response) {
                if (!$('#test_group_id').val()) {
                    response([]);
                    return;
                }
                $.ajax({
                    url: "<?php echo site_url('TestController/invoice_no_load'); ?>",
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
                if (!$('#test_group_id').val()) {
                    return false;
                }
                $('#invoice_no').val(ui.item.label);
                patient_data_set(ui.item.value);
                return false;
            }
        });

        setInvoiceEnabled(!!$('#test_group_id').val());
        $('#test_group_id').on('change', function() {
            var gid = $(this).val();
            if (!gid) {
                clearPatientAndTests();
                setInvoiceEnabled(false);
                return;
            }
            setInvoiceEnabled(true);
            if ($('#patient_test_entry_id').val()) {
                test_configuration_load();
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {
            e.preventDefault();

            var manual_or_dynamic_report = $('#manual_or_dynamic_report').val();

            // Manual report file check
            if (manual_or_dynamic_report === 'Manual') {
                var manualReportFile = $('#manual_report').val(); // Get the file value
                if (manualReportFile == '') {
                    // Show error if no file is selected
                    $.toast({
                        heading: 'Error',
                        text: 'Please select a file to upload for Manual Report.',
                        showHideTransition: 'slide',
                        position: 'top-right',
                        hideAfter: 3000,
                        icon: 'error'
                    });
                    return; // Stop the form submission
                }
            }

            // Dynamic report input field check (test_configuration_value array)
            if (manual_or_dynamic_report === 'Dynamic') {
                var dynamicInputs = $('input[name="test_configuration_value[]"]'); // Get all input fields with name="test_configuration_value[]"
                var isValid = false;

                dynamicInputs.each(function() {
                    if ($(this).val() != '') { // Check if any of the inputs are filled
                        isValid = true;
                        return false; // Stop the loop if we find a filled input
                    }
                });

                if (!isValid) {
                    // Show error if none of the dynamic inputs are filled
                    $.toast({
                        heading: 'Error',
                        text: 'Please fill up the Dynamic Report configuration values.',
                        showHideTransition: 'slide',
                        position: 'top-right',
                        hideAfter: 3000,
                        icon: 'error'
                    });
                    return; // Stop the form submission
                }
            }


            var submitBtn = $(this);
            var formData = new FormData($('#test_result_entry_form')[0]); // Create FormData object with form data

            // Check if the form is valid
            $('#invoice_no').prop('disabled', false);
            if ($("#test_result_entry_form").valid()) {
                $('#test_result_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('TestResultController/edit_test_result_data_save'); ?>",
                    data: formData,
                    dataType: "json",
                    processData: false, // Important: tell jQuery not to process the data
                    contentType: false, // Important: tell jQuery not to set contentType
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
                            $('#test_result_entry_form')[0].reset();
                            $('#test_group_id').trigger('change');
                            $('#test_result_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('test-result-report-print') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#test_result_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#test_result_entry_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });

    function manual_or_dynamic_report_data(value) {
        if (value == 'Dynamic') {
            document.getElementById('test_configuration').style.display = 'block';
            document.getElementById('manual_report_container').style.display = 'none';
        } else if (value == 'Manual') {
            document.getElementById('test_configuration').style.display = 'none';
            document.getElementById('manual_report_container').style.display = 'block';
        }
    }
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Edit Test Result</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" id="test_result_entry_form" method="post" enctype='multipart/form-data'>

                <?php
                error_reporting(0);
                $test_result = $this->db->where('test_result_id', $test_result_id)
                    ->get('test_result')->row();
                $test_result_details = $this->db->where('test_result_id', $test_result_id)
                    ->get('test_result_details')->result();

                $patient_test_entry = $this->db->where('patient_test_entry_id', $test_result->patient_test_entry_id)
                    ->get('patient_test_entry')->row();

                $doctor = $this->db->where('doctor_id', $test_result->doctor_id)
                    ->get('doctor')->row();
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="test_group_id">Test Group *</label>
                            <div class="col-sm-8">
                                <select required class="form-control" id="test_group_id" name="test_group_id">
                                    <option value="">Select Test Group</option>
                                    <?php
                                    $all_test_groups = $this->db->select('*')->order_by('test_group_name', 'ASC')->get('test_group')->result();
                                    foreach ($all_test_groups as $value) {
                                        $g_sel = ((int) $value->test_group_id === (int) $test_result->test_group_id) ? ' selected' : '';
                                    ?>
                                        <option value="<?php echo (int) $value->test_group_id; ?>"<?php echo $g_sel; ?>><?php echo html_escape($value->test_group_name); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Invoice No *</label>
                            <div class="col-sm-4">
                                <input type="hidden" name="test_result_id" id="test_result_id" value="<?php echo $test_result_id ?>">
                                <input type="hidden" value="<?php echo $test_result->patient_test_entry_id ?>" id="patient_test_entry_id" name="patient_test_entry_id">
                                <input type="text" class="form-control" value="<?php echo html_escape($test_result->invoice_no); ?>" placeholder="Select test group first..." id="invoice_no" name="invoice_no" disabled autocomplete="off">
                            </div>
                            <div class="col-sm-4">
                                <select class="form-control" onchange="manual_or_dynamic_report_data(this.value)" id="manual_or_dynamic_report" name="manual_or_dynamic_report">
                                    <option <?php echo $test_result->manual_or_dynamic_report == 'Dynamic' ? "selected" : "" ?>>Dynamic</option>
                                    <option <?php echo $test_result->manual_or_dynamic_report == 'Manual' ? "selected" : "" ?>>Manual</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Age</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" value="<?php echo $patient_test_entry->age ?>" class="form-control" placeholder="Enter Age" id="age" name="age">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Patient Name</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" class="form-control" value="<?php echo $patient_test_entry->patient_name . '-' . $patient_test_entry->invoice_no; ?>" placeholder="Patent Name" id="patient_name" name="patient_name">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Sex</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" class="form-control" value="<?php echo html_escape($patient_test_entry->gender); ?>" id="gender" name="gender">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">



                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Entry Date</label>
                            <div class="col-sm-8">
                                <input type="text" value="<?php echo date('d-m-y', strtotime($test_result->date)) ?>" class="form-control" id="datepicker" name="date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Mobile</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" class="form-control" value="<?php echo $patient_test_entry->mobile ?>" id="mobile" name="mobile">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Ref. Doctor</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" class="form-control" value="<?php echo $doctor->doctor_name ?>" id="doctor_name" name="doctor_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Test No</label>
                            <div class="col-sm-8">
                                <?php
                                $serial = $this->db->select('*')->get('test_result');
                                $serial = 'TR' . str_pad($serial->num_rows() + 1, 5, '0', STR_PAD_LEFT);
                                ?>
                                <input type="text" readonly="" class="form-control" id="test_result_no" value="<?php echo $test_result->test_result_no ?>" name="test_result_no">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <?php
                    $manual_report_display = 'none';
                    $dynamic_report_display = 'none';
                    if ($test_result->manual_or_dynamic_report == 'Dynamic') {
                        $dynamic_report_display = 'block';
                    }
                    if ($test_result->manual_or_dynamic_report == 'Manual') {
                        $manual_report_display = 'block';
                    }

                    ?>
                    <div class="col-md-6" id="manual_report_container" style="display: <?php echo $manual_report_display ?>">
                        <div class="form-group">

                            <label class="control-label col-sm-4" for="name">Or Manual Report Upload</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" id="manual_report" name="manual_report">
                                <input type="hidden" value="<?php echo $test_result->manual_report ?>" readonly class="form-control" id="manual_report_previous" name="manual_report_previous">
                            </div>

                        </div>
                    </div>
                </div>
                <hr>
                <div id="test_configuration" style="display: <?php echo $dynamic_report_display ?>;">
                    <?php
                    $check = 1;
                    foreach ($test_result_details as $value) {

                        $test = $this->db
                            ->where('test_id', $value->test_id)
                            ->get('test')->row();
                        if ($check % 2 != 0):
                    ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name"><?php echo $test->test_name ?></label>
                                        <div class="col-sm-8">
                                            <input type="hidden" class="form-control" value="<?php echo $value->test_id ?>" id="test_id" name="test_id[]">
                                            <!--<input type="hidden"  class="form-control" value="<?php echo $value->test_configuration_id ?>"  id="test_configuration_id"  name="test_configuration_id[]">-->
                                            <input type="text" class="form-control" value="<?php echo $value->test_configuration_value ?>" id="test_configuration_value" name="test_configuration_value[]">
                                        </div>
                                    </div>
                                </div>
                            <?php
                        endif;
                        if ($check % 2 == 0):
                            ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name"><?php echo $test->test_name ?></label>
                                        <div class="col-sm-8">
                                            <input type="hidden" class="form-control" value="<?php echo $value->test_id ?>" id="test_id" name="test_id[]">
                                            <!--<input type="hidden"  class="form-control" value="<?php echo $value->test_configuration_id ?>"  id="test_configuration_id"  name="test_configuration_id[]">-->
                                            <input type="text" class="form-control" id="test_configuration_value" value="<?php echo $value->test_configuration_value ?>" name="test_configuration_value[]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        endif;
                        $check++;
                    }
                    ?>

                </div>
                <div class="row">

                    <div class="col-md-8">
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-4">

                                <button id="submit_button" name="submit_button" type="submit" class="btn btn-primary">Update</button>
                            </div>

                        </div>
                        <div class="col-sm-4">
                            <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />

                        </div>
                    </div>
                </div>


            </form>

        </div>
    </div>
</div>