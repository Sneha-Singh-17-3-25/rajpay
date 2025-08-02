@extends('layouts.offline')
@section('title', $KIOSKCODEJ)
@section('name', $KIOSKNAMEJ)

@section('content')
<div class="content">
    <div class="tabbable">
        <ul class="nav nav-tabs bg-teal-600 nav-tabs-component no-margin-bottom">
            <li><a href="#openaccount" data-toggle="tab" id="reportTab" class="legitRipple active" aria-expanded="true"><i class="icon-plus22"></i> Open Account</a></li>
            <li><a href="#reoprt" data-toggle="tab" id="reportTab" class="legitRipple" aria-expanded="false"><i class="icon-file-text3"></i> Report</a></li>
        </ul><br>
        <!--<div>-->
        <!--    <marquee style="color: red; font-weight: bold;">-->
        <!--        सूचना :- प्रिय किओस्क, NSDL Payment Bank में तकनीकी समस्या के कारण Account Opening की सेवा अभी बंद है। जल्द ही इसका समाधान कर दिया जाएगा। -->
        <!--        Rajpay Helpdesk No - 9587667777। धन्यवाद।-->
        <!--    </marquee>-->
        <!--</div>-->
        <div class="tab-content pt-20">
            <div class="tab-pane active" id="openaccount">
                @if(session('responseData'))
                <div class="row " style="margin:10px 0;">
                    <div class="col-md-12 ">
                        <div class="alert alert-info text-center p-3" role="alert" style="border-radius: 10px; font-size: 18px;">
                            <strong>Result : {{ session('responseData') }}</strong>
                        </div>
                    </div>
                </div>
                @endif

                @if(!$bankagent ?? '')
                @if(!$agent_onboarded ?? ' ')

                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Agent Onboard for NSDL Payments Bank Account</h4>
                            </div>
                            <form action="{{route('accountNsdlKyc')}}" method="post" id="nsdlkycForm" enctype="multipart/form-data">
                                <div class="panel-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="gps_location">
                                    <!-- <input type="hidden" name="user_id" value="{{$KIOSKCODEJ}}"> -->
                                    <input type="hidden" name="user_id" value="{{ $KIOSKCODEJ}}">

                                    <input type="hidden" name="transactionType" id="transactionType" value="bankagentonboard">

                                    <!-- Personal Information -->
                                    <h6 class="form-section-title">Personal Information</h6>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" autocomplete="off" name="agentName" placeholder="Enter Name" value="{{$firstname}}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Middle Name</label>
                                            <input type="text" class="form-control" name="middlename" placeholder="Enter middle name">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="agentLastName" autocomplete="off" placeholder="Enter Your Last Name" value="{{$lastname}}" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>Date of Birth <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="agentDob" autocomplete="off" placeholder="Enter Your Mobile" value="" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>PAN Card <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" autocomplete="off" name="userPan" value="" placeholder="Enter PAN number" maxlength="10" required>
                                            <small class="form-text text-muted">Format: ABCDE1234F</small>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Company/Shop Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="agentShopName" autocomplete="off" placeholder="Enter Company/Shop Name" value="" required>
                                        </div>
                                    </div>

                                    <!-- Contact Information -->
                                    <h6 class="form-section-title mt-4">Contact Information</h6>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>Mobile <span class="text-danger">*</span></label>
                                            <input type="text" pattern="[0-9]*" maxlength="10" minlength="10" class="form-control" name="agentPhoneNumber" autocomplete="off" placeholder="Enter Your Mobile" value="" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Alternate Mobile</label>
                                            <input type="text" class="form-control" name="alternatenumber" placeholder="Enter alternate number">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Telephone</label>
                                            <input type="text" class="form-control" name="telephone" placeholder="Enter telephone number">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Email ID <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="agentEmail" autocomplete="off" placeholder="Enter Your Email" value="" required>
                                    </div>

                                    <!-- Residential Address -->
                                    <h6 class="form-section-title mt-4">Residential Address</h6>
                                    <div class="form-group">
                                        <label>Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" autocomplete="off" name="agentAddress" placeholder="Enter Address" value="" required>
                                    </div>

                                    <div class="row">
                                        <!--<div class="form-group col-md-3">-->
                                        <!--    <label>State <span class="text-danger">*</span></label>-->
                                        <!--    <input type="text" class="form-control" autocomplete="off" name="agentState" value="" placeholder="Enter state" required>-->
                                        <!--</div>-->
                                        <div class="form-group col-md-3">
                                            <label>State <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="agentState" value="Rajasthan" placeholder="Enter state" readonly>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>City <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" autocomplete="off" name="agentCityName" placeholder="Enter City" value="" required>
                                        </div>
                                        <!--<div class="form-group col-md-3">-->
                                        <!--    <label>District <span class="text-danger">*</span></label>-->
                                        <!--    <input class="form-control" name="district" placeholder="Enter district name">-->
                                        <!--</div>-->
                                        <div class="form-group col-md-3">
                                            <label>District <span class="text-danger">*</span></label>
                                            <select class="form-control select2" name="district" required>
                                                <option value="">Select shop district</option>
                                                <option>Ajmer</option>
                                                <option>Alwar</option>
                                                <option>Balotra</option>
                                                <option>Banswara</option>
                                                <option>Baran</option>
                                                <option>Barmer</option>
                                                <option>Beawar</option>
                                                <option>Bharatpur</option>
                                                <option>Bhilwara</option>
                                                <option>Bikaner</option>
                                                <option>Bundi</option>
                                                <option>Chittorgarh</option>
                                                <option>Churu</option>
                                                <option>Dausa</option>
                                                <option>Deeg</option>
                                                <option>Dholpur</option>
                                                <option>Didwana-Kuchaman</option>
                                                <option>Dungarpur</option>
                                                <option>Hanumangarh</option>
                                                <option>Jaipur</option>
                                                <option>Jaisalmer</option>
                                                <option>Jalore</option>
                                                <option>Jhalawar</option>
                                                <option>Jhunjhunu</option>
                                                <option>Jodhpur</option>
                                                <option>Karauli</option>
                                                <option>Kota</option>
                                                <option>Kotputli-Behror</option>
                                                <option>Nagaur</option>
                                                <option>Pali</option>
                                                <option>Phalodi</option>
                                                <option>Pratapgarh</option>
                                                <option>Rajsamand</option>
                                                <option>Salumbar</option>
                                                <option>Sawai Madhopur</option>
                                                <option>Sikar</option>
                                                <option>Sirohi</option>
                                                <option>Sri Ganganagar</option>
                                                <option>Tonk</option>
                                                <option>Udaipur</option>
                                                <option>Khairthal-Tijara</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Area <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="area" placeholder="Enter area name">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>PIN Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" autocomplete="off" name="agentPinCode" maxlength="6" minlength="6" pattern="[0-9]*" value="" placeholder="Enter PIN Code" required>
                                    </div>

                                    <!-- Shop Address -->
                                    <h6 class="form-section-title mt-4">Shop Address</h6>
                                    <div class="form-group">
                                        <label>Shop Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="shopaddress" rows="3" placeholder="Enter shop address"></textarea>
                                    </div>

                                    <div class="row">
                                        <!--<div class="form-group col-md-3">-->
                                        <!--    <label>Shop State <span class="text-danger">*</span></label>-->
                                        <!--    <input class="form-control" name="shopstate" placeholder="Enter shop state">-->
                                        <!--</div>-->
                                        <div class="form-group col-md-3">
                                            <label>Shop State <span class="text-danger">*</span></label>
                                            <input class="form-control" name="shopstate" value="Rajasthan" placeholder="Shop State" readonly>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Shop City <span class="text-danger">*</span></label>
                                            <input class="form-control" name="shopcity" placeholder="Enter shop city">
                                        </div>
                                        <!--<div class="form-group col-md-3">-->
                                        <!--    <label>Shop District <span class="text-danger">*</span></label>-->
                                        <!--    <input class="form-control" name="shopdistrict" placeholder="Enter shop district">-->
                                        <!--</div>-->
                                        <div class="form-group col-md-3">
                                            <label>Shop District <span class="text-danger">*</span></label>
                                            <select class="form-control select2" name="shopdistrict" required>
                                                <option value="">Select shop district</option>
                                                <option>Ajmer</option>
                                                <option>Alwar</option>
                                                <option>Balotra</option>
                                                <option>Banswara</option>
                                                <option>Baran</option>
                                                <option>Barmer</option>
                                                <option>Beawar</option>
                                                <option>Bharatpur</option>
                                                <option>Bhilwara</option>
                                                <option>Bikaner</option>
                                                <option>Bundi</option>
                                                <option>Chittorgarh</option>
                                                <option>Churu</option>
                                                <option>Dausa</option>
                                                <option>Deeg</option>
                                                <option>Dholpur</option>
                                                <option>Didwana-Kuchaman</option>
                                                <option>Dungarpur</option>
                                                <option>Hanumangarh</option>
                                                <option>Jaipur</option>
                                                <option>Jaisalmer</option>
                                                <option>Jalore</option>
                                                <option>Jhalawar</option>
                                                <option>Jhunjhunu</option>
                                                <option>Jodhpur</option>
                                                <option>Karauli</option>
                                                <option>Kota</option>
                                                <option>Kotputli-Behror</option>
                                                <option>Nagaur</option>
                                                <option>Pali</option>
                                                <option>Phalodi</option>
                                                <option>Pratapgarh</option>
                                                <option>Rajsamand</option>
                                                <option>Salumbar</option>
                                                <option>Sawai Madhopur</option>
                                                <option>Sikar</option>
                                                <option>Sirohi</option>
                                                <option>Sri Ganganagar</option>
                                                <option>Tonk</option>
                                                <option>Udaipur</option>
                                                <option>Khairthal-Tijara</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Shop Area <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="shoparea" placeholder="Enter shop area">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Shop PIN Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="shoppincode" placeholder="Enter 6 digit shop PIN code">
                                    </div>

                                    <!-- Products -->
                                    <!--<h6 class="form-section-title mt-4">Product Details</h6>-->
                                    <!--<div class="row">-->
                                    <!--    <div class="form-group col-md-3">-->
                                    <!--        <div class="checkbox">-->
                                    <!--            <label>-->
                                    <!--                <input type="checkbox" name="dmt" value="1" class="styled"> DMT-->
                                    <!--            </label>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--    <div class="form-group col-md-3">-->
                                    <!--        <div class="checkbox">-->
                                    <!--            <label>-->
                                    <!--                <input type="checkbox" name="aeps" value="1" class="styled"> AEPS-->
                                    <!--            </label>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--    <div class="form-group col-md-3">-->
                                    <!--        <div class="checkbox">-->
                                    <!--            <label>-->
                                    <!--                <input type="checkbox" name="cardpin" value="1" class="styled"> Card PIN-->
                                    <!--            </label>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--    <div class="form-group col-md-3">-->
                                    <!--        <div class="checkbox">-->
                                    <!--            <label>-->
                                    <!--                <input type="checkbox" name="accountopen" value="1" class="styled"> Account Open-->
                                    <!--            </label>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->

                                    <!-- Terminal Details -->
                                    <!--<div id="terminalDetails">-->
                                    <!--    <h6 class="form-section-title mt-4">Terminal Details</h6>-->
                                    <!--    <div class="row">-->
                                    <!--        <div class="form-group col-md-6">-->
                                    <!--            <label>Terminal Serial Number <span class="text-danger">*</span></label>-->
                                    <!--            <input type="text" class="form-control" name="tposserialno" placeholder="Enter terminal serial number">-->
                                    <!--        </div>-->
                                    <!--        <div class="form-group col-md-6">-->
                                    <!--            <label>Terminal Email</label>-->
                                    <!--            <input type="email" class="form-control" name="temail" placeholder="Enter terminal email">-->
                                    <!--        </div>-->
                                    <!--    </div>-->

                                    <!--    <div class="form-group">-->
                                    <!--        <label>Terminal Address <span class="text-danger">*</span></label>-->
                                    <!--        <textarea class="form-control" name="taddress" rows="2" placeholder="Enter terminal address"></textarea>-->
                                    <!--    </div>-->

                                    <!--    <div class="form-group">-->
                                    <!--        <label>Terminal Address Line 2</label>-->
                                    <!--        <textarea class="form-control" name="taddress1" rows="2" placeholder="Enter additional address details"></textarea>-->
                                    <!--    </div>-->

                                    <!--    <div class="row">-->
                                    <!--        <div class="form-group col-md-4">-->
                                    <!--            <label>Terminal State <span class="text-danger">*</span></label>-->
                                    <!--            <input type="text" class="form-control" name="tstate" placeholder="Enter terminal state">-->
                                    <!--        </div>-->
                                    <!--        <div class="form-group col-md-4">-->
                                    <!--            <label>Terminal City <span class="text-danger">*</span></label>-->
                                    <!--            <input type="text" class="form-control" name="tcity" placeholder="Enter terminal city">-->
                                    <!--        </div>-->
                                    <!--        <div class="form-group col-md-4">-->
                                    <!--            <label>Terminal PIN Code <span class="text-danger">*</span></label>-->
                                    <!--            <input type="text" class="form-control" name="tpincode" placeholder="Enter 6 digit terminal PIN code">-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->

                                    <!-- Agent Type -->
                                    <h6 class="form-section-title mt-4">Agent Type</h6>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Agent Type <span class="text-danger">*</span></label>
                                            <select class="form-control select2" name="agenttype" data-placeholder="Select agent type">
                                                <option value="1">Normal Agent</option>
                                                <option value="2">Direct Agent</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <!--<label>BC ID<span class="text-danger">*</span></label>-->
                                            <input type="hidden" class="form-control" name="agentbcid" value="3653" placeholder="3653">
                                        </div>
                                    </div>

                                    @if(isset($error))
                                    <div class="panel-footer text-center text-danger">
                                        Error - {{$error}}
                                    </div>
                                    @endif
                                </div>

                                <div class="panel-footer text-center">
                                    <button type="submit" class="btn bg-teal-400 btn-labeled legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Submitting"><b><i class=" icon-paperplane"></i></b> Proceed To Onboard</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success text-center p-3" role="alert" style="border-radius: 10px; font-size: 18px;">
                            <strong>Agent onboarding is in process</strong>
                        </div>
                    </div>
                </div>
                @endif
                @else
                <div class="row">
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    <div class="col-md-4">
                        <div class="panel panel-default panel-shadow">
                            <div class="panel-heading">
                                <h5 class="panel-title"><img src="{{asset('assets/images/balance.png')}}" width="40px" />Open Saving Account</h5>
                            </div>
                            <form action="{{ route('accountNsdlProcess') }}" method="post" id="transactionForm">
                                <input type="hidden" name="SSOID" value="{{$SSOID}}">
                                <input type="hidden" name="SERVICEID" value="{{$SERVICEID}}">
                                <input type="hidden" name="SSOTOKEN" value="{{$SSOTOKEN}}">
                                <input type="hidden" name="RETURNURL" value="{{$RETURNURL}}">
                                <input type="hidden" name="KIOSKNAMEJ" value="{{$KIOSKNAMEJ}}">
                                <!-- <input type="hidden" name="KIOSKCODEJ" value="{{$KIOSKCODEJ}}"> -->
                                <input type="hidden" name="KIOSKCODEJ" value="{{ $KIOSKCODEJ }}">
                                <input type="hidden" name="KIOSKDATA" value="{{$KIOSKDATA}}">
                                <input type="hidden" name="encData" value="{{$encData}}">
                                {{ csrf_field() }}

                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="Customername">Name</label>
                                        <input type="text" class="form-control" id="name" name="Customername"
                                            placeholder="Enter name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="mobileNo">Mobile</label>
                                        <input type="text" class="form-control" id="mobile" name="mobileNo"
                                            pattern="[0-9]*" maxlength="10" minlength="10" placeholder="Enter mobile number"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="Email">Email</label>
                                        <input type="email" class="form-control" id="Email" name="Email"
                                            placeholder="Enter email" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="panNo">PAN Number</label>
                                        <input type="text" class="form-control" id="panNo" name="panNo"
                                            placeholder="Enter pan" required>
                                    </div>
                                </div>

                                <div class="panel-footer text-center">
                                    <button type="submit" class="btn bg-teal-800 btn-labeled legitRipple btn-lg" onclick="return confirm('Are you sure you want to process this transaction?')"
                                        data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Submitting">
                                        <b><i class="icon-paperplane"></i></b> Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 10;" class="ml-paragraph">
                                <h2><b>NSDL JIFY ZERO BALANCE ACCOUNT</b></h2>
                                <img src="{{asset('assets/images/logo.png')}}" />
                            </div>
                            <div style="text-align: center;" class="ml-paragraph">NSDL Jiffy Zero Balance Account is a 100% Digital - Zero Balance savings account that comes with a host of features and benefits.<br>
                                You can open this account with eMitra within a few minutes using just your Aadhar and PAN number.<br>
                                With instant activation of the account, you can enjoy instant fund transfers, utility bill payments,quick recharge, UPI payments, and much more.
                            </div>
                        </div>

                        <div class="justify-content-center text-center">
                            <h2>Key Features</h2>
                        </div>

                        <div>
                            <p class="ml-paragraph">- Zero Balance Account (No requirement to maintain Average Monthly Balance)
                            </p>
                            <p class="ml-paragraph">- Free Virtual Debit Card, Choose your free virtual debit card from RuPay or
                                VISA
                                for online purchases/shopping.</p>
                            <p class="ml-paragraph">You can request a physical debit card from your NSDL Jiffy App</p>
                            <p class="ml-paragraph">- Instant Account Activation, Activate your account instantly with our 100%
                                digital, safe and secure account opening process</p>
                            <p class="ml-paragraph">- Bill Payments and Recharges</p>
                            <p class="ml-paragraph">- Banking On Mobile - 24/7</p>
                            <p class="ml-paragraph">Enjoy banking anytime, anywhere from your smartphone with NSDL Jiffy App.,
                                without
                                going to the bank branch</p>
                            <p>Account Opening Fee - 120/-</p>
                            <p>eMitra Commission - 20/</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="tab-pane report" id="reoprt">
                <div class="panel panel-default">
                    <form id="searchForm">
                        <!-- <input type="hidden" name="agent" value="{{$KIOSKCODEJ}}"> -->
                        <input type="hidden" name="agent" value="{{$KIOSKCODEJ}}">
                        <div class="panel panel-default no-margin">
                            <div class="panel-body p-tb-10">
                                <div class="row">
                                    <div class="form-group col-md-2 m-b-10">
                                        <input type="text" name="from_date" class="form-control mydate"
                                            placeholder="From Date">
                                    </div>

                                    <div class="form-group col-md-2 m-b-10">
                                        <input type="text" name="to_date" class="form-control mydate"
                                            placeholder="To Date">
                                    </div>

                                    <div class="form-group col-md-2 m-b-10">
                                        <input type="text" name="searchtext" class="form-control"
                                            placeholder="Search Value">
                                    </div>

                                    @if (isset($status))
                                    <div class="form-group col-md-2">
                                        <select name="status" class="form-control select">
                                            <option value="">Select {{ $status['type'] ?? '' }} Status
                                            </option>
                                            @if (isset($status['data']) && sizeOf($status['data']) > 0)
                                            @foreach ($status['data'] as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}
                                            </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @endif

                                    <div class="form-group col-md-4 m-b-10">
                                        <button type="submit" class="btn bg-slate btn-labeled legitRipple mt-5"
                                            data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Searching"><b><i
                                                    class="icon-search4"></i></b> Search</button>

                                        <button type="button" class="btn btn-warning btn-labeled legitRipple mt-5"
                                            id="formReset"
                                            data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Refreshing"><b><i
                                                    class="icon-rotate-ccw3"></i></b> Refresh</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="datatable" width="100%">
                            <thead>
                                <tr style="white-space: nowrap;">
                                    <th>S.No.</th>
                                    <th>Action</th>
                                    <th>Type</th>
                                    <th>Customer Name</th>
                                    <th>Request ID</th>
                                    <th>Transaction ID</th>
                                    <th>Status</th>
                                    <th>URL</th>
                                    <th>Acknowledge No</th>
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
        </div>
    </div>
