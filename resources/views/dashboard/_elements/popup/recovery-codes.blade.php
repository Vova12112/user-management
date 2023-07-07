<div class="mfa-codes">
	<h2>Recovery codes</h2>
	<div class="recovery-codes-columns">
	@foreach( $recoveryCodes as $key => $recoveryCode )
		<div class="recovery-code-item">{{ $recoveryCode }}</div>
	@endforeach
	</div>
	<input class="js-regenerate-codes-button default-button default" type="button" value="Regenerate codes">
</div>
<script>
	$(document).ready(function () {
		const
			$popup = popup.getPopup(),
			$regenerateCodesButton = $popup.find(".js-regenerate-codes-button");

		$regenerateCodesButton.on("click", function () {
			request.send(
				"{{ route('action.user-profile.refresh-two-factor-codes') }}",
				{},
				() => {
					popup.showAjax('{{ route('popup.show-two-factor-codes') }}');
				},
				(response) => {
					alert(response.exception);
				}
			);
		});
	});
</script>