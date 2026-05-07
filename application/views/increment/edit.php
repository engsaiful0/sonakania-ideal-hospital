<script>
    $(document).ready(function() {
     
        $('#employee_id').select2();
    });

    $(document).ready(function() {
        // Validate the form
        $("#issue_entry_form").validate({
            rules: {
                employee_id: "required",
                amount: "required",
                date: "required",
            },
            messages: {
                employee_id: "Please select an employee",
                amount: "Please enter increment amount",
                date: "Please select a valid date",              
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {

            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#increment_entry_form').serialize();

            // Check if the form is valid
            if ($("#increment_entry_form").valid()) {
                $('#increment_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('IncrementController/update_increment_data'); ?>",
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
                            $('#increment_entry_form')[0].reset();
                            $('#increment_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            window.location.href = "<?php echo base_url('view-increment') ?>";
                        } else {
                            alert('Error: ' + response.message);
                            $('#increment_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#increment_entry_form :input').prop('disabled', false);
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
            <h3 style="text-align: center">Update Increment</h3>
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
             <?php
        $increment = $this->db->where('increment_id', $increment_id)->get('increment')->row();
        
        ?>
            <form id="increment_entry_form" class="form-horizontal" method="post" enctype='multipart/form-data'>
            <input type="hidden" id="issue_id" name="increment_id" class="form-control" value="<?php echo $increment_id ?>" />
             
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Employee *</label>
                            <div class="col-sm-8">
                                <select id="employee_id" name="employee_id" class="form-control">
                                    <option value="" disabled="">Select Employee</option>
                                    <?php
                                    $employes = $this->db->select('*')->get('employee')->result();
                                    foreach ($employes as $employe) {
                                    ?>
                                        <option <?php $employe->employee_id==$increment->employee_id?"selected":""?>  value="<?php echo $employe->employee_id  ?>"><?php echo $employe->employee_name . '-' . $employe->employee_unique_id ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Increment Amount *</label>
                            <div class="col-sm-8">
                                <input type="text" oninput="validateInputFloatingPoint(this)" class="form-control" value="<?php echo $increment->amount ?>"  placeholder="Enter Increment Amount" id="amount" name="amount">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Remark</label>
                            <div class="col-sm-8">
                                <textarea type="text" class="form-control" placeholder="Enter Remark" value="" id="remark" name="remark"><?php echo $increment->remark ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Date *</label>
                            <div class="col-sm-8">
                                <input type="text" readonly required="" value="<?php echo date('d-m-Y',strtotime($increment->date)); ?>" class="form-control" id="date" name="date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Update</button>
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
