<script>
     $(document).ready(function() {
        $('#doctor_id').select2();
    });
    $().ready(function() {
        // validate the comment form when it is submitted       
        // validate signup form on keyup and submit
        $("#doctor_sms_form").validate({
            rules: {
                doctor_id: "required",
                message: "required",
            },
            messages: {
                doctor_id: "Please select a doctor",
                message: "Please enter message",
            }
        });
    });
    $(document).ready(function() {
        // On form submission
        $('#submit_button').click(function(e) {
            e.preventDefault();
            var submitBtn = $(this);
            var formData = $('#doctor_sms_form').serialize();

            // Check if the form is valid
            if ($("#doctor_sms_form").valid()) {
                $('#doctor_sms_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('SMSController/send_doctor_sms_save'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {

                            $('#doctor_sms_form')[0].reset();
                            $('#doctor_sms_form :input').prop('disabled', false);
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
                                window.location.href = "<?php echo base_url('send-director-sms') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#doctor_sms_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#doctor_sms_form :input').prop('disabled', false);
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
            <h3 style="text-align: center">Send SMS to Doctor</h3>
        </div>
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">

                    <form class="form-horizontal" id="doctor_sms_form" method="post" enctype="multipart/form-data">

                        <div class="row" style="">
                           
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Doctor *</label>
                                    <div class="col-sm-8">
                                        <select style="width: 100%;" type="text" class="form-control" id="doctor_id" name="doctor_id">
                                            <option selected="" disabled="" value="">Select Doctor</option>
                                            <option value="all">All</option>
                                            <?php
                                            $doctors = $this->db->select('*')->get('doctor')->result();
                                            foreach ($doctors as $doctor) {
                                            ?>
                                                <option value="<?php echo $doctor->doctor_id ?>"><?php echo $doctor->doctor_name ?></option>
                                            <?php
                                            }
                                            ?>
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