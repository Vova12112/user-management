<div class="mfa">
	Enable MFA:
	<label>
		Enter the current password:
		<input id="mfa-current-password" class="default" type="password" placeholder="Enter password">
	</label>
	<input type="button" class="js-mfa-button default-button default" value="Enable MFA">
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
				"{{ route('action.user-profile.enable-two-factor') }}",
				{current_password: $mfaPasswordInput.val()},
				(response) => {
					popup.show("Confirm MFA", response.content, "mfa-confirm-popup");
				},
				() => {
					$mfaButton.prop("disabled", true);
					inputValidationHandler($mfaPasswordInput, $mfaButton);
				}
			);
		});
	});
</script>