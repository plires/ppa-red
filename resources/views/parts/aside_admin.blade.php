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

                {{-- Formularios --}}
                <li class="nav-item">
                    <a href="{{ route('form_submissions.index') }}" class="nav-link">
                        <i class="nav-icon fa-brands fa-wpforms"></i>
                        <p>
                            Formularios de usuarios
                        </p>
                    </a>
                </li>
                {{-- Formularios end --}}

                @if (auth()->check() && auth()->user()->role === 'admin')
                    {{-- Provicincias --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ Route::is('provinces.index') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-earth-americas"></i>
                            <p>
                                Provincias
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('provinces.index') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-list-ul"></i>
                                    <p>
                                        Listar
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('provinces.create') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-plus"></i>
                                    <p>
                                        Agregar
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('provinces.trashed') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-trash-can-arrow-up"></i>
                                    <p>
                                        Restaurar
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Provicincias end --}}

                    {{-- Zonas --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ Route::is('zones.index') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-map-location-dot"></i>
                            <p>
                                Zonas
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('zones.index') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-list-ul"></i>
                                    <p>
                                        Listar
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('zones.create') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-plus"></i>
                                    <p>
                                        Agregar
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('zones.trashed') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-trash-can-arrow-up"></i>
                                    <p>
                                        Restaurar
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Zonas end --}}

                    {{-- Localidades --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ Route::is('localities.index') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-location-dot"></i>
                            <p>
                                Localidades
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('localities.index') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-list-ul"></i>
                                    <p>
                                        Listar
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('localities.create') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-plus"></i>
                                    <p>
                                        Agregar
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('localities.trashed') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-trash-can-arrow-up"></i>
                                    <p>
                                        Restaurar
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Localidades end --}}

                    {{-- Partners --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ Route::is('partners.index') ? 'active' : '' }}">
                            <i class="nav-icon fa-regular fa-handshake"></i>
                            <p>
                                Partners
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('partners.index') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-list-ul"></i>
                                    <p>
                                        Listar
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('partners.create') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-plus"></i>
                                    <p>
                                        Agregar
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('partners.trashed') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-trash-can-arrow-up"></i>
                                    <p>
                                        Restaurar
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Partners end --}}
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
