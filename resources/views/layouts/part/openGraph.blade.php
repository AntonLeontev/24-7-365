<meta property="og:title" content="@yield('title') | {{ config('app.name', 'Laravel') }}"/>
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ request()->url() }}" />
<meta property="og:image" content="{{ env('APP_URL') }}/ogimage.jpg"/>
<meta property="og:locale" content="ru_RU" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:image" content="{{ env('APP_URL') }}/ogimage.jpg" />
