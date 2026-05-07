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
            <td colspan="6" style="text-align: center">Salary Sheet of Month <b><?php echo $month ?></b> Session <b><?php echo $session; ?></b></td>
        </tr>
        <tr>
            <td>Sl</td>
            <td>Employee</td>
            <td>Monthly Salary</td>
            <td>Taken</td>
            <td>Payable</td>
            <td>Signature</td>
        </tr>
        <?php
        $query = 0;


        $sql_employee = $this->db->select('*')
                ->get('employee_payroll')
                ->result();

        $sl = 1;
        $grand_total = 0;
        $total_salary = 0;
        $total_payable = 0;

        foreach ($sql_employee as $sql_employee_query_value) {
            $employee = $this->db->where('employee_id', $sql_employee_query_value->employee_id)
                    ->get('employee')
                    ->row();
            $query_employee = $this->db
                    ->where('employee_id', $sql_employee_query_value->employee_id)
                    ->where('month', $month)
                    ->where('session', $session)
                    ->get('employee_salary')
                    ->result();
            $total_taken = 0;
            foreach ($query_employee as $query_employee_value) {
                $total_taken += $query_employee_value->amount;
            }
            ?>
            <tr>
                <td>
                    <?php echo $sl++ ?>
                </td>
                <td>
                    <?php echo $employee->employee_name ?>
                </td>
                <td>
                    <?php
                    echo $sql_employee_query_value->total;

                    $total_salary += $sql_employee_query_value->total;
                    ?>
                </td>
                <td>
                    <?php
                    echo $total_taken;
                    $grand_total += $total_taken;
                    ?>
                </td>
                <td>
                    <?php
                    echo $sql_employee_query_value->total - $total_taken;
                    $total_payable += ($sql_employee_query_value->total - $total_taken);
                    ?>
                </td>
                <td style=" height: 30px;">

                </td>

            </tr>
            <?php
        }
        ?>
        <tr>


            <td colspan="2" style="text-align: right">Total</td>
            <td><?php echo number_format($total_salary, 0) ?></td>
            <td><?php echo number_format($grand_total, 0) ?></td>
            <td><?php echo number_format($total_payable, 0) ?></td>
            <td></td>

        </tr>
    </table>
</div>