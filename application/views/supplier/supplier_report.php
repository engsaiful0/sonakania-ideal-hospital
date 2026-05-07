<script>
    function supplier_due_details_load() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        var supplier_id = document.getElementById('supplier_id').value;


        var xhttp = new XMLHttpRequest();
        $('#img').show();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("expense_report_details").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('SupplierController/supplier_due_details_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("from_date=" + from_date + "&to_date=" + to_date + "&supplier_id=" + supplier_id);
    }
    $(document).ready(function () {
        $("#supplier_id").select2();
    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center"> Supplier Payment Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>From Date</td>
                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>
                <td>To Date</td>
                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>
                <td>Supplier</td>
                <td><select name="supplier_id" id="supplier_id" class="form-control">
                        <option></option>
                        <?php
                        $supplier = $this->db->select('*')->get('supplier')->result();
                        foreach ($supplier as $supplier_value) {
                            ?>
                            <option value="<?php echo $supplier_value->supplier_id ?>"><?php echo $supplier_value->supplier_name ?></option>
                            <?php
                        }
                        ?>

                    </select></td>
                <td><input type="submit" onclick="supplier_due_details_load()" value="Search"></td>
            </tr>
        </table>   

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none"/>
        <div id="expense_report_details">

        </div>


    </div>

</div>

