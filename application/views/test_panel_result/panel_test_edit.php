<?php
$require_lab_test_group = $this->db->field_exists('test_group_id', 'lab_reports');
$report = isset($report) ? $report : null;
$report_id = isset($report_id) ? (int) $report_id : 0;
if (!$report || $report_id < 1) {
    echo '<p class="text-danger">Invalid report.</p>';

    return;
}
$panel_id = isset($report->panel_id) ? (int) $report->panel_id : 0;
$panel_label = isset($report->panel_name) ? (string) $report->panel_name : '';
$invoice_val = isset($report->patient_id) ? (string) $report->patient_id : '';
$patient_name_val = isset($report->patient_name) ? (string) $report->patient_name : '';
$age_y = isset($report->age_year) ? (string) $report->age_year : '';
$age_m = isset($report->age_month) ? (string) $report->age_month : '';
$age_d = isset($report->age_day) ? (string) $report->age_day : '';
$sex_val = isset($report->sex) ? (string) $report->sex : '';
$report_date_disp = isset($report->report_date) ? (string) $report->report_date : '';
$saved_test_group_id = 0;
if (!empty($require_lab_test_group) && isset($report->test_group_id)) {
    $saved_test_group_id = (int) $report->test_group_id;
}
?>
<script>
    $(document).ready(function() {
        <?php if (!empty($require_lab_test_group)) { ?>
        $('#test_group_id').select2();
        <?php } ?>
        $('#invoice_no').focus();
    });

    function panel_test_load(panel_test_id, report_id) {
        var $cfg = $('#test_configuration');
        $cfg.empty();
        if (!panel_test_id) {
            return;
        }
        $('#img').show();
        var postData = {
            panel_test_id: panel_test_id
        };
        if (report_id) {
            postData.report_id = report_id;
        }
        $.ajax({
            url: "<?php echo site_url('TestPanelResultController/panel_test_load'); ?>",
            type: 'POST',
            data: postData,
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

                var setValue = function(id, value) {
                    var el = document.getElementById(id);
                    if (el) {
                        el.value = (value === undefined || value === null) ? '' : value;
                    }
                };

                setValue('patient_test_entry_id', patient_array[0]);
                setValue('patient_name', patient_array[1]);
                setValue('mobile_number', patient_array[2]);
                setValue('age', patient_array[3]);
                setValue('gender', patient_array[4]);
                setValue('invoice_date', patient_array[5]);
                setValue('invoice_time', patient_array[6]);
                setValue('age_year', patient_array[7]);
                setValue('age_month', patient_array[8]);
                setValue('age_day', patient_array[9]);

                $('#img').hide();
            }
        }
        xhttp.open("POST", "<?php echo site_url('TestResultController/patient_data_load_by_test_invoice_no'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("invoice_no=" + invoice_no);
    }

    $(document).ready(function() {
        var EDIT_REPORT_ID = <?php echo (int) $report_id; ?>;
        var PANEL_ID = <?php echo (int) $panel_id; ?>;
        if (PANEL_ID > 0) {
            panel_test_load(PANEL_ID, EDIT_REPORT_ID);
        }

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

        $("#test_result_entry_form").validate({
            rules: {
                invoice_no: "required",
                <?php if (!empty($require_lab_test_group)) { ?>
                test_group_id: "required",
                <?php } ?>
            },
            messages: {
                invoice_no: "Enter invoice no",
                <?php if (!empty($require_lab_test_group)) { ?>
                test_group_id: "Select test group name",
                <?php } ?>
            }
        });

        $('#test_result_entry_form').on('submit', function(e) {
            e.preventDefault();
            if (window.__panelTestEditSubmitting) {
                return false;
            }

            var panel_test_id = $('#panel_test_id').val();
            if (!panel_test_id) {
                $.toast({
                    heading: 'Error',
                    text: 'Panel is missing.',
                    showHideTransition: 'slide',
                    position: 'top-right',
                    hideAfter: 3000,
                    icon: 'error'
                });
                return false;
            }

            if (!$('#test_result_entry_form').valid()) {
                return false;
            }

            window.__panelTestEditSubmitting = true;
            var $btn = $('#submit_button');
            var formData = new FormData($('#test_result_entry_form')[0]);
            var $togglable = $('#test_result_entry_form').find('input, select, textarea, button').not('[type=hidden]');
            $togglable.prop('disabled', true);
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                type: 'POST',
                url: "<?php echo site_url('TestPanelResultController/update_panel_test'); ?>",
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done(function(res) {
                if (res && res.success) {
                    $.toast({
                        heading: 'Success',
                        text: res.message || 'Updated',
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
                        text: (res && res.message) ? res.message : 'Update failed.',
                        showHideTransition: 'slide',
                        position: 'top-right',
                        hideAfter: 3000,
                        icon: 'error'
                    });
                    $('#test_result_entry_form').find('input, select, textarea, button').not('[type=hidden]').prop('disabled', false);
                    $btn.prop('disabled', false).html('Update');
                    window.__panelTestEditSubmitting = false;
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
                $('#test_result_entry_form').find('input, select, textarea, button').not('[type=hidden]').prop('disabled', false);
                $btn.prop('disabled', false).html('Update');
                window.__panelTestEditSubmitting = false;
            });
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
            <h3 style="text-align: center">Edit Test Result</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" id="test_result_entry_form" method="post" enctype='multipart/form-data'>
                <input type="hidden" name="report_id" id="report_id" value="<?php echo (int) $report_id; ?>">
                <input type="hidden" name="panel_test_id" id="panel_test_id" value="<?php echo (int) $panel_id; ?>">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Invoice No</label>
                            <div class="col-sm-4">
                                <input type="hidden" id="patient_test_entry_id" name="patient_test_entry_id">
                                <input type="text" class="form-control" placeholder="Enter or Scan invoice no ..." id="invoice_no" name="invoice_no"
                                    value="<?php echo html_escape($invoice_val); ?>">
                            </div>
                            <div class="col-sm-4">
                                <select class="form-control" onchange="manual_or_dynamic_report_data(this.value)" id="manual_or_dynamic_report" name="manual_or_dynamic_report">
                                    <option selected>Dynamic</option>
                                    <option>Manual</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Age</label>
                            <div class="col-sm-2">
                                <input type="text" placeholder="Year" oninput="validateIntegerInput(this)" class="form-control" id="age_year" name="age_year"
                                    value="<?php echo html_escape($age_y); ?>">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" placeholder="Month" oninput="validateIntegerInput(this)" class="form-control" id="age_month" name="age_month"
                                    value="<?php echo html_escape($age_m); ?>">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" placeholder="Day" oninput="validateIntegerInput(this)" class="form-control" id="age_day" name="age_day"
                                    value="<?php echo html_escape($age_d); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Patient's Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="<?php echo html_escape($patient_name_val); ?>" placeholder="Patient Name" id="patient_name" name="patient_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Gender</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Gender" class="form-control" id="gender" name="gender"
                                    value="<?php echo html_escape($sex_val); ?>">
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
                            <label class="control-label col-sm-4" for="pwd">Report date</label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control" value="<?php echo html_escape($report_date_disp); ?>">
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

                <div class="row" style="margin-top:20px">
                    <?php if (!empty($require_lab_test_group)) { ?>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="test_group_id">Test group</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="test_group_id" name="test_group_id" required>
                                        <option value="">Select test group</option>
                                        <?php
                                        $test_groups = $this->db->select('test_group_id, test_group_name')->order_by('test_group_name', 'ASC')->get('test_group')->result();
                                        foreach ($test_groups as $tg_row) {
                                            $sel = ((int) $tg_row->test_group_id === $saved_test_group_id) ? ' selected' : '';
                                        ?>
                                            <option value="<?php echo (int) $tg_row->test_group_id; ?>"<?php echo $sel; ?>><?php echo html_escape($tg_row->test_group_name); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Panel test</label>
                            <div class="col-sm-8">
                                <p class="form-control-static" style="margin:0;padding-top:7px;">
                                    <strong><?php echo html_escape($panel_label); ?></strong>
                                </p>
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
                                <button id="submit_button" name="submit_button" type="submit" class="btn btn-primary">Update</button>
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
