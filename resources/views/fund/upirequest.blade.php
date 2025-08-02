@extends('layouts.app')
@section('title', "Scan & Load Money")
@section('pagetitle',  "Scan & Load Money")

@php
    $table = "yes";

    $status['type'] = "Fund";
    $status['data'] = [
        "success" => "Success",
        "pending" => "Pending",
        "failed" => "Failed",
        "approved" => "Approved",
        "rejected" => "Rejected",
    ];


    if(!$qrcode){
        $search = "hide";
    }
@endphp

@section('content')
<div class="content">
    @if(!$qrcode)
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Create VPA</h4>
                    </div>
                    <form action="{{route('fundtransaction')}}" method="post" id="fingkycForm">
                        <div class="panel-body"> 
                            {{ csrf_field() }}
                            <input type="hidden" name="type" value="qrcode">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Name</label>
                                    <input type="text" class="form-control" autocomplete="off" name="merchant_name" placeholder="Enter Name" value="{{Auth::user()->name}}" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Mobile</label>
                                    <input type="text" pattern="[0-9]*" maxlength="10" minlength="10" class="form-control" name="mobile" autocomplete="off" placeholder="Enter Your Mobile" value="{{Auth::user()->mobile}}" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Pancard</label>
                                    <input type="text" class="form-control" name="pan" autocomplete="off" placeholder="Enter pancard" value="{{Auth::user()->pancard}}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Account Number </label>
                                    <input type="text" class="form-control" autocomplete="off" name="acc_no" placeholder="Enter value" required>
                                </div>   
                                <div class="form-group col-md-4">
                                    <label>Ifsc Code </label>
                                    <input type="text" class="form-control" autocomplete="off" name="ifsccode" placeholder="Enter ifsc" required>
                                </div>   
                                <div class="form-group col-md-4">
                                    <label>Address </label>
                                    <input type="text" class="form-control" autocomplete="off" name="address" placeholder="Enter Address" value="{{Auth::user()->address}}" required>
                                </div>   
                            </div>

                             <div class="row">
                                <div class="form-group col-md-4">
                                    <label>City</label>
                                    <input type="text" class="form-control" autocomplete="off" name="city"  value="{{Auth::user()->city}}" placeholder="Enter Your City" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>State</label>
                                    <input type="text" class="form-control" autocomplete="off" name="state"  value="{{Auth::user()->state}}" placeholder="Enter state" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Pin Code </label>
                                    <input type="text" class="form-control" autocomplete="off" name="pincode" maxlength="6" minlength="6" pattern="[0-9]*"  value="{{Auth::user()->pincode}}" placeholder="Enter Merchant Pincode" required>
                                </div>
                            </div>
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
            @if($qrcode)
                <div class="col-sm-4">
                    <div class="panel border-left-lg border-left-success invoice-grid timeline-content">
                        <div class="panel-body text-center">
                            <div class="qrimage"></div>
                            <h4 class="pn">{{$qrcode->merchant_name}}</h4>
                            <h5 class="vpa">{{$qrcode->vpa}}</h5>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Scan & Load Money</h4>
                        <div class="heading-elements">
                            <button type="button" data-toggle="modal" data-target="#fundRequestModal" class="btn bg-slate btn-xs btn-labeled legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Searching"><b><i class="icon-plus2"></i></b> New Qr Code</button>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-hover" id="datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Deposit Bank Details</th>
                                <th>Refrence Details</th>
                                <th>Amount</th>
                                <th>Remark</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>


<div id="fundRequestModal" class="modal fade" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Scan & Pay</h6>
            </div>
            <form id="fundRequestForm" action="{{route('fundtransaction')}}" method="post">
                <div class="modal-body">
                    <input type="hidden" name="user_id">
                    <input type="hidden" name="type" value="qrcode_dynamic">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Amount</label>
                            <input type="number" name="amount" step="any" class="form-control" placeholder="Enter Amount" required="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn bg-slate btn-raised legitRipple" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@push('style')

