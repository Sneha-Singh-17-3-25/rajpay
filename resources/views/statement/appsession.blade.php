@extends('layouts.app')
@section('title', "App Session")
@section('pagetitle',  "App Session")

@php
    $table = "yes";
@endphp

@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">App Session</h4>
                </div>
                <table class="table table-bordered table-striped table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User Details</th>
                            <th>Ip Address</th>
                            <th>Action</th>
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
        var url = "{{url('statement/list/fetch')}}/appsession/0";
        var onDraw = function() {
        };
        var options = [
            { "data" : "id"},
            { "data" : "username"},
            { "data" : "ip"},
            { "data" : "status",
                render:function(data, type, full, meta){
                    return `<a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="mydelete('`+full.id+`')"><i class="icon-x"></i> Delete</a>`;
                }
            }
        ];

        datatableSetup(url, options, onDraw);
    });

    function mydelete(id){
        $.ajax({
            url: `{{route('statementDelete')}}`,
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend:function(){
                swal({
                    title: 'Wait!',
                    text: 'Please wait, we are working in your request',
                    onOpen: () => {
                        swal.showLoading()
                    },
                    allowOutsideClick: () => !swal.isLoading()
                });
            },
            dataType:'json',
            data:{'id':id, 'type': "appsession"}
        })
        .done(function(data) {
            swal.close();
            notify("Task Successfully Completed", 'success');
            $('#datatable').dataTable().api().ajax.reload();
        })
        .fail(function(errors) {
            swal.close();
            notify('Oops', errors.status+'! '+errors.statusText, 'warning');
        });
    }
</script>
@endpush