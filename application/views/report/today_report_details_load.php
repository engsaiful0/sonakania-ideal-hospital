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

            }

            @media print {
                body, page[size="A4"] {
                    margin: 0;
                    box-shadow: 0;

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
                margin-top: 40px
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
              
            }
            .callout {z-index:20;position:absolute;top:30px;border:0;left:-12px;}

            /*CSS3 extras*/
            a.tooltip span
            {
                border-radius:4px;
                box-shadow: 5px 5px 8px #CCC;
            }
            .boxright{width:35%; height:100px; float:left;  }
            .fbox1{width:320px; float:left; margin-left:39px; border-collapse: collapse; text-align:left; }
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

        <title>Retailer Due Report</title>
    </head>
    <body>
        <div id="report" id="report" style="width: 95%;margin:0 auto;margin-left:45px;margin-top:40px;">
            <?php
            $total_retail_sell_payment = $this->db
                    ->where('date>=', date('Y-m-d', strtotime($from_date)))
                    ->where('is_deleted', '0')->where('collection_type', 'From sells')
                    ->where('date<=', date('Y-m-d', strtotime($to_date)))
                    ->get('sales');
            $total_profit_rows = $total_retail_sell_payment->num_rows();
            $page_limit = ceil($total_profit_rows / 42);
            //echo $page_limit;
            $from = -42;
            $start = 0;
            $grand_retail_sell = 0;
            for ($page_no = 1; $page_no <= $page_limit; $page_no++):
                ?>
                <page size="A4">
                    <div class="first">
                        <div class="second">
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
                                <tr>
                                    <td colspan="5" style="text-align: center ">
                                        Cash Report   From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="text-align: center ">
                                        Retial Sell
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sl</td>
                                    <td>ID No</td>
                                    <td>Type</td>
                                    <td>Amount</td>
                                    <td>Date</td>           
                                </tr>
                                <?php
                                $query = '';
//                        $query = $this->db
//                                ->where('collection_type', 'From sells')
//                                ->where('date>=', date('Y-m-d', strtotime($from_date)))
//                                ->where('date<=', date('Y-m-d', strtotime($to_date)))
//                                ->get('retail_sell_payment')
//                                ->result();

                                $from = $from + 42;
                                $this->db->select('*');
                                $this->db->order_by('retail_customer_id', 'asc');
                                $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))->where('is_deleted', '0')->where('collection_type', 'From sells')
                                        ->where('date<=', date('Y-m-d', strtotime($to_date)));
                                $this->db->from('retail_sell_payment');
                                $this->db->limit(42, $from);
                                $query_profit = $this->db->get();
                                $query = $query_profit->result();
                                $sl = 1;
                                foreach ($query as $query_value) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $sl++ ?>
                                        </td>
                                        <td>
                                            <?php echo $query_value->id_no ?>
                                        </td>
                                        <td>
                                            <?php echo $query_value->collection_type ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $query_value->amount;
                                            $grand_retail_sell += $query_value->amount;
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
                                    <td></td>
                                    <td>Total</td>
                                    <td><?php echo number_format($grand_retail_sell, 0) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </page>
                <div style="height: 100px; "></div>

                <?php
            endfor;
            ?>
            <?php
            $total_retail_sell_payment = $this->db
                    ->where('is_deleted', '0')->where('collection_type', 'Installment Collection')
                    ->where('date>=', date('Y-m-d', strtotime($from_date)))
                    ->where('date<=', date('Y-m-d', strtotime($to_date)))
                    ->get('retail_sell_payment');
            $total_retail_sell_payment_rows = $total_retail_sell_payment->num_rows();

            $page_limit = ceil($total_retail_sell_payment_rows / 42);
            //echo $page_limit;
            $from = -42;
            $start = 0;
            $grand_retail_installment_collection = 0;
            for ($page_no = 1; $page_no <= $page_limit; $page_no++):
                ?>
                <page size="A4">
                    <div class="first">
                        <div class="second">
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
                                <tr>
                                    <td colspan="5" style="text-align: center ">
                                        Installment Collection
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sl</td>
                                    <td>ID No</td>
                                    <td>Type</td>
                                    <td>Amount</td>
                                    <td>Date</td>           
                                </tr>
                                <?php
                                $query = '';
