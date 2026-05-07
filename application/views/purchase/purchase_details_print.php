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
<div class="modal-content" style="width: 100%; ">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="modal-header hidden-print">
                <h3 style="text-align: center">Purchase Details</h3>	

            </div>
        </div>
        <div class="panel-body">
<div class="row">
    <div class="col-md-12"> 
        <button onclick="window.print()" class="btn btn-primary" >Print</button>
    </div>
</div>
            <div id="report" class="modal-body" style="width: 100%;">

                <?php
               // error_reporting(0);
                $purchase = $this->db
                                ->where('purchase_id', $purchase_id)
                                ->where('is_deleted', '0')
                                ->get('purchase')->row();

                $supplier = $this->db
                                ->where('supplier_id', $purchase->supplier)
                                ->get('supplier')->row();

                $purchase_product = $this->db
                                ->where('purchase_id', $purchase_id)
                                ->where('is_deleted', '0')
                                ->get('purchase_details')->result();
                ?>


                <table class="table" style="width: 100%;margin: 0 auto;border-collapse: collapse;color:black" border="1" >
                  
                    <tr id="1">
                       
                        
                        <td>Supplier</td>
                        <td><b><?php echo $supplier->name ?></b></td>
                        <td>Purchase Id</td>
                        <td><b><?php echo $purchase->mrr ?></b></td>
                        <td>Date</td>
                        <td><b><?php echo date('d-m-Y', strtotime($purchase->mrr_date)) ?></b></td>
                        <td>Total Amount</td>
                        <td><b><?php echo $purchase->total ?></b></td>
                         <td>Paid</td>
                        <td><b><?php echo $purchase->paid ?></b></td>
                    </tr>
                    <tr id="1" style=" background-color:#286090;color: white ">
                        <td>Sl</td>
                        <td>Type</td>
                        <td>Name</td>
                        <td>Box Qty</td>
                        <td>PBQ</td>
                        <td>TPR</td>
                        <td>P.Rate</td>
                        <td>MRP</td>
                        <td>Qty</td>
                        <td>Amount</td>
                    </tr>
                    <?php
                    $sl = 1;
                    $grand_quantity = 0;
                    $grand_amount = 0;
                    foreach ($purchase_product as $purchase_product_value) {

                        // $sales_details_id = $purchase_product_value->sales_details_id;
                        $drug = $this->db
                                ->where('drug_id', $purchase_product_value->drug)
                                ->get('drug')
                                ->row();

                        $drug_type = $this->db
                                ->where('drug_type_id', $purchase_product_value->type)
                                ->get('drug_type')
                                ->row();
                        ?>
                        <tr>
                            <td><?php echo $sl++; ?></td>
                            <td><?php echo $drug_type->type_name ?></td>
                            <td><?php echo $drug->drug_name ?></td>
                            <td><?php
                                echo $purchase_product_value->boxqty;
                                ?></td>
                            <td><?php echo $purchase_product_value->pbq; ?></td>
                            <td><?php
                                echo $purchase_product_value->boxrate;
                                ?></td>
                            <td><?php
                                echo $purchase_product_value->pur_rate;
                                ?></td>
                            <td><?php
                                echo $purchase_product_value->mrp;
                                ?></td>
                            <td><?php
                                echo $purchase_product_value->qty;
                                ?></td>
                            <td><?php
                                echo $purchase_product_value->amount;
                                $grand_amount += $purchase_product_value->amount;
                                ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>

                        <td style="text-align: right " colspan="9">Total</td>


                        <td><?php
                            echo number_format($grand_amount, 0);
                            ?></td>
                    </tr>
                </table>
            </div>
        </div>
      
    </div>
    <!-- Modal Header -->


    <!-- Modal body -->

</div>



