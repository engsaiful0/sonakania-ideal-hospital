<?php
$sales_id = $this->input->post('sales_id');
$this->db->where('sales_id', $sales_id);
$this->db->from('sales');
$result = $this->db->get();
foreach ($result->result() as $row) {
    $bill_no = $row->bill_no;
    $bill_date = date('d-m-Y', strtotime($row->bill_date));
    $customer_id = $row->customer;
    $customer = $this->db->where('customer_id', $customer_id)->get('customer')->row();
}
$sales_id = $this->input->post('sales_id');
$this->db->where('sales_id', $sales_id);
$this->db->from('sales_details');
$result = $this->db->get();
?>
<form action="<?php echo site_url('product/return_sales'); ?>" method="POST" class="form">
    <table width="100%" border="1">
        <tr>
            <td width="80px;" align="right">
                <b>Bill No:</b>
            </td>
            <td align="left" style="padding:5px;">
                <input type="text" id="bill_no" name="bill_no" class="form-control" readonly="" 
                       value="<?php echo $bill_no; ?>"/>
            </td>
            <td width="80px;" align="right">
                <b>Sale Date:</b>
            </td>
            <td align="left" style="padding:5px;">
                <input type="text"  value="<?php echo $bill_date; ?>" id="bill_date" name="bill_date" class="form-control" readonly=""/>
            </td>
        </tr>
        <tr>
            <td width="80px;">
                <b>Customer:</b>
            </td>
            <td align="left" style="padding:5px;">
                <input type="hidden" id="customer_id" name="customer_id" class="form-control"  value="<?php echo $customer->customer_id; ?>"/>
                <input type="text" id="customer" name="customer" class="form-control" readonly="" value="<?php echo $customer->name; ?>"/>
            </td>

            <td width="80px;">
                <b>Sale Return Date</b>
            </td>
            <td align="left" style="padding:5px;">
                <input type="text" id="sale_return_date" name="sale_return_date" class="form-control date" value="<?php echo date('d-m-Y'); ?>"/>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="padding:5px;">
                <table width="100%" cellpadding="4" border="0" id="cart_tab">
                    <tr>
                        <th style="padding:5px;">
                            Drug
                        </th>
                        <th style="padding:5px;">
                            Sale's Rate
                        </th>
                        <th>
                            Stock
                        </th>
                        <th style="padding:5px;">
                            QTY
                        </th>
                        <th style="padding:5px;">
                            Discount
                        </th>
                        <th style="padding:5px;">
                            Pur Rate
                        </th>
                        <th style="padding:5px;">
                            Self No
                        </th>
                        <th style="padding:5px;">
                            Group Name
                        </th>
                        <th style="padding:5px;">
                            Amount
                        </th>
                        <th style="padding:5px;">
                            &nbsp;
                        </th>
                    </tr>
                    <?php
                    $sequence = 0;
                    $id = '';
                    foreach ($result->result() as $row) {
                        $drug = $row->drug;
                        $sales_rate = $row->sales_rate;
                        $qty = $row->qty;
                        $discounteach = $row->discounteach;
                        $pur_rate = $row->pur_rate;
                        $amount = $row->amount;
                        if ($sequence)
                            $id = $sequence;

                        $condition_stock = array(
                            'drug' => $drug,
                            'company_id' => $this->session->userdata('company_id')
                        );
                        $condition_purchase = array(
                            'drug' => $drug
                        );
                        $condition_product = array(
                            'drug_id' => $drug,
                            'company_id' => $this->session->userdata('company_id')
                        );
                        $details = array();
                        $stock = get_single_value('quantity', 'stock', $condition_stock);
                        $mrp = get_single_value('mrp', 'purchase_details', $condition_purchase);
                        $pur_rate = get_single_value('pur_rate', 'purchase_details', $condition_purchase);
                        $shelf = get_single_value('shelf', 'drug', $condition_product);
                        $type = get_single_value('type', 'drug', $condition_product);
                        $condition_group = array(
                            'drug_type_id' => $type
                        );
                        $group_name = get_single_value('type_name', 'drug_type', $condition_group);
                        ?>
                        <tr>
                            <td style="padding:5px;">
                                <select name="drug[]" class="form-control" id="drug<?php echo $id; ?>" sequence=<?php echo $sequence; ?> onchange="getstock(this,event)" required="" readonly='' style="width:120px;">
                                    <?php
                                    echo make_select('drug', 'drug_id', 'drug_name', array('company_id' => $this->session->userdata('company_id')), $drug);
                                    ?>
                                </select>
                            </td>
                        <script type="text/javascript">
                            $(document).ready(function () {

                                $("#drug<?php echo $id; ?>").select2();



                            });
                        </script>
                        <td style="padding:5px;">
                            <input type="text" id="sales_rate<?php echo $id; ?>" name="sales_rate[]" class="form-control"
                                   sequence=<?php echo $sequence; ?> value="<?php echo $sales_rate; ?>" readonly="">
                        </td>
                        <td style="padding:5px;">
                            <input type="text" id="stock<?php echo $id; ?>" name="stock[]" class="form-control" readonly="" sequence=<?php echo $sequence; ?> value="<?php echo $stock; ?>">
                        </td>
                        <td style="padding:5px;">
                            <input type="text" id="qty<?php echo $id; ?>" name="qty[]" class="form-control" onkeyup="getamount(this, event)" required="" sequence=<?php echo $sequence; ?> value="<?php echo $qty; ?>">
                        </td>
                        <td style="padding:5px;">

                            <input type="text" id="discounteach<?php echo $id; ?>" name="discounteach[]" class="form-control" sequence=0 onkeyup="getamount(this, event)" sequence=<?php echo $sequence; ?> value="<?php echo $discounteach; ?>">
                        </td>
                        <td style="padding:5px;">
                            <input type="text" id="pur_rate<?php echo $id; ?>" name="pur_rate[]" class="form-control" sequence=0 readonly="" sequence=<?php echo $sequence; ?> value="<?php echo $pur_rate; ?>">
                        </td>
                        <td style="padding:5px;">
                            <input type="text" id="group_name<?php echo $id; ?>" name="group_name[]" class="form-control" readonly="" sequence=<?php echo $sequence; ?> value="<?php echo $group_name; ?>">
                        </td>
                        <td style="padding:5px;">
                            <input type="text" id="self_no<?php echo $id; ?>" name="self_no[]" class="form-control"  sequence=<?php echo $sequence; ?> value="<?php echo $shelf; ?>" readonly="">
                        </td>
                        <td style="padding:5px;">
                            <input type="text" onchange="totalamount()" id="amount<?php echo $id; ?>" name="amount[]" class="form-control amount" readonly=""
                                   sequence=<?php echo $sequence; ?> value="<?php echo $amount; ?>">
                        </td>
                        <td style="padding:5px;">
                                <!--<button class="btn btn-danger  btn-xs remove" type="button" sequence=0 onclick="removetr(this,event)"><i class="glyphicon glyphicon-remove"></i></button>-->
                        </td>
            </tr>
            <?php
            $sequence++;
        }
        ?>
    </table>
