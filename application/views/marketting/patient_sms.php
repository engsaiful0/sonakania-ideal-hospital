<script>
    $(document).ready(function() {
        $('#director_id').select2();
    });
    $().ready(function() {
        // validate the comment form when it is submitted       
        // validate signup form on keyup and submit
        $("#patient_sms_form").validate({
            rules: {
                patient_type: "required",
                message: "required",
            },
            messages: {
                patient_type: "Please select a patient",
                message: "Please enter message",
            }
        });
    });
    $(document).ready(function() {
        // On form submission
        $('#submit_button').click(function(e) {
            e.preventDefault();
            var submitBtn = $(this);
            var formData = $('#patient_sms_form').serialize();

            // Check if the form is valid
            if ($("#patient_sms_form").valid()) {
                $('#patient_sms_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('MarkettingController/send_patient_sms_save'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {

                            $('#patient_sms_form')[0].reset();
                            $('#patient_sms_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');

                            $.toast({
                                heading: 'Success',
                                text: 'Data has been saved successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });
                            setTimeout(function() {
                                //window.location.href = "<?php echo base_url('send-director-sms') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#patient_sms_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#patient_sms_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
</script>
<div class="container-fluid" style=" background-color: white;width: 98%;">

    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Send SMS to Director</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" id="patient_sms_form" method="post" enctype="multipart/form-data">
                        <div class="row" style="">
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Patient Type *</label>
                                    <div class="col-sm-8">
                                        <select style="width: 100%;" type="text" class="form-control" id="patient_type" name="patient_type">
                                            <option selected="" disabled="" value="">Select Patient Type</option>
                                            <option value="all">All</option>
                                            <option value="opd">OPD Patient</option>
                                            <option value="ipd">IPD Patient</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Message *</label>
                                    <div class="col-sm-8">
                                        <textarea placeholder="Enter Message" type="text" class="form-control" id="message" name="message"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-sm-offset-4 col-sm-8">
                                            <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Send</button>
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