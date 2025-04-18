<x-guest-layout>

    @section('css')
        <!-- DataTables -->
        @vite(['resources/css/auth/login.css'])
    @endsection

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="./login" class="h1"><b>PPA </b>RED</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Login para iniciar tu sesión</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="input-group mb-3">
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                            placeholder="Email" required autofocus autocomplete="username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required
                            autocomplete="current-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox" z name="remember">
                                    <span class="ms-2 text-sm">{{ __('Remember me') }}</span>
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                @if (Route::has('password.request'))
                    <p class="mb-1">
                        <a class="forgotPass" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    </p>
                @endif
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!-- /.card -->
</x-guest-layout>
