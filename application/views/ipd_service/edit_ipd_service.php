<script>
    $(document).ready(function() {
        $('#patient_unique_id').focus();
        $('#reference_doctor_id').select2();
        var id_control = document.getElementById('id_control').value * 1;
        for (var counter = 1; counter <= Number(id_control); counter++) {
            $('#ipd_service_item_id_' + counter).select2();
        }


    });

    function ipd_service_price_load(ipd_service_item_id) {
        var id = ipd_service_item_id.split('_');
        var idIndex = Number(id[4]);

        var ipd_service_item_id_value = $('#' + ipd_service_item_id).val();
        console.log('ipd_service_item_id_value=', ipd_service_item_id_value);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {

                var price_data = xhttp.responseText;
                var price_data_array = price_data.split('*');

                $('#price_' + idIndex).val(price_data_array[0]);
                $('#amount_' + idIndex).val(Number($('#price_' + idIndex).val()));
                document.getElementById("test_category_id_" + idIndex).value = price_data_array[1];
                totalamount();
            }
        }

        xhttp.open("POST", "<?php echo site_url('IpdServiceController/ipd_service_price_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhttp.send("ipd_service_item_id=" + ipd_service_item_id_value);

    }

    function removetr(element, e) {
        var seq = $(element).attr('sequence');
        if (seq != 0)
            $(element).parent().parent().remove();

        sub_total();
    }

    function sub_total() {
        var amount = $(".amount");
        total = 0;
        total = Number(total);
        $.each(amount, function(k, elm) {
            var camount = $(elm).val();
            camount = Number(camount);
            total += camount;
        });
        $("#net_total").val(total.toFixed(2));
    }

    function total_price_cal_unit_price(unit_price) {
        var id_no = unit_price.split("_");
        var quantit = $('#quantity_' + id_no[2]).val();
        //alert(quantit);

        var unit_price = $('#price_' + id_no[2]).val();
        $('#total_price_' + id_no[2]).val(Number(quantit) * Number(unit_price));
        //   alert(quantit);
        // alert(unit_price);
        var grand_total = 0;
        for (var i = 1; i <= Number(id_no[2]); i++) {
            var quantity = $('#quantity_' + i).val();
            var total_price = Number($('#price_' + i).val()) * Number(quantity);
            if (!isNaN(total_price)) {
                grand_total += Number(total_price);
            }
        }

        $('#net_total').val(Math.ceil(grand_total));

    }

    function total_price_cal(quantity_id) {
        var id_no = quantity_id.split("_");
        var quantit = $('#quantity_' + id_no[1]).val();
        var unit_price = $('#price_' + id_no[1]).val();
        $('#amount_' + id_no[1]).val(Number(quantit) * Number(unit_price));
        var grand_total = 0;
        for (var i = 0; i <= Number(id_no[1]); i++) {
            var quantity = $('#quantity_' + i).val();
            var total_price = Number($('#price_' + i).val()) * Number(quantity);

            if (!isNaN(total_price)) {
                grand_total += Number(total_price);
            }
        }
        $('#net_total').val(Math.ceil(grand_total));
    }



    function addMore() {
        var id_control = document.getElementById('id_control').value * 1;
        var next_id = id_control + 1;

        document.getElementById('id_control').value = next_id;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                // $('#cart_tab1').prepend(xhttp.responseText);
                var newdiv = document.createElement('tr');
                newdiv.innerHTML = xhttp.responseText;
                document.getElementById('cart_tab1').appendChild(newdiv);
                $("#ipd_service_item_id_" + next_id).select2();
                totalamount();
            }
        }

        xhttp.open("POST", "<?php echo site_url('IpdServiceController/add_more_ipd_service_row'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //                                    xhttp.send("fname=Henry&lname=Ford");
        xhttp.send('next_id=' + next_id);

    }

    function nettotalamount() {
        var total = $("#total").val();
        var discount = $("#discount").val();
        var len = discount.length;
        var discountAmount = 0;
        if (discount[len - 1] == '%') {
            var disArray = discount.split('%');
            discountAmount = disArray[0];
            var discount = (total * discountAmount) / 100;
            var nettotal = total - discount;
            $("#nettotal").val(nettotal.toFixed(2));
            $("#due_sale").val(nettotal.toFixed(2));
        } else {
            var nettotal = total - discount;
            $("#nettotal").val(nettotal.toFixed(2));
            $("#due_sale").val(nettotal.toFixed(2));
        }

    }

    function totalamount() {
        var amount = $(".amount");
        total = 0;
        total = Number(total);
        $.each(amount, function(k, elm) {
            var camount = $(elm).val();
            camount = Number(camount);
            total += camount;
        });
        $("#net_total").val(total.toFixed(2));
        //nettotalamount();
    }

    function patient_data_set(patient_unique_id) {
        $('#img').show();
        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var patient = xhttp.responseText;
                var patient_array = patient.split('*');
                //alert(patient_array);
                document.getElementById("ipd_patient_id").value = patient_array[0];
                document.getElementById("patient_name").value = patient_array[1];
                document.getElementById("mobile_number").value = patient_array[2];
                document.getElementById("age_year").value = patient_array[3];
                document.getElementById("age_month").value = patient_array[4];
                document.getElementById("age_day").value = patient_array[5];

                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('IpdPatientController/patient_data_load_by_unique_id'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("patient_unique_id=" + patient_unique_id);
    }

    $(document).ready(function() {
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
                return false;
            }
        });
        // Validate the form
        $("#ipd_service_entry_form").validate({
            rules: {
                patient_id: "required",
                ipd_service_id_1: "required",
            },
            messages: {
                patient_id: "Enter select a patient",
                ipd_service_id_1: "Please select a service",
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {

            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#ipd_service_entry_form').serialize();

            // Check if the form is valid
            if ($("#ipd_service_entry_form").valid()) {
                $('#ipd_service_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('IpdServiceController/ipd_service_update'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $.toast({
                                heading: 'Success',
                                text: 'Data has been updated successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });
                            $('#ipd_service_entry_form')[0].reset();
                            $('#ipd_service_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('view-ipd-service') ?>";
                            }, 1002);

                        } else {
                            alert('Error: ' + response.message);
                            $('#ipd_service_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#ipd_service_entry_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
</script>

<div class="container-fluid" style=" background-color: white;width: 98%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Edit IPD Service</h3>
        </div>

        <?php
        $ipd_service = $this->db->where('ipd_service_id', $ipd_service_id)->get('ipd_service')->row();
        $ipd_service_details = $this->db->where('ipd_service_id', $ipd_service_id)->get('ipd_service_details')->result();
        $ipd_patient = $this->db->where('ipd_patient_id', $ipd_service->ipd_patient_id)->get('ipd_patient')->row();
        ?>
        <div class="panel-body">
            <form id="ipd_service_entry_form" class="form-horizontal" method="post" enctype='multipart/form-data'>
                <input type="hidden" name="ipd_service_id" value="<?php echo $ipd_service_id ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Patient ID *</label>
                            <div class="col-sm-8">
                                <input type="hidden" value="<?php echo $ipd_service->ipd_patient_id ?>" class="form-control" id="ipd_patient_id" name="ipd_patient_id">
                                <input onchange="patient_data_set(this.value)" placeholder="Scan or Enter IPD Patient ID.." type="text" value="<?php echo $ipd_patient->patient_unique_id ?>" id="patient_unique_id" name="patient_unique_id" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Date *</label>
                            <div class="col-sm-8">
                                <input type="text" required="" value="<?php echo date('d-m-Y', strtotime($ipd_service->date)); ?>" class="form-control" id="datepicker" name="date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Patient Name *</label>

                            <div class="col-sm-8">
                                <input readonly type="text" placeholder="Enter Patient Name" class="form-control" value="<?php echo $ipd_patient->patient_name ?>" id="patient_name" name="patient_name">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="name">Age</label>


                            <div class="col-sm-3">
                                <input type="text" placeholder="Year" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $ipd_patient->age_year ?>" id="age_year" name="age_year">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" placeholder="Month" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $ipd_patient->age_month ?>" id="age_month" name="age_month">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" placeholder="Day" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $ipd_patient->age_day ?>" id="age_day" name="age_day">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Mobile Number</label>
                            <div class="col-sm-8">
                                <input readonly type="text" placeholder="Enter Mobile Number" class="form-control" value="<?php echo $ipd_patient->mobile_number ?>" id="mobile_number" name="mobile_number">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="reference_doctor_id">Reference Doctor</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="reference_doctor_id" name="reference_doctor_id">
                                    <option value="">Select Doctor</option>
                                    <?php
                                    $doctors = $this->db->select('*')->get('doctor')->result();
                                    foreach ($doctors as $doctor_value) {
                                    ?>
                                        <option <?php echo $doctor_value->doctor_id == $ipd_service->reference_doctor_id ? 'selected' : '' ?> value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->doctor_name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">

                            <table id="cart_tab1" class="table table-bordered table-hover table-striped">

                                <input type="hidden" id="current_id" value="0">
                                <tr id="1">
                                    <td>Service Name *</td>
                                    <td>Quantity *</td>
                                    <td>Unit Price *</td>
                                    <td>Total Price *</td>
                                </tr>
                                <input type="hidden" value="<?php echo count($ipd_service_details) ?>" name="id_control" id="id_control" class="form-control">
                                <?php
                                $id = 1;
                                foreach ($ipd_service_details as $ipd_service_detail_value) {
                                ?>
                                    <tr>
                                        <td>
                                            <select style="width: 300px;" type="text" required="" class="form-control" onchange="ipd_service_price_load(this.id)" id="ipd_service_item_id_<?php echo $id ?>" name="ipd_service_item_id[]">
                                                <option selected="" value="" disabled="">Select Service Item</option>
                                                <?php
                                                $ipd_services = $this->db->select('*')->get('ipd_service_item')->result();
                                                foreach ($ipd_services as $value) {
                                                ?>
                                                    <option <?php echo $ipd_service_detail_value->ipd_service_item_id == $value->ipd_service_item_id ? "selected" : "" ?> value="<?php echo $value->ipd_service_item_id; ?>">
                                                        <?php echo $value->name; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                            <input type="hidden" class="form-control" value="<?php echo $ipd_service_detail_value->test_category_id ?>" id="test_category_id_<?php echo $id ?>" name="test_category_id[]">
                                        </td>
                                        <td>
                                            <input style="width: 100px;" type="text" required="" value="<?php echo $ipd_service_detail_value->quantity ?>" class="form-control" id="quantity_<?php echo $id ?>" oninput="total_price_cal(this.id)" sequence=0 name="quantity[]">
                                        </td>
                                        <td>
                                            <input style="width: 100px;" type="text" required="" value="<?php echo $ipd_service_detail_value->price ?>" class="form-control price" id="price_<?php echo $id ?>" oninput="total_price_cal_unit_price(this.id)" placeholder="Price" sequence=0 name="price[]">
                                        </td>
                                        <td>
                                            <input readonly style="width: 100px;" type="text" required="" value="<?php echo $ipd_service_detail_value->amount ?>" class="form-control amount" id="price_<?php echo $id ?>" placeholder="Amount" sequence=0 name="amount[]">
                                        </td>
                                        <?php
                                        if ($id == 1) {
                                        ?>

                                            <td><input style="width: 40px;" type="button" onclick="addMore()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td>
                                                <input style="width: 40px" type="button" onclick="removetr(this, event)" style="width:50px" readonly id="add_more" title="Click TO Remove" value="-">
                                            </td>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                <?php
                                    $id++;
                                }
                                ?>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
                    </div>
                    <div class="col-md-4" style="margin-top:5px;">
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="pwd">Total</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="<?php echo $ipd_service->net_total ?>" required="" readonly="" id="net_total" name="net_total">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>

</div>
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
