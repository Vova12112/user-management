@php
    use App\ValuesObject\DashboardPageType;
    use App\ValuesObject\Statuses\ConnectionStatus;
    use WebsocketClient\ValuesObject\WebsocketPayloadType;
    use Carbon\Carbon;
@endphp
<table>
    <thead>
    <tr>
        <th scope="col" data-column-name="name">Name</th>
        <th scope="col" data-column-name="email">Email</th>
        <th scope="col" data-column-name="updated-at">Updated At</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach( $users as $user)
        <tr style="background-color:{{ $statusColorScheme['light'] }}" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
            <td class="js-name align-center">{{ $user->name }}</td>
            <td class="js-email align-center">{{ $user->email }}</td>
            <td class="js-updated-at align-center">{{ $user->updated_at->toDateTimeString() }}</td>
            <td class="js-action align-center">
                <div class="js-edit-user-btn js-action-btn action-btn icon-28 with-hover"></div>
                <div class="js-delete-user-btn js-action-btn action-btn icon-28 with-hover disconnect"></div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<script type="text/javascript">
	$(document).ready(function () {
		const
			$dashboardContent = $("#dashboard-content"),
			$connectionsTableWrapper = $dashboardContent.find(".js-dashboard-table-wrapper"),
			$connectionActions = $connectionsTableWrapper.find(".js-action-btn")
		;

		$connectionActions.on("click", function () {
			showActionPopup($(this), $(this).closest("tr").data("user-id"), $(this).closest("tr").data("user-name"));
		});
	});
</script>
