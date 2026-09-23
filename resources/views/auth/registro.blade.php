@extends('layouts.app')

@section('titulo')
    Registrate 
@endsection

@section('contenido')
<div class="container-fluid">
    <div class="card-body">
        <div class="text-center my-3">
            <img src="img/logo_control_mini.png" alt="">
        </div>
        <div class="widget bg-white px-4 py-4">
            <div class="d-flex justify-content-between mb-4">
                <div>
                    <h5 class="txt-dark mb-0"><strong>REGISTRO</strong><br></h5>
                    <div style="letter-spacing:3px; color:#555; font:12px Arial">
                        Registrarse como un nuevo usuario
                    </div>
                </div>
            </div>    
            <div class="col-lg-12 col-sm-12 col-12 layout-spacing">                
                <form class="form" method="post" action="{{route('agregarUsuario')}}">
                    @csrf
                    <div class="">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group mb-3">
                                    <label>Usuario:</label>
                                    <input type="text"  name="username" class="form-control" placeholder="Usuario">
                                </div>
                                @error('username')
                                    <p class="badge-danger text-white my-2 rounded-lg text-sm p-2 text-center ">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group mb-3">
                                    <label>Nombre Completo:</label>
                                    <input type="text"  name="firts_name" class="form-control" placeholder="Nombre completo" value="{{old('firts_name')}}">
                                    @error('firts_name')
                                    <p class="badge-danger text-white my-2 rounded-lg text-sm p-2 text-center ">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group mb-3">
                                    <label>Apellidos:</label>
                                    <input name="last_name" type="text" class="form-control" placeholder="Apellidos" value="{{old('last_name')}}">
                                    @error('last_name')
                                        <p class="badge-danger text-white my-2 rounded-lg text-sm p-2 text-center ">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="exampleFormControlInput1">Correo:</label>
                                    <input name="email"  type="email" class="form-control" placeholder="Correo" value="{{old('email')}}">
                                    @error('email')
                                        <p class="badge-danger text-white my-2 rounded-lg text-sm p-2 text-center ">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                            
                            
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group mb-3">
                                    <label>Contraseña:</label>
                                    <input name="password" type="password" class="form-control" placeholder="Contraseña" value="{{old('password')}}">
                                </div>
                                @error('password')
                                    <p class="badge-danger text-white my-2 rounded-lg text-sm p-2 text-center ">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="exampleFormControlInput1">Confirmar Contraseña:</label>
                                    <input name="password_confirmation" type="password" class="form-control" placeholder="Confirmar Contraseña">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{route('login')}}" class="btn btn-light mt-2 mx-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
                                        </svg>
                                        Volver
                                    </a>
                                    <button type="submit" class="btn btn-primary mt-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 23 23">
                                            <path d="M11 2H9v3h2V2Z"/>
                                            <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0ZM1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5Zm3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4v4.5ZM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5V15Z"/>
                                        </svg>
                                        Guardar                        
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 
@endsection