<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">View OPD Patient </h3>
    </div>

    <div class="panel-body" style="width: 100%;">
        <script>
            $(document).ready(function() {
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
        <script>
            jQuery(document).ready(function() {
                jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                    $(this).slideUp('slow', function() {
                        $(this).remove();
                    });
                });
            });

            function doctor_load(department_id) {
                $('#img').show();
                //alert(product_category_id);
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        document.getElementById("doctor_id").innerHTML = xhttp.responseText;
                        $('#img').hide();
                    }
                }
                //  alert(xhttp.responseText);
                xhttp.open("POST", "<?php echo site_url('PatientController/doctor_load'); ?>", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                //            xhttp.send("fname=Henry&lname=Ford");
                xhttp.send("department_id=" + department_id);
            }
        </script>
        <div class="col-md-12">
            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                <?php
                error_reporting(0);
                // var_dump($_SESSION);
                if ($this->session->userdata('deleted') != '') {
                ?>
                    <p class="alert alert-success alert-auto-hide dism " style="text-align: center"> <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a> <strong>Success!</strong>Data has been deleted successfully.</p>
                <?php
                    $sdata['deleted'] = '';
                    $this->session->set_userdata($sdata);
                }
                if ($this->session->userdata('update') != '') {
                ?>
                    <p class="alert alert-success alert-auto-hide dism " style="text-align: center"> <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a> <strong>Success!</strong>Data has been updated successfully.</p>
                <?php
                    $sdata['update'] = '';
                    $this->session->set_userdata($sdata);
                }
                ?>
            </div>
        </div>
        <?php
        ?>

        <form method="post" action="<?php echo site_url('PatientController/view_opd_patient') ?>">
            <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                <tr>
                    <td>Department</td>
                    <td>Doctor</td>
                    <td>Gender</td>
                    <td>From Date</td>
                    <td>To Date</td>

                    <td></td>
                </tr>
                <tr>

                    <td>
                        <select id="patient_name" onchange="doctor_load(this.value)" name="patient_name" class="form-control">
                            <option value="" disabled="" selected=""> Select Department</option>
                            <?php
                            $department = $this->db->select('*')->get('department')->result();
                            foreach ($department as $department_value) {
                            ?>
                                <option value="<?php echo $department_value->department_id ?>"><?php echo $department_value->department_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>

                    <td>
                        <select id="doctor_id" name="doctor_id" class="form-control">
                            <option value="" disabled="" selected="">Select Doctor</option>

                        </select>
                    </td>

                    <td>
                        <select id="gender" name="gender" class="form-control">
                            <option value="" disabled="" selected="">Selct Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>

                        </select>
                    </td>


                    <td>
                        <input id="datepicker1" name="from_date" class="form-control">

                    </td>

                    <td>
                        <input id="datepicker2" name="to_date" class="form-control">

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
                <td style="width: 10%;">Age</td>
                <td style="width: 10%;">Gender</td>
                <td style="width: 10%;">Department</td>
                <td style="width: 10%;">Doctor</td>
                <td style="width: 10%;">Serial</td>
                <td style="width: 10%;">Visiting Fee</td>
                <td style="width: 10%;">Visiting Date & Time</td>
              
                <td style="width: 10%;">Date</td>
                <?php
                $user_type_name = $this->session->userdata('user_type');
                if ($user_type_name == 'Admin') {
                ?>
                    <td style="width: 10%;">User</td>
                <?php
                }
                ?>
                <td style="width: 5%;">Edit</td>
                <td style="width: 5%;">Delete</td>
                <td style="width: 5%;">Print</td>
            </tr>
            <?php
            //print_r( count($detailsList));

            $sl = 1;
            for ($i = 0; $i < count($detailsList); ++$i) {
                $doctor = $this->db->where('doctor_id', $detailsList[$i]->doctor_id)->get('doctor')->row();
                $department = $this->db->where('department_id', $detailsList[$i]->department_id)->get('department')->row();
                $opd_patient_id = $detailsList[$i]->opd_patient_id;
                
            ?>
                <tr>
                    <td><?php
                        echo $sl++;
                        ?></td>
                    <td><?php echo $detailsList[$i]->opd_patient_name ?></td>


                    <td> <?php echo $detailsList[$i]->mobile_number ?></td>
                    <td><?php echo $detailsList[$i]->age ?></td>
                    <td><?php echo $detailsList[$i]->gender ?></td>
                    <td> <?php echo $department->department_name ?></td>
                    <td><?php echo $doctor->doctor_name ?> </td>


                    <td><?php echo $detailsList[$i]->serial_numaber ?> </td>
                    <td> <?php echo $detailsList[$i]->visiting_fee ?></td>
                    <td> <?php echo date('d-m-Y', strtotime($detailsList[$i]->visiting_date)) . '-' . $detailsList[$i]->visiting_time ?></td>
                    <td> <?php echo date('d-m-Y', strtotime($detailsList[$i]->entry_date)) ?></td>
                    <td><a href="<?php echo site_url("PatientController/opd_patient_edit/$opd_patient_id") ?>" class="btn btn-primary" class="btn btn-primary" style="color: white"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a></td>
                    <td><a onclick="return confirm('Do you want to delete?')" href="<?php echo site_url("PatientController/delete_this_opd_patient/$opd_patient_id") ?>" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
                    <td><a class="btn btn-primary" id="" class="btn btn-success" href="<?php echo site_url("PatientController/opd_patient_print_again/$opd_patient_id") ?>"><i class="glyphicon glyphicon-print" aria-hidden="true"></i></a></td>
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

<div class="modal" id="globalModalPermission" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
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

<div class="modal" id="globalModalEdit" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
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
            headers: {
                'X-CSRF-Token': $('meta[name=_token]').attr('content')
            },
            url: "<?php echo site_url('PatientController/opd_patient_edit') ?>" + '/' + data[2],
            type: 'GET',
            cache: false,
            data: {
                '_token': $_token
            }, //see the $_token
            datatype: 'html',
            beforeSend: function() {},
            success: function(data) {

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