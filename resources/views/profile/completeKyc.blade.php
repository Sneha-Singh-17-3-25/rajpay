@extends('layouts.app')
@section('title', "Complete Profile")
@section('pagetitle', "Complete Profile")
@section('content')

@php
    $search = "hide";
@endphp

<div class="content">
    <form class="memberForm" action="{{ route('profileUpdate') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{Auth::id()}}">
        <input type="hidden" name="actiontype" value="kycdata">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-title">Individual Documnents</h5>
                    </div>
                    <div class="panel-body p-b-0">
                        @if (Auth::user()->kyc == "rejected")
                            <div class="alert bg-danger alert-styled-left no-margin">
                                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                <span class="text-semibold">Kyc Rejected!</span> {{ Auth::user()->remark }}</a>.
                            </div>
                            <br>
                        @endif

                        <div class="row">
                            @if (!Auth::user()->pancardpic || Auth::user()->pancardpic == "")
                                <div class="form-group col-md-4">
                                    <label>Self Attested Pancard Copy</label>
                                    <input type="file" name="pancardpics" class="form-control" value="" placeholder="Enter Value" required="">
                                </div>
                            @endif

                            @if (!Auth::user()->aadharcardpicfront || Auth::user()->aadharcardpicfront == "")
                                <div class="form-group col-md-4">
                                    <label>Self Attedted E-Aadhar Copy</label>
                                    <input type="file" name="aadharcardpicfronts" class="form-control" value="" placeholder="Enter Value" required="">
                                </div>
                            @endif

                            @if (!Auth::user()->profile || Auth::user()->profile == "")
                                <div class="form-group col-md-4">
                                    <label>Self photo Latest</label>
                                    <input type="file" name="profiles" class="form-control" value="" placeholder="Enter Value" required="">
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            @if (!Auth::user()->passbook || Auth::user()->passbook == "")
                                <div class="form-group col-md-4">
                                    <label>Cancle Cheque/ Bank Passbook / Gst</label>
                                    <input type="file" name="passbooks" class="form-control" value="" placeholder="Enter Value" required="">
                                </div>
                            @endif

                            @if (!Auth::user()->otherdoc || Auth::user()->otherdoc == "")
                                <div class="form-group col-md-4">
                                    <label>Shop Outside Photo</label>
                                    <input type="file" name="otherdocs" class="form-control" value="" placeholder="Enter Value" required="">
                                </div>
                            @endif

                            @if (!Auth::user()->aadharcardpicback || Auth::user()->aadharcardpicback == "")
                                <div class="form-group col-md-4">
                                    <label>Video Kyc</label>
                                    <input type="file" name="aadharcardpicbacks" class="form-control" value="" placeholder="Enter Value" required="">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-md-offset-4">
                <button class="btn bg-slate btn-raised legitRipple btn-lg btn-block" type="submit" data-loading-text="Please Wait...">Complete Your Profile</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('script')
<script type="text/javascript">
    $(document).ready(function () {
        $( ".memberForm" ).validate({
            rules: {
                name: {
                    required: true,
                },
                mobile: {
                    required: true,
                    minlength: 10,
                    number : true,
                    maxlength: 10
                },
                email: {
                    required: true,
                    email : true
                },
                state: {
                    required: true,
                },
                city: {
                    required: true,
                },
                pincode: {
                    required: true,
                    minlength: 6,
                    number : true,
                    maxlength: 6
                },
                address: {
                    required: true,
                },
                aadharcard: {
                    required: true,
                    minlength: 12,
                    number : true,
                    maxlength: 12
                }
            },
            messages: {
                name: {
                    required: "Please enter name",
                },
                mobile: {
                    required: "Please enter mobile",
                    number: "Mobile number should be numeric",
                    minlength: "Your mobile number must be 10 digit",
                    maxlength: "Your mobile number must be 10 digit"
                },
                email: {
                    required: "Please enter email",
                    email: "Please enter valid email address",
                },
                state: {
                    required: "Please select state",
                },
                city: {
                    required: "Please enter city",
                },
                pincode: {
                    required: "Please enter pincode",
                    number: "Mobile number should be numeric",
                    minlength: "Your mobile number must be 6 digit",
                    maxlength: "Your mobile number must be 6 digit"
                },
                address: {
                    required: "Please enter address",
                },
                aadharcard: {
                    required: "Please enter aadharcard",
                    number: "Aadhar should be numeric",
                    minlength: "Your aadhar number must be 12 digit",
                    maxlength: "Your aadhar number must be 12 digit"
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
                var form = $('form.memberForm');
                form.find('span.text-danger').remove();
                $('form.memberForm').ajaxSubmit({
                    dataType:'json',
                    beforeSubmit:function(){
                        form.find('button:submit').button('loading');
                    },
                    complete: function () {
                        form.find('button:submit').button('reset');
                    },
                    success:function(data){
                        if(data.status == "success"){
                            form[0].reset();
                            $('select').val('');
                            $('select').trigger('change');
                            notify("Kyc Successfully Submitted , Wait For Profile Approval" , 'success');
                        }else{
                            notify(data.status , 'warning');
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });
    });
</script>
@endpush
