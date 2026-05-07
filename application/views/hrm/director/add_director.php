<script>
    $().ready(function() {
        // validate the share holder form when it is submitted       
        $("#share_holder_form").validate({
            rules: {
                name: "required",
                father_name: "required",
                mother_name: "required",
                mobile: {
                    required: true,
                    minlength: 11
                },
                gender: "required",
                amount_per_share: "required",
                yearly_share_value_increment_rate: {
                    number: true,
                    min: 0,
                    max: 100
                },
                name_of_nominee: "required"
            },
            messages: {
                name: "Please Enter Director Name",
                father_name: "Please Enter Father Name",
                mother_name: "Please Enter Mother Name",
                mobile: {
                    required: "Please enter a valid mobile number",
                    minlength: "Your mobile number must consist of at least 11 characters"
                },
                gender: "Select Gender",
                amount_per_share: "Please Enter Amount Per Share",
                yearly_share_value_increment_rate: {
                    number: "Please enter a valid percentage",
                    min: "Rate cannot be negative",
                    max: "Rate cannot exceed 100%"
                },
                name_of_nominee: "Please Enter Nominee Name"
            }
        });
    });

    $(document).ready(function() {
        // Initialize Select2 for dropdowns
        $('#gender').select2();
        $('#nationality_id').select2();
        $('#religion_id').select2();
        $('#profession_id').select2();
        $('#relation_id').select2();
        $('#bank_name_id').select2();
        $('#no_of_share').select2();
        $('#category').select2();

        // Initialize datepicker
        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        }).on('changeDate', function() {
            update_share_calculations();
        });

        // Add event listeners for calculation triggers
        $('#amount_per_share').on('input change', function() {
            update_share_calculations();
        });

        $('#yearly_share_value_increment_rate').on('input change', function() {
            update_share_calculations();
        });

        $('#no_of_share').on('change', function() {
            total_amount_cal();
        });
    });

    jQuery(document).ready(function() {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });

    function total_amount_cal() {
        var no_of_share = $('#no_of_share').val();
        var current_share_value = $('#current_share_value').val() || $('#amount_per_share').val();
        if (no_of_share && current_share_value) {
            $('#total_amount').val(Number(no_of_share) * Number(current_share_value));
        }
    }

    function calculate_current_share_value() {
        var amount_per_share = parseFloat($('#amount_per_share').val()) || 0;
        var yearly_increment_rate = parseFloat($('#yearly_share_value_increment_rate').val()) || 0;
        var date_of_join = $('#datepicker').val();

        if (amount_per_share > 0 && date_of_join) {
            // Calculate years since joining
            var joinDate = new Date(date_of_join);
            var currentDate = new Date();
            var timeDiff = currentDate.getTime() - joinDate.getTime();
            var daysDiff = timeDiff / (1000 * 3600 * 24);
            var yearsDiff = daysDiff / 365.25; // Account for leap years

            // Handle future dates (set to 0 years)
            if (yearsDiff < 0) {
                yearsDiff = 0;
            }

            // Calculate current share value using compound interest formula
            // Current Value = Original Value × (1 + rate/100)^years
            var current_value = amount_per_share * Math.pow(1 + (yearly_increment_rate / 100), yearsDiff);

            // Round to 2 decimal places
            current_value = Math.round(current_value * 100) / 100;

            $('#current_share_value').val(current_value);

            // Recalculate total amount with new current share value
            total_amount_cal();

            // Show calculation details in console for debugging
            console.log('Share Value Calculation:', {
                original_value: amount_per_share,
                yearly_rate: yearly_increment_rate,
                years_passed: yearsDiff.toFixed(2),
                current_value: current_value
            });
        } else {
            // If no increment rate or joining date, use original amount
            $('#current_share_value').val(amount_per_share || 0);
            total_amount_cal();
        }
    }

    function update_share_calculations() {
        calculate_current_share_value();
        total_amount_cal();
    }

    $(document).ready(function() {
        // On form submission
        $('#submit_button').click(function(e) {
            e.preventDefault();

            var submitBtn = $(this);
            var formData = new FormData($('#share_holder_form')[0]);

            // Check if the form is valid
            if ($("#share_holder_form").valid()) {
                $('#share_holder_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('DirectorController/save_director_data'); ?>",
                    data: formData,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $.toast({
                                heading: 'Success',
                                text: 'Director data has been saved successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 2000,
                                icon: 'success'
                            });
                            $('#share_holder_form')[0].reset();
                            $('#share_holder_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save Director');
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('view-director') ?>";
                            }, 2500);

                        } else {
                            alert('Error: ' + response.message);
                            $('#share_holder_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save Director');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#share_holder_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save Director');
                    }
                });
            }
        });
    });
