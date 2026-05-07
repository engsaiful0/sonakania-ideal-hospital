<script>
    $(document).ready(function () {
        $('#product_category_id').select2();
    });

    function opd_patient_report_details() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        var doctor_id = document.getElementById('doctor_id').value;
        if (doctor_id == '')
        {
            alert("Select Doctor First");
        } else
        {

            var xhttp = new XMLHttpRequest();
            $('#img').show();
            xhttp.onreadystatechange = function () {
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                    document.getElementById("opd_patient_report_details").innerHTML = xhttp.responseText;
                    $('#img').hide();
                }
            }
            //                    alert(xhttp.responseText);
            xhttp.open("POST", "<?php echo site_url('PatientController/opd_payment_load'); ?>", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            //            xhttp.send("fname=Henry&lname=Ford");
            xhttp.send("from_date=" + from_date + "&to_date=" + to_date + "&doctor_id=" + doctor_id);
        }
    }
    $(document).ready(function () {
        $("#doctor_id").select2();
    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">OPD Patient Payment</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>Dcotor</td>
                <td style="width: 300px;">
                    <select type="text"  class="form-control" id="doctor_id" name="doctor_id">
                        <option selected="" value="" disabled="">Dcotor</option> 
                        <?php
                        $doctor = $this->db->select('*')->get('doctor')->result();
                        foreach ($doctor as $value) {
                            ?>
                            <option value="<?php echo $value->doctor_id; ?>"><?php echo $value->doctor_name; ?></option>
                            <?php
                        }
                        ?>

                    </select>
                </td>
                <td>From Date</td>
                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>
                <td>To Date</td>
                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>

                <td><input type="submit" class="btn btn-primary " onclick="opd_patient_report_details()" value="Search"></td>
            </tr>
        </table>   

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none"/>
        <div id="opd_patient_report_details">

        </div>


    </div>

</div>

