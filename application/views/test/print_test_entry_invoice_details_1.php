<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #report, #report * {
            visibility: visible;
            overflow: visible;
        }
        #report {
            position: absolute;
            left: 0;
            top: 0;
        }
        .p1 {line-height: 80%!important; }
    }
    .p1 {line-height: 80%!important; }
</style>
<div class="row">
    <div class="col-md-11"> 
        <button onclick="window.print()" class="btn btn-primary" >Print</button>
    </div>
    <div class="col-md-1"> 
        <button type="button" class="btn btn-danger" data-dismiss="modal">X</button>
    </div>
</div>
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:50px;">

    <div class="panel-body" style="height: auto ">
        <?php
        error_reporting(0);
        $patient_test_entry = $this->db->where('patient_test_entry_id', $patient_test_entry_id)
                ->get('patient_test_entry')
                ->row();
        $patient_test_entry_details = $this->db
                ->where('patient_test_entry_id', $patient_test_entry_id)
                ->get('patient_test_entry_details')
                ->result();
        $doctor = $this->db->where('doctor_id', $patient_test_entry->doctor_id)
                ->get('doctor')
                ->row();
        ?> 
        <?php
        $compnay = $this->db->where('company_id', '1')->get('company')->row();
        ?>
<!--        <div class="" style="width: 100%;margin-bottom: 10px;">
            <div style="width: 20%;float: left;">

                <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">

            </div>

            <div style="width: 80%;float: left;margin-bottom: 20px;text-align: left">
                <div class="content" style="padding-right: 150px; ">
                    <p style="text-align: center"><span style="text-align: center;font-size: 22px;text-align: center "> <?php echo $compnay->company_name ?></span><br>
                        <span style="text-align: center">  Mobile: <?php echo $compnay->mobile ?><br>
                            Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                        </span>
                    </p>
                </div>


            </div>
        </div>-->
        <div class="name" style="width: 100%;margin-bottom: 10px;">
            <table border="0" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                <tr>
                    <td>Patient Name</td>

                    <td>

                        <b><?php echo $patient_test_entry->patient_name ?></b> 
                    </td>
                    <td>Date & Time</td>

                    <td>

                        <b><?php echo date('d-m-Y', strtotime($patient_test_entry->date)) . '-' . $patient_test_entry->time ?></b>  
                    </td>


                </tr>
                <tr>
                    <td>Age</td>

                    <td>
                        <b><?php echo $patient_test_entry->age ?></b>  
                    </td>
                    <td>Gender</td>

                    <td>

                        <b><?php echo $patient_test_entry->gender ?></b>  
                    </td>
                </tr>
                <tr>
                    <td>Mobile</td>

                    <td>
                        <b><?php echo $patient_test_entry->mobile ?></b>  
                    </td>
                    <td>Invoice No</td>

                    <td>

                        <b><?php echo $patient_test_entry->invoice_no ?></b>  
                    </td>

                </tr>
                <tr>
                    <td>Address</td>

                    <td>
                        <b><?php echo $patient_test_entry->address ?></b>   
                    </td>



                    <td>Ref.Doctor</td>
                    <td>
                        <b><?php echo $doctor->doctor_name . '-' . $doctor->doctor_unique_id ?></b>   
                    </td>
                </tr>

            </table>
        </div>
        <div class="product" style="height: 300px; ">
            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
                <tr>
                    <td>Sl</td>
                    <td>Test Name</td>
                    <td>Delivery Date</td>
                    <td>Quantity</td>
                    <td>Unit Price</td>
                    <td>Total Price</td>
                </tr>
                <?php
                $sl = 1;
                foreach ($patient_test_entry_details as $patient_test_entry_details_value) {
                    $test_id = $patient_test_entry_details_value->test_id;

                    $test = $this->db
                            ->where('test_id', $test_id)
                            ->get('test')
                            ->row();
                    ?>
                    <tr>
                        <td><?php echo $sl++ ?></td>
                        <td><?php echo $test->test_name ?></td>  
                        <td><?php echo date('d-m-Y', strtotime($patient_test_entry_details_value->delivery_date)) ?></td>
                        <td><?php echo $patient_test_entry_details_value->quantity ?></td>
                        <td><?php echo $patient_test_entry_details_value->unit_price ?></td>
                        <td><?php echo $patient_test_entry_details_value->total_price ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td colspan="5" style="text-align:right">Sub Total</td>
                    <td><?php echo number_format($patient_test_entry->sub_total, 3) ?></td>
                </tr>
                <tr>
                    <td colspan="5"       style="text-align:right">Discount</td>
                    <td><?php echo $patient_test_entry->discount ?></td>
                </tr>
                <tr>
                    <td colspan="5"       style="text-align:right">vat(<?php echo $patient_test_entry->vat_in_percentage ?>)%</td>
                    <td><?php echo number_format($patient_test_entry->vat, 3) ?></td>
                </tr>
                <tr>
                    <td colspan="5"       style="text-align:right">Net Total</td>
                    <td><?php echo number_format($patient_test_entry->net_total, 3) ?></td>
                </tr>
                <tr>
                    <td colspan="4" rowspan="2"  style="text-align:right">
                        <?php
                        if ($patient_test_entry->due == 0) {
                            ?>
                            <h2 style="font-weight: bold ">Paid</h2>
                            <?php
                        } else {
                            ?>
                            <h2 style="font-weight: bold ">Due</h2>
                            <?php
                        }
                        ?>

                    </td>
                    <td  style="text-align:right">Paid</td>

                    <td><?php echo number_format($patient_test_entry->paid, 3) ?></td>
                </tr>
                <tr>
                    <td  style="text-align:right">Due</td>
                    <td><?php echo number_format($patient_test_entry->due, 3) ?></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>

<!--                <tr>
                    <td colspan="2">
                        <p style="text-align: center">Software developed by Bijoy LAB Web & IT Solution Ltd:01818-650864,www.bijoylab.com</p>

                    </td>
                </tr>-->
            </table>

        </div>

    </div>
</div>

