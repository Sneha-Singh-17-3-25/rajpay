@extends('layouts.app')
@section('title', "Payout Bank Approval")
@section('pagetitle',  "Payout Bank Approval")

@php
    $table = "yes";

    $status['type'] = "Id";
    $status['data'] = [
        "approved" => "Approved",
        "pending"  => "Pending",
        "rejected" => "Rejected",
    ];
@endphp

@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Payout Bank</h4>
                </div>
                <table class="table table-bordered table-striped table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User Details</th>
                            <th>Bank Details</th>
                            <th>Passbook</th>
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

@if (Myhelper::can('payoutbank_edit'))
    <div id="editModal" class="modal fade" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-slate">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Edit Report</h6>
                </div>
                <form id="editBankForm" action="{{route('setupupdate')}}" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id">
                            <input type="hidden" name="actiontype" value="payoutbank">
                            {{ csrf_field() }}
                            <div class="form-group col-md-12">
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
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter account" required="">
                                <button class="btn btn-primary legitRipple btn-xs pull-right" type="button" id="getBenename">Get Name</button>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Account</label>
                                <input type="text" name="account" class="form-control" placeholder="Enter account" required="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Bank</label>
                                <input type="text" name="bank" class="form-control" placeholder="Enter bank" required="">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Ifsc</label>
                                <input type="text" name="ifsc" class="form-control" placeholder="Enter ifsc" required="">
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
    $(document).ready(function () {
        var url = "{{url('statement/list/fetch')}}/setuppayoutbank/0";
        var onDraw = function() {
            $('#datatable').on('click', '#agentEdit', function () {
                var data = DT.row($(this).parent().parent().parent().parent().parent().parent()).data();

                $('#editModal').find('[name="id"]').val(data.id);
                $('#editModal').find('[name="status"]').val(data.status).trigger('change');
                $('#editModal').find('[name="account"]').val(data.account);
                $('#editModal').find('[name="name"]').val(data.name);
                $('#editModal').find('[name="bank"]').val(data.bank);
                $('#editModal').find('[name="ifsc"]').val(data.ifsc);
                $("#editModal").modal();
            });
        };
        var options = [
            { "data" : "name",
                render:function(data, type, full, meta){
                    return `<div>
                            <span class='text-inverse pull-right m-l-10'><b>`+full.id +`</b> </span>
                            <div class="clearfix"></div>
                        </div><span style='font-size:13px' class="pull=right">`+full.created_at+`</span>`;
                }
            },
            { "data" : "username"},
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return `Name - `+full.name+`<br>Bank - `+full.bank+`<br>Account - `+full.account+`<br>Ifsc - `+full.ifsc+`</a>`;
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return `<a target="_blank" href="`+full.passbook+`">Passbook</a>`
                }
            },
            { "data" : "status",
                render:function(data, type, full, meta){
                    if(full.status == "approved"){
                        var out = `<span class="label label-success">Approved</span>`;
                    }else if(full.status == "pending"){
                        var out = `<span class="label label-warning">Pending</span>`;
                    }else{
                        var out = `<span class="label label-danger">Rejected</span>`;
                    }

                    var menu = ``;

                    @if (Myhelper::can('payoutbank_edit'))
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

                    out += `<button type="button" class="btn bg-slate btn-raised legitRipple btn-xs" onclick="deleteSlide('`+full.id+`')"> Delete</button>`;
                    return out;
                }
            }
        ];

        var DT = datatableSetup(url, options, onDraw);

        $( "#editBankForm" ).validate({
            rules: {
                status: {
                    required: true,
                },
            },
            messages: {
                name: {
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
                var form = $('#editBankForm');
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

        $('#getBenename').click(function(){
            var rid = $(this).closest('form').find("[name='rid']").val();
            var mobile = $(this).closest('form').find("[name='mobile']").val();
            var name = $(this).closest('form').find("[name='name']").val();
            var benebank = $(this).closest('form').find("[name='benebank']").val();
            var beneaccount = $(this).closest('form').find("[name='beneaccount']").val();
            var beneifsc = $(this).closest('form').find("[name='beneifsc']").val();
            var benename = $(this).closest('form').find("[name='benename']").val();
            var benemobile = $(this).closest('form').find("[name='benemobile']").val();

            if(mobile != '' || name != '' || benebank != '' || beneaccount != '' || beneifsc != '' || benename != '' || benemobile != ''){
                getname(mobile, name, benebank, beneaccount, beneifsc, benename);
            }
        });
    });

    function editUtiid(id, status){
        $('#editModal').find('[name="id"]').val(id);
        $('#editModal').find('[name="status"]').val(status).trigger('change');
    	$('#editModal').modal('show');
    }
    
    function deleteSlide(id) {
        $.ajax({
            url: '{{route("statementDelete")}}',
            type: 'post',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data : {"id" : id, 'type' : 'payoutbank'},
            beforeSend : function(){
                swal({
                    title: 'Wait!',
                    text: 'Please wait, we are deleting slides',
                    onOpen: () => {
                        swal.showLoading()
                    },
                    allowOutsideClick: () => !swal.isLoading()
                });
            }
        })
        .success(function(data) {
            swal.close();
            $('#datatable').dataTable().api().ajax.reload();
            notify("Account Successfully Deleted", 'success');
        })
        .fail(function() {
            swal.close();
            notify('Somthing went wrong', 'warning');
        });
    }
</script>
@endpush