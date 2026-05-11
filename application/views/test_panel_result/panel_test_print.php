<?php
/**
 * Maps stored status to Bootstrap 3 labels (color badges).
 */
if (!function_exists('lab_report_status_badge')) {
    function lab_report_status_badge($status)
    {
        $s = strtolower(trim((string) $status));

        if ($s === '' || $s === 'null') {
            return '<span class="label label-default">—</span>';
        }
        if ($s === 'normal' || $s === 'negative') {
            return '<span class="label label-success">Normal</span>';
        }
        if ($s === 'low') {
            return '<span class="label label-warning">Low</span>';
        }
        if ($s === 'high' || $s === 'positive') {
            return '<span class="label label-danger">' . html_escape(ucfirst($status)) . '</span>';
        }
        return '<span class="label label-default">' . html_escape((string) $status) . '</span>';
    }
}
?>

<div class="panel panel-primary" id="lab-report-view">
    <div class="panel-heading clearfix">
        <h3 class="text-center" style="margin:0;">Lab report</h3>
    </div>
    <div class="panel-body">
        <?php if (!empty($report)) { ?>
            <div class="well well-sm" style="background:#f9f9f9;">
                <h4 style="margin-top:0;">Patient information</h4>
                <table class="table table-condensed" style="margin-bottom:0;">
                    <tr>
                        <td style="width:140px;"><strong>Name</strong></td>
                        <td><?php echo html_escape($report->patient_name); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Age</strong></td>
                        <td><?php echo isset($report->age) ? html_escape((string) $report->age) : '—'; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Sex</strong></td>
                        <td><?php echo isset($report->sex) ? html_escape((string) $report->sex) : '—'; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Patient ID</strong></td>
                        <td><?php echo isset($report->patient_id) && $report->patient_id !== '' ? html_escape((string) $report->patient_id) : '—'; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Panel</strong></td>
                        <td><?php echo isset($report->panel_name) ? html_escape($report->panel_name) : '—'; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Report date</strong></td>
                        <td><?php echo isset($report->report_date) ? html_escape($report->report_date) : '—'; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Report #</strong></td>
                        <td><?php echo (int) $report->id; ?></td>
                    </tr>
                </table>
            </div>

            <hr>

            <?php if (!empty($section_blocks)) { ?>
                <?php foreach ($section_blocks as $block) { ?>
                    <div class="report-section-block" style="margin-bottom:24px;">
                        <h4 style="border-bottom:2px solid #337ab7;padding-bottom:6px;color:#337ab7;">
                            <?php echo html_escape($block['section_name']); ?>
                        </h4>
                        <table class="table table-bordered table-hover table-condensed">
                            <?php foreach ($block['rows'] as $row) {
                                $unit = isset($row->unit) && $row->unit !== '' ? ' ' . html_escape($row->unit) : '';
                                $badge = lab_report_status_badge($row->status);
                            ?>
                                <tr>
                                    <td style="width:35%;"><strong><?php echo html_escape($row->parameter_name); ?></strong><?php echo $unit; ?></td>
                                    <td style="width:35%;"><?php echo html_escape((string) $row->result_value); ?></td>
                                    <td style="width:30%;"><?php echo $badge; ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p class="text-muted">No result rows stored for this report.</p>
            <?php } ?>

            <p style="margin-top:16px;" class="hidden-print">
                <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
                <a href="<?php echo site_url('report/create_report'); ?>" class="btn btn-default">New report</a>
            </p>
        <?php } else { ?>
            <p class="text-danger">Report not found.</p>
        <?php } ?>
    </div>
</div>

<?php if (!empty($auto_print)) { ?>
    <script>
        jQuery(function($) {
            setTimeout(function() { window.print(); }, 400);
        });
    </script>
<?php } ?>
