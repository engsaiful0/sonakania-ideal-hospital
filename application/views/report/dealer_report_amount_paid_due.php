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
<div id="report" id="report" style="width: 95%;margin:0 auto;margin-left:45px;margin-top:40px;">
    <table border="1" class="table table-bordered table-hover" style="width: 98%;;margin-top:10px;color:black;border-collapse:collapse;">
        <tr>
            <td colspan="5" style="text-align: center">Dealer Amount,Paid,Due Report</td>
        </tr>
        <tr>
            <td>Sl</td>
            <td>Dealer</td>
            <td>Amount</td>
            <td>Paid</td>  
            <td>Due</td>
        </tr>
        <?php
        $query = '';
        $sl = 1;
        $query = $this->db->select('*')
                ->get('dealer')
                ->result();
        $grand_amount = 0;
        $grand_paid = 0;
        $grand_due = 0;
        foreach ($query as $query_value) {
            ?>
            <tr>
                <td>
                    <?php echo $sl++ ?>
                </td>
                <td>
                    <?php echo $query_value->dealer_name ?>
                </td>
                <td>
                    <?php
                    echo $query_value->total_amount;
                    $grand_amount += $query_value->total_amount;
                    ?>
                </td>

                <td>
                    <?php
                    echo $query_value->paid_amount;
                    $grand_paid += $query_value->paid_amount;
                    ?>
                </td>
                <td>
                    <?php
                    echo $query_value->due_amount;
                    $grand_due += $query_value->due_amount;
                    ?>
                </td>

            </tr>
            <?php
        }
        ?>
        <tr>        
            <td></td>
            <td>Total</td>
            <td><?php echo number_format($grand_amount, 0) ?></td>
            <td><?php echo number_format($grand_paid, 0) ?></td>
            <td><?php echo number_format($grand_due, 0) ?></td>
        </tr>
    </table>
</div>