<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('template_assets/assets/images/favicon_sm_py.png') }}">
        <title>SMARTPAY - Making India Digital</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                /* background-color: black; */
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                /* height: 100vh; */
                height: 5110px;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 70px;
                color:white;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body style="background:url({{url('template_assets/assets/images/home.png')}}) no-repeat;">
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{  Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN') ? url('/admin-home') : url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>

                        <!-- @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif -->
                    @endauth
                </div>
            @endif

            <!-- <div class="content">
                <div class="title m-b-md">
                    Coming Soon
                </div>
                <div>
                    <p style="color:white">Our website is currently undergoing development process
					Something interesting is coming very soon.</p>
                </div>
            </div> -->
        </div>
    </body>
</html>
