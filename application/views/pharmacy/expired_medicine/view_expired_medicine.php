<?php
$permissions = $this->session->userdata('permissions');
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">View Expired Medicine </h3>
    </div>

    <div class="panel-body" style="width: 100%;">
        <script>
            $(document).ready(function() {

                $('#expired_medicine_invoice_no').focus();
            });

            $(document).ready(function() {
                $("#expired_medicine_invoice_no").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('ExpiredMedicineController/expired_medicine_invoice_no_load'); ?>",
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
                        $('#expired_medicine_invoice_no').val(ui.item.label);
                        $('form').submit(); // Automatically submit the form
                        return false;
                    }
                });
            });

            function deleteExpiredMedicine(expired_medicine_id, row_id) {
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
                            url: "<?php echo site_url('ExpiredMedicineController/medicine_purchase_delete_ajax'); ?>",
                            type: 'POST',
                            data: {
                                expired_medicine_id: expired_medicine_id
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
        <?php if (in_array('pharmacny_expired_medicine_search', $permissions)) { ?>
            <form method="post" action="<?php echo base_url() . "index.php/ExpiredMedicineController/view_expired_medicine"; ?>">
                <table>
                    <tr>
                        <td>Invoice No</td>
                        <td>From Date</td>
                        <td>To Date</td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>
                            <input placeholder="Scan or Enter Invoice ID .." type="text" id="expired_medicine_invoice_no" name="expired_medicine_invoice_no" class="form-control" />
                        </td>
                        <td>
                            <input id="datepicker1" placeholder="Select from date" name="from_date" class="form-control">
                        </td>
                        <td>
                            <input id="datepicker2" placeholder="Select to date" name="to_date" class="form-control">
                        </td>
                        <td><input type="submit" class="btn btn-primary" value="Search"></td>
                    </tr>

                </table>
            </form>
        <?php } ?>
        <?php if (isset($detailsList) && is_array($detailsList) && !empty($detailsList)) : ?>
            <table class="table table-hover table-bordered table-condensed">
                <tr>
                    <td>#</td>

                    <td>Invoice No</td>
                    <td>Total</td>
                    <td>Date</td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td>User</td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('pharmacny_expired_medicine_print', $permissions)) { ?>
                        <td>Print</td>
                    <?php } ?>
                    <?php if (in_array('pharmacny_expired_medicine_edit', $permissions)) { ?>
                        <td>Edit</td>
                    <?php } ?>
                    <?php if (in_array('pharmacny_expired_medicine_delete', $permissions)) { ?>
                        <td>Delete</td>
                    <?php } ?>
                </tr>
                <?php
                $sl = 1;
                for ($i = 0; $i < count($detailsList); ++$i) {
                    $expired_medicine_id  = $detailsList[$i]->expired_medicine_id;
                    $user = getUserById($detailsList[$i]->user_id);
                ?>
                    <tr id="expired-medicine-row-<?php echo $expired_medicine_id; ?>">
                        <td><?php echo $sl++ ?></td>

                        <td><?php echo $detailsList[$i]->expired_medicine_invoice_no ?></td>
                        <td><?php echo $detailsList[$i]->total ?></td>
                        <td><?php echo date('d-m-Y', strtotime($detailsList[$i]->date)) ?></td>
                        <td><?php echo $user->user_name ?? "" ?> </td>
                        <?php if (in_array('pharmacny_expired_medicine_print', $permissions)) { ?>
                            <td><a class="btn btn-danger" href="<?php echo base_url("print-expired-medicine-again/$expired_medicine_id") ?>"><i class="glyphicon glyphicon-print" aria-hidden="true"></i>
                                </a></td>
                        <?php } ?>
                        <?php if (in_array('pharmacny_expired_medicine_edit', $permissions)) { ?>
                            <td>
                                <a id="expired_medicine_id_<?php echo $detailsList[$i]->expired_medicine_id ?>" class="btn btn-primary" href="<?php echo base_url() ?>expired-medicine-edit/<?php echo $detailsList[$i]->expired_medicine_id ?>"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('pharmacny_expired_medicine_delete', $permissions)) { ?>
                            <td><a onclick="deleteExpiredMedicine(<?php echo $expired_medicine_id; ?>, 'expired-medicine-row-<?php echo $expired_medicine_id; ?>')" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
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