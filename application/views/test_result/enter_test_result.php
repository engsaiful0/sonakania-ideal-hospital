<?php
$entry = isset($entry) ? $entry : (object) array();
$entries = isset($entries) && is_array($entries) ? $entries : array($entry);
$patient = isset($patient) ? $patient : (object) array();
$edit_mode = !empty($edit_mode);
$multi_mode = !empty($multi_mode);
$test_result_id = isset($test_result_id) ? (int) $test_result_id : 0;
$existing_values = isset($existing_values) && is_array($existing_values) ? $existing_values : array();
$existing_value = isset($existing_value) ? (string) $existing_value : '';
$entry_id = isset($entry->patient_test_entry_details_id) ? (int) $entry->patient_test_entry_details_id : 0;
$selected_detail_ids = isset($selected_detail_ids) ? (string) $selected_detail_ids : (string) $entry_id;
$test_result = isset($test_result) ? $test_result : null;
if ($edit_mode && $test_result && !empty($test_result->date)) {
    $now_date = date('d-m-y', strtotime($test_result->date));
    $now_time = !empty($test_result->time) ? $test_result->time : date('H:i:s');
} else {
    $now_date = !empty($entry->test_date) ? date('d-m-y', strtotime($entry->test_date)) : date('d-m-y');
    $now_time = !empty($entry->test_time) ? $entry->test_time : date('H:i:s');
}
$mobile = '';
if (isset($patient->mobile_number) && trim((string) $patient->mobile_number) !== '') {
    $mobile = $patient->mobile_number;
} elseif (isset($patient->mobile)) {
    $mobile = $patient->mobile;
}
$group_label = isset($entry->test_group_name) ? $entry->test_group_name : '';
?>
<div class="panel panel-primary">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left" style="margin-top:6px;">
            <?php echo $edit_mode ? 'Edit Test Result' : 'Enter Test Result'; ?>
            <?php if ($multi_mode && $group_label !== '') { ?>
                <small>— <?php echo html_escape($group_label); ?> (<?php echo count($entries); ?> tests)</small>
            <?php } ?>
        </h3>
        <a href="<?php echo isset($back_url) ? html_escape($back_url) : site_url('add-test-result'); ?>" class="btn btn-default btn-sm pull-right">
            <i class="glyphicon glyphicon-arrow-left"></i> Back to list
        </a>
    </div>
    <div class="panel-body">
        <div id="result-message"></div>
        <form class="form-horizontal" id="result-entry-form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="patient_test_entry_id" value="<?php echo (int) $entry->patient_test_entry_id; ?>">
            <input type="hidden" name="invoice_no" value="<?php echo html_escape($entry->invoice_no); ?>">
            <input type="hidden" name="test_group_id" value="<?php echo (int) $entry->test_group_id; ?>">
            <input type="hidden" name="manual_or_dynamic_report" value="Dynamic">
            <input type="hidden" name="test_result_no" value="<?php echo html_escape(isset($test_result_no) ? $test_result_no : ''); ?>">
            <input type="hidden" name="patient_test_entry_details_id" value="<?php echo html_escape($selected_detail_ids); ?>">
            <input type="hidden" name="test_result_id" id="test_result_id_field" value="<?php echo $test_result_id > 0 ? (int) $test_result_id : ''; ?>">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Invoice No</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><strong><?php echo html_escape($entry->invoice_no); ?></strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Test No</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?php echo html_escape(isset($test_result_no) ? $test_result_no : ''); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Patient Name</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?php echo html_escape($entry->patient_name); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Mobile</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?php echo html_escape($mobile); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Age / Gender</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <?php echo html_escape(isset($patient->age) ? $patient->age : ''); ?>
                                <?php if (!empty($patient->gender)) { ?>
                                    / <?php echo html_escape($patient->gender); ?>
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Test Group</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><strong><?php echo html_escape($group_label); ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Referring Doctor</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?php echo html_escape(isset($referring_doctor_label) ? $referring_doctor_label : ''); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Test Date</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <?php echo !empty($entry->test_date) ? date('d-m-Y', strtotime($entry->test_date)) : ''; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="result_date">Entry Date</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="result_date" name="date" value="<?php echo html_escape($now_date); ?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="time" value="<?php echo html_escape($now_time); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <?php foreach ($entries as $idx => $row) {
                $tid = (int) $row->test_id;
                $val = isset($existing_values[$tid]) ? $existing_values[$tid] : ($idx === 0 ? $existing_value : '');
                $unit_name = isset($row->unit_name) ? trim((string) $row->unit_name) : '';
            ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label col-sm-4"><?php echo html_escape($row->test_name); ?></label>
                        <div class="col-sm-8">
                            <input type="hidden" name="test_id[]" value="<?php echo $tid; ?>">
                            <input type="hidden" name="bold[]" value="No">
                            <div class="input-group">
                                <input type="text" name="test_configuration_value[]" class="form-control result-value-input"
                                    placeholder="Enter result value" maxlength="500"
                                    value="<?php echo html_escape($val); ?>"
                                    <?php echo ($idx === 0 && !$multi_mode) ? 'required autofocus' : ''; ?>>
                                <?php if ($unit_name !== '') { ?>
                                    <span class="input-group-addon"><?php echo html_escape($unit_name); ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if ($multi_mode) { ?>
                <p class="text-muted"><small>Enter at least one result value. All selected tests will print on one report.</small></p>
            <?php } ?>
            <div class="row">
                <div class="col-md-12 text-right">
                    <a href="<?php echo isset($back_url) ? html_escape($back_url) : site_url('add-test-result'); ?>" class="btn btn-default">Cancel</a>
                    <button type="submit" class="btn btn-success">
                        <i class="glyphicon glyphicon-floppy-disk"></i> <?php echo $edit_mode ? 'Update &amp; Print' : 'Save &amp; Print'; ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    (function($) {
        function showMessage(type, text) {
            var cls = type === 'success' ? 'alert-success' : 'alert-danger';
            $('#result-message').html(
                '<div class="alert ' + cls + ' alert-dismissible">' +
                '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                text + '</div>'
            );
        }

        $('#result-entry-form').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            var hasValue = false;
            $form.find('.result-value-input').each(function() {
                if ($.trim($(this).val()) !== '') {
                    hasValue = true;
                }
            });
            if (!hasValue) {
                showMessage('error', 'Please enter at least one result value.');
                return;
            }

            var formData = new FormData(this);
            var existingResultId = $('#test_result_id_field').val();
            if (existingResultId) {
                formData.set('test_result_id', existingResultId);
            }
            $form.find(':input').prop('disabled', true);

            $.ajax({
                url: "<?php echo site_url('TestResultController/add_test_result_data_save'); ?>",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function(res) {
                    if (res.success) {
                        if (res.print_url) {
                            window.location.href = res.print_url;
                        } else {
                            window.location.href = "<?php echo site_url('add-test-result'); ?>";
                        }
                    } else {
                        $form.find(':input').prop('disabled', false);
                        var extra = res.print_url ? ' <a href="' + res.print_url + '">View existing report</a>' : '';
                        showMessage('error', (res.message || 'Failed to save result.') + extra);
                    }
                },
                error: function(xhr) {
                    $form.find(':input').prop('disabled', false);
                    var msg = 'Failed to save result. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            var parsed = JSON.parse(xhr.responseText);
                            if (parsed.message) {
                                msg = parsed.message;
                            }
                        } catch (err) {
                            if (xhr.status) {
                                msg += ' (HTTP ' + xhr.status + ')';
                            }
                        }
                    }
                    showMessage('error', msg);
                }
            });
        });
    })(jQuery);
</script>
