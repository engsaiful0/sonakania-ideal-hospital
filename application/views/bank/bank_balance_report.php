<script>
    function bank_balance_report_details() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
//        var bank_id = document.getElementById('bank_id').value;

        var xhttp = new XMLHttpRequest();
        $('#img').show();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("bank_balance_report_details").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('ReportController/bank_balance_report_details'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("from_date=" + from_date + "&to_date=" + to_date);
    }
    $(document).ready(function () {
        $("#bank_id").select2();
    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Bank Balance Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>From Date</td>
                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>


                <td>To Date</td>
                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>
<!--                <td>Bank</td>
                <td><select name="bank_id"  id="bank_id" class="form-control">
                        <option selected="" value="" disabled="">Bank</option>
                        <?php
                        $bank = $this->db->select('*')->get('bank')->result();
                        foreach ($bank as $bank_value) {
                            ?>
                            <option value="<?php echo $bank_value->bank_id ?>"><?php echo $bank_value->bank_name . '-' . $bank_value->account_number ?></option>
                            <?php
                        }
                        ?>
                    </select></td>-->
                <td><input type="submit" onclick="bank_balance_report_details()" class="btn btn-primary" value="Search"></td>
            </tr>
        </table>   

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none"/>
        <div id="bank_balance_report_details">

        </div>


    </div>

</div>

