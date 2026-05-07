<script type="text/javascript">
    function available_quantity_load(item_id) {
        $('#img').show();
        var id_no = item_id.split("_");
        var item_id = $('#item_id_' + id_no[2]).val();
        // alert(id_no[2]);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("available_quantity_" + id_no[2]).value = xhttp.responseText;
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('IssueController/available_quantity_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("item_id=" + item_id);

    }

    function show_bank(type) {
        if (type == 'Bank') {
            document.getElementById('bank_container').style.display = "block";
        } else {
            document.getElementById('bank_container').style.display = "none";
        }
    }
</script>
<script>
    $(document).ready(function() {
        $('#employee_id').select2();
        $('#type').select2();
        $('#bank_id').select2();
    });


    $(document).ready(function() {
        // Validate the form
        $("#attendance_entry_form").validate({
            rules: {
                employee_id: "required",
                in_time: "required",
                out_time: "required",
            },
            messages: {
                employee_id: "Please Select an employee",
                in_time: "Please enter in-time",
                out_time: "Please enter out-time",
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {
            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#attendance_entry_form').serialize();

            // Convert in_time and out_time to 12-hour format with AM/PM
            var in_time = $('#in_time').val();
            var out_time = $('#out_time').val();
            formData += '&in_time=' + convertTo12HourFormat(in_time);
            formData += '&out_time=' + convertTo12HourFormat(out_time);

            // Check if the form is valid
            if ($("#attendance_entry_form").valid()) {
                $('#attendance_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('AttendanceController/save_attendance_data'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            $('#attendance_entry_form')[0].reset();
                            $('#attendance_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            window.location.href = "<?php echo base_url('add-attendance') ?>";
                        } else {
                            alert('Error: ' + response.message);
                            $('#credit_voucher_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#credit_voucher_entry_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });

        // Function to convert 24-hour format to 12-hour format with AM/PM
        function convertTo12HourFormat(time24) {
            var [hours, minutes] = time24.split(':');
            var suffix = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12; // Convert hours to 12-hour format
            return hours + ':' + minutes + ' ' + suffix;
        }


    });
</script>

<div class="container-fluid" style=" background-color: white;width: 98%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add credit Voucher</h3>
        </div>
        <div class="panel-body">
            <?php
            if ($this->session->userdata('success') != '') {
            ?>
                <div class="alert alert-success">
                    <strong>Success!</strong>Data has been saved successfully.
                </div>
            <?php
                $sdata['success'] = '';
                $this->session->set_userdata($sdata);
            }
            ?>
            <div class="row">
                <div class="col-md-10">
                    <form id="attendance_entry_form" class="form-horizontal" method="post" enctype='multipart/form-data'>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Account Name *</label>
                                    <div class="col-sm-8">
                                        <select style="width:100% ;" type="text" class="form-control" id="employee_id" name="employee_id">
                                            <option selected="" value="" disabled="">Select Employee</option>
                                            <?php
                                            $employees = $this->db->select('*')->get('employee')->result();
                                            foreach ($employees as $employee) {
                                            ?>
                                                <option value="<?php echo $employee->employee_id; ?>"><?php echo $employee->employee_name . '-' . $employee->employee_unique_id ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Date *</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly required="" value="<?php echo date('d-m-Y'); ?>" class="form-control" id="date" name="date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">In Time*</label>
                                    <div class="col-sm-8">
                                        <input placeholder="Enter In Time" type="time" class="form-control" value="" required="" id="in_time" name="in_time">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Out Time*</label>
                                    <div class="col-sm-8">
                                        <input placeholder="Enter Out Time" type="time" class="form-control" value="" required="" id="out_time" name="out_time">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Remarks</label>
                                    <div class="col-sm-8">
                                        <textarea placeholder="Enter Remarks" type="text" class="form-control" value="" required="" id="remarks" name="remarks"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd"></label>
                                    <div class="col-sm-8">
                                        <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
                            </div>

                        </div>

                    </form>
                </div>

            </div>

        </div>

    </div>

</div><?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
