<script>
    document.addEventListener('keydown', function(event) {
        if (event.shiftKey && event.key === '+') {
            event.preventDefault();
            addMore();
        }
    });



    $(document).ready(function() {

          // Custom validation method to check at least one field is filled in array
          $.validator.addMethod("checkQuantityOrWeight", function(value, element) {
            let isFilled = false;
            $("input[name='quantity_or_weight[]']").each(function() {
                if ($(this).val() !== "") {
                    isFilled = true;
                    return false; // Break out of the loop if at least one input is filled
                }
            });
            return isFilled;
        }, "Please enter at least one quantity or weight.");

        $("#canteen_goods_usage_form").validate({
            rules: {
                "quantity_or_weight[]": {
                    checkQuantityOrWeight: true // Use the custom validation rule here
                },
                date: "required"
            },
            messages: {
                "quantity_or_weight[]": {
                    checkQuantityOrWeight: "Please enter at least one quantity or weight."
                },
                date: "Date is required"
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {

            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#canteen_goods_usage_form').serialize();

            // Check if the form is valid
            if ($("#canteen_goods_usage_form").valid()) {
                $('#canteen_goods_usage_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('CanteenGoodsUsageController/update_canteen_goods_usage'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $.toast({
                                heading: 'Success',
                                text: 'Data has been saved successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });
                            $('#canteen_goods_usage_form')[0].reset();
                            $('#canteen_goods_usage_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            window.location.href = "<?php echo base_url('print-canteen-goods-usage') ?>";
                        } else {
                            alert('Error: ' + response.message);
                            $('#canteen_goods_usage_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#canteen_goods_usage_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).keypress(function(e) {
        if (e.which == 13) {
            //alert('enter key is pressed');
            var paid = $('#paid').val();
            // alert(paid);
            if (paid > 0) {
                $("#submit").click();
            } else if (paid == 0) {
                $("#add_more").click();
            }
        }
    });
</script>
<?php
$canteen_goods_usage = $this->db->where('canteen_goods_usage_id', $canteen_goods_usage_id)->get('canteen_goods_usage')->row();
$canteen_goods_usage_details = $this->db->where('canteen_goods_usage_id', $canteen_goods_usage_id)->get('canteen_goods_usage_details')->result();
$id = 0;
?>
<div class="container-fluid">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Edit Canteen Goods Usage</h3>
        </div>
        <div class="panel-body">
            <form id="canteen_goods_usage_form" method="POST" class="form">
                <div class="col-sm-12 col-md-12 main">
                    <div class="table-responsive">

                        <input name="canteen_goods_usage_id" type="hidden" value="<?php echo $canteen_goods_usage_id ?>">
                        <table width="100%" class="table">
                            <tr>
                                <td width="100px;" align="right">
                                    <b>Date</b>
                                </td>
                                <td align="left" style="padding:5px;">
                                    <input type="text" value="<?php echo date('d-m-Y', strtotime($canteen_goods_usage->date)); ?>" id="date" name="date" class="form-control" readonly="" />
                                </td>
                                <td width="100px;" align="right">
                                    <b>Invoice</b>
                                </td>
                                <td align="left" style="padding:5px;">
                                    <input type="text" id="canteen_goods_usage_invoice_no" name="canteen_goods_usage_invoice_no" class="form-control" readonly="" value="<?php echo $canteen_goods_usage->canteen_goods_usage_invoice_no; ?>" />

                                </td>
                                <td width="100px;" colspan="" align="right"><b>Purpose</b></td>
                                <td align="left" style="padding:5px;">
                                    <textarea placeholder="Enter Purpose" type="text" id="purpose" name="purpose" class="form-control"><?php echo $canteen_goods_usage->purpose ?></textarea>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" id="idControl" value="1">
                        <input type="hidden" id="current_id" value="1">
                        <table width="100%" border="0" id="cart_tab1">
                            <tr>
                                <th style="width:100px;text-align: center;">
                                    Item
                                </th>
                                <th style="text-align: center;">
                                    Quantity/Weight
                                </th>
                            </tr>
                            <?php
                            $items = $this->db->select('*')->order_by('name', 'ASC')->get('canteen_raw_goods')->result();
                            $id = 0;

                            foreach ($items as $item) {
                                $this->db->select('unit_id, name');
                                $this->db->from('units');
                                $this->db->where('unit_id', $item->unit_id);
                                $unit = $this->db->get()->row();

                                $canteen_goods_usage_detail = $this->db
                                    ->where('canteen_goods_usage_id', $canteen_goods_usage_id)
                                    ->where('canteen_raw_goods_id', $item->canteen_raw_goods_id)
                                    ->get('canteen_goods_usage_details')->row();
                            ?>
                                <tr>
                                    <td>
                                        <?php echo $item->name . ' (' . $unit->name . ')' ?>
                                        <input type="hidden" value="<?php echo $item->canteen_raw_goods_id ?>" id="canteen_raw_goods_id0" name="canteen_raw_goods_id[]" class="form-control" sequence=0>
                                    </td>
                                    <td>
                                        <input onkeydown="validateNumberInput(event)" placeholder="Enter Quantity/Weight" type="text" value="<?php echo (!empty($canteen_goods_usage_detail->quantity_or_weight) && $canteen_goods_usage_detail->quantity_or_weight != 0) ? $canteen_goods_usage_detail->quantity_or_weight : ''; ?>" id="quantity_or_weight<?php echo $id++ ?>" name="quantity_or_weight[]" class="form-control">
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td></td>
                                <td>
                                    <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Update</button>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>