<x-guest-layout>

    <!-- Session Status -->
    <div class="lockscreen-wrapper">
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <div class="lockscreen-logo">
            <a href="../../index2.html"><b>PPA</b>RED</a>
        </div>
        <!-- User name -->
        <div class="lockscreen-name">usuario</div>

        <!-- START LOCK SCREEN ITEM -->
        <div class="lockscreen-item">
            <!-- lockscreen image -->
            <div class="lockscreen-image">
                <img src="{{ Vite::asset('resources/images/circle-logo-partner.webp') }}" alt="User Image">
            </div>
            <!-- /.lockscreen-image -->

            <!-- lockscreen credentials (contains the form) -->
            <form method="POST" action="{{ route('password.email') }}" class="lockscreen-credentials">
                @csrf
                <div class="input-group">
                    <input id="email" name="email" value="{{ old('email') }}" class="form-control"
                        placeholder="Email" required autofocus>

                    <div class="input-group-append">
                        <button type="submit" class="btn">
                            <i class="fas fa-arrow-right text-muted"></i>
                        </button>
                    </div>
                </div>
            </form>
            <!-- /.lockscreen credentials -->

        </div>
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
        <!-- /.lockscreen-item -->
        <div class="help-block mb-4 text-sm text-center">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>
        <div class="text-center">
            <a href="{{ route('login') }}">O inicie sesión como un usuario diferente</a>
        </div>

    </div>
</x-guest-layout>
