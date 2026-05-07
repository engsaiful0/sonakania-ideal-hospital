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
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:50px;">

    <?php
    error_reporting(0);
    $patient = $this->db->where('ipd_patient_id', $data->ipd_patient_id)
        ->get('ipd_patient')
        ->row();

    $ot_service_detail = $this->db
        ->where('ot_service_id', $data->ot_service_id)
        ->get('ot_services')
        ->row();

    $surgons = json_decode($ot_service_detail->surgon_doctor_id);
    $nurses = json_decode($ot_service_detail->employee_nurse_id);



    $surgery = $this->db
        ->where('surgery_id', $ot_service_detail->surgery_id)
        ->get('surgeries')
        ->row();
    $anestasia = $this->db
        ->where('doctor_id', $ot_service_detail->anestasia_doctor_id)
        ->get('doctor')
        ->row();
    ?>


    <div class="customer-copy" style="margin-top: 50px; ">


        <div class="name" style="width: 100%;margin-bottom: 10px;">
            <table border="1" class="table table-bordered table-hover" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                <tr>
                    <td>Patient</td>

                    <td>
                        <b><?php echo $patient->patient_name ?></b> </b>
                    </td>
                    <td>Mobile</td>
                    <td>
                        <b><?php echo $patient->mobile_number ?></b>
                    </td>
                </tr>
                <tr>
                    <td>Patient Id</td>
                    <td>
                        <b><?php echo $patient->patient_unique_id ?></b>
                    </td>
                    <td>Date</td>
                    <td>
                        <b> <?php echo date('d-m-Y', strtotime($data->date)) ?></b>
                    </td>
                </tr>
                <tr>
                    <td>Surgery Name</td>
                    <td>
                        <b><?php echo $surgery->name ?></b>
                    </td>
                    <td>Price</td>
                    <td>
                        <b> <?php echo (float)$surgery->price ?></b>
                    </td>
                </tr>
                <tr>
                    <td>Discount</td>
                    <td>
                        <b><?php echo (float)$ot_service_detail->discount ?></b>
                    </td>
                    <td>Net Price</td>
                    <td>
                        <b> <?php echo (float)$ot_service_detail->net_price ?></b>
                    </td>
                </tr>
                <tr>
                    <td>Discount Reference</td>
                    <td>
                        <b><?php echo $ot_service_detail->discount_reference ?></b>
                    </td>
                    <td></td>
                    <td>
                     
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:center"><b><u>Anaestesiologist</u></b></td>

                </tr>
                <tr>
                    <td>Anaestesia Name</td>
                    <td>
                        <b><?php echo $anestasia->doctor_name ?></b>
                    </td>
                    <td>Degree</td>
                    <td>
                        <b> <?php echo $anestasia->degree ?></b>
                    </td>
                </tr>

            </table>
        </div>
        <div class="product" style="min-height: 300px; ">
            <div style="margin-top:5px">
                <table border="1" class="table table-bordered table-hover" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
                    <tr>
                        <td colspan="5" style="text-align:center"><b><u>Surgon Details</u></b></td>

                    </tr>
                    <tr>
                        <td>Sl</td>
                        <td>Surjon Name</td>
                        <td>ID</td>
                        <td>Mobile</td>
                        <td>Degree</td>
                    </tr>
                    <?php
                    if (!isset($surgons) || !is_array($surgons)) {
                        $surgons = []; // Ensure $surgons is at least an empty array to prevent errors
                    }
                    
                    for ($index = 0; $index < count($surgons); $index++) {
                        $surgery = $this->db
                            ->where('doctor_id', $surgons[$index])
                            ->get('doctor')
                            ->row();
                    ?>
                        <tr>
                            <td><?php echo $index + 1 ?></td>
                            <td><?php echo $surgery->doctor_name ?></td>
                            <td style="text-align:left"><?php echo $surgery->doctor_unique_id ?></td>
                            <td style="text-align:left"><?php echo $surgery->mobile ?></td>
                            <td style="text-align:left"><?php echo $surgery->degree ?></td>
                        </tr>
                    <?php
                    }

                    ?>
                </table>
            </div>
            <div style="margin-top:5px">
                <table border="1" class="table table-bordered table-hover" style="margin-top:5px;width: 100%;border-collapse:collapse;margin:0 auto;color:black">
                    <tr>
                        <td colspan="5" style="text-align:center"><b><u>Nurses Details</u></b></td>

                    </tr>
                    <tr>
                        <td>Sl</td>
                        <td>Surjon Name</td>
                        <td>ID</td>
                        <td>Mobile</td>
                        <td>Designation</td>
                    </tr>
                    <?php
                    for ($index = 0; $index < count($nurses); $index++) {
                        $employee = $this->db
                            ->where('employee_id', $nurses[$index])
                            ->get('employee')
                            ->row();
                        $designation = $this->db
                            ->where('designation_id', $employee->designation_id)
                            ->get('designation')
                            ->row();
                    ?>
                        <tr>
                            <td><?php echo $index + 1 ?></td>
                            <td><?php echo $employee->employee_name ?></td>
                            <td style="text-align:left"><?php echo $employee->employee_unique_id ?></td>
                            <td style="text-align:left"><?php echo $employee->mobile ?></td>
                            <td style="text-align:left"><?php echo $designation->designation_name ?></td>
                        </tr>
                    <?php
                    }

                    ?>
                </table>
            </div>
            <div style="margin-top:5px">
                <img style="width: 100%;" src="<?php echo base_url() ?>assets/bond/<?php echo $ot_service_detail->bond_paper ?>">
            </div>


        </div>
    </div>


</div>