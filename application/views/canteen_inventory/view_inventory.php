<?php
$permissions = $this->session->userdata('permissions');
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#canteen_ready_item_inventory_invoice_no").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('CanteenReadyItemInventoryController/canteen_ready_item_inventory_invoice_no_load'); ?>",
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
                $('#canteen_ready_item_inventory_invoice_no').val(ui.item.label);
                $('form').submit();
                return false;
            }
        });
    });

    function deleteCanteenReadyItemInventory(canteen_ready_item_inventory_id, row_id) {
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
                    url: "<?php echo site_url('CanteenReadyItemInventoryController/cantenn_ready_item_inventory_delete_ajax'); ?>",
                    type: 'POST',
                    data: {
                        canteen_ready_item_inventory_id: canteen_ready_item_inventory_id
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
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">View Ready Item Inventory</h3>
        </div>
        <div class="panel-body">

            <form method="post" action="<?php echo base_url('view-inventory') ?>">
                <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                    <tr>
                        <td>Invoice No</td>
                        <td>From Date</td>
                        <td>To Date</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <input placeholder="Enter any part of invoice" type="text" id="canteen_ready_item_inventory_invoice_no" name="canteen_ready_item_sell_invoice_no" class="form-control" />
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
            <?php if (isset($canteen_ready_item_inventory_data) && is_array($canteen_ready_item_inventory_data) && !empty($canteen_ready_item_inventory_data)) : ?>
                <table class="table table-hover table-bordered table-condensed">
                    <tr>
                        <td>#</td>
                        <td>Purpose</td>
                        <td>Invoice No</td>
                        <td>Total Amount</td>
                        <td>Total Quantity</td>
                        <td>Date</td>
                        <?php
                        $user_type_name = $this->session->userdata('user_type');
                        if ($user_type_name == 'Admin') {
                        ?>
                            <td>User</td>
                        <?php
                        }
                        ?>
                        <?php if (in_array('canteen_inventory_ready_item_print', $permissions)) { ?>
                            <td>Print</td>
                        <?php } ?>
                        <?php if (in_array('canteen_inventory_ready_item_edit', $permissions)) { ?>
                            <td>Edit</td>
                        <?php } ?>
                        <?php if (in_array('canteen_inventory_ready_item_delete', $permissions)) { ?>
                            <td>Delete</td>
                        <?php } ?>
                    </tr>
                    <?php

                    $sl = 1;
                    $grand_total = 0;
                    foreach ($canteen_ready_item_inventory_data as $data) :
                        $canteen_ready_item_inventory_id   = $data->canteen_ready_item_inventory_id;
                        $user = getUserById($data->user_id);
                    ?>
                        <tr id="goods-row-<?php echo $canteen_ready_item_inventory_id; ?>">
                            <td><?php echo $sl++ ?></td>
                            <td><?php echo $data->purpose ?></td>
                            <td><?php echo $data->canteen_ready_item_inventory_invoice_no ?></td>
                            <td><?php echo $data->grand_total_amount ?></td>
                            <td><?php echo $data->grand_total_quantity ?></td>
                            <td><?php echo date('d-m-Y', strtotime($data->date)) ?></td>
                            <?php
                            $user_type_name = $this->session->userdata('user_type');
                            if ($user_type_name == 'Admin') {
                            ?>
                                <td><?php echo $user->user_name ?? "" ?> </td>
                            <?php
                            }
                            ?>
                            <?php if (in_array('canteen_inventory_ready_item_print', $permissions)) { ?>
                                <td>
                                    <a class="btn btn-primary" href="<?php echo base_url("print-canteen-ready-item-inventory-with-id/$canteen_ready_item_inventory_id") ?>"><i class="glyphicon glyphicon-print"></i></a>
                                </td>
                            <?php } ?>
                            <?php if (in_array('canteen_inventory_ready_item_edit', $permissions)) { ?>
                                <td>
                                    <a class="btn btn-primary" href="<?php echo base_url("edit-canteen-ready-item-inventory/$canteen_ready_item_inventory_id") ?>"><i class="glyphicon glyphicon-edit"></i></a>
                                </td>
                            <?php } ?>
                            <?php if (in_array('canteen_inventory_ready_item_delete', $permissions)) { ?>
                                <td><a onclick="deleteCanteenReadyItemInventory(<?php echo $canteen_ready_item_inventory_id; ?>, 'goods-row-<?php echo $canteen_ready_item_inventory_id; ?>')" href="#" class="btn btn-success"><i class="glyphicon glyphicon-trash"></i></a></td>
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