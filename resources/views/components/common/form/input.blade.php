@props([
	'type' => 'text',
	'name',
	'placeholder' => '',
	'value' => '',
	'label' => '',
	'disabled' => false,
	])

<div {{ $attributes->class('form-input')->merge() }}>
	<input type="{{ $type }}" class="form-control"  name="{{ $name }}" placeholder="{{ $placeholder }}" 
	value="{{ $value }}" @disabled($disabled)>
	<label class="form-label">{{ $label }}</label>
</div>
