<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<title>Sahaz It Solutions Pvt.Ltd.</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
<!--Bootstrap-->
<link rel="stylesheet" type="text/css" href="{{asset("")}}front/css/bootstrap.css">
<!--Stylesheets-->
<link rel="stylesheet" type="text/css" href="{{asset("")}}front/css/style.css">
<!--Responsive-->
<link rel="stylesheet" type="text/css" href="{{asset("")}}front/css/responsive.css">
<!--Animation-->
<link rel="stylesheet" type="text/css" href="{{asset("")}}front/css/animate.css">
<!--Prettyphoto-->
<link rel="stylesheet" type="text/css" href="{{asset("")}}front/css/prettyPhoto.css">
<!--Font-Awesome-->
<link rel="stylesheet" type="text/css" href="{{asset("")}}front/css/font-awesome.css">
<!--Owl-Slider-->
<link rel="stylesheet" type="text/css" href="{{asset("")}}front/css/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="{{asset("")}}front/css/owl.theme.css">
<link rel="stylesheet" type="text/css" href="{{asset("")}}front/css/owl.transitions.css">
<link href="{{asset('')}}assets/css/snackbar.css" rel="stylesheet">
<link href="{{asset('')}}assets/css/jquery-confirm.min.css" rel="stylesheet" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>

    <snackbar></snackbar>
<body data-spy="scroll" data-target=".navbar-default" data-offset="100" style="overflow-x: clip;">
<!--Preloader-->
<div id="preloader">
  <div id="pre-status">
    <div class="preload-placeholder"></div>
  </div>
</div>
<!--Navigation-->
<header id="menu">
  <div class="navbar navbar-default navbar-fixed-top" style="box-shadow:0px 4px 10px #3609a3;">
    <div class="container">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button"style="background:white;" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar" style="color:#0096ff:"></span> <span class="icon-bar" style="color:#0096ff:"></span> <span class="icon-bar" style="color:#0096ff:"></span> </button>
          <a class="navbar-brand" href="#menu"><img src="{{asset("")}}front/images/Logo/logo.png" style="width:300px;" alt="Logo is here"></a> </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li class="active"><a class="scroll" href="#menu">Home</a></li>
            <li><a class="scroll" href="#service">Advantages</a></li>
            <li><a class="scroll" href="#features">Service</a></li>
            <li><a class="scroll" href="#team">Our Partners</a></li>
            <li><a class="scroll" href="#contact">Contact</a></li>
            <li><a class="scroll" href="" data-toggle="modal" data-target="#about">About</a></li>
            <li><a class="scroll feb-button-signin" href="{{url("login")}}">Sign In</a></li>
            <li><a class="scroll feb-button-signin" href="" data-toggle="modal" data-target="#registerModal" data-whatever="@mdo">Sign Up</a></li>

        </ul>
        </div>
        <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
    </div>
  </div>
</header>
<!--Slider-Start-->

<section id="slider" style="margin-top:70px; margin-left:+2px; margin-right:+4px">
  <div class="owl-carousel owl-theme">
    <div class="slider">
      <img class="slider-img" src="{{asset("")}}front/images/Slider/01.jpg">
    </div>
    <div class="slider">
      <img class="slider-img" src="{{asset("")}}front/images/Slider/02.jpg">
    </div>
    <div class="slider">
      <img class="slider-img" src="{{asset("")}}front/images/Slider/03.jpg">
    </div>
    <div class="slider">
      <img class="slider-img" src="{{asset("")}}front/images/Slider/04.jpg">
    </div>
    <div class="slider">
      <img class="slider-img" src="{{asset("")}}front/images/Slider/05.jpg">
    </div>
    <div class="slider">
      <img class="slider-img" src="{{asset("")}}front/images/Slider/06.jpg">
    </div>
  </div>
  <!--/#home-carousel-->
</section>


<!-- loging form -->
<div class="modal fade transform " id="signin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content feb-model">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center feb-font-title" id="exampleModalLabel">Sign In</h5>
      </div>
      <div class="modal-body">

    <form class="login-form" id="login" method="POST" action="{{route('authCheck')}}" novalidate="" style="margin-bottom: 20px">
        {{ csrf_field() }}
        <input type="hidden" name="gps_location">
          <div class="form-group">
            <input type="text" name="mobile" class="form-control feb-height" id="Mobile Number" placeholder="Mobile Number" required="">
          </div>
          <div class="form-group">
            <input type="password" name="password" class="form-control feb-height" id="password" placeholder="Password" required="">
          </div>
          <div class="text-center"><button class="feb-btn" type="submit">Login</button></div>
          <div class="feb-signup">
            
          </div>
          <div class="feb-forgot" onclick="LOGINSYSTEM.PASSWORDRESET()">Forgot password?</div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- login form end  -->

<script>
  $(document).ready(function(){
    $("#signupbtn").click(function(){
      $("#signin").modal("hide");
      $("#signup").modal("show");
    });
  });
  $(document).ready(function(){
    $("#signinbtn").click(function(){
      $("#signin").modal("show");
      $("#signup").modal("hide");
    });
  });
  $(document).ready(function(){
    $("#forgotbtn").click(function(){
      $("#signin").modal("hide");
      $("#forgot").modal("show");
    });
  });
</script>

<!--Service-Section-Start-->
<section id="service">
  <div class="container ">
    <div class="col-md-8 col-md-offset-2">
      <div class="heading">
        <h2>Advantages of Sahaz Mo<span>ney</span></h2>
        <div class="line"></div>
      </div>
    </div>
    <div class="row feb-side">
      <div class="features-sec">
        <div class="col-lg-4 col-md-6 col-12 wow slideInLeft" data-wow-duration="1s" data-wow-delay=".2s">
          <div class="feb-card">
            <div class="feb-title">All Financial Servicres In One Platform</div>
            <div>
              <img class="feb-image" src="{{asset("")}}front/images/Service/banking.png">
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12 wow slideInUp" data-wow-duration="1s" data-wow-delay=".2s">
          <div class="feb-card">
            <div class="feb-title">Instant & Easy Onboarding On Our Platform</div>
            <div>
              <img class="feb-image" src="{{asset("")}}front/images/Service/onboarding.png">
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12 wow slideInRight" data-wow-duration="1s" data-wow-delay=".2s">
          <div class="feb-card">
            <div class="feb-title">No Investment Required Become Bussiness With Us</div>
            <div>
              <img class="feb-image" src="{{asset("")}}front/images/Service/make-money.png">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row feb-side-right">
      <div class="features-sec">
        <div class="col-lg-4 col-md-6 col-12 wow slideInLeft" data-wow-duration="1s" data-wow-delay=".2s">
          <div class="feb-card">
            <div class="feb-title">Simple, Secure And Easy To Use</div>
            <div>
              <img class="feb-image" src="{{asset("")}}front/images/Service/login.png">
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12 wow slideInUp" data-wow-duration="1s" data-wow-delay=".2s">
          <div class="feb-card">
            <div class="feb-title">Get High Earning Opportunity With Us</div>
            <div>
              <img class="feb-image" src="{{asset("")}}front/images/Service/earn-money.png">
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12 wow slideInRight" data-wow-duration="1s" data-wow-delay=".2s">
          <div class="feb-card">
            <div class="feb-title">Easy And Instant Payout</div>
            <div>
              <img class="feb-image" style="margin-top:30px;" src="{{asset("")}}front/images/Service/instantpay.png">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--Features-Section-Start-->
<section id="features">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="heading">
          <h2>OUR SERVI<span>CE</span></h2>
          <div class="line"></div>
        </div>
      </div>
    </div>
    <div class="row service_btn">
      <div id="aeps-btn" class="col-lg-3 col-12 child-btn active-left">
        <div>AEPS</div>
      </div>
      <div id="bill-btn" class="col-lg-3 col-12 child-btn">
        <div >Bill Payment</div>
      </div>
      <div id="Insurance-btn" class="col-lg-3 col-12 child-btn">
        <div>Insurance</div>
      </div>
      <div id="Pancard-btn"class="col-lg-3 col-12 child-btn">
        <div>Pancard Services</div>
      </div>
    </div>
    <div class="row main-aeps" id="aeps-section">
      <div class="col-lg-4 col-12">
        <div class="feb-card-sev">
          <div class="sev-title">AEPS</div>
          <div><img src="{{asset("")}}front/images/Service/aeps.png"></div>
          <div class="sev-discription">Easy Cash Withdrawal Mini Statement & Instant Balance Inquiry</div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="feb-card-sev">
          <div class="sev-title">M-ATM</div>
          <div><img src="{{asset("")}}front/images/Service/mAtm.png" width="200px;"></div>
          <div class="sev-discription">Earn Extra Commion With Micro ATM Services</div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="feb-card-sev">
          <div class="sev-title">Money Transfer</div>
          <div><img src="{{asset("")}}front/images/Service/transfer-money.png" width="250px;"></div>
          <div class="sev-discription">Send Money To Anyone In Seconds</div>
        </div>
      </div>
    </div>
    <div class="row main-aeps" id="bill-section" style="display:none;">
      <div class="col-lg-4 col-12">
        <div class="feb-card-sev">
          <div class="sev-title">Mobile Recharge</div>
          <div><img src="{{asset("")}}front/images/Service/recharge-online.png" width="270px;"></div>
          <div class="sev-discription">Recharge Mobile Number in a Minute</div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="feb-card-sev">
          <div class="sev-title">DTH Recharge</div>
          <div><img src="{{asset("")}}front/images/Service/Utility.png" width="180px;"></div>
          <div class="sev-discription">Recharge DTH Number in a Minute</div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="feb-card-sev">
          <div class="sev-title">BBPS Bill Payment</div>
          <div><img src="{{asset("")}}front/images/Service/gas-bill.png" width="300px;"></div>
          <div class="sev-discription">Pay Your Bills Anytime Anywhere</div>
        </div>
      </div>
    </div>
    <div class="row main-aeps" id="Insurance-section" style="display:none;">
      <div class="col-lg-4 col-12">
        <div class="feb-card-sev">
          <div class="sev-title">Moter Insurance</div>
          <div><img src="{{asset("")}}front/images/Service/car-insurance.png" width="250px;"></div>
          <div class="sev-discription">Get a Good insurance for your car and bike, Today.</div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="feb-card-sev">
          <div class="sev-title">Health Insurance</div>
          <div><img src="{{asset("")}}front/images/Service/health-insurance.png" width="250px;"></div>
          <div class="sev-discription">Life secure in your hands. A Better value of Better Protection.</div>
        </div>
      </div>

    </div>
    <div class="row main-aeps" id="pan-section" style="display:none;">
      <div class="col-lg-4 col-12">
        <div class="feb-card-sev">
          <div class="sev-title">UTI Pancard</div>
          <div><img src="{{asset("")}}front/images/Service/utipan.png" width="200px;"></div>
          <div class="sev-discription">Create Your Pan Card Online Here.</div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="feb-card-sev">
          <div class="sev-title">NSDL Pancard</div>
          <div><img src="{{asset("")}}front/images/Service/nsdlpancard.png" width="250px;"></div>
          <div class="sev-discription">Create Your Pan Card Online Here.</div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  $(document).ready(function(){
    $("#bill-btn").click(function(){
      $("#aeps-section").hide("slow");
      $("#bill-section").show("slow");
      $("#pan-section").hide("slow");
      $("#Insurance-section").hide("slow");
      $("#bill-btn").addClass("active-center")
      $("#aeps-btn").removeClass("active-left")
      $("#Insurance-btn").removeClass("active-center")
      $("#Pancard-btn").removeClass("active-right")
    });
  });
  $(document).ready(function(){
    $("#aeps-btn").click(function(){
      $("#aeps-section").show("slow");
      $("#bill-section").hide("slow");
      $("#pan-section").hide("slow");
      $("#Insurance-section").hide("slow");
      $("#bill-btn").removeClass("active-center")
      $("#aeps-btn").addClass("active-left")
      $("#Insurance-btn").removeClass("active-center")
      $("#Pancard-btn").removeClass("active-right")
    });
  });
  $(document).ready(function(){
    $("#Insurance-btn").click(function(){
      $("#aeps-section").hide("slow");
      $("#bill-section").hide("slow");
      $("#pan-section").hide("slow");
      $("#Insurance-section").show("slow");
      $("#bill-btn").removeClass("active-center")
      $("#aeps-btn").removeClass("active-left")
      $("#Insurance-btn").addClass("active-center")
      $("#Pancard-btn").removeClass("active-right")
    });
  });
  $(document).ready(function(){
    $("#Pancard-btn").click(function(){
      $("#aeps-section").hide("slow");
      $("#bill-section").hide("slow");
      $("#pan-section").show("slow");
      $("#Insurance-section").hide("slow");
      $("#bill-btn").removeClass("active-center")
      $("#aeps-btn").removeClass("active-left")
      $("#Insurance-btn").removeClass("active-center")
      $("#Pancard-btn").addClass("active-right")
    });
  });
