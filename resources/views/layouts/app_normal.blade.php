<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user" content="{{ Auth::user() }}"> 
    <title> @yield('titulo') </title>
    
    
    <link rel="icon" type="image/x-icon" href="{{ asset( 'assets-cork/src/assets/img/favicon.ico') }} "/>
    <link href="{{ asset('assets-cork/layouts/modern-light-menu/css/light/loader.css') }} " rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets-cork/layouts/modern-light-menu/css/dark/loader.css') }} " rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets-cork/layouts/modern-light-menu/loader.js') }} "></script>
    <script src="https://kit.fontawesome.com/58a25a80e0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('assets-cork/src/bootstrap/css/bootstrap.min.css') }} " rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets-cork/layouts/modern-light-menu/css/light/plugins.css') }} " rel="stylesheet" type="text/css" />
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

<body class="{{Route::is('result_query','periodo','empresarial','impacto','dashboard','aunsenteX','importar','ubicacion') ? 'alt-menu layout-boxed':'layout-boxed'}}">
    <!-- BEGIN LOADER -->
    <div id="load_screen"> 
        <div class="loader"> 
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->
     <!--  BEGIN NAVBAR  -->
     @include("layouts/header") 
    <!--  END NAVBAR  -->
    <div class="main-container" id="container">
        <div class="overlay"></div>
        <div class="cs-overlay"></div>
        <div class="search-overlay"></div>
        <!--  BEGIN SIDEBAR  -->
        @include("layouts/sidebar") 
        <!--  END SIDEBAR  -->
        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="middle-content container-xxl p-0">
                    <div class="row layout-top-spacing" id="app">
                        @yield('contenido')
                    </div>
                </div>
            </div>
            <!--  BEGIN FOOTER -->
            @include('layouts/footer')
            <!--  END FOOTER  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    @yield('outcontent')  
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS --> --}}

    {{-- 
    <script src="{{ asset('/plugins/src/highlight/highlight.pack.js')}}"></script>
    <script src="{{ asset('/plugins/js/bootstrap.bundle.min.js')}}"></script>
    --}}
    {{-- <script src="{{ asset('/plugins/src/global/vendors.min.js')}}"></script> --}}
    <script src="{{ asset('/plugins/src/waves/waves.min.js')}}"></script>
    <script src="{{ asset('/plugins/src/mousetrap/mousetrap.min.js')}}"></script>
    
    <script src="{{ asset('/js/jquery.min.js')}}"></script>
    <script src="{{ asset('/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script> 
    {{-- dev --}}
    @vite(['resources/js/app.js'])
    
   
    {{-- fin dev --}}
    {{-- produccion --}}
    {{-- <script src="/build/assets/app.js"></script> --}}
    {{-- fin proction --}}
    <script src="{{ asset('/assets-cork/layouts/modern-light-menu/app.js')}}"></script>
    {{-- <script src="{{asset('/assets-cork/src/bootstrap/js/bootstrap.bundle.min.js')}}"></script> --}}
    
    {{-- <script src="../layouts/modern-light-menu/app.js"></script> --}}
    {{-- <script src="{{ asset('/js/bootstrap.bundle.min.js')}}"></script> --}}
</body>
</html>


