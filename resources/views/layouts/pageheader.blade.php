
@if (!isset($header))
    <div class="page-header page-header-default mb-10">
        <div class="page-header-content">
            <div class="page-title">
                <div class="row">
                    <h5 class="col-md-3"><span class="text-semibold">Home</span> - <span class="servicename">@yield('pagetitle')</span></h5>
                    @if ($mydata['news'] != '' && $mydata['news'] != null)
                    <h5 class="col-md-9 text-danger"><marquee style="height: 25px" onmouseover="this.stop();" onmouseout="this.start();">{{$mydata['news']}}</marquee></h5>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

@if (!isset($search))
<div class="content p-b-0">
    <form id="searchForm">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Search</h4>
                <div class="heading-elements">
                    <button type="button" class="btn btn-primary btn-xs btn-labeled legitRipple {{ isset($export) ? '' : 'hide' }}" product="{{ $export ?? '' }}" id="reportExport"><b><i class="icon-cloud-download2"></i></b> Export</button></div>
            </div>
            <div class="panel-body p-tb-10">
                @if(isset($mystatus))
                    <input type="hidden" name="status" value="{{$mystatus}}">
                @endif
                <div class="row">
                    <div class="col-md-10">
                        <div class="row">
                            <div class="form-group col-md-3 m-b-10">
                                <input type="text" name="from_date" id="dateField" autocomplete="off" class="form-control mydate" placeholder="From Date">
                            </div>
                            <div class="form-group col-md-3 m-b-10">
                                <input type="text" name="to_date" autocomplete="off" class="form-control mydate" placeholder="To Date">
                            </div>
                            <div class="form-group col-md-3 m-b-10">
                                <input type="text" name="searchtext" class="form-control" placeholder="Search Value">
                            </div>

                            @if (Myhelper::hasNotRole(['retailer', 'apiuser']))
                                <div class="form-group col-md-3 m-b-10 {{ isset($agentfilter) ? $agentfilter : ''}}">
                                    <input type="text" name="agent" class="form-control" placeholder="Agent Id / Parent Id">
                                </div>
                            @endif

                            @if(isset($status))
                                <div class="form-group col-md-3">
                                    <select name="status" class="form-control select">
                                        <option value="">Select {{$status['type'] ?? ''}} Status</option>
                                        @if (isset($status['data']) && sizeOf($status['data']) > 0)
                                            @foreach ($status['data'] as $key => $value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            @endif
                            
                            @if(Myhelper::hasRole('admin') && (isset($type) && ($type == "directpancard")))
                                <a onclick="window.location='https://rajpay.in/pancardStatus'" class="btn bg-slate btn-labeled legitRipple mt-10" 
                                   data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Checking" 
                                   style="margin-right: 15px;">
                                    <b><i class="icon-search4"></i></b> Check Status
                                </a>
                                <button type="button" onclick="window.location='https://rajpay.in/pancardClear?data=all'" class="btn btn-warning btn-labeled legitRipple mt-10" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Refund all">
                                        <b><i class="icon-rotate-ccw3"></i></b> Refund All
                                </button>
                            @elseif(Myhelper::hasRole('admin') && (isset($type) && ($type == "nsdlaccount")))
                                <button type="button" id="refundButton" onclick="linkmaker()" class="btn btn-warning btn-labeled legitRipple mt-10" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Refund all">
                                        <b><i class="icon-rotate-ccw3"></i></b> Refund All
                                </button>
                                
                                <!--<button type="button" id="refundButton" class="btn btn-warning btn-labeled legitRipple mt-10" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b>"><b><i class="icon-rotate-ccw3"></i></b>Refund All</button>-->
                            @endif


                            @if(isset($product))
                                <div class="form-group col-md-3">
                                    <select name="product" class="form-control select">
                                        <option value="">Select {{$product['type'] ?? ''}}</option>
                                        @if (isset($product['data']) && sizeOf($product['data']) > 0)
                                            @foreach ($product['data'] as $key => $value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-2 mt-5">
                        <button type="submit" class="btn bg-slate btn-labeled legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Searching"><b><i class="icon-search4"></i></b> Search</button>
                        <button type="button" class="btn btn-warning btn-labeled legitRipple mt-10" id="formReset" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Refreshing"><b><i class="icon-rotate-ccw3"></i></b> Refresh</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

    <script>
        // $(document).ready(function() {
        //     $('#refundButton').on('click', function() {
        //         // Get the input value
        //         console.log("Button clicked");
        //         var fromdate = $('#dateField').val();
                
        //         // Construct the dynamic URL
        //         var baseUrl = 'https://rajpay.in/pancardClear'; // Replace with your base URL
        //         var dynamicUrl = baseUrl + '?data=allnsdl&date=' + encodeURIComponent(fromdate);
        //         console.log(dynamicUrl);
        //         // Open the URL in a new tab
        //         window.open(dynamicUrl, '_blank');
        //     });
        // });
    </script>
@endif