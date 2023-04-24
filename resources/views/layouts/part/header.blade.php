<header class="border-bottom top-header-bottom px-xxl-110 px-xxxl-120">
	<div class="container-fluid">
		<div class="top-header">
	
			<div class="d-xl-none ps-0">
				<a data-bs-toggle="offcanvas" href="#mobile-menu" role="button" aria-controls="mobile-menu">
					<svg width="42" height="42" viewBox="0 0 42 42" fill="none"
						xmlns="http://www.w3.org/2000/svg">
						<circle cx="21" cy="21" r="21" fill="#202022" />
						<rect x="10" y="10" width="22" height="3" fill="#FCE301" />
						<rect x="10" y="28" width="22" height="3" fill="#FCE301" />
						<rect x="10" y="19" width="18" height="3" fill="#FCE301" />
					</svg>
				</a>
	
			</div>
	
			<div class="align-self-center ps-0 d-none d-xl-flex flex-column">
				@include('layouts.part.top_greetings')
			</div>

			<div class="logo d-xl-none">
				@include('layouts.part.logo')
			</div>
	
			
			<div id="notifications" class="text-end pe-0">
				<notifications></notifications>
			</div>
		</div>
    </div>
</header>
