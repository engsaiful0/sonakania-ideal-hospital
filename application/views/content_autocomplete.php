<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>    
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    
        <div id="body">
        Text: <input type="text" id="autocomplete" />
    </div>
    <div id="body">
      Text: <input type="text" id="code" />
    </div>
    <script>
            $(document).ready(function () {            
                $( "#autocomplete" ).autocomplete({
                    source: function(request, response) {
                        $.ajax({ 
                            url: "<?php echo site_url('PurchaseGoodsController/purchase_goods_invoice_no_load'); ?>",
                            data: { parameter: request.term},
                            dataType: "json",
                            type: "POST",
                            success: function(data){
                                response(data);
                            }    
                        });
                    },
                    select: function (event, ui) {
                        $('#autocomplete').val(ui.item.label);
                        $('#code').val(ui.item.value);
                        return false;
                    }
                });
        });
        </script>