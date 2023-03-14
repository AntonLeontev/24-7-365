<div {{ $attributes->class(['table_dark'])->merge() }}>
	<div class="table_dark__header">
		{{ $header }}
	</div>
    <div class="table_dark__body">
		{{ $slot }}
    </div>
</div>
