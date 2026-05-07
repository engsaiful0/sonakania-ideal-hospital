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

    <title>Medicine Stock Report</title>
</head>

<body>
    <?php
    $total_medicines = '';
    if ($drug_name == "") {
        $total_medicines = $this->db->select('*')->get('drug');
    } else {
        $total_medicines = $this->db->where('drug_name', $drug_name)->get('drug');
    }


    $total_rows = $total_medicines->num_rows();

    $page_limit = ceil($total_rows / 50);
    $from = -50;
    $grand_mrp_value = 0;
    $grand_purchase_value = 0;
    $grand_stock = 0;
    $sl = 1;
    if ($page_limit == 0): // If there are no pages to display
    ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                        <tr style="background-color: #0074B3;color: white;">
                            <td colspan="6" style="text-align: center"><b>Medicine Stock Report</b></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="text-align: center;">No data found</td>
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
                            <tr style="background-color: #0074B3;color: white;">
                                <td colspan="7" style="text-align: center"><b>Medicine Stock Report</b></td>
                            </tr>
                            <tr>
                                <td>Sl</td>
                                <td>Medicine Name</td>
                                <td>Stock</td>
                                <td>MRP</td>
                                <td>MRP Value</td>
                                <td>Purchase Rate</td>
                                <td>Purchase Value</td>
                            </tr>
                            <?php
                            $from = $from + 50;
                            $this->db->select('*'); // Select all columns from both tables or specify the required columns
                            $this->db->from('drug'); // Base table
                            if ($drug_name == "") {
                               
                            } else {
                                $this->db->where('drug_name', $drug_name); // Date range filter
                            }
                          
                            $this->db->order_by('drug_name', 'ASC'); // Order by expired_medicine_id
                            $this->db->limit(50, $from); // Pagination limit and offset
                            $query_stock_medicines = $this->db->get();
                            $query = $query_stock_medicines->result();

                           
                            foreach ($query as $query_value) {
                                $stock = getStock($query_value->drug_id);
                                $grand_stock += $stock;
                            ?>
                                <tr>
                                    <td><?php echo $sl++ ?></td>
                                    <td><?php echo $query_value->drug_name ?></td>
                                    <td><?php echo $stock ?></td>
                                    <td><?php echo $query_value->mrp ?></td>
                                    <td>
                                        <?php
                                        echo $stock * (float)$query_value->mrp;
                                        $grand_mrp_value += floatval((float)$stock * (float)$query_value->mrp);
                                        ?>
                                    </td>
                                    <td><?php echo $query_value->purchase_rate ?></td>
                                    <td>
                                        <?php
                                        echo $stock * (float)$query_value->purchase_rate;
                                        $grand_purchase_value += floatval((float)$stock * (float)$query_value->purchase_rate);
                                        ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr>
                               
                                <td colspan="2" style="text-align: right;"></td>
                                <td>Total Stock<br><b>=<?php echo number_format($grand_stock); ?></b></td>
                                <td></td>
                                <td>Total Stock Value<br><b>=<?php echo number_format($grand_mrp_value); ?></b></td>
                                <td></td>
                                <td>Total Purchase Value<br><b>=<?php echo number_format($grand_purchase_value); ?></b></td>
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