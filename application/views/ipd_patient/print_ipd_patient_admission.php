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
    $patient = '';
    if ($this->session->userdata('print_ipd_patient_id')) {
        $print_ipd_patient_id = $this->session->userdata('print_ipd_patient_id');
        $patient = $this->db->where('ipd_patient_id', $print_ipd_patient_id)
            ->get('ipd_patient')
            ->row();
    } else {
        $patient = $this->db->where('ipd_patient_id', $ipd_patient_id)
            ->get('ipd_patient')
            ->row();
    }

    $cabin = $this->db->where('cabin_id', $patient->cabin_id)->get('cabin')->row();
    $ward = $this->db->where('ward_id', $patient->ward_id)->get('ward')->row();
    $bed = $this->db->where('bed_id', $patient->bed_id)->get('bed')->row();
    $doctor = $this->db->where('doctor_id', $patient->reference_doctor_id)->get('doctor')->row();
    ?>
    <?php
    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    ?>
    <div class="" style="width: 100%;margin-bottom: 10px;">
        <div style="width: 15%;float: left;margin-top:20px">
            <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
        </div>
        <div style="width: 70%;float: left;text-align: center">
            <p style="text-align: center"><span style="text-align: center;font-size: 20px;text-align: center "> <?php echo $compnay->company_name ?></span><br><?php echo $compnay->address ?></span><br>
                <span style="text-align: center">
                    Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                </span>
            </p>
        </div>
        <div style="width: 15%;float: left;margin-top:20px">
            <img src="<?php echo base_url('IpdPatientController/set_barcode/' . $patient->patient_unique_id); ?>" alt="Barcode">
        </div>
    </div>
    <div class="name" style="width: 100%;margin-bottom: 10px;">
        <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
            <tr>
                <td colspan="4" style="text-align:center;font-weight:bold;font-size:20px;"><u>IPD Patient Addmission</u></td>
            </tr>
            <tr>
                <td>Patient Name</td>
                <td>
                    <b><?php echo $patient->patient_name ?></b>
                </td>

                <td>Gender</td>
                <td>
                    <b><?php echo $patient->gender ?></b>
                </td>
            </tr>
            <tr>
                <td>Age</td>
                <td>
                    <b>
                        <?php if ($patient->age_year > 0): ?>
                            <?php echo $patient->age_year . ' ' . ($patient->age_year == 1 ? 'Year' : 'Years'); ?>
                        <?php endif; ?>

                        <?php if ($patient->age_month > 0): ?>
                            <?php echo ' ' . $patient->age_month . ' ' . ($patient->age_month == 1 ? 'Month' : 'Months'); ?>
                        <?php endif; ?>

                        <?php if ($patient->age_day > 0): ?>
                            <?php echo ' ' . $patient->age_day . ' ' . ($patient->age_day == 1 ? 'Day' : 'Days'); ?>
                        <?php endif; ?>
                    </b>

                </td>

                <td>Mobile</td>
                <td>
                    <b> <?php echo $patient->mobile_number ?></b>
                </td>
            </tr>
            <tr>

                <td>Reference</td>
                <td>
                    <?php
                    $reference_doctor = $this->db->where('doctor_id', $patient->reference_doctor_id)->get('doctor')->row();
                    $under_doctor = $this->db->where('doctor_id', $patient->under_doctor_id)->get('doctor')->row();
                    $reference_media = $this->db->where('reference_media_id', $patient->reference_media_id)->get('reference_media')->row();
                    $reference_director = $this->db->where('director_id', $patient->reference_director_id)->get('director')->row();
                    $reference_employee = $this->db->where('employee_id', $patient->reference_employee_id)->get('employee')->row();

                    $ward = $this->db->where('ward_id', $patient->ward_id)->get('ward')->row();
                    $bed = $this->db->where('bed_id', $patient->bed_id)->get('bed')->row();
                    $cabin = $this->db->where('cabin_id', $patient->cabin_id)->get('cabin')->row();

$user = getUserById($patient->user_id);
                    if ($reference_doctor != '') {
                    ?>
                        Doctor:<b> <?php echo $reference_doctor->doctor_name ?></b><br>
                    <?php
                    }
                    ?>
                    <?php
                    if ($reference_media != '') {
                    ?>
                        Media:<b> <?php echo $reference_media->reference_media_name ?></b><br>
                    <?php
                    }
                    ?>
                    <?php
                    if ($reference_director != '') {
                    ?>
                        Director:<b> <?php echo $reference_director->name ?></b><br>
                    <?php
                    }
                    if ($reference_employee != '') {
                    ?>
                        Employee:<b> <?php echo $reference_employee->employee_name ?></b><br>
                    <?php
                    }
                    ?>
                </td>

                <td>Under Doctor</td>
                <td>
                    <b><?php echo $under_doctor->doctor_name ?></b>
                </td>

            </tr>
            <tr>
                <?php
                if ($patient->cabin_id != '') {
                ?>
                    <td>Cabin Number</td>
                    <td>
                        <b><?php echo $cabin->cabin_number ?></b>
                    </td>
                <?php
                } elseif ($patient->ward_id != '') {
                ?>
                    <td>Ward/Bed Number</td>
                    <td>
                       Bed: <b><?php echo $bed->bed_number ?></b>
                    </td>
                <?php
                }
                ?>

                <td>Patient ID</td>
                <td>
                    <b><?php echo $patient->patient_unique_id ?></b>
                </td>

              </tr>
              <tr>

                <td>Admit Date & Time</td>
                <td>
                    <b>
                        <?php echo date('d-m-Y', strtotime($patient->date)) . ':' . $patient->admission_time ?>
                    </b>
                </td>


                <td>Guardian</td>
                <td>
                  <b><?php echo $patient->gurdian_name ?></b>

                </td>
</tr>
            <tr>
                <td>Address</td>
                <td> <?php echo $patient->address ?></td>

                <td>Relation</td>
                <td>
                    <b><?php echo $patient->relation ?></b>
                </td>
            </tr>
<tr>
  <td><?php echo $patient->father_or_husband_selection ?> Name</td>
  <td> <?php echo $patient->father_or_husband_name ?></td>

                <td>Paid</td>
                <td>
                    <b><?php echo $patient->paid_amount ?></b>
                </td>
            </tr>


        </table>
    </div>
    <div id="entry_by">
        <div style="width: 50%;float:left">
            <p style="text-align: left;">Software By:<span style="font-weight:bold"> Bijoylab, www.bijoylab.com</span></p>
        </div>
        <div style="width: 30%;float:right">
            <p style="text-align: right;font-weight: bold;">Entry By: <?php echo  $user->name ?? "" ?></p>
        </div>
    </div>

</div>
