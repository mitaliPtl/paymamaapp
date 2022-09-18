{{--@extends('layouts.full') --}}

@extends('layouts.full_new') 
@section('page_content')

<!-- <section> -->
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dist\bank\css\bank_account.css') }}">
<style>
    th {
  text-transform: uppercase;
}
</style>
<div class="page-content container-fluid">

<!-- Bank Account table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Notifications</h4>
                <br>
               
                <br>
                    <!-- <table id="bank-ac-table" class="table table-striped table-sm border is-data-table"> -->
                    <table id="notifications-table" class="table table-striped table-bordered table-sm border is-data-table">

                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Description</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($all_notification as $index => $notify)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $notify->sended_on }}</td>
                                    <td>{{ $notify->title }}</td>
                                    <td>{{ $notify->body }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                            <th>Sr No</th>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Description</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bank Account table ends -->



<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="template_assets\other\js\sweetalert.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\bank\js\bankAcFormValidation.js') }}"></script>
<script src="{{ asset('dist\bank\js\bank_account.js') }}"></script>
@endsection
