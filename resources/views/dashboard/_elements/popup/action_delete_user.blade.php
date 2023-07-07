<noscript id="delete-user-popup-noscript-container" style="display:none;" data-title="Delete user ">
	<div class="js-content-popup delete-user-popup action-popup">
		<span class="js-popup-title popup-title">
			Do you want to delete
			<span class="js-user-name-block"></span>'s account?
		</span>
		<div class="popup-buttons-wrapper">
			<button class="js-cancel-button popup-button cancel inline-element" style="cursor: default">Cancel</button>
			<button class="js-delete-user-button popup-button delete inline-element">Delete</button>
		</div>
	</div>
	<script>
		$(document).ready(function () {
			const
				$popup = popup.getPopup(),
				$sendButton = $popup.find(".js-delete-user-button"),
				$cancelButton = $popup.find(".js-cancel-button"),
				$userNameBlock = $(".js-user-name-block")
			;

			$sendButton.focus();
			$userNameBlock.text($popup.data("data"));

			function send() {
				request.send(
					"{{ route('action.user-management.delete-user') }}",
					{id: $popup.data("id")},
					() => {
						content.showAjax("{{ route('content.users') }}");
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
