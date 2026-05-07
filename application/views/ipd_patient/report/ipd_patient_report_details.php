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
    </style>

    <title>IPD Patient Admission Report</title>
</head>

<body>
    <?php
    error_reporting(0);
    $query = $this->db->where('date >=', date('Y-m-d', strtotime($from_date)))
        ->where('date <=', date('Y-m-d', strtotime($to_date)));

    if (!empty($status)) {
        $query = $query->where('status', $status);
    }

    $total_ipd_patient = $query->get('ipd_patient');
    $total_rows = $total_ipd_patient->num_rows();

    $page_limit = ceil($total_rows / 22);
    //echo $page_limit;
    $from = -22;
    $start = 0;
    $grand_total_net_total = 0;
    $grand_total_paid = 0;
    $grand_total_due = 0;
    for ($page_no = 1; $page_no <= $page_limit; $page_no++):
    ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                        <tr style="background-color: #0074B3;color: white  ">
                            <td colspan="8" style="text-align: center"><b>IPD Patient Admission Report</b> From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                        </tr>


                        <tr>
                            <td>Sl</td>
                            <td>Patient</td>
                            <td>Mobile</td>
                            <td>Id No</td>
                            <td>Reference</td>
                            <td>Location</td>
                            <td>Status</td>
                            <td>Date</td>
                        </tr>
                        <?php


                        $from = $from + 22;
                        $this->db->select('*');
                        $this->db->order_by('ipd_patient_id', 'DESC');
                        $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                            ->where('date<=', date('Y-m-d', strtotime($to_date)));
                        if (!empty($status)) {
                            $this->db->where('status', $status);
                        }
                        $this->db->from('ipd_patient');
                        $this->db->limit(22, $from);
                        $query_ipd_patient = $this->db->get();
                        $query = $query_ipd_patient->result();
                        $sl = 1;

                        foreach ($query as $query_value) {
                            $reference_doctor = $this->db->where('doctor_id', $query_value->reference_doctor_id)->get('doctor')->row();
                            $reference_media = $this->db->where('reference_media_id', $query_value->reference_media_id)->get('reference_media')->row();
                            $reference_director = $this->db->where('director_id', $query_value->reference_director_id)->get('director')->row();
                            $reference_employee = $this->db->where('employee_id', $query_value->reference_employee_id)->get('employee')->row();

                            $ward = $this->db->where('ward_id', $query_value->ward_id)->get('ward')->row();
                            $bed = $this->db->where('bed_id', $query_value->bed_id)->get('bed')->row();
                            $cabin = $this->db->where('cabin_id', $query_value->cabin_id)->get('cabin')->row();

                        ?>
                            <tr>
                                <td>
                                    <?php echo $sl++ ?>
                                </td>
                                <td>
                                    <?php echo $query_value->patient_name ?>
                                </td>
                                <td>
                                    <?php echo $query_value->mobile_number ?>
                                </td>
                                <td>
                                    <?php echo $query_value->patient_unique_id ?>
                                </td>
                                <td>
                                    <?php
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
                                <td>
                                    <?php if ($ward) { ?>
                                        Ward:<b><?php echo $ward->name ?></b> Bed:<b><?php echo $bed->bed_number ?></b>
                                    <?php
                                    }
                                    if ($cabin) { ?>
                                        Cabin:<b><?php echo $cabin->cabin_number ?></b>
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $query_value->status;
                                    ?>
                                </td>
                                <td>
                                    <?php echo date('d-m-Y', strtotime($query_value->date)) . '-' . $query_value->admission_time ?>
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
    ?>

</body>

</html>