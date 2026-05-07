<script>
    function sent_sms_report_report_details() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        var type = document.getElementById('type').value;
        var ids = from_date + "_" + to_date + "_" + type;
        window.open(
            '<?php echo site_url('ReportMarkettingController/sent_sms_report_report_details'); ?>' + "/" + ids,
            '_blank' // <- This is what makes it open in a new window.
        );
    }
    $(document).ready(function() {
        $('#type').select2();
    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Send SMS Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>Type</td>
                <td>From Date</td>
                <td>To Date</td>
                <td></td>
            </tr>
            <tr>

                <td>
                    <select type="text" required="" class="form-control" id="type" name="type">
                        <option selected="" disabled="" value="">Select Type</option>
                        <option>OPD Patient</option>
                        <option>IPD Patient</option>
                        <option>Test Entry</option>
                        <option>Emergency</option>
                        <option>Phygiotherapy</option>
                        <option>Report Delivery</option>
                        <option>Discharge</option>
                        <option>Director</option>
                        <option>Doctor</option>
                        <option>Employee</option>
                    </select>
                </td>

                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>

                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>

                <td><input type="submit" class="btn btn-primary " onclick="sent_sms_report_report_details()" value="Search"></td>
            </tr>
        </table>

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
        <div id="sent_sms_report_report_details">

        </div>


    </div>

</div>