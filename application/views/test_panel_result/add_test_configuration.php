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
                document.getElementById("test_id").innerHTML = xhttp.responseText;
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
            absolute_value: "required",
        },
        messages: {
            test_group_id: "Enter test group",
            test_id: "Please test name",
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
                url: "<?php echo base_url('TestResultController/test_configuration_save'); ?>",
                data: formData,
                dataType: "json",
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
                        $('#test_configuration_form')[0].reset();
                        $('#test_configuration_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                        setTimeout(function() {
                            window.location.href = "<?php echo base_url('add-test-configuration') ?>";
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
                                <input type="text" class="form-control" required="" id="unit" name="unit">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Normal Range</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" required="" id="normal_range" name="normal_range">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Absolute Value</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="absolute_value" name="absolute_value">
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
