<?php
$sl_start = isset($sl_start) ? (int) $sl_start : 1;
$rows = isset($rows) ? $rows : array();
$panels = isset($panels) ? $panels : array();
$pagination = isset($pagination) ? $pagination : '';
$total_rows = isset($total_rows) ? (int) $total_rows : 0;
$per_page = isset($per_page) ? (int) $per_page : 25;
$filter_patient = isset($filter_patient) ? (string) $filter_patient : '';
$filter_panel_id = isset($filter_panel_id) ? (int) $filter_panel_id : 0;
$filter_date_from = isset($filter_date_from) ? (string) $filter_date_from : '';
$filter_date_to = isset($filter_date_to) ? (string) $filter_date_to : '';
$back_url = uri_string() . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '');
?>

<div class="container-fluid" style="background-color:white;width:100%;" id="pt-list-root">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="text-center" style="margin:0;">View Panel Test</h3>
        </div>
        <div class="panel-body">
            <form method="get" action="<?php echo site_url('TestPanelResultController/view_panel_test'); ?>">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Patient</label>
                            <input type="text" name="patient" class="form-control" placeholder="Name or patient/invoice id"
                                value="<?php echo html_escape($filter_patient); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Panel</label>
                            <select name="panel_id" id="filter_panel_id" class="form-control" style="width:100%;">
                                <option value="">All panels</option>
                                <?php foreach ($panels as $pn) {
                                    $sel = ((int) $pn->id === (int) $filter_panel_id) ? ' selected' : '';
                                ?>
                                    <option value="<?php echo (int) $pn->id; ?>"<?php echo $sel; ?>>
                                        <?php echo html_escape($pn->panel_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>From date</label>
                            <input type="date" name="date_from" class="form-control"
                                value="<?php echo html_escape($filter_date_from); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>To date</label>
                            <input type="date" name="date_to" class="form-control"
                                value="<?php echo html_escape($filter_date_to); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="visibility:hidden;">.</label>
                            <div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                                <a href="<?php echo site_url('TestPanelResultController/view_panel_test'); ?>" class="btn btn-default">Reset</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <hr>

            <p class="text-muted">
                <?php
                if ($total_rows > 0) {
                    $sl_end = min($sl_start + count($rows) - 1, $total_rows);
                    echo 'Showing ' . $sl_start . '–' . $sl_end . ' of ' . $total_rows;
                } else {
                    echo 'No panel test reports found.';
                }
                ?>
            </p>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th style="width:50px;">SL</th>
                            <th style="width:60px;">ID</th>
                            <th style="width:110px;">Date</th>
                            <th>Patient</th>
                            <th>Panel</th>
                            <th style="width:80px;">#</th>
                            <th style="width:170px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rows)) {
                            $sl = $sl_start;
                            foreach ($rows as $r) {
                                $view_url = site_url('report/view_report/' . (int) $r->id);
                                $print_url = $view_url . '?print=1';
                                $delete_url = site_url('TestPanelResultController/delete_panel_test/' . (int) $r->id)
                                    . '?back=' . urlencode($back_url);
                        ?>
                                <tr>
                                    <td><?php echo $sl++; ?></td>
                                    <td><?php echo (int) $r->id; ?></td>
                                    <td><?php echo html_escape($r->report_date); ?></td>
                                    <td>
                                        <strong><?php echo html_escape($r->patient_name); ?></strong>
                                        <?php
                                        $sub = array();
                                        if (isset($r->age) && $r->age !== '') {
                                            $sub[] = 'Age ' . html_escape((string) $r->age);
                                        }
                                        if (isset($r->sex) && $r->sex !== '') {
                                            $sub[] = html_escape((string) $r->sex);
                                        }
                                        if (isset($r->patient_id) && $r->patient_id !== '') {
                                            $sub[] = '#' . html_escape((string) $r->patient_id);
                                        }
                                        if (!empty($sub)) {
                                            echo '<br><small class="text-muted">' . implode(' · ', $sub) . '</small>';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo isset($r->panel_name) ? html_escape($r->panel_name) : '—'; ?></td>
                                    <td class="text-right"><?php echo (int) $r->result_count; ?></td>
                                    <td class="text-nowrap">
                                        <a class="btn btn-xs btn-primary" href="<?php echo base_url("print-panel-test-with-id/$r->id") ?>" title="View">
                                            <i class="glyphicon glyphicon-print"></i>
                                        </a>
                                        <a class="btn btn-xs btn-info"  href="<?php echo base_url("test-result-edit/$r->id") ?>" title="Edit">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                        <a class="btn btn-xs btn-danger" href="<?php echo $delete_url; ?>"
                                            onclick="return confirm('Delete this report and its results?');" title="Delete">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No matching records.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($pagination)) { ?>
                <div style="text-align:center;">
                    <?php echo $pagination; ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<script>
    (function($) {
        $(function() {
            if ($.fn.select2) {
                $('#filter_panel_id').select2({ width: '100%', dropdownParent: $('#pt-list-root') });
            }
        });
    })(jQuery);
</script>
