@props([
	'type' => 'text',
	'name',
	'placeholder' => '',
	'value' => '',
	'label' => '',
	'id' => '',
	'disabled' => false,
	])

<div {{ $attributes->class('form-input')->merge() }}>
	<input type="{{ $type }}" class="form-control"  name="{{ $name }}" placeholder="{{ $placeholder }}" 
	value="{{ $value }}" id="{{ $id }}" @disabled($disabled) autocorrect="off" autocomplete="off" autocapitalize="off">
	<label class="form-label">{{ $label }}</label>
</div>
