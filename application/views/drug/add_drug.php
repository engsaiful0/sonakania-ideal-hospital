<script>
    $(document).ready(function() {
        $('#manufacturer_id').select2();
        $('#drug_type_id').select2();
        $('#shelf_id').select2();
        $('#status').select2();

    });

    function check_duplicate_drug_name(drug_name) {
        $('#img').show(); // Show the loading image or spinner

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var response = JSON.parse(xhttp.responseText);
                if (response.length > 0) {
                    // If duplicate found, clear input, focus, and show message
                    document.getElementById('drug_name').value = ''; // Clear input field
                    document.getElementById('drug_name').focus(); // Set focus back on input
                    document.getElementById('dulicate_drug_name_notify').innerText = 'Duplicate drug name found! Please enter a unique name.';
                } else {
                    document.getElementById('dulicate_drug_name_notify').innerText = ''; // Clear message if no duplicate
                }
                $('#img').hide(); // Hide the loading image or spinner
            }
        };

        xhttp.open("POST", "<?php echo site_url('DrugController/check_duplicate_drug_name'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("drug_name=" + encodeURIComponent(drug_name)); // Send the drug name in the request
    }


    $(document).ready(function() {
        // Validate the form
        $("#drug_entry_form").validate({
            rules: {
                manufacturer_id: "required",
                drug_type_id: "required",
                drug_name: "required",
                mrp: "required",
                purchase_rate: "required",
            },
            messages: {
                manufacturer_id: "Select Manufacturer",
                drug_type_id: "Select Drug Type",
                drug_name: "Enter name",
                mrp: "Enter mrp",
                purchase_rate: "Enter purchase rate",

            }
        });

        // On form submission
        $('#submit_button').click(function(e) {
            e.preventDefault();
            var submitBtn = $(this);
            var formData = $('#drug_entry_form').serialize();

            // Check if the form is valid
            if ($("#drug_entry_form").valid()) {
                $('#drug_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('DrugController/add_drug_data_save'); ?>",
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
                            $('#drug_entry_form')[0].reset();
                            $('#drug_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');

                        } else {
                            alert('Error: ' + response.message);
                            $('#drug_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#drug_entry_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add Medicine</h3>
        </div>
        <div class="panel-body">

            <form class="form-horizontal" id="drug_entry_form" method="post" enctype='multipart/form-data'>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Manufacturer *</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="manufacturer_id" name="manufacturer_id">
                                    <?php
                                    $man = $this->db->select('*')->order_by('name', 'ASC')->get('manufacturer')->result();
                                    ?>
                                    <option value="">Select Manufacturer</option>
                                    <?php
                                    foreach ($man as $value) {
                                    ?>
                                        <option value="<?php echo $value->manufacturer_id ?>"><?php echo $value->name ?></option>
                                    <?php
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row" style="margin-top:20px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Medicine Type *</label>
                            <div class="col-sm-8">
                                <select name="drug_type_id" class="form-control" id="drug_type_id">
                                    <option value="" selected="">Select Type</option>
                                    <?php
                                    $sql = $this->db->select('*')->order_by('type_name', 'ASC')->get('drug_type')->result();

                                    foreach ($sql as $value) {
                                    ?>
                                        <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Medicine Name *</label>
                            <div class="col-sm-8">
                                <input type="text" onblur="check_duplicate_drug_name(this.value)" class="form-control" placeholder="Enter medicine name" id="drug_name" name="drug_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none;z-index:1" />
                            <p id="dulicate_drug_name_notify" style="color: red;"></p> <!-- Error message will be shown here -->
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px;display:none">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Strength</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" placeholder="Enter Strength" id="strenght" name="strenght">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <p id="dulicate_drug_name_notify" style="color: red;"></p> <!-- Error message will be shown here -->
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px;display:none">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Shelf</label>
                            <div class="col-sm-8">
                                <select name="shelf_id" class="form-control" id="shelf_id">
                                    <option value="" selected="">Select Type</option>
                                    <?php
                                    $shelfs = $this->db->select('*')->order_by('shelf_number', 'ASC')->get('shelfs')->result();
                                    foreach ($shelfs as $value) {
                                    ?>
                                        <option value="<?php echo $value->shelf_id ?>"><?php echo $value->shelf_number ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row" style="margin-top:20px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Purchase Rate *</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Enter Purchase Rate" class="form-control" id="purchase_rate" name="purchase_rate">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row" style="margin-top:20px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">MRP *</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Enter MRP" class="form-control" id="mrp" name="mrp">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row" style="margin-top:20px;display:none">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Whole Sale Rate</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Enter whole sale rate" class="form-control" id="whole_sale_rate" name="whole_sale_rate">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row" style="margin-top:20px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Opening Stock</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Enter opening stock" class="form-control" id="opening_stock" name="opening_stock">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Reorder Quantity</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Enter reorder quantity" class="form-control" id="reorder_quantity" name="reorder_quantity">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top:20px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Status *</label>
                            <div class="col-sm-8">
                                <select name="status" class="form-control" id="status">
                                    <option>Active</option>
                                    <option>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-3">
                                <button id="submit_button" name="submit_button" type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </div>

                    </div>
                </div>


            </form>

        </div>
    </div>
</div>