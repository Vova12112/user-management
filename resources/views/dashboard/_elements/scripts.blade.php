<script type="text/javascript">
	$(document).ready(function () {
		const
			$dashboardMenu = $("#dashboard-menu"),
			$menuItems = $dashboardMenu.find(".menu-item")
		;
		let currentContent = "connections";
		$menuItems.on("click", function () {
			switch ($(this).data("menu-action")) {
				case "connections":
					$dashboardMenu.find(".menu-item .icon-32.active").removeClass("active");
					$(this).find(".icon-32").addClass("active");
					content.showAjax("{{ route("content.users") }}");
					currentContent = "connections";
					break;
				case "profile":
					$dashboardMenu.find(".menu-item .icon-32.active").removeClass("active");
					$(this).find(".icon-32").addClass("active");
					content.showAjax("{{ route("content.profile") }}");
					currentContent = "profile";
					break;

			}
		});
	});
</script>