<x-app-layout>

    @section('css')
        <!-- DataTables -->
        @vite(['resources/css/dataTables.bootstrap4.min.css', 'resources/css/responsive.bootstrap4.min.css', 'resources/css/buttons.bootstrap4.min.css'])
    @endsection

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Detalle del Partner {{ $partner->name }}</h1>
                    </div>
                </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        {{-- Datos --}}
                        <div class="card card-secondary shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fa-solid fa-handshake"></i>
                                    <span class="ml-2">Partner {{ $partner->name }}</span>
                                </h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body" style="display: block;">
                                <dl>
                                    <dt>
                                        Nombre Completo
                                    </dt>
                                    <dd>
                                        {{ $partner->name }}
                                    </dd>
                                    <dt>
                                        Email
                                    </dt>
                                    <dd>
                                        {{ $partner->email }}
                                    </dd>
                                    <dt>
                                        Teléfono
                                    </dt>
                                    <dd>
                                        {{ $partner->phone }}
                                    </dd>
                                </dl>
                            </div>
                            <div class="card-footer">
                                <div class="content-buttons">
                                    <a href="{{ route('partners.edit', $partner->id) }}" type="button"
                                        class="btn btn-primary btn-sm float-right">
                                        Editar datos Partner
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- Datos end --}}

                        {{-- Localidades --}}
                        <div class="card card-secondary shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span class="ml-2">Localidades asignadas a {{ $partner->name }}</span>
                                </h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            @if ($partner->localities->isEmpty())
                                <div class="card-body" style="display: block;">
                                    <p>Este partner, no tiene localidades asignadas</p>
                                </div>
                            @else
                                <div class="card-body table-responsive" style="display: block;">

                                    <table id="tableLocalities"
                                        class="table table-hover table-head-fixed text-nowrap table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Localidad</th>
                                                <th>Zona</th>
                                                <th>Provincia</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($partner->localities as $locality)
                                                <tr>
                                                    <td>
                                                        @if (isset($locality->name))
                                                            <a title="Ver localidad {{ $locality->name }}"
                                                                href="{{ route('localities.show', $locality->id) }}">
                                                                {{ $locality->name }}
                                                            </a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (isset($locality->zone->name))
                                                            <a title="Ver zona {{ $locality->zone->name }}"
                                                                href="{{ route('zones.show', $locality->zone->id) }}">
                                                                {{ $locality->zone->name }}
                                                            </a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (isset($locality->province->name))
                                                            <a title="Ver provincia {{ $locality->province->name }}"
                                                                href="{{ route('provinces.show', $locality->province->id) }}">
                                                                {{ $locality->province->name }}
                                                            </a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('localities.edit', $locality->id) }}"
                                                            type="button" class="btn btn-primary btn-sm">
                                                            Editar
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Localidad</th>
                                                <th>Zona</th>
                                                <th>Provincia</th>
                                                <th>Acción</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endif

                        </div>
                        {{-- Localidades end --}}

                    </div>
                </div>
            </div>
        </section>
    </div>

    @section('scripts')
        @vite(['resources/js/jquery.dataTables.min.js', 'resources/js/dataTables.bootstrap4.min.js', 'resources/js/dataTables.responsive.min.js', 'resources/js/responsive.bootstrap4.min.js', 'resources/js/dataTables.buttons.min.js', 'resources/js/buttons.bootstrap4.min.js', 'resources/js/jszip.min.js', 'resources/js/pdfmake.min.js', 'resources/js/vfs_fonts.js', 'resources/js/buttons.html5.min.js', 'resources/js/buttons.print.min.js', 'resources/js/buttons.colVis.min.js', 'resources/js/configure-modal-confirm-delete.js'])
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // TODO: ver porque no aparecen los botones de exportacion en todas las tablas dataTable
                jQuery("#tableLocalities").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "language": {
                        url: '/locales/dataTables_es-ES.json',
                    },
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo(
                    '#tableLocalities_wrapper .col-md-6:eq(0)');
            });
        </script>
    @endsection

</x-app-layout>
