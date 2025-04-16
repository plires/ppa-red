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
                        <h1>Reporte - Estado de Formularios por Partners</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Filtros</h3>
                            </div>
                            <div class="card-body">
                                <form id="filter-form">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Fecha Inicio</label>
                                            <input type="date" class="form-control" name="start_date"
                                                id="start_date">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Fecha Fin</label>
                                            <input type="date" class="form-control" name="end_date" id="end_date">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Partner</label>
                                            <select class="form-control" name="partner_id" id="partner_id">
                                                <option value="">Todos</option>
                                                @foreach ($partners as $partner)
                                                    <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Estado de Formularios por Partner</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="chartFormStatus" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Detalle de Formularios por Partner</h3>
                            </div>
                            <div class="card-body">
                                <table id="tableFormSubmissions"
                                    class="table table-hover table-head-fixed table-bordered table-striped"
                                    style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th>Estado</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @section('scripts')
        <script>
            window.statuses = @json($statuses);
            window.routeChartData = "{{ route('reports.form_status_chart') }}";
        </script>

        @vite(['resources/js/pages/reports/form-status-chart.js', 'resources/js/configure-modal-confirm-delete.js', 'resources/js/jquery.dataTables.min.js', 'resources/js/dataTables.bootstrap4.min.js', 'resources/js/dataTables.responsive.min.js', 'resources/js/responsive.bootstrap4.min.js', 'resources/js/dataTables.buttons.min.js', 'resources/js/buttons.bootstrap4.min.js', 'resources/js/jszip.min.js', 'resources/js/pdfmake.min.js', 'resources/js/vfs_fonts.js', 'resources/js/buttons.html5.min.js', 'resources/js/buttons.print.min.js', 'resources/js/buttons.colVis.min.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endsection

</x-app-layout>
