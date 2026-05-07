<script>
    $(document).ready(function() {
        $('#debit_account_id').select2();
    });

    function account_debit_voucher_report_details() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        var debit_account_id = document.getElementById('debit_account_id').value;


        var ids = from_date + "_" + to_date + "_" + debit_account_id;
        window.open(
            '<?php echo site_url('ReportAccountController/account_debit_voucher_report_details'); ?>' + "/" + ids,
            '_blank' // <- This is what makes it open in a new window.
        );
    }
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Debit Voucher Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>Purpose</td>
                <td>
                    <select style="width:100% ;" type="text" class="form-control" id="debit_account_id" name="debit_account_id">
                        <option selected="" value="" disabled="">Select Purpose</option>
                        <?php
                        $debit_accounts = $this->db->select('*')->order_by('account_name')->get('debit_account')->result();
                        foreach ($debit_accounts as $debit_account) {
                        ?>
                            <option value="<?php echo $debit_account->debit_account_id; ?>"><?php echo $debit_account->account_name ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>From Date</td>
                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>
                <td>To Date</td>
                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>
                <td><input type="submit" class="btn btn-primary " onclick="account_debit_voucher_report_details()" value="Search"></td>
            </tr>
        </table>

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
        <div id="account_debit_voucher_report_details">

        </div>


    </div>

</div>