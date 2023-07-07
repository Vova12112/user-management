<noscript id="debug-popup-noscript-container" class="hidden" data-title="Change name">
	<div class="js-content-popup debug-popup action-popup">
		<span class="js-popup-title popup-title">
			Enter new name:<br>
			<span class="js-wpi-token-block"></span>
		</span>
		<label>
			<input type="text" class="js-debug-message-input default" placeholder="Enter Name" name="new-name">
		</label>
		<button class="js-send-message-button popup-button send">Change</button>
	</div>
	<script>
		$(document).ready(function () {
			const
				$popup = popup.getPopup(),
				$sendButton = $popup.find(".js-send-message-button"),
				$channelSelectLabel = $popup.find(".js-channel-select-label"),
				$channelSelect = $channelSelectLabel.find(".js-channel-select"),
				$title = $popup.find(".js-popup-title"),
				$wpiTokenBlock = $title.find(".js-wpi-token-block"),
				$debugMessageInput = $popup.find(".js-debug-message-input"),
				wpiToken = $popup.data("wpi-token"),
				isPublic = wpiToken === undefined
			;

			$debugMessageInput.focus();

			function send() {
				$sendButton.prop("disabled", true);
				let request_data = {
					wpi_token: isPublic ? undefined : wpiToken,
					channel:   isPublic ? $channelSelect.val() : undefined,
					message:   $debugMessageInput.val()
				};
				request.send(
					{{--"{{ route('action.send-debug') }}",--}}
					request_data,
					() => {
						popup.hide();
					},
					(response) => {
						if (response.validator.message !== undefined) {
							alert(response.validator.message);
							inputValidationHandler($debugMessageInput, $sendButton);
						} else {
							alert("Ajax failed! Exception:" + response.exception);
						}
					}
				);
			}

			$(document).unbind("keyup").on("keyup", function (event) {
				const currentKeyCode = parseInt(event.keyCode);
				if (currentKeyCode === 13 && $debugMessageInput.is(":focus")) {
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

			if (isPublic) {
				$channelSelectLabel.removeClass("hidden");
				$title.addClass("hidden");
			} else {
				$wpiTokenBlock.text(wpiToken);
			}
		});
	</script>
</noscript>
