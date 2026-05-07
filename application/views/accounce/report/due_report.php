<script type="text/javascript">
    function due_report_details_load() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        $('#loadingSpinner').show();
        $.ajax({
            url: "<?php echo base_url('ReportAccountController/due_report_details_load'); ?>",
            method: "POST",
            data: {
                from_date: from_date,
                to_date: to_date,
            },
            dataType: "json",
            success: function(response) {
                $('#loadingSpinner').hide();
                if (response.status === 'success') {
                    $('#report_container').html(response.data);
                } else if (response.status === 'error') {
                    $('#report_container').html(response.data);
                }
            },
            error: function(xhr, status, error) {

            }
        });
    }
    $(document).ready(function() {
        $('#user_id').select2();
    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Due Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>From Date</td>
                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>
                <td>To Date</td>
                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>

                <td><input type="submit" class="btn btn-primary " onclick="due_report_details_load()" value="Search"></td>
            </tr>
        </table>

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
        <div id="report_container"">

        </div>
        <div class="col-md-4">
            <img
                src="<?php echo base_url(); ?>images/ajax-loader.gif"
                id="loadingSpinner"
                style="
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;"
                alt="Loading...">

        </div>


    </div>

</div>