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
    //error_reporting(0);
    ?>
    <?php
    $company = $this->db->where('company_id', '1')->get('company')->row();
    $prescription_header = $this->db->where('prescription_header_id', '1')->get('prescription_header')->row();


    $discharge_slip_id = isset($discharge_slip_id) ? $discharge_slip_id : $this->session->userdata('print_discharge_slip_id');
    $discharge_slip = null;
    $ipd_patient = null;
    $discharge = null;

    if (!empty($discharge_slip_id)) {
        $discharge_slip = $this->db->where('discharge_slip_id', $discharge_slip_id)
            ->get('discharge_slips')
            ->row();
    }

    if (!empty($discharge_slip)) {
        $ipd_patient = $this->db->where('ipd_patient_id', $discharge_slip->ipd_patient_id)->get('ipd_patient')->row();
        $discharge = $this->db->where('ipd_patient_id', $discharge_slip->ipd_patient_id)->get('discharge')->row();
    }
    ?>
     <?php
    $company = $this->db->where('company_id', '1')->get('company')->row();
    ?>
    <div class="" style="width: 100%;margin-bottom: 10px;">
        <div style="width: 20%;float: left;">
            <img style="width:100px;height:100px;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $company->logo ?>">
        </div>

        <div style="width: 80%;float: left;margin-bottom: 20px;text-align: left">
            <div class="content" style="padding-right: 150px; ">
                <p style="text-align: center"><span style="text-align: center;font-size: 22px;text-align: center "> <?php echo $company->company_name ?></span><br>
                    <span style="text-align: center"> Mobile: <?php echo $company->mobile ?><br>
                        Email: <?php echo $company->email ?>,Web:<?php echo $company->web ?>
                    </span>
                </p>
            </div>
        </div>
        <?php if (!empty($discharge_slip)) { ?>
            <?php echo $discharge_slip->discharge_slip_unique_id; ?>
            <img src="<?php echo base_url('DischargeSlipController/set_barcode/' . $discharge_slip->discharge_slip_unique_id); ?>" alt="Barcode">
        <?php } ?>
    </div>
    <?php if (empty($discharge_slip) || empty($ipd_patient) || empty($discharge)) { ?>
        <div class="alert alert-warning" style="margin-top: 10px;">
            No discharge slip data found to print.
        </div>
    <?php } else { ?>
    <div class="name" style="width: 100%;margin-bottom: 10px;">

        <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
            <tr>
                <td colspan="8" style="text-align:center;font-weight:bold">Discharge Slip</td>
            </tr>
            <tr>
                <td>Name</td>
                <td>
                    <b><?php echo $ipd_patient->patient_name ?></b>
                </td>
                <td>Age</td>
                <td>
                    <b><?php echo $ipd_patient->age_year . ' Years ' . $ipd_patient->age_month . ' Months ' . $ipd_patient->age_day . ' Days' ?></b>
                </td>
                <td>Gender</td>
                <td>
                    <b><?php echo $ipd_patient->gender ?></b>
                </td>
                <td>Address</td>
                <td>
                    <b><?php echo $ipd_patient->address ?></b>
                </td>

            </tr>
            <tr>
                <td>Ad.Date</td>
                <td>

                    <b><?php echo date('d-m-Y', strtotime($ipd_patient->date)) ?></b>
                </td>

                <td>Ad. Time</td>
                <td>

                    <b><?php echo $ipd_patient->admission_time ?></b>
                </td>
                <td>Dis. Date</td>
                <td>
                    <b><?php echo date('d-m-Y', strtotime($discharge->discharge_date)) ?></b>
                </td>
                <td>Dis. Time</td>
                <td>
                    <b><?php echo $discharge->discharge_time ?></b>
                </td>

            </tr>
            <tr>
                <td style="text-align: center;" colspan="8"><b><?php echo $discharge_slip->follow_up . ' ' . $discharge_slip->follow_up_day_month_year ?></b> পর দেখা করবেন </td>
            </tr>
        </table>
    </div>
    <div class="product" style="height: 300px; ">
        <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
            <tr>
                <td><b><u>Diagnosis</u></b></td>
                <td><b><u>Treatment</u></b></td>
                <td><b><u>Advice</u></b></td>
            </tr>
            <tr>
                <td>
                    <?php
                    $discharge_slip_diagnosis = $this->db
                        ->where('discharge_slip_id', $discharge_slip_id)
                        ->get('discharge_slip_diagnosis')
                        ->result();
                    $sl = 1;

                    foreach ($discharge_slip_diagnosis as $discharge_slip_diagnosis_value) {
                        $diagnosis = $this->db->where('diagnosis_id', $discharge_slip_diagnosis_value->diagnosis_id)->get('diagnosis')->row();
                    ?>
                        <p><?php echo $sl++ . ') ' . $diagnosis->diagnosis_name; ?></p>
                    <?php
                    }
                    ?>
                    <p><?php echo $discharge_slip->custom_diagnosis ?></p>
                    <p><b>Discharge BP: <?php echo $discharge_slip->bp_systolic ?>/<?php echo $discharge_slip->bp_diastolic ?></b></p>
                </td>
                <td>
                    <?php
                    $sl = 1;
                    $prescription_medicin = $this->db
                        ->where('discharge_slip_id', $discharge_slip_id)
                        ->get('discharge_slip_medicins')
                        ->result();
                    foreach ($prescription_medicin as $prescription_medicin_value) {
                        $prescription_header = $this->db->where('prescription_header_id', '1')->get('prescription_header')->row();
                        $drug_type = $this->db->where('drug_type_id', $prescription_medicin_value->drug_type_id)->get('drug_type')->row();
                        $drug = $this->db->where('drug_id', $prescription_medicin_value->drug_id)->get('drug')->row();
                        $medicin_times = $this->db->where('medicin_times_id', $prescription_medicin_value->medicin_times_id)->get('medicin_times')->row();
                    ?>
                        <p><?php echo $sl++ ?>) <?php echo $drug_type->short_name . '&nbsp;&nbsp;' . $drug->drug_name; ?><br>
                            &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $medicin_times->medicin_times_name . '&nbsp;&nbsp;&nbsp;&nbsp;' . $prescription_medicin_value->days . ' ' . $prescription_medicin_value->day_or_month_or_year_or_colbay; ?></p>
                    <?php
                    }
                    ?>
                </td>
                <td>
                    <?php
                    $discharge_slip_advices = $this->db
                        ->where('discharge_slip_id', $discharge_slip_id)
                        ->get('discharge_slip_advices')
                        ->result();
                    $sl = 1;
                    foreach ($discharge_slip_advices as $advice_value) {
                        $advice = $this->db->where('advice_id', $advice_value->advice_id)->get('advice')->row();
                    ?>
                        <p><?php echo $sl++ . ') ' . $advice->advice_name; ?></p>
                    <?php
                    }
                    ?>
                      <p><?php echo $discharge_slip->custom_advice ?></p>
                </td>
            </tr>
        </table>



    </div>
    <?php } ?>

</div>