</td>
</tr>
<tr>
    <td colspan="4" align="right" valign="middle" style="padding:5px;">
        <b>Sub Total:&nbsp;</b>
        <input type="text" name="total" id="total" class="form-control input input-lg" style="width:200px;float: right;">
    </td>
</tr>
<tr>
    <td colspan="4" align="right" valign="middle" style="padding:5px;">
        <b>Discount(%):&nbsp;</b>
        <input type="text" oninput="payable_total_calc()" name="discount" id="discount" class="form-control input input-lg" style="width:200px;float: right;">
    </td>
</tr>
<tr>
    <td colspan="4" align="right" valign="middle" style="padding:5px;">
        <b>Total:&nbsp;</b>
        <input type="text" readonly="" id="payable_total" name="payable_total" id="payable_total" class="form-control input input-lg" style="width:200px;float: right;">
    </td>
</tr>
<tr>
    <td colspan="4" align="right" style="padding:5px;">
        <button type="submit" id="submit" class="btn btn-primary btn-lg">Sales Return</button>&nbsp;
        <a onclick="resetFn()" class="btn btn-primary btn-lg">Reset</a>

    </td>
</tr>
</table>
<script>
     $(function ()
    {
        totalamount();
        $(".form").submit(function (e)
        {
            e.preventDefault();
            var formaction = $(this).attr('action');
            var formdata = $(this).serialize();
            $.ajax(
                    {
                        url: formaction,
                        data: formdata,
                        type: "post",
                        success: function (msg)
                        {
                            if (msg == 1)
                            {
                                $("#reset").click();
                                alert('Sales return completed');
                            }
                            else
                            {
                                $("#result").addClass('alert alert-danger');
                                $("#result").html(msg);
                                $("#result").show();
                            }
                        }
                    });
        });
        $(".date").datepicker(
                {
                    "format": "dd-mm-yyyy"
                });

    });
    function totalamount()
    {
        var amount = $(".amount");
        total = 0;
        total = Number(total);
        $.each(amount, function (k, elm)
        {
            var camount = $(elm).val();
            camount = Number(camount);
            total += camount;
        });
        // total = toFixed(total, 2);
        $("#total").val(total);
    }
    function removetr(element, e)
    {
        var seq = $(element).attr('sequence');
        if (seq > 0)
            $(element).parent().parent().remove();
    }
    function getamount(element, e)
    {
        var seq = $(element).attr('sequence');
        if (seq == 0)
            seq = '';
        var qty = $("#qty" + seq).val();
        var sales_rate = $("#sales_rate" + seq).val();
        var amount = qty * sales_rate;
        $("#amount" + seq).val(amount);
        totalamount();
    }
</script>
