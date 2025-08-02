<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('pagetitle') - {{$mydata['company']->companyname}}</title>
    <link rel="shortcut icon" href="{{asset('')}}assets/images/rajpaylogoremovebg.png">
<meta name="csrf-token" content="{{ csrf_token() }}">
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
.vertical {
   columns: 2;
   column-gap: 0;
   column-fill: balance;
   column-rule: 5px solid;
   
   text-align: center;
   padding: 10px;
}
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
                var todate   =  $(this).find('input[name="to_date"]').val();
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
    @stack('script')
</head>

<body class="navbar-top @yield('bodyClass')" @yield('bodyextra')>
    <input type="hidden" name="dataType" value="">

    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-header">
            @if ($mydata['company']->logo)
                <div class="vertical">
                <div>
                <a class="navbar-brand no-padding" href="javascript:void(0)">
                    <img src="{{asset('')}}public/{{$mydata['company']->logo}}" class=" img-responsive" alt="">
                </a>
                </div>
                <div>                 
                <a class="navbar-brand no-padding" href="javascript:void(0)">
                    <img src="{{asset('')}}public/logos/t-logo.png" class=" img-responsive" style="width:75%; height:75%;" alt="">
                </a>
                </div>                 
                </div>
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
                        <a class="dropdown-toggle legitRipple p-10" data-toggle="dropdown" aria-expanded="false">
                            <span class="p-0">@yield('title')</span><br>
                            <span class="p-0">@yield('name')</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{$RETURNURL ?? ''}}" class="p-10">
                            <button type="button" class="btn bg-slate btn-labeled btn-sm legitRipple"><b><i class="icon-switch2"></i></b> Back To Emitra</button>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-container">
        <div class="page-content">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
