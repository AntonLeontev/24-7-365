@props(['modalTitle'])

<div {{ $attributes->class(['modal', 'fade'])->merge() }} aria-labelledby="exampleModalLabel" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title pe-3">{{ $modalTitle ?? '' }}</h5>
				<button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Закрыть">
			</div>
			<div class="modal-body">
				{{ $slot }}
			</div>
		</div>
	</div>
</div>
