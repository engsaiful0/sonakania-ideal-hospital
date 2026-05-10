<script>
    function patient_info_load(patient_test_entry_id)
    {
        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                //  var data_array = xhttp.responseText.split("_");
                var data_array = xhttp.responseText.split("_");
                document.getElementById("age").value = data_array[0];/*age*/
                document.getElementById("invoice_no").value = data_array[1];/*invoice no*/
                document.getElementById("datepicker").value = data_array[2];/*date*/
                document.getElementById("mobile").value = data_array[3];/*mobile*/
                document.getElementById("gender").value = data_array[4];/*gender*/
                document.getElementById("doctor_name").value = data_array[5];/*doctor*/
                $('#img').hide();
                // document.getElementById("stock_" + id_no[2]).value = data_array[1];/*stock*/
            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('TestResultController/patient_info_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("patient_test_entry_id=" + patient_test_entry_id);
    }

    $(document).ready(function () {
// alert();
        $('#patient_test_entry_id').select2();
    });
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Edit Biochemical Test Data</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" action="<?php echo site_url('TestResultController/edit_biomedical_data_save') ?>" enctype='multipart/form-data'>
                <?php
                error_reporting(0);
                $biomedical_test = $this->db->where('biomedical_test_id', $biomedical_test_id)
                                ->get('biomedical_test')->row();
                $patient_test_entry = $this->db->where('patient_test_entry_id', $biomedical_test->patient_test_entry_id)
                                ->get('patient_test_entry')->row();
               // echo '<pre>';
               // print_r($patient_test_entry);
             //   die;
                $doctor = $this->db->where('doctor_id', $patient_test_entry->doctor_id)
                                ->get('doctor')->row();
                ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="hidden"  class="form-control"  id="biomedical_test_id" value="<?php echo $biomedical_test_id ?>"  name="biomedical_test_id">
                            <label class="control-label col-sm-4" for="pwd">Patient's Name</label>
                            <div class="col-sm-8">   

                                <select type="text"   class="form-control" onchange="patient_info_load(this.value)"  id="patient_test_entry_id" name="patient_test_entry_id">
                                    <option selected="" value=""  disabled="">Patient's Name</option> 
                                    <?php
                                    $patient_test_entry_edit = $this->db->where('patient_test_entry_id', $biomedical_test->patient_test_entry_id)
                                                    ->get('patient_test_entry')->row();
                                    ?>
                                    <option value="<?php echo $patient_test_entry_edit->patient_test_entry_id; ?>"><?php echo $patient_test_entry_edit->patient_name . '-' . $patient_test_entry_edit->invoice_no; ?></option>
                                    <?php
                                    $patient_test_entry = $this->db->select('*')->get('patient_test_entry')->result();
                                    foreach ($patient_test_entry as $value) {
                                        ?>
                                        <option value="<?php echo $value->patient_test_entry_id; ?>"><?php echo $value->patient_name . '-' . $value->invoice_no; ?></option>
                                        <?php
                                    }
                                    ?>



                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Age</label>
                            <div class="col-sm-8">

                                <input type="text" class="form-control" value="<?php echo $patient_test_entry_edit->age ?>" placeholder="Enter Age" id="age"  name="age">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">


                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Invoice No</label>
                            <div class="col-sm-8">

                                <input type="text" readonly="" class="form-control" value="<?php echo $patient_test_entry_edit->invoice_no ?>" placeholder="Invoice No" id="invoice_no"  name="invoice_no">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Sex</label>
                            <div class="col-sm-8">
                                <input type="text" readonly=""  value="<?php echo $patient_test_entry_edit->gender ?>"  class="form-control"  id="gender"  name="gender">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">



                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Date</label>
                            <div class="col-sm-8">          
                                <input type="text" readonly=""  value="" class="form-control" value="<?php echo date('d-m-Y', strtotime($patient_test_entry_edit->date)) ?>"  id="datepicker"  name="date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Mobile</label>
                            <div class="col-sm-8">          
                                <input type="text" readonly=""   class="form-control" value="<?php echo $patient_test_entry_edit->mobile ?>" id="mobile"  name="mobile">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Ref. Doctor</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" class="form-control" value="<?php echo $doctor->name . '-' . $doctor->unique_id ?>" id="doctor_name"  name="doctor_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Biomedical Test No</label>
                            <div class="col-sm-8">

                                <input type="text" readonly="" class="form-control"  id="biomedical_test_no" value="<?php echo $biomedical_test->biomedical_test_no ?>" name="biomedical_test_no">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">Fasting Food Sugar(FBS)</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control"  id="fasting_food_sugar" value="<?php echo $biomedical_test->fasting_food_sugar ?>" name="fasting_food_sugar">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">S.Creatinine</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control"  id="s_creatinine" value="<?php echo $biomedical_test->s_creatinine ?>"  name="s_creatinine">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">Corresponding Urine Sugar</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control"  id="corresponding_urine_sugar_fasting_food_sugar"  value="<?php echo $biomedical_test->corresponding_urine_sugar_fasting_food_sugar ?>" name="corresponding_urine_sugar_fasting_food_sugar">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">HbA1c</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control"  id="HbA1c" value="<?php echo $biomedical_test->HbA1c ?>"  name="HbA1c">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">Post Prandial Blood Sugar(PPBS/AL/AD)</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control"  id="post_prandial_blood_sugar_ppbs_al_ad" value="<?php echo $biomedical_test->post_prandial_blood_sugar_ppbs_al_ad ?>"  name="post_prandial_blood_sugar_ppbs_al_ad">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">T.Billirubin</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control"  id="t_billirubin" value="<?php echo $biomedical_test->t_billirubin ?>"  name="t_billirubin">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">Corresponding Urine Sugar</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control"  id="corrponding_urine_sugar_post_prandial_blood_sugar_ppbs_al_ad" value="<?php echo $biomedical_test->corrponding_urine_sugar_post_prandial_blood_sugar_ppbs_al_ad ?>" name="corrponding_urine_sugar_post_prandial_blood_sugar_ppbs_al_ad">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">S.ALT(SGPT)</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control" value="<?php echo $biomedical_test->s_alt_sgpt ?>"  id="s_alt_sgpt"  name="s_alt_sgpt">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">2 Hours after 75gm Glucose</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control" value="<?php echo $biomedical_test->two_hours_after_75gm_glucose ?>" id="two_hours_after_75gm_glucose"  name="two_hours_after_75gm_glucose">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">S.AST(SGOT)</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control" value="<?php echo $biomedical_test->s_ast_sgot ?>" id="s_ast_sgot"  name="s_ast_sgot">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">Corresponding Urine Sugar</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control" value="<?php echo $biomedical_test->corresponding_urine_sugar_2hours_after_75gm_glucose ?>"  id="corresponding_urine_sugar_2hours_after_75gm_glucose"  name="corresponding_urine_sugar_2hours_after_75gm_glucose">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">Alk.Phosphates</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control" value="<?php echo $biomedical_test->alk_phosphates ?>"  id="alk_phosphates"  name="alk_phosphates">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">Random Blood Sugar</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control" value="<?php echo $biomedical_test->random_blood_sugar ?>" id="random_blood_sugar"  name="random_blood_sugar">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">S.Uric Acid</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control" value="<?php echo $biomedical_test->s_uric_acid ?>"  id="s_uric_acid"  name="s_uric_acid">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">Corresponding Urine Sugar</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control"  value="<?php echo $biomedical_test->corresponding_urine_sugar_random_blood_sugar ?>"  id="corresponding_urine_sugar_random_blood_sugar"  name="corresponding_urine_sugar_random_blood_sugar">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">Serum Eletrolytes-Sodium(Na+)</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control" value="<?php echo $biomedical_test->serum_electrolytes_sodium_na_plus ?>"  id="serum_electrolytes_sodium_na_plus"  name="serum_electrolytes_sodium_na_plus">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">Lipid Profile(F)-S.Cholesterol(Total)</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control"  id="lipid_profile_f_s_cholesterol" value="<?php echo $biomedical_test->lipid_profile_f_s_cholesterol ?>"  name="lipid_profile_f_s_cholesterol">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">Serum Eletrolytes-Potassium(K+)</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control"  id="serum_electrolytes_potassium_k_plus" value="<?php echo $biomedical_test->serum_electrolytes_potassium_k_plus ?>"  name="serum_electrolytes_potassium_k_plus">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">Lipid Profile(F)-S.HDL-Cholesterol</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control"  id="lipid_profile_f_s_hdl_cholesterol" value="<?php echo $biomedical_test->lipid_profile_f_s_hdl_cholesterol ?>" name="lipid_profile_f_s_hdl_cholesterol">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">Serum Eletrolytes-Chloride(CL-)</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control"  id="serum_electrolytes_chloride_cl_minus" value="<?php echo $biomedical_test->serum_electrolytes_chloride_cl_minus ?>"  name="serum_electrolytes_chloride_cl_minus">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">Lipid Profile(F)-S.LDL-Cholesterol</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control"  id="lipid_profile_f_s_ldl_cholesterol" value="<?php echo $biomedical_test->lipid_profile_f_s_ldl_cholesterol ?>" name="lipid_profile_f_s_ldl_cholesterol">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name">Lipid Profile(F)-Triglyceride(TG)</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control"  id="lipid_profile_f_triglyceride_tg_cholesterol" value="<?php echo $biomedical_test->lipid_profile_f_triglyceride_tg_cholesterol ?>" name="lipid_profile_f_triglyceride_tg_cholesterol">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name"></label>
                            <div class="col-sm-4">
                                <button type="submit" onclick="return confirm('Do you want to submit')" id="sumbit_button" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    </div>

                </div>


            </form>

        </div>
    </div>
</div>