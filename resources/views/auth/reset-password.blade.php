<x-guest-layout>

    @section('css')
        <!-- DataTables -->
        @vite(['resources/css/auth/reset-password.css'])
    @endsection

    <section class="login-page resetPassword">
        <div class="login-box">

            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <span class="h1"><b>PPA </b>RED</span>
                    <img src="{{ Vite::asset('resources/images/ppa-red-auth.webp') }}" alt="ppa red">
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Login para iniciar tu sesión</p>

                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
                        <x-input-label for="email" :value="__('Email')" />
                        <div class="input-group mb-3">
                            <input id="email" type="email" name="email"
                                value="{{ old('email', $request->email) }}" class="form-control" placeholder="Email"
                                required autofocus autocomplete="username">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />

                        <!-- Password -->
                        <x-input-label for="password" :value="__('Password')" />
                        <div class="input-group mb-3">
                            <input id="password" type="password" name="password" class="form-control"
                                placeholder="Password" required autocomplete="new-password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />

                        <!-- Confirm Password -->
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <div class="input-group mb-3">
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                class="form-control" placeholder="Password" required autocomplete="new-password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-block">Restablecer Contraseña</button>
                            </div>
                        </div>

                    </form>

                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
</x-guest-layout>
