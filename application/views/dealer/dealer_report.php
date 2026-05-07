<script>
    function dealer_report_details_load() {

        var dealer_id = document.getElementById('dealer_id').value;
         var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;

        var xhttp = new XMLHttpRequest();
        $('#img').show();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("expense_report_details").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('DealerController/dealer_report_details_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("dealer_id=" + dealer_id+"&from_date="+from_date+"&to_date="+to_date);
    }
    $(document).ready(function () {
        $("#dealer_id").select2();
    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Dealer Transaction Report</h3>
    </div>
    <div class="panel-body">
        <table class="table" style="width: 100%">
            <tr>
                <td>Dealer</td>
                <td>From Date</td>
                <td>To Date</td>
                <td></td>

            </tr>
            <tr>

                <td>
                    <select name="dealer_id" id="dealer_id" class="form-control">
                        <option></option>
                        <?php
                        $dealer = $this->db->select('*')->get('dealer')->result();
                        foreach ($dealer as $dealer_value) {
                            ?>
                            <option value="<?php echo $dealer_value->dealer_id ?>"><?php echo $dealer_value->dealer_name ?></option>
                            <?php
                        }
                        ?>

                    </select>
                </td>

                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>



                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>

                <td><input type="submit" class="btn btn-primary " onclick="dealer_report_details_load()" value="Search"></td>
            </tr>
        </table>   

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none"/>
        <div id="expense_report_details">

        </div>


    </div>

</div>

