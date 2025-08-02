<?php dd(Route::currentRouteAction()); ?>

@extends('layouts.app')
@section('title', "National Pension System")
@section('pagetitle', "National Pension System")
@php
    $table = "yes";
@endphp

@php
    $search = "hide";
@endphp

@section('content')
    <div class="content">
        
        <h3>Open NSDL Payments Bank Account Online</h3>
        <div class="row">
            <div class="col-md-5">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-title">Account Details</h5>
                    </div>

                    <form action="{{route('nsdlaccountApply')}}" method="post" id="transactionForm"> 
                        {{ csrf_field() }}

                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>Name</label>
                                    <input type="text" class="form-control" autocomplete="off" name="Customername" placeholder="Enter value">
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Email</label>
                                    <input type="text" class="form-control" autocomplete="off" name="Email" placeholder="Enter value" required>
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Mobile Number</label>
                                    <input type="text" pattern="[0-9]*" maxlength="10" minlength="10" class="form-control" autocomplete="off" name="mobileNo" placeholder="Enter value" required>
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Pancard Number</label>
                                    <input type="text" class="form-control" autocomplete="off" name="panNo" placeholder="Enter value" required>
                                </div>
                            </div>
                        </div>

                        <div class="panel-footer text-center">
                            <button type="submit" class="btn bg-teal-800 btn-labeled legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Submitting"><b><i class=" icon-paperplane"></i></b> Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-7">
                <div class="panel">
                    <div class="panel-body p-10 border-radius-top border-radius-bottom">
                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                            </ol>

                            <div class="carousel-inner" role="listbox">
                                <div class="item active">
                                    <img src="{{asset("")}}/assets/nps/nps2.jpeg" width="100%" height="700px">
                                </div>

                                <div class="item">
                                    <img src="{{asset("")}}/assets/nps/nps1.webp" width="100%" height="700px">
                                </div>
                            </div>

                            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            
                            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="nsdlFormData">
            <form method="post" action="https:\\assisted-service.egov.proteantech.in\SpringBootFormHandling\PanStatusReq" target="_blank" name="f1">
                <input type="hidden" name="req" value="">
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.dobdate').datepicker({
                'autoclose':true,
                'clearBtn':true,
                'todayHighlight':true,
                'format':'dd/mm/yyyy'
            });

            $( "#transactionForm" ).validate({
                rules: {
                    name: {
                        required: true
                    },
                    panNo: {
                        required: true
                    },
                    mobile: {
                        required: true
                    },
                    email: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Please enter value"
                    },
                    panNo: {
                        required: "Please enter value"
                    },
                    mobile: {
                        required: "Please enter value"
                    },
                    email: {
                        required: "Please enter value"
                    },
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
                    var form = $('#transactionForm');

                    SYSTEM.FORMSUBMIT(form, function(data){
                        form[0].reset();
                        if (!data.statusText) {
                            if(data.statuscode == "TXN"){
                                window.open(data.message);
                            }else if(data.statuscode == "TXF"){
                                $.alert({
                                    title: 'Oops!',
                                    content: data.message,
                                    type: 'red'
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
        });
    </script>
@endpush