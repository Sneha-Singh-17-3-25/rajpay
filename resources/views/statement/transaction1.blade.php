@extends('layouts.app')
@section('title', "Transaction History")
@section('pagetitle',  "Transaction History")

@php
    $table = "yes";
    $export = "recharge";

    switch ($type) {
        case 'recharge':
            $billers = App\Model\Provider::whereIn('type', ['mobile', 'dth'])->get(['id', 'name']);
            foreach ($billers as $item){
                $product['data'][$item->id] = $item->name;
            }
            $product['type'] = "Operator";
            break;

        case 'billpay':
            $billers = App\Model\Provider::whereIn('type', ["electricity","gas","lpg","landline","postpaid","broadband","loanrepay","fasttag","cable","insurance",'water'])->get(['id', 'name']);
            foreach ($billers as $item){
                $product['data'][$item->id] = $item->name;
            }
            $product['type'] = "Operator";
            break;

        case 'payout':
            $product['data']["wallet"] = "Move to wallet";
            $product['data']["bank"]   = "Move to bank";
            $product['type'] = "Type";
            break;
        
        default:
            $billers = App\Model\Api::get(['id', 'name']);
            foreach ($billers as $item){
                $product['data'][$item->id] = $item->name;
            }
            $product['type'] = "Api";
            break;
    }

    $status['type'] = "Report";
    $status['data'] = [
        "initiated"=> "Initiated",
        "success"  => "Success",
        "pending"  => "Pending",
        "reversed" => "Reversed",
        "refunded" => "Refunded",
        "failed"   => "Failed"
    ];
@endphp

@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Transaction History</h4>
                </div>
                 (hello)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="datatable">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Order ID</th>
                                <th>User Details</th>
                                <th>Transaction Details</th>
                                <th>Refrence Details</th>
                                <th>Amount</th>
                                @if(Myhelper::hasRole(["admin", "whitelable", 'md', 'distributor']))
                                    <th>Commission</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
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
                                <th style="padding: 10px 0px;">
                                    @if(Auth::user()->company->logo)
                                        <img src="{{asset('')}}public/{{$mydata['company']->logo}}" class=" img-responsive" alt="" style="height: 75px;">
                                    @else
                                        {{Auth::user()->company->companyname}}
                                    @endif 
                                </th>
                                <th style="padding: 10px 0px;  text-align: right;">Receipt - <span class="created_at"></span></th>
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
                                    <address class="m-b-10 default">
                                        <strong>Consumer Name : </strong> <span class="option1"></span><br>
                                        <strong>Operator Name : </strong> <span class="providername"></span><br>
                                        <strong>Operator Number : </strong> <span class="number"></span>
                                    </address>

                                    <address class="m-b-10 dmt">
                                        <strong>Name : </strong> <span class="option2"></span><br>
                                        <strong>Account : </strong> <span class="number"></span><br>
                                        <strong>Bank : </strong> <span class="option3"></span>
                                    </address>

                                    <address class="m-b-10 aeps">
                                        <strong>Aadhar Number : </strong> <span class="number"></span><br>
                                        <strong>Account Balance : </strong> <span class="option6"></span><br>
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
                                            <th>Txn Id</th>
                                            <th>Amount</th>
                                            <th>Ref. No.</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="txnid"></td>
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
                            <h6 class="text-right">Amount : <span class="amount"></span></h6>
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

@push('style')

@endpush

