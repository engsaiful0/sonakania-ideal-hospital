<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        padding: 5px;
        vertical-align: middle;
        /* Ensures the button stays centered */
        text-align: center;
        /* Centers horizontally */
        overflow: hidden;
        /* Prevents overflow */
    }

    .btn-sm {
        padding: 2px 5px;
        height: auto;
        line-height: normal;
        margin: 0;
    }

    /* Optional: Add some spacing around the table */
    .table-container {
        margin: 20px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $("#year_id").select2();
        $('#month_id').select2();
    });


    $(document).ready(function() {
        // Validate the form
        $("#salary_entry_form").validate({
            rules: {
                date: "required",
                month_id: "required",
                year_id: "required",

            },
            messages: {
                date: "Enter date",
                month_id: "Please select a month",
                year_id: "Please select a year",

            }
        });

        // On form submission
        $('#submit_button').click(function(e) {

            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#salary_entry_form').serialize();

            // Check if the form is valid
            if ($("#salary_entry_form").valid()) {
                $('#salary_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('SalaryController/salary_edit_save'); ?>",
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
                            $('#salary_entry_form')[0].reset();
                            $('#salary_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');

                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('print-salary') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#salary_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#salary_entry_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
</script>


<script type="text/javascript">
    function working_day_load(month_id) {
        var xhttp = new XMLHttpRequest();
        $('#img').show();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                let working_days = parseInt(xhttp.responseText.trim());

                // Set working days for each employee row
                $('.working_days').each(function() {
                    $(this).val(working_days);

                    // Find the row
                    let $row = $(this).closest('tr');

                    // Get gross salary from the row's data attribute or input
                    let gross = parseFloat($row.data('gross')) || 0;

                    // Calculate daily salary
                    let daily_salary = working_days > 0 ? (gross / working_days).toFixed(2) : 0;

                    // Set daily salary
                    $row.find('.daily_salary').val(daily_salary);
                });

                $('#img').hide();
            }
        };

        xhttp.open("POST", "<?php echo site_url('SalaryController/working_day_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("month_id=" + month_id);
    }


    $(function() {
        $(".date").datepicker({
            "format": "dd-mm-yyyy"
        });
    });

    $(document).keypress(function(e) {
        if (e.which == 13) {
            //alert('enter key is pressed');
            var paid = $('#paid').val();
            // alert(paid);
            if (paid == '') {
                $("#add_more").click();
            } else {
                //  $("#submit").click();
            }
        }
    });
</script>

<script>
    function working_day_load(month_id) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                let working_days = parseInt(xhttp.responseText.trim());
                $('.working_days').each(function() {
                    $(this).val(working_days);
                    let $row = $(this).closest('tr');
                    let gross = parseFloat($row.data('gross')) || 0;
                    let daily_salary = working_days > 0 ? (gross / working_days).toFixed(2) : 0;
                    $row.find('.daily_salary').val(daily_salary);
                    calculateSalaryRow($row);
                });
            }
        };
        xhttp.open("POST", "<?php echo site_url('SalaryController/working_day_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("month_id=" + month_id);
    }

    function calculateSalaryRow($row) {
        let daily_salary = parseFloat($row.find('.daily_salary').val()) || 0;
        let working_days = parseFloat($row.find('.working_days').val()) || 0;
        let ot_allowance = parseFloat($row.find('.ot_allowance').val()) || 0;
        let over_time = parseFloat($row.find('.over_time').val()) || 0;
        let absent = parseFloat($row.find('.absent').val()) || 0;
        let other_allowance = parseFloat($row.find('.other_allowance').val()) || 0;
        let deduction = parseFloat($row.find('.deduction').val()) || 0;

        let total_salary = (daily_salary * working_days) + (daily_salary * over_time) - (daily_salary * absent) + ot_allowance + other_allowance;
        let payable = total_salary - deduction;

        $row.find('.total_salary').val(total_salary.toFixed(2));
        $row.find('.payable').val(payable.toFixed(2));

        // Recalculate the total net payable
        calculateNetPayable();
    }

    function calculateNetPayable() {
        let totalNetPayable = 0;
        $('.payable').each(function() {
            let val = parseFloat($(this).val()) || 0;
            totalNetPayable += val;
        });
        $('#net_payable').val(totalNetPayable.toFixed(2));
    }

    $(document).on('input', '.over_time, .ot_allowance, .other_allowance, .absent, .deduction', function() {
        let $row = $(this).closest('tr');
        calculateSalaryRow($row);
    });
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Salary Sheet</h3>
        </div>
        <div class="panel-body">
            <?php
            $salary = $this->db->where('salary_id', $salary_id)->get('salaries')->row();
            $salary_details = $this->db
                ->select('salary_details.*, employee.employee_name')
                ->from('salary_details')
                ->join('employee', 'employee.employee_id = salary_details.employee_id')
                ->where('salary_details.salary_id', $salary_id)
                ->get()
                ->result();
            ?>
            <form id="salary_entry_form" method="POST" class="form">
                <input type="hidden" required="" value="<?php echo $salary->salary_id; ?>" class="form-control" id="salary_id" name="salary_id">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Year *</label>
                            <div class="col-sm-8">
                                <select name="year_id" id="year_id" class="form-control">
                                    <option value="">Year</option>
                                    <?php
                                    $sql = $this->db->select('*')->get('year')->result();

                                    foreach ($sql as $value) {
                                    ?>
                                        <option <?php echo $value->year_id == $salary->year_id ? "selected" : "" ?> value="<?php echo $value->year_id ?>"><?php echo $value->name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Month *</label>
                            <div class="col-sm-8">
                                <select name="month_id" id="month_id" onchange="working_day_load(this.value)" class="form-control">
                                    <option value="">Month</option>
                                    <?php
                                    $sql = $this->db->select('*')->get('month')->result();

                                    foreach ($sql as $value) {
                                    ?>
                                        <option <?php echo $value->month_id == $salary->month_id ? "selected" : "" ?> value="<?php echo $value->month_id ?>"><?php echo $value->name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Date *</label>
                            <div class="col-sm-8">
                                <input type="text" required="" value="<?php echo date('d-m-Y', strtotime($salary->date)); ?>" class="form-control" id="datepicker1" name="date">
                            </div>
                        </div>
                    </div>

                </div>


                <div style="clear: left;margin-top:5px" class="table-responsive">
                    <table width="100%" border="1" class="table table-bordered table-striped">
                        <tr>
                            <input id="total_control" value="" type="hidden">
                            <td colspan="4" style="padding:5px;">
                                <?php
                                $active_employees = $this->db->where('status', "Active")->get('employee')->result();
                                ?>

                                <table class="table table-bordered" id="salary_table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee</th>
                                            <th>Gross<br> Salary</th>
                                            <th>Working<br> Days</th>
                                            <th>Daily<br> Salary</th>
                                            <th>Over<br> Time</th>
                                            <th>OT<br> Allowance</th>
                                            <th>Other<br> Allowance</th>
                                            <th>Total</th>
                                            <th>Absent</th>
                                            <th>Late</th>
                                            <th>Deduction</th>
                                            <th>Payable</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $serial = 1;
                                        foreach ($salary_details as $emp): ?>
                                            <tr data-gross="<?= $emp->gross_salary ?>">

                                                <td><?php echo $serial++; ?></td>
                                                <td><?= $emp->employee_name ?>
                                                    <input type="hidden" class="form-control employee_id" value="<?php echo $emp->employee_id ?>" name="employee_id[]">
                                                </td>
                                                <td><input style="width: 50px;" type="text" class="form-control gross_salary" name="gross_salary[]" value="<?= $emp->gross_salary ?>" readonly></td>
                                                <td><input style="width: 50px;" type="text" class="form-control working_days" name="working_days[]" value="<?= $emp->working_days ?>"></td>
                                                <td><input type="text" class="form-control daily_salary" name="daily_salary[]" value="<?= $emp->daily_salary ?>" readonly></td>
                                                <td><input type="number" style="width: 50px;" class="form-control over_time" name="over_time[]" value="<?= $emp->over_time ?>"></td>
                                                <td><input type="number" class="form-control ot_allowance" name="ot_allowance[]" value="<?= $emp->ot_allowance ?>"></td>
                                                <td><input type="number" class="form-control other_allowance" name="other_allowance[]" value="<?= $emp->other_allowance ?>"></td>
                                                <td><input type="number" class="form-control total_salary" name="total_salary[]" value="<?= $emp->total_salary ?>" readonly></td>
                                                <td><input style="width: 50px;" type="number" class="form-control absent" name="absent[]" value="<?= $emp->absent ?>"></td>
                                                <td><input type="text" style="width: 50px;" class="form-control late_day" name="late_day[]" value="<?= $emp->late_day ?>"></td>
                                                <td><input type="number" class="form-control deduction" name="deduction[]" value="<?= $emp->deduction ?>"></td>

                                                <td><input type="text" class="form-control payable" name="payable[]" readonly value="<?= $emp->payable ?>"></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>


                            </td>
                        </tr>
                    </table>
                </div>
                <table style="margin-top:5px;float:right;width:60%">

                    <tr>
                        <td>Net Payable</td>
                        <td> <input type="text" readonly="" name="net_payable" id="net_payable" class="form-control" value="<?php echo $salary->net_payable ?>"></td>


                    </tr>

                    <tr>
                        <td>
                            <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;" />
                        </td>
                        <td>
                            <button type="submit" name="submit_button" id="submit_button" class="pull-left btn btn-primary">Update</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<style>
    /* Adjust the padding/margin for specific elements */
    .control-label {
        padding-left: 0;
        padding-right: 0;
    }

    .form-control {
        margin-bottom: 0;
    }

    .col-md-3>.form-group>.col-sm-8 {
        padding-left: 0;
        padding-right: 0;
    }
</style>