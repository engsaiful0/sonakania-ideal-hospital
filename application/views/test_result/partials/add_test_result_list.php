<?php
$permissions = $this->session->userdata('permissions');
$rows = isset($detailsList) && is_array($detailsList) ? $detailsList : array();
$sl = isset($sl_start) ? (int) $sl_start : 1;
?>
<div id="bulk-action-bar" class="well well-sm" style="display:none;margin-bottom:10px;">
    <span id="selected-count" class="text-muted">0 selected</span>
    <button type="button" class="btn btn-primary btn-sm" id="btn-bulk-result-entry" style="margin-left:10px;">
        <i class="glyphicon glyphicon-edit"></i> Result Entry (Selected)
    </button>
    <span class="text-muted" style="margin-left:8px;">Same invoice and test group only</span>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-condensed table-striped table-hover" id="add-test-result-table">
        <thead>
            <tr>
                <th style="width:30px;"><input type="checkbox" id="select-all-rows" title="Select all on this page"></th>
                <th>SL</th>
                <th>Invoice ID</th>
                <th>Patient Name<br>Mobile Number</th>
                <th>Test Group</th>
                <th>Test Name</th>
                <th>Test Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rows)) { ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">No test entries found.</td>
                </tr>
            <?php } ?>
            <?php foreach ($rows as $row) { ?>
                <?php
                $is_panel = $this->TestResultModel->entry_is_panel_test($row);
                $existing_single = isset($row->existing_test_result_id) ? (int) $row->existing_test_result_id : 0;
                $existing_panel = isset($row->existing_lab_report_id) ? (int) $row->existing_lab_report_id : 0;
                $detail_id = (int) $row->patient_test_entry_details_id;
                $group_id = isset($row->test_group_id) ? (int) $row->test_group_id : 0;
                $group_name = isset($row->test_group_name) ? (string) $row->test_group_name : '';
                $can_bulk = !$is_panel && $group_id > 0;
                ?>
                <tr data-detail-id="<?php echo $detail_id; ?>"
                    data-invoice="<?php echo html_escape($row->invoice_no); ?>"
                    data-group-id="<?php echo $group_id; ?>"
                    data-group-name="<?php echo html_escape($group_name); ?>"
                    data-is-panel="<?php echo $is_panel ? '1' : '0'; ?>">
                    <td>
                        <?php if ($can_bulk) { ?>
                            <input type="checkbox" class="row-select-cb" value="<?php echo $detail_id; ?>">
                        <?php } ?>
                    </td>
                    <td><?php echo $sl++; ?></td>
                    <td><?php echo html_escape($row->invoice_no); ?></td>
                    <td><?php echo html_escape($row->patient_name); ?><br><?php echo html_escape($row->mobile_number); ?></td>
                    <td><?php echo html_escape($group_name); ?></td>
                    <td><?php echo html_escape($row->test_name); ?></td>
                    <td><?php echo !empty($row->test_date) ? date('d-m-Y', strtotime($row->test_date)) : ''; ?></td>
                    <td>
                        <?php if ($is_panel && $existing_panel > 0) { ?>
                            <?php if (in_array('print_test_result', $permissions)) { ?>
                                <a class="btn btn-xs btn-info" target="_blank" href="<?php echo site_url('print-panel-test-with-id/' . $existing_panel); ?>?print=1">
                                    <i class="glyphicon glyphicon-print"></i> Print
                                </a>
                            <?php } ?>
                            <?php if (in_array('edit_test_result', $permissions)) { ?>
                                <a class="btn btn-xs btn-warning" href="<?php echo site_url('panel-test-edit/' . $existing_panel); ?>">
                                    <i class="glyphicon glyphicon-edit"></i> Edit
                                </a>
                            <?php } ?>
                        <?php } elseif (!$is_panel && $existing_single > 0) { ?>
                            <?php if (in_array('print_test_result', $permissions)) { ?>
                                <a class="btn btn-xs btn-info" target="_blank" href="<?php echo site_url('TestResultController/test_result_report_print_again/' . $existing_single); ?>">
                                    <i class="glyphicon glyphicon-print"></i> Print
                                </a>
                            <?php } ?>
                            <?php if (in_array('edit_test_result', $permissions)) { ?>
                                <a class="btn btn-xs btn-warning" href="<?php echo site_url('enter-test-result/' . $detail_id); ?>">
                                    <i class="glyphicon glyphicon-edit"></i> Edit
                                </a>
                            <?php } ?>
                            <?php if (in_array('delete_test_result', $permissions)) { ?>
                                <button type="button" class="btn btn-xs btn-danger btn-delete-result"
                                    data-test-result-id="<?php echo $existing_single; ?>"
                                    data-test-id="<?php echo (int) $row->test_id; ?>"
                                    data-detail-id="<?php echo $detail_id; ?>">
                                    <i class="glyphicon glyphicon-trash"></i> Delete
                                </button>
                            <?php } ?>
                        <?php } else { ?>
                            <a href="<?php echo site_url('enter-test-result/' . $detail_id); ?>" class="btn btn-xs btn-primary">
                                <i class="glyphicon glyphicon-plus"></i> Result Entry
                            </a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div class="text-center">
    <?php echo isset($pagination) ? $pagination : ''; ?>
</div>
