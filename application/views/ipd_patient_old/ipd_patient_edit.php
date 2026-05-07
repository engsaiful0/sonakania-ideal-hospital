
<script>
    $().ready(function () {
        // validate the comment form when it is submitted       
        // validate signup form on keyup and submit
        $("#patient_form").validate({
            rules: {

                patient_name: "required",

                mobile_number: {
                    required: true,
                    minlength: 11
                },
                gender: "required",

            },
            messages: {
                patient_name: "Please Enter Patient Name",
                mobile_number: {
                    required: "Please enter a valid mobile number",
                    minlength: "Your mobile number must consist of at least 13 characters"
                },
                gender: "Select Item Type",
            }
        });
    });
    $(document).ready(function () {
        $('#gender1').select2();
        $('#doctor_id1').select2();
        $('#reference_media_id1').select2();
        $('#general_bed_id1').select2();
        $('#cabin_id1').select2();

    });
    jQuery(document).ready(function () {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
            $(this).slideUp('slow', function () {
                $(this).remove();
            });
        });
    });
    $(function () {
        $('[name="admission_time"]').timeselector()
    });

    function total_price_cal()
    {
        var quantity = $('#quantity').val();
        var unit_price = $('#unit_price').val();
        $('#total_price').val(Number(quantity) * Number(unit_price));
    }
</script>


<div class="panel panel-primary" style="width: 100%;margin: 0 auto">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-10">
                <h3 style="text-align: center">Edit Patient</h3>
            </div>
            <div class="col-md-2">
                <a type="" style="color: white" href="" class="pull-right" data-dismiss="modal">X</a>
            </div>
        </div>

    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                    <?php
                    if ($this->session->userdata('success') != '') {
                        ?>                   
                        <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a> <strong>Success!</strong>Data has been saved successfully.</p>                      
                        <?php
                        $sdata['success'] = '';
                        $this->session->set_userdata($sdata);
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-12">
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
                $patient = $this->db->where('patient_id', $patient_id)->get('patient')->row();
                ?>
                <form class="form-horizontal" id="patient_form" method="post" action="<?php echo site_url('PatientController/edit_ipd_patient_save') ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Patient Name *</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" value="<?php echo $patient->patient_name ?>"  id="patient_name"   name="patient_name">
                                    <input type="hidden" class="form-control" value="<?php echo $patient_id ?>"  id="patient_id"   name="patient_id">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">General Bed </label>
                                <div class="col-sm-8">
                                    <select type="text" class="form-control" id="bed_id"  name="bed_id">

                                        <?php
                                        if ($patient->bed_id != '') {
                                            $bed = $this->db->where('bed_id', $patient->bed_id)->get('bed')->row();
                                            ?>
                                            <option value="<?php echo $bed->bed_id ?>"><?php echo $bed->bed_number . '-' . $bed->bed_rent ?></option>
                                            <?php
                                        }
                                        $beds = $this->db->where('status', 'Available')->get('bed')->result();
                                        foreach ($beds as $bed_value) {
                                            ?>
                                            <option value="<?php echo $bed_value->bed_id ?>"><?php echo $bed_value->bed_number . '-' . $bed_value->bed_rent ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>                               
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Mobile Number *</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control"  id="mobile_number"  value="<?php echo $patient->mobile_number ?>"  name="mobile_number">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Cabin </label>
                                <div class="col-sm-8">
                                    <select type="text" class="form-control" id="cabin_id1"  name="cabin_id">

                                        <?php
                                        if ($patient->cabin_id != '') {
                                            $cabin = $this->db->where('cabin_id', $patient->cabin_id)->get('cabin')->row();
                                            $cabin_category = $this->db->where('cabin_category_id', $cabin->cabin_category_id)->get('cabin_category')->row();
                                            ?>
                                            <option value="<?php echo $cabin->cabin_id ?>"><?php echo $cabin->cabin_number . '-' . $cabin_category->cabin_category_name . '-' . $cabin->cabin_rent ?></option>
                                            <?php
                                        }
                                        $cabin = $this->db->where('status', 'Available')->get('cabin')->result();
                                        foreach ($cabin as $cabin_value) {
                                            $cabin_category = $this->db->where('cabin_category_id', $cabin_value->cabin_category_id)->get('cabin_category')->row();
                                            ?>
                                            <option value="<?php echo $cabin_value->cabin_id ?>"><?php echo $cabin_value->cabin_number . '-' . $cabin_category->cabin_category_name . '-' . $cabin_value->cabin_rent ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Unique ID</label>
                                <div class="col-sm-8">

                                    <input type="text" readonly="" class="form-control" value="<?php echo $patient->patient_name ?>"  id="patient_unique_id"   name="patient_unique_id">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Date *</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control"  id="datepicker"  value="<?php echo date('d-m-Y', strtotime($patient->date)) ?>"  name="date">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="pwd">Gender </label>
                                <div class="col-sm-8">          
                                    <select type="text"     class="form-control" id="gender1"  name="gender">
                                        <option><?php echo $patient->gender ?></option>
                                        <option>Male</option>
                                        <option>Female</option>
                                        <option>Other</option>                               
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Time </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control"  id="admission_time"  value="<?php echo $patient->admission_time ?>"  name="admission_time">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Age</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" value="<?php echo $patient->age ?>" id="age"   name="age">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="pwd">Reference Doctor</label>
                                <div class="col-sm-8">          
                                    <select type="text" class="form-control" id="doctor_id1"  name="doctor_id">

                                        <?php
                                        $doctor = $this->db->where('doctor_id', $patient->doctor_id)->get('doctor')->row();
                                        ?>
                                        <option value="">Select Doctor</option>
                                        <?php
                                        $doctors = $this->db->select('*')->get('doctor')->result();
                                        foreach ($doctors as $doctor_value) {
                                            ?>
                                            <option <?php echo $doctor_value->doctor_id==$doctor->doctor_id?'selected':'' ?> value="<?php echo $doctor_value->doctor_id?>"><?php echo $doctor_value->doctor_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Paid Amount</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control"  id="paid_amount" value="<?php echo $patient->paid_amount ?>" name="paid_amount">
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Reference Media</label>
                                <div class="col-sm-8">
                                    <select type="text" class="form-control" id="reference_media_id1"  name="reference_media_id">

                                        <?php
                                        $reference_media = $this->db->where('reference_media_id', $patient->reference_media_id)->get('reference_media')->row();
                                        $previous_reference_id=$reference_media->reference_media_id!=''?$reference_media->reference_media_id:'';
                                        ?>
                                        <option value="" >Selec Reference Media</option>
                                        <?php
                                        $reference_media = $this->db->select('*')->get('reference_media')->result();
                                        foreach ($reference_media as $reference_media_value) {
                                            ?>
                                            <option <?php echo $reference_media_value->reference_media_id==$previous_reference_id?"selected":'' ?> value="<?php echo $reference_media_value->reference_media_id ?>"><?php echo $reference_media_value->reference_media_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">        
                                <div class="col-sm-offset-4 col-sm-4">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                               
                            </div>
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>  
                </form>
            </div>

        </div>

    </div>
</div>
