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
    $patient='';
    if ($this->session->userdata('print_patient_id')) {
        $patient_id = $this->session->userdata('print_patient_id');
        $patient = $this->db->where('patient_id', $patient_id)
        ->get('patient')
        ->row();
    }else{
        $patient = $this->db->where('patient_id', $patient_id)
        ->get('patient')
        ->row();
    }
   
    $cabin = $this->db->where('cabin_id', $patient->cabin_id)->get('cabin')->row();
    $ward = $this->db->where('ward_id', $patient->ward_id)->get('ward')->row();
    $bed = $this->db->where('bed_id', $patient->bed_id)->get('bed')->row();
    $doctor = $this->db->where('doctor_id', $patient->doctor_id)->get('doctor')->row();
    ?>
    <?php
    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    ?>
    <div class="" style="width: 100%;margin-bottom: 10px;">
        <div style="width: 20%;float: left;">
            <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
        </div>

        <div style="width: 80%;float: left;margin-bottom: 20px;text-align: left">
            <div class="content" style="padding-right: 150px; ">
                <p style="text-align: center"><span style="text-align: center;font-size: 22px;text-align: center "> <?php echo $compnay->company_name ?></span><br>
                    <span style="text-align: center"> Mobile: <?php echo $compnay->mobile ?><br>
                        Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
    <div class="name" style="width: 100%;margin-bottom: 10px;">
    <img src="<?php echo base_url('PatientController/set_barcode/' . $patient->patient_unique_id); ?>" alt="Barcode">

        <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
            <tr>
                <td>Patient Name</td>
                <td>
                    <b><?php echo $patient->patient_name ?></b>
                </td>
            </tr>
            <tr>
                <td>Gender</td>
                <td>
                    <b><?php echo $patient->gender ?></b>
                </td>
            </tr>
            <tr>
                <td>Age</td>
                <td>
                    <b><?php echo $patient->age ?></b>
                </td>
            </tr>
            <tr>
                <td>Mobile</td>
                <td>
                    <b> <?php echo $patient->mobile_number ?></b>
                </td>
            </tr>
            <tr>

                <td>Ref. Doctor</td>
                <td>
                    <b><?php echo $doctor->name ?></b>
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
                       Ward:<b><?php echo $ward->name ?>(<?php echo $ward->number ?>)</b> Bed: <b><?php echo $bed->bed_number ?></b>
                    </td>
                <?php
                }
                ?>
            </tr>
            <tr>
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
            </tr>
            <tr>

                <td>Paid</td>
                <td>
                    <b><?php echo $patient->paid_amount ?></b>
                </td>
            </tr>

        </table>
    </div>
    <div class="product" style="height: 300px; ">

        <p style="text-align: center;margin-top: 100px;">Software developed by Bijoy LAB Web & IT Solution Ltd:01818-650864,www.bijoylab.com</p>

    </div>

</div>