<!-- Main sidebar -->
<div class="sidebar sidebar-main sidebar-default sidebar-fixed">
    <div class="sidebar-content" id="my-scrollbar">
        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">
                    <li><a class="legitRipple" href="{{route('home')}}"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                    <li><a class="legitRipple" href="{{route('pending_approvals_list')}}"><i class="icon-home4"></i> <span>Emitra Pending approvals</span></a></li>
                 @if(Myhelper::hasRole("admin"))
    <li>
        <a class="legitRipple" href="{{ route('service.register') }}">
            <i class="fas fa-user-plus"></i> <span>Agent Registration</span>
        </a>
    </li>
@endif

@if(Myhelper::hasRole("admin"))
    <li>
        <a class="legitRipple" href="{{ route('service.balance-check-page') }}">
            <i class="fas fa-wallet"></i> <span>Check Balance</span>
        </a>
    </li>
@endif

                    @if (Myhelper::hasNotRole("admin"))
                        <li class="navigation-header  no-margin p-10"><span>Portal Services</span> <i class="icon-menu" title="" data-original-title="Forms"></i></li>
                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="fa fa-inr"style="font-size: 17px;padding: 2px 4px;"></i> <span>Banking Service</span></a>
                            <ul>
                                @if (Myhelper::can('aeps1_service'))
                                    <li><a href="{{route('aeps')}}">Aeps</a></li>
                                    <li><a href="{{route('aadharpay')}}">Aadhar Pay</a></li>
                                @endif

                                @if (Myhelper::can(['dmt1_service']))
                                    <li><a href="{{route('dmt1')}}">Money Transfer</a></li>
                                @endif
                            </ul>
                        </li>

                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="fa fa-bolt"style="font-size: 17px;padding: 2px 4px;"></i> <span>Recharge Service</span></a>
                            <ul>
                                @if (Myhelper::can(['recharge_service']))
                                    <li><a href="{{route('recharge', ["type" => "mobile"])}}">Mobile Recharge</a></li>
                                    <li><a href="{{route('recharge', ["type" => "dth"])}}">Dth Recharge</a></li>
                                @endif
                                
                                @if (Myhelper::can(['billpay_service']))
                                    <li><a href="{{route('recharge', ["type" => "fasttag"])}}">FastTag Recharge</a></li>
                                    <li><a href="{{route('recharge', ["type" => "cabletv"])}}">Cable Tv Recharge</a></li>
                                @endif
                            </ul>
                        </li>

                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="fa fa-bolt"style="font-size: 17px;padding: 2px 4px;"></i> <span>Billpay Service</span></a>
                            <ul>
                                @if (Myhelper::can(['billpay_service']))
                                    <li><a href="{{route('bill', ["type" => "electricity"])}}">Electricity</a></li>
                                    <li><a href="{{route('bill', ["type" => "water"])}}">Water</a></li>
                                    <li><a href="{{route('bill', ["type" => "lpggas"])}}">Lpg Gas</a></li>
                                    <li><a href="{{route('bill', ["type" => "gasutility"])}}">Gas Cylinder</a></li>
                                    <li><a href="{{route('bill', ["type" => "postpaid"])}}">Postpaid</a></li>
                                    <li><a href="{{route('bill', ["type" => "broadband"])}}">Broadband</a></li>
                                @endif
                            </ul>
                        </li>

                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="fa fa-bolt"style="font-size: 17px;padding: 2px 4px;"></i> <span>Insurace & Tax Service</span></a>
                            <ul>
                                @if (Myhelper::can(['billpay_service']))
                                    <li><a href="{{route('bill', ["type" => "muncipal"])}}">Muncipal</a></li>
                                    <li><a href="{{route('bill', ["type" => "tax"])}}">Tax</a></li>
                                    <li><a href="{{route('bill', ["type" => "lifeinsurance"])}}">Life Insurance</a></li>
                                    <li><a href="{{route('bill', ["type" => "insurance"])}}">Insurance</a></li>
                                @endif
                            </ul>
                        </li>

                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="fa fa-bolt"style="font-size: 17px;padding: 2px 4px;"></i> <span>E-Govt Service</span></a>
                            <ul>
                                @if (Myhelper::can(['billpay_service']))
                                    <li><a href="{{route('pancard' , ['type' => 'nsdl'])}}">Nsdl Pancard</a></li>
                                    <li><a href="{{route('account' , ['type' => 'nps'])}}">National Pension System</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (Myhelper::can(['company_manager', 'change_company_profile']))
                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="icon-wrench"></i> <span>Resources</span></a>
                            <ul>
                                @if (Myhelper::hasRole('admin'))
                                    <li><a class="legitRipple" href="{{route('resource', ['type' => 'scheme'])}}">Scheme Manager</a></li>
                                    <li><a class="legitRipple" href="{{route('resource', ['type' => 'company'])}}">Company Manager</a></li>
                                @endif

                                @if (Myhelper::can('change_company_profile'))
                                    <li><a class="legitRipple" href="{{route('resource', ['type' => 'companyprofile'])}}">Company Profile</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if (Myhelper::can(['company_manager', 'change_company_profile']))
                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="icon-wrench"></i> <span>PAN UAT</span></a>
                            <ul>
                                @if (Myhelper::hasRole('admin'))
                                    <li><a class="legitRipple" href="{{route('pancard.service')}}">PAN UAT</a></li>
                                    <li><a class="legitRipple" href="{{route('uat_logs_page')}}">PAN UAT Logs</a></li>
                                    <!--<li><a class="legitRipple" href="{{route('resource', ['type' => 'company'])}}">Company Manager</a></li>-->
                                @endif

                              
                            </ul>
                        </li>
                    @endif

                    @if (Myhelper::can(['view_whitelable', 'view_md', 'view_distributor', 'view_retailer', 'view_other','view_web', 'view_apiuser']))
                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="icon-user"></i> <span>Member</span>
                                <span class="label bg-warning {{Myhelper::hasRole('admin') ? '' : 'hide'}} member">0</span></a>
                            <ul>
                                @if (Myhelper::can(['view_whitelable']))
                                    <li><a class="legitRipple" href="{{route('member', ['type' => 'whitelable'])}}">Whitelable</a></li>
                                @endif

                                @if (Myhelper::can(['view_md']))
                                    <li><a class="legitRipple" href="{{route('member', ['type' => 'md'])}}">Master Distributor</a></li>
                                @endif

                                @if (Myhelper::can(['view_distributor']))
                                    <li><a class="legitRipple" href="{{route('member', ['type' => 'distributor'])}}">Distributor</a></li>
                                @endif

                                @if (Myhelper::can(['view_retailer']))
                                    <li><a class="legitRipple" href="{{route('member', ['type' => 'retailer'])}}">Retailer</a></li>
                                @endif

                                @if (Myhelper::can(['view_web']))
                                    <li><a class="legitRipple" href="{{route('member', ['type' => 'web'])}}">Registered User</a></li>
                                @endif

                                @if (Myhelper::can(['view_other']))
                                    <li><a class="legitRipple" href="{{route('member', ['type' => 'other'])}}">Employee</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (Myhelper::can(['view_kycpending', 'view_kycsubmitted', 'view_kycrejected']))
                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="icon-user"></i> <span>Kyc Manager</span> 
                                <span class="label bg-warning {{Myhelper::hasRole('admin') ? '' : 'hide'}} kycuser">0</span>
                            </a>
                            <ul>
                                @if (Myhelper::can(['view_kycpending']))
                                <li><a class="legitRipple" href="{{route('member', ['type' => 'kycpending'])}}">Pending Kyc 
                                    <span class="label bg-warning {{Myhelper::hasRole('admin') ? '' : 'hide'}} kycpending">0</span></a>
                                </li>
                                @endif

                                @if (Myhelper::can(['view_kycsubmitted']))
                                <li><a class="legitRipple" href="{{route('member', ['type' => 'kycsubmitted'])}}">Submitted Kyc 
                                    <span class="label bg-warning {{Myhelper::hasRole('admin') ? '' : 'hide'}} kycsubmitted">0</span></a>
                                </li>
                                @endif

                                @if (Myhelper::can(['view_kycrejected']))
                                <li><a class="legitRipple" href="{{route('member', ['type' => 'kycrejected'])}}">Rejected Kyc 
                                    <span class="label bg-warning {{Myhelper::hasRole('admin') ? '' : 'hide'}} kycrejected">0</span></a>
                                </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (Myhelper::can(['utiid_statement', 'aepsid_statement']))
                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="icon-user"></i> <span>Outlet List</span>
                                <span class="label bg-warning {{Myhelper::hasRole('admin') ? '' : 'hide'}} utiids">0</span></a>
                            <ul>
                                @if (Myhelper::can('aepsid_statement'))
                                    <li><a class="legitRipple" href="{{route('statement', ['type' => 'faepsid'])}}">Fing Outlet</a></li>
                                    <li><a class="legitRipple" href="{{route('statement', ['type' => 'bbpsid'])}}">BBPS Outlet</a></li>
                                @endif
                                
                                @if (Myhelper::can('utiid_statement'))
                                    <li><a class="legitRipple" href="{{route('statement', ['type' => 'utiid'])}}">Uti Agent</a></li>
                                    <li><a class="legitRipple" href="{{route('statement', ['type' => 'nsdlid'])}}">Nsdl Agent</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    <li class="navigation-header  no-margin p-10"><span>Portal Reporting</span> <i class="icon-menu" title="" data-original-title="Forms"></i></li>
                    @if (Myhelper::can(['fund_transfer', 'fund_return', 'fund_request_view', 'fund_report', 'fund_request']))
                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="icon-wallet"></i> <span>Fund Manager</span>
                            <span class="label bg-warning fundCount {{Myhelper::hasRole('admin')?'' : 'hide'}}">0</span></a>
                            <ul>
                                @if (Myhelper::can(['fund_transfer', 'fund_return']))
                                <li><a class="legitRipple" href="{{route('fund', ['type' => 'tr'])}}">Transfer/Return</a></li>
                                @endif

                                @if (Myhelper::can(['fund_requestview']))
                                <li><a class="legitRipple" href="{{route('fund', ['type' => 'requestview'])}}">Request 
                                    <span class="label bg-slate-800 fundCount {{Myhelper::hasRole('admin')?'' : 'hide'}}">0</span></a>
                                </li>
                                @endif

                                @if (Myhelper::hasNotRole('admin') && Myhelper::can('fund_request'))
                                <li><a class="legitRipple" href="{{route('fund', ['type' => 'request'])}}">Load Wallet</a></li>
                                @endif

                                @if (Myhelper::can(['fund_report']))
                                <li><a class="legitRipple" href="{{route('fund', ['type' => 'requestviewall'])}}">Request Report</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (Myhelper::can(['aeps_fund_request', 'payout_statement']))
                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="icon-wallet"></i> <span>Payout Manager</span>
                            <span class="label bg-warning payoutrequest {{Myhelper::hasRole('admin')?'' : 'hide'}}">0</span></a>
                            <ul>
                                @if (Myhelper::can('aeps_fund_request') && Myhelper::hasNotRole("admin"))
                                    <li>
                                        <a class="legitRipple" href="{{route('fund', ['type' => 'aeps'])}}">Payout Request</a>
                                    </li>
                                @endif

                                @if (Myhelper::can(['payout_statement']))
                                    <li><a class="legitRipple" href="{{route('reports', ['type' => 'payout'])}}">Payout Report
                                    <span class="label bg-slate-800 payoutrequest {{Myhelper::hasRole('admin')?'' : 'hide'}}">0</span></a></li>
                                @endif
                                
                                @if (Myhelper::can('payout_banks'))
                                    <li><a class="legitRipple" href="{{route('fund', ['type' => 'payoutbank'])}}">Payout Banks</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    <li>
                        <a class="legitRipple" href="javascript:void(0)"><i class="icon-history"></i> <span>Transaction History</span>
                            <span class="label bg-warning transactionCount {{Myhelper::hasRole('admin')?'' : 'hide'}}">0</span></a>
                        <ul>
                            <li><a class="legitRipple" href="{{route('reports', ['type' => 'aeps'])}}">Aeps</a></li>
                            <li><a class="legitRipple" href="{{route('reports', ['type' => 'aadharpay'])}}">Aadhar Pay</a></li>
                            <li><a class="legitRipple" href="{{route('reports', ['type' => 'ministatement'])}}">Mini Statement</a></li>
                            <li><a class="legitRipple" href="{{route('reports', ['type' => 'billpay'])}}">Bill Payment</a></li>
                            <li><a class="legitRipple" href="{{route('reports', ['type' => 'matm'])}}">MicroAtm</a></li>
                            <li><a class="legitRipple" href="{{route('reports', ['type' => 'dmt'])}}">Money Transfer</a></li>
                            <li><a class="legitRipple" href="{{route('reports', ['type' => 'recharge'])}}">Recharge</a></li>
                            <li>
                                <a class="legitRipple" href="{{route('reports', ['type' => 'pancard'])}}">Pancard
                                    <span class="label bg-slate-800 pancard {{Myhelper::hasRole('admin')?'' : 'hide'}}">0</span>
                                </a>
                            </li>

                            <li>
                                <a class="legitRipple" href="{{route('reports', ['type' => 'directpancard'])}}">Direct Pancard
                                    <span class="label bg-slate-800 directpancard {{Myhelper::hasRole('admin')?'' : 'hide'}}">0</span>
                                </a>
                            </li>
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

                    <li>
                        <a class="legitRipple" href="javascript:void(0)"><i class="icon-menu6"></i> <span>Account Ledger</span></a>
                        <ul>
                            <li><a class="legitRipple" href="{{route('reports', ['type' => 'ledger'])}}">Main Wallet</a></li>
                            <li><a class="legitRipple" href="{{route('reports', ['type' => 'aepsledger'])}}">Aeps Wallet</a></li>
                        </ul>
                    </li>

                    @if (Myhelper::can(['complaint']))
                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="icon-alarm"></i> <span>Pending Approvals</span>
                                <span class="label bg-warning pendingApprovals {{Myhelper::hasRole('admin')?'' : 'hide'}}">0</span></a>
                            <ul>
                                @if (Myhelper::can('complaint'))
                                    <li><a class="legitRipple" href="{{route('complaint')}}">Complaints<span class="label bg-slate-800 complaint {{Myhelper::hasRole('admin')?'' : 'hide'}}">0</span></a></li>
                                @endif

                                @if (Myhelper::can('setup_payout_bank'))
                                    <li><a class="legitRipple" href="{{route('setup', ['type' => "payoutbank"])}}">Payout Bank<span class="label bg-slate-800 payoutbank {{Myhelper::hasRole('admin')?'' : 'hide'}}">0</span></a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    
                    @if (Myhelper::can(['setup_bank', 'api_manager', 'setup_operator']))
                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="icon-cog3"></i> <span>Admin Setting</span></a>
                            <ul>
                                @if (Myhelper::can('api_manager'))
                                <li><a class="legitRipple" href="{{route('setup', ['type' => 'api'])}}">Api Manager</a></li>
                                @endif
                                @if (Myhelper::can('setup_bank'))
                                <li><a class="legitRipple" href="{{route('setup', ['type' => 'bank'])}}">Bank Account</a></li>
                                @endif
                                @if (Myhelper::can('complaint_subject'))
                                <li><a class="legitRipple" href="{{route('setup', ['type' => 'complaintsub'])}}">Complaint Subject</a></li>
                                @endif
                                @if (Myhelper::can('setup_operator'))
                                <li><a class="legitRipple" href="{{route('setup', ['type' => 'operator'])}}">Operator Manager</a></li>
                                @endif
                                @if (Myhelper::hasRole('admin'))
                                <li><a class="legitRipple" href="{{route('setup', ['type' => 'portalsetting'])}}">Portal Setting</a></li>
                                <li><a class="legitRipple" href="{{route('setup', ['type' => 'links'])}}">Quick Links</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    <li>
                        <a class="legitRipple" href="{{route('profile')}}"><i class="icon-user"></i> <span>My Profile</span></a>
                    </li>

                    @if (Myhelper::hasNotRole("admin"))
                        <li><a class="legitRipple" href="{{route('complaint')}}"><i class="icon-cog3"></i> <span>My Complaints</span></a></li>
                    @endif
                    
                    @if (Myhelper::hasRole('apiuser'))
                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="icon-cog2"></i> <span>Api Settings</span></a>
                            <ul>
                                <li><a class="legitRipple" href="{{route('apisetup', ['type' => 'setting'])}}">Callback & Token</a></li>
                                <li><a class="legitRipple" href="{{route('apisetup', ['type' => 'operator'])}}">Operator Code</a></li>
                                 <li><a class="legitRipple" href="{{route('apisetup', ['type' => 'document'])}}">Api Documents</a></li> 
                            </ul>
                        </li>
                    @endif

                    @if (Myhelper::can("check_logs"))
                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="icon-cog2"></i> <span>Log Manager</span></a>
                            <ul>
                                <li><a class="legitRipple" href="{{route('portallogs', ['type' => 'api'])}}">Api Logs</a></li>
                                <li><a class="legitRipple" href="{{route('portallogs', ['type' => 'login'])}}">Login Session</a></li>
                            </ul>
                        </li>
                    @endif

                    @if (Myhelper::hasRole('admin'))
                        <li>
                            <a class="legitRipple" href="javascript:void(0)"><i class="icon-lock"></i> <span>Role Manager</span></a>
                            <ul>
                                <li><a class="legitRipple" href="{{route('tools' , ['type' => 'roles'])}}">Roles</a></li>
                                <li><a class="legitRipple" href="{{route('tools' , ['type' => 'permissions'])}}">Permission</a></li>
                            </ul>
                        </li>
                    @endif

                    <li>
                        <a class="legitRipple" href="{{route('logout')}}"><i class="icon-switch2"></i> <span>Logout</span></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
