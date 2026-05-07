
<script>
    function payable_calculate()
    {


        var discount = $('#discount').val();
        var grand_total=0;
        var visiting_fee = $('#visiting_fee').val();
        var length = discount.length;

        if (discount[length - 1] == '%')
        {
            discount = discount.split("%");
            grand_total = Math.ceil(visiting_fee - (visiting_fee * (Number(discount[0]) / 100)));
        } else
        {
            grand_total = Math.ceil(visiting_fee - discount);
        }
        $('#payable').val(Math.ceil(grand_total));
    }
    function visiting_fee_load(doctor_id)
    {
        $('#img').show();
        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("visiting_fee").value = xhttp.responseText;
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('PatientController/visiting_fee_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("doctor_id=" + doctor_id);
    }
    function serial_load(doctor_id)
    {
        $('#img').show();
        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("serial_numaber").value = xhttp.responseText;
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('PatientController/serial_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("doctor_id=" + doctor_id);
    }
    function doctor_load(department_id)
    {
        $('#img').show();
        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("doctor_id").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('PatientController/doctor_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("department_id=" + department_id);
    }
    $().ready(function () {
        // validate the comment form when it is submitted       
        // validate signup form on keyup and submit
        $("#patient_form1").validate({
            rules: {

                patient_name: "required",

                mobile_number: {
                    required: true,
                    minlength: 11
                },
                gender: "required",

            },
            messages: {
                patient_name: "Please Enter Patient Name",
                mobile_number: {
                    required: "Please enter a valid mobile number",
                    minlength: "Your mobile number must consist of at least 13 characters"
                },
                gender: "Select Item Type",
            }
        });
    });
    $(document).ready(function () {
        $('#gender').select2();
        $('#doctor_id').select2();
        $('#reference_media_id').select2();
        $('#doctor_id').select2();
        $('#department_id').select2();



    });
    jQuery(document).ready(function () {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
            $(this).slideUp('slow', function () {
                $(this).remove();
            });
        });
    });
    $(function () {
        $('[name="visiting_time"]').timeselector()
    });

    function total_price_cal()
    {
        var quantity = $('#quantity').val();
        var unit_price = $('#unit_price').val();
        $('#total_price').val(Number(quantity) * Number(unit_price));
    }
</script>
<div class="container-fluid" style=" background-color: white;width: 98%;">

    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add OPD Patient</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                        <?php
                        if ($this->session->userdata('success') != '') {
                            ?>                   
                            <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a> <strong>Success!</strong>Data has been saved successfully.</p>                      
                            <?php
                            $sdata['success'] = '';
                            $this->session->set_userdata($sdata);
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-12">
                    <?php
                    $doctor_id = $this->db->select('*')->get('opd_patient');
                    $doctor_id = str_pad($doctor_id->num_rows() + 1, 5, '0', STR_PAD_LEFT);
                    ?>
                    <form class="form-horizontal" id="patient_form" method="post" action="<?php echo site_url('PatientController/add_opd_patient_save') ?>" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Patient Name *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" required=""  id="opd_patient_name"   name="opd_patient_name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Patient ID </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly="" value="<?php echo $doctor_id ?>"  id="opd_patient_unique_id"   name="opd_patient_unique_id">                            
                                    </div>
                                </div>
                            </div>

                        </div>  
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Mobile Number *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="mobile_number" required=""  name="mobile_number">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Gender  *</label>
                                    <div class="col-sm-8">
                                        <select type="text" required="" class="form-control" id="gender"  name="gender">
                                            <option selected="" disabled="" value="">Select Gender</option> 
                                            <option>Male</option>
                                            <option>Female</option>
                                            <option>Other</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Department *</label>
                                    <div class="col-sm-8">
                                        <select type="text" required class="form-control" onchange="doctor_load(this.value)" id="department_id"  name="department_id">

                                            <?php
                                            $department = $this->db->select('*')->get('department')->result();
                                            foreach ($department as $department_value) {
                                                ?>
                                                <option value="<?php echo $department_value->department_id ?>"><?php echo $department_value->department_name ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Doctor  *</label>
                                    <div class="col-sm-8">
                                        <select type="text" required="" class="form-control" id="doctor_id" onchange="visiting_fee_load(this.value), serial_load(this.value)"  name="doctor_id">
                                            <option selected="" disabled="" value="">Select Doctor</option>                                

                                        </select>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Entry Date </label>
                                    <div class="col-sm-8">          
                                        <input type="text" class="form-control"  id="datepicker"  value="<?php echo date('d-m-Y') ?>"  name="entry_date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Serial Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="serial_numaber"  value=""  name="serial_numaber">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Visiting Date </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="datepicker2"  value="<?php echo date('d-m-Y') ?>"  name="visiting_date">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Visiting Fee </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="visiting_fee"   name="visiting_fee">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Visiting Time </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="visiting_time"  value="<?php echo date("h:i:s") ?>"  name="visiting_time">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Discount</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" oninput="payable_calculate()"  id="discount"  name="discount">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Age</label>
                                    <div class="col-sm-8">
                                        <input oninput="validateIntegerInput(this)" type="text" class="form-control"  id="age"   name="age">

                                    </div>
                                </div>


                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Payable *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" required=""  id="payable"  name="payable">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Reference Media</label>
                                    <div class="col-sm-8">
                                        <select type="text" class="form-control" id="reference_media_id"  name="reference_media_id">
                                            <option selected="" disabled="" value="">Select Reference Media</option>                                
                                            <?php
                                            $reference_media = $this->db->select('*')->get('reference_media')->result();
                                            foreach ($reference_media as $reference_media_value) {
                                                ?>
                                                <option value="<?php echo $reference_media_value->reference_media_id ?>"><?php echo $reference_media_value->reference_media_name ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Referred By</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"   id="referred_by"  name="referred_by">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">        
                                    <div class="col-sm-offset-4 col-sm-4">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <img src="<?php echo base_url() ?>images/ajax-loader.gif" id="img" style="display:none"/>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>

</div>


