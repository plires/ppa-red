<x-guest-layout>

    @section('css')
        <!-- DataTables -->
        @vite(['resources/css/auth/login.css'])
    @endsection

    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="./login" class="h1"><b>PPA </b>RED</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Registro de Nuevos Partners</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="input-group mb-3">
                        <input type="name" name="name" value="{{ old('name') }}" class="form-control"
                            placeholder="Nombre" required autofocus autocomplete="name">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa-solid fa-user-plus"></span>
                            </div>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />

                    <!-- Email Address -->
                    <div class="input-group mb-3">
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                            placeholder="Email" required autocomplete="username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />

                    <!-- Phone -->
                    <div class="input-group mb-3">
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control"
                            placeholder="Teléfono" required autocomplete="username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa-solid fa-mobile-screen"></span>
                            </div>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('phone')" class="mt-1" />

                    <!-- Password -->
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Contraseña nueva"
                            required autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />

                    <!-- Confirm Password -->
                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Repetir contraseña" required autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Registrar</button>
                        </div>
                        <div class="col-12 mt-2 text-center">
                            <p class="mb-1">
                                <a class="forgotPass" href="{{ route('partners.create') }}">
                                    Cambiar vista de registro
                                </a>
                            </p>
                        </div>
                    </div>

                </form>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</x-guest-layout>
