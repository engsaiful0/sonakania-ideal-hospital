<script>
    $(document).ready(function() {
        $('#test_id').select2();
        $('#test_group_id').select2();
        $('#invoice_no').focus();
    });

    function test_configuration_load() {
        var test_group_id = $('#test_group_id').val();
        var patient_test_entry_id = $('#patient_test_entry_id').val();

        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("test_configuration").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        xhttp.open("POST", "<?php echo site_url('TestResultController/test_configuration_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("test_group_id=" + test_group_id + "&patient_test_entry_id=" + patient_test_entry_id);
    }

    function test_name_load(test_group_id) {

        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("test_configuration").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        xhttp.open("POST", "<?php echo site_url('TestResultController/test_name_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("test_group_id=" + test_group_id);
    }





    function patient_data_set(invoice_no) {
        $('#img').show();
        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var patient = xhttp.responseText;
                var patient_array = patient.split('*');
                //alert(patient_array);
                document.getElementById("patient_test_entry_id").value = patient_array[0];
                document.getElementById("patient_name").value = patient_array[1];
                document.getElementById("mobile_number").value = patient_array[2];
                document.getElementById("age").value = patient_array[3];
                document.getElementById("gender").value = patient_array[4];
                document.getElementById("invoice_date").value = patient_array[5];
                document.getElementById("invoice_time").value = patient_array[6];
                test_configuration_load();
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('TestResultController/patient_data_load_by_test_invoice_no'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("invoice_no=" + invoice_no);
    }
    $(document).ready(function() {

        $("#invoice_no").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('TestController/invoice_no_load'); ?>",
                    data: {
                        parameter: request.term
                    },
                    dataType: "json",
                    type: "POST",
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                $('#invoice_no').val(ui.item.label);
                patient_data_set(ui.item.value);
                return false;
            }
        });

        // Validate the form
        $("#test_result_entry_form").validate({
            rules: {
                invoice_no: "required",
                test_group_id: "required",
            },
            messages: {
                invoice_no: "Enter invoice no",
                test_group_id: "Select test group name",

            }
        });

        // On form submission
        $('#submit_button').click(function(e) {
            e.preventDefault();
            var manual_or_dynamic_report = $('#manual_or_dynamic_report').val();
            var formData = new FormData($('#test_result_entry_form')[0]); // Create FormData object with form data

            // Check if the form is valid
            if ($("#test_result_entry_form").valid()) {

                // Manual report file check
                if (manual_or_dynamic_report == 'Manual') {
                    var manualReportFile = $('#manual_report').val(); // Get the file value
                    if (manualReportFile == '') {
                        // Show error if no file is selected
                        $.toast({
                            heading: 'Error',
                            text: 'Please select a file to upload for Manual Report.',
                            showHideTransition: 'slide',
                            position: 'top-right',
                            hideAfter: 3000,
                            icon: 'error'
                        });
                        return; // Stop the form submission
                    }
                }

                // Dynamic report input field check (test_configuration_value array)
                if (manual_or_dynamic_report == 'Dynamic') {
                    var dynamicInputs = $('input[name="test_configuration_value[]"]'); // Get all input fields with name="test_configuration_value[]"
                    var isValid = false;

                    dynamicInputs.each(function() {
                        if ($(this).val() != '') { // Check if any of the inputs are filled
                            isValid = true;
                            return false; // Stop the loop if we find a filled input
                        }
                    });

                    if (!isValid) {
                        // Show error if none of the dynamic inputs are filled
                        $.toast({
                            heading: 'Error',
                            text: 'Please fill up the Dynamic Report configuration values.',
                            showHideTransition: 'slide',
                            position: 'top-right',
                            hideAfter: 3000,
                            icon: 'error'
                        });
                        return; // Stop the form submission
                    }
                }


                var submitBtn = $(this);
                $('#test_result_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('TestResultController/add_test_result_data_save'); ?>",
                    data: formData,
                    dataType: "json",
                    processData: false, // Important: tell jQuery not to process the data
                    contentType: false, // Important: tell jQuery not to set contentType
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
                            $('#test_result_entry_form')[0].reset();
                            $('#test_result_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('test-result-report-print') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#test_result_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#test_result_entry_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });

    });

    function manual_or_dynamic_report_data(value) {
        if (value == 'Dynamic') {
            document.getElementById('test_configuration').style.display = 'block';
            document.getElementById('manual_report_container').style.display = 'none';
        } else if (value == 'Manual') {
            document.getElementById('test_configuration').style.display = 'none';
            document.getElementById('manual_report_container').style.display = 'block';
        }
    }
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add Sample Collect</h3>
        </div>
        <div class="panel-body">
            <?php
            error_reporting(0);
            $patient_test_entry = $this->db->where('patient_test_entry_id', $patient_test_entry_id)
                ->get('patient_test_entry')->row();

            $this->db->select('test.test_name,test.test_id');
            $this->db->from('test');
            $this->db->join('patient_test_entry_details', 'patient_test_entry_details.test_id = test.test_id');
            $this->db->where('patient_test_entry_details.patient_test_entry_id', $patient_test_entry_id);
            $this->db->where('test.name_only', 'no');
            // $this->db->where('test.test_group_id', $test_group_id);

            $query = $this->db->get();
            $test_configuration = $query->result();
            $check = 1;

            ?>
            <form class="form-horizontal" id="test_result_entry_form" method="post" enctype='multipart/form-data'>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Invoice No</label>
                            <div class="col-sm-4">
                                <input type="hidden" id="patient_test_entry_id" name="patient_test_entry_id">
                                <input type="text" class="form-control" value="<?php echo $patient_test_entry->invoice_no ?>" placeholder="Enter or Scan invoice no ..." id="invoice_no" name="invoice_no">
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Age</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" value="<?php echo $patient_test_entry->age ?>" class="form-control" placeholder="Age" id="age" name="age">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Patient's Name</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" class="form-control" value="" placeholder="Patient Name" value="<?php echo $patient_test_entry->patient_name ?>" id="patient_name" name="patient_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Gender</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" placeholder="Gender" class="form-control" id="gender" value="<?php echo $patient_test_entry->gender ?>" name="gender">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Invoice Date & Time</label>
                            <div class="col-sm-4">
                                <input readonly type="text" placeholder="Invoice Date" class="form-control" value="<?php echo $patient_test_entry->invoice_date ?>" id="invoice_date" name="invoice_date">
                            </div>
                            <div class="col-sm-4">
                                <input readonly type="text" placeholder="Invoice Time" class="form-control" value="<?php echo $patient_test_entry->invoice_time ?>" id="invoice_time" name="invoice_time">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Mobile</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" placeholder="Mobile" class="form-control" value="<?php echo $patient_test_entry->mobile_number ?>" id="mobile_number" name="mobile_number">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top:20px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Entry Date</label>
                            <div class="col-sm-4">
                                <input type="text" placeholder="Date" value="<?php echo date('d-m-y') ?>" class="form-control" id="datepicker" name="date">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" placeholder="Time" value="<?php echo date('H:i:s') ?>" class="form-control" id="datepicker" name="time">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Address</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" value="<?php echo $patient_test_entry->address ?>" placeholder="Address" class="form-control" id="address" name="address">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row" style="margin-top:20px">
                    <div class="col-md-6" style="display:none">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Test Group Name</label>
                            <div class="col-sm-8">
                                <select type="text" required="" class="form-control" onchange="test_configuration_load()" id="test_group_id" name="test_group_id">
                                    <option value="" disabled="" selected="">Select Test Group Name</option>

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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Test No</label>
                            <div class="col-sm-8">
                                <?php
                                $serial = $this->db->select('*')->get('test_result');
                                $serial = 'TR' . str_pad($serial->num_rows() + 1, 5, '0', STR_PAD_LEFT);
                                ?>
                                <input type="text" readonly="" class="form-control" id="test_result_no" value="<?php echo $serial ?>" name="test_result_no">
                            </div>
                        </div>
                    </div>

                </div>
                <hr>
                <div id="test_configuration">
                    <?php
                    foreach ($test_configuration as $value) {
                        //            $test = $this->db->where('test_id', $value->test_id)->get('test')->row();
                        if ($check % 2 != 0) :
                    ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="name"><?php echo $value->test_name ?></label>
                                        <div class="col-sm-3">
                                            <input type="hidden" class="form-control" value="<?php echo $value->test_id ?>" id="test_id" name="test_id[]">
                                            <!--<input type="hidden"  class="form-control" value="<?php echo $value->test_configuration_id ?>"  id="test_configuration_id"  name="test_configuration_id[]">-->
                                            <input type="text" class="form-control" id="test_configuration_value" name="test_configuration_value[]">
                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-control" name="bold[]">
                                                <option>No</option>
                                                <option>Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        endif;
                        if ($check % 2 == 0) :
                            ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6" for="name"><?php echo $value->test_name ?></label>
                                        <div class="col-sm-3">
                                            <input type="hidden" class="form-control" value="<?php echo $value->test_id ?>" id="test_id" name="test_id[]">
                                            <!--<input type="hidden"  class="form-control" value="<?php echo $value->test_configuration_id ?>"  id="test_configuration_id"  name="test_configuration_id[]">-->
                                            <input type="text" class="form-control" id="test_configuration_value" name="test_configuration_value[]">
                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-control" name="bold[]">
                                                <option>No</option>
                                                <option>Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        endif;
                        $check++;
                    } ?>
                </div>
                <div class="row">

                    <div class="col-md-5">
                        <div class="form-group">
                            <div class="col-sm-offset-5 col-sm-3">
                                <button id="submit_button" name="submit_button" type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </div>
                        <div class="col-sm-4">
                            <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none;z-index:1" />

                        </div>
                    </div>
                </div>


            </form>

        </div>
    </div>
</div>