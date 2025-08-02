<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{$company->companyname ?? "Payin Do"}} - Neo Banking</title>
    <link rel="shortcut icon" href="{{asset('')}}/frontassets/images/favicon.png" type="image/x-icon">
    <!--====== Google Fonts ======-->
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@400;500;600;700&amp;family=Oswald:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
    
    <!--====== Font Awesome ======-->
    <link rel="stylesheet" href="{{asset('')}}/frontassets/css/font-awesome-5.9.0.css">
    <!--====== Bootstrap ======-->
    <link rel="stylesheet" href="{{asset('')}}/frontassets/css/bootstrap.min.css">
    <!--====== Magnific Popup ======-->
    <link rel="stylesheet" href="{{asset('')}}/frontassets/css/magnific-popup.css">
    <!--====== Falticon ======-->
    <link rel="stylesheet" href="{{asset('')}}/frontassets/css/flaticon.css">
    <!--====== Animate ======-->
    <link rel="stylesheet" href="{{asset('')}}/frontassets/css/animate.css">
    <!--====== Slick ======-->
    <link rel="stylesheet" href="{{asset('')}}/frontassets/css/slick.css">
    <!--====== Main Style ======-->
    <link rel="stylesheet" href="{{asset('')}}/frontassets/css/style.css">
    
