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
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Visualización completa de los datos del partner</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fa-solid fa-user"></i>
                                            <span class="ml-2"><strong>{{ $partner->name }}</strong></span>
                                        </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
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
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <div class="content-buttons">
                                            <a href="{{ route('partners.edit', $partner->id) }}" type="button"
                                                class="btn btn-primary btn-sm">Editar datos Partner</a>
                                        </div>
                                    </div>
                                    <!-- /.card-footer -->
                                </div>
                                <!-- /.card -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fa-solid fa-location-dot"></i>
                                            Localidades asignadas
                                        </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    @if ($partner->localities->isEmpty())
                                        <div class="card-body">
                                            <p>Este partner, no tiene localidades asignadas</p>
                                        </div>
                                    @else
                                        <!-- /.card-header -->
                                        <div class="card-body table-responsive p-0" style="height: 300px;">

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
                                                    @if ($partner->localities->isEmpty())
                                                        <tr class="align-middle">
                                                            <td>No hay localidades asignadas para este partner</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    @else
                                                        @foreach ($partner->localities as $locality)
                                                            <tr>
                                                                <td>{{ $locality->name ?? '-' }}</td>
                                                                <td>{{ $locality->zone->name ?? '-' }}</td>
                                                                <td>{{ $locality->province->name ?? '-' }}</td>
                                                                <td>
                                                                    <a href="{{ route('localities.edit', $locality->id) }}"
                                                                        type="button" class="btn btn-primary btn-sm">
                                                                        Editar
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
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
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <a class="btn btn-default float-right" href="{{ url()->previous() }}">
                                    Volver
                                </a>
                            </div>
                            <!-- /.card-footer -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @section('scripts')
        @vite(['resources/js/jquery.dataTables.min.js', 'resources/js/dataTables.bootstrap4.min.js', 'resources/js/dataTables.responsive.min.js', 'resources/js/responsive.bootstrap4.min.js', 'resources/js/dataTables.buttons.min.js', 'resources/js/buttons.bootstrap4.min.js', 'resources/js/jszip.min.js', 'resources/js/pdfmake.min.js', 'resources/js/vfs_fonts.js', 'resources/js/buttons.html5.min.js', 'resources/js/buttons.print.min.js', 'resources/js/buttons.colVis.min.js', 'resources/js/configure-modal-confirm-delete.js'])
        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
