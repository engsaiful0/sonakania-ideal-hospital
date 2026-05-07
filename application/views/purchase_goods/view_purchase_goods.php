<script>
    $(document).ready(function() {
        $('#issue_no').select2();
        $('#supplier_id').select2();
    });
</script>
<?php
error_reporting(0);
$permissions = $this->session->userdata('permissions');
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#purchase_goods_invoice_no").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('PurchaseGoodsController/purchase_goods_invoice_no_load'); ?>",
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
                $('#purchase_goods_invoice_no').val(ui.item.label);
                $('form').submit();
                return false;
            }
        });
    });

    function deletePurchaseGoods(purchase_goods_id, row_id) {
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
                    url: "<?php echo site_url('PurchaseGoodsController/purchase_goods_delete_ajax'); ?>",
                    type: 'POST',
                    data: {
                        purchase_goods_id: purchase_goods_id
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
            <h3 style="text-align: center">View Purchase Goods</h3>
        </div>
        <div class="panel-body">

            <form method="post" action="<?php echo base_url('view-purchase-goods') ?>">
                <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                    <tr>
                        <td>Supplier</td>
                        <td>Invoice No</td>
                        <td>From Date</td>
                        <td>To Date</td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>
                            <select id="supplier_id" name="supplier_id" class="form-control">
                                <option value="" disabled="" selected="">Select Supplier </option>
                                <?php
                                $sql = $this->db->select('*')->order_by('name', 'ASC')->get('supplier')->result();

                                foreach ($sql as $value) {
                                ?>
                                    <option value="<?php echo $value->supplier_id ?>"><?php echo $value->name ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <input placeholder="Enter any part of invoice" type="text" id="purchase_goods_invoice_no" name="purchase_goods_invoice_no" class="form-control" />
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
            <table class="table table-hover table-bordered table-condensed">
                <tr>
                    <td>#</td>
                    <td>Supplier</td>
                    <td>Purpose</td>
                    <td>Invoice No</td>
                    <td>Total</td>
                    <td>Discount</td>
                    <td>Total Discount</td>
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
                    <?php if (in_array('account_purchase_print', $permissions)) { ?>
                        <td>Print</td>
                    <?php } ?>
                    <?php if (in_array('account_purchase_edit', $permissions)) { ?>
                        <td>Edit</td>
                    <?php } ?>
                    <?php if (in_array('account_purchase_delete', $permissions)) { ?>
                        <td>Delete</td>
                    <?php } ?>
                </tr>
                <?php

                $sl = 1;
                $grand_total = 0;
                foreach ($purchase_goods_data as $data) :
                    $supplier = getSupplier($data->supplier_id);
                    $purchase_goods_id = $data->purchase_goods_id;
                    $user = getUserById($data->user_id);
                ?>
                    <tr id="goods-row-<?php echo $purchase_goods_id; ?>">
                        <td><?php echo $sl++ ?></td>
                        <td><?php echo $supplier->name ?></td>
                        <td><?php echo $data->purpose ?></td>
                        <td><?php echo $data->purchase_goods_invoice_no ?></td>
                        <td><?php echo $data->total ?></td>
                        <td><?php echo $data->discount ?></td>
                        <td><?php echo $data->total_discount ?></td>
                        <td><?php echo $data->paid ?></td>
                        <td><?php echo $data->due ?></td>
                        <td><?php echo date('d-m-Y', strtotime($data->date)) ?></td>
                        <td><?php echo $user->user_name ?? "" ?> </td>
                        <?php if (in_array('account_purchase_print', $permissions)) { ?>
                            <td>
                                <a class="btn btn-primary" href="<?php echo base_url("print-purchase-goods/$purchase_goods_id") ?>"><i class="glyphicon glyphicon-print"></i></a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('account_purchase_edit', $permissions)) { ?>
                            <td>
                                <a class="btn btn-primary" href="<?php echo base_url("edit-purchase-goods/$purchase_goods_id") ?>"><i class="glyphicon glyphicon-edit"></i></a>

                            </td>
                        <?php } ?>
                        <?php if (in_array('account_purchase_delete', $permissions)) { ?>
                            <td><a onclick="deletePurchaseGoods(<?php echo $purchase_goods_id; ?>, 'goods-row-<?php echo $purchase_goods_id; ?>')" href="#" class="btn btn-success"><i class="glyphicon glyphicon-trash"></i></a></td>
                        <?php } ?>

                    </tr>
                <?php endforeach; ?>

            </table>


            <div style="width:70%;margin:0 auto;text-align:center">
                <p><?php echo $pagination; ?></p>
            </div>

        </div>
    </div>
</div>