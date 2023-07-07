@php
	use WebsocketClient\ValuesObject\PayloadKeys;
	use WebsocketClient\ValuesObject\WebsocketChannel;
@endphp

<noscript id="send-notification-popup-noscript-container" style="display:none;" data-title="Send a notification">
	<div class="js-content-popup send-notification-popup action-popup">
		<span class="js-popup-title popup-title">
			Enter the message and choose the type of notification to be sent to connection:<br>
			<span class="js-wpi-token-block"></span>
		</span>
		<label class="js-channel-select-label hidden channel-select-label">
			Enter the message and choose the type of notification to be sent publicly:<br>
			<select name="channel" class="js-channel-select public-channel-select default">
				@foreach( $websocketPublicChannels as $channelKey => $channel)
					<option value="{{ $channelKey }}" {{ $channelKey === WebsocketChannel::PUBLIC_GLOBAL ? 'selected' : '' }}>{{ $channel }}</option>
				@endforeach
			</select>
		</label>
		<label class="js-notification-type-select-label notification-type-select-label">
			<select name="notification_type" class="js-notification-type-select notification-type-select public-channel-select default">
				@foreach( PayloadKeys::getClientNotificationTypes() as $type)
					<option value="{{ $type }}" {{ $type === PayloadKeys::CLIENT_NOTIFICATION_SUCCESS ? 'selected' : '' }}>{{ ucwords($type) }}</option>
				@endforeach
			</select>
		</label>
		<label>
			<input type="text" class="js-notification-message-input default" placeholder="Enter a message" name="notification-message">
		</label>
		<button class="js-send-notification-button popup-button send">Send notification</button>
	</div>
	<script>
		$(document).ready(function () {
			const
				$popup = popup.getPopup(),
				$sendButton = $popup.find(".js-send-notification-button"),
				$channelSelectLabel = $popup.find(".js-channel-select-label"),
				$channelSelect = $channelSelectLabel.find(".js-channel-select"),
				$typeSelectLabel = $popup.find(".js-notification-type-select-label"),
				$typeSelect = $typeSelectLabel.find(".js-notification-type-select"),
				$title = $popup.find(".js-popup-title"),
				$wpiTokenBlock = $title.find(".js-wpi-token-block"),
				$notificationMessageInput = $popup.find(".js-notification-message-input"),
				wpiToken = $popup.data("wpi-token"),
				isPublic = wpiToken === undefined
			;

			$notificationMessageInput.focus();

			function send() {
				$sendButton.prop("disabled", true);
				let request_data = {
					wpi_token: isPublic ? undefined : wpiToken,
					type:      $typeSelect.val(),
					channel:   isPublic ? $channelSelect.val() : undefined,
					message:   $notificationMessageInput.val()
				};
				request.send(
					"{{ route('action.send-notification') }}",
					request_data,
					() => {
						popup.hide();
					},
					function (response) {
						if (response.validator.message !== undefined) {
							alert(response.validator.message);
							inputValidationHandler($notificationMessageInput, $sendButton);
						} else {
							alert("Ajax failed! Exception:" + response.exception);
						}
					}
				);
			}

			$(document).unbind("keyup").on("keyup", function (event) {
				const currentKeyCode = parseInt(event.keyCode);
				if (currentKeyCode === 13 && $notificationMessageInput.is(":focus")) {
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
