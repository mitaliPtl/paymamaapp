<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('template_assets/assets/images/favicon_sm_py.png') }}">
    <title>SMARTPAY - Making India Digital</title>
    <link rel="stylesheet" href="{{ asset('template_assets\other\mdb\css\bootstrap.min.css') }}">

</head>
<body class="container" style="background:url(template_assets/assets/images/big/auth-bg.jpg) no-repeat center center;margin-top:10%">
    
    <form action="{{ route('check-otp') }}" method="post">
    @csrf
        <div class="row">
            <div class="col-md-4 col-sm-0"></div>
            <div class="col-md-4 col-sm-12 text-center">
               
                <div class="form-group card" style="padding:30px">
                    <img class="mt-2" src="{{ asset('template_assets/assets/images/logos/logo-text-flat.png') }}" style="width:50%;margin-left:25%">
                    <label for="otp" style="font-weight:500;font-size:12px">Device Verification PIN</label>
                    <input type="text" class="form-control mb-2 text-center" maxlength="6" name="otp" autocomplete="off" required placeholder="6-digit PIN">
                    <button type="submit" class="btn btn-md btn-info">Verify</button>
                    <div class="progress" style="height:5px">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%;background-color:#207b8a"></div>
                    </div>
                    <div style="font-size:12px">
                        Verify to get the Best Experience
                    <!-- We just sent your authentication code via SMS to your Mobile No. -->
                    </div>
                    
                    <!-- <a href="{{ route('admin-home') }}" style="font-size:12px;color:blue" class="text-left">Re-send the code</a> -->
                    <div class="text-danger text-sm" style="font-size:12px">
                        {{ session('message') ? session('message') : ''}}
                    </div>
                </div>
                                
            </div>
            <div class="col-12 text-center">
                <a href="{{ route('logout') }}" class="btn btn-danger btn-md"><i class="fa fa-power-off"></i> Logout</a>
            </div>
        </div>
    </form>
</body>
<script src="{{ asset('template_assets\other\mdb\js\jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
</html>
