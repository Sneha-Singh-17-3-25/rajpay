@extends('layouts.app')
@section('title', "Money Transfer")
@section('pagetitle', "Money Transfer")
@php
    $table = "yes";
    $search = "hide";
@endphp

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Money Transfer (Bank-2)</h4>
                    </div>
                    <form id="serachForm" action="{{route('dmt1transaction')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="type" value="SenderDetails">
                        <input type="hidden" id="rname">
                        <input type="hidden" id="rlimit">
                        <div class="panel-body">
                            <div class="form-group no-margin-bottom">
                                <label>Mobile Number</label>
                                <input type="number" step="any" name="mobile" class="form-control" placeholder="Enter Mobile Number" required="">
                            </div>
                        </div>
                        <div class="panel-footer text-center">
                            <button type="submit" class="btn btn-primary btn-labeled btn-rounded legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Searching"><b><i class="icon-search4"></i></b> Search</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="panel userdetails" style="display:none">
                    <div class="panel-heading">
                        <h4 class="panel-title name"></h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h6 class="text-semibold no-margin-top mobile"></h6>
                                <ul class="list list-unstyled">
                                    <li>Used Limit : <i class="fa fa-inr"></i> <span class="usedlimit"></span></li>
                                </ul>
                            </div>

                            <div class="col-sm-6">
                                <h6 class="text-semibold text-right no-margin-top">Total Limit : <i class="fa fa-inr"></i> <span class="totallimit"></span></h6>
                                <ul class="list list-unstyled text-right">
                                    <li>Remain Limit: <i class="fa fa-inr"></i> <span class="text-semibold remainlimit"></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <hr class="no-margin">
                    <div class="panel-footer text-center alpha-grey">
                        <a href="#" data-toggle="modal" data-target="#beneficiaryModal" class="btn btn-primary legitRipple"><i class="icon-plus22 position-left"></i>New Beneficiary</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Beneficiary List</h4>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-bordered transaction" cellspacing="0" width="100%">
                                <thead>
                                    <th width="350px">Name</th>
                                    <th width="450px">Account Details</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="registrationModal" class="modal fade" role="dialog" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header btn-primary">
                    <h4 class="modal-title pull-left">Member Registration</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{route('dmt1transaction')}}" method="post" id="registrationForm">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <input type="hidden" name="type" value="SenderRegister">
                        <input type="hidden" name="mobile">
                        <div  class="row">
                            <div class="form-group col-md-6">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="fname" required="" placeholder="Enter last name">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Last Name</label>
                                <input type="text" class="form-control" name="lname" required="" placeholder="Enter first name">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Pincode</label>
                                <input type="text" class="form-control" name="pincode" required="" placeholder="Enter Pincode">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-raised legitRipple" type="button" data-dismiss="modal" aria-hidden="true">Close</button>
                        <button class="btn btn-primary btn-raised legitRipple" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="otpModal" class="modal fade" role="dialog" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header btn-primary">
                    <h4 class="modal-title pull-left">Otp Verification</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{route('dmt1transaction')}}" method="post" id="otpForm">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <input type="hidden" name="type" value="VerifySender">
                        <input type="hidden" name="mobile">
                        <input type="hidden" name="regdata">
                        <div class="form-group">
                            <label>OTP</label><a href="javascript:void(0)" id="btn-resend-otp" class="pull-right" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Sending" type="resendOtpVerification"><i class='fa fa-paper-plane'></i> Resend Otp</a>
                            <input type="text" class="form-control" name="otp" placeholder="enter otp" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
                        <button class="btn btn-primary btn-raised legitRipple" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="beneficiaryModal" class="modal fade" role="dialog" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header btn-primary">
                    <h4 class="modal-title pull-left">Beneficiary Details Please</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{route('dmt1transaction')}}" method="post" id="beneficiaryForm">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="type" value="RegRecipient">
                        <input type="hidden" name="mobile">
                        <input type="hidden" name="name">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bank Name : </label>
                                    <select id="bank" name="benebank" class="form-control select">
                                        <option value="">Select Bank</option>
                                        @foreach($banks as $bank)
                                            <option value="{{$bank->bankcode}}" ifsc="{{$bank->masterifsc}}">{{$bank->bankname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">IFSC Code:</label>
                                    <input type="text" class="form-control" name="beneifsc" placeholder="Bank ifsc code" required="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Bank Account No.:</label>
                                    <input type="text" class="form-control" id="account" name="beneaccount" placeholder="Enter account no." required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Beneficiary Mobile:</label>
                                    <input type="text" class="form-control" name="benemobile" placeholder="Enter name" required="">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Beneficiary Name:</label>
                                    <input type="text" class="form-control" name="benename" placeholder="Enter name" required="">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary legitRipple" type="button" id="getBenename">Get Name</button>
                        <button type="button" class="btn btn-primary btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
                        <button class="btn btn-primary btn-raised legitRipple" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="transferModal" class="modal fade right" role="dialog" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog modal-sm" st>
            <div class="modal-content">
                <div class="modal-header btn-primary">
                    <h4 class="modal-title">Transfer Money</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{route('dmt1pay')}}" method="post" id="transferForm">
                    {{ csrf_field() }}
                    <input type="hidden" name="type" value="FundTransfer">
                    <input type="hidden" name="mobile" value="9971702308">
                    <input type="hidden" name="name" value="himanshu">
                    <input type="hidden" name="benename" value="himanshu">
                    <input type="hidden" name="beneaccount" value="311401000181">
                    <input type="hidden" name="benebank" value="Icici Bank">
                    <input type="hidden" name="beneifsc" value="IICICI">
                    <input type="hidden" name="rec_id" value="1">
                    <input type="hidden" name="convfee" value="90">
                    <input type="hidden" name="pin" value="230421">
                    <div class="modal-body" style="padding-bottom:20px">
                        <div class="panel border-left-lg border-left-success invoice-grid timeline-content">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h6 class="text-semibold no-margin-top ">Name - <span class="benename"></span></h6>
                                    </div>
                                    <div class="col-sm-12">
                                        <h6 class="text-semibold no-margin-top ">Bank - <span class="benebank"></span></h6>
                                    </div>
                                    <div class="col-sm-12">
                                        <h6 class="text-semibold no-margin-top">Acc - <span class="beneaccount"></span></h6>
                                    </div>
                                    <div class="col-sm-12">
                                        <h6 class="text-semibold no-margin-top ">Ifsc - <span class="beneifsc"></span></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Transfer Mode</label>
                                <select name="mode" class="form-control select">
                                    <option value="IMPS">IMPS</option>
                                    <option value="NEFT">NEFT</option>
                                </select>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="number" class="form-control" placeholder="Enter amount to be transfer" name="amount" step="any" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align:left">
                        <button class="btn btn-primary btn-raised legitRipple" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Transfer</button>
                        <button type="button" class="btn btn-primary btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
                    </div>
                </form>
            </div>
        
        </div>
    </div>

    <div id="receipt" class="modal fade" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header btn-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Receipt</h4>
                </div>
                <div class="modal-body">
                    <ul class="list-group transactionData p-0">
                    </ul>
                    <div id="receptTable">
                        <div class="clearfix">
                            <div class="pull-left">
                                <h4>
                                    @if (Auth::user()->company->logo)
                                        <img src="{{session("logo") ?? ""}}" class=" img-responsive" alt="" style="width: 220px;height: 40px;">
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
                                        <strong>Agent :</strong> <span>{{Auth::user()->name}}</span><br>
                                        <strong>Shop Name :</strong> <span>{{Auth::user()->shopname}}</span><br>
                                        <strong>Phone :</strong> <span>{{Auth::user()->mobile}}</span>
                                        <strong>City :</strong> <span>{{Auth::user()->city}}</span>
                                    </address>
                                </div>
                                <div class="pull-right m-t-10">
                                    <address class="m-b-10">
                                        <strong>Date : </strong> <span class="date">{{date('d M y - h:i A')}}</span><br>
                                        <strong>Name : </strong> <span class="benename"></span><br>
                                        <strong>Account : </strong> <span class="beneaccount"></span><br>
                                        <strong>Bank : </strong> <span class="benebank"></span>
                                        <strong>Remitter : </strong> <span class="mobile"></span>
                                    </address>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <h4>Transaction Details :</h4>
                                    <table class="table m-t-10">
                                        <thead>
                                            <tr>
                                                <th>Order Id</th>
                                                <th>Amount</th>
                                                <th>UTR No.</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="id"></td>
                                                <td class="amount"></td>
                                                <td class="refno"></td>
                                                <td class="status"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="border-radius: 0px;">
                            <div class="col-md-6 col-md-offset-6">
                                <h5 class="text-right">Transfer Amount : <span class="samount"></span></h5>
                            </div>
                        </div>
                        <p>* As per RBI guideline, maximum charges allowed is 2%.</p>
                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn btn-primary btn-raised legitRipple" type="button" id="print"><i class="fa fa-print"></i></button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script src="{{ asset('/assets/js/core/jQuery.print.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("[name='mobile']").keyup(function(){
            $( "#serachForm" ).submit();
        });

        $('#print').click(function(){
            $('#receptTable').print();
        });

        $('#bank').on('change', function (e) {
            $('input[name="beneifsc"]').val($(this).find('option:selected').attr('ifsc'));
        }); 

        $( "#serachForm" ).validate({
            rules: {
                mobile: {
                    required: true,
                    number : true,
                    minlength:10,
                    maxlength:10
                },
            },
            messages: {
                mobile: {
                    required: "Please enter mobile number",
                    number: "Mobile number should be numeric",
                    minlenght: "Mobile number length should be 10 digit",
                    maxlenght: "Mobile number length should be 10 digit",
                }
            },
            errorElement: "p",
            errorPlacement: function ( error, element ) {
                if ( element.prop("tagName").toLowerCase() === "select" ) {
                    error.insertAfter( element.closest( ".form-group" ).find(".select2") );
                } else {
                    error.insertAfter( element );
                }
            },
            submitHandler: function () {
                var form = $('#serachForm');
                var mobile = form.find('[name="mobile"]').val();
                form.ajaxSubmit({
                    dataType:'json',
                    beforeSubmit:function() {
                        form.find('button[type="submit"]').button('loading');
                    },
                    success:function(data) {
                        form.find('button[type="submit"]').button('reset');
                        if (data.statuscode === "TXN") {
                            setVerifyData(data);
                            setBeneData(mobile);
                        } else if(data.statuscode === "RNF") {
                            $('.userdetails').hide();
                            $('#registrationModal').find('[name="mobile"]').val(mobile);
                            $('#registrationModal').modal();
                        } else {
                            notify(data.message, 'danger', "inline",form);
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });

        $( "#otpForm" ).validate({
            rules: {
                otp: {
                    required: true,
                    number : true,
                    minlength:6,
                    maxlength:6
                },
            },
            messages: {
                otp: {
                    required: "Please enter otp number",
                    number: "Otp number should be numeric",
                    minlenght: "OTP length should be 6 digit",
                    maxlenght: "OTP length should be 6 digit",
                }
            },
            errorElement: "p",
            errorPlacement: function ( error, element ) {
                if ( element.prop("tagName").toLowerCase() === "select" ) {
                    error.insertAfter( element.closest( ".form-group" ).find(".select2") );
                } else {
                    error.insertAfter( element );
                }
            },
            submitHandler: function () {
                var form = $('#otpForm');
                form.ajaxSubmit({
                    dataType:'json',
                    beforeSubmit:function(){
                        form.find('button[type="submit"]').button('loading');
                    },
                    success:function(data){
                        form.find('button[type="submit"]').button('reset');
                        if(data.statuscode == "TXN"){
                            var type = form.find('[name="type"]').val();
                            form[0].reset();
                            $('#otpModal').find('[name="mobile"]').val("");
                            $('#otpModal').find('[name="regdata"]').val("");
                            $('#otpModal').modal('hide');
                            if(type == "registrationValidate"){
                                notify('Member successfully registered.', 'success');
                            }else{
                                notify('Beneficiary Successfully verified.', 'success');
                            }
                            $( "#serachForm" ).submit();
                        }else{
                            notify(data.message, 'danger', "inline",form);
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });

        $( "#registrationForm" ).validate({
            rules: {
                name: {
                    required: true,
                },
                surname: {
                    required: true,
                },
                pincode: {
                    required: true,
                    number : true,
                    minlength:6,
                    maxlength:6
                },
            },
            messages: {
                name: {
                    required: "Please enter firstname",
                },
                surname: {
                    required: "Please enter surname",
                },
                pincode: {
                    required: "Please enter pincode",
                    number: "Pincode should be numeric",
                    minlenght: "Pincode length should be 6 digit",
                    maxlenght: "Pincode length should be 6 digit",
                }
            },
            errorElement: "p",
            errorPlacement: function ( error, element ) {
                if ( element.prop("tagName").toLowerCase() === "select" ) {
                    error.insertAfter( element.closest( ".form-group" ).find(".select2") );
                } else {
                    error.insertAfter( element );
                }
            },
            submitHandler: function () {
                var form = $('#registrationForm');
                var type = form.find('input[name="type"]').val();
                form.ajaxSubmit({
                    dataType:'json',
                    beforeSubmit:function(){
                        form.find('button[type="submit"]').button('loading');
                    },
                    success:function(data){
                        form.find('button[type="submit"]').button('reset');
                        if(data.statuscode == "TXN"){
                            form.closest('.modal').modal('hide');
                            $( "#serachForm" ).submit();
                        } else if(data.statuscode === "TXNOTP") {
                            var mobile = form.find('[name="mobile"]').val();
                            $('#otpModal').find('[name="mobile"]').val(mobile);
                            $('[name="regdata"]').val(data.message.additionalRegData);
                            form.closest('.modal').modal('hide');
                            //$("#btn-resend-otp").trigger("click");
                            $('#otpModal').modal();
                        } else{
                            notify(data.message, 'danger', "inline",form);
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });

        $('#getBenename').click(function(){
            var mobile = $(this).closest('form').find("[name='mobile']").val();
            var benebank = $(this).closest('form').find("[name='benebank']").val();
            var beneaccount = $(this).closest('form').find("[name='beneaccount']").val();
            var beneifsc = $(this).closest('form').find("[name='beneifsc']").val();
            var benename = $(this).closest('form').find("[name='benename']").val();
            var benemobile = $(this).closest('form').find("[name='benemobile']").val();

            if (mobile != '' || benebank != '' || beneaccount != '' || beneifsc != '' || benename != '') {
                getName(mobile, benebank, beneaccount, beneifsc, benename, benemobile, 'add');
            }
        });

        $("#btn-resend-otp").click( function() {
            var mobile = $(this).closest('form').find('input[name="mobile"]').val();
            var form   = $(this).closest('form');
            $.ajax({
                url: "{{route('dmt1transaction')}}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType:'json',
                data: {'mobile':mobile, 'type':"ResendSenderOtp"},
                beforeSend:function(){
                    swal({
                        title: 'Wait!',
                        text: 'We are processing your request.',
                        allowOutsideClick: () => !swal.isLoading(),
                        onOpen: () => {
                            swal.showLoading()
                        }
                    });
                },
                success: function(data){
                    swal.close();
                    if(data.statuscode == "TXN"){
                        $('[name="regdata"]').val(data.message.additionalRegData);
                        notify(data.status, 'success', "inline",form);
                    }else{
                        notify(data.status, 'danger', "inline",form);
                    }
                },
                error: function(error){
                    swal.close();
                    notify("Something went wrong", 'danger', "inline",form);
                }
            });
        });

        $( "#beneficiaryForm" ).validate({
            rules: {
                ifsc: {
                    required: true,
                },
                account: {
                    required: true,
                },
                account_confirmation: {
                    required: true,
                    equalTo : '#account'
                },
                name: {
                    required: true,
                }
            },
            messages: {
                ifsc: {
                    required: "Bank ifsc code is required",
                },
                account: {
                    required: "Beneficiary bank account number is required",
                },
                account_confirmation: {
                    required: "Account number confirmation is required",
                    equalTo : 'Account confirmation is same as account number'
                },
                name: {
                    required: "Beneficiary account name is required",
                }
            },
            errorElement: "p",
            errorPlacement: function ( error, element ) {
                if ( element.prop("tagName").toLowerCase() === "select" ) {
                    error.insertAfter( element.closest( ".form-group" ).find(".select2") );
                } else {
                    error.insertAfter( element );
                }
            },
            submitHandler: function () {
                var form = $('#beneficiaryForm');
                form.ajaxSubmit({
                    dataType:'json',
                    beforeSubmit:function(){
                        form.find('button[type="submit"]').button('loading');
                    },
                    success:function(data){
                        form.find('button[type="submit"]').button('reset');
                        if(data.statuscode == "TXN"){
                            form[0].reset();
                            form.find('select').select2().val(null).trigger('change');
                            form.closest('.modal').modal('hide');
                            notify('Beneficiary Successfully Added.', 'success');
                            $( "#serachForm" ).submit();
                        }else{
                            notify(data.message, 'danger', "inline", form);
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });

        $( "#transferForm" ).validate({
            rules: {
                amount: {
                    required : true,
                    number   : true,
                    min      : 10
                }
            },
            messages: {
                amount: {
                    required : "Please enter amount",
                    number   : "Amount should be numeric",
                    min      : "Amount value should be greater than 100"
                }
            },
            errorElement: "p",
            errorPlacement: function ( error, element ) {
                if ( element.prop("tagName").toLowerCase() === "select" ) {
                    error.insertAfter( element.closest( ".form-group" ).find(".select2") );
                } else {
                    error.insertAfter( element );
                }
            },
            submitHandler: function () {
                var form = $('#transferForm');
                var amount      = form.find('[name="amount"]').val();
                var benename    = form.find('[name="benename"]').val();
                var beneaccount = form.find('[name="beneaccount"]').val();
                var benebank = form.find('[name="benebank"]').val();
                var bankname = form.find('.benebank').text();
                var beneifsc = form.find('[name="beneifsc"]').val();
                var name     = form.find('[name="name"]').val();
                var mobile   = form.find('[name="mobile"]').val();
                var beneid   = form.find('[name="rec_id"]').val();
                var mode     = form.find('[name="mode"]').val();

                SYSTEM.tpinVerify(function(pin){
                    form.find('[name="pin"]').val(pin);
                    SYSTEM.FORMSUBMIT(form, function(data){
                        form.find('[name="tpin"]').val("");
                        if (!data.statusText) {
                            getbalance();
                            if(data.statuscode == "TXN"){
                                var samount = 0;
                                var out ="";
                                var tbody = '';

                                if(data.data){
                                    $.each(data.data , function(index, val){
                                        if(val.status == "success" || val.status == "pending"){
                                            samount += parseFloat(val.amount);
                                        }

                                        tbody += `
                                            <tr>
                                                <td>`+val.txnid+`</td>
                                                <td>`+val.amount+`</td>
                                                <td>`+val.refno+`</td>
                                                <td>`+val.status+`</td>
                                            </tr>        
                                        `;
                                    });
                                }else{
                                    tbody += `
                                        <tr>
                                            <td colspan="4" class="text-danger b">Duplicate Transaction Found, Check Transaction History Before Re-Initiate Transaction</td>
                                        </tr>        
                                    `;
                                }
                                
                                $('#receptTable').fadeIn('400');                            
                                $('.benename').text(benename);
                                $('.mobile').text(mobile);
                                $('.beneaccount').text(beneaccount);
                                $('.benebank').text(bankname);
                                $('#receptTable').find('tbody').html(tbody);
                                $('.samount').text(parseFloat(samount));
                                $('#receipt').modal();
                            }else{
                                $.alert({
                                    title: 'Oops!',
                                    content: data.message,
                                    type: 'red'
                                });
                            }
                        } else {
                            SYSTEM.SHOWERROR(data, form);
                        }
                    });
                });
            }
        });
    });

    function setVerifyData(data)
    {
        $('.transaction').find('tbody').html('');
        $('.name').text(data.data.senderName);
        $('.mobile').text(data.data.senderMobileNumber);
        var total = 0;
        
        // $.each(data.data.availableLimitBreakup.amtValue, function(index, val) {
        //     console.log(val)
        //     total = total+ parseInt(val);
        // });

        $('.totallimit').text( parseInt(data.data.totalLimit));
        $('.usedlimit').text( parseInt(data.data.usedLimit) );
        $('.remainlimit').text( parseInt(data.data.totalLimit) - parseInt(data.data.usedLimit));
        $('[name="mobile"]').val(data.data.senderMobileNumber);
        $('[name="name"]').val(data.data.senderName);
        $('#rname').val(data.data.senderName);
        $('#rlimit').val(parseInt(data.data.availableLimit));
        $('.userdetails').fadeIn('400');
    }

    function setBeneData(mobile)
    {
        $.ajax({
            url: "{{ route('dmt1transaction') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType:'json',
            data: {'mobile': mobile, 'type': "AllRecipient"},
            success: function(data) {
                var out = '';
                if (data.message.dmtRecipient) {
                    if(data.message.dmtRecipient.length > 0) {
                        $.each(data.message.dmtRecipient, function(index, beneficiary) {
                            out += `<tr>
                                        <td>`+beneficiary.recipientName+`</td>
                                        <td>`+beneficiary.bankAccountNumber+` <br> (`+beneficiary.ifsc+`)<br> ( `+beneficiary.bankName+` )</td>
                                        <td>`;
                                        //if (beneficiary.isVerified == "Y") {
                                            out +=`<button class="btn btn-primary btn-xs legitRipple" onclick="sendMoney('`+mobile+`', '`+beneficiary.recipientId+`', '`+beneficiary.recipientName+`', '`+beneficiary.bankAccountNumber+`', '`+beneficiary.bankName+`', '`+beneficiary.ifsc+`')"><i class="fa fa-paper-plane"></i> Send</button>`;
                                        //} else {
                                        //    out +=`<button class="btn btn-info btn-xs legitRipple" onclick="otpVerify('`+mobile+`', '`+beneficiary.bankCode+`', '`+beneficiary.bankAccountNumber+`', '`+beneficiary.ifsc+`', '`+beneficiary.recipientName+`')"><i class="fa fa-check"></i> Verify</button>`;
                                        //}
                                out +=`</td>
                                    </tr>`;
                        });    
                    } else {
                        var beneficiary = data.message.dmtRecipient;
                        out += `<tr>
                                    <td>`+beneficiary.recipientName+`</td>
                                    <td>`+beneficiary.bankAccountNumber+` <br> (`+beneficiary.ifsc+`)<br> ( `+beneficiary.bankName+` )</td>
                                    <td>`;
                                    //if (beneficiary.isVerified == "Y") {
                                        out +=`<button class="btn btn-primary btn-xs legitRipple" onclick="sendMoney('`+mobile+`', '`+beneficiary.recipientId+`', '`+beneficiary.recipientName+`', '`+beneficiary.bankAccountNumber+`', '`+beneficiary.bankName+`', '`+beneficiary.ifsc+`')"><i class="fa fa-paper-plane"></i> Send</button>`;
                                    //} else {
                                    //    out +=`<button class="btn btn-info btn-xs legitRipple" onclick="otpVerify('`+mobile+`', '`+beneficiary.bankCode+`', '`+beneficiary.bankAccountNumber+`', '`+beneficiary.ifsc+`', '`+beneficiary.recipientName+`')"><i class="fa fa-check"></i> Verify</button>`;
                                    //}
                            out +=`</td>
                                </tr>`;
                    }
                    $('.transaction').find('tbody').html(out);
                } else {
                    $('.transaction').find('tbody').html('');
                }
            },
            error: function(error){
                notify("Something went wrong", 'danger', "inline",form);
            }
        });
    }

    function getName(mobile, benebank, beneaccount, beneifsc, benename, benemobile, type)
    {
        swal({
            title: 'Are you sure ?',
            text: "You want verify account details, it will charge.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: "Yes Verify",
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !swal.isLoading(),
            preConfirm: () => {
                return new Promise((resolve) => {
                    $.ajax({
                        url: "{{route('dmt1transaction')}}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType:'json',
                        data: {
                            'type':"VerifyBankAcct",
                            'mobile':mobile,
                            "beneaccount":beneaccount,
                            "beneifsc":beneifsc,
                            "benebank":benebank,
                            "benename":benename,
                            "benemobile":benemobile
                        },
                        success: function(data){
                            swal.close();
                            if(data.statuscode == "IWB"){
                                notify(data.message , 'warning');
                            }else if (data.statuscode == "TXN") {
                                if(type == "add"){
                                    $( "#beneficiaryForm" ).find('input[name="benename"]').val(data.message.impsName);
                                    $( "#beneficiaryForm" ).find('input[name="benename"]').blur();
                                    notify("Success! Account details found", 'success', "inline", $( "#beneficiaryForm" ));
                                }else{
                                    swal(
                                        'Account Verified',
                                        "Account Name is - "+ data.message.impsName,
                                        'success'
                                    );
                                }
                            }else {
                                if(type == "add"){
                                    notify(data.message, 'danger', "inline", $( "#beneficiaryForm" ));
                                }else{
                                    swal('Oops!', data.message, 'error');
                                }
                            }
                        },
                        error: function(errors){
                            swal.close();
                            showError(errors, 'withoutform');
                        }
                    });
                });
            },
        });
    }

    function sendMoney(mobile, recipientId, recipientName, bankAccountNumber, bankName, ifsc)
    {
        $('#transferForm').find('input[name="mobile"]').val(mobile);
        $('#transferForm').find('input[name="rec_id"]').val(recipientId);
        $('#transferForm').find('input[name="benename"]').val(recipientName);
        $('#transferForm').find('input[name="beneaccount"]').val(bankAccountNumber);
        $('#transferForm').find('input[name="benebank"]').val(bankName);
        $('#transferForm').find('input[name="beneifsc"]').val(ifsc);

        $('#transferForm').find('.benename').text(recipientName);
        $('#transferForm').find('.beneaccount').text(bankAccountNumber);
        $('#transferForm').find('.beneifsc').text(ifsc);
        $('#transferForm').find('.benebank').text(bankName);
        $('#transferModal').modal();
    }

    function banklist()
    {
        var mobile = $(this).closest('form').find('input[name="mobile"]').val();
        var form  = $(this).closest('form');
        $.ajax({
            url: "{{route('dmt1transaction')}}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType:'json',
            data: {'mobile':mobile, 'type':"BankList"},
            beforeSend:function(){
                swal({
                    title: 'Wait!',
                    text: 'We are processing your request.',
                    allowOutsideClick: () => !swal.isLoading(),
                    onOpen: () => {
                        swal.showLoading()
                    }
                });
            },
            success: function(data){
                console.log(data);
                swal.close();
                if(data.statuscode == "TXN"){
                    $('[name="regdata"]').val(data.message.additionalRegData);
                    notify(data.status, 'success', "inline",form);
                }else{
                    notify(data.status, 'danger', "inline",form);
                }
            },
            error: function(error){
                swal.close();
                notify("Something went wrong", 'danger', "inline",form);
            }
        });
    }
    
    function otpVerify(mobile, benebank, beneaccount, beneifsc, benemobile)
    {
        swal({
            title: 'Are you sure ?',
            text: "You want verify account details, it will charge.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: "Yes Verify",
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !swal.isLoading(),
            preConfirm: () => {
                return new Promise((resolve) => {
                    $.ajax({
                        url: "{{route('dmt1transaction')}}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType:'json',
                        data: {
                            'type':"VerifyBankAcct",
                            'mobile':mobile,
                            "benebank":benebank,
                            "beneaccount":beneaccount,
                            "beneifsc":beneifsc,
                            "benemobile":benemobile
                        },
                        success: function(data){
                            console.log(data);
                            swal.close();
                            if(data.statuscode == "IWB"){
                                notify(data.message , 'warning');
                            }else if (data.statuscode == "TXN") {
                                if(type == "add"){
                                    $( "#beneficiaryForm" ).find('input[name="benename"]').val(data.message.impsName);
                                    $( "#beneficiaryForm" ).find('input[name="benename"]').blur();
                                    notify("Success! Account details found", 'success', "inline", $( "#beneficiaryForm" ));
                                }else{
                                    swal(
                                        'Account Verified',
                                        "Account Name is - "+ data.message.impsName,
                                        'success'
                                    );
                                }
                            }else {
                                if(type == "add"){
                                    notify(data.message, 'danger', "inline", $( "#beneficiaryForm" ));
                                }else{
                                    swal('Oops!', data.message, 'error');
                                }
                            }
                        },
                        error: function(errors){
                            swal.close();
                            showError(errors, 'withoutform');
                        }
                    });
                });
            },
        });
    }
</script>
@endpush