</script>
<!--Team-Section-Start-->
<section id="team">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="text-center heading">
          <h2>OUR Partn<span>ers</span></h2>
          <div class="line"></div>
          <!-- <p><span><strong>&nbsp;</strong></span></p> -->
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4 col-md-3 col-12 team-main-sec wow slideInUp" data-wow-duration="1s" data-wow-delay=".1s">
        <div class="team-sec feb-partner">
          <div class="team-img"> <img src="{{asset("")}}front/images/Partners/01.png" class="img-responsive" alt="" style="padding-top:6px;">
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-3 col-12 team-main-sec wow slideInUp" data-wow-duration="1s" data-wow-delay=".2s">
        <div class="team-sec feb-partner">
          <div class="team-img"> <img src="{{asset("")}}front/images/Partners/02.png" class="img-responsive" alt="" style="padding-top:25px;">
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-3 col-12  team-main-sec wow slideInUp" data-wow-duration="1s" data-wow-delay=".3s">
        <div class="team-sec feb-partner">
          <div class="team-img"> <img src="{{asset("")}}front/images/Partners/03.png" style="height:100px; width:100%;" class="img-responsive" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--Testimonials-Section-Start-->
<section id="testimonials" class="parallex">
  <div class="container">
    <div class="quote feb-font"><i class="fa fa-quote-left"></i> Customer's speak<i class="fa fa-quote-right"></i></div>
    <div class="clearfix"></div>
    <div class="slider-text">
      <div id="owl-testi" class="owl-carousel owl-theme">
        <div class="item">
          <div  class="col-md-10 col-md-offset-1"> <img src="{{asset("")}}front/images/Testimonials/01.jpeg" style="width:100px;" class="img-circle" alt="">
            <h5>मै SahazMoney के साथ पिछले साल जुड़ा था | मेरा सिर्फ  मोबाइल शॉप  का कम था लेकिन अब SahazMoney के साथ जुड़ कर आधार पेमेंट ,मनी ट्रांसफ़र ,मोबाइल रिचार्ज और भी ऑनलाइन सर्विसेज पे कम करके एक अच्छा इनकम कर लेता हु|साथ ही SahazMoney की टीम समस्या का समाधान समय कर देती है |</h5>
            <h6>Ravindra Kushwaha</h6>
            <!-- <p>Web Developer</p> -->
          </div>
        </div>
        <div  class="col-md-10 col-md-offset-1"> <img src="{{asset("")}}front/images/Testimonials/02.jpeg" style="width:100px;" class="img-circle" alt="">
          <h5>मै SahazMoney के साथ पिछले 2 साल से जुड़ कर SahazMoney के सर्विसेज पे काम कर रहा  हु  मेरा अनुभव बहुत ही अच्छा रहा है| SahazMoney की टीम से समस्या का समाधान समय से मिल जाता है | मै SahazMoney की सर्विसेज पे काम करके अच्छा कमाई भी कर लेता हु और इनकी सेवावो से संतुष्ट हु|</h5>
          <h6>Jitendra Sharma</h6>
          <!-- <p>Web Designer</p> -->
        </div>
        <div  class="col-md-10 col-md-offset-1"> <img src="{{asset("")}}front/images/Testimonials/03.jpeg" style="width:100px;" class="img-circle" alt="">
          <h5>मै आनलाइन सर्विसेज प्रदान करता हु, जिसमे हमें पैन कार्ड की सर्विस की जरुरत पड़ती थी उसके लिये मै SahazMoney के साथ जुड़ा जिसमे हमे पैन कार्ड की सर्विस के साथ साथ और भी बहुत सी सर्विसेज का लाभ मिलता है जिससे मुझे अच्छा इनकम हो जाता है | मेरा अनुभव SahazMoney के साथ बहुत ही अच्छा रहा है </h5>
          <h6>Hakim Ansari</h6>
          <!-- <p>Web Designer</p> -->
        </div>
        <div  class="col-md-10 col-md-offset-1"> <img src="{{asset("")}}front/images/Testimonials/04.jpeg" style="width:100px;" class="img-circle" alt="">
          <h5>मेंरा टूर्स & ट्रेवल्स का काम था ,जिसमे मै सिर्फ ट्रेवल्स का काम करता था फिर मै पिछले साल  SahazMoney के साथ जुड़ा जिसमे हमे पैन कार्ड,मनी ट्रांसफ़र ,रिचार्ज  की सर्विस के साथ साथ और भी बहुत सी सर्विसेज का लाभ मिलता है जिससे मुझे अच्छा इनकम हो जाता है | मेरा अनुभव SahazMoney के साथ बहुत ही अच्छा रहा है </h5>
          <h6>Jubair Ansari</h6>
          <!-- <p>CEO</p> -->
        </div>
      </div>
    </div>
  </div>
</section>
<!--Fun-Facts-Section-Start-->
<section id="fun-facts">
  <div class="container">
    <div class="row">
      <div class="col-sm-6 col-md-3">
        <div class="fun-fact text-center">
          <h3><i class="fa fa-thumbs-o-up"></i> <span class="timer">5000</span><i class="fa fa-plus" style="font-size: 15px; color: #0096ff; position: absolute; top: 25px;"></i></h3>
          <h6>Happy Clients</h6>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="fun-fact text-center">
          <h3><i class="fa fa-briefcase fa-6"></i> <span class="timer">5500</span><i class="fa fa-plus" style="font-size: 15px; color: #0096ff; position: absolute; top: 25px;"></i></h3>
          <h6>Our Partners</h6>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="fun-fact text-center">
          <h3><i class="fa fa-coffee"></i> <span class="timer">20</span><i class="fa fa-plus" style="font-size: 15px; color: #0096ff; position: absolute; top: 25px;"></i></h3>
          <h6>Services</h6>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="fun-fact text-center">
          <h3><i class="fa fa-user"></i> <span class="timer">6000</span><i class="fa fa-plus" style="font-size: 15px; color: #0096ff; position: absolute; top: 25px;"></i></h3>
          <h6>Connect With Us</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="contact">
  <div class="container">
    <div class="col-md-8 col-md-offset-2">
      <div class="heading">
        <h2>CONTACT <span>US</span></h2>
        <div class="line"></div>
        <p><span><strong></strong>&nbsp;</span></p>
      </div>
    </div>
    <div class="text-center">
      <div class="col-md-6 col-sm-6 contact-sec-1">
        <h4>CONTACT IN<span>FO</span></h4>
        <ul class="contact-form"> <h6 class="feb-font-contact"><strong>     Sahaz It Solutions Pvt.Ltd.</h6>
          <li><i class="fa fa-map-marker"></i>
            <h5 class="feb-font-contact"><strong>Address :</strong> JANGAL AMWA ,POST JANGAL BELWA ,PADRAUNA,DISTT KUSHINAGAR UP PIN.274304</h4>
          </li>
          <li><i class="fa fa-envelope"></i>
            <h5 class="feb-font-contact"><strong>Mail Us :</strong> <a href="#">support@Sahajmoney.org</a></h6>
          </li>
          <li><i class="fa fa-phone"></i>
            <h5 class="feb-font-contact"><strong>Phone :</strong> 7570001356, 7570001355 </h6>
          </li>
          <li><i class="fa fa-wechat"></i>
            <h5 class="feb-font-contact"><strong>Website :</strong> <a href="#">www.Sahajmoney.org</a> </h6>
          </li>
        </ul>
      </div>
      <div class="col-md-6 col-sm-6">
        <form id="main-contact-form" name="contact-form" method="post" action="#">
          <div class="row  wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
            <div class="col-sm-6">
              <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="Name" required="required">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required="required">
              </div>
            </div>
          </div>
          <div class="form-group">
            <input type="text" name="subject" class="form-control" placeholder="Subject" required="required">
          </div>
          <div class="form-group">
            <textarea name="message" id="message" class="form-control" rows="4" placeholder="Enter your message" required="required"></textarea>
          </div>
          <a class="btn-send col-md-12 col-sm-12 col-xs-12" href="#">Send Now</a>
        </form>
      </div>
    </div>
  </div>
</section>

<!--About-Section-Start-->

<div class="modal fade" id="about" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" style="width: 100%; margin-top: 0px; margin-left:0px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title text-center" id="exampleModalLongTitle">
                    <div class="heading">
                        <h2>ABOUT <span>US</span></h2>
                        <div class="line"></div>
                    </div>
                </h5>
            </div>
            <div class="modal-body">
                <section class="aboutUs_section my-4 pb-4">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Sahazmoney has embarked on a mission to reach every Indian and offer them easy on-the-go banking and a bouquet of services.
Sahazmoney is trying to use technology and available platforms to deliver a full range of financial services and products at one place, and aspires to build a nation, where every Indian is part of the financial ecosystem and has access to banking and value-added digital services at their doorstep
.</p>
                                
                                <p>Sahazmoney is a product of <a href="https://sahajmoney.org/" target="_blank" style="text-decoration:underline ;">Sahaz It Solutions Pvt.Ltd.</a></p>
                            </div>
                            <div class="col-md-7">
                                <ul class="tick-list mt-4">
                                    <li>
                                        <div class="cnt-holder">
                                            <h3>Convenience</h6>
                                            <p>Consumers have a range of services at their fingertips making it convenient for them
                                                to switch to digital modes of transactions</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="cnt-holder">
                                            <h3>Accessibility Ease of use</h6>
                                            <p>Rural consumers have access to a variety of services that are available in their
                                                neighbourhood along with specialised assistance

                                            </p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="cnt-holder">
                                            <h3>Increased Reach</h6>
                                            <p>With presence spread across the country, it offers extended reach not only to the
                                                consumers but also to our business partners

                                            </p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="cnt-holder">
                                            <h3>Security</h6>
                                            <p>Sahazmoney device is an EMV certified digital payment device having STQC certified
                                                scanner that enables payment transactions through all the available digital payment
                                                channels

                                            </p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="cnt-holder">
                                            <h3>Banking, Payment &amp; Value Added Services</h6>
                                            <p>Sahazmoney is a one stop payment and service solution provider for both merchants and
                                                consumers.

                                            </p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
            <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
            </div>
      </div>
    </div>
</div>
  <!-- about section code end  -->
  <!-- privacy policy code end here -->
