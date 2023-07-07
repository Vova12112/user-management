<div class="mfa">
	<label>
		Enter the current password:
		<input id="mfa-current-password" class="default" type="password" placeholder="Enter password">
	</label>

	<input type="button" class="js-mfa-button default-button default" value="Confirm and disable MFA">
</div>
<script type="text/javascript">
	$(document).ready(function () {

		const
			$popup = popup.getPopup(),
			$mfaPasswordInput = $popup.find("#mfa-current-password"),
			$mfaButton = $popup.find(".js-mfa-button")
		;

		$mfaButton.on("click", function () {
			request.send(
				"{{ route('action.user-profile.disable-two-factor') }}",
				{ current_password: $mfaPasswordInput.val() },
				() => {
					popup.hide();
					content.showAjax("{{ route('content.profile') }}");
				},
				() => {
					$mfaButton.prop("disabled", true);
					inputValidationHandler($mfaPasswordInput, $mfaButton);
				}
			);
		});
	});
</script>