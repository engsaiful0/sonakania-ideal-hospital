<?php
$entry = isset($entry) ? $entry : (object) array();
$patient = isset($patient) ? $patient : (object) array();
$panel = isset($panel) ? $panel : (object) array();
$panel_id = isset($panel_id) ? (int) $panel_id : 0;
$panel_test_group_id = isset($panel_test_group_id) ? (int) $panel_test_group_id : 0;
$require_lab_test_group = !empty($require_lab_test_group);
$panel_label = isset($panel->panel_name) ? (string) $panel->panel_name : (isset($entry->test_name) ? (string) $entry->test_name : '');
$invoice_val = isset($entry->invoice_no) ? (string) $entry->invoice_no : '';
$patient_name_val = isset($entry->patient_name) ? (string) $entry->patient_name : '';
$age_y = isset($patient->age_year) ? (string) $patient->age_year : '';
$age_m = isset($patient->age_month) ? (string) $patient->age_month : '';
$age_d = isset($patient->age_day) ? (string) $patient->age_day : '';
$sex_val = isset($patient->gender) ? (string) $patient->gender : '';
$mobile = '';
if (isset($patient->mobile_number) && trim((string) $patient->mobile_number) !== '') {
    $mobile = $patient->mobile_number;
} elseif (isset($patient->mobile)) {
    $mobile = $patient->mobile;
}
$invoice_date = !empty($entry->test_date) ? date('d-m-Y', strtotime($entry->test_date)) : '';
$invoice_time = isset($entry->test_time) ? (string) $entry->test_time : '';
?>
<div class="panel panel-primary">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left" style="margin-top:6px;">Enter Panel Test Result</h3>
        <a href="<?php echo isset($back_url) ? html_escape($back_url) : site_url('add-test-result'); ?>" class="btn btn-default btn-sm pull-right">
            <i class="glyphicon glyphicon-arrow-left"></i> Back to list
        </a>
    </div>
    <div class="panel-body">
        <div id="result-message"></div>
        <form class="form-horizontal" id="panel-result-entry-form" method="post">
            <input type="hidden" name="panel_test_id" id="panel_test_id" value="<?php echo (int) $panel_id; ?>">
            <?php if ($require_lab_test_group) { ?>
                <input type="hidden" name="test_group_id" id="test_group_id" value="<?php echo (int) $panel_test_group_id; ?>">
            <?php } ?>
            <input type="hidden" name="invoice_no" value="<?php echo html_escape($invoice_val); ?>">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Invoice No</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><strong><?php echo html_escape($invoice_val); ?></strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Panel Test</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><strong><?php echo html_escape($panel_label); ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Patient Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="patient_name" name="patient_name" value="<?php echo html_escape($patient_name_val); ?>" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Gender</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="gender" name="gender" value="<?php echo html_escape($sex_val); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Age</label>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" placeholder="Year" id="age_year" name="age_year" value="<?php echo html_escape($age_y); ?>">
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" placeholder="Month" id="age_month" name="age_month" value="<?php echo html_escape($age_m); ?>">
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" placeholder="Day" id="age_day" name="age_day" value="<?php echo html_escape($age_d); ?>">
                                </div>
                            </div>
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
                        <label class="control-label col-sm-4">Invoice Date / Time</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <?php echo html_escape($invoice_date); ?>
                                <?php if ($invoice_time !== '') { ?>
                                    <?php echo ' ' . html_escape($invoice_time); ?>
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
            <hr>
            <div id="test_configuration" class="form-horizontal"></div>
            <div id="img" style="display:none;text-align:center;margin:12px 0;">
                <img src="<?php echo base_url('assets/img/ajax-loader.gif'); ?>" alt="Loading">
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    <a href="<?php echo isset($back_url) ? html_escape($back_url) : site_url('add-test-result'); ?>" class="btn btn-default">Cancel</a>
                    <button type="submit" class="btn btn-success" id="submit_button">
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
                dataType: 'html',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).done(function(html) {
                $cfg.html(html);
            }).fail(function() {
                $cfg.html('<p class="text-danger">Could not load panel test parameters.</p>');
            }).always(function() {
                $('#img').hide();
            });
        }

        $(document).ready(function() {
            panel_test_load($('#panel_test_id').val());
        });

        $('#panel-result-entry-form').on('submit', function(e) {
            e.preventDefault();
            if (window.__panelResultEntrySubmitting) {
                return false;
            }

            var panel_test_id = $('#panel_test_id').val();
            if (!panel_test_id) {
                showMessage('error', 'Panel test is not configured.');
                return false;
            }

            var patient_name = $.trim($('#patient_name').val());
            if (patient_name === '') {
                showMessage('error', 'Patient name is required.');
                return false;
            }

            var hasValue = false;
            $('#test_configuration').find('.panel-param').each(function() {
                if ($.trim($(this).val()) !== '') {
                    hasValue = true;
                    return false;
                }
            });
            if (!hasValue) {
                showMessage('error', 'Enter at least one panel result value.');
                return false;
            }

            window.__panelResultEntrySubmitting = true;
            var formData = new FormData(this);
            var $form = $(this);
            $form.find(':input').prop('disabled', true);

            $.ajax({
                url: "<?php echo site_url('TestPanelResultController/save_panel_test'); ?>",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).done(function(res) {
                if (res && res.success && res.print_url) {
                    window.location.href = res.print_url;
                } else if (res && res.success) {
                    window.location.href = "<?php echo site_url('add-test-result'); ?>";
                } else {
                    window.__panelResultEntrySubmitting = false;
                    $form.find(':input').prop('disabled', false);
                    showMessage('error', (res && res.message) ? res.message : 'Failed to save panel result.');
                }
            }).fail(function() {
                window.__panelResultEntrySubmitting = false;
                $form.find(':input').prop('disabled', false);
                showMessage('error', 'Failed to save panel result. Please try again.');
            });

            return false;
        });
    })(jQuery);
</script>
