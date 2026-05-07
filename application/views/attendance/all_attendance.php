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

            var isValid = true;

            // Loop through each employee row to handle in_time, out_time, and calculate working hours
            $('.employee-row').each(function(index) {
                var in_time = $(this).find('.in_time').val();
                var out_time = $(this).find('.out_time').val();

                console.log("Row " + index + " In Time:", in_time);
                console.log("Row " + index + " Out Time:", out_time);

                if (!in_time || !out_time) {
                    alert("Please fill in all time fields for row " + (index + 1) + ".");
                    isValid = false;
                    return false; // Exit loop
                }

                // Convert times to 24-hour format
                var inTime24 = convertTo24HourFormat(in_time);
                var outTime24 = convertTo24HourFormat(out_time);
                var workingHours = calculateWorkingHours(inTime24, outTime24);

                console.log("Row " + index + " Working Hours:", workingHours);

                // Append calculated data to formData
                formData += '&in_time[]=' + encodeURIComponent(inTime24) +
                    '&out_time[]=' + encodeURIComponent(outTime24) +
                    '&working_hours[]=' + encodeURIComponent(workingHours);
            });

            if (!isValid) return;

            // Proceed with AJAX request if form is valid
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('AttendanceController/save_bulk_attendance_data'); ?>",
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
                        $('#attendance_entry_form')[0].reset();
                        window.location.href = "<?php echo base_url('all-attendance') ?>";
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert("An error occurred: " + error);
                }
            });
        });


    });
    // Utility functions
    function convertTo12HourFormat(time) {
        var [hour, minute] = time.split(':');
        var ampm = hour >= 12 ? 'PM' : 'AM';
        hour = hour % 12 || 12;
        return `${hour}:${minute} ${ampm}`;
    }

    function convertTo24HourFormat(time) {
        if (!time) return null; // Handle empty or invalid inputs
        var [hours, minutes] = time.split(':');
        hours = parseInt(hours);
        var isPM = time.includes('PM');

        if (isPM && hours < 12) {
            hours += 12;
        } else if (!isPM && hours === 12) {
            hours = 0;
        }

        return hours.toString().padStart(2, '0') + ':' + minutes;
    }


    function calculateWorkingHours(startTime, endTime) {
        var start = new Date(`1970-01-01T${startTime}:00`);
        var end = new Date(`1970-01-01T${endTime}:00`);
        var diff = (end - start) / 1000 / 60 / 60; // Difference in hours
        return diff > 0 ? diff : 24 + diff; // Handle overnight shifts
    }
</script>

<div class="container-fluid" style=" background-color: white;width: 98%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">All Employee Attendance</h3>
        </div>
        <div class="panel-body">

            <div class="row">
                <div class="col-md-10">
                    <form id="attendance_entry_form" class="form-horizontal" method="post" enctype='multipart/form-data'>

                        <table class="table table-bordered table-hover">
                            <tr>
                                <td>#</td>
                                <td>Employee</td>
                                <td>Department</td>
                                <td>In Time</td>
                                <td>Out Time</td>
                            </tr>
                            <?php
                            $this->db->select('employee.*, department.department_name');
                            $this->db->from('employee');
                            $this->db->join('department', 'employee.department_id = department.department_id', 'left'); // Use 'left' if you want all employees even if they don't have a department
                            $employees = $this->db->get()->result();

                            foreach ($employees as $employee) {
                                $attendance=getAttendance($employee->employee_id,date('Y-m-d'));
                            ?>
                                <tr class="employee-row">
                                    <td></td>
                                    <td>
                                        <input type="hidden" name="employee_id[]" value="<?php echo $employee->employee_id; ?>">
                                        <?php echo $employee->employee_name ?> (<?php echo $employee->employee_unique_id ?>)
                                    </td>
                                    <td><?php echo $employee->department_name ?></td>
                                    <td>
                                        <input value="<?php $attendance->in_time??"" ?>" placeholder="Enter In Time" type="time" class="form-control in_time" name="in_time[]">
                                    </td>
                                    <td>
                                        <input value="<?php $attendance->out_time??"" ?>" placeholder="Enter Out Time" type="time" class="form-control out_time" name="out_time[]">
                                    </td>

                                </tr>

                            <?php
                            }
                            ?>

                        </table>

                        <div class="row" style="margin-top:10px">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd"></label>
                                    <div class="col-sm-8">
                                        <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Date *</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly required="" value="<?php echo date('d-m-Y'); ?>" class="form-control" id="date" name="date">
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
