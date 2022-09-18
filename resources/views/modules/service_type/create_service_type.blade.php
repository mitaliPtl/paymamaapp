@extends('layouts.full')

@section('page_content')

<section>
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <h4 class="card-title">Add New Service Type</h4>

                <form method="post" action="{{ route('create_service_type') }}" id="addServiceTypeForm">
                @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="service_name">Service Name</label>
                                <input type="text" class="form-control" id="service_name" name="service_name">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="username">Description</label>
                                <textarea type="text" class="form-control" id="service_dtls" name="service_dtls"></textarea>
                            </div>

                                                        
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success mr-2">Submit</button>
                            <button type="button" class="btn btn-dark">Cancel</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist/user/js/addServiceType.js') }}"></script>
@endsection