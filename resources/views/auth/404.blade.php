<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Morfee - Ausentismo</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
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
            <div class="col-xl-12 col-lg-12 col-sm-12 col-xs-12 vh-100 degradadoX text-center">
                <div class="">
                    <img class="logo pt-1" src="{{ asset( 'img/logomediano.png')}}" style="width: 10%"/>												
                </div>
               
                @if($denegado)
                <div class="alert alert-danger text-center" role="alert">
                    {{ $denegado }}
                </div>
                @endif
                <a href="{{route('dashboard')}}" class="btn btn-primary">
                        Volver
                </a>
                
            </div>
        </div>  
    </div>
</body>
</html>
