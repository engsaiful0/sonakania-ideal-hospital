<script>
    function bank_withdraw_report_details_load() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        var bank_id = document.getElementById('bank_id').value;
        var ids = from_date + "_" + to_date + "_" + bank_id;
        window.open(
                '<?php echo site_url('BankController/bank_withdraw_report_details'); ?>' + "/" + ids,
                '_blank' // <- This is what makes it open in a new window.
                );
    }
    $(document).ready(function () {
        $("#bank_id").select2();
    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Bank Withdraw Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>From Date</td>
                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>


                <td>To Date</td>
                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>
                <td>Bank</td>
                <td><select name="bank_id"  id="bank_id" class="form-control">
                        <option selected="" value="" disabled="">Select Bank</option>
                        <?php
                        $bank = $this->db->select('*')->get('bank')->result();
                        foreach ($bank as $bank_value) {
                            ?>
                            <option value="<?php echo $bank_value->bank_id ?>"><?php echo $bank_value->bank_name . '-' . $bank_value->account_number ?></option>
                            <?php
                        }
                        ?>
                    </select></td>
                <td><input type="submit" onclick="bank_withdraw_report_details_load()" class="btn btn-primary" value="Search"></td>
            </tr>
        </table>   

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none"/>
        <div id="bank_withdraw_report_details_load">

        </div>


    </div>

</div>

