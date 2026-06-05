<script>
    $(document).ready(function() {
        $('#gender').select2();
        $('#doctor_id').select2();
        $('#employee_nurse_id').select2();

        $('#reference_media_id').select2();
        $('#reference_director_id').select2();
        $('#reference_doctor_id').select2();
        $('#reference_employee_id').select2();
        $('#years_or_days').select2();
        var id_control = document.getElementById('id_control').value * 1;
        for (var counter = 0; counter < Number(id_control); counter++) {
            $('#phygiotherapy_service_id_' + counter).select2();
        }


    });
    jQuery(document).ready(function() {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });
    $(function() {
        $('[name="visiting_time"]').timeselector()
    });

    function total_price_cal() {
        var quantity = $('#quantity').val();
        var unit_price = $('#unit_price').val();
        $('#total_price').val(Number(quantity) * Number(unit_price));
    }

    function director_discaount_amount_calculate() {
        var director_discount_percentage = $("#director_discount_percentage").val();
        var total = $("#sub_total").val();
        var director_discount = Number(total) * (Number(director_discount_percentage) / 100);
        $("#director_discount").val(director_discount.toFixed(2));
    }

    function director_discount_load() {
        var reference_director_id = $('#reference_director_id').val();
        document.getElementById('director_discount_container').style.display = 'block';
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                $('#director_discount_percentage_show').text(xhttp.responseText);
                $('#director_discount_percentage').val(xhttp.responseText);
                totalamount();
                director_discaount_amount_calculate();
                nettotalamount();
            }
        }
        xhttp.open("POST", "<?php echo site_url('CommonController/director_phygiotherapy_discount_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("reference_director_id=" + reference_director_id);
    }
    $(document).ready(function() {
        $("#discount_reference").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('OpdPatientController/discount_reference_load'); ?>",
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
                $('#discount_reference').val(ui.item.label);

                // Clear all reference IDs first
                $('#discount_reference_doctor_id').val('');
                $('#discount_reference_employee_id').val('');
                $('#discount_reference_media_id').val('');
                $('#discount_reference_director_id').val('');

                // Set the appropriate ID based on type
                if (ui.item.type === 'doctor') {
                    $('#discount_reference_doctor_id').val(ui.item.value);
                } else if (ui.item.type === 'director') {
                    $('#discount_reference_director_id').val(ui.item.value);
                } else if (ui.item.type === 'employee') {
                    $('#discount_reference_employee_id').val(ui.item.value);
                } else if (ui.item.type === 'reference_media') {
                    $('#discount_reference_media_id').val(ui.item.value);
                }

                return false;
            }
        });
        
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
        $("#phygiotherapy_data_entry_form").validate({
            rules: {
                name: "required",

                phone: {
                    required: true,
                    minlength: 11
                },
                gender: "required",
            },
            messages: {
                name: "Please Enter Patient Name",

                phone: {
                    required: "Please enter a valid mobile number",
                    minlength: "Your mobile number must consist of at least 11 characters"
                },
                gender: "Select Gender",
            }
        });

        // Single handler: Enter submits the form — prevent native POST + AJAX double-save.
        $('#phygiotherapy_data_entry_form').on('submit', function(e) {
            e.preventDefault();
            if (window.__phygioEditSubmitting) {
                return false;
            }

            var submitBtn = $('#submit_button');
            if (!$("#phygiotherapy_data_entry_form").valid()) {
                return false;
            }

            window.__phygioEditSubmitting = true;
            var formData = $('#phygiotherapy_data_entry_form').serialize();
            $('#phygiotherapy_data_entry_form :input').prop('disabled', true);
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

            $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('phygiotherapyController/update_phygiotherapy_data'); ?>",
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
                            $('#phygiotherapy_data_entry_form')[0].reset();
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('print-phygiotherapy') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            window.__phygioEditSubmitting = false;
                            $('#phygiotherapy_data_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Update');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        window.__phygioEditSubmitting = false;
                        $('#phygiotherapy_data_entry_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Update');
                    }
                });
            return false;
        });
    });
