<noscript id="copy-invitation-link-popup-noscript-container" style="display:none;" data-title="Invitation link">
	<div class="js-content-popup delete-invitation-popup action-popup">
		<span class="js-popup-title popup-title">
			Invitation link for
			<span class="js-user-name-block"></span> :<br>
		</span>
		<label>
			<input type="text" id="invitation-link" class="default">
		</label>
		<div class="popup-buttons-wrapper">
			<button class="js-cancel-button popup-button cancel inline-element">Close</button>
			<button class="js-copy-button popup-button copy inline-element">Copy</button>
		</div>
	</div>
	<script>
		$(document).ready(function () {
			const
				$popup = popup.getPopup(),
				$cancelButton = $popup.find(".js-cancel-button"),
				$copyButton = $popup.find(".js-copy-button"),
				$userNameBlock = $(".js-user-name-block"),
				$invitationLink = $("#invitation-link"),
				inviteeName = $popup.data("name"),
				invitationToken = $popup.data("token")
			;

			$userNameBlock.text(inviteeName);
			$invitationLink.val("{{ route( 'invite', '' ) }}" + "/" + invitationToken);
			$invitationLink.select();

			$copyButton.on("click", function () {
				$invitationLink.select();
				if ( ! navigator.clipboard) {
					document.execCommand("copy");
				} else {
					navigator.clipboard.writeText($invitationLink.val());
				}
			});

			$(document).unbind("keyup").on("keyup", function (event) {
				const currentKeyCode = parseInt(event.keyCode);
				if (currentKeyCode === 27) {
					event.preventDefault();
					popup.hide();
				}
			});

			$cancelButton.on("click", function (event) {
				event.preventDefault();
				popup.hide();
			});
		});
	</script>
</noscript>
