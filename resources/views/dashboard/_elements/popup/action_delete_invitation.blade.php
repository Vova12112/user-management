<noscript id="delete-invitation-popup-noscript-container" style="display:none;" data-title="Delete invitation ">
	<div class="js-content-popup delete-invitation-popup action-popup">
		<span class="js-popup-title popup-title">
			Do you want to delete the invitation for
			<span class="js-user-name-block"></span> ?
		</span>
		<div class="popup-buttons-wrapper">
			<button class="js-cancel-button popup-button cancel inline-element">Cancel</button>
			<button class="js-delete-invitation-button popup-button delete inline-element">Delete</button>
		</div>
	</div>
	<script>
		$(document).ready(function () {
			const
				$popup = popup.getPopup(),
				$sendButton = $popup.find(".js-delete-invitation-button"),
				$cancelButton = $popup.find(".js-cancel-button"),
				$userNameBlock = $(".js-user-name-block"),
				inviteeName = $popup.data("name")
			;

			$sendButton.focus();
			$userNameBlock.text(inviteeName);

			function send() {
				request.send(
					"{{ route('action.user-management.delete-invitation') }}",
					{token: $popup.data("token")},
					(response) => {
						alert(response.msg);
						content.showAjax("{{ route('content.user-management') }}");
						popup.hide();
					}
				);
			}

			$(document).unbind("keyup").on("keyup", function (event) {
				const currentKeyCode = parseInt(event.keyCode);
				if (currentKeyCode === 13 && $sendButton.is(":focus")) {
					event.preventDefault();
					send();
				} else if (currentKeyCode === 27) {
					event.preventDefault();
					popup.hide();
				}
			});

			$sendButton.on("click", function (event) {
				event.stopImmediatePropagation();
				event.preventDefault();
				send();
			});

			$cancelButton.on("click", function (event) {
				event.preventDefault();
				popup.hide();
			});
		});
	</script>
</noscript>
