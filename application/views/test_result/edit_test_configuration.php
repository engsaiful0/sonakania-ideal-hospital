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
                        test_group_id: "required",
                        test_id: "required",
                        normal_range: "required",
                    },
                    messages: {
                        test_group_id: "Select test group",
                        test_id: "Select test name",
                        normal_range: "Please enter normal range",
                    }
                });

                function checkTestConfigurationDuplicateEdit(testId, configId, done) {
                    if (!testId) {
                        done(false, '');
                        return;
                    }
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo site_url('TestResultController/test_configuration_duplicate_check'); ?>",
                        data: { test_id: testId, test_configuration_id: configId },
                        dataType: 'json',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    }).done(function(res) {
                        done(!!(res && res.exists), (res && res.message) ? res.message : 'Configuration for this test already exists.');
                    }).fail(function() {
                        done(false, '');
                    });
                }

                $('#test_id_edit').on('change', function() {
                    var testId = $(this).val();
                    var configId = $('input[name="test_configuration_id"]').val();
                    $('#test_id_duplicate_msg_edit').hide().text('');
                    if (!testId) {
                        return;
                    }
                    checkTestConfigurationDuplicateEdit(testId, configId, function(exists, message) {
                        if (exists) {
                            $('#test_id_duplicate_msg_edit').text(message).show();
                        }
                    });
                });

                $('#test_configuration_form').on('submit', function(e) {
                    e.preventDefault();
                    if (window.__testConfigEditSubmitting) {
                        return false;
                    }
                    var submitBtn = $('#submit_button');
                    if (!$("#test_configuration_form").valid()) {
                        return false;
                    }
                    var testId = $('#test_id_edit').val();
                    var configId = $('input[name="test_configuration_id"]').val();
                    window.__testConfigEditSubmitting = true;
                    submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Checking...');
                    checkTestConfigurationDuplicateEdit(testId, configId, function(exists, message) {
                        if (exists) {
                            window.__testConfigEditSubmitting = false;
                            submitBtn.prop('disabled', false).html('Submit');
                            $('#test_id_duplicate_msg_edit').text(message).show();
                            if (typeof $.toast === 'function') {
                                $.toast({ heading: 'Duplicate', text: message, showHideTransition: 'slide', position: 'top-right', hideAfter: 4000, icon: 'warning' });
                            } else {
                                alert(message);
                            }
                            return;
                        }
                        var formData = $('#test_configuration_form').serialize();
                        $('#test_configuration_form :input').prop('disabled', true);
                        submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Saving...');
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url('TestResultController/edit_test_configuration_save'); ?>",
                            data: formData,
                            dataType: "json",
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
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
                                    setTimeout(function() {
                                        window.location.href = "<?php echo base_url('view-test-configuration') ?>";
                                    }, 1002);
                                } else {
                                    window.__testConfigEditSubmitting = false;
                                    $('#test_configuration_form :input').prop('disabled', false);
                                    submitBtn.prop('disabled', false).html('Submit');
                                    var errMsg = response.message || 'Update failed.';
                                    if (typeof $.toast === 'function') {
                                        $.toast({ heading: 'Error', text: errMsg, showHideTransition: 'slide', position: 'top-right', hideAfter: 4000, icon: 'error' });
                                    } else {
                                        alert(errMsg);
                                    }
                                }
                            },
                            error: function() {
                                window.__testConfigEditSubmitting = false;
                                alert("An error occurred while saving.");
                                $('#test_configuration_form :input').prop('disabled', false);
                                submitBtn.prop('disabled', false).html('Submit');
                            }
                        });
                    });
                    return false;
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
                            <p id="test_id_duplicate_msg_edit" class="text-danger" style="display:none;margin-top:6px;margin-bottom:0;"></p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="pwd">Unit</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  value="<?php echo $test_configuration->unit ?>" id="unit" name="unit">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="pwd">Normal Range</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="<?php echo $test_configuration->normal_range ?>" id="normal_range" name="normal_range">
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