@extends('layouts.full_new')
@section('page_content')

<div class="page-content container-fluid">
    @if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>SUCCESS</strong> {{ Session::get('success') }}
        {{ Session::forget('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @elseif(Session::has('error'))

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>FAILED</strong> {{ Session::get('error') }}
        {{ Session::forget('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div id="loader"></div>
    <div class="row" style="margin-left:20px;">
        <div class="col-7">
            <div class="row">
                <div class="card card-body">
                    <h3 class="card-title">Distributor Info</h3>
                    <hr style="border:1px solid red" />
                    <div class="col-12">
                       
                        <table class="table table-striped" >
                        <tr>
                                <th>Username</th>
                                <td>{{$dist->username}}</td>
                            </tr>
                            <tr>
                                <th>First Name</th>
                                <td>{{$dist->first_name}}</td>
                            </tr>
                            <tr>
                                <th>Last Name</th>
                                <td>{{$dist->first_name}}</td>
                            </tr>
                            <tr>
                                <th>Store Name</th>
                                <td>{{$dist->store_name}}</td>
                            </tr>
                            <tr>
                                <th>Mobile</th>
                                <td>{{$dist->mobile}}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{$dist->email}}</td>
                            </tr>
                        </table>
                        <style>
                            label {
                                font-size: 20px;
                            }
                        </style>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </section>

</div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script>
    $("#mobileNumber").on("keyup", function() {
        var inputvalues = $(this).val();
        var regex = /([0-9]){10}$/;
        if (!regex.test(inputvalues)) {
            warning('mobileNumber');
            return regex.test(inputvalues);
        } else {
            success('mobileNumber');
        }
    });

    function success(span) {
        $("#" + span + "invalid").remove();
        $("#" + span + "valid").remove();
        $('#' + span).after('<i class="info fa fa-check-circle" id="' + span + 'valid" style="color:green;position: absolute;top: 50%;right: -15px;transition: right 0.2s;font-size: 20px;"></i>');
    }

    function warning(span) {
        $("#" + span + "valid").remove();
        $("#" + span + "invalid").remove();
        $('#' + span).after('<i class="info fa fa-exclamation-triangle" id="' + span + 'invalid" style="color:red;position: absolute;top: 50%;right: -15px;transition: right 0.2s;font-size: 20px;"></i>');
    }
</script>
@endsection