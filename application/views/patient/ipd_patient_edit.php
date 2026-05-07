<script>
     $().ready(function() {
        // validate the comment form when it is submitted       
        // validate signup form on keyup and submit
        $("#patient_form").validate({
            rules: {
                patient_name: "required",
                ward_or_bed: "required",

                mobile_number: {
                    required: true,
                    minlength: 11
                },
                gender: "required",
                age: "required",
            },
            messages: {
                patient_name: "Please Enter Patient Name",
                patient_name: "Please select ward or bed",
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
                    url: "<?php echo base_url('IpdPatientController/edit_ipd_patient_save'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {

                            $('#patient_form')[0].reset();
                            $('#patient_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');

                            $.toast({
                                heading: 'Success',
                                text: 'Data has been saved successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 2000,
                                icon: 'success'
                            });
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('print-ipd-patient') ?>";
                            }, 2002);
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
    $(document).ready(function() {
        $('#gender').select2();
        $('#doctor_id').select2();
        $('#reference_media_id').select2();
        $('#bed_id').select2();
        $('#cabin_id').select2();
        $('#ward_id').select2();
        $('#ward_or_bed').select2();
        $('#cabin_category_id').select2();
        

    });
    jQuery(document).ready(function() {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });
    $(function() {
        $('[name="admission_time"]').timeselector()
    });

    function total_price_cal() {
        var quantity = $('#quantity').val();
        var unit_price = $('#unit_price').val();
        $('#total_price').val(Number(quantity) * Number(unit_price));
    }

    function cabin_or_ward_selection(cabin_or_ward) {

        if (cabin_or_ward == 'Cabin') {
            document.getElementById('cabin_container').style.display = "block";
            document.getElementById('ward_container').style.display = "none";
            // Reset ward_id and bed_id
            document.getElementById('ward_id').selectedIndex = 0;
            document.getElementById('bed_id').selectedIndex = 0;
            document.getElementById('bed_id').innerHTML = '<option selected="" disabled="" value="">Select Bed</option>';
        } else if (cabin_or_ward == 'Ward') {
            document.getElementById('ward_container').style.display = "block";
            document.getElementById('cabin_container').style.display = "none";
        }

    }

    function bed_number_load(ward_id) {

        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("bed_id").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('PatientController/bed_number_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("ward_id=" + ward_id);

    }

    function cabin_number_load(cabin_category_id) {

        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("cabin_id").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('IpdPatientController/cabin_number_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("cabin_category_id=" + cabin_category_id);

    }
</script>


<div class="panel panel-primary" style="width: 100%;margin: 0 auto">
    <div class="panel-heading">
        <h3 style="text-align: center">Edit Patient</h3>
    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                    <?php
                    if ($this->session->userdata('success') != '') {
                    ?>
                        <p class="alert alert-success alert-auto-hide dism " style="text-align: center"> <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a> <strong>Success!</strong>Data has been saved successfully.</p>
                    <?php
                        $sdata['success'] = '';
                        $this->session->set_userdata($sdata);
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-12">
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
                <?php
                $patient = $this->db->where('patient_id', $patient_id)->get('patient')->row();
                ?>
                <form class="form-horizontal" id="patient_form" method="post"  enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Patient Name *</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" value="<?php echo $patient->patient_name ?>" id="patient_name" name="patient_name">
                                    <input type="hidden" class="form-control" value="<?php echo $patient_id ?>" id="patient_id" name="patient_id">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Ward/Cabin *</label>
                                <div class="col-sm-8">
                                    <select type="text" onchange="cabin_or_ward_selection(this.value)" class="form-control" id="cabin_or_ward" name="cabin_or_ward">
                                        <option selected="" disabled="" value="">Select Ward/Cabin</option>
                                        <option <?php echo $patient->cabin_or_ward == 'Ward' ? "selected" : "" ?>>Ward</option>
                                        <option <?php echo $patient->cabin_or_ward == 'Cabin' ? "selected" : "" ?>>Cabin</option>
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
                                    <input type="text" class="form-control" id="mobile_number" value="<?php echo $patient->mobile_number ?>" name="mobile_number">
                                </div>
                            </div>
                        </div>
                        <?php
                        $display_cabin = 'none';
                        if ($patient->cabin_or_ward == 'Cabin') {
                            $display_cabin = 'block';
                        }
                        ?>
                        <div class="col-md-6" id="cabin_container" style="display:<?php echo $display_cabin ?>">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Cabin </label>
                                <div class="col-sm-8">
                                    <div style="width: 50%;float:left">
                                        <select style="width: 100%;" type="text" class="form-control" onchange="cabin_number_load(this.value)" id="cabin_category_id" name="cabin_category_id">
                                            <option selected="" disabled="" value="">Select Category</option>
                                            <?php
                                            $cabin_categorys = $this->db->select('*')->get('cabin_category')->result();
                                            foreach ($cabin_categorys as $value) {
                                            ?>
                                                <option <?php echo $patient->cabin_category_id == $value->cabin_category_id ? "selected" : "" ?> value="<?php echo $value->cabin_category_id ?>"><?php echo $value->cabin_category_name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <select style="width: 50%;" type="text" class="form-control" id="cabin_id" name="cabin_id">
                                        <option  disabled="" value="">Select Cabin</option>
                                        <?php
                                        $cabin = $this->db->where('cabin_id', $patient->cabin_id)->get('cabin')->row();

                                        ?>
                                        <option selected value="<?php echo $cabin->cabin_id ?>"><?php echo $cabin->cabin_number ?></option>

                                    </select>

                                </div>
                            </div>
                        </div>
                        <?php
                        $display_ward = 'none';
                        if ($patient->cabin_or_ward == 'Ward') {
                            $display_ward = 'block';
                        }
                        ?>
                        <div class="col-md-6" id="ward_container" style="display:<?php echo $display_ward ?>">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Ward</label>
                                <div class="col-sm-8">
                                    <div style="width: 50%;float:left">
                                        <select style="width: 100%;" type="text" onchange="bed_number_load(this.value)" class="form-control" id="ward_id" name="ward_id">
                                            <option selected="" disabled="" value="">Select Ward</option>
                                            <?php
                                            $wards = $this->db->select('*')->get('ward')->result();
                                            foreach ($wards as $value) {
                                            ?>
                                                <option <?php echo $patient->ward_id == $value->ward_id ? "selected" : "" ?> value="<?php echo $value->ward_id ?>"><?php echo $value->name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div style="width: 50%;float:left">
                                        <?php
                                        $bed = $this->db->where('bed_id', $patient->bed_id)->get('bed')->row();
                                        ?>
                                        <select style="width: 100%;" type="text" class="form-control" id="bed_id" name="bed_id">
                                            <option  disabled="" value="">Select Bed</option>
                                            <option selected value="<?php echo $bed->bed_id ?>"><?php echo $bed->bed_number . '-' . $bed->bed_rent ?></option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Unique ID</label>
                                <div class="col-sm-8">

                                    <input type="text" readonly="" class="form-control" value="<?php echo $patient->patient_unique_id ?>" id="patient_unique_id" name="patient_unique_id">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Date *</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="datepicker" value="<?php echo date('d-m-Y', strtotime($patient->date)) ?>" name="date">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="pwd">Gender </label>
                                <div class="col-sm-8">
                                    <select type="text" class="form-control" id="gender1" name="gender">
                                        <option><?php echo $patient->gender ?></option>
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
                                    <input type="text" class="form-control" id="admission_time" value="<?php echo $patient->admission_time ?>" name="admission_time">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Age</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" value="<?php echo $patient->age ?>" id="age" name="age">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="pwd">Reference Doctor</label>
                                <div class="col-sm-8">
                                    <select type="text" class="form-control" id="doctor_id1" name="doctor_id">

                                        <?php
                                        $doctor = $this->db->where('doctor_id', $patient->doctor_id)->get('doctor')->row();
                                        ?>
                                        <option value="">Select Doctor</option>
                                        <?php
                                        $doctors = $this->db->select('*')->get('doctor')->result();
                                        foreach ($doctors as $doctor_value) {
                                        ?>
                                            <option <?php echo $doctor_value->doctor_id == $doctor->doctor_id ? 'selected' : '' ?> value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->doctor_name ?></option>
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
                                    <input type="text" class="form-control" id="paid_amount" value="<?php echo $patient->paid_amount ?>" name="paid_amount">
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Reference Media</label>
                                <div class="col-sm-8">
                                    <select type="text" class="form-control" id="reference_media_id1" name="reference_media_id">

                                        <?php
                                        $reference_media = $this->db->where('reference_media_id', $patient->reference_media_id)->get('reference_media')->row();
                                        $previous_reference_id = $reference_media->reference_media_id != '' ? $reference_media->reference_media_id : '';
                                        ?>
                                        <option value="">Selec Reference Media</option>
                                        <?php
                                        $reference_media = $this->db->select('*')->get('reference_media')->result();
                                        foreach ($reference_media as $reference_media_value) {
                                        ?>
                                            <option <?php echo $reference_media_value->reference_media_id == $previous_reference_id ? "selected" : '' ?> value="<?php echo $reference_media_value->reference_media_id ?>"><?php echo $reference_media_value->reference_media_name ?></option>
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
                                <div class="col-sm-offset-4 col-sm-4">
                                <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Update</button>
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