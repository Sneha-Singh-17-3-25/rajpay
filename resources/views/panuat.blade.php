@extends('layouts.app')
@section('title', "Balance Check")
@section('pagetitle', "Balance Check")
@php
    $table = "yes";
    $search = "hide";
@endphp

@section('content')
    <div class="content">
        <div class="tabbable">
            <ul class="nav nav-tabs bg-teal-600 nav-tabs-component no-margin-bottom">
                <li class="active"><a href="#new-pan" data-toggle="tab" class="legitRipple" aria-expanded="true" onclick="SETTITLE('A')">New Pan</a></li>
                <li><a href="#pan-correction" data-toggle="tab" class="legitRipple" aria-expanded="false" onclick="SETTITLE('CR')">Pan Correction</a></li>
                <li><a href="#pan-status" data-toggle="tab" class="legitRipple" aria-expanded="false" onclick="SETTITLE('CR')">Pan Status</a></li>
                <li><a href="#entity-reports" data-toggle="tab" class="legitRipple" aria-expanded="false" onclick="SETTITLE('CR')">Entity Reports</a></li>
                <li><a href="#incomplete-application" data-toggle="tab" class="legitRipple" aria-expanded="false" onclick="SETTITLE('I')">Incomplete Application</a></li>
                <li><a href="#balance-check" data-toggle="tab" class="legitRipple" aria-expanded="false" onclick="SETTITLE('B')">Balance Check</a></li>
                <li><a href="#transaction-status" data-toggle="tab" class="legitRipple" aria-expanded="false" onclick="SETTITLE('T')">Transaction Status</a></li>
            </ul>

            <div class="tab-content">
                <!-- New PAN Application Tab - Required fields for newPanReq -->
                <div class="tab-pane active" id="new-pan">
                    <div class="panel panel-default">
                        <form action="{{ route('pancardpay') }}" method="post" id="newPanForm"> 
                            {{ csrf_field() }}

                            <div class="panel-body">
                                <h5>Basic Details</h5>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>PAN Card Type</label>
                                        <select class="form-control" name="applnMode" required="" aria-required="true">
                                            <option value="">Select Application Mode</option>
                                            <option value="K">EKYC Based</option>
                                            <option value="E">Scanned Based</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" name="firstName" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" name="lastName" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="emailId" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Mobile No</label>
                                        <input type="text" class="form-control" name="mobileNo" required>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-footer text-center">
                                <button type="submit" class="btn bg-teal-800 btn-labeled legitRipple btn-lg"
                                    data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Submitting">
                                    <b><i class="icon-paperplane"></i></b> Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- PAN Correction Tab - Required fields for crPanReq -->
                <div class="tab-pane" id="pan-correction">
                    <div class="panel panel-default">
                        <form action="{{ route('pancardcorrection') }}" method="post" id="correctionPanForm"> 
                            <input type="hidden" name="category" value="CR">
                            <input type="hidden" name="reqType" value="CR">
                            {{ csrf_field() }}

                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>PAN Card Type</label>
                                        <select class="form-control" name="applnMode" required="" aria-required="true">
                                            <option value="">Select Application Mode</option>
                                            <option value="K">EKYC Based</option>
                                            <option value="E">Scanned Based</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>PAN Number</label>
                                        <input type="text" class="form-control" name="pan" placeholder="Enter PAN Number" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" name="firstName" placeholder="Enter First Name">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Middle Name</label>
                                        <input type="text" class="form-control" name="middleName" placeholder="Enter Middle Name">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" name="lastName" placeholder="Enter Last Name">
                                    </div>
                                     <div class="form-group col-md-6">
                                        <label>Mobile Number</label>
                                        <input type="text" class="form-control" name="telOrMobNo" placeholder="Enter Mobile Number">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email ID</label>
                                        <input type="email" class="form-control" name="emailId" placeholder="Enter Email ID">
                                    </div>
                                </div>

                                <div class="row">
                                    
                                </div>

                                <div class="row">
                                   
                                </div>
                            </div>

                            <div class="panel-footer text-center">
                                <button type="submit" class="btn bg-teal-800 btn-labeled legitRipple btn-lg"
                                    data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Submitting">
                                    <b><i class="icon-paperplane"></i></b> Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Incomplete Application Tab - Required fields for incompleteApplication -->
                <div class="tab-pane" id="incomplete-application">
                    <div class="panel panel-default">
                        <form action="{{ route('incompleteapplication') }}" method="post" id="incompleteApplicationForm"> 
                            <input type="hidden" name="category" value="I">
                            {{ csrf_field() }}

                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Incomplete Transaction ID</label>
                                        <input type="text" class="form-control" autocomplete="off" name="incomplete_txn_id" placeholder="Enter Incomplete Transaction ID" required>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-footer text-center">
                                <button type="submit" class="btn bg-teal-800 btn-labeled legitRipple btn-lg"
                                    data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Submitting">
                                    <b><i class="icon-paperplane"></i></b> Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Balance Check Tab - Required fields for checkEntityBalance -->
                <div class="tab-pane" id="balance-check">
                    <div class="panel panel-default">
                        <form action="{{ route('balancecheck') }}" method="post" id="balanceCheckForm"> 
                            {{ csrf_field() }}
                            <input type="hidden" name="category" value="B">
                            <input type="hidden" name="entityId" value="YOUR_ENTITY_ID">
                            <input type="hidden" name="authKey" value="YOUR_AUTH_KEY">
                            <input type="hidden" name="dscProvider" value="YOUR_DSC_PROVIDER">
                            <input type="hidden" name="dscSerialNumber" value="YOUR_DSC_SERIAL">
                            <input type="hidden" name="dscExpiryDate" value="YOUR_EXPIRY_DATE">

                            <div class="panel-body">
                                <div class="text-center" id="balance-loading">
                                    <i class='fa fa-spin fa-spinner fa-2x'></i>
                                    <p>Checking balance...</p>
                                </div>
                                
                                <div id="balance-result" class="alert" style="display:none;">
                                    <h4><i class="icon-info3"></i> Balance Information</h4>
                                    <div id="balance-content"></div>
                                </div>
                            </div>

                            <div class="panel-footer text-center">
                                <button type="button" id="refreshBalance" class="btn bg-teal-800 btn-labeled legitRipple">
                                    <b><i class="icon-refresh"></i></b> Refresh Balance
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Transaction Status Tab - Required fields for checkTransactionStatus -->
                <div class="tab-pane" id="transaction-status">
                    <div class="panel panel-default">
                        <form action="{{ route('checktransaction') }}" method="post" id="transactionStatusForm"> 
                            {{ csrf_field() }}

                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Transaction Type</label>
                                        <select class="form-control" name="request_type" id="transactionType" required>
                                            <option value="T">Transaction Status (T)</option>
                                            <option value="D">Date-wise Report (D)</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6" id="txnIdGroup">
                                        <label>Transaction ID</label>
                                        <input type="text" class="form-control" name="txn_id" placeholder="Enter Transaction ID">
                                    </div>
                                </div>

                                <div id="transaction-result" class="alert" style="display:none; margin-top: 20px;">
                                    <h4><i class="icon-info3"></i> Transaction Status</h4>
                                    <div id="transaction-content"></div>
                                </div>
                            </div>

                            <div class="panel-footer text-center">
                                <button type="button" id="checkTransaction" class="btn bg-teal-800 btn-labeled legitRipple btn-lg">
                                    <b><i class="icon-search4"></i></b> Check Status
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="tab-pane" id="entity-reports">
                    <div class="panel panel-default">
                        <form action="{{ route('checkentityReports') }}" method="post" id="entityReportsForm">
                            {{ csrf_field() }}
                            <input type="hidden" name="category" value="R">

                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Start Date</label>
                                        <input type="date" class="form-control" name="start_date" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>End Date</label>
                                        <input type="date" class="form-control" name="end_date" required>
                                    </div>
                                </div>

                                <div id="entity-result" class="alert" style="display:none; margin-top: 20px;">
                                    <h4><i class="icon-info3"></i> Entity Reports</h4>
                                    <div id="entity-content"></div>
                                </div>
                            </div>

                            <div class="panel-footer text-center">
                                <button type="button" id="checkEntityReports" class="btn bg-teal-800 btn-labeled legitRipple btn-lg">
                                    <b><i class="icon-search4"></i></b> Generate Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="tab-pane" id="pan-status">
                    <div class="panel panel-default">
                        <form action="{{ route('pancardstatuscheck') }}" method="post" id="panStatusForm">
                            <input type="hidden" name="category" value="P">
                            <input type="hidden" name="request_type" value="P">
                            {{ csrf_field() }}

                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Acknowledge Number</label>
                                        <input type="text" class="form-control" autocomplete="off" name="acknowledge_no" placeholder="Enter Acknowledge Number" required>
                                    </div>
                                </div>
                                
                                <div id="pan-status-result" class="alert" style="display:none; margin-top: 20px;">
                                    <h4><i class="icon-info3"></i> PAN Status</h4>
                                    <div id="pan-status-content"></div>
                                </div>
                            </div>

                            <div class="panel-footer text-center">
                                <button type="button" id="checkPanStatus" class="btn bg-indigo-600 btn-labeled legitRipple btn-lg">
                                    <b><i class="icon-search4"></i></b> Check PAN Status
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="nsdlFormData">
            <form method="post" action="" id="nsdlFormData" name="f1">
                <input type="hidden" name="req" value="">
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.dobdate').datepicker({
                'autoclose': true,
                'clearBtn': true,
                'todayHighlight': true,
                'format': 'dd/mm/yyyy'
            });

            // Check balance automatically when the tab is clicked
            $('a[href="#balance-check"]').on('click', function() {
                checkBalance();
            });

            // Refresh balance button click handler
            $('#refreshBalance').on('click', function() {
                checkBalance();
            });

            // Transaction status check button click handler
            $('#checkTransaction').on('click', function() {
                checkTransactionStatus();
            });

            // Entity reports button click handler
            $('#checkEntityReports').on('click', function() {
                checkEntityReports();
            });

            // PAN status check button click handler
            $('#checkPanStatus').on('click', function() {
                checkPanStatus();
            });

            // Balance Check function
            function checkBalance() {
                $('#balance-loading').show();
                $('#balance-result').hide();

                $.ajax({
                    url: "{{ route('balancecheck') }}",
                    type: "POST",
                    data: $('#balanceCheckForm').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#balance-loading').hide();
                        $('#balance-result').show();

                        if (response.status) {
                            $('#balance-result').removeClass('alert-danger').addClass('alert-success');
                            $('#balance-content').html(`
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        
                                        <tr>
                                            <th>Current Balance</th>
                                            <td>₹ ${response.data.amount || '0'}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated</th>
                                            <td>${response.data.status || 'N/A'}</td>
                                        </tr>
                                    </table>
                                </div>
                            `);
                        } else {
                            $('#balance-result').removeClass('alert-success').addClass('alert-danger');
                            $('#balance-content').html(`<p>Error: ${response.message || 'Unable to fetch balance information'}</p>`);
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#balance-loading').hide();
                        $('#balance-result').show().removeClass('alert-success').addClass('alert-danger');
                        $('#balance-content').html(`<p>Error: ${error || 'An error occurred while fetching balance information.'}</p>`);
                    }
                });
            }

            // Transaction Status Check function
           function checkTransactionStatus() {
    const transactionBtn = $('#checkTransaction');
    const originalText = transactionBtn.html();
    transactionBtn.html('<b><i class="fa fa-spin fa-spinner"></i></b> Checking...').attr('disabled', true);

    $('#transaction-result').hide();

    $.ajax({
        url: "{{ route('checktransaction') }}",
        type: "POST",
        data: $('#transactionStatusForm').serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            transactionBtn.html(originalText).attr('disabled', false);
            $('#transaction-result').show();

            const data = response.data || {};
            const transactionType = $('#transactionType').val();

            if (response.status === "success" && data.status !== "NA") {
                $('#transaction-result').removeClass('alert-danger').addClass('alert-success');

                if (transactionType === 'T') {
                    // Single Transaction
                    $('#transaction-content').html(`
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Transaction ID</th>
                                    <td>${data.unique_txn_id || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>${data.status || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>${data.txn_tmsp || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>₹ ${data.amount || '0.00'}</td>
                                </tr>
                            </table>
                        </div>
                    `);
                } else {
                    // Date-wise Transaction Report
                    let tableRows = '';
                    if (data.list && data.list.length > 0) {
                        data.list.forEach(function(txn, index) {
                            tableRows += `
                                <tr>
                                    <td>${txn.unique_txn_id || 'N/A'}</td>
                                    <td>${txn.txn_tmsp || 'N/A'}</td>
                                    <td>${txn.status || 'N/A'}</td>
                                    <td>₹ ${txn.amount || '0.00'}</td>
                                </tr>
                            `;
                        });
                    } else {
                        tableRows = '<tr><td colspan="4">No transactions found</td></tr>';
                    }

                    $('#transaction-content').html(`
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>${tableRows}</tbody>
                            </table>
                        </div>
                    `);
                }
            } else {
                // Show specific error messages for known error codes
                $('#transaction-result').removeClass('alert-success').addClass('alert-danger');

                let errorMsg = "Unable to fetch transaction information.";
                if (data.errorcode || data.errordesc) {
                    errorMsg = `${data.errorcode || ''} ${data.errordesc || ''}`.trim();
                } else if (data.error && typeof data.error === 'object') {
                    const key = Object.keys(data.error)[0];
                    errorMsg = `${key}: ${data.error[key]}`;
                }

                $('#transaction-content').html(`<p>${errorMsg}</p>`);
            }
        },
        error: function(xhr, status, error) {
            transactionBtn.html(originalText).attr('disabled', false);
            $('#transaction-result').show().removeClass('alert-success').addClass('alert-danger');
            $('#transaction-content').html(`<p>Error: ${error || 'An error occurred while fetching transaction information.'}</p>`);
        }
    });
}


            // Entity Reports function
           function checkEntityReports() {
    const reportBtn = $('#checkEntityReports');
    const originalText = reportBtn.html();
    reportBtn.html('<b><i class="fa fa-spin fa-spinner"></i></b> Generating...').attr('disabled', true);

    $('#entity-result').hide();

    $.ajax({
        url: "{{ route('checkentityReports') }}",
        type: "POST",
        data: $('#entityReportsForm').serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            reportBtn.html(originalText).attr('disabled', false);
            $('#entity-result').show();

            const data = response.data || {};
            const reportList = data.reportList || [];

            if (response.status === 'success' && data.statusCode === "1" && reportList.length > 0) {
                $('#entity-result').removeClass('alert-danger').addClass('alert-success');

                let tableRows = '';
                reportList.forEach(function(report, index) {
                    tableRows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${report.transactionDate || 'N/A'}</td>
                            <td>${report.ackNo || 'N/A'}</td>
                            <td>₹ ${report.debit || '0.00'}</td>
                            <td>${report.applnType || 'N/A'}</td>
                            <td>${report.applnMode || 'N/A'}</td>
                            <td>${report.deviceType || 'N/A'}</td>
                            <td>${report.esignMode || 'N/A'}</td>
                        </tr>
                    `;
                });

                $('#entity-content').html(`
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Ack No</th>
                                    <th>Debit</th>
                                    <th>Application Type</th>
                                    <th>Application Mode</th>
                                    <th>Device Type</th>
                                    <th>eSign Mode</th>
                                </tr>
                            </thead>
                            <tbody>${tableRows}</tbody>
                        </table>
                    </div>
                `);
            } else {
                $('#entity-result').removeClass('alert-success').addClass('alert-danger');
                $('#entity-content').html(`<p>${data.status || 'No data available.'}</p>`);
            }
        },
        error: function(xhr, status, error) {
            reportBtn.html(originalText).attr('disabled', false);
            $('#entity-result').show().removeClass('alert-success').addClass('alert-danger');
            $('#entity-content').html(`<p>Error: ${error || 'An error occurred while fetching entity reports.'}</p>`);
        }
    });
}


            // PAN Status Check function
            function checkPanStatus() {
    const statusBtn = $('#checkPanStatus');
    const originalText = statusBtn.html();
    statusBtn.html('<b><i class="fa fa-spin fa-spinner"></i></b> Checking...').attr('disabled', true);
    
    $('#pan-status-result').hide();

    $.ajax({
        url: "{{ route('pancardstatuscheck') }}",
        type: "POST",
        data: $('#panStatusForm').serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            statusBtn.html(originalText).attr('disabled', false);
            $('#pan-status-result').show();

            const data = response.data || {};
            const error = data.error;

            // Check for error object and display message
            if (error && typeof error === 'object') {
                const errorCode = Object.keys(error)[0];
                const errorMsg = error[errorCode];

                $('#pan-status-result').removeClass('alert-success').addClass('alert-danger');
                $('#pan-status-content').html(`<p><strong>Error (${errorCode}):</strong> ${errorMsg}</p>`);
                return;
            }

            // If no error, assume success and show data
            $('#pan-status-result').removeClass('alert-danger').addClass('alert-success');
            $('#pan-status-content').html(`
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>Acknowledge Number</th>
                            <td>${data.ackNo || 'N/A'}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>${data.panStatus || 'N/A'}</td>
                        </tr>
                        <tr>
                            <th>Transaction ID</th>
                            <td>${data.txnid || 'N/A'}</td>
                        </tr>
                        <tr>
                            <th>Timestamp</th>
                            <td>${data.responseGeneratedTimeStamp || 'N/A'}</td>
                        </tr>
                    </table>
                </div>
            `);
        },
        error: function(xhr, status, error) {
            statusBtn.html(originalText).attr('disabled', false);
            $('#pan-status-result').show().removeClass('alert-success').addClass('alert-danger');
            $('#pan-status-content').html(`<p>Error: ${error || 'An error occurred while checking PAN status.'}</p>`);
        }
    });
}


            // Function to handle tab switching for autoloading balance
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                if ($(e.target).attr('href') === '#balance-check') {
                    checkBalance();
                }
            });

            // Transaction type change handler - show/hide date fields
            $('#transactionType').on('change', function() {
                const selectedType = $(this).val();
                if (selectedType === 'D') {
                    $('#txnIdGroup').hide();
                    // Could add date fields here if needed
                } else {
                    $('#txnIdGroup').show();
                }
            });
        });

        function SETTITLE(type) {
            $("[name='category']").val(type);
            let nsdlAction = "";
            
            switch(type) {
                case "A":
                    nsdlAction = "https://assisted-service.egov-nsdl.com/SpringBootFormHandling/newPanReq";
                    break;
                case "CR":
                    nsdlAction = "https://assisted-service.egov-nsdl.com/SpringBootFormHandling/crPanReq";
                    break;
                case "I":
                    nsdlAction = "https://assisted-service.egov-nsdl.com/SpringBootFormHandling/incompleteApplication";
                    break;
                case "B":
                    nsdlAction = "https://assisted-service.egov-nsdl.com/SpringBootFormHandling/checkEntityBalance";
                    break;
                case "T":
                    nsdlAction = "https://assisted-service.egov-nsdl.com/SpringBootFormHandling/checkTransactionStatus";
                    break;
            }
            
            $("#nsdlFormData").attr("action", nsdlAction);
        }
    </script>
@endpush