</div>

<div id="receiptModal" class="modal fade" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Receipt</h4>
            </div>
            <div class="modal-body">
                <div id="receptTable">
                    <table class="table m-t-10">
                        <thead>
                            <tr>
                                <th style="padding: 10px 0px;">
                                    <img src="{{asset('')}}public/{{$mydata['company']->logo}}" class="img-responsive pull-left" alt="" style="height: 60px; width: 260px;">
                                </th>
                                <th style="padding: 10px 0px;">
                                    <img src="{{asset('')}}public/logos/t-logo.png" class="img-responsive pull-right" alt="" style="height: 75%; width: 75%;">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 10px 0px" class="text-left">
                                    <address class="m-b-10">
                                        <strong>KIOSK Code : </strong> <span class="p-0">@yield('title')</span><br>
                                        <strong>KIOSK Name : </strong><span class="p-0">@yield('name')</span><br>
                                        <strong>Date : </strong><span class="created_at"></span>
                                    </address>
                                </td>
                                <td style="padding: 10px 0px" class="text-right">
                                    <address class="m-b-10 default">
                                        <strong>Consumer Name : </strong> <span class="option1"></span><br>
                                        <strong>Operator Name : </strong> <span class="product"></span><br>
                                    </address>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <h5>Transaction Details :</h5>
                                <table class="table m-t-10 default">
                                    <thead>
                                        <tr>
                                            <th>Request ID</th>
                                            <th>Ack No</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="txnid text-left"></td>
                                            <td class="refno text-left"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table m-t-10 default">
                                    <thead>
                                        <tr>
                                            <th>Txn Id</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="payid text-left"></td>
                                            <td class="status text-left"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="border-radius: 0px;">
                        <div class="col-md-6 col-md-offset-6">
                            <h6 class="text-right">Amount : <span class="amount"></span></h6>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn bg-slate btn-raised legitRipple" type="button" id="printModal"><i class="fa fa-print"></i></button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    td {
        white-space: nowrap;
        max-width: 100%;
        size: 10px;
        font-size: 13px;
        text-align: center;
    }

    .label {
        font-size: 10px;
        display: flex;
    }

    .content {
        background-image: url("{{asset('assets/images/asia.png')}}");
        background-repeat: no-repeat;
        background-size: 100%;
        background-position: bottom center;
        padding-top: 200px;
        background-color: rgba(255, 255, 255, 0.6);
        background-blend-mode: hard-light;
    }

    .vertical {
        padding: 0px !important;
    }
