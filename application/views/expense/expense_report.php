<script>
    function expense_details_load() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        var expense_head_id = document.getElementById('expense_head_id').value;
        var ids = from_date + "_" + to_date + "_" + expense_head_id;
        window.open(
                '<?php echo site_url('ReportController/expense_details_load'); ?>' + "/" + ids,
                '_blank' // <- This is what makes it open in a new window.
                );
    }
    $(document).ready(function () {
        $("#expense_head_id").select2();
    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center"> Expense Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>From Date</td>
                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>


                <td>To Date</td>
                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>
                <td>Expense Head</td>
                <td><select name="expense_head_id"  id="expense_head_id" class="form-control">
                        <option selected="" value="" disabled="">Expense Head</option>
                        <?php
                        $exoense_head = $this->db->select('*')->get('expense_head')->result();
                        foreach ($exoense_head as $exoense_head_value) {
                            ?>
                            <option value="<?php echo $exoense_head_value->expense_head_id ?>"><?php echo $exoense_head_value->expense_head_name ?></option>
                            <?php
                        }
                        ?>
                    </select></td>
                <td><input type="submit" onclick="expense_details_load()" class="btn btn-primary" value="Search"></td>
            </tr>
        </table>   

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none"/>
        <div id="expense_report_details">

        </div>


    </div>

</div>

