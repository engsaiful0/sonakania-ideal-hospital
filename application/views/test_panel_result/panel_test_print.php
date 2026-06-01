<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #report,
        #report * {
            visibility: visible;
            overflow: visible;
        }

        #report {
            position: absolute;
            left: 0;
            top: 0;
            margin-top: 150px !important;
        }

        /* Make sure background fills (e.g. status badges) and panel borders
         * are actually printed by Chrome/Edge. */
        #report,
        #report * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        .p1 {
            line-height: 80% !important;
        }

        #entry_by {
            margin-top: 5px !important;
        }
    }

    #report .print-result-table {
        width: 100%;
        border-collapse: collapse;
        border: none !important;
        margin-bottom: 8px;
    }

    #report .print-result-table thead th,
    #report .print-result-table tbody td {
        border: none !important;
        padding: 6px 8px;
        vertical-align: top;
        background: transparent !important;
    }

    #report .print-result-table.table-striped > tbody > tr:nth-of-type(odd) {
        background-color: transparent !important;
    }

    #report .print-result-table thead th {
        border-bottom: 1px solid #333 !important;
    }

  #report .print-result-table tbody tr.print-result-data-row td {
        border-bottom: none !important;
        padding-bottom: 4px;
    }

    #report .print-result-dot-sep td {
        border: none !important;
        padding: 0 8px 5px !important;
        line-height: 0;
        background: transparent !important;
    }

    #report .print-row-dots {
        display: block;
        width: 100%;
        height: 3px;
        border: 0;
        background: transparent url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='2'%3E%3Ccircle cx='1' cy='1' r='0.55' fill='%23555'/%3E%3C/svg%3E") repeat-x left center;
        background-size: 14px 2px;
    }

    @media print {
        #report .print-row-dots {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='2'%3E%3Ccircle cx='1' cy='1' r='0.55' fill='%23000'/%3E%3C/svg%3E");
        }
    }

    .p1 {
        line-height: 80% !important;
    }
</style>
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
$compnay = $this->db->where('company_id', '1')->get('company')->row();
?>
<div class="row">
    <div class="col-md-12">
        <button onclick="window.print()" id="sumbit_button" class="btn btn-primary">Print</button>
    </div>

</div>
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:50px;">

    <div class="panel panel-primary" id="lab-report-view">

        <div class="panel-body">
            <?php

            if (!empty($report)) {
                $referring_doctor_label = $this->Report_model->format_referring_doctor_label(
                    $this->Report_model->get_referring_doctor_for_report($report)
                );
                $age_parts = array();

                if ($report->age_year > 0) {
                    $age_parts[] = $report->age_year . ' ' . ($report->age_year == 1 ? 'Year' : 'Years');
                }

                if ($report->age_month > 0) {
                    $age_parts[] = $report->age_month . ' ' . ($report->age_month == 1 ? 'Month' : 'Months');
                }

                if ($report->age_day > 0) {
                    $age_parts[] = $report->age_day . ' ' . ($report->age_day == 1 ? 'Day' : 'Days');
                }

                $age_display = !empty($age_parts) ? implode(' ', $age_parts) : '—';
                $section_block_count = (!empty($section_blocks) && is_array($section_blocks)) ? count($section_blocks) : 0;
                // Single section: panel name in header is enough — hide the section title row.
                $hide_section_titles = ($section_block_count === 1);
            ?>
                <div class="well well-sm" style="background:#f9f9f9;margin-bottom:10px;">
                    <p><b>Patient Name:</b> <?php echo html_escape($report->patient_name); ?>, <b>Age:</b> <?php echo html_escape($age_display); ?>, <b>Sex:</b> <?php echo html_escape($report->sex); ?>, <b>Patient ID:</b> <?php echo html_escape($report->patient_id); ?>, <b>Report date:</b> <?php echo date('d-m-Y', strtotime($report->report_date)); ?>
                        <br>
                        Referring Doctor: <b><?php echo html_escape($referring_doctor_label); ?></b>
                    </p>
                    <?php

                    $group_name = $this->db->where('test_group_id', $report->test_group_id)->get('test_group')->row();
                    if ($group_name && isset($group_name->test_group_name) && $group_name->test_group_name) {
                    ?>
                        <p style="margin-bottom:0;text-align: center;"><strong></strong> <u><?php echo strtoupper(html_escape($group_name->test_group_name . ' Report')); ?></u></p>

                    <?php } ?>
                    <p style="text-align: center;font-weight: bold;"><?php echo isset($group_name->machine_name) ? strtoupper(html_escape($group_name->machine_name)) : ''; ?></p>
                    <p style="text-align: center;font-weight: bold;"><?php echo isset($report->panel_name) ? strtoupper(html_escape($report->panel_name)) : ''; ?></p>

                </div>
                <?php if (!empty($section_blocks)) { ?>
                    <?php foreach ($section_blocks as $block) { ?>
                        <div class="report-section-block" style="margin-bottom:5px;">
                            <table class="print-result-table">
                                <thead>
                                    <tr>
                                        <th style="width:32%;text-align: left;">Parameter</th>
                                        <th style="width:18%;text-align: left;">Result</th>
                                        <th style="width:35%;text-align: left;">Normal Range</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($block['rows'] as $row) {
                                    $unit = isset($row->unit) && $row->unit !== '' ? ' ' . html_escape($row->unit) : '';
                                    $badge = lab_report_status_badge($row->status);
                                    $normal_range = $this->Report_model->format_normal_range($row);
                                ?>
                                    <tr class="print-result-data-row">
                                        <td><strong><?php echo html_escape($row->parameter_name); ?></strong></td>
                                        <td><?php echo html_escape((string) $row->result_value); ?></td>
                                        <td><?php echo html_escape($normal_range); ?></td>
                                    </tr>
                                    <tr class="print-result-dot-sep">
                                        <td colspan="3"><div class="print-row-dots"></div></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-muted">No result rows stored for this report.</p>
                <?php } ?>


            <?php } else { ?>
                <p class="text-danger">Report not found.</p>
            <?php } ?>
        </div>
    </div>
</div>

<?php if (!empty($auto_print)) { ?>
    <script>
        jQuery(function($) {
            setTimeout(function() {
                window.print();
            }, 400);
        });
    </script>
<?php } ?>