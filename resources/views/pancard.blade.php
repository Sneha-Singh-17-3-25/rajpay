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
            $(window).load(function () {
                @if(isset($status))
                    $("#receipt").modal("show");
                @endif
            });

            $(".modal").on('hidden.bs.modal', function () {
                window.location.href = "{{url('pan/apply/new')}}";
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
                    <li class="dropdown dropdown-user">
                        <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <img src="{{asset('')}}public/profiles/user.png" alt="">
                            <span>Test</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-container">
        <div class="page-content">
            <div class="content-wrapper">
                <div class="content">
                    <div class="tabbable">
                        <ul class="nav nav-tabs bg-teal-600 nav-tabs-component no-margin-bottom">
                            <li class="active"><a href="#recharge" data-toggle="tab" id="mobileTab" class="legitRipple" aria-expanded="false" onclick="SETTITLE('A')">New Pan</a></li>
                            <li><a href="#recharge" data-toggle="tab" id="dthTab" class="legitRipple" aria-expanded="false" onclick="SETTITLE('CR')">Pan Correction</a></li>
                            <li><a href="#reoprt" data-toggle="tab" id="reportTab" class="legitRipple" aria-expanded="false">Report</a></li>
                            <li><a href="#panstatus" data-toggle="tab" id="reportTab" class="legitRipple" aria-expanded="false">Pancard Status Check</a></li>
                            <li><a href="#transactionstatus" data-toggle="tab" id="reportTab" class="legitRipple" aria-expanded="false">Transaction Status Check</a></li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="recharge">
                                <div class="panel panel-default">
                                    <form action="{{route('panApplyProcess')}}" method="post" id="transactionForm"> 
                                        <input type="hidden" name="option2" value="Y">
                                        <input type="hidden" name="category" value="A">
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
                                            <button type="submit" class="btn bg-teal-800 btn-labeled legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Submitting"><b><i class=" icon-paperplane"></i></b> Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="tab-pane" id="reoprt">
                                <div class="panel panel-default">

                                    <form id="searchForm">
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
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>Action</th>
                                                    <th>Transaction ID</th>
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
                </div>
            </div>
        </div>
    </div>

    @if(isset($status))
        <div id="receipt" class="modal fade" data-backdrop="false" data-keyboard="false">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header bg-slate">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Transaction Information</h4>
                    </div>

                    <div class="modal-body">
                        @if($status == "success")
                            <h4 class="text-success text-center">Pan Registration Successfull</h4>
                            <p>Please check your registerd email or mobile number for application details.</p>
                        @else
                            <h4 class="text-danger text-center">Pan Registration Failed</h4>
                        @endif

                        <div id="receptTable">
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
</body>
</html>
