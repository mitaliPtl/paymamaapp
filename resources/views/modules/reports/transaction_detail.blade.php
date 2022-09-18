@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets/other/css/bootstrap-toggle.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets/other/css/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dist/reports/css/transaction_detail.css') }}">

<!-- Transaction detail table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">All Transactions</h4>
                <div class="row">
                    <div class="col-12 text-right mb-2">
                        <a href="{{$_SERVER['REQUEST_URI']}}">
                            <button type="button" title="Refresh" class="btn btn-outline-primary btn-circle btn-md mr-2"><i class="mdi mdi-rotate-right"></i></button>
                        </a>
                        <button type="button" title="Apply Filter" class="btn btn-outline-info btn-circle btn-md mr-2" data-toggle="collapse" data-target="#filterBox"><i class="fa fa-filter"></i></button>
                    </div>

                    <div class="col-12">
                        <div class="collapse show" id="filterBox">
                        @if(isset($filtersList) && $filtersList)
                            <form action="{{ $_SERVER['REQUEST_URI'] }}" method="post">
                            @csrf
                                <input type="hidden" id="is_export" name="is_export" value="0">
                                <div class="row ml-2">

                                    @foreach($filtersList as $i => $filter)
                                        <div class="filter-elements">
                                                @if($filter['name'] == "from_date")
                                                    <input type="text" class="form-control date-inp" id="{{ $filter['id'] }}" name="{{ $filter['name'] }}" value="{{ $request->from_date}}" placeholder="{{ $filter['label'] }}">
                                                @endif

                                                @if($filter['name'] == "to_date")
                                                    <input type="text" class="form-control date-inp" id="{{ $filter['id'] }}" name="{{ $filter['name'] }}" value="{{ $request->to_date}}" placeholder="{{ $filter['label'] }}">
                                                @endif

                                                @if($filter['name'] == "state_id")
                                                    <select name="{{ $filter['name'] }}" id="{{ $filter['id'] }}" class="form-control">
                                                        <option value="" selected>{{ $filter['label'] }}</option>
                                                        @foreach($states as $state)
                                                            @if($state->state_id == $request->state_id)
                                                                <option value="{{ $state->state_id }}" selected> {{ $state->state_name }}</option>
                                                            @else
                                                                <option value="{{ $state->state_id }}"> {{ $state->state_name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @endif

                                                @if($filter['name'] == "city_id")
                                                    <select name="{{ $filter['name'] }}" id="{{ $filter['id'] }}" class="form-control">
                                                        <option value="" selected>{{ $filter['label'] }}</option>
                                                        @foreach($cities as $city)
                                                            @if($city->city_id == $request->city_id)
                                                                <option value="{{ $city->city_id }}" selected> {{ $city->city_name }}</option>
                                                            @else
                                                                <option value="{{ $city->city_id }}"> {{ $city->city_name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @endif

                                                @if($filter['name'] == "store_category_id")
                                                    <select name="{{ $filter['name'] }}" id="{{ $filter['id'] }}" class="form-control">
                                                        <option value="" selected>{{ $filter['label'] }}</option>
                                                        @foreach($storeCategories as $sCat)
                                                            @if($sCat->id == $request->store_category_id)
                                                                <option value="{{ $sCat->id }}" selected> {{ $sCat->store_category_name }}</option>
                                                            @else
                                                                <option value="{{ $sCat->id }}"> {{ $sCat->store_category_name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @endif

                                                @if($filter['name'] == "api_id")
                                                    <select name="{{ $filter['name'] }}" id="{{ $filter['id'] }}" class="form-control">
                                                        <option value="" selected>{{ $filter['label'] }}</option>
                                                        @foreach($apiSettings as $setting)
                                                            @if($setting->api_id == $request->api_id)
                                                                <option value="{{ $setting->api_id }}" selected> {{ $setting->api_name }}</option>
                                                            @else
                                                                <option value="{{ $setting->api_id }}"> {{ $setting->api_name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @endif

                                                @if($filter['name'] == "service_id")
                                                    <select name="{{ $filter['name'] }}" id="{{ $filter['id'] }}" class="form-control">
                                                        <option value="" selected>{{ $filter['label'] }}</option>
                                                        @foreach($servicesTypes as $service)
                                                            @if($service->service_id == $request->service_id)
                                                                <option value="{{ $service->service_id }}" selected> {{ $service->service_name }}</option>
                                                            @else
                                                                <option value="{{ $service->service_id }}"> {{ $service->service_name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @endif

                                                @if($filter['name'] == "operator_id")
                                                    <select name="{{ $filter['name'] }}" id="{{ $filter['id'] }}" class="form-control" style="min-width:150px">
                                                        <option value="" selected>{{ $filter['label'] }}</option>
                                                        @foreach($operators as $operator)
                                                            @if($operator->operator_id == $request->operator_id)
                                                                <option value="{{ $operator->operator_id }}" selected> {{ $operator->operator_name }}</option>
                                                            @else
                                                                <option value="{{ $operator->operator_id }}"> {{ $operator->operator_name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @endif
                                                

                                                @if($filter['name'] == "order_status")
                                                    <select name="{{ $filter['name'] }}" id="{{ $filter['id'] }}" class="form-control">
                                                            <option value="" selected>{{ $filter['label'] }}</option>
                                                            @if($request->order_status == "SUCCESS")
                                                                <option value="SUCCESS" selected>Success</option>
                                                                <option value="FAILED">Failed</option>
                                                            @elseif($request->order_status == "FAILED")
                                                                <option value="SUCCESS">Success</option>
                                                                <option value="FAILED" selected>Failed</option>
                                                            @else
                                                                <option value="SUCCESS">Success</option>
                                                                <option value="FAILED">Failed</option>
                                                            @endif
                                                    </select>
                                                @endif
                                        </div>
                                    @endforeach

                                    <div class="filter-elements">
                                        <button class="btn btn-md btn-outline-primary" id="filter-submit-btn" type="submit"><i class="fa fa-filter"></i> Filter</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                        </div>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table id="transaction-detail-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Store Name</th>
                                <th>City</th>
                                <th>Store Category</th>
                                <th>Amount</th>
                                <th>Com. Amount</th>
                                <th class="text-center">User Status</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $index => $data)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $data->trans_date }}</td>
                                    <td>{{ $data->user_id ?  App\User::getClmnValById($data->user_id,'first_name') .' '. App\User::getClmnValById($data->user_id,'last_name') : '' }}</td>
                                    <td>{{ $data->user_id ?  App\User::getClmnValById($data->user_id,'store_name') : '' }}</td>
                                    <td>{{ $data->user_id ?  App\User::getMetaClmnValById($data->user_id,'district_id') : '' }}</td>
                                    <td>{{ $data->user_id ?  App\User::getMetaClmnValById($data->user_id,'store_category_id') : '' }}</td>
                                    <td class="label">{{ $data->total_amount }}</td>
                                    <td>{{ $data->user_id && $data->order_id ?  App\WalletTransactionDetail::getComAmtByTranDtl($data) : '' }}</td>
                                    <td class="text-center">{{ $data->user_id ?  App\User::getClmnValById($data->user_id,'activated_status') == 'YES' ? 'Active' : 'Inactive' : '' }}</td>
                                    <td class="text-center label {{ $data->order_status == 'SUCCESS' ? 'text-success' :  ($data->order_status == 'PENDING' ? 'text-warning' : 'text-danger') }}">{{ $data->order_status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Store Name</th>
                                <th>City</th>
                                <th>Store Category</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Com. Amount</th>
                                <th class="text-center">User Status</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Transaction detail table ends -->

</section>

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="template_assets/other/js/bootstrap-toggle.min.js"></script>
<script src="template_assets/other/js/sweetalert.min.js"></script>
<script src="{{ asset('template_assets/other/js/flatpickr') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist/reports/js/transactionDetail.js') }}"></script>
@endsection
