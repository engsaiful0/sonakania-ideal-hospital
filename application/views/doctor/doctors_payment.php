<script type="text/javascript">
    function bank_name_load(bank_or_cash)
    {

        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("bank_id").innerHTML = xhttp.responseText;
//                  var newdiv = document.createElement('tr');
//                newdiv.innerHTML = xhttp.responseText;
//                document.getElementById('due_history').appendChild(newdiv);
                $('#img').hide();
            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('WholeSellController/bank_name_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("bank_or_cash=" + bank_or_cash);
    }
    function due_history_load(doctor_id)
    {
        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("total_due_commission").value = xhttp.responseText;
                $('#img').hide();
            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('DoctorController/due_commission_history_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("doctor_id=" + doctor_id);
    }
</script>
<script>
    $(document).ready(function () {

        $('#doctor_id').select2();
        $('#cash_or_bank').select2();
        $('#bank_id').select2();


    });

    function SomeDeleteRowFunction(btndel) {

        if (typeof (btndel) == "object") {
            $(btndel).closest("tr").remove();
        } else {
            return false;
        }
        sub_total();
    }
    function current_due_cal()
    {

        var total_due_commission = $('#total_due_commission').val();
        var paid_amount = $('#paid_amount').val();
        if (Number(paid_amount) > Number(total_due_commission))
        {
            alert('Paid amount cant not be greater');
            $('#paid_amount').val(0);
        } else
        {
            $('#current_due_commission').val(Number(total_due_commission) - Number(paid_amount));
        }

    }
    function tatal_calculations()
    {
        var total_ids = $('#total_ids').val();
        //  alert(id);
        var grand_total = 0;
        for (var i = 1; i <= Number(total_ids); i++)
        {
            if (!isNaN($('#paid_' + i).val()))
            {
                grand_total += Number($('#paid_' + i).val());
            }


            // alert(grand_total);
        }
        $('#total_amount').val(Math.ceil(grand_total));


    }

</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add Doctor Commission Payment</h3>
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
            <div class="" style="width: 90%;float: left;margin: 0 auto">


                <form class="form-horizontal" method="post" action="<?php echo site_url('DoctorController/add_doctor_payment_save') ?>" enctype='multipart/form-data'>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Doctor</label>
                                <div class="col-sm-8">
                                    <select type="text" onchange="due_history_load(this.value)" required="" class="form-control"  id="doctor_id" name="doctor_id">
                                        <option selected="" value="" disabled="">Select Doctor</option> 

                                        <?php
                                        $doctor = $this->db->select('*')->get('doctor')->result();
                                        foreach ($doctor as $value) {
                                            ?>
                                            <option value="<?php echo $value->doctor_id; ?>"><?php echo $value->doctor_name . '-' . $value->doctor_unique_id; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="name">Date</label>
                                <div class="col-sm-8">
                                    <input type="text"  required="" value="<?php echo date('d-m-Y') ?>" class="form-control" name="date" id="datepicker">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="pwd">Total Due Commision</label>
                                <div class="col-sm-8">  

                                    <input type="text" readonly="" class="form-control"   id="total_due_commission"  name="total_due_commission">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="pwd">Bank/Cash</label>
                                <div class="col-sm-8">  

                                    <select type="text"  class="form-control"   id="cash_or_bank" onchange="bank_name_load(this.value)" name="cash_or_bank">
                                        <option>Cash</option>
                                        <option>Bank</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="pwd">Bank</label>
                                <div class="col-sm-8">  

                                    <select type="text"  class="form-control"   id="bank_id"  name="bank_id">
                                        <option selected="" value="0" disabled="">Select Bank</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="pwd">Paid Amount</label>
                                <div class="col-sm-8">  

                                    <input type="text"  class="form-control" required="" oninput="current_due_cal()" id="paid_amount"  name="paid_amount">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="pwd">Current Due Commission</label>
                                <div class="col-sm-8">  
                                    <input type="text"  class="form-control" required=""  id="current_due_commission"  name="current_due_commission">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">        
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="submit" onclick="return confirm('Do you want to submit?')" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="" style="width: 10%;margin: 0;float: left">
                <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none"/>
            </div>
        </div>

    </div>

</div><?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

