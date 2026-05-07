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
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:40px;">


    <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
        <tr>
            <td colspan="6" style="text-align: center">From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
        </tr>
        <tr>
            <td colspan="6"><h3 style="text-align: center">Total,Paid and Due</h3></td>


        </tr>
        <tr>
            <td>Sl</td>
            <td>Supplier</td>
            <td>Amount</td>
            <td>Paid</td>
            <td>Opening Due</td>
            <td>Total Due</td>
        </tr>
        <?php
        error_reporting(0);

        $sql_supplier = '';
        if ($supplier_id != '') {
            $sql_supplier = $this->db
                            ->where('supplier_id', $supplier_id)
                            ->get('supplier')->result();
        } else {
            $sql_supplier = $this->db->select('*')->get('supplier')->result();
        }


        $supplier_name = '';
        $supplier_sl = 1;
        $grand_paid = 0;

        $total_due = 0;
        $grand_amount = 0;
        foreach ($sql_supplier as $sql_value) {
            $due = 0;
            $total_amount = 0;
            $total_paid = 0;
            $supplier_due_payment = 0;
            $supplier_name = $sql_value->supplier_name;
//            if ($from_date != '' && $to_date != '' && $supplier_id != '') {
            $sql_purchase = $this->db
                            ->where('is_deleted', '0')
                            ->where('supplier_id', $sql_value->supplier_id)
                            ->where('date>=', date('Y-m-d', strtotime($from_date)))
                            ->where('date<=', date('Y-m-d', strtotime($to_date)))
                            ->get('purchase')->result();
            // } 


            foreach ($sql_purchase as $sql_purchase_value) {
                $total_amount += $sql_purchase_value->net_total;
                $total_paid += $sql_purchase_value->paid;
            }

            $sql_supplier_payment = '';

            $sql_supplier_payment = $this->db
                            ->where('is_deleted', '0')
                            ->where('supplier_id', $sql_value->supplier_id)
                            ->where('date>=', date('Y-m-d', strtotime($from_date)))
                            ->where('date<=', date('Y-m-d', strtotime($to_date)))
                            ->get('supplier_payment')->result();

            foreach ($sql_supplier_payment as $sql_supplier_payment_value) {
                $supplier_due_payment += $sql_supplier_payment_value->amount;
            }
            $total_paid += $supplier_due_payment;
            ?>
            <tr>
                <td><?php echo $supplier_sl++ ?></td>
                <td><?php echo $supplier_name ?></td>
                <td><?php
                    echo $total_amount;
                    $grand_amount += $total_amount;
                    ?></td>
                <td><?php
                    echo $total_paid;
                    $grand_paid += $total_paid;
                    ?></td>
                <td><?php
                    echo $sql_value->opening_due;
                    ?></td>
                <td><?php
                    //    
                    $due = $total_amount - $total_paid + $sql_value->opening_due;
                    echo $due;
                    $total_due += $due;
                    ?></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td colspan="2" style="text-align:right">Total</td>
            <td><?php echo number_format($grand_amount, 0) ?></td>
            <td><?php echo number_format($grand_paid, 0) ?></td>
            <td>&nbsp;</td>
            <td><?php echo number_format($total_due, 0) ?></td>
        </tr>
    </table>
    <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;margin-top:10px;">
        <tr>
            <td colspan="6"><h3 style="text-align: center">Payment Details</h3></td>


        </tr>
        <tr>
            <td>Sl</td>
            <td>Supplier</td>         
            <td>Amount</td>
            <td>Bank</td>
            <td>Account Number</td>
            <td>Date</td>
        </tr>
        <?php
        $sql_purchase = '';
        if ($from_date != '' && $to_date != '' && $supplier_id != '') {
            $sql_purchase = $this->db
                            ->where('is_deleted', '0')
                            ->where('date>=', date('Y-m-d', strtotime($from_date)))
                            ->where('date<=', date('Y-m-d', strtotime($to_date)))
                            ->where('supplier_id', $sql_value->supplier_id)
                            ->get('purchase')->result();
        } else if ($from_date != '' && $to_date != '' && $supplier_id == '') {
            $sql_purchase = $this->db
                            ->where('is_deleted', '0')
                            ->where('date>=', date('Y-m-d', strtotime($from_date)))
                            ->where('date<=', date('Y-m-d', strtotime($to_date)))
                            ->get('purchase')->result();
        } else if ($from_date == '' && $to_date == '' && $supplier_id != '') {
            $sql_purchase = $this->db
                            ->where('is_deleted', '0')
                            ->where('supplier_id', $sql_value->supplier_id)
                            ->get('purchase')->result();
        } else {
            $sql_purchase = $this->db
                            ->where('is_deleted', '0')
                            ->get('purchase')->result();
        }
        $sl = 1;
        $grand_total = 0;
        foreach ($sql_purchase as $sql_purchase_value) {
            if ($sql_purchase_value->paid == 0)
                continue;
            $sql_supplier = $this->db->where('supplier_id', $sql_purchase_value->supplier_id)
                    ->get('supplier')
                    ->row();
            $sql_bank = $this->db->where('bank_id', $sql_purchase_value->bank_id)
                    ->get('bank')
                    ->row();
            ?>
            <tr>
                <td>
                    <?php echo $sl++ ?>
                </td>
                <td>
                    <?php echo $sql_supplier->supplier_name ?>
                </td>
                <td>
                    <?php
                    echo $sql_purchase_value->paid;
                    $grand_total += $sql_purchase_value->paid;
                    ?>
                </td>


                <td>
                    <?php echo $sql_bank->bank_name ?>
                </td>
                <td>
                    <?php echo $sql_bank->account_number ?>
                </td>
                <td>
                    <?php echo date('d-m-Y', strtotime($sql_purchase_value->date)) ?>
                </td>
            </tr>
            <?php
        }
        ?>
        <?php
        $sql_supplier_payment = '';
        if ($from_date != '' && $to_date != '' && $supplier_id != '') {
            $sql_supplier_payment = $this->db
                            ->where('is_deleted', '0')
                            ->where('date>=', date('Y-m-d', strtotime($from_date)))
                            ->where('date<=', date('Y-m-d', strtotime($to_date)))
                            ->where('supplier_id', $sql_value->supplier_id)
                            ->get('supplier_payment')->result();
        } else if ($from_date != '' && $to_date != '' && $supplier_id == '') {
            $sql_supplier_payment = $this->db
                            ->where('is_deleted', '0')
                            ->where('date>=', date('Y-m-d', strtotime($from_date)))
                            ->where('date<=', date('Y-m-d', strtotime($to_date)))
                            ->get('supplier_payment')->result();
        } else if ($from_date == '' && $to_date == '' && $supplier_id != '') {
            $sql_supplier_payment = $this->db
                            ->where('is_deleted', '0')
                            ->where('supplier_id', $sql_value->supplier_id)
                            ->get('supplier_payment')->result();
        } else {
            $sql_supplier_payment = $this->db
                            ->where('is_deleted', '0')
                            ->get('supplier_payment')->result();
        }


        $sl = 1;
        $grand_total = 0;
        foreach ($sql_supplier_payment as $sql_supplier_payment_value) {
            $sql_supplier = $this->db->where('supplier_id', $sql_supplier_payment_value->supplier_id)
                    ->get('supplier')
                    ->row();
            $sql_bank = $this->db->where('bank_id', $sql_supplier_payment_value->bank_id)
                    ->get('bank')
                    ->row();
            ?>
            <tr>
                <td>
                    <?php echo $sl++ ?>
                </td>
                <td>
                    <?php echo $sql_supplier->supplier_name ?>
                </td>
                <td>
                    <?php
                    echo $sql_supplier_payment_value->amount;
                    $grand_total += $sql_supplier_payment_value->amount;
                    ?>
                </td>


                <td>
                    <?php echo $sql_bank->bank_name ?>
                </td>
                <td>
                    <?php echo $sql_bank->account_number ?>
                </td>
                <td>
                    <?php echo date('d-m-Y', strtotime($sql_supplier_payment_value->date)) ?>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>

            <td></td>
            <td colspan="">Total</td>
            <td><?php echo number_format($grand_total, 3) ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
</div>