<?php
$entry = isset($entry) ? $entry : (object) array();
$patient = isset($patient) ? $patient : (object) array();
$entry_id = isset($entry->patient_test_entry_details_id) ? (int) $entry->patient_test_entry_details_id : 0;
$now_date = !empty($entry->test_date) ? date('d-m-y', strtotime($entry->test_date)) : date('d-m-y');
$now_time = !empty($entry->test_time) ? $entry->test_time : date('H:i:s');
$mobile = '';
if (isset($patient->mobile_number) && trim((string) $patient->mobile_number) !== '') {
    $mobile = $patient->mobile_number;
} elseif (isset($patient->mobile)) {
    $mobile = $patient->mobile;
}
?>
<div class="panel panel-primary">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left" style="margin-top:6px;">Enter Test Result</h3>
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
            <input type="hidden" name="manual_or_dynamic_report" value="dynamic_report">
            <input type="hidden" name="test_result_no" value="<?php echo html_escape(isset($test_result_no) ? $test_result_no : ''); ?>">

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
                        <label class="control-label col-sm-4">Referring Doctor</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?php echo html_escape(isset($referring_doctor_label) ? $referring_doctor_label : ''); ?></p>
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
            <hr>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label col-sm-4"><?php echo html_escape($entry->test_name); ?></label>
                        <div class="col-sm-8">
                            <input type="hidden" name="test_id[]" value="<?php echo (int) $entry->test_id; ?>">
                            <input type="hidden" name="bold[]" value="No">
                            <input type="text" name="test_configuration_value[]" class="form-control" placeholder="Enter result value" required maxlength="500" autofocus>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    <a href="<?php echo isset($back_url) ? html_escape($back_url) : site_url('add-test-result'); ?>" class="btn btn-default">Cancel</a>
                    <button type="submit" class="btn btn-success">
                        <i class="glyphicon glyphicon-floppy-disk"></i> Save &amp; Print
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
            var formData = new FormData(this);
            $form.find(':input').prop('disabled', true);

            $.ajax({
                url: "<?php echo site_url('TestResultController/add_test_result_data_save'); ?>",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
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
                error: function() {
                    $form.find(':input').prop('disabled', false);
                    showMessage('error', 'Failed to save result. Please try again.');
                }
            });
        });
    })(jQuery);
</script>
