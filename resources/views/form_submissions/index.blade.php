<x-app-layout>

    @section('css')
        <!-- DataTables -->

        @vite(['resources/css/dataTables.bootstrap4.min.css', 'resources/css/responsive.bootstrap4.min.css', 'resources/css/buttons.bootstrap4.min.css'])
    @endsection

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        @php
            function statusColorClass($status)
            {
                switch ($status) {
                    case App\Models\FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER:
                        return 'bg-primary';
                    case App\Models\FormSubmissionStatus::STATUS_RESPONDIO_PARTNER:
                        return 'bg-success';
                    case App\Models\FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER:
                        return 'bg-danger';
                    case App\Models\FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER:
                        return 'bg-warning';
                    case App\Models\FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO:
                        return 'bg-secondary';
                    case App\Models\FormSubmissionStatus::STATUS_CERRADO_POR_PARTNER:
                        return 'bg-dark';
                    default:
                        return 'bg-primary'; // Estado por defecto
                }
            }
        @endphp

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Consultas de Formularios</h1>
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
                                <h3 class="card-title">DataTable with default features</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="tableFormSubmissions" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Nombre</th>
                                            <th>Localidad</th>
                                            @if ($user->role === $role_admin)
                                                <th>Partner</th>
                                            @else
                                                <th>Email</th>
                                            @endif
                                            <th>Estado</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($formSubmissions as $formSubmission)
                                            @php
                                                $data = json_decode($formSubmission->data, true); // Convierte JSON en array
                                            @endphp

                                            <tr class="align-middle">
                                                <td><a
                                                        href="{{ route('form_submissions.show', $formSubmission->id) }}">{{ $formSubmission->id }}</a>
                                                </td>
                                                <td><a
                                                        href="{{ route('form_submissions.show', $formSubmission->id) }}">{{ $data['name'] }}</a>
                                                </td>
                                                <td><a
                                                        href="{{ route('form_submissions.show', $formSubmission->id) }}">{{ $formSubmission->locality->name }}</a>
                                                </td>
                                                @if ($user->role === $role_admin)
                                                    <td><a
                                                            href="{{ route('form_submissions.show', $formSubmission->id) }}">{{ $formSubmission->user->name }}</a>
                                                    </td>
                                                @else
                                                    <td><a
                                                            href="{{ route('form_submissions.show', $formSubmission->id) }}">{{ $data['email'] }}</a>
                                                    </td>
                                                @endif

                                                <td>
                                                    <a
                                                        href="{{ route('form_submissions.show', $formSubmission->id) }}">
                                                        <span
                                                            class="badge {{ statusColorClass($formSubmission->status->status) }}">{{ $formSubmission->status->status }}
                                                        </span>
                                                    </a>
                                                </td>
                                                <td><a
                                                        href="{{ route('form_submissions.show', $formSubmission->id) }}">{{ $formSubmission->FormattedDate }}</a>
                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Id</th>
                                            <th>Nombre</th>
                                            <th>Localidad</th>
                                            @if ($user->role === $role_admin)
                                                <th>Partner</th>
                                            @else
                                                <th>Email</th>
                                            @endif
                                            <th>Estado</th>
                                            <th>Fecha</th>
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
                jQuery("#tableFormSubmissions").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo(
                    '#tableFormSubmissions_wrapper .col-md-6:eq(0)');
            });
        </script>
    @endsection
</x-app-layout>
