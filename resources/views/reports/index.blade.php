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
                        <h1>Reportes</h1>
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
                                    <button type="button" class="btn btn-primary mt-3" id="apply-filters">Aplicar
                                        Filtros</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Cantidad de Formularios por Partner</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="chartFormsByPartner"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Detalle de Formularios por Partner</h3>
                            </div>
                            <div class="card-body">
                                <table id="tablaFormularios" class="table table-striped" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th>Provincia</th>
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
        @vite(['resources/js/jquery.dataTables.min.js', 'resources/js/dataTables.bootstrap4.min.js', 'resources/js/dataTables.responsive.min.js', 'resources/js/responsive.bootstrap4.min.js', 'resources/js/dataTables.buttons.min.js', 'resources/js/buttons.bootstrap4.min.js', 'resources/js/jszip.min.js', 'resources/js/pdfmake.min.js', 'resources/js/vfs_fonts.js', 'resources/js/buttons.html5.min.js', 'resources/js/buttons.print.min.js', 'resources/js/buttons.colVis.min.js', 'resources/js/configure-modal-confirm-delete.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

                const ctx = document.getElementById("chartFormsByPartner");

                if (ctx) {
                    const chart = new Chart(ctx, {
                        type: "bar",
                        data: {
                            labels: [], // Se llenará con AJAX
                            datasets: [{
                                label: "Cantidad de Formularios",
                                data: [],
                                backgroundColor: "rgba(54, 162, 235, 0.5)",
                                borderColor: "rgba(54, 162, 235, 1)",
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    min: 0
                                }
                            },
                            onClick: (event, elements) => {
                                if (elements.length > 0) {
                                    const index = elements[0].index;
                                    const partnerId = chart.data.id[index]; // Obtener el ID del partner
                                    loadFormulariosTable(partnerId);
                                }
                            }
                        }
                    });

                    // Cargar datos iniciales del gráfico
                    fetch(`{{ route('reports.form_submissions_by_partner') }}`)
                        .then(response => response.json())
                        .then(data => {
                            chart.data.id = data.id;
                            chart.data.labels = data.labels;
                            chart.data.datasets[0].data = data.data;
                            chart.update();
                        });
                }

                // Función para cargar formularios en la tabla
                function loadFormulariosTable(partnerId) {
                    $("#tablaFormularios tbody").empty(); // Limpiar datos anteriores

                    fetch(`/reports/form_submissions/${partnerId}`)
                        .then(response => response.json())
                        .then(formularios => {
                            if (formularios.length > 0) {
                                formularios.forEach(f => {
                                    $("#tablaFormularios tbody").append(`
                        <tr>
                            <td>${f.id}</td>
                            <td>${f.data.nombre}</td>
                            <td>${f.data.mail}</td>
                            <td>${f.data.cel}</td>
                            <td>${f.province?.name || 'N/A'}</td>
                            <td>${new Date(f.created_at).toLocaleDateString()}</td>
                        </tr>
                    `);
                                });

                                $("#tablaFormularios").show();
                                $("#tablaFormularios").DataTable(); // Activar DataTables
                            } else {
                                $("#tablaFormularios").hide();
                            }
                        });
                }
            });
        </script>
    @endsection
</x-app-layout>