<!--About-Sec-2-Start-->
<!-- privacy policy code start here -->
<div class="modal fade" id="privacy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" style="width: 100%; margin-top: 0px; margin-left:0px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center" id="exampleModalLongTitle">Privacy Policy</h5>

      </div>
      <div class="modal-body">

        <div class="col-md-12">
                    <p>
                        Sahaz It Solutions Pvt.Ltd. ("us", "we", or "our") operates the <a href="https://sahajmoney.org/" target="_blank" class="color-blue">www.Sahajmoney.org/</a>
                        website
                    </p>
                    <p>
                        This Privacy Policy ("Policy") explains how Sahaz It Solutions Pvt.Ltd.-sahazmoney
                        ("sahazmoney") collects, uses, shares and protects information of users ("You") through its
                        website located at the URL <a href="https://sahajmoney.org/" target="_blank" class="color-blue">"www.Sahajmoney.org/"</a> ("Platform").

                    </p>
                    <p>
                        sahazmoney is committed to protecting your privacy and has therefore, provided this Policy to
                        familiarize You with the manner in which sahazmoney uses the information disclosed by You to
                        sahazmoney.
                    </p>
                    <p>
                        The terms of the Policy shall govern your use of the Website. This Policy shall be construed
                        in compliance with Information Technology Act, 2000 as amended from time to time and read
                        with Information Technology (Reasonable Security Practices and Procedures and Sensitive
                        Personal Data or Information) Rules, 2011.
                    </p>

                    <p>
                        WE USE YOUR DATA TO PROVIDE AND IMPROVE THE SERVICE. BY ACCESSING AND USING THE PLATFORM AND
                        AVAILING THE SERVICES OFFERED BY sahazmoney THROUGH THE PLATFORM, YOU AGREE TO THE COLLECTION
                        AND USE OF INFORMATION IN ACCORDANCE WITH THIS POLICY. UNLESS OTHERWISE DEFINED IN THIS
                        PRIVACY POLICY, TERMS USED IN THIS PRIVACY POLICY HAVE THE SAME MEANINGS AS IN OUR TERMS AND
                        CONDITIONS, ACCESSIBLE FROM OUR WEBSITE. IF YOU ARE RESIDING IN THE EUROPEAN UNION, PLEASE
                        NOTE THAT, BY FURTHER USING THIS PLATFORM, YOU AGREE THAT YOUR CONSENT IS GIVEN TO US TO
                        PROCESS YOUR PERSONAL DATA.
                    </p>
                    <p>
                        sahazmoney reserves the right to change, modify or remove portions of this Policy at any time.
                        sahazmoney recommends that You review this Policy periodically to ensure that You are aware of
                        the current privacy practices. sahazmoney may notify You of the modifications to the Policy on
                        Your use of the Platform or in any other manner as sahazmoney may deem fit. Your continued use
                        of the Platform after the amendment to the Policy will be deemed acceptance of the
                        modifications thereof.
                    </p>
                    <div class="privacy_list">
                        <ol>
                            <li>
                                <span class="heading">Collection</span>
                                <ol>
                                    <li>
                                        <span class="sub-headings">Information sahazmoney may collect:</span>
                                        <p>
                                            For the purposes of this privacy statement, 'Personal Information' is
                                            any data which relates to an individual who may be identified from that
                                            data, or from a combination of a set of data, and other information
                                            which is in possession of sahazmoney. In general, you may browse our
                                            website without providing any Personal Information about yourself.
                                            However, we collect certain information such as
                                        </p>
                                        <p>
                                            <span class="sub-headings"> a. Personal Information:</span><br>
                                            You may be required to provide certain information such as Your name,
                                            (current &amp; former), Date of birth, address , email ID, phone number,
                                            photograph, documents to prove identity or address, Gender , and any
                                            other details capable of identifying You ("Information") for the
                                            provision of the Services to You via the Platform. sahazmoney collects only
                                            such Personal Information that SahazMoney believes to be relevant and is
                                            required to understand You or Your interests and for the provision of
                                            the Services.
                                        </p>
                                        <p>
                                            <span class="sub-headings">b. Non-Personal Information:</span><br>
                                            sahazmoney may collect technical data such as the IP address of Your
                                            device, device ID, the IMEI of Your mobile device, IMSI of Your SIM,
                                            etc. As this will not contain any personally identifiable information,
                                            SahazMoney may use this Non-Personal Information in any manner as SahazMoney
                                            deems fit.
                                        </p>
                                        <p>
                                            <span class="sub-headings">c. Cookies and Usage Data:</span>
                                        </p>
                                        <div class="roman">
                                            <p>
                                                <span class="sub-headings"> i. Usage Data: </span>
                                                We may also collect information how the Service is accessed and used
                                                ("Usage Data"). This Usage Data may include information such as your
                                                computer's Internet Protocol address (e.g. IP address), browser
                                                type, browser version, the pages of our Service that you visit, the
                                                time and date of your visit, the time spent on those pages, unique
                                                device identifiers and other diagnostic data
                                            </p>
                                            <p>
                                                <span class="sub-headings"> ii. Tracking &amp; Cookies Data: </span>
                                                We use cookies and similar tracking technologies to track the
                                                activity on our Service and hold certain information.
                                            </p>
                                            <p>
                                                <span class="sub-headings">iii.</span>
                                                Cookies are files with small amount of data which may include an
                                                anonymous unique identifier. Cookies are sent to your browser from a
                                                website and stored on your device. Tracking technologies also used
                                                are beacons, tags, and scripts to collect and track information and
                                                to improve and analyse our Service.
                                            </p>
                                            <p>
                                                <span class="sub-headings">iv.</span>
                                                You can instruct your browser to refuse all cookies or to indicate
                                                when a cookie is being sent. However, if you do not accept cookies,
                                                you may not be able to use some portions of our Service
                                            </p>
                                            <p>
                                                <span class="sub-headings"> v. </span>Examples of Cookies we use:
                                            </p>
                                            <div class="bullets">
                                                <p>
                                                    <span class="sub-headings"><i class="far fa-hand-point-right"></i> Session Cookies.</span>
                                                    We use Session Cookies to operate our Service.
                                                </p>
                                                <p>
                                                    <span class="sub-headings"><i class="far fa-hand-point-right"></i> Preference Cookies.</span>
                                                    We use Preference Cookies to remember your preferences and
                                                    various settings.
                                                </p>
                                                <p>
                                                    <span class="sub-headings"><i class="far fa-hand-point-right"></i> Security Cookies.</span>
                                                    We use Security Cookies for security purposes.
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </li>
                            <li>
                                <span class="heading">Use of Information by Sahazmoney</span>
                                <ol>
                                    <li>
                                        The Information provided by You to Sahazmoney will be kept confidential to the
                                        maximum possible extent, and may be used for a number of purposes connected
                                        with Sahazmoney’s business operations which may include the following:
                                        <h6 class="heading-sub">To provide and maintain the Service</h6>
                                        <p>
                                            <span class="sub-headings"> a)</span>
                                            To notify you about changes to our Service
                                        </p>
                                        <p>
                                            <span class="sub-headings">b)</span>
                                            To provide better usability, troubleshooting and site maintenance
                                        </p>
                                        <p>
                                            <span class="sub-headings">c)</span>
                                            To allow you to participate in interactive features of our Service when
                                            you choose to do so
                                        </p>
                                        <p><span class="sub-headings">d)</span> To provide customer care and
                                            support.</p>
                                        <p><span class="sub-headings">e)</span> To provide analysis or valuable
                                            information so that we can improve the Service</p>
                                        <p><span class="sub-headings">f)</span> To monitor the usage of the Service.
                                        </p>
                                        <p><span class="sub-headings">g)</span> To detect, prevent and address
                                            technical issues.</p>
                                        <p><span class="sub-headings">h)</span> Dealing with requests, enquiries and
                                            complaints and customer related activities.</p>
                                        <p><span class="sub-headings">i)</span> To provide access to desirable
                                            content based on your preferences.</p>
                                        <p><span class="sub-headings">j)</span> Marketing products and services and
                                            data analysis, including, for providing advertisements and surveys of
                                            relevance to You.</p>
                                        <p><span class="sub-headings">j)</span> Personalizing and enhancing Your
                                            experience while using the Platform by presenting products and offers
                                            tailored to You; and,</p>
                                        <p><span class="sub-headings">l)</span> Abiding with laws and law
                                            enforcement / regulatory requests</p>
                                    </li>
                                </ol>
                            </li>
                            <li>
                                <span class="heading">Disclosure</span>
                                <ol>
                                    <li>
                                        By accessing this website, you hereby give your express consent to share
                                        your Personal Information within:
                                        <div class="roman mt-2">
                                            <p>
                                                <span class="sub-headings"><i class="far fa-hand-point-right"></i></span>
                                                Sahazmoney or with any of its subsidiaries
                                            </p>

                                            <p>
                                                <span class="sub-headings"><i class="far fa-hand-point-right"></i></span>
                                                Business partners
                                            </p>
                                            <p>
                                                <span class="sub-headings"><i class="far fa-hand-point-right"></i></span>
                                                Service vendors
                                            </p>
                                            <p>
                                                <span class="sub-headings"><i class="far fa-hand-point-right"></i></span>
                                                Authorized third-party agents; or
                                            </p>
                                            <p>
                                                <span class="sub-headings"><i class="far fa-hand-point-right"></i></span>
                                                Contractors.
                                            </p>
                                        </div>
                                    </li>
                                    <li>
                                        Additionally, your consent may be implicit or implied or through course of
                                        conduct as necessary or appropriate or where the Information may be
                                        disclosed as follows:<br>
                                        <p class="c">

                                            (a) In any manner permitted under applicable law, including laws outside
                                            your country of residence; <br>
                                            (b) To comply with legal process whether local or foreign; <br>
                                            (c) To respond to requests from public and government authorities, <br>
                                            including public and government authorities outside your place of
                                            residence;<br>
                                            (d) To enforce the terms and conditions of the Platform;<br>
                                            (e) To protect Sahazmoney's rights, privacy, safety or property, and/or
                                            that of Sahazmoney's affiliates, You or others; and <br>
                                            (f) To allow Sahazmoney to pursue available remedies or limit the damages
                                            that Sahazmoney may sustain.
                                        </p>
                                    </li>
                                </ol>
                            </li>
                            <li>
                                <span class="heading">Google Analytics</span>
                                <ol>
                                    <li>
                                        Google Analytics is a web analytics service offered by Google that tracks
                                        and reports website traffic. Google uses the data collected to track and
                                        monitor the use of our Service. This data is shared with other Google
                                        services. Google may use the collected data to contextualize and personalize
                                        the ads of its own advertising network.
                                        <p>
                                            You can opt-out of having made your activity on the Service available to
                                            Google Analytics by installing the Google Analytics opt-out browser
                                            add-on. The add-on prevents the Google Analytics JavaScript (ga.js,
                                            analytics.js, and dc.js) from sharing information with Google Analytics
                                            about visits activity.
                                        </p>
                                    </li>
                                    <li>
                                        For more information on the privacy practices of Google, please visit the
                                        Google Privacy &amp; Terms web page: <a href="https://policies.google.com/privacy?hl=en" target="_blank" class="color-blue">https://policies.google.com/privacy?hl=en</a>
                                    </li>
                                </ol>
                            </li>
                            <li>
                                <span class="heading">Links to Other Sites</span>
                                <ol>
                                    <li>Our Service may contain links to other sites that are not operated by us. If
                                        you click on a third-party link, you will be directed to that third party's
                                        site. We strongly advise you to review the Privacy Policy of every site you
                                        visit.</li>
                                    <li>We have no control over and assume no responsibility for the content,
                                        privacy policies or practices of any third-party sites or services.</li>
                                </ol>
                            </li>
                            <li>
                                <span class="heading">Children's Privacy</span>
                                <ol>
                                    <li>Our Service does not address anyone under the age of 18("Children").</li>
                                    <li>We do not knowingly collect personally identifiable information from anyone
                                        under the age of 18. If you are a parent or guardian and you are aware that
                                        your Children has provided us with Personal Data, please contact us. If we
                                        become aware that we have collected Personal Data from children without
                                        verification of parental consent, we take steps to remove that information
                                        from our servers.</li>
                                </ol>
                            </li>
                            <li>
                                <span class="heading">Information Security and Storage</span>
                                <ol>
                                    <li>
                                        Sahazmoney uses reasonable security measures, at the minimum those mandated
                                        under Information Technology Act, 2000 as amended and read with Information
                                        Technology (Reasonable Security Practices and Procedures and Sensitive
                                        Personal Data or Information) Rules, 2011, to safeguard and protect Your
                                        data and information. Sahazmoney has adopted reasonable security practices and
                                        procedures, in line with IS/ISO/IEC 27001, to include, strategic,
                                        operational, managerial, technical, and physical security controls to
                                        safeguard and protect Your data and information. Sahazmoney has implemented
                                        such measures, as stated above, to protect against unauthorized access to,
                                        and unlawful interception of Your Information. However, security risk cannot
                                        be completely eliminated while using the internet. You accept the inherent
                                        security implications of providing information over the internet and agree
                                        not to hold Sahazmoney responsible for any breach of security or the disclosure
                                        of personal information unless Sahazmoney has been grossly and willfully
                                        negligent.
                                    </li>

                                    <li>Notwithstanding anything contained in this Policy or elsewhere, Sahazmoney
                                        shall be under no liability whatsoever in case of occurrence of a force
                                        majeure event, including in case of non-availability of any portion of the
                                        Platform and/or the Services occasioned by any act of God, war, disease,
                                        revolution, riot, civil commotion, strike, lockout, flood, fire, failure of
                                        any public utility, man- made disaster, infrastructure failure, technology
                                        outages, failure of technology integration of partners or any other cause
                                        whatsoever, beyond the control of . Further, in case of a force majeure
                                        event, Sahazmoney shall not be liable for any breach of security or loss of
                                        data / Information or any Content uploaded by You to the Platform.</li>
                                    <li>
                                        Our Retention of Personal Data: Sahazmoney shall retains personal data for as
                                        long as necessary to provide the access to and use of the website, or for
                                        other essential purposes such as complying with our legal obligations,
                                        resolving disputes and enforcing our agreements. Because these needs can
                                        vary for different data types and purposes, actual retention periods can
                                        vary significantly.
                                    </li>
                                    <li>
                                        Data Deletion or Shredding: Any documents that contain sensitive or
                                        confidential information (and particularly sensitive personal data) will be
                                        disposed of as confidential waste and be subject to secure electronic
                                        deletion; some expired or superseded contracts may only warrant in-house
                                        shredding. The Document Disposal / Shredding will be carried out as per the
                                        Scheduled frequency.
                                    </li>
                                </ol>
                            </li>
                            <li>
                                <span class="heading">Governing Law and Jurisdiction</span>
                                <p>Any dispute over privacy issues will be subject to this Policy, the Terms of
                                    Service, and the law of the Republic of India. Courts in Kushinagar, Uttar Pradesh 
                                    alone shall have exclusive jurisdiction to hear disputes arising out of this
                                    Policy.</p>
                                <p>If You have questions or concerns about this Policy, please contact at the below
                                    mentioned details.</p>
                                <p>
                                </p>
                                <address>
                                    Sahaz It Solutions Pvt.Ltd..<br>
                                    Jangal Amwa Post Jangal Belwa<br>
                                    Padrauna, Distt Kushinagar 274304<br>
                                    Contact No.+91-7570001355 <br>
                                    Email:<a href="mailto:support@Sahajmoney.org " class="color-blue">
                                        support@Sahajmoney.org </a>
                                </address>
                                <p></p>
                            </li>
                        </ol>
                    </div>

                </div>
  </div>
  <div class="modal-footer">
    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
  </div>
    </div>
  </div>
