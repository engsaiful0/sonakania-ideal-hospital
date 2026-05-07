<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            width: 100%;
            background: rgb(204, 204, 204);
            overflow-x: hidden;
            font-size: 12px;

        }

        page[size="A4"] {
            background: white;
            width: 21cm;
            height: 29cm;
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            box-shadow: 0 0 0.1cm rgba(0, 0, 0, 0.5);
            -o-box-shadow: 0 0 0.1cm rgba(0, 0, 0, 0.5);
            -webkit-box-shadow: 0 0 0.1cm rgba(0, 0, 0, 0.5);
            -moz-box-shadow: 0 0 0.1cm rgba(0, 0, 0, 0.5);

        }

        @media print {

            body,
            page[size="A4"] {
                margin: 0;
                box-shadow: 0;

            }
        }

        .first {
            width: 100%;
            height: 29cm;
            margin: auto;
            padding: 5px 0px 0px 0px;
        }

        .second {
            width: 728px;
            height: 600px;
            margin: auto;
            margin-top: 40px
        }

        .third {
            width: 607px;
            height: 820px;
            margin: auto;
        }

        h2 {
            font-size: 24px;
        }

        #footer {
            width: 400px;
            margin: auto;
            background-color: #FFF;
        }

        #footer a {
            text-decoration: none;
            text-align: center;
            font-size: 10px;
        }

        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            .first {

                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
                -webkit-print-color-adjust: exact;
                font-size: 12px !important;
            }
        }

        @media print {
            #print {
                display: none;
            }

            .print {
                display: none;
            }
        }

        .upon {
            width: 70%;
            height: auto;
            margin: auto;

        }

        #site_header_logo {
            position: relative;
            width: 15%;
            height: 100px;
            float: left;
            margin-top: 10px;
            padding-left: 80px;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        a.tooltip {
            outline: none;
        }

        a.tooltip strong {
            line-height: 30px;
        }

        a.tooltip:hover {
            text-decoration: none;
        }

        a.tooltip span {
            z-index: 10;
            display: none;
            padding: 14px 20px;
            margin-top: 30px;
            margin-left: 2px;
            width: 300px;
            line-height: 16px;
        }

        a.tooltip:hover span {
            display: inline;
            position: absolute;
            color: #111;
            border: 1px solid #DCA;
            background: #fffAF0;
            font-size: 10px;
        }

        .callout {
            z-index: 20;
            position: absolute;
            top: 30px;
            border: 0;
            left: -12px;
        }

        /*CSS3 extras*/
        a.tooltip span {
            border-radius: 4px;
            box-shadow: 5px 5px 8px #CCC;
        }

        .boxright {
            width: 35%;
            height: 100px;
            float: left;
        }

        .fbox1 {
            width: 320px;
            float: left;
            margin-left: 39px;
            border-collapse: collapse;
            text-align: left;
            font-size: 10px;
        }

        .fbox1 td {
            height: 25px;
            line-height: 16px
        }

        @media print {
            .offs {
                display: none;
            }
        }

        tr:hover {
            background-color: #91b8f7
        }

        @font-face {
            font-family: 'NikoshBAN';
            src: url('../assets/fonts/NikoshBAN.ttf') format('truetype');

        }

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

    <title>Canteen Goods Usage Report</title>
</head>

<body>
    <div class="row">
        <div class="col-md-12">
            <button onclick="window.print()" id="sumbit_button" class="btn btn-primary">Print</button>
        </div>

    </div>
    <div id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:50px;">

        <?php
        error_reporting(0);
        $total_doctor = $this->db->get('doctor');
        $total_rows = $total_doctor->num_rows();
        $page_limit = ceil($total_rows / 10);
        //echo $page_limit;
        $from = -10;
        $start = 0;
        $grand_total_net_total = 0;
        $sl = 1;
        if ($page_limit == 0): // If there are no pages to display
        ?>
            <page size="A4">
                <div class="first">
                    <div class="second">
                        <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                            <tr style="background-color: #0074B3;color: white  ">
                                <td colspan="9" style="text-align: center"><b>Doctor List</b></td>
                            </tr>
                            <tr>
                                <td colspan="" style="text-align: center;">No data found</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </page>
            <?php
        else: // If there are pages to display

            for ($page_no = 1; $page_no <= $page_limit; $page_no++):
            ?>
                <page size="A4">
                    <div class="first">
                        <div class="second">
                            <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                                <tr style="background-color: #0074B3;color: white  ">
                                    <td colspan="9" style="text-align: center"><b>Doctor List</b></td>
                                </tr>
                                <tr>
                                    <td>#</td>
                                    <td>Name</td>
                                    <td>Degree</td>
                                    <td>Mobile</td>
                                    <td>Consulting Fee</td>
                                    <td>Picture</td>
                                    <td>OPD(%)</td>
                                    <td>LAB(%)</td>
                                   
                                </tr>
                                <?php
                                $from = $from + 10;
                                $this->db->select('*'); // Select all columns from both tables or specify the required columns
                                $this->db->from('doctor'); // Base table
                                $this->db->limit(10, $from); // Pagination limit and offset
                                $query_doctor = $this->db->get();
                                $query = $query_doctor->result();

                                foreach ($query as $query_value) {
                                ?>
                                    <tr>
                                        <td>
                                            <?php echo $sl++ ?>
                                        </td>
                                        <td>
                                            <?php echo $query_value->doctor_name ?>
                                        </td>
                                        <td>
                                            <?php echo $query_value->director_unique_id ?>
                                        </td>
                                        <td>
                                            <?php echo $query_value->mobile ?>
                                        </td>

                                        <td>
                                            <?php echo $query_value->new_patient_fee ?>
                                        </td>
                                        <td>
                                            <?php echo $query_value->amount_per_share ?>
                                        </td>
                                 
                                        <td>
                                            <?php
                                            if ($data->picture == '') {
                                            ?>
                                                <img style="height: 75px;width: 100px;" src="<?php echo base_url() ?>assets/image_icon.jpg">
                                        </td>
                                    <?php
                                            } else {
                                    ?>
                                        <img style="height: 75px;width: 100px;" src="<?php echo base_url() ?>assets/doctor_picture/<?php echo $data->picture ?>"></td>
                                    <?php
                                            }
                                    ?> </td>
                                      <td>
                                            <?php echo $query_value->opd_patient_percentage ?>
                                        </td>
                                        <td>
                                            <?php echo $query_value->lab_percentage ?>
                                        </td>
                                   
                                    </tr>
                                <?php
                                }
                                ?>

                            </table>
                        </div>
                    </div>
                </page>
        <?php
            endfor;
        endif;
        ?>
    </div>
</body>

</html>