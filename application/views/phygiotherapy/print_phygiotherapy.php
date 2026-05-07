<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #report,
        #report * {
            visibility: visible;
            overflow: visible;
        }

        #report {
            position: absolute;
            left: 0;
            top: 0;
        }

        .p1 {
            line-height: 80% !important;
        }
    }

    .p1 {
        line-height: 80% !important;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <button onclick="window.print()" id="sumbit_button" class="btn btn-primary">Print</button>
    </div>

</div>
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:50px;">

    <?php
    error_reporting(0);
    $phygiotherapy_id = $this->session->userdata('print_phygiotherapy_id');
    // print_r($_SESSION);
    // die;
    $phygiotherapy = $this->db->where('phygiotherapy_id', $phygiotherapy_id)
        ->get('phygiotherapy')
        ->row();
    $doctor = $this->db->where('doctor_id', $phygiotherapy->doctor_id)
        ->get('doctor')
        ->row();
    $phygiotherapy_details = $this->db
        ->where('phygiotherapy_id', $phygiotherapy->phygiotherapy_id)
        ->get('phygiotherapy_details')
        ->result();
    $user = getUserById($phygiotherapy->user_id);

    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    ?>

    <div class="" style="width: 100%;margin-bottom: 10px;">
        <div style="width: 15%;float: left;margin-top:20px">
            <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
        </div>
        <div style="width: 70%;float: left;text-align: center">
            <p style="text-align: center"><span style="text-align: center;font-size: 20px;text-align: center "> <?php echo $compnay->company_name ?><br><?php echo $compnay->address ?></span><br>
                <span style="text-align: center">
                    Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                </span>
            </p>
        </div>
        <div style="width: 15%;float: left;margin-top:20px">
            <img src="<?php echo base_url('PhygiotherapyController/set_barcode/' . $phygiotherapy->phygiotherapy_invoice_no); ?>" alt="Barcode">
        </div>
    </div>


    <div class="name" style="width: 100%;margin-bottom: 10px;">
        <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
            <tr>
                <td colspan="4" style="text-align:center;font-weight:bold;font-size:20px"><u>Physiotherapy</u></td>
            </tr>
            <tr>
                <td>Name</td>

                <td>
                    <b><?php echo $phygiotherapy->name ?></b> </b>
                </td>
                <td>Date & Time</td>
                <td>
                    <b> <?php echo date('d-m-Y', strtotime($phygiotherapy->date)) ?> & <?php echo $phygiotherapy->physiotherapy_time ?> </b>
                </td>


            </tr>
            <tr>
                <td>Age</td>

                <td>
                    <b><?php
                        $age_parts = [];

                        if ($phygiotherapy->age_year > 0) {
                            $age_parts[] = $phygiotherapy->age_year . ' ' . ($phygiotherapy->age_year == 1 ? 'Year' : 'Years');
                        }

                        if ($phygiotherapy->age_month > 0) {
                            $age_parts[] = $phygiotherapy->age_month . ' ' . ($phygiotherapy->age_month == 1 ? 'Month' : 'Months');
                        }

                        if ($phygiotherapy->age_day > 0) {
                            $age_parts[] = $phygiotherapy->age_day . ' ' . ($phygiotherapy->age_day == 1 ? 'Day' : 'Days');
                        }

                        echo implode(' ', $age_parts);
                        ?></b>
                </td>
                <td>Gender</td>

                <td>

                    <b> <?php echo $phygiotherapy->gender ?></b>
                </td>
            </tr>
            <tr>
                <td>Phone</td>

                <td>
                    <b> <?php echo $phygiotherapy->phone ?></b>
                </td>
                <td>Invoice No</td>

                <td>

                    <b> <?php echo $phygiotherapy->phygiotherapy_invoice_no ?></b>
                </td>

            </tr>
            <tr>
                <td>Address</td>

                <td>
                    <b><?php echo $phygiotherapy->address ?></b>
                </td>



                <td>Reference</td>
                <td>
                <?php
                        $reference_doctor = $this->db->where('doctor_id', $patient_test_entry->reference_doctor_id)->get('doctor')->row();
                        $reference_media = $this->db->where('reference_media_id', $phygiotherapy->reference_media_id)->get('reference_media')->row();
                        $reference_director = $this->db->where('director_id', $phygiotherapy->reference_director_id)->get('director')->row();
                        $reference_employee = $this->db->where('employee_id', $phygiotherapy->reference_employee_id)->get('employee')->row();



                        if ($reference_doctor != '') {
                        ?>
                            Doctor:<b> <?php echo $reference_doctor->doctor_name ?></b><br>
                        <?php
                        }
                        ?>
                        <?php
                        if ($reference_media != '') {
                        ?>
                            Media:<b> <?php echo $reference_media->reference_media_name ?></b><br>
                        <?php
                        }
                        ?>
                        <?php
                        if ($reference_director != '') {
                        ?>
                            Director:<b> <?php echo $reference_director->name ?></b><br>
                        <?php
                        }
                        if ($reference_employee != '') {
                        ?>
                            Employee:<b> <?php echo $reference_employee->employee_name ?></b><br>
                        <?php
                        }
                        ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="product" style="height: 300px; ">
        <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
            <tr>
                <td>Sl</td>
                <td>Service Name</td>
                <td>Price</td>
                <td style="text-align:center">Quantity</td>
                <td style="text-align:right">Discount</td>
                <td style="text-align:right">Total Price</td>
            </tr>
            <?php
            $sl = 1;
            foreach ($phygiotherapy_details as $phygiotherapy_detail) {
                $phygiotherapy_service_id = $phygiotherapy_detail->phygiotherapy_service_id;
                $phygiotherapy_service = $this->db
                    ->where('phygiotherapy_service_id', $phygiotherapy_service_id)
                    ->get('phygiotherapy_service')
                    ->row();
            ?>
                <tr>
                    <td><?php echo $sl++ ?></td>
                    <td><?php echo $phygiotherapy_service->name ?></td>
                    <td style="text-align:center"><?php echo $phygiotherapy_detail->price ?></td>
                    <td style="text-align:right"><?php echo $phygiotherapy_detail->quantity ?></td>
                    <td style="text-align:right"><?php echo $phygiotherapy_detail->discounteach ?></td>
                    <td style="text-align:right"><?php echo $phygiotherapy_detail->amount ?></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td colspan="4" rowspan="8" style="text-align:right">
                    <?php if ($phygiotherapy->due == 0): ?>
                        <h2 style="font-weight: bold;text-align: center ">Paid</h2>
                    <?php else: ?>
                        <h2 style="font-weight: bold;text-align: center ">Due</h2>
                    <?php endif; ?>
                </td>
                <td style="text-align:right">Sub Total</td>
                <td style="text-align:right"><?php echo number_format($phygiotherapy->total, 3) ?></td>
            </tr>
            <tr>
                <td style="text-align:right">Discount</td>
                <td style="text-align:right"><?php echo $phygiotherapy->discount ?></td>
            </tr>
            <tr>
                <td style="text-align:right">D.Discount</td>
                <td style="text-align:right"><?php echo $phygiotherapy->director_discount ?></td>
            </tr>
            <tr>
                <td style="text-align:right">Total Discount</td>
                <td style="text-align:right"><?php echo $phygiotherapy->total_discount ?></td>
            </tr>
            <tr>
                <td style="text-align:right">Dis. Reference</td>
                <td style="text-align:right"><?php echo $phygiotherapy->discount_reference ?></td>
            </tr>
            <tr style="display:none">
                <td style="text-align:right">vat(<?php echo $phygiotherapy->vat_in_percentage ?>)%</td>
                <td style="text-align:right"><?php echo number_format($phygiotherapy->vat, 3) ?></td>
            </tr>
            <tr>
                <td style="text-align:right">Net Total</td>
                <td style="text-align:right"><?php echo number_format($phygiotherapy->nettotal, 3) ?></td>
            </tr>
            <tr>
                <td style="text-align:right">Paid</td>
                <td style="text-align:right"><?php echo number_format((float)$phygiotherapy->paid + (float)$phygiotherapy->due_payment - (float)$phygiotherapy->returnable_amount, 3) ?></td>
            </tr>
            <tr>
                <td style="text-align:right">Due</td>
                <td style="text-align:right"><?php echo number_format($phygiotherapy->due, 3) ?></td>
            </tr>

            <?php
            if ($phygiotherapy->status == 'Returned') {

            ?>
                <tr style="background-color:#005825;">
                    <td colspan="4" style="text-align:center;font-weight:bold;color:white">Returned</td>
                </tr>
                <tr>
                    <td>Return Date</td>
                    <td><b><?php echo date('d-m-Y', strtotime($phygiotherapy->return_date)) ?></b></td>
                    <td>Return Amount</td>
                    <td><b><?php echo $phygiotherapy->returnable_amount ?></b></td>

                </tr>
                <tr>
                    <td>Return Reason</td>
                    <td colspan="3"><b><?php echo $phygiotherapy->return_reason ?></b></td>
                </tr>
            <?php
            }
            ?>
        </table>
        <div id="entry_by">
            <div style="width: 50%;float:left">
                <p style="text-align: left;">Software By:<span style="font-weight:bold"> Bijoylab, www.bijoylab.com</span></p>
            </div>
            <div style="width: 30%;float:right">
                <p style="text-align: right;font-weight: bold;">Entry By: <?php echo  $user->name ?? "" ?></p>
            </div>
        </div>
    </div>
</div>