</div>
<!-- privacy policy code end here -->
<!-- term and condition code start here -->
<div class="modal fade" id="refound" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" style="width: 100%; margin-top: 0px; margin-left:0px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title text-center" id="exampleModalLongTitle">Refund and Cancellation Policy</h4>

      </div>
      <div class="modal-body">
        <p>1. Amount once paid through the payment gateway shall not be refunded other than in the
        following circumstances:</p>
        <p>• Multiple times debiting of Customer’s Card/Bank Account due to technical error OR Customer's
        account being debited with excess amount in a single transaction due to technical error. In such
        cases, excess amount excluding Payment Gateway charges would be refunded to the Customer.</p>
        <p>• Due to technical error, payment being charged on the Customer’s Card/Bank Account but the
        enrolment for the examination is unsuccessful. Customer would be provided with the enrolment
        by NISM at no extra cost. However, if in such cases, Customer wishes to seek refund of the
        amount, he/she would be refunded net the amount, after deduction of Payment Gateway
        charges or any other charges.</p>
        <p>2. The Customer will have to make an application for refund along with the transaction number
        and original payment receipt if any generated at the time of making payments.</p>
        <p>3. The application in the prescribed format should be sent to <a href="mailto:support@Sahajmoney.org " class="color-blue">
            support@Sahajmoney.org </a> </p>
        <p>4. The application will be processed manually and after verification, if the claim is found valid, the
        amount received in excess will be refunded by NISM through electronic mode in favor of the
        applicant and confirmation sent to the mailing address given in the online registration form,
        within a period of 21 calendar days on receipt of such claim. It will take 3-21 days for the money
        to show in your bank account depending on your bank’s policy.</p>
        <p>5. Company assumes no responsibility and shall incur no liability if it is unable to affect any
        Payment Instruction(s) on the Payment Date owing to any one or more of the following
        circumstances:</p>
        <p>a. If the Payment Instruction(s) issued by you is/are incomplete, inaccurate, and invalid and
        delayed.</p>
        <p>b. If the Payment Account has insufficient funds/limits to cover for the amount as mentioned
        in the Payment Instruction(s)</p>
        <p>c. If the funds available in the Payment Account are under any encumbrance or charge.</p>
        <p>d. If your Bank or the NCC refuses or delays honoring the Payment Instruction(s)</p>
        <p>e. Circumstances beyond the control of Company (including, but not limited to, fire, flood,
        natural disasters, bank strikes, power failure, systems failure like computer or telephone
        lines breakdown due to an unforeseeable cause or interference from an outside force)</p>
        <p>f. In case the payment is not effected for any reason, you will be intimated about the failed
        payment by an e-mail</p>
        <p>6. User agrees that Company, in its sole discretion, for any or no reason, and without penalty, may
        suspend or terminate his/her account (or any part thereof) or use of the Services and remove
        and discard all or any part of his/her account, user profile, or his/her recipient profile, at any
        time. Company may also in its sole discretion and at any time discontinue providing access to
        the Services, or any part thereof, with or without notice. User agrees that any termination of his
        /her access to the Services or any account he/she may have or portion thereof may be effected
        without prior notice, and also agrees that Company will not be liable to user or any third party
        for any such termination. Any suspected, fraudulent, abusive or illegal activity may be referred
        to appropriate law enforcement authorities. These remedies are in addition to any other
        remedies Company may have at law or in equity. Upon termination for any reason, user agrees
        to immediately stop using the Services.</p>
        <p>7. Company may elect to resolve any dispute, controversy or claim arising out of or relating to this
        Agreement or Service provided in connection with this Agreement by binding arbitration in
        accordance with the provisions of the Indian Arbitration & Conciliation Act, 1996. Any such
        dispute, controversy or claim shall be arbitrated on an individual basis and shall not be
        consolidated in any arbitration with any claim or controversy of any other party.</p>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
      </div>
    </div>
  </div>
