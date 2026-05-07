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
    $ot_service_detail = '';
    if ($this->session->userdata('print_ot_service_id')) {
        $ot_service_id = $this->session->userdata('print_ot_service_id');
        $ot_service_detail = $this->db->where('ot_service_id', $ot_service_id)
            ->get('ot_services')
            ->row();
    } else {
        $ot_service_detail = $this->db->where('ot_service_id', $ot_service_id)
            ->get('ot_services')
            ->row();
    }

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

    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    $user = getUserById($ot_service_detail->user_id);
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
            <img src="<?php echo base_url('OTServiceController/set_barcode/' . $ot_service_detail->ot_service_unique_id); ?>" alt="Barcode">
        </div>
    </div>
    <div class="name" style="width: 100%;margin-bottom: 10px;">
        <table border="1" class="table table-bordered table-hover" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
            <tr>
                <td>Patient</td>

                <td>
                    <b><?php echo $ot_service_detail->patient_name ?></b> &nbsp;&nbsp;&nbsp;Age:<b><?php
                                                                                                    $age_parts = [];

                                                                                                    if ($ot_service_detail->age_year > 0) {
                                                                                                        $age_parts[] = $ot_service_detail->age_year . ' ' . ($ot_service_detail->age_year == 1 ? 'Year' : 'Years');
                                                                                                    }

                                                                                                    if ($ot_service_detail->age_month > 0) {
                                                                                                        $age_parts[] = $ot_service_detail->age_month . ' ' . ($ot_service_detail->age_month == 1 ? 'Month' : 'Months');
                                                                                                    }

                                                                                                    if ($ot_service_detail->age_day > 0) {
                                                                                                        $age_parts[] = $ot_service_detail->age_day . ' ' . ($ot_service_detail->age_day == 1 ? 'Day' : 'Days');
                                                                                                    }

                                                                                                    echo implode(' ', $age_parts);
                                                                                                    ?></b>
                </td>
                <td>Mobile</td>
                <td>
                    <b><?php echo $ot_service_detail->mobile_number ?></b>
                </td>
            </tr>
            <tr>
                <td>Invoice Id</td>
                <td>
                    <b><?php echo $ot_service_detail->ot_service_unique_id ?></b>
                </td>
                <td>Date & Time</td>
                <td>
                    <b> <?php echo date('d-m-Y', strtotime($ot_service_detail->date)) ?> & <?php echo $ot_service_detail->time ?></b>
                </td>
            </tr>
            <tr>
                <td>Surgery Name</td>
                <td>
                    <b><?php echo $surgery->name ?></b>
                </td>
                <td>Address</td>
                <td>
                    <b><?php echo $ot_service_detail->address ?></b>
                </td>
            </tr>
            <tr>
                <td>Price</td>
                <td>
                    <b> <?php echo (float)$surgery->price ?></b>
                </td>
                <td>Total Discount</td>
                <td>
                    <b><?php echo (float)$ot_service_detail->total_discount ?></b>
                </td>

            </tr>
            <tr>
                <td>Net Price</td>
                <td>
                    <b> <?php echo (float)$ot_service_detail->net_price ?></b>
                </td>
                <td>Discount Reference</td>
                <td>
                    <b><?php echo $ot_service_detail->discount_reference ?></b>
                </td>

            </tr>
            <tr>
                <td>Paid</td>
                <td>
                    <b> <?php echo (float)$ot_service_detail->paid + (float)$ot_service_detail->due_payment ?></b>
                </td>
                <td>Due</td>
                <td>
                    <b><?php echo $ot_service_detail->due ?></b>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Amount In word: <?php echo convertNumberToWord($ot_service_detail->paid + (float)$ot_service_detail->due_payment) ?> Taka only</b>
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
                $nurses = isset($nurses) && is_array($nurses) ? $nurses : [];
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
            <?php if (!empty($ot_service_detail->bond_paper)) : ?>
                <img style="width: 100%;" src="<?php echo base_url('assets/bond/' . $ot_service_detail->bond_paper); ?>">
            <?php endif; ?>
        </div>
    </div>
    <p style="text-align: right;padding-right: 100px;padding-top: 100px; "> Entry By: <?php echo  $user->name ?? "" ?></p>

</div>