@extends('layouts.full_new')
@section('page_content')


<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist\reports\css\reports.css') }}">

<style>
    th {
  text-transform: uppercase;
}
</style>


<div class="page-content container-fluid">
        <!-- ============================================================== -->
        <!-- Card Group  -->
        <!-- ============================================================== -->
<div class="row">
    <div class="col-12">


        <div class="card-group">
            <div class="card p-2 p-lg-3">
                <h4 class="card-title ">Filter</h4>
                <hr>
                <div class="p-lg-3 p-2">
                    <form action="/member_passbook_pm">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="font-weight-bold">FROM DATE</label>
                                    <input type="text" class="form-control" name="from_date" id="from_date"  value="{{request()->input('from_date')}}">
                                </div>
                            </div>


                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="font-weight-bold">TO DATE</label>
                                    <input type="text" class="form-control" name="to_date" id="to_date" value="{{request()->input('to_date')}}">
                                </div>
                            </div>

                           

                            <button type="submit" class="btn btn-lg success-grad " style="height: 40px;margin-top:30px;height: calc(2.1rem + .75rem + 2px);">Submit</button>
                    </form>

                </div>
            </div>
        </div>
        </div>
        </div>

        </div>

        

        @if(isset($report))
      
<div class="row">

<div class="col-12">
       <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">



                        <!-- table -->
                        <table id="retailerdatatable" class="table table-striped  table-bordered table-sm border ">
                            <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>DATE & TIME</th>
                                    <th>ORDER ID</th>
                                    <th>TRANSACTION ID</th>
                                    <th>TRANSACTION TYPE</th>
                                    <th>DESCRIPTION</th>
                                    <th>DEBIT AMOUNT</th>
                                    <th>CREDIT AMOUNT</th>
                                    <th>CURRENT BALANCE </th>
                                    


                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($report as $index => $trans)
                                <tr>
                                    <td >{{ $index+1 }}</td>
                                    <td>{{ $trans['order_id'] }}</td>
                                    <td>{{ $trans['trans_date'] }}</td>
                                    <td>{{ $trans['transaction_id'] }}</td>
                                    <td>{{ $trans['payment_type'] }}</td>
                                    <td>{{ $trans['payment_mode'] }}</td>
                                    @if($trans['transaction_type'] == 'DEBIT')
                                    <td style="text-align: center;">{{ $trans['total_amount'] }}</td>
                                    @else
                                    <td></td>
                                    @endif
                                    @if($trans['transaction_type'] == 'CREDIT')
                                    <td style="text-align: center;">{{ $trans['total_amount'] }}</td>
                                    @else
                                    <td></td>
                                    @endif
                                    <td>{{ $trans['balance'] }}</td>
                                    

                                  
                                    

                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                <th>S.NO</th>
                                    <th>DATE & TIME</th>
                                    <th>ORDER ID</th>
                                    <th>TRANSACTION ID</th>
                                    <th>TRANSACTION TYPE</th>
                                    <th>DESCRIPTION</th>
                                    <th>DEBIT AMOUNT</th>
                                    <th>CREDIT AMOUNT</th>
                                    <th>CURRENT BALANCE </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        </div>
        </div>

        @endif
 




<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="{{ asset('template_assets/other/js/flatpickr.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\reports\js\rechargeReport.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#retailerdatatable').DataTable({
            "pageLength": 10
        });
    });
</script>
@endsection