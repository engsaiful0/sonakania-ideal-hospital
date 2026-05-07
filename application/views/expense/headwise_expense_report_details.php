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

    .mySpan{
        text-align: center;
        writing-mode: vertical-lr; 
        transform: rotate(180deg);
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
<div id="report" id="report" style="width: 95%;margin:0 auto;margin-left:45px;;margin-top:40px;font-size: 12px;">
    <page size="A4">
        <div class="first">
            <div class="second">                
                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">

                    <tr>
                        <td colspan="20" style="text-align: center">Head wise expense report from date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
                    </tr>
                    <tr>
                        <!--<td>Sl</td>-->
                        <td style="width: 50px">
                            <!--Date-->
                        </td>
                        <?php
                        $sql_head = $this->db->select('*')
                                ->get('expense_head')
                                ->result();
                        $grand_amount_of_all = array();
                        $index = 0;
                        foreach ($sql_head as $sql_head_value) {
                            $query = $this->db
                                    ->select_sum('amount', 'Amount')
                                    ->where('date>=', date('Y-m-d', strtotime($from_date)))
                                    ->where('date<=', date('Y-m-d', strtotime($to_date)))
                                    ->where('expense_head_id', $sql_head_value->expense_head_id)
                                    ->get('expense')
                                    ->result();
                            //  echo '<pre>';
                            //   print_r($query);
                            if ($query[0]->Amount == '') {
                                $grand_amount_of_all[$index] = '0';
                            } else {
                                $grand_amount_of_all[$index] = $query[0]->Amount;
                            }
                            $index++;
                            //   print_r($grand_amount_of_all);
                            // array_push($grand_amount, $query[0]->Amount.'');
                            ?>
                            <td class="vertical-text1 mySpan" style="height:auto;width:0px;text-align:center">
                                <?php echo $sql_head_value->expense_head_name ?></td>
                            <?php
                        }
                        //   die;
                        ?>
                    </tr>



                    <?php
                    $query = '';
                    $sql_head = $this->db->select('*')
                            ->get('expense_head')
                            ->result();
                    $grand_sum = 0;
                    //   $date1 = date_create("2013-03-15");
                    //$date2 = date_create("2013-12-12");
                    $date = date('Y-m-d', strtotime($from_date));
                    $from = strtotime(date('Y-m-d', strtotime($from_date)));
                    $to = strtotime(date('Y-m-d', strtotime($to_date)));
                    $diff = abs($from - $to) / (60 * 60 * 24);

                    // print_r('$from=' . $from_date);
                    // print_r('$to=' . $to);
                    //  var_dump('dif=' . $diff);
                    //  die;
                    for ($i = 1; $i <= $diff + 1; $i++) {
                        ?>
                        <tr>
            <!--                <td>
                            <?php //echo $i ?>
                            </td>-->
                            <td><?php echo date('d-m-Y', strtotime($date)) ?></td>
                            <?php
                            foreach ($sql_head as $sql_head_value) {

                                $query = $this->db
                                        ->where('date', $date)
//                            ->where('date<=', date('Y-m-d', strtotime($to_date)))
                                        ->where('expense_head_id', $sql_head_value->expense_head_id)
                                        ->get('expense')
                                        ->result();
                                $grand_amount = 0;
                                foreach ($query as $query_value) {
                                    $grand_amount += $query_value->amount;
                                }
                                $grand_sum += $grand_amount;
                                ?>

                                <td><?php echo $grand_amount ?></td>
                                <?php
                            }
                            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                        }
                        ?>
                    </tr>
                    <tr>

                        <td colspan="" style="text-align: right">Total</td>
                        <?php
                        // echo '<pre>';
                        //print_r($grand_amount_of_all);
                        $sum = 0;
                        for ($i = 0; $i < count($grand_amount_of_all); $i++) {
                            $sum += $grand_amount_of_all[$i];
                            ?>
                            <td>
                                <?php
                                echo number_format($grand_amount_of_all[$i], 0);
                                ?>
                            </td>
                            <?php
                        }
                        ?>

                    </tr>
                </table>
            </div>
        </div>
    </page>
    <div style="height: 80px;clear: left ">
        &nbsp;
    </div>
    <page size="A4">
        <div class="first">
            <div class="second">

                <table border="1" border="1" style="width: 60%;border-collapse:collapse;margin:0 auto;color:black;margin-top: 70px;">
                    <tr>
                        <td>Sl</td>
                        <td>Owner</td>
                        <td>Amount</td>
                        <td>Date</td>
                    </tr>
                    <?php
                    error_reporting(0);
                    $owner_all = $this->db->select('*')
                            ->get('owner')
                            ->result();
                    $owner_grand = 0;
                    foreach ($owner_all as $owner_all_value) {
                        $sql_owner = $this->db
                                ->where('owner_id', $owner_all_value->owner_id)
                                ->where('date>=', date('Y-m-d', strtotime($from_date)))
                                ->where('date<=', date('Y-m-d', strtotime($to_date)))
                                ->get('owner_payment')
                                ->result();
                        $grand_amount_of_all = array();
                        $index = 0;
                        $sl = 1;
                        $owner_grand_unique = 0;

                        foreach ($sql_owner as $sql_owner_value) {
                            $owner = $this->db->where('owner_id', $sql_owner_value->owner_id)
                                    ->get('owner')
                                    ->row();
                            ?>
                            <tr>
                                <td><?php echo $sl++ ?></td>
                                <td><?php echo $owner->owner_name ?></td>
                                <td><?php
                                    echo $sql_owner_value->amount;
                                    $owner_grand += $sql_owner_value->amount;
                                    $owner_grand_unique += $sql_owner_value->amount;
                                    ?></td>
                                <td><?php echo date('d-m-Y', strtotime($sql_owner_value->date)) ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td colspan="2" style="text-align:right">Total</td>
                            <td><?php echo number_format($owner_grand_unique, 0);
                        ?></td>
                        </tr>

                        <?php
                    }
                    ?>
                    <tr>
                        <td colspan="2" style="text-align:right">Total Owner Payment</td>
                        <td><?php echo number_format($owner_grand, 0);
                    ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:right">Total Expense</td>
                        <td><?php echo number_format($sum, 0);
                    ?></td>
                    </tr>
                    <td colspan="2" style="text-align:right">Total</td>
                    <td><?php echo number_format($sum + $owner_grand, 0) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </page>
 
</div>