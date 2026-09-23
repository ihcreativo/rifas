@auth
    @extends('layouts.app_normal')
    @section('titulo')
        CONFIGURACION - KAKEBO
    @endsection
    @section('contenido') 
        <div class="container-fluid"> 
            <configuracion path="{{route('login.index')}}"></configuracion>
        </div>
    @endsection
@endauth