</div>
<!-- term and condition code end here -->
<!-- term and condition code start here -->
<div class="modal fade" id="term" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" style="width: 100%; margin-top: 0px; margin-left:0px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center" id="exampleModalLongTitle">Terms of Use</h5>
      </div>
      <div class="modal-body">
        <div class="col-md-12">
                    <p>THIS TERMS OF USE DOCUMENT (“TOS”) IS A LEGAL AGREEMENT BETWEEN YOU, THE END USER (“YOU”/“YOUR”), AND Sahaz It Solutions Pvt.Ltd., THE OWNER OF ALL INTELLECTUAL PROPERTY RIGHTS IN THE MOBILE APPLICATION TITLED “Sahazmoney” AND WEBSITE LOCATED AT THE URL <a href="https://sahajmoney.org/" class="color-blue" target="_blank" style="text-transform:lowercase!important;">www.Sahajmoney.org/</a> (THE APPLICATION AND THE WEBSITE ARE COLLECTIVELY REFERRED TO AS THE “PLATFORM”). THE TOS DESCRIBES THE TERMS ON WHICH Sahaz It Solutions Pvt.Ltd. OFFERS ACCESS TO THE PLATFORM AND THE SERVICES (DEFINED BELOW) TO ALL END USERS BEING CONSUMERS.</p>
                    <p>
                    <strong>
                        PLEASE READ THE TOS CAREFULLY. YOUR ACCEPTANCE OF THIS TOS AND USE OF THE PLATFORM SHALL SIGNIFY YOUR ACCEPTANCE OF THE TOS AND THE PRIVACY POLICY AVAILABLE AT <a href="https://sahajmoney.org/" class="color-blue" target="_blank">[www.Sahajmoney.org/]</a> AS WELL AS YOUR AGREEMENT TO BE LEGALLY BOUND BY THE SAME. THIS USER AGREEMENT IS EFFECTIVE UPON ACCEPTANCE IN REGISTRATION FOR NEW REGISTERING USERS.
                    </strong>
                </p>
                    <p>This is a legally binding agreement and an electronic record under the Information Technology Act, 2000 and the rules thereunder and the amended provisions pertaining to electronic records under various Indian statutes and is enforceable against you. Sahaz It Solutions Pvt.Ltd. may modify the terms of the TOS at its sole discretion due to changes in applicable laws or for any other reason, with or without any notification to you. You will be deemed to have accepted the revised TOS if you continue to access the Platform after the modifications become effective. If you do not agree to the terms of this TOS, please do not access or use the Platform.</p>
                    <p>The following are the terms and conditions on which Sahaz It Solutions Pvt.Ltd. agrees to permit you to access and use the Platform and the Services.</p>
                    <ol>
                        <li>
                            <h5>REGISTRATION</h5>
                            <ol>
                                <li>
                                    <p>
                                        In order to access and use the Platform, You will be required to register yourself and maintain an Sahazmoney account (“Account”) which will require you to furnish to Sahaz It Solutions Pvt.Ltd., certain information and details, including your name, e-mail id, and any other information deemed necessary by Sahaz It Solutions Pvt.Ltd. (“Account Information”). You agree to keep all information updated at all times.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        You shall, at all times, ensure that the Account credentials are kept safe and shall, under no circumstances, whatsoever, allow the Account to be used by any unauthorized individuals. You shall be responsible for maintaining the confidentiality and security of the password and for all activities that occur in and through Your Account. Sahaz It Solutions Pvt.Ltd. and its affiliates / partners will not be liable for any harm caused by, or related to the theft of, Your ID, Your disclosure of Your Account Information, or Your authorization to allow another person to access and use the Service using Your Account. However, you may be liable to Sahaz It Solutions Pvt.Ltd. and its affiliates / partners for the losses caused to them due to such unauthorized use.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        <b>Account Dos and Don’ts:</b>
                                    </p>
                                    <p>
                                        You agree to abide by the following dos and don’ts for registering and maintaining the security of Your Account.
                                    </p>
                                    <ul class="terms-ullist">
                                        <li>
                                            <p>A natural person aged 18 years or above</p>
                                            <ol>
                                                <li>
                                                    <p>A natural person aged 18 years or above;  </p>
                                                </li>
                                                <li>
                                                    <p>A citizen of India; and</p>
                                                </li>
                                                <li>
                                                    <p>
                                                        Competent to enter into a valid and binding contract within the meaning of the Indian Contract Act, 1872 including any modification and/or amendment thereof
                                                    </p>
                                                </li>
                                            </ol>
                                            <p>
                                                Persons who are "incompetent to contract" within the meaning of the Indian Contract Act, 1872 including, but not limited to, minors and un-discharged insolvents are not eligible to use the Platform. The Platform is not available to persons whose membership has been suspended or terminated by Sahaz It Solutions Pvt.Ltd.. If you are registering as a business entity, you represent that you have the authority to bind the entity to this TOS. In accordance with the above, you hereby agree to comply with the terms, conditions, obligations, representations, and warranties set forth in this TOS.
                                            </p>

                                        </li>
                                        <li>
                                            <p>You will not provide any false personal information to Sahaz It Solutions Pvt.Ltd.</p>
                                        </li>
                                        <li>
                                            <p>You shall ensure that the Account Information is complete, accurate and up-to-date at all times</p>
                                        </li>
                                        <li>
                                            <p>You shall not use any other user’s Account Information or log into that user’s Account.</p>
                                        </li>
                                        <li>
                                            <p>You will not share your password or do anything that might jeopardize the security of Your Account.</p>
                                        </li>
                                        <li>
                                            <p>On completing the registration process, you shall be entitled to access the Platform and avail of the Services.</p>
                                        </li>
                                        <li>
                                            <p>Your Account, ID, and password shall not be transferred or sold to another party.</p>
                                        </li>

                                    </ul>
                                </li>
                                <li>
                                    <p>You agree to immediately notify Sahaz It Solutions Pvt.Ltd. of any unauthorized use of Your Account or any other breach of security known to you. </p>
                                </li>
                                <li>
                                    <p>
                                        In order to ensure that Sahaz It Solutions Pvt.Ltd. is able to provide high quality services, respond to customer needs, and comply with applicable law, you hereby consent to let Sahaz It Solutions Pvt.Ltd.’s employees and agents access Your Account and records on a case-to-case basis to investigate and resolve complaints or other allegations or suspected abuse
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        You also grant Sahaz It Solutions Pvt.Ltd. the right to disclose to its affiliates / partners / third parties Your Account Information to the extent necessary for the purpose of rendering the Services.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        If you provide any information that is untrue, inaccurate, not current or incomplete or Sahaz It Solutions Pvt.Ltd. has a reasonable ground to suspect that such information is untrue, inaccurate, not current or incomplete, or not in accordance with the TOS, Sahaz It Solutions Pvt.Ltd. has the right to indefinitely suspend or terminate your membership and refuse to provide you with access to the Platform.
                                    </p>
                                </li>

                            </ol>
                        </li>
                        <li>
                            <h5>SCOPE OF SERVICES AND PAYMENT OF FEE</h5>
                            <ol>
                                <li>
                                    <p>
                                        Sahaz It Solutions Pvt.Ltd. hereby grants You a specific, non-exclusive, non-transferrable and limited license to access and use the Platform via the internet, and avail of all the services provided on the Platform (“Services”), including but not limited.
                                    </p>
                                </li>
                            </ol>
                        </li>
                        <li>
                            <h5>ACCESS TO THE SERVICES </h5>
                            <ol>
                                <li>
                                    <p>The Services are being provided to you via the Platform. Your access of the Platform signifies your consent to view / receive all such Services. </p>
                                    <ul class="terms-ullist">
                                        <h6><i class="far fa-hand-point-down"></i> Payments</h6>
                                        
                                        <li>
                                            <p>UPI</p>
                                        </li>
                                        <li>
                                            <p>Aadhaar Pay</p>
                                        </li>
                                    </ul>
                                    <ul class="terms-ullist">
                                        <h6><i class="far fa-hand-point-down"></i> Banking</h6>
                                        <li>
                                            <p>AEPS</p>
                                        </li>
                                        <li>
                                            <p>Card Transactions</p>
                                        </li>

                                    </ul>
                                    <ul class="terms-ullist">
                                        <h6><i class="far fa-hand-point-down"></i> Value Added Service</h6>
                                        <li>
                                            <p>Recharge</p>
                                        </li>
                                        <li>
                                            <p>Bill Payments</p>
                                        </li>
                                        <li>
                                            <p>Travel</p>
                                        </li>
                                        <li>
                                            <p>Entertainment </p>
                                        </li>
                                        <li>
                                            <p>Insurance</p>
                                        </li>
                                        <li>
                                            <p>E-Government Services</p>
                                        
                                        
                                        
                                        <li>
                                            <p>Domestic Money Transfer</p>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <p>
                                        The Platform is licensed and not sold to you. You hereby agree that all rights, title and interest in the Platform are owned by, or licensed to, Sahaz It Solutions Pvt.Ltd.. Subject to your strict and full compliance with the TOS, Sahaz It Solutions Pvt.Ltd. hereby grants you a revocable, non-exclusive, non-transferable, non-sub-licensable, limited license, to download, install and use the Platform, on a worldwide basis, for use only by you as part of your use of the Services, during the subsistence of this TOS.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        You agree that Sahaz It Solutions Pvt.Ltd. is in no way responsible for the accuracy, timeliness or completeness of information it may obtain from these third parties.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Your interaction with any third party accessed through the Platform is at your own risk, and Sahaz It Solutions Pvt.Ltd. will have no liability with respect to the acts, omissions, errors, representations, warranties, breaches or negligence of any such third parties or for any personal injuries, death, property damage, or other damages or expenses resulting from your interactions with the third parties
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Sahaz It Solutions Pvt.Ltd. is not responsible for any non-performance or breach of any contract entered into with third Party. Sahaz It Solutions Pvt.Ltd. shall not and is not required to mediate or resolve any dispute or disagreement between you and third Party.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        You agree not to access or enable access to the Services by any unauthorized third-party Platform.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Except for the rights expressly stated herein, no other rights are granted to you with respect to the Platform or the Services, either by implication, estoppel, or otherwise. Sahaz It Solutions Pvt.Ltd. reserves all rights not expressly granted herein.
                                    </p>
                                </li>
                            </ol>
                        </li>
                        <li>
                            <h5>USE OF SERVICES </h5>
                            <ol>
                                <li>
                                    <p>
                                        You agree to use the Services solely for the purpose for which the Services are provided
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        You shall not sub-license or resell the Platform or the Services for the use or benefit of any other organization, entity, business or enterprise.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        You agree not to submit or upload to the Platform, any material that is illegal, misleading, defamatory, indecent, obscene, threatening, infringing of any third-party proprietary rights, invasive of personal privacy or otherwise objectionable (collectively, “Objectionable Matter”). Sahaz It Solutions Pvt.Ltd. reserves the right to adopt and amend rules for the permissible use of the Platform and the Services at any time, and you shall be required to comply with such rules. You shall also be required to comply with all applicable laws regarding privacy, data storage etc., or any other policy of Sahaz It Solutions Pvt.Ltd., as updated from time to time. Sahaz It Solutions Pvt.Ltd. reserves the right to terminate this Agreement and Your access to the Platform, without notice, if you commit any breach of this Clause.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        You shall be able to access the Platform through internet-enabled computer terminals and mobile phones empowered to host applications or browse the internet.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        All user data whether uploaded or submitted by you to Your Account or obtained by Sahaz It Solutions Pvt.Ltd. pursuant to its discharge of the Services, shall be your sole property. You retain all rights in your user data made available to Sahaz It Solutions Pvt.Ltd. and utilized on the Platform and shall remain liable for the legality, reliability, integrity, accuracy and copyright permissions thereto of such data. Sahaz It Solutions Pvt.Ltd. will use commercially reasonable security measures to protect your data against unauthorized disclosure or use. However, Sahaz It Solutions Pvt.Ltd. does not guarantee data security. If your data is damaged or lost, Sahaz It Solutions Pvt.Ltd. will use commercially reasonable means to recover such data. You agree that you are entering into this agreement in full knowledge of the same.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        You will not use the Platform or the Service to (i) build a competitive product or service, (ii) make or have a product with similar ideas, features, functions or graphics of the Platform, (iii) copy any features, functions or graphics of the Platform /Services. You shall use the Services and the Platform strictly in accordance with the TOS and shall not: (i) decompile, disassemble, reverse engineer or attempt to derive the source code of or in any manner decrypt the Platform; (ii) make any modification, adaptation or improvement, enhancement, or derivative work from the Platform or incorporate any portion of the Platform into your own programs or compile any portion of it in combination with your own programs, transfer it for use with another service; (iii) violate any applicable laws or regulations in connection with your access or use of the Services and the Platform; (iv) remove or obscure any proprietary notice (including any notice of copyright or trademark) forming a part of the Platform; (v) use the Platform or Services for any revenue generation endeavor, or any other purpose for which it is not designed or intended; (vi) publish, copy, offer for sale or commercial rental, license or otherwise lend the Platform or the Services to third parties; (vii) use the Platform for data mining, scraping, crawling, redirecting, or for any purpose not in accordance with the TOS; (viii) use the Platform for undertaking any hacking activities like breaching or attempt to breach the security of another user or attempt to gain unauthorized access to any other person’s computer; (ix) derive any confidential information, processes, data or algorithms from the Platform.
                                    </p>
                                </li>
                                <li>
                                    <p>You also expressly agree not to engage in any use or activity that </p>
                                    <ul class="terms-ullist">
                                        <li>
                                            <p>
                                                may interrupt, destroy, alter, damage, delay, or limit the functionality or integrity of the Platform or Services including that of, any associated Platform, hardware, telecommunications or wireless equipment;
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                May manipulate identifiers, or numeric information to disguise the origin of any user, device, material or other information;
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                May interfere with the proper working of the Platform or prevent others from using the Platform; or
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                May delete the copyright and other proprietary rights notices on the Platform.
                                            </p>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <p>
                                        You agree that Sahaz It Solutions Pvt.Ltd. shall have no liability for any claims, losses or damages arising out of, or in connection with, your unauthorized use of the Platform and/or the Services.
                                    </p>
                                </li>
                                <li>
                                    <p>

                                        Sahaz It Solutions Pvt.Ltd. is an intermediary as defined under the Information Technology Act, 2000. Sahaz It Solutions Pvt.Ltd. does not monitor or control any data or content uploaded by you to the Platform. You agree not to use or encourage, or permit others to store, upload, modify, update or share any information that:
                                    </p>
                                    <ul class="terms-ullist">
                                        <li>
                                            <p>
                                                Belongs to another person and to which you do not have any right;
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                is grossly harmful, misleading, harassing, blasphemous defamatory, indecent, obscene, pornographic, pedophilic, libelous, invasive of another's privacy, hateful, or racially, ethnically objectionable, disparaging, relating or encouraging money laundering or gambling, invasive of personal privacy or otherwise objectionable or any data / content that is contrary to any applicable local, national, and international laws and regulations;
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                Infringes any patent, trademark, copyright or other proprietary rights;
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                violates any law for the time being in force;
                                            </p>
                                        </li>
                                        <li>
                                            <p>Results in impersonation of any person or entity, or falsely states or otherwise misrepresents your affiliation with a person or entity;</p>
                                        </li>
                                        <li>
                                            <p>Is someone’s identification documents or sensitive financial information;</p>
                                        </li>
                                        <li>
                                            <p>
                                                Contains software viruses or any other computer code, files or programs designed to interrupt, destroy or limit the functionality of any computer resource;
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                threatens the unity, integrity, defense, security or sovereignty of India, friendly relations with foreign states, or public order or causes incitement to the commission of any cognizable offence or prevents investigation of any offence or is insulting any other nation; or
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                Makes available any data / content in contravention of these TOS or applicable policies, or any data / content that You do not have a right to access, store, use or make available to third parties under any law or contractual or fiduciary relationship
                                            </p>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <p>
                                        Sahaz It Solutions Pvt.Ltd. reserves the right to suspend or terminate your access to Your Account if you cause any disruption or harm to the Platform or to any third parties, or violate the provisions of the Information Technology Act, 2000, any applicable privacy laws or any of the applicable laws. You hereby consent to let Sahaz It Solutions Pvt.Ltd.’s employees and agents access Your Account and records on a case-to-case basis to investigate complaints or other allegations or suspected abuse.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Nothing contained herein shall be construed or implied to grant any right or license to use any trademarks, trade names, service marks or logos, which are a part of the Platform or the Services, without the prior written consent of the owner of rights in such marks.


                                    </p>
                                </li>
                            </ol>
                        </li>
                        <li>
                            <h5>DISCLAIMER OF WARRANTIES</h5>
                            <ol>
                                <li>
                                    <p>
                                        THE PLATFORM AND THE SERVICES ARE PROVIDED ON AN “AS-IS” AND “WITH ALL FAULTS AND RISKS” BASIS, WITHOUT WARRANTIES OF ANY KIND. Sahaz It Solutions Pvt.Ltd. DOES NOT WARRANT, EXPRESSLY OR BY IMPLICATION, THE ACCURACY OR RELIABILITY OF THE PLATFORM OR THE SERVICES OR THE SAFETY/SECURITY OF THE DATA/CONTENT STORED BY YOU. Sahaz It Solutions Pvt.Ltd. DISCLAIMS ALL WARRANTIES WHETHER EXPRESS OR IMPLIED, INCLUDING THOSE OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, NON-INFRINGEMENT, OR THAT USE OF THE PLATFORM OR ANY MATERIAL THEREOF WILL BE UNINTERRUPTED OR ERROR-FREE.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Sahaz It Solutions Pvt.Ltd. provides no warranty on the use of the Platform and the Services, and shall not be liable for the same under any laws applicable to intellectual property rights, libel, privacy, publicity, obscenity or other laws. Sahaz It Solutions Pvt.Ltd. also disclaims all liability with respect to the misuse, loss, modification or unavailability of the Platform and the Services.
                                    </p>
                                </li>
                            </ol>
                        </li>
                        <li>
                            <h5>DISCLAIMER</h5>
                            <ol>
                                <li>
                                    <p>
                                        Our customers/partners and prospective partners are strongly advised to seek information/clarifications by contacting our business office directly and/or online through email at <a href="mailto:support@Sahajmoney.org" class="color-blue">support@Sahajmoney.org</a> before they enroll in any of our merchant program or services and communicate with any website other than <a href="https://sahajmoney.org/" target="_blank" class="color-blue">www.Sahajmoney.org/</a>.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Sahaz It Solutions Pvt.Ltd. advise you to be guarded against any fraudulent activity being perpetuated by fraudsters who aim to deceive and defraud our customers/partners by unauthorized use of Company name and trademarks on their websites and emails. This type of fraud may be via email, letters, text messages, facsimile, acting as Company employee or by using a website purporting to be that of Sahaz It Solutions Pvt.Ltd. Private Limited.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Sahaz It Solutions Pvt.Ltd. hereby disclaims all such transactions/correspondence and always warns its customers/partners and the general public to disregard such fraudsters and to exercise extreme caution.
                                        <strong>
                                            Any information which is provided by the Company like login password, transaction password, OTP or any other information is always highly confidential and do not share with anyone. Sahaz It Solutions Pvt.Ltd. never ask any confidential information and sharing any such information is done at your own risk. Sahaz It Solutions Pvt.Ltd. will have NO LIABILITY whatsoever for any and all losses/damages suffered by anyone who falls victim to such scams/letters from fraudsters
                                        </strong>
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        You expressly agree that use of the Sahazmoney Services on the Sahazmoney Platform is at Your sole risk. It is Your responsibility to evaluate the accuracy, completeness and usefulness of all opinions, advice, services and other information provided through the site or on the Internet generally.
                                    </p>
                                </li>
                            </ol>
                        </li>
                        <li>
                            <h5>LIMITATION OF LIABILITY</h5>
                            <ol>
                                <li>
                                    <p>
                                        YOU ASSUME THE ENTIRE RISK OF USING THE PLATFORM AND THE SERVICES. IN NO EVENT SHALL Sahaz It Solutions Pvt.Ltd. BE LIABLE TO YOU FOR ANY SPECIAL, INCIDENTAL, INDIRECT, PUNITIVE OR CONSEQUENTIAL DAMAGES WHATSOEVER (INCLUDING, WITHOUT LIMITATION, DAMAGES FOR LOSS OF BUSINESS PROFITS, BUSINESS INTERRUPTION, LOSS OF DATA OR INFORMATION, OR ANY OTHER PECUNIARY LOSS) ARISING OUT OF THE USE OF, OR INABILITY TO USE OR ACCESS THE PLATFORM OR THE SERVICES OR FOR ANY SECURITY BREACH OR ANY VIRUS, BUG, UNAUTHORIZED INTERVENTION, DEFECT, OR TECHNICAL MALFUNCTIONING OF THE PLATFORM, WHETHER OR NOT FORESEEABLE AND WHETHER OR NOT Sahaz It Solutions Pvt.Ltd. HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES, OR BASED ON ANY THEORY OF LIABILITY, INCLUDING BREACH OF CONTRACT OR WARRANTY, NEGLIGENCE OR OTHER TORTIOUS ACTION, OR ANY OTHER CLAIM ARISING OUT OF, OR IN CONNECTION WITH, YOUR USE OF, OR ACCESS TO, THE SOFTWARE OR THE SERVICES. FURTHER, Sahaz It Solutions Pvt.Ltd. SHALL NOT BE LIABLE TO YOU FOR ANY TEMPORARY DISABLEMENT, PERMANENT DISCONTINUANCE OF THE SERVICES BY Sahaz It Solutions Pvt.Ltd., DATA LOSS OR FOR ANY CONSEQUENCES RESULTING FROM SUCH ACTIONS.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Sahaz It Solutions Pvt.Ltd.’S AGGREGATE LIABILITY, IF ANY, (WHETHER UNDER CONTRACT, TORT INCLUDING NEGLIGENCE, WARRANTY OR OTHERWISE) AND THAT OF ITS AFFILIATES SHALL BE LIMITED TO AMOUNT OF FEES, IF ANY, PAID BY YOU TO Sahaz It Solutions Pvt.Ltd.. DAMAGES, IN THE NATURE, AND TO THE AMOUNT, PROVIDED IN THIS CLAUSE, IS THE ONLY RECOURSE THAT YOU MAY HAVE AGAINST Sahaz It Solutions Pvt.Ltd. FOR BREACH BY Sahaz It Solutions Pvt.Ltd. OF ANY OF ITS RIGHTS OR OBLIGATIONS HEREUNDER.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        The Platform may provide links to other third-party websites. However since Sahaz It Solutions Pvt.Ltd. has no control over such third party websites, You acknowledge and agree that Sahaz It Solutions Pvt.Ltd. is not responsible for the availability of such third party websites, and does not endorse and is not responsible or liable for any content, advertising, products or other materials on or available from such third party websites. You further acknowledge and agree that Sahaz It Solutions Pvt.Ltd. shall not be responsible or liable, directly or indirectly, for any damage or loss caused or alleged to be caused by or in connection with use of or reliance on any such content, goods or services available on or through any such third party websites.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Sahaz It Solutions Pvt.Ltd., its affiliates and technology partners make no representations or warranties about the accuracy, reliability, completeness, correctness and/or timeliness of any content, information, software, text, graphics, links or communications provided on or through the use of the Platform or that the operation of the Site will be error free and/or uninterrupted.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Sahaz It Solutions Pvt.Ltd., its affiliates and technology partners shall not be liable for any direct or indirect damages caused to You or to any third parties due to information provided by You pursuant to this TOS being untrue, inaccurate, not current or incomplete, or not in accordance with the TOS.
                                    </p>
                                </li>
                            </ol>
                        </li>
                        <li>
                            <h5>TERMINATION </h5>
                            <ol>
                                <li>
                                    <p>
                                        Sahaz It Solutions Pvt.Ltd. reserves the right to terminate Your Account or restrict or prohibit You access to the Platform immediately (a) if Sahaz It Solutions Pvt.Ltd. is unable to verify or authenticate Your registration data, email address or other information provided by You, (b) if Sahaz It Solutions Pvt.Ltd. believes that Your actions may cause legal liability for You or for Sahaz It Solutions Pvt.Ltd., or all or some of Sahaz It Solutions Pvt.Ltd.’s other users, or (c) if Sahaz It Solutions Pvt.Ltd. believes You have provided false or misleading registration data or other information, have not updated Your Account Information, have interfered with other users or the administration of the Services, or have violated this TOS or the Privacy Policy.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        In addition to the above, if you have not logged into Your Account for a continuous period of one year(s), Sahaz It Solutions Pvt.Ltd. shall have the option of terminating Your Account.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Upon termination of this TOS, your right to access the Platform and use the Services shall immediately cease. Thereafter, you shall have no right, and Sahaz It Solutions Pvt.Ltd. shall have no obligation thereafter, to execute any of the uncompleted tasks.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Once the Services are terminated or suspended, any data that you may have stored on the Platform, may not be retrieved later. Sahaz It Solutions Pvt.Ltd. shall be under no obligation to return the information or data to you.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Sahaz It Solutions Pvt.Ltd. may at any time at its sole discretion reinstate suspended Users. A User that has been indefinitely suspended may not register or attempt to register with Sahaz It Solutions Pvt.Ltd. or use the Platform in any manner whatsoever until such time that such User is reinstated by Sahaz It Solutions Pvt.Ltd.. Notwithstanding the foregoing, if you breach the TOS or the documents it incorporates by reference, Sahaz It Solutions Pvt.Ltd. reserves the right to recover any damages and amounts due and owing by You to Sahaz It Solutions Pvt.Ltd. and to take strict legal action including but not limited to a referral to the Cyber Crime Division /initiating criminal proceedings against you.
                                    </p>
                                </li>
                            </ol>
                        </li>
                        <li>
                            <h5>MISCELLANEOUS </h5>
                            <ol>
                                <li>
                                    <p>
                                        Indemnity.
                                        You agree to indemnify and hold Sahaz It Solutions Pvt.Ltd. (and its officers, directors, agents and employees) harmless from any and against any claims, causes of action, demands, recoveries, losses, damages, fines, penalties or other costs or expenses of any kind or nature, including reasonable attorneys' fees, or arising out of or related to Your breach of this TOS or the documents it incorporates by reference, Your violation of any law or the rights of a third party, or Your use of the Platform.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Grievance Officer.
                                        In case of any grievances with the working of the Platform, you may inform the Grievance Officer of the grievances or any violation of the TOS.
                                    </p>
                                </li>
                                <li>
                                    <p>Governing Law.This TOS is governed and construed in accordance with the laws of India. The courts in Kushinagar alone shall have exclusive jurisdiction to hear disputes arising out of the TOS.</p>
                                </li>
                                <li>
                                    <p>
                                        Force Majeure.
                                        Sahaz It Solutions Pvt.Ltd. shall be under no liability whatsoever in case of occurrence of a Force Majeure event, including in case of non-availability of any portion of the Platform and/or Services occasioned by act of God, war, disease, revolution, riot, civil commotion, strike, lockout, flood, fire, failure of any public utility, man-made disaster, infrastructure failure, technology outages, failure of technology integration of partners or any other cause whatsoever, beyond the control of Sahaz It Solutions Pvt.Ltd.. Further, in case of a force majeure event, Sahaz It Solutions Pvt.Ltd. shall not be liable for any breach of security or loss of data uploaded by you to the Platform.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Waiver.
                                        Any failure by Sahaz It Solutions Pvt.Ltd. to enforce the TOS, for whatever reason, shall not necessarily be construed as a waiver of any right to do so at any time.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Severability.
                                        If any of the provisions of this TOS are deemed invalid, void, or for any reason unenforceable, that part of the TOS will be deemed severable and will not affect the validity and enforceability of any remaining provisions of the TOS.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Electronic Communications.
                                        When you visit the Platform or send emails to us, you are communicating with us electronically. You consent to receive communications from us electronically. We will communicate with you by email or by posting notices on the Platform. You agree that all agreements, notices, disclosures and other communications that we provide to you electronically satisfy any legal requirement that such communications be in writing.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Entire Agreement.
                                        The TOS as amended from time to time, along with the Privacy Policy and other related policies made available from time to time, constitutes the entire agreement and supersedes all prior understandings between the parties relating to the subject matter herein.
                                    </p>
                                </li>
                            </ol>
                        </li>
                    </ol>
                    <p>
                    For any queries regarding TOS, you may please contact <a href="mailto:support@Sahajmoney.org">support@Sahajmoney.org</a></p>
                </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
      </div>
    </div>
  </div>