//                        $query = $this->db
//                                ->where('collection_type', 'Installment Collection')
//                                ->where('date>=', date('Y-m-d', strtotime($from_date)))
//                                ->where('date<=', date('Y-m-d', strtotime($to_date)))
//                                ->get('retail_sell_payment')
//                                ->result();

                                $from = $from + 42;
                                $this->db->select('*');
                                $this->db->order_by('retail_customer_id', 'asc');
                                $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))->where('is_deleted', '0')->where('collection_type', 'Installment Collection')
                                        ->where('date<=', date('Y-m-d', strtotime($to_date)));
                                $this->db->from('retail_sell_payment');
                                $this->db->limit(42, $from);
                                $query_profit = $this->db->get();
                                $query = $query_profit->result();

                                $sl = 1;
                                foreach ($query as $query_value) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $sl++ ?>
                                        </td>
                                        <td>
                                            <?php echo $query_value->id_no ?>
                                        </td>
                                        <td>
                                            <?php echo $query_value->collection_type ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $query_value->amount;
                                            $grand_retail_installment_collection += $query_value->amount;
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
                                    <td></td>
                                    <td>Total</td>
                                    <td><?php echo number_format($grand_retail_installment_collection, 0) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </page>
                <div style="height: 100px; "></div>

                <?php
            endfor;
            ?>
            <?php
            $total_whole_customer_payment = $this->db
                    ->where('is_deleted', '0')
                    ->where('paid>', '0')
                    ->where('date>=', date('Y-m-d', strtotime($from_date)))
                    ->where('date<=', date('Y-m-d', strtotime($to_date)))
                    ->get('whole_customer_payment');
            $total_whole_customer_payment_row = $total_whole_customer_payment->num_rows();
            $page_limit = ceil($total_whole_customer_payment_row / 42);
            //echo $page_limit;
            $from = -42;
            $start = 0;
            $grand_whole_sell = 0;
            for ($page_no = 1; $page_no <= $page_limit; $page_no++):
                ?>
                <page size="A4">
                    <div class="first">
                        <div class="second">
                            <table border="1" class="table table-bordered table-hover" style="width: 98%;;margin-top:10px;color:black;border-collapse:collapse;">
                                <tr>
                                    <td colspan="4" style="text-align: center ">
                                        Whole Sell
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sl</td>
                                    <td>Dealer</td>
                                    <td>Amount</td>
                                    <td>Date</td>           
                                </tr>
                                <?php
                                $query = '';
                                $sl = 1;
