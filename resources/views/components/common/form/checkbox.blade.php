@props([
	'name',
	'label' => '',
	'checked' => false,
	'disabled' => false,
])

<div class="form-check" @disabled($disabled)>
	<label class="form-check-label">
	  <input class="form-check-input" type="checkbox" name="{{ $name }}" @checked($checked) 
	  @disabled($disabled)>
    {{ $label }}
  </label>
</div>
