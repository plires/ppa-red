<x-app-layout>

    @section('css')
        @vite(['resources/css/dataTables.dataTables.css'])
    @endsection

    {{-- <x-slot name="header">
        <h2>
            pepe
        </h2>
    </x-slot> --}}

    @include('layouts.app_content_header_admin')

    <!--begin::App Content-->
    <div class="app-content">

        @php
            function statusColorClass($status)
            {
                switch ($status) {
                    case App\Models\FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER:
                        return 'text-bg-primary';
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
                        return 'text-bg-primary'; // Estado por defecto
                }
            }
        @endphp

        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-md-12">

                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Bordered Table</h3>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">

                            <table id="formSubmissionTable" class="display table table-striped table-bordered"
                                style="width:100%">
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
                                                        href="{{ route('form_submissions.show', $formSubmission->id) }}">{{ $formSubmission->partner->name }}</a>
                                                </td>
                                            @else
                                                <td><a
                                                        href="{{ route('form_submissions.show', $formSubmission->id) }}">{{ $data['email'] }}</a>
                                                </td>
                                            @endif

                                            <td>
                                                <a href="{{ route('form_submissions.show', $formSubmission->id) }}">
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
                                        <th>Nombre</th>
                                        <th>Position</th>
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
                        <div class="card-footer clearfix">
                            footer
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->


    </div>
    <!--end::Container-->
    </div>
    <!--end::App Content-->

    @section('scripts')
        @vite(['resources/js/datatablesConfig.js', 'resources/js/jquery-3.7.1.js', 'resources/js/datatables.js'])
    @endsection
</x-app-layout>
