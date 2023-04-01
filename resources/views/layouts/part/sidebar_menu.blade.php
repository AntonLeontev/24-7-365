<div class="sidebar-menu d-none d-xl-flex flex-column justify-content-between text-bg-dark col-auto flex-shrink-0 min-vh-100">

	<div class="h-100 mb-4">
		<div class="position-sticky top-0">
			<div class="sidebar-header">
		
				<div class="logo">
					@include('layouts.part.logo')
				</div>
			</div>
		
			@include('layouts.part.sidebar_menu_list')
		</div>
	</div>


    <div>

        @include('layouts.part.socials_links')

        <div class="sidebar-bottom">
            @include('layouts.part.agreements')
            <br>
            @include('layouts.part.copyright')


        </div>

    </div>



</div>


<div class="offcanvas offcanvas-start mobile-menu" id="mobile-menu" data-mdb-hidden="false">

    <div class="top-mobile-header">
        @include('layouts.part.top_greetings')
        <a class="position-absolute top-50 start-100 translate-middle" data-bs-toggle="offcanvas" href="#mobile-menu" role="button" aria-controls="mobile-menu">
            <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g filter="url(#filter0_d_226_2575)">
                    <circle cx="28" cy="28" r="16" fill="#FCE301" />
                </g>
                <g clip-path="url(#clip0_226_2575)">
                    <path
                        d="M34.6856 36.9125L28 30.2268L21.3144 36.9125L19.0858 34.6839L25.7714 27.9983L19.0858 21.3126L21.3144 19.0841L28 25.7697L34.6856 19.0841L36.9142 21.3126L30.2285 27.9983L36.9142 34.6839L34.6856 36.9125Z"
                        fill="#202022" />
                </g>
                <defs>
                    <filter id="filter0_d_226_2575" x="0" y="0" width="56" height="56"
                        filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix" />
                        <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" in="SourceAlpha" result="hardAlpha" />
                        <feOffset />
                        <feGaussianBlur stdDeviation="6" />
                        <feComposite in2="hardAlpha" operator="out" />
                        <feColorMatrix type="matrix"
                            values="0 0 0 0 0.988235 0 0 0 0 0.889804 0 0 0 0 0.00392157 0 0 0 0.24 0"
                            values="0 0 0 0 0.988235 0 0 0 0 0.889804 0 0 0 0 0.00392157 0 0 0 0.24 0" />
                        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_226_2575" />
                        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_226_2575" result="shape" />
                    </filter>
                    <clipPath id="clip0_226_2575">
                        <rect width="18" height="18" fill="white" transform="translate(19 19)" />
                    </clipPath>
                </defs>
            </svg>
        </a>
    </div>
	<div class="overflow-auto h-100">
		@include('layouts.part.sidebar_menu_list')
	</div>


</div>
