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
<div class="row">
    <div class="col-md-12"> 
        <button onclick="window.print()" class="btn btn-primary" >Print</button>
    </div>
</div>
<div id="report" id="report" style="width: 95%;margin:0 auto;margin-left:45px;;margin-top:40px;">
    <table border="1" border="1" style="width: 98%;border-collapse:collapse;margin:0 auto;color:black">
        <tr style="background-color:#337AB7;color: white  ">
            <td colspan="7" style="text-align: center">Purchase Report</td>
        </tr>
        <tr style="background-color: #337AB7;color: white  ">
            <td colspan="7" style="text-align: center">From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
        </tr>
        <tr>
            <td>Sl</td>
            <td>Supplier</td>
            <td>Amount</td>        
            <td>Date</td>
        </tr>
        <?php
        $query = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                ->where('date<=', date('Y-m-d', strtotime($to_date)))
                ->get('purchase')
                ->result();
        $sl = 1;
        $grand_total_net_total = 0;

        foreach ($query as $query_value) {
            $supplier = $this->db->where('supplier_id', $query_value->supplier_id)
                    ->get('supplier')
                    ->row();
            ?>
            <tr>
                <td>
                    <?php echo $sl++ ?>
                </td>
                <td>
                    <?php echo $supplier->supplier_name ?>
                </td>
                <td>
                    <?php
                    echo number_format($query_value->net_total, 0);
                    $grand_total_net_total += $query_value->net_total;
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
            <td colspan="" style="text-align: right">Total</td>
            <td><?php echo number_format($grand_total_net_total, 0) ?></td>
            <td></td>
        </tr>
    </table>
</div>