<html>

<head>
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <link rel="stylesheet" href="<?php echo base_url() ?>css/background.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>css/bootstrap.min.css">
    <link href="<?php echo base_url() ?>css/bayanno.css" media="screen" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url() ?>js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>js/jquery.toast.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.toast.min.css">
    <script src="<?php echo base_url() ?>js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>js/jquery.validate.js"></script>
    <title>Hospital ERP</title>
    <script>
         $().ready(function() {
        // validate the comment form when it is submitted       
        // validate signup form on keyup and submit
        $("#sign_in_form").validate({
            rules: {
                password: "required",
                user_name: "required",
            },
            messages: {
                password: "Please Enter Password",
                user_name: "Please Enter User Name",
              
            }
        });
    });
        $(document).ready(function() {
            
            // On form submission
            $('#submit_button').click(function(e) {
                e.preventDefault();
                var submitBtn = $(this);
                var formData = $('#sign_in_form').serialize();
                // Check if the form is valid
                if ($("#sign_in_form").valid()) {
                    $('#sign_in_form :input').prop('disabled', true);
                    submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('LoginController/FunctionLogin'); ?>",
                        data: formData,
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {

                                $('#sign_in_form')[0].reset();
                                $('#sign_in_form :input').prop('disabled', false);
                                submitBtn.prop('disabled', false).html('Save');
                                $.toast({
                                    heading: 'Success',
                                    text: 'Signed in successfully.',
                                    showHideTransition: 'slide',
                                    position: 'top-right',
                                    hideAfter: 1000,
                                    icon: 'success'
                                });
                                setTimeout(function() {
                                    window.location.href = "<?php echo base_url('HomeController/home') ?>";
                                }, 1002);
                            } else {
                                $.toast({
                                    heading: 'Error',
                                    text: 'Invalid username or password.',
                                    showHideTransition: 'slide',
                                    position: 'top-right',
                                    hideAfter: 1000,
                                    icon: 'error'
                                });
                                $('#sign_in_form :input').prop('disabled', false);
                                submitBtn.prop('disabled', false).html('Save');
                            }
                        },
                        error: function(xhr, status, error) {
                            alert("An error occurred: " + error);
                            $('#sign_in_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    });
                }
            });
        });
    </script>

</head>

<body>

    <div class="container">
        <div class="span4 offset4">
            <div class="padded">
                <?php
                //    $compnay=$this->db->where('company_id','1')->get('company')->row();
                ?>
                <!--<img  src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">-->
                <div class="login box" style="margin-top: 150px;">

                    <div class="box-header" style="text-align: center">
                        <span class="title">login</span>
                    </div>
                    <div class="box-content padded">
                        <i class="m-icon-swapright m-icon-white"></i>
                        <form class="form-horizontal" id="sign_in_form" method="post" enctype="multipart/form-data">

                            <div style="display:none">
                                <input type="hidden" name="authenticity_token" value="6741c40719025848ffc4b8e799390765" />
                            </div>
                            <div style="color: black" class="input-prepend">
                                <span class="add-on" href="#">
                                    <i class="glyphicon glyphicon-user "></i>
                                </span>
                                <input name="user_name" id="user_name" value="" type="text" placeholder="User Name">
                            </div>
                            <div class="input-prepend">
                                <span class="add-on" href="#">
                                    <i class="glyphicon glyphicon-lock "></i>
                                </span>
                                <input name="password" id="password" value="alim01712138002" type="password" placeholder="Password">
                            </div>
                            <div>

                                <button type="submit" name="submit_button" id="submit_button" class="btn btn-blue btn-block btn-primary">Sing In</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
    
</body>

</html>