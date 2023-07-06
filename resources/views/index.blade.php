<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>@yield('page-title')</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
	<link rel="stylesheet" href="{{ asset('css/icons.css') }}">
	<link rel="stylesheet" href="{{ asset('css/main.css') }}">
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/img/apple-touch-icon.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/img/favicon-16x16.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/img/favicon-16x16.png') }}">
	<link rel="manifest" href="{{ asset('/img/site.webmanifest') }}">
	<link rel="mask-icon" href="{{ asset('/img/safari-pinned-tab.svg') }}" color="#5bbad5">
	<link rel="shortcut icon" href="{{ asset('/img/favicon.ico') }}">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="msapplication-config" content="/img/browserconfig.xml">
	<meta name="theme-color" content="#ffffff">
	@stack('page-style-links')
	<script src="{{ asset('jquery-3-6-3.min.js') }}"></script>
</head>
<body>
	@csrf
	@include('_elements.popup')
	@include('_elements.loader')
	@include('_scripts._common')
	<div id="wrapper">
		@yield('page-content')
	</div>
</body>
</html>