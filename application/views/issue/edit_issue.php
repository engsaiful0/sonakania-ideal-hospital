<script type="text/javascript">
    function available_quantity_load(item_id) {

        $('#img').show();
        var id_no = item_id.split("_");
        var item_id_value = $('#item_id_' + id_no[2]).val();
        var idIndex = id_no[2];
        // Check for duplicates
        var isDuplicate = false;

        $('select[name="item_id[]"]').each(function() {
            console.log("Comparing: ", $(this).val(), " with ", item_id_value);
            if ($(this).val() === item_id_value && $(this).attr('id') !== item_id) {
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
                text: "This item is already selected. Please choose a different one.",
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 2000,
                icon: 'error'
            });

            // Reset the dropdown
            $('#item_id_' + idIndex).val('').trigger('change');
           // return; // Stop further execution
        } else {
            // alert(id_no[2]);
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                    document.getElementById("available_quantity_" + id_no[2]).value = xhttp.responseText;
                    $('#issue_quantity_' + id_no[2]).focus();
                    $('#img').hide();
                }
            }
            //  alert(xhttp.responseText);
            xhttp.open("POST", "<?php echo site_url('IssueController/available_quantity_load'); ?>", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            //            xhttp.send("fname=Henry&lname=Ford");
            xhttp.send("item_id=" + item_id_value);
        }

    }
