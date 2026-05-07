<script>
    $(document).ready(function () {
// alert();
        $('#test_id').select2();
        $('#bill_type').select2();


    });
    function bill_type_details_load(bill_type)
    {

        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("bill_type_details_load").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('BillGenericController/bill_type_details_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("bill_type=" + bill_type);
    }
    function test_name_load(test_group_id)
    {

        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("test_id").innerHTML = xhttp.responseText;
//                  var newdiv = document.createElement('tr');
//                newdiv.innerHTML = xhttp.responseText;
                //                document.getElementById('due_history').appendChild(newdiv);
                $('#img').hide();
            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('TestResultController/test_name_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("test_group_id=" + test_group_id);
    }

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
                // document.getElementById("datepicker").value = data_array[2];/*date*/
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
            <h3 style="text-align: center">Bill Generic</h3>
        </div>
        <div class="panel-body">

            <form class="form-horizontal" method="post" action="<?php echo site_url('TestResultController/add_test_result_data_save') ?>" enctype='multipart/form-data'>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Patient's Name</label>
                            <div class="col-sm-8">   

                                <select type="text" required=""  class="form-control" onchange="patient_info_load(this.value)"  id="patient_test_entry_id" name="patient_test_entry_id">
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
                            <label class="control-label col-sm-4" for="name">Patient ID</label>
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
                            <label class="control-label col-sm-4" for="pwd">Date</label>
                            <div class="col-sm-8">          
                                <input type="text"  value="<?php echo date('d-m-y') ?>" class="form-control"  id="datepicker"  name="date">
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
                            <label class="control-label col-sm-4" for="name">Ward/Cabin</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" class="form-control"  id="doctor_name"  name="doctor_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Bed No</label>
                            <div class="col-sm-8">
                                <?php
                                $serial = $this->db->select('*')->get('test_result');
                                $serial = 'TR' . str_pad($serial->num_rows() + 1, 5, '0', STR_PAD_LEFT);
                                ?>
                                <input type="text" readonly="" class="form-control"  id="test_result_no" value="<?php echo $serial ?>" name="test_result_no">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Bill Type</label>
                            <div class="col-sm-8">
                                <select type="text" required="" class="form-control" onchange="bill_type_details_load(this.value)"  id="bill_type"  name="bill_type">
                                    <option value="" disabled="" selected="">Select Bill Type</option>
                                    <option value="consultation_bill">Consultation Bill</option>
                                    <option value="ot_service_bill">OT Service Bill</option>
                                    <option value="patient_service_bill">Patient Service Bill</option>

                                </select>
                            </div>
                        </div>

                    </div>


                </div>
                <hr>
                <div id="test_configuration">

                </div>
                <div class="row">

                    <div class="col-md-8">
                        <div class="form-group">        
                            <div class="col-sm-offset-4 col-sm-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </div>
                        <div class="col-sm-4">
                            <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none"/>

                        </div>
                    </div>
                </div>


            </form>

        </div>
    </div>
</div>