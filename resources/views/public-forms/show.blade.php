@php
    use App\Models\FormSubmissionStatus;
    $closeStateByPartner = FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER;
    $closedStatusWithNoReplyFromPartner = FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER;
    $closedStatusWithNoReplyFromUser = FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO;
@endphp
<x-guest-layout>

    @section('css')
        @vite(['resources/css/form-submissions-show.css'])
    @endsection

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @include('parts.msg-success')
        @include('parts.msg-errors')

        @include('parts.modal-confirm-send-response')

        @include('parts.statusColorClass')

        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <h1>Detalle de la consulta</h1>
                                </div>
                            </div><!-- /.container-fluid -->
                    </section>

                    <!-- Main content -->
                    <section class="content formSubmissionsShow">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">Visualización completa de la consulta</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3
                                                        class="card-title d-flex justify-content-between align-items-center">
                                                        <i class="fa-solid fa-user"></i>
                                                        <span class="ml-2">
                                                            <strong>Consulta del cliente: {{ $data['name'] }}</strong>
                                                        </span>
                                                    </h3>
                                                    <div class="content">
                                                        <p class="date">Fecha contacto:
                                                            {{ $formSubmission->FormattedDate }}
                                                        </p>
                                                        <p class="status">Estado actual de esta consulta:
                                                            <span
                                                                class="badge {{ statusColorClass($formSubmission->status->name) }}">{{ $formSubmission->status->name }}
                                                            </span>
                                                        </p>
                                                    </div>

                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">

                                                            {{-- Profile User --}}
                                                            <div class="card bg-light d-flex flex-fill contentProfile">
                                                                <div class="card-header text-muted border-bottom-0">
                                                                    <h2 class="lead">
                                                                        <b> Usuario:</b> {{ $data['name'] }}
                                                                    </h2>
                                                                </div>
                                                                <div class="card-body pt-0">
                                                                    <div class="row">
                                                                        <div class="col-7">

                                                                            <p class="text-muted text-sm"><b>Localidad:
                                                                                </b>
                                                                                {{ $formSubmission->locality->name }}
                                                                            </p>
                                                                            @if ($formSubmission->zone)
                                                                                <p class="text-muted text-sm">
                                                                                    <b>Zona:
                                                                                    </b>
                                                                                    {{ $formSubmission->zone->name }}
                                                                                </p>
                                                                            @endif
                                                                            <p class="text-muted text-sm"><b>Provincia:
                                                                                </b>
                                                                                {{ $formSubmission->province->name }}
                                                                            </p>
                                                                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                                                                <li class="small">
                                                                                    <span class="fa-li">
                                                                                        <i
                                                                                            class="fa-solid fa-envelope"></i>
                                                                                    </span>
                                                                                    Email: {{ $data['email'] }}
                                                                                </li>
                                                                                <li class="small"><span
                                                                                        class="fa-li"><i
                                                                                            class="fas fa-lg fa-phone"></i></span>
                                                                                    Teléfono: {{ $data['phone'] }}
                                                                                </li>
                                                                                <li class="small"><span
                                                                                        class="fa-li"><i
                                                                                            class="fa-solid fa-calendar"></i></span>
                                                                                    Fecha de contacto:
                                                                                    {{ $formSubmission->FormattedDate }}
                                                                                </li>

                                                                            </ul>
                                                                        </div>
                                                                        <div class="col-5 text-center">
                                                                            <img src="{{ Vite::asset('resources/images/user.webp') }}"
                                                                                alt="user-avatar"
                                                                                class="img-circle img-fluid">
                                                                            <br>
                                                                            <span
                                                                                class="text-muted text-sm">{{ $data['name'] }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            {{-- Profile User end --}}

                                                        </div>
                                                        <div class="col-md-6">

                                                            {{-- Profile Partner --}}
                                                            <div class="card bg-light d-flex flex-fill contentProfile">
                                                                <div class="card-header text-muted border-bottom-0">
                                                                    <h2 class="lead">
                                                                        <b>Partner PPA RED:</b>
                                                                        {{ $formSubmission->user->name }}
                                                                    </h2>
                                                                </div>
                                                                <div class="card-body pt-0">
                                                                    <div class="row">
                                                                        <div class="col-7">

                                                                            <p class="text-muted text-sm"><b>Localidad:
                                                                                </b>
                                                                                {{ $formSubmission->locality->name }}
                                                                            </p>
                                                                            @if ($formSubmission->zone)
                                                                                <p class="text-muted text-sm">
                                                                                    <b>Zona:
                                                                                    </b>
                                                                                    {{ $formSubmission->zone->name }}
                                                                                </p>
                                                                            @endif
                                                                            <p class="text-muted text-sm"><b>Provincia:
                                                                                </b>
                                                                                {{ $formSubmission->province->name }}
                                                                            </p>
                                                                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                                                                <li class="small">
                                                                                    <span class="fa-li">
                                                                                        <i
                                                                                            class="fa-solid fa-envelope"></i>
                                                                                    </span>
                                                                                    Email:
                                                                                    {{ $formSubmission->user->name }}
                                                                                </li>
                                                                                <li class="small"><span
                                                                                        class="fa-li"><i
                                                                                            class="fas fa-lg fa-phone"></i></span>
                                                                                    Teléfono:
                                                                                    {{ $formSubmission->user->phone }}
                                                                                </li>


                                                                            </ul>
                                                                        </div>
                                                                        <div class="col-5 text-center">
                                                                            <img src="{{ Vite::asset('resources/images/circle-logo-partner.webp') }}"
                                                                                alt="user-avatar-partner"
                                                                                class="img-circle img-fluid">
                                                                            <br>
                                                                            <span
                                                                                class="text-muted text-sm">{{ $formSubmission->user->name }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            {{-- Profile Partner end --}}

                                                        </div>
                                                    </div>

                                                </div>
                                                <!-- /.card-body -->

                                            </div>
                                            <!-- /.card -->

                                            <div class="card direct-chat direct-chat-primary">
                                                <div class="card-header ui-sortable-handle" style="cursor: move;">
                                                    <h3 class="card-title">Conversasiones</h3>

                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool"
                                                            data-card-widget="collapse">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-tool"
                                                            data-card-widget="remove">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body">
                                                    <!-- Conversations are loaded here -->
                                                    <div class="direct-chat-messages">

                                                        @if ($responses->isEmpty())
                                                            <p>No hay conversasiones</p>
                                                        @else
                                                            @foreach ($responses as $response)
                                                                @if (!$response->is_system)
                                                                    {{-- Msgs User --}}
                                                                    <div class="direct-chat-msg right">
                                                                        <div class="direct-chat-infos clearfix">
                                                                            <span class="direct-chat-name float-right">
                                                                                {{ $data['name'] }}
                                                                            </span>
                                                                            <span
                                                                                class="direct-chat-timestamp float-left">
                                                                                {{ \Carbon\Carbon::parse($response->created_at)->locale('es')->translatedFormat('d M h:i a') }}
                                                                            </span>
                                                                        </div>
                                                                        <img class="direct-chat-img"
                                                                            src="{{ Vite::asset('resources/images/user.webp') }}"
                                                                            alt="message user {{ $response->id }}">
                                                                        <div class="direct-chat-text">
                                                                            {!! nl2br(e($response->message)) !!}
                                                                        </div>
                                                                    </div>
                                                                    {{-- Msgs User end --}}
                                                                @else
                                                                    {{-- Msgs Partner --}}
                                                                    <div class="direct-chat-msg">
                                                                        <div class="direct-chat-infos clearfix">
                                                                            <span class="direct-chat-name float-left">
                                                                                {{ $response->user->name }}
                                                                            </span>
                                                                            <span
                                                                                class="direct-chat-timestamp float-right">
                                                                                {{ \Carbon\Carbon::parse($response->created_at)->locale('es')->translatedFormat('d M h:i a') }}
                                                                            </span>
                                                                        </div>
                                                                        <img class="direct-chat-img"
                                                                            src="{{ Vite::asset('resources/images/circle-logo-partner.webp') }}"
                                                                            alt="message partner {{ $response->id }}">
                                                                        <div class="direct-chat-text">
                                                                            {!! nl2br(e($response->message)) !!}
                                                                        </div>
                                                                    </div>
                                                                    {{-- Msgs Partner end --}}
                                                                @endif
                                                            @endforeach

                                                        @endif

                                                        <div class="card-footer">
                                                            @if (
                                                                $formSubmission->status->name !== $closeStateByPartner &&
                                                                    $formSubmission->status->name !== $closedStatusWithNoReplyFromPartner &&
                                                                    $formSubmission->status->name !== $closedStatusWithNoReplyFromUser)
                                                                <form id="message-form"
                                                                    action="{{ route('public.form_responses.store') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="form_submission_id"
                                                                        value="{{ $formSubmission->id }}">
                                                                    <input type="hidden" name="user_id"
                                                                        value="{{ $formSubmission->user->id }}">
                                                                    <div class="input-group">
                                                                        <input type="text" id="message"
                                                                            name="message"
                                                                            placeholder="Envia un nuevo mensaje ..."
                                                                            class="form-control">
                                                                        <span class="input-group-append">
                                                                            <!-- Botón para abrir el modal -->
                                                                            <button type="button"
                                                                                class="btn btn-primary"
                                                                                data-toggle="modal"
                                                                                data-target="#confirmModal">Enviar</button>
                                                                        </span>
                                                                    </div>
                                                                </form>
                                                            @else
                                                                <h5>Esta consulta fue cerrada por el siguiente motivo:
                                                                </h5>
                                                                <p>{{ $msg = $formSubmission->status->name !== $closeStateByPartner ? $formSubmission->closure_reason : 'Esta consulta fue cerrada por el partner.' }}
                                                                    <br>
                                                                    Si necesita volver a contactarse, puede hacerlo a
                                                                    los
                                                                    datos de su partner asignado.
                                                                </p>
                                                            @endif
                                                        </div>

                                                    </div>
                                                    <!--/.direct-chat-messages-->

                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </div>

    </div>

    @section('scripts')
        @vite(['resources/js/pages/show-submissions/send-messages.js'])
    @endsection

</x-guest-layout>
