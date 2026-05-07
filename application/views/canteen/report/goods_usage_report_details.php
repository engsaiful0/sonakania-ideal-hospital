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

    <title>Canteen Goods Usage Report</title>
</head>

<body>
    <?php
    $total_canteen_goods_usage_details = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
        ->where('date<=', date('Y-m-d', strtotime($to_date)))
        ->get('canteen_goods_usage_details');
    $total_rows = $total_canteen_goods_usage_details->num_rows();

    $page_limit = ceil($total_rows / 30);
    //echo $page_limit;
    $from = -30;
    $start = 0;
    $grand_total_net_total = 0;
    if ($page_limit == 0): // If there are no pages to display
    ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                        <tr style="background-color: #0074B3;color: white  ">
                            <td colspan="7" style="text-align: center"><b>Goods Usage Report</b> From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
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
                                <td colspan="7" style="text-align: center"><b>Goods Usage Report</b> From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                            </tr>
                            <tr>
                                <td>Sl</td>
                                <td>Goods Name</td>
                                <td>Quantity/Weight</td>
                                <td>Unit</td>
                                <td>Price</td>
                                <td>Total</td>
                                <td>Date</td>
                            </tr>
                            <?php


                            $from = $from + 30;

                            $this->db->select('canteen_goods_usage_details.*,  canteen_raw_goods.*,units.name as unit_name'); // Select all columns from both tables or specify the required columns
                            $this->db->from('canteen_goods_usage_details'); // Base table
                            $this->db->join('canteen_raw_goods', 'canteen_goods_usage_details.canteen_raw_goods_id = canteen_raw_goods.canteen_raw_goods_id', 'left');
                            $this->db->join('units', 'canteen_raw_goods.unit_id = units.unit_id', 'left');
                            $this->db->where('canteen_goods_usage_details.date >=', date('Y-m-d', strtotime($from_date))); // Date range filter
                            $this->db->where('canteen_goods_usage_details.date <=', date('Y-m-d', strtotime($to_date)));
                            $this->db->order_by('canteen_goods_usage_details.canteen_goods_usage_details_id', 'DESC'); // Order by ipd_service_id
                            $this->db->limit(30, $from); // Pagination limit and offset
                            $query_canteen_goods_usage_details = $this->db->get();
                            $query = $query_canteen_goods_usage_details->result();
                            $sl = 1;
                            foreach ($query as $query_value) {
                            ?>
                                <tr>
                                    <td>
                                        <?php echo $sl++ ?>
                                    </td>
                                    <td>
                                        <?php echo $query_value->name ?>
                                    </td>
                                    <td>
                                        <?php echo $query_value->quantity_or_weight ?>
                                    </td>
                                    <td>
                                        <?php echo $query_value->unit_name ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo $query_value->price;
                                        $grand_total_net_total += floatval($query_value->price);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $query_value->price * $query_value->quantity_or_weight;
                                        $grand_total_net_total += floatval($query_value->price * $query_value->quantity_or_weight);
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo date('d-m-Y', strtotime($query_value->date)) ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td></td>
                                <td> </td>
                                <td> </td>
                                <td style="text-align: right;">Total</td>

                                <td> </td>
                                <td> <?php
                                        echo $grand_total_net_total;
                                        ?></td>
                                <td> </td>

                            </tr>
                        </table>
                    </div>
                </div>
            </page>
    <?php
        endfor;
    endif;
    ?>

</body>

</html>