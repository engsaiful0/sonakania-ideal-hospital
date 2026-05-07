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
    }
</style>

<div class="row">
    <div class="col-md-12"> 
        <button onclick="window.print()" id="sumbit_button" class="btn btn-primary" >Print</button>
    </div>

</div>
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:20px;">
    <?php
    error_reporting(0);
    $biomedical_test = $this->db->where('biomedical_test_id', $biomedical_test_id)
                    ->get('biomedical_test')->row();
    // echo '<pre>';
    //   print_r($biomedical_test);

    $patient_test_entry = $this->db->where('patient_test_entry_id', $biomedical_test->patient_test_entry_id)
                    ->get('patient_test_entry')->row();
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
                    <span style="text-align: center">  Mobile: <?php echo $compnay->mobile ?><br>
                        Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                    </span>
                </p>
            </div>


        </div>
    </div>
    <div class="name" style="width: 100%;margin-bottom: 10px;">
        <table border="0" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
            <tr>
                <td><b>Patient Name</b></td>
                <td>:</td>
                <td>

                    <?php echo $patient_test_entry->patient_name ?>
                </td>
                <td><b>Date</b></td>
                <td>:</td>
                <td>

                    <?php echo date('d-m-Y', strtotime($patient_test_entry->date)) ?>
                </td>


            </tr>
            <tr>
                <td><b>Age</b></td>
                <td>:</td>
                <td>

                    <?php echo $patient_test_entry->age ?>
                </td>
                <td><b>Gender</b></td>
                <td>:</td>
                <td>

                    <?php echo $patient_test_entry->gender ?>
                </td>
            </tr>
            <tr>
                <td><b>Mobile</b></td>
                <td>:</td>
                <td>
                    <?php echo $patient_test_entry->mobile ?>
                </td>
                <td><b>Invoice No</b></td>
                <td>:</td>
                <td>

                    <?php echo $patient_test_entry->invoice_no ?>
                </td>

            </tr>
        </table>
    </div>
    <div class="product" style="height: 560px; ">
        <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
            <tr>
                <td>Sl</td>
                <td>Test</td>
                <td>Result</td>
                <td>Unit</td>
                <td>Reference Value</td>
            </tr>
            <?php
            $sl = 1;

            if ($biomedical_test->fasting_food_sugar != '') {
                $referece = $this->db->where('sequence', '1')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>Fasting Food Sugar</td>                 
                    <td><?php echo $biomedical_test->fasting_food_sugar ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }
            if ($biomedical_test->corresponding_urine_sugar_fasting_food_sugar != '') {
                $referece = $this->db->where('sequence', '2')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>Corresponding Urine Sugar</td>                 
                    <td><?php echo $biomedical_test->corresponding_urine_sugar_fasting_food_sugar ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }
            if ($biomedical_test->post_prandial_blood_sugar_ppbs_al_ad != '') {
                $referece = $this->db->where('sequence', '3')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>Post Prandial Blood Sugar(PPBS/AL/AD)</td>                 
                    <td><?php echo $biomedical_test->post_prandial_blood_sugar_ppbs_al_ad ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }
            if ($biomedical_test->corrponding_urine_sugar_post_prandial_blood_sugar_ppbs_al_ad != '') {
                $referece = $this->db->where('sequence', '4')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>Corresponding Urine Sugar</td>                 
                    <td><?php echo $biomedical_test->corrponding_urine_sugar_post_prandial_blood_sugar_ppbs_al_ad ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }
            if ($biomedical_test->two_hours_after_75gm_glucose != '') {
                $referece = $this->db->where('sequence', '5')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>2 Hours after 75gm Glucose</td>                 
                    <td><?php echo $biomedical_test->two_hours_after_75gm_glucose ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }
            if ($biomedical_test->corresponding_urine_sugar_2hours_after_75gm_glucose != '') {
                $referece = $this->db->where('sequence', '6')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>Corresponding Urine Sugar</td>                 
                    <td><?php echo $biomedical_test->corresponding_urine_sugar_2hours_after_75gm_glucose ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }
            if ($biomedical_test->random_blood_sugar != '') {
                $referece = $this->db->where('sequence', '7')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>Random Blood Sugar</td>                 
                    <td><?php echo $biomedical_test->random_blood_sugar ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }
            if ($biomedical_test->corresponding_urine_sugar_random_blood_sugar != '') {
                $referece = $this->db->where('sequence', '8')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>Corresponding Urine Sugar</td>                 
                    <td><?php echo $biomedical_test->corresponding_urine_sugar_random_blood_sugar ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }
            if ($biomedical_test->lipid_profile_f_s_cholesterol != '') {
                $referece = $this->db->where('sequence', '9')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>Lipid Profile(F)-S.Cholesterol(Total)</td>                 
                    <td><?php echo $biomedical_test->lipid_profile_f_s_cholesterol ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }
            if ($biomedical_test->lipid_profile_f_s_hdl_cholesterol != '') {
                $referece = $this->db->where('sequence', '10')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>Lipid Profile(F)-S.HDL-Cholesterol</td>                 
                    <td><?php echo $biomedical_test->lipid_profile_f_s_hdl_cholesterol ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }
            if ($biomedical_test->lipid_profile_f_s_ldl_cholesterol != '') {
                $referece = $this->db->where('sequence', '11')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>
                        Lipid Profile(F)-S.LDL-Cholesterol
                    </td>                 
                    <td><?php echo $biomedical_test->lipid_profile_f_s_ldl_cholesterol ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }
            if ($biomedical_test->lipid_profile_f_triglyceride_tg_cholesterol != '') {
                $referece = $this->db->where('sequence', '12')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>
                        Lipid Profile(F)-Triglyceride(TG)
                    </td>                 
                    <td><?php echo $biomedical_test->lipid_profile_f_triglyceride_tg_cholesterol ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }

            if ($biomedical_test->s_creatinine != '') {
                $referece = $this->db->where('sequence', '13')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>
                        S.Creatinine
                    </td>                 
                    <td><?php echo $biomedical_test->s_creatinine ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }

            if ($biomedical_test->HbA1c != '') {
                $referece = $this->db->where('sequence', '14')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>
                        HbA1c
                    </td>                 
                    <td><?php echo $biomedical_test->HbA1c ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }

            if ($biomedical_test->t_billirubin != '') {
                $referece = $this->db->where('sequence', '15')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>
                        T.Billirubin
                    </td>                 
                    <td><?php echo $biomedical_test->t_billirubin ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }
            if ($biomedical_test->s_alt_sgpt != '') {
                $referece = $this->db->where('sequence', '16')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>
                        S.ALT(SGPT)
                    </td>                 
                    <td><?php echo $biomedical_test->s_alt_sgpt ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }

            if ($biomedical_test->s_ast_sgot != '') {
                $referece = $this->db->where('sequence', '17')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>
                        S.AST(SGOT)
                    </td>                 
                    <td><?php echo $biomedical_test->s_ast_sgot ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }

            if ($biomedical_test->alk_phosphates != '') {
                $referece = $this->db->where('sequence', '18')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>
                        Alk.Phosphates
                    </td>                 
                    <td><?php echo $biomedical_test->alk_phosphates ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }
            if ($biomedical_test->s_uric_acid != '') {
                $referece = $this->db->where('sequence', '19')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>
                        S.Uric Acid
                    </td>                 
                    <td><?php echo $biomedical_test->s_uric_acid ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }
            if ($biomedical_test->serum_electrolytes_sodium_na_plus != '') {
                $referece = $this->db->where('sequence', '20')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>
                        Serum Eletrolytes-Sodium(Na+)
                    </td>                 
                    <td><?php echo $biomedical_test->serum_electrolytes_sodium_na_plus ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }
            if ($biomedical_test->serum_electrolytes_potassium_k_plus != '') {
                $referece = $this->db->where('sequence', '21')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>
                        Serum Eletrolytes-Potassium(K+)
                    </td>                 
                    <td><?php echo $biomedical_test->serum_electrolytes_potassium_k_plus ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }

            if ($biomedical_test->serum_electrolytes_chloride_cl_minus != '') {
                $referece = $this->db->where('sequence', '22')->get('biomedical_test_unit_reference')->row();
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td>
                        Serum Eletrolytes-Chloride(CL-)
                    </td>                 
                    <td><?php echo $biomedical_test->serum_electrolytes_chloride_cl_minus ?></td>
                    <td><?php echo $referece->unit ?></td>
                    <td><?php echo $referece->reference ?></td>
                </tr>
                <?php
            }
            ?>


        </table>
    </div>
    <div class="report-footer row" >
        <?php
        $report_footer = $this->db->where('report_footer_id', '1')->get('report_footer')->row();
        ?>
        <table border="0" style="width: 100%; ">
            <tr>
                <td style="width: 33% "><?php echo $report_footer->report_footer_1 ?></td>
                <td style="width: 33% "><?php echo $report_footer->report_footer_2 ?></td>
                <td style="width: 33% "> <?php echo $report_footer->report_footer_3 ?></td>
            </tr>
        </table>



    </div>
    <p style="text-align: center;margin-top: 35px;">Software developed by Bijoy LAB Web & IT Solution Ltd:01818-650864,www.bijoylab.com</p>

</div>

</div>
