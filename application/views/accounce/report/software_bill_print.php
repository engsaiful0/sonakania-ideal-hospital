<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #report,
        #report * {
            visibility: visible;
            overflow: visible;
        }

        #report {
            position: absolute;
            left: 0;
            top: 0;
        }

        .p1 {
            line-height: 80% !important;
        }
    }

    .p1 {
        line-height: 80% !important;
    }
</style>
</head>

<body>

    <div class="row">
        <div class="col-md-12">
            <button onclick="window.print()" id="submit_button" class="btn btn-primary">Print</button>
        </div>
    </div>

    <div id="report" style="width: 90%; margin: 0 auto; margin-left: 45px; margin-top: 10px;">
        <?php
        error_reporting(0);
        $software_bill = $this->db->where('software_bill_id', $software_bill_id)->get('software_bills')->row();
        ?>

        <?php if ($software_bill): ?>
            <!-- Header -->
            <div style="width: 100%; margin-bottom: 10px;">
                <div style="width: 20%; float: left; margin-top: 20px;">
                    <img style="width: 60%; padding-left: 30px;" src="<?= base_url() ?>assets/images/bijoylab_logo.png">
                </div>
                <div style="width: 80%; float: left; text-align: center;">
                    <p>
                        <span style="font-size: 30px;">Bijoylab Web & IT Solution</span><br>
                        <span>Email: info@bijoylab.com, Web: www.bijoylab.com, Mobile: +8801818-650864</span><br>
                        <span>Address: Flat # A6, Molla Para, North Agrabad, Double Mooring, Chattogram, Bangladesh</span>
                    </p>
                </div>
            </div>

            <!-- Software Bill Info -->
            <div style="width: 100%; margin-bottom: 10px;">
                <table border="1" style="width: 100%; border-collapse: collapse; color: black;">
                    <tr>
                        <td colspan="8" style="text-align: center;"><b>Monthly Software Bill</b></td>
                    </tr>
                    <tr>
                        <td>Month</td>
                        <td><b><?= $software_bill->month ?></b></td>
                        <td>Year</td>
                        <td><b><?= $software_bill->year ?></b></td>
                        <td>Generated Date</td>
                        <td><b><?= date('d-m-Y', strtotime($software_bill->created_at)) ?></b></td>
                        <td>Status</td>
                        <td><b><?= $software_bill->status ?></b></td>
                    </tr>
                    <tr>
                        <td colspan="8"><b><?= $software_bill->bill_note ?></b></td>
                    </tr>
                </table>

                <!-- Billing Details -->
                <table border="1" style="width: 100%; border-collapse: collapse; color: black; margin-top: 10px;">
                    <tr>
                        <td colspan="6" style="text-align: center;"><u>Description</u></td>
                    </tr>
                    <tr>
                        <td>#</td>
                        <td>Regular Service Charge</td>
                        <td>Total SMS</td>
                        <td>Per SMS Price</td>
                        <td>Total SMS Price</td>
                        <td>Total Bill</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td><?= $software_bill->regular_charge ?></td>
                        <td><?= $software_bill->total_sms ?></td>
                        <td><?= $software_bill->per_sms_price ?></td>
                        <td><?= $software_bill->total_sms_price ?></td>
                        <td><?= $software_bill->total_bill ?></td>
                    </tr>
                    <tr>
                        <td colspan="6" style="text-align: left;">
                            <b>Amount in Words: <?= convertNumberToWord($software_bill->total_bill) ?> Taka only</b>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Signatures -->
            <div style="margin-top: 100px;">
                <div style="width: 50%; float: left;">
                    <img style="width: 200px; height: 70px; padding-left: 30px;" src="<?= base_url() ?>assets/images/signature.png">
                    <p>_____________________________________ <br>On behalf<br>
                        Engr. Saiful Islam, B.Sc(DUET), M.Sc(CUET), PhD (Running) in CSE<br>
                        CEO & Founder, Bijoylab.
                    </p>
                </div>
                <div style="width: 50%; float: right;">
                    <p style="text-align: right; margin-top: 100px;">________________<br>Receiver Signature</p>
                </div>
            </div>
        <?php else: ?>
            <p style="color: red; text-align: center;">No bill record found for the specified ID.</p>
        <?php endif; ?>
    </div>

    <!-- JavaScript (if needed for dynamic forms) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on('submit', '.due-payment-form', function(e) {
                e.preventDefault();

                const form = $(this);
                const submitBtn = form.find('.submit-due-btn');
                const formData = form.serialize();

                form.find(':input').prop('disabled', true);
                submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Saving...');

                $.ajax({
                    type: "POST",
                    url: "<?= base_url('TestDueController/test_due_payment_save'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $.toast({
                                heading: 'Success',
                                text: 'Payment saved successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1500,
                                icon: 'success'
                            });
                            setTimeout(function() {
                                window.location.href = "<?= base_url('print-test-entry-after-due-payment') ?>";
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