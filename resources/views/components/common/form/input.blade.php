@props([
	'type' => 'text',
	'name',
	'placeholder' => '',
	'value' => '',
	'label' => '',
	'id' => '',
	'disabled' => false,
	'max' => '255',
	'min' => '',
	])

<div {{ $attributes->class('form-input')->merge() }}>
	<input type="{{ $type }}" class="form-control"  name="{{ $name }}" placeholder="{{ $placeholder }}" 
	value="{{ $value }}" id="{{ $id }}" @disabled($disabled) autocorrect="off" autocomplete="off" autocapitalize="off"
	v-on:input="errors.{{ $name }} = null" :class="{'border-primary': errors.{{ $name }}}">
	<label class="form-label" v-show="!errors.{{ $name }}">
		@if (! empty($value))
			{{ $placeholder ?? $label }}
		@else
			{{ $label }}
		@endif
	</label>
	<label class="form-label text-primary" v-cloak v-show="errors.{{ $name }}" v-text="errors.{{ $name }}"></label>
</div>
