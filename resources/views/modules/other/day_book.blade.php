@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dist\other\css\day_book.css') }}">

<!-- Service type table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Day Book</h4>
                <br>
                <!-- Filter Section -->
                <div class="row">
                    <div class="col-12 text-right mb-2">

                        @if(isset($total['total_hits']) && $total['total_hits'])
                        <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2"> Hits: {{ $total['total_hits'] }}</button>
                        @endif

                        @if(isset($total['total_amount']) && $total['total_amount'])
                        <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2"> Amount: {{ $total['total_amount'] }}</button>
                        @endif

                        @if(isset($total['success_hits']) && $total['success_hits'])
                        <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2"> Success Hits: {{ $total['success_hits'] }}</button>
                        @endif

                        @if(isset($total['success_amount']) && $total['success_amount'])
                        <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2"> Success Amount: {{ $total['success_amount'] }}</button>
                        @endif

                        @if(isset($total['failure_hits']) && $total['failure_hits'])
                        <button type="button" title="Total Amount" class="btn btn-light btn-danger btn-md mr-2"> Failure Hits: {{ $total['failure_hits'] }}</button>
                        @endif

                        @if(isset($total['failure_amount']) && $total['failure_amount'])
                        <button type="button" title="Total Amount" class="btn btn-warning info-button btn-md mr-2"> Failure Amount: {{ $total['failure_amount'] }}</button>
                        @endif

                        @if(isset($total['commission']) && $total['commission'])
                        <button type="button" title="Total Amount" class="btn btn-cyan info-button btn-md mr-2"> Commission: {{ $total['commission'] }}</button>
                        @endif
                        <a href="{{$_SERVER['REQUEST_URI']}}">
                            <button type="button" title="Refresh" class="btn btn-outline-primary btn-circle btn-md mr-2"><i class="mdi mdi-rotate-right"></i></button>
                        </a>
                        <button type="button" title="Apply Filter" class="btn btn-outline-info btn-circle btn-md mr-2" data-toggle="collapse" data-target="#filterBox"><i class="fa fa-filter"></i></button>
                    </div>

                    <div class="col-10">
                    <div class="collapse show" id="filterBox">
                        <form action="{{ $_SERVER['REQUEST_URI'] }}" method="post">
                        @csrf
                            <div class="row">

                                <div class="col-3">
                                    <input type="text" class="form-control" id="date" name="date" value="{{ $request->date ? $request->date : $today}}" placeholder="Select Date">
                                </div>

                                <div class="col-2">
                                    <button class="btn btn-md btn-outline-primary" id="filter-submit-btn" type="submit"><i class="fa fa-filter"></i> Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    </div>
                    <div class="col-2 text-right">
                    <div class="collapse text-right" id="exportBox">
                        <div class="btn-group">
                            @if(isset($rechargeReports) && $rechargeReports)
                                <button type="submit"  id="pdf-btn" class="btn btn-md btn-warning"><i class="mdi mdi-file-pdf"></i> PDF</button>
                            @else
                                <button type="submit"  id="pdf-btn" class="btn btn-md btn-warning" disabled><i class="mdi mdi-file-pdf"></i> PDF</button>
                            @endif
                        </div>
                    </div>
                    </div>

                    <div class="col-12 text-right mb-2">
                        
                    </div>
                </div>
                <br>
                <!-- Filter Section ends -->
                <div class="table-responsive">
                    <table id="service-type-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator</th>
                                <th>Total Hits</th>
                                <th>Total Amount</th>
                                <th>Success Hits</th>
                                <th>Success Amount</th>
                                <th>Failure Hits</th>
                                <th>Failure Amount</th>
                                <th>Commission</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $index => $report)
                                <tr>
                                    <td>{{ $index + 1}}</td>
                                    <td>{{ $report['operator_name']}}</td>
                                    <td>{{ $report['total_hits']}}</td>
                                    <td>{{ $report['total_amount']}}</td>
                                    <td>{{ $report['success_hits']}}</td>
                                    <td>{{ $report['success_amount']}}</td>
                                    <td>{{ $report['failure_hits']}}</td>
                                    <td>{{ $report['failure_amount']}}</td>
                                    <td>{{ $report['commission']}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator</th>
                                <th>Total Hits</th>
                                <th>Total Amount</th>
                                <th>Success Hits</th>
                                <th>Success Amount</th>
                                <th>Failure Hits</th>
                                <th>Failure Amount</th>
                                <th>Commission</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Service Type table ends -->

</section>

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="{{ asset('template_assets\other\js\flatpickr') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\other\js\dayBook.js') }}"></script>
@endsection
