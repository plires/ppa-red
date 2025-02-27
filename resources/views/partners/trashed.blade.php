<x-app-layout>

    @section('css')
        <!-- DataTables -->
        @vite(['resources/css/dataTables.bootstrap4.min.css', 'resources/css/responsive.bootstrap4.min.css', 'resources/css/buttons.bootstrap4.min.css'])
    @endsection

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper data-table-lists">

        @include('parts.msg-success')
        @include('parts.msg-errors')

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Listado de Partners Eliminadas</h1>
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
                            <div class="card-header  header-index">
                                <div class="content-buttons">
                                    <a href="{{ route('partners.create') }}" type="button"
                                        class="btn btn-primary btn-sm">Agregar Partner</a>
                                    <a href="{{ route('partners.index') }}" type="button"
                                        class="btn btn-primary btn-sm">Ver Partners
                                        Habilitadas</a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="tablePartners" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th>Fecha de eliminación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($partners->isEmpty())
                                            <tr class="align-middle">
                                                <td>No hay partners para listar</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        @else
                                            @foreach ($partners as $partner)
                                                <tr class="align-middle">
                                                    <td>{{ $partner['name'] }}</td>

                                                    <td>{{ $partner['email'] }}</td>

                                                    <td>{{ $partner['phone'] }}</td>

                                                    <td>
                                                        {{ $partner->FormattedDeletedDate }}
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('partners.restore', $partner->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                class="btn btn-block btn-success btn-sm">Restaurar</button>
                                                        </form>
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
                                            <th>Fecha de eliminación</th>
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
        @vite(['resources/js/jquery.dataTables.min.js', 'resources/js/dataTables.bootstrap4.min.js', 'resources/js/dataTables.responsive.min.js', 'resources/js/responsive.bootstrap4.min.js', 'resources/js/dataTables.buttons.min.js', 'resources/js/buttons.bootstrap4.min.js', 'resources/js/jszip.min.js', 'resources/js/pdfmake.min.js', 'resources/js/vfs_fonts.js', 'resources/js/buttons.html5.min.js', 'resources/js/buttons.print.min.js', 'resources/js/buttons.colVis.min.js'])
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

        <script>
            document.addEventListener('click', function(event) {
                // Verifica si el clic fue en un botón con la clase 'delete-btn'
                if (event.target.classList.contains('delete-btn')) {

                    // Obtén los datos del botón
                    const deleteRoute = event.target.getAttribute('data-delete-route');

                    // Actualiza el formulario de eliminación dentro del modal
                    const deleteForm = document.getElementById('deleteForm');
                    deleteForm.setAttribute('action', deleteRoute);
                }
            });
        </script>
    @endsection
</x-app-layout>
