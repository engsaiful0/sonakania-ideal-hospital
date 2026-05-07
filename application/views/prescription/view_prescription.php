<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">View Prescription</h3>
    </div>

    <div class="panel-body" style="width: 100%;">
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
        if ($this->session->userdata('update') != '') {
            ?>
            <div class="alert alert-success">
                <strong>Success!</strong>Data has been updated successfully.
            </div>
            <?php
            $sdata['update'] = '';
            $this->session->set_userdata($sdata);
        }
        ?>
        <script>
            $(document).ready(function () {

                $('#patient_name').select2();
                $('#gender').select2();
$('#invoice_no').select2();


            });
        </script>
        <form method="post" action="<?php echo site_url('PrescriptionController/view_prescription') ?>">
            <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 90%;">
                <tr>
                    <td>Patient Name</td>
                    <td>Gender</td>
                    <td>Id</td>
                    <td>Date</td>
                </tr>
                <tr>
                    <td>
                        <select id="patient_name" name="patient_name" class="form-control">
                            <option disabled="" selected="" value="">Patient Name</option>
                            <?php
                            $prescription = $this->db->where('is_deleted', '0')->get('prescription')->result();

                            foreach ($prescription as $prescription_value) {
                                ?>
                                <option value="<?php echo $prescription_value->patient_name ?>"><?php echo $prescription_value->patient_name ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>

                    <td>
                        <select id="gender" name="gender" class="form-control">
                            <option selected="" value="" disabled="">Select Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </td>
                    <td>
                        <select id="invoice_no" name="invoice_no" class="form-control">
                            <option disabled="" selected="" value="">Id</option>
                            <?php
                            $prescription = $this->db->where('is_deleted', '0')->get('prescription')->result();

                            foreach ($prescription as $prescription_value) {
                                ?>
                                <option value="<?php echo $prescription_value->invoice_no ?>"><?php echo $prescription_value->invoice_no ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>


                    <td>
                        <input id="datepicker1" name="date" class="form-control" value="">
                    </td>              
                    <td><input type="submit" id="sumbit_button" value="Search" class="btn btn-primary"></td>
                </tr>
            </table>
        </form>
        <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
            <tr>
                <td>Sl</td>
                <td>Patient Name</td>              
                <td>Age</td>
                <td>Gender</td>
                <td>BP</td>
                <td>Invoice No</td>

                <td>Date</td>
                <td>Edit</td>
                <td>Delete</td>
                <td>Details</td>
            </tr>
            <?php
            error_reporting(0);
            //  print_r( count($detailsList));

            $sl = 1;
            for ($i = 0; $i < count($detailsList); ++$i) {
                $prescription_id = $detailsList[$i]->prescription_id;
                ?>
                <tr>
                    <td><?php
                        echo $sl++;
                        ?></td>
                    <td><?php echo $detailsList[$i]->patient_name ?></td>


                    <td><?php echo $detailsList[$i]->age ?></td>
                    <td><?php echo $detailsList[$i]->gender ?>
                    </td>
                    <td><?php echo $detailsList[$i]->bp ?>
                    <td><?php echo $detailsList[$i]->invoice_no ?>
                    </td>

                    <td><?php echo date('d-m-Y', strtotime($detailsList[$i]->date)) ?></td>
                    <td><a href="" class="btn btn-primary" id="patient_id_<?php echo $prescription_id ?>" onclick="modalLoadEdit(this.id)" class="btn btn-primary" data-target="#globalModalEdit"  data-toggle="modal" data-placement="top" data-content="update" href=""><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a></td>
                    <td><a onclick="return confirm('Do you want to delete?')" href="<?php echo site_url("PrescriptionController/delete_this_prescription/$prescription_id") ?>" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
                    <td>
                        <a href="<?php echo site_url("PrescriptionController/print_prescription_again/$prescription_id") ?>"   class="btn btn-success" ><i class="glyphicon glyphicon-print" aria-hidden="true"></i></a>
                    </td>




                </tr>
                <?php
            }
            ?>

        </table>
    </div>
</div>
<div class="container" style="width: 100%">
    <div class="row" style="list-style: none ">
        <?php echo $pagination; ?>
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
<script>

</script>
<script>
    function modalLoadEdit(rowId) {

        var data = rowId.split('_'); //To get the row id

        //alert(data[2]);
        $_token = "{{ csrf_token() }}";
        $.ajax({
            headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
            url: "<?php echo site_url('PrescriptionController/edit_prescription') ?>" + '/' + data[2],
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