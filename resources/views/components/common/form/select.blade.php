@props([
	'name',
	'label' => '',
	'disabled' => false,
])

<div {{ $attributes->class(['form-selection'])->merge() }}>
	<select class="form-select" name="{{ $name }}" aria-label="Default select example" @disabled($disabled)>
		{{ $slot }}
	</select>
	<label class="form-label">{{ $label }}</label>
	<svg class="selection-svg" width='16' height='10' viewBox='0 0 16 10' xmlns='http://www.w3.org/2000/svg'><path class="arrow" d='M16 1.79687L8 9.79687L-3.49691e-07 1.79687L1.42 0.376875L8 6.95687L14.58 0.376875L16 1.79687Z'/></svg>
</div>

