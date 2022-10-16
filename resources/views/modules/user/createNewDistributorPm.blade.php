@extends('layouts.full_new')
@section('page_content')

<style>
    .form-check-input {
        width: 1.375em;
        height: 1.375em;
        margin-top: .0625em;
        vertical-align: top;
        background-color: #eaedf5;
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        border: 0;
        appearance: none;
        color-adjust: exact
    }

    .form-check-input[type=checkbox] {
        border-radius: 4px
    }

    .form-check-input:active {
        filter: brightness(90%)
    }

    .form-check-input:focus {
        border-color: rgba(0, 0, 0, .25);
        outline: 0;
        box-shadow: 0 0 0 0 transparent
    }

    .form-check-input:checked {
        background-color: #2cabe3;
        border-color: #2cabe3
    }

    .form-check-input:checked[type=checkbox] {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e")
    }

    .form-control {
        height: 41px !important;
    }

    label {
        font-weight: bold;
    }
</style>
<link rel="stylesheet" type="text/css" href="https://paymamaapp.in/template_assets/other/css/bootstrap-toggle.min.css">
<div class="page-content container-fluid">
    <div class="card card-body" style="width:50%;">
        <h2 style="font-weight:700">DISTRIBUTOR REGISTRATION</h2>
        <hr style="1px dotted red">

        <h4 class="text-danger mx-5">{{$error ?? ''}}</h4>

        <form action="/store_new_distributor" method="POST">
            @csrf
            <style>
            .form-group label{
                font-weight: normal;
                font-size: 18px;
            }
            </style>
            <div class="form-group">
                <label for="first_name" style="font-weight: normal;font-size: 18px;">First Name:</label> 
                <input type="text" class="form-control" id="first_name" required value="{{request()->input('first_name')}}" name="first_name" placeholder="First Name">
            </div>
            <div class=" form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" class="form-control" id="last_name" required value="{{request()->input('last_name')}}" name="last_name" placeholder="Last Name">
            </div>

            <div class=" form-group">
                <label for="first_name">Mobile Number:</label>
                <input type="number"  class="form-control" required value="{{request()->input('mobile_no')}}" id="mobilenumber" name="mobile_no" placeholder="Mobile Number">
            </div>
            <div class=" form-group">
                <label for="last_name">Email Id:</label>
                <input type="email" class="form-control" id="email_id" required value="{{request()->input('email_id')}}" name="email_id" placeholder="Email Id">
            </div>
            <div class=" form-group">
                <label for="last_name">Business Name:</label>
                <input type="text" class="form-control" name="business_name" required value="{{request()->input('business_name')}}" placeholder="Business Name">
            </div>

          



            <input type="hidden" name="parent_role_id" value="{{Auth::user()->roleId}}">
            <input type="hidden" name="parent_user_id" value="{{Auth::user()->userId}}">
            <input type="hidden" name="parent_package_id" value="{{Auth::user()->roleId}}">



            <div class="form-group">
                <button type="submit" class="btn btn-lg success-grad float-right" style="background-image: linear-gradient(to right, #251c63 , #dc182d);
     color: white;
    border-color: #ffffff;">Submit</button>
            </div>
        </form>
    </div>
</div>

<div class="container">
    <input type="hidden" id="success" value="{{$success ?? 0 }}">
</div>

<!-- Successmodal -->
<div class="modal fade" id="staticBackdrop">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <!-- <h4 class="modal-title">Verification </h4> -->
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <center>
                    <img src="{{ asset('template_new/img/verify_ic.png') }}" alt="verified" style="width: 75px; display:block;" id="resp_success_logo">
                    <h3 class="text-success" id="">Distributor Created Successfully</h3>

                    <div class="col-8">
                        <table id="recharge_resp-table" class="table" style="font-size: 18px">

                            <tr>

                                <th class="text-red">
                                    Name
                                </th>
                                <th class="text-red">
                                    <span class="colon-algin">:</span>
                                </th>
                                <td id="">{{request()->input('first_name')}} {{request()->input('last_name')}} </td>
                            </tr>
                            <tr>
                                <th class="text-red">
                                    Mobile
                                </th>
                                <th class="text-red">
                                    <span class="colon-algin">:</span>
                                </th>
                                <td id="">{{request()->input('mobile_no')}} </td>
                            </tr>
                            <tr>
                                <th class="text-red">
                                    Email 
                                </th>
                                <th class="text-red">
                                    <span class="colon-algin">:</span>
                                </th>
                                <td id="">{{request()->input('email_id')}}</td>
                            </tr>
                            <tr>
                                <th class="text-red">
                                    Business Name
                                </th>
                                <th class="text-red">
                                    <span class="colon-algin">:</span>
                                </th>
                                <td id="">{{request()->input('business_name')}}</td>
                            </tr>




                        </table>
                    </div>
                </center>
            </div>
            <div class="modal-footer" style="justify-content: center;">
            <a href="/home">  <button type="button" class="btn btn-primary success-grad btn-lg" id="recharge_ok">Close</button></a>
            </div>
        </div>
    </div>
</div>
<!-- endsuccessmodal -->


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="https://paymamaapp.in/template_assets/other/js/bootstrap-toggle.min.js"></script>
<script src="{{ asset('template_assets\other\js\angular.min.js') }}"></script>
<script src="{{ asset('dist/user/js/addUser.js') }}"></script>
<script src="{{ asset('dist/user/js/ngAddEditUser.js') }}"></script>

<script>
    $(document).ready(function() {
        var success = $('#success').val();
        console.log(success)
        console.log('fos created')
        if (success == 1) {
            $('#staticBackdrop').modal('show')
            $('#success').val(0);
        }

    });
</script>


<script>
    $('#mobilenumber').on('keyup keydown change', function(e) {
        console.log($(this).val() > 999999999)
        if ($(this).val() > 999999999 &&
            e.keyCode !== 46 &&
            e.keyCode !== 8
        ) {
            e.preventDefault();
            $(this).val(this.value);
        }
    })
</script>
@endsection