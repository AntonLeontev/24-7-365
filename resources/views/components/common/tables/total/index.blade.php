<div {{ $attributes->class(['total'])->merge() }}>
    <div class="total-header">{{ $header }}</div>
    {{ $slot }}
</div>
