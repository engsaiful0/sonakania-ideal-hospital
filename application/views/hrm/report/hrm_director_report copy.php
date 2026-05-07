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



    <table width="100%" border="1" style="border-collapse:collapse " id="cart_tab1">
        <tr style="background-color: #0074B3;color: white  ">
            <td colspan="9" style="text-align: center"><b>Director List</b></td>
        </tr>
        <tr>
            <td>#</td>
            <td>Director Name</td>
            <td>ID</td>
            <td>Mobile</td>
            <td>No of Share</td>
            <td>Shar Value</td>
            <td>Discount Rate</td>
            <td>Picture</td>
            <td>Date of Join</td>

        </tr>
        <?php

        $sl = 1;
        $grand_total = 0;
        foreach ($director_data as $data) :
            $director_id  = $data->director_id;

        ?>
            <tr>
                <td><?php echo $sl++ ?></td>

                <td><?php echo $data->name ?></td>
                <td><?php echo $data->unique_id ?></td>
                <td><?php echo $data->mobile ?></td>
                <td><?php echo $data->no_of_share ?></td>
                <td><?php echo $data->total_amount ?></td>
                <td>IPD:<b><?php echo $data->ipd_discount ?>%</b> OPD:<b><?php echo $data->opd_discount ?>%</b> Test:<b><?php echo $data->test_discount ?>%</b><br>
                    Emergency:<b><?php echo $data->emergency_discount ?>%</b> Phygiotherapy:<b><?php echo $data->phygiotherapy_discount ?>%</b><br>
                    Pharmachy:<b><?php echo $data->pharmachy_discount ?>%</b></td>
                <td>
                    <?php
                    if ($data->picture == '') {
                    ?>
                        <img style="height: 100px;width: 100px;" src="<?php echo base_url() ?>assets/image_icon.jpg">
                </td>
            <?php
                    } else {
            ?>
                <img style="height: 100px;width: 100px;" src="<?php echo base_url() ?>assets/director/<?php echo $data->picture ?>"></td>
            <?php
                    }
            ?>


            <td><?php echo !empty($data->date_of_join) ? date('d-m-Y', strtotime($data->date_of_join)) : 'N/A' ?></td>
            <td>
                <a class="btn btn-primary" href="<?php echo base_url("print-director/$director_id") ?>"><i class="glyphicon glyphicon-print"></i></a>
            </td>
            <td>
                <a class="btn btn-primary" href="<?php echo base_url("edit-director/$director_id") ?>"><i class="glyphicon glyphicon-edit"></i></a>
            </td>
            <td><a onclick="return confirm('Do you want to delete?')" href="<?php echo base_url("delete-this-director/$director_id") ?>" class="btn btn-success"><i class="glyphicon glyphicon-trash"></i></a></td>

            </tr>
        <?php endforeach; ?>

    </table>


</div>