@extends('layouts.app')
@section('title', "Transaction History")
@section('pagetitle', "Transaction History")

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
$product['data']["bank"] = "Move to bank";
$product['type'] = "Type";
break;
}

$status['type'] = "Report";
$status['data'] = [
"initiated"=> "Initiated",
"incomplete"=> "Incomplete",
"success" => "Success",
"pending" => "Pending",
"refund_pending" => "Refund Pending",
"reversed" => "Reversed",
"refunded" => "Refunded",
"failed" => "Failed"
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
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="datatable">
                        <thead>
                            <tr style="white-space: nowrap;">
                                <th>S.No.</th>
                                <th>Action</th>
                                <th>Type</th>
                                <th>Customer Name</th>
                                <th>Request ID</th>
                                @if(in_array($type, ["pancard"]))
                                <th>Acknowledge No</th>
                                <th>Name</th>
                                @else
                                <th>Number</th>
                                @endif
                                <th>Transaction ID</th>
                                <th>Pan Number</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Acknowledge No</th>
                                <th>Remark</th>
                                <th>SSOID</th>
                                <th>Kiosk Code</th>
                                <th>Kiosk Name</th>
                                <th>Kiosk Address</th>
                                <th>Kiosk Mob.</th>
                                <th>Kiosk Email</th>
                                <th>District</th>
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
                                    @php $company = Auth::User()->company; @endphp
                                    @if($company && $company->logo)
                                    <img src="{{ asset('public/' . $company->logo) }}" class="img-responsive" alt="" style="height: 75px;">
                                    @elseif($company)
                                    {{ $company->companyname }}
                                    @else
                                    <span>No company linked</span>
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
                                <table class="table m-t-10 default">
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

<div id="viewReport" class="modal fade right" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <h6 class="modal-title">View Report</h6>
            </div>
            <div class="modal-body p-10">
                <div class="panel invoice-grid timeline-content">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h6 class="text-semibold no-margin-top title text-capitalize"></h6>
                                <ul class="list list-unstyled">
                                    <li>Product : <span class="product text-capitalize"></span></li>
                                    <li>Amount : <span class="amount"></span></li>
                                </ul>
                            </div>

                            <div class="col-sm-6">
                                <h6 class="text-semibold text-right no-margin-top created_at text-capitalize"></h6>
                                <ul class="list list-unstyled text-right">
                                    <li>Status : <span class="status text-capitalize"></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive aeps">
                                <h5 style="background-color: #d4ebe8;">Transaction Details :</h5>
                                <hr>
                                <table class="table m-t-10 table-borderless">
                                    <tbody class="text-muted">
                                        <tr>
                                            <td class="pull-left">Aadhar Number</td>
                                            <td class="number"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Mobile</td>
                                            <td class="mobile"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Bank</td>
                                            <td class="option3"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Remark</td>
                                            <td class="option3"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive dmt">
                                <h5 style="background-color: #d4ebe8;">Beneficiary Details :</h5>
                                <table class="table m-t-10 table-borderless">
                                    <tbody class="text-muted">
                                        <tr>
                                            <td class="pull-left">Remitter</td>
                                            <td class="mobile"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Name</td>
                                            <td class="option2"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Account</td>
                                            <td class="number"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Bank</td>
                                            <td class="option3"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">IFSC</td>
                                            <td class="option4"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br>
                                <h5 style="background-color: #d4ebe8;">Remitter Details :</h5>
                                <table class="table m-t-10 table-borderless">
                                    <tbody class="text-muted">
                                        <tr>
                                            <td class="pull-left">Remitter Number</td>
                                            <td class="description"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Remitter Name</td>
                                            <td class="option2"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive recharge">
                                <h5 style="background-color: #d4ebe8;">Transaction Details :</h5>
                                <table class="table m-t-10 table-borderless">
                                    <tbody class="text-muted">
                                        <tr>
                                            <td class="pull-left">Number</td>
                                            <td class="number"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Provider</td>
                                            <td class="providername"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive matm">
                                <h5 style="background-color: #d4ebe8;">Transaction Details :</h5>
                                <table class="table m-t-10 table-borderless">
                                    <tbody class="text-muted">
                                        <tr>
                                            <td class="pull-left">Card No.</td>
                                            <td class="number"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Mobile</td>
                                            <td class="mobile"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Bank</td>
                                            <td class="option3"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Remark</td>
                                            <td class="remark"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive billpay">
                                <h5 style="background-color: #d4ebe8;">Transaction Details :</h5>
                                <table class="table m-t-10 table-borderless">
                                    <tbody class="text-muted">
                                        <tr>
                                            <td class="pull-left">Number</td>
                                            <td class="number"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Provider</td>
                                            <td class="providername"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Name</td>
                                            <td class="option1"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Duedate</td>
                                            <td class="option2"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive pancard">
                                <h5 style="background-color: #d4ebe8;">Transaction Details :</h5>
                                <table class="table m-t-10 table-borderless">
                                    <tbody class="text-muted">
                                        <tr>
                                            <td class="pull-left">Number</td>
                                            <td class="number"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Provider</td>
                                            <td class="providername"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">No Of Token</td>
                                            <td class="option1"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <h5 style="background-color: #d4ebe8;">Reference Details :</h5>
                                <hr>
                                <table class="table m-t-10 table-borderless">
                                    <tbody class="text-muted">
                                        <tr>
                                            <td class="pull-left">Reference/ Utr.</td>
                                            <td class="refno"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Transaction Id</td>
                                            <td class="txnid"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Pay ID</td>
                                            <td class="payid"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Amount</td>
                                            <td class="amount"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <h5 style="background-color: #d4ebe8;">Order Details :</h5>
                                <hr>
                                <table class="table m-t-10 table-borderless">
                                    <tbody class="text-muted">
                                        <tr>
                                            <td class="pull-left">Order Id</td>
                                            <td class="id"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Product</td>
                                            <td class="product"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Created At</td>
                                            <td class="created_at"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Status</td>
                                            <td class="status"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <h5 style="background-color: #d4ebe8;">Payment Details :</h5>
                                <hr>
                                <table class="table table-borderless">
                                    <tbody class="text-muted">
                                        <tr>
                                            <td class="pull-left">Profit</td>
                                            <td class="profit"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Charge</td>
                                            <td class="charge"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Tds</td>
                                            <td class="tds"></td>
                                        </tr>
                                        <tr>
                                            <td class="pull-left">Gst</td>
                                            <td class="gst"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('style')
    <style>
        td {
            white-space: nowrap;
            max-width: 100%;
            size: 10px;
            font-size: 13px;
            text-align: center;
        }

        th {
            text-align: center;
        }

        .label {
            font-size: 10px;
            display: flex;
        }
    </style>

    @endpush

    @push('script')
    <script type="text/javascript">
        var DT;
        $(document).ready(function() {
            $('[name="dataType"]').val("{{$type}}");

            @if(isset($id) && $id != 0)
            $('form#searchForm').find('[name="agent"]').val("{{$id}}");
            @endif

            var url = "{{route('reportstatic')}}";
            var onDraw = function() {
                $('[data-popup="tooltip"]').tooltip();

                $('.print').click(function(event) {
                    var data = DT.row($(this).parent().parent().parent().parent().parent()).data();

                    $.each(data, function(index, values) {
                        $("." + index).text(values);
                    });
                    $('address.dmt').hide();
                    $('address.aeps').hide();
                    $('address.default').hide();

                    if (data['product'] == "dmt") {
                        $('address.dmt').show();
                    } else if (data['product'] == "aeps") {
                        $('address.aeps').show();
                    } else {
                        $('address.default').show();
                    }
                    $('#receipt').modal();
                });

                $('.viewreport').click(function(event) {
                    var data = DT.row($(this).parent().parent()).data();
                    $.each(data, function(index, values) {
                        $("." + index).text(values);
                    });

                    $('div.dmt').hide();
                    $('div.aeps').hide();
                    $('div.recharge').hide();
                    $('div.matm').hide();
                    $('div.billpay').hide();
                    $('div.pancard').hide();
                    $('div.default').hide();

                    if (data['product'] == "dmt") {
                        $('div.dmt').show();
                    } else if (data['product'] == "aeps") {
                        $('div.aeps').show();
                    } else if (data['product'] == "recharge") {
                        $('div.recharge').show();
                    } else if (data['product'] == "matm") {
                        $('div.matm').show();
                    } else if (data['product'] == "billpay" || data['product'] == "offlinebillpay") {
                        $('div.billpay').show();
                    } else if (data['product'] == "pancard") {
                        $('div.pancard').show();
                    } else {
                        $('div.default').show();
                    }
                    $('#viewReport').modal();
                });
            };
            var options = [{
                    "data": "id"
                },
                {
                    "data": "bank",
                    render: function(data, type, full, meta) {
                        var menu = `<li class="dropdown-header">Action</li>`;
                        menu += `<li><a href="javascript:void(0)" class="print"><i class="icon-info22"></i>Print Invoice</a></li>`;
                        if (full.status == "refund") {
                            menu += `<li class="dropdown-header">Get Refund</li>
                            <li><a href="javascript:void(0)" onclick="getrefund(` + full.id + `)"><i class="icon-info22"></i>Get Refund</a></li>`;
                        }

                        if (full.status == "accept" || full.status == "pending" || full.status == "initiated" || full.status == "refund_pending") {
                            menu += `<li><a href="javascript:void(0)" onclick="status(` + full.id + `, '` + full.product + `')"><i class="icon-info22"></i>Check Status</a></li>`;
                        }

                        if (full.status == "success" || full.status == "accept" || full.status == "pending" || full.status == "reversed" || full.status == "refund_pending") {
                            menu += `<li><a href="javascript:void(0)" onclick="complaint(` + full.id + `, '` + full.product + `')"><i class="icon-cogs"></i> Complaint</a></li>`;
                        }

                        @if(Myhelper::can(["recharge_statement_edit", "billpay_statement_edit", "pancard_statement_edit", "dmt_statement_edit", "adminaeps_statement_edit"]))
                        if (full.status == "incomplete" || full.status == "pending" || full.status == "failed" || full.status == "initiated" || full.status == "refund_pending" || full.status == "refunded") {
                            menu += `<li><a href="javascript:void(0)" onclick="editReport(` + full.id + `,'` + full.refno + `','` + full.txnid + `','` + full.payid + `', '` + full.status + `', '` + full.product + `')"><i class="icon-pencil5"></i> Edit</a></li>`;
                        }
                        @endif

                        return `<ul class="icons-list mr-5">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-left">
                                        ` + menu + `
                                    </ul>
                                </li>
                            </ul>`;
                    }
                },
                {
                    "data": "option6",
                    render: function(data, type, full, meta) {
                        if (full.option6 == "A") {
                            return "NEW PAN";
                        } else if (full.option6 == "CR") {
                            return "CORRECTION";
                        } else {
                            return full.product;
                        }
                    }
                },
                @if(in_array($type, ["directpancard"])) {
                    "data": "option4"
                },
                @else {
                    "data": "option1"
                },
                @endif {
                    "data": "bank",
                    render: function(data, type, full, meta) {
                        if (full.product == "matm" && full.status == "failed") {
                            var out = `<a href="javascript:void(0)" class="viewreport" data-popup="tooltip" title="" data-original-title="View report" >` + full.txnid + `</a>`;
                        } else {
                            var out = `<a href="javascript:void(0)" class="viewreport" data-popup="tooltip" title="" data-original-title="View report">` + full.txnid + `</a>`;
                        }

                        return out;
                    }
                },
                @if(in_array($type, ["pancard"])) {
                    "data": "refno"
                },
                {
                    "data": "option4"
                },
                @else {
                    "data": "number"
                },
                @endif {
                    "data": "payid"
                },
                {
                    "data": "option7"
                },
                {
                    "data": "bank",
                    render: function(data, type, full, meta) {
                        var out = "";
                        if (full.status == "success") {
                            var out = `<span class="label label-success">Success</span>`;
                        } else if (full.status == "accept") {
                            var out = `<span class="label label-info">Accept</span>`;
                        } else if (full.status == "pending") {
                            var out = `<span class="label label-warning">Pending</span>`;
                        } else if (full.status == "reversed" || full.status == "refunded") {
                            var out = `<span class="label bg-slate">` + full.status + `</span>`;
                        } else {
                            var out = `<span class="label label-danger">` + full.status + `</span>`;
                        }

                        return out;
                    }
                },
                {
                    "data": "amount"
                },
                {
                    "data": "created_at"
                },
                {
                    "data": "refno"
                },
                {
                    "data": "bank",
                    render: function(data, type, full, meta) {
                        return `<div>
                    		<span class='text-inverse m-l-10' style="display: flex;">` + full.remark + `</span>
                            <div class="clearfix"></div>
                            </div>`;
                    }
                },
                {
                    "data": "bank",
                    render: function(data, type, full, meta) {
                        try {
                            var jsonData = JSON.parse(full.option10);
                            if (jsonData && jsonData.SSOID) {
                                return jsonData.SSOID;
                            } else {
                                return ''; // Return empty string or handle the case when property doesn't exist
                            }
                        } catch (error) {
                            console.error('Error parsing JSON:', error);
                            return '';
                        }
                    }
                },
                {
                    "data": "bank",
                    render: function(data, type, full, meta) {
                        try {
                            var jsonData = JSON.parse(full.option10);
                            if (jsonData && jsonData.KIOSKCODE) {
                                return jsonData.KIOSKCODE;
                            } else {
                                return '';
                            }
                        } catch (error) {
                            console.error('Error parsing JSON:', error);
                            return '';
                        }
                    }
                },
                {
                    "data": "bank",
                    render: function(data, type, full, meta) {
                        try {
                            var jsonData = JSON.parse(full.option10);
                            if (jsonData && jsonData.KIOSKNAME) {
                                return jsonData.KIOSKNAME;
                            } else {
                                return '';
                            }
                        } catch (error) {
                            console.error('Error parsing JSON:', error);
                            return '';
                        }
                    }
                },
                {
                    "data": "bank",
                    render: function(data, type, full, meta) {
                        try {
                            var jsonData = JSON.parse(full.option10);
                            if (jsonData && jsonData.TEHSIL) {
                                return jsonData.TEHSIL;
                            } else {
                                return '';
                            }
                        } catch (error) {
                            console.error('Error parsing JSON:', error);
                            return '';
                        }
                    },
                },
                {
                    "data": "bank",
                    render: function(data, type, full, meta) {
                        try {
                            var jsonData = JSON.parse(full.option10);
                            if (jsonData && jsonData.MOBILE) {
                                return jsonData.MOBILE;
                            } else {
                                return '';
                            }
                        } catch (error) {
                            console.error('Error parsing JSON:', error);
                            return '';
                        }
                    }
                },
                {
                    "data": "bank",
                    render: function(data, type, full, meta) {
                        try {
                            var jsonData = JSON.parse(full.option10);
                            if (jsonData && jsonData.EMAIL) {
                                return jsonData.EMAIL;
                            } else {
                                return '';
                            }
                        } catch (error) {
                            console.error('Error parsing JSON:', error);
                            return '';
                        }
                    }
                },
                {
                    "data": "bank",
                    render: function(data, type, full, meta) {
                        try {
                            var jsonData = JSON.parse(full.option10);
                            if (jsonData && jsonData.DISTRICT) {
                                return jsonData.DISTRICT;
                            } else {
                                return '';
                            }
                        } catch (error) {
                            console.error('Error parsing JSON:', error);
                            return '';

                        }
                    }
                }

            ];

            DT = datatableSetup(url, options, onDraw);
        });


        function linkmaker() {
            // alert('OK');
            const toDateValue = document.querySelector('input[name="to_date"]').value;
            alert(toDateValue)
            window.location = `https://rajpay.in/pancardClear?data=allnsdl&date=${toDateValue}`;
        }
    </script>
    @endpush