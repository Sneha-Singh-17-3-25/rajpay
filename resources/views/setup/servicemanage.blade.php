@extends('layouts.app')
@section('title', $user->name . " Service Manager")
@section('pagetitle',  $user->name . " Service Manager")
@php
    $table = "yes";
    $agentfilter = "hide";
    $product['type'] = "Operator Type";
    $product['data'] = [
        "mobile"  => "Mobile",
        "dth"  => "Dth",
        "electricity" => "Electricity",
        "pancard" => "Pancard",
        "dmt"  => "Dmt",
        "fund" => "Fund",
        "aeps" => "Aeps",
        "aadharpay" => "Aadhar Pay",
        "lpggas"        => "Lpg Gas",
        "gasutility"    => "Piped Gas",
        "landline"      => "Landline",
        "postpaid"      => "Postpaid",
        "broadband"     => "Broadband",
        "loanrepay"     => "Loan Repay",
        "lifeinsurance" => "Life Insurance",
        "fasttag"       => "Fast Tag",
        "cable"         => "Cable",
        "insurance"     => "Insurance",
        "schoolfees"    => "School Fees",
        "muncipal"      => "Minicipal",
        "housing"       => "Housing"
    ];

    $status['type'] = "Operator";
    $status['data'] = [
        "1" => "Active",
        "0" => "De-active"
    ];
