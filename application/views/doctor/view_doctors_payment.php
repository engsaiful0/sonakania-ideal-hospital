<style>
    .dropbtn {

        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {background-color: #ddd;}

    .dropdown:hover .dropdown-content {display: block;}

    .dropdown:hover .dropbtn {background-color: #3e8e41;}
</style>


<script>
    $(document).ready(function () {
// alert();
        $('#doctor_id').select2();

    });
    function model_load(product_category_id)
    {


        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("model_id").innerHTML = xhttp.responseText;
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('ProductController/model_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("product_category_id=" + product_category_id);
    }
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Doctor Payment View</h3>
    </div>
    <div class="panel-body">
        <div class="container" style="width: 100%">
            <div class="row">

                <div class="panel panel-primary">
                    <?php
                    if ($this->session->userdata('deleted') != '') {
                        ?>
                        <div class="alert alert-success">
                            <strong>Success!</strong>Data has been deleted successfully.
                        </div>
                        <?php
                        $sdata['deleted'] = '';
                        $this->session->set_userdata($sdata);
                    }
                    if ($this->session->userdata('update') != '') {
                        ?>
                        <div class="alert alert-success">
                            <strong>Success!</strong>Data has been updated successfully.
                        </div>
                        <?php
                        $sdata['update'] = '';
                        $this->session->set_userdata($sdata);
                    }
                    ?>
                    <form method="post" action="<?php echo site_url('DoctorController/view_doctors_payment') ?>">
                        <table>
                            <tr>
                                <td>Doctor</td>
                                <td></td>
                            </tr>
                            <tr>

                                <td style="width: 300px;">
                                    <select type="text"  class="form-control"  id="doctor_id" name="doctor_id">
                                        <option selected="" value="" disabled="">Doctor</option> 
                                        <?php
                                        $doctor = $this->db->select('*')->get('doctor')->result();
                                        foreach ($doctor as $value) {
                                            ?>
                                            <option value="<?php echo $value->doctor_id; ?>"><?php echo $value->doctor_name . '-' . $value->doctor_unique_id; ?></option>
                                            <?php
                                        }
                                        ?>

                                    </select>
                                </td>


                                <td><input type="submit" class="btn btn-primary" value="Search"></td>
                            </tr>
                        </table>
                    </form>

                    <table class="table table-hover table-bordered table-condensed">
                        <tr>
                            <td>#</td>
                            <td>Doctor</td>
                            <td>Paid Amount</td>
                            <td>Cash/Bank</td>
                            <td>Date</td>                   
                            <td>Edit</td>
                            <td>Delete</td>
                        </tr>
                        <?php
                        //print_r( count($detailsList));
                        $sl = 1;
                        $grand_total = 0;
                        for ($i = 0; $i < count($detailsList); ++$i) {
                            $doctor_commission_payment_id = $detailsList[$i]->doctor_commission_payment_id;
                            $doctor = $this->db->where('doctor_id', $detailsList[$i]->doctor_id)->get('doctor')->row();


                            //   print_r($category);
                            ?>
                            <tr>
                                <td><?php echo $sl++ ?></td>
                                <td><?php echo $doctor->doctor_name . '-' . $doctor->doctor_unique_id ?></td>
                                <td><?php
                        echo $detailsList[$i]->paid_amount;
                        $grand_total += $detailsList[$i]->paid_amount;
                            ?></td>
                                <td><?php echo $detailsList[$i]->cash_or_bank ?></td>

                                <td><?php echo date('d-m-Y', strtotime($detailsList[$i]->date)) ?></td>


                                <td>
                                    <a  id="doctorpayment_id_<?php echo $doctor_commission_payment_id ?>" onclick="modalLoadEdit(this.id)" class="btn btn-primary" data-target="#globalModalEdit"  data-toggle="modal" data-placement="top" data-content="update" href=""><i class="glyphicon glyphicon-edit"></i></a>
                                </td>
                                <td><a onclick="return confirm('Do you want to delete?')" href="<?php echo site_url("DoctorController/doctor_payment_delete/$doctor_commission_payment_id") ?>" class="btn btn-success"><i class="glyphicon glyphicon-trash"></i></a></td>

                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td colspan="2" style="text-align: right ">Total Payment</td>
                            <td><?php echo number_format($grand_total, 3) ?></td>
                            <td colspan="4"></td>
                        </tr>

                    </table>  


                </div>
            </div>
        </div>	
        <div class="container" style="width: 100%">
            <div class="row" style="list-style: none ">

                <?php echo $pagination; ?>



            </div>

        </div>
    </div>
</div>
<div class="modal"  id="globalModalEdit" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="">

        <div class="modal-content">

            <div class="modal-body">
                <div class="loader">
                    <div class="es-spinner">
                        <i class="glyphicon glyphicon-spinner fa-pulse fa-5x fa-fw"></i>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
<script>
    function modalLoadEdit(rowId) {

        var data = rowId.split('_'); //To get the row id

        //alert(data[2]);
        $_token = "{{ csrf_token() }}";
        $.ajax({
            headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
            url: "<?php echo site_url('DoctorController/doctor_payment_edit') ?>" + '/' + data[2],
            type: 'GET',
            cache: false,
            data: {'_token': $_token}, //see the $_token
            datatype: 'html',
            beforeSend: function () {
            },
            success: function (data) {

                // alert(data.length);
                //                    $('.modal-content').html(data);
                if (data.length > 0) {
                    // remove modal body
                    $('.modal-body').remove();
                    // add modal content
                    $('.modal-content').html(data);
                } else {
                    // add modal content
                    $('.modal-content').html('info');
                }
            }
        });
    }
</script>