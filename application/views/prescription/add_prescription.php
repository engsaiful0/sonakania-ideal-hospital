<script>
    function SomeDeleteRowFunction(btndel) {

        if (typeof (btndel) == "object") {
            $(btndel).closest("tr").remove();
        } else {
            return false;
        }
    }
    $(document).ready(function () {
        $('#gender').select2();
        $('#follow_up_day_month_year').select2();
        for (var i = 1; i <= 6; i++)
        {
            $('#type_name_id_' + i).select2();
            $('#medicin_id_' + i).select2();
            $('#medicin_times_id_' + i).select2();
            $('#advice_' + i).select2();
            $('#diagnosis_' + i).select2();
            $('#day_or_month_or_year_or_colbay_' + i).select2();
        }
    });
    function load_diagnosis_row()
    {
        $('#img').show();
        var id = document.getElementById("idControlDiagnosis").value * 1;


        document.getElementById("idControlDiagnosis").value = id + 1;
        id = Number(id) + 1;
        // alert(id);

        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {

                var newdiv = document.createElement('tr');
                newdiv.innerHTML = xhttp.responseText;
                document.getElementById('diagnosis_table').appendChild(newdiv);
                //  for (var i = 2; i <= id; i++)
                // {                  
                $('#diagnosis_' + id).select2();
                //  }

                $('#img').hide();
            }
        }

        xhttp.open("POST", "<?php echo site_url('PrescriptionController/load_diagnosis_row'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("id=" + id);
    }
    function load_advice_row()
    {
        $('#img').show();
        var id = document.getElementById("idControlAdvice").value * 1;


        document.getElementById("idControlAdvice").value = id + 1;
        id = Number(id) + 1;
        // alert(id);

        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {

                var newdiv = document.createElement('tr');
                newdiv.innerHTML = xhttp.responseText;
                document.getElementById('advice_table').appendChild(newdiv);
                //  for (var i = 2; i <= id; i++)
                // {                  
                $('#advice_' + id).select2();
                //  }

                $('#img').hide();
            }
        }

        xhttp.open("POST", "<?php echo site_url('PrescriptionController/load_advice_row'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("id=" + id);
    }
    function load_product_row() {
        $('#img').show();
        var id = document.getElementById("idControl").value * 1;


        document.getElementById("idControl").value = id + 1;
        id = Number(id) + 1;
        // alert(id);

        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {

                var newdiv = document.createElement('tr');
                newdiv.innerHTML = xhttp.responseText;
                document.getElementById('product_table').appendChild(newdiv);
                for (var i = 2; i <= id; i++)
                {

                    $('#type_name_id_' + i).select2();
                    $('#medicin_id_' + i).select2();
                    $('#medicin_times_id_' + i).select2();
                    $('#day_or_month_or_year_or_colbay_' + i).select2();


                }

                $('#img').hide();
            }
        }

        xhttp.open("POST", "<?php echo site_url('PrescriptionController/load_product_row'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("id=" + id);
    }
    function drug_name_load(type_name)
    {
        $('#img').show();
        var xhttp = new XMLHttpRequest();

        var data = type_name.split("_");
        //alert(data);
        var type_name_val = document.getElementById(type_name).value;
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {

                document.getElementById("medicin_id_" + data[3]).innerHTML = xhttp.responseText;
                //alert(xhttp.responseText);

//                $("#type_name_" + data[3]).select2();
                $('#img').hide();
            }
        }

        xhttp.open("POST", "<?php echo site_url('ProductController/add_drug_name'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhttp.send("type_name_val=" + type_name_val);
    }
</script>

