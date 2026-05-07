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
//                document.getElementById("datepicker").value = data_array[2];/*date*/
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
<style>
    fieldset {
        border:1px solid black;
        border-color: #F00;
        border-style: solid;
    }
    legend
    {

    }
</style>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add Urine Test Data</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" action="<?php echo site_url('TestResultController/add_urine_data_save') ?>" enctype='multipart/form-data'>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Patient's Name</label>
                            <div class="col-sm-8">   

                                <select type="text"   class="form-control" onchange="patient_info_load(this.value)"  id="patient_test_entry_id" name="patient_test_entry_id">
                                    <option selected="" value=""  disabled="">Patient's Name</option> 
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

                                <input type="text" readonly="" class="form-control" placeholder="Enter Age" id="age"  name="age">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">


                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Invoice No</label>
                            <div class="col-sm-8">

                                <input type="text" readonly="" class="form-control" value="" placeholder="Invoice No" id="invoice_no"  name="invoice_no">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Sex</label>
                            <div class="col-sm-8">
                                <input type="text" readonly=""   class="form-control"  id="gender"  name="gender">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">



                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Entry Date</label>
                            <div class="col-sm-8">          
                                <input type="text"  value="<?php echo date('d-m-y')?>" class="form-control"   id="datepicker"  name="date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Mobile</label>
                            <div class="col-sm-8">          
                                <input type="text" readonly=""   class="form-control"  id="mobile"  name="mobile">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Ref. Doctor</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" class="form-control"  id="doctor_name"  name="doctor_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Urine Test No</label>
                            <div class="col-sm-8">
                                <?php
                                $serial = $this->db->select('*')->get('urine_test');
                                $serial = 'UT' . str_pad($serial->num_rows() + 1, 5, '0', STR_PAD_LEFT);
                                ?>
                                <input type="text" readonly="" class="form-control"  id="urine_test_no" value="<?php echo $serial ?>" name="biomedical_test_no">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <fieldset>
                    <legend>Physical Examination</legend>
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Amount</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="physical_examination_amount"  name="physical_examination_amount">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Colour</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="physical_examination_colour"  name="physical_examination_colour">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Smell</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="physical_examination_smell"  name="physical_examination_smell">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Transparency</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="physical_examination_transparency"  name="physical_examination_transparency">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Specific Gravity</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="physical_examination_specific_gravity"  name="physical_examination_specific_gravity">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Sediment</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="physical_examination_sediment"  name="physical_examination_sediment">
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Chemical Examination</legend>
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Reaction</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="chemical_examination_reaction"  name="chemical_examination_reaction">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Sugar</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="chemical_examination_sugar"  name="chemical_examination_sugar">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Albumin</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="chemical_examination_albumin"  name="chemical_examination_albumin">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Phosphate</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="chemical_examination_phosphate"  name="chemical_examination_phosphate">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Kitonebodies</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="chemical_examination_kitonebodies"  name="chemical_examination_kitonebodies">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Bile Salts</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="chemical_examination_bile_salts"  name="chemical_examination_bile_salts">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Bile Pigments</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="chemical_examination_bile_pigments"  name="chemical_examination_bile_pigments">
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Microscopical Examination</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Pus Cells</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="microscopical_examination_pus_cells"  name="microscopical_examination_pus_cells">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Red Cells</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="microscopical_examination_red_cells"  name="microscopical_examination_red_cells">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Epithelial Cells</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="microscopical_examination_epithelial_cells"  name="microscopical_examination_epithelial_cells">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Crystals</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="microscopical_examination_crystals"  name="microscopical_examination_crystals">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Casts</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="microscopical_examination_casts"  name="microscopical_examination_casts">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Bacteria</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="microscopical_examination_bacteria"  name="microscopical_examination_bacteria">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Parasite</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="microscopical_examination_parasite"  name="microscopical_examination_parasite">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Fungus</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="microscopical_examination_fungus"  name="microscopical_examination_fungus">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Spermatocyte</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="microscopical_examination_spermatocyte"  name="microscopical_examination_spermatocyte">
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Others</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="microscopical_examination_others"  name="microscopical_examination_others">
                                </div>
                            </div>
                        </div>


                    </div>
                </fieldset>
                <fieldset>
                    <legend>Pragnency</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Positive</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="pragnency_positive"  name="pragnency_positive">
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-8" for="name">Negative</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control"  id="pragnency_negative"  name="pragnency_negative">
                                </div>
                            </div>
                        </div>


                    </div>
                </fieldset>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-8" for="name"></label>
                            <div class="col-sm-4">
                                <button type="submit" onclick="return confirm('Do you want to submit')" id="sumbit_button" class="btn btn-primary">Submit</button>
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