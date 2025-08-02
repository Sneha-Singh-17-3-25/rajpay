@extends('layouts.app')

@section('title', "Pending Approvals")
@section('pagetitle', "Pending Approvals")

@php
    $table = "yes";
@endphp

@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Pending Approvals (Emitra Agents)</h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="datatable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Serial No</th>
                                    <th>Request Date</th>
                                    
                                    <th>Kiosk Code</th>
                                    <th>BC AGENT ID</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Date of Birth</th>
                                    <th>Email</th>
                                    <th>Shop Name</th>
                                    <th>Phone Number</th>
                                    <th>Alt. Phone</th>
                                    <th>Telephone</th>
                                    <th>PAN</th>
                                    
                                    <!-- Residential Address -->
                                    <th>Address</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>District</th>
                                    <th>Area</th>
                                    <th>PIN Code</th>
                                    
                                    <!-- Shop Address -->
                                    <th>Shop Address</th>
                                    <th>Shop State</th>
                                    <th>Shop City</th>
                                    <th>Shop District</th>
                                    <th>Shop Area</th>
                                    <th>Shop PIN</th>
                                    
                                    <!-- Agent Type -->
                                    <th>Agent Type</th>
                                 
                                    <th>Status</th>
                                    <th style="width: 200px;">Action</th>
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
                <h4 class="modal-title" id="detailsModalLabel">Agent Details</h4>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Agent Modal -->
<div class="modal fade" id="editAgentModal" tabindex="-1" role="dialog" aria-labelledby="editAgentModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="editAgentModalLabel">Edit Agent</h4>
            </div>
            <div class="modal-body">
                <form id="editAgentForm">
                    <input type="hidden" id="edit_agent_id">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="kioskcode">Kiosk Code</label>
                            <input type="text" class="form-control" id="edit_kioskcode" name="kioskcode">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="bcId">BC AGENT ID</label>
                            <input type="text" class="form-control" id="edit_bcId" name="bcId">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="agentName">First Name</label>
                            <input type="text" class="form-control" id="edit_agentName" name="agentName">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="middlename">Middle Name</label>
                            <input type="text" class="form-control" id="edit_middlename" name="middlename">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="agentLastName">Last Name</label>
                            <input type="text" class="form-control" id="edit_agentLastName" name="agentLastName">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="agentDob">Date of Birth</label>
                            <input type="date" class="form-control" id="edit_agentDob" name="agentDob">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="agentEmail">Email</label>
                            <input type="email" class="form-control" id="edit_agentEmail" name="agentEmail">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="agentShopName">Shop Name</label>
                            <input type="text" class="form-control" id="edit_agentShopName" name="agentShopName">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="agentPhoneNumber">Phone Number</label>
                            <input type="text" class="form-control" id="edit_agentPhoneNumber" name="agentPhoneNumber">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="alternatenumber">Alt. Phone</label>
                            <input type="text" class="form-control" id="edit_alternatenumber" name="alternatenumber">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="telephone">Telephone</label>
                            <input type="text" class="form-control" id="edit_telephone" name="telephone">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="userPan">PAN</label>
                            <input type="text" class="form-control" id="edit_userPan" name="userPan">
                        </div>
                    </div>
                    
                    <h5 class="mt-4"><strong>Residential Address</strong></h5>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="agentAddress">Address</label>
                            <input type="text" class="form-control" id="edit_agentAddress" name="agentAddress">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="agentState">State</label>
                            <input type="text" class="form-control" id="edit_agentState" name="agentState">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="agentCityName">City</label>
                            <input type="text" class="form-control" id="edit_agentCityName" name="agentCityName">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="district">District</label>
                            <input type="text" class="form-control" id="edit_district" name="district">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="area">Area</label>
                            <input type="text" class="form-control" id="edit_area" name="area">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="agentPinCode">PIN Code</label>
                            <input type="text" class="form-control" id="edit_agentPinCode" name="agentPinCode">
                        </div>
                    </div>
                    
                    <h5 class="mt-4"><strong>Shop Address</strong></h5>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="shopaddress">Shop Address</label>
                            <input type="text" class="form-control" id="edit_shopaddress" name="shopaddress">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="shopstate">Shop State</label>
                            <input type="text" class="form-control" id="edit_shopstate" name="shopstate">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="shopcity">Shop City</label>
                            <input type="text" class="form-control" id="edit_shopcity" name="shopcity">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="shopdistrict">Shop District</label>
                            <input type="text" class="form-control" id="edit_shopdistrict" name="shopdistrict">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="shoparea">Shop Area</label>
                            <input type="text" class="form-control" id="edit_shoparea" name="shoparea">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="shoppincode">Shop PIN</label>
                            <input type="text" class="form-control" id="edit_shoppincode" name="shoppincode">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveAgentBtn">Save Changes</button>
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

