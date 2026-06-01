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

<div class="row">
    <div class="col-md-12">
        <button onclick="window.print()" id="sumbit_button" class="btn btn-primary">Print</button>
    </div>

</div>
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:50px;">

    <?php
    error_reporting(0);
    $patient_test_entry = '';
    if ($this->session->userdata('patient_test_entry_id')) {
        $patient_test_entry_id = $this->session->userdata('patient_test_entry_id');
        $patient_test_entry = $this->db->where('patient_test_entry_id', $patient_test_entry_id)
            ->get('patient_test_entry')
            ->row();
    } else {

        $patient_test_entry = $this->db->where('patient_test_entry_id', $patient_test_entry_id)
            ->get('patient_test_entry')
            ->row();
    }

    $doctor = $this->db->where('doctor_id', $patient_test_entry->reference_doctor_id)
        ->get('doctor')
        ->row();
    $patient_test_entry_details = $this->db
        ->where('patient_test_entry_id', $patient_test_entry_id)
        ->get('patient_test_entry_details')
        ->result();

    $user = getUserById($patient_test_entry->user_id);

    $this->db->select_sum('paid');
    $this->db->from('test_collection');
    $this->db->where('patient_test_entry_id', $patient_test_entry_id); // Filter by today's date
    $query = $this->db->get();
    $total_paid_of_this_test = $query->row()->paid; // Return 0 if no income found

    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    $test_categories = $this->db->select('*')->get('test_categories')->result();
    ?>

    <div class="customer-copy" style="margin-top: 50px; ">
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
                <img src="<?php echo base_url('TestController/set_barcode/' . $patient_test_entry->invoice_no); ?>" alt="Barcode">
            </div>
            <div style="clear: left;">
                <p style="text-align: left;font-weight:bold">
                    <?php if (!empty($duplicate_or_main)) {
                        echo $duplicate_or_main;
                    } ?></p>
            </div>

        </div>

        <div class="name" style="width: 100%;margin-bottom: 10px;">
            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                <tr>
                    <td>Name</td>
                    <td>
                        <b><?php echo $patient_test_entry->patient_name ?></b> </b>
                    </td>
                    <td>Gender</td>

                    <td>

                        <b> <?php echo $patient_test_entry->gender ?></b>
                    </td>
                    <td>Date & Time</td>
                    <td>
                        <?php
                        if ($patient_test_entry->date != '' && $patient_test_entry->time != '') {
                            echo '<b>' . date('d-m-Y', strtotime($patient_test_entry->date)) . ' & ' . $patient_test_entry->time . ' (Customer Copy)</b>';
                        }
                        ?>


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

                    <td>Mobile</td>
                    <td>
                        <b> <?php echo $patient_test_entry->mobile_number ?></b>
                    </td>
                    <td>Invoice No</td>
                    <td>
                        <b> <?php echo $patient_test_entry->invoice_no ?></b>
                    </td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>
                        <b><?php echo $patient_test_entry->address ?></b>
                    </td>
                    <td>Reference</td>
                    <td>
                        <?php
                        $reference_doctor = $this->db->where('doctor_id', $patient_test_entry->reference_doctor_id)->get('doctor')->row();
                        $reference_media = $this->db->where('reference_media_id', $patient_test_entry->reference_media_id)->get('reference_media')->row();
                        $reference_director = $this->db->where('director_id', $patient_test_entry->reference_director_id)->get('director')->row();
                        $reference_employee = $this->db->where('employee_id', $patient_test_entry->reference_employee_id)->get('employee')->row();



                        if ($reference_doctor != '') {
                        ?>
                            Doctor:<b> <?php echo $reference_doctor->doctor_name. '<br>' . $reference_doctor->degree  ?></b><br>
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
                    <td>Discount Ref</td>
                    <td><?php echo $patient_test_entry->discount_reference ?></td>
                </tr>
                <?php
                if ($patient_test_entry->status == 'Returned') {

                ?>
                    <tr style="background-color:#005825;">
                        <td colspan="6" style="text-align:center;font-weight:bold;color:white">Returned</td>
                    </tr>
                    <tr>
                        <td>Return Date</td>
                        <td><b><?php echo date('d-m-Y', strtotime($patient_test_entry->return_date)) ?></b></td>
                        <td>Return Amount</td>
                        <td><b><?php echo $patient_test_entry->returnable_amount ?></b></td>
                        <td>Return Reason</td>
                        <td colspan="3"><b><?php echo $patient_test_entry->return_reason ?></b></td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
        <div class="product" style="height: 300px; ">
            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
                <tr>
                    <td>Sl</td>
                    <td>Test Name</td>
                    <td>Delivery Date</td>
                    <td style="text-align:center">Room</td>
                    <td style="text-align:right">Price</td>
                    <td style="text-align:right">Total</td>
                    <?php
                    if ($patient_test_entry->status == 'Returned') {
                    ?>
                        <td style="text-align:right">Return</td>
                    <?php
                    }
                    ?>
                </tr>
                <?php
                $sl = 1;
                foreach ($patient_test_entry_details as $patient_test_entry_details_value) {
                    $test_id = $patient_test_entry_details_value->test_id;

                    $test = $this->db
                        ->where('test_id', $test_id)
                        ->get('test')
                        ->row();

                    if ($test) {
                        $test_category = $this->db
                            ->where('test_category_id', $test->test_category_id)
                            ->get('test_categories')
                            ->row();

                        $room = $test_category ? $test_category->room : 'N/A';
                    }
                ?>
                    <tr>
                        <td><?php echo $sl++ ?></td>
                        <td><?php echo $test->test_name ?></td>
                        <td><?php echo date('d-m-Y', strtotime($patient_test_entry_details_value->delivery_date)) ?></td>
                        <td style="text-align:center"><?php echo $room ?></td>
                        <td style="text-align:right"><?php echo $patient_test_entry_details_value->unit_price ?></td>
                        <td style="text-align:right"><?php echo $patient_test_entry_details_value->total_price ?></td>
                        <?php
                        if ($patient_test_entry->status == 'Returned') {
                        ?>
                            <td style="text-align:right"><?php echo $patient_test_entry_details_value->total_return ?></td>
                        <?php
                        }
                        ?>
                    </tr>
                <?php
                }
                ?>
            </table>
            <div class="custom-container-fluid">
                <div style="width: 30%;float:left">
                    <p style="margin-top:40px;">To see your report through the online, please scan the QR code:</p>

                </div>
                <div style="width: 30%;float:left;">
                    <img style="margin-top:20px;" src="<?php echo base_url('generate_qr_code/' . urlencode($patient_test_entry->username) . '/' . urlencode($patient_test_entry->password)); ?>" alt="QR Code">
                </div>
                <div style="width: 20%;float:left">
                    <?php
                    if ($patient_test_entry->net_total - $total_paid_of_this_test == 0) {
                    ?>
                        <h2 style="font-weight: bold;text-align: center;margin-top:40px; ">Paid</h2>
                    <?php
                    } else {
                    ?>
                        <h2 style="font-weight: bold;text-align: center;margin-top:40px; ">Due</h2>
                    <?php
                    }
                    ?>
                </div>
                <div style="width: 20%;float:left">
                    <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
                        <?php
                        if ($patient_test_entry->status == 'Returned') {
                        ?>
                            <tr style="color:white;background-color: red">
                                <td style="text-align:right;">Total Return</td>
                                <td style="text-align:right"><?php echo number_format($patient_test_entry->returnable_amount, 3) ?></td>
                            </tr>
                        <?php
                        }
                        ?>

                        <tr>
                            <td style="text-align:right">Sub Total</td>
                            <td style="text-align:right"><?php echo number_format($patient_test_entry->sub_total, 3) ?></td>
                        </tr>

                        <tr>
                            <?php if (!empty($patient_test_entry->discount) && $patient_test_entry->discount != 0) { ?>
                                <td style="text-align:right">D.Discount</td>
                                <td style="text-align: right"><?php echo $patient_test_entry->director_discount ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($patient_test_entry->discount) && $patient_test_entry->discount != 0) { ?>
                        <tr>
                            <td style="text-align:right">Special Discount</td>
                            <td style="text-align: right"><?php echo $patient_test_entry->discount ?></td>
                        </tr>
                    <?php } ?>

                    <tr>
                        <td style="text-align:right">Total Discount</td>
                        <td style="text-align: right"><?php echo $patient_test_entry->total_discount ?></td>
                    </tr>

                    <tr style="display:none">
                        <td style="text-align:right">vat(<?php echo $patient_test_entry->vat_in_percentage ?>)%</td>
                        <td style="text-align:right"><?php echo number_format($patient_test_entry->vat, 3) ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right">Net Total</td>
                        <td style="text-align:right"><?php echo number_format($patient_test_entry->net_total, 3) ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right">Paid</td>
                        <td style="text-align:right"><?php echo number_format($total_paid_of_this_test, 3) ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right">Due</td>
                        <td style="text-align:right"><?php echo number_format($patient_test_entry->net_total - $total_paid_of_this_test, 3) ?></td>
                    </tr>
                    </table>
                </div>
                <div style="clear: left;">
                    <p style="font-weight: bold;font-size:15px;margin-top: 20px;"><?php echo $compnay->report_instruction ?></p>
                </div>
                <div id="entry_by">
                    <div style="width: 50%;float:left">
                        <p style="text-align: left;">Software By:<span style="font-weight:bold"> Bijoylab, www.bijoylab.com</span></p>
                    </div>
                    <div style="width: 30%;float:right">
                        <p style="text-align: right;font-weight: bold;">Entry By: <?php echo  $user->name ?? "" ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <?php
    error_reporting(0);
    $patient_test_entry = $this->db->where('patient_test_entry_id', $patient_test_entry_id)
        ->get('patient_test_entry')
        ->row();
    $doctor = $this->db->where('doctor_id', $patient_test_entry->reference_doctor_id)
        ->get('doctor')
        ->row();
    $patient_test_entry_details = $this->db
        ->where('patient_test_entry_id', $patient_test_entry_id)
        ->get('patient_test_entry_details')
        ->result();


    // Step 1: Group test entries by test_category_id
    $grouped_tests = [];

    foreach ($patient_test_entry_details as $entry) {
        // Get test info to determine test_category_id
        $test = $this->db
            ->where('test_id', $entry->test_id)
            ->get('test')
            ->row();

        if ($test) {
            $grouped_tests[$test->test_category_id][] = (object)[
                'test' => $test,
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
                    <img src="<?php echo base_url('TestController/set_barcode/' . $patient_test_entry->invoice_no); ?>" alt="Barcode">
                </div>
            </div>
            <div class="name" style="width: 100%;margin-bottom: 30px;">

                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr>
                        <td>Name</td>

                        <td>

                            <b><?php echo $patient_test_entry->patient_name ?></b>
                        </td>
                        <td>Gender</td>
                        <td>

                            <b> <?php echo $patient_test_entry->gender ?></b>
                        </td>
                        <td>Date & Time</td>

                        <td>


                            <?php
                            if ($patient_test_entry->date != '' && $patient_test_entry->time != '') {
                                echo '<b>' . date('d-m-Y', strtotime($patient_test_entry->date)) . ' & ' . $patient_test_entry->time . '(Department Copy)</b>';
                            }
                            ?>
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
                        <td>Mobile</td>

                        <td>
                            <b> <?php echo $patient_test_entry->mobile_number ?></b>
                        </td>
                        <td>Invoice No</td>

                        <td>

                            <b> <?php echo $patient_test_entry->invoice_no ?></b>
                        </td>

                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>
                            <b><?php echo $patient_test_entry->address ?></b>
                        </td>
                        <td>Reference</td>
                        <td>
                            <?php
                            $reference_doctor = $this->db->where('doctor_id', $patient_test_entry->reference_doctor_id)->get('doctor')->row();
                            $reference_media = $this->db->where('reference_media_id', $patient_test_entry->reference_media_id)->get('reference_media')->row();
                            $reference_director = $this->db->where('director_id', $patient_test_entry->reference_director_id)->get('director')->row();
                            $reference_employee = $this->db->where('employee_id', $patient_test_entry->reference_employee_id)->get('employee')->row();



                            if ($reference_doctor != '') {
                            ?>
                                Doctor:<b> <?php echo $reference_doctor->doctor_name. '<br>' . $reference_doctor->degree  ?></b><br>
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
            <h3 style="text-align:center"><b><?php echo strtoupper($test_category_name_bangla); ?> বিভাগ-রুম নং:<?php echo $room_bangla ?></b></h3>
            <div class="product" style="height: auto;">
                <table border="1" style="width: 100%; border-collapse: collapse; color: black;">
                    <tr>
                        <td>Sl</td>
                        <td>Test Name</td>
                        <td>Delivery Date</td>
                        <td style="text-align:center">Quantity</td>
                    </tr>
                    <?php
                    $sl = 1;
                    foreach ($tests as $item) {
                    ?>
                        <tr>
                            <td><?php echo $sl++; ?></td>
                            <td><?php echo $item->test->test_name; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($item->entry->delivery_date)); ?></td>
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