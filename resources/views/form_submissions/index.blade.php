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
                                        <th>Email</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($formSubmissions as $formSubmission)
                                        @php
                                            $data = json_decode($formSubmission->data, true); // Convierte JSON en array
                                        @endphp

                                        <tr class="align-middle">
                                            <td>{{ $formSubmission->id }}</td>
                                            <td>{{ $data['name'] }}</td>
                                            <td>{{ $formSubmission->locality->name }}</td>
                                            <td>{{ $data['email'] }}</td>
                                            <td>{{ $formSubmission->status->status }}</td>
                                            <td>{{ $formSubmission->FormattedDate }}</td>
                                            <td>
                                                <a href="#">✏️ Editar</a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Position</th>
                                        <th>Localidad</th>
                                        <th>Email</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
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
