<div class="confirm-two-factor">
	<div class="confirm-two-factor-side-by-side">
		<div>
			<div>QR code</div>
			<div>
				{!! $qrCodeTwoFactor !!}
			</div>
		</div>
		<div class="two-factor-recovery-codes">
			<div class="">Recovery codes</div>
			<div class="">
				@foreach( $recoveryCodes as $key=>$recoveryCode )
					<div>{{ $recoveryCode }}</div>
				@endforeach
			</div>
		</div>
	</div>
	<div>
		Using your preferred authenticator app scan the QR-code and enter the MFA code below to activate
	</div>
	<form>
		<label><input type="text" name="code" id="js-qr-code-accept" placeholder="MFA code" class="default"></label>
	</form>
	<div>
		<button type="submit" class="js-confirm-button-two-factor default default-button">Confirm</button>
	</div>
</div>

<script type="application/javascript">
	$(document).ready(function () {

		const
			$codeInput = $("#js-qr-code-accept"),
			$confirmButton = $(".js-confirm-button-two-factor"),
			$closeButton = $(".js-popup-close-confirm");

		$codeInput.on("keyup", function () {
			$confirmButton.prop("disabled", false);
		});
		$confirmButton.on("click", function () {
			let request_data = {
				code: $codeInput.val()
			};
			request.send(
				"{{ route('action.user-profile.toggle-two-factor-confirm') }}",
				request_data,
				(response) => {
					console.log(response);
					window.location.replace('{{ route('home') }}')
					popup.hide();
				},
				(response) => {
					alert(response.validator.code);
					$confirmButton.prop("disabled", true);
					inputValidationHandler($codeInput, $confirmButton);
				}
			);
		});
		$closeButton.unbind("click").on("click", function (event) {
			event.preventDefault();
			$(".js-content-popup").empty();
		});
	});
</script>