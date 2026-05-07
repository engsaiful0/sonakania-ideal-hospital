<?php
$permissions = $this->session->userdata('permissions');
error_reporting(0);
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">View Medicine</h3>
    </div>
    <div class="panel-body" style="width: 100%;">

        <script>
            $(document).ready(function() {

                $("#drug_name").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('DrugController/drug_name_load'); ?>",
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
                        $('#drug_name').val(ui.item.label);
                        $('#view_drug_form').submit(); // Automatically submit the form
                        return false;
                    }
                });

              
            });

            function supplier_set() {
                var supplier = document.getElementById('manufacture_id').value;
                // alert(supplier);

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        document.getElementById("drug").innerHTML = xhttp.responseText;
                        // alert(xhttp.responseText);
                    }
                }

                xhttp.open("POST", "<?php echo site_url('product/supplierget'); ?>", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                //                                    xhttp.send("fname=Henry&lname=Ford");
                xhttp.send("supplier=" + supplier);
            }

            function drug_name_load(type_name) {
                var xhttp = new XMLHttpRequest();
                var data = type_name.split("_");
                var type_name_val = document.getElementById(type_name).value;
                xhttp.onreadystatechange = function() {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        document.getElementById("drug_id").innerHTML = xhttp.responseText;
                    }
                }
                xhttp.open("POST", "<?php echo site_url('ProductController/add_drug_name'); ?>", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                xhttp.send("type_name_val=" + type_name_val);
            }
            $(document).ready(function() {
                $("#manufacturer_id").select2();
                $("#drug_id").select2();
                $("#drug_type_id").select2();
                
                $('#per_page').select2();

            });

            function deleteDrug(drug_id, row_id) {
                Swal.fire({
                    title: 'Do you want to delete this?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "<?php echo site_url('DrugController/drug_delete_ajax'); ?>",
                            type: 'POST',
                            data: {
                                drug_id: drug_id
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

                                    $('#' + row_id).remove();
                                } else {
                                    $.toast({
                                        heading: 'Error',
                                        text: res.message,
                                        showHideTransition: 'slide',
                                        position: 'top-right',
                                        hideAfter: 2000,
                                        icon: 'error'
                                    });
                                }
                            }
                        });
                    }
                });
            }
        </script>
        <script>
            // Update a single row's opening stock value
            function updateOpeningStock(drug_id) {
                var stock_value = $('#opening_stock_' + drug_id).val(); // Get the updated stock value

                if (isNaN(stock_value) || stock_value < 0) {
                    $.toast({
                        heading: 'Error',
                        text: 'Please enter a valid positive number for stock.',
                        showHideTransition: 'slide',
                        position: 'top-right',
                        hideAfter: 2000,
                        icon: 'error'
                    });
                    return;
                }

                $.ajax({
                    url: "<?php echo site_url('DrugController/update_opening_stock_ajax'); ?>",
                    type: "POST",
                    data: {
                        drug_id: drug_id,
                        stock_value: stock_value
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
                        } else {
                            $.toast({
                                heading: 'Error',
                                text: res.message,
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 2000,
                                icon: 'error'
                            });
                        }
                    },
                    error: function() {
                        $.toast({
                            heading: 'Error',
                            text: 'Something went wrong. Please try again.',
                            showHideTransition: 'slide',
                            position: 'top-right',
                            hideAfter: 2000,
                            icon: 'error'
                        });
                    }
                });
            }

            // Update selected rows' opening stock values
            function updateSelectedStocks() {
                var selectedRows = [];
                var submitBtn = $('#submit_button'); // Define the button reference

                $('input[name="selected_drugs[]"]:checked').each(function() {
                    var drug_id = $(this).val();
                    var stock_value = $('#opening_stock_' + drug_id).val();

                    if (isNaN(stock_value) || stock_value < 0) {
                        $.toast({
                            heading: 'Error',
                            text: 'Please enter a valid positive number for stock.',
                            showHideTransition: 'slide',
                            position: 'top-right',
                            hideAfter: 2000,
                            icon: 'error'
                        });
                        return false; // Exit each() loop
                    }

                    selectedRows.push({
                        drug_id: drug_id,
                        stock_value: stock_value
                    });
                });

                if (selectedRows.length === 0) {
                    $.toast({
                        heading: 'Error',
                        text: 'Please select at least one row to update.',
                        showHideTransition: 'slide',
                        position: 'top-right',
                        hideAfter: 2000,
                        icon: 'error'
                    });
                    return;
                }

                // If using jQuery Validation
                if ($("#view_drug_form").valid && !$("#view_drug_form").valid()) {
                    return;
                }

                // Disable form and show loading
                $('#view_drug_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    url: "<?php echo site_url('DrugController/update_selected_opening_stock_ajax'); ?>",
                    type: "POST",
                    data: {
                        selectedRows: selectedRows
                    },
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status === 'success') {
                            $.toast({
                                heading: 'Success',
                                text: res.message,
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });
                        } else {
                            $.toast({
                                heading: 'Error',
                                text: res.message,
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 2000,
                                icon: 'error'
                            });
                        }

                        // Re-enable form and button
                        $('#view_drug_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Update Selected Stocks');
                    },
                    error: function() {
                        $.toast({
                            heading: 'Error',
                            text: 'Something went wrong. Please try again.',
                            showHideTransition: 'slide',
                            position: 'top-right',
                            hideAfter: 2000,
                            icon: 'error'
                        });
                        // Re-enable form and button
                        $('#view_drug_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Update Selected Stocks');
                    }
                });
            }

            // Select all rows checkbox
            $('#select_all').on('change', function() {
                if ($(this).prop('checked')) {
                    $('input[name="selected_drugs[]"]').prop('checked', true);
                } else {
                    $('input[name="selected_drugs[]"]').prop('checked', false);
                }
            });
        </script>
        <script>
            $(document).ready(function() {
                // Handle Select All checkbox
                $('#select_all').on('change', function() {
                    $('.drug-checkbox').prop('checked', this.checked);
                });

                // Uncheck Select All if any individual checkbox is unchecked
                $('.drug-checkbox').on('change', function() {
                    if (!$(this).prop('checked')) {
                        $('#select_all').prop('checked', false);
                    } else if ($('.drug-checkbox:checked').length === $('.drug-checkbox').length) {
                        $('#select_all').prop('checked', true);
                    }
                });
                $('#select_all').on('change', function() {
                    $('.select_drug_for_stock_update').prop('checked', this.checked);
                });
            });
        </script>
        <!-- Search Form -->
        <?php if (in_array('pharmacy_drug_search', $permissions)) { ?>
            <form method="post" id="view_drug_form" action="<?php echo base_url() . "index.php/DrugController/view_drug"; ?>">
                <table style="margin-top:20px;" class="table table-bordered">
                    <tr>
                        <td>Per Page</td>
                        <td style="width:20%">Search by Medicine Name</td>
                        <td style="width:20%">Supplier</td>
                        <td style="width:20%">Type</td>
                        <td style="width:30%">Medicine</td>
                    </tr>
                    <tr>
                        <td>
                            <form method="post" action="<?php echo base_url() . "index.php/DrugController/view_drug"; ?>">
                                <select style="width: 100px;" class="form-control" name="per_page" id="per_page" onchange="this.form.submit()">
                                    <option value="10" <?php echo ($per_page == 10) ? 'selected' : ''; ?>>10</option>
                                    <option value="20" <?php echo ($per_page == 20) ? 'selected' : ''; ?>>20</option>
                                    <option value="50" <?php echo ($per_page == 50) ? 'selected' : ''; ?>>50</option>
                                    <option value="100" <?php echo ($per_page == 100) ? 'selected' : ''; ?>>100</option>
                                    <option value="200" <?php echo ($per_page == 200) ? 'selected' : ''; ?>>200</option>
                                    <option value="300" <?php echo ($per_page == 300) ? 'selected' : ''; ?>>300</option>
                                    <option value="500" <?php echo ($per_page == 500) ? 'selected' : ''; ?>>500</option>
                                    <option value="1000" <?php echo ($per_page == 1000) ? 'selected' : ''; ?>>1000</option>
                                    <option value="2000" <?php echo ($per_page == 2000) ? 'selected' : ''; ?>>2000</option>
                                    <option value="3000" <?php echo ($per_page == 3000) ? 'selected' : ''; ?>>3000</option>
                                    <option value="4000" <?php echo ($per_page == 4000) ? 'selected' : ''; ?>>4000</option>
                                    <option value="5000" <?php echo ($per_page == 5000) ? 'selected' : ''; ?>>5000</option>
                                </select>

                            </form>
                        </td>
                        <td><input placeholder="Type drug name.." autofocus type="text" class="form-control" name="drug_name" id="drug_name"></td>
                        <td>
                            <select class="form-control" id="manufacturer_id" name="manufacturer_id">
                                <option value="">All</option>
                                <?php foreach ($this->db->select('*')->order_by('name', 'ASC')->get('manufacturer')->result() as $value) { ?>
                                    <option value="<?php echo $value->manufacturer_id ?>"><?php echo $value->name ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <select name="drug_type_id" class="form-control" id="drug_type_id">
                                <option value="" selected="">Select Type</option>
                                <?php foreach ($this->db->select('*')->order_by('type_name', 'ASC')->get('drug_type')->result() as $value) { ?>
                                    <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td><select class="form-control" id="drug_id" style="width: 300px;" name="drug_id">
                                <option></option>
                            </select></td>
                        <td><input type="submit" class="btn btn-primary" value="Submit"></td>
                    </tr>
                </table>
            </form>
        <?php } ?>

        <!-- Display Table of Drugs -->
        <?php if (isset($detailsList) && !empty($detailsList)) : ?>


            <form method="POST" action="">
                <table class="table table-bordered table-striped">
                    <tr>
                        <?php if (in_array('pharmacy_drug_stock_manage', $permissions)) { ?>
                            <td><input type="checkbox" id="select_all"></td>
                        <?php } ?>
                        <td>Sl</td>
                        <!-- <td>Manufacturer</td> -->
                        <td>Medicine Name</td>
                        <!-- <td>Type</td> -->
                        <!-- <td>Strength</td> -->
                        <td>Purchase Rate</td>
                        <td>MRP</td>
                        <td>Shelf No</td>
                        <td>Opening Stock</td>
                        <td>Reorder Quantity</td>
                        <td>Status</td>
                        <?php if ($this->session->userdata('user_type') == 'Admin') { ?>
                            <td>User</td>
                        <?php } ?>
                        <?php if (in_array('pharmacy_drug_edit', $permissions)) { ?>
                            <td>Edit</td>
                        <?php } ?>
                        <?php if (in_array('pharmacy_drug_delete', $permissions)) { ?>
                            <td>Delete</td>
                        <?php } ?>
                    </tr>

                    <?php
                    $k = 1;
                    foreach ($detailsList as $drug) {
                        $manufacturer = getManufacturer($drug->manufacturer_id);
                        $drug_type = getDrugType($drug->drug_type_id);
                        $shelf = getShelf($drug->shelf_id);
                        $user = getUserById($drug->user_id);
                    ?>
                        <tr id="drug-row-<?php echo $drug->drug_id; ?>">
                            <?php if (in_array('pharmacy_drug_stock_manage', $permissions)) { ?>
                                <td><input class="select_drug_for_stock_update" type="checkbox" name="selected_drugs[]" value="<?php echo $drug->drug_id; ?>"></td>
                            <?php } ?>
                            <td><?php echo $k++; ?></td>
                            <!-- <td><?php echo $manufacturer->name; ?></td> -->
                            <td><?php echo getDrug($drug->drug_id)->drug_name; ?></td>
                            <!-- <td><?php echo $drug_type->type_name; ?></td> -->
                            <!-- <td><?php echo $drug->strength; ?></td> -->
                            <td><?php echo $drug->purchase_rate; ?></td>
                            <td><?php echo $drug->mrp; ?></td>
                            <td><?php echo $shelf->shelf_number; ?></td>
                            <td>
                                <?php if (in_array('pharmacy_drug_stock_manage', $permissions)) { ?>
                                    <table>
                                        <tr>
                                            <td>
                                                <input type="number" style="width: 60px;" id="opening_stock_<?php echo $drug->drug_id; ?>" name="opening_stock[<?php echo $drug->drug_id; ?>]" value="<?php echo $drug->opening_stock; ?>" min="0" class="form-control">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success" onclick="updateOpeningStock(<?php echo $drug->drug_id; ?>)">
                                                    <i class="fas fa-save"></i>
                                                </button>

                                            </td>
                                        </tr>
                                    </table>

                                <?php } else {
                                    echo $drug->opening_stock; // Display opening stock if not editable
                                } ?>
                            </td>
                            <td><?php echo $drug->reorder_quantity; ?></td>
                            <td><?php echo $drug->status; ?></td>
                            <?php if ($this->session->userdata('user_type') == 'Admin') { ?>
                                <td><?php echo $user->username; ?></td>
                            <?php } ?>
                            <?php if (in_array('pharmacy_drug_edit', $permissions)) { ?>
                                <td>
                                    <a class="btn btn-primary" href="<?php echo base_url("edit-drug/{$drug->drug_id}") ?>"><i class="glyphicon glyphicon-edit"></i></a>
                                </td>
                            <?php } ?>
                            <?php if (in_array('pharmacy_drug_delete', $permissions)) { ?>
                                <td><a onclick="deleteDrug(<?php echo $drug->drug_id; ?>, 'drug-row-<?php echo $drug->drug_id; ?>')" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </table>

                <!-- Button to update selected rows -->
                <button type="button" id="submit_button" class="btn btn-success" onclick="updateSelectedStocks()">Update Selected Stocks</button>
            </form>

        <?php endif; ?>

        <!-- Display Pagination -->
        <div class="pagination_links"><?php echo $pagination; ?></div>
    </div>
</div>