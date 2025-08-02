<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('pagetitle') - {{$mydata['company']->companyname}}</title>
    <link rel="shortcut icon" href="{{asset('')}}assets/icon/aps.png">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{asset('')}}assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="{{asset('')}}assets/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
    <link href="{{asset('')}}assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="{{asset('')}}assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="{{asset('')}}assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="{{asset('')}}assets/css/colors.css" rel="stylesheet" type="text/css">
    <link href="{{asset('')}}assets/css/snackbar.css" rel="stylesheet">
    <link href="{{asset('')}}assets/css/jquery-confirm.min.css" rel="stylesheet" type="text/css">
    <link href="{{asset('')}}assets/js/plugins/materialToast/mdtoast.min.css" rel="stylesheet" type="text/css">

    <style>
        .navbar-inverse {
            background-color: {{$mydata['topheadcolor']->value ?? ''}} !important;
            border-color: #272e3b !important;
            border-bottom: 5px solid;
        }

        .navigation > li.active > a {
            color: #fff !important;
            background-color: {{$mydata['sidebarlightcolor']->value ?? '#0096FF'}};
        }

        .panel-default > .panel-heading{
            color: #fff !important;
            background-color: #272e3b !important;
        }
        
        .newservice{
            background-image: url(http://e-banker.in/assets/new.png);
            background-size: 60px;
            background-repeat: no-repeat;
            background-position: -5px -9px;
            padding-left: 35px !important;
        }

        .sidebar-default {
            background-color: {{$mydata['sidebardarkcolor']->value ?? '#3082ab'}};
        }

        .sidebar-default .navigation li.active > a,
        .sidebar-default .navigation li.active > a:hover,
        .sidebar-default .navigation li.active > a:focus {
          background-color: #fe961a;
          color: {{$mydata['sidebarchildhrefcolor']->value ?? '#3082ab'}};
        }

        .sidebar-detached .sidebar-default .navigation li > a, .sidebar-detached .navigation li a > i {
            color: #333333;
        }

        .navigation li a > i {
            float : left;
            top : 0;
            margin-top : 2px;
            margin-right : 15px;
            -webkit-transition : opacity 0.2s ease-in-out;
            -o-transition : opacity 0.2s ease-in-out;
            transition : opacity 0.2s ease-in-out;
            color : {{$mydata['sidebariconcolor']->value ?? '#409cab'}};
        }

        p.error{
            color: #F44336;
        }

        .changePic{
            position: absolute;
            width: 100%;
            height: 30%;
            left: 0px;
            bottom: 0px;
            background: #fff;
            color: #000;
            padding: 20px 0px;
            line-height: 0px;
        }

        .companyname{
            font-size: 20px;
        }

        .navbar-brand{
            padding : 20px;
            height  : 100%!important;
        }

        .modal{
            overflow: auto;
        }

        .news {
            background-color: #000;
            padding: 12px;
            font-size: 22px;
            color: white;
            text-transform: capitalize;
            border-radius: 3px;
            text-align: center;
        }

        .animationClass {
            animation: blink 1.5s linear infinite;
            -webkit-animation: blink 1.5s linear infinite;
            -moz-animation: blink 1.5s linear infinite;
            -o-animation: blink 1.5s linear infinite;
        }

        .news:hover .animationClass{
            opacity: 1!important;
            -webkit-animation-play-state: paused;
            -moz-animation-play-state: paused;
            -o-animation-play-state: paused;
            animation-play-state: paused;
        }
          
        @keyframes blink{
            30%{opacity: .30;}
            50%{opacity: .5;}
            75%{opacity: .75;}
            100%{opacity: 1;}
        }

        input[type="number"]::-webkit-outer-spin-button, input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
         
        input[type="number"] {
            -moz-appearance: textfield;
        }

        .sidebar-default .navigation > li ul {
            border-radius: 0px;
            padding:10px;
        }

        /* width */
        ::-webkit-scrollbar {
          width: 7px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
          background: #f1f1f1; 
        }
         
        /* Handle */
        ::-webkit-scrollbar-thumb {
          background: #888; 
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
          background: #555; 
        }
        
        .sidebar-mobile-main .sidebar-main{
            position: absolute;
            width: 350px;
        }

        .nav-tabs.nav-tabs-component > .active > a:after, .nav-tabs.nav-tabs-component > .active > a:hover:after, .nav-tabs.nav-tabs-component > .active > a:focus:after {
        background-color: #FFC107;
        }

        .nav-tabs.nav-tabs-component > li > a:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .otp{
            display: inline-block;
            width: 40px;
            height: 40px;
            text-align: center;
            margin: 5px;
            font-size: 20px;
        }

        .bg-teal-600 {
            background-color: #272e3b !important;
            border-color: #fe961a !important;
            color: #fff;
            border-left: 5px solid;
        }
    </style>
    @stack('style')
    <!-- Core JS files -->
    <script type="text/javascript" src="{{asset('')}}assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/plugins/loaders/blockui.min.js"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/plugins/ui/ripple.min.js"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/core/jquery.validate.min.js"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/core/jquery.form.min.js"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/plugins/forms/selects/select2.min.js"></script>
    <script src="{{asset('')}}/assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('/assets/js/core/jQuery.print.js') }}"></script>
    <!-- /core JS files -->
    <script type="text/javascript" src="{{asset('')}}assets/js/plugins/tables/datatables/datatables.min.js"></script>

    <script type="text/javascript" src="{{asset('')}}assets/js/core/app.js"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/core/dropzone.js"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/core/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/plugins/materialToast/mdtoast.min.js"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/core/sweetalert2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("[name='gps_location']").remove();
            $("form").prepend('<input type="hidden" name="gps_location" value="'+localStorage.getItem("gps_location")+'">');

            $('.select').select2();

            $('.mydate').datepicker({
                'autoclose':true,
                'clearBtn':true,
                'todayHighlight':true,
                'format':'yyyy-mm-dd'
            });

            $('input[name="from_date"]').datepicker("setDate", new Date());
            $('input[name="to_date"]').datepicker('setStartDate', new Date());

             $('input[name="to_date"]').focus(function(){
                if($('input[name="from_date"]').val().length == 0){
                    $('input[name="to_date"]').datepicker('hide');
                    $('input[name="from_date"]').focus();
                }
            });

            $('input[name="from_date"]').datepicker().on('changeDate', function(e) {
                $('input[name="to_date"]').datepicker('setStartDate', $('input[name="from_date"]').val());
                $('input[name="to_date"]').datepicker('setDate', $('input[name="from_date"]').val());
            });

            $('form#searchForm').submit(function(){
                $('#searchForm').find('button:submit').button('loading');
                var fromdate =  $(this).find('input[name="from_date"]').val();
                var todate =  $(this).find('input[name="to_date"]').val();
                if(fromdate.length !=0 || todate.length !=0){
                    $('#datatable').dataTable().api().ajax.reload();
                }
                return false;
            });

            $('#formReset').click(function () {
                $('form#searchForm')[0].reset();
                $('form#searchForm').find('[name="from_date"]').datepicker().datepicker("setDate", new Date());
                $('form#searchForm').find('[name="to_date"]').datepicker().datepicker("setDate", null);
                $('form#searchForm').find('select').select2().val(null).trigger('change')
                $('#formReset').button('loading');
                $('#datatable').dataTable().api().ajax.reload();
            });
        });

        function datatableSetup(urls, datas, onDraw=function () {}, ele="#datatable", element={}) {
            var options = {
                dom: '<"datatable-scroll"t><"datatable-footer"ipl>',
                processing: true,
                serverSide: true,
                ordering: false,
                stateSave: true,
                columnDefs: [{
                    orderable: false,
                    width: '130px',
                    targets: [ 0 ]
                }], 
                lengthMenu: [10, 25, 50, 100],
                language: {
                    paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
                },
                drawCallback: function () {
                    $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
                },
                preDrawCallback: function() {
                    $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
                },    
                ajax:{
                    url : urls,
                    type: "post",
                    data:function( d )
                        {
                            d._token   = $('meta[name="csrf-token"]').attr('content');
                            d.type     = $('[name="dataType"]').val();
                            d.fromdate = $('#searchForm').find('[name="from_date"]').val();
                            d.todate   = $('#searchForm').find('[name="to_date"]').val();
                            d.searchtext = $('#searchForm').find('[name="searchtext"]').val();
                            d.agent    = $('#searchForm').find('[name="agent"]').val();
                            d.status   = $('#searchForm').find('[name="status"]').val();
                            d.product  = $('#searchForm').find('[name="product"]').val();
                        },
                    beforeSend: function(){
                    },
                    complete: function(){
                        $('#searchForm').find('button:submit').button('reset');
                        $('#formReset').button('reset');
                    },
                    error:function(response) {
                    }
                },
                columns: datas
            };

            $.each(element, function(index, val) {
                options[index] = val; 
            });

            var DT = $(ele).DataTable(options).on('draw.dt', onDraw);
            return DT;
        }
    </script>
    
    <script type="text/javascript">
        var ROOT = "{{url('')}}" , SYSTEM, tpinConfirm, otpConfirm, CALLBACK, OTPCALLBACK;

        $(document).ready(function () {
            SYSTEM = {
                DEFAULT: function () {
                },

                FORMBLOCK:function (form) {
                    form.block({
                        message: '<span class="text-semibold"><i class="icon-spinner4 spinner position-left"></i>&nbsp; Working on request</span>',
                        overlayCSS: {
                            backgroundColor: '#fff',
                            opacity: 0.8,
                            cursor: 'wait'
                        },
                        css: {
                            border: 0,
                            padding: '10px 15px',
                            color: '#fff',
                            width: 'auto',
                            '-webkit-border-radius': 2,
                            '-moz-border-radius': 2,
                            backgroundColor: '#333'
                        }
                    });
                },

                FORMUNBLOCK: function (form) {
                    form.unblock();
                },

                FORMSUBMIT: function(form, callback, block="none"){
                    form.ajaxSubmit({
                        dataType:'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSubmit:function(){
                            form.find('button[type="submit"]').button('loading');
                            if(block == "none"){
                                form.block({
                                    message: '<span class="text-semibold"><i class="icon-spinner4 spinner position-left"></i>&nbsp; Working on request</span>',
                                    overlayCSS: {
                                        backgroundColor: '#fff',
                                        opacity: 0.8,
                                        cursor: 'wait'
                                    },
                                    css: {
                                        border: 0,
                                        padding: '10px 15px',
                                        color: '#fff',
                                        width: 'auto',
                                        '-webkit-border-radius': 2,
                                        '-moz-border-radius': 2,
                                        backgroundColor: '#333'
                                    }
                                });
                            }
                        },
                        complete: function(){
                            form.find('button[type="submit"]').button('reset');
                            if(block == "none"){
                                form.unblock();
                            }
                        },
                        success:function(data){
                            callback(data);
                        },
                        error: function(errors) {
                            callback(errors);
                        }
                    });
                },

                AJAX: function(url, method, data, callback, loading="none", msg="Updating Data"){
                    $.ajax({
                        url: url,
                        type: method,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType:'json',
                        data: data,
                        beforeSend:function(){
                            if(loading != "none"){
                                $(loading).block({
                                    message: '<span class="text-semibold"><i class="icon-spinner4 spinner position-left"></i> '+msg+'</span>',
                                    overlayCSS: {
                                        backgroundColor: '#fff',
                                        opacity: 0.8,
                                        cursor: 'wait'
                                    },
                                    css: {
                                        border: 0,
                                        padding: '10px 15px',
                                        color: '#fff',
                                        width: 'auto',
                                        '-webkit-border-radius': 2,
                                        '-moz-border-radius': 2,
                                        backgroundColor: '#333'
                                    }
                                });
                            }
                        },
                        complete: function () {
                            $(loading).unblock();
                        },
                        success:function(data){
                            callback(data);
                        },
                        error: function(errors) {
                            callback(errors);
                        }
                    });
                },

                SHOWERROR: function(errors, form){
                    if(errors.status == 422){
                        $.each(errors.responseJSON.errors, function (index, value) {
                            form.find('[name="'+index+'"]').closest('div.form-group').append('<p class="error">'+value+'</span>');
                        });
                        form.find('p.error').first().closest('.form-group').find('input').focus();
                        setTimeout(function () {
                            form.find('p.error').remove();
                        }, 5000);
                    }else if(errors.status == 400){
                        mdtoast.error("Oops! "+errors.responseJSON.message, { position: "top center" });
                    }else{
                        if(errors.message){
                            mdtoast.error("Oops! "+errors.message, { position: "top center" });
                        }else{
                            mdtoast.error("Oops! "+errors.statusText, { position: "top center" });
                        }
                    }
                },

                NOTIFY: function(msg, type="success",element="none"){
                    if(element == "none"){
                        switch(type){
                            case "success":
                                mdtoast.success("Success : "+msg, { position: "top center" });
                            break;

                            default:
                                mdtoast.error("Oops! "+msg, { position: "top center" });
                                break;
                        }
                    }else{
                        element.find('div.alert').remove();
                        element.prepend(`<div class="alert bg-`+type+` alert-styled-left">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button> `+msg+`
                        </div>`);

                        setTimeout(function(){
                            element.find('div.alert').remove();
                        }, 10000);
                    }
                }
            }

            SYSTEM.DEFAULT();
        });
    </script>

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
                                window.open(data.message, '_blank').focus();
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

            @if(isset($id) && $id != 0)
                $('form#searchForm').find('[name="agent"]').val("{{$id}}");
            @endif

            var url = "{{route("panreportstatic")}}";
            var onDraw = function() {
            };

            var options = [
                { "data" : "status",
                    render:function(data, type, full, meta){
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

                        var menu = `<li class="dropdown-header">Action</li>`;
                        menu += `<li><a href="javascript:void(0)" class="print"><i class="icon-info22"></i>Print Invoice</a></li>`;
                        if(full.status == "success" || full.status == "accept" || full.status == "pending" || full.status == "initiated"){
                            menu += `<li><a href="javascript:void(0)" onclick="status(`+full.id+`, '`+full.product+`')"><i class="icon-info22"></i>Check Status</a></li>`;
                        }
                        
                        out +=  `<ul class="icons-list">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <ul class="dropdown-menu dropdown-menu-left">
                                            `+menu+`
                                        </ul>
                                    </li>
                                </ul>`;

                        return out;
                    }
                },
                { "data" : "name",
                    render:function(data, type, full, meta){
                        return `<div>
                                <span class='text-inverse m-l-10'>SN : <b>`+full.id +`</b> </span>
                                <div class="clearfix"></div>
                            </div><span style='font-size:13px' class="pull=right">`+full.created_at+`</span>`;
                    }
                },
                { "data" : "bank",
                    render:function(data, type, full, meta){
                        if(full.product == "aeps"){
                            return "Aadhar No. : "+full.number + "<br>Mobile : "+full.mobile+"<br>Bank : "+full.option3 + "<br>Remark : "+full.remark;
                        }else if(full.product == "matm"){
                            return "Card No. : "+full.number + "<br>Mobile : "+full.mobile+"<br>Bank : "+full.option3 + "<br>Remark : "+full.remark;
                        }else if(full.product == "dmt"){
                            return "Beneficiary : "+full.option2+"<br>Account : "+full.number+"<br>Bank: "+full.option3+"<br>Ifsc: "+full.option4+"<br>Sender: "+full.mobile + " ("+full.option1+")";
                        }else if(full.option1 == "bank"){
                            return "Name : "+full.description+"<br>Account : "+full.number+"<br>Bank : "+full.option3+"<br>Ifsc: "+full.option2;
                        }else if(full.product == "billpay" || full.product == "offlinebillpay"){
                            return "Number : "+full.number+"<br>Provider: "+full.providername+"<br>Name: "+full.option1+"<br>Duedate: "+full.option2;
                        }else if(full.product == "pancard"){
                            return "Number : "+full.number+"<br>Provider: "+full.providername+"<br>No Of Token: "+full.option1;
                        }else{
                            return "Number : "+full.number+"<br>Txn Id : "+full.txnid;
                        }
                    }
                },
                { "data" : "bank",
                    render:function(data, type, full, meta){
                        return "Reference : "+full.refno+"<br>Pay Id : "+full.payid;
                    }
                },
                { "data" : "bank",
                    render:function(data, type, full, meta){
                        if(full.product == "dmt"){
                            return "Amount : "+full.amount+"<br>Charge : "+full.charge+"<br>Profit : "+full.profit+"<br>Tds : "+full.tds+"<br>Gst : "+full.gst;
                        }else if(full.option1 == "bank" || full.option1 == "M"){
                            return "Amount : "+full.amount+"<br>Charge : "+full.charge+"<br>Tds : "+full.tds;
                        }else{
                            return "Amount : "+full.amount+"<br>Profit : "+full.profit+"<br>Tds : "+full.tds;
                        }
                    }
                }
            ];

            DT = datatableSetup(url, options, onDraw);
        });

        function SETTITLE(type, form) {
            $("[name='category']").val(type);
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
</head>

<body class="navbar-top @yield('bodyClass')" @yield('bodyextra')>
    <input type="hidden" name="dataType" value="">

    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-header">
            @if ($mydata['company']->logo)
                <a class="navbar-brand no-padding" href="javascript:void(0)">
                    <img src="{{asset('')}}public/{{$mydata['company']->logo}}" class=" img-responsive" alt="">
                </a>
            @else
                <a class="navbar-brand" href="javascript:void(0)" style="padding: 17px">
                    <span class="companyname" style="color: black">{{$mydata['company']->companyname}}</span>
                </a>
            @endif

            <ul class="nav navbar-nav visible-xs-block">
                <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
            </ul>
        </div>

        <div class="navbar-collapse collapse" id="navbar-mobile">
            <div class="navbar-right">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <img src="{{asset('')}}/assets/nps/NSDL_Logo.png" style="width: 250px;padding: 10px;" alt="">
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-container">
        <div class="page-content">
            <div class="content-wrapper">
                <div class="content">
                    <h3>Open NSDL Payments Bank Account Online</h3>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Account Details</h5>
                                </div>

                                <form action="{{route('accountNsdlProcess')}}" method="post" id="transactionForm"> 
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
                        <form method="post" action="https:\\assisted-service.egov-nsdl.com\SpringBootFormHandling\PanStatusReq" target="_blank" name="f1">
                            <input type="hidden" name="req" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
