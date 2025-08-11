@extends('layouts.offline')
@section('title', $KIOSKCODEJ)
@section('name', $KIOSKNAMEJ)

@section('content')
<div class="content">
    <div class="tabbable">

        <ul class="nav nav-tabs bg-teal-600 nav-tabs-component no-margin-bottom ">
            <div style="display:flex;justify-content:space-between;padding:15px 5px; color:aliceblue;">
                <div style="display:flex; gap:15px;">
                    <li><a href="#seller-registration" data-toggle="tab" id="sellerTab" class="legitRipple active" aria-expanded="true" style="font-size: 15px;color:aliceblue;"><i class="icon-plus22"></i> Seller Registration</a></li>

                    <li><a style="font-size:15px;color:aliceblue;" href="#report" data-toggle="tab" id="reportTab" class="legitRipple" aria-expanded="false"><i class="icon-file-text3"></i>Report</a></li>
                </div>
                <div style="display:flex; gap:15px;">
                    <li><a style="font-size: 15px;color:aliceblue;" href="" class="legitRipple"><i class="fa-solid fa-phone"></i>
                            +91 8930300602</a>
                    </li>
                    <li><a style="font-size: 15px;color:aliceblue;" href="" class="legitRipple"><i class="fa-solid fa-envelope"></i>
                            Shopneohelp@gmail.com</a>
                    </li>
                </div>
            </div>

        </ul><br>
        <div class="tab-content">
            <div class="tab-pane active" id="seller-registration">
                @if(session('responseData'))
                <div class="row " style="margin:10px 0;">
                    <div class="col-md-12 ">
                        <div class="alert alert-info text-center p-3" role="alert" style="border-radius: 10px; font-size: 18px;">
                            <strong>Result : {{ session('responseData') }}</strong>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-default panel-shadow">
                            <div class="panel-heading">
                                <h5 class="panel-title"><img src="{{asset('assets/images/ONDC.png')}}" style="height:80px;width:60px;margin-left:10px;" /><span style="margin-left: 15px;">New Seller Registration</span></h5>
                            </div>
                            <form action="" method="post" id="sellerForm">
                                {{ csrf_field() }}

                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="Customername">Name</label>
                                        <input type="text" class="form-control" id="name" name="sellername"
                                            placeholder="Enter name" required>

                                    </div>

                                    <div class="form-group">
                                        <label for="mobileNo">Mobile</label>
                                        <input type="text" class="form-control" id="mobile" name="mobile"
                                            pattern="[0-9]*" maxlength="10" minlength="10" placeholder="Enter mobile number"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="Email">Email</label>
                                        <input type="email" class="form-control" id="Email" name="email"
                                            placeholder="Enter email" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="district">District</label>
                                        <select name="district" id="" class="form-control select2" required>
                                            <option value="">Select District</option>
                                            <option value="AJMER">AJMER</option>
                                            <option value="ALWAR">ALWAR</option>
                                            <option value="BALOTARA">BALOTARA</option>
                                            <option value="BANSWARA">BANSWARA</option>
                                            <option value="BARAN">BARAN</option>
                                            <option value="BARMER">BARMER</option>
                                            <option value="BEAWAR">BEAWAR</option>
                                            <option value="BHARATPUR">BHARATPUR</option>
                                            <option value="BHILWARA">BHILWARA</option>
                                            <option value="BIKANER">BIKANER</option>
                                            <option value="BUNDI">BUNDI</option>
                                            <option value="CHITTORGARH">CHITTORGARH</option>
                                            <option value="CHURU">CHURU</option>
                                            <option value="DAUSA">DAUSA</option>
                                            <option value="DEEG">DEEG</option>
                                            <option value="DHOLPUR">DHOLPUR</option>
                                            <option value="DIDWANA-KUCHAMAN">DIDWANA-KUCHAMAN</option>
                                            <option value="DUNGARPUR">DUNGARPUR</option>
                                            <option value="HANUMANGARH">HANUMANGARH</option>
                                            <option value="JAIPUR">JAIPUR</option>
                                            <option value="JAISALMER">JAISALMER</option>
                                            <option value="JALORE">JALORE</option>
                                            <option value="JHALAWAR">JHALAWAR</option>
                                            <option value="JHUNJHUNU">JHUNJHUNU</option>
                                            <option value="JODHPUR">JODHPUR</option>
                                            <option value="KARAULI">KARAULI</option>
                                            <option value="KHAIRTHAL-TIJARA">KHAIRTHAL-TIJARA</option>
                                            <option value="KOTA">KOTA</option>
                                            <option value="KOTPUTLI-BEHROR">KOTPUTLI-BEHROR</option>
                                            <option value="NAGAUR">NAGAUR</option>
                                            <option value="PALI">PALI</option>
                                            <option value="PHALODI">PHALODI</option>
                                            <option value="PRATAPGARH">PRATAPGARH</option>
                                            <option value="RAJSAMAND">RAJSAMAND</option>
                                            <option value="SALUMBAR">SALUMBAR</option>
                                            <option value="SAWAI MADHOPUR">SAWAI MADHOPUR</option>
                                            <option value="SIKAR">SIKAR</option>
                                            <option value="SIROHI">SIROHI</option>
                                            <option value="SRI GANGANAGAR">SRI GANGANAGAR</option>
                                            <option value="TONK">TONK</option>
                                            <option value="UDAIPUR">UDAIPUR</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="panel-footer text-center">
                                    <button type="submit" class="btn bg-teal-800 btn-labeled legitRipple btn-lg">
                                        <b><i class="icon-paperplane"></i></b> Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div>
                            <div style="display:flex; justify-content:space-between; align-items: center; padding:15px 10; gap: 10px;" class="ml-paragraph">
                                <!-- <h2><b>अब बिज़नेस को डिजिटल बनाएं – ONDC पर Seller बनें!</b></h2> -->
                                <img src="{{asset('assets/images/ONDC.png')}}" style="height: 70px; width:150px" />
                                <h2 style="text-align: center;"><b>अब बिज़नेस को डिजिटल बनाएं – ONDC पर Seller बनें!</b></h2>
                                <img src="{{asset('assets/images/shopneoLogo.png')}}" style="height: 120px; width:120px" />

                            </div>
                            <div style="text-align: center;" class="ml-paragraph">क्या आप अपने प्रोडक्ट्स पूरे भारत में बेचना चाहते हैं?<br>
                                भारत सरकार की पहल ONDC (Open Network for Digital Commerce) पर Seller बनकर:<br>
                                लाखों ग्राहकों तक पहुंचे
                            </div>
                        </div>

                        <div class="justify-content-center text-center">
                            <h2>Key Features</h2>
                        </div>

                        <div class="text-center">
                            <p class="ml-paragraph">- बिना किसी एक platform पर निर्भर रहे
                            </p>
                            <p class="ml-paragraph">- कम कमीशन में ज़्यादा मुनाफा कमाएं</p>
                            <p class="ml-paragraph">- सरकारी नेटवर्क पर भरोसे के साथ बिजनेस करें</p>
                            <p class="ml-paragraph">- रजिस्ट्रेशन आसान है!</p>
                            <p class="ml-paragraph">- ज़रूरी दस्तावेज़: Mobile Number, Mail ID, PAN, GST, Bank Details, Address & Product Info</p>
                            <p class="ml-paragraph">- Grocery, Fashion, Restaurant, Electronics, Handicrafts – सभी के लिए अवसर!</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- <div class="tab-pane report" id="report">
                <div class="panel panel-default">
                    <form id="searchForm">
                        <input type="hidden" name="agent" value="{{$KIOSKCODEJ}}">
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
                                    <th>Seller Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>District</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ondcseller as $seller)
                                <tr id="seller-row-{{ $seller->id }}">
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$seller->name}}</td>
                                    <td>{{$seller->email}}</td>
                                    <td>{{$seller->mobile}}</td>
                                    <td>{{$seller->district}}</td>
                                    <td>
                                        <button class="btn btn-xs btn-danger delete-seller-btn" data-id="{{ $seller->id }}">
                                            <i class="fas fa-trash-alt text-white"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> -->
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- this is for insert the ondc seller data   -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('sellerForm');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch("{{ url('ondc/ondc-seller/store') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(async (response) => {
                    if (!response.ok) {
                        if (response.status === 422) {
                            const data = await response.json();
                            const errors = Object.values(data.errors).flat().join('\n');
                            throw new Error(errors);
                        }
                        throw new Error('Something went wrong!');
                    }
                    return response.json();
                })
                .then(data => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Seller registered successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // location.reload();
                            window.location.href = "https://admin-dashboard.shopneo.in/sign-up/";
                        }
                    });
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text: error.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
        });
    });
</script>

<!-- this is for delete seller -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-seller-btn').forEach(button => {
            button.addEventListener('click', function() {
                const sellerId = this.dataset.id;
                console.log(sellerId);



                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This Seller will be permanently deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`${window.location.origin}/ondc/seller-delete/${sellerId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Deleted!', data.message, 'success').then(() => {
                                        const row = document.querySelector(`#seller-row-${sellerId}`);
                                        if (row) row.remove();
                                    });
                                } else {
                                    Swal.fire('Error', data.message, 'error');
                                }
                            })
                            .catch(() => Swal.fire('Error', 'Something went wrong', 'error'));
                    }
                });
            });
        });
    });
</script>

@endpush