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
                                        <li><a href="#home">Home</a></li>
                                        <li><a href="#services">services</a></li>
                                        <li><a href="#about">about</a></li>
                                        <li><a href="#contact">Contact Us</a></li>
                                    </ul>
                                </div>

                            </nav>
                            <!-- Main Menu End-->

                            <!-- Menu Button -->
                            <div class="menu-btn">
                                <a href="{{route('mylogin')}}" class="theme-btn">Login</a>
                                <a href="{{asset('public/assets/app-sakshi.apk')}}" class="theme-btn" download="">Download App</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Header Upper-->
        </header>
    
        <section class="hero-section overlay bgs-cover pb-150" id="home" style="background-image: url({{asset('')}}/assets/slide.jpeg);">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-9">
                        <div class="hero-content text-center text-white">
                           <span class="sub-title d-block wow fadeInUp delay-0-2s">EMPOWER YOUR BUSINESS</span>
                            <h1 class="wow fadeInUp delay-0-4s mt-20">Excellent Banking services for your success</h1>
                            <div class="hero-btn mt-35 wow fadeInUp delay-0-6s">
                                <a href="{{route('mylogin')}}" class="theme-btn">Start With Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="services-section pt-120 rpt-100 pb-90 rpb-70" id="services">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-sm-6">
                        <div class="service-box wow fadeInUp delay-0-2s">
                            <div class="service-normal">
                                <div class="icon">
                                   <i class="flaticon flaticon-computer"></i>
                                </div>
                                <h6>Aeps Service</h6>
                                <p>We are started to providing aadhaar enabled payment system services with same day Settlement process.</p>
                                <a class="btn-circle" href="{{route('mylogin')}}"><i class="fas fa-long-arrow-alt-right"></i></a>
                            </div>
                            <div class="service-hover bg-blue text-white">
                                <h3>Preparing For Your Business Success With Aeps Banking</h3>
                                <p>We are started to providing aadhaar enabled payment system services with same day Settlement process</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="service-box wow fadeInUp delay-0-4s">
                            <div class="service-normal">
                                <div class="icon">
                                   <i class="flaticon flaticon-web-development-4"></i>
                                </div>
                                <h6>Money Transfer</h6>
                                <p>Send money online or in person to friends and family around the world to more than 200 countries.</p>
                                <a class="btn-circle" href="{{route('mylogin')}}"><i class="fas fa-long-arrow-alt-right"></i></a>
                            </div>
                            <div class="service-hover bg-blue text-white">
                                <h3>Banking for the New India</h3>
                                <p>Send money online or in person to friends and family around the world to more than 200 countries.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="service-box wow fadeInUp delay-0-6s">
                            <div class="service-normal">
                                <div class="icon">
                                   <i class="flaticon flaticon-web"></i>
                                </div>
                                <h6>Billpayment Services</h6>
                                <p>Service Provider of Bill Payment-Postpaid Bill Payment, Electricity Bill Payment for any company etc</p>
                                <a class="btn-circle" href="{{route('mylogin')}}"><i class="fas fa-long-arrow-alt-right"></i></a>
                            </div>
                            <div class="service-hover bg-blue text-white">
                                <h3>Preparing For Your Business Success With All Billpayment</h3>
                                <p>Service Provider of Bill Payment-Postpaid Bill Payment, Electricity Bill Payment for any company etc</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="service-box wow fadeInUp delay-0-8s">
                            <div class="service-normal">
                                <div class="icon">
                                   <i class="flaticon flaticon-data"></i>
                                </div>
                                <h6>Pancard Solution</h6>
                                <p>Apply for a New UTI Pan card, Lost Pan card, Damaged Pan card, Correction Pan card online with best cost</p>
                                <a class="btn-circle" href="{{route('mylogin')}}"><i class="fas fa-long-arrow-alt-right"></i></a>
                            </div>
                            <div class="service-hover bg-blue text-white">
                                <h3>Preparing For Your Business Success With Pancard Solution</h3>
                                <p>Apply for a New UTI Pan card, Lost Pan card, Damaged Pan card, Correction Pan card online with best cost</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-section bg-light-black pt-120 rpt-100" id="about">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="about-image-shape rmb-70 wow fadeInLeft delay-0-2s">
                            <img src="{{asset('')}}/frontassets/images/about/about.png" alt="About">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-content text-white pr-70 rpr-0 wow fadeInRight delay-0-2s">
                            <div class="section-title mb-35">
                                <span class="sub-title">Banking Services & Support For Business</span>
                                <h2>Banking for the New भारत</h2>
                            </div>
                            <p>Start Payin Do With a strong customer support and best services in Industry we claim to be No.1 Recharge Company in India.</p>
                            <ul class="list-style-one mt-15">
                                <li>Aadhar Enabled Banking</li>
                                <li>Neo Banking</li>
                                <li>Insurance Services</li>
                                <li>Recharge Services</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="featured-section bgs-cover pt-240 rpt-150 pb-120 rpb-100" id="featured" style="background-image: url({{asset('')}}/frontassets/images/feature/feature-bg.jpg);">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8 col-sm-10">
                        <div class="section-title text-center mb-35">
                            <span class="sub-title">WHO WE ARE</span>
                            <h2>We deal with the aspects of professional Digital Services</h2>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-xl-4 col-md-6">
                        <div class="feature-item wow fadeInUp delay-0-2s">
                            <div class="icon">
                                <i class="flaticon flaticon-art"></i>
                            </div>
                            <div class="feature-content">
                                <h5>Aeps Service</h5>
                                <p>We are started to providing aadhaar enabled payment system services with same day Settlement process.</p>
                                <a href="{{route('mylogin')}}" class="learn-more">Read More <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="feature-item wow fadeInUp delay-0-4s">
                            <div class="icon">
                                <i class="flaticon flaticon-cloud-computing-1"></i>
                            </div>
                            <div class="feature-content">
                                <h5>Money Transfer Services</h5>
                                <p>Send money online or in person to friends and family around the world to more than 200 countries.</p>
                                <a href="{{route('mylogin')}}" class="learn-more">Read More <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="feature-item wow fadeInUp delay-0-6s">
                            <div class="icon">
                                <i class="flaticon flaticon-development-3"></i>
                            </div>
                            <div class="feature-content">
                                <h5>Recharge Service</h5>
                                <p>Use InstantPay on the web, mobile or integrate our API with your business applications or accounting systems.</p>
                                <a href="{{route('mylogin')}}" class="learn-more">Read More <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="feature-item wow fadeInUp delay-0-8s">
                            <div class="icon">
                                <i class="flaticon flaticon-analysis-1"></i>
                            </div>
                            <div class="feature-content">
                                <h5>Billpayment Service</h5>
                                <p>Service Provider of Bill Payment-Postpaid Bill Payment, Electricity Bill Payment for any company etc</p>
                                <a href="{{route('mylogin')}}" class="learn-more">Read More <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="feature-item wow fadeInUp delay-1-0s">
                            <div class="icon">
                                <i class="flaticon flaticon-web-development"></i>
                            </div>
                            <div class="feature-content">
                                <h5>Uti Pancard</h5>
                                <p>Apply for a New UTI Pan card, Lost Pan card, Damaged Pan card, Correction Pan card online with best cost</p>
                                <a href="{{route('mylogin')}}" class="learn-more">Read More <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="feature-item wow fadeInUp delay-1-2s">
                            <div class="icon">
                                <i class="flaticon flaticon-plan"></i>
                            </div>
                            <div class="feature-content">
                                <h5>Customer Support</h5>
                                <p>Our customers are at the heart of PayinDo. We’re there to provider solution for all your queries any time.</p>
                                <a href="{{route('mylogin')}}" class="learn-more">Read More <i class="fas fa-arrow-right"></i></a>
                            </div>
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
                                        <li><a href="{{route('policy')}}">Terms & Condition</a></li>
                                        <li><a href="{{route('policy')}}">Refund and Cancellation Policy</a></li>
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