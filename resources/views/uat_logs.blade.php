@extends('layouts.app')

@section('title', "UAT Logs")
@section('pagetitle', "UAT Logs")

@php
    $table = "yes";
@endphp

@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="panel-title">UAT Logs</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-success btn-sm" id="exportBtn"><i class="fa fa-download"></i> Export</button>
                            <button class="btn btn-danger btn-sm" id="cleanupBtn"><i class="fa fa-trash"></i> Cleanup Old Logs</button>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter_endpoint">Endpoint</label>
                                <input type="text" class="form-control" id="filter_endpoint" placeholder="Filter by endpoint">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter_start_date">Start Date</label>
                                <input type="date" class="form-control" id="filter_start_date">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter_end_date">End Date</label>
                                <input type="date" class="form-control" id="filter_end_date">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button class="btn btn-primary" id="applyFiltersBtn">Apply Filters</button>
                                    <button class="btn btn-default" id="resetFiltersBtn">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="datatable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Endpoint</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th style="width: 150px;">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for viewing details -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="detailsModalLabel">Log Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="endpoint"><strong>Endpoint:</strong></label>
                            <p id="view_endpoint"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="created_at"><strong>Created At:</strong></label>
                            <p id="view_created_at"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="updated_at"><strong>Updated At:</strong></label>
                            <p id="view_updated_at"></p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="request_data"><strong>Request Data:</strong></label>
                            <pre id="view_request_data" class="pre-scrollable" style="max-height: 250px; background-color: #f8f9fa; padding: 10px; border-radius: 4px;"></pre>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="response_data"><strong>Response Data:</strong></label>
                            <pre id="view_response_data" class="pre-scrollable" style="max-height: 250px; background-color: #f8f9fa; padding: 10px; border-radius: 4px;"></pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="deleteLogBtn">Delete</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Cleanup Modal -->
<div class="modal fade" id="cleanupModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Cleanup Old Logs</h4>
            </div>
            <div class="modal-body">
                <p>Delete logs older than:</p>
                <div class="form-group">
                    <select class="form-control" id="cleanup_days">
                        <option value="7">7 days</option>
                        <option value="14">14 days</option>
                        <option value="30" selected>30 days</option>
                        <option value="60">60 days</option>
                        <option value="90">90 days</option>
                    </select>
                </div>
                <div class="alert alert-warning">
                    <strong>Warning!</strong> This action cannot be undone. All logs older than the selected period will be permanently deleted.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmCleanupBtn">Delete Old Logs</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<style>
/* Custom styles for DataTable */
.dataTables_wrapper .top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.dataTables_wrapper .bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;
}

.dataTables_length {
    margin-right: 20px;
}

.dataTables_filter {
    margin-left: auto;
}

.column-search {
    width: 100%;
    font-size: 12px;
    padding: 2px 5px;
}

/* Improve horizontal scrolling */
.dataTables_scrollBody {
    border-bottom: 1px solid #ddd;
}

/* Improved action buttons */
.action-btns {
    display: flex;
    gap: 5px;
    justify-content: center;
}

.action-btns .btn {
    padding: 3px 8px;
    font-size: 12px;
    border-radius: 3px;
    text-transform: uppercase;
    font-weight: 500;
}

/* Table improvements */
#datatable thead th {
    font-weight: 600;
    white-space: nowrap;
    background-color: #f8f9fa;
}

#datatable tbody td {
    vertical-align: middle;
    padding: 8px;
}

/* Modal improvements */
.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #eee;
}

.modal-title {
    font-weight: 600;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    border-top: 1px solid #eee;
    padding: 15px;
}

/* Form styling */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    font-weight: 500;
    margin-bottom: 5px;
    display: block;
}

/* Spacing for filter buttons */
.mb-3 {
    margin-bottom: 20px;
}

