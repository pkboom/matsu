<!DOCTYPE html>
<html lang="en">

<head>
  	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-123248786-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-123248786-1');
	</script>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Matsu Sushi</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
	<link rel="shortcut icon" type="image/x-icon" href="/images/sushi.ico" />
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default" defer></script>

    <script>
        window.App = {!! json_encode([
			'csrfToken' => csrf_token(),
		]) !!};
    </script>
    
	@yield('header')

</head>

<body class="font-sans bg-white leading-none text-gray-800 antialiased">
    <div id="app">
        @yield('content')
        <flash message="{{ session('flash') }}"></flash>
    </div>
    <script src="{{ mix('js/app.js') }}" defer></script>
</body>

</html>