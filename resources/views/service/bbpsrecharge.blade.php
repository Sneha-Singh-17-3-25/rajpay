@extends('layouts.app')
@section('title', ucfirst($type).' Bill Payment')
@section('pagetitle', ucfirst($type).' Bill Payment')

@php
    $table = "yes";
    $search = "hide";
    $category = ["prepaid-mobile", "prepaid-dth", "fasttag", "cabletv", "mobilepostpaid", "landlinepostpaid", "broadbandpostpaid"];
@endphp


@section('content')
<div class="content">
    <div class="tabbable">
        <ul class="nav nav-tabs bg-slate nav-tabs-component">

            @foreach ($category as $category)
                <li><a href="#recharge" data-toggle="tab" id="{{$category}}Tab" class="legitRipple" aria-expanded="false" onclick="SETTITLE('{{$category}}', 'billpayForm', 'online')">{{ucfirst($category)}}</a></li>
            @endforeach
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="recharge">
                <div class="panel panel-default">
                    <form id="billpayForm" action="{{route('billpay')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="type" value="payment">
                        <input type="hidden" name="TransactionId">
                        <input type="hidden" name="biller">
                        <input type="hidden" name="duedate">
                        <input type="hidden" name="gps_location">
                        <input type="hidden" name="pin">
                        <input type="hidden" name="amount">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-11">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>Select Billpay Operator</label>
                                            <select name="provider_id" class="form-control select" required="" onchange="GET_PROVIDER('billpayForm')">
                                                <option value="">Select Operator</option>
                                            </select>
                                        </div>

                                        <div class="billdata">
                                          
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-1">{{-- 
                                    <img src="{{asset('assets')}}/BharatBillPay.png" class="pull-left" style="width: 70px"> --}}
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button type="submit" id="pay" class="btn bg-teal-400 btn-labeled legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Paying"><b><i class=" icon-paperplane"></i></b> Pay Now</button>
                            <button type="button" class="btn bg-teal-400 legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Fetching" onclick="getname('none', 'billpayForm')"> Fetch Bill</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Recent {{ucfirst($type)}} Bill Payment</h4>
                </div>
                <table class="table table-bordered table-striped table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User Details</th>
                            <th>Transaction Details</th>
                            <th>Refrence Details</th>
                            <th>Amount/Commission</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="receipt" class="modal fade" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
  
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Invoice</h4>
            </div>
            <div class="modal-body">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4>#Invoice</h4>
                    </div>
                    <div class="pull-right">
                        @if(Auth::user()->company->logo)
                            <img src="{{asset('')}}public/{{$mydata['company']->logo}}" class=" img-responsive" alt="" style="height: 75px;">
                        @else
                            {{Auth::user()->company->companyname}}
                        @endif 
                    </div>
                </div>
                <hr class="mt-5 mb-5">
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
                                <strong>Electricity Board: </strong> <span class="provider"></span><br>
                            </address>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <h4>Bill Details :</h4>
                            <table class="table m-t-10">
                              <thead>
                                    <tr>
                                        <th>Consumer Name</th>
                                        <th>Consumer Number</th>
                                        <th>Ref No.</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="biller"></td>
                                        <td class="number"></td>
                                        <td class="refno"></td>
                                        <td class="status"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <hr class="mt-5 mb-5">

                <div class="row">
                    <div class="col-md-6">
                        <span>Powered By</span>
                        <img src="{{$mydata['company']->logo ?? ""}}" width="100px" class="img-responsive">
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-right"><b>Amount : <span class="amount"></span></b></h6>
                    </div>
                </div>
            </div>

            <div class="hidden-print modal-footer">
                <div class="pull-right">
                  <a href="javascript:void(0)"  id="print" class="btn btn-primary waves-effect waves-light"><i class="fa fa-print"></i> Print</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
    <style type="text/css">
        .nav-tabs-component > li > a{
            padding: 15px 15px !important;
            font-size: 14px !important;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('/assets/js/core/jQuery.print.js') }}"></script>
    <script type="text/javascript">
        var PARAMETER = {};

        $(document).ready(function () {
            $(window).load(function () {
                $("#{{$type}}Tab").trigger("click");
            });

            var url = "{{route('reportstatic')}}";
            $("[name='dataType']").val("billpay");

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
                { "data" : "username"},
                { "data" : "bank",
                    render:function(data, type, full, meta){
                        return `Number - `+full.number+`<br>Operator - `+full.providername;
                    }
                },
                { "data" : "bank",
                    render:function(data, type, full, meta){
                        return `Ref No.  - `+full.refno+`<br>Txnid - `+full.txnid;
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

            $('#print').click(function(){
                $('#receipt').find('.modal-body').print();
            });

            $( "#billpayForm" ).validate({
                rules: {
                    provider_id: {
                        required: true,
                        number : true,
                    },
                    amount: {
                        required: true,
                        number : true,
                        min: 10
                    },
                    biller: {
                        required: true
                    },
                    duedate: {
                        required: true,
                    },
                },
                messages: {
                    provider_id: {
                        required: "Please select recharge operator",
                        number: "Operator id should be numeric",
                    },
                    amount: {
                        required: "Please enter recharge amount",
                        number: "Amount should be numeric",
                        min: "Min recharge amount value rs 10",
                    },
                    biller: {
                        required: "Please enter biller name",
                    },
                    duedate: {
                        required: "Please enter biller duedate",
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
                    var id   = form.find('[name="id"]').val();
                    var type = form.find('[name="type"]').val();
                    var TransactionId = form.find('[name="TransactionId"]').val();

                    if(TransactionId != ""){
                        SYSTEM.tpinVerify(function(pin){
                        form.find('[name="pin"]').val(pin);
                            SYSTEM.FORMSUBMIT(form, function(data){
                                form.find('[name="pin"]').val("");
                                if (!data.statusText) {
                                    if(data.statuscode == "TXN"){
                                        form[0].reset();
                                        form.find('select').select2().val(null).trigger('change');
                                        getbalance();
                                        form.find('button[type="submit"]').button('reset');
                                        notify("Billpayment Successfully Submitted", 'success');

                                        $('#receipt').find('.date').text(data.date);
                                        $('#receipt').find('.amount').text(data.amount);
                                        $('#receipt').find('.refno').text(data.refno);
                                        $('#receipt').find('.number').text(data.number);
                                        $('#receipt').find('.txnid').text(data.txnid);
                                        $('#receipt').find('.provider').text(data.provider);
                                        $('#receipt').find('.biller').text(data.biller);
                                        $('#receipt').find('.status').text(data.status);
                                        $('#receipt').modal();
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
                                            data.message == "Transaction Pin is incorrect" 
                                        ){
                                            $.alert({
                                                title: 'Oops!',
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
                        notify("Biller fetching required", 'error');
                        getname("button", "billpayForm");
                    }
                }
            });
        });

        function SETTITLE(type, form, mode) {
            $("#"+form).find('.billdata').empty();
            $.ajax({
                url: "{{route('getoperators')}}",
                type: 'post',
                dataType: 'json',
                data : {"type" : type, "mode" : mode},
                headers: {
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
                showError(errors, $("#"+form));
            });
        }

        function GET_PROVIDER(form) {
            $("#"+form).find('.billdata').empty();
            var providerid = $("#"+form).find('[name="provider_id"]').val();
            if(providerid != ''){
                $.ajax({
                    url: "{{route('getprovider')}}",
                    type: 'post',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend:function(){
                        swal({
                            title: 'Wait!',
                            text: 'We are fetching operators details',
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
                    $("#"+form).find('.billdata').empty();
                    var paramcount = data.paramname.length;
                    PARAMETER.PARAMCOUNT = paramcount;
                    PARAMETER.NUMBER = "number"+(paramcount-1);

                    $.each(data.paramname, function(i,val) {
                        var html =   '';
                        html     +=  '<div class="form-group col-md-4">';
                        html     +=  '<label>Enter '+data.paramname[i]+'</label>';
                        html     +=  `<input type="text" name="number`+i+`" class="form-control" placeholder="Enter value" required="">`;
                        html     +=  '</div>';
                        $("#"+form).find('.billdata').append(html);
                    });
                })
                .fail(function(errors) {
                    swal.close();
                    showError(errors, $("#"+form));
                });
            }
        }

        function getname(type="none", form) {
            var params = {
                "provider_id" : $("#"+form).find('[name="provider_id"]').val()
            };

            for (var i = 0; i < PARAMETER.PARAMCOUNT; i++) {
                var key = "number"+i;
                params[key] = $("#"+form).find("[name='"+key+"']").val();

                if(type != "none"){
                    if($("#"+form).find("[name='"+key+"']").val() == ""){
                        notify("Biller number fields required", 'error');
                        return false;
                    }
                }
            }

            if(PARAMETER.NUMBER != "number"+(PARAMETER.PARAMCOUNT-1)){
                return false;
            }

            console.log(PARAMETER);

            var url = "{{route('billpayFetch')}}";

            if($("#"+form).find('[name="provider_id"]').val() != ''){
                $.ajax({
                    url: url,
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
                    data : params
                })
                .done(function(data) {
                    swal.close();
                    if(data.statuscode == "TXN"){
                        $('#bbpspayForm').find('.billdata').find(".amount").closest("div.form-group").remove();
                        $('#billpayForm').find('[name="TransactionId"]').val(data.data.TransactionId);
                        $('#billpayForm').find('[name="biller"]').val(data.data.customername);
                        $('#billpayForm').find('[name="duedate"]').val(data.data.duedate);
                        $('#billpayForm').find('.billdata').append(`
                            <div class="form-group col-md-4">
                                <label>Due Amount</label>
                                <input type="text" class="form-control amount" name="amount" placeholder="Enter amount" required="">
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
                notify("Please select bill operator", 'error');
            }
        }
    </script>
@endpush