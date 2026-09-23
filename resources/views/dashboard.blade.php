@auth
    @extends('layouts.app_normal')
    @section('style')
    <style>
        .linea_0{background:linear-gradient(to right,#FF7518,#FFB90C)}
        .linea_1{background:linear-gradient(to right,#77DEFF,#869AF1)}
        .linea_2{background:linear-gradient(to right,#94F23C,#CAFF8B)}
        .linea_3{background:linear-gradient(to right,#de8fbd,#f56cb3)}
        .linea_4{background:linear-gradient(to right,#05eed7,#f56cb3)}
        .linea_5{background:linear-gradient(to right,#5063D9,#9eace6)}
        .linea_6{background:linear-gradient(to right,#e9600c,#ee945b)}
        .linea_7{background:linear-gradient(to right,#ef57ba,#eba2ca)}
        .linea_8{background:linear-gradient(to right,#98928f,#edd1bf)}
        .linea_9{background:linear-gradient(to right,#4a6126,#91e787)}
        .linea_10{background:linear-gradient(to right,#a7c7d9,#b5bbbc)}
        .linea_11{background:linear-gradient(to right,#f24191,#f2a0d0)}
        .linea_12{background:linear-gradient(to right,#244902,#cdf2ed)}
        .linea_13{background:linear-gradient(to right,#2d2f2f,#54504e)}
    </style>
    @endsection
    @section('titulo')
        KAKEBO - iShevi
    @endsection
    @section('contenido') 
        <div class="container-fluid"> 
            <dashboard path="{{route('login.index')}}"></dashboard>
        </div>
    @endsection
@endauth


