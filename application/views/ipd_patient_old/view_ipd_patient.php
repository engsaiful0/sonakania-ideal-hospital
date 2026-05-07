<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Patient View</h3>
    </div>

    <div class="panel-body" style="width: 100%;">
        <script>
            jQuery(document).ready(function () {
                jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                    $(this).slideUp('slow', function () {
                        $(this).remove();
                    });
                });
            });
        </script>
        <div class="col-md-12">
            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                <?php
                error_reporting(0);
                // var_dump($this->session->userdata('deleted'));
                if ($this->session->userdata('deleted') != '') {
                    ?>                   
                    <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a> <strong>Success!</strong>Data has been deleted successfully.</p>                      
                    <?php
                    $sdata['deleted'] = '';
                    $this->session->set_userdata($sdata);
                }
                if ($this->session->userdata('update') != '') {
                    ?>
                    <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>   <strong>Success!</strong>Data has been updated successfully.</p>  
                    <?php
                    $sdata['update'] = '';
                    $this->session->set_userdata($sdata);
                }
                ?>
            </div>
        </div>
        <?php
        ?>
        <script>
            $(document).ready(function () {
                $('#patient_name').select2();
                $('#mobile_number').select2();
                $('#patient_unique_id').select2();
                $('#gender').select2();
                $('#doctor_id').select2();
                $('#reference_media_id').select2();
                $('#general_bed_id').select2();
                $('#cabin_id').select2();
            });
        </script>
        <form method="post" action="<?php echo site_url('PatientController/view_patient') ?>">
            <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                <tr>
                    <td>Patient Name</td>
                    <td>Mobile Number</td>
                    <td>ID</td>
                    <td>Gender</td>
                    <td>Ref. Doctor</td>
                    <td>Ref. Media</td>
                    <td>Bed Number</td>
                    <td>Cabin Number</td>
                    <td>Date</td>
                    <td></td>
                </tr>
                <tr>

                    <td>
                        <select id="patient_name" name="patient_name" class="form-control">
                            <option value="" disabled="" selected=""></option>
                            <?php
                            $patient = $this->db->select('*')->get('patient')->result();
                            foreach ($patient as $patient_value) {
                                ?>
                                <option><?php echo $patient_value->patient_name ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>

                    <td>
                        <select id="mobile_number" name="mobile_number" class="form-control">
                            <option></option>
                            <?php
                            $patient = $this->db->select('*')->get('patient')->result();
                            foreach ($patient as $patient_value) {
                                ?>
                                <option><?php echo $patient_value->mobile_number ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select id="patient_unique_id" name="patient_unique_id" class="form-control">
                            <option></option>
                            <?php
                            $patient = $this->db->select('*')->get('patient')->result();
                            foreach ($patient as $patient_value) {
                                ?>
                                <option><?php echo $patient_value->patient_unique_id ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select id="gender" name="gender" class="form-control">
                            <option></option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option> 

                        </select>
                    </td>
                    <td>
                        <select id="doctor_id" name="doctor_id" class="form-control">
                            <option></option>
                            <?php
                            $doctor = $this->db->select('*')->get('doctor')->result();
                            foreach ($doctor as $doctor_value) {
                                ?>
                                <option value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->doctor_name ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select id="reference_media_id" name="reference_media_id" class="form-control">
                            <option></option>
                            <?php
                            $reference_media = $this->db->select('*')->get('reference_media')->result();
                            foreach ($reference_media as $reference_media_value) {
                               
                                ?>
                                <option value="<?php echo $reference_media_value->reference_media_id ?>"><?php echo $reference_media_value->reference_media_name ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select id="bed_id" name="bed_id" class="form-control">
                            <option></option>
                            <?php
                            $beds = $this->db->select('*')->get('bed')->result();
                            foreach ($beds as $bed) {
                                
                                ?>
                                <option value="<?php echo $bed->bed_id ?>"><?php echo $bed->bed_number ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select id="cabin_id" name="cabin_id" class="form-control">
                            <option></option>
                            <?php
                            $cabin = $this->db->select('*')->get('cabin')->result();
                            foreach ($cabin as $cabin_value) {
                               
                                ?>
                                <option value="<?php echo $cabin_value->cabin_id ?>"><?php echo $cabin_value->cabin_number ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input id="datepicker1" name="date" class="form-control">

                    </td>
                    <td><input type="submit" value="Submit" class="btn btn-primary"></td>
                </tr>

            </table>
        </form>
        <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
            <tr>
                <td style="width: 2%;">Sl</td>
                <td style="width: 10%;">Patient Name</td>
                <td style="width: 10%;">Mobile</td>
                <td style="width: 10%;">Id</td>  
                <td style="width: 10%;">Gender</td>
                <td style="width: 10%;">Ref. Doctor</td>             
                <td style="width: 10%;">Ref. Media</td> 
                <td style="width: 10%;">Bed</td>
                <td style="width: 10%;">Cabin</td>
                <td style="width: 10%;">Date & Time</td>                
                <td style="width: 5%;">Edit</td>
                <td style="width: 5%;">Delete</td>
                <td style="width: 5%;">Print</td>
            </tr>
            <?php
//print_r( count($detailsList));

            $sl = 1;
            for ($i = 0; $i < count($detailsList); ++$i) {
                $doctor = $this->db->where('doctor_id', $detailsList[$i]->doctor_id)->get('doctor')->row();
                $reference_media = $this->db->where('reference_media_id', $detailsList[$i]->reference_media_id)->get('reference_media')->row();
                $bed = $this->db->where('bed_id', $detailsList[$i]->bed_id)->get('bed')->row();
                $cabin = $this->db->where('cabin_id', $detailsList[$i]->cabin_id)->get('cabin')->row();
                $patient_id = $detailsList[$i]->patient_id;
                ?>
                <tr>
                    <td><?php
            echo $sl++;
                ?></td>
                    <td><?php echo $detailsList[$i]->patient_name ?></td>


                    <td>  <?php echo $detailsList[$i]->mobile_number ?></td>
                    <td><?php echo $detailsList[$i]->patient_unique_id ?></td>
                    <td><?php echo $detailsList[$i]->gender ?></td>
                    <td><?php echo $doctor->name ?> </td>
                    <td> <?php echo $reference_media->reference_media_name ?></td>

                    <td><?php echo $bed->bed_number ?> </td>
                    <td> <?php echo $cabin->cabin_number ?></td>
                    <td> <?php echo date('d-m-Y', strtotime($detailsList[$i]->date)) . '-' . $detailsList[$i]->admission_time ?></td>

                    <td><a href="<?php echo site_url("PatientController/ipd_patient_edit/$patient_id") ?>" class="btn btn-primary"  style="color: white"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a></td>
                    <td><a onclick="return confirm('Do you want to delete?')" href="<?php echo site_url("PatientController/delete_this_opd_patient/$patient_id") ?>" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
                    <td><a class="btn btn-primary" id="" class="btn btn-success"   href="<?php echo site_url("PatientController/ipd_patient_print_again/$patient_id") ?>"><i class="glyphicon glyphicon-print" aria-hidden="true"></i></a></td>
                </tr>
                <?php
            }
            ?>

        </table>
    </div>
</div>


<div class="container" style="width: 100%">
    <div class="row" style="list-style: none ">
    <?php echo $this->pagination->create_links(); ?>
    </div>

</div>

<div class="modal"  id="globalModalPermission" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
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
    function modalLoadEdit(rowId) {

        var data = rowId.split('_'); //To get the row id

        //alert(data[2]);
        $_token = "{{ csrf_token() }}";
        $.ajax({
            headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
            url: "<?php echo site_url('PatientController/patient_edit') ?>" + '/' + data[2],
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