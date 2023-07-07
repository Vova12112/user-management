@extends('auth.index')
@section('page-title')
	Login
@endsection
@section('page-content')
	<div class="auth-title">Log in</div>
	<form method="POST" action="{{ url('/login') }}">
		@csrf
		<div class="login-form">
			<label>
				<input id="login-field" class="default" name="email" type="email" placeholder="Email" value="{{ $_GET['login'] ?? '' }}"/>
			</label>
			<label>
				<input id="password-field" class="default" name="password" type="password" placeholder="Password">
			</label>
			<span id="auth-validation-description" class="invisible">Invalid login or password!</span>
			<button class="auth-btn" type="submit">Login</button>
		</div>
	</form>
@endsection

@push('page-scripts')
	<script type="text/javascript">
		const
			$invalidInputText = $("#auth-validation-description"),
			$loginField = $("#login-field"),
			$passwordField = $("#password-field")
		;
		if (performance.getEntriesByType("navigation")[0].redirectCount > 0 && document.referrer === '{{ Request::url() }}') {
			$invalidInputText.removeClass("invisible");
			inputValidationHandler($loginField);
			inputValidationHandler($passwordField);
		}
		@if ( empty($_GET['login']))
		$loginField.focus();
		@else
		$passwordField.focus();
		@endif
	</script>
@endpush