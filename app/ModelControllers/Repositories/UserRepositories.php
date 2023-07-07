<?php

namespace App\ModelControllers\Repositories;

use App\Exceptions\User\UserInviteInvalidException;
use App\Exceptions\User\UserNotFoundException;
use App\Models\User;
use App\Models\UserInvite;
use Auth;
use DB;
use Illuminate\Support\Collection;

/**
 * Class UserRepositories
 * @package App\ModelControllers\Repositories
 */
class UserRepositories
{
	/**
	 * @param int $id
	 * @return User
	 * @throws UserNotFoundException
	 */
	public function findById(int $id): User
	{
		$user = User::where('id', '=', $id)->first();
		if ($user === NULL) {
			throw new UserNotFoundException();
		}
		return $user;
	}

	/**
	 * @param int $email
	 * @return User
	 * @throws UserNotFoundException
	 */
	public function findByEmail(int $email): User
	{
		$user = User::where('email', '=', $email)->first();
		if ($user === NULL) {
			throw new UserNotFoundException();
		}
		return $user;
	}

	/**
	 * @param string $token
	 * @return UserInvite
	 * @throws UserInviteInvalidException
	 */
	public function findUserInviteByToken(string $token): UserInvite
	{
		$invite = UserInvite::where('token', '=', $token)->first();
		if ($invite === NULL) {
			throw new UserInviteInvalidException();
		}
		return $invite;
	}

	/*** @return Collection */
	public function fetchUserInvites(): Collection
	{
		$query    = DB::table('user_invites as t1')
			->leftJoin('users as t2', 't2.id', 't1.invited_by_id')
			->select([
				't1.id',
				't1.token',
				't1.name as invite_name',
				't1.email',
				't1.invited_at',
				't1.updated_at',
			]);
		return $query->orderBy('t1.invited_at')->get();
	}

	/*** @return Collection */
	public function fetchUsers(): Collection
	{
		/*** @var User $authUser */
		$query    = DB::table('users as t1')
			->select([
				't1.id',
				't1.name',
				't1.email',
				't1.updated_at',
			])
			->whereNull('t1.deleted_at');
		return $query->orderBy('t1.name')->get();
	}
}