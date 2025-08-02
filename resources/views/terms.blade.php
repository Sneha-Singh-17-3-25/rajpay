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
                                <span class="sub-title">Terms & Conditions</span>
                            </div>
                            <p>The terms "We" / "Us" / "Our"/”Company” individually and collectively refer to pay in do services and the terms "Visitor” ”User” refer to the users.
This page states the Terms and Conditions under which you (Visitor) may visit this website www.payindo.in Please read this page carefully. If you do not accept the Terms and Conditions stated here, we would request you to exit this site. The business, any of its business divisions and / or its subsidiaries, associate companies or subsidiaries to subsidiaries or such other investment companies (in India or abroad) reserve their respective rights to revise these Terms and Conditions at any time by updating this posting. You should visit this page periodically to re-appraise yourself of the Terms and Conditions, because they are binding on all users of this Website.</p>
<p>USE OF CONTENT
All logos, brands, marks headings, labels, names, signatures, numerals, shapes or any combinations thereof, appearing in this site, except as otherwise noted, are properties either owned, or used under licence, by the business and / or its associate entities who feature on this Website. The use of these properties or any other content on this site, except as provided in these terms and conditions or in the site content, is strictly prohibited.You may not sell or modify the content of this Website or reproduce, display, publicly perform, distribute, or otherwise use the materials in any way for any public or commercial purpose without the respective organisation’s or entity’s written permission.</p>
<p>Security Rules
Visitors are prohibited from violating or attempting to violate the security of the Web site, including, without limitation, (1) accessing data not intended for such user or logging into a server or account which the user is not authorised to access, (2) attempting to probe, scan or test the vulnerability of a system or network or to breach security or authentication measures without proper authorisation, (3) attempting to interfere with service to any user, host or network, including, without limitation, via means of submitting a virus or "Trojan horse" to the Website, overloading, "flooding", "mail bombing" or "crashing", or (4) sending unsolicited electronic mail, including promotions and/or advertising of products or services. Violations of system or network security may result in civil or criminal liability. The business and / or its associate entities will have the right to investigate occurrences that they suspect as involving such violations and will have the right to involve, and cooperate with, law enforcement authorities in prosecuting users who are involved in such violations.</p>
<p>General Rules
Visitors may not use the Web Site in order to transmit, distribute, store or destroy material (a) that could constitute or encourage conduct that would be considered a criminal offence or violate any applicable law or regulation, (b) in a manner that will infringe the copyright, trademark, trade secret or other intellectual property rights of others or violate the privacy or publicity of other personal rights of others, or (c) that is libellous, defamatory, pornographic, profane, obscene, threatening, abusive or hateful.</p>
<p>INDEMNITY
The User unilaterally agree to indemnify and hold harmless, without objection, the Company, its officers, directors, employees and agents from and against any claims, actions and/or demands and/or liabilities and/or losses and/or damages whatsoever arising from or resulting from their use of www.payindo.in or their breach of the terms .</p>
<p>LIABILITY
User agrees that neither Company nor its group companies, directors, officers or employee shall be liable for any direct or/and indirect or/and incidental or/and special or/and consequential or/and exemplary damages, resulting from the use or/and the inability to use the service or/and for cost of procurement of substitute goods or/and services or resulting from any goods or/and data or/and information or/and services purchased or/and obtained or/and messages received or/and transactions entered into through or/and from the service or/and resulting from unauthorized access to or/and alteration of user's transmissions or/and data or/and arising from any other matter relating to the service, including but not limited to, damages for loss of profits or/and use or/and data or other intangible, even if Company has been advised of the possibility of such damages.</p>
<p>User further agrees that Company shall not be liable for any damages arising from interruption, suspension or termination of service, including but not limited to direct or/and indirect or/and incidental or/and special consequential or/and exemplary damages, whether such interruption or/and suspension or/and termination was justified or not, negligent or intentional, inadvertent or advertent.</p>
<p>User agrees that Company shall not be responsible or liable to user, or anyone, for the statements or conduct of any third party of the service. In sum, in no event shall Company's total liability to the User for all damages or/and losses or/and causes of action exceed the amount paid by the User to Company, if any, that is related to the cause of action.</p>
<p>DISCLAIMER OF CONSEQUENTIAL DAMAGES
In no event shall Company or any parties, organizations or entities associated with the corporate brand name us or otherwise, mentioned at this Website be liable for any damages whatsoever (including, without limitations, incidental and consequential damages, lost profits, or damage to computer hardware or loss of data information or business interruption) resulting from the use or inability to use the Website and the Website material, whether based on warranty, contract, tort, or any other legal theory, and whether or not, such organization or entities were advised of the possibility of such damages.</p>
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