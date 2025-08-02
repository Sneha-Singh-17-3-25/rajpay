<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Receipt</title>
    <link rel="shortcut icon" href="{{asset('')}}assets/icon/aps.png">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{asset('')}}assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="{{asset('')}}assets/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
    <link href="{{asset('')}}assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="{{asset('')}}assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="{{asset('')}}assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="{{asset('')}}assets/css/colors.css" rel="stylesheet" type="text/css">

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
    <script type="text/javascript" src="{{asset('')}}assets/js/core/app.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $(window).load(function () {
                @if(isset($status))
                    $("#receipt").modal("show");
                @endif
            });

            $(".modal").on('hidden.bs.modal', function () {
                window.location.href = "{{url('pancard/nsdl')}}";
            });
        });
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
    </div>

    <div class="page-container">

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
                            <h4 class="text-success text-center">Pancard Registration Completed Successfully</h4>
                            <p>Please check your registerd email or mobile number for application details.</p>
                        @else
                            <h4 class="text-danger text-center">Pancard Registration Failed</h4>
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
                        <a href="{{url("pancard/nsdl")}}">
                            <button type="button" class="btn  bg-teal-800 btn-raised legitRipple">Apply New Pancard</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</body>
</html>
