<script>
    $(document).ready(function () {
        $('#product_category_id').select2();
    });
    function sold_test_report_details_load() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        var test_id = document.getElementById('test_id').value;



        var ids = from_date + "_" + to_date + "_" + test_id;
        window.open(
                '<?php echo site_url('TestController/sold_test_report_details_load'); ?>' + "/" + ids,
                '_blank' // <- This is what makes it open in a new window.
                );
    }
    $(document).ready(function () {
        $("#test_id").select2();
    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Investigation Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>Category</td>
                <td style="width: 300px;">
                    <select type="text" required=""  class="form-control" id="test_id" name="test_id">
                        <option selected="" value="" disabled="">Test Name</option> 
                        <?php
                        $test = $this->db->select('*')->get('test')->result();
                        foreach ($test as $value) {
                            ?>
                            <option value="<?php echo $value->test_id; ?>"><?php echo $value->test_name; ?></option>
                            <?php
                        }
                        ?>

                    </select>
                </td>
                <td>From Date</td>
                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>
                <td>To Date</td>
                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>

                <td><input type="submit" class="btn btn-primary " onclick="sold_test_report_details_load()" value="Search"></td>
            </tr>
        </table>   

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none"/>
        <div id="sold_test_report_details_load">

        </div>


    </div>

</div>