/* Status badge styling */
.badge {
    padding: 5px 8px;
    font-size: 11px;
    text-transform: capitalize;
    border-radius: 3px;
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

h5.mt-4 {
    margin-top: 20px;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 1px solid #eee;
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
</style>
@endpush

@push('script')
<script type="text/javascript">
$(document).ready(function () {
    var url = "{{ route('pending_approvals') }}"; // Fetch pending agents

    var table = $('#datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": url,
            "type": "POST",
            "headers": {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            "dataSrc": function(response) {
                return response.data;
            }
        },
        "columns": [
            { "data": null, "orderable": false },
           {
            data: 'created_at',
            render: function (data, type, row) {
                if (!data) return '';
                
                var date = new Date(data);
                var day = String(date.getDate()).padStart(2, '0');
                var month = String(date.getMonth() + 1).padStart(2, '0');
                var year = date.getFullYear();
                var hours = String(date.getHours()).padStart(2, '0');
                var minutes = String(date.getMinutes()).padStart(2, '0');

                return `${day}-${month}-${year} `;
            }
        },
            { "data": "kioskcode" },
            { "data": "bcId" },
            { "data": "agentName" },
            { "data": "middlename", "defaultContent": "" },
            { "data": "agentLastName" },
           { 
            "data": "agentDob", 
            "defaultContent": "",
            "render": function(data, type, row) {
                return formatDate(data); // Call formatDate function
            }
        },
            { "data": "agentEmail" },
            { "data": "agentShopName" },
            { "data": "agentPhoneNumber" },
            { "data": "alternatenumber", "defaultContent": "" },
            { "data": "telephone", "defaultContent": "" },
            { "data": "userPan" },
            { "data": "agentAddress" },
            { "data": "agentState" },
            { "data": "agentCityName" },
            { "data": "district", "defaultContent": "" },
            { "data": "area", "defaultContent": "" },
            { "data": "agentPinCode" },
            { "data": "shopaddress", "defaultContent": "" },
            { "data": "shopstate", "defaultContent": "" },
            { "data": "shopcity", "defaultContent": "" },
            { "data": "shopdistrict", "defaultContent": "" },
            { "data": "shoparea", "defaultContent": "" },
            { "data": "shoppincode", "defaultContent": "" },
            { "data": "agenttype", "render": function(data) { return data == 1 ? "Normal Agent" : "Direct Agent"; } },
            { "data": "status", "render": function(data) {
                let badgeClass = (data === 'approved') ? 'badge-success' :
                                 (data === 'failed' || data === 'rejected') ? 'badge-danger' : 'badge-warning';
                return `<span class="badge ${badgeClass}">${data === 'failed' ? 'rejected' : data}</span>`;
            }},
            { "data": null, "orderable": false, "render": function(data, type, full) {
                return `
                <div class="action-btns">
                    <button class="btn btn-xs btn-primary" onclick="editAgent('${full.id}')">Edit</button>
                    <button class="btn btn-xs btn-success" onclick="changeStatus('${full.id}', 'approved')">Approve</button>
                    <button class="btn btn-xs btn-danger" onclick="changeStatus('${full.id}', 'rejected')">Reject</button>
                </div>`;
            }}
        ],
        "columnDefs": [
            {
                "searchable": false,
                "orderable": false,
                "targets": 0
            }
        ],
        "rowCallback": function(row, data, index) {
            var pageInfo = $('#datatable').DataTable().page.info(); // Get pagination info
    var serialNumber = pageInfo.start + index + 1; // Adjust serial number based on current page
    $('td:eq(0)', row).html(serialNumber);
        },
        "pageLength": 10,
        "order": [[1, "asc"]],
        "responsive": true,
        "scrollX": true
    });
    
    // Save Agent Changes
    $('#saveAgentBtn').on('click', function() {
        var formData = {};
        $('#editAgentForm').find('input').each(function() {
            var fieldName = $(this).attr('name');
            formData[fieldName] = $(this).val();
        });
        
        formData.id = $('#edit_agent_id').val();
        
        $.ajax({
            url: "{{ route('update_agent') }}",
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#editAgentModal').modal('hide');
                    table.ajax.reload();
                    notify("Agent updated successfully", 'success');
                } else {
                    notify(response.message, 'error');
                }
            },
            error: function(xhr) {
                notify("Error updating agent: " + xhr.responseJSON.message, 'error');
            }
        });
    });
});

