
@extends('layouts.full')

@section('page_content')


<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist\reports\css\reports.css') }}">

<section >
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Office Expense Reports</h4>
                <div class="row">
                    <div class="col-12 text-right mb-2">
                            @if(isset($total['total_amt']) && $total['total_amt'])
                                <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2"> Amount : {{ $total['total_amt'] }}</button>
                            @endif
                            @if(isset($total['total_balnc']) && $total['total_balnc'])
                                <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2"> Balance : {{ $total['total_balnc'] }}</button>
                            @endif

                        <a href="{{$_SERVER['REQUEST_URI']}}">
                            <button type="button" title="Refresh" class="btn btn-outline-primary btn-circle btn-md mr-2"><i class="mdi mdi-rotate-right"></i></button>
                        </a>

                        <button type="button" title="Apply Filter" class="btn btn-outline-info btn-circle btn-md mr-2" data-toggle="collapse" data-target="#filterBox"><i class="fa fa-filter"></i></button>
                        <button type="button" title="Export" class="btn btn-outline-dark btn-circle btn-md mr-3" data-toggle="collapse" data-target="#exportBox"><i class="fa fa-download"></i></button>
                    </div>
                    
                    <div class="col-11">
                    <div class="collapse show" id="filterBox">
                    @if(isset($filtersList) && $filtersList)
                        <form action="{{ $_SERVER['REQUEST_URI'] }}" method="post">
                        @csrf
                            <input type="hidden" id="is_export" name="is_export" value="0">
                            <div class="row">

                            @foreach($filtersList as $i => $filter)
                                <div class="filter-elements">

                                        @if($filter['name'] == "from_date")
                                            <input type="text" class="form-control" id="{{ $filter['id'] }}" name="{{ $filter['name'] }}" value="{{ $request->from_date}}" placeholder="{{ $filter['label'] }}">
                                        @endif

                                        @if($filter['name'] == "to_date")
                                            <input type="text" class="form-control" id="{{ $filter['id'] }}" name="{{ $filter['name'] }}" value="{{ $request->to_date}}" placeholder="{{ $filter['label'] }}">
                                        @endif

                                       
                                        @if($filter['name'] == "filter_category_id")
                                            <select name="{{ $filter['name'] }}" id="{{ $filter['id'] }}" class="form-control">
                                                <option value="" selected>{{ $filter['label'] }}</option>
                                                @foreach($categories as $category)
                                                    @if($category->category == $request->filter_category_id)
                                                        <option value="{{ $category->category }}" selected> {{ $category->category }}</option>
                                                    @else
                                                        <option value="{{ $category->category }}"> {{ $category->category }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif


                                        @if($filter['name'] == "filter_bank_name")
                                            <select name="{{ $filter['name'] }}" id="{{ $filter['id'] }}" class="form-control">
                                                <option value="" selected>{{ $filter['label'] }}</option>
                                                @foreach($bank_acc as $bank_acc_val)
                                                    @if($bank_acc_val->bank_name == $request->filter_bank_name)
                                                    <option value="{{ $bank_acc_val->bank_name }}" selected> {{ $bank_acc_val->bank_name }}</option>
                                                    @else
                                                    <option value="{{ $bank_acc_val->bank_name }}"> {{ $bank_acc_val->bank_name }}</option>

                                                    @endif
                                                @endforeach
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
                    <div class="col-1 text-right">
                    <div class="collapse text-right" id="exportBox">
                        <div class="btn-group filter-elements">
                            @if(isset($expenses_report) && $expenses_report)
                                <button type="submit" id="pdf-btn" class="btn btn-sm btn-warning"><i class="mdi mdi-file-pdf"></i> PDF</button>
                            @else
                                <button type="submit"  id="pdf-btn" class="btn btn-sm btn-warning" disabled><i class="mdi mdi-file-pdf"></i> PDF</button>
                            @endif
                        </div>
                    </div>
                    </div>
                  
                </div>
                <div class="row card-title">
                    <div class="col-12 text-right">
                        <button type="button" title="ADD Expenses" class="btn btn-primary btn-md add-expenses-btn" data-toggle="collapse" data-target="#add-expenses"><i class="fa fa-plus"></i> Add Expense </button>
                    </div>
                </div>
                <br>
              
                <div class="table-responsive">
                    <table id="recharge-report-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                @foreach($reportListTH as $i => $head)
                                    <th>{{ $reportListTH[$i]['name'] }}</th>
                                @endforeach
                              
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses_report as $index => $value)
                            
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{  (date('d/m/y H:i:s',strtotime($value['date'])))}}</td>
                                    <td>{{ $value['category_bank'] }}</td>
                                    <td>{{ $value['account_name'] }}</td>
                                    <td>{{ $value['description'] }}</td>
                                    <td>{{ $value['cr_dr'] }}</td>
                                    <td>{{ $value['amount'] }}</td>
                                    <td>{{ $value['balance'] }}</td>
                                    
                                </tr>
                            @endforeach
                           
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                @foreach($reportListTH as $i => $head)
                                    <th>{{ $reportListTH[$i]['name'] }}</th>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                </div>
                </div>
        </div>
    </div>
</div>

    <!-- add modal starts -->
    <div class="modal" id="add-expense" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Add Expense</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="addExpenseForm" action="{{ route('add_expense') }}" method="post">
                @csrf
                    <!-- <input type="hidden" name="change_complaint_id" id="change_complaint_id"> -->
                    <div class="form-group container mt-2">
                        <label for="expenses_type">Select Expense</label>
                        <select name="expenses_type" id="expenses_type" class="form-control" required>
                            <option selected disabled value="">Select</option>
                            @foreach($categories as $cat_key => $cat_value)
                                <option value="{{ $cat_value['category_id'] }}" id="tran-sts-success-option"> {{ $cat_value['category'] }} </option>
                            @endforeach
                            
                        </select>
                    </div>

                    <!-- <div class="form-group container mt-2">
                        <div class="filter-elements">
                            <label for="expenses_type">Date</label>
                            <input type="text" class="form-control flatpickr-input" id="expense_date" name="expense_date" value="" placeholder="Select Date" readonly="readonly">
                        </div> 
                    </div> -->

                    <div class="col-12">
                        <label for="default_time">Description</label>
                        <br>
                        <div class="form-group">
                            <textarea type="text" class="form-control" id="expense_description" name="expense_description" required></textarea>
                        </div>
                    </div>
                    <div class="form-group container mt-2">
                        <label for="bank_acc">Select Account</label>
                        <select name="bank_acc" id="bank_acc" class="form-control" required>
                            <option selected disabled value="">Select</option>
                            <option value="Wallet" id="tran-sts-success-option"> Wallet </option>

                            @foreach($bank_acc as $acc_key => $acc_value)
                                <option value="{{ $acc_value['id'] }}" id="tran-sts-success-option"> {{ $acc_value['bank_name'] }} </option>
                            @endforeach
                            
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="default_time">Amount</label>
                        <br>
                        <div class="form-group">
                                <input type="text" class="form-control" name="expense_amt" id="expense_amt" aria-describedby="expense_amt" placeholder="Amount">
                        </div>
                    </div>
                   

                    <button type="submit" id="add-template-btn" class="btn btn-info btn-block mt-4">
                        ADD
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- add  modal ends -->

    

</section>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="{{ asset('template_assets\other\js\flatpickr') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\reports\js\rechargeReport.js') }}"></script>
<script src="{{ asset('dist\officeexpenses\js\officeexpenses.js') }}"></script>

@endsection