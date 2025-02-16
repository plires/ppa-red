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
                        <h1>Listado de Provincias Eliminadas</h1>
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
                            <div class="card-header">
                                <h3 class="card-title">Sección para restarurar provincias al listado original</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="tableProvinces" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Fecha de Eliminación</th>
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
                                                    <td>{{ $province['name'] }}
                                                    </td>

                                                    <td>
                                                        {{ $province->FormattedDeletedDate }}
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('provinces.restore', $province->id) }}"
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
                                            <th>Fecha de Eliminación</th>
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
                jQuery("#tableProvinces").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo(
                    '#tableProvinces_wrapper .col-md-6:eq(0)');
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
