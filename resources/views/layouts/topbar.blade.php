@php
    $downlinebalance = \DB::table("users")->where("id", "!=", \Auth::id())->sum("mainwallet");
@endphp
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-header">
     @if (isset($mydata['company']) && $mydata['company']->logo)
    <a class="navbar-brand no-padding" href="{{ route('home') }}">
        <img src="{{ asset('public/' . $mydata['company']->logo) }}" class="img-responsive" alt="">
    </a>
@elseif(isset($mydata['company']))
    <a class="navbar-brand" href="{{ route('home') }}" style="padding: 17px">
        <span class="companyname" style="color: black">{{ $mydata['company']->companyname }}</span>
    </a>
@endif


        <ul class="nav navbar-nav visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile">
                <i class="fa fa-inr"></i> : <span class="mainwallet">{{Auth::user()->mainwallet}}</span>
            </a></li>
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav">
            @if (Myhelper::hasRole('admin'))
            <li><a href="javascript:void(0)" style="padding: 13px"><button type="button" class="btn bg-slate btn-labeled btn-xs legitRipple" data-toggle="modal" data-target="#walletLoadModal"><b><i class="icon-wallet"></i></b> Load Wallet</button></a></li>
            @endif
            <div class="clearfix"></div>
        </ul>

        <div class="navbar-right">
            @if(Myhelper::hasRole("admin"))
                <p class="navbar-text"><i class="icon-wallet"></i> Downline : <span class="downlinebalance"></span> /-</p>
            @endif
            <p class="navbar-text"><i class="icon-wallet"></i> Utility Wallet : <span class="mainwallet">{{Auth::user()->mainwallet}}</span> /-</p>
            <p class="navbar-text"><i class="icon-wallet"></i> Aeps Wallet : <span class="aepswallet">{{Auth::user()->aepswallet}}</span> /-</p>
            <ul class="nav navbar-nav">
                <li class="dropdown dropdown-user">
                    <a class="dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false">
                        <img src="{{asset('')}}public/profiles/user.png" alt="">
                        <span>{{ explode(' ',ucwords(Auth::user()->name))[0] }} (AID - {{Auth::id()}})</span>
                        <i class="caret"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right">
                        <li class="dropdown-header">
                            Agent Id : {{ Auth::id()}}
                        </li>
                        <li class="dropdown-header">
                            Name : {{ Auth::user()->name}}
                        </li>
                        <li class="dropdown-header">
                            Member : {{Auth::user()->role->name}}
                        </li>
                        <li class="divider"></li>
                        <li><a href="{{route('profile')}}"><i class="icon-user-plus"></i> <span>My profile</span></a></li>
                        @if (Myhelper::hasNotRole('admin') && Myhelper::can('view_commission'))
                            <li><a href="{{route('resource', ['type' => 'commission'])}}"><i class="icon-coins"></i> <span>My Commission</span></a></li>
                        @endif
                        <li><a href="{{route('logout')}}"><i class="icon-switch2"></i> <span>Logout</span></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

@if(session("role") != "admin")
    {{-- @include('layouts.sidebar') --}}
@endif