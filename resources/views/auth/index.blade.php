<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>@yield('page-title')</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
	<link rel="stylesheet" href="{{ asset('css/icons.css') }}">
	<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/img/apple-touch-icon.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/img/favicon-16x16.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/img/favicon-16x16.png') }}">
	<link rel="manifest" href="{{ asset('/img/site.webmanifest') }}">
	<link rel="mask-icon" href="{{ asset('/img/safari-pinned-tab.svg') }}" color="#5bbad5">
	<link rel="shortcut icon" href="{{ asset('/img/favicon.ico') }}">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="msapplication-config" content="/img/browserconfig.xml">
	<meta name="theme-color" content="#ffffff">
	<script src="{{ asset('jquery-3-6-3.min.js') }}"></script>
</head>
<body id="auth-page">
	@csrf
	<div id="wrapper">
		<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
		<link rel="stylesheet" href="{{ asset('css/main.css') }}">
		<div class="login-all-page">
			<div class="js-login login">
				@yield('page-content')
			</div>
		</div>
	</div>
	@include('_elements.loader')
	<script>
		$(document).ready(function () {
			const
				$authPage = $("#auth-page"),
				$authValidationDescription = $authPage.find("#auth-validation-description")
			;
			$("input.invalid-input").on("keyup", function () {
				const $authInputs = $authPage.find("input[type=text], input[type=email], input[type=password]");
				$authInputs.each(function () {
					$(this).removeClass("invalid-input");
				});
				$authValidationDescription.addClass("invisible");
			});
		});

		function inputValidationHandler($textField, $button = undefined) {
			$textField.addClass("invalid-input");
			$textField.on("keyup", function () {
				if ($button !== undefined) {
					$button.prop("disabled", false);
				}
				$textField.removeClass("invalid-input");
				$textField.unbind("keyup");
			});
		}
	</script>
	@stack('page-scripts')
</body>
</html>