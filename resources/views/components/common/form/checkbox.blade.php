@props([
	'name',
	'checked' => false,
	'disabled' => false,
])

<div class="form-check" @disabled($disabled)>
	<label {{ $attributes->class(["form-check-label lh-base"])->merge() }}>
	  	<input 
			class="form-check-input fs-5" 
			type="checkbox" 
			name="{{ $name }}" 
			@checked($checked) 
	  		@disabled($disabled)
		>
		{{ $slot }}
	</label>
</div>
