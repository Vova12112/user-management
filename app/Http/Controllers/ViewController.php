<?php

namespace App\Http\Controllers;

use App\Constants\StatusColorScheme;
use App\Exceptions\User\UserInviteInvalidException;
use App\ModelControllers\UserController;
use App\Models\User;
use App\Models\UserInvite;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

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

	/**
	 * @param string $token
	 * @return Application|Factory|View|Response
	 */
	public function confirmRegistration(string $token)
	{
		try {
			$invite = (new UserController())->findUserInviteByToken($token);
//			if ($invite->getInvitedAt()->addMinutes(UserInvite::INVITE_LIFETIME_MIN) < now('UTC')) {
//				$invite->delete();
//				throw new UserInviteInvalidException();
//			}
		} catch (UserInviteInvalidException $e) {
			return response()->view('error.invalid_link');
		}
		return view(
			'auth.pages.confirm_registration',
			[
				'invite' => $invite,
			]
		);
	}
}
