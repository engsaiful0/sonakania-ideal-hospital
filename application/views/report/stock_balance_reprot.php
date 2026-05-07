<style>
    .sgn{width:150px !important; height:25px !important; float:left;}
    @media print {
        .offs{display:none;}
        .sgn{display:none;}
    }
</style>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <style>
            body
            {
                width:100%;
                background: rgb(204,204,204); 
                overflow-x: hidden;
                font-size:9px;
            }
            page[size="A4"] {
                background: white;
                width: 21cm;
                height: 29cm;
                display: block;
                margin: 0 auto;
                margin-bottom: 0.5cm;
                box-shadow: 0 0 0.1cm rgba(0,0,0,0.5);
                -o-box-shadow: 0 0 0.1cm rgba(0,0,0,0.5);
                -webkit-box-shadow: 0 0 0.1cm rgba(0,0,0,0.5);
                -moz-box-shadow: 0 0 0.1cm rgba(0,0,0,0.5);
                font-size:9px!important;
            }

            @media print {
                body, page[size="A4"] {
                    margin: 0;
                    box-shadow: 0;
                    font-size:9px!important;
                }
            }

            .first
            {
                width:100%;
                height:29cm;
                margin:auto; padding:5px 0px 0px 0px;
            }
            .second
            {
                width:728px;
                height:600px;
                margin:auto; 
            }
            .third
            {
                width:607px;
                height:820px;
                margin:auto;
            }
            h2{font-size:24px;}
            #footer
            {
                width:400px;  
                margin:auto;
                background-color:#FFF;
            }
            #footer a
            {
                text-decoration:none;
                text-align:center;
                font-size:10px;
            }

            @page {
                size: A4;
                margin: 0;
            }
            @media print {
                .first {
                    font-size:9px!important;
                    margin: 0;
                    border: initial;
                    border-radius: initial;
                    width: initial;
                    min-height: initial;
                    box-shadow: initial;
                    background: initial;
                    page-break-after: always;
                    -webkit-print-color-adjust: exact;
                }
            }
            @media print{
                #print{
                    display:none;
                }
                .print{
                    display:none;
                }
            }

            .upon{
                width:70%;
                height:auto;
                margin:auto;

            }
            #site_header_logo{
                position: relative;
                width:15%;
                height:100px;
                float:left;
                margin-top:10px;
                padding-left:80px;
            }
            a{
                text-decoration:none;
                color:inherit;
            }
            a.tooltip {outline:none;}
            a.tooltip strong {line-height:30px;}
            a.tooltip:hover {text-decoration:none;} 
            a.tooltip span {
                z-index:10;display:none; padding:14px 20px;
                margin-top:30px; margin-left:2px;
                width:300px; line-height:16px;
            }
            a.tooltip:hover span{
                display:inline; position:absolute; color:#111;
                border:1px solid #DCA; background:#fffAF0;
                font-size:10px;
            }
            .callout {z-index:20;position:absolute;top:30px;border:0;left:-12px;}

            /*CSS3 extras*/
            a.tooltip span
            {
                border-radius:4px;
                box-shadow: 5px 5px 8px #CCC;
            }
            .boxright{width:35%; height:100px; float:left;  }
            .fbox1{width:320px; float:left; margin-left:39px; border-collapse: collapse; text-align:left; font-size:10px;}
            .fbox1 td{height:25px;  line-height:16px}

            @media print {
                .offs{display:none;}
            }
            tr:hover{background-color:#91b8f7}
            @font-face {
                font-family: 'NikoshBAN';
                src: url('../assets/fonts/NikoshBAN.ttf') format('truetype');

            }

        </style>

        <title>Stock Balance Report</title>
    </head>
    <body>
        <?php

        function eng_to_bangali_Number($number) {

            $replace_array = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০", ".");
            $search_array = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", ".");
            $bn_number = str_replace($search_array, $replace_array, $number);

            return $bn_number;
        }

        ini_set('max_execution_time', 6000); //300 seconds = 5 minutes
        $from = -50;
        $start = 0;
        // $subject = $this->db->where('subject_id', $subject_id)->get('subject')->row();
        $total_product = $this->db->where('stock>', '0')->get('product');
        $total_products = $total_product->num_rows();
        //  print_r($total_absents);
        // die;
        $page_number_check = 0;
        $page_number_print = 1;
        // echo $total_students;
        $page_limit = ceil($total_products / 50);
        //echo $page_limit;
        $grand_stock = 0;
        $grand_mrp = 0;
        $grand_purchase_price = 0;
        for ($page_no = 1; $page_no <= $page_limit; $page_no++):
            //echo 'hello';
            $sl = 1;
            ?>
        <page size="A4">
            <div class="first">
                <div class="second">

                    <div style="width:100%; height:auto; margin:auto;height: 50px;">
                        <table style="width:100%; height:auto; margin:auto" >
                            <tr>
                                <td style="text-align: center;font-size: 20px; ">
                                    &nbsp;

                                </td>
                            </tr>
    <!--                            <tr>
                                <td style="text-align: center;font-size: 20px; ">
                                    Chittagong
                                </td>

                            </tr>-->
                        </table>

                    </div>

                    <div class="content" style="width:100%;margin:0 auto">

                        <?php
                        $from = $from + 50;

                        $this->db->select('*');
                        $this->db->order_by('product_category_id', 'asc');
                        $this->db->where('stock>', '0');
                        $this->db->from('product');
                        $this->db->limit(50, $from);
                        $query = $this->db->get();
                        $product = $query->result();
                        ?>
                        <table border="1" style="border-collapse: collapse;width: 100%;font-size: 10px; ">
                            <tr>
                                <td style="text-align:center">Sl</td>
                                <td style="text-align:center">Category</td>
                                <td style="text-align:center">Model</td>
                                <td style="text-align:center">Color</td>                               
                                <td style="text-align:center">Stock</td>
                                <td style="text-align:center">P.Price</td>
                                <td style="text-align:center">MRP</td>
                            </tr>
                            <?php
                            error_reporting(0);
                            $category_temp = '';
                            $model_temp = '';
                            foreach ($product as $product_value) {
                                $product_category = $this->db->where('product_category_id', $product_value->product_category_id)
                                        ->get('product_category')
                                        ->row();
                                $model = $this->db->where('model_id', $product_value->model_id)
                                        ->get('model')
                                        ->row();
                                $color = $this->db->where('color_id', $product_value->color_id)
                                        ->get('color')
                                        ->row();
                                ?>
                                <tr>
                                    <td style="text-align: center "><?php echo $sl++ ?></td>
                                    <td>
                                        <?php
                                        if ($category_temp != $product_category->category_name) {
                                            echo $product_category->category_name;
                                            $category_temp = $product_category->category_name;
                                        } else {
                                            echo '"';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($model_temp != $model->model_name) {

                                            echo $model->model_name;
                                            $model_temp = $model->model_name.'-'.$color->color_name;
                                        } else {
                                            echo '"'.'-'.$color->color_name;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo $color->color_name ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $product_value->stock;
                                        $grand_stock += $product_value->stock;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $product_value->purchase_price * $product_value->stock;
                                        $grand_purchase_price += $product_value->purchase_price * $product_value->stock;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $product_value->retail_sell_price * $product_value->stock;
                                        $grand_mrp += $product_value->retail_sell_price * $product_value->stock;
                                        ?>
                                    </td>

                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td></td>
                                <td>Page No:<?php echo $page_no ?></td>
                                <td></td>
                                <td style="text-align: right" colspan="1">Total</td>
                                <td><?php echo $grand_stock ?></td>
                                <td><?php echo number_format($grand_purchase_price, 0) ?></td>
                                <td><?php echo number_format($grand_mrp, 0) ?></td>
                            </tr>
                        </table>
                    </div>                
                </div>

            </div>
        </page>
        <?php
        // die;
    endfor
    ?>
</body>
</html>