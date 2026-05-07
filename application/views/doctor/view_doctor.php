<?php
$permissions = $this->session->userdata('permissions');
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">View Doctor</h3>
    </div>
    <div class="panel-body" style="width: 100%;">

        <script>
            $(document).ready(function() {

                $('#doctor_id').select2();

            });

            function deleteDoctor(doctor_id, row_id) {
                Swal.fire({
                    title: 'Do you want to delete this?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "<?php echo site_url('DoctorController/delete_this_doctor_ajax'); ?>",
                            type: 'POST',
                            data: {
                                doctor_id: doctor_id
                            },
                            success: function(response) {
                                var res = JSON.parse(response);
                                if (res.status == 'success') {
                                    $.toast({
                                        heading: 'Success',
                                        text: res.message,
                                        showHideTransition: 'slide',
                                        position: 'top-right',
                                        hideAfter: 1000,
                                        icon: 'success'
                                    });

                                    $('#' + row_id).remove();
                                } else {
                                    $.toast({
                                        heading: 'Error',
                                        text: res.message,
                                        showHideTransition: 'slide',
                                        position: 'top-right',
                                        hideAfter: 2000,
                                        icon: 'error'
                                    });
                                }
                            }
                        });
                    }
                });
            }
        </script>

        <?php if (in_array('hrm_doctor_search', $permissions)) { ?>
            <form method="post" action="<?php echo site_url('DoctorController/view_doctor') ?>">
                <table>
                    <tr>
                        <td>Doctor</td>
                        <td></td>
                    </tr>
                    <tr>

                        <td style="width: 300px;">
                            <select type="text" class="form-control" id="doctor_id" name="doctor_id">
                                <option selected="" value="" disabled="">Doctor</option>
                                <?php
                                $doctor = $this->db->select('*')->get('doctor')->result();
                                foreach ($doctor as $value) {
                                ?>
                                    <option value="<?php echo $value->doctor_id; ?>"><?php echo $value->doctor_name . '-' . $value->doctor_unique_id; ?></option>
                                <?php
                                }
                                ?>

                            </select>
                        </td>


                        <td><input type="submit" class="btn btn-primary" value="Search"></td>
                    </tr>
                </table>
            </form>
        <?php } ?>
        <table class="table table-hover table-bordered table-condensed">
            <tr>
                <td>#</td>
                <td>Doctor</td>
                <td>Degree</td>
                <td>Mobile</td>
                <td>Visiting Fee</td>
                <td>Visiting Fee<br>Withing 7 Dyas</td>
                <td>Visiting Fee<br>Withing 15 Dyas</td>
                <td>Visiting Fee<br>Withing 30 Dyas</td>
                <td>Picture</td>
                <td>OPD(%)</td>
                <td>IPD(%)</td>
                <td>LAB(%)</td>
                <?php
                $user_type_name = $this->session->userdata('user_type');
                if ($user_type_name == 'Admin') {
                ?>
                    <td>User</td>
                <?php
                }
                ?>
                <?php if (in_array('hrm_doctor_edit', $permissions)) { ?>
                    <td>Edit</td>
                <?php } ?>
                <?php if (in_array('hrm_doctor_delete', $permissions)) { ?>
                    <td>Delete</td>
                <?php } ?>
            </tr>
            <?php
            //print_r( count($detailsList));
            $sl = 1;
            $grand_total = 0;
            for ($i = 0; $i < count($detailsList); ++$i) {

                $doctor = $this->db->where('doctor_id', $detailsList[$i]->doctor_id)->get('doctor')->row();

                $doctor_id = $detailsList[$i]->doctor_id;
                $user = getUserById($detailsList[$i]->user_id);
                //   print_r($category);
            ?>
                <tr id="doctor-row-<?php echo $doctor_id; ?>">
                    <td><?php echo $sl++ ?></td>
                    <td><?php echo $doctor->doctor_name . '-' . $doctor->doctor_unique_id ?></td>
                    <td><?php
                        echo $detailsList[$i]->degree;
                        ?>
                    </td>
                    <td><?php echo $detailsList[$i]->mobile ?></td>

                    <td><?php echo $detailsList[$i]->new_patient_fee ?></td>
                    <td><?php echo $detailsList[$i]->within_seven_days_visiting_fee ?></td>
                    <td><?php echo $detailsList[$i]->within_fifteen_days_visiting_fee ?></td>
                    <td><?php echo $detailsList[$i]->within_thirty_days_visiting_fee ?></td>
                    <td> <?php
                            if ($detailsList[$i]->picture != '') {
                            ?>
                            <img style="height: 50px;width: 100px;" src="<?php echo base_url() ?>assets/doctor_picture/<?php echo $doctor->picture ?>">
                        <?php
                            }
                        ?>
                    </td>
                    <td><?php echo $detailsList[$i]->opd_patient_percentage ?></td>
                    <td><?php echo $detailsList[$i]->ipd_commission_percentage ?></td>

                    <td><?php echo $detailsList[$i]->lab_percentage ?></td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td><?php echo $user->user_name ?? "" ?> </td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('hrm_doctor_edit', $permissions)) { ?>
                        <td>
                            <a class="btn btn-primary" href="<?php echo site_url("DoctorController/doctor_edit/$doctor_id") ?>"><i class="glyphicon glyphicon-edit"></i></a>
                        </td>
                    <?php } ?>
                    <?php if (in_array('hrm_doctor_delete', $permissions)) { ?>
                        <td><a onclick="deleteDoctor(<?php echo $doctor_id; ?>, 'doctor-row-<?php echo $doctor_id; ?>')" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
                    <?php } ?>
                </tr>
            <?php
            }
            ?>
        </table>
        <div style="width:70%;margin:0 auto;text-align:center">
            <p><?php echo $pagination; ?></p>
        </div>
    </div>
</div>
