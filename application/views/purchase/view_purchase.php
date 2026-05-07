<script>
    $(document).ready(function() {
        $('#mrr_no').select2();
    });
</script>
<?php
$permissions = $this->session->userdata('permissions');
?>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">View Purchase</h3>
        </div>
        <div class="panel-body">
            <div class="container" style="width: 100%">
                <div class="row">
                    <div class="panel panel-primary">
                        <form method="post" action="<?php echo site_url('PurchaseController/view_purchase') ?>">
                            <table>
                                <tr>
                                    <td>Mrr No</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="width: 300px;">
                                        <select type="text" required="" class="form-control" id="mrr_no" name="mrr_no">
                                            <option selected="" value="" disabled="">Mrr No</option>
                                            <?php
                                            $purchase = $this->db->where('is_deleted', '0')->get('purchase')->result();
                                            foreach ($purchase as $value) {
                                            ?>
                                                <option value="<?php echo $value->mrr; ?>"><?php echo $value->mrr; ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </td>


                                    <td><input type="submit" class="btn btn-primary" value="Search"></td>
                                </tr>
                            </table>
                        </form>

                        <table class="table table-hover table-bordered table-condensed">
                            <tr>
                                <td>#</td>

                                <td>Supplier</td>

                                <td>Purchase Id</td>
                                <td>Total</td>
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
                            //print_r( count($detailsList));
                            $sl = 1;
                            for ($i = 0; $i < count($detailsList); ++$i) {
                                $purchase_id = $detailsList[$i]->purchase_id;
                                $supplier = $this->db
                                    ->where('supplier_id', $detailsList[$i]->supplier)
                                    ->get('supplier')->row();
                                //   print_r($category);
                                $purchase_id = $detailsList[$i]->purchase_id;
                            ?>
                                <tr>
                                    <td><?php echo $sl++ ?></td>
                                    <td><?php echo $supplier->name ?></td>
                                    <td><?php echo $detailsList[$i]->mrr ?></td>
                                    <td><?php echo $detailsList[$i]->total ?></td>
                                    <td><?php echo $detailsList[$i]->paid ?></td>
                                    <td><?php echo $detailsList[$i]->due ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($detailsList[$i]->invoice_date)) ?></td>

                                    <td>

                                        <a id="purchase_id_<?php echo $detailsList[$i]->purchase_id ?>" href="<?php echo site_url("PurchaseController/purchase_edit/$purchase_id") ?>" class="btn btn-primary"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a>
                                      
                                    </td>
                                    <td><a onclick="return confirm('Do you want to delete?')" href="<?php echo site_url("PurchaseController/purchase_delete/$purchase_id") ?>" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
                                    <td><a class="btn btn-danger" href="<?php echo site_url("PurchaseController/purchase_details_print/$purchase_id") ?>"><i class="glyphicon glyphicon-print" aria-hidden="true"></i>
                                        </a></td>
                                </tr>
                            <?php
                            }
                            ?>

                        </table>


                    </div>
                </div>
            </div>
            <div class="container" style="width: 100%">
                <div class="row" style="list-style: none ">

                    <?php echo $pagination; ?>



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
    <div class="modal" id="globalModalDetails" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
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
                url: "<?php echo site_url('PurchaseController/purchase_edit') ?>" + '/' + data[2],
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