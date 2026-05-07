<?php
$permissions = $this->session->userdata('permissions');
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">View Medicine Purchase </h3>
    </div>

    <div class="panel-body" style="width: 100%;">
        <script>
            $(document).ready(function() {
                $("#supplier_id").select2();
                $('#medicine_purchase_invoice_no').focus();
            });

            $(document).ready(function() {
                $("#medicine_purchase_invoice_no").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('MedicinePurchaseController/medicine_purchase_invoice_no_load'); ?>",
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
                        $('#medicine_purchase_invoice_no').val(ui.item.label);
                        $('form').submit(); // Automatically submit the form
                        return false;
                    }
                });
            });

            function deleteMedicinePurchase(medicine_purchase_id, row_id) {
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
                            url: "<?php echo site_url('MedicinePurchaseController/medicine_purchase_delete_ajax'); ?>",
                            type: 'POST',
                            data: {
                                medicine_purchase_id: medicine_purchase_id
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

        <?php if (in_array('pharmacy_search_medicine_purchase', $permissions)) { ?>
            <form method="post" action="<?php echo base_url() . "index.php/MedicinePurchaseController/view_medicine_purchase"; ?>">
                <table style="width: 100%;" class="table table-hover table-bordered table-condensed"> 
                    <tr>
                        <td>Supplier</td>
                        <td>Invoice No</td>
                        <td>From Date</td>
                        <td>To Date</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <select name="supplier_id" id="supplier_id" class="form-control">
                                <option value="">Supplier</option>
                                <?php
                                $sql = $this->db->where('type','medicine_supplier')->order_by('name', 'ASC')->get('supplier')->result();

                                foreach ($sql as $value) {
                                ?>
                                    <option value="<?php echo $value->supplier_id ?>"><?php echo $value->name ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <input placeholder="Scan or Enter Invoice ID .." type="text" id="medicine_purchase_invoice_no" name="medicine_purchase_invoice_no" class="form-control" />
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
            <table style="width: 100%;" class="table table-hover table-bordered table-condensed">
                <tr>
                    <td>#</td>
                    <td>Supplier</td>
                    <td>Invoice No</td>
                    <!-- <td>Status</td> -->
                    <td>Total</td>
                    <!-- <td>Discount</td> -->
                    <td>Net Total</td>
                    <td>Paid</td>
                    <td>Due</td>
                    <td>Date</td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td>User</td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('pharmacy_return_medicine_purchase', $permissions)) { ?>
                        <td>Return</td>
                    <?php } ?>
                    <?php if (in_array('pharmacy_print_medicine_purchase', $permissions)) { ?>
                        <td>Print</td>
                    <?php } ?>
                    <?php if (in_array('pharmacy_edit_medicine_purchase', $permissions)) { ?>
                        <td>Edit</td>
                    <?php } ?>
                    <?php if (in_array('pharmacy_delete_medicine_purchase', $permissions)) { ?>
                        <td>Delete</td>
                    <?php } ?>

                </tr>
                <?php
                error_reporting(0);
                $sl = 1;
                for ($i = 0; $i < count($detailsList); ++$i) {
                    $medicine_purchase_id = $detailsList[$i]->medicine_purchase_id;
                    $supplier = $this->db->where('supplier_id', $detailsList[$i]->supplier_id)->get('supplier')->row();
                    $user = getUserById($detailsList[$i]->user_id);
                ?>
                    <tr id="medicine-purchase-row-<?php echo $medicine_purchase_id; ?>">
                        <td><?php echo $sl++ ?></td>
                        <td><?php if ($supplier->name != ''): ?>
                                <?= $supplier->name ?>
                            <?php endif; ?></td>
                        <td><?php echo $detailsList[$i]->medicine_purchase_invoice_no ?? ''; ?></td>
                        <!-- <td><?php echo $detailsList[$i]->status ?? ''; ?></td> -->
                        <td><?php echo $detailsList[$i]->total ?? ''; ?></td>
                        <!-- <td><?php echo $detailsList[$i]->discount ?? ''; ?></td> -->
                        <td><?php echo $detailsList[$i]->nettotal ?? ''; ?></td>
                        <td><?php echo $detailsList[$i]->paid ?? ''; ?></td>
                        <td><?php echo $detailsList[$i]->due ?? ''; ?></td>

                        <td><?php echo date('d-m-Y', strtotime($detailsList[$i]->date)) ?></td>
                        <td><?php echo $user->user_name ?? "" ?> </td>
                        <?php if (in_array('pharmacy_return_medicine_purchase', $permissions)) { ?>
                            <td>
                                <a class="btn btn-warning" title="Sale Return" href="<?php echo base_url("medicine-purchase-return/$medicine_purchase_id") ?>"><i class="glyphicon glyphicon-repeat" aria-hidden="true"></i>
                                </a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('pharmacy_print_medicine_purchase', $permissions)) { ?>
                            <td><a class="btn btn-danger" href="<?php echo base_url("print-medicine-purchase-again/$medicine_purchase_id") ?>"><i class="glyphicon glyphicon-print" aria-hidden="true"></i>
                                </a></td>
                        <?php } ?>
                        <?php if (in_array('pharmacy_edit_medicine_purchase', $permissions)) { ?>
                            <td>
                                <a id="medicinsell_id_<?php echo $detailsList[$i]->medicine_purchase_id ?>" class="btn btn-primary" href="<?php echo base_url() ?>medicine-purchase-edit/<?php echo $detailsList[$i]->medicine_purchase_id ?>"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('pharmacy_delete_medicine_purchase', $permissions)) { ?>
                            <td><a onclick="deleteMedicinePurchase(<?php echo $medicine_purchase_id; ?>, 'medicine-purchase-row-<?php echo $medicine_purchase_id; ?>')" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
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