@endpush

@push('script')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script> 
<script type="text/javascript" src="{{asset('')}}assets/js/core/sweetalert2.all.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        jQuery(".qrimage").qrcode({
            width  : 200,
            height : 200,
            text: "{{$qrcode->qr_lLink ?? ''}}"
        });

        var url = "{{url('statement/list/fetch')}}/upirequest/0";
        var onDraw = function() {};
        var options = [
            { "data" : "name",
                render:function(data, type, full, meta){
                    return `<span class='text-inverse m-l-10'><b>`+full.id +`</b> </span><br>
                        <span style='font-size:13px'>`+full.updated_at+`</span>`;
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return `Name - `+full.fundbank.name+`<br>Account No. - `+full.fundbank.account+`<br>Branch - `+full.fundbank.branch;
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    var slip = '';
                    if(full.payslip){
                        var slip = `<a target="_blank" href="{{asset('public')}}/deposit_slip/`+full.payslip+`">Pay Slip</a>`
                    }
                    return `Ref No. - `+full.ref_no+`<br>Paydate - `+full.paydate+`<br>Paymode - `+full.paymode+` ( `+slip+` )`;
                }
            },
            { "data" : "amount"},
            { "data" : "remark"},
            { "data" : "action",
                render:function(data, type, full, meta){
                    var out = '';
                    if(full.status == "approved"){
                        out += `<label class="label label-success">Approved</label>`;
                    }else if(full.status == "pending"){
                        out += `<label class="label label-warning">Pending</label>`;
                    }else if(full.status == "rejected"){
                        out += `<label class="label label-danger">Rejected</label>`;
                    }

                    return out;
                }
            }
        ];

        datatableSetup(url, options, onDraw);

        $( "#fundRequestForm").validate({
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
                var form = $('#fundRequestForm');
                form.ajaxSubmit({
                    dataType:'json',
                    beforeSubmit:function(){
                        form.find('button:submit').button('loading');
                    },
                    complete: function () {
                        form.find('button:submit').button('reset');
                    },
                    success:function(data){
                        if(data.statuscode == "TXN"){
                            form.closest('.modal').modal('hide');
                            Swal.fire({
                                title: 'Scan & Pay',
                                html: `<div class="qrimage"></div>
                                        <h4 class="pn"></h4>
                                        <h5 class="vpa"></h5>`,
                                footer: 'Please do not hit back button untill complete payment',
                                timer: 300000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading()
                                    $('.pn').text(data.pn);
                                    $('.vpa').text(data.vpa);
                                    jQuery(".qrimage").qrcode({
                                        width  : 250,
                                        height : 250,
                                        text: data.qr_link
                                    });
                                },
                                willClose: () => {
                                    //clearInterval(timerInterval)
                                },
                                showConfirmButton: false,
                                allowOutsideClick: () => false
                            });
                        }else{
                            notify(data.message , 'warning');
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });

        $( "#fingkycForm").validate({
            rules: {
                merchant_name: {
                    required: true
                }
            },
            messages: {
                merchant_name: {
                    required: "Please enter value",
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
                var form = $('#fingkycForm');
                form.ajaxSubmit({
                    dataType:'json',
                    beforeSubmit:function(){
                        form.find('button:submit').button('loading');
                    },
                    complete: function () {
                        form.find('button:submit').button('reset');
                    },
                    success:function(data){
                        if(data.statuscode == "TXN"){
                            form.closest('.modal').modal('hide');
                            notify("Request submitted Successfull", 'success');
                            $('#datatable').dataTable().api().ajax.reload();
                        }else{
                            notify(data.message , 'warning');
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });
    });

    function fundRequest(id = "none"){
        if(id != "none"){
            $('#fundRequestForm').find('[name="fundbank_id"]').select2().val(id).trigger('change');
        }
        $('#fundRequestModal').modal();
    }
</script>
@endpush