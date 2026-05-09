<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css" />
<style>
    .fa-thumb { max-height: 44px; max-width: 60px; }
    #assetsTable .btn { margin-bottom: 4px; }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>

<div class="container-fluid" style="background-color: white; width: 100%;">
    <div class="panel panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="text-center" style="margin:0;">Fixed Assets — Register</h3>
        </div>
        <div class="panel-body">
            <p class="clearfix">
                <a href="<?php echo site_url('fixed-assets/add'); ?>" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add asset
                </a>
                <a href="<?php echo site_url('fixed-assets'); ?>" class="btn btn-default">Dashboard</a>
                <a href="<?php echo site_url('fixed-assets/categories'); ?>" class="btn btn-default">Categories</a>
                <a href="<?php echo site_url('fixed-assets/reports'); ?>" class="btn btn-default">Reports</a>
            </p>
            <div class="table-responsive">
                <table id="assetsTable" class="table table-striped table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Purchase date</th>
                            <th>Cost</th>
                            <th>Annual depr.</th>
                            <th>Book value</th>
                            <th>Department</th>
                            <th>Staff</th>
                            <th>Condition</th>
                            <th style="min-width:200px;">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
(function($) {
    var dt = $('#assetsTable').DataTable({
        processing: true,
        serverSide: true,
        ordering: false,
        ajax: {
            url: '<?php echo site_url('fixed-assets/ajax/asset-datatable'); ?>',
            type: 'POST'
        },
        order: [[2, 'asc']],
        pageLength: 25,
        columns: [
            { data: 0, orderable: false, searchable: false },
            { data: 1 },
            { data: 2 },
            { data: 3 },
            { data: 4 },
            { data: 5 },
            { data: 6 },
            { data: 7 },
            { data: 8 },
            { data: 9 },
            { data: 10 },
            { data: 11, orderable: false, searchable: false }
        ]
    });

    $(document).on('click', '.fa-del-asset', function() {
        var id = $(this).data('id');
        var $btn = $(this);
        Swal.fire({
            title: 'Delete this asset?',
            text: 'Maintenance records will also be removed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete'
        }).then(function(result) {
            if (!result.isConfirmed) return;
            var label = $btn.html();
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url('fixed-assets/ajax/delete-asset'); ?>',
                data: { id: id },
                dataType: 'json'
            }).done(function(res) {
                if (res.success) {
                    $.toast({ heading: 'Deleted', text: res.message, icon: 'success', position: 'top-right' });
                    dt.ajax.reload(null, false);
                } else {
                    $.toast({ heading: 'Error', text: res.message, icon: 'error', position: 'top-right' });
                }
            }).fail(function() {
                $.toast({ heading: 'Error', text: 'Request failed', icon: 'error', position: 'top-right' });
            }).always(function() {
                $btn.prop('disabled', false).html(label);
            });
        });
    });
})(jQuery);
</script>
