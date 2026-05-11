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
    }
</style>

<div class="row">
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
        <table border="0" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
            <tr>
                <td>Patient Name</td>

                <td>

                    <b> <?php echo $patient_test_entry->patient_name ?></b>
                </td>
                <td>Date</td>

                <td>

                    <b> <?php echo date('d-m-Y', strtotime($patient_test_entry->date)) ?> </b>
                </td>


            </tr>
            <tr>
                <td>Age</td>
                <td>
                    <b>
                    <?php
                            $age_parts = [];

                            if ($patient_test_entry->age_year > 0) {
                                $age_parts[] = $patient_test_entry->age_year . ' ' . ($patient_test_entry->age_year == 1 ? 'Year' : 'Years');
                            }

                            if ($patient_test_entry->age_month > 0) {
                                $age_parts[] = $patient_test_entry->age_month . ' ' . ($patient_test_entry->age_month == 1 ? 'Month' : 'Months');
                            }

                            if ($patient_test_entry->age_day > 0) {
                                $age_parts[] = $patient_test_entry->age_day . ' ' . ($patient_test_entry->age_day == 1 ? 'Day' : 'Days');
                            }

                            echo implode(' ', $age_parts);
                            ?>
                    </b>
                </td>
                <td>Gender</td>
                <td>

                    <b> <?php echo $patient_test_entry->gender ?> </b>
                </td>
            </tr>
            <tr>
                <td>Mobile</td>

                <td>
                    <b> <?php echo $patient_test_entry->mobile ?> </b>
                </td>
                <td>Invoice No</td>

                <td>

                    <b> <?php echo $patient_test_entry->invoice_no ?> </b>
                </td>

            </tr>
            <tr>
                <td>Ref.Doctor</td>

                <td>
                    <b> <?php echo $doctor->doctor_name ?> </b>
                </td>


            </tr>
        </table>
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
    <div class="report-footer row">
        <?php
        $report_footer = $this->db->where('report_footer_id', '1')->get('report_footer')->row();
        ?>
        <table border="0" style="width: 100%; ">
            <tr>
                <td style="width: 50% "><?php //echo $report_footer->report_footer_1                                                  
                                        ?></td>
                <!--<td style="width: 33% "><?php echo $report_footer->report_footer_2 ?></td>-->
                <td style="width: 50% "> <?php echo $report_footer->report_footer_3 ?></td>
            </tr>
        </table>



    </div>

</div>