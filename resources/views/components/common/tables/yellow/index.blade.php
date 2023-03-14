<div {{ $attributes->class(['table_yellow'])->merge() }}>
	<div class="table_yellow__header">
		{{ $header }}
	</div>
    <div class="table_yellow__body">
		{{ $slot }}
    </div>
</div>
