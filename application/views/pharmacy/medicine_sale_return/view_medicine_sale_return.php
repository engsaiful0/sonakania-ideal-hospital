<?php
$permissions = $this->session->userdata('permissions');
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Return Medicine Sale Return</h3>
    </div>
    <div class="panel-body" style="width: 100%;">
        <script>
            $(document).ready(function() {
                $('#return_invoice_no').focus();
            });
            $(document).ready(function() {
                $("#return_invoice_no").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "<?php echo site_url('MedicineSaleReturnWithoutInvoiceController/return_invoice_no_load'); ?>",
                            data: {
                                parameter: request.term
                            },
                            dataType: "json",
                            type: "POST",
                            success: function(data) {
                                response(data);
                            }
                        });
                    },
                    select: function(event, ui) {
                        $('#return_invoice_no').val(ui.item.label);
                        $('form').submit(); // Automatically submit the form
                        return false;
                    }
                });
            });

            function deleteMedicineSaleReturn(medicine_sale_return_id_without_invoice, row_id) {
                Swal.fire({
                    title: 'Do you want to delete this?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "<?php echo site_url('MedicineSaleReturnWithoutInvoiceController/medicine_sale_return_delete_ajax'); ?>",
                            type: 'POST',
                            data: {
                                medicine_sale_return_id_without_invoice: medicine_sale_return_id_without_invoice
                            },
                            success: function(response) {
                                var res = JSON.parse(response);
                                if (res.status == 'success') {
                                    $.toast({
                                        heading: 'Success',
                                        text: res.message,
                                        showHideTransition: 'slide',
                                        position: 'top-right',
                                        hideAfter: 1000,
                                        icon: 'success'
                                    });
                                    $('#' + row_id).remove();
                                } else {
                                    $.toast({
                                        heading: 'Error',
                                        text: res.message,
                                        showHideTransition: 'slide',
                                        position: 'top-right',
                                        hideAfter: 2000,
                                        icon: 'error'
                                    });
                                }
                            }
                        });
                    }
                });
            }
        </script>
        <?php if (in_array('pharmacy_search_medicine_sell_return', $permissions)) { ?>
            <form method="post" action="<?php echo base_url() . "index.php/MedicineSaleReturnWithoutInvoiceController/view_medicine_sale_return"; ?>">
                <table>
                    <tr>
                        <td>Invoice No</td>
                        <td>From Date</td>
                        <td>To Date</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <input placeholder="Scan or Enter Invoice ID .." type="text" id="return_invoice_no" name="return_invoice_no" class="form-control" />
                        </td>
                        <td>
                            <input id="datepicker1" placeholder="Select from date" name="from_date" class="form-control">
                        </td>
                        <td>
                            <input id="datepicker2" placeholder="Select to date" name="to_date" class="form-control">
                        </td>
                        <td><input type="submit" class="btn btn-primary" value="Search"></td>
                    </tr>
                </table>
            </form>
        <?php } ?>
        <?php if (isset($detailsList) && is_array($detailsList) && !empty($detailsList)) : ?>
            <!-- Bulk Action Buttons -->
            <div class="row bulk-action-buttons">
                <div class="col-md-12">
                    <button type="button" class="btn btn-warning btn-sm" onclick="performBulkAction('print')" title="Print Selected">
                        <i class="glyphicon glyphicon-print"></i> Print Selected
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="performBulkAction('delete')" title="Delete Selected">
                        <i class="glyphicon glyphicon-trash"></i> Delete Selected
                    </button>
                    <button type="button" class="btn btn-info btn-sm" onclick="performBulkAction('export')" title="Export Selected">
                        <i class="glyphicon glyphicon-export"></i> Export Selected
                    </button>
                    <span id="selected-count" class="badge badge-info" style="margin-left: 10px;">0 selected</span>
                </div>
            </div>
            
            <table class="table table-hover table-bordered table-condensed">
                <tr>
                    <td>
                        <input type="checkbox" id="select_all_pharmacy_medicine_sale_return_without_invoice_checkbox" onchange="toggleAllCheckboxes()">
                    </td>
                    <td>#</td>
                    <td>Name</td>
                    <td>Return Invoice No</td>
                    <td>Total</td>
                    <td>Discount</td>
                    <td>Total Discount</td>
                    <td>Return Amount</td>
                    <td>Date</td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td>User</td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('pharmacy_print_medicine_sell_return', $permissions)) { ?>
                        <td>Print</td>
                    <?php } ?>
                    <?php if (in_array('pharmacy_edit_medicine_sell_return', $permissions)) { ?>
                        <td>Edit</td>
                    <?php } ?>
                    <?php if (in_array('pharmacy_delete_medicine_sell_return', $permissions)) { ?>
                        <td>Delete</td>
                    <?php } ?>
                </tr>
                <?php
                error_reporting(0);
                $sl = 1;
                for ($i = 0; $i < count($detailsList); ++$i) {
                    $medicine_sale_return_id = $detailsList[$i]->medicine_sale_return_id_without_invoice;
                    $medicine_sale = $this->db->where('medicine_sale_id', $detailsList[$i]->medicine_sale_id)->get('medicine_sales')->row();
                    $user = getUserById($detailsList[$i]->user_id);
                ?>
                    <tr id="medicine-sale-return-row-<?php echo $medicine_sale_return_id; ?>">
                        <td>
                            <input type="checkbox" class="pharmacy_medicine_sale_return_without_invoice_checkbox" value="<?php echo $medicine_sale_return_id; ?>" onchange="updateSelectAllCheckbox()">
                        </td>
                        <td><?php echo $sl++ ?></td>
                        <td><?php echo $detailsList[$i]->name != '' ? $detailsList[$i]->name : ''; ?></td>
                        <td><?php echo $detailsList[$i]->return_invoice_no ?></td>
                        <td><?php echo $detailsList[$i]->total ?></td>
                        <td><?php echo $detailsList[$i]->discount ?></td>
                        <td><?php echo $detailsList[$i]->total_discount ?></td>
                        <td><?php echo $detailsList[$i]->return_amount ?></td>
                        <td><?php echo date('d-m-Y', strtotime($detailsList[$i]->return_date)) ?></td>
                        <td><?php echo $user->user_name ?? "" ?> </td>
                        <?php if (in_array('pharmacy_print_medicine_sell_return', $permissions)) { ?>
                            <td>
                                <a class="btn btn-danger" href="<?php echo base_url("print-medicine-sale-return-without-invoice-again/$medicine_sale_return_id") ?>"><i class="glyphicon glyphicon-print" aria-hidden="true"></i>
                                </a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('pharmacy_edit_medicine_sell_return', $permissions)) { ?>
                            <td>
                                <a id="medicinsellreturn_id_<?php echo $detailsList[$i]->medicine_sale_return_id_without_invoice ?>" class="btn btn-primary" href="<?php echo base_url() ?>medicine-sale-return/edit/<?php echo $detailsList[$i]->medicine_sale_return_id_without_invoice ?>"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('pharmacy_delete_medicine_sell_return', $permissions)) { ?>
                            <td><a onclick="deleteMedicineSaleReturn(<?php echo $medicine_sale_return_id; ?>, 'medicine-sale-return-row-<?php echo $medicine_sale_return_id; ?>')" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
                        <?php } ?>
                    </tr>
                <?php
                }
                ?>
            </table>
            <div style="width:70%;margin:0 auto;text-align:center">
                <p><?php echo $pagination; ?></p>
            </div>
        <?php else : ?>
            <p style="text-align:center">No records found.</p>
        <?php endif; ?>
    </div>
