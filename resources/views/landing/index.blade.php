<x-guest-layout>
    @section('scripts')
        @viteReactRefresh
        @vite('resources/js/landing/app.jsx')
    @endsection

    <div id="landing-root"></div>
</x-guest-layout>
