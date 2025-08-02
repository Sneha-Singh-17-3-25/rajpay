@extends('layouts.app')
@section('title', "Uti Pancard")
@section('pagetitle', "Uti Pancard")
@php
    $table = "yes";
@endphp

@php
    $search = "hide";
@endphp

@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title pull-left">Uti Pancard</h4>
                    @if (sizeof($vledatas) == 0)
                        <a class="btn bg-slate legitRipple pull-right ml-10" href="javascript:void(0)" onclick="vlerequest()">Request For Vle-id</a>
                    @elseif ($vledatas[0] && $vledatas[0]->status != "success")
                        <button disabled="disabled" class="btn bg-danger pull-right ml-10">Utiid Request is {{$vledatas[0]->status}}, {{$vledatas[0]->remark}}</button>
                    @endif

                    <a class="btn bg-slate legitRipple pull-right" href="http://www.psaonline.utiitsl.com/psaonline/" target="_blank">Login UTI Portal</a>

                    @if(Myhelper::can("apply_utimultiid"))
                        <a class="btn bg-slate legitRipple pull-right m-r-10" href="#" data-toggle="modal" data-target="#addModal">Generate More Vle Id</a>
                    @endif

                    <div class="clearfix"></div>
                </div>
                <div class="panel-body p-0">
                    <table class="table table-bordered">
                        <tr><td>1 Token</td><td>1 PAN Application</td><td>Username</td><td>{{isset($vledatas[0]) ? $vledatas[0]->vleid : ''}}</td><td>Password</td><td>{{isset($vledatas[0]) ? $vledatas[0]->vlepassword : ''}}</td></tr>
                    </table>
                </div>
                <form id="pancardForm" action="{{route('utipay')}}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="pin">
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Vle Id</label>
                                <select name="vleid" class="form-control select" required>
                                    @foreach($vledatas as $vledata)
                                        <option value="{{$vledata->vleid ?? ""}}">{{$vledata->vleid ?? "Select Vleid"}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>No Of Tokens</label>
                                <input type="number" class="form-control" name="tokens" placeholder="Enter No. of tokens" required="">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Total Price in Rs</label>
                                <input type="number" class="form-control" id = "price" name="amount" value = "" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        @if (isset($vledatas[0]) && $vledatas[0]->status == "success")
                            <button type="submit" class="btn bg-teal-400 btn-labeled legitRipple btn-lg pull-right" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Paying"><b><i class=" icon-paperplane"></i></b> Pay Now</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if(Myhelper::can("apply_utimultiid"))
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Uti Id List</h4>
                    </div>
                    
                    <table class="table table-bordered table-striped table-hover" id="utidatatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User Details</th>
                                <th>Vle Details</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Recent Uti Pancard Token</h4>
                </div>
                
                <table class="table table-bordered table-striped table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User Details</th>
                            <th>Transaction Details</th>
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

<div class="footer text-muted">
    <div class="row">
        <div class="col-md-6">
            <h4><strong>Important T&amp;Cs:</strong></h4>
            <ul>
                <li>The fee for processing PAN application is ₹107 inclusive of GST.</li>
                <li>PAN card application can be processed using eKYC or physical documents.</li>
            </ul>
        </div>
        <div class="col-md-6 text-right">
            <div>Powered by</div>
            <img src="{{asset('')}}/assets/images/uti.png" style="position: relative;">
        </div>
    </div>
</div>

@if(Myhelper::can("apply_utimultiid"))
    <div id="addModal" class="modal fade" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-slate">
                    <h5 class="modal-title">New Id Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <form id="idForm" method="post" action="{{ route('vleid') }}">
                    <input type="hidden" name="id" name="new">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Vle Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Value" required="" >
                            </div>
                            <div class="form-group col-md-6">
                                <label>Shop Name</label>
                                <input type="text" class="form-control" name="shopname" placeholder="Enter Value" required="">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Value" required="">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Mobile</label>
                                <input type="text" class="form-control" name="mobile" pattern="[0-9]*" maxlength="10" minlength="10" placeholder="Enter Value" required="">
                            </div>
                            <div class="form-group col-md-6">
                                <label>pancard</label>
                                <input type="text" class="form-control" name="pancard" required="" placeholder="Enter Value" required="">
                            </div>
                            <div class="form-group col-md-6">
                                <label>City</label>
                                <input type="text" class="form-control" name="city" placeholder="Enter Value" required="">
                            </div>
                            <div class="form-group col-md-6">
                                <label>State</label>
                                <input type="text" class="form-control" name="state" placeholder="Enter Value" required="">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Pincode</label>
                                <input type="text" class="form-control" name="pincode" placeholder="Enter Value" required="">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm " data-loading-text="<i class='fa fa-spin fa-spinner'></i> Processing">Submit</button>
                        <button type="button" class="btn btn-warning btn-sm " data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@endsection

@push('script')
    <script type="text/javascript">
    $(document).ready(function () {
        $('[name="dataType"]').val("pancard");
        var url = "{{route("reportstatic")}}";

        var onDraw = function() {
        };

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
                   
                    return full.username+`<br>`+full.mobile;
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return `Vle Id - `+full.number+`<br>Tokens - `+full.option1;
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
                    }else{
                        var out = `<span class="label label-danger">Failed</span>`;
                    }

                    var menu = ``;
                    @if (Myhelper::can('Utipancard_statement_edit'))
                    menu += `<li class="dropdown-header">Setting</li>
                            <li><a href="javascript:void(0)" onclick="editReport(`+full.id+`,'`+full.refno+`','`+full.txnid+`','`+full.payid+`','`+full.remark+`', '`+full.status+`', 'utipancard')"><i class="icon-pencil5"></i> Edit</a></li>`;
                    @endif

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


        var url1 = "{{url('statement/list/fetch')}}/utiidstatement/{{Auth::id()}}";
        var onDraw1 = function() {
        };
        var options1 = [
            { "data" : "name",
                render:function(data, type, full, meta){
                    return `<div>
                            <span class='text-inverse pull-left m-l-10 text-capitalize'><b>`+full.type +`</b> </span>
                            <span class='text-inverse pull-right m-l-10'><b>`+full.id +`</b> </span>
                            <div class="clearfix"></div>
                        </div><span style='font-size:13px' class="pull=right">`+full.created_at+`</span>`;
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return `Name - `+full.name+`<br>Email - `+full.email;
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return `Vle Id - `+full.vleid+`<br>Vle Password -`+full.vlepassword;
                }
            },
            { "data" : "status",
                render:function(data, type, full, meta){
                    if(full.status == "success"){
                        var out = `<span class="label label-success">Success</span>`;
                    }else if(full.status == "pending"){
                        var out = `<span class="label label-warning">Pending</span>`;
                    }else{
                        var out = `<span class="label label-danger">Failed</span>`;
                    }
                    return out;
                }
            }
        ];
        datatableSetup(url1, options1, onDraw1, "#utidatatable");

        $('[name="tokens"]').keyup(function(){
             $("#price").val($(this).val() * 107);
        });

        $( "#pancardForm" ).validate({
            rules: {
                vleid: {
                    required: true,
                },
                tokens: {
                    required: true,
                }
            },
            messages: {
                vleid: {
                    required: "Please enter vleid",
                },
                tokens: {
                    required: "Please enter no of tokens",
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
                var form = $('#pancardForm');
                var id   = form.find('[name="id"]').val();

                SYSTEM.tpinVerify(function(pin){
                    form.find('[name="pin"]').val(pin);
                    SYSTEM.FORMSUBMIT(form, function(data){
                        form.find('[name="pin"]').val("");
                        if (!data.statusText) {
                            if(data.statuscode == "TXN"){
                                getbalance();
                                form[0].reset();
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

        @if(Myhelper::can("apply_utimultiid"))
            $( "#idForm" ).validate({
            rules: {
                name: {
                    required: true,
                },
                shopname: {
                    required: true,
                },
                pincode: {
                    required: true,
                },
                pancard: {
                    required: true,
                },
                state: {
                    required: true,
                },
                mobile: {
                    required: true,
                },
                email: {
                    required: true,
                },
                city: {
                    required: true,
                }
            },
            messages: {
                vleid: {
                    required: "Please enter vleid",
                },
                shopname: {
                    required: "Please enter shopname",
                },
                pincode: {
                    required: "Please enter pincode",
                },
                pancard: {
                    required: "Please enter pancard",
                },
                state: {
                    required: "Please enter state",
                },
                mobile: {
                    required: "Please enter mobile",
                },
                email: {
                    required: "Please enter email",
                },
                city: {
                    required: "Please enter city",
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
                var form = $('#idForm');
                SYSTEM.FORMSUBMIT(form, function(data){
                    if (!data.statusText) {
                        if(data.statuscode == "TXN"){
                            form[0].reset();
                            form.closest(".modal").modal("hide");
                            $.alert({
                                icon      : 'fa fa-check',
                                theme     : 'modern',
                                animation : 'scale',
                                type      : 'green',
                                title     : "Success",
                                content   : data.message
                            });
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
            }
        });
        @endif
    });

    function vlerequest(){
        $.ajax({
            url: "{{ route('vleid') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType:'json',
            beforeSend:function(){
                swal({
                    title: 'Wait!',
                    text: 'We are feching details.',
                    onOpen: () => {
                        swal.showLoading()
                    }
                });
            }
        })
        .success(function(data) {
            if(data.statuscode == "TXN"){
                swal({
                    type: "success",
                    title: "Success",
                    text: "Uti id request submitted successfull",
                    onClose: () => {
                        window.location.reload();
                    }
                });
            }else{
                swal.close();
                notify(data.status, 'warning');
            }
        })
        .error(function(errors) {
            swal.close();
            showError(errors, $('#pancardForm'));
        });
    }   
</script>
@endpush