@push('script')
<script type="text/javascript">
    var DT;
    $(document).ready(function () {
        $('[name="dataType"]').val("{{$type}}");

        @if(isset($id) && $id != 0)
            $('form#searchForm').find('[name="agent"]').val("{{$id}}");
        @endif

        var url = "{{route("reportstatic")}}";
        var onDraw = function() {
            $('.print').click(function(event) {
                var data = DT.row($(this).parent().parent().parent().parent().parent()).data();
                $.each(data, function(index, values) {
                    $("."+index).text(values);
                });
                $('address.dmt').hide();
                $('address.aeps').hide();
                $('address.default').hide();

                if(data['product'] == "dmt"){
                    $('address.dmt').show();
                }else if(data['product'] == "aeps"){
                    $('address.aeps').show();
                }else{
                    $('address.default').show();
                }
                $('#receipt').modal();
            });
        };
        var options = [
            { "data" : "status",
                render:function(data, type, full, meta){
                    if(full.status == "success"){
                        var out = `<span class="label label-success">Success</span>`;
                    }else if(full.status == "accept"){
                        var out = `<span class="label label-info">Accept</span>`;
                    }else if(full.status == "pending"){
                        var out = `<span class="label label-warning">Pending</span>`;
                    }else if(full.status == "reversed" || full.status == "refunded"){
                        var out = `<span class="label bg-slate">`+full.status+`</span>`;
                    }else{
                        var out = `<span class="label label-danger">`+full.status+`</span>`;
                    }

                    var menu = `<li class="dropdown-header">Action</li>`;
                    menu += `<li><a href="javascript:void(0)" class="print"><i class="icon-info22"></i>Print Invoice</a></li>`;
                    if(full.status == "refund"){
                        menu += `<li class="dropdown-header">Get Refund</li>
                            <li><a href="javascript:void(0)" onclick="getrefund(`+full.id+`)"><i class="icon-info22"></i>Get Refund</a></li>`;
                    }

                    if(full.status == "success" || full.status == "accept" || full.status == "pending" || full.status == "initiated"){
                        menu += `<li><a href="javascript:void(0)" onclick="status(`+full.id+`, '`+full.product+`')"><i class="icon-info22"></i>Check Status</a></li>`;
                    }

                    if(full.status == "success" || full.status == "accept" || full.status == "pending" || full.status == "reversed"){
                        menu += `<li><a href="javascript:void(0)" onclick="complaint(`+full.id+`, '`+full.product+`')"><i class="icon-cogs"></i> Complaint</a></li>`;
                    }

                    @if(Myhelper::can(["recharge_statement_edit", "billpay_statement_edit", "pancard_statement_edit", "dmt_statement_edit", "adminaeps_statement_edit"]))
                        if(full.status == "success" || full.status == "accept" || full.status == "pending" || full.status == "failed" || full.status == "initiated"){
                            
                            menu += `<li><a href="javascript:void(0)" onclick="editReport(`+full.id+`,'`+full.refno+`','`+full.txnid+`','`+full.payid+`','`+full.remark+`', '`+full.status+`', '`+full.product+`')"><i class="icon-pencil5"></i> Edit</a></li>`;
                        }
                    @endif
                    
                    out +=  `<ul class="icons-list">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-left">
                                        `+menu+`
                                    </ul>
                                </li>
                            </ul>`;

                    return out;
                }
            },
            { "data" : "name",
                render:function(data, type, full, meta){
                    return `<div>
                            <span class=''>`+full.apiname+ ` (`+full.via+`)` +`</span><br>
                            <span class='text-inverse m-l-10'>SN : <b>`+full.id +`</b> </span>
                            <div class="clearfix"></div>
                        </div><span style='font-size:13px' class="pull=right">`+full.created_at+`</span>`;
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return full.username+"<br>"+full.usermobile+"<br>"+full.useragentcode+"<br>"+full.userrole;
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    if(full.product == "aeps"){
                        return "Aadhar No. : "+full.number + "<br>Mobile : "+full.mobile+"<br>Bank : "+full.option3 + "<br>Remark : "+full.remark;
                    }else if(full.product == "matm"){
                        return "Card No. : "+full.number + "<br>Mobile : "+full.mobile+"<br>Bank : "+full.option3 + "<br>Remark : "+full.remark;
                    }else if(full.product == "dmt"){
                        return "Beneficiary : "+full.option2+"<br>Account : "+full.number+"<br>Bank: "+full.option3+"<br>Ifsc: "+full.option4+"<br>Sender: "+full.mobile + " ("+full.option1+")";
                    }else if(full.option1 == "bank"){
                        return "Name : "+full.description+"<br>Account : "+full.number+"<br>Bank : "+full.option3+"<br>Ifsc: "+full.option2;
                    }else if(full.product == "billpay" || full.product == "offlinebillpay"){
                        return "Number : "+full.number+"<br>Provider: "+full.providername+"<br>Name: "+full.option1+"<br>Duedate: "+full.option2;
                    }else if(full.product == "pancard"){
                        return "Number : "+full.number+"<br>Provider: "+full.providername+"<br>No Of Token: "+full.option1;
                    }else{
                        return "Number : "+full.number+"<br>Provider: "+full.providername;
                    }
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    if(full.product == "matm" && full.status == "failed"){
                        return "Reference / Utr: "+full.refno+"<br>Txn Id : "+full.txnid+"<br>Remark : "+full.remark;
                    }else{
                        return "Reference / Utr: "+full.refno+"<br>Txn Id : "+full.txnid+"<br>Pay Id : "+full.payid;
                    }
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    if(full.product == "dmt"){
                        return "Amount : "+full.amount+"<br>Charge : "+full.charge+"<br>Profit : "+full.profit+"<br>Tds : "+full.tds+"<br>Gst : "+full.gst;
                    }else if(full.option1 == "bank" || full.option1 == "M"){
                        return "Amount : "+full.amount+"<br>Charge : "+full.charge+"<br>Tds : "+full.tds;
                    }else{
                        return "Amount : "+full.amount+"<br>Profit : "+full.profit+"<br>Tds : "+full.tds;
                    }
                }
            }
            @if(Myhelper::hasRole(["admin", "whitelable", 'md', 'distributor']))                        
            ,{ "data" : "bank",
                render:function(data, type, full, meta){
                    var commission = "";

                    @if(Myhelper::hasRole(["admin", "whitelable", 'md', 'distributor']))  
                        commission += "Dis : "+full.disprofit.toFixed(2);
                    @endif

                    @if(Myhelper::hasRole(["admin", "whitelable", 'md']))  
                        commission += "<br>Md : "+full.mdprofit.toFixed(2);
                    @endif

                    @if(Myhelper::hasRole(["admin", "whitelable", 'asm', 'statehead']))  
                        commission += "<br>WL : "+full.wprofit.toFixed(2);
                    @endif

                    return commission;
                }
            }
            @endif
        ];

        DT = datatableSetup(url, options, onDraw);
    });
</script>
@endpush