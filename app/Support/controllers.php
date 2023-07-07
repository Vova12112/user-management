<?php

use App\ModelControllers\ConnectionController;
use App\ModelControllers\LogController;
use App\ModelControllers\PlatformController;
use App\ModelControllers\StatisticController;
use App\ModelControllers\UserController;
use WebsocketClient\Models\Controllers\WebsocketController;

if ( ! function_exists('connectionController')) {
	/*** @return ConnectionController */
	function connectionController(): ConnectionController
	{
		return app('ConnectionController');
	}
}
if ( ! function_exists('websocketController')) {
	/*** @return WebsocketController */
	function websocketController(): WebsocketController
	{
		return app('WebsocketController');
	}
}
if ( ! function_exists('platformController')) {
	/*** @return PlatformController */
	function platformController(): PlatformController
	{
		return app('PlatformController');
	}
}
if ( ! function_exists('logController')) {
	/*** @return LogController */
	function logController(): LogController
	{
		return app('LogController');
	}
}
if ( ! function_exists('statisticController')) {
	/*** @return StatisticController */
	function statisticController(): StatisticController
	{
		return app('StatisticController');
	}
}
if ( ! function_exists('userController')) {
	/*** @return UserController */
	function userController(): UserController
	{
		return app('UserController');
	}
}