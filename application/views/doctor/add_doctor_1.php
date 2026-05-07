
<script>
    $().ready(function () {
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
    $(document).ready(function () {
        $('#gender').select2();
        $('#marital_status').select2();
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
        $('[name="admission_time"]').timeselector()
    });

    function select_all_this_reporting_dcotor_type()
    {
        if (document.getElementById('reporting_doctor_type_all').checked == true)
        {
            document.getElementById('lab_technician').checked = true;
            document.getElementById('medical_technologist').checked = true;
            document.getElementById('senior_warrent_officer').checked = true;

        } else
        {
            document.getElementById('lab_technician').checked = false;
            document.getElementById('medical_technologist').checked = false;
            document.getElementById('senior_warrent_officer').checked = false;

        }


    }
    function select_all_this_dcotor_type()
    {

        if (document.getElementById('all_doctor_type').checked == true)
        {
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
        } else
        {
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
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">

    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add Doctor</h3>
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
                    $doctor_id = $this->db->select('*')->get('doctor');
                    $doctor_id = str_pad($doctor_id->num_rows() + 1, 5, '0', STR_PAD_LEFT);
                    ?>

                    <form class="form-horizontal" id="doctor_form" method="post" action="<?php echo site_url('DoctorController/add_doctor_save') ?>" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Doctor Name *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="doctor_name" required=""  name="doctor_name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Doctor ID</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly="" value="<?php echo $doctor_id ?>" id="doctor_unique_id"   name="doctor_unique_id">                             
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Degree *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="degree"  required=""  name="degree">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Nationality </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="nationality"   name="nationality">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Father Name</label>
                                    <div class="col-sm-8">

                                        <input type="text" class="form-control"   id="fathers_name"   name="fathers_name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Mother Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"   id="mothers_name"   name="mothers_name">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Marital Status </label>
                                    <div class="col-sm-8">          
                                        <select type="text" class="form-control" id="marital_status"  name="marital_status">
                                            <option selected="" disabled="" value="">Select Marital Status</option>
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
                                        <input type="text" class="form-control"  id="spouse_name"    name="spouse_name">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Phone Home</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="phone_home"   name="phone_home">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Mobile</label>
                                    <div class="col-sm-8">          
                                        <input type="text" class="form-control"  id="mobile"   name="mobile">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Phone Office</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="phone_office"  name="phone_office">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Email Personal</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="email_personal"  name="email_personal">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Oficial Email</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="oficial_email"  name="oficial_email">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Department</label>
                                    <div class="col-sm-8">
                                        <select type="text" class="form-control" id="department_id"  name="department_id">
                                            <option selected="" disabled="" value="">Select Department</option>                                
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
                                        <input type="text" class="form-control"  id="job_title"  name="job_title">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Joining Date</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="datepicker1"  name="joining_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Gender</label>
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
                                    <label class="control-label col-sm-4" for="name">Remarks</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="remarks"  name="remarks">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Age</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="age"  name="age">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Picture</label>
                                    <div class="col-sm-8">
                                        <input type="file"   id="picture"  name="picture">
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="row">

                          
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Signature</label>
                                    <div class="col-sm-8">
                                        <input type="file"   id="signature"  name="signature">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>Doctor Type</legend>
                                    <div class="col-md-3">
                                        <input type="checkbox" onclick="select_all_this_dcotor_type()" name="all_doctor_type" id="all_doctor_type">&nbsp;All<br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" id="internal_doctor" name="internal_doctor" >&nbsp;Internal<br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" id="external_doctor" name="external_doctor" >&nbsp;External<br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" id="referral_doctor"  name="referral_doctor">&nbsp;Referral<br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" id="corporate_house_doctor" name="corporate_house_doctor" >&nbsp;Corporate House<br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" id="primary_doctor" name="primary_doctor">&nbsp;Primary Doctor<br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" id="surgeon" name="surgeon">&nbsp;Surgeon<br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" id="anaestesiologist" name="anaestesiologist">&nbsp;Anaestesiologist<br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" id="opd_consultation" name="opd_consultation">&nbsp;OPD Consultation<br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" id="ipd_consultation" name="ipd_consultation" >&nbsp;IPD Consultation<br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" id="emr_consultation" name="emr_consultation">&nbsp;EMR Consultation<br>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">

                                <fieldset>
                                    <legend>Reporting Doctor Type</legend>
                                    <div class="col-md-3">
                                        <input type="checkbox" onclick="select_all_this_reporting_dcotor_type()"  id="reporting_doctor_type_all"  name="reporting_doctor_type_all" value="Reporting Doctor Type All">&nbsp;All<br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" name="lab_technician" id="lab_technician">&nbsp;Lab Technician<br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" id="medical_technologist" name="medical_technologist">&nbsp;Medical Technologist<br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" id="senior_warrent_officer" name="senior_warrent_officer">&nbsp;Senior Warrent Officer<br>
                                    </div>
                                </fieldset>                            
                            </div>



                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">New Patient Fee</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="new_patient_fee"  name="new_patient_fee">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Day Care Fee</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="day_care_fee"  name="day_care_fee">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Staff Report Fee</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="remarks"  name="staff_report_fee">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">First Follow Up</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="first_follow_up_fee"  name="first_follow_up_fee">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Second Follow Up Fee</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="second_follow_up_fee"  name="second_follow_up_fee">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">EMR Fee</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="emr_fee"  name="emr_fee">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">        
                                    <div class="col-sm-offset-4 col-sm-8">
                                        <button type="submit" class="btn btn-primary">Save</button>
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



