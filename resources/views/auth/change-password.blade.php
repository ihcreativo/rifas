@extends('layouts.app_normal')

@section('titulo')
    Registrate 
@endsection
@section('style')
    <style scoped>
        .loading {opacity:0.45; pointer-events:none !important}
        .colmin {width: 1%; white-space: nowrap}
        .df-ajuste {white-space: normal !important}
        .df-hand {cursor: pointer}
        .df-raya {text-decoration: line-through}
    </style>
@endsection
@section('contenido')
    <div class="container">
        <div class="card-body">
            <div class="widget bg-white px-4 py-4">
                <div class="row">
                    <div class="col-12">
                        <div class="panel panel-default">
                            <div class="d-flex justify-content-between mb-4">
                                <div>
                                    <h5 class="txt-dark mb-0"><strong>MODIFICAR PASSWORD</strong><br></h5>
                                    <div style="letter-spacing:3px; color:#555; font:12px Arial">
                                         Usuario :  {{ auth()->user()->firts_name.' '.auth()->user()->last_name }}
                                    </div>
                                </div>
                            </div>
                
                            <div class="panel-body">
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if($errors)
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger">{{ $error }}</div>
                                    @endforeach
                                @endif
                                <form class="form-horizontal" method="POST" action="{{ route('changePasswordPost') }}">
                                    {{ csrf_field() }}
                                    @csrf
                                    
                                    <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                        <label for="new-password" class="col-md-4 control-label">Password actual</label>

                                        <div class="col-md-6">
                                            <input id="current-password" type="password" class="form-control" name="current-password" required>

                                            @if ($errors->has('current-password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('current-password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }} mt-3">
                                        <label for="new-password" class="col-md-4 control-label">Nuevo Password</label>
                                        <div class="col-md-6">
                                            <input id="new-password" type="password" class="form-control" name="new-password" required>
                                            @if ($errors->has('new-password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('new-password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="new-password-confirm" class="col-md-4 control-label">Confirmar nuevo Password</label>

                                        <div class="col-md-6">
                                            <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>
                                        </div>
                                    </div>

                                    <div class="form-group mt-4">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                Cambiar Password
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
        </div>  
    </div>
@endsection
