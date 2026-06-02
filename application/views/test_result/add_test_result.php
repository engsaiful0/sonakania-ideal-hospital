<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align:center;">Add Test Result</h3>
    </div>
    <div class="panel-body">
        <?php if ($this->session->flashdata('test_result_error')) { ?>
            <div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo html_escape($this->session->flashdata('test_result_error')); ?>
            </div>
        <?php } ?>
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

<script>
    (function($) {
        var searchTimer = null;

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

        function showSwalWarning(message, title) {
            Swal.fire({
                title: title || 'Selection not allowed',
                text: message,
                icon: 'warning',
                confirmButtonColor: '#3085d6'
            });
        }

        function showSwalError(message, title) {
            Swal.fire({
                title: title || 'Error',
                text: message,
                icon: 'error',
                confirmButtonColor: '#3085d6'
            });
        }

        function confirmSwal(options) {
            return Swal.fire({
                title: options.title || 'Are you sure?',
                text: options.text || '',
                icon: options.icon || 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: options.confirmText || 'Yes',
                cancelButtonText: options.cancelText || 'Cancel'
            });
        }

        function updateBulkBar() {
            var $checked = $('#test-list-container .row-select-cb:checked');
            var count = $checked.length;
            if (count > 0) {
                $('#bulk-action-bar').show();
                $('#selected-count').text(count + ' selected');
            } else {
                $('#bulk-action-bar').hide();
            }
        }

        function validateSelection($checkboxes) {
            if (!$checkboxes.length) {
                return { ok: false, message: 'Please select at least one test.' };
            }
            var invoice = null;
            var groupId = null;
            var groupName = '';
            var invalid = false;
            $checkboxes.each(function() {
                var $tr = $(this).closest('tr');
                var inv = String($tr.data('invoice') || '');
                var gid = String($tr.data('group-id') || '');
                groupName = String($tr.data('group-name') || groupName);
                if ($tr.data('is-panel') === 1 || $tr.data('is-panel') === '1') {
                    invalid = true;
                    return false;
                }
                if (invoice === null) {
                    invoice = inv;
                    groupId = gid;
                } else if (invoice !== inv || groupId !== gid) {
                    invalid = true;
                    return false;
                }
            });
            if (invalid) {
                return {
                    ok: false,
                    message: 'Selected tests must belong to the same invoice and the same test group.'
                };
            }
            return { ok: true, message: '', groupName: groupName };
        }

        $(document).on('change', '#test-list-container .row-select-cb', function() {
            var $this = $(this);
            if ($this.is(':checked')) {
                var $others = $('#test-list-container .row-select-cb:checked').not($this);
                var check = validateSelection($others.add($this));
                if (!check.ok) {
                    $this.prop('checked', false);
                    showSwalWarning(check.message);
                }
            }
            var total = $('#test-list-container .row-select-cb').length;
            var checked = $('#test-list-container .row-select-cb:checked').length;
            $('#select-all-rows').prop('checked', total > 0 && total === checked);
            updateBulkBar();
        });

        $(document).on('change', '#select-all-rows', function() {
            var checked = $(this).is(':checked');
            if (!checked) {
                $('#test-list-container .row-select-cb').prop('checked', false);
                updateBulkBar();
                return;
            }
            var $all = $('#test-list-container .row-select-cb');
            if (!$all.length) {
                return;
            }
            var first = $all.first();
            var invoice = String(first.closest('tr').data('invoice') || '');
            var groupId = String(first.closest('tr').data('group-id') || '');
            var mismatch = false;
            $all.each(function() {
                var $tr = $(this).closest('tr');
                if (String($tr.data('invoice') || '') !== invoice || String($tr.data('group-id') || '') !== groupId) {
                    mismatch = true;
                    return false;
                }
            });
            if (mismatch) {
                $(this).prop('checked', false);
                showSwalWarning(
                    'Select all works only when every row on this page shares the same invoice and test group.',
                    'Cannot select all'
                );
                return;
            }
            $all.prop('checked', true);
            updateBulkBar();
        });

        $(document).on('click', '#btn-bulk-result-entry', function() {
            var $checked = $('#test-list-container .row-select-cb:checked');
            var check = validateSelection($checked);
            if (!check.ok) {
                showSwalWarning(check.message);
                return;
            }
            var ids = [];
            $checked.each(function() {
                ids.push($(this).val());
            });
            var groupLabel = check.groupName ? check.groupName : 'selected group';
            confirmSwal({
                title: 'Open result entry?',
                text: 'Enter results for ' + ids.length + ' test(s) from "' + groupLabel + '" on one report.',
                icon: 'question',
                confirmText: 'Yes, continue'
            }).then(function(result) {
                if (!result.isConfirmed) {
                    return;
                }
                $.ajax({
                    url: "<?php echo site_url('TestResultController/validate_group_selection_ajax'); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: { detail_ids: ids },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(res) {
                        if (res.success && res.redirect_url) {
                            window.location.href = res.redirect_url;
                        } else {
                            showSwalError(res.message || 'Could not open result entry.');
                        }
                    },
                    error: function() {
                        showSwalError('Could not validate selection. Please try again.');
                    }
                });
            });
        });

        $(document).on('click', '.btn-delete-result', function() {
            var $btn = $(this);
            confirmSwal({
                title: 'Delete this result?',
                text: 'This test result will be removed from the report.',
                icon: 'warning',
                confirmText: 'Yes, delete it!'
            }).then(function(result) {
                if (!result.isConfirmed) {
                    return;
                }
                $btn.prop('disabled', true);
                $.ajax({
                    url: "<?php echo site_url('TestResultController/test_result_detail_delete_ajax'); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        test_result_id: $btn.data('test-result-id'),
                        test_id: $btn.data('test-id')
                    },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                title: 'Deleted',
                                text: res.message || 'Result deleted successfully.',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            loadList(0);
                        } else {
                            showSwalError(res.message || 'Failed to delete result.');
                            $btn.prop('disabled', false);
                        }
                    },
                    error: function() {
                        showSwalError('Failed to delete result.');
                        $btn.prop('disabled', false);
                    }
                });
            });
        });
    })(jQuery);
</script>
