@php
	use WebsocketClient\ValuesObject\WebsocketChannel;
@endphp
@extends('index')

@section('page-title')
	User Menegement
@endsection

@push('page-style-links')
	<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('page-content')
	<div id="dashboard-wrapper">
		<div id="dashboard-menu">
			<br>
			<div class="menu-item" data-menu-action="connections">
				<i class="js-menu-item-connections icon-32 dashboard with-hover active"></i>
			</div>
			<div class="menu-item bottom" data-menu-action="profile" style="margin-left: 5px;">
				<i class="icon-32 profile with-hover"></i>
			</div>
{{--			<div class="menu-item bottom" data-menu-action="reload">--}}
{{--				<i class="icon-32 reload with-hover"></i>--}}
{{--			</div>--}}
		</div>
		<div id="dashboard-content">
			<div class="dashboard-title">User Menegement</div>
			<div class="js-dashboard-table-wrapper dashboard-table-wrapper">
                @include('dashboard._elements.users')
			</div>
		</div>
		@include('dashboard._elements.scripts')
		@include('dashboard._elements.popup.action_delete_user')
		@include('dashboard._elements.popup.action_debug')
	</div>
@endsection