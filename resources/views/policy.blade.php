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
                                        <img src="{{asset('')}}public/{{$company->logo}}" class=" img-responsive" alt="" width="200px">
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
                                                <img src="{{asset('')}}public/{{$company->logo}}" class=" img-responsive" alt="">
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
                                <span class="sub-title">Privacy & Policy</span>
                            </div>
                            <p>This Privacy Policy provides you with details about the manner in which your data is collected, stored & used by us. You are advised to read this Privacy Policy carefully. By visiting {{$company->companyname}} services website/application you expressly give us consent to use & disclose your personal information in accordance with this Privacy Policy. If you do not agree to the terms of the policy, please do not use or access {{$company->companyname}} website, WAP site or mobile applications.</p>
                            <p>Note : Our privacy policy may change at any time without prior notification. To make sure that you are aware of any changes, kindly review the policy periodically. This Privacy Policy shall apply uniformly to {{$company->companyname}} desktop website, {{$company->companyname}} mobile WAP site & payido mobile applications</p>
                            <p>General
We will not sell, share or rent your personal information to any 3rd party or use your email address/mobile number for unsolicited emails and/or SMS. Any emails and/or SMS sent by payido will only be in connection with the provision of agreed services & products and this Privacy Policy.</p>
<p>Periodically, we may reveal general statistical information about payido & its users, such as number of visitors, number and type of goods and services purchased, etc.We reserve the right to communicate your personal information to any third party that makes a legally-compliant request for its disclosure.</p>
<p>Personal Information
Personal Information means and includes all information that can be linked to a specific individual or to identify any individual, such as name, address, mailing address, telephone number, email ID, credit card number, cardholder name, card expiration date, information about your mobile phone, DTH service, data card, electricity connection, Smart Tags and any details that may have been voluntarily provided by the user in connection with availing any of the services on {{$company->companyname}} services</p>
<p>When you browse through {{$company->companyname}} services, we may collect information regarding the domain and host from which you access the internet, the Internet Protocol [IP] address of the computer or Internet service provider [ISP] you are using, and anonymous site statistical data.</p>
<p>Use of Personal Information
We use personal information to provide you with services & products you explicitly requested for, to resolve disputes, troubleshoot concerns, help promote safe services, collect money, measure consumer interest in our services, inform you about offers, products, services, updates, customize your experience, detect & protect us against error, fraud and other criminal activity, enforce our terms and conditions, etc.</p>
<p>We also use your contact information to send you offers based on your previous orders and interests.We may occasionally ask you to complete optional online surveys. These surveys may ask you for contact information and demographic information (like zip code, age, gender, etc.). We use this data to customize your experience at a{{$company->companyname}}, providing you with content that we think you might be interested in and to display content according to your preferences.</p>
<p>Log Data
We want to inform you that whenever you use our Service, in a case of an error in the app we collect data and information (through third party products) on your phone called Log Data. This Log Data may include information such as your device Internet Protocol (“IP”) address, device name, operating system version, the configuration of the app when utilizing our Service, the time and date of your use of the Service, and other statistics.we dont save or upload any contact data on server.</p>
<p>Cookies
A "cookie" is a small piece of information stored by a web server on a web browser so it can be later read back from that browser. pay indo services  uses cookie and tracking technology depending on the features offered. No personal information will be collected via cookies and other tracking technology; however, if you previously provided personally identifiable information, cookies may be tied to such information. Aggregate cookie and tracking information may be shared with third parties.</p>
<p>Links to Other Sites
Our site links to other websites that may collect personally identifiable information about you {{$company->companyname}} services is not responsible for the privacy practices or the content of those linked websites.</p>
<p>Security
{{$company->companyname}} services has stringent security measures in place to protect the loss, misuse, and alteration of the information under our control. Whenever you change or access your account information, we offer the use of a secure server. Once your information is in our possession we adhere to strict security guidelines, protecting it against unauthorized access.</p>
<p>Consent
By using {{$company->companyname}} and/or by providing your information, you consent to the collection and use of the information you disclose on {{$company->companyname}} services in accordance with this Privacy Policy, including but not limited to your consent for sharing your information as per this privacy policy.</p>
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
                                                <img src="{{asset('')}}public/{{$company->logo}}" class=" img-responsive" alt="" width="200px">
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
                            
                                        <li><a href="{{route('policy')}}">Privacy & Policy</a></li>

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