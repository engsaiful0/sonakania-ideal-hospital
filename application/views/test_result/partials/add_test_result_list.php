<?php
$permissions = $this->session->userdata('permissions');
$rows = isset($detailsList) && is_array($detailsList) ? $detailsList : array();
$sl = isset($sl_start) ? (int) $sl_start : 1;
?>
<div class="table-responsive">
    <table class="table table-bordered table-condensed table-striped table-hover">
        <thead>
            <tr>
                <th style="width:60px;">SL</th>
                <th>Invoice ID</th>
                <th>Patient Name</th>
                <th>Mobile Number</th>
                <th>Test Name</th>
                <th>Test Date</th>
                <th>Referring Doctor</th>
                <th style="width:180px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rows)) { ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">No test entries found.</td>
                </tr>
            <?php } ?>
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td><?php echo $sl++; ?></td>
                    <td><?php echo html_escape($row->invoice_no); ?></td>
                    <td><?php echo html_escape($row->patient_name); ?></td>
                    <td><?php echo html_escape($row->mobile_number); ?></td>
                    <td><?php echo html_escape($row->test_name); ?></td>
                    <td><?php echo !empty($row->test_date) ? date('d-m-Y', strtotime($row->test_date)) : ''; ?></td>
                    <td>
                        <?php
                        $ref_name = isset($row->referring_doctor_name) ? trim((string) $row->referring_doctor_name) : '';
                        $ref_degree = isset($row->referring_doctor_degree) ? trim((string) $row->referring_doctor_degree) : '';
                        if ($ref_name !== '' && $ref_degree !== '') {
                            echo html_escape($ref_name) . ', ' . html_escape($ref_degree);
                        } elseif ($ref_name !== '') {
                            echo html_escape($ref_name);
                        } else {
                            echo html_escape($ref_degree);
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        $is_panel = $this->TestResultModel->entry_is_panel_test($row);
                        $existing_single = isset($row->existing_test_result_id) ? (int) $row->existing_test_result_id : 0;
                        $existing_panel = isset($row->existing_lab_report_id) ? (int) $row->existing_lab_report_id : 0;
                        ?>
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
                                <a class="btn btn-xs btn-warning" href="<?php echo site_url('enter-test-result/' . (int) $row->patient_test_entry_details_id); ?>">
                                    <i class="glyphicon glyphicon-edit"></i> Edit
                                </a>
                            <?php } ?>
                        <?php } else { ?>
                            <a href="<?php echo site_url('enter-test-result/' . (int) $row->patient_test_entry_details_id); ?>" class="btn btn-xs btn-primary">
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
