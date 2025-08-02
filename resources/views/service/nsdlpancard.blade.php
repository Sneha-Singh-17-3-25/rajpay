@extends('layouts.app')
@section('title', "Pancard Service")
@section('pagetitle', "Pancard Service")
@php
    $table = "yes";
@endphp

@php
    $search = "hide";
@endphp

@section('content')
    <div class="content">
        <div class="tabbable">
            <ul class="nav nav-tabs bg-teal-600 nav-tabs-component no-margin-bottom">
                <li class="active"><a href="#recharge" data-toggle="tab" id="mobileTab" class="legitRipple" aria-expanded="false" onclick="SETTITLE('A')">New Pan</a></li>
                <li><a href="#recharge" data-toggle="tab" id="dthTab" class="legitRipple" aria-expanded="false" onclick="SETTITLE('CR')">Pan Correction</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="recharge">
                    <div class="panel panel-default">
                        <form action="{{route('pancardpay')}}" method="post" id="transactionForm"> 
                            <input type="hidden" name="option2" value="Y">
                            <input type="hidden" name="category" value="A">
                            {{ csrf_field() }}

                            <div class="panel-body">
                                <div class="pandata"></div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>PAN Card Type</label>
                                        <select class="form-control" name="description" required="">
                                            <option value="">Select Application Mode</option>
                                            <option value="K">EKYC Based</option>
                                            <option value="E">Scanned Based</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Title</label>
                                        <select class="form-control" name="title" required="">
                                            <option value="0" selected="selected">Please Select</option>
                                            <option value="1">Shri</option>
                                            <option value="2">Smt</option>
                                            <option value="3">Kumari</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Last Name </label>
                                        <input type="text" class="form-control" autocomplete="off" name="lastname" placeholder="Enter Last Name" required>
                                    </div>
                                    
                                    <div class="form-group col-md-4">
                                        <label>Middle Name </label>
                                        <input type="text" class="form-control" autocomplete="off" name="middlename" placeholder="Enter Middle Name">
                                    </div>
                                    
                                    <div class="form-group col-md-4">
                                        <label>First Name </label>
                                        <input type="text" class="form-control" autocomplete="off" name="firstname" placeholder="Enter First Name" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Mobile</label>
                                        <input type="text" pattern="[0-9]*" maxlength="10" minlength="10" class="form-control" name="mobile" autocomplete="off" placeholder="Enter Your Mobile" required>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>email</label>
                                        <input type="text" class="form-control" autocomplete="off" name="email" placeholder="Enter Your Email" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="md-checkbox no-margin">
                                            <input type="checkbox" name="consent" value="Y" id="consent" checked="true">
                                            <label for="consent">Accept Consent</label><br>
                                            <span class="text-primary">I have no objection in authenticating myself and fully understand that information provided by me shall be used for authenticating my identity through Aadhaar Authentication System for the purpose stated above and no other purpose.</span>
                                        </div>

                                        <p class="mt-10">
                                            नोट: Scanned Bassed आवेदन ई-मेल आईडी पर 3-4 कार्य दिवस में आता है एवं E-KYC Bassed पैन कार्ड मात्र 02 घण्टे में प्राप्त हो जाते है
                                        </p>

                                        <p> Instructions :
                                            यदि पैन कार्ड का टोकन कटने के बाद किसी कारणवश एप्लिकेशन प्रोसैस नहीं होती है जैसे इंटरनेट कनेक्टिविटी का चले जाना या बिजली का चले जाना इस स्थिति में आपका पैसा वापस से आपके वॉलेट में अधिकतम 2-6 घंटे मे वापस से स्वत: ही रिफ़ंड हो जाएगा, और आपको इस संबंध मे अधिक जानकारी के लिए आप 9587667777 पर संपर्क कर सकते है
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-footer text-center">
                                <button type="submit" class="btn bg-teal-800 btn-labeled legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Submitting"><b><i class=" icon-paperplane"></i></b> Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="nsdlFormData">
            <form method="post" action="https:\\assisted-service.egov.proteantech.in\SpringBootFormHandling\newPanReq" id="nsdlFormData" name="f1">
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
                    description: {
                        required: true
                    },
                    title: {
                        required: true
                    },
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
                    description: {
                        required: "Please select value"
                    },
                    title: {
                        required: "Please select value"
                    },
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
                    error.insertAfter( element );
                },
                submitHandler: function () {
                    var form = $('#transactionForm');

                    SYSTEM.tpinVerify(function(pin){
                        form.find('[name="pin"]').val(pin);
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
                    });
                }
            });
        });

        function SETTITLE(type) {
            $("[name='category']").val(type);
            if(type == "A"){
                $(".pandata").html(``);
                $("#nsdlFormData").attr("action", "https://assisted-service.egov.proteantech.in/SpringBootFormHandling/newPanReq");
            }else{
                $(".pandata").html(`
                    <div class="form-group col-md-4">
                        <label>Pan Number</label>
                        <input type="text" class="form-control" autocomplete="off" name="pancard" placeholder="Enter value" required>
                </div>`);

                $("#nsdlFormData").attr("action", "https://assisted-service.egov.proteantech.in/SpringBootFormHandling/crPanReq");
            }

        }

        function vlerequest(){
            $.ajax({
                url: "{{ route('pancardcreate') }}",
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
                        text: "Pancard activated successfull",
                        onClose: () => {
                            window.location.reload();
                        }
                    });
                }else{
                    swal.close();
                    mdtoast.error("Oops! "+data.message, { position: "top center" });
                }
            })
            .error(function(errors) {
                swal.close();
                mdtoast.error("Oops! Somthing went wrong", { position: "top center" });
            });
        }
    </script>
@endpush