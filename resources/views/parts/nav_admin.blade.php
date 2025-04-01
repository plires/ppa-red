<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../../index3.html" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- Messages Dropdown Menu -->
        @if (auth()->user()->isPartner() && auth()->user()->unreadCommentsCount() > 0)
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-comments"></i>
                    <span class="badge badge-danger navbar-badge">
                        {{ auth()->user()->unreadCommentsCount() }}
                    </span>
                </a>

                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                    @foreach (auth()->user()->formSubmissions as $formSubmission)
                        @foreach ($formSubmission->formResponses as $formResponse)
                            @if (!$formResponse->is_read && !$formResponse->is_system)
                                @php
                                    $data = json_decode($formSubmission->data, true); // Convierte JSON en array
                                @endphp

                                <a href="{{ route('responses.mark_as_read', $formResponse->id) }}"
                                    class="dropdown-item">
                                    <!-- Message Start -->
                                    <div class="media">
                                        <img src="{{ Vite::asset('resources/images/user1-128x128.jpg') }}"
                                            alt="User Avatar" class="img-size-50 mr-3 img-circle">
                                        <div class="media-body">
                                            <h3 class="dropdown-item-title">
                                                {{ $data['name'] }}
                                                {{ $formResponse->id }}
                                                <span class="float-right text-sm text-danger"><i
                                                        class="fas fa-star"></i></span>
                                            </h3>
                                            <p class="text-sm">{{ $formResponse->message }}.</p>
                                            <p class="text-sm text-muted"><i
                                                    class="far fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($formResponse->created_at)->diffForHumans(['short' => true]) }}
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Message End -->
                                </a>
                            @endif
                        @endforeach
                    @endforeach

                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('responses.mark_as_all_read', $formResponse->user_id) }}">
                        @csrf
                        <button type="submit" class="dropdown-item dropdown-footer">Marcar todos como leídos</button>
                    </form>
                </div>
            </li>
        @else
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-comments"></i>
                    <span class="badge badge-danger navbar-badge">
                        0
                    </span>
                </a>

                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <div class="dropdown-divider"></div>
                    <div class="dropdown-item dropdown-footer">Todas los comentarios leídos</div>
                </div>
            </li>
        @endif

        <!-- Notifications Dropdown Menu -->
        @if (auth()->user()->isPartner() && auth()->user()->unreadNotificationsCount() > 0)



            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span
                        class="badge badge-warning navbar-badge">{{ auth()->user()->unreadNotificationsCount() }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">{{ auth()->user()->unreadNotificationsCount() }}
                        Notificaciones</span>
                    <div class="dropdown-divider"></div>


                    @foreach (auth()->user()->formSubmissions as $formSubmission)
                        @foreach ($formSubmission->notifications as $notification)
                            @if (!$notification->is_read)
                                <a title="{{ $notification->notification_details }}"
                                    href="{{ route('notification.mark_as_read', ['notification' => $notification->id, 'formSubmission' => $formSubmission->id]) }}"
                                    class="dropdown-item">
                                    <div>
                                        <i class="fas fa-envelope mr-2"></i>
                                        <span class="float-left text-muted text-sm">
                                            {{ $notification->notification_details }}
                                        </span>
                                    </div>
                                    <span
                                        class="float-right text-muted text-sm">{{ \Carbon\Carbon::parse($formResponse->created_at)->diffForHumans(['short' => true]) }}
                                    </span>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endif
                        @endforeach
                    @endforeach

                    <form method="POST" action="{{ route('notification.mark_as_all_read') }}">
                        @csrf
                        <button type="submit" class="dropdown-item dropdown-footer">Marcar todas como leídas</button>
                    </form>

                </div>
            </li>
        @else
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">Sin Notificaciones</span>
                    <div class="dropdown-divider"></div>
                </div>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

    </ul>
</nav>
<!-- /.navbar -->
