<?php
$permissions = $this->session->userdata('permissions');
?>
<script>
    $(document).ready(function() {
        $('#invoice_no').focus();
        $("#invoice_no").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('TestController/invoice_no_load'); ?>",
                    data: {
                        parameter: request.term
                    },
                    dataType: "json",
                    type: "POST",
                    success: function(data) {
                        response(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error fetching data: " + textStatus);
                    }
                });
            },
            select: function(event, ui) {
                $('#invoice_no').val(ui.item.label);
                $('form').submit();
                return false;
            }
        });

        $("#patient_name").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('TestController/patient_name_load'); ?>",
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
                $('form').submit();
                return false;
            }
        });
        $("#mobile").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('TestController/mobile_load'); ?>",
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
                $('#mobile').val(ui.item.label);
                $('form').submit();
                return false;
            }
        });
    });

    function deleteTestEntry(patient_test_entry_id, row_id) {
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
                    url: "<?php echo site_url('TestController/delete_test_entry_ajax'); ?>",
                    type: 'POST',
                    data: {
                        patient_test_entry_id: patient_test_entry_id
                    },
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status == 'success') {
                            $.toast({
                                heading: 'Success',
                                text: res.message,
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 2000,
                                icon: 'success'
                            });
                            // Remove the specific row from the table
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
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Test View</h3>
    </div>
    <div class="panel-body" style="width: 100%;">
        <?php if (in_array('test_search', $permissions)) { ?>
            <form method="post" action="<?php echo base_url('view-test-entry') ?>">
                <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 90%;">
                    <tr>
                        <td>Patient Name</td>
                        <td>Mobile</td>
                        <td>Invoice No</td>
                        <td>From Date</td>
                        <td>To Date</td>
                        <td>Status</td>
                    </tr>
                    <tr>
                        <td>
                            <input placeholder="Part of name" type="text" id="patient_name" name="patient_name" class="form-control" />

                        </td>

                        <td>
                            <input placeholder="Part of mobile" type="text" id="mobile" name="mobile" class="form-control" />

                        </td>
                        <td>
                            <input placeholder="Scan of Enter invoice no.." type="text" id="invoice_no" name="invoice_no" class="form-control" />
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
                        <td><input type="submit" id="sumbit_button" value="Search" class="btn btn-primary"></td>
                    </tr>
                </table>
            </form>
        <?php } ?>
        <?php if (isset($detailsList) && is_array($detailsList) && !empty($detailsList)) : ?>
            <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                <tr>
                    <td>Sl</td>
                    <td>Patient Name</td>
                    <!-- <td>Mobile</td> -->
                    <!-- <td>Age</td> -->
                    <!-- <td>Gender</td> -->
                    <td>Invoice No</td>
                    <td>Ref.Doctor</td>
                    <td>Sub Total</td>
                    <td>Total Discount</td>
                    <td>Dis. Ref</td>
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
                    <?php if (in_array('test_print', $permissions)) { ?>
                        <td>Print</td>
                    <?php } ?>
                    <?php if (in_array('test_return', $permissions)) { ?>
                        <td>
                            Return
                        </td>
                    <?php } ?>
                    <?php if (in_array('test_edit', $permissions)) { ?>
                        <td>Edit</td>
                    <?php } ?>
                    <?php if (in_array('test_delete', $permissions)) { ?>
                        <td>Delete</td>
                    <?php } ?>

                </tr>
                <?php
                error_reporting(0);
                //  print_r( count($detailsList));

                $sl = 1;
                for ($i = 0; $i < count($detailsList); ++$i) {
                    $patient_test_entry_id = $detailsList[$i]->patient_test_entry_id;
                    $ref_doctor = $this->db->where('doctor_id', $detailsList[$i]->reference_doctor_id)->get('doctor')->row();
                    $user = getUserById($detailsList[$i]->user_id);

                    $this->db->select_sum('paid');
                    $this->db->from('test_collection');
                    $this->db->where('patient_test_entry_id', $detailsList[$i]->patient_test_entry_id); // Filter by today's date
                    $query = $this->db->get();
                    $total_paid_of_this_test = $query->row()->paid; // Return 0 if no income found
                ?>
                    <tr id="test-row-<?php echo $patient_test_entry_id; ?>">
                        <td><?php
                            echo $sl++;
                            ?></td>
                        <td><?php echo $detailsList[$i]->patient_name ?></td>

                        <!-- <td> <?php echo $detailsList[$i]->mobile_number ?></td> -->
                        <!-- <td><?php echo $detailsList[$i]->age ?></td> -->
                        <!-- <td><?php echo $detailsList[$i]->gender ?> -->
                        </td>

                        <td><?php echo $detailsList[$i]->invoice_no ?><br><b><?php echo $detailsList[$i]->status; ?></b>
                        </td>
                        <td><?php echo $ref_doctor->doctor_name . '-' . $ref_doctor->doctor_unique_id ?>
                        </td>
                        <td><?php echo $detailsList[$i]->sub_total ?></td>
                        <td><?php echo $detailsList[$i]->total_discount ?></td>
                        <td><?php echo $detailsList[$i]->discount_reference ?></td>
                        <td><?php echo $detailsList[$i]->net_total ?></td>
                        <td><?php echo $total_paid_of_this_test ?></td>
                        <td><?php echo $detailsList[$i]->net_total - $total_paid_of_this_test ?></td>
                        <td><?php echo date('d-m-Y', strtotime($detailsList[$i]->date)) ?></td>
                            <td><?php echo $detailsList[$i]->time ?></td>
                        <?php
                        $user_type_name = $this->session->userdata('user_type');
                        if ($user_type_name == 'Admin') {
                        ?>
                            <td><?php echo $user->user_name ?? "" ?> </td>
                        <?php
                        }
                        ?>
                        <?php if (in_array('test_print', $permissions)) { ?>
                            <td>
                                <a href="<?php echo base_url("print-test-entry-with-id/$patient_test_entry_id") ?>" class="btn btn-primary">
                                    <i class="glyphicon glyphicon-print" aria-hidden="true"></i>
                                </a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('test_return', $permissions)) { ?>
                            <td>
                                <a class="btn btn-warning" title="Sale Return" href="<?php echo base_url("return-test-entry/$patient_test_entry_id") ?>"><i class="glyphicon glyphicon-repeat" aria-hidden="true"></i>
                                </a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('test_edit', $permissions)) { ?>
                            <td>
                                <a href="<?php echo base_url("edit-test-entry/$patient_test_entry_id") ?>" class="btn btn-primary">
                                    <i class="glyphicon glyphicon-edit" aria-hidden="true"></i>
                                </a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('test_delete', $permissions)) { ?>
                            <td>
                                <a onclick="deleteTestEntry(<?php echo $patient_test_entry_id; ?>, 'test-row-<?php echo $patient_test_entry_id; ?>')" class="btn btn-success">
                                    <i class="glyphicon glyphicon-trash" aria-hidden="true"></i>
                                </a>
                            </td>
                        <?php } ?>
                    </tr>
                <?php
                }
                ?>
            </table>
            <div style="width:70%;margin:0 auto;text-align:center">
                <p><?php echo $pagination; ?></p>
            </div>
        <?php else : ?>
            <p style="text-align:center">No records found.</p>
        <?php endif; ?>
    </div>
</div>
