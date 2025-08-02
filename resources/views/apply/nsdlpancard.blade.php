@extends('layouts.offline')
@section('title', $KIOSKCODEJ)
@section('name', $KIOSKNAMEJ)

@section('content')
    <div class="content">
        <div class="tabbable">
            <ul class="nav nav-tabs bg-teal-600 nav-tabs-component no-margin-bottom">
                @if($type == "new")
                	<li><a href="#recharge" data-toggle="tab" id="{{$type}}Tab" class="legitRipple" aria-expanded="false" onclick="SETTITLE('A')"><i class="icon-plus2"></i>&nbsp; New Pan</a></li>
                @endif

                @if($type == "correction")
                    <li><a href="#recharge" data-toggle="tab" id="{{$type}}Tab" class="legitRipple" aria-expanded="false" onclick="SETTITLE('CR')">Pan Correction</a></li>
                @endif

                <li><a href="#reoprt" data-toggle="tab" id="reportTab" class="legitRipple" aria-expanded="false"><i class="icon-file-text3"></i>&nbsp; Report</a></li>
                <li><a href="#panstatus" data-toggle="tab" id="reportTab" class="legitRipple" aria-expanded="false"><i class="icon-checkbox-checked"></i>&nbsp; Pancard Status Check</a></li>
                <li><a href="#transactionstatus" data-toggle="tab" id="reportTab" class="legitRipple" aria-expanded="false"><i class="icon-checkbox-checked"></i>&nbsp; Transaction Status Check</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="recharge">
                    <div class="panel panel-default">
                        <form action="{{route('panApplyProcess')}}" method="post" id="transactionForm"> 
                            <input type="hidden" name="SSOID" value="{{$SSOID}}">
                            <input type="hidden" name="SERVICEID"  value="{{$SERVICEID}}">
                            <input type="hidden" name="SSOTOKEN"   value="{{$SSOTOKEN}}">
                            <input type="hidden" name="RETURNURL"  value="{{$RETURNURL}}">
                            <input type="hidden" name="KIOSKNAMEJ" value="{{$KIOSKNAMEJ}}">
                            <input type="hidden" name="KIOSKCODEJ" value="{{$KIOSKCODEJ}}">
                            <input type="hidden" name="KIOSKDATA"  value="{{$KIOSKDATA}}">
                            <input type="hidden" name="encData"  value="{{$encData}}">

                            <input type="hidden" name="option2" value="Y">
                            <input type="hidden" name="category" value="A">
                            <input type="hidden" name="type" value="{{$type}}">
                            {{ csrf_field() }}

                            <div class="panel-body">
                                <div class="row">
                                    <div class="pandata"></div>

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
                                            <option value="" selected="selected">Please Select</option>
                                            <option value="1">Shri</option>
                                            <option value="2">Smt</option>
                                            <option value="3">Kumari</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>First Name </label>
                                        <input type="text" class="form-control" autocomplete="off" name="firstname" placeholder="Enter First Name">
                                    </div>
                                    
                                    <div class="form-group col-md-4">
                                        <label>Middle Name </label>
                                        <input type="text" class="form-control" autocomplete="off" name="middlename" placeholder="Enter Middle Name">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Last Name </label>
                                        <input type="text" class="form-control" autocomplete="off" name="lastname" placeholder="Enter Last Name" required>
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
                                <button type="submit"  class="btn bg-teal-800 btn-labeled legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Submitting"><b><i class=" icon-paperplane"></i></b> Submit</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="tab-pane" id="reoprt">
                    <div class="panel panel-default">

                        <form id="searchForm">
                            <input type="hidden" name="agent" value="{{$KIOSKCODEJ}}">
                            <div class="panel panel-default no-margin">
                                <div class="panel-body p-tb-10">
                                    <div class="row">
                                        <div class="form-group col-md-2 m-b-10">
                                            <input type="text" name="from_date" class="form-control mydate" placeholder="From Date">
                                        </div>

                                        <div class="form-group col-md-2 m-b-10">
                                            <input type="text" name="to_date" class="form-control mydate" placeholder="To Date">
                                        </div>

                                        <div class="form-group col-md-2 m-b-10">
                                            <input type="text" name="searchtext" class="form-control" placeholder="Search Value">
                                        </div>

                                        @if(isset($status))
                                            <div class="form-group col-md-2">
                                                <select name="status" class="form-control select">
                                                    <option value="">Select {{$status['type'] ?? ''}} Status</option>
                                                    @if (isset($status['data']) && sizeOf($status['data']) > 0)
                                                        @foreach ($status['data'] as $key => $value)
                                                            <option value="{{$key}}">{{$value}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        @endif

                                        <div class="form-group col-md-4 m-b-10">
                                            <button type="submit" class="btn bg-slate btn-labeled legitRipple mt-5" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Searching"><b><i class="icon-search4"></i></b> Search</button>

                                            <button type="button" class="btn btn-warning btn-labeled legitRipple mt-5" id="formReset" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Refreshing"><b><i class="icon-rotate-ccw3"></i></b> Refresh</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="datatable" width="100%">
                                <thead>
                                    <tr style="white-space: nowrap;">
                                        <th>S.No.</th>
                                        <th>Action</th>
                                        <th>Type</th>
                                        <th>Request ID</th>
                                        <th>Transaction ID</th>
                                        <th>Status</th>
                                        <th>Acknowledge No</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="panstatus">
                    <div class="panel panel-default">
                        <form action="{{route('panStatusProcess')}}" method="post" id="panStatusForm"> 
                            {{ csrf_field() }}

                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-4 col-md-offset-4 text-center">
                                        <label>Acknowledge No </label>
                                        <input type="text" class="form-control" autocomplete="off" name="txnid" placeholder="Enter value">
                                    </div>
                                </div>
                            </div>

                            <div class="panel-footer text-center">
                                <button type="submit" class="btn bg-teal-800 btn-labeled legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Submitting"><b><i class=" icon-paperplane"></i></b> Submit</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="tab-pane" id="transactionstatus">
                    <div class="panel panel-default">
                        <form action="{{route('txnStatusProcess')}}" method="post" id="transactionStatusForm"> 
                            {{ csrf_field() }}

                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-4 col-md-offset-4 text-center">
                                        <label>Transaction Id</label>
                                        <input type="text" class="form-control" autocomplete="off" name="txnid" placeholder="Enter value">
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

        <div class="backForm">
            <form method="post" action="{{url('apply/pan/'.$type)}}" id="backForm">
                {{ csrf_field() }}
                <input type="hidden" name="encData" value="{{$encData ?? ""}}">
            </form>
        </div>
    </div>

    @if(isset($status))
        <div id="receipt" class="modal fade" data-backdrop="false" data-keyboard="false">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header bg-slate">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title">Transaction Information</h4>
                    </div>

                    <div class="modal-body" id="receptTable">
                        @if($status == "success")
                            <h4 class="text-success text-center">Pan Registration Successfull</h4>
                            <p>Please check your registerd email or mobile number for application details.</p>
                        @else
                            <h4 class="text-danger text-center">Pan Registration Under Process</h4>
                        @endif

                        <div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <h5>Transaction Details :</h5>
                                        <table class="table m-t-10 default">
                                            <thead>
                                                <tr>
                                                    <th>Transaction Id</th>
                                                    <th>{{$txnid}}</th>
                                                </tr>
                                                <tr>
                                                    <th>Acknowledge No</th>
                                                    <th>{{$refno}}</th>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <th>{{$status}}</th>
                                                </tr>
                                                <tr>
                                                    <th>Remark</th>
                                                    <th>{{$remark}}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($status == "success")
                            <hr>
                            <p class="text-danger">सूचना - आप के द्वारा पैन कार्ड का आवेदन सफलतापूर्वक कर दिया गया है 2 घंटे मे डिजिटल पैन कार्ड आप के द्वारा दर्ज मेल पर भेज दिया जाएगा। भौतिक पैन कार्ड की प्रति 5-7 दिवस के अंदर आधार कार्ड मे दर्ज पते पर भेज दी जाएगी</p>
                        @else
                            <hr>
                            <p class="text-danger">सूचना - आप के द्वारा पैन कार्ड आवेदन सफलतापूर्वक नहीं किया गया है। इस आवेदन की राशि 10 से 12 घंटे मे आप के ई मित्र वॉलेट मे स्वतः रिफ़ंड हो जाएगी नये पैन कार्ड के लिये पुनः प्रयास करें ..अधिक जानकारी के लिये कॉल करे 95876 67777</p>
                        @endif

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
                        <button class="btn bg-slate btn-raised legitRipple" type="button" id="print"><i class="fa fa-print"></i></button>
                    </div>
                </div>
            </div>
        </div>
    @endif
                                        
  <div id="receiptModal" class="modal fade" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-slate">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Receipt</h4>
            </div>
            <div class="modal-body">
                <div id="receptTable">
                    <table class="table m-t-10">
                        <thead>
                            <tr>
                                <th style="padding: 10px 0px;">
                                <img src="{{asset('')}}public/{{$mydata['company']->logo}}" class="img-responsive pull-left" alt="" style="height: 60px; width: 260px;">
                                </th>
            					<th style="padding: 10px 0px;">
                                <img src="{{asset('')}}public/logos/t-logo.png" class="img-responsive pull-right" alt="" style="height: 75%; width: 75%;">
            					</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 10px 0px" class="text-left">
                                    <address class="m-b-10">
										<strong>KIOSK Code : </strong> <span class="p-0">@yield('title')</span><br>
                                        <strong>KIOSK Name : </strong><span class="p-0">@yield('name')</span><br>
                                        <strong>Date : </strong><span class="created_at"></span>
                                    </address>
                                </td>
                                <td style="padding: 10px 0px" class="text-right">
                                    <address class="m-b-10 default">
                                        <strong>Consumer Name : </strong> <span class="option4"></span><br>
                                        <strong>Operator Name : </strong> <span class="product"></span><br>
                                    </address>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <h5>Transaction Details :</h5>
                                <table class="table m-t-10 default">
                                	<thead>
                                		<tr>
                                			<th>Request ID</th>
                                			<th>Ack No</th>
                                		</tr>
                                	</thead>
                                	<tbody>
                                		<tr>
                                			<td class="txnid text-left"></td>
                                			<td class="refno text-left"></td>
                                		</tr>
                                	</tbody>
                                </table>
                                <table class="table m-t-10 default">
                                    <thead>
                                        <tr>
                                            <th>Txn Id</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="payid text-left"></td>
                                            <td class="status text-left"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="border-radius: 0px;">
                        <div class="col-md-6 col-md-offset-6">
                            <h6 class="text-right">Amount : <span class="amount"></span></h6>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn bg-slate btn-raised legitRipple" type="button" id="printModal"><i class="fa fa-print"></i></button>
            </div>
        </div>
    </div>
</div>
@endsection
                                        
@push('style')
<style>
    td {
        white-space: nowrap;
        max-width: 100%;
        size: 10px;
        font-size: 13px;
        text-align: center;
    }
    .label {
        font-size: 10px;
     	display: flex;
    }
</style>
@endpush

@push('script')
    <script src="{{ asset('/assets/js/core/jQuery.print.js') }}"></script>
    <script type="text/javascript">

		var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
		(function(){
		var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/665af932981b6c564777282b/1hv9lqrq3';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();

        $(document).ready(function () {
            $('#print').click(function(){
                $('#receipt').find('.modal-body').print();
            });
        
        	$('#printModal').click(function(){
                $('#receiptModal').find('.modal-body').print();
            });
            
            $(window).load(function () {
                @if(isset($status))
                    $("#receipt").modal("show");
                @endif
                
                $("#{{$type}}Tab").click();
            });

            $(".modal").on('hidden.bs.modal', function () {
                $("#backForm").submit();
            });

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
                    // alert('subm');
                    var form = $('#transactionForm');
                    SYSTEM.FORMSUBMIT(form, function(data){
                        form[0].reset();
                        form.find('[name="pin"]').val("");
                        if (!data.statusText) {
                            if(data.statuscode == "TXN"){
                                $("[name='req']").val(data.requestData);
                                console.log(data.requestData);
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

            $( "#panStatusForm" ).validate({
                rules: {
                    txnid: {
                        required: true
                    }
                },
                messages: {
                    txnid: {
                        required: "Please enter value"
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
                    var form = $('#panStatusForm');
                    SYSTEM.FORMSUBMIT(form, function(data){
                        form[0].reset();
                        form.find('[name="pin"]').val("");
                        if (!data.statusText) {
                            if(data.statuscode == "TXN"){
                                $.alert({
                                    title: 'Status Found',
                                    content: data.message,
                                    type: 'green'
                                });
                            }else{
                                $.alert({
                                    title: 'Oops',
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

            $( "#transactionStatusForm" ).validate({
                rules: {
                    txnid: {
                        required: true
                    }
                },
                messages: {
                    txnid: {
                        required: "Please enter value"
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
                    var form = $('#transactionStatusForm');
                    SYSTEM.FORMSUBMIT(form, function(data){
                        form[0].reset();
                        form.find('[name="pin"]').val("");
                        if (!data.statusText) {
                            if(data.statuscode == "TXN"){
                                $.alert({
                                    title: 'Status Found',
                                    content: data.message,
                                    type: 'green'
                                });
                            }else{
                                $.alert({
                                    title: 'Oops',
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
            $('[data-popup="tooltip"]').tooltip();
            $('.print').click(function(event) {
                var data = DT.row($(this).parent().parent().parent().parent().parent()).data();

                $.each(data, function(index, values) {
                    $("."+index).text(values);
                });
                $('address.dmt').hide();
                $('address.aeps').hide();
                $('address.default').hide();

                if(data['product'] == "dmt"){
                    $('address.dmt').show();
                }else if(data['product'] == "aeps"){
                    $('address.aeps').show();
                }else{
                    $('address.default').show();
                }
                $('#receiptModal').modal();
            });
            };

            var options = [
                { "data" : "id"},
            	{ "data" : "bank",
                 	render:function(data, type, full, meta){
                    var menu = `<li class="dropdown-header">Action</li>`;
                    menu += `<li><a href="javascript:void(0)" class="print"><i class="icon-info22"></i>Print Invoice</a></li>`;

                    return  `<ul class="icons-list mr-5">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-left">
                                        `+menu+`
                                    </ul>
                                </li>
                            </ul>`;
                	}
            	},
            	{ "data" : "bank",
             		render:function(data, type, full, meta){
                		if(full.option6 == "A"){
                    		return "NEW PAN";
                		}else{
                    		return "CORRECTION";
                    	}
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
            	{ "data" : "payid"},
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
                { "data" : "refno"},
                { "data" : "option4"},
                { "data" : "amount"},
                { "data" : "created_at"},
                { "data" : "remark"}
            ];

            DT = datatableSetup(url, options, onDraw);
        });

        function SETTITLE(type, form) {
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