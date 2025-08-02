
@php
    $links = \App\Model\Link::get();
@endphp

<div class="navbar navbar-default no-padding-bottom no-padding-top" id="navbar-second">
    <ul class="nav navbar-nav no-border visible-xs-block">
        <li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="icon-menu7 position-left"></i></a></li>
    </ul>

    <div class="navbar-collapse collapse" id="navbar-second-toggle">
        <ul class="nav navbar-nav navbar-nav-material">
            <li><a class="legitRipple" href="{{route('home')}}"><i class="icon-home4 position-left"></i> <span>Dashboard</span></a></li>

            @if(Myhelper::hasNotRole('admin'))
                <li class="dropdown mega-menu mega-menu-wide">
                    <a href="#" class="dropdown-toggle legitRipple" data-toggle="dropdown"><i class="icon-cart position-left"></i> Our Services <span class="caret"></span></a>

                    <div class="dropdown-menu dropdown-content">
                        <div class="dropdown-content-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <span class="menu-heading underlined">Banking</span>
                                    <ul class="menu-list">
                                        @if (Myhelper::can(['dmt1_service']))
                                            <li><a href="{{route('dmt1')}}"><i class="icon-arrow-right5"></i> Money Transfer</a></li>
                                        @endif

                                        @if (Myhelper::can('aeps1_service'))
                                            <li><a href="{{route('aeps')}}"><i class="icon-arrow-right5"></i> Aeps</a></li>
                                            <li><a href="{{route('aadharpay')}}"><i class="icon-arrow-right5"></i> Aadhar Pay</a></li>
                                        @endif
                                    </ul>
                                </div>

                                <div class="col-md-2">
                                    <span class="menu-heading underlined">Recharge</span>
                                    <ul class="menu-list">
                                        @if (Myhelper::can(['recharge_service']))
                                            <li><a href="{{route('recharge', ["type" => "mobile"])}}"><i class="icon-arrow-right5"></i> Mobile Recharge</a></li>
                                            <li><a href="{{route('recharge', ["type" => "dth"])}}"><i class="icon-arrow-right5"></i> Dth Recharge</a></li>
                                        @endif
                                        
                                        @if (Myhelper::can(['billpay_service']))
                                            <li><a href="{{route('recharge', ["type" => "fasttag"])}}"><i class="icon-arrow-right5"></i> FastTag</a></li>
                                            <li><a href="{{route('recharge', ["type" => "cabletv"])}}"><i class="icon-arrow-right5"></i> Cable Tv</a></li>
                                        @endif
                                    </ul>
                                </div>

                                <div class="col-md-2">
                                    <span class="menu-heading underlined">Bill Payment</span>
                                    <ul class="menu-list">
                                        @if (Myhelper::can(['billpay_service']))
                                            <li><a href="{{route('bill', ["type" => "electricity"])}}"><i class="icon-arrow-right5"></i> Electricity</a></li>
                                            <li><a href="{{route('bill', ["type" => "water"])}}"><i class="icon-arrow-right5"></i> Water</a></li>
                                            <li><a href="{{route('bill', ["type" => "lpggas"])}}"><i class="icon-arrow-right5"></i> Lpg Gas</a></li>
                                            <li><a href="{{route('bill', ["type" => "gasutility"])}}"><i class="icon-arrow-right5"></i> Gas Cylinder</a></li>
                                            <li><a href="{{route('bill', ["type" => "postpaid"])}}"><i class="icon-arrow-right5"></i> Postpaid</a></li>
                                            <li><a href="{{route('bill', ["type" => "broadband"])}}"><i class="icon-arrow-right5"></i> Broadband</a></li>
                                        @endif
                                    </ul>
                                </div>

                                <div class="col-md-2">
                                    <span class="menu-heading underlined">Insurace & Tax</span>
                                    <ul class="menu-list">
                                        @if (Myhelper::can(['billpay_service']))
                                            <li><a href="{{route('bill', ["type" => "muncipal"])}}"><i class="icon-arrow-right5"></i> Muncipal</a></li>
                                            <li><a href="{{route('bill', ["type" => "tax"])}}"><i class="icon-arrow-right5"></i> Tax</a></li>
                                            <li><a href="{{route('bill', ["type" => "lifeinsurance"])}}"><i class="icon-arrow-right5"></i> Life Insurance</a></li>
                                            <li><a href="{{route('bill', ["type" => "insurance"])}}"><i class="icon-arrow-right5"></i> Insurance</a></li>
                                        @endif
                                    </ul>
                                </div>

                                <div class="col-md-2">
                                    <span class="menu-heading underlined">E-Govt Service</span>
                                    <ul class="menu-list">
                                        @if (Myhelper::can(['billpay_service']))
                                            <li><a href="{{route('pancard' , ['type' => 'uti'])}}"><i class="icon-arrow-right5"></i> Uti Pancard</a></li>
                                            <li><a href="{{route('pancard' , ['type' => 'nsdl'])}}"><i class="icon-arrow-right5"></i> Nsdl Pancard</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle legitRipple" data-toggle="dropdown">
                        <i class="icon-sphere position-left"></i> Quick Links <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu width-200">
                        @if(sizeof($links) > 0)
                            @foreach($links as $link)
                                <li><a href="{{$link->value}}" target="_blank"><i class="icon-arrow-right5"></i> {{$link->name}}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </li>
            @endif

            @if (Myhelper::can(['change_company_profile']))
                <li class="dropdown">
                    <a class="dropdown-toggle legitRipple" data-toggle="dropdown" href="#"><i class="icon-wrench position-left"></i> <span>Resources</span></a>
                    <ul class="dropdown-menu width-250">
                        @if (Myhelper::can('change_company_profile'))
                            <li><a class="legitRipple" href="{{route('resource', ['type' => 'companyprofile'])}}">Company Profile</a></li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (Myhelper::can(['view_md', 'view_distributor', 'view_retailer']))
                <li class="dropdown">
                    <a class="dropdown-toggle legitRipple" data-toggle="dropdown" href="#"><i class="icon-user position-left"></i> <span>Member</span>
                        <span class="label bg-danger {{Myhelper::hasRole('admin') ? '' : 'hide'}} member">0</span></a>
                    <ul class="dropdown-menu width-250">
                        @if (Myhelper::can(['view_md']))
                            <li><a class="legitRipple" href="{{route('member', ['type' => 'md'])}}">Master Distributor</a></li>
                        @endif

                        @if (Myhelper::can(['view_distributor']))
                            <li><a class="legitRipple" href="{{route('member', ['type' => 'distributor'])}}">Distributor</a></li>
                        @endif

                        @if (Myhelper::can(['view_retailer']))
                            <li><a class="legitRipple" href="{{route('member', ['type' => 'retailer'])}}">Retailer</a></li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (Myhelper::can(['fund_transfer', 'fund_return', 'fund_report', 'fund_request', 'aeps_fund_request']))
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle legitRipple" data-toggle="dropdown"><i class="icon-wallet position-left"></i>Wallet Fund Management<span class="caret"></span></a>

                    <ul class="dropdown-menu width-250">
                        <li class="dropdown-header">Mainwallet Management</li>  
                        @if (Myhelper::can(['fund_transfer', 'fund_return']))
                        <li><a class="legitRipple" href="{{route('fund', ['type' => 'tr'])}}"><i class="icon-arrow-right5"></i> Transfer/Return</a></li>
                        @endif

                        @if (Myhelper::hasNotRole('admin') && Myhelper::can('fund_request'))
                        <li><a class="legitRipple" href="{{route('fund', ['type' => 'request'])}}"><i class="icon-arrow-right5"></i> Load Wallet</a></li>
                        @endif

                        @if (Myhelper::can(['fund_report']))
                        <li><a class="legitRipple" href="{{route('fund', ['type' => 'requestviewall'])}}"><i class="icon-arrow-right5"></i> Request Report</a></li>
                        @endif

                        <li class="dropdown-header">Aepswallet Management</li>    
                        @if (Myhelper::can('aeps_fund_request') && Myhelper::hasNotRole("admin"))
                            <li><a class="legitRipple" href="{{route('fund', ['type' => 'aeps'])}}"><i class="icon-arrow-right5"></i> Payout Request</a></li>
                        @endif
                    </ul>
                </li>
            @endif

            <li class="dropdown">
                <a class="dropdown-toggle legitRipple" data-toggle="dropdown" href="#">
                    <i class="icon-history position-left"></i> <span>Transaction History</span> <span class="caret"></span>
                </a>

                <ul class="dropdown-menu width-250">
                    <li><a class="legitRipple" href="{{route('reports', ['type' => 'aeps'])}}">Aeps</a></li>
                    <li><a class="legitRipple" href="{{route('reports', ['type' => 'aadharpay'])}}">Aadhar Pay</a></li>
                    <li><a class="legitRipple" href="{{route('reports', ['type' => 'ministatement'])}}">Mini Statement</a></li>
                    <li><a class="legitRipple" href="{{route('reports', ['type' => '2fa'])}}">2fa Charge</a></li>
                    <li><a class="legitRipple" href="{{route('reports', ['type' => 'billpay'])}}">Bill Payment</a></li>
                    <li><a class="legitRipple" href="{{route('reports', ['type' => 'matm'])}}">MicroAtm</a></li>
                    <li><a class="legitRipple" href="{{route('reports', ['type' => 'dmt'])}}">Money Transfer</a></li>
                    <li><a class="legitRipple" href="{{route('reports', ['type' => 'recharge'])}}">Recharge</a></li>
                    <li><a class="legitRipple" href="{{route('reports', ['type' => 'pancard'])}}">Pancard</a></li>
                    <li>
                        <a class="legitRipple" href="{{route('reports', ['type' => 'nps'])}}">National Pension
                            <span class="label bg-slate-800 pancard {{Myhelper::hasRole('admin')?'' : 'hide'}}">0</span>
                        </a>
                    </li>
                    <li>
                        <a class="legitRipple" href="{{route('reports', ['type' => 'nsdlaccount'])}}">Nsdl Account
                            <span class="label bg-slate-800 pancard {{Myhelper::hasRole('admin')?'' : 'hide'}}">0</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="dropdown">
                <a class="dropdown-toggle legitRipple" data-toggle="dropdown" href="#">
                    <i class="icon-menu6 position-left"></i> <span>Ledger</span>
                </a>
                <ul class="dropdown-menu width-250">
                    <li><a class="legitRipple" href="{{route('reports', ['type' => 'ledger'])}}">Main Wallet</a></li>
                    <li><a class="legitRipple" href="{{route('reports', ['type' => 'aepsledger'])}}">Aeps Wallet</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
