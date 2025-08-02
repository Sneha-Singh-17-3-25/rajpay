@extends('layouts.app')
@section('title', "Aadhar Pay Service")
@section('pagetitle', "Aadhar Pay Service")

@php
    $table = "yes";
    $search = "hide";
@endphp

@section('content')
    <div class="content">
        @if(!$agent || ($agent && $agent->status == "rejected"))
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Merchant Onboard For Aeps (Bank-1)</h4>
                        </div>
                        <form action="{{route('aepskyc')}}" method="post" id="fingkycForm" enctype="multipart/form-data">
                            <div class="panel-body"> 
                                {{ csrf_field() }}
                                <input type="hidden" name="gps_location">
                                <input type="hidden" name="transactionType" id="transactionType" value="useronboard">
                                <div class="row">
                                        <div class="form-group col-md-4">
                                        <label>Name</label>
                                        <input type="text" class="form-control" autocomplete="off" name="merchantName" placeholder="Enter Name" value="{{Auth::user()->name}}" readonly="" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Mobile</label>
                                        <input type="text" pattern="[0-9]*" maxlength="10" minlength="10" class="form-control" name="merchantPhoneNumber" autocomplete="off" placeholder="Enter Your Mobile" value="{{Auth::user()->mobile}}" readonly="" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Aadhaar Number</label>
                                        <input type="text" class="form-control" name="merchantAadhar" pattern="[0-9]*" maxlength="12" minlength="12" autocomplete="off" value="{{Auth::user()->aadharcard}}" placeholder="Enter Your Aadhaar" readonly="" value="" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Pancard Number</label>
                                        <input type="text" class="form-control" autocomplete="off" maxlength="10" minlength="10" name="userPan" readonly="" placeholder="Enter Your Pancard" value="{{Auth::user()->pancard}}" value=""  required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>City</label>
                                        <input type="text" class="form-control" autocomplete="off" name="merchantCityName"  value="{{Auth::user()->city}}" readonly="" placeholder="Enter Your City" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>State</label>
                                        <select name="merchantState" class="form-control select" required>
                                            <option value="">Select State</option>
                                            @foreach ($state as $state)
                                            <option value="{{$state->statecode}}">{{$state->state}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Address </label>
                                        <input type="text" readonly="" class="form-control" autocomplete="off" name="merchantAddress" placeholder="Enter Address" value="{{Auth::user()->address}}" required>
                                    </div>   
                                </div>
                                 <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Pin Code </label>
                                        <input type="text" readonly="" class="form-control" autocomplete="off" name="merchantPinCode" maxlength="6" minlength="6" pattern="[0-9]*"  value="{{Auth::user()->pincode}}" placeholder="Enter Merchant Pincode" required>
                                    </div>
                                </div>
                            
                                @if(isset($error))
                                   <div class="panel-footer text-center text-danger">
                                        Error - {{$error}}
                                    </div>
                                @endif
                            </div>

                            <div class="panel-footer text-center">
                                <button type="submit" class="btn bg-teal-400 btn-labeled legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Submitting"><b><i class=" icon-paperplane"></i></b> Proceed To Onboard</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="tabbable">
                        <div class="tab-content">
                            <div class="tab-pane active" id="recharge">
                                <div class="panel panel-body auth_cw_panel p-10">
                                    <div class="media no-margin stack-media-on-mobile">
                                        <div class="media-body">
                                            <h6 class="media-heading text-semibold auth_cw_text text-danger">Agent 2FA verification is required</h6>
                                        </div>

                                        <div class="media-right media-middle">
                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#authenticationModal" class="btn bg-material legitRipple authButton">Click here to verify</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <form action="{{route('aepspay')}}" method="POST" id="aepsTransactionForm" enctype="multipart/form-data">
                                        {{ csrf_field() }}

                                        <input type="hidden" name="transactionType" id="transactionType" value="M">
                                        <input type="hidden" name="aeps" value="">
                                        <input type="hidden" name="biodata" value="">
                                        <input type="hidden" name="gps_location">
                                        <input type="hidden" name="auth_cw" value="no">
                                        <input type="hidden" name="auth" value="{{$agent->aadhar_auth}}">

                                        <div class="panel panel-default no-margin">
                                            <div class="panel-heading">
                                                <h4 class="panel-title mytitle">Balance Enquiry</h4>
                                            </div>

                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Mobile Number :</label>
                                                            <input type="text"  class="form-control" name="mobileNumber" id="mobileNumber" maxlength="10"  autocomplete="off" placeholder="Enter mobile number" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Aadhar Number :</label>
                                                            <input type="text" class="form-control" name="adhaarNumber" id="adhaarNumber" maxlength="12" minlength="12" autocomplete="off" pattern="[0-9]*"  placeholder="Enter aadhar number" required="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Bank :</label>
                                                            <select name="iinno" id="iinno" class="form-control select" required="">
                                                                <option value="">Select Bank</option>         
                                                                @foreach ($aepsbanks as $bank)
                                                                    <option value="{{$bank->iinno}}" ftype="{{$bank->ftype}}">{{$bank->bankName}}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="label label-primary" onclick="bank('607094')" style="cursor: pointer;">SBI Bank</span>
                                                            <span class="label label-primary" onclick="bank('508534')" style="cursor: pointer;">ICICI Bank</span>
                                                            <span class="label label-primary" onclick="bank('607152')" style="cursor: pointer;">HDFC Bank</span>
                                                            <span class="label label-primary" onclick="bank('607027')" style="cursor: pointer;">PNB Bank</span>
                                                            <span class="label label-primary" onclick="bank('607161')" style="cursor: pointer;">Union Bank</span>
                                                            <span class="label label-primary" onclick="bank('606993')" style="cursor: pointer;">B.UP Bank</span>
                                                            <span class="label label-primary" onclick="bank('606985')" style="cursor: pointer;">BOB Bank</span>
                                                            <span class="label label-primary" onclick="bank('607264')" style="cursor: pointer;">CBI Bank</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Amount :</label>
                                                            <input type="text" class="form-control" name="transactionAmount" pattern="[0-9]*" id="amount" autocomplete="off" placeholder="Enter Amount">
                                                            <span class="label label-primary" onclick="amount('100')" style="cursor: pointer;">100</span>
                                                            <span class="label label-primary" onclick="amount('500')" style="cursor: pointer;">500</span>
                                                            <span class="label label-primary" onclick="amount('1000')" style="cursor: pointer;">1000</span>
                                                            <span class="label label-primary" onclick="amount('1500')" style="cursor: pointer;">1500</span>
                                                            <span class="label label-primary" onclick="amount('2000')" style="cursor: pointer;">2000</span>
                                                            <span class="label label-primary" onclick="amount('2500')" style="cursor: pointer;">2500</span>
                                                            <span class="label label-primary" onclick="amount('3000')" style="cursor: pointer;">3000</span>
                                                            <span class="label label-primary" onclick="amount('10000')" style="cursor: pointer;">10000</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="md-checkbox no-margin">
                                                            <input type="checkbox" name="consent" id="consent" required="">
                                                            <label for="consent">Accept Consent</label><br>
                                                            <span class="text-primary">I hereby confirm that Direct Benefits Transfer (DBT)/subsidies granted by the Government is received by me into the bank account. I carry out transactions through Business Correspondents of banks for withdrawal of money from my account/s and also for enquiry of balance under my account. I am submitting my Aadhaar details voluntarily to you for authentication purpose using my Aadhaar number/biometrics with UIDAI for the aforesaid purposes through AEPS.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel-footer text-center">
                                                @if($agent->status == "approved" && $agent->everify == "success")
                                                    <button type="submit" class="btn bg-slate-800 btn-lg btn-raised legitRipple .aepsButton" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Proceeding...">Scan & Submit</button>
                                                @elseif($agent->status == "approved" && $agent->everify == "pending")
                                                    <button type="button" data-toggle="modal" data-target="#kycModal" class="btn bg-slate-800 btn-lg btn-raised legitRipple">Click Here To Complete E-Kyc</button>
                                                @else
                                                    <h4 class="text-danger">Useronboard is {{$agent->status}}</h4>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div id="receipt" class="modal fade" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog">
      
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-slate">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Receipt</h4>
                </div>
                <div class="modal-body p-0">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <div class="clearfix">
                                <div class="pull-left">
                                    <h4>
                                        @if (Auth::user()->company->logo)
                                            <img src="{{asset('')}}public/{{Auth::user()->company->logo}}" class=" img-responsive" alt="" style="width: 220px;height: 40px;">
                                        @else
                                            {{Auth::user()->company->companyname}}
                                        @endif
                                    </h4>
                                </div>
                                <div class="pull-right">
                                    <h4><span class="receptTitle"></span> Invoice</h4>
                                </div>
                            </div>
                            <hr class="m-t-10 m-b-10">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-left m-t-10">
                                        <address class="m-b-10">
                                            <strong>{{Auth::user()->name}}</strong><br>
                                            {{Auth::user()->company->companyname}}<br>
                                            Phone : {{Auth::user()->mobile}}
                                        </address>
                                    </div>
                                    <div class="pull-right m-t-10">
                                        <address class="m-b-10">
                                            <strong>Date: </strong> <span class="date"></span><br>
                                            <strong>Order ID: </strong> <span class="txnid"></span><br>
                                            <strong>Status: </strong> <span class="status"></span><br>
                                            <strong>Remark: </strong> <span class="message"></span>
                                        </address>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <h4 class="title"></h4>
                                        <table class="table m-t-10 table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Bank</th>
                                                    <th>Aadhar Number</th>
                                                    <th>Ref No.</th>
                                                    <th class="cash">Amount</th>
                                                    <th>Account Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="biller"></td>
                                                    <td class="number"></td>
                                                    <td class="rrn"></td>
                                                    <td class="amount cash"></td>
                                                    <td class="balance"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="border-radius: 0px;">
                                <div class="col-md-6 col-md-offset-6">
                                    <h4 class="text-right cash">Withdrawal Amount : <span class="amount"></span></h4>
                                </div>
                            </div>
                            <hr>
                            <div class="hidden-print">
                                <div class="pull-right">
                                    <a href="javascript:void(0)"  id="print" class="btn btn-inverse waves-effect waves-light"><i class="fa fa-print"></i></a>
                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="kycModal" class="modal fade" role="dialog" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog">
        <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-slate">
                    <h4 class="modal-title pull-left">Complete E-Kyc</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{route('aepskyc')}}" method="post" id="kycForm">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="transactionType" value="useronboardotp">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Aadhar Number</label>
                                    <input type="text" class="form-control" name="merchantAadhar" placeholder="Enter aadhar no." required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Pancard Number</label>
                                    <input type="text" class="form-control" name="userPan" placeholder="Enter pan number" required="">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
                        <button class="btn bg-slate btn-raised legitRipple" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="authenticationModal" class="modal fade" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog">
      
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agent 2FA Verification is required</h4>
                </div>
                <div class="modal-body p-0">
                    <div class="panel panel-default">
                        <form action="{{route('iaepstransaction')}}" method="POST" id="aeps2FAForm" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <input type="hidden" name="transactionType" id="transactionType" value="AUO">
                            <input type="hidden" name="serviceType" value="AP">
                            <input type="hidden" name="biodata" value="">
                            <input type="hidden" name="gps_location">

                            <div class="panel panel-default no-margin">
                                <div class="panel-body">
                                    <h6 class="text-danger">Note : As per new rule of NPCI, Agent has to verify himself before every cash withdrawal transaction</h6>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="md-checkbox no-margin">
                                                <input type="checkbox" name="consents2" id="consents2" required="">
                                                <label for="consents2">Accept Consent</label><br>
                                                <span class="text-primary">I hereby confirm that Direct Benefits Transfer (DBT)/subsidies granted by the Government is received by me into the bank account. I carry out transactions through Business Correspondents of banks for withdrawal of money from my account/s and also for enquiry of balance under my account. I am submitting my Aadhaar details voluntarily to you for authentication purpose using my Aadhaar number/biometrics with UIDAI for the aforesaid purposes through AEPS.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-footer text-center">
                                    <button type="submit" class="btn bg-material btn-lg btn-raised legitRipple 2faButton" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Proceeding...">Scan & Authenticate</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link href="{{asset('')}}assets/css/jquery-confirm.min.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .error{
            color: red;
        }
        .has-detached-left .content-detached{
            margin-left: 261px;
        }
        .mycontent{
            background: #fff;
            margin: 20px;
            padding: 0px;
        }

        .label{
            margin: 2px;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('/assets/js/core/jQuery.print.js') }}"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/core/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/core/notify.min.js"></script>
    <script src="{{ asset('/assets/js/core/jquery.cookie.js') }}"></script>
    <script src="{{ asset('/assets/js/core/aadhaar_capture.js') }}"></script>

    <script type="text/javascript">
        var gif = '{{url('')}}/assets/images/capute.gif',loading = '{{url('')}}/assets/images/loading.gif';
        var ftype="2";
        
        var STOCK = {};
        STOCK.RD_SERVICES = [];
        STOCK.DEVICE_LIST = [];
        STOCK.PAST_TXNS = [];
        STOCK.BANKLIST = [];

        var FLAG = {};
        FLAG.RD_SERVICE_SCAN_DONE = false;

        $(document).ready(function () {
            @if($agent && $agent->status == "approved" && $agent->everify == "success" && $agent->auth == "need")
                $(window).load(function () {
                    $("#authenticationModal").modal();
                });
            @endif

            @if(isset($tab) && $tab == "ap")
                $("#MTab").trigger("click");
            @endif
            
            FETCH_RD_SERVICE_LIST();

            $('#print').click(function(){
                $('#receipt').find('.modal-body').print();
            });
            
            $('#statementprint').click(function(){
                $('#ministatement').find('.modal-body').print();
            });

            $('#iinno').on('change', function (e) {
                ftype = $(this).find('option:selected').attr('ftype');
            }); 
            
            $('#kycForm').submit(function (){
                SYSTEM.FORMSUBMIT($('#kycForm'), function(data){
                    if(!data.statusText){
                        if(data.statuscode == "TXN"){
                            var otpConfirm = $.confirm({
                                lazyOpen: true,
                                title: 'Otp Verification',
                                content: '' +
                                '<form action="javascript:void(0)" class="formName">' +
                                '<div class="form-group">' +
                                '<label>Otp</label>' +
                                '<input type="hidden" name="transactionType" value="useronboardvalidate"  class="form-control" required />' +
                                '<input type="hidden" name="primaryKeyId" value="'+data.primaryKeyId+'"  class="form-control" required />' +
                                '<input type="hidden" name="encodeFPTxnId" value="'+data.encodeFPTxnId+'"  class="form-control" required />' +
                                '<input type="text" placeholder="Enter Otp" name="otp" class="name form-control" required />' +
                                '</div>' +
                                '</form>',
                                buttons: {
                                    formSubmit: {
                                        text: 'Submit',
                                        btnClass: 'btn-blue kycButton',
                                        action: function () {
                                            var otp = this.$content.find('[name="otp"]').val();
                                            var primaryKeyId  = this.$content.find('[name="primaryKeyId"]').val();
                                            var encodeFPTxnId = this.$content.find('[name="encodeFPTxnId"]').val();
                                            if(!otp){
                                                $.alert({
                                                    title: 'Oops!',
                                                    content: 'Provide a valid otp',
                                                    type: 'red'
                                                });
                                                return false;
                                            }

                                            SYSTEM.AJAX("{{route('aepskyc')}}", "POST", { "transactionType" : "useronboardvalidate", "otp" : otp, "primaryKeyId" : primaryKeyId, 'encodeFPTxnId' : encodeFPTxnId}, function(data){
                                                if(!data.statusText){
                                                    if(data.statuscode == "TXN"){
                                                        otpConfirm.close();
                                                        aadhaar_capture("kyc");
                                                        var kycConfirm = $.confirm({
                                lazyOpen: true,
                                title: 'Biometric Verification',
                                content: '' +
                                '<form action="javascript:void(0)" class="formName">' +
                                '<div class="form-group">' +
                                '<label>Scan your finger</label>' +
                                '<input type="hidden" name="transactionType" value="useronboardekyc"  class="form-control" required />' +
                                '<input type="hidden" name="primaryKeyId" value="'+primaryKeyId+'"  class="form-control" required />' +
                                '<input type="hidden" name="encodeFPTxnId" value="'+encodeFPTxnId+'"  class="form-control" required />' +
                                '<input type="hidden" name="gps_location" value="'+localStorage.getItem("gps_location")+'"  class="form-control" required />' +
                                '<input type="hidden" name="biodata"  class="form-control" required />' +
                                `` +
                                '</div>' +
                                '</form>',
                                buttons: {
                                    scan: {
                                        text: 'Scan',
                                        btnClass: 'btn-blue',
                                        keys: ['enter', 'shift'],
                                        action: function(){
                                            aadhaar_capture(".kycButton");
                                            return false;
                                        }
                                    },
                                    formSubmit: {
                                        text: 'Submit',
                                        btnClass: 'btn-blue',
                                        action: function () {
                                            var biodata  = this.$content.find('[name="biodata"]').val();
                                            var primaryKeyId  = this.$content.find('[name="primaryKeyId"]').val();
                                            var encodeFPTxnId = this.$content.find('[name="encodeFPTxnId"]').val();
                                            if(!biodata){
                                                $.alert({
                                                    title: 'Oops!',
                                                    content: 'Please scan your finger',
                                                    type: 'red'
                                                });
                                                return false;
                                            }
                                            
                                            SYSTEM.AJAX("{{route('aepskyc')}}", "POST", { "transactionType" : "useronboardekyc", "biodata" : biodata, "primaryKeyId" : primaryKeyId, 'encodeFPTxnId' : encodeFPTxnId}, function(data){
                                                if(!data.statusText){
                                                    if(data.statuscode == "TXN"){
                                                        kycConfirm.close();
                                                        $.alert({
                                                            icon: 'fa fa-check',
                                                            theme: 'modern',
                                                            animation: 'scale',
                                                            type: 'green',
                                                            title : "Success",
                                                            content : data.message,
                                                            buttons: {
                                                                somethingElse: {
                                                                    text: 'Ok',
                                                                    btnClass: 'btn-primary',
                                                                    action: function(){
                                                                        location.reload();
                                                                    }
                                                                }
                                                            }
                                                        });
                                                    }else{
                                                        $('[name="biodata"]').val("");
                                                        if(data.status == 400){
                                                            $.alert({
                                                                title: 'Oops!',
                                                                content: data.responseJSON.message,
                                                                type: 'red'
                                                            });
                                                        }else{
                                                            if(data.message){
                                                                $.alert({
                                                                    title: 'Oops!',
                                                                    content: data.message,
                                                                    type: 'red'
                                                                });
                                                            }else{
                                                                $.alert({
                                                                    title: 'Oops!',
                                                                    content: data.statusText,
                                                                    type: 'red'
                                                                });
                                                            }
                                                        }
                                                    }
                                                }
                                                    
                                            }, $(".formName"));
                                                        
                                            return false;
                                        }
                                    },
                                    cancel: function () {
                                    }
                                }
                    });  
                            kycConfirm.open();
                                                    }else{
                                                        if(data.status == 400){
                                                            $.alert({
                                                                title: 'Oops!',
                                                                content: data.responseJSON.message,
                                                                type: 'red'
                                                            });
                                                        }else{
                                                            if(data.message){
                                                                $.alert({
                                                                    title: 'Oops!',
                                                                    content: data.message,
                                                                    type: 'red'
                                                                });
                                                            }else{
                                                                $.alert({
                                                                    title: 'Oops!',
                                                                    content: data.statusText,
                                                                    type: 'red'
                                                                });
                                                            }
                                                        }
                                                    }
                                                }
                                            }, $('.jconfirm-box-container'), "Please Wait");
                                            return false;
                                        }
                                    },
                                    cancel: function () {
                                    },
                                }
                    });  
                            otpConfirm.open();
                        }else{
                            SYSTEM.SHOWERROR(data, $(this));
                        }
                    }else{
                        SYSTEM.SHOWERROR(data, $(this));
                    }
                });

                return false;
            });
                        
            $( "#fingkycForm" ).validate({
                rules: {
                    merchantName: {
                        required: true
                    },
                    merchantAddress: {
                        required: true
                    },
                    merchantState: {
                        required: true
                    },
                    merchantPhoneNumber: {
                        required: true,
                        number: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    merchantAadhar: {
                        required: true,
                        number: true,
                        minlength: 12,
                        maxlength: 12
                    },
                    userPan: {
                        required: true
                    },
                    merchantPinCode: {
                        required: true,
                        number: true,
                        minlength: 6,
                        maxlength: 6
                    }
                },
                messages: {
                    merchantName: {
                        required: "Please enter value",
                    },
                    merchantAddress: {
                        required: "Please enter value",
                    },
                    merchantState: {
                        required: "Please enter value",
                    },
                    merchantPhoneNumber: {
                        required: "Please enter value",
                        nnumber: "Aadhar number should be numeric",
                        minlength: "Your aadhar number must be 10 digit",
                        maxlength: "Your aadhar number must be 10 digit"
                    },
                    merchantAadhar: {
                        required: "Please enter value",
                        nnumber: "Aadhar number should be numeric",
                        minlength: "Your aadhar number must be 12 digit",
                        maxlength: "Your aadhar number must be 12 digit"
                    },
                    userPan: {
                        required: "Please enter value",
                    },
                    merchantPinCode: {
                        required: "Please enter value",
                        nnumber: "Aadhar number should be numeric",
                        minlength: "Your aadhar number must be 6 digit",
                        maxlength: "Your aadhar number must be 6 digit"
                    }
                },
                errorElement: "p",
                errorPlacement: function ( error, element ) {
                    if ( element.prop( "name" ) === "iinno" || element.prop( "name" ) === "bankName2" ) {
                        error.insertAfter( element.closest( "div" ).find(".select2-container") );
                    } else {
                        error.insertAfter( element );
                    }
                },
                submitHandler: function (form) {
                    var form = $('#fingkycForm');
                    $("[name='gps_location']").val(localStorage.getItem("gps_location"));
                    
                    form.ajaxSubmit({
                        dataType:'json',
                        beforeSubmit:function(){
                            form.find('button[type="submit"]').button('loading');
                        },
                        success:function(data){
                            form.find('button[type="submit"]').button('reset');
                            if(data.statuscode == "TXN"){
                                swal({
                                    title:'Suceess', 
                                    text : data.message, 
                                    type : 'success',
                                    onClose: () => {
                                        window.location.reload();
                                    }
                                });
                            }else{
                                swal({
                                    title:'Failed', 
                                    text : data.message, 
                                    type : 'error'
                                });
                            }
                        },
                        error: function(errors) {
                            form.find('[name="biodata"]').val('');
                            showError(errors, form);
                        }
                    });
                }
            });

            $( "#aepsTransactionForm" ).validate({
                rules: {
                    mobileNumber: {
                        required: true,
                        minlength: 10,
                        number : true,
                        maxlength: 11
                    },
                    adhaarNumber: {
                        required: true,
                        number: true,
                        minlength: 12,
                        maxlength: 12
                    },
                    iinno: 'required',
                    device: 'required'
                },
                messages: {
                    mobileNumber: {
                        required: "Please enter mobile number",
                        number: "Mobile number should be numeric",
                        minlength: "Your mobile number must be 10 digit",
                        maxlength: "Your mobile number must be 10 digit"
                    },
                    adhaarNumber: {
                        required: "Please enter aadhar number",
                        number: "Aadhar number should be numeric",
                        minlength: "Your aadhar number must be 12 digit",
                        maxlength: "Your aadhar number must be 12 digit"
                    },
                    transactionAmount: {
                        required: "Please enter amount",
                        number: "Transaction amount should be numeric",
                        min : "Minimum transaction amount should be 10"
                    },
                    iinno : "Please select bank",
                    device : "Please select device"
                },
                errorElement: "p",
                errorPlacement: function ( error, element ) {
                    if ( element.prop( "name" ) === "iinno" ) {
                        error.insertAfter( element.closest( ".form-group" ).find("span.select2"));
                    } else {
                        error.insertAfter( element );
                    }
                },
                submitHandler: function (element) {
                    var form = $("#aepsTransactionForm" );
                    var scan = form.find('[name="biodata"]').val();
                    var lat  = "yes";
                    $("[name='gps_location']").val(localStorage.getItem("gps_location"));

                    var transactionType = form.find('[name="transactionType"]').val();
                    var auth_cw = form.find('[name="auth_cw"]').val();
                    var auth = form.find('[name="auth"]').val();
                    var lat  = "yes";
                    $("[name='gps_location']").val(localStorage.getItem("gps_location"));

                    if(auth == "need" || auth_cw == "no"){
                        $("#authenticationModal").modal();
                    }else{
                        if(scan != ''){
                            if(lat != ''){
                                SYSTEM.FORMSUBMIT(form, function(data){
                                    $('[name="biodata"]').val('');
                                    if(!data.statusText){
                                        form.find('button[type="submit"]').button('reset');
                                        if(data.statuscode == "TXN" || data.statuscode == "TUP" || data.statuscode == "TXF"){
                                            form[0].reset();
                                            form.find('select').select2().val(null).trigger('change');
                                            getbalance();
                                            form.find('button[type="submit"]').button('reset');

                                            if(data.statuscode == "TXF"){
                                                form.find("[name='auth_cw']").val("no");
                                                $(".auth_cw_text").text("Agent 2FA verification is required");
                                                $(".auth_cw_text").removeClass("text-success").addClass("text-danger");
                                                $(".authButton").show();
                                            }
                                            $(".cash").show();
                                            $('#receipt').find('.date').text(data.date);
                                            $('#receipt').find('.amount').text(data.amount);
                                            $('#receipt').find('.rrn').text(data.rrn);
                                            $('#receipt').find('.number').text(data.number);
                                            $('#receipt').find('.txnid').text(data.txnid);
                                            $('#receipt').find('.status').text(data.status);
                                            $('#receipt').find('.biller').text(data.biller);
                                            $('#receipt').find('.balance').text(data.balance);
                                            $('#receipt').find('.title').text(data.provider);
                                            $('#receipt').find('.message').text(data.message);
                                            $('#receipt').modal();
                                        }else{
                                            swal({
                                                title:'Failed', 
                                                text : data.message, 
                                                type : 'error'
                                            });
                                        }
                                    }else{
                                        SYSTEM.SHOWERROR(data, form);
                                    }
                                });
                            }
                        }else{
                            aadhaar_capture(".aepsButton");
                        }
                    }
                }
            });

            $( "#aeps2FAForm" ).validate({
                errorElement: "p",
                errorPlacement: function ( error, element ) {
                    if ( element.prop( "name" ) === "bankId" ) {
                        error.insertAfter( element.closest( ".form-group" ).find("span.select2"));
                    } else if ( element.prop( "name" ) === "consents" ) {
                        error.insertAfter( element.closest( ".md-checkbox" ));
                    } else {
                        error.insertAfter( element );
                    }
                },
                submitHandler: function (element) {
                    var form = $("#aeps2FAForm" );
                    var scan = form.find('[name="biodata"]').val();
                    var auth = $('[name="auth"]').val();
                    var auth_cw = $('[name="auth_cw"]').val();
                    var lat  = "yes";
                    $("[name='gps_location']").val(localStorage.getItem("gps_location"));

                    if(scan != ''){
                        if(lat != ''){
                            form.ajaxSubmit({
                                dataType:'json',
                                beforeSubmit:function(){
                                    form.find('button[type="submit"]').button('loading');
                                },
                                success:function(data){
                                    $('[name="biodata"]').val('');
                                    form.find('button[type="submit"]').button('reset');
                                    form.find('[name="biodata"]').val('');
                                    form.closest(".modal").modal("hide");

                                    if(data.statuscode == "TXN"){
                                        if(auth == "need"){
                                            $("[name='auth']").val("yes");
                                        }else{
                                            $("[name='auth_cw']").val("yes");
                                            $(".auth_cw_text").text("Agent 2FA verification is completed, proceed to cash withdrawal");
                                            $(".auth_cw_text").removeClass("text-danger").addClass("text-success");
                                            $(".authButton").hide();
                                        }

                                        swal({
                                            title: "Success",
                                            text:  data.message,
                                            type: 'success',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#456b8c',
                                            confirmButtonText: 'Ok',
                                            cancelButtonText: 'Close',
                                            allowOutsideClick : false,
                                            allowEscapeKey : false,
                                            allowEnterKey : false
                                        });
                                        form.closest(".modal").modal("hide");
                                    }else{
                                        form.find('[name="biodata"]').val('');
                                        swal({
                                            title:'Failed', 
                                            text : data.message, 
                                            type : 'error'
                                        });
                                    }
                                },
                                error: function(errors) {
                                    $('[name="biodata"]').val('');
                                    showError(errors, form);
                                }
                            });
                        }
                    }else{
                        aadhaar_capture(".2faButton");
                    }
                }
            });
        });
        
        function FETCH_RD_SERVICE_LIST(forced = false) {
            var rd_service_cookie = $.cookie('RDSLS');
            if(rd_service_cookie) {
                FLAG.RD_SERVICE_SCAN_DONE = true;
                STOCK.RD_SERVICES = JSON.parse(rd_service_cookie);
            }
            
            if(forced || !rd_service_cookie || (rd_service_cookie && STOCK.RD_SERVICES.length == 0) ) {
                STOCK.RD_SERVICES = [];
                FLAG.RD_SERVICE_SCAN_DONE = false;
                scan_all_rd_services();
            }
        }
        
        function bank(iinno, type){
            ftype = type;
            $('[name="iinno"]').val(iinno).trigger('change');
        }

        function amount(amount){
            $('[name="transactionAmount"]').val(amount);
        };
    </script>
@endpush