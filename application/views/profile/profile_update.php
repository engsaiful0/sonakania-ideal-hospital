<?php
$this->load->view("common/header_common.php");
?>
<div class="container">
<script>
    $(document).ready(function() {
        // Add a custom method for password confirmation
        $.validator.addMethod("passwordMatch", function(value, element) {
            return value === $("#new_password").val();
        }, "Passwords do not match");

        // Add a custom method for file extension validation
        $.validator.addMethod("extension", function(value, element, param) {
            if (value) {
                var fileExtension = value.split('.').pop().toLowerCase();
                return param.indexOf(fileExtension) !== -1;
            }
            return true; // No file selected, so it is valid
        }, "Please upload a file with a valid extension.");

        // Validate the form
        $("#profile_update_form").validate({
            rules: {
                user_name: "required",
                new_password: {
                    required: true,
                    minlength: 6 // Minimum password length
                },
                confirm_password: {
                    required: true,
                    minlength: 6,
                    passwordMatch: true // Custom rule for matching passwords
                },
                mobile: "required",
                picture: {
                    extension: ["jpg", "jpeg", "png", "gif"] // Allowable file extensions
                }
            },
            messages: {
                user_name: "Please enter user name",
                new_password: {
                    required: "Please enter new password",
                    minlength: "Password must be at least 6 characters long"
                },
                confirm_password: {
                    required: "Please confirm your password",
                    minlength: "Password must be at least 6 characters long",
                    passwordMatch: "Passwords do not match"
                },
                mobile: "Please enter mobile number",
                picture: {
                    extension: "Please upload a valid image file (jpg, jpeg, png, gif)"
                }
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {
            e.preventDefault();

            var submitBtn = $(this);
            var formData = new FormData($('#profile_update_form')[0]); // Create FormData object with form data

            // Check if the form is valid
            if ($("#profile_update_form").valid()) {
                $('#profile_update_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('ProfileController/update_profile_save'); ?>",
                    data: formData,
                    dataType: "json",
                    processData: false, // Important: tell jQuery not to process the data
                    contentType: false, // Important: tell jQuery not to set contentType
                    success: function(response) {
                        if (response.success) {
                            $.toast({
                                heading: 'Success',
                                text: 'Data has been updated successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });
                            $('#profile_update_form')[0].reset();
                            $('#profile_update_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('profile-update') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#profile_update_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#profile_update_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
</script>


    <div class="container-fluid" style="background-color: white;width: 98%;">
        <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
            <div class="panel-heading">
                <h3 style="text-align: center">Profile Update</h3>
            </div>
            <div class="panel-body">

                <div class="row">
                    <?php
                    $user_id = $this->session->userdata('user_id');
                    $user = $this->db->where('user_id', $user_id)->get('user')->row();

                    ?>
                    <div class="col-md-12">
                    <form id="profile_update_form" class="form-horizontal" method="post" enctype="multipart/form-data">

                            <!-- User Name -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="user_name">User Name *</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" value="<?php echo  $user->user_name ?>" id="user_name" name="user_name" placeholder="Enter your username" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="new_password">New Password *</label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter your new password" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="confirm_password">Confirm Password *</label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="mobile">Mobile *</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="mobile" value="<?php echo  $user->mobile ?>" name="mobile" placeholder="Enter your mobile number" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="email">Email </label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control" id="email" value="<?php echo  $user->email ?>" name="email" placeholder="Enter your email">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Picture -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="picture">Profile Picture *</label>
                                        <div class="col-sm-4">
                                            <input type="file" class="form-control" id="picture" name="picture" >
                                        </div>
                                        <div class="col-sm-4">
                                            <img style="width: 100px;height: 100px;" src="<?php echo base_url() ?>assets/<?php echo (!empty($user->picture)) ? $user->picture : 'demo.png'; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="submit_button"></label>
                                        <div class="col-sm-8">
                                            <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>


</div> <!-- /cols -->
<?php
$this->load->view("common/footer_common.php");
?>