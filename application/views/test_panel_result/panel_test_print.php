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

        /*
         * Bootstrap 3's print stylesheet weakens table borders to #ddd, which
         * many printers render as nearly invisible. Force solid black borders
         * for every table inside the printed report so the grid stays crisp.
         */
        #report table,
        #report .table {
            border-collapse: collapse !important;
            width: 100% !important;
        }

        #report table,
        #report .table,
        #report .table>thead>tr>th,
        #report .table>tbody>tr>th,
        #report .table>tfoot>tr>th,
        #report .table>thead>tr>td,
        #report .table>tbody>tr>td,
        #report .table>tfoot>tr>td,
        #report .table-bordered,
        #report .table-bordered>thead>tr>th,
        #report .table-bordered>tbody>tr>th,
        #report .table-bordered>tfoot>tr>th,
        #report .table-bordered>thead>tr>td,
        #report .table-bordered>tbody>tr>td,


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

    /* On-screen: keep the existing look but darken table borders so the
     * "Print preview" / browser view also has a clearer grid. */
    #report .table,
    #report .table-bordered,
    #report .table>thead>tr>th,
    #report .table>tbody>tr>th,
    #report .table>tfoot>tr>th,
    #report .table>thead>tr>td,
    #report .table>tbody>tr>td,
    #report .table>tfoot>tr>td {
        border-color: #555 !important;
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
                    <p><b>Patient Name:</b> <?php echo html_escape($report->patient_name); ?>, <b>Age:</b> <?php echo html_escape($age_display); ?>, <b>Sex:</b> <?php echo html_escape($report->sex); ?>, <b>Patient ID:</b> <?php echo html_escape($report->patient_id); ?>, <b>Report date:</b> <?php echo date('d-m-Y', strtotime($report->report_date)); ?></p>
                    <?php
                   
                   $group_name = $this->db->where('test_group_id', $report->test_group_id)->get('test_group')->row();
                   if ($group_name && isset($group_name->test_group_name) && $group_name->test_group_name) {
                   ?>
                       <p style="margin-bottom:0;text-align: center;"><strong></strong> <u><?php echo html_escape($group_name->test_group_name.' Report'); ?></u></p>

                   <?php } ?>
                    <p style="text-align: center;font-weight: bold;"><?php echo isset($report->panel_name) ? html_escape($report->panel_name) : '—'; ?></p>
                   
                </div>

                <?php if (!empty($section_blocks)) { ?>
                    <?php foreach ($section_blocks as $block) { ?>
                        <div class="report-section-block" style="margin-bottom:5px;">
                            <?php if (empty($hide_section_titles)) { ?>
                            <p style="border-bottom:1px solid black;padding-bottom:2px;color:black;">
                                <?php echo html_escape($block['section_name']); ?>
                            </p>
                            <?php } ?>
                            <table class="table  table-hover table-striped">
                                <thead>
                                    <tr style="margin-bottom:1px;">
                                        <th style="width:32%;text-align: left;">Parameter</th>
                                        <th style="width:18%;text-align: left;">Result</th>

                                        <th style="width:35%;text-align: left;">Normal Range</th>
                                    </tr>
                                </thead>
                                <?php foreach ($block['rows'] as $row) {
                                    $unit = isset($row->unit) && $row->unit !== '' ? ' ' . html_escape($row->unit) : '';
                                    $badge = lab_report_status_badge($row->status);
                                    $normal_range = $this->Report_model->format_normal_range($row);
                                ?>
                                    <tr>
                                        <td><strong><?php echo html_escape($row->parameter_name); ?></strong></td>
                                        <td><?php echo html_escape((string) $row->result_value); ?></td>
                                        <td><?php echo html_escape($normal_range); ?></td>
                                    </tr>
                                <?php } ?>
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