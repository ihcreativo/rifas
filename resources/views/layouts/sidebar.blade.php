    <div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar">
        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="nav-item theme-logo">
                    <a href="javascript:void(0)">
                        <img src="{{ asset('img/favicon.png') }}" alt="logo">
                    </a>
                </div>
                <div class="nav-item theme-text">
                    <a href="javascript:void(0)" class="nav-link morfee-title">Kakebo</a>
                </div>
            </div>
            
            <div class="nav-item sidebar-toggle">
            
                <div class="btn-toggle sidebarCollapse">
                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
                </div>
            </div>
        </div>
        
        <div class="profile-info">
            <div class="user-info">
                <div class="profile-img">
                    @if(auth()->user()->img == 'none.png')
                        <img alt="avatar" src="{{ asset('img/iuser.png' ) }} ">
                    @else      
                        <img alt="avatar" src="{{ asset('img/iuser.png' ) }} ">{{-- <img alt="avatar" src="{{ asset('img/ auth()->user()->img') }}">     --}}
                    @endif
                </div>
                <div class="profile-content">
                    
                    <h6 class="">{{ auth()->user()->firts_name}} {{auth()->user()->last_name }}</h6>
                    <p class="">
                        @if (!empty(auth()->user()->cliente))
                            {{auth()->user()->cliente->cliente}}
                        @endif
                    </p>
                    <p>
                        @if (!empty(auth()->user()->rol))
                            {{ auth()->user()->rol->rol}}
                        @endif
                    </p>
                </div>
            </div>
        </div>             
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories">
            @include("layouts/menu_".auth()->user()->rol_id) 
        </ul>
    </nav>
</div>