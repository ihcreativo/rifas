<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title> IshevI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="img/favicon.png "/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user" content="{{ Auth::user() }}"> 
    {{-- <script src="{{asset('build/assets/app-dab06c43.js')}}"> --}}
        
    </script>
    @vite(['resources/js/app.js'])
    <link href="{{ asset('assets-cork/layouts/modern-light-menu/css/light/loader.css') }} " rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets-cork/layouts/modern-light-menu/css/dark/loader.css') }} " rel="stylesheet" type="text/css" />
    <script src="https://kit.fontawesome.com/58a25a80e0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('assets-cork/src/bootstrap/css/bootstrap.min.css') }} " rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets-cork/layouts/modern-light-menu/css/light/plugins.css') }} " rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets-cork/layouts/modern-light-menu/css/dark/plugins.css') }} " rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets-cork/src/assets/css/light/components/modal.css') }} " rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets-cork/src/assets/css/dark/components/modal.css') }} " rel="stylesheet" type="text/css" />
    <script src="{{asset('js/listado2.js') }}"></script>
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}"> --}}
    <style>
        .logo {margin:10% auto; width: 38%; display: flex; vertical-align: middle;}
        label{display: block}
        @media only screen and (max-width: 1200px)  {.degradadoX{display:none;}}
        @media only screen and (min-width: 1201px)  {.degradadoX{background:radial-gradient(#FFFFFF, #d8d5d5);}}       
        </style>
{{-- <script src="{{ asset('build/assets/app-dab06c43.js') }}" defer></script> --}}
</head>
<body class="p-0 m-0">
    <div class="container-fluid p-0 m-0" id="app">
        <asistencia_admin path="{{route('login.index')}}"></asistencia_admin>
    </div>
</body>
</html>
