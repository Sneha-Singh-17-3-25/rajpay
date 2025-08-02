<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - <?php echo e($company->name ?? ''); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('')); ?>/assets/loginpage/vendor/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('')); ?>/assets/loginpage/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('')); ?>/assets/loginpage/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('')); ?>/assets/loginpage/vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('')); ?>/assets/loginpage/vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('')); ?>/assets/loginpage/vendor/animsition/css/animsition.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('')); ?>/assets/loginpage/vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('')); ?>/assets/loginpage/vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('')); ?>/assets/loginpage/css/util.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('')); ?>/assets/loginpage/css/main.css">
    <link href="<?php echo e(asset('')); ?>assets/css/jquery-confirm.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('')); ?>assets/css/snackbar.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('')); ?>/assets/loginpage/css/custom-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <title>Login - <?php echo e($company->name ?? ''); ?></title>

    <style type="text/css">
    .password-container{
              width: 253px;
              position: relative;
            }
            .password-container input[type="password"],
            .password-container input[type="text"]{
              width: 170%;
              padding: 12px 36px 12px 12px;
              box-sizing: border-box;
            }
            .fa-eye{
              position: absolute;
              top: 58%;
              right: -65%;
              cursor: pointer;
              color: darkgray;
            }
        p.error{
            color: #dc3545 !important;
        }

        p.help {
            position: absolute;
            top: -70px;
            right: 25px;
        }

        .jconfirm .jconfirm-box div.jconfirm-content-pane{
            display: block !important;
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center align-items-center h-screen">

                <div class="col-md-6 mb-3 mb-md-0">
                    <!-- <img src="template/login/images/undraw_remotely_2j6y.svg" alt="Image" class="img-fluid"> -->
                    <div class="row justify-content-center align-items-center h-md-screen bg-light py-3">
                        <div class="col-md-10">
                            <div id="carouselExampleSlidesOnly" class="carousel slide bg-white p-3" data-ride="carousel">
                                <div class="carousel-inner">

                                    <?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $slidess): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($key == "0"): ?>
                                            <div class="carousel-item active">
                                                <img class="d-block w-100" src="<?php echo e($slidess->value); ?>" style="width: 540; height: 475px;" alt="First slide">
                                            </div>
                                        <?php else: ?>
                                            <div class="carousel-item">
                                                <img class="d-block w-100" src="<?php echo e($slidess->value); ?>" style="width: 540; height: 475px;" alt="First slide">
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 contents mb-5 mb-md-0">
                    <div class="row justify-content-center align-items-center py-3">
                        <div class="col-lg-8 col-md-10 mb-3">
                            <div class="text-left mb-3 text-center">
                                <?php if(isset($company) && $company->logo): ?>
                                <img src="<?php echo e(asset('public/' . $company->logo)); ?>" style="height: 70px;">
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-lg-8 col-md-10">
                            <div class="form-title">
                                <span class="title">Login Here</span>
                            </div>
                            <form class="login100-form validate-form" id="login" method="POST" action="<?php echo e(route('authCheck')); ?>" novalidate="">
                                <?php echo e(csrf_field()); ?>

                                <input type="hidden" name="gps_location">
                                <div class="form-group first password-container">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" name="mobile" value="<?php echo e(old('mobile')); ?>" id="cmobile" placeholder="Enter username">
                                </div>
                                <div class="form-group last password-container">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" class="form-control" id="log_password" placeholder="Enter password">
                                     <i class="fa-solid fa-eye" id="show-password"></i>
                                </div>
                                
                                <div class="d-flex mb-4 align-items-center justify-content-between pull-left">
                                    <div>
                                        <a href="<?php echo e(route('policy')); ?>" class="font-weight-bold text-black" id="">Privacy Policy</a>
                                    </div>
                                </div>

                                <div class="d-flex mb-4 align-items-center justify-content-between pull-right">
                                    <div>
                                        <a href="#" class="font-weight-bold text-black" id="authReset">Forgot Password</a>
                                    </div>
                                </div>

                                <input type="submit" id="cconfirm" value="Log In" class="btn btn-block btn-primary" />

                                <div class="mt-3 text-center">
                                    <p class="text-primary text-center">
                                        <strong>Help & Support</strong><br><?php echo e($mydata['supportnumber'] ?? ''); ?>

                                    </p>
                                    <br>
                                    <a href="#" data-toggle="modal" data-target="#registerModal" class="font-weight-bold text-black btn btn-primary">Register With Us</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="registerModal" class="modal fade" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-slate">
                    <h6 class="modal-title pull-left">Member Registration</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="registerForm" action="<?php echo e(route('register')); ?>" method="post">
                        <?php echo e(csrf_field()); ?>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputPassword1">Email</label>
                                <input type="text" name="email" class="form-control" placeholder="Enter your email id" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputPassword1">Mobile</label>
                                <input type="text" name="mobile" class="form-control" placeholder="Enter your mobile" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>State</label>
                                <select name="state" class="form-control state" required="">
                                    <option value="">Select State</option>
                                    <?php $__currentLoopData = $state; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($state->state); ?>"><?php echo e($state->state); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>City</label>
                                <input type="text" name="city" class="form-control" value="" required="" placeholder="Enter Value">
                            </div>
                            <div class="form-group col-md-4">
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
                            <button type="submit" class="btn btn-primary">Submit</button>
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
                    <form id="passwordForm" action="<?php echo e(route('authReset')); ?>" method="post">
                        <b><p class="text-danger"></p></b>
                        <input type="hidden" name="mobile">
                        <input type="hidden" name="type" value="reset">
                        <?php echo e(csrf_field()); ?>

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

    <snackbar></snackbar>
    <script src="<?php echo e(asset('')); ?>/assets/loginpage/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="<?php echo e(asset('')); ?>/assets/loginpage/vendor/animsition/js/animsition.min.js"></script>
    <script src="<?php echo e(asset('')); ?>/assets/loginpage/vendor/bootstrap/js/popper.js"></script>
    <script src="<?php echo e(asset('')); ?>/assets/loginpage/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo e(asset('')); ?>assets/js/core/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo e(asset('')); ?>assets/js/core/jquery.form.min.js"></script>
    <script type="text/javascript" src="<?php echo e(asset('')); ?>assets/js/core/sweetalert2.min.js"></script>
    <script type="text/javascript" src="<?php echo e(asset('')); ?>assets/js/core/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="<?php echo e(asset('')); ?>assets/js/core/notify.min.js"></script>
    <script src="<?php echo e(asset('')); ?>assets/js/core/snackbar.js"></script>
    <script src="<?php echo e(asset('')); ?>assets/js/crytojs/cryptojs-aes-format.js"></script>
    <script src="<?php echo e(asset('')); ?>assets/js/crytojs/cryptojs-aes.min.js"></script>
    <script type="text/javascript">
        var LOGINROOT = "<?php echo e(url('auth')); ?>",
            LOGINSYSTEM;

        function getLocation(){
            if (navigator.geolocation){
                navigator.geolocation.getCurrentPosition(showPosition,showError);
            }
        }

        function showPosition(position){
            var lat = position.coords.latitude;
            var lon = position.coords.longitude;
            console.log("lat :" + lat);
            console.log("lon :" + lon);
        }

        function showError(error){
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    swal({
                        type: 'error',
                        title : 'Location Access Denied',
                        text: 'Kindly allow permission to access location for secure browsing',
                    });
                break;

                case error.POSITION_UNAVAILABLE:
                    swal({
                        type: 'error',
                        title : 'error',
                        text: 'Permission Denied',
                    });
                    break;

                case error.TIMEOUT:
                    swal({
                        type: 'error',
                        title : 'error',
                        text: 'Permission Denied',
                    });
                    break;

                case error.UNKNOWN_ERROR:
                    swal({
                        type: 'error',
                        title : 'error',
                        text: 'Permission Denied',
                    });
                    break;
            }
        }

        function notify(msg, type = "success") {
            let snackbar = new SnackBar;
            snackbar.make("message", [
                msg,
                null,
                "bottom",
                "right",
                "text-" + type
            ], 5000);
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
                        LOGINSYSTEM.PASSWORDRESET();
                    },

                    BEFORE_SUBMIT: function() {
                        $('#login').submit(function() {
                            var username = $("[name='mobile']").val();
                            var password = $("[name='password']").val();

                            if (username == "") {
                                $("[name='mobile']").closest('.form-group').myalert('Enter username', 'danger');
                            }else if (password == "") {
                                $("[name='password']").closest('.form-group').myalert('Enter Password', 'danger');
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
                                            form.find("[name='password']").val(CryptoJSAesJson.encrypt(JSON.stringify(form.serialize()), "<?php echo e(csrf_token()); ?>"));
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
            window.location.href = '/home'; // Change this line
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
                                                        "_token" : "<?php echo e(csrf_token()); ?>",
                                                        "mobile" : mobile,
                                                        "otp" : CryptoJSAesJson.encrypt(JSON.stringify("otp="+otp), "<?php echo e(csrf_token()); ?>"),
                                                        "password" : password,
                                                        "gps_location" : localStorage.getItem("gps_location")
                                                    };

                                                    form.find("[name='password']").val(null);
                                                    SYSTEM.AJAX("<?php echo e(route('authCheck')); ?>", "POST", data, function(data){
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
                        $('#authReset').click(function() {
                            var mobile = $('input[name="mobile"]').val();
                            var ele = $(this);
                            if (mobile.length > 0) {
                                $.ajax({
                                        url: '<?php echo e(route("authReset")); ?>',
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
                        });
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
                    url: '<?php echo e(route("authCheck")); ?>',
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data :  {'mobile' : mobile, 'password' : password , 'otp' : "resend", "_token" : "<?php echo e(csrf_token()); ?>"},
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
        $(function() {
           $('#cmobile').keypress(function() {
              var self = $(this);
              //wait until character is inserted
              setTimeout(function() {
                 if (self.val().length > 9) {
                    $('#log_password').focus();
                 }
              }, 1);
           });
        });
    </script>
</body>

</html>

 <script> 
    const showPassword = document.querySelector("#show-password");
    const passwordField = document.querySelector("#log_password");

        showPassword.addEventListener("click", function(){
            this.classList.toggle("fa-eye-slash");
            const type = passwordField.getAttribute("type")==="password" ? "text" : "password";
            passwordField.setAttribute("type", type); 
        })
</script>
