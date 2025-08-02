
@extends('layouts.app')
@section('title', "Account Ladger")
@section('pagetitle',  "Ladger")

@php
    $table  = "yes";
    $newexport = "yes";
@endphp

@section('content')

<div class="content p-b-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title text-capitalize"><span class="titleName"></span> Statement</h4>
                </div>
                <table class="table table-bordered table-striped table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Refrence Details</th>
                            <th>Provider</th>
                            <th>Txnid</th>
                            <th>ST Type</th>
                            <th>Status</th>
                            <th>Opening Bal.</th>
                            <th style="width: 150px;">Amount</th>
                            <th>Closing Bal.</th>
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
            <h4 class="modal-title">Receipt</h4>
            </div>
            <div class="modal-body">
                <div id="receptTable">
                    <table class="table m-t-10">
                        <thead>
                            <tr>
                                <th style="padding: 10px 0px;"">
                                    @if(Auth::user()->company->logo)
                                        <img src="{{asset('')}}public/{{Auth::user()->company->logo}}" class=" img-responsive" alt="" style="height: 75px;">
                                    @else
                                        {{Auth::user()->company->companyname}}
                                    @endif 
                                </th>
                                <th style="padding: 10px 0px; text-align: right;">Receipt - <span class="created_at"></span></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td style="padding: 10px 0px">
                                    <address class="m-b-10">
                                        <strong>Agent :</strong> <span>{{Auth::user()->name}}</span><br>
                                        <strong>Shop Name :</strong> <span>{{Auth::user()->shopname}}</span><br>
                                        <strong>Phone :</strong> <span>{{Auth::user()->mobile}}</span>
                                    </address>
                                </td>
                                <td style="padding: 10px 0px" class="text-right">
                                    <address class="m-b-10 notdmt">
                                        <strong>Consumer Name : </strong> <span class="option1"></span><br>
                                        <strong>Operator Name : </strong> <span class="providername"></span><br>
                                        <strong>Operator Number : </strong> <span class="number"></span>
                                    </address>

                                    <address class="m-b-10 dmt">
                                        <strong>Name : </strong> <span class="option2"></span><br>
                                        <strong>Account : </strong> <span class="number"></span><br>
                                        <strong>Bank : </strong> <span class="option3"></span>
                                    </address>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <h5>Transaction Details :</h5>
                                <table class="table m-t-10">
                                    <thead>
                                        <tr>
                                            <th style="padding: 10px 0px">Order Id</th>
                                            <th style="padding: 10px 0px">Amount</th>
                                            <th style="padding: 10px 0px">Ref. No.</th>
                                            <th style="padding: 10px 0px">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="id" style="padding: 10px 0px"></td>
                                            <td class="amount" style="padding: 10px 0px"></td>
                                            <td class="refno" style="padding: 10px 0px"></td>
                                            <td class="status" style="padding: 10px 0px"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="border-radius: 0px;">
                        <div class="col-md-6 col-md-offset-6">
                            <h5 class="text-right">Bill Amount : <span class="amount"></span></h5>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn bg-slate btn-raised legitRipple" type="button" id="print"><i class="fa fa-print"></i></button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('/assets/js/core/jQuery.print.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('[name="dataType"]').val("{{$type}}");
        @if(isset($id) && $id != 0)
            $('form#searchForm').find('[name="agent"]').val("{{$id}}");
        @endif

        $('#print').click(function(){
            $('#receptTable').print();
        });
        
        var url = "{{route('reportstatic')}}";
        var onDraw = function() {
            $('.print').click(function(event) {
                var data = DT.row($(this).parent().parent().parent().parent().parent()).data();
                $.each(data, function(index, values) {
                    $("."+index).text(values);
                });

                if(data['product'] == "dmt"){
                    $('address.dmt').show();
                    $('address.notdmt').hide();
                }else{
                    $('address.notdmt').show();
                    $('address.dmt').hide();
                }
                $('#receipt').modal();
            });
        };

        var options = [
            { "data" : "name",
                render:function(data, type, full, meta){
                    var out = "";
                    out += `</a><span style='font-size:13px' class="pull=right">`+full.created_at+`</span>`;
                    return out;
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return full.username+" ("+full.credit_by+")<br>( "+full.usermobile+" )";
                }
            },
            { "data" : "providername"},
            { "data" : "id"},
            { "data" : "rtype"},
            { "data" : "status"},
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return `<i class="fa fa-inr"></i> `+full.balance;
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    var out = '';

                    if(full.product == "aeps" || full.product == "matm"){
                        if(full.trans_type == "credit"){
                            out += `<i class="text-success icon-plus22"></i> `+ (full.amount - full.charge + (full.profit - full.tds)).toFixed(2) + " Cr";
                        }else if(full.trans_type == "debit"){
                            out += `<i class="text-danger icon-dash"></i> `+ (full.amount - full.charge + (full.profit - full.tds)).toFixed(2) + " Dr";
                        }else{
                            out += `<i class="fa fa-inr"></i> `+ (full.amount - full.charge + (full.profit - full.tds)).toFixed(2);
                        }
                    }else{
                        if(full.trans_type == "credit"){
                            out += `<i class="text-success icon-plus22"></i> `+ (full.amount + full.charge - (full.profit - full.tds)).toFixed(2) + " Cr";
                        }else if(full.trans_type == "debit"){
                            out += `<i class="text-danger icon-dash"></i> `+ (full.amount + full.charge - (full.profit - full.tds)).toFixed(2) + " Dr";
                        }else{
                            out += `<i class="fa fa-inr"></i> `+ (full.amount + full.charge - (full.profit - full.tds)).toFixed(2);
                        }
                    }
                    return out;
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    if(full.status == "pending" || full.status == "success" || full.status == "reversed" || full.status == "refunded"){

                        if(full.product == "aeps" || full.product == "matm"){
                            if(full.trans_type == "credit"){
                                return `<i class="fa fa-inr"></i> `+ (full.balance + (full.amount - full.charge + (full.profit - full.tds))).toFixed(2);
                            }else if(full.trans_type == "debit"){
                                return `<i class="fa fa-inr"></i> `+ (full.balance - (full.amount - full.charge + (full.profit - full.tds))).toFixed(2);
                            }
                        }else{
                            if(full.rtype == "main"){
                                if(full.trans_type == "credit"){
                                    return `<i class="fa fa-inr"></i> `+ (full.balance + (full.amount + full.charge - (full.profit - full.tds))).toFixed(2);
                                }else if(full.trans_type == "debit"){
                                    return `<i class="fa fa-inr"></i> `+ (full.balance - (full.amount + full.charge - (full.profit - full.tds))).toFixed(2);
                                }
                            }else{
                                if(full.trans_type == "credit"){
                                    return `<i class="fa fa-inr"></i> `+ (full.balance + (full.amount - full.tds)).toFixed(2);
                                }else if(full.trans_type == "debit"){
                                    return `<i class="fa fa-inr"></i> `+ (full.balance - (full.amount - full.tds)).toFixed(2);
                                }
                            }
                        }
                    }else{
                        return `<i class="fa fa-inr"></i> `+full.balance;
                    }
                }
            },
        ];

        var DT = datatableSetup(url, options, onDraw);
    });

    function SETTITLE(type) {
        $('[name="dataType"]').val(type);
        $(".titleName").text(type);
        $('#datatable').dataTable().api().ajax.reload(null, false);
    }

    function viewUtiid(id){
        $.ajax({
            url: `{{url('statement/list/fetch')}}/utiidstatement/`+id,
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType:'json',
            data:{'scheme_id':id}
        })
        .done(function(data) {
            $.each(data, function(index, values) {
                $("."+index).text(values);
            });
            $('#utiidModal').modal();
        })
        .fail(function(errors) {
            notify('Oops', errors.status+'! '+errors.statusText, 'warning');
        });
    }
</script>
@endpush