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
            margin: 20px;
            /* Reset margins for printing */
            padding: 0;
            /* Reset padding for printing */
            width: 100%;
            /* Ensure it fits within the page */
        }

        .p1 {
            line-height: 80% !important;
        }

        /* Remove extra spacing that may cause a second page */
        html,
        body {
            margin: 0;
            padding: 0;
        }

        table {
            page-break-inside: avoid;
            /* Prevent table from breaking into multiple pages */
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
    $doctor_serial = '';
    if ($this->session->userdata('doctor_serial_id')) {
        $doctor_serial_id = $this->session->userdata('doctor_serial_id');
        $doctor_serial = $this->db->where('doctor_serial_id', $doctor_serial_id)
            ->get('doctor_serial')
            ->row();
    } else {
        $doctor_serial = $this->db->where('doctor_serial_id', $doctor_serial_id)
            ->get('doctor_serial')
            ->row();
    }

    $doctor = $this->db->where('doctor_id', $doctor_serial->doctor_id)->get('doctor')->row();
    $department = $this->db->where('department_id', $doctor_serial->department_id)->get('department')->row();

    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    $user = getUserById($doctor_serial->user_id);
    ?>
    <div class="" style="width: 100%;margin-bottom: 10px;">
        <div style="width: 15%;float: left;margin-top:20px">
            <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
        </div>
        <div style="width: 70%;float: left;text-align: center">
            <p style="text-align: center"><span style="text-align: center;font-size: 20px;text-align: center "> <?php echo $compnay->company_name ?><br><?php echo $compnay->address ?></span><br>
                <span style="text-align: center">
                    Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                </span>
            </p>
        </div>
        <div style="width: 15%;float: left;margin-top:20px">
            <img src="<?php echo base_url('DoctorSerialController/set_barcode/' . $doctor_serial->doctor_serial_unique_id); ?>" alt="Barcode">
        </div>
    </div>
    <p style="font-size: 20px;font-weight: bold;text-align: center;clear:left; "><?php echo $department->department_name ?></p>
    <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
        <tr>
            <td colspan="4" style="text-align:center;font-weight:bold;font-size:20px;"><u>Doctor Serial</u></td>

        </tr>
        <tr>
            <td>Patient Name</td>
            <td>
                <b><?php echo $doctor_serial->patient_name ?></b>
            </td>

            <td>Gender</td>
            <td>
                <b><?php echo $doctor_serial->gender ?></b>
            </td>
        </tr>
        <tr>
            <td>Age</td>
            <td>
                <b><?php
                    $age_parts = [];

                    if ($doctor_serial->age_year > 0) {
                        $age_parts[] = $doctor_serial->age_year . ' ' . ($doctor_serial->age_year == 1 ? 'Year' : 'Years');
                    }

                    if ($doctor_serial->age_month > 0) {
                        $age_parts[] = $doctor_serial->age_month . ' ' . ($doctor_serial->age_month == 1 ? 'Month' : 'Months');
                    }

                    if ($doctor_serial->age_day > 0) {
                        $age_parts[] = $doctor_serial->age_day . ' ' . ($doctor_serial->age_day == 1 ? 'Day' : 'Days');
                    }

                    echo implode(' ', $age_parts);
                    ?></b>
            </td>

            <td>Mobile</td>
            <td>
                <b> <?php echo $doctor_serial->mobile_number ?></b>
            </td>
        </tr>
        <tr>

            <td>Doctor</td>
            <td>
                <b><?php echo $doctor->doctor_name . '<br>' . $doctor->degree ?></b>
            </td>

            <td>Serial No</td>
            <td>
                <b><?php echo $doctor_serial->serial_numaber ?></b>
            </td>
        </tr>
        <tr>
            <td>Visiting Date</td>
            <td>
                <b>
                    <?php echo date('d-m-Y', strtotime($doctor_serial->visiting_date))  ?>
                </b>
            </td>
            <td> Visiting Time</td>
            <td>
                <b>
                    <?php echo $doctor_serial->visiting_time ?>
                </b>
            </td>
        </tr>
        
        

    </table>
    <div id="entry_by">
        <div style="width: 50%;float:left">
            <p style="text-align: left;">Software By:<span style="font-weight:bold"> Bijoylab, www.bijoylab.com</span></p>
        </div>
        <div style="width: 30%;float:right">
            <p style="text-align: right;font-weight: bold;">Entry By: <?php echo  $user->name ?? "" ?></p>
        </div>
    </div>

</div>