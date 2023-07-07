<?php

namespace App\Http\Controllers;


use App\ModelControllers\UserController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

/**
 * Class UserManagementActionController
 * @package App\Http\Controllers
 */
class UserManagementActionController
{

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function deleteInvitation(Request $request): JsonResponse
	{
		try {
				$invite = (new UserController())->findUserInviteByToken($request->get('token'));
				$invite->delete();
				return response()->json(['ack' => 'success', 'msg' => 'Invitation is deleted!']);
		} catch (Exception | Throwable $e) {
			return response()->json(['ack' => 'fail', 'exception' => $e->getMessage()]);
		}
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function deleteUser(Request $request): JsonResponse
	{
		try {
				$user = (new UserController())->findById($request->get('id'));
				$user->delete();
				return response()->json(['ack' => 'success', 'msg' => 'User is deleted!']);
		} catch (Exception | Throwable $e) {
			return response()->json(['ack' => 'fail', 'exception' => $e->getMessage()]);
		}
	}

}