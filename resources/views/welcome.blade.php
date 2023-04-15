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
				<a href="#">
					<img src="{{ Vite::asset('resources/images/logo.png') }}" alt="Logo">
				</a>
                
            </div>

            <ul class="header__nav">
                <li class="nav__item nav__item_active"><a class="nav__link" href="#offer">Наше предложение</a></li>
                <li class="nav__item"><a class="nav__link" href="#tariffs">Тарифы</a></li>
                <li class="nav__item"><a class="nav__link" href="#howitworks">Как это работает?</a></li>
                <li class="nav__item"><a class="nav__link" href="#faq">Вопросы</a></li>
                <li class="nav__item"><a class="nav__link" href="#whatyouget">Вы получаете</a></li>
            </ul>

            <div class="header__auth">
                <a class="btn flex-center auth-button" href="{{ route('login') }}">
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
                <a class="auth-button_mobile" href="{{ route('login') }}">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
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
                <a class="top-mobile-header_close" data-bs-toggle="offcanvas" href="#mobile-menu" role="button"
                    aria-controls="mobile-menu">
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
                                    values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                    values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
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
                                    values="0 0 0 0 0.988235 0 0 0 0 0.889804 0 0 0 0 0.00392157 0 0 0 0.24 0"
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
                        <a class="menu__link" href="#offer">Наше предложение</a>
                    </li>
                    <li class="menu__item"><a class="menu__link" href="#tariffs">Тарифы</a></li>
                    <li class="menu__item"><a class="menu__link" href="#howitworks">Как это работает?</a></li>
                    <li class="menu__item"><a class="menu__link" href="#faq">Вопросы</a></li>
                    <li class="menu__item menu__item_active"><a class="menu__link" href="#whatyouget">Вы получаете</a></li>
                </ul>

                <a class="menu-btn btn btn-outline-primary flex-center" href="{{ route('login') }}">
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
                    <h1 class="title firstscreen__h1">
                        <span class="text-primary">Прибыльный товарный бизнес,</span> созданный для того, чтобы помочь Вам
                        зарабатывать <span class="text-primary">от 25% годовых!</span>
                    </h1>

                    <p class="text firstscreen__text">
                        Помогаем получить от 24% до 100% в год по модели “True Trade Deal” (Честная Торговая Сделка).
                    </p>

                    <a class="btn btn-primary firstscreen__button" href="{{ route('register') }}">Начать зарабатывать</a>
                </div>

                <div class="picture-wrap">
                    <picture>
                        <source srcset="{{ Vite::asset('resources/images/cart-mob.webp') }}" media="(max-width: 992px)">
                        <source srcset="{{ Vite::asset('resources/images/cart-lap.webp') }}" media="(max-width: 1200px)">
                        <source srcset="{{ Vite::asset('resources/images/cart-desc.webp') }}">
                        <img class="firstscreen__image" src="{{ Vite::asset('resources/images/cart-mob.png') }}"
                            alt="cart-mob">
                    </picture>
                </div>
            </div>
        </div>

        <div class="bg-image"></div>


        <div class="yellow-stripe"></div>
    </div>

    <div class="offer" id="offer">
        <div class="stripe__primary"></div>
        <div class="offer__content">
            <div class="container-xxl">
                <h2 class="title offer__title"><span class="text-primary">наше предложение</span> «True Trade Deal»</h2>
                <p class="text offer__text">Давайте объединим Ваши свободные средства и наш опыт.</p>
                <div class="offer__cards">
                    <div class="offer__card">
                        <div class="card__title">У нас есть большой опыт</div>
                        <div class="card__body">Мы умеем закупать максимально актуальные товары рынка розничных продаж … и
                            умеем быстро их продавать через Ozon, WB и Яндекс-Маркет … но у нас не хватает оборотных средств
                            в таком объеме, чтобы вывести нашу маржу на максимально достижимый уровень.</div>
                    </div>
                    <div class="offer__card">
                        <div class="card__title">Мы ищем партнёров</div>
                        <div class="card__body">Мы ищем партнёров по закупкам, которые не так опытны в отборе, закупке и
                            продаже актуальных товаров … но у котрых есть оборотные средства, которых нам не хватает (даже
                            если это всего 500 тыс. или 1 млн. р, который мы можем объединить с большим количеством
                            миллионов рублей других наших Партнеров по закупкам).</div>
                    </div>
                    <div class="offer__card">
                        <div class="card__title">Давайте объединимся</div>
                        <div class="card__body">Давайте объединим наши ресурсные возможности (наши опыт, знания, умения и
                            наши собственные средства в оборотке … и ваши средства) … добьемся максимальной маржинальности
                            торговых операций нашей компании … и честно поделим итоговую операционную прибыль.</div>
                    </div>
                    <div class="offer__card">
                        <div class="card__title">Мы предлагаем Вам доходность</div>
                        <div class="card__body">По нашей оценке, в зависимости от условий предоставления нам ваших средств
                            в оборотку, Ваша ожидаемая доходность в год может колебаться от 24 до 100% годовых … а при
                            определенных условиях, от 150 до 200% годовых
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="charge">
        <div class="container-xxl">
            <div class="title charging__title">Исходя из нашего опыта, <span class="text-primary">вы поручаете нам</span>:
            </div>
            <div class="text charging__text">
                Если вы предоставите свободные средства, все остальное (выбор товаров, поставщиков, закупка товаров,
                логистика, продажи и др.) сделаем мы!
                Полученную в итоге доходность поделим так, чтобы Ваша доходность всегда была не ниже Ваших ожиданий.
            </div>
            <div class="charging__cards">
                <div class="card">
                    <div class="card__image"></div>
                    <div class="card__text">Выбрать поставщиков, интересующих нас товаров.</div>
                </div>
                <div class="card">
                    <div class="card__image"></div>
                    <div class="card__text">Закупить эти товары на полученые от вас средства.</div>
                </div>
                <div class="card">
                    <div class="card__image"></div>
                    <div class="card__text">Организовать логистику доставки этих товаров.</div>
                </div>
                <div class="card">
                    <div class="card__image"></div>
                    <div class="card__text">Организовать продажи этих товаров в полном объеме.</div>
                </div>
            </div>
        </div>
        <div class="stripe__primary stripe__primary_horizontal"></div>
    </div>

    <div class="tariffs" id="tariffs">
        <div class="container-xxl">
            <div class="tariffs__description">
                <h2 class="title tariffs__title">Наши Тарифы:</h2>
                <p class="text tariffs__text">Если вы предоставите свободные средства, все остальное (выбор товаров,
                    поставщиков, закупка товаров, логистика, продажи и др.) сделаем мы!</p>
                <p class="text tariffs__text">Полученную в итоге доходность поделим так, чтобы Ваша доходность всегда была
                    не ниже Ваших ожиданий.</p>
                <a class="btn btn-outline-primary" href="">Стать партнёром</a>
            </div>
            <div class="tariffs__slider">

            </div>
        </div>
    </div>


    <div class="roadmap">
        <div class="container-xxl">
            <h2 class="title roadmap__title">
                <span class="text-primary">Этапы</span> работы с нами
            </h2>

            <div class="roadmap__map">
                <div class="bordered">
                    <div class="bordered__number">01</div>
                    <div class="text bordered__text">
                        Вам нужно зарегистрировать личный кабинет и выбрать один из 5 вариантов сотрудничества.
                    </div>
                </div>
                <div class="bordered">
                    <div class="bordered__number">02</div>
                    <div class="text bordered__text">
                        Прочитайте внимательно Договор и Приложение к нему и подтвердите согласие с условиями Договора и
                        выбранного вами Приложения.
                    </div>
                </div>
                <div class="bordered">
                    <div class="bordered__number">03</div>
                    <div class="text bordered__text">
                        Оформите ИП или ООО, если Вы этого не сделали ранее. Если надо, мы можем помочь Вам зарегистрировать
                        ИП или ООО.
                    </div>
                </div>
                <div class="bordered">
                    <div class="bordered__number">04</div>
                    <div class="text bordered__text">
                        В личном кабинете укажите ИНН и расчетный счет Вашего ИП или ООО.
                    </div>
                </div>
                <div class="bordered">
                    <div class="bordered__number">05</div>
                    <div class="text bordered__text">
                        Определитесь с суммой закупки товаров.
                    </div>
                </div>
                <div class="bordered">
                    <div class="bordered__number">06</div>
                    <div class="text bordered__text">
                        Сгенерите платежное поручение и переведите нам средства на закупку товаров.
                    </div>
                </div>
                <div class="bordered">
                    <div class="bordered__number">07</div>
                    <div class="text bordered__text">
                        Чтобы вы могли лучше контролировать процесс, в личном кабинете появится график платежей ожидаемой
                        доходности и полного возврата суммы вложенных средств.
                    </div>
                </div>
                <div class="bordered">
                    <div class="bordered__number">08</div>
                    <div class="text bordered__text">
                        Получаете доход со своих вложений по выбранному вами тарифу!
                    </div>
                </div>
                <div class="roadmap__image">
                    <picture>
                        <source srcset="" media="(min-width: )">
                        <img src="" alt="roadmap image">
                    </picture>
                </div>
            </div>
        </div>

        <div class="stripe__primary stripe__primary_horizontal"></div>
    </div>


    <div class="why" id="why">
        <div class="stripe__primary"></div>
        <div class="why__content">
            <div class="container-xxl">
                <div class="title why__title">
                    <span class="text-primary">Почему схема</span> True Trade Deal <span
                        class="text-primary">выгоднее</span> собственного магазина?
                </div>
                <p class="text why__text">
                    Многие начинающие предприниматели мечтают о собственном магазине. Вроде бы открыть товарный бизнес
                    сейчас достаточно просто, необходимо лишь зарегистрировать ИП, закупить товар и продавать его на Ozon,
                    Wildberries, Яндекс. Маркете или любой другой онлайн-площадке.
                </p>
                <p class="text why__text">
                    Однако эта простая схема содержит много подводных камней, которые, как снежный ком, обрушиваются на
                    голову начинающего бизнесмена. И вот через пару месяцев мы получаем разочарованного человека, который
                    остался без денег, но с кучей неликвидного товара на руках. Знакомая ситуация?
                </p>
                <a class="btn btn-primary" href="{{ route('register') }}">Начать зарабатывать</a>
            </div>
        </div>
    </div>

    <div class="howitworks" id="howitworks">
        <div class="container-xxl">
            <h2 class="title howitworks__title">Как <span class="text-primary">это работает</span>?</h2>
            <p class="text howitworks__text">
                Давайте разберемся, как устроена наша модель:
            </p>
            <div class="howitworks__cards">
                <div class="card">
                    <div class="card__title">Вы переводите средства</div>
                    <div class="card__body">Для закупки товаров, Вы с р/с вашего ИП переводите нам по договору, например, 1
                        млн.р. (Агентский договор, по которому вы нанимаете нас за Агентское вознаграждение исполнить всю
                        вышеозначенную работу).</div>
                </div>
                <div class="card">
                    <div class="card__title">Мы осуществляем закупки</div>
                    <div class="card__body">Эти деньги поступают в оборот: мы закупаем товары и продаем их с максимально
                        возможной маржинальностью. На реализацию одной партии товара в среднем уходит 1-2 месяца</div>
                </div>
                <div class="card">
                    <div class="card__title">Реализуем товар закупленный на полученые от вас средства</div>
                    <div class="card__body">
                        В итоге, мы не только отбиваем затраты на закупку товаров, но и зарабатываем доходность. Наше
                        Агентское вознаграждение, это вся доходность от проведенной торговой операции сверх Вашей ожидаемой
                        доходности.
                    </div>
                </div>
                <div class="card">
                    <div class="card__title">
                        Повторяем цикл «Закупка - Продажа»
                    </div>
                    <div class="card__body">
                        Дальше мы снова закупаем партию товара и пытаемся реализовать ее с максимальной маржинальностью. За
                        год таких циклов может быть разное количество, но в среднем мы успеваем продать 8-12 партий товаров.
                    </div>
                </div>
                <div class="card">
                    <div class="card__title">
                        Выплачиваем Вам доходность
                    </div>
                    <div class="card__body">
                        Все расходы нашей деятельности мы берем на себя и компенсируем их из той маржи, которая останется
                        после выплаты Вам ожидаемой доходности и возврата Вам первоначальных средств (того самого 1 млн.р.)
                        который вы перевели нам по договору изначально.
                    </div>
                </div>
            </div>
        </div>
        <div class="stripe__primary stripe__primary_horizontal"></div>
    </div>

    <div class="watch" id="watch">
        <div class="container-xxl">
            <h2 class="title watch__title"><span class="text-primary">Вы можете</span> следить за всем</h2>
            <p class="text watch__text">Как Вы можете контролировать результаты нашего с Вами сотрудничества:</p>
            <div class="watch__cards">
                <div class="card card_text">Как только Договор и выбранное Вами Приложение вступило в законную силу, в
                    Вашем личном кабинете сразу же появится график выплат Вашей ожидаемой доходности и возврата 100% объема
                    предоставленных нам ваших средств.</div>
                <div class="card card_text">
                    Со временем, те выплаты вашей доходности, которые мы произведем, будут отмечаться жёлтой галочкой
                    (исполнено!).
                </div>
                <div class="card card_text">
                    Те выплаты Вашей доходности, которые еще только предстоит исполнить, будут не будут ничем отмечены.
                </div>
                <div class="card card_text">
                    Таким образом, у Вас будет возможность четко контролировать исполнение обязательств нашей стороны (что
                    называется, в онлайн режиме).
                </div>
            </div>
        </div>
    </div>

    <div class="faq" id="faq">
        <div class="container-xxl">
            <div class="title faq__title"><span class="text-primary">ответы</span> на частые вопросы</div>
            <div class="faq__questions">
                <div class="question">
                    <div class="question__title">
                        Как мы выбираем наиболее актуальные товары для последующей продажи?
                    </div>
                    <div class="question__answer">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Numquam ex qui placeat nobis! Officiis
                        neque nisi unde ratione obcaecati possimus totam atque dolore tenetur aut, nihil cumque aperiam,
                        officia nulla.
                    </div>
                </div>
                <div class="question">
                    <div class="question__title">
                        Как мы решаем вопросы поставки товаров на наш склад в Москве?
                    </div>
                    <div class="question__answer">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Numquam ex qui placeat nobis! Officiis
                        neque nisi unde ratione obcaecati possimus totam atque dolore tenetur aut, nihil cumque aperiam,
                        officia nulla.
                    </div>
                </div>
                <div class="question">
                    <div class="question__title">
                        Как мы отбираем поставщиков?
                    </div>
                    <div class="question__answer">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Numquam ex qui placeat nobis! Officiis
                        neque nisi unde ratione obcaecati possimus totam atque dolore tenetur aut, nihil cumque aperiam,
                        officia nulla.
                    </div>
                </div>
                <div class="question">
                    <div class="question__title">
                        Какой у нас опыт работы в данной области?
                    </div>
                    <div class="question__answer">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Numquam ex qui placeat nobis! Officiis
                        neque nisi unde ratione obcaecati possimus totam atque dolore tenetur aut, nihil cumque aperiam,
                        officia nulla.
                    </div>
                </div>
                <div class="question">
                    <div class="question__title">
                        Как мы определяем ценообразование товаров, которые мы продаем в розницу?
                    </div>
                    <div class="question__answer">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Numquam ex qui placeat nobis! Officiis
                        neque nisi unde ratione obcaecati possimus totam atque dolore tenetur aut, nihil cumque aperiam,
                        officia nulla.
                    </div>
                </div>
                <div class="question">
                    <div class="question__title">
                        На каких условиях мы продаем наши товары через Ozon, WB и Яндекс-Маркет?
                    </div>
                    <div class="question__answer">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Numquam ex qui placeat nobis! Officiis
                        neque nisi unde ratione obcaecati possimus totam atque dolore tenetur aut, nihil cumque aperiam,
                        officia nulla.
                    </div>
                </div>
                <div class="question">
                    <div class="question__title">
                        Как мы добиваемся быстрых продаж через Ozon, WB и Яндекс-Маркет?
                    </div>
                    <div class="question__answer">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Numquam ex qui placeat nobis! Officiis
                        neque nisi unde ratione obcaecati possimus totam atque dolore tenetur aut, nihil cumque aperiam,
                        officia nulla.
                    </div>
                </div>
                <div class="question">
                    <div class="question__title">
                        Какие риски мы видим в нашей работе и как мы эти риски нивелируем?
                    </div>
                    <div class="question__answer">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Numquam ex qui placeat nobis! Officiis
                        neque nisi unde ratione obcaecati possimus totam atque dolore tenetur aut, nihil cumque aperiam,
                        officia nulla.
                    </div>
                </div>
            </div>
        </div>
        <div class="stripe__primary stripe__primary_horizontal"></div>
    </div>

    <div class="whatyouget" id="whatyouget">
        <div class="stripe__primary"></div>
        <div class="whatyouget__content">
            <div class="container-xxl">
                <div class="whatyouget__right">
                    <div class="title whatyouget__title">Что <span class="text-primary">вы получаете</span> в итоге </div>
                    <p class="text whatyouget__text">Мы предлагаем простую и понятную модель совместного заработка для тех,
                        кто хотел бы, чтобы его сбережения работали и приносили дополнительный доход.</p>
                    <p class="text whatyouget__text">Если вы НЕ знаете, как заставить свои деньги работать, или у вас
                        просто нет времени, чтобы следить за доходностью вкладов, то наше предложение – именно для вас.</p>
                    <a class="btn btn-primary" href="">Хочу получить доход!</a>
                </div>
                <div class="whatyouget__left">
                    <div class="whatyouget__cards">
                        <div class="whatyouget__card">
                            <div class="card__image"></div>
                            <div class="card__text">
                                <p class="">
                                    Вам не придется разбираться в сложностях бизнеса, следить за закупками и продажами,
                                    решать возникающие проблемы.
                                </p>
                            </div>
                        </div>
                        <div class="whatyouget__card">
                            <div class="card__image"></div>
                            <div class="card__text">
                                <p class="">
                                    Ваши доходы не будут зависеть от рыночной ситуации.
                                    Все вопросы мы берем на себя и обязуемся стабильно выплачивать вашу доходность в
                                    соответствии с выбранным вами тарифом договора.
                                </p>
                            </div>
                        </div>
                        <div class="whatyouget__card">
                            <div class="card__image"></div>
                            <div class="card__text">
                                <p class="">
                                    Залог успеха нашего сотрудничества – в совместной работе ваших возможностей и нашего
                                    опыта.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container-xxl">
            <div class="footer__content">
                <div class="footer__logo">
                    <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="Logo">
                </div>
                <div class="footer__socials">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_154_2487)">
                            <path
                                d="M18.3764 1.77531L0.657315 8.64352C-0.0557314 8.96336 -0.296903 9.60387 0.484972 9.95149L5.03067 11.4036L16.0216 4.57582C16.6217 4.14719 17.2361 4.26148 16.7074 4.73301L7.26771 13.3242L6.97118 16.96C7.24583 17.5214 7.74872 17.524 8.0695 17.245L10.6811 14.761L15.154 18.1277C16.1929 18.7459 16.7581 18.347 16.9817 17.2139L19.9154 3.2502C20.22 1.85547 19.7006 1.24094 18.3764 1.77531Z"
                                fill="#737373" />
                        </g>
                        <defs>
                            <clipPath id="clip0_154_2487">
                                <rect width="20" height="20" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M18.9336 5.15625C19.0727 4.71563 18.9336 4.39531 18.3184 4.39531H16.2727C15.7566 4.39531 15.5184 4.67578 15.3793 4.97578C15.3793 4.97578 14.327 7.53828 12.8574 9.19961C12.3809 9.68008 12.1625 9.84023 11.9043 9.84023C11.7652 9.84023 11.5863 9.68008 11.5863 9.23984V5.13555C11.5863 4.61523 11.4277 4.375 10.9906 4.375H7.77344C7.45547 4.375 7.25703 4.61523 7.25703 4.85547C7.25703 5.35586 7.9918 5.47617 8.07109 6.87734V9.92188C8.07109 10.5824 7.95391 10.7031 7.69375 10.7031C6.99883 10.7031 5.31094 8.1207 4.29844 5.17812C4.10156 4.59531 3.90156 4.375 3.38516 4.375H1.31992C0.724219 4.375 0.625 4.65508 0.625 4.95547C0.625 5.49609 1.31992 8.21836 3.86172 11.8215C5.54961 14.2824 7.95234 15.6051 10.1168 15.6051C11.4277 15.6051 11.5863 15.3047 11.5863 14.8043V12.9426C11.5863 12.3422 11.7055 12.2422 12.1227 12.2422C12.4203 12.2422 12.9566 12.402 14.168 13.5832C15.5578 14.9844 15.7961 15.625 16.5707 15.625H18.616C19.2117 15.625 19.4898 15.3246 19.3309 14.7441C19.1523 14.1637 18.477 13.323 17.6031 12.3223C17.1266 11.7621 16.4117 11.1414 16.1934 10.841C15.8957 10.4406 15.975 10.2805 16.1934 9.92031C16.1734 9.92031 18.6758 6.35703 18.9336 5.15469" fill="#737373"/>
					</svg>
                </div>
                <div class="footer__nav">
					<a class="footer__link" href="#">Соглашения платформы</a>
					<a class="footer__link" href="#">Политика конфиденциальности</a>
				</div>
                <div class="footer__copyright">
					© 2023 ООО "ProfitUP". Все права защищены.
				</div>
            </div>
        </div>
    </footer>
@endsection
