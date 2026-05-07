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

        #report_block_header {
            background-color: yellow;
            color: black;
        }

        .container_user {
            display: none;
        }
    }
</style>
<script type="text/javascript">
    function load_user_based_sell_report_for_user() {

        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        $('#loadingSpinner').show();
        $.ajax({
            url: "<?php echo base_url('ReportController/load_user_based_sell_report_for_user'); ?>",
            method: "POST",
            data: {
                from_date: from_date,
                to_date: to_date,
            },
            dataType: "json",
            success: function(response) {
                $('#loadingSpinner').hide();
                if (response.status === 'success') {
                    $('#report_container').html(response.data);
                } else if (response.status === 'error') {
                    $('#report_container').html(response.data);
                }
            },
            error: function(xhr, status, error) {

            }
        });
    }

    function load_user_based_sell_report_for_admin() {
        var user_id = document.getElementById('user_id').value;
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;

        // Check if user_id is empty
        if (user_id == 'All') { // For all users
            $.ajax({
                url: "<?php echo base_url('ReportController/load_all_user_based_sell_report_for_admin'); ?>",
                method: "POST",
                data: {
                    user_id: user_id,
                    from_date: from_date,
                    to_date: to_date,
                },
                dataType: "json",
                success: function(response) {
                    $('#loadingSpinner').hide();
                    if (response.status === 'success') {
                        $('#report_container').html(response.data);
                    } else if (response.status === 'error') {
                        $('#report_container').html(response.data);
                    }
                },
                error: function(xhr, status, error) {
                    $('#loadingSpinner').hide();
                    $('#report_container').html('<p class="text-danger">Something went wrong. Please try again.</p>');
                }
            });
        } else {
            $.ajax({
                url: "<?php echo base_url('ReportController/load_user_based_sell_report_for_admin'); ?>",
                method: "POST",
                data: {
                    user_id: user_id,
                    from_date: from_date,
                    to_date: to_date,
                },
                dataType: "json",
                success: function(response) {
                    $('#loadingSpinner').hide();
                    if (response.status === 'success') {
                        $('#report_container').html(response.data);
                    } else if (response.status === 'error') {
                        $('#report_container').html(response.data);
                    }
                },
                error: function(xhr, status, error) {
                    $('#loadingSpinner').hide();
                    $('#report_container').html('<p class="text-danger">Something went wrong. Please try again.</p>');
                }
            });
        }

        $('#loadingSpinner').show();
    }
    $(document).ready(function() {
        $('#user_id').select2();
    });
</script>
<div class="row">
    <div class="col-md-12">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
    </div>
