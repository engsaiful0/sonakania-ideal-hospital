<script>
    function medicine_purchase_supplier_details_report_load() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        var supplier_id=document.getElementById('supplier_id').value;

        var ids = from_date + "_" + to_date+"_"+supplier_id;
        window.open(
            '<?php echo site_url('ReportPharmacyController/medicine_purchase_supplier_details_report_load'); ?>' + "/" + ids,
            '_blank' // <- This is what makes it open in a new window.
        );
    }

    $(document).ready(function() {
        $("#supplier_id").select2();
    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Medicine Purchase Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>From Date</td>
                <td>To Date</td>
                <td>Supplier</td>
                <td></td>
            </tr>
            <tr>
                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>
                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>
                <td>
                    <select name="supplier_id" id="supplier_id" class="form-control">
                        <option value="">Medicine Supplier</option>
                        <?php
                        $sql = $this->db->where('type', 'medicine_supplier')->order_by('name', 'ASC')->get('supplier')->result();

                        foreach ($sql as $value) {
                        ?>
                            <option value="<?php echo $value->supplier_id ?>"><?php echo $value->name ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td><input type="submit" class="btn btn-primary " onclick="medicine_purchase_supplier_details_report_load()" value="Search"></td>
            </tr>
        </table>

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
        <div id="medicine_purchase_supplier_details_report_load">

        </div>


    </div>

</div>