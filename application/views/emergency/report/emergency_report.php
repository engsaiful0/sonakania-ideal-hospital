<script>
    function emergency_report_details_load() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        // var emergency_service_id = document.getElementById('emergency_service_id').value;
        var ids = from_date + "_" + to_date;
        window.open(
            '<?php echo site_url('ReportEmergencyController/emergency_report_details_load'); ?>' + "/" + ids,
            '_blank' // <- This is what makes it open in a new window.
        );
    }
</script>
<script>
$(document).ready(function () {
$('#emergency_service_id').select2();
});
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Emergency Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
          <tr>
            <!-- <td>Emergency Service</td> -->
<td>From Date</td>
<td>To Date</td>
<td></td>
        </tr>
          <tr>
            <td style="display:none">
              <select name="emergency_service_id" class="form-control" id="emergency_service_id" style="width:250px;">
                <option value="" selected="">Select Emergency Service</option>
                <?php
                $emergency_services = $this->db->select('*')->get('emergency_service')->result();

                foreach ($emergency_services as $value) {
                ?>
                    <option value="<?php echo $value->emergency_service_id ?>"><?php echo $value->name ?></option>
                <?php
                }
                ?>
            </select>
          </td>


                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>

                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>

                <td><input type="submit" class="btn btn-primary " onclick="emergency_report_details_load()" value="Search"></td>
            </tr>
        </table>

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
        <div id="emergency_report_details_load">

        </div>


    </div>

</div>
