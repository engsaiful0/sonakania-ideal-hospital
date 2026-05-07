<script>
    function all_employee_salary_report_details_load() {
        var month = document.getElementById('month').value;

        var session = document.getElementById('session').value;
        var xhttp = new XMLHttpRequest();
        $('#img').show();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("all_employee_salary_report_details_load").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('EmployeeController/all_employee_salary_report_details'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("month=" + month + "&session=" + session);
    }
    $(document).ready(function () {
        $("#month").select2();
        $("#session").select2();
    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">All Employee Salary Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>

                <td>Month</td>
                <td>
                    <select id="month" name="month" class="form-control">
                        <option></option>
                        <option>January</option>
                        <option>February</option>
                        <option>March</option>
                        <option>April</option>
                        <option>May</option>
                        <option>Jun</option>
                        <option>July</option>
                        <option>August</option>
                        <option>September</option>
                        <option>October</option>
                        <option>November</option>
                        <option>December</option>
                    </select>

                </td>
                <td>Year</td>
                <td> 
                    <select id="session" name="session" class="form-control">
                        <option>2019</option>
                        <option>2020</option>
                    </select>
                </td>


                <td><input type="submit" class="btn btn-primary" onclick="all_employee_salary_report_details_load()" value="Search"></td>
            </tr>
        </table>   

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none"/>
        <div id="all_employee_salary_report_details_load">

        </div>


    </div>

</div>

