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



        .p1 {line-height: 80%!important; }
    }
    .p1 {line-height: 80%!important; }
</style>

<div class="row">
    <div class="col-md-12"> 
        <button onclick="window.print()" id="sumbit_button" class="btn btn-primary" >Print</button>
    </div>

</div>
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:50px;">

    <?php
    error_reporting(0);
    $opd_doctor_commision_payment = $this->db->where('opd_doctor_commision_payment_id', $opd_doctor_commision_payment_id)
            ->get('opd_doctor_commision_payment')
            ->row();

    $doctor = $this->db->where('doctor_id', $opd_doctor_commision_payment->doctor_id)->get('doctor')->row();
    $department = $this->db->where('department_id', $doctor->department_id)->get('department')->row();
    ?>
    <p style="font-size: 20px;font-weight: bold;text-align: center "><u>OPD Patient Payment Voucher</u></p>
    <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">

        <tr>
            <td>Doctor Name</td>
            <td>
                <?php echo $doctor->doctor_name ?>
            </td>

            <td>Department</td>
            <td>
                <?php echo $department->department_name ?>
            </td>
        </tr>
        <tr>
            <td>Total Patients</td>
            <td>
                <?php echo $opd_doctor_commision_payment->total_patients ?>
            </td>

            <td>Total Visiting Fee</td>
            <td>
                <?php echo $opd_doctor_commision_payment->total_visitiong_fee ?>
            </td>
        </tr>
        <tr>

            <td>Commission Rate</td>
            <td>
                <?php echo $opd_doctor_commision_payment->percentage_of_commission ?>%
            </td>

            <td>Payable Amount</td>
            <td>
                <?php echo $opd_doctor_commision_payment->total_doctors_commission ?>
            </td>

        </tr>
        <tr>

            <td>Visiting Date</td>
            <td>
                &nbsp;
                <?php echo date('d-m-Y', strtotime($opd_doctor_commision_payment->from_date)) . '&nbsp;To&nbsp;' . date('d-m-Y', strtotime($opd_doctor_commision_payment->to_date)) ?>  

            </td>
            <td>Payment Date</td>
            <td> <?php echo date('d-m-Y', strtotime($opd_doctor_commision_payment->paid_date)) ?></td>
        </tr>  

    </table>
    <table style="width: 100%;margin-top: 50px; ">
        <tr>
            <td style="width: 50% ">
                <p style="text-align: center;">_________<br>Doctor</p>
            </td>
            <td style="width: 50% ">
                <p style="text-align: center;">_________<br>Manager</p>
            </td>
        </tr>
    </table>

</div>

