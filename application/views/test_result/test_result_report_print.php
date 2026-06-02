<style>
    @media print {

        /* Hide layout chrome; keep #report in normal flow for correct page breaks. */
        #tray,
        #menu,
        #aside,
        #footer,
        #img,
        hr.noscreen,
        .no-print {
            display: none !important;
        }

        #main,
        #cols,
        #content {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            float: none !important;
            display: block !important;
        }

        #content {
            margin-left: 0 !important;
        }

        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            height: auto !important;
        }

        #report {
            position: static !important;
            left: auto !important;
            top: auto !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            margin-top: 40mm !important;
            margin-left: 0 !important;
            box-sizing: border-box !important;
            padding: 0 8mm !important;
            overflow: visible !important;
        }

        #report .product {
            height: auto !important;
            min-height: 0 !important;
            max-height: none !important;
        }

        #report .product table td,
        #report .product table th {
            white-space: normal !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
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

    #report .product table {
        width: 100%;
        border-collapse: collapse;
        border: none !important;
        margin-bottom: 10px;
    }

    /* Header row only — bordered box */
    #report #result-header th {
        border: 1px solid #333 !important;
        padding: 6px 8px;
        vertical-align: top;
        text-align: left;
        font-weight: bold;
        background: transparent !important;
    }

    #report .product table tbody td {
        border: none !important;
        padding: 6px 8px;
        vertical-align: top;
        background: transparent !important;
    }

    .p1 {
        line-height: 80% !important;
    }
</style>

<div class="row no-print">
    <div class="col-md-12">
        <button onclick="window.print()" id="sumbit_button" class="btn btn-primary">Print</button>
    </div>
</div>
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:20px;">
    <?php
    error_reporting(0);
    $test_result = '';


    if ($this->session->userdata('print_test_result_id')) {
        $print_test_result_id = $this->session->userdata('print_test_result_id');
        $test_result = $this->db->where('test_result_id', $print_test_result_id)
            ->get('test_result')
            ->row();
    } else {
        $test_result = $this->db->where('test_result_id', $test_result_id)
            ->get('test_result')
            ->row();
    }

    $test_result_details = $this->db->where('test_result_id', $test_result->test_result_id)
        ->get('test_result_details')->result();

    $patient_test_entry = $this->db->where('patient_test_entry_id', $test_result->patient_test_entry_id)
        ->get('patient_test_entry')->row();

    $doctor = $this->db->where('doctor_id', $patient_test_entry->reference_doctor_id)
        ->get('doctor')->row();
    $test_group = '';

    /* Unique test_group_name for each detail line with a result (from test.test_group_id). */
    $unique_group_names = array();
    foreach ($test_result_details as $d) {
        if (!isset($d->test_configuration_value) || trim((string) $d->test_configuration_value) === '') {
            continue;
        }
        $test_row = $this->db->where('test_id', $d->test_id)->get('test')->row();
        if (!$test_row || empty($test_row->test_group_id)) {
            continue;
        }
        $gid = (int) $test_row->test_group_id;
        if ($gid < 1) {
            continue;
        }
        $test_group = $this->db->where('test_group_id', $gid)->get('test_group')->row();
    }

    ?>
    <?php
    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    ?>

    <div class="name" style="width: 100%;margin-bottom: 10px;">
        <table border="0" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
            <tr>
                <td>Patient Name:<b><?php echo $patient_test_entry->patient_name ?></b>, Date:<b> <?php echo date('d-m-Y', strtotime($patient_test_entry->date)) ?></b>, Age:<b> <?php echo $patient_test_entry->age ?></b>, Gender:<b> <?php echo $patient_test_entry->gender ?></b>, Mobile:<b><?php echo $patient_test_entry->mobile ?></b>, Invoice No:<b><?php echo $patient_test_entry->invoice_no ?></b></td>
            </tr>
            <tr>
                <td>Referring Doctor: <b><?php echo $doctor->doctor_name ?>, <?php echo $doctor->degree ?></b></td>
            </tr>
        </table>

          
        <?php if (!empty($test_group)) { ?>
            <p style="margin-top: 6px; margin-bottom: 0; text-align: center; font-weight: bold;">
                <u><?php echo strtoupper(html_escape($test_group->test_group_name)) . ' REPORT'; ?></u>
            </p>
        <?php } ?>
        <p style="text-align: center;font-weight: bold;"><?php echo isset($test_group->machine_name) ? strtoupper(html_escape($test_group->machine_name)) : ''; ?></p>
    </div>
    <div class="product" style="height: 600px;margin-top: 1px; ">

        <?php

        ?>
        <table  border="0" style="width: 100%;border-collapse:collapse">
            <thead>
            <tr id="result-header">
                <th>Investigation</th>
                <th>Result</th>
                <th colspan="2">Normal Range</th>
            </tr>
            </thead>
     
            <tbody>
            <?php
            error_reporting(0);
            $sl = 1;
            $coun_test_total_count = 1;
            $coun_test_differential = 1;
            foreach ($test_result_details as $test_result_details_value) {
                $test_configuration = $this->db->where('test_id', $test_result_details_value->test_id)
                    ->where('is_deleted', '0')
                    ->get('test_configuration')->row();
                $test = $this->db->where('test_id', $test_result_details_value->test_id)
                    ->get('test')->row();
                if ($test_result_details_value->test_configuration_value == '')
                    continue;
            ?>
                <tr>

                    <td><?php echo $test->test_name ?></td>
                    <td><?php
                        if ($test_result_details_value->bold == 'Yes') {
                        ?>
                            <p style=" font-weight: bold"><?php echo $test_result_details_value->test_configuration_value ?></p>
                        <?php
                        } else {
                            echo $test_result_details_value->test_configuration_value;
                        }
                        ?>
                    </td>

                    <td colspan="2" style="text-align: left"><?php echo $test_configuration->normal_range ?></td>
                </tr>
            <?php


            }
            ?>
            </tbody>
        </table>
        <?php


        ?>
    </div>
    <div class="report-footer row" style="margin-top: 150px;">
        <?php
        $report_footer = $this->db->where('report_footer_id', '1')->get('report_footer')->row();
        ?>
    </div>

</div>