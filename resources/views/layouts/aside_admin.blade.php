<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index3.html" class="brand-link">
        <img src="{{ Vite::asset('resources/images/adminltelogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Vite::asset('resources/images/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('form_submissions.index') }}" class="nav-link">
                        <i class="nav-icon fa-brands fa-wpforms"></i>
                        <p>
                            Formularios de usuarios
                        </p>
                    </a>
                </li>
                @if (auth()->check() && auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('provinces.index') }}" class="nav-link">
                            <i class="nav-icon fa-solid fa-earth-americas"></i>
                            <p>
                                Provincias
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('provinces.index') }}" class="nav-link">
                            <i class="nav-icon fa-solid fa-map-location"></i>
                            <p>
                                Zonas
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('provinces.index') }}" class="nav-link">
                            <i class="nav-icon fa-solid fa-location-dot"></i>
                            <p>
                                Localidades
                            </p>
                        </a>
                    </li>
                @endif

                @auth
                    <li class="nav-item">
                        <a href="{{ route('logout') }}" class="nav-link"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="nav-icon fa-solid fa-right-from-bracket"></i>
                            <p>
                                Cerrar Sesión
                            </p>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @endauth
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
