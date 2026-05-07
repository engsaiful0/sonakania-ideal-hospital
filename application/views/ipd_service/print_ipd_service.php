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
    }

    .p1 {
        line-height: 80% !important;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <button onclick="window.print()" id="sumbit_button" class="btn btn-primary">Print</button>
    </div>

</div>
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:50px;">
    <?php
    $compnay = $this->db->where('company_id', '1')->get('company')->row();

    $ipd_service = '';
    if ($this->session->userdata('print_ipd_service_id')) {
        $ipd_service_id = $this->session->userdata('print_ipd_service_id');
        $ipd_service = $this->db->where('ipd_service_id', $ipd_service_id)
            ->get('ipd_service')
            ->row();
    } else {
        $ipd_service = $this->db->where('ipd_service_id', $ipd_service_id)
            ->get('ipd_service')
            ->row();
    }
    $reference_doctor = $this->db->where('doctor_id', $ipd_service->reference_doctor_id)->get('doctor')->row();
    $patient = $this->db->where('ipd_patient_id', $ipd_service->ipd_patient_id)
        ->get('ipd_patient')
        ->row();
    ?>
    <div class="" style="width: 100%;margin-bottom: 10px;">
        <div style="width: 15%;float: left;margin-top:20px">
            <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
        </div>
        <div style="width: 70%;float: left;text-align: center">
            <p style="text-align: center"><span style="text-align: center;font-size: 20px;text-align: center "> <?php echo $compnay->company_name ?><br><?php echo $compnay->address ?></span><br>
                <span style="text-align: center">
                    Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                </span>
            </p>
        </div>
        <div style="width: 15%;float: left;margin-top:20px">
            <img src="<?php echo base_url('IpdPatientController/set_barcode/' . $patient->patient_unique_id); ?>" alt="Barcode">
        </div>
    </div>
    <?php


    $ipd_service_details = $this->db
        ->where('ipd_service_id', $ipd_service_id)
        ->get('ipd_service_details')
        ->result();


    $user = getUserById($ipd_service->user_id);

    ?>


    <div class="customer-copy" style="clear:left;margin-top: 50px; ">
        <div class="name" style="width: 100%;margin-bottom: 10px;">
            <table border="1" class="table table-bordered table-hover" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                <t>
                    <td colspan="4" style="text-align: center;"><u><b>IPD Service</b></u></td>
                </t>
                <tr>
                    <td>Patient</td>

                    <td>
                        <b><?php echo $patient->patient_name ?></b> </b>
                    </td>
                    <td>Mobile</td>
                    <td>
                        <b><?php echo $patient->mobile_number ?></b>
                    </td>
                    <td>Patient Id</td>
                    <td>
                        <b><?php echo $patient->patient_unique_id ?></b>
                    </td>
                </tr>
                <tr>
                    <td>Age</td>
                    <td>
                        <b>
                            <?php if ($patient->age_year > 0): ?>
                                <?php echo $patient->age_year . ' ' . ($patient->age_year == 1 ? 'Year' : 'Years'); ?>
                            <?php endif; ?>

                            <?php if ($patient->age_month > 0): ?>
                                <?php echo ' ' . $patient->age_month . ' ' . ($patient->age_month == 1 ? 'Month' : 'Months'); ?>
                            <?php endif; ?>

                            <?php if ($patient->age_day > 0): ?>
                                <?php echo ' ' . $patient->age_day . ' ' . ($patient->age_day == 1 ? 'Day' : 'Days'); ?>
                            <?php endif; ?>
                        </b>
                    </td>
                    <td>Date</td>
                    <td>
                        <b> <?php echo date('d-m-Y', strtotime($ipd_service->date)) ?></b>
                    </td>
                    <?php if ($reference_doctor != '') {
                    ?>
                        <td>Ref. Doctor</td>
                        <td>
                            <b> <?php echo $reference_doctor->doctor_name ?></b><br>
                        </td>
                    <?php
                    } ?>
                </tr>

            </table>
        </div>
        <div class="product" style="height: 300px; ">
            <table border="1" class="table table-bordered table-hover" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
                <tr>
                    <td>Sl</td>
                    <td>Service Name</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Amount</td>
                </tr>
                <?php
                $sl = 1;

                foreach ($ipd_service_details as $ipd_service_detail) {

                    $ipd_service_item = $this->db
                        ->where('ipd_service_item_id', $ipd_service_detail->ipd_service_item_id)
                        ->get('ipd_service_item')
                        ->row();
                ?>
                    <tr>
                        <td><?php echo $sl++ ?></td>
                        <td><?php echo $ipd_service_item->name ?></td>
                        <td style="text-align:left"><?php echo $ipd_service_detail->price ?></td>
                        <td style="text-align:left"><?php echo $ipd_service_detail->quantity ?></td>
                        <td style="text-align:left"><?php echo $ipd_service_detail->amount ?></td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="4" style="text-align:right">Total Amount</td>
                    <td style="text-align:left"><?php echo $ipd_service->net_total ?></td>
                </tr>
            </table>
            <p style="text-align: right;padding-right: 100px;padding-top: 20px; "> Entry By: <?php echo  $user->name ?? "" ?></p>
        </div>



    </div>
    <?php
    $ipd_service_details = $this->db
        ->where('ipd_service_id', $ipd_service->ipd_service_id)
        ->get('ipd_service_details')
        ->result();


    // Step 1: Group test entries by test_category_id
    $grouped_tests = [];

    foreach ($ipd_service_details as $entry) {
        // Get test info to determine test_category_id
        $ipd_service_item = $this->db
            ->where('ipd_service_item_id', $entry->ipd_service_item_id)
            ->get('ipd_service_item')
            ->row();

        if ($ipd_service_item) {
            $grouped_tests[$ipd_service_item->test_category_id][] = (object)[
                'ipd_service_item' => $ipd_service_item,
                'entry' => $entry
            ];
        }
    }

    // Step 2: Loop through each department (category) and print slip
    foreach ($grouped_tests as $test_category_id => $tests) {
        // Get department (category) name
        $category = $this->db
            ->where('test_category_id', $test_category_id)
            ->get('test_categories')
            ->row();
        if (!$category) continue; // Skip if category not found
        $category_name = $category ? $category->test_category_name : 'Unknown';
        $test_category_name_bangla = $category ? $category->test_category_name_bangla : 'Unknown';
        $room_no = $category ? $category->room : 'Unknown';
        $room_bangla = $category ? $category->room_bangla : 'Unknown';
    ?>
        <div class="lab-copy" style="margin-top: 700px;">
            <div class="" style="width: 100%;margin-bottom: 10px;">
                <div style="width: 15%;float: left;margin-top:20px">
                    <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
                </div>
                <div style="width: 70%;float: left;text-align: center">
                    <p style="text-align: center"><span style="text-align: center;font-size: 20px;text-align: center "> <?php echo $compnay->company_name ?><br><?php echo $compnay->address ?></span><br>
                        <span style="text-align: center">
                            Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                        </span>
                    </p>
                </div>
                <div style="width: 15%;float: left;margin-top:20px">
                    <img src="<?php echo base_url('IpdPatientController/set_barcode/' . $patient->patient_unique_id); ?>" alt="Barcode">
                </div>
                <h3 style="clear:left; text-align:center"><b><?php echo strtoupper($test_category_name_bangla); ?> বিভাগ-রুম নং:<?php echo $room_bangla ?></b></h3>
            </div>
            <div class="name" style="width: 100%;margin-bottom: 30px;">

                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr>
                        <td>Patient</td>

                        <td>
                            <b><?php echo $patient->patient_name ?></b> </b>
                        </td>
                        <td>Mobile</td>
                        <td>
                            <b><?php echo $patient->mobile_number ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td>Patient Id</td>
                        <td>
                            <b><?php echo $patient->patient_unique_id ?></b>
                        </td>
                        <td>Date</td>
                        <td>
                            <b> <?php echo date('d-m-Y', strtotime($ipd_service->date)) ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td>Age</td>
                        <td>
                            <b>
                                <?php
                                $age_parts = [];

                                if ($patient->age_year > 0) {
                                    $age_parts[] = $patient->age_year . ' ' . ($patient->age_year == 1 ? 'Year' : 'Years');
                                }

                                if ($patient->age_month > 0) {
                                    $age_parts[] = $patient->age_month . ' ' . ($patient->age_month == 1 ? 'Month' : 'Months');
                                }

                                if ($patient->age_day > 0) {
                                    $age_parts[] = $patient->age_day . ' ' . ($patient->age_day == 1 ? 'Day' : 'Days');
                                }

                                echo implode(' ', $age_parts);
                                ?>
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>
                            <b><?php echo $patient->address ?></b>
                        </td>
                        <td>Reference</td>
                        <td>
                            <?php
                            $reference_doctor = $this->db->where('doctor_id', $patient->reference_doctor_id)->get('doctor')->row();
                            $reference_media = $this->db->where('reference_media_id', $patient->reference_media_id)->get('reference_media')->row();
                            $reference_director = $this->db->where('director_id', $patient->reference_director_id)->get('director')->row();
                            $reference_employee = $this->db->where('employee_id', $patient->reference_employee_id)->get('employee')->row();



                            if ($reference_doctor != '') {
                            ?>
                                Doctor:<b> <?php echo $reference_doctor->doctor_name ?></b><br>
                            <?php
                            }
                            ?>
                            <?php
                            if ($reference_media != '') {
                            ?>
                                Media:<b> <?php echo $reference_media->reference_media_name ?></b><br>
                            <?php
                            }
                            ?>
                            <?php
                            if ($reference_director != '') {
                            ?>
                                Director:<b> <?php echo $reference_director->name ?></b><br>
                            <?php
                            }
                            if ($reference_employee != '') {
                            ?>
                                Employee:<b> <?php echo $reference_employee->employee_name ?></b><br>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>

                </table>
            </div>

            <div class="product" style="height: auto;">
                <table border="1" style="width: 100%; border-collapse: collapse; color: black;">
                    <tr>
                        <td>Sl</td>
                        <td>Service Name</td>
                        <td style="text-align:center">Quantity</td>
                    </tr>
                    <?php
                    $sl = 1;
                    foreach ($tests as $item) {
                    ?>
                        <tr>
                            <td><?php echo $sl++; ?></td>
                            <td><?php echo $item->ipd_service_item->name; ?></td>
                            <td style="text-align: center"><?php echo $item->entry->quantity; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    <?php
    }
    ?>



</div>