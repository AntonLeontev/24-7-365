@props([
	'type' => 'text',
	'name',
	'placeholder' => '',
	'value' => '',
	'label' => ''
	])

<div {{ $attributes->class('form-input')->merge() }}>
	<input type="{{ $type }}" class="form-control"  name="{{ $name }}" placeholder="{{ $placeholder }}" 
	value="{{ $value }}">
	<label class="form-label">{{ $label }}</label>
</div>
