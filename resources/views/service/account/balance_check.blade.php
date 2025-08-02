@extends('layouts.app')
@section('title', "Balance Check")
@section('pagetitle', "Balance Check")

@section('content')
    <div class="content">
        <h3 class="mb-4">Balance Check</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" style="border-radius:.8rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #eee;">
                    <div class="panel-heading" style="background-color: #f9f9f9; border-bottom: 1px solid #eee; padding: 15px 20px; border-top-left-radius: .8rem; border-top-right-radius: .8rem;">
                        <h5 class="panel-title" style="font-size: 18px; margin: 0; font-weight: 600;">Agent Details</h5>
                    </div>

                    <form id="balanceCheckForm" method="post">
                        @csrf
                        <div class="panel-body" style="padding: 25px 20px;">
                            <div class="row mb-4">
                                <div class="form-group col-md-6">
                                    <label style="font-weight: 500; margin-bottom: 8px; display: block;">Entity ID</label>
                                    <input type="text" class="form-control" id="entityId" name="entityId" placeholder="Enter Entity ID" required style="height: 45px; border-radius: 8px; border: 1px solid #ddd; padding: 8px 15px; transition: border-color 0.3s;">
                                </div>

                                <div class="form-group col-md-6">
                                    <label style="font-weight: 500; margin-bottom: 8px; display: block;">DSC Provider</label>
                                    <input type="text" class="form-control" name="dscProvider" placeholder="Enter DSC Provider" required style="height: 45px; border-radius: 8px; border: 1px solid #ddd; padding: 8px 15px; transition: border-color 0.3s;">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label style="font-weight: 500; margin-bottom: 8px; display: block;">DSC Serial Number</label>
                                    <input type="text" class="form-control" name="dscSerialNumber" placeholder="Enter DSC Serial Number" required style="height: 45px; border-radius: 8px; border: 1px solid #ddd; padding: 8px 15px; transition: border-color 0.3s;">
                                </div>

                                <div class="form-group col-md-6" style=" gap: 10px; align-items: center;">
                                    <label style="flex-basis: 100px; font-weight: 500;">DSC Expiry Date</label>
                                    
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        <input type="text" id="day" class="form-control" maxlength="2" placeholder="DD" 
                                            oninput="formatDate()" 
                                            style="width: 60px; text-align: center; font-weight: bold; font-size: 16px; border: 1px solid #ddd; border-radius: 8px; height: 45px; transition: border-color 0.3s;">
                                        
                                        <span style="font-size: 18px; color: #777;">/</span>
                                        
                                        <input type="text" id="month" class="form-control" maxlength="2" placeholder="MM" 
                                            oninput="formatDate()" 
                                            style="width: 60px; text-align: center; font-weight: bold; font-size: 16px; border: 1px solid #ddd; border-radius: 8px; height: 45px; transition: border-color 0.3s;">
                                        
                                        <span style="font-size: 18px; color: #777;">/</span>
                                        
                                        <input type="text" id="year" class="form-control" maxlength="2" placeholder="YY" 
                                            oninput="formatDate()" 
                                            style="width: 60px; text-align: center; font-weight: bold; font-size: 16px; border: 1px solid #ddd; border-radius: 8px; height: 45px; transition: border-color 0.3s;">
                                    </div>
                                    
                                    <!-- Hidden Input to Store the Combined Date -->
                                    <input type="hidden" name="dscExpiryDate" id="dscExpiryDate">
                                </div>

                                <script>
                                function formatDate() {
                                    let day = document.getElementById("day").value.replace(/^0+/, ''); // Remove leading zeros
                                    let month = document.getElementById("month").value.replace(/^0+/, ''); // Remove leading zeros
                                    let year = document.getElementById("year").value; // Keep as is

                                    // Ensure at least some values exist before updating the hidden input
                                    if (day && month && year) {
                                        document.getElementById("dscExpiryDate").value = `${day} ${month} ${year}`;
                                    }
                                }
                                </script>
                            </div>

                            <input type="hidden" name="authKey" id="authKey">
                        </div>

                        <div class="panel-footer text-center" style="background-color: #f9f9f9; padding: 20px; border-top: 1px solid #eee; border-bottom-left-radius: .8rem; border-bottom-right-radius: .8rem;">
                            <button type="submit" class="btn bg-teal-800 btn-labeled legitRipple btn-lg" style="border-radius: 8px; padding: 12px 30px; font-weight: 500; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: all 0.3s ease;">
                                <b style="padding: 9px 12px; background-color: rgba(0,0,0,0.1); border-radius: 8px 0 0 8px;"><i class="icon-calculator"></i></b> Check Balance
                            </button>
                        </div>
                    </form>

                    <div id="balanceResult" class="panel-body" style="display:none; padding: 25px 20px; border-top: 1px solid #eee;">
                        <h4 style="font-size: 18px; margin-bottom: 20px; font-weight: 600;">Balance Check Result</h4>
                        <div id="resultContent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script type="text/javascript">