</div>
<!-- term and condition code end here -->
<footer id="footer-down">
  <div class="row footer-background">
    <div class="col-lg-4 col-12 text-center">
      <ul class="social-icon" style="padding-top:14px; padding-left:0px;">
        <li class="facebook hvr-pulse"><a href="#"><i class="fa fa-facebook-f"></i></a></li>
        <li class="twitter hvr-pulse"><a href="#"><i class="fa fa-twitter"></i></a></li>
        <li class="linkedin hvr-pulse"><a href="#"><i class="fa fa-linkedin"></i></a></li>
        <li class="youtube hvr-pulse"><a href="#"><i class="fa fa-youtube"></i></a></li>
        <li class="instagram hvr-pulse"><a href="#"><i class="fa fa-instagram"></i></a></li>
      </ul>
    </div>
    <div class="col-lg-4 col-12 ">
      <div class="text-center">
        <a class="hvr-pulse" href="https://play.google.com/store/apps/details?id=com.nera.SahazmoneyProduction" target="_blank"><img src="{{asset("")}}front/images/app/google-play-badge-342x132.png" width="200px;"></a>
      </div>
    </div>
    <div class="col-lg-4 col-12 text-center">
      <div class="row" style="padding-top: 20px; cursor: pointer;">
        <div class="col-3 feb-button hvr-pulse" data-toggle="modal" data-target="#privacy">Privacy Policy</div>
        <div class="col-3 feb-button hvr-pulse" data-toggle="modal" data-target="#refound">Refound Policy</div>
        <div class="col-3 feb-button hvr-pulse" data-toggle="modal" data-target="#term">Terms of Use</div>
      </div>
    </div>
  </div>
  <div class="text-center backaground">
    <p class="color"> &copy; Copyright 2016-2022 Sahaz It Solutions Pvt.Ltd.
      <a href="#" target="_blank"></a>
    </p>
  </div>
