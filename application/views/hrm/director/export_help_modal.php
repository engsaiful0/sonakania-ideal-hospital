<!-- Export Help Modal -->
<div class="modal fade" id="exportHelpModal" tabindex="-1" role="dialog" aria-labelledby="exportHelpModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exportHelpModalLabel">
                    <i class="fa fa-info-circle"></i> Director Export Guide
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-info"><i class="fa fa-lightbulb-o"></i> <strong>Choose the right export format for your needs:</strong></p>
                        
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            
                            <!-- Basic List -->
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingBasic">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseBasic" aria-expanded="true" aria-controls="collapseBasic">
                                            <i class="fa fa-table text-success"></i> <strong>Basic List Export</strong>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseBasic" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingBasic">
                                    <div class="panel-body">
                                        <p><strong>Best for:</strong> Quick overview, simple reports</p>
                                        <p><strong>Contains:</strong> Name, Unique ID, Mobile, Email, Shares, Share Value, Total Amount, Current Value, Join Date, Category</p>
                                        <p><strong>File size:</strong> Small</p>
                                        <p class="text-muted">Perfect for basic shareholder listings and general reference.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Comprehensive -->
                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingComp">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseComp" aria-expanded="false" aria-controls="collapseComp">
                                            <i class="fa fa-database text-primary"></i> <strong>Comprehensive Report</strong> <span class="label label-primary">RECOMMENDED</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseComp" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingComp">
                                    <div class="panel-body">
                                        <p><strong>Best for:</strong> Complete analysis, legal documentation, audit reports</p>
                                        <p><strong>Contains:</strong> All basic info + Nominee details, Bank information, Personal details (Father/Mother name, Address, Gender, NID), Discounts, Multiple data sheets</p>
                                        <p><strong>File size:</strong> Large</p>
                                        <p class="text-muted">Most detailed export with multiple worksheets for different data categories.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Summary & Analytics -->
                            <div class="panel panel-info">
                                <div class="panel-heading" role="tab" id="headingSummary">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSummary" aria-expanded="false" aria-controls="collapseSummary">
                                            <i class="fa fa-chart-bar text-info"></i> <strong>Summary & Analytics</strong>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseSummary" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSummary">
                                    <div class="panel-body">
                                        <p><strong>Best for:</strong> Management reports, board presentations, quick insights</p>
                                        <p><strong>Contains:</strong> Key metrics, Growth percentages, Category breakdowns, Investment status classifications</p>
                                        <p><strong>File size:</strong> Medium</p>
                                        <p class="text-muted">Includes category-wise analysis and investment tier classifications.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Financial Analysis -->
                            <div class="panel panel-warning">
                                <div class="panel-heading" role="tab" id="headingFinancial">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFinancial" aria-expanded="false" aria-controls="collapseFinancial">
                                            <i class="fa fa-money text-warning"></i> <strong>Financial Analysis</strong>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFinancial" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFinancial">
                                    <div class="panel-body">
                                        <p><strong>Best for:</strong> Financial planning, ROI analysis, investment performance tracking</p>
                                        <p><strong>Contains:</strong> Investment amounts, Current valuations, Gain/Loss calculations, ROI percentages, Portfolio performance summary</p>
                                        <p><strong>File size:</strong> Medium</p>
                                        <p class="text-muted">Focus on financial metrics and investment performance analysis.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Nominee & Bank Details -->
                            <div class="panel panel-success">
                                <div class="panel-heading" role="tab" id="headingNominee">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseNominee" aria-expanded="false" aria-controls="collapseNominee">
                                            <i class="fa fa-users text-success"></i> <strong>Nominee & Bank Details</strong>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseNominee" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingNominee">
                                    <div class="panel-body">
                                        <p><strong>Best for:</strong> Legal compliance, succession planning, payment processing</p>
                                        <p><strong>Contains:</strong> Nominee information, Banking details, Data completeness status, Missing information alerts</p>
                                        <p><strong>File size:</strong> Medium</p>
                                        <p class="text-muted">Specialized for managing nominee and banking information with completeness tracking.</p>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <!-- Tips Section -->
                        <div class="alert alert-info" role="alert">
                            <h4><i class="fa fa-lightbulb-o"></i> Pro Tips:</h4>
                            <ul>
                                <li><strong>Filter before exporting:</strong> Use the search filters above to export only the data you need</li>
                                <li><strong>Date ranges:</strong> Set specific date ranges to export shareholders who joined within certain periods</li>
                                <li><strong>Regular backups:</strong> Use Comprehensive Report for complete data backups</li>
                                <li><strong>Board meetings:</strong> Use Summary & Analytics for executive presentations</li>
                            </ul>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="exportToExcelComprehensive()">
                    <i class="fa fa-download"></i> Export Comprehensive Report
                </button>
            </div>
        </div>
    </div>
</div>

