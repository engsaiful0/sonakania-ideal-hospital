<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">View Biomedical Test</h3>
    </div>
    <div class="panel-body">
        <?php
        if ($this->session->userdata('deleted') != '') {
            ?>
            <div class="alert alert-success">
                <strong>Success!</strong>Data has been deleted successfully.
            </div>
            <?php
            $sdata['deleted'] = '';
            $this->session->set_userdata($sdata);
        }
        ?>
        <form method="post" action="<?php echo site_url('TestResultController/view_biomedical_test') ?>">
            <table>
                <tr>
                    <td>Patient</td>

                    <td>Biomedical Test No</td>
                    <td></td>
                </tr>
                <tr>

                    <td style="width: 300px;">
                        <select type="text" class="form-control"  id="patient_test_entry_id" name="patient_test_entry_id">
                            <option selected="" value="" disabled="">Select Patient</option> 
                            <?php
                            $patient_test_entry = $this->db->select('*')->get('patient_test_entry')->result();
                            foreach ($patient_test_entry as $patient_test_entry_value) {
                                ?>
                                <option value="<?php echo $patient_test_entry_value->patient_test_entry_id; ?>"><?php echo $patient_test_entry_value->patient_name . '-' . $patient_test_entry_value->invoice_no; ?></option>
                                <?php
                            }
                            ?>

                        </select>
                    </td>

                    <td style="width: 300px;">
                        <select type="text"   class="form-control"  id="biomedical_test_no" name="biomedical_test_no">
                            <option selected="" value="" disabled="">Select Test No</option> 
                            <?php
                            $biomedical_test = $this->db->select('*')->get('biomedical_test')->result();
                            foreach ($biomedical_test as $biomedical_test_value) {
                                ?>
                                <option value="<?php echo $biomedical_test_value->biomedical_test_no; ?>"><?php echo $biomedical_test_value->biomedical_test_no ?></option>
                                <?php
                            }
                            ?>

                        </select>
                    </td>
                    <td><input type="submit" class="btn btn-primary" value="Search"></td>
                </tr>
            </table>
        </form>
        <table class="table table-bordered table-hover table-condensed">
            <caption style="text-align: center"></caption>
            <tr>
                <td>Sl</td>
                <td>Patient Name</td>
                <td>Sex</td>
                <td>Mobile</td>
                <td>BT No</td>
                <td>Status</td>               
                <td>Date</td>
                <td>Edit</td>
                <td>Delete</td>
                <td>Print</td>
            </tr>
            <?php
            $sl = 1;

            for ($i = 0; $i < count($detailsList); ++$i) {
                $patient_test_entry = $this->db->where('patient_test_entry_id', $detailsList[$i]->patient_test_entry_id)
                                ->get('patient_test_entry')->row();
                $biomedical_test_id = $detailsList[$i]->biomedical_test_id;
                ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td><?php echo $patient_test_entry->patient_name ?></td>
                    <td><?php echo $patient_test_entry->gender ?></td>
                    <td><?php echo $patient_test_entry->mobile ?></td>
                    <td><?php
                        echo $detailsList[$i]->biomedical_test_no;
                        ?></td>
                    <td><?php
                        echo $detailsList[$i]->delivery_status;
                        ?></td>

                    <td><?php echo date('d-m-Y', strtotime($patient_test_entry->date)) ?></td>
                    <td>
                        <a  id="biomedicaltest_id_<?php echo $detailsList[$i]->biomedical_test_id ?>" onclick="modalLoadEdit(this.id)" class="btn btn-primary" data-target="#globalModalEdit"  data-toggle="modal" data-placement="top" data-content="update" href=""><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a>
                    </td>
                    <td><a onclick="return confirm('Do you want to delete?')" href="<?php echo site_url("TestResultController/biomedical_test_delete/$biomedical_test_id") ?>" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
                    <td><a class="btn btn-danger" href="<?php echo site_url("TestResultController/biomedical_test_report_print/$biomedical_test_id") ?>" ><i class="glyphicon glyphicon-print" aria-hidden="true"></i>
                        </a></td>
                </tr>
                <?php
            }
            ?>

        </table>
    </div>
    <div class="container" style="width: 100%">
        <div class="row" style="list-style: none ">

            <?php echo $pagination; ?>

        </div>

    </div>
</div>
<div class="modal"  id="globalModalEdit" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="">

        <div class="modal-content">

            <div class="modal-body">
                <div class="loader">
                    <div class="es-spinner">
                        <i class="glyphicon glyphicon-spinner fa-pulse fa-5x fa-fw"></i>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
<div class="modal"  id="globalModalDetails" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="">

        <div class="modal-content">

            <div class="modal-body">
                <div class="loader">
                    <div class="es-spinner">
                        <i class="glyphicon glyphicon-spinner fa-pulse fa-5x fa-fw"></i>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
<script>
    $(document).ready(function () {
// alert();
        $('#patient_test_entry_id').select2();
        $('#biomedical_test_no').select2();

    });
    function modalLoadPrint(rowId)
    {
        var data = rowId.split('_'); //To get the row id

        //alert(data[2]);
        $_token = "{{ csrf_token() }}";
        $.ajax({
            headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
            url: "<?php echo site_url('TestResultController/biomedical_test_report_print') ?>" + '/' + data[2],
            type: 'GET',
            cache: false,
            data: {'_token': $_token}, //see the $_token
            datatype: 'html',
            beforeSend: function () {
            },
            success: function (data) {

                // alert(data.length);
                //                    $('.modal-content').html(data);
                if (data.length > 0) {
                    // remove modal body
                    $('.modal-body').remove();
                    // add modal content
                    $('.modal-content').html(data);
                } else {
                    // add modal content
                    $('.modal-content').html('info');
                }
            }
        });
    }
    function modalLoadEdit(rowId) {

        var data = rowId.split('_'); //To get the row id

        //alert(data[2]);
        $_token = "{{ csrf_token() }}";
        $.ajax({
            headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
            url: "<?php echo site_url('TestResultController/edit_biochemical_test') ?>" + '/' + data[2],
            type: 'GET',
            cache: false,
            data: {'_token': $_token}, //see the $_token
            datatype: 'html',
            beforeSend: function () {
            },
            success: function (data) {

                // alert(data.length);
                //                    $('.modal-content').html(data);
                if (data.length > 0) {
                    // remove modal body
                    $('.modal-body').remove();
                    // add modal content
                    $('.modal-content').html(data);
                } else {
                    // add modal content
                    $('.modal-content').html('info');
                }
            }
        });
    }
</script>