/* Responsive improvements */
@media (max-width: 767.98px) {
    .dataTables_wrapper .top,
    .dataTables_wrapper .bottom {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .dataTables_length,
    .dataTables_filter,
    .dataTables_info,
    .dataTables_paginate {
        margin: 5px 0;
        width: 100%;
    }
    
    .action-btns {
        flex-direction: column;
        align-items: center;
    }
    
    .action-btns .btn {
        margin: 2px 0;
        width: 100%;
    }
}

/* Panel styling improvements */
.panel {
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.panel-heading {
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.panel-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.panel-body {
    padding: 15px;
}

/* JSON formatting */
pre {
    white-space: pre-wrap;
    word-wrap: break-word;
}
</style>
@endpush

@push('script')
<script type="text/javascript">
// Global variable for current log ID
var currentLogId = null;

$(document).ready(function () {
    var url = "{{ route('uat_logs') }}";
    
    // Initialize DataTable
    var table = $('#datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": url,
            "type": "POST",
            "headers": {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            "data": function(d) {
                d.endpoint = $('#filter_endpoint').val();
                d.start_date = $('#filter_start_date').val();
                d.end_date = $('#filter_end_date').val();
                return d;
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "endpoint" },
            { 
                "data": "created_at", 
                "render": function(data, type, row) {
                    return formatDateTime(data);
                }
            },
            { 
                "data": "updated_at", 
                "render": function(data, type, row) {
                    return formatDateTime(data);
                }
            },
            { 
                "data": null, 
                "orderable": false, 
                "render": function(data, type, full) {
                    return `
                    <div class="action-btns">
                        <button class="btn btn-xs btn-primary view-details" data-id="${full.id}">View Details</button>
                    </div>`;
                }
            }
        ],
        "order": [[0, "desc"]], 
        "responsive": true,
        "scrollX": true,
        "pagingType": "full_numbers",
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
        "pageLength": 10,
        "drawCallback": function() {
            // Bind click events to dynamically created buttons
            $('.view-details').on('click', function() {
                var id = $(this).data('id');
                viewLogDetails(id);
            });
        }
    });
    
    // Apply filters
    $('#applyFiltersBtn').on('click', function() {
        table.ajax.reload();
    });
    
    // Reset filters
    $('#resetFiltersBtn').on('click', function() {
        $('#filter_endpoint').val('');
        $('#filter_start_date').val('');
        $('#filter_end_date').val('');
        table.ajax.reload();
    });
    
    // Export logs
    $('#exportBtn').on('click', function() {
        var endpoint = $('#filter_endpoint').val();
        var startDate = $('#filter_start_date').val();
        var endDate = $('#filter_end_date').val();
        
        var exportUrl = "{{ route('export_logs') }}" + 
            "?endpoint=" + encodeURIComponent(endpoint) +
            "&start_date=" + encodeURIComponent(startDate) +
            "&end_date=" + encodeURIComponent(endDate);
            
        window.location.href = exportUrl;
    });
    
    // Show cleanup modal
    $('#cleanupBtn').on('click', function() {
        $('#cleanupModal').modal('show');
    });
    
    // Confirm cleanup
    $('#confirmCleanupBtn').on('click', function() {
        var days = $('#cleanup_days').val();
        
        $.ajax({
            url: "{{ route('cleanup_logs') }}",
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { 'days': days },
            success: function(response) {
                if (response.success) {
                    $('#cleanupModal').modal('hide');
                    table.ajax.reload();
                    notify(response.message, 'success');
                } else {
                    notify(response.message || "Error cleaning up logs", 'error');
                }
            },
            error: function(xhr) {
                notify("Error cleaning up logs: " + (xhr.responseJSON ? xhr.responseJSON.message : "Unknown error"), 'error');
            }
        });
    });
    
    // Delete log
    $('#deleteLogBtn').on('click', function() {
        if (!currentLogId) return;
        
        swal({
            title: "Delete Log?",
            text: "Are you sure you want to delete this log? This action cannot be undone.",
            icon: "warning",
            buttons: ["Cancel", "Delete"],
            dangerMode: true
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "{{ route('delete_log') }}",
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: { 'id': currentLogId },
                    success: function(response) {
                        if (response.success) {
                            $('#detailsModal').modal('hide');
                            table.ajax.reload();
                            notify("Log deleted successfully", 'success');
                        } else {
                            notify(response.message || "Error deleting log", 'error');
                        }
                    },
                    error: function(xhr) {
                        notify("Error deleting log: " + (xhr.responseJSON ? xhr.responseJSON.message : "Unknown error"), 'error');
                    }
                });
            }
        });
    });
});

// View Log Details - Make it accessible in global scope
function viewLogDetails(id) {
    currentLogId = id;
    
    $.ajax({
        url: "{{ route('get_log_details') }}",
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { 'id': id },
        success: function(response) {
            if (response.success && response.data) {
                var logData = response.data;
                
                // Populate modal with log details
                $('#view_endpoint').text(logData.endpoint || '');
                
                // Format dates
                $('#view_created_at').text(formatDateTime(logData.created_at));
                $('#view_updated_at').text(formatDateTime(logData.updated_at));
                
                // Format JSON data for better readability
                try {
                    var requestData = logData.request_data ? JSON.parse(logData.request_data) : {};
                    $('#view_request_data').text(JSON.stringify(requestData, null, 4));
                } catch (e) {
                    $('#view_request_data').text(logData.request_data || '');
                }
                
                try {
                    var responseData = logData.response_data ? JSON.parse(logData.response_data) : {};
                    $('#view_response_data').text(JSON.stringify(responseData, null, 4));
                } catch (e) {
                    $('#view_response_data').text(logData.response_data || '');
                }
                
                // Show the modal
                $('#detailsModal').modal('show');
            } else {
                notify("Log details not found!", 'error');
            }
        },
        error: function(xhr) {
            notify("Error fetching log details: " + (xhr.responseJSON ? xhr.responseJSON.message : "Unknown error"), 'error');
        }
    });
}

// Format Date and Time Helper
function formatDateTime(dateStr) {
    if (!dateStr) return '';
    
    var date = new Date(dateStr);
    var day = String(date.getDate()).padStart(2, '0');
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var year = date.getFullYear();
    var hours = String(date.getHours()).padStart(2, '0');
    var minutes = String(date.getMinutes()).padStart(2, '0');
    var seconds = String(date.getSeconds()).padStart(2, '0');
    
    return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
}

// Notify Function
function notify(message, type) {
    $.alert({
        title: type === "success" ? "Success!" : "Oops!",
        content: message,
        type: type === "success" ? "green" : "red",
        theme: 'modern',
        animation: 'scale',
        closeAnimation: 'scale',
        buttons: {
            okay: {
                text: 'Okay',
                btnClass: type === "success" ? 'btn-success' : 'btn-danger'
            }
        }
    });
}
</script>
@endpush