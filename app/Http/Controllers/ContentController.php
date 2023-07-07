<?php

namespace App\Http\Controllers;


use App\ModelControllers\UserController;
use App\Models\User;
use App\ValuesObject\Statuses\StatusColorScheme;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Class ContentController
 * @package App\Http\Controllers
 */
class ContentController extends Controller
{
	public function getMessageLogs(Request $request): JsonResponse
	{
		try {
			$messageLogs = logController()->fetchMessageLogs($request->get('currentPage'), $request->get('perPage') ?? 10, $request->get('search'));
			return response()->json([
				'ack'     => 'success',
				'msg'     => 'Message logs fetched successfully',
				'content' => view(
					'content.dashboard._elements.message_logs',
					[
						'messageLogs'       => $messageLogs,
						'search'            => $request->get('search'),
						'statusColorScheme' => StatusColorScheme::ALL,
					]
				)->render(),
				'styles'  => "message-logs-content",
			]);
		} catch (Exception $e) {
			return response()->json(['ack' => 'fail', 'exception' => $e->getMessage()]);
		}
	}

	/*** @return JsonResponse */
	public function getUserManagementView(): JsonResponse
	{
		try {
			return response()->json([
				'ack'     => 'success',
				'msg'     => 'User management view fetched successfully',
				'content' => view(
					'dashboard._elements.users',
					[
						//						'authUser'    => Auth::user(),
						//						'userInvites' => (new UserController)->fetchUserInvites(),
						'statusColorScheme' => \App\Constants\StatusColorScheme::ALL,
						'users'       => User::all(),
					]
				)->render(),
				'styles'  => 'user-management-content',
			]);
		} catch (Exception $e) {
			return response()->json(['ack' => 'fail', 'exception' => $e->getMessage()]);
		}
	}

	/*** @return JsonResponse */
	public function getProfile(): JsonResponse
	{
		try {
			return response()->json([
				'ack'     => 'success',
				'msg'     => 'Profile fetched successfully',
				'content' => view(
					'dashboard._elements.user_profile',
					[
						'authUser' => Auth::user(),
					]
				)->render(),
				'styles'  => 'profile-content',
			]);
		} catch (Exception $e) {
			return response()->json(['ack' => 'fail', 'exception' => $e->getMessage()]);
		}
	}
}