// Edit Agent - Open Modal with Data
function editAgent(id) {
    var table = $('#datatable').DataTable();
    var data = table.rows().data();
    var agentData = null;

    // alert(id);
    // Find the agent data by ID
    // for (var i = 0; i < data.length; i++) {
    //     if (data[i].id === id) {
    //         agentData = data[i];
    //         break;
    //     }
    // }
  $.ajax({
    url: "{{ route('get_agent_details') }}", // Ensure this route is correctly defined in web.php
    type: 'GET',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    data: { 'id': id },
    success: function(response) {
        if (response.success && response.data) {
            var agentData = response.data;
            console.log(agentData); // Debugging: Check the fetched data in the console
              if (agentData) {
        // Set form fields
        $('#edit_agent_id').val(id);
        
        // Populate all form fields
        $('#edit_kioskcode').val(agentData.kioskcode || '');
        $('#edit_bcId').val(agentData.bcId || '');
        $('#edit_agentName').val(agentData.agentName || '');
        $('#edit_middlename').val(agentData.middlename || '');
        $('#edit_agentLastName').val(agentData.agentLastName || '');
        $('#edit_agentDob').val(formatDate(agentData.agentDob));
        $('#edit_agentEmail').val(agentData.agentEmail || '');
        $('#edit_agentShopName').val(agentData.agentShopName || '');
        $('#edit_agentPhoneNumber').val(agentData.agentPhoneNumber || '');
        $('#edit_alternatenumber').val(agentData.alternatenumber || '');
        $('#edit_telephone').val(agentData.telephone || '');
        $('#edit_userPan').val(agentData.userPan || '');
        
        // Residential address
        $('#edit_agentAddress').val(agentData.agentAddress || '');
        $('#edit_agentState').val(agentData.agentState || '');
        $('#edit_agentCityName').val(agentData.agentCityName || '');
        $('#edit_district').val(agentData.district || '');
        $('#edit_area').val(agentData.area || '');
        $('#edit_agentPinCode').val(agentData.agentPinCode || '');
        
        // Shop address
        $('#edit_shopaddress').val(agentData.shopaddress || '');
        $('#edit_shopstate').val(agentData.shopstate || '');
        $('#edit_shopcity').val(agentData.shopcity || '');
        $('#edit_shopdistrict').val(agentData.shopdistrict || '');
        $('#edit_shoparea').val(agentData.shoparea || '');
        $('#edit_shoppincode').val(agentData.shoppincode || '');
        
        // Show the modal
        $('#editAgentModal').modal('show');
    } else {
        notify("Could not find agent data", 'error');
    }
            // Example: Populate form fields (modify as per your form structure)
           
        } else {
            notify("Agent data not found!", 'error');
        }
    },
    error: function(xhr) {
        notify("Error fetching agent details: " + xhr.responseJSON.message, 'error');
    }
});

    
    
    
  
}

// Change Status
function changeStatus(id, status) {
    swal({
        title: status === 'approved' ? 'Approve Agent?' : 'Reject Agent?',
        text: "Are you sure you want to " + (status === 'approved' ? 'approve' : 'reject') + " this agent?",
        icon: "warning",
        buttons: ["Cancel", status === 'approved' ? 'Approve' : 'Reject'],
        dangerMode: status === 'rejected'
    }).then((willChange) => {
        if (willChange) {
            $.ajax({
                url: "{{ route('updateStatus') }}",
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: { 'id': id, 'status': status },
                success: function(response) {
                    if (response.success) {
                        $('#datatable').DataTable().ajax.reload();
                        notify("Status changed successfully", 'success');
                    } else {
                        notify(response.message || "Error changing status", 'error');
                    }
                },
                error: function(xhr) {
                    notify("Error changing status: " + (xhr.responseJSON ? xhr.responseJSON.message : "Unknown error"), 'error');
                }
            });
        }
    });
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

function formatDate(dateString) {
    if (!dateString) return ''; // Null/empty case handle kare
    let parts = dateString.split('-'); // Split by '-'
    return parts[2] + '-' + parts[1] + '-' + parts[0]; // Rearrange to DD-MM-YYYY
}

</script>
@endpush