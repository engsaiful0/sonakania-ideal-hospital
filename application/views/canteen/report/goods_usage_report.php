<script>
    function goods_usage_report_details() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        var ids = from_date + "_" + to_date;
        window.open(
            '<?php echo site_url('ReportCanteenController/goods_usage_report_details'); ?>' + "/" + ids,
            '_blank' // <- This is what makes it open in a new window.
        );
    }
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Goods Usage Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>From Date</td>
                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>
                <td>To Date</td>
                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>

                <td><input type="submit" class="btn btn-primary " onclick="goods_usage_report_details()" value="Search"></td>
            </tr>
        </table>

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
        <div id="goods_usage_report_details">

        </div>


    </div>

</div>