</style>
@endpush

@push('script')

<script src="{{ asset('/assets/js/core/jQuery.print.js') }}"></script>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        function checkGPS() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        console.log("GPS is enabled.");
                        console.log("Latitude:", position.coords.latitude);
                        console.log("Longitude:", position.coords.longitude);

                        let gpsInput = document.querySelector("input[name='gps_location']");
                        //   if (!gpsInput.value) {
                        //   console.log('yes')

                        var loc = gpsInput.value = position.coords.latitude + "/" + position.coords.longitude;
                        if (loc) {
                            console.log('yes');
                        }

                        //   }
                        // You might want to call your success function here
                        // handleLocationSuccess(position);
                    },
                    (error) => {
                        if (error.code === error.PERMISSION_DENIED) {
                            showGPSModal("Location access is denied", "Please enable location permissions in your browser settings.");
                        } else if (error.code === error.POSITION_UNAVAILABLE) {
                            showGPSModal("GPS is disabled", "Please enable GPS on your device to use this feature.");
                        } else {
                            showGPSModal("Location error", "Unable to access your location. Please check your GPS settings.");
                        }
                    }, {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                );
            } else {
                showGPSModal("Not supported", "Geolocation is not supported by your browser.");
            }
        }

        function showGPSModal(title, message) {
            // Create overlay
            const overlay = document.createElement("div");
            overlay.id = "gps-modal-overlay";
            overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    `;

            // Create modal
            const modal = document.createElement("div");
            modal.id = "gps-modal";
            modal.style.cssText = `
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        padding: 24px;
        width: 90%;
        max-width: 400px;
        text-align: center;
        animation: fadeIn 0.3s ease-out;
    `;

            // Create modal content
            const icon = document.createElement("div");
            icon.innerHTML = `
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="10" stroke="#FF5757" stroke-width="2"/>
            <path d="M12 7V13" stroke="#FF5757" stroke-width="2" stroke-linecap="round"/>
            <circle cx="12" cy="16" r="1" fill="#FF5757"/>
        </svg>
    `;

            const titleElement = document.createElement("h2");
            titleElement.textContent = title;
            titleElement.style.cssText = `
        margin: 16px 0 8px 0;
        font-size: 20px;
        color: #333;
    `;

            const messageElement = document.createElement("p");
            messageElement.textContent = message;
            messageElement.style.cssText = `
        margin: 0 0 24px 0;
        color: #666;
        line-height: 1.5;
    `;

            // Create buttons
            const buttonContainer = document.createElement("div");
            buttonContainer.style.cssText = `
        display: flex;
        justify-content: center;
        gap: 16px;
    `;

            const cancelButton = document.createElement("button");
            cancelButton.textContent = "Cancel";
            cancelButton.style.cssText = `
        padding: 10px 16px;
        border: 1px solid #ddd;
        background-color: #f5f5f5;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.2s;
    `;
            cancelButton.onclick = () => {
                document.body.removeChild(overlay);
            };

            const enableButton = document.createElement("button");
            enableButton.textContent = "Enable GPS";
            enableButton.style.cssText = `
        padding: 10px 16px;
        border: none;
        background-color: #4285F4;
        color: white;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.2s;
    `;
            enableButton.onclick = () => {
                document.body.removeChild(overlay);
                checkGPS(); // Try again
            };

            // Add elements to modal
            modal.appendChild(icon);
            modal.appendChild(titleElement);
            modal.appendChild(messageElement);

            buttonContainer.appendChild(cancelButton);
            buttonContainer.appendChild(enableButton);

            modal.appendChild(buttonContainer);
            overlay.appendChild(modal);

            // Add styles for animation
            const style = document.createElement('style');
            style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
            document.head.appendChild(style);

            // Add modal to body
            document.body.appendChild(overlay);
        }

        // Call function when needed
        checkGPS();
    });
    $(document).ready(function() {
        $('#print').click(function() {
            $('#receipt').find('.modal-body').print();
        });

        $('#printModal').click(function() {
            $('#receiptModal').find('.modal-body').print();
        });

        $(window).load(function() {
            @if(isset($status))
            $("#receipt").modal("show");
            @endif

            $("#{{$type}}Tab").click();
        });


        $("#transactionForm").validate({
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
            errorPlacement: function(error, element) {
                if (element.prop("tagName").toLowerCase() === "select") {
                    error.insertAfter(element.closest(".form-group").find(".select2"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function() {
                var form = $('#transactionForm');
                SYSTEM.FORMSUBMIT(form, function(data) {
                    form[0].reset();
                    form.find('[name="pin"]').val("");
                    if (!data.statusText) {
                        if (data.statuscode == "TXN") {
                            // window.open(data.message, '_blank').focus();
                            window.location.href = data.message;
                        } else if (data.statuscode == "TXF") {
                            $.alert({
                                title: 'Oops!',
                                content: data.message,
                                type: 'red'
                            });
                        } else {
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

        $('[name="dataType"]').val("nsdl");

        var url = "{{route("
        nsdlreport ")}}";

        var onDraw = function() {
            $('.print').click(function(event) {
                var data = DT.row($(this).parent().parent().parent().parent().parent()).data();

                $.each(data, function(index, values) {
                    $("." + index).text(values);
                });
                $('address.dmt').hide();
                $('address.aeps').hide();
                $('address.default').hide();

                if (data['product'] == "dmt") {
                    $('address.dmt').show();
                } else if (data['product'] == "aeps") {
                    $('address.aeps').show();
                } else {
                    $('address.default').show();
                }
                $('#receiptModal').modal();
            });
        };

        var options = [{
                "data": "id"
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    var menu = `<li class="dropdown-header">Action</li>`;
                    menu += `<li><a href="javascript:void(0)" class="print"><i class="icon-info22"></i>Print Invoice</a></li>`;

                    return `<ul class="icons-list mr-5">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-left">
                                        ` + menu + `
                                    </ul>
                                </li>
                            </ul>`;
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    return full.product;
                }
            },
            {
                "data": "option1"
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    if (full.product == "matm" && full.status == "failed") {
                        var out = `<a href="javascript:void(0)" class="viewreport" data-popup="tooltip" title="" data-original-title="View report" >` + full.txnid + `</a>`;
                    } else {
                        var out = `<a href="javascript:void(0)" class="viewreport" data-popup="tooltip" title="" data-original-title="View report">` + full.txnid + `</a>`;
                    }

                    return out;
                }
            },
            {
                "data": "payid"
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    var out = "";
                    if (full.status == "success") {
                        var out = `<span class="label label-success">Success</span>`;
                    } else if (full.status == "accept") {
                        var out = `<span class="label label-info">Accept</span>`;
                    } else if (full.status == "pending") {
                        var out = `<span class="label label-warning">Pending</span>`;
                    } else if (full.status == "reversed" || full.status == "refunded") {
                        var out = `<span class="label bg-slate">` + full.status + `</span>`;
                    } else {
                        var out = `<span class="label label-danger">` + full.status + `</span>`;
                    }

                    return out;
                }
            },
            {
                data: 'url',
                render: function(data, type, row) {
                    return `<a href="${data}" target="_blank">Go to URL</a>`;
                }
            },
            {
                "data": "refno"
            },
            {
                "data": "amount"
            },
            {
                "data": "created_at"
            },
            {
                "data": "remark"
            }
        ];

        DT = datatableSetup(url, options, onDraw);
    });

    @if(!$bankagent)
    // Function to submit the form using fetch
    function submitFormWithFetch() {
        // Get the form element
        const form = document.getElementById('nsdlkycForm');

        // Add event listener to the form submission
        form.addEventListener('submit', function(event) {
            // Prevent the default form submission
            event.preventDefault();

            // Get the submit button to update its state
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonHtml = submitButton.innerHTML;

            // Update button to loading state
            submitButton.innerHTML = submitButton.getAttribute('data-loading-text');
            submitButton.disabled = true;

            // Create a FormData object from the form
            const formData = new FormData(form);

            // CSRF token handling - ensure it's included
            const csrfToken = document.querySelector('input[name="_token"]').value;

            // Log form data for debugging (remove in production)
            console.log("Form data before submission:");
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            // Get the form action URL
            const url = form.getAttribute('action');

            // Set up a response container if it doesn't exist
            let responseContainer = document.getElementById('responseContainer');
            if (!responseContainer) {
                responseContainer = document.createElement('div');
                responseContainer.id = 'responseContainer';
                responseContainer.className = 'panel panel-default mt-3';
                form.after(responseContainer);
            }

            // Perform the fetch request
            fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin' // Important for CSRF token
                })
                .then(response => {
                    console.log("Response status:", response.status);
                    console.log("Response headers:", [...response.headers]);

                    // For debugging
                    if (!response.ok) {
                        console.error("Response not OK:", response.status, response.statusText);
                    }

                    // Check content type to determine how to process the response
                    const contentType = response.headers.get('Content-Type');
                    console.log("Content-Type:", contentType);

                    if (contentType && contentType.includes('application/json')) {
                        return response.json().then(data => {
                            console.log("JSON response:", data);
                            return data;
                        });
                    } else {
                        // Attempt to capture redirect information
                        return response.text().then(text => {
                            console.log("Text response (first 100 chars):", text.substring(0, 100));
                            return {
                                statuscode: "TXN",
                                message: "Form submitted successfully. Please check the server response."
                            };
                        });
                    }
                })
                .then(data => {
                    // Display the response
                    responseContainer.innerHTML = `
        <div class="panel-heading">
          <h4 class="panel-title">Response</h4>
        </div>
       
      `;

                    // Add status message based on response
                    if (data.statuscode === "TXN") {
                        responseContainer.innerHTML += `
          <div class="panel-footer text-center text-success">
            ${data.message || "Operation completed successfully"}
          </div>
        `;
                    } else if (data.statuscode === "ERR") {
                        responseContainer.innerHTML += `
          <div class="panel-footer text-center text-danger">
            ${data.message || "An error occurred"}
          </div>
        `;
                    }
                })
                .catch(error => {
                    console.error("Fetch error:", error);

                    // Display any errors
                    responseContainer.innerHTML = `
        <div class="panel-heading">
          <h4 class="panel-title">Error</h4>
        </div>
        <div class="panel-body">
          <div class="alert alert-danger">
            ${error.message}
          </div>
          <div>
            <p>Please try the following:</p>
            <ul>
              <li>Check browser console for detailed error messages</li>
              <li>Ensure all required fields are filled correctly</li>
              <li>Try refreshing the page and submitting again</li>
            </ul>
          </div>
        </div>
      `;
                })
                .finally(() => {
                    // Restore the button to its original state
                    submitButton.innerHTML = originalButtonHtml;
                    submitButton.disabled = false;

                    // Scroll to the response container
                    responseContainer.scrollIntoView({
                        behavior: 'smooth'
                    });
                });
        });
    }
    @endif
    // Initialize the script when the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('nsdlkycForm');
        if (form) {
            submitFormWithFetch();
        }

    });
</script>
@endpush