</footer>



    <div id="passwordResetModal" class="modal fade" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left">Password Reset Request</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="passwordRequestForm" action="{{route('authReset')}}" method="post">
                        <b><p class="text-danger"></p></b>
                        <input type="hidden" name="type" value="request">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>Mobile</label>
                            <input type="text" name="mobile" class="form-control" placeholder="Enter Mobile Number" required="">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-block text-uppercase waves-effect waves-light" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Resetting">Reset Request</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div id="passwordModal" class="modal fade" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left">Password Reset</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert bg-success alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        <span class="text-semibold">Success!</span> Your password reset token successfully sent on your registered e-mail id & Mobile number.
                    </div>
                    <form id="passwordForm" action="{{route('authReset')}}" method="post">
                        <b><p class="text-danger"></p></b>
                        <input type="hidden" name="mobile">
                        <input type="hidden" name="type" value="reset">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>Reset Token</label>
                            <input type="text" name="token" class="form-control" placeholder="Enter OTP" required="">
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter New Password" required="">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-block text-uppercase waves-effect waves-light" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Resetting">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div id="termsModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left">TERMS AND CONDITIONS</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div id="registerModal" class="modal fade" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-slate">
                    <h5 class="modal-title pull-left">Member Registration</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-body">
                    <form id="registerForm" action="{{route('register')}}" method="post">
                        {{ csrf_field() }}
                        <legend>Personal Details</legend>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1" class="text-uppercase">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputPassword1" class="text-uppercase">Email</label>
                                <input type="text" name="email" class="form-control" placeholder="Enter your email id" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputPassword1" class="text-uppercase">Mobile</label>
                                <input type="text" name="mobile" class="form-control" placeholder="Enter your mobile" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputPassword1" class="text-uppercase">Alternate Mobile</label>
                                <input type="text" name="alternate_mobile" class="form-control" placeholder="Enter your alternate mobile" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>State</label>
                                <select name="state" class="form-control state" required="">
                                    <option value="">Select State</option>
                                    @foreach ($state as $state)
                                        <option value="{{$state->state}}">{{$state->state}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>City</label>
                                <input type="text" name="city" class="form-control" value="" required="" placeholder="Enter Value">
                            </div>
                            <div class="form-group col-md-3">
                                <label>District</label>
                                <input type="text" name="district" class="form-control" value="" required="" placeholder="Enter Value">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Pincode</label>
                                <input type="text" name="pincode" class="form-control" value="" required="" maxlength="6" minlength="6" placeholder="Enter Value" pattern="[0-9]*">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Address</label>
                                <textarea name="address" class="form-control" rows="3" required="" placeholder="Enter Value"></textarea>
                            </div>
                        </div>

                        <legend>Kyc Information</legend>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Shop Name</label>
                                <input type="text" name="shopname" class="form-control" value="" required="" placeholder="Enter Value">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Pancard</label>
                                <input type="text" name="pancard" class="form-control" value="" required="" placeholder="Enter Value">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Aadhar</label>
                                <input type="text" name="aadharcard" required="" class="form-control" placeholder="Enter Value" pattern="[0-9]*" maxlength="12" minlength="12">
                            </div>
                        </div>
                        <div class="text-center form-group">
                            <button type="submit" class="btn btn-lg bg-slate">Submit</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

<!--Jquery-->
<script type="text/javascript" src="{{asset("")}}front/js/jquery.min.js"></script>
<!--Boostrap-Jquery-->
<script type="text/javascript" src="{{asset("")}}front/js/bootstrap.js"></script>
<!--Preetyphoto-Jquery-->
<script type="text/javascript" src="{{asset("")}}front/js/jquery.prettyPhoto.js"></script>
<!--NiceScroll-Jquery-->
<script type="text/javascript" src="{{asset("")}}front/js/jquery.nicescroll.js"></script>
<script type="text/javascript" src="{{asset("")}}front/js/waypoints.min.js"></script>
<!--Isotopes-->
<script type="text/javascript" src="{{asset("")}}front/js/jquery.isotope.js"></script>
<!--Wow-Jquery-->
<script type="text/javascript" src="{{asset("")}}front/js/wow.js"></script>
<!--Count-Jquey-->
<script type="text/javascript" src="{{asset("")}}front/js/jquery.countTo.js"></script>
<script type="text/javascript" src="{{asset("")}}front/js/jquery.inview.min.js"></script>
<!--Owl-Crousels-Jqury-->
<script type="text/javascript" src="{{asset("")}}front/js/owl.carousel.js"></script>
<!--Main-Scripts-->
<script type="text/javascript" src="{{asset("")}}front/js/script.js"></script>
<script type="text/javascript" src="{{asset('')}}assets/js/core/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{asset('')}}assets/js/core/jquery.form.min.js"></script>
<script type="text/javascript" src="{{asset('')}}assets/js/core/sweetalert2.min.js"></script>
<script src="{{asset('')}}assets/js/core/snackbar.js"></script>
<script src="{{asset('')}}assets/js/crytojs/cryptojs-aes-format.js"></script>
<script src="{{asset('')}}assets/js/crytojs/cryptojs-aes.min.js"></script>
<script type="text/javascript" src="{{asset('')}}assets/js/core/jquery-confirm.min.js"></script>

<script type="text/javascript">
    window.alert = function(){};
    var defaultCSS = document.getElementById('bootstrap-css');
    function changeCSS(css){
        if(css) $('head > link').filter(':first').replaceWith('<link rel="stylesheet" href="'+ css +'" type="text/css" />'); 
        else $('head > link').filter(':first').replaceWith(defaultCSS); 
    }

    $(document).ready(function() {
        $.fn.extend({
            myalert: function(value, type, time = 5000) {
                var tag = $(this);
                tag.find('.myalert').remove();
                tag.append('<p id="" class="myalert text-' + type + '">' + value + '</p>');
                tag.find('input').focus();
                tag.find('select').focus();
                setTimeout(function() {
                    tag.find('.myalert').remove();
                }, time);
                tag.find('input').change(function() {
                    if (tag.find('input').val() != '') {
                        tag.find('.myalert').remove();
                    }
                });
                tag.find('select').change(function() {
                    if (tag.find('select').val() != '') {
                        tag.find('.myalert').remove();
                    }
                });
            },

            mynotify: function(value, type, time = 5000) {
                var tag = $(this);
                tag.find('.mynotify').remove();
                tag.prepend(`<div class="mynotify alert alert-` + type + ` alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    ` + value + `
                </div>`);
                setTimeout(function() {
                    tag.find('.mynotify').remove();
                }, time);
            }
        });

        LOGINSYSTEM = {
                DEFAULT: function() {
                    LOGINSYSTEM.BEFORE_SUBMIT();
                },

                BEFORE_SUBMIT: function() {
                    $('#login').submit(function() {
                        var username = $('#login').find("[name='mobile']").val();
                        var password = $('#login').find("[name='password']").val();

                        if (username == "") {
                            $('#login').find("[name='mobile']").closest('.form-group').myalert('Enter username', 'danger');
                        }else if (password == "") {
                            $('#login').find("[name='password']").closest('.form-group').myalert('Enter Password', 'danger');
                        } else {
                            var form = $('#login');
                            swal({
                                title: 'Wait!',
                                text: 'Please wait, we are working on your request',
                                onOpen: () => {
                                    swal.showLoading()
                                }
                            });
                            if (navigator.geolocation){
                                navigator.geolocation.getCurrentPosition(
                                    function(position){
                                        form.find("[name='gps_location']").val(position.coords.latitude+"/"+position.coords.longitude);
                                        localStorage.setItem("gps_location", position.coords.latitude+"/"+position.coords.longitude);
                                        form.find("[name='password']").val(CryptoJSAesJson.encrypt(JSON.stringify(form.serialize()), "{{ csrf_token() }}"));
                                        LOGINSYSTEM.LOGIN();
                                    },function(error){
                                        switch(error.code) {
                                            case error.PERMISSION_DENIED:
                                                swal({
                                                    type  : 'error',
                                                    title : 'Location Access Denied',
                                                    text  : 'Kindly allow permission to access location for secure browsing',
                                                });
                                                return false;
                                            break;

                                            default:
                                                LOGINSYSTEM.LOGIN();
                                            break;
                                        }
                                    }
                                );
                            }
                        }

                        return false;
                    });

                    $("#registerForm").validate({
                        rules: {
                            name: {
                                required: true,
                            },
                            mobile: {
                                required: true,
                                number: true
                            },
                            email: {
                                required: true,
                                email: true
                            }
                        },
                        messages: {
                            mobile: {
                                required: "Please enter mobile",
                                number: "mobile should be numeric",
                            },
                            name: {
                                required: "Please enter your name",
                            },
                            email: {
                                required: "Please enter your email",
                                email: "Please enter valid email"
                            }
                        },
                        errorElement: "p",
                        errorPlacement: function(error, element) {
                            if (element.prop("tagName").toLowerCase() === "select") {
                                error.insertAfter(element.closest(".form-group").find(".select2"));
                            } else {
                                error.insertAfter(element);
                            }
                        },
                        submitHandler: function() {
                            LOGINSYSTEM.REGISTRATION()
                        }
                    });

                    $( "#passwordForm" ).validate({
                        rules: {
                            token: {
                                required: true,
                                number : true
                            },
                            password: {
                                required: true,
                            }
                        },
                        messages: {
                            token: {
                                required: "Please enter reset token",
                                number: "Reset token should be numeric",
                            },
                            password: {
                                required: "Please enter password",
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
                            var form = $('#passwordForm');
                            form.ajaxSubmit({
                                dataType:'json',
                                beforeSubmit:function(){
                                    swal({
                                        title: 'Wait!',
                                        text: 'We are checking your login credential',
                                        onOpen: () => {
                                            swal.showLoading()
                                        },
                                        allowOutsideClick: () => !swal.isLoading()
                                    });
                                },
                                success:function(data){
                                    swal.close();
                                    if(data.status == "TXN"){
                                        $('#passwordModal').modal('hide');
                                        swal({
                                            type: 'success',
                                            title: 'Reset!',
                                            text: 'Password Successfully Changed',
                                            showConfirmButton: true
                                        });
                                    }else{
                                        notify(data.message, 'warning');
                                    }
                                },
                                error: function(errors) {
                                    swal.close();
                                    if(errors.status == '400'){
                                        notify(errors.responseJSON.status, 'warning');
                                    }else{
                                        notify('Something went wrong, try again later.', 'warning');
                                    }
                                }
                            });
                        }
                    });
                },

                LOGIN: function() {
                    var form = $('#login');
                    SYSTEM.FORMSUBMIT($('#login'), function(data) {
                        swal.close();
                        if (!data.statusText) {
                            if (data.status == "TXN") {
                                form.find("[name='password']").val(null);
                                swal({
                                    type: 'success',
                                    title: 'Successfully Logged In.',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    onClose: () => {
                                        window.location.reload();
                                    }
                                });
                            } else if(data.status == "TXNOTP"){
                                var otpConfirm = $.confirm({
                                    lazyOpen: true,
                                    title: 'Otp Verification',
                                    content: '' +
                                    '<form action="javascript:void(0)" id="otpValidateForm">' +
                                    '<div class="form-group">' +
                                    '<input type="password" placeholder="Enter Otp" name="otp" class="name form-control" required />' +
                                    '</div>' +
                                    '<p class="text-success"><b>'+data.message+'</b></p>'+
                                    '</form>',
                                    buttons: {
                                        formSubmit: {
                                            text: 'Submit',
                                            keys: ['enter', 'shift'],
                                            btnClass: 'btn-blue',
                                            action: function () {
                                                var otp = this.$content.find('[name="otp"]').val();
                                                var mobile  = $('#login').find('[name="mobile"]').val();
                                                var password = $('#login').find('[name="password"]').val();
                                                if(!otp){
                                                    $.alert({
                                                        title: 'Oops!',
                                                        content: 'Provide a valid otp',
                                                        type: 'red'
                                                    });
                                                    return false;
                                                }
                                                otpConfirm.close();
                                                var data = { 
                                                    "_token" : "{{csrf_token()}}", 
                                                    "mobile" : mobile, 
                                                    "otp" : CryptoJSAesJson.encrypt(JSON.stringify("otp="+otp), "{{csrf_token()}}"), 
                                                    "password" : password,
                                                    "gps_location" : localStorage.getItem("gps_location")
                                                };

                                                form.find("[name='password']").val(null);
                                                SYSTEM.AJAX("{{route('authCheck')}}", "POST", data, function(data){
                                                    if(!data.statusText){
                                                        if(data.status == "TXN"){
                                                            form.find("[name='password']").val(null);
                                                            $.alert({
                                                                title: 'Login',
                                                                content: "Successfully Login",
                                                                type: 'green'
                                                            });

                                                            setTimeout(function(){
                                                                window.location.reload();
                                                            }, 2000);
                                                        }else{
                                                            if(data.status == 400){
                                                                $.alert({
                                                                    title: 'Oops!',
                                                                    content: data.responseJSON.message,
                                                                    type: 'red'
                                                                });
                                                            }else{
                                                                if(data.message){
                                                                    $.alert({
                                                                        title: 'Oops!',
                                                                        content: data.message,
                                                                        type: 'red'
                                                                    });
                                                                }else{
                                                                    $.alert({
                                                                        title: 'Oops!',
                                                                        content: data.statusText,
                                                                        type: 'red'
                                                                    });
                                                                }
                                                            }
                                                        }
                                                    }
                                                });
                                                return false;
                                            }
                                        },
                                        cancel: function () {
                                            form.find("[name='password']").val(null);
                                        },
                                        'Resend Otp': function () {
                                            OTPRESEND();
                                            return false;
                                        },
                                    }
                                });  
                                otpConfirm.open();
                            }else {
                                form.find("[name='password']").val(null);
                                SYSTEM.SHOWERROR(data, $('#login'));
                            }
                        } else {
                                form.find("[name='password']").val(null);
                            SYSTEM.SHOWERROR(data, $('#login'));
                        }
                    });
                },

                REGISTRATION: function() {
                    SYSTEM.FORMSUBMIT($('#registerForm'), function(data) {
                        swal.close();
                        if (!data.statusText) {
                            if (data.status == "TXN") {
                                $('#registerForm')[0].reset();
                                $('#registerModal').modal('hide');
                                swal({
                                    type: 'success',
                                    title: 'Success',
                                    text: 'Thank You for join us, your accont details will be sent on your mobile number and email id',
                                    showConfirmButton: true
                                });
                            } else {
                                SYSTEM.SHOWERROR(data, $('#registerForm'));
                            }
                        } else {
                            SYSTEM.SHOWERROR(data, $('#registerForm'));
                        }
                    });
                },

                PASSWORDRESET: function() {
                    var mobile = $('#login').find('input[name="mobile"]').val();
                    var ele = $(this);
                    if (mobile.length > 0) {
                        $.ajax({
                                url: '{{ route("authReset") }}',
                                type: 'post',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                beforeSend: function() {
                                    swal({
                                        title: 'Wait!',
                                        text: 'Please wait, we are working on your request',
                                        onOpen: () => {
                                            swal.showLoading()
                                        }
                                    });
                                },
                                data: {
                                    'type': 'request',
                                    'mobile': mobile
                                },
                                complete: function() {
                                    swal.close();
                                }
                            })
                            .done(function(data) {
                                swal.close();
                                if (data.status == "TXN") {
                                    $('#passwordForm').find('input[name="mobile"]').val(mobile);
                                    $('#passwordModal').modal('show');
                                } else {
                                    notify(data.message, 'warning');
                                }
                            })
                            .fail(function() {
                                swal.close();
                                notify('Something went wrong, try again', 'warning');
                            });
                    } else {
                        notify('Enter mobile number to reset password', 'warning');
                    }
                }
            },

            SYSTEM = {
                NOTIFY: function(type, title, message) {
                    swal({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 10000,
                        type: type,
                        title: title,
                        text: message
                    });
                },

                FORMSUBMIT: function(form, callback) {
                    form.ajaxSubmit({
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSubmit: function() {
                        },
                        complete: function() {
                        },
                        success: function(data) {
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
                            swal({
                                title: 'Wait!',
                                text: 'Please wait, we are working on your request',
                                onOpen: () => {
                                    swal.showLoading()
                                }
                            });
                        },
                        complete: function () {
                            swal.close();
                        },
                        success:function(data){
                            callback(data);
                        },
                        error: function(errors) {
                            callback(errors);
                        }
                    });
                },

                SHOWERROR: function(errors, form, type = "inline") {
                    if (type == "inline") {
                        if (errors.statusText) {
                            if (errors.status == 422) {
                                form.find('p.error').remove();
                                $.each(errors.responseJSON, function(index, value) {
                                    form.find('[name="' + index + '"]').closest('div.form-group').myalert(value, 'danger');
                                });
                            } else if (errors.status == 400) {
                                form.mynotify(errors.responseJSON.message, 'danger');
                            } else {
                                form.mynotify(errors.statusText, 'danger');
                            }
                        } else {
                            form.mynotify(errors.message, 'danger');
                        }
                    } else {
                        if (errors.statusText) {
                            if (errors.status == 400) {
                                SYSTEM.NOTIFY('error', 'Oops', errors.responseJSON.message);
                            } else {
                                SYSTEM.NOTIFY('error', 'Oops', errors.statusText);
                            }
                        } else {
                            SYSTEM.NOTIFY('error', 'Oops', errors.message);
                        }
                    }
                }
            }

        LOGINSYSTEM.DEFAULT();
    });

    function OTPRESEND() {
        var mobile = $('input[name="mobile"]').val();
        var password = $('input[name="password"]').val();
        if(mobile.length > 0){
            $.ajax({
                url: '{{ route("authCheck") }}',
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data :  {'mobile' : mobile, 'password' : password , 'otp' : "resend", "_token" : "{{csrf_token()}}"},
                beforeSend:function(){
                    swal({
                        title: 'Wait!',
                        text: 'Please wait, we are working on your request',
                        onOpen: () => {
                            swal.showLoading()
                        }
                    });
                },
                complete: function(){
                    swal.close();
                }
            })
            .done(function(data) {
                if(data.status == "TXNOTP"){
                    $.alert({
                        title: 'Login',
                        content: "Otp sent successfully",
                        type: 'green'
                    });
                }else{
                    $.alert({
                        title: 'Oops!',
                        content: data.message,
                        type: 'red'
                    });
                }
            })
            .fail(function() {
                $.alert({
                    title: 'Oops!',
                    content: "Something went wrong, try again",
                    type: 'red'
                });
            });
        }else{
            $.alert({
                title: 'Oops!',
                content: "Enter your registered mobile number",
                type: 'red'
            });
        }
    }

    function notify(msg, type="success"){
        let snackbar  = new SnackBar;
        snackbar.make("message",[
            msg,
            null,
            "bottom",
            "right",
            "text-"+type
        ], 5000);
    }
</script>
</body>
</html>

<!-- Hosting24 Analytics Code -->
<script type="text/javascript" src="http://stats.hosting24.com/count.php"></script>
<!-- End Of Analytics Code -->
