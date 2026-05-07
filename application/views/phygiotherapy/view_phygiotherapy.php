<?php
$permissions = $this->session->userdata('permissions');
?>
<script>
    $(document).ready(function() {
        $('#gender').select2();
        $('#reference_doctor_id').select2();
        $('#reference_media_id').select2();

        $('#phygiotherapy_invoice_no').focus();
    });
    $(document).ready(function() {
        $("#name").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('PhygiotherapyController/name_load'); ?>",
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
                $('#name').val(ui.item.label);
                $('form').submit(); // Automatically submit the form
                return false;
            }
        });
        $("#patient_name").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('PhygiotherapyController/patient_name_load'); ?>",
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
                $('#patient_name').val(ui.item.label);
                $('form').submit(); // Automatically submit the form
                return false;
            }
        });
        $("#phone").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('PhygiotherapyController/phone_load'); ?>",
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
                $('#phone').val(ui.item.label);
                $('form').submit(); // Automatically submit the form
                return false;
            }
        });
        $("#phone").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('PhygiotherapyController/phone_load'); ?>",
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
                $('#phone').val(ui.item.label);
                $('form').submit(); // Automatically submit the form
                return false;
            }
        });
        $("#phygiotherapy_invoice_no").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('PhygiotherapyController/phygiotherapy_invoice_no_load'); ?>",
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
                $('#phygiotherapy_invoice_no').val(ui.item.label);
                $('form').submit(); // Automatically submit the form
                return false;
            }
        });

    });

    function deletePhygiotherapy(phygiotherapy_id) {
        if (confirm('Do you want to delete?')) {
            $.ajax({
                url: "<?php echo site_url('PhygiotherapyController/delete_ipd_phygiotherapy_ajax'); ?>",
                type: 'POST',
                data: {
                    phygiotherapy_id: phygiotherapy_id
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

                        setTimeout(function() {
                            window.location.href = "<?php echo base_url('view-phygiotherapy'); ?>";
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
    }
</script>

<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">View Phygiotherapy</h3>
        </div>
        <div class="panel-body">

            <form method="post" action="<?php echo base_url('view-phygiotherapy') ?>">
                <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                    <tr>
                        <td>Name</td>
                        <td>Phone</td>
                        <td>Invoice No</td>
                        <td>Gender</td>
                        <td>Ref. Doctor</td>
                        <td>Ref. Media</td>
                        <td>From Date</td>
                        <td>To Date</td>
                        <td>Status</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <input placeholder="Enter Name" id="name" name="name" class="form-control">
                        </td>
                        <td>
                            <input placeholder="Enter Phone" id="phone" name="phone" class="form-control">
                        </td>
                        <td>
                            <input placeholder="Scan or Insert Invoice ID.." id="phygiotherapy_invoice_no" name="phygiotherapy_invoice_no" class="form-control">

                        </td>
                        <td>
                            <select id="gender" name="gender" class="form-control">
                                <option selected="" value="">Select Gender</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>

                            </select>
                        </td>
                        <td>
                            <select type="text" class="form-control" id="reference_doctor_id" name="reference_doctor_id">
                                <option selected="" value="">Select Reference Doctor</option>
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
                                <option selected="" value="">Select Reference Media</option>
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
                            <input id="datepicker1" name="from_date" class="form-control">
                        </td>
                        <td>
                            <input id="datepicker2" name="to_date" class="form-control">
                        </td>
                        <td>
                            <select id="status" name="status" class="form-control">
                                <option value="">Select Status</option>
                                <option>Returned</option>
                            </select>

                        </td>
                        <td><input type="submit" value="Submit" class="btn btn-primary"></td>
                    </tr>

                </table>
            </form>
            <?php if (isset($phygiotherapys_data) && is_array($phygiotherapys_data) && !empty($phygiotherapys_data)) : ?>
                <table class="table table-hover table-bordered table-condensed">
                    <tr>
                        <td>#</td>
                        <td>Name</td>
                        <td>Age</td>
                        <td>Phone</td>
                        <!-- <td>Address</td> -->
                        <!-- <td>Attendant</td> -->
                        <td>Invoice No</td>
                        <td>Total</td>
                        <td>Paid</td>
                        <td>Due</td>
                        <td>Date</td>
                        <td>D.C.Date</td>
                        <td>Time</td>
                        <?php
                        $user_type_name = $this->session->userdata('user_type');
                        if ($user_type_name == 'Admin') {
                        ?>
                            <td>User</td>
                        <?php
                        }
                        ?>
                        <?php if (in_array('due_payment_status_physiotherapy', $permissions)) { ?>
                            <td>Payment<br> Status</td>
                        <?php } ?>
                        <?php if (in_array('print_phygiotherapy', $permissions)) { ?>
                            <td>Print</td>
                        <?php } ?>
                        <?php if (in_array('return_phygiotherapy', $permissions)) { ?>
                            <td>Return</td>
                        <?php } ?>
                        <?php if (in_array('edit_phygiotherapy', $permissions)) { ?>
                            <td>Edit</td>
                        <?php } ?>
                        <?php if (in_array('delete_phygiotherapy', $permissions)) { ?>
                            <td>Delete</td>
                        <?php } ?>
                    </tr>
                    <?php

                    $sl = 1;
                    $grand_total = 0;

                    foreach ($phygiotherapys_data as $phygiotherapy) :

                        $doctor = $this->db->where('doctor_id', $phygiotherapy->doctor_id)->get('doctor')->row();
                        $reference_media = $this->db->where('reference_media_id', $phygiotherapy->reference_media_id)->get('reference_media')->row();
                        $phygiotherapy_id = $phygiotherapy->phygiotherapy_id;
                        $user = getUserById($phygiotherapy->user_id);
                    ?>
                        <tr>
                            <td><?php echo $sl++ ?></td>

                            <td><?php echo $phygiotherapy->name ?></td>
                            <td>
                                <b><?php
                                    $age_parts = [];

                                    if ($phygiotherapy->age_year > 0) {
                                        $age_parts[] = $phygiotherapy->age_year . ' ' . ($phygiotherapy->age_year == 1 ? 'Year' : 'Years');
                                    }

                                    if ($phygiotherapy->age_month > 0) {
                                        $age_parts[] = $phygiotherapy->age_month . ' ' . ($phygiotherapy->age_month == 1 ? 'Month' : 'Months');
                                    }

                                    if ($phygiotherapy->age_day > 0) {
                                        $age_parts[] = $phygiotherapy->age_day . ' ' . ($phygiotherapy->age_day == 1 ? 'Day' : 'Days');
                                    }

                                    echo implode(' ', $age_parts);
                                    ?></b>
                            </td>
                            <td><?php echo $phygiotherapy->phone ?></td>
                            <!-- <td><?php //echo $phygiotherapy->address ?></td>
                            <td><?php //echo $phygiotherapy->attendant ?></td> -->
                            <td><?php echo $phygiotherapy->phygiotherapy_invoice_no ?></td>
                            <td><?php echo $phygiotherapy->total ?></td>
                            <td><?php echo (float)$phygiotherapy->paid+(float)$phygiotherapy->due_payment ?><br><b><?php echo $phygiotherapy->status; ?></b></td>
                            <td><?php echo $phygiotherapy->due ?></td>
                            <td><?php echo $phygiotherapy->date ? date('d-m-Y', strtotime($phygiotherapy->date)) : '' ?></td>
                            <td><?php echo $phygiotherapy->due_payment_date ? date('d-m-Y', strtotime($phygiotherapy->due_payment_date)) : '' ?></td>
                            <td><?php echo $phygiotherapy->physiotherapy_time ?></td>
                            <?php
                            $user_type_name = $this->session->userdata('user_type');
                            if ($user_type_name == 'Admin') {
                            ?>
                                <td><?php echo $user->user_name ?? "" ?> </td>
                            <?php
                            }
                            ?>
                            <?php if (in_array('due_payment_status_physiotherapy', $permissions)) : ?>
                                <?php if ($phygiotherapy->due != 0) : ?>
                                    <td>
                                        <a class="btn btn-primary" href="<?php echo base_url("physiotherapy-due-payment/$phygiotherapy_id") ?>">Pay Now</a>
                                    </td>
                                <?php else : ?>
                                    <td>
                                        Paid
                                    </td>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if (in_array('print_phygiotherapy', $permissions)) { ?>

                                <td>
                                    <a class="btn btn-primary" href="<?php echo base_url("print-phygiotherapy/$phygiotherapy_id") ?>"><i class="glyphicon glyphicon-print"></i></a>
                                </td>
                            <?php } ?>
                            <?php if (in_array('return_phygiotherapy', $permissions)) { ?>
                                <td>
                                    <a class="btn btn-warning" title="Sale Return" href="<?php echo base_url("return-phygiotherapy/$phygiotherapy_id") ?>"><i class="glyphicon glyphicon-repeat" aria-hidden="true"></i>
                                    </a>
                                </td>
                            <?php } ?>
                            <?php if (in_array('edit_phygiotherapy', $permissions)) { ?>
                                <td>
                                    <a class="btn btn-primary" href="<?php echo base_url("edit-phygiotherapy/$phygiotherapy_id") ?>"><i class="glyphicon glyphicon-edit"></i></a>
                                </td>
                            <?php } ?>
                            <?php if (in_array('delete_phygiotherapy', $permissions)) { ?>
                                <td><a onclick="deletePhygiotherapy(<?php echo $phygiotherapy_id; ?>)" class="btn btn-success"><i class="glyphicon glyphicon-trash"></i></a></td>
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
</div>