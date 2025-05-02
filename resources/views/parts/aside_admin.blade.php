<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('form_submissions.index') }}" class="brand-link">
        <img src="{{ Vite::asset('resources/images/ppa-red.webp') }}" alt="logo PPA RED"
            class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">PPA RED</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Vite::asset('resources/images/circle-logo-partner.webp') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <span class="d-block brand-text font-weight-light">{{ auth()->user()->name }}</span>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                {{-- Formularios --}}
                <li class="nav-item">
                    <a href="{{ route('form_submissions.index') }}"
                        class="nav-link {{ Request::is('form_submissions*') ? 'active' : '' }}">
                        <i class="nav-icon fa-brands fa-wpforms"></i>
                        <p>
                            Formularios de usuarios
                        </p>
                    </a>

                </li>
                {{-- Formularios end --}}

                <div class="sidebarDivider"></div>

                @if (auth()->check() && auth()->user()->role === 'admin')
                    {{-- Provicincias --}}
                    <li class="nav-item {{ Request::is('provinces*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Request::is('provinces*') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-earth-americas"></i>
                            <p>
                                Provincias
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('provinces.index') }}"
                                    class="nav-link {{ Route::is('provinces.index') ? 'active' : '' }}">
                                    <i class="nav-icon fa-solid fa-list-ul"></i>
                                    <p>
                                        Listar
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('provinces.create') }}"
                                    class="nav-link {{ Route::is('provinces.create') ? 'active' : '' }}">
                                    <i class="nav-icon fa-solid fa-plus"></i>
                                    <p>
                                        Agregar
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('provinces.trashed') }}"
                                    class="nav-link {{ Route::is('provinces.trashed') ? 'active' : '' }}">
                                    <i class="nav-icon fa-solid fa-trash-can-arrow-up"></i>
                                    <p>
                                        Restaurar
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Provicincias end --}}

                    <div class="sidebarDivider"></div>

                    {{-- Zonas --}}
                    <li class="nav-item {{ Request::is('zones*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Request::is('zones*') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-map-location-dot"></i>
                            <p>
                                Zonas
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('zones.index') }}"
                                    class="nav-link {{ Route::is('zones.index') ? 'active' : '' }}">
                                    <i class="nav-icon fa-solid fa-list-ul"></i>
                                    <p>
                                        Listar
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('zones.create') }}"
                                    class="nav-link {{ Route::is('zones.create') ? 'active' : '' }}">
                                    <i class="nav-icon fa-solid fa-plus"></i>
                                    <p>
                                        Agregar
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('zones.trashed') }}"
                                    class="nav-link {{ Route::is('zones.trashed') ? 'active' : '' }}">
                                    <i class="nav-icon fa-solid fa-trash-can-arrow-up"></i>
                                    <p>
                                        Restaurar
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Zonas end --}}

                    <div class="sidebarDivider"></div>

                    {{-- Localidades --}}
                    <li class="nav-item {{ Request::is('localities*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Request::is('localities*') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-location-dot"></i>
                            <p>
                                Localidades
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('localities.index') }}"
                                    class="nav-link {{ Route::is('localities.index') ? 'active' : '' }}">
                                    <i class="nav-icon fa-solid fa-list-ul"></i>
                                    <p>
                                        Listar
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('localities.create') }}"
                                    class="nav-link {{ Route::is('localities.create') ? 'active' : '' }}">
                                    <i class="nav-icon fa-solid fa-plus"></i>
                                    <p>
                                        Agregar
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('localities.trashed') }}"
                                    class="nav-link {{ Route::is('localities.trashed') ? 'active' : '' }}">
                                    <i class="nav-icon fa-solid fa-trash-can-arrow-up"></i>
                                    <p>
                                        Restaurar
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Localidades end --}}

                    <div class="sidebarDivider"></div>

                    {{-- Partners --}}
                    <li class="nav-item {{ Request::is('partners*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Request::is('partners*') ? 'active' : '' }}">
                            <i class="nav-icon fa-regular fa-handshake"></i>
                            <p>
                                Partners
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('partners.index') }}"
                                    class="nav-link {{ Route::is('partners.index') ? 'active' : '' }}">
                                    <i class="nav-icon fa-solid fa-list-ul"></i>
                                    <p>
                                        Listar
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('partners.create') }}"
                                    class="nav-link {{ Route::is('partners.create') ? 'active' : '' }}">
                                    <i class="nav-icon fa-solid fa-plus"></i>
                                    <p>
                                        Agregar
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('partners.trashed') }}"
                                    class="nav-link {{ Route::is('partners.trashed') ? 'active' : '' }}">
                                    <i class="nav-icon fa-solid fa-trash-can-arrow-up"></i>
                                    <p>
                                        Restaurar
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Partners end --}}

                    <div class="sidebarDivider"></div>

                    {{-- Reportes --}}
                    <li class="nav-item {{ Request::is('reports*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Request::is('reports*') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-chart-column"></i>
                            <p>
                                Reportes
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('reports.index') }}"
                                    class="nav-link {{ Route::is('reports.index') ? 'active' : '' }}">
                                    <i class="nav-icon fa-solid fa-square-poll-vertical"></i>
                                    <p>
                                        Forms por Partner
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reports.status_chart') }}"
                                    class="nav-link {{ Route::is('reports.status_chart') ? 'active' : '' }}">
                                    <i class="nav-icon fa-solid fa-square-poll-vertical"></i>
                                    <p>
                                        Estado de Forms por Partner
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Reportes end --}}

                    <div class="sidebarDivider"></div>
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
