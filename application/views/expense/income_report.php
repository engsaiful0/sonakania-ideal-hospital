<script>
    function income_details_load() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        var income_head_id = document.getElementById('income_head_id').value;
        var ids = from_date + "_" + to_date + "_" + income_head_id;
        window.open(
                '<?php echo site_url('ExpenseController/income_details_load'); ?>' + "/" + ids,
                '_blank' // <- This is what makes it open in a new window.
                );
    }
    $(document).ready(function () {
        $("#income_head_id").select2();
    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center"> Income Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>From Date</td>
                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>


                <td>To Date</td>
                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>
                <td>Income Head</td>
                <td><select name="income_head_id"  id="income_head_id" class="form-control">
                        <option selected="" value="" disabled="">Income Head</option>
                        <?php
                        $income_head = $this->db->select('*')->get('income_head')->result();
                        foreach ($income_head as $income_head_value) {
                            ?>
                            <option value="<?php echo $income_head_value->income_head_id ?>"><?php echo $income_head_value->income_head_name ?></option>
                            <?php
                        }
                        ?>
                    </select></td>
                <td><input type="submit" onclick="income_details_load()" class="btn btn-primary" value="Search"></td>
            </tr>
        </table>   

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none"/>
        <div id="income_details_load">

        </div>


    </div>

</div>

