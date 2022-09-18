{{-- @extends('layouts.full') --}}
@extends('layouts.full_new')
@section('page_content')



<div class="page-content container-fluid">
    <link rel="stylesheet" type="text/css" href="https://paymamaapp.in/template_assets/other/css/bootstrap-toggle.min.css">



    <div class="row">

        <div class="col-6">
            <div class="card">
                <div class="px-3 py-2  ">
                    <h4 class="font-weight-bolder">ADD NEW FOS</h4>
                </div>

                <hr style="1px dotted red">
                <h4 class="text-danger mx-5">{{$error ?? ''}}</h4>


                <div class="card-body">
                    <form method="POST" action="/store_new_fos" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" required class="form-control" name="parent_role_id" value="{{Auth::user()->roleId}}">
                        <input type="hidden" required class="form-control" name="parent_user_id" value="{{Auth::user()->userId}}">

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" required class="form-control" name="name" value="{{request()->input('name')}}">
                        </div>

                        <div class="form-group">
                            <label for="name">Mobile Number</label>
                            <input type="number" id="mobilenumber" required class="form-control" name="mobile" value="{{request()->input('mobile')}}">
                        </div>

                        <div class="form-group">
                            <label for="name">Email</label>
                            <input type="email" required class="form-control" name="email" value="{{request()->input('email')}}">
                        </div>


                        <div class="form-group">
                            <label for="name">Aadhar Number</label>
                            <input type="text" required class="form-control" name="aadhar" value="{{request()->input('aadhar')}}">
                        </div>

                        <div class="form-group">
                            <label for="name">Pan Number</label>
                            <input type="text" required class="form-control" name="pan" value="{{request()->input('pan')}}">
                        </div>

                        <button type="submit" class="btn btn-lg success-grad " style=" background-image: linear-gradient(to right, #251c63 , #dc182d);
     color: white;
    border-color: #ffffff;">Submit</button>
                    </form>
                </div>
            </div>
        </div>


    </div>

</div>

<div class="container">
    <input type="hidden" id="success" value="{{$success ?? 0 }}">
</div>



<!-- Successmodal -->
<!-- Modal -->

<!-- <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2 class="font-weight-bold">FOS CREATED SUCCESSFULLY </h3>
            </div>

        </div>
    </div>
</div> -->

<!-- endsuccessmodal -->

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
                    <h3 class="text-success" id="">Fos Created Successfully</h3>

                    <div class="col-8">
                        <table id="recharge_resp-table" class="table" style="font-size: 18px">

                            <tr>

                                <th class="text-red">
                                    Name<span class="colon-algin">:</span>
                                </th>
                                <td id="">{{request()->input('name')}} </td>
                            </tr>
                            <tr>
                                <th class="text-red">
                                    Mobile<span class="colon-algin">:</span>
                                </th>
                                <td id="">{{request()->input('mobile')}} </td>
                            </tr>
                            <tr>
                                <th class="text-red">
                                    Email <span class="colon-algin">:</span>
                                </th>
                                <td id="">{{request()->input('email')}} </td>
                            </tr>




                        </table>
                    </div>
                </center>
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <a href="/distributor_fos_list"> <button type="button" class="btn btn-primary success-grad btn-lg" id="recharge_ok">Close</button></a>
            </div>
        </div>
    </div>
</div>


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
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\user\js\userList.js') }}"></script>



<script>
    $(document).ready(function() {
        var success = $('#success').val();
        if (success == 1) {
            $('#staticBackdrop').modal('show')
            $('#success').val(0);
        }

    });
</script>


<script>
    $('#mobilenumber').on('keyup keydown change', function(e) {
        if ($(this).val() > 999999999 &&
            e.keyCode !== 46 &&
            e.keyCode !== 8
        ) {
            e.preventDefault();
            $(this).val(this.value);
        }
    })
</script>


</div>




@endsection