@extends('layouts.app')
@section('title', "P-Aeps Agents List")
@section('pagetitle',  "P-Aeps Agent List")

@php
    $table  = "yes";
    $export = "paepsagent";
    $status['type'] = "Id";
    $status['data'] = [
        "success"  => "Success",
        "pending"  => "Pending",
        "failed"   => "Failed",
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
                    <h4 class="panel-title">Aeps Agent List</h4>
                </div>
                <table class="table table-bordered table-striped table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User Details</th>
                            <th>Agent Details</th>
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

<div id="viewFullDataModal" class="modal fade right" role="dialog" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header bg-slate">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Agent Details</h4>
            </div>
            <div class="modal-body p-0">
                <table class="table table-bordered table-striped ">
                    <tbody class="agentData">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
            </div>
        </div>
    </div>
</div>

@if (Myhelper::can('agentid_edit'))
    <div id="editModal" class="modal fade" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-slate">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Edit Report</h6>
                </div>
                <form id="editUtiidForm" action="{{route('statementUpdate')}}" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <input type="hidden" name="actiontype" value="paeps">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Status</label>
                                <select name="status" class="form-control select" required>
                                    <option value="">Select Type</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Merchant Login Id</label>
                                <input type="text" name="merchantLoginId" class="form-control" value="" placeholder="" required="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
                        <button class="btn bg-slate btn-raised legitRipple" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endif

@endsection

@push('style')

@endpush

@push('script')
<script type="text/javascript">
    var DT;
    $(document).ready(function () {
        $('[name="dataType"]').val("paysagent");
        
        $( "#editUtiidForm" ).validate({
            rules: {
                bbps_agent_id: {
                    required: true,
                },
            },
            messages: {
                bbps_agent_id: {
                    required: "Please enter id",
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
                var form = $('#editUtiidForm');
                var id = form.find('[name="id"]').val();
                form.ajaxSubmit({
                    dataType:'json',
                    beforeSubmit:function(){
                        form.find('button[type="submit"]').button('loading');
                    },
                    success:function(data){
                        if(data.status == "success"){
                            if(id == "new"){
                                form[0].reset();
                            }
                            form.find('button[type="submit"]').button('reset');
                            notify("Task Successfully Completed", 'success');
                            $('#datatable').dataTable().api().ajax.reload();
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

        $("#editModal").on('hidden.bs.modal', function () {
            $('#setupModal').find('form')[0].reset();
        });
        
        var url = "{{url('statement/list/fetch')}}/paepsagentstatement/{{$id}}";
        var onDraw = function() {
            $('#datatable').on('click', '#viewAgentData', function () {
                var data = DT.row($(this).parent().parent()).data();
                var agentdata = "";
                $.each(data, function(index, values) {
                    if(index == "aadharPic" || index == "pancardPic"){
                        agentdata += `<tr>
                            <td>`+index+`</td>
                            <td><a href="`+values+`" download target="_blank">Download</a></td>
                        </tr>`;
                    }else{
                        agentdata += `<tr>
                            <td>`+index+`</td>
                            <td>`+values+`</td>
                        </tr>`;
                    }
                });

                $(".agentData").html(agentdata);
                $("#viewFullDataModal").modal();
            });

            $('#datatable').on('click', '#agentEdit', function () {
                var data = DT.row($(this).parent().parent().parent().parent().parent().parent()).data();

                $('#editModal').find('[name="id"]').val(data.id);
                $('#editModal').find('[name="status"]').val(data.status).trigger('change');
                $('#editModal').find('[name="merchantLoginId"]').val(data.merchantLoginId);
                $("#editModal").modal();
            });
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
            { "data" : "username"},
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return `Merchant Code - `+full.merchantLoginId+`<br>Name - <a href="javascript:void(0)" id="viewAgentData">`+full.merchantName+`</a>`;
                }
            },
            { "data" : "status",
                render:function(data, type, full, meta){
                    if(full.status == "success" || full.status == "approved"){
                        var out = `<span class="label label-success">Approved</span>`;
                    }else if(full.status == "pending"){
                        var out = `<span class="label label-warning">Pending</span>`;
                    }else{
                        var out = `<span class="label label-danger">Rejected</span>`;
                    }
                    var menu = '';
                    @if (Myhelper::can('aepsid_statement_edit'))
                    menu += `<li class="dropdown-header">Setting</li>
                            <li><a href="javascript:void(0)" id="agentEdit"><i class="icon-pencil5"></i> Edit</a></li>`;
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

        DT = datatableSetup(url, options, onDraw);
    });

    function editUtiid(id, bbps_agent_id, bbps_id){
        $('#editModal').find('[name="id"]').val(id);
        $('#editModal').find('[name="bbps_agent_id"]').val(bbps_agent_id);
        $('#editModal').find('[name="bbps_id"]').val(bbps_id);
        $('#editModal').modal('show');
    }
</script>
@endpush