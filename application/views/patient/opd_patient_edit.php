
<script>
    function payable_calculate()
    {
        var discount = $('#discount').val();
        var grand_total = 0;
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
                document.getElementById("doctor_id1").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('PatientController/doctor_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("department_id=" + department_id);
    }
   
    $(document).ready(function () {
        $('#gender1').select2();
        $('#reference_media_id1').select2();
        $('#doctor_id1').select2();
        $('#department_id1').select2();
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
            <h3 style="text-align: center">Edit OPD Patient</h3>
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
                    $opd_patient = $this->db->where('opd_patient_id', $opd_patient_id)->get('opd_patient')->row();
                    $doctor_id = $this->db->select('*')->get('doctor');
                    $doctor_id = str_pad($doctor_id->num_rows() + 1, 5, '0', STR_PAD_LEFT);
                    ?>
                    <form class="form-horizontal" id="patient_form" method="post" action="<?php echo site_url('PatientController/edit_opd_patient_save') ?>" enctype="multipart/form-data">
                        <input name="opd_patient_id" type="hidden" value="<?php echo $opd_patient_id?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Patient Name *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="opd_patient_name" value="<?php echo $opd_patient->opd_patient_name ?>"   name="opd_patient_name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Patient ID </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly="" value="<?php echo $opd_patient->opd_patient_unique_id ?>"  id="opd_patient_unique_id"   name="opd_patient_unique_id">                            
                                    </div>
                                </div>
                            </div>

                        </div>  
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Mobile Number *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="mobile_number" value="<?php echo $opd_patient->mobile_number ?>"  name="mobile_number">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Gender  *</label>
                                    <div class="col-sm-8">
                                        <select type="text" required="" class="form-control" id="gender"  name="gender">
                                            <option><?php echo $opd_patient->gender ?></option> 
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
                                        <select type="text" required class="form-control" onchange="doctor_load(this.value)" id="department_id1"  name="department_id">

                                            <?php
                                            $department = $this->db->where('department_id', $opd_patient->department_id)->get('department')->row();
                                            ?>
                                            <option value="<?php echo $department->department_id ?>"><?php echo $department->department_name ?></option>
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
                                        <select type="text" required="" class="form-control" id="doctor_id1" onchange="visiting_fee_load(this.value), serial_load(this.value)"  name="doctor_id">
                                                                      
                                            <?php
                                            $doctor = $this->db->where('doctor_id', $opd_patient->doctor_id)->get('doctor')->row();
                                            ?>
                                            <option value="<?php echo $doctor->doctor_id ?>"><?php echo $doctor->doctor_name ?></option>

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
                                        <input type="text" class="form-control"  id="datepicker"  value="<?php echo date('d-m-Y', strtotime($opd_patient->entry_date)) ?>"  name="entry_date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Serial Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="serial_numaber"  value="<?php echo $opd_patient->serial_numaber ?>"  name="serial_numaber">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Visiting Date </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="datepicker2"  value="<?php echo date('d-m-Y', strtotime($opd_patient->visiting_date)) ?>"  name="visiting_date">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Visiting Fee </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="visiting_fee"  value="<?php echo $opd_patient->visiting_fee ?>"  name="visiting_fee">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Visiting Time </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="visiting_time"  value="<?php echo $opd_patient->visiting_time ?>"   name="visiting_time">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Discount</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" oninput="payable_calculate()" value="<?php echo $opd_patient->discount ?>" id="discount"  name="discount">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Age</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="age" value="<?php echo $opd_patient->age ?>"  name="age">

                                    </div>
                                </div>


                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Payable *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" required=""   id="payable" value="<?php echo $opd_patient->payable ?>"  name="payable">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Reference Media</label>
                                    <div class="col-sm-8">
                                        <select type="text" class="form-control" id="reference_media_id1"  name="reference_media_id">

                                            <?php
                                            $reference_media = $this->db->where('reference_media_id', $opd_patient->reference_media_id)->get('reference_media')->row();
                                            ?>
                                            <option value="<?php echo $reference_media->reference_media_id ?>"><?php echo $reference_media->reference_media_name ?></option>
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
                                        <input type="text" class="form-control" value="<?php echo $opd_patient->referred_by ?>"  id="referred_by"  name="referred_by">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">        
                                    <div class="col-sm-offset-4 col-sm-4">
                                        <button type="submit" class="btn btn-primary">Update</button>
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


