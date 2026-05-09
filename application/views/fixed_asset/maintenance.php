<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>

<div class="container-fluid" style="background-color: white; width: 100%;">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="text-center" style="margin:0;">
                Maintenance — <?php echo html_escape($asset->asset_name); ?> (<?php echo html_escape($asset->asset_code); ?>)
            </h3>
        </div>
        <div class="panel-body">
            <p>
                <a href="<?php echo site_url('fixed-assets/edit/' . $asset->id); ?>" class="btn btn-default">Edit asset</a>
                <a href="<?php echo site_url('fixed-assets/register'); ?>" class="btn btn-default">Back to register</a>
            </p>

            <div class="row">
                <div class="col-md-7">
                    <h4>History</h4>
                    <div class="table-responsive">
                        <table id="maintTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Cost</th>
                                    <th>Performed by</th>
                                    <th>Next due</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="col-md-5">
                    <h4>Add maintenance</h4>
                    <form id="maintForm" class="form-horizontal">
                        <input type="hidden" name="asset_id" value="<?php echo (int) $asset->id; ?>" />
                        <div class="form-group">
                            <label>Date *</label>
                            <input type="date" class="form-control" name="maintenance_date" required value="<?php echo date('Y-m-d'); ?>" />
                        </div>
                        <div class="form-group">
                            <label>Description *</label>
                            <textarea name="description" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Cost</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="cost" value="0" />
                        </div>
                        <div class="form-group">
                            <label>Performed by</label>
                            <input type="text" class="form-control" name="performed_by" maxlength="200" />
                        </div>
                        <div class="form-group">
                            <label>Next due date</label>
                            <input type="date" class="form-control" name="next_due_date" />
                        </div>
                        <button type="submit" class="btn btn-primary" id="maintSaveBtn">Save record</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
(function($) {
    var assetId = <?php echo (int) $asset->id; ?>;

    var mt = $('#maintTable').DataTable({
        ajax: {
            url: '<?php echo site_url('fixed-assets/ajax/maintenance-datatable'); ?>',
            type: 'POST',
            data: function(d) {
                d.asset_id = assetId;
            },
            dataSrc: 'data'
        },
        order: [[1, 'desc']],
        columns: [
            { data: 0 },
            { data: 1 },
            { data: 2 },
            { data: 3 },
            { data: 4 },
            { data: 5 },
            { data: 6, orderable: false, searchable: false }
        ]
    });

    $('#maintForm').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#maintSaveBtn');
        var label = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('fixed-assets/ajax/save-maintenance'); ?>',
            data: $(this).serialize(),
            dataType: 'json'
        }).done(function(res) {
            if (res.success) {
                $.toast({ heading: 'Saved', text: res.message, icon: 'success', position: 'top-right' });
                $('#maintForm')[0].reset();
                $('input[name="asset_id"]').val(assetId);
                $('input[name="maintenance_date"]').val('<?php echo date('Y-m-d'); ?>');
                $('input[name="cost"]').val('0');
                mt.ajax.reload(null, false);
            } else {
                $.toast({ heading: 'Error', text: res.message, icon: 'error', position: 'top-right' });
            }
        }).fail(function() {
            $.toast({ heading: 'Error', text: 'Request failed', icon: 'error', position: 'top-right' });
        }).always(function() {
            $btn.prop('disabled', false).html(label);
        });
    });

    $(document).on('click', '.fa-del-maint', function() {
        var id = $(this).data('id');
        var $btn = $(this);
        Swal.fire({
            title: 'Delete this record?',
            icon: 'warning',
            showCancelButton: true
        }).then(function(res) {
            if (!res.isConfirmed) return;
            var lab = $btn.html();
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url('fixed-assets/ajax/delete-maintenance'); ?>',
                data: { id: id },
                dataType: 'json'
            }).done(function(r) {
                if (r.success) {
                    $.toast({ heading: 'Deleted', text: r.message, icon: 'success', position: 'top-right' });
                    mt.ajax.reload(null, false);
                } else {
                    $.toast({ heading: 'Error', text: r.message, icon: 'error', position: 'top-right' });
                }
            }).always(function() {
                $btn.prop('disabled', false).html(lab);
            });
        });
    });
})(jQuery);
</script>
