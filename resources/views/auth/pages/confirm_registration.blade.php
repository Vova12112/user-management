@php
	/*** @var UserInvite $invite */
	use App\Models\UserInvite;
@endphp

@extends('auth.index')
@section('page-title')
	Registration
@endsection
@section('page-content')
	<div class="auth-title">Registration</div>
	<form id="confirm-registration-form" method="POST" action="{{ route('invite.complete', ['token' => $invite->getToken()]) }}">
		@csrf
		<div class="login-form">
			<label>
				<input id="name-field" class="default" name="name" type="text" placeholder="Name" value="{{ old('name') ?? $invite->getName() }}" data-old="{{ $invite->getName() }}" required/>
			</label>
			<label>
				<input id="email-field" class="default" name="email" type="email" placeholder="Email" value="{{ $invite->getEmail() }}" disabled/>
			</label>
			The password must be at least 10 characters and contain at least one uppercase character, one number, and one special character (!@#$%^&*)
			<label>
				<input id="new-password-field" class="default" name="password" type="password" placeholder="New Password" value="{{ old('password') ?? '' }}" required>
			</label>
			<label>
				<input id="confirm-password-field" class="default" name="confirm_password" type="password" placeholder="Confirm Password" value="{{ old('confirm_password') ?? '' }}" required>
			</label>
			<span id="auth-validation-description" class="invisible">{{ session('error') ?? 'Email is not unique or password is so easy!' }}</span>
			<button class="js-confirm-btn auth-btn" type="submit" disabled>Login</button>
		</div>
	</form>
@endsection

@push('page-scripts')
	<script type="text/javascript">
		$(document).ready(function () {
			const
				$invalidInputText = $("#auth-validation-description"),
				$confirmRegistrationForm = $("#confirm-registration-form"),
				$nameField = $confirmRegistrationForm.find("input#name-field"),
				$emailField = $confirmRegistrationForm.find("input#email-field"),
				$newPasswordField = $confirmRegistrationForm.find("input#new-password-field"),
				$confirmPasswordField = $confirmRegistrationForm.find("input#confirm-password-field"),
				$formFields = $([$nameField, $emailField, $newPasswordField, $confirmPasswordField]),
				$formBtn = $confirmRegistrationForm.find("button.js-confirm-btn")
			;

			function isFormValid() {
				let isValid = $newPasswordField.val() === $confirmPasswordField.val();
				$([$nameField, $emailField, $newPasswordField, $confirmPasswordField]).each(function () {
					isValid = isValid && $(this).val().length > 2;
				});
				return isValid;
			}

			if (performance.getEntriesByType("navigation")[0].redirectCount > 0 && document.referrer === '{{ Request::url() }}') {
				$invalidInputText.removeClass("invisible");
				inputValidationHandler($newPasswordField);
				inputValidationHandler($confirmPasswordField);
			}

			$([$nameField, $emailField, $newPasswordField, $confirmPasswordField]).each(function () {
				$(this).on("keyup", function () {
					if (isFormValid()) {
						$formBtn.removeAttr("disabled");
					} else {
						$formBtn.prop("disabled", "1");
					}
				});
			});
		});
	</script>
@endpush