</div>

<style>
    .pharmacy_medicine_sale_return_without_invoice_checkbox {
        transform: scale(1.2);
        margin: 0;
    }
    
    #select_all_pharmacy_medicine_sale_return_without_invoice_checkbox {
        transform: scale(1.2);
        margin: 0;
    }
    
    #selected-count {
        font-size: 12px;
        padding: 5px 10px;
    }
    
    .bulk-action-buttons {
        margin-bottom: 10px;
    }
    
    .bulk-action-buttons .btn {
        margin-right: 5px;
    }
</style>

<script type="text/javascript">
    // Function to toggle all checkboxes when select all is clicked
    function toggleAllCheckboxes() {
        var selectAllCheckbox = document.getElementById('select_all_pharmacy_medicine_sale_return_without_invoice_checkbox');
        var individualCheckboxes = document.querySelectorAll('.pharmacy_medicine_sale_return_without_invoice_checkbox');
        
        for (var i = 0; i < individualCheckboxes.length; i++) {
            individualCheckboxes[i].checked = selectAllCheckbox.checked;
        }
        
        // Update selected count display
        var selectedCountElement = document.getElementById('selected-count');
        if (selectedCountElement) {
            var checkedCount = selectAllCheckbox.checked ? individualCheckboxes.length : 0;
            selectedCountElement.textContent = checkedCount + ' selected';
        }
    }
    
    // Function to update select all checkbox based on individual checkbox states
    function updateSelectAllCheckbox() {
        var selectAllCheckbox = document.getElementById('select_all_pharmacy_medicine_sale_return_without_invoice_checkbox');
        var individualCheckboxes = document.querySelectorAll('.pharmacy_medicine_sale_return_without_invoice_checkbox');
        var checkedCount = 0;
        
        for (var i = 0; i < individualCheckboxes.length; i++) {
            if (individualCheckboxes[i].checked) {
                checkedCount++;
            }
        }
        
        // Update select all checkbox state
        if (checkedCount === 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        } else if (checkedCount === individualCheckboxes.length) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
        }
        
        // Update selected count display
        var selectedCountElement = document.getElementById('selected-count');
        if (selectedCountElement) {
            selectedCountElement.textContent = checkedCount + ' selected';
        }
    }
    
    // Function to get selected medicine sale return IDs
    function getSelectedMedicineSaleReturnIds() {
        var selectedIds = [];
        var individualCheckboxes = document.querySelectorAll('.pharmacy_medicine_sale_return_without_invoice_checkbox:checked');
        
        for (var i = 0; i < individualCheckboxes.length; i++) {
            selectedIds.push(individualCheckboxes[i].value);
        }
        
        return selectedIds;
    }
    
    // Function to perform bulk actions on selected items
    function performBulkAction(action) {
        var selectedIds = getSelectedMedicineSaleReturnIds();
        
        if (selectedIds.length === 0) {
            alert('Please select at least one item to perform this action.');
            return;
        }
        
        if (confirm('Are you sure you want to perform this action on ' + selectedIds.length + ' selected item(s)?')) {
            // Here you can add the logic for bulk actions like bulk delete, bulk print, etc.
            console.log('Selected IDs:', selectedIds);
            console.log('Action:', action);
            
            // Example: Bulk delete
            if (action === 'delete') {
                // Add your bulk delete logic here
                alert('Bulk delete functionality can be implemented here.');
            }
        }
    }
</script>