</head>
<body>
    <div class="page-wrapper">

        <!-- Preloader -->
        <div class="preloader">
            <div class="theme-loader"></div>
        </div>

        <!-- main header -->
        <header class="main-header header-one">

           <div class="header-top bg-lighter py-10">
               <div class="top-left">
                    <ul>
                        <li>Call Us: <a href="callto:{{$companydata->number ?? ''}}">{{$companydata->number ?? ''}}</a></li>
                        <li>Email us: <a href="mailto:{{$companydata->email ?? ''}}">{{$companydata->email ?? ''}}</a></li>
                        <li>Our address: {{$companydata->address ?? ''}}</li>
                    </ul>
               </div>
               <div class="top-right">
                    {{-- <div class="office-time">
                        <i class="far fa-clock"></i><span>08:00 am - 06:00 pm</span>
                    </div> --}}
                    <div class="social-style-one">
                        <a href="http://facebook.com/"><i class="fab fa-facebook-f"></i></a>
                        <a href="http://twitter.com/"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.pinterest.com/"><i class="fab fa-pinterest-p"></i></a>
                    </div>
               </div>
           </div>
           
            <!--Header-Upper-->
            <div class="header-upper bg-white">
                <div class="container-fluid clearfix">

                    <div class="header-inner d-flex align-items-center">
                        <div class="logo-outer bg-blue">
                            <div class="logo" style="padding: 0px;">
                                <a href="{{route('web')}}">
                                    @if ($company->logo)
                                        <img src="{{asset('')}}public/logos/{{$company->logo}}" class=" img-responsive" alt="" width="200px">
                                    @else
                                        <span class="companyname" style="color: black">{{$company->companyname}}</span>
                                    @endif
                                </a>
                            </div>
                        </div>

                        <div class="nav-outer clearfix d-flex align-items-center">
                            <!-- Main Menu -->
                            <nav class="main-menu navbar-expand-lg">
                                <div class="navbar-header">
                                   <div class="mobile-logo bg-blue p-15">
                                       <a href="{{route('web')}}">
                                            @if ($company->logo)
                                                <img src="{{asset('')}}public/logos/{{$company->logo}}" class=" img-responsive" alt="">
                                            @else
                                                <span class="companyname" style="color: black">{{$company->companyname}}</span>
                                            @endif
                                        </a>
                                   </div>

                                    <!-- Toggle Button -->
                                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                </div>

                                <div class="navbar-collapse collapse clearfix">
                                    <ul class="navigation onepage clearfix">
                                        <li><a href="{{route('web')}}">Home</a></li>
                                        <li><a href="{{route('web')}}">services</a></li>
                                        <li><a href="{{route('web')}}">about</a></li>
                                        <li><a href="{{route('web')}}">Contact Us</a></li>
                                    </ul>
                                </div>

                            </nav>
                            <!-- Main Menu End-->

                            <!-- Menu Button -->
                            <div class="menu-btn">
                                <a href="{{route('mylogin')}}" class="theme-btn">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Header Upper-->
        </header>

        <section class="about-section style-four py-120 rpy-100">
            <div class="container rpb-95">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <div class="about-content pr-90 rpr-0 rmb-55 wow fadeInLeft delay-0-2s">
                            <div class="section-title mb-35">
                                <span class="sub-title">Refund and Cancellation</span>
                            </div>
                            <p>Our focus is complete customer satisfaction. In the event, if you are displeased with the services provided, we will refund back the money, provided the reasons are genuine and proved after investigation. Please read the fine prints of each deal before buying it, it provides all the details about the services or the product you purchase. In case of dissatisfaction from our services, clients have the liberty to cancel their projects and request a refund from us. Our Policy for the cancellation and refund will be as follows:

                            <h4>Cancellation Policy</h4>
                            <p>For Cancellations please contact the us at  smcweb@yahoo.com Requests received later than 3 business days prior to the end of the current service period will be treated as cancellation of services for the next service period.</p>

                            <h4>Refund Policy</h4>
                            <p>We will try our best to provide best service to our user, In case any client is not completely satisfied with our service we can provide a refund. If paid by credit card, refunds will be issued to the original credit card provided at the time of purchase and in case of payment gateway name payments refund will be made to the same account.</p>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="news-section pb-90 rpb-70" id="contact">
            <footer class="main-footer footer-one text-white">
                <div class="footer-widget-area bgs-cover pt-100 pb-50" style="background-image: url({{asset('')}}/frontassets/images/footer/footer-bg-dots.png);">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4 col-sm-6">
                                <div class="footer-widget about-widget">
                                    <div class="footer-logo mb-35">
                                        <a href="{{route("web")}}">
                                            @if ($company->logo)
                                                <img src="{{asset('')}}public/logos/{{$company->logo}}" class=" img-responsive" alt="" width="200px">
                                            @else
                                                <span class="companyname" style="color: black">{{$company->companyname}}</span>
                                            @endif
                                        </a>
                                    </div>
                                    <div class="text">
                                        Start your own Multi Recharge Bussiness with {{$company->companyname}},
{{$company->companyname}} Recharge committed to excellent support to his registered members. With a strong customer support and best services in Industry we claim to be No.1 Recharge Company in India.
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="footer-widget link-widget ml-20 rml-0">
                                    <h4 class="footer-title">Page Links</h4>
                                    <ul class="list-style-two">
                                        <li><a href="{{route('web')}}">Home</a></li>
                                        <li><a href="{{route('policy')}}">Privacy & Policy</a></li>
                                        <li><a href="{{route('term')}}">Terms & Condition</a></li>
                                        <li><a href="{{route('refund')}}">Refund and Cancellation Policy</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="footer-widget contact-widget mr-30 rmr-0">
                                    <h4 class="footer-title">Contacts</h4>
                                    <ul class="list-style-two">
                                        <li><i class="fas fa-map-marker-alt"></i> {{$companydata->address ?? ""}}</li>
                                        <li><i class="fas fa-phone-alt"></i> <a href="#">{{$companydata->number ?? ""}}</a></li>
                                        <li><i class="fas fa-envelope"></i> <a href="#">{{$companydata->email ?? ""}}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="copyright-area bg-blue">
                    <div class="container">
                        <div class="copyright-inner pt-15">
                            <div class="social-style-one mb-10">
                                <a href="http://facebook.com/"><i class="fab fa-facebook-f"></i></a>
                                <a href="http://twitter.com/"><i class="fab fa-twitter"></i></a>
                                <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                                <a href="https://www.pinterest.com/"><i class="fab fa-pinterest-p"></i></a>
                            </div>
                            <p>Copyright 2021 Restly All Rights Reserved.</p>
                        </div>
                    </div>
                </div>
            </footer>
        </section>

    </div>
    <!--End pagewrapper-->
   
    <!-- Scroll Top Button -->
    <button class="scroll-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></button>
    <!--====== Jquery ======-->
    <script src="{{asset('')}}/frontassets/js/jquery-3.6.0.min.js"></script>
    <!--====== Bootstrap ======-->
    <script src="{{asset('')}}/frontassets/js/bootstrap.min.js"></script>
    <!--====== Appear Js ======-->
    <script src="{{asset('')}}/frontassets/js/appear.min.js"></script>
    <!--====== Slick ======-->
    <script src="{{asset('')}}/frontassets/js/slick.min.js"></script>
    <!--====== Magnific Popup ======-->
    <script src="{{asset('')}}/frontassets/js/jquery.magnific-popup.min.js"></script>
    <!--====== Isotope ======-->
    <script src="{{asset('')}}/frontassets/js/isotope.pkgd.min.js"></script>
    <!--  WOW Animation -->
    <script src="{{asset('')}}/frontassets/js/wow.js"></script>
    <!-- Custom script -->
    <script src="{{asset('')}}/frontassets/js/script.js"></script>

</body>
</html>