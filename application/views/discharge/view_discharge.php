<?php
$permissions = $this->session->userdata('permissions');
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">View Discharge</h3>
    </div>

    <div class="panel-body" style="width: 100%;">
        <script>
            jQuery(document).ready(function() {
                jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                    $(this).slideUp('slow', function() {
                        $(this).remove();
                    });
                });
            });
            $(document).ready(function() {
                $('#discharge_reason_id').select2();

                $('#discharge_bill_id').focus();

                $("#patient_unique_id").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('IpdPatientController/patient_unique_id_load'); ?>",
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
                        $('#patient_unique_id').val(ui.item.label);
                        $('form').submit(); // Automatically submit the form
                        return false;
                    }
                });
            });


            function deleteDischarge(discharge_id, row_id) {
                if (confirm('Do you want to delete?')) {
                    $.ajax({
                        url: "<?php echo site_url('DischargeController/delete_ipd_discharge_ajax'); ?>",
                        type: 'POST',
                        data: {
                            discharge_id: discharge_id
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
                                setTimeout(function() {
                                    window.location.href = "<?php echo base_url('view-discharge'); ?>";
                                }, 1005);
                                // Remove the specific row from the table
                                //$('#' + row_id).remove();
                            } else {
                                $.toast({
                                    heading: 'Error',
                                    text: res.message,
                                    showHideTransition: 'slide',
                                    position: 'top-right',
                                    hideAfter: 1000,
                                    icon: 'error'
                                });
                            }
                        }
                    });
                }
            }
        </script>

        <?php if (in_array('search_discharge', $permissions)) { ?>
            <form method="post" action="<?php echo base_url('view-discharge') ?>">
                <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                    <tr style="background-color: #337AB7;color:white">
                        <td>Discharge ID</td>
                        <td>Patient ID</td>
                        <td>Discharge Reason</td>
                        <td>Admission Date</td>
                        <td>Discharge Date</td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>
                            <input placeholder="Scan or Enter Discharge ID" type="text" id="discharge_bill_id" name="discharge_bill_id" class="form-control" />
                        </td>

                        <td>
                            <input placeholder="Scan or Enter Patient ID" type="text" id="patient_unique_id" name="patient_unique_id" class="form-control" />
                        </td>
                        <td>
                            <select required type="text" class="form-control" id="discharge_reason_id" name="discharge_reason_id">
                                <option disabled="" value="">Select Discharge Reason</option>
                                <?php
                                $discharge_reasons = $this->db->select('*')->get('discharge_reasons')->result();
                                foreach ($discharge_reasons as $discharge_reason) {
                                ?>
                                    <option value="<?php echo $discharge_reason->discharge_reason_id ?>"><?php echo $discharge_reason->name ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <input id="datepicker1" name="admission_date" class="form-control">
                        </td>
                        <td>
                            <input id="datepicker2" name="discharge_date" class="form-control">
                        </td>
                        <td><input type="submit" value="Submit" class="btn btn-primary"></td>
                    </tr>
                </table>
            </form>
        <?php } ?>

        <?php if (isset($detailsList) && is_array($detailsList) && !empty($detailsList)) :

        ?>
            <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                <tr>
                    <td style="width: 2%;">Sl</td>
                    <td style="width: 10%;">Patient</td>

                    <td style="width: 10%;">Admit Date</td>
                    <td style="width: 10%;">Discharge Date</td>
                    <td>Duration</td>
                    <td>Total Bill</td>
                    <td>Previous Paid</td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td>Bill<br>Maker</td>
                        <td>Bill<br>Collector</td>
                    <?php
                    }
                    ?>
                    <!-- <td>Director Discount</td>
                    <td>Payable</td>
                    <td>Special Discount</td>
                    <td>Net Payable</td>
                    <td>Paid</td>
                    <td>Due</td> -->
                    <?php if (in_array('discharge_pay', $permissions)) { ?>
                        <td style="width: 5%;">Pay</td>
                    <?php } ?>

                    <?php if (in_array('print_discharge', $permissions)) { ?>
                        <td style="width: 5%;">Print</td>
                    <?php } ?>
                    <?php if (in_array('edit_discharge', $permissions)) { ?>
                        <td style="width: 5%;">Edit</td>
                    <?php } ?>
                    <?php if (in_array('delete_discharge', $permissions)) { ?>
                        <td style="width: 5%;">Delete</td>
                    <?php } ?>
                </tr>
                <?php
                error_reporting(0);
                $sl = 1;
                for ($i = 0; $i < count($detailsList); ++$i) {
                    $discharge_reason = getDischargeReasonByID($detailsList[$i]->discharge_reason_id);
                    $ipd_patient = $this->db->where('ipd_patient_id', $detailsList[$i]->ipd_patient_id)->get('ipd_patient')->row();
                    $doctor = $this->db->where('doctor_id', $ipd_patient->reference_doctor_id)->get('doctor')->row();
                    $reference_media = $this->db->where('reference_media_id', $ipd_patient->reference_media_id)->get('reference_media')->row();
                    $ward = $this->db->where('ward_id', $ipd_patient->ward_id)->get('ward')->row();
                    $bed = $this->db->where('bed_id', $ipd_patient->bed_id)->get('bed')->row();
                    $cabin = $this->db->where('cabin_id', $ipd_patient->cabin_id)->get('cabin')->row();
                    $discharge_id = $detailsList[$i]->discharge_id;

                    // Combine date and time into a single string
                    $admissionDateTimeStr = $ipd_patient->date . ' ' . $ipd_patient->admission_time;

                    // Create DateTime object for the admission date and time
                    $admissionDateTime = new DateTime($admissionDateTimeStr);

                    $dischargeDateTimeStr = $detailsList[$i]->discharge_date . ' ' . $detailsList[$i]->discharge_time;

                    // Create DateTime object for the current date and time
                    $currentDateTime = new DateTime($dischargeDateTimeStr);

                    // Calculate the difference between the current time and the admission time
                    $interval = $currentDateTime->diff($admissionDateTime);

                    // Build the duration string
                    $duration = '';
                    if ($interval->y > 0) {
                        $duration .= $interval->y . ' Y, ';
                    }
                    if ($interval->m > 0) {
                        $duration .= $interval->m . ' M, ';
                    }
                    if ($interval->d > 0) {
                        $duration .= $interval->d . ' D, ';
                    }
                    if ($interval->h > 0) {
                        $duration .= $interval->h . ' H, ';
                    }
                    if ($interval->i > 0) {
                        $duration .= $interval->i . ' M, ';
                    }
                    if ($interval->s > 0) {
                        $duration .= $interval->s . ' S';
                    }

                    // Remove trailing comma and space if they exist
                    $duration = rtrim($duration, ', ');

                    // Calculate total hours
                    $total_hours = ($interval->y * 365 * 24) + ($interval->m * 30 * 24) + ($interval->d * 24) + $interval->h;
                    $user = getUserById($detailsList[$i]->user_id); //bill collector
                    $user_bill_maker = getUserById($detailsList[$i]->bill_maker_id); //bill maker
                    // echo '<pre>';
                    // print_r($detailsList[$i]);
                ?>
                    <tr id="patient-row-<?php echo $discharge_id; ?>">
                        <td><?php echo $sl++; ?></td>
                        <td>Name:<b><?php echo $ipd_patient->patient_name ?></b><br>Mobile:<b><?php echo $ipd_patient->mobile_number ?></b><br>
                            Id:<b><?php echo $detailsList[$i]->patient_unique_id ?></b>
                            <?php if ($ward) { ?>
                                Ward:<b><?php echo $ward->name ?></b> Bed:<b><?php echo $bed->bed_number ?></b>
                            <?php
                            }
                            if ($cabin) { ?>
                                Cabin:<b><?php echo $cabin->cabin_number ?></b>
                            <?php
                            }
                            ?><br>
                            Dis. Id: <b><?php echo $detailsList[$i]->discharge_bill_id; ?></b><br>
                            <?php echo $discharge_reason->name; ?>
                        </td>

                        <td><?php echo date('d-m-Y', strtotime($detailsList[$i]->admission_date)) . '-' . $detailsList[$i]->admission_time ?></td>
                        <td><?php echo date('d-m-Y', strtotime($detailsList[$i]->discharge_date)) . '-' . $detailsList[$i]->discharge_time ?></td>
                        <td><b>
                                <?php
                                if ($detailsList[$i]->total_duration_day > 0) {
                                    echo $detailsList[$i]->total_duration_day . ' ' . ($detailsList[$i]->total_duration_day == 1 ? 'Day ' : 'Days ') . ' ';
                                }
                                if ($detailsList[$i]->total_duration_hours >= 0) {
                                    echo $detailsList[$i]->total_duration_hours . ' ' . ($detailsList[$i]->total_duration_hours == 1 ? 'Hour ' : 'Hours ');
                                }
                                if ($detailsList[$i]->discharge_time_minute > 0) {
                                    echo $detailsList[$i]->discharge_time_minute . ' ' . ($detailsList[$i]->discharge_time_minute == 1 ? 'Minute ' : 'Minutes ');
                                }
                                if ($detailsList[$i]->discharge_time_second > 0) {
                                    echo $detailsList[$i]->discharge_time_second . ' ' . ($detailsList[$i]->discharge_time_second == 1 ? 'Second ' : 'Seconds ');
                                }
                                ?>
                            </b></td>
                        <td><?php echo $detailsList[$i]->total_bill ?></td>
                        <td>
                            <span><b>Previous Paid:</b><?php echo number_format($detailsList[$i]->previous_paid) ?></span><br>
                            <span><b>Director Discount:</b><?php echo number_format($detailsList[$i]->director_discount) ?></span><br>
                            <span><b>Payable:</b><?php echo number_format($detailsList[$i]->payable) ?></span><br>
                            <span><b>Special Discount:</b><?php echo !empty($detailsList[$i]->special_discount)
                                                                ? number_format((float) $detailsList[$i]->special_discount)
                                                                : ''; ?></span><br>
                            <span><b>Net Payable:</b><?php echo number_format($detailsList[$i]->net_payable) ?></span><br>
                            <span><b>Paid:</b><?php
                                                if (isset($detailsList[$i]) && isset($detailsList[$i]->paid)) {
                                                    // Cast to float and format
                                                    $paidAmount = (float)$detailsList[$i]->paid; // Cast to float
                                                    $formattedPaid = number_format($paidAmount, 2); // Format to 2 decimal places
                                                    echo $formattedPaid; // Output the formatted value
                                                }


                                                ?></span><br>
                            ______________<br>
                            <span><b>Due:</b><?php echo number_format((float)$detailsList[$i]->due) ?></span><br>

                        </td>
                        <?php
                        $user_type_name = $this->session->userdata('user_type');
                        if ($user_type_name == 'Admin') {
                        ?>

                            <td><?php echo $user_bill_maker->user_name ?? "" ?> </td>
                            <td><?php echo $user->user_name ?? "" ?> </td>
                        <?php
                        }
                        ?>
                        <?php if (in_array('discharge_pay', $permissions)) { ?>
                            <td>
                                <?php
                                if ($detailsList[$i]->due == 0 || $detailsList[$i]->due == '') {
                                ?>
                                    <button class="btn btn-success">Paid<button />
                                    <?php
                                } else {
                                    ?>
                                        <a href="<?php echo base_url("full-pay-bill/$discharge_id") ?>" class="btn btn-primary" style="color: white">Pay</a>
                                    <?php
                                }
                                    ?>

                            </td>
                        <?php } ?>

                        <?php if (in_array('print_discharge', $permissions)) { ?>
                            <td><a href="<?php echo base_url("print-discharge-bill-again/$discharge_id") ?>" title="Print" class="btn btn-primary" style="color: white"><i class="glyphicon glyphicon-print" aria-hidden="true"></i></a></td>
                        <?php } ?>

                        <?php if (in_array('edit_discharge', $permissions)) { ?>
                            <td><a href="<?php echo base_url("edit-discharge/$discharge_id") ?>" class="btn btn-primary" style="color: white"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a></td>
                        <?php } ?>
                        <?php if (in_array('delete_discharge', $permissions)) { ?>
                            <td><a onclick="deleteDischarge(<?php echo $discharge_id; ?>)" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </table>
            <div style="width:70%;margin:0 auto;text-align:center">
                <p><?php echo $pagination; ?></p>
            </div>
        <?php else : ?>
            <p style="text-align:center">No records found.</p>
        <?php endif; ?>
    </div>
</div>