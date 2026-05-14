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

        #report table {
            table-layout: auto !important;
        }

        #report td,
        #report th {
            white-space: normal !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
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
        #report .table-bordered>tfoot>tr>td {
            border: 1px solid #000 !important;
            border-color: #000 !important;
        }

        #report .well {
            border: 1px solid #000 !important;
            background: transparent !important;
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
    $doctor = $this->db->where('doctor_id', $patient_test_entry->doctor_id)
        ->get('doctor')->row();
    ?>
    <?php
    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    ?>

    <div class="name" style="width: 100%;margin-bottom: 10px;">
        <p>

            <b>Patient Name:</b> <?php echo $patient_test_entry->patient_name ?>, <b>Date:</b> <?php echo date('d-m-Y', strtotime($patient_test_entry->date)) ?>, <b>Age:</b> <?php echo $patient_test_entry->age ?>, <b>Gender:</b> <?php echo $patient_test_entry->gender ?>, <b>Mobile:</b> <?php echo $patient_test_entry->mobile ?>, <b>Invoice No:</b> <?php echo $patient_test_entry->invoice_no ?>, <b>Ref.Doctor:</b> <?php echo $doctor->doctor_name ?>
        </p>

       
    </div>
    <div class="product" style="height: 600px;margin-top: 20px; ">
        <?php
        if ($test_group->test_group_name != 'Urine Examination Report' && $test_group->test_group_name != 'Blood Grouping and RH Type') {
        ?>
            <p style="text-align: center"><b><u><?php
                                                echo $test_group->test_group_name;
                                                ?>
                    </u></b></p>
            <?php
            if ($test_group->test_group_name == 'Heamatology') {
            ?>
                <p style="text-align: center">Test are carried out by Automated Haematology Analyzer Pentra ABX-120DX & Checked Manually</p>
            <?php
            } else if ($test_group->test_group_name == 'Biochemistry Analysis') {
            ?>
                <p style="text-align: center;font-style: initial">Estimations are carried out by Semi-auto Biochemistry Analyzer BIOGEN-5500,biogenGmbH,Germany.</p>

            <?php
            }
            if ($test_result->manual_report != '') {
            ?>
                <a target="_blank" href="<?php echo base_url() ?>assets/manual_report/<?php echo $test_result->manual_report ?>" class="btn btn-primary">Click to view manual report</a>
            <?php
            } else {


            ?>
                <table border="0" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
                    <tr style="font-weight: bold;">

                        <td>Test Name</td>
                        <td>Result</td>
                        <td>Unit</td>
                        <td colspan="2">Normal Range</td>
                    </tr>
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
                        if ($test->test_name == 'Red Blood Cells' || $test->test_name == 'White Blood Cell(Total Count)' || $test->test_name == 'Platelet Count') {
                            if ($coun_test_total_count == 1) {
                                $coun_test_total_count++;
                    ?>
                                <tr>
                                    <td style="text-align: left"><b>Total Count</b></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                </tr>
                            <?php
                            }
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
                                <td><?php echo $test_configuration->unit ?></td>
                                <td colspan="2"><?php echo $test_configuration->normal_range ?></td>
                            </tr>
                            <?php
                        } else if ($test->test_name == 'Neutrophils' || $test->test_name == 'neutrophils' || $test->test_name == 'Lymphocytes' || $test->test_name == 'lymphocytes' || $test->test_name == 'Monocytes' || $test->test_name == 'monocytes' || $test->test_name == 'Esinophil' || $test->test_name == 'esinophil' || $test->test_name == 'Basophils' || $test->test_name == 'basophils') {
                            if ($coun_test_differential == 1) {
                                $coun_test_differential++;
                            ?>
                                <tr>
                                    <td></td>
                                    <td style="text-align: left"><b>Differential Count</b></td>
                                    <td></td>
                                    <td></td>
                                    <td colspan="2"></td>
                                    <!--<td style="text-align: left"><b>Absolute Value</b></td>-->
                                </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td><?php echo $sl++ ?></td>
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
                                <td><?php echo $test_configuration->unit ?></td>
                                <td colspan="2"><?php echo $test_configuration->normal_range ?></td>
                                <!--<td style="text-align: left"><?php //echo $test_configuration->absolute_value       
                                                                    ?></td>-->
                            </tr>
                        <?php
                        } else {
                        ?>
                            <tr>
                                <td><?php echo $sl++ ?></td>
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
                                <td><?php echo $test_configuration->unit ?></td>
                                <td colspan="2" style="text-align: left"><?php echo $test_configuration->normal_range ?></td>
                            </tr>

                    <?php
                        }
                    }
                    ?>


                </table>
            <?php
            }
        } else if ($test_group->test_group_name == 'Urine Examination Report') {
            /* For urine test examination repot */
            ?>

            <p style="text-align: center"><b><u><?php
                                                echo $test_group->test_group_name;
                                                ?></u></b></p>

            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">

                <tr>
                    <td>Sl</td>
                    <td>Test Name</td>
                    <td>Result</td>
                    <td>Unit</td>
                    <td colspan="2">Normal Range</td>
                </tr>
                <?php
                error_reporting(0);
                $sl = 1;
                $coun_test_total_count = 1;
                $coun_test_differential = 1;
                $test_group = $this->db->where('test_group_name', 'Urine Examination Report')/* Only for Urine Examination Report */
                    ->get('test_group')->row();

                $test_sub_group = $this->db->where('test_group_id', $test_group->test_group_id)/* Only for Urine Examination Report */
                    ->get('test_sub_group')->result();

                foreach ($test_sub_group as $test_sub_group_value) {
                    if ($test_sub_group_value->sub_group_name != 'Test Name') {
                ?>
                        <tr>
                            <td style="text-align: left"><b></b></td>
                            <td style="text-align: left"><b><?php echo $test_sub_group_value->sub_group_name ?></b></td>
                            <td colspan="3"></td>
                        </tr>
                    <?php
                    }
                    $sl = 1;
                    ?>

                    <?php
                    $test = $this->db->where('test_sub_group_id', $test_sub_group_value->test_sub_group_id)
                        ->get('test')->result();
                    foreach ($test as $test_value) {
                        $test_result_details = $this->db
                            ->where('test_result_id', $test_result_id)
                            ->where('test_id', $test_value->test_id)
                            ->where('is_deleted', '0')
                            ->get('test_result_details')->row();
                        $test_configuration = $this->db->where('test_id', $test_value->test_id)
                            ->where('is_deleted', '0')
                            ->get('test_configuration')->row();
                        if ($test_result_details->test_configuration_value == '') {
                            continue;
                        }
                    ?>
                        <tr>
                            <td><?php echo $sl++ ?></td>
                            <td><?php echo $test_value->test_name ?></td>
                            <td><?php
                                if ($test_result_details->bold == 'Yes') {
                                ?>
                                    <p style=" font-weight: bold"><?php echo $test_result_details->test_configuration_value ?></p>
                                <?php
                                } else {
                                    echo $test_result_details->test_configuration_value;
                                }
                                ?>
                            </td>
                            <td><?php echo $test_configuration->unit ?></td>
                            <td colspan="2"><?php echo $test_configuration->normal_range ?></td>
                        </tr>
                <?php
                    }
                }
                ?>
            </table>
        <?php
        } else if ($test_group->test_group_name == 'Blood Grouping and RH Type') {
            /* For Blood Grouping repot */
        ?>

            <p style="text-align: center"><b><u><?php
                                                echo $test_group->test_group_name;
                                                ?></u></b></p>

            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">

                <tr>
                    <td>Sl</td>
                    <td>ABO Grouping & RH Type</td>
                    <td>Result</td>

                </tr>
                <?php
                error_reporting(0);
                $sl = 1;
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
                        <td><?php echo $sl++ ?></td>
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

                    </tr>
                <?php
                }
                ?>
            </table>
        <?php
        }
        ?>
    </div>
    <div class="report-footer row" style="margin-top: 150px;">
        <?php
        $report_footer = $this->db->where('report_footer_id', '1')->get('report_footer')->row();
        ?>




    </div>

</div>