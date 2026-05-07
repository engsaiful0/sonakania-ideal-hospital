<div class="panel-heading" style="font-weight: bold;text-align: center;">
    <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
        <tr style="background-color: #1A7DFF;color: white;font-weight: bold;">
            <td>#</td>
            <td>Name</td>
            <td>IPD</td>
            <td>IPD <br>Advance</td>
            <td>OPD</td>
            <td>Emergency</td>
            <td>Physio</td>
            <td>Test</td>
            <td>Due<br> Collection</td>
            <td>Total</td>
            <td>Return</td>
            <td>Balance</td>
        </tr>
        <?php
        $user_total_balance_array = array();
        if ($to_date != '') {
            $to_date = date('Y-m-d', strtotime($to_date));
        }
        if ($from_date  != '') {
            $from_date = date('Y-m-d', strtotime($from_date));
        }
        $reception_users = $this->db->where('user_type_id', 6)->get('user')->result();
        $user_id = '';
        $serial = 1;
        $grand_total_paid_sum = 0;
        $grand_total_return_sum = 0;
        $grand_total_due_payment_sum = 0;
        $total_ipd_sum = 0;
        $total_opd_sum = 0;
        $total_emergency_sum = 0;
        $total_physiotherapy_sum = 0;
        $total_discharge_sum = 0;
        $total_patient_test_entry_sum = 0;
        $total_return_sum = 0;
        $total_due_payment_sum = 0;
        $total_paid_sum = 0;
        foreach ($reception_users as $reception_user) {
            $user = getUserById($reception_user->user_id);
            $user_id = $reception_user->user_id;
            $total_ipd_each_user = 0;
            $total_opd_each_user = 0;
            $total_emergency_each_user = 0;
            $total_physiotherapy_each_user = 0;
            $total_patient_test_entry_each_user = 0;
            $total_discharge_each_user = 0;
            $total_return_each = 0;
            $total_due_payment_each = 0;
            $total_paid_each = 0;


            $this->db->select_sum('paid');
            $this->db->where('user_id', $user_id);
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('date >=', $from_date)
                    ->where('date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('date', $to_date);
            }
            $query = $this->db->get('ot_services');
            $result = $query->row();
            $total_paid = $result->paid ?? 0; // fallback to 0 if null
            $total_paid_sum += $total_paid;
            $total_paid_each += $result->paid ?? 0;


            //opd_patient

            $this->db->select_sum('opd_patient.payable');
            $this->db->where('opd_patient.user_id', $user_id);
            // Apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('opd_patient.entry_date >=', $from_date)
                    ->where('opd_patient.entry_date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('opd_patient.entry_date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('opd_patient.entry_date', $to_date);
            }
            $opd_patient_query = $this->db->get('opd_patient');
            $result = $opd_patient_query->row();

            // Handle the case where no data is found
            $total_paid = $result->payable ?? 0; // fallback to 0 if null
            $total_opd_each_user = $result->payable ?? 0;
            $total_opd_sum += $result->payable ?? 0;
            $total_paid_sum += $total_paid;
            $total_paid_each += $result->payable ?? 0;


            //ipd_patient
            $this->db->select_sum('ipd_patient.paid_amount');
            $this->db->where('ipd_patient.user_id', $user_id);
            // Apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('ipd_patient.date >=', $from_date)
                    ->where('ipd_patient.date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('ipd_patient.date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('ipd_patient.date', $to_date);
            }
            $ipd_patient_query = $this->db->get('ipd_patient');
            $result = $ipd_patient_query->row();
            // Handle the case where no data is found
            $total_paid = $result->paid_amount ?? 0; // fallback to 0 if null
            $total_ipd_each_user = $result->paid_amount ?? 0; // fallback to 0 if null
            $total_ipd_sum += $result->paid_amount ?? 0;
            $total_paid_sum += $total_paid;
            $total_paid_each += $result->paid_amount ?? 0;


            //emergency
            $this->db->select_sum('emergency.paid');
            $this->db->where('emergency.user_id', $user_id);
            // Apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('emergency.date >=', $from_date)
                    ->where('emergency.date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('emergency.date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('emergency.date', $to_date);
            }
            $emergency_query = $this->db->get('emergency');
            $result = $emergency_query->row();
            // Handle the case where no data is found
            $total_paid = $result->paid ?? 0; // fallback to 0 if null
            $total_emergency_user = $result->paid ?? 0;
            $total_emergency_sum += $result->paid ?? 0;
            $total_paid_sum += $total_paid;
            $total_paid_each += $result->paid ?? 0;


            //physiotherapy paid
            $this->db->select_sum('phygiotherapy.paid');
            $this->db->where('phygiotherapy.user_id', $user_id);
            // Apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('phygiotherapy.date >=', $from_date)
                    ->where('phygiotherapy.date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('phygiotherapy.date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('phygiotherapy.date', $to_date);
            }
            $phygiotherapy_query = $this->db->get('phygiotherapy');
            $result = $phygiotherapy_query->row();
            // Handle the case where no data is found
            $total_paid = $result->paid ?? 0; // fallback to 0 if null
            $total_physiotherapy_user =  $result->paid ?? 0; // fallback to 0 if null
            $total_physiotherapy_sum += $result->paid ?? 0;
            $total_paid_sum += $total_paid;
            $total_paid_each += $result->paid ?? 0;


            //patient_test_entry paid
            $this->db->select_sum('patient_test_entry.paid');
            $this->db->where('patient_test_entry.user_id', $user_id);
            // Apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('patient_test_entry.date >=', $from_date)
                    ->where('patient_test_entry.date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('patient_test_entry.date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('patient_test_entry.date', $to_date);
            }
            $patient_test_entry_query = $this->db->get('patient_test_entry');
            $result = $patient_test_entry_query->row();
            // Handle the case where no data is found
            $total_paid = $result->paid ?? 0; // fallback to 0 if null
            $total_patient_test_entry_each_user = $result->paid ?? 0; // fallback to 0 if null
            $total_patient_test_entry_sum += $result->paid ?? 0;
            $total_paid_sum += $total_paid;
            $total_paid_each += $result->paid ?? 0;


            //discharge paid
            $this->db->select_sum('discharge.paid');
            $this->db->where('discharge.user_id', $user_id);
            // Apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('discharge.discharge_date >=', $from_date)
                    ->where('discharge.discharge_date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('discharge.discharge_date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('discharge.discharge_date', $to_date);
            }
            $ipd_patient_query = $this->db->get('discharge');
            $result = $ipd_patient_query->row();
            // Handle the case where no data is found
            $total_paid = $result->paid ?? 0; // fallback to 0 if null
            $total_discharge_each_user =  $result->paid ?? 0; // fallback to 0 if null
            $total_discharge_sum += $result->paid ?? 0;
            $total_paid_sum += $total_paid;
            $total_paid_each += $result->paid ?? 0;


            //ot_services return
            $this->db->select_sum('ot_services.returnable_amount');
            $this->db->where('ot_services.user_id', $user_id);
            // Apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('ot_services.returnable_date >=', $from_date)
                    ->where('ot_services.returnable_date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('ot_services.returnable_date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('ot_services.returnable_date', $to_date);
            }
            $ot_services_query_return = $this->db->get('ot_services');
            $result_ot_return = $ot_services_query_return->row();
            // Handle the case where no data is found
            $total_return = $result_ot_return->returnable_amount ?? 0; // fallback to 0 if null
            $total_return_sum += $total_return;
            $total_return_each += $result_ot_return->returnable_amount ?? 0;



            //opd_patient return
            $this->db->select_sum('opd_patient.returnable_amount');
            $this->db->where('opd_patient.user_id', $user_id);
            // Apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('opd_patient.return_date >=', $from_date)
                    ->where('opd_patient.return_date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('opd_patient.return_date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('opd_patient.return_date', $to_date);
            }
            $opd_patient_query_return = $this->db->get('opd_patient');
            $result_opd_return = $opd_patient_query_return->row();
            // Handle the case where no data is found
            $total_return = $result_opd_return->returnable_amount ?? 0; // fallback to 0 if null
            $total_return_sum += $total_return;
            $total_return_each += $result_opd_return->returnable_amount ?? 0;



            //emergency return
            $this->db->select_sum('emergency.returnable_amount');
            $this->db->where('emergency.user_id', $user_id);
            // Apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('emergency.return_date >=', $from_date)
                    ->where('emergency.return_date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('emergency.return_date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('emergency.return_date', $to_date);
            }
            $emergency_query_return = $this->db->get('emergency');
            $result_emergency_return = $emergency_query_return->row();
            // Handle the case where no data is found
            $total_return = $result_emergency_return->returnable_amount ?? 0; // fallback to 0 if null
            $total_return_sum += $total_return;
            $total_return_each += $result_emergency_return->returnable_amount ?? 0;


            //physiotherapy return
            $this->db->select_sum('phygiotherapy.returnable_amount');
            $this->db->where('phygiotherapy.user_id', $user_id);
            // Apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('phygiotherapy.return_date >=', $from_date)
                    ->where('phygiotherapy.return_date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('phygiotherapy.return_date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('phygiotherapy.return_date', $to_date);
            }
            $phygiotherapy_query_return = $this->db->get('phygiotherapy');
            $result_physiotherapy_return = $phygiotherapy_query_return->row();
            // Handle the case where no data is found
            $total_return = $result_physiotherapy_return->returnable_amount ?? 0; // fallback to 0 if null
            $total_return_sum += $total_return;
            $total_return_each += $result_physiotherapy_return->returnable_amount ?? 0;


            //patient_test_entry return
            $this->db->select_sum('patient_test_entry.returnable_amount');
            $this->db->where('patient_test_entry.user_id', $user_id);
            // Apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('patient_test_entry.return_date >=', $from_date)
                    ->where('patient_test_entry.return_date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('patient_test_entry.return_date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('patient_test_entry.return_date', $to_date);
            }
            $patient_test_entry_query_return = $this->db->get('patient_test_entry');
            $result_test_return = $patient_test_entry_query_return->row();
            // Handle the case where no data is found
            $total_return = $result_test_return->returnable_amount ?? 0; // fallback to 0 if null
            $total_return_sum += $total_return;
            $total_return_each += $result_test_return->returnable_amount ?? 0;



            //ot_services due payment
            $this->db->select_sum('ot_services.due_payment');
            $this->db->where('ot_services.due_payment_user_id', $user_id);
            // Apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('ot_services.due_payment_date >=', $from_date)
                    ->where('ot_services.due_payment_date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('ot_services.due_payment_date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('ot_services.due_payment_date', $to_date);
            }
            $ot_services_query_return = $this->db->get('ot_services');
            $result_ot_services_due_payment = $ot_services_query_return->row();
            // Handle the case where no data is found
            $total_due_payment = $result_ot_services_due_payment->due_payment ?? 0; // fallback to 0 if null
            $total_due_payment_sum += $result_ot_services_due_payment->due_payment ?? 0; // fallback to 0 if null
            $total_due_payment_each += $result_ot_services_due_payment->due_payment ?? 0; // fallback to 0 if null


            //emergency due payment
            $this->db->select_sum('emergency.due_payment');
            $this->db->where('emergency.due_payment_user_id', $user_id);
            // Apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('emergency.due_payment_date >=', $from_date)
                    ->where('emergency.due_payment_date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('emergency.due_payment_date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('emergency.due_payment_date', $to_date);
            }
            $emergency_query_return = $this->db->get('emergency');
            $result_emergency_due_payment = $emergency_query_return->row();
            // Handle the case where no data is found
            $total_due_payment = $result_emergency_due_payment->due_payment ?? 0; // fallback to 0 if null
            $total_due_payment_sum += $result_emergency_due_payment->due_payment ?? 0;
            $total_due_payment_each += $result_emergency_due_payment->due_payment ?? 0;


            //physiotherapy due payment
            $this->db->select_sum('phygiotherapy.due_payment');
            $this->db->where('phygiotherapy.due_payment_user_id', $user_id);
            // Apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('phygiotherapy.due_payment_date >=', $from_date)
                    ->where('phygiotherapy.due_payment_date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('phygiotherapy.due_payment_date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('phygiotherapy.due_payment_date', $to_date);
            }
            $phygiotherapy_query_return = $this->db->get('phygiotherapy');
            $result_physitherapy_due_payment = $phygiotherapy_query_return->row();
            // Handle the case where no data is found
            $total_due_payment = $result_physitherapy_due_payment->due_payment ?? 0; // fallback to 0 if null
            $total_due_payment_sum += $result_physitherapy_due_payment->due_payment ?? 0;
            $total_due_payment_each += $result_physitherapy_due_payment->due_payment ?? 0;


            //test entry due payment
            $this->db->select_sum('test_collection.paid');
            $this->db->where('test_collection.user_id', $user_id);
            $this->db->where('test_collection.payment_type', 'from_due_collection');
            // Apply date filters
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('test_collection.date >=', $from_date)
                    ->where('test_collection.date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('test_collection.date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('test_collection.date', $to_date);
            }
            $test_collection_query_return = $this->db->get('test_collection');
            $result_test_entry_due_payment = $test_collection_query_return->row();
            // Handle the case where no data is found
            $total_due_payment = $result_test_entry_due_payment->paid ?? 0; // fallback to 0 if null
            $total_due_payment_sum += $result_test_entry_due_payment->paid ?? 0;
            $total_due_payment_each += $result_test_entry_due_payment->paid ?? 0;



        ?>
            <tr>
                <td><?php echo $serial++ ?></td>
                <td><?php echo $user->user_name ?></td>

                <td id="total_paid_<?php echo $user->user_id ?>">
                    <?php echo  number_format($total_discharge_each_user); ?>
                </td>
                <td id="total_paid_<?php echo $user->user_id ?>">
                    <?php echo  number_format($total_ipd_each_user); ?>
                </td>
                <td id="total_paid_<?php echo $user->user_id ?>">
                    <?php echo  number_format($total_opd_each_user); ?>
                </td>
                <td id="total_paid_<?php echo $user->user_id ?>">
                    <?php echo  number_format($total_emergency_user); ?>
                </td>
                <td id="total_paid_<?php echo $user->user_id ?>">
                    <?php echo  number_format($total_physiotherapy_user); ?>
                </td>
                <td id="total_paid_<?php echo $user->user_id ?>">
                    <?php echo  number_format($total_patient_test_entry_each_user); ?>
                </td>
                <td id="total_paid_<?php echo $user->user_id ?>">
                    <?php echo  number_format($total_due_payment_each); ?>
                </td>
                <td id="total_paid_<?php echo $user->user_id ?>">
                    <?php
                    //Total paid amount calculation
                    echo number_format($total_paid_each + $total_due_payment_each);
                    $grand_total_paid_sum += $total_paid_each;
                    ?>
                </td>

                <td id="total_return_<?php echo $user->user_id ?>">
                    <?php
                    echo  number_format($total_return_each);
                    $grand_total_return_sum += $total_return_each;
                    ?>
                </td>

                <td id="total_balance_<?php echo $user->user_id ?>">
                    <?php
                    echo  number_format((float)$total_paid_each + (float)$total_due_payment_each - (float)$total_return_each);
                    ?>
                </td>
            </tr>
        <?php
            $user_total_balance_array[$reception_user->user_id] = (float)$total_paid_each + (float)$total_due_payment_each - (float)$total_return_each;
        }
        ?>
        <tr style="background-color: yellowgreen;">
            <td></td>
            <td>Total</td>
            <td><?php echo  number_format($total_discharge_sum) ?></td>
            <td><?php echo  number_format($total_ipd_sum) ?></td>
            <td><?php echo  number_format($total_opd_sum) ?></td>
            <td><?php echo  number_format($total_emergency_sum) ?></td>
            <td><?php echo  number_format($total_physiotherapy_sum) ?></td>
            <td><?php echo  number_format($total_patient_test_entry_sum) ?></td>
            <td><?php echo  number_format($total_due_payment_sum) ?></td>
            <td><?php
                //Total paid amount calculation
                echo  number_format((float)$grand_total_paid_sum + (float)$total_due_payment_sum) ?></td>
            <td><?php echo  number_format($grand_total_return_sum) ?></td>
            <td><?php
                echo  number_format((float)$grand_total_paid_sum + (float)$total_due_payment_sum - (float)$grand_total_return_sum) ?>
            </td>
        </tr>
    </table>
    <?php
    //    echo '<pre>';
    //    print_r($user_total_balance_array);
    //    die;
    foreach ($reception_users as $reception_user) {

        if ($user_total_balance_array[$reception_user->user_id] == 0) {
            continue;
        }

    ?>

        <?php
        $user = getUserById($reception_user->user_id);
        $user_id = $reception_user->user_id;
        $this->load->view('common/report_header');
        ?>
        <p style="clear:left;text-align: center">My Sell Report. Date:<b><?php echo date('d-m-Y', strtotime($from_date)) . ' To ' . date('d-m-Y', strtotime($to_date)) ?></b> <br>
            <span style="text-align: center;font-weight:bold">User:<?php echo  $user->user_name ?></span>
        </p>
</div>
<div class="panel-body" style="width: 100%;">
    <div class="row">
        <div class="col-md-12">
            <table border="1" class="table table-bordered table-hover" style="width: 100%;margin-top:10px;color:black;border-collapse:collapse;">
                <tr style="background-color: #1A7DFF;color: white">
                    <td colspan="6" style="text-align: center">Department Wise Test</td>
                </tr>
                <tr>
                    <td>Department</td>
                    <td>Total Entry</td>
                    <td>Total Amount</td>
                    <td>Total Discount</td>
                    <td>Return</td>
                    <td>Total Amount</td>
                </tr>

                <?php
                $grand_test_total_entry = 0;
                $grand_test_total_price = 0;
                $grand_test_total_discount_each = 0;
                $grand_test_total_paid_each = 0;
                $grand_test_total_return_each_category = 0;

                $test_categories = $this->db->get('test_categories')->result();
                foreach ($test_categories as $test_category) {


                    $this->db->select('
SUM(total_price) as total_price,
SUM(total_return) as total_return,
SUM(discount_each) as total_discount_each,
SUM(paid_each) as total_paid_each,
COUNT(*) as total_rows
');
                    $this->db->from('patient_test_entry_details');

                    $this->db->where('test_category_id', $test_category->test_category_id); // Filter by category
                    if ($user_id != '') {
                        $this->db->where('patient_test_entry_details.user_id', $user_id);
                    }
                    if ($from_date != '' and $to_date != '') {
                        $this->db
                            ->where('patient_test_entry_details.date>=', $from_date)
                            ->where('patient_test_entry_details.date<=', $to_date);
                    } else if ($from_date != '' && $to_date == '') {
                        $this->db
                            ->where('patient_test_entry_details.date', $from_date);
                    } else if ($to_date != '' && $from_date == '') {
                        $this->db
                            ->where('patient_test_entry_details.date', $to_date);
                    }

                    $query = $this->db->get();
                    $row = $query->row();

                    $test_total_price = $row->total_price ?? 0;
                    $test_total_discount_each = $row->total_discount_each ?? 0;
                    $test_total_paid_each = $row->total_paid_each ?? 0;
                    $test_total_rows = $row->total_rows ?? 0;

                    $grand_test_total_entry += $test_total_rows;
                    $grand_test_total_price += $test_total_price;
                    $test_total_return_each_category = $row->total_return ?? 0;
                    $grand_test_total_discount_each += $test_total_discount_each;
                    $grand_test_total_paid_each += $test_total_paid_each;
                    $grand_test_total_return_each_category += $test_total_return_each_category;
                ?>
                    <tr>
                        <td><?php echo $test_category->test_category_name ?></td>
                        <td><?php echo number_format($test_total_rows) ?></td>
                        <td><?php echo number_format($test_total_price) ?></td>
                        <td><?php echo number_format($test_total_discount_each) ?></td>
                        <td><?php echo number_format($test_total_return_each_category) ?></td>
                        <td><?php echo number_format($test_total_paid_each - $test_total_return_each_category) ?></td>
                    </tr>
                <?php } ?>

                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong><?php echo number_format($grand_test_total_entry) ?></strong></td>
                    <td><strong><?php echo number_format($grand_test_total_price) ?></strong></td>
                    <td><strong><?php echo number_format($grand_test_total_discount_each) ?></strong></td>
                    <td><strong><?php echo number_format($grand_test_total_return_each_category) ?></strong></td>
                    <td><strong><?php echo number_format($grand_test_total_paid_each - $grand_test_total_return_each_category) ?></strong></td>
                </tr>
            </table>
            <?php
            $total_due_payment = 0;
            $total_returnable_amount = 0;
            $total_paid = 0;
            $grand_total = 0;


            //OT service table

            $this->db->where('user_id', $user_id);
            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('date>=', $from_date)
                    ->where('date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('date', $to_date);
            }
            $ot_services = $this->db->get('ot_services')->result();

            if (!empty($ot_services)) {
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="7">Income from OT</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:20%">Patient Name</td>
                        <td style="width:20%">Invoice No</td>
                        <td style="width:10%">Total</td>
                        <td style="width:10%">Discount</td>
                        <td style="width:10%">Paid</td>
                        <td style="width:10%">Due</td>
                        <td style="width:10%">Date</td>
                    </tr>
                    <?php
                    $k = 1;
                    $grand_ot_paid = 0;

                    foreach ($ot_services as $value) {
                    ?>
                        <tr>
                            <td><?php echo $k++; ?></td>
                            <td><?php echo $value->patient_name ?? "" ?></td>
                            <td><?php echo $value->ot_service_unique_id ?? "" ?></td>
                            <td><?php echo $value->price ?? "" ?></td>
                            <td><?php echo $value->total_discount ?? "" ?></td>
                            <td><?php echo $value->paid ?? "";
                                $grand_ot_paid += $value->paid;
                                $grand_total += $value->paid;
                                ?>
                            </td>
                            <td><?php echo $value->due ?? ""; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="5" style="text-align:right">Total</td>
                        <td> <?php echo number_format($grand_ot_paid) ?></td>
                        <td></td>

                    </tr>
                </table>
            <?php
            }
            //Calculate OT Service Return

            $this->db->where('return_user_id', $user_id);
            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('returnable_date>=', $from_date)
                    ->where('returnable_date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('returnable_date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('returnable_date', $to_date);
            }
            $ot_services_return = $this->db->get('ot_services')->result();
            $ot_services_returnable_amount = 0;
            if (!empty($ot_services_return)) {
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="5">OT Return</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:20%">Invoice No</td>
                        <td style="width:10%">Return Amount</td>
                        <td style="width:10%">Date</td>
                    </tr>
                    <?php
                    $serial = 1;
                    foreach ($ot_services_return as $value) {

                    ?>
                        <tr>
                            <td><?php echo $serial++; ?></td>

                            <td><?php echo $value->ot_service_unique_id ?? "" ?></td>
                            <td><?php echo $value->returnable_amount;
                                $ot_services_returnable_amount += $value->returnable_amount;
                                $total_returnable_amount += $value->returnable_amount;
                                ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->return_date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="2" style="text-align: right;"><strong>Total</strong></td>
                        <td>
                            <?php echo number_format($ot_services_returnable_amount); ?>
                        </td>
                    </tr>

                </table>
            <?php
            }


            //Calculate OT Service Due Collection

            $this->db->where('due_payment_user_id', $user_id);

            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('due_payment_date>=', $from_date)
                    ->where('due_payment_date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('due_payment_date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('due_payment_date', $to_date);
            }
            $ot_services_due_payment = $this->db->get('ot_services')->result();
            $total_ot_services_due_payment = 0;
            if (!empty($ot_services_due_payment)) {
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="5">OT Due Collection</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:20%">Invoice No</td>
                        <td style="width:10%">Due Payment</td>
                        <td style="width:10%">Date</td>
                    </tr>
                    <?php
                    $serial = 1;
                    foreach ($ot_services_due_payment as $value) {

                    ?>
                        <tr>
                            <td><?php echo $serial++; ?></td>

                            <td><?php echo $value->ot_service_unique_id ?? "" ?></td>
                            <td><?php echo $value->due_payment;
                                $total_ot_services_due_payment += $value->due_payment;
                                $total_due_payment += $value->due_payment;
                                ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->due_payment_date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="2" style="text-align: right;">Total</td>
                        <td>
                            <?php echo number_format($total_ot_services_due_payment); ?>
                        </td>
                    </tr>

                </table>
            <?php
            }


            $this->db->where('user_id', $user_id);

            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('entry_date>=', $from_date)
                    ->where('entry_date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('entry_date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('entry_date', $to_date);
            }
            $opd_patient_sells = $this->db->get('opd_patient')->result();
            // $grand_total = 0;
            if (!empty($opd_patient_sells)) {
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="7">Income from OPD Patient</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:20%">Patient Name</td>
                        <td style="width:20%">Invoice No</td>
                        <td style="width:10%">Total</td>
                        <td style="width:10%">Discount</td>
                        <td style="width:10%">Paid</td>

                        <td style="width:10%">Date</td>
                    </tr>
                    <?php
                    $k = 1;
                    $grand_visiting_fee = 0;

                    foreach ($opd_patient_sells as $value) {
                    ?>
                        <tr>
                            <td><?php echo $k++; ?></td>
                            <td><?php echo $value->opd_patient_name ?? "" ?></td>
                            <td><?php echo $value->opd_patient_unique_id ?? "" ?></td>
                            <td><?php echo $value->visiting_fee ?? "" ?></td>
                            <td><?php echo $value->discount ?? "" ?></td>
                            <td><?php echo $value->payable ?? "";
                                $grand_visiting_fee += $value->payable;
                                $grand_total += $value->/* The above code is a comment block in PHP.
                                Comments in PHP are used to provide
                                explanations or documentation within the
                                code and are not executed by the PHP
                                interpreter. The text "payable" and " */payable;
                                ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->entry_date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="5" style="text-align:right">Total</td>
                        <td> <?php echo number_format($grand_visiting_fee) ?></td>
                        <td></td>

                    </tr>
                </table>
            <?php
            }

            //Calculate OPD Return

            $this->db->where('return_user_id', $user_id);

            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('return_date>=', $from_date)
                    ->where('return_date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('return_date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('return_date', $to_date);
            }
            $opd_patient_return = $this->db->get('opd_patient')->result();
            $opd_patient_returnable_amount = 0;
            if (!empty($opd_patient_return)) {
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="5">OPD Return</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:20%">Invoice No</td>
                        <td style="width:10%">Return Amount</td>
                        <td style="width:10%">Date</td>
                    </tr>
                    <?php
                    $serial = 1;
                    foreach ($opd_patient_return as $value) {

                    ?>
                        <tr>
                            <td><?php echo $serial++; ?></td>

                            <td><?php echo $value->opd_patient_unique_id ?? "" ?></td>
                            <td><?php echo $value->returnable_amount;
                                $opd_patient_returnable_amount += $value->returnable_amount;
                                $total_returnable_amount += $value->returnable_amount;
                                ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->return_date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="2" style="text-align: right;">Total</td>
                        <td>
                            <?php echo number_format($opd_patient_returnable_amount); ?>
                        </td>
                    </tr>

                </table>
            <?php
            }

            $this->db->where('user_id', $user_id);

            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('date>=', $from_date)
                    ->where('date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('date', $to_date);
            }
            $ipd_patient_sells = $this->db->get('ipd_patient')->result();

            if (!empty($ipd_patient_sells)) {
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="8">Income from IPD Patient</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:20%">Patient Name</td>
                        <td style="width:20%">Invoice No</td>
                        <!-- <td style="width:10%">Total</td> -->
                        <!-- <td style="width:10%">Discount</td> -->
                        <td style="width:10%">Paid</td>
                        <!-- <td style="width:10%">Due</td> -->
                        <td style="width:10%">Date</td>
                    </tr>
                    <?php
                    $k = 1;
                    $grand_paid_amount = 0;
                    foreach ($ipd_patient_sells as $value) {
                    ?>
                        <tr>
                            <td><?php echo $k++; ?></td>
                            <td><?php echo $value->patient_name ?? "" ?></td>
                            <td><?php echo $value->patient_unique_id ?? "" ?></td>
                            <td><?php echo $value->paid_amount;
                                $grand_paid_amount += $value->paid_amount;
                                $grand_total += $value->paid_amount;
                                ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="3" style="text-align:right">Total</td>
                        <td> <?php echo number_format($grand_paid_amount) ?></td>
                        <td></td>
                    </tr>
                </table>
            <?php
            }


            $this->db->where('user_id', $user_id);

            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('date>=', $from_date)
                    ->where('date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('date', $to_date);
            }
            $emergency_sells = $this->db->get('emergency')->result();
            if (!empty($emergency_sells)) {
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="9">Income from Emergency</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:15%">Patient Name</td>
                        <td style="width:15%">Invoice No</td>
                        <td style="width:10%">Total</td>
                        <td style="width:10%">Discount</td>
                        <td style="width:10%">Net Total</td>
                        <td style="width:10%">Paid</td>
                        <td style="width:10%">Due</td>
                        <td style="width:10%">Date</td>
                    </tr>
                    <?php
                    $k = 1;
                    $grand_paid_amount = 0;
                    foreach ($emergency_sells as $value) {
                    ?>
                        <tr>
                            <td><?php echo $k++; ?></td>
                            <td><?php echo $value->name ?? "" ?></td>
                            <td><?php echo $value->emergency_invoice_no ?? "" ?></td>
                            <td><?php echo $value->total ?></td>
                            <td><?php echo $value->discount ?></td>
                            <td><?php echo $value->nettotal ?></td>
                            <td><?php echo $value->paid;
                                $grand_paid_amount += $value->paid;
                                $grand_total += $value->paid;
                                ?></td>
                            <td><?php echo $value->due ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="6" style="text-align:right">Total</td>
                        <td> <?php echo number_format($grand_paid_amount) ?></td>
                        <td></td>
                    </tr>
                </table>
            <?php
            }
            //Calculate Emergency Return

            $this->db->where('return_user_id', $user_id);

            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('return_date>=', $from_date)
                    ->where('return_date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('return_date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('return_date', $to_date);
            }
            $emergency_returns = $this->db->get('emergency')->result();
            $emergency_returnable_amount = 0;
            if (!empty($emergency_returns)) {
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="5">Emergency Return</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:20%">Invoice No</td>
                        <td style="width:10%">Return Amount</td>
                        <td style="width:10%">Date</td>
                    </tr>
                    <?php
                    $serial = 1;
                    foreach ($emergency_returns as $value) {
                        $grand_total -= $value->paid;
                    ?>
                        <tr>
                            <td><?php echo $serial++; ?></td>

                            <td><?php echo $value->emergency_invoice_no ?? "" ?></td>
                            <td><?php echo $value->returnable_amount;
                                $emergency_returnable_amount += $value->returnable_amount;
                                $total_returnable_amount += $value->returnable_amount;
                                ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->return_date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="2" style="text-align: right;">Total</td>
                        <td>
                            <?php echo number_format($emergency_returnable_amount); ?>
                        </td>
                    </tr>
                </table>
            <?php
            }
            //Calculate Emergency Due Collection

            $this->db->where('due_payment_user_id', $user_id);

            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('due_payment_date>=', $from_date)
                    ->where('due_payment_date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('due_payment_date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('due_payment_date', $to_date);
            }
            $emergency_due_payment = $this->db->get('emergency')->result();
            $total_emergency_due_payment = 0;
            if (!empty($emergency_due_payment)) {
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="5">Emergency Due Collection</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:20%">Invoice No</td>
                        <td style="width:10%">Due Payment</td>
                        <td style="width:10%">Date</td>
                    </tr>
                    <?php
                    $serial = 1;
                    foreach ($emergency_due_payment as $value) {

                    ?>
                        <tr>
                            <td><?php echo $serial++; ?></td>

                            <td><?php echo $value->emergency_invoice_no ?? "" ?></td>
                            <td><?php echo $value->due_payment;
                                $total_emergency_due_payment += $value->due_payment;
                                $total_due_payment += $value->due_payment;
                                ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->due_payment_date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="2" style="text-align: right;">Total</td>
                        <td>
                            <?php echo number_format($total_emergency_due_payment); ?>
                        </td>
                    </tr>

                </table>
            <?php
            }

            $this->db->where('user_id', $user_id);

            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('date>=', $from_date)
                    ->where('date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('date', $to_date);
            }
            $phygiotherapy_sells = $this->db->get('phygiotherapy')->result();

            if (!empty($phygiotherapy_sells)) {
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="9">Income from Physiotherapy</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:15%">Patient Name</td>
                        <td style="width:15%">Invoice No</td>
                        <td style="width:10%">Total</td>
                        <td style="width:10%">Discount</td>
                        <td style="width:10%">Net Total</td>
                        <td style="width:10%">Paid</td>
                        <td style="width:10%">Due</td>
                        <td style="width:10%">Date</td>
                    </tr>
                    <?php
                    $k = 1;
                    $grand_paid_amount = 0;
                    foreach ($phygiotherapy_sells as $value) {

                    ?>
                        <tr>
                            <td><?php echo $k++; ?></td>
                            <td><?php echo $value->name ?? "" ?></td>
                            <td><?php echo $value->phygiotherapy_invoice_no ?? "" ?></td>
                            <td><?php echo $value->total ?></td>
                            <td><?php echo $value->discount ?></td>
                            <td><?php echo $value->nettotal ?></td>
                            <td><?php echo $value->paid;
                                $grand_paid_amount += $value->paid;
                                $grand_total += $value->paid;
                                ?></td>
                            <td><?php echo $value->due ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="6" style="text-align:right">Total</td>
                        <td> <?php echo number_format($grand_paid_amount) ?></td>
                        <td></td>
                    </tr>
                </table>
            <?php
            }
            //Calculate Physiotherapy Return

            $this->db->where('return_user_id', $user_id);

            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('return_date>=', $from_date)
                    ->where('return_date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('return_date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('return_date', $to_date);
            }
            $phygiotherapy_returns = $this->db->get('phygiotherapy')->result();
            $phygiotherapy_returnable_amount = 0;
            if (!empty($phygiotherapy_returns)) {
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="5">Physiotherapy Return</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:20%">Invoice No</td>
                        <td style="width:10%">Return Amount</td>
                        <td style="width:10%">Date</td>
                    </tr>
                    <?php
                    $serial = 1;
                    foreach ($phygiotherapy_returns as $value) {

                    ?>
                        <tr>
                            <td><?php echo $serial++; ?></td>

                            <td><?php echo $value->phygiotherapy_invoice_no ?? "" ?></td>
                            <td><?php echo $value->returnable_amount;
                                $phygiotherapy_returnable_amount += $value->returnable_amount;
                                $total_returnable_amount += $value->returnable_amount;
                                ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->return_date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="2" style="text-align: right;">Total</td>
                        <td>
                            <?php echo number_format($phygiotherapy_returnable_amount); ?>
                        </td>
                    </tr>

                </table>
            <?php
            }
            //Calculate Physiotherapy Due Collection

            $this->db->where('due_payment_user_id', $user_id);

            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('due_payment_date>=', $from_date)
                    ->where('due_payment_date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('due_payment_date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('due_payment_date', $to_date);
            }
            $phygiotherapy_due_payment = $this->db->get('phygiotherapy')->result();
            $total_phygiotherapy_due_payment = 0;
            if (!empty($phygiotherapy_due_payment)) {
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="5">Physiotherapy Due Collection</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:20%">Invoice No</td>
                        <td style="width:10%">Due Payment</td>
                        <td style="width:10%">Date</td>
                    </tr>
                    <?php
                    $serial = 1;
                    foreach ($phygiotherapy_due_payment as $value) {

                    ?>
                        <tr>
                            <td><?php echo $serial++; ?></td>

                            <td><?php echo $value->phygiotherapy_invoice_no ?? "" ?></td>
                            <td><?php echo $value->due_payment;
                                $total_phygiotherapy_due_payment += $value->due_payment;
                                $total_due_payment += $value->due_payment;
                                ?></td>
                            <td><?php echo $value->due_payment_date ? date('d-m-Y', strtotime($value->due_payment_date)) : '' ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="2" style="text-align: right;">Total</td>
                        <td>
                            <?php echo number_format($total_phygiotherapy_due_payment); ?>
                        </td>
                    </tr>
                </table>
            <?php
            }

            $this->db
                ->select('test_collection.*, patient_test_entry.patient_name,patient_test_entry.invoice_no')
                ->from('test_collection')
                ->join('patient_test_entry', 'test_collection.patient_test_entry_id = patient_test_entry.patient_test_entry_id', 'left') // Adjust the ON condition
                ->where('test_collection.payment_type', 'from_direct_sales');
            if ($user_id != '') {
                $this->db->where('test_collection.user_id', $user_id);
            }
            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('test_collection.date>=', $from_date)
                    ->where('test_collection.date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('test_collection.date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('test_collection.date', $to_date);
            }
            $test_collection_sells = $this->db->get()->result();
            $total_test_due = 0;
            $total_test_net_total = 0;

            if (!empty($test_collection_sells)) {
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="9">Income from Test</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:15%">Patient Name</td>
                        <td style="width:15%">Invoice No</td>
                        <td style="width:10%">Total</td>
                        <td style="width:10%">Discount</td>
                        <td style="width:10%">Net Total</td>
                        <td style="width:10%">Paid</td>
                        <td style="width:10%">Due</td>
                        <td style="width:10%">Date</td>
                    </tr>
                    <?php
                    $k = 1;
                    $grand_paid_amount = 0;
                    foreach ($test_collection_sells as $value) {
                    ?>
                        <tr>
                            <td><?php echo $k++; ?></td>
                            <td><?php echo $value->patient_name ?? "" ?></td>
                            <td><?php echo $value->invoice_no ?? "" ?></td>
                            <td><?php echo $value->sub_total ?></td>
                            <td><?php echo $value->discount ?></td>
                            <td><?php echo $value->net_total;
                                $total_test_net_total += $value->net_total; ?></td>
                            <td><?php echo $value->paid;
                                $grand_paid_amount += $value->paid;
                                $grand_total += $value->paid;
                                ?></td>
                            <td><?php echo $value->due;
                                $total_test_due += (float)$value->due; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="5" style="text-align:right">Total</td>
                        <td> <?php echo number_format($total_test_net_total) ?></td>
                        <td> <?php echo number_format($grand_paid_amount) ?></td>
                        <td><?php echo number_format($total_test_due) ?></td>
                    </tr>
                </table>
            <?php
            }


            $this->db
                ->select('test_collection.*, patient_test_entry.patient_name,patient_test_entry.invoice_no')
                ->from('test_collection')
                ->join('patient_test_entry', 'test_collection.patient_test_entry_id = patient_test_entry.patient_test_entry_id', 'left') // Adjust the ON condition
                ->where('test_collection.payment_type', 'from_due_collection');

            $this->db->where('test_collection.user_id', $user_id);

            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('test_collection.date>=', $from_date)
                    ->where('test_collection.date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('test_collection.date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('test_collection.date', $to_date);
            }

            $test_collection_sells = $this->db->get()->result();

            if (!empty($test_collection_sells)) {
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="5">Income from Test Due Collection</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:25%">Patient Name</td>
                        <td style="width:25%">Invoice No</td>
                        <td style="width:20%">Total</td>
                        <td style="width:20%">Date</td>
                    </tr>
                    <?php
                    $k = 1;
                    $grand_paid_amount = 0;
                    // $grand_total = 0; // Ensure grand_total is initialized

                    foreach ($test_collection_sells as $value) {
                        $patient_name = $value->patient_name ?? "";
                        $invoice_no = $value->invoice_no ?? "";
                        $paid = $value->paid ?? 0;
                        $date = $value->date ?? "";
                    ?>
                        <tr>
                            <td><?php echo $k++; ?></td>
                            <td><?php echo htmlspecialchars($patient_name); ?></td>
                            <td><?php echo htmlspecialchars($invoice_no); ?></td>
                            <td><?php echo number_format($paid, 2);
                                $grand_paid_amount += $paid;
                                $total_due_payment += $paid;
                                ?></td>
                            <td><?php echo date('d-m-Y', strtotime($date)); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="3" style="text-align:right">Total</td>
                        <td><?php echo number_format($grand_paid_amount, 2); ?></td>
                        <td></td>
                    </tr>
                </table>
            <?php
            }

            //Calculate Test Entry Return

            $this->db->where('return_user_id', $user_id);

            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('return_date>=', $from_date)
                    ->where('return_date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('return_date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('return_date', $to_date);
            }
            $patient_test_entry = $this->db->get('patient_test_entry')->result();
            $patient_test_entry_returnable_amount = 0;
            if (!empty($patient_test_entry)) {
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="5">Test Return</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:20%">Invoice No</td>
                        <td style="width:10%">Return Amount</td>
                        <td style="width:10%">Date</td>
                    </tr>
                    <?php
                    $serial = 1;
                    foreach ($patient_test_entry as $value) {

                    ?>
                        <tr>
                            <td><?php echo $serial++; ?></td>

                            <td><?php echo $value->invoice_no ?? "" ?></td>
                            <td><?php echo $value->returnable_amount;
                                $patient_test_entry_returnable_amount += $value->returnable_amount;
                                $total_returnable_amount += $value->returnable_amount;
                                ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->return_date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="2" style="text-align: right;">Total</td>
                        <td>
                            <?php echo number_format($patient_test_entry_returnable_amount) ?>
                        </td>
                    </tr>
                </table>
            <?php
            }



            $this->db->where('user_id', $user_id);

            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('date>=', $from_date)
                    ->where('date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('date', $to_date);
            }
            $ipd_patient_sells = $this->db->get('ipd_patient')->result();

            if (!empty($ipd_patient_sells)) {
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="8">Income from IPD Patient</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:20%">Patient Name</td>
                        <td style="width:20%">Invoice No</td>
                        <!-- <td style="width:10%">Total</td> -->
                        <!-- <td style="width:10%">Discount</td> -->
                        <td style="width:10%">Paid</td>
                        <!-- <td style="width:10%">Due</td> -->
                        <td style="width:10%">Date</td>
                    </tr>
                    <?php
                    $k = 1;
                    $grand_paid_amount = 0;
                    foreach ($ipd_patient_sells as $value) {
                    ?>
                        <tr>
                            <td><?php echo $k++; ?></td>
                            <td><?php echo $value->patient_name ?? "" ?></td>
                            <td><?php echo $value->patient_unique_id ?? "" ?></td>
                            <td><?php echo $value->paid_amount;
                                $grand_paid_amount += $value->paid_amount;
                                $grand_total += $value->paid_amount;
                                ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="3" style="text-align:right">Total</td>
                        <td> <?php echo number_format($grand_paid_amount) ?></td>
                        <td></td>
                    </tr>
                </table>
            <?php
            }
            ?>

            <?php

            $this->db
                ->select('discharge.*, ipd_patient.patient_name, ipd_patient.patient_unique_id')
                ->from('discharge')
                ->join('ipd_patient', 'discharge.ipd_patient_id = ipd_patient.ipd_patient_id', 'left');

            $this->db->where('discharge.user_id', $user_id);

            if ($from_date != '' and $to_date != '') {
                $this->db
                    ->where('discharge.discharge_date>=', $from_date)
                    ->where('discharge.discharge_date<=', $to_date);
            } else if ($from_date != '' && $to_date == '') {
                $this->db
                    ->where('discharge.discharge_date', $from_date);
            } else if ($to_date != '' && $from_date == '') {
                $this->db
                    ->where('discharge.discharge_date', $to_date);
            }
            $discharge_sells = $this->db->get()->result();

            if (!empty($discharge_sells)) { // Check if $discharge_sells is not empty
            ?>
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header">
                        <td colspan="9">Income from Discharged</td>
                    </tr>
                    <tr>
                        <td style="width:10%">Sl</td>
                        <td style="width:15%">Patient Name</td>
                        <td style="width:15%">Invoice No</td>
                        <td style="width:10%">Total</td>
                        <td style="width:10%">Discount</td>
                        <td style="width:10%">Net Total</td>
                        <td style="width:10%">Paid</td>
                        <td style="width:10%">Due</td>
                        <td style="width:10%">Date</td>
                    </tr>
                    <?php
                    $k = 1;
                    $grand_paid_amount = 0;

                    foreach ($discharge_sells as $value) {
                    ?>
                        <tr>
                            <td><?php echo $k++; ?></td>
                            <td><?php echo $value->patient_name; ?></td>
                            <td><?php echo $value->patient_unique_id; ?></td>
                            <td><?php echo $value->total_bill; ?></td>
                            <td><?php echo (float)$value->director_discount + (float)$value->special_discount; ?></td>
                            <td><?php echo $value->net_payable; ?></td>
                            <td>
                                <?php
                                // Ensure $value->paid is set and is numeric
                                $paidAmount = isset($value->paid) && is_numeric($value->paid) ? (float)$value->paid : 0.0;

                                // Display the formatted paid amount
                                echo number_format($paidAmount, 2);

                                // Update the grand totals safely
                                $grand_paid_amount += $paidAmount;
                                $grand_total += $paidAmount;
                                ?>
                            </td>
                            <td><?php echo $value->due; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->discharge_date)); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="6" style="text-align:right">Total</td>
                        <td><?php echo number_format($grand_paid_amount, 2); ?></td>
                        <td></td>
                    </tr>
                </table>
            <?php
            }
            ?>
            <table border="1" style="border-collapse:collapse;margin:0 auto;color:black;">
                <tr style="background-color: #1A7DFF;color: white;font-weight: bold;text-align: center;" id="report_block_header" style="background-color:#006ADC;color:black">
                    <td>User</td>
                    <td>Total Paid</td>
                    <td>Total Return</td>
                    <td>Total Due Collection</td>
                    <td>Balance</td>
                </tr>
                <tr>
                    <td><?php echo $user->user_name ?></td>
                    <td><?php echo number_format($grand_total) ?></td>
                    <td><?php echo number_format($total_returnable_amount) ?></td>
                    <td><?php echo number_format($total_due_payment) ?></td>
                    <td><?php echo number_format((float)$grand_total + (float)$total_due_payment - (float)$total_returnable_amount) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?php
    }

?>