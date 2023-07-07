@php
	use WebsocketClient\ValuesObject\WebsocketChannel;
@endphp
<noscript id="disconnect-popup-noscript-container" style="display:none;" data-title="Terminate connection ">
	<div class="js-content-popup disconnect-popup action-popup">
		<span class="js-popup-title popup-title">
			Do you want to terminate connection:<br>
			<span class="js-wpi-token-block"></span>
		</span>
		<label class="js-channel-select-label hidden channel-select-label">
			Choose the channel for the message:<br>
			<select name="channel" class="js-channel-select public-channel-select default">
				@foreach( $websocketPublicChannels as $channelKey => $channel)
					<option value="{{ $channelKey }}" {{ $channelKey === WebsocketChannel::PUBLIC_GLOBAL ? 'selected' : '' }}>{{ $channel }}</option>
				@endforeach
			</select>
		</label>
		<div class="popup-buttons-wrapper">
			<button class="js-cancel-button popup-button inline-element cancel">Cancel</button>
			<button class="js-send-disconnect-button popup-button send inline-element">Disconnect</button>
		</div>
	</div>
	<script>
		$(document).ready(function () {
			const
				$popup = popup.getPopup(),
				$sendButton = $popup.find(".js-send-disconnect-button"),
				$cancelButton = $popup.find(".js-cancel-button"),
				$channelSelectLabel = $popup.find(".js-channel-select-label"),
				$channelSelect = $channelSelectLabel.find(".js-channel-select"),
				$title = $popup.find(".js-popup-title"),
				$wpiTokenBlock = $title.find(".js-wpi-token-block"),
				wpiToken = $popup.data("wpi-token"),
				isPublic = wpiToken === undefined
			;

			function send() {
				let request_data = {
					wpi_token: isPublic ? undefined : wpiToken,
					channel:   isPublic ? $channelSelect.val() : undefined
				};
				request.send(
					"{{ route('action.send-disconnect') }}",
					request_data,
					() => {
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

			if (isPublic) {
				$channelSelectLabel.removeClass("hidden");
				$title.addClass("hidden");
			} else {
				$wpiTokenBlock.text(wpiToken);
			}
		});
	</script>
</noscript>
