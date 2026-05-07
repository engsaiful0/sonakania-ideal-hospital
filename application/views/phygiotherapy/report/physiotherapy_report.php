<script>
    function phygiotherapy_report_details_load() {
        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        var phygiotherapy_service_id = document.getElementById('phygiotherapy_service_id').value;

        var ids = from_date + "_" + to_date+ "_" + phygiotherapy_service_id;
        window.open(
            '<?php echo site_url('ReportPhysiotherapyController/physiotherapy_report_details_load'); ?>' + "/" + ids,
            '_blank' // <- This is what makes it open in a new window.
        );
    }
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Phygiotherapy Report</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>From Date</td>

                <td>To Date</td>
                <td>Phygiotherapy Service</td>
                <td></td>



            </tr>
            <tr>
                  <td><input name="from_date" value="<?php echo date('d-m-Y') ?>" id="datepicker1" class="form-control"></td>
                      <td><input name="to_date" value="<?php echo date('d-m-Y') ?>" id="datepicker2" class="form-control"></td>
              <td>
                  <select id="phygiotherapy_service_id" name="phygiotherapy_service_id" class="form-control">
                      <option></option>
                      <?php
                      $phygiotherapy_services = $this->db->select('*')->get('phygiotherapy_service')->result();
                      foreach ($phygiotherapy_services as $phygiotherapy_service) {
                      ?>
                          <option value="<?php echo $phygiotherapy_service->phygiotherapy_service_id  ?>"><?php echo $phygiotherapy_service->name ?></option>
                      <?php
                      }
                      ?>
                  </select>
              </td>
              <td><input type="submit" class="btn btn-primary " onclick="phygiotherapy_report_details_load()" value="Search"></td>
            </tr>
        </table>

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
        <div id="phygiotherapy_report_details_load">

        </div>


    </div>

</div>
