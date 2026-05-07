<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #report,
        #report * {
            visibility: visible;
        }

        #report {
            position: absolute;
            left: 0;
            top: 0;
            margin: 20px; /* Reset margins for printing */
            padding: 0; /* Reset padding for printing */
            width: 100%; /* Ensure it fits within the page */
        }

        .p1 {
            line-height: 80% !important;
        }

        /* Remove extra spacing that may cause a second page */
        html, body {
            margin: 0;
            padding: 0;
        }

        table {
            page-break-inside: avoid; /* Prevent table from breaking into multiple pages */
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
<div id="report" style="margin: 0 auto; margin-top: 20px;">
    <?php
    error_reporting(0);
    $opd_patient = '';
    if ($this->session->userdata('opd_patient_id')) {
        $opd_patient_id = $this->session->userdata('opd_patient_id');
        $opd_patient = $this->db->where('opd_patient_id', $opd_patient_id)
            ->get('opd_patient')
            ->row();
    } else {
        $opd_patient = $this->db->where('opd_patient_id', $opd_patient_id)
            ->get('opd_patient')
            ->row();
    }

    $doctor = $this->db->where('doctor_id', $opd_patient->doctor_id)->get('doctor')->row();
    $department = $this->db->where('department_id', $opd_patient->department_id)->get('department')->row();

    $compnay = $this->db->where('company_id', '1')->get('company')->row();
   
    ?>
    <div class="" style="width: 100%;margin-bottom: 10px;">
        <div style="width: 20%;float: left;">
            <img style="width:90%;padding-left: 30px;margin-top:20px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
        </div>

        <div style="width: 80%;float: left;margin-bottom: 20px;text-align: left">
            <div class="content">
                <p style="text-align: center"><span style="text-align: center;font-size: 20px;text-align: center "> <?php echo $compnay->company_name ?></span><br>
                    <span style="text-align: center"> Mobile: <?php echo $compnay->mobile ?><br>
                        Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                    </span>
                </p>
            </div>
        </div>
        <img src="<?php echo base_url('OpdPatientController/set_barcode/' . $opd_patient->opd_patient_unique_id); ?>" alt="Barcode">
    </div>
    <p style="font-size: 20px;font-weight: bold;text-align: center "><?php echo $department->department_name ?></p>
    <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
        <tr>
            <td colspan="4" style="text-align:center;font-weight:bold">Out Patient</td>

        </tr>
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
                <b><?php echo $opd_patient->age.' '.$opd_patient->years_or_days ?></b> 
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
            <td> <b><?php echo $doctor->new_patient_fee ?></b></td>
        </tr>
        <tr>

            <td>Visiting Date & Time</td>
            <td>
                <b>
                    <?php echo date('d-m-Y', strtotime($opd_patient->visiting_date)) . ':' . $opd_patient->visiting_time ?>
                </b>
            </td>
            <td>Visiting Fee</td>
            <td> <b><?php echo $doctor->new_patient_fee ?></b></td>
        </tr>

    </table>
    <p style="text-align: right;padding-right: 100px;padding-top: 100px; ">___________<br>Manager</p>

</div>