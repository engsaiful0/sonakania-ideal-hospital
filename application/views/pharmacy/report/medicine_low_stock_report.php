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
    function medicine_low_stock_report_details() {
        var drug_name = document.getElementById('drug_name').value;

        if (drug_name == "") {
            window.open(
                '<?php echo site_url('ReportPharmacyController/medicine_low_stock_report_details_without_parameter'); ?>',
                '_blank'
            );
        } else {
            // Use POST method to avoid URL encoding issues
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo site_url('ReportPharmacyController/medicine_low_stock_report_details_with_search'); ?>';
            form.target = '_blank';
            
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'drug_name';
            input.value = drug_name;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    }

    function viewLowStockReport() {
        window.open(
            '<?php echo site_url('ReportPharmacyController/medicine_low_stock_report_details_without_parameter'); ?>',
            '_blank'
        );
    }

    function exportLowStockToExcel() {
        window.open(
            '<?php echo site_url('ReportPharmacyController/export_low_stock_to_excel'); ?>',
            '_blank'
        );
    }

    function printLowStockReport() {
        window.open(
            '<?php echo site_url('ReportPharmacyController/print_low_stock_report'); ?>',
            '_blank'
        );
    }
</script>
<div class="panel panel-danger">
    <div class="panel-heading">
        <h3 style="text-align: center">🚨 Medicine Low Stock Report</h3>
        <p style="text-align: center; margin: 0; color: #fff;">
            <small>Monitor medicines that need immediate restocking</small>
        </p>
    </div>
    <div class="panel-body">
        <!-- Quick Action Buttons -->
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-12">
                <div class="btn-group btn-group-justified" role="group">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-danger btn-lg" onclick="viewLowStockReport()">
                            <i class="glyphicon glyphicon-eye-open"></i> View All Low Stock
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-info btn-lg" onclick="printLowStockReport()">
                            <i class="glyphicon glyphicon-print"></i> Print Report
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-success btn-lg" onclick="exportLowStockToExcel()">
                            <i class="glyphicon glyphicon-download-alt"></i> Export to Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <!-- Search Section -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="glyphicon glyphicon-search"></i> Search Specific Medicine</h4>
            </div>
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td><strong>Search by Medicine Name:</strong></td>
                    </tr>
                    <tr>
                        <td style="width: 70%;">
                            <input placeholder="Type medicine name (partial names work too)..." 
                                   autofocus type="text" class="form-control" name="drug_name" id="drug_name">
                        </td>
                        <td>
                            <input type="submit" class="btn btn-primary btn-block" 
                                   onclick="medicine_low_stock_report_details()" value="Search Specific">
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="alert alert-info">
            <h4><i class="glyphicon glyphicon-info-sign"></i> About Low Stock Report</h4>
            <p><strong>This report shows medicines where:</strong></p>
            <ul>
                <li>Current stock is <strong>less than</strong> the reorder quantity</li>
                <li>Medicines with <strong>reorder quantity set to 0 or blank</strong> are excluded</li>
                <li><span class="label label-warning">LOW STOCK</span> - Stock below reorder level</li>
                <li><span class="label label-danger">OUT OF STOCK</span> - Zero or negative stock</li>
            </ul>
        </div>

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
        <div id="medicine_low_stock_report_details"></div>
    </div>
</div>