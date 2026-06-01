<?php
$entry = isset($entry) ? $entry : (object) array();
$entry_id = isset($entry->patient_test_entry_details_id) ? (int) $entry->patient_test_entry_details_id : 0;
$now_date = !empty($entry->test_date) ? date('Y-m-d', strtotime($entry->test_date)) : date('Y-m-d');
$now_time = !empty($entry->test_time) ? $entry->test_time : date('H:i:s');
$test_result_no = 'TRN' . date('ymdHis') . $entry_id;
?>
<form id="result-entry-form" enctype="multipart/form-data">
    <input type="hidden" name="patient_test_entry_id" value="<?php echo (int) $entry->patient_test_entry_id; ?>">
    <input type="hidden" name="invoice_no" value="<?php echo html_escape($entry->invoice_no); ?>">
    <input type="hidden" name="test_group_id" value="<?php echo (int) $entry->test_group_id; ?>">
    <input type="hidden" name="date" value="<?php echo $now_date; ?>">
    <input type="hidden" name="time" value="<?php echo html_escape($now_time); ?>">
    <input type="hidden" name="manual_or_dynamic_report" value="dynamic_report">
    <input type="hidden" name="test_result_no" value="<?php echo html_escape($test_result_no); ?>">

    <div class="row">
        <div class="col-md-6">
            <p><strong>Invoice:</strong> <?php echo html_escape($entry->invoice_no); ?></p>
            <p><strong>Patient:</strong> <?php echo html_escape($entry->patient_name); ?></p>
        </div>
        <div class="col-md-6">
            <p><strong>Mobile:</strong> <?php echo html_escape($entry->mobile_number); ?></p>
            <p><strong>Doctor:</strong> <?php echo html_escape($entry->referring_doctor); ?></p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label><?php echo html_escape($entry->test_name); ?></label>
                <input type="hidden" name="test_id[]" value="<?php echo (int) $entry->test_id; ?>">
                <input type="hidden" name="bold[]" value="No">
                <input type="text" name="test_configuration_value[]" class="form-control" placeholder="Enter result value" required maxlength="500">
            </div>
        </div>
    </div>
    <div class="text-right">
        <button type="submit" class="btn btn-success">
            <i class="glyphicon glyphicon-floppy-disk"></i> Save & Print
        </button>
    </div>
</form>
