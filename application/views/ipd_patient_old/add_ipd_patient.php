function bed_number_load(item_id) {
        
        $('#img').show();
        var id_no = item_id.split("_");
        var item_id = $('#item_id_' + id_no[2]).val();
        // alert(id_no[2]);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("available_quantity_" + id_no[2]).value = xhttp.responseText;
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('IssueController/available_quantity_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("item_id=" + item_id);

    }
<script>

    $().ready(function () {
        // validate the comment form when it is submitted       
        // validate signup form on keyup and submit
        $("#patient_form").validate({
            rules: {
                patient_name: "required",
                mobile_number: {
                    required: true,
                    minlength: 11
                },
                gender: "required",
                age: "required",

            },
            messages: {
                patient_name: "Please Enter Patient Name",
                age: "Please enter age",
                mobile_number: {
                    required: "Please enter a valid mobile number",
                    minlength: "Your mobile number must consist of at least 13 characters"
                },
                gender: "Select Item Type",
            }
        });
    });
    $(document).ready(function() {
       

        // On form submission
        $('#submit_button').click(function(e) {
            e.preventDefault();
            var submitBtn = $(this);
            var formData = $('#patient_form').serialize();

            // Check if the form is valid
            if ($("#patient_form").valid()) {
                $('#patient_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('PatientController/add_ipd_patient_save'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            $('#patient_form')[0].reset();
                            $('#patient_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            window.location.href = "<?php echo base_url('print-issue') ?>";
                        } else {
                            alert('Error: ' + response.message);
                            $('#patient_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#patient_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
    $(document).ready(function () {
        $('#gender').select2();
        $('#doctor_id').select2();
        $('#reference_media_id').select2();
        $('#bed_id').select2();
        $('#cabin_id').select2();

    });
    jQuery(document).ready(function () {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
            $(this).slideUp('slow', function () {
                $(this).remove();
            });
        });
    });
    $(function () {
        $('[name="admission_time"]').timeselector()
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
            <h3 style="text-align: center">Add IPD Patient</h3>
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

                    <form class="form-horizontal" id="patient_form" method="post"  enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Patient Name *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="patient_name"   name="patient_name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Ward/Cabin </label>
                                    <div class="col-sm-8">
                                        <select type="text" onchange="bed_number_load(this.value)" class="form-control" id="cabin_id"  name="cabin_id">
                                            <option selected="" disabled="" value="">Select Ward/Cabin</option>   
                                            <option>Ward</option>
                                            <option>Cabin</option>
                                        </select>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Mobile Number *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="mobile_number"   name="mobile_number">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Bed Number</label>
                                    <div class="col-sm-8">

                                        <select type="text" class="form-control" id="bed_id"  name="bed_id">
                                            <option selected="" disabled="" value="">Select Bed</option>                                
                                           
                                        </select>    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Unique ID</label>
                                    <div class="col-sm-8">
                                
                                        <?php
                                        $uniqu_id = $this->db->select('*')->order_by('patientunique_id', 'DESC')->limit('1')->get('patient_unique_table')->row();
                                        $patient_unique_id = 'IPD/' . date('d-m-Y') . '/' . $uniqu_id->id_serial + 1;
                                        ?>
                                         <input type="hidden" readonly="" class="form-control" value="<?php echo $uniqu_id->id_serial + 1 ?>" id="id_serial" name="id_serial">
                                        <input type="text" readonly="" class="form-control" value="<?php echo $patient_unique_id ?>"  id="patient_unique_id"   name="patient_unique_id">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Date *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="datepicker"  value="<?php echo date('d-m-Y') ?>"  name="date">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Gender </label>
                                    <div class="col-sm-8">          
                                        <select type="text"     class="form-control" id="gender"  name="gender">
                                            <option selected="" disabled="" value="">Select Gender</option>
                                            <option>Male</option>
                                            <option>Female</option>
                                            <option>Other</option>                               
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Time </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="admission_time"  value="<?php echo date("h:i:s") ?>"  name="admission_time">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Age</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="age"   name="age">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Reference Doctor</label>
                                    <div class="col-sm-8">          
                                        <select type="text" class="form-control" id="doctor_id"  name="doctor_id">
                                            <option selected="" disabled="" value="">Select Reference Doctor</option>                                
                                            <?php
                                            $doctor = $this->db->select('*')->get('doctor')->result();
                                            foreach ($doctor as $doctor_value) {
                                                ?>
                                                <option value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->name ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Paid Amount</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="paid_amount"  name="paid_amount">
                                    </div>
                                </div>

                            </div>
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
                        </div>
                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-6">
                                <div class="form-group">        
                                    <div class="col-sm-offset-4 col-sm-8">
                                    <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Submit</button>
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
    </div>

</div>