</script>
<script type="text/javascript">
    function phygiotherapy_service_price_load(phygiotherapy_service_id) {
        console.log("Function triggered: ", phygiotherapy_service_id);

        // Split ID to extract the index
        var id = phygiotherapy_service_id.split('_');
        var idIndex = id[3];
        var phygiotherapy_service_id_value = $('#phygiotherapy_service_id_' + idIndex).val();

        console.log("Selected Value: ", phygiotherapy_service_id_value);

        // Check for duplicates
        var isDuplicate = false;

        $('select[name="phygiotherapy_service_id[]"]').each(function() {
            console.log("Comparing: ", $(this).val(), " with ", phygiotherapy_service_id_value);
            if ($(this).val() === phygiotherapy_service_id_value && $(this).attr('id') !== phygiotherapy_service_id) {
                isDuplicate = true;
                console.log("Duplicate found!");
                return false; // Exit loop
            }
        });

        // Handle duplicate case
        if (isDuplicate) {
            console.log("Duplicate detected, resetting the dropdown.");
            $.toast({
                heading: 'Error',
                text: "This service is already selected. Please choose a different one.",
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 2000,
                icon: 'error'
            });

            // Reset the dropdown
            $('#phygiotherapy_service_id_' + idIndex).val('').trigger('change');
            return; // Stop further execution
        }

        // No duplicate detected
        console.log("No duplicate detected, proceeding with AJAX call.");

        // Proceed with AJAX call
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                console.log("AJAX response received: ", xhttp.responseText);
                $('#price_' + idIndex).val(xhttp.responseText);
                $('#amount_' + idIndex).val(Number($('#price_' + idIndex).val()));
                totalamount();
            }
        };
        xhttp.open("POST", "<?php echo site_url('PhygiotherapyController/phygiotherapy_service_price_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("phygiotherapy_service_id=" + phygiotherapy_service_id_value);
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

                $("#phygiotherapy_service_id_" + next_id).select2();
                totalamount();
            }
        }

        xhttp.open("POST", "<?php echo site_url('phygiotherapyController/add_more_phygiotherapy_row'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //                                    xhttp.send("fname=Henry&lname=Ford");
        xhttp.send('next_id=' + next_id);

    }

    function removetr(element, e) {
        var seq = $(element).attr('sequence');
        if (seq != 0)
            $(element).parent().parent().remove();

        totalamount();
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
        $("#total").val(total.toFixed(2));
        nettotalamount();
    }

    function nettotalamount() {
        var director_discount = $("#director_discount").val();
        var total = $("#total").val();
        var discount = $("#discount").val();
        var len = discount.length;
        var discountAmount = 0;
        if (discount[len - 1] == '%') {
            var disArray = discount.split('%');
            discountAmount = disArray[0];
            var discount = (total * discountAmount) / 100;
            var nettotal = total - discount - director_discount;
            $("#nettotal").val(nettotal.toFixed(2));
            $("#paid").val(nettotal.toFixed(2));
            var paid = $("#paid").val() * 1;
            $("#due_sale").val((nettotal - paid).toFixed(2));
            $('#total_discount').val(Number(discount) + Number(director_discount));
        } else {
            var nettotal = total - discount - director_discount;
            $("#nettotal").val(nettotal.toFixed(2));
            $("#paid").val(nettotal.toFixed(2));
            var paid = $("#paid").val() * 1;
            $("#due_sale").val((nettotal - paid).toFixed(2));
            $('#total_discount').val(Number(discount) + Number(director_discount));
        }
    }

    function getTotalAmount(element, e) {

        var seq = $(element).attr('sequence');
        var quantity = $("#quantity_" + seq).val();
        var price = $("#price_" + seq).val();
        $("#amount_" + seq).val(Number(quantity) * Number(price));
        totalamount();
    }

    function getamount(element, e) {

        var seq = $(element).attr('sequence');
        var discount = $("#discounteach_" + seq).val();
        //var discount = $("#discount").val();
        var len = discount.length;
        var discountAmount = 0;
        if (discount[len - 1] == '%') {
            var quantity = $("#quantity_" + seq).val();
            var price = $("#price_" + seq).val();
            var disArray = discount.split('%');
            discountAmount = disArray[0];
            var amount = $("#amount_" + seq).val();
            var discount = (quantity * price) * (discountAmount / 100);
            var amount = (quantity * price) - discount;
            $("#amount_" + seq).val(amount.toFixed(2));
            totalamount();
        } else {
            var quantity = $("#quantity_" + seq).val();
            var price = $("#price_" + seq).val();
            var amount = (price * quantity) - discount;
            $("#amount_" + seq).val(amount.toFixed(2));
            totalamount();

        }
    }

    function dueCal() {
        var paid = $("#paid").val() * 1;
        var nettotal = $("#nettotal").val() * 1;
        if (paid > nettotal) {
            alert('Paid amount can not be greater thn total amount');
            $('#paid').val(0);
            $('#due_sale').val(0);
        } else {
            var due = nettotal - paid;
            $("#due_sale").val(due.toFixed(2));
        }

    }

    function resetFn() {
        window.location.href = "<?php echo base_url('add-phygiotherapy') ?>";
    }

    function patient_data_set(patient_unique_id) {
        $('#img').show();
        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var patient = xhttp.responseText;
                var patient_array = patient.split('*');
                document.getElementById("ipd_patient_id").value = patient_array[0];
                document.getElementById("name").value = patient_array[1];
                document.getElementById("phone").value = patient_array[2];
                document.getElementById("age_year").value = patient_array[3];
                document.getElementById("age_month").value = patient_array[4];
                document.getElementById("age_day").value = patient_array[5];
                document.getElementById("address").value = patient_array[6];

                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('IpdPatientController/patient_data_load_by_unique_id'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("patient_unique_id=" + patient_unique_id);
    }
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Edit Physiotherapy</h3>
            <?php
            $phygiotherapy = $this->db->where('phygiotherapy_id', $phygiotherapy_id)->get('phygiotherapy')->row();
            $phygiotherapy_details = $this->db->where('phygiotherapy_id', $phygiotherapy_id)->get('phygiotherapy_details')->result();
            ?>
        </div>
        <div class="panel-body">
            <form id="phygiotherapy_data_entry_form" action="" method="POST" class="form">

                <input type="hidden" class="form-control" id="discount_reference_director_id" name="discount_reference_director_id" value="<?php echo $phygiotherapy->discount_reference_director_id ?>">
                <input type="hidden" class="form-control" id="discount_reference_doctor_id" name="discount_reference_doctor_id" value="<?php echo $phygiotherapy->discount_reference_doctor_id ?>">
                <input type="hidden" class="form-control" id="discount_reference_employee_id" name="discount_reference_employee_id" value="<?php echo $phygiotherapy->discount_reference_employee_id ?>">
                <input type="hidden" class="form-control" id="discount_reference_media_id" name="discount_reference_media_id" value="<?php echo $phygiotherapy->discount_reference_media_id ?>">

                <input type="hidden" id="phygiotherapy_id" name="phygiotherapy_id" class="form-control" value="<?php echo $phygiotherapy_id ?>" />
                <fieldset style="background-color:whitesmoke;">
                    <legend>Personal Info</legend>
                    <div class="row" style="margin-top:20px;">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="pwd">Date</label>
                                <div class="col-sm-4">
                                    <input type="text" value="<?php echo date('Y-m-d', strtotime($phygiotherapy->date)); ?>" id="datepicker" name="date" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Time </label>
                                <div class="col-sm-8">
                                    <?php date_default_timezone_set('Asia/Dhaka'); ?>
                                    <input type="text" class="form-control" id="emergency_time"
                                        value="<?php echo !empty($phygiotherapy->physiotherapy_time) ? $phygiotherapy->physiotherapy_time : date('h:i:s A'); ?>"
                                        name="emergency_time">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="pwd">Patient Id</label>
                                <div class="col-sm-8">
                                    <input type="hidden" class="form-control" id="ipd_patient_id" name="ipd_patient_id" value="<?php echo $phygiotherapy->ipd_patient_id ?>">
                                    <input type="text" placeholder="Scan of Type Patient Id.." value="<?php echo $phygiotherapy->patient_unique_id ?>" class="form-control" id="patient_unique_id" name="patient_unique_id">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Name*</label>
                                <div class="col-sm-8">
                                    <input type="text" placeholder="Enter Name" value="<?php echo $phygiotherapy->name ?>" required="" id="name" name="name" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="name">Age</label>
                                <div class="col-sm-3">
                                    <input type="text" placeholder="Year" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $phygiotherapy->age_year ?>" id="age_year" name="age_year">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" placeholder="Month" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $phygiotherapy->age_month ?>" id="age_month" name="age_month">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" placeholder="Day" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $phygiotherapy->age_day ?>" id="age_day" name="age_day">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Gender*</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?php echo $phygiotherapy->gender == 'Male' ? "selected" : "" ?>>Male</option>
                                        <option value="Female" <?php echo $phygiotherapy->gender == 'Female' ? "selected" : "" ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Phone*</label>
                                <div class="col-sm-8">
                                    <input type="text" oninput="validatePhoneNumberInput(this)" placeholder="Enter Phone" value="<?php echo $phygiotherapy->phone ?>" id="phone" name="phone" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="name">Address</label>
                                <div class="col-sm-9">
                                    <textarea placeholder="Enter Address" type="text" id="address" name="address" class="form-control"><?php echo $phygiotherapy->address ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Attendant</label>
                                <div class="col-sm-8">
                                    <input type="text" placeholder="Enter Attendant" value="<?php echo $phygiotherapy->attendant ?>" id="attendant" name="attendant" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:30px;">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Duty Doctor</label>
                                <div class="col-sm-8">
                                    <select type="text" class="form-control" id="doctor_id" name="doctor_id">
                                        <option selected="" value="" disabled="">Select Duty Doctor</option>
                                        <?php
                                        $doctor = $this->db->select('*')->get('doctor')->result();
                                        foreach ($doctor as $value) {
                                        ?>
                                            <option <?php echo $phygiotherapy->doctor_id == $value->doctor_id ? "selected" : "" ?> value="<?php echo $value->doctor_id; ?>"><?php echo $value->doctor_name . '-' . $value->doctor_unique_id; ?></option>
                                        <?php
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="name">Duty Nurse</label>
                                <div class="col-sm-9">
                                    <select type="text" class="form-control" id="employee_nurse_id" name="employee_nurse_id">
                                        <option selected="" value="" disabled="">Select Duty Nurse</option>
                                        <?php
                                        $nurses = getAllNurses();

                                        foreach ($nurses as $value) {
                                        ?>
                                            <option <?php echo $phygiotherapy->employee_nurse_id == $value->employee_id ? "selected" : "" ?> value="<?php echo $value->employee_id; ?>"><?php echo $value->employee_name . '-' . $value->employee_unique_id; ?></option>
                                        <?php
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Invoice No</label>
                                <div class="col-sm-8">
                                    <input type="text" id="phygiotherapy_invoice_no" name="phygiotherapy_invoice_no" class="form-control" readonly="" value="<?php echo $phygiotherapy->phygiotherapy_invoice_no ?>" />

                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 main">
                            <div class="table-responsive">
                                <table>
                                    <tr>
                                        <input id="total_control" value="" type="hidden">
                                        <td colspan="4" style="padding:5px;">

                                            <table width="100%" class="table-responsive table table-bordered table-striped " id="cart_tab1">
                                                <tr>
                                                    <th style="padding-left:25px;width:240px;text-align: center;">
                                                        Service Name
                                                    </th>

                                                    <th id="sale_rate_title" style="padding-left:10px;width: 190px;text-align: center;">
                                                        Price
                                                    </th>

                                                    <th style="padding:5px;padding:5px;width: 200px;text-align: center;">
                                                        Quantity
                                                    </th>
                                                    <th style="padding:5px;padding:5px;width: 210px;text-align: center;">
                                                        Discount(%)
                                                    </th>
                                                    <th style="padding:5px;padding:5px;width: 170px;text-align: left;">
                                                        Amount
                                                    </th>
                                                    <th style="padding:5px;">
                                                        &nbsp;
                                                    </th>
                                                </tr>
                                                <input type="hidden" value="<?php echo count($phygiotherapy_details) ?>" name="id_control" id="id_control" class="form-control">
                                                <?php
                                                $id = 0;
                                                foreach ($phygiotherapy_details as $phygiotherapy_details_value) {
                                                ?>
                                                    <tr>
                                                        <td style="padding:5px;">
                                                            <select name="phygiotherapy_service_id[]" class="form-control" id="phygiotherapy_service_id_<?php echo $id ?>" sequence=<?php echo $id ?> onchange="phygiotherapy_service_price_load(this.id)" required="" style="width:150px;">
                                                                <option value="" selected="">Select Service*</option>
                                                                <?php
                                                                $phygiotherapy_services = $this->db->select('*')->get('phygiotherapy_service')->result();

                                                                foreach ($phygiotherapy_services as $ES_value) {
                                                                ?>
                                                                    <option <?php echo $phygiotherapy_details_value->phygiotherapy_service_id == $ES_value->phygiotherapy_service_id ? "selected" : "" ?> value="<?php echo $ES_value->phygiotherapy_service_id ?>"><?php echo $ES_value->name ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td style="padding:5px;">
                                                            <input readonly type="text" value="<?php echo $phygiotherapy_details_value->price ?>" id="price_<?php echo $id ?>" name="price[]" class="form-control" sequence=<?php echo $id ?> required="" onkeyup="getamount(this, event)">
                                                        </td>
                                                        <td style="padding:5px;">
                                                            <input type="text" value="<?php echo $phygiotherapy_details_value->quantity ?>" onkeydown="validateNumberInput(event)" id="quantity_<?php echo $id ?>" name="quantity[]" oninput="getTotalAmount(this, event)" class="form-control" sequence=<?php echo $id ?> onkeyup="getamount(this, event)" required="" onkeyup="getamount(this, event)">
                                                        </td>
                                                        <td style="padding:5px;">
                                                            <input type="text" value="<?php echo $phygiotherapy_details_value->discounteach ?>" onkeydown="validateNumberInput(event)" id="discounteach_<?php echo $id ?>" name="discounteach[]" class="form-control" sequence=<?php echo $id ?> onkeyup="getamount(this, event)">
                                                        </td>

                                                        <td style="padding:5px;">
                                                            <input type="text" value="<?php echo $phygiotherapy_details_value->amount ?>" id="amount_<?php echo $id ?>" name="amount[]" class="form-control amount" readonly="" sequence=<?php echo $id ?>>
                                                        </td>
                                                        <?php
                                                        if ($id == 0) {
                                                        ?>

                                                            <td> <input type="button" onclick="addMore()" type="button" id="add_more" style="width:50px" id="add_more_<?php echo $id ?>" title="Click TO Remove" value="+"></td>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <td><input type="button" onclick="removetr(this, event)" style="width:50px" readonly id="add_more_<?php echo $id ?>" title="Click TO Remove" value="-">

                                                            <?php
                                                        }
                                                            ?>
                                                    </tr>
                                                <?php
                                                    $id++;
                                                }
                                                ?>
                                            </table>

                                        </td>
                                    </tr>

                                </table>



                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                        <fieldset style="background-color:whitesmoke;">
                    <legend>Reference</legend>
                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Reference Director</label>
                                <div class="col-sm-8">
                                    <select onchange="director_discount_load()" type="text" class="form-control" id="reference_director_id" name="reference_director_id">
                                        <option selected="" disabled="" value="">Select Reference Director</option>
                                        <?php
                                        $directors = $this->db->select('*')->get('director')->result();
                                        foreach ($directors as $director) {
                                        ?>
                                            <option <?php echo $phygiotherapy->reference_director_id == $director->director_id ? "selected" : "" ?> value="<?php echo $director->director_id ?>"><?php echo $director->name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>

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
                                            <option <?php echo $phygiotherapy->reference_doctor_id == $doctor_value->doctor_id ? "selected" : "" ?> value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->doctor_name.' - '.$doctor_value->doctor_unique_id ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Reference Media</label>
                                <div class="col-sm-8">
                                    <select type="text" class="form-control" id="reference_media_id" name="reference_media_id">
                                        <option selected="" value="">Select Reference Media</option>
                                        <?php
                                        $reference_media = $this->db->select('*')->get('reference_media')->result();
                                        foreach ($reference_media as $reference_media_value) {
                                        ?>
                                            <option <?php echo $phygiotherapy->reference_media_id == $reference_media_value->reference_media_id ? "selected" : "" ?> value="<?php echo $reference_media_value->reference_media_id ?>"><?php echo $reference_media_value->reference_media_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Reference Officer</label>
                                <div class="col-sm-8">
                                    <select style="width:100% ;" type="text" class="form-control" id="reference_employee_id" name="reference_employee_id">
                                        <option selected="" value="">Select Employee</option>
                                        <?php
                                        $employees = $this->db->select('*')->get('employee')->result();
                                        foreach ($employees as $employee) {
                                        ?>
                                            <option <?php echo $phygiotherapy->reference_employee_id == $employee->employee_id ? "selected" : "" ?> value="<?php echo $employee->employee_id; ?>"><?php echo $employee->employee_name . '-' . $employee->employee_unique_id ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                        </div>
                        <div class="col-md-4">
                            <table>
                                <tr>
                                    <td colspan="4" align="right" valign="middle" style="padding:5px;">
                                        <b>Total:&nbsp;</b>
                                        <input type="text" readonly="" value="<?php echo $phygiotherapy->total ?>" name="total" id="total" class="form-control" style="width:200px;float: right;" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="right" valign="middle" id="director_discount_container">
                                        <b>D.Discount(<span id="director_discount_percentage_show"></span>)%</b>
                                        <input style="width:200px;float: right;" type="text" name="director_discount" value="<?php echo $phygiotherapy->director_discount ?>" id="director_discount" class="form-control">
                                        <input type="hidden" value="<?php echo $phygiotherapy->director_discount_percentage ?>" name="director_discount_percentage" id="director_discount_percentage">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="right" valign="middle" style="padding:5px;">
                                        <b>Discount(%):&nbsp;</b>
                                        <input type="text" name="discount" value="<?php echo $phygiotherapy->discount ?>" id="discount" class="form-control" style="width:200px;float: right;" value="0" onkeyup="totalamount()">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="right" valign="middle" style="padding:5px;">
                                        <b>Total Discount</b>
                                        <input type="text" style="width:200px;float: right;" readonly class="form-control" value="<?php echo $phygiotherapy->total_discount ?>" id="total_discount" placeholder="" name="total_discount">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding:5px;">
                                        <b>Dis. Reference</b>
                                        <input type="text" style="width:200px;float: right;" class="form-control" value="<?php echo $phygiotherapy->discount_reference ?>" id="discount_reference" placeholder="Enter Discount Ref.." name="discount_reference">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="right" valign="middle" style="padding:5px;">
                                        <b>Net Total:&nbsp;</b>
                                        <input type="text" readonly="" name="nettotal" value="<?php echo $phygiotherapy->nettotal ?>" id="nettotal" class="form-control" style="width:200px;float: right;" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="right" valign="middle" style="padding:5px;">
                                        <b>Paid:&nbsp;</b>
                                        <input type="text" name="paid" id="paid" value="<?php echo $phygiotherapy->paid ?>" oninput="dueCal()" required="" class="form-control" style="width:200px;float: right;" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="right" valign="middle" style="padding:5px;">
                                        <b>Due:&nbsp;</b>
                                        <input type="text" name="due" id="due_sale" value="<?php echo $phygiotherapy->due ?>" readonly="" class="form-control" style="width:200px;float: right;" value="0">
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4" align="right" style="padding:5px;">
                                        <img src="<?php echo base_url() ?>images/ajax-loader.gif" id="img" style="display:none" />
                                        <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Update</button>&nbsp;

                                    </td>
                                </tr>
                            </table>
                        </div>


                    </div>
                </div>
            </form>
        </div>
    </div>
</div>