@extends('layouts.full')

@section('page_content')

<section>
    <!-- ============================================================== -->
    <!-- Container fluid Admin starts -->
    <!-- ============================================================== -->
    @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
        <div class="page-content container-fluid">
            <!-- ============================================================== -->
            <!-- Card Group  -->
            <!-- ============================================================== -->
            <div class="card-group" style="margin-top:30px">
                <div class="card p-2 p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-danger text-white btn-lg" href="javascript:void(0)">
                            <i class="mdi mdi-account-convert"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">Total Distributors</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $totalUser ? ($dTCount/$totalUser)*100 : $totalUser }}%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h2 class="display-7 mb-0">{{ $dTCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-2 p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-cyan text-white btn-lg" href="javascript:void(0)">
                            <i class="mdi mdi-account-outline"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">Total FOSs</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-cyan" role="progressbar" style="width: {{$totalUser ? ($fosCount/$totalUser)*100 : $totalUser }}%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h2 class="display-7 mb-0">{{ $fosCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-2 p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-warning text-white btn-lg" href="javascript:void(0)">
                            <i class="mdi mdi-account-switch"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">Total Retailers</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalUser ? ($rTCount/$totalUser)*100 : $totalUser }}%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h2 class="display-7 mb-0">{{ $rTCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-group" style="margin-top:30px">
                <div class="card p-2 p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-info text-white btn-lg" href="javascript:void(0)">
                            <i class="mdi mdi-cash-100"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">Total API Balance</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $totalApiBalance ? ($totalApiBalance/$totalApiBalance)*100 : $totalApiBalance }}%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h3 class="display-9 mb-0"><i class="mdi mdi-currency-inr"></i>{{ $totalApiBalance }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-2 p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-success text-white btn-lg" href="javascript:void(0)">
                            <i class="mdi mdi-cash-100"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">Total Fund</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalFundWithAdmin ? ($totalFund/$totalFundWithAdmin)*100 : $totalFundWithAdmin }}%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h3 class="display-8 mb-0"><i class="mdi mdi-currency-inr"></i>{{ $totalFund }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-2  p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-cyan text-white btn-lg" href="javascript:void(0)">
                            <i class="mdi mdi-account-multiple"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">New Members</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-cyan" role="progressbar" style="width: {{ $totalUser ? ($newMembersCount/$totalUser) * 100 : $totalUser }}%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h3 class="display-8 mb-0">{{ $newMembersCount }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Column --> 
                <div class="col-lg-3 col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1 class="mt-0"><i class="fa fa-hourglass-half {{ $pendingBalReq ? 'fa-spin' : ''}} text-warning"></i></h1>
                            <h4 class="font-light">Pending Balance Request</h4>
                            <a href="{{ route('balance_request') }}" class="btn  btn-lg card-btn btn-warning" id="pending-bal-req-btn" style="border-radius:50%">{{ $pendingBalReq ? $pendingBalReq : 00 }}</a>                        
                        </div>
                    </div>
                </div>
                <!-- Column -->

                <!-- Column --> 
                <div class="col-lg-3 col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1 class="mt-0"><i class="fa fa-hourglass-half {{ $pendingKYCReq ? 'fa-spin' : ''}} text-primary"></i></h1>
                            <h4 class="font-light">Pending KYC Request</h4>
                            <a href="{{ route('user_list') }}" class="btn  btn-lg card-btn btn-primary" style="border-radius:50%">{{ $pendingKYCReq ? $pendingKYCReq : 00 }}</a>                        
                        </div>
                    </div>
                </div>
                <!-- Column -->

                 <!-- Column --> 
                 <div class="col-lg-3 col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1 class="mt-0"><i class="fa fa-hourglass-half {{ $pendingComplaints ? 'fa-spin' : ''}} text-danger"></i></h1>
                            <h4 class="font-light">Pending Complaints</h4>
                            <a href="{{ route('complaints',['service_type'=>'COMPLAINT']) }}" class="btn  btn-lg card-btn btn-danger" style="border-radius:50%">{{ $pendingComplaints ? $pendingComplaints : 00 }}</a>                        
                        </div>
                    </div>
                </div>
                <!-- Column -->
            </div>
            <!-- ============================================================== -->
            <!-- Transaction Section  -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-md-12 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase">
                                <span>Transactions Till Date</span>
                                </h5>
                                <ul class="list-style-none country-state mt-4" style="cursor:pointer" onclick="location.href='{{ route('day_book')}}'">
                                    <li class="mb-4">
                                        <h2 class="mb-0">{{ ($transaction['success'] + $transaction['pending'] + $transaction['failed']) }}</h2>
                                        <small>Total Transaction</small>
                                    </li>
                                    <li class="mb-4">
                                        <h2 class="mb-0">{{ $transaction['success'] }}</h2>
                                        <small>Success</small>
                                        <div class="float-right">{{ $transaction['success'] ? (int) (($transaction['success']/($transaction['success'] + $transaction['pending'] + $transaction['failed'])) * 100) : $transaction['success'] }}% <i class="fas fa-level-up-alt text-success"></i></div>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $transaction['success'] ? ($transaction['success']/($transaction['success'] + $transaction['failed'])) * 100 : $transaction['success']}}%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>
                                    <li class="mb-4">
                                        <h2 class="mb-0">{{ $transaction['pending'] }}</h2>
                                        <small>Pending</small>
                                        <div class="float-right">{{ $transaction['pending'] ? (int) (($transaction['pending']/($transaction['success'] + $transaction['pending'] + $transaction['failed'])) * 100) : $transaction['pending'] }}% <i class="fas fa-level-up-alt text-warning"></i></div>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $transaction['pending'] ? ($transaction['pending']/($transaction['success'] + $transaction['failed'])) * 100 : $transaction['pending'] }}%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>
                                    <li class="mb-4">
                                        <h2 class="mb-0">{{ $transaction['failed'] }}</h2>
                                        <small>Failure</small>
                                        <div class="float-right">{{$transaction['failed'] ? (int) (($transaction['failed']/($transaction['success'] + $transaction['pending'] + $transaction['failed'])) * 100) : $transaction['failed'] }}% <i class="fas fa-level-up-alt text-danger"></i></div>
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $transaction['failed'] ? (($transaction['failed']/($transaction['success'] + $transaction['failed'])) * 100) : $transaction['failed'] }}%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    @endif
    <!-- ============================================================== -->
    <!-- End Container fluid Admin ends  -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Container fluid Distributor starts -->
    <!-- ============================================================== -->
    @if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR'))
        <div class="page-content container-fluid">
            <!-- ============================================================== -->
            <!-- Card Group  -->
            <!-- ============================================================== -->
            <div class="card-group" style="margin-top:30px">
                <div class="card p-2 p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-danger text-white btn-lg" href="javascript:void(0)">
                            <i class="ti-clipboard"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">Total Distributors</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h2 class="display-7 mb-0">00</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- Products yearly sales, Weather Cards Section  -->
            <!-- ============================================================== -->
           
        </div>
    @endif
    <!-- ============================================================== -->
    <!-- End Container fluid Distributor ends  -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Container fluid Fos starts -->
    <!-- ============================================================== -->
    @if( Auth::user()->roleId == Config::get('constants.FOS'))
        <div class="page-content container-fluid">
            <!-- ============================================================== -->
            <!-- Card Group  -->
            <!-- ============================================================== -->
            <div class="card-group" style="margin-top:30px">
                
                <div class="card p-2 p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-cyan text-white btn-lg" href="javascript:void(0)">
                            <i class="ti-wallet"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">Total FOSs</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-cyan" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h2 class="display-7 mb-0">00</h2>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <!-- ============================================================== -->
            <!-- Products yearly sales, Weather Cards Section  -->
            <!-- ============================================================== -->
           
        </div>
    @endif
    <!-- ============================================================== -->
    <!-- End Container fluid Fos ends  -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Container fluid Retailer starts -->
    <!-- ============================================================== -->
    @if( Auth::user()->roleId == Config::get('constants.RETAILER'))
        <div class="page-content container-fluid">
            <!-- ============================================================== -->
            <!-- Card Group  -->
            <!-- ============================================================== -->
            <div class="card-group" style="margin-top:30px">
                
                <div class="card p-2 p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-warning text-white btn-lg" href="javascript:void(0)">
                            <i class="fas fa-dollar-sign"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">Total Retailers</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h2 class="display-7 mb-0">00</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-group">
                <!-- Recharge & Bill payments starts -->
                <div class="card">
                    <div class="row m-3">
                        <div class="col-12 mb-3">
                            <div class="card-title">Recharge & Bill Payments</div>
                        </div>
                        @foreach($serviceList as $i => $service)
                            <div class="col-2 text-center">
                                <a class="btn btn-circle btn-light text-white btn-lg mb-1" href="{{ route($service['route'],['type' => $service['key']]) }}">
                                    <i class="{{ $service['icon'] }}" style="color:#3c1c5d"></i>
                                </a>
                                <div><span>{{ $service['name'] }}</span></div>
                            </div>
                        @endforeach   
                        <div class="col-12 pb-3"></div>                   
                    </div>
                </div>
                <!-- Recharge & Bill payments ends -->
            </div>
            <!-- ============================================================== -->
            <!-- Products yearly sales, Weather Cards Section  -->
            <!-- ============================================================== -->
        </div>
    @endif
    <!-- ============================================================== -->
    <!-- End Container fluid Retailer ends  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
    <footer class="footer text-center">
        Copyright @ SMARTPAY - Making India Digital.
    </footer>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
</section>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script> 
@endsection