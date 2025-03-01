<x-app-layout>

    @section('css')
        <!-- DataTables -->
        @vite(['resources/css/dataTables.bootstrap4.min.css', 'resources/css/responsive.bootstrap4.min.css', 'resources/css/buttons.bootstrap4.min.css'])
    @endsection

    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Detalle de la zona {{ $zone->name }}</h1>
                    </div>
                </div>
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
                                    <i class="fa-solid fa-map-location-dot"></i>
                                    <span class="ml-2">Datos de la zona {{ $zone->name }}</span>
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
                                        Nombre
                                    </dt>
                                    <dd>
                                        {{ $zone->name }}
                                    </dd>

                                    <dt>
                                        Pertenece a la provincia
                                    </dt>
                                    <dd>
                                        {{ $zone->province->name }}
                                    </dd>
                                </dl>
                            </div>
                            <div class="card-footer">
                                <div class="content-buttons">
                                    <a href="{{ route('zones.edit', $zone->id) }}" type="button"
                                        class="btn btn-primary btn-sm float-right">
                                        Editar datos Zona
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
                                    <span class="ml-2">Localidades asignadas a {{ $zone->name }}</span>
                                </h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            @if ($zone->localities->isEmpty())
                                <div class="card-body" style="display: block;">
                                    <p>Esta zona, no tiene localidades asignadas</p>
                                </div>
                            @else
                                <div class="card-body table-responsive" style="display: block;">

                                    <table id="tableZones"
                                        class="table table-hover table-head-fixed text-nowrap table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Localidad</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($zone->localities as $locality)
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
                                                <th>Acción</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endif

                        </div>
                        {{-- Localidades end --}}

                        <div class="w-100">
                            <a class="btn btn-default float-right" href="{{ url()->previous() }}">
                                Volver
                            </a>
                        </div>

                    </div>

                </div>
            </div>
        </section>
    </div>

    @section('scripts')
        @vite(['resources/js/jquery.dataTables.min.js', 'resources/js/dataTables.bootstrap4.min.js', 'resources/js/dataTables.responsive.min.js', 'resources/js/responsive.bootstrap4.min.js', 'resources/js/dataTables.buttons.min.js', 'resources/js/buttons.bootstrap4.min.js', 'resources/js/jszip.min.js', 'resources/js/pdfmake.min.js', 'resources/js/vfs_fonts.js', 'resources/js/buttons.html5.min.js', 'resources/js/buttons.print.min.js', 'resources/js/buttons.colVis.min.js', 'resources/js/configure-modal-confirm-delete.js'])
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                jQuery("#tableZones").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "language": {
                        url: '/locales/dataTables_es-ES.json',
                    },
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo(
                    '#tableZones_wrapper .col-md-6:eq(0)');
            });
        </script>
    @endsection

</x-app-layout>
