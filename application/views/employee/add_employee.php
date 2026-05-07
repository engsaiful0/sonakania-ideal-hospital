<script>
    $().ready(function() {
        // validate the comment form when it is submitted       
        // validate signup form on keyup and submit
        $("#employee_form").validate({
            rules: {
                employee_name: "required",
                // father_name: "required",
                // mother_name: "required",

                // nid_number: "required",
                gender: "required",
                // mobile: {
                //     required: true,
                //     minlength: 11
                // },
                gender: "required",

            },
            messages: {
                employee_name: "Please Enter Director Name",
                // father_name: "Please Enter Father Name",
                // mother_name: "Please Enter Mother Name",
                // nid_number: "Please Enter Your NID Number",

                // mobile: {
                //     required: "Please enter a valid mobile number",
                //     minlength: "Your mobile number must consist of at least 13 characters"
                // },
                gender: "Select Gender",
            }
        });
    });
    $(document).ready(function() {
        $('#gender').select2();
        $('#nationality_id').select2();
        $('#religion_id').select2();
        $('#profession_id').select2();
        $('#relation_id').select2();
        $('#bank_name_id').select2();
        $('#marital_status_id').select2();
        $('#designation_id').select2();
        $('#department_id').select2();
        $('#is_disable').select2();
        $('#men_power_category_id').select2();


    });
    jQuery(document).ready(function() {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });


    function calculate_gross() {
        var basic_salary = $('#basic_salary').val();
        var transport_allowance = $('#transport_allowance').val();
        var house_rent = $('#house_rent').val();
        var medical_allowance = $('#medical_allowance').val();
        var communication_allowance = $('#communication_allowance').val();
        $('#gross_salary').val(Number(basic_salary) + Number(transport_allowance) + Number(house_rent) + Number(medical_allowance) + Number(communication_allowance));
    }
    $(document).ready(function() {


        // On form submission
        $('#submit_button').click(function(e) {
            e.preventDefault();

            var submitBtn = $(this);
            var formData = new FormData($('#employee_form')[0]); // Create FormData object with form data

            // Check if the form is valid
            if ($("#employee_form").valid()) {
                $('#employee_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('EmployeeController/save_employee_data'); ?>",
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
                            $('#employee_form')[0].reset();
                            $('#employee_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('view-employee') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#employee_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#employee_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
    function designation_load(men_power_category_id) {
        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("designation_id").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        xhttp.open("POST", "<?php echo site_url('EmployeeController/designation_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("men_power_category_id=" + men_power_category_id);
    }
</script>
<style>
    .margin-bottom-15px {
        margin-bottom: 15px;
    }
</style>
<div class="container-fluid" style=" background-color: white;width: 98%;">

    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add Employee</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" id="employee_form" method="post" enctype="multipart/form-data">
                        <div class="row margin-bottom-15px">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Employee Name *</label>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="Enter Employee Name" required class="form-control" id="employee_name" name="employee_name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">ID *</label>
                                    <div class="col-sm-8">
                                        <?php

                                        $uniqu_id = $this->db->select('*')->order_by('employee_uniqueid_table_id', 'DESC')->limit('1')->get('employee_uniqueid_table')->row();
                                        if (!$uniqu_id) {
                                            $uniqu_id = new stdClass();
                                            $uniqu_id->employee_unique_id_serial = 0; // Default if no record found
                                        }
                                        $employee_unique_id = 'E' . str_pad($uniqu_id->employee_unique_id_serial + 1, 4, '0', STR_PAD_LEFT);
                                        ?>
                                        <input type="text" readonly="" class="form-control" value="<?php echo $employee_unique_id ?>" id="employee_unique_id" name="employee_unique_id">
                                        <input type="hidden" readonly="" class="form-control" value="<?php echo $uniqu_id->employee_unique_id_serial + 1 ?>" id="employee_unique_id_serial" name="employee_unique_id_serial">

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row margin-bottom-15px">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Father Name *</label>
                                    <div class="col-sm-8">
                                        <input type="text"  placeholder="Enter Father Name" class="form-control" id="father_name" name="father_name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Mother Name *</label>
                                    <div class="col-sm-8">
                                        <input type="text"  placeholder="Enter Mother Name" class="form-control" id="mother_name" name="mother_name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row margin-bottom-15px">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Mobile *</label>
                                    <div class="col-sm-8">
                                        <input type="text" oninput="validatePhoneNumberInput(this)"  placeholder="Enter Mobile" class="form-control" id="mobile" value="" name="mobile">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Email</label>
                                    <div class="col-sm-8">
                                        <input type="email" placeholder="Enter Email" class="form-control" id="email" name="email">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row margin-bottom-15px">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Gender * </label>
                                    <div class="col-sm-8">
                                        <select type="text" required class="form-control" id="gender" name="gender">
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
                                    <label class="control-label col-sm-4" for="name">NID Number * </label>
                                    <div class="col-sm-8">
                                        <input type="number"  placeholder="Enter NID Number" class="form-control" id="nid_number" value="" name="nid_number">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom:60px!important">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Present Address</label>
                                    <div class="col-sm-8">
                                        <textarea placeholder="Enter Present Address" type="text" class="form-control" id="present_address" name="present_address"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Parmanent Address</label>
                                    <div class="col-sm-8">
                                        <textarea placeholder="Enter Parmanent Address" type="text" class="form-control" id="permanent_address" name="permanent_address"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row margin-bottom-15px">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Nationality *</label>
                                    <div class="col-sm-8">
                                        <select required type="text" class="form-control" id="nationality_id" name="nationality_id">
                                            <option selected="" disabled="" value="">Select Nationality</option>
                                            <?php
                                            $nationalities = $this->db->select('*')->get('nationality')->result();
                                            foreach ($nationalities as $nationality_value) {
                                            ?>
                                                <option value="<?php echo $nationality_value->nationality_id ?>"><?php echo $nationality_value->name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Religion *</label>
                                    <div class="col-sm-8">
                                        <select required type="text" class="form-control" id="religion_id" name="religion_id">
                                            <option selected="" disabled="" value="">Select Religion</option>
                                            <?php
                                            $religions = $this->db->select('*')->order_by('name')->get('religion')->result();
                                            foreach ($religions as $religion_value) {
                                            ?>
                                                <option value="<?php echo $religion_value->religion_id ?>"><?php echo $religion_value->name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row margin-bottom-15px">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Date of Birth</label>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="Enter Date of Birth" class="form-control" id="datepicker" name="date_of_birth">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Date of Join</label>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="Enter Date of Join" class="form-control" id="datepicker1" name="date_of_join">
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="row margin-bottom-15px">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Age *</label>
                                    <div class="col-sm-8">
                                        <input type="text" oninput="validateIntegerInput(this)" placeholder="Enter Age"  class="form-control" id="age" name="age">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Picture</label>
                                    <div class="col-sm-8">
                                        <input type="file" class="form-control" id="picture" name="picture">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row margin-bottom-15px">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Marital Status</label>
                                    <div class="col-sm-8">
                                        <select type="text" class="form-control" id="marital_status_id" name="marital_status_id">
                                            <option selected disabled value="">Select Marital Status</option>
                                            <?php
                                            $marital_status = $this->db->select('*')->get('marital_status')->result();
                                            foreach ($marital_status as $marital_status_value) {
                                            ?>
                                                <option value="<?php echo $marital_status_value->marital_status_id ?>"><?php echo $marital_status_value->name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">NID Picture</label>
                                    <div class="col-sm-8">
                                        <input type="file" class="form-control" id="nid" name="nid">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row" style="margin-top: 30px;margin-bottom: 50px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Department</label>
                                    <div class="col-sm-8">
                                        <select type="text" class="form-control" id="department_id" name="department_id">
                                            <option selected disabled value="">Select Department</option>
                                            <?php
                                            $department = $this->db->select('*')->order_by('department_name','ASC')->get('department')->result();
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
                            <div class="col-md-6"  >
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Designation *</label>
                                    <div class="col-sm-4">
                                        <select onchange="designation_load(this.value)" required type="text" class="form-control" id="men_power_category_id" name="men_power_category_id">
                                            <option selected disabled value="">Category</option>
                                            <?php
                                            $men_power_categories = $this->db->select('*')->order_by('name')->get('men_power_categories')->result();
                                            foreach ($men_power_categories as $men_power_category) {
                                            ?>
                                                <option value="<?php echo $men_power_category->men_power_category_id ?>"><?php echo $men_power_category->name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select type="text" class="form-control" id="designation_id" name="designation_id">
                                            <option selected disabled value="">Select Designation</option>
                                           
                                        </select>
                                    </div>
                                    <span id="men_power_category_id_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row margin-bottom-15px">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Is Disable?</label>
                                    <div class="col-sm-8">
                                        <select type="text" class="form-control" id="is_disable" name="is_disable">
                                            <option selected disabled value="">Select Is Disable</option>
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Disabilities Description</label>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="Enter Disabilities Description" class="form-control" id="disabilites_description" name="disabilites_description">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <fieldset>
                            <legend>Financial Information</legend>
                            <div class="row margin-bottom-15px">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Bank Name </label>
                                        <div class="col-sm-8">
                                            <select type="text" class="form-control" id="bank_name_id" name="bank_name_id">
                                                <option selected disabled value="">Select Bank Name</option>
                                                <?php
                                                $bank_names = $this->db->select('*')->order_by('name')->get('bank_name')->result();
                                                foreach ($bank_names as $bank_name) {
                                                ?>
                                                    <option value="<?php echo $bank_name->bank_name_id ?>"><?php echo $bank_name->name ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Branch Name </label>
                                        <div class="col-sm-8">
                                            <input type="text" placeholder="Enter Branch Name" class="form-control" id="branch_name" name="branch_name">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row margin-bottom-15px">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Account Name </label>
                                        <div class="col-sm-8">
                                            <input  type="text" placeholder="Enter Account Name" class="form-control" id="account_name" name="account_name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Account Number </label>
                                        <div class="col-sm-8">
                                            <input type="text" oninput="validateIntegerInput(this)" placeholder="Enter Account Number" class="form-control" id="account_number" name="account_number">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row margin-bottom-15px">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Basic Salary *</label>
                                        <div class="col-sm-8">
                                            <input type="number" oninput="validateInputFloatingPoint(this)"  oninput="calculate_gross()" placeholder="Enter Basic Salary" class="form-control" id="basic_salary" name="basic_salary">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">TIN </label>
                                        <div class="col-sm-8">
                                            <input type="text" oninput="validateIntegerInput(this)" placeholder="Enter TIN" class="form-control" id="tin" name="tin">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row margin-bottom-15px">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Transport Allowance </label>
                                        <div class="col-sm-8">
                                            <input type="text" oninput="validateInputFloatingPoint(this)" onkeyup="calculate_gross()" placeholder="Enter Transport Allowance" class="form-control" id="transport_allowance" name="transport_allowance">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">House Rent</label>
                                        <div class="col-sm-8">
                                            <input type="text" oninput="validateInputFloatingPoint(this)" onkeyup="calculate_gross()" placeholder="Enter House Rent" class="form-control" id="house_rent" name="house_rent">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row margin-bottom-15px">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Medical Allowance </label>
                                        <div class="col-sm-8">
                                            <input type="text" oninput="validateInputFloatingPoint(this)" onkeyup="calculate_gross()" placeholder="Enter Medical Allowance" class="form-control" id="medical_allowance" name="medical_allowance">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Communication Allowance</label>
                                        <div class="col-sm-8">
                                            <input type="text" oninput="validateInputFloatingPoint(this)" onkeyup="calculate_gross()" placeholder="Enter Communication Allowance" class="form-control" id="communication_allowance" name="communication_allowance">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row margin-bottom-15px">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Gross Salary * </label>
                                        <div class="col-sm-8">
                                            <input type="text" oninput="validateInputFloatingPoint(this)" readonly  placeholder="Gross Salary" class="form-control" id="gross_salary" name="gross_salary">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Emergency Information</legend>
                            <div class="row margin-bottom-15px">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Contact Person</label>
                                        <div class="col-sm-8">
                                            <input type="text" placeholder="Enter Contact Person" class="form-control" id="contact_person" name="contact_person">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Relatioin</label>
                                        <div class="col-sm-8">
                                            <select type="text" class="form-control" id="relation_id" name="relation_id">
                                                <option selected disabled value="">Select Relation</option>
                                                <?php
                                                $relations = $this->db->select('*')->order_by('name')->get('relation')->result();
                                                foreach ($relations as $relation) {
                                                ?>
                                                    <option value="<?php echo $relation->relation_id ?>"><?php echo $relation->name ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row margin-bottom-15px">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Contact No</label>
                                        <div class="col-sm-8">
                                            <input type="text" placeholder="Enter Contact No" class="form-control" id="contact_no" name="contact_no">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Contact Email</label>
                                        <div class="col-sm-8">
                                            <input type="email" placeholder="Enter Contact Email" class="form-control" id="contact_email" name="contact_email">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row margin-bottom-15px">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Status</label>
                                        <div class="col-sm-8">
                                            <select type="text" class="form-control" id="status" name="status">
                                                <option value="">Select Status</option>
                                                <option>Active</option>
                                                <option>Suspended</option>
                                                <option>Resigned</option>
                                                <option>Retired</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                </div>
                            </div>
                        </fieldset>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-offset-4 col-sm-8">
                                        <button name="submit_button" id="submit_button" type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">

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