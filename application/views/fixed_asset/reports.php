<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>

<div class="container-fluid" style="background-color: white; width: 100%;">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="text-center" style="margin:0;">Fixed Assets — Reports</h3>
        </div>
        <div class="panel-body">
            <h4>Overview</h4>
            <div class="row">
                <div class="col-sm-4">
                    <div class="well">
                        <strong>Total asset count</strong>
                        <div style="font-size:24px;"><?php echo (int) $stats['asset_count']; ?></div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="well">
                        <strong>Total asset value (book)</strong>
                        <div style="font-size:24px;"><?php echo number_format($stats['total_book_value'], 2); ?></div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="well">
                        <strong>Total accumulated depreciation</strong>
                        <div style="font-size:24px;"><?php echo number_format($stats['total_accumulated_depreciation'], 2); ?></div>
                    </div>
                </div>
            </div>

            <h4>Depreciation summary</h4>
            <div class="table-responsive">
                <table id="rDep" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Asset count</th>
                            <th>Book value</th>
                            <th>Annual depreciation</th>
                            <th>Accumulated depreciation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dep_summary as $row) { ?>
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

            <h4 style="margin-top:25px;">Category-wise asset report</h4>
            <div class="table-responsive">
                <table id="rCat" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Asset count</th>
                            <th>Total purchase cost</th>
                            <th>Total book value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cat_report as $row) { ?>
                            <tr>
                                <td><?php echo html_escape($row->category_name); ?></td>
                                <td><?php echo (int) $row->asset_count; ?></td>
                                <td><?php echo number_format((float) $row->total_cost, 2); ?></td>
                                <td><?php echo number_format((float) $row->total_book_value, 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function() {
    $('#rDep, #rCat').DataTable({
        pageLength: 25
    });
});
</script>
