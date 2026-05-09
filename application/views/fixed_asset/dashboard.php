<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>

<div class="container-fluid" style="background-color: white; width: 100%;">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="text-center" style="margin:0;">Fixed Assets — Dashboard</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="well text-center">
                        <h4 style="margin-top:0;">Total Assets</h4>
                        <p style="font-size:28px;font-weight:bold;"><?php echo (int) $stats['asset_count']; ?></p>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="well text-center">
                        <h4 style="margin-top:0;">Total Book Value</h4>
                        <p style="font-size:28px;font-weight:bold;"><?php echo number_format($stats['total_book_value'], 2); ?></p>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="well text-center">
                        <h4 style="margin-top:0;">Annual Depreciation (sum)</h4>
                        <p style="font-size:28px;font-weight:bold;"><?php echo number_format($stats['total_annual_depreciation'], 2); ?></p>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="well text-center">
                        <h4 style="margin-top:0;">Accumulated Depreciation (sum)</h4>
                        <p style="font-size:28px;font-weight:bold;"><?php echo number_format($stats['total_accumulated_depreciation'], 2); ?></p>
                    </div>
                </div>
            </div>

            <h4>Depreciation summary by category</h4>
            <div class="table-responsive">
                <table id="depTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Assets</th>
                            <th>Book value</th>
                            <th>Annual depreciation</th>
                            <th>Accumulated depreciation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dep_by_cat as $row) { ?>
                            <tr>
                                <td><?php echo html_escape($row->category_name); ?></td>
                                <td><?php echo (int) $row->asset_count; ?></td>
                                <td><?php echo number_format((float) $row->total_book_value, 2); ?></td>
                                <td><?php echo number_format((float) $row->total_annual, 2); ?></td>
                                <td><?php echo number_format((float) $row->total_accum, 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <p class="text-muted" style="margin-top:15px;">
                Straight-line depreciation: (<em>cost − salvage</em>) ÷ useful life; book value is calculated to the current date and stored on each save.
            </p>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function() {
    $('#depTable').DataTable({
        pageLength: 25,
        ordering: true,
        order: [[0, 'asc']]
    });
});
</script>
