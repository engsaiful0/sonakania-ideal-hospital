<!-- Styles for Print -->
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

<!-- JavaScript -->
<script>
    function load_all_users_based_sell_report_for_admin() {

        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;

        $('#loadingSpinner').show();
        $.ajax({
            url: "<?php echo base_url('DrugController/load_all_users_medicine_sale_report'); ?>",
            method: "POST",
            data: {

                from_date: from_date,
                to_date: to_date,
            },
            dataType: "json",
            success: function(response) {
                $('#loadingSpinner').hide();
                $('#report_container').html(response.data);
            },
            error: function(xhr, status, error) {
                $('#loadingSpinner').hide();
                alert("Something went wrong!");
            }
        });
    }

    $(document).ready(function() {
        $('#user_id').select2();
    });
</script>

<!-- UI Controls -->
<div class="row">
    <div class="col-md-12">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
    </div>
</div>

<!-- Filter Form -->
<table class="table" style="width: 90%;margin:auto">
    <tr>
        <td>From Date</td>
        <td>To Date</td>
    </tr>
    <tr>
        <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>
        <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>
        <td><input type="submit" class="btn btn-primary" onclick="load_all_users_based_sell_report_for_admin()" value="Search"></td>
    </tr>
</table>

<!-- Report Area -->
<div id="report" style="width: 90%; margin: 40px auto; color:black;">
    <div id="report_container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php $this->load->view('common/report_header'); ?>
                <p style="clear:left;text-align: center;">
                    All Users Medicine Sale Report. Date: <b><?php echo date('d-m-Y') ?></b><br>

                </p>
            </div>
            <div class="panel-body">
                <!-- Summary Table -->
                <table border="1" class="table table-bordered table-hover" style="width: 100%; margin: auto; color: black; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <td>User</td>
                            <td>Total Entry</td>
                            <td>Total Price</td>
                            <td>Total Discount</td>
                            <td>Net Total</td>
                            <td>Paid</td>
                            <td>Due</td>
                            <td>Return</td>
                            <td>Balance</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $today = date('Y-m-d');
                        $pharmacy_users = $this->db->select('*')->from('user')->where('user_type_id', '1')->get()->result();

                        $grand_total_price = 0;
                        $grand_total_discount = 0;
                        $grand_total_paid = 0;
                        $grand_total_nettotal = 0;
                        $grand_total_due = 0;
                        $grand_total_rows = 0;
                        $grand_sale_return = 0;
                        $grand_balance = 0;

                        foreach ($pharmacy_users as $pharmacy_user) {
                            $user_id = $pharmacy_user->user_id;
                            $user_name = $pharmacy_user->user_name;

                            $this->db->select('
                                SUM(total) as total_price,
                                SUM(discount) as total_discount,
                                SUM(paid) as total_paid,
                                SUM(nettotal) as total_nettotal,
                                SUM(due) as total_due,
                                COUNT(*) as total_rows
                            ');
                            $this->db->from('medicine_sales');
                            $this->db->where('bill_date', $today);
                            $this->db->where('user_id', $user_id);
                            $row = $this->db->get()->row();

                            $total_price = $row->total_price ?? 0;
                            $total_discount = $row->total_discount ?? 0;
                            $total_paid = $row->total_paid ?? 0;
                            $total_nettotal = $row->total_nettotal ?? 0;
                            $total_due = $row->total_due ?? 0;
                            $total_rows = $row->total_rows ?? 0;

                            $this->db->select_sum('paid');
                            $this->db->from('medicine_sale_return');
                            $this->db->where('user_id', $user_id);
                            $this->db->where('date', $today);
                            $return_amount = $this->db->get()->row()->paid ?? 0;

                            $balance = $total_paid - $return_amount;

                            $grand_total_price += (float)$total_price;
                            $grand_total_discount += (float)$total_discount;
                            $grand_total_paid += (float)$total_paid;
                            $grand_total_nettotal += (float)$total_nettotal;
                            $grand_total_due += (float)$total_due;
                            $grand_total_rows += (float)$total_rows;
                            $grand_sale_return += (float)$return_amount;
                            $grand_balance += (float)$balance;
                        ?>
                            <tr>
                                <td><?php echo $user_name; ?></td>
                                <td><?php echo $total_rows; ?></td>
                                <td><?php echo number_format($total_price); ?></td>
                                <td><?php echo number_format($total_discount); ?></td>
                                <td><?php echo number_format($total_nettotal); ?></td>
                                <td><?php echo number_format($total_paid); ?></td>
                                <td><?php echo number_format($total_due); ?></td>
                                <td><?php echo number_format($return_amount); ?></td>
                                <td><?php echo number_format($balance); ?></td>
                            </tr>
                        <?php } ?>
                        <tr style="font-weight: bold;">
                            <td>Total</td>
                            <td><?php echo $grand_total_rows; ?></td>
                            <td><?php echo number_format($grand_total_price); ?></td>
                            <td><?php echo number_format($grand_total_discount); ?></td>
                            <td><?php echo number_format($grand_total_nettotal); ?></td>
                            <td><?php echo number_format($grand_total_paid); ?></td>
                            <td><?php echo number_format($grand_total_due); ?></td>
                            <td><?php echo number_format($grand_sale_return); ?></td>
                            <td><?php echo number_format($grand_balance); ?></td>
                        </tr>
                    </tbody>
                </table>
                <?php
                foreach ($pharmacy_users as $pharmacy_user) {
                    $user_id = $pharmacy_user->user_id;
                    $user_name = $pharmacy_user->user_name;
                ?>
                    <table border="1" class="table table-bordered table-hover" style="margin-top: 10px;width: 100%;color: black; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color:yellow; color: black;">
                                <td colspan="7" style="text-align: center;"><b> Medicine Sale</b> Date: <?php echo date('d-m-Y', strtotime($today)); ?>. User: <b><?php echo $user_name ?></b></td>
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
                        </thead>


                        <tbody>
                            <?php
                            $query = $this->db->select('*')
                                ->from('medicine_sales')
                                ->where('user_id', $user_id)
                                ->where('bill_date', $today)
                                ->order_by('medicine_sale_id', 'DESC')
                                ->get()->result();

                            $k = 1;
                            $grand_nettotal = 0;
                            $total_paid = 0;
                            $grand_due = 0;

                            foreach ($query as $value) {
                                $grand_nettotal += (float)$value->nettotal;
                                $total_paid += (float)$value->paid;
                                $grand_due += (float)$value->due;
                            ?>
                                <tr>
                                    <td><?php echo $k++; ?></td>
                                    <td><?php echo $value->name ?? ""; ?></td>
                                    <td><?php echo $value->medicine_sale_invoice_no ?? ""; ?></td>
                                    <td><?php echo number_format((float)$value->nettotal); ?></td>
                                    <td><?php echo number_format((float)$value->paid); ?></td>
                                    <td><?php echo number_format((float)$value->due); ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($value->bill_date)); ?></td>
                                </tr>
                            <?php } ?>
                            <tr style="font-weight: bold;">
                                <td colspan="3" style="text-align: right;">Total</td>
                                <td><?php echo number_format((float)$grand_nettotal); ?></td>
                                <td><?php echo number_format((float)$total_paid); ?></td>
                                <td><?php echo number_format((float)$grand_due); ?></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                        <tr style="background-color: #0074B3;color: white  ">
                            <td colspan="7" style="text-align: center"><b>Sale Return Amount</b></td>
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
                        $this->db->where('user_id', $user_id);
                        if (!empty($from_date) && !empty($to_date)) {
                            $this->db->where('return_date >=', $from_date);
                            $this->db->where('return_date <=', $to_date);
                        } elseif (!empty($from_date)) {
                            $this->db->where('return_date', $from_date);
                        } elseif (!empty($to_date)) {
                            $this->db->where('return_date', $to_date);
                        }
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
                            <td colspan="7" style="text-align: center"><b>Medicine Sale Due Payment</b> Date: <?php echo date('d-m-Y', strtotime($today)); ?></b></td>
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
                        $this->db->where('due_payment_user_id', $user_id);
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

                <?php
                }
                ?>

            </div> <!-- End of Panel Body -->
        </div> <!-- End of Panel -->
    </div>
</div>