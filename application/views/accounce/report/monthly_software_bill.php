<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Software Bill </h3>
    </div>
    <div class="panel-body">
        <table class="table" border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Regular Charge</th>
                    <th>Total SMS</th>
                    <th>Per SMS Price</th>
                    <th>Total SMS Price</th>
                    <th>Total Bill</th>
                    <th>Paid</th>
                    <th>Status</th>
                    <th>Generated Date</th>

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $serial = 1;
                $software_bills = $this->db->select('*')->order_by('software_bill_id', 'DESC')->get('software_bills')->result();
                foreach ($software_bills as $software_bill) {
                    $software_bill_id = $software_bill->software_bill_id;
                ?>
                    <tr>
                        <td><?php echo $serial++; ?></td>
                        <td><?php echo $software_bill->month; ?></td>
                        <td><?php echo $software_bill->year; ?></td>
                        <td><?php echo $software_bill->regular_charge; ?></td>
                        <td><?php echo $software_bill->total_sms; ?></td>
                        <td><?php echo $software_bill->per_sms_price; ?></td>
                        <td><?php echo $software_bill->total_sms_price; ?></td>
                        <td><?php echo $software_bill->total_bill; ?></td>
                        <td><?php echo $software_bill->paid_amount; ?></td>
                        <td>
                            <?php echo ($software_bill->status === 'Paid') ? '<span style="color:green;">Paid</span>' : '<span style="color:red;">Due</span>'; ?>
                        </td>
                        <td><?php echo date('d-m-Y', strtotime($software_bill->created_at)); ?></td>
                        <td>
                            <!-- Print Button -->
                            <a href="<?php echo base_url() ?>software-bill-print/<?php echo $software_bill_id ?>" class="btn btn-primary">🖨️</a>
                            <!-- Toggle Status Button -->
                            <form class="software-bill-form" data-id="<?= $software_bill_id ?>" method="post" style="display:inline;">
                                <input type="hidden" name="software_bill_id" value="<?php echo $software_bill->software_bill_id; ?>">
                                <input type="hidden" name="new_status" value="<?php echo $software_bill->status === 'Paid' ? 'Due' : 'Paid'; ?>">
                                <input class="form-control" name="paid_amount" placeholder="Enter Paid Amount" id="paid_amount">
                                <button class="btn btn-success submit-status-btn" type="submit">
                                    Mark as <?php echo $software_bill->status === 'Paid' ? 'Due' : 'Paid'; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>

</div>
<script>
    $(document).ready(function() {
        $(document).on('submit', '.software-bill-form', function(e) {
            e.preventDefault();

            const form = $(this);
            const submitBtn = form.find('.submit-status-btn');
            const formData = form.serialize();

            const given = form.find('[name="given"]').val();
            form.find(':input').prop('disabled', true);
            submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                type: "POST",
                url: "<?= base_url('CronForBillController/update_status'); ?>",
                data: formData,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $.toast({
                            heading: 'Success',
                            text: 'Payment update successfully.',
                            showHideTransition: 'slide',
                            position: 'top-right',
                            hideAfter: 1500,
                            icon: 'success'
                        });
                        setTimeout(function() {
                            window.location.href = "<?php echo base_url('monthly-software-bill') ?>";
                        }, 1002);
                    } else {
                        alert("Error: " + response.message);
                        form.find(':input').prop('disabled', false);
                        submitBtn.html('Pay');
                    }
                },
                error: function(xhr, status, error) {
                    alert("AJAX error: " + error);
                    form.find(':input').prop('disabled', false);
                    submitBtn.html('Pay');
                }
            });
        });
    });
</script>