@endphp

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <form id="updateFormAll" action="{{route("setupupdate")}}" method="post">
                <input type="hidden" name="actiontype" value="servicemanageall">
                <input type="hidden" name="user_id" value="{{$id}}">
                {{ csrf_field() }}        
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">{{$user->name}} Service Manager</h4>
                        <div class="heading-elements">
                            <button type="submit" class="btn bg-slate btn-xs btn-labeled legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Searching"><b><i class="icon-search4"></i></b> Update</button>
                        </div>
                    </div>
                    <div class="panel-body p-tb-10">
                        @if(isset($product))
                            <div class="form-group col-md-4">
                                <select name="type" class="form-control select">
                                    <option value="">Select {{$product['type'] ?? ''}}</option>
                                    @if (isset($product['data']) && sizeOf($product['data']) > 0)
                                        @foreach ($product['data'] as $key => $value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        @endif

                        @if($apis)
                        <div class="form-group col-md-4">
                            <select name="api_id" class="form-control select">
                                <option value="">Select Api</option>
                                @if (sizeOf($apis) > 0)
                                    @foreach ($apis as $myapi)
                                        <option value="{{$myapi->id}}">{{$myapi->product}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Operator Lists</h4>
                    <div class="heading-elements">
                        <button type="button" class="btn btn-sm bg-slate btn-raised heading-btn legitRipple" onclick="addSetup()">
                            <i class="icon-plus2"></i> Add New
                        </button>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Provider Name</th>
                            <th>Api Name</th>
                            <th>Operator Api</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="setupModal" class="modal fade" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title"><span class="msg">Add</span> Operator</h6>
            </div>
            <form id="setupManager" action="{{route('setupupdate')}}" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id">
                        <input type="hidden" name="actiontype" value="operator">
                        {{ csrf_field() }}
                        <div class="form-group col-md-6">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter value" required="">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Recharge1</label>
                            <input type="text" name="recharge1" class="form-control" placeholder="Enter value" required="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Recharge2</label>
                            <input type="text" name="recharge2" class="form-control" placeholder="Enter value" required="">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Recharge3</label>
                            <input type="text" name="recharge3" class="form-control" placeholder="Enter value" required="">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Operator Type</label>
                            <select name="type" class="form-control select" required>
                                <option value="">Select Operator Type</option>
                                <option value="mobile">Mobile</option>
                                <option value="dth">DTH</option>
                                <option value="bbps">Bbps Bills</option>
                                <option value="pancard">Pancard</option>
                                <option value="dmt">Dmt</option>
                                <option value="aeps">Aeps</option>
                                <option value="fund">Fund</option>
                                <option value="electricity">Electricity Bill</option>
                                <option value="water">Water</option>
                                <option value="gas">Gas</option>
                                <option value="postpaid">Postpaid</option>
                                <option value="landline">Landline</option>
                                <option value="broadband">Broadband</option>
                                <option value="insurance">Insurance</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Api</label>
                            <select name="api_id" class="form-control select" required>
                                <option value="">Select Api</option>
                                @foreach ($apis as $api)
                                <option value="{{$api->id}}">{{$api->product}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Param Count</label>
                            <input type="text" name="paramcount" class="form-control" placeholder="Enter value" required="">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Mandit Count</label>
                            <input type="text" name="manditcount" class="form-control" placeholder="Enter value" required="">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Param Name</label>
                            <input type="text" name="paramname" class="form-control" placeholder="Enter value" required="">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Is Mandatory</label>
                            <input type="text" name="ismandatory" class="form-control" placeholder="Enter value" required="">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Max Length</label>
                            <input type="text" name="maxlength" class="form-control" placeholder="Enter value" required="">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Min Length</label>
                            <input type="text" name="minlength" class="form-control" placeholder="Enter value" required="">
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

@push('script')
    <script type="text/javascript">
    $(document).ready(function () {
        var url = "{{url('statement/list/fetch')}}/setupoperator/0";

        var onDraw = function() {
            $('select').select2();
        };

        var options = [
            { "data" : "id"},
            { "data" : "name"},
            { "data" : "name",
                render:function(data, type, full, meta){
                    var name = "No Api Select";
                    @foreach ($services as $service)
                        var providerid = "{{$service->provider_id}}";
                        var apiname = "{{$service->api->product}}";

                        if(full.id == providerid){
                            name = apiname;
                        }
                    @endforeach
                    return name;
                }
            },
            { "data" : "name",
                render:function(data, type, full, meta){
                    var out = "";
                    out += `<select class="form-control select" required="" onchange="apiUpdate(this, `+full.id+`)">
                    <option value="">Select Api</option>`;
                    @foreach ($apis as $api)
                        out += `<option value="{{$api->id}}">{{$api->product}}</option>`;
                    @endforeach
                    out += `</select>`;
                    return out;
                }
            }
        ];
        var DT = datatableSetup(url, options, onDraw);

        $( "#updateFormAll" ).validate({
            rules: {
                status: {
                    required: true,
                },
                type: {
                    required: true,
                }
            },
            messages: {
                type: {
                    required: "Please select operator type",
                },
                status: {
                    required: "Please select status",
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
                var form = $('#updateFormAll');
                form.ajaxSubmit({
                    dataType:'json',
                    beforeSubmit:function(){
                        form.find('button[type="submit"]').button('loading');
                    },
                    success:function(data){
                        if(data.status == "success"){
                            form.find('button[type="submit"]').button('reset');
                            notify("Task Successfully Completed", 'success');
                            $('#datatable').dataTable().api().ajax.reload();

                            setTimeout(function(){
                                window.location.reload();
                            }, 2000);
                        }else{
                            notify(data.status, 'warning');
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });

    });

    function apiUpdate(ele, id){
        var api_id = $(ele).val();
        if(api_id != ""){
            $.ajax({
                url: '{{ route('setupupdate') }}',
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType:'json',
                data: {'provider_id':id, 'api_id':api_id, "user_id" : "{{$id}}", "actiontype":"servicemanage"}
            })
            .done(function(data) {
                if(data.status == "success"){
                    notify("Operator Updated", 'success');

                    setTimeout(function(){
                        window.location.reload();
                    }, 1000);
                }else{
                    notify("Something went wrong, Try again." ,'warning');
                }
                $('#datatable').dataTable().api().ajax.reload();
            })
            .fail(function(errors) {
                showError(errors, "withoutform");
            });
        }
    }
</script>
@endpush