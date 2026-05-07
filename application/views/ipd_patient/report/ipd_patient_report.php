<script>
    function income_report_details_load() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        var status = document.getElementById('status').value;
        var ids = from_date + "_" + to_date+"_" + status;
        window.open(
            '<?php echo site_url('ReportIPDController/ipd_patient_report_details'); ?>' + "/" + ids,
            '_blank' // <- This is what makes i  t open in a new window.
        );
    }
    $(document).ready(function() {
        $("#supplier_id").select2();
    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">IPD Patient Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>From Date</td>
                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>
                <td>To Date</td>
                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>
                <td>
                    <select id="status" name="status" class="form-control">
                        <option value="">Select Status</option>
                        <option>Admitted</option>
                        <option>Discharged</option>
                    </select>
                </td>
                <td><input type="submit" class="btn btn-primary " onclick="income_report_details_load()" value="Search"></td>
            </tr>
        </table>

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
        <div id="income_report_details_load">

        </div>


    </div>

</div>