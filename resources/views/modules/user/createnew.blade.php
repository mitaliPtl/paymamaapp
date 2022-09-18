@extends('layouts.full_new')
@section('page_content')
    <div class="page-content container-fluid">
        <div class="card card-body" style="width:50%;">
            <h2 style="font-weight:700">MEMBER REGISTRATION</h2>
             <hr style="1px dotted red">
            <!--<form action="{{ route('signUpUserbyAdmin') }}" method="post">-->
                <!--@csrf-->
                <div class="row">
                    <div class="col-6 form-group">
                        <label for="first_name">ENTER FIRST NAME</label>
                        <input type="text" class="form-control" id="first_name" name="first_name">
                    </div>
                    <div class="col-6 form-group">
                        <label for="last_name">ENTER LAST NAME</label>
                        <input type="text" class="form-control" id="last_name" name="last_name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 form-group">
                        <label for="first_name">ENTER MOBILE NO</label>
                        <input type="text" maxlength="10" class="form-control" id="mobile_no" name="mobile_no">
                    </div>
                    <div class="col-6 form-group">
                        <label for="last_name">ENTER EMAIL ID</label>
                        <input type="email" class="form-control" id="email_id" name="email_id">
                    </div>
                </div>
                <div class="form-group">
                    <label for="aadhar_no">SELECT USERTYPE</label>
                    <select class="form-control" id="role_id" name="role_id">
                        @foreach($allRoles as $role)
                            <option value="{{ $role->roleId}}" data-alias="{{ $role->alias }}">{{ $role->role}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="parent_role_id">Parent User Type</label>
                    <select class="form-control custom-select" id="parent_role_id" name="parent_role_id">
                    </select>
                </div>
                <div class="form-group">
                    <label for="aadhar_no">SELECT PARENT USER</label>
                    <select name="parent_user_id" class="form-control" id="parent_user_id">
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="aadhar_no">SELECT PACKAGE</label>
                    <select name="package_id" class="form-control" id="package_id">
                        <option selected disabled>Select</option>
                        @foreach($allPackages as $package)
                            <option value="{{ $package->package_id}}">{{ $package->package_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button type="button" id="sendSms" class="btn btn-primary">SEND SMS</button>
                </div>
            <!--</form>-->
        </div>
    </div>
    <div id="errorModal" class="modal" tabindex="-1" role="document">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="text-align: center;padding-left: 20px;padding-right: 20px;">
                    <i class="fas fa-times text-danger" style="font-size: 100px;line-height: 1.1;margin-top: 20px;display: inline-block !important;"></i>
                    <h2>Error!</h2>
                    <h4 id="errorMsg">Unable to Update</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="successModal" class="modal" tabindex="-1" role="document">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="text-align: center;padding-left: 20px;padding-right: 20px;">
                    <i class="far fa-check-circle text-success" style="font-size: 100px;line-height: 1.1;margin-top: 20px;margin-bottom: 20px;display: inline-block !important;"></i>
                    <h2>Success!</h2>
                    <h4 id="successMsg">KYC Updated successful</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" ></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script>
  $(function() {
    $('#role_id').change(function() {
        $("#parent_role_id").empty();
        var role_id = $('#role_id').val(); 
        $.ajax({
              url: "{{ url('checkbottomrole') }}",
              method: 'get',
              data: { role: role_id },
              success: function(res) {
                 var result = res.message;
                 for(var x=0; x < result.length; x++) {
                     $("#parent_role_id").append('<option value="'+result[x]['id']+'" name="days[]">'+result[x]['name']+'</option>');
                  }
              }
        });
    })
    
    $('#parent_role_id').change(function() {
        $("#parent_user_id").empty();
        var role_id = $('#parent_role_id').val(); 
        $.ajax({
             url: "{{ url('checkperrole') }}",
             method: 'get',
             data: { role: role_id, source: 1},
             success: function(res) {
                 var result = res.message;
                 for(var x=0; x < result.length; x++) {
                     $("#parent_user_id").append('<option value="'+result[x].id+'" name="days[]">'+result[x].name+'</option>');
                 }
              }
        });
    })
  })
</script>
<script>
$(document).ready(function () {
    $(document).on("click", "#sendSms", function() {
        $('.preloader').css("display", "block");
        var first_name = $('input[id=first_name]').val();
        var last_name = $('input[id=last_name]').val();
        var mobile_no = $('input[id=mobile_no]').val();
        var email_id = $('input[id=email_id]').val();
        var role_id = $('select[id=role_id]').val();
        var parent_role_id = $('select[id=parent_role_id]').val();
        var parent_user_id = $('select[id=parent_user_id]').val();
        var package_id = $('select[id=package_id]').val();
        var CSRF_TOKEN = '{{ csrf_token() }}';
        $.ajax({
            type:'POST',
            url: '{{ route("signUpUserbyAdmin") }}',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            dataType: "json",
            data: { _token: CSRF_TOKEN,first_name: first_name,last_name: last_name,mobile_no: mobile_no,email_id: email_id,role_id: role_id,parent_role_id: parent_role_id,parent_user_id: parent_user_id,package_id: package_id},
            success:function(resp) {
                $('.preloader').css("display", "none");
                if(resp.status) {
                    $('#successMsg').html(resp.message);
                    $('#successModal').modal('show');
                } else {
                    $('#errorMsg').html(resp.message);
                    $('#errorModal').modal('show');
                }
            },
            error:function(xhr) {
                var resp = JSON.parse(xhr.responseText);
                $('.preloader').css("display", "none");
                if(typeof(resp.message)!="undefined") {
                    $('#errorMsg').html(resp.message);
                }
                $('#errorModal').modal('show');
            }
        });
    });
});
</script>
    


<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="https://paymamaapp.in/template_assets/other/js/bootstrap-toggle.min.js"></script>
<script src="{{ asset('template_assets\other\js\angular.min.js') }}"></script>
<script src="{{ asset('dist/user/js/addUser.js') }}"></script>
<script src="{{ asset('dist/user/js/ngAddEditUser.js') }}"></script>
@endsection