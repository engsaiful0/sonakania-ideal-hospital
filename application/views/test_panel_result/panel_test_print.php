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

        #report thead {
            display: table-header-group !important;
        }

        #report #result-header th {
            visibility: visible !important;
            color: #000 !important;
            border: 1px solid #000 !important;
            background: #fff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

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
        margin-bottom: 12px;
    }

    #report #result-header th {
        border: 1px solid #333 !important;
        padding: 6px 8px;
        vertical-align: middle;
        text-align: left;
        font-weight: bold;
        color: #000 !important;
        background: #fff !important;
    }

    #report .print-result-table tbody td {
        border: none !important;
        padding: 5px 8px;
        vertical-align: top;
        background: transparent !important;
        color: #000 !important;
    }

    #report .print-result-table.table-striped > tbody > tr:nth-of-type(odd) {
        background-color: transparent !important;
    }

    #report .print-section-heading-row td {
        border: none !important;
        padding: 10px 8px 4px !important;
        font-weight: bold;
        color: #000 !important;
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
            ?>
                <div class="well well-sm" style="background:#f9f9f9;margin-bottom:10px;">
                <table border="0" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                        <tr>
                        <td>Patient Name:<b> <?php echo html_escape($report->patient_name); ?></b>, Age:<b> <?php echo html_escape($age_display); ?></b>, Sex:<b> <?php echo html_escape($report->sex); ?></b>, Patient ID:<b> <?php echo html_escape($report->patient_id); ?></b>, Report date:<b> <?php echo date('d-m-Y', strtotime($report->report_date)); ?></b></td>
                        </tr>
                        <tr>
                            <td>Referring Doctor: <b><?php echo html_escape($referring_doctor_label); ?></b></td>
                        </tr>
                        <tr>
                            <td>Test Name: <b><?php echo isset($report->panel_name) ? html_escape($report->panel_name) : ''; ?></b></td>
                        </tr>
                    </table>
                    <?php

                    $group_name = $this->db->where('test_group_id', $report->test_group_id)->get('test_group')->row();
                    if ($group_name && isset($group_name->test_group_name) && $group_name->test_group_name) {
                    ?>
                        <p style="margin-bottom:0;text-align: center;font-weight: bold;"><?php echo strtoupper(html_escape($group_name->test_group_name . ' Report')); ?></p>

                    <?php } ?>
                    <p style="text-align: center;font-weight: bold;"><?php echo isset($group_name->machine_name) ? strtoupper(html_escape($group_name->machine_name)) : ''; ?></p>

                </div>
                <?php if (!empty($section_blocks)) { ?>
                    <?php foreach ($section_blocks as $block) {
                        $section_heading = isset($block['section_heading']) ? trim((string) $block['section_heading']) : '';
                        if ($section_heading === '' && isset($block['section_name'])) {
                            $section_heading = trim((string) $block['section_name']);
                        }
                    ?>
                        <div class="report-section-block" style="margin-bottom:5px;">
                            <table class="print-result-table table-bordered">
                                <thead>
                                    <tr id="result-header">
                                        <th style="width:40%;">Investigation</th>
                                        <th style="width:25%;">Value</th>
                                        <th style="width:35%;">Normal Range</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if ($section_heading !== '') { ?>
                                    <tr class="print-section-heading-row">
                                        <td><?php echo html_escape($section_heading); ?></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                <?php } ?>
                                <?php foreach ($block['rows'] as $row) {
                                    $unit = isset($row->unit) && $row->unit !== '' ? ' ' . html_escape($row->unit) : '';
                                    $normal_range = $this->Report_model->format_normal_range($row);
                                ?>
                                    <tr class="print-result-data-row">
                                        <td><?php echo html_escape($row->parameter_name); ?><?php echo $unit; ?></td>
                                        <td><?php echo html_escape((string) $row->result_value); ?></td>
                                        <td><?php echo html_escape($normal_range); ?></td>
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
