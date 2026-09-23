<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user" content="{{ Auth::user() }}"> 
    <title> @yield('titulo') </title>
    
    <link rel="icon" type="image/x-icon" href="{{ asset( '/img/favicon.png') }} "/>
    <link href="{{ asset('assets-cork/layouts/modern-light-menu/css/light/loader.css') }} " rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets-cork/layouts/modern-light-menu/loader.js') }} "></script>
    <script src="https://kit.fontawesome.com/58a25a80e0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('assets-cork/src/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets-cork/layouts/modern-light-menu/css/light/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets-cork/layouts/modern-light-menu/css/dark/plugins.css') }} " rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets-cork/src/assets/css/light/components/modal.css') }} " rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets-cork/src/assets/css/dark/components/modal.css') }} " rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="{{ asset('assets-cork/src/assets/css/light/dashboard/dash_1.css') }} " rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets-cork/src/assets/css/dark/dashboard/dash_1.css') }} " rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->    

    <!-- css importado para configuraciones puntuales propias -->
    <link rel="stylesheet" href="{{ asset('assets-cork/src/assets/css/configuracion_base_css.css') }}">
    
    <script src="https://unpkg.com/feather-icons"></script>
    
     @yield('style')
</head>

<body class="p-0 m-0">
     <!-- BEGIN LOADER -->
     <div id="load_screen"> 
        <div class="loader"> 
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->
    <div class="m-0 p-0">
        <div class="m-0 pt-0" id="app">
            @yield('contenido')
        </div>
    </div>
    

    <!-- END MAIN CONTAINER -->
    @yield('outcontent')  
    <script src="{{ asset('/js/jquery.min.js')}}"></script>
    <script src="{{ asset('/js/bootstrap.min.js')}}"></script>
    {{-- dev --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- fin dev --}}
    {{-- produccion --}}
    {{-- <script src="/build/assets/app4.js"></script> --}}
    {{-- fin proction --}}
    
</body>
</html>