<div class="container-fluid" style=" background-color: white;width: 98%">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add Prescription</h3>
        </div>

        <div class="panel-body" style="min-height: 900px;">
            <?php
            if ($this->session->userdata('success') != '') {
                ?>
                <div class="alert alert-success">
                    <strong>Success!</strong>Data has been saved successfully.
                </div>
                <?php
                $sdata['success'] = '';
                $this->session->set_userdata($sdata);
            }
            ?>
            <form class="form-horizontal" method="post" action="<?php echo site_url('PrescriptionController/add_prescription_save') ?>" enctype='multipart/form-data'>


                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Patient's Name</label>
                            <div class="col-sm-8">          
                                <input type="text" required="" class="form-control"  id="patient_name" placeholder="Enter Patient Name" name="patient_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Age</label>
                            <div class="col-sm-8">

                                <input type="text" required=""  class="form-control" placeholder="Enter Age" id="age"  name="age">

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Serial No</label>
                            <div class="col-sm-8">
                                <?php
                                $invoice_id = $this->db->select('*')->get('prescription');
                                $invoice_no = str_pad($invoice_id->num_rows() + 1, 5, '0', STR_PAD_LEFT);
                                ?>
                                <input type="text" readonly="" class="form-control" value="<?php echo $invoice_no ?>" placeholder="Invoice No" id="invoice_no"  name="invoice_no">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">


                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Date</label>
                            <div class="col-sm-8">          
                                <input type="text"   required=""  value="<?php echo date('d-m-Y'); ?>" class="form-control"  id="datepicker"  name="date">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">

                            <label class="control-label col-sm-4" for="pwd">BP</label>
                            <div class="col-sm-8">          
                                <input type="text"      class="form-control"  id="bp"  name="bp">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Gender</label>
                            <div class="col-sm-8">
                                <select type="text" required=""    class="form-control"  id="gender" name="gender">
                                    <option selected="" value=""  disabled="">Select Gender</option> 
                                    <option>Male</option> 
                                    <option>Female</option> 
                                    <option>Other</option>
                                    ?>

                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Follow Up</label>
                            <div class="col-sm-8">          
                                <input type="text"    class="form-control"  id="follow_up"  name="follow_up">
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4">

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">দিন/মাস/বছর/</label>
                            <div class="col-sm-8">
                                <select type="text" required=""    class="form-control"  id="follow_up_day_month_year" name="follow_up_day_month_year">
                                    <option selected="" value=""  disabled="">Select দিন/মাস/বছর/</option> 
                                    <option>দিন</option>
                                    <option>মাস</option>
                                    <option>বছর</option>

                                    ?>

                                </select>
                            </div>
                        </div>
                    </div>

                </div>



                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">  
                            <table id="product_table" class="table table-bordered table-hover table-striped">
                                <input type="hidden"   id="idControl" value="6">
                                <input type="hidden"   id="current_id" value="6">

                                <input type="hidden"   id="idControlAdvice" value="6">
                                <input type="hidden"   id="current_idAvice" value="6">

                                <input type="hidden"   id="idControlDiagnosis" value="6">
                                <input type="hidden"   id="current_idDiagnosis" value="6">
                                <tr id="1">
                                    <td>Sl</td>
                                    <td>Drug Type</td>                           
                                    <td>Drug</td>
                                    <td>Times</td>
                                    <td>How Many</td>
                                    <td>How Many</td>
                                    <td>দিন/মাস/বছর/চলবে</td>
                                </tr>
                                <tr>
                                    <td style="width: 2%;">1</td>
                                    <td style="width: 20%;">


                                        <select name="drug_type_id[]" class="form-control" id="type_name_id_1" onchange="drug_name_load(this.id)"  style="width:100%;">
                                            <option value="" selected="">Select Type</option>
                                            <?php
                                            $sql = $this->db->select('*')->get('drug_type')->result();

                                            foreach ($sql as $value) {
                                                ?>
                                                <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </td>


                                    <td  style="width: 16%;">
                                        <select type="text" style="width: 100%"     class="form-control"  id="medicin_id_1" name="drug_id[]">
                                            <option selected="" value="" disabled=""></option> 

                                        </select>

                                    </td>
                                    <td  style="width: 15%;">
                                        <select type="text" style="width: 100%"     class="form-control"  id="medicin_times_id_1" name="medicin_times_id[]">
                                            <option selected="" value="" disabled=""></option> 
                                            <?php
                                            $medicin_times = $this->db->select('*')->get('medicin_times')->result();
                                            foreach ($medicin_times as $value) {
                                                ?>
                                                <option value="<?php echo $value->medicin_times_id; ?>"><?php echo $value->medicin_times_name; ?></option>
                                                <?php
                                            }
                                            ?>

                                        </select>
                                    </td>
                                    <td  style="width: 15%;">

                                        <input type="text" style="width: 100%"    class="form-control"  id="days_1"  name="days[]">
                                    </td>
                                    <td  style="width: 15%;">
                                        <select type="text" style="width: 100%"    class="form-control"  id="day_or_month_or_year_or_colbay_1" placeholder="Days" name="day_or_month_or_year_or_colbay[]">
                                            <option>দিন</option>
                                            <option>মাস</option>
                                            <option>বছর</option>
                                            <option>চলবে</option>
                                        </select>
                                    </td>
                                    <td  style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td style="width: 20%;">
                                        <select name="drug_type_id[]" class="form-control" id="type_name_id_2" onchange="drug_name_load(this.id)"  style="width:100%;">
                                            <option value="" selected="">Select Type</option>
                                            <?php
                                            $sql = $this->db->select('*')->get('drug_type')->result();

                                            foreach ($sql as $value) {
                                                ?>
                                                <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </td>


                                    <td  style="width: 16%;">
                                        <select type="text"    style="width: 100%"  class="form-control"  id="medicin_id_2" name="drug_id[]">
                                            <option selected="" value="" disabled=""></option> 

                                        </select>

                                    </td>
                                    <td  style="width: 15%;">
                                        <select type="text"    style="width: 100%"  class="form-control"  id="medicin_times_id_2" name="medicin_times_id[]">
                                            <option selected="" value="" disabled=""></option> 
                                            <?php
                                            $medicin_times = $this->db->select('*')->get('medicin_times')->result();
                                            foreach ($medicin_times as $value) {
                                                ?>
                                                <option value="<?php echo $value->medicin_times_id; ?>"><?php echo $value->medicin_times_name; ?></option>
                                                <?php
                                            }
                                            ?>

                                        </select>
                                    </td>
                                    <td  style="width: 15%;">

                                        <input type="text" style="width: 100%"    class="form-control"  id="days_2"  name="days[]">
                                    </td>
                                    <td  style="width: 15%;">

                                        <select type="text"    style="width: 100%"  class="form-control"  id="day_or_month_or_year_or_colbay_2"  name="day_or_month_or_year_or_colbay[]">
                                            <option>দিন</option>
                                            <option>মাস</option>
                                            <option>বছর</option>
                                            <option>চলবে</option>
                                        </select>
                                    </td>
                                    <td  style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td style="width: 20%;">
                                        <select name="drug_type_id[]" class="form-control" id="type_name_id_3" onchange="drug_name_load(this.id)"  style="width:100%;">
                                            <option value="" selected="">Select Type</option>
                                            <?php
                                            $sql = $this->db->select('*')->get('drug_type')->result();

                                            foreach ($sql as $value) {
                                                ?>
                                                <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </td>


                                    <td  style="width: 16%;">
                                        <select type="text"  style="width: 100%"  class="form-control"  id="medicin_id_3" name="drug_id[]">
                                            <option selected="" value="" disabled=""></option> 

                                        </select>

                                    </td>
                                    <td  style="width: 15%;">
                                        <select type="text" style="width: 100%"  class="form-control"  id="medicin_times_id_3" name="medicin_times_id[]">
                                            <option selected="" value="" disabled=""></option> 
                                            <?php
                                            $medicin_times = $this->db->select('*')->get('medicin_times')->result();
                                            foreach ($medicin_times as $value) {
                                                ?>
                                                <option value="<?php echo $value->medicin_times_id; ?>"><?php echo $value->medicin_times_name; ?></option>
                                                <?php
                                            }
                                            ?>

                                        </select>
                                    </td>
                                    <td  style="width: 15%;">

                                        <input type="text" style="width: 100%"    class="form-control"  id="days_3"  name="days[]">
                                    </td>
                                    <td  style="width: 15%;">

                                        <select type="text"  style="width: 100%" class="form-control"  id="day_or_month_or_year_or_colbay_3"  name="day_or_month_or_year_or_colbay[]">

                                            <option>দিন</option>
                                            <option>মাস</option>
                                            <option>বছর</option>
                                            <option>চলবে</option>
                                        </select>
                                    </td>
                                    <td  style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </tr>

                                <tr>
                                    <td>4</td>
                                    <td style="width: 20%;">
                                        <select name="drug_type_id[]" class="form-control" id="type_name_id_4" onchange="drug_name_load(this.id)"  style="width:100%;">
                                            <option value="" selected="">Select Type</option>
                                            <?php
                                            $sql = $this->db->select('*')->get('drug_type')->result();

                                            foreach ($sql as $value) {
                                                ?>
                                                <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </td>


                                    <td  style="width: 16%;">
                                        <select type="text"    style="width: 100%"  class="form-control"  id="medicin_id_4" name="drug_id[]">
                                            <option selected="" value="" disabled=""></option> 

                                        </select>

                                    </td>
                                    <td  style="width: 15%;">
                                        <select type="text"    style="width: 100%"  class="form-control"  id="medicin_times_id_4" name="medicin_times_id[]">
                                            <option selected="" value="" disabled=""></option> 
                                            <?php
                                            $medicin_times = $this->db->select('*')->get('medicin_times')->result();
                                            foreach ($medicin_times as $value) {
                                                ?>
                                                <option value="<?php echo $value->medicin_times_id; ?>"><?php echo $value->medicin_times_name; ?></option>
                                                <?php
                                            }
                                            ?>

                                        </select>
                                    </td>
                                    <td  style="width: 15%;">

                                        <input type="text" style="width: 100%"    class="form-control"  id="days_4"  name="days[]">
                                    </td>
                                    <td  style="width: 15%;">

                                        <select type="text"    style="width: 100%" class="form-control"  id="day_or_month_or_year_or_colbay_4"  name="day_or_month_or_year_or_colbay[]">
                                            <option>দিন</option>
                                            <option>মাস</option>
                                            <option>বছর</option>
                                            <option>চলবে</option>
                                        </select>
                                    </td>
                                    <td  style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td style="width: 20%;">
                                        <select name="drug_type_id[]" class="form-control" id="type_name_id_5" onchange="drug_name_load(this.id)"  style="width:100%;">
                                            <option value="" selected="">Select Type</option>
                                            <?php
                                            $sql = $this->db->select('*')->get('drug_type')->result();

                                            foreach ($sql as $value) {
                                                ?>
                                                <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </td>


                                    <td  style="width: 16%;">
                                        <select type="text"    style="width: 100%"  class="form-control"  id="medicin_id_5" name="drug_id[]">
                                            <option selected="" value="" disabled=""></option> 

                                        </select>

                                    </td>
                                    <td  style="width: 15%;">
                                        <select type="text"    style="width: 100%"  class="form-control"  id="medicin_times_id_5" name="medicin_times_id[]">
                                            <option selected="" value="" disabled=""></option> 
                                            <?php
                                            $medicin_times = $this->db->select('*')->get('medicin_times')->result();
                                            foreach ($medicin_times as $value) {
                                                ?>
                                                <option value="<?php echo $value->medicin_times_id; ?>"><?php echo $value->medicin_times_name; ?></option>
                                                <?php
                                            }
                                            ?>

                                        </select>
                                    </td>
                                    <td  style="width: 15%;">

                                        <input type="text" style="width: 100%"    class="form-control"  id="days_5"  name="days[]">
                                    </td>
                                    <td  style="width: 15%;">

                                        <select type="text"    style="width: 100%" class="form-control"  id="day_or_month_or_year_or_colbay_5"  name="day_or_month_or_year_or_colbay[]">
                                            <option>দিন</option>
                                            <option>মাস</option>
                                            <option>বছর</option>
                                            <option>চলবে</option>
                                        </select>
                                    </td>
                                    <td  style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td style="width: 20%;">
                                        <select name="drug_type_id[]" class="form-control" id="type_name_id_6" onchange="drug_name_load(this.id)"  style="width:100%;">
                                            <option value="" selected="">Select Type</option>
                                            <?php
                                            $sql = $this->db->select('*')->get('drug_type')->result();

                                            foreach ($sql as $value) {
                                                ?>
                                                <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </td>


                                    <td  style="width: 16%;">
                                        <select type="text"    style="width: 100%"  class="form-control"  id="medicin_id_6" name="drug_id[]">
                                            <option selected="" value="" disabled=""></option> 

                                        </select>

                                    </td>
                                    <td  style="width: 15%;">
                                        <select type="text"     style="width: 100%" class="form-control"  id="medicin_times_id_6" name="medicin_times_id[]">
                                            <option selected="" value="" disabled=""></option> 
                                            <?php
                                            $medicin_times = $this->db->select('*')->get('medicin_times')->result();
                                            foreach ($medicin_times as $value) {
                                                ?>
                                                <option value="<?php echo $value->medicin_times_id; ?>"><?php echo $value->medicin_times_name; ?></option>
                                                <?php
                                            }
                                            ?>

                                        </select>
                                    </td>
                                    <td  style="width: 15%;">

                                        <input type="text" style="width: 100%"    class="form-control"  id="days_6"  name="days[]">
                                    </td>
                                    <td  style="width: 15%;">

                                        <select type="text"    style="width: 100%" class="form-control"  id="day_or_month_or_year_or_colbay_6"  name="day_or_month_or_year_or_colbay[]">
                                            <option>দিন</option>
                                            <option>মাস</option>
                                            <option>বছর</option>
                                            <option>চলবে</option>
                                        </select>
                                    </td>
                                    <td  style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </tr>

                            </table>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <table id="diagnosis_table" class="table table-bordered table-hover table-striped">


                            <tr>
                                <td>1</td>
                                <td>
                                    <select name="diagnosis_id[]" class="form-control" id="diagnosis_1" style="width:100%;">
                                        <option value="" disabled="" selected="">History </option>
                                        <?php
                                        $diagnosis = $this->db->select('*')->get('diagnosis')->result();

                                        foreach ($diagnosis as $value) {
                                            ?>
                                            <option value="<?php echo $value->diagnosis_id ?>"><?php echo $value->diagnosis_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td  style="width: 2%;"><input type="button" onclick="load_diagnosis_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <select name="diagnosis_id[]" class="form-control" id="diagnosis_2" style="width:100%;">
                                        <option value="" disabled="" selected="">History</option>
                                        <?php
                                        $diagnosis = $this->db->select('*')->get('diagnosis')->result();

                                        foreach ($diagnosis as $value) {
                                            ?>
                                            <option value="<?php echo $value->diagnosis_id ?>"><?php echo $value->diagnosis_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td  style="width: 2%;"><input type="button" onclick="load_diagnosis_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    <select name="diagnosis_id[]" class="form-control" id="diagnosis_3" style="width:100%;">
                                        <option value="" disabled="" selected="">History</option>
                                        <?php
                                        $diagnosis = $this->db->select('*')->get('diagnosis')->result();

                                        foreach ($diagnosis as $value) {
                                            ?>
                                            <option value="<?php echo $value->diagnosis_id ?>"><?php echo $value->diagnosis_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td  style="width: 2%;"><input type="button" onclick="load_diagnosis_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>
                                    <select name="diagnosis_id[]" class="form-control" id="diagnosis_4" style="width:100%;">
                                        <option value="" disabled="" selected="">History</option>
                                        <?php
                                        $diagnosis = $this->db->select('*')->get('diagnosis')->result();

                                        foreach ($diagnosis as $value) {
                                            ?>
                                            <option value="<?php echo $value->diagnosis_id ?>"><?php echo $value->diagnosis_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td  style="width: 2%;"><input type="button" onclick="load_diagnosis_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>
                                    <select name="diagnosis_id[]" class="form-control" id="diagnosis_5" style="width:100%;">
                                        <option value="" disabled="" selected="">History</option>
                                        <?php
                                        $diagnosis = $this->db->select('*')->get('diagnosis')->result();

                                        foreach ($diagnosis as $value) {
                                            ?>
                                            <option value="<?php echo $value->diagnosis_id ?>"><?php echo $value->diagnosis_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td  style="width: 2%;"><input type="button" onclick="load_diagnosis_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>
                                    <select name="diagnosis_id[]" class="form-control" id="diagnosis_6" style="width:100%;">
                                        <option value="" disabled="" selected="">History</option>
                                        <?php
                                        $diagnosis = $this->db->select('*')->get('diagnosis')->result();

                                        foreach ($diagnosis as $value) {
                                            ?>
                                            <option value="<?php echo $value->diagnosis_id ?>"><?php echo $value->diagnosis_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td  style="width: 2%;"><input type="button" onclick="load_diagnosis_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <table id="advice_table" class="table table-bordered table-hover table-striped" >


                            <tr>
                                <td>1</td>
                                <td>
                                    <select name="advice_id[]" class="form-control" id="advice_1" style="width:100%;">
                                        <option value="" disabled="" selected="">Advice</option>
                                        <?php
                                        $advice = $this->db->select('*')->get('advice')->result();

                                        foreach ($advice as $value) {
                                            ?>
                                            <option value="<?php echo $value->advice_id ?>"><?php echo $value->advice_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td  style="width: 2%;"><input type="button" onclick="load_advice_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <select name="advice_id[]" class="form-control" id="advice_2" style="width:100%;">
                                        <option value="" disabled="" selected="">Advice</option>
                                        <?php
                                        $advice = $this->db->select('*')->get('advice')->result();

                                        foreach ($advice as $value) {
                                            ?>
                                            <option value="<?php echo $value->advice_id ?>"><?php echo $value->advice_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td  style="width: 2%;"><input type="button" onclick="load_advice_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    <select name="advice_id[]" class="form-control" id="advice_3" style="width:100%;">
                                        <option value="" disabled="" selected="">Advice</option>
                                        <?php
                                        $advice = $this->db->select('*')->get('advice')->result();

                                        foreach ($advice as $value) {
                                            ?>
                                            <option value="<?php echo $value->advice_id ?>"><?php echo $value->advice_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td  style="width: 2%;"><input type="button" onclick="load_advice_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>
                                    <select name="advice_id[]" class="form-control" id="advice_4" style="width:100%;">
                                        <option value="" disabled="" selected="">Advice</option>
                                        <?php
                                        $advice = $this->db->select('*')->get('advice')->result();

                                        foreach ($advice as $value) {
                                            ?>
                                            <option value="<?php echo $value->advice_id ?>"><?php echo $value->advice_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td  style="width: 2%;"><input type="button" onclick="load_advice_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>
                                    <select name="advice_id[]" class="form-control" id="advice_5" style="width:100%;">
                                        <option value="" disabled="" selected="">Advice</option>
                                        <?php
                                        $advice = $this->db->select('*')->get('advice')->result();

                                        foreach ($advice as $value) {
                                            ?>
                                            <option value="<?php echo $value->advice_id ?>"><?php echo $value->advice_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td  style="width: 2%;"><input type="button" onclick="load_advice_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>
                                    <select name="advice_id[]" class="form-control" id="advice_6" style="width:100%;">
                                        <option value="" disabled="" selected="">Advice</option>
                                        <?php
                                        $advice = $this->db->select('*')->get('advice')->result();

                                        foreach ($advice as $value) {
                                            ?>
                                            <option value="<?php echo $value->advice_id ?>"><?php echo $value->advice_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td  style="width: 2%;"><input type="button" onclick="load_advice_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"  ></td>
                                </td>
                            </tr>

                        </table>
                        <div class="col-sm-3">
                            <button type="submit" id="sumbit_button" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">




                    </div>

                </div>


        </div>


    </div>

</form>
</div>



