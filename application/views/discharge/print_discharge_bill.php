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
    $patient = '';
    $discharge = '';
    if ($this->session->userdata('print_discharge_id')) {
        $print_discharge_id = $this->session->userdata('print_discharge_id');
        $discharge = $this->db->where('discharge_id', $print_discharge_id)
            ->get('discharge')
            ->row();
    } else {
        $discharge = $this->db->where('discharge_id', $discharge_id)
            ->get('discharge')
            ->row();
    }


    $patient = $this->db->where('ipd_patient_id', $discharge->ipd_patient_id)
        ->get('ipd_patient')
        ->row();
    //          print_r($patient);
    //die;
    $cabin = $this->db->where('cabin_id', $patient->cabin_id)->get('cabin')->row();
    $ward = $this->db->where('ward_id', $patient->ward_id)->get('ward')->row();
    $bed = $this->db->where('bed_id', $patient->bed_id)->get('bed')->row();
    $doctor = $this->db->where('doctor_id', $patient->reference_doctor_id)->get('doctor')->row();
    $under_doctor = $this->db->where('doctor_id', $patient->under_doctor_id)->get('doctor')->row();
    $user = getUserById($discharge->user_id);
    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    ?>
    <div class="" style="width: 100%;margin-bottom: 10px;">
        <div style="width: 15%;float: left;margin-top:20px">
            <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
        </div>
        <div style="width: 70%;float: left;text-align: center">
            <p style="text-align: center"><span style="text-align: center;font-size: 20px;text-align: center "> <?php echo $compnay->company_name ?></span><br>
                <span style="text-align: center">
                    Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                </span>
            </p>
        </div>
        <div style="width: 15%;float: left;margin-top:20px">

            <img src="<?php echo base_url('DischargeController/set_barcode/' . $discharge->discharge_bill_id); ?>" alt="Barcode">
            <!-- <img src="<?php echo base_url('IpdPatientController/set_barcode/' . $patient->patient_unique_id); ?>" alt="Barcode"> -->
        </div>
    </div>

    <div class="name" style="width: 100%;margin-bottom: 10px;height:auto">
        <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
            <tr>
                <td colspan="4">
                    <?php
                    if ($discharge->due == 0) {
                    ?>
                        <h2 style="font-weight: bold;text-align: center ">Final Bill</h2>
                    <?php
                    } else {
                    ?>
                        <h2 style="font-weight: bold;text-align: center ">Demo Bill</h2>
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Patient Name</td>
                <td>
                    <b><?php echo $patient->patient_name ?>&nbsp;(<?php echo $patient->gender ?>)</b>
                </td>
                <td>Address</td>
                <td>
                    <b><?php echo $patient->address ?></b>
                </td>
            </tr>
            <tr>
                <td>Mobile</td>
                <td>
                    <b> <?php echo $patient->mobile_number ?></b>
                </td>
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
            </tr>
            <tr>
                <td>Admission</td>
                <td>
                    <b>
                        <?php echo date('d-m-Y', strtotime($patient->date)) . ' ' . $patient->admission_time ?>
                    </b>
                </td>
                <td>Discharge</td>
                <td>
                    <b>
                        <?php echo date('d-m-Y', strtotime($discharge->discharge_date)) . ' ' . $discharge->discharge_time ?>
                    </b>
                </td>
            </tr>
            <tr>
                <td>Seat</td>
                <td>
                    <?php if ($ward) { ?>
                        Ward:<b><?php echo $ward->name ?></b> Bed:<b><?php echo $bed->bed_number ?></b>
                    <?php
                    }
                    if ($cabin) { ?>
                        Cabin:<b><?php echo $cabin->cabin_number ?></b>
                    <?php
                    }
                    ?>
                </td>
                <td>Duration</td>
                <td>
                    <b>
                        <?php
                        if ($discharge->total_duration_day > 0) {
                            echo $discharge->total_duration_day . ' ' . ($discharge->total_duration_day == 1 ? 'Day ' : 'Days ') . ' ';
                        }
                        if ($discharge->total_duration_hours >= 0) {
                            echo $discharge->total_duration_hours . ' ' . ($discharge->total_duration_hours == 1 ? 'Hour ' : 'Hours ');
                        }
                        if ($discharge->discharge_time_minute > 0) {
                            echo $discharge->discharge_time_minute . ' ' . ($discharge->discharge_time_minute == 1 ? 'Minute ' : 'Minutes ');
                        }
                        if ($discharge->discharge_time_second > 0) {
                            echo $discharge->discharge_time_second . ' ' . ($discharge->discharge_time_second == 1 ? 'Second ' : 'Seconds ');
                        }
                        ?>
                    </b>

                </td>
            </tr>
            <tr>
                <td>Discount Reference</td>
                <td><?php echo $discharge->discount_reference ?></td>
                <td>Ref. Doctor: <b><?php echo $doctor->doctor_name ?></b></td>
                <td>Under Doctor: <b><?php echo $under_doctor->doctor_name ?></b></td>
            </tr>
            <tr>
                <td>Remarks</td>
                <td><?php echo $discharge->remarks ?></td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
            <tr>
                <td colspan="5" style="text-align:center"><u>Service Description</u></td>
            </tr>
            <tr>
                <td>#</td>
                <td>Name</td>
                <td>Unit Price</td>
                <td>Qty</td>
                <td>Total Price</td>
            </tr>
            <?php
            $ipd_required_services = $this->db->where('service_type', 'Required')->get('ipd_service_item')->result();
            $serial = 1;


            $total_service_fee = 0;
            $ipd_service_details = $this->db->where('ipd_patient_id', $discharge->ipd_patient_id)->get('ipd_service_details')->result();
            foreach ($ipd_service_details as $value) {
                $ipd_service_item = $this->db->where('ipd_service_item_id', $value->ipd_service_item_id)->get('ipd_service_item')->row();
            ?>
                <tr>
                    <td><?php echo $serial++ ?></td>
                    <td><?php echo $ipd_service_item->name; ?></td>
                    <td><?php echo $value->price; ?></td>
                    <td><?php echo $value->quantity; ?></td>
                    <td><?php echo $value->amount;
                        $total_service_fee += $value->amount; ?></td>
                </tr>

            <?php
            }
            ?>
            <tr>
                <td colSpan="4" style="text-align:right">Total</td>
                <td><?php echo $total_service_fee; ?></td>
            </tr>
        </table>
        <div class="container">
            <div style="width: 40%;float:left;">
                <div style="border:1px solid black;height: 50px;">
                    <?php
                    if ($discharge->due == 0) {
                    ?>
                        <h2 style="font-weight: bold;text-align: center ">Paid</h2>
                    <?php
                    } else {
                    ?>
                        <h2 style="font-weight: bold;text-align: center ">Due</h2>
                    <?php
                    }
                    ?>
                    <br>
                </div>
                <div style="height: 200px;">
                    <p style="font-weight: bold;text-align: left ">Pharmacy Clearance<br>__________________________________</p>
                </div>

            </div>
            <div style="width: 60%;float:left;">
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                    <tr>

                        <td style="text-align:right"><?php echo $discharge->bed_or_cabin_charge; ?></td>
                        <td><?php echo $discharge->cabin_or_bed_bill; ?></td>
                    </tr>


                    <?php
                    if ($discharge->test_bill != 0) {
                    ?>
                        <tr>
                            <td style="text-align:right">Test Rent</td>
                            <td><?php echo $discharge->test_bill; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if ($discharge->pharmachy_bill != 0) {
                    ?>
                        <tr>
                            <td style="text-align:right">Pharmacy Bill</td>
                            <td><?php echo $discharge->pharmachy_bill; ?></td>
                        </tr>
                    <?php
                    }
                    ?>

                    <?php
                    if ($discharge->emergency_bill != 0) {
                    ?>
                        <tr>
                            <td style="text-align:right">Emergency Bill</td>
                            <td><?php echo $discharge->emergency_bill; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if ($discharge->ot_service_bill != 0) {
                    ?>
                        <tr>
                            <td style="text-align:right">OT Bill</td>
                            <td><?php echo $discharge->ot_service_bill; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>

                        <td style="text-align:right">Admission Reg Fee</td>
                        <td><?php echo $discharge->admission_reg_fee; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right">Assistant Fee</td>
                        <td><?php echo $discharge->assistatnt_fee; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right">Consultant Fee</td>
                        <td><?php echo $discharge->consultant_fee; ?></td>
                    </tr>
                    <?php
                    if ($discharge->extra_hours_bill != 0) {
                    ?>
                        <tr>
                            <td style="text-align:right;font-weight:bold">Extra Hours Bill</td>
                            <td><b><?php echo $discharge->extra_hours_bill; ?></b></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if ($discharge->total_bill != 0) {
                    ?>
                        <tr>
                            <td style="text-align:right;font-weight:bold">Total Bill</td>
                            <td><b><?php echo $discharge->total_bill; ?></b></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td style="text-align:right">Service Charge</td>
                        <td><?php echo $discharge->service_charge; ?></td>
                    </tr>

                    <?php
                    if ($discharge->previous_paid != 0) {
                    ?>
                        <tr>
                            <td style="text-align:right">Advance Paid</td>
                            <td><?php echo $discharge->previous_paid; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if ($discharge->director_discount != 0) {
                    ?>
                        <tr>
                            <td style="text-align:right">Director Discount</td>
                            <td><?php echo $discharge->director_discount; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if ($discharge->payable != 0) {
                    ?>
                        <tr>
                            <td style="text-align:right;font-weight:bold">Payable</td>
                            <td><b><?php echo $discharge->payable; ?></b></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if ($discharge->special_discount != '') {
                    ?>
                        <tr>
                            <td style="text-align:right">Special Discount</td>
                            <td><?php echo $discharge->special_discount; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if ($discharge->net_payable != 0) {
                    ?>
                        <tr>
                            <td style="text-align:right;font-weight:bold">Net Payable</td>
                            <td><b><?php echo $discharge->net_payable; ?></b></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if ($discharge->paid != 0) {
                    ?>
                        <tr>
                            <td style="text-align:right">Paid</td>
                            <td><?php echo $discharge->paid; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if ($discharge->due != 0) {
                    ?>
                        <tr>
                            <td style="text-align:right;font-weight:bold">Due</td>
                            <td><b><?php echo $discharge->due; ?></b></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <div class="report_footer" style="clear:left;margin-top: 200px!important; ">
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