//                        $query = $this->db
//                                ->where('paid>', '0')
//                                ->where('date>=', date('Y-m-d', strtotime($from_date)))
//                                ->where('date<=', date('Y-m-d', strtotime($to_date)))
//                                ->get('whole_customer_payment')
//                                ->result();

                                $from = $from + 42;
                                $this->db->select('*');
                                $this->db->order_by('dealer_id', 'asc');
                                $this->db->where('is_deleted', '0')
                                        ->where('paid>', '0')
                                        ->where('date>=', date('Y-m-d', strtotime($from_date)))
                                        ->where('date<=', date('Y-m-d', strtotime($to_date)));
                                $this->db->from('whole_customer_payment');
                                $this->db->limit(42, $from);
                                $query_profit = $this->db->get();
                                $query = $query_profit->result();


                                foreach ($query as $query_value) {
                                    $dealer = $this->db->where('dealer_id', $query_value->dealer_id)->get('dealer')->row();
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $sl++ ?>
                                        </td>
                                        <td>
                                            <?php echo $dealer->dealer_name ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $query_value->paid;
                                            $grand_whole_sell += $query_value->paid;
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
                                    <td>Total</td>
                                    <td><?php echo number_format($grand_whole_sell, 0) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </page>
                <div style="height: 100px; "></div>

                <?php
            endfor;
            ?>
            <?php
            $total_owner_payment = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                    ->where('date<=', date('Y-m-d', strtotime($to_date)))
                    ->get('owner_payment');
            $total_owner_payment_rows = $total_owner_payment->num_rows();
            $page_limit = ceil($total_owner_payment_rows / 42);
            //echo $page_limit;
            $from = -42;
            $start = 0;
            $grand_owner_payment = 0;
            for ($page_no = 1; $page_no <= $page_limit; $page_no++):
                ?>
                <page size="A4">
                    <div class="first">
                        <div class="second">
                            <table border="1" class="table table-bordered table-hover" style="width: 98%;margin-top:5px;color:black;border-collapse:collapse;">
                                <tr>
                                    <td colspan="4" style="text-align: center ">
                                        Owner Payment
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sl</td>
                                    <td>Owner Name</td>
                                    <td>Amount</td>
                                    <td>Date</td>           
                                </tr>
                                <?php
                                $sl = 1;
                                $query = '';

//                        $owner_payment = $this->db
//                                ->order_by('owner_id')
//                                ->where('date>=', date('Y-m-d', strtotime($from_date)))
//                                ->where('date<=', date('Y-m-d', strtotime($to_date)))
//                                ->get('owner_payment')
//                                ->result();

                                $from = $from + 42;
                                $this->db->select('*');
                                $this->db->order_by('owner_id', 'asc');
                                $this->db
                                        ->where('date>=', date('Y-m-d', strtotime($from_date)))
                                        ->where('date<=', date('Y-m-d', strtotime($to_date)));
                                $this->db->from('owner_payment');
                                $this->db->limit(42, $from);
                                $owner_payment_get = $this->db->get();
                                $owner_payment = $owner_payment_get->result();


                                foreach ($owner_payment as $owner_payment_value) {
                                    $owner = $this->db->where('owner_id', $owner_payment_value->owner_id)->get('owner')->row();
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $sl++ ?>
                                        </td>
                                        <td>
                                            <?php echo $owner->owner_name ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $owner_payment_value->amount;
                                            $grand_owner_payment += $owner_payment_value->amount;
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo date('d-m-Y', strtotime($owner_payment_value->date)) ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr>        
                                    <td></td>
                                    <td>Total</td>
                                    <td><?php echo number_format($grand_owner_payment, 0) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </page>
                <div style="height: 100px; "></div>

                <?php
            endfor;
            ?>
            <?php
            $total_expense = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                    ->where('date<=', date('Y-m-d', strtotime($to_date)))
                    ->get('expense');
            $total_expense_rows = $total_expense->num_rows();

            $page_limit = ceil($total_expense_rows / 42);
            //echo $page_limit;
            $from = -42;
            $start = 0;
            $grand_expense = 0;
            for ($page_no = 1; $page_no <= $page_limit; $page_no++):
                ?>
                <page size="A4">
                    <div class="first">
                        <div class="second">
                            <table border="1" class="table table-bordered table-hover" style="width: 98%;margin-top:5px;color:black;border-collapse:collapse;">
                                <tr>
                                    <td colspan="4" style="text-align: center ">
                                        Expense
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sl</td>
                                    <td>Head</td>
                                    <td>Amount</td>
                                    <td>Date</td>           
                                </tr>
                                <?php
                                $sl = 1;
                                $query = '';


//                        $query = $this->db
//                                ->where('date>=', date('Y-m-d', strtotime($from_date)))
//                                ->where('date<=', date('Y-m-d', strtotime($to_date)))
//                                ->get('expense')
//                                ->result();

                                $from = $from + 42;
                                $this->db->select('*');
                                $this->db->order_by('expense_head_id', 'asc');
                                $this->db
                                        ->where('date>=', date('Y-m-d', strtotime($from_date)))
                                        ->where('date<=', date('Y-m-d', strtotime($to_date)));
                                $this->db->from('expense');
                                $this->db->limit(42, $from);
                                $expense_get = $this->db->get();
                                $query = $expense_get->result();

                                foreach ($query as $query_value) {
                                    $expense = $this->db->where('expense_head_id', $query_value->expense_head_id)->get('expense_head')->row();
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $sl++ ?>
                                        </td>
                                        <td>
                                            <?php echo $expense->expense_head_name ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $query_value->amount;
                                            $grand_expense += $query_value->amount;
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
                                    <td>Total</td>
                                    <td><?php echo number_format($grand_expense, 0) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </page>
                <div style="height: 100px; "></div>
                <?php
            endfor;
            $employee_salary = $this->db->select_sum('amount', 'amount')
                    ->where('date>=', date('Y-m-d', strtotime($from_date)))
                    ->where('date<=', date('Y-m-d', strtotime($to_date)))
                    ->get('employee_salary')
                    ->result();
            $owner_expense = $this->db->select_sum('amount', 'amount')
                    ->where('date>=', date('Y-m-d', strtotime($from_date)))
                    ->where('date<=', date('Y-m-d', strtotime($to_date)))
                    ->get('owner_payment')
                    ->result();
            $special_income = $this->db->select_sum('amount', 'amount')
                    ->where('date>=', date('Y-m-d', strtotime($from_date)))
                    ->where('date<=', date('Y-m-d', strtotime($to_date)))
                    ->get('special_income')
                    ->result();
            ?>

            <page size="A4">
                <div class="first">
                    <div class="second">
                        <table border="1" class="table table-bordered table-hover" style="width: 98%;margin-top:5px;color:black;border-collapse:collapse;">
                            <tr>
                                <td colspan="2" style="text-align: center ">
                                    Total Income
                                </td>
                                <td colspan="2" style="text-align: center ">
                                    Total Expense
                                </td>
                            </tr>
                            <tr>
                                <td  style="text-align: right ">
                                    Retail Sell 
                                </td>
                                <td  style="text-align: center ">
                                    <?php
                                    echo number_format($grand_retail_sell, 0)
                                    ?>
                                </td>
                                <td  style="text-align: right ">
                                    Total Expense
                                </td>
                                <td  style="text-align: center ">
                                    <?php echo number_format($grand_expense, 0) ?>
                                </td>
                            </tr>
                            <tr>
                                <td  style="text-align: right ">
                                    Installment Collection
                                </td>
                                <td  style="text-align: center ">
                                    <?php
                                    echo number_format($grand_retail_installment_collection, 0)
                                    ?>
                                </td>
                                <td  style="text-align: right ">
                                    Employee Salary
                                </td>
                                <td  style="text-align: center ">
                                    <?php
                                    echo number_format($employee_salary[0]->amount, 0)
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td  style="text-align: right ">
                                    Whole Sell
                                </td>
                                <td  style="text-align: center ">
                                    <?php
                                    echo number_format($grand_whole_sell, 0)
                                    ?>
                                </td>
                                <td  style="text-align: right ">
                                    Owner Payment
                                </td>
                                <td  style="text-align: center ">
                                    <?php
                                    echo number_format($owner_expense[0]->amount, 0)
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td  style="text-align: right ">
                                    Special Income
                                </td>
                                <td  style="text-align: center ">
                                    <?php
                                    echo number_format($special_income[0]->amount, 0)
                                    ?>
                                </td>
                                <td  style="text-align: right ">

                                </td>
                                <td  style="text-align: center ">

                                </td>

                            </tr>

                            <tr>
                                <td  style="text-align: right ">
                                    Total
                                </td>
                                <td  style="text-align: center ">
                                    <?php
                                    echo number_format($special_income[0]->amount + $grand_whole_sell + $grand_retail_sell + $grand_retail_installment_collection, 0)
                                    ?>
                                </td>
                                <td  style="text-align: right ">
                                    Total
                                </td>
                                <td  style="text-align: center ">
                                    <?php
                                    echo number_format($grand_expense + $employee_salary[0]->amount + $owner_expense[0]->amount, 0)
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="" style="text-align: right ">
                                    Balance
                                </td>
                                <td  style="text-align: center ">
                                    <?php
                                    echo number_format($special_income[0]->amount + $grand_whole_sell + $grand_retail_installment_collection + $grand_retail_sell - $grand_expense - $employee_salary[0]->amount - $owner_expense[0]->amount, 0)
                                    ?>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </page>
            <div style="height: 100px; "></div>
        </div>
    </body>
</html>