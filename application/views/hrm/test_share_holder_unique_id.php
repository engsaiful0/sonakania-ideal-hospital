<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Share Holder Unique ID Test</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <h4>Test Share Holder Unique ID Generation</h4>
                <p>This page demonstrates the unique_id generation functionality for share_holder table.</p>
                
                <div class="well">
                    <h5>Available Functions:</h5>
                    <ul>
                        <li><strong>Generate New Unique ID:</strong> Creates a new unique_id in format SH + timestamp + serial</li>
                        <li><strong>Update Existing Records:</strong> Adds unique_id to existing share_holders that don't have one</li>
                        <li><strong>Check Uniqueness:</strong> Verifies that generated IDs are unique</li>
                    </ul>
                </div>
                
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary btn-lg" onclick="generateUniqueId()">
                            <i class="glyphicon glyphicon-plus"></i> Generate New Unique ID
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-warning btn-lg" onclick="updateExistingIds()">
                            <i class="glyphicon glyphicon-refresh"></i> Update Existing Records
                        </button>
                    </div>
                </div>
                
                <div id="result" class="alert" style="margin-top: 20px; display: none;"></div>
                
                <div class="row" style="margin-top: 30px;">
                    <div class="col-md-12">
                        <h5>Generated Unique IDs:</h5>
                        <div id="generated-ids" class="well" style="max-height: 200px; overflow-y: auto;">
                            <p class="text-muted">No IDs generated yet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var generatedIds = [];
    
    function generateUniqueId() {
        $.ajax({
            url: '<?php echo base_url("get-share-holder-unique-id"); ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    generatedIds.push(response.unique_id);
                    displayResult('success', 'Generated Unique ID: ' + response.unique_id);
                    updateGeneratedIdsList();
                } else {
                    displayResult('danger', 'Error: ' + response.message);
                }
            },
            error: function() {
                displayResult('danger', 'Error: Failed to generate unique ID');
            }
        });
    }
    
    function updateExistingIds() {
        $.ajax({
            url: '<?php echo base_url("update-share-holder-unique-ids"); ?>',
            type: 'GET',
            success: function(response) {
                displayResult('info', response);
            },
            error: function() {
                displayResult('danger', 'Error: Failed to update existing records');
            }
        });
    }
    
    function displayResult(type, message) {
        var resultDiv = $('#result');
        resultDiv.removeClass('alert-success alert-danger alert-info alert-warning');
        resultDiv.addClass('alert-' + type);
        resultDiv.html(message);
        resultDiv.show();
        
        // Hide after 5 seconds
        setTimeout(function() {
            resultDiv.fadeOut();
        }, 5000);
    }
    
    function updateGeneratedIdsList() {
        var container = $('#generated-ids');
        if (generatedIds.length === 0) {
            container.html('<p class="text-muted">No IDs generated yet.</p>');
        } else {
            var html = '<ul class="list-unstyled">';
            generatedIds.forEach(function(id, index) {
                html += '<li><span class="badge badge-primary">' + (index + 1) + '</span> ' + id + '</li>';
            });
            html += '</ul>';
            container.html(html);
        }
    }
    
    // Generate a few sample IDs on page load
    $(document).ready(function() {
        // Generate 3 sample IDs
        for (var i = 0; i < 3; i++) {
            setTimeout(function() {
                generateUniqueId();
            }, i * 500);
        }
    });
</script>

<style>
    .well {
        background-color: #f5f5f5;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        padding: 15px;
    }
    
    .badge {
        background-color: #337ab7;
        color: white;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 11px;
    }
    
    .list-unstyled li {
        margin-bottom: 5px;
    }
</style>
