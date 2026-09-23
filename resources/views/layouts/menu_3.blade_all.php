<li class="menu {{ ! Route::is('dashboard') ?: 'active'}}">
    <a href="{{route('dashboard')}}" aria-expanded="false" class="dropdown-toggle">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            <span>Inicio</span>
        </div>
    </a>
</li>
@if(Route::is('dashboard','users','usuariosAdd','usuarioX','changePasswordGet','setting'))
<li class="menu {{ ! Route::is('users','usuariosAdd','usuarioX','changePasswordGet') ?: 'active'}}">
    <a href="{{route('users')}}" aria-expanded="false" class="dropdown-toggle">
        <div class="">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            <span>Usuarios</span>
        </div>
    </a>
</li>
<li class="menu {{ ! Route::is('setting') ?: 'active'}}">
    <a href="{{route('setting')}}" aria-expanded="false" class="dropdown-toggle">
        <div>
            <i class="fa-solid fa-gears"></i>
            <span>Configuración</span>
        </div>
    </a>
</li>
@else
<li class="menu {{ ! Route::is('ausentismo') ?: 'active'}}">
    <a href="{{route('ausentismo')}}" aria-expanded="false" class="dropdown-toggle">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            <span>Panel</span>
        </div>
    </a>
</li> 
<li class="menu menu-heading h-auto mt-3">
    <div class="heading py-0 my-0 mt-0 pt-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        <span>CARACTERIZACIONES</span>
    </div>
</li>
<li class="menu {{ !Route::is('personal') ?: 'active'}}">
    <a href="{{route('personal')}}" aria-expanded="false" class="dropdown-toggle">
        <div class="">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> 
            <span> Personal</span>
        </div>
    </a>
</li> 

<li class="menu {{ !Route::is('empresarial') ?: 'active'}}">
    <a href="{{route('empresarial')}}" aria-expanded="false" class="dropdown-toggle">
        <div class="">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                <path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>
                <polygon points="12 15 17 21 7 21 12 15"></polygon>
                
            </svg> 
            <span> Empresarial</span>
        </div>
    </a>
</li> 
<li class="menu {{ !Route::is('periodo') ?: 'active'}}">
    <a href="{{route('periodo')}}" aria-expanded="false" class="dropdown-toggle">
        <div class="">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg> 
            <span> Periodo de tiempo</span>
        </div>
    </a>
</li>

<li class="menu {{ !Route::is('ubicacion') ?: 'active'}}">
    <a href="{{route('ubicacion')}}" aria-expanded="false" class="dropdown-toggle">
        <div class="">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg>
            <span> Ubicación geográfica</span>
        </div>
    </a>
</li>

<li class="menu {{ !Route::is('impacto') ?: 'active'}}">
    <a href="{{route('impacto')}}" aria-expanded="false" class="dropdown-toggle">
        <div class="">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            <span> Impacto Económico</span>
        </div>
    </a>
</li>

<li class="menu menu-heading h-auto mt-3">
    <div class="heading py-0 my-0 mt-0 pt-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        <span>IMPORTACIONES</span>
    </div>
</li>
<li class="menu {{ ! Route::is('importar') ?: 'active'}}">
    <a href="{{route('importar')}}" aria-expanded="false" class="dropdown-toggle">
        <div class="">
            <i class="fa-solid fa-upload"></i>
            <span>Carga de archivos</span>
        </div>
    </a>
</li>

@endif
