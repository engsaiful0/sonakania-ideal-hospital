<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="ipdServiceModalLabel">Edit Test Configuration</h4>
    </div>
    <div class="panel-body">
        <script>
            $(document).ready(function() {
                $('#test_id_edit').select2();
                $('#test_group_id_edit').select2();
            });

            function test_name_load(test_group_id) {
                $('#img').show();
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        document.getElementById("test_id_edit").innerHTML = xhttp.responseText;
                        $('#img').hide();
                    }
                }
                xhttp.open("POST", "<?php echo site_url('TestResultController/test_name_load'); ?>", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                
                xhttp.send("test_group_id=" + test_group_id);
            }

            $(document).ready(function() {
                // Validate the form
                $("#test_configuration_form").validate({
                    rules: {
                        test_group_id_edit: "required",
                        test_id_edit: "required",
                        normal_range: "required",
                        absolute_value: "required",
                    },
                    messages: {
                        test_group_id_edit: "Enter test group",
                        test_id_edit: "Please test name",
                        normal_range: "Please enter normal range",
                        absolute_value: "Please enter absolute value",
                    }
                });

                // On form submission
                $('#submit_button').click(function(e) {
                    e.preventDefault();
                    var submitBtn = $(this);
                    var formData = $('#test_configuration_form').serialize();

                    // Check if the form is valid
                    if ($("#test_configuration_form").valid()) {
                        $('#test_configuration_form :input').prop('disabled', true);
                        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url('TestResultController/edit_test_configuration_save'); ?>",
                            data: formData,
                            dataType: "json",
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
                                    $('#test_configuration_form')[0].reset();
                                    $('#test_configuration_form :input').prop('disabled', false);
                                    submitBtn.prop('disabled', false).html('Save');
                                    setTimeout(function() {
                                        window.location.href = "<?php echo base_url('view-test-configuration') ?>";
                                    }, 1002);
                                } else {
                                    alert('Error: ' + response.message);
                                    $('#test_configuration_form :input').prop('disabled', false);
                                    submitBtn.prop('disabled', false).html('Save');
                                }
                            },
                            error: function(xhr, status, error) {
                                alert("An error occurred: " + error);
                                $('#test_configuration_form :input').prop('disabled', false);
                                submitBtn.prop('disabled', false).html('Save');
                            }
                        });
                    }
                });
            });
        </script>
        <?php
        $test_configuration = $this->db
            ->where('test_configuration_id', $test_configuration_id)
            ->get('test_configuration')->row();
        $test_group = $this->db
            ->where('test_group_id', $test_configuration->test_group_id)
            ->get('test_group')->row();
        $test = $this->db
            ->where('test_id', $test_configuration->test_id)
            ->get('test')->row();
        ?>
        <form id="test_configuration_form" method="post">
            <input name="test_configuration_id" type="hidden" value="<?php echo $test_configuration_id ?>">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="pwd">Test Group</label>
                        <div class="col-sm-8">
                            <select  type="text" class="form-control" onchange="test_name_load(this.value)" id="test_group_id_edit" name="test_group_id">
                                <option value="" disabled="" selected="">Select Test Group</option>
                                <?php
                                $test_groups = $this->db->select('*')->get('test_group')->result();
                                foreach ($test_groups as $value) {
                                ?>
                                    <option <?php echo $test_configuration->test_group_id == $value->test_group_id ? "selected" : '' ?> value="<?php echo $value->test_group_id; ?>"><?php echo $value->test_group_name; ?></option>
                                <?php
                                }
                                ?>
                            </select>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="pwd">Test Name</label>
                        <div class="col-sm-8">
                            <select type="text" required="" class="form-control" id="test_id_edit" name="test_id">
                                <option value="<?php echo $test->test_id; ?>"><?php echo $test->test_name; ?></option>



                            </select>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="pwd">Unit</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" required="" value="<?php echo $test_configuration->unit ?>" id="unit" name="unit">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="pwd">Normal Range</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" required="" value="<?php echo $test_configuration->normal_range ?>" id="normal_range" name="normal_range">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="pwd">Absolute Value</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="<?php echo $test_configuration->absolute_value ?>" id="absolute_value" name="absolute_value">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">

                    <div class="col-md-8">
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-2">
                                <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Submit</button>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                            <div class="col-sm-4">
                                <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;" />

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>
</div>