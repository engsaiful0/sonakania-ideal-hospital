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

    <title>Send SMS Report</title>
</head>

<body>
    <?php
    $this->db->from('send_sms');

    // Check and apply conditions
    if (!empty($from_date) && empty($to_date) && empty($type)) {
        $this->db->where('date', date('Y-m-d', strtotime($from_date)));
    } else if (!empty($to_date) && empty($from_date) && empty($type)) {
        $this->db->where('date', date('Y-m-d', strtotime($to_date)));
    } else if (!empty($to_date) && !empty($from_date) && empty($type)) {
        $this->db
            ->where('date >=', date('Y-m-d', strtotime($from_date)))
            ->where('date <=', date('Y-m-d', strtotime($to_date)));
    } else if (!empty($to_date) && !empty($from_date) && !empty($type)) {
        $this->db
            ->where('date >=', date('Y-m-d', strtotime($from_date)))
            ->where('date <=', date('Y-m-d', strtotime($to_date)))
            ->where('type', $type);
    } else if (!empty($type) && empty($from_date) && empty($to_date)) {
        $this->db->where('type', $type);
    }

    // Get the result and count the rows
    $query = $this->db->get();
    $total_rows = $query->num_rows();
    $sl = 1;
    $page_limit = ceil($total_rows / 50);
    //echo $page_limit;
    $from = -50;
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
                            <td colspan="8" style="text-align: center"><b>Send SMS Report</b> From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                        </tr>
                        <tr>
                            <td>Sl</td>
                            <td>Type</td>
                            <td>Mobile</td>
                            <!-- <td>Message</td> -->
                            <td>Date</td>
                            <td>Time</td>
                        </tr>
                        <?php
                        $from = $from + 50;
                        $this->db->select('*'); // Select all columns
                        $this->db->from('send_sms'); // Base table

                        // Apply conditions based on input
                        if (!empty($from_date) && empty($to_date) && empty($type)) {
                            $this->db->where('date', date('Y-m-d', strtotime($from_date)));
                        } elseif (!empty($to_date) && empty($from_date) && empty($type)) {
                            $this->db->where('date', date('Y-m-d', strtotime($to_date)));
                        } elseif (!empty($from_date) && !empty($to_date) && empty($type)) {
                            $this->db
                                ->where('date >=', date('Y-m-d', strtotime($from_date)))
                                ->where('date <=', date('Y-m-d', strtotime($to_date)));
                        } elseif (!empty($from_date) && !empty($to_date) && !empty($type)) {
                            $this->db
                                ->where('date >=', date('Y-m-d', strtotime($from_date)))
                                ->where('date <=', date('Y-m-d', strtotime($to_date)))
                                ->where('type', $type);
                        } elseif (!empty($type) && empty($from_date) && empty($to_date)) {
                            $this->db->where('type', $type);
                        }

                        // Add ordering and pagination
                        $this->db->order_by('id', 'DESC'); // Order by `id` in descending order
                        $this->db->limit(50, $from); // Pagination limit and offset

                        // Execute query
                        $query_send_sms = $this->db->get();
                        $query = $query_send_sms->result();

                        foreach ($query as $query_value) {
                          if(strlen($query_value->mobile_number)<10 || strlen($query_value->mobile_number)>14){
                            continue;
                          }
                        ?>
                            <tr>
                                <td>
                                    <?php echo $sl++ ?>
                                </td>
                                <td>
                                    <?php echo $query_value->type ?>
                                </td>
                                <td>
                                    <?php echo $query_value->mobile_number ?>
                                </td>
                                <!-- <td>
                                    <?php echo $query_value->message ?>
                                </td> -->


                                <td>
                                    <?php echo date('d-m-Y', strtotime($query_value->date)) ?>
                                </td>
                                <td>
                                    <?php echo date('H:i:s', strtotime($query_value->date_and_time)); ?>
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