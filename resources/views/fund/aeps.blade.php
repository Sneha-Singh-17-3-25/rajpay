@extends('layouts.app')
@section('title', "Payout Request")
@section('pagetitle',  "Payout Request")

@php
    $table = "yes";
    $export = "aepsfundrequest";
    $status['type'] = "Fund";
    $status['data'] = [
        "success" => "Success",
        "pending" => "Pending",
        "failed" => "Failed",
        "approved" => "Approved",
        "rejected" => "Rejected",
    ];
@endphp

@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Payouts Bank</h4>
                    <div class="heading-elements">
                        <button type="button" data-toggle="modal" data-target="#fundRequestModal" class="btn bg-slate btn-xs btn-labeled legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Searching"><b><i class="icon-plus2"></i></b> Move to Wallet Request</button>

                        <button type="button" data-toggle="modal" data-target="#addBankModel" class="btn bg-slate btn-xs btn-labeled legitRipple btn-lg"><b><i class="icon-plus2"></i></b> New Bank</button>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-hover" id="payout_datatable">
                    <thead>
                        <tr>
                            <th width="160px">Id</th>
                            <th>Name</th>
                            <th>Account</th>
                            <th>Bank</th>
                            <th>Ifsc</th>
                            <th>Status</th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Payout Request</h4>
                </div>
                <table class="table table-bordered table-striped table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th width="160px">#</th>
                            <th>User Details</th>
                            <th>Bank Details</th>
                            <th>Refrence Details</th>
                            <th>Amount</th>
                            <th>Remark</th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="fundRequestModal" class="modal fade" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Wallet Fund Request</h6>
            </div>

            <form id="walletRequestForm" action="{{route('payout')}}" method="post">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <input type="hidden" class="form-control" name="type" value="wallet" required="">
                    <input type="hidden" name="pin">
                    <legend>Move To Wallet</legend>
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" class="form-control" name="amount" placeholder="Enter Value" required="">
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

<div id="payoutModal" class="modal fade" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Payout</h6>
            </div>

            <form id="fundRequestForm" action="{{route('payout')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="type" value="bank">
                <input type="hidden" name="beneid">
                <input type="hidden" name="pin">
                <div class="modal-body">
                    <legend>Bank Account</legend>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Account Number</label>
                            <input type="text" class="form-control" id="account" placeholder="Enter Value" readonly="">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Ifsc Code</label>
                            <input type="text" class="form-control" id="ifsc" placeholder="Enter Value" readonly="">
                        </div>
                    </div>

                    <legend>Bank Settlement</legend>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Transfer Mode</label>
                            <select name="mode" class="form-control select">
                                <option value="IMPS" selected="">IMPS</option>
                                <option value="NEFT">NEFT</option>
                                <option value="RTGS">RTGS</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Amount</label>
                            <input type="number" class="form-control" name="amount" placeholder="Enter Value" required="">
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

