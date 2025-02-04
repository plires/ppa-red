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
                            <h3 class="card-title">Detalle de la consulta</h3>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <p>{{ $formSubmission }}</p>
                            <p>{{ $formSubmission->status->status }}</p>
                            <p>{{ $formSubmission->formResponses }}</p>
                            <p>{{ $user }}</p>
                            <p>{{ $role_admin }}</p>
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
