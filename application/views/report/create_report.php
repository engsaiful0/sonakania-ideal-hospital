<div class="panel panel-primary" id="lab-report-create">
    <div class="panel-heading">
        <h3 class="text-center" style="margin:0;">Create lab report</h3>
    </div>
    <div class="panel-body">
        <form method="post" action="<?php echo site_url('report/store_report'); ?>" id="lab-report-form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Select panel *</label>
                        <select id="panel_select" class="form-control">
                            <option value="">— Choose panel —</option>
                            <?php if (!empty($panels)) {
                                foreach ($panels as $pn) { ?>
                                    <option value="<?php echo (int) $pn->id; ?>"><?php echo html_escape($pn->panel_name); ?></option>
                            <?php }
                            } ?>
                        </select>
                        <input type="hidden" name="panel_id" id="panel_id" value="">
                    </div>
                </div>
            </div>

            <hr>
            <h4>Patient info</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Patient name *</label>
                        <input type="text" name="patient_name" class="form-control" required maxlength="255">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Age</label>
                        <input type="text" name="age" class="form-control" maxlength="50">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Sex</label>
                        <select name="sex" class="form-control">
                            <option value="">—</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Patient ID / MRN</label>
                        <input type="text" name="patient_id" class="form-control" maxlength="100">
                    </div>
                </div>
            </div>

            <hr>
            <h4>Test entry</h4>
            <p class="text-muted" id="lab-panel-hint">Select a panel to load sections and parameters.</p>
            <div id="lab-dynamic-fields"></div>

            <div class="form-group" style="margin-top:20px;">
                <button type="submit" class="btn btn-primary btn-lg" id="lab-submit-btn" disabled>Save report</button>
            </div>
        </form>
    </div>
</div>

<script>
(function($) {
    var ajaxBase = '<?php echo site_url('report/ajax_panel_structure'); ?>';

    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function buildInput(p) {
        var name = 'parameters[' + p.id + ']';
        var unit = p.unit ? ' <span class="text-muted">(' + escapeHtml(p.unit) + ')</span>' : '';
        var label = '<label>' + escapeHtml(p.parameter_name) + unit + '</label>';

        if (p.input_type === 'numeric') {
            var min = p.min_value !== null && p.min_value !== '' ? ' min="' + escapeHtml(p.min_value) + '"' : '';
            var max = p.max_value !== null && p.max_value !== '' ? ' max="' + escapeHtml(p.max_value) + '"' : '';
            return '<div class="form-group">' + label +
                '<input type="number" step="any" class="form-control" name="' + name + '" ' + min + max + '></div>';
        }
        if (p.input_type === 'boolean') {
            return '<div class="form-group">' + label +
                '<select class="form-control" name="' + name + '">' +
                '<option value="">—</option>' +
                '<option value="Negative">Negative</option>' +
                '<option value="Positive">Positive</option>' +
                '</select></div>';
        }
        return '<div class="form-group">' + label +
            '<input type="text" class="form-control" name="' + name + '" maxlength="500"></div>';
    }

    function renderPanel(data) {
        var $wrap = $('#lab-dynamic-fields').empty();
        if (!data.ok || !data.sections || !data.sections.length) {
            $wrap.append('<p class="text-warning">No sections/parameters defined for this panel yet.</p>');
            return;
        }
        data.sections.forEach(function(sec) {
            var $block = $('<div class="well well-sm" style="margin-bottom:18px;"></div>');
            $block.append('<h4 style="margin-top:0;border-bottom:1px solid #ddd;padding-bottom:6px;">' +
                escapeHtml(sec.section_name) + '</h4>');
            if (!sec.parameters || !sec.parameters.length) {
                $block.append('<p class="text-muted">No parameters in this section.</p>');
            } else {
                sec.parameters.forEach(function(p) {
                    $block.append(buildInput(p));
                });
            }
            $wrap.append($block);
        });
    }

    $('#panel_select').on('change', function() {
        var pid = parseInt($(this).val(), 10);
        $('#panel_id').val(pid || '');
        $('#lab-dynamic-fields').empty();
        $('#lab-panel-hint').text(pid ? 'Loading…' : 'Select a panel to load sections and parameters.');
        $('#lab-submit-btn').prop('disabled', true);

        if (!pid) {
            return;
        }

        $.ajax({
            url: ajaxBase + '/' + pid,
            dataType: 'json'
        }).done(function(data) {
            if (data && data.ok) {
                $('#lab-panel-hint').text('Panel: ' + (data.panel && data.panel.panel_name ? data.panel.panel_name : ''));
                renderPanel(data);
                $('#lab-submit-btn').prop('disabled', false);
            } else {
                $('#lab-panel-hint').text(data.message || 'Could not load panel.');
            }
        }).fail(function() {
            $('#lab-panel-hint').text('Request failed.');
        });
    });
})(jQuery);
</script>