</script>
<style>
    .margin-bottom-15px {
        margin-bottom: 15px;
    }
</style>
<div class="container-fluid" style=" background-color: white;width: 98%;">

    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add Director</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" id="share_holder_form" method="post" enctype="multipart/form-data">
                        <div class="row margin-bottom-15px">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Name *</label>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="Enter Director Name" required class="form-control" id="name" name="name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Unique ID *</label>
                                    <div class="col-sm-8">
                                        <?php

                                        $total = $this->db->count_all('director');

                                        // Next serial will be total + 1
                                        $nextSerial = $total + 1;

                                        // Generate formatted unique ID like D0001, D0002, ...
                                        $unique_id = 'SIH' . str_pad($nextSerial, 4, '0', STR_PAD_LEFT);
                                        ?>
                                        <input type="text" readonly="" class="form-control" value="<?php echo $unique_id ?>" id="unique_id" name="unique_id">


                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Father Name *</label>
                                    <div class="col-sm-8">
                                        <input type="text" required placeholder="Enter Father Name" class="form-control" id="father_name" name="father_name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row margin-bottom-15px">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Mother Name *</label>
                                    <div class="col-sm-8">
                                        <input type="text" required placeholder="Enter Mother Name" class="form-control" id="mother_name" name="mother_name">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Mobile *</label>
                                    <div class="col-sm-8">
                                        <input type="text" oninput="validatePhoneNumberInput(this)" required placeholder="Enter Mobile" class="form-control" id="mobile" value="" name="mobile">

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Email</label>
                                    <div class="col-sm-8">
                                        <input type="email" placeholder="Enter Email" class="form-control" id="email" name="email">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row margin-bottom-15px">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Gender </label>
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

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">NID Number </label>
                                    <div class="col-sm-8">
                                        <input type="number" placeholder="Enter NID Number" class="form-control" id="nid_number" value="" name="nid_number">

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Present Address</label>
                                    <div class="col-sm-8">
                                        <textarea placeholder="Enter Present Address" type="text" class="form-control" id="present_address" name="present_address"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom:60px!important">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Parmanent Address</label>
                                    <div class="col-sm-8">
                                        <textarea placeholder="Enter Parmanent Address" type="text" class="form-control" id="permanent_address" name="permanent_address"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Nationality</label>
                                    <div class="col-sm-8">
                                        <select type="text" class="form-control" id="nationality_id" name="nationality_id">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Religion</label>
                                    <div class="col-sm-8">
                                        <select type="text" class="form-control" id="religion_id" name="religion_id">
                                            <option selected="" disabled="" value="">Select Religion</option>
                                            <?php
                                            $religions = $this->db->select('*')->get('religion')->result();
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Date of Join</label>
                                    <div class="col-sm-8">
                                        <input type="date" placeholder="Enter Date of Join" class="form-control" id="" name="date_of_join">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Profession</label>
                                    <div class="col-sm-8">
                                        <select type="text" class="form-control" id="profession_id" name="profession_id">
                                            <option selected="" disabled="" value="">Select Profession</option>
                                            <?php
                                            $professions = $this->db->select('*')->get('profession')->result();
                                            foreach ($professions as $profession) {
                                            ?>
                                                <option value="<?php echo $profession->profession_id ?>"><?php echo $profession->name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Amount (Per Share)</label>
                                    <div class="col-sm-8">
                                        <input type="number" placeholder="Enter Amount (Per Share)" class="form-control" id="amount_per_share" name="amount_per_share">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row margin-bottom-15px">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Picture</label>
                                    <div class="col-sm-8">
                                        <input type="file" class="form-control" id="picture" name="picture">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">NID File</label>
                                    <div class="col-sm-8">
                                        <input type="file" class="form-control" id="nid_file" name="nid_file">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">No of Share</label>
                                    <div class="col-sm-8">
                                        <select type="number" class="form-control" id="no_of_share" name="no_of_share">
                                            <option selected disabled value="0">Select No of Share</option>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                            <option>6</option>
                                            <option>7</option>
                                            <option>8</option>
                                            <option>9</option>
                                            <option>10</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="clear:both"></div>
                        <div class="row margin-bottom-15px">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Increment Rate(%)</label>
                                    <div class="col-sm-8">
                                        <input type="number" placeholder="Enter Yearly Share value Increment Rate(%)" class="form-control" id="yearly_share_value_increment_rate" name="yearly_share_value_increment_rate">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Current Share value</label>
                                    <div class="col-sm-8">
                                        <input type="number" readonly placeholder="Enter Current Share value" class="form-control" id="current_share_value" name="current_share_value">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Total Amount</label>
                                    <div class="col-sm-8">
                                        <input type="number" readonly placeholder="Total Amount" class="form-control" id="total_amount" name="total_amount">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="clear:both"></div>
                        <div class="row margin-bottom-15px">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Category</label>
                                    <div class="col-sm-8">
                                        <select type="text" class="form-control" id="category" name="category">
                                            <option selected disabled value="">Select Category</option>
                                            <option>Founder</option>
                                            <option>Management</option>
                                            <option>General</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                       
                        <fieldset style="background-color:whitesmoke">
                            <legend>Sign In Information</legend>
                            <div class="row margin-bottom-15px">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Sign In Mobile No *</label>
                                        <div class="col-sm-8">
                                            <input type="text" required class="form-control" placeholder="Enter Sign In Mobile No" id="sign_in_mobile_no" name="sign_in_mobile_no">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </fieldset>
                        <fieldset style="background-color:whitesmoke">
                            <legend>Nominee</legend>
                            <div class="row margin-bottom-15px">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Nominee *</label>
                                        <div class="col-sm-8">
                                            <input type="text" required class="form-control" placeholder="Enter Name of Nominee" id="name_of_nominee" name="name_of_nominee">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Relation</label>
                                        <div class="col-sm-8">

                                            <select type="text" class="form-control" id="relation_id" name="relation_id">
                                                <option selected disabled value="">Select Relation</option>
                                                <?php
                                                $relations = $this->db->select('*')->get('relation')->result();
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

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Mobile</label>
                                        <div class="col-sm-8">
                                            <input type="text" oninput="validatePhoneNumberInput(this)" class="form-control" placeholder="Enter Mobile" id="nominee_mobile" name="nominee_mobile">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row margin-bottom-15px">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Email</label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control" placeholder="Enter Email" id="nominee_email" name="nominee_email">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Present Address</label>
                                        <div class="col-sm-8">
                                            <textarea type="text" class="form-control" placeholder="Enter Present Address" id="nominee_present_address" name="nominee_present_address"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Parmanent Address</label>
                                        <div class="col-sm-8">
                                            <textarea type="text" placeholder="Enter Parmanent Address" class="form-control" id="nominee_parmanent_address" name="nominee_parmanent_address"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset style="background-color:whitesmoke">
                            <legend>Financial Information</legend>
                            <div class="row margin-bottom-15px">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Bank Name </label>
                                        <div class="col-sm-8">
                                            <select type="text" class="form-control" id="bank_name_id" name="bank_name_id">
                                                <option selected disabled value="">Select Relation</option>
                                                <?php
                                                $bank_names = $this->db->select('*')->get('bank_name')->result();
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Branch Name </label>
                                        <div class="col-sm-8">
                                            <input type="text" placeholder="Enter Branch Name" class="form-control" id="branch_name" name="branch_name">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Account Name </label>
                                        <div class="col-sm-8">
                                            <input type="text" placeholder="Enter Account Name" class="form-control" id="account_name" name="account_name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Account Number </label>
                                        <div class="col-sm-8">
                                            <input oninput="validateIntegerInput(this)" type="text" placeholder="Enter Account Number" class="form-control" id="account_number" name="account_number">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset style="background-color:whitesmoke">
                            <legend>Discount Fascility</legend>
                            <div class="row margin-bottom-15px">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">IPD Discount(%) </label>
                                        <div class="col-sm-7">
                                            <input type="number" placeholder="Enter IPD Discount" class="form-control" id="ipd_discount" name="ipd_discount">
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">OPD Discount(%) </label>
                                        <div class="col-sm-7">
                                            <input type="number" placeholder="Enter OPD Discount" class="form-control" id="opd_discount" name="opd_discount">
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Test Discount(%) </label>
                                        <div class="col-sm-7">
                                            <input type="number" placeholder="Enter Test Discount" class="form-control" id="test_discount" name="test_discount">
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row margin-bottom-15px">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Emergency Discount(%) </label>
                                        <div class="col-sm-7">
                                            <input type="number" placeholder="Enter Emergency Number" class="form-control" id="emergency_discount" name="emergency_discount">
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Phygiotherapy Discount(%) </label>
                                        <div class="col-sm-7">
                                            <input type="number" placeholder="Enter Phygiotherapy Discount" class="form-control" id="phygiotherapy_discount" name="phygiotherapy_discount">
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Pharmachy Discount(%) </label>
                                        <div class="col-sm-7">
                                            <input type="number" placeholder="Enter Pharmachy Number" class="form-control" id="pharmachy_discount" name="pharmachy_discount">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-sm-offset-4 col-sm-8">
                                        <button name="submit_button" id="submit_button" type="submit" class="btn btn-primary">Save Director</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">

                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>

</div>