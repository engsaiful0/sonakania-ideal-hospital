<div class="container-fluid" style="background-color: white; width: 98%;">
    <div class="panel panel-primary" style="width: 100%; margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Share Holders List</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="shareHolderTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Unique ID</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Number of Shares</th>
                                    <th>Original Amount</th>
                                    <th>Current Value</th>
                                    <th>Total Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewShareHolderModal" tabindex="-1" role="dialog" aria-labelledby="viewShareHolderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewShareHolderModalLabel">
                    <i class="fas fa-user"></i> Share Holder Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="shareHolderDetails">
                    <!-- Share holder details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteShareHolderModal" tabindex="-1" role="dialog" aria-labelledby="deleteShareHolderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteShareHolderModalLabel">
                    <i class="fas fa-trash"></i> Confirm Delete
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this share holder? This action cannot be undone.</p>
                <p><strong>Share Holder:</strong> <span id="deleteShareHolderName"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
let shareHolderTable;
let deleteShareHolderId = null;

$(document).ready(function() {
    // Initialize DataTable
    shareHolderTable = $('#shareHolderTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo base_url("ShareHolderController/get_share_holders"); ?>',
            type: 'GET'
        },
        columns: [
            { data: 0 },
            { data: 1 },
            { data: 2 },
            { data: 3 },
            { data: 4 },
            { data: 5 },
            { data: 6 },
            { data: 7 },
            { data: 8 },
            { data: 9, orderable: false }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
        }
    });

    // Confirm delete button click
    $('#confirmDeleteBtn').click(function() {
        if (deleteShareHolderId) {
            $.ajax({
                url: '<?php echo base_url("ShareHolderController/delete_share_holder/"); ?>' + deleteShareHolderId,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showToast('Success', response.message, 'success');
                        shareHolderTable.ajax.reload();
                    } else {
                        showToast('Error', response.message, 'error');
                    }
                },
                error: function() {
                    showToast('Error', 'An error occurred while deleting the share holder.', 'error');
                },
                complete: function() {
                    $('#deleteShareHolderModal').modal('hide');
                    deleteShareHolderId = null;
                }
            });
        }
    });
});

function viewShareHolder(id) {
    $.ajax({
        url: '<?php echo base_url("ShareHolderController/get_share_holder/"); ?>' + id,
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
            $('#shareHolderDetails').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');
            $('#viewShareHolderModal').modal('show');
        },
        success: function(response) {
            if (response.success) {
                const data = response.data;
                const detailsHtml = `
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Basic Information</h5>
                            <table class="table table-borderless">
                                <tr><td><strong>Name:</strong></td><td>${data.name}</td></tr>
                                <tr><td><strong>Unique ID:</strong></td><td>${data.unique_id}</td></tr>
                                <tr><td><strong>Father's Name:</strong></td><td>${data.father_name}</td></tr>
                                <tr><td><strong>Mother's Name:</strong></td><td>${data.mother_name || 'N/A'}</td></tr>
                                <tr><td><strong>Gender:</strong></td><td>${data.gender || 'N/A'}</td></tr>
                                <tr><td><strong>NID Number:</strong></td><td>${data.nid_number || 'N/A'}</td></tr>
                                <tr><td><strong>Mobile:</strong></td><td>${data.mobile}</td></tr>
                                <tr><td><strong>Email:</strong></td><td>${data.email || 'N/A'}</td></tr>
                                <tr><td><strong>Present Address:</strong></td><td>${data.present_address || 'N/A'}</td></tr>
                                <tr><td><strong>Permanent Address:</strong></td><td>${data.permanent_address || 'N/A'}</td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Share Information</h5>
                            <table class="table table-borderless">
                                <tr><td><strong>Number of Shares:</strong></td><td>${parseInt(data.no_of_share).toLocaleString()}</td></tr>
                                <tr><td><strong>Original Amount per Share:</strong></td><td>৳${parseFloat(data.amount_per_share).toLocaleString()}</td></tr>
                                <tr><td><strong>Current Share Value:</strong></td><td>৳${parseFloat(data.current_share_value || data.amount_per_share).toLocaleString()}</td></tr>
                                <tr><td><strong>Yearly Increment Rate:</strong></td><td>${data.yearly_share_value_increment_rate || 0}%</td></tr>
                                <tr><td><strong>Total Amount:</strong></td><td>৳${parseFloat(data.total_amount).toLocaleString()}</td></tr>
                                <tr><td><strong>Discount Rate:</strong></td><td>${data.discount_rate || 0}%</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Nominee Information</h5>
                            <table class="table table-borderless">
                                <tr><td><strong>Name:</strong></td><td>${data.name_of_nominee}</td></tr>
                                <tr><td><strong>Mobile:</strong></td><td>${data.nominee_mobile || 'N/A'}</td></tr>
                                <tr><td><strong>Email:</strong></td><td>${data.nominee_email || 'N/A'}</td></tr>
                                <tr><td><strong>Present Address:</strong></td><td>${data.nominee_present_address || 'N/A'}</td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Discount Facilities</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>IPD Discount:</strong></td><td>${data.ipd_discount}%</td>
                                    <td><strong>OPD Discount:</strong></td><td>${data.opd_discount}%</td>
                                </tr>
                                <tr>
                                    <td><strong>Test Discount:</strong></td><td>${data.test_discount}%</td>
                                    <td><strong>Emergency Discount:</strong></td><td>${data.emergency_discount}%</td>
                                </tr>
                                <tr>
                                    <td><strong>Physiotherapy Discount:</strong></td><td>${data.phygiotherapy_discount}%</td>
                                    <td><strong>Pharmacy Discount:</strong></td><td>${data.pharmachy_discount}%</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                `;
                $('#shareHolderDetails').html(detailsHtml);
            } else {
                $('#shareHolderDetails').html('<div class="alert alert-danger">' + response.message + '</div>');
            }
        },
        error: function() {
            $('#shareHolderDetails').html('<div class="alert alert-danger">An error occurred while loading share holder details.</div>');
        }
    });
}

function editShareHolder(id) {
    window.location.href = '<?php echo base_url("edit-share-holder/"); ?>' + id;
}

function deleteShareHolder(id) {
    // Get share holder details first
    $.ajax({
        url: '<?php echo base_url("ShareHolderController/get_share_holder/"); ?>' + id,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                deleteShareHolderId = id;
                $('#deleteShareHolderName').text(response.data.name);
                $('#deleteShareHolderModal').modal('show');
            } else {
                showToast('Error', response.message, 'error');
            }
        },
        error: function() {
            showToast('Error', 'An error occurred while loading share holder details.', 'error');
        }
    });
}

function showToast(title, message, type) {
    const toastClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const iconClass = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
    
    const toastHtml = `
        <div class="alert ${toastClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="${iconClass}"></i> <strong>${title}:</strong> ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    $('body').append(toastHtml);
    
    // Auto-remove after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);
}
</script>

<style>
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.btn-group .btn {
    margin: 0 2px;
}

.modal-header.bg-primary {
    background-color: #007bff !important;
}

.modal-header.bg-danger {
    background-color: #dc3545 !important;
}
</style>
