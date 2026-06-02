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
            margin-top: 135px !important;
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
        margin-bottom: 6px;
    }

    #report .print-result-table tbody td {
        border: none !important;
        padding: 4px 6px;
        vertical-align: top;
        background: transparent !important;
    }

    #report .print-result-table tbody tr.print-result-data-row td {
        border-bottom: none !important;
        padding-bottom: 3px;
    }

    #report .print-result-dot-sep td {
        border: none !important;
        padding: 0 4px 4px !important;
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

    /*
     * Two-column layout: use a table shell (not Bootstrap grid). Bootstrap’s
     * print CSS forces .col-* to width:100%, which stacks columns when printing.
     */
    #report .urine-split-shell {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        margin-bottom: 0;
    }

    #report .urine-split-shell>tbody>tr>td {
        width: 50%;
        vertical-align: top;
        border: none !important;
        padding: 0 6px 0 0;
    }

    #report .urine-split-shell>tbody>tr>td+td {
        padding: 0 0 0 6px;
    }

    #report .urine-two-col-section .urine-col-table {
        margin-bottom: 0;
        font-size: 11px;
    }

    @media print {
        #report .urine-split-shell {
            display: table !important;
            width: 100% !important;
            table-layout: fixed !important;
            page-break-inside: avoid;
        }

        #report .urine-split-shell>tbody>tr {
            display: table-row !important;
        }

        #report .urine-split-shell>tbody>tr>td {
            display: table-cell !important;
            width: 50% !important;
            max-width: 50% !important;
            float: none !important;
        }
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
            <?php if (!empty($report)) {
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
                <div class="well well-sm" style="background:#f9f9f9;">
                    <table border="0" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                        <tr>
                        <td>Patient Name:<b> <?php echo html_escape($report->patient_name); ?></b>, Age:<b> <?php echo html_escape($age_display); ?></b>, Sex:<b> <?php echo html_escape($report->sex); ?></b>, Patient ID:<b> <?php echo html_escape($report->patient_id); ?></b>, Report date:<b> <?php echo date('d-m-Y', strtotime($report->report_date)); ?></b></td>
                        </tr>
                        <tr>
                            <td>Referring Doctor: <b><?php echo html_escape($referring_doctor_label); ?></b></td>
                        </tr>
                    </table>
                </div>
                <?php
                   
                   $group_name = $this->db->where('test_group_id', $report->test_group_id)->get('test_group')->row();
                   if ($group_name->test_group_name) {
                   ?>
                       <p style="margin-bottom:0;text-align: center;"><strong> <u><?php echo strtoupper(html_escape($group_name->test_group_name.' Report')); ?></u></strong></p>

                   <?php } ?>

                <p style="text-align: center;font-weight: bold;"><?php echo isset($group_name->machine_name) ? strtoupper(html_escape($group_name->machine_name)) : ''; ?></p>
                <p style="text-align: center;font-weight: bold;"><?php echo isset($report->panel_name) ? strtoupper(html_escape($report->panel_name)) : ''; ?></p>
               
                <?php if (!empty($section_blocks)) { ?>
                    <?php foreach ($section_blocks as $block) {
                        $block_rows = isset($block['rows']) && is_array($block['rows']) ? $block['rows'] : array();
                        $n = count($block_rows);
                        $use_two_cols = $n > 1;
                        if ($use_two_cols) {
                            $mid = (int) ceil($n / 2);
                            $left_rows = array_slice($block_rows, 0, $mid);
                            $right_rows = array_slice($block_rows, $mid);
                        } else {
                            $left_rows = $block_rows;
                            $right_rows = array();
                        }
                    ?>
                        <div class="report-section-block urine-two-col-section" >
                            <p style="margin-bottom:1px;">
                                <?php echo html_escape($block['section_name']); ?>
                    </p>
                            <?php if ($use_two_cols) { ?>
                                <table class="urine-split-shell" role="presentation">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table class="print-result-table urine-col-table">
                                                    <!-- <thead>
                                                        <tr>
                                                            <th style="width:46%;text-align:left;">Parameter</th>
                                                            <th style="width:54%;text-align:left;">Result</th>
                                                        </tr>
                                                    </thead> -->
                                                    <tbody>
                                                        <?php foreach ($left_rows as $row) {
                                                            $unit = isset($row->unit) && $row->unit !== '' ? ' ' . html_escape($row->unit) : '';
                                                        ?>
                                                            <tr class="print-result-data-row">
                                                                <td style="width: 80%;"><strong><?php echo html_escape($row->parameter_name); ?></strong></td>
                                                                <td style="width: 20%;"><?php echo html_escape((string) $row->result_value); ?></td>
                                                            </tr>
                                                            <tr class="print-result-dot-sep">
                                                                <td colspan="2"><div class="print-row-dots"></div></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td>
                                                <table class="print-result-table urine-col-table">
                                                    <!-- <thead>
                                                        <tr>
                                                            <th style="width:46%;text-align:left;">Parameter</th>
                                                            <th style="width:54%;text-align:left;">Result</th>
                                                        </tr>
                                                    </thead> -->
                                                    <tbody>
                                                        <?php foreach ($right_rows as $row) {
                                                            $unit = isset($row->unit) && $row->unit !== '' ? ' ' . html_escape($row->unit) : '';
                                                        ?>
                                                            <tr class="print-result-data-row">
                                                                <td style="width: 80%;"><strong><?php echo html_escape($row->parameter_name); ?></strong></td>
                                                                <td style="width: 20%;"><?php echo html_escape((string) $row->result_value); ?></td>
                                                            </tr>
                                                            <tr class="print-result-dot-sep">
                                                                <td colspan="2"><div class="print-row-dots"></div></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php } else { ?>
                                <table class="print-result-table urine-col-table">
                                    <!-- <thead>
                                        <tr>
                                            <th style="width:32%;text-align:left;">Parameter</th>
                                            <th style="width:68%;text-align:left;">Result</th>
                                        </tr>
                                    </thead> -->
                                    <tbody>
                                        <?php foreach ($left_rows as $row) {
                                            $unit = isset($row->unit) && $row->unit !== '' ? ' ' . html_escape($row->unit) : '';
                                        ?>
                                            <tr class="print-result-data-row">
                                                <td style="width: 80%;"><strong><?php echo html_escape($row->parameter_name); ?></strong></td>
                                                <td style="width: 20%;"><?php echo html_escape((string) $row->result_value); ?></td>
                                            </tr>
                                            <tr class="print-result-dot-sep">
                                                <td colspan="2"><div class="print-row-dots"></div></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php } ?>
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