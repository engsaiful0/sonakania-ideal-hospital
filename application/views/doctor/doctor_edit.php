<script>
    $().ready(function() {
        // validate the comment form when it is submitted       
        // validate signup form on keyup and submit
        $("#doctor_form").validate({
            rules: {

                doctor_name: "required",

                gender: "required",

            },
            messages: {
                patient_name: "Please Enter Doctor Name",

                gender: "Select Gender",
            }
        });
    });
    $(document).ready(function() {
        $('#gender').select2();
        $('#marital_status').select2();
        $('#department_id').select2();
        $('#nationality_id').select2();
        

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

    function select_all_this_reporting_dcotor_type() {
        if (document.getElementById('reporting_doctor_type_all').checked == true) {
            document.getElementById('lab_technician').checked = true;
            document.getElementById('medical_technologist').checked = true;
            document.getElementById('senior_warrent_officer').checked = true;

        } else {
            document.getElementById('lab_technician').checked = false;
            document.getElementById('medical_technologist').checked = false;
            document.getElementById('senior_warrent_officer').checked = false;

        }


    }

    function select_all_this_dcotor_type() {

        if (document.getElementById('all_doctor_type').checked == true) {
            document.getElementById('internal_doctor').checked = true;
            document.getElementById('referral_doctor').checked = true;
            document.getElementById('external_doctor').checked = true;
            document.getElementById('referral_doctor').checked = true;
            document.getElementById('corporate_house_doctor').checked = true;
            document.getElementById('primary_doctor').checked = true;
            document.getElementById('surgeon').checked = true;
            document.getElementById('anaestesiologist').checked = true;
            document.getElementById('opd_consultation').checked = true;
            document.getElementById('ipd_consultation').checked = true;
            document.getElementById('emr_consultation').checked = true;
        } else {
            document.getElementById('internal_doctor').checked = false;
            document.getElementById('referral_doctor').checked = false;
            document.getElementById('external_doctor').checked = false;
            document.getElementById('referral_doctor').checked = false;
            document.getElementById('corporate_house_doctor').checked = false;
            document.getElementById('primary_doctor').checked = false;
            document.getElementById('surgeon').checked = false;
            document.getElementById('anaestesiologist').checked = false;
            document.getElementById('opd_consultation').checked = false;
            document.getElementById('ipd_consultation').checked = false;
            document.getElementById('emr_consultation').checked = false;
        }
    }
    $(document).ready(function() {
        // On form submission
        $('#submit_button').click(function(e) {
            e.preventDefault();

            var submitBtn = $(this);
            var formData = new FormData($('#doctor_form')[0]); // Create FormData object with form data

            // Check if the form is valid
            if ($("#doctor_form").valid()) {
                $('#doctor_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('DoctorController/edit_doctor_save'); ?>",
                    data: formData,
                    dataType: "json",
                    processData: false, // Important: tell jQuery not to process the data
                    contentType: false, // Important: tell jQuery not to set contentType
                    success: function(response) {
                        if (response.success) {
                            $.toast({
                                heading: 'Success',
                                text: 'Data has been saved successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });
                            $('#doctor_form')[0].reset();
                            $('#doctor_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('view-doctor') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#doctor_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#doctor_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">

    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Edit Doctor</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $doctor = $this->db->where('doctor_id', $doctor_id)->get('doctor')->row();
                    ?>
                    <form class="form-horizontal" id="doctor_form" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Doctor Name *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $doctor->doctor_name ?>" id="doctor_name" required="" name="doctor_name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Doctor ID</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly="" value="<?php echo $doctor->doctor_unique_id ?>" id="doctor_unique_id" name="doctor_unique_id">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Degree *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="degree" value="<?php echo $doctor->degree ?>" required="" name="degree">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Nationality </label>
                                    <div class="col-sm-8">
                                        <select required type="text" class="form-control" id="nationality_id" name="nationality_id">
                                            <option selected="" disabled="" value="">Select Nationality</option>
                                            <?php
                                            $nationalities = $this->db->select('*')->get('nationality')->result();
                                            foreach ($nationalities as $nationality_value) {
                                            ?>
                                                <option <?php echo $doctor->nationality_id == $nationality_value->nationality_id ? "selected" : "" ?> value="<?php echo $nationality_value->nationality_id ?>"><?php echo $nationality_value->name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Father Name</label>
                                    <div class="col-sm-8">

                                        <input type="text" class="form-control" value="<?php echo $doctor->fathers_name ?>" id="fathers_name" name="fathers_name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Mother Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $doctor->mothers_name ?>" id="mothers_name" name="mothers_name">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Marital Status *</label>
                                    <div class="col-sm-8">
                                        <select type="text" required="" class="form-control" id="marital_status" name="marital_status">
                                            <option><?php echo $doctor->marital_status ?></option>
                                            <option>Single</option>
                                            <option>Maried</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Spouse Name </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="spouse_name" value="<?php echo $doctor->spouse_name ?>" name="spouse_name">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Phone Home</label>
                                    <div class="col-sm-8">
                                        <input type="text" oninput="validatePhoneNumberInput(this)" class="form-control" id="phone_home" value="<?php echo $doctor->phone_home ?>" name="phone_home">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Mobile</label>
                                    <div class="col-sm-8">
                                        <input type="text" oninput="validatePhoneNumberInput(this)" class="form-control" id="mobile" value="<?php echo $doctor->mobile ?>" name="mobile">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Phone Office</label>
                                    <div class="col-sm-8">
                                        <input type="text" oninput="validatePhoneNumberInput(this)" class="form-control" id="phone_office" value="<?php echo $doctor->phone_office ?>" name="phone_office">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Email Personal</label>
                                    <div class="col-sm-8">
                                        <input type="email" class="form-control" id="email_personal" value="<?php echo $doctor->email_personal ?>" name="email_personal">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Oficial Email</label>
                                    <div class="col-sm-8">
                                        <input type="email" class="form-control" id="oficial_email" value="<?php echo $doctor->oficial_email ?>" name="oficial_email">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Department *</label>
                                    <div class="col-sm-8">
                                        <select type="text" required class="form-control" id="department_id" name="department_id">

                                            <?php
                                            $department = $this->db->where('department_id', $doctor->department_id)->get('department')->row();
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
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Job Title</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="job_title" value="<?php echo $doctor->job_title ?>" name="job_title">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Joining Date</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="datepicker1" value="<?php
                                                                                                        if ($doctor->joining_date != '') {
                                                                                                            echo date('d-m-Y', strtotime($doctor->joining_date));
                                                                                                        }
                                                                                                        ?>" name="joining_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Gender *</label>
                                    <div class="col-sm-8">
                                        <select type="text" required class="form-control" id="gender" name="gender">
                                            <option><?php echo $doctor->gender ?></option>
                                            <option>Male</option>
                                            <option>Female</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Remarks</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="remarks" value="<?php echo $doctor->remarks ?>" name="remarks">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Age</label>
                                    <div class="col-sm-8">
                                        <input type="text" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $doctor->age ?>" id="age" name="age">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Picture</label>
                                    <div class="col-sm-8">
                                        <input type="hidden" id="picture_edit" name="picture_edit" value="<?php echo $doctor->picture ?>">
                                        <input type="file" id="picture" name="picture">
                                        <?php
                                        if ($doctor->picture != '') {
                                        ?>
                                            <img style="height: 50px;width: 100px;" src="<?php echo base_url() ?>assets/doctor_picture/<?php echo $doctor->picture ?>">
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Signature</label>
                                    <div class="col-sm-8">
                                        <input type="hidden" id="signature_edit" name="signature_edit" value="<?php echo $doctor->signature ?>">

                                        <input type="file" id="signature" name="signature">
                                        <?php
                                        if ($doctor->signature != '') {
                                        ?>
                                            <img style="height: 50px;width: 100px;" src="<?php echo base_url() ?>assets/doctor_signature/<?php echo $doctor->signature ?>">
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>Doctor Type</legend>
                                    <div class="col-md-3">
                                        <?php
                                        if ($doctor->all_doctor_type == 'Yes') {
                                        ?>
                                            <input type="checkbox" checked="" onclick="select_all_this_dcotor_type()" name="all_doctor_type" id="all_doctor_type">&nbsp;All<br>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="checkbox" onclick="select_all_this_dcotor_type()" name="all_doctor_type" id="all_doctor_type">&nbsp;All<br>
                                        <?php
                                        };
                                        ?>

                                    </div>
                                    <div class="col-md-3">
                                        <?php
                                        if ($doctor->internal_doctor == 'Yes') {
                                        ?>
                                            <input type="checkbox" checked="" id="internal_doctor" name="internal_doctor">&nbsp;Internal<br>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="checkbox" id="internal_doctor" name="internal_doctor">&nbsp;Internal<br>
                                        <?php
                                        };
                                        ?>

                                    </div>
                                    <div class="col-md-3">
                                        <?php
                                        if ($doctor->external_doctor == 'Yes') {
                                        ?>
                                            <input type="checkbox" checked="" id="external_doctor" name="external_doctor">&nbsp;External<br>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="checkbox" id="external_doctor" name="external_doctor">&nbsp;External<br>
                                        <?php
                                        };
                                        ?>

                                    </div>
                                    <div class="col-md-3">
                                        <?php
                                        if ($doctor->referral_doctor == 'Yes') {
                                        ?>
                                            <input type="checkbox" checked="" id="referral_doctor" name="referral_doctor">&nbsp;Referral<br>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="checkbox" id="referral_doctor" name="referral_doctor">&nbsp;Referral<br>
                                        <?php
                                        };
                                        ?>

                                    </div>
                                    <div class="col-md-3">
                                        <?php
                                        if ($doctor->corporate_house_doctor == 'Yes') {
                                        ?>
                                            <input type="checkbox" checked="" id="corporate_house_doctor" name="corporate_house_doctor">&nbsp;Corporate House<br>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="checkbox" id="corporate_house_doctor" name="corporate_house_doctor">&nbsp;Corporate House<br>
                                        <?php
                                        };
                                        ?>

                                    </div>
                                    <div class="col-md-3">
                                        <?php
                                        if ($doctor->primary_doctor == 'Yes') {
                                        ?>
                                            <input type="checkbox" id="primary_doctor" checked="" name="primary_doctor">&nbsp;Primary Doctor<br>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="checkbox" id="primary_doctor" name="primary_doctor">&nbsp;Primary Doctor<br>
                                        <?php
                                        };
                                        ?>

                                    </div>
                                    <div class="col-md-3">
                                        <?php
                                        if ($doctor->surgeon == 'Yes') {
                                        ?>
                                            <input type="checkbox" checked="" id="surgeon" name="surgeon">&nbsp;Surgeon<br>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="checkbox" id="surgeon" name="surgeon">&nbsp;Surgeon<br>
                                        <?php
                                        };
                                        ?>

                                    </div>
                                    <div class="col-md-3">
                                        <?php
                                        if ($doctor->anaestesiologist == 'Yes') {
                                        ?>
                                            <input type="checkbox" checked="" id="anaestesiologist" name="anaestesiologist">&nbsp;Anaestesiologist<br>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="checkbox" id="anaestesiologist" name="anaestesiologist">&nbsp;Anaestesiologist<br>
                                        <?php
                                        };
                                        ?>

                                    </div>
                                    <div class="col-md-3">
                                        <?php
                                        if ($doctor->opd_consultation == 'Yes') {
                                        ?>
                                            <input type="checkbox" id="opd_consultation" checked="" name="opd_consultation">&nbsp;OPD Consultation<br>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="checkbox" id="opd_consultation" name="opd_consultation">&nbsp;OPD Consultation<br>
                                        <?php
                                        };
                                        ?>

                                    </div>
                                    <div class="col-md-3">
                                        <?php
                                        if ($doctor->ipd_consultation == 'Yes') {
                                        ?>
                                            <input type="checkbox" id="ipd_consultation" checked="" name="ipd_consultation">&nbsp;IPD Consultation<br>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="checkbox" id="ipd_consultation" name="ipd_consultation">&nbsp;IPD Consultation<br>
                                        <?php
                                        };
                                        ?>

                                    </div>
                                    <div class="col-md-3">
                                        <?php
                                        if ($doctor->emr_consultation == 'Yes') {
                                        ?>
                                            <input type="checkbox" checked="" id="emr_consultation" name="emr_consultation">&nbsp;EMR Consultation<br>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="checkbox" id="emr_consultation" name="emr_consultation">&nbsp;EMR Consultation<br>
                                        <?php
                                        };
                                        ?>

                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">

                                <fieldset>
                                    <legend>Reporting Doctor Type</legend>
                                    <div class="col-md-3">
                                        <?php
                                        if ($doctor->reporting_doctor_type_all == 'Yes') {
                                        ?>
                                            <input type="checkbox" onclick="select_all_this_reporting_dcotor_type()" id="reporting_doctor_type_all" checked="" name="reporting_doctor_type_all" value="Reporting Doctor Type All">&nbsp;All<br>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="checkbox" onclick="select_all_this_reporting_dcotor_type()" id="reporting_doctor_type_all" name="reporting_doctor_type_all" value="Reporting Doctor Type All">&nbsp;All<br>
                                        <?php
                                        };
                                        ?>

                                    </div>
                                    <div class="col-md-3">
                                        <?php
                                        if ($doctor->lab_technician == 'Yes') {
                                        ?>
                                            <input type="checkbox" checked="" name="lab_technician" id="lab_technician">&nbsp;Lab Technician<br>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="checkbox" name="lab_technician" id="lab_technician">&nbsp;Lab Technician<br>
                                        <?php
                                        };
                                        ?>

                                    </div>
                                    <div class="col-md-3">
                                        <?php
                                        if ($doctor->medical_technologist == 'Yes') {
                                        ?>
                                            <input type="checkbox" checked="" id="medical_technologist" name="medical_technologist">&nbsp;Medical Technologist<br>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="checkbox" id="medical_technologist" name="medical_technologist">&nbsp;Medical Technologist<br>
                                        <?php
                                        };
                                        ?>

                                    </div>
                                    <div class="col-md-3">
                                        <?php
                                        if ($doctor->senior_warrent_officer == 'Yes') {
                                        ?>
                                            <input type="checkbox" checked="" id="senior_warrent_officer" name="senior_warrent_officer">&nbsp;Senior Warrent Officer<br>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="checkbox" id="senior_warrent_officer" name="senior_warrent_officer">&nbsp;Senior Warrent Officer<br>
                                        <?php
                                        };
                                        ?>

                                    </div>
                                </fieldset>
                            </div>



                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">New Patient Fee *</label>
                                    <div class="col-sm-8">
                                        <input type="number" required="" class="form-control" id="new_patient_fee" value="<?php echo $doctor->new_patient_fee ?>" name="new_patient_fee">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Day Care Fee</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="day_care_fee" value="<?php echo $doctor->day_care_fee ?>" name="day_care_fee">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Staff Report Fee</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="remarks" value="<?php echo $doctor->staff_report_fee ?>" name="staff_report_fee">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">First Follow Up</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="first_follow_up_fee" value="<?php echo $doctor->first_follow_up_fee ?>" name="first_follow_up_fee">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Second Follow Up Fee</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="second_follow_up_fee" value="<?php echo $doctor->second_follow_up_fee ?>" name="second_follow_up_fee">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">EMR Fee</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="emr_fee" value="<?php echo $doctor->emr_fee ?>" name="emr_fee">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">OPD Patient(%)</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" value="<?php echo $doctor->opd_patient_percentage ?>" id="opd_patient_percentage" name="opd_patient_percentage">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">IPD Patient(%)</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" value="<?php echo $doctor->ipd_commission_percentage ?>" id="ipd_commission_percentage" name="ipd_commission_percentage">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">LAB(%)</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" value="<?php echo $doctor->lab_percentage ?>" id="lab_percentage" name="lab_percentage">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Within 7 Days Fee</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" placeholder="Enter Within 7 Days Fee" value="<?php echo $doctor->within_seven_days_visiting_fee ?>" id="within_seven_days_visiting_fee" name="within_seven_days_visiting_fee">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Within 15 Days Fee</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" placeholder="Enter Within 15 Days Fee" value="<?php echo $doctor->within_fifteen_days_visiting_fee ?>" id="within_fifteen_days_visiting_fee" name="within_fifteen_days_visiting_fee">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Within 30 Days Fee</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" placeholder="Enter Within 30 Days Fee" value="<?php echo $doctor->within_thirty_days_visiting_fee ?>" id="within_thirty_days_visiting_fee" name="within_thirty_days_visiting_fee">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-offset-4 col-sm-8">
                                        <button name="submit_button" id="submit_button" type="submit" class="btn btn-primary">Update</button>
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