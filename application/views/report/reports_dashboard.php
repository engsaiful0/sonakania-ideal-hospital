<div class="container">
    <?php $permissions = $this->session->userdata('permissions'); ?>
    <?php if (in_array('report_dashboard', $permissions)) { ?>
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                .box_top {
                    display: none !important;
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
                    width: 100%;
                    margin: 0;
                    padding: 0;
                    page-break-inside: avoid;
                    /* Prevent page breaks inside the report */
                }

                .panel {
                    page-break-inside: avoid;
                    /* Prevent page breaks inside the panel */
                }

                .panel-body {
                    page-break-inside: avoid;
                    /* Prevent page breaks inside the panel body */
                }

                .row {
                    page-break-inside: avoid;
                    /* Prevent page breaks inside rows */
                }

                .col-md-12 {
                    page-break-inside: avoid;
                    /* Prevent page breaks inside columns */
                }

                .container_user {
                    display: none;
                }
            }
        </style>
        <script type="text/javascript">
            function load_date_wise_summary_report() {
                var from_date = document.getElementById('datepicker1').value;
                var to_date = document.getElementById('datepicker2').value;
                $('#loadingSpinner').show();
                $.ajax({
                    url: "<?php echo base_url('ReportController/load_date_wise_summary_report'); ?>",
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
            $(document).ready(function() {
                $('#user_id').select2();
            });
        </script>
        <div class="row">
            <div class="col-md-12">
                <button onclick="window.print()" class="btn btn-primary">Print</button>
            </div>
        </div>

        <div id="report" style="width: 90%; margin: 0 auto; margin-left: 45px; margin-top: 10px;" class="panel panel-primary">
            <div class="panel-heading">

            </div>
            <div class="panel-body">
                <?php

                $compnay = $this->db->where('company_id', '1')->get('company')->row();
                $user_type_name = $this->session->userdata('user_type');
                if ($user_type_name == 'Admin') {
                ?>
                    <div class="container_user">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <table class="table">
                                        <tr>

                                            <td>From Date</td>
                                            <td>To Date</td>
                                        </tr>
                                        <tr>



                                            <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>

                                            <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>

                                            <td><input type="submit" class="btn btn-primary " onclick="load_date_wise_summary_report()" value="Search"></td>
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
                <?php
                }
                ?>
                <div id="report_container_header">
                    <div class="" style="width: 100%;margin-bottom: 10px;">
                        <div style="width: 15%;float: left;margin-top:20px">
                            <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
                        </div>
                        <div style="width: 85%;float: left;text-align: center">
                            <p style="text-align: center"><span style="text-align: center;font-size: 20px;text-align: center "> <?php echo $compnay->company_name ?></span><br><?php echo $compnay->address ?></span><br>
                                <span style="text-align: center">
                                    Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                                </span>
                            </p>
                        </div>

                    </div>
                    <div id="report_container">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                $this->db->select_sum('nettotal');
                                $this->db->from('medicine_sales');
                                $this->db->where('date', date('Y-m-d')); // Filter by today's date
                                $query = $this->db->get();
                                $total_medicine_sells_today = $query->row()->nettotal ?? 0; // Return 0 if no income found

                                $this->db->select_sum('payable');
                                $this->db->from('opd_patient');
                                $this->db->where('entry_date', date('Y-m-d')); // Filter by today's date
                                $query = $this->db->get();
                                $total_opd_payable_today = $query->row()->payable ?? 0; // Return 0 if no income found

                                $this->db->select_sum('paid_amount');
                                $this->db->from('ipd_patient');
                                $this->db->where('date', date('Y-m-d')); // Filter by today's date
                                $query = $this->db->get();
                                $total_ipd_paid_amount_today = $query->row()->paid_amount ?? 0; // Return 0 if no income found

                                $this->db->select_sum('paid');
                                $this->db->from('emergency');
                                $this->db->where('date', date('Y-m-d')); // Filter by today's date
                                $query = $this->db->get();
                                $total_emergency_today = $query->row()->paid ?? 0; // Return 0 if no income found

                                $this->db->select_sum('paid');
                                $this->db->from('phygiotherapy');
                                $this->db->where('date', date('Y-m-d')); // Filter by today's date
                                $query = $this->db->get();
                                $total_phygiotherapy_today = $query->row()->paid ?? 0; // Return 0 if no income found

                                $this->db->select_sum('total_amount');
                                $this->db->from('debit_voucher');
                                $this->db->where('date', date('Y-m-d')); // Filter by today's date
                                $query = $this->db->get();
                                $total_debit_voucher_today = $query->row()->total_amount ?? 0; // Return 0 if no income found

                                $this->db->select_sum('total_amount');
                                $this->db->from('credit_voucher');
                                $this->db->where('date', date('Y-m-d')); // Filter by today's date
                                $query = $this->db->get();
                                $total_credit_voucher_today = $query->row()->total_amount ?? 0; // Return 0 if no income found

                                $this->db->select_sum('paid');
                                $this->db->from('ot_services');
                                $this->db->where('date', date('Y-m-d')); // Filter by today's date
                                $query = $this->db->get();
                                $total_ot_service_paid_today = $query->row()->paid ?? 0; // Return 0 if no income found


                                $this->db->select_sum('paid');
                                $this->db->from('test_collection');
                                $this->db->where('payment_type', 'from_direct_sales');
                                $this->db->where('date', date('Y-m-d')); // Filter by today's date
                                $query = $this->db->get();
                                $total_test_entry_today = $query->row()->paid ?? 0; // Return 0 if no income found

                                $this->db->select_sum('paid');
                                $this->db->from('test_collection');
                                $this->db->where('payment_type', 'from_due_collection');
                                $this->db->where('date', date('Y-m-d')); // Filter by today's date
                                $query = $this->db->get();
                                $total_test_due_collection_today = $query->row()->paid ?? 0; // Return 0 if no income found

                                $this->db->select_sum('total_return');
                                $this->db->from('patient_test_entry_details');
                                $this->db->where('date', date('Y-m-d')); // Filter by today's date
                                $query = $this->db->get();
                                $total_test_return_today = $query->row()->total_return ?? 0; // Return 0 if no income found


                                $this->db->select_sum('paid');
                                $this->db->from('discharge');
                                $this->db->where('discharge_date', date('Y-m-d')); // Filter by today's date
                                $query = $this->db->get();
                                $total_discharge_value_today = $query->row()->paid ?? 0; // Return 0 if no income found

                                $this->db->select_sum('paid');
                                $this->db->from('medicine_sales');
                                $this->db->where('bill_date', date('Y-m-d')); // Filter by today's date
                                $query = $this->db->get();
                                $total_medicine_sells_today = $query->row()->paid ?? 0; // Return 0 if no income found

                                $this->db->select_sum('due_payment');
                                $this->db->from('medicine_sales');
                                $this->db->where('due_payment_date', date('Y-m-d')); // Filter by today's date
                                $query = $this->db->get();
                                $total_medicine_sells_due_payment = $query->row()->due_payment ?? 0; // Return 0 if no income found

                                //Emergency Retrun Amount
                                $date_condition = [
                                    'date' => date('Y-m-d'),
                                ];
                                $this->db->select_sum('returnable_amount');
                                $this->db->from('emergency');

                                $this->db->where($date_condition); // Filter by date range
                                $query = $this->db->get();
                                $emergency_return_amount = $query->row()->returnable_amount ?? 0;

                                //Physiotherapy Return Amount
                                $date_condition = [
                                    'date' => date('Y-m-d'),
                                ];
                                $this->db->select_sum('returnable_amount');
                                $this->db->from('phygiotherapy');

                                $this->db->where($date_condition); // Filter by date range

                                $query = $this->db->get();
                                $phygiotherapy_return_amount = $query->row()->returnable_amount ?? 0;

                                //OPD Returnable  Amount
                                $date_condition = [
                                    'entry_date' => date('Y-m-d'),
                                ];
                                $this->db->select_sum('returnable_amount');
                                $this->db->from('opd_patient');
                                $this->db->where($date_condition); // Filter by today's date
                                $query = $this->db->get();
                                $opd_returnable_amount = $query->row()->returnable_amount ?? 0;


                                //Pharmacy Return Amount
                                $date_condition = [
                                    'date' => date('Y-m-d'),
                                ];
                                $this->db->select_sum('paid');
                                $this->db->from('medicine_sale_return');
                                $this->db->where($date_condition); // Filter by date range
                                $query = $this->db->get();
                                $medicine_sales_return_amount = $query->row()->paid ?? 0;

                                ?>
                                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">

                                    <tbody>
                                        <tr>
                                            <td colspan="5" style="text-align:center">
                                                <h3 style="text-align: center">Today Summary</h3>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Income</th>
                                            <th>Return</th>
                                            <th>Expense</th>
                                            <th>Balance</th>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="text-align: center;">Summary report of date: <b><?php echo date('d-m-Y') ?></b></td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>OPD</td>
                                            <td><?php echo  number_format($total_opd_payable_today) ?></td>
                                            <td><?php echo  number_format($opd_returnable_amount) ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>IPD</td>
                                            <td><?php echo  number_format($total_ipd_paid_amount_today) ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Discharge</td>
                                            <td><?php echo  number_format($total_discharge_value_today) ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Emergency</td>
                                            <td><?php echo  number_format($total_emergency_today) ?></td>
                                            <td><?php echo  number_format($emergency_return_amount) ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Physiotherapy</td>
                                            <td><?php echo  number_format($total_phygiotherapy_today) ?></td>
                                            <td><?php echo  number_format($phygiotherapy_return_amount) ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>Test Entry</td>
                                            <td><?php echo  number_format($total_test_entry_today) ?></td>
                                            <td><?php echo  number_format($total_test_return_today) ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>Test Due Collection</td>
                                            <td><?php echo  number_format($total_test_due_collection_today) ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>Pharmacy</td>
                                            <td><?php echo  number_format($total_medicine_sells_today+$total_medicine_sells_due_payment) ?></td>
                                            <td><?php echo  number_format($medicine_sales_return_amount) ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>Credit Voucher</td>
                                            <td><?php echo  number_format($total_credit_voucher_today) ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>OT</td>
                                            <td><?php echo  number_format($total_ot_service_paid_today) ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>11</td>
                                            <td>Debit Voucher</td>
                                            <td></td>
                                            <td></td>
                                            <td><?php echo  number_format($total_debit_voucher_today) ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td style="text-align:right">Total</td>
                                            <td><?php echo  number_format($total_ot_service_paid_today + $total_discharge_value_today + $total_test_entry_today + $total_test_due_collection_today + $total_opd_payable_today + $total_ipd_paid_amount_today + $total_emergency_today + $total_phygiotherapy_today + $total_medicine_sells_today+$total_medicine_sells_due_payment + $total_credit_voucher_today) ?></td>
                                            <td><?php echo  number_format($medicine_sales_return_amount+$opd_returnable_amount + $total_test_return_today + $emergency_return_amount + $phygiotherapy_return_amount) ?></td>
                                            <td><?php echo  number_format($total_debit_voucher_today) ?></td>
                                            <td><?php echo  number_format(($total_ot_service_paid_today + $total_discharge_value_today + $total_test_entry_today + $total_test_due_collection_today + $total_opd_payable_today + $total_ipd_paid_amount_today + $total_emergency_today + $total_phygiotherapy_today + $total_medicine_sells_today+$total_medicine_sells_due_payment + $total_credit_voucher_today) - ($total_debit_voucher_today) - ($medicine_sales_return_amount+$opd_returnable_amount + $total_test_return_today + $emergency_return_amount + $phygiotherapy_return_amount)) ?></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<script>
    // Function to fetch and update the pharmacy todayTotalSell
    function updatePharmacySell() {
        $.ajax({
            url: '<?= base_url("ReportDashboardController/getPharmacyTodaySell") ?>', // URL to fetch the income
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Update the Pharmacy Income column in the table
                $('table tbody tr:nth-child(5) td:nth-child(3)').text(response.todayTotalSell);
            },
            error: function() {
                console.error('Failed to fetch pharmacy todayTotalSell.');
            }
        });
    }

    // Call the function every 10 seconds (or on demand)
    //setInterval(updatePharmacySell, 10000); // Update every 10 seconds
</script>