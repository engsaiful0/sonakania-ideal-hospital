<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>

<style>
    .modal-wide .modal-dialog { max-width: 560px; }
</style>

<div class="container-fluid" style="background-color: white; width: 100%;">
    <div class="panel panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="text-center" style="margin:0;">Fixed Asset — Categories</h3>
        </div>
        <div class="panel-body">
            <p>
                <button type="button" class="btn btn-success" id="btnAddCat" data-toggle="modal" data-target="#catModal">
                    <i class="fas fa-plus"></i> Add category
                </button>
                <a href="<?php echo site_url('fixed-assets/register'); ?>" class="btn btn-default">Back to register</a>
            </p>
            <div class="table-responsive">
                <table id="catTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th style="width:140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-wide" id="catModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="catModalTitle">Add category</h4>
            </div>
            <form id="catForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="cat_id" value="" />
                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" class="form-control" name="name" id="cat_name" required maxlength="150" />
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" id="cat_desc" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="is_active" id="cat_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="catSaveBtn">
                        <span class="cat-save-label">Save</span>
                        <span class="cat-save-spinner" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
(function($) {
    var catTable = $('#catTable').DataTable({
        ajax: {
            url: '<?php echo site_url('fixed-assets/categories/datatable'); ?>',
            type: 'GET',
            dataSrc: 'data'
        },
        order: [[0, 'desc']],
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'description', defaultContent: '' },
            { data: 'status_html', orderable: false },
            { data: 'created_at' },
            {
                data: null,
                orderable: false,
                render: function(row) {
                    return '<button type="button" class="btn btn-xs btn-primary fa-cat-edit">Edit</button> ' +
                        '<button type="button" class="btn btn-xs btn-danger fa-cat-del" data-id="' + row.id + '">Delete</button>';
                }
            }
        ]
    });

    $('#btnAddCat').on('click', function() {
        $('#catModalTitle').text('Add category');
        $('#catForm')[0].reset();
        $('#cat_id').val('');
        $('#cat_active').val('1');
    });

    $(document).on('click', '.fa-cat-edit', function() {
        var rec = catTable.row($(this).closest('tr')).data();
        if (!rec) return;
        $('#catModalTitle').text('Edit category');
        $('#cat_id').val(rec.id);
        $('#cat_name').val(rec.name);
        $('#cat_desc').val(rec.description || '');
        $('#cat_active').val(rec.is_active ? '1' : '0');
        $('#catModal').modal('show');
    });

    function catSetSaveLoading($btn, on) {
        var $lab = $btn.find('.cat-save-label');
        var $spin = $btn.find('.cat-save-spinner');
        if (on) {
            $lab.hide();
            $spin.show();
            $btn.prop('disabled', true);
        } else {
            $spin.hide();
            $lab.show();
            $btn.prop('disabled', false);
        }
    }

    $('#catForm').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#catSaveBtn');
        var id = $('#cat_id').val();
        var url = id ? '<?php echo site_url('fixed-assets/categories/update'); ?>' : '<?php echo site_url('fixed-assets/categories/save'); ?>';
        var data = $(this).serialize();
        if (id) {
            data += '&id=' + encodeURIComponent(id);
        }
        catSetSaveLoading($btn, true);
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(res) {
            if (res.success) {
                $.toast({
                    heading: 'Success',
                    text: res.message || 'Saved',
                    icon: 'success',
                    position: 'top-right',
                    hideAfter: 2000,
                    showHideTransition: 'slide'
                });
                $('#catModal').modal('hide');
                catTable.ajax.reload(null, false);
            } else {
                $.toast({ heading: 'Error', text: res.message || 'Failed', icon: 'error', position: 'top-right' });
            }
        }).fail(function() {
            $.toast({ heading: 'Error', text: 'Request failed', icon: 'error', position: 'top-right' });
        }).always(function() {
            catSetSaveLoading($btn, false);
        });
    });

    $(document).on('click', '.fa-cat-del', function() {
        var id = $(this).data('id');
        var $btn = $(this);
        Swal.fire({
            title: 'Delete category?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete'
        }).then(function(result) {
            if (!result.isConfirmed) return;
            var label = $btn.html();
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url('fixed-assets/categories/delete'); ?>',
                data: { id: id },
                dataType: 'json'
            }).done(function(res) {
                if (res.success) {
                    $.toast({ heading: 'Deleted', text: res.message, icon: 'success', position: 'top-right' });
                    catTable.ajax.reload(null, false);
                } else {
                    $.toast({ heading: 'Error', text: res.message, icon: 'error', position: 'top-right' });
                }
            }).always(function() {
                $btn.prop('disabled', false).html(label);
            });
        });
    });
})(jQuery);
</script>
