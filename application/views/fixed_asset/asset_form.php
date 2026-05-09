<?php
$is_edit = isset($asset) && $asset;
$purchase_d = $is_edit ? $asset->purchase_date : '';
$war_d = $is_edit && $asset->warranty_expiry ? $asset->warranty_expiry : '';
?>

<div class="container-fluid" style="background-color: white; width: 100%;">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="text-center" style="margin:0;">
                <?php echo $is_edit ? 'Edit fixed asset' : 'Add fixed asset'; ?>
            </h3>
        </div>
        <div class="panel-body">
            <p>
                <a href="<?php echo site_url('fixed-assets/register'); ?>" class="btn btn-default">Back to register</a>
            </p>

            <form id="fa_asset_form" class="form-horizontal" enctype="multipart/form-data">
                <?php if ($is_edit) { ?>
                    <input type="hidden" name="id" value="<?php echo (int) $asset->id; ?>" />
                <?php } ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Asset name *</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="asset_name" required maxlength="200"
                                    value="<?php echo $is_edit ? html_escape($asset->asset_name) : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Asset code *</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="asset_code" required maxlength="50"
                                    value="<?php echo $is_edit ? html_escape($asset->asset_code) : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Category *</label>
                            <div class="col-sm-8">
                                <select name="category_id" class="form-control" id="category_id" required>
                                    <option value="">Select</option>
                                    <?php foreach ($categories as $c) {
                                        if (!$is_edit && !$c->is_active) {
                                            continue;
                                        } ?>
                                        <option value="<?php echo (int) $c->id; ?>"
                                            <?php echo ($is_edit && (int) $asset->category_id === (int) $c->id) ? 'selected' : ''; ?>>
                                            <?php echo html_escape($c->name); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Purchase date *</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" name="purchase_date" id="purchase_date" required
                                    value="<?php echo $purchase_d ? html_escape($purchase_d) : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Purchase cost *</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" min="0" class="form-control" name="purchase_cost" id="purchase_cost" required
                                    value="<?php echo $is_edit ? html_escape($asset->purchase_cost) : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Salvage value</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" min="0" class="form-control" name="salvage_value" id="salvage_value"
                                    value="<?php echo $is_edit ? html_escape($asset->salvage_value) : '0'; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Useful life (years) *</label>
                            <div class="col-sm-8">
                                <input type="number" min="1" class="form-control" name="useful_life_years" id="useful_life_years" required
                                    value="<?php echo $is_edit ? (int) $asset->useful_life_years : '5'; ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="well well-sm">
                            <strong>Depreciation preview</strong> (straight-line, to today)
                            <div>Annual depreciation: <span id="pv_annual">—</span></div>
                            <div>Estimated book value: <span id="pv_book">—</span></div>
                            <?php if ($is_edit) { ?>
                                <div class="text-muted" style="margin-top:8px;">Stored book value: <?php echo number_format((float) $asset->current_book_value, 2); ?></div>
                            <?php } ?>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Department</label>
                            <div class="col-sm-8">
                                <select name="department_id" id="department_id" class="form-control">
                                    <option value="">—</option>
                                    <?php
                                    $department = $this->db->select('*')->order_by('department_name', 'ASC')->get('department')->result();
                                    foreach ($department as $d) {
                                        $sel = ($is_edit && (int) $asset->department_id === (int) $d->department_id) ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo (int) $d->department_id; ?>" <?php echo $sel; ?>>
                                            <?php echo html_escape($d->department_name); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Assigned staff</label>
                            <div class="col-sm-8">
                                <select name="employee_id" id="employee_id" class="form-control">
                                    <option value="">—</option>
                                    <?php
                                    $employees = $this->db->select('*')->order_by('employee_name', 'ASC')->get('employee')->result();
                                    foreach ($employees as $e) {
                                        $sel = ($is_edit && (int) $asset->employee_id === (int) $e->employee_id) ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo (int) $e->employee_id; ?>" <?php echo $sel; ?>>
                                            <?php echo html_escape($e->employee_name . ' — ' . $e->mobile); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Warranty expiry</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" name="warranty_expiry"
                                    value="<?php echo $war_d ? html_escape($war_d) : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Condition *</label>
                            <div class="col-sm-8">
                                <?php
                                $conds = array('Excellent', 'Good', 'Fair', 'Poor', 'Non-functional');
                                $cur = $is_edit ? $asset->condition_status : 'Good';
                                ?>
                                <select name="condition_status" class="form-control" required>
                                    <?php foreach ($conds as $co) { ?>
                                        <option value="<?php echo $co; ?>" <?php echo ($cur === $co) ? 'selected' : ''; ?>><?php echo $co; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Notes</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="notes" rows="2"><?php echo $is_edit ? html_escape($asset->notes) : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Image</label>
                            <div class="col-sm-8">
                                <input type="file" name="asset_image" accept="image/*" />
                                <?php if ($is_edit && !empty($asset->image_path)) { ?>
                                    <div style="margin-top:8px;">
                                        <img src="<?php echo base_url('assets/fixed_assets/' . $asset->image_path); ?>" class="img-thumbnail" style="max-height:80px;" alt="" />
                                        <div class="text-muted small">Upload a new file to replace.</div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="fa_save_btn">
                            <?php echo $is_edit ? 'Update asset' : 'Save asset'; ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
(function($) {
    function num(v) { var n = parseFloat(v); return isNaN(n) ? 0 : n; }
    function previewDepreciation() {
        var cost = num($('#purchase_cost').val());
        var salv = num($('#salvage_value').val());
        var life = parseInt($('#useful_life_years').val(), 10);
        if (!life || life < 1) life = 1;
        var pd = $('#purchase_date').val();
        if (!pd) {
            $('#pv_annual').text('—');
            $('#pv_book').text('—');
            return;
        }
        var depreciable = Math.max(0, cost - salv);
        var annual = depreciable / life;
        var purchase = new Date(pd + 'T00:00:00');
        var today = new Date();
        today.setHours(0,0,0,0);
        var days = (today - purchase) / 86400000;
        if (days < 0) {
            $('#pv_annual').text(annual.toFixed(2));
            $('#pv_book').text(cost.toFixed(2));
            return;
        }
        var years = days / 365.25;
        var accum = Math.min(depreciable, annual * years);
        var book = Math.max(salv, cost - accum);
        $('#pv_annual').text(annual.toFixed(2));
        $('#pv_book').text(book.toFixed(2));
    }

    $('#purchase_cost, #salvage_value, #useful_life_years, #purchase_date').on('change keyup', previewDepreciation);
    previewDepreciation();

    $(document).ready(function() {
        $('#department_id, #employee_id').select2({ width: '100%' });
        $('#category_id').select2({ width: '100%' });
    });

    $('#fa_asset_form').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#fa_save_btn');
        var isEdit = $('input[name="id"]').length > 0;
        var url = isEdit
            ? '<?php echo site_url('fixed-assets/ajax/update-asset'); ?>'
            : '<?php echo site_url('fixed-assets/ajax/save-asset'); ?>';

        var label = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?php echo $is_edit ? 'Updating...' : 'Saving...'; ?>');

        var fd = new FormData(this);
        $.ajax({
            url: url,
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json'
        }).done(function(res) {
            if (res.success) {
                $.toast({
                    heading: 'Success',
                    text: res.message || 'Saved',
                    icon: 'success',
                    position: 'top-right',
                    hideAfter: 1500
                });
                if (res.redirect) {
                    window.setTimeout(function() {
                        window.location.href = res.redirect;
                    }, 600);
                }
            } else {
                $.toast({ heading: 'Error', text: res.message || 'Failed', icon: 'error', position: 'top-right' });
            }
        }).fail(function(xhr) {
            var msg = 'Request failed';
            if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
            $.toast({ heading: 'Error', text: msg, icon: 'error', position: 'top-right' });
        }).always(function() {
            $btn.prop('disabled', false).html(label);
        });
    });
})(jQuery);
</script>
