@extends('layouts.full')

@section('page_content')


<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist\reports\css\reports.css') }}">

<section >
<!-- Recharge Reports table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Charges Setting</h4>
                <div class="row">
                    
                    
                   
                    <div class="col-1 text-right">
                    <div class="collapse text-right" id="exportBox">
                        <div class="btn-group filter-elements">
                            @if(isset($report) && $report)
                                <button type="submit" id="pdf-btn" class="btn btn-sm btn-warning"><i class="mdi mdi-file-pdf"></i> PDF</button>
                            @else
                                <button type="submit"  id="pdf-btn" class="btn btn-sm btn-warning" disabled><i class="mdi mdi-file-pdf"></i> PDF</button>
                            @endif
                        </div>
                    </div>
                    </div>
                  
                </div>
                <br>
                <div class="table-responsive">
                    <table id="recharge-report-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                               
                                <th>Mode</th>
                                <th>Code</th>
                                <th>Charge</th>
                                <th>Charge Type</th>
                                <th>Action</th>
                               
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($all_charges as $index => $value)
                           
                                <tr>
                                <form action="{{ route('update_charges_setting') }}" method="post">
                                @csrf
                                <input type="hidden" name="charge_id" value="{{ $value->id }}">
                                    <td>{{ $index+1 }}</td>
                                   <td>{{ $value->mode }}</td>
                                   <td>{{ $value->code }}</td>
                                   <td> <input type="text" class="form-control" name="charges" value="{{ $value->charge }}" required></td>
                                   
                                   <td> 
                                        <select class="form-control" name="charge_type">
                                            <option disabled selected value="">Select</option>
                                            <option value="RS" @if($value->charge_type == 'RS')  selected @endif >Rupees</option>
                                            <option value="%" @if($value->charge_type == '%')  selected @endif >Percent %</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-sm btn-outline-info"   title="Edit" >
                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                    </td>
                                    </form>
                                </tr>
                           
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                               
                               <th>Mode</th>
                               <th>Code</th>
                               <th>Charge</th>
                               <th>Charge Type</th>
                               <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Recharge Reports table ends -->

</section>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="{{ asset('template_assets\other\js\flatpickr') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<!-- <script src="{{ asset('dist\reports\js\rechargeReport.js') }}"></script> -->
@endsection
