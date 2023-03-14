<div {{ $attributes->class([
	'contract-property',
	])
	->merge() }}
>
    {{ $label }}
	<span class="property-value">{{ $value }}</span>
</div>
