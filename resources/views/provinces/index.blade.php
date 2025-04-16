<x-app-layout>

    @section('css')
        <!-- DataTables -->
        @vite(['resources/css/dataTables.bootstrap4.min.css', 'resources/css/responsive.bootstrap4.min.css', 'resources/css/buttons.bootstrap4.min.css'])
    @endsection

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper data-table-lists">

        @include('parts.msg-success')
        @include('parts.msg-errors')

        @include('parts.modal-confirm-delete')

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Listado de Provincias</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <div class="card">
                            <div class="card-header header-index">
                                <div class="content-buttons">
                                    <a href="{{ route('provinces.create') }}" type="button"
                                        class="btn btn-primary btn-sm">Agregar Provincia</a>
                                    <a href="{{ route('provinces.trashed') }}" type="button"
                                        class="btn btn-primary btn-sm">Ver Provincias
                                        Eliminadas</a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="tableProvinces"
                                    class="table table-hover table-head-fixed table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Fecha de Modificación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($provinces->isEmpty())
                                            <tr class="align-middle">
                                                <td>No hay provincias para listar</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                </td>
                                            </tr>
                                        @else
                                            @foreach ($provinces as $province)
                                                <tr class="align-middle">
                                                    <td>
                                                        <a href="{{ route('provinces.show', $province->id) }}">
                                                            {{ $province->name }}
                                                        </a>
                                                    </td>

                                                    <td>
                                                        {{ $province->FormattedModifiedDate }}
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button"
                                                                class="btn btn-default">Acción</button>
                                                            <button type="button"
                                                                class="btn btn-default dropdown-toggle dropdown-icon"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                                <span class="sr-only">Toggle Dropdown</span>
                                                            </button>
                                                            <div class="dropdown-menu" role="menu" style="">
                                                                <a class="dropdown-item"
                                                                    href="{{ route('provinces.edit', $province->id) }}">Editar</a>
                                                                <a data-toggle="modal" data-target="#modalConfirm"
                                                                    data-entity="la provincia"
                                                                    data-name="{{ $province->name }}"
                                                                    data-delete-route="{{ route('provinces.destroy', $province->id) }}"
                                                                    class="dropdown-item delete-btn" dropdown-item"
                                                                    href="{{ route('provinces.destroy', $province->id) }}">Eliminar</a>

                                                            </div>
                                                        </div>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Fecha de Modificación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                            <!-- /.card-body -->
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
        @vite(['resources/js/jquery.dataTables.min.js', 'resources/js/dataTables.bootstrap4.min.js', 'resources/js/dataTables.responsive.min.js', 'resources/js/responsive.bootstrap4.min.js', 'resources/js/dataTables.buttons.min.js', 'resources/js/buttons.bootstrap4.min.js', 'resources/js/jszip.min.js', 'resources/js/pdfmake.min.js', 'resources/js/vfs_fonts.js', 'resources/js/buttons.html5.min.js', 'resources/js/buttons.print.min.js', 'resources/js/buttons.colVis.min.js', 'resources/js/configure-modal-confirm-delete.js', 'resources/js/utils/utils.js'])
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                initDataTableWithButtons('tableProvinces');
            });
        </script>
    @endsection
</x-app-layout>
