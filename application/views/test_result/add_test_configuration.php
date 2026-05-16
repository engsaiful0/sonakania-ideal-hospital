<script>
    $(document).ready(function() {
        // alert();
        $('#test_id').select2();
        $('#test_group_id').select2();
    });

    function test_name_load(test_group_id) {

        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var $sel = $('#test_id');
                if ($sel.hasClass('select2-hidden-accessible')) {
                    $sel.select2('destroy');
                }
                document.getElementById("test_id").innerHTML = xhttp.responseText;
                $sel.select2({ width: '100%' });
                $('#test_id_duplicate_msg').hide().text('');
                //                  var newdiv = document.createElement('tr');
                //                newdiv.innerHTML = xhttp.responseText;
                //                document.getElementById('due_history').appendChild(newdiv);
                $('#img').hide();
            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('TestResultController/test_name_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
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

    function checkTestConfigurationDuplicate(testId, done) {
        if (!testId) {
            done(false, '');
            return;
        }
        $.ajax({
            type: 'POST',
            url: "<?php echo site_url('TestResultController/test_configuration_duplicate_check'); ?>",
            data: { test_id: testId },
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(res) {
            done(!!(res && res.exists), (res && res.message) ? res.message : 'Configuration for this test already exists.');
        }).fail(function() {
            done(false, '');
        });
    }

    $('#test_id').on('change', function() {
        var testId = $(this).val();
        $('#test_id_duplicate_msg').hide().text('');
        if (!testId) {
            return;
        }
        checkTestConfigurationDuplicate(testId, function(exists, message) {
            if (exists) {
                $('#test_id_duplicate_msg').text(message).show();
            }
        });
    });

    $('#test_configuration_form').on('submit', function(e) {
        e.preventDefault();
        if (window.__testConfigAddSubmitting) {
            return false;
        }
        var submitBtn = $('#submit_button');
        if (!$("#test_configuration_form").valid()) {
            return false;
        }
        var testId = $('#test_id').val();
        window.__testConfigAddSubmitting = true;
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Checking...');
        checkTestConfigurationDuplicate(testId, function(exists, message) {
            if (exists) {
                window.__testConfigAddSubmitting = false;
                submitBtn.prop('disabled', false).html('Submit');
                $('#test_id_duplicate_msg').text(message).show();
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
                url: "<?php echo base_url('TestResultController/test_configuration_save'); ?>",
                data: formData,
                dataType: "json",
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function(response) {
                    if (response.success) {
                        $.toast({ heading: 'Success', text: 'Data has been saved successfully.', showHideTransition: 'slide', position: 'top-right', hideAfter: 1000, icon: 'success' });
                        $('#test_configuration_form')[0].reset();
                        setTimeout(function() { window.location.href = "<?php echo base_url('add-test-configuration') ?>"; }, 1002);
                    } else {
                        window.__testConfigAddSubmitting = false;
                        $('#test_configuration_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Submit');
                        var errMsg = response.message || 'Save failed.';
                        if (typeof $.toast === 'function') {
                            $.toast({ heading: 'Error', text: errMsg, showHideTransition: 'slide', position: 'top-right', hideAfter: 4000, icon: 'error' });
                        } else {
                            alert(errMsg);
                        }
                    }
                },
                error: function() {
                    window.__testConfigAddSubmitting = false;
                    alert("An error occurred while saving.");
                    $('#test_configuration_form :input').prop('disabled', false);
                    submitBtn.prop('disabled', false).html('Submit');
                }
            });
        });
        return false;
    });
});
    function parameter_set(test_id)
    {
        //$('#test_parameter').val(test_id);
    }
</script>
<div class="container-fluid" style=" background-color: white;width: 98%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add Test Configuration</h3>
        </div>

        <div class="panel-body">



            <form id="test_configuration_form" class="form-horizontal" method="post" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Test Group</label>
                            <div class="col-sm-8">
                                <select type="text" class="form-control" onchange="test_name_load(this.value)" id="test_group_id" name="test_group_id">
                                    <option value="" disabled="" selected="">Select Test Name</option>

                                    <?php
                                    $test_group = $this->db->select('*')->get('test_group')->result();
                                    foreach ($test_group as $value) {
                                    ?>
                                        <option value="<?php echo $value->test_group_id; ?>"><?php echo $value->test_group_name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Test Name</label>
                            <div class="col-sm-8">
                                <select type="text" required="" onchange="parameter_set(this.value)" class="form-control" id="test_id" name="test_id">
                                    <option value="" disabled="" selected="">Select Test Name</option>
                                </select>
                                <p id="test_id_duplicate_msg" class="text-danger" style="display:none;margin-top:6px;margin-bottom:0;"></p>

                            </div>
                        </div>
                    </div>
                </div>

                <!--                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="pwd">Test Parameter</label>
                                            <div class="col-sm-8">          
                                                <input type="text" class="form-control" required=""   id="test_parameter" name="test_parameter">
                                            </div>
                                        </div>
                                    </div>
                                </div>-->
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Unit</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="unit" name="unit">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Normal Range</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"  id="normal_range" name="normal_range">
                            </div>
                        </div>
                    </div>
                </div>
              

                <div class="row">

                    <div class="col-md-8">
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-4">

                                <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Submit</button>
                            </div>

                        </div>
                        <div class="col-sm-4">
                        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;" />
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>

</div><?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
