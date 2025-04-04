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
                        <h1>Reporte - Formularios por Partners</h1>
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
                                <table id="tableFormSubmissions"
                                    class="table table-hover table-head-fixed table-bordered table-striped"
                                    style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th>Localidad</th>
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

                const startDate = document.getElementById("start_date");
                const endDate = document.getElementById("end_date");
                const partnerId = document.getElementById("partner_id");

                let chart = null; // Variable global para el gráfico
                const ctx = document.getElementById("chartFormsByPartner").getContext("2d");

                function createChart(data) {
                    if (chart) {
                        chart.destroy(); // Destruir el gráfico existente antes de crear uno nuevo
                    }

                    chart = new Chart(ctx, {
                        type: "bar",
                        data: {
                            labels: data.labels,
                            id: data.id,
                            datasets: [{
                                label: "Cantidad de Formularios",
                                data: data.data,
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
                                    const partnerId = chart.data.id[
                                        index];
                                    loadFormSubmissionsTable(partnerId);
                                }
                            }
                        }
                    });
                }

                function fetchChartData() {

                    const {
                        start,
                        end
                    } = getStartAndEndDate(startDate, endDate);

                    const params = new URLSearchParams({
                        start_date: start,
                        end_date: end,
                        partner_id: partnerId.value
                    });

                    resetTable("#tableFormSubmissions"); // Llamamos a la función de limpieza

                    fetch(`{{ route('reports.form_submissions_by_partner') }}?${params.toString()}`)
                        .then(response => response.json())
                        .then(data => {
                            createChart(data); // Llamamos a la función para crear el gráfico con los nuevos datos
                        });
                }

                function resetTable(tableId) {
                    if ($.fn.DataTable.isDataTable(tableId)) {
                        $(tableId).DataTable().clear().destroy(); // Limpia y destruye la instancia de DataTables
                    }
                    $(tableId + " tbody").empty(); // Vacía el contenido de la tabla
                }

                function getStartAndEndDate(startDate, endDate) {
                    const today = new Date().toISOString().split('T')[
                        0]; // Obtener la fecha actual en formato 'YYYY-MM-DD'

                    startDate.setAttribute("max", today); // Restringir fecha máxima
                    endDate.setAttribute("max", today); // Restringir fecha máxima

                    let start = startDate.value || '1900-01-01'; // Si no hay valor, usar fecha por defecto
                    let end = endDate.value || today; // Si no hay valor, usar la fecha de hoy

                    return {
                        start,
                        end
                    };
                }

                function loadFormSubmissionsTable(partnerId) {

                    const {
                        start,
                        end
                    } = getStartAndEndDate(startDate, endDate);

                    resetTable("#tableFormSubmissions"); // Llamamos a la función de limpieza

                    fetch(`/reports/form_submissions/${partnerId}/${start}/${end}`)
                        .then(response => response.json())
                        .then(formularios => {
                            if (formularios.length > 0) {
                                let table = $("#tableFormSubmissions").DataTable({
                                    "responsive": true,
                                    "lengthChange": false,
                                    "autoWidth": false,
                                    "destroy": true,
                                    order: [
                                        [4, "desc"]
                                    ],
                                    "language": {
                                        url: '/locales/dataTables_es-ES.json',
                                    },
                                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                                });

                                formularios.forEach(f => {
                                    let data = JSON.parse(f.data);
                                    table.row.add([
                                        `<a href="/form_submissions/${f.id}">${data.name}</a>`,
                                        `<a href="/form_submissions/${f.id}">${data.email}</a>`,
                                        `<a href="/form_submissions/${f.id}">${data.phone}</a>`,
                                        `<a href="/form_submissions/${f.id}">${f.locality?.name || 'N/A'}</a>`,
                                        `<a href="/form_submissions/${f.id}">${new Date(f.created_at).toLocaleDateString()}</a>`
                                    ]);
                                });

                                table.draw();
                                $("#tableFormSubmissions").show();
                                table.buttons().container().appendTo(
                                    '#tableFormSubmissions_wrapper .col-md-6:eq(0)');
                            } else {
                                $("#tableFormSubmissions").hide();
                            }
                        });
                }

                // Cargar gráfico inicial sin filtros
                fetchChartData();

                // Aplicar filtros al cambiar los inputs
                document.getElementById("start_date").addEventListener("change", fetchChartData);
                document.getElementById("end_date").addEventListener("change", fetchChartData);
                document.getElementById("partner_id").addEventListener("change", fetchChartData);
            });
        </script>
    @endsection
</x-app-layout>
