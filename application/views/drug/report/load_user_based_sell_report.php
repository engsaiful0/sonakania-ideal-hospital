<?php
$user = getUserById($user_id);

if (!empty($to_date)) {
    $to_date = date('Y-m-d', strtotime($to_date));
}

if (!empty($from_date)) {
    $from_date = date('Y-m-d', strtotime($from_date));
}
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <?php $this->load->view('common/report_header'); ?>
        <p style="clear:left; text-align: center;">
        <p style="clear:left;text-align: center">My Sell Report. Date:<b><?php echo date('Y-m-d', strtotime($from_date)) . ' To ' . date('Y-m-d', strtotime($to_date)) ?></b> <br>
            <span style="font-weight:bold;">User: <?php echo $user->user_name; ?></span>
        </p>
    </div>

    <div class="panel-body" style="width: 100%;">
        <?php




        // Get paginated data
        // Get paginated data
        $this->db->select('*');
        $this->db->from('medicine_sales');
        $this->db->where('user_id', $user_id);

        if (!empty($from_date) && !empty($to_date)) {
            $this->db->where('bill_date >=', $from_date);
            $this->db->where('bill_date <=', $to_date);
        } elseif (!empty($from_date)) {
            $this->db->where('bill_date', $from_date);
        } elseif (!empty($to_date)) {
            $this->db->where('bill_date', $to_date);
        }

        $this->db->order_by('medicine_sale_id', 'DESC');

        $query_medicine_sales = $this->db->get();
        $query = $query_medicine_sales->result();

        ?>

        <table border="1" class="table table-bordered table-hover" style="width: 90%; margin: 0 auto; color: black; border-collapse: collapse;">
            <tr style="background-color: #0074B3; color: white;">
                <td colspan="7" style="text-align: center;">
                    <b>Income From Medicine Sale</b>
                </td>
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
            $grand_nettotal = 0;
            $total_paid = 0;
            $grand_due = 0;
            $k = 1;
            foreach ($query as $value):
                $nettotal = $value->nettotal ?? 0;
                $paid = $value->paid ?? 0;
                $due = is_numeric($value->due) ? (float)$value->due : 0;

                $grand_nettotal += $nettotal;
                $total_paid += $paid;
                $grand_due += $due;
            ?>
                <tr>
                    <td><?php echo $k++; ?></td>
                    <td><?php echo $value->name ?? ''; ?></td>
                    <td><?php echo $value->medicine_sale_invoice_no ?? ''; ?></td>
                    <td><?php echo number_format($nettotal); ?></td>
                    <td><?php echo number_format($paid); ?></td>
                    <td><?php echo number_format($due); ?></td>
                    <td><?php echo date('d-m-Y', strtotime($value->bill_date)); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr style="font-weight: bold;">
                <td colspan="3" style="text-align: right;">Total</td>
                <td><?php echo number_format($grand_nettotal); ?></td>
                <td><?php echo number_format($total_paid); ?></td>
                <td><?php echo number_format($grand_due); ?></td>
                <td></td>
            </tr>
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
                <td colspan="7" style="text-align: center"><b>Medicine Sale Due Payment</b></td>
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
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('due_payment_date >=', $from_date);
                $this->db->where('due_payment_date <=', $to_date);
            } elseif (!empty($from_date)) {
                $this->db->where('due_payment_date', $from_date);
            } elseif (!empty($to_date)) {
                $this->db->where('due_payment_date', $to_date);
            }
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
                <td>Total Collection: <b><?php echo number_format($total_paid + $total_due_payment-$grand_return_amount) ?></b></td>
            </tr>
            <tr>
                <td>In Word: <b><?php echo convertNumberToWord(number_format($total_paid + $total_due_payment- $grand_return_amount)) ?></b></td>
            </tr>
        </table>


    </div>
</div>