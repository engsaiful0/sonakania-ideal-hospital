<script type="text/javascript">
    function available_quantity_load(item_id) {
        $('#img').show();
        var id_no = item_id.split("_");
        var item_id = $('#item_id_' + id_no[2]).val();
        // alert(id_no[2]);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("available_quantity_" + id_no[2]).value = xhttp.responseText;
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('IssueController/available_quantity_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("item_id=" + item_id);

    }
</script>
<script>
    $(document).ready(function() {
        $('#user_id').select2();

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
                total_quantity: "required",

                issue_quantity_1: "required",
            },
            messages: {
                employee_id: "Please Select a Concern By",
                total_quantity: "Please enter quantity",
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
                    url: "<?php echo base_url('IssueController/save_issue_data'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
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
            <h3 style="text-align: center">User Role</h3>
        </div>

        <div class="panel-body">
            <?php
            if ($this->session->userdata('success') != '') {
            ?>
                <div class="alert alert-success">
                    <strong>Success!</strong>Data has been saved successfully.
                </div>
            <?php
                $sdata['success'] = '';
                $this->session->set_userdata($sdata);
            }
            ?>
            <form id="role_entry_form" class="form-horizontal" method="post" enctype='multipart/form-data'>
             
                <input type="hidden" readonly="" class="form-control" value="<?php echo $issue_invoice->issue_invoice_serial + 1 ?>" id="issue_invoice_serial" name="issue_invoice_serial">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">User *</label>
                            <div class="col-sm-8">
                                <select type="text" class="form-control" id="user_id" name="user_id">
                                    <option selected="" value="" disabled="">Select User</option>
                                    <?php
                                    $users = $this->db->select('*')->get('user')->result();
                                    foreach ($users as $value) {
                                    ?>
                                        <option value="<?php echo $value->user_id; ?>"><?php echo $value->user_name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd"></label>
                            <div class="col-sm-8">
                                <input type="checkbox"> Set All Permissions
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset>
                            <legend>HRM</legend>
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <td class="bg-primary">Employee</td>
                                    <td>
                                        <input type="checkbox"> Add Employee
                                    </td>
                                    <td>
                                        <input type="checkbox"> Edit Employee
                                    </td>
                                    <td>
                                        <input type="checkbox"> View Employee
                                    </td>
                                    <td>
                                        <input type="checkbox"> Print Employee
                                    </td>
                                    <td>
                                        <input type="checkbox"> Delete Employee
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Increment</td>
                                    <td>
                                        <input type="checkbox"> Add Increment
                                    </td>
                                    <td>
                                        <input type="checkbox"> Edit Increment
                                    </td>
                                    <td>
                                        <input type="checkbox"> View Increment
                                    </td>
                                    <td>
                                        <input type="checkbox"> Print Increment
                                    </td>
                                    <td>
                                        <input type="checkbox"> Delete Increment
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Director</td>
                                    <td>
                                        <input type="checkbox"> Add Director
                                    </td>
                                    <td>
                                        <input type="checkbox"> Edit Director
                                    </td>
                                    <td>
                                        <input type="checkbox"> View Director
                                    </td>
                                    <td>
                                        <input type="checkbox"> Print Director
                                    </td>
                                    <td>
                                        <input type="checkbox"> Delete Director
                                    </td>
                                </tr>

                            </table>

                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
                    </div>
                    <div class="col-md-7">
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
