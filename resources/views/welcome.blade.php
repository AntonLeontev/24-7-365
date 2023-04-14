@extends('layouts.landing')

@section('title', 'Главная')

@section('content')

    <header class="header">
        <div class="container-xxl header__content">
            <div class="header__menu-button">
                <a data-bs-toggle="offcanvas" href="#mobile-menu" role="button" aria-controls="mobile-menu">
                    <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="21" cy="21" r="21" fill="#202022" />
                        <rect x="10" y="10" width="22" height="3" fill="#FCE301" />
                        <rect x="10" y="28" width="22" height="3" fill="#FCE301" />
                        <rect x="10" y="19" width="18" height="3" fill="#FCE301" />
                    </svg>
                </a>
            </div>

            <div class="header__logo">
                <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="Logo">
            </div>

			<ul class="header__nav">
				<li class="nav__item nav__item_active"><a href="#" class="nav__link">Наше предложение</a></li>
				<li class="nav__item"><a href="#" class="nav__link">Тарифы</a></li>
				<li class="nav__item"><a href="#" class="nav__link">Как это работает?</a></li>
				<li class="nav__item"><a href="#" class="nav__link">Вопросы</a></li>
				<li class="nav__item"><a href="#" class="nav__link">Вы получаете</a></li>
			</ul>

            <div class="header__auth">
				<a href="{{ route('login') }}" class="btn flex-center auth-button">
					<svg width="15" height="14" viewBox="0 0 15 14" fill="none"
						xmlns="http://www.w3.org/2000/svg">
						<path
							d="M7.5002 7.70001C2.8382 7.70001 1.2002 10.0625 1.2002 11.6375V14H13.8002V11.6375C13.8002 10.0625 12.1622 7.70001 7.5002 7.70001Z"
							fill="#FCE301" />
						<path
							d="M7.5 7C9.433 7 11 5.433 11 3.5C11 1.567 9.433 0 7.5 0C5.567 0 4 1.567 4 3.5C4 5.433 5.567 7 7.5 7Z"
							fill="#FCE301" />
					</svg>
					<span>
						Личный кабинет
					</span>
				</a>
				<a href="{{ route('login') }}" class="auth-button_mobile">
					<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
						<circle cx="16" cy="16" r="16" fill="#202022" />
						<g clip-path="url(#clip0_154_2103)">
							<path
								d="M15.9998 16.8C10.6718 16.8 8.7998 19.5 8.7998 21.3V24H23.1998V21.3C23.1998 19.5 21.3278 16.8 15.9998 16.8Z"
								fill="#FCE301" />
							<path
								d="M16 16C18.2091 16 20 14.2091 20 12C20 9.79086 18.2091 8 16 8C13.7909 8 12 9.79086 12 12C12 14.2091 13.7909 16 16 16Z"
								fill="#FCE301" />
						</g>
						<defs>
							<clipPath id="clip0_154_2103">
								<rect width="16" height="16" fill="white" transform="translate(8 8)" />
							</clipPath>
						</defs>
					</svg>
				</a>
            </div>
        </div>


        <div class="mobile-menu" id="mobile-menu" data-mdb-hidden="false">
			<div class="top-mobile-header">
				<div class="header__logo">
					<img src="{{ Vite::asset('resources/images/logo.png') }}" alt="Logo">
				</div>
				<a class="top-mobile-header_close" data-bs-toggle="offcanvas"
					href="#mobile-menu" role="button" aria-controls="mobile-menu">
					<svg width="56" height="56" viewBox="0 0 56 56" fill="none"
						xmlns="http://www.w3.org/2000/svg">
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
									values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
									values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
									values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
									values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
									values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
									values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
									values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" in="SourceAlpha"
									result="hardAlpha" />
								<feOffset />
								<feGaussianBlur stdDeviation="6" />
								<feComposite in2="hardAlpha" operator="out" />
								<feColorMatrix type="matrix"
									values="0 0 0 0 0.988235 0 0 0 0 0.889804 0 0 0 0 0.00392157 0 0 0 0.24 0"
									values="0 0 0 0 0.988235 0 0 0 0 0.889804 0 0 0 0 0.00392157 0 0 0 0.24 0"
									values="0 0 0 0 0.988235 0 0 0 0 0.889804 0 0 0 0 0.00392157 0 0 0 0.24 0"
									values="0 0 0 0 0.988235 0 0 0 0 0.889804 0 0 0 0 0.00392157 0 0 0 0.24 0"
									values="0 0 0 0 0.988235 0 0 0 0 0.889804 0 0 0 0 0.00392157 0 0 0 0.24 0"
									values="0 0 0 0 0.988235 0 0 0 0 0.889804 0 0 0 0 0.00392157 0 0 0 0.24 0"
									values="0 0 0 0 0.988235 0 0 0 0 0.889804 0 0 0 0 0.00392157 0 0 0 0.24 0"
									values="0 0 0 0 0.988235 0 0 0 0 0.889804 0 0 0 0 0.00392157 0 0 0 0.24 0" />
								<feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_226_2575" />
								<feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_226_2575"
									result="shape" />
							</filter>
							<clipPath id="clip0_226_2575">
								<rect width="18" height="18" fill="white" transform="translate(19 19)" />
							</clipPath>
						</defs>
					</svg>
				</a>
			</div>
			<div class="menu__wrap">
				<ul class="menu__list">
					<li class="menu__item">
						<a href="#" class="menu__link">Наше предложение</a>
					</li>
					<li class="menu__item"><a href="#" class="menu__link">Тарифы</a></li>
					<li class="menu__item"><a href="#" class="menu__link">Как это работает?</a></li>
					<li class="menu__item"><a href="#" class="menu__link">Вопросы</a></li>
					<li class="menu__item menu__item_active"><a href="#" class="menu__link">Вы получаете</a></li>
				</ul>

				<a href="{{ route('login') }}" class="menu-btn btn btn-outline-primary flex-center">
					<svg width="15" height="14" viewBox="0 0 15 14" fill="none"
						xmlns="http://www.w3.org/2000/svg">
						<path
							d="M7.5002 7.70001C2.8382 7.70001 1.2002 10.0625 1.2002 11.6375V14H13.8002V11.6375C13.8002 10.0625 12.1622 7.70001 7.5002 7.70001Z"
							fill="#FCE301" />
						<path
							d="M7.5 7C9.433 7 11 5.433 11 3.5C11 1.567 9.433 0 7.5 0C5.567 0 4 1.567 4 3.5C4 5.433 5.567 7 7.5 7Z"
							fill="#FCE301" />
					</svg>
					<span>
						Личный кабинет
					</span>
				</a>
			</div>
        </div>
    </header>

	<div class="firstscreen">
		<div class="container-xxl firstscreen__content">
			<div class="content-wrap">
				<div class="text-wrap">
					<h1 class="firstscreen__h1">
						<span class="text-primary">Прибыльный товарный бизнес,</span> созданный для того, чтобы помочь Вам зарабатывать <span class="text-primary">от 25% годовых!</span>
					</h1>
		
					<p class="firstscreen__text">
						Помогаем получить от 24% до 100% в год по модели “True Trade Deal” (Честная Торговая Сделка).
					</p>
		
					<a href="{{ route('register') }}" class="btn btn-primary firstscreen__button">Начать зарабатывать</a>
				</div>
				
				<div class="picture-wrap">
					<picture>
						<source srcset="{{ Vite::asset('resources/images/cart-mob.webp') }}" media="(max-width: 992px)">
						<source srcset="{{ Vite::asset('resources/images/cart-lap.webp') }}" media="(max-width: 1200px)">
						<source srcset="{{ Vite::asset('resources/images/cart-desc.webp') }}">
						<img class="firstscreen__image" src="{{ Vite::asset('resources/images/cart-mob.png') }}" alt="cart-mob">
					</picture>
				</div>
			</div>
		</div>

		<div class="bg-image"></div>


		<div class="yellow-stripe"></div>
	</div>


    {{-- <div class="roadmap">
        <div class="bordered p-3">
            <div class="bordered__number">01</div>
            <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus tempore molestias eligendi voluptatum,
                ipsam quasi blanditiis officia odio sit amet, sed esse vero laboriosam temporibus. Illo quod accusamus ipsam
                eum?</div>
        </div>
        <div class="bordered p-3">
            <div class="bordered__number">02</div>
            <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A maiores, laboriosam repellendus, nihil magnam
                ab placeat deleniti molestias rem, at non. Voluptas, excepturi. Nam, ut consectetur reprehenderit hic et
                iure!</div>
        </div>
        <div class="bordered p-3">
            <div class="bordered__number">03</div>
            <div>Lorem ipsum dolor sit amet consectetur adipisicing elit. At maiores id aperiam eveniet quos quaerat quam
                dolor, possimus blanditiis tenetur fuga fugit. Libero, temporibus fuga. Necessitatibus amet accusamus
                facilis dicta!</div>
        </div>
        <div class="bordered p-3">
            <div class="bordered__number">04</div>
            <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus tempore molestias eligendi voluptatum,
                ipsam quasi blanditiis officia odio sit amet, sed esse vero laboriosam temporibus. Illo quod accusamus ipsam
                eum?</div>
        </div>
        <div class="bordered p-3">
            <div class="bordered__number">05</div>
            <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A maiores, laboriosam repellendus, nihil magnam
                ab placeat deleniti molestias rem, at non. Voluptas, excepturi. Nam, ut consectetur reprehenderit hic et
                iure!</div>
        </div>
        <div class="bordered p-3">
            <div class="bordered__number">06</div>
            <div>Lorem ipsum dolor sit amet consectetur adipisicing elit. At maiores id aperiam eveniet quos quaerat quam
                dolor, possimus blanditiis tenetur fuga fugit. Libero, temporibus fuga. Necessitatibus amet accusamus
                facilis dicta!</div>
        </div>
        <div class="bordered p-3">
            <div class="bordered__number">07</div>
            <div>Lorem ipsum dolor sit amet consectetur adipisicing elit. At maiores id aperiam eveniet quos quaerat quam
                dolor, possimus blanditiis tenetur fuga fugit. Libero, temporibus fuga. Necessitatibus amet accusamus
                facilis dicta!</div>
        </div>
        <div class="bordered p-3">
            <div class="bordered__number">08</div>
            <div>Lorem ipsum dolor sit amet consectetur adipisicing elit. At maiores id aperiam eveniet quos quaerat quam
                dolor, possimus blanditiis tenetur fuga fugit. Libero, temporibus fuga. Necessitatibus amet accusamus
                facilis dicta!</div>
        </div>
    </div> --}}
@endsection
