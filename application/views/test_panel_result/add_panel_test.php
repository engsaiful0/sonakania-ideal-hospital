<?php $require_lab_test_group = $this->db->field_exists('test_group_id', 'lab_reports'); ?>
<script>
    $(document).ready(function() {
        <?php //if (!empty($require_lab_test_group)) { ?>
        $('#test_group_id').select2({ width: '100%' });
        $('#panel_test_id').select2({ width: '100%' });
        <?php //} ?>
        $('#invoice_no').focus();

        $('#test_group_id').on('change', function() {
            test_group_change_panel_load($(this).val());
        });
    });

    /**
     * Load panel dropdown options for the selected test group (test_panels.test_group_id).
     */
    function test_group_change_panel_load(test_group_id) {
        var $p = $('#panel_test_id');
        var $cfg = $('#test_configuration');
        $cfg.empty();

        if ($p.data('select2')) {
            $p.select2('destroy');
        }
        $p.empty();
        if (!test_group_id) {
            $p.append($('<option></option>').attr('value', '').text('Select test group first'));
            $p.select2({ width: '100%' });
            return;
        }

        $p.append($('<option></option>').attr('value', '').text('Select Panel Test'));
        $p.select2({ width: '100%' });

        $('#img').show();
        $.ajax({
            url: "<?php echo site_url('TestPanelResultController/panels_by_test_group'); ?>",
            type: 'POST',
            data: { test_group_id: test_group_id },
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(res) {
            if ($p.data('select2')) {
                $p.select2('destroy');
            }
            $p.empty();
            if (res && res.success && res.panels && res.panels.length) {
                $p.append($('<option></option>').attr('value', '').text('Select Panel Test'));
                $.each(res.panels, function(i, row) {
                    $p.append($('<option></option>').attr('value', row.id).text(row.panel_name));
                });
            } else if (res && res.success) {
                $p.append($('<option></option>').attr('value', '').text('No panels for this group'));
            } else {
                $p.append($('<option></option>').attr('value', '').text('Select Panel Test'));
            }
            $p.select2({ width: '100%' });
            $p.val('').trigger('change');
        }).fail(function() {
            if (typeof $.toast === 'function') {
                $.toast({
                    heading: 'Error',
                    text: 'Could not load panels for this test group.',
                    showHideTransition: 'slide',
                    position: 'top-right',
                    hideAfter: 4000,
                    icon: 'error'
                });
            } else {
                alert('Could not load panels for this test group.');
            }
        }).always(function() {
            $('#img').hide();
        });
    }

    function panel_test_load(panel_test_id) {
        var $cfg = $('#test_configuration');
        $cfg.empty();
        if (!panel_test_id) {
            return;
        }
        $('#img').show();
        $.ajax({
            url: "<?php echo site_url('TestPanelResultController/panel_test_load'); ?>",
            type: 'POST',
            data: { panel_test_id: panel_test_id },
            dataType: 'html'
        }).done(function(html) {
            $cfg.html(html).show();
            $('#manual_or_dynamic_report').val('Dynamic');
            $('#manual_report_container').hide();
        }).fail(function() {
            $cfg.html('<p class="text-danger">Could not load panel test inputs.</p>');
        }).always(function() {
            $('#img').hide();
        });
    }





    function patient_data_set(invoice_no) {
        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var patient = xhttp.responseText;
                var patient_array = patient.split('*');

                // Safely assign a value to an input by id; ignore inputs that
                // don't exist on this form (e.g. plain `age` is omitted here
                // because age is split into age_year/age_month/age_day).
                var setValue = function(id, value) {
                    var el = document.getElementById(id);
                    if (el) {
                        el.value = (value === undefined || value === null) ? '' : value;
                    }
                };

                setValue('patient_test_entry_id', patient_array[0]);
                setValue('patient_name',          patient_array[1]);
                setValue('mobile_number',         patient_array[2]);
                setValue('age',                   patient_array[3]);
                setValue('gender',                patient_array[4]);
                setValue('invoice_date',          patient_array[5]);
                setValue('invoice_time',          patient_array[6]);
                setValue('age_year',              patient_array[7]);
                setValue('age_month',             patient_array[8]);
                setValue('age_day',               patient_array[9]);

                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('TestResultController/patient_data_load_by_test_invoice_no'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("invoice_no=" + invoice_no);
    }
    $(document).ready(function() {

        $("#invoice_no").autocomplete({
            source: function(request, response) {
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
                $('#invoice_no').val(ui.item.label);
                patient_data_set(ui.item.value);
                return false;
            }
        });

        // Validate the form
        $("#test_result_entry_form").validate({
            rules: {
                invoice_no: "required",
                
                test_group_id: "required",
                
            },
            messages: {
                invoice_no: "Enter invoice no",
                
                test_group_id: "Select test group name",
                
            }
        });

        // Single handler: Enter in a field triggers form submit, not button click — prevent native POST + AJAX double-save.
        $('#test_result_entry_form').on('submit', function(e) {
            e.preventDefault();
            if (window.__panelTestFormSubmitting) {
                return false;
            }

            var $btn = $('#submit_button');
            var manual_or_dynamic_report = $('#manual_or_dynamic_report').val();
            var panel_test_id = $('#panel_test_id').val();

            // Panel-test mode: when a panel is selected, save via the panel endpoint and print.
            if (panel_test_id) {
                if (!$('#test_result_entry_form').valid()) {
                    return false;
                }

                window.__panelTestFormSubmitting = true;
                var formData = new FormData($('#test_result_entry_form')[0]);
                $('#test_result_entry_form :input').prop('disabled', true);
                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

                $.ajax({
                    type: 'POST',
                    url: "<?php echo site_url('TestPanelResultController/save_panel_test'); ?>",
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).done(function(res) {
                    if (res && res.success) {
                        $.toast({
                            heading: 'Success',
                            text: res.message || 'Saved',
                            showHideTransition: 'slide',
                            position: 'top-right',
                            hideAfter: 1200,
                            icon: 'success'
                        });
                        setTimeout(function() {
                            window.location.href = res.print_url;
                        }, 700);
                    } else {
                        $.toast({
                            heading: 'Error',
                            text: (res && res.message) ? res.message : 'Save failed.',
                            showHideTransition: 'slide',
                            position: 'top-right',
                            hideAfter: 3000,
                            icon: 'error'
                        });
                        window.__panelTestFormSubmitting = false;
                        $('#test_result_entry_form :input').prop('disabled', false);
                        $btn.prop('disabled', false).html('Submit');
                    }
                }).fail(function() {
                    $.toast({
                        heading: 'Error',
                        text: 'Request failed.',
                        showHideTransition: 'slide',
                        position: 'top-right',
                        hideAfter: 3000,
                        icon: 'error'
                    });
                    window.__panelTestFormSubmitting = false;
                    $('#test_result_entry_form :input').prop('disabled', false);
                    $btn.prop('disabled', false).html('Submit');
                });
                return false;
            }

            // Check if the form is valid
            if ($("#test_result_entry_form").valid()) {

                // Manual report file check
                if (manual_or_dynamic_report == 'Manual') {
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
                        return false; // Stop the form submission
                    }
                }

                // Dynamic report input field check (test_configuration_value array)
                if (manual_or_dynamic_report == 'Dynamic') {
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
                        return false; // Stop the form submission
                    }
                }

                window.__panelTestFormSubmitting = true;
                var formData = new FormData($('#test_result_entry_form')[0]);
                $('#test_result_entry_form :input').prop('disabled', true);
                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('TestResultController/add_test_result_data_save'); ?>",
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
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('test-result-report-print') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            window.__panelTestFormSubmitting = false;
                            $('#test_result_entry_form :input').prop('disabled', false);
                            $btn.prop('disabled', false).html('Submit');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        window.__panelTestFormSubmitting = false;
                        $('#test_result_entry_form :input').prop('disabled', false);
                        $btn.prop('disabled', false).html('Submit');
                    }
                });
            }
            return false;
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
            <h3 style="text-align: center">Add Panel Test</h3>
        </div>
        <div class="panel-body">
            <?php
            error_reporting(0);
            $biomedical_test = $this->db->where('biomedical_test_id', $biomedical_test_id)
                ->get('biomedical_test')->row();
            $patient_test_entry = $this->db->where('patient_test_entry_id', $biomedical_test->patient_test_entry_id)
                ->get('patient_test_entry')->row();
            $doctor = $this->db->where('doctor_id', $patient_test_entry->doctor_id)
                ->get('doctor')->row();
            ?>
            <form class="form-horizontal" id="test_result_entry_form" method="post" enctype='multipart/form-data'>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Invoice No</label>
                            <div class="col-sm-4">
                                <input type="hidden" id="patient_test_entry_id" name="patient_test_entry_id">
                                <input type="text" class="form-control" placeholder="Enter or Scan invoice no ..." id="invoice_no" name="invoice_no">
                            </div>
                            <div class="col-sm-4">
                                <select class="form-control" onchange="manual_or_dynamic_report_data(this.value)" id="manual_or_dynamic_report" name="manual_or_dynamic_report">
                                    <option>Dynamic</option>
                                    <option>Manual</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Age</label>
                            <div class="col-sm-3">
                                <input type="text" placeholder="Year" oninput="validateIntegerInput(this)" class="form-control" id="age_year" name="age_year">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" placeholder="Month" oninput="validateIntegerInput(this)" class="form-control" id="age_month" name="age_month">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" placeholder="Day" oninput="validateIntegerInput(this)" class="form-control" id="age_day" name="age_day">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Patient's Name</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" class="form-control" value="" placeholder="Patient Name" id="patient_name" name="patient_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Gender</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" placeholder="Gender" class="form-control" id="gender" name="gender">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Invoice Date & Time</label>
                            <div class="col-sm-4">
                                <input readonly type="text" placeholder="Invoice Date" class="form-control" id="invoice_date" name="invoice_date">
                            </div>
                            <div class="col-sm-4">
                                <input readonly type="text" placeholder="Invoice Time" class="form-control" id="invoice_time" name="invoice_time">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Mobile</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" placeholder="Mobile" class="form-control" id="mobile_number" name="mobile_number">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top:20px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Entry Date</label>
                            <div class="col-sm-4">
                                <input type="text" placeholder="Date" value="<?php echo date('d-m-y') ?>" class="form-control" id="datepicker" name="date">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" placeholder="Time" value="<?php echo date('H:i:s') ?>" class="form-control" id="datepicker" name="time">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Address</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" placeholder="Address" class="form-control" id="address" name="address">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-6" style="display: none;">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Test No</label>
                            <div class="col-sm-8">
                                <?php
                                $serial = $this->db->select('*')->get('test_result');
                                $serial = 'TR' . str_pad($serial->num_rows() + 1, 5, '0', STR_PAD_LEFT);
                                ?>
                                <input type="text" readonly="" class="form-control" id="test_result_no" value="<?php echo $serial ?>" name="test_result_no">
                            </div>
                        </div>

                    </div>
                
                <div class="row" style="margin-top:20px">
                
                
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="test_group_id">Test group</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="test_group_id" name="test_group_id" required>
                                    <option value="">Select test group</option>
                                    <?php
                                    $test_groups = $this->db->select('test_group_id, test_group_name')->order_by('test_group_name', 'ASC')->get('test_group')->result();
                                    foreach ($test_groups as $tg_row) {
                                    ?>
                                        <option value="<?php echo (int) $tg_row->test_group_id; ?>"><?php echo html_escape($tg_row->test_group_name); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                
                
                
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Panel Test *</label>
                            <div class="col-sm-8">
                                <?php 
                                
                                $panel_test = getAllPanelTest();
                                ?>
                                <select required class="form-control select2" onchange="panel_test_load(this.value)" id="panel_test_id" name="panel_test_id">
                                    <option value="">Select Panel Test</option>
                                    <?php foreach ($panel_test as $value) { ?>
                                        <option value="<?php echo $value->id; ?>"><?php echo $value->panel_name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="manual_report_container" style="display: none;">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Or Manual Report Upload</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" id="manual_report" name="manual_report">
                            </div>
                        </div>
                    </div>



                </div>
                <hr>
                <div id="test_configuration">

                </div>
                <div class="row">

                    <div class="col-md-5">
                        <div class="form-group">
                            <div class="col-sm-offset-5 col-sm-3">
                                <button id="submit_button" name="submit_button" type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </div>
                        <div class="col-sm-4">
                            <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none;z-index:1" />

                        </div>
                    </div>
                </div>


            </form>

        </div>
    </div>
</div>