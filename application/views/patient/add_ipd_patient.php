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
                    url: "<?php echo base_url('IpdPatientController/add_ipd_patient_save'); ?>",
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
        xhttp.open("POST", "<?php echo site_url('IpdPatientController/bed_number_load'); ?>", true);
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
<div class="container-fluid" style=" background-color: white;width: 98%;">

    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add IPD Patient</h3>
        </div>
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">

                    <form class="form-horizontal" id="patient_form" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Patient Name *</label>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="Enter Patient Name" class="form-control" id="patient_name" name="patient_name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Ward/Cabin *</label>
                                    <div class="col-sm-8">
                                        <select type="text" onchange="cabin_or_ward_selection(this.value)" class="form-control" id="cabin_or_ward" name="cabin_or_ward">
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
                                        <input placeholder="Enter Mobile Number" type="text" class="form-control" id="mobile_number" name="mobile_number">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="cabin_container" style="display:none">
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
                                                    <option value="<?php echo $value->cabin_category_id ?>"><?php echo $value->cabin_category_name ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div style="width: 50%;float:left">

                                            <select style="width: 100%;" type="text" class="form-control" id="cabin_id" name="cabin_id">
                                                <option selected="" disabled="" value="">Select Cabin</option>

                                            </select>
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="ward_container" style="display:none">
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
                                                    <option value="<?php echo $value->ward_id ?>"><?php echo $value->name ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div style="width: 50%;float:left">

                                            <select style="width: 100%;" type="text" class="form-control" id="bed_id" name="bed_id">
                                                <option selected="" disabled="" value="">Select Bed</option>

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

                                        <?php
                                        $uniqu_id = $this->db->select('*')->order_by('patientunique_id', 'DESC')->limit('1')->get('patient_unique_table')->row();
                                        $patient_unique_id = 'IPD-' . date('d-m-Y') . '-' . $uniqu_id->id_serial + 1;
                                        ?>
                                        <input type="hidden" readonly="" class="form-control" value="<?php echo $uniqu_id->id_serial + 1 ?>" id="id_serial" name="id_serial">
                                        <input type="text" readonly="" class="form-control" value="<?php echo $patient_unique_id ?>" id="patient_unique_id" name="patient_unique_id">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Date *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="datepicker" value="<?php echo date('d-m-Y') ?>" name="date">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Gender </label>
                                    <div class="col-sm-8">
                                        <select type="text" class="form-control" id="gender" name="gender">
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
                                        <input type="text" class="form-control" id="admission_time" value="<?php echo date("h:i:s") ?>" name="admission_time">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Age</label>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="Enter Age" class="form-control" id="age" name="age">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Reference Doctor</label>
                                    <div class="col-sm-8">
                                        <select type="text" class="form-control" id="doctor_id" name="doctor_id">
                                            <option selected="" disabled="" value="">Select Reference Doctor</option>
                                            <?php
                                            $doctor = $this->db->select('*')->get('doctor')->result();
                                            foreach ($doctor as $doctor_value) {
                                            ?>
                                                <option value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->doctor_name ?></option>
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
                                        <input placeholder="Enter Paid Amount" type="text" class="form-control" id="paid_amount" name="paid_amount">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Reference Media</label>
                                    <div class="col-sm-8">
                                        <select type="text" class="form-control" id="reference_media_id" name="reference_media_id">
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