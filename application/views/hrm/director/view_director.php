<?php
$permissions = $this->session->userdata('permissions');
?>
<script>
    $(document).ready(function() {
        $('#category').select2();
    });
    $(document).ready(function() {
        $("#unique_id").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('DirectorController/director_unique_id_load'); ?>",
                    data: {
                        parameter: request.term
                    },
                    dataType: "json",
                    type: "POST",
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                $('#unique_id').val(ui.item.label);
                $('form').submit(); // Automatically submit the form
                return false;
            }
        });
    });

    // Initialize datepickers
    $(document).ready(function() {
        $('#datepicker1').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

        $('#datepicker2').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });

    // Export functions
    function getSearchParams() {
        return {
            unique_id: $('#unique_id').val(),
            from_date: $('#datepicker1').val(),
            to_date: $('#datepicker2').val()
        };
    }

    function exportToExcel() {
        var params = getSearchParams();
        var url = '<?php echo base_url("export-directors-excel"); ?>';
        var queryString = $.param(params);
        window.open(url + (queryString ? '?' + queryString : ''), '_blank');
    }

    function exportToExcelComprehensive() {
        var params = getSearchParams();
        var url = '<?php echo base_url("export-directors-comprehensive"); ?>';
        var queryString = $.param(params);
        window.open(url + (queryString ? '?' + queryString : ''), '_blank');
    }

    function exportToExcelSummary() {
        var params = getSearchParams();
        var url = '<?php echo base_url("export-directors-summary"); ?>';
        var queryString = $.param(params);
        window.open(url + (queryString ? '?' + queryString : ''), '_blank');
    }

    function exportToExcelFinancial() {
        var params = getSearchParams();
        var url = '<?php echo base_url("export-directors-financial"); ?>';
        var queryString = $.param(params);
        window.open(url + (queryString ? '?' + queryString : ''), '_blank');
    }

    function exportToExcelNomineeBank() {
        var params = getSearchParams();
        var url = '<?php echo base_url("export-directors-nominee-bank"); ?>';
        var queryString = $.param(params);
        window.open(url + (queryString ? '?' + queryString : ''), '_blank');
    }

    function exportToPDF() {
        var params = getSearchParams();
        var url = '<?php echo base_url("export-directors-pdf"); ?>';
        var queryString = $.param(params);
        window.open(url + (queryString ? '?' + queryString : ''), '_blank');
    }

    function printReport() {
        var params = getSearchParams();
        var url = '<?php echo base_url("print-directors"); ?>';
        var queryString = $.param(params);
        window.open(url + (queryString ? '?' + queryString : ''), '_blank');
    }
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">View Director</h3>
        </div>
        <div class="panel-body">
            <?php if (in_array('hrm_director_search', $permissions)) { ?>
                <form method="post" action="<?php echo base_url('view-director') ?>" id="searchForm">
                    <table class="table table-bordered table-hover table-condensed table-responsive" style="width: 100%;">
                        <tr>
                            <td>Category</td>
                            <td>Unique ID</td>
                            <td>Name</td>
                            <td>Mobile</td>
                            <td>Action</td>
                            <td style="width: 250px;">Export & Actions</td>
                        </tr>
                        <tr>
                            <td>
                                <select type="text" class="form-control" id="category" name="category">
                                    <option disabled value="">Select Category</option>
                                    <option value="Founder">Founder</option>
                                    <option value="Management">Management</option>
                                    <option value="General">General</option>
                                </select>
                            </td>
                            <td>
                                <input placeholder="Enter any part of Unique ID" type="text" id="unique_id" name="unique_id" class="form-control" />
                            </td>
                            <td>
                                <input id="name" name="name" class="form-control" placeholder="Name">
                            </td>
                            <td>
                                <input id="mobile" name="mobile" class="form-control" placeholder="Mobile">
                            </td>
                            <td><input type="submit" value="Search" class="btn btn-primary"></td>
                            <td>
                                <!-- Excel Export Dropdown -->
                                <div style="display: none;" class="btn-group" role="group">
                                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-file-excel-o"></i> Excel Export <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">

                                        <li><a href="javascript:void(0)" onclick="exportToExcelComprehensive()">
                                                <i class="fa fa-database"></i> Comprehensive Report
                                            </a></li>
                                        <li><a href="javascript:void(0)" onclick="exportToExcelSummary()">
                                                <i class="fa fa-chart-bar"></i> Summary & Analytics
                                            </a></li>
                                        <li><a href="javascript:void(0)" onclick="exportToExcelFinancial()">
                                                <i class="fa fa-money"></i> Financial Analysis
                                            </a></li>
                                        <li><a href="javascript:void(0)" onclick="exportToExcelNomineeBank()">
                                                <i class="fa fa-users"></i> Nominee & Bank Details
                                            </a></li>
                                    </ul>
                                </div>

                                <!-- Other Actions -->
                                <button type="button" class="btn btn-danger btn-sm" onclick="exportToPDF()" title="Export to PDF">
                                    <i class="fa fa-file-pdf-o"></i> PDF
                                </button>
                                <button type="button" class="btn btn-info btn-sm" onclick="printReport()" title="Print Report">
                                    <i class="fa fa-print"></i> Print
                                </button>
                            </td>
                        </tr>

                    </table>
                </form>
            <?php } ?>
            <table class="table table-hover table-bordered table-condensed">
                <tr>
                    <td>#</td>
                    <td>Name</td>
                    <td>ID</td>
                    <td>Category</td>
                    <td>No of Share</td>
                    <td>Share Value</td>
                    <td>Total Share Value</td>
                    <td>Current Share Value</td>
                    <td>Discount Rate</td>
                    <td>Picture</td>
                    <td>Date of Join</td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td>User</td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('hrm_director_print', $permissions)) { ?>
                        <td>Print</td>
                    <?php } ?>
                    <?php if (in_array('hrm_director_edit', $permissions)) { ?>
                        <td>Edit</td>
                    <?php } ?>
                    <?php if (in_array('hrm_director_delete', $permissions)) { ?>
                        <td>Delete</td>
                    <?php } ?>
                </tr>
                <?php

                $sl = 1;
                $grand_total = 0;
                foreach ($director_data as $data) :
                    $director_id  = $data->director_id;
                    $user = getUserById($data->user_id);

                ?>
                    <tr>
                        <td><?php echo $sl++ ?></td>

                        <td><?php echo $data->name ?><br><?php echo $data->mobile ?></td>
                        <td><?php echo $data->unique_id ?></td>
                        <td><?php echo $data->category ?></td>
                        <td><?php echo $data->no_of_share ?></td>
                        <td><?php echo number_format($data->amount_per_share); ?></td>
                        <td><?php echo number_format((float)$data->amount_per_share * (float)$data->no_of_share); ?></td>
                        <td id="current_share_value"><?php
                                                        // Calculate current share value using helper function
                                                        $current_share_value = calculate_current_share_value(
                                                            $data->amount_per_share,
                                                            $data->yearly_share_value_increment_rate,
                                                            $data->date_of_join
                                                        );
                                                        echo number_format((float)$current_share_value * (float)$data->no_of_share, 2);
                                                        ?></td>
                        <td>IPD:<b><?php echo $data->ipd_discount ?>%</b> OPD:<b><?php echo $data->opd_discount ?>%</b> Test:<b><?php echo $data->test_discount ?>%</b><br>
                            Emergency:<b><?php echo $data->emergency_discount ?>%</b> Phygiotherapy:<b><?php echo $data->phygiotherapy_discount ?>%</b><br>
                            Pharmachy:<b><?php echo $data->pharmachy_discount ?>%</b></td>
                        <td>
                            <?php
                            if ($data->picture == '') {
                            ?>
                                <img style="height: 100px;width: 100px;" src="<?php echo base_url() ?>assets/image_icon.jpg">
                        </td>
                    <?php
                            } else {
                    ?>
                        <img style="height: 100px;width: 100px;" src="<?php echo base_url() ?>assets/director/<?php echo $data->picture ?>"></td>
                    <?php
                            }
                    ?>


                    <td><?php echo !empty($data->date_of_join) ? date('d-m-Y', strtotime($data->date_of_join)) : 'N/A' ?></td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td><?php echo $user->user_name ?? "" ?> </td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('hrm_director_print', $permissions)) { ?>
                        <td>
                            <a class="btn btn-primary" href="<?php echo base_url("print-director-again/$director_id") ?>"><i class="glyphicon glyphicon-print"></i></a>
                        </td>
                    <?php } ?>
                    <?php if (in_array('hrm_director_edit', $permissions)) { ?>
                        <td>
                            <a class="btn btn-primary" href="<?php echo base_url("edit-director/$director_id") ?>"><i class="glyphicon glyphicon-edit"></i></a>
                        </td>
                    <?php } ?>
                    <?php if (in_array('hrm_director_delete', $permissions)) { ?>
                        <td><a onclick="return confirm('Do you want to delete?')" href="<?php echo base_url("delete-this-director/$director_id") ?>" class="btn btn-success"><i class="glyphicon glyphicon-trash"></i></a></td>
                    <?php } ?>
                    </tr>
                <?php endforeach; ?>


            </table>


            <div style="width:70%;margin:0 auto;text-align:center">
                <p><?php echo $pagination; ?></p>
            </div>

        </div>
    </div>
</div>
</div>