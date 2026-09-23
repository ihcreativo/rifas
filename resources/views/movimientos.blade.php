@auth
    @extends('layouts.app_normal')
    @section('titulo')
        KAKEBO - iShevi
    @endsection
    @section('contenido') 
        <div class="container-fluid"> 
            <movimientos path="{{route('login.index')}}"></movimientos>
        </div>
    @endsection
@endauth


