<script>
    function doctor_test_referece_report_load() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        var doctor_id = document.getElementById('doctor_id').value;

        var ids = from_date + "_" + to_date+"_"+doctor_id;
        window.open(
                '<?php echo site_url('ReportController/doctor_test_referece_report_load'); ?>' + "/" + ids,
                '_blank' // <- This is what makes it open in a new window.
                );
    }
    $(document).ready(function () {
        $("#doctor_id").select2();
    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Doctor Test Reference Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>Doctor</td>

                <td>
                    <select name="doctor_id" id="doctor_id" class="form-control">
                        <option value="" disabled="" selected="">Select Doctor</option>
                        <?php
                        $doctor = $this->db->select('*')->get('doctor')->result();
                        foreach ($doctor as $doctor_value) {
                            ?>
                            <option value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->doctor_name . '-' . $doctor_value->doctor_unique_id ?></option>
                            <?php
                        }
                        ?>

                    </select>

                </td>
                <td>From Date</td>
                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>
                <td>To Date</td>
                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>

                <td><input type="submit" class="btn btn-primary " onclick="doctor_test_referece_report_load()" value="Search"></td>
            </tr>
        </table>   

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none"/>
        <div id="doctor_test_referece_report_load">

        </div>


    </div>

</div>

