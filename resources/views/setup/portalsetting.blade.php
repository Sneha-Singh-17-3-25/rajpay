@extends('layouts.app')
@section('title', 'Portal Settings')
@section('pagetitle',  'Portal Settings')

@php
    $search = "hide";
@endphp

@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-4">
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="banklistupdate">
                <input type="hidden" name="code" value="banklistupdate">
                <input type="hidden" name="name" value="Bank List Update">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-title">Bank List Update</h5>
                    </div>
                    <div class="panel-body p-b-0">
                        <div class="form-group">
                            <label>Select Service For Update</label>
                            <select name="value" required="" class="form-control select">
                                <option value="">Select Service</option>
                                <option value="aeps">Aeps</option>
                            </select>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button class="btn bg-slate btn-raised legitRipple pull-right" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update Info</button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="col-sm-4">
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="otplogin">
                <input type="hidden" name="name" value="Login required otp">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-title">Login Otp Required</h5>
                    </div>
                    <div class="panel-body p-b-0">
                        <div class="form-group">
                            <label>Login Type</label>
                            <select name="value" required="" class="form-control select">
                                <option value="">Select Type</option>
                                <option value="yes" {{(isset($otplogin->value) && $otplogin->value == "yes") ? "selected=''" : ''}}>With Otp</option>
                                <option value="no" {{(isset($otplogin->value) && $otplogin->value == "no") ? "selected=''" : ''}}>Without Otp</option>
                            </select>
                        </div>
                        @if(Myhelper::hasRole('admin'))
                            <div class="form-group">
                                <label>Security Pin</label>
                                <input type="password" name="mpin" autocomplete="off" class="form-control" required="">
                            </div>
                        @endif
                    </div>
                    <div class="panel-footer">
                        <button class="btn bg-slate btn-raised legitRipple pull-right" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update Info</button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="col-sm-4">
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="pincheck">
                <input type="hidden" name="name" value="Pin Based Transaction">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-title">Pin Based Transaction</h5>
                    </div>
                    <div class="panel-body p-b-0">
                        <div class="form-group">
                            <label>Pin Check</label>
                            <select name="value" required="" class="form-control select">
                                <option value="">Select Type</option>
                                <option value="yes" {{(isset($pincheck->value) && $pincheck->value == "yes") ? "selected=''" : ''}}>Yes</option>
                                <option value="no" {{(isset($pincheck->value) && $pincheck->value == "no") ? "selected=''" : ''}}>No</option>
                            </select>
                        </div>
                        @if(Myhelper::hasRole('admin'))
                            <div class="form-group">
                                <label>Security Pin</label>
                                <input type="password" name="mpin" autocomplete="off" class="form-control" required="">
                            </div>
                        @endif
                    </div>
                    <div class="panel-footer">
                        <button class="btn bg-slate btn-raised legitRipple pull-right" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update Info</button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="col-sm-4">
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="otppayout">
                <input type="hidden" name="name" value="Otp Based Payout">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-title">Otp Based Payout</h5>
                    </div>
                    <div class="panel-body p-b-0">
                        <div class="form-group">
                            <label>Need Otp</label>
                            <select name="value" required="" class="form-control select">
                                <option value="">Select Type</option>
                                <option value="yes" {{(isset($otppayout->value) && $otppayout->value == "yes") ? "selected=''" : ''}}>Yes</option>
                                <option value="no" {{(isset($otppayout->value) && $otppayout->value == "no") ? "selected=''" : ''}}>No</option>
                            </select>
                        </div>
                        @if(Myhelper::hasRole('admin'))
                            <div class="form-group">
                                <label>Security Pin</label>
                                <input type="password" name="mpin" autocomplete="off" class="form-control" required="">
                            </div>
                        @endif
                    </div>
                    <div class="panel-footer">
                        <button class="btn bg-slate btn-raised legitRipple pull-right" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update Info</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-sm-4">
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="otpsendmailid">
                <input type="hidden" name="name" value="Sending mail id for otp">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-title">Sending mail id for otp</h5>
                    </div>
                    <div class="panel-body p-b-0">
                        <div class="form-group">
                            <label>Mail Id</label>
                            <input type="text" name="value" value="{{$otpsendmailid->value ?? ''}}" class="form-control" required="" placeholder="Enter value">
                        </div>
                        @if(Myhelper::hasRole('admin'))
                            <div class="form-group">
                                <label>Security Pin</label>
                                <input type="password" name="mpin" autocomplete="off" class="form-control" required="">
                            </div>
                        @endif
                    </div>
                    <div class="panel-footer">
                        <button class="btn bg-slate btn-raised legitRipple pull-right" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update Info</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-sm-4">
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="otpsendmailname">
                <input type="hidden" name="name" value="Sending mailer name id for otp">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-title">Sending mailer name id for otp</h5>
                    </div>
                    <div class="panel-body p-b-0">
                        <div class="form-group">
                            <label>Mailer Name</label>
                            <input type="text" name="value" value="{{$otpsendmailname->value ?? ''}}" class="form-control" required="" placeholder="Enter value">
                        </div>
                        @if(Myhelper::hasRole('admin'))
                            <div class="form-group">
                                <label>Security Pin</label>
                                <input type="password" name="mpin" autocomplete="off" class="form-control" required="">
                            </div>
                        @endif
                    </div>
                    <div class="panel-footer">
                        <button class="btn bg-slate btn-raised legitRipple pull-right" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update Info</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-sm-4">
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="transactioncode">
                <input type="hidden" name="name" value="Transaction Id Code">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-title">Transaction Id Code</h5>
                    </div>
                    <div class="panel-body p-b-0">
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" name="value" value="{{$transactioncode->value ?? ''}}" class="form-control" required="" placeholder="Enter value">
                        </div>
                        @if(Myhelper::hasRole('admin'))
                            <div class="form-group">
                                <label>Security Pin</label>
                                <input type="password" name="mpin" autocomplete="off" class="form-control" required="">
                            </div>
                        @endif
                    </div>
                    <div class="panel-footer">
                        <button class="btn bg-slate btn-raised legitRipple pull-right" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update Info</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script type="text/javascript">
    $(document).ready(function () {
        $('.actionForm').submit(function(event) {
            var form = $(this);
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
                            $('[name="api_id"]').select2().val(null).trigger('change');
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
            return false;
        });

        $("#setupModal").on('hidden.bs.modal', function () {
            $('#setupModal').find('.msg').text("Add");
            $('#setupModal').find('form')[0].reset();
        });

        $('')
    });
</script>
@endpush