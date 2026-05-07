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

    <title>Physiotherapy Report</title>
</head>

<body>
    <?php
    $this->db->where('date >=', date('Y-m-d', strtotime($from_date)));
    $this->db->where('date <=', date('Y-m-d', strtotime($to_date)));

    if (!empty($phygiotherapy_service_id)) {
        $this->db->join('phygiotherapy_details', 'phygiotherapy.phygiotherapy_id = phygiotherapy_details.phygiotherapy_id', 'left');
        $this->db->where('phygiotherapy_details.phygiotherapy_service_id', $phygiotherapy_service_id);
    }

    $total_phygiotherapy = $this->db->get('phygiotherapy');
    $total_rows = $total_phygiotherapy->num_rows();

    $page_limit = ceil($total_rows / 50);
    $from = -50;
    $grand_total_net_total = 0;
    $grand_total_paid = 0;
    $grand_total_discount = 0;
    ?>
    <?php for ($page_no = 1; $page_no <= $page_limit; $page_no++): ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                        <tr style="background-color: #0074B3;color: white">
                            <td colspan="9" style="text-align: center">
                                <b>Physiotherapy Report</b> From <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b>
                                To <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>Sl</td>
                            <td>Name</td>
                            <td>Age</td>
                            <td>Mobile</td>
                            <td>Service Name</td>
                            <td>Total</td>
                            <td>Discount</td>
                            <td>Paid</td>
                            <td>Date</td>
                        </tr>
                        <?php
                        $from += 50;
                        $this->db->select('phygiotherapy.*, phygiotherapy_service.name as phygiotherapy_name');
                        $this->db->from('phygiotherapy');
                        $this->db->join('phygiotherapy_details', 'phygiotherapy.phygiotherapy_id = phygiotherapy_details.phygiotherapy_id', 'left');
                        $this->db->join('phygiotherapy_service', 'phygiotherapy_details.phygiotherapy_service_id = phygiotherapy_service.phygiotherapy_service_id', 'left');
                        $this->db->where('phygiotherapy.date >=', date('Y-m-d', strtotime($from_date)));
                        $this->db->where('phygiotherapy.date <=', date('Y-m-d', strtotime($to_date)));
                        if (!empty($phygiotherapy_service_id)) {
                            $this->db->where('phygiotherapy_details.phygiotherapy_service_id', $phygiotherapy_service_id);
                        }
                        $this->db->order_by('phygiotherapy.phygiotherapy_id', 'DESC');
                        $this->db->limit(50, $from);
                        $query_phygiotherapy = $this->db->get();
                        $query = $query_phygiotherapy->result();
                        $sl = $from + 1;
                        foreach ($query as $query_value):
                        ?>
                            <tr>
                                <td><?php echo $sl++; ?></td>
                                <td><?php echo $query_value->name; ?></td>
                                <td>
                                    <?php if ($query_value->age_year > 0): ?>
                                        <?php echo $query_value->age_year . ' ' . ($query_value->age_year == 1 ? 'Year' : 'Years'); ?>
                                    <?php endif; ?>
                                    <?php if ($query_value->age_month > 0): ?>
                                        <?php echo ' ' . $query_value->age_month . ' ' . ($query_value->age_month == 1 ? 'Month' : 'Months'); ?>
                                    <?php endif; ?>
                                    <?php if ($query_value->age_day > 0): ?>
                                        <?php echo ' ' . $query_value->age_day . ' ' . ($query_value->age_day == 1 ? 'Day' : 'Days'); ?>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $query_value->phone; ?></td>
                                <td><?php echo $query_value->phygiotherapy_name; ?></td>
                                <td><?php echo $query_value->total;
                                    $grand_total_net_total += (float)$query_value->total; ?></td>
                                <td><?php echo $query_value->discount;
                                    $grand_total_discount += (float)$query_value->discount; ?></td>
                                <td><?php echo $query_value->paid;
                                    $grand_total_paid += (float)$query_value->paid; ?></td>
                                <td><?php echo date('d-m-Y', strtotime($query_value->date)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="5" style="text-align:right"><strong>Total</strong></td>
                            <td><strong><?php echo $grand_total_net_total; ?></strong></td>
                            <td><strong><?php echo $grand_total_discount; ?></strong></td>
                            <td><strong><?php echo $grand_total_paid; ?></strong></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </page>
    <?php endfor; ?>

</body>

</html>