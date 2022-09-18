@extends('layouts.full')

@section('page_content')

<section>
    
   
    
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <h4 class="card-title"> User</h4>
               @if(isset($user_info))
               <input type="hidden" name="user_id" id="user_id" value="{{ isset($user_info->userId) ? $user_info->userId : '' }}">
               <form method="post" action="{{ route('update_subadmin',isset($user_info->userId) ? $user_info->userId : '') }}" id="editUserSAForm">
               
               @else

                <form method="post" action="{{ route('store_subadmin') }}" id="addUserSAForm">
               @endif
                
                @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ isset($user_info)? $user_info->first_name : '' }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ isset($user_info->last_name)? $user_info->last_name : '' }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="mobile">Mobile Number</label>
                                       
                                        <input type="number" class="form-control" id="mobile" name="mobile" value="{{ isset($user_info->mobile)? $user_info->mobile : '' }}" >
                                        
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="last_name">Email </label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ isset($user_info->email)? $user_info->email : '' }}">
                                    </div>
                                </div>
                                
                            </div>      

                                
                           
                            
                        </div>
                        <div class="col-md-6">                            

                           

                            <div class="row">                            

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="whatsapp_no">Whatsapp No.</label>
                                        <input type="text" class="form-control" id="whatsapp_no" name="whatsapp_no" value="{{ isset($user_info->whatsapp_no)? $user_info->whatsapp_no : '' }}">
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="alternate_mob_no">Alternate Number</label>
                                        <input type="number" class="form-control" id="alternate_mob_no" name="alternate_mob_no" value="{{ isset($user_info->alternate_mob_no)? $user_info->alternate_mob_no : '' }}">
                                    </div>
                                </div>
                            </div>

                           

                           
                                                
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success mr-2"> {{ isset($user_info)? 'Update': 'Submit' }}  </button>
                           
                                <a type="button" href="{{ route('user_list') }}" class="btn btn-dark">Cancel</a>
                            
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('template_assets\other\js\angular.min.js') }}"></script>
<script src="{{ asset('dist/user/js/addUser.js') }}"></script>

<script src="{{ asset('dist/user/js/ngAddEditUser.js') }}"></script>
@endsection
