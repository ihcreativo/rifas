<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title> IshevI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="img/favicon.png "/>
    @vite(['resources/scss/app.scss'])
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}"> --}}
    <style>
        .logo {margin:10% auto; width: 38%; display: flex; vertical-align: middle;}
        label{display: block}
        @media only screen and (max-width: 1200px)  {.degradadoX{display:none;}}
        @media only screen and (min-width: 1201px)  {.degradadoX{background:radial-gradient(#FFFFFF, #d8d5d5);}}       
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-7 col-lg-7 col-sm-12 col-xs-12 vh-100 degradadoX">
                <div class="">
                    <img class="logo pt-1" src="{{ asset( 'img/logo_panel.svg')}}"/>												
                </div>
            </div>
            <div class="col-xl-5 col-lg-5 col-sm-12 col-xs-12 px-5">	       
                <div class="form-wrap">
                    <form class="ui form" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mt-5 pt-5 mb-4 ">
                            <strong class="fs-1">Iniciar sesión</strong>
                            <p class="fs-6">Ingrese su usuario y contraseña para iniciar sesión</p>
                        </div>
                        <div>
                            @if(session('mensaje'))
                            <div class="alert alert-danger text-center" role="alert">
                                {{ session('mensaje') }}
                            </div>
                            @endif
                            <label for="username"  class="mb-2 block uppercase fs-5 fw-bold">
                                Usuario
                            </label> 
                            <input 
                                id="username"
                                name="username"
                                placeholder="Digita tu usuario"
                                type="text"
                                class="border p-3 w-100 fs-6 rounded-lg @error('username') border-red-500 @enderror"
                                value="{{ old('username')}}"
                            />
                            @error('username')
                                <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center ">
                                    {{ $message }}
                                </p>
                            @enderror   
                        </div>
    
                        <div class="mt-4">
                            <label for="password" class="mb-2 block uppercase fs-5 fw-bold">
                                Password
                            </label>
                            <input 
                                id="password"
                                name="password"
                                placeholder="Contraseña"
                                type="password"
                                class="border p-3 w-100 fs-6" 
                            
                            />
                            @error('password')
                                <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center ">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <!-- 
                            <input type="checkbox" name="remember"><label class="text-gray-500 text-sm">
                                Mantener mi sesion abierta
                            </label>
                        -->
                        <br>
                            <input class="btn btn-primary w-100 fs-5" 
                                type="submit"
                                value="Iniciar sesion"
                                class="ui blue button"
                            />
                    </form>
                </div>
                <div class="text-center mt-4 text-dark">
                    <a href="{{route('usuariosAdd')}}">
                       Deseo registrarme como usuario... !
                    </a>
                </div>
            
                <div class="d-flex align-items-center justify-content-center mt-3">			
                    <img src="{{ asset('img/logo.svg') }}" title="Ishevi.com"  height="23px">								
                    <!--<span class="ms-3" style="letter-spacing: 1px; font: 11px Arial;">Innovación Analítica S.A.S.</span>-->
                    &nbsp;&nbsp;
                </div>
                
            </div>
        </div>  
    </div>
</body>
</html>
