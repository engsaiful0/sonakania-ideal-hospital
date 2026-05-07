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
    $user = getUserById($opd_patient->user_id);
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
            <img src="<?php echo base_url('OpdPatientController/set_barcode/' . $opd_patient->opd_patient_unique_id); ?>" alt="Barcode">
        </div>
    </div>
    <p style="font-size: 20px;font-weight: bold;text-align: center;clear:left; "><?php echo $department->department_name ?></p>
    <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
        <tr>
            <td colspan="4" style="text-align:center;font-weight:bold;font-size:20px;"><u>Out Patient Department</u></td>

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
                <b><?php
                    $age_parts = [];

                    if ($opd_patient->age_year > 0) {
                        $age_parts[] = $opd_patient->age_year . ' ' . ($opd_patient->age_year == 1 ? 'Year' : 'Years');
                    }

                    if ($opd_patient->age_month > 0) {
                        $age_parts[] = $opd_patient->age_month . ' ' . ($opd_patient->age_month == 1 ? 'Month' : 'Months');
                    }

                    if ($opd_patient->age_day > 0) {
                        $age_parts[] = $opd_patient->age_day . ' ' . ($opd_patient->age_day == 1 ? 'Day' : 'Days');
                    }

                    echo implode(' ', $age_parts);
                    ?></b>
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
            <td> <b><?php echo $opd_patient->visiting_fee ?></b></td>
        </tr>
        <tr>
            <td>Discount</td>

            <td>
                <b><?php echo $opd_patient->discount ?></b>
            </td>
            <td>Payable</td>
            <td> <b><?php echo $opd_patient->payable ?></b></td>
        </tr>
        <tr>
            <td>Discount Reference</td>

            <td>
                <b><?php echo $opd_patient->discount_reference ?></b>
            </td>
            <td></td>
            <td></td>
        </tr>
        <?php
        if ($opd_patient->status == 'Returned') {

        ?>
            <tr style="background-color:#005825;">
                <td colspan="4" style="text-align:center;font-weight:bold;color:white">Returned</td>
            </tr>
            <tr>
                <td>Return Date</td>
                <td><b><?php echo date('d-m-Y', strtotime($opd_patient->return_date)) ?></b></td>
                <td>Return Amount</td>
                <td><b><?php echo $opd_patient->returnable_amount ?></b></td>
            </tr>
            <tr>
                <td>Return Reason</td>
                <td colspan="3"><b><?php echo $opd_patient->return_reason ?></b></td>
            </tr>
        <?php
        }
        ?>

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