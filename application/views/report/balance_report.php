<script>
    
      function balance_report_details_load() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;

        var ids = from_date + "_" + to_date;
        window.open(
                '<?php echo site_url('ReportController/balance_report_details_load'); ?>' + "/" + ids,
                '_blank' // <- This is what makes it open in a new window.
                );
    }
    function balance_report_details_load22() {
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
        xhttp.open("POST", "<?php echo site_url('ReportController/balance_report_details_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("from_date=" + from_date + "&to_date=" + to_date);
    }

</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Balance Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
               <td>From Date</td>
                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>
                <td>To Date</td>
                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>

                <td><input type="submit" onclick="balance_report_details_load()" class="btn btn-primary" value="Search"></td>
            </tr>
        </table>   

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none"/>
        <div id="bank_balance_report_details">

        </div>


    </div>

</div>

