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

        .container_user {
            display: none;
        }
    }
</style>
<script>
    function load_user_based_sell_report_for_user() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        $('#loadingSpinner').show();
        $.ajax({
            url: "<?php echo base_url('DrugController/load_user_based_sell_report_for_user'); ?>",
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
        if (!user_id) {
            alert("Please select a user.");
            return; // Stop the function here
        }
        $('#loadingSpinner').show();
        $.ajax({
            url: "<?php echo base_url('DrugController/load_user_based_sell_report_for_admin'); ?>",
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
<table class="table">
    <tr>
        <?php

        $user_type_name = $this->session->userdata('user_type');

        $user = getUserById($this->session->userdata('user_id'));

        if ($user_type_name == 'Admin') {
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

        if ($user_type_name == 'Admin') {
        ?>
            <td>
                <select class="form-control" id="user_id" name="user_id">
                    <option selected="" value="" disabled="">Select User</option>
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

        if ($user_type_name == 'Admin') {
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
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;margin-top:40px;color:black;height: 20cm ">
    <div id="report_container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?php
                $this->load->view('common/report_header');
                ?>
                <p style="clear:left;text-align: center">Today My Sale Report. Date:<b><?php echo date('d-m-Y') ?></b> <br>
                    <span style="text-align: center;font-weight:bold">User:<?php echo  $user->user_name ?></span>
                </p>
            </div>
            <div class="panel-body" style="width: 100%;">
                <?php
                $today = date('Y-m-d');
                ?>
                <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                    <tr style="background-color: #0074B3;color: white  ">
                        <td colspan="7" style="text-align: center"><b>Income From Medicine Sale</b> Date: <?php echo date('d-m-Y', strtotime($today)); ?></b></td>
                    </tr>
                    <tr>
                        <td>Sl</td>
                        <td>Customer Name</td>
                        <td>Invoice No</td>
                        <td>Net Total</td>
                        <td>Paid</td>
                        <td>Due</td>
                        <td>Date</td>
                    </tr>
                    <?php

                    $this->db->select('*');
                    $this->db->from('medicine_sales');
                    $this->db->where('user_id', $this->session->userdata('user_id'));
                    $this->db->where('bill_date', $today);
                    $this->db->order_by('medicine_sale_id ', 'DESC');
                    $query_medicine_sales = $this->db->get();
                    $query = $query_medicine_sales->result();

                    $sl = 1;
                    $k = 1;
                    $grand_nettotal = 0;
                    $total_paid = 0;
                    $grand_due = 0;
                    foreach ($query as $value) {

                    ?>
                        <tr>
                            <td><?php echo $k++; ?></td>
                            <td><?php echo $value->name ?? "" ?></td>
                            <td><?php echo $value->medicine_sale_invoice_no ?? "" ?></td>
                            <td><?php echo $value->nettotal ?? "";
                                $grand_nettotal += $value->nettotal; ?></td>
                            <td><?php echo $value->paid ?? "";
                                $total_paid += $value->paid ?></td>
                            <td>
                                <?php
                                // Ensure $value->due is numeric
                                $due = is_numeric($value->due) ? (float)$value->due : 0;
                                echo $due;
                                $grand_due += $due;
                                ?>
                            </td>

                            <td><?php echo date('d-m-Y', strtotime($value->bill_date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr style="font-weight: bold;">
                        <td colspan="3" style="text-align:right">Total</td>
                        <td> <?php echo number_format($grand_nettotal) ?></td>
                        <td> <?php echo number_format($total_paid) ?></td>
                        <td> <?php echo number_format($grand_due) ?></td>
                    </tr>
                </table>
                <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                    <tr style="background-color: #0074B3;color: white  ">
                        <td colspan="7" style="text-align: center"><b>Sale Return Amount</b> Date: <?php echo date('d-m-Y', strtotime($today)); ?></b></td>
                    </tr>
                    <tr>
                        <td>Sl</td>
                        <td>Customer Name</td>
                        <td>Return Invoice No</td>
                        <td>Return Amount</td>
                        <td>Date</td>
                    </tr>
                    <?php

                    $this->db->select('*');
                    $this->db->from('medicine_sale_returns_without_invoice');
                    $this->db->where('user_id', $this->session->userdata('user_id'));
                    $this->db->where('return_date', $today);
                    $this->db->order_by('medicine_sale_return_id_without_invoice ', 'DESC');
                    $query_medicine_sale_return_id_without_invoice = $this->db->get();
                    $query = $query_medicine_sale_return_id_without_invoice->result();

                    $sl = 1;
                    $k = 1;
                    $grand_return_amount = 0;
                  
                    foreach ($query as $value) {

                    ?>
                        <tr>
                            <td><?php echo $k++; ?></td>
                            <td><?php echo $value->name ?? "" ?></td>
                            <td><?php echo $value->return_invoice_no ?? "" ?></td>
                            <td><?php echo $value->return_amount ?? "";
                                $grand_return_amount += $value->return_amount; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->return_date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr style="font-weight: bold;">
                        <td colspan="3" style="text-align:right">Total</td>
                        <td> <?php echo number_format($grand_return_amount) ?></td>
                        <td></td>
                    </tr>
                </table>
                <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                    <tr style="background-color: #0074B3;color: white  ">
                        <td colspan="7" style="text-align: center"><b>Medicine Sell Due Payment</b> Date: <?php echo date('d-m-Y', strtotime($today)); ?></b></td>
                    </tr>
                    <tr>
                        <td>Sl</td>
                        <td>Customer Name</td>
                        <td>Invoice No</td>

                        <td>Due Paid</td>

                        <td>Date</td>
                    </tr>
                    <?php

                    $this->db->select('*');
                    $this->db->from('medicine_sales');
                    $this->db->where('due_payment_user_id', $this->session->userdata('user_id'));
                    $this->db->where('due_payment_date', $today);
                    $query_medicine_sales = $this->db->get();
                    $query_due_payment = $query_medicine_sales->result();

                    $sl = 1;
                    $k = 1;
                    $total_due_payment = 0;

                    foreach ($query_due_payment as $value) {

                    ?>
                        <tr>
                            <td><?php echo $k++; ?></td>
                            <td><?php echo $value->name ?? "" ?></td>
                            <td><?php echo $value->medicine_sale_invoice_no ?? "" ?></td>
                            <td><?php echo $value->due_payment ?? "";
                                $total_due_payment += $value->due_payment; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($value->due_payment_date)) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr style="font-weight: bold;">
                        <td colspan="3" style="text-align:right">Total</td>
                        <td> <?php echo number_format($total_due_payment) ?></td>
                    </tr>
                </table>

                <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;margin-top: 10px;">
                    <tr>
                        <td>Total Collection: <b><?php echo number_format($total_paid + $total_due_payment - $grand_return_amount) ?></b></td>
                    </tr>
                    <tr>
                        <td>In Word: <b><?php echo convertNumberToWord(number_format($total_paid + $total_due_payment- $grand_return_amount)) ?></b></td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
</div>