@extends('layouts.app')
@section('title', ucfirst($type).' Recharge')
@section('pagetitle', ucfirst($type).' Recharge')
@php
    $table = "yes";
    $search = "hide";
@endphp

@section('content')
<div class="content">
        <div class="tabbable">
            <ul class="nav nav-tabs bg-slate nav-tabs-component">
                <li class="active"><a href="#recharge" data-toggle="tab" id="mobileTab" class="legitRipple" aria-expanded="false" onclick="SETTITLE('mobile', 'rechargeForm')">Mobile</a></li>
                <li><a href="#recharge" data-toggle="tab" id="dthTab" class="legitRipple" aria-expanded="false" onclick="SETTITLE('dth', 'rechargeForm')">Dth</a></li>
                <li><a href="#bbpsRecharge" data-toggle="tab" id="dthTab" class="legitRipple" aria-expanded="false" onclick="SETBILLTITLE('fasttag', 'billpayForm')">FasTag</a></li>
                <li><a href="#bbpsRecharge" data-toggle="tab" id="dthTab" class="legitRipple" aria-expanded="false" onclick="SETBILLTITLE('cable', 'billpayForm')">Cabel TV</a></li>
                <li><a href="#bbpsRecharge" data-toggle="tab" id="dthTab" class="legitRipple" aria-expanded="false" onclick="SETBILLTITLE('postpaid', 'billpayForm')">Postpaid</a></li>
                <li><a href="#bbpsRecharge" data-toggle="tab" id="dthTab" class="legitRipple" aria-expanded="false" onclick="SETBILLTITLE('broadband', 'billpayForm')">Broadband</a></li>
                <li><a href="#bbpsRecharge" data-toggle="tab" id="dthTab" class="legitRipple" aria-expanded="false" onclick="SETBILLTITLE('landline', 'billpayForm')">Landline</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="recharge">
                    <div class="panel panel-default">
                        <form id="rechargeForm" action="{{route('rechargepay')}}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="type" value="{{$type}}">
                            <input type="hidden" name="gps_location">
                            <input type="hidden" name="pin">

                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label><span class="servicename">{{$type}}</span> Operator</label>
                                        <select name="provider_id" class="form-control select" required>
                                            <option value="">Select Operator</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Enter Recharge Number</label>
                                        <input type="text" name="number" class="form-control" placeholder="Enter number" required="" autocomplete="false">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Enter Recharge Amount</label>
                                        <label class="label label-primary planlable btn-raised pull-right cursor-pointer .h5" onclick="GETPLAN()">Browse Plan</label>
                                        <input type="number" step="any" name="amount" class="form-control" placeholder="Enter amount" required="" autocomplete="false" >
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <button type="submit" class="btn bg-teal-400 btn-labeled legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Paying"><b><i class=" icon-paperplane"></i></b> Pay Now</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="tab-pane" id="bbpsRecharge">
                    <div class="panel panel-default">
                        <form id="billpayForm" action="{{route('billpay')}}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="type" value="payment">
                            <input type="hidden" name="TransactionId">
                            <input type="hidden" name="biller">
                            <input type="hidden" name="pin">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label><span class="servicename">{{$type}}</span> Operator</label>
                                        <select name="provider_id" class="form-control select" required="" onchange="GET_PROVIDER()">
                                            <option value="">Select Operator</option>
                                        </select>
                                    </div>

                                    <div class="billdata">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <button type="submit" id="pay" class="btn bg-teal-400 btn-labeled legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Paying"><b><i class=" icon-paperplane"></i></b> Pay Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Recent <span class="servicename">{{$type}}</span> Recharge</h4>
            </div>
            <table class="table table-bordered table-striped table-hover" id="datatable">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Recharge Details</th>
                        <th>Reference Details</th>
                        <th>Amount/Commission</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div id="mplanModal" class="modal fade right" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-slate">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Recharge Plans</h6>
                </div>
                <div class="modal-body p-0 planData">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script type="text/javascript">
    $(document).ready(function () {
        var url = "{{route('reportstatic')}}";
        $("[name='dataType']").val("recharge");

        $(window).load(function () {
            $("#{{$type}}Tab").trigger("click");
        });

        var onDraw = function() {};

        var options = [
            { "data" : "name",
                render:function(data, type, full, meta){
                    return `<div>
                            <span class='text-inverse m-l-10'><b>`+full.id +`</b> </span>
                            <div class="clearfix"></div>
                        </div><span style='font-size:13px' class="pull=right">`+full.created_at+`</span>`;
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return `Number - `+full.number+`<br>Operator - `+full.providername;
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return `Refno - `+full.refno+`<br>Payid - `+full.payid+`<br>Txnid - `+full.txnid;
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return `Amount - <i class="fa fa-inr"></i> `+full.amount+`<br>Profit - <i class="fa fa-inr"></i> `+full.profit;
                }
            },
            { "data" : "status",
                render:function(data, type, full, meta){
                    if(full.status == "success"){
                        var out = `<span class="label label-success">Success</span>`;
                    }else if(full.status == "pending"){
                        var out = `<span class="label label-warning">Pending</span>`;
                    }else if(full.status == "reversed"){
                        var out = `<span class="label bg-slate">Reversed</span>`;
                    }else{
                        var out = `<span class="label label-danger">Failed</span>`;
                    }
                    return out;
                }
            }
        ];

        datatableSetup(url, options, onDraw);

        $( "#rechargeForm" ).validate({
            rules: {
                provider_id: {
                    required: true,
                    number : true,
                },
                number: {
                    required: true,
                    number : true,
                    minlength: 8
                },
                amount: {
                    required: true,
                    number : true,
                    min: 10
                },
            },
            messages: {
                provider_id: {
                    required: "Please select {{$type}} operator",
                    number: "Operator id should be numeric",
                },
                number: {
                    required: "Please enter {{$type}} number",
                    number: "Mobile number should be numeric",
                    min: "Mobile number length should be atleast 8",
                },
                amount: {
                    required: "Please enter {{$type}} amount",
                    number: "Amount should be numeric",
                    min: "Min {{$type}} amount value rs 10",
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
                var form = $('#rechargeForm');
                var id   = form.find('[name="id"]').val();

                SYSTEM.tpinVerify(function(pin){
                    form.find('[name="pin"]').val(pin);
                    SYSTEM.FORMSUBMIT(form, function(data){
                        form.find('[name="pin"]').val("");
                        if (!data.statusText) {
                            if(data.statuscode == "TXN"){
                                getbalance();
                                form[0].reset();
                                form.find('select').select2().val(null).trigger('change');
                                $.alert({
                                    icon: 'fa fa-check',
                                    theme: 'modern',
                                    animation: 'scale',
                                    type: 'green',
                                    title   : "Success",
                                    content : data.message+", Refno - "+data.rrn
                                });
                                $('#datatable').dataTable().api().ajax.reload();
                            }else if(data.statuscode == "TXF"){
                                $.alert({
                                    title: 'Oops!',
                                    content: data.message + ", Reason - "+ data.rrn,
                                    type: 'red'
                                });
                            }else{
                                if(
                                    data.message == "Transaction Pin is block, reset tpin" ||
                                    data.message == "Transaction pin is incorrect" 
                                ){
                                    $.alert({
                                        title: 'Oops Wrong Pin!',
                                        content: data.message,
                                        type: 'red'
                                    });

                                    tpinConfirm.open();
                                }else{
                                    $.alert({
                                        title: 'Oops!',
                                        content: data.message,
                                        type: 'red'
                                    });
                                }
                            }
                        } else {
                            SYSTEM.SHOWERROR(data, form);
                        }
                    });
                });
            }
        });

        $( "#billpayForm" ).validate({
            rules: {
                provider_id: {
                    required: true,
                    number : true,
                },
                number: {
                    required: true,
                    number : true,
                    minlength: 8
                },
                amount: {
                    required: true,
                    number : true,
                    min: 10
                },
            },
            messages: {
                provider_id: {
                    required: "Please select {{$type}} operator",
                    number: "Operator id should be numeric",
                },
                number: {
                    required: "Please enter {{$type}} number",
                    number: "Mobile number should be numeric",
                    min: "Mobile number length should be atleast 8",
                },
                amount: {
                    required: "Please enter {{$type}} amount",
                    number: "Amount should be numeric",
                    min: "Min {{$type}} amount value rs 10",
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
                var form = $('#billpayForm');
                var id = form.find('[name="id"]').val();
                var billerResponse = form.find('[name="billerResponse"]').val();

                if(billerResponse != ""){
                    SYSTEM.tpinVerify(function(pin){
                        form.find('[name="pin"]').val(pin);
                        SYSTEM.FORMSUBMIT(form, function(data){
                            form.find('[name="pin"]').val("");
                            if (!data.statusText) {
                                if(data.statuscode == "TXN"){
                                    form[0].reset();
                                    form.find('select').select2().val(null).trigger('change');
                                    getbalance();
                                    $('#datatable').dataTable().api().ajax.reload();

                                    $.alert({
                                        icon: 'fa fa-check',
                                        theme: 'modern',
                                        animation: 'scale',
                                        type: 'green',
                                        title   : "Success",
                                        content : data.message+", Refno - "+data.refno
                                    });

                                }else if(data.statuscode == "TXF"){                     
                                    form[0].reset();
                                    form.find('select').select2().val(null).trigger('change');
                                    $.alert({
                                        title: 'Oops!',
                                        content: data.message + ", Reason - "+ data.rrn,
                                        type: 'red'
                                    });
                                }else{
                                    if(
                                        data.message == "Transaction Pin is block, reset tpin" ||
                                        data.message == "Transaction pin is incorrect" 
                                    ){
                                        $.alert({
                                            title: 'Oops Wrong Pin!',
                                            content: data.message,
                                            type: 'red'
                                        });

                                        tpinConfirm.open();
                                    }else{
                                        $.alert({
                                            title: 'Oops!',
                                            content: data.message,
                                            type: 'red'
                                        });
                                    }
                                }
                            } else {
                                SYSTEM.SHOWERROR(data, form);
                            }
                        });
                    });
                }else{
                    $.alert({
                        title: 'Oops!',
                        content: "Bill Fetching Is Required",
                        type: 'red'
                    });
                }
            }
        });
    });

    function SETTITLE(type, form) {
        $(".servicename").text(type);
        $.ajax({
            url  : "{{route('providers')}}",
            type : 'post',
            dataType : 'json',
            data : {"type" : type},
            headers  : {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend:function(){
                swal({
                    title: 'Wait!',
                    text: 'We are fetching operators',
                    onOpen: () => {
                        swal.showLoading()
                    },
                    allowOutsideClick: () => !swal.isLoading()
                });
            }
        })
        .done(function(data) {
            swal.close();
            $("#"+form).find('[name="provider_id"]').empty();

            var out = `<option value="">Select Operator</option>`;
            $.each(data.data, function(i,val) {
                out += `<option value="`+val.id+`">`+val.name+`</option>`;
            });

            $("#"+form).find('[name="provider_id"]').html(out);
        })
        .fail(function(errors) {
            swal.close();
            showError(errors, $('#billpayForm'));
        });
    }

    function SETBILLTITLE(type, form) {
        $(".servicename").text(type);
        $.ajax({
            url  : "{{route('providersList')}}",
            type : 'post',
            dataType : 'json',
            data : {"type" : type, "billPayType" : "quickpay,validatepay,fetchpay"},
            headers  : {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend:function(){
                swal({
                    title: 'Wait!',
                    text: 'We are fetching operators',
                    onOpen: () => {
                        swal.showLoading()
                    },
                    allowOutsideClick: () => !swal.isLoading()
                });
            }
        })
        .done(function(data) {
            swal.close();
            $("#"+form).find('[name="provider_id"]').empty();

            var out = `<option value="">Select Operator</option>`;
            $.each(data.data, function(i,val) {
                out += `<option value="`+val.id+`">`+val.name+`</option>`;
            });

            $("#"+form).find('[name="provider_id"]').html(out);
        })
        .fail(function(errors) {
            swal.close();
            showError(errors, $('#billpayForm'));
        });
    }

    function GET_PROVIDER() {
        $('.billdata').empty();
        var providerid = $("#billpayForm").find('[name="provider_id"]').val();
        if(providerid != ''){
            $.ajax({
                url: "{{route('providersDetails')}}",
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend:function(){
                    swal({
                        title: 'Wait!',
                        text: 'We are fetching bill details',
                        onOpen: () => {
                            swal.showLoading()
                        },
                        allowOutsideClick: () => !swal.isLoading()
                    });
                },
                data: { "provider_id" : providerid}
            })
            .done(function(data) {
                swal.close();
                $('.billdata').empty();

                if(data.data.billPayType == "fetchpay"){
                    $.each(data.data.paramname, function(i,val) {
                        var html =   '';
                        html     +=  '<div class="form-group col-md-4">';
                        html     +=  '<label>Enter '+data.data.paramname[i]+'</label>';
                        html     +=  '<input type="text" name="number'+i+'" class="form-control" placeholder="Enter value" required="" onchange="getname()" autocomplete="false">';
                        html     +=  '<label class="label label-primary planlable btn-raised pull-right mt-5" onclick="getname()" style="cursor:pointer">Fetch Name</label>';
                        html     +=  '</div>';
                        $('.billdata').append(html);
                    });
                }else{
                    $.each(data.data.paramname, function(i,val) {
                        var html =   '';
                        html     +=  '<div class="form-group col-md-4">';
                        html     +=  '<label>Enter '+data.data.paramname[i]+'</label>';
                        html     +=  '<input type="text" name="number'+i+'" class="form-control" placeholder="Enter value" required="" autocomplete="false">';
                        html     +=  '</div>';
                        $('.billdata').append(html);
                    });

                    $('.billdata').append(`
                        <div class="form-group col-md-4">
                            <label>Enter Recharge Amount</label>
                            <input type="text" name="amount" class="form-control" placeholder="Enter amount" required="" autocomplete="false">
                            <b>Name - </b>`+data.data.customername+`
                        </div>`
                    );
                }
            })
            .fail(function(errors) {
                swal.close();
                showError(errors, $('#billpayForm'));
            });
        }
    }

    function getname() {
        var providerid = $("#billpayForm").find('[name="provider_id"]').val();
        var number = $("#billpayForm").find('[name="number0"]').val();
        $('.billdata').find("[name='amount']").closest(".form-group").remove();

        if(providerid != '' && number != ''){
            $.ajax({
                url: "{{route('billpayFetch')}}",
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {'provider_id' : providerid, 'number0' : number},
                beforeSend:function(){
                    swal({
                        title: 'Wait!',
                        text: 'We are fetching bill details',
                        onOpen: () => {
                            swal.showLoading()
                        },
                        allowOutsideClick: () => !swal.isLoading()
                    });
                }
            })
            .done(function(data) {
                swal.close();
                if(data.statuscode == "TXN"){
                    $('#billpayForm').find('[name="amount"]').closest("div.form-group").remove();
                    $('#billpayForm').find('[name="TransactionId"]').val(data.data.TransactionId);
                    $('#billpayForm').find('[name="biller"]').val(data.data.customername);
                    
                    $('.billdata').append(`
                        <div class="form-group col-md-4">
                            <label>Enter Recharge Amount</label>
                            <input type="text" name="amount" class="form-control" placeholder="Enter amount" required="" autocomplete="false">
                            <b>Name - </b>`+data.data.customername+`
                        </div>`);
                }else{
                    $.alert({
                        title: 'Oops!',
                        content: data.message,
                        type: 'red'
                    });
                }
            })
            .fail(function(errors) {
                swal.close();
                showError(errors, $('#billpayForm'));
            });
        }else{
            notify("Operator & Vehical Number is required", 'error');
        }
    }

    function GETPLAN() {
        var providerid = $("#rechargeForm").find('[name="provider_id"]').val();
        var number = $("#rechargeForm").find('[name="number"]').val();
        var type   = $("[name='dataType']").val();

        if(providerid != ''){
            $.ajax({
                url: "{{route('plan')}}",
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend:function(){
                    swal({
                        title: 'Wait!',
                        text: 'We are fetching plan details',
                        onOpen: () => {
                            swal.showLoading()
                        },
                        allowOutsideClick: () => !swal.isLoading()
                    });
                },
                data: { "number" : number, "provider_id" : providerid, "type" : "plan"}
            })
            .done(function(data) {
                swal.close();
                if(data.statuscode == "TXN"){
                    var head = `<ul class="nav nav-tabs  nav-tabs-bottom no-margin">`;
                    var tabdata = ``;
                    
                    var count = 0;
                    $.each(data.key, function(index, val) {
                        count = count+1;
                        if(count == "1"){
                            var active = "active";
                        }else{
                            var active = "";
                        }
                        head += `<li class="`+active+`">
                            <a href="#`+count+`-tab" data-toggle="tab" class="nav-link" aria-expanded="false">`+val+` Plan</a>
                        </li>`;
                    });

                    var count = 0;
                    $.each(data.value, function(index, value) {
                        count = count+1;
                        if(count == "1"){
                            var active = "active";
                        }else{
                            var active = "";
                        }
                        var plandata = ``;

                        $.each(value, function(index, val) {
                            plandata += `<tr><td><button class="btn btn-xs btn-primary" onclick="SETAMOUNT('`+val.rs+`')" style="width: 70px;padding:2px 0px;font-size: 15px;"><i class="fa fa-inr"></i> `+val.rs+`</button></td><td>`+val.validity+`</td><td>`+val.desc+`</td>
                            </tr>`;
                        });

                        tabdata += `<div class="tab-pane `+active+`" id="`+count+`-tab">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="150px">Amount</th>
                                        <th width="150px">Validity</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    `+plandata+`
                                </tbody>
                            </table>
                        </div>`;
                    });
                    head += '</ul>';

                    var htmldata = head+`<div class="tab-content">
                                `+tabdata+`
                            </div>`;

                    
                    $('#mplanModal').find('.planData').html(htmldata);
                    $('#mplanModal').modal();
                }else{
                    $.alert({
                        title: 'Oops!',
                        content: data.message,
                        type: 'red'
                    });
                }
            })
            .fail(function(errors) {
                swal.close();
                showError(errors, $('#rechargeForm'));
            });
        }
    }

    function SETAMOUNT(amount){
        $('#rechargeForm').find('[name="amount"]').val(amount);
        $('#mplanModal').modal("hide");
    }
</script>
@endpush