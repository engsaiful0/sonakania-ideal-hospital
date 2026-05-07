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


    <table class="table table-bordered table-hover" style="width: 98%;">
        <tr>
            <td colspan="4" style="text-align: center">Month <b><?php echo $month ?></b>Year <b><?php echo $year; ?></b></td>
        </tr>
        <tr>
            <td>Sl</td>
            <td>Employee</td>
            <td>Amount</td>
            <td>Date</td>
        </tr>
        <?php
        $query = 0;
        if ($employee_id != '') {
            $query = $this->db
                    ->where('month', $month)
                    ->where('session', $session)
                    ->where('employee_id', $employee_id)
                    ->get('employee_salary')
                    ->result();
        } else {
            $query = $this->db
                    ->where('month', $month)
                    ->where('session', $session)
                    ->get('employee_salary')
                    ->result();
        }

        $sl = 1;
        $grand_total = 0;
        foreach ($query as $query_value) {
            $sql_employee = $this->db->where('employee_id', $query_value->employee_id)
                    ->get('employee')
                    ->row();
            ?>
            <tr>
                <td>
                    <?php echo $sl++ ?>
                </td>
                <td>
                    <?php echo $sql_employee->employee_name ?>
                </td>

                <td>
                    <?php
                    echo $query_value->amount;
                    $grand_total += $query_value->amount;
                    ?>
                </td>
                <td>
                    <?php echo date('d-m-Y', strtotime($query_value->date)) ?>
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
        </tr>
    </table>
</div>