$(document).ready(function() {
    // Set authKey to be the same as entityId
    $('#entityId').on('input', function() {
        $('#authKey').val($(this).val());
    });

    // Form submission handler
    $('#balanceCheckForm').on('submit', function(e) {
        e.preventDefault();
        
        // Disable submit button and show loading
        var submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true).html('<b><i class="fa fa-spinner fa-spin"></i></b> Checking Balance');

        // Clear previous results
        $('#balanceResult').hide();
        $('#resultContent').empty();

        // Prepare form data
        var formData = new FormData(this);

        // AJAX submission
        $.ajax({
            url: "{{ route('checkBalance') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                // Show result panel
                console.log(response);
                $('#balanceResult').show();

                // Determine response type and display accordingly
                if (response.status === 'success' || response.statuscode === 'TXN') {
                    let balanceAmount = response.data.amount || 'N/A';
                    let statusMessage = response.data.status || 'Balance check completed successfully';

                    $('#resultContent').html(`
                        <div class="alert alert-success balance-result-container">
                           
                            
                            <div class="balance-details mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="balance-item">
                                            <i class="fa fa-money text-primary mr-2"></i>
                                            <strong>Current Balance:</strong>
                                            <span class="balance-amount text-success ml-2" style="font-size: 1.5rem;">
                                                ${balanceAmount}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="balance-item">
                                            <i class="fa fa-info-circle text-info mr-2"></i>
                                            <strong>Status:</strong>
                                            <span class="ml-2">${statusMessage}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            ${response.message ? `
                            <div class="additional-info mt-3">
                                <i class="fa fa-external-link text-primary mr-2"></i>
                                <a href="${response.message}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    View Additional Details
                                </a>
                            </div>
                            ` : ''}
                        </div>
                    `);
                } else {
                    $('#resultContent').html(`
                        <div class="alert alert-danger error-result-container">
                            <div class="error-result-header">
                                <i class="fa fa-times-circle text-danger mr-2" style="font-size: 2rem;"></i>
                                <h4 class="d-inline-block">Balance Inquiry Failed</h4>
                            </div>
                            
                            <div class="error-details mt-3">
                                <i class="fa fa-exclamation-triangle text-warning mr-2"></i>
                                <strong>Error Details:</strong>
                                <p class="mt-2">${response.description || 'Unable to retrieve balance information'}</p>
                            </div>
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                // Show error result
                $('#balanceResult').show();
                $('#resultContent').html(`
                    <div class="alert alert-danger error-result-container">
                        <div class="error-result-header">
                            <i class="fa fa-times-circle text-danger mr-2" style="font-size: 2rem;"></i>
                            <h4 class="d-inline-block">Connection Error</h4>
                        </div>
                        
                        <div class="error-details mt-3">
                            <i class="fa fa-network-wired text-warning mr-2"></i>
                            <strong>Network Error:</strong>
                            <p class="mt-2">An unexpected error occurred while processing your request. Please try again later.</p>
                        </div>
                    </div>
                `);
            },
            complete: function() {
                // Re-enable submit button
                submitButton.prop('disabled', false).html('<b><i class="icon-calculator"></i></b> Check Balance');
            }
        });
    });
});
</script>
@endpush

@push('css')
<style>
.balance-result-container,
.error-result-container {
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.balance-result-header,
.error-result-header {
    display: flex;
    align-items: center;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.balance-details,
.error-details {
    padding: 15px;
    background-color: rgba(255,255,255,0.9);
    border-radius: 5px;
}

.balance-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.balance-amount {
    font-weight: bold;
    color: #28a745;
}

/* Additional UI Improvements */
.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    outline: 0;
}

.form-group {
    margin-bottom: 1.5rem;
}

/* Improve spacing and readability */
@media (max-width: 767px) {
    .form-group {
        margin-bottom: 1rem;
    }
    
    .panel-body {
        padding: 15px;
    }
}

/* Add subtle hover effect to inputs */
input.form-control:hover {
    border-color: #bbb;
}
</style>
@endpush