    @php
        use App\Models\FormSubmissionStatus;
        $closeStateByPartner = FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER;
        $closedStatusWithNoReplyFromPartner = FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER;
        $closedStatusWithNoReplyFromUser = FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO;
    @endphp
    <x-app-layout>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @include('parts.msg-success')
            @include('parts.msg-errors')

            @include('parts.modal-confirm-send-response')
            @include('parts.modal-closure-reason')

            @include('parts.statusColorClass')

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
            <section class="content">
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
                                            <h3 class="card-title d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fa-solid fa-user"></i>
                                                    <span class="ml-2">
                                                        <strong>Consulta del cliente: {{ $data['name'] }}</strong>
                                                    </span>
                                                </div>

                                                <div class="fecha">
                                                    <p>Fecha contacto: {{ $formSubmission->FormattedDate }}</p>
                                                    <p>Estado actual de esta consulta:
                                                        <span
                                                            class="badge {{ statusColorClass($formSubmission->status->status) }}">{{ $formSubmission->status->status }}
                                                        </span>
                                                    </p>
                                                </div>
                                            </h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <dl>
                                                <dt class="mb-3">
                                                </dt>

                                                <dt>
                                                    Nombre Completo
                                                </dt>
                                                <dd>
                                                    {{ $data['name'] }}
                                                </dd>
                                                <dt>
                                                    Email
                                                </dt>
                                                <dd>
                                                    {{ $data['email'] }}
                                                </dd>
                                                <dt>
                                                    Teléfono
                                                </dt>
                                                <dd>
                                                    {{ $data['phone'] }}
                                                </dd>
                                                <dt>
                                                    Fecha de contacto
                                                </dt>
                                                <dd>
                                                    {{ $formSubmission->FormattedDate }}
                                                </dd>
                                            </dl>
                                        </div>
                                        <!-- /.card-body -->

                                    </div>
                                    <!-- /.card -->

                                    <div class="card direct-chat direct-chat-primary">
                                        <div class="card-header ui-sortable-handle" style="cursor: move;">
                                            <h3 class="card-title">Conversasiones</h3>

                                            <div class="card-tools">
                                                <span title="3 New Messages" class="badge badge-primary">3</span>
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <button type="button" class="btn btn-tool" title="Contacts"
                                                    data-widget="chat-pane-toggle">
                                                    <i class="fas fa-comments"></i>
                                                </button>
                                                <button type="button" class="btn btn-tool" data-card-widget="remove">
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
                                                        @if ($response->is_system)
                                                            {{-- Msgs Partner --}}
                                                            <div class="direct-chat-msg right">
                                                                <div class="direct-chat-infos clearfix">
                                                                    <span class="direct-chat-name float-right">
                                                                        {{ $response->user->name }}
                                                                    </span>
                                                                    <span class="direct-chat-timestamp float-left">
                                                                        {{ \Carbon\Carbon::parse($response->created_at)->locale('es')->translatedFormat('d M h:i a') }}
                                                                    </span>
                                                                </div>
                                                                <img class="direct-chat-img"
                                                                    src="{{ Vite::asset('resources/images/user3-128x128.jpg') }}"
                                                                    alt="message user image">
                                                                <div class="direct-chat-text">
                                                                    {!! nl2br(e($response->message)) !!}
                                                                </div>
                                                            </div>
                                                            {{-- Msgs Partner end --}}
                                                        @else
                                                            {{-- Msgs User --}}
                                                            <div class="direct-chat-msg">
                                                                <div class="direct-chat-infos clearfix">
                                                                    <span class="direct-chat-name float-left">
                                                                        {{ $data['name'] }}
                                                                    </span>
                                                                    <span class="direct-chat-timestamp float-right">
                                                                        {{ \Carbon\Carbon::parse($response->created_at)->locale('es')->translatedFormat('d M h:i a') }}
                                                                    </span>
                                                                </div>
                                                                <img class="direct-chat-img"
                                                                    src="{{ Vite::asset('resources/images/user1-128x128.jpg') }}"
                                                                    alt="message user image">
                                                                <div class="direct-chat-text">
                                                                    {!! nl2br(e($response->message)) !!}
                                                                </div>
                                                            </div>
                                                            {{-- Msgs User end --}}
                                                        @endif
                                                    @endforeach

                                                @endif

                                                <div class="card-footer">
                                                    @if (
                                                        $user->role !== $role_admin &&
                                                            ($formSubmission->status->status !== $closeStateByPartner &&
                                                                $formSubmission->status->status !== $closedStatusWithNoReplyFromPartner &&
                                                                $formSubmission->status->status !== $closedStatusWithNoReplyFromUser))
                                                        <form id="message-form"
                                                            action="{{ route('form_responses.store') }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="form_submission_id"
                                                                value="{{ $formSubmission->id }}">
                                                            <input type="hidden" name="user_id"
                                                                value="{{ $user->id }}">
                                                            <div class="input-group">
                                                                <input type="text" id="message" name="message"
                                                                    placeholder="Envia un nuevo mensaje ..."
                                                                    class="form-control">
                                                                <span class="input-group-append">
                                                                    <!-- Botón para abrir el modal -->
                                                                    <button type="button" class="btn btn-primary"
                                                                        data-toggle="modal"
                                                                        data-target="#confirmModal">Enviar</button>
                                                                </span>
                                                            </div>
                                                        </form>
                                                        <button type="button" class="mt-3 float-right btn btn-primary"
                                                            data-toggle="modal" data-target="#closureReason">
                                                            cerrar formulario
                                                        </button>
                                                    @else
                                                        <h5>Esta consulta fue cerrada por el siguiente motivo:</h5>
                                                        <p>{{ $formSubmission->closure_reason }}</p>
                                                    @endif

                                                </div>

                                            </div>
                                            <!--/.direct-chat-messages-->

                                        </div>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <a class="btn btn-default float-right" href="{{ url()->previous() }}">
                                        Volver
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @section('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Seleccionamos el botón que abre el modal de confirmacion de envio de respuesta
                    const submitButton = document.querySelector('[data-target="#confirmModal"]');

                    if (submitButton) {
                        // El botón existe, puedes trabajar con él sin problemas
                        // Cuando se hace clic en el botón "Enviar consulta"
                        submitButton.addEventListener('click', function() {
                            // Capturamos el valor del input
                            const textValue = inputText.value;

                            // Mostramos el valor en el modal
                            modalText.textContent = textValue;
                        });
                    }

                    // Seleccionamos el input de texto
                    const inputText = document.getElementById('message');

                    // Seleccionamos el elemento donde se mostrará el texto en el modal
                    const modalText = document.getElementById('modalText');

                });
            </script>
        @endsection

    </x-app-layout>
