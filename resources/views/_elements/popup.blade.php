<div id="popup-wrapper" class="hidden">
	<div class="popup-container">
		<div class="popup-header">
			<span></span>
			<div class=" js-popup-close close-btn">✕</div>
		</div>
		<div class="popup-content"></div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		const
			$popup = popup.getPopup(),
			$closeButton = $popup.find(".js-popup-close")
			;

		$closeButton.on("click", function (event) {
			event.preventDefault();
			popup.hide();
		});
	});
</script>