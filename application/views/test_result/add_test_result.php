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
    })(jQuery);
</script>
