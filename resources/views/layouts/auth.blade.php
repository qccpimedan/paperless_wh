<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Paperless QC-WH</title>
    

    <link href="{{asset('dist/css/fonts.css')}}" rel="stylesheet">
   
    <link rel="stylesheet" href="{{asset('dist/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('dist/vendors/bootstrap-icons/bootstrap-icons.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/pages/auth.css')}}">
    <link rel="icon" href="{{asset('dist/images/logo/logo5.png')}}" type="image/x-icon">
</head>

<body>
    <div id="auth">
        @yield('container')
    </div>

    <!-- Script JS -->
    <script src="{{asset('dist/vendors/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('dist/js/main.js')}}"></script>
</body>

</html>