<?php
$permissions = $this->session->userdata('permissions');
?>
<script>
    $(document).ready(function() {

        $('#surgery_id').select2();
        $('#patient_unique_id').focus();
    });


    $(document).ready(function() {
        $("#patient_unique_id").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('IpdPatientController/patient_unique_id_load'); ?>",
                    data: {
                        parameter: request.term
                    },
                    dataType: "json",
                    type: "POST",
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                $('#patient_unique_id').val(ui.item.label);
                $('form').submit(); // Automatically submit the form
                return false;
            }
        });
        $("#ot_service_unique_id").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('OTServiceController/ot_service_unique_id_load'); ?>",
                    data: {
                        parameter: request.term
                    },
                    dataType: "json",
                    type: "POST",
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                $('#ot_service_unique_id').val(ui.item.label);
                $('form').submit(); // Automatically submit the form
                return false;
            }
        });
        
    });
</script>
<script>
    function deleteOTService(ot_service_id, row_id) {
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
                    url: "<?php echo site_url('OTServiceController/delete_ot_service_ajax'); ?>",
                    type: 'POST',
                    data: {
                        ot_service_id: ot_service_id
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
                            setTimeout(function() {
                                //  window.location.href = "<?php echo base_url('view-ot-service'); ?>";
                            }, 1005);
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
    $(document).ready(function() {
        $('.view-ot-service').on('click', function() {
            var oTServiceId = $(this).data('id');

            $.ajax({
                url: "<?php echo base_url('get-ot-service-details'); ?>",
                type: 'POST',
                data: {
                    ot_service_id: oTServiceId
                },
                success: function(response) {
                    $('#ipdServiceModal .modal-body').html(response);
                    $('#ipdServiceModal').modal('show');
                }
            });
        });
    });
</script>

<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">View OT Service</h3>
        </div>
        <div class="panel-body">
            <div class="col-md-12">


            </div>
        </div>
        <?php if (in_array('search_ot_service', $permissions)) { ?>
            <form method="post" action="<?php echo base_url('view-ot-service') ?>">
                <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                    <tr>
                        <td>Patient ID</td>
                        <td>Invoice ID</td>
                        <td>Surgery</td>
                        <td>From Date</td>
                        <td>To Date</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <input placeholder="Scan or Enter any part of Patient ID .." type="text" id="patient_unique_id" name="patient_unique_id" class="form-control" />
                        </td>
                        <td>
                            <input placeholder="Scan or Enter any part of Invoice ID .." type="text" id="ot_service_unique_id" name="ot_service_unique_id" class="form-control" />
                        </td>
                        <td>
                            <select name="surgery_id" class="form-control" id="surgery_id">
                                <option selected="" value="" disabled="">Select Surgery Name</option>
                                <?php
                                $surgeries = $this->db->select('*')->get('surgeries')->result();

                                foreach ($surgeries as $surgery) {
                                ?>
                                    <option value="<?php echo $surgery->surgery_id; ?>"><?php echo $surgery->name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <input id="datepicker1" placeholder="Select from date" name="from_date" class="form-control">
                        </td>
                        <td>
                            <input id="datepicker2" placeholder="Select to date" name="to_date" class="form-control">
                        </td>
                        <td><input type="submit" value="Submit" class="btn btn-primary"></td>
                    </tr>

                </table>
            </form>
        <?php } ?>
        <?php if (isset($ipd_service_data) && is_array($ipd_service_data) && !empty($ipd_service_data)) : ?>
            <table class="table table-hover table-bordered table-condensed">
                <tr>
                    <td>#</td>
                    <td>Patient</td>
                    <td>Invoice ID</td>
                    <td>Surgery</td>
                    <td>Total</td>
                    <td>Discount</td>
                    <td>Net Total</td>
                    <td>Paid</td>
                    <td>Due</td>
                    <td>Date</td>
                    <td>Time</td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td>User</td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('due_payment_ot_service', $permissions)) { ?>
                        <td>Payment<br> Status</td>
                    <?php } ?>
                    <?php if (in_array('view_ot_service', $permissions)) { ?>
                        <td>Print</td>
                    <?php } ?>
                    <?php if (in_array('edit_ot_service', $permissions)) { ?>
                        <td>Edit</td>
                    <?php } ?>
                    <?php if (in_array('delete_ot_service', $permissions)) { ?>
                        <td>Delete</td>
                    <?php } ?>
                </tr>
                <?php

                $sl = 1;
                $grand_total = 0;
                error_reporting(0);
                foreach ($ipd_service_data as $data) :

                    $patient = $this->db->where('ipd_patient_id', $data->ipd_patient_id)->get('ipd_patient')->row();
                    $surgery = $this->db->where('surgery_id', $data->surgery_id)->get('surgeries')->row();
                    $ot_service_id = $data->ot_service_id;
                    $user = getUserById($data->user_id);
                ?>
                    <tr id="ot-service-row-<?php echo $ot_service_id; ?>">
                        <td><?php echo $sl++ ?></td>
                        <td><?php echo $data->patient_name ?></td>

                        <td><?php echo $data->ot_service_unique_id ?></td>
                        <td><?php echo $surgery->name ?></td>
                        <td><?php echo $data->price ?></td>
                        <td><?php echo $data->total_discount ?></td>
                        <td><?php echo $data->net_price ?></td>
                        <td><?php echo (float)$data->paid+(float)$data->due_payment ?></td>
                        <td><?php echo $data->due ?></td>
                        <td><?php echo date('d-m-Y', strtotime($data->date)) ?></td>
                        <td><?php echo $data->time ?></td>
                        <?php
                        $user_type_name = $this->session->userdata('user_type');
                        if ($user_type_name == 'Admin') {
                        ?>
                            <td><?php echo $user->user_name ?? "" ?> </td>
                        <?php
                        }
                        ?>
                        <?php if (in_array('due_payment_ot_service', $permissions)) : ?>
                            <?php if ($data->due != 0) : ?>
                                <td>
                                    <a class="btn btn-primary" href="<?php echo base_url("ot-service-due-payment/$ot_service_id") ?>">Pay Now</a>
                                </td>
                            <?php else : ?>
                                <td>
                                    Paid
                                </td>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (in_array('view_ot_service', $permissions)) { ?>
                            <td>
                                <a class="btn btn-primary" href="<?php echo base_url("print-ot-service-by-id/$ot_service_id") ?>"><i class="glyphicon glyphicon-print"></i></a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('edit_ot_service', $permissions)) { ?>
                            <td>
                                <a class="btn btn-primary" href="<?php echo base_url("edit-ot-service/$ot_service_id") ?>"><i class="glyphicon glyphicon-edit"></i></a>
                            </td> <?php } ?>
                        <?php if (in_array('delete_ot_service', $permissions)) { ?>
                            <td><a onclick="deleteOTService(<?php echo $ot_service_id; ?>, 'ot-service-row-<?php echo $ot_service_id; ?>')" class="btn btn-success"><i class="glyphicon glyphicon-trash"></i></a></td>
                        <?php } ?>

                    </tr>
                <?php endforeach; ?>


            </table>


            <div style="width:70%;margin:0 auto;text-align:center">
                <p><?php echo $pagination; ?></p>
            </div>
        <?php else : ?>
            <p style="text-align:center">No records found.</p>
        <?php endif; ?>
    </div>
</div>
<div class="modal fade" id="ipdServiceModal" tabindex="-1" role="dialog" aria-labelledby="ipdServiceModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="ipdServiceModalLabel">OT Service Details</h4>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>