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
                    <h4 class="panel-title">Payout Banks</h4>
                </div>
                <table class="table table-bordered table-striped table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th width="160px">#</th>
                            <th>User Details</th>
                            <th>Bank Details</th>
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
@endsection

@push('style')

@endpush

@push('script')
<script type="text/javascript">
    $(document).ready(function () {
        $('[name="dataType"]').val("payoutaccount");
        var url = "{{url('statement/list/fetch')}}/payoutaccount/0";;

        var onDraw = function() {};
        var options = [
            { "data" : "name",
                render:function(data, type, full, meta){
                    var out = '';
                    out += `<span class='text-inverse'>`+full.id +`</span><br><span style='font-size:12px'>`+full.created_at+`</span>`;
                    return out;
                }
            },
            { "data" : "username"},
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return "Name: "+full.name+"<br>Account : "+full.account+"<br>Bank : "+full.bank+"<br>Ifsc : "+full.ifsc;
                }
            },
            { 
                "data": "action",
                render:function(data, type, full, meta){
                    return "";
                }
            }
        ];

        datatableSetup(url, options, onDraw);
    });
</script>
@endpush