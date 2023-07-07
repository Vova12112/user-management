@php
	use App\Models\User;
	/*** @var User $authUser */
@endphp
<div class="profile-top">
	<div class="profile-name">
		Welcome,
		<span class="text-primary">{{ $authUser->getName() }}</span>!
	</div>
	<a class='logout-container' href="{{route("logout")}}">
		<span>Logout</span>
		<i class="js-logout-button icon-28 logout with-hover"></i>
	</a>
</div>
<div class="profile-user-data">
	<label>
		Email:
		<input class="default" readonly placeholder="Email" value="{{ $authUser->getEmail() }}">
	</label>
</div>
<div class="profile-middle">
	<div class="change-password-div">
		Change your password:
		<form id="js-change-password" method="POST" action="">
			<div class="change-password-fields">
				<label>
					Old password:
					<input type="password" id="js-current-password" name="current_password" class="default" placeholder="Old password">
				</label>
				<label>
					New password:
					<input type="password" id="js-new-password" name="new_password" class="default" placeholder="New password">
				</label>
				<label>
					Repeat new password:
					<input type="password" id="js-confirm-password" name="confirm_password" class="default" placeholder="Repeat new password">
				</label>
			</div>
		</form>
		<input type="button" class="js-change-password-button default-button default" value="Change password">
	</div>
{{--	@if( $authUser->isConfirmedTwoFactor())--}}
{{--		<div class="mfa-on-container">--}}
{{--			<div class="mfa">--}}
{{--				<h3>2FA Enabled</h3>--}}
{{--				<div class="confirm-two-factor-side-by-side">--}}
{{--					<input type="button" class="js-disable-mfa-button default-button default" value="Disable MFA">--}}
{{--					<input type="button" class="js-codes-button default-button default" value="Show recovery codes">--}}
{{--				</div>--}}
{{--			</div>--}}
{{--		</div>--}}
{{--	@else--}}
{{--		<div class="mfa">--}}
{{--			MFA is disabled:--}}
{{--			<input type="button" class="js-mfa-button default-button default" value="Enable MFA">--}}
{{--		</div>--}}
{{--	@endif--}}
{{--	@if( $authUser->isRoot())--}}
		<div class="mfa-on-container">
			<div class="mfa">
				<h3>Users Invites </h3>
				<div class="confirm-two-factor-side-by-side">
					<input id="invite-user-button" type="button" class="default-button default" value="Invite new user">
				</div>
			</div>
{{--		</div>--}}
		<noscript id="invite-new-user-noscript-popup">
			<div class="input-line right">
				<label>
					User name:
					<input id="invite-new-user-name" type="text" name="name" class="default" placeholder="Name">
				</label>
			</div>
			<div class="input-line right">
				<label>
					Email:
					<input id="invite-new-user-email" type="text" name="email" class="default" placeholder="Email">
				</label>
			</div>
			<div class="input-line right">
				<label>
					Confirm password:
					<input id="invite-confirm-password" type="password" name="password" class="default" placeholder="Your password">
				</label>
			</div>
			<input id="confirm-invite-user-button" type="button" class="default-button default" value="Invite">
			<script type="text/javascript">
				$(document).ready(function () {
					const
						$userNameInput = $("#invite-new-user-name"),
						$userEmailInput = $("#invite-new-user-email"),
						$confirmPasswordInput = $("#invite-confirm-password"),
						$inviteBtn = $("#confirm-invite-user-button")
					;
					$([$userNameInput, $userEmailInput, $confirmPasswordInput]).each(function () {
						$(this).on("keyup", function () {
							if ($userNameInput.val().length > 0 && $userEmailInput.val().length > 0 && $confirmPasswordInput.val().length > 0) {
								$inviteBtn.removeAttr("disabled");
							} else {
								$inviteBtn.prop("disabled", "1");
							}
						});
					});
					$inviteBtn.on("click", function () {
						request.send(
							"{{ route('action.user-profile.invite-user') }}",
							{
								"name":     $userNameInput.val(),
								"email":    $userEmailInput.val(),
								"password": $confirmPasswordInput.val()
							},
							(response) => {
								popup.showNoscript("Invite New User", $("#confirm-invite-new-user-noscript-popup"), "invite-user-popup");
								popup.getPopup().find("#invite-new-user-link").val(response.link);
								content.showAjax("{{ route('content.profile') }}");
							},
							(response) => {
								$inviteBtn.prop("disabled", true);
								if (response.validator !== undefined) {
									if (response.validator["name"] !== undefined) {
										inputValidationHandler($userNameInput, $inviteBtn);
									}
									if (response.validator["email"] !== undefined) {
										inputValidationHandler($userEmailInput, $inviteBtn);
									}
									if (response.validator["password"] !== undefined) {
										inputValidationHandler($confirmPasswordInput, $inviteBtn);
									}
								}
							}
						);
					});
				});
			</script>
		</noscript>
		<noscript id="confirm-invite-new-user-noscript-popup">
			<div class="input-line">
				The invitation link was successfully created. Send it to the user within 2 hours.
			</div>
			<div class="input-line">
				<label>
					Invite link:
					<input id="invite-new-user-link" type="text" class="default" onClick="this.select();">
				</label>
			</div>
		</noscript>
		<script type="text/javascript">
			$(document).ready(function () {
				const $inviteBtn = $("#invite-user-button");
				$inviteBtn.on("click", function () {
					popup.showNoscript("Invite New User", $("#invite-new-user-noscript-popup"), "invite-user-popup");
				});
			});
		</script>
{{--	@endif--}}
</div>
<div class="profile-bottom"></div>
<script type="text/javascript">
	$(document).ready(function () {

		let
			$logoutButton = $(".js-logout-button"),
			$changePasswordButton = $(".js-change-password-button"),
			$currentPassword = $("#js-current-password"),
			$newPassword = $("#js-new-password"),
			$confirmPassword = $("#js-confirm-password")
		;

		$changePasswordButton.prop("disabled", true);


		function isPasswordValid() {
			let
				password = $newPassword.val(),
				confirmPassword = $confirmPassword.val();
			return password !== "" && confirmPassword !== "" && password === confirmPassword && password.length >= 8;
		}

		$("#js-new-password, #js-confirm-password, #js-current-password").unbind("keyup").on("keyup DOMAutoComplete", function (event) {
			event.preventDefault();
			if (isPasswordValid()) {
				$changePasswordButton.prop("disabled", false);
			}
		});
		$logoutButton.on("click", function () {
			loader.show();
		});

		$changePasswordButton.on("click", function (e) {
			e.preventDefault();
			if (isPasswordValid()) {
				request.send(
					"{{ route('action.user-profile.change-password') }}",
					{
						current_password: $currentPassword.val(),
						new_password:     $newPassword.val(),
						confirm_password: $confirmPassword.val()
					},
					() => {
						alert("Password changed successfully!");
						content.showAjax("{{ route('content.profile') }}");
					},
					(response) => {
						$changePasswordButton.prop("disabled", true);
						if (response.validator["current_password"] !== undefined) {
							inputValidationHandler($currentPassword, $changePasswordButton);
						}
						if (response.validator["new_password"] !== undefined) {
							inputValidationHandler($newPassword, $changePasswordButton);
						}
						if (response.validator["confirm_password"] !== undefined) {
							inputValidationHandler($confirmPassword, $changePasswordButton);
						}
						Object.keys(response.validator).forEach(key => {
							alert(response.validator[key]);
						});
					}
				);
			}
		});
	});

</script>
</div>