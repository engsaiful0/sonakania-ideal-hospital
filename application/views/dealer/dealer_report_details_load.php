<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #report, #report * {
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
        <button onclick="window.print()" class="btn btn-primary" >Print</button>
    </div>
</div>
<div id="report">


    <table border="1" class="table table-bordered table-hover" style="width: 98%;">

        <tr>
            <td colspan="6"><h3 style="text-align: center">Dealer Transaction Report</h3></td>
        </tr>
        <tr>
            <td>Sl</td>
            <td>Dealer</td>
            <td>Date</td>
            <td>Amount</td>
            <!--<td>Due</td>-->
            <td>Paid</td>
            <td>Current Due</td>
        </tr>
        <?php
        error_reporting(0);
        $dealer = '';



        $supplier_name = '';
        $sl = 1;
        $grand_net_total = 0;
        $grand_paid = 0;
        $grand_due = 0;

        $date = date('Y-m-d', strtotime($from_date));
        $from = strtotime(date('Y-m-d', strtotime($from_date)));
        $to = strtotime(date('Y-m-d', strtotime($to_date)));

        $diff = abs($from - $to) / (60 * 60 * 24);

        for ($i = 1; $i <= $diff + 1; $i++) {
            $dealer_sells = '';

            // $count_sell=0;
            // $dealer_sells_payment=0;
            if ($dealer_id != '') {
                $dealer_sells = $this->db
                                ->where('date', $date)
                                ->where('dealer_id', $dealer_id)
                                ->get('whole_customer_sells')->result();
            } else {
                $dealer_sells = $this->db->where('date', $date)
                                ->get('whole_customer_sells')->result();
            }
            $dealer_sells_payment = '';
            if ($dealer_id != '') {
                $dealer_sells_payment = $this->db
                                ->order_by('whole_customer_payment_id', 'asc')
                                ->where('date', $date)
                                ->where('dealer_id', $dealer_id)
                                ->get('whole_customer_payment')->result();
            } else {
                $dealer_sells_payment = $this->db
                                ->order_by('whole_customer_payment_id', 'asc')
                                ->where('date', $date)
                                ->get('whole_customer_payment')->result();
            }
            $count_sell = count($dealer_sells);
            $count_payment = count($dealer_sells_payment);
            // print_r($count_sell . '-' . $count_payment . '<br>');
            if ($count_sell == 0 && $count_payment == 0) {
                $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                continue;
            }

            $due = 0;
            $paid = 0;
            foreach ($dealer_sells_payment as $dealer_sells_payment_value) {
                $dealer = $this->db->where('dealer_id', $dealer_sells_payment_value->dealer_id)->get('dealer')->row();
                $paid = $dealer_sells_payment_value->paid;
                $grand_paid += $paid;
                $due = $dealer_sells_payment_value->current_due;
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td><?php echo $dealer->dealer_name ?></td>
                    <td><?php echo date('d-m-Y', strtotime($date)) ?></td>

                    <td><?php
                        $total_sell_amount = 0;
                        echo $dealer_sells_payment_value->net_total;
                        ?></td>
                    <td><?php
                        echo $paid;
                        ?></td>

                    <td><?php
                        if ($due != 0) {
                            $grand_due = $due;
                        }
                        echo $due;
                        ?></td>
                </tr>
                <?php
            }
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }
        ?>
      <tr>
            <td colspan="3" style="text-align: right">Total Amount</td>
            <td><?php echo number_format($dealer->total_amount, 0) ?></td>
            <td><?php echo number_format($grand_paid, 0) ?></td>
            <td><?php echo number_format($dealer->due_amount, 0) ?></td>
        </tr>
    </table>

</div>