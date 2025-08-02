@extends('layouts.app')
@section('title', 'Dashboard')
@section('pagetitle', 'Dashboard')

@php
    $search = "hide";
    $header = "hide";
@endphp

@section('content')

    @if(Myhelper::hasRole("admin"))
        <div class="content pt-20 p-b-0">
            <div class="row">
                <div class="col-md-2">
                    <div class="panel panel-body bg-success border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                        <div class="media no-margin">
                            <div class="media-body">
                                <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold mainwallet"></h5>
                                <div class="list-group-divider"></div>
                                <span>Main Wallet</span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-2">
                    <div class="panel panel-body bg-primary border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                        <div class="media no-margin">
                            <div class="media-body">
                                <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold aepswallet"></h5>
                                <div class="list-group-divider"></div>
                                <span>Aeps Wallet</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if(Myhelper::hasRole("admin"))
                    <div class="col-md-2">
                        <div class="panel panel-body bg-danger border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold downlinebalance"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Downline Balance</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="panel panel-body bg-indigo border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold lockedamount"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Locked Amount</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="panel panel-body bg-indigo border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold apibalance"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>E-Balance</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="panel panel-body bg-indigo border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold myrcapibalance"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>P-Balance</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if(Myhelper::hasRole(["admin", "whitelable", "md", "distributor"]))
        <div class="content pt-20 p-b-0">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-body no-margin">
                        <div class="row text-center">

                            @if(Myhelper::hasRole(["admin"]))
                                <div class="col-xs-2">
                                    <p><i class="icon-users2 icon-2x display-inline-block text-danger"></i></p>
                                    <h5 class="text-semibold no-margin">{{$whitelable}}</h5>
                                    <span class="text-muted text-size-small">Whitelable</span>
                                </div>
                            @endif

                            @if(Myhelper::hasRole(["admin", "whitelable"]))
                                <div class="col-xs-2">
                                    <p><i class="icon-users2 icon-2x display-inline-block text-primary"></i></p>
                                    <h5 class="text-semibold no-margin">{{$md}}</h5>
                                    <span class="text-muted text-size-small">Master Distributor</span>

                                    @if(Myhelper::hasRole(["whitelable"]))
                                        <br><span class="text-muted text-size-small">Stock : {{\Auth::user()->mstock}}</span>
                                    @endif
                                </div>
                            @endif

                            @if(Myhelper::hasRole(["admin", "whitelable", "md"]))
                                <div class="col-xs-2">
                                    <p><i class="icon-users2 icon-2x display-inline-block text-warning"></i></p>
                                    <h5 class="text-semibold no-margin">{{$distributor}}</h5>
                                    <span class="text-muted text-size-small">Distributor</span>

                                    @if(Myhelper::hasRole(["whitelable", "md"]))
                                        <br><span class="text-muted text-size-small">Stock : {{\Auth::user()->dstock}}</span>
                                    @endif
                                </div>
                            @endif

                            @if(Myhelper::hasRole(["admin", "whitelable", "md", "distributor"]))
                                <div class="col-xs-2">
                                    <p><i class="icon-users2 icon-2x display-inline-block text-success"></i></p>
                                    <h5 class="text-semibold no-margin">{{$retailer}}</h5>
                                    <span class="text-muted text-size-small">Retailer</span>
                                    @if(Myhelper::hasRole(["whitelable", "md", "distributor"]))
                                        <br><span class="text-muted text-size-small">Stock : {{\Auth::user()->rstock}}</span>
                                    @endif
                                </div>
                            @endif

                            @if(Myhelper::hasRole(["admin"]))
                                <div class="col-xs-2">
                                    <p><i class="icon-users2 icon-2x display-inline-block text-info"></i></p>
                                    <h5 class="text-semibold no-margin">{{$apiuser}}</h5>
                                    <span class="text-muted text-size-small">Retailer Lite</span>
                                </div>
                            @endif

                            @if(Myhelper::hasRole(["admin"]))
                                <div class="col-xs-2">
                                    <p><i class="icon-users2 icon-2x display-inline-block text-teal"></i></p>
                                    <h5 class="text-semibold no-margin">{{$other}}</h5>
                                    <span class="text-muted text-size-small">Other User</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(Myhelper::hasNotRole('retailer'))
        <div class="content p-b-0 pt-20">
            <form id="filterForm">
                <div class="panel panel-default no-margin" style="border-radius: 10px;">
                    <div class="panel-body p-tb-10">
                        <div class="row">
                            <div class="form-group col-md-3 m-b-10">
                                <input type="text" name="fromdate" class="form-control mydate" placeholder="From Date">
                            </div>
                            <div class="form-group col-md-3 m-b-10">
                                <input type="text" name="todate" class="form-control mydate" placeholder="To Date">
                            </div>

                            @if (\Myhelper::hasRole("admin"))
                                <div class="form-group col-md-3 m-b-10">
                                    <input type="text" name="userid" class="form-control" placeholder="Agent Id">
                                </div>
                            @endif
                            <div class="form-group col-md-3 m-b-10 pull-right">
                                <button type="submit" class="btn bg-slate btn-xs btn-labeled legitRipple btn-lg mt-10" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Searching"><b><i class="icon-search4"></i></b> Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif

    @if(Myhelper::hasNotRole('admin'))
        <div class="content pt-20 p-b-0">

            @if(Myhelper::hasNotRole('admin'))
                <div class="row">
                    <div class="col-sm-3 col-md-2 col-xl-2">
                        <a href="{{route('reports', ['type' => 'aeps'])}}" class="text-white">
                            <div class="panel panel-body bg-teal-600 border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                                <div class="media no-margin">
                                    <div class="media-body">
                                        <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold iaepssuccessamt"></h5>
                                        <div class="list-group-divider"></div>
                                        <span>Aeps Success</span>
                                        <h6 class="no-margin iaepscommission">0</h6>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-3 col-md-2 col-xl-2">
                        <a href="{{route('reports', ['type' => 'offlinebillpay'])}}" class="text-white">
                            <div class="panel panel-body bg-teal-600 border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                                <div class="media no-margin">
                                    <div class="media-body">
                                        <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold billpaysuccessamt"></h5>
                                        <div class="list-group-divider"></div>
                                        <span>Billpayment Success</span>
                                        <h6 class="no-margin billpaycommission">0</h6>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-3 col-md-2 col-xl-2">
                        <a href="{{route('reports', ['type' => 'recharge'])}}" class="text-white">
                            <div class="panel panel-body bg-teal-600 border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                                <div class="media no-margin">
                                    <div class="media-body">
                                        <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold rechargesuccessamt"></h5>
                                        <div class="list-group-divider"></div>
                                        <span>Recharge Success</span>
                                        <h6 class="no-margin rechargecommission">0</h6>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-3 col-md-2 col-xl-2">
                        <a href="{{route('reports', ['type' => 'money'])}}" class="text-white">
                            <div class="panel panel-body bg-teal-600 border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                                <div class="media no-margin">
                                    <div class="media-body">
                                        <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold dmtsuccessamt"></h5>
                                        <div class="list-group-divider"></div>
                                        <span>Dmt Success</span>
                                        <h6 class="no-margin dmtcommission">0</h6>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-3 col-md-2 col-xl-2">
                        <a href="{{route('reports', ['type' => 'matm'])}}" class="text-white">
                            <div class="panel panel-body bg-teal-600 border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                                <div class="media no-margin">
                                    <div class="media-body">
                                        <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i>
                                        <h5 class="media-heading text-semibold matmsuccessamt"></h5>
                                        <div class="list-group-divider"></div>
                                        <span>Matm Success</span>
                                        <h6 class="no-margin matmcommission">0</h6>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-3 col-md-2 col-xl-2">
                        <a href="{{route('reports', ['type' => 'pancard'])}}" class="text-white">
                            <div class="panel panel-body bg-teal-600 border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                                <div class="media no-margin">
                                    <div class="media-body">
                                        <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i>
                                        <h5 class="media-heading text-semibold pancardsuccessamt"></h5>
                                        <div class="list-group-divider"></div>
                                        <span>Pancard Success</span>
                                        <h6 class="no-margin pancardcommission">0</h6>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endif

            <hr>
            <div class="row">
                <div class="col-sm-3 col-md-2 col-xl-2">
                    <a href="{{route("aeps")}}">
                        <div class="panel">
                            <div class="panel-body text-center">
                                <img src="{{asset("assets/svgicon/aeps.svg")}}" alt="" style="width: 50px; height: 50px;">

                                <h6 class="text-semibold no-margin-bottom">
                                    Aeps <br> Cash Withdraw
                                </h6>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-3 col-md-2 col-xl-2">
                    <a href="{{route("aadharpay")}}">
                        <div class="panel">
                            <div class="panel-body text-center">
                                <img src="{{asset("assets/svgicon/aadharpayicon.svg")}}" alt="" style="width: 50px; height: 50px;">

                                <h6 class="text-semibold no-margin-bottom">
                                    Aadharpay <br> Cash Withdraw
                                </h6>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-sm-3 col-md-2 col-xl-2">
                    <a href="{{route("dmt1")}}">
                        <div class="panel">
                            <div class="panel-body text-center">
                                <img src="{{asset("assets/svgicon/dmt.svg")}}" alt="" style="width: 50px; height: 50px;">

                                <h6 class="text-semibold no-margin-bottom">
                                    Money Transfer <br> Banking
                                </h6>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-sm-3 col-md-2 col-xl-2">
                    <a href="{{route('account', ['type' => 'nps'])}}">
                        <div class="panel">
                            <div class="panel-body text-center">
                                <img src="{{asset("assets/svgicon/pension.svg")}}" alt="" style="width: 50px; height: 50px;">

                                <h6 class="text-semibold no-margin-bottom">
                                    National Pension <br> System
                                </h6>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-sm-3 col-md-2 col-xl-2">
                    <a href="{{route('fund', ['type' => 'aeps'])}}">
                        <div class="panel">
                            <div class="panel-body text-center">
                                <img src="{{asset("assets/svgicon/payout.svg")}}" alt="" style="width: 50px; height: 50px;">

                                <h6 class="text-semibold no-margin-bottom">
                                    Bank Payout <br> Aeps
                                </h6>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-sm-3 col-md-2 col-xl-2">
                    <a href="{{route('pancard' , ['type' => 'nsdl'])}}">
                        <div class="panel">
                            <div class="panel-body text-center">
                                <img src="{{asset("assets/svgicon/pancard.svg")}}" alt="" style="width: 50px; height: 50px;">

                                <h6 class="text-semibold no-margin-bottom">
                                    NSDl <br> Pancard
                                </h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-sm-3 col-md-2 col-xl-2">
                    <a href="{{route('recharge' , ['type' => 'mobile'])}}">
                        <div class="panel">
                            <div class="panel-body text-center">
                                <img src="{{asset("assets/svgicon/mobile.svg")}}" alt="" style="width: 50px; height: 50px;">

                                <h6 class="text-semibold no-margin-bottom">
                                    Mobile <br> Recharge
                                </h6>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-3 col-md-2 col-xl-2">
                    <a href="{{route('recharge' , ['type' => 'dth'])}}">
                        <div class="panel">
                            <div class="panel-body text-center">
                                <img src="{{asset("assets/svgicon/dth.svg")}}" alt="" style="width: 50px; height: 50px;">

                                <h6 class="text-semibold no-margin-bottom">
                                    Dth <br> Recharge
                                </h6>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-sm-3 col-md-2 col-xl-2">
                    <a href="{{route('recharge', ["type" => "fasttag"])}}">
                        <div class="panel">
                            <div class="panel-body text-center">
                                <img src="{{asset("assets/svgicon/fasttag.svg")}}" alt="" style="width: 50px; height: 50px;">

                                <h6 class="text-semibold no-margin-bottom">
                                    FastTag <br> Recharge
                                </h6>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-sm-3 col-md-2 col-xl-2">
                    <a href="{{route('recharge', ["type" => "cabletv"])}}">
                        <div class="panel">
                            <div class="panel-body text-center">
                                <img src="{{asset("assets/svgicon/cabletv.svg")}}" alt="" style="width: 50px; height: 50px;">

                                <h6 class="text-semibold no-margin-bottom">
                                    Cable-Tv <br> Recharge
                                </h6>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-sm-3 col-md-2 col-xl-2">
                    <a href="{{route('bill' , ['type' => 'electricity'])}}}">
                        <div class="panel">
                            <div class="panel-body text-center">
                                <img src="{{asset("assets/svgicon/electricity.svg")}}" alt="" style="width: 50px; height: 50px;">

                                <h6 class="text-semibold no-margin-bottom">
                                    Electricity <br>Bill Payment
                                </h6>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-sm-3 col-md-2 col-xl-2">
                    <a href="{{route('bill' , ['type' => 'loanrepay'])}}">
                        <div class="panel">
                            <div class="panel-body text-center">
                                <img src="{{asset("assets/svgicon/loanrepay.svg")}}" alt="" style="width: 50px; height: 50px;">

                                <h6 class="text-semibold no-margin-bottom">
                                    Loan <br> Re-Payment
                                </h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if(Myhelper::hasRole('admin'))
        <div class="content pt-20" >
            <div class="row">
                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'aeps'])}}" class="text-white">
                        <div class="panel panel-body border-left-success border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold faepssuccessamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>F-Aeps Success</span>
                                    <h6 class="no-margin faepssuccess">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'aeps'])}}" class="text-white">
                        <div class="panel panel-body border-left-warning border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold faepspendingamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>F-Aeps Pending</span>
                                    <h6 class="no-margin faepspending">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'aeps'])}}" class="text-white">
                        <div class="panel panel-body border-left-danger border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold faepsfailedamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>F-Aeps Failed</span>
                                    <h6 class="no-margin faepsfailed">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'aadharpay'])}}" class="text-white">
                        <div class="panel panel-body border-left-success border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold faadharpaysuccessamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>F-Aadharpay Success</span>
                                    <h6 class="no-margin faadharpaysuccess">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'aadharpay'])}}" class="text-white">
                        <div class="panel panel-body border-left-warning border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold faadharpaypendingamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>F-Aadharpay Pending</span>
                                    <h6 class="no-margin faadharpaypending">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'aadharpay'])}}" class="text-white">
                        <div class="panel panel-body border-left-danger border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold faadharpayfailedamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>F-Aadharpay Failed</span>
                                    <h6 class="no-margin faadharpayfailed">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'aeps'])}}" class="text-white">
                        <div class="panel panel-body border-left-success border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold iaepssuccessamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>I-Aeps Success</span>
                                    <h6 class="no-margin iaepssuccess">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'aeps'])}}" class="text-white">
                        <div class="panel panel-body border-left-warning border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold iaepspendingamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>I-Aeps Pending</span>
                                    <h6 class="no-margin iaepspending">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'aeps'])}}" class="text-white">
                        <div class="panel panel-body border-left-danger border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold iaepsfailedamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>I-Aeps Failed</span>
                                    <h6 class="no-margin iaepsfailed">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'aadharpay'])}}" class="text-white">
                        <div class="panel panel-body border-left-success border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold iaadharpaysuccessamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>I-Aadharpay Success</span>
                                    <h6 class="no-margin iaadharpaysuccess">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'aadharpay'])}}" class="text-white">
                        <div class="panel panel-body border-left-warning border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold iaadharpaypendingamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>I-Aadharpay Pending</span>
                                    <h6 class="no-margin iaadharpaypending">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'aadharpay'])}}" class="text-white">
                        <div class="panel panel-body border-left-danger border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold iaadharpayfailedamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>I-Aadharpay Failed</span>
                                    <h6 class="no-margin iaadharpayfailed">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'billpay'])}}" class="text-white">
                        <div class="panel panel-body border-left-success border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold bbpssuccessamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Bbps Success</span>
                                    <h6 class="no-margin bbpssuccess">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'billpay'])}}" class="text-white">
                        <div class="panel panel-body border-left-warning border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold bbpspendingamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Bbps Pending</span>
                                    <h6 class="no-margin bbpspending">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'billpay'])}}" class="text-white">
                        <div class="panel panel-body border-left-danger border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold bbpsfailedamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Bbps Failed</span>
                                    <h6 class="no-margin bbpsfailed">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'offlinebillpay'])}}" class="text-white">
                        <div class="panel panel-body border-left-success border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold billpaysuccessamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Billpay Success</span>
                                    <h6 class="no-margin billpaysuccess">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'offlinebillpay'])}}" class="text-white">
                        <div class="panel panel-body border-left-warning border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold billpaypendingamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Billpay Pending</span>
                                    <h6 class="no-margin billpaypending">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'offlinebillpay'])}}" class="text-white">
                        <div class="panel panel-body border-left-danger border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold billpayfailedamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Billpay Failed</span>
                                    <h6 class="no-margin billpayfailed">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'recharge'])}}" class="text-white">
                        <div class="panel panel-body border-left-success border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold rechargesuccessamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Recharge Success</span>
                                    <h6 class="no-margin rechargesuccess">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'recharge'])}}" class="text-white">
                        <div class="panel panel-body border-left-warning border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold rechargependingamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Recharge Pending</span>
                                    <h6 class="no-margin rechargepending">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'recharge'])}}" class="text-white">
                        <div class="panel panel-body border-left-danger border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i>
                                    <h5 class="media-heading text-semibold rechargefailedamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Recharge Failed</span>
                                    <h6 class="no-margin rechargefailed">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'dmt'])}}" class="text-white">
                        <div class="panel panel-body border-left-success border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold dmtsuccessamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Dmt Success</span>
                                    <h6 class="no-margin dmtsuccess">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'dmt'])}}" class="text-white">
                        <div class="panel panel-body border-left-warning border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold dmtpendingamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Dmt Pending</span>
                                    <h6 class="no-margin dmtpending">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'dmt'])}}" class="text-white">
                        <div class="panel panel-body border-left-danger border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold dmtfailedamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Dmt Failed</span>
                                    <h6 class="no-margin dmtfailed">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'matm'])}}" class="text-white">
                        <div class="panel panel-body border-left-success border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i>
                                    <h5 class="media-heading text-semibold matmsuccessamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Matm Success</span>
                                    <h6 class="no-margin matmsuccess">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'matm'])}}" class="text-white">
                        <div class="panel panel-body border-left-warning border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i>
                                    <h5 class="media-heading text-semibold matmpendingamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Matm Pending</span>
                                    <h6 class="no-margin matmpending">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'matm'])}}" class="text-white">
                        <div class="panel panel-body border-left-danger border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i>
                                    <h5 class="media-heading text-semibold matmfailedamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Matm Failed</span>
                                    <h6 class="no-margin matmfailed">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'payout'])}}" class="text-white">
                        <div class="panel panel-body border-left-success border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold payoutsuccessamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Payout Success</span>
                                    <h6 class="no-margin payoutsuccess">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'payout'])}}" class="text-white">
                        <div class="panel panel-body border-left-warning border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold payoutpendingamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Payout Pending</span>
                                    <h6 class="no-margin payoutpending">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'payout'])}}" class="text-white">
                        <div class="panel panel-body border-left-danger border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i><h5 class="media-heading text-semibold payoutfailedamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Payout Failed</span>
                                    <h6 class="no-margin payoutfailed">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'matm'])}}" class="text-white">
                        <div class="panel panel-body border-left-success border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i>
                                    <h5 class="media-heading text-semibold pancardsuccessamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Pancard Success</span>
                                    <h6 class="no-margin pancardsuccess">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'pancard'])}}" class="text-white">
                        <div class="panel panel-body border-left-warning border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i>
                                    <h5 class="media-heading text-semibold pancardpendingamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Pancard Pending</span>
                                    <h6 class="no-margin pancardpending">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-2 col-xl-2">
                    <a href="{{route('reports', ['type' => 'pancard'])}}" class="text-white">
                        <div class="panel panel-body border-left-danger border-left-xlg cursor-pointer mb-5" style="padding: 10px 10px;">
                            <div class="media no-margin">
                                <div class="media-body">
                                    <i class="fa fa-inr pull-left" style="padding:5px 0px;"></i>
                                    <h5 class="media-heading text-semibold pancardfailedamt"></h5>
                                    <div class="list-group-divider"></div>
                                    <span>Pancard Failed</span>
                                    <h6 class="no-margin pancardfailed">0</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-body p-10 bg-indigo-600 no-margin">
                        <h6 class="no-margin-top">Commission</h6>
                        <div class="row text-center">
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <h5 class="text-bold no-margin rechargecommission">0</h5>
                                <span class="text-semibold">Recharge</span>
                            </div>

                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <h5 class="text-bold no-margin bbpscommission">0</h5>
                                <span class="text-semibold">Bbps</span>
                            </div>

                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <h5 class="text-bold no-margin billpaycommission">0</h5>
                                <span class="text-semibold">Billpay</span>
                            </div>

                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <h5 class="text-bold no-margin dmtcommission">0</h5>
                                <span class="text-semibold">Dmt</span>
                            </div>

                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <h5 class="text-bold no-margin iaepscommission">0</h5>
                                <span class="text-semibold">I-Aeps</span>
                            </div>

                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <h5 class="text-bold no-margin faepscommission">0</h5>
                                <span class="text-semibold">F-Aeps</span>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <h5 class="text-bold no-margin aadharpaycommission">0</h5>
                                <span class="text-semibold">Aadhar Pay</span>
                            </div>

                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <h5 class="text-bold no-margin payoutcommission">0</h5>
                                <span class="text-semibold">Payout</span>
                            </div>

                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <h5 class="text-bold no-margin matmcommission">0</h5>
                                <span class="text-semibold">Matm</span>
                            </div>

                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <h5 class="text-bold no-margin pancardcommission">0</h5>
                                <span class="text-semibold">Pancard</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-body p-10 bg-indigo-600 no-margin">
                        <h6 class="no-margin-top">User Onboard Data</h6>
                        <div class="row text-center">
                            <div class="col-md-3 col-xs-6">
                                <h5 class="text-bold no-margin onboardday">0</h5>
                                <span class="text-semibold">Today</span>
                            </div>

                            <div class="col-md-3 col-xs-6">
                                <h5 class="text-bold no-margin onboardsevenday">0</h5>
                                <span class="text-semibold">7 Days</span>
                            </div>

                            <div class="col-md-3 col-xs-6">
                                <h5 class="text-bold no-margin onboard30day">0</h5>
                                <span class="text-semibold">30 Days</span>
                            </div>

                            <div class="col-md-3 col-xs-6">
                                <h5 class="text-bold no-margin onboardall">0</h5>
                                <span class="text-semibold">Total</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (Myhelper::hasNotRole('admin'))
        @if (Auth::user()->kyc == "pending" || Auth::user()->kyc == "rejected")
            <div id="kycModal" class="modal fade" data-backdrop="false" data-keyboard="false">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-slate">
                            <h6 class="modal-title">Complete your profile with kyc</h6>
                        </div>
                        @if (Auth::user()->kyc == "rejected")
                            <div class="alert bg-danger alert-styled-left">
                                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                <span class="text-semibold">Kyc Rejected!</span> {{ Auth::user()->remark }}</a>.
                            </div>
                        @endif

                        <form id="kycForm" action="{{route('profileUpdate')}}" method="post" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" name="id" value="{{Auth::id()}}">
                                <input type="hidden" name="actiontype" value="kycdata">
                                <input type="hidden" name="ekyc" value="">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Address</label>
                                        <textarea name="address" class="form-control" rows="2" required="" placeholder="Enter Value">{{ Auth::user()->address}}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>State</label>
                                        <select name="state" class="form-control select" required="">
                                            <option value="">Select State</option>
                                            @foreach ($state as $state)
                                                <option value="{{$state->state}}" {{ (Auth::user()->state == $state->state)? 'selected=""': '' }}>{{$state->state}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>City</label>
                                        <input type="text" name="city" class="form-control" required="" placeholder="Enter Value" value="{{Auth::user()->city}}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Pincode</label>
                                        <input type="number" name="pincode" value="{{ Auth::user()->pincode}}" class="form-control" value="" required="" maxlength="6" minlength="6" placeholder="Enter Value">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Shop Name</label>
                                        <input type="text" name="shopname" value="{{ Auth::user()->shopname}}"  class="form-control" value="" required="" placeholder="Enter Value">
                                    </div>
        
                                    <div class="form-group col-md-4">
                                        <label>Pancard Number</label>
                                        <input type="text" name="pancard" value="{{ Auth::user()->pancard}}"  class="form-control" value="" required="" placeholder="Enter Value">
                                    </div>
        
                                    <div class="form-group col-md-4">
                                        <label>Adhaarcard Number</label>
                                        <input type="text" name="aadharcard" value="{{ Auth::user()->aadharcard}}"  class="form-control" value="" required="" placeholder="Enter Value" maxlength="12" minlength="12">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Pancard Pic</label>
                                        <input type="file" name="pancardpics" class="form-control" value="" placeholder="Enter Value" required="">
                                    </div>
        
                                    <div class="form-group col-md-4">
                                        <label>Adhaarcard Pic Front</label>
                                        <input type="file" name="aadharcardpicfronts" class="form-control" value="" placeholder="Enter Value" required="">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Adhaarcard Pic Back</label>
                                        <input type="file" name="aadharcardpicbacks" class="form-control" value="" placeholder="Enter Value" required="">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Self Photo</label>
                                        <input type="file" name="profiles" class="form-control" value="" placeholder="Enter Value" required="">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Bank Passbook/Cancelle Chaque</label>
                                        <input type="file" name="passbooks" class="form-control" value="" placeholder="Enter Value" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn bg-slate btn-raised legitRipple" type="button">Scan For Ekyc</button>
                                <button class="btn bg-slate btn-raised legitRipple" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Complete Profile</button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
        @endif

        @if (Auth::user()->resetpwd == "default")
            <div id="pwdModal" class="modal fade" data-backdrop="false" data-keyboard="false">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-slate">
                            <h6 class="modal-title">Change Password </h6>
                        </div>
                        <form id="passwordForm" action="{{route('profileUpdate')}}" method="post">
                            <div class="modal-body">
                                <input type="hidden" name="id" value="{{Auth::id()}}">
                                <input type="hidden" name="actiontype" value="password">
                                {{ csrf_field() }}
                                @if (Myhelper::can('password_reset'))
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Old Password</label>
                                            <input type="password" name="oldpassword" class="form-control" required="" placeholder="Enter Value">
                                        </div>
                                        <div class="form-group col-md-6  ">
                                            <label>New Password</label>
                                            <input type="password" name="password" id="password" class="form-control" required="" placeholder="Enter Value">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6  ">
                                            <label>Confirmed Password</label>
                                            <input type="password" name="password_confirmation" class="form-control" required="" placeholder="Enter Value">
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button class="btn bg-slate btn-raised legitRipple" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Change Password</button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
        @endif
    @endif

    <div id="noticeModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-slate">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Necessary Notice ( आवश्यक सूचना )</h4>
                </div>
                <div class="modal-body">
                    {!! nl2br($mydata['notice']) !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .dmt-img img{
            height: 250px;
        }

        .img-title{
            border :1px solid darkorchid;
        }

        .img-title img{
            width:30px;
        }

        .col{
            flex: 0 0 16.6666666667%;
            max-width: 16.6666666667%;
            border:1px solid red!important;
        }

        .card {
            margin-bottom: 1.875rem;
            background-color: #fff;
            transition: all .5s ease-in-out;
            position: relative;
            border: 0px solid transparent;
            border-radius: 5px;
            box-shadow: 0 2px 1px -1px #0003, 0 1px 1px #00000024, 0 1px 3px #0000001f;
            height: calc(100% - 30px);
            color: #000;
        }

        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 10px 0px;
        }

        .border-left-xlg {
            border-radius: 5px !important;
        }

        .wrapper-title{
            position: relative;
        }

        .box{
            opacity: 1;
            display: block;
            transition: .5s ease;
           backface-visibility: hidden;
        }

        .middle {
          transition: .5s ease;
          opacity: 0;
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          -ms-transform: translate(-50%, -50%);
          text-align: center;
        }

        .wrapper-title:hover .middle {
          opacity: 1;
        }

        .text {
          background-color: #04AA6D;
          color: white;
          font-size: 16px;
          padding: 16px 32px;
        }

        .box h6{
            font-size: 15px;
        }

        .margin-title{
            margin-top: 1rem;
        }



        </style>
@endpush

@push('script')
<script type="text/javascript" src="{{asset('')}}assets/js/plugins/forms/selects/select2.min.js"></script>
<script>
    $(document).ready(function(){
        $('select').select2();
        @if (Myhelper::hasNotRole('admin') && Auth::user()->resetpwd == "default")
            $('#pwdModal').modal();
        @endif

        @if ($mydata['notice'] != null && $mydata['notice'] != '')
            $('#noticeModal').modal();
        @endif

        $( "#passwordForm" ).validate({
            rules: {
                @if (!Myhelper::can('member_password_reset'))
                oldpassword: {
                    required: true,
                    minlength: 6,
                },
                password_confirmation: {
                    required: true,
                    minlength: 8,
                    equalTo : "#password"
                },
                @endif
                password: {
                    required: true,
                    minlength: 8
                }
            },
            messages: {
                @if (!Myhelper::can('member_password_reset'))
                oldpassword: {
                    required: "Please enter old password",
                    minlength: "Your password lenght should be atleast 6 character",
                },
                password_confirmation: {
                    required: "Please enter confirmed password",
                    minlength: "Your password lenght should be atleast 8 character",
                    equalTo : "New password and confirmed password should be equal"
                },
                @endif
                password: {
                    required: "Please enter new password",
                    minlength: "Your password lenght should be atleast 8 character"
                }
            },
            errorElement: "p",
            errorPlacement: function ( error, element ) {
                if ( element.prop("tagName").toLowerCase().toLowerCase() === "select" ) {
                    error.insertAfter( element.closest( ".form-group" ).find(".select2") );
                } else {
                    error.insertAfter( element );
                }
            },
            submitHandler: function () {
                var form = $('form#passwordForm');
                form.find('span.text-danger').remove();
                form.ajaxSubmit({
                    dataType:'json',
                    beforeSubmit:function(){
                        form.find('button:submit').button('loading');
                    },
                    complete: function () {
                        form.find('button:submit').button('reset');
                    },
                    success:function(data){
                        if(data.status == "success"){
                            form[0].reset();
                            form.closest('.modal').modal('hide');
                            notify("Password Successfully Changed" , 'success');
                        }else{
                            notify(data.status , 'warning');
                        }
                    },
                    error: function(errors) {
                        showError(errors, form.find('.modal-body'));
                    }
                });
            }
        });

        $('form#filterForm').submit(function(){
            $.ajax({
                url: "{{url('mystatics')}}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType:'json',
                data:{"fromdate" : $('form#filterForm').find("[name='fromdate']").val(), "todate" : $('form#filterForm').find("[name='todate']").val(), "userid" : $('form#filterForm').find("[name='userid']").val()},
                success: function(data){
                    $.each(data, function (index, value) {
                        $('.'+index).text(value);
                    });
                }
            });

            $.ajax({
                url: "{{url('mycommission')}}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType:'json',
                data:{"fromdate" : $('form#filterForm').find("[name='fromdate']").val(), "todate" : $('form#filterForm').find("[name='todate']").val(), "userid" : $('form#filterForm').find("[name='userid']").val()},
                success: function(data){
                    console.log(data);
                    $.each(data, function (index, value) {
                        $('.'+index).text(value);
                    });
                }
            });
            return false;
        });

        $.ajax({
            url: "{{url('mystatics')}}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType:'json',
            data:{"fromdate" : "{{date("Y-m-d")}}", "todate" : "{{date("Y-m-d")}}", "userid" : 0},
            success: function(data){
                console.log(data);
                $.each(data, function (index, value) {
                    $('.'+index).text(value);
                });
            }
        });

        $.ajax({
            url: "{{url('mycommission')}}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType:'json',
            data:{"fromdate" : "{{date("Y-m-d")}}", "todate" : "{{date("Y-m-d")}}", "userid" : 0},
            success: function(data){
                console.log(data);
                $.each(data, function (index, value) {
                    $('.'+index).text(value);
                });
            }
        });

        $.ajax({
            url: "{{url('useronboard')}}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType:'json',
            success: function(data){
                console.log(data);
                $.each(data, function (index, value) {
                    $('.'+index).text(value);
                });
            }
        });
    });
</script>
@endpush
