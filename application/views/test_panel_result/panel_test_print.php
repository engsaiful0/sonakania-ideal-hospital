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
        }



        .p1 {
            line-height: 80% !important;
        }

        #entry_by {
            margin-top: 5px !important;
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
<div class="" style="width: 100%;margin-bottom: 10px;">
            <div style="width: 15%;float: left;margin-top:5px">
                <img style="width:70%;padding-left: 30px;height: 100px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
            </div>
            <div style="width: 70%;float: left;text-align: center">
                <p style="text-align: center"><span style="text-align: center;font-size: 20px;text-align: center "> <?php echo $compnay->company_name ?><br><?php echo $compnay->address ?></span><br>
                    <span style="text-align: center">
                        Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                    </span>
                </p>
            </div>
          
            <div style="clear: left;">
                <p style="text-align: left;font-weight:bold">
                    <?php if (!empty($duplicate_or_main)) {
                        echo $duplicate_or_main;
                    } ?></p>
            </div>

        </div>
    <div class="panel-body">
        <?php if (!empty($report)) { ?>
            <div class="well well-sm" style="background:#f9f9f9;">
                <h4 style="margin-top:0;">Patient information</h4>
                <table class="table table-condensed" style="margin-bottom:0;">
                    <tr>
                        <td style="width:140px;"><strong>Name</strong></td>
                        <td><?php echo html_escape($report->patient_name); ?></td>
                   
                        <td><strong>Age</strong></td>
                        <td>
                        <b>
                            <?php
                            $age_parts = [];

                            if ($report->age_year > 0) {
                                $age_parts[] = $report->age_year . ' ' . ($report->age_year == 1 ? 'Year' : 'Years');
                            }

                            if ($report->age_month > 0) {
                                $age_parts[] = $report->age_month . ' ' . ($report->age_month == 1 ? 'Month' : 'Months');
                            }

                            if ($report->age_day > 0) {
                                $age_parts[] = $report->age_day . ' ' . ($report->age_day == 1 ? 'Day' : 'Days');
                            }

                            echo implode(' ', $age_parts);
                            ?>
                        </b>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Sex</strong></td>
                        <td><?php echo isset($report->sex) ? html_escape((string) $report->sex) : '—'; ?></td>
                  
                        <td><strong>Patient ID</strong></td>
                        <td><?php echo isset($report->patient_id) && $report->patient_id !== '' ? html_escape((string) $report->patient_id) : '—'; ?></td>
                    </tr>
                
                    <tr>
                        <td><strong>Report date</strong></td>
                        <td><?php echo date('d-m-Y', strtotime($report->report_date)); ?></td>
                    
                        <td><strong>Report #</strong></td>
                        <td><?php echo (int) $report->id; ?></td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <div class="col-md-12">
                 
                    <h4 style="text-align: center;font-weight: bold;"><u><?php echo isset($report->panel_name) ? html_escape($report->panel_name) : '—'; ?></u></h4>
                </div>
               
            </div>

            <hr>

            <?php if (!empty($section_blocks)) { ?>
                <?php foreach ($section_blocks as $block) { ?>
                    <div class="report-section-block" style="margin-bottom:24px;">
                        <h4 style="border-bottom:2px solid #337ab7;padding-bottom:6px;color:#337ab7;">
                            <?php echo html_escape($block['section_name']); ?>
                        </h4>
                        <table class="table table-bordered table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th style="width:32%;">Parameter</th>
                                    <th style="width:18%;">Result</th>
                                    
                                    <th style="width:35%;">Normal Range</th>
                                </tr>
                            </thead>
                            <?php foreach ($block['rows'] as $row) {
                                $unit = isset($row->unit) && $row->unit !== '' ? ' ' . html_escape($row->unit) : '';
                                $badge = lab_report_status_badge($row->status);
                                $normal_range = $this->Report_model->format_normal_range($row);
                            ?>
                                <tr>
                                    <td><strong><?php echo html_escape($row->parameter_name); ?></strong><?php echo $unit; ?></td>
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
            setTimeout(function() { window.print(); }, 400);
        });
    </script>
<?php } ?>
