<script type="text/javascript">
	const
		loader = (function () {
			const $loaderWrapper = $("#loader-wrapper");

			function show() {
				$loaderWrapper.show();
			}

			function hide() {
				$loaderWrapper.hide();
			}

			return {
				show: show,
				hide: hide
			};
		})(),
		sorting = (function () {
			function addSorting($table, sortField, sortOrder, route, currentPage, perPage, search) {

				const $tableHeaders = $table.find("th");
				$tableHeaders.each(function () {
					let
						$column = $(this),
						newSortField = $column.data("column-name"),
						newSortOrder = "asc"
					;
					if ($column.data("column-name") !== undefined) {
						$column.addClass("sorting");

						if (sortField === newSortField) {
							sortOrder === "asc" ? $column.addClass("sorting-asc") : (sortOrder === "desc" ? $column.addClass("sorting-desc") : "");
						}
						$column.on("click", function () {
							if (sortField === newSortField) {
								sortOrder === "asc" ? newSortOrder = "desc" : (sortOrder === "desc" ? newSortField = "" : " ");
								newSortField === "" ? newSortOrder = "" : "";
							} else {
								newSortOrder = "asc";
							}
							content.showAjax(route, currentPage, perPage, search, newSortField, newSortOrder);
						});
					}
				});
			}

			return {
				addSorting: addSorting
			};
		})(),
		content = (function () {
			let
				customContainerClasses = "",
				defaultClasses = "js-dashboard-table-wrapper dashboard-table-wrapper",
				$contentWrapper
			;

			function show(content, styles = "") {
				$contentWrapper = $(".js-dashboard-table-wrapper");
				customContainerClasses = styles;
				$contentWrapper.html(content);
				$contentWrapper.attr("class", defaultClasses);
				$contentWrapper.addClass(customContainerClasses);
			}

			return {
				show:     show,
				showAjax: (route, currentPage = "1", perPage = "10", search = "", sortField = "", sortOrder = "") => {
					if ( ! websocket.isConnectionInit()) {
						websocket.connect();
					}
					request.send(
						route,
						{
							"currentPage": currentPage,
							"perPage":     perPage,
							"search":      search,
							"sortField":   sortField,
							"sortOrder":   sortOrder
						},
						function (response) {
							console.log(response);
							show(response.content, response.styles);
						});
				}
			};
		})(),
		popup = (function () {
			const
				$popupWrapper = $("#popup-wrapper"),
				$popupContainer = $popupWrapper.find(".popup-container"),
				$popupHeader = $popupWrapper.find(".popup-header"),
				$popupTitle = $popupHeader.find("span"),
				$popupContent = $popupWrapper.find(".popup-content")
			;
			let
				isShown = false,
				customContainerClasses = ""
			;

			function show(title, content, styles = "") {
				customContainerClasses = styles;
				$popupTitle.html(title);
				$popupContent.html(content);
				$popupContainer.addClass(customContainerClasses);
				$popupWrapper.removeClass("hidden");
			}

			function hide() {
				$popupWrapper.addClass("hidden");
				$popupTitle.html("");
				$popupContent.html("");
				$popupContainer.removeClass(customContainerClasses);
				customContainerClasses = "";
				$popupWrapper.removeData();
			}

			return {
				show:         show,
				showNoscript: (title, $noscript, styles = "") => {
					if ($($noscript).length === 0) {
						return;
					}
					show(title, $noscript.text(), styles);
				},
				showAjax:     (route) => {
					request.send(
						route,
						{},
						function (response) {
							show(response.title, response.content, response.styles);
						});
				},
				hide:         hide,
				getPopup:     () => {
					return $popupWrapper;
				},
				isShown:      () => {
					return isShown;
				}
			};
		})(),
		status = (function () {
			let
				$statusCircle = $(".server-status"),
				customContainerClasses = "active connecting disconnected"
			;
			let currentStatus = "none";
			$(document).ready(function () {
				$statusCircle = $(".server-status");
			});

			function setActive() {
				$statusCircle.removeClass(customContainerClasses);
				$statusCircle.addClass("active");
				currentStatus = "active";
			}

			function setConnecting() {
				$statusCircle.removeClass(customContainerClasses);
				$statusCircle.addClass("connecting");
				currentStatus = "connecting";
			}

			function setDisconnected() {
				$statusCircle.removeClass(customContainerClasses);
				$statusCircle.addClass("disconnected");
				currentStatus = "disconnected";
			}

			return {
				setActive:       setActive,
				setConnecting:   setConnecting,
				setDisconnected: setDisconnected,
				getStatus:       () => {
					return currentStatus;
				}
			};
		})(),
		request = (function () {
			function sendAjax(
				route,
				data,
				successFunction = function (response) {
				},
				failFunction = function (response) {
					alert("Ajax failed! Exception:" + response.exception);
					popup.hide();
				}
			) {
				loader.show();
				$.ajaxSetup({
					headers: {
						"X-CSRF-TOKEN": $("input[name=_token]").val()
					}
				});
				$.ajax({
					type:    "POST",
					url:     route,
					data:    data,
					success: function (response) {
						switch (response.ack) {
							case "success":
								successFunction(response);
								break;
							case "reload":
								window.location.reload();
								break;
							case "fail":
							default:
								console.log("Ajax failed: ");
								console.log(response);
								failFunction(response);
								break;
						}
						loader.hide();
					},
					error:   function (response) {
						@if ( env('APP_ENV')  === 'production' )
						window.location.reload();
						@else
						alert(response.responseJSON.message);
						console.log("Ajax error! " + "Data: ");
						console.log(response);
						loader.hide();
						@endif
					}
				});
			}

			return {
				send: function (route, data, successFunction, failFunction) {
					sendAjax(route, data, successFunction, failFunction);
				}
			};
		}());

	function inputValidationHandler($textField, $button = undefined) {
		$textField.addClass("invalid-input");
		$textField.on("keyup", function () {
			if ($button !== undefined) {
				$button.prop("disabled", false);
			}
			$textField.removeClass("invalid-input");
			$textField.unbind("keyup");
		});
	}

	function showActionPopup($button, wpiToken = undefined) {
		let $noscript = undefined;
		if ($button.hasClass("js-debug-btn")) {
			$noscript = $("#debug-popup-noscript-container");
		} else if ($button.hasClass("js-notif-btn")) {
			$noscript = $("#send-notification-popup-noscript-container");
		} else if ($button.hasClass("js-reload-btn")) {
			$noscript = $("#reload-popup-noscript-container");
		} else if ($button.hasClass("js-disconnect-btn")) {
			$noscript = $("#disconnect-popup-noscript-container");
		} else {
			return;
		}
		popup.showNoscript($noscript.data("title"), $noscript);
		if (wpiToken !== undefined) {
			popup.getPopup().data("wpi-token", wpiToken);
		}
	}
</script>