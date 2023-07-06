<?php

namespace App\Http\Controllers;

use App\Constants\StatusColorScheme;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 * Class ViewController
 * @package App\Http\Controllers
 */
class ViewController extends Controller
{
	/*** @return Application|Factory|View */
	public function get()
	{
		return view(
			'dashboard._common',
			[
				'statusColorScheme' => StatusColorScheme::ALL,
				'users'             => User::all(),
			]
		);
	}
}