</div>
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;margin-top:40px;color:black;height: 20cm ">
    <?php
    $permissions = $this->session->userdata('permissions');
    $user_id = $this->session->userdata('user_id');
    $user_type_name = $this->session->userdata('user_type');
    $user = getUserById($this->session->userdata('user_id'));
    $total_due_payment = 0;
    $total_returnable_amount = 0;
    $today = date('Y-m-d');
    ?>
    <div class="container_user">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <table class="table">
                        <tr>
                            <?php

                            if (in_array('all_user_today_sell_report', $permissions)) {
                                ?>
                                <td>User</td>
                            <?php
                            }
                            ?>
                            <td>From Date</td>
                            <td>To Date</td>
                        </tr>
                        <tr>
                            <?php

                            if (in_array('all_user_today_sell_report', $permissions)) {
                            ?>
                                <td>
                                    <select class="form-control" id="user_id" name="user_id">

                                        <option value="All">All Users</option>
                                        <?php
                                        $users = $this->db->select('*')->get('user')->result();
                                        foreach ($users as $value) {
                                        ?>
                                            <option value="<?php echo $value->user_id; ?>"><?php echo $value->user_name; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            <?php
                            }
                            ?>
                            <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>

                            <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>
                            <?php

                            if (in_array('all_user_today_sell_report', $permissions)) {
                            ?>
                                <td><input type="submit" class="btn btn-primary " onclick="load_user_based_sell_report_for_admin()" value="Search"></td>
                            <?php
                            } else {
                            ?>
                                <td><input type="submit" class="btn btn-primary " onclick="load_user_based_sell_report_for_user()" value="Search"></td>
                            <?php }
                            ?>
                        </tr>
                    </table>

                </div>
            </div>
            <div class="col-md-4">
                <img
                    src="<?php echo base_url(); ?>images/ajax-loader.gif"
                    id="loadingSpinner"
                    style="
        display: none; 
        position: fixed; 
        top: 50%; 
        left: 50%; 
        transform: translate(-50%, -50%); 
        z-index: 9999;"
                    alt="Loading...">

            </div>
        </div>
    </div>
    <div style="clear: left;" class="panel panel-primary">
        <div id="report_container">
            <div class="panel-heading" style="font-weight: bold;text-align: center;">
                <?php
                $this->load->view('common/report_header');
                ?>
                <p style="clear:left;text-align: center">Today My Sell Report. Date:<b><?php echo date('d-m-Y') ?></b> <br>
                    <span style="text-align: center;font-weight:bold">User:<?php echo  $user->user_name ?></span>
                </p>
            </div>
            <div class="panel-body" style="width: 100%;">
                <div class="row">
                    <div class="col-md-12">
                        <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                            <tr style="background-color:yellowgreen;color: black;font-weight: bold;" id="report_block_header">
                                <td>#</td>
                                <td>Name</td>
                                <td>Total Paid</td>
                                <td>Total Return</td>
                                <td>Total Due Collection</td>
                                <td>Balance</td>
                            </tr>
                            <?php


                            $serial = 1;
                            $grand_total_paid_sum = 0;
                            $grand_total_return_sum = 0;
                            $grand_total_due_payment_sum = 0;


                            $total_paid_sum = 0;
                            $total_return_sum = 0;
                            $total_due_payment_sum = 0;

                            $this->db->select_sum('paid');
                            $this->db->where('user_id', $user_id);
                            $this->db->where('date', $today);
                            $query = $this->db->get('ot_services');
                            $result = $query->row();
                            $total_paid = $result->paid ?? 0; // fallback to 0 if null
                            $total_paid_sum += $total_paid;
                            $grand_total_paid_sum += $total_paid_sum;

                            //opd_patient

                            $this->db->select_sum('opd_patient.payable');
                            $this->db->where('opd_patient.user_id', $user_id);
                            $this->db->where('opd_patient.entry_date', $today);
                            $opd_patient_query = $this->db->get('opd_patient');
                            $result = $opd_patient_query->row();

                            // Handle the case where no data is found
                            $total_paid = $result->payable ?? 0; // fallback to 0 if null
                            $total_paid_sum += $total_paid;
                            $grand_total_paid_sum += $total_paid_sum;

                            //ipd_patient
                            $this->db->select_sum('ipd_patient.paid_amount');
                            $this->db->where('ipd_patient.user_id', $user_id);
                            $this->db->where('ipd_patient.date', $today);
                            $ipd_patient_query = $this->db->get('ipd_patient');
                            $result = $ipd_patient_query->row();
                            // Handle the case where no data is found
                            $total_paid = $result->paid_amount ?? 0; // fallback to 0 if null
                            $total_paid_sum += $total_paid;

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
                            $total_paid_sum += $total_paid;
                            $grand_total_paid_sum += $total_paid_sum;

                            //phygiotherapy
                            $this->db->select_sum('phygiotherapy.paid');
                            $this->db->where('phygiotherapy.user_id', $user_id);
                            $this->db->where('phygiotherapy.date', $today);

                            $phygiotherapy_query = $this->db->get('phygiotherapy');
                            $result = $phygiotherapy_query->row();
                            // Handle the case where no data is found
                            $total_paid = $result->paid ?? 0; // fallback to 0 if null
                            $total_paid_sum += $total_paid;
                            $grand_total_paid_sum += $total_paid_sum;

                            //patient_test_entry
                            $this->db->select_sum('patient_test_entry.paid');
                            $this->db->where('patient_test_entry.user_id', $user_id);
                            $this->db->where('patient_test_entry.date', $today);

                            $patient_test_entry_query = $this->db->get('patient_test_entry');
                            $result = $patient_test_entry_query->row();
                            // Handle the case where no data is found
                            $total_paid = $result->paid ?? 0; // fallback to 0 if null
                            $total_paid_sum += $total_paid;
                            $grand_total_paid_sum += $total_paid_sum;

                            //patient_test_entry
                            $this->db->select_sum('ipd_patient.paid_amount');
                            $this->db->where('ipd_patient.user_id', $user_id);
                            $this->db->where('ipd_patient.date', $today);

                            $ipd_patient_query = $this->db->get('ipd_patient');
                            $result = $ipd_patient_query->row();
                            // Handle the case where no data is found
                            $total_paid = $result->paid_amount ?? 0; // fallback to 0 if null
                            $total_paid_sum += $total_paid;
                            $grand_total_paid_sum += $total_paid_sum;


                            //patient_test_entry
                            $this->db->select_sum('discharge.paid');
                            $this->db->where('discharge.user_id', $user_id);
                            $this->db->where('discharge.discharge_date', $today);

                            $ipd_patient_query = $this->db->get('discharge');
                            $result = $ipd_patient_query->row();
                            // Handle the case where no data is found
                            $total_paid = $result->paid ?? 0; // fallback to 0 if null
                            $total_paid_sum += $total_paid;
                            $grand_total_paid_sum += $total_paid_sum;


                            //ot_services return
                            $this->db->select_sum('ot_services.returnable_amount');
                            $this->db->where('ot_services.user_id', $user_id);
                            $this->db->where('ot_services.returnable_date', $today);

                            $ot_services_query_return = $this->db->get('ot_services');
                            $result = $ot_services_query_return->row();
                            // Handle the case where no data is found
                            $total_return = $result->returnable_amount ?? 0; // fallback to 0 if null
                            $total_return_sum += $total_return;
                            $grand_total_return_sum += $total_return_sum;


                            //opd_patient return
                            $this->db->select_sum('opd_patient.returnable_amount');
                            $this->db->where('opd_patient.user_id', $user_id);
                            $this->db->where('opd_patient.return_date', $today);

                            $opd_patient_query_return = $this->db->get('opd_patient');
                            $result = $opd_patient_query_return->row();
                            // Handle the case where no data is found
                            $total_return = $result->returnable_amount ?? 0; // fallback to 0 if null
                            $total_return_sum += $total_return;
                            $grand_total_return_sum += $total_return_sum;


                            //emergency return
                            $this->db->select_sum('emergency.returnable_amount');
                            $this->db->where('emergency.user_id', $user_id);
                            $this->db->where('emergency.return_date', $today);

                            $emergency_query_return = $this->db->get('emergency');
                            $result = $emergency_query_return->row();
                            // Handle the case where no data is found
                            $total_return = $result->returnable_amount ?? 0; // fallback to 0 if null
                            $total_return_sum += $total_return;
                            $grand_total_return_sum += $total_return_sum;

                            //phygiotherapy return
                            $this->db->select_sum('phygiotherapy.returnable_amount');
                            $this->db->where('phygiotherapy.user_id', $user_id);
                            $this->db->where('phygiotherapy.return_date', $today);

                            $phygiotherapy_query_return = $this->db->get('phygiotherapy');
                            $result = $phygiotherapy_query_return->row();
                            // Handle the case where no data is found
                            $total_return = $result->returnable_amount ?? 0; // fallback to 0 if null
                            $total_return_sum += $total_return;
                            $grand_total_return_sum += $total_return_sum;

                            //patient_test_entry return
                            $this->db->select_sum('patient_test_entry.returnable_amount');
                            $this->db->where('patient_test_entry.user_id', $user_id);
                            $this->db->where('patient_test_entry.return_date', $today);

                            $patient_test_entry_query_return = $this->db->get('patient_test_entry');
                            $result = $patient_test_entry_query_return->row();
                            // Handle the case where no data is found
                            $total_return = $result->returnable_amount ?? 0; // fallback to 0 if null
                            $total_return_sum += $total_return;
                            $grand_total_return_sum += $total_return_sum;


                            //ot_services due payment
                            $this->db->select_sum('ot_services.due_payment');
                            $this->db->where('ot_services.due_payment_user_id', $user_id);
                            $this->db->where('ot_services.due_payment_date', $today);

                            $ot_services_query_return = $this->db->get('ot_services');
                            $result = $ot_services_query_return->row();
                            // Handle the case where no data is found
                            $total_due_payment = $result->due_payment ?? 0; // fallback to 0 if null
                            $total_due_payment_sum += $total_due_payment;
                            $grand_total_due_payment_sum += $total_due_payment_sum;

                            //emergency due payment
                            $this->db->select_sum('emergency.due_payment');
                            $this->db->where('emergency.due_payment_user_id', $user_id);
                            $this->db->where('emergency.due_payment_date', $today);

                            $emergency_query_return = $this->db->get('emergency');
                            $result = $emergency_query_return->row();
                            // Handle the case where no data is found
                            $total_due_payment = $result->due_payment ?? 0; // fallback to 0 if null
                            $total_due_payment_sum += $total_due_payment;
                            $grand_total_due_payment_sum += $total_due_payment_sum;

                            //phygiotherapy due payment
                            
                            $this->db->select_sum('phygiotherapy.due_payment');
                            $this->db->where('phygiotherapy.due_payment_user_id', $user_id);
                            $this->db->where('phygiotherapy.due_payment_date', $today);

                            $phygiotherapy_query_due_payment = $this->db->get('phygiotherapy');
                            $result = $phygiotherapy_query_due_payment->row();
                            // print_r($result);
                            // die;
                            // Handle the case where no data is found
                            $total_due_payment = $result->due_payment ?? 0; // fallback to 0 if null
                            $total_due_payment_sum += $total_due_payment;
                            $grand_total_due_payment_sum += $total_due_payment_sum;

                            //test entry due payment
                            $this->db->select_sum('test_collection.paid');
                            $this->db->where('test_collection.user_id', $user_id);
                            $this->db->where('test_collection.payment_type', 'from_due_collection');
                            $this->db->where('test_collection.date', $today);

                            $test_collection_query_return = $this->db->get('test_collection');
                            $result = $test_collection_query_return->row();
                            // Handle the case where no data is found
                            $total_due_payment = $result->paid ?? 0; // fallback to 0 if null
                            $total_due_payment_sum += $total_due_payment;
                            $grand_total_due_payment_sum += $total_due_payment_sum;

                            ?>
                            <tr>
                                <td><?php echo $serial++ ?></td>
                                <td><?php echo $user->user_name ?></td>
                                <td id="total_paid_<?php echo $user->user_id ?>"><?php echo  number_format($total_paid_sum) ?></td>
                                <td id="total_return_<?php echo $user->user_id ?>"><?php echo  number_format($total_return_sum) ?></td>
                                <td id="total_due_collection_<?php echo $user->user_id ?>"><?php echo  number_format($total_due_payment_sum) ?></td>
                                <td id="total_balance_<?php echo $user->user_id ?>"><?php echo  number_format((float)$total_paid_sum + (float)$total_due_payment_sum - (float)$total_return_sum) ?></td>
                            </tr>
                        </table>
                        <?php

                        $opd_patient_sells = $this->db->where('user_id', $this->session->userdata('user_id'))
                            ->where('entry_date', $today)
                            ->get('opd_patient')->result();
                        $grand_total = 0;
                        if (!empty($opd_patient_sells)) {
                        ?>
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;margin-top:20px;">
                                <tr style="background-color: yellow;color: black;font-weight: bold;text-align: center;" id="report_block_header">
                                    <td colspan="9" style="text-align:center;font-weight:bold;background-color:yellow;color:black">Income from OPD Patient</td>
                                </tr>
                                <tr>
                                    <td style="width:5%">Sl</td>
                                    <td style="width:10%">Patient Name</td>
                                    <td style="width:10%">Invoice No</td>
                                    <td style="width:10%">Total</td>
                                    <td style="width:10%">Discount</td>
                                    <td style="width:10%">Return</td>
                                    <td style="width:10%">Paid</td>
                                    <td style="width:10%">Date</td>
                                </tr>
                                <?php
                                $k = 1;
                                $grand_visiting_fee = 0;
                                $grand_opd_return_amount = 0;
                                $grand_opd_total_amount = 0;
                                $grand_opd_discount_amount = 0;
                                foreach ($opd_patient_sells as $value) {
                                ?>
                                    <tr>
                                        <td><?php echo $k++; ?></td>
                                        <td><?php echo $value->opd_patient_name ?></td>
                                        <td><?php echo $value->opd_patient_unique_id ?></td>
                                        <td>
                                            <?php
                                            echo $value->visiting_fee;
                                            $grand_opd_total_amount += floatval($value->visiting_fee);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $value->discount;
                                            $grand_opd_discount_amount += floatval($value->discount);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $value->returnable_amount;
                                            $grand_opd_return_amount += floatval($value->returnable_amount);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $net_amount = floatval($value->payable) - floatval($value->returnable_amount);
                                            echo $net_amount;
                                            $grand_visiting_fee += $net_amount;
                                            $grand_total += $net_amount;
                                            ?>
                                        </td>

                                        <td><?php echo date('d-m-Y', strtotime($value->entry_date)) ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <td colspan="3" style="text-align:right">Total</td>
                                    <td> <?php echo number_format($grand_opd_total_amount) ?></td>
                                    <td> <?php echo number_format($grand_opd_discount_amount) ?></td>
                                    <td> <?php echo number_format($grand_opd_return_amount) ?></td>
                                    <td> <?php echo number_format($grand_visiting_fee) ?></td>
                                    <td></td>

                                </tr>
                            </table>
                        <?php
                        }
                        //OT service table
                        $this->db->where('user_id', $this->session->userdata('user_id'));
                        $this->db->where('date', $today);
                        $ot_services = $this->db->get('ot_services')->result();
                        if (!empty($ot_services)) {
                        ?>
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                <tr style="background-color: yellow;color: black;font-weight: bold;text-align: center;" id="report_block_header">
                                    <td colspan="8" style="text-align:center;font-weight:bold;background-color:yellow;color:black">Income from OT</td>
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
                        $this->db->where('returnable_date', $today);
                        $ot_services_return = $this->db->get('ot_services')->result();
                        $ot_services_returnable_amount = 0;
                        if (!empty($ot_services_return)) {
                        ?>
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                <tr style="background-color: yellow;color: black;font-weight: bold;text-align: center;" id="report_block_header">
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
                                        <td><?php echo $value->return_date ? date('d-m-Y', strtotime($value->return_date)) : '' ?></td>
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
                        $this->db->where('due_payment_date', $today);
                        $ot_services_due_payment = $this->db->get('ot_services')->result();
                        $total_ot_services_due_payment = 0;
                        if (!empty($ot_services_due_payment)) {
                        ?>
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                <tr style="background-color: yellow;color: black;font-weight: bold;text-align: center;" id="report_block_header">
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
                                        <td><?php echo $value->due_payment_date ? date('d-m-Y', strtotime($value->due_payment_date)) : '' ?></td>
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


                        $ipd_patient_sells = $this->db->where('user_id', $this->session->userdata('user_id'))
                            ->where('date', $today)
                            ->get('ipd_patient')->result();
                        if (!empty($ipd_patient_sells)) {
                        ?>
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                <tr style="background-color: yellow;color: black;font-weight: bold;text-align: center;" id="report_block_header">
                                    <td colspan="5" style="text-align:center;font-weight:bold;background-color:yellow;color:black">Income from IPD Patient</td>
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
                                foreach ($ipd_patient_sells as $value) {
                                ?>
                                    <tr>
                                        <td><?php echo $k++; ?></td>
                                        <td><?php echo $value->patient_name ?></td>
                                        <td><?php echo $value->patient_unique_id ?></td>
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
                        //Calculate OPD Return
                        $this->db->where('return_user_id', $user_id);
                        $this->db->where('return_date', $today);
                        $opd_patient_return = $this->db->get('opd_patient')->result();
                        $opd_patient_returnable_amount = 0;
                        if (!empty($opd_patient_return)) {
                        ?>
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                <tr style="background-color: yellow;color: black;font-weight: bold;text-align: center;" id="report_block_header">
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
                                        <td><?php echo $value->return_date ? date('d-m-Y', strtotime($value->return_date)) : '' ?></td>
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


                        $emergency_sells = $this->db->where('user_id', $this->session->userdata('user_id'))
                            ->where('date', $today)
                            ->get('emergency')->result();
                        if (!empty($emergency_sells)) {
                        ?>
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                <tr style="background-color: yellow;color: black;font-weight: bold;text-align: center;" id="report_block_header">
                                    <td colspan="10" style="text-align:center;font-weight:bold;background-color:yellow;color:black">Income from Emergency</td>
                                </tr>
                                <tr>
                                    <td style="width:10%">Sl</td>
                                    <td style="width:10%">Patient Name</td>
                                    <td style="width:10%">Invoice No</td>
                                    <td style="width:10%">Total</td>
                                    <td style="width:10%">Discount</td>
                                    <td style="width:10%">Net Total</td>
                                    <td style="width:10%">Return</td>
                                    <td style="width:10%">Paid</td>
                                    <td style="width:10%">Due</td>
                                    <td style="width:10%">Date</td>
                                </tr>
                                <?php
                                $k = 1;
                                $grand_emergency_paid_amount = 0;
                                $grand_emergency_return_amount = 0;
                                $grand_emergency_net_total = 0;
                                foreach ($emergency_sells as $value) {
                                ?>
                                    <tr>
                                        <td><?php echo $k++; ?></td>
                                        <td><?php echo $value->name; ?></td>
                                        <td><?php echo $value->emergency_invoice_no; ?></td>
                                        <td><?php echo $value->total; ?></td>
                                        <td><?php echo $value->discount; ?></td>
                                        <td><?php echo $value->nettotal;
                                            $grand_emergency_net_total += $value->nettotal; ?></td>
                                        <td><?php echo $value->returnable_amount;
                                            $grand_emergency_return_amount += $value->returnable_amount; ?></td>
                                        <td><?php echo $value->paid - $value->returnable_amount;
                                            $grand_emergency_paid_amount += $value->paid - $value->returnable_amount;
                                            $grand_total += $value->paid - $value->returnable_amount;
                                            ?></td>
                                        <td><?php echo $value->due ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($value->date)) ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <td colspan="5" style="text-align:right">Total</td>
                                    <td> <?php echo number_format($grand_emergency_net_total) ?></td>
                                    <td> <?php echo number_format($grand_emergency_return_amount) ?></td>
                                    <td> <?php echo number_format($grand_emergency_paid_amount) ?></td>
                                    <td></td>
                                </tr>
                            </table>
                        <?php
                        }

                        //Calculate Emergency Return
                        $this->db->where('return_user_id', $user_id);
                        $this->db->where('return_date', $today);
                        $emergency_returns = $this->db->get('emergency')->result();
                        $emergency_returnable_amount = 0;
                        if (!empty($emergency_returns)) {
                        ?>
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                <tr style="background-color: yellow;color: black;font-weight: bold;text-align: center;" id="report_block_header">
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
                                        <td><?php echo $value->return_date ? date('d-m-Y', strtotime($value->return_date)) : '' ?></td>
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
                        $this->db->where('due_payment_date', $today);
                        $emergency_due_payment = $this->db->get('emergency')->result();
                        $total_emergency_due_payment = 0;
                        if (!empty($emergency_due_payment)) {
                        ?>
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                <tr style="background-color: yellow;color: black;font-weight: bold;text-align: center;" id="report_block_header">
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
                                        <td><?php echo $value->due_payment_date ? date('d-m-Y', strtotime($value->due_payment_date)) : '' ?></td>
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


                        $phygiotherapy_sells = $this->db->where('user_id', $this->session->userdata('user_id'))
                            ->where('date', $today)
                            ->get('phygiotherapy')->result();
                        if (!empty($phygiotherapy_sells)) {
                        ?>
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                <tr style="background-color: yellow;color: black;font-weight: bold;text-align: center;" id="report_block_header">
                                    <td colspan="10" style="text-align:center;font-weight:bold;background-color:yellow;color:black">Income from Physiotherapy</td>
                                </tr>
                                <tr>
                                    <td style="width:5%">Sl</td>
                                    <td style="width:10%">Patient Name</td>
                                    <td style="width:10%">Invoice No</td>
                                    <td style="width:10%">Total</td>
                                    <td style="width:10%">Discount</td>
                                    <td style="width:10%">Net Total</td>
                                    <td style="width:10%">Return</td>
                                    <td style="width:10%">Paid</td>

                                    <td style="width:10%">Due</td>
                                    <td style="width:10%">Date</td>
                                </tr>
                                <?php
                                $k = 1;
                                $grand_paid_amount = 0;
                                $grand_physiotherapy_return_amount = 0;
                                $grand_physiotherapy_nettotal_amount = 0;
                                foreach ($phygiotherapy_sells as $value) {

                                ?>
                                    <tr>
                                        <td><?php echo $k++; ?></td>
                                        <td><?php echo $value->name ?></td>
                                        <td><?php echo $value->phygiotherapy_invoice_no ?></td>
                                        <td><?php echo $value->total ?></td>
                                        <td><?php echo $value->discount ?></td>
                                        <td>
                                            <?php
                                            echo $value->nettotal;
                                            $grand_physiotherapy_nettotal_amount += floatval($value->nettotal);
                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                            echo $value->returnable_amount;
                                            $grand_physiotherapy_return_amount += floatval($value->returnable_amount);
                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                            $net_paid = floatval($value->paid) - floatval($value->returnable_amount);
                                            echo $net_paid;
                                            $grand_paid_amount += $net_paid;
                                            $grand_total += $net_paid;
                                            ?>
                                        </td>

                                        <td><?php echo $value->due ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($value->date)) ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <td colspan="5" style="text-align:right">Total</td>
                                    <td> <?php echo number_format($grand_physiotherapy_nettotal_amount) ?></td>
                                    <td> <?php echo number_format($grand_physiotherapy_return_amount) ?></td>
                                    <td> <?php echo number_format($grand_paid_amount) ?></td>
                                    <td></td>
                                </tr>
                            </table>
                        <?php
                        }
                        //Calculate Physiotherapy Return
                        $this->db->where('return_user_id', $user_id);
                        $this->db->where('return_date', $today);
                        $phygiotherapy_returns = $this->db->get('phygiotherapy')->result();
                        $phygiotherapy_returnable_amount = 0;
                        if (!empty($phygiotherapy_returns)) {
                        ?>
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                <tr style="background-color: yellow;color: black;font-weight: bold;text-align: center;" id="report_block_header">
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
                                        <td><?php echo $value->return_date ? date('d-m-Y', strtotime($value->return_date)) : '' ?></td>
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
                        $this->db->where('due_payment_date', $today);

                        $phygiotherapy_due_payment = $this->db->get('phygiotherapy')->result();
                        $total_phygiotherapy_due_payment = 0;
                        if (!empty($phygiotherapy_due_payment)) {
                        ?>
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                <tr style="background-color: yellow;color: black;font-weight: bold;text-align: center;" id="report_block_header">
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


                        $today = date('Y-m-d');
                        $test_collection_sells = $this->db
                            ->select('test_collection.*, patient_test_entry.patient_name,patient_test_entry.returnable_amount,patient_test_entry.invoice_no')
                            ->from('test_collection')
                            ->join('patient_test_entry', 'test_collection.patient_test_entry_id = patient_test_entry.patient_test_entry_id', 'left') // Adjust the ON condition
                            ->where('test_collection.user_id', $this->session->userdata('user_id'))
                            ->where('test_collection.date', $today)
                            ->where('test_collection.payment_type', 'from_direct_sales')
                            ->get()
                            ->result();
                        if (!empty($test_collection_sells)) {
                        ?>
                            <table style="font-weight: bold;text-align: center;" border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                <tr id="report_block_header">
                                    <td colspan="10" style="text-align:center;font-weight:bold;background-color:yellow;color:black">Income from Test Sale</td>
                                </tr>
                                <tr>
                                    <td style="width:5%">Sl</td>
                                    <td style="width:10%">Patient Name</td>
                                    <td style="width:10%">Invoice No</td>
                                    <td style="width:10%">Total</td>
                                    <td style="width:10%">Discount</td>
                                    <td style="width:10%">Net Total</td>
                                    <td style="width:10%">Return</td>
                                    <td style="width:10%">Paid</td>
                                    <td style="width:10%">Due</td>
                                    <td style="width:10%">Date</td>
                                </tr>
                                <?php
                                $k = 1;
                                $grand_paid_amount = 0;
                                $grand_return_amount = 0;
                                $grand_test_net_total = 0;
                                foreach ($test_collection_sells as $value) {
                                ?>
                                    <tr>
                                        <td><?php echo $k++; ?></td>
                                        <td><?php echo $value->patient_name ?></td>
                                        <td><?php echo $value->invoice_no ?></td>
                                        <td><?php echo $value->sub_total ?></td>
                                        <td><?php echo $value->discount ?></td>
                                        <td><?php echo $value->net_total;
                                            $grand_test_net_total += $value->net_total; ?></td>
                                        <td><?php echo $value->returnable_amount;
                                            $grand_return_amount += $value->returnable_amount; ?></td>
                                        <td><?php echo $value->paid - $value->returnable_amount;
                                            $grand_paid_amount += $value->paid - $value->returnable_amount;
                                            $grand_total += $value->paid - $value->returnable_amount;
                                            ?></td>
                                        <td><?php echo $value->due ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($value->date)) ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <td colspan="5" style="text-align:right">Total</td>
                                    <td> <?php echo number_format($grand_test_net_total) ?></td>
                                    <td> <?php echo number_format($grand_return_amount) ?></td>
                                    <td> <?php echo number_format($grand_paid_amount) ?></td>
                                    <td></td>
                                </tr>
                            </table>
                        <?php
                        }

                        $today = date('Y-m-d');
                        $test_collection_sells = $this->db
                            ->select('test_collection.*, patient_test_entry.patient_name,patient_test_entry.invoice_no')
                            ->from('test_collection')
                            ->join('patient_test_entry', 'test_collection.patient_test_entry_id = patient_test_entry.patient_test_entry_id', 'left') // Adjust the ON condition
                            ->where('test_collection.user_id', $this->session->userdata('user_id'))
                            ->where('test_collection.date', $today)
                            ->where('test_collection.payment_type', 'from_due_collection')
                            ->get()
                            ->result();
                        if (!empty($test_collection_sells)) {
                        ?>
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                <tr style="background-color: yellow;color: black;font-weight: bold;text-align: center;" id="report_block_header">
                                    <td colspan="5" style="text-align:center;font-weight:bold;background-color:#006ADC;color:color">Income from Test Due Collection</td>
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
                                foreach ($test_collection_sells as $value) {
                                ?>
                                    <tr>
                                        <td><?php echo $k++; ?></td>
                                        <td><?php echo $value->patient_name ?></td>
                                        <td><?php echo $value->invoice_no ?></td>
                                        <td><?php echo $value->paid;
                                            $grand_paid_amount += $value->paid;
                                            $total_due_payment += $value->paid;
                                            ?></td>
                                        <td><?php echo $value->date ? date('d-m-Y', strtotime($value->date)) : '' ?></td>
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
                        <table border="1" class="table table-bordered table-hover" style="width: 100%;margin-top:10px;color:black;border-collapse:collapse;">
                            <tr style="background-color: yellow;color: black">
                                <td colspan="6" style="text-align: center">Department Wise Test</td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td>Total Entry</td>
                                <td>Total Amount</td>
                                <td>Total Discount</td>
                                <td>Return</td>
                                <td>Net Amount</td>
                                
                            </tr>

                            <?php
                            $grand_test_total_entry = 0;
                            $grand_test_total_price = 0;
                            $grand_test_total_discount_each = 0;
                            $grand_test_total_paid_each = 0;
                            $grand_test_total_return_each_category = 0;

                            $test_categories = $this->db->get('test_categories')->result();
                            foreach ($test_categories as $test_category) {
                                $date_condition = [
                                    'date=' => $today
                                ];

                                $this->db->select('
        SUM(total_price) as total_price,
        SUM(total_return) as total_return,
        SUM(discount_each) as total_discount_each,
        SUM(paid_each) as total_paid_each,
        COUNT(*) as total_rows
    ');
                                $this->db->from('patient_test_entry_details');
                                $this->db->where('user_id', $this->session->userdata('user_id'));
                                $this->db->where('test_category_id', $test_category->test_category_id); // Filter by category
                                $this->db->where('date', $today); // Filter by date

                                $query = $this->db->get();
                                $row = $query->row();

                                $test_total_price = $row->total_price ?? 0;
                                $test_total_discount_each = $row->total_discount_each ?? 0;
                                $test_total_paid_each = $row->total_paid_each ?? 0;
                                $test_total_return_each_category = $row->total_return ?? 0;
                                $test_total_rows = $row->total_rows ?? 0;

                                $grand_test_total_entry += $test_total_rows;
                                $grand_test_total_price += $test_total_price;
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
                                    <td><?php echo number_format($test_total_paid_each-$test_total_return_each_category) ?></td>
                                    
                                </tr>
                            <?php } ?>

                            <tr>
                                <td><strong>Total</strong></td>
                                <td><strong><?php echo number_format($grand_test_total_entry) ?></strong></td>
                                <td><strong><?php echo number_format($grand_test_total_price) ?></strong></td>
                                <td><strong><?php echo number_format($grand_test_total_discount_each) ?></strong></td>
                                <td><strong><?php echo number_format($grand_test_total_return_each_category) ?></strong></td>
                                <td><strong><?php echo number_format($grand_test_total_paid_each-$grand_test_total_return_each_category) ?></strong></td>
                              
                            </tr>
                        </table>
                        <?php

                        $today = date('Y-m-d');
                        $discharge_sells = $this->db
                            ->select('discharge.*, ipd_patient.patient_name, ipd_patient.patient_unique_id')
                            ->from('discharge')
                            ->join('ipd_patient', 'discharge.ipd_patient_id = ipd_patient.ipd_patient_id', 'left') // Adjust the ON condition
                            ->where('discharge.user_id', $this->session->userdata('user_id'))
                            ->where('discharge.discharge_date', $today)
                            ->get()
                            ->result();

                        if (!empty($discharge_sells)) { // Check if $discharge_sells is not empty
                        ?>
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                <tr style="background-color: yellow;color: black;font-weight: bold;text-align: center;" id="report_block_header">
                                    <td colspan="9" style="text-align:center;font-weight:bold;background-color:#006ADC;color:color">Income from Discharged</td>
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
                                        <td><?php echo (float)$value->director_discount + (float)$value->special_discount; ?> </td>
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
                                        <td><?php echo $value->discharge_date ? date('d-m-Y', strtotime($value->discharge_date)) : ''; ?></td>
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
                        <table border="1" style="border-collapse:collapse;margin:0 auto;color:black;diplay:none;">
                            <tr style="background-color: yellow;color: black;font-weight: bold;text-align: center;" id="report_block_header" style="background-color:#006ADC;color:black">
                                <td>Total Paid</td>
                                <td>Total Return</td>
                                <td>Total Due Collection</td>
                                <td>Balance</td>

                            </tr>
                            <tr>
                                <td><?php echo number_format($grand_total) ?></td>
                                <td><?php echo number_format($total_returnable_amount) ?></td>
                                <td><?php echo number_format($total_due_payment) ?></td>
                                <td><?php echo number_format((float)$grand_total + (float)$total_due_payment - (float)$total_returnable_amount) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>