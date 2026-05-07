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
        <button onclick="window.print()" id="submit_button" class="btn btn-primary">Print</button>
    </div>
</div>
<?php
$compnay = $this->db->where('company_id', '1')->get('company')->row();
?>
<div id="report" style="width: 90%; margin:0 auto; margin-left:45px; margin-top:20px;">

    <?php
    error_reporting(0);
    $test_result = $this->db->where('patient_test_entry_id', $patient_test_entry_id)
        ->get('test_result')
        ->row();

    if (empty($test_result)) {
        echo "<p style='text-align: center; font-size: 20px; color: red; font-weight: bold; margin-top: 50px;'>
                Report result is pending.
              </p>";
    } else {
        $test_result_details = $this->db->where('test_result_id', $test_result->test_result_id)
            ->get('test_result_details')->result();

        $patient_test_entry = $this->db->where('patient_test_entry_id', $test_result->patient_test_entry_id)
            ->get('patient_test_entry')->row();
        $doctor = $this->db->where('doctor_id', $patient_test_entry->doctor_id)
            ->get('doctor')->row();

        if ($test_result->manual_report != '') {
            $file_extension = pathinfo($test_result->manual_report, PATHINFO_EXTENSION);
            $file_path = base_url() . 'assets/manual_report/' . $test_result->manual_report;
    ?>
            <div>
                <?php if ($file_extension == 'pdf'): ?>
                    <!-- Embed the PDF directly in the page -->
                    <embed src="<?php echo $file_path; ?>" width="100%" height="600px" type="application/pdf">
                <?php elseif ($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png'): ?>
                    <!-- Display the image -->
                    <img src="<?php echo $file_path; ?>" alt="Manual Report" style="width: 100%; height: auto;">
                <?php elseif ($file_extension == 'doc' || $file_extension == 'docx'): ?>
                    <!-- Provide a link to download or open the DOC file -->
                    <a href="<?php echo $file_path; ?>" target="_blank" class="btn btn-primary">Click to view manual report</a>
                <?php else: ?>
                    <!-- Handle other file types (optional) -->
                    <a href="<?php echo $file_path; ?>" target="_blank" class="btn btn-primary">Click to view manual report</a>
                <?php endif; ?>
            </div>
        <?php
        } else {
        ?>
            <div class="" style="width: 100%;margin-bottom: 10px;">
                <div style="width: 15%;float: left;margin-top:20px">
                    <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
                </div>
                <div style="width: 70%;float: left;text-align: center">
                    <p style="text-align: center"><span style="text-align: center;font-size: 20px;text-align: center "> <?php echo $compnay->company_name ?></span><br>
                        <span style="text-align: center"> Mobile: <?php echo $compnay->mobile ?><br>
                            Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                        </span>
                    </p>
                </div>
            </div>
            <div class="name" style="width: 100%; margin-bottom: 10px;">
                <table border="1" style="width: 100%; border-collapse:collapse; margin:0 auto; color:black;">
                    <tr>
                        <td>Patient Name</td>
                        <td><b> <?php echo $patient_test_entry->patient_name ?></b></td>
                        <td>Date</td>
                        <td><b> <?php echo date('d-m-Y', strtotime($patient_test_entry->date)) ?> </b></td>
                    </tr>
                    <tr>
                        <td>Age</td>
                        <td><b><?php echo $patient_test_entry->age ?></b></td>
                        <td>Gender</td>
                        <td><b> <?php echo $patient_test_entry->gender ?> </b></td>
                    </tr>
                    <tr>
                        <td>Mobile</td>
                        <td><b> <?php echo $patient_test_entry->mobile ?> </b></td>
                        <td>Invoice No</td>
                        <td><b> <?php echo $patient_test_entry->invoice_no ?> </b></td>
                    </tr>
                    <tr>
                        <td>Ref. Doctor</td>
                        <td><b> <?php echo $doctor->doctor_name ?> </b></td>
                    </tr>
                </table>
            </div>

            <div class="product" style="height: 600px; margin-top: 20px;">
                <table border="1" style="width: 100%; border-collapse:collapse; margin:0 auto; color:black">
                    <tr>
                        <td>Sl</td>
                        <td>Test Name</td>
                        <td>Result</td>
                        <td>Unit</td>
                        <td colspan="2">Normal Range</td>
                    </tr>
                    <?php
                    $sl = 1;
                    foreach ($test_result_details as $test_result_details_value) {
                        $test_configuration = $this->db->where('test_id', $test_result_details_value->test_id)
                            ->where('is_deleted', '0')
                            ->get('test_configuration')->row();
                        $test = $this->db->where('test_id', $test_result_details_value->test_id)
                            ->get('test')->row();

                        if ($test_result_details_value->test_configuration_value == '') continue;
                    ?>
                        <tr>
                            <td><?php echo $sl++ ?></td>
                            <td><?php echo $test->test_name ?></td>
                            <td><?php echo ($test_result_details_value->bold == 'Yes') ? "<b>" . $test_result_details_value->test_configuration_value . "</b>" : $test_result_details_value->test_configuration_value; ?></td>
                            <td><?php echo $test_configuration->unit ?></td>
                            <td colspan="2"><?php echo $test_configuration->normal_range ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>

            <div class="report-footer row">
                <?php
                $report_footer = $this->db->where('report_footer_id', '1')->get('report_footer')->row();
                ?>
                <table border="0" style="width: 100%;">
                    <tr>
                        <td style="width: 50%"></td>
                        <td style="width: 50%"> <?php echo $report_footer->report_footer_3 ?></td>
                    </tr>
                </table>
            </div>

    <?php
        }
    }
    ?>

</div>