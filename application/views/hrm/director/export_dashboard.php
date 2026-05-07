<?php
$permissions = $this->session->userdata('permissions');
?>
<div class="container-fluid" style="background-color: white; width: 100%;">
    <div class="panel panel-primary" style="width: 100%; margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">
                <i class="fa fa-download"></i> ShareHolder Export Dashboard
            </h3>
        </div>
        <div class="panel-body">
            
            <!-- Quick Stats -->
            <?php
            $total_shareholders = $this->db->count_all('share_holder');
            $total_investment = $this->db->select_sum('total_amount')->get('share_holder')->row()->total_amount ?: 0;
            ?>
            
            <div class="row">
                <div class="col-md-3">
                    <div class="alert alert-info text-center">
                        <h4><?php echo number_format($total_shareholders); ?></h4>
                        <p>Total Shareholders</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-success text-center">
                        <h4>৳<?php echo number_format($total_investment, 2); ?></h4>
                        <p>Total Investment</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-warning text-center">
                        <h4><?php echo date('Y'); ?></h4>
                        <p>Current Year</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-danger text-center">
                        <h4><?php echo date('d M'); ?></h4>
                        <p>Today's Date</p>
                    </div>
                </div>
            </div>
            
            <!-- Export Options Grid -->
            <div class="row">
                
                <!-- Basic Export -->
                <div class="col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4><i class="fa fa-table"></i> Basic Export</h4>
                        </div>
                        <div class="panel-body">
                            <p>Simple shareholder listing with essential information.</p>
                            <p><strong>Contains:</strong> Name, ID, Contact, Shares, Investment</p>
                            <button class="btn btn-success btn-block" onclick="exportToExcel()">
                                <i class="fa fa-download"></i> Download Basic List
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Comprehensive Export -->
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4><i class="fa fa-database"></i> Comprehensive Report <span class="label label-warning">RECOMMENDED</span></h4>
                        </div>
                        <div class="panel-body">
                            <p>Complete shareholder data with all available information.</p>
                            <p><strong>Contains:</strong> All data fields, Personal info, Nominees, Banking</p>
                            <button class="btn btn-primary btn-block" onclick="exportToExcelComprehensive()">
                                <i class="fa fa-download"></i> Download Complete Report
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Summary Export -->
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4><i class="fa fa-chart-bar"></i> Summary & Analytics</h4>
                        </div>
                        <div class="panel-body">
                            <p>Summary report with analytics and category breakdowns.</p>
                            <p><strong>Contains:</strong> Statistics, Growth analysis, Category summaries</p>
                            <button class="btn btn-info btn-block" onclick="exportToExcelSummary()">
                                <i class="fa fa-download"></i> Download Summary Report
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Financial Export -->
                <div class="col-md-6">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h4><i class="fa fa-money"></i> Financial Analysis</h4>
                        </div>
                        <div class="panel-body">
                            <p>Detailed financial performance and investment analysis.</p>
                            <p><strong>Contains:</strong> ROI calculations, Portfolio performance, Valuations</p>
                            <button class="btn btn-warning btn-block" onclick="exportToExcelFinancial()">
                                <i class="fa fa-download"></i> Download Financial Report
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Nominee & Bank Export -->
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4><i class="fa fa-users"></i> Nominee & Banking</h4>
                        </div>
                        <div class="panel-body">
                            <p>Specialized report for nominee and banking information.</p>
                            <p><strong>Contains:</strong> Nominee details, Bank accounts, Completeness status</p>
                            <button class="btn btn-default btn-block" onclick="exportToExcelNomineeBank()">
                                <i class="fa fa-download"></i> Download Nominee & Bank Report
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Other Formats -->
                <div class="col-md-6">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h4><i class="fa fa-file-pdf-o"></i> Other Formats</h4>
                        </div>
                        <div class="panel-body">
                            <p>Export in different formats for various purposes.</p>
                            <div class="btn-group btn-group-justified" role="group">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-danger" onclick="exportToPDF()">
                                        <i class="fa fa-file-pdf-o"></i> PDF
                                    </button>
                                </div>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-info" onclick="printReport()">
                                        <i class="fa fa-print"></i> Print
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <!-- Export Tips -->
            <div class="alert alert-info">
                <h4><i class="fa fa-lightbulb-o"></i> Export Tips:</h4>
                <ul>
                    <li><strong>For management reports:</strong> Use Summary & Analytics or Financial Analysis</li>
                    <li><strong>For data backup:</strong> Use Comprehensive Report</li>
                    <li><strong>For compliance:</strong> Use Nominee & Banking report</li>
                    <li><strong>For quick reference:</strong> Use Basic Export</li>
                </ul>
            </div>
            
        </div>
    </div>
</div>

<script>
    // Export functions
    function exportToExcel() {
        var url = '<?php echo base_url("export-shareholders-excel"); ?>';
        window.open(url, '_blank');
    }

    function exportToExcelComprehensive() {
        var url = '<?php echo base_url("export-shareholders-comprehensive"); ?>';
        window.open(url, '_blank');
    }

    function exportToExcelSummary() {
        var url = '<?php echo base_url("export-shareholders-summary"); ?>';
        window.open(url, '_blank');
    }

    function exportToExcelFinancial() {
        var url = '<?php echo base_url("export-shareholders-financial"); ?>';
        window.open(url, '_blank');
    }

    function exportToExcelNomineeBank() {
        var url = '<?php echo base_url("export-shareholders-nominee-bank"); ?>';
        window.open(url, '_blank');
    }

    function exportToPDF() {
        var url = '<?php echo base_url("export-shareholders-pdf"); ?>';
        window.open(url, '_blank');
    }

    function printReport() {
        var url = '<?php echo base_url("print-shareholders"); ?>';
        window.open(url, '_blank');
    }
</script>

