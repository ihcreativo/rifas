@auth
    @extends('layouts.app_normal')
    @section('titulo')
        KAKEBO - iShevi
    @endsection
    @section('contenido') 
        <div class="container-fluid"> 
            
            <usuarios path="{{route('login.index')}}"></usuarios>
        </div>
    @endsection
@endauth


