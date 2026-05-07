<script>
     $(document).ready(function() {
        $('#employee_id').select2();
    });
    $().ready(function() {
        // validate the comment form when it is submitted       
        // validate signup form on keyup and submit
        $("#employee_sms_form").validate({
            rules: {
                employee_id: "required",
                message: "required",
            },
            messages: {
                employee_id: "Please select a employee",
                message: "Please enter message",
            }
        });
    });
    $(document).ready(function() {
        // On form submission
        $('#submit_button').click(function(e) {
            e.preventDefault();
            var submitBtn = $(this);
            var formData = $('#employee_sms_form').serialize();

            // Check if the form is valid
            if ($("#employee_sms_form").valid()) {
                $('#employee_sms_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('MarkettingController/send_employee_sms_save'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {

                            $('#employee_sms_form')[0].reset();
                            $('#employee_sms_form :input').prop('disabled', false);
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
                            $('#employee_sms_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#employee_sms_form :input').prop('disabled', false);
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
            <h3 style="text-align: center">Send SMS to Employee</h3>
        </div>
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">

                    <form class="form-horizontal" id="employee_sms_form" method="post" enctype="multipart/form-data">

                        <div class="row" style="">
                           
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Employee *</label>
                                    <div class="col-sm-8">
                                        <select style="width: 100%;" type="text" class="form-control" id="employee_id" name="employee_id">
                                            <option selected="" disabled="" value="">Select Employee</option>
                                            <option value="all">All</option>
                                            <?php
                                            $employees = $this->db->select('*')->get('employee')->result();
                                            foreach ($employees as $employee) {
                                            ?>
                                                <option value="<?php echo $employee->employee_id ?>"><?php echo $employee->employee_name ?></option>
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