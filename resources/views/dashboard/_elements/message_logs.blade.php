@php
	use WebsocketClient\ValuesObject\WebsocketPayloadType;
	use App\ValuesObject\DashboardPageType;
@endphp
<div id="message-logs" class="js-content-popup">
	<div class="message-logs-content-parent-div side-by-side-table-div">
		<table class="table side-by-side-table">
			<caption>
				@include(
					'content.dashboard._elements.search',
					[
						'route' => route("content.message-logs"),
						'search' => $search ?? NULL,
						'sortField' => $sortField ?? NULL,
						'sortOrder' => $sortOrder ?? NULL,
						'currentPage' => $messageLogs->currentPage()
					]
				)
				Message Logs ( <span id="js-caption-first-record">{{(($messageLogs->currentPage() - 1) * $messageLogs->count()) + $messageLogs->count()?1:0}}</span>
				to <span id="js-caption-last-record">{{((($messageLogs->currentPage() - 1) * $messageLogs->perPage()) + $messageLogs->count())}}</span>
				from <span id="js-caption-total-records">{{ $messageLogs->total() }}</span> )
			</caption>
			<thead>
			<tr>
				<th scope="col">Status</th>
				<th scope="col">Platform</th>
				<th scope="col">WPI-Token</th>
				<th scope="col">Message</th>
				<th scope="col">Created_at</th>
				<th scope="col">Exception({{ $messageLogs->whereNotNull('exception')->count() }})</th>
			</tr>
			</thead>
			<tbody>
			@foreach( $messageLogs as $messageLog )
				<tr style="background-color:{{  $statusColorScheme[$messageLog->status] }}">
					<td>
						<div class="align-center">{{ $messageLog->status }}</div>
					</td>
					<td>
						<div class="align-center">{{ $messageLog->platform_title }}</div>
					</td>
					<td>
						<div class="align-center">{{ $messageLog->wpi_token }}</div>
					</td>
					<td>
						<div class="align-center">{{ $messageLog->message }}</div>
					</td>
					<td>
						<div class="align-center">{{ $messageLog->created_at }}</div>
					</td>
					<td>
						<div class="align-center">{{ $messageLog->exception }}</div>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	<script>
		$(document).ready(function () {
			const
				onOpenEvent = "{{ WebsocketPayloadType::WEBSOCKET_SERVER_DASHBOARD_CONNECTION_OPENED }}",
				onRegisteredEvent = "{{ WebsocketPayloadType::WEBSOCKET_SERVER_DASHBOARD_CONNECTION_REGISTERED }}",
				onCloseEvent = "{{ WebsocketPayloadType::WEBSOCKET_SERVER_DASHBOARD_CONNECTION_CLOSED }}",
				onMessageEvent = "{{ WebsocketPayloadType::WEBSOCKET_SERVER_DASHBOARD_MESSAGE_HANDLED }}",
				onActionEvent = "{{ WebsocketPayloadType::WEBSOCKET_SERVER_DASHBOARD_ACTION_CHANGED }}"
			;

			$(document).unbind(onOpenEvent);
			$(document).unbind(onRegisteredEvent);
			$(document).unbind(onCloseEvent);
			$(document).unbind(onMessageEvent);
			$(document).unbind(onActionEvent);

			@if( ! isset($search) && $messageLogs->currentPage() === 1)
			$(document).on(onMessageEvent, function (event) {
				let
					$paginator = $(".pagination"),
					$firstRecord = $("#js-caption-first-record"),
					$lastRecord = $("#js-caption-last-record"),
					$totalRecords = $("#js-caption-total-records"),
					$tbody = $("tbody"),
					currentRecordsCount = $paginator.data("current-records"),
					maxRecordsCount = $paginator.data("max-records")
				;
				$totalRecords.text(parseInt($totalRecords.text()) + 1);
				if (currentRecordsCount >= maxRecordsCount) {
					$tbody.find("tr").last().remove();
				} else {
					$paginator.data("current-records", currentRecordsCount + 1);
					$lastRecord.text(parseInt($lastRecord.text()) + 1);
				}
				$tbody.prepend($("<tr>").css("background-color", event.payload.rowColor).html(newConnectionHTML(event)));
			});

			function newConnectionHTML(event) {
				let exception = event.payload.exception ?? "";
				return `<td>
						<div class="align-center" >` + event.payload.status + `</div>
					</td>
					<td>
						<div class="align-center" >` + event.payload.platform + `</div>
					</td>
					<td>
						<div class="align-center" >` + event.payload.wpiToken + `</div>
					</td>
					<td>
						<div class="align-center" >` + event.payload.message + `</div>
					</td>
					<td>
						<div class="align-center" >` + event.payload.createdAt + `</div>
					</td>
					<td>
						<div class="align-center" >` + exception + `</div>
					</td>
					`;
			}
			@endif
		});
	</script>
	@include(
		'content.dashboard._elements.pagination',
		[
			'pageType' => DashboardPageType::MESSAGE_LOGS,
			'page' => $messageLogs,
			'search' => $search ?? NULL,
			'route' => route("content.message-logs"),
		]
	)
</div>
