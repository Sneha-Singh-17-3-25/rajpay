@extends('layouts.offline')

@section('content')
    <div class="content">
        <h3>National Pension System (NPS) - Open NPS Account Online</h3>
        <div class="row">
            <div class="col-md-5">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-title">Ready to Register?</h5>
                    </div>

                    <form action="{{route('panApplyProcess')}}" method="post" id="transactionForm"> 
                        {{ csrf_field() }}

                        <div class="panel-body">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Name</label>
                                        <input type="text" class="form-control" autocomplete="off" name="firstname" placeholder="Enter value">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Mobile</label>
                                        <input type="text" pattern="[0-9]*" maxlength="10" minlength="10" class="form-control" name="mobile" autocomplete="off" placeholder="Enter value" required>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Email</label>
                                        <input type="text" class="form-control" autocomplete="off" name="email" placeholder="Enter value" required>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>District</label>
                                        <input type="text" class="form-control" autocomplete="off" name="district" placeholder="Enter value" required>
                                    </div>
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
    </div>
@endsection

@push('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $( "#transactionForm" ).validate({
                rules: {
                    name: {
                        required: true
                    },
                    gender: {
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
                    gender: {
                        required: "Please select value"
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
                        form.find('[name="pin"]').val("");
                        if (!data.statusText) {
                            if(data.statuscode == "TXN"){
                                $("[name='req']").val(data.requestData);
                                document.f1.submit();
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

            $('[name="dataType"]').val("directpancard");
            
            var url = "{{route("panreportstatic")}}";
            var onDraw = function() {
            };

            var options = [
                { "data" : "id"},
                { "data" : "bank",
                    render:function(data, type, full, meta){
                        var out = "";
                        if(full.status == "success"){
                            var out = `<span class="label label-success">Success</span>`;
                        }else if(full.status == "accept"){
                            var out = `<span class="label label-info">Accept</span>`;
                        }else if(full.status == "pending"){
                            var out = `<span class="label label-warning">Pending</span>`;
                        }else if(full.status == "reversed" || full.status == "refunded"){
                            var out = `<span class="label bg-slate">`+full.status+`</span>`;
                        }else{
                            var out = `<span class="label label-danger">`+full.status+`</span>`;
                        }

                        return  out;
                    }
                },
                { "data" : "bank",
                    render:function(data, type, full, meta){
                        if(full.product == "matm" && full.status == "failed"){
                            var out =  `<a href="javascript:void(0)" class="viewreport" data-popup="tooltip" title="" data-original-title="View report" >`+full.txnid+`</a>`;
                        }else{
                            var out =  `<a href="javascript:void(0)" class="viewreport" data-popup="tooltip" title="" data-original-title="View report">`+full.txnid+`</a>`;
                        }

                        return out;
                    }
                },
                { "data" : "refno"},
                { "data" : "option4"},
                { "data" : "amount"},
                { "data" : "created_at"},
                { "data" : "remark"}
            ];

            DT = datatableSetup(url, options, onDraw);
        });
    </script>
@endpush
