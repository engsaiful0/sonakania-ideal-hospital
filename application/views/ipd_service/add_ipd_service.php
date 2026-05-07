<script>
    $(document).ready(function() {
        $('#ipd_service_item_id_0').select2();
        $('#reference_doctor_id').select2();
        
        $('#patient_unique_id').focus();

    });

    function ipd_service_price_load(ipd_service_item_id) {
        var id = ipd_service_item_id.split('_');
        var idIndex = id[4];

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

        xhttp.open("POST", "<?php echo site_url('IpdServiceController/ipd_service_price_with_category_load'); ?>", true);
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
        //  alert(id_no);
        var quantit = $('#quantity_' + id_no[1]).val();
        //  alert(quantit);

        var unit_price = $('#price_' + id_no[1]).val();
        $('#total_price_' + id_no[1]).val(Number(quantit) * Number(unit_price));
        //   alert(quantit);
        // alert(unit_price);
        var grand_total = 0;
        for (var i = 1; i <= Number(id_no[1]); i++) {
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
                patient_data_set(ui.item.value);
                return false;
            }
        });
        // Validate the form
        $("#ipd_service_entry_form").validate({
            rules: {
                ipd_patient_id: "required",
                ipd_service_item_id_0: "required",
            },
            messages: {
                ipd_patient_id: "Enter select a patient",
                ipd_service_item_id_0: "Please select a service",
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
                    url: "<?php echo base_url('IpdServiceController/ipd_service_save'); ?>",
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
                            $('#ipd_service_entry_form')[0].reset();
                            $('#ipd_service_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');

                            setTimeout(function() {
                                setTimeout(function() {
                                    window.location.href = "<?php echo base_url('print-ipd-service') ?>";
                                }, 1002);
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
            <h3 style="text-align: center">Add IPD Service</h3>
        </div>

        <div class="panel-body">
            <form id="ipd_service_entry_form" class="form-horizontal" method="post" enctype='multipart/form-data'>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Patient ID *</label>
                            <div class="col-sm-8">

                                <input type="hidden" id="ipd_patient_id" name="ipd_patient_id">
                                <input placeholder="Scan or Enter IPD Patient ID" type="text" id="patient_unique_id" name="patient_unique_id" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Date *</label>
                            <div class="col-sm-8">
                                <input type="text" required="" value="<?php echo date('d-m-Y'); ?>" class="form-control" id="datepicker" name="date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Patient Name *</label>

                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Enter Patient Name" class="form-control" id="patient_name" name="patient_name">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="name">Age</label>
                            <div class="col-sm-3">
                                <input readonly type="text" placeholder="Year" oninput="validateIntegerInput(this)" class="form-control" id="age_year" name="age_year">
                            </div>
                            <div class="col-sm-3">
                                <input readonly type="text" placeholder="Month" oninput="validateIntegerInput(this)" class="form-control" id="age_month" name="age_month">
                            </div>
                            <div class="col-sm-3">
                                <input readonly type="text" placeholder="Day" oninput="validateIntegerInput(this)" class="form-control" id="age_day" name="age_day">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Mobile Number</label>
                            <div class="col-sm-8">
                                <input type="text" readonly placeholder="Enter Mobile Number" class="form-control" id="mobile_number" name="mobile_number">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Reference Doctor</label>
                            <div class="col-sm-8">
                                <select type="text" class="form-control" id="reference_doctor_id" name="reference_doctor_id">
                                    <option selected="" value="">Select Reference Doctor</option>
                                    <?php
                                    $doctor = $this->db->select('*')->get('doctor')->result();
                                    foreach ($doctor as $doctor_value) {
                                    ?>
                                        <option value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->doctor_name ?></option>
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
                            <input type="hidden" value="0" name="id_control" id="id_control" class="form-control">
                            <table id="cart_tab1" class="table table-bordered table-hover table-striped">
                                <input type="hidden" id="idControl" value="0">
                                <input type="hidden" id="current_id" value="0">
                                <tr id="1">
                                    <td>Service Name *</td>
                                    <td>Quantity *</td>
                                    <td>Unit Price *</td>
                                    <td>Total Price *</td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;">
                                        <input type="hidden" class="form-control" id="test_category_id_1" name="test_category_id[]">
                                        <select style="width: 300px;" type="text" required="" class="form-control" onchange="ipd_service_price_load(this.id)" id="ipd_service_item_id_0" name="ipd_service_item_id[]">
                                            <option selected="" value="" disabled="">Select Service Item</option>
                                            <?php
                                            $ipd_services = $this->db->select('*')->get('ipd_service_item')->result();
                                            foreach ($ipd_services as $value) {
                                            ?>
                                                <option value="<?php echo $value->ipd_service_item_id; ?>">
                                                    <?php echo $value->name; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td style="width: 10%;">
                                        <input type="text" required="" value="1" class="form-control" id="quantity_0" placeholder="Quantity" oninput="total_price_cal(this.id)" name="quantity[]">
                                    </td>
                                    <td style="width: 10%;">
                                        <input type="text" required="" class="form-control" id="price_0" oninput="total_price_cal_unit_price(this.id)" placeholder="Price" name="price[]">
                                    </td>
                                    <td style="width: 10%;">
                                        <input type="text" required="" class="form-control amount" id="amount_0" placeholder="Total Amount" name="amount[]">
                                    </td>
                                    <td style="width: 2%;"><input type="button" onclick="addMore()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
                                </tr>
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
                            <label class="control-label col-sm-3" for="pwd">Total*</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="0" required="" readonly="" id="net_total" name="net_total">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<?php
