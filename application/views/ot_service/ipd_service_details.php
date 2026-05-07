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

    $patient = $this->db->where('ipd_patient_id', $data->ipd_patient_id)
        ->get('ipd_patient')
        ->row();

    $ipd_service_details = $this->db
        ->where('ipd_service_id', $data->ipd_service_id)
        ->get('ipd_service_details')
        ->result();
    ?>


    <div class="customer-copy" style="margin-top: 50px; ">


        <div class="name" style="width: 100%;margin-bottom: 10px;">
            <table border="1" class="table table-bordered table-hover" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                <tr>
                    <td>Patient</td>

                    <td>
                        <b><?php echo $patient->patient_name ?></b> </b>
                    </td>
                    <td>Mobile</td>
                    <td>
                        <b><?php echo $patient->mobile_number ?></b>
                    </td>
                </tr>
                <tr>
                    <td>Patient Id</td>
                    <td>
                    <b><?php echo $patient->patient_unique_id ?></b>
                    </td>
                    <td>Date</td>
                    <td>
                        <b> <?php echo date('d-m-Y', strtotime($data->date)) ?></b>
                    </td>
                </tr>

            </table>
        </div>
        <div class="product" style="height: 300px; ">
            <table border="1" class="table table-bordered table-hover" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
                <tr>
                    <td>Sl</td>
                    <td>Service Name</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Amount</td>
                </tr>
                <?php
                $sl = 1;
              
                foreach ($ipd_service_details as $ipd_service_detail) {
                    
                    $ipd_service_item = $this->db
                        ->where('ipd_service_item_id', $ipd_service_detail->ipd_service_item_id)
                        ->get('ipd_service_item')
                        ->row();
                ?>
                    <tr>
                        <td><?php echo $sl++ ?></td>
                        <td><?php echo $ipd_service_item->name ?></td>
                        <td style="text-align:left"><?php echo $ipd_service_detail->price ?></td>
                        <td style="text-align:left"><?php echo $ipd_service_detail->quantity ?></td>
                        <td style="text-align:left"><?php echo $ipd_service_detail->amount ?></td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="4" style="text-align:right">Total Amount</td>
                    <td style="text-align:left"><?php echo $data->net_total ?></td>
                </tr>


            </table>
       
        </div>
    </div>


</div>