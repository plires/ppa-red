<x-app-layout>

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
                                                Comentario inicial
                                            </dt>
                                            <dd>
                                                {{ $data['comments'] }}
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
                                        <h3 class="card-title">Contactos Posteriores</h3>

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
                                            <!-- Message. Default to the left -->
                                            <div class="direct-chat-msg">
                                                <div class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-left">Alexander Pierce</span>
                                                    <span class="direct-chat-timestamp float-right">23 Jan 2:00
                                                        pm</span>
                                                </div>
                                                <!-- /.direct-chat-infos -->
                                                <img class="direct-chat-img"
                                                    src="{{ Vite::asset('resources/images/user1-128x128.jpg') }}"
                                                    alt="message user image">
                                                <!-- /.direct-chat-img -->
                                                <div class="direct-chat-text">
                                                    Is this template really for free? That's unbelievable!
                                                </div>
                                                <!-- /.direct-chat-text -->
                                            </div>
                                            <!-- /.direct-chat-msg -->

                                            <!-- Message to the right -->
                                            <div class="direct-chat-msg right">
                                                <div class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-right">Sarah Bullock</span>
                                                    <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                                                </div>
                                                <!-- /.direct-chat-infos -->
                                                <img class="direct-chat-img"
                                                    src="{{ Vite::asset('resources/images/user3-128x128.jpg') }}"
                                                    alt="message user image">
                                                <!-- /.direct-chat-img -->
                                                <div class="direct-chat-text">
                                                    You better believe it!
                                                </div>
                                                <!-- /.direct-chat-text -->
                                            </div>
                                            <!-- /.direct-chat-msg -->

                                            <!-- Message. Default to the left -->
                                            <div class="direct-chat-msg">
                                                <div class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-left">Alexander Pierce</span>
                                                    <span class="direct-chat-timestamp float-right">23 Jan 5:37
                                                        pm</span>
                                                </div>
                                                <!-- /.direct-chat-infos -->
                                                <img class="direct-chat-img"
                                                    src="{{ Vite::asset('resources/images/user1-128x128.jpg') }}"
                                                    alt="message user image">
                                                <!-- /.direct-chat-img -->
                                                <div class="direct-chat-text">
                                                    Working with AdminLTE on a great new app! Wanna join?
                                                </div>
                                                <!-- /.direct-chat-text -->
                                            </div>
                                            <!-- /.direct-chat-msg -->

                                            <!-- Message to the right -->
                                            <div class="direct-chat-msg right">
                                                <div class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-right">Sarah Bullock</span>
                                                    <span class="direct-chat-timestamp float-left">23 Jan 6:10 pm</span>
                                                </div>
                                                <!-- /.direct-chat-infos -->
                                                <img class="direct-chat-img"
                                                    src="{{ Vite::asset('resources/images/user3-128x128.jpg') }}"
                                                    alt="message user image">
                                                <!-- /.direct-chat-img -->
                                                <div class="direct-chat-text">
                                                    I would love to.
                                                </div>
                                                <!-- /.direct-chat-text -->
                                            </div>
                                            <!-- /.direct-chat-msg -->

                                        </div>
                                        <!--/.direct-chat-messages-->

                                        <!-- Contacts are loaded here -->
                                        <div class="direct-chat-contacts">
                                            <ul class="contacts-list">
                                                <li>
                                                    <a href="#">
                                                        <img class="contacts-list-img"
                                                            src="{{ 'dist/img/user1-128x128.jpg' }}" alt="User Avatar">

                                                        <div class="contacts-list-info">
                                                            <span class="contacts-list-name">
                                                                Count Dracula
                                                                <small
                                                                    class="contacts-list-date float-right">2/28/2015</small>
                                                            </span>
                                                            <span class="contacts-list-msg">How have you been? I
                                                                was...</span>
                                                        </div>
                                                        <!-- /.contacts-list-info -->
                                                    </a>
                                                </li>
                                                <!-- End Contact Item -->
                                                <li>
                                                    <a href="#">
                                                        <img class="contacts-list-img" src="dist/img/user7-128x128.jpg"
                                                            alt="User Avatar">

                                                        <div class="contacts-list-info">
                                                            <span class="contacts-list-name">
                                                                Sarah Doe
                                                                <small
                                                                    class="contacts-list-date float-right">2/23/2015</small>
                                                            </span>
                                                            <span class="contacts-list-msg">I will be waiting
                                                                for...</span>
                                                        </div>
                                                        <!-- /.contacts-list-info -->
                                                    </a>
                                                </li>
                                                <!-- End Contact Item -->
                                                <li>
                                                    <a href="#">
                                                        <img class="contacts-list-img"
                                                            src="{{ Vite::asset('resources/images/user3-128x128.jpg') }}"
                                                            alt="User Avatar">

                                                        <div class="contacts-list-info">
                                                            <span class="contacts-list-name">
                                                                Nadia Jolie
                                                                <small
                                                                    class="contacts-list-date float-right">2/20/2015</small>
                                                            </span>
                                                            <span class="contacts-list-msg">I'll call you back
                                                                at...</span>
                                                        </div>
                                                        <!-- /.contacts-list-info -->
                                                    </a>
                                                </li>
                                                <!-- End Contact Item -->
                                                <li>
                                                    <a href="#">
                                                        <img class="contacts-list-img"
                                                            src="dist/img/user5-128x128.jpg" alt="User Avatar">

                                                        <div class="contacts-list-info">
                                                            <span class="contacts-list-name">
                                                                Nora S. Vans
                                                                <small
                                                                    class="contacts-list-date float-right">2/10/2015</small>
                                                            </span>
                                                            <span class="contacts-list-msg">Where is your new...</span>
                                                        </div>
                                                        <!-- /.contacts-list-info -->
                                                    </a>
                                                </li>
                                                <!-- End Contact Item -->
                                                <li>
                                                    <a href="#">
                                                        <img class="contacts-list-img"
                                                            src="dist/img/user6-128x128.jpg" alt="User Avatar">

                                                        <div class="contacts-list-info">
                                                            <span class="contacts-list-name">
                                                                John K.
                                                                <small
                                                                    class="contacts-list-date float-right">1/27/2015</small>
                                                            </span>
                                                            <span class="contacts-list-msg">Can I take a look
                                                                at...</span>
                                                        </div>
                                                        <!-- /.contacts-list-info -->
                                                    </a>
                                                </li>
                                                <!-- End Contact Item -->
                                                <li>
                                                    <a href="#">
                                                        <img class="contacts-list-img"
                                                            src="dist/img/user8-128x128.jpg" alt="User Avatar">

                                                        <div class="contacts-list-info">
                                                            <span class="contacts-list-name">
                                                                Kenneth M.
                                                                <small
                                                                    class="contacts-list-date float-right">1/4/2015</small>
                                                            </span>
                                                            <span class="contacts-list-msg">Never mind I
                                                                found...</span>
                                                        </div>
                                                        <!-- /.contacts-list-info -->
                                                    </a>
                                                </li>
                                                <!-- End Contact Item -->
                                            </ul>
                                            <!-- /.contacts-list -->
                                        </div>
                                        <!-- /.direct-chat-pane -->
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <form action="#" method="post">
                                            <div class="input-group">
                                                <input type="text" name="message" placeholder="Type Message ..."
                                                    class="form-control">
                                                <span class="input-group-append">
                                                    <button type="button" class="btn btn-primary">Send</button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.card-footer-->
                                </div>

                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <a class="btn btn-default float-right" href="{{ url()->previous() }}">
                                    Volver
                                </a>
                            </div>
                            <!-- /.card-footer -->
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

</x-app-layout>
