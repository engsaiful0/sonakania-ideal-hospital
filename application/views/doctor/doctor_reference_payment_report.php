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


    <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
        <tr>
            <td colspan="7" style="text-align: center;font-weight: bold ">Doctor reference & paid commission report</td>
        </tr>
        <tr>
            <td>Sl</td>
            <td>Doctor</td>
            <td>Commission Rate</td>
            <td>Total Amount</td>
            <td>Total Commission</td>
            <td>Paid Commission</td>
            <td>Due Commission</td>
        </tr>
        <?php
        error_reporting(0);
        $query = '';

        $query = $this->db
                ->get('doctor')
                ->result();


        $sl = 1;
        $grand_total = 0;
        $grand_net_total = 0;
        $grand_paid_amount = 0;
        $grand_due_commission = 0;
        $total_grand_commission = 0;
        foreach ($query as $query_value) {

            $commission_rate = explode('%', $query_value->commission);
            $commission_rate = intval($commission_rate[0]);

            $doctor_commission_payment = $this->db->select_sum('paid_amount', 'paid_amount')
                            ->where('doctor_id', $query_value->doctor_id)
                            ->where('is_deleted', '0')
                            ->get('doctor_commission_payment')->result();
            $total_commission_paid = $doctor_commission_payment[0]->paid_amount;

            $patient_test_entry = $this->db->select_sum('net_total', 'net_total')
                            ->where('is_deleted', '0')
                            ->where('doctor_id',  $query_value->doctor_id)
                            ->get('patient_test_entry')->result();
            
            $commission=0;
            $commission=$patient_test_entry[0]->net_total* ($commission_rate / 100);
     
            ?>
            <tr>
                <td>
                    <?php echo $sl++ ?>
                </td>
                <td>
                    <?php echo $query_value->doctor_name ?>
                </td>
                <td>
                    <?php echo $query_value->commission ?>
                </td>
                <td>
                    <?php
                    echo $value->net_total;
                    $grand_net_total += $value->net_total;
                    ?>
                </td>
                <td>
                    <?php
                    echo $commission;
                    $total_grand_commission += $commission;
                    ?>
                </td>
                <td>
                    <?php
                    echo $total_commission_paid;
                    $grand_paid_amount += $total_commission_paid;
                    ?>
                </td>
                <td>
                    <?php
                    echo $grand_commission - $total_commission_paid;
                    $grand_due_commission += $grand_commission - $total_commission_paid;
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>

            <td colspan="3" style="text-align: right">Total</td>
            <td><?php echo number_format($grand_net_total, 0) ?></td>
            <td><?php echo number_format($total_grand_commission, 0) ?></td>
            <td><?php echo number_format($grand_paid_amount, 0) ?></td>
            <td><?php echo number_format($grand_due_commission, 0) ?></td>
       
        </tr>
    </table>
</div>