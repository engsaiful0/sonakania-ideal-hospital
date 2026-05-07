
<?php
$this->load->view("common/top-page");
?>

<?php
$this->load->view("common/navigation-top");
?>
<form action="<?php echo site_url('product/confirm_purchase'); ?>" method="POST" class="form">
    <div class="container-fluid">
        <div class="col-sm-12 col-md-12 main">
            <div class="table-responsive">
                <script type="text/javascript">
                    function supplier_set()
                    {
                        var supplier = document.getElementById('supplier').value;
                        // alert(supplier);

                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function () {
                            if (xhttp.readyState == 4 && xhttp.status == 200) {


                                document.getElementById("drug_add0").innerHTML = xhttp.responseText;
                                // alert(xhttp.responseText);


                            }
                        }

                        xhttp.open("POST", "<?php echo site_url('product/supplierget'); ?>", true);
                        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//                                    xhttp.send("fname=Henry&lname=Ford");
                        xhttp.send("supplier=" + supplier);
                    }
                    function addMore()
                    {


                        var supplier = document.getElementById('supplier').value;
                        // alert(supplier);
                        if (supplier == '')
                        {
                            alert("Select Supplier First");
                        }
                        else
                        {
                            var id_control = document.getElementById('id_control').value * 1;
                            var next_id = id_control + 1;
                            // alert(next_id);


                            document.getElementById('id_control').value = next_id;
                            var xhttp = new XMLHttpRequest();
                            xhttp.onreadystatechange = function () {
                                if (xhttp.readyState == 4 && xhttp.status == 200) {


                                    $('#cart_tab1').prepend(xhttp.responseText);
                                    $("#drug_add" + next_id).select2();
                                    $("#type_name_" + next_id).select2();
                                    $(".date" + next_id).datepicker(
                                            {
                                                "format": "dd-mm-yyyy"
                                            });
                                       $('#boxqty'+next_id).val($('#boxqty0').val());   
                                       $('#re_order_qty'+next_id).val($('#re_order_qty0').val()); 
                                       $('#pbq'+next_id).val($('#pbq0').val()); 
                                       $('#boxrate'+next_id).val($('#boxrate0').val()); 
                                       $('#discount'+next_id).val($('#discount0').val()); 
                                       $('#vat'+next_id).val($('#vat0').val()); 
                                       
                                }
                            }

                            xhttp.open("POST", "<?php echo site_url('product/addMorePurchase'); ?>", true);
                            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//                                    xhttp.send("fname=Henry&lname=Ford");
                            xhttp.send('next_id=' + next_id + '&supplier=' + supplier);
                        }
                    }
                    
                    function drug_name_load(type_name)
                        {
                            var xhttp = new XMLHttpRequest();

                            var data = type_name.split("_");
                            var type_name_val = document.getElementById(type_name).value;
                            var supplier = document.getElementById('supplier').value;
                            xhttp.onreadystatechange = function () {
                                if (xhttp.readyState == 4 && xhttp.status == 200) {

                                    document.getElementById("drug_add" + data[2]).innerHTML = xhttp.responseText;
//alert(xhttp.responseText);

                                    $("#type_name_" + data[2]).select2();
                                 //   $("#type_name_" + data[2]).select2();

                                }
                            }

                            xhttp.open("POST", "<?php echo site_url('product/add_drug_name_purchase'); ?>", true);
                            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                            xhttp.send("type_name_val=" + type_name_val+"&supplier="+supplier);
                        }

                    $(document).ready(function () {


                        $("#drug_add0").select2();
                        $("#customer_type").select2();
                        $("#customer_id").select2();
                        $("#supplier").select2();
                    });</script>
                <table width="100%" border="1">
                    <tr>
                        <td width="80px;" align="right">
                            <b>MRR No:</b>
                        </td>
                        <td align="left" style="padding:5px;width: 400px;">
                            <input type="text" id="mrr" name="mrr" class="form-control" readonly="" 
                                   value="<?php echo $mrr = generate_id('purchase', 'mrr', 'MRR-', 10); ?>"/>
                        </td>
                        <td width="80px;" align="left">
                            <b>MRR Date:</b>
                        </td>
                        <td align="left" style="padding:5px;">
                            <input type="text"  value="<?php echo date('d-m-Y'); ?>" id="mrr_date" name="mrr_date" class="form-control" readonly=""/>
                        </td>
                    </tr>
                    <tr>
                        <td width="80px;">
                            <b>Invoice No:</b>
                        </td>
                        <td align="left" style="padding:5px;">
                            <input type="text"  value="" id="invoice" name="invoice" placeholder="Invoice No" class="form-control" required=""/>
                        </td>
                        <td>
                            <b>Invoice&nbsp;Date:&nbsp;</b>
                        </td>
                        <td align="left" style="padding:5px;">
                            <input type="text"  value="" id="invoice_date" name="invoice_date" placeholder="Invoice Date" class="form-control date" required=""/>
                        </td>
                    </tr>
                    <tr>
                        <td width="80px;">
                            <b>Supplier:</b>
                        </td>
                        <td align="left" style="padding:5px;">
                            <select name="supplier" onchange="supplier_set()" id="supplier" class="form-control" required="">
                                <option value="">Supplier</option>
                                <?php
                                $company_id = $this->session->userdata('comapny_id');
                                $condition = array(
                                    "company_id" => $company_id
                                );
                                echo make_select('supplier', 'supplier_id', 'name', $condition);
                                ?>
                            </select>
                        </td>
                        <td colspan=""><b>Type</b></td>
                        <td align="left" style="padding:5px;">
                            <select name="pur_type" class="form-control input" id="pur_type">
                                <option value="purchase">Purchase</option>

                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="padding:5px;">
                            <table width="100%" border="0" id="cart_tab">
                                <tr>
                                    <th style="width:100px;text-align: center;">
                                Type
                                    </th>
                                    <th style="width:185px;text-align: center;">
                                    Drug
                                        <button type="button" onclick="addMore()" id="add_more" class="btn btn-sm btn-default">
                                            <i class="glyphicon glyphicon-plus"></i>
                                        </button>
                                    </th>
                                    <th style="width:55px;text-align: center;">
                                    <p>Box<br>Qty</p>
                                    </th>
                                    <th style="width:70px;text-align: center;">
                                        Re<br>Qty
                                    </th>
                                    <th style="text-align: center;width: 78px">
                                        PBQ
                                    </th>
                                    <th style="width:60px;text-align: center;">
                                        TP<br>Rate
                                    </th>
                                    <th style="width:125px;text-align: center;">
                                        Exp<br>Date
                                    </th>
                                    <th style="width:45px;text-align: center;">
                                    <p>  Dis<br>(%)</p>
                                    </th>
                                    <th style="text-align: center;width: 50px">
                                        VAT<br>(%)
                                    </th>
                                    
                                    <th style="text-align: center;width: 70px">
                                        Pur<br>Rate
                                    </th>
                                    <th style="text-align: center;">
                                        MRP
                                    </th>
                                    <th style="text-align: center;">
                                        WSR
                                    </th>
                                    <th style="text-align: center;width: 60px">
                                        Qty
                                    </th>
                                    <th style="text-align: center;">
                                        Stock
                                    </th>
                                    <th style="text-align: center;">
                                        Amount
                                    </th>
                                   
                                </tr>
                            </table>
                            <table width="100%"  id="cart_tab1">
                                <tr>
                                    <td>
                                        <select name="type_name[]" class="form-control" id="type_name_0" sequence=0 onchange="drug_name_load(this.id)" required="" style="width:110px;">
                                        <option value="" selected="">Select Type</option>
                                        <?php
                                        $sql = $this->db->select('*')->get('drug_type')->result();

                                        foreach ($sql as $value) {
                                            ?>
                                            <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    </td>
                                    <td style="padding:5px;">
                                        <input type="hidden"  value="" id="supplier_id" name="supplier_id" class="form-control" >
                                        <input type="hidden"  value="0"  name="id_control"  id="id_control" class="form-control" >
                                        <select name="drug[]"  class="form-control" id="drug_add0" sequence=0 onchange="details(this, event);" required="" style="width:160px;">
                                            <option value="" selected="">Select Drug</option>

                                        </select>
                                    </td>
                                    <td >
                                        <input type="text"  value="" id="boxqty0" name="boxqty[]"  class="form-control" sequence=0 onkeyup="getamount(this, event), getqty(this, event)" required="">
                                    </td>
                                    <td style="padding:5px;">
                                        <input type="text"  value="" id="re_order_qty0" name="re_order_qty[]" class="form-control" sequence=0 >
                                    </td>
                                    <td style="padding:5px;">
                                        <input type="text"  value="" id="pbq0" name="pbq[]" class="form-control" sequence=0 onkeyup="getamount(this, event), purchase_rate(this, event), getqty(this, event)" required="">
                                    </td>
                                    <td style="padding:5px;">
                                        <input type="text"  value="" id="boxrate0" name="boxrate[]" class="form-control" sequence=0 onkeyup="purchase_rate(this, event), getamount(this, event)" required="">
                                    </td>
                                    <td style="padding:5px;">
                                        <input type="text"  value="" id="invoice_date0" name="expdate[]" style="width:100px;" class="form-control date" sequence=0  required="">
                                    </td>
                                    <td style="padding:5px;">
                                        <input type="text"  value="" id="discount0" name="discount[]" style="width:40px;" class="form-control" sequence=0 onkeyup="purchase_rate(this, event), getamount(this, event)">
                                    </td>
                                    <td style="padding:5px;">
                                        <input type="text"  value="" id="vat0" name="vat[]" style="width:40px;" class="form-control" sequence=0 onkeyup="purchase_rate(this, event), getamount(this, event)">
                                    </td>
                                    
                                    <td style="padding:5px;">
                                        <input type="text"  value="" id="pur_rate0" name="pur_rate[]" class="form-control"  sequence=0 required="">
                                    </td>
                                    <td style="padding:5px;">
                                        <input type="text"  value="" id="mrp0" name="mrp[]" class="form-control" sequence=0 >
                                    </td>
                                    <td style="padding:5px;">
                                        <input type="text"  value="" id="wsr0" name="whole_sale_rate[]" class="form-control" sequence=0 >
                                    </td>
                                    <td style="padding:5px;">
                                        <input type="text"  value="" id="qty0" name="qty[]" class="form-control" readonly="" sequence=0>
                                    </td>
                                    <td style="padding:5px;">
                                        <input type="text"  value="" id="stock0" name="stock[]" class="form-control" readonly="" sequence=0 required="">
                                    </td>
                                    <td style="padding:5px;">
                                        <input type="text"  value="" id="amount0" name="amount[]" class="form-control amount" readonly="" sequence=0 required="">
                                    </td>
                                    <td style="padding:5px;">
                                        <button class="btn btn-danger  btn-xs remove" type="button" sequence=0 onclick="removetr(this, event)"><i class="glyphicon glyphicon-remove"></i></button>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right" valign="middle" style="padding:5px;">
                            <b>Total:&nbsp;</b>
                            <input type="text" name="total" id="total" readonly="" class="form-control input input-lg" style="width:200px;float: right;">
                        </td>
                    </tr>
					
                    <tr>
                        <td colspan="4" align="right" valign="middle" style="padding:5px;">
                            <b>Payment Type:&nbsp;</b>
                            <select type="text" name="paymenttype" required="" id="paymenttype" class="form-control input input-lg" style="width:200px;float: right;">
                                <option></option>
                                <option>Cash</option>
                                <option>Check</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right" valign="middle" style="padding:5px;">
                            <b>Paid:&nbsp;</b>
                            <input type="text" name="paid" required="" oninput="dueCal()" id="paid" class="form-control input input-lg" style="width:200px;float: right;">
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4" align="right" valign="middle" style="padding:5px;">
                            <b>Due:&nbsp;</b>
                            <input type="text" name="due" id="due" readonly="" class="form-control input input-lg" style="width:200px;float: right;">
                        </td>
                    </tr>
					 <tr>
                        <td colspan="4" align="right">
                            <img src="<?php echo base_url()?>images/ajax-loader.gif" id="img" style="display:none"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right" style="padding:5px;">
                            <button type="submit" id="submit" class="btn btn-primary btn-lg">Complete Purchase</button>&nbsp;
                            <a onclick="resetFn()" class="btn btn-primary btn-lg">Reset</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    function resetFn()
    {
        
        window.location.href = "http://192.168.0.102/maadrug/product/purchase_product";
    }
    $(document).ready(function () {

        $("#drug_add0").select2();
        $("#supplier").select2();
        $("#paymenttype").select2();
        $("#type_name_0").select2();
        
    });
    $(document).keypress(function (e) {
        if (e.which == 13) {
            //alert('enter key is pressed');
            var paid = $('#paid').val();
            // alert(paid);
            if (paid > 0)
            {
                $("#submit").click();
            }
            else if (paid == 0)
            {
                $("#add_more").click();
            }

        }

    });
    function dueCal()
    {
        var due = $("#due").val() * 1;
        var total = $("#total").val() * 1;
        var paid = $("#paid").val() * 1;
        //$("#due").val(total - paid);

        if (paid > total)
        {
            alert('Paid amount can not be greater thn total amount');
            $('#paid').val(0);
            $('#due').val(0);
        }
        else
        {
            var due = total - paid;
            $("#due").val(due.toFixed(2));
        }
    }
    function removetr(element, e)
    {
        var seq = $(element).attr('sequence');
        if (seq > 0)
            $(element).parent().parent().remove();
        totalamount();
    }
    function purchase_rate(element, e)
    {
        var seq = $(element).attr('sequence');
        
        var box_rate = ($("#boxrate" + seq).val());
        var boxqty = ($("#boxqty" + seq).val());
        var pbq = ($("#pbq" + seq).val());
        var vat = ($("#vat" + seq).val());
        var discount = ($("#discount" + seq).val());
        if (!vat)
            vat = 0;
        if (!discount)
            discount = 0;
        var pur_rate = box_rate / pbq;
        
        var box_rate_af_dis=box_rate-((discount * box_rate) / 100);
        
        pur_rate = (box_rate_af_dis + ((vat * box_rate_af_dis) / 100))/pbq;
        pur_rate = pur_rate.toFixed(2);
        $("#pur_rate" + seq).val(pur_rate);
    }
    function getamount(element, e)
    {
        
        var seq = $(element).attr('sequence');
        
        var box_rate = $("#boxrate" + seq).val();
        var pbq = $("#pbq" + seq).val();
        var boxqty = $("#boxqty" + seq).val();
        var vat = $("#vat" + seq).val();
        var discount = $("#discount" + seq).val();
           var total_qty = boxqty;
//        alert(total_qty);
        var discount=(boxqty*box_rate*(discount/100));
        var amount = total_qty * box_rate;
        
        
//        var amount = boxqty * box_rate;
        
//        var amount_discount = (amount * discount) / 100;
        
//        var amount_after_discount=amount-amount_discount;
        var amount_vat = (box_rate*boxqty) * (vat / 100);
        
        
        
        
        amount = amount + amount_vat-discount;
        amount = amount.toFixed(2);
        $("#amount" + seq).val(amount);
        totalamount();
    }
    function getqty(element, e)
    {
        var seq = $(element).attr('sequence');
        
        var boxqty = $("#boxqty" + seq).val();
        var pbq = $("#pbq" + seq).val();
        var totalqty = boxqty * pbq;
        $("#qty" + seq).val(totalqty);
    }
    function details(element, e)
    {
        var seq = $(element).attr('sequence');
        var drug = $("#drug_add" + seq).val();
        $.ajax({
            type: "post",
            url: "<?php echo site_url('product/details') ?>",
            data: "drug=" + drug,
            dataType: "json",
            success: function (msg)
            {
                $("#stock" + seq).val(msg.stock);
                $("#wsr" + seq).val(msg.wsr);
                $("#mrp" + seq).val(msg.mrp);
                $("#pur_rate" + seq).val(msg.pur_rate);
            }
        });
    }
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
        $("#total").val(total.toFixed(2));
    }
    $(function ()
    {
        totalamount();

        $(".form").submit(function (e)
        {
			$('#img').show();
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
								$('#img').hide();
                                $("#reset").click();
                                alert('Purchase Completed');
                                window.location.reload();
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
    

    var i = 1;
            $("#add_more").click(function () {

        var newStr = $("#cart_tab tr:nth-child(2)").clone().find("input,select,button").each(function () {
            $(this).val('').attr('id', function (_, id) {
                return id + i
            }).attr('sequence', function (_, sequence) {
                return i
            });
        }).end().appendTo("#cart_tab");
        i++;
        newStr.find(".date").each(function () {
            $(this).attr("id", "").removeData().off();
            $(this).find('.add-on').removeData().off();
            $(this).find('input').removeData().off();
            $(this).datepicker();
        });
                
    });
    $(".date").datepicker(
    {
    "format": "dd-mm-yyyy"
    });
    });
</script>
<?php
$this->load->view("common/bottom-page");
?>