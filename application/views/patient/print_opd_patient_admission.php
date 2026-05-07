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
<div id="report" style="width: 90%;margin:0 auto;margin-left:40px;margin-right:40px;margin-top:120px;height: 300px;">

    <?php
    error_reporting(0);
    $opd_patient = $this->db->where('opd_patient_id', $opd_patient_id)
            ->get('opd_patient')
            ->row();

    $doctor = $this->db->where('doctor_id', $opd_patient->doctor_id)->get('doctor')->row();
    $department = $this->db->where('department_id', $opd_patient->department_id)->get('department')->row();

    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    ?>
<p style="font-size: 20px;font-weight: bold;text-align: center "><?php echo $department->department_name ?></p>
    <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
       
        <tr>
            <td>Patient Name</td>
            <td>
                <b><?php echo $opd_patient->opd_patient_name ?></b>
            </td>

            <td>Gender</td>
            <td>
                <b><?php echo $opd_patient->gender ?></b>
            </td>
        </tr>
        <tr>
            <td>Age</td>
            <td>
                <b><?php echo $opd_patient->age ?></b>
            </td>

            <td>Mobile</td>
            <td>
                <b> <?php echo $opd_patient->mobile_number ?></b>
            </td>
        </tr>
        <tr>

            <td>Doctor</td>
            <td>
                <b><?php echo $doctor->doctor_name . '<br>' . $doctor->degree ?></b>
            </td>

            <td>Serial No</td>
            <td>
                <b><?php echo $opd_patient->serial_numaber ?></b>
            </td>

        </tr>
        <tr>

            <td>Visiting Date & Time</td>
            <td>
                <b>
                    <?php echo date('d-m-Y', strtotime($opd_patient->visiting_date)) . ':' . $opd_patient->visiting_time ?>  
                </b> 
            </td>
            <td>Visiting Fee</td>
            <td>  <b><?php echo $opd_patient->payable?></b></td>
        </tr>  

    </table>
    <p style="text-align: right;padding-right: 100px;padding-top: 100px; ">___________<br>Manager</p>
  
</div>