</script>
<script>
    $(document).ready(function() {
        $('#item_id_1').select2();
        $('#employee_id').select2();
        $('#department_id').select2();
    });

    function load_product_row() {
        $('#img').show();
        var id = document.getElementById("idControl").value * 1;


        document.getElementById("idControl").value = id + 1;
        id = Number(id) + 1;
        // alert(id);

        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {

                var newdiv = document.createElement('tr');
                newdiv.innerHTML = xhttp.responseText;
                document.getElementById('product_table').appendChild(newdiv);
                for (var i = 2; i <= id; i++) {
                    $('#item_id_' + i).select2();
                }

                $('#img').hide();
            }
        }

        xhttp.open("POST", "<?php echo site_url('IssueController/load_product_row'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("next_id=" + id);
    }

    function removetr(element, e) {
        var seq = $(element).attr('sequence');
        if (seq != 0)
            $(element).parent().parent().remove();

        getTotalQuantity();
    }

    function getTotalQuantity() {
        var issue_quantity = $(".issue_quantity");
        total = 0;
        total = Number(total);
        $.each(issue_quantity, function(k, elm) {
            var cquantity = $(elm).val();
            cquantity = Number(cquantity);
            total += cquantity;
        });
        $("#total_quantity").val(total);

    }
    $(document).ready(function() {
        // Validate the form
        $("#issue_entry_form").validate({
            rules: {
                employee_id: "required",
                purpose: "required",
                item_id_1: "required",
                issue_quantity_1: "required",
            },
            messages: {
                employee_id: "Please Select a Concern By",
                purpose: "Please Enter Purpose",
                item_id_1: "Please Select An Item",
                issue_quantity_1: "Select Enter Issue Quantity",
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {

            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#issue_entry_form').serialize();

            // Check if the form is valid
            if ($("#issue_entry_form").valid()) {
                $('#issue_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('IssueController/update_issue_data'); ?>",
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
                            $('#issue_entry_form')[0].reset();
                            $('#issue_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            window.location.href = "<?php echo base_url('print-issue') ?>";
                        } else {
                            alert('Error: ' + response.message);
                            $('#issue_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#issue_entry_form :input').prop('disabled', false);
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
            <h3 style="text-align: center">Edit Issue Entry</h3>
        </div>
        <?php
        $issue = $this->db->where('issue_id', $issue_id)->get('issue')->row();
        $issue_details = $this->db->where('issue_id', $issue_id)->get('issue_details')->result();
        ?>
        <div class="panel-body">

            <form id="issue_entry_form" class="form-horizontal" method="post" enctype='multipart/form-data'>
                <input type="hidden" id="issue_id" name="issue_id" class="form-control" value="<?php echo $issue_id ?>" />
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Concern By *</label>
                            <div class="col-sm-8">
                                <select type="text" class="form-control" id="employee_id" name="employee_id">
                                    <option selected="" value="" disabled="">Select Concern</option>
                                    <?php
                                    $employees = $this->db->select('*')->get('employee')->result();
                                    foreach ($employees as $value) {
                                    ?>
                                        <option <?php echo $value->employee_id == $issue->employee_id ? "selected" : "" ?> value="<?php echo $value->employee_id; ?>"><?php echo $value->employee_name . '-' . $value->mobile; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Purpose *</label>
                            <div class="col-sm-8">
                                <input type="text" value="<?php echo $issue->purpose ?>" class="form-control" placeholder="Enter Purpose" id="purpose" name="purpose">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Issue No</label>
                            <div class="col-sm-8">
                                <input type="text" value="<?php echo $issue->issue_no ?>" readonly="" class="form-control" id="issue_no" name="issue_no">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Date and Time *</label>
                            <div class="col-sm-8">
                                <input type="text" readonly required="" value="<?php echo $issue->date; ?>" class="form-control" id="" name="date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Department</label>
                            <div class="col-sm-8">
                                <select type="text" class="form-control"  id="department_id" name="department_id">
                                    <option selected="" disabled="" value="">Select Department</option>
                                    <?php
                                    $department = $this->db->select('*')->order_by('department_name', 'ASC')->get('department')->result();
                                    foreach ($department as $department_value) {
                                    ?>
                                        <option <?php echo $issue->department_id == $department_value->department_id ? "selected" : "" ?> value="<?php echo $department_value->department_id ?>"><?php echo $department_value->department_name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="form-group">
                    <div class="col-sm-12">
                        <table id="product_table" class="table table-bordered table-hover table-striped">
                            <input type="hidden" id="idControl" value="1">
                            <input type="hidden" id="current_id" value="1">
                            <tr id="1">
                                <td style="width:30%">Item Name *</td>
                                <td style="width:30%">Available Quantity *</td>
                                <td style="width:30%">Issue Quantity *</td>
                            </tr>
                            <input type="hidden" value="<?php echo count($issue_details) ?>" name="id_control" id="id_control" class="form-control">
                            <?php
                            $id = 1;
                            foreach ($issue_details as $issue_detail_value) {
                            ?>
                                <tr>
                                    <td>
                                        <select style="width:100%" type="text" required="" class="form-control" onchange="available_quantity_load(this.id)" id="item_id_1" sequence=0 name="item_id[]">
                                            <option selected="" value="" disabled="">Select Item</option>
                                            <?php
                                            $items = $this->db->select('*')->order_by('item_name', 'ASC')->get('item')->result();
                                            foreach ($items as $value) {
                                            ?>
                                                <option <?php echo $value->item_id == $issue_detail_value->item_id ? "selected" : "" ?> value="<?php echo $value->item_id; ?>"><?php echo $value->item_name; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input readonly style="width:100%" type="text" required="" value="<?php echo $issue_detail_value->issue_no ?>" class="form-control" id="available_quantity_1" placeholder="Available Quantity" sequence=0 name="available_quantity[]">
                                    </td>
                                    <td>
                                        <input style="width:100%"  type="number" required="" value="<?php echo $issue_detail_value->issue_quantity ?>" class="form-control issue_quantity" id="issue_quantity_1" oninput="getTotalQuantity(this, event)" placeholder="Issue Quantity" sequence=0 name="issue_quantity[]">
                                    </td>
                                    <?php
                                    if ($id == 1) {
                                    ?>

                                        <td><input style="width: 40px;" type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more" title="Click To Add" value="+"></td>
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
                            </tr>
                        </table>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />

                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="pwd">Total Quantity*</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="<?php echo $issue->total_quantity; ?>" required="" readonly="" id="total_quantity" name="total_quantity">
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

</div><?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