<div id="addBankModel" class="modal fade" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Payout Bank </h6>
            </div>
            <form id="addBankForm" action="{{route('payout')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="type" value="addaccount">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Bank Name</label>
                            <select id="bank" name="bank" class="form-control select">
                                <option value="">Select Bank</option>
                                @foreach($banks as $bank)
                                    <option value="{{$bank->bankid}}" ifsc="{{$bank->masterifsc}}">{{$bank->bankname}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Ifsc Code</label>
                            <input type="text" class="form-control" name="ifsc" placeholder="Enter Value">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Account Number</label>
                            <input type="text" class="form-control" name="account" placeholder="Enter Value">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Account Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Value">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Passbook</label>
                            <input type="file" class="form-control" name="passbook" placeholder="Enter Value">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Pancard</label>
                            <input type="file" class="form-control" name="pancard" placeholder="Enter Value">
                        </div>
                    </div>
                    
                    @if($mydata['pincheck'] == "yes")
                        @if(!Myhelper::can('pin_check'))
                            <legend>Authorization</legend>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>T-Pin</label>
                                    <input type="password" class="form-control" name="pin" placeholder="Enter Value" required="">
                                    <a href="{{url('profile/view?tab=pinChange')}}" target="_blank" class="text-primary pull-right">Generate Or Forgot Pin??</a>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn bg-slate btn-raised legitRipple" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="docsModel" class="modal fade" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Submit Documents</h6>
            </div>
            <form id="docForm" action="{{route('payout')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="type" value="uploaddocs">
                <input type="hidden" name="beneid">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Passbook Photo</label>
                            <input type="file" class="form-control" name="passbook" placeholder="Enter Value">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Pancard Photo</label>
                            <input type="file" class="form-control" name="pancard" placeholder="Enter Value">
                        </div>
                    </div>

                    @if($mydata['pincheck'] == "yes")
                        @if(!Myhelper::can('pin_check'))
                            <legend>Authorization</legend>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>T-Pin</label>
                                    <input type="password" class="form-control" name="pin" placeholder="Enter Value" required="">
                                    <a href="{{url('profile/view?tab=pinChange')}}" target="_blank" class="text-primary pull-right">Generate Or Forgot Pin??</a>
                                </div>
                            </div>
                        @endif
                    @endif

                    <p class="text-danger">Note - If you want to change bank details, please send mail with account details to update your bank details.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn bg-slate btn-raised legitRipple" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script type="text/javascript">
    $(document).ready(function () {
        $('[name="dataType"]').val("payout");
        var url = "{{route("reportstatic")}}";

        var onDraw = function() {};
        var options = [
            { "data" : "name",
                render:function(data, type, full, meta){
                    var out = '';
                    if(full.api){
                        out +=  `<span class='myspan'>`+full.api.api_name +`</span><br>`;
                    }
                    out += `<span class='text-inverse'>`+full.id +`</span><br><span style='font-size:12px'>`+full.created_at+`</span>`;
                    return out;
                }
            },
            { "data" : "username"},
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return full.number +" ( "+full.description+" )<br>"+full.option3 + " ("+full.option2+")";
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return "Ref No: "+full.refno+"<br>Txn Id : "+full.txnid+"<br>Pay Id : "+full.payid;
                }
            },
            { "data" : "description",
                render:function(data, type, full, meta){
                    return `<span class='text-inverse'><i class="fa fa-rupee"></i> `+full.amount +`</span> / `+full.option4;
                }
            },
            { "data" : "remark"},
            { 
                "data": "action",
                render:function(data, type, full, meta){
                    if(full.status == "success"){
                        var out = '<span class="label label-success text-uppercase"><b>'+full.status+'</b></span>';
                    }else if(full.status== 'pending'){
                        var out = '<span class="label label-warning text-uppercase"><b>'+full.status+'</b></span>';
                    }else{
                        var out = '<span class="label label-danger text-uppercase"><b>'+full.status+'</b></span>';
                    }

                    var menu = `<li class="dropdown-header">Action</li>`;
                    menu += `<li><a href="javascript:void(0)" class="print"><i class="icon-info22"></i>Print Invoice</a></li>`;
                    if(full.status == "refund"){
                        menu += `<li class="dropdown-header">Get Refund</li>
                            <li><a href="javascript:void(0)" onclick="getrefund(`+full.id+`)"><i class="icon-info22"></i>Get Refund</a></li>`;
                    }

                    if(full.status == "success" || full.status == "pending" || full.status == "initiated"){
                        menu += `<li><a href="javascript:void(0)" onclick="status(`+full.id+`, '`+full.product+`')"><i class="icon-info22"></i>Check Status</a></li>`;
                    }

                    if(full.status == "success" || full.status == "pending" || full.status == "reversed"){
                        menu += `<li><a href="javascript:void(0)" onclick="complaint(`+full.id+`, '`+full.product+`')"><i class="icon-cogs"></i> Complaint</a></li>`;
                    }
                    
                    out +=  `<ul class="icons-list">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-right">
                                        `+menu+`
                                    </ul>
                                </li>
                            </ul>`;

                    return out;
                }
            }
        ];

        datatableSetup(url, options, onDraw);

        var patout_url = "{{url('statement/list/fetch')}}/payoutaccount/0";
        var patout_onDraw = function() {};
        var patout_options = [
            { "data" : "id"},
            { "data" : "name"},
            { "data" : "account"},
            { "data" : "bank"},
            { "data" : "ifsc"},{ 
                "data": "action",
                render:function(data, type, full, meta){
                    if(full.status == "approved"){
                        var out = '<span class="label label-success text-uppercase"><b>'+full.status+'</b></span>';
                    }else if(full.status== 'pending'){
                        var out = '<span class="label label-warning text-uppercase"><b>'+full.status+'</b></span>';
                    }else{
                        var out = '<span class="label label-danger text-uppercase"><b>'+full.status+'</b></span>';
                    }
                    return out;
                }
            },
            { 
                "data": "action",
                render:function(data, type, full, meta){
                    if(full.status == "document"){
                        var btn = `<button class="btn bg-slate btn-xs waves-effect mt-10" onclick="docs('`+full.beneid+`')"><i class="fa fa-info22"></i> Submit Document</button>`;
                    }else{
                        var btn = `<button class="btn bg-slate btn-xs waves-effect mt-10" onclick="transfer('`+full.id+`', '`+full.account+`', '`+full.ifsc+`')"><i class="fa fa-info22"></i> Payment</button>`;
                    }

                    return btn;
                }
            }
        ];

        datatableSetup(patout_url, patout_options, patout_onDraw, "#payout_datatable");

        $( "#walletRequestForm").validate({
            rules: {
                amount: {
                    required: true
                }
            },
            messages: {
                amount: {
                    required: "Please enter request amount",
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
                var form = $('#walletRequestForm');
                var inputdata = { "for": "Payout"};
               
                SYSTEM.tpinVerify(function (pin) {
                    form.find('[name="pin"]').val(pin);
                    SYSTEM.FORMSUBMIT(form, function(data){
                        form.find('[name="pin"]').val("");
                        if (!data.statusText) {
                            if(data.statuscode == "TXN" || data.statuscode == "TUP"){
                                form[0].reset();
                                form.closest(".modal").modal('hide');
                                form.find("[name='mode']").val('IMPS').trigger('change');
                                $.alert({
                                    icon: 'fa fa-check',
                                    theme: 'modern',
                                    animation: 'scale',
                                    type: 'green',
                                    title   : "Success",
                                    content : "Fund Request submitted Successfull "
                                });
                                $('#datatable').dataTable().api().ajax.reload();
                                getbalance();
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

        $( "#fundRequestForm").validate({
            rules: {
                amount: {
                    required: true
                },
                type: {
                    required: true
                },
            },
            messages: {
                amount: {
                    required: "Please enter request amount",
                },
                type: {
                    required: "Please select request type",
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
                var form = $('#fundRequestForm');
                var inputdata = { "for": "Payout"};

                @if(session("otppayout") == "yes")
                    SYSTEM.AJAX("{{route('sendotp')}}", "POST", inputdata, function(data){
                        if(!data.statusText){
                            if(data.status == "TXN" || data.status == "PASS"){
                                if(data.status == "TXN"){
                                    SYSTEM.NOTIFY('Otp send successfully', 'success');
                                }

                                SYSTEM.otpVerify(function (pin) {
                                    form.find('[name="pin"]').val(pin);
                                    SYSTEM.FORMSUBMIT(form, function(data){
                                        form.find('[name="pin"]').val("");
                                        if (!data.statusText) {
                                            if(data.statuscode == "TXN"){
                                                form[0].reset();
                                                form.closest(".modal").modal('hide');
                                                form.find("[name='mode']").val('IMPS').trigger('change');
                                                $.alert({
                                                    icon: 'fa fa-check',
                                                    theme: 'modern',
                                                    animation: 'scale',
                                                    type: 'green',
                                                    title   : "Success",
                                                    content : "Fund Request submitted Successfull "
                                                });
                                                $('#datatable').dataTable().api().ajax.reload();
                                                getbalance();
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

                            }else{
                                SYSTEM.SHOWERROR(data, $('#fundRequestForm'));
                            }
                        }else{
                            SYSTEM.SHOWERROR(data, $('#fundRequestForm'));
                        }
                    }, $("#fundRequestForm"), "Sending Otp");
                @else
                    SYSTEM.tpinVerify(function (pin) {
                        form.find('[name="pin"]').val(pin);
                        SYSTEM.FORMSUBMIT(form, function(data){
                            form.find('[name="pin"]').val("");
                            if (!data.statusText) {
                                if(data.statuscode == "TXN"){
                                    form[0].reset();
                                    form.closest(".modal").modal('hide');
                                    form.find("[name='mode']").val('IMPS').trigger('change');
                                    $.alert({
                                        icon: 'fa fa-check',
                                        theme: 'modern',
                                        animation: 'scale',
                                        type: 'green',
                                        title   : "Success",
                                        content : "Fund Request submitted Successfull "
                                    });
                                    $('#datatable').dataTable().api().ajax.reload();
                                    getbalance();
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
                @endif
            }
        });

        $( "#addBankForm").validate({
            rules: {
                name: {
                    required: true
                },
                account: {
                    required: true
                },
                ifsc: {
                    required: true
                },
                bank: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Please enter request amount",
                },
                account: {
                    required: "Please select request type",
                },
                ifsc: {
                    required: "Please select request type",
                },
                bank: {
                    required: "Please select request type",
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
                var form = $('#addBankForm');
                form.ajaxSubmit({
                    dataType:'json',
                    beforeSubmit:function(){
                        form.find('button:submit').button('loading');
                    },
                    complete: function () {
                        form.find('button:submit').button('reset');
                    },
                    success:function(data){
                        form.find('button:submit').button('reset');
                        if(data.statuscode == "TXN"){
                            form[0].reset();
                            form.closest(".modal").modal("hide");
                            notify("Payout Bank Successfully added", 'success');
                            $('#payout_datatable').dataTable().api().ajax.reload();
                        }else{
                            notify(data.message , 'warning');
                        }
                    },
                    error: function(errors) {
                        form.find('button:submit').button('reset');
                        showError(errors);
                    }
                });
            }
        });

        $( "#docForm").validate({
            rules: {
                passbook: {
                    required: true
                },
                pancard: {
                    required: true
                }
            },
            messages: {
                passbook: {
                    required: "Please upload passbook",
                },
                pancard: {
                    required: "Please upload pancard",
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
                var form = $('#docForm');
                form.ajaxSubmit({
                    dataType:'json',
                    beforeSubmit:function(){
                        form.find('button:submit').button('loading');
                    },
                    complete: function () {
                        form.find('button:submit').button('reset');
                    },
                    success:function(data){
                        form.find('button:submit').button('reset');
                        if(data.statuscode == "TXN"){
                            form[0].reset();
                            form.closest(".modal").modal("hide");
                            notify("Payout Bank Approved Successfully", 'success');
                            $('#payout_datatable').dataTable().api().ajax.reload();
                        }else{
                            notify(data.message , 'warning');
                        }
                    },
                    error: function(errors) {
                        form.find('button:submit').button('reset');
                        showError(errors);
                    }
                });
            }
        });

        $('#getBenename').click(function(){
            var mobile = "{{Auth::user()->mobile}}";
            var name   = "payout";
            var benebank    = $(this).closest('form').find("[name='bank']").val();
            var beneaccount = $(this).closest('form').find("[name='account']").val();
            var beneifsc = $(this).closest('form').find("[name='ifsc']").val();
            var benename = "{{Auth::user()->name}}";

            if(mobile != '' || name != '' || benebank != '' || beneaccount != '' || beneifsc != '' || benename != ''){
                getname(mobile, name, benebank, beneaccount, beneifsc, benename);
            }
        });
    });

    function bankChange() {
        var type = $("[name='type']").val();
        if(type == "bank"){
            $('[name="mode"]').closest('.form-group').show();
        }else{
            $('[name="mode"]').closest('.form-group').hide();
        }
    }

    function transfer(beneid, account, ifsc) {
        $("[name='beneid']").val(beneid);
        $("#account").val(account);
        $("#ifsc").val(ifsc);

        $('#payoutModal').modal('show');
    }

    function docs(beneid) {
        $("[name='beneid']").val(beneid);
        $('#docsModel').modal('show');
    }

    function GETOTP(type){
        var inputdata = { "for": type};

        SYSTEM.AJAX("{{route('sendotp')}}", "POST", inputdata, function(data){
            if(!data.statusText){
                if(data.status == "TXN"){
                    SYSTEM.NOTIFY('Otp send successfully', 'success');
                }else if(data.status == "PASS"){
                }else{
                    SYSTEM.SHOWERROR(data, $('#addBankForm'));
                }
            }else{
                SYSTEM.SHOWERROR(data, $('#addBankForm'));
            }
        }, $("#addBankForm"), "Sending Otp");
    }

    function getname(mobile, name, benebank, beneaccount, beneifsc, benename) {
        $("[name='verify']").val("yes");
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
                            'type':"accountverification",
                            'mobile':mobile,
                            "beneaccount":beneaccount,
                            "beneifsc":beneifsc,
                            "name":name,
                            "benebank":benebank,
                            "benename":benename
                        },
                        success: function(data){
                            swal.close();
                            if (data.statuscode == "TXN") {
                                $("[name='name']").val(data.message);
                                swal(
                                    'Account Verified',
                                    "Account Name is - "+ data.message,
                                    'success'
                                );
                            }else {
                                swal('Oops!', data.message,'error');
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