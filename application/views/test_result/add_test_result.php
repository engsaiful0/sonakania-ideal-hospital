<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align:center;">Add Test Result</h3>
    </div>
    <div class="panel-body">
        <div id="result-message"></div>
        <form id="search-form" class="form-horizontal">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <label>Invoice ID</label>
                    <input type="text" class="form-control" id="filter_invoice_id" name="invoice_id" value="<?php echo isset($filter_invoice_id) ? html_escape($filter_invoice_id) : ''; ?>" placeholder="Invoice ID">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label>Mobile Number</label>
                    <input type="text" class="form-control" id="filter_mobile_number" name="mobile_number" value="<?php echo isset($filter_mobile_number) ? html_escape($filter_mobile_number) : ''; ?>" placeholder="Mobile Number">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label>Test Name</label>
                    <input type="text" class="form-control" id="filter_test_name" name="test_name" value="<?php echo isset($filter_test_name) ? html_escape($filter_test_name) : ''; ?>" placeholder="Test Name">
                </div>
                <div class="col-md-3 col-sm-6" style="padding-top:25px;">
                    <button type="button" class="btn btn-default" id="btn-clear-filters">Clear</button>
                </div>
            </div>
        </form>
        <hr>
        <div id="test-list-container">
            <?php $this->load->view('test_result/partials/add_test_result_list', array('detailsList' => isset($detailsList) ? $detailsList : array(), 'pagination' => isset($pagination) ? $pagination : '', 'sl_start' => isset($sl_start) ? $sl_start : 1)); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="resultEntryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title">Result Entry</h4>
            </div>
            <div class="modal-body" id="result-entry-form-wrapper">
                <p class="text-muted">Loading...</p>
            </div>
        </div>
    </div>
</div>

<script>
    (function($) {
        var searchTimer = null;

        function showMessage(type, text, extraHtml) {
            var cls = type === 'success' ? 'alert-success' : 'alert-danger';
            var html = '<div class="alert ' + cls + ' alert-dismissible">' +
                '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                text + (extraHtml ? '<br>' + extraHtml : '') +
                '</div>';
            $('#result-message').html(html);
        }

        function readOffsetFromUrl(url) {
            var match = url.match(/\/add_test_result\/(\d+)/);
            return match ? match[1] : '0';
        }

        function loadList(offset) {
            $.ajax({
                url: "<?php echo site_url('TestResultController/add_test_result_list_ajax'); ?>",
                type: 'GET',
                dataType: 'json',
                data: {
                    invoice_id: $('#filter_invoice_id').val(),
                    mobile_number: $('#filter_mobile_number').val(),
                    test_name: $('#filter_test_name').val(),
                    offset: offset || 0
                },
                success: function(res) {
                    if (res && res.success) {
                        $('#test-list-container').html(res.html);
                    }
                }
            });
        }

        $('#filter_invoice_id, #filter_mobile_number, #filter_test_name').on('keyup', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function() {
                loadList(0);
            }, 300);
        });

        $('#btn-clear-filters').on('click', function() {
            $('#filter_invoice_id, #filter_mobile_number, #filter_test_name').val('');
            loadList(0);
        });

        $(document).on('click', '#test-list-container .pagination a', function(e) {
            e.preventDefault();
            var href = $(this).attr('href') || '';
            loadList(readOffsetFromUrl(href));
        });

        $(document).on('click', '.btn-result-entry', function() {
            var entryId = $(this).data('entry-id');
            $('#result-entry-form-wrapper').html('<p class="text-muted">Loading...</p>');
            $('#resultEntryModal').modal('show');

            $.ajax({
                url: "<?php echo site_url('TestResultController/add_test_result_entry_form_ajax'); ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    patient_test_entry_details_id: entryId
                },
                success: function(res) {
                    if (res.success) {
                        $('#result-entry-form-wrapper').html(res.html);
                    } else {
                        var link = res.print_url ? '<a target="_blank" href="' + res.print_url + '" class="btn btn-xs btn-info">Print Existing</a>' : '';
                        $('#result-entry-form-wrapper').html('<div class="alert alert-warning">' + (res.message || 'Cannot load entry form.') + (link ? '<br>' + link : '') + '</div>');
                    }
                },
                error: function() {
                    $('#result-entry-form-wrapper').html('<div class="alert alert-danger">Failed to load result entry form.</div>');
                }
            });
        });

        $(document).on('submit', '#result-entry-form', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo site_url('TestResultController/add_test_result_data_save'); ?>",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        $('#resultEntryModal').modal('hide');
                        var printBtn = res.print_url ? '<a target="_blank" href="' + res.print_url + '" class="btn btn-xs btn-primary">Print Report</a>' : '';
                        showMessage('success', res.message || 'Result saved successfully.', printBtn);
                        if (res.print_url) {
                            window.open(res.print_url, '_blank');
                        }
                        loadList(0);
                    } else {
                        var extra = res.print_url ? '<a target="_blank" href="' + res.print_url + '" class="btn btn-xs btn-info">Print Existing</a>' : '';
                        showMessage('error', res.message || 'Failed to save result.', extra);
                    }
                },
                error: function() {
                    showMessage('error', 'Failed to save result. Please try again.');
                }
            });
        });
    })(jQuery);
</script>