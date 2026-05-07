<?php
$permissions = $this->session->userdata('permissions');
?>
<script>
    function change_calculate(given) {
        var id = given.split("_");
        var due = $('#due_' + id[1]).val();
        var given = $('#given_' + id[1]).val();
        $('#change_' + id[1]).val(Number(given) - Number(due));

    }
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
    $(document).ready(function() {

        // Validate the form
        $("#due_payment_form").validate({
            rules: {
                given: "required",
            },
            messages: {
                given: "Enter given amount",
            }
        });




    });
</script>
<script>
    $(document).ready(function() {
        $(document).on('submit', '.due-payment-form', function(e) {
            e.preventDefault();

            const form = $(this);
            const submitBtn = form.find('.submit-due-btn');
            const formData = form.serialize();

            const given = form.find('[name="given"]').val();
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
                                window.location.href = "<?php echo base_url('print-test-entry-after-due-payment') ?>";
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

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Paid and Due Management</h3>
    </div>

    <div class="panel-body" style="width: 100%;">


        <?php if (in_array('test_search_due', $permissions)) { ?>
            <form method="post" action="<?php echo base_url('test-due-collection') ?>">
                <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 90%;">
                    <tr>
                        <td>Patient Name</td>
                        <td>Mobile</td>
                        <td>Invoice No</td>
                        <td>From Date</td>
                        <td>To Date</td>
                    </tr>
                    <tr>
                        <td>
                            <input placeholder="Part of name" type="text" id="patient_name" name="patient_name" class="form-control" />

                        </td>

                        <td>
                            <input placeholder="Part of mobile" type="text" id="mobile" name="mobile" class="form-control" />

                        </td>
                        <td>
                            <input placeholder="Scan of Enter invoice no" type="text" id="invoice_no" name="invoice_no" class="form-control" />
                        </td>
                        <td>
                            <input id="datepicker1" name="from_date" class="form-control">
                        </td>
                        <td>
                            <input id="datepicker2" name="to_date" class="form-control">
                        </td>
                        <td><input type="submit" id="sumbit_button" value="Search" class="btn btn-primary"></td>
                    </tr>
                </table>
            </form>
        <?php } ?>
        <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
            <tr>
                <td>Sl</td>
                <td>Patient Name<br>Mobile</td>

                <td>Invoice No</td>

                <td>Sub Total</td>
                <td>Disc.</td>
                <td>Net Total</td>
                <td>Paid</td>
                <td>Due</td>
                <td style="width:10%">Date</td>
                <?php
                $user_type_name = $this->session->userdata('user_type');
                if ($user_type_name == 'Admin') {
                ?>
                    <td>User</td>
                <?php
                }
                ?>
                <td>Given</td>
                <td>Change</td>
                <?php if (in_array('test_search_pay', $permissions)) { ?>
                    <td>Pay</td>
                <?php } ?>
                <?php if (in_array('test_print_due', $permissions)) { ?>
                    <td>Print</td>
                <?php } ?>
            </tr>
            <?php

            //  print_r( count($detailsList));

            $sl = 1;
            for ($i = 0; $i < count($detailsList); ++$i) {
                $patient_test_entry_id = $detailsList[$i]->patient_test_entry_id;
                $ref_doctor = $this->db->where('doctor_id', $detailsList[$i]->doctor_id)->get('doctor')->row();
                $user = getUserById($detailsList[$i]->user_id);

                $this->db->select_sum('paid');
                $this->db->from('test_collection');
                $this->db->where('patient_test_entry_id', $detailsList[$i]->patient_test_entry_id); // Filter by today's date
                $query = $this->db->get();
                $total_paid_of_this_test = $query->row()->paid; // Return 0 if no income found


            ?>
                <tr>
                    <td><?php
                        echo $sl++;
                        ?></td>
                    <td>
                        <?php echo $detailsList[$i]->patient_name ?><br><?php echo $detailsList[$i]->mobile_number ?>
                    </td>



                    <td>
                        <?php echo $detailsList[$i]->invoice_no ?>
                    </td>
                    <td><?php echo $detailsList[$i]->sub_total ?></td>
                    <td><?php echo $detailsList[$i]->discount ?></td>
                    <td><?php echo $detailsList[$i]->net_total ?></td>
                    <td><?php echo $total_paid_of_this_test ?></td>
                    <td><?php echo $detailsList[$i]->net_total - $total_paid_of_this_test ?>

                        <input type="hidden" name="due" value="<?php echo $detailsList[$i]->due ?>" id="due_<?php echo $patient_test_entry_id ?>">
                    </td>
                    <td><?php echo date('d-m-Y', strtotime($detailsList[$i]->date)) ?></td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td><?php echo $user->user_name ?? "" ?> </td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('test_search_pay', $permissions)) { ?>
                        <?php
                        if ($detailsList[$i]->net_total == $total_paid_of_this_test) {
                        ?>
                            <td colspan="3" style="background-color: #2189ff ;color: white;text-align: center;font-weight: bold  ">Paid</td>

                        <?php
                        } else {
                        ?>
                            <form class="due-payment-form" data-id="<?= $patient_test_entry_id ?>">
                                <td>
                                    <input type="text" placeholder="Given" class="form-control" oninput="change_calculate(this.id)" name="given" id="given_<?php echo $patient_test_entry_id ?>">
                                    <input type="hidden" name="patient_test_entry_id" value="<?php echo $patient_test_entry_id ?>">
                                    <input type="hidden" name="date" value="<?php echo date('Y-m-d') ?>">
                                </td>
                                <td>
                                    <input type="text" readonly placeholder="Change" class="form-control" name="change" id="change_<?php echo $patient_test_entry_id ?>">
                                </td>
                                <td>
                                <button type="submit" class="btn btn-primary submit-due-btn">Pay</button>
                                </td>
                            </form>

                        <?php
                        }
                        ?>
                    <?php } ?>
                    <?php if (in_array('test_print_due', $permissions)) { ?>
                        <td>
                            <a href="<?php echo site_url("print-test-entry-with-id/$patient_test_entry_id") ?>" class="btn btn-primary"><i class="glyphicon glyphicon-print" aria-hidden="true"></i></a>
                        </td>
                    <?php } ?>
                </tr>
            <?php
            }
            ?>

        </table>
    </div>
</div>
<div class="container" style="width: 50%;margin:0 auto">
    <div class="row" style="list-style: none ">
        <p><?php echo $pagination; ?></p>
    </div>
</div>