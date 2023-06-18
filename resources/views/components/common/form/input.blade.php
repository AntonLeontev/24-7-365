@props([
	'type' => 'text',
	'name',
	'placeholder' => '',
	'value' => '',
	'label' => '',
	'id' => '',
	'pattern' => '',
	'disabled' => false,
	'max' => '255',
	'min' => '',
	'readonly' => false,
	'tabindex' => false,
	'required' => false,
	])

<div {{ $attributes->class('form-input')->merge() }}>
	<input 
		type="{{ $type }}" 
		@if($type === 'date') {{ $pattern }} @endif
		class="form-control"  
		name="{{ $name }}" 
		placeholder="{{ $placeholder }}" 
		value="{{ $value }}" 
		id="{{ $id }}" 
		@disabled($disabled) 
		autocorrect="off" 
		autocomplete="off" 
		autocapitalize="off" 
		@if($readonly) readonly @endif 
		@if($tabindex) tabindex="{{ $tabindex }}" @endif
		v-on:input="errors.{{ $name }} = null" 
		:class="{'border-primary': errors.{{ $name }}}"
		@required($required)
	>
	<label class="form-label" v-show="!errors.{{ $name }}">
		@if (! empty($value))
			{{ $placeholder ?? $label }}
		@else
			{{ $label }}
		@endif
	</label>
	<label class="form-label text-primary" v-cloak v-show="errors.{{ $name }}" v-text="errors.{{ $name }}"></label>
</div>
