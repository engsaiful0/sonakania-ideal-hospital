<script>
    $(document).ready(function() {

        $("#drug_name").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('DrugController/drug_name_load'); ?>",
                    data: {
                        parameter: request.term
                    },
                    dataType: "json",
                    type: "POST",
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                $('#drug_name').val(ui.item.label);
                $('form').submit(); // Automatically submit the form
                return false;
            }
        });

    });
</script>
<script>
    function medicine_stock_report_details() {
        var drug_name = document.getElementById('drug_name').value;

        var ids = drug_name;
        if (ids == "") {
            window.open(
                '<?php echo site_url('ReportPharmacyController/medicine_stock_report_details_without_parameter'); ?>',
                '_blank' // <- This is what makes it open in a new window.
            );
        } else {
            window.open(
                '<?php echo site_url('ReportPharmacyController/medicine_stock_report_details'); ?>' + "/" + ids,
                '_blank' // <- This is what makes it open in a new window.
            );
        }

    }
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Medicine Stock Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>Search by Medicine Name</td>
            </tr>
            <tr>

                <td>
                    <input placeholder="Type drug name.." autofocus type="text" class="form-control" name="drug_name" id="drug_name">
                </td>

                <td><input type="submit" class="btn btn-primary " onclick="medicine_stock_report_details()" value="Search"></td>
            </tr>
        </table>

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
        <div id="medicine_stock_report_details">

        </div>


    </div>

</div>