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
                        <h1>Listado de Partners</h1>
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
                                    <a href="{{ route('partners.create') }}" type="button"
                                        class="btn btn-primary btn-sm">Agregar Partner</a>
                                    <a href="{{ route('partners.trashed') }}" type="button"
                                        class="btn btn-primary btn-sm">Ver Partners
                                        Eliminadas</a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="tablePartners"
                                    class="table table-hover table-head-fixed table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($partners->isEmpty())
                                            <tr class="align-middle">
                                                <td>No hay partners para listar</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                </td>
                                            </tr>
                                        @else
                                            @foreach ($partners as $partner)
                                                <tr class="align-middle">

                                                    <td>
                                                        <a href="{{ route('partners.show', $partner->id) }}">
                                                            {{ $partner['name'] }}
                                                        </a>
                                                    </td>

                                                    <td>{{ $partner->email }}</td>

                                                    <td>
                                                        {{ $partner->phone }}
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
                                                                    href="{{ route('partners.show', $partner->id) }}">Ver
                                                                    Detalles</a>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('partners.edit', $partner->id) }}">Editar</a>
                                                                <a data-toggle="modal" data-target="#modalConfirm"
                                                                    data-entity="el partner"
                                                                    data-name="{{ $partner->name }}"
                                                                    data-delete-route="{{ route('partners.destroy', $partner->id) }}"
                                                                    class="dropdown-item delete-btn" dropdown-item"
                                                                    href="{{ route('partners.destroy', $partner->id) }}">Eliminar</a>

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
                                            <th>Email</th>
                                            <th>Teléfono</th>
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
        @vite(['resources/js/jquery.dataTables.min.js', 'resources/js/dataTables.bootstrap4.min.js', 'resources/js/dataTables.responsive.min.js', 'resources/js/responsive.bootstrap4.min.js', 'resources/js/dataTables.buttons.min.js', 'resources/js/buttons.bootstrap4.min.js', 'resources/js/jszip.min.js', 'resources/js/pdfmake.min.js', 'resources/js/vfs_fonts.js', 'resources/js/buttons.html5.min.js', 'resources/js/buttons.print.min.js', 'resources/js/buttons.colVis.min.js', 'resources/js/configure-modal-confirm-delete.js'])
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                jQuery("#tablePartners").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "language": {
                        url: '/locales/dataTables_es-ES.json',
                    },
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo(
                    '#tablePartners_wrapper .col-md-6:eq(0)');
            });
        </script>
    @endsection
</x-app-layout>
