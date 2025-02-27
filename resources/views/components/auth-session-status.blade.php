@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-sm text-center']) }}>
        {{ $status }}
    </div>
@endif
