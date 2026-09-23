
<li class="menu {{ ! Route::is('dashboard') ?: 'active'}}">
    <a href="{{route('dashboard')}}" aria-expanded="false" class="dropdown-toggle">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            <span>DashBoard</span>
        </div>
    </a>
</li>
<li class="menu {{ ! Route::is('movimientos') ?: 'active'}}">
    <a href="{{route('movimientos')}}" aria-expanded="false" class="dropdown-toggle">
        {{-- {{ ! Route::is('dashboard') ?: 'text-white align-middle'}} --}}
        <div>
            <i class="fa-solid fa-list-check fs-6 {{ Route::is('movimientos')? 'text-white':'text-dark'}}"></i>
           <span class="pb-2">Movimientos</span>
        </div>
    </a>
</li>
<li class="menu {{ ! Route::is('setting') ?: 'active'}}">
    <a href="{{route('setting')}}" aria-expanded="false" class="dropdown-toggle">
        <div>
            <i class="fa-solid fa-gear fs-6 aling-middle {{ Route::is('setting')? 'text-white':'text-dark'}}"></i>
            <span class="pb-2">Configuracion</span>
        </div>
    </a>
</li>
   
{{-- <li class="menu {{ ! Route::is('users','usuariosAdd,','usuarioX')?: 'badge-light-primary rounded-2 mx-3'}}">
    <a href="{{route('users')}}" aria-expanded="false" class="dropdown-toggle">
        <div class="">
            <img src="{{ asset('img/ico/users.png')}}" width="24px">
            <span>Usuarios</span>
        </div>
    </a>
</li> --}}


{{-- <li class="menu {{ ! Route::is('dashboard') ?: 'badge-light-primary rounded-2 mx-3'}}">
    <a href="{{route('dashboard')}}" aria-expanded="false" class="dropdown-toggle">
        <div class="">
            <img src="{{ asset('img/ico/home.png')}}" width="24px">
            <span>Inicio</span>
        </div>
    </a>
</li> --}}

