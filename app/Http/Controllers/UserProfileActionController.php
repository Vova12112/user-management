<?php

namespace App\Http\Controllers;

use App\Exceptions\User\UserInviteInvalidException;
use App\ModelControllers\UserController;
use App\Models\User;
use App\Models\UserInvite;
use App\ValuesObject\Factories\UserFactory;
use App\ValuesObject\Validator\DefaultValidator;
use App\ValuesObject\Validator\IsValidPassword;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

/**
 * Class UserProfileActionController
 * @package App\Http\Controllers
 */
class UserProfileActionController
{

	/**
	 * @param Request $request
	 * @return JsonResponse|NULL
	 */
	public function changePassword(Request $request): ?JsonResponse
	{
		try {
			/*** @var User $authUser */
			$authUser  = Auth::user();
			$validator = DefaultValidator::getPasswordValidator($request, $authUser);
			if ($validator->fails()) {
				return response()->json(
					[
						'ack'       => 'fail',
						'validator' => $validator->errors(),
					]
				);
			}
			$authUser->updatePassword($request->get('new_password'));
			return response()->json(['ack' => 'success', 'message' => 'Password changed successfully!']);
		} catch (Exception $e) {
			return response()->json(['ack' => 'fail', 'exception' => $e->getMessage()]);
		}
	}
	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function inviteNewUser(Request $request): JsonResponse
	{
		try {
			/*** @var User $authUser */
			$authUser = Auth::user();
//			if ( ! $authUser->isRoot()) {
//				return response()->json(['ack' => 'fail']);
//			}
			$validator = Validator::make(
				$request->all(),
				[
					'name'     => 'required|string|min:1|max:70',
					'email'    => 'required|email:filter|max:200|unique:users,email|unique:user_invites,email',
					'password' => 'required|string',
				]
			);
			if ($validator->fails()) {
				return response()->json([
					'ack'       => 'fail',
					'validator' => $validator->errors(),
					'exception' => json_encode($validator->errors(), JSON_THROW_ON_ERROR),
				]);
			}
			$password = $request->get('password');
//			if ( ! Hash::check($password, $authUser->getPassword())) {
//				return response()->json([
//					'ack'       => 'fail',
//					'validator' => ['password' => 'Password is not correct'],
//				]);
//			}
			$invite = UserFactory::invite($authUser, $request->get('name'), $request->get('email'));
			return response()->json(['ack' => 'success', 'link' => route('invite', ['token' => $invite->getToken()])]);
		} catch (Exception $e) {
			return response()->json(['ack' => 'fail', 'exception' => $e->getMessage()]);
		}
	}

	/**
	 * @param Request $request
	 * @param string  $token
	 * @return RedirectResponse|void
	 */
	public function completeRegistration(Request $request, string $token)
	{
		try {
			$invite = (new UserController())->findUserInviteByToken($token);
			if ($invite->getInvitedAt()->addMinutes(UserInvite::INVITE_LIFETIME_MIN) < now('UTC')) {
				throw new UserInviteInvalidException();
			}
			$validator = Validator::make(
				$request->all(),
				[
					'name'             => 'nullable|string|min:1|max:70',
					'password'         => new IsValidPassword(),
					'confirm_password' => 'required|same:password',
				]
			);
			if ($validator->fails()) {
				return back()->with('error', $validator->errors()->first())->withInput();
			}
            UserFactory::create($invite, $request->get('password'), $request->get('name'));
//			$invite->delete();
			return response()->redirectToRoute('login', ['login' => $invite->getEmail()]);
		} catch (Exception $e) {
//			return response()->json(['error' => $e->getMessage()]);
			abort(419);
		}
	}
}
