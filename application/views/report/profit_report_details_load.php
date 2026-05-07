<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #report, #report * {
            visibility: visible;
            overflow: visible;
        }
        #report {
            position: absolute;
            left: 0;
            top: 0;
        }
    }
</style>
<style>
    body
    {
        width:100%;
        background: rgb(204,204,204); 
        overflow-x: hidden;

    }
    page[size="A4"] {

        height: 35cm;
        display: block;
        margin: 0 auto;
        margin-bottom: 0.5cm;


    }

    @media print {
        body, page[size="A4"] {
            width: 21cm;
            height: 29cm;
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
    }
    .third
    {
        width:607px;
        height:820px;
        margin:auto;
    }
    h2{font-size:24px;}



    @page {
        size: A4;
        margin: 0;
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

</style>
<div class="row">
    <div class="col-md-12"> 
        <button onclick="window.print()" class="btn btn-primary" >Print</button>
    </div>
</div>
<div id="report" id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:40px;color:black">
    <?php
    $total_pofit = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
            ->where('date<=', date('Y-m-d', strtotime($to_date)))
            ->get('whole_customer_sells');
    $total_profit_rows = $total_pofit->num_rows();

    $page_limit = ceil($total_profit_rows / 42);
    //echo $page_limit;
    $from = -42;
    $start = 0;
    $grand_profit = 0;
    $grand_total = 0;
    for ($page_no = 1; $page_no <= $page_limit; $page_no++):
        ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
                        <tr style="background-color: #008080;color: white  ">
                            <td colspan="5" style="text-align: center">From Dealer</td>
                        </tr>
                        <tr style="background-color: #008080;color: white">
                            <td colspan="5" style="text-align: center">Profit Report From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                        </tr>
                        <tr>
                            <td>Sl</td>
                            <td>Dealer</td>

                            <td>Sell Amount</td>
                            <td>Profit</td>
                            <td>Date</td>
                        </tr>
                        <?php
//                        $query = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
//                                ->where('date<=', date('Y-m-d', strtotime($to_date)))
//                                ->get('whole_customer_sells')
//                                ->result();
                        $from = $from + 42;
                        $this->db->select('*');
                        $this->db->order_by('dealer_id', 'asc');
                        $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                                ->where('date<=', date('Y-m-d', strtotime($to_date)));
                        $this->db->from('whole_customer_sells');
                        $this->db->limit(42, $from);
                        $query_profit = $this->db->get();
                        $query = $query_profit->result();


                        $sl = 1;               
                        foreach ($query as $query_value) {
                            $dealer = $this->db->where('dealer_id', $query_value->dealer_id)
                                    ->get('dealer')
                                    ->row();
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
                                    echo $query_value->net_total;
                                    $grand_total += $query_value->net_total;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $query_value->profit;
                                    $grand_profit += $query_value->profit;
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

                            <td colspan="" style="text-align: right">Total Sell</td>
                            <td><?php echo number_format($grand_total, 0) ?></td>
                            <td><?php echo number_format($grand_profit, 0) ?></td>
                            <td></td>
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
    $total_pofit = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
            ->where('date<=', date('Y-m-d', strtotime($to_date)))
            ->get('retail_customer_sells');
    $total_profit_rows = $total_pofit->num_rows();

    $page_limit = ceil($total_profit_rows / 42);
    //echo $page_limit;
    $from = -42;
    $start = 0;
    $grand_total_retailer = 0;
    $grand_profit_retailer = 0;
    for ($page_no = 1; $page_no <= $page_limit; $page_no++):
        ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;margin-top:10px;">
                        <tr style="background-color: #0074B3;color: white">
                            <td colspan="6" style="text-align: center">From Retailer</td>
                        </tr>
                        <tr style="background-color: #0074B3;color: white  ">
                            <td colspan="6" style="text-align: center">Profit Report From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                        </tr>
                        <tr>
                            <td>Sl</td>
                            <td>Retailer</td>
                            <td>Id No</td>
                            <td>Sell Amount</td>
                            <td>Profit</td>
                            <td>Date</td>
                        </tr>
                        <?php
//                        $query = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
//                                ->where('date<=', date('Y-m-d', strtotime($to_date)))
//                                ->get('retail_customer_sells')
//                                ->result();
                        $from = $from + 42;
                        $this->db->select('*');
                        $this->db->order_by('retail_customer_id', 'asc');
                        $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                                ->where('date<=', date('Y-m-d', strtotime($to_date)));
                        $this->db->from('retail_customer_sells');
                        $this->db->limit(42, $from);
                        $query_profit = $this->db->get();
                        $query = $query_profit->result();

                        $sl = 1;

                        foreach ($query as $query_value) {
                            $retail_customer = $this->db->where('retail_customer_id', $query_value->retail_customer_id)
                                    ->get('retail_customer')
                                    ->row();
                            ?>
                            <tr>
                                <td>
                                    <?php echo $sl++ ?>
                                </td>
                                <td>
                                    <?php echo $retail_customer->retail_customer_name ?>
                                </td>
                                <td>
                                    <?php echo $query_value->id_no ?>
                                </td>
                                <td>
                                    <?php
                                    echo $query_value->net_total;
                                    $grand_total_retailer += $query_value->net_total;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $query_value->profit;
                                    $grand_profit_retailer += $query_value->profit;
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
                            <td colspan="" style="text-align: right">Total Sell Amount</td>
                            <td><?php echo number_format($grand_total_retailer, 0) ?></td>
                            <td><?php echo number_format($grand_profit_retailer, 0) ?></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </page>
        <div style="height: 100px; "></div>
        <?php
    endfor;
    ?>
    <page size="A4">
        <div class="first">
            <div class="second">
                <table border="1" style="width: 90%;border-collapse:collapse;margin:0 auto;color:black;margin-top:10px;">
                    <tr style="background-color: violet ;color: white  ">
                        <td colspan="" style="text-align: center ">Total</td>
                        <td colspan="" style="text-align: center ">Sell Amount</td>
                        <td colspan="" style="text-align: center ">Profit</td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Sell from Dealer</td>
                        <td><?php echo number_format($grand_total, 0) ?></td>
                        <td><?php echo number_format($grand_profit, 0) ?></td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Sell from Retailer</td>
                        <td><?php echo number_format($grand_total_retailer, 0) ?></td>
                        <td><?php echo number_format($grand_profit_retailer, 0) ?></td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Grand Total Sell Amount</td>
                        <td><?php echo number_format($grand_total_retailer + $grand_total, 0) ?></td>
                        <td><?php echo number_format($grand_profit + $grand_profit_retailer, 0) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </page>
</div>