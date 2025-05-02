<x-app-layout>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        @include('parts.msg-success')
        @include('parts.msg-errors')

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Creación del Partner</h1>
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
                                <h3 class="card-title">Agregá los datos del partner
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-horizontal" method="POST" action="{{ route('partners.store') }}">
                                @csrf

                                <div class="card-body">

                                    {{-- Nombre --}}
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                                        <div class="col-sm-10">
                                            <input required class="form-control" id="name" placeholder="Nombre"
                                                type="text" name="name" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    {{-- Nombre end --}}

                                    {{-- Email --}}
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input required class="form-control" id="name" placeholder="Email"
                                                type="email" name="email" value="{{ old('email') }}">
                                        </div>
                                    </div>
                                    {{-- Email end --}}

                                    {{-- Teléfono --}}
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Teléfono</label>
                                        <div class="col-sm-10">
                                            <input required class="form-control" id="name" placeholder="Teléfono"
                                                type="text" name="phone" value="{{ old('phone') }}">
                                        </div>
                                    </div>
                                    {{-- Teléfono end --}}

                                    {{-- Password --}}
                                    <div class="form-group row">
                                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                                        <div class="col-sm-10">
                                            <div class="input-group input-group-sm">
                                                <input required class="form-control" id="password"
                                                    placeholder="Contraseña" type="password" name="password"
                                                    value="{{ old('password') }}">
                                                <span type="button" id="togglePassword" class="input-group-append">
                                                    <button type="button" class="btn btn-info btn-flat">
                                                        <i class="fas fa-eye-slash" id="passwordIcon"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Password end --}}

                                    {{-- Password Repite --}}
                                    <div class="form-group row">
                                        <label for="password_confirmation" class="col-sm-2 col-form-label">Repetir la
                                            Password</label>
                                        <div class="col-sm-10">
                                            <div class="input-group input-group-sm">
                                                <input required class="form-control" id="password_confirmation"
                                                    placeholder="Repetir la Contraseña" type="password"
                                                    name="password_confirmation"
                                                    value="{{ old('password_confirmation') }}">
                                                <span type="button" id="togglePasswordConfirm"
                                                    class="input-group-append">
                                                    <button type="button" class="btn btn-info btn-flat">
                                                        <i class="fas fa-eye-slash" id="passwordConfirmIcon"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Password Repite end --}}

                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-info float-right ml-3">Guardar
                                        cambios</button>
                                    <a class="btn btn-default float-right" href="{{ route('partners.index') }}">
                                        Cancelar y volver
                                    </a>
                                </div>
                                <!-- /.card-footer -->
                            </form>
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
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const togglePassword = document.querySelector('#togglePassword');
                const togglePasswordConfirm = document.querySelector('#togglePasswordConfirm');
                const password = document.querySelector('#password');
                const passwordConfirmation = document.querySelector('#password_confirmation');
                const passwordIcon = document.querySelector('#passwordIcon');
                const passwordConfirmIcon = document.querySelector('#passwordConfirmIcon');

                // Alternar visibilidad de la contraseña
                togglePassword.addEventListener('click', function() {
                    const type = password.type === 'password' ? 'text' : 'password';
                    password.type = type;

                    // Alternar icono entre ojo cerrado y abierto
                    passwordIcon.classList.toggle('fa-eye-slash');
                    passwordIcon.classList.toggle('fa-eye');
                });

                // Alternar visibilidad de la confirmación de la contraseña
                togglePasswordConfirm.addEventListener('click', function() {
                    const type = passwordConfirmation.type === 'password' ? 'text' : 'password';
                    passwordConfirmation.type = type;

                    // Alternar icono entre ojo cerrado y abierto
                    passwordConfirmIcon.classList.toggle('fa-eye-slash');
                    passwordConfirmIcon.classList.toggle('fa-eye');
                });
            });
        </script>
    @endsection
</x-app-layout>
