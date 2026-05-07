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

        .report_footer {
            margin-top: 300px !important;
        }

        .p1 {
            line-height: 80% !important;
        }
    }

    .p1 {
        line-height: 80% !important;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <button onclick="window.print()" id="sumbit_button" class="btn btn-primary">Print</button>
    </div>

</div>
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:40px;">
    <?php
    error_reporting(0);

    $salaries = '';

    $print_salary_id = $this->session->userdata('print_salary_id');
    $salary = $this->db->where('salary_id', $print_salary_id)
        ->get('salaries')
        ->row();

    $salary_details = $this->db->where('salary_id', $print_salary_id)
        ->get('salary_details')
        ->result();

    $month = $this->db->where('month_id', $salary->month_id)->get('month')->row();
    $year = $this->db->where('year_id', $salary->year_id)->get('year')->row();

    $user = getUserById($salary->user_id);
    $company = getCompany();
    ?>
    <div class="" style="width: 100%;margin-bottom: 10px;">
        <div style="width: 15%;float: left;margin-top:20px">
            <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $company->logo ?>">
        </div>
        <div style="width: 85%;float: left;text-align: center">
            <p style="text-align: center"><span style="text-align: center;font-size: 20px;text-align: center "> <?php echo $company->company_name ?></span><br>
                <span style="text-align: center">
                    Email: <?php echo $company->email ?>,Web:<?php echo $company->web ?>
                </span>
            </p>
        </div>
       
    </div>

    <div class="name" style="width: 100%;margin-bottom: 10px;height:auto">
        <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
            <tr>
                <td colspan="6">
                    <h2 style="font-weight: bold;text-align: center ">Salary Sheet</h2>
                </td>
            </tr>
            <tr>
                <td>Year</td>
                <td>
                    <b><?php echo $year->name ?></b>
                </td>
                <td>Month</td>
                <td>
                    <b><?php echo $month->name ?></b>
                </td>
                <td>Date</td>
                <td><b>
                        <?php echo date('d-m-Y', strtotime($salary->date)) . ' ' . $patient->admission_time ?>
                    </b></td>
            </tr>
        </table>

        <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">

            <tr>
                <td>#</td>
                <td>Name</td>
                <td>Gross<br>Salary</td>
                <td>W.<br>Days</td>
                <td>Daily<br>Salary</td>
                <td>Over<br>Time</td>
                <td>OT<br>Allow.</td>
                <td>Other<br>Allow.</td>
                <td>Total<br>Salary</td>
                <td>Absent</td>
                <td>Late<br>Day</td>
                <td>Deduct.</td>
                <td>Payable</td>
                <td>Sign</td>
            </tr>
            <?php
            $serial = 1;
            $total_payable = 0;
            $total_salary = 0;
            $total_deduction = 0;
            $total_ot_allowance = 0;
            $total_other_allowance = 0;
            foreach ($salary_details as $value) {
                $employee = getEmployee($value->employee_id);
            ?>
                <tr>
                    <td><?php echo $serial++ ?></td>
                    <td><?php echo $employee->employee_name; ?></td>
                    <td><?php echo $value->gross_salary; ?></td>
                    <td><?php echo $value->working_days; ?></td>
                    <td><?php echo $value->daily_salary; ?></td>
                    <td><?php echo $value->over_time; ?></td>
                    <td><?php echo $value->ot_allowance;
                        $total_ot_allowance += $value->ot_allowance; ?></td>
                    <td><?php echo $value->other_allowance;
                        $total_other_allowance += $value->other_allowance; ?></td>
                    <td><?php echo $value->total_salary;
                        $total_salary += $value->total_salary; ?></td>
                    <td><?php echo $value->absent; ?></td>
                    <td><?php echo $value->late_day; ?></td>
                    <td><?php echo $value->deduction;
                        $total_deduction += $value->deduction; ?></td>
                    <td><?php echo $value->payable;
                        $total_payable += $value->payable; ?></td>
                        <td style="width: 100px;"></td>
                </tr>

            <?php
            }
            ?>
            <tr>
                <td colSpan="6" style="text-align:right">Total</td>
                <td><?php echo $total_ot_allowance ?></td>
                <td><?php echo $total_other_allowance ?></td>
                <td><?php echo $total_salary ?></td>
                <td></td>
                <td></td>
                <td><?php echo $total_deduction ?></td>
                <td><?php echo $total_payable ?></td>
            </tr>
        </table>

    </div>
    <!-- <div class="report_footer" style="clear:left;margin-top: 200px!important; ">
        <div style="width: 25%;float:left">
            <p style="text-align: left; ">___________<br>Accounts</p>
        </div>
        <div style="width: 25%;float:left">
            <p style="text-align: left; ">___________<br>Bill Received</p>
        </div>
        <div style="width: 25%;float:left">
            <p style="text-align: left; ">________________<br>Discounted By</p>
        </div>
        <div style="width: 25%;float:left">
            <p style="text-align: left; ">________________<br>Authorized</p>
        </div>
    </div> -->
    <div style="margin-top: 20px; ">
        <div style="width: 30%;float:right">
            <p style="text-align: right;">Entry By: <?php echo  $user->name ?? "" ?></p>
        </div>
    </div>

</div>