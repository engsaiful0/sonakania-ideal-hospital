<script>
    function user_id_wise_summary_report_details() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        var user_id = document.getElementById('user_id').value;

        var ids = from_date + "_" + to_date+ "_" + user_id;
        window.open(
            '<?php echo site_url('ReportAccountController/all_users_collection_report_details'); ?>' + "/" + ids,
            '_blank' // <- This is what makes it open in a new window.
        );
    }
    $(document).ready(function () {
        $("#user_id").select2();
    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">All Users Collection Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">

          <tr>
            <td>User</td>
             <td>From Date</td>
  <td>To Date</td>

        </tr>
            <tr>
              <td>
                <select class="form-control" id="user_id" name="user_id">
                    <option selected="" value="all" >All Users</option>
                    <?php
                    $users = $this->db->select('*')->get('user')->result();
                    foreach ($users as $value) {
                    ?>
                        <option value="<?php echo $value->user_id; ?>"><?php echo $value->user_name; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
                <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>

                <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>


                <td><input type="submit" class="btn btn-primary " onclick="user_id_wise_summary_report_details()" value="Search"></td>
            </tr>
        </table>
        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
        <div id="user_id_wise_summary_report_details">

        </div>


    </div>

</div>
