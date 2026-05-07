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
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:40px;">
    <?php
    //error_reporting(0);
    ?> 
    <?php
    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    $prescription_header = $this->db->where('prescription_header_id', '1')->get('prescription_header')->row();
    $prescription = $this->db->where('prescription_id', $prescription_id)->get('prescription')->row();
    ?>
    <div class="" style="width: 100%;margin-bottom: 10px;">
        <div style="width:40%;float: left;">
            <div class="content" >
                <p style="text-align: center">
                    <span style="text-align: center;font-size: 22px;text-align: center "> <?php echo $prescription_header->doctors_name_in_bangla ?></span><br>
                    <span style="text-align: center"><?php echo $prescription_header->degree1_in_bangla ?><br>
                        <?php echo $prescription_header->degree2_in_bangla ?>
                    </span>
                </p>
            </div>
        </div>
        <div style="width: 20%;float: left;">

            <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">

        </div>

        <div style="width:40%;float: left;">
            <div class="content">
                <p style="text-align: center">
                    <span style="text-align: center;font-size: 20px;text-align: center "> <?php echo $prescription_header->doctors_name_in_english ?></span><br>
                    <span style="text-align: center"><?php echo $prescription_header->degree1_in_english ?><br>
                        <?php echo $prescription_header->degree2_in_english ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
    <div class="name" style="width: 100%;margin-bottom: 10px;">
        
        <table border="0" style="width: 100%;border-collapse:collapse;color:black;margin-top: 20px;">
            <tr>
                <td><b>Patient Name</b></td>             
                <td>

                    <?php echo $prescription->patient_name ?>
                </td>
                <td><b>Date</b></td>

                <td>

                    <?php echo date('d-m-Y', strtotime($prescription->date)) ?>
                </td>
                <td><b>Age</b></td>
                <td>
                    <?php echo $prescription->age ?>
                </td>
                <td><b>Gender</b></td>
                <td>
                    <?php echo $prescription->gender ?>
                </td>

            </tr>      
             <tr>
                 <td colspan="8"><b><?php echo $prescription->follow_up.' '.$prescription->follow_up_day_month_year?></b> পর দেখা করবেন </td>             
               
              

            </tr>   
        </table>
    </div>
    <div class="product" style="height: 300px; ">
        <div class="row">
            <div class="left-content" style="width: 30%;float: left">
                <div class="history">
                    <p style="text-align: center "><b><u>History</u></b></p>
                    <?php
                    $prescription_diagnosis = $this->db
                            ->where('prescription_id', $prescription_id)
                            ->get('prescription_diagnosis')
                            ->result();
                    $sl = 1;

                    foreach ($prescription_diagnosis as $prescription_diagnosis_value) {
                        $diagnosis = $this->db->where('diagnosis_id', $prescription_diagnosis_value->diagnosis_id)->get('diagnosis')->row();
                        ?>
                        <p><?php echo $sl++ . ') ' . $diagnosis->diagnosis_name; ?></p>
                        <?php
                    }
                    ?>
                        <p><b>BP: <?php echo $prescription->bp?></b></p>
                </div>
                <div class="Advice">
                    <p style="text-align: center "><b><u>Advice</u></b></p>
                    <?php
                    $prescription_advice = $this->db
                            ->where('prescription_id', $prescription_id)
                            ->get('prescription_advice')
                            ->result();
                    $sl = 1;
                    foreach ($prescription_advice as $advice_value) {
                        $advice = $this->db->where('advice_id', $advice_value->advice_id)->get('advice')->row();
                        ?>
                        <p><?php echo $sl++ . ') ' . $advice->advice_name; ?></p>
                        <?php
                    }
                    ?>
                </div>

            </div>
            <div style="width: 65%;float: left;text-align: left;padding-left: 30px;">
               
                <?php
                $sl = 1;
                $prescription_medicin = $this->db
                        ->where('prescription_id', $prescription_id)
                        ->get('prescription_medicin')
                        ->result();
                foreach ($prescription_medicin as $prescription_medicin_value) {
                    $prescription_header = $this->db->where('prescription_header_id', '1')->get('prescription_header')->row();
                    $drug_type = $this->db->where('drug_type_id', $prescription_medicin_value->drug_type_id)->get('drug_type')->row();
                    $drug = $this->db->where('drug_id', $prescription_medicin_value->drug_id)->get('drug')->row();
                    $medicin_times = $this->db->where('medicin_times_id', $prescription_medicin_value->medicin_times_id)->get('medicin_times')->row();
                    ?>
                <p><?php echo $sl++ ?>) <?php echo $drug_type->short_name . '&nbsp;&nbsp;' . $drug->drug_name; ?><br>
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $medicin_times->medicin_times_name . '&nbsp;&nbsp;&nbsp;&nbsp;->' . $prescription_medicin_value->days . ' ' . $prescription_medicin_value->day_or_month_or_year_or_colbay; ?></p>
                    <?php
                }
                ?>
            </div>

        </div>

       
    </div>

</div>
