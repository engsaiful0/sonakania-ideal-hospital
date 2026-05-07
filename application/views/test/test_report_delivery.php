<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Report Delivery</h3>
    </div>
    <?php
    $permissions = $this->session->userdata('permissions');
    ?>
    <div class="panel-body" style="width: 100%;">

        <script>
            function change_calculate(given) {
                var id = given.split("_");
                var due = $('#due_' + id[1]).val();
                var given = $('#given_' + id[1]).val();
                $('#change_' + id[1]).val(Number(given) - Number(due));

            }
            $(document).ready(function() {

                $("#invoice_no").autocomplete({
                    source: function(request, response) {
                        console.log("Inside The Invoice No");
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
        </script>
        <?php if (in_array('search_report_delivery', $permissions)) { ?>
            <form method="post" action="<?php echo base_url('test-report-delivery') ?>">
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
                            <input placeholder="Part of invoice no" type="text" id="invoice_no" name="invoice_no" class="form-control" />
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
                <td>Patient Name</td>
                <td>Mobile</td>
                <!--<td>Age</td>-->
                <td>Gender</td>
                <td>Invoice No</td>
                <!--<td>Ref.Doctor</td>-->
                <td>Sub Total</td>
                <td>Discount</td>
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
                <?php if (in_array('report_delivery_now', $permissions)) { ?>
                    <td>Status</td>
                <?php } ?>
                <?php if (in_array('report_delivery_print', $permissions)) { ?>
                    <td>Print</td>
                <?php } ?>
            </tr>
            <?php
            error_reporting(0);
            //  print_r( count($detailsList));

            $sl = 1;
            for ($i = 0; $i < count($detailsList); ++$i) {
                $patient_test_entry_id = $detailsList[$i]->patient_test_entry_id;
                $ref_doctor = $this->db->where('doctor_id', $detailsList[$i]->doctor_id)->get('doctor')->row();
                $user = getUserById($detailsList[$i]->user_id);
            ?>
                <tr>
                    <td><?php
                        echo $sl++;
                        ?></td>
                    <td><?php echo $detailsList[$i]->patient_name ?></td>

                    <td> <?php echo $detailsList[$i]->mobile ?></td>
                    <!--<td><?php //echo $detailsList[$i]->age                                   
                            ?></td>-->
                    <td><?php echo $detailsList[$i]->gender ?>
                    </td>

                    <td><?php echo $detailsList[$i]->invoice_no ?>
                    </td>
                    <!-- <td><?php // echo $ref_doctor->doctor_name . '-' . $ref_doctor->doctor_unique_id                                   
                                ?>-->
                    </td>
                    <td><?php echo $detailsList[$i]->sub_total ?></td>
                    <td><?php echo $detailsList[$i]->discount ?></td>
                    <td><?php echo $detailsList[$i]->net_total ?></td>
                    <td><?php echo $detailsList[$i]->paid ?></td>
                    <td><?php echo $detailsList[$i]->due ?>
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
                    <?php if (in_array('report_delivery_now', $permissions)) { ?>
                        <?php
                        if ($detailsList[$i]->is_delivered == 'delivered') {
                        ?>
                            <td style="background-color: #2189ff ;color: white;text-align: center;font-weight: bold  ">Delivered</td>

                        <?php
                        } else if ($detailsList[$i]->due == '0') {
                        ?>
                            <td><a href="<?php echo site_url("TestController/deliver_report/$patient_test_entry_id") ?>" class="btn btn-success">Deliver Now</a></td>
                        <?php
                        } else if ($detailsList[$i]->due > '0') {
                        ?>
                            <td style=" text-align: center;font-weight: bold ">
                                Pay First
                            </td>
                        <?php
                        }
                        ?>
                    <?php } ?>
                    <?php if (in_array('report_delivery_print', $permissions)) { ?>
                        <td>
                            <a href="<?php echo site_url("TestController/TestEntryDetailsPrint/$patient_test_entry_id") ?>" class="btn btn-primary"><i class="glyphicon glyphicon-print" aria-hidden="true"></i></a>
                        </td>
                    <?php } ?>
                </tr>
            <?php
            }
            ?>

        </table>
    </div>
</div>
<div class="container" style="width: 100%">
    <div class="row" style="list-style: none ">
        <?php echo $pagination; ?>
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
<script>
    function TestEntryDetailsPrint(rowId) {

        var data = rowId.split('_'); //To get the row id

        //alert(data[2]);
        $_token = "{{ csrf_token() }}";
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name=_token]').attr('content')
            },
            url: "<?php echo site_url('TestController/TestEntryDetailsPrint') ?>" + '/' + data[2],
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
<script>
    function modalLoadEdit(rowId) {

        var data = rowId.split('_'); //To get the row id

        //alert(data[2]);
        $_token = "{{ csrf_token() }}";
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name=_token]').attr('content')
            },
            url: "<?php echo site_url('TestController/edit_test_entry') ?>" + '/' + data[2],
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